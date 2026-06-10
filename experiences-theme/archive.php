<?php
/**
 * Archive Template — Lista articoli blog (anche categorie, tag, autori)
 * @package experiences-srl
 */
get_header();

// Determine context
$archive_title = '';
$archive_description = '';
$archive_icon = 'fa-newspaper';

if (is_category()) {
    $archive_title = single_cat_title('', false);
    $archive_description = category_description();
    $archive_icon = 'fa-folder';
} elseif (is_tag()) {
    $archive_title = '#' . single_tag_title('', false);
    $archive_description = tag_description();
    $archive_icon = 'fa-tag';
} elseif (is_author()) {
    $archive_title = 'Articoli di ' . get_the_author();
    $archive_description = get_the_author_meta('description');
    $archive_icon = 'fa-user';
} elseif (is_date()) {
    $archive_title = 'Archivio: ' . get_the_date('F Y');
    $archive_icon = 'fa-calendar';
} else {
    $archive_title = 'Blog & Risorse';
    $archive_description = 'Strategie, guide pratiche e case study per digitalizzare il tuo business turistico.';
}
?>

<!-- Spacer per header fixed -->
<div class="h-20 lg:h-24"></div>

<!-- Hero Archive -->
<section class="relative bg-gradient-to-br from-primary via-dark to-secondary py-16 lg:py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-accent/30 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-secondary/30 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="w-20 h-20 mx-auto mb-6 bg-accent/20 backdrop-blur border border-accent/30 rounded-2xl flex items-center justify-center" data-aos="zoom-in">
            <i class="fas <?php echo esc_attr($archive_icon); ?> text-accent text-3xl"></i>
        </div>
        <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-white mb-4" data-aos="fade-up" data-aos-delay="100">
            <?php echo esc_html($archive_title); ?>
        </h1>
        <?php if ($archive_description) : ?>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                <?php echo wp_kses_post($archive_description); ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<!-- Categorie filter -->
<?php if (!is_category() && !is_tag() && !is_author()) :
    $all_cats = get_categories(['orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true]);
    if (!empty($all_cats)) : ?>
        <section class="py-8 bg-white border-b border-gray-100 sticky top-16 lg:top-20 z-30 backdrop-blur-md bg-white/95">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>"
                       class="flex-shrink-0 px-5 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:bg-secondary transition-all">
                        Tutti
                    </a>
                    <?php foreach ($all_cats as $cat) : ?>
                        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                           class="flex-shrink-0 px-5 py-2 bg-light hover:bg-accent text-secondary hover:text-white text-sm font-semibold rounded-full transition-all">
                            <?php echo esc_html($cat->name); ?>
                            <span class="ml-1 text-xs opacity-70">(<?php echo $cat->count; ?>)</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif;
endif; ?>

<!-- Articoli -->
<section class="py-12 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if (have_posts()) : ?>

            <!-- Featured article (primo articolo grande) -->
            <?php if (!is_paged() && !is_category() && !is_tag()) : ?>
                <?php the_post(); ?>
                <article class="mb-12 lg:mb-16 bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up">
                    <a href="<?php the_permalink(); ?>" class="block lg:grid lg:grid-cols-2">
                        <div class="aspect-video lg:aspect-auto overflow-hidden bg-gradient-to-br from-primary to-secondary relative">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']); ?>
                            <?php else : ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-star text-white/30 text-8xl"></i>
                                </div>
                            <?php endif; ?>
                            <span class="absolute top-4 left-4 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full uppercase tracking-wide">
                                ⭐ In Evidenza
                            </span>
                        </div>
                        <div class="p-8 lg:p-12 flex flex-col justify-center">
                            <?php $cats = get_the_category(); if (!empty($cats)) : ?>
                                <div class="mb-4">
                                    <span class="inline-block px-3 py-1 bg-light text-secondary text-xs font-bold rounded-full uppercase">
                                        <?php echo esc_html($cats[0]->name); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <h2 class="font-heading text-2xl lg:text-3xl xl:text-4xl font-bold text-primary mb-4 leading-tight group-hover:text-accent transition-colors">
                                <?php the_title(); ?>
                            </h2>
                            <p class="text-gray-600 text-base lg:text-lg leading-relaxed mb-6">
                                <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?>
                            </p>
                            <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                                <span class="flex items-center gap-1"><i class="fas fa-calendar text-accent"></i> <?php echo get_the_date('j M Y'); ?></span>
                                <span class="flex items-center gap-1"><i class="fas fa-clock text-accent"></i> <?php echo function_exists('exp_reading_time') ? exp_reading_time() : '5'; ?> min</span>
                            </div>
                            <span class="inline-flex items-center gap-2 text-secondary font-semibold group-hover:text-accent transition-colors">
                                Leggi articolo completo <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                </article>
                <?php rewind_posts(); the_post(); ?>
            <?php endif; ?>

            <!-- Grid articoli -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <?php
                $skip_first = !is_paged() && !is_category() && !is_tag();
                $i = 0;
                while (have_posts()) : the_post();
                    if ($skip_first && $i === 0) { $i++; continue; }
                    $i++;
                    $cats = get_the_category();
                    $primary_cat = !empty($cats) ? $cats[0]->name : 'Blog';
                ?>
                    <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up">
                        <a href="<?php the_permalink(); ?>" class="block">
                            <div class="aspect-video overflow-hidden bg-gradient-to-br from-primary to-secondary relative">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']); ?>
                                <?php else : ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-book-open text-white/30 text-6xl"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="absolute top-4 left-4 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full uppercase tracking-wide">
                                    <?php echo esc_html($primary_cat); ?>
                                </span>
                            </div>
                        </a>
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                <span class="flex items-center gap-1"><i class="fas fa-calendar text-accent"></i> <?php echo get_the_date('j M Y'); ?></span>
                                <span class="flex items-center gap-1"><i class="fas fa-clock text-accent"></i> <?php echo function_exists('exp_reading_time') ? exp_reading_time() : '5'; ?> min</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-primary mb-3 leading-tight group-hover:text-accent transition-colors">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                            </p>
                            <div class="flex items-center justify-between">
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-2 text-secondary font-semibold text-sm hover:text-accent transition-colors">
                                    Leggi <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-user"></i> <?php the_author(); ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Paginazione -->
            <div class="mt-16 flex justify-center" data-aos="fade-up">
                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '<i class="fas fa-arrow-left"></i> Precedente',
                    'next_text' => 'Successiva <i class="fas fa-arrow-right"></i>',
                    'class'     => 'blog-pagination'
                ]);
                ?>
            </div>

        <?php else : ?>

            <!-- Stato vuoto -->
            <div class="text-center py-20" data-aos="fade-up">
                <div class="w-24 h-24 mx-auto mb-6 bg-light rounded-2xl flex items-center justify-center">
                    <i class="fas fa-inbox text-gray-300 text-4xl"></i>
                </div>
                <h2 class="font-heading text-2xl font-bold text-primary mb-3">Nessun articolo trovato</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Non ci sono ancora articoli in questa sezione. Torna presto: stiamo preparando contenuti utili per te.</p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-secondary hover:bg-accent text-white font-semibold rounded-xl transition-all">
                    <i class="fas fa-home"></i> Torna alla home
                </a>
            </div>

        <?php endif; ?>

    </div>
</section>

<!-- CTA Newsletter -->
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-primary to-secondary rounded-3xl p-8 lg:p-12 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-64 h-64 bg-accent/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-secondary/30 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="w-16 h-16 mx-auto mb-6 bg-white/10 backdrop-blur rounded-2xl flex items-center justify-center">
                    <i class="fas fa-envelope text-accent text-2xl"></i>
                </div>
                <h2 class="font-heading text-2xl lg:text-3xl xl:text-4xl font-bold mb-4">
                    Una guida pratica nella tua inbox, ogni settimana
                </h2>
                <p class="text-white/80 mb-8 max-w-2xl mx-auto">
                    Strategie testate per Channel Manager, SEO turismo, conversione siti hotel. Niente spam, solo valore.
                </p>
                <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary font-bold rounded-xl hover:bg-accent hover:text-white transition-all shadow-xl">
                    <i class="fas fa-paper-plane"></i> Iscriviti gratis
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
