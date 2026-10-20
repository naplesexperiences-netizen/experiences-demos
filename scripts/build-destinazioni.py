#!/usr/bin/env python3
"""Builds the "destinazioni" blog articles: one per accommodation demo.

For every entry in ARTICLES this script:
  * writes blog-articles/destinazioni/<NN>-<slug>/seo.json
  * (re)writes the footer block of article.html between the markers
    <!-- destinazioni:footer --> and <!-- /destinazioni:footer -->
    with the "Leggi anche" links (same area) and the CTA to the demo.

The article body is hand-written and never touched. Idempotent: run it
again after editing the table below.
"""
import html
import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / "blog-articles" / "destinazioni"
DEMO_BASE = "https://naplesexperiences-netizen.github.io/experiences-demos/demos"
SITE = "https://naplesexperiences.com"

# (slug, struttura, demo, città, area, post_title, focus_keyword, meta_description, tags)
ARTICLES = [
    ("perche-visitare-napoli-lungomare-centro-storico", "Hotel Vesuvio", "test-hotel-vesuvio", "Napoli", "Golfo di Napoli",
     "Perché visitare Napoli: il lungomare, il centro storico UNESCO e il Golfo da scoprire",
     "cosa vedere a napoli",
     "Cosa vedere a Napoli partendo dal lungomare di Via Partenope: centro storico UNESCO, Castel dell'Ovo, Pompei, Vesuvio e le isole del Golfo.",
     ["napoli", "lungomare", "centro storico", "pompei", "vesuvio", "golfo di napoli"]),
    ("sant-agnello-penisola-sorrentina-cosa-vedere", "Villa Crawford", "villa-crawford-sorrento-gsap-experiences", "Sant'Agnello", "Penisola Sorrentina",
     "Sant'Agnello e la Penisola Sorrentina: la base tranquilla per Sorrento, Capri e Pompei",
     "cosa vedere a sant'agnello",
     "Sant'Agnello, accanto a Sorrento: perché sceglierla, cosa vedere in Penisola Sorrentina e come raggiungere Capri, Pompei e la Costiera.",
     ["sant'agnello", "sorrento", "penisola sorrentina", "capri", "punta campanella"]),
    ("ischia-perche-visitare-isola-verde-terme", "Terme di Vivara", "terme-di-vivara-ischia-demo-experiences", "Ischia", "Isole del Golfo",
     "Ischia, l'isola verde: terme, Sant'Angelo e giardini — perché visitarla",
     "cosa vedere a ischia",
     "Perché visitare Ischia: sorgenti termali, Castello Aragonese, il borgo di Sant'Angelo, i giardini La Mortella e la vicina Procida.",
     ["ischia", "terme", "sant'angelo", "castello aragonese", "procida"]),
    ("positano-cosa-vedere-borgo-verticale", "Le Sirenuse", "le-sirenuse-experiences-standard", "Positano", "Positano",
     "Positano, il borgo verticale: perché visitarlo e cosa vedere fra scalinate e cupole",
     "cosa vedere a positano",
     "Cosa vedere a Positano: Santa Maria Assunta, la villa romana, la Spiaggia Grande e i dintorni della Costiera Amalfitana da non perdere.",
     ["positano", "costiera amalfitana", "santa maria assunta", "spiaggia grande"]),
    ("positano-in-barca-li-galli-capri-grotte", "Il San Pietro di Positano", "il-san-pietro-di-positano-experiences-standard", "Positano", "Positano",
     "Positano dal mare: Li Galli, Capri e le grotte della Costiera in barca",
     "positano in barca",
     "Positano vista dal mare: gite in barca a Li Galli, Capri e alla Grotta dello Smeraldo, spiagge raggiungibili solo via acqua e consigli pratici.",
     ["positano", "gita in barca", "li galli", "capri", "costiera amalfitana"]),
    ("positano-artisti-zeffirelli-nureyev-steinbeck", "Villa Treville", "villa-treville-experiences-standard", "Positano", "Positano",
     "La Positano degli artisti: Steinbeck, Zeffirelli, Nureyev e i luoghi che li hanno stregati",
     "positano artisti",
     "Positano e i suoi ospiti illustri: da Steinbeck a Zeffirelli e Nureyev, i luoghi della Positano creativa e cosa vedere nei dintorni.",
     ["positano", "steinbeck", "zeffirelli", "nureyev", "li galli", "arienzo"]),
    ("spiagge-positano-fornillo-arienzo-laurito", "Hotel Le Agavi", "hotel-le-agavi-experiences-standard", "Positano", "Positano",
     "Le spiagge di Positano: Spiaggia Grande, Fornillo, Arienzo e Laurito",
     "spiagge positano",
     "Guida alle spiagge di Positano: Spiaggia Grande, Fornillo, Arienzo e Laurito, come raggiungerle e le calette più belle dei dintorni.",
     ["positano", "spiagge", "fornillo", "arienzo", "laurito", "costiera amalfitana"]),
    ("sentiero-degli-dei-positano-nocelle-montepertuso", "Hotel Villa Franca", "hotel-villa-franca-experiences-standard", "Positano", "Positano",
     "Positano a piedi: Sentiero degli Dei, Nocelle e Montepertuso",
     "sentiero degli dei positano",
     "Positano per chi cammina: Sentiero degli Dei, Nocelle, Montepertuso e Santa Maria del Castello. Percorsi, tempi e consigli pratici.",
     ["positano", "sentiero degli dei", "nocelle", "montepertuso", "trekking"]),
    ("praiano-cosa-vedere-borgo-dei-tramonti", "Hotel Margherita", "hotel-margherita-experiences-standard", "Praiano", "Praiano",
     "Praiano, il borgo dei tramonti: perché visitarlo e cosa vedere",
     "cosa vedere a praiano",
     "Perché visitare Praiano: tramonti su Positano, la cupola di San Gennaro, il Convento di San Domenico e i dintorni della Costiera.",
     ["praiano", "costiera amalfitana", "tramonto", "san gennaro", "luminaria"]),
    ("praiano-mare-marina-di-praia-fiordo-furore", "Hotel Onda Verde", "hotel-onda-verde-experiences-standard", "Praiano", "Praiano",
     "Praiano e il mare: Marina di Praia, la Gavitella e il Fiordo di Furore",
     "praiano spiagge",
     "Il mare di Praiano: Marina di Praia, la spiaggia della Gavitella, il Fiordo di Furore e la Grotta dello Smeraldo a pochi minuti.",
     ["praiano", "marina di praia", "gavitella", "fiordo di furore", "spiagge"]),
    ("furore-fiordo-paese-che-non-ce", "Furore Grand Hotel", "furore-grand-hotel-experiences-standard", "Furore", "Furore",
     "Furore, il paese che non c'è: il Fiordo, i muri dipinti e le vigne sospese",
     "fiordo di furore",
     "Perché visitare Furore: il Fiordo, i muri dipinti, le vigne a picco sul mare e i sentieri verso Agerola e il Sentiero degli Dei.",
     ["furore", "fiordo di furore", "costiera amalfitana", "vino", "sentieri"]),
    ("conca-dei-marini-grotta-dello-smeraldo", "Hotel Belvedere", "hotel-belvedere-experiences-standard", "Conca dei Marini", "Conca dei Marini",
     "Conca dei Marini e la Grotta dello Smeraldo: il borgo marinaro più discreto della Costiera",
     "grotta dello smeraldo",
     "Conca dei Marini: la Grotta dello Smeraldo, la Torre di Capo di Conca, il borgo marinaro e i dintorni fra Amalfi e Furore.",
     ["conca dei marini", "grotta dello smeraldo", "capo di conca", "costiera amalfitana"]),
    ("conca-dei-marini-sfogliatella-santa-rosa", "Monastero Santa Rosa", "monastero-santa-rosa-experiences-standard", "Conca dei Marini", "Conca dei Marini",
     "Conca dei Marini, il borgo della sfogliatella Santa Rosa: storia, sapori e dintorni",
     "sfogliatella santa rosa",
     "La sfogliatella Santa Rosa nasce a Conca dei Marini: la storia del monastero, cosa assaggiare e cosa vedere fra Amalfi e Furore.",
     ["conca dei marini", "sfogliatella santa rosa", "pasticceria", "costiera amalfitana"]),
    ("ravello-villa-rufolo-festival", "Hotel Rufolo", "hotel-rufolo-experiences-standard", "Ravello", "Ravello",
     "Ravello e Villa Rufolo: la città della musica sopra la Costiera",
     "villa rufolo ravello",
     "Perché visitare Ravello: Villa Rufolo e il giardino di Wagner, il Ravello Festival, il Duomo e i borghi vicini di Scala e Atrani.",
     ["ravello", "villa rufolo", "ravello festival", "wagner", "costiera amalfitana"]),
    ("ravello-villa-cimbrone-terrazza-infinito", "Hotel Villa Cimbrone", "hotel-villa-cimbrone-experiences-standard", "Ravello", "Ravello",
     "Villa Cimbrone e la Terrazza dell'Infinito: perché Ravello vale il viaggio",
     "terrazza dell'infinito",
     "Villa Cimbrone e la Terrazza dell'Infinito: storia, giardini e cosa vedere a Ravello e dintorni, dal Duomo all'Auditorium Niemeyer.",
     ["ravello", "villa cimbrone", "terrazza dell'infinito", "giardini", "costiera amalfitana"]),
    ("ravello-base-costiera-amalfi-atrani-scala-minori", "Palazzo Avino", "palazzo-avino-experiences-standard", "Ravello", "Ravello",
     "Ravello come base per la Costiera: Amalfi, Atrani, Scala e Minori a portata di passo",
     "ravello dintorni",
     "Dormire a Ravello e scoprire la Costiera: Amalfi, Atrani, Scala, Minori e i sentieri che li collegano. Itinerario di tre giorni.",
     ["ravello", "amalfi", "atrani", "scala", "minori", "itinerario"]),
    ("raito-vietri-sul-mare-ceramica-villa-guariglia", "Hotel Raito", "hotel-raito-experiences-standard", "Vietri sul Mare", "Vietri sul Mare",
     "Raito e Vietri sul Mare: la ceramica, Villa Guariglia e la Costiera che comincia",
     "raito vietri sul mare",
     "Raito, frazione di Vietri sul Mare: il Museo della Ceramica a Villa Guariglia, le botteghe di Vietri, Cetara e Salerno a pochi minuti.",
     ["vietri sul mare", "raito", "ceramica", "villa guariglia", "costiera amalfitana"]),
    ("vietri-sul-mare-cosa-vedere-due-fratelli-cetara", "Lloyd's Baia Hotel", "lloyds-baia-hotel-experiences-standard", "Vietri sul Mare", "Vietri sul Mare",
     "Vietri sul Mare: ceramiche, i Due Fratelli e Cetara a un passo",
     "cosa vedere a vietri sul mare",
     "Cosa vedere a Vietri sul Mare: il Duomo maiolicato, le botteghe di ceramica, i faraglioni Due Fratelli e la vicina Cetara.",
     ["vietri sul mare", "ceramica", "due fratelli", "cetara", "colatura di alici"]),
    ("perche-visitare-salerno-lungomare-duomo", "Hotel Polo Nautico", "hotel-polo-nautico-experiences-standard", "Salerno", "Salerno",
     "Perché visitare Salerno: lungomare, Duomo e Scuola Medica, fra Costiera e Cilento",
     "cosa vedere a salerno",
     "Cosa vedere a Salerno: lungomare Trieste, Duomo di San Matteo, Giardino della Minerva, Castello di Arechi e le gite in Costiera e a Paestum.",
     ["salerno", "lungomare", "duomo di salerno", "giardino della minerva", "luci d'artista"]),
    ("paestum-mozzarella-di-bufala-campagna", "Agriturismo Seliano", "agriturismo-seliano-experiences-standard", "Paestum", "Paestum",
     "Paestum oltre i templi: la campagna delle bufale e la mozzarella DOP",
     "mozzarella di bufala paestum",
     "La Piana di Paestum fra bufale, caseifici e templi greci: perché visitarla, cosa assaggiare e cosa vedere nei dintorni.",
     ["paestum", "mozzarella di bufala", "agriturismo", "campagna", "cilento"]),
    ("paestum-templi-e-mare-cosa-vedere", "Hotel Schuhmann", "hotel-schuhmann-experiences-standard", "Paestum", "Paestum",
     "Paestum, templi e mare: perché visitare l'unica città greca con la spiaggia a un chilometro",
     "cosa vedere a paestum",
     "Cosa vedere a Paestum: i tre templi dorici, le mura, il museo e le spiagge a pochi passi dall'area archeologica UNESCO.",
     ["paestum", "templi", "unesco", "spiaggia", "magna grecia"]),
    ("paestum-museo-tomba-del-tuffatore-heraion", "L'Araba Fenice Hotel & Resort", "laraba-fenice-hotel-resort-experiences-standard", "Paestum", "Paestum",
     "Paestum archeologica: il Museo, la Tomba del Tuffatore e il santuario di Hera al Sele",
     "tomba del tuffatore",
     "Paestum per chi ama l'archeologia: il Museo con la Tomba del Tuffatore, le mura, l'Heraion alla Foce del Sele e Velia nel Cilento.",
     ["paestum", "tomba del tuffatore", "museo archeologico", "heraion", "velia"]),
    ("paestum-weekend-itinerario-due-giorni", "Mec Paestum Hotel", "mec-paestum-hotel-experiences-standard", "Paestum", "Paestum",
     "Un weekend a Paestum: itinerario di due giorni fra templi, mare e caseifici",
     "weekend a paestum",
     "Itinerario di due giorni a Paestum: area archeologica, museo, spiaggia, caseifici e un tramonto a Capaccio Vecchio. Consigli pratici.",
     ["paestum", "weekend", "itinerario", "capaccio", "cilento"]),
    ("paestum-pineta-oasi-dunale-spiagge", "Savoy Hotel & Spa Pagano", "savoy-hotel-spa-pagano-experiences-standard", "Paestum", "Paestum",
     "Paestum lenta: pineta, Oasi Dunale e spiagge lunghe chilometri",
     "spiagge paestum",
     "Il lato naturale di Paestum: la pineta, l'Oasi Dunale, le spiagge sabbiose, il fiume Sele e le Grotte di Pertosa nei dintorni.",
     ["paestum", "spiagge", "oasi dunale", "pineta", "natura"]),
    ("paestum-con-bambini-famiglie", "Paestum Inn Beach Resort", "paestum-inn-beach-resort-experiences-standard", "Paestum", "Paestum",
     "Paestum con i bambini: templi, spiaggia e fattorie per una vacanza in famiglia",
     "paestum con bambini",
     "Paestum con i bambini: mare basso e sabbioso, templi da esplorare, caseifici e grotte. Idee e consigli per una vacanza in famiglia.",
     ["paestum", "bambini", "famiglie", "spiaggia", "vacanze"]),
    ("agropoli-porto-gite-in-barca", "Hotel La Darsena Residence", "hotel-la-darsena-residence-experiences-standard", "Agropoli", "Agropoli",
     "Agropoli dal porto: gite in barca verso Trentova, Tresino e Punta Licosa",
     "agropoli in barca",
     "Agropoli vista dal porto: lungomare San Marco, gite in barca a Trentova, Tresino e Punta Licosa, e cosa vedere nei dintorni.",
     ["agropoli", "porto", "gita in barca", "trentova", "punta licosa"]),
    ("agropoli-centro-storico-castello", "Hotel Mare", "hotel-mare-experiences-standard", "Agropoli", "Agropoli",
     "Agropoli: il centro storico, il castello e il mare della porta del Cilento",
     "cosa vedere ad agropoli",
     "Cosa vedere ad Agropoli: gli scaloni, il castello angioino-aragonese, Santa Maria di Costantinopoli e le spiagge in centro.",
     ["agropoli", "castello", "centro storico", "cilento", "spiagge"]),
    ("agropoli-cilento-a-tavola-cucina", "Hotel Ristorante Il Ceppo", "hotel-ristorante-il-ceppo-experiences-standard", "Agropoli", "Agropoli",
     "Il Cilento a tavola partendo da Agropoli: cosa mangiare e dove trovarlo",
     "cucina cilentana",
     "Cosa mangiare nel Cilento partendo da Agropoli: mozzarella, fico bianco, alici di menaica, cacioricotta e i presidi Slow Food.",
     ["agropoli", "cucina cilentana", "slow food", "dieta mediterranea", "fico bianco"]),
    ("agropoli-porta-del-cilento-dintorni", "San Francesco Resort", "san-francesco-resort-experiences-standard", "Agropoli", "Agropoli",
     "Agropoli, porta del Cilento: perché sceglierla come base e cosa vedere nei dintorni",
     "agropoli dintorni",
     "Agropoli come base per il Cilento: Paestum, Castellabate, Punta Tresino e i borghi dell'interno, tutti entro 40 minuti.",
     ["agropoli", "cilento", "paestum", "castellabate", "dintorni"]),
    ("santa-maria-di-castellabate-mare-pozzillo-licosa", "Grand Hotel Santa Maria", "grand-hotel-santa-maria-experiences-standard", "Santa Maria di Castellabate", "Castellabate",
     "Il mare di Santa Maria di Castellabate: Pozzillo, l'Area Marina Protetta e Punta Licosa",
     "santa maria di castellabate mare",
     "Il mare di Santa Maria di Castellabate: spiaggia del Pozzillo, Area Marina Protetta, Punta Licosa e il borgo di Castellabate.",
     ["castellabate", "santa maria di castellabate", "pozzillo", "punta licosa", "area marina protetta"]),
    ("castellabate-borgo-castello-benvenuti-al-sud", "Hotel Villa Sirio", "hotel-villa-sirio-experiences-standard", "Santa Maria di Castellabate", "Castellabate",
     "Castellabate, il borgo di Benvenuti al Sud: castello, belvedere e vicoli",
     "castellabate cosa vedere",
     "Castellabate fra Borghi più belli d'Italia e Benvenuti al Sud: il Castello dell'Abate, il belvedere, Santa Maria e i dintorni.",
     ["castellabate", "benvenuti al sud", "borghi più belli d'italia", "castello dell'abate"]),
    ("punta-licosa-sentiero-natura-castellabate", "Hotel Costa d'Oro", "hotel-costa-doro-experiences-standard", "Santa Maria di Castellabate", "Castellabate",
     "Punta Licosa a piedi: il sentiero, la sirena Leucosia e il mare più limpido del Cilento",
     "punta licosa",
     "Come arrivare a Punta Licosa da Santa Maria di Castellabate: il sentiero, la leggenda di Leucosia, le calette e la natura protetta.",
     ["punta licosa", "castellabate", "trekking", "cilento", "natura"]),
    ("castellabate-tre-giorni-itinerario-cilento", "Prince Franklyn Hotel", "prince-franklyn-hotel-experiences-standard", "Santa Maria di Castellabate", "Castellabate",
     "Tre giorni a Castellabate: itinerario fra borgo, mare e Cilento costiero",
     "castellabate itinerario",
     "Itinerario di tre giorni da Santa Maria di Castellabate: il borgo, Punta Licosa, Ogliastro, Agnone, Acciaroli e Paestum.",
     ["castellabate", "itinerario", "cilento", "acciaroli", "paestum"]),
    ("san-marco-di-castellabate-porto-borgo", "New Hotel Sonia", "new-hotel-sonia-experiences-standard", "San Marco di Castellabate", "Castellabate",
     "San Marco di Castellabate: il porto antico, le calette e il Cilento a misura di passo",
     "san marco di castellabate",
     "Perché visitare San Marco di Castellabate: il porto, le calette, il borgo di Castellabate, Punta Licosa e Agropoli a pochi minuti.",
     ["san marco di castellabate", "castellabate", "porto", "cilento", "punta licosa"]),
    ("acciaroli-bandiera-blu-hemingway", "Hotel Acciaroli", "hotel-acciaroli-experiences-standard", "Acciaroli", "Pollica",
     "Acciaroli: il mare Bandiera Blu, il porto e la leggenda di Hemingway",
     "acciaroli cosa vedere",
     "Perché visitare Acciaroli: mare limpido, il borgo dei pescatori, la torre, la leggenda di Hemingway e i dintorni di Pollica.",
     ["acciaroli", "pollica", "bandiera blu", "hemingway", "cilento"]),
    ("pollica-longevita-parco-cilento-monte-stella", "Hotel La Playa", "hotel-la-playa-experiences-standard", "Acciaroli", "Pollica",
     "Pollica e il segreto della longevità: Acciaroli, Pioppi, Celso e il Monte Stella",
     "pollica longevità",
     "Da Acciaroli all'interno di Pollica: la longevità cilentana, Pioppi, Celso, Galdo e il Monte Stella nel Parco Nazionale del Cilento.",
     ["pollica", "acciaroli", "longevità", "monte stella", "parco nazionale del cilento"]),
    ("pioppi-dieta-mediterranea-ancel-keys", "Hotel La Vela", "hotel-la-vela-experiences-standard", "Pioppi", "Pollica",
     "Pioppi, la culla della dieta mediterranea: Ancel Keys, il museo vivente e il mare",
     "pioppi dieta mediterranea",
     "Pioppi e la dieta mediterranea: la storia di Ancel Keys, il Museo Vivente, il mare cristallino e i dintorni fra Acciaroli e Velia.",
     ["pioppi", "dieta mediterranea", "ancel keys", "pollica", "cilento"]),
    ("velia-elea-parmenide-ascea", "Eleamare Resort", "eleamare-resort-experiences-standard", "Ascea", "Ascea",
     "Velia, la città di Parmenide: perché visitare gli scavi di Elea e la costa di Ascea",
     "scavi di velia",
     "Gli scavi di Elea-Velia ad Ascea: Porta Rosa, l'acropoli, la scuola di Parmenide e Zenone e il mare del Cilento a due passi.",
     ["velia", "elea", "parmenide", "ascea", "porta rosa", "magna grecia"]),
    ("ascea-borgo-e-spiagge-cilento", "Victoria Resort", "victoria-resort-experiences-standard", "Ascea", "Ascea",
     "Ascea fra borgo e spiagge: il Cilento che si vive in due altitudini",
     "cosa vedere ad ascea",
     "Cosa vedere ad Ascea: il borgo collinare, Marina di Ascea, Velia e le spiagge lunghe del Cilento. Dintorni e consigli pratici.",
     ["ascea", "marina di ascea", "velia", "spiagge", "cilento"]),
    ("marina-di-ascea-famiglie-casal-velino-palinuro", "MagicoMar Hotel", "magicomar-hotel-experiences-standard", "Marina di Ascea", "Ascea",
     "Marina di Ascea: spiaggia lunga, Casal Velino e Palinuro a mezz'ora",
     "marina di ascea",
     "Marina di Ascea per una vacanza di mare: la spiaggia, Velia, Casal Velino, Pisciotta e Palinuro raggiungibili in pochi minuti.",
     ["marina di ascea", "ascea", "casal velino", "palinuro", "famiglie"]),
    ("pisciotta-borgo-medievale-cilento", "Marulivo Hotel", "marulivo-hotel-experiences-standard", "Pisciotta", "Pisciotta",
     "Pisciotta, il borgo medievale fra gli ulivi: perché fermarsi qui",
     "pisciotta cosa vedere",
     "Perché visitare Pisciotta: il borgo medievale, gli ulivi secolari della pisciottana, Marina di Pisciotta e Palinuro a un passo.",
     ["pisciotta", "borgo medievale", "cilento", "ulivi", "palinuro"]),
    ("alici-di-menaica-pisciotta-slow-food", "Hotel Riviera", "hotel-riviera-experiences-standard", "Pisciotta", "Pisciotta",
     "Le alici di menaica di Pisciotta: la pesca antica che è diventata Presidio Slow Food",
     "alici di menaica",
     "Le alici di menaica di Pisciotta: come si pescano, perché sono Presidio Slow Food, dove assaggiarle e cosa vedere nei dintorni.",
     ["alici di menaica", "pisciotta", "slow food", "cilento", "gastronomia"]),
    ("marina-di-pisciotta-caprioli-mare", "Villaggio Club La Maree", "villaggio-club-la-maree-experiences-standard", "Pisciotta", "Pisciotta",
     "Il mare di Pisciotta: Marina di Pisciotta, Caprioli e la costa verso Palinuro",
     "marina di pisciotta",
     "Il mare di Pisciotta: Marina di Pisciotta, le spiagge di Caprioli, la costa verso Palinuro e le escursioni da fare in vacanza.",
     ["pisciotta", "marina di pisciotta", "caprioli", "palinuro", "spiagge"]),
    ("vallo-della-lucania-cilento-interno-gelbison", "Ruggiero Park Hotel", "ruggiero-park-hotel-experiences-standard", "Vallo della Lucania", "Cilento interno",
     "Vallo della Lucania e il Cilento interno: il Gelbison, i borghi e il Parco a portata di mano",
     "vallo della lucania",
     "Vallo della Lucania, cuore del Parco del Cilento: il Monte Gelbison, Novi Velia, i borghi dell'interno e il mare a 20 minuti.",
     ["vallo della lucania", "cilento", "gelbison", "novi velia", "parco nazionale del cilento"]),
    ("palinuro-grotte-capo-in-barca", "Albergo Santa Caterina", "albergo-santa-caterina-experiences-standard", "Palinuro", "Palinuro",
     "Le grotte di Capo Palinuro: il giro in barca che vale da solo il viaggio",
     "grotte di palinuro",
     "Il giro in barca di Capo Palinuro: Grotta Azzurra, Grotta d'Argento, Arco Naturale e la spiaggia del Buon Dormire. Consigli pratici.",
     ["palinuro", "grotta azzurra", "gita in barca", "buon dormire", "cilento"]),
    ("palinuro-mito-molpa-san-severino", "Hotel La Torre", "hotel-la-torre-experiences-standard", "Palinuro", "Palinuro",
     "Palinuro oltre il mare: il mito di Virgilio, la Molpa e il borgo fantasma di San Severino",
     "palinuro cosa vedere",
     "Palinuro da terra: il mito del nocchiero di Enea, il faro, la Molpa, l'Antiquarium e il borgo abbandonato di San Severino di Centola.",
     ["palinuro", "virgilio", "molpa", "san severino di centola", "faro"]),
    ("marina-di-camerota-infreschi-cala-bianca", "Albergo Delfino", "albergo-delfino-experiences-standard", "Marina di Camerota", "Camerota",
     "Marina di Camerota, Infreschi e Cala Bianca: il mare più bello del Cilento",
     "marina di camerota cosa vedere",
     "Perché visitare Marina di Camerota: Cala Bianca, Porto Infreschi, l'Area Marina Protetta e le spiagge del paese.",
     ["marina di camerota", "infreschi", "cala bianca", "area marina protetta", "cilento"]),
    ("marina-di-camerota-calette-famiglie", "Cala d'Arconte Hotel Village", "cala-darconte-hotel-village-experiences-standard", "Marina di Camerota", "Camerota",
     "Le calette di Marina di Camerota: dove fare il bagno, dalla spiaggia del paese alle baie in barca",
     "spiagge marina di camerota",
     "Le spiagge di Marina di Camerota: Lentiscelle, Calanca, il Mingardo con l'Arco Naturale e le baie raggiungibili in barca.",
     ["marina di camerota", "spiagge", "lentiscelle", "calanca", "mingardo"]),
    ("marina-di-camerota-grotte-preistoria-snorkeling", "Hotel La Scogliera", "hotel-la-scogliera-experiences-standard", "Marina di Camerota", "Camerota",
     "Marina di Camerota sott'acqua e sottoterra: grotte preistoriche, snorkeling e immersioni",
     "grotte marina di camerota",
     "Marina di Camerota fra grotte preistoriche e fondali protetti: Grotta della Cala, snorkeling e immersioni negli Infreschi.",
     ["marina di camerota", "grotte", "preistoria", "snorkeling", "immersioni"]),
    ("camerota-borghi-collina-licusati-lentiscosa", "Hotel Riccio", "hotel-riccio-experiences-standard", "Marina di Camerota", "Camerota",
     "Camerota oltre la spiaggia: il borgo, Licusati, Lentiscosa e la cucina di collina",
     "camerota borgo",
     "Da Marina di Camerota ai borghi di collina: Camerota alta e il castello, Licusati, Lentiscosa e la tavola cilentana.",
     ["camerota", "licusati", "lentiscosa", "borghi", "cucina cilentana"]),
    ("sentiero-infreschi-trekking-marina-di-camerota", "Odissea Camping Village", "odissea-camping-village-experiences-standard", "Marina di Camerota", "Camerota",
     "Il sentiero degli Infreschi: trekking e natura da Marina di Camerota",
     "sentiero degli infreschi",
     "Il sentiero costiero da Marina di Camerota a Cala Bianca e Porto Infreschi: tempi, consigli e altre escursioni nel Parco.",
     ["marina di camerota", "sentiero degli infreschi", "trekking", "cala bianca", "natura"]),
    ("marina-di-camerota-con-bambini", "Villaggio La Barca", "villaggio-la-barca-experiences-standard", "Marina di Camerota", "Camerota",
     "Marina di Camerota con i bambini: spiagge comode, barche e piccole avventure",
     "marina di camerota bambini",
     "Marina di Camerota con i bambini: spiagge con servizi, gite in barca brevi, grotte e passeggiate adatte a tutta la famiglia.",
     ["marina di camerota", "bambini", "famiglie", "spiagge", "vacanze"]),
    ("marina-di-camerota-settimana-itinerario", "Villaggio Nessuno", "villaggio-nessuno-experiences-standard", "Marina di Camerota", "Camerota",
     "Una settimana a Marina di Camerota: itinerario giorno per giorno fra mare e Cilento",
     "marina di camerota itinerario",
     "Sette giorni a Marina di Camerota: Infreschi, Palinuro, Camerota alta, Morigerati e il Golfo di Policastro. Itinerario completo.",
     ["marina di camerota", "itinerario", "palinuro", "golfo di policastro", "cilento"]),
    ("scario-golfo-di-policastro-masseta", "Hotel Ristorante Cavaliere", "hotel-ristorante-cavaliere-experiences-standard", "Scario", "Golfo di Policastro",
     "Scario, il porto sul Golfo di Policastro: la costa della Masseta e San Giovanni a Piro",
     "scario cosa vedere",
     "Perché visitare Scario: il porto, la costa della Masseta e degli Infreschi in barca, San Giovanni a Piro e il Monte Bulgheria.",
     ["scario", "golfo di policastro", "masseta", "san giovanni a piro", "infreschi"]),
    ("sapri-spigolatrice-pisacane-storia", "Hotel Pisacane", "hotel-pisacane-experiences-standard", "Sapri", "Golfo di Policastro",
     "Sapri e la Spigolatrice: la storia di Pisacane e la città sul Golfo di Policastro",
     "sapri cosa vedere",
     "Perché visitare Sapri: la spedizione di Pisacane, la Spigolatrice di Mercantini, il lungomare e i dintorni del Golfo di Policastro.",
     ["sapri", "pisacane", "spigolatrice", "golfo di policastro", "storia"]),
    ("golfo-di-policastro-dintorni-maratea-morigerati", "Hotel Tirreno", "hotel-tirreno-experiences-standard", "Sapri", "Golfo di Policastro",
     "Da Sapri al Golfo di Policastro: Maratea, Morigerati e la Certosa di Padula",
     "golfo di policastro",
     "Sapri come base: Maratea e il Cristo Redentore, l'Oasi del Bussento a Morigerati, Policastro e la Certosa di Padula.",
     ["sapri", "golfo di policastro", "maratea", "morigerati", "certosa di padula"]),
]

FOOTER_RE = re.compile(
    r"<!-- destinazioni:footer -->.*?<!-- /destinazioni:footer -->", re.DOTALL
)


def dir_name(i, slug):
    return f"{i:02d}-{slug}"


def footer(entry, related):
    slug, struttura, demo, citta = entry[:4]
    s = html.escape(struttura, quote=False)
    c = html.escape(citta, quote=False)
    lines = ["<!-- destinazioni:footer -->"]
    if related:
        lines.append('  <h2>Leggi anche</h2>')
        lines.append("  <ul>")
        for r in related:
            lines.append(f'    <li><a href="/blog/{r[0]}/">{html.escape(r[5], quote=False)}</a></li>')
        lines.append("  </ul>")
        lines.append("")
    lines += [
        '  <div style="background:linear-gradient(135deg,#0e7490,#1e3a8a);border-radius:16px;padding:36px 32px;margin:44px 0;color:#ffffff;">',
        f'    <h3 style="color:#ffffff;margin-top:0;margin-bottom:12px;">Il tuo soggiorno a {c}: {s}</h3>',
        '    <p style="color:#e0f2fe;margin-bottom:26px;">Camere, servizi ed esperienze: scopri la struttura e chiedi la tua disponibilità direttamente, senza intermediari, con la tariffa migliore e i consigli di chi questo territorio lo vive tutto l\'anno.</p>',
        f'    <a href="{DEMO_BASE}/{demo}/" style="display:inline-block;background:#ffffff;color:#1e3a8a;padding:14px 28px;border-radius:8px;font-weight:700;text-decoration:none;margin-bottom:10px;">Scopri {s}</a>',
        "  </div>",
        "<!-- /destinazioni:footer -->",
    ]
    return "\n".join(lines)


def seo(i, entry, words):
    slug, struttura, demo, citta, area, title, kw, desc, tags = entry
    seo_title = title.split(":")[0].strip()
    if len(seo_title) > 60:
        seo_title = seo_title[:57].rstrip() + "…"
    image = f"{SITE}/wp-content/uploads/blog/{slug}.jpg"
    return {
        "slug": slug,
        "post_title": title,
        "seo_title": seo_title,
        "meta_description": desc,
        "focus_keyword": kw,
        "secondary_keywords": [t for t in tags if t != kw][:6],
        "og_title": title,
        "og_description": desc,
        "category": "Destinazioni",
        "tags": tags,
        "struttura": struttura,
        "citta": citta,
        "demo_url": f"{DEMO_BASE}/{demo}/",
        "word_count": words,
        "estimated_reading_time": max(3, round(words / 200)),
        "schema_article": {
            "@context": "https://schema.org",
            "@type": "Article",
            "headline": title,
            "description": desc,
            "image": image,
            "about": {"@type": "Place", "name": citta},
            "author": {"@type": "Organization", "name": "Experiences Srl", "url": SITE},
            "publisher": {"@type": "Organization", "name": "Experiences Srl", "url": SITE},
            "inLanguage": "it-IT",
            "mainEntityOfPage": f"{SITE}/blog/{slug}/",
        },
    }


def main():
    missing = []
    for i, entry in enumerate(ARTICLES, 1):
        d = OUT / dir_name(i, entry[0])
        art = d / "article.html"
        if not art.exists():
            missing.append(art.relative_to(ROOT))
            continue
        related = [e for e in ARTICLES if e[4] == entry[4] and e is not entry]
        text = art.read_text(encoding="utf-8")
        if not FOOTER_RE.search(text):
            sys.exit(f"{art}: marcatori destinazioni:footer mancanti")
        text = FOOTER_RE.sub(lambda _: footer(entry, related), text)
        art.write_text(text, encoding="utf-8")
        body = FOOTER_RE.sub("", text)
        words = len(re.sub(r"<[^>]+>", " ", body).split())
        (d / "seo.json").write_text(
            json.dumps(seo(i, entry, words), ensure_ascii=False, indent=2) + "\n",
            encoding="utf-8",
        )
    if missing:
        print("article.html mancanti:", *missing, sep="\n  ")
    print(f"{len(ARTICLES) - len(missing)}/{len(ARTICLES)} articoli aggiornati")


if __name__ == "__main__":
    main()
