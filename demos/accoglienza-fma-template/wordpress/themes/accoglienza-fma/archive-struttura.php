<?php
/**
 * Elenco di tutte le strutture (/strutture/) e delle strutture di una regione (/regione/<slug>/):
 * stessa sezione mappa + elenco della home, con il titolo come H1.
 */

defined( 'ABSPATH' ) || exit;

if ( ! fma_plugin_attivo() ) {
	get_template_part( 'index' );
	return;
}

get_header();

$strutture = array_map( 'fma_struttura_dati', $GLOBALS['wp_query']->posts );
if ( is_tax( 'regione' ) ) {
	$titolo = sprintf( 'Le case in %s', single_term_title( '', false ) );
	$testo  = wp_strip_all_tags( term_description() );
} else {
	$titolo = fma_testo( 'strutture_titolo' );
	$testo  = fma_testo( 'strutture_testo' );
}
?>
<main id="contenuto" class="archivio-strutture">
	<?php if ( $strutture ) : ?>
		<?php
		get_template_part(
			'template-parts/strutture-mappa',
			null,
			array(
				'strutture'  => $strutture,
				'titolo'     => $titolo,
				'testo'      => $testo,
				'titolo_tag' => 'h1',
			)
		);
		?>
	<?php else : ?>
		<header class="page-head">
			<h1 class="page-head__title" data-hero-in><?php echo esc_html( $titolo ); ?></h1>
		</header>
		<div class="page-body">
			<div class="empty"><p class="empty__title">Non ci sono ancora case da mostrare.</p><p>Torna a trovarci presto, oppure <a href="<?php echo esc_url( home_url( '/' ) ); ?>">vai alla home</a>.</p></div>
		</div>
	<?php endif; ?>
</main>
<?php
get_footer();
