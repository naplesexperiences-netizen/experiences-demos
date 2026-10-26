# Patch Giugno 2026 — Fix contact form + integrazione blog

## Cosa è stato cambiato

### 1. Fix form contatti (non funzionava)

**Causa del bug:** in `functions.php` la chiamata
`wp_localize_script( 'jquery', 'experiencesAjax', … )` era agganciata al
handle `jquery` che il tema non carica mai. Risultato: la variabile
JS `experiencesAjax` non veniva mai stampata in pagina, quindi
`main.js` inviava un nonce vuoto e il PHP rispondeva con `-1`
(`check_ajax_referer` fallito). L'utente vedeva sempre l'errore di
invio.

**Fix:**

- `functions.php` → `experiences_enqueue_assets()` ora fa `wp_enqueue_script('experiences-main', …, true)` e localizza `experiencesAjax` su quel handle.
- `footer.php` → rimosso il `<script src=…/main.js>` hardcoded (ora gestito da `wp_footer()`).
- `assets/js/main.js` → handler reso più difensivo: controlla che `experiencesAjax` esista prima di inviare, mostra messaggi di errore parlanti, fallback se la risposta non è JSON valido.
- `front-page.php` → aggiunto campo honeypot nascosto `name="website"` (anti-spam, invisibile agli umani).
- `functions.php` → `experiences_contact_handler()` ora valida tutti i campi obbligatori, usa un `From:` coerente col dominio (evita filtri spam), ha hook `experiences_contact_recipient` e `experiences_contact_submitted` per estensioni future, logga errori `wp_mail` su `error_log`.

### 2. Articolo blog incluso

I 6 articoli in `/blog-articles/` (cartella root del repo) sono pronti
per essere importati su WordPress. Procedura:

1. **Articoli → Aggiungi nuovo**
2. Titolo: dal `seo.json` (`post_title`)
3. Permalink: lo slug del `seo.json`
4. Aggiungi un blocco **HTML personalizzato** e incolla `article.html`
5. Categoria, tag, focus keyword, meta description: dal `seo.json`
6. Featured image: vedi suggerimenti in `blog-articles/README.md`

Il template `single.php` già nel tema mostra:
- Hero con titolo, breadcrumb, categoria, autore, data, reading time
- Featured image fluttuante
- Sidebar share + newsletter + categorie
- Author box + CTA finale + articoli correlati + nav prev/next

Lo stile `.prose-blog` in `style.css` formatta già callout
(`.callout-info`, `.callout-tip`, `.callout-warning`), tabelle,
blockquote, `<pre>` con look coerente al brand (Montserrat + Inter,
palette teal #14A3A3 / blu #0B3D61).

### 3. Anteprima statica

Vedi `demos/experiences-blog-channel-manager-2026/index.html` per
un'anteprima esattamente come apparirà l'articolo "Channel Manager per
Hotel: La Guida Completa 2026" una volta pubblicato. Aprilo nel
browser per validare grafica/layout prima di pubblicare in WordPress.

## Come deployare

1. Backup del tema attuale su WordPress.
2. Sostituisci i file modificati (`functions.php`, `footer.php`, `front-page.php`, `assets/js/main.js`) — oppure carica l'intera cartella `experiences-theme/` come nuovo tema (versione 2.1.1).
3. Svuota cache (plugin di caching + cache CDN se presente).
4. Testa il form con email valida → controlla che arrivi a `admin_email`.
5. Importa l'articolo seguendo la procedura sopra.

## Test rapido del form

Dopo il deploy, apri DevTools → Network e invia il form:
- La richiesta `POST /wp-admin/admin-ajax.php` con `action=experiences_contact` deve restituire `200` e JSON `{"success":true,"data":{"message":"Messaggio inviato!..."}}`.
- Se invece torna `-1` o `403`, il nonce non è stato localizzato → verifica che `experiences-main` sia enqueued (View Source → cerca `var experiencesAjax`).

---

# v2.2.0 — Performance + fix blog

## Performance (LCP 9,8s → atteso ~2,5-3,5s)

1. **Tailwind precompilato**: rimosso il Play CDN (~300KB di JS render-blocking, prima causa dell'LCP); ora `assets/css/tailwind.min.css` statico (33KB) compilato dai template del tema.
2. **Logo WebP**: `assets/img/logo.webp` 19KB (era logo.png 226KB), con `width`/`height` espliciti.
3. **Font Awesome subset**: `assets/css/icons.min.css` (2,8KB) + 2 woff2 subsettati (6KB) con le sole 50 icone usate — al posto del CDN completo (~370KB).
4. **Google Fonts**: da 10 a 6 pesi (Inter 400/500/600/700, Montserrat 600/700).
5. **AOS**: self-hosted in `assets/vendor/`, CSS async, JS nel footer con `defer` (main.js dipende da AOS, ordine garantito).
6. **jquery-migrate** rimosso dal front-end (jQuery core resta per i plugin).

## Fix blog

- **Navbar e footer ora su tutte le pagine** (articoli, archivi, 404): erano hardcoded solo in front-page.php; estratti in `template-parts/site-header.php` e `template-parts/site-footer.php`, inclusi da header.php/footer.php. Gli anchor (#services, #contact…) puntano a `home_url('/#...')` fuori dalla homepage.
- **Layout articolo a tutta larghezza**: container da max-w-4xl a max-w-7xl, colonna contenuto da 7/12 a 8/12 (≈520px → ≈830px), hero e featured image allargati, font articolo 1.125rem su desktop.
- Corretto un `<div>` duplicato non chiuso nel footer.

## Note deploy

Dopo l'upload del tema: svuotare cache pagina + CDN. Se in futuro si
aggiungono classi Tailwind nuove nei template, ricompilare
`assets/css/tailwind.min.css` (vedi tailwind.config.js → content scan).

---

# v2.3.0 — GDPR Privacy/Cookie + Booking modal Cal.com

## Cookie consent (GDPR)

- **Banner custom** in `template-parts/cookie-banner.php`: 3 azioni (Accetta tutti / Solo essenziali / Personalizza) + modal con toggle per Analytics e Marketing (essenziali sempre attivi).
- **Stato del consenso** salvato in `localStorage` (`exp_consent`) e in cookie HTTP (`exp_consent_v1`, 12 mesi) per essere letto anche da PHP.
- **API JS** `window.expConsent.has('analytics')` e evento `experiences:consent-updated` per integrare GA4 / Meta Pixel.
- **API PHP** `experiences_has_consent('analytics')` per condizionare il rendering di pixel di terze parti.
- **Riapertura preferenze** da qualsiasi link con attributo `data-cookie-settings` (incluso il link "Preferenze Cookie" aggiunto al footer).
- **Disattivabile** da *Personalizza tema → Privacy & Cookie* se il cliente usa Iubenda/Complianz/CookieYes.

## Pagine legali auto-create

All'attivazione del tema vengono create (se non esistono) le pagine:
- `/privacy-policy/` (impostata anche come `wp_page_for_privacy_policy` di WordPress)
- `/cookie-policy/`
- `/termini-e-condizioni/`

Contenuto boilerplate GDPR/EU. Il cliente personalizza con: indirizzo legale, P.IVA, hosting provider, data ultima modifica.

I link `href="#"` nel footer e nel form contatti puntano ora alle pagine vere.

## Booking modal (Cal.com / Calendly)

- Nuovo `template-parts/booking-modal.php`: modal full-screen su mobile, max-4xl su desktop, con embed iframe **lazy-load** (rete solo all'apertura).
- **Config**: `Personalizza tema → Audit gratuito` oppure costante `EXP_CAL_LINK` in wp-config.php. Funziona sia con Cal.com sia con Calendly.
- **Trigger**: qualsiasi elemento con `data-booking-trigger` o `href="#booking"` apre il modal. Aggiornati i 3 CTA principali della homepage:
  - Hero: "Audit Gratuito del tuo Sito" → "Prenota Audit Gratuito"
  - Mid: "Richiedi una consulenza gratuita" → "Prenota una call di 30 min"
  - Chatbot CTA: "Richiedi una Demo"
- **Trust strip** in fondo al modal: "Nessun impegno · 30 min · Online · 100% gratuito"
- **Fallback** se il link non è configurato: pannello con WhatsApp + form contatti, e (per admin) shortcut alla pagina di configurazione.
- **ESC** per chiudere, `#booking` nell'URL apre automaticamente al load.

## Note deploy

1. Carica il tema v2.3.0
2. Vai su **Personalizza tema → Audit gratuito (Cal.com)** e incolla il tuo link Cal.com/Calendly
3. Vai su **Pagine** e personalizza i `TODO` nelle tre pagine create automaticamente (indirizzo legale, P.IVA, hosting)
4. Verifica che il banner cookie appaia in incognito e che le scelte vengano memorizzate
5. Clicca "Prenota Audit Gratuito" → si apre il modal con il calendario

Se in futuro si decide di passare a Iubenda/Complianz/CookieYes, basta
disattivare il banner custom da Personalizza tema (un click).
