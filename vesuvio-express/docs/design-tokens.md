# Design token — Vesuvio Express 2027

Valori macchina: `design/tokens.json` (DTCG) e `design/tokens.css`.
Versione visiva: pagina **Design system** del file Figma
<https://www.figma.com/design/ESMMSzTDRTsMLsVJzDG0At>.

## Colore

Sei colori legati al luogo. Ognuno ha **un solo compito**, così il colore stesso diventa informazione.

| Token | Hex | Compito | Perché questo colore |
|---|---|---|---|
| **Basalto** | `#231F1C` | Testo, superfici scure, linea del percorso | La pietra lavica delle colate. Un nero caldo, non il `#000` digitale. |
| **Cenere** | `#EEEAE3` | Sfondo pagina | La cenere chiara del sentiero del Gran Cono. Riverbera meno del bianco puro quando si legge al sole, col telefono in mano. |
| **Scoria** | `#A93F24` | **Solo** azioni di prenotazione | Il rosso ossidato dei lapilli sul bordo del cratere. Regola: se è rosso, si prenota. Nessun uso decorativo, così il bottone si trova a colpo d'occhio. |
| **Leccio** | `#3D5A3A` | Stato «aperto», conferme, WhatsApp | Il verde scuro dei lecci e della macchia mediterranea del Parco. Dice «si può salire». |
| **Ginestra** | `#E6B422` | Avvisi (vento, chiusure) e accento su fondo scuro | La ginestra che colonizza le colate del Vesuvio (Leopardi). Usata solo come **fondo** con testo Basalto, o come segno su Basalto. Mai testo giallo su chiaro. |
| **Golfo** | `#1D4F6E` | Link, informazioni, anello di focus, acqua nelle mappe | Il blu del Golfo visto dal cratere. |

Neutri di supporto: `basalto-2 #3A3430` (footer), `pomice #6B635C` (testo secondario),
`cenere-2 #DDD6CB` (bordi), `carta #F7F5F1` (schede e campi), `scoria-scura #8E331C` (hover ed errori).

### Contrasti verificati (WCAG 2.2)

| Coppia | Rapporto | Esito |
|---|---|---|
| Basalto su Cenere | 13.6:1 | AAA |
| Pomice su Cenere | 4.9:1 | AA testo normale |
| Bianco su Scoria | 6.1:1 | AA |
| Bianco su Scoria scura (hover) | 8.0:1 | AAA |
| Bianco su Leccio | 7.7:1 | AAA |
| Basalto su Ginestra | 8.5:1 | AAA |
| Ginestra su Basalto | 8.5:1 | AAA |
| Golfo su Cenere (link, focus) | 7.3:1 | AAA |
| Scoria su Carta (testo errore) | 5.6:1 | AA |

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
- L'anello di focus (Golfo, 3 px) compare subito, senza transizione.
