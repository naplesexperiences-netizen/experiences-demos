<?php
/**
 * Importatore dei contenuti della demo (data.json + cartella img/), usato da WP-CLI
 * (`wp fma importa`) e dalla pagina Strumenti → Importa contenuti FMA.
 *
 * Si può rilanciare: aggiorna quello che trova (per slug) e non carica due volte la stessa immagine,
 * quindi un'importazione interrotta si completa rilanciandola.
 */

defined( 'ABSPATH' ) || exit;

class FMA_Importatore {

	/** @var string */
	private $cartella;

	/** @var callable */
	private $registro;

	/** @var array<string,int> */
	private $allegati = array();

	/**
	 * @param string        $cartella Cartella con le immagini (strutture/, blog/, loghi/); vuota = niente immagini.
	 * @param callable|null $registro function( string $messaggio, string $tipo ) con tipo 'info' o 'avviso'.
	 */
	public function __construct( string $cartella = '', ?callable $registro = null ) {
		$this->cartella = rtrim( $cartella, '/' );
		$this->registro = $registro ?? function () {};
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	/** Legge e controlla il file dati. */
	public static function leggi( string $file ): array {
		if ( ! is_readable( $file ) ) {
			throw new RuntimeException( "File non leggibile: $file" );
		}
		$dati = json_decode( (string) file_get_contents( $file ), true );
		if ( ! is_array( $dati ) || empty( $dati['strutture'] ) || ! is_array( $dati['strutture'] ) ) {
			throw new RuntimeException( 'Il file non contiene strutture.' );
		}
		return $dati;
	}

	/** Importa tutto in una volta (WP-CLI). Restituisce i conteggi. */
	public function importa( array $dati, bool $configura = false ): array {
		foreach ( $dati['strutture'] as $ordine => $s ) {
			$this->struttura( $s, $ordine );
			$this->log( "Struttura: {$s['nome']}" );
		}
		foreach ( $dati['partner'] ?? array() as $ordine => $p ) {
			$this->partner( $p, $ordine );
		}
		foreach ( $dati['articoli'] ?? array() as $ordine => $a ) {
			$this->articolo( $a, $ordine );
			$this->log( "Articolo: {$a['titolo']}" );
		}
		if ( $configura ) {
			$this->configura_sito();
		}
		return array(
			'strutture' => count( $dati['strutture'] ),
			'partner'   => count( $dati['partner'] ?? array() ),
			'articoli'  => count( $dati['articoli'] ?? array() ),
		);
	}

	private function log( string $messaggio, string $tipo = 'info' ): void {
		( $this->registro )( $messaggio, $tipo );
	}

	public function trova( string $tipo, string $slug ): int {
		$trovati = get_posts( array( 'post_type' => $tipo, 'name' => $slug, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
		return $trovati ? (int) $trovati[0] : 0;
	}

	/** Carica un file della cartella immagini una sola volta (riconosciuto dal percorso di origine). */
	private function allegato( string $relativo, int $genitore = 0, string $alt = '' ): int {
		if ( ! $this->cartella || ! $relativo ) {
			return 0;
		}
		if ( isset( $this->allegati[ $relativo ] ) ) {
			return $this->allegati[ $relativo ];
		}
		$esistente = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => '_fma_origine', 'meta_value' => $relativo ) );
		if ( $esistente ) {
			return $this->allegati[ $relativo ] = (int) $esistente[0];
		}
		// Solo file dentro la cartella delle immagini: un percorso con ../ nel file dati non esce di lì.
		$base     = realpath( $this->cartella );
		$percorso = realpath( $this->cartella . '/' . $relativo );
		if ( ! $base || ! $percorso || ! str_starts_with( $percorso, $base . DIRECTORY_SEPARATOR ) || ! is_file( $percorso ) ) {
			$this->log( "Immagine mancante: $relativo", 'avviso' );
			return 0;
		}
		$temporaneo = wp_tempnam( basename( $percorso ) );
		copy( $percorso, $temporaneo );
		$nome = sanitize_title( str_replace( '/', '-', preg_replace( '/\.[a-z]+$/', '', $relativo ) ) ) . '.' . pathinfo( $percorso, PATHINFO_EXTENSION );
		$id   = media_handle_sideload( array( 'name' => $nome, 'tmp_name' => $temporaneo ), $genitore );
		if ( is_wp_error( $id ) ) {
			$this->log( "Immagine non caricata ($relativo): " . $id->get_error_message(), 'avviso' );
			return 0;
		}
		update_post_meta( $id, '_fma_origine', $relativo );
		if ( $alt ) {
			update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		}
		return $this->allegati[ $relativo ] = (int) $id;
	}

	/** Converte le sezioni HTML della demo in blocchi Gutenberg (titoli, paragrafi, elenchi). */
	private function blocchi( array $sezioni ): string {
		$out = '';
		foreach ( $sezioni as $sez ) {
			$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $sez['titolo'] ) . "</h2>\n<!-- /wp:heading -->\n\n";
			preg_match_all( '#<(p|ul)>(.*?)</\1>#s', $sez['html'], $parti, PREG_SET_ORDER );
			foreach ( $parti as $p ) {
				if ( 'p' === $p[1] ) {
					$out .= "<!-- wp:paragraph -->\n<p>" . wp_kses_post( $p[2] ) . "</p>\n<!-- /wp:paragraph -->\n\n";
				} else {
					preg_match_all( '#<li>(.*?)</li>#s', $p[2], $voci );
					$out .= "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
					foreach ( $voci[1] as $voce ) {
						$out .= "<!-- wp:list-item -->\n<li>" . wp_kses_post( $voce ) . "</li>\n<!-- /wp:list-item -->";
					}
					$out .= "</ul>\n<!-- /wp:list -->\n\n";
				}
			}
		}
		return trim( $out );
	}

	public function struttura( array $s, int $ordine ): int {
		$post = array(
			'post_type'    => 'struttura',
			'post_status'  => 'publish',
			'post_title'   => $s['nome'],
			'post_name'    => $s['slug'],
			'post_excerpt' => $s['intro'] ?? '',
			'post_content' => $this->blocchi( $s['sezioni'] ?? array() ),
			'menu_order'   => $ordine,
		);
		$id = $this->trova( 'struttura', $s['slug'] );
		if ( $id ) {
			$post['ID'] = $id;
			wp_update_post( wp_slash( $post ) );
		} else {
			$id = wp_insert_post( wp_slash( $post ), true );
			if ( is_wp_error( $id ) ) {
				throw new RuntimeException( $id->get_error_message() );
			}
		}

		$c    = $s['contatti'] ?? array();
		$meta = array(
			'fma_titolo_hero'   => $s['titolo_hero'] ?? '',
			'fma_tipo'          => $s['tipo'] ?? '',
			'fma_localita'      => $s['localita'] ?? '',
			'fma_provincia'     => $s['provincia'] ?? '',
			'fma_indirizzo'     => $s['indirizzo'] ?? '',
			'fma_lat'           => $s['lat'] ?? '',
			'fma_lng'           => $s['lng'] ?? '',
			'fma_camere_totali' => $s['camere_totali'] ?? '',
			'fma_prezzo_da'     => $s['prezzo_da'] ?? '',
			'fma_tassa'         => $s['tassa'] ?? '',
			'fma_orari'         => $s['orari'] ?? '',
			'fma_booking_url'   => $s['booking_url'] ?? '',
			'fma_in_slider'     => true,
			'fma_referente'     => $c['nome'] ?? '',
			'fma_email'         => $c['email'] ?? '',
			'fma_telefono'      => $c['telefono'] ?? '',
			'fma_cellulare'     => $c['cellulare'] ?? '',
			'fma_whatsapp'      => $c['whatsapp'] ?? '',
			'fma_sito'          => $c['sito'] ?? '',
		);
		foreach ( $meta as $k => $v ) {
			if ( null === $v || '' === $v ) {
				delete_post_meta( $id, $k );
			} else {
				update_post_meta( $id, $k, $v );
			}
		}

		$slug = $s['slug'];
		$foto = $this->allegato( "strutture/$slug/hero.webp", $id, $s['nome'] );
		if ( $foto ) {
			set_post_thumbnail( $id, $foto );
		}
		$galleria = array();
		foreach ( $s['gallery'] ?? array() as $n => $g ) {
			$galleria[ $g ] = $this->allegato( "strutture/$slug/$g.webp", $id, $s['nome'] . ', foto ' . ( $n + 2 ) );
		}
		update_post_meta( $id, 'fma_galleria', array_values( array_filter( $galleria ) ) );
		if ( ! empty( $c['logo'] ) ) {
			update_post_meta( $id, 'fma_logo', $this->allegato( $c['logo'], $id, $c['nome'] ?? '' ) );
		}

		$camere = array();
		foreach ( $s['camere'] ?? array() as $r ) {
			$camere[] = array(
				'nome'      => $r['nome'],
				'dettaglio' => $r['dettaglio'] ?? '',
				'letti'     => $r['letti'] ?? '',
				'ospiti'    => $r['ospiti'] ?? '',
				'quante'    => $r['quante'] ?? '',
				'immagine'  => ! empty( $r['img'] ) ? ( $galleria[ $r['img'] ] ?? 0 ) : '',
			);
		}
		update_post_meta( $id, 'fma_camere', $camere );
		update_post_meta( $id, 'fma_dintorni', array_map( fn( $d ) => array( 'luogo' => $d['luogo'], 'distanza' => $d['distanza'] ), $s['dintorni'] ?? array() ) );
		update_post_meta( $id, 'fma_regole', array_values( $s['regole'] ?? array() ) );

		if ( ! empty( $s['regione'] ) ) {
			wp_set_object_terms( $id, $s['regione'], 'regione' );
		}
		wp_set_object_terms( $id, $s['servizi'] ?? array(), 'servizio' );
		return (int) $id;
	}

	public function partner( array $p, int $ordine ): void {
		$slug = sanitize_title( $p['nome'] );
		$post = array( 'post_type' => 'partner', 'post_status' => 'publish', 'post_title' => $p['nome'], 'post_name' => $slug, 'menu_order' => $ordine );
		$id   = $this->trova( 'partner', $slug );
		if ( $id ) {
			$post['ID'] = $id;
			wp_update_post( wp_slash( $post ) );
		} else {
			$id = wp_insert_post( wp_slash( $post ) );
		}
		$logo = $this->allegato( $p['logo'], $id, $p['nome'] );
		if ( $logo ) {
			set_post_thumbnail( $id, $logo );
		}
		if ( ! empty( $p['url'] ) ) {
			update_post_meta( $id, 'fma_url', esc_url_raw( $p['url'] ) );
		}
	}

	/** $ordine: posizione nel file. A parità di giorno, il primo articolo del file risulta il più recente. */
	public function articolo( array $a, int $ordine = 0 ): void {
		$categoria = term_exists( $a['categoria'], 'category' ) ?: wp_insert_term( $a['categoria'], 'category' );
		$post      = array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_title'    => $a['titolo'],
			'post_name'     => $a['slug'],
			'post_content'  => $a['html'],
			'post_excerpt'  => $a['estratto'],
			'post_date'     => gmdate( 'Y-m-d H:i:s', strtotime( $a['data'] . ' 12:00:00 UTC' ) - 60 * $ordine ),
			'post_category' => is_array( $categoria ) ? array( (int) $categoria['term_id'] ) : array(),
		);
		$id = $this->trova( 'post', $a['slug'] );
		if ( $id ) {
			$post['ID'] = $id;
			wp_update_post( wp_slash( $post ) );
		} else {
			$id = wp_insert_post( wp_slash( $post ) );
		}
		$foto = $this->allegato( "blog/{$a['slug']}.webp", $id, $a['titolo'] );
		if ( $foto ) {
			set_post_thumbnail( $id, $foto );
		}
		$casa = ! empty( $a['casa'] ) ? $this->trova( 'struttura', $a['casa'] ) : 0;
		if ( $casa ) {
			update_post_meta( $id, 'fma_struttura_vicina', $casa );
		}
	}

	public function configura_sito(): void {
		// Una pagina «home» o «blog» già presente ma in bozza, privata o programmata darebbe 404
		// ai visitatori una volta impostata come pagina iniziale: si pubblica.
		$pagina = function ( string $titolo, string $slug ) {
			$id = $this->trova( 'page', $slug );
			if ( ! $id ) {
				return (int) wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $titolo, 'post_name' => $slug ) );
			}
			$stato = (string) get_post_status( $id );
			if ( 'publish' !== $stato ) {
				wp_update_post( array( 'ID' => $id, 'post_status' => 'publish', 'post_password' => '' ) );
				$this->log( "Pagina «{$titolo}» pubblicata (era in stato: {$stato})" );
			}
			return $id;
		};
		$home = $pagina( 'Home', 'home' );
		$blog = $pagina( 'Blog', 'blog' );
		if ( ! $home || ! $blog ) {
			throw new RuntimeException( 'Non riesco a creare le pagine Home e Blog.' );
		}
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home );
		update_option( 'page_for_posts', $blog );
		update_option( 'posts_per_page', 9 );
		// set_permalink_structure aggiorna anche $wp_rewrite: con il solo update_option le regole
		// verrebbero rigenerate con i permalink vecchi e il .htaccess non verrebbe scritto.
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		update_option( 'blogdescription', 'Ti sentirai come a casa' );
		update_option( 'default_comment_status', 'closed' );

		// Contenuti di esempio di WordPress: via, ma solo se nessuno li ha mai modificati.
		foreach ( array( 'post' => array( 'hello-world', 'ciao-mondo' ), 'page' => array( 'sample-page', 'pagina-di-esempio' ) ) as $tipo => $slugs ) {
			foreach ( $slugs as $slug ) {
				$id = $this->trova( $tipo, $slug );
				if ( $id && get_post_field( 'post_date_gmt', $id ) === get_post_field( 'post_modified_gmt', $id ) ) {
					wp_delete_post( $id, true );
					$this->log( "Rimosso il contenuto di esempio: $slug" );
				}
			}
		}

		$menu = wp_get_nav_menu_object( 'Principale' );
		$menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( 'Principale' );
		if ( ! $menu || ! wp_get_nav_menu_items( $menu_id ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Strutture', 'menu-item-url' => home_url( '/strutture/' ), 'menu-item-status' => 'publish' ) );
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Chi siamo', 'menu-item-url' => home_url( '/#chi-siamo' ), 'menu-item-status' => 'publish' ) );
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Blog', 'menu-item-object' => 'page', 'menu-item-object-id' => $blog, 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
		$posizioni             = get_theme_mod( 'nav_menu_locations', array() );
		$posizioni['primario'] = $menu_id;
		$posizioni['piede']    = $menu_id;
		set_theme_mod( 'nav_menu_locations', $posizioni );

		$logo = $this->allegato( 'loghi/accoglienza-fma.png', 0, 'Accoglienza delle Salesiane' );
		if ( $logo ) {
			set_theme_mod( 'custom_logo', $logo );
		}
		$chi = get_posts( array( 'post_type' => 'attachment', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => '_fma_origine', 'meta_value' => 'strutture/fma-napoli/hero.webp' ) );
		if ( $chi ) {
			set_theme_mod( 'fma_chi_immagine', (int) $chi[0] );
		}
		flush_rewrite_rules( true ); // true: aggiorna anche il .htaccess, se il server lo usa e il file è scrivibile
		$this->log( 'Sito configurato: Home, Blog, menu “Principale”, permalink, logo.' );
	}
}
