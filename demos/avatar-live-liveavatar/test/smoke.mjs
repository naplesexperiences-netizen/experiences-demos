/**
 * Smoke test della pagina demo, senza toccare LiveAvatar.
 *
 * Sostituisce l'SDK caricato da CDN con lo stub in stub-sdk.js e pilota la
 * pagina con un browser vero: serve a sapere se la UI è ancora integra dopo
 * una modifica, senza consumare minuti a pagamento.
 *
 *   npm install playwright && npx playwright install chromium
 *   node test/smoke.mjs
 *
 * Esce con codice 1 se un controllo fallisce.
 */
import { chromium } from "playwright";
import http from "node:http";
import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";

const ROOT = path.dirname(path.dirname(fileURLToPath(import.meta.url)));
const STUB = await fs.readFile(path.join(ROOT, "test", "stub-sdk.js"), "utf8");

const TYPES = { ".html": "text/html", ".js": "application/javascript", ".css": "text/css" };

const server = http.createServer(async (req, res) => {
    const name = (req.url === "/" ? "/index.html" : req.url).split("?")[0];
    try {
        const file = path.join(ROOT, path.normalize(name).replace(/^(\.\.[/\\])+/, ""));
        const body = await fs.readFile(file);
        res.writeHead(200, { "Content-Type": TYPES[path.extname(file)] || "application/octet-stream" });
        res.end(body);
    } catch {
        res.writeHead(404).end("not found");
    }
});
await new Promise((resolve) => server.listen(0, "127.0.0.1", resolve));
const base = `http://127.0.0.1:${server.address().port}`;

let failures = 0;
function check(label, actual, expected) {
    const ok = JSON.stringify(actual) === JSON.stringify(expected);
    if (!ok) failures += 1;
    console.log(`${ok ? "  ok  " : " FAIL "} ${label}${ok ? "" : ` — atteso ${JSON.stringify(expected)}, ottenuto ${JSON.stringify(actual)}`}`);
}

const browser = await chromium.launch();

// --- 1. l'SDK non carica: la pagina lo deve dire, non morire in silenzio ---
console.log("\nSDK non disponibile");
{
    const page = await browser.newPage();
    await page.route((url) => url.hostname === "cdn.jsdelivr.net", (route) => route.abort());
    await page.goto(base, { waitUntil: "domcontentloaded" });
    await page.waitForSelector("#errorNotice:not([hidden])");
    check("pulsante avvio bloccato", await page.locator("#btnStart").isDisabled(), true);
    check("stato segnalato", await page.locator("#statusText").textContent(), "SDK non disponibile");
    await page.close();
}

// --- 2. flusso completo con SDK simulato ---
console.log("\nFlusso completo");
const page = await browser.newPage();
const errors = [];
page.on("pageerror", (e) => errors.push(e.message));
page.on("console", (m) => { if (m.type() === "error") errors.push(m.text()); });
await page.route(
    (url) => url.hostname === "cdn.jsdelivr.net",
    (route) => route.fulfill({ status: 200, contentType: "application/javascript", body: STUB })
);
await page.goto(base, { waitUntil: "domcontentloaded" });

check("avviso di configurazione mostrato", await page.locator("#setupNotice").isVisible(), true);
check("domande suggerite presenti", await page.locator(".chip").count(), 4);
check("suggerimenti disattivi a sessione chiusa", await page.locator(".chip").first().isDisabled(), true);

await page.locator("#devToken").fill("token.di.prova");
await page.locator("#devTokenSave").click();
check("avviso nascosto dopo il token", await page.locator("#setupNotice").isVisible(), false);

await page.locator("#btnStart").click();
await page.waitForFunction(() => document.getElementById("statusText").textContent === "In diretta");
check("stream agganciato al <video>", await page.evaluate(() => window.__stub.attached), true);
check("overlay nascosto", await page.locator("#overlay").isVisible(), false);
check("timer visibile", await page.locator("#timer").isVisible(), true);
check("qualita rete mostrata", await page.locator("#metaQuality").textContent(), "EXCELLENT");
check("campo domanda attivo", await page.locator("#input").isDisabled(), false);

await page.locator("#btnMic").click();
check("microfono attivabile", await page.locator("#btnMic").textContent(), "Microfono attivo");
await page.locator("#btnMic").click();
check("microfono richiudibile", await page.locator("#btnMic").textContent(), "Microfono off");

await page.locator(".chip").first().click();
await page.waitForFunction(() => document.querySelectorAll(".msg.avatar").length === 1);
check("domanda inviata all'agente", await page.evaluate(() => window.__stub.lastMessage), "Che tipo di camere avete?");
check("una sola bolla per la risposta a chunk", await page.locator(".msg.avatar").count(), 1);
check("risposta consolidata", await page.locator(".msg.avatar").first().textContent(), "Certo, le nostre camere sono tutte vista mare.");
check("nessuna bolla provvisoria residua", await page.locator(".msg.pending").count(), 0);

await page.locator("#input").fill("Avete parcheggio?");
await page.locator("#btnSend").click();
await page.waitForFunction(() => document.querySelectorAll(".msg.avatar").length === 2);
check("campo svuotato dopo l'invio", await page.locator("#input").inputValue(), "");
check("due domande in trascrizione", await page.locator(".msg.user").count(), 2);

await page.locator("#btnStop2").click();
check("interruzione inoltrata", await page.evaluate(() => window.__stub.interrupted), true);

await page.setViewportSize({ width: 390, height: 844 });
check(
    "nessuno scroll orizzontale su mobile",
    await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth + 1),
    false
);
await page.setViewportSize({ width: 1280, height: 900 });

await page.locator("#btnStop").click();
await page.waitForFunction(() => document.getElementById("statusText").textContent === "Non connesso");
check("avvio riabilitato dopo la chiusura", await page.locator("#btnStart").isDisabled(), false);
check("campo bloccato a sessione chiusa", await page.locator("#input").isDisabled(), true);
check("chiusura annotata in trascrizione", await page.locator(".msg.system").count(), 1);

check("nessun errore in console", errors, []);

await browser.close();
server.close();

console.log(failures === 0 ? "\nTutti i controlli superati.\n" : `\n${failures} controlli falliti.\n`);
process.exit(failures === 0 ? 0 : 1);
