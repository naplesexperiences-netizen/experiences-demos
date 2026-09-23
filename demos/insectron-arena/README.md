# Insectron Arena — demo tattica

Prototipo giocabile di battaglia a turni 5v5 su griglia, costruito a partire dalla
documentazione del minigioco **Insectron** di *Rogue Galaxy* (PS2, Level-5 / Sony).

**File**
- `index.html` — landing page di presentazione, con il pulsante per giocare
- `gioca.html` — il gioco vero e proprio: autonomo, nessuna dipendenza, nessun build step
- `insectors.json` — dataset grezzo estratto dal wiki
- `img/` — tre schermate più la card di anteprima social (le uniche immagini del progetto: dentro al
  gioco non c'è un solo file immagine, la grafica è generata da codice)

**Online**: una volta su `main`, GitHub Pages pubblica la landing a
`https://naplesexperiences-netizen.github.io/experiences-demos/demos/insectron-arena/`
e il gioco a `.../demos/insectron-arena/gioca.html`.

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

## Il percorso prima della partita

Quattro passaggi, ognuno con le informazioni che servono a decidere:

1. **Roster.** Toccando un Insector si apre a destra la sua **scheda**: ruolo in campo,
   vita/forza/difesa, danno d'attacco, movimento, mossa speciale e relativo danno. Si
   entra in squadra solo confermando con il pulsante — un tocco non impegna a nulla.
2. **Riepilogo squadra.** Le cinque pedine affiancate con le stesse informazioni, ed è
   qui che si **nomina il Re**. Finché non lo scegli non si entra in arena.
3. **Chi gioca.** «Entra in arena» non porta piu' dritto in campo: prima si sceglie
   fra Human vs PC, Human vs Human e PC vs PC, e con un computer in campo il suo
   livello (vedi «Chi gioca: le tre modalita'»).
4. **Schieramento.** Le pedine si posizionano sulle due file di casa — tranne in
   PC vs PC, dove le mette in fila il gioco.

In Human vs Human i primi due passaggi li rifa' anche il secondo giocatore, con una
schermata di consegna in mezzo.

In battaglia, selezionando una pedina il pannello mostra **danno d'attacco, danno della
speciale e movimento residuo**. Quando c'è un bersaglio a tiro i due danni sono quelli
reali contro quel bersaglio (difesa inclusa); altrimenti sono valori indicativi contro un
avversario senza difesa, utili per confrontare le unità fra loro.

## Schieramento delle pedine

Prima di ogni battaglia si posizionano le 5 pedine, una alla volta, **entro le due file
più vicine** (10 caselle). Il pannello laterale elenca la squadra con il range di
movimento di ciascuna, così la scelta si fa con i dati sott'occhio: le unità lente
convengono avanti, il Re coperto dietro.

- clic su una casella illuminata → piazza la pedina evidenziata
- clic su una pedina già in campo → torna in panchina, pronta per un'altra casella
- **Schiera a caso** riempie solo le caselle rimaste vuote, senza spostare ciò che hai
  già posizionato
- **Inizia battaglia** si attiva solo a schieramento completo

Nel torneo gli avversari sono schierati dalla CPU nelle loro due file, con il Re
nell'ultima; in Human vs Human le schiera l'altra persona, nelle due file dalla sua
parte (che la rotazione le mostra in basso); in PC vs PC non si schiera affatto.

## Chi gioca: le tre modalita'

La scelta arriva **dopo** il riepilogo della squadra: si preme «Entra in arena» e prima
del campo compare la schermata «Chi gioca questa partita?». La squadra appena composta
scende in campo in tutti e tre i casi; qui si decide solo chi la muove.

| Modalita' | Chi muove | A cosa serve |
|---|---|---|
| **Human vs PC** | tu contro il computer | il torneo di sempre: cinque round per rank |
| **Human vs Human** | due persone sullo stesso dispositivo | una partita fra amici, a turno |
| **PC vs PC** | il computer su tutti e due i lati | guardare la demo all'opera, per provarla |

Ogni carta ha le sue icone — una sagoma umana accanto a «Human», un monitor accanto a
«PC» — disegnate in SVG dentro la pagina come il resto della grafica.

Sotto le carte compare solo quello che serve a quella modalita':

- **Human vs PC** → il livello dell'avversario (era il selettore in testata, che non c'e' piu')
- **Human vs Human** → i nomi dei due giocatori
- **PC vs PC** → il livello di tutti e due i computer, scelti separatamente

In ogni caso la descrizione del profilo scelto compare sotto il selettore, cosi' si sa
cosa si sta per affrontare prima di entrare in campo.

### Human vs Human

1. **Squadre in privato.** La squadra gia' composta e' di chi gioca in basso; dopo la
   conferma una schermata di consegna copre tutto e sceglie l'altro.
2. **Schieramento al coperto.** Ognuno schiera nelle due file dalla sua parte; finche'
   si schiera, **le pedine dell'altro non sono disegnate**. Le due zone non si
   sovrappongono, quindi nessuno puo' dedurne la posizione provando a occupare una
   casella. A schieramento chiuso il campo si scopre per entrambi.
3. **Turni alternati.** A ogni «Fine turno» compare la consegna: il campo resta coperto
   finche' chi subentra non conferma.
4. **Esito.** Vince chi abbatte il Re avversario, con **rivincita** a squadre invariate
   o ritorno al roster.

**La scacchiera ruota di mezzo giro a ogni consegna**: chi ha il turno si ritrova le
proprie pedine in basso, come se fosse seduto da quella parte del tavolo. Ruota la
**vista**, non lo stato: le coordinate delle unita' restano quelle logiche e passano da
`vX()`/`vY()` solo per essere disegnate o cliccate. Di conseguenza ruotano anche

- la mappatura delle caselle (ogni casella a schermo riscrive il suo `data-xy`),
- il verso degli sprite (guardano sempre verso il campo avversario),
- le frecce della tastiera (giu' resta giu' *sullo schermo*),
- le coordinate annunciate agli screen reader (riga 7 e' l'ultima riga in basso per chi
  sta guardando).

Non ruotano invece i colori: le pedine di chi sta in basso nella partita restano azzurre
e quelle dell'altro rosse, e la tinta delle due zone segue il proprietario. Cosi' a
scacchiera girata si riconosce comunque di chi e' cosa.

Altre scelte:

- **Stesso roster per tutti e due.** Si gioca su un dispositivo solo, quindi entrambi
  pescano dagli Insector sbloccati su quel salvataggio: nessuno parte avvantaggiato.
- **La squadra del torneo non si perde.** Mentre sceglie il secondo giocatore viene
  messa da parte e torna intatta al ritorno nel roster; il salvataggio locale continua
  a registrare solo la carriera.
- **Niente rete.** Tutto resta in una pagina statica: nessun server, nessun account,
  funziona anche offline.

### PC vs PC

Serve a provare la demo, non a giocarla: i due lati li muove l'IA con i livelli scelti,
il lato in basso con la squadra composta dall'utente e quello in alto con un quintetto
pescato dagli avversari del rank corrente. Non c'e' schieramento (le pedine partono
nelle due file di casa), il pulsante «Fine turno» sparisce e **il torneo non avanza**:
l'esito dice solo quale dei due profili ha vinto e con quante pedine in piedi.

Il livello del lato in basso e' guidato dall'utente solo qui: in tutte le altre
modalita' resta fisso su Normale, il metro con cui sono state misurate le difficolta'.

## Difficolta degli avversari

Il torneo contro il computer usa quattro profili di IA. La difficolta' non sta solo nelle statistiche: sale il livello di
gioco dell'avversario.

| # | Profilo | Come ragiona |
|---|---|---|
| 1 | Principiante | Avanza e mena. Non punta il Re, sceglie i bersagli a caso, usa le speciali di rado. |
| 2 | Normale | Punta il Re e il bersaglio piu debole, ma non valuta dove conviene spostarsi. |
| 3 | Esperto | Valuta **ogni casella raggiungibile** incrociata con ogni azione possibile: cerca il colpo letale, sfrutta il ring-out, evita di esporsi, si ritira se ferito. |
| 4 | Campione | Come l'Esperto, e sceglie **l'ordine** con cui muovere la squadra: agisce per prima l'unita che ha il colpo migliore. |

Il livello segue il rank del torneo (E→1, D e C→2, B e A→3, S→4) e si puo' forzare dal
selettore nella schermata «Chi gioca», per provare un profilo qualsiasi a qualunque rank.

**Quanto pesa davvero.** Misurato a specchio: stessa squadra e stesse statistiche sui due
lati, il lato "giocatore" sempre sul profilo Normale, alternando chi muove per primo.
Cosi' l'unica variabile e' il cervello.

| livello | vince l'avversario |
|---|---|
| Principiante | 41% |
| Normale | 50% (≈50%, la verifica di simmetria torna) |
| Esperto | 53% |
| Campione | 57% |

**Il passaggio al campo 5×7 ha ristretto questa scala.** Sul vecchio 6×6 il Campione
arrivava al 98%: su un campo stretto le squadre si toccavano subito e chi sceglie
l'ordine di azione chiudeva la partita in pochi turni. Con sette file di profondità ci
sono più turni di avvicinamento, la partita si decide meno sul colpo di apertura e il
vantaggio si assottiglia. Misurato, non ipotizzato: una sweep sui pesi del pianificatore
sul nuovo campo si ferma intorno al 60%.

Il vantaggio del Campione va letto bene: il metro di paragone è un'IA che muove le pedine
**in ordine fisso**. Una persona sceglie gia' da se' quale unita' far agire per prima,
quindi contro un umano il divario e' molto piu' stretto. Dare l'ordinamento all'IA non e'
un trucco: e' toglierle una zavorra che il giocatore non ha mai avuto.

Sulla curva del torneo — 500 partite per rank, squadra scelta bene, statistiche e IA che
salgono insieme (margine ±4.4 punti):

| Rank | IA avversaria | vittorie del giocatore |
|---|---|---|
| E | Principiante | 99% |
| D | Normale | 97% |
| C | Normale | 84% |
| B | Esperto | 69% |
| A | Esperto | 61% |
| S | Campione | 23% |

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

1. ~~Griglia 6×6~~ → **Campo 5×7** (5 colonne, 7 file). Nessuna delle due fonti scritte
   indicava la dimensione; l'informazione è arrivata dal committente sulla base del gioco
   originale. Il campo rettangolare e profondo bilancia i movimenti obliqui, che su una
   griglia quadrata coprivano troppo. Costanti `NX` e `NY`.
2. **Formula di danno.** Assente da entrambe le fonti.
   `max(str×0.3, str×2 − def) × moltiplicatore × (0.9…1.1)`.
   Il pavimento al 30% della forza serve a evitare che un DEF alto renda un'unità
   letteralmente invulnerabile agli attaccanti deboli (Orion Beetle ha DEF 32
   contro STR 14 della Faerie). Funzione `strike()`.
3. **Scalatura difficoltà.** Due assi: un moltiplicatore di statistiche crescente per
   rank (campo `mul` in `RANKS`) e il profilo di IA (`RANK_AI`). Il secondo conta più del
   primo: un avversario grosso ma ottuso spreca il vantaggio.
4. **Cooldown 3 turni** sulle mosse speciali: nel gioco originale la gestione è
   diversa, qui serve a evitare lo spam della stessa mossa.
5. **IA avversaria.** Vedi la sezione sulla difficoltà. I profili 3 e 4 assegnano un
   punteggio a ogni coppia (destinazione, azione) in `scorePlan()` e scelgono il massimo
   in `bestPlan()`. La prudenza è pesata sulla propria stazza: in valore assoluto
   un'unità robusta sprecava il proprio vantaggio restando alla larga.
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

- 22 controlli automatici sulle tre schede introdotte: la scheda del roster mostra
  caratteristiche, movimento, speciale e danni e non seleziona senza conferma; il
  riepilogo genera una card per pedina e blocca l'ingresso in arena finché manca il Re;
  la card di battaglia riporta danno d'attacco, danno speciale e movimento residuo
- 4 controlli sul campo rettangolare: 5 colonne, 7 file, e i limiti su entrambi gli assi

- 30 controlli automatici su Human vs Human: la scelta della modalità compare solo
  dopo «Entra in arena»; le consegne nominano il giocatore giusto a ogni passaggio; a
  inizio schieramento il campo è vuoto e si vedono solo le cinque pedine di chi sta
  schierando; ognuno schiera nella propria metà; a battaglia iniziata compaiono tutte e
  dieci; non si seleziona una pedina avversaria; «Fine turno» è attivo per tutti e due;
  una partita completa giocata via UI arriva a un vincitore dichiarato per nome; la
  rivincita riparte dalle stesse squadre; tornando al roster la squadra del torneo è
  intatta, nel salvataggio e a schermo, e i badge del torneo ricompaiono. Nessun
  errore in console.

- 37 controlli automatici sulla schermata «Chi gioca» e su PC vs PC: la testata non ha
  più selettori; le tre carte sono nell'ordine giusto con le etichette chieste e
  l'icona accanto a ogni parola (umana accanto a «Human», monitor accanto a «PC»);
  sotto le carte compaiono i nomi in Human vs Human, un livello in Human vs PC e due in
  PC vs PC, con la descrizione del profilo che segue la scelta; i livelli scelti
  arrivano ai due lati e sopravvivono al ricaricamento; PC vs PC entra in campo senza
  schierare, con dieci pedine e senza «Fine turno», va avanti da solo fino a un
  vincitore, non muove rank e round e si può abbandonare a metà; Human vs PC continua a
  passare dallo schieramento e mostra in campo il livello scelto.

- 18 controlli sulla rotazione della scacchiera, a 1280 e 390 px: col primo giocatore
  la mappatura è diretta, col secondo è girata di mezzo giro; la zona di schieramento
  di chi sta schierando è in basso sullo schermo; le pedine di chi ha il turno stanno
  nella metà bassa; le righe annunciate agli screen reader seguono quello che si vede;
  la freccia «giù» muove verso il basso dello schermo anche a scacchiera girata.

- 10 controlli automatici sulla landing: nessuna risorsa mancante, immagini caricate con
  dimensioni dichiarate e testo alternativo, un solo `h1` con gerarchia coerente, skip
  link funzionante, pulsante che apre davvero il gioco, nessuno scroll orizzontale a
  1280, 820 e 390 px

- 9 controlli automatici sul sistema di difficoltà: selettore etichettato, default
  automatico, forzatura del livello, scelta salvata e ripristinata al ricaricamento,
  scala che sale col rank, profilo mostrato in battaglia, turno avversario senza errori
- Misura a specchio dei quattro profili (300 partite ciascuno) con verifica di simmetria:
  due IA identiche danno ≈50%, come deve essere

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
- Layout verificato a 1280px e 390px, nessuno scroll orizzontale, schermate nuove
  comprese (scelta della modalità, consegna, squadra del secondo giocatore,
  schieramento a scacchiera girata)
- Totale dei controlli automatici sul gioco: **200** (meccaniche 30, schieramento 20,
  schede 22, accessibilità 14, difficoltà 10, movimento 13, scheda del roster 6,
  Human vs Human 30, modalità e PC vs PC 37, rotazione 18), più 10 sulla landing
- Animazioni: affondo, scossa, numero di danno, proiettile, onda, movimento e ring-out
  verificati attivi nel browser; 3 partite complete giocate via UI senza errori in console
  e senza token fantasma rimasti sul campo

## Testi del sito e attribuzione

Su richiesta del committente, **il sito non riporta più fonti, crediti o riferimenti
esterni**: il footer dice solo che è una demo prodotta da Experiences Srl.

Perché fosse una scelta legittima e non una violazione, le descrizioni delle unità sono
state **riscritte da zero**: ora parlano del ruolo in campo ("Artiglieria. Colpisce da
lontano ma va tenuta al riparo") invece di tradurre la prosa del wiki. Statistiche,
regole e diagrammi sono fatti, non materiale protetto. Così non è dovuta alcuna
attribuzione CC BY-SA e il footer può restare di una riga.

La tracciabilità delle fonti resta in questo README, che è documentazione interna e non
viene pubblicata come pagina del sito.

## Licenze e diritti

- **Dati** (statistiche, famiglie, regole, torneo): Rogue Galaxy Wiki (Fandom) e la
  In-Depth FAQ di Paul Michael «VHAYSTE». Sono fatti di gioco, non prosa riutilizzata.
- **Grafica**: interamente originale, generata da codice. **Nessuno sprite, artwork o
  screenshot del gioco è stato usato**, e non c'è alcun file immagine nel repo.
- *Rogue Galaxy* è © Sony Interactive Entertainment / Level-5. Questo prototipo è un
  esercizio tecnico non affiliato, non autorizzato e non commerciale.
