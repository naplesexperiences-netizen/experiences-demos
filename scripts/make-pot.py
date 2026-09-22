#!/usr/bin/env python3
"""Genera experiences-srl.pot dalle stringhe marcate nel tema."""
import re, pathlib, datetime, collections

ROOT = pathlib.Path('/home/user/experiences-demos/experiences-theme')
DOMAIN = 'experiences-srl'

# __( 'testo', 'experiences-srl' )  /  esc_html__( 'testo', 'experiences-srl' )
CALL = re.compile(
    r"\b(?:esc_html__|esc_attr__|__)\s*\(\s*"
    r"'((?:[^'\\]|\\.)*)'"
    r"\s*,\s*'" + re.escape(DOMAIN) + r"'\s*\)"
)

entries = collections.OrderedDict()
for php in sorted(ROOT.rglob('*.php')):
    rel = php.relative_to(ROOT).as_posix()
    text = php.read_text(encoding='utf-8')
    for m in CALL.finditer(text):
        raw = m.group(1)
        # Unescape PHP single-quoted: \' e \\ sono le uniche sequenze reali.
        msgid = raw.replace("\\'", "'").replace('\\\\', '\\')
        line = text.count('\n', 0, m.start()) + 1
        entries.setdefault(msgid, []).append(f'{rel}:{line}')

def po_escape(s):
    return s.replace('\\', '\\\\').replace('"', '\\"').replace('\n', '\\n').replace('\t', '\\t')

year = datetime.date.today().year
out = [
    '# Copyright (C) %d Experiences Srl' % year,
    '# This file is distributed under the same license as the Experiences Srl theme.',
    'msgid ""',
    'msgstr ""',
    '"Project-Id-Version: Experiences Srl 2.6.0\\n"',
    '"Report-Msgid-Bugs-To: naplesexperiences@gmail.com\\n"',
    '"MIME-Version: 1.0\\n"',
    '"Content-Type: text/plain; charset=UTF-8\\n"',
    '"Content-Transfer-Encoding: 8bit\\n"',
    '"POT-Creation-Date: %s\\n"' % datetime.datetime.now(datetime.timezone.utc).strftime('%Y-%m-%dT%H:%M:%S+00:00'),
    '"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n"',
    '"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n"',
    '"Language-Team: LANGUAGE <LL@li.org>\\n"',
    '"Plural-Forms: nplurals=2; plural=(n != 1);\\n"',
    '"X-Domain: %s\\n"' % DOMAIN,
    '',
]
for msgid, refs in entries.items():
    out.append('#: ' + ' '.join(refs))
    out.append('msgid "%s"' % po_escape(msgid))
    out.append('msgstr ""')
    out.append('')

dest = ROOT / 'languages' / (DOMAIN + '.pot')
dest.parent.mkdir(parents=True, exist_ok=True)
dest.write_text('\n'.join(out), encoding='utf-8')
print(f'{dest}: {len(entries)} stringhe da {len({r.split(":")[0] for rs in entries.values() for r in rs})} file')
