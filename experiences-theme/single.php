<?php
/**
 * Single Post Template — Layout articolo blog
 * @package experiences-srl
 */
get_header();

while ( have_posts() ) : the_post();
    $categories = get_the_category();
    $primary_cat = !empty($categories) ? $categories[0]->name : 'Blog';
    $author_id = get_the_author_meta('ID');
    $reading_time = function_exists('exp_reading_time') ? exp_reading_time() : 5;
?>

<!-- Spacer per header fixed -->
<div class="h-20 lg:h-24"></div>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white'); ?>>

    <!-- Hero Section dell'articolo -->
    <section class="relative bg-gradient-to-br from-primary via-dark to-secondary py-16 lg:py-24 overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-accent/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-secondary/30 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-white/60 mb-6" data-aos="fade-up">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-accent transition-colors">
                    <i class="fas fa-home"></i>
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="hover:text-accent transition-colors">Blog</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-accent"><?php echo esc_html($primary_cat); ?></span>
            </nav>

            <!-- Categoria badge -->
            <div class="mb-6" data-aos="fade-up" data-aos-delay="100">
                <?php foreach ($categories as $cat) : ?>
                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                       class="inline-block px-4 py-1 bg-accent/20 backdrop-blur-sm border border-accent/30 text-accent text-xs font-bold rounded-full uppercase tracking-wide mr-2 mb-2 hover:bg-accent hover:text-white transition-all">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Titolo -->
            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-tight mb-6" data-aos="fade-up" data-aos-delay="200">
                <?php the_title(); ?>
            </h1>

            <!-- Excerpt -->
            <?php if (has_excerpt()) : ?>
                <p class="text-xl text-gray-200 leading-relaxed mb-8 max-w-3xl" data-aos="fade-up" data-aos-delay="300">
                    <?php echo esc_html(get_the_excerpt()); ?>
                </p>
            <?php endif; ?>

            <!-- Meta info -->
            <div class="flex flex-wrap items-center gap-6 text-white/80" data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center gap-3">
                    <?php echo get_avatar($author_id, 48, '', '', ['class' => 'rounded-full border-2 border-accent/30']); ?>
                    <div>
                        <p class="text-sm font-semibold text-white"><?php the_author(); ?></p>
                        <p class="text-xs text-white/60">Autore</p>
                    </div>
                </div>
                <div class="hidden sm:block w-px h-10 bg-white/20"></div>
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-calendar text-accent"></i>
                    <span><?php echo get_the_date('j F Y'); ?></span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-clock text-accent"></i>
                    <span><?php echo esc_html($reading_time); ?> min di lettura</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-eye text-accent"></i>
                    <span><?php echo function_exists('exp_get_post_views') ? exp_get_post_views(get_the_ID()) : '0'; ?> views</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="relative -mt-12 lg:-mt-16 z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up">
            <div class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white">
                <?php the_post_thumbnail('full', ['class' => 'w-full h-auto']); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Content area -->
    <div class="py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-12">

                <!-- Sidebar sinistra: Share & TOC -->
                <aside class="hidden lg:block lg:col-span-1">
                    <div class="sticky top-28 space-y-6">
                        <!-- Share buttons -->
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase mb-3 tracking-wider">Condividi</p>
                            <div class="flex flex-col gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                                   target="_blank" rel="noopener"
                                   class="w-10 h-10 rounded-full bg-light hover:bg-accent text-secondary hover:text-white flex items-center justify-center transition-all"
                                   aria-label="Condividi su Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                                   target="_blank" rel="noopener"
                                   class="w-10 h-10 rounded-full bg-light hover:bg-accent text-secondary hover:text-white flex items-center justify-center transition-all"
                                   aria-label="Condividi su Twitter">
                                    <i class="fab fa-x-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>"
                                   target="_blank" rel="noopener"
                                   class="w-10 h-10 rounded-full bg-light hover:bg-accent text-secondary hover:text-white flex items-center justify-center transition-all"
                                   aria-label="Condividi su LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>"
                                   target="_blank" rel="noopener"
                                   class="w-10 h-10 rounded-full bg-light hover:bg-green-500 text-secondary hover:text-white flex items-center justify-center transition-all"
                                   aria-label="Condividi su WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <button onclick="navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>'); this.querySelector('i').className='fas fa-check'; setTimeout(()=>this.querySelector('i').className='fas fa-link', 2000);"
                                        class="w-10 h-10 rounded-full bg-light hover:bg-accent text-secondary hover:text-white flex items-center justify-center transition-all"
                                        aria-label="Copia link">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Contenuto articolo -->
                <div class="lg:col-span-8">
                    <div class="prose-blog text-gray-700">
                        <?php the_content(); ?>
                    </div>

                    <!-- Mobile share buttons -->
                    <div class="lg:hidden mt-12 pt-8 border-t border-gray-200">
                        <p class="text-sm font-semibold text-gray-500 mb-4">Condividi questo articolo:</p>
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="flex-1 py-3 bg-light hover:bg-accent text-secondary hover:text-white text-center rounded-lg transition-all"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="flex-1 py-3 bg-light hover:bg-accent text-secondary hover:text-white text-center rounded-lg transition-all"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="flex-1 py-3 bg-light hover:bg-accent text-secondary hover:text-white text-center rounded-lg transition-all"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" rel="noopener" class="flex-1 py-3 bg-light hover:bg-green-500 text-secondary hover:text-white text-center rounded-lg transition-all"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>

                    <!-- Tags -->
                    <?php if (has_tag()) : ?>
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-500 mb-4 flex items-center gap-2"><i class="fas fa-tags text-accent"></i> Tag:</p>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach (get_the_tags() as $tag) : ?>
                                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                                       class="px-4 py-2 bg-light hover:bg-secondary text-secondary hover:text-white text-xs font-semibold rounded-full transition-all">
                                        #<?php echo esc_html($tag->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Author box -->
                    <div class="mt-12 p-6 lg:p-8 bg-gradient-to-br from-light to-white rounded-2xl border border-gray-100">
                        <div class="flex items-start gap-4">
                            <?php echo get_avatar($author_id, 80, '', '', ['class' => 'rounded-full border-4 border-accent/20']); ?>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Scritto da</p>
                                <h3 class="font-heading text-xl font-bold text-primary mb-2"><?php the_author(); ?></h3>
                                <p class="text-gray-600 text-sm leading-relaxed mb-3">
                                    <?php
                                    $bio = get_the_author_meta('description');
                                    echo $bio ? esc_html($bio) : 'Esperto di digitalizzazione per il turismo. Aiuta hotel, agenzie e tour operator a crescere online con Channel Manager, SEO e AI.';
                                    ?>
                                </p>
                                <div class="flex gap-3">
                                    <?php $author_url = get_the_author_meta('user_url'); if ($author_url) : ?>
                                        <a href="<?php echo esc_url($author_url); ?>" target="_blank" rel="noopener" class="text-secondary hover:text-accent text-sm font-semibold">
                                            <i class="fas fa-globe"></i> Sito web
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="text-secondary hover:text-accent text-sm font-semibold">
                                        <i class="fas fa-edit"></i> Altri articoli
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA box -->
                    <div class="mt-8 p-8 bg-gradient-to-br from-primary to-secondary rounded-2xl text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <h3 class="font-heading text-2xl lg:text-3xl font-bold mb-3">
                                Vuoi applicare queste strategie al tuo business?
                            </h3>
                            <p class="text-white/80 mb-6 leading-relaxed">
                                Richiedi un'audit gratuito del tuo sito. Analizziamo dove perdi prenotazioni e ti diciamo esattamente cosa fare. Senza vincoli.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="px-6 py-3 bg-white text-primary font-semibold rounded-xl hover:bg-accent hover:text-white transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-rocket"></i> Audit Gratuito
                                </a>
                                <a href="https://wa.me/393926917657" class="px-6 py-3 bg-white/10 backdrop-blur border border-white/30 text-white font-semibold rounded-xl hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                                    <i class="fab fa-whatsapp text-xl"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar destra: TOC + Newsletter -->
                <aside class="hidden lg:block lg:col-span-3 mt-12 lg:mt-0">
                    <div class="sticky top-28 space-y-6">

                        <!-- Newsletter box -->
                        <div class="bg-gradient-to-br from-accent/10 to-secondary/10 border border-accent/20 rounded-2xl p-6">
                            <div class="w-12 h-12 bg-accent rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <h3 class="font-heading text-lg font-bold text-primary mb-2">Newsletter</h3>
                            <p class="text-gray-600 text-sm mb-4">Ricevi una guida pratica ogni settimana per crescere il tuo business turistico.</p>
                            <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="block w-full py-3 px-4 bg-secondary hover:bg-accent text-white text-center text-sm font-semibold rounded-lg transition-all">
                                Iscriviti gratis
                            </a>
                        </div>

                        <!-- Categorie popolari -->
                        <?php
                        $cats = get_categories(['number' => 6, 'orderby' => 'count', 'order' => 'DESC']);
                        if (!empty($cats)) : ?>
                            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                                <h3 class="font-heading text-lg font-bold text-primary mb-4 flex items-center gap-2">
                                    <i class="fas fa-folder text-accent"></i> Categorie
                                </h3>
                                <ul class="space-y-2">
                                    <?php foreach ($cats as $cat) : ?>
                                        <li>
                                            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                                               class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-light text-gray-700 hover:text-secondary transition-all text-sm">
                                                <span><?php echo esc_html($cat->name); ?></span>
                                                <span class="text-xs text-gray-400"><?php echo $cat->count; ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                    </div>
                </aside>

            </div>
        </div>
    </div>

    <!-- Articoli correlati -->
    <?php
    $related = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post__not_in'   => [get_the_ID()],
        'category__in'   => wp_list_pluck($categories, 'term_id'),
        'orderby'        => 'rand'
    ]);

    if ($related->have_posts()) : ?>
        <section class="py-16 lg:py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="inline-block px-4 py-1 bg-light text-secondary text-sm font-semibold rounded-full mb-3">ARTICOLI CORRELATI</span>
                    <h2 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-bold text-primary">Continua a leggere</h2>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <?php while ($related->have_posts()) : $related->the_post();
                        $rel_cats = get_the_category();
                        $rel_cat = !empty($rel_cats) ? $rel_cats[0]->name : 'Blog';
                    ?>
                        <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group">
                            <a href="<?php the_permalink(); ?>" class="block">
                                <div class="aspect-video overflow-hidden bg-gradient-to-br from-primary to-secondary relative">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-book-open text-white/30 text-5xl"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="absolute top-3 left-3 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full">
                                        <?php echo esc_html($rel_cat); ?>
                                    </span>
                                </div>
                            </a>
                            <div class="p-5">
                                <div class="text-xs text-gray-500 mb-2">
                                    <i class="fas fa-calendar text-accent"></i> <?php echo get_the_date('j M Y'); ?>
                                </div>
                                <h3 class="font-heading text-lg font-bold text-primary mb-3 leading-tight group-hover:text-accent transition-colors">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-2 text-secondary font-semibold text-sm hover:text-accent">
                                    Leggi <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Navigation precedente/successivo -->
    <section class="py-12 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 gap-4">
                <?php
                $prev = get_previous_post();
                if ($prev) : ?>
                    <a href="<?php echo esc_url(get_permalink($prev->ID)); ?>" class="group p-6 bg-light hover:bg-accent rounded-xl transition-all">
                        <div class="flex items-center gap-2 text-xs text-secondary group-hover:text-white mb-2">
                            <i class="fas fa-arrow-left"></i> Articolo precedente
                        </div>
                        <p class="font-heading font-bold text-primary group-hover:text-white text-sm leading-tight">
                            <?php echo esc_html($prev->post_title); ?>
                        </p>
                    </a>
                <?php endif;

                $next = get_next_post();
                if ($next) : ?>
                    <a href="<?php echo esc_url(get_permalink($next->ID)); ?>" class="group p-6 bg-light hover:bg-accent rounded-xl transition-all text-right">
                        <div class="flex items-center justify-end gap-2 text-xs text-secondary group-hover:text-white mb-2">
                            Articolo successivo <i class="fas fa-arrow-right"></i>
                        </div>
                        <p class="font-heading font-bold text-primary group-hover:text-white text-sm leading-tight">
                            <?php echo esc_html($next->post_title); ?>
                        </p>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

</article>

<?php
endwhile;
get_footer();
?>
