<?php
/**
 * Cache di pagina svuotata dopo ogni aggiornamento di tema o plugin FMA.
 *
 * Caricare un nuovo zip non svuota la cache di WP-Optimize: le pagine restano quelle vecchie
 * (senza le correzioni) finché non scadono. Qui si confronta la data dei file principali con
 * quella dell'ultima volta; se è cambiata, la cache si svuota una volta sola.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'fma_cache_dopo_aggiornamento', 100 );

function fma_cache_firma(): string {
	$file = array_unique(
		array(
			get_template_directory() . '/style.css',
			get_stylesheet_directory() . '/style.css',
			FMA_STRUTTURE_DIR . 'fma-strutture.php',
			WP_PLUGIN_DIR . '/fma-richieste/fma-richieste.php',
		)
	);
	$firma = get_stylesheet();
	foreach ( $file as $f ) {
		$firma .= '|' . ( is_file( $f ) ? filemtime( $f ) : 0 );
	}
	return md5( $firma );
}

function fma_cache_dopo_aggiornamento(): void {
	$firma = fma_cache_firma();
	if ( get_option( 'fma_cache_firma' ) === $firma ) {
		return;
	}
	update_option( 'fma_cache_firma', $firma, true );
	fma_svuota_cache();
}

/** Svuota la cache di pagina dei plugin più diffusi; senza plugin di cache non fa nulla. */
function fma_svuota_cache(): void {
	$wpo = function_exists( 'WP_Optimize' ) && method_exists( WP_Optimize(), 'get_page_cache' ) ? WP_Optimize()->get_page_cache() : null;
	if ( is_object( $wpo ) && method_exists( $wpo, 'purge' ) ) {
		$wpo->purge();
	} elseif ( function_exists( 'wpo_cache_flush' ) ) {
		wpo_cache_flush();
	}
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
	}
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
	do_action( 'litespeed_purge_all' );
	do_action( 'fma_cache_svuotata' );
}
