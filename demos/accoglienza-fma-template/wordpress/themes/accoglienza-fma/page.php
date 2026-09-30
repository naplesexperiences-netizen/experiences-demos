<?php
/**
 * Pagina generica (privacy, contatti, chi siamo…).
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
<main id="contenuto">
	<article <?php post_class( 'article article--page' ); ?>>
		<header class="article__head">
			<h1 class="article__title" data-hero-in><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="page-head__lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</header>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="article__media" data-unveil><?php the_post_thumbnail( 'large', array( 'alt' => '', 'loading' => 'eager', 'sizes' => '(min-width: 48em) 42rem, 100vw' ) ); ?></figure>
		<?php endif; ?>
		<div class="article__body prose"><?php the_content(); ?></div>
		<?php
		wp_link_pages(
			array(
				'before' => '<nav class="page-links" aria-label="Pagine">',
				'after'  => '</nav>',
			)
		);
		?>
	</article>
</main>
	<?php
endwhile;

get_footer();
