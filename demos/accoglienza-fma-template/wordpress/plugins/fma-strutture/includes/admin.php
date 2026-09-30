<?php
/**
 * Schede di modifica in amministrazione, colonne e avvisi.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes_struttura', 'fma_admin_box_struttura' );
add_action( 'add_meta_boxes_post', 'fma_admin_box_articolo' );
add_action( 'add_meta_boxes_partner', 'fma_admin_box_partner' );
add_action( 'save_post_struttura', 'fma_admin_salva_struttura', 10, 2 );
add_action( 'save_post_post', 'fma_admin_salva_articolo', 10, 2 );
add_action( 'save_post_partner', 'fma_admin_salva_partner', 10, 2 );
add_action( 'admin_enqueue_scripts', 'fma_admin_asset' );

function fma_admin_asset( string $pagina ): void {
	$schermo = get_current_screen();
	if ( ! in_array( $pagina, array( 'post.php', 'post-new.php' ), true ) || ! $schermo || 'struttura' !== $schermo->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_style( 'fma-admin', FMA_STRUTTURE_URL . 'assets/admin.css', array(), FMA_STRUTTURE_VERSIONE );
	wp_enqueue_script( 'fma-admin', FMA_STRUTTURE_URL . 'assets/admin.js', array( 'jquery', 'jquery-ui-sortable' ), FMA_STRUTTURE_VERSIONE, true );
}

function fma_admin_box_struttura(): void {
	add_meta_box( 'fma-scheda', 'Scheda della struttura', 'fma_admin_render_scheda', 'struttura', 'normal', 'high' );
	add_meta_box( 'fma-contatti', 'Contatti e richieste di soggiorno', 'fma_admin_render_contatti', 'struttura', 'normal', 'high' );
	add_meta_box( 'fma-camere', 'Camere', 'fma_admin_render_camere', 'struttura', 'normal', 'default' );
	add_meta_box( 'fma-galleria', 'Galleria foto', 'fma_admin_render_galleria', 'struttura', 'normal', 'default' );
	add_meta_box( 'fma-dintorni', 'Nei dintorni e “Da sapere”', 'fma_admin_render_dintorni', 'struttura', 'normal', 'default' );
	add_meta_box( 'fma-home', 'Home page', 'fma_admin_render_home', 'struttura', 'side', 'default' );
}

function fma_admin_campo( WP_Post $post, string $chiave ): void {
	$campo  = fma_campi_struttura()[ $chiave ];
	$valore = get_post_meta( $post->ID, $chiave, true );
	$tipo   = array( 'email' => 'email', 'url' => 'url', 'number' => 'number', 'integer' => 'number' )[ $campo['tipo'] ] ?? 'text';
	$extra  = 'number' === $campo['tipo'] ? ' step="any"' : ( 'integer' === $campo['tipo'] ? ' step="1" min="0"' : '' );
	$extra .= isset( $campo['max'] ) ? ' maxlength="' . (int) $campo['max'] . '"' : '';
	$id     = 'fma-' . $chiave;
	?>
	<p class="fma-campo">
		<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $campo['etichetta'] ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $id ); ?>" name="fma[<?php echo esc_attr( $chiave ); ?>]" type="<?php echo esc_attr( $tipo ); ?>" value="<?php echo esc_attr( (string) $valore ); ?>"<?php echo $extra; // phpcs:ignore ?>>
		<?php if ( ! empty( $campo['aiuto'] ) ) : ?>
			<span class="description"><?php echo esc_html( $campo['aiuto'] ); ?></span>
		<?php endif; ?>
	</p>
	<?php
}

function fma_admin_render_scheda( WP_Post $post ): void {
	wp_nonce_field( 'fma_salva_struttura', 'fma_nonce' );
	echo '<p class="description">L’introduzione della pagina è il <strong>Riassunto</strong> (pannello a destra); le sezioni descrittive (storia, spazi…) si scrivono nell’editor, ognuna con un titolo.</p>';
	echo '<div class="fma-griglia">';
	foreach ( array( 'fma_tipo', 'fma_titolo_hero', 'fma_localita', 'fma_provincia', 'fma_indirizzo', 'fma_camere_totali', 'fma_lat', 'fma_lng', 'fma_prezzo_da', 'fma_tassa', 'fma_orari', 'fma_booking_url' ) as $k ) {
		fma_admin_campo( $post, $k );
	}
	echo '</div>';
}

function fma_admin_render_contatti( WP_Post $post ): void {
	$logo = (int) get_post_meta( $post->ID, 'fma_logo', true );
	echo '<div class="fma-griglia">';
	foreach ( array( 'fma_email', 'fma_referente', 'fma_telefono', 'fma_cellulare', 'fma_whatsapp', 'fma_sito' ) as $k ) {
		fma_admin_campo( $post, $k );
	}
	?>
	<div class="fma-campo fma-media" data-fma-media>
		<label>Logo della struttura</label>
		<span class="fma-media__anteprima" data-anteprima><?php echo $logo ? wp_get_attachment_image( $logo, 'thumbnail' ) : ''; ?></span>
		<input type="hidden" name="fma[fma_logo]" value="<?php echo $logo ? (int) $logo : ''; ?>" data-valore>
		<button type="button" class="button" data-scegli>Scegli il logo</button>
		<button type="button" class="button-link fma-rimuovi" data-togli<?php echo $logo ? '' : ' hidden'; ?>>Rimuovi</button>
	</div>
	<?php
	echo '</div>';
}

/** Riga di un campo ripetibile; con $riga null stampa il modello vuoto usato dal JavaScript. */
function fma_admin_riga( string $gruppo, array $campi, ?array $riga, string $indice ): void {
	?>
	<div class="fma-riga" data-riga>
		<span class="fma-riga__maniglia dashicons dashicons-menu" aria-hidden="true" title="Trascina per riordinare"></span>
		<div class="fma-riga__campi">
			<?php foreach ( $campi as $chiave => $def ) : ?>
				<?php
				$nome   = "fma[{$gruppo}][{$indice}][{$chiave}]";
				$valore = $riga[ $chiave ] ?? '';
				?>
				<?php if ( 'immagine' === $def['tipo'] ) : ?>
					<div class="fma-campo fma-media" data-fma-media>
						<label><?php echo esc_html( $def['etichetta'] ); ?></label>
						<span class="fma-media__anteprima" data-anteprima><?php echo $valore ? wp_get_attachment_image( (int) $valore, 'thumbnail' ) : ''; ?></span>
						<input type="hidden" name="<?php echo esc_attr( $nome ); ?>" value="<?php echo esc_attr( (string) $valore ); ?>" data-valore>
						<button type="button" class="button" data-scegli>Scegli foto</button>
						<button type="button" class="button-link fma-rimuovi" data-togli<?php echo $valore ? '' : ' hidden'; ?>>Rimuovi</button>
					</div>
				<?php elseif ( 'textarea' === $def['tipo'] ) : ?>
					<p class="fma-campo fma-campo--largo"><label><?php echo esc_html( $def['etichetta'] ); ?><textarea class="widefat" rows="2" name="<?php echo esc_attr( $nome ); ?>"><?php echo esc_textarea( (string) $valore ); ?></textarea></label></p>
				<?php else : ?>
					<p class="fma-campo"><label><?php echo esc_html( $def['etichetta'] ); ?><input class="widefat" type="<?php echo 'number' === $def['tipo'] ? 'number' : 'text'; ?>"<?php echo 'number' === $def['tipo'] ? ' min="0" step="1"' : ''; ?> name="<?php echo esc_attr( $nome ); ?>" value="<?php echo esc_attr( (string) $valore ); ?>"<?php echo ! empty( $def['segnaposto'] ) ? ' placeholder="' . esc_attr( $def['segnaposto'] ) . '"' : ''; ?>></label></p>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button-link fma-rimuovi" data-rimuovi-riga>Elimina</button>
	</div>
	<?php
}

function fma_admin_ripetibile( WP_Post $post, string $gruppo, array $campi, string $aggiungi ): void {
	$righe = (array) get_post_meta( $post->ID, $gruppo, true );
	?>
	<div class="fma-ripetibile" data-ripetibile="<?php echo esc_attr( $gruppo ); ?>">
		<input type="hidden" name="fma[<?php echo esc_attr( $gruppo ); ?>][__presente]" value="1">
		<div class="fma-righe" data-righe>
			<?php
			foreach ( array_values( $righe ) as $i => $riga ) {
				fma_admin_riga( $gruppo, $campi, (array) $riga, (string) $i );
			}
			?>
		</div>
		<template data-modello><?php fma_admin_riga( $gruppo, $campi, null, '__i__' ); ?></template>
		<button type="button" class="button" data-aggiungi-riga><?php echo esc_html( $aggiungi ); ?></button>
	</div>
	<?php
}

function fma_admin_render_camere( WP_Post $post ): void {
	echo '<p class="description">Una riga per tipologia. Se non ci sono tipologie, la pagina invita a chiedere alla casa. Le camere compaiono anche nel modulo di richiesta.</p>';
	fma_admin_ripetibile(
		$post,
		'fma_camere',
		array(
			'nome'      => array( 'tipo' => 'text', 'etichetta' => 'Tipologia', 'segnaposto' => 'Camera doppia' ),
			'letti'     => array( 'tipo' => 'text', 'etichetta' => 'Letti', 'segnaposto' => 'Matrimoniale o 2 singoli' ),
			'ospiti'    => array( 'tipo' => 'number', 'etichetta' => 'Ospiti max' ),
			'quante'    => array( 'tipo' => 'number', 'etichetta' => 'Quante camere' ),
			'dettaglio' => array( 'tipo' => 'textarea', 'etichetta' => 'Descrizione breve' ),
			'immagine'  => array( 'tipo' => 'immagine', 'etichetta' => 'Foto' ),
		),
		'Aggiungi tipologia'
	);
}

function fma_admin_render_dintorni( WP_Post $post ): void {
	echo '<h4>Nei dintorni</h4>';
	fma_admin_ripetibile(
		$post,
		'fma_dintorni',
		array(
			'luogo'    => array( 'tipo' => 'text', 'etichetta' => 'Luogo', 'segnaposto' => 'Scavi di Pompei' ),
			'distanza' => array( 'tipo' => 'text', 'etichetta' => 'Distanza', 'segnaposto' => '10 minuti in auto' ),
		),
		'Aggiungi luogo'
	);
	$regole = implode( "\n", (array) get_post_meta( $post->ID, 'fma_regole', true ) );
	?>
	<h4><label for="fma-regole">Da sapere</label></h4>
	<textarea class="widefat" id="fma-regole" name="fma[fma_regole]" rows="6" placeholder="Check-in dalle 14:00 alle 21:00"><?php echo esc_textarea( $regole ); ?></textarea>
	<p class="description">Una regola o informazione per riga (orari, animali, cancellazione…).</p>
	<?php
}

function fma_admin_render_galleria( WP_Post $post ): void {
	$ids = (array) get_post_meta( $post->ID, 'fma_galleria', true );
	?>
	<div class="fma-galleria" data-galleria>
		<p class="description">La foto principale (pannello a destra) apre la galleria; qui aggiungi le altre. Trascina per cambiare l’ordine.</p>
		<ul class="fma-galleria__lista" data-lista>
			<?php foreach ( $ids as $id ) : ?>
				<?php if ( wp_attachment_is_image( $id ) ) : ?>
					<li data-id="<?php echo (int) $id; ?>"><?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?><button type="button" class="fma-galleria__via" aria-label="Rimuovi foto">×</button></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" name="fma[fma_galleria]" value="<?php echo esc_attr( implode( ',', array_map( 'absint', $ids ) ) ); ?>" data-valore>
		<button type="button" class="button" data-aggiungi-foto>Aggiungi foto</button>
	</div>
	<?php
}

function fma_admin_render_home( WP_Post $post ): void {
	$attivo = (bool) get_post_meta( $post->ID, 'fma_in_slider', true );
	?>
	<input type="hidden" name="fma[fma_in_slider]" value="0">
	<label><input type="checkbox" name="fma[fma_in_slider]" value="1"<?php checked( $attivo ); ?>> Mostra nello slider della home</label>
	<p class="description">Lo slider usa la foto principale e il “Titolo nello slider”. L’ordine segue il campo <em>Ordine</em> in “Attributi”.</p>
	<?php
}

function fma_admin_salva_struttura( int $post_id, WP_Post $post ): void {
	if ( ! isset( $_POST['fma_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['fma_nonce'] ) ), 'fma_salva_struttura' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$dati = isset( $_POST['fma'] ) && is_array( $_POST['fma'] ) ? wp_unslash( $_POST['fma'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- pulito campo per campo sotto.

	foreach ( fma_campi_struttura() as $chiave => $campo ) {
		if ( ! array_key_exists( $chiave, $dati ) ) {
			continue;
		}
		$valore = fma_pulisci_valore( $dati[ $chiave ], $campo['tipo'] );
		if ( '' === $valore || ( 'boolean' === $campo['tipo'] && ! $valore ) ) {
			delete_post_meta( $post_id, $chiave );
		} else {
			update_post_meta( $post_id, $chiave, $valore );
		}
	}
	foreach ( fma_campi_ripetibili() as $gruppo => $schema ) {
		if ( ! isset( $dati[ $gruppo ]['__presente'] ) ) {
			continue;
		}
		unset( $dati[ $gruppo ]['__presente'], $dati[ $gruppo ]['__i__'] );
		update_post_meta( $post_id, $gruppo, fma_pulisci_righe( $dati[ $gruppo ], $schema ) );
	}
	if ( array_key_exists( 'fma_regole', $dati ) ) {
		update_post_meta( $post_id, 'fma_regole', fma_pulisci_lista_testo( $dati['fma_regole'] ) );
	}
	if ( array_key_exists( 'fma_galleria', $dati ) ) {
		update_post_meta( $post_id, 'fma_galleria', fma_pulisci_id_lista( $dati['fma_galleria'] ) );
	}
}

/* ---------------------------------------------------------------- articoli: casa collegata */

function fma_admin_box_articolo(): void {
	add_meta_box( 'fma-vicina', 'Casa collegata', 'fma_admin_render_articolo', 'post', 'side', 'default' );
}

function fma_admin_render_articolo( WP_Post $post ): void {
	wp_nonce_field( 'fma_salva_articolo', 'fma_nonce_articolo' );
	$scelta    = (int) get_post_meta( $post->ID, 'fma_struttura_vicina', true );
	$strutture = get_posts( array( 'post_type' => 'struttura', 'post_status' => 'publish', 'numberposts' => 100, 'orderby' => 'title', 'order' => 'ASC' ) );
	?>
	<label class="screen-reader-text" for="fma-vicina-sel">Casa collegata</label>
	<select id="fma-vicina-sel" name="fma_struttura_vicina" class="widefat">
		<option value="0">— Nessuna —</option>
		<?php foreach ( $strutture as $s ) : ?>
			<option value="<?php echo (int) $s->ID; ?>"<?php selected( $scelta, $s->ID ); ?>><?php echo esc_html( get_the_title( $s ) ); ?></option>
		<?php endforeach; ?>
	</select>
	<p class="description">In fondo all’articolo compare il box “Dormi vicino” con questa casa.</p>
	<?php
}

function fma_admin_salva_articolo( int $post_id ): void {
	if ( ! isset( $_POST['fma_nonce_articolo'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['fma_nonce_articolo'] ) ), 'fma_salva_articolo' ) ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$id = isset( $_POST['fma_struttura_vicina'] ) ? absint( $_POST['fma_struttura_vicina'] ) : 0;
	if ( $id && 'struttura' === get_post_type( $id ) ) {
		update_post_meta( $post_id, 'fma_struttura_vicina', $id );
	} else {
		delete_post_meta( $post_id, 'fma_struttura_vicina' );
	}
}

/* ---------------------------------------------------------------- partner */

function fma_admin_box_partner(): void {
	add_meta_box( 'fma-partner-url', 'Sito del partner', 'fma_admin_render_partner', 'partner', 'normal', 'high' );
}

function fma_admin_render_partner( WP_Post $post ): void {
	wp_nonce_field( 'fma_salva_partner', 'fma_nonce_partner' );
	?>
	<p><label for="fma-url">Indirizzo del sito (facoltativo)</label>
	<input class="widefat" type="url" id="fma-url" name="fma_url" value="<?php echo esc_attr( (string) get_post_meta( $post->ID, 'fma_url', true ) ); ?>" placeholder="https://"></p>
	<p class="description">Il logo è l’immagine in evidenza. Nella home i loghi scorrono nell’ordine indicato in “Attributi”.</p>
	<?php
}

function fma_admin_salva_partner( int $post_id ): void {
	if ( ! isset( $_POST['fma_nonce_partner'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['fma_nonce_partner'] ) ), 'fma_salva_partner' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$url = isset( $_POST['fma_url'] ) ? esc_url_raw( wp_unslash( $_POST['fma_url'] ) ) : '';
	if ( $url ) {
		update_post_meta( $post_id, 'fma_url', $url );
	} else {
		delete_post_meta( $post_id, 'fma_url' );
	}
}

/* ---------------------------------------------------------------- elenco strutture: colonne e avvisi */

add_filter(
	'manage_struttura_posts_columns',
	function ( array $colonne ): array {
		$nuove = array();
		foreach ( $colonne as $k => $v ) {
			$nuove[ $k ] = $v;
			if ( 'title' === $k ) {
				$nuove['fma_luogo'] = 'Località';
				$nuove['fma_email'] = 'Email richieste';
				$nuove['fma_slider'] = 'Slider';
			}
		}
		return $nuove;
	}
);

add_action(
	'manage_struttura_posts_custom_column',
	function ( string $colonna, int $post_id ): void {
		if ( 'fma_luogo' === $colonna ) {
			echo esc_html( trim( get_post_meta( $post_id, 'fma_localita', true ) . ' ' . ( get_post_meta( $post_id, 'fma_provincia', true ) ? '(' . get_post_meta( $post_id, 'fma_provincia', true ) . ')' : '' ) ) );
		}
		if ( 'fma_email' === $colonna ) {
			$email = function_exists( 'fma_richieste_destinatario' ) ? fma_richieste_destinatario( $post_id ) : (string) get_post_meta( $post_id, 'fma_email', true );
			echo $email ? esc_html( $email ) : '<strong class="fma-manca">Manca: le richieste non partono</strong>';
		}
		if ( 'fma_slider' === $colonna ) {
			echo get_post_meta( $post_id, 'fma_in_slider', true ) ? '<span class="dashicons dashicons-yes" aria-label="Sì"></span>' : '<span aria-label="No">—</span>';
		}
	},
	10,
	2
);

add_action(
	'admin_notices',
	function (): void {
		$schermo = get_current_screen();
		if ( ! $schermo || 'struttura' !== $schermo->post_type ) {
			return;
		}
		if ( 'post' === $schermo->base ) {
			global $post;
			if ( $post && 'auto-draft' !== $post->post_status && ! is_email( (string) get_post_meta( $post->ID, 'fma_email', true ) )
				&& ! ( function_exists( 'fma_richieste_destinatario' ) && fma_richieste_destinatario( $post->ID ) ) ) {
				echo '<div class="notice notice-warning"><p><strong>Manca l’email per le richieste di soggiorno.</strong> Senza email il modulo di questa struttura non può inviare richieste: compilala in “Contatti e richieste di soggiorno”.</p></div>';
			}
		}
	}
);

add_action(
	'admin_head-edit.php',
	function (): void {
		echo '<style>.fma-manca{color:#b32d2e}.column-fma_slider{width:4rem}</style>';
	}
);
