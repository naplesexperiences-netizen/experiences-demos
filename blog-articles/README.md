# Blog Articles — Naples Experiences

Articoli blog B2B **WordPress-ready** per **naplesexperiences.com** (Experiences Srl).
Gli articoli 13-18 includono anche i prompt per generare le immagini con Google Gemini.

## Contenuto del pacchetto

### Batch 1 — Pillar (gennaio-febbraio 2026)

| # | Slug | Titolo | Focus Keyword | Categoria | Parole |
|---|------|--------|---------------|-----------|--------|
| 01 | `channel-manager-hotel-guida-completa-2026` | Channel Manager per Hotel: La Guida Completa 2026 | channel manager hotel | Hotel & Strutture Ricettive | 1.637 |
| 02 | `come-evitare-overbooking-hotel` | Come Evitare l'Overbooking: Guida Pratica per Hotel e B&B | evitare overbooking hotel | Hotel & Strutture Ricettive | 1.513 |
| 03 | `seo-locale-hotel-top-google` | SEO Locale per Hotel: Come Arrivare in Top 3 su Google in 90 Giorni | seo locale hotel | Marketing Digitale | 1.788 |
| 04 | `da-5-a-20-prenotazioni-mese-agenzia-tour` | Da 5 a 20 Prenotazioni al Mese: Il Case Study di un'Agenzia Tour di Napoli | aumentare prenotazioni agenzia viaggi | Case Study | 1.541 |
| 05 | `vendere-tour-online-getyourguide-viator-sito-proprio` | GetYourGuide vs Viator vs Sito Proprio: Dove Vendere i Tuoi Tour nel 2026 | vendere tour online | Tour Operator & Agenzie | 1.594 |
| 06 | `channel-manager-tour-operator` | Channel Manager per Tour Operator: Gestisci 100+ Tour da 1 Dashboard | channel manager tour operator | Tour Operator & Agenzie | 1.770 |

### Batch 2 — Turismo & Innovazione (febbraio-marzo 2026)

| # | Slug | Titolo | Focus Keyword | Categoria | Parole |
|---|------|--------|---------------|-----------|--------|
| 07 | `ai-chatbot-hotel-bnb-segretario-digitale-24-7` | AI Chatbot per Hotel e B&B: Il Segretario Digitale che Lavora 24/7 | ai chatbot hotel | AI & Innovazione | 1.963 |
| 08 | `dynamic-pricing-ai-hotel-revpar` | Dynamic Pricing AI: Come Hotel Italiani Aumentano il RevPAR del 35% | dynamic pricing hotel | Revenue Management | 1.971 |
| 09 | `turismo-sostenibile-hotel-certificazioni-green-2026` | Turismo Sostenibile 2026: Certificazioni Green, KPI e Viaggiatori Eco | turismo sostenibile hotel | Sostenibilità & Innovazione | 2.233 |
| 10 | `smart-hotel-check-in-keyless-iot-guida-2026` | Smart Hotel: Check-in Senza Reception, Smart Key e IoT | smart hotel | Innovazione Tecnologica | 2.350 |
| 11 | `vr-ar-tour-operator-esperienze-immersive` | Realtà Virtuale e Aumentata per Tour Operator | realtà virtuale tour operator | Tour Operator & Innovazione | 2.240 |
| 12 | `voice-search-hotel-ottimizzazione-alexa-google-assistant` | Voice Search per Hotel: Ottimizzare per Alexa e Google Assistant | voice search hotel | SEO & Marketing Digitale | 2.184 |

### Batch 3 — Revenue & Marketing (marzo-aprile 2026)

Include i prompt Gemini per le immagini.

| # | Slug | Titolo | Focus Keyword | Categoria | Parole |
|---|------|--------|---------------|-----------|--------|
| 13 | `aumentare-prenotazioni-dirette-hotel-disintermediazione` | Prenotazioni Dirette: Come Tagliare le Commissioni OTA e Aumentare i Margini del 40% | prenotazioni dirette hotel | Revenue Management | 2.556 |
| 14 | `gestire-recensioni-online-hotel-booking-tripadvisor` | Recensioni Online per Hotel: Come Passare da 8.1 a 9.0 su Booking in 6 Mesi | recensioni online hotel | Reputazione & Customer Care | 2.357 |
| 15 | `email-marketing-hotel-pre-stay-post-stay` | Email Marketing per Hotel: Pre-Stay, Post-Stay e Recupero delle Prenotazioni Abbandonate | email marketing hotel | Marketing Digitale | 2.459 |
| 16 | `metasearch-google-hotel-ads-trivago-tripadvisor` | Metasearch per Hotel: Google Hotel Ads, Trivago e TripAdvisor — La Guida Completa 2026 | metasearch hotel | Marketing Digitale | 2.556 |
| 17 | `whatsapp-business-hotel-tour-operator` | WhatsApp Business per Hotel e Tour Operator: Il Canale che Converte il 45% | whatsapp business hotel | AI & Innovazione | 2.770 |
| 18 | `instagram-tiktok-hotel-strutture-ricettive-ugc` | Instagram e TikTok per Hotel: La Strategia Contenuti (e l'UGC) che Riempie le Camere | instagram hotel marketing | Social Media & Contenuti | 2.511 |

**Totale: 18 articoli, ~38.900 parole** di contenuto B2B.

---

## Struttura di ogni cartella

```
13-aumentare-prenotazioni-dirette-hotel-disintermediazione/
├── article.html        ← contenuto HTML pronto per WordPress
├── seo.json            ← pacchetto SEO completo + schema JSON-LD
└── image-prompts.md    ← 4 prompt Gemini + alt text (solo batch 3)
```

Gli articoli **01-12** hanno `article.html` e `seo.json`. Il file `image-prompts.md`
è presente solo per il **batch 3** (13-18); per i primi dodici le immagini vanno
scelte da stock o generate adattando i prompt esistenti.

### `article.html`

HTML pronto da incollare nell'editor "HTML personalizzato" di WordPress (Gutenberg)
o in modalità Testo dell'editor classico.

- Wrapper `<div class="prose-blog">` per lo stile del tema
- Tag standard: `<h2>`, `<h3>`, `<p>`, `<ul>`, `<ol>`, `<table>`, `<blockquote>`, `<pre>`
- Callout: `.callout-info` (📌), `.callout-tip` (💡), `.callout-warning` (⚠️)
- 2 CTA box gradient con link a `/#contact` e WhatsApp `+39 392 691 7657`
- **Nessun `<h1>`** — WordPress lo genera dal titolo del post
- Link interni cross-articolo già impostati (topic cluster)

### `seo.json`

```json
{
  "slug": "url-friendly",
  "post_title": "Titolo per WordPress",
  "seo_title": "Titolo SEO (max 60 char)",
  "meta_description": "Max 155 char con keyword primaria",
  "focus_keyword": "keyword principale",
  "secondary_keywords": ["kw2", "kw3", "..."],
  "og_title": "Open Graph title",
  "og_description": "OG description ~200 char",
  "category": "categoria WordPress",
  "tags": ["tag1", "tag2", "..."],
  "estimated_reading_time": 11,
  "schema_article": { "...JSON-LD Article completo..." }
}
```

### `image-prompts.md` (batch 3)

Quattro prompt pronti da incollare in Google Gemini:

1. **Featured image** (16:9 → 1200×675) — immagine in evidenza WordPress
2. **Immagine concettuale** (16:9) — dentro l'articolo
3. **Case study** (16:9) — prima della sezione con i numeri
4. **Social card** (1:1 → 1080×1080) — LinkedIn e Instagram

Ogni prompt include varianti alternative, alt text SEO in italiano e nome file consigliato.

Il workflow completo (generazione → ritaglio → WebP → upload) è in
**[GUIDA-IMMAGINI-GEMINI.md](GUIDA-IMMAGINI-GEMINI.md)**.

---

## Come importare su WordPress

### Metodo 1 — Manuale (consigliato)

Per ogni articolo:

1. **Articoli → Aggiungi nuovo**
2. **Titolo**: copia `post_title` da `seo.json`
3. **Permalink**: imposta lo `slug` da `seo.json`
4. **Contenuto**: blocco **HTML personalizzato** → incolla `article.html`
5. **Categoria**: quella indicata in `seo.json` (creala se non esiste)
6. **Tag**: quelli indicati in `seo.json`
7. **Immagine in evidenza**: genera con il prompt #1 di `image-prompts.md`
8. **SEO** (Yoast / Rank Math / SEOPress):
   - Focus keyword → `focus_keyword`
   - SEO Title → `seo_title`
   - Meta description → `meta_description`
   - Social → OG Title/Description → `og_title` / `og_description`
9. **Schema markup**: se il plugin SEO non genera lo schema Article, incolla
   il JSON-LD da `schema_article` in un blocco HTML in fondo all'articolo,
   avvolto in `<script type="application/ld+json">…</script>`
10. **Pubblica** o pianifica

### Metodo 2 — WP-CLI

```bash
wp post create \
  --post_type=post \
  --post_status=publish \
  --post_title="Prenotazioni Dirette: Come Tagliare le Commissioni OTA…" \
  --post_name="aumentare-prenotazioni-dirette-hotel-disintermediazione" \
  --post_category="Revenue Management" \
  --post_content="$(cat 13-*/article.html)"
```

### Metodo 3 — File WXR

Un file WXR (WordPress eXtended RSS) permette di importare tutti gli articoli
in un colpo solo da **Strumenti → Importa → WordPress**, con categorie, tag e
meta SEO già assegnati.

Se l'importer restituisce *"numero di versione WXR mancante o errato"*, il file
è quasi sempre valido ma l'upload lo tronca: usa la variante `.xml.gz`, che
l'importer scompatta in memoria bypassando i limiti di `upload_max_filesize`.

---

## Permalink richiesti

Gli articoli si linkano tra loro con URL nella forma `/blog/<slug>/`.
Imposta in **Impostazioni → Permalink → Struttura personalizzata**:

```
/blog/%postname%/
```

Senza questa struttura i link interni cross-articolo restituiscono 404.

Serve anche una **pagina degli articoli**: crea una pagina "Blog" (slug `blog`)
e assegnala in **Impostazioni → Lettura → Pagina degli articoli**, altrimenti
l'archivio `/blog/` non esiste e i pulsanti "Tutti gli articoli" non portano
da nessuna parte.

---

## Calendario editoriale consigliato

Pubblicazione settimanale, **martedì o mercoledì mattina (9:00-10:00 CET)** —
picco di engagement B2B.

| Sett. | Articolo | Ruolo nel cluster |
|-------|----------|-------------------|
| 1 | #01 — Channel Manager Hotel | Pillar tecnologico |
| 2 | #02 — Evitare Overbooking | Supporta #01 |
| 3 | #03 — SEO Locale Hotel | Cambio topic: visibilità |
| 4 | #04 — Case Study Agenzia Tour | Switch verso tour operator |
| 5 | #05 — Dove Vendere Tour | Supporta #04 |
| 6 | #06 — Channel Manager Tour Operator | Chiude il cluster tour |
| 7 | #07 — AI Chatbot Hotel | Apre il cluster innovazione |
| 8 | #08 — Dynamic Pricing AI | Supporta #07 |
| 9 | #09 — Turismo Sostenibile | Cambio topic: ESG |
| 10 | #10 — Smart Hotel IoT | Supporta #09 |
| 11 | #11 — VR/AR Tour Operator | Innovazione lato tour |
| 12 | #12 — Voice Search Hotel | Chiude su SEO futuro |
| 13 | #13 — Prenotazioni dirette | **Pillar economico** del batch 3 |
| 14 | #16 — Metasearch | Supporta #13 (canale per il diretto) |
| 15 | #15 — Email marketing | Supporta #13 (database proprietario) |
| 16 | #14 — Recensioni online | Cambio topic: reputazione |
| 17 | #17 — WhatsApp Business | Canale conversazionale |
| 18 | #18 — Instagram e TikTok | Chiude sul funnel di scoperta |

Il batch 3 costruisce un topic cluster attorno alla **disintermediazione**:
il #13 è il pillar, gli altri cinque sono supporting content che vi rimandano.

---

## Checklist post-pubblicazione

Per ogni articolo:

- [ ] URL pulito e funzionante (`/blog/<slug>/`)
- [ ] Featured image impostata
- [ ] Alt text compilato su tutte le immagini
- [ ] Categoria e tag corretti
- [ ] Meta SEO compilati (focus keyword, title, description)
- [ ] Open Graph compilato
- [ ] Schema markup Article presente nel sorgente
- [ ] Link interni cross-articolo funzionanti (testali cliccando)
- [ ] Link a `/#contact` e WhatsApp funzionanti
- [ ] Articolo presente nella Sitemap XML
- [ ] URL inviato in Google Search Console per indicizzazione
- [ ] Condivisione su LinkedIn (canale B2B principale)

---

## Note

- **Brand voice:** professionale, B2B, pain-point first, dati concreti. Mantenere consistente.
- **CTA:** ogni articolo ne ha 2. Non modificare i link (`/#contact` e WhatsApp).
- **Cross-linking:** la struttura a topic cluster è già impostata, non rimuovere i link interni.
- **Case study:** i numeri sono illustrativi e coerenti con i benchmark di settore.
  Le strutture citate non sono clienti reali identificabili.
- **Aggiornamenti:** per gli articoli con l'anno nel titolo, aggiornare titolo e
  contenuti a inizio anno successivo per mantenere freschezza SEO.
