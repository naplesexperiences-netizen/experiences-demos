# Insectron Arena — prossimi passi, in ordine di priorità

Sintesi dei quattro piani più le cose che nei piani non stanno. Le giornate sono di
lavoro, non di calendario.

**Dove siamo.** Arena completa (tre modalità, sei rank, 34 Insector), sprite e animazioni
generati da codice, gabbie e allevamento, mondo e cattura con semi condivisibili,
salvataggi esportabili, eco ed edizione demo. 356 controlli automatici, equilibrio
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

## Priorità 2 — Che si rigiochi · 1,5 giornate

| Passo | Giornate | Perché fa tornare |
|---|---|---|
| **Sfida del giorno** | 1 | Un seme ricavato dalla data: tutti giocano la stessa identica battaglia, e a fine partita esce una riga da condividere. È l'unica meccanica di ritorno che non richiede né account né server — e il generatore col seme esiste già. Va instradato il caso della battaglia attraverso quel generatore (11 punti nel codice, pochi nella battaglia) |
| Collezione e traguardi | 0,5 | «Hai incontrato 17 Insector su 34», «mai perso un Re»: una schermata che mostra cosa manca è la ragione più economica che esista per riaprire il gioco |

## Priorità 3 — Che abbia fondo · 2 giornate

**Le sei resistenze in battaglia.** Si accumulano col cibo e la scheda dice onestamente
che «non contano ancora»: è l'unico punto dove il gioco ammette un pezzo incompiuto. La
macchina degli stati esiste già (stordimento, ribaltamento, vulnerabilità), quindi è
collegarle, non inventarle. Mezza giornata di rimisurazione compresa.

## Priorità 4 — Un obiettivo lungo · 2 giornate

**Riproduzione** (il resto di `PIANO-ALLEVAMENTO.md`): accoppiamenti, ereditarietà, 23
coppie speciali da scoprire. È la cosa che dà un motivo per tornare *per settimane*
invece che per giorni — ma ha senso solo dopo le tre sopra, altrimenti si aggiunge
profondità a un gioco che non trattiene ancora.

## Priorità 5 — Sapere davvero cosa succede · 0,5 giornate + un servizio

**Endpoint per la telemetria.** L'eco è già in casa e oggi raccoglie **solo su base
volontaria**: arriva quello che la gente decide di mandare, che è poco e distorto. Un
endpoint lo rende passivo e anonimo; nel gioco è cambiare una costante.

| Strada | Costo | Lavoro | Cosa comporta |
|---|---|---|---|
| **Cloudflare Worker + D1** | 0 € sul piano gratuito *(limiti da verificare)* | 0,5 gg | Il primo servizio del progetto da mantenere |
| Statistiche senza cookie a pagamento | ~9-14 €/mese | poche ore | Nessun codice nostro, ma uno script esterno nella pagina |
| Modulo o foglio (Google Form) | 0 € | poche ore | Raccoglie il testo, non l'imbuto: è quel che già facciamo col copia-incolla |
| Server proprio (VPS) | 4-6 €/mese | 1 gg | Manutenzione vera, aggiornamenti, sicurezza: sconsigliato qui |

**Quando**, non se: ha senso accenderlo quando c'è gente che gioca. Con dieci visite al
mese non c'è niente da raccogliere, e resta solo una cosa in più che si può rompere.

**Nota da verificare con un consulente prima di accendere:** passare da volontario a
passivo significa raccogliere senza che l'utente prema. Senza cookie, senza
identificatori e senza conservare l'IP la posizione resta pulita e l'informativa è di
dieci righe, ma è una verifica che va fatta, non data per scontata.

## Priorità 6 — Quando si esce di casa

| Passo | Giornate | Serve per |
|---|---|---|
| I 118 nomi | 0,5 | Prerequisito assoluto per vendere o stare in uno store (`PIANO-VENDITE.md` §10) |
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

**Audio** (1), **sfida del giorno** (1), **resistenze in battaglia** (2). Quattro
giornate che rendono il gioco più bello da giocare, gli danno un motivo per riaprirlo
domani e completano l'unica promessa rimasta aperta.

---

## Regola che vale per ogni passo

Ogni modifica che tocca l'equilibrio si **rimisura** prima di pubblicare, con gli
strumenti che esistono già (200 battaglie per scenario, pochi minuti). È così che
l'allevamento è stato ritarato quando la prima economia rendeva il Rank S una formalità.
