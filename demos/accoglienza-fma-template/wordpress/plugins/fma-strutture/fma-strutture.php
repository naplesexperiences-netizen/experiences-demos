<?php
/**
 * Plugin Name:       FMA Strutture
 * Description:       Strutture ricettive, partner e articoli collegati per il sito Accoglienza delle Salesiane.
 * Version:           1.3.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            experiences srl
 * License:           GPL-2.0-or-later
 * Text Domain:       fma-strutture
 */

defined( 'ABSPATH' ) || exit;

define( 'FMA_STRUTTURE_VERSIONE', '1.3.0' );
define( 'FMA_STRUTTURE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FMA_STRUTTURE_URL', plugin_dir_url( __FILE__ ) );

require FMA_STRUTTURE_DIR . 'includes/tipi.php';
require FMA_STRUTTURE_DIR . 'includes/campi.php';
require FMA_STRUTTURE_DIR . 'includes/dati.php';
require FMA_STRUTTURE_DIR . 'includes/recensioni.php';
require FMA_STRUTTURE_DIR . 'includes/riservatezza.php';
require FMA_STRUTTURE_DIR . 'includes/cache.php';

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require FMA_STRUTTURE_DIR . 'includes/importatore.php';
}

if ( is_admin() ) {
	require FMA_STRUTTURE_DIR . 'includes/admin.php';
	require FMA_STRUTTURE_DIR . 'includes/importa-pagina.php';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require FMA_STRUTTURE_DIR . 'includes/cli.php';
}

register_activation_hook(
	__FILE__,
	function () {
		fma_registra_tipi();
		flush_rewrite_rules();
	}
);
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

/**
 * Regole degli indirizzi sempre allineate: dopo un aggiornamento del plugin caricato come zip
 * (che non riattiva il plugin) o un cambio di permalink fatto senza salvare la pagina Permalink,
 * le regole si rigenerano una volta. Evita i 404 su /strutture/<nome>/ e /regione/<nome>/.
 */
add_action(
	'init',
	function () {
		$firma = FMA_STRUTTURE_VERSIONE . '|' . get_option( 'permalink_structure' );
		$regole = get_option( 'rewrite_rules' );
		$mancano = get_option( 'permalink_structure' ) && is_array( $regole ) && ! preg_grep( '#^strutture/#', array_keys( $regole ) );
		if ( $mancano || get_option( 'fma_strutture_regole' ) !== $firma ) {
			flush_rewrite_rules( false );
			update_option( 'fma_strutture_regole', $firma, true );
		}
	},
	99
);
