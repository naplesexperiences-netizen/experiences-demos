<?php
/**
 * Fallback della gerarchia dei template.
 *
 * Faceva get_template_part('front-page'): qualunque vista senza un
 * modello proprio rispondeva 200 mostrando la homepage. Con page.php
 * aggiunto resta scoperta la ricerca, che e il caso piu visibile —
 * chi cerca qualcosa si ritrovava la pagina iniziale, senza risultati
 * e senza capire perche.
 *
 * @package experiences-srl
 */

get_header();

$exp_cerca = is_search() ? get_search_query() : '';
?>

<div class="h-20 lg:h-24"></div>

<main id="main-content">

    <section class="relative bg-gradient-to-br from-primary via-dark to-secondary py-16 lg:py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10" aria-hidden="true">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-accent/30 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                <?php if ( $exp_cerca ) : ?>
                    Risultati per <span class="text-accent">&ldquo;<?php echo esc_html( $exp_cerca ); ?>&rdquo;</span>
                <?php else : ?>
                    Articoli
                <?php endif; ?>
            </h1>
            <?php if ( have_posts() ) : ?>
                <p class="text-white/70">
                    <strong class="text-accent"><?php echo (int) $GLOBALS['wp_query']->found_posts; ?></strong>
                    <?php echo 1 === (int) $GLOBALS['wp_query']->found_posts ? 'risultato' : 'risultati'; ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <section class="py-12 lg:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <?php if ( have_posts() ) : ?>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while ( have_posts() ) : the_post();
                        $exp_cats  = get_the_category();
                        $exp_label = ! empty( $exp_cats ) ? $exp_cats[0]->name : 'Blog';
                    ?>
                        <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group flex flex-col">
                            <a href="<?php the_permalink(); ?>" class="block" tabindex="-1" aria-hidden="true">
                                <div class="aspect-video overflow-hidden bg-gradient-to-br from-primary to-secondary relative">
                                    <?php if ( has_post_thumbnail() ) :
                                        the_post_thumbnail( 'large', [
                                            'class'   => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                            'loading' => 'lazy',
                                        ] );
                                    else : ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-book-open text-white/30 text-5xl" aria-hidden="true"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="absolute top-3 left-3 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full">
                                        <?php echo esc_html( $exp_label ); ?>
                                    </span>
                                </div>
                            </a>
                            <div class="p-5 flex flex-col flex-1">
                                <div class="text-xs text-gray-500 mb-2">
                                    <i class="fas fa-calendar text-accent" aria-hidden="true"></i> <?php echo get_the_date( 'j M Y' ); ?>
                                </div>
                                <h2 class="font-heading text-lg font-bold text-primary mb-3 leading-tight group-hover:text-accent transition-colors">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4 flex-1">
                                    <?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '…' ) ); ?>
                                </p>
                                <span class="inline-flex items-center gap-2 text-secondary font-semibold text-sm group-hover:text-accent transition-colors">
                                    Leggi <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                                </span>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '&larr; Precedenti',
                    'next_text' => 'Successivi &rarr;',
                    'class'     => 'mt-12 text-center',
                ]);
                ?>

            <?php else : ?>
                <div class="max-w-xl mx-auto text-center py-10">
                    <p class="text-gray-600 text-lg mb-6">
                        <?php if ( $exp_cerca ) : ?>
                            Nessun articolo per &ldquo;<?php echo esc_html( $exp_cerca ); ?>&rdquo;. Prova con una parola più generica.
                        <?php else : ?>
                            Non c&rsquo;è ancora niente da mostrare qui.
                        <?php endif; ?>
                    </p>
                    <?php get_search_form(); ?>
                    <p class="mt-8">
                        <a href="<?php echo esc_url( experiences_blog_archive_url() ); ?>"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-secondary hover:bg-accent text-white font-semibold rounded-xl transition-colors">
                            Vedi tutti gli articoli
                        </a>
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>
