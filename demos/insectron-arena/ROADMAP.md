# Insectron Arena — prossimi passi, in ordine di priorità

Sintesi dei quattro piani più le cose che nei piani non stanno, messe in fila per
priorità. Le giornate sono di lavoro, non di calendario.

**Dove siamo.** Arena completa (tre modalità, sei rank, 34 Insector), sprite e animazioni
generati da codice, gabbie e allevamento, mondo e cattura con semi condivisibili,
salvataggi esportabili, sito installabile e offline. 315 controlli automatici, equilibrio
misurato a simulazione. Quel che manca non sono pezzi rotti: sono pezzi mancanti.

---

## Priorità 1 — Sapere cosa succede · 1 giornata

| Passo | Giornate | Perché sta sopra a tutto |
|---|---|---|
| Telemetria minima e domanda dentro al gioco | 0,5 | Il gioco **non misura niente**, per scelta. Senza l'imbuto, ogni decisione sotto si prende a sensazione |
| Demo «solo arena» | 0,5 | Due pulsanti e due schermate da chiudere dietro una costante, più i controlli che verificano che restino chiuse anche forzando le funzioni |

Non impegnano a niente e sbloccano tutto il resto.

## Priorità 2 — Il primo minuto · 1,5 giornate

| Passo | Giornate | Nota |
|---|---|---|
| **Audio** | 1 | Il gioco è completamente muto. Si genera da codice con WebAudio: nessun file, nessuna dipendenza |
| Primo minuto guidato | 0,5 | Oggi si entra e si trovano 34 unità. Bastano tre passaggi accompagnati fino alla prima partita |

## Priorità 3 — Mantenere una promessa già fatta · 2 giornate

**Le sei resistenze in battaglia.** Si accumulano col cibo e la scheda dice onestamente che
«non contano ancora»: è l'unico punto dove il gioco ammette un pezzo incompiuto. La macchina
degli stati esiste già (stordimento, ribaltamento, vulnerabilità), quindi è collegarle, non
inventarle. Mezza giornata di rimisurazione compresa.

## Priorità 4 — Togliere il vincolo · 0,5 giornate

**I 118 nomi** presi dall'originale (dettaglio in `PIANO-VENDITE.md`, §10). Serve per
vendere, per qualsiasi store e anche per l'inglese. Tutto il resto è già nostro.

## Priorità 5 — Raddoppiare il pubblico · 2 giornate

**Inglese.** Il gioco è solo in italiano: ~276 stringhe di testo nel codice più 22
nell'HTML. Vanno estratte, e va aggiunto il selettore. Senza, qualsiasi store serve solo
l'Italia.

## Priorità 6 — Una sola profondità, non tre

| Opzione | Giornate | Cosa aggiunge |
|---|---|---|
| Partita per link (`PIANO-MULTIPLAYER.md`, §14) | 1 | Gioco a distanza senza nessun servizio |
| Riproduzione (il resto di `PIANO-ALLEVAMENTO.md`) | 2 | Accoppiamenti, ereditarietà, 23 coppie speciali |
| Multiplayer online (`PIANO-MULTIPLAYER.md`) | 3,5 + ~5 €/mese | Socialità vera, e il primo servizio da mantenere |

Si sceglie **dopo** aver visto i numeri della demo: se la gente torna, la riproduzione; se
invita qualcuno, il multiplayer.

## Priorità 7 — Distribuzione · 1 giornata + il resto

Pacchetto Android (TWA) e, se i numeri lo giustificano, lo sblocco «togli la pubblicità».
Tutto in `PIANO-VENDITE.md`.

---

## Cosa non farei adesso

- **La mappa percorribile** (7,5 giornate, `PIANO-MONDO.md` forma B): è un'altra commessa,
  con due giornate di sola grafica di terreno che oggi non esiste.
- **Lo store interno con la valuta** (3-4 giornate e un servizio da mantenere): è l'unico
  punto del progetto che richiede infrastruttura, e rischia di disfare l'economia misurata.
- **La pubblicità** sotto i diecimila giocatori al mese: rende una manciata di euro e costa
  una giornata e mezza più tutto lo stack del consenso.

## Se si potessero fare solo tre cose

Telemetria e demo (1), audio (1), resistenze in battaglia (2). **Quattro giornate** che
rendono il gioco più finito e danno i numeri per decidere tutto il resto.

---

## Regola che vale per ogni passo

Ogni modifica che tocca l'equilibrio si **rimisura** prima di pubblicare, con gli strumenti
che esistono già (200 battaglie per scenario, pochi minuti). È così che l'allevamento è
stato ritarato quando la prima economia rendeva il Rank S una formalità.
