#!/usr/bin/env python3
"""
Report di contatti aggregato dai CSV regionali (source of truth).
Genera statistiche e summary dello stato dei contatti per regione.
"""

import csv
import os
from collections import Counter
from datetime import datetime

regional_csvs = [
    ('Cilento', 'CSV_Cilento.csv'),
    ('Costiera Amalfitana', 'CSV_Costiera_Amalfitana.csv'),
    ('Golfo Napoli/Sorrento', 'CSV_Golfo_Napoli_Sorrento.csv'),
    ('Isole del Golfo', 'CSV_Isole_del_Golfo.csv'),
    ('Salerno', 'CSV_Salerno.csv'),
]

base_dir = os.path.dirname(os.path.abspath(__file__))
all_rows = []
stats_by_region = {}

print("=" * 70)
print("REPORT CONTATTI CAMPANIA - CSV REGIONALI")
print("=" * 70)
print(f"Generato: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")

for region_name, filename in regional_csvs:
    csv_path = os.path.join(base_dir, filename)
    
    rows = []
    with open(csv_path, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        rows = list(reader)
        all_rows.extend(rows)
    
    contattati = len([r for r in rows if r.get('Contattato', '').strip() == 'Sì'])
    non_contattati = len([r for r in rows if r.get('Contattato', '').strip() != 'Sì'])
    
    stats_by_region[region_name] = {
        'total': len(rows),
        'contattati': contattati,
        'non_contattati': non_contattati,
    }
    
    pct = (contattati / len(rows) * 100) if len(rows) > 0 else 0
    print(f"{region_name:25} | {len(rows):3} aziende | {contattati:2} contattate ({pct:5.1f}%)")

# Totali
total_aziende = sum(s['total'] for s in stats_by_region.values())
total_contattati = sum(s['contattati'] for s in stats_by_region.values())
total_non_contattati = sum(s['non_contattati'] for s in stats_by_region.values())
pct_total = (total_contattati / total_aziende * 100) if total_aziende > 0 else 0

print("\n" + "-" * 70)
print(f"{'TOTALE':25} | {total_aziende:3} aziende | {total_contattati:2} contattate ({pct_total:5.1f}%)")
print("-" * 70)

# Analisi rifiuti
print("\n📋 RIFIUTI/FEEDBACK:")
for row in all_rows:
    if 'RIFIUTO' in (row.get('Note', '')):
        print(f"  ❌ {row['Nome_Azienda']:30} ({row.get('Email', 'N/A')})")

# Ultimi contatti
print("\n📅 ULTIMI CONTATTI (ultimi 5):")
contattati_rows = sorted(
    [r for r in all_rows if r.get('Data_Contatto', '').strip()],
    key=lambda r: r.get('Data_Contatto', ''),
    reverse=True
)[:5]
for row in contattati_rows:
    print(f"  ✓ {row['Nome_Azienda']:30} ({row.get('Data_Contatto', 'N/A')})")

print("\n" + "=" * 70)
