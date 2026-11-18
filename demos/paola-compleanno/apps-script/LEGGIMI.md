# Dove arriva la scelta di Paola

Quando Paola apre una busta, la pagina:

1. **salva la scelta sul suo dispositivo** (resta aperta solo quella busta, anche
   se ricarica la pagina);
2. **invia un'email** a `ing.bartolocastellano@gmail.com` con la busta scelta.

L'indirizzo è in `index.html`, nell'oggetto `CONFIG` in cima allo script.

## Modo semplice: FormSubmit (predefinito, nessuna configurazione)

Con `endpoint: ''` l'email parte da [formsubmit.co](https://formsubmit.co).
**La prima volta** FormSubmit non consegna il messaggio: manda all'indirizzo
un'email «Confirm your email» con un pulsante di attivazione. Quindi, **prima di
mandare il link a Paola**:

1. apri la pagina con `?prova` in fondo all'indirizzo
   (es. `…/demos/paola-compleanno/?prova`) e apri una busta;
2. nella casella `ing.bartolocastellano@gmail.com` conferma l'email di FormSubmit
   (controlla anche Spam/Promozioni);
3. rifai la prova con `?prova`: ora deve arrivare «[PROVA] Paola ha scelto la busta …».

In modalità `?prova` la scelta non resta salvata, quindi puoi provare quante
volte vuoi. Per azzerare una scelta su un dispositivo: `?reset`.

## Modo completo: Foglio Google + email (Apps Script)

Se vuoi anche un registro nel Foglio Google e l'email inviata dal tuo account:

1. [sheets.new](https://sheets.new) → **Estensioni → Apps Script**.
2. Incolla `Codice.gs`, salva, scegli **prova** accanto a **Esegui** e autorizza.
   Arriva un'email di prova e nel foglio **Scelte** compare una riga.
3. **Esegui il deployment → Nuovo deployment → App web**,
   *Esegui come: Me*, *Chi può accedere: Chiunque*. Copia l'URL che finisce con `/exec`.
4. In `index.html` incollalo in `CONFIG.endpoint`.

Con `endpoint` impostato FormSubmit non viene più usato.
