#!/usr/bin/env python3
"""Integra le nuove città (Sorrento->Napoli) nel CSV con dati PageSpeed."""
import csv, urllib.parse, re, os, unicodedata

CSV='/home/user/experiences-demos/ricerca_clienti_napoli_salerno.csv'
TSV_IN='/tmp/new_cities.tsv'
PS='/tmp/psall2/summary.tsv'

# ---- load PageSpeed results keyed by name ----
ps={}
with open(PS,encoding='utf-8') as f:
    r=csv.reader(f,delimiter='\t'); next(r)
    for row in r:
        if len(row)<9: continue
        citta,name,url,perf,seo,bp,stack,booking,images=row[:9]
        ps[name]=dict(perf=perf,seo=seo,bp=bp,stack=stack,booking=booking,images=images)

# ---- load existing CSV ----
rows=list(csv.reader(open(CSV,encoding='utf-8')))
header=rows[0]
existing=[r for r in rows[1:] if r and r[0] and not r[0].startswith('===')]
existing_names={r[0].lower() for r in existing}
existing_urls={r[1].rstrip('/').lower() for r in existing}

LUX=re.compile(r'lusso|luxury|5 stelle|boutique|charme|dimora storica',re.I)

def gmaps(name,citta):
    q=urllib.parse.quote_plus(f"{name} {citta}")
    return f"https://www.google.com/maps/search/?api=1&query={q}"

def score(name,cat,note):
    p=ps.get(name,{})
    perf=p.get('perf',''); seo=p.get('seo','')
    stack=(p.get('stack') or '').lower(); booking=(p.get('booking') or '').lower()
    ok=perf.isdigit()
    seo_i=int(seo) if seo.isdigit() else -1
    if not ok:
        mobile='Incerto (PageSpeed fallito)'
    elif seo_i>=85: mobile=f'Sì (SEO {seo})'
    elif seo_i>=0: mobile=f'Parziale (SEO {seo})'
    else: mobile='Incerto'
    minimal=stack.strip() in ('','core-js')
    if not ok: pre='Incerto'
    elif minimal: pre='Sì (probabile)'
    elif any(x in stack for x in ['litelement','react','preact','vue','wp-rocket']): pre='No'
    elif 'joomla' in stack or 'yepnope' in stack or 'jquery ui' in stack: pre='Sì (probabile)'
    elif stack.startswith(('bootstrap','jquery')): pre='Incerto (stack classico)'
    else: pre='Incerto'
    cm=f'Sì ({booking})' if booking else ('No (non rilevato)' if ok else 'Incerto')
    # criteri: C1 datato C2 traffico C3 vendite C4 noCM C5 noblog(assunto No blog finché non verificato -> 0.5)
    c1=1.0 if pre.startswith('Sì') else (0.5 if 'Incerto' in pre else 0.0)
    big=bool(re.search(r'5 stelle|resort|160 camere|397 camere|grand hotel',cat+' '+note,re.I))
    small=bool(re.search(r'b&b|affittacamere|guest house|2 stelle',cat,re.I))
    c2=c3=1.0 if big else (0.0 if small else 0.5)
    c4=1.0 if (ok and not booking) else (0.0 if booking else 0.5)
    c5=0.5  # blog non verificato in questo passaggio
    crit=c1+c2+c3+c4+c5
    if crit>=4.0: prio=1
    elif crit>=3.0: prio=2
    elif crit>=2.0: prio=3
    else: prio=4
    lux=' LUSSO(plus).' if LUX.search(cat+' '+note) else ''
    ps_note=f"PageSpeed mobile: perf={p.get('perf','n/d')} seo={p.get('seo','n/d')} bp={p.get('bp','n/d')}. Stack: {p.get('stack') or 'n/d'}. Booking engine: {p.get('booking') or 'non rilevato'}.{lux} Blog non ancora verificato (conteggiato 0.5)."
    return crit,prio,pre,mobile,cm,ps_note

def brief(name,cat,citta,note):
    return (f"{name}; {cat}; {citta}; {note}; palette mediterranea su misura; "
            "sezioni: hero / camere-servizi / esperienze / posizione / contatti-prenota; "
            "lingue IT/EN; tono coerente col segmento; CTA 'Prenota ora / Richiedi disponibilità'")

new_rows=[]
skipped=[]
for line in open(TSV_IN,encoding='utf-8'):
    parts=line.rstrip('\n').split('\t')
    if len(parts)<6: continue
    citta,name,url,cat,email,note=parts[:6]
    if name.lower() in existing_names or url.rstrip('/').lower() in existing_urls:
        skipped.append(name); continue
    crit,prio,pre,mobile,cm,ps_note=score(name,cat,note)
    imgs=ps.get(name,{}).get('images','')
    new_rows.append([
        name,url,gmaps(name,citta),cat,prio,
        '' if email=='n/d' else email, imgs,'','2026-06-10','','No',
        citta,pre,mobile,cm,'Incerto',
        f"{crit:.1f}", f"{note}. | {ps_note}", brief(name,cat,citta,note)
    ])

RESUME=["=== RESUME POINT ===",'','','','','','','','2026-06-10','','','','','','','','',
 ("STOP 2026-06-10: completate SORRENTO (26 schede, analisi piena con blog verificato) + città Sorrento->Napoli: "
  "Sant'Agnello, Piano di Sorrento, Meta, Vico Equense, Castellammare di Stabia, Pompei, Torre Annunziata, Torre del Greco, Ercolano, Portici, Napoli "
  f"({len(new_rows)} nuove schede, PageSpeed eseguito su tutte). LIMITI nuove schede: campo Blog='Incerto' (non verificato, conteggiato 0.5 nei criteri) "
  "e brief demo in forma sintetica -> approfondire per i prospect priorità 1-2. Wayback bloccato in ambiente (anzianità stimata dallo stack). "
  "DA FARE alla ripresa: (1) verificare Blog e affinare brief per priorità 1-2 delle nuove città; (2) eventuale estensione a Salerno e provincia "
  "(Salerno città, Cava de' Tirreni, Vietri, Costiera Amalfitana: Amalfi/Positano/Maiori/Ravello, Paestum/Cilento). "
  "LEGENDA criteri (max 5, Incerto=0.5): C1 sito pre-2020 + C2 traffico + C3 vendite + C4 no channel manager + C5 no blog. Traffico/vendite=stime qualitative."),'']

out=[header]+existing+new_rows+[RESUME]
with open(CSV,'w',encoding='utf-8',newline='') as f:
    csv.writer(f,quoting=csv.QUOTE_MINIMAL).writerows(out)

print(f"Aggiunte {len(new_rows)} schede nuove; saltati duplicati: {skipped}")
print(f"Totale schede: {len(existing)+len(new_rows)}")
ranked=sorted(new_rows,key=lambda r:(int(r[4]),-float(r[16])))
print("\n=== Top nuovi prospect ===")
for r in ranked[:12]:
    print(f"  P{r[4]} crit={r[16]} | {r[11]} | {r[0]} | CM={r[14][:28]} | pre2020={r[12][:22]}")
