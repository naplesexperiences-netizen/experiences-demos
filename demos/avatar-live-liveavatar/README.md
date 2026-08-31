# Avatar Live — concierge conversazionale (LiveAvatar / HeyGen)

Demo di avatar fotorealistico in streaming che risponde a voce alle domande dei
visitatori direttamente da una pagina web.

- **Avatar**: `c72a9099-84b9-4d5d-98f4-a19ba131e654`
- **Contesto (knowledge base)**: `06d11e63-ff70-4c2b-b0a3-aad3f1134013`
- **Lingua**: italiano
- **SDK**: [`@heygen/liveavatar-web-sdk`](https://www.npmjs.com/package/@heygen/liveavatar-web-sdk) via CDN

## Come funziona

```
browser (index.html)                token server (Worker)          LiveAvatar
        │                                    │                          │
        │  POST /token ─────────────────────▶│                          │
        │                                    │  POST /v1/sessions/token │
        │                                    │  X-API-KEY: ••••  ──────▶│
        │  ◀───────────── { session_token }  │◀──── session_token ──────│
        │                                                               │
        │  new LiveAvatarSession(session_token).start()  ───────────────▶│
        │  ◀═════════ video + audio WebRTC (LiveKit) ═══════════════════│
```

Il browser **non vede mai la API key**. Il token server la custodisce, crea la
sessione e restituisce solo il `session_token`, che è a uso singolo e a vita
breve. Senza questo passaggio la chiave sarebbe leggibile nel sorgente della
pagina e chiunque potrebbe consumare minuti a pagamento sull'account.

## Setup

### 1. Pubblica il token server

```bash
cd demos/avatar-live-liveavatar/token-server
npm install -g wrangler          # se non già installato
wrangler login
wrangler secret put LIVEAVATAR_API_KEY   # incolla la chiave quando richiesto
wrangler deploy
```

Il deploy stampa l'URL del Worker, es.
`https://liveavatar-token.<tuo-subdomain>.workers.dev`.

Verifica che `ALLOWED_ORIGINS` in `wrangler.toml` contenga il dominio da cui
servirai la pagina. È l'unica cosa che impedisce a un sito terzo di usare il
tuo endpoint per generare sessioni a tue spese.

### 2. Collega la pagina al token server

In `index.html`, nel blocco `CONFIG` in fondo al file:

```js
tokenEndpoint: "https://liveavatar-token.<tuo-subdomain>.workers.dev",
```

### 3. Prova in locale

```bash
cd demos/avatar-live-liveavatar
python3 -m http.server 8000
# apri http://localhost:8000
```

Il microfono e la webcam richiedono un contesto sicuro: `localhost` va bene,
un IP di rete locale in `http://` no.

## Prova rapida senza token server

Se vuoi vedere l'avatar funzionare prima di pubblicare il Worker, genera un
token a mano e incollalo nel riquadro giallo della pagina (resta in
`sessionStorage`, non viene salvato da nessuna parte):

```bash
curl -s -X POST https://api.liveavatar.com/v1/sessions/token \
  -H "X-API-KEY: $LIVEAVATAR_API_KEY" \
  -H "content-type: application/json" \
  -d '{
    "mode": "FULL",
    "avatar_id": "c72a9099-84b9-4d5d-98f4-a19ba131e654",
    "avatar_persona": {
      "context_id": "06d11e63-ff70-4c2b-b0a3-aad3f1134013",
      "language": "it"
    },
    "interactivity_type": "CONVERSATIONAL",
    "max_session_duration": 300
  }' | python3 -m json.tool
```

Il token è nel campo `data.session_token`. Serve per **una sola** sessione:
per una nuova prova bisogna rigenerarlo.

## Funzioni della pagina

| Elemento | Comportamento |
|---|---|
| **Avvia sessione** | Richiede il token, apre lo stream WebRTC, mostra l'avatar |
| **Microfono** | Attiva/disattiva la voce del visitatore (parte muto) |
| **Interrompi risposta** | `session.interrupt()` — taglia la risposta in corso |
| **Campo di testo** | `session.message(testo)` — l'agente risponde a voce |
| **Trascrizione** | Domande e risposte in tempo reale, aggiornate a chunk |
| **Durata / costo** | Contatore live con stima a `CONFIG.costPerMinute` |

## Note operative

- **La sessione costa mentre è aperta**, anche in silenzio. `MAX_SESSION_SECONDS`
  (default 300) è il tetto lato server: alla scadenza LiveAvatar chiude da solo.
- Il `keepAlive` parte ogni 30 secondi; senza, il server chiude la sessione per
  inattività.
- **Cosa risponde l'avatar dipende dal contesto**, non da questa pagina: per
  cambiare tono, informazioni o regole si modifica il contesto
  `06d11e63-…` nella dashboard LiveAvatar.
- L'autoplay con audio richiede un gesto dell'utente: per questo lo stream parte
  solo dopo il clic su "Avvia sessione".
- La versione dell'SDK è **pinnata** (`@0.0.18`) nel tag `<script>`: è ancora
  `0.0.x`, quindi le minor possono rompere l'API.

## Riferimenti

- [Documentazione LiveAvatar](https://docs.liveavatar.com)
- [API — Create Session Token](https://docs.liveavatar.com/api-reference/sessions/create-session-token)
- [Web SDK su npm](https://www.npmjs.com/package/@heygen/liveavatar-web-sdk)
