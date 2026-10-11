<?php
/**
 * Anteprima quando si condivide un link (WhatsApp, Facebook, LinkedIn, Telegram, X):
 * meta description, Open Graph e scheda Twitter con foto, titolo e descrizione della pagina.
 *
 * Se è attivo un plugin SEO che scrive già questi tag, il tema non li duplica.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'fma_condivisione_meta', 5 );
add_filter( 'document_title_parts', 'fma_titolo_documento' );

function fma_condivisione_plugin_seo(): bool {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || defined( 'SLIM_SEO_VER' );
}

/** Foto predefinita: quella scelta in Personalizza, altrimenti la prima casa dello slider, altrimenti il logo. */
function fma_condivisione_immagine_predefinita(): int {
	$scelta = (int) get_theme_mod( 'fma_social_immagine', 0 );
	if ( $scelta && wp_attachment_is_image( $scelta ) ) {
		return $scelta;
	}
	if ( fma_plugin_attivo() ) {
		foreach ( fma_strutture() as $s ) {
			if ( $s['in_slider'] && $s['foto'] ) {
				return $s['foto'];
			}
		}
	}
	return (int) get_theme_mod( 'custom_logo', 0 );
}

/**
 * Testo semplice, su una riga, al massimo $max caratteri. Se va accorciato si chiude
 * all'ultima frase intera che ci sta, altrimenti all'ultima parola intera: mai a metà parola.
 */
function fma_condivisione_testo( string $testo, int $max = 155 ): string {
	$testo = trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( $testo ), ENT_QUOTES, 'UTF-8' ) ) );
	if ( mb_strlen( $testo ) <= $max ) {
		return $testo;
	}
	$pezzo = mb_substr( $testo, 0, $max );
	if ( preg_match( '/^(.{80,}[.!?])\s/su', $pezzo . ' ', $frase ) ) {
		return $frase[1];
	}
	return rtrim( preg_replace( '/\s+\S*$/u', '', mb_substr( $pezzo, 0, $max - 1 ) ), " ,.;:–-" ) . '…';
}

/** Titolo per Google di una struttura: campo «Titolo per Google», altrimenti «Nome, tipo a Località». */
function fma_titolo_struttura( WP_Post $post ): string {
	$s = fma_struttura_dati( $post );
	if ( '' !== ( $s['seo_titolo'] ?? '' ) ) {
		return $s['seo_titolo'];
	}
	$luogo = $s['localita'];
	if ( ! $luogo ) {
		return $s['nome'];
	}
	$tipo = mb_strtolower( mb_substr( $s['tipo'], 0, 1 ) ) . mb_substr( $s['tipo'], 1 );
	return ! $tipo || false !== mb_stripos( $s['nome'], $tipo ) ? "{$s['nome']} a {$luogo}" : "{$s['nome']}, {$tipo} a {$luogo}";
}

/** Titoli con quello che si cerca (tipo di casa e luogo) invece dello slogan. */
function fma_titolo_documento( array $parti ): array {
	if ( fma_condivisione_plugin_seo() ) {
		return $parti;
	}
	if ( is_front_page() && fma_testo( 'seo_titolo_home' ) ) {
		$parti = array( 'title' => fma_testo( 'seo_titolo_home' ), 'site' => get_bloginfo( 'name' ) );
	} elseif ( is_singular( 'struttura' ) && fma_plugin_attivo() ) {
		$parti['title'] = fma_titolo_struttura( get_queried_object() );
	} elseif ( is_post_type_archive( 'struttura' ) ) {
		$parti['title'] = 'Case per ferie in Italia';
	} elseif ( is_tax( 'regione' ) ) {
		$parti['title'] = 'Case per ferie in ' . single_term_title( '', false );
	}
	// Oltre i 60-65 caratteri Google taglia: meglio perdere il nome del sito che la località.
	if ( isset( $parti['site'], $parti['title'] ) && mb_strlen( $parti['title'] . ' – ' . $parti['site'] ) > 65 ) {
		unset( $parti['site'] );
	}
	return $parti;
}

/**
 * Immagine 1200×630 in JPEG. Le foto importate sono WebP, che LinkedIn non legge e WhatsApp non sempre:
 * la prima volta se ne salva una copia JPEG accanto alle altre misure e la si riusa.
 *
 * @return array{0:string,1:int,2:int}|false
 */
function fma_condivisione_jpeg( int $id ) {
	$img = wp_get_attachment_image_src( $id, 'fma-social' );
	if ( ! $img || preg_match( '/\.(jpe?g|png|gif)$/i', $img[0] ) ) {
		return $img;
	}
	$salvata = get_post_meta( $id, '_fma_social_jpg', true );
	$cartella = wp_upload_dir( null, false );
	if ( is_array( $salvata ) && is_file( $cartella['basedir'] . '/' . $salvata['file'] ) ) {
		return array( $cartella['baseurl'] . '/' . $salvata['file'], (int) $salvata['w'], (int) $salvata['h'] );
	}
	$origine = wp_get_original_image_path( $id ) ?: get_attached_file( $id );
	$editor  = $origine ? wp_get_image_editor( $origine ) : null;
	if ( ! $editor || is_wp_error( $editor ) ) {
		return $img;
	}
	$editor->resize( 1200, 630, true );
	$editor->set_quality( 82 );
	$dest  = preg_replace( '/\.[a-z0-9]+$/i', '', $origine ) . '-social-1200x630.jpg';
	$esito = $editor->save( $dest, 'image/jpeg' );
	if ( is_wp_error( $esito ) ) {
		return $img;
	}
	$file = ltrim( str_replace( wp_normalize_path( $cartella['basedir'] ), '', wp_normalize_path( $esito['path'] ) ), '/' );
	update_post_meta( $id, '_fma_social_jpg', array( 'file' => $file, 'w' => $esito['width'], 'h' => $esito['height'] ) );
	return array( $cartella['baseurl'] . '/' . $file, (int) $esito['width'], (int) $esito['height'] );
}

function fma_condivisione_dati(): array {
	$immagine = 0;
	$testo    = '';
	$tipo     = 'website';
	$url      = home_url( add_query_arg( array() ) );

	if ( is_front_page() ) {
		$testo = fma_testo( 'seo_descrizione_home' ) ?: fma_testo( 'hero_testo' );
		$url   = home_url( '/' );
	} elseif ( is_singular() ) {
		$post     = get_queried_object();
		$immagine = (int) get_post_thumbnail_id( $post );
		$testo    = has_excerpt( $post ) ? $post->post_excerpt : $post->post_content;
		$url      = (string) get_permalink( $post );
		$tipo     = is_singular( 'post' ) ? 'article' : 'website';
		if ( is_singular( 'struttura' ) && fma_plugin_attivo() ) {
			$s     = fma_struttura_dati( $post );
			$testo = $s['seo_descrizione'] ?: $testo;
			if ( ! $testo ) {
				$testo = trim( $s['tipo'] . ' a ' . fma_luogo( $s ) . ( $s['regione'] ? ', ' . $s['regione'] : '' ) );
			}
		}
	} elseif ( is_post_type_archive( 'struttura' ) ) {
		$testo = fma_testo( 'strutture_testo' );
		$url   = (string) get_post_type_archive_link( 'struttura' );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$termine = get_queried_object();
		$testo   = term_description();
		$url     = (string) get_term_link( $termine );
		if ( ! wp_strip_all_tags( $testo ) && is_tax( 'regione' ) ) {
			$n     = (int) $termine->count;
			$testo = sprintf( '%s delle Salesiane in %s: foto, camere e servizi. Scegli la casa e scrivi direttamente alla struttura, senza intermediari.', 1 === $n ? 'Una casa per ferie' : "{$n} case per ferie", $termine->name );
		}
	} elseif ( is_home() ) {
		$pagina = (int) get_option( 'page_for_posts' );
		$testo  = $pagina && has_excerpt( $pagina ) ? get_the_excerpt( $pagina ) : 'Consigli di viaggio e storie dai luoghi delle nostre case: cosa vedere, quando andare, come arrivare.';
		$url    = $pagina ? (string) get_permalink( $pagina ) : $url;
	}

	if ( ! $immagine ) {
		$immagine = fma_condivisione_immagine_predefinita();
	}
	$testo = fma_condivisione_testo( $testo ?: (string) get_bloginfo( 'description' ) );

	$img = $immagine ? fma_condivisione_jpeg( $immagine ) : false;
	return array(
		'titolo'      => html_entity_decode( wp_get_document_title(), ENT_QUOTES, 'UTF-8' ),
		'descrizione' => $testo,
		'url'         => $url,
		'tipo'        => $tipo,
		'immagine'    => $img ? array(
			'url'    => $img[0],
			'width'  => (int) $img[1],
			'height' => (int) $img[2],
			'alt'    => (string) get_post_meta( $immagine, '_wp_attachment_image_alt', true ),
		) : null,
	);
}

function fma_condivisione_meta(): void {
	if ( fma_condivisione_plugin_seo() || is_404() || is_search() ) {
		return;
	}
	$d    = fma_condivisione_dati();
	$meta = array(
		array( 'name', 'description', $d['descrizione'] ),
		array( 'property', 'og:locale', 'it_IT' ),
		array( 'property', 'og:site_name', get_bloginfo( 'name' ) ),
		array( 'property', 'og:type', $d['tipo'] ),
		array( 'property', 'og:title', $d['titolo'] ),
		array( 'property', 'og:description', $d['descrizione'] ),
		array( 'property', 'og:url', $d['url'] ),
		array( 'name', 'twitter:card', $d['immagine'] ? 'summary_large_image' : 'summary' ),
		array( 'name', 'twitter:title', $d['titolo'] ),
		array( 'name', 'twitter:description', $d['descrizione'] ),
	);
	if ( $d['immagine'] ) {
		array_push(
			$meta,
			array( 'property', 'og:image', $d['immagine']['url'] ),
			array( 'property', 'og:image:width', (string) $d['immagine']['width'] ),
			array( 'property', 'og:image:height', (string) $d['immagine']['height'] ),
			array( 'property', 'og:image:alt', $d['immagine']['alt'] ?: $d['titolo'] ),
			array( 'name', 'twitter:image', $d['immagine']['url'] )
		);
	}
	if ( 'article' === $d['tipo'] ) {
		$meta[] = array( 'property', 'article:published_time', get_the_date( 'c', get_queried_object() ) );
	}
	foreach ( $meta as list( $attr, $chiave, $valore ) ) {
		if ( '' !== (string) $valore ) {
			printf( '<meta %s="%s" content="%s">' . "\n", $attr, esc_attr( $chiave ), esc_attr( $valore ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- $attr è fisso.
		}
	}
}
