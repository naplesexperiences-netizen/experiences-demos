<?php
/**
 * Plugin Name:       FMA Richieste di soggiorno
 * Description:       Invia ogni richiesta di soggiorno all'email della struttura scelta, con copia all'ospite.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            experiences srl
 * License:           GPL-2.0-or-later
 * Text Domain:       fma-richieste
 *
 * Uso nel template della struttura:  <?php fma_richieste_form( get_the_ID() ); ?>
 */

defined( 'ABSPATH' ) || exit;

const FMA_RICHIESTE_ACTION     = 'fma_richiesta';
const FMA_RICHIESTE_TIPI       = array( 'Vacanza', 'Famiglia', 'Gruppo o parrocchia', 'Ritiro spirituale' );
const FMA_RICHIESTE_LIMITE     = 5;            // richieste per IP…
const FMA_RICHIESTE_FINESTRA   = 15 * 60;      // …ogni 15 minuti
const FMA_RICHIESTE_MIN_SECONDI = 3;           // tempo minimo di compilazione (antispam)

add_action( 'admin_post_nopriv_' . FMA_RICHIESTE_ACTION, 'fma_richieste_gestisci' );
add_action( 'admin_post_' . FMA_RICHIESTE_ACTION, 'fma_richieste_gestisci' );

/**
 * Tipi di contenuto che rappresentano una struttura. "property" è il CPT di RealHomes
 * usato oggi da accoglienzafma.com, "struttura" quello del nuovo plugin.
 */
function fma_richieste_tipi_struttura(): array {
	return (array) apply_filters( 'fma_richieste_tipi_struttura', array( 'struttura', 'property' ) );
}

/**
 * Email della struttura, sempre risolta lato server dall'ID: il modulo non trasporta mai
 * l'indirizzo, così non può essere usato per scrivere a destinatari arbitrari.
 * Ordine: meta "fma_email" della struttura → email del referente RealHomes collegato.
 */
function fma_richieste_destinatario( int $struttura_id ): string {
	$email = (string) get_post_meta( $struttura_id, 'fma_email', true );

	if ( ! is_email( $email ) ) {
		$referenti = (array) get_post_meta( $struttura_id, 'REAL_HOMES_agents', false );
		foreach ( $referenti as $referente_id ) {
			$candidata = (string) get_post_meta( (int) $referente_id, 'REAL_HOMES_agent_email', true );
			if ( is_email( $candidata ) ) {
				$email = $candidata;
				break;
			}
		}
	}

	$email = (string) apply_filters( 'fma_richieste_destinatario', $email, $struttura_id );
	return is_email( $email ) ? $email : '';
}

/** Tipologie di camera proposte nel modulo: meta "fma_camere" oppure camere RealHomes Vacation Rentals. */
function fma_richieste_camere( int $struttura_id ): array {
	$camere = get_post_meta( $struttura_id, 'fma_camere', true );
	$nomi   = array();

	if ( is_array( $camere ) ) {
		foreach ( $camere as $camera ) {
			$nomi[] = is_array( $camera ) ? ( $camera['nome'] ?? '' ) : (string) $camera;
		}
	} else {
		foreach ( (array) get_post_meta( $struttura_id, 'rvr_accommodation', true ) as $camera ) {
			$nomi[] = is_array( $camera ) ? ( $camera['room_type'] ?? '' ) : '';
		}
	}

	$nomi = array_values( array_unique( array_filter( array_map( 'sanitize_text_field', $nomi ) ) ) );
	return (array) apply_filters( 'fma_richieste_camere', $nomi, $struttura_id );
}

/** Riga di intestazione sicura: niente a capo (header injection) né caratteri riservati. */
function fma_richieste_riga( string $testo ): string {
	return trim( preg_replace( '/[\r\n\t<>"]+/', ' ', $testo ) );
}

/** Nome per "Reply-To": oltre agli a capo toglie i separatori di indirizzi. */
function fma_richieste_nome_intestazione( string $nome ): string {
	return trim( preg_replace( '/\s+/', ' ', str_replace( array( ':', ';', ',', '@', '\\' ), ' ', fma_richieste_riga( $nome ) ) ) );
}

function fma_richieste_data_valida( string $data ): bool {
	if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $data, $m ) ) {
		return false;
	}
	return checkdate( (int) $m[2], (int) $m[3], (int) $m[1] );
}

function fma_richieste_data_it( string $data ): string {
	$mesi = array( 'gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre' );
	list( $a, $m, $g ) = array_map( 'intval', explode( '-', $data ) );
	return $g . ' ' . $mesi[ $m - 1 ] . ' ' . $a;
}

function fma_richieste_notti( string $arrivo, string $partenza ): int {
	return (int) round( ( strtotime( $partenza . ' 12:00:00 UTC' ) - strtotime( $arrivo . ' 12:00:00 UTC' ) ) / DAY_IN_SECONDS );
}

/**
 * Valida e pulisce i campi. Restituisce [ campi puliti, errori per campo ].
 * Nessun effetto collaterale: è la parte coperta dai test.
 */
function fma_richieste_valida( array $dati, string $oggi ): array {
	$errori = array();
	$c      = array(
		'arrivo'    => sanitize_text_field( $dati['arrivo'] ?? '' ),
		'partenza'  => sanitize_text_field( $dati['partenza'] ?? '' ),
		'adulti'    => (int) ( $dati['adulti'] ?? 0 ),
		'bambini'   => (int) ( $dati['bambini'] ?? 0 ),
		'camera'    => mb_substr( sanitize_text_field( $dati['camera'] ?? '' ), 0, 80 ),
		'tipo'      => sanitize_text_field( $dati['tipo'] ?? '' ),
		'nome'      => mb_substr( fma_richieste_riga( sanitize_text_field( $dati['nome'] ?? '' ) ), 0, 100 ),
		'email'     => sanitize_email( $dati['email'] ?? '' ),
		'telefono'  => mb_substr( fma_richieste_riga( sanitize_text_field( $dati['telefono'] ?? '' ) ), 0, 30 ),
		'messaggio' => mb_substr( sanitize_textarea_field( $dati['messaggio'] ?? '' ), 0, 2000 ),
		'privacy'   => ! empty( $dati['privacy'] ),
	);

	if ( ! fma_richieste_data_valida( $c['arrivo'] ) ) {
		$errori['arrivo'] = 'Scegli la data di arrivo.';
	} elseif ( $c['arrivo'] < $oggi ) {
		$errori['arrivo'] = 'La data di arrivo è già passata.';
	}
	if ( ! fma_richieste_data_valida( $c['partenza'] ) ) {
		$errori['partenza'] = 'Scegli la data di partenza.';
	} elseif ( empty( $errori['arrivo'] ) && $c['partenza'] <= $c['arrivo'] ) {
		$errori['partenza'] = 'La partenza deve essere dopo l’arrivo.';
	} elseif ( empty( $errori['arrivo'] ) && fma_richieste_notti( $c['arrivo'], $c['partenza'] ) > 120 ) {
		$errori['partenza'] = 'Per soggiorni oltre 120 notti scrivi direttamente alla casa.';
	}
	if ( $c['adulti'] < 1 || $c['adulti'] > 120 ) {
		$errori['adulti'] = 'Indica da 1 a 120 adulti.';
	}
	if ( $c['bambini'] < 0 || $c['bambini'] > 60 ) {
		$errori['bambini'] = 'Indica da 0 a 60 bambini.';
	}
	if ( ! in_array( $c['tipo'], FMA_RICHIESTE_TIPI, true ) ) {
		$c['tipo'] = FMA_RICHIESTE_TIPI[0];
	}
	if ( mb_strlen( $c['nome'] ) < 2 ) {
		$errori['nome'] = 'Scrivi nome e cognome.';
	}
	if ( ! is_email( $c['email'] ) ) {
		$errori['email'] = 'Controlla l’indirizzo email (es. nome@esempio.it).';
	}
	if ( ! $c['privacy'] ) {
		$errori['privacy'] = 'Serve il consenso per inviare la richiesta.';
	}

	return array( $c, $errori );
}

function fma_richieste_ip(): string {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	return (string) apply_filters( 'fma_richieste_ip', $ip );
}

/**
 * Elabora una richiesta: controlli antispam, validazione, email alla struttura e copia all'ospite.
 * Restituisce [ 'ok' => bool, 'messaggio' => string, 'campi' => errori per campo ].
 *
 * Niente nonce: le pagine delle strutture sono in cache (WP-Optimize) e un nonce scaduto
 * farebbe perdere le richieste dei visitatori non loggati. Un modulo pubblico di contatto
 * non ha una sessione da proteggere; lo spam si ferma con honeypot, tempo minimo e limite per IP.
 */
function fma_richieste_elabora( array $dati, string $ip, ?int $adesso = null ): array {
	$adesso = $adesso ?? time();

	// Bot: honeypot compilato o invio troppo rapido → risposta positiva, nessuna email.
	$inizio = (int) ( $dati['fma_t'] ?? 0 );
	if ( ! empty( $dati['fma_sito'] ) || ( $inizio && $adesso - $inizio < FMA_RICHIESTE_MIN_SECONDI ) ) {
		return array( 'ok' => true, 'messaggio' => 'Richiesta inviata.', 'campi' => array() );
	}

	$chiave   = 'fma_rq_' . md5( $ip );
	$contatore = (int) get_transient( $chiave );
	if ( $contatore >= FMA_RICHIESTE_LIMITE ) {
		return array( 'ok' => false, 'messaggio' => 'Hai inviato molte richieste in poco tempo. Riprova tra qualche minuto o scrivi direttamente alla casa.', 'campi' => array() );
	}

	$struttura_id = (int) ( $dati['struttura_id'] ?? 0 );
	$struttura    = $struttura_id ? get_post( $struttura_id ) : null;
	if ( ! $struttura || 'publish' !== $struttura->post_status || ! in_array( $struttura->post_type, fma_richieste_tipi_struttura(), true ) ) {
		return array( 'ok' => false, 'messaggio' => 'Struttura non trovata. Ricarica la pagina e riprova.', 'campi' => array() );
	}

	$destinatario = fma_richieste_destinatario( $struttura_id );
	if ( '' === $destinatario ) {
		return array( 'ok' => false, 'messaggio' => 'Questa casa non ha ancora un indirizzo email configurato: contattala per telefono.', 'campi' => array() );
	}

	list( $c, $errori ) = fma_richieste_valida( $dati, wp_date( 'Y-m-d', $adesso ) );
	if ( $errori ) {
		$n = count( $errori );
		return array( 'ok' => false, 'messaggio' => 1 === $n ? 'Manca un’informazione: la trovi evidenziata.' : "Mancano {$n} informazioni: le trovi evidenziate.", 'campi' => $errori );
	}

	set_transient( $chiave, $contatore + 1, FMA_RICHIESTE_FINESTRA );

	$casa   = fma_richieste_riga( get_the_title( $struttura ) );
	$notti  = fma_richieste_notti( $c['arrivo'], $c['partenza'] );
	$ospiti = $c['adulti'] . ( 1 === $c['adulti'] ? ' adulto' : ' adulti' )
		. ( $c['bambini'] ? ', ' . $c['bambini'] . ( 1 === $c['bambini'] ? ' bambino' : ' bambini' ) : '' );
	$camera = $c['camera'] ?: 'da concordare con la casa';
	$quando = fma_richieste_data_it( $c['arrivo'] ) . ' – ' . fma_richieste_data_it( $c['partenza'] ) . " ({$notti} " . ( 1 === $notti ? 'notte' : 'notti' ) . ')';

	$riepilogo = implode(
		"\n",
		array(
			"Struttura: {$casa}",
			"Date: {$quando}",
			"Partecipanti: {$ospiti}",
			"Camera: {$camera}",
			"Tipo di soggiorno: {$c['tipo']}",
		)
	);

	$corpo_casa = "Nuova richiesta di soggiorno dal sito " . fma_richieste_riga( get_bloginfo( 'name' ) ) . ".\n\n"
		. $riepilogo . "\n\n"
		. "Nome: {$c['nome']}\n"
		. "Email: {$c['email']}\n"
		. ( $c['telefono'] ? "Telefono: {$c['telefono']}\n" : '' )
		. ( $c['messaggio'] ? "\nMessaggio:\n{$c['messaggio']}\n" : '' )
		. "\nRispondi a questa email per scrivere direttamente all’ospite.\n"
		. 'Pagina della struttura: ' . get_permalink( $struttura ) . "\n";

	$intestazioni_casa = (array) apply_filters(
		'fma_richieste_intestazioni',
		array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . fma_richieste_nome_intestazione( $c['nome'] ) . ' <' . $c['email'] . '>',
		),
		$struttura_id,
		$c
	);

	$oggetto = sprintf( 'Richiesta di soggiorno: %s, %s', fma_richieste_nome_intestazione( $c['nome'] ), fma_richieste_data_it( $c['arrivo'] ) );

	if ( ! wp_mail( $destinatario, $oggetto, $corpo_casa, $intestazioni_casa ) ) {
		error_log( sprintf( '[fma-richieste] invio fallito verso %s (struttura %d)', $destinatario, $struttura_id ) );
		return array( 'ok' => false, 'messaggio' => "Non siamo riusciti a inviare la richiesta. Scrivi direttamente a {$destinatario}.", 'campi' => array() );
	}

	// Copia all'ospite: se fallisce, la richiesta alla casa è comunque partita.
	$corpo_ospite = "Ciao {$c['nome']},\n\n"
		. "abbiamo inoltrato la tua richiesta a {$casa}. Ti risponderà direttamente la casa, con disponibilità e prezzi.\n\n"
		. $riepilogo . "\n\n"
		. "Per aggiungere dettagli rispondi a questa email: arriverà a {$casa} ({$destinatario}).\n";
	wp_mail(
		$c['email'],
		"Abbiamo inoltrato la tua richiesta a {$casa}",
		$corpo_ospite,
		array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . fma_richieste_nome_intestazione( $casa ) . ' <' . $destinatario . '>' )
	);

	do_action( 'fma_richieste_inviata', $struttura_id, $c, $destinatario );

	return array( 'ok' => true, 'messaggio' => "Richiesta inviata a {$casa}. Ti abbiamo mandato una copia a {$c['email']}.", 'campi' => array() );
}

/** Endpoint admin-post.php: risponde JSON alle chiamate fetch, altrimenti torna alla pagina. */
function fma_richieste_gestisci(): void {
	$dati  = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification -- vedi fma_richieste_elabora().
	$esito = fma_richieste_elabora( is_array( $dati ) ? $dati : array(), fma_richieste_ip() );

	if ( ! empty( $dati['ajax'] ) ) {
		wp_send_json( $esito, $esito['ok'] ? 200 : 422 );
	}

	$ritorno = wp_get_referer() ?: home_url( '/' );
	$ritorno = add_query_arg( 'richiesta', $esito['ok'] ? 'inviata' : 'errore', remove_query_arg( 'richiesta', $ritorno ) );
	wp_safe_redirect( $ritorno . '#richiesta' );
	exit;
}

/**
 * Stampa il modulo di richiesta per una struttura. Stesso markup della demo statica,
 * così site.css e site.js del template funzionano senza modifiche.
 */
function fma_richieste_form( int $struttura_id ): void {
	$casa   = get_the_title( $struttura_id );
	$email  = fma_richieste_destinatario( $struttura_id );
	$camere = fma_richieste_camere( $struttura_id );
	$oggi   = wp_date( 'Y-m-d' );
	$stato  = isset( $_GET['richiesta'] ) ? sanitize_key( wp_unslash( $_GET['richiesta'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$icona  = function ( string $d ): string {
		return '<svg class="icon icon--sm" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . esc_attr( $d ) . '"/></svg>';
	};
	$meno = $icona( 'M6 12h12' );
	$piu  = $icona( 'M12 6v12M6 12h12' );
	?>
	<form class="request" id="richiesta" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
		data-request data-endpoint="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate aria-labelledby="req-title"<?php echo 'inviata' === $stato ? ' hidden' : ''; ?>>
		<input type="hidden" name="action" value="<?php echo esc_attr( FMA_RICHIESTE_ACTION ); ?>">
		<input type="hidden" name="struttura_id" value="<?php echo (int) $struttura_id; ?>">
		<input type="hidden" name="fma_t" value="" data-started>
		<div class="visually-hidden" aria-hidden="true">
			<label for="r-sito">Lascia vuoto questo campo</label>
			<input id="r-sito" name="fma_sito" type="text" tabindex="-1" autocomplete="off">
		</div>

		<h2 id="req-title" class="request__title">Richiedi un soggiorno</h2>
		<p class="request__hint">Senza impegno: la casa ti risponde con disponibilità e prezzi.</p>
		<div class="request__dates">
			<div class="field"><label for="r-arrivo">Arrivo</label><input id="r-arrivo" name="arrivo" type="date" min="<?php echo esc_attr( $oggi ); ?>" required data-date-in aria-describedby="r-arrivo-err"><p class="field__err" id="r-arrivo-err"></p></div>
			<div class="field"><label for="r-partenza">Partenza</label><input id="r-partenza" name="partenza" type="date" min="<?php echo esc_attr( $oggi ); ?>" required data-date-out aria-describedby="r-partenza-err"><p class="field__err" id="r-partenza-err"></p></div>
		</div>
		<p class="request__nights" data-nights aria-live="polite"></p>
		<fieldset class="request__people">
			<legend>Partecipanti</legend>
			<div class="field"><label for="r-adulti">Adulti</label>
				<div class="stepper" data-stepper><button type="button" data-step="-1" aria-label="Un adulto in meno"><?php echo $meno; // phpcs:ignore ?></button><input id="r-adulti" name="adulti" type="number" min="1" max="120" value="2" inputmode="numeric"><button type="button" data-step="1" aria-label="Un adulto in più"><?php echo $piu; // phpcs:ignore ?></button></div></div>
			<div class="field"><label for="r-bambini">Bambini</label>
				<div class="stepper" data-stepper><button type="button" data-step="-1" aria-label="Un bambino in meno"><?php echo $meno; // phpcs:ignore ?></button><input id="r-bambini" name="bambini" type="number" min="0" max="60" value="0" inputmode="numeric"><button type="button" data-step="1" aria-label="Un bambino in più"><?php echo $piu; // phpcs:ignore ?></button></div></div>
		</fieldset>
		<div class="field"><label for="r-camera">Tipologia di camera</label>
			<select id="r-camera" name="camera">
				<option value="">Da concordare con la casa</option>
				<?php if ( $camere ) : ?>
					<?php foreach ( $camere as $camera ) : ?>
						<option><?php echo esc_html( $camera ); ?></option>
					<?php endforeach; ?>
					<option>Più tipologie (gruppo)</option>
				<?php else : ?>
					<option>Camera singola</option><option>Camera doppia</option><option>Camera per famiglia</option><option>Più camere (gruppo)</option>
				<?php endif; ?>
			</select></div>
		<fieldset class="request__kind">
			<legend>Tipo di soggiorno</legend>
			<div class="kinds">
				<?php foreach ( FMA_RICHIESTE_TIPI as $i => $tipo ) : ?>
					<label><input type="radio" name="tipo" value="<?php echo esc_attr( $tipo ); ?>"<?php checked( 0 === $i ); ?>><span><?php echo esc_html( $tipo ); ?></span></label>
				<?php endforeach; ?>
			</div>
		</fieldset>
		<div class="field"><label for="r-nome">Nome e cognome</label><input id="r-nome" name="nome" type="text" autocomplete="name" required aria-describedby="r-nome-err"><p class="field__err" id="r-nome-err"></p></div>
		<div class="field"><label for="r-email">Email</label><input id="r-email" name="email" type="email" autocomplete="email" required aria-describedby="r-email-err"><p class="field__err" id="r-email-err"></p></div>
		<div class="field"><label for="r-tel">Telefono <span class="opt">(facoltativo)</span></label><input id="r-tel" name="telefono" type="tel" autocomplete="tel"></div>
		<div class="field"><label for="r-msg">Messaggio <span class="opt">(facoltativo)</span></label><textarea id="r-msg" name="messaggio" rows="3" placeholder="Esigenze particolari, orario di arrivo, pasti…"></textarea></div>
		<label class="consent"><input type="checkbox" name="privacy" value="1" required><span>Ho letto l’<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" target="_blank" rel="noopener">informativa privacy</a> e acconsento al trattamento dei dati per rispondere alla richiesta.</span></label>
		<p class="request__error" role="alert" data-request-error<?php echo 'errore' === $stato ? '' : ' hidden'; ?>><?php echo 'errore' === $stato ? esc_html__( 'La richiesta non è partita: controlla i campi e riprova.', 'fma-richieste' ) : ''; ?></p>
		<button class="btn btn--primary request__send" type="submit"><span class="btn__label">Invia la richiesta</span><span class="btn__spinner" aria-hidden="true"></span></button>
		<?php if ( $email ) : ?>
			<p class="request__to">La richiesta arriva a <strong><?php echo esc_html( $email ); ?></strong>.</p>
		<?php endif; ?>
	</form>
	<div class="request-done" data-request-done tabindex="-1"<?php echo 'inviata' === $stato ? '' : ' hidden'; ?>>
		<span class="request-done__icon" aria-hidden="true"><?php echo $icona( 'M5 12.5l4.5 4.5L19 7.5' ); // phpcs:ignore ?></span>
		<h2 class="request__title">Richiesta inviata</h2>
		<p data-request-summary><?php echo esc_html( sprintf( 'La tua richiesta è arrivata a %s: ti risponderà direttamente la casa. Ti abbiamo mandato una copia via email.', $casa ) ); ?></p>
		<button type="button" class="text-link" data-request-again>Invia un’altra richiesta</button>
	</div>
	<?php
}
