<?php get_header(); ?>
<main class="min-h-screen hero-gradient flex items-center justify-center text-center px-4">
    <div>
        <h1 class="font-heading text-8xl font-bold text-accent mb-4">404</h1>
        <h2 class="font-heading text-3xl font-bold text-white mb-6"><?php esc_html_e( 'Pagina non trovata', 'experiences-srl' ); ?></h2>
        <p class="text-gray-300 text-lg mb-8"><?php esc_html_e( 'La pagina che cerchi non esiste o è stata spostata.', 'experiences-srl' ); ?></p>
        <a href="<?php echo esc_url(home_url('/')); ?>"
           class="px-8 py-4 bg-accent hover:bg-secondary text-white font-semibold rounded-xl transition-all inline-flex items-center gap-2">
            <i class="fas fa-home"></i> <?php esc_html_e( 'Torna alla Home', 'experiences-srl' ); ?>
        </a>
    </div>
</main>
<?php get_footer(); ?>
