<?php
/**
 * Tema Accoglienza FMA.
 */

defined( 'ABSPATH' ) || exit;

define( 'FMA_TEMA_VERSIONE', '1.4.0' );

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/condivisione.php';

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

	// Stesso taglio 16:10 in tre larghezze: così lo srcset dello slider offre la misura giusta per ogni schermo.
	add_image_size( 'fma-hero', 1920, 1200, true );
	add_image_size( 'fma-hero-m', 1200, 750, true );
	add_image_size( 'fma-hero-s', 800, 500, true );
	add_image_size( 'fma-card', 720, 480, true );
	add_image_size( 'fma-social', 1200, 630, true ); // anteprima dei link condivisi (Open Graph)
	add_image_size( 'fma-card-s', 400, 267, true ); // miniature dell'elenco strutture su schermi ad alta densità
	add_image_size( 'fma-quadrato', 320, 320, true );
}

add_action( 'wp_enqueue_scripts', 'fma_tema_asset' );

function fma_tema_asset(): void {
	$uri = get_template_directory_uri() . '/assets';
	// La versione nell'indirizzo cambia a ogni modifica del file: browser e cache (WP-Optimize, CDN) prendono subito quello nuovo.
	$v = function ( string $file ): string {
		$percorso = get_template_directory() . '/assets/' . $file;
		return FMA_TEMA_VERSIONE . ( is_file( $percorso ) ? '.' . filemtime( $percorso ) : '' );
	};

	wp_enqueue_style( 'fma-font', "$uri/css/fonts.css", array(), $v( 'css/fonts.css' ) );
	wp_enqueue_style( 'fma-token', "$uri/css/tokens.css", array( 'fma-font' ), $v( 'css/tokens.css' ) );
	wp_enqueue_style( 'fma-sito', "$uri/css/site.css", array( 'fma-token' ), $v( 'css/site.css' ) );
	wp_enqueue_style( 'fma-wp', "$uri/css/wp.css", array( 'fma-sito' ), $v( 'css/wp.css' ) );

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
	wp_enqueue_script( 'fma-sito', "$uri/js/site.js", $dip, $v( 'js/site.js' ), $def );
	if ( ! str_starts_with( determine_locale(), 'it' ) ) {
		wp_add_inline_script( 'fma-sito', 'window.fmaTesti=' . wp_json_encode( fma_testi_js(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . ';', 'before' );
	}
	if ( is_singular( 'struttura' ) ) {
		// Dopo site.js: legge la conferma del modulo che site.js mostra. Vedi assets/js/eventi.js.
		wp_enqueue_script( 'fma-eventi', "$uri/js/eventi.js", array( 'fma-sito' ), $v( 'js/eventi.js' ), $def );
	}
}

/**
 * Google Analytics (Site Kit): nella pagina di una casa il parametro «casa» entra nella
 * configurazione del tag, così accompagna tutti gli eventi della pagina, compresi i clic su
 * email, telefono e WhatsApp che Site Kit registra da solo. Un gtag('set') a pagina caricata
 * non basta: Google applica i parametri condivisi solo se arrivano con la configurazione.
 */
add_filter(
	'googlesitekit_gtag_opt',
	function ( $opzioni ) {
		if ( is_array( $opzioni ) && is_singular( 'struttura' ) ) {
			$opzioni['casa'] = wp_strip_all_tags( get_the_title( get_queried_object_id() ) );
		}
		return $opzioni;
	}
);

/**
 * Testi di site.js nella lingua della pagina (le chiavi sono quelle dell'oggetto T in site.js,
 * dove c'è l'italiano). %1, %2… sono i valori inseriti dallo script.
 */
function fma_testi_js(): array {
	global $wp_locale;
	return array(
		// Mesi abbreviati come nelle date: «mai» e non «Mai» in francese, «5月» senza spazio in cinese.
		'mesi'             => array_values(
			array_map(
				fn( $m ) => str_starts_with( determine_locale(), 'en' ) ? wp_strip_all_tags( $m ) : mb_strtolower( str_replace( ' ', '', wp_strip_all_tags( $m ) ) ),
				$wp_locale->month_abbrev
			)
		),
		/* translators: data breve in JavaScript: %1 giorno, %2 mese abbreviato */
		'giornoMese'       => _x( '%1 %2', 'data breve: giorno e mese', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'notte'            => __( '%1 notte', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'notti'            => __( '%1 notti', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'ospite'           => __( '%1 ospite', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'ospiti'           => __( '%1 ospiti', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'adulto'           => __( '%1 adulto', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'adulti'           => __( '%1 adulti', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'bambino'          => __( '%1 bambino', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'bambini'          => __( '%1 bambini', 'accoglienza-fma' ),
		'unaCasa'          => __( '1 casa', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'nCase'            => __( '%1 case', 'accoglienza-fma' ),
		'tutteCase'        => __( 'Tutte le case', 'accoglienza-fma' ),
		'scopri'           => __( 'Scopri la casa →', 'accoglienza-fma' ),
		'pausa'            => __( 'Metti in pausa lo scorrimento', 'accoglienza-fma' ),
		'riprendi'         => __( 'Riprendi lo scorrimento', 'accoglienza-fma' ),
		'dateMancanti'     => __( 'Indica sia la data di arrivo sia quella di partenza, oppure lasciale vuote.', 'accoglienza-fma' ),
		'dateOrdine'       => __( 'La partenza deve essere dopo l’arrivo.', 'accoglienza-fma' ),
		/* translators: %1: riepilogo della ricerca */
		'staiCercando'     => __( 'Stai cercando: %1. Le date passano al modulo della casa che scegli.', 'accoglienza-fma' ),
		'fotoStruttura'    => __( 'Foto della struttura', 'accoglienza-fma' ),
		'chiudi'           => __( 'Chiudi', 'accoglienza-fma' ),
		'fotoPrec'         => __( 'Foto precedente', 'accoglienza-fma' ),
		'fotoSucc'         => __( 'Foto successiva', 'accoglienza-fma' ),
		/* translators: %1: nome della casa, %2: numero della foto, %3: totale */
		'fotoAlt'          => __( '%1, foto %2 di %3', 'accoglienza-fma' ),
		/* translators: %1: «3 notti», %2: data di arrivo, %3: data di partenza */
		'notteDalAl'       => __( '%1 · dal %2 al %3', 'accoglienza-fma' ),
		'arrivoVuoto'      => __( 'Scegli la data di arrivo.', 'accoglienza-fma' ),
		'partenzaVuota'    => __( 'Scegli la data di partenza.', 'accoglienza-fma' ),
		'nomeCorto'        => __( 'Scrivi nome e cognome.', 'accoglienza-fma' ),
		'emailErrata'      => __( 'Controlla l’indirizzo email (es. nome@esempio.it).', 'accoglienza-fma' ),
		'privacy'          => __( 'Serve il consenso per inviare la richiesta.', 'accoglienza-fma' ),
		'mancaUna'         => __( 'Manca un’informazione: la trovi evidenziata qui sopra.', 'accoglienza-fma' ),
		/* translators: %1: numero */
		'mancanoN'         => __( 'Mancano %1 informazioni: le trovi evidenziate qui sopra.', 'accoglienza-fma' ),
		'laCasa'           => __( 'la casa', 'accoglienza-fma' ),
		'cameraConcordare' => __( 'camera da concordare', 'accoglienza-fma' ),
		/* translators: %1: nome della casa, %2: arrivo, %3: partenza */
		'riepilogoCasa'    => __( '%1 · dal %2 al %3', 'accoglienza-fma' ),
		/* translators: %1: arrivo, %2: partenza */
		'riepilogo'        => __( 'Dal %1 al %2', 'accoglienza-fma' ),
		'connessione'      => __( 'Connessione non riuscita: la richiesta non è partita.', 'accoglienza-fma' ),
		/* translators: %1: indirizzo email */
		'scriviA'          => __( 'Puoi scrivere direttamente a %1.', 'accoglienza-fma' ),
	);
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
