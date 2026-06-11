#!/usr/bin/env python3
"""
Generatore di Demo in Batch per clienti Campania
Crea personalizzazioni automatiche per hotel e tour operator
"""

import csv
import json
import os
from datetime import datetime
from pathlib import Path
import sys

# Template base colori per categorie
COLOR_SCHEMES = {
    "hotel": {
        "primary": "#1a4d7a",      # Blu scuro elegante
        "secondary": "#d4a574",     # Oro/beige
        "accent": "#f0e6d2",        # Crema
        "light_primary": "#2d6fa0"
    },
    "tour_operator": {
        "primary": "#1e5a8b",       # Blu navale
        "secondary": "#4ab2e8",     # Azzurro cielo
        "accent": "#f5f5f5",        # Bianco
        "light_primary": "#155a99"
    }
}

# Emoji per categoria
CATEGORY_EMOJI = {
    "hotel": "🏨",
    "tour_operator": "✈️",
    "dmv": "🚗",
    "resort": "🏖️"
}

def get_category_type(categoria):
    """Determina se è hotel o tour operator"""
    categoria_lower = categoria.lower()
    if any(word in categoria_lower for word in ["hotel", "resort", "inn", "spa"]):
        return "hotel"
    elif any(word in categoria_lower for word in ["tour", "operator", "dmc", "travel"]):
        return "tour_operator"
    return "hotel"  # default

def get_demo_slug(nome_azienda):
    """Genera slug sicuro per directory"""
    return nome_azienda.lower().replace(" ", "-").replace("&", "e").replace("'", "")[:50]

def generate_hotel_demo_html(azienda_data, colors):
    """Genera HTML per hotel"""
    emoji = "🏨"
    tagline = f"Scopri il lusso e l'eleganza a {azienda_data['Citta']}"
    services = [
        ("Camere Lusso", "Suite panoramiche con vista mare, aria condizionata e servizi premium"),
        ("Spa & Wellness", "Massaggi, trattamenti viso e percorsi benessere con prodotti naturali"),
        ("Ristorante Gourmet", f"Cucina di qualità con veduta sul Golfo e sul territorio di {azienda_data['Citta']}"),
        ("Piscina", "Piscina riscaldata con accesso privato o veduta panoramica"),
        ("Spiaggia Privata", "Accesso esclusivo alla spiaggia con lettini e servizi dedicati"),
        ("Concierge 24/7", "Assistenza personalizzata per escursioni, trasporti e prenotazioni")
    ]
    stats = [
        ("15+", "Anni di Ospitalità"),
        ("⭐⭐⭐⭐⭐", "Valutazione Ospiti"),
        ("50+", "Camere & Suite"),
        ("2000+", "Ospiti Annuali")
    ]

    html = f"""<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{azienda_data['Nome_Azienda']} - Demo Experiences Srl</title>
    <meta name="description" content="{azienda_data['Nome_Azienda']} - {azienda_data['Categoria']}">
    <meta name="demo:tags" content="hotel,{azienda_data['Citta'].lower()},luxury">
    <meta name="demo:category" content="hotel">
    <style>
        * {{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }}

        html {{
            scroll-behavior: smooth;
        }}

        body {{
            font-family: system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: #333;
        }}

        .banner-top {{
            background: linear-gradient(90deg, {colors['primary']} 0%, {colors['light_primary']} 100%);
            color: white;
            text-align: center;
            padding: 14px 20px;
            font-size: 14px;
            font-weight: 600;
        }}

        nav {{
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }}

        .navbar-container {{
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
        }}

        .navbar-logo {{
            font-weight: 700;
            font-size: 16px;
            color: {colors['primary']};
        }}

        .navbar-menu {{
            display: flex;
            gap: 30px;
            list-style: none;
        }}

        .navbar-menu a {{
            color: #333;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }}

        .btn-cta {{
            background: linear-gradient(135deg, {colors['primary']} 0%, {colors['light_primary']} 100%);
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
        }}

        .hero {{
            background: linear-gradient(135deg, rgba(26, 77, 122, 0.75) 0%, rgba(45, 111, 160, 0.6) 100%),
                        url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1600&q=80');
            background-size: cover;
            background-position: center;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }}

        .hero-content {{
            max-width: 700px;
            padding: 40px 20px;
        }}

        .hero h1 {{
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
        }}

        .hero p {{
            font-size: 18px;
            margin-bottom: 30px;
            font-weight: 300;
        }}

        .stats-bar {{
            background: {colors['primary']};
            color: white;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
        }}

        .stat-number {{
            font-size: 36px;
            font-weight: 800;
            color: {colors['secondary']};
            margin-bottom: 8px;
        }}

        .services-section {{
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
        }}

        .section-title {{
            text-align: center;
            font-size: 36px;
            font-weight: 800;
            color: {colors['primary']};
            margin-bottom: 50px;
        }}

        .services-grid {{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }}

        .service-card {{
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }}

        .service-card-image {{
            width: 100%;
            height: 180px;
            object-fit: cover;
        }}

        .service-card-content {{
            padding: 24px;
        }}

        .service-card-title {{
            font-size: 18px;
            font-weight: 700;
            color: {colors['primary']};
            margin-bottom: 12px;
        }}

        .ai-section {{
            background: linear-gradient(135deg, {colors['primary']} 0%, {colors['light_primary']} 100%);
            padding: 60px 20px;
            margin: 80px 0;
        }}

        .ai-section h2 {{
            color: white;
            font-size: 32px;
            margin-bottom: 20px;
        }}

        .ai-section p {{
            color: rgba(255,255,255,0.9);
            font-size: 15px;
            margin-bottom: 30px;
        }}

        .pricing-section {{
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
        }}

        .pricing-title {{
            text-align: center;
            font-size: 36px;
            font-weight: 800;
            color: {colors['primary']};
            margin-bottom: 50px;
        }}

        .pricing-grid {{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }}

        .pricing-card {{
            background: white;
            border-radius: 14px;
            padding: 30px 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-top: 4px solid {colors['secondary']};
        }}

        .pricing-name {{
            font-size: 18px;
            font-weight: 700;
            color: {colors['primary']};
            margin-bottom: 15px;
        }}

        .pricing-price {{
            font-size: 32px;
            font-weight: 800;
            color: {colors['secondary']};
            margin-bottom: 8px;
        }}

        .cta-final {{
            background: linear-gradient(135deg, {colors['secondary']} 0%, {colors['primary']} 100%);
            padding: 60px 20px;
            text-align: center;
            margin: 80px 0 0 0;
        }}

        .cta-final h2 {{
            color: white;
            font-size: 32px;
            margin-bottom: 20px;
        }}

        footer {{
            background: {colors['primary']};
            color: white;
            text-align: center;
            padding: 30px 20px;
            font-size: 13px;
        }}

        @media (max-width: 768px) {{
            .hero h1 {{
                font-size: 32px;
            }}
            .navbar-menu {{
                display: none;
            }}
        }}
    </style>
</head>
<body>
    <div class="banner-top">
        🎯 DEMO GRATUITO realizzato da <strong>Experiences Srl</strong> — <a href="https://naplesexperiences.com" target="_blank" style="color:white;">naplesexperiences.com</a>
    </div>

    <nav>
        <div class="navbar-container">
            <div class="navbar-logo">{emoji} {azienda_data['Nome_Azienda']}</div>
            <ul class="navbar-menu">
                <li><a href="#home">Home</a></li>
                <li><a href="#services">Servizi</a></li>
                <li><a href="#pricing">Prezzi</a></li>
            </ul>
            <button class="btn-cta">Prenota Ora</button>
        </div>
    </nav>

    <section class="hero" id="home">
        <div class="hero-content">
            <h1>{azienda_data['Nome_Azienda']}</h1>
            <p>{tagline}</p>
            <button class="btn-cta" onclick="document.getElementById('services').scrollIntoView();">Scopri i Servizi</button>
        </div>
    </section>

    <section class="stats-bar">
"""

    for num, label in stats:
        html += f"""        <div class="stat-item">
            <div class="stat-number">{num}</div>
            <div class="stat-label">{label}</div>
        </div>
"""

    html += """    </section>

    <section class="services-section" id="services">
        <h2 class="section-title">Servizi Esclusivi</h2>
        <div class="services-grid">
"""

    for service_title, service_desc in services:
        html += f"""            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80" alt="{service_title}" class="service-card-image" loading="lazy">
                <div class="service-card-content">
                    <h3 class="service-card-title">{service_title}</h3>
                    <p class="service-card-desc">{service_desc}</p>
                </div>
            </div>
"""

    html += f"""        </div>
    </section>

    <section class="ai-section">
        <div class="ai-container" style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:50px; align-items:center;">
            <div>
                <h2>Assistente AI 24/7 Multilingua</h2>
                <p>Rispondi istantaneamente alle prenotazioni in italiano, inglese, tedesco, francese e spagnolo. Aumenta le conversioni e riduci il carico del team.</p>
                <button class="btn-cta" onclick="window.open('https://wa.me/393926917657', '_blank');">Attiva il Chatbot</button>
            </div>
            <div style="background:white; border-radius:14px; padding:20px; box-shadow:0 8px 30px rgba(0,0,0,0.15);">
                <div style="display:flex; gap:10px; margin-bottom:20px; padding-bottom:15px; border-bottom:1px solid #eee;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg, {colors['secondary']} 0%, {colors['primary']} 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:12px;">AI</div>
                    <div>
                        <div style="font-size:13px; font-weight:600; color:{colors['primary']};">Hotel Assistant</div>
                        <div style="font-size:11px; color:#888;">Online</div>
                    </div>
                </div>
                <div style="font-size:12px; color:#666; margin-bottom:15px;">
                    <div style="background:#f0f0f0; padding:10px 14px; border-radius:8px; margin-bottom:12px;">Ciao! Quale camera preferisci?</div>
                    <div style="background:{colors['primary']}; color:white; padding:10px 14px; border-radius:8px; margin-bottom:12px; text-align:right;">Suite con vista mare</div>
                    <div style="background:#f0f0f0; padding:10px 14px; border-radius:8px;">Perfetto! Disponibili 15-30 luglio...</div>
                </div>
            </div>
        </div>
    </section>

    <section class="pricing-section" id="pricing">
        <h2 class="pricing-title">Piani Experiences Srl</h2>
        <div class="pricing-grid">
            <div class="pricing-card">
                <div class="pricing-name">Base</div>
                <div class="pricing-price">€1.400</div>
                <div style="font-size:12px; color:#999; margin-bottom:20px;">/anno</div>
                <ul style="list-style:none; font-size:13px; color:#666;">
                    <li style="padding:8px 0; border-bottom:1px solid #f0f0f0;">✓ Sito mobile-first</li>
                    <li style="padding:8px 0; border-bottom:1px solid #f0f0f0;">✓ Booking integrato</li>
                    <li style="padding:8px 0;">✓ SEO ottimizzazione</li>
                </ul>
                <button class="btn-cta" style="margin-top:20px; width:100%;">Scegli</button>
            </div>
            <div class="pricing-card">
                <div class="pricing-name">Pro</div>
                <div class="pricing-price">€2.500</div>
                <div style="font-size:12px; color:#999; margin-bottom:20px;">/anno</div>
                <ul style="list-style:none; font-size:13px; color:#666;">
                    <li style="padding:8px 0; border-bottom:1px solid #f0f0f0;">✓ Tutto del Base</li>
                    <li style="padding:8px 0; border-bottom:1px solid #f0f0f0;">✓ Chatbot AI</li>
                    <li style="padding:8px 0;">✓ Google Ads</li>
                </ul>
                <button class="btn-cta" style="margin-top:20px; width:100%;">Scegli</button>
            </div>
        </div>
    </section>

    <section class="cta-final">
        <h2>Pronto a Trasformare il Tuo {azienda_data['Categoria']}?</h2>
        <p>Aumenta le prenotazioni e riduci i costi operativi</p>
        <a href="https://wa.me/393926917657" style="background:white; color:{colors['primary']}; padding:14px 40px; border-radius:8px; text-decoration:none; font-weight:700; display:inline-block;">💬 Contattaci su WhatsApp</a>
    </section>

    <footer>
        <p>© 2024 {azienda_data['Nome_Azienda']}. Demo realizzato da <a href="https://naplesexperiences.com" target="_blank" style="color:{colors['secondary']};"><strong>Experiences Srl</strong></a></p>
    </footer>
</body>
</html>"""

    return html

def main():
    csv_file = 'demos/ricerca_clienti_csv/CSV_Golfo_Napoli_Sorrento.csv'

    with open(csv_file, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        rows = list(reader)

    # Leggi RESUMEPOINT.json per sapere quali aziende fare
    with open('demos/ricerca_clienti_csv/RESUMEPOINT.json', 'r', encoding='utf-8') as f:
        resume_data = json.load(f)

    # Prendi le prossime 9 aziende (abbiamo già fatto la prima)
    next_companies = resume_data['next_companies_to_process'][:9]

    created_demos = []

    for company_info in next_companies:
        # Trova la riga nel CSV
        row_num = company_info['row_number']
        azienda_row = None

        for i, row in enumerate(rows):
            if row['Nome_Azienda'] == company_info['name']:
                azienda_row = row
                break

        if not azienda_row:
            print(f"❌ Non trovata: {company_info['name']}")
            continue

        # Determina categoria
        cat_type = get_category_type(azienda_row['Categoria'])
        colors = COLOR_SCHEMES[cat_type]

        # Genera slug
        slug = get_demo_slug(company_info['name'])
        demo_dir = f'demos/{slug}-demo-experiences'

        # Crea directory
        Path(demo_dir).mkdir(parents=True, exist_ok=True)

        # Genera HTML
        html_content = generate_hotel_demo_html(azienda_row, colors)

        # Salva HTML
        with open(f'{demo_dir}/index.html', 'w', encoding='utf-8') as f:
            f.write(html_content)

        # Aggiorna CSV
        demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-demo-experiences/"
        azienda_row['Link_Demo'] = demo_url
        azienda_row['Data_Contatto'] = datetime.now().strftime('%Y-%m-%d')

        created_demos.append({
            "name": company_info['name'],
            "slug": slug,
            "category": azienda_row['Categoria'],
            "demo_url": demo_url
        })

        print(f"✓ {company_info['name']} -> {slug}")

    # Riscrivi CSV
    with open(csv_file, 'w', newline='', encoding='utf-8') as f:
        fieldnames = reader.fieldnames if hasattr(reader, 'fieldnames') else list(rows[0].keys())
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)

    print(f"\n✓ {len(created_demos)} demo create")
    return created_demos

if __name__ == '__main__':
    created = main()
    for d in created:
        print(f"  {d['name']}: {d['demo_url']}")
