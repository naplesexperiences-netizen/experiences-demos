# Insectron Arena — demo tattica

Prototipo giocabile di battaglia a turni 5v5 su griglia, costruito a partire dalla
documentazione del minigioco **Insectron** di *Rogue Galaxy* (PS2, Level-5 / Sony).

**File**: `index.html` (autonomo, nessuna dipendenza, nessun build step) ·
`insectors.json` (dataset grezzo estratto dal wiki)

---

## Fonti

1. **Rogue Galaxy Wiki** (Fandom, CC BY-SA): pagina `Insector` + 35 schede unità →
   statistiche, famiglie, rank, torneo, avversari.
2. **In-Depth FAQ/Walkthrough di Paul Michael "VHAYSTE"** (GameFAQs) → i **diagrammi
   ASCII di ogni mossa speciale e di ogni range di movimento**, che il wiki non ha.
   È da qui che arrivano le regole di targeting precise.

## Cosa c'è di autentico (dal wiki)

Estratto dalla pagina `Insector` della Rogue Galaxy Wiki e dalle 35 schede unità collegate:

| Elemento | Dettaglio |
|---|---|
| **34 Insector** | nome, famiglia, rank (1-6), HP/Str/Def minimi, descrizione |
| **13 famiglie** | ognuna con la propria mossa speciale e il testo descrittivo originale |
| **Range di movimento** | `3×3` (1 casella), `7×7` (volo, 3 caselle), `3 diagonali`, `2 dritto avanti` |
| **Regola del Re** | squadra da 5, uno è il Re; se cade hai perso; il Re si muove di 1 casella anche se la famiglia ne concede di più |
| **Torneo** | 6 rank (E→S), 5 round ciascuno, quote d'iscrizione (400z → 6000z), nomi dei 30 avversari, premi |
| **Ring-out** | Scissor Throw, Wing Flap, Itsakick e Crushing Horn possono buttare un Insector fuori dal campo |

Le mosse speciali sono implementate seguendo la descrizione del wiki, non solo citate:

- **Jumping Stab** — danno singolo maggiorato
- **Crushing Horn** — spinta di 1 casella + reazione a catena su chi sta dietro
- **Cannon Blast** — attacco in linea retta fino a 3 caselle
- **Over Easy** — ribaltamento, bersaglio immobilizzato 2 turni
- **Scissor Throw** — lancio alle spalle del lanciatore, danno extra se la casella è occupata, ring-out se è fuori campo
- **Sickle Dance** — colpisce tutte le 8 caselle adiacenti
- **Body Blow** — carica in linea retta, l'attaccante resta scoperto (+30% danni subiti)
- **Healing Jig** — cura gli alleati adiacenti e rimuove l'immobilizzo
- **Fill Hole** — blocca completamente i 2 attacchi successivi
- **Itsakick** — colpisce davanti e dietro con spinta
- **Charm Beam** — converte un avversario, al costo di metà stamina
- **Wing Flap** — spinge via di 2 caselle tutti gli adiacenti
- **The Emperor's Rage** — colpisce tutti i nemici entro 2 caselle + stordimento

## Regole di targeting (dai diagrammi della FAQ)

Il wiki descriveva le mosse a parole; la FAQ le disegna casella per casella. La
differenza è sostanziale: **quasi tutte le speciali colpiscono solo le 4 caselle
ortogonali**, non le 8 attorno. Posizionarsi in diagonale è quindi una difesa reale.

| Mossa | Area effettiva |
|---|---|
| Jumping Stab, Crushing Horn, Over Easy, Scissor Throw, Charm Beam, Healing Jig | 4 caselle ortogonali |
| Sickle Dance | tutte e 8 le caselle attorno (unica eccezione) |
| Cannon Blast, Wing Flap | croce ortogonale fino a 2 caselle; qualsiasi Insector in mezzo, **amico o nemico**, blocca |
| Body Blow | solo dritto in avanti, fino a 2 caselle, l'attaccante si sposta in posizione |
| Itsakick | solo la casella davanti e quella dietro |
| Emperor's Rage | bersaglio singolo entro 2 caselle in ogni direzione |
| Attacco normale | tutte e 8 le caselle attorno |

Altre correzioni che la FAQ ha imposto:

- **Scissor Throw non fa danno diretto.** Il danno viene solo da dove atterra il
  bersaglio: se finisce addosso a qualcuno, **rimbalza a catena** ferendo entrambi,
  e continua finché non trova una casella libera o esce dal campo.
- **Over Easy** non immobilizza soltanto: chi è ribaltato è anche **vulnerabile**
  (+30% danni subiti) per i 2 turni.
- **Healing Jig non cura la Lady Beetle stessa.**
- **Movimento dell'Itsahorse**: 1 casella in qualsiasi direzione, oppure 2 in linea
  retta in una delle 8 direzioni. Il wiki diceva solo "2 caselle dritto in avanti".
- **Dark Emperor**: la furia fulminea è a bersaglio singolo con **cariche infinite**
  (nessuna ricarica), e il suo **attacco normale** atterra l'avversario e lo sbalza di
  una casella, con rimbalzo a catena ed eventuale uscita dal campo. È questo che lo
  rende il Re migliore, come dice il wiki.

## Schieramento delle pedine

Prima di ogni battaglia si posizionano le 5 pedine, una alla volta, **entro le due file
più vicine** (12 caselle). Il pannello laterale elenca la squadra con il range di
movimento di ciascuna, così la scelta si fa con i dati sott'occhio: le unità lente
convengono avanti, il Re coperto dietro.

- clic su una casella illuminata → piazza la pedina evidenziata
- clic su una pedina già in campo → torna in panchina, pronta per un'altra casella
- **Schiera a caso** riempie solo le caselle rimaste vuote, senza spostare ciò che hai
  già posizionato
- **Inizia battaglia** si attiva solo a schieramento completo

Gli avversari sono schierati dalla CPU nelle loro due file, con il Re nell'ultima.

## Progressione del roster

Si comincia con le sole **forme base**: i 7 Insector di rank 1 (Faerie, Flipperbug,
Hercules Beetle, Itsahorse, Knife Beetle, Mantis, Staggy). Tutto il resto è visibile nel
roster ma bloccato, con indicata la condizione di sblocco.

| Rank in corso | Insector disponibili |
|---|---|
| E (debutto) | 7 — solo forme base |
| D | 20 |
| C | 27 |
| B | 31 |
| A e S | 33 |
| dopo aver vinto il Rank S | 34, Dark Emperor compreso |

Il **Dark Emperor** resta fuori fino alla vittoria del Rank S: sul wiki si cattura solo
dopo aver finito il gioco almeno una volta, ed è il Re più forte disponibile.

Questo sostituisce il sistema di cattura e riproduzione, che non è implementato: al suo
posto è il torneo a "far crescere" il roster. La soglia di sblocco segue il tetto della
fascia da cui pescano gli avversari del rank corrente — altrimenti si combatterebbe
sempre con una generazione di svantaggio (misurato: dal Rank D in poi le vittorie
crollavano dal 61% al 29%).

## Cosa è ricostruzione di design (non documentato sul wiki)

Queste scelte sono nostre e si possono cambiare in un punto solo del codice:

1. **Griglia 6×6.** Nessuna delle due fonti indica la dimensione del campo — verificato
   cercandola in entrambe. Sei colonne tengono 10 unità con densità sensata. Costante `N`.
2. **Formula di danno.** Assente da entrambe le fonti.
   `max(str×0.3, str×2 − def) × moltiplicatore × (0.9…1.1)`.
   Il pavimento al 30% della forza serve a evitare che un DEF alto renda un'unità
   letteralmente invulnerabile agli attaccanti deboli (Orion Beetle ha DEF 32
   contro STR 14 della Faerie). Funzione `strike()`.
3. **Scalatura difficoltà.** Le squadre avversarie usano un moltiplicatore di
   statistiche crescente per rank (0.80 → 1.15) e pescano da fasce di rank
   sempre più alte. Campo `mul` in `RANKS`.
4. **Cooldown 3 turni** sulle mosse speciali: nel gioco originale la gestione è
   diversa, qui serve a evitare lo spam della stessa mossa.
5. **IA avversaria.** Priorità: speciale se conviene → attacco al bersaglio più
   debole (Re in priorità) → avvicinamento. Il Re nemico resta coperto finché ha
   almeno un compagno vivo. Funzione `aiAct()`.
6. **Un movimento e un'azione per unità per turno.** Lo spostamento si può fare una
   volta sola; dopo si può ancora attaccare o usare la speciale, ma attaccare chiude il
   turno della pedina. Attacco e speciale non si sommano.

## Discrepanze fra le fonti

- **Premio del Rank B**: il wiki dice *Devil Forks*, la FAQ dice *Spirit Calibur*.
  Nel demo resta il valore del wiki.
- **Movimento dei volanti**: il wiki dice 7×7 (3 caselle), i diagrammi della FAQ si
  fermano a 2 perché la griglia disegnata è 5×5. Vale il wiki: il testo della FAQ
  stessa cita "flying insectrons (3 square movement range)".

## Cosa NON c'è (esiste sul wiki ma è fuori dallo scopo di una demo)

- Cattura con trappole ed esche, luoghi di spawn, probabilità
- Riproduzione, ereditarietà delle statistiche, special breeding, alberi delle famiglie
  (sostituiti dallo sblocco per rank descritto sopra)
- Sistema di alimentazione (tabella dei 22 cibi con effetti su HP/Str/Def/resistenze e costo in punti vita)
- Le 6 resistenze (Knockback, Confusion, Cut, Explosion, Throw, Poison): i valori sono
  nel dataset `insectors.json` ma non sono ancora usati in battaglia
- Le 136 unità complete: il wiki ha schede dettagliate solo per 35
- Colore, sesso, condizione, satietà, aspettativa di vita
- Modalità Vs. con password a 118 caratteri
- Modalità **"Eliminate the enemy"** (documentata nella FAQ per le partite Vs.: nessun
  Re designato, si vince solo abbattendo tutti). Implementabile rapidamente: cambia
  solo la condizione di vittoria
- Famiglie senza scheda statistiche sul wiki e quindi senza unità giocabili, per cui
  la FAQ documenta comunque la mossa: Hopper (Giant Leap), Springtail (Hypnotasm),
  Stingbee (Poison Needle), Bombsnail (Bomb Drop), Silkspider (Sticky Net)

## Grafica

Tutto disegnato per questo prototipo, generato da codice: nessun file immagine nel repo.

**Personaggi.** La funzione `art(famiglia, ruotato, rank)` compone un SVG per ciascuna
famiglia. Ogni SVG riceve un id di gradiente univoco: con id ripetuti il browser risolve
`url(#id)` alla prima definizione del documento, e se quella sta in una sezione
`display:none` il gradiente non viene dipinto affatto. Ogni corpo ha gradiente verticale, contorno scuro e ombra a terra. Il rank
cambia il disegno su tre livelli (1-2, 3-4, 5-6): corna, chele, ali e spine crescono, e
la stazza aumenta dell'11% per livello, così la progressione del torneo si vede a colpo
d'occhio senza leggere la scheda. Le unità avversarie sono ruotate di 180° per fronteggiare
il giocatore, con le fermate del gradiente invertite: altrimenti la luce arriverebbe dal
basso e sembrerebbero capovolte.

**Animazione.** I token non vengono ridisegnati a ogni azione: vivono su uno strato sopra
la griglia e si spostano con una transizione, quindi movimento, spinte e lanci sono animati
gratis. Sopra a questo:

| Evento | Effetto |
|---|---|
| Riposo | oscillazione lenta, con sfasamento casuale per unità |
| Attacco | affondo verso il bersaglio |
| Colpo subito | scossa laterale + lampo bianco |
| Mossa speciale | ingrandimento dell'attaccante |
| Aree (Sickle Dance, Wing Flap, Emperor's Rage) | onda circolare espansiva |
| Cannon Blast | proiettile che viaggia da attaccante a bersaglio |
| Healing Jig / Fill Hole | onda verde / beige |
| K.O. | dissolvenza con rotazione |
| Ring-out | volo fuori dal campo con rotazione di 560° |

Gli effetti d'area stanno su uno strato ritagliato sul bordo della scacchiera; i token no,
così un ring-out può davvero uscire dal campo. Tutte le animazioni si disattivano con
`prefers-reduced-motion`.

## Accessibilità

La scacchiera è una **griglia ARIA** (`role="grid"` con righe e celle), non un mucchio di
`div` cliccabili: si gioca interamente da tastiera e uno screen reader legge il campo.

- **Frecce** muovono il cursore, **Home/End** a inizio e fine riga, **PagSu/PagGiù** alla
  prima e ultima riga, **Invio/Spazio** seleziona o conferma
- *Roving tabindex*: si entra nella griglia con un solo Tab, poi ci si muove con le frecce,
  senza dover attraversare 36 elementi
- Ogni casella ha un nome parlato: posizione, chi la occupa, punti vita e stato
  (*Re, immobilizzato, protetto, scoperto, ha già agito, bersaglio disponibile*)
- Focus da tastiera **bianco**, selezione della pedina **gialla**: due segnali distinti
- Il diario di battaglia è una regione `aria-live="polite"`, quindi le azioni vengono
  annunciate mentre accadono
- Gli strati grafici e le icone di stato sono `aria-hidden`: lo stato passa dal nome della
  casella, non da emoji nude
- Azzerare il torneo chiede conferma, ma solo se c'è davvero qualcosa da perdere

Verificato con l'audit della skill `web-design-guidelines`. Sistemati anche:
`transition` shorthand (equivale a `transition: all`), `color-scheme: dark`,
`<meta name="theme-color">`, `touch-action: manipulation`, `-webkit-tap-highlight-color`,
`font-variant-numeric: tabular-nums` sulle colonne di statistiche, `text-wrap: balance`
sui titoli.

## Verifiche fatte

- 8 controlli automatici sulla regola del movimento: "Muovi" attivo a inizio turno,
  spostamento registrato, pulsante che diventa "Già mossa" e si disattiva, nessuna casella
  raggiungibile evidenziata dopo il primo spostamento, secondo spostamento rifiutato anche
  forzando la modalità, azione e passo ancora disponibili, movimento di nuovo possibile al
  turno successivo: tutti superati

- 14 controlli automatici sull'accessibilità: struttura della griglia, roving tabindex,
  nomi delle caselle, navigazione con frecce/Home/End, cursore che non esce dal bordo,
  selezione con Invio, contorno di focus visibile, regione live: tutti superati
- 20 controlli automatici sulla fase di schieramento (zona valida, rifiuto dei click
  fuori zona, ritorno in panchina, "schiera a caso" che non sposta il già piazzato,
  avvio bloccato finché mancano pedine): tutti superati
- 26 controlli automatici sulle regole di targeting e sugli effetti corretti dalla FAQ
  (area ortogonale, blocco della linea di tiro, rimbalzo a catena, ring-out, vulnerabilità
  da ribaltamento, cura non su se stessa, movimento dell'Itsahorse, cariche infinite
  dell'Imperatore): tutti superati

- 720 battaglie simulate headless: nessuna eccezione, nessuno stallo
- Tutte e 13 le mosse speciali eseguite (da 48 a 554 volte ciascuna) senza errori
- Partita completa giocata via UI automatizzata: nessun errore in console
- Curva di difficoltà su 1800 battaglie, con il roster limitato dallo sblocco progressivo.
  La colonna che conta è la seconda: scegliere bene la squadra è ora una decisione vera.

  | Rank | squadra a caso | squadra scelta bene |
  |---|---|---|
  | E | 82% | 89% |
  | D | 62% | 95% |
  | C | 34% | 73% |
  | B | 20% | 80% |
  | A | 7% | 77% |
  | S | 2% | 25% |
- Layout verificato a 1280px e 390px, nessuno scroll orizzontale
- Animazioni: affondo, scossa, numero di danno, proiettile, onda, movimento e ring-out
  verificati attivi nel browser; 3 partite complete giocate via UI senza errori in console
  e senza token fantasma rimasti sul campo

## Licenze e diritti

- **Testo e dati**: Rogue Galaxy Wiki (Fandom), licenza [CC BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0/).
- **Grafica**: interamente originale, generata da codice. **Nessuno sprite, artwork o
  screenshot del gioco è stato usato**, e non c'è alcun file immagine nel repo.
- *Rogue Galaxy* è © Sony Interactive Entertainment / Level-5. Questo prototipo è un
  esercizio tecnico non affiliato, non autorizzato e non commerciale.
