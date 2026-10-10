# Collegare il modulo di conferma a un Foglio Google

Le risposte del modulo finiscono in un Foglio Google degli sposi, una riga per
invitato. Se qualcuno modifica la risposta, la sua riga viene aggiornata
(riconosciuta dall'email). Serve farlo una volta sola, con l'account Google che
deve ricevere le conferme.

## 1. Crea il Foglio

1. Apri [sheets.new](https://sheets.new) e chiama il file, ad esempio,
   «Conferme matrimonio Bartolo e Paola».
2. Non serve preparare colonne: le crea lo script alla prima risposta, in un
   foglio chiamato **Conferme**.

## 2. Aggiungi lo script

1. Nel Foglio: **Estensioni → Apps Script**.
2. Cancella il contenuto di `Codice.gs` e incolla tutto il file `Codice.gs` di
   questa cartella.
3. Salva (icona del dischetto).

## 3. Pubblica lo script come Web App

1. In alto a destra: **Esegui il deployment → Nuovo deployment**.
2. Tipo (icona dell'ingranaggio): **App web**.
3. **Esegui come**: *Me*. **Chi può accedere**: *Chiunque*.
   (Serve perché gli invitati non hanno un account sul tuo Google: possono
   solo aggiungere la propria risposta, non leggere il foglio.)
4. **Esegui il deployment** e autorizza l'accesso quando Google lo chiede
   (avviso «App non verificata»: *Avanzate → Vai a … (non sicura)*; è il tuo
   script, nel tuo account).
5. Copia l'**URL dell'app web**: finisce con `/exec`.

Prova: aprendo quell'URL nel browser deve comparire
«Conferme Bartolo e Paola: attivo.».

## 4. Collega il sito

In `index.html`, cerca:

```js
var RSVP_ENDPOINT = '';
```

e incolla l'URL tra gli apici:

```js
var RSVP_ENDPOINT = 'https://script.google.com/macros/s/…/exec';
```

Fatto: la nota «Modulo non ancora collegato» sparisce e ogni conferma arriva
nel Foglio.

## Se modifichi lo script

Dopo ogni modifica a `Codice.gs`: **Esegui il deployment → Gestisci
deployment → modifica (matita) → Versione: Nuova versione → Esegui il
deployment**. L'URL resta lo stesso.

## Cosa salva

| Colonna | Contenuto |
|---|---|
| Aggiornato il | data e ora dell'ultima risposta |
| Presenza | Sì / No |
| Nome, Email | come scritti dall'invitato |
| Persone | quante persone, invitato compreso |
| Esigenze alimentari | testo libero |
| Autobus, Posti autobus | Sì / No e quanti posti |
| Hotel, Camere, Notti | Sì / No, quante camere, quali notti |
| Messaggio | messaggio per gli sposi |
