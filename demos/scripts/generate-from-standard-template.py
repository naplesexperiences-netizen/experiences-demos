#!/usr/bin/env python3
"""
Generate demo HTML and email from standard templates.

Usage:
  python3 generate-from-standard-template.py <region_csv> <company_name> [--force]

Example:
  python3 generate-from-standard-template.py ../ricerca_clienti_csv/CSV_Cilento.csv "Hotel Myrtus"
"""

import csv
import sys
import os
from pathlib import Path
from datetime import datetime
import re
import argparse

TEMPLATE_DIR = Path(__file__).parent.parent / "template-standard"
DEMOS_DIR = Path(__file__).parent.parent

# Mappatura categoria -> template email
CATEGORY_TO_EMAIL_TEMPLATE = {
    "Hotel": "template-email-hotel.txt",
    "Resort": "template-email-hotel.txt",
    "Struttura ricettiva": "template-email-hotel.txt",
    "Tour Operator": "template-email-tourop.txt",
    "DMC": "template-email-tourop.txt",
    "Agriturismo": "template-email-agriturismo.txt",
    "B&B": "template-email-agriturismo.txt",
}

def get_email_template_for_category(categoria):
    """Determina quale template email usare basandosi sulla categoria."""
    categoria = categoria.strip() if categoria else ""

    for key, template_file in CATEGORY_TO_EMAIL_TEMPLATE.items():
        if key.lower() in categoria.lower():
            return template_file

    # Default a hotel
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

    # Leggi il template demo
    template_file = TEMPLATE_DIR / "template-demo.html"
    with open(template_file, 'r', encoding='utf-8') as f:
        template_html = f.read()

    # Prepara i dati per la sostituzione
    slug = slugify(company_data['Nome_Azienda'])
    demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-experiences-standard/"

    data = {
        'NOME_AZIENDA': company_data['Nome_Azienda'],
        'CATEGORIA': company_data['Categoria'],
        'CITTA': company_data['Citta'],
        'DATA_GENERAZIONE': datetime.now().strftime("%d/%m/%Y"),
        'BRIEF_DESCRIZIONE': company_data.get('Brief_Demo_OpenClaw', f"Scopri {company_data['Nome_Azienda']} nel cuore di {company_data['Citta']}."),
        'PUNTO_FORZA_1': company_data.get('Categoria', 'qualità').capitalize(),
        'PUNTO_FORZA_2': company_data.get('Citta', 'territorio').capitalize(),
    }

    # Sostituisci i placeholder
    html_output = substitute_placeholders(template_html, data)

    # Crea la directory se non esiste
    output_path = output_dir / f"{slug}-experiences-standard"
    output_path.mkdir(parents=True, exist_ok=True)

    # Salva il file HTML
    html_file = output_path / "index.html"
    with open(html_file, 'w', encoding='utf-8') as f:
        f.write(html_output)

    return str(demo_url), str(html_file)

def generate_email(company_data, category_mapping):
    """Genera il testo email dal template appropriato."""

    # Determina quale template email usare
    categoria = company_data.get('Categoria', '')
    email_template_file = get_email_template_for_category(categoria)

    template_path = TEMPLATE_DIR / email_template_file
    if not template_path.exists():
        print(f"⚠️  Template email non trovato: {email_template_file}", file=sys.stderr)
        return None

    with open(template_path, 'r', encoding='utf-8') as f:
        template_text = f.read()

    # Prepara i dati per la sostituzione
    slug = slugify(company_data['Nome_Azienda'])
    demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-experiences-standard/"

    data = {
        'NOME_AZIENDA': company_data['Nome_Azienda'],
        'NOME_CONTATTO': company_data['Nome_Azienda'],  # Default, da customizzare
        'CATEGORIA': company_data.get('Categoria', 'partner'),
        'CITTA': company_data['Citta'],
        'PUNTO_FORZA_1': company_data.get('Categoria', 'eccellenza'),
        'PUNTO_FORZA_2': company_data.get('Citta', 'posizione strategica'),
        'LINK_DEMO': demo_url,
        'NOME_MITTENTE': 'Experiences Srl Team',
        'FIRMA_MITTENTE': 'Experiences Srl',
        'CONTATTI_MITTENTE': 'info@experiences-srl.it | www.experiences-srl.it',
    }

    # Sostituisci i placeholder
    email_output = substitute_placeholders(template_text, data)

    return email_output

def find_company_in_csv(csv_path, company_name):
    """Trova un'azienda nel CSV regionale."""
    with open(csv_path, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        for row in reader:
            if row['Nome_Azienda'].strip().lower() == company_name.lower():
                return row
    return None

def main():
    parser = argparse.ArgumentParser(description="Genera demo HTML e email da template standard")
    parser.add_argument("csv_file", help="Path al CSV regionale")
    parser.add_argument("company_name", help="Nome azienda da template")
    parser.add_argument("--force", action="store_true", help="Sovrascrivi file esistenti")
    parser.add_argument("--output-dir", default=None, help="Directory output custom")

    args = parser.parse_args()

    # Verifica che il CSV esista
    csv_path = Path(args.csv_file)
    if not csv_path.exists():
        print(f"❌ CSV non trovato: {csv_path}")
        sys.exit(1)

    # Trova l'azienda nel CSV
    company_data = find_company_in_csv(csv_path, args.company_name)
    if not company_data:
        print(f"❌ Azienda non trovata nel CSV: {args.company_name}")
        sys.exit(1)

    print(f"✓ Azienda trovata: {company_data['Nome_Azienda']}")

    # Determina la directory output
    if args.output_dir:
        output_dir = Path(args.output_dir)
    else:
        output_dir = DEMOS_DIR

    output_dir.mkdir(parents=True, exist_ok=True)

    # Genera la demo HTML
    print("📝 Generazione demo HTML...")
    try:
        demo_url, demo_file = generate_demo(company_data, output_dir)
        print(f"✓ Demo HTML creato: {demo_file}")
        print(f"  URL: {demo_url}")
    except Exception as e:
        print(f"❌ Errore nella generazione demo: {e}")
        sys.exit(1)

    # Genera l'email
    print("📧 Generazione email...")
    try:
        email_text = generate_email(company_data, CATEGORY_TO_EMAIL_TEMPLATE)
        if email_text:
            print(f"✓ Email template generata")
            print("\n" + "="*70)
            print("PREVIEW EMAIL:")
            print("="*70)
            print(email_text)
            print("="*70)
        else:
            print("⚠️  Email non generata (template non trovato)")
    except Exception as e:
        print(f"❌ Errore nella generazione email: {e}")
        sys.exit(1)

    print("\n✅ Completato!")
    print(f"\nRiassunto:")
    print(f"  Azienda: {company_data['Nome_Azienda']}")
    print(f"  Categoria: {company_data['Categoria']}")
    print(f"  Città: {company_data['Citta']}")
    print(f"  Demo: {demo_url}")

if __name__ == "__main__":
    main()
