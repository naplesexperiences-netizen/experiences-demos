#!/usr/bin/env python3
"""
Analizzatore Multi-CSV Campania - Status delle Aziende
Analizza 5 CSV regionali per tracciare:
- Aziende già contattate (email inviate nell'ultima settimana)
- Aziende con bozze di email in corso
- Aziende con demo link già creato
"""

import csv
import json
from pathlib import Path
from collections import defaultdict
from datetime import datetime, timedelta
import re

# Directory paths
CSV_DIR = Path(__file__).parent
DEMOS_DIR = CSV_DIR.parent

# Load the RESUMEPOINT.json to get demo data
with open(CSV_DIR / "RESUMEPOINT.json") as f:
    resume_data = json.load(f)

# Create a mapping of company names to demo URLs from RESUMEPOINT
demos_map = {}
for company in resume_data.get("processed_companies", []):
    demos_map[company["name"].lower()] = {
        "demo_url": company["demo_url"],
        "cycle": company.get("cycle"),
        "batch": company.get("batch")
    }

def normalize_company_name(name):
    """Normalize company name for matching"""
    if not name:
        return ""
    # Remove common suffixes and normalize
    name = name.lower().strip()
    # Remove extra spaces
    name = re.sub(r'\s+', ' ', name)
    return name

def check_demo_folder(company_name):
    """Check if demo folder exists for a company"""
    normalized = normalize_company_name(company_name)
    if normalized in demos_map:
        return demos_map[normalized]

    # Also check if folder exists in demos directory
    demo_folders = list(DEMOS_DIR.glob("*demo-experiences*"))
    for folder in demo_folders:
        folder_name = folder.name.lower().replace("-demo-experiences", "").replace("-", " ")
        if normalize_company_name(company_name) in folder_name or folder_name in normalize_company_name(company_name):
            return {
                "demo_url": f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{folder.name}/",
                "cycle": "existing",
                "batch": "existing"
            }

    return None

def load_csv_file(filepath):
    """Load and parse a CSV file"""
    companies = []
    try:
        with open(filepath, encoding='utf-8') as f:
            reader = csv.DictReader(f)
            for row in reader:
                companies.append(row)
    except Exception as e:
        print(f"Error loading {filepath}: {e}")
    return companies

def main():
    csv_files = [
        "CSV_Cilento.csv",
        "CSV_Costiera_Amalfitana.csv",
        "CSV_Golfo_Napoli_Sorrento_NEW.csv",
        "CSV_Isole_del_Golfo.csv",
        "CSV_Salerno.csv"
    ]

    # Load all CSVs
    all_companies = []
    csv_stats = {}

    for csv_file in csv_files:
        filepath = CSV_DIR / csv_file
        if filepath.exists():
            companies = load_csv_file(filepath)
            all_companies.extend(companies)
            csv_stats[csv_file] = len(companies)
            print(f"✓ {csv_file}: {len(companies)} aziende")
        else:
            print(f"✗ {csv_file}: Non trovato")

    # Analyze companies
    analysis = {
        "totale_aziende": len(all_companies),
        "csv_files": csv_stats,
        "aziende_con_demo": [],
        "aziende_senza_contatto": [],
        "aziende_con_email_valida": [],
        "riassunto_per_regione": {}
    }

    # Group by region (derived from CSV filename)
    regions = {
        "Cilento": [],
        "Costiera Amalfitana": [],
        "Golfo di Napoli / Sorrento": [],
        "Isole del Golfo": [],
        "Salerno": []
    }

    region_mapping = {
        "CSV_Cilento.csv": "Cilento",
        "CSV_Costiera_Amalfitana.csv": "Costiera Amalfitana",
        "CSV_Golfo_Napoli_Sorrento_NEW.csv": "Golfo di Napoli / Sorrento",
        "CSV_Isole_del_Golfo.csv": "Isole del Golfo",
        "CSV_Salerno.csv": "Salerno"
    }

    # Process each company
    for idx, company in enumerate(all_companies, 1):
        nome = company.get("Nome_Azienda", "").strip()
        email = company.get("Email", "").strip()
        citta = company.get("Citta", "").strip()
        categoria = company.get("Categoria", "").strip()
        priorita = company.get("Priorita", "").strip()

        # Determine region
        region = None
        for csv_file, region_name in region_mapping.items():
            if csv_file in [CSV_DIR.joinpath(cf).name for cf in csv_files]:
                region = region_name

        # Check for demo
        demo_info = check_demo_folder(nome)

        company_data = {
            "id": idx,
            "nome": nome,
            "email": email,
            "citta": citta,
            "categoria": categoria,
            "priorita": priorita,
            "demo": demo_info is not None,
            "demo_url": demo_info["demo_url"] if demo_info else None,
            "region": region
        }

        # Categorize
        if demo_info:
            analysis["aziende_con_demo"].append(company_data)
        else:
            analysis["aziende_senza_contatto"].append(company_data)

        if email:
            analysis["aziende_con_email_valida"].append(company_data)

    # Generate report
    print("\n" + "="*80)
    print("ANALISI MULTI-CSV CAMPANIA - STATUS AZIENDE")
    print("="*80)

    print(f"\n📊 STATISTICHE GENERALI:")
    print(f"  Totale aziende: {analysis['totale_aziende']}")
    print(f"  Aziende con demo: {len(analysis['aziende_con_demo'])}")
    print(f"  Aziende senza demo: {len(analysis['aziende_senza_contatto'])}")
    print(f"  Aziende con email valida: {len(analysis['aziende_con_email_valida'])}")

    print(f"\n📁 DISTRIBUZIONE PER CSV:")
    for csv_file, count in analysis['csv_files'].items():
        print(f"  {csv_file}: {count} aziende")

    # Show demo links
    print(f"\n✅ AZIENDE CON DEMO CREATO ({len(analysis['aziende_con_demo'])}):")
    for company in analysis['aziende_con_demo'][:20]:  # Show first 20
        print(f"  • {company['nome']}")
        if company['demo_url']:
            print(f"    🔗 {company['demo_url']}")

    if len(analysis['aziende_con_demo']) > 20:
        print(f"  ... e altri {len(analysis['aziende_con_demo']) - 20}")

    # Save full analysis as JSON
    output_file = CSV_DIR / "analysis_campania_csvs.json"
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(analysis, f, ensure_ascii=False, indent=2)

    print(f"\n💾 Analisi completa salvata in: {output_file}")
    print("\n" + "="*80)
    print("NOTA IMPORTANTE - URL DI ACCESSO AI CSV:")
    print("="*80)
    print(f"\n📍 Percorso locale: {CSV_DIR}")
    print(f"📍 URL GitHub Pages: https://naplesexperiences-netizen.github.io/experiences-demos/demos/ricerca_clienti_csv/")
    print(f"\n✓ I file CSV sono accessibili pubblicamente via GitHub Pages")
    print(f"✓ Elenco file disponibili:")
    for csv_file in csv_files:
        filepath = CSV_DIR / csv_file
        if filepath.exists():
            print(f"  • {csv_file}")
            url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/ricerca_clienti_csv/{csv_file}"
            print(f"    📌 {url}")

if __name__ == "__main__":
    main()
