# Insectron Arena — prossimi passi, in ordine di priorità

Sintesi dei quattro piani più le cose che nei piani non stanno. Le giornate sono di
lavoro, non di calendario.

**Dove siamo.** Arena completa (tre modalità, sei rank, 34 Insector), sprite e animazioni
generati da codice, gabbie e allevamento, mondo e cattura con semi condivisibili,
salvataggi esportabili, eco, edizione demo, nomenclatura tutta nostra e le due schermate
prima della partita che stanno in una finestra sola. 702 controlli automatici, equilibrio
misurato a simulazione. Quel che manca non sono pezzi rotti: sono pezzi mancanti.

**La decisione che riordina tutto** *(27 settembre 2026)*: la demo si ferma finché il
gioco completo non è bello abbastanza da far tornare la gente. Giusto — una demo di un
gioco che non trattiene non misura il gioco, misura la demo. Quindi da qui in poi la fila
è ordinata su una domanda sola: **cosa fa tornare un giocatore il giorno dopo?**

Il meccanismo della demo **resta pronto**: `EDIZIONE`, `crea-demo.sh` e i suoi 14
controlli sono in casa, e la build si genera in un comando quando servirà.

---

## ~~Priorità 1 — Che sia piacevole~~ · **fatta** (27 settembre)

**Audio**: tredici suoni sintetizzati con WebAudio, nessun file, si spengono dalla
testata. **Guida del primo minuto**: quattro righe che compaiono quando servono e
spariscono da sole; non la vede chi ha già un torneo in corso. 41 controlli nuovi.

## ~~Priorità 2 — Che si rigiochi~~ · **fatta** (28 settembre)

**Sfida del giorno**: una battaglia uguale per tutti, dal seme della data, giocabile una
volta al giorno, col risultato da copiare in una riga. Ha richiesto di far passare tutto
il caso della battaglia da un punto solo, così due partite con le stesse scelte escono
identiche colpo per colpo. **Collezione e traguardi**: i 34 Insector si scoprono
incontrandoli, più otto traguardi. 48 controlli nuovi.

## ~~Priorità 3 — Che abbia fondo~~ · **fatta** (29 settembre)

**Le sei resistenze in battaglia.** Taglio ed Esplosione riducono il danno delle mosse di
quel tipo (3% a punto, tetto 30%), Knockback accorcia le spinte e insieme a Lancio dà una
presa sul bordo contro il ring-out, Confusione fa fallire ammaliamento e ribaltamento. Il
Veleno resta fermo e il gioco lo dice: nel roster giocabile non c'è una mossa che
avveleni, e inventarla sarebbe stato peggio. I sei cibi che davano solo resistenze erano
fuori dal premio perché inerti: adesso che contano, sono dentro. Rimisurata a 400
battaglie per scenario: una quota piccola di corazza pareggia le statistiche pure (48%
contro 46% al Rank S), una quota grossa peggiora (35%) — una scelta, non un potenziamento.
39 controlli nuovi. **Non resta nessuna promessa aperta nella scheda.**

## ~~Priorità 4 — Un obiettivo lungo~~ · **fatta** (27 settembre)

**Riproduzione**: due adulti danno un figlio del gradino successivo che eredita il 90% del
meglio, i genitori si consumano, e 18 coppie documentate danno risultati fuori linea.
Rimisurata: al Rank S una linea allevata sta al 46%, come le altre strade. 32 controlli
nuovi.

## ~~Priorità 5 — Sapere davvero cosa succede~~ · **costruita** (30 settembre), **spenta**

**Endpoint per la telemetria.** Fatto per intero e non acceso, che non è un rinvio ma
l'ordine giusto. Nel gioco: un rapporto per visita alla chiusura della pagina, con lo
stesso contenuto che il pannello mostra in chiaro, senza il commento libero, con
l'interruttore per spegnerlo nel menu; il testo del pannello lo genera il codice a partire
dalla costante, così non può promettere una cosa mentre il codice ne fa un'altra. Il
servizio in `telemetria/`: Worker Cloudflare più D1, che non legge mai l'IP, scrive solo le
colonne dichiarate, arrotonda l'ora alla mezz'ora e cancella tutto dopo dodici mesi. Più
`privacy.html`, pubblicata. 17 controlli nuovi nel gioco, 19 sull'endpoint, 11 sulla
landing e sull'informativa.

**Resta da fare una cosa sola, e non è codice**: la verifica di un consulente privacy sul
passaggio da volontario a passivo, e l'indirizzo email da mettere nell'informativa. Con
quelle due, accendere è cambiare `TELEMETRIA_URL` e alzare la versione del service worker —
procedura completa in `telemetria/README.md`.

*Com'era prima:* l'eco raccoglieva **solo su base volontaria**: arrivava quello che la
gente decideva di mandare, che è poco e distorto.

| Strada | Costo | Lavoro | Cosa comporta |
|---|---|---|---|
| **Cloudflare Worker + D1** | 0 € sul piano gratuito *(limiti da verificare)* | 0,5 gg | Il primo servizio del progetto da mantenere |
| Statistiche senza cookie a pagamento | ~9-14 €/mese | poche ore | Nessun codice nostro, ma uno script esterno nella pagina |
| Modulo o foglio (Google Form) | 0 € | poche ore | Raccoglie il testo, non l'imbuto: è quel che già facciamo col copia-incolla |
| Server proprio (VPS) | 4-6 €/mese | 1 gg | Manutenzione vera, aggiornamenti, sicurezza: sconsigliato qui |

**Quando**, non se: ha senso accenderlo quando c'è gente che gioca. Con dieci visite al
mese non c'è niente da raccogliere, e resta solo una cosa in più che si può rompere. Per
questo è stato costruito adesso e acceso dopo: il lavoro è fatto, il servizio si pubblica
in dieci minuti quando serve.

**Nota da verificare con un consulente prima di accendere:** passare da volontario a
passivo significa raccogliere senza che l'utente prema. Senza cookie, senza
identificatori e senza conservare l'IP la posizione resta pulita e l'informativa è di
dieci righe, ma è una verifica che va fatta, non data per scontata.

## ~~Fuori fila — Tutto in una schermata~~ · **fatta** (2 ottobre)

Non era in questa lista: è arrivata guardando il gioco su un telefono. Le due schermate
prima della partita si leggevano scorrendo — fino a **1069 px di scorrimento** su un
360×740 per arrivare in fondo al riepilogo, 417 su un monitor da 1280×900 per vedere la
scheda di un Insector. Adesso sono **zero**, su ogni schermo alto almeno 640 px.

Il roster elenca solo gli Insector disponibili invece di 27 caselle col lucchetto; la
scheda è un pop-up con dentro i pulsanti per scegliere; il riepilogo è fatto di soli
numeri, con la descrizione dietro a un tondo «i». Sul telefono la squadra scelta è una
striscia orizzontale da 65 px al posto di una colonna da 288, e il roster passa da una
pedina visibile a tre e mezza. Sotto i 640 px di altezza la pagina torna a scorrere, che
è peggio ma è l'unica cosa onesta. 38 controlli nuovi, dettaglio in `README.md`.

## Priorità 6 — Quando si esce di casa

| Passo | Giornate | Serve per |
|---|---|---|
| ~~I 118 nomi~~ · **fatto** (30 settembre) | 0,5 | Era il prerequisito assoluto per vendere o stare in uno store (`PIANO-VENDITE.md` §10). Nel gioco non resta nessun nome dell'originale; 20 controlli nuovi lo verificano a ogni giro |
| Il nome «Insectron» e la parola «Insector» | 0,5 | Sono le ultime due parole che vengono dal minigioco. Toccano URL, manifest, chiave di salvataggio e prefisso dei codici: decisione a sé, non un rinvio |
| Inglese | 2 | ~276 stringhe nel codice più 22 nell'HTML: senza, qualsiasi store serve solo l'Italia |
| Pacchetto Android (TWA) | 1 | Essere trovabili come app |
| **Demo** | *(già fatta, in attesa)* | Si pubblica quando il completo va altrove |

## Priorità 7 — Socialità

Una sola fra: **partita per link** (1 giornata, nessun servizio) o **multiplayer online**
(3,5 giornate e ~5 €/mese). Si sceglie guardando i numeri: se la gente torna da sola, la
riproduzione basta; se invita qualcuno, serve il multiplayer.

---

## Cosa non farei adesso

- **La mappa percorribile** (7,5 giornate, `PIANO-MONDO.md` forma B): è un'altra commessa,
  con due giornate di sola grafica di terreno che oggi non esiste.
- **Lo store interno con la valuta** (3-4 giornate e un servizio): rischia di disfare
  l'economia misurata, e comunque viene dopo il pubblico.
- **La pubblicità** sotto i diecimila giocatori al mese: rende una manciata di euro e
  costa una giornata e mezza più tutto lo stack del consenso.

## Se si potessero fare solo tre cose

Erano **audio** (1), **sfida del giorno** (1), **resistenze in battaglia** (2): quattro
giornate per rendere il gioco più bello da giocare, dargli un motivo per riaprirlo domani
e chiudere l'unica promessa rimasta aperta. Sono fatte tutte e tre. Se se ne potessero
fare altre tre, adesso: **endpoint della telemetria** (5), **nomi e inglese** (6), **link
play** (7) — cioè sapere cosa succede, farlo capire a chi non parla italiano, e dare due
giocatori allo stesso schermo anche quando non sono nella stessa stanza.

---

## Regola che vale per ogni passo

Ogni modifica che tocca l'equilibrio si **rimisura** prima di pubblicare, con gli
strumenti che esistono già (200 battaglie per scenario, pochi minuti). È così che
l'allevamento è stato ritarato quando la prima economia rendeva il Rank S una formalità.
