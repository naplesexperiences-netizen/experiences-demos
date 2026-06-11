#!/usr/bin/env python3
"""
Generatore Demo Ciclo 3 Batch 1 - 5 aziende priority 2
Hotel Crawford, Vestours, Karsana Travel, Hotel Giosue a Mare, Panorama Palace
"""

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
        "slug": "hotel-crawford-santagnello",
        "name": "Hotel Crawford",
        "email": "reservations@hotelcrawford.com",
        "category": "Hotel 4 stelle",
        "city": "Sant'Agnello",
        "website": "https://www.hotelcrawford.it",
        "hero_img": "https://www.hotelcrawford.it/templates/crawford/images/1465308036_Hotel-Crawford-slide-1.jpg",
        "about_img": "https://www.hotelcrawford.it/templates/crawford/images/1465308047_Hotel-Crawford-slide-2.jpg",
        "tagline": "Hotel rinnovato sul Corso Crawford · Terrazza panoramica · A 2 min dal centro di Sorrento",
        "description": "L'Hotel Crawford è un elegante 4 stelle sul Corso Marion Crawford 77 a Sant'Agnello, rinnovato nel 2016. Con la sua terrazza panoramica sul Golfo di Napoli, camere moderne e posizione privilegiata a 2 minuti dal centro di Sorrento, è una scelta ideale per soggiorni di qualità nella Penisola Sorrentina.",
        "palette": {"primary": "#1a3a5f", "secondary": "#c0953a", "accent": "#c0953a", "light": "#f5f8fc", "dark": "#0d2035"},
        "stats": [
            {"num": "4★", "label": "Stelle"},
            {"num": "2016", "label": "Anno Ristrutturazione"},
            {"num": "2 min", "label": "Dal Centro di Sorrento"},
            {"num": "SEO 83", "label": "Score Web Attuale"},
        ],
        "services": [
            {"title": "Camere Vista Mare", "desc": "Camere rinnovate con balcone e vista panoramica sul Golfo di Napoli e Vesuvio", "img": "https://www.hotelcrawford.it/templates/crawford/images/1465308036_Hotel-Crawford-slide-1.jpg"},
            {"title": "Terrazza Panoramica", "desc": "Terrazza con vista mozzafiato: colazioni, aperitivi e relax con il Golfo di fronte", "img": "https://www.hotelcrawford.it/templates/crawford/images/1465308047_Hotel-Crawford-slide-2.jpg"},
            {"title": "Posizione Privilegiata", "desc": "Corso Crawford nel cuore di Sant'Agnello, a 2 minuti a piedi dal centro di Sorrento", "img": CAMPANIA_IMAGES["napoli1"]},
            {"title": "Escursioni Guidate", "desc": "Capri, Costiera Amalfitana, Pompei e Vesuvio a portata di mano con partner selezionati", "img": CAMPANIA_IMAGES["capri1"]},
            {"title": "Food & Dining", "desc": "Colazione con prodotti locali freschi e ristorante con cucina mediterranea tradizionale", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Booking Diretto", "desc": "Prenotazione online diretta senza intermediari per le migliori tariffe garantite", "img": CAMPANIA_IMAGES["vesuvio"]},
        ],
        "highlights": ["Ristrutturato 2016 — tutto nuovo", "Terrazza panoramica sul Golfo", "2 minuti a piedi da Sorrento", "Gestione indipendente familiare", "Zero commissioni OTA con prenotazione diretta"],
        "cta": "Prenota Direttamente",
        "chatbot_msg": "Benvenuto all'Hotel Crawford! Sono il vostro assistente personale. Posso aiutarti a trovare la camera perfetta, verificare disponibilità e organizzare le vostre escursioni dalla Penisola Sorrentina.",
    },
    {
        "slug": "vestours-santagnello",
        "name": "Vestours",
        "email": "info@vestours.com",
        "category": "Tour operator",
        "city": "Sant'Agnello",
        "website": "https://www.vestours.com",
        "hero_img": "https://www.vestours.com/gallery/gallery/1600/3g84sjijfa4g04c8wc.jpg",
        "about_img": "https://www.vestours.com/gallery/thumbs/h430c2cpp40gko088.jpg",
        "tagline": "Tour operator su Costiera Amalfitana, Capri, Sicilia e Puglia · Corso Italia Sant'Agnello",
        "description": "Vestours è un tour operator con sede su Corso Italia 15/A a Sant'Agnello, specializzato in tour ed escursioni lungo la Costiera Amalfitana, Capri, Sicilia e Puglia. Con anni di esperienza nell'organizzazione di viaggi per gruppi e individuali, offre itinerari personalizzati su tutto il Sud Italia.",
        "palette": {"primary": "#1e4a3a", "secondary": "#e8961a", "accent": "#e8961a", "light": "#f5faf7", "dark": "#0d2a1e"},
        "stats": [
            {"num": "4+", "label": "Destinazioni Sud Italia"},
            {"num": "B2B", "label": "e B2C"},
            {"num": "SEO 82", "label": "Score Web Attuale"},
            {"num": "0", "label": "Booking Engine Online"},
        ],
        "services": [
            {"title": "Costiera Amalfitana", "desc": "Tour giornalieri e multi-giorno: Positano, Amalfi, Ravello con guide certificate multilingue", "img": "https://www.vestours.com/gallery/gallery/1600/3g84sjijfa4g04c8wc.jpg"},
            {"title": "Capri & Isole", "desc": "Escursioni in barca a Capri con Grotta Azzurra, giro dell'isola e pranzo in ristorante tipico", "img": CAMPANIA_IMAGES["capri1"]},
            {"title": "Sicilia & Puglia", "desc": "Pacchetti fly&drive e tour organizzati in Sicilia e Puglia per gruppi e individuali", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Pompei & Vesuvio", "desc": "Tour guidati agli scavi archeologici di Pompei ed escursioni sul Vesuvio", "img": CAMPANIA_IMAGES["vesuvio"]},
            {"title": "Food Experience", "desc": "Degustazioni di limoncello, mozzarella, pizza e vini vulcanici con produttori locali", "img": CAMPANIA_IMAGES["pizza2"]},
            {"title": "Transfer & Navette", "desc": "Transfer privati da/per aeroporti, porti e stazioni con veicoli climatizzati", "img": CAMPANIA_IMAGES["napoli2"]},
        ],
        "highlights": ["Tour sulla Costiera Amalfitana", "Escursioni a Capri", "Sicilia e Puglia", "Gruppi e individuali", "Seat-in-coach e tour privati"],
        "cta": "Prenota la Tua Escursione",
        "chatbot_msg": "Ciao! Sono l'assistente AI di Vestours. Posso aiutarti a scegliere il tour perfetto per la tua vacanza nel Sud Italia. Costiera, Capri, Sicilia o Puglia — dove vuoi andare?",
    },
    {
        "slug": "karsana-travel-santagnello",
        "name": "Karsana Travel",
        "email": "info@karsanatravel.com",
        "category": "Tour operator",
        "city": "Sant'Agnello",
        "website": "https://karsanatravel.it",
        "hero_img": "https://karsanatravel.it/images/yootheme/slide/amalfi_coast_karsanatravel.jpg",
        "about_img": "https://karsanatravel.it/images/yootheme/slide/amalfi_coast_karsanatravel.jpg",
        "tagline": "28 anni di esperienza · Tour operator incoming Via Maiano Sant'Agnello",
        "description": "Karsana Travel è un tour operator con 28 anni di esperienza nel settore del turismo incoming, con sede a Via Maiano 8 a Sant'Agnello. Specializzata in escursioni e pacchetti per la Penisola Sorrentina e la Costiera Amalfitana, offre servizi per gruppi e individuali con un approccio personalizzato e professionale.",
        "palette": {"primary": "#2a1a5e", "secondary": "#c9501a", "accent": "#c9501a", "light": "#f5f3fc", "dark": "#150e35"},
        "stats": [
            {"num": "28+", "label": "Anni di Esperienza"},
            {"num": "IT/EN/FR", "label": "Lingue di Servizio"},
            {"num": "SEO 92", "label": "Score Web Attuale"},
            {"num": "Perf 39", "label": "PageSpeed (da migliorare)"},
        ],
        "services": [
            {"title": "Tour Costiera Amalfitana", "desc": "Escursioni giornaliere lungo la Costiera con sosta a Positano, Amalfi e Ravello", "img": "https://karsanatravel.it/images/yootheme/slide/amalfi_coast_karsanatravel.jpg"},
            {"title": "Escursioni Capri", "desc": "Tour in barca a Capri con Grotta Azzurra e giro panoramico dell'isola", "img": CAMPANIA_IMAGES["capri2"]},
            {"title": "Pompei & Ercolano", "desc": "Visite guidate agli scavi di Pompei ed Ercolano con guide certificate bilingue", "img": CAMPANIA_IMAGES["vesuvio2"]},
            {"title": "Tour Napoli", "desc": "Scopri Napoli con guide locali esperte: centro storico UNESCO, Castel dell'Ovo, Spaccanapoli", "img": CAMPANIA_IMAGES["napoli3"]},
            {"title": "Esperienze Gastronomiche", "desc": "Cooking class, tour della pizza e degustazioni di prodotti tipici campani", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Transfer Privati", "desc": "Transfer da/per aeroporto di Napoli e porti con veicoli climatizzati e autisti professionali", "img": CAMPANIA_IMAGES["napoli1"]},
        ],
        "highlights": ["28 anni di esperienza", "Incoming specializzato", "IT/EN/FR multilingue", "Personalizzazione itinerari", "Rapporto qualità/prezzo"],
        "cta": "Richiedi un Preventivo",
        "chatbot_msg": "Benvenuto in Karsana Travel! Con 28 anni di esperienza siamo qui per creare la vostra vacanza perfetta nel Golfo di Napoli. Come posso aiutarti oggi?",
    },
    {
        "slug": "hotel-giosue-a-mare-meta-sorrento",
        "name": "Hotel Giosue a Mare",
        "email": "info@giosueamare.it",
        "category": "Hotel 4 stelle",
        "city": "Meta di Sorrento",
        "website": "https://www.giosueamare.it",
        "hero_img": "https://www.giosueamare.it/media/upload/images/suite/Elegance.webp",
        "about_img": "https://www.giosueamare.it/media/upload/images/suite/SUITE_1951-scaled.webp",
        "tagline": "Fronte mare a Meta di Sorrento · Piscina con vista · Ristorante gourmet",
        "description": "L'Hotel Giosue a Mare è un elegante 4 stelle fronte mare a Via A. Caruso 2, Meta di Sorrento. Con piscina panoramica, ristorante gourmet e camere con balcone sul Golfo di Napoli, offre un'esperienza autentica nella parte più tranquilla e autentica della Penisola Sorrentina. PageSpeed mobile al 94% — uno dei siti più performanti della zona.",
        "palette": {"primary": "#1a4060", "secondary": "#2a8a6a", "accent": "#c9a84c", "light": "#f0f8f5", "dark": "#0d2535"},
        "stats": [
            {"num": "4★", "label": "Stelle"},
            {"num": "94", "label": "PageSpeed Mobile"},
            {"num": "🌊", "label": "Fronte Mare"},
            {"num": "Meta", "label": "di Sorrento"},
        ],
        "services": [
            {"title": "Suite Fronte Mare", "desc": "Suite ed elegance rooms con balcone privato e vista panoramica sul Golfo di Napoli", "img": "https://www.giosueamare.it/media/upload/images/suite/Elegance.webp"},
            {"title": "Suite 1951", "desc": "La suite storica che porta il nome dell'anno di apertura: un viaggio nel tempo tra lusso e tradizione", "img": "https://www.giosueamare.it/media/upload/images/suite/SUITE_1951-scaled.webp"},
            {"title": "Camera Deluxe", "desc": "Camere deluxe spaziose con arredamento elegante e tutti i comfort moderni", "img": "https://www.giosueamare.it/media/upload/images/deluxe/Camera-Deluxe-001-1.webp"},
            {"title": "Piscina Panoramica", "desc": "Piscina all'aperto con solarium affacciata sul mare e vista sul Golfo di Napoli", "img": CAMPANIA_IMAGES["napoli2"]},
            {"title": "Ristorante Gourmet", "desc": "Cucina mediterranea con prodotti freschi del territorio servita con vista sul mare", "img": CAMPANIA_IMAGES["pizza2"]},
            {"title": "Escursioni", "desc": "Capri, Positano, Amalfi, Pompei: tutte le mete raggiungibili facilmente da Meta di Sorrento", "img": CAMPANIA_IMAGES["capri1"]},
        ],
        "highlights": ["Fronte mare diretto", "PageSpeed 94 — sito veloce", "Piscina con vista golfo", "Ristorante gourmet", "Meta di Sorrento — zona tranquilla"],
        "cta": "Prenota la Tua Camera",
        "chatbot_msg": "Benvenuto al Giosue a Mare! Sono il vostro assistente personale. Posso aiutarvi con prenotazioni camere, informazioni sulla piscina e ristorante, e organizzare le vostre escursioni nella Penisola Sorrentina.",
    },
    {
        "slug": "panorama-palace-hotel-meta-sorrento",
        "name": "Panorama Palace Hotel",
        "email": "info@hotelpanoramapalace.it",
        "category": "Hotel 4 stelle",
        "city": "Meta di Sorrento",
        "website": "https://www.hotelpanoramapalace.it",
        "hero_img": "https://www.hotelpanoramapalace.it/images/large/112_camere-superior-hotel-panorama-palace-1.jpg?v=90ab",
        "about_img": "https://www.hotelpanoramapalace.it/images/large/116_piscina-hotel-panorama-palace-4.jpg?v=a831",
        "tagline": "Vista sul Golfo di Napoli · Museo Archeologico · Piscina con solarium · Piazza Scarpati",
        "description": "Il Panorama Palace Hotel è un 4 stelle in Piazza Scarpati 1 a Meta di Sorrento, con vista panoramica sul Golfo di Napoli. Ospita un piccolo museo archeologico locale e dispone di piscina con solarium, ristorante e camere spaziose. Una destinazione autentica per chi vuole scoprire la Penisola Sorrentina lontano dalla folla.",
        "palette": {"primary": "#2a4a1a", "secondary": "#c9a84c", "accent": "#c9a84c", "light": "#f5f8f2", "dark": "#162a0d"},
        "stats": [
            {"num": "4★", "label": "Stelle"},
            {"num": "🏛️", "label": "Museo Archeologico"},
            {"num": "Vista", "label": "Golfo di Napoli"},
            {"num": "SEO 92", "label": "Score Web Attuale"},
        ],
        "services": [
            {"title": "Camere Superior Vista Mare", "desc": "Camere superior spaziose con balcone e vista panoramica sul Golfo di Napoli", "img": "https://www.hotelpanoramapalace.it/images/large/112_camere-superior-hotel-panorama-palace-1.jpg?v=90ab"},
            {"title": "Piscina e Solarium", "desc": "Ampia piscina all'aperto con solarium e lettini, ideale per rilassarsi con vista sul golfo", "img": "https://www.hotelpanoramapalace.it/images/large/116_piscina-hotel-panorama-palace-4.jpg?v=a831"},
            {"title": "Solarium Panoramico", "desc": "Area solarium con vista a 360° sul Golfo di Napoli, Capri e la Costiera Amalfitana", "img": "https://www.hotelpanoramapalace.it/images/large/119_solarium-hotel-panorama-palace-72_621.jpg?v=a831"},
            {"title": "Museo Archeologico", "desc": "Piccolo museo con reperti locali che raccontano la storia millenaria della Penisola Sorrentina", "img": CAMPANIA_IMAGES["italy"]},
            {"title": "Ristorante Tipico", "desc": "Cucina campana tradizionale con prodotti freschi del mercato locale e vini regionali", "img": CAMPANIA_IMAGES["pizza1"]},
            {"title": "Escursioni dalla Penisola", "desc": "Capri, Amalfi, Positano, Pompei: tutte le mete facilmente raggiungibili", "img": CAMPANIA_IMAGES["capri2"]},
        ],
        "highlights": ["Museo archeologico in hotel", "Vista panoramica 360°", "Piscina con solarium", "Piazza Scarpati Meta di Sorrento", "Autenticità senza folla turistica"],
        "cta": "Prenota la Tua Vista",
        "chatbot_msg": "Benvenuto al Panorama Palace! Sono il vostro assistente virtuale. Posso aiutarvi con prenotazioni, informazioni sul museo archeologico e suggerirvi le migliori escursioni dalla Penisola Sorrentina.",
    },
]


def generate_html(company):
    p = company["palette"]
    stats_html = "\n".join([
        f'<div class="stat-item"><span class="stat-num">{s["num"]}</span><span class="stat-label">{s["label"]}</span></div>'
        for s in company["stats"]
    ])
    services_html = "\n".join([
        f'''<div class="service-card">
  <div class="service-img" style="background-image:url('{s["img"]}')"></div>
  <div class="service-body">
    <h3>{s["title"]}</h3>
    <p>{s["desc"]}</p>
  </div>
</div>'''
        for s in company["services"]
    ])
    highlights_html = "\n".join([f'<li>✓ {h}</li>' for h in company["highlights"]])

    return f'''<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{company["name"]} × Experiences – Demo</title>
<style>
  :root {{
    --primary: {p["primary"]};
    --secondary: {p["secondary"]};
    --accent: {p["accent"]};
    --light: {p["light"]};
    --dark: {p["dark"]};
  }}
  * {{ margin:0; padding:0; box-sizing:border-box; }}
  body {{ font-family:'Segoe UI',system-ui,sans-serif; color:#333; background:#fff; }}

  /* NAVBAR */
  nav {{
    position:fixed; top:0; width:100%; background:rgba(255,255,255,0.97);
    backdrop-filter:blur(10px); z-index:100; padding:14px 40px;
    display:flex; justify-content:space-between; align-items:center;
    box-shadow:0 2px 20px rgba(0,0,0,0.08);
  }}
  .nav-brand {{ font-size:1.1rem; font-weight:700; color:var(--primary); }}
  .nav-badge {{
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:#fff; font-size:0.7rem; font-weight:700; padding:4px 10px;
    border-radius:20px; letter-spacing:0.5px;
  }}
  .nav-cta {{
    background:var(--accent); color:#fff; border:none; padding:9px 22px;
    border-radius:25px; font-weight:700; cursor:pointer; font-size:0.88rem;
    text-decoration:none; display:inline-block;
    transition: transform 0.2s, box-shadow 0.2s;
  }}
  .nav-cta:hover {{ transform:translateY(-1px); box-shadow:0 4px 15px rgba(0,0,0,0.2); }}

  /* HERO */
  .hero {{
    height:100vh; position:relative; overflow:hidden;
    background:var(--dark);
    display:flex; align-items:center; justify-content:center;
  }}
  .hero-bg {{
    position:absolute; inset:0;
    background:url('{company["hero_img"]}') center/cover no-repeat;
    opacity:0.55;
  }}
  .hero-content {{
    position:relative; z-index:2; text-align:center;
    color:#fff; padding:0 20px; max-width:800px;
  }}
  .hero-badge {{
    display:inline-block; background:rgba(255,255,255,0.2);
    backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.4);
    color:#fff; font-size:0.8rem; font-weight:600; padding:6px 16px;
    border-radius:20px; margin-bottom:20px; letter-spacing:1px; text-transform:uppercase;
  }}
  .hero-content h1 {{ font-size:clamp(2rem,5vw,3.8rem); font-weight:800; line-height:1.15; margin-bottom:18px; text-shadow:0 2px 20px rgba(0,0,0,0.4); }}
  .hero-content p {{ font-size:clamp(1rem,2.5vw,1.3rem); opacity:0.92; margin-bottom:32px; line-height:1.6; }}
  .hero-btns {{ display:flex; gap:16px; justify-content:center; flex-wrap:wrap; }}
  .btn-primary {{
    background:linear-gradient(135deg,var(--accent),var(--secondary));
    color:#fff; padding:15px 36px; border-radius:30px; font-weight:700;
    text-decoration:none; font-size:1rem; border:none; cursor:pointer;
    transition:transform 0.2s, box-shadow 0.2s;
  }}
  .btn-primary:hover {{ transform:translateY(-2px); box-shadow:0 6px 25px rgba(0,0,0,0.3); }}
  .btn-secondary {{
    background:rgba(255,255,255,0.15); backdrop-filter:blur(10px);
    color:#fff; padding:15px 36px; border-radius:30px; font-weight:600;
    text-decoration:none; font-size:1rem; border:2px solid rgba(255,255,255,0.5);
    transition:background 0.2s;
  }}
  .btn-secondary:hover {{ background:rgba(255,255,255,0.25); }}

  /* STATS BAR */
  .stats-bar {{
    background:linear-gradient(135deg,var(--primary),var(--dark));
    padding:40px; display:flex; justify-content:center;
    gap:60px; flex-wrap:wrap;
  }}
  .stat-item {{ text-align:center; color:#fff; }}
  .stat-num {{ display:block; font-size:2.2rem; font-weight:800; color:var(--accent); }}
  .stat-label {{ font-size:0.82rem; opacity:0.85; text-transform:uppercase; letter-spacing:0.5px; margin-top:4px; display:block; }}

  /* SECTION */
  .section {{ padding:80px 40px; max-width:1200px; margin:0 auto; }}
  .section-tag {{
    display:inline-block; background:var(--light); color:var(--primary);
    font-size:0.75rem; font-weight:700; padding:5px 14px;
    border-radius:15px; text-transform:uppercase; letter-spacing:1px; margin-bottom:14px;
  }}
  .section h2 {{ font-size:clamp(1.8rem,4vw,2.8rem); color:var(--dark); margin-bottom:16px; }}
  .section .lead {{ font-size:1.1rem; color:#555; line-height:1.7; max-width:700px; }}

  /* ABOUT */
  .about-grid {{ display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; }}
  @media(max-width:768px) {{ .about-grid {{ grid-template-columns:1fr; }} }}
  .about-img {{
    border-radius:16px; overflow:hidden; height:420px;
    box-shadow:0 20px 60px rgba(0,0,0,0.15);
  }}
  .about-img img {{ width:100%; height:100%; object-fit:cover; }}
  .highlights-list {{ list-style:none; margin-top:24px; }}
  .highlights-list li {{
    padding:10px 0; border-bottom:1px solid #eee; color:#444;
    font-size:0.95rem;
  }}
  .highlights-list li::before {{ color:var(--secondary); font-weight:700; }}

  /* SERVICES */
  .services-bg {{ background:var(--light); padding:80px 40px; }}
  .services-grid {{
    max-width:1200px; margin:0 auto;
    display:grid; grid-template-columns:repeat(auto-fit,minmax(340px,1fr));
    gap:28px; margin-top:48px;
  }}
  .service-card {{
    background:#fff; border-radius:16px; overflow:hidden;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
    transition:transform 0.3s, box-shadow 0.3s;
  }}
  .service-card:hover {{ transform:translateY(-6px); box-shadow:0 12px 40px rgba(0,0,0,0.14); }}
  .service-img {{
    height:220px; background-size:cover; background-position:center;
    background-color:var(--light);
  }}
  .service-body {{ padding:22px; }}
  .service-body h3 {{ font-size:1.1rem; color:var(--dark); margin-bottom:8px; }}
  .service-body p {{ font-size:0.9rem; color:#666; line-height:1.6; }}

  /* CHATBOT */
  .chatbot-section {{
    background:linear-gradient(135deg,var(--dark),var(--primary));
    padding:80px 40px; text-align:center; color:#fff;
  }}
  .chatbot-window {{
    max-width:600px; margin:40px auto 0;
    background:rgba(255,255,255,0.1); backdrop-filter:blur(10px);
    border-radius:20px; padding:28px; border:1px solid rgba(255,255,255,0.2);
    text-align:left;
  }}
  .chat-msg {{ display:flex; gap:14px; margin-bottom:16px; }}
  .chat-avatar {{
    width:40px; height:40px; border-radius:50%;
    background:linear-gradient(135deg,var(--accent),var(--secondary));
    display:flex; align-items:center; justify-content:center;
    font-size:1.2rem; flex-shrink:0;
  }}
  .chat-bubble {{
    background:rgba(255,255,255,0.15); border-radius:12px 12px 12px 4px;
    padding:12px 16px; font-size:0.9rem; line-height:1.5;
    border:1px solid rgba(255,255,255,0.15);
  }}
  .chat-input {{
    display:flex; gap:10px; margin-top:16px;
    background:rgba(255,255,255,0.1); border-radius:30px; padding:8px 12px;
    border:1px solid rgba(255,255,255,0.2);
  }}
  .chat-input input {{
    flex:1; background:none; border:none; color:#fff; outline:none;
    font-size:0.9rem; padding:4px;
  }}
  .chat-input input::placeholder {{ color:rgba(255,255,255,0.5); }}
  .chat-send {{
    background:var(--accent); color:#fff; border:none; border-radius:20px;
    padding:6px 18px; cursor:pointer; font-weight:600; font-size:0.85rem;
  }}

  /* PRICING */
  .pricing-section {{ padding:80px 40px; background:#fafafa; }}
  .pricing-grid {{
    max-width:1200px; margin:48px auto 0;
    display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:24px;
  }}
  .plan {{
    background:#fff; border-radius:16px; padding:36px 28px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
    border:2px solid transparent; text-align:center;
    transition:transform 0.3s, border-color 0.3s;
  }}
  .plan:hover {{ transform:translateY(-6px); border-color:var(--accent); }}
  .plan.featured {{
    background:linear-gradient(135deg,var(--primary),var(--dark));
    color:#fff; border-color:var(--accent);
    transform:scale(1.04);
  }}
  .plan-name {{ font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px; opacity:0.7; }}
  .plan-price {{ font-size:2.4rem; font-weight:800; color:var(--accent); margin:12px 0 4px; }}
  .plan.featured .plan-price {{ color:#ffd700; }}
  .plan-sub {{ font-size:0.82rem; opacity:0.65; margin-bottom:24px; }}
  .plan-features {{ list-style:none; font-size:0.88rem; text-align:left; margin-bottom:28px; }}
  .plan-features li {{ padding:7px 0; border-bottom:1px solid rgba(0,0,0,0.06); }}
  .plan.featured .plan-features li {{ border-color:rgba(255,255,255,0.15); }}
  .plan-features li::before {{ content:"✓ "; color:var(--secondary); font-weight:700; }}
  .plan.featured .plan-features li::before {{ color:#ffd700; }}
  .plan-btn {{
    display:block; padding:12px; border-radius:25px;
    background:var(--accent); color:#fff; text-align:center;
    font-weight:700; text-decoration:none; font-size:0.9rem;
    transition:opacity 0.2s;
  }}
  .plan-btn:hover {{ opacity:0.88; }}
  .plan.featured .plan-btn {{ background:#ffd700; color:#333; }}

  /* CTA */
  .cta-section {{
    background:linear-gradient(135deg,var(--secondary),var(--primary));
    padding:80px 40px; text-align:center; color:#fff;
  }}
  .cta-section h2 {{ font-size:2.4rem; font-weight:800; margin-bottom:16px; }}
  .cta-section p {{ font-size:1.1rem; opacity:0.9; max-width:600px; margin:0 auto 36px; }}

  /* FOOTER */
  footer {{
    background:var(--dark); color:rgba(255,255,255,0.7);
    padding:36px 40px; text-align:center; font-size:0.85rem;
  }}
  footer a {{ color:var(--accent); text-decoration:none; }}
  .demo-watermark {{
    display:inline-block; background:rgba(255,255,255,0.1);
    padding:6px 16px; border-radius:20px; font-size:0.75rem;
    margin-top:10px; border:1px solid rgba(255,255,255,0.15);
  }}
</style>
</head>
<body>

<nav>
  <span class="nav-brand">{company["name"]}</span>
  <span class="nav-badge">⚡ DEMO EXPERIENCES</span>
  <a href="https://naplesexperiences.com" class="nav-cta">{company["cta"]} →</a>
</nav>

<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-content">
    <div class="hero-badge">Demo Esclusiva · Experiences Srl</div>
    <h1>{company["name"]}</h1>
    <p>{company["tagline"]}</p>
    <div class="hero-btns">
      <a href="https://naplesexperiences.com" class="btn-primary">{company["cta"]}</a>
      <a href="#about" class="btn-secondary">Scopri di più ↓</a>
    </div>
  </div>
</section>

<div class="stats-bar">
  {stats_html}
</div>

<div id="about" class="section">
  <div class="about-grid">
    <div>
      <span class="section-tag">Chi Siamo</span>
      <h2>{company["name"]}</h2>
      <p class="lead">{company["description"]}</p>
      <ul class="highlights-list">
        {highlights_html}
      </ul>
    </div>
    <div class="about-img">
      <img src="{company["about_img"]}" alt="{company["name"]} — about" loading="lazy">
    </div>
  </div>
</div>

<div class="services-bg">
  <div style="max-width:1200px;margin:0 auto">
    <span class="section-tag">I Nostri Servizi</span>
    <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);color:var(--dark);margin-bottom:12px;">Cosa offriamo con Experiences</h2>
    <p class="lead" style="color:#555;">Ogni servizio potenziato dall'intelligenza artificiale per massimizzare prenotazioni e soddisfazione degli ospiti.</p>
  </div>
  <div class="services-grid">
    {services_html}
  </div>
</div>

<div class="chatbot-section">
  <span class="section-tag" style="background:rgba(255,255,255,0.2);color:#fff;">AI Chatbot 24/7</span>
  <h2 style="font-size:2.2rem;margin-bottom:16px;">Il vostro assistente non dorme mai</h2>
  <p style="opacity:0.85;font-size:1.05rem;max-width:600px;margin:0 auto;">Rispondi automaticamente a ogni richiesta — anche alle 3 di notte, anche in inglese, tedesco, francese.</p>
  <div class="chatbot-window">
    <div class="chat-msg">
      <div class="chat-avatar">🤖</div>
      <div class="chat-bubble">{company["chatbot_msg"]}</div>
    </div>
    <div class="chat-msg">
      <div class="chat-avatar" style="background:rgba(255,255,255,0.2)">👤</div>
      <div class="chat-bubble">Do you have availability for 2 adults next weekend?</div>
    </div>
    <div class="chat-msg">
      <div class="chat-avatar">🤖</div>
      <div class="chat-bubble">Of course! I can check availability for you right away. How many nights are you planning to stay, and do you prefer a sea view room?</div>
    </div>
    <div class="chat-input">
      <input type="text" placeholder="Scrivi un messaggio...">
      <button class="chat-send">Invia</button>
    </div>
  </div>
</div>

<div class="pricing-section">
  <div style="max-width:1200px;margin:0 auto;text-align:center">
    <span class="section-tag">Prezzi Trasparenti</span>
    <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);color:var(--dark);margin-bottom:12px;">Scegli il piano giusto per te</h2>
    <p style="color:#555;font-size:1.05rem;max-width:600px;margin:0 auto;">Nessun costo nascosto. Commissione solo sulle vendite generate.</p>
  </div>
  <div class="pricing-grid">
    <div class="plan">
      <div class="plan-name">Advanced</div>
      <div class="plan-price">€500</div>
      <div class="plan-sub">/ anno + X% sulle vendite</div>
      <ul class="plan-features">
        <li>Sito web ottimizzato</li>
        <li>Chatbot AI base</li>
        <li>SEO locale</li>
        <li>Dashboard analytics</li>
      </ul>
      <a href="https://naplesexperiences.com" class="plan-btn">Inizia ora</a>
    </div>
    <div class="plan featured">
      <div class="plan-name">⭐ Pro — Più Scelto</div>
      <div class="plan-price">€1.000</div>
      <div class="plan-sub">/ anno + X% sulle vendite</div>
      <ul class="plan-features">
        <li>Tutto di Advanced</li>
        <li>Chatbot AI multilingue</li>
        <li>CRM e automazioni</li>
        <li>Integrazione booking engine</li>
        <li>Supporto prioritario</li>
      </ul>
      <a href="https://naplesexperiences.com" class="plan-btn">Scegli Pro</a>
    </div>
    <div class="plan">
      <div class="plan-name">Base</div>
      <div class="plan-price">€1.400</div>
      <div class="plan-sub">/ anno + X% sulle vendite</div>
      <ul class="plan-features">
        <li>Tutto di Pro</li>
        <li>Account manager dedicato</li>
        <li>Campagne marketing</li>
        <li>Report mensili</li>
      </ul>
      <a href="https://naplesexperiences.com" class="plan-btn">Scopri Base</a>
    </div>
    <div class="plan">
      <div class="plan-name">Enterprise</div>
      <div class="plan-price">X%</div>
      <div class="plan-sub">solo sulle vendite generate</div>
      <ul class="plan-features">
        <li>Zero costi fissi</li>
        <li>Soluzione completa su misura</li>
        <li>Paghi solo i risultati</li>
        <li>Ideale per grandi strutture</li>
      </ul>
      <a href="https://naplesexperiences.com" class="plan-btn">Parla con noi</a>
    </div>
  </div>
</div>

<div class="cta-section">
  <h2>Pronto a trasformare il tuo business?</h2>
  <p>Questo sito è una demo personalizzata per {company["name"]}. Contattaci per attivare la tua versione reale.</p>
  <a href="https://naplesexperiences.com" class="btn-primary" style="font-size:1.1rem;padding:18px 48px;">{company["cta"]} →</a>
</div>

<footer>
  <p>Demo realizzata da <a href="https://naplesexperiences.com">Experiences Srl</a> per {company["name"]} · {company["city"]}</p>
  <div class="demo-watermark">⚡ Demo esclusiva — non pubblicata</div>
</footer>

</body>
</html>'''


def main():
    base = Path(__file__).parent / "demos"
    for company in COMPANIES:
        out_dir = base / f"{company['slug']}-demo-experiences"
        out_dir.mkdir(parents=True, exist_ok=True)
        html = generate_html(company)
        (out_dir / "index.html").write_text(html, encoding="utf-8")
        print(f"✓ {company['name']} → {out_dir.name}/index.html ({len(html):,} bytes)")
    print(f"\nDone: {len(COMPANIES)} demos generated.")


if __name__ == "__main__":
    main()
