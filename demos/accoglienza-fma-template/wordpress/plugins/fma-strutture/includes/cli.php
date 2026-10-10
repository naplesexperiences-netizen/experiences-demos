<?php
/**
 * Comando WP-CLI: importa strutture, partner e articoli dal file dati della demo.
 *
 *   wp fma importa demos/accoglienza-fma-template/_src/data.json --immagini=demos/accoglienza-fma-template/img --configura-sito
 */

defined( 'ABSPATH' ) || exit;

class FMA_Importa_Comando {

	/**
	 * Importa i contenuti. Si può rilanciare: aggiorna quello che trova (per slug) senza duplicare immagini.
	 *
	 * ## OPTIONS
	 *
	 * <file>
	 * : Percorso di data.json.
	 *
	 * [--immagini=<cartella>]
	 * : Cartella img/ della demo (strutture/, blog/, loghi/).
	 *
	 * [--configura-sito]
	 * : Crea le pagine Home e Blog, il menu principale, i permalink e il logo.
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assoc ) {
		$registro = function ( string $messaggio, string $tipo ) {
			'avviso' === $tipo ? WP_CLI::warning( $messaggio ) : WP_CLI::log( $messaggio );
		};
		try {
			$dati  = FMA_Importatore::leggi( $args[0] );
			$conti = ( new FMA_Importatore( (string) ( $assoc['immagini'] ?? '' ), $registro ) )->importa( $dati, isset( $assoc['configura-sito'] ) );
		} catch ( RuntimeException $e ) {
			WP_CLI::error( $e->getMessage() );
		}
		WP_CLI::success( sprintf( '%d strutture, %d partner, %d articoli.', $conti['strutture'], $conti['partner'], $conti['articoli'] ) );
	}
}

WP_CLI::add_command( 'fma importa', 'FMA_Importa_Comando' );
