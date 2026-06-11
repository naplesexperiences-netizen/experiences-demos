#!/usr/bin/env python3
"""Esegue PageSpeed (5 thread) su isole_del_golfo_RAW.tsv.
Uso: PAGESPEED_API_KEY=xxx python3 ps_driver.py
Input  : isole_del_golfo_RAW.tsv  (col: area, citta, nome, url, categoria, email, note)
Output : isole_del_golfo_pagespeed.tsv (col: area,citta,name,url,perf,seo,bp,stack,booking,images)
"""
import json, subprocess, urllib.parse, re, csv, os, sys
from concurrent.futures import ThreadPoolExecutor

KEY=os.environ.get('PAGESPEED_API_KEY','')
if not KEY:
    sys.exit("Imposta PAGESPEED_API_KEY")
HERE=os.path.dirname(os.path.abspath(__file__))
IN=os.path.join(HERE,'isole_del_golfo_RAW.tsv')
OUT=os.path.join(HERE,'isole_del_golfo_pagespeed.tsv')
BOOKING=r'simplebooking|verticalbooking|vertical-booking|blastness|ericsoft|ermeshotels|scidoo|slope|octorate|bedzzle|booking-?expert|hotelrunner|bookassist|d-edge|decms|cendyn|bokun|fareharbor|regiondo|tourcms|getyourguide|viator|benvenuto|triptease|hotelinone|wubook|krossbooking|beddy|nozio|parityrate|cloudbeds'

rows=[l.rstrip('\n').split('\t') for l in open(IN,encoding='utf-8') if l.strip()]

def work(r):
    area,citta,name,url=r[0],r[1],r[2],r[3]
    api=f"https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url={urllib.parse.quote(url,safe='')}&strategy=mobile&category=performance&category=seo&category=best-practices&key={KEY}"
    try:
        res=subprocess.run(['curl','-sS','-m','90',api],capture_output=True,text=True,timeout=110)
        d=json.loads(res.stdout)
    except Exception:
        return [area,citta,name,url,'ERR','','','','','']
    lr=d.get('lighthouseResult',{})
    if not lr: return [area,citta,name,url,'FAIL','','','','','']
    cats=lr.get('categories',{}); audits=lr.get('audits',{})
    def sc(c):
        v=cats.get(c,{}).get('score'); return str(int(v*100)) if v is not None else ''
    perf,seo,bp=sc('performance'),sc('seo'),sc('best-practices')
    stacks=[s.get('id') for s in (lr.get('stackPacks') or [])]
    libs=[i.get('name') for i in audits.get('js-libraries',{}).get('details',{}).get('items',[])]
    stack='|'.join(filter(None,stacks+libs))[:60]
    host=urllib.parse.urlparse(lr.get('finalUrl','')).hostname or ''
    base='.'.join(host.split('.')[-2:]) if host else ''
    booking=set(); imgs=[]
    for it in audits.get('network-requests',{}).get('details',{}).get('items',[]):
        u=it.get('url','')
        if not u.startswith('http'): continue
        h=(urllib.parse.urlparse(u).hostname or '')
        m=re.search(BOOKING,h+u,re.I)
        if m: booking.add(m.group(0).lower())
        if it.get('resourceType','')=='Image' and base and base in h and not re.search(r'logo|icon|sprite|favicon|pixel|1x1',u,re.I):
            if u not in imgs: imgs.append(u)
    return [area,citta,name,url,perf,seo,bp,stack,'|'.join(sorted(booking)),' '.join(imgs[:3])]

with open(OUT,'w',encoding='utf-8',newline='') as f:
    w=csv.writer(f,delimiter='\t')
    w.writerow(['area','citta','name','url','perf','seo','bp','stack','booking','images'])
    done=0
    with ThreadPoolExecutor(max_workers=5) as ex:
        for res in ex.map(work,rows):
            w.writerow(res); f.flush(); done+=1
print(f"BATCH_DONE {done}")
