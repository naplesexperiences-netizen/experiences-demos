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

### 3. Prova che funzioni

Vedi **Verificare che funzioni** qui sotto: si parte dallo smoke test senza
API key e si arriva alla sessione reale.

## Verificare che funzioni

Tre livelli, dal più economico al più realistico.

### 1. Smoke test della UI — nessuna API key, nessun costo

Sostituisce l'SDK con uno stub e pilota la pagina con un browser vero:
dice se l'interfaccia è integra, non se LiveAvatar risponde.

```bash
cd demos/avatar-live-liveavatar
npm install playwright && npx playwright install chromium
node test/smoke.mjs
```

Stampa un elenco di controlli e esce con codice 1 se qualcosa si è rotto.
Da rilanciare dopo ogni modifica a `index.html`.

### 2. Sessione reale con token generato a mano

Serve la API key dalla dashboard LiveAvatar. Genera un token:

```bash
export LIVEAVATAR_API_KEY="la-tua-chiave"

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

Se ottieni `data.session_token`, **avatar, contesto e chiave sono validi**:
metà della verifica è già fatta senza aprire il browser.

Poi servi la pagina e incolla il token nel riquadro giallo:

```bash
python3 -m http.server 8000     # da questa cartella
# apri http://localhost:8000
```

`localhost` è indispensabile: microfono e WebRTC non funzionano da `file://`.

Cosa deve succedere, in ordine:

| Passo | Atteso | Se non succede |
|---|---|---|
| Clic su "Avvia sessione" | spinner, poi il video dell'avatar | apri la console: errore su `/v1/sessions/start` = token già usato o scaduto |
| Stato in alto a sinistra | pallino verde, "In diretta" | resta "Connessione" = il browser non completa il WebRTC (rete o firewall) |
| Scrivi una domanda e invia | l'avatar parla e la risposta compare in trascrizione | silenzio ma trascrizione presente = audio bloccato dall'autoplay, clicca sulla pagina |
| Clic su "Microfono" e parla | la tua frase compare come bolla blu | nessuna bolla = permesso microfono negato |
| Clic su "Interrompi" | l'avatar si zittisce subito | — |

Ogni token vale **una sola** sessione: per una seconda prova rigenera il token.

### 3. Verifica end-to-end col token server

Dopo `wrangler deploy`, controlla che il Worker risponda e che l'allowlist
faccia il suo lavoro:

```bash
# origine autorizzata -> deve restituire session_token
curl -s -X POST https://liveavatar-token.<subdomain>.workers.dev \
  -H "Origin: https://naplesexperiences-netizen.github.io" \
  -H "content-type: application/json" -d '{}'

# origine non autorizzata -> deve restituire 403
curl -s -o /dev/null -w "%{http_code}\n" \
  -X POST https://liveavatar-token.<subdomain>.workers.dev \
  -H "Origin: https://sito-a-caso.example" \
  -H "content-type: application/json" -d '{}'
```

Il secondo comando **deve** dare `403`. Se dà `200` l'endpoint è aperto a
chiunque e le sessioni le paghi tu: controlla `ALLOWED_ORIGINS`.

Infine imposta `CONFIG.tokenEndpoint` in `index.html`, ricarica la pagina —
il riquadro giallo deve sparire — e ripeti la tabella del punto 2.

### Diagnosticare un errore del token server

```bash
wrangler tail        # log in tempo reale del Worker
```

Il Worker non rimanda al browser il corpo dell'errore di LiveAvatar (finirebbe
in pagina), ma lo scrive nei log: `wrangler tail` è dove si vede il motivo
reale di un 502.

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
