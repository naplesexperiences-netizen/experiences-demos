<?php
/**
 * Titolo e descrizione per Google delle strutture (campi «Titolo per Google» e «Descrizione per Google»).
 *
 * Per le otto case FMA il plugin porta già un titolo con località e tipo di casa e una descrizione
 * scritta apposta (fra 140 e 155 caratteri, frasi intere). Si inseriscono una volta sola e solo
 * nei campi vuoti: quello che è stato scritto in amministrazione non viene mai toccato.
 */

defined( 'ABSPATH' ) || exit;

const FMA_SEO_VERSIONE = 1;

/** @return array<string, array{0:string,1:string}> slug => [ titolo, descrizione ] */
function fma_seo_predefiniti(): array {
	return array(
		'villa-tiberiade' => array(
			'Villa Tiberiade, casa per ferie a Torre Annunziata',
			'Casa di accoglienza e spiritualità a Torre Annunziata, con vista sul Golfo di Napoli e giardino, a 10 minuti da Pompei. Camere, sala convegni e parcheggio.',
		),
		'villa-crawford' => array(
			'Villa Crawford, dimora storica a Sant’Agnello',
			'La villa dello scrittore Marion Crawford a Sant’Agnello, a picco sul Golfo di Napoli. Parco, cappella e sale panoramiche per ritiri e incontri.',
		),
		'fma-napoli' => array(
			'Casa FMA Napoli, casa per ferie a Napoli',
			'Casa per ferie delle Salesiane a Napoli, nella sede dell’Ispettoria: 12 camere singole e doppie con aria condizionata e wifi, per famiglie e gruppi.',
		),
		'fma-roma' => array(
			'Casa per Ferie Maria Ausiliatrice a Roma Termini',
			'Casa per ferie delle Salesiane in via Marghera, a due passi dalla stazione Termini: 20 camere per gruppi parrocchiali, famiglie, religiosi e pellegrini.',
		),
		'san-giuseppe-caorle' => array(
			'Casa per Ferie San Giuseppe a Caorle, sul mare',
			'Casa per ferie delle Salesiane a Caorle, a un passo dal mare: camere singole, triple e quadruple per famiglie, gruppi e chi cerca riposo.',
		),
		'villa-tabor' => array(
			'Villa Tabor, casa per ferie sull’Altopiano di Asiago',
			'Casa per ferie a Cesuna di Roana, sull’Altopiano di Asiago, tra faggi e abeti: 15 camere, cappella e parcheggio gratuito per gruppi, famiglie e ritiri.',
		),
		'casa-per-feriemaria-mazzarello' => array(
			'Casa per Ferie Mazzarello a Verbania, sul Lago Maggiore',
			'Casa per ferie a Verbania con vista sul Lago Maggiore: parco panoramico, sale incontri, cappelle e parcheggio privato. Silenzio e accoglienza familiare.',
		),
		'fma-soverato' => array(
			'Casa FMA Soverato, casa per ferie in Calabria',
			'Casa per ferie delle Salesiane a Soverato, sulla costa ionica calabrese: 15 camere con aria condizionata, wifi e parcheggio gratuito per famiglie e gruppi.',
		),
	);
}

/** Completa i campi vuoti di una struttura con i testi predefiniti del suo slug. */
function fma_seo_completa( int $id ): void {
	$slug  = (string) get_post_field( 'post_name', $id );
	$testi = fma_seo_predefiniti()[ $slug ] ?? fma_seo_predefiniti()[ str_replace( 'ferie-maria', 'feriemaria', $slug ) ] ?? null; // anche dopo la correzione dell'indirizzo di Mazzarello
	if ( ! $testi ) {
		return;
	}
	foreach ( array( 'fma_seo_titolo' => $testi[0], 'fma_seo_descrizione' => $testi[1] ) as $chiave => $valore ) {
		if ( '' === (string) get_post_meta( $id, $chiave, true ) ) {
			update_post_meta( $id, $chiave, $valore );
		}
	}
}

// Sui siti già popolati: una volta, dopo l'aggiornamento del plugin.
add_action(
	'init',
	function () {
		if ( (int) get_option( 'fma_seo_versione' ) >= FMA_SEO_VERSIONE ) {
			return;
		}
		foreach ( get_posts( array( 'post_type' => 'struttura', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ) ) as $id ) {
			fma_seo_completa( (int) $id );
		}
		update_option( 'fma_seo_versione', FMA_SEO_VERSIONE, true );
	},
	20
);

/**
 * robots.txt con un solo gruppo per ogni User-agent. WordPress scrive il suo gruppo
 * «User-agent: *» e alcuni plugin (WP-Optimize) ne aggiungono un secondo uguale: qui le
 * regole si riuniscono sotto un'unica intestazione e le righe Sitemap vanno in fondo.
 */
add_filter( 'robots_txt', 'fma_robots_unico', 999 );

function fma_robots_unico( string $testo ): string {
	$gruppi    = array(); // user-agent => regole
	$sitemap   = array();
	$note      = array();
	$correnti  = array();
	$in_regole = false;
	foreach ( preg_split( '/\R/', $testo ) as $riga ) {
		$riga = trim( $riga );
		if ( '' === $riga ) {
			continue;
		}
		if ( preg_match( '/^user-agent\s*:\s*(.+)$/i', $riga, $m ) ) {
			if ( $in_regole ) { // un nuovo gruppo comincia; più User-agent di fila condividono le regole
				$correnti  = array();
				$in_regole = false;
			}
			$ua            = trim( $m[1] );
			$correnti[]    = $ua;
			$gruppi[ $ua ] = $gruppi[ $ua ] ?? array();
		} elseif ( preg_match( '/^sitemap\s*:/i', $riga ) ) {
			$sitemap[] = $riga;
		} elseif ( ! $correnti || str_starts_with( $riga, '#' ) ) {
			$note[] = $riga;
		} else {
			$in_regole = true;
			foreach ( $correnti as $ua ) {
				if ( ! in_array( $riga, $gruppi[ $ua ], true ) ) {
					$gruppi[ $ua ][] = $riga;
				}
			}
		}
	}
	$out = $note ? implode( "\n", $note ) . "\n\n" : '';
	foreach ( $gruppi as $ua => $regole ) {
		$out .= "User-agent: {$ua}\n" . implode( "\n", $regole ) . "\n\n";
	}
	return $out . implode( "\n", array_unique( $sitemap ) ) . "\n";
}

// /sitemap_index.xml (l'indirizzo di Yoast, che molti strumenti provano) porta alla mappa di WordPress.
add_action(
	'init',
	function () {
		$percorso = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$base     = rtrim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
		if ( $base . '/sitemap_index.xml' === $percorso && ! fma_seo_plugin_sitemap() ) {
			wp_safe_redirect( home_url( '/wp-sitemap.xml' ), 301 );
			exit;
		}
	},
	1
);

/** Con Yoast o Rank Math la loro sitemap_index.xml esiste già. */
function fma_seo_plugin_sitemap(): bool {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}
