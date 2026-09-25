# Fase 1 — Piano (approvato)

Profilo di lavoro: **landing** (sito vetrina orientato alla prenotazione).
Deliverable: design token → `docs/design-tokens.md`, wireframe → `docs/wireframes.md`,
struttura del repo → qui sotto, Figma → <https://www.figma.com/design/ESMMSzTDRTsMLsVJzDG0At>.

## Direzione in una riga

Un orario dei trasporti ben fatto, scritto con i materiali del vulcano: tipografia da
segnaletica, i tre colori del logo (verde, azzurro, giallo) su neutri di pietra lavica e cenere, e **un solo** momento
scenico: il percorso «Dalla stazione al cratere», disegnato come una linea di trasporto con le sue fermate.

Scelte deliberate contro i tratti dei siti generati:
- niente etichette in maiuscolo sopra i titoli e niente frecce nei bottoni;
- il prodotto principale si distingue per bordo e fondo, non per un badge «più scelto»
  (sarebbe un dato inventato); le altre schede hanno solo il bordo;
- nessuna animazione d'ingresso sulle sezioni;
- il Giallo Vesuvio del logo è riservato alle azioni di prenotazione.

## Struttura del repo (da creare in fase 2)

```
vesuvio-express/
├── CLAUDE.md                     contesto di progetto + stato fasi
├── CONTENUTI_MANCANTI.md         ogni TODO_* con chi lo deve fornire
├── design/
│   ├── tokens.json               fonte unica (DTCG)
│   └── tokens.css                variabili CSS, importate da app/globals.css
├── docs/                         piani e decisioni per fase
├── app/
│   ├── globals.css               @import tokens.css + @theme Tailwind v4
│   ├── [locale]/
│   │   ├── layout.tsx            <html lang dir>, font per lingua, header/footer
│   │   ├── page.tsx              Home
│   │   ├── prodotti/[slug]/page.tsx        4 prodotti + servizi extra
│   │   ├── prenota/page.tsx                flusso a 3 passi (client, stato in URL)
│   │   ├── prenota/conferma/page.tsx       voucher QR, .ics, indicazioni
│   │   ├── come-arrivare/page.tsx
│   │   ├── come-visitare-il-vesuvio/page.tsx   guida MDX (slug localizzati)
│   │   ├── gruppi/page.tsx                 preventivo B2B
│   │   ├── faq/page.tsx
│   │   ├── contatti/page.tsx
│   │   ├── legale/[doc]/page.tsx           privacy, cookie, termini
│   │   └── not-found.tsx                   404 con link a prenota, come arrivare, WhatsApp
│   ├── sitemap.ts                tutte le lingue con alternates hreflang
│   └── robots.ts                 GPTBot, ClaudeBot, PerplexityBot, Google-Extended ammessi
├── components/
│   ├── ui/                       Button, Field, Stepper, Segmented, Accordion, StatusBanner
│   ├── booking/                  BookingWidget, SlotPicker, SummaryBar, SummaryPanel, Voucher
│   └── sections/                 ProductCompare, RouteLine, HowItWorks, Reviews, WhatsAppBlock
├── content/{it,en,fr,es,de,pt,ja,zh}/    MDX: guida, FAQ, pagine prodotto (testi lunghi)
├── messages/{it,en,…}.json       microcopy UI per next-intl
├── config/
│   ├── site-status.ts            flag Gran Cono (aperto | vento | chiuso | da-verificare)
│   └── products.ts               catalogo mock tipizzato (fase 3)
├── lib/
│   ├── booking/client.ts         client tipizzato verso il proxy esistente (fase 4)
│   ├── booking/types.ts          tipi forniti dal proxy
│   ├── booking/compat.ts         regola navetta ↔ fascia (pura, testata)
│   └── seo/                      metadata, alternates, JSON-LD (blocchi forniti)
├── i18n/
│   ├── routing.ts                locales, defaultLocale, pathnames localizzati
│   └── request.ts
├── middleware.ts                 negoziazione lingua: stranieri → /en
├── public/placeholders/          segnaposto con proporzioni reali (4:3, 3:2, 16:9)
└── tests/
    ├── unit/                     compat.ts, formattazione prezzi/date per lingua
    └── e2e/                      Playwright: home 360 px, flusso prenota in sandbox, axe
```

### Decisioni tecniche

- **Next.js App Router + TypeScript + Tailwind v4**; i token entrano in Tailwind con `@theme`
  a partire da `tokens.css`, così classi e CSS usano gli stessi nomi.
- **next-intl** con `pathnames` localizzati (es. `/en/how-to-visit-vesuvius`); `it` ha i propri slug.
  Lingue: it, en, fr, es, de, pt, ja, zh. Default per visitatori non italiani: `/en`.
- **RTL**: solo proprietà logiche CSS e `dir` sull'`<html>` in base alla lingua. L'arabo non è
  nell'elenco delle URL del brief: vedi domanda 3.
- **Font**: `next/font/google` self-hosted; Archivo e Atkinson con `display: swap` e fallback
  metrico (`adjustFontFallback`); Noto CJK/Arabic caricati solo nel layout della lingua corrispondente.
- **Stato del Gran Cono**: `config/site-status.ts` legge `GRAN_CONO_STATUS` (variabile Vercel)
  con fallback al valore nel file; pagine con revalidate breve così il cambio non richiede deploy.
- **Booking**: nessuna chiamata diretta a Regiondo; solo il client verso il proxy esistente.
  La regola di compatibilità è una funzione pura in `lib/booking/compat.ts`, testata a parte.
- **Prestazioni**: Home statica (SSG per lingua), widget idratato come isola client con
  dimensioni riservate; immagini `next/image` con `sizes` corretti; niente librerie d'animazione
  (la linea del percorso usa CSS scroll-driven animations con fallback statico).

## Domande aperte (bloccano le fasi indicate)

1. **Repo GitHub** — non ho i permessi per creare `vesuvio-express` sul tuo account (403).
   Crealo vuoto e aggiungilo alla sessione: sposto questa cartella con la sua storia.
   Nel frattempo vive in `experiences-demos/vesuvio-express/`. *(fase 2)*
2. **Endpoint e tipi del proxy** di prenotazione (disponibilità per data/prodotto, fasce del
   Gran Cono, creazione ordine, conferma PayPal, voucher). *(fase 4)*
3. **Arabo**: il brief chiede supporto RTL ma le URL elencate non includono `/ar`.
   Lo aggiungiamo come nona lingua o prepariamo solo l'RTL? *(fase 2)*
4. **Margine navetta ↔ fascia**: quanti minuti prima dell'inizio fascia deve arrivare
   la navetta alla quota 1000? *(fase 3)*
5. **Politica vento/chiusura**: rimborso, cambio data, o navetta comunque attiva? Il testo
   dello stato del giorno dipende da questo. *(fase 3)*
6. **URL attuali del sito Duda** (export o sitemap) per la mappa dei redirect 301. *(fase 6)*
7. **Blocchi JSON-LD** pronti. *(fase 5)*
