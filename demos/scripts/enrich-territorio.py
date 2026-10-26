#!/usr/bin/env python3
"""
Arricchisce una demo standard con le informazioni sul territorio prese
dall'articolo destinazioni della struttura (<blog>/destinazioni/, nel repo experiences-website).

Nella demo:
  * aggiunge la sezione «Il territorio» dopo i servizi (lead, attrazioni,
    distanze, foto con credito) e la voce di menu;
  * personalizza le schede «Esperienze sul Territorio» e «Posizione
    Strategica», il testo «Chi siamo» e la conversazione del concierge AI;
  * crea la pagina guida/index.html con l'articolo completo;
  * copia la foto in evidenza in img/territorio.jpg.

Funziona sui due template standard: «classic» (shared/classic.css) e «gsap»
(shared/gsap.css). Idempotente: si può rilanciare dopo aver modificato l'articolo.

Usage:
  python3 demos/scripts/enrich-territorio.py albergo-delfino-experiences-standard [altre-demo...]
"""
import html
import importlib.util
import json
import re
import shutil
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
DEMOS = ROOT / "demos"
_spec = importlib.util.spec_from_file_location("dest", ROOT / "scripts" / "build-destinazioni.py")
DEST = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(DEST)

START, END = "<!-- territorio:start -->", "<!-- territorio:end -->"
TAG_RE = re.compile(r"<[^>]+>")
ARROW = ('<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">'
         '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>')


def text(fragment):
    return re.sub(r"\s+", " ", html.unescape(TAG_RE.sub("", fragment))).strip()


def short(sentence_text, limit=230):
    """Prime frasi intere entro il limite (una frase molto breve si completa con la successiva)."""
    out = ""
    for s in re.split(r"(?<=[.!?])\s+", sentence_text):
        if len(out) + len(s) + 1 > limit and len(out) >= 90:
            break
        out = f"{out} {s}".strip()
    return out


def article_for(demo):
    for i, e in enumerate(DEST.ARTICLES, 1):
        if e[2] == demo:
            return i, e
    sys.exit(f"{demo}: nessun articolo in {DEST.OUT}")


def parse(body):
    """Estrae dall'articolo le parti che servono alla demo."""
    body = DEST.FOOTER_RE.sub("", body)
    lead = re.search(r'<p class="lead">(.*?)</p>', body, re.S)
    h2s = [(m.start(), text(m.group(1))) for m in re.finditer(r"<h2>(.*?)</h2>", body, re.S)]

    def h2_block(prefix):
        for n, (pos, title) in enumerate(h2s):
            if title.lower().startswith(prefix):
                end = h2s[n + 1][0] if n + 1 < len(h2s) else len(body)
                return body[pos:end]
        return ""

    def first_text(block):
        m = re.search(r"<p>(.*?)</p>", block, re.S) or re.search(r"<li>(.*?)</li>", block, re.S)
        return short(text(m.group(1))) if m else ""

    skip = ("perché", "quando", "dove", "leggi", "i dintorni", "l'itinerario", "giorno", "come arrivare")
    meals = ("pranzo", "sera", "cena", "aperitivo",  # e titoli che non nominano un luogo
             "altre idee", "se avete", "a piedi", "in barca", "che cosa", "cosa ", "un itinerario", "il percorso", "benvenuti", "come si")
    trip = re.compile(r"^(?:giorno \d+|mattina|tarda mattina|pomeriggio)\s*[—:–-]\s*(.+)$", re.I)
    blocks = [(m.group(1), text(m.group(2)), m.group(3))
              for m in re.finditer(r"<(h[23])>(.*?)</h[23]>(.*?)(?=<h[23]>|$)", body, re.S)]
    places, topics, stops = [], [], []
    for tag, title, block in blocks:
        desc = first_text(block)
        if not desc or title.lower().startswith(meals):
            continue
        stop = trip.match(title)
        if stop:
            stops.append((stop.group(1)[0].upper() + stop.group(1)[1:], desc))
        elif tag == "h3":
            places.append((title, desc))
        elif not title.lower().startswith(skip):
            topics.append((title, desc))
    items = places + topics + stops
    if len(items) < 3:
        for m in re.finditer(r"<li><strong>(.*?)</strong>(.*?)</li>", h2_block("i dintorni") + body, re.S):
            title = text(m.group(1)).rstrip(":")
            if title not in (t for t, _ in items):
                items.append((title, short(text(m.group(2)).lstrip(":, ").capitalize())))

    dist = []
    for t in re.finditer(r"<table>(.*?)</table>", body, re.S):
        cols = [text(h).lower() for h in re.findall(r"<th>(.*?)</th>", t.group(1), re.S)]
        # Colonna del tempo/distanza ("tempo da dedicare" è la durata della visita, non la distanza)
        ti = next((i for i, h in enumerate(cols) if "distanza" in h), None)
        if ti is None:
            ti = next((i for i, h in enumerate(cols) if "tempo" in h and "dedicare" not in h), None)
            if cols[0].startswith("esperienza"):  # «Esperienza | Tempo»: durate, non distanze
                ti = None
        if ti is None:
            continue
        ni = next(i for i, h in enumerate(cols) if not h.startswith("giorno"))
        for row in re.findall(r"<tr>(.*?)</tr>", t.group(1), re.S)[1:]:
            cells = [text(c) for c in re.findall(r"<td>(.*?)</td>", row, re.S)]
            if len(cells) > max(ti, ni) and cells[ti] not in ("—", "-", ""):
                dist.append((cells[ni], cells[ti]))
        break

    stay = re.search(r"<p>(.*?)</p>", h2_block("dove dormire"), re.S)
    return {
        "lead": text(lead.group(1)) if lead else "",
        "items": items[:4],
        "dist": dist[:6],
        "stay": text(stay.group(1)) if stay else "",
        "body": body,
    }


def section_html(entry, info, img, gsap=False):
    slug, struttura, demo, citta = entry[:4]
    e = lambda s: html.escape(s, quote=False)
    cards = "\n".join(
        f'                        <li class="terr-item"><h3>{e(t)}</h3><p>{e(d)}</p></li>' for t, d in info["items"])
    dist = ""
    if info["dist"]:
        rows = "".join(f"<dt>{e(a)}</dt><dd>{e(b)}</dd>" for a, b in info["dist"])
        dist = f'''
                    <div class="terr-dist">
                        <h3>Distanze dalla struttura</h3>
                        <dl>{rows}</dl>
                    </div>'''
    credit = DEST.credit_html(img) if img else ""
    alt = html.escape(img["alt"]) if img else ""
    if gsap:
        return f'''{START}
<!-- ===== TERRITORIO ===== -->
<section class="territorio terr-gsap" id="territorio">
    <div class="terr-wrap">
        <div class="section-header reveal">
            <div class="section-tag">Il Territorio</div>
            <h2>Perché visitare {e(citta)}</h2>
            <p>{e(short(info["lead"], 260))}</p>
        </div>
        <div class="terr-grid">
            <figure class="terr-photo reveal">
                <img src="img/territorio.jpg" alt="{alt}" width="1200" height="675" loading="lazy">
                <figcaption class="terr-credit">Foto: {credit}</figcaption>
            </figure>
            <div class="reveal">
                <ul class="terr-list">
{cards}
                </ul>{dist}
                <a href="guida/" class="btn btn-primary">Leggi la guida di {e(citta)} →</a>
            </div>
        </div>
    </div>
</section>
{END}'''
    return f'''{START}
    <!-- ===== TERRITORIO ===== -->
    <section class="territorio section-pad" id="territorio">
        <div class="container">
            <div class="section-header reveal">
                <div class="eyebrow">Il Territorio</div>
                <h2 class="section-title">Perché visitare {e(citta)}</h2>
                <p class="section-sub">{e(short(info["lead"], 260))}</p>
                <div class="section-rule"></div>
            </div>
            <div class="terr-grid">
                <figure class="terr-photo reveal">
                    <img src="img/territorio.jpg" alt="{alt}" width="1200" height="675" loading="lazy">
                    <figcaption class="terr-credit">Foto: {credit}</figcaption>
                </figure>
                <div class="reveal">
                    <ul class="terr-list">
{cards}
                    </ul>{dist}
                    <a href="guida/" class="btn-about">
                        Leggi la guida di {e(citta)}
                        {ARROW}
                    </a>
                </div>
            </div>
        </div>
    </section>
{END}'''


def enrich_index(page, entry, info, img):
    citta = entry[3]
    e = lambda s: html.escape(s, quote=False)
    gsap = "shared/gsap.css" in page
    base = "gsap" if gsap else "classic"
    if "territorio.css" not in page:
        page = page.replace(f'<link rel="stylesheet" href="../../shared/{base}.css">',
                            f'<link rel="stylesheet" href="../../shared/{base}.css">\n'
                            '    <link rel="stylesheet" href="../../shared/territorio.css">', 1)
        if "territorio.css" not in page:
            page = page.replace("</head>", '    <link rel="stylesheet" href="../../shared/territorio.css">\n</head>', 1)
    if 'href="#territorio"' not in page:
        page = re.sub(r'( *)(<li><a href="#services">Servizi</a></li>)',
                      lambda m: m.group(0) + "\n" + m.group(1) + '<li><a href="#territorio">Il Territorio</a></li>', page, count=1)

    block = section_html(entry, info, img, gsap)
    if START in page:
        page = re.sub(re.escape(START) + r".*?" + re.escape(END), lambda _: block, page, flags=re.S)
    else:
        s = page.index('id="services"')
        end = page.index("</section>", s) + len("</section>")
        page = page[:end] + "\n\n" + block + page[end:]
    if gsap:
        return enrich_gsap(page, entry, info)

    if info["stay"]:
        page = re.sub(r'(<p class="about-desc">).*?(</p>)', lambda m: m.group(1) + e(info["stay"]) + m.group(2), page, count=1, flags=re.S)

    # Tagline di hero e footer: il generatore vi copia la prima frase delle Note
    # del CSV, che a volte è solo indirizzo + fascia di prezzo ("Via Bolivar 55 mid").
    pill = re.search(r'<div class="about-pill">.*?<span>(.*?)</span>\s*</div>', page, re.S)
    tagline = f"{text(pill.group(1))} a {citta}" if pill else citta
    page = re.sub(r'(<p class="(?:hero|footer)-tagline">)[^<]*?\b(?:mid|upper|upscale|luxury|mid-upper|ultra-luxury)\s*(</p>)',
                  lambda m: m.group(1) + e(tagline) + m.group(2), page)

    listed = listed_names(info)
    if listed:
        page = re.sub(r'(<div class="svc-img" style="background-image:url\()[^)]*(\)"></div>\s*<div class="svc-body">\s*<h3 class="svc-title">Esperienze sul Territorio</h3>)',
                      lambda m: m.group(1) + "'img/territorio.jpg'" + m.group(2), page, count=1)
        page = svc(page, "Esperienze sul Territorio",
                   f"Da qui si scoprono {listed}. Tutti i consigli nella nostra guida di {citta}.",
                   "guida/", "Scopri la guida", blank=False)
    if info["dist"]:
        near = "; ".join(f"{a}: {b}" for a, b in info["dist"][:3])
        page = svc(page, "Posizione Strategica", f"A {citta}, in posizione comoda per il territorio. {near}.")

    if info["items"]:
        answer = chat_answer(citta, info, e)
        page = re.sub(r'(<div class="chat-row user">\s*<div class="msg-av">👤</div>\s*<div class="msg-bub">).*?(</div>)',
                      lambda m: m.group(1) + f"Cosa ci consigliate di vedere a {e(citta)} e nei dintorni?" + m.group(2),
                      page, count=1, flags=re.S)
        page = re.sub(r'(<div class="chat-row user">.*?</div>\s*</div>\s*<div class="chat-row">\s*<div class="msg-av">🤖</div>\s*<div class="msg-bub">).*?(</div>)',
                      lambda m: m.group(1) + "\n                                    " + answer + "\n                                " + m.group(2),
                      page, count=1, flags=re.S)
    return page


def listed_names(info):
    names = [re.sub(r"^(Il|Lo|La|I|Gli|Le|L')\b", lambda m: m.group(1).lower(), t) for t, _ in info["items"][:3]]
    return ", ".join(names[:-1]) + (" e " if len(names) > 1 else "") + names[-1] if names else ""


def chat_answer(citta, info, e):
    a, b = info["items"][0], info["items"][1] if len(info["items"]) > 1 else None
    return (f"Da non perdere: <strong>{e(a[0])}</strong>" + (f" e <strong>{e(b[0])}</strong>" if b else "") +
            f". {e(short(a[1], 150))} Vuoi che ti suggerisca un itinerario per i giorni del tuo soggiorno?")


def enrich_gsap(page, entry, info):
    """Template gsap: «Chi siamo», schede servizio e risposte del concierge."""
    citta = entry[3]
    e = lambda s: html.escape(s, quote=False)
    if info["stay"]:
        page = re.sub(r'(<div class="about-text">.*?</h2>\s*<p>).*?(</p>)',
                      lambda m: m.group(1) + e(info["stay"]) + m.group(2), page, count=1, flags=re.S)

    def card(page, title, desc, img=None, link=None):
        pat = re.compile(r'(<img src=")([^"]*)(" alt="' + re.escape(title) + r'"[^>]*>(?:(?!<img ).)*?<h3 class="card-title">'
                         + re.escape(title) + r'</h3>\s*<p class="card-desc">).*?(</p>)(\s*<a class="terr-card-link"[^>]*>.*?</a>)?', re.S)
        extra = f'\n                <a class="terr-card-link" href="{link[0]}">{link[1]} →</a>' if link else ""
        return pat.sub(lambda m: m.group(1) + (img or m.group(2)) + m.group(3) + e(desc) + m.group(4) + extra, page, count=1)

    if info["items"]:
        page = card(page, "Esperienze sul Territorio",
                    f"Da qui si scoprono {listed_names(info)}. Tutti i consigli nella nostra guida di {citta}.",
                    "img/territorio.jpg", ("guida/", "Scopri la guida"))
    if info["dist"]:
        near = "; ".join(f"{a}: {b}" for a, b in info["dist"][:3])
        page = card(page, "Posizione Strategica", f"A {citta}, in posizione comoda per il territorio. {near}.")

    if info["items"]:
        reply = ("        /* territorio */ { keys: ['vedere', 'visitare', 'dintorni', 'escursion', 'consigli', 'territorio', 'spiagg'], text: "
                 + json.dumps(chat_answer(citta, info, e), ensure_ascii=False) + " },\n")
        page = re.sub(r"        /\* territorio \*/ .*\n", "", page)
        page = page.replace("    var replies = [\n", "    var replies = [\n" + reply, 1)
        page = page.replace('placeholder="Prova: avete disponibilità per il weekend?"',
                            f'placeholder="Prova: cosa vedere a {html.escape(citta)}?"', 1)
    return page


def svc(page, title, desc, href=None, label=None, blank=True):
    """Sostituisce testo (e link) della scheda servizio con il titolo dato."""
    pat = re.compile(r'(<h3 class="svc-title">' + re.escape(title) + r'</h3>\s*<p class="svc-desc">).*?(</p>\s*<a href=")([^"]*)("[^>]*>)(.*?)(<svg)', re.S)

    def rep(m):
        link_href = href or m.group(3)
        attrs = m.group(4) if blank else '" class="svc-link">'
        link_label = f"\n                            {label}\n                            " if label else m.group(5)
        return m.group(1) + html.escape(desc, quote=False) + m.group(2) + link_href + attrs + link_label + m.group(6)
    return pat.sub(rep, page, count=1)


def guida_html(page, entry, info, img, seo):
    slug, struttura, demo, citta = entry[:4]
    e = lambda s: html.escape(s, quote=False)
    head = page[:page.index("</head>")]
    head = head.replace("../../shared/", "../../../shared/")
    head = re.sub(r"<title>.*?</title>", f"<title>{e(seo['seo_title'])} · {e(struttura)}</title>", head, count=1, flags=re.S)
    head = re.sub(r'(<meta name="description" content=")[^"]*', lambda m: m.group(1) + html.escape(seo["meta_description"]), head, count=1)
    head = re.sub(r'(<meta property="og:(?:title|description)" content=")[^"]*', lambda m: m.group(1) + html.escape(seo["post_title"]), head)
    if "territorio.css" not in head:
        head += '    <link rel="stylesheet" href="../../../shared/territorio.css">\n'
    banner = re.search(r'<div class="demo-banner">.*?</div>', page, re.S)
    footer = re.search(r"<footer.*?</footer>", page, re.S)
    foot = re.sub(r'href="#', 'href="../#', footer.group(0)) if footer else ""
    body = info["body"]
    credit = f'<p class="photo-credit"><small>Foto in evidenza: {DEST.credit_html(img)}.</small></p>' if img else ""
    body = body.rstrip()
    if body.endswith("</div>"):
        body = body[: -len("</div>")] + f"  {credit}\n</div>"
    nav = page[page.index('<nav class="navbar"'): page.index("</nav>") + len("</nav>")]
    nav = re.sub(r'href="#', 'href="../#', nav)
    nav = nav.replace('href="../#territorio"', 'href="./"')
    gsap = "gsap.css" in head
    # Il menu mobile del template gsap si apre via JS: la guida porta il suo script.
    toggle = '''
    <script>
    (function () {
        var t = document.querySelector('.nav-toggle'), l = document.getElementById('navLinks');
        if (!t || !l) return;
        t.addEventListener('click', function () {
            var open = l.classList.toggle('open');
            t.classList.toggle('open', open);
            t.setAttribute('aria-expanded', open);
        });
    })();
    </script>
''' if gsap else ""
    return f'''{head}</head>
<body{' class="guida-gsap"' if gsap else ""}>

    {banner.group(0) if banner else ""}

    {nav}

    <header class="guida-hero">
        <img src="../img/territorio.jpg" alt="{html.escape(img["alt"]) if img else ""}" width="1200" height="675">
        <div class="container">
            <div class="eyebrow">Guida di {e(citta)} · {e(struttura)}</div>
            <h1>{e(seo["post_title"])}</h1>
        </div>
    </header>

    <main class="section-pad">
        <div class="container">
            <a class="guida-back" href="../#territorio">← Torna al sito</a>
            {body}
            <aside class="guida-cta">
                <h2>Il tuo soggiorno a {e(citta)}</h2>
                <p>Scrivici per disponibilità e tariffe: rispondiamo direttamente, senza intermediari.</p>
                <a href="../#contact">Richiedi disponibilità</a>
            </aside>
        </div>
    </main>

    {foot}
{toggle}
</body>
</html>
'''


def enrich(demo):
    i, entry = article_for(demo)
    adir = DEST.OUT / DEST.dir_name(i, entry[0])
    info = parse((adir / "article.html").read_text(encoding="utf-8"))
    seo = json.loads((adir / "seo.json").read_text(encoding="utf-8"))
    images = json.loads(DEST.IMAGES.read_text(encoding="utf-8"))
    img = images.get(DEST.dir_name(i, entry[0]))
    ddir = DEMOS / demo
    index = ddir / "index.html"
    page = index.read_text(encoding="utf-8")

    (ddir / "img").mkdir(exist_ok=True)
    shutil.copyfile(adir / "featured.jpg", ddir / "img" / "territorio.jpg")
    page = enrich_index(page, entry, info, img)
    index.write_text(page, encoding="utf-8")
    (ddir / "guida").mkdir(exist_ok=True)
    (ddir / "guida" / "index.html").write_text(guida_html(page, entry, info, img, seo), encoding="utf-8")
    print(f"{demo}: {len(info['items'])} attrazioni, {len(info['dist'])} distanze, guida/ creata")


if __name__ == "__main__":
    if len(sys.argv) < 2:
        sys.exit(__doc__)
    for d in sys.argv[1:]:
        enrich(d)
