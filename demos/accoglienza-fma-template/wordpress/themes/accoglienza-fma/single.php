<?php
/**
 * Articolo del blog, con la casa più vicina e altri due articoli.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$pagina_blog = (int) get_option( 'page_for_posts' );
$url_blog    = $pagina_blog ? get_permalink( $pagina_blog ) : home_url( '/' );

while ( have_posts() ) :
	the_post();
	$cat  = get_the_category();
	$casa = (int) get_post_meta( get_the_ID(), 'fma_struttura_vicina', true );
	$casa = $casa && fma_plugin_attivo() && 'publish' === get_post_status( $casa ) ? fma_struttura_dati( $casa ) : array();
	?>
<main id="contenuto">
	<nav class="crumbs" aria-label="Percorso"><ol><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li><a href="<?php echo esc_url( $url_blog ); ?>"><?php echo esc_html( $pagina_blog ? get_the_title( $pagina_blog ) : 'Blog' ); ?></a></li><li aria-current="page"><?php the_title(); ?></li></ol></nav>
	<article <?php post_class( 'article' ); ?>>
		<header class="article__head">
			<p class="post__meta"><?php if ( $cat ) : ?><span class="post__cat"><?php echo esc_html( $cat[0]->name ); ?></span> · <?php endif; ?><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( fma_data() ); ?></time> · <?php echo (int) fma_minuti_lettura(); ?> min di lettura</p>
			<h1 class="article__title" data-hero-in><?php the_title(); ?></h1>
		</header>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="article__media" data-unveil><?php the_post_thumbnail( 'large', array( 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 48em) 42rem, 100vw' ) ); ?></figure>
		<?php endif; ?>
		<div class="article__body prose"><?php the_content(); ?></div>
		<?php
		wp_link_pages(
			array(
				'before' => '<nav class="page-links" aria-label="Pagine dell’articolo">',
				'after'  => '</nav>',
			)
		);
		?>
		<?php if ( $casa ) : ?>
			<aside class="near" aria-label="Casa vicina">
				<a href="<?php echo esc_url( $casa['url'] ); ?>">
					<span class="near__media"><?php echo fma_immagine( $casa['foto'], 'fma-card', array( 'alt' => '', 'sizes' => '12rem' ) ); // phpcs:ignore ?></span>
					<span class="near__body"><span class="near__kicker">Dormi vicino</span><span class="near__name"><?php echo esc_html( $casa['nome'] ); ?></span><span class="near__where"><?php echo esc_html( fma_luogo( $casa ) ); ?></span><span class="text-link">Scopri la casa <?php fma_e_icona( 'arrow', 'icon icon--sm' ); ?></span></span>
				</a>
			</aside>
		<?php endif; ?>
	</article>

	<?php
	$altri = get_posts(
		array(
			'numberposts'         => 2,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
		)
	);
	?>
	<?php if ( $altri ) : ?>
		<section class="others" aria-labelledby="more-title">
			<h2 id="more-title" class="section-title section-title--sm">Continua a leggere</h2>
			<ul class="journal__list journal__list--wide">
				<?php foreach ( $altri as $a ) : ?>
					<li class="post post--row"><a href="<?php echo esc_url( get_permalink( $a ) ); ?>">
						<span class="post__thumb"><?php echo get_the_post_thumbnail( $a, 'fma-quadrato', array( 'alt' => '' ) ); ?></span>
						<span class="post__body"><span class="post__meta"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $a ) ); ?>"><?php echo esc_html( fma_data( $a ) ); ?></time> · <?php echo (int) fma_minuti_lettura( $a ); ?> min</span><span class="post__title"><?php echo esc_html( get_the_title( $a ) ); ?></span></span>
					</a></li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
</main>
	<?php
endwhile;

get_footer();
