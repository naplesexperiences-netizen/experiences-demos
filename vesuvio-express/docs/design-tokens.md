# Design token — Vesuvio Express 2027

Valori macchina: `design/tokens.json` (DTCG) e `design/tokens.css`.
Versione visiva: pagina **Design system** del file Figma
<https://www.figma.com/design/ESMMSzTDRTsMLsVJzDG0At>.

## Colore

La palette parte dai **tre colori del logo** (`design/brand/logo-vesuvio-express.png`),
misurati sui pixel del file: verde della montagna, azzurro della scritta e delle ruote,
giallo della «O» e del motto. Nessuno dei tre, così com'è, regge testo bianco in AA:
per questo ognuno ha una variante scurita per il testo, e il giallo lavora con testo scuro.

| Token | Hex | Origine | Compito |
|---|---|---|---|
| **Giallo Vesuvio** | `#F7A707` | logo | **Solo** azioni di prenotazione, con testo Basalto (8.2:1). Il colore più caldo del logo porta all'azione: se è giallo, si prenota. |
| Giallo scuro | `#DE9606` | variante | Hover/pressed dell'azione (Basalto 6.6:1). |
| **Verde Vesuvio** | `#009D44` | logo | Solo grafica: profilo del vulcano, illustrazioni, fasce senza testo piccolo. |
| Verde bosco | `#00662C` | variante | Stato «Gran Cono aperto», conferme, bottone WhatsApp, spunte «incluso» (bianco 7.2:1, su Cenere 6.0:1). |
| **Azzurro Express** | `#00A0E3` | logo | Linea del percorso su fondo Basalto (5.6:1), acqua nelle mappe. Mai testo su chiaro. |
| Azzurro profondo | `#006088` | variante | Link, informazioni, anello di focus (su Cenere 5.8:1). |
| **Basalto** | `#231F1C` | neutro | Testo, superfici scure. Nero caldo, legato alla pietra lavica. |
| **Cenere** | `#EEEAE3` | neutro | Sfondo pagina: fa risaltare i tre colori del logo meglio del bianco e riverbera meno al sole. |
| Scoria | `#A93F24` | funzionale | Fuori dal logo, di proposito: solo avvisi (vento, chiusure) con testo bianco. Deve distinguersi dal giallo dell'azione. |

Neutri di supporto: `basalto-2 #3A3430` (footer), `pomice #6B635C` (testo secondario),
`cenere-2 #DDD6CB` (bordi), `carta #F7F5F1` (schede e campi), `scoria-scura #8E331C` (testo errori).

### Contrasti verificati (WCAG 2.2)

| Coppia | Rapporto | Esito |
|---|---|---|
| Basalto su Cenere | 13.6:1 | AAA |
| Pomice su Cenere | 4.9:1 | AA |
| Basalto su Giallo Vesuvio (bottone) | 8.2:1 | AAA |
| Basalto su Giallo scuro (hover) | 6.6:1 | AA |
| Bianco su Verde bosco | 7.2:1 | AAA |
| Verde bosco su Cenere (spunte, stato) | 6.0:1 | AA |
| Azzurro profondo su Cenere (link, focus) | 5.8:1 | AA |
| Azzurro Express su Basalto (linea percorso) | 5.6:1 | AA |
| Giallo Vesuvio su Basalto (fermate percorso) | 8.2:1 | AAA |
| Bianco su Scoria (avvisi) | 6.1:1 | AA |
| Scoria scura su Carta (errori) | 7.3:1 | AAA |

Non passano, e quindi **non si usano per il testo**: bianco su Verde Vesuvio (3.6:1),
bianco su Azzurro Express (2.9:1), bianco su Giallo Vesuvio (2.0:1).

**Da sapere**: il bottone giallo su fondo Cenere ha poco contrasto di bordo (1.7:1). Il
bottone resta riconoscibile perché l'etichetta è a 8.2:1 e il giallo compare solo sulle
azioni. Se nei test con utenti non basta, aggiungiamo un bordo inferiore Basalto di 2 px.

## Tipografia

| Ruolo | Famiglia | Perché |
|---|---|---|
| Titoli, prezzi, orari | **Archivo** (variabile, asse `wdth` a 87 ≈ semi-condensato) | Grottesca con radici nella segnaletica e nei trasporti; cifre tabellari per allineare orari e prezzi in colonna; la larghezza ridotta fa stare un titolo a 40 px in 3 righe su 360 px. |
| Testo e interfaccia | **Atkinson Hyperlegible Next** | Disegnata per la leggibilità a bassa visione (Braille Institute): lettere ambigue ben distinte (I/l/1, 0/O). Utile a chi legge in fretta, al sole, in una lingua non sua. |
| Giapponese | Noto Sans JP | Archivo e Atkinson non hanno kana/kanji. |
| Cinese semplificato | Noto Sans SC | Idem per gli hanzi. |
| Arabo (RTL) | Noto Sans Arabic | Copertura completa, pesi coerenti. |

Il cambio famiglia è automatico con `:lang(ja|zh|ar)` sulle variabili `--font-display` e `--font-body`.
I font CJK si caricano **solo** nelle rispettive lingue, con subset (`next/font/google` con `subsets`/`preload: false`) per non pesare sull'LCP delle lingue latine.

### Scala (mobile 360 → desktop 1200, fluida con `clamp()`)

| Token | Mobile | Desktop | Interlinea | Peso |
|---|---|---|---|---|
| `display` | 40 | 72 | 1.04 | Archivo 700 |
| `h1` | 32 | 48 | 1.1 | Archivo 700 |
| `h2` | 26 | 36 | 1.15 | Archivo 600 |
| `h3` | 20 | 24 | 1.25 | Archivo 600 |
| `data` (prezzi, orari) | 28 | 32 | 1.1 | Archivo 600, `tnum` |
| `body-l` | 18 | 20 | 1.5 | Atkinson 400 |
| `body` | 17 | 17 | 1.5 | Atkinson 400 |
| `ui` (bottoni, etichette) | 16 | 16 | 1.3 | Atkinson 600 |
| `small` | 14 | 14 | 1.45 | Atkinson 400 |

Il corpo del testo parte da 17 px, non 16: target mobile, luce forte, lettori non madrelingua.
Titoli sempre in tondo, mai corsivo; nessuna etichetta in maiuscolo sopra i titoli.

## Spaziatura e forma

- Scala 4 pt: `4 · 8 · 12 · 16 · 24 · 32 · 48 · 64 · 96 · 128`.
- Margine laterale: 16 px a 360 px, fino a 32 px; contenitore massimo 1200 px; misura del testo 68ch.
- Raggi: 4 px campi e bottoni, 8 px schede e widget, pill solo per il chip di stato.
- Area di tocco minima 48 × 48 px.
- **Un'unica ombra** nel sistema: la barra di prenotazione sticky. Le schede si distinguono con bordo e fondo, e il prodotto principale ha un bordo Basalto di 2 px: niente schede identiche con la stessa ombra.

## Movimento

- 150 ms (stati) e 250 ms (aperture), `cubic-bezier(0.2, 0.7, 0.2, 1)`; solo `transform` e `opacity`.
- Nessuna animazione d'ingresso sulle sezioni. L'unico movimento «narrativo» è il riempimento della linea nel percorso «Dalla stazione al cratere», e con `prefers-reduced-motion` viene mostrato già completo.
- L'anello di focus (Azzurro profondo, 3 px) compare subito, senza transizione.
