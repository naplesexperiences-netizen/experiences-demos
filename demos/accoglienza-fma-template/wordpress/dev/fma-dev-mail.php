<?php
/**
 * Solo per il sito locale: nessuna email esce davvero. Ogni wp_mail finisce in wp-content/mail-log.json.
 */
add_filter(
	'pre_wp_mail',
	function ( $nullo, $atts ) {
		$file   = WP_CONTENT_DIR . '/mail-log.json';
		$log    = is_file( $file ) ? (array) json_decode( (string) file_get_contents( $file ), true ) : array();
		$log[]  = array( 'quando' => gmdate( 'c' ) ) + $atts;
		file_put_contents( $file, wp_json_encode( $log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );
		return true;
	},
	10,
	2
);
