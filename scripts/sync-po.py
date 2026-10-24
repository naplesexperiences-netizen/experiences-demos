#!/usr/bin/env python3
"""Allinea un .po al .pot e ne compila il .mo.

    python3 scripts/make-pot.py          # prima: aggiorna il modello
    python3 scripts/sync-po.py           # poi: allinea en_US.po e scrive en_US.mo

Fa tre cose, in ordine:

1. **Allinea.** Rimette le voci del .po nell'ordine del .pot con i
   riferimenti aggiornati, aggiunge le stringhe nuove con `msgstr ""` e
   butta quelle che nel tema non esistono piu'. Le traduzioni che c'erano
   restano come sono: il .po e' la sorgente, non un file generato.
2. **Controlla.** Se restano `msgstr ""` elenca quali e esce con stato 1
   senza toccare il .mo. Il .po allineato resta sul disco, con i buchi in
   vista, da riempire a mano.
3. **Compila.** Scrive il .mo, che e' quello che WordPress legge.

Il .mo lo costruiamo qui perche' `msgfmt` non e' detto che ci sia. Il
formato e' quello del manuale GNU gettext: intestazione, due tabelle di
(lunghezza, offset) — prima gli originali, poi le traduzioni, in ordine
di byte del msgid — e in fondo i dati. La tabella hash e' facoltativa e
resta a zero: il lettore di WordPress la salta.

Attenzione a dove va il file: un **tema** carica
`languages/{locale}.mo`, un **plugin** `languages/{domain}-{locale}.mo`.
Sbagliare convenzione non da' nessun errore, semplicemente non traduce.
"""

import argparse
import os
import re
import struct
import sys

BASE = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
LINGUE = os.path.join(BASE, 'experiences-theme', 'languages')

INTESTAZIONE = [
    ('Project-Id-Version', 'Experiences Srl'),
    ('MIME-Version', '1.0'),
    ('Content-Type', 'text/plain; charset=UTF-8'),
    ('Content-Transfer-Encoding', '8bit'),
    ('Plural-Forms', 'nplurals=2; plural=(n != 1);'),
    ('X-Generator', 'scripts/make-pot.py + scripts/sync-po.py'),
]


def po_escape(s):
    return (s.replace('\\', '\\\\').replace('"', '\\"')
             .replace('\n', '\\n').replace('\t', '\\t'))


def po_unescape(s):
    return re.sub(
        r'\\(.)',
        lambda m: {'n': '\n', 't': '\t', '"': '"', '\\': '\\'}.get(m.group(1), m.group(1)),
        s,
    )


def leggi_po(percorso):
    """{msgid: (msgstr, [riferimenti])}, msgid e msgstr non escapati."""
    if not os.path.exists(percorso):
        return {}
    voci = {}
    for blocco in open(percorso, encoding='utf-8').read().split('\n\n'):
        mid = re.search(r'^msgid "((?:[^"\\]|\\.)*)"', blocco, re.M)
        mstr = re.search(r'^msgstr "((?:[^"\\]|\\.)*)"', blocco, re.M)
        if not mid or not mstr:
            continue
        if mid.group(1) == '':
            continue                 # l'intestazione la riscriviamo noi
        voci[po_unescape(mid.group(1))] = (
            po_unescape(mstr.group(1)),
            re.findall(r'^#: (.+)$', blocco, re.M),
        )
    return voci


def scrivi_po(percorso, locale, voci, commento):
    righe = list(commento)
    righe += ['msgid ""', 'msgstr ""', '"Language: %s\\n"' % locale]
    for chiave, valore in INTESTAZIONE:
        righe.append('"%s: %s\\n"' % (chiave, valore))
    righe.append('')
    for msgid, (msgstr, riferimenti) in voci.items():
        for rif in riferimenti:
            righe.append('#: %s' % rif)
        righe.append('msgid "%s"' % po_escape(msgid))
        righe.append('msgstr "%s"' % po_escape(msgstr))
        righe.append('')
    testo = '\n'.join(righe)
    with open(percorso, 'w', encoding='utf-8') as fh:
        fh.write(testo)
    return testo


def scrivi_mo(percorso, locale, voci):
    testata = 'Language: %s\n' % locale
    testata += ''.join('%s: %s\n' % kv for kv in INTESTAZIONE)

    coppie = [('', testata)] + [(m, v[0]) for m, v in voci.items()]
    coppie.sort(key=lambda c: c[0].encode('utf-8'))

    originali = [c[0].encode('utf-8') for c in coppie]
    tradotte = [c[1].encode('utf-8') for c in coppie]
    n = len(coppie)

    off_orig = 7 * 4
    off_trad = off_orig + n * 8
    off_dati = off_trad + n * 8

    tab_o, tab_t, dati = b'', b'', b''
    cursore = off_dati
    for s in originali:
        tab_o += struct.pack('<II', len(s), cursore)
        dati += s + b'\x00'
        cursore += len(s) + 1
    for s in tradotte:
        tab_t += struct.pack('<II', len(s), cursore)
        dati += s + b'\x00'
        cursore += len(s) + 1

    mo = struct.pack('<IiIIIII', 0x950412de, 0, n, off_orig, off_trad, 0, 0)
    mo += tab_o + tab_t + dati
    with open(percorso, 'wb') as fh:
        fh.write(mo)
    return len(mo)


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('locale', nargs='?', default='en_US',
                    help='locale da allineare (default: en_US)')
    ap.add_argument('--pot', default=os.path.join(LINGUE, 'experiences-srl.pot'))
    ap.add_argument('--dir', default=LINGUE, help='cartella dei file di lingua')
    args = ap.parse_args()

    if not os.path.exists(args.pot):
        sys.exit('manca %s: lancia prima scripts/make-pot.py' % args.pot)

    # Dal .pot: msgid in ordine, con i riferimenti freschi.
    modello = []
    for blocco in open(args.pot, encoding='utf-8').read().split('\n\n'):
        mid = re.search(r'^msgid "((?:[^"\\]|\\.)*)"', blocco, re.M)
        if not mid or mid.group(1) == '':
            continue
        modello.append((po_unescape(mid.group(1)), re.findall(r'^#: (.+)$', blocco, re.M)))

    percorso_po = os.path.join(args.dir, '%s.po' % args.locale)
    percorso_mo = os.path.join(args.dir, '%s.mo' % args.locale)
    vecchie = leggi_po(percorso_po)

    voci = {}
    for msgid, riferimenti in modello:
        voci[msgid] = (vecchie.get(msgid, ('', []))[0], riferimenti)

    nuove = [m for m in voci if m not in vecchie]
    rimosse = [m for m in vecchie if m not in voci]

    scrivi_po(percorso_po, args.locale, voci, [
        '# Traduzione %s del tema Experiences Srl.' % args.locale,
        '# Questo file e la sorgente: il .mo si rigenera da qui con',
        '# scripts/sync-po.py, non si modifica a mano.',
    ])

    print('%s: %d voci (%d nuove, %d rimosse)'
          % (os.path.relpath(percorso_po, BASE), len(voci), len(nuove), len(rimosse)))
    for m in rimosse:
        print('  rimossa  %r' % m[:70])

    mancanti = [m for m, v in voci.items() if v[0] == '']
    if mancanti:
        print('\n%d stringhe senza traduzione: il .mo non e stato toccato.' % len(mancanti))
        for m in mancanti:
            print('  da tradurre  %r' % m[:70])
        print('\nRiempi i msgstr vuoti in %s e rilancia.'
              % os.path.relpath(percorso_po, BASE))
        return 1

    byte = scrivi_mo(percorso_mo, args.locale, voci)
    print('%s: %d byte' % (os.path.relpath(percorso_mo, BASE), byte))
    return 0


if __name__ == '__main__':
    sys.exit(main())
