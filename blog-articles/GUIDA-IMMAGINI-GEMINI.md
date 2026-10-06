# Guida Immagini Blog — Generazione con Google Gemini

Come produrre le immagini per gli articoli del blog di Experiences Srl
mantenendo uno stile visivo coerente su tutto il catalogo.

Ogni cartella articolo contiene un file `image-prompts.md` con i prompt
già scritti e pronti da incollare. Questa guida spiega il processo
attorno a quei prompt.

---

## 1. Perché le immagini contano (non è estetica)

| Effetto | Impatto misurato |
|---------|------------------|
| Tempo di permanenza sulla pagina | +38% con almeno un'immagine ogni 500 parole |
| Condivisioni social | Un post con Open Graph image corretta ottiene 2-3× i click |
| CTR nei risultati Google Discover | Le immagini ≥1200px larghe sono requisito per l'inclusione |
| Percezione di autorevolezza | Determinante per un pubblico B2B che valuta un fornitore |

Un articolo da 2.300 parole senza immagini viene percepito come "muro di
testo" e abbandonato entro 15 secondi dal 60% dei lettori mobile.

---

## 2. Quante immagini per articolo

Il minimo indispensabile è **1**, il consigliato è **3**:

| # | Immagine | Formato | Dove va | Priorità |
|---|----------|---------|---------|----------|
| 1 | Copertina | 16:9 · 1200×675 | Immagine in evidenza WordPress + Open Graph | **Obbligatoria** |
| 2 | Concettuale | 16:9 · 1200×675 | Dentro l'articolo, a metà scroll | Consigliata |
| 3 | Social card | 1:1 · 1080×1080 | LinkedIn / Instagram in fase di condivisione | Consigliata |
| 4 | Case study | 16:9 · 1200×675 | Prima della sezione con i numeri | Facoltativa |

Se hai poco tempo: fai solo la copertina per tutti gli articoli, poi
torna indietro ad aggiungere le altre.

---

## 3. Il workflow, dall'inizio alla fine

```
   ┌──────────────────────┐
   │ image-prompts.md     │  ← già pronto nella cartella articolo
   │ (copia il prompt)    │
   └──────────┬───────────┘
              │
              ▼
   ┌──────────────────────┐
   │ Google Gemini        │  gemini.google.com
   │ incolla e genera     │  → genera 1-4 varianti
   └──────────┬───────────┘
              │  scegli la migliore
              ▼
   ┌──────────────────────┐
   │ Ritaglia a 16:9      │  Squoosh / Photopea / Canva
   │ 1200×675 px          │
   └──────────┬───────────┘
              │
              ▼
   ┌──────────────────────┐
   │ Converti in WebP     │  squoosh.app → qualità 82
   │ target < 120 KB      │
   └──────────┬───────────┘
              │
              ▼
   ┌──────────────────────┐
   │ WordPress → Media    │  nome file in kebab-case
   │ + alt text SEO       │  (l'alt text è nel prompt file)
   └──────────────────────┘
```

---

## 4. Come usare Gemini in pratica

1. Vai su [gemini.google.com](https://gemini.google.com)
2. Apri il file `image-prompts.md` della cartella dell'articolo
3. Copia **l'intero blocco di testo** dentro le triple virgolette
4. Incollalo in Gemini e invia
5. Gemini genera l'immagine (a volte più varianti)
6. Se non convince, usa le **varianti suggerite** in fondo a ogni prompt:
   incolla di nuovo il prompt aggiungendo in coda la frase indicata

### Se il risultato non va bene, in ordine

1. **Rigenera** — stesso prompt, risultato diverso. Spesso basta questo.
2. **Applica una variante** — quelle scritte nel file sono già calibrate
3. **Aggiungi un vincolo** — es. `Shot on 35mm lens, shallow depth of field`
4. **Togli complessità** — se hai chiesto 3 soggetti, riduci a 1

---

## 5. Convenzioni brand (già dentro tutti i prompt)

| Elemento | Regola |
|----------|--------|
| Stile | Fotografia editoriale realistica o render 3D pulito — mai illustrazione cartoon |
| Palette | Navy `#0B3D61`, teal `#14A3A3`, neutri caldi |
| Luce | Naturale, preferibilmente golden hour per gli esterni |
| Ambientazione | Mediterranea: Napoli, Costiera, Ischia, Sorrento, Pompei |
| Volti | Tre quarti, di spalle o solo mani — mai primi piani frontali |
| Testo nell'immagine | **Mai.** Ogni prompt termina con `No text, no letters…` |

### Perché "no text"

I modelli di generazione immagini producono testo illeggibile o con
errori di ortografia. Se ti serve del testo sopra l'immagine (es. per la
social card), sovrapponilo dopo con Canva o Figma.

---

## 6. Ottimizzazione: i numeri che contano

Un'immagine non ottimizzata può da sola raddoppiare il tempo di
caricamento della pagina — vanificando il lavoro fatto sulle performance
del tema.

| Parametro | Valore target | Perché |
|-----------|--------------|--------|
| Larghezza | 1200 px | Sufficiente per retina, requisito Google Discover |
| Formato | WebP | 25-35% più leggero di JPEG a parità di qualità |
| Qualità | 82 | Sotto 80 iniziano gli artefatti visibili sulle sfumature |
| Peso finale | < 120 KB | Sopra questa soglia impatta l'LCP su mobile |
| Nome file | `slug-descrizione.webp` | Segnale SEO, kebab-case, niente spazi o accenti |

**Strumenti gratuiti:**
- [Squoosh](https://squoosh.app) — ridimensiona + converte + comprime, tutto in una schermata
- [TinyPNG](https://tinypng.com) — compressione batch, supporta WebP
- [Photopea](https://photopea.com) — ritaglio preciso, gratis, gira nel browser

---

## 7. Alt text: come è scritto e perché

Ogni prompt nel file `image-prompts.md` è accompagnato da un **alt text
già pronto** in italiano. Non improvvisarlo: è scritto per contenere la
focus keyword dell'articolo in modo naturale.

**Buono:** `Dashboard di un channel manager per hotel con calendario sincronizzato`
**Cattivo:** `immagine`, `foto hotel`, `channel-manager-hotel-seo-keyword`

L'alt text serve a tre cose: accessibilità per screen reader (obbligo
normativo per i siti aziendali), indicizzazione su Google Immagini, e
fallback se l'immagine non carica.

---

## 8. Nota legale sulle immagini AI

Le immagini generate con Gemini sono utilizzabili commercialmente
secondo i termini di Google. Due accortezze:

1. **Non generare volti riconoscibili di persone reali** né riproduzioni
   di marchi altrui (loghi Booking, Airbnb, ecc.). Tutti i prompt del
   catalogo sono già scritti per evitarlo.
2. **Per i case study**, le immagini sono illustrative e non ritraggono
   le strutture reali citate. Se un cliente chiede di essere mostrato,
   servono foto vere e liberatoria scritta.

Se in futuro servisse una dichiarazione di trasparenza sull'uso di AI
nei contenuti visivi, il posto giusto è una riga nella pagina
[Termini e Condizioni](/termini-e-condizioni/).

---

## 9. Alternative a Gemini

Se preferisci un altro strumento, i prompt del catalogo funzionano quasi
identici su:

| Strumento | Note |
|-----------|------|
| **Midjourney** | Resa fotografica superiore. Aggiungi `--ar 16:9 --style raw` in coda |
| **DALL·E 3** (ChatGPT) | Segue le istruzioni molto fedelmente, meno "fotografico" |
| **Adobe Firefly** | Licenza commerciale esplicita, integrato con Photoshop |
| **Flux** (via Replicate) | Ottimo realismo, richiede un minimo di setup tecnico |

Per Midjourney ricorda di sostituire la frase finale `No text…` con il
parametro `--no text, letters, watermark, logo`.

---

## 10. Stock photo: quando ha più senso

Le immagini generate non sono sempre la scelta migliore. Usa foto stock
o foto reali quando:

- Devi mostrare un **luogo reale riconoscibile** (il Vesuvio, un hotel
  specifico, piazza del Plebiscito) — l'AI lo inventa in modo impreciso
- Serve una **persona in un contesto professionale credibile** — i volti
  generati restano il punto debole
- Hai **foto vere della struttura** cliente — sempre preferibili

**Risorse gratuite:** [Unsplash](https://unsplash.com), [Pexels](https://pexels.com)
(cerca: `naples italy`, `amalfi coast hotel`, `hotel reception`, `tour guide`)

**A pagamento:** Shutterstock, Adobe Stock (€10-30/immagine, licenza estesa
e maggiore scelta su soggetti italiani specifici)

---

*Ogni cartella articolo contiene il proprio `image-prompts.md` con i
prompt specifici già scritti. Questa guida copre solo il processo attorno.*
