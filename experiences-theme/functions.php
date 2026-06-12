<?php
/**
 * Experiences Srl Theme Functions
 * @package experiences-srl
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── Theme Setup ────────────────────────────────────────────────
function experiences_setup() {
    load_theme_textdomain( 'experiences-srl', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption', 'script', 'style' ] );
    add_theme_support( 'responsive-embeds' );
    register_nav_menus([ 'primary' => __( 'Menu Principale', 'experiences-srl' ) ]);
}
add_action( 'after_setup_theme', 'experiences_setup' );

// ── Enqueue assets ──────────────────────────────────────────────────────
// v2.2.0: Tailwind precompilato + Font Awesome subset al posto dei CDN
// (il Play CDN di Tailwind era ~300KB di JS render-blocking, prima causa
// di LCP 9,8s). AOS è enqueued nel footer con defer; main.js dipende da
// AOS così l'ordine di esecuzione è garantito (defer preserva l'ordine).
function experiences_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');
    $theme_uri     = get_template_directory_uri();

    // Tailwind precompilato — deve precedere style.css che lo sovrascrive
    wp_enqueue_style( 'experiences-tailwind', $theme_uri . '/assets/css/tailwind.min.css', [], $theme_version );

    // Font Awesome subset (50 icone, ~9KB css+font vs ~370KB del CDN completo)
    wp_enqueue_style( 'experiences-icons', $theme_uri . '/assets/css/icons.min.css', [], $theme_version );

    wp_enqueue_style( 'experiences-style', get_stylesheet_uri(), [ 'experiences-tailwind' ], $theme_version );

    // AOS self-hosted, footer + defer (il CSS è caricato async in header.php)
    wp_enqueue_script( 'experiences-aos', $theme_uri . '/assets/vendor/aos.js', [], '2.3.1', true );

    wp_enqueue_script(
        'experiences-main',
        $theme_uri . '/assets/js/main.js',
        [ 'experiences-aos' ],
        $theme_version,
        true
    );

    wp_localize_script( 'experiences-main', 'experiencesAjax', [
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'experiences_contact' ),
    ]);
}
add_action( 'wp_enqueue_scripts', 'experiences_enqueue_assets' );

// ── Defer per gli script del tema ───────────────────────────────────────
// defer non blocca il parsing e preserva l'ordine: aos.js esegue prima di
// main.js (che chiama AOS.init). Filter compatibile con ogni versione WP.
function experiences_defer_scripts( $tag, $handle ) {
    if ( in_array( $handle, [ 'experiences-aos', 'experiences-main' ], true )
         && false === strpos( $tag, ' defer' ) ) {
        $tag = str_replace( ' src=', ' defer src=', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'experiences_defer_scripts', 10, 2 );

// ── Rimuovi jquery-migrate sul front-end ────────────────────────────────
// Il tema non usa jQuery; jquery-migrate è solo un shim di compatibilità
// per plugin legacy (~12KB inutili). jQuery core resta disponibile per i
// plugin che lo richiedono. Per rimuovere anche jQuery core, verificare
// prima che nessun plugin attivo lo utilizzi sul front-end.
function experiences_remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $jquery = $scripts->registered['jquery'];
        if ( $jquery->deps ) {
            $jquery->deps = array_diff( $jquery->deps, [ 'jquery-migrate' ] );
        }
    }
}
add_action( 'wp_default_scripts', 'experiences_remove_jquery_migrate' );

// ── Customizer: Cal.com link + Cookie banner toggle ─────────────────────
function experiences_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'experiences_lead_section', [
        'title'    => __( 'Audit gratuito (Cal.com)', 'experiences-srl' ),
        'priority' => 30,
    ]);

    $wp_customize->add_setting( 'exp_cal_link', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'exp_cal_link', [
        'label'       => __( 'Link Cal.com o Calendly per l\'audit gratuito', 'experiences-srl' ),
        'description' => __( 'Es: https://cal.com/experiences/audit-gratuito oppure https://calendly.com/.../30min. Il modal "Prenota Audit Gratuito" usa questo link.', 'experiences-srl' ),
        'section'     => 'experiences_lead_section',
        'type'        => 'url',
    ]);

    $wp_customize->add_section( 'experiences_privacy_section', [
        'title'    => __( 'Privacy & Cookie', 'experiences-srl' ),
        'priority' => 31,
    ]);

    $wp_customize->add_setting( 'exp_cookie_banner_enabled', [
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'exp_cookie_banner_enabled', [
        'label'       => __( 'Mostra cookie banner GDPR', 'experiences-srl' ),
        'description' => __( 'Disattivare solo se usi un plugin di cookie consent (Iubenda, Complianz, CookieYes…).', 'experiences-srl' ),
        'section'     => 'experiences_privacy_section',
        'type'        => 'checkbox',
    ]);
}
add_action( 'customize_register', 'experiences_customize_register' );

// ── Auto-creazione pagine legali al primo cambio tema ──────────────────
// Crea Privacy Policy (se WP non ne ha già una) + Cookie Policy + Termini.
// Contenuto boilerplate — il cliente lo personalizza. Idempotente: non
// duplica pagine esistenti.
function experiences_create_legal_pages() {
    $pages = [
        'privacy-policy' => [
            'title'   => 'Privacy Policy',
            'content' => experiences_legal_template_privacy(),
            'is_wp_privacy' => true, // imposta come pagina privacy ufficiale di WP
        ],
        'cookie-policy' => [
            'title'   => 'Cookie Policy',
            'content' => experiences_legal_template_cookie(),
        ],
        'termini-e-condizioni' => [
            'title'   => 'Termini e Condizioni',
            'content' => experiences_legal_template_terms(),
        ],
    ];

    foreach ( $pages as $slug => $config ) {
        $existing = get_page_by_path( $slug );
        if ( $existing && 'trash' !== $existing->post_status ) {
            // Se è la privacy policy ufficiale di WP, allinea l'opzione
            if ( ! empty( $config['is_wp_privacy'] ) && ! get_option( 'wp_page_for_privacy_policy' ) ) {
                update_option( 'wp_page_for_privacy_policy', $existing->ID );
            }
            continue;
        }

        $page_id = wp_insert_post([
            'post_title'   => $config['title'],
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => $config['content'],
            'post_author'  => get_current_user_id() ?: 1,
        ]);

        if ( $page_id && ! is_wp_error( $page_id ) && ! empty( $config['is_wp_privacy'] ) ) {
            update_option( 'wp_page_for_privacy_policy', $page_id );
        }
    }
}
add_action( 'after_switch_theme', 'experiences_create_legal_pages' );

function experiences_legal_template_privacy() {
    $site = esc_html( get_bloginfo( 'name' ) );
    $home = esc_url( home_url( '/' ) );
    return <<<HTML
<p><em>Ultima modifica: {$site} — TODO data.</em></p>

<h2>Titolare del trattamento</h2>
<p><strong>Experiences Srl</strong><br>Sede legale: TODO indirizzo, Napoli<br>P.IVA: TODO<br>Email: <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a><br>Sito: <a href="{$home}">{$home}</a></p>

<h2>Dati raccolti</h2>
<p>Raccogliamo solo i dati che ci fornisci volontariamente compilando il modulo contatti:</p>
<ul>
<li>Nome e cognome</li>
<li>Indirizzo email</li>
<li>Tipo di attività (hotel, agenzia, ecc.)</li>
<li>Piano di interesse</li>
<li>Contenuto del messaggio</li>
</ul>
<p>Raccogliamo inoltre, previo tuo consenso, dati di navigazione anonimi attraverso cookie di analytics (vedi Cookie Policy).</p>

<h2>Finalità del trattamento</h2>
<ul>
<li>Rispondere alle richieste di consulenza e contatto</li>
<li>Inviare comunicazioni commerciali (solo previo consenso esplicito)</li>
<li>Migliorare il sito tramite analisi aggregate del traffico</li>
<li>Adempiere agli obblighi di legge</li>
</ul>

<h2>Base giuridica</h2>
<ul>
<li><strong>Esecuzione di misure precontrattuali</strong> (art. 6.1.b GDPR) per le richieste di consulenza</li>
<li><strong>Consenso</strong> (art. 6.1.a GDPR) per cookie non essenziali e newsletter</li>
<li><strong>Obbligo legale</strong> (art. 6.1.c GDPR) per la conservazione contabile/fiscale</li>
</ul>

<h2>Conservazione</h2>
<p>I dati di contatto sono conservati per il tempo necessario a evadere la richiesta e successivamente per un massimo di <strong>24 mesi</strong>, salvo necessità contabili o contrattuali.</p>

<h2>Destinatari</h2>
<p>I dati possono essere trattati da:</p>
<ul>
<li><strong>Google LLC</strong> (Gmail) — provider email del titolare</li>
<li><strong>{TODO_HOSTING}</strong> — hosting del sito web</li>
<li><strong>Cal.com / Calendly</strong> — per la prenotazione delle call (solo dati che fornisci al momento della prenotazione)</li>
<li>Consulenti, commercialista, autorità competenti — solo quando obbligatorio per legge</li>
</ul>
<p>Non vendiamo mai i tuoi dati a terzi.</p>

<h2>Trasferimento extra-UE</h2>
<p>Alcuni fornitori (es. Google) possono trattare dati negli Stati Uniti. Il trasferimento avviene in conformità al Data Privacy Framework UE-USA o tramite Clausole Contrattuali Standard.</p>

<h2>I tuoi diritti</h2>
<p>In qualsiasi momento puoi esercitare i seguenti diritti scrivendo a <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a>:</p>
<ul>
<li>Accesso ai tuoi dati (art. 15 GDPR)</li>
<li>Rettifica (art. 16 GDPR)</li>
<li>Cancellazione / oblio (art. 17 GDPR)</li>
<li>Limitazione del trattamento (art. 18 GDPR)</li>
<li>Portabilità (art. 20 GDPR)</li>
<li>Opposizione (art. 21 GDPR)</li>
<li>Revoca del consenso in qualsiasi momento</li>
</ul>
<p>Hai inoltre diritto a presentare reclamo all'autorità di controllo: <a href="https://www.garanteprivacy.it" rel="noopener" target="_blank">Garante per la protezione dei dati personali</a>.</p>

<h2>Modifiche</h2>
<p>Eventuali modifiche a questa Privacy Policy verranno pubblicate su questa pagina. Ti invitiamo a consultarla periodicamente.</p>
HTML;
}

function experiences_legal_template_cookie() {
    return <<<HTML
<p><em>Ultima modifica: TODO data.</em></p>

<p>Questo sito utilizza cookie per garantire il corretto funzionamento, analizzare il traffico e, previo consenso, mostrare contenuti personalizzati. Puoi gestire le tue preferenze in qualsiasi momento cliccando su <strong>"Preferenze Cookie"</strong> in fondo a ogni pagina.</p>

<h2>Cos'è un cookie</h2>
<p>Un cookie è un piccolo file di testo memorizzato dal browser sul tuo dispositivo. Serve a ricordare informazioni tra una visita e l'altra (es: lingua, stato di login, scelte sul consenso).</p>

<h2>Categorie di cookie utilizzati</h2>

<h3>1. Cookie essenziali (sempre attivi)</h3>
<p>Necessari per il funzionamento del sito. Senza di essi alcune funzioni non sarebbero disponibili.</p>
<table>
<thead><tr><th>Nome</th><th>Fornitore</th><th>Scopo</th><th>Durata</th></tr></thead>
<tbody>
<tr><td>exp_consent_v1</td><td>naplesexperiences.com</td><td>Memorizza le tue preferenze di consenso ai cookie</td><td>12 mesi</td></tr>
<tr><td>wordpress_*</td><td>WordPress</td><td>Gestione sessione utente loggato (admin)</td><td>Sessione</td></tr>
</tbody>
</table>

<h3>2. Cookie di analytics (opt-in)</h3>
<p>Ci aiutano a capire come gli utenti usano il sito, in forma anonima e aggregata. Attivati solo dopo il tuo consenso esplicito.</p>
<table>
<thead><tr><th>Nome</th><th>Fornitore</th><th>Scopo</th><th>Durata</th></tr></thead>
<tbody>
<tr><td>_ga, _ga_*</td><td>Google Analytics (Google LLC)</td><td>Statistiche aggregate di traffico</td><td>13 mesi</td></tr>
<tr><td>_clck, _clsk</td><td>Microsoft Clarity (Microsoft Corp.)</td><td>Heatmap e session recording anonimi</td><td>12 mesi</td></tr>
</tbody>
</table>

<h3>3. Cookie di marketing (opt-in)</h3>
<p>Utilizzati per mostrare annunci più pertinenti su altri siti. Attivati solo dopo il tuo consenso esplicito.</p>
<table>
<thead><tr><th>Nome</th><th>Fornitore</th><th>Scopo</th><th>Durata</th></tr></thead>
<tbody>
<tr><td>_fbp</td><td>Meta Platforms Inc.</td><td>Remarketing su Facebook/Instagram</td><td>3 mesi</td></tr>
<tr><td>_gcl_au</td><td>Google Ads</td><td>Attribuzione conversioni Google Ads</td><td>3 mesi</td></tr>
</tbody>
</table>

<h2>Come modificare le preferenze</h2>
<p>Puoi modificare le tue scelte in qualsiasi momento cliccando su <a href="#" data-cookie-settings>Preferenze Cookie</a> in fondo alla pagina. Puoi anche disabilitare o cancellare i cookie direttamente dal tuo browser:</p>
<ul>
<li><a href="https://support.google.com/chrome/answer/95647" rel="noopener" target="_blank">Google Chrome</a></li>
<li><a href="https://support.mozilla.org/it/kb/Eliminare%20i%20cookie" rel="noopener" target="_blank">Mozilla Firefox</a></li>
<li><a href="https://support.apple.com/it-it/guide/safari/sfri11471/mac" rel="noopener" target="_blank">Safari</a></li>
<li><a href="https://support.microsoft.com/it-it/microsoft-edge" rel="noopener" target="_blank">Microsoft Edge</a></li>
</ul>
<p>Nota: disabilitare i cookie essenziali può compromettere il funzionamento del sito.</p>

<h2>Per maggiori informazioni</h2>
<p>Consulta la nostra <a href="/privacy-policy/">Privacy Policy</a> o scrivici a <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a>.</p>
HTML;
}

function experiences_legal_template_terms() {
    return <<<HTML
<p><em>Ultima modifica: TODO data.</em></p>

<p>I presenti Termini e Condizioni regolano l'utilizzo del sito naplesexperiences.com e dei servizi offerti da Experiences Srl.</p>

<h2>1. Informazioni sul titolare</h2>
<p><strong>Experiences Srl</strong><br>Sede legale: TODO indirizzo, Napoli<br>P.IVA: TODO</p>

<h2>2. Oggetto dei servizi</h2>
<p>Experiences Srl offre servizi di digitalizzazione per il settore turistico, tra cui sviluppo siti web, SEO/SEM marketing, gestione Channel Manager e OTA, assistenti virtuali AI.</p>

<h2>3. Utilizzo del sito</h2>
<p>L'utente si impegna a utilizzare il sito in modo lecito, rispettando le leggi vigenti e i diritti di terzi. È vietato:</p>
<ul>
<li>Tentare di accedere a sezioni riservate</li>
<li>Compiere attività che possano danneggiare il sito o gli altri utenti</li>
<li>Copiare o riprodurre contenuti senza autorizzazione</li>
</ul>

<h2>4. Proprietà intellettuale</h2>
<p>Tutti i contenuti del sito (testi, immagini, loghi, design) sono di proprietà di Experiences Srl o dei rispettivi titolari e sono protetti dalle leggi sul diritto d'autore.</p>

<h2>5. Limitazione di responsabilità</h2>
<p>Experiences Srl si impegna a fornire informazioni accurate ma non garantisce l'assenza di errori. Non è responsabile per eventuali danni derivanti dall'uso del sito o dall'impossibilità di accedervi.</p>

<h2>6. Modifiche</h2>
<p>Experiences Srl si riserva il diritto di modificare i presenti Termini in qualsiasi momento. Le modifiche entrano in vigore dalla loro pubblicazione su questa pagina.</p>

<h2>7. Legge applicabile e foro competente</h2>
<p>I presenti Termini sono regolati dalla legge italiana. Per qualsiasi controversia è competente il Foro di Napoli.</p>

<h2>8. Contatti</h2>
<p>Per qualsiasi domanda relativa ai presenti Termini: <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a></p>
HTML;
}

// ── Helper PHP: leggere il consenso lato server ─────────────────────────
// Permette ai template di condizionare il rendering di pixel/script di
// terze parti in base al consenso dell'utente.
//   if ( experiences_has_consent( 'analytics' ) ) { /* GA4 tag */ }
function experiences_has_consent( $category ) {
    if ( empty( $_COOKIE['exp_consent_v1'] ) ) {
        return false;
    }
    $raw = wp_unslash( $_COOKIE['exp_consent_v1'] );
    $data = json_decode( $raw, true );
    if ( ! is_array( $data ) ) {
        return false;
    }
    return ! empty( $data[ $category ] );
}

// ── SEO: meta tags ─────────────────────────────────────────────
function experiences_meta_tags() {
    if ( ! is_front_page() ) return; ?>
    <meta name="description" content="Experiences Srl digitalizza agenzie di viaggi e strutture alberghiere italiane. Sviluppo siti web, SEO/SEM, gestione Channel Manager, annunci OTA e assistenti AI. Richiedi una consulenza gratuita.">
    <meta name="keywords" content="soluzioni digitali turismo, sito web agenzia viaggi, channel manager hotel, gestione OTA, SEO turismo italia, prenotazioni online hotel, assistente virtuale AI turismo, experiences srl napoli">
    <meta name="author" content="Experiences Srl">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="theme-color" content="#0B3D61">
    <link rel="canonical" href="<?php echo esc_url( home_url('/') ); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Experiences Srl">
    <meta property="og:title" content="Experiences Srl | Soluzioni Digitali per il Turismo">
    <meta property="og:description" content="Digitalizziamo agenzie di viaggi e strutture alberghiere. Siti web, SEO/SEM, Channel Manager, OTA e AI. Scopri i nostri piani a partire da €0/anno.">
    <meta property="og:url" content="<?php echo esc_url( home_url('/') ); ?>">
    <meta property="og:image" content="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="it_IT">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Experiences Srl | Soluzioni Digitali per il Turismo">
    <meta name="twitter:description" content="Digitalizziamo agenzie di viaggi e hotel italiani. Siti web, SEO, Channel Manager, OTA e AI 24/7.">
    <meta name="twitter:image" content="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/og-image.jpg">
    <meta name="geo.region" content="IT-72">
    <meta name="geo.placename" content="Napoli, Campania, Italia">
    <meta name="geo.position" content="40.8518;14.2681">
    <meta name="ICBM" content="40.8518, 14.2681">
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"ProfessionalService","name":"Experiences Srl","url":"<?php echo esc_url(home_url('/')); ?>","description":"Soluzioni digitali complete per agenzie di viaggi e strutture alberghiere italiane.","address":{"@type":"PostalAddress","addressCountry":"IT","addressRegion":"Campania","addressLocality":"Napoli"},"areaServed":{"@type":"Country","name":"Italy"},"serviceType":["Sviluppo Sito Web","SEO/SEM Marketing","Gestione Channel Manager","Creazione Annunci OTA","Assistenti Virtuali AI"],"priceRange":"€€"}
    </script>
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"Quanto costa un sito web per un'agenzia di viaggi?","acceptedAnswer":{"@type":"Answer","text":"Experiences Srl offre piani a partire da €0/anno (Enterprise) fino a €1.400/anno (Base), con commissioni sulle vendite generate."}},{"@type":"Question","name":"Cos'è un Channel Manager per hotel?","acceptedAnswer":{"@type":"Answer","text":"Strumento che sincronizza in tempo reale disponibilità e prezzi su tutte le OTA (Booking.com, Expedia, Agoda), eliminando l'overbooking."}}]}
    </script>
    <?php
}
add_action( 'wp_head', 'experiences_meta_tags', 1 );

// ── Remove WP clutter from <head> ─────────────────────────────
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// ── Portfolio Custom Post Type ─────────────────────────────────
function experiences_register_portfolio_cpt() {
    register_post_type( 'portfolio_site', [
        'labels' => [
            'name'          => __( 'Portfolio Siti', 'experiences-srl' ),
            'singular_name' => __( 'Sito Portfolio', 'experiences-srl' ),
            'add_new_item'  => __( 'Aggiungi Nuovo Sito', 'experiences-srl' ),
            'edit_item'     => __( 'Modifica Sito', 'experiences-srl' ),
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'     => 'dashicons-portfolio',
        'menu_position' => 5,
        'rewrite'       => [ 'slug' => 'portfolio' ],
    ]);
}
add_action( 'init', 'experiences_register_portfolio_cpt' );

// ── Portfolio shortcode [experiences_portfolio] ────────────────
function experiences_portfolio_shortcode( $atts ) {
    $atts  = shortcode_atts([ 'posts' => -1 ], $atts);
    $query = new WP_Query([
        'post_type'      => 'portfolio_site',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ]);
    // Static items always shown; WP_Query items are additional
    // Deduplicate by title before output
    $seen_titles = [];
    if ( $query->have_posts() ) {
        $unique_posts = [];
        while ( $query->have_posts() ) {
            $query->the_post();
            $t = get_the_title();
            if ( ! in_array( $t, $seen_titles ) ) {
                $seen_titles[]   = $t;
                $unique_posts[]  = get_post();
            }
        }
        wp_reset_postdata();
        // Re-run with unique posts only
        $query = new WP_Query([
            'post_type'  => 'portfolio_site',
            'post__in'   => wp_list_pluck( $unique_posts, 'ID' ),
            'orderby'    => 'menu_order',
            'order'      => 'ASC',
            'posts_per_page' => -1,
        ]);
    }

    ob_start(); ?>
    <style>
    /* ── Portfolio Carousel ──────────────────────────────────── */
    .exp-carousel-wrap {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding-bottom: 0.5rem;
    }
    .exp-carousel-track {
        display: flex;
        gap: 24px;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
        align-items: stretch;
    }
    .exp-carousel-slide {
        flex: 0 0 calc((100% - 48px) / 3);
        min-width: calc((100% - 48px) / 3);
        border-radius: 1rem;
        overflow: hidden;
        background: #0B3D61;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        text-decoration: none;
        display: block;
        position: relative;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    @media (max-width: 1023px) {
        .exp-carousel-slide {
            flex: 0 0 calc((100% - 24px) / 2);
            min-width: calc((100% - 24px) / 2);
        }
    }
    @media (max-width: 639px) {
        .exp-carousel-slide {
            flex: 0 0 100%;
            min-width: 100%;
        }
    }
    .exp-carousel-slide:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    /* Image */
    .exp-c-img-wrap {
        width: 100%;
        padding-top: 62.5%;
        position: relative;
        overflow: hidden;
        background: #0B3D61;
    }
    .exp-c-img-wrap img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        object-position: top center;
        display: block;
        transition: transform 0.6s cubic-bezier(0.4,0,0.2,1);
    }
    .exp-carousel-slide:hover .exp-c-img-wrap img { transform: scale(1.07); }
    /* Overlay */
    .exp-c-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(10,22,40,0.95) 0%, transparent 55%);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
        pointer-events: none;
    }
    .exp-carousel-slide:hover .exp-c-overlay { opacity: 1; }
    /* Content */
    .exp-c-content {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        z-index: 2;
        padding: 1.25rem 1.5rem;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.4s ease;
    }
    .exp-carousel-slide:hover .exp-c-content { transform: translateY(0); opacity: 1; }
    .exp-c-content h3 {
        font-size: 1.1rem; font-weight: 700;
        color: #fff; margin: 0 0 0.2rem; font-family: Montserrat, sans-serif;
        line-height: 1.3;
    }
    .exp-c-content p  { font-size: 0.8rem; color: rgba(255,255,255,0.7); margin: 0 0 0.5rem; }
    .exp-c-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.8rem; font-weight: 600; color: #14A3A3;
    }
    /* Nav arrows */
    .exp-carousel-nav {
        display: flex; justify-content: center;
        align-items: center; gap: 1rem; margin-top: 1.5rem;
    }
    .exp-carousel-btn {
        width: 44px; height: 44px; border-radius: 50%;
        background: #fff; border: 2px solid #E8F4F4;
        color: #0B3D61; font-size: 1rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.25s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    .exp-carousel-btn:hover { background: #0D7C7C; color: #fff; border-color: #0D7C7C; }
    .exp-carousel-btn:disabled { opacity: 0.3; cursor: default; }
    /* Dots */
    .exp-carousel-dots { display: flex; gap: 0.5rem; }
    .exp-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #CBD5E1; transition: all 0.3s ease; cursor: pointer; border: none;
    }
    .exp-dot.active { background: #0D7C7C; transform: scale(1.3); }
    /* Placeholder */
    .exp-c-placeholder {
        position:absolute; top:0; left:0; width:100%; height:100%;
        display:flex; align-items:center; justify-content:center;
    }
    /* Auto-play progress bar */
    .exp-progress-bar {
        height: 3px; background: #E8F4F4; border-radius: 2px;
        margin-top: 1rem; overflow: hidden;
    }
    .exp-progress-fill {
        height: 100%; background: linear-gradient(90deg, #0D7C7C, #14A3A3);
        border-radius: 2px; width: 0%;
        transition: width linear;
    }
    </style>

    <div class="exp-carousel-wrap" id="exp-portfolio-carousel">
        <div class="exp-carousel-track" id="exp-track">
        <?php
        $slide_idx = 0;
        while ( $query->have_posts() ) : $query->the_post();
            $url      = get_post_meta( get_the_ID(), 'site_url', true );
            $category = get_post_meta( get_the_ID(), 'site_category', true );
            $thumb    = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            if ( ! $thumb ) $thumb = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            if ( ! $thumb ) $thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
            if ( ! $thumb ) $thumb = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
            $slide_idx++;
        ?>
            <a href="<?php echo esc_url($url ?: '#'); ?>" target="_blank" rel="noopener"
               class="exp-carousel-slide"
               data-aos="fade-up" data-aos-delay="<?php echo min($slide_idx * 80, 400); ?>">
                <div class="exp-c-img-wrap">
                    <?php if ($thumb): ?>
                    <img src="<?php echo esc_url($thumb); ?>"
                         alt="<?php echo esc_attr(get_the_title()); ?>"
                         loading="lazy">
                    <?php else: ?>
                    <div class="exp-c-placeholder">
                        <i class="fas fa-globe" style="font-size:2.5rem;color:rgba(20,163,163,0.5);"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="exp-c-overlay"></div>
                <div class="exp-c-content">
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo esc_html($category); ?></p>
                    <span class="exp-c-link">
                        Visita il sito <i class="fas fa-external-link-alt"></i>
                    </span>
                </div>
            </a>
        <?php endwhile; wp_reset_postdata(); ?>
        </div><!-- /.exp-carousel-track -->

        <!-- Progress bar -->
        <div class="exp-progress-bar"><div class="exp-progress-fill" id="exp-progress"></div></div>

        <!-- Navigation -->
        <div class="exp-carousel-nav">
            <button class="exp-carousel-btn" id="exp-prev" aria-label="Precedente">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="exp-carousel-dots" id="exp-dots"></div>
            <button class="exp-carousel-btn" id="exp-next" aria-label="Successivo">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <script>
    (function() {
        const AUTOPLAY_INTERVAL = 4000;
        const track   = document.getElementById('exp-track');
        const prevBtn = document.getElementById('exp-prev');
        const nextBtn = document.getElementById('exp-next');
        const dotsEl  = document.getElementById('exp-dots');
        const progress= document.getElementById('exp-progress');

        if (!track) return;

        const slides = Array.from(track.querySelectorAll('.exp-carousel-slide'));
        const total  = slides.length;
        let current  = 0;
        let autoTimer, progressTimer;

        // Responsive: slides per view
        function slidesPerView() {
            if (window.innerWidth >= 1024) return 3;
            if (window.innerWidth >= 640)  return 2;
            return 1;
        }

        // Build dots
        function buildDots() {
            dotsEl.innerHTML = '';
            const pages = Math.ceil(total / slidesPerView());
            for (let i = 0; i < pages; i++) {
                const d = document.createElement('button');
                d.className = 'exp-dot' + (i === 0 ? ' active' : '');
                d.setAttribute('aria-label', 'Pagina ' + (i+1));
                d.onclick = () => goTo(i * slidesPerView());
                dotsEl.appendChild(d);
            }
        }

        function updateDots() {
            const page = Math.floor(current / slidesPerView());
            dotsEl.querySelectorAll('.exp-dot').forEach((d, i) => {
                d.classList.toggle('active', i === page);
            });
        }

        function getSlideWidth() {
            if (!slides[0]) return 0;
            const GAP    = 24;
            const spv    = slidesPerView();
            const trackW = track.parentElement.offsetWidth;
            // Each slide = (trackW - gaps) / spv, plus one gap to advance
            return (trackW - GAP * (spv - 1)) / spv + GAP;
        }

        function goTo(idx) {
            const maxIdx = Math.max(0, total - slidesPerView());
            current = Math.max(0, Math.min(idx, maxIdx));
            track.style.transform = 'translateX(-' + (current * getSlideWidth()) + 'px)';
            prevBtn.disabled = current === 0;
            nextBtn.disabled = current >= maxIdx;
            updateDots();
            resetProgress();
        }

        function next() {
            const maxIdx = Math.max(0, total - slidesPerView());
            goTo(current >= maxIdx ? 0 : current + 1);
        }

        function resetProgress() {
            clearTimeout(progressTimer);
            progress.style.transition = 'none';
            progress.style.width = '0%';
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    progress.style.transition = 'width ' + AUTOPLAY_INTERVAL + 'ms linear';
                    progress.style.width = '100%';
                });
            });
        }

        function startAutoplay() {
            clearInterval(autoTimer);
            autoTimer = setInterval(next, AUTOPLAY_INTERVAL);
            resetProgress();
        }

        prevBtn.onclick = () => { goTo(current - 1); startAutoplay(); };
        nextBtn.onclick = () => { goTo(current + 1); startAutoplay(); };

        // Pause on hover
        track.addEventListener('mouseenter', () => {
            clearInterval(autoTimer);
            clearTimeout(progressTimer);
            progress.style.transition = 'none';
        });
        track.addEventListener('mouseleave', startAutoplay);

        // Touch swipe
        let touchStartX = 0;
        track.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, {passive:true});
        track.addEventListener('touchend', e => {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) { diff > 0 ? next() : goTo(current - 1); startAutoplay(); }
        }, {passive:true});

        // Resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => { buildDots(); goTo(0); }, 200);
        });

        // Init
        buildDots();
        goTo(0);
        startAutoplay();
    })();
    </script>

    <?php return ob_get_clean();
}
add_shortcode( 'experiences_portfolio', 'experiences_portfolio_shortcode' );

// ── Contact form AJAX handler ──────────────────────────────────
function experiences_contact_handler() {
    if ( ! check_ajax_referer( 'experiences_contact', 'nonce', false ) ) {
        wp_send_json_error([ 'message' => 'Sessione scaduta. Ricarica la pagina e riprova.' ], 403);
    }

    $nome      = sanitize_text_field( wp_unslash( $_POST['nome'] ?? '' ) );
    $cognome   = sanitize_text_field( wp_unslash( $_POST['cognome'] ?? '' ) );
    $email     = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $attivita  = sanitize_text_field( wp_unslash( $_POST['tipo_attivita'] ?? '' ) );
    $piano     = sanitize_text_field( wp_unslash( $_POST['piano'] ?? '' ) );
    $messaggio = sanitize_textarea_field( wp_unslash( $_POST['messaggio'] ?? '' ) );

    if ( ! $nome || ! $cognome || ! is_email( $email ) || ! $messaggio ) {
        wp_send_json_error([ 'message' => 'Compila tutti i campi obbligatori (nome, cognome, email, messaggio).' ]);
    }

    // Honeypot anti-spam: se è valorizzato un campo "website" è probabile sia un bot.
    if ( ! empty( $_POST['website'] ) ) {
        wp_send_json_success([ 'message' => 'Messaggio inviato! Ti contatteremo presto.' ]);
    }

    $to      = apply_filters( 'experiences_contact_recipient', get_option( 'admin_email' ) );
    $subject = sprintf( '[Sito] Richiesta Consulenza – %s %s', $nome, $cognome );
    $body    = "Nuova richiesta dal sito naplesexperiences.com\n"
             . "----------------------------------------------\n"
             . "Nome:      {$nome} {$cognome}\n"
             . "Email:     {$email}\n"
             . "Attività:  " . ( $attivita ?: '—' ) . "\n"
             . "Piano:     " . ( $piano    ?: '—' ) . "\n"
             . "----------------------------------------------\n\n"
             . "Messaggio:\n{$messaggio}\n";

    $from_name   = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
    $from_domain = wp_parse_url( home_url(), PHP_URL_HOST ) ?: 'naplesexperiences.com';
    $from_email  = 'no-reply@' . preg_replace( '/^www\./', '', $from_domain );

    $headers = [
        "From: {$from_name} <{$from_email}>",
        "Reply-To: {$nome} {$cognome} <{$email}>",
        'Content-Type: text/plain; charset=UTF-8',
    ];

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        do_action( 'experiences_contact_submitted', compact( 'nome', 'cognome', 'email', 'attivita', 'piano', 'messaggio' ) );
        wp_send_json_success([ 'message' => 'Messaggio inviato! Ti contatteremo presto.' ]);
    }

    error_log( '[experiences_contact] wp_mail() failed for ' . $email );
    wp_send_json_error([ 'message' => "Errore nell'invio. Riprova o contattaci su WhatsApp." ]);
}
add_action( 'wp_ajax_experiences_contact',        'experiences_contact_handler' );
add_action( 'wp_ajax_nopriv_experiences_contact', 'experiences_contact_handler' );

// ── Image sizes ───────────────────────────────────────────────
add_image_size( 'portfolio-thumb', 800, 500, true );
if ( ! isset( $content_width ) ) $content_width = 1280;


// ── Blog Helpers ──────────────────────────────────────────────

/**
 * Calcola il tempo di lettura stimato di un articolo (parole / 200 wpm)
 * @param int|null $post_id
 * @return int Minuti di lettura (min 1)
 */
function exp_reading_time( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $minutes = ceil( $word_count / 200 );
    return max( 1, $minutes );
}

/**
 * Conta visualizzazioni post (incremento)
 */
function exp_set_post_views( $post_id ) {
    $count_key = 'exp_post_views_count';
    $count = (int) get_post_meta( $post_id, $count_key, true );
    update_post_meta( $post_id, $count_key, $count + 1 );
}

/**
 * Recupera visualizzazioni post (formato leggibile)
 */
function exp_get_post_views( $post_id ) {
    $count = (int) get_post_meta( $post_id, 'exp_post_views_count', true );
    if ( $count >= 1000 ) {
        return number_format( $count / 1000, 1 ) . 'k';
    }
    return $count > 0 ? $count : '0';
}

/**
 * Track visualizzazioni quando si visualizza un singolo articolo
 */
add_action( 'wp_head', function() {
    if ( is_single() && ! is_admin() && get_post_type() === 'post' ) {
        exp_set_post_views( get_the_ID() );
    }
});

/**
 * Excerpt più lungo per cards blog (40 parole)
 */
add_filter( 'excerpt_length', function( $length ) {
    return 40;
}, 999 );

add_filter( 'excerpt_more', function( $more ) {
    return '…';
}, 999 );

/**
 * Image size custom per blog (16:9)
 */
add_action( 'after_setup_theme', function() {
    add_image_size( 'blog-thumb', 1200, 675, true );
});
