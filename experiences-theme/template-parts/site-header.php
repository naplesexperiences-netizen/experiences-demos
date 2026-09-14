<?php
/**
 * Site header — navbar + mobile menu, condiviso da tutte le pagine.
 * In homepage gli anchor restano #sezione (smooth scroll); nelle altre
 * pagine (articoli, archivi) puntano a home_url('/#sezione').
 *
 * @package experiences-srl
 */

$exp_anchor = is_front_page() ? '' : esc_url( home_url( '/' ) );
$exp_logo   = get_template_directory_uri() . '/assets/img/logo.webp';
?>

    <!-- Header -->
    <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Mobile: hamburger left placeholder to balance logo center -->
                <div class="w-10 lg:hidden"></div>
                <!-- Logo: absolutely centered on mobile, left-aligned on desktop -->
                <div class="flex-1 flex justify-center lg:justify-start lg:flex-none">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 group">
                        <img src="<?php echo esc_url( $exp_logo ); ?>" alt="Experiences Logo" width="282" height="300" class="logo-img group-hover:scale-105 transition-transform">
                        <div class="hidden sm:block">
                            <span class="font-heading font-bold text-lg lg:text-xl text-primary">EXPERIENCES</span>
                            <span class="block text-xs text-secondary font-medium">SRL</span>
                        </div>
                    </a>
                </div>
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="<?php echo $exp_anchor; ?>#home" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-secondary hover:bg-light rounded-lg transition-all">Home</a>
                    <a href="<?php echo $exp_anchor; ?>#services" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-secondary hover:bg-light rounded-lg transition-all">Servizi</a>
                    <a href="<?php echo $exp_anchor; ?>#portfolio" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-secondary hover:bg-light rounded-lg transition-all">Portfolio</a>
                    <a href="<?php echo $exp_anchor; ?>#avatar" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-secondary hover:bg-light rounded-lg transition-all">AI Avatar</a>
                    <a href="<?php echo $exp_anchor; ?>#blog" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-secondary hover:bg-light rounded-lg transition-all">Blog</a>
                    <a href="<?php echo $exp_anchor; ?>#pricing" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-secondary hover:bg-light rounded-lg transition-all">Prezzi</a>
                    <a href="<?php echo $exp_anchor; ?>#chatbot" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-secondary hover:bg-light rounded-lg transition-all flex items-center gap-1"><i class="fas fa-robot text-xs text-accent"></i> Chatbot</a>
                    <a href="<?php echo $exp_anchor; ?>#contact" class="ml-2 px-4 py-2 text-sm font-semibold text-white bg-secondary hover:bg-accent rounded-lg transition-all shadow-md hover:shadow-lg">Contatti</a>
                </nav>
                <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition" aria-label="Apri menu">
                    <i class="fas fa-bars text-xl text-primary"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="mobile-menu fixed top-0 right-0 h-full w-72 bg-white shadow-2xl z-50 p-6">
        <button id="close-menu" class="absolute top-4 right-4 p-2 rounded-lg hover:bg-gray-100" aria-label="Chiudi menu">
            <i class="fas fa-times text-xl text-gray-600"></i>
        </button>
        <div class="mt-12 flex flex-col gap-2">
            <a href="<?php echo $exp_anchor; ?>#home" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition">Home</a>
            <a href="<?php echo $exp_anchor; ?>#services" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition">Servizi</a>
            <a href="<?php echo $exp_anchor; ?>#portfolio" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition">Portfolio</a>
            <a href="<?php echo $exp_anchor; ?>#avatar" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition">AI Avatar</a>
            <a href="<?php echo $exp_anchor; ?>#blog" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition">Blog</a>
            <a href="<?php echo $exp_anchor; ?>#pricing" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition">Prezzi</a>
            <a href="<?php echo $exp_anchor; ?>#chatbot" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition flex items-center gap-2"><i class="fas fa-robot text-accent"></i> Chatbot</a>
            <a href="<?php echo $exp_anchor; ?>#contact" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition">Contatti</a>
            <a href="https://wa.me/393926917657" class="mt-4 px-4 py-3 bg-green-500 text-white rounded-lg font-medium text-center flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-xl"></i> WhatsApp
            </a>
        </div>
    </div>
    <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-40 hidden"></div>
