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

/** Data dell'articolo nel formato della lingua del sito: «29 ottobre 2024», «29 October 2024», «2024年10月29日». */
function fma_data( $post = null ): string {
	/* translators: formato della data degli articoli (https://www.php.net/manual/datetime.format.php) */
	$data = wp_date( _x( 'j F Y', 'formato data articoli', 'accoglienza-fma' ), get_post_timestamp( $post ) );
	return str_starts_with( determine_locale(), 'it' ) ? mb_strtolower( $data ) : $data; // in italiano i mesi vanno in minuscolo
}

/** Minuti di lettura: 200 parole al minuto; in cinese, giapponese e coreano 400 caratteri al minuto. */
function fma_minuti_lettura( $post = null ): int {
	$testo = trim( wp_strip_all_tags( get_post_field( 'post_content', $post ) ) );
	if ( preg_match( '/^(zh|ja|ko)/', determine_locale() ) ) {
		return max( 1, (int) round( mb_strlen( preg_replace( '/\s+/u', '', $testo ) ) / 400 ) );
	}
	$parole = '' === $testo ? 0 : count( preg_split( '/\s+/u', $testo ) );
	return max( 1, (int) round( $parole / 200 ) );
}

/** «4 min» oppure, con $lungo, «4 min di lettura». */
function fma_minuti_testo( int $minuti, bool $lungo = false ): string {
	/* translators: %d: minuti */
	return sprintf( $lungo ? __( '%d min di lettura', 'accoglienza-fma' ) : __( '%d min', 'accoglienza-fma' ), $minuti );
}

/** «8 case in 5 regioni», o in breve «8 case · 5 regioni». */
function fma_case_regioni( int $case, int $regioni, bool $breve = false ): string {
	/* translators: %d: numero di case */
	$c = sprintf( _n( '%d casa', '%d case', $case, 'accoglienza-fma' ), $case );
	/* translators: %d: numero di regioni */
	$r = sprintf( _n( '%d regione', '%d regioni', $regioni, 'accoglienza-fma' ), $regioni );
	/* translators: 1: «8 case», 2: «5 regioni» */
	return $breve ? $c . ' · ' . $r : sprintf( __( '%1$s in %2$s', 'accoglienza-fma' ), $c, $r );
}

/** «1 casa», «8 case». */
function fma_n_case( int $n ): string {
	/* translators: %d: numero di case */
	return sprintf( _n( '%d casa', '%d case', $n, 'accoglienza-fma' ), $n );
}

/** Testo sotto il titolo del blog quando la pagina Blog non ha un riassunto. */
function fma_blog_lede(): string {
	return __( 'Consigli di viaggio e storie dai luoghi delle nostre case: cosa vedere, quando andare, come arrivare.', 'accoglienza-fma' );
}

/** Con Polylang, la traduzione nella lingua corrente di un contenuto (per esempio la casa vicina di un articolo). */
function fma_traduzione( int $id ): int {
	if ( $id && function_exists( 'pll_get_post' ) ) {
		$tradotto = (int) pll_get_post( $id );
		return $tradotto ?: $id;
	}
	return $id;
}

/**
 * Selettore della lingua (Polylang). In testata un menu a tendina con il codice della lingua
 * corrente; nel menu del telefono l'elenco completo. Senza Polylang, o con una sola lingua, nulla.
 */
function fma_lingue( string $classe ): void {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return;
	}
	$lingue = (array) pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
	if ( count( $lingue ) < 2 ) {
		return;
	}
	$corrente = current( array_filter( $lingue, fn( $l ) => ! empty( $l['current_lang'] ) ) ) ?: reset( $lingue );
	$voci     = '';
	foreach ( $lingue as $l ) {
		$voci .= sprintf(
			'<li><a href="%1$s" hreflang="%2$s" lang="%2$s"%3$s>%4$s</a></li>',
			esc_url( $l['url'] ),
			esc_attr( $l['locale'] ? str_replace( '_', '-', $l['locale'] ) : $l['slug'] ),
			empty( $l['current_lang'] ) ? '' : ' aria-current="true"',
			esc_html( $l['name'] )
		);
	}
	if ( 'mast__lang' === $classe ) {
		printf(
			'<details class="lang %1$s" data-lang><summary aria-label="%2$s"><span aria-hidden="true">%3$s</span></summary><ul>%4$s</ul></details>',
			esc_attr( $classe ),
			/* translators: %s: lingua corrente */
			esc_attr( sprintf( __( 'Lingua: %s', 'accoglienza-fma' ), $corrente['name'] ) ),
			esc_html( fma_codice_lingua( $corrente['slug'] ) ),
			$voci // phpcs:ignore WordPress.Security.EscapeOutput -- costruito sopra con esc_*.
		);
	} else {
		printf( '<nav class="lang %1$s" aria-label="%2$s"><ul>%3$s</ul></nav>', esc_attr( $classe ), esc_attr__( 'Lingua', 'accoglienza-fma' ), $voci ); // phpcs:ignore
	}
}

/** Sigla breve della lingua in testata: IT, EN, FR… e 中文 per il cinese. */
function fma_codice_lingua( string $slug ): string {
	return 'zh' === $slug ? '中文' : strtoupper( $slug );
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

/**
 * Testo della home (Personalizza → Home page). Se è quello predefinito si traduce come il resto
 * del tema; se è stato cambiato, la traduzione si scrive in Lingue → Traduzioni stringhe (Polylang).
 */
function fma_testo( string $chiave ): string {
	$predefiniti = fma_testi_predefiniti();
	$originale   = $predefiniti[ $chiave ] ?? '';
	$valore      = (string) get_theme_mod( 'fma_' . $chiave, $originale );
	if ( $valore === $originale ) {
		return '' === $valore ? '' : translate( $valore, 'accoglienza-fma' ); // phpcs:ignore WordPress.WP.I18n -- testi predefiniti, elencati in fma_testi_predefiniti() con __().
	}
	return function_exists( 'pll__' ) ? pll__( $valore ) : $valore;
}

/** Link del menu principale di riserva, quando il menu non è ancora stato creato. */
function fma_menu_riserva( array $args ): void {
	$voci = array(
		__( 'Strutture', 'accoglienza-fma' ) => get_post_type_archive_link( 'struttura' ) ?: home_url( '/#strutture' ),
		__( 'Chi siamo', 'accoglienza-fma' ) => home_url( '/#chi-siamo' ),
		__( 'Blog', 'accoglienza-fma' )      => get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/?post_type=post' ),
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
		'case'    => count( fma_strutture() ), // solo la lingua corrente: wp_count_posts conterebbe anche le traduzioni
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

/** «a persona a notte», «a camera a notte» o solo «a notte», secondo il campo «Il prezzo è». */
function fma_prezzo_unita( string $unita ): string {
	return array( 'persona' => __( 'a persona a notte', 'accoglienza-fma' ), 'camera' => __( 'a camera a notte', 'accoglienza-fma' ) )[ $unita ] ?? __( 'a notte', 'accoglienza-fma' );
}

/** Tassa di soggiorno senza l'eventuale «Tassa di soggiorno:» iniziale, perché l'etichetta la mette il tema. */
function fma_tassa_testo( string $tassa ): string {
	return trim( (string) preg_replace( '/^\s*tassa\s+di\s+soggiorno\s*[:\-–]?\s*/iu', '', $tassa ) );
}

/**
 * Immagine da caricare più tardi via JS (slide dello slider non ancora visibili):
 * src, srcset e sizes passano in attributi data-, al loro posto un segnaposto trasparente.
 */
function fma_immagine_rimandata( string $html ): string {
	if ( '' === $html ) {
		return '';
	}
	$html = preg_replace( '/\s(srcset|sizes|src)="/', ' data-$1="', $html );
	$html = preg_replace( '/\sloading="[^"]*"|\sfetchpriority="[^"]*"|\sdecoding="[^"]*"/', '', $html );
	return str_replace( '<img ', '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" decoding="async" ', $html );
}

/**
 * Foto di un passo di «Come si prenota»: quella scelta in Personalizza, altrimenti una foto delle case
 * importate (riconosciuta dal percorso d'origine), altrimenti la foto principale della prima struttura.
 */
function fma_passo_immagine( int $n ): int {
	$scelta = (int) get_theme_mod( "fma_passo{$n}_immagine", 0 );
	if ( $scelta && wp_attachment_is_image( $scelta ) ) {
		return $scelta;
	}
	$predefinite = array(
		1 => 'strutture/san-giuseppe-caorle/hero.webp',
		2 => 'strutture/villa-crawford/g1.webp',
		3 => 'strutture/villa-crawford/g3.webp',
	);
	if ( isset( $predefinite[ $n ] ) ) {
		$trovate = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => '_fma_origine', 'meta_value' => $predefinite[ $n ] ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		if ( $trovate ) {
			return (int) $trovate[0];
		}
	}
	if ( 1 === $n && fma_plugin_attivo() ) {
		$prima = get_posts( array( 'post_type' => 'struttura', 'numberposts' => 1, 'orderby' => 'menu_order', 'order' => 'ASC', 'fields' => 'ids' ) );
		return $prima ? (int) get_post_thumbnail_id( $prima[0] ) : 0;
	}
	return 0;
}
