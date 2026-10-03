<?php
/**
 * Tipi di contenuto e tassonomie.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'fma_registra_tipi' );

function fma_registra_tipi(): void {
	register_post_type(
		'struttura',
		array(
			'labels'        => array(
				'name'               => 'Strutture',
				'singular_name'      => 'Struttura',
				'menu_name'          => 'Strutture',
				'add_new'            => 'Aggiungi struttura',
				'add_new_item'       => 'Aggiungi una struttura',
				'edit_item'          => 'Modifica struttura',
				'new_item'           => 'Nuova struttura',
				'view_item'          => 'Vedi struttura',
				'view_items'         => 'Vedi strutture',
				'search_items'       => 'Cerca strutture',
				'not_found'          => 'Nessuna struttura trovata',
				'not_found_in_trash' => 'Nessuna struttura nel cestino',
				'all_items'          => 'Tutte le strutture',
				'featured_image'     => 'Foto principale',
				'set_featured_image' => 'Scegli la foto principale',
				'remove_featured_image' => 'Rimuovi la foto principale',
				'use_featured_image' => 'Usa come foto principale',
				'item_published'     => 'Struttura pubblicata.',
				'item_updated'       => 'Struttura aggiornata.',
			),
			'description'   => 'Case per ferie e strutture ricettive della rete.',
			'public'        => true,
			'has_archive'   => 'strutture',
			'rewrite'       => array( 'slug' => 'strutture', 'with_front' => false ),
			'menu_icon'     => 'dashicons-admin-home',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
			'show_in_rest'  => true,
			'template'      => array(
				array( 'core/heading', array( 'placeholder' => 'Titolo della sezione (es. La storia)' ) ),
				array( 'core/paragraph', array( 'placeholder' => 'Racconta la casa: storia, spazi, spirito dell’accoglienza…' ) ),
			),
		)
	);

	register_post_type(
		'partner',
		array(
			'labels'       => array(
				'name'               => 'Partner',
				'singular_name'      => 'Partner',
				'add_new'            => 'Aggiungi partner',
				'add_new_item'       => 'Aggiungi un partner',
				'edit_item'          => 'Modifica partner',
				'all_items'          => 'Tutti i partner',
				'featured_image'     => 'Logo',
				'set_featured_image' => 'Scegli il logo',
				'remove_featured_image' => 'Rimuovi il logo',
				'not_found'          => 'Nessun partner',
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-groups',
			'menu_position' => 6,
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'regione',
		'struttura',
		array(
			'labels'            => array(
				'name'          => 'Regioni',
				'singular_name' => 'Regione',
				'add_new_item'  => 'Aggiungi regione',
				'search_items'  => 'Cerca regioni',
				'all_items'     => 'Tutte le regioni',
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'regione', 'with_front' => false ),
		)
	);

	register_taxonomy(
		'servizio',
		'struttura',
		array(
			'labels'            => array(
				'name'          => 'Servizi',
				'singular_name' => 'Servizio',
				'add_new_item'  => 'Aggiungi servizio',
				'search_items'  => 'Cerca servizi',
				'all_items'     => 'Tutti i servizi',
			),
			'hierarchical'      => true, // caselle di spunta nell'editor
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => false,
		)
	);
}
