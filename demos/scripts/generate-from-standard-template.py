#!/usr/bin/env python3
"""
Generate demo HTML e email da template standard (usando demo-hotel/tour-operator ufficiali).

Usage:
  python3 generate-from-standard-template.py <region_csv> <company_name>

Example:
  python3 generate-from-standard-template.py ../ricerca_clienti_csv/CSV_Cilento.csv "Hotel Myrtus"
"""

import csv
import sys
import os
from pathlib import Path
from datetime import datetime
import re

TEMPLATE_DIR = Path(__file__).parent.parent / "template-standard"
DEMOS_DIR = Path(__file__).parent.parent

# Colori default per categoria
COLOR_MAPPING = {
    "Hotel": {"primary": "#2c5aa0", "secondary": "#d4af37"},
    "Resort": {"primary": "#1e7b8f", "secondary": "#f4a460"},
    "Tour Operator": {"primary": "#8b4513", "secondary": "#daa520"},
    "DMC": {"primary": "#2f4f4f", "secondary": "#ff8c00"},
    "Agriturismo": {"primary": "#6b8e23", "secondary": "#daa520"},
    "B&B": {"primary": "#a0522d", "secondary": "#ffd700"},
}

def get_colors_for_category(categoria):
    """Restituisci colori basati sulla categoria."""
    categoria = categoria.strip() if categoria else ""
    for key, colors in COLOR_MAPPING.items():
        if key.lower() in categoria.lower():
            return colors
    return {"primary": "#0066cc", "secondary": "#ff9900"}

def get_demo_template_for_category(categoria):
    """Determina quale template demo usare."""
    categoria = categoria.strip().lower() if categoria else ""

    if "tour" in categoria or "dmc" in categoria:
        return "template-demo-tourop.html"
    else:
        # Default hotel per tutto il resto (hotel, resort, agriturismo, b&b)
        return "template-demo-hotel.html"

def get_email_template_for_category(categoria):
    """Determina quale template email usare."""
    categoria = categoria.strip().lower() if categoria else ""

    if "tour" in categoria or "dmc" in categoria:
        return "template-email-tourop.txt"
    elif "agriturismo" in categoria or "b&b" in categoria:
        return "template-email-agriturismo.txt"
    else:
        return "template-email-hotel.txt"

def slugify(text):
    """Converti testo in slug per URL."""
    text = text.lower().strip()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[-\s]+', '-', text)
    return text.rstrip('-')

def substitute_placeholders(template_text, data):
    """Sostituisci placeholder nel template."""
    result = template_text
    for key, value in data.items():
        placeholder = f"{{{{{key}}}}}"
        result = result.replace(placeholder, str(value or ""))
    return result

def generate_demo(company_data, output_dir):
    """Genera il file HTML demo dal template."""

    categoria = company_data.get('Categoria', '')
    template_file = TEMPLATE_DIR / get_demo_template_for_category(categoria)

    if not template_file.exists():
        print(f"❌ Template demo non trovato: {template_file}")
        return None, None

    with open(template_file, 'r', encoding='utf-8') as f:
        template_html = f.read()

    slug = slugify(company_data['Nome_Azienda'])
    colors = get_colors_for_category(categoria)

    # Prepara i dati
    data = {
        'HOTEL_NAME': company_data['Nome_Azienda'],
        'HOTEL_CATEGORY': company_data.get('Categoria', ''),
        'HOTEL_DESCRIPTION': company_data.get('Brief_Demo_OpenClaw', f"Scopri {company_data['Nome_Azienda']}"),
        'HOTEL_TAGLINE': f"{company_data.get('Categoria', '')} | {company_data.get('Citta', '')}",
        'HOTEL_URL': company_data.get('URL_Sito', ''),
        'HOTEL_EMAIL': company_data.get('Email', ''),
        'PRIMARY_COLOR': colors['primary'],
        'SECONDARY_COLOR': colors['secondary'],
        'HERO_IMAGE_URL': 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="800" height="400"%3E%3Crect fill="%23ddd" width="800" height="400"/%3E%3C/svg%3E',
        'ABOUT_IMAGE_URL': 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="300"%3E%3Crect fill="%23ddd" width="400" height="300"/%3E%3C/svg%3E',
        'SERVICE1_TITLE': 'Servizio Premium',
        'SERVICE1_DESC': 'Esperienza di qualità',
        'SERVICE1_IMG': 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="300" height="300"%3E%3Crect fill="%23ddd" width="300" height="300"/%3E%3C/svg%3E',
        'SERVICE2_TITLE': 'Servizio Esclusivo',
        'SERVICE2_DESC': 'Personalizzato su misura',
        'SERVICE2_IMG': 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="300" height="300"%3E%3Crect fill="%23ddd" width="300" height="300"/%3E%3C/svg%3E',
        'HIGHLIGHT1': '⭐ Qualità garantita',
        'HIGHLIGHT2': '🌍 Territorio autentico',
        'HIGHLIGHT3': '💼 Professionalità',
        'HIGHLIGHT4': '🎯 Risultati certificati',
        'HIGHLIGHT5': '✅ Esperienza consolidata',
        'CTA_TEXT': 'Contatta adesso',
        'CHATBOT_GREETING': f'Ciao! Scopri {company_data["Nome_Azienda"]}',
    }

    html_output = substitute_placeholders(template_html, data)

    # Crea directory
    output_path = output_dir / f"{slug}-experiences-standard"
    output_path.mkdir(parents=True, exist_ok=True)

    # Salva HTML
    html_file = output_path / "index.html"
    with open(html_file, 'w', encoding='utf-8') as f:
        f.write(html_output)

    demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-experiences-standard/"
    return demo_url, str(html_file)

def generate_email(company_data):
    """Genera email dal template."""

    categoria = company_data.get('Categoria', '')
    email_template_file = TEMPLATE_DIR / get_email_template_for_category(categoria)

    if not email_template_file.exists():
        return None

    with open(email_template_file, 'r', encoding='utf-8') as f:
        template_text = f.read()

    slug = slugify(company_data['Nome_Azienda'])
    demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-experiences-standard/"

    data = {
        'NOME_AZIENDA': company_data['Nome_Azienda'],
        'NOME_CONTATTO': company_data['Nome_Azienda'],
        'CATEGORIA': company_data.get('Categoria', 'partner'),
        'CITTA': company_data['Citta'],
        'PUNTO_FORZA_1': company_data.get('Categoria', 'eccellenza'),
        'PUNTO_FORZA_2': company_data.get('Citta', 'posizione'),
        'LINK_DEMO': demo_url,
        'NOME_MITTENTE': 'Experiences Srl Team',
        'FIRMA_MITTENTE': 'Experiences Srl',
        'CONTATTI_MITTENTE': 'info@experiences-srl.it | www.experiences-srl.it',
    }

    return substitute_placeholders(template_text, data)

def find_company_in_csv(csv_path, company_name):
    """Trova azienda nel CSV."""
    with open(csv_path, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        for row in reader:
            if row['Nome_Azienda'].strip().lower() == company_name.lower():
                return row
    return None

def main():
    if len(sys.argv) < 3:
        print("Usage: python3 generate-from-standard-template.py <csv_path> <company_name>")
        sys.exit(1)

    csv_path = Path(sys.argv[1])
    company_name = sys.argv[2]

    if not csv_path.exists():
        print(f"❌ CSV non trovato: {csv_path}")
        sys.exit(1)

    company_data = find_company_in_csv(csv_path, company_name)
    if not company_data:
        print(f"❌ Azienda non trovata: {company_name}")
        sys.exit(1)

    print(f"✓ Azienda trovata: {company_data['Nome_Azienda']}")

    output_dir = DEMOS_DIR
    output_dir.mkdir(parents=True, exist_ok=True)

    print("📝 Generazione demo HTML...")
    demo_url, demo_file = generate_demo(company_data, output_dir)
    if demo_url:
        print(f"✓ Demo creata: {demo_file}")
        print(f"  URL: {demo_url}")
    else:
        sys.exit(1)

    print("📧 Generazione email...")
    email_text = generate_email(company_data)
    if email_text:
        print(f"✓ Email template generata\n")
        print("="*70)
        print("PREVIEW EMAIL:")
        print("="*70)
        print(email_text)
        print("="*70)

    print(f"\n✅ Completato!")
    print(f"Demo: {demo_url}")

if __name__ == "__main__":
    main()
