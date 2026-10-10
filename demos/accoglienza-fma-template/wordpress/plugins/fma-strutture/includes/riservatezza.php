<?php
/**
 * Il nome di accesso degli amministratori non compare nel sito pubblico.
 *
 * WordPress lo mostra di serie nell'indirizzo delle pagine autore (/author/<nome-di-accesso>/),
 * nella mappa del sito degli utenti, nei dati di incorporamento (oEmbed) e nell'elenco utenti
 * delle API. Il sito non ha pagine autore, quindi si tolgono tutte.
 */

defined( 'ABSPATH' ) || exit;

// Mappa del sito: niente wp-sitemap-users-1.xml.
add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, string $nome ) {
		return 'users' === $nome ? false : $provider;
	},
	10,
	2
);

// Pagine autore e /?author=N portano alla home, prima che WordPress rediriga all'indirizzo con il nome.
add_action(
	'template_redirect',
	function () {
		if ( is_author() || isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	},
	1
);

// Nessun link verso le pagine autore, ovunque venga generato.
add_filter( 'author_link', fn() => home_url( '/' ) );

// Incorporamento degli articoli in altri siti: senza nome e link dell'autore.
add_filter(
	'oembed_response_data',
	function ( array $dati ): array {
		unset( $dati['author_name'], $dati['author_url'] );
		return $dati;
	}
);

// Feed RSS: come autore il nome del sito.
add_filter( 'the_author', fn( $nome ) => is_feed() ? get_bloginfo( 'name' ) : $nome );

// API: l'elenco utenti resta solo per chi è collegato (serve all'editor).
add_filter(
	'rest_endpoints',
	function ( array $rotte ): array {
		if ( ! is_user_logged_in() ) {
			unset( $rotte['/wp/v2/users'], $rotte['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $rotte;
	}
);
