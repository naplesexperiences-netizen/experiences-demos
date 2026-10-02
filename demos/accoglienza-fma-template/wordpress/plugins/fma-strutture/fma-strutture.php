<?php
/**
 * Plugin Name:       FMA Strutture
 * Description:       Strutture ricettive, partner e articoli collegati per il sito Accoglienza delle Salesiane.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            experiences srl
 * License:           GPL-2.0-or-later
 * Text Domain:       fma-strutture
 */

defined( 'ABSPATH' ) || exit;

define( 'FMA_STRUTTURE_VERSIONE', '1.0.0' );
define( 'FMA_STRUTTURE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FMA_STRUTTURE_URL', plugin_dir_url( __FILE__ ) );

require FMA_STRUTTURE_DIR . 'includes/tipi.php';
require FMA_STRUTTURE_DIR . 'includes/campi.php';
require FMA_STRUTTURE_DIR . 'includes/dati.php';

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
