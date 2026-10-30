// Crea una sessione di pagamento Stripe Checkout per il box di arance.
// Una tantum (mode=payment) oppure abbonamento mensile (mode=subscription).
//
// Variabili d'ambiente, da impostare sul server di hosting (mai nel repo):
//   STRIPE_SECRET_KEY         chiave segreta Stripe (sk_live_... o sk_test_...)
//   BOX_PRICE_ONCE_CENT       prezzo una tantum in centesimi di euro, es. 2500
//   BOX_PRICE_MONTHLY_CENT    prezzo mensile in centesimi di euro
//   SITE_URL                  URL pubblico del sito, es. https://www.sosrosarno.org
//
// Finché i prezzi non sono impostati, il pagamento resta disattivato (nessun importo inventato).

const STRIPE_API = 'https://api.stripe.com/v1/checkout/sessions';

function prices() {
  return {
    payment: Number(process.env.BOX_PRICE_ONCE_CENT) || 0,
    subscription: Number(process.env.BOX_PRICE_MONTHLY_CENT) || 0,
  };
}

function send(res, status, body) {
  res.statusCode = status;
  res.setHeader('Content-Type', 'application/json; charset=utf-8');
  res.setHeader('Cache-Control', 'no-store');
  res.end(JSON.stringify(body));
}

module.exports = async function handler(req, res) {
  // GET: il front-end legge i prezzi configurati, in centesimi.
  if (req.method === 'GET') {
    return send(res, 200, { currency: 'eur', prices: prices() });
  }

  if (req.method !== 'POST') {
    res.setHeader('Allow', 'GET, POST');
    return send(res, 405, { error: 'Metodo non consentito.' });
  }

  const secret = process.env.STRIPE_SECRET_KEY;
  const siteUrl = process.env.SITE_URL;
  if (!secret || !siteUrl) {
    console.error('checkout: STRIPE_SECRET_KEY o SITE_URL non impostati');
    return send(res, 503, { error: 'Pagamenti non ancora attivi. Scrivi a ordinisosrosarno@gmail.com.' });
  }

  let body = req.body;
  if (typeof body === 'string') {
    try { body = JSON.parse(body); } catch { body = {}; }
  }
  const mode = body && body.mode;
  if (mode !== 'payment' && mode !== 'subscription') {
    return send(res, 400, { error: 'Scegli se acquistare una volta sola o in abbonamento.' });
  }

  const amount = prices()[mode];
  if (!amount) {
    return send(res, 503, { error: 'Prezzo non ancora disponibile. Scrivi a ordinisosrosarno@gmail.com.' });
  }

  const form = new URLSearchParams();
  form.set('mode', mode);
  form.set('locale', 'it');
  form.set('success_url', `${siteUrl}/?ordine=ok`);
  form.set('cancel_url', `${siteUrl}/?ordine=annullato#box`);
  form.set('shipping_address_collection[allowed_countries][0]', 'IT');
  form.set('line_items[0][quantity]', '1');
  form.set('line_items[0][price_data][currency]', 'eur');
  form.set('line_items[0][price_data][unit_amount]', String(amount));
  form.set('line_items[0][price_data][product_data][name]', 'Box di arance SOS Rosarno');
  if (mode === 'subscription') {
    form.set('line_items[0][price_data][recurring][interval]', 'month');
  }

  try {
    const stripeRes = await fetch(STRIPE_API, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${secret}`,
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: form,
    });
    const session = await stripeRes.json();
    if (!stripeRes.ok || !session.url) {
      console.error('checkout: Stripe ha risposto con errore', stripeRes.status, session.error && session.error.type);
      return send(res, 502, { error: 'Non riusciamo ad aprire il pagamento. Riprova tra poco.' });
    }
    return send(res, 200, { url: session.url });
  } catch (err) {
    console.error('checkout: errore di rete verso Stripe', err.message);
    return send(res, 502, { error: 'Non riusciamo ad aprire il pagamento. Riprova tra poco.' });
  }
};
