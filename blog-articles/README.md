# Blog Articles v1 — Naples Experiences

Pacchetto di 6 articoli blog completi, WordPress-ready, per il sito **naplesexperiences.com** (Experiences Srl).

## Contenuto del pacchetto

| # | Slug | Titolo | Focus Keyword | Categoria | Parole (~) |
|---|------|--------|---------------|-----------|------------|
| 1 | `channel-manager-hotel-guida-completa-2026` | Channel Manager per Hotel: La Guida Completa 2026 | channel manager hotel | Hotel & Strutture Ricettive | 2.100 |
| 2 | `come-evitare-overbooking-hotel` | Come Evitare l'Overbooking: Guida Pratica per Hotel e B&B | evitare overbooking hotel | Hotel & Strutture Ricettive | 1.950 |
| 3 | `seo-locale-hotel-top-google` | SEO Locale per Hotel: Come Arrivare in Top 3 su Google in 90 Giorni | seo locale hotel | Marketing Digitale | 2.350 |
| 4 | `da-5-a-20-prenotazioni-mese-agenzia-tour` | Da 5 a 20 Prenotazioni al Mese: Il Case Study di un'Agenzia Tour di Napoli | aumentare prenotazioni agenzia viaggi | Case Study | 2.200 |
| 5 | `vendere-tour-online-getyourguide-viator-sito-proprio` | GetYourGuide vs Viator vs Sito Proprio: Dove Vendere i Tuoi Tour nel 2026 | vendere tour online | Tour Operator & Agenzie | 2.400 |
| 6 | `channel-manager-tour-operator` | Channel Manager per Tour Operator: Gestisci 100+ Tour da 1 Dashboard | channel manager tour operator | Tour Operator & Agenzie | 2.500 |

**Totale parole stimate:** ~13.500 parole di contenuto B2B premium.

## Struttura cartella

Ogni articolo è in una cartella numerata:

```
blog-articles/
├── 01-channel-manager-hotel-guida-completa-2026/
│   ├── article.html      ← contenuto HTML WordPress-ready
│   └── seo.json          ← pacchetto SEO completo
├── 02-come-evitare-overbooking-hotel/
│   ├── article.html
│   └── seo.json
├── ... (idem per 03, 04, 05, 06)
└── README.md             ← questo file
```

## File `article.html`

Contenuto HTML pronto per essere incollato direttamente nell'editor "HTML/Code" di WordPress (Gutenberg → blocco "HTML personalizzato", oppure editor classico).

**Caratteristiche HTML:**

- Tag standard: `<h2>`, `<h3>`, `<p>`, `<ul>`, `<ol>`, `<table>`, `<blockquote>`, `<strong>`, `<em>`, `<code>`, `<pre>`
- Wrapper `<div class="prose-blog">` per applicare lo stile Tailwind del tema
- Callout blocks: `.callout-info`, `.callout-tip`, `.callout-warning`
- 2 CTA box gradient (1 intermedia + 1 finale) con link a `/#contact` e WhatsApp `+39 3926917657`
- **Nessun tag `<h1>`** (WordPress aggiunge automaticamente l'H1 dal titolo del post)
- Link interni cross-articoli già impostati

## File `seo.json`

Pacchetto SEO completo per ogni articolo:

```json
{
  "slug": "url-friendly",
  "post_title": "Titolo per WordPress",
  "seo_title": "Titolo SEO (max 60 char)",
  "meta_description": "Max 155 char con keyword primaria",
  "focus_keyword": "keyword principale",
  "secondary_keywords": ["kw2", "kw3", "kw4", "kw5"],
  "og_title": "Open Graph title",
  "og_description": "OG description ~200 char",
  "category": "categoria WordPress",
  "tags": ["tag1", "tag2", ...],
  "estimated_reading_time": 11,
  "schema_article": { ...JSON-LD Article schema completo... }
}
```

---

## Come importare gli articoli su WordPress

### Metodo 1 — Manuale (raccomandato per 6 articoli)

Per ogni articolo:

1. **Login** in WordPress Admin → **Articoli → Aggiungi nuovo**
2. **Titolo**: copia il valore `post_title` da `seo.json`
3. **Permalink**: imposta come `slug` da `seo.json`
4. **Contenuto**:
   - In Gutenberg: aggiungi un blocco **HTML personalizzato** e incolla l'intero contenuto di `article.html`
   - In editor classico: passa in modalità **Testo** (HTML) e incolla
5. **Categoria**: assegna la categoria indicata in `seo.json` (creala se non esiste)
6. **Tag**: aggiungi i tag indicati in `seo.json`
7. **Immagine in evidenza**: carica un'immagine pertinente (vedi sezione "Immagini suggerite" sotto)
8. **SEO (Yoast / Rank Math / SEOPress)**:
   - **Focus keyword**: `focus_keyword` da `seo.json`
   - **SEO Title**: `seo_title` da `seo.json`
   - **Meta description**: `meta_description` da `seo.json`
   - **Social → Open Graph Title**: `og_title`
   - **Social → Open Graph Description**: `og_description`
9. **Schema markup**: se il tuo tema/plugin non genera automaticamente lo schema Article, incolla il JSON-LD da `seo.json` → `schema_article` in un blocco HTML personalizzato in fondo all'articolo, avvolto in:
   ```html
   <script type="application/ld+json">
   { ...contenuto schema_article... }
   </script>
   ```
10. **Pubblica** o pianifica con scadenza settimanale (vedi sezione "Calendario editoriale" sotto)

### Metodo 2 — WP-CLI (avanzato)

Se hai accesso SSH al server WordPress, puoi automatizzare con WP-CLI:

```bash
# Esempio articolo 1
wp post create \
  --post_type=post \
  --post_status=publish \
  --post_title="Channel Manager per Hotel: La Guida Completa 2026" \
  --post_name="channel-manager-hotel-guida-completa-2026" \
  --post_category="Hotel & Strutture Ricettive" \
  --tags_input="channel manager,hotel,gestione hotel,OTA,booking.com" \
  --post_content="$(cat 01-channel-manager-hotel-guida-completa-2026/article.html)"

# Poi aggiungi meta SEO (esempio con Yoast):
wp post meta update <POST_ID> _yoast_wpseo_focuskw "channel manager hotel"
wp post meta update <POST_ID> _yoast_wpseo_title "Channel Manager Hotel: Guida Completa 2026 | Experiences"
wp post meta update <POST_ID> _yoast_wpseo_metadesc "Channel Manager hotel: cos'è, come funziona, costi e ROI..."
```

Ripeti per ogni articolo.

### Metodo 3 — Import via REST API (per dev)

```bash
# Pseudocodice
curl -X POST https://naplesexperiences.com/wp-json/wp/v2/posts \
  -u admin:application_password \
  -H "Content-Type: application/json" \
  -d @article-1-payload.json
```

Costruisci un payload JSON con: `title`, `slug`, `content`, `categories`, `tags`, `status`, `meta` (per i campi SEO custom).

---

## Calendario editoriale consigliato

Per massimizzare l'impatto SEO, pubblica gli articoli in sequenza settimanale:

| Settimana | Articolo | Note |
|-----------|----------|------|
| 1 | #1 — Channel Manager Hotel | Pillar content, base del topic cluster |
| 2 | #2 — Evitare Overbooking | Supporta il pillar #1 |
| 3 | #3 — SEO Locale Hotel | Cambio topic per varietà |
| 4 | #4 — Case Study Agenzia Tour | Switch verso tour operator |
| 5 | #5 — Dove Vendere Tour | Supporta il #4 |
| 6 | #6 — Channel Manager Tour Operator | Chiude il cluster tour |

**Tip:** programma i post per **martedì o mercoledì mattina (ore 9:00-10:00 CET)** — picco di engagement B2B.

---

## Immagini suggerite per Featured Image

Per ogni articolo, suggerimenti per immagini di copertina (16:9, minimo 1200×675 px):

1. **CM Hotel** — dashboard di gestione hotel su schermo, reception sullo sfondo
2. **Overbooking** — calendario con segnali di "doppia prenotazione", o persona stressata in reception
3. **SEO Locale** — mappa di Napoli con pin di hotel + risultati Google
4. **Case Study Tour** — guida turistica con turisti in centro storico di Napoli
5. **Vendere Tour** — split screen tra logo GetYourGuide/Viator/Airbnb/sito web
6. **CM Tour Operator** — dashboard con multipli tour, mappa Campania

**Risorse gratuite:** Unsplash, Pexels, Pixabay (cerca: "hotel reception", "naples tourism", "tour guide", "dashboard analytics").

**Risorse a pagamento (raccomandato per brand):** Shutterstock, Adobe Stock (€10-30 ad immagine).

---

## Checklist post-pubblicazione

Per ogni articolo, dopo la pubblicazione:

- [ ] URL pulito e funzionante (es. `naplesexperiences.com/blog/channel-manager-hotel-guida-completa-2026/`)
- [ ] Featured image impostata
- [ ] Categoria e tag corretti
- [ ] Meta SEO compilati (focus keyword, title, description)
- [ ] Open Graph compilato (per condivisioni social)
- [ ] Schema markup Article presente nel sorgente HTML
- [ ] Link interni funzionanti (testa cliccando i link agli altri articoli)
- [ ] Link a `/#contact` funzionante
- [ ] Link WhatsApp `https://wa.me/393926917657` funzionante
- [ ] Articolo aggiunto alla **Sitemap XML** (Yoast/RankMath dovrebbe farlo automaticamente)
- [ ] **Submit URL** in Google Search Console per indicizzazione immediata
- [ ] Condivisione su LinkedIn (canale B2B principale)
- [ ] Newsletter agli iscritti (se applicabile)

---

## Note finali

- **Brand voice:** professionale, B2B, pain-point first, dati concreti. Mantenere consistente.
- **CTA:** ogni articolo ha 2 CTA. Non modificare i link (`/#contact` e WhatsApp).
- **Cross-linking:** la struttura "topic cluster" è già impostata. Non rimuovere i link interni tra articoli.
- **Aggiornamenti:** per articoli con date specifiche (es. "Guida 2026"), aggiornare titolo e contenuti a inizio anno successivo per mantenere freschezza SEO.

Per richieste di articoli aggiuntivi o aggiornamenti, contatta il team Experiences Srl.

---

*Pacchetto generato come parte della content strategy 2026 per naplesexperiences.com.*
