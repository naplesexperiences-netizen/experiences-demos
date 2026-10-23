#!/usr/bin/env python3
"""Costruisce i `seo.en.json` dai `seo.json` italiani piu' en-meta.py.

Il pacchetto SEO e' per nove decimi struttura identica fra le due
lingue — autore, editore, schema, tempo di lettura — e per un decimo
testo da tradurre. Scrivere a mano ventidue file quasi uguali e' il
modo migliore per infilarci una svista: qui la parte ripetitiva si
genera e in `en-meta.py` restano solo le stringhe tradotte.

    python3 blog-articles/build-seo-en.py            # tutti
    python3 blog-articles/build-seo-en.py 01 02      # solo questi
"""

import importlib.util
import json
import os
import re
import sys

BASE = os.path.dirname(os.path.abspath(__file__))
SITO = 'https://naplesexperiences.com'


def carica_meta():
    percorso = os.path.join(BASE, 'en-meta.py')
    spec = importlib.util.spec_from_file_location('en_meta', percorso)
    m = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(m)
    return m.CATEGORIE, m.ARTICOLI


def parole(html):
    testo = re.sub(r'<[^>]+>', ' ', html)
    return len(re.sub(r'\s+', ' ', testo).split())


def main():
    categorie, articoli = carica_meta()
    numeri = {n.zfill(2) for n in sys.argv[1:]}

    cartelle = sorted(
        d for d in os.listdir(BASE)
        if re.match(r'^\d\d-', d) and os.path.isdir(os.path.join(BASE, d))
    )
    scritti, saltati, problemi = [], [], []

    for cartella in cartelle:
        if numeri and cartella[:2] not in numeri:
            continue
        percorso = os.path.join(BASE, cartella)
        html_en = os.path.join(percorso, 'article.en.html')
        if not os.path.exists(html_en):
            saltati.append(cartella + ' (manca article.en.html)')
            continue

        with open(os.path.join(percorso, 'seo.json'), encoding='utf-8') as fh:
            it = json.load(fh)
        en = articoli.get(it['slug'])
        if not en:
            problemi.append('%s: slug %s assente da en-meta.py' % (cartella, it['slug']))
            continue

        categoria_en = categorie.get(it['category'])
        if not categoria_en:
            problemi.append('%s: categoria %r senza traduzione' % (cartella, it['category']))
            continue

        with open(html_en, encoding='utf-8') as fh:
            corpo = fh.read()

        url = '%s/en/blog/%s/' % (SITO, en['slug'])
        schema = dict(it['schema_article'])
        schema.update({
            'headline': en['post_title'],
            'description': en['meta_description'],
            'keywords': ', '.join(en['secondary_keywords']),
            'articleSection': categoria_en,
            'wordCount': parole(corpo),
            'inLanguage': 'en',
            'mainEntityOfPage': {'@type': 'WebPage', '@id': url},
        })

        fuori = {
            'slug': en['slug'],
            'lang': 'en',
            'translation_of': it['slug'],
            'post_title': en['post_title'],
            'seo_title': en['seo_title'],
            'meta_description': en['meta_description'],
            'focus_keyword': en['focus_keyword'],
            'secondary_keywords': en['secondary_keywords'],
            'og_title': en['og_title'],
            'og_description': en['og_description'],
            'category': categoria_en,
            'tags': en['tags'],
            'estimated_reading_time': it['estimated_reading_time'],
            'schema_article': schema,
        }
        if 'schema_faq' in it and 'faq' in en:
            fuori['schema_faq'] = {
                '@context': 'https://schema.org',
                '@type': 'FAQPage',
                'mainEntity': [
                    {
                        '@type': 'Question',
                        'name': d,
                        'acceptedAnswer': {'@type': 'Answer', 'text': r},
                    }
                    for d, r in en['faq']
                ],
            }

        # Controlli che altrimenti si scoprono solo a sito pubblicato.
        if len(en['seo_title']) > 62:
            problemi.append('%s: seo_title %d caratteri' % (cartella, len(en['seo_title'])))
        if len(en['meta_description']) > 160:
            problemi.append('%s: meta_description %d caratteri' % (cartella, len(en['meta_description'])))

        destinazione = os.path.join(percorso, 'seo.en.json')
        with open(destinazione, 'w', encoding='utf-8') as fh:
            json.dump(fuori, fh, ensure_ascii=False, indent=2)
            fh.write('\n')
        scritti.append('%s → %s (%d parole)' % (cartella, en['slug'], schema['wordCount']))

    for s in scritti:
        print('  scritto  ', s)
    for s in saltati:
        print('  saltato  ', s)
    for s in problemi:
        print('  PROBLEMA ', s)
    print('%d scritti, %d saltati, %d problemi' % (len(scritti), len(saltati), len(problemi)))
    return 1 if problemi else 0


if __name__ == '__main__':
    sys.exit(main())
