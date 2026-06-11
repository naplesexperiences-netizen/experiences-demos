#!/usr/bin/env python3
"""Genera 3 CSV per area (Costiera Amalfitana, Salerno, Cilento) dallo schema unificato.
Input: salerno_amalfi_cilento_RAW.tsv (area,citta,nome,url,categoria,email,note)
       salerno_amalfi_cilento_pagespeed.tsv (area,citta,name,url,perf,seo,bp,stack,booking,images)
Output: CSV_<area>.csv nella cartella corrente.
"""
import csv, os, re, urllib.parse

HERE=os.path.dirname(os.path.abspath(__file__))
RAW=os.path.join(HERE,'salerno_amalfi_cilento_RAW.tsv')
PS=os.path.join(HERE,'salerno_amalfi_cilento_pagespeed.tsv')
TODAY='2026-06-10'

COLS=["Nome_Azienda","URL_Sito","Google_Business_Link","Categoria","Priorita","Email",
 "Immagini","Link_Demo","Data_Generazione","Data_Contatto","Contattato","Citta",
 "Sito_Pre2020","Mobile_Friendly","Channel_Manager","Blog","Criteri_Soddisfatti","Note","Brief_Demo_OpenClaw"]

ps={}
with open(PS,encoding='utf-8') as f:
    r=csv.reader(f,delimiter='\t'); next(r)
    for row in r:
        if len(row)>=10:
            ps[row[2]]=dict(perf=row[4],seo=row[5],bp=row[6],stack=row[7],booking=row[8],images=row[9])

LUX=re.compile(r'lusso|luxury|5 stelle|boutique|charme|dimora storica|relais|ultra',re.I)
SMALL=re.compile(r'b&b|bed & breakfast|affittacamere|guest|2 stelle|economy|budget|camping|villaggio',re.I)
BIG=re.compile(r'5 stelle|resort|grand hotel|leading hotels|relais & chateaux|ultra-luxury|165 camere|villaggio',re.I)

def gmaps(name,citta):
    return "https://www.google.com/maps/search/?api=1&query="+urllib.parse.quote_plus(f"{name} {citta}")

def analyze(name,cat,note):
    p=ps.get(name,{})
    perf=p.get('perf',''); seo=p.get('seo','')
    stack=(p.get('stack') or '').lower(); booking=(p.get('booking') or '').lower()
    ok=perf.isdigit(); seo_i=int(seo) if seo.isdigit() else -1
    if not ok: mobile='Da analizzare (no PageSpeed)'
    elif seo_i>=85: mobile=f'Sì (SEO {seo})'
    elif seo_i>=0: mobile=f'Parziale (SEO {seo})'
    else: mobile='Incerto'
    minimal=stack.strip() in ('','core-js')
    if not ok: pre='Da analizzare'
    elif minimal: pre='Sì (probabile)'
    elif any(x in stack for x in ['litelement','react','preact','vue','wp-rocket','svelte','next']): pre='No'
    elif 'joomla' in stack or 'yepnope' in stack or 'jquery ui' in stack or 'mootools' in stack: pre='Sì (probabile)'
    elif stack.startswith(('bootstrap','jquery','wordpress')): pre='Incerto (stack classico)'
    else: pre='Incerto'
    cm=f'Sì ({booking})' if booking else ('No (non rilevato)' if ok else 'Da analizzare')
    c1=1.0 if pre.startswith('Sì') else (0.5 if 'Incerto' in pre else 0.0)
    txt=cat+' '+note
    if BIG.search(txt) and not SMALL.search(cat): c2=c3=1.0
    elif SMALL.search(cat): c2=c3=0.0
    else: c2=c3=0.5
    c4=1.0 if (ok and not booking) else (0.0 if booking else 0.5)
    c5=0.5  # blog non verificato
    crit=c1+c2+c3+c4+c5
    if crit>=4.0: prio=1
    elif crit>=3.0: prio=2
    elif crit>=2.0: prio=3
    else: prio=4
    lux=' LUSSO(plus).' if LUX.search(txt) else ''
    psnote=f"PageSpeed: perf={p.get('perf','n/d')} seo={p.get('seo','n/d')} bp={p.get('bp','n/d')}. Stack: {p.get('stack') or 'n/d'}. Booking engine: {p.get('booking') or 'non rilevato'}.{lux} Blog non verificato (0.5)."
    return crit,prio,pre,mobile,cm,psnote,p.get('images','')

def brief(name,cat,citta,note):
    return (f"{name}; {cat}; {citta}; {note}; palette mediterranea coerente col segmento; "
            "sezioni: hero / camere e servizi / esperienze e territorio / posizione / contatti-prenota; "
            "lingue IT/EN; CTA 'Prenota ora / Richiedi disponibilità'")

# raggruppa per area
areas={}
with open(RAW,encoding='utf-8') as f:
    for line in f:
        p=line.rstrip('\n').split('\t')
        if len(p)<7: continue
        area,citta,name,url,cat,email,note=p[:7]
        areas.setdefault(area,[]).append((citta,name,url,cat,email,note))

summary={}
for area,items in areas.items():
    rows=[COLS]
    for citta,name,url,cat,email,note in items:
        crit,prio,pre,mobile,cm,psnote,imgs=analyze(name,cat,note)
        rows.append([name,url,gmaps(name,citta),cat,prio,
            '' if email=='n/d' else email,imgs,'',TODAY,'','No',citta,
            pre,mobile,cm,'Incerto',f"{crit:.1f}",f"{note}. | {psnote}",brief(name,cat,citta,note)])
    slug=re.sub(r'[^A-Za-z]+','_',area).strip('_')
    fn=os.path.join(HERE,f'CSV_{slug}.csv')
    with open(fn,'w',encoding='utf-8',newline='') as f:
        csv.writer(f,quoting=csv.QUOTE_MINIMAL).writerows(rows)
    summary[area]=(fn,len(items))
    print(f"{area}: {len(items)} schede -> {os.path.basename(fn)}")

print("\nTOP per area:")
for area,items in areas.items():
    scored=[]
    for citta,name,url,cat,email,note in items:
        crit,prio,*_=analyze(name,cat,note)
        scored.append((prio,-crit,name,citta))
    scored.sort()
    print(f"\n== {area} ==")
    for prio,ncrit,name,citta in scored[:5]:
        print(f"  P{prio} crit={-ncrit:.1f} | {name} ({citta})")
