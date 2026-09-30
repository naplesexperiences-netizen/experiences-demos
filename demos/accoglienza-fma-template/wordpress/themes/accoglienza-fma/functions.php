<?php
/**
 * Tema Accoglienza FMA.
 */

defined( 'ABSPATH' ) || exit;

define( 'FMA_TEMA_VERSIONE', '1.0.0' );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/customizer.php';

add_action( 'after_setup_theme', 'fma_tema_setup' );

function fma_tema_setup(): void {
	load_theme_textdomain( 'accoglienza-fma', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array( 'width' => 500, 'height' => 121, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/css/fonts.css', 'assets/css/tokens.css', 'assets/css/editor.css' ) );

	register_nav_menus(
		array(
			'primario' => 'Menu principale',
			'piede'    => 'Menu a piè di pagina',
		)
	);

	add_image_size( 'fma-hero', 1920, 1200, true );
	add_image_size( 'fma-card', 720, 480, true );
	add_image_size( 'fma-quadrato', 320, 320, true );
}

add_action( 'wp_enqueue_scripts', 'fma_tema_asset' );

function fma_tema_asset(): void {
	$uri = get_template_directory_uri() . '/assets';
	$v   = FMA_TEMA_VERSIONE;

	wp_enqueue_style( 'fma-font', "$uri/css/fonts.css", array(), $v );
	wp_enqueue_style( 'fma-token', "$uri/css/tokens.css", array( 'fma-font' ), $v );
	wp_enqueue_style( 'fma-sito', "$uri/css/site.css", array( 'fma-token' ), $v );
	wp_enqueue_style( 'fma-wp', "$uri/css/wp.css", array( 'fma-sito' ), $v );

	$mappa = is_front_page() || is_singular( 'struttura' ) || is_post_type_archive( 'struttura' ) || is_tax( 'regione' );
	$dip   = array( 'fma-gsap', 'fma-scrolltrigger' );
	$def   = array( 'in_footer' => true, 'strategy' => 'defer' );

	wp_enqueue_script( 'fma-gsap', "$uri/vendor/gsap.min.js", array(), '3.13.0', $def );
	wp_enqueue_script( 'fma-scrolltrigger', "$uri/vendor/ScrollTrigger.min.js", array( 'fma-gsap' ), '3.13.0', $def );
	if ( $mappa ) {
		wp_enqueue_style( 'fma-leaflet', "$uri/vendor/leaflet/leaflet.css", array(), '1.9.4' );
		wp_enqueue_script( 'fma-leaflet', "$uri/vendor/leaflet/leaflet.js", array(), '1.9.4', $def );
		$dip[] = 'fma-leaflet';
	}
	wp_enqueue_script( 'fma-sito', "$uri/js/site.js", $dip, $v, $def );
}

/** Classi "js" e "motion" prima del primo disegno, come nella demo (evita il lampo delle animazioni). */
add_action(
	'wp_head',
	function (): void {
		echo "<script>document.documentElement.classList.add('js');if(!matchMedia('(prefers-reduced-motion: reduce)').matches)document.documentElement.classList.add('motion');setTimeout(function(){document.documentElement.classList.add('motion-ready')},3000)</script>\n";
	},
	1
);

add_action(
	'wp_head',
	function (): void {
		if ( ! has_site_icon() ) {
			echo '<link rel="icon" href="' . esc_url( get_template_directory_uri() . '/assets/img/favicon.png' ) . '">' . "\n";
		}
		echo '<meta name="theme-color" content="#f7f3ec">' . "\n";
	}
);

/* Niente emoji di WordPress: script e stili in meno su ogni pagina. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

add_filter(
	'body_class',
	function ( array $classi ): array {
		if ( is_singular( 'struttura' ) ) {
			$classi[] = 'pagina-struttura';
		}
		return $classi;
	}
);

/* Il menu compare due volte (barra e pannello mobile): niente id duplicati nel secondo. */
add_filter( 'nav_menu_item_id', fn( $id, $item, $args ) => empty( $args->fma_mobile ) ? $id : '', 10, 3 );

/**
 * Voce attiva del menu per sezione, come nella demo: "Strutture" su archivio, regioni e singole case,
 * "Blog" su articoli e archivi. I link con ancora (/#chi-siamo) non sono mai la pagina corrente.
 */
add_filter(
	'nav_menu_link_attributes',
	function ( array $attr, $item ): array {
		$url = untrailingslashit( (string) $item->url );
		unset( $attr['aria-current'] );
		if ( str_contains( $url, '#' ) ) {
			return $attr;
		}
		$attivo = ! empty( $item->current );
		if ( is_singular( 'struttura' ) || is_post_type_archive( 'struttura' ) || is_tax( 'regione' ) ) {
			$attivo = untrailingslashit( (string) get_post_type_archive_link( 'struttura' ) ) === $url;
		} elseif ( is_singular( 'post' ) || is_home() || is_category() || is_tag() || is_date() || is_author() ) {
			$blog   = (int) get_option( 'page_for_posts' );
			$attivo = $blog && ( (int) $item->object_id === $blog && 'page' === $item->object );
		}
		if ( $attivo ) {
			$attr['aria-current'] = 'page';
		}
		return $attr;
	},
	10,
	2
);

add_filter( 'excerpt_length', fn() => 30 );
add_filter( 'excerpt_more', fn() => '…' );

/** Le strutture nell'archivio seguono l'ordine scelto in amministrazione, tutte in una pagina. */
add_action(
	'pre_get_posts',
	function ( WP_Query $q ): void {
		if ( ! is_admin() && $q->is_main_query() && ( $q->is_post_type_archive( 'struttura' ) || $q->is_tax( 'regione' ) ) ) {
			$q->set( 'posts_per_page', 100 );
			$q->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
		}
	}
);

/** Avviso per gli amministratori se mancano i plugin del sito. */
add_action(
	'admin_notices',
	function (): void {
		if ( fma_plugin_attivo() && function_exists( 'fma_richieste_form' ) ) {
			return;
		}
		echo '<div class="notice notice-warning"><p><strong>Tema Accoglienza FMA:</strong> attiva i plugin <em>FMA Strutture</em> e <em>FMA Richieste</em> per strutture, mappa e richieste di soggiorno.</p></div>';
	}
);
