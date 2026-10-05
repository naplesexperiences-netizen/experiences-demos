# I 34 sprite, da far migliorare

*5 ottobre 2026.* Materiale pronto da dare a un generatore di immagini
(Nano Banana o altro) per rifare gli sprite tenendo la griglia, così il
risultato si ritaglia e si rimette dentro al gioco.

| file | a cosa serve |
|---|---|
| `insetti-per-il-modello.png` | **Questo si dà al modello.** 1800×1800, griglia 6×6, celle da 300 px, nessun testo: il testo dentro a un'immagine data a un generatore torna storpiato o copiato male. Il bordo colorato di ogni cella è il colore della famiglia, e dice al modello che cosa deve restare coerente. |
| `insetti-legenda.png` | **Questa è per noi.** Stessa identica griglia, con nome, famiglia, rank e colore: serve a ritagliare il ritorno e a sapere chi è chi. |
| `prova-una-famiglia.png` | I quattro Falcidi soli. Per provare la strada su quattro invece che su 34 prima di spendere. |
| `prompt.txt` | Il testo da incollare insieme all'immagine. |

## La cosa che conta, misurata

Gli sprite sono disegnati da `art(fam, flip, rank)`, che ha **tre taglie per
sei rank**: `rank >= 5 ? 3 : rank >= 3 ? 2 : 1`. Conseguenza: R1 e R2 danno la
stessa figura, R3 e R4 pure, R5 e R6 pure.

Misurato alla dimensione in cui gli sprite compaiono davvero (46 px, la scheda
del riepilogo), **nove coppie sono identiche allo 0%**:

| famiglia | i due Insector | celle |
|---|---|---|
| Lamidi | R3 Rasoio = R4 Bipenne | r1c2 / r1c3 |
| Bastidi | R3 Barbacane = R4 Rivellino | r1c6 / r2c1 |
| Bombardidi | R3 Mortaio = R4 Colubrina | r2c3 / r2c4 |
| Bombardidi | R5 Bombarda = R6 Basilisco | r2c5 / r2c6 |
| Voltidi | R3 Girandola = R4 Capovolta | r3c2 / r3c3 |
| Tenaglidi | R1 Tenaglia = R2 Morsa | r3c5 / r3c6 |
| Falcidi | R1 Falcetta = R2 Falcione | r4c2 / r4c3 |
| Falcidi | R3 Roncola = R4 Turbine | r4c4 / r4c5 |
| Silfidi | R1 Lusinga = R2 Malia | r6c1 / r6c2 |

Dentro a una famiglia la coppia più diversa arriva al 4,5% di differenza. Fra
famiglie diverse, le due più simili (Bastidi e Tenaglidi) stanno al 7%.
**Separare quelle nove coppie vale più di qualunque rifinitura estetica**: sono
nove casi in cui due pedine diverse, in campo, sono la stessa figura.

## Dove va ogni cella

Lettura da sinistra a destra, dall'alto in basso. Le famiglie sono blocchi
contigui, e dentro ogni famiglia i rank salgono.

| cella | Insector | famiglia | rank | colore |
|---|---|---|---:|---|
| r1c1 | Sfregio | Lamidi | 1 | `#7dd3fc` |
| r1c2 | Rasoio | Lamidi | 3 | `#7dd3fc` |
| r1c3 | Bipenne | Lamidi | 4 | `#7dd3fc` |
| r1c4 | Trinciaferro | Lamidi | 5 | `#7dd3fc` |
| r1c5 | Bastione | Bastidi | 1 | `#fbbf24` |
| r1c6 | Barbacane | Bastidi | 3 | `#fbbf24` |
| r2c1 | Rivellino | Bastidi | 4 | `#fbbf24` |
| r2c2 | Mastio | Bastidi | 5 | `#fbbf24` |
| r2c3 | Mortaio | Bombardidi | 3 | `#a78bfa` |
| r2c4 | Colubrina | Bombardidi | 4 | `#a78bfa` |
| r2c5 | Bombarda | Bombardidi | 5 | `#a78bfa` |
| r2c6 | Basilisco | Bombardidi | 6 | `#a78bfa` |
| r3c1 | Ribalta | Voltidi | 1 | `#34d399` |
| r3c2 | Girandola | Voltidi | 3 | `#34d399` |
| r3c3 | Capovolta | Voltidi | 4 | `#34d399` |
| r3c4 | Rovescio | Voltidi | 5 | `#34d399` |
| r3c5 | Tenaglia | Tenaglidi | 1 | `#fb923c` |
| r3c6 | Morsa | Tenaglidi | 2 | `#fb923c` |
| r4c1 | Ganascia | Tenaglidi | 3 | `#fb923c` |
| r4c2 | Falcetta | Falcidi | 1 | `#4ade80` |
| r4c3 | Falcione | Falcidi | 2 | `#4ade80` |
| r4c4 | Roncola | Falcidi | 3 | `#4ade80` |
| r4c5 | Turbine | Falcidi | 4 | `#4ade80` |
| r4c6 | Trivella | Trivellidi | 4 | `#f472b6` |
| r5c1 | Punteruolo | Trivellidi | 6 | `#f472b6` |
| r5c2 | Bufera | Ventalidi | 4 | `#22d3ee` |
| r5c3 | Balsamina | Coccinidi | 3 | `#f87171` |
| r5c4 | Ruzzola | Sferidi | 2 | `#c4b5a0` |
| r5c5 | Macigno | Sferidi | 3 | `#c4b5a0` |
| r5c6 | Galoppo | Corsidi | 1 | `#fcd34d` |
| r6c1 | Lusinga | Silfidi | 1 | `#e879f9` |
| r6c2 | Malia | Silfidi | 2 | `#e879f9` |
| r6c3 | Incanto | Silfidi | 3 | `#e879f9` |
| r6c4 | Folgore | Tonantidi | 1 | `#818cf8` |
| r6c5, r6c6 | *(vuote)* | | | |

## Prima di metterli nel gioco

Vale quello che è venuto fuori dalla prova su Canva del 3 ottobre
(`prove/grafica-2026-10-03/canva-tre-famiglie/`): quello che torna da un
generatore è **un'immagine**, mentre il gioco oggi non ha un solo file
immagine — una funzione produce tutti e 34 gli sprite in 2,7 KB, col colore
della famiglia come parametro, e per costruzione non può sbagliare lo sfondo.

Quindi, se il ritorno piace, la domanda da farsi non è «li incolliamo?» ma
«questi 34 PNG valgono il peso, lo scontorno e la perdita del parametro
colore?». La risposta può benissimo essere sì — ma è una decisione, non un
passaggio automatico. Una terza strada: usare il ritorno come **riferimento**
per rifare `art()` a mano, tenendo il sistema e guadagnando le silhouette.
