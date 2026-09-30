<?php
/**
 * Funzioni di supporto del tema.
 */

defined( 'ABSPATH' ) || exit;

/** Set di icone del template (tratto 1.6), identico alla demo. */
function fma_icona( string $nome, string $classe = 'icon' ): string {
	static $tracciati = array(
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'chev-l'   => '<path d="M15 5l-7 7 7 7"/>',
		'chev-r'   => '<path d="M9 5l7 7-7 7"/>',
		'pause'    => '<path d="M9 5v14M15 5v14"/>',
		'play'     => '<path d="M8 5l11 7-11 7z"/>',
		'phone'    => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1z"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
		'chat'     => '<path d="M4 20l1.4-4.2A8 8 0 1 1 8.6 19z"/><path d="M9 10.5c.5 2 2 3.5 4 4l1.2-1.2 2 1-1 1.7c-3.4 0-7.2-3.8-7.2-7.2l1.7-1 1 2z"/>',
		'pin'      => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'globe'    => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.7 3.8 5.7 3.8 9s-1.3 6.3-3.8 9c-2.5-2.7-3.8-5.7-3.8-9S9.5 5.7 12 3z"/>',
		'check'    => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
		'photos'   => '<rect x="3" y="5" width="14" height="12" rx="1.5"/><path d="M7 20h12a2 2 0 0 0 2-2V8"/><path d="M3 14l4-4 4 4 2-2 4 4"/>',
		'minus'    => '<path d="M6 12h12"/>',
		'plus'     => '<path d="M12 6v12M6 12h12"/>',
		'menu'     => '<path d="M4 8h16M4 16h16"/>',
		'external' => '<path d="M14 5h5v5M19 5l-8 8M18 14v4a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h4"/>',
	);
	return '<svg class="' . esc_attr( $classe ) . '" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ( $tracciati[ $nome ] ?? '' ) . '</svg>';
}

function fma_e_icona( string $nome, string $classe = 'icon' ): void {
	echo fma_icona( $nome, $classe ); // phpcs:ignore WordPress.Security.EscapeOutput -- SVG statico.
}

/** "29 ottobre 2024" dal timestamp del post (formato italiano, indipendente dalle impostazioni). */
function fma_data( $post = null ): string {
	return mb_strtolower( wp_date( 'j F Y', get_post_timestamp( $post ) ) ); // in italiano i mesi vanno in minuscolo
}

function fma_minuti_lettura( $post = null ): int {
	$testo  = trim( wp_strip_all_tags( get_post_field( 'post_content', $post ) ) );
	$parole = '' === $testo ? 0 : count( preg_split( '/\s+/u', $testo ) );
	return max( 1, (int) round( $parole / 200 ) );
}

/** Immagine allegata con classi e priorità, o stringa vuota. */
function fma_immagine( int $id, string $taglia, array $attributi = array() ): string {
	if ( ! $id ) {
		return '';
	}
	return wp_get_attachment_image( $id, $taglia, false, $attributi );
}

/** I plugin del sito sono attivi? */
function fma_plugin_attivo(): bool {
	return function_exists( 'fma_struttura_dati' ) && post_type_exists( 'struttura' );
}

function fma_testo( string $chiave ): string {
	$predefiniti = fma_testi_predefiniti();
	return (string) get_theme_mod( 'fma_' . $chiave, $predefiniti[ $chiave ] ?? '' );
}

/** Link del menu principale di riserva, quando il menu non è ancora stato creato. */
function fma_menu_riserva( array $args ): void {
	$voci = array(
		'Strutture' => get_post_type_archive_link( 'struttura' ) ?: home_url( '/#strutture' ),
		'Chi siamo' => home_url( '/#chi-siamo' ),
		'Blog'      => get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/?post_type=post' ),
	);
	echo '<ul>';
	foreach ( $voci as $etichetta => $url ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $etichetta ) . '</a></li>';
	}
	echo '</ul>';
}

/** Numero di case e regioni per la riga della testata e il "Chi siamo". */
function fma_conteggi(): array {
	if ( ! fma_plugin_attivo() ) {
		return array( 'case' => 0, 'regioni' => 0 );
	}
	return array(
		'case'    => (int) wp_count_posts( 'struttura' )->publish,
		'regioni' => count( fma_regioni() ),
	);
}

function fma_numero_in_lettere( int $n ): string {
	$nomi = array( 'zero', 'una', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci', 'undici', 'dodici' );
	return $nomi[ $n ] ?? (string) $n;
}

/**
 * Divide il contenuto dell'editor in sezioni a ogni titolo H2, come i blocchi della demo:
 * ogni sezione diventa <section class="block prose"> con il titolo in stile block__title.
 */
function fma_sezioni_contenuto( string $html ): string {
	$html = trim( $html );
	if ( '' === $html ) {
		return '';
	}
	$out = '';
	foreach ( preg_split( '/(?=<h2[\s>])/i', $html, -1, PREG_SPLIT_NO_EMPTY ) as $i => $pezzo ) {
		$pezzo = trim( $pezzo );
		if ( '' === $pezzo ) {
			continue;
		}
		$id    = 'h-sezione-' . ( $i + 1 );
		$ha_h2 = false;
		$pezzo = preg_replace_callback(
			'/^<h2([^>]*)>/i',
			function ( $m ) use ( $id, &$ha_h2 ) {
				$ha_h2 = true;
				$attr  = preg_replace( '/\sid="[^"]*"/i', '', $m[1] );
				$attr  = preg_match( '/class="/i', $attr )
					? preg_replace( '/class="/i', 'class="block__title ', $attr, 1 )
					: $attr . ' class="block__title"';
				return '<h2 id="' . $id . '"' . $attr . '>';
			},
			$pezzo,
			1
		);
		$out .= $ha_h2
			? '<section class="block prose" aria-labelledby="' . $id . '">' . $pezzo . '</section>'
			: '<div class="block prose">' . $pezzo . '</div>';
	}
	return $out;
}

/** Prezzo leggibile: 45 → "45", 42.5 → "42,50". */
function fma_prezzo( float $valore ): string {
	return floor( $valore ) === $valore ? number_format_i18n( $valore ) : number_format_i18n( $valore, 2 );
}
