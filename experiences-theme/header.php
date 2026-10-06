<?php
/**
 * Header template — Experiences Srl
 * @package experiences-srl
 *
 * v2.2.0 — Performance:
 *  - Tailwind Play CDN (~300KB di JS render-blocking) sostituito da
 *    assets/css/tailwind.min.css precompilato (~33KB), enqueued in functions.php.
 *  - Font Awesome CDN completo sostituito da subset self-hosted
 *    (assets/css/icons.min.css + 2 woff2, ~9KB totali per le 50 icone usate).
 *  - Google Fonts ridotti da 10 a 6 pesi (solo quelli effettivamente usati).
 *  - AOS: CSS async qui sotto, JS nel footer con defer (vedi functions.php).
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts — solo i pesi usati: Inter 400/500/600/700, Montserrat 600/700 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">

    <!-- AOS CSS — async per non bloccare il rendering (animazioni decorative) -->
    <link rel="preload" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/vendor/aos.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/vendor/aos.css"></noscript>

    <!-- WP head: tailwind.min.css + icons.min.css + style.css (enqueued in functions.php) -->
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-50 font-sans text-gray-800 overflow-x-hidden'); ?>>
<?php
wp_body_open();

// Navbar + menu mobile su tutte le pagine (homepage, articoli, archivi, 404)
get_template_part( 'template-parts/site-header' );
?>
