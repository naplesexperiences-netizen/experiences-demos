/*
 * Eventi per Google Analytics nella pagina di una casa.
 *
 * I clic su email, telefono e WhatsApp li registra già Site Kit (evento contact_link_click,
 * parametro link_type). Qui si aggiunge quello che manca:
 *   - casa              nome della casa, aggiunto a tutti gli eventi della pagina (anche quelli di Site Kit)
 *   - richiesta_iniziata  il visitatore comincia a compilare il modulo
 *   - generate_lead       la richiesta è partita davvero (nome standard di Google per i contatti)
 *
 * Nessun dato personale: solo il nome della casa e il tipo di soggiorno.
 * Se il tag di Google non c'è (nessun consenso, o Analytics spento) non fa nulla.
 */
(function () {
  'use strict';

  function gtagPronto() { return typeof window.gtag === 'function'; }
  function invia(nome, dati) { if (gtagPronto()) window.gtag('event', nome, dati); }

  var titolo = document.querySelector('h1');
  var casa = titolo ? titolo.textContent.replace(/\s+/g, ' ').trim() : '';
  if (casa && gtagPronto()) window.gtag('set', { casa: casa });

  var modulo = document.querySelector('[data-request]');
  var conferma = document.querySelector('[data-request-done]');
  if (!modulo || !conferma) return;

  var iniziata = false;
  modulo.addEventListener('focusin', function () {
    if (iniziata) return;
    iniziata = true;
    invia('richiesta_iniziata', { casa: casa });
  });

  // La conferma compare (attributo hidden tolto) solo quando il server ha accettato la richiesta.
  var contata = false;
  function controlla() {
    if (conferma.hidden) { contata = false; iniziata = false; return; } // «Invia un'altra richiesta»
    if (contata) return;
    contata = true;
    var tipo = modulo.querySelector('[name="tipo"]:checked');
    invia('generate_lead', { casa: casa, tipo_soggiorno: tipo ? tipo.value : '' });
  }
  new MutationObserver(controlla).observe(conferma, { attributes: true, attributeFilter: ['hidden'] });

  // Invio senza JavaScript: la pagina si ricarica con ?richiesta=inviata. Si conta una volta
  // e si toglie il parametro, così ricaricare la pagina non conta di nuovo.
  if (!conferma.hidden && /[?&]richiesta=inviata/.test(location.search)) {
    controlla();
    if (window.history && history.replaceState) {
      history.replaceState(null, '', location.pathname + location.search.replace(/([?&])richiesta=inviata&?/, '$1').replace(/[?&]$/, '') + location.hash);
    }
  }
})();
