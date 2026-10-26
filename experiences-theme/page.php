<?php
/**
 * Template per le pagine singole.
 *
 * Mancava del tutto. Senza page.php la gerarchia dei template di
 * WordPress scende fino a index.php, che in questo tema fa
 * get_template_part('front-page'): ogni pagina senza un modello proprio
 * rispondeva 200 ma mostrava la homepage. Privacy Policy, Cookie Policy
 * e Termini e Condizioni erano in quello stato — il banner cookie
 * linkava alla privacy e apriva la pagina iniziale.
 *
 * Il contenuto passa da the_content(), quindi riceve anche il filtro
 * che sostituisce i segnaposto dei dati legali.
 *
 * @package experiences-srl
 */

get_header();
?>

<!-- Spazio per l'intestazione fissa -->
<div class="h-20 lg:h-24"></div>

<main id="main-content">

<?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white' ); ?>>

        <!-- Intestazione -->
        <section class="relative bg-gradient-to-br from-primary via-dark to-secondary py-16 lg:py-20 overflow-hidden">
            <div class="absolute inset-0 opacity-10" aria-hidden="true">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-accent/30 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-secondary/30 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                    <?php the_title(); ?>
                </h1>
                <?php if ( get_the_modified_date() ) : ?>
                    <p class="mt-4 text-sm text-white/70">
                        Ultimo aggiornamento:
                        <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_modified_date( 'j F Y' ) ); ?>
                        </time>
                    </p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Contenuto -->
        <section class="py-12 lg:py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose-blog text-gray-700">
                    <?php the_content(); ?>
                </div>

                <?php
                wp_link_pages([
                    'before' => '<nav class="mt-8 text-sm text-gray-500" aria-label="Pagine di questo contenuto">Pagine: ',
                    'after'  => '</nav>',
                ]);
                ?>
            </div>
        </section>

    </article>

<?php endwhile; ?>

</main>

<?php get_footer(); ?>
