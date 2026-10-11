<?php
/**
 * Testi originali per le schede che riprendevano alla lettera il sito della casa
 * (San Giuseppe a Caorle e Maria Mazzarello a Verbania): due pagine con lo stesso testo
 * si fanno concorrenza su Google e una delle due viene ignorata.
 *
 * Sui siti già popolati la sostituzione avviene una volta sola, e solo se introduzione e
 * testo sono ancora quelli importati (stessa impronta): ciò che è stato riscritto in
 * amministrazione non viene toccato. Gli stessi testi sono in _src/data.json per le nuove importazioni.
 */

defined( 'ABSPATH' ) || exit;

const FMA_TESTI_VERSIONE = 1;

function fma_testi_originali(): array {
	return array(
		'san-giuseppe-caorle' => array(
			'impronte' => array( '498014e74c1313864a897fee2060ed33', '65286fa3b05b7f5619e27e68fd3374b4' ),
			'intro'    => 'A Caorle, a pochi passi dal mare, le Figlie di Maria Ausiliatrice accolgono famiglie, gruppi e singoli in una casa semplice e familiare. Qui il ritmo rallenta: il verde, il mare e un clima sereno aiutano a ritrovare l’equilibrio.',
			'sezioni'  => array(
				array( 'titolo' => 'Come accogliamo', 'html' => '<ul><li><strong>Aperta a tutti.</strong> Chi arriva è accolto con rispetto, qualunque siano la sua storia, la sua cultura o la sua fede.</li><li><strong>Un clima di famiglia.</strong> Si parla, ci si ascolta, ci si conosce, anche in un soggiorno di pochi giorni.</li><li><strong>Tranquillità.</strong> Il verde e la vicinanza del mare invitano a vivere con più calma.</li><li><strong>Cura dei dettagli.</strong> Gli spazi sono tenuti con attenzione e chi lavora nella casa è sempre disponibile.</li></ul>' ),
			),
		),
		'casa-per-feriemaria-mazzarello' => array(
			'impronte' => array( '869f081eafa0c9489ed757475c272109', '85852d5d9d323c8ab74524e74ff97afa' ),
			'intro'    => 'Sulla collina di Zoverallo, sopra Verbania, la casa si affaccia sul Lago Maggiore da un grande parco. È un posto tranquillo per chi viaggia da solo, per i gruppi e per chi cerca qualche giorno di silenzio: camere curate, sale per gli incontri, cappelle e parcheggio privato.',
			'sezioni'  => array(
				array( 'titolo' => 'Ritiri e incontri durante l’anno', 'html' => '<p>La casa accoglie ritiri ed esercizi spirituali, sia dei gruppi che arrivano con un proprio programma sia proposti dalla comunità, con tempi di preghiera, ascolto e approfondimento. Chi viene semplicemente per riposare trova lo stesso clima raccolto.</p>' ),
				array( 'titolo' => 'Il parco e il belvedere', 'html' => '<p>Dal parco il lago resta sempre in vista, dalle prime ore del giorno fino al tramonto. Il belvedere, una terrazza naturale sul paesaggio, è il posto giusto per fermarsi un momento.</p><ul><li>Passeggiate e meditazione</li><li>Yoga all’aperto</li><li>Preghiera e silenzio</li><li>Lettura e studio</li><li>Incontri di gruppo all’aperto</li></ul>' ),
			),
		),
	);
}

/** Impronta di un testo: solo le parole, in minuscolo, senza HTML né punteggiatura. */
function fma_testo_impronta( string $html ): string {
	$t = mb_strtolower( html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES, 'UTF-8' ) );
	preg_match_all( '/[\p{L}\p{N}]+/u', $t, $m );
	return md5( implode( ' ', $m[0] ) );
}

add_action(
	'admin_init',
	function () {
		if ( (int) get_option( 'fma_testi_versione' ) >= FMA_TESTI_VERSIONE || ! class_exists( 'FMA_Importatore' ) ) {
			return;
		}
		foreach ( fma_testi_originali() as $slug => $t ) {
			$post = get_page_by_path( $slug, OBJECT, 'struttura' );
			if ( ! $post ) {
				continue;
			}
			$nuovo = array( 'ID' => $post->ID );
			if ( fma_testo_impronta( $post->post_excerpt ) === $t['impronte'][0] ) {
				$nuovo['post_excerpt'] = $t['intro'];
			}
			if ( fma_testo_impronta( $post->post_content ) === $t['impronte'][1] ) {
				$nuovo['post_content'] = FMA_Importatore::blocchi( $t['sezioni'] );
			}
			if ( count( $nuovo ) > 1 ) {
				wp_update_post( wp_slash( $nuovo ) );
			}
		}
		update_option( 'fma_testi_versione', FMA_TESTI_VERSIONE, true );
	}
);
