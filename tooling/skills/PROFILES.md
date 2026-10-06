# Profili e conflitti fra skill

`CLAUDE.md` contiene la tabella operativa. Qui c'è il perché, e soprattutto la mappa
delle **collisioni di trigger**: casi in cui più skill rivendicano la stessa frase e
Claude ne farebbe partire una sbagliata senza accorgersene.

## Perché i profili esistono

Una skill installata pesa in due momenti diversi:

| | Quando | Cosa entra | Lo riduce un profilo? |
|---|---|---|---|
| **description** | sempre, a ogni sessione | `name` + `description` di ogni skill installata | ❌ no, è già in contesto |
| **corpo** | solo all'invocazione | `SKILL.md` (3–66 KB) + i `references/` che serve leggere | ✅ sì |

I profili **non** servono a risparmiare contesto: servono a non far partire la skill
sbagliata. Il risparmio di contesto si ottiene installando poco (vedi `AUDIT.md`).

## Collisioni note, e chi vince

### 1. «controlla / audita la UI» → 7 candidate
`hallmark`, `ui-ux-pro-max`, `web-design-guidelines`, `interface-design`,
`design-audit`, `refactoring-ui`, `ux-heuristics` rivendicano tutte questo trigger.

| Se il problema è… | Usa | Non usare |
|---|---|---|
| conformità tecnica (a11y, focus, form, layout) | `web-design-guidelines` | le altre |
| «sembra fatto da un'AI», manca carattere | `hallmark` | `design-audit` |
| gerarchia visiva, spaziature, colore, ombre | `refactoring-ui` | `hallmark` |
| l'utente non capisce come si usa | `ux-heuristics` | `refactoring-ui` |
| restyle sistematico e a fasi di qualcosa che esiste | `design-audit` | `hallmark` |
| craft di una UI di prodotto (dashboard, tabelle, stati) | `interface-design` | — |

`interface-design` dichiara esplicitamente **«not for marketing pages, landing pages,
campaigns»**. Le 212 pagine in `demos/` *sono* landing page: su quelle non va usata,
anche se il trigger scatta. Vale per l'area riservata / booking, non per le vetrine.

### 2. Le tre skill di tipografia sono complementari, non alternative
- `web-typography` → **scelta**: quale carattere, abbinamenti, scala, caricamento, FOUT/FOIT.
- `ui-typography` → **esecuzione**: virgolette tipografiche, trattini, spaziature, micro-dettagli nel codice generato.
- `top-design` → **espressione**: tipografia drammatica come elemento scenico.

⚠️ `ui-typography` è in **ENFORCEMENT MODE**: la sua description dice di applicarsi in
automatico e in silenzio a *qualsiasi* output HTML/CSS/React con testo visibile. È la
skill più invasiva del set. Se un deliverable deve rispettare convenzioni tipografiche
del cliente diverse dalle sue, dillo esplicitamente all'inizio.

### 3. `hallmark` vs `top-design` (profilo landing)
Convivono con ruoli diversi: `hallmark` decide la **direzione** e fa da filtro anti-slop;
`top-design` fornisce il **vocabolario immersivo** (scroll composition, motion, scenografia).
Direzione prima, vocabolario dopo. Mai `top-design` da solo su un brief senza direzione.

### 4. ⚠️ `scroll-craft` non si mescola con GSAP
`scroll-craft` porta un **motore proprio** (`engine/scrollcraft.js`, 1.211 righe): zero
riferimenti a GSAP, ScrollTrigger o Lenis. Montarlo su una pagina che usa già
ScrollTrigger/ScrollSmoother significa due sistemi di scroll che si contendono lo stesso
evento — jank garantito. **Scegli uno dei due per pagina.**

Nel profilo `scroll` di `CLAUDE.md` le `gsap-*` restano elencate perché servono quando
si costruisce lo scroll a mano; se si adotta `scroll-craft`, si usa il suo engine e basta.

`scroll-craft` richiede inoltre: ffmpeg **completo** (non quello ridotto di Remotion/Electron),
`playwright-core` nel progetto, Chrome **installato** (Chromium non ha il decoder H.264, i
video scrubbed non verrebbero disegnati e la verifica passerebbe contro i poster).
La generazione asset via kie.ai è **opzionale e a pagamento**: `kie.mjs` risale fino a 8
cartelle sopra il cwd cercando un `.env` da cui leggere `KIE_AI_API_KEY`, e carica le
immagini locali su `kieai.redpandaai.co`. Non serve per usare l'engine.

### 5. `theme-factory` vs `experiences-theme/`
`theme-factory` genera palette/font per artifact una tantum. Il tema dei clienti vive in
`experiences-theme/` (tema WordPress in PHP). Non applicare `theme-factory` alle demo
che devono restare coerenti col tema: serve per prototipi e presentazioni.

### 6. Grafici
`dataviz` vince sempre su `ui-ux-pro-max` (che pure ha 25 tipi di grafico) e su
`interface-design`. Va letta **prima** di scrivere la prima riga di codice di un grafico.

## Cosa è stato deliberatamente escluso

| Escluso | Motivo |
|---|---|
| `Owl-Listener/designer-skills` (111 skill) | media 46 righe, contenuto generico, nessun `references/`, nessun codice: 6.958 token di description per valore quasi nullo |
| `claude-code-apple-skills` come skill (183) | 15.802 token/sessione di skill SwiftUI/App Store. Tenuto come **riferimento** in `~/.naples-references/apple-hig/` |
| `LovroPodobnik/refactoring-ui-skill` | 32 righe, sovrapposta e inferiore a `refactoring-ui` di wondelai (251 righe) |
| `frontend-design-pro-demo` come skill | senza LICENSE, fermo a nov 2025, teaser di un prodotto a pagamento. Tenuto come **galleria visiva** |
| `bencium/emotion-statusline` | hook `Stop` che a ogni turno legge il transcript e chiama un modello; costo a ogni turno, log locale persistente, solo macOS |
| `vercel/web-design-guidelines` | duplicato di quello già disponibile a livello Anthropic |
| `img2threejs` | ottimo ma di nicchia (3D procedurale da immagine). Installalo ad hoc se serve un hero 3D |
| le altre 54 skill di `wondelai/skills` | strategia d'impresa, DDD, clean architecture, negoziazione: fuori tema |
