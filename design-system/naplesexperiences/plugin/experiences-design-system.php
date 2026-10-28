<?php
/**
 * Plugin Name:       Experiences Design System
 * Plugin URI:        https://naplesexperiences.com
 * Description:       Token, componenti exp-* e correzioni di contrasto WCAG AA per il tema experiences-theme. Carica i CSS dopo quelli del tema; disattivarlo riporta il sito com'era.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Experiences Srl
 * Author URI:        https://naplesexperiences.com
 * License:           GPL-2.0-or-later
 * Text Domain:       experiences-design-system
 */

defined( 'ABSPATH' ) || exit;

define( 'EXP_DS_VERSION', '1.0.0' );

/**
 * Ordine: tokens → (tailwind del tema) → components → (style.css del tema) → compat.
 * Le dipendenze dal tema si aggiungono solo se registrate: un handle mancante
 * farebbe saltare in silenzio lo stile che ne dipende.
 */
add_action( 'wp_enqueue_scripts', function () {
	$url  = plugin_dir_url( __FILE__ ) . 'assets/';
	$deps = function ( array $own, array $theme ) {
		foreach ( $theme as $handle ) {
			if ( wp_style_is( $handle, 'registered' ) ) {
				$own[] = $handle;
			}
		}
		return $own;
	};

	wp_enqueue_style( 'exp-ds-tokens', $url . 'tokens.css', array(), EXP_DS_VERSION );
	wp_enqueue_style( 'exp-ds-components', $url . 'components.css', $deps( array( 'exp-ds-tokens' ), array( 'experiences-tailwind' ) ), EXP_DS_VERSION );

	/* Il livello correttivo serve solo con experiences-theme; con un altro tema resta spento. */
	if ( 'experiences-theme' === get_template() ) {
		wp_enqueue_style( 'exp-ds-compat', $url . 'compat.css', $deps( array( 'exp-ds-components' ), array( 'experiences-tailwind', 'experiences-style' ) ), EXP_DS_VERSION );
	}
}, 20 );

/* I token servono anche nell'editor, per vedere i blocchi con i colori giusti. */
add_action( 'enqueue_block_editor_assets', function () {
	wp_enqueue_style( 'exp-ds-tokens', plugin_dir_url( __FILE__ ) . 'assets/tokens.css', array(), EXP_DS_VERSION );
} );

/* Link alla scheda dei componenti dalla lista dei plugin. */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	$links[] = '<a href="' . esc_url( plugin_dir_url( __FILE__ ) . 'preview.html' ) . '" target="_blank" rel="noopener">Anteprima componenti</a>';
	return $links;
} );
