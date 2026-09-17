# Insectron Arena — demo tattica

Prototipo giocabile di battaglia a turni 5v5 su griglia, costruito a partire dalla
documentazione del minigioco **Insectron** di *Rogue Galaxy* (PS2, Level-5 / Sony).

**File**: `index.html` (autonomo, nessuna dipendenza, nessun build step) ·
`insectors.json` (dataset grezzo estratto dal wiki)

---

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

## Cosa è ricostruzione di design (non documentato sul wiki)

Queste scelte sono nostre e si possono cambiare in un punto solo del codice:

1. **Griglia 6×6.** Il wiki non indica la dimensione del campo. Sei colonne tengono
   10 unità con densità sensata. Costante `N`.
2. **Formula di danno.** `max(str×0.3, str×2 − def) × moltiplicatore × (0.9…1.1)`.
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
6. **Un'azione per unità per turno** (movimento + attacco/speciale).

## Cosa NON c'è (esiste sul wiki ma è fuori dallo scopo di una demo)

- Cattura con trappole ed esche, luoghi di spawn, probabilità
- Riproduzione, ereditarietà delle statistiche, special breeding, alberi delle famiglie
- Sistema di alimentazione (tabella dei 22 cibi con effetti su HP/Str/Def/resistenze e costo in punti vita)
- Le 6 resistenze (Knockback, Confusion, Cut, Explosion, Throw, Poison): i valori sono
  nel dataset `insectors.json` ma non sono ancora usati in battaglia
- Le 136 unità complete: il wiki ha schede dettagliate solo per 35
- Colore, sesso, condizione, satietà, aspettativa di vita
- Modalità Vs. con password a 118 caratteri

## Grafica

Tutto disegnato per questo prototipo, generato da codice: nessun file immagine nel repo.

**Personaggi.** La funzione `art(famiglia, ruotato, rank)` compone un SVG per ciascuna
famiglia. Ogni corpo ha gradiente verticale, contorno scuro e ombra a terra. Il rank
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

## Verifiche fatte

- 720 battaglie simulate headless: nessuna eccezione, nessuno stallo
- Tutte e 13 le mosse speciali eseguite (da 48 a 554 volte ciascuna) senza errori
- Partita completa giocata via UI automatizzata: nessun errore in console
- Curva di difficoltà (squadra casuale, gioco non ottimale): E 88% · D 69% · C 43% · B 36% · A 35% · S 10%
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
