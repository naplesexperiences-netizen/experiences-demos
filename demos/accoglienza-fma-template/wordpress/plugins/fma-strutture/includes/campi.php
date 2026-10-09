<?php
/**
 * Campi delle strutture: definizione unica usata da registrazione meta, schede di modifica e importatore.
 */

defined( 'ABSPATH' ) || exit;

/** Campi semplici della struttura (tipo scalare). */
function fma_campi_struttura(): array {
	return array(
		'fma_titolo_hero'   => array( 'tipo' => 'string', 'etichetta' => 'Titolo nello slider della home', 'aiuto' => 'Facoltativo. Se vuoto nello slider compare il nome della struttura.' ),
		'fma_tipo'          => array( 'tipo' => 'string', 'etichetta' => 'Tipo di struttura', 'aiuto' => 'Es. Casa per ferie, Dimora storica.' ),
		'fma_localita'      => array( 'tipo' => 'string', 'etichetta' => 'Località' ),
		'fma_provincia'     => array( 'tipo' => 'string', 'etichetta' => 'Provincia (sigla)', 'max' => 2 ),
		'fma_indirizzo'     => array( 'tipo' => 'string', 'etichetta' => 'Indirizzo completo' ),
		'fma_lat'           => array( 'tipo' => 'number', 'etichetta' => 'Latitudine', 'aiuto' => 'Es. 40.7522. Su openstreetmap.org: tasto destro sul punto → “Mostra indirizzo”.' ),
		'fma_lng'           => array( 'tipo' => 'number', 'etichetta' => 'Longitudine', 'aiuto' => 'Es. 14.4265.' ),
		'fma_camere_totali' => array( 'tipo' => 'integer', 'etichetta' => 'Numero di camere' ),
		'fma_prezzo_da'     => array( 'tipo' => 'number', 'etichetta' => 'Prezzo minimo a notte (€)', 'aiuto' => 'Il prezzo più basso, indicativo. Vuoto: il prezzo non compare.' ),
		'fma_prezzo_unita'  => array( 'tipo' => 'string', 'etichetta' => 'Il prezzo è', 'opzioni' => array( '' => 'Non specificato (solo «a notte»)', 'persona' => 'A persona, a notte', 'camera' => 'A camera, a notte' ) ),
		'fma_tassa'         => array( 'tipo' => 'string', 'etichetta' => 'Tassa di soggiorno', 'aiuto' => 'Importo e regole, es. 2 € a persona per notte, esenti i minori di 14 anni. Compare accanto al prezzo.' ),
		'fma_orari'         => array( 'tipo' => 'string', 'etichetta' => 'Orari della reception' ),
		'fma_booking_url'   => array( 'tipo' => 'url', 'etichetta' => 'Pagina su Booking.com (facoltativa)' ),
		'fma_in_slider'     => array( 'tipo' => 'boolean', 'etichetta' => 'Mostra nello slider della home' ),
		'fma_referente'     => array( 'tipo' => 'string', 'etichetta' => 'Nome del referente o della reception' ),
		'fma_logo'          => array( 'tipo' => 'integer', 'etichetta' => 'Logo della struttura' ),
		'fma_email'         => array( 'tipo' => 'email', 'etichetta' => 'Email per le richieste di soggiorno', 'aiuto' => 'Le richieste inviate dal modulo arrivano a questo indirizzo.' ),
		'fma_telefono'      => array( 'tipo' => 'string', 'etichetta' => 'Telefono' ),
		'fma_cellulare'     => array( 'tipo' => 'string', 'etichetta' => 'Cellulare (se diverso)' ),
		'fma_whatsapp'      => array( 'tipo' => 'string', 'etichetta' => 'Numero WhatsApp' ),
		'fma_sito'          => array( 'tipo' => 'url', 'etichetta' => 'Sito della casa' ),
	);
}

/** Campi ripetibili (array). Le chiavi interne sono quelle lette da tema e plugin fma-richieste. */
function fma_campi_ripetibili(): array {
	return array(
		'fma_camere'   => array(
			'nome'      => 'string',
			'dettaglio' => 'string',
			'letti'     => 'string',
			'ospiti'    => 'integer',
			'quante'    => 'integer',
			'immagine'  => 'integer',
		),
		'fma_dintorni' => array(
			'luogo'    => 'string',
			'distanza' => 'string',
		),
	);
}

/** Valore pulito di un campo semplice; i campi a scelta accettano solo le loro opzioni. */
function fma_pulisci_campo( string $chiave, $valore ) {
	$campo  = fma_campi_struttura()[ $chiave ];
	$pulito = fma_pulisci_valore( $valore, $campo['tipo'] );
	return isset( $campo['opzioni'] ) && ! array_key_exists( (string) $pulito, $campo['opzioni'] ) ? '' : $pulito;
}

function fma_pulisci_valore( $valore, string $tipo ) {
	switch ( $tipo ) {
		case 'integer':
			return '' === $valore || null === $valore ? '' : absint( $valore );
		case 'number':
			if ( '' === $valore || null === $valore ) {
				return '';
			}
			return (float) str_replace( ',', '.', (string) $valore );
		case 'boolean':
			return (bool) $valore;
		case 'email':
			return sanitize_email( (string) $valore );
		case 'url':
			return esc_url_raw( trim( (string) $valore ) );
		default:
			return sanitize_text_field( (string) $valore );
	}
}

function fma_pulisci_righe( $righe, array $schema ): array {
	$pulite = array();
	foreach ( (array) $righe as $riga ) {
		if ( ! is_array( $riga ) ) {
			continue;
		}
		$nuova = array();
		foreach ( $schema as $chiave => $tipo ) {
			$v = $riga[ $chiave ] ?? '';
			$nuova[ $chiave ] = 'dettaglio' === $chiave ? sanitize_textarea_field( (string) $v ) : fma_pulisci_valore( $v, $tipo );
		}
		$chiave_principale = array_key_first( $schema );
		if ( '' !== (string) $nuova[ $chiave_principale ] ) {
			$pulite[] = $nuova;
		}
	}
	return $pulite;
}

function fma_pulisci_lista_testo( $valore ): array {
	if ( is_string( $valore ) ) {
		$valore = preg_split( '/\r\n|\r|\n/', $valore );
	}
	return array_values( array_filter( array_map( 'sanitize_text_field', (array) $valore ), 'strlen' ) );
}

function fma_pulisci_id_lista( $valore ): array {
	if ( is_string( $valore ) ) {
		$valore = explode( ',', $valore );
	}
	return array_values( array_filter( array_map( 'absint', (array) $valore ) ) );
}

add_action( 'init', 'fma_registra_meta', 20 );

function fma_registra_meta(): void {
	$permesso = function ( $consenti, $meta_key, $post_id ) {
		return current_user_can( 'edit_post', $post_id );
	};
	$tipi_rest = array( 'string' => 'string', 'email' => 'string', 'url' => 'string', 'integer' => 'integer', 'number' => 'number', 'boolean' => 'boolean' );

	foreach ( fma_campi_struttura() as $chiave => $campo ) {
		register_post_meta(
			'struttura',
			$chiave,
			array(
				'type'              => $tipi_rest[ $campo['tipo'] ],
				'single'            => true,
				'show_in_rest'      => 'fma_email' !== $chiave, // l'email di destinazione resta fuori dall'API pubblica
				'sanitize_callback' => function ( $v ) use ( $chiave ) {
					return fma_pulisci_campo( $chiave, $v );
				},
				'auth_callback'     => $permesso,
			)
		);
	}

	foreach ( fma_campi_ripetibili() as $chiave => $schema ) {
		$proprieta = array();
		foreach ( $schema as $k => $t ) {
			$proprieta[ $k ] = array( 'type' => $tipi_rest[ $t ] );
		}
		register_post_meta(
			'struttura',
			$chiave,
			array(
				'type'              => 'array',
				'single'            => true,
				'default'           => array(),
				'show_in_rest'      => array( 'schema' => array( 'type' => 'array', 'items' => array( 'type' => 'object', 'properties' => $proprieta ) ) ),
				'sanitize_callback' => function ( $v ) use ( $schema ) {
					return fma_pulisci_righe( $v, $schema );
				},
				'auth_callback'     => $permesso,
			)
		);
	}

	register_post_meta(
		'struttura',
		'fma_regole',
		array(
			'type'              => 'array',
			'single'            => true,
			'default'           => array(),
			'show_in_rest'      => array( 'schema' => array( 'type' => 'array', 'items' => array( 'type' => 'string' ) ) ),
			'sanitize_callback' => 'fma_pulisci_lista_testo',
			'auth_callback'     => $permesso,
		)
	);
	register_post_meta(
		'struttura',
		'fma_galleria',
		array(
			'type'              => 'array',
			'single'            => true,
			'default'           => array(),
			'show_in_rest'      => array( 'schema' => array( 'type' => 'array', 'items' => array( 'type' => 'integer' ) ) ),
			'sanitize_callback' => 'fma_pulisci_id_lista',
			'auth_callback'     => $permesso,
		)
	);

	register_post_meta(
		'partner',
		'fma_url',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'esc_url_raw',
			'auth_callback'     => $permesso,
		)
	);

	register_post_meta(
		'post',
		'fma_struttura_vicina',
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => $permesso,
		)
	);
}
