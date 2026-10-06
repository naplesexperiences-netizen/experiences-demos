# Wireframe — Home e flusso di prenotazione

I mockup ad alta fedeltà corrispondenti sono nel file Figma
<https://www.figma.com/design/ESMMSzTDRTsMLsVJzDG0At> (pagine *Mockup · mobile 360* e *Mockup · desktop 1440*).
Tutti i dati sono segnaposto `TODO_*` (vedi `CONTENUTI_MANCANTI.md`).

## Home · mobile 360 × 640 (sopra la piega)

```
┌────────────────────────────────────┐ 0
│ Vesuvio Express        IT    Menu  │ header 56
├────────────────────────────────────┤
│ ● Gran Cono aperto oggi · agg. hh:mm│ stato del giorno (Leccio / Ginestra)
├────────────────────────────────────┤ 100
│ Dalla stazione al                  │
│ cratere in 30                      │ display 40/1.04
│ minuti.                            │
│ A TODO_DISTANZA m dall'uscita della│ body, testo secondario
│ stazione Ercolano Scavi.           │
│ ┌────────────────────────────────┐ │ ~290  widget di prenotazione
│ │ [ sab gg mmm      ▾ ] [– 2 +] │ │ data → persone (una riga)
│ │ [█Navetta + cratere█|Solo nav.]│ │ prodotto: 2 opzioni principali
│ │ ✓ Biglietto del Gran Cono      │ │ cosa è incluso, cambia con la scelta
│ │   incluso · da € TODO_PREZZO   │ │
│ │ [ Vedi orari: navetta + ingr. ]│ │ CTA Scoria, fondo a ~565 px
│ │ Combo con gli Scavi o tour da  │ │ link agli altri prodotti
│ │ Napoli                         │ │
│ └────────────────────────────────┘ │
├ ─ ─ ─ ─ ─ ─ ─ piega 640 ─ ─ ─ ─ ─ ─┤
```

In 5 secondi si leggono: **punto di partenza** (sottotitolo), **prezzo da** (riga «incluso»),
**orari** (la CTA porta direttamente alle corse del giorno).

## Home · mobile (sotto la piega)

```
│ [ FOTO 4:3 navetta davanti all'ufficio ]│
│                                    │
│ Cosa scegliere                     │ h2
│ ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓ │ prodotto principale: bordo Basalto 2px, fondo Carta
│ ┃ Navetta + ingresso Gran Cono    ┃ │
│ ┃ da € TODO_PREZZO                ┃ │ data 28, cifre tabellari
│ ┃ ✓ Navetta A/R                   ┃ │
│ ┃ ✓ Biglietto cratere nella fascia┃ │
│ ┃ ✓ Assistenza WhatsApp           ┃ │
│ ┃ [ Prenota navetta + ingresso  ] ┃ │ primario
│ ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛ │
│ ┌ Solo navetta A/R ──────────────┐ │ bordo sottile, senza fondo
│ │ ✕ Biglietto cratere a parte,    │ │ il «non incluso» è scritto
│ │   spesso esaurito               │ │
│ │ [ Prenota solo navetta ]        │ │ secondario
│ └─────────────────────────────────┘ │
│ ┌ Navetta + Scavi di Ercolano ────┐ │
│ └─────────────────────────────────┘ │
│████████████████████████████████████│ ← MOMENTO MEMORABILE (fondo Basalto)
│█ Dalla stazione al cratere        █│
│█  ●  Uscita stazione Ercolano  0′ █│ linea verticale, fermate Ginestra
│█  │  [FOTO 3:2]                   █│
│█  ●  Ufficio Vesuvio Express  TODO█│
│█  │  [FOTO 3:2]                   █│
│█  ●  Strada del Parco        ≈30′ █│
│█  ●  Quota 1000 · ingresso        █│
│█  ●  Sentiero fino al cratere TODO█│
│████████████████████████████████████│
│ Come funziona                      │ 4 passi numerati, lista semplice
│ 1 Scegli data e fascia             │
│ 2 Ricevi il voucher                │
│ 3 Presentati all'ufficio           │
│ 4 Sali e cammina                   │
│ Cosa dicono i passeggeri           │
│ ┆ TODO_RECENSIONI (widget reale)  ┆ │ nessuna recensione inventata
│ Domande rapide                     │ 4 accordion + «Tutte le domande»
│ Hai un dubbio prima di partire?    │
│ [ Scrivici su WhatsApp ]           │ Leccio
│████ footer Basalto-2 ██████████████│ «Sito ufficiale: vesuvioexpress.it,
│                                    │  nessun legame con vesuvioexpress.info»
├────────────────────────────────────┤
│ Navetta + ingresso  [  Prenota  ] │ barra sticky: appare quando il widget
│ da € TODO_PREZZO                   │ esce dallo schermo (unica ombra)
└────────────────────────────────────┘
```

## Home · desktop 1440

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ Vesuvio Express  Navetta+ingresso  Solo navetta  Come arrivare  Guida  Gruppi  FAQ  IT [Prenota] │
├──────────────────────────────────────────────────────────────────────────────┤
│ ● Gran Cono aperto oggi · aggiornato alle hh:mm                               │
├──────────────────────────────────────────────┬───────────────────────────────┤
│ Dalla stazione al                            │ Prenota la tua salita         │
│ cratere in 30 minuti.        (display 72)    │ Data     [ sab gg mmm    ▾ ]  │
│ La navetta parte a TODO m dall'uscita…       │ Persone         [ – 2 + ]     │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │ Cosa prenoti                  │
│ Partenza      Corse       Viaggio   Nav+ingr │ (●) Navetta + ingresso        │
│ Ercolano S.   ogni TODO   ≈30 min   da € TODO│ ( ) Solo navetta A/R          │
│ ┌──────────────────────────────────────────┐ │ ( ) Navetta + Scavi           │
│ │ FOTO 16:9 navetta sulla strada del Parco │ │ ( ) Tour da Napoli            │
│ └──────────────────────────────────────────┘ │ [ Vedi orari: navetta+ingr. ] │
├──────────────────────────────────────────────┴───────────────────────────────┤
│ Cosa è incluso, prodotto per prodotto       (tabella, colonna principale su Carta) │
│                    │ Navetta + ingresso │ Solo navetta        │ Navetta + Scavi │
│ Prezzo             │ da € TODO          │ da € TODO           │ da € TODO       │
│ Navetta A/R        │ ✓                  │ ✓                   │ ✓               │
│ Biglietto Gran Cono│ ✓ nella fascia     │ ✕ da comprare a parte│ TODO_VERIFICA  │
│ Ingresso Scavi     │ ✕                  │ ✕                   │ ✓               │
│                    │ [Prenota nav+ingr] │ [Prenota solo nav.] │ [Prenota +Scavi]│
├──────────────────────────────────────────────────────────────────────────────┤
│██ Dalla stazione al cratere ██████████████████████████████████████████████████│
│██ ●━━━━━━━━━━━━●━━━━━━━━━━━━●━━━━━━━━━━━━●━━━━━━━━━━━━●                      ██│
│██ 0 min        TODO         ≈30 min      —            TODO                   ██│
│██ Uscita staz. Ufficio      Strada Parco Quota 1000   Cratere                ██│
│██ [FOTO 4:3]   [FOTO 4:3]   [FOTO 4:3]   [FOTO 4:3]   [FOTO 4:3]             ██│
├──────────────────────────────────────────────────────────────────────────────┤
│ Come funziona   1 ……   2 ……   3 ……   4 ……                                     │
│ Domande rapide (accordion)          │ ┆ Recensioni (widget reale) ┆          │
│                                      │ Hai un dubbio? [Scrivici su WhatsApp]   │
│█ footer ██████████████████████████████████████████████████████████████████████│
└──────────────────────────────────────────────────────────────────────────────┘
```

## Flusso di prenotazione (3 schermate + conferma)

Route: `/[locale]/prenota` con stato nell'URL (`?data=&persone=&prodotto=&slot=`) così
«indietro» del browser e link condivisi funzionano. Su desktop il riepilogo è una colonna
fissa a destra; su mobile è la barra sticky in basso, che si espande in un pannello.

```
 1 · DATA E FASCIA            2 · PARTECIPANTI ED EXTRA      3 · PAGAMENTO
┌──────────────────────────┐ ┌──────────────────────────┐ ┌──────────────────────────┐
│ Chiudi        Passo 1 di 3│ │ Indietro      Passo 2 di 3│ │ Indietro      Passo 3 di 3│
│ ███████─────── ───────── │ │ ███████ ███████ ──────── │ │ ███████ ███████ ████████ │
│ Quando vuoi salire?      │ │ Chi viene?               │ │ Controlla e paga         │
│ [gio][ven][█sab█][dom][lun]│ │ Nome e cognome referente │ │ ┌──────────────────────┐ │
│ ● Gran Cono aperto oggi  │ │ [                      ] │ │ │ Nav+ingresso 2×€TODO │ │
│ Scegli la fascia d'ingresso│ │ Email                   │ │ │ Cratere  sab · fascia│ │
│ al cratere. Ti abbiniamo │ │ [                      ] │ │ │ Navetta  alle hh:mm  │ │
│ la navetta giusta.       │ │ Qui arriva il voucher.   │ │ │ Bagagli  1×€TODO     │ │
│ ┌──────────────────────┐ │ │ Telefono (WhatsApp)      │ │ │ ──────────────────── │ │
│ │ Cratere TODO_FASCIA   ○│ │ │ [+39                   ] │ │ │ Totale   € TODO      │ │
│ │ Navetta TODO_ORARIO │ │ │ Solo avvisi vento.       │ │ └──────────────────────┘ │
│ │ TODO posti           │ │ │ Extra                    │ │ Cancellazione gratuita … │
│ └──────────────────────┘ │ │ Deposito bagagli  [– 1 +]│ │ Se chiude per vento: …   │
│ ┏━━━━━━━━━━━━━━━━━━━━━━┓ │ │ Codice sconto            │ │ [ ] Accetto i termini    │
│ ┃ Cratere TODO_FASCIA   ●┃ │ │ [ HOTEL10     ] [Applica]│ │ ┌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌┐ │
│ ┃ Navetta TODO_ORARIO ┃ │ │                          │ │ ┆ Pulsanti PayPal (SDK) ┆ │
│ ┗━━━━━━━━━━━━━━━━━━━━━━┛ │ │                          │ │ └╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌┘ │
│ ┌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌┐ │ │                          │ │                          │
│ ┆ Cratere TODO_FASCIA   ┆ │ │                          │ │                          │
│ ┆ La navetta arriva    ┆ │ │                          │ │                          │
│ ┆ dopo l'inizio fascia ┆ │ │                          │ │                          │
│ └╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌┘ │ │                          │ │                          │
├──────────────────────────┤ ├──────────────────────────┤ │                          │
│ 2 persone · sab gg mmm   │ │ 2 persone · 1 bagaglio   │ │                          │
│ € TODO     [ Continua ]  │ │ € TODO [Vai al pagamento]│ │                          │
│ Vedi riepilogo           │ │ Vedi riepilogo           │ │                          │
└──────────────────────────┘ └──────────────────────────┘ └──────────────────────────┘

 CONFERMA
┌──────────────────────────┐
│ Prenotazione confermata  │
│ Voucher inviato a …      │
│ ┏━━━━━━━━━━━━━━━━━━━━━━┓ │
│ ┃      [ QR CODE ]     ┃ │
│ ┃ Codice TODO_CODICE   ┃ │
│ ┃ sab · navetta hh:mm ·┃ │
│ ┃ cratere fascia · 2 p.┃ │
│ ┗━━━━━━━━━━━━━━━━━━━━━━┛ │
│ [ Aggiungi al calendario]│  .ics + link Google Calendar
│ Come arrivare all'ufficio│
│ 1 Esci dalla stazione …  │
│ 2 …                      │
│ 3 …                      │
│ [ mappa statica → Maps ] │
│ [ Scrivici su WhatsApp ] │
└──────────────────────────┘
```

### Regole del passo 1 (compatibilità navetta ↔ fascia)

- L'utente sceglie la **fascia del Gran Cono**, non la corsa: la navetta si deduce.
  Per il prodotto «solo navetta» si sceglie invece la corsa.
- Una fascia è **selezionabile** solo se esiste una corsa con
  `arrivo_quota_1000 + margine ≤ inizio_fascia` (margine: `TODO_MARGINE` min) e posti liberi su entrambe.
- Le fasce incompatibili o esaurite restano visibili, con il motivo scritto
  («La navetta arriva dopo l'inizio della fascia», «Esaurito»), `aria-disabled="true"` e
  nessuna risposta al tap: si capisce perché, non si sbaglia.
- Con Gran Cono chiuso (flag) il passo 1 mostra l'avviso Ginestra e blocca i prodotti con
  ingresso; resta prenotabile solo ciò che la politica consente (`TODO_POLICY_VENTO`).
- Il widget ha altezze riservate (skeleton delle stesse dimensioni) mentre carica le
  disponibilità: CLS = 0.
