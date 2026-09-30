<?php
/**
 * Pagina degli articoli (Blog) e archivi di categoria, tag, autore e data.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$pagina_blog = (int) get_option( 'page_for_posts' );
if ( is_home() ) {
	$titolo = $pagina_blog ? get_the_title( $pagina_blog ) : 'Blog';
	$lede   = $pagina_blog && has_excerpt( $pagina_blog ) ? get_the_excerpt( $pagina_blog ) : 'Consigli di viaggio e storie dai luoghi delle nostre case: cosa vedere, quando andare, come arrivare.';
} elseif ( is_search() ) {
	$titolo = sprintf( 'Risultati per “%s”', get_search_query() );
	$lede   = '';
} else {
	$titolo = wp_strip_all_tags( get_the_archive_title() );
	$lede   = wp_strip_all_tags( get_the_archive_description() );
}
$in_evidenza = ! is_paged();
?>
<main id="contenuto">
	<header class="page-head">
		<h1 class="page-head__title" data-hero-in><?php echo esc_html( $titolo ); ?></h1>
		<?php if ( $lede ) : ?>
			<p class="page-head__lede" data-hero-in><?php echo esc_html( $lede ); ?></p>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<ol class="entries">
			<?php
			$i = 0;
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/articolo-voce', null, array( 'primo' => $in_evidenza && 0 === $i++ ) );
			endwhile;
			?>
		</ol>
		<?php
		the_posts_pagination(
			array(
				'mid_size'           => 1,
				'prev_text'          => fma_icona( 'chev-l', 'icon icon--sm' ) . '<span class="screen-reader-text">Articoli più recenti</span>',
				'next_text'          => '<span class="screen-reader-text">Articoli meno recenti</span>' . fma_icona( 'chev-r', 'icon icon--sm' ),
				'screen_reader_text' => 'Altre pagine del blog',
				'aria_label'         => 'Pagine',
			)
		);
		?>
	<?php else : ?>
		<div class="page-body">
			<div class="empty"><p class="empty__title">Nessun articolo trovato.</p><p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Torna alla home</a></p></div>
		</div>
	<?php endif; ?>
</main>
<?php
get_footer();
