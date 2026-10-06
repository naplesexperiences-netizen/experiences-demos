<?php
/**
 * Informativa privacy del modulo di richiesta.
 *
 * Il modulo raccoglie nome, email e telefono: senza un'informativa pubblicata l'amministratore
 * vede un avviso con un pulsante che crea la pagina in bozza, con un testo di partenza già
 * scritto per questo sito. I punti da completare sono tra parentesi quadre; finché restano
 * nella pagina pubblicata, l'avviso lo ricorda.
 */

defined( 'ABSPATH' ) || exit;

const FMA_RICHIESTE_SEGNAPOSTO = '[da completare';

add_action( 'admin_notices', 'fma_richieste_avviso_informativa' );
add_action( 'admin_post_fma_richieste_crea_informativa', 'fma_richieste_crea_informativa' );

/** ID della pagina scelta in Impostazioni → Privacy, se esiste ancora. */
function fma_richieste_pagina_informativa(): int {
	$id = (int) get_option( 'wp_page_for_privacy_policy' );
	return $id && get_post( $id ) && 'trash' !== get_post_status( $id ) ? $id : 0;
}

function fma_richieste_avviso_informativa(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$id = fma_richieste_pagina_informativa();

	if ( get_privacy_policy_url() ) {
		if ( str_contains( (string) get_post_field( 'post_content', $id ), FMA_RICHIESTE_SEGNAPOSTO ) ) {
			printf(
				'<div class="notice notice-warning"><p><strong>Informativa privacy da completare.</strong> La pagina è pubblicata ma contiene ancora punti tra parentesi quadre «[da completare…]». <a href="%s">Completala</a>.</p></div>',
				esc_url( (string) get_edit_post_link( $id ) )
			);
		}
		return;
	}

	$azione = sprintf(
		'<a class="button button-primary" href="%s">Crea l’informativa (bozza da completare)</a>',
		esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=fma_richieste_crea_informativa' ), 'fma_richieste_crea_informativa' ) )
	);
	if ( $id ) {
		// Di solito è la bozza «Privacy Policy» creata da WordPress all'installazione: è una traccia generica, non un'informativa.
		$azione .= sprintf( ' <a class="button" href="%s">Completa la bozza «%s»</a>', esc_url( (string) get_edit_post_link( $id ) ), esc_html( get_the_title( $id ) ) );
	}
	printf(
		'<div class="notice notice-error"><p><strong>Manca l’informativa privacy.</strong> Il modulo di richiesta di soggiorno raccoglie nome, email e telefono degli ospiti: per legge serve un’informativa pubblicata, collegata al modulo e al piè di pagina.</p><p>%s <a class="button" href="%s">Scegli una pagina esistente</a></p></div>',
		$azione, // phpcs:ignore WordPress.Security.EscapeOutput -- costruito sopra con esc_url.
		esc_url( admin_url( 'options-privacy.php' ) )
	);
}

function fma_richieste_crea_informativa(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Non hai i permessi per creare questa pagina.', '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'fma_richieste_crea_informativa' );

	// Già creata con questo pulsante e non cestinata: si riapre quella, senza doppioni.
	$id = (int) get_option( 'fma_richieste_informativa' );
	if ( ! $id || ! get_post( $id ) || 'trash' === get_post_status( $id ) ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'draft',
				'post_title'   => 'Informativa privacy',
				'post_name'    => 'informativa-privacy',
				'post_content' => fma_richieste_testo_informativa(),
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			wp_die( esc_html( 'La pagina non è stata creata: ' . $id->get_error_message() ) );
		}
		update_option( 'fma_richieste_informativa', (int) $id, false );
	}
	update_option( 'wp_page_for_privacy_policy', (int) $id );
	wp_safe_redirect( admin_url( 'post.php?post=' . (int) $id . '&action=edit' ) );
	exit;
}

/**
 * Testo di partenza, in blocchi dell'editor. Descrive quello che il sito fa davvero:
 * modulo inoltrato via email alla casa (nessuna richiesta salvata nel database), copia all'ospite,
 * limite anti-abuso sull'IP, statistiche Burst sul nostro server, mappe OpenStreetMap,
 * font e script senza servizi esterni.
 */
function fma_richieste_testo_informativa(): string {
	$sito  = wp_parse_url( home_url(), PHP_URL_HOST );
	$oggi  = wp_date( 'j F Y' );
	$blocchi = array(
		array( 'p', '<em>Ultimo aggiornamento: ' . $oggi . '.</em>' ),
		array( 'p', 'Questa informativa spiega come trattiamo i dati personali di chi visita ' . $sito . ' e di chi invia una richiesta di soggiorno a una delle nostre case per ferie (art. 13 del Regolamento UE 2016/679, «GDPR»).' ),

		array( 'h2', 'Chi tratta i dati' ),
		array( 'p', '<strong>Titolare del sito:</strong> [da completare: denominazione dell’ente, indirizzo della sede, codice fiscale] — email per la privacy: [da completare: indirizzo email].' ),
		array( 'p', 'Ogni casa per ferie riceve direttamente le richieste che la riguardano e le gestisce come titolare autonomo per rispondere e, se il soggiorno si conferma, per la prenotazione. I suoi recapiti sono nella pagina della casa. [da completare: verificare con l’ente se le case sono titolari autonomi o se il titolare è unico]' ),

		array( 'h2', 'Richiesta di soggiorno' ),
		array( 'p', '<strong>Quali dati:</strong> nome e cognome, email, telefono (facoltativo), date, numero di adulti e bambini, tipo di camera e di soggiorno, messaggio.' ),
		array( 'p', '<strong>Perché:</strong> per inoltrare la richiesta alla casa scelta, che ti risponde con disponibilità e prezzo, e per mandarti una copia. La base giuridica è la risposta a una tua richiesta prima di un eventuale contratto (art. 6.1.b GDPR). Senza nome, email e date non possiamo inoltrare la richiesta.' ),
		array( 'p', '<strong>Dove finiscono:</strong> la richiesta parte via email verso la casa scelta e verso il tuo indirizzo. Il sito non la conserva nel database. L’email resta nella casella della casa per il tempo necessario a gestire la richiesta e l’eventuale soggiorno [da completare: periodo di conservazione indicato dall’ente, per esempio 24 mesi].' ),
		array( 'p', '<strong>Contro gli abusi:</strong> per limitare l’invio automatico di richieste conserviamo per 15 minuti un codice ricavato dal tuo indirizzo IP, poi viene cancellato.' ),

		array( 'h2', 'Navigazione del sito' ),
		array( 'p', '<strong>Registri del server:</strong> come ogni sito, il server registra indirizzo IP, pagina richiesta, data e browser, per sicurezza e per risolvere guasti. [da completare: per quanto tempo il fornitore di hosting conserva i log]' ),
		array( 'p', '<strong>Statistiche:</strong> contiamo le visite con Burst Statistics, installato sul nostro server: i dati non vengono condivisi con terzi e servono solo a capire quali pagine vengono lette. [da completare: verificare in Burst → Impostazioni se è attiva la modalità senza cookie e l’anonimizzazione dell’IP]' ),
		array( 'p', '<strong>Mappe:</strong> le mappe usano le tessere di OpenStreetMap. Per mostrarle il tuo browser si collega ai server della OpenStreetMap Foundation (Regno Unito, Paese con decisione di adeguatezza della Commissione europea), che riceve il tuo indirizzo IP. Informativa di OpenStreetMap: <a href="https://osmfoundation.org/wiki/Privacy_Policy">osmfoundation.org/wiki/Privacy_Policy</a>.' ),
		array( 'p', '<strong>Nessun servizio esterno di pubblicità o profilazione:</strong> caratteri e script sono caricati dal nostro server, senza Google Fonts, Google Analytics o pixel dei social.' ),

		array( 'h2', 'Chi altro vede i dati' ),
		array( 'p', 'I fornitori che ospitano il sito e la posta, come responsabili del trattamento: [da completare: nome del fornitore di hosting e del servizio email]. Non vendiamo né cediamo i dati ad altri.' ),

		array( 'h2', 'I tuoi diritti' ),
		array( 'p', 'Puoi chiedere di accedere ai tuoi dati, correggerli, cancellarli, limitarne il trattamento, opporti o riceverli in formato portabile (artt. 15–22 GDPR), scrivendo all’email per la privacy indicata sopra o direttamente alla casa a cui hai scritto. Puoi anche presentare reclamo al Garante per la protezione dei dati personali (<a href="https://www.garanteprivacy.it">garanteprivacy.it</a>).' ),
	);

	$html = '';
	foreach ( $blocchi as list( $tag, $testo ) ) {
		$html .= 'h2' === $tag
			? "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">{$testo}</h2>\n<!-- /wp:heading -->\n\n"
			: "<!-- wp:paragraph -->\n<p>{$testo}</p>\n<!-- /wp:paragraph -->\n\n";
	}
	return $html;
}
