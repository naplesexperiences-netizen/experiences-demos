<?php
/**
 * Elenco articoli con filtro per categoria e ricerca dal vivo.
 * Condiviso da home.php (pagina degli articoli) e da page-blog.php
 * (modello di pagina), così esiste una sola copia del markup.
 *
 * Si aspetta $exp_q (WP_Query) e $exp_cats già valorizzati dal chiamante.
 *
 * @package experiences-srl
 */
if ( ! isset( $exp_q ) || ! ( $exp_q instanceof WP_Query ) ) {
    return;
}
$exp_cats = isset( $exp_cats ) ? $exp_cats : get_categories([
    'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true,
]);
?>

    <?php if ( $exp_q->have_posts() ) : ?>

    <!-- Barra filtri -->
    <section class="sticky top-16 lg:top-20 z-30 bg-white/95 backdrop-blur-md border-b border-gray-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                <!-- Ricerca -->
                <div class="lg:w-72 flex-shrink-0">
                    <label for="exp-blog-search" class="sr-only">Cerca fra gli articoli</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true"></i>
                        <input type="search" id="exp-blog-search" autocomplete="off"
                               placeholder="Cerca fra gli articoli…"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-secondary text-sm outline-none">
                    </div>
                </div>

                <!-- Categorie -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide" role="group" aria-label="Filtra per categoria">
                    <button type="button" data-cat="*" aria-pressed="true"
                            class="exp-chip flex-shrink-0 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full transition-colors">
                        Tutti
                    </button>
                    <?php foreach ( $exp_cats as $c ) : ?>
                        <button type="button" data-cat="<?php echo esc_attr( $c->slug ); ?>" aria-pressed="false"
                                class="exp-chip flex-shrink-0 px-4 py-2 bg-light hover:bg-accent text-secondary hover:text-white text-sm font-semibold rounded-full transition-colors">
                            <?php echo esc_html( $c->name ); ?>
                            <span class="ml-1 text-xs opacity-70"><?php echo (int) $c->count; ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Elenco articoli -->
    <section class="py-10 lg:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <p id="exp-blog-count" class="text-sm text-gray-500 mb-6" role="status" aria-live="polite"></p>

            <div id="exp-blog-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <?php while ( $exp_q->have_posts() ) : $exp_q->the_post();
                    $p_cats = get_the_category();
                    $slugs  = implode( ' ', wp_list_pluck( $p_cats, 'slug' ) );
                    $label  = ! empty( $p_cats ) ? $p_cats[0]->name : 'Blog';
                    $mins   = function_exists( 'exp_reading_time' ) ? exp_reading_time() : 5;
                ?>
                    <article class="exp-post blog-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group flex flex-col"
                             data-cats="<?php echo esc_attr( $slugs ); ?>"
                             data-title="<?php echo esc_attr( mb_strtolower( get_the_title() . ' ' . get_the_excerpt() ) ); ?>">
                        <a href="<?php the_permalink(); ?>" class="block" tabindex="-1" aria-hidden="true">
                            <div class="aspect-video overflow-hidden bg-gradient-to-br from-primary to-secondary relative">
                                <?php if ( has_post_thumbnail() ) :
                                    the_post_thumbnail( 'large', [
                                        'class'   => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                        'loading' => 'lazy',
                                    ] );
                                else : ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-book-open text-white/30 text-5xl"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="absolute top-3 left-3 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full">
                                    <?php echo esc_html( $label ); ?>
                                </span>
                            </div>
                        </a>
                        <div class="p-5 flex flex-col flex-1">
                            <div class="text-xs text-gray-500 mb-2 flex items-center gap-3">
                                <span><i class="fas fa-calendar text-accent" aria-hidden="true"></i> <?php echo get_the_date( 'j M Y' ); ?></span>
                                <span><i class="fas fa-clock text-accent" aria-hidden="true"></i> <?php echo esc_html( $mins ); ?> min</span>
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
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <!-- Nessun risultato -->
            <div id="exp-blog-empty" class="hidden text-center py-16">
                <div class="w-16 h-16 bg-light rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-secondary text-2xl" aria-hidden="true"></i>
                </div>
                <h2 class="font-heading text-xl font-bold text-primary mb-2">Nessun articolo trovato</h2>
                <p class="text-gray-600 mb-6">Prova con un altro termine o rimuovi il filtro per categoria.</p>
                <button type="button" id="exp-blog-reset"
                        class="px-6 py-3 bg-secondary hover:bg-accent text-white font-semibold rounded-xl transition-colors">
                    Mostra tutti gli articoli
                </button>
            </div>
        </div>
    </section>

    <?php else : ?>

    <section class="py-20 bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <div class="w-16 h-16 bg-light rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-book-open text-secondary text-2xl" aria-hidden="true"></i>
            </div>
            <h2 class="font-heading text-2xl font-bold text-primary mb-3">Nessun articolo pubblicato</h2>
            <p class="text-gray-600">Gli articoli compariranno qui appena verranno pubblicati.</p>
        </div>
    </section>

    <?php endif; ?>

    <!-- CTA finale -->
    <section class="py-16 bg-gradient-to-br from-primary to-secondary">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-heading text-2xl sm:text-3xl font-bold text-white mb-4">
                Vuoi applicare queste strategie al tuo business?
            </h2>
            <p class="text-white/80 mb-8 leading-relaxed">
                Richiedi un audit gratuito: analizziamo dove perdi prenotazioni e ti diciamo cosa fare. Senza vincoli.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#booking" data-booking-trigger
                   class="px-6 py-3 bg-white text-primary font-semibold rounded-xl hover:bg-accent hover:text-white transition-colors inline-flex items-center justify-center gap-2">
                    <i class="fas fa-rocket" aria-hidden="true"></i> Prenota Audit Gratuito
                </a>
                <a href="https://wa.me/393926917657" target="_blank" rel="noopener"
                   class="px-6 py-3 bg-white/10 backdrop-blur border border-white/30 text-white font-semibold rounded-xl hover:bg-white/20 transition-colors inline-flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp text-xl" aria-hidden="true"></i> WhatsApp
                </a>
            </div>
        </div>
    </section>

<script>
(function () {
    var grid   = document.getElementById('exp-blog-grid');
    if (!grid) return;

    var posts  = Array.prototype.slice.call(grid.querySelectorAll('.exp-post'));
    var chips  = Array.prototype.slice.call(document.querySelectorAll('.exp-chip'));
    var search = document.getElementById('exp-blog-search');
    var count  = document.getElementById('exp-blog-count');
    var empty  = document.getElementById('exp-blog-empty');
    var reset  = document.getElementById('exp-blog-reset');

    var cat = '*', term = '';

    function applica() {
        var visibili = 0;

        posts.forEach(function (p) {
            var okCat = cat === '*' ||
                        (' ' + p.dataset.cats + ' ').indexOf(' ' + cat + ' ') !== -1;
            var okTxt = term === '' || p.dataset.title.indexOf(term) !== -1;
            var mostra = okCat && okTxt;
            p.hidden = !mostra;
            if (mostra) visibili++;
        });

        empty.classList.toggle('hidden', visibili > 0);
        grid.classList.toggle('hidden', visibili === 0);

        count.textContent = visibili === posts.length
            ? posts.length + (posts.length === 1 ? ' articolo' : ' articoli')
            : visibili + ' di ' + posts.length + ' articoli';

        // Lo stato finisce nell'URL: il filtro resta condivisibile e
        // sopravvive a un ricaricamento.
        var qs = new URLSearchParams();
        if (cat !== '*') qs.set('categoria', cat);
        if (term)        qs.set('q', term);
        var s = qs.toString();
        history.replaceState(null, '', s ? '?' + s : location.pathname);
    }

    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            cat = chip.dataset.cat;
            chips.forEach(function (c) {
                var on = c === chip;
                c.setAttribute('aria-pressed', on ? 'true' : 'false');
                c.classList.toggle('bg-primary', on);
                c.classList.toggle('text-white', on);
                c.classList.toggle('bg-light', !on);
                c.classList.toggle('text-secondary', !on);
            });
            applica();
        });
    });

    var timer;
    search.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            term = search.value.trim().toLowerCase();
            applica();
        }, 160);
    });

    reset.addEventListener('click', function () {
        search.value = '';
        term = '';
        var tutti = chips[0];
        if (tutti) tutti.click(); else applica();
        search.focus();
    });

    // Ripristina il filtro dall'URL al caricamento
    var iniziale = new URLSearchParams(location.search);
    var c0 = iniziale.get('categoria'), q0 = iniziale.get('q');
    if (q0) { search.value = q0; term = q0.toLowerCase(); }
    if (c0) {
        var chip = chips.filter(function (x) { return x.dataset.cat === c0; })[0];
        if (chip) { chip.click(); return; }
    }
    applica();
})();
</script>
