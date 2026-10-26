<?php
/**
 * Testo alternativo delle foto delle case: cosa mostra la foto, non «Nome, foto 2».
 * Serve a chi usa un lettore di schermo e a Google Immagini.
 *
 * Sui siti già popolati si applica una volta sola e solo alle foto con il testo generico
 * dell'importazione (o senza testo): quello scritto nella Libreria media resta com'è.
 */

defined( 'ABSPATH' ) || exit;

const FMA_ALT_VERSIONE = 1;

/** @return array<string, array<string, string>> slug => [ nome del file senza estensione => alt ] */
function fma_alt_foto(): array {
	return array(
		'villa-tiberiade'                => array(
			'hero' => 'Villa Tiberiade: la facciata con le terrazze e il prato davanti alla casa',
			'g1'   => 'Camera singola con scrivania',
			'g2'   => 'Camera singola con armadio',
			'g3'   => 'Sala convegni con file di sedie',
			'g4'   => 'Villa Tiberiade vista dal mare, sulla costa di Torre Annunziata',
			'g5'   => 'Il prato della villa affacciato sul Golfo di Napoli',
			'g6'   => 'La facciata della villa vista dal giardino',
			'g7'   => 'Sposi sulla terrazza a picco sul mare',
		),
		'villa-crawford'                 => array(
			'hero' => 'Villa Crawford: il giardino con le palme e la fontana',
			'g1'   => 'Camera con letto matrimoniale e letto singolo, pavimento in legno',
			'g2'   => 'La cappella della villa',
			'g3'   => 'La reception con le boiserie in legno intagliato',
			'g4'   => 'Sala riunioni con tavolo e finestre in legno',
			'g5'   => 'Vista dalla villa sul Golfo di Napoli e sul Vesuvio',
		),
		'fma-napoli'                     => array(
			'hero' => 'Il chiostro porticato della casa FMA di Napoli',
			'g1'   => 'Il pozzo al centro del chiostro',
			'g2'   => 'Il giardino del chiostro tra i portici',
			'g3'   => 'Camera matrimoniale',
			'g4'   => 'Corridoio della casa con pavimento in maiolica',
			'g5'   => 'Statuetta della Sacra Famiglia davanti alla libreria',
		),
		'fma-roma'                       => array(
			'hero' => 'L’edicola con la statua di Maria Ausiliatrice nel cortile della casa di via Marghera',
			'g1'   => 'Camera singola',
			'g2'   => 'Camera singola con scrivania e asciugamani',
			'g3'   => 'L’ingresso con la scala in ferro battuto',
			'g4'   => 'Scala interna con montascale',
			'g5'   => 'Camera con finestra e scrivania',
			'g6'   => 'Quadro della Madonna e fiori nel corridoio',
			'g7'   => 'Camera singola con biancheria sul letto',
		),
		'san-giuseppe-caorle'            => array(
			'hero' => 'Caorle dall’alto: la spiaggia, il porto e il santuario sul mare',
			'g1'   => 'Camera quadrupla con letti singoli',
			'g2'   => 'Camera singola con biancheria sul letto',
			'g3'   => 'Camera tripla con letto matrimoniale e letto singolo',
			'g4'   => 'Bagno con doccia',
			'g5'   => 'Bambini che giocano a pallone sulla spiaggia di Caorle',
			'g6'   => 'Le calli colorate del centro storico di Caorle',
			'g7'   => 'Il santuario della Madonna dell’Angelo al tramonto',
		),
		'villa-tabor'                    => array(
			'hero' => 'Villa Tabor tra i boschi di Cesuna, con la cappella in pietra',
			'g1'   => 'L’ingresso di Villa Tabor con la scala esterna',
			'g2'   => 'La facciata in pietra della cappella con il monogramma di Cristo',
			'g3'   => 'I tetti della cappella e i boschi dell’Altopiano di Asiago',
			'g4'   => 'Scala d’ingresso sotto il portico in legno',
			'g5'   => 'La facciata della casa',
			'g6'   => 'Il cortile tra la casa e la cappella',
			'g7'   => 'Villa Tabor e il prato davanti alla casa',
		),
		'casa-per-feriemaria-mazzarello' => array(
			'hero' => 'La Casa per Ferie Maria Mazzarello con il piazzale e le palme',
			'g1'   => 'Il Lago Maggiore e una cittadina sulla riva al tramonto',
			'g2'   => 'Una mano sfiora l’erba di un prato al tramonto',
			'g3'   => 'Veranda luminosa con poltroncine in vimini',
			'g4'   => 'Il Lago Maggiore visto dall’alto tra le montagne',
			'g5'   => 'Sala da pranzo con tavoli in legno',
			'g6'   => 'Piccola cappella con panche in legno',
			'g7'   => 'L’eremo di Santa Caterina del Sasso sul Lago Maggiore',
		),
		'fma-soverato'                   => array(
			'hero' => 'Il campo da gioco della casa e il mare di Soverato',
			'g1'   => 'Camera doppia con due letti singoli',
			'g2'   => 'Camera con letto matrimoniale, letto singolo e balcone sul mare',
			'g3'   => 'Camera con porta finestra sul balcone',
			'g4'   => 'Il mare di Soverato visto dalla casa',
			'g5'   => 'Bagno della camera',
		),
	);
}

/** Alt previsto per una foto importata, dal suo percorso «strutture/<slug>/<nome>.webp». */
function fma_alt_per_origine( string $origine ): string {
	if ( ! preg_match( '#^strutture/([^/]+)/([^/.]+)\.#', $origine, $m ) ) {
		return '';
	}
	return fma_alt_foto()[ $m[1] ][ $m[2] ] ?? '';
}

add_action(
	'admin_init',
	function () {
		if ( (int) get_option( 'fma_alt_versione' ) >= FMA_ALT_VERSIONE ) {
			return;
		}
		$foto = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1, 'fields' => 'ids', 'meta_key' => '_fma_origine', 'meta_compare' => 'LIKE', 'meta_value' => 'strutture/' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		foreach ( $foto as $id ) {
			$alt   = fma_alt_per_origine( (string) get_post_meta( $id, '_fma_origine', true ) );
			$prima = trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) );
			if ( $alt && ( '' === $prima || preg_match( '/,\s*foto\s*\d+$/u', $prima ) || $prima === get_the_title( (int) wp_get_post_parent_id( $id ) ) ) ) {
				update_post_meta( $id, '_wp_attachment_image_alt', $alt );
			}
		}
		update_option( 'fma_alt_versione', FMA_ALT_VERSIONE, true );
	}
);
