<?php
/**
 * Portfolio section template part
 * Usage: get_template_part('inc/portfolio-template');
 * Or shortcode: [experiences_portfolio posts="6"]
 * @package experiences-srl
 */

$portfolio_items = new WP_Query([
    'post_type'      => 'portfolio_site',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

if ( $portfolio_items->have_posts() ) :
    $delay = 100;
    while ( $portfolio_items->have_posts() ) : $portfolio_items->the_post();
        $site_url  = get_post_meta( get_the_ID(), 'site_url', true );
        $category  = get_post_meta( get_the_ID(), 'site_category', true );
        $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'portfolio-thumb' );
?>
    <a href="<?php echo esc_url( $site_url ?: '#' ); ?>" target="_blank" rel="noopener noreferrer"
       class="portfolio-item rounded-2xl shadow-md group block"
       data-aos="fade-up" data-aos-delay="<?php echo intval($delay); ?>">
        <?php if ( $thumb_url ) : ?>
        <img src="<?php echo esc_url( $thumb_url ); ?>"
             alt="<?php echo esc_attr( get_the_title() ); ?>"
             class="w-full h-64 object-cover"
             loading="lazy"
             width="800" height="500">
        <?php endif; ?>
        <div class="portfolio-content p-6">
            <h3 class="font-heading text-xl font-bold text-white mb-1"><?php the_title(); ?></h3>
            <p class="text-white/70 text-sm"><?php echo esc_html( $category ); ?></p>
            <span class="inline-flex items-center gap-2 mt-3 text-accent text-sm font-medium">
                Visita il sito <i class="fas fa-external-link-alt" aria-hidden="true"></i>
            </span>
        </div>
    </a>
<?php
        $delay += 100;
    endwhile;
    wp_reset_postdata();
endif;
?>
