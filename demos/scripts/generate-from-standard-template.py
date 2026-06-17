#!/usr/bin/env python3
"""
Genera demo HTML + email dai template UFFICIALI Experiences Srl.

Template demo:
  - demo-hotel-template-experiences/index.html      (hotel, resort, agriturismo, B&B)
  - demo-tour-operator-template-experiences/index.html  (tour operator, DMC)

Usage:
  python3 generate-from-standard-template.py <region_csv> "<company_name>"

Example:
  python3 generate-from-standard-template.py ../ricerca_clienti_csv/CSV_Cilento.csv "Hotel Myrtus"
"""

import csv
import sys
import re
from pathlib import Path

DEMOS_DIR = Path(__file__).parent.parent
HOTEL_TEMPLATE = DEMOS_DIR / "demo-hotel-template-experiences" / "index.html"
TOUROP_TEMPLATE = DEMOS_DIR / "demo-tour-operator-template-experiences" / "index.html"
EMAIL_TEMPLATE_DIR = DEMOS_DIR / "template-standard"

PLACEHOLDER_IMG = ("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' "
                   "width='800' height='600'%3E%3Crect fill='%23dde3ea' width='800' "
                   "height='600'/%3E%3C/svg%3E")

# Palette colori per categoria
COLOR_MAPPING = {
    "tour": {"primary": "#1d6a8f", "secondary": "#e8883a"},
    "dmc": {"primary": "#1d6a8f", "secondary": "#e8883a"},
    "agriturismo": {"primary": "#6b8e23", "secondary": "#c9a227"},
    "b&b": {"primary": "#a0522d", "secondary": "#d4af37"},
    "resort": {"primary": "#0e7c8a", "secondary": "#e6a532"},
    "5 stelle": {"primary": "#1a2b4a", "secondary": "#c9a227"},
    "_default": {"primary": "#2c5aa0", "secondary": "#d4af37"},
}

# Servizi standard per categoria (titoli + descrizioni generici ma veritieri)
HOTEL_SERVICES = [
    ("Camere & Suite", "Sistemazioni accoglienti e curate, pensate per il comfort di coppie, famiglie e viaggiatori business."),
    ("Ristorazione", "Cucina del territorio con prodotti freschi e sapori autentici della tradizione campana."),
    ("Relax & Benessere", "Spazi dedicati al riposo e al benessere per rigenerarsi durante il soggiorno."),
    ("Esperienze sul Territorio", "Escursioni, tour ed attività per scoprire le bellezze naturali e culturali della zona."),
    ("Posizione Strategica", "Facile accesso alle principali attrazioni, spiagge e punti di interesse del territorio."),
    ("Servizi su Misura", "Accoglienza personalizzata e servizi dedicati per rendere unico ogni soggiorno."),
]

TOUROP_SERVICES = [
    ("Tour & Escursioni", "Itinerari curati alla scoperta delle destinazioni più suggestive del territorio."),
    ("Transfer & Trasporti", "Servizi di transfer affidabili e confortevoli da e verso aeroporti, porti e hotel."),
    ("Esperienze Su Misura", "Pacchetti personalizzati costruiti sulle esigenze di ogni cliente e gruppo."),
    ("Guide Multilingue", "Accompagnatori e guide qualificate in più lingue per un'esperienza completa."),
    ("Gruppi & Incentive", "Organizzazione di viaggi per gruppi, eventi aziendali e incentive."),
    ("Booking & Assistenza", "Supporto dedicato in ogni fase, dalla prenotazione al termine del viaggio."),
]

def slugify(text):
    text = text.lower().strip()
    text = re.sub(r"[^\w\s-]", "", text)
    text = re.sub(r"[-\s]+", "-", text)
    return text.rstrip("-")

def get_colors(categoria):
    cat = (categoria or "").lower()
    for key, colors in COLOR_MAPPING.items():
        if key != "_default" and key in cat:
            return colors
    return COLOR_MAPPING["_default"]

def is_tour_operator(categoria):
    cat = (categoria or "").lower()
    return "tour" in cat or "dmc" in cat or "agenzia" in cat

def parse_images(immagini_field, count=8):
    """Estrai URL immagini reali dal campo CSV (separati da spazio)."""
    urls = [u.strip() for u in (immagini_field or "").split() if u.strip().startswith("http")]
    # Filtra placeholder inutili (svg, gif, flags, loghi)
    urls = [u for u in urls if not re.search(r"\.(svg|gif)(\?|$)|flag|logo|placeholder|star\.|rating", u, re.I)]
    if not urls:
        return [PLACEHOLDER_IMG] * count
    # Ripeti ciclicamente se ne servono di più
    return [urls[i % len(urls)] for i in range(count)]

def build_description(company):
    """Costruisci descrizione dal Brief/Note senza inventare."""
    brief = (company.get("Brief_Demo_OpenClaw") or "").strip()
    note = (company.get("Note") or "").split("|")[0].strip()
    nome = company["Nome_Azienda"]
    citta = company.get("Citta", "")

    if note:
        return f"{nome} — {note} Situato a {citta}, è la scelta ideale per chi cerca qualità e autenticità nel territorio campano."
    if brief:
        # prendi la parte descrittiva del brief (dopo i primi 3 ; metadata)
        parts = [p.strip() for p in brief.split(";")]
        desc = parts[3] if len(parts) > 3 else brief
        return f"{nome}, {desc}. A {citta}."
    return f"{nome}, eccellenza dell'ospitalità a {citta}, nel cuore della Campania."

def first_phrase(company):
    """Prima frase breve descrittiva per tagline."""
    note = (company.get("Note") or "").split("|")[0].split(".")[0].strip()
    return note or company.get("Categoria", "")

def build_data(company):
    """Costruisci il dizionario completo di sostituzione."""
    nome = company["Nome_Azienda"]
    categoria = company.get("Categoria", "")
    citta = company.get("Citta", "")
    email = company.get("Email", "")
    url = company.get("URL_Sito", "")
    colors = get_colors(categoria + " " + (company.get("Note") or ""))
    imgs = parse_images(company.get("Immagini"), count=8)
    tourop = is_tour_operator(categoria)
    services = TOUROP_SERVICES if tourop else HOTEL_SERVICES
    desc = build_description(company)
    tagline = first_phrase(company) or f"{categoria} · {citta}"

    # Stats derivate (veritiere/generiche)
    stat_label_loc = citta or "Campania"
    stats = [
        ("100%", "Su Misura"),
        ("IT/EN", "Multilingua"),
        (stat_label_loc, "Posizione"),
        ("24/7", "Assistenza"),
    ]

    # Set comune (sia HOTEL_* che COMPANY_*)
    data = {
        # Hotel placeholders
        "HOTEL_NAME": nome,
        "HOTEL_CATEGORY": categoria,
        "HOTEL_DESCRIPTION": desc,
        "HOTEL_TAGLINE": tagline,
        "HOTEL_URL": url,
        "HOTEL_EMAIL": email,
        # Tour operator placeholders
        "COMPANY_NAME": nome,
        "COMPANY_CATEGORY": categoria,
        "COMPANY_DESCRIPTION": desc,
        "COMPANY_TAGLINE": tagline,
        "COMPANY_URL": url,
        "COMPANY_EMAIL": email,
        # Comuni
        "PRIMARY_COLOR": colors["primary"],
        "SECONDARY_COLOR": colors["secondary"],
        "HERO_IMAGE_URL": imgs[0],
        "ABOUT_IMAGE_URL": imgs[1],
        "CTA_TEXT": "Prenota Ora" if not tourop else "Richiedi un Preventivo",
        "CHATBOT_GREETING": f"Ciao! Posso aiutarti a scoprire {nome}. Come posso esserti utile?",
        # Highlights
        "HIGHLIGHT1": "Qualità garantita",
        "HIGHLIGHT2": "Territorio autentico",
        "HIGHLIGHT3": "Accoglienza dedicata",
        "HIGHLIGHT4": "Esperienza consolidata",
        "HIGHLIGHT5": "Posizione ideale",
    }

    # Services 1-6
    for i, (title, sdesc) in enumerate(services, start=1):
        data[f"SERVICE{i}_TITLE"] = title
        data[f"SERVICE{i}_DESC"] = sdesc
        data[f"SERVICE{i}_IMG"] = imgs[(i + 1) % len(imgs)]

    # Stats 1-4
    for i, (num, label) in enumerate(stats, start=1):
        data[f"STAT{i}_NUM"] = num
        data[f"STAT{i}_LABEL"] = label

    return data, tourop

def substitute(text, data):
    for key, value in data.items():
        text = text.replace(f"{{{{{key}}}}}", str(value or ""))
    return text

def generate_demo(company):
    data, tourop = build_data(company)
    template_file = TOUROP_TEMPLATE if tourop else HOTEL_TEMPLATE

    with open(template_file, "r", encoding="utf-8") as f:
        html = f.read()

    html = substitute(html, data)

    # Verifica placeholder residui
    residui = re.findall(r"\{\{[^}]+\}\}", html)
    if residui:
        print(f"⚠️  Placeholder non sostituiti: {set(residui)}")

    slug = slugify(company["Nome_Azienda"])
    out_dir = DEMOS_DIR / f"{slug}-experiences-standard"
    out_dir.mkdir(parents=True, exist_ok=True)
    out_file = out_dir / "index.html"
    with open(out_file, "w", encoding="utf-8") as f:
        f.write(html)

    url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-experiences-standard/"
    return url, out_file, tourop

def generate_email(company, tourop):
    cat = (company.get("Categoria") or "").lower()
    if tourop:
        tpl = EMAIL_TEMPLATE_DIR / "template-email-tourop.txt"
    elif "agriturismo" in cat or "b&b" in cat:
        tpl = EMAIL_TEMPLATE_DIR / "template-email-agriturismo.txt"
    else:
        tpl = EMAIL_TEMPLATE_DIR / "template-email-hotel.txt"

    if not tpl.exists():
        return None

    with open(tpl, "r", encoding="utf-8") as f:
        text = f.read()

    slug = slugify(company["Nome_Azienda"])
    demo_url = f"https://naplesexperiences-netizen.github.io/experiences-demos/demos/{slug}-experiences-standard/"
    note = (company.get("Note") or "").split("|")[0].split(".")[0].strip()
    pf = [p.strip() for p in note.split() if p] if note else []

    email_data = {
        "NOME_AZIENDA": company["Nome_Azienda"],
        "NOME_CONTATTO": "Responsabile",
        "CATEGORIA": company.get("Categoria", "partner"),
        "CITTA": company.get("Citta", ""),
        "PUNTO_FORZA_1": note or company.get("Categoria", "eccellenza"),
        "PUNTO_FORZA_2": company.get("Citta", "posizione strategica"),
        "LINK_DEMO": demo_url,
        "NOME_MITTENTE": "Experiences Srl",
        "FIRMA_MITTENTE": "Experiences Srl",
        "CONTATTI_MITTENTE": "info@experiences-srl.it | +39 392 691 7657",
    }
    return substitute(text, email_data)

def find_company(csv_path, name):
    with open(csv_path, "r", encoding="utf-8") as f:
        for row in csv.DictReader(f):
            if row["Nome_Azienda"].strip().lower() == name.lower():
                return row
    return None

def main():
    if len(sys.argv) < 3:
        print('Usage: python3 generate-from-standard-template.py <csv_path> "<company_name>"')
        sys.exit(1)

    csv_path = Path(sys.argv[1])
    name = sys.argv[2]

    if not csv_path.exists():
        print(f"❌ CSV non trovato: {csv_path}")
        sys.exit(1)

    company = find_company(csv_path, name)
    if not company:
        print(f"❌ Azienda non trovata: {name}")
        sys.exit(1)

    print(f"✓ Azienda: {company['Nome_Azienda']} ({company.get('Categoria','')}, {company.get('Citta','')})")

    print("📝 Generazione demo...")
    url, out_file, tourop = generate_demo(company)
    print(f"✓ Demo creata: {out_file}")
    print(f"  Template: {'TOUR OPERATOR' if tourop else 'HOTEL'}")
    print(f"  URL: {url}")

    print("\n📧 Email template:")
    email = generate_email(company, tourop)
    if email:
        print("=" * 70)
        print(email)
        print("=" * 70)

if __name__ == "__main__":
    main()
