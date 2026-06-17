#!/usr/bin/env python3
"""
Genera demo + email per tutte le aziende NON ancora contattate di un CSV regionale.
Aggiorna Link_Demo e Data_Generazione nel CSV. Non tocca Data_Contatto/Contattato
(quello avviene solo dopo l'invio reale dell'email).

Usage:
  python3 batch_generate_region.py <region_csv> [--date YYYY-MM-DD]
"""

import csv
import sys
from datetime import date
from pathlib import Path

import importlib.util

_spec = importlib.util.spec_from_file_location(
    "gen", Path(__file__).parent / "generate-from-standard-template.py"
)
gen = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(gen)

SCRIPT_DIR = Path(__file__).parent
EMAIL_DRAFTS_DIR = SCRIPT_DIR.parent / "ricerca_clienti_csv" / "email_drafts"


def main():
    if len(sys.argv) < 2:
        print("Usage: python3 batch_generate_region.py <region_csv>")
        sys.exit(1)

    csv_path = Path(sys.argv[1])
    today = date.today().isoformat()

    with open(csv_path, "r", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        fieldnames = reader.fieldnames
        rows = list(reader)

    region_slug = csv_path.stem.replace("CSV_", "").lower()
    out_dir = EMAIL_DRAFTS_DIR / region_slug
    out_dir.mkdir(parents=True, exist_ok=True)

    processed, skipped_no_email, skipped_contacted = [], [], []

    for row in rows:
        if row.get("Contattato", "").strip() == "Sì":
            skipped_contacted.append(row["Nome_Azienda"])
            continue
        if not (row.get("Email") or "").strip():
            skipped_no_email.append(row["Nome_Azienda"])
            continue

        url, out_file, tourop = gen.generate_demo(row)
        email = gen.generate_email(row, tourop)

        slug = gen.slugify(row["Nome_Azienda"])
        email_file = out_dir / f"{slug}.txt"
        with open(email_file, "w", encoding="utf-8") as f:
            f.write(email)

        row["Link_Demo"] = url
        row["Data_Generazione"] = today
        processed.append((row["Nome_Azienda"], url, str(email_file)))

    with open(csv_path, "w", encoding="utf-8", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)

    print(f"✓ Processate: {len(processed)}")
    for name, url, email_file in processed:
        print(f"  - {name}\n    demo:  {url}\n    email: {email_file}")
    print(f"\n⏭️  Già contattate (skip): {len(skipped_contacted)}")
    print(f"⚠️  Senza email (skip, nessuna demo generata): {len(skipped_no_email)}")
    for name in skipped_no_email:
        print(f"  - {name}")


if __name__ == "__main__":
    main()
