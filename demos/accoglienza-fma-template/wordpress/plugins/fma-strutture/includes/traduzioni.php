<?php
/**
 * Traduzioni dei contenuti con Polylang: case, articoli, pagine Home e Blog, categorie, regioni,
 * servizi e il motto del sito, dai file traduzioni/<lingua>.json del plugin.
 *
 * Le versioni tradotte sono collegate a quelle italiane (stesso gruppo di traduzioni in Polylang).
 * I contenuti italiani non vengono mai modificati. Si può rilanciare: le traduzioni già presenti
 * vengono aggiornate con il testo dei file, quelle nuove create. Foto, coordinate, contatti ed
 * email per le richieste si copiano dalla versione italiana.
 */

defined( 'ABSPATH' ) || exit;

class FMA_Traduttore {

	/** @var callable */
	private $registro;

	/** @var array<string, array> */
	private static $cache = array();

	public function __construct( ?callable $registro = null ) {
		$this->registro = $registro ?: function () {};
	}

	private function log( string $messaggio, string $tipo = 'info' ): void {
		( $this->registro )( $messaggio, $tipo );
	}

	public static function cartella(): string {
		return FMA_STRUTTURE_DIR . 'traduzioni/';
	}

	/** Polylang attivo? Altrimenti il motivo. */
	public static function problema(): string {
		if ( ! function_exists( 'pll_languages_list' ) || ! function_exists( 'pll_save_post_translations' ) ) {
			return 'Polylang non è attivo: installalo e attivalo, poi aggiungi le lingue in Lingue → Lingue.';
		}
		return '';
	}

	/** File di traduzione disponibili, per nome (es. «en»). */
	private static function file(): array {
		return array_map( fn( $f ) => basename( $f, '.json' ), (array) glob( self::cartella() . '*.json' ) );
	}

	/**
	 * Lingue Polylang con un file di traduzione, slug => nome del file. Il file si cerca per slug
	 * («zh») e, se lo slug è stato cambiato, per le prime lettere della locale («zh_CN» → zh.json).
	 */
	private static function abbinamenti(): array {
		if ( self::problema() ) {
			return array();
		}
		$file   = self::file();
		$slug   = (array) pll_languages_list( array( 'fields' => 'slug' ) );
		$locale = (array) pll_languages_list( array( 'fields' => 'locale' ) );
		$esito  = array();
		foreach ( $slug as $i => $s ) {
			$prefisso = strtolower( substr( (string) ( $locale[ $i ] ?? '' ), 0, 2 ) );
			if ( in_array( $s, $file, true ) ) {
				$esito[ $s ] = $s;
			} elseif ( $prefisso && in_array( $prefisso, $file, true ) ) {
				$esito[ $s ] = $prefisso;
			}
		}
		return $esito;
	}

	/** Lingue con un file di traduzione E presenti in Polylang (slug, es. «en»). */
	public static function lingue(): array {
		return array_keys( self::abbinamenti() );
	}

	/** Lingue con un file ma non ancora aggiunte in Polylang. */
	public static function lingue_mancanti(): array {
		if ( self::problema() ) {
			return array();
		}
		return array_values( array_diff( self::file(), array_values( self::abbinamenti() ) ) );
	}

	public static function dati( string $lingua ): array {
		if ( ! isset( self::$cache[ $lingua ] ) ) {
			$nome = self::abbinamenti()[ $lingua ] ?? $lingua;
			$file = self::cartella() . sanitize_key( $nome ) . '.json';
			$dati = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : null;
			if ( ! is_array( $dati ) ) {
				throw new RuntimeException( "File di traduzione illeggibile: $nome.json" );
			}
			self::$cache[ $lingua ] = $dati;
		}
		return self::$cache[ $lingua ];
	}

	/**
	 * Passi dell'importazione: [ lingua, tipo, chiave, etichetta ]. Un passo per ogni casa e ogni
	 * articolo, così anche un hosting lento non si ferma a metà.
	 */
	public static function passi( array $lingue ): array {
		$passi = array();
		foreach ( $lingue as $l ) {
			$d    = self::dati( $l );
			$nome = $d['nome'] ?? strtoupper( $l );
			$passi[] = array( $l, 'base', '', "$nome: categorie, regioni, servizi e pagine" );
			foreach ( array_keys( $d['strutture'] ?? array() ) as $slug ) {
				$passi[] = array( $l, 'struttura', $slug, "$nome: casa $slug" );
			}
			foreach ( array_keys( $d['articoli'] ?? array() ) as $slug ) {
				$passi[] = array( $l, 'articolo', $slug, "$nome: articolo $slug" );
			}
		}
		return $passi;
	}

	public function esegui( array $passo ): void {
		list( $lingua, $tipo, $chiave ) = $passo;
		$d = self::dati( $lingua );
		switch ( $tipo ) {
			case 'base':
				$this->prepara();
				$this->termini( $lingua, $d['termini'] ?? array() );
				$this->pagine( $lingua, $d['pagine'] ?? array() );
				$this->stringhe( $lingua, $d['stringhe'] ?? array() );
				break;
			case 'struttura':
				$this->struttura( $lingua, $chiave, $d['strutture'][ $chiave ] );
				break;
			case 'articolo':
				$this->articolo( $lingua, $chiave, $d['articoli'][ $chiave ] );
				break;
		}
	}

	/** I contenuti creati prima di Polylang non hanno lingua: diventano italiani. */
	private function prepara(): void {
		if ( isset( PLL()->model ) && method_exists( PLL()->model, 'set_language_in_mass' ) ) {
			PLL()->model->set_language_in_mass();
		}
	}

	private function predefinita(): string {
		return (string) pll_default_language();
	}

	/** Originale italiano per slug, solo se è davvero nella lingua predefinita. */
	private function originale( string $slug, string $tipo ): ?WP_Post {
		$trovati = get_posts(
			array(
				'name'        => $slug,
				'post_type'   => $tipo,
				'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'numberposts' => 5,
				'lang'        => '',
			)
		);
		foreach ( $trovati as $p ) {
			if ( pll_get_post_language( $p->ID ) === $this->predefinita() ) {
				return $p;
			}
		}
		return null;
	}

	/** Crea o aggiorna la traduzione di $originale e la collega al gruppo. */
	private function salva_traduzione( WP_Post $originale, string $lingua, array $postarr ): int {
		$esistente = (int) pll_get_post( $originale->ID, $lingua );
		$postarr  += array(
			'post_type'      => $originale->post_type,
			'post_status'    => $originale->post_status,
			'post_author'    => $originale->post_author,
			'post_date'      => $originale->post_date,
			'post_date_gmt'  => $originale->post_date_gmt,
			'menu_order'     => $originale->menu_order,
			'comment_status' => $originale->comment_status,
			'ping_status'    => $originale->ping_status,
		);
		if ( $esistente ) {
			$postarr['ID'] = $esistente;
			$id            = wp_update_post( wp_slash( $postarr ), true );
		} else {
			$id = wp_insert_post( wp_slash( $postarr ), true );
		}
		if ( is_wp_error( $id ) ) {
			throw new RuntimeException( $id->get_error_message() );
		}
		pll_set_post_language( $id, $lingua );
		$gruppo            = pll_get_post_translations( $originale->ID );
		$gruppo[ $lingua ] = $id;
		$gruppo[ $this->predefinita() ] = $originale->ID;
		pll_save_post_translations( $gruppo );
		return (int) $id;
	}

	/** Contenuto in blocchi: titoli, paragrafi ed elenchi del file diventano blocchi dell'editor. */
	private function blocchi( string $html ): string {
		$html = trim( $html );
		if ( '' === $html || str_contains( $html, '<!-- wp:' ) ) {
			return $html;
		}
		$out = '';
		preg_match_all( '#<(h[2-4]|p|ul|ol|blockquote|table)\b[^>]*>.*?</\1>#s', $html, $parti, PREG_SET_ORDER );
		foreach ( $parti as $p ) {
			$tag = strtolower( $p[1] );
			if ( 'p' === $tag ) {
				$out .= "<!-- wp:paragraph -->\n{$p[0]}\n<!-- /wp:paragraph -->\n\n";
			} elseif ( 'h2' === $tag ) {
				$out .= "<!-- wp:heading -->\n" . preg_replace( '#^<h2\b[^>]*>#', '<h2 class="wp-block-heading">', $p[0] ) . "\n<!-- /wp:heading -->\n\n";
			} elseif ( 'h3' === $tag || 'h4' === $tag ) {
				$livello = (int) substr( $tag, 1 );
				$out    .= "<!-- wp:heading {\"level\":$livello} -->\n" . preg_replace( '#^<h([34])\b[^>]*>#', '<h$1 class="wp-block-heading">', $p[0] ) . "\n<!-- /wp:heading -->\n\n";
			} elseif ( 'ul' === $tag || 'ol' === $tag ) {
				$lista = preg_replace( '#^<(ul|ol)\b[^>]*>#', '<$1 class="wp-block-list">', $p[0] );
				$lista = preg_replace( '#<li\b[^>]*>(.*?)</li>#s', "<!-- wp:list-item -->\n<li>$1</li>\n<!-- /wp:list-item -->", $lista );
				$out  .= ( 'ol' === $tag ? "<!-- wp:list {\"ordered\":true} -->\n" : "<!-- wp:list -->\n" ) . $lista . "\n<!-- /wp:list -->\n\n";
			} elseif ( 'table' === $tag ) {
				$out .= "<!-- wp:table -->\n<figure class=\"wp-block-table\">" . preg_replace( '#^<table\b[^>]*>#', '<table>', $p[0] ) . "</figure>\n<!-- /wp:table -->\n\n";
			} else {
				$out .= "<!-- wp:quote -->\n" . preg_replace( '#^<blockquote\b[^>]*>#', '<blockquote class="wp-block-quote">', $p[0] ) . "\n<!-- /wp:quote -->\n\n";
			}
		}
		return trim( $out );
	}

	/**
	 * A importazione finita: Polylang rilegge le pagine iniziali delle lingue, gli indirizzi
	 * (/en/, /fr/…) vengono rigenerati e la cache delle pagine si svuota.
	 */
	public static function concludi(): void {
		if ( isset( PLL()->model ) && method_exists( PLL()->model, 'clean_languages_cache' ) ) {
			PLL()->model->clean_languages_cache();
		}
		flush_rewrite_rules( false );
		if ( function_exists( 'fma_svuota_cache' ) ) {
			fma_svuota_cache();
		}
	}

	/* ------------------------------------------------------------ termini */

	private function termini( string $lingua, array $per_tassonomia ): void {
		foreach ( $per_tassonomia as $tassonomia => $voci ) {
			if ( ! taxonomy_exists( $tassonomia ) || ! pll_is_translated_taxonomy( $tassonomia ) ) {
				continue;
			}
			foreach ( $voci as $slug_it => $t ) {
				$this->termine( $tassonomia, (string) $slug_it, $lingua, (array) $t );
			}
		}
	}

	private function termine( string $tassonomia, string $slug_it, string $lingua, array $t ): int {
		$originale = null;
		foreach ( get_terms( array( 'taxonomy' => $tassonomia, 'slug' => $slug_it, 'hide_empty' => false, 'lang' => '' ) ) as $termine ) {
			if ( pll_get_term_language( $termine->term_id ) === $this->predefinita() ) {
				$originale = $termine;
			}
		}
		if ( ! $originale ) {
			$this->log( "Termine non trovato ($tassonomia): $slug_it", 'avviso' );
			return 0;
		}
		$esistente = (int) pll_get_term( $originale->term_id, $lingua );
		$nome      = (string) ( $t['nome'] ?? $originale->name );
		$args      = array( 'slug' => sanitize_title( (string) ( $t['slug'] ?? $slug_it . '-' . $lingua ) ) );
		if ( isset( $t['descrizione'] ) ) {
			$args['description'] = (string) $t['descrizione'];
		}
		if ( $esistente ) {
			$esito = wp_update_term( $esistente, $tassonomia, $args + array( 'name' => $nome ) );
		} elseif ( function_exists( 'pll_insert_term' ) ) {
			$esito = pll_insert_term( $nome, $tassonomia, $lingua, $args );
		} else {
			$esito = wp_insert_term( $nome, $tassonomia, $args );
		}
		if ( is_wp_error( $esito ) ) {
			$this->log( "Termine non salvato ($tassonomia, $slug_it): " . $esito->get_error_message(), 'avviso' );
			return 0;
		}
		$id = (int) $esito['term_id'];
		pll_set_term_language( $id, $lingua );
		$gruppo            = pll_get_term_translations( $originale->term_id );
		$gruppo[ $lingua ] = $id;
		$gruppo[ $this->predefinita() ] = $originale->term_id;
		pll_save_term_translations( $gruppo );
		return $id;
	}

	/** Termini tradotti di un contenuto italiano (quelli senza traduzione restano fuori). */
	private function termini_tradotti( int $id_it, string $tassonomia, string $lingua ): array {
		$tradotti = array();
		foreach ( wp_get_object_terms( $id_it, $tassonomia, array( 'fields' => 'ids', 'lang' => '' ) ) as $term_id ) {
			$t = (int) pll_get_term( (int) $term_id, $lingua );
			if ( $t ) {
				$tradotti[] = $t;
			}
		}
		return $tradotti;
	}

	/* ------------------------------------------------------------ pagine Home e Blog */

	private function pagine( string $lingua, array $pagine ): void {
		$opzioni = array( 'home' => 'page_on_front', 'blog' => 'page_for_posts' );
		foreach ( $opzioni as $chiave => $opzione ) {
			if ( empty( $pagine[ $chiave ] ) ) {
				continue;
			}
			$id_it = (int) get_option( $opzione );
			$id_it = $id_it ? ( (int) pll_get_post( $id_it, $this->predefinita() ) ?: $id_it ) : 0;
			$it    = $id_it ? get_post( $id_it ) : null;
			if ( ! $it ) {
				continue;
			}
			$t       = $pagine[ $chiave ];
			$postarr = array( 'post_title' => (string) $t['titolo'], 'post_status' => 'publish' );
			// Polylang crea già, aggiungendo una lingua, una pagina «Home» vuota: si riusa invece di farne un'altra.
			if ( ! pll_get_post( $it->ID, $lingua ) && 'home' === $chiave ) {
				foreach ( get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 20, 'lang' => $lingua ) ) as $p ) {
					if ( str_starts_with( $p->post_name, 'home' ) && count( pll_get_post_translations( $p->ID ) ) <= 1 ) {
						$gruppo            = pll_get_post_translations( $it->ID );
						$gruppo[ $lingua ] = $p->ID;
						pll_save_post_translations( $gruppo );
						break;
					}
				}
			}
			if ( ! pll_get_post( $it->ID, $lingua ) ) {
				$postarr['post_name'] = (string) ( $t['slug'] ?? $it->post_name . '-' . $lingua );
			}
			$this->salva_traduzione( $it, $lingua, $postarr );
		}
	}

	/* ------------------------------------------------------------ stringhe (motto del sito) */

	private function stringhe( string $lingua, array $stringhe ): void {
		if ( ! $stringhe || ! class_exists( 'PLL_MO' ) ) {
			return;
		}
		$lingua_obj = PLL()->model->get_language( $lingua );
		if ( ! $lingua_obj ) {
			return;
		}
		$mo = new PLL_MO();
		$mo->import_from_db( $lingua_obj );
		foreach ( $stringhe as $originale => $traduzione ) {
			$mo->add_entry( $mo->make_entry( (string) $originale, (string) $traduzione ) );
		}
		// Il motto attuale del sito (Impostazioni → Generali), se è tra quelli tradotti con o senza punto finale.
		$motto = (string) get_option( 'blogdescription' );
		foreach ( array( rtrim( $motto, '.' ), $motto ) as $variante ) {
			foreach ( $stringhe as $originale => $traduzione ) {
				if ( '' !== $variante && rtrim( (string) $originale, '.' ) === rtrim( $variante, '.' ) && $variante !== $originale ) {
					$testo = rtrim( (string) $traduzione, '.。' );
					// Il punto finale come nell'originale, «。» dopo un testo cinese o giapponese.
					$punto = preg_match( '/[\x{3000}-\x{9FFF}]$/u', $testo ) ? '。' : '.';
					$mo->add_entry( $mo->make_entry( $variante, str_ends_with( $variante, '.' ) ? $testo . $punto : $testo ) );
				}
			}
		}
		$mo->export_to_db( $lingua_obj );
	}

	/* ------------------------------------------------------------ case */

	private function struttura( string $lingua, string $slug, array $t ): void {
		$it = $this->originale( $slug, 'struttura' );
		if ( ! $it ) {
			$this->log( "Casa non trovata nel sito: $slug", 'avviso' );
			return;
		}
		$postarr = array(
			'post_title'   => (string) $t['titolo'],
			'post_excerpt' => (string) ( $t['riassunto'] ?? '' ),
			'post_content' => $this->blocchi( (string) ( $t['contenuto'] ?? '' ) ),
		);
		if ( ! pll_get_post( $it->ID, $lingua ) ) {
			$postarr['post_name'] = (string) ( $t['slug'] ?? $slug . '-' . $lingua );
		}
		$id = $this->salva_traduzione( $it, $lingua, $postarr );

		// Campi: si copia tutto dalla versione italiana, poi si mettono i testi tradotti.
		$saltare = array( '_edit_lock', '_edit_last', '_wp_old_slug', '_pingme', '_encloseme' );
		foreach ( get_post_meta( $it->ID ) as $chiave => $valori ) {
			if ( in_array( $chiave, $saltare, true ) || in_array( $chiave, FMA_META_DA_TRADURRE, true ) ) {
				continue;
			}
			update_post_meta( $id, $chiave, maybe_unserialize( $valori[0] ) );
		}
		$campi = (array) ( $t['campi'] ?? array() );
		foreach ( array( 'fma_titolo_hero', 'fma_tipo', 'fma_localita', 'fma_tassa', 'fma_orari', 'fma_seo_titolo', 'fma_seo_descrizione' ) as $chiave ) {
			$valore = $campi[ $chiave ] ?? get_post_meta( $it->ID, $chiave, true );
			'' === (string) $valore ? delete_post_meta( $id, $chiave ) : update_post_meta( $id, $chiave, (string) $valore );
		}
		foreach ( array( 'fma_camere', 'fma_dintorni' ) as $chiave ) {
			$righe    = (array) get_post_meta( $it->ID, $chiave, true );
			$tradotte = (array) ( $campi[ $chiave ] ?? array() );
			foreach ( $righe as $i => $riga ) {
				if ( is_array( $riga ) && isset( $tradotte[ $i ] ) && is_array( $tradotte[ $i ] ) ) {
					$righe[ $i ] = array_merge( $riga, array_intersect_key( $tradotte[ $i ], $riga ) );
				}
			}
			update_post_meta( $id, $chiave, array_values( $righe ) );
		}
		if ( isset( $campi['fma_regole'] ) ) {
			update_post_meta( $id, 'fma_regole', array_values( (array) $campi['fma_regole'] ) );
		} else {
			update_post_meta( $id, 'fma_regole', (array) get_post_meta( $it->ID, 'fma_regole', true ) );
		}

		foreach ( array( 'regione', 'servizio' ) as $tassonomia ) {
			wp_set_object_terms( $id, $this->termini_tradotti( $it->ID, $tassonomia, $lingua ), $tassonomia );
		}
	}

	/* ------------------------------------------------------------ articoli */

	private function articolo( string $lingua, string $slug, array $t ): void {
		$it = $this->originale( $slug, 'post' );
		if ( ! $it ) {
			$this->log( "Articolo non trovato nel sito: $slug", 'avviso' );
			return;
		}
		$postarr = array(
			'post_title'   => (string) $t['titolo'],
			'post_excerpt' => (string) ( $t['riassunto'] ?? '' ),
			'post_content' => $this->blocchi( (string) $t['contenuto'] ),
		);
		if ( ! pll_get_post( $it->ID, $lingua ) ) {
			$postarr['post_name'] = (string) ( $t['slug'] ?? $slug . '-' . $lingua );
		}
		$id = $this->salva_traduzione( $it, $lingua, $postarr );

		$foto = (int) get_post_thumbnail_id( $it->ID );
		$foto ? set_post_thumbnail( $id, $foto ) : delete_post_thumbnail( $id );
		$categorie = $this->termini_tradotti( $it->ID, 'category', $lingua );
		if ( $categorie ) {
			wp_set_post_categories( $id, $categorie );
		}
		$casa = (int) get_post_meta( $it->ID, 'fma_struttura_vicina', true );
		if ( $casa ) {
			update_post_meta( $id, 'fma_struttura_vicina', (int) pll_get_post( $casa, $lingua ) ?: $casa );
		}
	}
}
