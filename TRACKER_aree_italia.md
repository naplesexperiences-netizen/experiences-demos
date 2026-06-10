# Tracker Ricerca Clienti — Aree Turistiche Italia

Master tracker per la ricerca prospect (strutture ricettive + tour operator).
Per ogni AREA si produce un CSV dedicato + bozza Gmail.

**Protocollo di ripresa:** quando l'utente scrive "continua", riprendere dalla prima area con stato "Da fare" (ordine dall'alto).
**Metodo per area:** 5 subagent paralleli per enumerare → PageSpeed batch (stack/booking/mobile/immagini) → CSV schema unificato → bozza Gmail.
**Schema CSV:** Nome_Azienda,URL_Sito,Google_Business_Link,Categoria,Priorita,Email,Immagini,Link_Demo,Data_Generazione,Data_Contatto,Contattato,Citta,Sito_Pre2020,Mobile_Friendly,Channel_Manager,Blog,Criteri_Soddisfatti,Note,Brief_Demo_OpenClaw

## Divisione geografica dell'Italia per aree turistiche

| # | Area turistica | Sotto-zone principali | Stato | File CSV |
|---|---|---|---|---|
| 1 | Golfo di Napoli e Penisola Sorrentina | Napoli, Sorrento, Sant'Agnello, Vico Equense, area vesuviana (Pompei, Ercolano, Torre del Greco/Annunziata, Portici), Castellammare | ✅ FATTO | ricerca_clienti_napoli_salerno.csv |
| 2 | Isole del Golfo | Ischia, Capri, Procida | 🔄 IN CORSO (enumerazione subagent Sonnet) | — |
| 3 | Costiera Amalfitana | Positano, Praiano, Amalfi, Ravello, Maiori, Minori, Cetara, Vietri sul Mare | ✅ FATTO | _ricerca_intermedia/CSV_Costiera_Amalfitana.csv |
| 4 | Salerno e agro | Salerno città, Cava de' Tirreni | ✅ FATTO | _ricerca_intermedia/CSV_Salerno.csv |
| 5 | Cilento e Paestum | Agropoli, Castellabate, Palinuro, Marina di Camerota, Paestum/Capaccio, Vallo della Lucania, Sapri | ✅ FATTO | _ricerca_intermedia/CSV_Cilento.csv |
| 6 | Roma e Lazio | Roma, Castelli Romani, litorale, Tuscia, Ciociaria | ⏳ Da fare | — |
| 7 | Toscana | Firenze, Chianti, Siena/Val d'Orcia, Versilia, Maremma, Lucca, Elba | ⏳ Da fare | — |
| 8 | Cinque Terre e Liguria | Cinque Terre, Portofino/Tigullio, Riviera Ponente, Genova | ⏳ Da fare | — |
| 9 | Laghi del Nord | Como, Garda, Maggiore, Iseo | ⏳ Da fare | — |
| 10 | Venezia e Veneto | Venezia/laguna, Verona, Riviera del Brenta, litorale | ⏳ Da fare | — |
| 11 | Dolomiti e Alto Adige | Bolzano, Merano, Val Gardena, Cortina, Madonna di Campiglio | ⏳ Da fare | — |
| 12 | Riviera Romagnola ed Emilia | Rimini/Riccione, Bologna, Parma, Ravenna/Ferrara | ⏳ Da fare | — |
| 13 | Piemonte, Langhe e Valle d'Aosta | Langhe-Roero, Torino, Cervinia/Courmayeur | ⏳ Da fare | — |
| 14 | Friuli-Venezia Giulia | Trieste, Grado/Lignano, Collio | ⏳ Da fare | — |
| 15 | Umbria | Assisi/Perugia, Orvieto, Spoleto | ⏳ Da fare | — |
| 16 | Marche | Riviera del Conero, Urbino, Sibillini | ⏳ Da fare | — |
| 17 | Abruzzo e Molise | Costa dei Trabocchi, Gran Sasso/parchi, Termoli | ⏳ Da fare | — |
| 18 | Puglia | Salento, Valle d'Itria, Bari, Gargano | ⏳ Da fare | — |
| 19 | Basilicata | Matera, Maratea, Pollino | ⏳ Da fare | — |
| 20 | Calabria | Tropea/Costa degli Dei, Sila, Riviera dei Cedri, Ionica | ⏳ Da fare | — |
| 21 | Sicilia | Taormina/Etna, Palermo, Siracusa/Val di Noto, Agrigento, Trapani/Egadi, Eolie | ⏳ Da fare | — |
| 22 | Sardegna | Costa Smeralda/Gallura, Alghero, Cagliari/Sud, Ogliastra | ⏳ Da fare | — |

**Prossima ripresa:** completare aree 3-4-5 (Salerno/Costiera Amalfitana/Cilento) già avviate, poi area 2 (Isole del Golfo), poi proseguire verso nord (6+).
