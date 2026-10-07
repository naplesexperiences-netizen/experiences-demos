<?php
/**
 * Accesso ai dati per il tema: una struttura come array con la stessa forma della demo statica.
 */

defined( 'ABSPATH' ) || exit;

function fma_link_telefono( string $numero ): string {
	$cifre = preg_replace( '/[^\d+]/', '', $numero );
	if ( '' === $cifre ) {
		return '';
	}
	if ( str_starts_with( $cifre, '+' ) ) {
		return $cifre;
	}
	if ( str_starts_with( $cifre, '00' ) ) {
		return '+' . substr( $cifre, 2 );
	}
	return '+39' . $cifre; // numeri italiani senza prefisso: lo 0 dei fissi resta
}

function fma_link_whatsapp( string $numero ): string {
	$link = fma_link_telefono( $numero );
	return $link ? 'https://wa.me/' . ltrim( $link, '+' ) : '';
}

/**
 * @param int|WP_Post $post
 */
function fma_struttura_dati( $post ): array {
	$post = get_post( $post );
	if ( ! $post || 'struttura' !== $post->post_type ) {
		return array();
	}
	$id  = $post->ID;
	$m   = function ( string $k ) use ( $id ) {
		return get_post_meta( $id, $k, true );
	};

	$regioni = get_the_terms( $id, 'regione' );
	$servizi = get_the_terms( $id, 'servizio' );
	$tel     = (string) $m( 'fma_telefono' );
	$wa      = (string) $m( 'fma_whatsapp' );
	$cell    = (string) $m( 'fma_cellulare' );

	$galleria = array_values( array_filter( array_map( 'absint', (array) $m( 'fma_galleria' ) ) ) );

	return array(
		'id'            => $id,
		'nome'          => get_the_title( $post ),
		'url'           => get_permalink( $post ),
		'titolo_hero'   => (string) $m( 'fma_titolo_hero' ),
		'tipo'          => (string) $m( 'fma_tipo' ),
		'localita'      => (string) $m( 'fma_localita' ),
		'provincia'     => strtoupper( (string) $m( 'fma_provincia' ) ),
		'regione'       => ( $regioni && ! is_wp_error( $regioni ) ) ? $regioni[0]->name : '',
		'regione_slug'  => ( $regioni && ! is_wp_error( $regioni ) ) ? $regioni[0]->slug : '',
		'indirizzo'     => (string) $m( 'fma_indirizzo' ),
		'lat'           => '' === $m( 'fma_lat' ) ? null : (float) $m( 'fma_lat' ),
		'lng'           => '' === $m( 'fma_lng' ) ? null : (float) $m( 'fma_lng' ),
		'intro'         => has_excerpt( $post ) ? $post->post_excerpt : '',
		'servizi'       => ( $servizi && ! is_wp_error( $servizi ) ) ? wp_list_pluck( $servizi, 'name' ) : array(),
		'camere'        => (array) $m( 'fma_camere' ),
		'camere_totali' => (int) $m( 'fma_camere_totali' ) ?: null,
		'prezzo_da'     => '' === $m( 'fma_prezzo_da' ) ? null : (float) $m( 'fma_prezzo_da' ),
		'tassa'         => (string) $m( 'fma_tassa' ),
		'orari'         => (string) $m( 'fma_orari' ),
		'dintorni'      => (array) $m( 'fma_dintorni' ),
		'regole'        => (array) $m( 'fma_regole' ),
		'foto'          => (int) get_post_thumbnail_id( $post ),
		'galleria'      => $galleria,
		'booking_url'   => (string) $m( 'fma_booking_url' ),
		'in_slider'     => (bool) $m( 'fma_in_slider' ),
		'contatti'      => array(
			'nome'          => (string) $m( 'fma_referente' ),
			'logo'          => (int) $m( 'fma_logo' ),
			'email'         => (string) $m( 'fma_email' ),
			'telefono'      => $tel,
			'telefono_link' => fma_link_telefono( $tel ),
			'cellulare'     => $cell && $cell !== $tel ? $cell : '',
			'whatsapp'      => $wa,
			'whatsapp_link' => fma_link_whatsapp( $wa ),
			'sito'          => (string) $m( 'fma_sito' ),
		),
	);
}

/** Tutte le strutture pubblicate, nell'ordine scelto in amministrazione ("Ordine"). */
function fma_strutture( array $args = array() ): array {
	$q = new WP_Query(
		array_merge(
			array(
				'post_type'      => 'struttura',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
				'no_found_rows'  => true,
			),
			$args
		)
	);
	return array_map( 'fma_struttura_dati', $q->posts );
}

/** Regioni con il numero di strutture, dalla più rappresentata. */
function fma_regioni(): array {
	$termini = get_terms( array( 'taxonomy' => 'regione', 'hide_empty' => true ) );
	if ( is_wp_error( $termini ) ) {
		return array();
	}
	usort(
		$termini,
		function ( $a, $b ) {
			return array( $b->count, $a->name ) <=> array( $a->count, $b->name );
		}
	);
	return $termini;
}

function fma_luogo( array $s ): string {
	if ( ! $s['localita'] ) {
		return '';
	}
	return $s['provincia'] ? "{$s['localita']} ({$s['provincia']})" : $s['localita'];
}
