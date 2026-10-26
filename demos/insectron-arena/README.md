# Insectron Arena — copia giocabile

Battaglia tattica a turni 5v5 su griglia. Si gioca da qui:
**[`gioca.html`](gioca.html)**, un file solo, nessuna dipendenza, funziona
anche senza rete.

Demo prodotta da **Experiences Srl**.

---

## Il progetto sta altrove

Questa cartella tiene **solo la copia giocabile**, perché l'hub delle demo la
pubblica insieme alle altre. Tutto il resto — documentazione tecnica, piani,
prove automatiche, materiale grafico, telemetria — vive nel repository
dedicato al gioco:

**<https://github.com/naplesexperiences-netizen/insectorarena>**

| se cerchi | vai a |
|---|---|
| com'è fatto, da dove vengono i dati, che cosa è stato misurato | `DOCUMENTAZIONE.md` |
| dove siamo e cosa manca | `ROADMAP.md` |
| i piani per allevamento, mondo, multiplayer, vendite, grafica | `PIANO-*.md` |
| le 31 suite e gli 816 controlli | `prove-automatiche/` |
| il materiale per far ridisegnare gli Insector | `sprite-da-migliorare/` |
| worker e schema della telemetria (spenta) | `telemetria/` |

Il gioco ha anche un **sito proprio**, con vetrina, informativa e installazione
come app: <https://naplesexperiences-netizen.github.io/insectorarena/>

## Come si tengono allineate le due copie

`gioca.html` qui dentro è **generato** da quello del repository del gioco: non
va modificato a mano. La differenza è di due righe — qui ci sono i due
`<meta demo:*>` che servono all'indice dell'hub, e manca la registrazione del
service worker, perché l'hub non ne ha uno.

Dal repository del gioco:

```sh
python3 prove-automatiche/strumenti/specchia.py
```
