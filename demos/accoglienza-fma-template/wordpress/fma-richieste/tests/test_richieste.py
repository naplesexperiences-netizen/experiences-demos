import json, os, subprocess, time, urllib.parse, urllib.request, datetime

BASE = 'http://127.0.0.1:8899'
WPDIR = os.environ.get('WPDIR', os.path.join(os.path.dirname(__file__), 'wp', 'wordpress'))
WPCLI = os.environ.get('WPCLI', os.path.join(os.path.dirname(WPDIR), 'wp-cli.phar'))
LOG = os.path.join(WPDIR, 'wp-content', 'mail-log.json')
opener = urllib.request.build_opener(urllib.request.ProxyHandler({}))


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


noredir = urllib.request.build_opener(urllib.request.ProxyHandler({}), NoRedirect)


def wp(*args):
    return subprocess.run(['php', WPCLI, '--allow-root', *args], cwd=WPDIR, capture_output=True, text=True).stdout.strip()


def reset():
    if os.path.exists(LOG):
        os.remove(LOG)
    wp('transient', 'delete', '--all')


def mails():
    return json.load(open(LOG)) if os.path.exists(LOG) else []


oggi = datetime.date.today()
A = (oggi + datetime.timedelta(days=20)).isoformat()
P = (oggi + datetime.timedelta(days=23)).isoformat()


def dati(**kw):
    d = {'action': 'fma_richiesta', 'struttura_id': '4', 'arrivo': A, 'partenza': P, 'adulti': '2', 'bambini': '1',
         'camera': 'Camera doppia', 'tipo': 'Famiglia', 'nome': 'Anna Esposito', 'email': 'anna@ospite.test',
         'telefono': '333 1234567', 'messaggio': 'Arriviamo verso sera.', 'privacy': '1', 'fma_t': str(int(time.time()) - 60), 'ajax': '1'}
    d.update(kw)
    return {k: v for k, v in d.items() if v is not None}


def post(d, ajax=True, referer=BASE + '/strutture/villa-tiberiade/'):
    req = urllib.request.Request(BASE + '/wp-admin/admin-post.php', data=urllib.parse.urlencode(d).encode(), headers={'Referer': referer})
    try:
        r = (opener if ajax else noredir).open(req)
        return r.status, r.read().decode(), dict(r.headers)
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode(), dict(e.headers)


ok = fail = 0


def check(cond, msg):
    global ok, fail
    ok += bool(cond)
    fail += not cond
    print(('PASS ' if cond else 'FAIL ') + msg)


def hdr(m, name):
    h = m['headers'] if isinstance(m['headers'], list) else [m['headers']]
    return [x for x in h if x.lower().startswith(name.lower() + ':')]


# 1 · richiesta valida → email alla struttura + copia all'ospite
reset()
s, b, _ = post(dati())
j = json.loads(b)
m = mails()
check(s == 200 and j['ok'], f'richiesta valida → 200 ok ({j["messaggio"]})')
check(len(m) == 2, f'due email inviate (struttura + ospite): {len(m)}')
check(m[0]['to'] == 'tiberiade@case.test', f'destinatario = email della struttura: {m[0]["to"]}')
check(hdr(m[0], 'Reply-To') == ['Reply-To: Anna Esposito <anna@ospite.test>'], f'Reply-To = ospite: {hdr(m[0], "Reply-To")}')
check('Villa Tiberiade' in m[0]['message'] and '3 notti' in m[0]['message'] and '2 adulti, 1 bambino' in m[0]['message'] and 'Camera doppia' in m[0]['message'], 'riepilogo completo nel corpo')
check(m[1]['to'] == 'anna@ospite.test' and hdr(m[1], 'Reply-To') == ['Reply-To: Villa Tiberiade <tiberiade@case.test>'], 'copia all’ospite con Reply-To della casa')
print('   oggetto:', m[0]['subject'])

# 2 · ogni struttura riceve le sue richieste
reset()
post(dati(struttura_id='5'))
check(mails()[0]['to'] == 'tabor@case.test', 'Villa Tabor → tabor@case.test')
reset()
post(dati(struttura_id='7'))
check(mails()[0]['to'] == 'napoli@case.test', 'struttura RealHomes (property) → email del referente collegato')

# 3 · il destinatario non si può forzare dal modulo
reset()
post(dati(to='evil@x.test', fma_email='evil@x.test', destinatario='evil@x.test', email_struttura='evil@x.test'))
check(all('evil' not in json.dumps(x) for x in mails()) and mails()[0]['to'] == 'tiberiade@case.test', 'campi extra ignorati: nessuna email a indirizzi arbitrari')

# 4 · header injection nel nome
reset()
post(dati(nome='Mario Rossi\r\nBcc: evil@x.test'))
m = mails()
check(m and hdr(m[0], 'Reply-To') == ['Reply-To: Mario Rossi Bcc evil x.test <anna@ospite.test>'] and not any('bcc:' in x.lower() for x in m[0]['headers']), f'nessuna intestazione iniettata: {hdr(m[0], "Reply-To")}')

# 5 · validazione
reset()
s, b, _ = post(dati(arrivo='', partenza='', nome='', email='non-email', privacy=None))
j = json.loads(b)
check(s == 422 and set(j['campi']) == {'arrivo', 'partenza', 'nome', 'email', 'privacy'} and not mails(), f'campi mancanti → 422, errori per campo: {sorted(j["campi"])}')
ieri = (oggi - datetime.timedelta(days=1)).isoformat()
s, b, _ = post(dati(arrivo=ieri))
check(s == 422 and 'arrivo' in json.loads(b)['campi'], 'arrivo nel passato → errore')
s, b, _ = post(dati(arrivo=P, partenza=A))
check(s == 422 and 'partenza' in json.loads(b)['campi'], 'partenza prima dell’arrivo → errore')
s, b, _ = post(dati(arrivo='2026-02-31'))
check(s == 422 and 'arrivo' in json.loads(b)['campi'], 'data inesistente → errore')
s, b, _ = post(dati(adulti='0'))
check(s == 422 and 'adulti' in json.loads(b)['campi'] and not mails(), 'zero adulti → errore, nessuna email')

# 6 · strutture non valide
for sid, label in [('8', 'senza email'), ('9', 'in bozza'), ('10', 'articolo, non struttura'), ('999', 'inesistente')]:
    s, b, _ = post(dati(struttura_id=sid))
    check(s == 422 and not json.loads(b)['ok'] and not mails(), f'struttura {label} → errore, nessuna email ({json.loads(b)["messaggio"]})')

# 7 · antispam
reset()
s, b, _ = post(dati(fma_sito='http://spam.test'))
check(s == 200 and json.loads(b)['ok'] and not mails(), 'honeypot compilato → risposta ok ma nessuna email')
s, b, _ = post(dati(fma_t=str(int(time.time()))))
check(s == 200 and not mails(), 'invio in meno di 3 secondi → nessuna email')

# 8 · invio non riuscito
reset()
wp('option', 'update', 'fma_test_mail_fail', '1')
s, b, _ = post(dati())
wp('option', 'delete', 'fma_test_mail_fail')
j = json.loads(b)
check(s == 422 and 'tiberiade@case.test' in j['messaggio'], f'wp_mail fallisce → errore con email di riserva ({j["messaggio"]})')

# 9 · senza JavaScript → redirect alla pagina con esito
reset()
s, b, h = post(dati(ajax=None), ajax=False)
loc = h.get('Location', '')
check(s in (302, 303) and loc.endswith('/strutture/villa-tiberiade/?richiesta=inviata#richiesta') and len(mails()) == 2, f'POST classico → redirect {loc}')
s, b, h = post(dati(ajax=None, email='x'), ajax=False)
check('richiesta=errore' in h.get('Location', ''), 'POST classico con errori → ?richiesta=errore')
html = opener.open(BASE + '/strutture/villa-tiberiade/?richiesta=inviata').read().decode()
check('data-request-done tabindex="-1">' in html and 'id="richiesta"' in html and '<form class="request" id="richiesta"' in html and 'hidden>' in html.split('id="richiesta"')[1][:400], 'pagina con ?richiesta=inviata mostra la conferma')

# 10 · limite per IP
reset()
esiti = [json.loads(post(dati())[1])['ok'] for _ in range(6)]
check(esiti == [True] * 5 + [False] and len(mails()) == 10, f'limite: 5 richieste ogni 15 minuti per IP, la sesta è respinta ({esiti})')

print(f'\n{ok} pass · {fail} fail')
debug = os.path.join(WPDIR, 'wp-content', 'debug.log')
if os.path.exists(debug):
    print('debug.log:\n' + open(debug).read()[-1500:])
