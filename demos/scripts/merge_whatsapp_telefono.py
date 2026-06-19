#!/usr/bin/env python3
"""
Importa le colonne Telefono e WhatsApp dai CSV di /tmp/gg_csv (branch
claude/gracious-gates-7lzr3i) nei CSV regionali correnti, senza toccare
nessun'altra colonna (in particolare Contattato/Data_Contatto/Link_Demo,
che su main sono piu' aggiornati).

Usage:
  python3 merge_whatsapp_telefono.py <source_dir> <target_csv> [...]
"""

import csv
import sys
from pathlib import Path


def load_lookup(source_csv):
    with open(source_csv, "r", encoding="utf-8") as f:
        rows = list(csv.DictReader(f))
    return {r["Nome_Azienda"].strip().lower(): (r.get("Telefono", ""), r.get("WhatsApp", "")) for r in rows}


def merge(target_csv, source_csv):
    lookup = load_lookup(source_csv)

    with open(target_csv, "r", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        fieldnames = reader.fieldnames
        rows = list(reader)

    if "Telefono" not in fieldnames or "WhatsApp" not in fieldnames:
        idx = fieldnames.index("Email") + 1
        fieldnames = fieldnames[:idx] + ["Telefono", "WhatsApp"] + fieldnames[idx:]

    matched, unmatched = 0, []
    for row in rows:
        key = row["Nome_Azienda"].strip().lower()
        if key in lookup:
            tel, wa = lookup[key]
            row["Telefono"] = tel
            row["WhatsApp"] = wa
            matched += 1
        else:
            row.setdefault("Telefono", "")
            row.setdefault("WhatsApp", "")
            unmatched.append(row["Nome_Azienda"])

    with open(target_csv, "w", encoding="utf-8", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)

    print(f"{target_csv.name}: {matched} match, {len(unmatched)} senza corrispondenza: {unmatched}")


def main():
    source_dir = Path(sys.argv[1])
    targets = [Path(p) for p in sys.argv[2:]]
    for target in targets:
        source = source_dir / target.name
        if not source.exists():
            print(f"⚠️  Nessun file sorgente per {target.name}, salto")
            continue
        merge(target, source)


if __name__ == "__main__":
    main()
