# Blog Articles — Naples Experiences

Articoli blog B2B **WordPress-ready** per **naplesexperiences.com** (Experiences Srl),
ciascuno corredato dai prompt per generare le immagini con Google Gemini.

## Contenuto del pacchetto

| # | Slug | Titolo | Focus Keyword | Categoria | Parole |
|---|------|--------|---------------|-----------|--------|
| 13 | `aumentare-prenotazioni-dirette-hotel-disintermediazione` | Prenotazioni Dirette: Come Tagliare le Commissioni OTA e Aumentare i Margini del 40% | prenotazioni dirette hotel | Revenue Management | 2.556 |
| 14 | `gestire-recensioni-online-hotel-booking-tripadvisor` | Recensioni Online per Hotel: Come Passare da 8.1 a 9.0 su Booking in 6 Mesi | recensioni online hotel | Reputazione & Customer Care | 2.778 |
| 15 | `email-marketing-hotel-pre-stay-post-stay` | Email Marketing per Hotel: Pre-Stay, Post-Stay e Recupero delle Prenotazioni Abbandonate | email marketing hotel | Marketing Digitale | 2.459 |
| 16 | `metasearch-google-hotel-ads-trivago-tripadvisor` | Metasearch per Hotel: Google Hotel Ads, Trivago e TripAdvisor — La Guida Completa 2026 | metasearch hotel | Marketing Digitale | 2.556 |
| 17 | `whatsapp-business-hotel-tour-operator` | WhatsApp Business per Hotel e Tour Operator: Il Canale che Converte il 45% | whatsapp business hotel | AI & Innovazione | 2.770 |
| 18 | `instagram-tiktok-hotel-strutture-ricettive-ugc` | Instagram e TikTok per Hotel: La Strategia Contenuti (e l'UGC) che Riempie le Camere | instagram hotel marketing | Social Media & Contenuti | 3.133 |

**Totale:** ~16.250 parole di contenuto B2B premium.

## Struttura di ogni cartella

```
13-aumentare-prenotazioni-dirette-hotel-disintermediazione/
├── article.html        ← contenuto HTML pronto per WordPress
├── seo.json            ← pacchetto SEO completo + schema JSON-LD
└── image-prompts.md    ← 4 prompt Gemini + alt text
```

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

### `image-prompts.md`

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
meta SEO già assegnati. Chiedi la generazione del WXR quando gli articoli sono
definitivi.

---

## Permalink richiesti

Gli articoli si linkano tra loro con URL nella forma `/blog/<slug>/`.
Imposta in **Impostazioni → Permalink → Struttura personalizzata**:

```
/blog/%postname%/
```

Senza questa struttura i link interni cross-articolo restituiscono 404.

---

## Calendario editoriale consigliato

Pubblicazione settimanale, **martedì o mercoledì mattina (9:00-10:00 CET)** —
picco di engagement B2B.

| Settimana | Articolo | Note |
|-----------|----------|------|
| 1 | #13 — Prenotazioni dirette | Pillar: il tema economicamente più rilevante |
| 2 | #16 — Metasearch | Supporta il #13 (canale per il diretto) |
| 3 | #15 — Email marketing | Supporta il #13 (database proprietario) |
| 4 | #14 — Recensioni online | Cambio topic: reputazione |
| 5 | #17 — WhatsApp Business | Canale conversazionale |
| 6 | #18 — Instagram e TikTok | Chiude sul funnel di scoperta |

Questa sequenza costruisce un topic cluster attorno alla **disintermediazione**:
il #13 è il pillar, gli altri cinque sono i supporting content che vi rimandano.

---

## Checklist post-pubblicazione

Per ogni articolo:

- [ ] URL pulito e funzionante (`/blog/<slug>/`)
- [ ] Featured image impostata (generata con il prompt #1)
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
