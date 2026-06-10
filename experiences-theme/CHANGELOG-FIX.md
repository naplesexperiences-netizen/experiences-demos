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
