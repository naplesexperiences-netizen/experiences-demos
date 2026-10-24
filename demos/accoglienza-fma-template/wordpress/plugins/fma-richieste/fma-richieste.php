<?php
/**
 * Plugin Name:       FMA Richieste di soggiorno
 * Description:       Invia ogni richiesta di soggiorno all'email della struttura scelta, con copia all'ospite.
 * Version:           1.2.0
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

if ( is_admin() ) {
	require __DIR__ . '/includes/informativa.php';
}

add_action(
	'init',
	function () {
		load_plugin_textdomain( 'fma-richieste', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

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

/** Etichetta del tipo di soggiorno nella lingua della pagina (il valore inviato resta quello italiano). */
function fma_richieste_tipo_etichetta( string $tipo ): string {
	$etichette = array(
		'Vacanza'            => __( 'Vacanza', 'fma-richieste' ),
		'Famiglia'           => __( 'Famiglia', 'fma-richieste' ),
		'Gruppo o parrocchia' => __( 'Gruppo o parrocchia', 'fma-richieste' ),
		'Ritiro spirituale'  => __( 'Ritiro spirituale', 'fma-richieste' ),
	);
	return $etichette[ $tipo ] ?? $tipo;
}

/** Data nella lingua corrente, per la copia all'ospite. */
function fma_richieste_data_lingua( string $data ): string {
	if ( str_starts_with( determine_locale(), 'it' ) ) {
		return fma_richieste_data_it( $data );
	}
	/* translators: formato della data nelle email (https://www.php.net/manual/datetime.format.php) */
	return wp_date( _x( 'j F Y', 'formato data email', 'fma-richieste' ), strtotime( $data . ' 12:00:00 UTC' ), new DateTimeZone( 'UTC' ) );
}

/** Nome della lingua per l'email alla casa («inglese», «francese»…). */
function fma_richieste_nome_lingua( string $locale ): string {
	$nomi = array( 'en' => 'inglese', 'fr' => 'francese', 'es' => 'spagnolo', 'pt' => 'portoghese', 'zh' => 'cinese', 'de' => 'tedesco' );
	return $nomi[ substr( $locale, 0, 2 ) ] ?? $locale;
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
		$errori['arrivo'] = __( 'Scegli la data di arrivo.', 'fma-richieste' );
	} elseif ( $c['arrivo'] < $oggi ) {
		$errori['arrivo'] = __( 'La data di arrivo è già passata.', 'fma-richieste' );
	}
	if ( ! fma_richieste_data_valida( $c['partenza'] ) ) {
		$errori['partenza'] = __( 'Scegli la data di partenza.', 'fma-richieste' );
	} elseif ( empty( $errori['arrivo'] ) && $c['partenza'] <= $c['arrivo'] ) {
		$errori['partenza'] = __( 'La partenza deve essere dopo l’arrivo.', 'fma-richieste' );
	} elseif ( empty( $errori['arrivo'] ) && fma_richieste_notti( $c['arrivo'], $c['partenza'] ) > 120 ) {
		$errori['partenza'] = __( 'Per soggiorni oltre 120 notti scrivi direttamente alla casa.', 'fma-richieste' );
	}
	if ( $c['adulti'] < 1 || $c['adulti'] > 120 ) {
		$errori['adulti'] = __( 'Indica da 1 a 120 adulti.', 'fma-richieste' );
	}
	if ( $c['bambini'] < 0 || $c['bambini'] > 60 ) {
		$errori['bambini'] = __( 'Indica da 0 a 60 bambini.', 'fma-richieste' );
	}
	if ( ! in_array( $c['tipo'], FMA_RICHIESTE_TIPI, true ) ) {
		$c['tipo'] = FMA_RICHIESTE_TIPI[0];
	}
	if ( mb_strlen( $c['nome'] ) < 2 ) {
		$errori['nome'] = __( 'Scrivi nome e cognome.', 'fma-richieste' );
	}
	if ( ! is_email( $c['email'] ) ) {
		$errori['email'] = __( 'Controlla l’indirizzo email (es. nome@esempio.it).', 'fma-richieste' );
	}
	if ( ! $c['privacy'] ) {
		$errori['privacy'] = __( 'Serve il consenso per inviare la richiesta.', 'fma-richieste' );
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
		return array( 'ok' => true, 'messaggio' => __( 'Richiesta inviata.', 'fma-richieste' ), 'campi' => array() );
	}

	$chiave   = 'fma_rq_' . md5( $ip );
	$contatore = (int) get_transient( $chiave );
	if ( $contatore >= FMA_RICHIESTE_LIMITE ) {
		return array( 'ok' => false, 'messaggio' => __( 'Hai inviato molte richieste in poco tempo. Riprova tra qualche minuto o scrivi direttamente alla casa.', 'fma-richieste' ), 'campi' => array() );
	}

	$struttura_id = (int) ( $dati['struttura_id'] ?? 0 );
	$struttura    = $struttura_id ? get_post( $struttura_id ) : null;
	if ( ! $struttura || 'publish' !== $struttura->post_status || ! in_array( $struttura->post_type, fma_richieste_tipi_struttura(), true ) ) {
		return array( 'ok' => false, 'messaggio' => __( 'Struttura non trovata. Ricarica la pagina e riprova.', 'fma-richieste' ), 'campi' => array() );
	}

	$destinatario = fma_richieste_destinatario( $struttura_id );
	if ( '' === $destinatario ) {
		return array( 'ok' => false, 'messaggio' => __( 'Questa casa non riceve ancora richieste dal sito: usa i recapiti indicati nella pagina.', 'fma-richieste' ), 'campi' => array() );
	}

	list( $c, $errori ) = fma_richieste_valida( $dati, wp_date( 'Y-m-d', $adesso ) );
	if ( $errori ) {
		$n = count( $errori );
		/* translators: %d: numero di campi da correggere */
		return array( 'ok' => false, 'messaggio' => 1 === $n ? __( 'Manca un’informazione: la trovi evidenziata.', 'fma-richieste' ) : sprintf( __( 'Mancano %d informazioni: le trovi evidenziate.', 'fma-richieste' ), $n ), 'campi' => $errori );
	}

	set_transient( $chiave, $contatore + 1, FMA_RICHIESTE_FINESTRA );

	$casa   = fma_richieste_riga( get_the_title( $struttura ) );
	$notti  = fma_richieste_notti( $c['arrivo'], $c['partenza'] );
	$locale = determine_locale();
	$estero = ! str_starts_with( $locale, 'it' );

	// Email alla casa: sempre in italiano.
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

	// Copia all'ospite: nella lingua della pagina da cui ha scritto.
	/* translators: 1: adulti, 2: bambini, es. «2 adulti, 1 bambino» */
	$ospiti_t = sprintf( _n( '%d adulto', '%d adulti', $c['adulti'], 'fma-richieste' ), $c['adulti'] )
		. ( $c['bambini'] ? ', ' . sprintf( /* translators: %d: numero */ _n( '%d bambino', '%d bambini', $c['bambini'], 'fma-richieste' ), $c['bambini'] ) : '' );
	/* translators: %d: numero di notti */
	$quando_t    = fma_richieste_data_lingua( $c['arrivo'] ) . ' – ' . fma_richieste_data_lingua( $c['partenza'] ) . ' (' . sprintf( _n( '%d notte', '%d notti', $notti, 'fma-richieste' ), $notti ) . ')';
	$riepilogo_t = implode(
		"\n",
		array(
			/* translators: %s: nome della casa */
			sprintf( __( 'Struttura: %s', 'fma-richieste' ), $casa ),
			/* translators: %s: date del soggiorno */
			sprintf( __( 'Date: %s', 'fma-richieste' ), $quando_t ),
			/* translators: %s: adulti e bambini */
			sprintf( __( 'Partecipanti: %s', 'fma-richieste' ), $ospiti_t ),
			/* translators: %s: tipo di camera */
			sprintf( __( 'Camera: %s', 'fma-richieste' ), $c['camera'] ?: __( 'da concordare con la casa', 'fma-richieste' ) ),
			/* translators: %s: tipo di soggiorno */
			sprintf( __( 'Tipo di soggiorno: %s', 'fma-richieste' ), fma_richieste_tipo_etichetta( $c['tipo'] ) ),
		)
	);

	$corpo_casa = "Nuova richiesta di soggiorno dal sito " . fma_richieste_riga( get_bloginfo( 'name' ) ) . ".\n\n"
		. ( $estero ? 'Lingua dell’ospite: ' . fma_richieste_nome_lingua( $locale ) . ". Se puoi, rispondi in questa lingua o in inglese.\n\n" : '' )
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
		/* translators: %s: email della casa */
		return array( 'ok' => false, 'messaggio' => sprintf( __( 'Non siamo riusciti a inviare la richiesta. Scrivi direttamente a %s.', 'fma-richieste' ), $destinatario ), 'campi' => array() );
	}

	// Copia all'ospite: se fallisce, la richiesta alla casa è comunque partita.
	/* translators: %s: nome dell'ospite */
	$corpo_ospite = sprintf( __( 'Ciao %s,', 'fma-richieste' ), $c['nome'] ) . "\n\n"
		/* translators: %s: nome della casa */
		. sprintf( __( 'abbiamo inoltrato la tua richiesta a %s. Ti risponderà direttamente la casa, con disponibilità e prezzi.', 'fma-richieste' ), $casa ) . "\n\n"
		. $riepilogo_t . "\n\n"
		/* translators: 1: nome della casa, 2: email della casa */
		. sprintf( __( 'Per aggiungere dettagli rispondi a questa email: arriverà a %1$s (%2$s).', 'fma-richieste' ), $casa, $destinatario ) . "\n";
	wp_mail(
		$c['email'],
		/* translators: %s: nome della casa */
		sprintf( __( 'Abbiamo inoltrato la tua richiesta a %s', 'fma-richieste' ), $casa ),
		$corpo_ospite,
		array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . fma_richieste_nome_intestazione( $casa ) . ' <' . $destinatario . '>' )
	);

	do_action( 'fma_richieste_inviata', $struttura_id, $c, $destinatario );

	/* translators: 1: nome della casa, 2: email dell'ospite */
	return array( 'ok' => true, 'messaggio' => sprintf( __( 'Richiesta inviata a %1$s. Ti abbiamo mandato una copia a %2$s.', 'fma-richieste' ), $casa, $c['email'] ), 'campi' => array() );
}

/** Endpoint admin-post.php: risponde JSON alle chiamate fetch, altrimenti torna alla pagina. */
function fma_richieste_gestisci(): void {
	$dati = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification -- vedi fma_richieste_elabora().
	// Risposte e copia all'ospite nella lingua della pagina del modulo; solo lingue installate nel sito.
	$lingua = is_array( $dati ) ? (string) ( $dati['lingua'] ?? '' ) : '';
	if ( preg_match( '/^[a-z]{2,3}(_[A-Z]{2})?$/', $lingua ) && in_array( $lingua, array_merge( array( 'it_IT', 'en_US' ), get_available_languages() ), true ) ) {
		switch_to_locale( $lingua );
	}
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
		<input type="hidden" name="lingua" value="<?php echo esc_attr( determine_locale() ); ?>">
		<div class="visually-hidden" aria-hidden="true">
			<label for="r-sito"><?php esc_html_e( 'Lascia vuoto questo campo', 'fma-richieste' ); ?></label>
			<input id="r-sito" name="fma_sito" type="text" tabindex="-1" autocomplete="off">
		</div>

		<h2 id="req-title" class="request__title"><?php esc_html_e( 'Richiedi un soggiorno', 'fma-richieste' ); ?></h2>
		<p class="request__hint"><?php esc_html_e( 'Senza impegno: la casa ti risponde con disponibilità e prezzi.', 'fma-richieste' ); ?></p>
		<div class="request__dates">
			<div class="field"><label for="r-arrivo"><?php esc_html_e( 'Arrivo', 'fma-richieste' ); ?></label><input id="r-arrivo" name="arrivo" type="date" min="<?php echo esc_attr( $oggi ); ?>" required data-date-in aria-describedby="r-arrivo-err"><p class="field__err" id="r-arrivo-err"></p></div>
			<div class="field"><label for="r-partenza"><?php esc_html_e( 'Partenza', 'fma-richieste' ); ?></label><input id="r-partenza" name="partenza" type="date" min="<?php echo esc_attr( $oggi ); ?>" required data-date-out aria-describedby="r-partenza-err"><p class="field__err" id="r-partenza-err"></p></div>
		</div>
		<p class="request__nights" data-nights aria-live="polite"></p>
		<fieldset class="request__people">
			<legend><?php esc_html_e( 'Partecipanti', 'fma-richieste' ); ?></legend>
			<div class="field"><label for="r-adulti"><?php esc_html_e( 'Adulti', 'fma-richieste' ); ?></label>
				<div class="stepper" data-stepper><button type="button" data-step="-1" aria-label="<?php esc_attr_e( 'Un adulto in meno', 'fma-richieste' ); ?>"><?php echo $meno; // phpcs:ignore ?></button><input id="r-adulti" name="adulti" type="number" min="1" max="120" value="2" inputmode="numeric"><button type="button" data-step="1" aria-label="<?php esc_attr_e( 'Un adulto in più', 'fma-richieste' ); ?>"><?php echo $piu; // phpcs:ignore ?></button></div></div>
			<div class="field"><label for="r-bambini"><?php esc_html_e( 'Bambini', 'fma-richieste' ); ?></label>
				<div class="stepper" data-stepper><button type="button" data-step="-1" aria-label="<?php esc_attr_e( 'Un bambino in meno', 'fma-richieste' ); ?>"><?php echo $meno; // phpcs:ignore ?></button><input id="r-bambini" name="bambini" type="number" min="0" max="60" value="0" inputmode="numeric"><button type="button" data-step="1" aria-label="<?php esc_attr_e( 'Un bambino in più', 'fma-richieste' ); ?>"><?php echo $piu; // phpcs:ignore ?></button></div></div>
		</fieldset>
		<div class="field"><label for="r-camera"><?php esc_html_e( 'Tipologia di camera', 'fma-richieste' ); ?></label>
			<select id="r-camera" name="camera">
				<option value=""><?php esc_html_e( 'Da concordare con la casa', 'fma-richieste' ); ?></option>
				<?php if ( $camere ) : ?>
					<?php foreach ( $camere as $camera ) : ?>
						<option><?php echo esc_html( $camera ); ?></option>
					<?php endforeach; ?>
					<option><?php esc_html_e( 'Più tipologie (gruppo)', 'fma-richieste' ); ?></option>
				<?php else : ?>
					<option><?php esc_html_e( 'Camera singola', 'fma-richieste' ); ?></option><option><?php esc_html_e( 'Camera doppia', 'fma-richieste' ); ?></option><option><?php esc_html_e( 'Camera per famiglia', 'fma-richieste' ); ?></option><option><?php esc_html_e( 'Più camere (gruppo)', 'fma-richieste' ); ?></option>
				<?php endif; ?>
			</select></div>
		<fieldset class="request__kind">
			<legend><?php esc_html_e( 'Tipo di soggiorno', 'fma-richieste' ); ?></legend>
			<div class="kinds">
				<?php foreach ( FMA_RICHIESTE_TIPI as $i => $tipo ) : ?>
					<label><input type="radio" name="tipo" value="<?php echo esc_attr( $tipo ); ?>"<?php checked( 0 === $i ); ?>><span><?php echo esc_html( fma_richieste_tipo_etichetta( $tipo ) ); ?></span></label>
				<?php endforeach; ?>
			</div>
		</fieldset>
		<div class="field"><label for="r-nome"><?php esc_html_e( 'Nome e cognome', 'fma-richieste' ); ?></label><input id="r-nome" name="nome" type="text" autocomplete="name" required aria-describedby="r-nome-err"><p class="field__err" id="r-nome-err"></p></div>
		<div class="field"><label for="r-email"><?php esc_html_e( 'Email', 'fma-richieste' ); ?></label><input id="r-email" name="email" type="email" autocomplete="email" required aria-describedby="r-email-err"><p class="field__err" id="r-email-err"></p></div>
		<div class="field"><label for="r-tel"><?php esc_html_e( 'Telefono', 'fma-richieste' ); ?> <span class="opt"><?php esc_html_e( '(facoltativo)', 'fma-richieste' ); ?></span></label><input id="r-tel" name="telefono" type="tel" autocomplete="tel"></div>
		<div class="field"><label for="r-msg"><?php esc_html_e( 'Messaggio', 'fma-richieste' ); ?> <span class="opt"><?php esc_html_e( '(facoltativo)', 'fma-richieste' ); ?></span></label><textarea id="r-msg" name="messaggio" rows="3" placeholder="<?php esc_attr_e( 'Esigenze particolari, orario di arrivo, pasti…', 'fma-richieste' ); ?>"></textarea></div>
		<label class="consent"><input type="checkbox" name="privacy" value="1" required><span>
			<?php if ( get_privacy_policy_url() ) : ?>
				<?php
				printf(
					/* translators: %s: link «informativa privacy» */
					esc_html__( 'Ho letto l’%s e acconsento al trattamento dei dati per rispondere alla richiesta.', 'fma-richieste' ),
					'<a href="' . esc_url( get_privacy_policy_url() ) . '" target="_blank" rel="noopener">' . esc_html__( 'informativa privacy', 'fma-richieste' ) . '</a>'
				);
				?>
			<?php else : // Senza informativa pubblicata niente link vuoto; l'amministratore vede un avviso (includes/informativa.php). ?>
				<?php esc_html_e( 'Acconsento al trattamento dei dati per rispondere alla richiesta: li riceve solo la casa scelta.', 'fma-richieste' ); ?>
			<?php endif; ?>
		</span></label>
		<p class="request__error" role="alert" data-request-error<?php echo 'errore' === $stato ? '' : ' hidden'; ?>><?php echo 'errore' === $stato ? esc_html__( 'La richiesta non è partita: controlla i campi e riprova.', 'fma-richieste' ) : ''; ?></p>
		<button class="btn btn--primary request__send" type="submit"><span class="btn__label"><?php esc_html_e( 'Invia la richiesta', 'fma-richieste' ); ?></span><span class="btn__spinner" aria-hidden="true"></span></button>
		<?php if ( $email ) : ?>
			<p class="request__to"><?php printf( /* translators: %s: email della casa */ esc_html__( 'La richiesta arriva a %s.', 'fma-richieste' ), '<strong>' . esc_html( $email ) . '</strong>' ); ?></p>
		<?php endif; ?>
	</form>
	<div class="request-done" data-request-done tabindex="-1"<?php echo 'inviata' === $stato ? '' : ' hidden'; ?>>
		<span class="request-done__icon" aria-hidden="true"><?php echo $icona( 'M5 12.5l4.5 4.5L19 7.5' ); // phpcs:ignore ?></span>
		<h2 class="request__title"><?php esc_html_e( 'Richiesta inviata', 'fma-richieste' ); ?></h2>
		<p data-request-summary><?php echo esc_html( sprintf( /* translators: %s: nome della casa */ __( 'La tua richiesta è arrivata a %s: ti risponderà direttamente la casa. Ti abbiamo mandato una copia via email.', 'fma-richieste' ), $casa ) ); ?></p>
		<button type="button" class="text-link" data-request-again><?php esc_html_e( 'Invia un’altra richiesta', 'fma-richieste' ); ?></button>
	</div>
	<?php
}
