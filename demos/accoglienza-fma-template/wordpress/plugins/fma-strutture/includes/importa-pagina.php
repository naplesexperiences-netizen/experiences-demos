<?php
/**
 * Strumenti → Importa contenuti FMA: carica fma-contenuti.zip dal browser, senza WP-CLI.
 *
 * Lo zip viene estratto (solo data.json e immagini) in una cartella temporanea di uploads,
 * poi l'importazione procede a passi, una struttura per richiesta: così non supera i limiti
 * di tempo degli hosting condivisi. Se un passo fallisce si riprova da lì.
 */

defined( 'ABSPATH' ) || exit;

const FMA_IMPORTA_STATO    = 'fma_importa_stato';
const FMA_IMPORTA_PREFISSO = 'fma-importa-';

add_action( 'admin_menu', 'fma_importa_menu' );
add_action( 'admin_post_fma_importa_carica', 'fma_importa_carica' );
add_action( 'admin_post_fma_importa_annulla', 'fma_importa_annulla' );
add_action( 'wp_ajax_fma_importa_passo', 'fma_importa_passo' );
add_action( 'admin_init', 'fma_importa_registra_importatore' );

function fma_importa_menu(): void {
	$pagina = add_management_page( 'Importa contenuti FMA', 'Importa contenuti FMA', 'manage_options', 'fma-importa', 'fma_importa_pagina' );
	add_action( "admin_print_scripts-$pagina", 'fma_importa_asset' );
}

/**
 * Compare anche in Strumenti → Importa, accanto agli importatori di WordPress.
 *
 * WordPress stampa l'intestazione dell'amministrazione prima di chiamare la funzione dell'importatore:
 * lì un reindirizzamento non funziona più. Si reindirizza quindi su load-importer-*, che arriva prima,
 * e la funzione dell'importatore disegna comunque la pagina se il reindirizzamento non avviene.
 */
function fma_importa_registra_importatore(): void {
	if ( defined( 'WP_LOAD_IMPORTERS' ) && function_exists( 'register_importer' ) ) {
		register_importer( 'fma-contenuti', 'Contenuti Accoglienza FMA', 'Strutture, partner e articoli da fma-contenuti.zip.', 'fma_importa_pagina_importatore' );
	}
}

add_action(
	'load-importer-fma-contenuti',
	function (): void {
		wp_safe_redirect( admin_url( 'tools.php?page=fma-importa' ) );
		exit;
	}
);

function fma_importa_pagina_importatore(): void {
	fma_importa_asset(); // stampati nel piè di pagina: l'intestazione è già uscita
	fma_importa_pagina();
}

function fma_importa_url( array $args = array() ): string {
	return add_query_arg( $args, admin_url( 'tools.php?page=fma-importa' ) );
}

function fma_importa_asset(): void {
	wp_enqueue_style( 'fma-admin', FMA_STRUTTURE_URL . 'assets/admin.css', array(), FMA_STRUTTURE_VERSIONE );
	if ( get_option( FMA_IMPORTA_STATO ) ) {
		wp_enqueue_script( 'fma-importa', FMA_STRUTTURE_URL . 'assets/importa.js', array(), FMA_STRUTTURE_VERSIONE, true );
		wp_add_inline_script(
			'fma-importa',
			'window.fmaImporta = ' . wp_json_encode(
				array(
					'ajax'  => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'fma_importa_passo' ),
					'fine'  => fma_importa_url( array( 'esito' => 'ok' ) ),
				)
			) . ';',
			'before'
		);
	}
}

/* ---------------------------------------------------------------- cartella temporanea */

function fma_importa_base(): string {
	return wp_normalize_path( wp_upload_dir( null, false )['basedir'] );
}

/**
 * Zip caricato via FTP: in wp-content/uploads/ oppure direttamente in wp-content/.
 * Restituisce il percorso del primo trovato, o stringa vuota.
 */
function fma_importa_zip_server(): string {
	foreach ( array( fma_importa_base(), wp_normalize_path( WP_CONTENT_DIR ) ) as $cartella ) {
		$file = $cartella . '/fma-contenuti.zip';
		if ( is_file( $file ) && is_readable( $file ) ) {
			return $file;
		}
	}
	return '';
}

/** Percorso da mostrare in pagina, a partire da wp-content. */
function fma_importa_percorso_breve( string $file ): string {
	return ltrim( str_replace( wp_normalize_path( dirname( WP_CONTENT_DIR ) ), '', wp_normalize_path( $file ) ), '/' );
}

/** Cancella la cartella temporanea, solo se è davvero una nostra cartella dentro uploads. */
function fma_importa_pulisci( string $cartella ): void {
	$cartella = wp_normalize_path( $cartella );
	if ( '' === $cartella || ! is_dir( $cartella ) || dirname( $cartella ) !== fma_importa_base() || ! str_starts_with( basename( $cartella ), FMA_IMPORTA_PREFISSO ) ) {
		return;
	}
	$voci = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $cartella, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST );
	foreach ( $voci as $voce ) {
		$voce->isDir() && ! $voce->isLink() ? rmdir( $voce->getPathname() ) : unlink( $voce->getPathname() );
	}
	rmdir( $cartella );
}

/** Nome di un file nello zip senza trucchi: niente percorsi assoluti, ../, barre rovesce, byte nulli o cartelle di macOS. */
function fma_importa_nome_sicuro( string $nome ): bool {
	return '' !== $nome && '/' !== $nome[0] && ! preg_match( '#(^|/)\.\.(/|$)|\\\\|\0|^[a-z]:#i', $nome ) && ! str_contains( $nome, '__MACOSX' );
}

/**
 * Estrae dallo zip solo data.json e le immagini sotto la stessa cartella.
 * Niente PHP né altri file eseguibili finiscono in uploads; percorsi con ../ scartati.
 *
 * @return string Cartella che contiene data.json (e img/).
 */
function fma_importa_estrai( string $zip_file, string $dest ): string {
	if ( ! class_exists( 'ZipArchive' ) ) {
		throw new RuntimeException( 'Il server non ha l’estensione PHP Zip: chiedi all’hosting di attivarla, oppure usa WP-CLI.' );
	}
	$zip = new ZipArchive();
	if ( true !== $zip->open( $zip_file ) ) {
		throw new RuntimeException( 'Il file non è uno zip valido.' );
	}

	$radice = null;
	for ( $i = 0; $i < $zip->numFiles; $i++ ) {
		$nome = (string) $zip->getNameIndex( $i );
		if ( 'data.json' === basename( $nome ) && fma_importa_nome_sicuro( $nome ) && ( null === $radice || strlen( $nome ) < strlen( $radice . 'data.json' ) ) ) {
			$radice = substr( $nome, 0, -strlen( 'data.json' ) );
		}
	}
	if ( null === $radice ) {
		$zip->close();
		throw new RuntimeException( 'Nello zip manca data.json: carica fma-contenuti.zip così com’è.' );
	}

	$ammessi = array( 'webp', 'png', 'jpg', 'jpeg', 'gif' );
	$totale  = 0;
	$n       = 0;
	for ( $i = 0; $i < $zip->numFiles; $i++ ) {
		$info = $zip->statIndex( $i );
		$nome = (string) $info['name'];
		if ( ! str_starts_with( $nome, $radice ) || str_ends_with( $nome, '/' ) ) {
			continue;
		}
		$relativo = substr( $nome, strlen( $radice ) );
		$ext      = strtolower( pathinfo( $relativo, PATHINFO_EXTENSION ) );
		$valido   = 'data.json' === $relativo || ( str_starts_with( $relativo, 'img/' ) && in_array( $ext, $ammessi, true ) );
		if ( ! $valido || ! fma_importa_nome_sicuro( $nome ) ) {
			continue;
		}
		$totale += (int) $info['size'];
		if ( ++$n > 3000 || $totale > 300 * MB_IN_BYTES ) {
			$zip->close();
			throw new RuntimeException( 'Lo zip è troppo grande (oltre 3000 file o 300 MB una volta estratto).' );
		}
		$file = $dest . '/' . $relativo;
		wp_mkdir_p( dirname( $file ) );
		$contenuto = $zip->getFromIndex( $i );
		if ( false === $contenuto || false === file_put_contents( $file, $contenuto ) ) {
			$zip->close();
			throw new RuntimeException( "Non riesco a scrivere $relativo nella cartella uploads: controlla i permessi." );
		}
	}
	$zip->close();
	return $dest;
}

/* ---------------------------------------------------------------- caricamento */

function fma_importa_fallisci( string $messaggio ): void {
	set_transient( 'fma_importa_errore_' . get_current_user_id(), $messaggio, 10 * MINUTE_IN_SECONDS );
	wp_safe_redirect( fma_importa_url() );
	exit;
}

function fma_importa_carica(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Non hai i permessi per importare contenuti.', 403 );
	}
	// Oltre post_max_size PHP scarta tutta la richiesta, nonce compreso: lo diciamo chiaro invece di "link scaduto".
	if ( empty( $_POST ) && (int) ( $_SERVER['CONTENT_LENGTH'] ?? 0 ) > wp_convert_hr_to_bytes( (string) ini_get( 'post_max_size' ) ) ) {
		fma_importa_fallisci( sprintf( 'Il file supera il limite di caricamento del server (%s). Caricalo via FTP in wp-content/uploads/fma-contenuti.zip e scegli «File già sul server».', size_format( wp_max_upload_size() ) ) );
	}
	check_admin_referer( 'fma_importa_carica' );

	$sorgente = isset( $_POST['sorgente'] ) ? sanitize_key( wp_unslash( $_POST['sorgente'] ) ) : 'upload';
	if ( 'server' === $sorgente ) {
		$zip = fma_importa_zip_server();
		if ( '' === $zip ) {
			fma_importa_fallisci( 'Non trovo fma-contenuti.zip né in wp-content/uploads né in wp-content.' );
		}
	} else {
		$file = $_FILES['fma_zip'] ?? null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$err  = is_array( $file ) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
		if ( UPLOAD_ERR_INI_SIZE === $err || UPLOAD_ERR_FORM_SIZE === $err ) {
			fma_importa_fallisci( sprintf( 'Il file supera il limite di caricamento del server (%s). Caricalo via FTP in wp-content/uploads/fma-contenuti.zip e scegli «File già sul server».', size_format( wp_max_upload_size() ) ) );
		}
		if ( UPLOAD_ERR_OK !== $err || ! is_uploaded_file( $file['tmp_name'] ) ) {
			fma_importa_fallisci( 'Scegli il file fma-contenuti.zip da caricare.' );
		}
		if ( 'zip' !== strtolower( pathinfo( (string) $file['name'], PATHINFO_EXTENSION ) ) ) {
			fma_importa_fallisci( 'Serve un file .zip (fma-contenuti.zip).' );
		}
		$zip = $file['tmp_name'];
	}

	$precedente = get_option( FMA_IMPORTA_STATO );
	if ( is_array( $precedente ) ) {
		fma_importa_pulisci( $precedente['cartella'] ?? '' );
		delete_option( FMA_IMPORTA_STATO );
	}

	$cartella = fma_importa_base() . '/' . FMA_IMPORTA_PREFISSO . wp_generate_password( 12, false );
	if ( ! wp_mkdir_p( $cartella ) ) {
		fma_importa_fallisci( 'Non riesco a creare la cartella temporanea in uploads: controlla i permessi.' );
	}
	file_put_contents( $cartella . '/index.php', "<?php\n// Silence is golden.\n" );
	file_put_contents( $cartella . '/.htaccess', "Require all denied\nDeny from all\n" );

	try {
		$radice = fma_importa_estrai( $zip, $cartella );
		$dati   = FMA_Importatore::leggi( $radice . '/data.json' );
	} catch ( RuntimeException $e ) {
		fma_importa_pulisci( $cartella );
		fma_importa_fallisci( $e->getMessage() );
	}

	$passi = array();
	foreach ( array_values( $dati['strutture'] ) as $i => $s ) {
		$passi[] = array( 'struttura', $i, 'Struttura: ' . sanitize_text_field( $s['nome'] ?? '' ) );
	}
	if ( ! empty( $dati['partner'] ) ) {
		$passi[] = array( 'partner', 0, sprintf( 'Partner (%d)', count( $dati['partner'] ) ) );
	}
	if ( ! empty( $dati['articoli'] ) ) {
		$passi[] = array( 'articoli', 0, sprintf( 'Articoli del blog (%d)', count( $dati['articoli'] ) ) );
	}
	if ( ! empty( $_POST['configura'] ) ) {
		$passi[] = array( 'configura', 0, 'Pagine, menu, logo e permalink' );
	}

	update_option(
		FMA_IMPORTA_STATO,
		array(
			'cartella' => $cartella,
			'passi'    => $passi,
			'fatti'    => 0,
			'utente'   => get_current_user_id(),
			'sorgente' => $sorgente,
			'conti'    => array(
				'strutture' => count( $dati['strutture'] ),
				'partner'   => count( $dati['partner'] ?? array() ),
				'articoli'  => count( $dati['articoli'] ?? array() ),
			),
		),
		false
	);
	wp_safe_redirect( fma_importa_url() );
	exit;
}

function fma_importa_annulla(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Non hai i permessi per importare contenuti.', 403 );
	}
	check_admin_referer( 'fma_importa_annulla' );
	$stato = get_option( FMA_IMPORTA_STATO );
	if ( is_array( $stato ) ) {
		fma_importa_pulisci( $stato['cartella'] ?? '' );
	}
	delete_option( FMA_IMPORTA_STATO );
	wp_safe_redirect( fma_importa_url( array( 'esito' => 'annullato' ) ) );
	exit;
}

/* ---------------------------------------------------------------- un passo (AJAX) */

function fma_importa_passo(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'messaggio' => 'Non hai i permessi per importare contenuti.' ), 403 );
	}
	check_ajax_referer( 'fma_importa_passo', 'nonce' );

	$stato = get_option( FMA_IMPORTA_STATO );
	if ( ! is_array( $stato ) || empty( $stato['passi'] ) ) {
		wp_send_json_error( array( 'messaggio' => 'Nessuna importazione in corso: ricarica la pagina.' ), 409 );
	}
	if ( get_transient( 'fma_importa_blocco' ) ) {
		wp_send_json_error( array( 'messaggio' => 'Un passo è ancora in corso, o si è interrotto da poco: riprova tra qualche minuto.' ), 409 );
	}
	set_transient( 'fma_importa_blocco', 1, 330 ); // poco più del tempo massimo di un passo

	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors -- alcuni hosting lo vietano.
	}
	wp_raise_memory_limit( 'image' );

	$log      = array();
	$registro = function ( string $messaggio, string $tipo ) use ( &$log ) {
		$log[] = array( 'tipo' => $tipo, 'testo' => $messaggio );
	};
	list( $tipo, $indice, $etichetta ) = $stato['passi'][ $stato['fatti'] ];

	try {
		$dati = FMA_Importatore::leggi( $stato['cartella'] . '/data.json' );
		$imp  = new FMA_Importatore( $stato['cartella'] . '/img', $registro );
		switch ( $tipo ) {
			case 'struttura':
				$imp->struttura( array_values( $dati['strutture'] )[ $indice ], $indice );
				break;
			case 'partner':
				foreach ( $dati['partner'] ?? array() as $ordine => $p ) {
					$imp->partner( $p, $ordine );
				}
				break;
			case 'articoli':
				foreach ( $dati['articoli'] ?? array() as $ordine => $a ) {
					$imp->articolo( $a, $ordine );
				}
				break;
			case 'configura':
				$imp->configura_sito();
				break;
		}
	} catch ( Throwable $e ) {
		delete_transient( 'fma_importa_blocco' );
		wp_send_json_error( array( 'messaggio' => $etichetta . ': ' . $e->getMessage(), 'log' => $log ), 500 );
	}

	++$stato['fatti'];
	$finito = $stato['fatti'] >= count( $stato['passi'] );
	if ( $finito ) {
		fma_importa_pulisci( $stato['cartella'] );
		delete_option( FMA_IMPORTA_STATO );
		// Lo zip caricato via FTP in uploads sarebbe scaricabile da chiunque: a lavoro finito si toglie.
		$zip_server = fma_importa_zip_server();
		if ( 'server' === ( $stato['sorgente'] ?? '' ) && '' !== $zip_server ) {
			wp_delete_file( $zip_server );
			$stato['conti']['zip_rimosso'] = fma_importa_percorso_breve( $zip_server );
		}
		set_transient( 'fma_importa_esito_' . get_current_user_id(), $stato['conti'], HOUR_IN_SECONDS );
	} else {
		update_option( FMA_IMPORTA_STATO, $stato, false );
	}
	delete_transient( 'fma_importa_blocco' );

	wp_send_json_success(
		array(
			'fatti'     => $stato['fatti'],
			'totale'    => count( $stato['passi'] ),
			'etichetta' => $etichetta,
			'prossimo'  => $finito ? '' : $stato['passi'][ $stato['fatti'] ][2],
			'log'       => $log,
			'finito'    => $finito,
		)
	);
}

/* ---------------------------------------------------------------- pagina */

function fma_importa_pagina(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$stato  = get_option( FMA_IMPORTA_STATO );
	$utente = get_current_user_id();
	$errore = get_transient( "fma_importa_errore_$utente" );
	$esito  = isset( $_GET['esito'] ) ? sanitize_key( wp_unslash( $_GET['esito'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$conti  = 'ok' === $esito ? get_transient( "fma_importa_esito_$utente" ) : false;
	if ( $errore ) {
		delete_transient( "fma_importa_errore_$utente" );
	}
	?>
	<div class="wrap fma-importa">
		<h1>Importa contenuti FMA</h1>

		<?php if ( $errore ) : ?>
			<div class="notice notice-error"><p><?php echo esc_html( $errore ); ?></p></div>
		<?php endif; ?>
		<?php if ( 'annullato' === $esito ) : ?>
			<div class="notice notice-info"><p>Importazione annullata. I contenuti già importati restano nel sito; puoi rilanciare quando vuoi.</p></div>
		<?php endif; ?>
		<?php if ( is_array( $conti ) ) : ?>
			<div class="notice notice-success">
				<p><strong>Importazione completata:</strong> <?php echo esc_html( sprintf( '%d strutture, %d partner, %d articoli.', $conti['strutture'], $conti['partner'], $conti['articoli'] ) ); ?>
					<?php echo ! empty( $conti['zip_rimosso'] ) ? esc_html( sprintf( 'Il file %s è stato cancellato dal server.', is_string( $conti['zip_rimosso'] ) ? $conti['zip_rimosso'] : 'fma-contenuti.zip' ) ) : ''; ?></p>
				<p><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Vedi il sito</a> <a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=struttura' ) ); ?>">Vai alle strutture</a></p>
			</div>
		<?php endif; ?>

		<?php if ( is_array( $stato ) ) : ?>
			<?php $totale = count( $stato['passi'] ); ?>
			<div class="fma-importa__corso" data-fma-importa>
				<h2>Importazione in corso</h2>
				<p>Lascia aperta questa pagina finché la barra non arriva in fondo: ci vuole circa un minuto.</p>
				<progress max="<?php echo (int) $totale; ?>" value="<?php echo (int) $stato['fatti']; ?>" data-barra></progress>
				<p class="fma-importa__stato" aria-live="polite" data-stato><?php echo esc_html( sprintf( 'Passo %d di %d: %s', $stato['fatti'] + 1, $totale, $stato['passi'][ $stato['fatti'] ][2] ) ); ?></p>
				<p class="notice notice-error inline fma-importa__errore" hidden data-errore></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="fma-importa__azioni">
					<button type="button" class="button button-primary" hidden data-riprova>Riprova da qui</button>
					<input type="hidden" name="action" value="fma_importa_annulla">
					<?php wp_nonce_field( 'fma_importa_annulla' ); ?>
					<button type="submit" class="button-link button-link-delete">Annulla l’importazione</button>
				</form>
				<ol class="fma-importa__log" data-log></ol>
				<noscript><p class="notice notice-warning inline">Per importare serve JavaScript attivo nel browser.</p></noscript>
			</div>
		<?php else : ?>
			<?php
			$zip_server = fma_importa_zip_server();
			$sul_server = '' !== $zip_server ? (int) filesize( $zip_server ) : 0;
			$esistenti  = (int) wp_count_posts( 'struttura' )->publish;
			$sito_nuovo = 0 === $esistenti && 'page' !== get_option( 'show_on_front' );
			?>
			<p>Carica <strong>fma-contenuti.zip</strong> per importare strutture, partner e articoli del blog con tutte le foto.
				Si può rilanciare: aggiorna quello che c’è già e non crea doppioni.</p>
			<?php if ( $esistenti ) : ?>
				<p>Nel sito ci sono già <?php echo (int) $esistenti; ?> strutture: quelle con lo stesso nome nell’indirizzo (slug) verranno aggiornate con i dati dello zip.</p>
			<?php endif; ?>

			<form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="fma-importa__form">
				<input type="hidden" name="action" value="fma_importa_carica">
				<?php wp_nonce_field( 'fma_importa_carica' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">File</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">Da dove prendere lo zip</legend>
								<label><input type="radio" name="sorgente" value="upload" checked> Carica dal computer</label><br>
								<input type="file" name="fma_zip" accept=".zip,application/zip" aria-label="fma-contenuti.zip">
								<p class="description">Limite di caricamento del server: <?php echo esc_html( size_format( wp_max_upload_size() ) ); ?>. Se lo zip è più grande, caricalo via FTP o con il File Manager in <code>wp-content/uploads/</code> (va bene anche <code>wp-content/</code>).</p>
								<?php if ( $sul_server ) : ?>
									<p><label><input type="radio" name="sorgente" value="server"> File già sul server: <code><?php echo esc_html( fma_importa_percorso_breve( $zip_server ) ); ?></code> (<?php echo esc_html( size_format( $sul_server ) ); ?>)</label></p>
								<?php endif; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Configurazione</th>
						<td>
							<label><input type="checkbox" name="configura" value="1"<?php checked( $sito_nuovo ); ?>> Configura anche il sito</label>
							<p class="description">Imposta le pagine Home e Blog, il menu principale, i permalink e il logo, e rimuove i contenuti di esempio di WordPress.
								<strong>Cambia la pagina iniziale e il menu:</strong> lascialo spento se importi in un sito già avviato.</p>
						</td>
					</tr>
				</table>
				<?php submit_button( 'Importa' ); ?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}
