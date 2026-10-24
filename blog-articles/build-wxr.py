#!/usr/bin/env python3
"""Costruisce un file WXR (WordPress eXtended RSS) dagli articoli in questa cartella.

Legge `article.html` e `seo.json` di ogni cartella `NN-slug/` e produce un
import pronto per Strumenti -> Importa -> WordPress, con categoria, tag,
data di pubblicazione e meta SEO (Yoast / Rank Math / SEOPress) gia
assegnati.

    python3 blog-articles/build-wxr.py                 # tutti gli articoli
    python3 blog-articles/build-wxr.py 19 20 21 22     # solo questi
    python3 blog-articles/build-wxr.py --lang en       # la versione inglese

Con `--lang en` legge `article.en.html` e `seo.en.json`: stessi articoli,
slug e termini inglesi. La lingua NON viene scritta nel WXR, perche'
l'importer di WordPress non sa niente di Polylang: la assegna dopo
l'import la pagina Strumenti -> Collega traduzioni del tema, che accoppia
anche gli articoli e le categorie fra le due lingue. Nel frattempo ogni
articolo porta un postmeta `_exp_lang`, che e' il riferimento se qualcosa
va rifatto a mano.

Il file esce in `blog-articles/import-wordpress.xml` piu la variante
compressa `.xml.gz`, che serve quando l'importer di WordPress tronca
l'upload e risponde "numero di versione WXR mancante o errato".
"""

import argparse
import datetime
import glob
import gzip
import json
import os
import re
import shutil
import sys
import unicodedata
import xml.etree.ElementTree as ET

BASE = os.path.dirname(os.path.abspath(__file__))
SITE = 'https://naplesexperiences.com'
AUTHOR = 'admin'
# Gli id partono alti per non collidere con i contenuti gia presenti.
FIRST_POST_ID = 9000
FIRST_TERM_ID = 9500

NS = {
    'excerpt': 'http://wordpress.org/export/1.2/excerpt/',
    'content': 'http://purl.org/rss/1.0/modules/content/',
    'wfw': 'http://wellformedweb.org/CommentAPI/',
    'dc': 'http://purl.org/dc/elements/1.1/',
    'wp': 'http://wordpress.org/export/1.2/',
}


def term_slug(name, lang='it'):
    """Slug WordPress per una categoria o un tag."""
    s = unicodedata.normalize('NFKD', name).encode('ascii', 'ignore').decode()
    s = s.lower().replace('&', ' e ' if lang == 'it' else ' and ')
    s = re.sub(r"[^a-z0-9]+", '-', s)
    return s.strip('-')


def slug_italiani():
    """Slug dei termini che gli articoli italiani hanno gia' sul sito.

    Serve per i termini inglesi: l'importer di WordPress riconosce un
    termine dallo slug, non dalla lingua. Un tag inglese che si scrive
    come quello italiano — `instagram`, `reels`, `social media` — verrebbe
    agganciato al termine italiano esistente, e l'articolo inglese si
    ritroverebbe un tag di un'altra lingua, invisibile sotto /en/.

    Categorie e tag sono tassonomie diverse e possono avere lo stesso
    slug senza darsi noia, quindi i due insiemi restano separati.
    """
    slugs = {'category': set(), 'post_tag': set()}
    for percorso in glob.glob(os.path.join(BASE, '[0-9][0-9]-*', 'seo.json')):
        with open(percorso, encoding='utf-8') as fh:
            seo = json.load(fh)
        slugs['category'].add(term_slug(seo['category']))
        for tag in seo.get('tags', []):
            slugs['post_tag'].add(term_slug(tag))
    return slugs


def rfc822(date_str):
    d = datetime.date.fromisoformat(date_str)
    dt = datetime.datetime(d.year, d.month, d.day, 9, 0, 0)
    return dt.strftime('%a, %d %b %Y %H:%M:%S +0000'), dt.strftime('%Y-%m-%d %H:%M:%S')


def load(folder, lang='it'):
    nomi = {
        'it': ('seo.json', 'article.html'),
        'en': ('seo.en.json', 'article.en.html'),
    }[lang]
    with open(os.path.join(folder, nomi[0]), encoding='utf-8') as fh:
        seo = json.load(fh)
    with open(os.path.join(folder, nomi[1]), encoding='utf-8') as fh:
        body = fh.read().strip()
    return seo, body


def sub(parent, tag, text=None, cdata=False):
    el = ET.SubElement(parent, tag)
    if text is not None:
        el.text = text
        # ElementTree serializza <tag /> quando il testo e vuoto: un nodo
        # senza tag di chiusura non puo ospitare una CDATA, e marcarlo
        # lascerebbe l'attributo segnaposto nel file.
        if cdata and text != '':
            el.set('_cdata', '1')
    return el


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('numbers', nargs='*', help='prefissi numerici da includere (default: tutti)')
    ap.add_argument('--lang', default='it', choices=['it', 'en'],
                    help='lingua da esportare (default: it)')
    ap.add_argument('--out', help='file di uscita (default: import-wordpress[-en].xml)')
    ap.add_argument('--status', default='draft', choices=['draft', 'publish', 'future'],
                    help='post_status degli articoli importati (default: draft)')
    args = ap.parse_args()

    lang = args.lang
    if not args.out:
        nome = 'import-wordpress.xml' if lang == 'it' else 'import-wordpress-en.xml'
        args.out = os.path.join(BASE, nome)
    # Gli id degli articoli inglesi partono piu' in alto: non serve a
    # WordPress, che li riassegna, ma tiene i due file distinguibili.
    first_post_id = FIRST_POST_ID + (0 if lang == 'it' else 100)
    url_blog = '%s/blog/%%s/' % SITE if lang == 'it' else '%s/en/blog/%%s/' % SITE
    occupati = slug_italiani() if lang == 'en' else {'category': set(), 'post_tag': set()}

    def slug_termine(name, taxonomy):
        s = term_slug(name, lang)
        return s + '-en' if s in occupati[taxonomy] else s

    folders = sorted(glob.glob(os.path.join(BASE, '[0-9][0-9]-*')))
    if args.numbers:
        wanted = {n.zfill(2) for n in args.numbers}
        folders = [f for f in folders if os.path.basename(f)[:2] in wanted]
    if not folders:
        sys.exit('nessun articolo selezionato')

    for ns, uri in NS.items():
        ET.register_namespace(ns, uri)

    rss = ET.Element('rss', {'version': '2.0'})
    # Le dichiarazioni xmlns dei prefissi effettivamente usati le scrive
    # ElementTree: ripeterle qui a mano produce "duplicate attribute".
    # wfw non compare in nessun nodo (non esportiamo commenti), quindi
    # quella va dichiarata, per restare uguali a un export di WordPress.
    rss.set('xmlns:wfw', NS['wfw'])
    channel = ET.SubElement(rss, 'channel')
    sub(channel, 'title', 'Experiences Srl Blog')
    sub(channel, 'link', SITE)
    sub(channel, 'description', 'Articoli B2B per hotel e tour operator')
    sub(channel, 'pubDate', rfc822(datetime.date.today().isoformat())[0])
    sub(channel, 'language', 'it-IT' if lang == 'it' else 'en-US')
    sub(channel, '{%s}wxr_version' % NS['wp'], '1.2')
    sub(channel, '{%s}base_site_url' % NS['wp'], SITE)
    sub(channel, '{%s}base_blog_url' % NS['wp'], SITE)

    author = ET.SubElement(channel, '{%s}author' % NS['wp'])
    sub(author, '{%s}author_id' % NS['wp'], '1')
    sub(author, '{%s}author_login' % NS['wp'], AUTHOR, cdata=True)
    sub(author, '{%s}author_display_name' % NS['wp'], 'Experiences Srl', cdata=True)

    # Dichiarazione dei termini: senza, l'importer crea le categorie ma non
    # sempre i tag, e il nome con gli accenti si perde.
    term_id = FIRST_TERM_ID
    seen_cat, seen_tag = {}, {}
    articles = []
    for folder in folders:
        seo, body = load(folder, lang)
        articles.append((folder, seo, body))
        if seo['category'] not in seen_cat:
            seen_cat[seo['category']] = term_id
            term_id += 1
        for tag in seo.get('tags', []):
            if tag not in seen_tag:
                seen_tag[tag] = term_id
                term_id += 1

    for name, tid in seen_cat.items():
        el = ET.SubElement(channel, '{%s}category' % NS['wp'])
        sub(el, '{%s}term_id' % NS['wp'], str(tid))
        sub(el, '{%s}category_nicename' % NS['wp'], slug_termine(name, 'category'), cdata=True)
        sub(el, '{%s}category_parent' % NS['wp'], '', cdata=True)
        sub(el, '{%s}cat_name' % NS['wp'], name, cdata=True)

    for name, tid in seen_tag.items():
        el = ET.SubElement(channel, '{%s}tag' % NS['wp'])
        sub(el, '{%s}term_id' % NS['wp'], str(tid))
        sub(el, '{%s}tag_slug' % NS['wp'], slug_termine(name, 'post_tag'), cdata=True)
        sub(el, '{%s}tag_name' % NS['wp'], name, cdata=True)

    post_id = first_post_id
    for folder, seo, body in articles:
        schema = seo.get('schema_article', {})
        date = schema.get('datePublished') or datetime.date.today().isoformat()
        pub, sql = rfc822(date)
        url = url_blog % seo['slug']

        item = ET.SubElement(channel, 'item')
        sub(item, 'title', seo['post_title'])
        sub(item, 'link', url)
        sub(item, 'pubDate', pub)
        sub(item, '{%s}creator' % NS['dc'], AUTHOR, cdata=True)
        sub(item, 'guid', url).set('isPermaLink', 'false')
        sub(item, 'description', '')
        sub(item, '{%s}encoded' % NS['content'], body, cdata=True)
        sub(item, '{%s}encoded' % NS['excerpt'], seo.get('meta_description', ''), cdata=True)
        sub(item, '{%s}post_id' % NS['wp'], str(post_id))
        sub(item, '{%s}post_date' % NS['wp'], sql, cdata=True)
        sub(item, '{%s}post_date_gmt' % NS['wp'], sql, cdata=True)
        sub(item, '{%s}post_modified' % NS['wp'], sql, cdata=True)
        sub(item, '{%s}post_modified_gmt' % NS['wp'], sql, cdata=True)
        sub(item, '{%s}comment_status' % NS['wp'], 'closed', cdata=True)
        sub(item, '{%s}ping_status' % NS['wp'], 'closed', cdata=True)
        sub(item, '{%s}post_name' % NS['wp'], seo['slug'], cdata=True)
        sub(item, '{%s}status' % NS['wp'], args.status, cdata=True)
        sub(item, '{%s}post_parent' % NS['wp'], '0')
        sub(item, '{%s}menu_order' % NS['wp'], '0')
        sub(item, '{%s}post_type' % NS['wp'], 'post', cdata=True)
        sub(item, '{%s}post_password' % NS['wp'], '', cdata=True)
        sub(item, '{%s}is_sticky' % NS['wp'], '0')

        cat = ET.SubElement(item, 'category', {
            'domain': 'category', 'nicename': slug_termine(seo['category'], 'category')})
        cat.text = seo['category']
        cat.set('_cdata', '1')
        for tag in seo.get('tags', []):
            el = ET.SubElement(item, 'category', {'domain': 'post_tag', 'nicename': slug_termine(tag, 'post_tag')})
            el.text = tag
            el.set('_cdata', '1')

        # Meta SEO per i tre plugin piu diffusi: quello installato legge le
        # sue chiavi, gli altri restano come postmeta inerte.
        meta = {
            '_yoast_wpseo_title': seo.get('seo_title', ''),
            '_yoast_wpseo_metadesc': seo.get('meta_description', ''),
            '_yoast_wpseo_focuskw': seo.get('focus_keyword', ''),
            '_yoast_wpseo_opengraph-title': seo.get('og_title', ''),
            '_yoast_wpseo_opengraph-description': seo.get('og_description', ''),
            'rank_math_title': seo.get('seo_title', ''),
            'rank_math_description': seo.get('meta_description', ''),
            'rank_math_focus_keyword': seo.get('focus_keyword', ''),
            '_seopress_titles_title': seo.get('seo_title', ''),
            '_seopress_titles_desc': seo.get('meta_description', ''),
            '_seopress_analysis_target_kw': seo.get('focus_keyword', ''),
            # Riferimenti nostri, utili in fase di revisione.
            '_exp_lang': lang,
            '_exp_translation_of': seo.get('translation_of', ''),
            '_exp_reading_time': str(seo.get('estimated_reading_time', '')),
            '_exp_secondary_keywords': ', '.join(seo.get('secondary_keywords', [])),
            '_exp_schema_article': json.dumps(schema, ensure_ascii=False),
        }
        if 'schema_faq' in seo:
            meta['_exp_schema_faq'] = json.dumps(seo['schema_faq'], ensure_ascii=False)
        for key, value in meta.items():
            if value in ('', None):
                continue
            pm = ET.SubElement(item, '{%s}postmeta' % NS['wp'])
            sub(pm, '{%s}meta_key' % NS['wp'], key, cdata=True)
            sub(pm, '{%s}meta_value' % NS['wp'], value, cdata=True)

        post_id += 1

    xml = ET.tostring(rss, encoding='unicode')
    # ElementTree non sa scrivere le CDATA: i nodi marcati con _cdata vengono
    # riavvolti qui, dopo aver annullato l'escape che ha applicato lui.
    def unescape(txt):
        return (txt.replace('&lt;', '<').replace('&gt;', '>')
                   .replace('&quot;', '"').replace('&#10;', '\n')
                   .replace('&amp;', '&'))

    def wrap(match):
        open_tag = re.sub(r'\s*_cdata="1"', '', match.group(1))
        # Un ']]>' nel contenuto chiuderebbe la CDATA in anticipo.
        inner = unescape(match.group(3)).replace(']]>', ']]]]><![CDATA[>')
        return '%s<![CDATA[%s]]>%s' % (open_tag, inner, match.group(4))

    xml = re.sub(r'(<([\w:-]+)[^>]*?\s_cdata="1"[^>]*>)(.*?)(</\2>)', wrap, xml, flags=re.S)
    if '_cdata=' in xml:
        sys.exit('marcatore _cdata rimasto nel file: la riscrittura CDATA non ha coperto tutto')

    with open(args.out, 'w', encoding='utf-8') as fh:
        fh.write('<?xml version="1.0" encoding="UTF-8" ?>\n')
        fh.write(xml)
        fh.write('\n')

    with open(args.out, 'rb') as src, gzip.open(args.out + '.gz', 'wb') as dst:
        shutil.copyfileobj(src, dst)

    size = os.path.getsize(args.out)
    print('%s — lingua %s, %d articoli, %d categorie, %d tag, %.1f KB (+ .gz)' % (
        os.path.relpath(args.out, BASE), lang,
        len(articles), len(seen_cat), len(seen_tag), size / 1024))
    if lang == 'en':
        collisi = sorted(
            [n for n in seen_cat if term_slug(n, 'en') in occupati['category']]
            + [n for n in seen_tag if term_slug(n, 'en') in occupati['post_tag']]
        )
        if collisi:
            print('  slug con suffisso -en (coincidevano con un termine italiano): %s'
                  % ', '.join(collisi))


if __name__ == '__main__':
    main()
