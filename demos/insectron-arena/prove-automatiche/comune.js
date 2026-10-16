/* I percorsi, in un posto solo.
   Le suite sono nate dentro una cartella di lavoro con percorsi assoluti
   scritti a mano; qui diventano relativi alla posizione di questo file, cosi'
   la batteria gira su qualsiasi macchina e da qualsiasi clone. */
const path = require("path");
const fs = require("fs");

/* Playwright puo' stare nei node_modules del progetto, in quelli globali, o in
   un percorso dichiarato a mano. Si prova in quest'ordine e, se non c'e', si
   dice che cosa installare invece di morire con uno stack trace. */
function playwright(){
  const posti = [
    process.env.PLAYWRIGHT_PATH,
    "playwright",
    "/opt/node22/lib/node_modules/playwright",
    "/usr/lib/node_modules/playwright",
    "/usr/local/lib/node_modules/playwright"
  ].filter(Boolean);
  for (const p of posti){
    try { return require(p); } catch(e){ /* si prova il prossimo */ }
  }
  console.error(
    "\nPlaywright non trovato. Installalo con:\n" +
    "  npm i -D playwright && npx playwright install chromium\n" +
    "oppure indica dove si trova:\n" +
    "  PLAYWRIGHT_PATH=/percorso/a/playwright sh batteria.sh\n");
  process.exit(1);
}

const DEMO = path.resolve(__dirname, "..");        /* la cartella del gioco */
const url = n => "file://" + path.join(DEMO, n);

/* Alcune prove hanno bisogno di un'origine vera (localStorage, service
   worker): batteria.sh alza un server statico e passa la porta qui. */
const PORTA = process.env.INSECTRON_PORTA || "8232";
const SERVITO = "http://127.0.0.1:" + PORTA + "/";

/* La radice servita e' quella del sito pubblicato, che oltre al gioco ha
   manifest, service worker e icone. Se non c'e' si serve la cartella della
   demo: tutto gira lo stesso tranne le prove sul sito installabile (pwa.js),
   che quei file li cercano davvero. */
function radiceSito(){
  if (process.env.INSECTRON_SITO) return process.env.INSECTRON_SITO;
  const candidati = [
    path.resolve(DEMO, "../../../insectorarena"),  /* i due repository affiancati */
    path.resolve(DEMO, "../insectorarena"),
    path.resolve(DEMO, "../sito")                  /* il pacchetto zip */
  ];
  for (const c of candidati)
    if (fs.existsSync(path.join(c, "sw.js"))) return c;
  return DEMO;
}

module.exports = {
  chromium: playwright().chromium,
  DEMO, RADICE_SITO: radiceSito(), PORTA, SERVITO,
  GIOCA: url("gioca.html"),
  INDEX: url("index.html"),
  PRIVACY: url("privacy.html"),
  CREA_DEMO: path.join(DEMO, "crea-demo.sh")
};
