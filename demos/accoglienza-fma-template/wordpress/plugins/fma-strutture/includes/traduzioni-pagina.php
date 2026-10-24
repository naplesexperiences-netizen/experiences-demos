<?php
/**
 * Strumenti → Traduzioni FMA: importa le traduzioni dei contenuti (case, articoli, pagine,
 * categorie, regioni, servizi) un passo alla volta, come l'importazione dei contenuti.
 */

defined( 'ABSPATH' ) || exit;

const FMA_TRADUZIONI_STATO = 'fma_traduzioni_stato';

add_action( 'admin_menu', 'fma_traduzioni_menu' );
add_action( 'admin_post_fma_traduzioni_avvia', 'fma_traduzioni_avvia' );
add_action( 'admin_post_fma_traduzioni_annulla', 'fma_traduzioni_annulla' );
add_action( 'wp_ajax_fma_traduzioni_passo', 'fma_traduzioni_passo' );

function fma_traduzioni_menu(): void {
	$pagina = add_management_page( 'Traduzioni FMA', 'Traduzioni FMA', 'manage_options', 'fma-traduzioni', 'fma_traduzioni_pagina' );
	add_action( "admin_print_scripts-$pagina", 'fma_traduzioni_asset' );
}

function fma_traduzioni_url( array $args = array() ): string {
	return add_query_arg( $args, admin_url( 'tools.php?page=fma-traduzioni' ) );
}

function fma_traduzioni_asset(): void {
	wp_enqueue_style( 'fma-admin', FMA_STRUTTURE_URL . 'assets/admin.css', array(), FMA_STRUTTURE_VERSIONE );
	if ( get_option( FMA_TRADUZIONI_STATO ) ) {
		wp_enqueue_script( 'fma-importa', FMA_STRUTTURE_URL . 'assets/importa.js', array(), FMA_STRUTTURE_VERSIONE, true );
		wp_add_inline_script(
			'fma-importa',
			'window.fmaImporta = ' . wp_json_encode(
				array(
					'ajax'   => admin_url( 'admin-ajax.php' ),
					'azione' => 'fma_traduzioni_passo',
					'nonce'  => wp_create_nonce( 'fma_traduzioni_passo' ),
					'fine'   => fma_traduzioni_url( array( 'esito' => 'ok' ) ),
				)
			) . ';',
			'before'
		);
	}
}

function fma_traduzioni_avvia(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Non hai i permessi per importare le traduzioni.', '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'fma_traduzioni_avvia' );
	$scelte = array_map( 'sanitize_key', (array) ( $_POST['lingue'] ?? array() ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- pulito qui.
	$lingue = array_values( array_intersect( FMA_Traduttore::lingue(), $scelte ) );
	if ( ! $lingue ) {
		wp_safe_redirect( fma_traduzioni_url( array( 'esito' => 'nessuna' ) ) );
		exit;
	}
	update_option( FMA_TRADUZIONI_STATO, array( 'passi' => FMA_Traduttore::passi( $lingue ), 'fatti' => 0, 'lingue' => $lingue ), false );
	wp_safe_redirect( fma_traduzioni_url() );
	exit;
}

function fma_traduzioni_annulla(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( '', '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'fma_traduzioni_annulla' );
	delete_option( FMA_TRADUZIONI_STATO );
	delete_transient( 'fma_traduzioni_blocco' );
	wp_safe_redirect( fma_traduzioni_url( array( 'esito' => 'annullato' ) ) );
	exit;
}

function fma_traduzioni_passo(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'messaggio' => 'Non hai i permessi per importare le traduzioni.' ), 403 );
	}
	check_ajax_referer( 'fma_traduzioni_passo', 'nonce' );
	$stato = get_option( FMA_TRADUZIONI_STATO );
	if ( ! is_array( $stato ) || empty( $stato['passi'] ) ) {
		wp_send_json_error( array( 'messaggio' => 'Nessuna importazione in corso: ricarica la pagina.' ), 409 );
	}
	if ( get_transient( 'fma_traduzioni_blocco' ) ) {
		wp_send_json_error( array( 'messaggio' => 'Un passo è ancora in corso, o si è interrotto da poco: riprova tra qualche minuto.' ), 409 );
	}
	set_transient( 'fma_traduzioni_blocco', 1, 330 );
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors -- alcuni hosting lo vietano.
	}

	$log   = array();
	$passo = $stato['passi'][ $stato['fatti'] ];
	try {
		( new FMA_Traduttore(
			function ( string $messaggio, string $tipo ) use ( &$log ) {
				$log[] = array( 'tipo' => $tipo, 'testo' => $messaggio );
			}
		) )->esegui( $passo );
	} catch ( Throwable $e ) {
		delete_transient( 'fma_traduzioni_blocco' );
		wp_send_json_error( array( 'messaggio' => $passo[3] . ': ' . $e->getMessage(), 'log' => $log ), 500 );
	}

	++$stato['fatti'];
	$finito = $stato['fatti'] >= count( $stato['passi'] );
	if ( $finito ) {
		delete_option( FMA_TRADUZIONI_STATO );
		set_transient( 'fma_traduzioni_esito_' . get_current_user_id(), $stato['lingue'], HOUR_IN_SECONDS );
		FMA_Traduttore::concludi();
	} else {
		update_option( FMA_TRADUZIONI_STATO, $stato, false );
	}
	delete_transient( 'fma_traduzioni_blocco' );

	wp_send_json_success(
		array(
			'fatti'     => $stato['fatti'],
			'totale'    => count( $stato['passi'] ),
			'etichetta' => $passo[3],
			'prossimo'  => $finito ? '' : $stato['passi'][ $stato['fatti'] ][3],
			'log'       => $log,
			'finito'    => $finito,
		)
	);
}

function fma_traduzioni_pagina(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$stato    = get_option( FMA_TRADUZIONI_STATO );
	$esito    = isset( $_GET['esito'] ) ? sanitize_key( wp_unslash( $_GET['esito'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$fatte    = 'ok' === $esito ? get_transient( 'fma_traduzioni_esito_' . get_current_user_id() ) : false;
	$problema = FMA_Traduttore::problema();
	?>
	<div class="wrap fma-importa">
		<h1>Traduzioni FMA</h1>

		<?php if ( is_array( $fatte ) ) : ?>
			<div class="notice notice-success"><p><strong>Traduzioni importate</strong> (<?php echo esc_html( strtoupper( implode( ', ', $fatte ) ) ); ?>). Il selettore della lingua è in alto a destra nel sito.</p>
				<p><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Vedi il sito</a></p></div>
		<?php elseif ( 'annullato' === $esito ) : ?>
			<div class="notice notice-info"><p>Importazione annullata. Le traduzioni già create restano; puoi rilanciare quando vuoi.</p></div>
		<?php elseif ( 'nessuna' === $esito ) : ?>
			<div class="notice notice-warning"><p>Scegli almeno una lingua.</p></div>
		<?php endif; ?>

		<?php if ( $problema ) : ?>
			<div class="notice notice-error inline"><p><?php echo esc_html( $problema ); ?></p></div>
		<?php elseif ( is_array( $stato ) ) : ?>
			<?php $totale = count( $stato['passi'] ); ?>
			<div class="fma-importa__corso" data-fma-importa>
				<h2>Importazione in corso</h2>
				<p>Lascia aperta questa pagina finché la barra non arriva in fondo.</p>
				<progress max="<?php echo (int) $totale; ?>" value="<?php echo (int) $stato['fatti']; ?>" data-barra></progress>
				<p class="fma-importa__stato" aria-live="polite" data-stato><?php echo esc_html( sprintf( 'Passo %d di %d: %s', $stato['fatti'] + 1, $totale, $stato['passi'][ $stato['fatti'] ][3] ) ); ?></p>
				<p class="notice notice-error inline fma-importa__errore" hidden data-errore></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="fma-importa__azioni">
					<button type="button" class="button button-primary" hidden data-riprova>Riprova da qui</button>
					<input type="hidden" name="action" value="fma_traduzioni_annulla">
					<?php wp_nonce_field( 'fma_traduzioni_annulla' ); ?>
					<button type="submit" class="button-link button-link-delete">Annulla</button>
				</form>
				<ol class="fma-importa__log" data-log></ol>
			</div>
		<?php else : ?>
			<?php
			$lingue   = FMA_Traduttore::lingue();
			$mancanti = FMA_Traduttore::lingue_mancanti();
			?>
			<p>Crea le versioni tradotte delle case, degli articoli, delle pagine Home e Blog, delle categorie, delle regioni e dei servizi, collegate a quelle italiane.
				I contenuti italiani non vengono toccati. Si può rilanciare: le traduzioni già presenti si aggiornano con i testi del plugin, quindi <strong>le modifiche fatte a mano alle traduzioni verrebbero sovrascritte</strong>.</p>
			<?php if ( ! empty( PLL()->options['force_lang'] ) && 1 === (int) PLL()->options['force_lang'] && empty( PLL()->options['redirect_lang'] ) ) : ?>
				<p class="notice notice-info inline"><span>Consiglio: in <a href="<?php echo esc_url( admin_url( 'admin.php?page=mlang_settings' ) ); ?>">Lingue → Impostazioni → Modifiche URL</a> spunta «L’URL della pagina iniziale contiene il codice della lingua invece del nome o dell’ID della pagina». Così la home inglese è <code><?php echo esc_html( home_url( '/en/' ) ); ?></code> e non <code>…/en/home-en/</code>.</span></p>
			<?php endif; ?>
			<?php if ( $mancanti ) : ?>
				<p class="notice notice-warning inline"><span>Ci sono traduzioni per lingue non ancora aggiunte in Polylang: <?php echo esc_html( strtoupper( implode( ', ', $mancanti ) ) ); ?>. Aggiungile in <a href="<?php echo esc_url( admin_url( 'admin.php?page=mlang' ) ); ?>">Lingue → Lingue</a>.</span></p>
			<?php endif; ?>
			<?php if ( $lingue ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="fma_traduzioni_avvia">
					<?php wp_nonce_field( 'fma_traduzioni_avvia' ); ?>
					<fieldset>
						<legend class="screen-reader-text">Lingue da importare</legend>
						<?php foreach ( $lingue as $l ) : ?>
							<?php $d = FMA_Traduttore::dati( $l ); ?>
							<p><label><input type="checkbox" name="lingue[]" value="<?php echo esc_attr( $l ); ?>" checked>
								<?php echo esc_html( sprintf( '%s — %d case, %d articoli', $d['nome'] ?? strtoupper( $l ), count( $d['strutture'] ?? array() ), count( $d['articoli'] ?? array() ) ) ); ?></label></p>
						<?php endforeach; ?>
					</fieldset>
					<p><button type="submit" class="button button-primary">Importa le traduzioni</button></p>
				</form>
			<?php else : ?>
				<p>Nessuna lingua da importare: aggiungi le lingue in Polylang (Lingue → Lingue).</p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php
}
