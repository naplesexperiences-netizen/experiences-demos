/**
 * Token server per LiveAvatar (HeyGen) — Cloudflare Worker.
 *
 * La pagina demo è statica (GitHub Pages) e non può custodire la API key:
 * chiunque aprisse il sorgente potrebbe consumare minuti a pagamento.
 * Questo Worker è l'unico punto che conosce la chiave: riceve una richiesta
 * dal browser, crea la sessione su LiveAvatar e restituisce solo il
 * session_token, che è a uso singolo e a vita breve.
 *
 * Variabili richieste (wrangler.toml + `wrangler secret put`):
 *   LIVEAVATAR_API_KEY  (secret)  chiave API dell'account LiveAvatar
 *   AVATAR_ID           (var)     id dell'avatar
 *   CONTEXT_ID          (var)     id del contesto / knowledge base
 *   ALLOWED_ORIGINS     (var)     origini autorizzate, separate da virgola
 *   LANGUAGE            (var)     codice lingua ISO, default "it"
 *   MAX_SESSION_SECONDS (var)     tetto di durata per sessione, default 300
 */

const LIVEAVATAR_TOKEN_URL = "https://api.liveavatar.com/v1/sessions/token";
const DEFAULT_MAX_SESSION_SECONDS = 300;

export default {
    async fetch(request, env) {
        const origin = request.headers.get("Origin") || "";
        const allowed = allowedOrigins(env);

        // Se l'allowlist è vuota il Worker resta chiuso: meglio un errore
        // evidente che un endpoint che chiunque può usare per generare minuti.
        if (allowed.length === 0) {
            return json({ error: "ALLOWED_ORIGINS non configurato." }, 500, {});
        }

        const cors = allowed.includes(origin)
            ? {
                  "Access-Control-Allow-Origin": origin,
                  "Access-Control-Allow-Methods": "POST, OPTIONS",
                  "Access-Control-Allow-Headers": "Content-Type",
                  "Access-Control-Max-Age": "86400",
                  Vary: "Origin",
              }
            : {};

        if (request.method === "OPTIONS") {
            return new Response(null, { status: 204, headers: cors });
        }
        if (request.method !== "POST") {
            return json({ error: "Method not allowed" }, 405, cors);
        }
        if (!allowed.includes(origin)) {
            return json({ error: "Origin non autorizzata." }, 403, {});
        }
        if (!env.LIVEAVATAR_API_KEY) {
            return json({ error: "LIVEAVATAR_API_KEY non configurata." }, 500, cors);
        }

        const maxSessionSeconds = Number(env.MAX_SESSION_SECONDS) || DEFAULT_MAX_SESSION_SECONDS;

        // avatar_id e context_id arrivano dalle variabili del Worker, non dal
        // corpo della richiesta: il client non deve poter dirottare la
        // sessione su un altro avatar o su un'altra knowledge base.
        const payload = {
            mode: "FULL",
            avatar_id: env.AVATAR_ID,
            avatar_persona: {
                context_id: env.CONTEXT_ID,
                language: env.LANGUAGE || "it",
            },
            interactivity_type: "CONVERSATIONAL",
            max_session_duration: maxSessionSeconds,
        };

        let upstream;
        try {
            upstream = await fetch(LIVEAVATAR_TOKEN_URL, {
                method: "POST",
                headers: {
                    accept: "application/json",
                    "content-type": "application/json",
                    "X-API-KEY": env.LIVEAVATAR_API_KEY,
                },
                body: JSON.stringify(payload),
            });
        } catch (error) {
            return json({ error: "LiveAvatar non raggiungibile." }, 502, cors);
        }

        const body = await upstream.text();
        if (!upstream.ok) {
            console.error("LiveAvatar token error", upstream.status, body);
            return json({ error: "LiveAvatar ha rifiutato la richiesta.", status: upstream.status }, 502, cors);
        }

        let parsed;
        try {
            parsed = JSON.parse(body);
        } catch (error) {
            return json({ error: "Risposta LiveAvatar non valida." }, 502, cors);
        }

        const sessionToken = parsed?.data?.session_token;
        if (!sessionToken) {
            console.error("LiveAvatar token mancante", body);
            return json({ error: "session_token assente nella risposta." }, 502, cors);
        }

        return json({ session_token: sessionToken, session_id: parsed?.data?.session_id }, 200, cors);
    },
};

function allowedOrigins(env) {
    return (env.ALLOWED_ORIGINS || "")
        .split(",")
        .map((value) => value.trim())
        .filter(Boolean);
}

function json(data, status, cors) {
    return new Response(JSON.stringify(data), {
        status,
        headers: { "Content-Type": "application/json", "Cache-Control": "no-store", ...cors },
    });
}
