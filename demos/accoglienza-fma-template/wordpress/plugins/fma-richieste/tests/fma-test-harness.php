<?php
// Solo per test locali: CPT, cattura email, pagina struttura con il modulo.
add_action( 'init', function () {
	register_post_type( 'struttura', array( 'public' => true, 'label' => 'Strutture', 'rewrite' => array( 'slug' => 'strutture' ) ) );
	register_post_type( 'property', array( 'public' => true, 'label' => 'Immobili RealHomes' ) );
	register_post_type( 'agent', array( 'public' => false, 'label' => 'Referenti' ) );
} );
add_filter( 'pre_wp_mail', function ( $null, $atts ) {
	$f   = WP_CONTENT_DIR . '/mail-log.json';
	$log = file_exists( $f ) ? json_decode( file_get_contents( $f ), true ) : array();
	$log[] = $atts;
	file_put_contents( $f, wp_json_encode( $log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );
	return get_option( 'fma_test_mail_fail' ) ? false : true;
}, 10, 2 );
add_filter( 'the_content', function ( $c ) {
	if ( ! is_singular( array( 'struttura', 'property' ) ) || ! function_exists( 'fma_richieste_form' ) ) return $c;
	ob_start();
	echo '<div class="stay"><div class="stay__main">' . $c . '<div class="request-slot" data-request-slot></div></div><aside class="stay__aside" data-aside>';
	fma_richieste_form( get_the_ID() );
	echo '</aside></div>';
	return ob_get_clean();
} );
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_singular( array( 'struttura', 'property' ) ) ) return;
	$u = content_url( 'fma-assets' );
	wp_enqueue_style( 'fma-tokens', "$u/css/tokens.css" );
	wp_enqueue_style( 'fma-site', "$u/css/site.css", array( 'fma-tokens' ) );
	wp_enqueue_script( 'fma-site', "$u/js/site.js", array(), null, true );
} );
