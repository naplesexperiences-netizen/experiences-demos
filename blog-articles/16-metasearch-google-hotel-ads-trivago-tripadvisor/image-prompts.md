# Prompt Immagini — Metasearch per Hotel: Google Hotel Ads, Trivago e TripAdvisor

Prompt pronti per **Google Gemini** (Nano Banana / Imagen). Copia il blocco
di testo e incollalo in Gemini senza modifiche.

**Convenzioni brand applicate:** fotografia editoriale, luce naturale,
palette navy `#0B3D61` + teal `#14A3A3`, ambientazione mediterranea,
nessun testo dentro l'immagine.

---

## 1. Featured image (copertina)

- **Formato:** 16:9 — esporta a 1200×675 px
- **Dove va:** Immagine in evidenza di WordPress
- **Nome file consigliato:** `metasearch-google-hotel-ads-trivago-tripadvisor-copertina.webp`

**Prompt:**

```
Photorealistic editorial photography. Three-quarter view of a hotel general manager standing beside a pale limestone console table in a sunlit boutique property on the Bay of Naples, comparing a tablet held in one hand with a large wall-mounted display angled away from camera, both screens glowing but with no readable content. A small espresso cup and a brass key tray sit on the console. Behind, tall arched windows open onto a soft-focus Mediterranean seascape with terracotta rooftops and a distant island silhouette. Mid-morning light enters from the right, crisp natural shadows, shallow depth of field. Analytical, decisive, premium mood. Palette of deep navy blue (#0B3D61), teal accent (#14A3A3) and warm neutral stone tones. 16:9 aspect ratio. No text, no letters, no watermarks, no logos in the image.
```

**Varianti se il risultato non convince:**
- *Più caldo/umano:* aggiungi in coda `Golden hour lighting, warmer skin tones, softer shadows.`
- *Più corporate:* aggiungi in coda `Cooler color grading, minimalist composition, more negative space.`

**Alt text SEO:** `Metasearch hotel: direttore che confronta le tariffe della propria struttura online`

---

## 2. Il percorso dell'utente dal box tariffe alla prenotazione — immagine concettuale

- **Formato:** 16:9 — esporta a 1200×675 px
- **Dove va:** all'apertura della sezione "Cos'è il metasearch (e cosa non è)", prima del diagramma ASCII
- **Nome file consigliato:** `metasearch-hotel-percorso-box-tariffe.webp`

**Prompt:**

```
Clean 3D render with soft studio lighting and photorealistic materials. Four frosted glass price cards float in a shallow stack above a warm limestone surface, three of them muted and receding, the fourth pushed forward and rimmed with a glowing teal edge as if selected. From that front card a single thin teal thread arcs down to a small matte navy building form resting on the stone below, suggesting a direct path from comparison to the hotel itself. Background is a softly blurred warm neutral gradient with a faint Mediterranean blue horizon. Slightly elevated three-quarter camera angle, gentle surface reflections, generous negative space, no clutter, no interface elements. Palette deep navy blue (#0B3D61), teal accent (#14A3A3), warm neutral tones. Precise, modern, editorial mood. 16:9 aspect ratio. No text, no letters, no watermarks, no logos in the image.
```

**Varianti se il risultato non convince:**
- *Più caldo/umano:* aggiungi in coda `Golden hour lighting, warmer skin tones, softer shadows.`
- *Più corporate:* aggiungi in coda `Cooler color grading, minimalist composition, more negative space.`

**Alt text SEO:** `Il percorso dal box tariffe di Google alla prenotazione diretta in una composizione tridimensionale`

---

## 3. Case study — hotel 4 stelle a Ischia

- **Formato:** 16:9 — esporta a 1200×675 px
- **Dove va:** all'apertura della sezione "Case study: hotel 4 stelle, 60 camere, Ischia"
- **Nome file consigliato:** `metasearch-hotel-case-study-ischia.webp`

**Prompt:**

```
Photorealistic editorial photography. Wide three-quarter view of the pool terrace of a four-star hotel on the island of Ischia: pale stone paving, navy and cream sun loungers with rolled white towels, low volcanic rock walls and Mediterranean pines framing the right edge. Beyond the terrace the sea opens in late afternoon golden-hour haze, with the coastline of the Gulf of Naples softly receding on the horizon. No faces in frame: only a staff member's hands appear mid-shot straightening a towel on a lounger. Warm directional light, natural shadows, shallow depth of field. Palette deep navy blue (#0B3D61), teal accent (#14A3A3), warm neutral tones. Aspirational, calm, premium. 16:9 aspect ratio. No text, no letters, no watermarks, no logos in the image.
```

**Varianti se il risultato non convince:**
- *Più caldo/umano:* aggiungi in coda `Golden hour lighting, warmer skin tones, softer shadows.`
- *Più corporate:* aggiungi in coda `Cooler color grading, minimalist composition, more negative space.`

**Alt text SEO:** `Case study metasearch hotel: terrazza piscina di un hotel 4 stelle a Ischia`

---

## 4. Social card (1:1)

- **Formato:** 1:1 — esporta a 1080×1080 px
- **Dove va:** condivisione su LinkedIn e Instagram (post e carosello di apertura)
- **Nome file consigliato:** `metasearch-hotel-social.webp`

**Prompt:**

```
Clean 3D render, minimal and precise. Two matte navy blocks of clearly different height stand side by side on a warm limestone plinth, the taller one crowned by a thin glowing teal ring that hovers just above it, suggesting a superior return without any chart, dial or numeric marking. Centred symmetrical composition with generous negative space in the upper third of the frame. Soft top-left key light, gentle contact shadows beneath the plinth, subtle depth-of-field blur on a warm neutral backdrop washed with a faint Mediterranean blue gradient. Eye-level camera, slightly tilted for depth. Palette deep navy blue (#0B3D61), teal accent (#14A3A3), warm neutral tones. Confident, data-driven, premium mood. 1:1 square format. No text, no letters, no watermarks, no logos in the image.
```

**Varianti se il risultato non convince:**
- *Più caldo/umano:* aggiungi in coda `Golden hour lighting, warmer skin tones, softer shadows.`
- *Più corporate:* aggiungi in coda `Cooler color grading, minimalist composition, more negative space.`

**Alt text SEO:** `Metasearch hotel: confronto tra costo diretto e commissione OTA in una composizione astratta`

---

## Checklist post-generazione

- [ ] Ritaglia/ridimensiona: 1200×675 px (copertina e interne), 1080×1080 px (social)
- [ ] Converti in **WebP** qualità 82 (peso target < 120 KB)
- [ ] Comprimi con [Squoosh](https://squoosh.app) o TinyPNG
- [ ] Nome file descrittivo in kebab-case (vedi sopra)
- [ ] Inserisci l'**alt text** indicato in ogni prompt
- [ ] Carica in WordPress → Media, poi assegna la copertina come *Immagine in evidenza*
