#!/usr/bin/env python3
"""
Generatore Demo v3 - Demo personalizzate con immagini reali e info dettagliate
"""

import csv
import json
from datetime import datetime
from pathlib import Path

ASSETS_BASE = "https://naplesexperiences-netizen.github.io/experiences-demos/demos/assets/campania-images"

CAMPANIA_IMAGES = {
    "vesuvio":  f"{ASSETS_BASE}/joakant-vesuvius-677714_1920.jpg",
    "capri1":   f"{ASSETS_BASE}/ebell8810-capri-854775_1920.jpg",
    "napoli1":  f"{ASSETS_BASE}/jorjoson-napoli-5337054_1920.jpg",
    "pizza1":   f"{ASSETS_BASE}/martinquijandria-pizza-2530169_1920.jpg",
    "capri2":   f"{ASSETS_BASE}/nitell-capri-4895720_1920.jpg",
    "napoli2":  f"{ASSETS_BASE}/ornaw-naples-4808406_1920.jpg",
    "pizza2":   f"{ASSETS_BASE}/petrovhey-pizza-3000274_1920.jpg",
    "napoli3":  f"{ASSETS_BASE}/serpae-napoli-5711208_1920.jpg",
    "italy":    f"{ASSETS_BASE}/yorick77-italy-6702554_1920.jpg",
    "vesuvio2": f"{ASSETS_BASE}/yorick77-vesuvius-4635882_1920.jpg",
}

COMPANIES = [
    {
        "slug": "sirenide-viaggi",
        "name": "Sirenide Viaggi",
        "email": "info@sirenide.com",
        "category": "Tour operator incoming / DMC",
        "city": "Sorrento",
        "website": "https://www.sirenide.com",
        "hero_img": "https://www.sirenide.com/images/scaled/000_positano.webp",
        "tagline": "Il vostro partner DMC di fiducia nel Sud Italia dal 1988",
        "description": "Sirenide Viaggi è un DMC (Destination Management Company) con oltre 35 anni di esperienza nell'incoming B2B verso il Sud Italia. Lavoriamo con tour operator e agenzie di viaggio in tutto il mondo per offrire esperienze su misura lungo la Penisola Sorrentina, Costiera Amalfitana, Capri, Ischia, Napoli e Roma.",
        "palette": {"primary": "#1e3a5f", "secondary": "#0e9aa7", "accent": "#c9a84c", "light": "#f0f7ff", "dark": "#0d2035"},
        "stats": [
            {"num": "35+", "label": "Anni di Esperienza"},
            {"num": "5", "label": "Lingue Parlate"},
            {"num": "200+", "label": "Agenzie Partner"},
            {"num": "15.000+", "label": "Ospiti Annuali"},
        ],
        "services": [
            {"title": "Tour Guidati Costiera", "desc": "Escursioni personalizzate lungo la Costiera Amalfitana con guide certificate in 5 lingue", "img": CAMPANIA_IMAGES["capri1"]},
            {"title": "Hotel & Accommodation", "desc": "Selezione esclusiva di hotel 3-5 stelle con tariffe riservate e allocazioni garantite", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Transfer & Trasporti", "desc": "Bus di lusso, minivan e transfer privati dall'aeroporto e dai porti turistici", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Escursioni a Capri", "desc": "Boat tour, grotta azzurra e tour dell'isola con barche private e guide esperte", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Food Experience", "desc": "Cooking class, degustazioni e cene gourmet nei migliori ristoranti del territorio", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Vesuvio & Pompei", "desc": "Visite guidate agli scavi di Pompei ed escursioni al Vesuvio con trasporto incluso", "img": CAMPANIA_IMAGES["vesuvio"]},
        ],
        "highlights": ["Copertura completa Sud Italia", "Fleet bus lusso Euro 6", "Guida in 5 lingue (IT/EN/DE/FR/ES)", "Area agenzie riservata online", "Rete di hotel propri a Sorrento"],
        "cta": "Richiedi un Preventivo",
        "chatbot_msg": "Ciao! Sono l'assistente AI di Sirenide Viaggi. Come posso aiutarti? Posso fornirti informazioni su itinerari, disponibilità hotel, transfer e molto altro.",
    },
    {
        "slug": "grand-hotel-excelsior-vittoria",
        "name": "Grand Hotel Excelsior Vittoria",
        "email": "fb@exvitt.it",
        "category": "Hotel 5 stelle Luxury (LHW)",
        "city": "Sorrento",
        "website": "https://excelsiorvittoria.com",
        "hero_img": "https://excelsiorvittoria.com/images-temp-/off-suit-rt-1.jpg",
        "tagline": "Dal 1834: un'icona di lusso sospesa tra storia e Golfo di Napoli",
        "description": "Il Grand Hotel Excelsior Vittoria è una dimora storica affacciata sul Golfo di Napoli a Piazza Tasso, Sorrento. Dal 1834 ospita personalità illustri come Richard Wagner, Oscar Wilde e Enrico Caruso. Membro dei Leading Hotels of the World, offre 79 camere con vista mare, il ristorante stellato Michelin Terrazza Bosquet e un parco-agrumeto privato.",
        "palette": {"primary": "#5c4a2a", "secondary": "#2d5a27", "accent": "#c9a84c", "light": "#fdf8f0", "dark": "#3a2e18"},
        "stats": [
            {"num": "1834", "label": "Anno di Fondazione"},
            {"num": "79", "label": "Camere Vista Mare"},
            {"num": "1★", "label": "Michelin Terrazza Bosquet"},
            {"num": "LHW", "label": "Leading Hotels of the World"},
        ],
        "services": [
            {"title": "Suite Panoramiche", "desc": "79 camere e suite con vista mozzafiato sul Golfo di Napoli, arredate con antichi mobili d'epoca", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Terrazza Bosquet ⭐", "desc": "Ristorante stellato Michelin con cucina mediterranea d'autore e panorama sul golfo", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Parco & Agrumeto", "desc": "Lussureggiante parco privato con alberi di limoni, aranci e bouganville centenari", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Spa & Benessere", "desc": "Trattamenti esclusivi con prodotti agli agrumi della Penisola Sorrentina", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Escursioni Luxury", "desc": "Capri, Pompei, Costiera Amalfitana con yacht privato e transfer esclusivi", "img": CAMPANIA_IMAGES["capri1"]},
            {"title": "Ascensore al Mare", "desc": "Accesso diretto al porto di Sorrento con ascensore privato storico", "img": CAMPANIA_IMAGES["napoli1"]},
        ],
        "highlights": ["Dimora storica dal 1834", "Leading Hotels of the World", "Ristorante ⭐ Michelin", "Parco agrumeto privato", "Wagner, Wilde, Caruso soggiornarono qui"],
        "cta": "Prenota il Tuo Soggiorno",
        "chatbot_msg": "Benvenuto all'Excelsior Vittoria. Sono il vostro concierge digitale. Posso assistervi per prenotazioni, disponibilità suite e organizzazione di esperienze esclusive.",
    },
    {
        "slug": "hotel-mediterraneo-sorrento",
        "name": "Hotel Mediterraneo Sorrento",
        "email": "info@mediterraneosorrento.com",
        "category": "Hotel 5 stelle",
        "city": "Sant'Agnello",
        "website": "https://www.mediterraneosorrento.com",
        "hero_img": "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1600&q=80",
        "tagline": "Michelin Key 2025 · A picco sul mare con ascensore privato alla spiaggia",
        "description": "L'Hotel Mediterraneo Sorrento è un raffinato 5 stelle a Sant'Agnello, premiato con la Michelin Key 2025. Con 61 camere a picco sul mare e un ascensore privato che scende direttamente alla spiaggia, offre un'esperienza unica tra lusso contemporaneo e natura mediterranea.",
        "palette": {"primary": "#1a4a6e", "secondary": "#c9aa71", "accent": "#c9aa71", "light": "#f0f6fc", "dark": "#0d2a42"},
        "stats": [
            {"num": "🔑", "label": "Michelin Key 2025"},
            {"num": "61", "label": "Camere Vista Mare"},
            {"num": "5★", "label": "Stelle"},
            {"num": "28", "label": "m sul livello del mare"},
        ],
        "services": [
            {"title": "Camere & Suite", "desc": "61 camere eleganti con balcone e vista sul Golfo di Napoli, design contemporaneo", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Piscina Infinity", "desc": "Piscina a sfioro sospesa sul mare con solarium e vista panoramica sul golfo", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Ascensore al Mare", "desc": "Accesso esclusivo alla spiaggia privata tramite ascensore panoramico scavato nella roccia", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Rooftop Restaurant", "desc": "Ristorante in terrazza con cucina mediterranea stellata e vista sul tramonto", "img": CAMPANIA_IMAGES["pizza2"]},
            {"title": "Spa & Relax", "desc": "Centro benessere con trattamenti viso e corpo ai prodotti naturali della Costiera", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Esperienze Locali", "desc": "Escursioni a Capri, Positano, Pompei con partner selezionati e guida privata", "img": CAMPANIA_IMAGES["capri1"]},
        ],
        "highlights": ["Michelin Key 2025", "Ascensore privato alla spiaggia", "Piscina infinity sul mare", "61 camere vista golfo", "Rooftop restaurant"],
        "cta": "Prenota la Tua Vista Mare",
        "chatbot_msg": "Ciao! Sono l'assistente del Mediterraneo Sorrento. Posso aiutarti con disponibilità, informazioni sulle camere e organizzazione del tuo soggiorno perfetto.",
    },
    {
        "slug": "grand-hotel-capodimonte-sorrento",
        "name": "Grand Hotel Capodimonte",
        "email": "capodimonte@manniellohotels.com",
        "category": "Hotel 4 stelle superior",
        "city": "Sorrento",
        "website": "https://www.capodimontesorrento.com",
        "hero_img": "https://www.capodimontesorrento.com/wp-content/uploads/sites/346/2024/05/Piscina-con-vistaSito-450x900.jpg",
        "tagline": "5 piscine a cascata con vista golfo · Resort mediterraneo classico nel cuore di Sorrento",
        "description": "Il Grand Hotel Capodimonte è un resort 4 stelle superior del Gruppo Manniello, celebre per le sue 5 piscine a cascata affacciate sul Golfo di Napoli. Situato in Via Capodimonte 16 con accesso scavato nella roccia, offre 2 ristoranti (Le Ginestre e Le Querce), giardini mediterranei e un beach club esclusivo.",
        "palette": {"primary": "#1b3d6e", "secondary": "#c46a1f", "accent": "#8fb96e", "light": "#f5f0eb", "dark": "#0f2440"},
        "stats": [
            {"num": "5", "label": "Piscine a Cascata"},
            {"num": "2", "label": "Ristoranti"},
            {"num": "4★S", "label": "Stelle Superior"},
            {"num": "Manniello", "label": "Gruppo Alberghiero"},
        ],
        "services": [
            {"title": "5 Piscine a Cascata", "desc": "Straordinario complesso di 5 piscine panoramiche a cascata con vista sul Golfo di Napoli", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Le Ginestre & Le Querce", "desc": "Due ristoranti con cucina mediterranea e specialità sorrentine in ambienti eleganti", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Giardini Mediterranei", "desc": "Parco botanico con ulivi, agrumi e bougainville che scende fino al mare", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Beach Club", "desc": "Club spiaggia esclusivo con accesso tramite ascensore e scogliera privata", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Spa & Jacuzzi", "desc": "Centro benessere con jacuzzi panoramica, sauna e trattamenti rigeneranti", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Escursioni Costiera", "desc": "Tour a Positano, Amalfi, Ravello e gite in barca a Capri con partner selezionati", "img": CAMPANIA_IMAGES["capri1"]},
        ],
        "highlights": ["5 piscine a cascata vista golfo", "Accesso scavato nella roccia", "Gruppo Manniello dal 1960", "Ristoranti Le Ginestre & Le Querce", "Beach Club esclusivo"],
        "cta": "Prenota la Tua Vista sul Golfo",
        "chatbot_msg": "Benvenuti al Grand Hotel Capodimonte! Sono il vostro assistente virtuale. Vi posso aiutare con prenotazioni, informazioni sulle piscine e sui ristoranti.",
    },
    {
        "slug": "grand-hotel-royal-sorrento",
        "name": "Grand Hotel Royal Sorrento",
        "email": "royal@manniellohotels.com",
        "category": "Hotel 5 stelle",
        "city": "Sorrento",
        "website": "https://www.royalsorrento.com",
        "hero_img": "https://www.royalsorrento.com/wp-content/uploads/sites/343/2023/03/Terrazza-Home-Page-450x900.jpeg",
        "tagline": "Lusso fronte mare in Via Correale · Spiaggia privata · 3 ristoranti · Infinity pool",
        "description": "Il Grand Hotel Royal è un 5 stelle del Gruppo Manniello situato in Via Correale 42, a picco sul mare nel centro di Sorrento. Offre spiaggia privata, infinity pool con cascata, giardini di palme, tre ristoranti e una spa con jacuzzi, con vista diretta sul Golfo di Napoli e il Vesuvio.",
        "palette": {"primary": "#0d2b45", "secondary": "#c9a84c", "accent": "#2d6a4f", "light": "#f8f5ef", "dark": "#081929"},
        "stats": [
            {"num": "5★", "label": "Stelle di Lusso"},
            {"num": "3", "label": "Ristoranti"},
            {"num": "∞", "label": "Infinity Pool"},
            {"num": "Manniello", "label": "Gruppo Alberghiero"},
        ],
        "services": [
            {"title": "Infinity Pool & Giardini", "desc": "Piscina infinity con cascata d'acqua e giardini tropicali di palme a strapiombo sul mare", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Spiaggia Privata", "desc": "Accesso esclusivo alla spiaggia privata con beach club, lettini e servizio bar", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "3 Ristoranti", "desc": "Tre proposte gastronomiche distinte: dalla cucina sorrentina tradizionale ai sapori internazionali", "img": CAMPANIA_IMAGES["pizza2"]},
            {"title": "Camere Vista Golfo", "desc": "Suite e camere con terrazzo privato, vista Vesuvio e tramonto sul golfo mozzafiato", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Spa & Wellness", "desc": "Percorso benessere con jacuzzi panoramica, sauna, vapore e massaggi personalizzati", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Esperienze Luxury", "desc": "Gite in yacht, elicottero, tour privati a Capri e Costiera Amalfitana", "img": CAMPANIA_IMAGES["capri1"]},
        ],
        "highlights": ["Via Correale 42 Sorrento centro", "Spiaggia privata e beach club", "Infinity pool con cascata", "Vista Golfo e Vesuvio", "Gruppo Manniello dal 1960"],
        "cta": "Vivi il Lusso Fronte Mare",
        "chatbot_msg": "Benvenuti al Grand Hotel Royal Sorrento! Sono il vostro concierge virtuale. Come posso rendervi il soggiorno indimenticabile?",
    },
    {
        "slug": "golden-tours-international",
        "name": "Golden Tours International",
        "email": "incoming@goldentours.it",
        "category": "Tour operator / DMC incoming",
        "city": "Sorrento",
        "website": "https://www.goldentours.it",
        "hero_img": "https://www.goldentours.it/images/risorsa-1_823.png?v=a43a",
        "tagline": "DMC di eccellenza dal 1957 · Servizi luxury per gruppi e individuali nel Sud Italia",
        "description": "Golden Tours International è un DMC (Destination Management Company) con 65+ anni di esperienza nel turismo alto/lusso. Dal 1957 organizziamo escursioni, transfer, hotel 4-5 stelle e ville esclusive per tour operator e agenzie internazionali. Specializzati in yacht, elicottero e esperienze premium lungo Capri, Costiera Amalfitana, Pompei e Vesuvio.",
        "palette": {"primary": "#1a3a5c", "secondary": "#c9a84c", "accent": "#c9a84c", "light": "#f8f6f0", "dark": "#0d2035"},
        "stats": [
            {"num": "65+", "label": "Anni di Esperienza"},
            {"num": "1957", "label": "Anno di Fondazione"},
            {"num": "4-5★", "label": "Hotel Partner"},
            {"num": "FareHarbor", "label": "Booking Engine"},
        ],
        "services": [
            {"title": "Escursioni Premium", "desc": "Tour esclusivi a Pompei, Capri, Vesuvio e Costiera con guide private multilingue", "img": CAMPANIA_IMAGES["vesuvio"]},
            {"title": "Transfer Luxury", "desc": "Flotta Mercedes, Sprinter di lusso e limousine per transfer aeroporto e porto", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Hotel & Ville", "desc": "Selezione esclusiva di hotel 4-5 stelle e ville private con tariffe riservate", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Yacht & Barca Privata", "desc": "Noleggio yacht e barche private per gite a Capri e lungo la Costiera Amalfitana", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Food & Wine Tour", "desc": "Degustazioni di limoncello, mozzarella di bufala, pizza e vini vulcanici del territorio", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Servizi Elicottero", "desc": "Transfer e tour aerei per panorami mozzafiato su Capri, Costiera e Vesuvio", "img": CAMPANIA_IMAGES["napoli3"]},
        ],
        "highlights": ["65+ anni di esperienza premium", "Network B2B internazionale", "Servizi elicottero e yacht", "FareHarbor booking engine", "Specializzati in gruppi luxury"],
        "cta": "Richiedi un Preventivo",
        "chatbot_msg": "Ciao! Sono l'assistente AI di Golden Tours International. Posso aiutarti con escursioni, transfer luxury e pacchetti personalizzati per il vostro gruppo.",
    },
    {
        "slug": "grand-hotel-cocumella",
        "name": "Grand Hotel Cocumella",
        "email": "info@cocumella.com",
        "category": "Hotel 5 stelle (Small Luxury Hotels)",
        "city": "Sant'Agnello",
        "website": "https://www.cocumella.com",
        "hero_img": "https://www.cocumella.com/template/cocumella/images/bg/home_intro_bg.png",
        "tagline": "Dimora storica dal 1637 · Small Luxury Hotels · Veliero d'epoca per escursioni",
        "description": "Il Grand Hotel Cocumella è una delle dimore storiche più antiche della Penisola Sorrentina, risalente al 1637 come collegio dei Gesuiti. Oggi membro dei Small Luxury Hotels of the World, offre un'esperienza unica tra storia secolare, parco con agrumeti, piscina panoramica e il leggendario veliero d'epoca per escursioni in mare.",
        "palette": {"primary": "#4a3728", "secondary": "#6b8c5a", "accent": "#c9a050", "light": "#f9f5ef", "dark": "#2e2118"},
        "stats": [
            {"num": "1637", "label": "Anno di Fondazione"},
            {"num": "SLH", "label": "Small Luxury Hotels"},
            {"num": "⛵", "label": "Veliero d'Epoca"},
            {"num": "5★", "label": "Stelle Boutique"},
        ],
        "services": [
            {"title": "Camere Heritage", "desc": "Suite affrescate in un edificio del 1637, arredate con mobili antichi e viste sul golfo", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Parco & Agrumeto", "desc": "Giardini storici con agrumi centenari, pergolati e percorsi tra la natura mediterranea", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Piscina Panoramica", "desc": "Piscina con vista a 180° sul Golfo di Napoli, ideale per l'aperitivo al tramonto", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Veliero d'Epoca", "desc": "Escursioni in mare a bordo di un autentico veliero storico lungo la Costiera Amalfitana", "img": CAMPANIA_IMAGES["capri1"]},
            {"title": "Ristorante Il Chiostro", "desc": "Cucina mediterranea raffinata servita nell'antico chiostro dei Gesuiti con vista sul mare", "img": CAMPANIA_IMAGES["pizza2"]},
            {"title": "Spa & Benessere", "desc": "Trattamenti olistici con prodotti naturali in un ambiente storico di rara bellezza", "img": CAMPANIA_IMAGES["vesuvio2"]},
        ],
        "highlights": ["Storia secolare dal 1637", "Membro Small Luxury Hotels", "Veliero d'epoca unico al mondo", "Parco agrumeto storico", "Ex collegio dei Gesuiti"],
        "cta": "Prenota il Tuo Soggiorno Storico",
        "chatbot_msg": "Benvenuti alla Cocumella! Sono il vostro assistente storico digitale. Posso raccontarvi la storia secolare dell'hotel e assistervi nella prenotazione.",
    },
    {
        "slug": "grand-hotel-cesare-augusto",
        "name": "Grand Hotel Cesare Augusto",
        "email": "info@hotelcesareaugusto.com",
        "category": "Hotel 4 stelle",
        "city": "Sorrento",
        "website": "https://www.hotelcesareaugusto.com",
        "hero_img": "https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1600&q=80",
        "tagline": "120 camere nel cuore di Sorrento · Roof Garden con piscina · Ideale per gruppi",
        "description": "Il Grand Hotel Cesare Augusto è un 4 stelle con 120 camere nel centro di Sorrento, a pochi passi da Piazza Tasso. Con il suo roof garden con piscina, ristorante e grande capacità ricettiva, è la scelta ideale per tour operator, gruppi organizzati e congressi. Dotato di sistema Blastness per il revenue management.",
        "palette": {"primary": "#1e3a5f", "secondary": "#c9a84c", "accent": "#c9a84c", "light": "#f5f3ee", "dark": "#0d2035"},
        "stats": [
            {"num": "120", "label": "Camere"},
            {"num": "4★", "label": "Stelle"},
            {"num": "🏊", "label": "Roof Garden Piscina"},
            {"num": "Blastness", "label": "Revenue Management"},
        ],
        "services": [
            {"title": "120 Camere Centro", "desc": "Ampia capacità ricettiva nel centro di Sorrento, ideale per gruppi e tour operator", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Roof Garden & Piscina", "desc": "Terrazza con piscina sul tetto con vista panoramica sulla città e sul golfo", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Ristorante", "desc": "Cucina tipica sorrentina con ingredienti a km 0 e ricette tradizionali campane", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Sale Riunioni", "desc": "Spazi meeting modulabili per congressi, eventi aziendali e cerimonie", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Posizione Centrale", "desc": "A pochi passi da Piazza Tasso, porto e stazione ferroviaria Circumvesuviana", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Tour Operator", "desc": "Tariffe e contratti dedicati a tour operator con servizio gruppi e concierge dedicato", "img": CAMPANIA_IMAGES["vesuvio"]},
        ],
        "highlights": ["120 camere in posizione centrale", "Roof garden con piscina", "Ideal per gruppi e tour operator", "Vicino Piazza Tasso", "Revenue management Blastness"],
        "cta": "Prenota / Richiedi Preventivo Gruppi",
        "chatbot_msg": "Ciao! Sono l'assistente del Cesare Augusto. Posso aiutarti con disponibilità, preventivi per gruppi e organizzazione di soggiorni per tour operator.",
    },
    {
        "slug": "grand-hotel-la-favorita",
        "name": "Grand Hotel La Favorita",
        "email": "info@hotellafavorita.com",
        "category": "Hotel 5 stelle",
        "city": "Sorrento",
        "website": "https://www.hotellafavorita.com",
        "hero_img": "https://www.hotellafavorita.com/wp-content/uploads/ingresso.jpg",
        "tagline": "85 camere eleganti nel centro di Sorrento · Roof garden · Spa · Matrimoni & Eventi",
        "description": "Il Grand Hotel La Favorita è un 5 stelle con 85 camere nel cuore di Sorrento. Con il suo elegante roof garden con piscina, ristorante gourmet e spa raffinata, è il luogo ideale per soggiorni di lusso, matrimoni esclusivi e eventi privati. Gestione familiare con booking engine ErMes Hotels.",
        "palette": {"primary": "#2d4a1e", "secondary": "#c9a84c", "accent": "#c9a84c", "light": "#f5f8f2", "dark": "#1a2e12"},
        "stats": [
            {"num": "85", "label": "Camere"},
            {"num": "5★", "label": "Stelle"},
            {"num": "💒", "label": "Wedding & Events"},
            {"num": "SEO 100", "label": "Visibilità Online"},
        ],
        "services": [
            {"title": "Camere & Suite Luxury", "desc": "85 camere eleganti con arredi classici, balcone e vista sul centro storico di Sorrento", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Roof Garden & Piscina", "desc": "Terrazza-giardino con piscina, solarium e veduta panoramica sulla città e sul golfo", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Ristorante Gourmet", "desc": "Cucina sorrentina d'autore con prodotti a km 0, vini locali e menù degustazione", "img": CAMPANIA_IMAGES["pizza2"]},
            {"title": "Spa & Relax", "desc": "Percorso benessere completo con piscina termale, sauna, vapore e massaggi", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Matrimoni & Cerimonie", "desc": "Location esclusiva per matrimoni ed eventi privati con servizio dedicato", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Esperienze Sorrento", "desc": "Tour a Capri, Costiera Amalfitana e Pompei con partner fidati e guide certificate", "img": CAMPANIA_IMAGES["capri1"]},
        ],
        "highlights": ["85 camere 5 stelle centro Sorrento", "Roof garden e piscina", "Cucina gourmet e spa", "Matrimoni ed eventi esclusivi", "Gestione familiare dal 1960"],
        "cta": "Prenota Ora",
        "chatbot_msg": "Benvenuti alla Favorita! Sono il vostro assistente personale. Posso aiutarvi con prenotazioni, informazioni sui servizi e organizzazione di eventi.",
    },
    {
        "slug": "hotel-continental-sorrento",
        "name": "Hotel Continental Sorrento",
        "email": "info@continentalsorrento.com",
        "category": "Hotel 4 stelle",
        "city": "Sorrento",
        "website": "https://www.continentalsorrento.com",
        "hero_img": "https://www.continentalsorrento.com/gallery/gallery/hotel-08466UAXKJAYE4M2.jpg",
        "tagline": "Nel cuore di Sorrento vicino al porto · Terrazza panoramica · Piscina · Ristorante",
        "description": "L'Hotel Continental Sorrento è un 4 stelle nel centro storico di Sorrento, a pochi passi dal porto, dal Chiostro di San Francesco e da Piazza Tasso. Con la sua splendida terrazza panoramica, piscina e ristorante, offre un soggiorno autentico nel cuore della città più amata della Penisola Sorrentina.",
        "palette": {"primary": "#1a3a6e", "secondary": "#c0a060", "accent": "#c0a060", "light": "#f0f5fc", "dark": "#0d2040"},
        "stats": [
            {"num": "4★", "label": "Stelle"},
            {"num": "🏊", "label": "Piscina con Vista"},
            {"num": "5 min", "label": "Dal Porto"},
            {"num": "Piazza Tasso", "label": "A pochi passi"},
        ],
        "services": [
            {"title": "Terrazza Panoramica", "desc": "Splendida terrazza con vista sul golfo di Napoli, ideale per aperitivi al tramonto", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Piscina", "desc": "Piscina con solarium e bar bordo piscina, perfetta per rilassarsi nel clima sorrentino", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Ristorante", "desc": "Cucina mediterranea tradizionale con specialità locali e vasta selezione di vini campani", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Posizione Centrale", "desc": "A 5 minuti a piedi dal porto e dalla stazione, vicino al Chiostro San Francesco", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Camere Classiche", "desc": "Camere confortevoli con arredi classici, aria condizionata e WiFi gratuito", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Tour & Escursioni", "desc": "Reception dedicata all'organizzazione di gite a Capri, Costiera, Pompei e Vesuvio", "img": CAMPANIA_IMAGES["vesuvio"]},
        ],
        "highlights": ["Posizione centrale Sorrento", "Terrazza panoramica sul golfo", "Vicino porto e Piazza Tasso", "Piscina con solarium", "A 5 minuti dal Chiostro San Francesco"],
        "cta": "Prenota Ora",
        "chatbot_msg": "Ciao! Sono l'assistente del Continental Sorrento. Come posso aiutarti? Posso fornirti informazioni su camere, disponibilità ed escursioni nei dintorni.",
    },
]

def get_demo_slug(name):
    return name.lower().replace(" ", "-").replace("&", "e").replace("'", "").replace("'", "")[:50]

def generate_html(company):
    p = company["palette"]
    slug = company["slug"]
    demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-demo-experiences/"

    stats_html = ""
    for s in company["stats"]:
        stats_html += f"""
            <div class="stat-item">
                <div class="stat-num">{s['num']}</div>
                <div class="stat-label">{s['label']}</div>
            </div>"""

    services_html = ""
    for svc in company["services"]:
        services_html += f"""
            <div class="card" data-aos="fade-up">
                <div class="card-img-wrap">
                    <img src="{svc['img']}" alt="{svc['title']}" loading="lazy">
                </div>
                <div class="card-body">
                    <h3 class="card-title">{svc['title']}</h3>
                    <p class="card-desc">{svc['desc']}</p>
                </div>
            </div>"""

    highlights_html = ""
    for h in company["highlights"]:
        highlights_html += f'<li><span class="check">✓</span> {h}</li>\n'

    return f"""<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{company['name']} - Demo Experiences Srl</title>
    <meta name="description" content="{company['name']} - {company['category']} a {company['city']} - Demo realizzato da Experiences Srl">
    <meta property="og:title" content="{company['name']} - Demo Experiences Srl">
    <meta property="og:description" content="{company['tagline']}">
    <style>
        *, *::before, *::after {{ margin: 0; padding: 0; box-sizing: border-box; }}
        html {{ scroll-behavior: smooth; font-size: 16px; }}
        body {{ font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; line-height: 1.65; color: #333; background: #fff; }}

        /* DEMO BANNER */
        .demo-banner {{
            background: linear-gradient(90deg, {p['primary']} 0%, {p['dark']} 100%);
            color: #fff;
            text-align: center;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.3px;
            position: relative;
            z-index: 200;
        }}
        .demo-banner a {{ color: {p['accent']}; text-decoration: none; border-bottom: 1px solid {p['accent']}; }}
        .demo-banner a:hover {{ opacity: 0.85; }}

        /* NAVBAR */
        .navbar {{
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0,0,0,0.07);
            padding: 0 24px;
        }}
        .nav-inner {{
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
        }}
        .nav-logo {{
            font-weight: 800;
            font-size: 17px;
            color: {p['primary']};
            letter-spacing: -0.3px;
        }}
        .nav-links {{
            display: flex;
            gap: 28px;
            align-items: center;
            list-style: none;
        }}
        .nav-links a {{
            color: #555;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }}
        .nav-links a:hover {{ color: {p['primary']}; }}
        .btn-cta {{
            background: {p['primary']};
            color: #fff !important;
            padding: 9px 22px;
            border-radius: 6px;
            font-weight: 600 !important;
            font-size: 14px;
            transition: all 0.2s !important;
        }}
        .btn-cta:hover {{ background: {p['dark']} !important; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }}

        /* HERO */
        .hero {{
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }}
        .hero-bg {{
            position: absolute;
            inset: 0;
            background-image: url('{company['hero_img']}');
            background-size: cover;
            background-position: center;
            filter: brightness(0.45);
            transform: scale(1.03);
            transition: transform 8s ease;
        }}
        .hero:hover .hero-bg {{ transform: scale(1.07); }}
        .hero-overlay {{
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.5) 100%);
        }}
        .hero-content {{
            position: relative;
            z-index: 2;
            color: #fff;
            max-width: 780px;
            padding: 0 24px;
            animation: fadeInUp 0.8s ease-out;
        }}
        .hero-badge {{
            display: inline-block;
            background: {p['accent']};
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 5px 16px;
            border-radius: 20px;
            margin-bottom: 20px;
        }}
        .hero h1 {{
            font-size: clamp(32px, 5vw, 60px);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 18px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }}
        .hero p {{
            font-size: clamp(15px, 2vw, 18px);
            opacity: 0.92;
            margin-bottom: 32px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }}
        .hero-btns {{ display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }}
        .btn {{
            padding: 13px 30px;
            border-radius: 7px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s;
            display: inline-block;
        }}
        .btn-primary {{ background: {p['accent']}; color: #fff; border: 2px solid {p['accent']}; }}
        .btn-primary:hover {{ background: transparent; color: {p['accent']}; }}
        .btn-outline {{ background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.7); }}
        .btn-outline:hover {{ background: rgba(255,255,255,0.15); }}

        /* STATS */
        .stats-bar {{
            background: {p['primary']};
            color: #fff;
            padding: 40px 20px;
        }}
        .stats-inner {{
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            text-align: center;
        }}
        .stat-item {{ padding: 10px 0; }}
        .stat-num {{ font-size: 38px; font-weight: 800; color: {p['accent']}; line-height: 1; margin-bottom: 8px; }}
        .stat-label {{ font-size: 13px; opacity: 0.85; letter-spacing: 0.3px; }}

        /* ABOUT */
        .about-section {{
            max-width: 1100px;
            margin: 80px auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }}
        .about-img-wrap {{
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }}
        .about-img-wrap img {{ width: 100%; height: 380px; object-fit: cover; display: block; }}
        .about-text .section-tag {{
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: {p['secondary']};
            margin-bottom: 14px;
        }}
        .about-text h2 {{ font-size: 34px; font-weight: 800; color: {p['primary']}; margin-bottom: 18px; line-height: 1.2; }}
        .about-text p {{ color: #555; font-size: 15px; margin-bottom: 20px; }}
        .highlights-list {{ list-style: none; margin-top: 10px; }}
        .highlights-list li {{
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 7px 0;
            font-size: 14px;
            color: #444;
            border-bottom: 1px solid #f0f0f0;
        }}
        .check {{ color: {p['secondary']}; font-weight: 800; font-size: 16px; flex-shrink: 0; }}

        /* SERVICES */
        .services-section {{ background: {p['light']}; padding: 80px 24px; }}
        .section-header {{ text-align: center; margin-bottom: 50px; }}
        .section-header .section-tag {{
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: {p['secondary']};
            margin-bottom: 12px;
        }}
        .section-header h2 {{ font-size: 36px; font-weight: 800; color: {p['primary']}; }}
        .section-header p {{ color: #666; margin-top: 12px; max-width: 560px; margin-left: auto; margin-right: auto; }}
        .cards-grid {{
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
        }}
        .card {{
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            transition: all 0.3s;
        }}
        .card:hover {{ transform: translateY(-6px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }}
        .card-img-wrap {{ overflow: hidden; height: 200px; }}
        .card-img-wrap img {{ width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }}
        .card:hover .card-img-wrap img {{ transform: scale(1.06); }}
        .card-body {{ padding: 24px; }}
        .card-title {{ font-size: 17px; font-weight: 700; color: {p['primary']}; margin-bottom: 10px; }}
        .card-desc {{ font-size: 13.5px; color: #666; line-height: 1.6; }}

        /* CHATBOT PREVIEW */
        .chatbot-section {{
            background: linear-gradient(135deg, {p['primary']} 0%, {p['dark']} 100%);
            padding: 80px 24px;
            color: #fff;
        }}
        .chatbot-inner {{
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }}
        .chatbot-text h2 {{ font-size: 32px; font-weight: 800; margin-bottom: 16px; }}
        .chatbot-text p {{ opacity: 0.88; margin-bottom: 20px; font-size: 15px; }}
        .chatbot-features {{ list-style: none; margin-bottom: 28px; }}
        .chatbot-features li {{
            padding: 8px 0;
            font-size: 14px;
            display: flex;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }}
        .chatbot-ui {{
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: 20px;
        }}
        .chatbot-header {{
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }}
        .chatbot-dot {{ width: 10px; height: 10px; border-radius: 50%; background: #4ade80; animation: blink 1.5s infinite; }}
        @keyframes blink {{ 0%,100%{{opacity:1}} 50%{{opacity:0.3}} }}
        .chat-bubble {{
            background: rgba(255,255,255,0.12);
            border-radius: 12px 12px 12px 0;
            padding: 12px 16px;
            margin-bottom: 10px;
            font-size: 13px;
            line-height: 1.5;
        }}
        .chat-user {{
            background: {p['accent']};
            border-radius: 12px 12px 0 12px;
            padding: 10px 14px;
            font-size: 13px;
            margin-left: auto;
            max-width: 80%;
            text-align: right;
            margin-bottom: 10px;
        }}
        .chat-input {{
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }}
        .chat-input input {{
            flex: 1;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            padding: 9px 14px;
            color: #fff;
            font-size: 13px;
        }}
        .chat-input input::placeholder {{ color: rgba(255,255,255,0.5); }}
        .chat-send {{
            background: {p['accent']};
            border: none;
            border-radius: 8px;
            padding: 9px 14px;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }}

        /* PRICING */
        .pricing-section {{ padding: 80px 24px; background: #fff; }}
        .pricing-grid {{
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
        }}
        .pricing-card {{
            border: 2px solid #e8e8e8;
            border-radius: 16px;
            padding: 32px 28px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
        }}
        .pricing-card:hover {{ border-color: {p['primary']}; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }}
        .pricing-card.featured {{
            border-color: {p['accent']};
            background: linear-gradient(180deg, {p['light']} 0%, #fff 100%);
            transform: scale(1.03);
        }}
        .pricing-badge {{
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: {p['accent']};
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            white-space: nowrap;
        }}
        .pricing-name {{ font-size: 16px; font-weight: 700; color: {p['primary']}; margin-bottom: 12px; }}
        .pricing-price {{ font-size: 32px; font-weight: 900; color: {p['primary']}; margin-bottom: 4px; }}
        .pricing-period {{ font-size: 12px; color: #999; margin-bottom: 6px; }}
        .pricing-commission {{ font-size: 13px; color: {p['secondary']}; font-weight: 600; margin-bottom: 20px; padding: 8px; background: {p['light']}; border-radius: 6px; }}
        .pricing-features {{ list-style: none; text-align: left; margin-bottom: 24px; }}
        .pricing-features li {{ padding: 7px 0; font-size: 13px; color: #555; display: flex; gap: 8px; border-bottom: 1px solid #f5f5f5; }}
        .pricing-btn {{
            display: block;
            width: 100%;
            padding: 11px;
            background: {p['primary']};
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }}
        .pricing-btn:hover {{ background: {p['dark']}; }}
        .pricing-card.featured .pricing-btn {{ background: {p['accent']}; }}

        /* CTA */
        .cta-section {{
            background: linear-gradient(135deg, {p['secondary']} 0%, {p['primary']} 100%);
            padding: 80px 24px;
            text-align: center;
            color: #fff;
        }}
        .cta-section h2 {{ font-size: 38px; font-weight: 800; margin-bottom: 16px; }}
        .cta-section p {{ opacity: 0.9; font-size: 17px; margin-bottom: 36px; max-width: 560px; margin-left: auto; margin-right: auto; }}
        .cta-btns {{ display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }}
        .cta-btns .btn-white {{
            background: #fff;
            color: {p['primary']};
            padding: 14px 34px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.25s;
        }}
        .cta-btns .btn-white:hover {{ transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }}
        .cta-btns .btn-ghost {{
            background: transparent;
            color: #fff;
            padding: 14px 34px;
            border: 2px solid rgba(255,255,255,0.6);
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.25s;
        }}
        .cta-btns .btn-ghost:hover {{ background: rgba(255,255,255,0.1); }}

        /* FOOTER */
        footer {{
            background: {p['dark']};
            color: rgba(255,255,255,0.8);
            padding: 40px 24px;
            text-align: center;
            font-size: 13px;
        }}
        footer a {{ color: {p['accent']}; text-decoration: none; }}
        footer strong {{ color: #fff; }}

        /* ANIMATIONS */
        @keyframes fadeInUp {{
            from {{ opacity: 0; transform: translateY(24px); }}
            to {{ opacity: 1; transform: translateY(0); }}
        }}

        /* RESPONSIVE */
        @media (max-width: 768px) {{
            .nav-links {{ display: none; }}
            .about-section {{ grid-template-columns: 1fr; gap: 30px; }}
            .chatbot-inner {{ grid-template-columns: 1fr; }}
            .pricing-card.featured {{ transform: none; }}
            .hero {{ min-height: 75vh; }}
        }}
    </style>
</head>
<body>

<div class="demo-banner">
    🎯 Questa è una <strong>DEMO GRATUITA</strong> realizzata da <a href="https://naplesexperiences.com" target="_blank">Experiences Srl</a> —
    Vuoi un sito come questo? <a href="https://wa.me/393926917657" target="_blank">Contattaci su WhatsApp</a>
</div>

<nav class="navbar">
    <div class="nav-inner">
        <div class="nav-logo">{company['name']}</div>
        <ul class="nav-links">
            <li><a href="#about">Chi Siamo</a></li>
            <li><a href="#services">Servizi</a></li>
            <li><a href="#pricing">Piani</a></li>
            <li><a href="#contact" class="btn-cta">{company['cta']}</a></li>
        </ul>
    </div>
</nav>

<section class="hero" id="home">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-badge">{company['category']}</div>
        <h1>{company['name']}</h1>
        <p>{company['tagline']}</p>
        <div class="hero-btns">
            <a href="#contact" class="btn btn-primary">{company['cta']}</a>
            <a href="#services" class="btn btn-outline">Scopri i Servizi</a>
        </div>
    </div>
</section>

<div class="stats-bar">
    <div class="stats-inner">
        {stats_html}
    </div>
</div>

<section class="about-section" id="about">
    <div class="about-img-wrap">
        <img src="{CAMPANIA_IMAGES['napoli3']}" alt="{company['name']} - Sorrento e Golfo di Napoli">
    </div>
    <div class="about-text">
        <div class="section-tag">Chi Siamo</div>
        <h2>La nostra storia e il nostro impegno</h2>
        <p>{company['description']}</p>
        <ul class="highlights-list">
            {highlights_html}
        </ul>
    </div>
</section>

<section class="services-section" id="services">
    <div class="section-header">
        <div class="section-tag">I Nostri Servizi</div>
        <h2>Esperienze Autentiche nel Sud Italia</h2>
        <p>Ogni servizio è pensato per offrire il meglio del territorio campano, con standard di eccellenza e attenzione al dettaglio.</p>
    </div>
    <div class="cards-grid">
        {services_html}
    </div>
</section>

<section class="chatbot-section" id="chatbot">
    <div class="chatbot-inner">
        <div class="chatbot-text">
            <h2>Assistente AI Multilingua 24/7</h2>
            <p>Con Experiences Srl, il vostro sito risponde ai clienti in italiano, inglese, tedesco, francese e spagnolo — anche di notte, nei weekend e durante i picchi di richieste.</p>
            <ul class="chatbot-features">
                <li>🌍 <span>Risponde in IT/EN/DE/FR/ES istantaneamente</span></li>
                <li>📅 <span>Gestisce prenotazioni e preventivi automatici</span></li>
                <li>💬 <span>Riduce il 70% delle email manuali</span></li>
                <li>📊 <span>Integrazione con il vostro booking engine</span></li>
                <li>⚡ <span>Risposta media: sotto i 2 secondi</span></li>
            </ul>
            <a href="https://wa.me/393926917657" class="btn btn-primary" style="display:inline-block;">💬 Demo Chatbot Live</a>
        </div>
        <div class="chatbot-ui">
            <div class="chatbot-header">
                <div class="chatbot-dot"></div>
                <span style="font-size:14px; font-weight:600;">Assistente AI · {company['name']}</span>
            </div>
            <div class="chat-bubble">{company['chatbot_msg']}</div>
            <div class="chat-user">Ciao! Avete disponibilità per il weekend del 20 luglio?</div>
            <div class="chat-bubble">Perfetto! Ho verificato: abbiamo disponibilità per il 20-22 luglio. Posso mostrarle le opzioni disponibili con prezzi e servizi inclusi. Quante persone siete?</div>
            <div class="chat-input">
                <input type="text" placeholder="Scrivi un messaggio...">
                <button class="chat-send">→</button>
            </div>
        </div>
    </div>
</section>

<section class="pricing-section" id="pricing">
    <div class="section-header">
        <div class="section-tag">Piani Experiences Srl</div>
        <h2>Soluzioni su Misura per il Vostro Business</h2>
        <p>Ogni piano include la commissione sulle vendite in base al volume — più vendete, più risparmiate sulla quota fissa.</p>
    </div>
    <div class="pricing-grid">
        <div class="pricing-card">
            <div class="pricing-name">Piano Advanced</div>
            <div class="pricing-price">€500</div>
            <div class="pricing-period">/anno</div>
            <div class="pricing-commission">+ X% commissione in base al volume</div>
            <ul class="pricing-features">
                <li>✓ <span>Sito mobile-first ottimizzato</span></li>
                <li>✓ <span>SEO base + Google My Business</span></li>
                <li>✓ <span>Modulo contatti e preventivi</span></li>
                <li>✓ <span>Integrazione social media</span></li>
                <li>✓ <span>Supporto tecnico email</span></li>
            </ul>
            <a href="https://wa.me/393926917657" class="pricing-btn">Inizia Ora</a>
        </div>
        <div class="pricing-card featured">
            <div class="pricing-badge">⭐ Più Scelto</div>
            <div class="pricing-name">Piano Pro</div>
            <div class="pricing-price">€1.000</div>
            <div class="pricing-period">/anno</div>
            <div class="pricing-commission">+ X% commissione in base al volume</div>
            <ul class="pricing-features">
                <li>✓ <span>Tutto del piano Advanced</span></li>
                <li>✓ <span>Chatbot AI multilingua 24/7</span></li>
                <li>✓ <span>Booking engine integrato</span></li>
                <li>✓ <span>Google Ads e Meta Ads gestiti</span></li>
                <li>✓ <span>Report mensile performance</span></li>
            </ul>
            <a href="https://wa.me/393926917657" class="pricing-btn">Inizia Ora</a>
        </div>
        <div class="pricing-card">
            <div class="pricing-name">Piano Base</div>
            <div class="pricing-price">€1.400</div>
            <div class="pricing-period">/anno</div>
            <div class="pricing-commission">+ X% commissione in base al volume</div>
            <ul class="pricing-features">
                <li>✓ <span>Tutto del piano Pro</span></li>
                <li>✓ <span>Channel Manager OTA</span></li>
                <li>✓ <span>Integrazione Booking.com, Expedia</span></li>
                <li>✓ <span>GetYourGuide, Viator, Tiqets</span></li>
                <li>✓ <span>Account manager dedicato</span></li>
            </ul>
            <a href="https://wa.me/393926917657" class="pricing-btn">Inizia Ora</a>
        </div>
        <div class="pricing-card">
            <div class="pricing-name">Piano Enterprise</div>
            <div class="pricing-price">0€</div>
            <div class="pricing-period">quota fissa</div>
            <div class="pricing-commission">Solo X% sulle vendite generate</div>
            <ul class="pricing-features">
                <li>✓ <span>Soluzione white-label completa</span></li>
                <li>✓ <span>Nessun costo fisso iniziale</span></li>
                <li>✓ <span>Account manager dedicato H24</span></li>
                <li>✓ <span>Sviluppo custom su misura</span></li>
                <li>✓ <span>SLA garantito al 99.9%</span></li>
            </ul>
            <a href="https://wa.me/393926917657" class="pricing-btn">Contattaci</a>
        </div>
    </div>
</section>

<section class="cta-section" id="contact">
    <h2>Pronti a Trasformare il Vostro Business?</h2>
    <p>Questa demo è stata creata appositamente per {company['name']}. Rendiamola reale — consulenza gratuita di 20 minuti senza impegno.</p>
    <div class="cta-btns">
        <a href="https://wa.me/393926917657" class="btn-white">💬 Scrivici su WhatsApp</a>
        <a href="mailto:naplesexperiences@gmail.com" class="btn-ghost">📧 Invia una Email</a>
    </div>
    <p style="margin-top:24px; font-size:13px; opacity:0.7;">Mario Esposito · Experiences Srl · +39 392 691 7657</p>
</section>

<footer>
    <p>© 2025 <strong>{company['name']}</strong> · Demo realizzato da <a href="https://naplesexperiences.com" target="_blank"><strong>Experiences Srl</strong></a></p>
    <p style="margin-top:8px;">
        <a href="https://wa.me/393926917657" target="_blank">+39 392 691 7657</a> ·
        <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a> ·
        <a href="https://naplesexperiences.com" target="_blank">naplesexperiences.com</a>
    </p>
    <p style="margin-top:8px; font-size:11px; opacity:0.5;">Rispondi con "CANCELLA" per non ricevere ulteriori comunicazioni</p>
</footer>

</body>
</html>"""

def main():
    csv_file = 'demos/ricerca_clienti_csv/CSV_Golfo_Napoli_Sorrento.csv'

    with open(csv_file, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        rows = list(reader)

    created = []
    for company in COMPANIES:
        slug = company["slug"]
        demo_dir = f'demos/{slug}-demo-experiences'
        Path(demo_dir).mkdir(parents=True, exist_ok=True)

        html = generate_html(company)
        with open(f'{demo_dir}/index.html', 'w', encoding='utf-8') as f:
            f.write(html)

        demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-demo-experiences/"

        # Update CSV
        for row in rows:
            if company["name"] in row["Nome_Azienda"]:
                row["Link_Demo"] = demo_url
                row["Data_Contatto"] = datetime.now().strftime('%Y-%m-%d')
                break

        created.append(company["name"])
        print(f"✓ {company['name']}")

    # Rewrite CSV
    with open(csv_file, 'w', newline='', encoding='utf-8') as f:
        fieldnames = list(rows[0].keys())
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)

    print(f"\n✅ {len(created)} demo generate con v3!")

if __name__ == '__main__':
    main()
