<?php
/**
 * Site header — navbar + menu mobile, condiviso da tutte le pagine.
 * In homepage gli anchor restano #sezione (smooth scroll); nelle altre
 * pagine (articoli, archivi) puntano a home_url('/#sezione').
 *
 * Le voci sono definite una volta sola e rese due volte (desktop e
 * mobile): prima erano due elenchi scritti a mano e ogni modifica
 * andava fatta in entrambi, con il rischio di dimenticarne uno.
 *
 * @package experiences-srl
 */

$exp_anchor = is_front_page() ? '' : esc_url( home_url( '/' ) );
$exp_logo   = get_template_directory_uri() . '/assets/img/logo.webp';

/**
 * Voci del menu principale.
 *
 * Erano nove. "AI Avatar" e "Chatbot" promettevano al lettore la stessa
 * cosa — un assistente AI — e lo costringevano a scegliere quale fosse
 * quella giusta; ora sono un'unica voce e la sezione le contiene
 * entrambe. "Home" è sparita perché la fa già il logo, e "Demo" si
 * raggiunge da "Lavori": la sezione demo segue subito il portfolio.
 *
 * Modificabile senza toccare il template:
 *   add_filter( 'experiences_nav_items', fn( $items ) => … );
 */
$exp_nav = apply_filters( 'experiences_nav_items', [
    [ 'label' => 'Servizi', 'href' => $exp_anchor . '#services' ],
    [ 'label' => 'Lavori',  'href' => $exp_anchor . '#portfolio' ],
    [ 'label' => 'AI',      'href' => $exp_anchor . '#avatar', 'icon' => 'fas fa-robot' ],
    [ 'label' => 'Prezzi',  'href' => $exp_anchor . '#pricing' ],
    [ 'label' => 'Blog',    'href' => experiences_blog_archive_url() ],
] );

$exp_cta = apply_filters( 'experiences_nav_cta', [
    'label' => 'Contatti',
    'href'  => $exp_anchor . '#contact',
] );
?>

    <!-- Skip link: primo elemento della pagina, raggiungibile con un Tab -->
    <a class="skip-link" href="#main-content">Salta al contenuto principale</a>

    <!-- Header -->
    <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Mobile: segnaposto a sinistra per bilanciare il logo centrato -->
                <div class="w-11 lg:hidden" aria-hidden="true"></div>
                <!-- Logo: centrato su mobile, allineato a sinistra su desktop -->
                <div class="flex-1 flex justify-center lg:justify-start lg:flex-none">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 group">
                        <img src="<?php echo esc_url( $exp_logo ); ?>" alt="Experiences Logo" width="282" height="300" class="logo-img group-hover:scale-105 transition-transform">
                        <div class="hidden sm:block">
                            <span class="font-heading font-bold text-lg lg:text-xl text-primary">EXPERIENCES</span>
                            <span class="block text-xs text-secondary font-medium">SRL</span>
                        </div>
                    </a>
                </div>

                <nav class="hidden lg:flex items-center gap-1" aria-label="Menu principale">
                    <?php foreach ( $exp_nav as $exp_item ) : ?>
                        <a href="<?php echo esc_url( $exp_item['href'] ); ?>"
                           class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-secondary hover:bg-light rounded-lg transition-all<?php echo empty( $exp_item['icon'] ) ? '' : ' flex items-center gap-1'; ?>">
                            <?php if ( ! empty( $exp_item['icon'] ) ) : ?>
                                <i class="<?php echo esc_attr( $exp_item['icon'] ); ?> text-xs text-accent" aria-hidden="true"></i>
                            <?php endif; ?>
                            <?php echo esc_html( $exp_item['label'] ); ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?php echo esc_url( $exp_cta['href'] ); ?>" class="ml-2 px-4 py-2 text-sm font-semibold text-white bg-secondary hover:bg-accent rounded-lg transition-all shadow-md hover:shadow-lg">
                        <?php echo esc_html( $exp_cta['label'] ); ?>
                    </a>
                </nav>

                <!-- 44×44 minimi: sotto quella soglia il dito manca il bersaglio -->
                <button id="mobile-menu-btn" type="button"
                        class="lg:hidden w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-100 transition"
                        aria-label="Apri menu" aria-expanded="false" aria-controls="mobile-menu">
                    <i class="fas fa-bars text-xl text-primary" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Menu mobile. A pannello chiuso il CSS lo rende visibility:hidden,
         così i suoi link escono dall'ordine di tabulazione: prima erano
         raggiungibili col Tab anche da desktop, con il focus che spariva
         fuori schermo. -->
    <nav id="mobile-menu" class="mobile-menu fixed top-0 right-0 h-full w-72 bg-white shadow-2xl z-50 p-6"
         aria-label="Menu principale (mobile)">
        <button id="close-menu" type="button"
                class="absolute top-3 right-3 w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-100"
                aria-label="Chiudi menu">
            <i class="fas fa-times text-xl text-gray-600" aria-hidden="true"></i>
        </button>
        <div class="mt-14 flex flex-col gap-2">
            <?php foreach ( $exp_nav as $exp_item ) : ?>
                <a href="<?php echo esc_url( $exp_item['href'] ); ?>"
                   class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition<?php echo empty( $exp_item['icon'] ) ? '' : ' flex items-center gap-2'; ?>">
                    <?php if ( ! empty( $exp_item['icon'] ) ) : ?>
                        <i class="<?php echo esc_attr( $exp_item['icon'] ); ?> text-accent" aria-hidden="true"></i>
                    <?php endif; ?>
                    <?php echo esc_html( $exp_item['label'] ); ?>
                </a>
            <?php endforeach; ?>
            <a href="<?php echo esc_url( $exp_cta['href'] ); ?>" class="mobile-link px-4 py-3 text-gray-700 hover:text-secondary hover:bg-light rounded-lg font-medium transition">
                <?php echo esc_html( $exp_cta['label'] ); ?>
            </a>
            <a href="https://wa.me/393926917657" class="mt-4 px-4 py-3 bg-green-500 text-white rounded-lg font-medium text-center flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-xl" aria-hidden="true"></i> WhatsApp
            </a>
        </div>
    </nav>
    <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-40 hidden"></div>
