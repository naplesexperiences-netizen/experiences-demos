<?php
/**
 * Booking modal — Cal.com embed per audit gratuito.
 *
 * Configurazione:
 *  - URL configurabile da Personalizza tema → Audit gratuito (Cal.com)
 *    oppure tramite costante: define('EXP_CAL_LINK', 'https://cal.com/.../audit-gratuito')
 *  - Funziona con Cal.com e Calendly: rilevamento automatico in main.js.
 *  - Apertura modal:
 *      <a href="#booking" data-booking-trigger>Prenota audit</a>
 *      <button data-booking-trigger>Prenota</button>
 *
 * Se nessun link è configurato, il modal mostra un fallback con WhatsApp
 * e form contatti — meglio che bottoni rotti.
 *
 * @package experiences-srl
 */

$cal_link = defined( 'EXP_CAL_LINK' ) ? EXP_CAL_LINK : get_theme_mod( 'exp_cal_link', '' );
$cal_link = esc_url( $cal_link );
?>

<div id="exp-booking-modal" class="exp-booking-modal fixed inset-0 z-[10000] hidden items-stretch sm:items-center justify-center sm:p-4" role="dialog" aria-modal="true" aria-labelledby="exp-booking-title" data-cal-link="<?php echo $cal_link; ?>">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-booking-action="close"></div>

    <div class="relative bg-white sm:rounded-2xl shadow-2xl w-full sm:max-w-4xl h-full sm:h-auto sm:max-h-[92vh] flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary to-secondary px-5 sm:px-8 py-4 sm:py-5 flex items-center justify-between text-white flex-shrink-0">
            <div class="min-w-0">
                <h2 id="exp-booking-title" class="font-heading text-lg sm:text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-rocket text-accent"></i> Prenota il tuo Audit Gratuito
                </h2>
                <p class="text-sm text-white/80 mt-1 hidden sm:block">30 minuti di call. Analizziamo dove perdi prenotazioni e ti diciamo esattamente cosa fare.</p>
            </div>
            <button type="button" data-booking-action="close" class="w-10 h-10 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center flex-shrink-0" aria-label="Chiudi">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Embed area -->
        <div class="flex-1 overflow-hidden bg-gray-50 relative min-h-[500px]">
            <?php if ( $cal_link ) : ?>
                <!-- Loading skeleton (rimosso quando l'iframe è pronto) -->
                <div data-booking-loader class="absolute inset-0 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-4xl mb-3 text-secondary"></i>
                        <p class="text-sm">Carico il calendario…</p>
                    </div>
                </div>
                <!-- iframe creato da JS al primo open (lazy: nessun network finché il modal non viene aperto) -->
                <div data-booking-iframe-slot class="absolute inset-0"></div>
            <?php else : ?>
                <!-- Fallback: nessun link configurato -->
                <div class="absolute inset-0 flex items-center justify-center p-6">
                    <div class="text-center max-w-md">
                        <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fab fa-whatsapp text-3xl text-accent"></i>
                        </div>
                        <h3 class="font-heading text-xl font-bold text-primary mb-2">Contattaci direttamente</h3>
                        <p class="text-gray-600 text-sm mb-6">
                            Scrivici su WhatsApp o usa il form contatti: ti rispondiamo entro 24 ore con la disponibilità per la tua call gratuita.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="https://wa.me/393926917657" target="_blank" rel="noopener" class="px-5 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-xl"></i> WhatsApp
                            </a>
                            <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" data-booking-action="close" class="px-5 py-3 bg-secondary hover:bg-accent text-white font-semibold rounded-xl flex items-center justify-center gap-2">
                                <i class="fas fa-envelope"></i> Form contatti
                            </a>
                        </div>
                        <?php if ( current_user_can( 'manage_options' ) ) : ?>
                            <p class="mt-6 text-xs text-gray-400 border-t border-gray-200 pt-4">
                                <i class="fas fa-info-circle"></i> Admin: configura il link Cal.com da
                                <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=exp_cal_link' ) ); ?>" class="text-secondary underline">Personalizza tema</a>.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Trust strip -->
        <div class="bg-white border-t border-gray-200 px-5 sm:px-8 py-3 sm:py-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs sm:text-sm text-gray-600 flex-shrink-0">
            <span class="inline-flex items-center gap-1.5"><i class="fas fa-check-circle text-emerald-500"></i> Nessun impegno</span>
            <span class="inline-flex items-center gap-1.5"><i class="fas fa-clock text-accent"></i> 30 min</span>
            <span class="inline-flex items-center gap-1.5"><i class="fas fa-video text-secondary"></i> Online (Google Meet/Zoom)</span>
            <span class="inline-flex items-center gap-1.5"><i class="fas fa-gift text-primary"></i> 100% gratuito</span>
        </div>
    </div>
</div>
