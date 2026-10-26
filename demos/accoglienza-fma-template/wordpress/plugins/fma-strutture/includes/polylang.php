<?php
/**
 * Polylang: le strutture, le regioni e i servizi si traducono come pagine e articoli.
 *
 * Partner e recensioni restano senza lingua: compaiono uguali in tutte le versioni del sito
 * (un logo non si traduce, una recensione resta nella lingua in cui l'ospite l'ha scritta).
 */

defined( 'ABSPATH' ) || exit;

/** Campi della struttura con testo da tradurre: ogni lingua ha il suo valore. */
const FMA_META_DA_TRADURRE = array(
	'fma_titolo_hero',
	'fma_tipo',
	'fma_localita',
	'fma_tassa',
	'fma_orari',
	'fma_seo_titolo',
	'fma_seo_descrizione',
	'fma_camere',
	'fma_dintorni',
	'fma_regole',
);

// Tipi e tassonomie traducibili, impostati dal plugin (nella pagina di Polylang non compaiono, così non si spengono per errore).
add_filter(
	'pll_get_post_types',
	function ( $tipi, $impostazioni ) {
		if ( $impostazioni ) {
			unset( $tipi['struttura'] );
		} else {
			$tipi['struttura'] = 'struttura';
		}
		return $tipi;
	},
	10,
	2
);
add_filter(
	'pll_get_taxonomies',
	function ( $tassonomie, $impostazioni ) {
		foreach ( array( 'regione', 'servizio' ) as $t ) {
			if ( $impostazioni ) {
				unset( $tassonomie[ $t ] );
			} else {
				$tassonomie[ $t ] = $t;
			}
		}
		return $tassonomie;
	},
	10,
	2
);

/**
 * Quando Polylang copia o sincronizza i campi tra le traduzioni (nuova traduzione, oppure
 * «Sincronizzazione → Campi personalizzati» attiva), passano solo quelli uguali in tutte le
 * lingue: coordinate, contatti, email per le richieste, prezzo, foto. I testi tradotti restano propri.
 */
add_filter(
	'pll_copy_post_metas',
	function ( $chiavi ) {
		return array_values( array_diff( (array) $chiavi, FMA_META_DA_TRADURRE ) );
	}
);

/** Lingua corrente del sito (slug Polylang), «it» senza Polylang. */
function fma_lingua(): string {
	return function_exists( 'pll_current_language' ) && pll_current_language() ? (string) pll_current_language() : 'it';
}

/**
 * Informativa privacy non ancora tradotta: Polylang la nasconde nelle altre lingue e sparirebbero
 * il link nel piè di pagina e quello del consenso nel modulo. Finché manca la traduzione si
 * rimanda alla versione italiana.
 */
add_filter(
	'privacy_policy_url',
	function ( $url ) {
		if ( $url || ! function_exists( 'pll_current_language' ) ) {
			return $url;
		}
		global $wpdb;
		$id = (int) $wpdb->get_var( "SELECT option_value FROM {$wpdb->options} WHERE option_name = 'wp_page_for_privacy_policy'" );
		return $id && 'publish' === get_post_status( $id ) ? (string) get_permalink( $id ) : $url;
	}
);
