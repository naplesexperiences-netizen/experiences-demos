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
		<h1 class="page-head__title" data-hero-in>Questa pagina non c’è</h1>
		<p class="page-head__lede" data-hero-in>Forse l’indirizzo è cambiato. Le case e gli articoli sono tutti a portata di clic.</p>
	</header>
	<div class="page-body">
		<p class="page-body__actions">
			<?php if ( $archivio ) : ?>
				<a class="btn btn--primary" href="<?php echo esc_url( $archivio ); ?>">Vedi tutte le case</a>
			<?php endif; ?>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>">Torna alla home</a>
		</p>
	</div>
</main>
<?php
get_footer();
