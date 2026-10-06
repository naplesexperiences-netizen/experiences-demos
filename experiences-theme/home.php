<?php
/**
 * Pagina degli articoli (Impostazioni → Lettura → "Pagina degli articoli").
 *
 * Senza questo file WordPress ricadeva su index.php, che a sua volta
 * carica front-page: risultato, /blog/ mostrava la homepage invece
 * dell'elenco degli articoli — ed erano due pagine diverse a servire lo
 * stesso H1, cosa che il crawl aveva segnalato come titolo duplicato.
 *
 * @package experiences-srl
 */

get_header();

$exp_page_id = (int) get_option( 'page_for_posts' );

$exp_q = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 200,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$exp_cats = get_categories([ 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ]);
?>

<!-- Spacer per header fixed -->
<div class="h-20 lg:h-24"></div>

<main id="main-content">

    <!-- Intestazione -->
    <section class="relative bg-gradient-to-br from-primary via-dark to-secondary py-16 lg:py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10" aria-hidden="true">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-accent/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-secondary/30 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                <?php echo esc_html( $exp_page_id ? get_the_title( $exp_page_id ) : 'Blog &amp; Risorse' ); ?>
            </h1>

            <?php
            $exp_intro = $exp_page_id ? get_post_field( 'post_content', $exp_page_id ) : '';
            if ( trim( wp_strip_all_tags( $exp_intro ) ) ) : ?>
                <div class="text-lg text-gray-200 max-w-2xl mx-auto leading-relaxed">
                    <?php echo wp_kses_post( apply_filters( 'the_content', $exp_intro ) ); ?>
                </div>
            <?php else : ?>
                <p class="text-lg text-gray-200 max-w-2xl mx-auto leading-relaxed">
                    Strategie, guide pratiche e case study per digitalizzare il tuo business turistico.
                </p>
            <?php endif; ?>

            <p class="mt-6 text-sm text-white/70">
                <strong class="text-accent"><?php echo (int) $exp_q->found_posts; ?></strong>
                articoli pubblicati
            </p>
        </div>
    </section>

    <?php get_template_part( 'template-parts/blog-listing' ); ?>

</main>

<?php get_footer(); ?>
