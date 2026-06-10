<?php
/**
 * Site footer — footer + WhatsApp float, condiviso da tutte le pagine.
 *
 * @package experiences-srl
 */

$exp_anchor = is_front_page() ? '' : esc_url( home_url( '/' ) );
$exp_logo   = get_template_directory_uri() . '/assets/img/logo.webp';
?>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 mb-4">
                        <img src="<?php echo esc_url( $exp_logo ); ?>" alt="Experiences Logo" width="282" height="300" class="logo-img">
                        <div><span class="font-heading font-bold text-lg">EXPERIENCES</span><span class="block text-xs text-accent">SRL</span></div>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">Soluzioni digitali complete per il settore turistico. Digitalizzazione, marketing e AI per agenzie di viaggi e strutture alberghiere.</p>
                    <div class="flex gap-3">
                        <a href="https://wa.me/393926917657" target="_blank" class="w-10 h-10 bg-green-500 hover:bg-green-600 rounded-lg flex items-center justify-center transition" title="WhatsApp"><i class="fab fa-whatsapp text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-heading font-semibold text-lg mb-4">Menu</h4>
                    <ul class="space-y-3">
                        <li><a href="<?php echo $exp_anchor; ?>#home" class="text-gray-400 hover:text-accent transition text-sm flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-accent/50"></i> Home</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-accent/50"></i> Servizi</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#portfolio" class="text-gray-400 hover:text-accent transition text-sm flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-accent/50"></i> Portfolio</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#pricing" class="text-gray-400 hover:text-accent transition text-sm flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-accent/50"></i> Prezzi</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#chatbot" class="text-gray-400 hover:text-accent transition text-sm flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-accent/50"></i> Chatbot AI</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#contact" class="text-gray-400 hover:text-accent transition text-sm flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-accent/50"></i> Contatti</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-heading font-semibold text-lg mb-4">Servizi</h4>
                    <ul class="space-y-3">
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm">Sviluppo Sito Web</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm">SEO/SEM Marketing</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm">Channel Manager</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm">Gestione OTA</a></li>
                        <li><a href="<?php echo $exp_anchor; ?>#services" class="text-gray-400 hover:text-accent transition text-sm">AI Assistant</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-heading font-semibold text-lg mb-4">Contatti</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fab fa-whatsapp text-accent mt-1"></i>
                            <a href="https://wa.me/393926917657" target="_blank" class="text-gray-400 hover:text-accent transition text-sm">WhatsApp Business</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">© <?php echo esc_html( date( 'Y' ) ); ?> Experiences Srl. Tutti i diritti riservati.</p>
                <div class="flex gap-6 text-sm text-gray-500">
                    <a href="#" class="hover:text-accent transition">Privacy Policy</a>
                    <a href="#" class="hover:text-accent transition">Cookie Policy</a>
                    <a href="#" class="hover:text-accent transition">Termini e Condizioni</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/393926917657" target="_blank" class="whatsapp-float w-14 h-14 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center text-white shadow-2xl transition-transform hover:scale-110" title="Contattaci su WhatsApp">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
