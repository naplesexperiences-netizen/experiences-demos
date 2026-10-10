<?php
/**
 * Recensioni degli ospiti: solo recensioni reali, ognuna con la fonte e il link all'originale.
 * Il tema le mostra in home e nella pagina della struttura collegata; se non ce ne sono, la sezione non compare.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'fma_recensioni_registra' );

/** Fonti accettate, nell'ordine del menu a tendina. */
function fma_recensioni_fonti(): array {
	return array(
		'booking'     => 'Booking.com',
		'google'      => 'Google',
		'tripadvisor' => 'Tripadvisor',
		'diretta'     => 'Scritta alla casa',
	);
}

function fma_recensioni_registra(): void {
	register_post_type(
		'recensione',
		array(
			'labels'        => array(
				'name'          => 'Recensioni',
				'singular_name' => 'Recensione',
				'add_new'       => 'Aggiungi recensione',
				'add_new_item'  => 'Aggiungi una recensione',
				'edit_item'     => 'Modifica recensione',
				'all_items'     => 'Tutte le recensioni',
				'not_found'     => 'Nessuna recensione',
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => 'edit.php?post_type=struttura',
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor' ),
			'template'      => array( array( 'core/paragraph', array( 'placeholder' => 'Il testo della recensione, così come l’ha scritto l’ospite.' ) ) ),
		)
	);

	$permesso = function ( $consenti, $meta_key, $post_id ) {
		return current_user_can( 'edit_post', $post_id );
	};
	$campi = array(
		'fma_struttura' => array( 'integer', 'absint' ),
		'fma_fonte'     => array( 'string', fn( $v ) => array_key_exists( (string) $v, fma_recensioni_fonti() ) ? (string) $v : '' ),
		'fma_voto'      => array( 'string', 'sanitize_text_field' ),
		'fma_periodo'   => array( 'string', 'sanitize_text_field' ),
		'fma_link'      => array( 'string', 'esc_url_raw' ),
	);
	foreach ( $campi as $chiave => list( $tipo, $pulisci ) ) {
		register_post_meta(
			'recensione',
			$chiave,
			array(
				'type'              => $tipo,
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $pulisci,
				'auth_callback'     => $permesso,
			)
		);
	}
}

/**
 * Recensioni pubblicate, dalla più recente.
 *
 * @param int $struttura 0 = di tutte le case.
 * @return array<int,array{nome:string,testo:string,casa:string,casa_url:string,fonte:string,voto:string,periodo:string,link:string}>
 */
function fma_recensioni( int $struttura = 0, int $quante = 3 ): array {
	$args = array(
		'post_type'      => 'recensione',
		'post_status'    => 'publish',
		'posts_per_page' => $quante,
		'no_found_rows'  => true,
	);
	if ( $struttura ) {
		$args['meta_query'] = array( array( 'key' => 'fma_struttura', 'value' => $struttura, 'type' => 'NUMERIC' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}
	$fonti = fma_recensioni_fonti();
	$out   = array();
	foreach ( ( new WP_Query( $args ) )->posts as $r ) {
		$casa  = (int) get_post_meta( $r->ID, 'fma_struttura', true );
		$casa  = $casa && 'publish' === get_post_status( $casa ) ? $casa : 0;
		$testo = trim( wp_strip_all_tags( $r->post_content ) );
		if ( '' === $testo ) {
			continue;
		}
		$out[] = array(
			'nome'     => get_the_title( $r ),
			'testo'    => $testo,
			'casa'     => $casa ? get_the_title( $casa ) : '',
			'casa_url' => $casa ? (string) get_permalink( $casa ) : '',
			'fonte'    => $fonti[ (string) get_post_meta( $r->ID, 'fma_fonte', true ) ] ?? '',
			'voto'     => (string) get_post_meta( $r->ID, 'fma_voto', true ),
			'periodo'  => (string) get_post_meta( $r->ID, 'fma_periodo', true ),
			'link'     => (string) get_post_meta( $r->ID, 'fma_link', true ),
		);
	}
	return $out;
}

/* ---------------------------------------------------------------- amministrazione */

if ( is_admin() ) {
	add_action( 'add_meta_boxes_recensione', 'fma_recensioni_box' );
	add_action( 'save_post_recensione', 'fma_recensioni_salva', 10, 2 );
	add_filter( 'enter_title_here', fn( $testo, $post ) => 'recensione' === $post->post_type ? 'Nome dell’ospite, come appare nella recensione' : $testo, 10, 2 );
	add_filter( 'manage_recensione_posts_columns', 'fma_recensioni_colonne' );
	add_action( 'manage_recensione_posts_custom_column', 'fma_recensioni_colonna', 10, 2 );
}

function fma_recensioni_box(): void {
	add_meta_box( 'fma-recensione', 'Dettagli della recensione', 'fma_recensioni_render', 'recensione', 'normal', 'high' );
}

function fma_recensioni_render( WP_Post $post ): void {
	wp_nonce_field( 'fma_salva_recensione', 'fma_nonce_recensione' );
	$m         = fn( string $k ) => (string) get_post_meta( $post->ID, $k, true );
	$strutture = get_posts( array( 'post_type' => 'struttura', 'post_status' => array( 'publish', 'draft' ), 'numberposts' => 200, 'orderby' => 'title', 'order' => 'ASC' ) );
	?>
	<p class="description">Pubblica solo recensioni reali, con il testo originale e il link per verificarle. Indica solo il nome dell’ospite (per esempio «Maria»), come compare sulla fonte.</p>
	<div class="fma-griglia">
		<p class="fma-campo"><label for="fma-r-struttura">Struttura</label>
			<select class="widefat" id="fma-r-struttura" name="fma_r[fma_struttura]">
				<option value="">Scegli la casa</option>
				<?php foreach ( $strutture as $s ) : ?>
					<option value="<?php echo (int) $s->ID; ?>"<?php selected( (int) $m( 'fma_struttura' ), $s->ID ); ?>><?php echo esc_html( get_the_title( $s ) ); ?></option>
				<?php endforeach; ?>
			</select></p>
		<p class="fma-campo"><label for="fma-r-fonte">Fonte</label>
			<select class="widefat" id="fma-r-fonte" name="fma_r[fma_fonte]">
				<?php foreach ( fma_recensioni_fonti() as $k => $etichetta ) : ?>
					<option value="<?php echo esc_attr( $k ); ?>"<?php selected( $m( 'fma_fonte' ), $k ); ?>><?php echo esc_html( $etichetta ); ?></option>
				<?php endforeach; ?>
			</select></p>
		<p class="fma-campo"><label for="fma-r-voto">Voto (facoltativo)</label>
			<input class="widefat" type="text" id="fma-r-voto" name="fma_r[fma_voto]" value="<?php echo esc_attr( $m( 'fma_voto' ) ); ?>" placeholder="9,6/10 oppure 5/5"></p>
		<p class="fma-campo"><label for="fma-r-periodo">Periodo del soggiorno (facoltativo)</label>
			<input class="widefat" type="text" id="fma-r-periodo" name="fma_r[fma_periodo]" value="<?php echo esc_attr( $m( 'fma_periodo' ) ); ?>" placeholder="Agosto 2025"></p>
		<p class="fma-campo fma-campo--largo"><label for="fma-r-link">Link alla recensione originale</label>
			<input class="widefat" type="url" id="fma-r-link" name="fma_r[fma_link]" value="<?php echo esc_attr( $m( 'fma_link' ) ); ?>" placeholder="https://"></p>
	</div>
	<?php
}

function fma_recensioni_salva( int $post_id ): void {
	if ( ! isset( $_POST['fma_nonce_recensione'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['fma_nonce_recensione'] ) ), 'fma_salva_recensione' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$dati = isset( $_POST['fma_r'] ) && is_array( $_POST['fma_r'] ) ? wp_unslash( $_POST['fma_r'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- ripulito da register_post_meta.
	foreach ( array( 'fma_struttura', 'fma_fonte', 'fma_voto', 'fma_periodo', 'fma_link' ) as $k ) {
		$v = isset( $dati[ $k ] ) ? trim( (string) $dati[ $k ] ) : '';
		if ( '' === $v ) {
			delete_post_meta( $post_id, $k );
		} else {
			update_post_meta( $post_id, $k, $v );
		}
	}
}

function fma_recensioni_colonne( array $colonne ): array {
	$nuove = array();
	foreach ( $colonne as $k => $v ) {
		$nuove[ $k ] = 'title' === $k ? 'Ospite' : $v;
		if ( 'title' === $k ) {
			$nuove['fma_struttura'] = 'Struttura';
			$nuove['fma_fonte']     = 'Fonte';
		}
	}
	return $nuove;
}

function fma_recensioni_colonna( string $colonna, int $post_id ): void {
	if ( 'fma_struttura' === $colonna ) {
		$casa = (int) get_post_meta( $post_id, 'fma_struttura', true );
		echo $casa ? esc_html( get_the_title( $casa ) ) : '—';
	} elseif ( 'fma_fonte' === $colonna ) {
		$fonte = fma_recensioni_fonti()[ (string) get_post_meta( $post_id, 'fma_fonte', true ) ] ?? '—';
		$link  = (string) get_post_meta( $post_id, 'fma_link', true );
		echo $link ? '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener">' . esc_html( $fonte ) . '</a>' : esc_html( $fonte );
	}
}
