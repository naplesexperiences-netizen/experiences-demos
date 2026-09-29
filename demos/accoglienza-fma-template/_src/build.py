#!/usr/bin/env python3
"""Genera la demo statica del template Accoglienza FMA da _src/data.json.

Ogni pagina generata corrisponde a un template WordPress:
  index.html                    -> front-page.php
  strutture/<slug>/index.html   -> single-struttura.php  (CPT "struttura" del plugin)
  blog/index.html               -> home.php (pagina articoli)
  blog/<slug>/index.html        -> single.php

Uso:  python3 _src/build.py
"""
import html
import json
import re
from datetime import date
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
DATA = json.loads((ROOT / '_src' / 'data.json').read_text())
STRUTTURE = DATA['strutture']
ARTICOLI = sorted(DATA['articoli'], key=lambda a: a['data'], reverse=True)
PARTNER = DATA['partner']
BY_SLUG = {s['slug']: s for s in STRUTTURE}

SITE = 'Accoglienza delle Salesiane'
TAGLINE = 'Ti sentirai come a casa'
DEMO_URL = 'https://naplesexperiences-netizen.github.io/experiences-demos/demos/accoglienza-fma-template/'

MESI = ['gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto',
        'settembre', 'ottobre', 'novembre', 'dicembre']

# Articolo -> casa collegata (stesso territorio)
ARTICOLO_CASA = {
    'la-storia-dellaltopiano-di-asiago': 'villa-tabor',
    'soverato-la-perla-della-calabria': 'fma-soverato',
    'il-giubileo-a-roma': 'fma-roma',
    'cosa-fare-a-napoli': 'fma-napoli',
    'perche-visitare-torre-annunziata': 'villa-tiberiade',
}

e = html.escape


def data_it(iso):
    d = date.fromisoformat(iso)
    return f'{d.day} {MESI[d.month - 1]} {d.year}'


def regioni():
    counts = {}
    for s in STRUTTURE:
        counts[s['regione']] = counts.get(s['regione'], 0) + 1
    return sorted(counts.items(), key=lambda kv: (-kv[1], kv[0]))


# ---------------------------------------------------------------- icone
# Set unico, tratto 1.6, disegnato per questo template.
ICON_PATHS = {
    'arrow': '<path d="M5 12h14M13 6l6 6-6 6"/>',
    'chev-l': '<path d="M15 5l-7 7 7 7"/>',
    'chev-r': '<path d="M9 5l7 7-7 7"/>',
    'pause': '<path d="M9 5v14M15 5v14"/>',
    'play': '<path d="M8 5l11 7-11 7z"/>',
    'close': '<path d="M6 6l12 12M18 6L6 18"/>',
    'phone': '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1z"/>',
    'mail': '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
    'chat': '<path d="M4 20l1.4-4.2A8 8 0 1 1 8.6 19z"/><path d="M9 10.5c.5 2 2 3.5 4 4l1.2-1.2 2 1-1 1.7c-3.4 0-7.2-3.8-7.2-7.2l1.7-1 1 2z"/>',
    'pin': '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
    'globe': '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.7 3.8 5.7 3.8 9s-1.3 6.3-3.8 9c-2.5-2.7-3.8-5.7-3.8-9S9.5 5.7 12 3z"/>',
    'check': '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
    'photos': '<rect x="3" y="5" width="14" height="12" rx="1.5"/><path d="M7 20h12a2 2 0 0 0 2-2V8"/><path d="M3 14l4-4 4 4 2-2 4 4"/>',
    'minus': '<path d="M6 12h12"/>',
    'plus': '<path d="M12 6v12M6 12h12"/>',
    'menu': '<path d="M4 8h16M4 16h16"/>',
    'external': '<path d="M14 5h5v5M19 5l-8 8M18 14v4a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h4"/>',
}


def icon(name, cls='icon'):
    return (f'<svg class="{cls}" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" '
            f'stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{ICON_PATHS[name]}</svg>')


def img(root, path, alt, w=None, h=None, cls='', lazy=True, sizes=None, priority=False):
    attrs = [f'src="{root}img/{path}"', f'alt="{e(alt)}"']
    if w and h:
        attrs += [f'width="{w}"', f'height="{h}"']
    if cls:
        attrs.append(f'class="{cls}"')
    if priority:
        attrs.append('fetchpriority="high"')
    elif lazy:
        attrs.append('loading="lazy"')
    attrs.append('decoding="async"')
    return f'<img {" ".join(attrs)}>'


IMGMETA = json.loads((ROOT / '_src' / 'imgmeta.json').read_text())


def dims(key):
    m = IMGMETA.get(key)
    return (m['w'], m['h']) if m else (None, None)


def s_img(root, s, name, alt, cls='', lazy=True, priority=False):
    w, h = dims(f"strutture/{s['slug']}/{name}")
    return img(root, f"strutture/{s['slug']}/{name}.webp", alt, w, h, cls, lazy, priority=priority)


def luogo(s):
    return f"{s['localita']} ({s['provincia']})"


# ---------------------------------------------------------------- layout
def head(root, title, desc, page, extra=''):
    return f'''<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{e(title)}</title>
<meta name="description" content="{e(desc)}">
<meta name="demo:tags" content="wordpress,template,casa-per-ferie,accoglienza,salesiane,mappa,prenotazione">
<meta name="demo:category" content="ricettivo">
<meta name="theme-color" content="#f7f3ec">
<link rel="icon" href="{root}img/loghi/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT@9..144,300..600,100&family=Geist:wght@400;500;600&display=swap">
<link rel="stylesheet" href="{root}assets/css/tokens.css">
<link rel="stylesheet" href="{root}assets/css/site.css">
{extra}<script>document.documentElement.classList.add('js');if(!matchMedia('(prefers-reduced-motion: reduce)').matches)document.documentElement.classList.add('motion');setTimeout(function(){{document.documentElement.classList.add('motion-ready')}},3000)</script>
</head>
<body data-page="{page}">
<a class="skip" href="#contenuto">Vai al contenuto</a>
'''


NAV = [('Strutture', 'index.html#strutture'), ('Chi siamo', 'index.html#chi-siamo'), ('Blog', 'blog/')]


def masthead(root, current=''):
    cur = ' aria-current="page"'
    links = ''.join(
        f'<li><a href="{root}{href}"{cur if label == current else ""}>{label}</a></li>'
        for label, href in NAV)
    n_reg = len(regioni())
    return f'''<header class="mast" data-mast>
  <div class="mast__issue">
    <p>Case per ferie delle Figlie di Maria Ausiliatrice · {len(STRUTTURE)} case in {n_reg} regioni</p>
  </div>
  <div class="mast__bar">
    <a class="mast__brand" href="{root}index.html" aria-label="{SITE} — home">
      <img src="{root}img/loghi/accoglienza-fma.png" alt="" width="500" height="121">
    </a>
    <nav class="mast__nav" aria-label="Principale">
      <ul>{links}</ul>
    </nav>
    <a class="btn btn--primary mast__cta" href="{root}index.html#strutture">Richiedi un soggiorno</a>
    <button class="mast__menu" type="button" aria-expanded="false" aria-controls="menu-mobile" data-menu-toggle>
      {icon('menu')}<span>Menu</span>
    </button>
  </div>
  <div class="mast__sheet" id="menu-mobile" hidden>
    <ul>{links}<li><a class="btn btn--primary" href="{root}index.html#strutture">Richiedi un soggiorno</a></li></ul>
  </div>
</header>
'''


def footer(root):
    return f'''<footer class="foot">
  <div class="foot__inner">
    <div class="foot__mast">
      <img src="{root}img/loghi/accoglienza-fma.png" alt="{SITE}" width="500" height="121" loading="lazy">
      <p class="foot__tagline">{TAGLINE}.</p>
    </div>
    <nav class="foot__links" aria-label="Piè di pagina">
      <a href="{root}index.html#strutture">Strutture</a>
      <a href="{root}index.html#chi-siamo">Chi siamo</a>
      <a href="{root}blog/">Blog</a>
      <a href="https://accoglienzafma.com/privacy-policy/" rel="noopener">Privacy</a>
    </nav>
    <p class="foot__meta">© {date.today().year} {SITE} · Figlie di Maria Ausiliatrice. Ogni casa risponde direttamente alle richieste di soggiorno.</p>
    <p class="foot__meta foot__demo">Demo di template WordPress · contenuti da accoglienzafma.com · design experiences srl</p>
  </div>
</footer>
'''


def scripts(root, leaflet=False):
    lf = ''
    if leaflet:
        lf = ('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">\n'
              '<script defer src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>\n')
    return f'''{lf}<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js"></script>
<script defer src="{root}assets/js/site.js"></script>
</body>
</html>
'''


def map_payload(root):
    return json.dumps([{
        'slug': s['slug'], 'nome': s['nome'], 'luogo': luogo(s), 'regione': s['regione'],
        'lat': s['lat'], 'lng': s['lng'], 'url': f"{root}strutture/{s['slug']}/",
        'img': f"{root}img/strutture/{s['slug']}/card.webp",
    } for s in STRUTTURE], ensure_ascii=False).replace('</', '<\\/')


# ---------------------------------------------------------------- home
def home():
    root = ''
    out = [head(root, f'{SITE} · Case per ferie delle Figlie di Maria Ausiliatrice',
                'Otto case per ferie delle Salesiane, dal Lago Maggiore alla Calabria: scegli la casa sulla mappa e invia la tua richiesta di soggiorno.',
                'home')]
    out.append(masthead(root))
    out.append('<main id="contenuto">')

    # --- hero: diptych testo + ricerca | slider
    slides = []
    for i, s in enumerate(STRUTTURE):
        titolo = s['titolo_hero'] or s['nome']
        sotto = s['nome'] if s['titolo_hero'] else s['tipo']
        slides.append(f'''<figure class="slide{' is-active' if i == 0 else ''}" data-slide role="group" aria-roledescription="slide" aria-label="{i + 1} di {len(STRUTTURE)}: {e(s['nome'])}"{'' if i == 0 else ' aria-hidden="true"'}>
          {s_img(root, s, 'hero', f"{s['nome']}, {s['localita']}", 'slide__img', lazy=i > 0, priority=i == 0)}
          <figcaption class="slide__cap">
            <span class="slide__where">{icon('pin', 'icon icon--sm')}{e(luogo(s))}</span>
            <span class="slide__title">{e(titolo)}</span>
            <span class="slide__sub">{e(sotto)}</span>
            <a class="slide__link" href="strutture/{s['slug']}/"{'' if i == 0 else ' tabindex="-1"'}>Scopri la casa {icon('arrow', 'icon icon--sm')}</a>
          </figcaption>
        </figure>''')
    opts_dove = ''.join(
        f'<optgroup label="{e(r)}">' + ''.join(
            f'<option value="{s["slug"]}">{e(s["localita"])} · {e(s["nome"])}</option>'
            for s in STRUTTURE if s['regione'] == r) + '</optgroup>'
        for r, _ in regioni())
    opts_regioni = ''.join(f'<option value="regione:{e(r)}">{e(r)} — tutte le case ({n})</option>' for r, n in regioni())
    out.append(f'''
<section class="hero" aria-labelledby="hero-title">
  <div class="hero__text">
    <h1 id="hero-title" class="hero__title" data-hero-in>{TAGLINE}</h1>
    <p class="hero__lede" data-hero-in>Otto case per ferie delle Figlie di Maria Ausiliatrice, dal Lago Maggiore al mare di Calabria. Per famiglie, gruppi e per chi cerca un tempo di pace.</p>
  </div>

  <div class="hero__slider" data-slider aria-roledescription="carousel" aria-label="Le nostre case">
    <div class="slider__track">
      {''.join(slides)}
    </div>
    <div class="slider__controls">
      <button class="slider__btn" type="button" data-slider-prev aria-label="Casa precedente">{icon('chev-l')}</button>
      <p class="slider__count" aria-live="polite"><span data-slider-now>01</span><span aria-hidden="true"> / </span><span class="visually-hidden"> di </span><span>{len(STRUTTURE):02d}</span></p>
      <button class="slider__btn" type="button" data-slider-next aria-label="Casa successiva">{icon('chev-r')}</button>
      <button class="slider__btn slider__btn--toggle" type="button" data-slider-toggle aria-label="Metti in pausa lo scorrimento" aria-pressed="false">{icon('pause', 'icon icon-pause')}{icon('play', 'icon icon-play')}</button>
      <span class="slider__progress" aria-hidden="true"><span data-slider-bar></span></span>
    </div>
  </div>

  <form class="search" action="#strutture" data-search role="search" aria-label="Cerca una casa" data-hero-in>
    <div class="field field--where">
      <label for="s-dove">Dove</label>
      <select id="s-dove" name="dove">
        <option value="">Tutte le case</option>
        <optgroup label="Per regione">{opts_regioni}</optgroup>
        {opts_dove}
      </select>
    </div>
    <div class="field">
      <label for="s-arrivo">Arrivo</label>
      <input id="s-arrivo" name="arrivo" type="date" data-date-in>
    </div>
    <div class="field">
      <label for="s-partenza">Partenza</label>
      <input id="s-partenza" name="partenza" type="date" data-date-out>
    </div>
    <div class="field field--guests">
      <label for="s-ospiti">Ospiti</label>
      <div class="stepper" data-stepper>
        <button type="button" data-step="-1" aria-label="Un ospite in meno">{icon('minus', 'icon icon--sm')}</button>
        <input id="s-ospiti" name="ospiti" type="number" min="1" max="60" value="2" inputmode="numeric">
        <button type="button" data-step="1" aria-label="Un ospite in più">{icon('plus', 'icon icon--sm')}</button>
      </div>
    </div>
    <button class="btn btn--primary search__go" type="submit">Cerca</button>
    <p class="search__error" role="alert" hidden data-search-error></p>
  </form>
</section>
''')

    # --- partner marquee
    logos = ''.join(
        (f'<li><a href="{e(p["url"])}" rel="noopener" target="_blank" aria-label="{e(p["nome"])} (si apre in una nuova scheda)">' if p['url'] else f'<li><span aria-label="{e(p["nome"])}">')
        + img(root, p['logo'], '' if p['url'] else p['nome'], *dims(p['logo'].replace('.png', '')), cls='partner__logo')
        + ('</a></li>' if p['url'] else '</span></li>')
        for p in PARTNER)
    out.append(f'''
<section class="partners" aria-labelledby="partner-title">
  <h2 id="partner-title" class="partners__title">Insieme a noi</h2>
  <div class="marquee" data-marquee>
    <ul class="marquee__track">{logos}</ul>
    <ul class="marquee__track" aria-hidden="true">{logos.replace('<a ', '<a tabindex="-1" ')}</ul>
  </div>
</section>
''')

    # --- chi siamo
    n_reg = len(regioni())
    napoli = BY_SLUG['fma-napoli']
    out.append(f'''
<section class="about" id="chi-siamo" aria-labelledby="about-title">
  <figure class="about__photo" data-unveil>
    {s_img(root, napoli, 'hero', 'Il cortile porticato della casa FMA di Napoli')}
    <figcaption>Il cortile della casa FMA di Napoli</figcaption>
  </figure>
  <div class="about__text">
    <h2 id="about-title" class="section-title">Chi siamo</h2>
    <p class="lede">Conosciute anche come salesiane di don Bosco, le Figlie di Maria Ausiliatrice sono una congregazione religiosa presente in tutto il mondo, nata nel 1872 dal carisma di san Giovanni Bosco e di santa Maria Domenica Mazzarello.</p>
    <p>Don Bosco scelse Maria come modello di donna capace di incarnare la “pedagogia del prendersi cura”. È lo stesso stile con cui apriamo le nostre case: accoglienza semplice, familiare, attenta alla persona. Qui soggiornano famiglie, gruppi parrocchiali, pellegrini e chiunque cerchi riposo, silenzio o un tempo di spiritualità.</p>
    <blockquote class="about__quote">
      <p>Ogni struttura è unica, ma tutte condividono l’impegno per l’accoglienza, la cura e il benessere degli ospiti.</p>
      <footer>Salesiane FMA</footer>
    </blockquote>
    <dl class="stats">
      <div><dt>Anno di fondazione della congregazione</dt><dd data-count="1872">1872</dd></div>
      <div><dt>Case per ferie in rete</dt><dd data-count="{len(STRUTTURE)}">{len(STRUTTURE)}</dd></div>
      <div><dt>Regioni, dal Piemonte alla Calabria</dt><dd data-count="{n_reg}">{n_reg}</dd></div>
    </dl>
  </div>
</section>
''')

    # --- strutture: mappa + elenco
    chips = [f'<button type="button" class="chip is-active" data-filter="" aria-pressed="true">Tutte <span>{len(STRUTTURE)}</span></button>']
    chips += [f'<button type="button" class="chip" data-filter="{e(r)}" aria-pressed="false">{e(r)} <span>{n}</span></button>' for r, n in regioni()]
    items = []
    for i, s in enumerate(STRUTTURE):
        servizi = ' · '.join(s['servizi'][:3])
        items.append(f'''<li class="place" data-place="{s['slug']}" data-regione="{e(s['regione'])}">
        <a class="place__link" href="strutture/{s['slug']}/" data-place-link>
          <span class="place__num" aria-hidden="true">{i + 1}</span>
          <span class="place__media">{s_img(root, s, 'card', '', 'place__img')}</span>
          <span class="place__body">
            <span class="place__name">{e(s['nome'])}</span>
            <span class="place__where">{e(luogo(s))} · {e(s['regione'])}</span>
            <span class="place__meta">{e(s['tipo'])}{(' · ' + e(servizi)) if servizi else ''}</span>
          </span>
          <span class="place__go" aria-hidden="true">{icon('arrow')}</span>
        </a>
      </li>''')
    out.append(f'''
<section class="places" id="strutture" aria-labelledby="places-title">
  <div class="places__head">
    <h2 id="places-title" class="section-title">Otto case, dal Lago Maggiore al mare di Calabria</h2>
    <p class="places__lede">Tutte gestite dalle Figlie di Maria Ausiliatrice. Scegli una casa sulla mappa o nell’elenco: ogni struttura risponde direttamente alla tua richiesta.</p>
  </div>
  <div class="places__tools">
    <div class="chips" role="group" aria-label="Filtra per regione">{''.join(chips)}</div>
    <p class="places__status" aria-live="polite" data-places-status>{len(STRUTTURE)} case</p>
  </div>
  <div class="search-echo" hidden data-search-echo>
    <p><span data-search-echo-text></span></p>
    <button type="button" class="link-btn" data-search-reset>Azzera la ricerca</button>
  </div>
  <div class="places__grid">
    <ol class="places__list" data-places-list>
      {''.join(items)}
    </ol>
    <div class="places__map">
      <div class="map" id="mappa-strutture" data-map role="region" aria-label="Mappa delle case"></div>
      <p class="map__fallback">La mappa si carica con la connessione. Tutte le case sono comunque nell’elenco.</p>
    </div>
  </div>
  <p class="places__empty" hidden data-places-empty>Nessuna casa corrisponde a questa ricerca. <button type="button" class="link-btn" data-search-reset>Mostra tutte le case</button></p>
  <script type="application/json" id="fma-map-data">{map_payload(root)}</script>
</section>
''')

    # --- blog
    a0, rest = ARTICOLI[0], ARTICOLI[1:3]
    w0, h0 = dims(f"blog/{a0['slug']}")
    small = ''.join(f'''<li class="post post--row">
        <a href="blog/{a['slug']}/">
          <span class="post__thumb">{img(root, f"blog/{a['slug']}.webp", '', *dims(f"blog/{a['slug']}"))}</span>
          <span class="post__body">
            <span class="post__meta"><time datetime="{a['data']}">{data_it(a['data'])}</time> · {a['minuti']} min</span>
            <span class="post__title">{e(a['titolo'])}</span>
          </span>
        </a>
      </li>''' for a in rest)
    out.append(f'''
<section class="journal" aria-labelledby="journal-title">
  <div class="journal__head">
    <h2 id="journal-title" class="section-title">Dal blog</h2>
    <a class="text-link" href="blog/">Tutti gli articoli {icon('arrow', 'icon icon--sm')}</a>
  </div>
  <div class="journal__grid">
    <article class="post post--lead">
      <a href="blog/{a0['slug']}/">
        <span class="post__media" data-unveil>{img(root, f"blog/{a0['slug']}.webp", '', w0, h0)}</span>
        <span class="post__meta"><span class="post__cat">{e(a0['categoria'])}</span> · <time datetime="{a0['data']}">{data_it(a0['data'])}</time> · {a0['minuti']} min</span>
        <span class="post__title">{e(a0['titolo'])}</span>
        <span class="post__excerpt">{e(a0['estratto'])}</span>
      </a>
    </article>
    <ul class="journal__list">{small}</ul>
  </div>
</section>
''')
    out.append('</main>')
    out.append(footer(root))
    out.append(scripts(root, leaflet=True))
    return ''.join(out)


# ---------------------------------------------------------------- struttura
def struttura(s):
    root = '../../'
    c = s['contatti']
    title = f"{s['nome']} · {s['localita']} · {SITE}"
    desc = (s['intro'] or f"{s['nome']}, {s['tipo'].lower()} a {s['localita']}.")[:155]
    out = [head(root, title, desc, 'struttura')]
    out.append(masthead(root, 'Strutture'))
    out.append('<main id="contenuto">')

    # intestazione
    sub = f'<p class="stay__claim">{e(s["titolo_hero"])}</p>' if s['titolo_hero'] else ''
    out.append(f'''
<nav class="crumbs" aria-label="Percorso">
  <ol><li><a href="{root}index.html">Home</a></li><li><a href="{root}index.html#strutture">Strutture</a></li><li aria-current="page">{e(s['nome'])}</li></ol>
</nav>
<header class="stay__head">
  <h1 class="stay__title" data-hero-in>{e(s['nome'])}</h1>
  {sub}
  <p class="stay__facts" data-hero-in>
    <span>{icon('pin', 'icon icon--sm')}{e(luogo(s))} · {e(s['regione'])}</span>
    <span>{e(s['tipo'])}</span>
    {f"<span>{s['camere_totali']} camere</span>" if s.get('camere_totali') else ''}
  </p>
</header>
''')

    # galleria
    photos = ['hero'] + s['gallery']
    tiles = []
    for i, name in enumerate(photos[:5]):
        tiles.append(f'''<button type="button" class="gallery__tile{' gallery__tile--lead' if i == 0 else ''}" data-lightbox-open="{i}" aria-label="Apri la foto {i + 1} di {len(photos)}">
      {s_img(root, s, name, f"{s['nome']}, foto {i + 1}", lazy=i > 0, priority=i == 0)}
    </button>''')
    lb_items = json.dumps([f"{root}img/strutture/{s['slug']}/{n}.webp" for n in photos])
    out.append(f'''
<section class="gallery" aria-label="Foto della struttura">
  <div class="gallery__grid gallery__grid--{min(len(photos), 5)}" data-unveil-group>
    {''.join(tiles)}
  </div>
  <button type="button" class="btn btn--ghost gallery__all" data-lightbox-open="0">{icon('photos', 'icon icon--sm')}Tutte le foto ({len(photos)})</button>
  <script type="application/json" id="fma-gallery">{lb_items}</script>
</section>
''')

    # --- colonna principale
    main = []
    if s['intro']:
        main.append(f'<p class="stay__intro">{e(s["intro"])}</p>')
    else:
        main.append('<div class="empty"><p class="empty__title">La descrizione di questa casa è in arrivo.</p>'
                    '<p>Nel frattempo trovi qui sotto servizi, posizione e contatti. Per qualsiasi domanda scrivi direttamente alla struttura.</p></div>')
    if s['servizi']:
        li = ''.join(f'<li>{icon("check", "icon icon--sm")}{e(x)}</li>' for x in s['servizi'])
        main.append(f'<section class="block" aria-labelledby="h-servizi"><h2 id="h-servizi" class="block__title">Servizi</h2><ul class="amenities">{li}</ul></section>')

    # camere
    if s['camere']:
        cards = []
        for r in s['camere']:
            media = ''
            if r.get('img'):
                media = f'<div class="room__media">{s_img(root, s, r["img"], r["nome"])}</div>'
            facts = []
            if r.get('letti'):
                facts.append(f'<li><span>Letti</span>{e(r["letti"])}</li>')
            if r.get('ospiti'):
                facts.append(f'<li><span>Ospiti</span>fino a {r["ospiti"]}</li>')
            if r.get('quante'):
                facts.append(f'<li><span>Camere</span>{r["quante"]}</li>')
            cards.append(f'''<article class="room{' room--media' if media else ''}">
          {media}
          <div class="room__body">
            <h3 class="room__name">{e(r['nome'])}</h3>
            {f'<p class="room__desc">{e(r["dettaglio"])}</p>' if r.get('dettaglio') else ''}
            {f'<ul class="room__facts">{"".join(facts)}</ul>' if facts else ''}
            <button type="button" class="text-link room__pick" data-pick-room="{e(r['nome'])}">Richiedi questa camera {icon('arrow', 'icon icon--sm')}</button>
          </div>
        </article>''')
        main.append(f'<section class="block" aria-labelledby="h-camere"><h2 id="h-camere" class="block__title">Le camere</h2><div class="rooms">{"".join(cards)}</div></section>')
    else:
        tot = f" La casa dispone di {s['camere_totali']} camere." if s.get('camere_totali') else ''
        main.append(f'''<section class="block" aria-labelledby="h-camere"><h2 id="h-camere" class="block__title">Le camere</h2>
      <div class="empty"><p class="empty__title">Le tipologie di camera sono in aggiornamento.{tot}</p>
      <p>Indica nel modulo di richiesta di quante persone e di che sistemazione hai bisogno: ti risponde direttamente la casa.</p></div></section>''')

    for sez in s['sezioni']:
        slug = re.sub(r'[^a-z]+', '-', sez['titolo'].lower()).strip('-')[:40]
        main.append(f'<section class="block prose" aria-labelledby="h-{slug}"><h2 id="h-{slug}" class="block__title">{e(sez["titolo"])}</h2>{sez["html"]}</section>')

    if s['dintorni']:
        rows = ''.join(f'<div><dt>{e(d["luogo"])}</dt><dd>{e(d["distanza"])}</dd></div>' for d in s['dintorni'])
        main.append(f'<section class="block" aria-labelledby="h-dintorni"><h2 id="h-dintorni" class="block__title">Nei dintorni</h2><dl class="spec">{rows}</dl></section>')
    info = list(s['regole'])
    if s.get('orari'):
        info.insert(0, s['orari'])
    if s.get('tassa'):
        info.append(s['tassa'])
    if info:
        li = ''.join(f'<li>{e(x)}</li>' for x in info)
        main.append(f'<section class="block" aria-labelledby="h-sapere"><h2 id="h-sapere" class="block__title">Da sapere</h2><ul class="rules">{li}</ul></section>')

    gmaps = f"https://www.google.com/maps/dir/?api=1&destination={s['lat']},{s['lng']}"
    main.append(f'''<section class="block" aria-labelledby="h-dove"><h2 id="h-dove" class="block__title">Dove siamo</h2>
      <p class="where">{icon('pin', 'icon icon--sm')}{e(s['indirizzo'])}</p>
      <div class="map map--small" data-map-single data-lat="{s['lat']}" data-lng="{s['lng']}" data-name="{e(s['nome'])}" role="region" aria-label="Mappa: {e(s['nome'])}"></div>
      <p class="where__links"><a class="text-link" href="{gmaps}" rel="noopener" target="_blank">Indicazioni stradali {icon('external', 'icon icon--sm')}</a>
      {f'<a class="text-link" href="{e(s["booking_url"])}" rel="noopener" target="_blank">La casa anche su Booking.com {icon("external", "icon icon--sm")}</a>' if s['booking_url'] else ''}</p>
    </section>''')

    # --- colonna laterale: contatti + modulo
    tel = f'<a class="contact__row" href="tel:{c["telefono_link"]}">{icon("phone")}<span><span class="contact__label">Telefono</span>{e(c["telefono"])}</span></a>' if c['telefono'] else ''
    wa = f'<a class="contact__row" href="{c["whatsapp_link"]}" rel="noopener" target="_blank">{icon("chat")}<span><span class="contact__label">WhatsApp</span>{e(c["whatsapp"])}</span></a>' if c['whatsapp'] else ''
    mail = f'<a class="contact__row" href="mailto:{e(c["email"])}">{icon("mail")}<span><span class="contact__label">Email</span>{e(c["email"])}</span></a>' if c['email'] else ''
    sito_label = re.sub(r'^https?://(www\.)?', '', c['sito']).rstrip('/')
    web = f'<a class="contact__row" href="{e(c["sito"])}" rel="noopener" target="_blank">{icon("globe")}<span><span class="contact__label">Sito della casa</span>{e(sito_label)}</span></a>' if c['sito'] else ''
    lw, lh = dims(c['logo'].replace('.png', ''))
    price = f'<p class="contact__price">Indicativo: da <strong>{s["prezzo_da"]} €</strong> a notte</p>' if s.get('prezzo_da') else ''

    if s['camere']:
        room_opts = ''.join(f'<option>{e(r["nome"])}</option>' for r in s['camere'])
        room_opts += '<option>Più tipologie (gruppo)</option>'
    else:
        room_opts = '<option>Camera singola</option><option>Camera doppia</option><option>Camera per famiglia</option><option>Più camere (gruppo)</option>'
    today = date.today().isoformat()
    aside = f'''<aside class="stay__aside" data-aside aria-label="Contatti e richiesta di soggiorno">
    <div class="contact">
      <div class="contact__head">
        <img class="contact__logo" src="{root}img/{c['logo']}" alt="" width="{lw}" height="{lh}" loading="lazy">
        <div><p class="contact__kicker">Ti risponde</p><p class="contact__name">{e(c['nome'])}</p></div>
      </div>
      {price}
      <div class="contact__rows">{tel}{wa}{mail}{web}</div>
    </div>

    <form class="request" id="richiesta" data-request novalidate aria-labelledby="req-title">
      <h2 id="req-title" class="request__title">Richiedi un soggiorno</h2>
      <p class="request__hint">Senza impegno: la casa ti risponde con disponibilità e prezzi.</p>
      <div class="request__dates">
        <div class="field"><label for="r-arrivo">Arrivo</label><input id="r-arrivo" name="arrivo" type="date" min="{today}" required data-date-in aria-describedby="r-arrivo-err"><p class="field__err" id="r-arrivo-err"></p></div>
        <div class="field"><label for="r-partenza">Partenza</label><input id="r-partenza" name="partenza" type="date" min="{today}" required data-date-out aria-describedby="r-partenza-err"><p class="field__err" id="r-partenza-err"></p></div>
      </div>
      <p class="request__nights" data-nights aria-live="polite"></p>
      <fieldset class="request__people">
        <legend>Partecipanti</legend>
        <div class="field"><label for="r-adulti">Adulti</label>
          <div class="stepper" data-stepper><button type="button" data-step="-1" aria-label="Un adulto in meno">{icon('minus', 'icon icon--sm')}</button><input id="r-adulti" name="adulti" type="number" min="1" max="120" value="2" inputmode="numeric"><button type="button" data-step="1" aria-label="Un adulto in più">{icon('plus', 'icon icon--sm')}</button></div></div>
        <div class="field"><label for="r-bambini">Bambini</label>
          <div class="stepper" data-stepper><button type="button" data-step="-1" aria-label="Un bambino in meno">{icon('minus', 'icon icon--sm')}</button><input id="r-bambini" name="bambini" type="number" min="0" max="60" value="0" inputmode="numeric"><button type="button" data-step="1" aria-label="Un bambino in più">{icon('plus', 'icon icon--sm')}</button></div></div>
      </fieldset>
      <div class="field"><label for="r-camera">Tipologia di camera</label>
        <select id="r-camera" name="camera"><option value="">Da concordare con la casa</option>{room_opts}</select></div>
      <fieldset class="request__kind">
        <legend>Tipo di soggiorno</legend>
        <div class="kinds">
          <label><input type="radio" name="tipo" value="Vacanza" checked><span>Vacanza</span></label>
          <label><input type="radio" name="tipo" value="Famiglia"><span>Famiglia</span></label>
          <label><input type="radio" name="tipo" value="Gruppo o parrocchia"><span>Gruppo o parrocchia</span></label>
          <label><input type="radio" name="tipo" value="Ritiro spirituale"><span>Ritiro spirituale</span></label>
        </div>
      </fieldset>
      <div class="field"><label for="r-nome">Nome e cognome</label><input id="r-nome" name="nome" type="text" autocomplete="name" required aria-describedby="r-nome-err"><p class="field__err" id="r-nome-err"></p></div>
      <div class="field"><label for="r-email">Email</label><input id="r-email" name="email" type="email" autocomplete="email" required aria-describedby="r-email-err"><p class="field__err" id="r-email-err"></p></div>
      <div class="field"><label for="r-tel">Telefono <span class="opt">(facoltativo)</span></label><input id="r-tel" name="telefono" type="tel" autocomplete="tel"></div>
      <div class="field"><label for="r-msg">Messaggio <span class="opt">(facoltativo)</span></label><textarea id="r-msg" name="messaggio" rows="3" placeholder="Esigenze particolari, orario di arrivo, pasti…"></textarea></div>
      <label class="consent"><input type="checkbox" name="privacy" required><span>Ho letto l’<a href="https://accoglienzafma.com/privacy-policy/" rel="noopener" target="_blank">informativa privacy</a> e acconsento al trattamento dei dati per rispondere alla richiesta.</span></label>
      <p class="request__error" role="alert" hidden data-request-error></p>
      <button class="btn btn--primary request__send" type="submit"><span class="btn__label">Invia la richiesta</span><span class="btn__spinner" aria-hidden="true"></span></button>
      <p class="request__to">La richiesta arriva a <strong>{e(c['email'] or c['nome'])}</strong>.</p>
    </form>
    <div class="request-done" hidden data-request-done tabindex="-1">
      <span class="request-done__icon" aria-hidden="true">{icon('check')}</span>
      <h2 class="request__title">Richiesta pronta</h2>
      <p data-request-summary></p>
      <p class="request-done__demo">Questa è una demo: nessun messaggio è stato inviato. Nel sito WordPress la richiesta arriverà a {e(c['email'] or c['nome'])}.</p>
      <button type="button" class="text-link" data-request-again>Modifica la richiesta</button>
    </div>
  </aside>'''

    out.append(f'''
<div class="stay">
  <div class="stay__main">
    {''.join(main)}
    <div class="request-slot" data-request-slot></div>
  </div>
  {aside}
</div>
<div class="bottom-bar" data-bottom-bar hidden>
  <p><strong>{e(s['nome'])}</strong><span>{e(luogo(s))}</span></p>
  <a class="btn btn--primary" href="#richiesta">Richiedi un soggiorno</a>
</div>
''')

    # altre case
    altre = sorted([x for x in STRUTTURE if x['slug'] != s['slug']], key=lambda x: (x['regione'] != s['regione'], STRUTTURE.index(x)))[:3]
    cards = ''.join(f'''<li class="mini">
      <a href="{root}strutture/{x['slug']}/">
        <span class="mini__media">{s_img(root, x, 'card', '')}</span>
        <span class="mini__name">{e(x['nome'])}</span>
        <span class="mini__where">{e(luogo(x))} · {e(x['regione'])}</span>
      </a></li>''' for x in altre)
    out.append(f'''
<section class="others" aria-labelledby="others-title">
  <h2 id="others-title" class="section-title section-title--sm">Altre case</h2>
  <ul class="others__list">{cards}</ul>
</section>
''')
    out.append('</main>')
    out.append(footer(root))
    out.append(scripts(root, leaflet=True))
    return ''.join(out)


# ---------------------------------------------------------------- blog
def blog_index():
    root = '../'
    out = [head(root, f'Blog · {SITE}', 'Consigli di viaggio e storie dai luoghi delle case per ferie delle Salesiane.', 'blog')]
    out.append(masthead(root, 'Blog'))
    rows = []
    for i, a in enumerate(ARTICOLI):
        casa = BY_SLUG.get(ARTICOLO_CASA.get(a['slug'], ''))
        rows.append(f'''<li class="entry{' entry--lead' if i == 0 else ''}">
      <a href="{a['slug']}/">
        <span class="entry__media"{' data-unveil' if i == 0 else ''}>{img(root, f"blog/{a['slug']}.webp", '', *dims(f"blog/{a['slug']}"), lazy=i > 0, priority=i == 0)}</span>
        <span class="entry__body">
          <span class="post__meta"><span class="post__cat">{e(a['categoria'])}</span> · <time datetime="{a['data']}">{data_it(a['data'])}</time> · {a['minuti']} min</span>
          <span class="entry__title">{e(a['titolo'])}</span>
          <span class="entry__excerpt">{e(a['estratto'])}</span>
          {f'<span class="entry__near">{icon("pin", "icon icon--sm")}Vicino a {e(casa["nome"])}</span>' if casa else ''}
        </span>
      </a>
    </li>''')
    out.append(f'''<main id="contenuto">
<header class="page-head">
  <h1 class="page-head__title" data-hero-in>Blog</h1>
  <p class="page-head__lede" data-hero-in>Consigli di viaggio e storie dai luoghi delle nostre case: cosa vedere, quando andare, come arrivare.</p>
</header>
<ol class="entries">{''.join(rows)}</ol>
</main>''')
    out.append(footer(root))
    out.append(scripts(root))
    return ''.join(out)


def articolo(a):
    root = '../../'
    out = [head(root, f"{a['titolo']} · {SITE}", a['estratto'][:155], 'articolo')]
    out.append(masthead(root, 'Blog'))
    casa = BY_SLUG.get(ARTICOLO_CASA.get(a['slug'], ''))
    near = ''
    if casa:
        near = f'''<aside class="near" aria-label="Casa vicina">
      <a href="{root}strutture/{casa['slug']}/">
        <span class="near__media">{s_img(root, casa, 'card', '')}</span>
        <span class="near__body"><span class="near__kicker">Dormi vicino</span><span class="near__name">{e(casa['nome'])}</span><span class="near__where">{e(luogo(casa))}</span><span class="text-link">Scopri la casa {icon('arrow', 'icon icon--sm')}</span></span>
      </a>
    </aside>'''
    others = [x for x in ARTICOLI if x['slug'] != a['slug']][:2]
    more = ''.join(f'''<li class="post post--row"><a href="../{x['slug']}/">
      <span class="post__thumb">{img(root, f"blog/{x['slug']}.webp", '', *dims(f"blog/{x['slug']}"))}</span>
      <span class="post__body"><span class="post__meta"><time datetime="{x['data']}">{data_it(x['data'])}</time> · {x['minuti']} min</span><span class="post__title">{e(x['titolo'])}</span></span>
    </a></li>''' for x in others)
    w, h = dims(f"blog/{a['slug']}")
    out.append(f'''<main id="contenuto">
<nav class="crumbs" aria-label="Percorso"><ol><li><a href="{root}index.html">Home</a></li><li><a href="{root}blog/">Blog</a></li><li aria-current="page">{e(a['titolo'])}</li></ol></nav>
<article class="article">
  <header class="article__head">
    <p class="post__meta"><span class="post__cat">{e(a['categoria'])}</span> · <time datetime="{a['data']}">{data_it(a['data'])}</time> · {a['minuti']} min di lettura</p>
    <h1 class="article__title" data-hero-in>{e(a['titolo'])}</h1>
  </header>
  <figure class="article__media" data-unveil>{img(root, f"blog/{a['slug']}.webp", '', w, h, priority=True)}</figure>
  <div class="article__body prose">{a['html']}</div>
  {near}
</article>
<section class="others" aria-labelledby="more-title">
  <h2 id="more-title" class="section-title section-title--sm">Continua a leggere</h2>
  <ul class="journal__list journal__list--wide">{more}</ul>
</section>
</main>''')
    out.append(footer(root))
    out.append(scripts(root))
    return ''.join(out)


def write(rel, content):
    p = ROOT / rel
    p.parent.mkdir(parents=True, exist_ok=True)
    p.write_text(content)
    print('  ', rel)


if __name__ == '__main__':
    write('index.html', home())
    for s in STRUTTURE:
        write(f"strutture/{s['slug']}/index.html", struttura(s))
    write('blog/index.html', blog_index())
    for a in ARTICOLI:
        write(f"blog/{a['slug']}/index.html", articolo(a))
