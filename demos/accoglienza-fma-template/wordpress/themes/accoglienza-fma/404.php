<?php
/**
 * Pagina non trovata.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$archivio = fma_plugin_attivo() ? get_post_type_archive_link( 'struttura' ) : '';
?>
<main id="contenuto">
	<header class="page-head">
		<h1 class="page-head__title" data-hero-in><?php esc_html_e( 'Questa pagina non c’è', 'accoglienza-fma' ); ?></h1>
		<p class="page-head__lede" data-hero-in><?php esc_html_e( 'Forse l’indirizzo è cambiato. Le case e gli articoli sono tutti a portata di clic.', 'accoglienza-fma' ); ?></p>
	</header>
	<div class="page-body">
		<p class="page-body__actions">
			<?php if ( $archivio ) : ?>
				<a class="btn btn--primary" href="<?php echo esc_url( $archivio ); ?>"><?php esc_html_e( 'Vedi tutte le case', 'accoglienza-fma' ); ?></a>
			<?php endif; ?>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Torna alla home', 'accoglienza-fma' ); ?></a>
		</p>
	</div>
</main>
<?php
get_footer();
