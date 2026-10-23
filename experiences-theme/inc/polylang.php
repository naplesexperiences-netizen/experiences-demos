<?php
/**
 * Integrazione con Polylang.
 *
 * Il tema non gestisce il routing per lingua: lo fa Polylang. Qui ci
 * sono le due cose che Polylang da solo non puo' sapere:
 *
 *  1. quali pagine inglesi creare e con che contenuto;
 *  2. quale articolo italiano corrisponde a quale articolo inglese.
 *
 * Il punto 2 e' il lavoro vero. Dopo un import WXR gli articoli sono
 * tutti li' ma scollegati: senza accoppiarli Polylang non sa che
 * /blog/channel-manager-hotel-guida-completa-2026/ e
 * /en/blog/channel-manager-hotels-complete-guide-2026/ sono la stessa
 * cosa, quindi niente hreflang e il selettore di lingua manda alla
 * home invece che all'articolo corrispondente. Accoppiarli a mano in
 * bacheca sono una trentina di operazioni, ognuna con la sua
 * possibilita' di sbagliare riga.
 *
 * Tutto quello che c'e' qui gira solo se Polylang e' attivo e solo per
 * un amministratore loggato. Senza Polylang il file non fa nulla.
 *
 * @package experiences-srl
 */

defined( 'ABSPATH' ) || exit;

/**
 * Polylang c'e' e ha almeno due lingue configurate?
 */
function experiences_polylang_pronto() {
	if ( ! function_exists( 'pll_languages_list' ) || ! function_exists( 'pll_set_post_language' ) ) {
		return false;
	}
	$lingue = pll_languages_list();
	return is_array( $lingue ) && count( $lingue ) >= 2;
}

/**
 * Codici lingua usati dal tema: italiano di partenza, inglese di arrivo.
 *
 * Si leggono da Polylang invece di darli per scontati, perche' chi
 * configura puo' aver scelto "en-gb" o "eng" come slug.
 */
function experiences_lingue_codici() {
	$codici = [ 'it' => '', 'en' => '' ];
	if ( ! function_exists( 'pll_languages_list' ) ) {
		return $codici;
	}
	foreach ( (array) pll_languages_list() as $slug ) {
		$iniziale = strtolower( substr( $slug, 0, 2 ) );
		if ( 'it' === $iniziale && '' === $codici['it'] ) {
			$codici['it'] = $slug;
		}
		if ( 'en' === $iniziale && '' === $codici['en'] ) {
			$codici['en'] = $slug;
		}
	}
	return apply_filters( 'experiences_lingue_codici', $codici );
}

/**
 * Le pagine inglesi da creare, in coppia con la rispettiva italiana.
 */
function experiences_pagine_en() {
	$pagine = [
		// Gli slug inglesi sono volutamente DIVERSI da quelli
		// italiani, non una loro traduzione letterale. Polylang
		// saprebbe gestire due pagine con lo stesso slug in lingue
		// diverse, ma solo se la lingua e' nota al momento
		// dell'inserimento: qui la assegniamo dopo, e WordPress
		// avrebbe gia' aggiunto un "-2". Meglio /en/privacy/ che
		// /en/privacy-policy-2/. Chi in futuro volesse allinearli
		// deve prima spostare pll_set_post_language() prima di
		// wp_insert_post(), che non e' possibile.
		'privacy-policy' => [
			'slug_en'  => 'privacy',
			'title_en' => 'Privacy Policy',
			'corpo'    => 'experiences_legal_template_privacy_en',
		],
		'cookie-policy' => [
			'slug_en'  => 'cookies',
			'title_en' => 'Cookie Policy',
			'corpo'    => 'experiences_legal_template_cookie_en',
		],
		'termini-e-condizioni' => [
			'slug_en'  => 'terms-and-conditions',
			'title_en' => 'Terms and Conditions',
			'corpo'    => 'experiences_legal_template_terms_en',
		],
		// Queste due non hanno corpo: il contenuto lo stampa un
		// template (page-configuratore.php, archive.php), che e' gia'
		// tradotto via gettext. Serve solo che la pagina esista nella
		// lingua giusta, altrimenti Polylang non ha dove mandare il
		// selettore.
		'configuratore' => [
			'slug_en'  => 'preview',
			'title_en' => 'Free website preview',
			'corpo'    => '',
		],
		'blog' => [
			'slug_en'  => 'guides',
			'title_en' => 'Guides & insights',
			'corpo'    => '',
		],
	];
	return apply_filters( 'experiences_pagine_en', $pagine );
}

/**
 * Crea le pagine inglesi mancanti e le collega alle italiane.
 *
 * @return array Resoconto: create, collegate, saltate.
 */
function experiences_crea_pagine_en() {
	$esito = [ 'create' => [], 'collegate' => [], 'saltate' => [] ];

	if ( ! experiences_polylang_pronto() ) {
		return $esito;
	}
	$codici = experiences_lingue_codici();
	if ( '' === $codici['it'] || '' === $codici['en'] ) {
		return $esito;
	}

	foreach ( experiences_pagine_en() as $slug_it => $cfg ) {
		$pagina_it = get_page_by_path( $slug_it );
		if ( ! $pagina_it ) {
			$esito['saltate'][] = $slug_it . ' (manca la pagina italiana)';
			continue;
		}

		// La pagina italiana potrebbe non avere ancora una lingua
		// assegnata: senza, Polylang non accetta la coppia.
		if ( ! pll_get_post_language( $pagina_it->ID ) ) {
			pll_set_post_language( $pagina_it->ID, $codici['it'] );
		}

		$gia = pll_get_post( $pagina_it->ID, $codici['en'] );
		if ( $gia ) {
			$esito['saltate'][] = $slug_it . ' (traduzione gia presente)';
			continue;
		}

		$pagina_en = get_page_by_path( $cfg['slug_en'] );
		if ( ! $pagina_en ) {
			$corpo = ( $cfg['corpo'] && function_exists( $cfg['corpo'] ) )
				? call_user_func( $cfg['corpo'] )
				: '';

			$id_en = wp_insert_post( [
				'post_title'   => $cfg['title_en'],
				'post_name'    => $cfg['slug_en'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => $corpo,
				'post_author'  => $pagina_it->post_author,
			] );
			if ( ! $id_en || is_wp_error( $id_en ) ) {
				$esito['saltate'][] = $slug_it . ' (creazione fallita)';
				continue;
			}
			// Stesso modello di pagina dell'italiana, se ne ha uno:
			// e' quello che fa funzionare il configuratore.
			$modello = get_post_meta( $pagina_it->ID, '_wp_page_template', true );
			if ( $modello && 'default' !== $modello ) {
				update_post_meta( $id_en, '_wp_page_template', $modello );
			}
			$esito['create'][] = $cfg['slug_en'];
		} else {
			$id_en = $pagina_en->ID;
		}

		pll_set_post_language( $id_en, $codici['en'] );
		pll_save_post_translations( [
			$codici['it'] => $pagina_it->ID,
			$codici['en'] => $id_en,
		] );
		$esito['collegate'][] = $slug_it . ' ⇄ ' . $cfg['slug_en'];
	}

	return $esito;
}

/**
 * Coppie articolo italiano ⇄ articolo inglese, per slug.
 *
 * Le chiavi sono gli slug italiani, i valori quelli inglesi. La mappa
 * copre i 22 articoli del repo piu' i 10 forniti gia' bilingui. Un
 * articolo che non e' in elenco non viene toccato.
 */
function experiences_mappa_traduzioni_articoli() {
	$mappa = [
		// ── I 22 del repo ─────────────────────────────────────────
		'channel-manager-hotel-guida-completa-2026'            => 'channel-manager-hotels-complete-guide-2026',
		'come-evitare-overbooking-hotel'                       => 'how-to-avoid-overbooking-hotels',
		'seo-locale-hotel-top-google'                          => 'local-seo-hotels-top-google',
		'da-5-a-20-prenotazioni-mese-agenzia-tour'             => 'from-5-to-20-bookings-month-tour-agency',
		'vendere-tour-online-getyourguide-viator-sito-proprio' => 'selling-tours-online-getyourguide-viator-own-site',
		'channel-manager-tour-operator'                        => 'channel-manager-tour-operators',
		'ai-chatbot-hotel-bnb-segretario-digitale-24-7'        => 'ai-chatbot-hotels-bnb-digital-front-desk',
		'dynamic-pricing-ai-hotel-revpar'                      => 'dynamic-pricing-ai-hotels-revpar',
		'turismo-sostenibile-hotel-certificazioni-green-2026'  => 'sustainable-tourism-hotels-green-certifications-2026',
		'smart-hotel-check-in-keyless-iot-guida-2026'          => 'smart-hotel-keyless-check-in-iot-guide-2026',
		'vr-ar-tour-operator-esperienze-immersive'             => 'vr-ar-tour-operators-immersive-experiences',
		'voice-search-hotel-ottimizzazione-alexa-google-assistant' => 'voice-search-hotels-alexa-google-assistant',
		'aumentare-prenotazioni-dirette-hotel-disintermediazione'  => 'increase-direct-bookings-hotels-disintermediation',
		'gestire-recensioni-online-hotel-booking-tripadvisor'  => 'managing-online-reviews-hotels-booking-tripadvisor',
		'email-marketing-hotel-pre-stay-post-stay'             => 'email-marketing-hotels-pre-stay-post-stay',
		'metasearch-google-hotel-ads-trivago-tripadvisor'      => 'metasearch-google-hotel-ads-trivago-tripadvisor-en',
		'whatsapp-business-hotel-tour-operator'                => 'whatsapp-business-hotels-tour-operators-guide',
		'instagram-tiktok-hotel-strutture-ricettive-ugc'       => 'instagram-tiktok-hotels-accommodation-ugc',
		'farsi-trovare-chatgpt-assistenti-ai-hotel'            => 'getting-found-chatgpt-ai-assistants-hotels',
		'cin-regolamento-ue-affitti-brevi-annunci'             => 'cin-eu-regulation-short-term-rentals-listings',
		'accessibilita-sito-hotel-eaa-esenzione-microimprese'  => 'website-accessibility-hotels-eaa-microenterprise-exemption',
		'banner-cookie-hotel-consenso-dati-analytics'          => 'cookie-banner-hotels-consent-analytics-data',

		// ── I 10 forniti gia' in entrambe le lingue ───────────────
		'chatbot-ai-hotel-agenzie-viaggio'          => 'ai-chatbot-hotels-travel-agencies',
		'prenotazioni-dirette-vs-ota-commissioni'   => 'direct-bookings-vs-ota-commissions',
		'seo-ricerca-ai-turismo-hotel-agenzie'      => 'seo-ai-search-tourism-hotels-agencies',
		'recensioni-online-hotel-tour-operator'     => 'online-reviews-hotels-tour-operators',
		'whatsapp-business-hotel-tour-operator-2'   => 'whatsapp-business-hotels-tour-operators',
		'upselling-digitale-turismo-pre-arrivo'     => 'digital-upselling-tourism-pre-arrival',
		'email-marketing-crm-turismo'               => 'email-marketing-crm-tourism',
		'google-business-profile-turismo'           => 'google-business-profile-tourism',
		'pagamenti-online-caparre-no-show'          => 'online-payments-deposits-no-shows',
		'destagionalizzazione-turismo-napoli-campania' => 'off-season-tourism-naples-campania',
	];
	return apply_filters( 'experiences_mappa_traduzioni_articoli', $mappa );
}

/**
 * Assegna la lingua e collega le coppie di articoli.
 *
 * @param bool $forza Se true rifa' anche le coppie gia' collegate.
 * @return array Resoconto.
 */
function experiences_collega_articoli( $forza = false ) {
	$esito = [ 'collegati' => [], 'mancanti' => [], 'gia_fatti' => [] ];

	if ( ! experiences_polylang_pronto() ) {
		return $esito;
	}
	$codici = experiences_lingue_codici();
	if ( '' === $codici['it'] || '' === $codici['en'] ) {
		return $esito;
	}

	foreach ( experiences_mappa_traduzioni_articoli() as $slug_it => $slug_en ) {
		$post_it = get_page_by_path( $slug_it, OBJECT, 'post' );
		$post_en = get_page_by_path( $slug_en, OBJECT, 'post' );

		if ( ! $post_it && ! $post_en ) {
			continue;   // nessuno dei due importato: niente da dire
		}
		if ( ! $post_it || ! $post_en ) {
			$esito['mancanti'][] = $post_it ? $slug_en : $slug_it;
			continue;
		}

		if ( ! $forza && pll_get_post( $post_it->ID, $codici['en'] ) ) {
			$esito['gia_fatti'][] = $slug_it;
			continue;
		}

		pll_set_post_language( $post_it->ID, $codici['it'] );
		pll_set_post_language( $post_en->ID, $codici['en'] );
		pll_save_post_translations( [
			$codici['it'] => $post_it->ID,
			$codici['en'] => $post_en->ID,
		] );
		$esito['collegati'][] = $slug_it . ' ⇄ ' . $slug_en;
	}

	return $esito;
}

/**
 * Pulsante in bacheca: Strumenti → Collega traduzioni.
 *
 * Non gira da solo a ogni caricamento: l'import degli articoli si fa
 * una volta, e una scansione di trenta slug a ogni admin_init sarebbe
 * lavoro sprecato per sempre.
 */
function experiences_menu_traduzioni() {
	if ( ! experiences_polylang_pronto() ) {
		return;
	}
	add_management_page(
		__( 'Collega traduzioni', 'experiences-srl' ),
		__( 'Collega traduzioni', 'experiences-srl' ),
		'manage_options',
		'experiences-traduzioni',
		'experiences_pagina_traduzioni'
	);
}
add_action( 'admin_menu', 'experiences_menu_traduzioni' );

function experiences_pagina_traduzioni() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$esito_pagine = null;
	$esito_post   = null;

	if ( isset( $_POST['experiences_collega'] )
		&& check_admin_referer( 'experiences_collega_traduzioni' ) ) {
		$forza        = ! empty( $_POST['forza'] );
		$esito_pagine = experiences_crea_pagine_en();
		$esito_post   = experiences_collega_articoli( $forza );
	}

	$mappa = experiences_mappa_traduzioni_articoli();
	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Collega traduzioni', 'experiences-srl' ) . '</h1>';
	echo '<p>' . esc_html__( 'Crea le pagine inglesi mancanti e accoppia ogni articolo italiano con la sua versione inglese, usando gli slug. Si lancia dopo aver importato gli articoli: rifarlo non fa danni.', 'experiences-srl' ) . '</p>';

	if ( null !== $esito_post ) {
		echo '<div class="notice notice-success"><p><strong>'
			. esc_html__( 'Fatto.', 'experiences-srl' ) . '</strong></p>';
		foreach ( [
			__( 'Pagine create', 'experiences-srl' )       => $esito_pagine['create'],
			__( 'Pagine collegate', 'experiences-srl' )    => $esito_pagine['collegate'],
			__( 'Articoli collegati', 'experiences-srl' )  => $esito_post['collegati'],
			__( 'Gia collegati', 'experiences-srl' )       => $esito_post['gia_fatti'],
			__( 'Senza corrispondenza', 'experiences-srl' ) => $esito_post['mancanti'],
		] as $titolo => $voci ) {
			if ( ! $voci ) {
				continue;
			}
			echo '<p><strong>' . esc_html( $titolo ) . ' (' . count( $voci ) . ')</strong><br>'
				. esc_html( implode( ', ', $voci ) ) . '</p>';
		}
		echo '</div>';
	}

	echo '<form method="post">';
	wp_nonce_field( 'experiences_collega_traduzioni' );
	echo '<p><label><input type="checkbox" name="forza" value="1"> '
		. esc_html__( 'Rifai anche le coppie gia collegate', 'experiences-srl' )
		. '</label></p>';
	submit_button( __( 'Collega adesso', 'experiences-srl' ), 'primary', 'experiences_collega' );
	echo '</form>';

	echo '<h2>' . esc_html__( 'Coppie previste', 'experiences-srl' ) . ' (' . count( $mappa ) . ')</h2>';
	echo '<table class="widefat striped"><thead><tr><th>Italiano</th><th>English</th><th>'
		. esc_html__( 'Stato', 'experiences-srl' ) . '</th></tr></thead><tbody>';
	foreach ( $mappa as $slug_it => $slug_en ) {
		$it = get_page_by_path( $slug_it, OBJECT, 'post' );
		$en = get_page_by_path( $slug_en, OBJECT, 'post' );
		if ( $it && $en ) {
			$stato = esc_html__( 'tutti e due presenti', 'experiences-srl' );
		} elseif ( $it ) {
			$stato = esc_html__( 'manca l inglese', 'experiences-srl' );
		} elseif ( $en ) {
			$stato = esc_html__( 'manca l italiano', 'experiences-srl' );
		} else {
			$stato = esc_html__( 'nessuno dei due importato', 'experiences-srl' );
		}
		printf(
			'<tr><td><code>%s</code></td><td><code>%s</code></td><td>%s</td></tr>',
			esc_html( $slug_it ),
			esc_html( $slug_en ),
			$stato
		);
	}
	echo '</tbody></table></div>';
}
