<?php
/**
 * Template Name: Blog — Tutti gli articoli
 *
 * Elenco completo degli articoli con filtro per categoria e ricerca dal
 * vivo. Assegnabile a qualsiasi pagina dall'editor (pannello Pagina →
 * Modello), quindi funziona anche senza aver configurato la "Pagina degli
 * articoli" in Impostazioni → Lettura.
 *
 * Gli articoli sono renderizzati tutti lato server e filtrati in pagina:
 * con un catalogo nell'ordine delle decine è più rapido di una
 * paginazione, e i contenuti restano visibili ai motori di ricerca.
 *
 * @package experiences-srl
 */

get_header();

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
                <?php the_title(); ?>
            </h1>
            <?php if ( get_the_content() ) : ?>
                <div class="text-lg text-gray-200 max-w-2xl mx-auto leading-relaxed">
                    <?php the_content(); ?>
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

    <?php get_template_part( 'template-parts/blog-listing', null, [
        'query' => $exp_q,
        'cats'  => $exp_cats,
    ] ); ?>

</main>

<?php get_footer(); ?>
