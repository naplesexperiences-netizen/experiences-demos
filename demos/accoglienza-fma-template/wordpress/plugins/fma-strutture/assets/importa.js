/* Strumenti → Importa contenuti FMA: esegue i passi dell'importazione uno dopo l'altro. */
(function () {
  var box = document.querySelector('[data-fma-importa]');
  if (!box || !window.fmaImporta) return;
  var cfg = window.fmaImporta;
  var barra = box.querySelector('[data-barra]');
  var stato = box.querySelector('[data-stato]');
  var errore = box.querySelector('[data-errore]');
  var riprova = box.querySelector('[data-riprova]');
  var log = box.querySelector('[data-log]');

  function riga(testo, tipo) {
    var li = document.createElement('li');
    li.textContent = testo;
    if (tipo === 'avviso') li.className = 'is-avviso';
    log.appendChild(li);
  }

  function ferma(messaggio) {
    errore.textContent = messaggio;
    errore.hidden = false;
    riprova.hidden = false;
    riprova.focus();
  }

  function passo() {
    errore.hidden = true;
    riprova.hidden = true;
    var dati = new FormData();
    dati.append('action', 'fma_importa_passo');
    dati.append('nonce', cfg.nonce);
    fetch(cfg.ajax, { method: 'POST', body: dati, credentials: 'same-origin' })
      .then(function (r) {
        return r.text().then(function (t) {
          try { return JSON.parse(t); } catch (e) {
            throw new Error(r.status >= 500 || r.status === 0
              ? 'Il server si è fermato durante il passo (tempo o memoria esauriti). Riprova: riparte da dove si era interrotto.'
              : 'Risposta inattesa dal server (' + r.status + ').');
          }
        });
      })
      .then(function (r) {
        var d = r.data || {};
        (d.log || []).forEach(function (l) { riga(l.testo, l.tipo); });
        if (!r.success) { ferma(d.messaggio || 'Passo non riuscito.'); return; }
        riga('✓ ' + d.etichetta);
        barra.value = d.fatti;
        if (d.finito) {
          stato.textContent = 'Fatto: ' + d.totale + ' passi su ' + d.totale + '.';
          window.location.href = cfg.fine;
          return;
        }
        stato.textContent = 'Passo ' + (d.fatti + 1) + ' di ' + d.totale + ': ' + d.prossimo;
        passo();
      })
      .catch(function (e) { ferma(e.message || 'Connessione interrotta. Riprova.'); });
  }

  riprova.addEventListener('click', passo);
  passo();
})();
