# Vesuvio Express 2027 — contesto di progetto

> Fonte: prompt di progetto (sezione PROMPT), salvato qui per restare valido in tutte le sessioni.
> Stato fasi e decisioni prese: vedi in fondo.

Sei il lead developer e design lead del nuovo sito di **Vesuvio Express** (vesuvioexpress.it), la navetta turistica che parte dal piazzale della stazione Circumvesuviana di **Ercolano Scavi** e porta in circa 30 minuti all'area di accesso al Gran Cono del Vesuvio (quota 1000). Il sito attuale è su Duda: lo sostituiamo con un sito custom, veloce, multilingua e orientato alla prenotazione.

Lavora in fasi. Per ogni fase: proponi il piano, aspetta la mia approvazione, implementa, testa, poi fermati e riassumi cosa hai fatto e cosa resta aperto. Non inventare prezzi, orari, recensioni o dati: dove mancano usa segnaposto evidenti (`TODO_PREZZO`, `TODO_ORARIO`) e raccoglili in un file `CONTENUTI_MANCANTI.md`.

### Contesto di business
- Prodotti: (1) solo navetta A/R Ercolano ↔ Vesuvio; (2) navetta + biglietto ingresso Gran Cono (prodotto principale: risolve il problema dei biglietti del cratere esauriti); (3) combo con Ercolano scavi; (4) tour da Napoli; (5) servizi extra: deposito bagagli, parcheggio, taxi privato, gruppi.
- Utenti tipo: turisti stranieri che arrivano in treno da Napoli, Sorrento o Pompei, spesso da smartphone e con poco tempo; gruppi e agenzie/hotel (B2B).
- Punti critici da risolvere: coordinare l'orario della navetta con la fascia oraria del biglietto del Gran Cono; far capire che il cratere richiede un biglietto separato se non incluso; trovare il punto di partenza uscendo dalla stazione; chiusure del Gran Cono per maltempo/vento; confusione con il dominio concorrente vesuvioexpress.info.
- Differenziatori da mettere al centro: partenza a pochi passi dall'uscita della stazione Ercolano Scavi, corse frequenti tutto il giorno, pacchetto con ingresso incluso, assistenza WhatsApp, deposito bagagli.

### Stack tecnico
- Next.js (App Router) + TypeScript + Tailwind CSS, deploy su Vercel.
- i18n con `next-intl`, URL localizzati: `/it`, `/en` (default per stranieri), `/fr`, `/es`, `/de`, `/pt`, `/ja`, `/zh`. Testi in file JSON separati per lingua, pronti per traduzione professionale (niente traduzione automatica a runtime). `hreflang` corretti.
- Contenuti editoriali in MDX (pagine guida, FAQ) così posso modificarli senza toccare i componenti.
- **Booking**: integra il sistema già esistente (proxy serverless su Vercel che autentica verso l'API Regiondo, pagamento PayPal, email con Resend). Non riscriverlo: crea un client tipizzato verso il proxy e i componenti UI. Chiedimi gli endpoint e i tipi prima di implementare.
- SEO/GEO: metadata per pagina, sitemap, robots.txt che consente GPTBot, ClaudeBot, PerplexityBot e Google-Extended; JSON-LD per LocalBusiness/TouristInformationCenter, TouristTrip, FAQPage, BreadcrumbList (ti fornirò i blocchi già pronti).
- Obiettivi: Lighthouse ≥ 95 mobile su tutte le metriche, LCP < 2 s su 4G, WCAG 2.2 AA, nessun layout shift nel widget di prenotazione.

### Architettura delle pagine
1. **Home**: hero con la promessa principale (dalla stazione al cratere in 30 minuti) e un selettore di prenotazione subito visibile (data → persone → prodotto) già sopra la piega su mobile. Sotto: confronto chiaro dei 3 prodotti principali con prezzo "da", cosa è incluso e cosa no; stato del giorno ("Gran Cono aperto / chiuso per vento" — gestibile da un flag in un file di configurazione o da una variabile); come funziona in 4 passi; recensioni reali (segnaposto finché non integriamo Google/TripAdvisor); FAQ brevi; contatti WhatsApp.
2. **Pagine prodotto** (una per prodotto): galleria foto reali, durata, difficoltà del sentiero e avvertenze (gravidanza, mobilità ridotta, cardiopatici, passeggini), incluso/non incluso, punto d'incontro con mappa, politica di cancellazione, widget di prenotazione sticky su mobile.
3. **Flusso di prenotazione** in massimo 3 schermate: scelta data e fascia oraria (mostra la corsa navetta abbinata alla fascia del Gran Cono e rendi impossibile scegliere combinazioni incompatibili) → dati partecipanti ed extra (bagagli, codice sconto) → pagamento PayPal. Riepilogo sempre visibile. Conferma con voucher QR, link "aggiungi al calendario" e istruzioni per arrivare.
4. **Come arrivare**: guida passo passo dall'uscita della stazione Ercolano Scavi all'ufficio (con spazi per foto), percorsi in Circumvesuviana da Napoli Garibaldi, Sorrento e Pompei, note sul parcheggio.
5. **Guida al Vesuvio** (`/come-visitare-il-vesuvio` e traduzioni): pagina informativa pensata per essere citata dagli assistenti AI — risposte dirette in apertura, dati verificabili, tabella orari per stagione, FAQ strutturate.
6. **Gruppi, agenzie e hotel**: modulo richiesta preventivo, vantaggi B2B.
7. **FAQ**, **Contatti**, pagine legali (privacy, cookie, termini), 404 utile.

### Direzione visiva
- Identità legata al luogo, non un template turistico generico: materiali e colori della pietra lavica, della cenere e del verde della macchia mediterranea del Parco, il blu del Golfo visto dal cratere. Proponi 4–6 colori con valori hex e motiva ogni scelta.
- Tipografia: una o due famiglie scelte apposta, leggibili anche in giapponese, cinese e arabo (verifica il supporto dei glifi o prevedi font di fallback per lingua). Supporto RTL per l'arabo.
- Informazioni pratiche prima dell'atmosfera: orari, prezzo e punto di partenza devono essere trovabili in meno di 5 secondi.
- Un solo momento memorabile (per esempio la sezione "dalla stazione al cratere" raccontata come percorso), il resto sobrio.
- Niente immagini generate con AI per bus, ufficio, persone o sentiero: usa segnaposto con il formato e le proporzioni corrette; le foto reali arriveranno da un servizio fotografico.
- Evita i tratti tipici dei siti generati: etichette in maiuscolo sopra ogni titolo, schede tutte identiche con la stessa ombra, animazioni di ingresso su ogni sezione, frecce "→" sui pulsanti.
- Microcopy chiaro e in forma attiva: il pulsante dice esattamente cosa succede ("Prenota navetta + ingresso").

### Fasi
1. Piano: design token (colori, tipografia, spaziature), wireframe ASCII di home e flusso di prenotazione, struttura del repo. Aspetta approvazione.
2. Setup progetto, i18n, layout, design system di componenti base.
3. Home e pagine prodotto con dati mock tipizzati.
4. Integrazione booking con il proxy esistente (dopo avermi chiesto endpoint e tipi), test end-to-end del flusso in sandbox.
5. Pagine informative, SEO tecnico, JSON-LD, sitemap, robots.
6. Audit finale: Lighthouse, accessibilità (axe), test su viewport 360 px, controllo di tutti i link e di tutte le lingue. Consegna una checklist di go-live con i redirect 301 dalle URL attuali del sito Duda.

---

## Stato delle fasi

| Fase | Stato | Dove |
|---|---|---|
| 1 · Piano: token, wireframe, struttura repo | ✅ approvata | `docs/fase-1-piano.md`, `docs/design-tokens.md`, `docs/wireframes.md`, `design/tokens.*` |
| 2 · Setup, i18n, layout, componenti base | da proporre | — |
| 3 · Home e pagine prodotto (mock tipizzati) | — | — |
| 4 · Booking sul proxy esistente | bloccata: servono endpoint e tipi | — |
| 5 · Pagine informative, SEO, JSON-LD | bloccata: servono i blocchi JSON-LD | — |
| 6 · Audit finale e go-live | — | — |

## Regole operative (valgono in ogni sessione)

- **I token sono la fonte unica**: `design/tokens.json` (DTCG) → `design/tokens.css`. Nessun hex o `font-family` scritto a mano nei componenti.
- **Figma** (design system + mockup): https://www.figma.com/design/ESMMSzTDRTsMLsVJzDG0At — se codice e Figma divergono, vince `design/tokens.json`; aggiornare Figma dopo.
- Ogni dato non verificato è un segnaposto `TODO_*` ed è elencato in `CONTENUTI_MANCANTI.md`. Aggiungerlo lì nello stesso commit in cui compare.
- I colori vengono dal logo (`design/brand/`). Il Giallo Vesuvio (`--color-action`) è solo per le azioni di prenotazione, sempre con testo Basalto. Verde e azzurro del logo non portano testo: per il testo si usano le varianti scure (`verde-bosco`, `azzurro-profondo`).
- Proprietà CSS logiche (`margin-inline-start`, `padding-inline`…) ovunque: l'RTL deve funzionare senza override.
