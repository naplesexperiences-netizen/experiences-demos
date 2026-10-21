# Articoli Destinazioni — uno per ogni struttura

Un articolo B2C per ciascuna struttura ricettiva presente nell'hub delle demo:
**perché visitare la città della struttura e i suoi dintorni**. Pensati per il
blog della struttura (piano Pro: "SEO avanzato + blog") o per naplesexperiences.com.

Stesso formato del resto di `blog-articles/` (WordPress-ready):

```
NN-<slug>/
├── article.html   ← contenuto HTML (wrapper .prose-blog, nessun <h1>)
└── seo.json       ← titolo, meta, keyword, tag, schema JSON-LD Article
```

Quando più strutture stanno nella stessa località (es. 7 a Marina di Camerota.
6 a Paestum, 5 a Positano) ogni articolo ha un **taglio diverso** — mare, borgo,
trekking, gastronomia, famiglie, itinerario — così non c'è contenuto duplicato
e gli articoli della stessa zona si linkano fra loro come topic cluster.

## Rigenerare footer e `seo.json`

Il corpo di ogni `article.html` è scritto a mano. Il blocco fra
`<!-- destinazioni:footer -->` e `<!-- /destinazioni:footer -->` ("Leggi anche"
+ CTA verso la demo della struttura) e i `seo.json` li genera lo script:

```bash
python3 scripts/build-destinazioni.py
```

Titoli, keyword, meta description e tag stanno nella tabella `ARTICLES` dello
script: modificali lì e rilancia. I link "Leggi anche" usano `/blog/<slug>/`
(vedi "Permalink richiesti" nel README principale).

## Da verificare prima della pubblicazione

- Orari, prezzi e aperture (musei, grotte, traghetti) non sono citati di proposito:
  cambiano ogni stagione. Le distanze sono indicative.
- Le informazioni sulle strutture vengono solo dai dati delle demo (stelle,
  posizione, servizi dichiarati). Fatele confermare al cliente.
- Il pulsante finale porta al sito ufficiale della struttura (tabella `SITES`
  nello script). Villa Crawford e Terme di Vivara non hanno un sito noto e puntano
  ancora alla demo.
- Escluse: Marèzzo (struttura di fantasia), il template FMA (8 case in 8 regioni),
  i template generici.

## Elenco (56 articoli, ~34.328 parole)

| # | Struttura | Località | Titolo | Focus keyword | Parole |
|---|---|---|---|---|---|
| 01 | Hotel Vesuvio | Napoli | Perché visitare Napoli: il lungomare, il centro storico UNESCO e il Golfo da scoprire | cosa vedere a napoli | 696 |
| 02 | Villa Crawford | Sant'Agnello | Sant'Agnello e la Penisola Sorrentina: la base tranquilla per Sorrento, Capri e Pompei | cosa vedere a sant'agnello | 649 |
| 03 | Terme di Vivara | Ischia | Ischia, l'isola verde: terme, Sant'Angelo e giardini — perché visitarla | cosa vedere a ischia | 587 |
| 04 | Le Sirenuse | Positano | Positano, il borgo verticale: perché visitarlo e cosa vedere fra scalinate e cupole | cosa vedere a positano | 625 |
| 05 | Il San Pietro di Positano | Positano | Positano dal mare: Li Galli, Capri e le grotte della Costiera in barca | positano in barca | 634 |
| 06 | Villa Treville | Positano | La Positano degli artisti: Steinbeck, Zeffirelli, Nureyev e i luoghi che li hanno stregati | positano artisti | 627 |
| 07 | Hotel Le Agavi | Positano | Le spiagge di Positano: Spiaggia Grande, Fornillo, Arienzo e Laurito | spiagge positano | 571 |
| 08 | Hotel Villa Franca | Positano | Positano a piedi: Sentiero degli Dei, Nocelle e Montepertuso | sentiero degli dei positano | 617 |
| 09 | Hotel Margherita | Praiano | Praiano, il borgo dei tramonti: perché visitarlo e cosa vedere | cosa vedere a praiano | 641 |
| 10 | Hotel Onda Verde | Praiano | Praiano e il mare: Marina di Praia, la Gavitella e il Fiordo di Furore | praiano spiagge | 668 |
| 11 | Furore Grand Hotel | Furore | Furore, il paese che non c'è: il Fiordo, i muri dipinti e le vigne sospese | fiordo di furore | 605 |
| 12 | Hotel Belvedere | Conca dei Marini | Conca dei Marini e la Grotta dello Smeraldo: il borgo marinaro più discreto della Costiera | grotta dello smeraldo | 650 |
| 13 | Monastero Santa Rosa | Conca dei Marini | Conca dei Marini, il borgo della sfogliatella Santa Rosa: storia, sapori e dintorni | sfogliatella santa rosa | 620 |
| 14 | Hotel Rufolo | Ravello | Ravello e Villa Rufolo: la città della musica sopra la Costiera | villa rufolo ravello | 594 |
| 15 | Hotel Villa Cimbrone | Ravello | Villa Cimbrone e la Terrazza dell'Infinito: perché Ravello vale il viaggio | terrazza dell'infinito | 637 |
| 16 | Palazzo Avino | Ravello | Ravello come base per la Costiera: Amalfi, Atrani, Scala e Minori a portata di passo | ravello dintorni | 666 |
| 17 | Hotel Raito | Vietri sul Mare | Raito e Vietri sul Mare: la ceramica, Villa Guariglia e la Costiera che comincia | raito vietri sul mare | 617 |
| 18 | Lloyd's Baia Hotel | Vietri sul Mare | Vietri sul Mare: ceramiche, i Due Fratelli e Cetara a un passo | cosa vedere a vietri sul mare | 650 |
| 19 | Hotel Polo Nautico | Salerno | Perché visitare Salerno: lungomare, Duomo e Scuola Medica, fra Costiera e Cilento | cosa vedere a salerno | 597 |
| 20 | Agriturismo Seliano | Paestum | Paestum oltre i templi: la campagna delle bufale e la mozzarella DOP | mozzarella di bufala paestum | 670 |
| 21 | Hotel Schuhmann | Paestum | Paestum, templi e mare: perché visitare l'unica città greca con la spiaggia a un chilometro | cosa vedere a paestum | 585 |
| 22 | L'Araba Fenice Hotel & Resort | Paestum | Paestum archeologica: il Museo, la Tomba del Tuffatore e il santuario di Hera al Sele | tomba del tuffatore | 680 |
| 23 | Mec Paestum Hotel | Paestum | Un weekend a Paestum: itinerario di due giorni fra templi, mare e caseifici | weekend a paestum | 652 |
| 24 | Savoy Hotel & Spa Pagano | Paestum | Paestum lenta: pineta, Oasi Dunale e spiagge lunghe chilometri | spiagge paestum | 603 |
| 25 | Paestum Inn Beach Resort | Paestum | Paestum con i bambini: templi, spiaggia e fattorie per una vacanza in famiglia | paestum con bambini | 591 |
| 26 | Hotel La Darsena Residence | Agropoli | Agropoli dal porto: gite in barca verso Trentova, Tresino e Punta Licosa | agropoli in barca | 632 |
| 27 | Hotel Mare | Agropoli | Agropoli: il centro storico, il castello e il mare della porta del Cilento | cosa vedere ad agropoli | 607 |
| 28 | Hotel Ristorante Il Ceppo | Agropoli | Il Cilento a tavola partendo da Agropoli: cosa mangiare e dove trovarlo | cucina cilentana | 618 |
| 29 | San Francesco Resort | Agropoli | Agropoli, porta del Cilento: perché sceglierla come base e cosa vedere nei dintorni | agropoli dintorni | 602 |
| 30 | Grand Hotel Santa Maria | Santa Maria di Castellabate | Il mare di Santa Maria di Castellabate: Pozzillo, l'Area Marina Protetta e Punta Licosa | santa maria di castellabate mare | 567 |
| 31 | Hotel Villa Sirio | Santa Maria di Castellabate | Castellabate, il borgo di Benvenuti al Sud: castello, belvedere e vicoli | castellabate cosa vedere | 610 |
| 32 | Hotel Costa d'Oro | Santa Maria di Castellabate | Punta Licosa a piedi: il sentiero, la sirena Leucosia e il mare più limpido del Cilento | punta licosa | 595 |
| 33 | Prince Franklyn Hotel | Santa Maria di Castellabate | Tre giorni a Castellabate: itinerario fra borgo, mare e Cilento costiero | castellabate itinerario | 610 |
| 34 | New Hotel Sonia | San Marco di Castellabate | San Marco di Castellabate: il porto antico, le calette e il Cilento a misura di passo | san marco di castellabate | 601 |
| 35 | Hotel Acciaroli | Acciaroli | Acciaroli: il mare Bandiera Blu, il porto e la leggenda di Hemingway | acciaroli cosa vedere | 625 |
| 36 | Hotel La Playa | Acciaroli | Pollica e il segreto della longevità: Acciaroli, Pioppi, Celso e il Monte Stella | pollica longevità | 625 |
| 37 | Hotel La Vela | Pioppi | Pioppi, la culla della dieta mediterranea: Ancel Keys, il museo vivente e il mare | pioppi dieta mediterranea | 607 |
| 38 | Eleamare Resort | Ascea | Velia, la città di Parmenide: perché visitare gli scavi di Elea e la costa di Ascea | scavi di velia | 617 |
| 39 | Victoria Resort | Ascea | Ascea fra borgo e spiagge: il Cilento che si vive in due altitudini | cosa vedere ad ascea | 601 |
| 40 | MagicoMar Hotel | Marina di Ascea | Marina di Ascea: spiaggia lunga, Casal Velino e Palinuro a mezz'ora | marina di ascea | 561 |
| 41 | Marulivo Hotel | Pisciotta | Pisciotta, il borgo medievale fra gli ulivi: perché fermarsi qui | pisciotta cosa vedere | 571 |
| 42 | Hotel Riviera | Pisciotta | Le alici di menaica di Pisciotta: la pesca antica che è diventata Presidio Slow Food | alici di menaica | 586 |
| 43 | Villaggio Club La Maree | Pisciotta | Il mare di Pisciotta: Marina di Pisciotta, Caprioli e la costa verso Palinuro | marina di pisciotta | 537 |
| 44 | Ruggiero Park Hotel | Vallo della Lucania | Vallo della Lucania e il Cilento interno: il Gelbison, i borghi e il Parco a portata di mano | vallo della lucania | 641 |
| 45 | Albergo Santa Caterina | Palinuro | Le grotte di Capo Palinuro: il giro in barca che vale da solo il viaggio | grotte di palinuro | 636 |
| 46 | Hotel La Torre | Palinuro | Palinuro oltre il mare: il mito di Virgilio, la Molpa e il borgo fantasma di San Severino | palinuro cosa vedere | 642 |
| 47 | Albergo Delfino | Marina di Camerota | Marina di Camerota, Infreschi e Cala Bianca: il mare più bello del Cilento | marina di camerota cosa vedere | 553 |
| 48 | Cala d'Arconte Hotel Village | Marina di Camerota | Le calette di Marina di Camerota: dove fare il bagno, dalla spiaggia del paese alle baie in barca | spiagge marina di camerota | 543 |
| 49 | Hotel La Scogliera | Marina di Camerota | Marina di Camerota sott'acqua e sottoterra: grotte preistoriche, snorkeling e immersioni | grotte marina di camerota | 619 |
| 50 | Hotel Riccio | Marina di Camerota | Camerota oltre la spiaggia: il borgo, Licusati, Lentiscosa e la cucina di collina | camerota borgo | 553 |
| 51 | Odissea Camping Village | Marina di Camerota | Il sentiero degli Infreschi: trekking e natura da Marina di Camerota | sentiero degli infreschi | 636 |
| 52 | Villaggio La Barca | Marina di Camerota | Marina di Camerota con i bambini: spiagge comode, barche e piccole avventure | marina di camerota bambini | 633 |
| 53 | Villaggio Nessuno | Marina di Camerota | Una settimana a Marina di Camerota: itinerario giorno per giorno fra mare e Cilento | marina di camerota itinerario | 621 |
| 54 | Hotel Ristorante Cavaliere | Scario | Scario, il porto sul Golfo di Policastro: la costa della Masseta e San Giovanni a Piro | scario cosa vedere | 589 |
| 55 | Hotel Pisacane | Sapri | Sapri e la Spigolatrice: la storia di Pisacane e la città sul Golfo di Policastro | sapri cosa vedere | 553 |
| 56 | Hotel Tirreno | Sapri | Da Sapri al Golfo di Policastro: Maratea, Morigerati e la Certosa di Padula | golfo di policastro | 605 |
