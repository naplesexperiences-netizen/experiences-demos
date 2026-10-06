<?php
/**
 * Cookie consent banner — Experiences Srl
 *
 * Logica GDPR-compliant essenziale:
 *  - I cookie tecnici sono sempre attivi (necessari per il funzionamento).
 *  - Analytics e Marketing sono OPT-IN (devono essere accettati).
 *  - Il consenso è registrato in localStorage (chiave "exp_consent") e in un
 *    cookie "exp_consent_v1" (per consumo lato server da plugin/script PHP).
 *  - Eventi JS personalizzati per integrare GA4 / Meta Pixel / ecc.:
 *      window.dispatchEvent(new CustomEvent('experiences:consent-updated', { detail }))
 *  - Pulsante "Preferenze Cookie" nel footer (selector [data-cookie-settings])
 *    riapre il pannello di personalizzazione.
 *
 * @package experiences-srl
 */

if ( ! function_exists( 'experiences_should_render_cookie_banner' ) ) {
    function experiences_should_render_cookie_banner() {
        return apply_filters( 'experiences_cookie_banner_enabled', (bool) get_theme_mod( 'exp_cookie_banner_enabled', true ) );
    }
}

if ( ! experiences_should_render_cookie_banner() ) {
    return;
}

$privacy_url = get_privacy_policy_url() ?: home_url( '/privacy-policy/' );
$cookie_url  = home_url( '/cookie-policy/' );
?>

<!-- Cookie consent banner (GDPR) -->
<div id="exp-cookie-banner"
     class="exp-cookie-banner fixed inset-x-0 bottom-0 z-[9999] bg-white shadow-2xl border-t-4 border-accent p-5 sm:p-6 transform translate-y-full transition-transform duration-300"
     role="dialog" aria-labelledby="exp-cookie-title" aria-describedby="exp-cookie-desc">
    <div class="max-w-6xl mx-auto flex flex-col lg:flex-row lg:items-center gap-5">
        <div class="flex-1 min-w-0">
            <h2 id="exp-cookie-title" class="font-heading text-lg font-bold text-primary mb-1 flex items-center gap-2">
                <i class="fas fa-info-circle text-accent"></i> Rispettiamo la tua privacy
            </h2>
            <p id="exp-cookie-desc" class="text-sm text-gray-600 leading-relaxed">
                Usiamo cookie tecnici essenziali e, previo tuo consenso, cookie di analytics per
                migliorare il sito e di marketing per mostrarti offerte rilevanti. Puoi accettare
                tutto, accettare solo gli essenziali o personalizzare le tue scelte in ogni momento.
                Maggiori dettagli nella nostra
                <a href="<?php echo esc_url( $cookie_url ); ?>" class="text-secondary hover:text-accent font-medium underline">Cookie Policy</a>
                e
                <a href="<?php echo esc_url( $privacy_url ); ?>" class="text-secondary hover:text-accent font-medium underline">Privacy Policy</a>.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0">
            <button type="button" data-cookie-action="customize" class="px-4 py-2.5 text-sm font-semibold text-secondary border-2 border-secondary hover:bg-secondary hover:text-white rounded-xl transition-all">
                Personalizza
            </button>
            <button type="button" data-cookie-action="reject" class="px-4 py-2.5 text-sm font-semibold text-gray-700 border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 rounded-xl transition-all">
                Solo essenziali
            </button>
            <button type="button" data-cookie-action="accept-all" class="px-5 py-2.5 text-sm font-semibold text-white bg-secondary hover:bg-accent rounded-xl transition-all shadow-md">
                Accetta tutti
            </button>
        </div>
    </div>
</div>

<!-- Cookie preferences modal -->
<div id="exp-cookie-modal" class="exp-cookie-modal fixed inset-0 z-[10000] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="exp-cookie-modal-title">
    <div class="absolute inset-0 bg-black/60" data-cookie-action="close"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h2 id="exp-cookie-modal-title" class="font-heading text-xl font-bold text-primary">Preferenze cookie</h2>
            <button type="button" data-cookie-action="close" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500" aria-label="Chiudi">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-600 leading-relaxed">
                Scegli quali categorie di cookie autorizzare. Puoi modificare queste preferenze
                in qualsiasi momento dal link "Preferenze Cookie" in fondo alla pagina.
            </p>

            <!-- Essenziali -->
            <div class="border border-gray-200 rounded-xl p-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="font-semibold text-primary mb-1 flex items-center gap-2">
                            <i class="fas fa-lock text-accent text-sm"></i> Cookie essenziali
                        </h3>
                        <p class="text-sm text-gray-600">Necessari per il funzionamento del sito (sessione, sicurezza, consenso). Non possono essere disattivati.</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                            <i class="fas fa-check text-xs"></i> Sempre attivi
                        </span>
                    </div>
                </div>
            </div>

            <!-- Analytics -->
            <div class="border border-gray-200 rounded-xl p-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="font-semibold text-primary mb-1 flex items-center gap-2">
                            <i class="fas fa-chart-line text-accent text-sm"></i> Cookie di analytics
                        </h3>
                        <p class="text-sm text-gray-600">Ci aiutano a capire come usi il sito (pagine viste, sessioni) in forma anonima e aggregata. Es: Google Analytics, Microsoft Clarity.</p>
                    </div>
                    <label class="exp-toggle flex-shrink-0">
                        <input type="checkbox" data-cookie-category="analytics" class="sr-only">
                        <span class="exp-toggle-track" aria-hidden="true"></span>
                    </label>
                </div>
            </div>

            <!-- Marketing -->
            <div class="border border-gray-200 rounded-xl p-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="font-semibold text-primary mb-1 flex items-center gap-2">
                            <i class="fas fa-bullhorn text-accent text-sm"></i> Cookie di marketing
                        </h3>
                        <p class="text-sm text-gray-600">Usati per mostrarti annunci più pertinenti su altri siti (es: Meta Pixel, Google Ads remarketing). Attivi solo se acconsenti.</p>
                    </div>
                    <label class="exp-toggle flex-shrink-0">
                        <input type="checkbox" data-cookie-category="marketing" class="sr-only">
                        <span class="exp-toggle-track" aria-hidden="true"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex flex-col sm:flex-row gap-2 justify-end">
            <button type="button" data-cookie-action="reject" class="px-5 py-2.5 text-sm font-semibold text-gray-700 border-2 border-gray-300 hover:bg-gray-100 rounded-xl transition-all">
                Rifiuta tutti
            </button>
            <button type="button" data-cookie-action="save" class="px-5 py-2.5 text-sm font-semibold text-secondary border-2 border-secondary hover:bg-secondary hover:text-white rounded-xl transition-all">
                Salva preferenze
            </button>
            <button type="button" data-cookie-action="accept-all" class="px-5 py-2.5 text-sm font-semibold text-white bg-secondary hover:bg-accent rounded-xl transition-all shadow-md">
                Accetta tutti
            </button>
        </div>
    </div>
</div>
