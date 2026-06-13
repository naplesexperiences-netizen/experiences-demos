#!/usr/bin/env python3
"""
Generate 32 new-template demos from old demo data.
Creates folders named *-new-template-experiences and fills them using the new templates.
"""

import re, os, shutil

BASE = '/home/user/experiences-demos/demos'
HOTEL_TEMPLATE = os.path.join(BASE, 'demo-hotel-template-experiences', 'index.html')
TO_TEMPLATE = os.path.join(BASE, 'demo-tour-operator-template-experiences', 'index.html')

# Default campania images
CAMP = 'https://naplesexperiences-netizen.github.io/experiences-demos/demos/assets/campania-images/'
IMG = {
    'napoli1': CAMP + 'jorjoson-napoli-5337054_1920.jpg',
    'napoli2': CAMP + 'serpae-napoli-5711208_1920.jpg',
    'napoli3': CAMP + 'ornaw-naples-4808406_1920.jpg',
    'capri':   CAMP + 'ebell8810-capri-854775_1920.jpg',
    'vesuvio': CAMP + 'joakant-vesuvius-677714_1920.jpg',
    'pizza':   CAMP + 'martinquijandria-pizza-2530169_1920.jpg',
    'italy':   CAMP + 'yorick77-italy-6702554_1920.jpg',
    'pompei':  CAMP + 'yorick77-vesuvius-4635882_1920.jpg',
}

# ─── Company data ───────────────────────────────────────────────────────────
COMPANIES = [

# 1. Sirenide Viaggi (tour operator)
{
 'old_folder': 'sirenide-viaggi-demo-experiences',
 'new_folder': 'sirenide-viaggi-new-template-experiences',
 'type': 'tour',
 'name': 'Sirenide Viaggi',
 'email': 'info@sirenide.com',
 'url': 'https://www.sirenide.com',
 'category': 'Tour operator incoming / DMC',
 'tagline': 'Il vostro partner DMC di fiducia nel Sud Italia dal 1988',
 'description': 'Sirenide Viaggi è un DMC (Destination Management Company) con oltre 35 anni di esperienza nell\'incoming B2B verso il Sud Italia. Lavoriamo con tour operator e agenzie di viaggio in tutto il mondo per offrire esperienze su misura lungo la Penisola Sorrentina, Costiera Amalfitana, Capri, Ischia, Napoli e Roma.',
 'primary': '#1e3a5f',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.sirenide.com/images/scaled/sirenide-viaggi_00073.webp',
 'about_img': 'https://www.sirenide.com/images/scaled/000_positano.webp',
 'stats': [('35+','Anni di Esperienza'),('5','Lingue Parlate'),('200+','Agenzie Partner'),('15.000+','Ospiti Annuali')],
 'highlights': ['Copertura completa Sud Italia','Fleet bus lusso Euro 6','Guida in 5 lingue (IT/EN/DE/FR/ES)','Area agenzie riservata online','Rete di hotel propri a Sorrento'],
 'services': [
   ('https://www.sirenide.com/images/scaled/000_positano.webp','Tour Guidati Costiera','Escursioni personalizzate lungo la Costiera Amalfitana con guide certificate in 5 lingue'),
   ('https://www.sirenide.com/images/scaled/AdobeStock_259147781.webp','Hotel & Accommodation','Selezione esclusiva di hotel 3-5 stelle con tariffe riservate e allocazioni garantite'),
   (IMG['napoli1'],'Transfer & Trasporti','Bus di lusso, minivan e transfer privati dall\'aeroporto e dai porti turistici'),
   (IMG['capri'],'Escursioni a Capri','Boat tour, grotta azzurra e tour dell\'isola con barche private e guide esperte'),
   (IMG['pizza'],'Food Experience','Cooking class, degustazioni e cene gourmet nei migliori ristoranti del territorio'),
   (IMG['vesuvio'],'Vesuvio & Pompei','Visite guidate agli scavi di Pompei ed escursioni al Vesuvio con trasporto incluso'),
 ],
 'chatbot_greeting': 'Ciao! Sono l\'assistente AI di Sirenide Viaggi. Posso fornirti informazioni su itinerari, disponibilità hotel, transfer e molto altro. Come posso aiutarti?',
},

# 2. Grand Hotel Excelsior Vittoria (hotel 5★)
{
 'old_folder': 'grand-hotel-excelsior-vittoria-demo-experiences',
 'new_folder': 'grand-hotel-excelsior-vittoria-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel Excelsior Vittoria',
 'email': 'fb@exvitt.it',
 'url': 'https://excelsiorvittoria.com',
 'category': 'Hotel 5 stelle Luxury (LHW)',
 'tagline': 'Dal 1834: un\'icona di lusso sospesa tra storia e Golfo di Napoli',
 'description': 'Il Grand Hotel Excelsior Vittoria è una dimora storica affacciata sul Golfo di Napoli a Piazza Tasso, Sorrento. Dal 1834 ospita personalità illustri come Richard Wagner, Oscar Wilde e Enrico Caruso. Membro dei Leading Hotels of the World, offre 79 camere con vista mare, il ristorante stellato Michelin Terrazza Bosquet e un parco-agrumeto privato.',
 'primary': '#5c4a2a',
 'secondary': '#c9a84c',
 'hero_img': 'https://excelsiorvittoria.com/images-temp-/off-suit-rt-1.jpg',
 'about_img': 'https://excelsiorvittoria.com/images-temp-/stellat-offer-sq--.jpg',
 'stats': [('1834','Anno di Fondazione'),('79','Camere Vista Mare'),('1★','Michelin Terrazza Bosquet'),('LHW','Leading Hotels of the World')],
 'highlights': ['Dimora storica dal 1834','Leading Hotels of the World','Ristorante Michelin stellato','Parco agrumeto privato','Wagner, Wilde, Caruso soggiornarono qui'],
 'services': [
   ('https://excelsiorvittoria.com/images-temp-/off-suit-rt-1.jpg','Suite Panoramiche','79 camere e suite con vista mozzafiato sul Golfo di Napoli, arredate con antichi mobili d\'epoca'),
   ('https://excelsiorvittoria.com/images-temp-/stellat-offer-sq--.jpg','Terrazza Bosquet ⭐','Ristorante stellato Michelin con cucina mediterranea d\'autore e panorama sul golfo'),
   (IMG['napoli3'],'Parco & Agrumeto','Lussureggiante parco privato con alberi di limoni, aranci e bouganville centenari'),
   (IMG['pompei'],'Spa & Benessere','Trattamenti esclusivi con prodotti agli agrumi della Penisola Sorrentina'),
   (IMG['capri'],'Escursioni Luxury','Capri, Pompei, Costiera Amalfitana con yacht privato e transfer esclusivi'),
   (IMG['napoli1'],'Ascensore al Mare','Accesso diretto al porto di Sorrento con ascensore privato storico'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Excelsior Vittoria. Sono il vostro concierge digitale. Posso assistervi per prenotazioni, disponibilità suite e organizzazione di esperienze esclusive.',
},

# 3. Hotel Mediterraneo Sorrento (hotel 5★)
{
 'old_folder': 'hotel-mediterraneo-sorrento-demo-experiences',
 'new_folder': 'hotel-mediterraneo-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Mediterraneo Sorrento',
 'email': 'info@mediterraneosorrento.com',
 'url': 'https://www.mediterraneosorrento.com',
 'category': 'Hotel 5 stelle',
 'tagline': 'Michelin Key 2025 · A picco sul mare con ascensore privato alla spiaggia',
 'description': 'L\'Hotel Mediterraneo Sorrento è un elegante 5 stelle affacciato a picco sul mare con accesso diretto alla spiaggia tramite ascensore privato. Premiato con la Michelin Key 2025, offre camere panoramiche, ristorante gourmet e una posizione privilegiata nel cuore della Penisola Sorrentina.',
 'primary': '#1a4a6e',
 'secondary': '#c9a84c',
 'hero_img': IMG['italy'],
 'about_img': IMG['napoli2'],
 'stats': [('5★','Stelle'),('2025','Michelin Key'),('Lift','Ascensore Mare'),('Centro','Sorrento')],
 'highlights': ['Michelin Key 2025','Ascensore privato alla spiaggia','Vista mare da ogni camera','Ristorante gourmet con terrazza','Posizione centrale a Sorrento'],
 'services': [
   (IMG['italy'],'Camere Vista Mare','Camere eleganti con balcone e vista panoramica sul Golfo di Napoli'),
   (IMG['napoli2'],'Spiaggia Privata','Accesso esclusivo alla spiaggia tramite ascensore privato'),
   (IMG['pizza'],'Ristorante Gourmet','Cucina mediterranea d\'eccellenza con prodotti freschi locali e terrazza panoramica'),
   (IMG['capri'],'Escursioni','Tour guidati a Capri, Costiera Amalfitana e Pompei'),
   (IMG['vesuvio'],'Wellness & Relax','Zona benessere con trattamenti esclusivi e piscina panoramica'),
   (IMG['napoli1'],'Concierge Premium','Servizio concierge dedicato per organizzare soggiorni su misura'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Mediterraneo Sorrento! Sono qui per aiutarvi con prenotazioni, informazioni sulle camere e organizzazione di escursioni. Come posso assistervi?',
},

# 4. Grand Hotel Capodimonte Sorrento (hotel 4★)
{
 'old_folder': 'grand-hotel-capodimonte-sorrento-demo-experiences',
 'new_folder': 'grand-hotel-capodimonte-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel Capodimonte',
 'email': 'capodimonte@manniellohotels.com',
 'url': 'https://www.capodimontesorrento.com',
 'category': 'Hotel 4 stelle Superior',
 'tagline': '5 piscine a cascata con vista golfo · Resort mediterraneo classico nel cuore di Sorrento',
 'description': 'Il Grand Hotel Capodimonte è un resort 4 stelle superior del gruppo Manniello Hotels immerso in un giardino mediterraneo di 3 ettari. Le sue 5 piscine a cascata con vista sul Golfo di Napoli lo rendono uno dei complessi più suggestivi della Penisola Sorrentina.',
 'primary': '#1b3d6e',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.capodimontesorrento.com/wp-content/uploads/sites/346/2024/05/Piscina-con-vistaSito-450x900.jpg',
 'about_img': 'https://www.capodimontesorrento.com/wp-content/uploads/sites/346/2024/05/Piscina-con-vistaSito-300x150.jpg',
 'stats': [('5','Piscine a Cascata'),('3 ha','Giardino Mediterraneo'),('4★','Superior'),('Manniello','Hotels Group')],
 'highlights': ['5 piscine panoramiche a cascata','Giardino mediterraneo di 3 ettari','Gruppo Manniello Hotels','Centro benessere e spa','Vista diretta sul Golfo di Napoli'],
 'services': [
   ('https://www.capodimontesorrento.com/wp-content/uploads/sites/346/2024/05/Piscina-con-vistaSito-450x900.jpg','Piscine Panoramiche','5 piscine a cascata con vista mozzafiato sul Golfo di Napoli'),
   (IMG['capri'],'Giardino Mediterraneo','3 ettari di giardino rigoglioso con piante mediterranee e sentieri privati'),
   (IMG['pizza'],'Ristorante & Bar','Cucina sorrentina autentica con ingredienti a km zero e terrazza panoramica'),
   (IMG['vesuvio'],'Spa & Wellness','Trattamenti esclusivi con prodotti agli agrumi e piscine termali'),
   (IMG['napoli1'],'Camere Superior','Camere eleganti con vista giardino o golfo, dotate di ogni comfort moderno'),
   (IMG['napoli2'],'Escursioni Sorrentine','Tour organizzati a Capri, Positano e Pompei con guide certificate'),
 ],
 'chatbot_greeting': 'Benvenuto al Grand Hotel Capodimonte! Posso aiutarvi a scegliere la camera ideale, verificare disponibilità e organizzare la vostra vacanza sorrentina.',
},

# 5. Grand Hotel Royal Sorrento (hotel 5★)
{
 'old_folder': 'grand-hotel-royal-sorrento-demo-experiences',
 'new_folder': 'grand-hotel-royal-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel Royal Sorrento',
 'email': 'royal@manniellohotels.com',
 'url': 'https://www.royalsorrento.com',
 'category': 'Hotel 5 stelle',
 'tagline': 'Lusso fronte mare in Via Correale · Spiaggia privata · 3 ristoranti',
 'description': 'Il Grand Hotel Royal Sorrento è un 5 stelle lusso del gruppo Manniello Hotels situato direttamente sul lungomare di Sorrento. Con spiaggia privata, 3 ristoranti e piscina panoramica affacciata sul Golfo di Napoli, è una delle destinazioni più esclusive della Penisola Sorrentina.',
 'primary': '#0d2b45',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.royalsorrento.com/wp-content/uploads/sites/343/2023/03/Terrazza-Home-Page-450x900.jpeg',
 'about_img': 'https://www.royalsorrento.com/wp-content/uploads/sites/343/2023/03/Terrazza-Home-Page-300x150.jpeg',
 'stats': [('5★','Stelle Lusso'),('Fronte','Mare'),('3','Ristoranti'),('Manniello','Hotels Group')],
 'highlights': ['Posizione fronte mare diretta','Spiaggia privata esclusiva','3 ristoranti panoramici','Piscina vista golfo','Gruppo Manniello Hotels'],
 'services': [
   ('https://www.royalsorrento.com/wp-content/uploads/sites/343/2023/03/Terrazza-Home-Page-450x900.jpeg','Suite Fronte Mare','Suite lussuose con terrazza e vista diretta sul Golfo di Napoli'),
   (IMG['capri'],'Spiaggia Privata','Spiaggia esclusiva con lettini, ombrelloni e servizio bar sull\'acqua'),
   (IMG['pizza'],'3 Ristoranti','Tre ristoranti con cucine diverse: gourmet, tradizionale e pool bar'),
   (IMG['vesuvio'],'Spa Reale','Centro benessere con trattamenti luxury e piscina idromassaggio panoramica'),
   (IMG['napoli2'],'Escursioni Premium','Capri, Positano, Pompei con transfer privati e guide certificate'),
   (IMG['napoli1'],'Concierge 5★','Servizio concierge d\'eccellenza disponibile 24/7 per ogni esigenza'),
 ],
 'chatbot_greeting': 'Benvenuto al Grand Hotel Royal Sorrento. Sono il vostro assistente personale. Come posso aiutarvi con la prenotazione o le attività della vostra vacanza?',
},

# 6. Grand Hotel Cocumella (hotel 5★)
{
 'old_folder': 'grand-hotel-cocumella-demo-experiences',
 'new_folder': 'grand-hotel-cocumella-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel Cocumella',
 'email': 'info@cocumella.com',
 'url': 'https://www.cocumella.com',
 'category': 'Hotel 5 stelle (Small Luxury Hotels)',
 'tagline': 'Dimora storica dal 1637 · Small Luxury Hotels · Veliero d\'epoca per escursioni',
 'description': 'Il Grand Hotel Cocumella è una delle dimore storiche più esclusive del Mediterraneo, ricavata in un antico monastero del 1637 a Sant\'Agnello. Membro dei Small Luxury Hotels of the World, offre un\'atmosfera unica con affreschi originali, giardini botanici e il leggendario veliero Cocumella per escursioni in mare.',
 'primary': '#4a3728',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.cocumella.com/template/cocumella/images/bg/home_intro_bg.png',
 'about_img': 'https://www.cocumella.com/template/cocumella/images/bg/home_intro_bg.png',
 'stats': [('1637','Anno di Fondazione'),('SLH','Small Luxury Hotels'),('Veliero','d\'Epoca'),('UNESCO','Area Protetta')],
 'highlights': ['Monastero storico del 1637','Small Luxury Hotels of the World','Veliero d\'epoca privato','Affreschi e opere d\'arte originali','Giardino botanico secolare'],
 'services': [
   ('https://www.cocumella.com/template/cocumella/images/bg/home_intro_bg.png','Suite Storiche','Camere ricavate nelle celle del monastero, con affreschi originali e mobili d\'antiquariato'),
   (IMG['capri'],'Veliero Cocumella','Escursioni esclusive in mare a bordo del leggendario veliero d\'epoca'),
   (IMG['pizza'],'Ristorante Il Chiostro','Cucina gourmet nel suggestivo chiostro del monastero, con agrumi del giardino'),
   (IMG['vesuvio'],'Giardino Botanico','Passeggiata tra piante centenarie, limoneti e terrazze panoramiche sul golfo'),
   (IMG['napoli1'],'Spa Monastero','Trattamenti benessere ispirati alle tradizioni monastiche e ai prodotti locali'),
   (IMG['napoli3'],'Esperienze Culturali','Visite private agli affreschi, serate musicali e degustazioni di vini campani'),
 ],
 'chatbot_greeting': 'Benvenuti al Grand Hotel Cocumella. Sono il vostro assistente personale. Posso aiutarvi a scoprire la storia secolare di questa dimora e pianificare un soggiorno indimenticabile.',
},

# 7. Golden Tours International (tour operator)
{
 'old_folder': 'golden-tours-international-demo-experiences',
 'new_folder': 'golden-tours-international-new-template-experiences',
 'type': 'tour',
 'name': 'Golden Tours International',
 'email': 'incoming@goldentours.it',
 'url': 'https://www.goldentours.it',
 'category': 'Tour operator / DMC incoming',
 'tagline': 'DMC di eccellenza dal 1957 · Servizi luxury per gruppi e individuali nel Sud Italia',
 'description': 'Golden Tours International è un DMC con oltre 65 anni di esperienza nell\'incoming turistico verso il Sud Italia. Specializzato in servizi luxury per gruppi e viaggiatori individuali, gestisce transfer, escursioni, accommodation e MICE dalla Penisola Sorrentina a tutta la Campania.',
 'primary': '#1a3a5c',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.goldentours.it/images/risorsa-1_823.png?v=a43a',
 'about_img': 'https://www.goldentours.it/images/risorsa-6_197.png?v=fcb9',
 'stats': [('65+','Anni di Esperienza'),('1957','Anno di Fondazione'),('Luxury','Servizi DMC'),('MICE','Specialist')],
 'highlights': ['DMC fondato nel 1957','Servizi luxury per gruppi e FIT','Specializzato in MICE e incentive','Copertura completa Campania','Flotta di veicoli di lusso propria'],
 'services': [
   ('https://www.goldentours.it/images/risorsa-1_823.png?v=a43a','Tour Luxury Costiera','Escursioni esclusive sulla Costiera Amalfitana con guida privata e transfer di lusso'),
   ('https://www.goldentours.it/images/risorsa-6_197.png?v=fcb9','MICE & Incentive','Organizzazione di eventi aziendali, conferenze e viaggi incentive su misura'),
   (IMG['capri'],'Capri Esclusiva','Tour privati a Capri con yacht charter e accesso a location riservate'),
   (IMG['pizza'],'Food & Wine Tours','Esperienze gastronomiche: cantine, ristoranti stellati e mercati locali'),
   (IMG['vesuvio'],'Pompei & Vesuvio','Visite guidate in italiano, inglese, tedesco, francese e spagnolo'),
   (IMG['napoli2'],'Transfer Aeroporto','Transfer luxury da/per aeroporto di Napoli con veicoli di rappresentanza'),
 ],
 'chatbot_greeting': 'Benvenuto in Golden Tours International! Dal 1957 organizziamo esperienze indimenticabili nel Sud Italia. Come posso aiutarvi a pianificare il vostro itinerario?',
},

# 8. Grand Hotel Cesare Augusto (hotel 4★)
{
 'old_folder': 'grand-hotel-cesare-augusto-demo-experiences',
 'new_folder': 'grand-hotel-cesare-augusto-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel Cesare Augusto',
 'email': 'info@hotelcesareaugusto.com',
 'url': 'https://www.hotelcesareaugusto.com',
 'category': 'Hotel 4 stelle',
 'tagline': '120 camere nel cuore di Sorrento · Roof Garden con piscina · Ideale per congressi',
 'description': 'Il Grand Hotel Cesare Augusto è un elegante 4 stelle nel centro di Sorrento con 120 camere, roof garden con piscina panoramica e ampi spazi congressuali. La sua posizione strategica a pochi passi da Piazza Tasso lo rende la scelta ideale sia per viaggiatori leisure che business.',
 'primary': '#1e3a5f',
 'secondary': '#c9a84c',
 'hero_img': IMG['napoli1'],
 'about_img': IMG['italy'],
 'stats': [('120','Camere'),('Roof','Garden Piscina'),('Centro','Sorrento'),('Congress','Centro')],
 'highlights': ['120 camere eleganti con vista','Roof garden con piscina panoramica','Centro congressi attrezzato','A 200m da Piazza Tasso','Ristorante panoramico'],
 'services': [
   (IMG['napoli1'],'Camere Vista Mare','120 camere eleganti con balcone e vista panoramica sul Golfo di Napoli'),
   (IMG['italy'],'Roof Garden','Piscina panoramica sul tetto con bar e terrazza vista mare mozzafiato'),
   (IMG['pizza'],'Ristorante Augusto','Cucina sorrentina autentica con prodotti locali freschi e carta vini selezionata'),
   (IMG['vesuvio'],'Centro Congressi','Sale meeting modulari fino a 500 persone con tecnologia audiovisiva avanzata'),
   (IMG['capri'],'Escursioni Guidate','Tour organizzati a Capri, Costiera Amalfitana, Pompei con guide certificate'),
   (IMG['napoli2'],'Spa & Fitness','Centro benessere con piscina coperta, palestra e trattamenti personalizzati'),
 ],
 'chatbot_greeting': 'Benvenuto al Grand Hotel Cesare Augusto! Sono il vostro assistente. Posso aiutarvi con prenotazioni camere, organizzazione eventi o informazioni sulle escursioni locali.',
},

# 9. Hotel Lorelei et Londres (hotel 5★)
{
 'old_folder': 'hotel-lorelei-et-londres-demo-experiences',
 'new_folder': 'hotel-lorelei-et-londres-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Lorelei et Londres',
 'email': 'info@loreleisorrento.com',
 'url': 'https://www.loreleisorrento.com',
 'category': 'Hotel 5 stelle boutique',
 'tagline': '5 stelle boutique fronte mare · Ristorante 1 stella Michelin · Ristrutturato 2024',
 'description': 'L\'Hotel Lorelei et Londres è un elegante 5 stelle boutique direttamente sul lungomare di Sorrento, completamente ristrutturato nel 2024. Il suo ristorante ha ottenuto 1 stella Michelin ed offre una cucina creativa con ingredienti locali e vista panoramica sul Golfo di Napoli.',
 'primary': '#4a6fa5',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.loreleisorrento.com/wp-content/themes/lorelei/images/home/roomVertical.jpg',
 'about_img': 'https://www.loreleisorrento.com/wp-content/themes/lorelei/images/home/roomsLong.jpg',
 'stats': [('5★','Boutique Hotel'),('1★','Michelin'),('2024','Ristrutturato'),('Fronte','Mare')],
 'highlights': ['Hotel boutique fronte mare','1 stella Michelin al ristorante','Ristrutturato completamente nel 2024','Camere con vista mare garantita','Terrazza panoramica privata'],
 'services': [
   ('https://www.loreleisorrento.com/wp-content/themes/lorelei/images/home/roomVertical.jpg','Camere Boutique','Camere di design con vista diretta sul mare, arredate con gusto contemporaneo'),
   ('https://www.loreleisorrento.com/wp-content/themes/lorelei/images/home/roomsLong.jpg','Ristorante ⭐ Michelin','Cucina creativa stellata con prodotti del territorio e cantina wines eccellente'),
   (IMG['napoli3'],'Terrazza Panoramica','Colazioni e aperitivi in terrazza con vista spettacolare sul Golfo di Napoli'),
   (IMG['capri'],'Escursioni Private','Transfer e tour esclusivi a Capri, Positano e Pompei con guida personale'),
   (IMG['vesuvio'],'Spa Boutique','Trattamenti benessere personalizzati con prodotti naturali sorrentini'),
   (IMG['pizza'],'Servizio Concierge','Assistenza h24 per ogni richiesta: tavoli, biglietti, transfer e attività'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Lorelei et Londres! Il nostro concierge digitale è a vostra disposizione per prenotazioni, informazioni sul ristorante stellato e organizzazione di esperienze esclusive.',
},

# 10. Grand Hotel La Favorita (hotel 5★)
{
 'old_folder': 'grand-hotel-la-favorita-demo-experiences',
 'new_folder': 'grand-hotel-la-favorita-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel La Favorita',
 'email': 'info@hotellafavorita.com',
 'url': 'https://www.hotellafavorita.com',
 'category': 'Hotel 5 stelle',
 'tagline': '85 camere eleganti nel centro di Sorrento · Roof garden · Spa · Matrimoni',
 'description': 'Il Grand Hotel La Favorita è un raffinato 5 stelle nel centro storico di Sorrento con 85 camere, roof garden panoramico, spa completa e ampi spazi per matrimoni ed eventi. La sua posizione privilegiata e la qualità dei servizi lo rendono una scelta ideale per soggiorni di lusso e celebrazioni.',
 'primary': '#2d4a1e',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.hotellafavorita.com/wp-content/uploads/favorita_18-e1689673695861.jpg',
 'about_img': 'https://www.hotellafavorita.com/wp-content/uploads/ingresso.jpg',
 'stats': [('85','Camere Eleganti'),('5★','Stelle'),('Roof','Garden Panoramico'),('Wedding','Specialist')],
 'highlights': ['85 camere nel centro di Sorrento','Roof garden con vista golfo','Spa e centro benessere','Specializzato in matrimoni','Ristorante panoramico gourmet'],
 'services': [
   ('https://www.hotellafavorita.com/wp-content/uploads/favorita_18-e1689673695861.jpg','Camere Panoramiche','85 camere eleganti con balcone e vista sui giardini o sul Golfo di Napoli'),
   ('https://www.hotellafavorita.com/wp-content/uploads/ingresso.jpg','Roof Garden','Terrazza panoramica con piscina e bar per momenti indimenticabili'),
   (IMG['pizza'],'Ristorante La Favorita','Alta cucina sorrentina con ingredienti del territorio e cantina eccellente'),
   (IMG['vesuvio'],'Spa & Wellness','Centro benessere completo con sauna, trattamenti e massaggi personalizzati'),
   (IMG['capri'],'Matrimoni & Eventi','Location esclusiva per matrimoni, cerimonie e eventi aziendali di prestigio'),
   (IMG['napoli1'],'Escursioni','Tour a Capri, Costiera Amalfitana e siti UNESCO con guide locali certificate'),
 ],
 'chatbot_greeting': 'Benvenuto al Grand Hotel La Favorita! Posso aiutarvi con prenotazioni, informazioni sul nostro ristorante, spa o per organizzare il vostro matrimonio speciale.',
},

# 11. Hotel Continental Sorrento (hotel 4★)
{
 'old_folder': 'hotel-continental-sorrento-demo-experiences',
 'new_folder': 'hotel-continental-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Continental Sorrento',
 'email': 'info@continentalsorrento.com',
 'url': 'https://www.continentalsorrento.com',
 'category': 'Hotel 4 stelle',
 'tagline': 'Nel cuore di Sorrento vicino al porto · Terrazza panoramica · Piscina',
 'description': 'L\'Hotel Continental Sorrento è un classico 4 stelle nel cuore di Sorrento, a pochi passi dal porto e dalla piazza principale. Con terrazza panoramica, piscina con vista mare e camere eleganti, offre un soggiorno confortevole in una delle posizioni più comode della Penisola Sorrentina.',
 'primary': '#1a3a6e',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.continentalsorrento.com/gallery/gallery/hotel-08466UAXKJAYE4M2.jpg',
 'about_img': 'https://www.continentalsorrento.com/gallery/gallery/hotel-08466UAXKJAYE4M2.jpg',
 'stats': [('4★','Stelle'),('Centro','Sorrento'),('Piscina','Vista Mare'),('Porto','100m')],
 'highlights': ['Posizione centrale nel cuore di Sorrento','100 metri dal porto e dai traghetti','Terrazza panoramica con piscina','Camere con vista mare e città','Staff multilingue e accogliente'],
 'services': [
   ('https://www.continentalsorrento.com/gallery/gallery/hotel-08466UAXKJAYE4M2.jpg','Camere Confortevoli','Camere eleganti con balcone, alcune con vista diretta sul Golfo di Napoli'),
   (IMG['napoli3'],'Terrazza & Piscina','Piscina panoramica e terrazza sole con vista sul porto di Sorrento'),
   (IMG['pizza'],'Ristorante','Cucina italiana e sorrentina con prodotti locali freschi di stagione'),
   (IMG['capri'],'Escursioni','Organizzazione tour a Capri, Ischia, Positano con partner selezionati'),
   (IMG['vesuvio'],'Bar Panoramico','Bar con vista sul golfo, aperitivi e cocktail con prodotti locali'),
   (IMG['napoli1'],'Concierge Service','Assistenza personalizzata per transfer, prenotazioni e attività locali'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Continental Sorrento! Posso aiutarvi a trovare la camera ideale, verificare la disponibilità e organizzare le vostre escursioni dalla Penisola Sorrentina.',
},

# 12. Hotel Antiche Mura Sorrento (hotel 4★)
{
 'old_folder': 'hotel-antiche-mura-sorrento-demo-experiences',
 'new_folder': 'hotel-antiche-mura-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Antiche Mura Sorrento',
 'email': 'info@hotelantichemura.com',
 'url': 'https://www.hotelantichemura.com',
 'category': 'Hotel 4 stelle',
 'tagline': 'Nel cuore di Piazza Tasso · Piscina nel vallone dei mulini · Agrumeto privato',
 'description': 'L\'Hotel Antiche Mura Sorrento è uno straordinario 4 stelle situato direttamente a Piazza Tasso, il cuore pulsante di Sorrento. La sua caratteristica piscina scavata nel vallone dei mulini e l\'agrumeto privato lo rendono unico nel panorama alberghiero della Penisola Sorrentina.',
 'primary': '#8b6f47',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.hotelantichemura.com/assets/images/public/slider/screen/swimming08-1920x1080_1562165772.jpg',
 'about_img': 'https://www.hotelantichemura.com/assets/images/public/slider/screen/swimming08-1920x1080_1562165772.jpg',
 'stats': [('Piazza','Tasso'),('Piscina','nel Vallone'),('Agrumeto','Privato'),('4★','Stelle')],
 'highlights': ['Piscina nel vallone dei mulini','Direttamente su Piazza Tasso','Agrumeto privato di limoni','Camere con vista sulla vallata','A 2 min dal porto di Sorrento'],
 'services': [
   ('https://www.hotelantichemura.com/assets/images/public/slider/screen/swimming08-1920x1080_1562165772.jpg','Piscina nel Vallone','Piscina unica scavata nell\'antico vallone dei mulini, circondata da vegetazione lussureggiante'),
   (IMG['napoli3'],'Camere Panoramiche','Camere eleganti con vista sulla vallata dei mulini o sulla città di Sorrento'),
   (IMG['pizza'],'Ristorante & Agrumeto','Cucina sorrentina con limoni e agrumi del giardino privato dell\'hotel'),
   (IMG['capri'],'Bar Terrazza','Aperitivi e cocktail sulla terrazza con vista su Piazza Tasso'),
   (IMG['vesuvio'],'Escursioni','Tour a Capri, Costiera Amalfitana e Pompei con guide certificate'),
   (IMG['napoli1'],'Concierge','Servizio concierge dedicato per organizzare la vostra vacanza perfetta'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Antiche Mura! Sono il vostro assistente digitale. Posso aiutarvi con prenotazioni, informazioni sulla nostra piscina nel vallone e le escursioni locali.',
},

# 13. Grand Hotel La Pace (hotel 5★)
{
 'old_folder': 'grand-hotel-la-pace-demo-experiences',
 'new_folder': 'grand-hotel-la-pace-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel La Pace',
 'email': 'info@ghlapace.com',
 'url': 'https://www.ghlapace.com',
 'category': 'Hotel 5 stelle',
 'tagline': '5 stelle lusso a Sant\'Agnello · Spa e piscine panoramiche · Via Tordara',
 'description': 'Il Grand Hotel La Pace è un magnifico 5 stelle situato a Sant\'Agnello di Sorrento, in Via Tordara. Con ampi giardini mediterranei, spa completa e piscine panoramiche con vista sul Golfo di Napoli, offre un rifugio di lusso e tranquillità a pochi minuti dal centro di Sorrento.',
 'primary': '#1a3a6e',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.ghlapace.com/wp-content/uploads/2022/04/grand-hotel-la-pace-sorrento.jpg',
 'about_img': 'https://www.ghlapace.com/wp-content/uploads/2022/06/barlapace1.jpg',
 'stats': [('5★','Stelle Lusso'),('Giardini','Mediterranei'),('Spa','Completa'),('Sant\'Agnello','Sorrento')],
 'highlights': ['5 stelle con ampi giardini privati','Spa completa e piscine panoramiche','Vista sul Golfo di Napoli','Ristorante gourmet con terrazza','A 5 min dal centro di Sorrento'],
 'services': [
   ('https://www.ghlapace.com/wp-content/uploads/2022/04/grand-hotel-la-pace-sorrento.jpg','Camere & Suite','Camere e suite eleganti con balconi affacciati sui giardini e sul golfo'),
   ('https://www.ghlapace.com/wp-content/uploads/2022/06/barlapace1.jpg','Bar La Pace','Bar panoramico con cocktail, aperitivi e vista spettacolare sul Golfo'),
   (IMG['pizza'],'Ristorante Gourmet','Alta cucina campana con prodotti locali e cantina con oltre 300 etichette'),
   (IMG['vesuvio'],'Spa & Wellness','Centro benessere con piscina termale, sauna finlandese e trattamenti ayurvedici'),
   (IMG['capri'],'Giardini & Piscine','3 ettari di giardini con piscine panoramiche e zone relax esclusive'),
   (IMG['napoli1'],'Escursioni Luxury','Tour privati a Capri, Positano e Pompei con concierge personale'),
 ],
 'chatbot_greeting': 'Benvenuto al Grand Hotel La Pace! Il nostro assistente digitale è a vostra disposizione per prenotazioni, informazioni sulla spa e organizzazione di soggiorni esclusivi.',
},

# 14. Bleu Village Resort & Residence (resort)
{
 'old_folder': 'bleu-village-resort-residence-demo-experiences',
 'new_folder': 'bleu-village-resort-residence-new-template-experiences',
 'type': 'hotel',
 'name': 'Bleu Village Resort & Residence',
 'email': 'info@bleuvillage.com',
 'url': 'https://www.bleuvillage.com',
 'category': 'Resort & Residence',
 'tagline': 'Villaggio turistico e residence · Bungalow e residence autonomi · Meta di Sorrento',
 'description': 'Bleu Village Resort & Residence è un ampio complesso turistico a Meta di Sorrento che offre bungalow, residence e appartamenti autonomi immersi in un giardino mediterraneo con piscina. La formula residence lo rende ideale per famiglie e soggiorni lunghi nella Penisola Sorrentina.',
 'primary': '#1b5e8a',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.bleuvillage.com/images/large/tbg1290_piscina-bleu-village-meta.jpg?v=ffe5',
 'about_img': 'https://www.bleuvillage.com/images/large/tbg145_bleu-village-holiday-village-38_673.jpg?v=d0af',
 'stats': [('Resort','& Residence'),('Piscina','Panoramica'),('Meta di','Sorrento'),('Famiglie','Welcome')],
 'highlights': ['Bungalow e residence autonomi','Piscina panoramica con vista mare','Giardino mediterraneo rigoglioso','Ideale per famiglie e lunghi soggiorni','Servizi resort completi inclusi'],
 'services': [
   ('https://www.bleuvillage.com/images/large/tbg1290_piscina-bleu-village-meta.jpg?v=ffe5','Piscina Resort','Grande piscina panoramica con vista sul Golfo di Napoli e zona solarium'),
   ('https://www.bleuvillage.com/images/large/tbg145_bleu-village-holiday-village-38_673.jpg?v=d0af','Bungalow & Residence','Sistemazioni autonome per 2-8 persone con cucina attrezzata e terrazza privata'),
   (IMG['pizza'],'Ristorante e Bar','Cucina italiana e locale con servizio al tavolo e self-service'),
   (IMG['capri'],'Escursioni','Organizzazione tour a Capri, Positano e Pompei con minibus dal resort'),
   (IMG['vesuvio'],'Animazione','Programma di intrattenimento per adulti e bambini tutto il giorno'),
   (IMG['napoli1'],'Reception & Servizi','Reception h24, minimarket, noleggio bici e servizi per famiglie'),
 ],
 'chatbot_greeting': 'Benvenuto al Bleu Village Resort! Posso aiutarvi a scegliere il bungalow o residence più adatto alla vostra famiglia e rispondere a tutte le domande sul resort.',
},

# 15. Grand Hotel Parker's (hotel 5★)
{
 'old_folder': 'grand-hotel-parkers-napoli-demo-experiences',
 'new_folder': 'grand-hotel-parkers-napoli-new-template-experiences',
 'type': 'hotel',
 'name': "Grand Hotel Parker's",
 'email': 'info@grandhotelparkers.it',
 'url': 'https://www.grandhotelparkers.it',
 'category': 'Hotel 5 stelle Lusso (Relais & Châteaux)',
 'tagline': 'Dal 1870 sul Lungomare di Napoli · Relais & Châteaux · Ristorante George\'s',
 'description': "Il Grand Hotel Parker's è una delle dimore storiche più prestigiose d'Italia, affacciata sul Lungomare di Napoli dal 1870. Membro dei Relais & Châteaux, il suo ristorante George's offre una delle cucine più raffinate della città con vista panoramica sul Golfo di Napoli e sul Vesuvio.",
 'primary': '#1c1008',
 'secondary': '#c9954a',
 'hero_img': IMG['napoli2'],
 'about_img': IMG['napoli3'],
 'stats': [('1870','Anno di Fondazione'),('R&C','Relais & Châteaux'),('George\'s','Ristorante Panoramico'),('Lungomare','di Napoli')],
 'highlights': ['Dimora storica dal 1870','Relais & Châteaux member','Ristorante George\'s con vista panoramica','Sul Lungomare di Napoli','Eccellenza gastronomica napoletana'],
 'services': [
   (IMG['napoli2'],'Suite Panoramiche','Suite eleganti con affaccio diretto sul Golfo di Napoli e Vesuvio'),
   (IMG['napoli3'],'Ristorante George\'s','Alta cucina napoletana con ingredienti di eccellenza e cantina premiata'),
   (IMG['pizza'],'Roof Bar','Bar panoramico sul tetto con vista a 360° su Napoli e il Golfo'),
   (IMG['vesuvio'],'Spa & Benessere','Centro benessere con trattamenti personalizzati e area relax esclusiva'),
   (IMG['capri'],'Escursioni da Napoli','Tour guidati al centro storico UNESCO, Pompei, Ercolano e Costiera'),
   (IMG['napoli1'],'Concierge d\'Eccellenza','Servizio concierge Relais & Châteaux per ogni esigenza del vostro soggiorno'),
 ],
 'chatbot_greeting': "Benvenuto al Grand Hotel Parker's. Sono il vostro concierge digitale. Come posso assistervi con prenotazioni, informazioni sul ristorante George's o organizzazione di esperienze esclusive a Napoli?",
},

# 16. Hotel Royal Continental (hotel 4★)
{
 'old_folder': 'hotel-royal-continental-napoli-demo-experiences',
 'new_folder': 'hotel-royal-continental-napoli-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Royal Continental Napoli',
 'email': 'info@royalcontinental.it',
 'url': 'https://www.royalcontinental.it',
 'category': 'Hotel 4 stelle',
 'tagline': '397 camere sul Lungomare di Napoli · Via Partenope · Gruppo Best Western',
 'description': 'L\'Hotel Royal Continental Napoli è uno dei più grandi hotel 4 stelle della città con 397 camere direttamente sul Lungomare di Napoli in Via Partenope. Parte del gruppo Best Western, offre vista sul Golfo, piscina panoramica sul tetto e posizione strategica per esplorare Napoli e la Campania.',
 'primary': '#1e3a5f',
 'secondary': '#c9a84c',
 'hero_img': IMG['napoli2'],
 'about_img': IMG['napoli3'],
 'stats': [('397','Camere'),('Lungomare','Napoli'),('Roof','Pool & Bar'),('BW','Premier Member')],
 'highlights': ['397 camere sul Lungomare di Napoli','Piscina panoramica sul tetto','Via Partenope con vista Vesuvio','Parte di Best Western Premier','Navetta gratuita per il centro'],
 'services': [
   (IMG['napoli2'],'Camere Vista Mare','397 camere con vista sul Golfo di Napoli, Vesuvio e Castel dell\'Ovo'),
   (IMG['napoli3'],'Roof Pool & Bar','Piscina panoramica sul tetto con bar e vista a 360° su Napoli'),
   (IMG['pizza'],'Ristorante Il Gobbetto','Cucina napoletana autentica con pizza verace e prodotti DOP locali'),
   (IMG['vesuvio'],'Conference Center','Sale meeting per 10-400 persone con tecnologia avanzata'),
   (IMG['capri'],'Escursioni Campania','Tour organizzati a Pompei, Ercolano, Capri e Costiera Amalfitana'),
   (IMG['napoli1'],'Posizione Strategica','A 5 minuti a piedi da Castel dell\'Ovo, centro storico UNESCO e porto'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Royal Continental Napoli! Posso aiutarvi con prenotazioni, informazioni sul roof bar panoramico o organizzare le vostre escursioni campane.',
},

# 17. Torres Travel (tour operator)
{
 'old_folder': 'torres-travel-pompei-demo-experiences',
 'new_folder': 'torres-travel-pompei-new-template-experiences',
 'type': 'tour',
 'name': 'Torres Travel Pompei',
 'email': 'info@torrestravel.it',
 'url': 'https://www.torrestravel.it',
 'category': 'Tour operator incoming · Pompei Guide Center',
 'tagline': 'Pompei Guide Center · Specialista crocieristi · 11+ lingue · Dal 1992',
 'description': 'Torres Travel è un tour operator incoming specializzato nella gestione dei flussi turistici verso Pompei, Ercolano e il Vesuvio, con particolare attenzione ai crocieristi in arrivo a Napoli. Con guide certificate in oltre 11 lingue e flotta propria di veicoli, offre servizi B2B a tour operator mondiali dal 1992.',
 'primary': '#1a5c8a',
 'secondary': '#c9a84c',
 'hero_img': 'https://torrestravel.it/wp-content/uploads/2020/01/pompeii-03.jpg',
 'about_img': 'https://torrestravel.it/wp-content/uploads/2020/01/home-slide-02-768x388.jpg',
 'stats': [('30+','Anni di Esperienza'),('11+','Lingue Disponibili'),('Pompei','Guide Center'),('Crociere','Specialist')],
 'highlights': ['Specialista scavi di Pompei ed Ercolano','Guide certificate in 11+ lingue','Gestione gruppi crocieristi','Flotta propria di veicoli di lusso','B2B con tour operator mondiali'],
 'services': [
   ('https://torrestravel.it/wp-content/uploads/2020/01/pompeii-03.jpg','Pompei Guide Center','Visite guidate agli scavi di Pompei con guide archeologhe certificate in 11 lingue'),
   ('https://torrestravel.it/wp-content/uploads/2020/01/home-slide-02-768x388.jpg','Crocieristi Napoli','Gestione gruppi crocieristi in arrivo al Porto di Napoli con tour pre-programmati'),
   (IMG['vesuvio'],'Vesuvio & Ercolano','Escursioni al Vesuvio e agli scavi di Ercolano con trasporto e guida inclusi'),
   (IMG['napoli1'],'Tour Napoli','Tour del centro storico UNESCO di Napoli con guide esperte e transfer'),
   (IMG['capri'],'Capri & Costiera','Escursioni a Capri e sulla Costiera Amalfitana con barca o trasporto terrestre'),
   (IMG['pizza'],'Esperienze Gastronomiche','Tour enogastronomici: pizza napoletana, mozzarella DOP, limoncello artigianale'),
 ],
 'chatbot_greeting': 'Benvenuto in Torres Travel! Siamo specialisti di Pompei e del territorio campano. Come posso aiutarvi a organizzare il vostro tour o gestire il vostro gruppo?',
},

# 18. Grand Hotel Aminta (hotel 4★)
{
 'old_folder': 'grand-hotel-aminta-sorrento-demo-experiences',
 'new_folder': 'grand-hotel-aminta-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel Aminta Sorrento',
 'email': 'info@aminta.it',
 'url': 'https://www.aminta.com',
 'category': 'Hotel 4 stelle',
 'tagline': 'Sorrento collina panoramica · Vista Golfo di Napoli · Piscine · Accoglienza familiare',
 'description': 'Il Grand Hotel Aminta è un elegante 4 stelle sulla collina di Sorrento con panorama spettacolare sul Golfo di Napoli. Gestione familiare con standard internazionali, piscine panoramiche e un servizio caldo e personalizzato che ha conquistato generazioni di ospiti fedeli.',
 'primary': '#1a3a6e',
 'secondary': '#c9a84c',
 'hero_img': IMG['napoli1'],
 'about_img': 'https://www.aminta.com/images/1405-senza-titolo-13.png?v=fbfc',
 'stats': [('Collina','Panoramica'),('Vista','Golfo Napoli'),('Piscine','Multiple'),('Famiglia','da Generazioni')],
 'highlights': ['Posizione panoramica sulla collina','Vista spettacolare sul Golfo','Gestione familiare da generazioni','Piscine con vista mare','Staff accogliente e multilingue'],
 'services': [
   (IMG['napoli1'],'Camere Panoramiche','Camere eleganti con vista sulla baia di Napoli e il Vesuvio all\'orizzonte'),
   ('https://www.aminta.com/images/1405-senza-titolo-13.png?v=fbfc','Piscine & Terrazza','Piscine panoramiche e terrazza sole con vista a 180° sul Golfo di Napoli'),
   (IMG['pizza'],'Ristorante Aminta','Cucina sorrentina tradizionale con prodotti freschi locali e menu stagionali'),
   (IMG['capri'],'Escursioni','Tour a Capri, Positano, Pompei con organizzazione di transfer e guide'),
   (IMG['vesuvio'],'Bar Panoramico','Cocktail e aperitivi al tramonto con vista indimenticabile'),
   (IMG['napoli3'],'Accoglienza Familiare','Atmosfera calda e personale dedicato per sentirsi a casa in Penisola Sorrentina'),
 ],
 'chatbot_greeting': 'Benvenuto al Grand Hotel Aminta! Come posso aiutarvi? Posso verificare disponibilità camere, illustrare i nostri servizi o aiutarvi a pianificare le escursioni dalla Penisola Sorrentina.',
},

# 19. Grand Hotel Ambasciatori (hotel 5★)
{
 'old_folder': 'grand-hotel-ambasciatori-demo-experiences',
 'new_folder': 'grand-hotel-ambasciatori-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel Ambasciatori Sorrento',
 'email': 'ambasciatori@manniellohotels.com',
 'url': 'https://www.ambasciatorisorrento.com',
 'category': 'Hotel 5 stelle (Manniello Hotels)',
 'tagline': '5 stelle a picco sul mare · Beach club esclusivo · Vista Vesuvio · Manniello Hotels',
 'description': 'Il Grand Hotel Ambasciatori Sorrento è un lussuoso 5 stelle del gruppo Manniello Hotels, posizionato a picco sulla scogliera con accesso diretto al beach club esclusivo. La sua posizione unica e il servizio d\'eccellenza lo rendono uno degli hotel più esclusivi dell\'intera Penisola Sorrentina.',
 'primary': '#0d2b45',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.ambasciatorisorrento.com/wp-content/uploads/sites/344/2024/07/Aperitivo-Mobile-450x900.jpg',
 'about_img': 'https://www.ambasciatorisorrento.com/wp-content/uploads/sites/344/2024/07/Aperitivo-Mobile-300x150.jpg',
 'stats': [('5★','Stelle'),('A Picco','sul Mare'),('Beach Club','Esclusivo'),('Manniello','Hotels Group')],
 'highlights': ['A picco sulla scogliera di Sorrento','Beach club esclusivo con accesso diretto','Vista diretta su Vesuvio e golfo','Parte del gruppo Manniello Hotels','Ristorante panoramico d\'eccellenza'],
 'services': [
   ('https://www.ambasciatorisorrento.com/wp-content/uploads/sites/344/2024/07/Aperitivo-Mobile-450x900.jpg','Suite sulla Scogliera','Suite con terrazza privata sulla scogliera e vista mozzafiato sul Golfo di Napoli'),
   (IMG['capri'],'Beach Club Esclusivo','Accesso diretto al beach club privato sulla roccia con piattaforma sul mare'),
   (IMG['pizza'],'Ristorante Panoramico','Alta cucina con ingredienti locali e terrazza con vista sul Vesuvio'),
   (IMG['vesuvio'],'Spa & Pool','Piscina panoramica e spa con trattamenti luxury firmati'),
   (IMG['napoli2'],'Escursioni Premium','Capri, Positano, Pompei con yacht privato e guida personale dedicata'),
   (IMG['napoli1'],'Concierge 5★','Servizio concierge d\'eccellenza Manniello Hotels disponibile 24/7'),
 ],
 'chatbot_greeting': 'Benvenuto al Grand Hotel Ambasciatori Sorrento! Il nostro concierge digitale è pronto ad assistervi. Posso aiutarvi con prenotazioni suite, informazioni sul beach club e organizzazione di escursioni esclusive.',
},

# 20. Best Western Hotel La Solara (hotel 4★)
{
 'old_folder': 'best-western-hotel-la-solara-demo-experiences',
 'new_folder': 'best-western-hotel-la-solara-new-template-experiences',
 'type': 'hotel',
 'name': 'Best Western Hotel La Solara',
 'email': 'info@lasolara.com',
 'url': 'https://www.lasolara.com',
 'category': 'Hotel 4 stelle (Best Western)',
 'tagline': '58 camere a Sorrento Capo · Piscina con solarium · Catena Best Western',
 'description': 'Best Western Hotel La Solara è un elegante 4 stelle affiliato alla catena Best Western situato a Capo di Sorrento, con 58 camere eleganti, piscina con solarium e giardino mediterraneo. La posizione tranquilla a pochi minuti dal centro lo rende ideale per soggiorni relax.',
 'primary': '#1a3a6e',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.lasolara.com/wp-content/uploads/sites/123/2025/01/HOTEL-VIEW-768x512.webp',
 'about_img': 'https://www.lasolara.com/wp-content/uploads/sites/123/2024/07/BW-core-w-768x281.png',
 'stats': [('58','Camere Eleganti'),('BW','Best Western'),('Piscina','Solarium'),('Capo','di Sorrento')],
 'highlights': ['Affiliato Best Western Premier','58 camere eleganti a Capo di Sorrento','Piscina con solarium e giardino','Navetta per il centro inclusa','Standard internazionale garantito'],
 'services': [
   ('https://www.lasolara.com/wp-content/uploads/sites/123/2025/01/HOTEL-VIEW-768x512.webp','Camere & Vista','58 camere confortevoli con balcone e vista sul giardino o sul golfo'),
   ('https://www.lasolara.com/wp-content/uploads/sites/123/2024/07/BW-core-w-768x281.png','Piscina & Solarium','Piscina con ampia zona solarium, lettini e servizio bar estivo'),
   (IMG['pizza'],'Colazione Buffet','Ricca colazione buffet con prodotti freschi locali e dolci artigianali'),
   (IMG['capri'],'Escursioni','Tour a Capri, Positano e Pompei con navetta dall\'hotel'),
   (IMG['vesuvio'],'Giardino Mediterraneo','Giardino rigoglioso per passeggiate e relax nella quiete sorrentina'),
   (IMG['napoli1'],'Standard BW','Servizi e qualità garantiti dalla catena Best Western internationale'),
 ],
 'chatbot_greeting': 'Benvenuto al Best Western Hotel La Solara! Posso aiutarvi a prenotare una camera, verificare disponibilità e rispondere a domande sui servizi del nostro hotel.',
},

# 21. Hotel Crawford (hotel 4★)
{
 'old_folder': 'hotel-crawford-santagnello-demo-experiences',
 'new_folder': 'hotel-crawford-santagnello-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Crawford',
 'email': 'reservations@hotelcrawford.com',
 'url': 'https://www.hotelcrawford.it',
 'category': 'Hotel 4 stelle',
 'tagline': 'Hotel rinnovato sul Corso Crawford · Terrazza panoramica · A 2 min dal centro di Sorrento',
 'description': 'L\'Hotel Crawford è un elegante 4 stelle sul Corso Marion Crawford 77 a Sant\'Agnello, completamente ristrutturato nel 2016. Con terrazza panoramica sul Golfo di Napoli, camere moderne e posizione privilegiata a 2 minuti dal centro di Sorrento, è la scelta ideale per soggiorni di qualità.',
 'primary': '#1a3a5f',
 'secondary': '#c0953a',
 'hero_img': 'https://www.hotelcrawford.it/templates/crawford/images/1465308036_Hotel-Crawford-slide-1.jpg',
 'about_img': 'https://www.hotelcrawford.it/templates/crawford/images/1465308047_Hotel-Crawford-slide-2.jpg',
 'stats': [('4★','Stelle'),('2016','Anno Ristrutturazione'),('2 min','Dal Centro Sorrento'),('Sant\'Agnello','Penisola Sorrentina')],
 'highlights': ['Ristrutturato nel 2016 — tutto nuovo','Terrazza panoramica sul Golfo','2 minuti a piedi da Sorrento','Gestione indipendente familiare','Zero commissioni con prenotazione diretta'],
 'services': [
   ('https://www.hotelcrawford.it/templates/crawford/images/1465308036_Hotel-Crawford-slide-1.jpg','Camere Vista Mare','Camere rinnovate con balcone e vista panoramica sul Golfo di Napoli e Vesuvio'),
   ('https://www.hotelcrawford.it/templates/crawford/images/1465308047_Hotel-Crawford-slide-2.jpg','Terrazza Panoramica','Terrazza con vista mozzafiato per colazioni, aperitivi e momenti di relax'),
   (IMG['napoli1'],'Posizione Privilegiata','Corso Crawford nel cuore di Sant\'Agnello, a 2 minuti a piedi da Sorrento'),
   (IMG['capri'],'Escursioni Guidate','Capri, Costiera Amalfitana, Pompei e Vesuvio con partner selezionati'),
   (IMG['pizza'],'Food & Dining','Colazione con prodotti locali e ristoranti partner nel centro di Sorrento'),
   (IMG['vesuvio'],'Booking Diretto','Prenotazione online diretta senza intermediari per le migliori tariffe'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Crawford! Sono il vostro assistente personale. Posso aiutarvi a trovare la camera perfetta, verificare disponibilità e organizzare le escursioni dalla Penisola Sorrentina.',
},

# 22. Vestours (tour operator)
{
 'old_folder': 'vestours-santagnello-demo-experiences',
 'new_folder': 'vestours-santagnello-new-template-experiences',
 'type': 'tour',
 'name': 'Vestours',
 'email': 'info@vestours.com',
 'url': 'https://www.vestours.com',
 'category': 'Tour operator',
 'tagline': 'Tour operator su Costiera Amalfitana, Capri, Sicilia e Puglia · Incoming B2B',
 'description': 'Vestours è un tour operator incoming con sede a Sant\'Agnello di Sorrento specializzato nell\'organizzazione di tour su misura per agenzie e tour operator internazionali. Opera su Costiera Amalfitana, Capri, Ischia, Sicilia e Puglia con un network consolidato di guide e fornitori locali.',
 'primary': '#1e4a3a',
 'secondary': '#e8961a',
 'hero_img': 'https://www.vestours.com/gallery/gallery/1600/3g84sjijfa4g04c8wc.jpg',
 'about_img': 'https://www.vestours.com/gallery/thumbs/h430c2cpp40gko088.jpg',
 'stats': [('B2B','Incoming Specialist'),('Costiera','Capri Sicilia'),('Guide','Certificate'),('Sant\'Agnello','Sorrento')],
 'highlights': ['Incoming B2B per agenzie internazionali','Tour su Costiera, Capri, Sicilia, Puglia','Guide locali certificate e bilingue','Network consolidato di fornitori','Preventivi personalizzati rapidi'],
 'services': [
   ('https://www.vestours.com/gallery/gallery/1600/3g84sjijfa4g04c8wc.jpg','Costiera Amalfitana','Tour esclusivi sulla Costiera con guide certificate e transfer di lusso'),
   ('https://www.vestours.com/gallery/thumbs/h430c2cpp40gko088.jpg','Capri & Isole','Escursioni a Capri e Ischia con barche private e guide locali esperte'),
   (IMG['vesuvio'],'Pompei & Vesuvio','Visite guidate agli scavi e ascesa al Vesuvio con trasporto incluso'),
   (IMG['pizza'],'Sicilia Tour','Itinerari personalizzati in Sicilia: Palermo, Agrigento, Taormina, Etna'),
   (IMG['napoli2'],'Puglia Discovery','Tour della Valle d\'Itria, Trulli di Alberobello e Lecce barocca'),
   (IMG['napoli1'],'B2B Services','Preventivi rapidi, contratti agenzie e gestione booking per professionisti'),
 ],
 'chatbot_greeting': 'Benvenuto in Vestours! Siamo specialisti nell\'incoming B2B per agenzie internazionali. Posso aiutarvi a pianificare tour sulla Costiera Amalfitana, Capri, Sicilia o Puglia. Cosa cercate?',
},

# 23. Karsana Travel (tour operator)
{
 'old_folder': 'karsana-travel-santagnello-demo-experiences',
 'new_folder': 'karsana-travel-santagnello-new-template-experiences',
 'type': 'tour',
 'name': 'Karsana Travel',
 'email': 'info@karsanatravel.com',
 'url': 'https://karsanatravel.it',
 'category': 'Tour operator',
 'tagline': '28 anni di esperienza · Tour operator incoming Via Maiano Sant\'Agnello',
 'description': 'Karsana Travel è un tour operator incoming con 28 anni di esperienza, con sede in Via Maiano a Sant\'Agnello di Sorrento. Specializzato in tour personalizzati per agenzie internazionali e clientela diretta sulla Costiera Amalfitana, Capri, Napoli e Pompei.',
 'primary': '#2a1a5e',
 'secondary': '#c9501a',
 'hero_img': 'https://karsanatravel.it/images/yootheme/slide/amalfi_coast_karsanatravel.jpg',
 'about_img': 'https://karsanatravel.it/images/yootheme/slide/amalfi_coast_karsanatravel.jpg',
 'stats': [('28','Anni di Esperienza'),('Incoming','Specialist'),('Costiera','& Capri'),('Sant\'Agnello','Base Operativa')],
 'highlights': ['28 anni di esperienza nell\'incoming','Tour personalizzati per agenzie B2B','Guide certificate multilingue','Costiera Amalfitana, Capri, Napoli','Pompei & Ercolano specialist'],
 'services': [
   ('https://karsanatravel.it/images/yootheme/slide/amalfi_coast_karsanatravel.jpg','Costiera Amalfitana','Tour completi sulla Costiera con soste a Positano, Ravello e Amalfi'),
   (IMG['capri'],'Capri Esclusiva','Giro dell\'isola, Grotta Azzurra e Villa Jovis con guida locale esperta'),
   (IMG['vesuvio'],'Pompei & Ercolano','Visite guidate agli scavi con guide archeologi certificati in 5 lingue'),
   (IMG['napoli2'],'Tour Napoli','Scopri Napoli con guide locali: centro storico UNESCO, Castel dell\'Ovo, Spaccanapoli'),
   (IMG['pizza'],'Food Experience','Cooking class, tour della pizza e degustazioni di prodotti tipici campani'),
   (IMG['napoli1'],'Transfer Privati','Transfer da/per aeroporto con veicoli climatizzati e autisti professionali'),
 ],
 'chatbot_greeting': 'Benvenuto in Karsana Travel! Con 28 anni di esperienza siamo qui per creare la vostra vacanza perfetta nel Golfo di Napoli. Come posso aiutarvi oggi?',
},

# 24. Hotel Giosue a Mare (hotel 4★)
{
 'old_folder': 'hotel-giosue-a-mare-meta-sorrento-demo-experiences',
 'new_folder': 'hotel-giosue-a-mare-meta-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Giosue a Mare',
 'email': 'info@giosueamare.it',
 'url': 'https://www.giosueamare.it',
 'category': 'Hotel 4 stelle',
 'tagline': 'Fronte mare a Meta di Sorrento · Piscina con vista · Ristorante gourmet',
 'description': 'L\'Hotel Giosue a Mare è un elegante 4 stelle direttamente sul mare a Meta di Sorrento, con accesso privato alla spiaggia, piscina panoramica e ristorante gourmet con vista sul Golfo. Un angolo di paradiso nella Penisola Sorrentina, ideale per soggiorni romantici e in famiglia.',
 'primary': '#1a4060',
 'secondary': '#2a8a6a',
 'hero_img': 'https://www.giosueamare.it/media/upload/images/suite/Elegance.webp',
 'about_img': 'https://www.giosueamare.it/media/upload/images/suite/SUITE_1951-scaled.webp',
 'stats': [('Fronte','Mare'),('Piscina','Panoramica'),('4★','Stelle'),('Meta','di Sorrento')],
 'highlights': ['Direttamente sul mare a Meta di Sorrento','Accesso privato alla spiaggia','Piscina panoramica con vista golfo','Ristorante gourmet con terrazza','Suite e camere di design moderne'],
 'services': [
   ('https://www.giosueamare.it/media/upload/images/suite/Elegance.webp','Suite Elegance','Suite di lusso con vista panoramica sul Golfo di Napoli e arredi di design'),
   ('https://www.giosueamare.it/media/upload/images/suite/SUITE_1951-scaled.webp','Camere Fronte Mare','Camere eleganti con balcone e accesso diretto alla spiaggia privata'),
   (IMG['pizza'],'Ristorante Gourmet','Cucina creativa con ingredienti di mare freschi e terrazza panoramica'),
   (IMG['capri'],'Piscina & Spiaggia','Piscina con vista e accesso diretto al mare cristallino di Meta'),
   (IMG['vesuvio'],'Escursioni','Tour a Capri, Positano e Pompei con transfer incluso dall\'hotel'),
   (IMG['napoli3'],'Wellness & Relax','Trattamenti benessere con prodotti naturali locali per rigenerarsi'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Giosue a Mare! Posso aiutarvi a scegliere la suite o camera perfetta, verificare disponibilità e organizzare le vostre escursioni dalla Penisola Sorrentina.',
},

# 25. Panorama Palace Hotel (hotel 4★)
{
 'old_folder': 'panorama-palace-hotel-meta-sorrento-demo-experiences',
 'new_folder': 'panorama-palace-hotel-meta-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Panorama Palace Hotel',
 'email': 'info@hotelpanoramapalace.it',
 'url': 'https://www.hotelpanoramapalace.it',
 'category': 'Hotel 4 stelle',
 'tagline': 'Vista sul Golfo di Napoli · Museo Archeologico · Piscina con panorama',
 'description': 'Il Panorama Palace Hotel è un elegante 4 stelle a Meta di Sorrento con panorama spettacolare sul Golfo di Napoli. Dotato di piscina panoramica, ristorante gourmet e un piccolo museo archeologico privato, offre un soggiorno culturale e di relax unico nella Penisola Sorrentina.',
 'primary': '#2a4a1a',
 'secondary': '#c9a84c',
 'hero_img': 'https://www.hotelpanoramapalace.it/images/large/112_camere-superior-hotel-panorama-palace-1.jpg?v=90ab',
 'about_img': 'https://www.hotelpanoramapalace.it/images/large/116_piscina-hotel-panorama-palace-4.jpg?v=a831',
 'stats': [('4★','Stelle'),('Vista','Golfo Napoli'),('Piscina','Panoramica'),('Museo','Archeologico')],
 'highlights': ['Panorama eccezionale sul Golfo di Napoli','Museo Archeologico privato in hotel','Piscina panoramica con vista mare','Ristorante con ingredienti locali','Posizione tranquilla a Meta di Sorrento'],
 'services': [
   ('https://www.hotelpanoramapalace.it/images/large/112_camere-superior-hotel-panorama-palace-1.jpg?v=90ab','Camere Superior','Camere superior con balcone e vista panoramica sul Golfo di Napoli'),
   ('https://www.hotelpanoramapalace.it/images/large/116_piscina-hotel-panorama-palace-4.jpg?v=a831','Piscina Panoramica','Piscina con vista mozzafiato sul golfo, ampia zona relax e bar estivo'),
   (IMG['pizza'],'Ristorante','Cucina campana tradizionale con prodotti locali freschi e vini selezionati'),
   (IMG['vesuvio'],'Museo Archeologico','Collezione privata di reperti romani e greci dell\'area sorrentina'),
   (IMG['capri'],'Escursioni','Tour organizzati a Capri, Positano, Pompei con transfer dall\'hotel'),
   (IMG['napoli1'],'Giardini & Relax','Giardini fioriti per passeggiate e momenti di pace nel verde'),
 ],
 'chatbot_greeting': 'Benvenuto al Panorama Palace Hotel! Sono il vostro assistente. Posso aiutarvi con prenotazioni, informazioni sul nostro museo archeologico e le escursioni dalla Penisola Sorrentina.',
},

# 26. Hotel Club Sorrento (hotel 4★)
{
 'old_folder': 'hotel-club-sorrento-demo-experiences',
 'new_folder': 'hotel-club-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Club Sorrento',
 'email': 'info@hotelclubsorrento.com',
 'url': 'http://www.hotelclubsorrento.com',
 'category': 'Hotel 4 stelle',
 'tagline': 'Il vostro rifugio perfetto a Sant\'Agnello · Piscina · Giardino · Club atmosphere',
 'description': 'L\'Hotel Club Sorrento è un accogliente 4 stelle a Sant\'Agnello di Sorrento, che unisce l\'atmosfera di un club privato con i servizi di un hotel moderno. Con piscina, giardino mediterraneo e personale dedicato, offre un\'esperienza rilassante e personalizzata nella Penisola Sorrentina.',
 'primary': '#2c5f2d',
 'secondary': '#c9a84c',
 'hero_img': IMG['capri'],
 'about_img': IMG['vesuvio'],
 'stats': [('4★','Stelle'),('Club','Atmosphere'),('Sant\'Agnello','Sorrento'),('Piscina','e Giardino')],
 'highlights': ['Atmosfera di club privato esclusivo','Piscina e giardino mediterraneo','Sant\'Agnello di Sorrento — posizione centrale','Staff dedicato e accogliente','Ideale per soggiorni relax in famiglia'],
 'services': [
   (IMG['capri'],'Camere & Club','Camere eleganti con vista sul giardino o sulla piscina del club'),
   (IMG['vesuvio'],'Piscina Privata','Piscina riservata agli ospiti con lettini e servizio bar estivo'),
   (IMG['pizza'],'Ristorante Italiano','Cucina italiana autentica con menu stagionali e prodotti locali freschi'),
   (IMG['napoli1'],'Giardino Mediterraneo','Ampio giardino con limoni, aranci e bouganville per momenti di relax'),
   (IMG['napoli3'],'Escursioni','Tour a Capri, Costiera Amalfitana e Pompei con organizzazione dall\'hotel'),
   (IMG['napoli2'],'Bar & Lounge','Bar con selezione di cocktail, vini locali e aperitivi all\'italiana'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Club Sorrento! Come posso aiutarvi? Sono a disposizione per prenotazioni, informazioni sui servizi del club e organizzazione di escursioni locali.',
},

# 27. Grand Hotel Vesuvio Sorrento (hotel 4★)
{
 'old_folder': 'grand-hotel-vesuvio-sorrento-demo-experiences',
 'new_folder': 'grand-hotel-vesuvio-sorrento-new-template-experiences',
 'type': 'hotel',
 'name': 'Grand Hotel Vesuvio Sorrento',
 'email': 'info@vesuviosorrento.com',
 'url': 'https://www.vesuviosorrento.com',
 'category': 'Hotel 4 stelle',
 'tagline': 'Lusso e tradizione con vista sul Vesuvio · Sorrento',
 'description': 'Il Grand Hotel Vesuvio Sorrento è un elegante 4 stelle che offre una posizione privilegiata a Sorrento con vista diretta sul Vesuvio. Con camere deluxe, spa e servizio attento ai dettagli, rappresenta l\'eccellenza nel segmento 4 stelle della Penisola Sorrentina.',
 'primary': '#5d3a1a',
 'secondary': '#c9a84c',
 'hero_img': IMG['vesuvio'],
 'about_img': IMG['napoli2'],
 'stats': [('4★','Stelle'),('Vista','Vesuvio'),('Spa','& Wellness'),('Sorrento','Centro')],
 'highlights': ['Vista diretta sul Vesuvio','Camere deluxe con arredi di qualità','Spa e centro benessere','Staff qualificato e multilingue','Posizione centrale a Sorrento'],
 'services': [
   (IMG['vesuvio'],'Suite Deluxe Vista Vesuvio','Camere deluxe con vista diretta sul Vesuvio e arredamento raffinato'),
   (IMG['napoli2'],'Spa Vesuviana','Trattamenti esclusivi con prodotti termali e massaggi specializzati'),
   (IMG['pizza'],'Fine Dining Restaurant','Ristorante con chef stellato e selezione vini delle migliori cantine campane'),
   (IMG['napoli3'],'Terrazza Panoramica','Spazi esclusivi per cene romantiche ed eventi con vista spettacolare'),
   (IMG['napoli1'],'Transfer Privati','Servizio auto con autista per escursioni e trasferimenti aeroporto'),
   (IMG['capri'],'Concierge Elite','Servizio VIP per organizzare esperienze personalizzate in Penisola Sorrentina'),
 ],
 'chatbot_greeting': 'Benvenuto al Grand Hotel Vesuvio Sorrento! Posso aiutarvi con prenotazioni, informazioni sulla spa o organizzazione di escursioni nella Penisola Sorrentina.',
},

# 28. Sorrento Tourist Office (tour operator)
{
 'old_folder': 'sorrento-tourist-office-demo-experiences',
 'new_folder': 'sorrento-tourist-office-new-template-experiences',
 'type': 'tour',
 'name': 'Sorrento Tourist Office',
 'email': 'info@sorrentotouristoffice.com',
 'url': 'https://www.sorrentotouristoffice.com',
 'category': 'Web tour operator / Agenzia viaggi',
 'tagline': 'Il vostro gateway per la Costiera Sorrentina · Tour, Hotel e Transfer online',
 'description': 'Sorrento Tourist Office è un web tour operator specializzato nell\'organizzazione di vacanze complete nella Penisola Sorrentina. Offre pacchetti hotel, tour e transfer prenotabili online con assistenza personalizzata in italiano, inglese e tedesco.',
 'primary': '#003d82',
 'secondary': '#c9a84c',
 'hero_img': IMG['capri'],
 'about_img': IMG['napoli3'],
 'stats': [('Online','Booking 24/7'),('3','Lingue IT/EN/DE'),('Costiera','Sorrentina'),('Tour','Hotel Transfer')],
 'highlights': ['Prenotazioni online 24/7','Pacchetti tour + hotel personalizzati','Assistenza in italiano, inglese, tedesco','Migliori tariffe garantite senza intermediari','Esperti locali della Penisola Sorrentina'],
 'services': [
   (IMG['capri'],'Capri Day Trip','Escursioni giornaliere a Capri con barca o tragetto e guida inclusa'),
   (IMG['napoli3'],'Costiera Amalfitana','Tour lungo la Costiera con soste a Positano, Ravello e Amalfi'),
   (IMG['vesuvio'],'Pompei & Vesuvio','Visite guidate agli scavi di Pompei e ascesa al Vesuvio'),
   (IMG['pizza'],'Hotel Selection','Selezione curata di hotel 3-5 stelle con tariffe esclusive per prenotazione diretta'),
   (IMG['napoli2'],'Transfer Service','Transfer aeroporto, porto e stazione con veicoli moderni e autisti professionali'),
   (IMG['napoli1'],'Napoli City Tour','Tour guidato di Napoli: centro storico UNESCO, musei e gastronomia'),
 ],
 'chatbot_greeting': 'Benvenuto a Sorrento Tourist Office! Posso aiutarvi a pianificare la vostra vacanza: tour, hotel, transfer e molto altro. Come posso assistervi?',
},

# 29. Hotel Sporting Vico Equense (hotel 4★)
{
 'old_folder': 'hotel-sporting-vico-equense-demo-experiences',
 'new_folder': 'hotel-sporting-vico-equense-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Sporting Vico Equense',
 'email': 'info@hotel-sporting.it',
 'url': 'https://www.hotel-sporting.it',
 'category': 'Hotel 4 stelle',
 'tagline': 'Sport, benessere e natura a Vico Equense · Piscina · Tennis · Scogliera',
 'description': 'L\'Hotel Sporting Vico Equense è un 4 stelle con spiaggia privata direttamente sulla scogliera di Vico Equense, dotato di piscina, campi da tennis e ampi spazi sportivi. Ideale per chi cerca una vacanza attiva tra sport, relax e la bellezza naturale del Golfo di Napoli.',
 'primary': '#1a5e3e',
 'secondary': '#c9a84c',
 'hero_img': IMG['napoli2'],
 'about_img': IMG['capri'],
 'stats': [('4★','Stelle'),('Scogliera','& Mare'),('Tennis','& Sport'),('Vico','Equense')],
 'highlights': ['Direttamente sulla scogliera di Vico Equense','Spiaggia privata con accesso al mare','Campi da tennis e strutture sportive','Piscina con vista sul Golfo','A 20 min da Sorrento e Napoli'],
 'services': [
   (IMG['napoli2'],'Camere Vista Mare','Camere con balcone e vista diretta sul Golfo di Napoli e Capri'),
   (IMG['capri'],'Spiaggia & Scogliera','Accesso diretto alla spiaggia privata sulla scogliera con piattaforme sul mare'),
   (IMG['pizza'],'Ristorante Sportivo','Cucina leggera e mediterranea con buffet per sportivi e famiglie'),
   (IMG['vesuvio'],'Tennis & Sport','Campi da tennis, calcetto e attività sportive organizzate per adulti e bambini'),
   (IMG['napoli1'],'Piscina & Fitness','Piscina con idromassaggio, palestra attrezzata e istruttori disponibili'),
   (IMG['napoli3'],'Escursioni Active','Escursioni in kayak, snorkeling e tour in barca lungo la costa'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Sporting di Vico Equense! Sono qui per aiutarvi con prenotazioni, informazioni sui campi da tennis e le attività sportive disponibili.',
},

# 30. Hotel Astoria Vico Equense (hotel 3★)
{
 'old_folder': 'hotel-astoria-vico-equense-demo-experiences',
 'new_folder': 'hotel-astoria-vico-equense-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Astoria Vico Equense',
 'email': 'prenotazioni@astoriavico.com',
 'url': 'https://www.hotelastoriavico.it',
 'category': 'Hotel 3 stelle',
 'tagline': 'Comfort e accoglienza a Vico Equense · Rapporto qualità-prezzo eccellente',
 'description': 'L\'Hotel Astoria Vico Equense è un confortevole 3 stelle che offre un eccellente rapporto qualità-prezzo nel cuore di Vico Equense. Con camere confortevoli, colazione abbondante e staff accogliente, è la base ideale per esplorare la Penisola Sorrentina senza spendere una fortuna.',
 'primary': '#2f5f7f',
 'secondary': '#c9a84c',
 'hero_img': IMG['napoli3'],
 'about_img': IMG['vesuvio'],
 'stats': [('3★','Stelle'),('Vico','Equense'),('Prezzo','Qualità +'),('Penisola','Sorrentina')],
 'highlights': ['Eccellente rapporto qualità-prezzo','Posizione centrale a Vico Equense','Colazione abbondante inclusa','Staff accogliente e disponibile','Base ideale per escursioni locali'],
 'services': [
   (IMG['napoli3'],'Camere Confortevoli','Camere pulite e confortevoli con bagno privato e ogni comfort necessario'),
   (IMG['vesuvio'],'Colazione Buffet','Colazione abbondante con prodotti freschi locali per iniziare la giornata'),
   (IMG['pizza'],'Ristorante & Bar','Cucina italiana con piatti locali e atmosfera accogliente e familiare'),
   (IMG['capri'],'Escursioni Economiche','Tour a Sorrento, Positano, Capri e Pompei con prezzi competitivi'),
   (IMG['napoli1'],'Posizione Strategica','A pochi minuti dalla stazione ferroviaria e dai principali servizi'),
   (IMG['napoli2'],'Accoglienza Familiare','Gestione familiare con attenzione personale per ogni ospite'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Astoria di Vico Equense! Posso aiutarvi con prenotazioni, informazioni sulle camere e consigli sulle escursioni più economiche dalla Penisola Sorrentina.',
},

# 31. Hotel Mary (hotel 4★)
{
 'old_folder': 'hotel-mary-vico-equense-demo-experiences',
 'new_folder': 'hotel-mary-vico-equense-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Mary',
 'email': 'info@hotelmaryvico.com',
 'url': 'https://www.hotelmaryvico.com',
 'category': 'Hotel 4 stelle',
 'tagline': 'Panorama e comfort a Vico Equense · Vista Golfo · Ristorante pizzeria',
 'description': 'L\'Hotel Mary è un accogliente 4 stelle a Vico Equense con splendida vista panoramica sul Golfo di Napoli. Con ristorante e pizzeria propria, piscina con vista mare e atmosfera familiare, è la scelta ideale per soggiorni autentici nella Penisola Sorrentina.',
 'primary': '#2f5f7f',
 'secondary': '#c9a84c',
 'hero_img': IMG['napoli3'],
 'about_img': IMG['vesuvio'],
 'stats': [('4★','Stelle'),('Vista','Golfo Napoli'),('Pizzeria','Propria'),('Vico','Equense')],
 'highlights': ['Vista panoramica sul Golfo di Napoli','Ristorante e pizzeria propria','Piscina con vista mare','Gestione familiare e accogliente','A 10 min da Sorrento in treno'],
 'services': [
   (IMG['napoli3'],'Camere Vista Mare','Camere con balcone e vista panoramica sul Golfo di Napoli e Capri'),
   (IMG['vesuvio'],'Piscina Panoramica','Piscina con vista sul mare e zona solarium per momenti di relax'),
   (IMG['pizza'],'Pizzeria & Ristorante','Pizza napoletana verace e cucina sorrentina autentica in ristorante proprio'),
   (IMG['capri'],'Terrazza Panoramica','Terrazza con vista spettacolare per aperitivi al tramonto'),
   (IMG['napoli1'],'Escursioni','Tour a Capri, Positano, Sorrento e Pompei con partner selezionati'),
   (IMG['napoli2'],'Accoglienza','Atmosfera familiare e personale dedicato per soggiorni autentici'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Mary di Vico Equense! Posso aiutarvi con prenotazioni, informazioni sulla nostra pizzeria e le escursioni nella Penisola Sorrentina.',
},

# 32. Hotel Capo La Gala (hotel 5★)
{
 'old_folder': 'hotel-capo-la-gala-vico-equense-demo-experiences',
 'new_folder': 'hotel-capo-la-gala-vico-equense-new-template-experiences',
 'type': 'hotel',
 'name': 'Hotel Capo La Gala',
 'email': 'info@hotelcapolagala.com',
 'url': 'https://www.hotelcapolagala.com',
 'category': 'Hotel 5 stelle',
 'tagline': 'Luxury wellness sul mare di Vico Equense · Terme di Punta Santa Croce',
 'description': 'L\'Hotel Capo La Gala è un esclusivo 5 stelle wellness direttamente sulla scogliera di Vico Equense, famoso per le sue acque termali naturali che scaturiscono dalla roccia. Con accesso al mare, piscine termali, spa e ristorante gourmet, è uno dei resort wellness più esclusivi del Sud Italia.',
 'primary': '#5d3a1a',
 'secondary': '#c9a84c',
 'hero_img': IMG['napoli2'],
 'about_img': IMG['capri'],
 'stats': [('5★','Stelle Wellness'),('Terme','Naturali'),('Scogliera','& Mare'),('Vico','Equense')],
 'highlights': ['Terme naturali direttamente sulla roccia','5 stelle wellness esclusivo','Accesso diretto al mare cristallino','Ristorante gourmet con vista mare','Il wellness resort più esclusivo della costa'],
 'services': [
   (IMG['napoli2'],'Camere sulla Scogliera','Camere e suite con vista diretta sul mare e accesso alle piattaforme balneari'),
   (IMG['capri'],'Terme Naturali','Piscine termali con acqua che sgorga naturalmente dalla roccia vulcanica'),
   (IMG['pizza'],'Ristorante Gourmet','Alta cucina con ingredienti di mare freschi locali e cantina eccellente'),
   (IMG['vesuvio'],'Spa Luxury','Centro benessere con trattamenti termali, massaggi e beauty rituals esclusivi'),
   (IMG['napoli3'],'Accesso al Mare','Piattaforme sulla scogliera con scala e pontile per il bagno nel mare cristallino'),
   (IMG['napoli1'],'Escursioni Premium','Tour privati a Capri, Sorrento e Costiera Amalfitana con tender privato'),
 ],
 'chatbot_greeting': 'Benvenuto all\'Hotel Capo La Gala! Sono il vostro assistente wellness. Posso aiutarvi con prenotazioni, informazioni sulle nostre terme naturali e i trattamenti spa esclusivi.',
},

]  # end COMPANIES

# ─── Load templates ──────────────────────────────────────────────────────────
with open(HOTEL_TEMPLATE) as f:
    hotel_tmpl = f.read()

with open(TO_TEMPLATE) as f:
    to_tmpl = f.read()

# ─── Generate each demo ──────────────────────────────────────────────────────
created = []
skipped = []

for c in COMPANIES:
    out_dir = os.path.join(BASE, c['new_folder'])
    os.makedirs(out_dir, exist_ok=True)
    out_path = os.path.join(out_dir, 'index.html')

    # Pick template
    tmpl = to_tmpl if c['type'] == 'tour' else hotel_tmpl
    name_key = 'COMPANY' if c['type'] == 'tour' else 'HOTEL'

    # Pad/truncate lists
    stats = c['stats'][:4]
    while len(stats) < 4:
        stats.append(('—', ''))

    highlights = c['highlights'][:5]
    while len(highlights) < 5:
        highlights.append('Qualità e professionalità garantite')

    services = c['services'][:6]
    while len(services) < 6:
        services.append((IMG['napoli1'], 'Servizio Premium', 'Servizio di qualità per soggiorni indimenticabili'))

    html = tmpl
    # Name/category
    html = html.replace(f'{{{{{name_key}_NAME}}}}', c['name'])
    html = html.replace(f'{{{{{name_key}_CATEGORY}}}}', c['category'])
    html = html.replace(f'{{{{{name_key}_TAGLINE}}}}', c['tagline'])
    html = html.replace(f'{{{{{name_key}_DESCRIPTION}}}}', c['description'])
    html = html.replace(f'{{{{{name_key}_EMAIL}}}}', c['email'])
    html = html.replace(f'{{{{{name_key}_URL}}}}', c['url'])

    # Colors
    html = html.replace('{{PRIMARY_COLOR}}', c['primary'])
    html = html.replace('{{SECONDARY_COLOR}}', c['secondary'])

    # Images
    html = html.replace('{{HERO_IMAGE_URL}}', c['hero_img'])
    html = html.replace('{{ABOUT_IMAGE_URL}}', c['about_img'])

    # Stats
    for i, (num, label) in enumerate(stats, 1):
        html = html.replace(f'{{{{STAT{i}_NUM}}}}', str(num))
        html = html.replace(f'{{{{STAT{i}_LABEL}}}}', str(label))

    # Highlights
    for i, h in enumerate(highlights, 1):
        html = html.replace(f'{{{{HIGHLIGHT{i}}}}}', h)

    # Services
    for i, (img, title, desc) in enumerate(services, 1):
        html = html.replace(f'{{{{SERVICE{i}_IMG}}}}', img)
        html = html.replace(f'{{{{SERVICE{i}_TITLE}}}}', title)
        html = html.replace(f'{{{{SERVICE{i}_DESC}}}}', desc)

    # Chatbot
    html = html.replace('{{CHATBOT_GREETING}}', c['chatbot_greeting'])
    html = html.replace('{{CTA_TEXT}}', f"Vuoi un sito come questo per {c['name']}?")

    with open(out_path, 'w') as f:
        f.write(html)

    created.append(c['new_folder'])
    print(f"[OK] {c['new_folder']}")

print(f"\n✓ Creati {len(created)} nuove demo con il nuovo template.")
print(f"✗ Saltati: {len(skipped)}")
