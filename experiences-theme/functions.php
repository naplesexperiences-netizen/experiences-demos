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
