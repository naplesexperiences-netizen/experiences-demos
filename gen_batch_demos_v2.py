#!/usr/bin/env python3
"""
Generatore di Demo in Batch v2 - Crea ulteriori 4 demo
"""

import csv
import json
from datetime import datetime
from pathlib import Path

def get_demo_slug(nome_azienda):
    """Genera slug sicuro per directory"""
    return nome_azienda.lower().replace(" ", "-").replace("&", "e").replace("'", "")[:50]

def generate_simple_hotel_html(azienda_data):
    """Genera HTML semplificato per hotel"""
    slug = get_demo_slug(azienda_data['Nome_Azienda'])
    demo_url_base = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-demo-experiences/"

    html = f"""<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{azienda_data['Nome_Azienda']} - Demo Experiences Srl</title>
    <meta name="description" content="{azienda_data['Nome_Azienda']} - {azienda_data['Categoria']}">
    <meta name="demo:tags" content="hotel,{azienda_data['Citta'].lower()}">
    <meta name="demo:category" content="hotel">
    <style>
        * {{ margin: 0; padding: 0; box-sizing: border-box; }}
        html {{ scroll-behavior: smooth; }}
        body {{ font-family: system-ui; line-height: 1.6; color: #333; }}

        .banner {{ background: #1a4d7a; color: white; padding: 14px 20px; text-align: center; font-size: 14px; font-weight: 600; }}
        .banner a {{ color: white; text-decoration: none; border-bottom: 1px dashed white; }}

        nav {{ background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }}
        .navbar {{ max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; }}
        .navbar-logo {{ font-weight: 700; font-size: 16px; color: #1a4d7a; }}

        .hero {{ background: linear-gradient(135deg, rgba(26, 77, 122, 0.8) 0%, rgba(212, 165, 116, 0.6) 100%), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1600&q=80'); background-size: cover; background-position: center; min-height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center; color: white; }}
        .hero h1 {{ font-size: 48px; font-weight: 800; margin-bottom: 20px; }}
        .hero p {{ font-size: 18px; margin-bottom: 30px; }}

        .btn {{ background: #1a4d7a; color: white; padding: 12px 28px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }}
        .btn:hover {{ background: #0f3250; transform: scale(1.05); }}

        .stats {{ background: #1a4d7a; color: white; padding: 40px 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; text-align: center; }}
        .stat-num {{ font-size: 36px; font-weight: 800; color: #d4a574; margin-bottom: 8px; }}

        .section {{ max-width: 1200px; margin: 80px auto; padding: 0 20px; }}
        .title {{ text-align: center; font-size: 36px; font-weight: 800; color: #1a4d7a; margin-bottom: 50px; }}

        .grid {{ display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }}
        .card {{ background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }}
        .card-img {{ width: 100%; height: 180px; object-fit: cover; }}
        .card-body {{ padding: 24px; }}
        .card-title {{ font-size: 18px; font-weight: 700; color: #1a4d7a; margin-bottom: 12px; }}
        .card-desc {{ font-size: 13px; color: #666; }}

        .cta {{ background: #d4a574; padding: 60px 20px; text-align: center; }}
        .cta h2 {{ color: white; font-size: 32px; margin-bottom: 20px; }}
        .cta p {{ color: rgba(255,255,255,0.9); margin-bottom: 30px; }}

        footer {{ background: #1a4d7a; color: white; text-align: center; padding: 30px 20px; font-size: 13px; }}
        footer a {{ color: #d4a574; text-decoration: none; }}

        @media (max-width: 768px) {{
            .hero h1 {{ font-size: 32px; }}
            .title {{ font-size: 26px; }}
        }}
    </style>
</head>
<body>
    <div class="banner">🎯 DEMO GRATUITO realizzato da <strong>Experiences Srl</strong> — <a href="https://naplesexperiences.com" target="_blank">naplesexperiences.com</a></div>

    <nav>
        <div class="navbar">
            <div class="navbar-logo">🏨 {azienda_data['Nome_Azienda']}</div>
            <button class="btn" onclick="document.getElementById('contact').scrollIntoView();">Prenota</button>
        </div>
    </nav>

    <section class="hero" id="home">
        <div>
            <h1>{azienda_data['Nome_Azienda']}</h1>
            <p>Il vostro rifugio esclusivo a {azienda_data['Citta']}</p>
            <button class="btn" onclick="document.getElementById('services').scrollIntoView();">Scopri di più</button>
        </div>
    </section>

    <section class="stats">
        <div>
            <div class="stat-num">20+</div>
            <div>Anni di Esperienza</div>
        </div>
        <div>
            <div class="stat-num">⭐⭐⭐⭐⭐</div>
            <div>Rating Ospiti</div>
        </div>
        <div>
            <div class="stat-num">100+</div>
            <div>Camere</div>
        </div>
        <div>
            <div class="stat-num">5000+</div>
            <div>Ospiti Annuali</div>
        </div>
    </section>

    <section class="section" id="services">
        <h2 class="title">Servizi Esclusivi</h2>
        <div class="grid">
            <div class="card">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80" alt="Camere" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Camere Lusso</h3>
                    <p class="card-desc">Suite panoramiche con vista mare, aria condizionata e servizi premium</p>
                </div>
            </div>

            <div class="card">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80" alt="Piscina" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Piscina</h3>
                    <p class="card-desc">Piscina riscaldata con veduta sul mare e accesso spiaggia privata</p>
                </div>
            </div>

            <div class="card">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80" alt="Spa" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Spa & Benessere</h3>
                    <p class="card-desc">Massaggi e trattamenti con prodotti naturali per il vostro relax</p>
                </div>
            </div>

            <div class="card">
                <img src="https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=800&q=80" alt="Ristorante" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Ristorante</h3>
                    <p class="card-desc">Cucina mediterannea con ingredienti locali e vista sul golfo</p>
                </div>
            </div>

            <div class="card">
                <img src="https://images.unsplash.com/photo-1530092285049-1c42085fd395?w=800&q=80" alt="Spiaggia" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Spiaggia Privata</h3>
                    <p class="card-desc">Accesso esclusivo a sabbia bianca con lettini e ombrelloni</p>
                </div>
            </div>

            <div class="card">
                <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&q=80" alt="Escursioni" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Tour & Escursioni</h3>
                    <p class="card-desc">Visite a Capri, Pompeip, Costiera Amalfitana con guida privata</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <h2 class="title">Piani Experiences Srl</h2>
        <div class="grid">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Base</h3>
                    <p style="font-size:28px; font-weight:800; color:#d4a574; margin:15px 0;">€1.400</p>
                    <p style="font-size:12px; color:#999; margin-bottom:20px;">/anno</p>
                    <ul style="list-style:none; font-size:13px; color:#666; text-align:left;">
                        <li style="padding:8px 0;">✓ Sito mobile-first</li>
                        <li style="padding:8px 0;">✓ Booking integrato</li>
                        <li style="padding:8px 0;">✓ SEO ottimizzazione</li>
                    </ul>
                    <button class="btn" style="margin-top:20px; width:100%;">Scegli</button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Pro ⭐</h3>
                    <p style="font-size:28px; font-weight:800; color:#d4a574; margin:15px 0;">€2.500</p>
                    <p style="font-size:12px; color:#999; margin-bottom:20px;">/anno</p>
                    <ul style="list-style:none; font-size:13px; color:#666; text-align:left;">
                        <li style="padding:8px 0;">✓ Tutto del Base</li>
                        <li style="padding:8px 0;">✓ Chatbot AI</li>
                        <li style="padding:8px 0;">✓ Google Ads gestiti</li>
                    </ul>
                    <button class="btn" style="margin-top:20px; width:100%; background:#d4a574; color:#1a4d7a;">Scegli</button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Enterprise</h3>
                    <p style="font-size:28px; font-weight:800; color:#d4a574; margin:15px 0;">Custom</p>
                    <p style="font-size:12px; color:#999; margin-bottom:20px;">/anno</p>
                    <ul style="list-style:none; font-size:13px; color:#666; text-align:left;">
                        <li style="padding:8px 0;">✓ Soluzione white-label</li>
                        <li style="padding:8px 0;">✓ Account manager</li>
                        <li style="padding:8px 0;">✓ Support 24/7</li>
                    </ul>
                    <button class="btn" style="margin-top:20px; width:100%;">Contatta</button>
                </div>
            </div>
        </div>
    </section>

    <section class="cta" id="contact">
        <h2>Pronto a Trasformare il Tuo Hotel?</h2>
        <p>Aumenta le prenotazioni e riduci i costi operativi con Experiences Srl</p>
        <a href="https://wa.me/393926917657" class="btn" style="background:white; color:#1a4d7a;">💬 Contattaci su WhatsApp</a>
    </section>

    <footer>
        <p>© 2024 {azienda_data['Nome_Azienda']}. Demo realizzato da <a href="https://naplesexperiences.com" target="_blank"><strong>Experiences Srl</strong></a></p>
        <p style="margin-top:10px; font-size:12px;">📞 <a href="https://wa.me/393926917657" target="_blank">+39 392 691 7657</a> | naplesexperiences.com</p>
    </footer>
</body>
</html>"""

    return html

def main():
    csv_file = 'demos/ricerca_clienti_csv/CSV_Golfo_Napoli_Sorrento.csv'

    with open(csv_file, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        rows = list(reader)

    # Aziende rimanenti per il ciclo di 10
    remaining = [
        "Grand Hotel Cocumella",
        "Grand Hotel Cesare Augusto",
        "Grand Hotel La Favorita",
        "Hotel Continental Sorrento"
    ]

    created = []

    for company_name in remaining:
        # Trova nel CSV
        azienda_row = None
        for row in rows:
            if row['Nome_Azienda'] == company_name:
                azienda_row = row
                break

        if not azienda_row:
            print(f"❌ Non trovato: {company_name}")
            continue

        slug = get_demo_slug(company_name)
        demo_dir = f'demos/{slug}-demo-experiences'
        Path(demo_dir).mkdir(parents=True, exist_ok=True)

        html = generate_simple_hotel_html(azienda_row)

        with open(f'{demo_dir}/index.html', 'w', encoding='utf-8') as f:
            f.write(html)

        # Update CSV
        demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-demo-experiences/"
        azienda_row['Link_Demo'] = demo_url
        azienda_row['Data_Contatto'] = datetime.now().strftime('%Y-%m-%d')

        created.append(company_name)
        print(f"✓ {company_name}")

    # Riscrivi CSV
    with open(csv_file, 'w', newline='', encoding='utf-8') as f:
        fieldnames = list(rows[0].keys())
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)

    print(f"\n✓ {len(created)} demo create per il ciclo 1 (totale 10)")

if __name__ == '__main__':
    main()
