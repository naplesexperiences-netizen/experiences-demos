<?php
/**
 * Site footer — footer + WhatsApp float, condiviso da tutte le pagine.
 *
 * @package experiences-srl
 */

$exp_anchor = is_front_page() ? '' : esc_url( home_url( '/' ) );
$exp_logo   = get_template_directory_uri() . '/assets/img/logo.webp';

// Stesse voci dell'intestazione, piu i Contatti che in alto sono il
// bottone della CTA. Prima erano un secondo elenco scritto a mano, che
// infatti era rimasto indietro.
$exp_nav  = experiences_nav_items();
$exp_nav[] = experiences_nav_cta();
?>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Il blocco del marchio sta fuori dalla griglia: dentro
                 avrebbe avuto bisogno di col-span-2, che nel Tailwind
                 compilato del tema non esiste. Fuori funziona senza
                 classi nuove, ed e anche piu sensato — non e una delle
                 colonne di link. -->
            <div class="mb-12 max-w-md">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 mb-4">
                    <img src="<?php echo esc_url( $exp_logo ); ?>" alt="<?php echo esc_attr__( 'Experiences Logo', 'experiences-srl' ); ?>" width="282" height="300" class="logo-img">
                    <div><span class="font-heading font-bold text-lg"><?php esc_html_e( 'EXPERIENCES', 'experiences-srl' ); ?></span><span class="block text-xs text-accent"><?php esc_html_e( 'SRL', 'experiences-srl' ); ?></span></div>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed mb-6"><?php esc_html_e( 'Soluzioni digitali complete per il settore turistico. Digitalizzazione, marketing e AI per agenzie di viaggi e strutture alberghiere.', 'experiences-srl' ); ?></p>
                <div class="flex gap-3">
                    <a href="https://wa.me/393926917657" target="_blank" rel="noopener" class="w-10 h-10 bg-green-500 hover:bg-green-600 rounded-lg flex items-center justify-center transition" title="<?php echo esc_attr__( 'WhatsApp', 'experiences-srl' ); ?>"><i class="fab fa-whatsapp text-xl" aria-hidden="true"></i></a>
                </div>
            </div>

            <!-- Due colonne gia dal telefono: in colonna singola le tre
                 liste diventavano uno scorrimento lunghissimo. -->
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-10 mb-12">
                <div>
                    <h3 class="font-heading font-semibold text-lg mb-4"><?php esc_html_e( 'Menu', 'experiences-srl' ); ?></h3>
                    <ul class="space-y-3">
                        <?php foreach ( $exp_nav as $exp_voce ) : ?>
                            <li>
                                <a href="<?php echo esc_url( $exp_voce['href'] ); ?>"
                                   class="text-gray-400 hover:text-accent transition text-sm flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-xs text-accent/50" aria-hidden="true"></i>
                                    <?php echo esc_html( $exp_voce['label'] ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-lg mb-4"><?php esc_html_e( 'Servizi', 'experiences-srl' ); ?></h3>
                    <ul class="space-y-3">
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm"><?php esc_html_e( 'Sviluppo Sito Web', 'experiences-srl' ); ?></a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm"><?php esc_html_e( 'SEO/SEM Marketing', 'experiences-srl' ); ?></a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm"><?php esc_html_e( 'Channel Manager', 'experiences-srl' ); ?></a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm"><?php esc_html_e( 'Gestione OTA', 'experiences-srl' ); ?></a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm"><?php esc_html_e( 'AI Assistant', 'experiences-srl' ); ?></a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-lg mb-4"><?php esc_html_e( 'Contatti', 'experiences-srl' ); ?></h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fab fa-whatsapp text-accent mt-1"></i>
                            <a href="https://wa.me/393926917657" target="_blank" class="text-gray-400 hover:text-accent transition text-sm"><?php esc_html_e( 'WhatsApp Business', 'experiences-srl' ); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
            // Dati societari: obbligatori sul sito di un'impresa italiana
            // e finora assenti dal footer. Si compilano dal
            // Personalizzatore → Dati legali.
            $exp_societari = experiences_dati_societari();
            if ( $exp_societari ) : ?>
                <div class="border-t border-white/10 pt-8 pb-6">
                    <p class="text-gray-500 text-xs leading-relaxed"><?php echo esc_html( $exp_societari ); ?></p>
                </div>
            <?php endif; ?>

            <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">© <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( experiences_legal_values()['company'] ); ?>. Tutti i diritti riservati.</p>
                <div class="flex flex-wrap gap-6 text-sm text-gray-500">
                    <a href="<?php echo esc_url( get_privacy_policy_url() ?: home_url( '/privacy-policy/' ) ); ?>" class="hover:text-accent transition"><?php esc_html_e( 'Privacy Policy', 'experiences-srl' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>" class="hover:text-accent transition"><?php esc_html_e( 'Cookie Policy', 'experiences-srl' ); ?></a>
                    <a href="<?php echo esc_url( experiences_cookie_settings_url() ); ?>" class="hover:text-accent transition"<?php echo experiences_cookie_settings_apre_pannello() ? ' data-cookie-settings' : ''; ?>><?php esc_html_e( 'Preferenze Cookie', 'experiences-srl' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/termini-e-condizioni/' ) ); ?>" class="hover:text-accent transition"><?php esc_html_e( 'Termini e Condizioni', 'experiences-srl' ); ?></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/393926917657" target="_blank" class="whatsapp-float w-14 h-14 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center text-white shadow-2xl transition-transform hover:scale-110" title="<?php echo esc_attr__( 'Contattaci su WhatsApp', 'experiences-srl' ); ?>">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
