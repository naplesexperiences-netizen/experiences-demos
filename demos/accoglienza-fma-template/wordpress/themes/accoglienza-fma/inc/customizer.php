<?php
/**
 * Testi della home modificabili da Aspetto → Personalizza → "Home page".
 */

defined( 'ABSPATH' ) || exit;

function fma_testi_predefiniti(): array {
	return array(
		'testata'            => 'Case per ferie delle Figlie di Maria Ausiliatrice',
		'cta'                => 'Richiedi un soggiorno',
		'hero_titolo'        => 'Ti sentirai come a casa',
		'hero_testo'         => 'Otto case per ferie delle Figlie di Maria Ausiliatrice, dal Lago Maggiore al mare di Calabria. Per famiglie, gruppi e per chi cerca un tempo di pace.',
		'partner_titolo'     => 'Insieme a noi',
		'chi_titolo'         => 'Chi siamo',
		'chi_lede'           => 'Conosciute anche come salesiane di don Bosco, le Figlie di Maria Ausiliatrice sono una congregazione religiosa presente in tutto il mondo, nata nel 1872 dal carisma di san Giovanni Bosco e di santa Maria Domenica Mazzarello.',
		'chi_testo'          => 'Don Bosco scelse Maria come modello di donna capace di incarnare la “pedagogia del prendersi cura”. È lo stesso stile con cui apriamo le nostre case: accoglienza semplice, familiare, attenta alla persona. Qui soggiornano famiglie, gruppi parrocchiali, pellegrini e chiunque cerchi riposo, silenzio o un tempo di spiritualità.',
		'chi_citazione'      => 'Ogni struttura è unica, ma tutte condividono l’impegno per l’accoglienza, la cura e il benessere degli ospiti.',
		'chi_firma'          => 'Salesiane FMA',
		'chi_didascalia'     => 'Il cortile della casa FMA di Napoli',
		'chi_anno'           => '1872',
		'chi_anno_etichetta' => 'Anno di fondazione della congregazione',
		'strutture_titolo'   => 'Otto case, dal Lago Maggiore al mare di Calabria',
		'strutture_testo'    => 'Tutte gestite dalle Figlie di Maria Ausiliatrice. Scegli una casa sulla mappa o nell’elenco: ogni struttura risponde direttamente alla tua richiesta.',
		'passi_titolo'       => 'Come si prenota',
		'passo1_titolo'      => 'Scegli la casa',
		'passo1_testo'       => 'Cerca per regione o sulla mappa e apri la pagina della casa: foto, camere, servizi e posizione.',
		'passo2_titolo'      => 'Invia la richiesta',
		'passo2_testo'       => 'Nel modulo della casa indica date, persone e camera. È senza impegno e non serve registrarsi.',
		'passo3_titolo'      => 'Ti risponde la casa',
		'passo3_testo'       => 'La richiesta arriva direttamente alla struttura, che ti scrive con disponibilità e prezzo. A te arriva una copia via email.',
		'recensioni_titolo'  => 'Dicono di noi',
		'blog_titolo'        => 'Dal blog',
		'piede_nota'         => 'Ogni casa risponde direttamente alle richieste di soggiorno.',
	);
}

add_action( 'customize_register', 'fma_customizer' );

function fma_customizer( WP_Customize_Manager $wp ): void {
	$wp->add_section( 'fma_home', array( 'title' => 'Home page', 'priority' => 30, 'description' => 'Testi della home. Le strutture, i partner e gli articoli si gestiscono dalle rispettive voci del menu.' ) );

	$campi = array(
		'testata'            => array( 'Riga sopra il logo', 'text' ),
		'cta'                => array( 'Pulsante in testata', 'text' ),
		'hero_titolo'        => array( 'Titolo principale', 'text' ),
		'hero_testo'         => array( 'Testo sotto il titolo', 'textarea' ),
		'partner_titolo'     => array( 'Titolo dei partner', 'text' ),
		'chi_titolo'         => array( 'Chi siamo: titolo', 'text' ),
		'chi_lede'           => array( 'Chi siamo: introduzione', 'textarea' ),
		'chi_testo'          => array( 'Chi siamo: testo', 'textarea' ),
		'chi_citazione'      => array( 'Chi siamo: citazione', 'textarea' ),
		'chi_firma'          => array( 'Chi siamo: firma della citazione', 'text' ),
		'chi_didascalia'     => array( 'Chi siamo: didascalia della foto', 'text' ),
		'chi_anno'           => array( 'Chi siamo: anno', 'text' ),
		'chi_anno_etichetta' => array( 'Chi siamo: descrizione dell’anno', 'text' ),
		'strutture_titolo'   => array( 'Strutture: titolo', 'text' ),
		'strutture_testo'    => array( 'Strutture: testo', 'textarea' ),
		'passi_titolo'       => array( 'Come si prenota: titolo', 'text' ),
		'passo1_titolo'      => array( 'Passo 1: titolo', 'text' ),
		'passo1_testo'       => array( 'Passo 1: testo', 'textarea' ),
		'passo2_titolo'      => array( 'Passo 2: titolo', 'text' ),
		'passo2_testo'       => array( 'Passo 2: testo', 'textarea' ),
		'passo3_titolo'      => array( 'Passo 3: titolo', 'text' ),
		'passo3_testo'       => array( 'Passo 3: testo', 'textarea' ),
		'recensioni_titolo'  => array( 'Recensioni: titolo (la sezione compare solo se ci sono recensioni pubblicate)', 'text' ),
		'blog_titolo'        => array( 'Blog: titolo in home', 'text' ),
		'piede_nota'         => array( 'Nota a piè di pagina', 'text' ),
	);
	$predefiniti = fma_testi_predefiniti();
	foreach ( $campi as $chiave => $def ) {
		$wp->add_setting( 'fma_' . $chiave, array( 'default' => $predefiniti[ $chiave ], 'sanitize_callback' => 'textarea' === $def[1] ? 'sanitize_textarea_field' : 'sanitize_text_field' ) );
		$wp->add_control( 'fma_' . $chiave, array( 'label' => $def[0], 'section' => 'fma_home', 'type' => $def[1] ) );
		if ( 'chi_didascalia' === $chiave ) {
			$wp->add_setting( 'fma_chi_immagine', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
			$wp->add_control( new WP_Customize_Media_Control( $wp, 'fma_chi_immagine', array( 'label' => 'Chi siamo: foto', 'section' => 'fma_home', 'mime_type' => 'image' ) ) );
		}
	}
}
