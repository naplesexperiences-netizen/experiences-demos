// Collega i pulsanti "acquista" al backend (api/checkout.js) e mostra i prezzi configurati.
(function () {
  const esito = document.getElementById('esito');
  const buttons = document.querySelectorAll('button[data-mode]');
  const eur = (cents) => new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR' }).format(cents / 100);

  function say(text, isError) {
    if (!esito) return;
    esito.textContent = text;
    esito.classList.toggle('error', Boolean(isError));
  }

  // Esito di ritorno da Stripe (?ordine=ok oppure ?ordine=annullato).
  const params = new URLSearchParams(window.location.search);
  if (params.get('ordine') === 'ok') say('Grazie, ordine ricevuto. Ti scriveremo via email per la spedizione.');
  if (params.get('ordine') === 'annullato') say('Pagamento annullato. Il box non è stato addebitato.');

  // Prezzi dal server: il front-end non conosce né importi né chiavi.
  fetch('api/checkout', { headers: { Accept: 'application/json' } })
    .then((r) => r.json())
    .then((data) => {
      const p = data && data.prices;
      if (!p) return;
      const once = document.querySelector('[data-price-label="once"]');
      const sub = document.querySelector('[data-price-label="subscription"]');
      if (once && p.payment) once.textContent = eur(p.payment);
      if (sub && p.subscription) sub.textContent = `${eur(p.subscription)} al mese`;
      if (once && !p.payment) once.textContent = 'Da confermare';
      if (sub && !p.subscription) sub.textContent = 'Da confermare';
    })
    .catch(() => {});

  buttons.forEach((button) => {
    button.addEventListener('click', async () => {
      const mode = button.dataset.mode;
      buttons.forEach((b) => { b.disabled = true; });
      say('Apriamo il pagamento sicuro...');
      try {
        const res = await fetch('api/checkout', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
          body: JSON.stringify({ mode }),
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok || !data.url) throw new Error(data.error || 'Pagamento non disponibile al momento. Scrivi a ordinisosrosarno@gmail.com.');
        window.location.assign(data.url);
      } catch (err) {
        say(err.message, true);
        buttons.forEach((b) => { b.disabled = false; });
      }
    });
  });

  // Il link "Abbonati ogni mese" della hero porta alla sezione e attiva il piano mensile.
  document.querySelectorAll('a[data-mode]').forEach((a) => {
    a.addEventListener('click', () => {
      const target = document.querySelector(`button[data-mode="${a.dataset.mode}"]`);
      if (target) setTimeout(() => target.focus({ preventScroll: true }), 400);
    });
  });
})();
