<?php
/**
 * Template Name: Configuratore anteprima
 *
 * Il visitatore sceglie il tipo di attività, scrive nome e città, carica
 * qualche fotografia e vede subito come verrebbe il suo sito, in tre
 * stili diversi.
 *
 * Le fotografie non lasciano mai il browser: l'anteprima si costruisce
 * con URL.createObjectURL, quindi nessun caricamento sul server, niente
 * da conservare e niente da dichiarare. Al modulo finale viaggiano solo
 * le scelte e i contatti, mai le immagini.
 *
 * L'invio passa dall'azione experiences_contact già esistente, così
 * eredita il recupero del nonce quando la pagina arriva dalla cache.
 *
 * @package experiences-srl
 */

get_header();
?>

<div class="h-20 lg:h-24"></div>

<main id="main-content">

<section class="expcfg">
    <div class="expcfg-wrap">

        <header class="expcfg-intro">
            <p class="expcfg-occhiello"><?php esc_html_e( 'Anteprima gratuita', 'experiences-srl' ); ?></p>
            <h1 class="expcfg-titolo"><?php echo wp_kses_post( __( 'Guarda come verrebbe <em>il tuo sito</em>', 'experiences-srl' ) ); ?></h1>
            <p class="expcfg-sommario"><?php esc_html_e( 'Tre domande e qualche fotografia. In meno di un minuto vedi la tua struttura dentro un sito vero, in tre stili diversi.', 'experiences-srl' ); ?></p>
            <p class="expcfg-nota-privacy">
                <span aria-hidden="true">&#128274;</span>
                <?php esc_html_e( 'Le fotografie restano sul tuo dispositivo: l’anteprima si costruisce qui nel browser e le immagini non vengono caricate da nessuna parte.', 'experiences-srl' ); ?>
            </p>
        </header>

        <!-- ── Passo 1: tipo di attività ───────────────────────────── -->
        <fieldset class="expcfg-passo" id="expcfg-p1">
            <legend><span class="expcfg-num">1</span> <?php esc_html_e( 'Che attività hai?', 'experiences-srl' ); ?></legend>
            <div class="expcfg-scelte" role="radiogroup" aria-label="<?php echo esc_attr__( 'Tipo di attività', 'experiences-srl' ); ?>">
                <button type="button" class="expcfg-scelta" data-tipo="hotel" role="radio" aria-checked="false">
                    <span class="expcfg-icona" aria-hidden="true"><i class="fas fa-hotel"></i></span>
                    <span class="expcfg-scelta-tit"><?php esc_html_e( 'Hotel o B&B', 'experiences-srl' ); ?></span>
                    <span class="expcfg-scelta-sub"><?php esc_html_e( 'Camere, servizi, prenotazione', 'experiences-srl' ); ?></span>
                </button>
                <button type="button" class="expcfg-scelta" data-tipo="tour" role="radio" aria-checked="false">
                    <span class="expcfg-icona" aria-hidden="true"><i class="fas fa-map-marked-alt"></i></span>
                    <span class="expcfg-scelta-tit"><?php esc_html_e( 'Tour ed esperienze', 'experiences-srl' ); ?></span>
                    <span class="expcfg-scelta-sub"><?php esc_html_e( 'Escursioni, durata, partenze', 'experiences-srl' ); ?></span>
                </button>
                <button type="button" class="expcfg-scelta" data-tipo="altro" role="radio" aria-checked="false">
                    <span class="expcfg-icona" aria-hidden="true"><i class="fas fa-suitcase"></i></span>
                    <span class="expcfg-scelta-tit"><?php esc_html_e( 'Altra attività', 'experiences-srl' ); ?></span>
                    <span class="expcfg-scelta-sub"><?php esc_html_e( 'Ristorante, noleggio, servizi', 'experiences-srl' ); ?></span>
                </button>
            </div>
        </fieldset>

        <!-- ── Passo 2: nome e città ───────────────────────────────── -->
        <fieldset class="expcfg-passo" id="expcfg-p2">
            <legend><span class="expcfg-num">2</span> <?php esc_html_e( 'Come si chiama?', 'experiences-srl' ); ?></legend>
            <div class="expcfg-campi">
                <p class="expcfg-campo">
                    <label for="expcfg-nome"><?php esc_html_e( 'Nome della struttura', 'experiences-srl' ); ?></label>
                    <input type="text" id="expcfg-nome" maxlength="60" autocomplete="organization"
                           placeholder="<?php echo esc_attr__( 'Villa Serena', 'experiences-srl' ); ?>">
                </p>
                <p class="expcfg-campo">
                    <label for="expcfg-citta"><?php esc_html_e( 'Città o località', 'experiences-srl' ); ?></label>
                    <input type="text" id="expcfg-citta" maxlength="60" autocomplete="address-level2"
                           placeholder="<?php echo esc_attr__( 'Sorrento', 'experiences-srl' ); ?>">
                </p>
            </div>
        </fieldset>

        <!-- ── Passo 3: fotografie ─────────────────────────────────── -->
        <fieldset class="expcfg-passo" id="expcfg-p3">
            <legend><span class="expcfg-num">3</span> <?php esc_html_e( 'Carica da tre a sei fotografie', 'experiences-srl' ); ?></legend>
            <p class="expcfg-aiuto"><?php esc_html_e( 'La prima diventa l’immagine grande in cima. Vanno bene anche scatti dal telefono: meglio orizzontali, luminosi, senza persone riconoscibili.', 'experiences-srl' ); ?></p>

            <label class="expcfg-dropzone" id="expcfg-dropzone" for="expcfg-file">
                <input type="file" id="expcfg-file" accept="image/jpeg,image/png,image/webp" multiple
                       class="expcfg-file-input">
                <span class="expcfg-dz-icona" aria-hidden="true"><i class="fas fa-eye"></i></span>
                <span class="expcfg-dz-tit"><?php esc_html_e( 'Trascina qui le fotografie', 'experiences-srl' ); ?></span>
                <span class="expcfg-dz-sub"><?php esc_html_e( 'oppure premi per sceglierle — JPG, PNG o WEBP, fino a 10 MB l’una', 'experiences-srl' ); ?></span>
            </label>

            <p class="expcfg-errore" id="expcfg-errore" role="alert" hidden></p>
            <ul class="expcfg-miniature" id="expcfg-miniature"></ul>
        </fieldset>

        <!-- ── Anteprima ───────────────────────────────────────────── -->
        <div class="expcfg-vuoto" id="expcfg-vuoto">
            <p><?php esc_html_e( 'Completa i tre passi qui sopra e l’anteprima comparirà qui.', 'experiences-srl' ); ?></p>
        </div>

        <section class="expcfg-risultato" id="expcfg-risultato" hidden aria-live="polite">
            <div class="expcfg-barra">
                <h2 class="expcfg-barra-tit"><?php esc_html_e( 'La tua anteprima', 'experiences-srl' ); ?></h2>
                <div class="expcfg-stili" role="radiogroup" aria-label="<?php echo esc_attr__( 'Stile dell’anteprima', 'experiences-srl' ); ?>">
                    <button type="button" class="expcfg-stile" data-stile="classico" role="radio" aria-checked="true"><?php esc_html_e( 'Classico', 'experiences-srl' ); ?></button>
                    <button type="button" class="expcfg-stile" data-stile="editoriale" role="radio" aria-checked="false"><?php esc_html_e( 'Editoriale', 'experiences-srl' ); ?></button>
                    <button type="button" class="expcfg-stile" data-stile="scuro" role="radio" aria-checked="false"><?php esc_html_e( 'Scuro', 'experiences-srl' ); ?></button>
                </div>
            </div>

            <div class="expcfg-finestra">
                <div class="expcfg-barratitolo" aria-hidden="true">
                    <span class="expcfg-pallino"></span><span class="expcfg-pallino"></span><span class="expcfg-pallino"></span>
                    <span class="expcfg-url" id="expcfg-url"><?php esc_html_e( 'www.latuastruttura.it', 'experiences-srl' ); ?></span>
                </div>
                <div class="expcfg-tela" id="expcfg-tela" data-stile="classico"></div>
            </div>

            <p class="expcfg-disclaimer"><?php esc_html_e( 'È un’anteprima costruita al volo con le tue fotografie, non un sito finito: serve a farti vedere l’impianto. Il sito vero ha prenotazione, mappa, lingue e il resto.', 'experiences-srl' ); ?></p>
        </section>

        <!-- ── Richiesta ───────────────────────────────────────────── -->
        <section class="expcfg-chiusura" id="expcfg-chiusura" hidden>
            <h2><?php esc_html_e( 'Ti piace? Partiamo da qui', 'experiences-srl' ); ?></h2>
            <p class="expcfg-chiusura-sub"><?php esc_html_e( 'Ti mandiamo questa impostazione sviluppata per davvero, con le tue fotografie e i tuoi testi. Nessun impegno.', 'experiences-srl' ); ?></p>

            <form class="expcfg-form" id="expcfg-form" novalidate>
                <div class="expcfg-form-griglia">
                    <p class="expcfg-campo">
                        <label for="expcfg-f-nome"><?php esc_html_e( 'Nome', 'experiences-srl' ); ?> <span aria-hidden="true">*</span></label>
                        <input type="text" id="expcfg-f-nome" name="nome" required autocomplete="given-name">
                    </p>
                    <p class="expcfg-campo">
                        <label for="expcfg-f-cognome"><?php esc_html_e( 'Cognome', 'experiences-srl' ); ?> <span aria-hidden="true">*</span></label>
                        <input type="text" id="expcfg-f-cognome" name="cognome" required autocomplete="family-name">
                    </p>
                </div>
                <p class="expcfg-campo">
                    <label for="expcfg-f-email"><?php esc_html_e( 'Email', 'experiences-srl' ); ?> <span aria-hidden="true">*</span></label>
                    <input type="email" id="expcfg-f-email" name="email" required autocomplete="email">
                </p>

                <!-- Trappola anti-bot: invisibile a chi legge, compilata dagli automatismi -->
                <p class="expcfg-trappola" aria-hidden="true">
                    <label for="expcfg-website"><?php esc_html_e( 'Non compilare questo campo', 'experiences-srl' ); ?></label>
                    <input type="text" id="expcfg-website" name="website" tabindex="-1" autocomplete="off">
                </p>

                <p class="expcfg-consenso">
                    <input type="checkbox" id="expcfg-privacy" required>
                    <label for="expcfg-privacy">
                        Acconsento al trattamento dei miei dati secondo la
                        <a href="<?php echo esc_url( get_privacy_policy_url() ?: home_url( '/privacy-policy/' ) ); ?>"
                           target="_blank" rel="noopener"><?php esc_html_e( 'Privacy Policy', 'experiences-srl' ); ?></a>.
                    </label>
                </p>

                <button type="submit" class="expcfg-invia" id="expcfg-invia">
                    <?php esc_html_e( 'Voglio questo sito', 'experiences-srl' ); ?> <i class="fas fa-paper-plane" aria-hidden="true"></i>
                </button>
                <p class="expcfg-esito" id="expcfg-esito" role="status" aria-live="polite" hidden></p>
            </form>
        </section>

    </div>
</section>

</main>

<?php get_footer(); ?>
