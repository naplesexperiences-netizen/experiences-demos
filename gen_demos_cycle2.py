#!/usr/bin/env python3
"""
Generatore Demo Ciclo 2 - Prossimi 10 aziende (priority 1-3)
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
        "slug": "hotel-antiche-mura-sorrento",
        "name": "Hotel Antiche Mura Sorrento",
        "email": "info@hotelantichemura.com",
        "category": "Hotel 4 stelle",
        "city": "Sorrento",
        "website": "https://www.hotelantichemura.com",
        "hero_img": "https://www.hotelantichemura.com/assets/images/public/slider/screen/swimming08-1920x1080_1562165772.jpg",
        "about_img": "https://www.hotelantichemura.com/assets/images/public/slider/screen/swimming08-1920x1080_1562165772.jpg",
        "tagline": "Nel cuore di Piazza Tasso · Piscina nel vallone dei mulini · Agrumeto · Charme storico",
        "description": "L'Hotel Antiche Mura Sorrento è un elegante 4 stelle ubicato in Piazza Tasso, nel cuore del centro storico di Sorrento. Con la sua piscina unica scavata nei mulini storici, un agrumeto privato e un'atmosfera di charme autentico, offre un'esperienza esclusiva a pochi passi da tutte le attrazioni della città.",
        "palette": {"primary": "#8b6f47", "secondary": "#5a8c5a", "accent": "#d4a574", "light": "#f9f6f0", "dark": "#5a4a38"},
        "stats": [
            {"num": "4★", "label": "Stelle"},
            {"num": "Piazza Tasso", "label": "Centro Storico"},
            {"num": "🏊", "label": "Piscina Vallone"},
            {"num": "SEO 83", "label": "Visibilità"},
        ],
        "services": [
            {"title": "Camere Eleganti", "desc": "Camere accoglienti nel cuore del centro storico con viste sulla piazza e il golfo", "img": "https://www.hotelantichemura.com/assets/images/public/slider/screen/swimming08-1920x1080_1562165772.jpg"},
            {"title": "Piscina Vallone", "desc": "Piscina unica scavata negli antichi mulini del vallone, esperienza esclusiva", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Agrumeto Privato", "desc": "Giardino con agrumi storici e terrazza panoramica sul centro di Sorrento", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Ristorante", "desc": "Cucina locale tradizionale con ingredienti a km 0 e viste sulla piazza", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Posizione Premium", "desc": "A pochi passi dal Chiostro San Francesco, porto e tutte le attrazioni storiche", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Esperienze Locali", "desc": "Concierge per escursioni a Capri, Positano, Pompei e Vesuvio con transfer incluso", "img": CAMPANIA_IMAGES["capri2"]},
        ],
        "highlights": ["Posizione premium Piazza Tasso", "Piscina nel vallone storico", "Agrumeto privato", "Centro storico Sorrento", "SEO 83 - ottimizzazione possibile"],
        "cta": "Prenota Ora",
        "chatbot_msg": "Ciao! Sono l'assistente dell'Hotel Antiche Mura. Posso aiutarti con disponibilità, informazioni sulla piscina unica e organizzazione di esperienze nel centro storico.",
    },
    {
        "slug": "grand-hotel-la-pace",
        "name": "Grand Hotel La Pace",
        "email": "info@ghlapace.com",
        "category": "Hotel 5 stelle",
        "city": "Sant'Agnello",
        "website": "https://www.ghlapace.com",
        "hero_img": "https://www.ghlapace.com/wp-content/uploads/2022/04/grand-hotel-la-pace-sorrento.jpg",
        "about_img": "https://www.ghlapace.com/wp-content/uploads/2022/06/barlapace1.jpg",
        "tagline": "5 stelle lusso a Sant'Agnello · Spa e piscine panoramiche · Via Tordara 10",
        "description": "Il Grand Hotel La Pace è un 5 stelle lusso situato a Sant'Agnello, Via Tordara 10, con una prestigiosa spa e piscine panoramiche affacciate sul Golfo di Napoli. Perfetto per ospiti che cercano il massimo del lusso e del benessere in una location esclusiva della Penisola Sorrentina.",
        "palette": {"primary": "#1a3a6e", "secondary": "#c9a84c", "accent": "#c9a84c", "light": "#f0f5fc", "dark": "#0d2040"},
        "stats": [
            {"num": "5★", "label": "Stelle Lusso"},
            {"num": "SEO 100", "label": "Ottimale"},
            {"num": "🏊", "label": "Piscine Panoramiche"},
            {"num": "Sant'Agnello", "label": "Esclusiva Posizione"},
        ],
        "services": [
            {"title": "Suite Lusso", "desc": "Suite panoramiche con vista golfo, design elegante e comfort supremo", "img": "https://www.ghlapace.com/wp-content/uploads/2022/04/grand-hotel-la-pace-sorrento.jpg"},
            {"title": "Spa Benessere", "desc": "Centro benessere completo con trattamenti e cure ayurvediche e wellness", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Piscine Panoramiche", "desc": "Piscine con vista spettacolare sul Golfo e solarium riscaldato", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Ristorante Gourmet", "desc": "Cucina d'autore con ingredienti locali e selezione vini campani", "img": CAMPANIA_IMAGES["pizza2"]},
            {"title": "Terrazza Vista Golfo", "desc": "Ampia terrazza panoramica ideale per cena al tramonto e aperitivi", "img": CAMPANIA_IMAGES["capri1"]},
            {"title": "Esperienze Luxury", "desc": "Yacht privati, elicottero, tour esclusivi e servizi bespoke", "img": CAMPANIA_IMAGES["italy"]},
        ],
        "highlights": ["5 stelle lusso Sant'Agnello", "SEO 100 - Top ranking", "Spa e piscine panoramiche", "Via Tordara 10", "Clientela VIP internazionale"],
        "cta": "Prenota il Tuo Soggiorno Luxury",
        "chatbot_msg": "Benvenuti al Grand Hotel La Pace! Sono il vostro concierge virtuale. Posso assistervi con prenotazioni suite, informazioni sulla spa e organizzazione di esperienze private.",
    },
    {
        "slug": "bleu-village-resort-residence",
        "name": "Bleu Village Resort & Residence",
        "email": "info@bleuvillage.com",
        "category": "Resort & Residence",
        "city": "Meta di Sorrento",
        "website": "https://www.bleuvillage.com",
        "hero_img": "https://www.bleuvillage.com/images/large/tbg1290_piscina-bleu-village-meta.jpg?v=ffe5",
        "about_img": "https://www.bleuvillage.com/images/large/tbg145_bleu-village-holiday-village-38_673.jpg?v=d0af",
        "tagline": "Villaggio turistico e residence · Bungalow e residence autonomi · Meta di Sorrento",
        "description": "Bleu Village Resort & Residence è un innovativo villaggio turistico a Meta di Sorrento con bungalow e residence completamente autonomi. Ideale per famiglie e gruppi che cercano indipendenza e flessibilità in una location turistica di qualità sulla Penisola Sorrentina.",
        "palette": {"primary": "#1b5e8a", "secondary": "#c9a84c", "accent": "#c9a84c", "light": "#ecf3f9", "dark": "#0d3a52"},
        "stats": [
            {"num": "1", "label": "Resort Priority"},
            {"num": "🏠", "label": "Residence Autonomi"},
            {"num": "Meta di Sorrento", "label": "Posizione"},
            {"num": "Famiglie", "label": "Target"},
        ],
        "services": [
            {"title": "Bungalow & Residence", "desc": "Unità autonome con cucina, zona living e terrazza privata", "img": "https://www.bleuvillage.com/images/large/tbg145_bleu-village-holiday-village-38_673.jpg?v=d0af"},
            {"title": "Piscina Principale", "desc": "Area piscina ampia con solarium, bar e animazione", "img": "https://www.bleuvillage.com/images/large/tbg1290_piscina-bleu-village-meta.jpg?v=ffe5"},
            {"title": "Servizi Comuni", "desc": "Reception 24/7, parcheggio, WiFi, lavanderia e servizio pulizie", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Mini Market", "desc": "Negozio interno per generi alimentari e articoli da spiaggia", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Beach Access", "desc": "Accesso facilitato alle spiagge e servizi spiaggia convenzionati", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Escursioni Organize", "desc": "Pacchetti escursioni a Capri, Costiera, Vesuvio con organizzazione resort", "img": CAMPANIA_IMAGES["napoli1"]},
        ],
        "highlights": ["Villaggio turistico innovativo", "Residence totalmente autonomi", "Piscina e area comune", "Ideale per famiglie e gruppi", "Meta di Sorrento - posizione turistica"],
        "cta": "Prenota il Tuo Bungalow",
        "chatbot_msg": "Ciao! Benvenuti a Bleu Village. Sono l'assistente del resort. Posso aiutarvi con disponibilità bungalow, servizi del villaggio e prenotazione escursioni.",
    },
    {
        "slug": "grand-hotel-parkers-napoli",
        "name": "Grand Hotel Parker's Napoli",
        "email": "info@grandhotelparkers.it",
        "category": "Hotel 5 stelle Lusso",
        "city": "Napoli",
        "website": "https://www.grandhotelparkers.it",
        "hero_img": "https://www.grandhotelparkers.it/catalog/view/theme/hotel/image/svg/BASE-stemma.png",
        "about_img": "https://www.grandhotelparkers.it/catalog/view/theme/hotel/image/svg/BASE-stemma.png",
        "tagline": "Storico dal 1870 · Relais & Châteaux · Napoli Corso Vittorio Emanuele · 83 camere lusso",
        "description": "Il Grand Hotel Parker's è un leggendario 5 stelle lusso a Napoli, celebre dal 1870 e membro di Relais & Châteaux. Con 83 camere eleganti in Corso Vittorio Emanuele, offre un'esperienza d'eccellenza nel cuore del capoluogo campano, punto di partenza ideale per esplorare il Sud Italia.",
        "palette": {"primary": "#4a3728", "secondary": "#8b7355", "accent": "#d4a574", "light": "#f9f6f0", "dark": "#2e231a"},
        "stats": [
            {"num": "1870", "label": "Anno Fondazione"},
            {"num": "Relais &", "label": "Châteaux"},
            {"num": "83", "label": "Camere Lusso"},
            {"num": "SEO 92", "label": "Visibilità"},
        ],
        "services": [
            {"title": "Camere Storiche", "desc": "83 camere eleganti in Corso Vittorio Emanuele con vista Vesuvio e golfo", "img": "https://www.grandhotelparkers.it/catalog/view/theme/hotel/image/svg/BASE-stemma.png"},
            {"title": "Ristorante Stellato", "desc": "Cucina raffinata di alta tradizione napoletana e internazionale", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Rooftop Bar", "desc": "Bar panoramico sul golfo con cena sotto le stelle e vista Vesuvio", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Spa & Wellness", "desc": "Centro benessere con trattamenti luxury e spa privata", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Posizione Storica", "desc": "Nel cuore di Napoli, a pochi passi da San Carlo, Teatro San Ferdinando, Chiostri", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Concierge Luxury", "desc": "Servizio concierge esclusivo per esperienze VIP a Napoli e Sud Italia", "img": CAMPANIA_IMAGES["capri1"]},
        ],
        "highlights": ["Storico dal 1870", "Relais & Châteaux membro", "Corso Vittorio Emanuele", "Clientela VIP internazionale", "SEO 92 - top ranking"],
        "cta": "Prenota il Tuo Soggiorno Storico",
        "chatbot_msg": "Benvenuti al Grand Hotel Parker's. Sono il concierge digitale di questo hotel leggendario. Posso assistere con prenotazioni, esperienze a Napoli e servizi esclusivi.",
    },
    {
        "slug": "hotel-royal-continental-napoli",
        "name": "Hotel Royal Continental Napoli",
        "email": "info@royalcontinental.it",
        "category": "Hotel 4 stelle",
        "city": "Napoli",
        "website": "https://www.royalcontinental.it",
        "hero_img": CAMPANIA_IMAGES["napoli3"],
        "about_img": CAMPANIA_IMAGES["napoli2"],
        "tagline": "397 camere sul Lungomare di Napoli · Via Partenope · Gruppo Royal · Vista Golfo",
        "description": "L'Hotel Royal Continental è un grande 4 stelle con 397 camere situato sul Lungomare di Napoli, Via Partenope. Parte del prestigioso Gruppo Royal, è il luogo ideale per visitare Napoli con la comodità di una struttura grande e ben organizzata con vista diretto sul Golfo di Napoli e il Vesuvio.",
        "palette": {"primary": "#1e3a5f", "secondary": "#0e9aa7", "accent": "#c9a84c", "light": "#ecf3f9", "dark": "#0d2035"},
        "stats": [
            {"num": "397", "label": "Camere"},
            {"num": "4★", "label": "Stelle"},
            {"num": "Royal Group", "label": "Gestione"},
            {"num": "Lungomare", "label": "Vista Golfo"},
        ],
        "services": [
            {"title": "Camere Vista Golfo", "desc": "397 camere confortevoli con vista sul Lungomare, Golfo e Vesuvio", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Ristoranti Multipli", "desc": "Diversi spazi ristorazione con cucina napoletana, internazionale e bar", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Piscina", "desc": "Area piscina riscaldata con solarium e veduta sulla città", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Business Center", "desc": "Servizi business per riunioni, conferenze e eventi aziendali", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Posizione Lungomare", "desc": "Direttamente sul lungomare con accesso a piedi a Castel dell'Ovo e Santa Lucia", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Tour Napoli", "desc": "Concierge per tour della città, Capri, Vesuvio, Pompei e Costiera", "img": CAMPANIA_IMAGES["capri2"]},
        ],
        "highlights": ["397 camere Lungomare", "Gruppo Royal - qualità garantita", "Via Partenope vista Golfo", "Centro Napoli", "Posizione strategica turistica"],
        "cta": "Prenota il Tuo Soggiorno a Napoli",
        "chatbot_msg": "Ciao! Sono l'assistente dell'Hotel Royal Continental. Posso aiutarvi con disponibilità, informazioni su Napoli e organizzazione di escursioni sul Vesuvio e Costiera.",
    },
    {
        "slug": "torres-travel-pompei",
        "name": "Torres Travel Pompei",
        "email": "info@torrestravel.it",
        "category": "Tour operator incoming Pompei Guide Center",
        "city": "Pompei",
        "website": "https://www.torrestravel.it",
        "hero_img": "https://torrestravel.it/wp-content/uploads/2020/01/pompeii-03.jpg",
        "about_img": "https://torrestravel.it/wp-content/uploads/2020/01/home-slide-02-768x388.jpg",
        "tagline": "Pompei Guide Center · Specialista crocieristi · 11+ lingue · Shore excursions",
        "description": "Torres Travel è un tour operator incoming e Pompei Guide Center specializzato in escursioni per crocieristi e gruppi. Con guide multilingue in 11+ lingue e skip-the-line verso gli scavi, offre un'esperienza autentica di Pompei, Vesuvio e Golfo di Napoli dal 2001.",
        "palette": {"primary": "#1a5c8a", "secondary": "#8b4513", "accent": "#d4a574", "light": "#ecf5ff", "dark": "#0d3557"},
        "stats": [
            {"num": "2001", "label": "Fondazione"},
            {"num": "11+", "label": "Lingue"},
            {"num": "🎫", "label": "Skip-the-Line"},
            {"num": "Pompei GC", "label": "Guide Center"},
        ],
        "services": [
            {"title": "Tour Pompei", "desc": "Escursioni guidate agli scavi di Pompei con accesso skip-the-line e guide esperte", "img": "https://torrestravel.it/wp-content/uploads/2020/01/pompeii-03.jpg"},
            {"title": "Shore Excursions", "desc": "Escursioni da crociera specializzate con ritorno garantito a bordo", "img": "https://torrestravel.it/wp-content/uploads/2020/01/home-slide-02-768x388.jpg"},
            {"title": "Vesuvio & Costiera", "desc": "Tour del Vesuvio, Costiera Amalfitana, Capri e isole", "img": CAMPANIA_IMAGES["vesuvio"]},
            {"title": "Guide Multilingue", "desc": "Guide professioniste in 11+ lingue per tour personalizzati", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Transfer & Logistica", "desc": "Transfer da porti e aeroporti, organizzazione completa gruppi", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Food & Wine Tour", "desc": "Cooking class, degustazioni pizzeria e vini vulcanici", "img": CAMPANIA_IMAGES["pizza1"]},
        ],
        "highlights": ["Pompei Guide Center esclusivo", "11+ lingue professionali", "Skip-the-line guaranteed", "Specialista crocieristi", "Dal 2001 - esperienza consolidata"],
        "cta": "Prenota la Tua Escursione",
        "chatbot_msg": "Ciao! Sono l'assistente di Torres Travel. Posso aiutarvi con escursioni Pompei, shore excursions da crociere, tour Vesuvio e Costiera con guide in tutte le lingue.",
    },
    {
        "slug": "grand-hotel-aminta-sorrento",
        "name": "Grand Hotel Aminta Sorrento",
        "email": "info@aminta.it",
        "category": "Hotel 4 stelle",
        "city": "Sorrento",
        "website": "https://www.aminta.com",
        "hero_img": CAMPANIA_IMAGES["napoli1"],
        "about_img": "https://www.aminta.com/images/1405-senza-titolo-13.png?v=fbfc",
        "tagline": "Sorrento collina panoramica · Vista Golfo di Napoli · Piscine · Accogliente familiare",
        "description": "Il Grand Hotel Aminta è un 4 stelle accogliente e familiare situato sulla collina di Sorrento con vista panoramica sul Golfo di Napoli. Con piscine, navetta per il centro e un'atmosfera calorosa, è perfetto per coppie e famiglie che desiderano il comfort senza fronzoli.",
        "palette": {"primary": "#1a3a6e", "secondary": "#8b8b00", "accent": "#d4a574", "light": "#f0f5fc", "dark": "#0d2040"},
        "stats": [
            {"num": "4★", "label": "Stelle"},
            {"num": "Collina Sorrento", "label": "Posizione"},
            {"num": "🏊", "label": "Piscine"},
            {"num": "Navetta", "label": "Centro Gratuita"},
        ],
        "services": [
            {"title": "Camere Confortevoli", "desc": "Camere accoglienti con vista sul golfo, aria condizionata e comfort moderno", "img": "https://www.aminta.com/images/1405-senza-titolo-13.png?v=fbfc"},
            {"title": "Piscine Panoramiche", "desc": "Aree piscina con solarium vista golfo e bar", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Ristorante", "desc": "Cucina tipica sorrentina con ingredienti locali e servizio familiare", "img": CAMPANIA_IMAGES["pizza2"]},
            {"title": "Navetta Centro", "desc": "Navetta gratuita per Piazza Tasso e centro storico di Sorrento", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Sale Ampia", "desc": "Ampia sala comune, sala colazione e spazi comuni accoglienti", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Esperienze Famiglia", "desc": "Attività per famiglie, tour a Capri, Pompei e animazione serale", "img": CAMPANIA_IMAGES["capri2"]},
        ],
        "highlights": ["Collina panoramica Sorrento", "Vista Golfo di Napoli", "Piscine con solarium", "Navetta centro gratuita", "Atmosfera calorosa familiare"],
        "cta": "Prenota Ora",
        "chatbot_msg": "Ciao! Sono l'assistente del Grand Hotel Aminta. Posso aiutarvi con disponibilità, informazioni sulle piscine, organizzazione della navetta e tour per famiglie.",
    },
    {
        "slug": "hotel-lorelei-et-londres",
        "name": "Hotel Lorelei et Londres",
        "email": "info@loreleisorrento.com",
        "category": "Hotel 5 stelle boutique",
        "city": "Sorrento",
        "website": "https://www.loreleisorrento.com",
        "hero_img": "https://www.loreleisorrento.com/wp-content/themes/lorelei/images/home/roomVertical.jpg",
        "about_img": "https://www.loreleisorrento.com/wp-content/themes/lorelei/images/home/roomsLong.jpg",
        "tagline": "5 stelle boutique fronte mare · Ristorante 1 stella Michelin · Ristrutturato 2019",
        "description": "L'Hotel Lorelei et Londres è un elegante 5 stelle boutique affacciato sul mare di Sorrento, completamente ristrutturato nel 2019. Con il suo esclusivo ristorante 1 stella Michelin, piscina panoramica e design contemporaneo, rappresenta il massimo della raffinatezza nel segmento luxury della Penisola Sorrentina.",
        "palette": {"primary": "#4a6fa5", "secondary": "#c9a84c", "accent": "#c9a84c", "light": "#f0f5ff", "dark": "#2a3f5c"},
        "stats": [
            {"num": "5★", "label": "Boutique Lusso"},
            {"num": "1★", "label": "Michelin Ristorante"},
            {"num": "2019", "label": "Ristrutturato"},
            {"num": "Fronte Mare", "label": "Vista Golfo"},
        ],
        "services": [
            {"title": "Camere Eleganti", "desc": "Suite raffinate con vista mare, design contemporaneo e comfort supremo", "img": "https://www.loreleisorrento.com/wp-content/themes/lorelei/images/home/roomVertical.jpg"},
            {"title": "Ristorante Stellato", "desc": "Cucina gastronomica 1 stella Michelin con vista sul golfo e terrazz", "img": "https://www.loreleisorrento.com/wp-content/themes/lorelei/images/home/diningDish.png"},
            {"title": "Piscina Panoramica", "desc": "Piscina infinity sul mare con solarium e veduta spettacolare", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Terrazza Vista Golfo", "desc": "Ampia terrazza per aperitivi al tramonto con vista mare mozzafiato", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Spa & Wellness", "desc": "Centro benessere con trattamenti luxury e spa privata", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Esperienze Luxury", "desc": "Concierge esclusivo per yacht, elicottero e tour privati", "img": CAMPANIA_IMAGES["capri1"]},
        ],
        "highlights": ["5 stelle boutique fronte mare", "Ristorante 1 stella Michelin", "Ristrutturato 2019 design moderno", "Piscina infinity sul mare", "Clientela VIP internazionale"],
        "cta": "Prenota la Tua Esperienza Gastronomica",
        "chatbot_msg": "Benvenuti al Lorelei et Londres. Sono il vostro concierge digitale. Posso assistere con prenotazioni suite, tavoli al ristorante stellato e organizzazione di esperienze esclusive.",
    },
    {
        "slug": "grand-hotel-ambasciatori",
        "name": "Grand Hotel Ambasciatori Sorrento",
        "email": "ambasciatori@manniellohotels.com",
        "category": "Hotel 5 stelle (Manniello Hotels)",
        "city": "Sorrento",
        "website": "https://www.ambasciatorisorrento.com",
        "hero_img": "https://www.ambasciatorisorrento.com/wp-content/uploads/sites/344/2024/07/Aperitivo-Mobile-450x900.jpg",
        "about_img": "https://www.ambasciatorisorrento.com/wp-content/uploads/sites/344/2024/07/Aperitivo-Mobile-300x150.jpg",
        "tagline": "5 stelle a picco sul mare · Beach club esclusivo · Vista Vesuvio · Manniello Hotels",
        "description": "Il Grand Hotel Ambasciatori è un 5 stelle del Gruppo Manniello ubicato a picco sul mare di Sorrento con vista diretta sul Golfo di Napoli e il Vesuvio. Con il suo esclusivo beach club, terrazza panoramica e una posizione straordinaria, offre un'esperienza di lusso senza compromessi.",
        "palette": {"primary": "#0d2b45", "secondary": "#c9a84c", "accent": "#c9a84c", "light": "#ecf3f9", "dark": "#061829"},
        "stats": [
            {"num": "5★", "label": "Lusso"},
            {"num": "Picco Mare", "label": "Posizione Unica"},
            {"num": "Beach Club", "label": "Esclusivo"},
            {"num": "Manniello", "label": "Gruppo Alberghiero"},
        ],
        "services": [
            {"title": "Camere Vista Mare", "desc": "Suite panoramiche a picco sul golfo con vista Vesuvio e balcone privato", "img": "https://www.ambasciatorisorrento.com/wp-content/uploads/sites/344/2024/07/Aperitivo-Mobile-450x900.jpg"},
            {"title": "Beach Club", "desc": "Accesso esclusivo al beach club con lettini, ombrelloni e servizio spiaggia", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Terrazza Scogliera", "desc": "Terrazza spettacolare a picco sul mare per cena, aperitivi e events", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Piscina Panoramica", "desc": "Piscina infinity sul mare con veduta a 180° sul golfo", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Ristorante", "desc": "Cucina mediterranea d'autore con vista mare mozzafiato", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Esperienze Luxury", "desc": "Yacht privati, transfer VIP e tour esclusivi della Costiera", "img": CAMPANIA_IMAGES["italy"]},
        ],
        "highlights": ["5 stelle Manniello Hotels", "Posizione picco mare unica", "Beach club esclusivo", "Vista Vesuvio e Golfo", "Terrazza scogliera spettacolare"],
        "cta": "Prenota la Tua Vista Spettacolare",
        "chatbot_msg": "Benvenuti al Grand Hotel Ambasciatori. Sono il vostro concierge digitale. Posso assistervi con prenotazioni suite, accesso beach club e organizzazione di cene esclusive sulla terrazza.",
    },
    {
        "slug": "best-western-hotel-la-solara",
        "name": "Best Western Hotel La Solara",
        "email": "info@lasolara.com",
        "category": "Hotel 4 stelle (catena BW)",
        "city": "Sorrento",
        "website": "https://www.lasolara.com",
        "hero_img": "https://www.lasolara.com/wp-content/uploads/sites/123/2025/01/HOTEL-VIEW-768x512.webp",
        "about_img": "https://www.lasolara.com/wp-content/uploads/sites/123/2024/07/BW-core-w-768x281.png",
        "tagline": "58 camere a Sorrento Capo · Piscina con solarium · Catena Best Western · Navetta centro",
        "description": "Best Western Hotel La Solara è un 4 stelle efficiente e moderno a Sorrento Capo, con 58 camere, piscina con solarium e navetta gratuita per il centro. Parte della rinomata catena Best Western, offre comfort, praticità e un ottimo rapporto qualità-prezzo per famiglie e coppie.",
        "palette": {"primary": "#1a3a6e", "secondary": "#0e9aa7", "accent": "#d4a574", "light": "#ecf3f9", "dark": "#0d2040"},
        "stats": [
            {"num": "58", "label": "Camere"},
            {"num": "4★", "label": "Stelle"},
            {"num": "🏊", "label": "Piscina Solarium"},
            {"num": "Best Western", "label": "Catena Globale"},
        ],
        "services": [
            {"title": "Camere Moderne", "desc": "58 camere confortevoli e ben arredate con WiFi, air condizionato e vista città", "img": "https://www.lasolara.com/wp-content/uploads/sites/123/2025/01/HOTEL-VIEW-768x512.webp"},
            {"title": "Piscina Solarium", "desc": "Piscina riscaldata con ampia area solarium, bar e lettini", "img": "https://www.lasolara.com/wp-content/uploads/sites/123/2025/01/Lounge-Bar10-768x512.webp"},
            {"title": "Ristorante", "desc": "Cucina italiana e internazionale con buffet colazione e bar aperto", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Navetta Centro", "desc": "Navetta gratuita per raggiungere Piazza Tasso e il centro storico", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Parcheggio", "desc": "Parcheggio interno gratuito, pratico per chi arriva in auto", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Tour Organizati", "desc": "Desk per organizzazione escursioni Capri, Costiera, Vesuvio con partner locali", "img": CAMPANIA_IMAGES["capri2"]},
        ],
        "highlights": ["58 camere comode e moderne", "Piscina con solarium riscaldato", "Navetta centro gratuita", "Catena Best Western - standard globale", "Sorrento Capo - posizione pratica"],
        "cta": "Prenota Ora",
        "chatbot_msg": "Ciao! Sono l'assistente del Best Western La Solara. Posso aiutarvi con disponibilità, informazioni sui servizi e organizzazione della navetta o escursioni.",
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
        <img src="{company['about_img']}" alt="{company['name']}">
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

    print(f"\n✅ {len(created)} demo Ciclo 2 create!")

if __name__ == '__main__':
    main()
