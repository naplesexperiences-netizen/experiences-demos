#!/usr/bin/env python3
"""
Generatore email corrette per tutti i cicli
Ciclo 1: 11 aziende (Sirenide + 5 hotel + Golden Tours + 4 hotel)
Ciclo 2: 10 aziende
Ciclo 3 Batch 1: 5 aziende
"""

EMAILS = [
    # CICLO 1
    {
        "email": "info@sirenide.com",
        "name": "Sirenide Viaggi",
        "subject": "Demo personalizzata per Sirenide Viaggi – Experiences Srl",
        "intro": "Ho analizzato il vostro sito e la vostra operatività DMC nel segmento incoming B2B. Con 35+ anni di esperienza e una copertura completa del Sud Italia, avete un prodotto eccellente — ma il vostro sito attuale non rende giustizia a questo posizionamento.",
        "problems": [
            "PageSpeed mobile al 62% con stack datato (core-js): i clienti B2B internazionali si aspettano un'esperienza digitale all'altezza del vostro servizio",
            "Nessun chatbot AI per rispondere automaticamente alle richieste di preventivo fuori orario dalle agenzie estere",
            "Manca una sezione dedicata ai partner agenzie con area riservata online",
            "Le immagini dei servizi premium (hotel 4-5 stelle, yacht, guide multilingue) non emergono abbastanza nel funnel"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/sirenide-viaggi-demo-experiences/",
        "features": [
            "Chatbot AI attivo 24/7 per richieste preventivi da agenzie internazionali",
            "Sezione B2B dedicata con area partner riservata",
            "Vetrina servizi premium (hotel, guide multilingue, yacht)",
            "Motore di preventivi integrato",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "fb@exvitt.it",
        "name": "Grand Hotel Excelsior Vittoria",
        "subject": "Demo personalizzata per Excelsior Vittoria – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e la vostra storia straordinaria dal 1834. Essere un'icona internazionale con il ristorante Michelin ⭐ è un riconoscimento raro — ma il vostro sito attuale non sta sfruttando appieno questo posizionamento luxury per aumentare le prenotazioni dirette.",
        "problems": [
            "PageSpeed mobile al 50%: uno dei più bassi nel segmento Leading Hotels of the World, i visitatori abbandonano prima di prenotare",
            "Nessun chatbot per convertire i visitatori notturni in prenotazioni",
            "La storia dal 1834 e i nomi illustri (Wagner, Wilde, Caruso) meritano una narrazione digitale più coinvolgente e visiva",
            "Il ristorante Michelin e il parco agrumeto non hanno abbastanza spazio nel percorso di conversione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-excelsior-vittoria-demo-experiences/",
        "features": [
            "Storytelling visivo della storia dal 1834 ad oggi",
            "Chatbot AI con tono elegante per assistere gli ospiti 24/7",
            "Showcase del ristorante ⭐ Michelin e del parco agrumeto privato",
            "Gallery immersiva delle camere e del Golfo di Napoli",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@mediterraneosorrento.com",
        "name": "Hotel Mediterraneo Sorrento",
        "subject": "Demo personalizzata per Hotel Mediterraneo – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e il riconoscimento Michelin Key 2025. Con 61 camere a picco sul mare e l'ascensore privato alla spiaggia, avete creato un'esperienza unica — ma il sito non sta convertendo al massimo del vostro potenziale.",
        "problems": [
            "PageSpeed mobile al 28%: uno dei più bassi della Penisola Sorrentina, i visitatori abbandonano il sito in 3 secondi",
            "Nessun chatbot per rispondere alle richieste internazionali fuori orario",
            "L'ascensore privato al mare — il vostro differenziatore principale — non emerge abbastanza nel funnel",
            "Michelin Key 2025 merita molto più spazio nella home e nel percorso di prenotazione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/hotel-mediterraneo-sorrento-demo-experiences/",
        "features": [
            "Showcaseimmersivo dell'ascensore privato e della spiaggia privata",
            "Chatbot AI multilingue per convertire visitatori notturni in prenotazioni",
            "Michelin Key 2025 come argomento di vendita principale",
            "Gallery rooftop restaurant e vista mare",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "capodimonte@manniellohotels.com",
        "name": "Grand Hotel Capodimonte Sorrento",
        "subject": "Demo personalizzata per Grand Hotel Capodimonte – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e il vostro posizionamento nel gruppo Manniello. Con 5 piscine a cascata e 2-3 ristoranti, avete una grande capacità ricettiva — ma il sito non sta generando abbastanza prenotazioni dirette dal segmento gruppi e tour operator.",
        "problems": [
            "PageSpeed mobile al 96% è eccellente, ma manca un chatbot per convertire i tour operator internazionali che visitano di notte",
            "Nessun funnel dedicato ai gruppi e tour operator: mancano preventivi rapidi e proposte di pacchetti",
            "Le 5 piscine a cascata — il vostro differenziatore principale — non hanno una gallery dedicata",
            "D-Edge è integrato ma non sfruttato a pieno per l'automazione del revenue management"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-capodimonte-sorrento-demo-experiences/",
        "features": [
            "Sezione gruppi e tour operator con richiesta preventivo rapida",
            "Chatbot AI per conversioni automatiche fuori orario",
            "Gallery dedicata alle 5 piscine a cascata",
            "Showcase dei 2-3 ristoranti con menu e foto",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "royal@manniellohotels.com",
        "name": "Grand Hotel Royal Sorrento",
        "subject": "Demo personalizzata per Grand Hotel Royal – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e il vostro posizionamento luxury del gruppo Manniello. Con la spiaggia privata, la piscina infinity e i 3 ristoranti, siete una destinazione premium — ma il sito non sta sfruttando appieno il vostro valore per aumentare le prenotazioni dirette.",
        "problems": [
            "PageSpeed mobile al 89% è buono, ma manca un chatbot per convertire i visitatori notturni in prenotazioni",
            "La spiaggia privata e la piscina infinity meritano una narrazione visiva molto più coinvolgente",
            "Nessun funnel per l'up-selling di suite premium, esperienze private e cene gourmet",
            "Il positioning luxury non emerge abbastanza nel percorso di conversione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-royal-sorrento-demo-experiences/",
        "features": [
            "Chatbot AI 24/7 per conversioni automatiche",
            "Showcase immersivo della spiaggia privata e piscina infinity",
            "Sezione esperienze premium (cene gourmet, spa, yacht privato)",
            "Gallery dei 3 ristoranti con tono gourmet",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "incoming@goldentours.it",
        "name": "Golden Tours International",
        "subject": "Demo personalizzata per Golden Tours International – Experiences Srl",
        "intro": "Ho analizzato il vostro sito e la vostra operatività DMC nel segmento luxury con 65+ anni di esperienza. Con servizi premium da yacht a elicottero, avete un prodotto eccellente — ma il sito non rende giustizia a questo posizionamento.",
        "problems": [
            "PageSpeed mobile al 73% con stack datato: i clienti B2B luxury si aspettano un'esperienza digitale all'altezza",
            "Nessun chatbot per rispondere automaticamente alle richieste di preventivo B2B internazionali fuori orario",
            "Manca una sezione dedicata ai servizi premium (yacht, elicottero, Capri privata)",
            "Le immagini dei servizi luxury non emergono abbastanza nel funnel di conversione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/golden-tours-international-demo-experiences/",
        "features": [
            "Chatbot AI 24/7 per richieste preventivi B2B da agenzie internazionali",
            "Sezione B2B dedicata con showcase servizi premium",
            "Vetrina yacht, elicottero, esperienze Capri privata",
            "Motore di preventivi integrato",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@cocumella.com",
        "name": "Grand Hotel Cocumella",
        "subject": "Demo personalizzata per Grand Hotel Cocumella – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e la storia straordinaria dal 1637. Essere tra i Small Luxury Hotels of the World è un riconoscimento raro — ma il vostro sito attuale non trasmette appieno questa unicità storica e il potenziale della Cocumella.",
        "problems": [
            "PageSpeed mobile al 45%: uno dei punteggi più bassi nel segmento SLH, con stack jQuery UI + yepnope datato del 2010",
            "Nessun chatbot per rispondere alle richieste di prenotazione fuori orario",
            "Il veliero d'epoca per escursioni — un vantaggio competitivo unico — non è valorizzato nel funnel",
            "La storia secolare (ex collegio gesuitico 1637) merita una narrazione digitale molto più coinvolgente"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-cocumella-demo-experiences/",
        "features": [
            "Storytelling visivo della storia dal 1637 ad oggi",
            "Chatbot AI con tono elegante e storico 24/7",
            "Sezione dedicata al veliero d'epoca e alle escursioni in mare",
            "Gallery immersiva del parco e dell'agrumeto",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@hotelcesareaugusto.com",
        "name": "Grand Hotel Cesare Augusto",
        "subject": "Demo personalizzata per Grand Hotel Cesare Augusto – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e la sua posizione strategica nel centro di Sorrento. Con 120 camere e il roof garden con piscina, siete la struttura di riferimento per gruppi e tour operator — ma il sito non sta sfruttando appieno questo vantaggio.",
        "problems": [
            "La grande capacità per gruppi (120 camere) non è promossa in modo efficace nel percorso di prenotazione online",
            "Nessun chatbot per gestire le richieste di preventivo gruppi fuori orario dall'estero",
            "Il roof garden con piscina merita molto più spazio nella home e nella sezione gruppi",
            "Manca una sezione B2B dedicata ai tour operator internazionali"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-cesare-augusto-demo-experiences/",
        "features": [
            "Sezione gruppi e tour operator con richiesta preventivo rapida",
            "Chatbot AI per conversioni automatiche B2B fuori orario",
            "Showcase del roof garden con piscina e vista Sorrento",
            "Integrazione con il sistema Blastness esistente",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@hotellafavorita.com",
        "name": "Grand Hotel La Favorita",
        "subject": "Demo personalizzata per Grand Hotel La Favorita – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e la sua proposta nel cuore di Sorrento. Con 85 camere, un elegante roof garden e una vocazione consolidata per matrimoni ed eventi, avete un prodotto di grande valore — ma il sito non sta generando tutto il potenziale.",
        "problems": [
            "PageSpeed mobile al 62%: i potenziali sposi si aspettano un'esperienza visiva d'impatto",
            "Il segmento matrimoni ed eventi richiede un funnel dedicato con gallerie, listini e richiesta preventivo rapida",
            "Nessun chatbot per rispondere alle richieste di informazioni matrimoni fuori orario",
            "Il roof garden con piscina — uno degli spazi più suggestivi per cerimonie — ha poca visibilità nel funnel"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-la-favorita-demo-experiences/",
        "features": [
            "Sezione matrimoni ed eventi con galleria fotografica e modulo preventivo",
            "Chatbot AI per rispondere automaticamente alle coppie in cerca di location",
            "Showcase del roof garden e della spa con immagini coinvolgenti",
            "Integrazione con il sistema di booking esistente",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@continentalsorrento.com",
        "name": "Hotel Continental Sorrento",
        "subject": "Demo personalizzata per Hotel Continental – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e la sua posizione privilegiata nel centro storico di Sorrento, a pochi passi dal porto e dal Chiostro di San Francesco. Una location straordinaria — ma il sito non sta sfruttando appieno questo vantaggio.",
        "problems": [
            "PageSpeed mobile al 67% con stack datato: i visitatori abbandonano siti lenti prima di vedere le camere",
            "La terrazza panoramica con vista sul Golfo non emerge abbastanza nel funnel di conversione",
            "Nessun chatbot per gestire le richieste di prenotazione fuori orario",
            "La vicinanza al porto e ai siti culturali è un argomento di vendita potente che va comunicato meglio"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/hotel-continental-sorrento-demo-experiences/",
        "features": [
            "Mappa interattiva con distanze dai principali punti di interesse",
            "Chatbot AI per conversioni automatiche fuori orario",
            "Showcase della terrazza panoramica con vista Golfo di Napoli",
            "Sezione esperienze con le attività raggiungibili a piedi",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    # CICLO 2
    {
        "email": "info@hotelantichemura.com",
        "name": "Hotel Antiche Mura Sorrento",
        "subject": "Demo personalizzata per Hotel Antiche Mura – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel nel cuore di Sorrento a Piazza Tasso. Con la posizione centrale e la piscina nel vallone dei mulini, avete un prodotto affascinante — ma il sito non sta convertendo al massimo del potenziale.",
        "problems": [
            "PageSpeed mobile al 76% con stack datato (Bootstrap + jQuery + yepnope)",
            "Nessun chatbot per convertire i visitatori notturni in prenotazioni",
            "La piscina nel vallone dei mulini — una rarità nella zona — non ha una gallery dedicata",
            "L'agrumeto privato merita molto più spazio nel percorso di conversione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/hotel-antiche-mura-sorrento-demo-experiences/",
        "features": [
            "Chatbot AI 24/7 per conversioni automatiche",
            "Gallery dedicata alla piscina nel vallone dei mulini",
            "Showcase dell'agrumeto privato",
            "Mappa interattiva della posizione a Piazza Tasso",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@ghlapace.com",
        "name": "Grand Hotel La Pace",
        "subject": "Demo personalizzata per Grand Hotel La Pace – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel luxury a Sant'Agnello. Con le spa e le piscine, siete una destinazione premium — ma il sito non sta sfruttando appieno il vostro valore per aumentare le prenotazioni dirette.",
        "problems": [
            "Nessun chatbot per convertire i visitatori notturni in prenotazioni",
            "La spa e le piscine meritano una narrazione visiva molto più coinvolgente",
            "Nessun funnel per l'up-selling di esperienze premium e servizi esclusivi",
            "Il positioning luxury non emerge abbastanza nel percorso di conversione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-la-pace-demo-experiences/",
        "features": [
            "Chatbot AI 24/7 per conversioni automatiche",
            "Showcase immersivo della spa e delle piscine",
            "Sezione esperienze premium con tono luxury",
            "Gallery delle camere con focus vista mare",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@bleuvillage.com",
        "name": "Bleu Village Resort & Residence",
        "subject": "Demo personalizzata per Bleu Village – Experiences Srl",
        "intro": "Ho analizzato il vostro resort a Meta di Sorrento. Con i bungalow e l'atmosfera village, avete una proposta unica — ma il sito non sta generando abbastanza prenotazioni dirette.",
        "problems": [
            "Nessun chatbot per convertire i visitatori notturni in prenotazioni",
            "I bungalow meritano una gallery fotografica molto più coinvolgente",
            "Nessun funnel per i soggiorni lunghi o settimanali che caratterizzano un village",
            "Le piscine e le aree comuni non hanno abbastanza spazio nel percorso di conversione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/bleu-village-resort-residence-demo-experiences/",
        "features": [
            "Chatbot AI 24/7 per conversioni automatiche",
            "Gallery immersiva dei bungalow e delle aree comuni",
            "Sezione soggiorni lunghi con tariffe per week/month",
            "Showcase delle piscine e dei servizi village",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@grandhotelparkers.it",
        "name": "Grand Hotel Parker's",
        "subject": "Demo personalizzata per Grand Hotel Parker's – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel luxury a Napoli. Dal 1870 e membro dei Relais & Châteaux, siete un'icona della città — ma il sito non sta sfruttando appieno questo posizionamento per aumentare le prenotazioni dirette.",
        "problems": [
            "PageSpeed mobile al 31%: uno dei più bassi tra i Relais & Châteaux, i visitatori abbandonano subito",
            "Nessun chatbot per convertire i visitatori notturni in prenotazioni",
            "La storia dal 1870 e il riconoscimento Relais & Châteaux meritano una narrazione molto più coinvolgente",
            "Il positioning luxury non emerge abbastanza nel percorso di conversione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-parkers-napoli-demo-experiences/",
        "features": [
            "Sito ultra-veloce ottimizzato per Relais & Châteaux",
            "Chatbot AI 24/7 per conversioni automatiche",
            "Storytelling visivo della storia dal 1870 ad oggi",
            "Gallery immersiva con tono luxury",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@royalcontinental.it",
        "name": "Hotel Royal Continental",
        "subject": "Demo personalizzata per Hotel Royal Continental – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel a Napoli lungomare. Con 397 camere e il posizionamento nel gruppo Royal, siete una struttura di riferimento — ma il sito non sta sfruttando appieno la vostra capacità per generare prenotazioni dirette.",
        "problems": [
            "PageSpeed mobile al 42%: la velocità di caricamento riduce le conversioni su mobile",
            "La grande capacità (397 camere) non è promossa in modo efficace per i gruppi",
            "Nessun chatbot per conversioni automatiche fuori orario",
            "La posizione lungomare a Napoli merita una narrazione visiva molto più coinvolgente"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/hotel-royal-continental-napoli-demo-experiences/",
        "features": [
            "Sito veloce ottimizzato per mobile",
            "Chatbot AI 24/7 per conversioni automatiche",
            "Sezione gruppi con preventivi rapidi",
            "Gallery lungomare con vista mare",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@torrestravel.it",
        "name": "Torres Travel",
        "subject": "Demo personalizzata per Torres Travel – Experiences Srl",
        "intro": "Ho analizzato il vostro sito e la vostra specializzazione come guide center a Pompei. Con escursioni per crocieristi e una copertura del Golfo di Napoli, avete un modello di business consolidato — ma il sito non sta sfruttando appieno il potenziale online.",
        "problems": [
            "PageSpeed mobile al 54%: i crocieristi cercano velocemente, abbandonano siti lenti",
            "Nessun chatbot per rispondere alle richieste urgenti dai tour operator durante le crociere",
            "Nessun booking engine: ogni prenotazione passa per email o telefono, perdendo impulso",
            "Le 11+ lingue parlate meritano molto più spazio nel sito per attirare crocieristi internazionali"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/torres-travel-pompei-demo-experiences/",
        "features": [
            "Sito veloce per crocieristi che cercano rapidamente",
            "Chatbot AI multilingue 11+ lingue per prenotazioni istantanee",
            "Booking engine online dedicato ai crocieristi",
            "Skip-the-line Pompei come featured experience",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@aminta.it",
        "name": "Grand Hotel Aminta",
        "subject": "Demo personalizzata per Grand Hotel Aminta – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel a Sorrento collina. Con la vista panoramica sul Golfo e le piscine, siete una scelta ideale per famiglie e coppie — ma il sito non sta convertendo al massimo del potenziale.",
        "problems": [
            "PageSpeed mobile al 56% con stack Bootstrap + jQuery datato",
            "Nessun chatbot per convertire i visitatori notturni in prenotazioni",
            "La vista panoramica merita una gallery fotografica molto più coinvolgente",
            "Le piscine e le aree comuni non hanno abbastanza spazio nel funnel"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-aminta-sorrento-demo-experiences/",
        "features": [
            "Sito veloce con gallery coinvolgente della vista panoramica",
            "Chatbot AI 24/7 per conversioni automatiche",
            "Showcase delle piscine con focus famiglie",
            "Sezione esperienze con attività nella Penisola Sorrentina",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@loreleisorrento.com",
        "name": "Hotel Lorelei et Londres",
        "subject": "Demo personalizzata per Hotel Lorelei et Londres – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e il vostro ristorante 1 stella Michelin. Con la ristrutturazione 2019 e la posizione fronte mare, siete una destinazione gastronomica di riferimento — ma il sito non sta sfruttando appieno questo posizionamento.",
        "problems": [
            "PageSpeed mobile al 63%: i foodies e gli ospiti gastronomici si aspettano un'esperienza visiva d'impatto",
            "Il ristorante ⭐ Michelin merita molto più spazio e una gallery gastronomica sofisticata",
            "Nessun chatbot per conversioni automatiche fuori orario",
            "L'esperienza culinaria non emerge abbastanza nel percorso di prenotazione"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/hotel-lorelei-et-londres-demo-experiences/",
        "features": [
            "Gallery gastronomica del ristorante ⭐ Michelin",
            "Chatbot AI 24/7 per conversioni automatiche",
            "Showcase della vista golfo e della terrazza",
            "Sezione chef e menu speciali",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "ambasciatori@manniellohotels.com",
        "name": "Grand Hotel Ambasciatori",
        "subject": "Demo personalizzata per Grand Hotel Ambasciatori – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e il vostro beach club fronte mare. Con la posizione a picco sul scogliera e la vista Vesuvio, siete una destinazione panoramica di riferimento — ma il sito non sta sfruttando appieno questo valore.",
        "problems": [
            "PageSpeed mobile al 72% ma nessun chatbot per conversioni automatiche fuori orario",
            "Il beach club e la terrazza a picco sul golfo meritano una gallery fotografica molto più coinvolgente",
            "La vista Vesuvio è un differenziatore unico che non viene valorizzato abbastanza",
            "Nessun funnel per l'up-selling di esperienze beach club e cene panoramiche"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/grand-hotel-ambasciatori-demo-experiences/",
        "features": [
            "Chatbot AI 24/7 per conversioni automatiche",
            "Gallery immersiva del beach club e della vista Vesuvio",
            "Showcase della terrazza a picco sul golfo",
            "Sezione esperienze con focus sunset e cene panoramiche",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@lasolara.com",
        "name": "Best Western Hotel La Solara",
        "subject": "Demo personalizzata per Best Western Hotel La Solara – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel e il vostro posizionamento nella catena Best Western. Con la piscina e la navetta centro, siete una scelta pratica e confortevole — ma il sito non sta sfruttando appieno il vostro valore per aumentare le prenotazioni dirette.",
        "problems": [
            "PageSpeed mobile al 37%: uno dei più bassi di Sorrento, i visitatori abbandonano subito",
            "Nessun chatbot per convertire i visitatori notturni in prenotazioni",
            "La piscina e il servizio navetta meritano più spazio nel funnel",
            "Il posizionamento Best Western merita una comunicazione più moderna e digitale"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/best-western-hotel-la-solara-demo-experiences/",
        "features": [
            "Sito ultra-veloce ottimizzato per mobile",
            "Chatbot AI 24/7 per conversioni automatiche",
            "Gallery della piscina e della navetta",
            "Sezione servizi con focus convenienza",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    # CICLO 3 BATCH 1
    {
        "email": "reservations@hotelcrawford.com",
        "name": "Hotel Crawford",
        "subject": "Demo personalizzata per Hotel Crawford Sant'Agnello – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel rinnovato sul Corso Crawford a Sant'Agnello. Con la ristrutturazione del 2016 e la terrazza panoramica sul Golfo di Napoli, avete creato un prodotto di qualità — ma il sito non sta convertendo al massimo del potenziale.",
        "problems": [
            "PageSpeed mobile al 53%: uno dei peggiori nel segmento 4 stelle della zona",
            "Nessun chatbot per rispondere alle richieste fuori orario",
            "La terrazza panoramica non emerge abbastanza nel funnel di conversione",
            "Zero booking engine: ogni prenotazione passa attraverso OTA con commissioni al 15-20%"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/hotel-crawford-santagnello-demo-experiences/",
        "features": [
            "Sito ultra-veloce ottimizzato per dispositivi mobili",
            "Chatbot AI multilingue 24/7",
            "Showcase della terrazza panoramica con vista sul Golfo",
            "Booking engine diretto — zero commissioni OTA",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@vestours.com",
        "name": "Vestours",
        "subject": "Demo personalizzata per Vestours Sant'Agnello – Experiences Srl",
        "intro": "Ho analizzato il vostro sito e la vostra operatività come tour operator su Costiera Amalfitana, Capri, Sicilia e Puglia. Avete un prodotto diversificato e di valore — ma il sito attuale non sta convertendo al massimo del potenziale.",
        "problems": [
            "Nessun sistema di prenotazione online: ogni cliente contatta manualmente, perdendo impulso",
            "PageSpeed mobile al 60% con stack Bootstrap + jQuery datato",
            "Nessun chatbot per risposte in inglese, tedesco e francese fuori orario",
            "La copertura multi-destinazione non viene valorizzata"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/vestours-santagnello-demo-experiences/",
        "features": [
            "Booking engine online per prenotazioni immediate",
            "Chatbot AI 4 lingue per preventivi automatici",
            "Pagine SEO per Costiera, Capri, Sicilia e Puglia",
            "Gallery coinvolgente per ogni destinazione",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@karsanatravel.com",
        "name": "Karsana Travel",
        "subject": "Demo personalizzata per Karsana Travel Sant'Agnello – Experiences Srl",
        "intro": "Ho analizzato il vostro sito e i vostri 28 anni di esperienza nel turismo incoming. Con quasi tre decenni di attività, avete costruito una reputazione solida — ma il sito attuale non riflette questa autorevolezza e sta perdendo prenotazioni.",
        "problems": [
            "PageSpeed mobile al 39%: uno dei più bassi della Penisola Sorrentina",
            "Joomla con stack datato: difficile da aggiornare e ottimizzare",
            "Nessun booking engine: 28 anni meritano un canale diretto moderno",
            "Nessun chatbot multilingue fuori orario"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/karsana-travel-santagnello-demo-experiences/",
        "features": [
            "Sito moderno ultra-veloce che valorizza i 28 anni",
            "Chatbot AI multilingue IT/EN/FR per preventivi",
            "Booking engine online integrato",
            "SEO ottimizzato per ricerche internazionali",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@giosueamare.it",
        "name": "Hotel Giosue a Mare",
        "subject": "Demo personalizzata per Hotel Giosue a Mare – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel fronte mare a Meta di Sorrento. Con un PageSpeed mobile al 94% avete uno dei siti più veloci della zona — ma la velocità tecnica da sola non basta a massimizzare le prenotazioni dirette.",
        "problems": [
            "Nonostante l'ottima performance, manca un chatbot per convertire visitatori notturni",
            "La Suite 1951 merita una narrazione visiva più coinvolgente",
            "Nessun CRM per gestire i clienti ricorrenti",
            "Nessun sistema di up-selling integrato (upgrade suite, ristorante, escursioni)"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/hotel-giosue-a-mare-meta-sorrento-demo-experiences/",
        "features": [
            "Chatbot AI per conversioni automatiche anche di notte",
            "Showcase immersivo della Suite 1951 e camere fronte mare",
            "Sistema di up-selling integrato",
            "CRM per fidelizzare clienti ricorrenti",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
    {
        "email": "info@hotelpanoramapalace.it",
        "name": "Panorama Palace Hotel",
        "subject": "Demo personalizzata per Panorama Palace Hotel – Experiences Srl",
        "intro": "Ho analizzato il vostro hotel in Piazza Scarpati a Meta di Sorrento. Con il museo archeologico interno, la piscina panoramica e la vista sul Golfo, avete una proposta unica — ma il sito non comunica questa unicità in modo efficace.",
        "problems": [
            "PageSpeed mobile al 62%: la velocità riduce le conversioni su mobile",
            "Il museo archeologico — una rarità — non viene valorizzato come differenziatore",
            "Nessun chatbot per richieste internazionali fuori orario",
            "La vista panoramica 360° merita una presentazione visiva molto più coinvolgente"
        ],
        "demo_url": "https://naplesexperiences-netizen.github.io/experiences-demos/demos/panorama-palace-hotel-meta-sorrento-demo-experiences/",
        "features": [
            "Sezione dedicata al museo archeologico come differenziatore unico",
            "Gallery immersiva della vista panoramica 360°",
            "Chatbot AI per turisti internazionali 24/7",
            "SEO per 'hotel museo Meta Sorrento' e 'panorama golfo Napoli'",
            "Prezzi trasparenti: da €500/anno + commissione sulle vendite"
        ]
    },
]


def generate_email_body(data):
    """Genera il corpo dell'email con spazi corretti e footer pulito"""

    features = "\n".join([f"- {f}" for f in data["features"]])
    problems = "\n".join([f"- {data[p]}" for p in ["problems"]])

    # Ricostruisci correttamente i problemi
    problems_text = "\n".join([f"- {p}" for p in data["problems"]])

    body = f"""Gentile Team {data["name"]},

{data["intro"]}

I problemi che ho rilevato:
{problems_text}

Ho preparato una demo del nuovo sito che {data["name"]} potrebbe avere con Experiences:

{data["demo_url"]}

Nella demo troverete:
{features}

Sono disponibile per una chiamata conoscitiva.

Cordiali saluti,
Mario Esposito
<a href="https://naplesexperiences.com">Experiences Srl</a> | Soluzioni Digitali per il Turismo
WhatsApp: +39 392 691 7657
naplesexperiences@gmail.com
https://naplesexperiences.com

---

<span style="color: #999; font-size: 12px;">Non desideri ricevere più email da parte di Experiences? <a href="mailto:naplesexperiences@gmail.com?subject=Richiesta%20rimozione%20dalla%20mailing%20list&body=Desidero%20essere%20rimosso%20dalla%20vostra%20mailing%20list.">Clicca qui per cancellarti dalla nostra mailing list</a>.</span>"""

    return body


if __name__ == "__main__":
    for data in EMAILS:
        body = generate_email_body(data)
        print(f"\n{'='*70}")
        print(f"TO: {data['email']}")
        print(f"SUBJECT: {data['subject']}")
        print(f"{'='*70}")
        print(body[:300] + "...")
        print(f"\n✓ Email pronta per: {data['name']}\n")

    print(f"\n{'='*70}")
    print(f"TOTALE EMAIL: {len(EMAILS)} (11 Ciclo 1 + 10 Ciclo 2 + 5 Ciclo 3)")
    print(f"{'='*70}")
