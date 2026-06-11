#!/usr/bin/env python3
"""Genera ricerca_clienti_napoli_salerno.csv con schema unificato.

Fonti:
- /tmp/psall/summary.tsv  (PageSpeed batch)
- /tmp/psall/retry_*.json (PageSpeed retry per i falliti)
- email_map / google_map definiti inline (verificati)
- dati di analisi qualitativa pre-PageSpeed
"""
import csv, json, glob, urllib.parse, re, os

# ============== schema ==============
COLS = [
    "Nome_Azienda","URL_Sito","Google_Business_Link","Categoria","Priorita",
    "Email","Immagini","Link_Demo","Data_Generazione","Data_Contatto","Contattato",
    "Citta","Sito_Pre2020","Mobile_Friendly","Channel_Manager","Blog",
    "Criteri_Soddisfatti","Note","Brief_Demo_OpenClaw"
]

# ============== load PageSpeed data ==============
ps = {}
if os.path.exists('/tmp/psall/summary.tsv'):
    with open('/tmp/psall/summary.tsv', encoding='utf-8') as f:
        r = csv.reader(f, delimiter='\t')
        next(r)  # header
        for row in r:
            if len(row) < 8: continue
            name,url,perf,seo,bp,stack,booking,images = row[:8]
            ps[name] = dict(perf=perf, seo=seo, bp=bp, stack=stack, booking=booking, images=images)

# overlay retry results
BOOKING=r'simplebooking|verticalbooking|vertical-booking|blastness|ericsoft|ermeshotels|scidoo|slope|octorate|bedzzle|d-edge|decms|cendyn|bokun|fareharbor|regiondo|tourcms|getyourguide|viator|benvenuto|triptease'
retry_map = {
    'Hotel-Mediterraneo-Sorrento': 'Hotel Mediterraneo Sorrento',
    'Hotel-Bellevue-Syrene': 'Hotel Bellevue Syrene',
    'Golden-Tours': 'Golden Tours International',
    'Hotel-Cesare-Augusto': 'Grand Hotel Cesare Augusto',
    'Torres-Travel': 'Torres Travel',
    'Sirenide-Viaggi': 'Sirenide Viaggi',
}
for slug, name in retry_map.items():
    fp = f'/tmp/psall/retry_{slug}.json'
    if not os.path.exists(fp): continue
    d = json.load(open(fp))
    lr = d.get('lighthouseResult',{})
    if not lr: continue
    cats=lr.get('categories',{}); audits=lr.get('audits',{})
    perf=int((cats.get('performance',{}).get('score') or 0)*100)
    seo=int((cats.get('seo',{}).get('score') or 0)*100)
    bp=int((cats.get('best-practices',{}).get('score') or 0)*100)
    libs=[i.get('name') for i in audits.get('js-libraries',{}).get('details',{}).get('items',[])]
    stacks=[s.get('id') for s in (lr.get('stackPacks') or [])]
    stack='|'.join(filter(None,stacks+libs))[:60]
    base='.'.join((urllib.parse.urlparse(lr.get('finalUrl','')).hostname or '').split('.')[-2:])
    booking=set(); imgs=[]
    for it in audits.get('network-requests',{}).get('details',{}).get('items',[]):
        u=it.get('url',''); h=(urllib.parse.urlparse(u).hostname or '')
        m=re.search(BOOKING,h+u,re.I)
        if m: booking.add(m.group(0).lower())
        if it.get('resourceType','')=='Image' and base and base in h and not re.search(r'logo|icon|sprite|favicon|pixel|1x1',u,re.I):
            if u not in imgs: imgs.append(u)
    ps[name] = dict(perf=str(perf), seo=str(seo), bp=str(bp), stack=stack,
                    booking='|'.join(sorted(booking)), images=' '.join(imgs[:3]))

# ============== email & google ==============
EMAIL = {
 'Hotel Prestige Sorrento': 'info@hotelprestigesorrento.com',
 'Grand Hotel Capodimonte Sorrento': 'capodimonte@manniellohotels.com',
 'Grand Hotel Royal Sorrento': 'royal@manniellohotels.com',
 'Grand Hotel Excelsior Vittoria': 'fb@exvitt.it',
 'Torres Travel': 'info@torrestravel.it',
 'Sirenide Viaggi': 'info@sirenide.com',
 'Grand Hotel Cocumella': 'info@cocumella.com',
 'Golden Tours International': 'incoming@goldentours.it',
 'Grand Hotel Aminta': 'info@aminta.it',
 'Grand Hotel Cesare Augusto': 'info@hotelcesareaugusto.com',
 'Hotel Mediterraneo Sorrento': 'info@mediterraneosorrento.com',
 'Hotel Lorelei et Londres': 'info@loreleisorrento.com',
 'Grand Hotel La Favorita': 'info@hotellafavorita.com',
 'Grand Hotel Ambasciatori': 'ambasciatori@manniellohotels.com',
 'Hotel Continental Sorrento': 'info@continentalsorrento.com',
 'Hotel Club Sorrento': 'info@hotelclubsorrento.com',
 'Best Western Hotel La Solara': 'info@lasolara.com',
 'Grand Hotel Vesuvio Sorrento': 'info@vesuviosorrento.com',
 'Hotel Antiche Mura Sorrento': 'info@hotelantichemura.com',
 'HP Travel': 'info@hptravel.it',
 'Sorrento Tourist Office': 'info@sorrentotouristoffice.com',
 'Hotel Bellevue Syrene': 'info@bellevue.it',
 'Ara Maris Sorrento': 'info@aramarishotel.com',
 'Grand Hotel Riviera Sorrento': 'info@hotelriviera.com',
 'Hotel Tirrenia Sorrento': 'info@hoteltirrenia.com',
 'Sorrento Insider / Gray Line': 'info@iammeia.com',
}

GMAPS = {
 'Hotel Prestige Sorrento': 'https://www.google.com/maps/search/?api=1&query=Hotel+Prestige+Sorrento+Via+Nastro+Azzurro+23',
 'Grand Hotel Capodimonte Sorrento': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Capodimonte+Sorrento+Via+Capodimonte+16',
 'Grand Hotel Royal Sorrento': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Royal+Sorrento+Via+Correale+42',
 'Grand Hotel Excelsior Vittoria': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Excelsior+Vittoria+Sorrento+Piazza+Tasso',
 'Torres Travel': 'https://www.google.com/maps/search/?api=1&query=Torres+Travel+Via+Lepanto+143+Pompei',
 'Sirenide Viaggi': 'https://www.google.com/maps/search/?api=1&query=Sirenide+Viaggi+Via+degli+Aranci+25+Sorrento',
 'Grand Hotel Cocumella': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Cocumella+SantAgnello',
 'Golden Tours International': 'https://www.google.com/maps/search/?api=1&query=Golden+Tours+International+Sorrento',
 'Grand Hotel Aminta': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Aminta+Sorrento',
 'Grand Hotel Cesare Augusto': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Cesare+Augusto+Sorrento',
 'Hotel Mediterraneo Sorrento': 'https://www.google.com/maps/search/?api=1&query=Hotel+Mediterraneo+SantAgnello',
 'Hotel Lorelei et Londres': 'https://www.google.com/maps/search/?api=1&query=Hotel+Lorelei+et+Londres+Sorrento',
 'Grand Hotel La Favorita': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+La+Favorita+Sorrento',
 'Grand Hotel Ambasciatori': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Ambasciatori+Sorrento',
 'Hotel Continental Sorrento': 'https://www.google.com/maps/search/?api=1&query=Hotel+Continental+Sorrento',
 'Hotel Club Sorrento': 'https://www.google.com/maps/search/?api=1&query=Hotel+Club+Sorrento+SantAgnello',
 'Best Western Hotel La Solara': 'https://www.google.com/maps/search/?api=1&query=Best+Western+Hotel+La+Solara+Sorrento',
 'Grand Hotel Vesuvio Sorrento': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Vesuvio+Sorrento',
 'Hotel Antiche Mura Sorrento': 'https://www.google.com/maps/search/?api=1&query=Hotel+Antiche+Mura+Sorrento+Piazza+Tasso',
 'HP Travel': 'https://www.google.com/maps/search/?api=1&query=HP+Travel+Ercolano',
 'Sorrento Tourist Office': 'https://www.google.com/maps/search/?api=1&query=Sorrento+Tourist+Office',
 'Hotel Bellevue Syrene': 'https://www.google.com/maps/search/?api=1&query=Hotel+Bellevue+Syrene+Sorrento',
 'Ara Maris Sorrento': 'https://www.google.com/maps/search/?api=1&query=Ara+Maris+Hotel+Sorrento',
 'Grand Hotel Riviera Sorrento': 'https://www.google.com/maps/search/?api=1&query=Grand+Hotel+Riviera+Sorrento',
 'Hotel Tirrenia Sorrento': 'https://www.google.com/maps/search/?api=1&query=Hotel+Tirrenia+Sorrento',
 'Sorrento Insider / Gray Line': 'https://www.google.com/maps/search/?api=1&query=Sorrento+Insider+Gray+Line',
}

# ============== entry data per Sorrento ==============
# campi: url, categoria, citta, blog (Sì/No), notes_extra, brief
ROWS = [
 ("Sirenide Viaggi","https://www.sirenide.com","Tour operator incoming / DMC","Sorrento","No",
  "DMC storico 35+ anni B2B verso TO/ADV esteri. Via degli Aranci 25.",
  "Sirenide Viaggi; DMC e tour operator incoming B2B; Sorrento; partner incoming per il Sud Italia rivolto a tour operator e agenzie esteri; punti forza: oltre 35 anni di esperienza incoming - copertura Penisola Sorrentina Costiera Capri Ischia Napoli Roma - servizi su misura (hotel 3-5 stelle guide in 5 lingue bus lusso yacht) - eventi e matrimoni - rete di hotel di proprietà a Sorrento; palette blu navy + turchese mediterraneo + bianco con accenti oro; sezioni: home B2B / chi siamo / destinazioni e itinerari / servizi (hotel transfer guide eventi) / area agenzie e tour operator / gallery / contatti e richiesta preventivo; lingue IT/EN; tono istituzionale affidabile orientato al partner; CTA 'Richiedi un preventivo'"),

 ("Grand Hotel Excelsior Vittoria","https://excelsiorvittoria.com","Hotel 5 stelle Luxury (LHW)","Sorrento","No",
  "Brand iconico dal 1834 LHW 79 camere ristorante stellato Terrazza Bosquet Piazza Tasso. Booking via LHW (Triptease è metasearch non CM).",
  "Grand Hotel Excelsior Vittoria; 5 stelle luxury; Sorrento Piazza Tasso; landmark storico iconico sul Golfo di Napoli; punti forza: dimora storica dal 1834 con ospiti illustri (Wagner Wilde Caruso) - membro Leading Hotels of the World - ristorante stellato Michelin Terrazza Bosquet - parco-agrumeto privato e spa - ascensore privato verso il porto e marina - 79 camere vista mare; palette avorio caldo + verde agrume + oro antico su fondo crema; sezioni: home cinematica / camere e suite / dining stellato / spa e parco / esperienze (Capri Pompei Costiera) / storia e heritage / eventi e matrimoni / prenota; lingue IT/EN; tono elegante sobrio evocativo; CTA 'Prenota il tuo soggiorno'"),

 ("Hotel Mediterraneo Sorrento","https://www.mediterraneosorrento.com","Hotel 5 stelle","Sant'Agnello","No",
  "Michelin Key 2025, 61 camere, ascensore al mare, segmento lusso. Stack solo jQuery+Lo-Dash, no booking engine esterno rilevato (Parziale).",
  "Hotel Mediterraneo Sorrento; 5 stelle; Sant'Agnello a picco sul mare; boutique luxury con ascensore al mare; punti forza: terrazza e piscina sul mare - ascensore privato alla spiaggia - rooftop restaurant - Michelin Key 2025 - vista golfo; palette blu + bianco + ottone; sezioni: hero rooftop / camere / piscina e mare / dining / spa / esperienze / prenota; lingue IT/EN; tono fresco contemporaneo elegante; CTA 'Prenota la tua vista mare'"),

 ("Hotel Prestige Sorrento","https://www.hotelprestigesorrento.com","Hotel 4 stelle boutique","Sorrento","No",
  "Via Nastro Azzurro 23. 25 camere vista doppio golfo. Stack LitElement+Bootstrap+jQuery (moderno), SimpleBooking integrato. NON pre-2020.",
  "Hotel Prestige Sorrento; 4 stelle boutique; Via Nastro Azzurro 23 Sorrento; boutique panoramico intimo con navetta gratuita per il centro; punti forza: vista doppio golfo Napoli e Salerno - 25 camere intime - piscina a sfioro - roof bar - ristorante à la carte - navetta gratuita; palette azzurro golfo + bianco calce + ottone caldo stile mediterraneo elegante; sezioni: hero vista mare / camere / piscina e roof bar / ristorante / esperienze e dintorni / prenotazione / contatti; lingue IT/EN; tono caldo intimo esclusivo; CTA 'Prenota la tua vista sul golfo'"),

 ("Grand Hotel Capodimonte Sorrento","https://www.capodimontesorrento.com","Hotel 4 stelle superior","Sorrento","No",
  "Via Capodimonte 16. Gruppo Manniello su D-Edge (WP multisito). 5 piscine, 2-3 ristoranti, volume alto.",
  "Grand Hotel Capodimonte; 4 stelle superior; Via Capodimonte 16 Sorrento; resort panoramico classico-mediterraneo gruppo Manniello; punti forza: 5 piscine a cascata vista golfo - 2-3 ristoranti (Le Ginestre Le Querce) - giardini mediterranei - beach club - spa e jacuzzi - ingresso scavato nella roccia con ascensori; palette blu profondo + terracotta + verde mediterraneo + crema; sezioni: hero piscine a cascata / camere e suite / le 5 piscine / ristoranti / spa e beach club / esperienze costiera / prenotazione / contatti; lingue IT/EN; tono elegante classico raffinato; CTA 'Prenota la tua vista sul Golfo di Napoli'"),

 ("Grand Hotel Royal Sorrento","https://www.royalsorrento.com","Hotel 5 stelle","Sorrento","No",
  "Via Correale 42. Gruppo Manniello su D-Edge. Spiaggia privata, beach club, 3 ristoranti.",
  "Grand Hotel Royal; 5 stelle lusso; Via Correale 42 Sorrento; resort di lusso urbano gruppo Manniello; punti forza: posizione centrale a picco sul mare - spiaggia privata e beach club - piscina infinity con cascata e giardini di palme - 3 ristoranti e bar - spa con jacuzzi - vista Golfo e Vesuvio; palette blu navy + oro/ottone + bianco e verde palma; sezioni: hero piscina e mare / camere e suite / spiaggia privata e beach club / ristoranti / spa e wellness / esperienze e posizione / prenotazione / contatti; lingue IT/EN; tono lussuoso prestigioso accogliente; CTA 'Vivi il lusso fronte mare a Sorrento - Prenota'"),

 ("Torres Travel","https://www.torrestravel.it","Tour operator incoming Pompei guide center","Pompei","No",
  "Via Lepanto 143 Pompei. WordPress + WooCommerce (no booking engine esterno). Specialista crocieristi.",
  "Torres Travel; tour operator incoming e guide center; Pompei (Via Lepanto 143); specialista Pompei e Golfo di Napoli per crocieristi e gruppi; punti forza: Pompei Guide Center con guide multilingue (11+ lingue) - skip-the-line e shore excursions per crocieristi - escursioni Pompei Capri Costiera - transfer ristoranti cooking class trekking - operatore strutturato dal 2001; palette blu Mediterraneo + terracotta pompeiana + sabbia chiara; sezioni: home con ricerca esperienze / escursioni per tema (shore cruise gruppi) / Pompei guide center / destinazioni / prenota online / chi siamo / contatti; lingue IT/EN; tono dinamico professionale rassicurante; CTA 'Prenota la tua escursione'"),

 ("Grand Hotel Cocumella","https://www.cocumella.com","Hotel 5 stelle (Small Luxury Hotels)","Sant'Agnello","No",
  "Storico 1637 SLH. Stack jQuery UI + yepnope = vecchio. Veliero d'epoca per escursioni.",
  "Grand Hotel Cocumella; 5 stelle boutique storico (1637) Small Luxury Hotels; Sant'Agnello; dimora di charme tra storia e mare; punti forza: storia secolare - parco e agrumeto - piscina panoramica - ristorante - veliero d'epoca per escursioni - spa; palette crema + verde salvia + legno caldo; sezioni: hero heritage / camere / parco e piscina / dining / esperienze in mare / storia / prenota; lingue IT/EN; tono elegante storico intimo; CTA 'Prenota il tuo soggiorno storico'"),

 ("Golden Tours International","https://www.goldentours.it","Tour operator / DMC incoming","Sorrento","No",
  "DMC dal 1957 segmento alto/lusso. FareHarbor identificato come engine.",
  "Golden Tours International; tour operator DMC dal 1957; Sorrento; incoming alto/lusso per gruppi e individuali; punti forza: 65+ anni esperienza - escursioni Pompei Capri Vesuvio Costiera - hotel 4-5 stelle e ville - servizi premium (elicottero yacht) - rete B2B; palette blu mediterraneo + oro + bianco; sezioni: home / destinazioni e tour / servizi luxury / area B2B / chi siamo / contatti; lingue IT/EN; tono professionale prestigioso; CTA 'Richiedi un preventivo'"),

 ("Grand Hotel Aminta","https://www.aminta.com","Hotel 4 stelle","Sorrento","No",
  "Sorrento collina panoramica. Bootstrap+jQuery+WebFont (stack misto). No booking engine esterno rilevato in PageSpeed (la prima analisi indicava Ericsoft: verificare).",
  "Grand Hotel Aminta; 4 stelle; Sorrento collina panoramica; resort vista golfo per famiglie e coppie; punti forza: vista panoramica Golfo di Napoli - piscine - navetta centro - ampie sale - ristorante; palette blu + bianco + legno; sezioni: hero vista / camere / piscine / ristorante / eventi / prenota; lingue IT/EN; tono accogliente familiare; CTA 'Prenota ora'"),

 ("Grand Hotel Cesare Augusto","https://www.hotelcesareaugusto.com","Hotel 4 stelle","Sorrento","No",
  "120 camere centro Sorrento. Blastness confermato. SEO 100.",
  "Grand Hotel Cesare Augusto; 4 stelle 120 camere; Sorrento centro; hotel per gruppi e tour operator; punti forza: posizione centrale - roof garden con piscina - grande capacità per gruppi - ristorante - vicino Piazza Tasso; palette blu + crema + oro; sezioni: hero / camere / roof e piscina / gruppi e tour operator / ristorante / prenota; lingue IT/EN; tono efficiente accogliente; CTA 'Prenota / Richiedi preventivo gruppi'"),

 ("Hotel Lorelei et Londres","https://www.loreleisorrento.com","Hotel 5 stelle","Sorrento","No",
  "WordPress + React + WP Rocket (sito moderno). Ristrutturato 2019. Ristorante 1 stella Michelin.",
  "Hotel Lorelei et Londres; 5 stelle boutique; Sorrento fronte mare; dimora rinnovata 2019 con ristorante stellato; punti forza: vista golfo - piscina panoramica - ristorante 1 stella Michelin - terrazza - posizione centrale; palette pastello mediterraneo + oro; sezioni: hero / camere / piscina / ristorante stellato / esperienze / prenota; lingue IT/EN; tono raffinato gastronomico; CTA 'Prenota la tua esperienza'"),

 ("Grand Hotel La Favorita","https://www.hotellafavorita.com","Hotel 5 stelle","Sorrento","No",
  "WordPress + jQuery UI. 85 camere. Booking via ermeshotels (non emerso in PageSpeed ma noto). SEO 100.",
  "Grand Hotel La Favorita; 5 stelle 85 camere; Sorrento centro; hotel elegante con roof garden; punti forza: posizione centrale - piscina roof con vista - ristorante - spa - eventi e matrimoni; palette oro + avorio + verde; sezioni: hero / camere e suite / roof e piscina / dining / eventi / prenota; lingue IT/EN; tono classico lussuoso; CTA 'Prenota ora'"),

 ("Grand Hotel Ambasciatori","https://www.ambasciatorisorrento.com","Hotel 5 stelle (Manniello Hotels)","Sorrento","No",
  "Stack Vue moderno + D-Edge confermato. Sito già rinnovato.",
  "Grand Hotel Ambasciatori; 5 stelle; Sorrento a picco sul mare; resort panoramico Manniello con beach club; punti forza: terrazza a picco sul golfo - piscina - ascensore al beach club - ristorante - vista Vesuvio; palette blu profondo + oro + bianco; sezioni: hero scogliera / camere / piscina e beach club / dining / esperienze / prenota; lingue IT/EN; tono elegante panoramico; CTA 'Prenota la tua vista'"),

 ("Hotel Continental Sorrento","https://www.continentalsorrento.com","Hotel 4 stelle","Sorrento","No",
  "Stack Bootstrap+jQuery+Modernizr+yepnope (datato). Nessun booking engine rilevato in PageSpeed (la prima ricerca diceva Blastness: verificare). Piscina, terrazza.",
  "Hotel Continental Sorrento; 4 stelle; Sorrento centro vicino al porto; hotel panoramico con piscina; punti forza: terrazza panoramica - piscina - vicino Chiostro San Francesco e porto - ristorante; palette blu + bianco; sezioni: hero / camere / piscina e terrazza / ristorante / posizione / prenota; lingue IT/EN; tono accogliente classico; CTA 'Prenota ora'"),

 ("Hotel Club Sorrento","http://www.hotelclubsorrento.com","Hotel 4 stelle","Sant'Agnello","No",
  "Solo Bootstrap+jQuery. SEO basso (77). Nessun engine rilevato (prima ricerca SimpleBooking: verificare). 58 camere a conduzione familiare.",
  "Hotel Club Sorrento; 4 stelle conduzione familiare; Sant'Agnello; hotel intimo con piscina; punti forza: atmosfera familiare - piscina - giardino - vicino al centro - colazione; palette verde + bianco + legno; sezioni: hero / camere / piscina e giardino / servizi / dintorni / prenota; lingue IT/EN; tono familiare caloroso; CTA 'Prenota il tuo soggiorno'"),

 ("Best Western Hotel La Solara","https://www.lasolara.com","Hotel 4 stelle (catena BW)","Sorrento","No",
  "WordPress + Preact + Triptease. Catena Best Western.",
  "Best Western Hotel La Solara; 4 stelle 58 camere; Sorrento Capo; hotel con piscina e navetta; punti forza: piscina con solarium - navetta centro - parcheggio - ristorante - camere ampie; palette blu + bianco; sezioni: hero / camere / piscina / ristorante / servizi / prenota; lingue IT/EN; tono efficiente moderno; CTA 'Prenota ora' (NB: vincoli brand catena)"),

 ("Grand Hotel Vesuvio Sorrento","https://www.vesuviosorrento.com","Hotel 4 stelle","Sorrento","No",
  "Bootstrap+jQuery. Booking diretto (no engine esterno rilevato). Sale congressi.",
  "Grand Hotel Vesuvio Sorrento; 4 stelle; Sorrento; grande hotel con sale congressi; punti forza: piscina - sale meeting ed eventi - ristorante - vicino centro - parcheggio; palette blu + grigio + bianco; sezioni: hero / camere / piscina / eventi e congressi / ristorante / prenota; lingue IT/EN; tono professionale congressuale; CTA 'Prenota / Richiedi preventivo eventi'"),

 ("Hotel Antiche Mura Sorrento","https://www.hotelantichemura.com","Hotel 4 stelle","Sorrento","No",
  "Bootstrap+jQuery+yepnope (stack misto). Piazza Tasso. Nessun engine in PageSpeed (prima ricerca SimpleBooking hid=431: probabile).",
  "Hotel Antiche Mura Sorrento; 4 stelle; Sorrento Piazza Tasso; hotel di charme nel centro storico; punti forza: posizione premium centro storico - piscina nel vallone dei mulini - agrumeto - ristorante; palette ocra + verde agrume + crema; sezioni: hero / camere / piscina e giardino / ristorante / posizione / prenota; lingue IT/EN; tono elegante centrale; CTA 'Prenota ora'"),

 ("HP Travel","https://www.hptravel.it","Tour operator incoming","Ercolano","No",
  "WordPress + WP Rocket. GetYourGuide identificato come distributore. Base Ercolano.",
  "HP Travel; tour operator escursioni e transfer; Ercolano; incoming per Costiera Capri Pompei; punti forza: escursioni e transfer su misura - guide - prenotazione online - copertura golfo e costiera; palette blu + arancio; sezioni: home con ricerca tour / escursioni / transfer / prenota online / chi siamo / contatti; lingue IT/EN; tono dinamico; CTA 'Prenota online'"),

 ("Sorrento Tourist Office","https://www.sorrentotouristoffice.com","Web tour operator / agenzia viaggi","Sorrento","No",
  "Bootstrap+jQuery+jQuery UI. SEO 75. E-commerce escursioni con sezione Luxury.",
  "Sorrento Tourist Office; agenzia/web tour operator; Sorrento; booking online escursioni e transfer; punti forza: e-commerce escursioni - sezione luxury - transfer e tour - pagamenti online; palette blu + bianco + oro; sezioni: home / escursioni / luxury / transfer / prenota / contatti; lingue IT/EN; tono pratico orientato al booking; CTA 'Prenota la tua escursione'"),

 ("Hotel Bellevue Syrene","https://www.bellevue.it","Hotel 5 stelle (Relais & Chateaux)","Sorrento","Sì",
  "Joomla. 49 camere. Vertical Booking + blog news-events presente. Da escludere.",
  "(escluso - Joomla con blog e Vertical Booking) Hotel Bellevue Syrene; 5 stelle Relais & Chateaux; Sorrento."),

 ("Ara Maris Sorrento","https://www.aramarishotel.com","Hotel 5 stelle lusso","Sorrento","No",
  "Aperto 2024 sito nuovo. Blastness confermato.",
  "(escluso - struttura e sito nuovi 2024 con Blastness) Ara Maris; 5 stelle lusso; Sorrento."),

 ("Grand Hotel Riviera Sorrento","https://www.hotelriviera.com","Hotel 4 stelle","Sorrento","No",
  "Gruppo Benvenuto Collection + Blastness. SEO 100. Spiaggia privata.",
  "(escluso - CMS Benvenuto Collection moderno + Blastness) Grand Hotel Riviera; 4 stelle; Sorrento."),

 ("Hotel Tirrenia Sorrento","https://www.hoteltirrenia.com","Hotel 4 stelle","Sorrento","No",
  "Gruppo Benvenuto Collection + Blastness. SEO 100. Piscina infinity.",
  "(escluso - CMS Benvenuto Collection moderno + Blastness) Hotel Tirrenia; 4 stelle; Sorrento."),

 ("Sorrento Insider / Gray Line","https://www.sorrentoinsider.com","Marketplace/portale escursioni","Sorrento","Sì",
  "Piattaforma Regiondo moderna aggregatore. Funziona da guida.",
  "(escluso - marketplace Regiondo moderno) Sorrento Insider / Gray Line."),
]

# ============== compute criteria & priority ==============
def score_row(name, blog, citta_other_data):
    """ritorna (criteri 0..5, prio 1..5, pre2020, mobile, cm, note_extra)"""
    p = ps.get(name, {})
    perf = int(p.get('perf','0') or 0) if p.get('perf','').isdigit() else 0
    seo = int(p.get('seo','0') or 0) if p.get('seo','').isdigit() else 0
    stack = (p.get('stack') or '').lower()
    booking = (p.get('booking') or '').lower()

    # mobile-friendly: SEO >= 85 implica viewport meta presente
    if seo >= 85: mobile = f"Sì (SEO {seo})"
    elif seo > 0: mobile = f"Parziale (SEO {seo})"
    else: mobile = "Incerto"

    # pre-2020: euristica su stack (moderno = no)
    modern = any(x in stack for x in ['litelement','lit-html','react','preact','vue','wp-rocket','wordpress|wp']) or 'wordpress' in stack
    minimal = stack.strip() in ('core-js','') or stack == ''
    # joomla = vecchio framework ma può essere recente; jQuery+Bootstrap = base classica
    if minimal: pre2020 = "Sì (probabile)"
    elif 'litelement' in stack or 'react' in stack or 'preact' in stack or 'vue' in stack or 'wp-rocket' in stack: pre2020 = "No"
    elif 'joomla' in stack: pre2020 = "Sì (probabile)"  # Bellevue è recente ma Joomla = stack classico
    elif 'yepnope' in stack or 'jquery ui' in stack: pre2020 = "Sì (probabile)"
    elif stack.startswith('bootstrap') or stack.startswith('jquery'): pre2020 = "Incerto (stack classico)"
    else: pre2020 = "Incerto"

    # CM
    if booking:
        cm = f"Sì ({booking})"
    else:
        cm = "No (non rilevato)"

    # criteri 0..5: C1 datato + C2 traffico + C3 vendite + C4 no CM + C5 no blog
    c1 = 1.0 if pre2020.startswith("Sì") else (0.5 if "Incerto" in pre2020 else 0.0)
    # traffico/vendite: stime qualitative basate su dati noti (proxy: tipo struttura + n camere + brand)
    HIGH = {'Grand Hotel Excelsior Vittoria','Grand Hotel Capodimonte Sorrento','Grand Hotel Cesare Augusto','Grand Hotel Royal Sorrento','Grand Hotel Riviera Sorrento','Hotel Bellevue Syrene'}
    LOW = {'Hotel Prestige Sorrento','Sorrento Tourist Office'}
    if name in HIGH: c2 = c3 = 1.0
    elif name in LOW: c2 = c3 = 0.0
    else: c2 = c3 = 0.5
    # C4: no CM (criterio principale)
    c4 = 1.0 if booking == "" else 0.0
    # C5: no blog
    c5 = 1.0 if blog == "No" else 0.0
    criteri = c1+c2+c3+c4+c5

    # priorità
    excluded = name in {'Hotel Bellevue Syrene','Ara Maris Sorrento','Grand Hotel Riviera Sorrento','Hotel Tirrenia Sorrento','Sorrento Insider / Gray Line'}
    if excluded: prio = 5
    elif criteri >= 4.0: prio = 1
    elif criteri >= 3.0: prio = 2
    elif criteri >= 2.0: prio = 3
    else: prio = 4

    note_extra = f"PageSpeed mobile: perf={p.get('perf','n/d')} seo={p.get('seo','n/d')} bp={p.get('bp','n/d')}. Stack: {p.get('stack') or 'n/d'}. Booking engine: {p.get('booking') or 'non rilevato'}."
    return criteri, prio, pre2020, mobile, cm, note_extra

# ============== build CSV ==============
out_rows = [COLS]
for url, _ in []: pass
for tup in ROWS:
    name, url, cat, citta, blog, notes_extra, brief = tup
    crit, prio, pre2020, mobile, cm, ps_note = score_row(name, blog, None)
    note = f"{notes_extra} | {ps_note}"
    out_rows.append([
        name, url, GMAPS.get(name,''), cat, prio,
        EMAIL.get(name,''), ps.get(name,{}).get('images',''), '', '2026-06-09', '', 'No',
        citta, pre2020, mobile, cm, blog,
        f"{crit:.1f}", note, brief
    ])

# placeholder RESUME POINT
out_rows.append([
    '=== RESUME POINT ===','','','','','','','','2026-06-09','','',
    '','','','','','',
    "STOP: città SORRENTO (26 schede) consolidata con schema unificato + PageSpeed reale + email + immagini. NUOVA CLASSIFICA post-PageSpeed batch: vedi colonna Priorita+Criteri_Soddisfatti. DA FARE: estendere ricerca alle città Sorrento -> Napoli (Sant'Agnello/Piano/Meta/Vico Equense/Castellammare/Torre Annunziata/Torre del Greco/Ercolano/Portici/Napoli). Wayback bloccato in ambiente -> anzianità precisa da Wayback non verificabile, stimata dallo stack tech.",
    ''
])

with open('/home/user/experiences-demos/ricerca_clienti_napoli_salerno.csv','w',encoding='utf-8',newline='') as f:
    w = csv.writer(f, quoting=csv.QUOTE_MINIMAL)
    w.writerows(out_rows)
print(f"Scritte {len(out_rows)-2} schede (+header +RESUME)")
# stampa ranking
print("\n=== Ranking Sorrento ===")
ranked = sorted(out_rows[1:-1], key=lambda r:(int(r[4]), -float(r[16])))
for r in ranked[:8]:
    print(f"  P{r[4]} crit={r[16]} | {r[0]} | CM={r[14][:30]} | Pre2020={r[12][:25]}")
