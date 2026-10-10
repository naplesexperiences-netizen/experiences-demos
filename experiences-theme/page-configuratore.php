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
            <p class="expcfg-occhiello">Anteprima gratuita</p>
            <h1 class="expcfg-titolo">Guarda come verrebbe <em>il tuo sito</em></h1>
            <p class="expcfg-sommario">
                Tre domande e qualche fotografia. In meno di un minuto vedi la tua
                struttura dentro un sito vero, in tre stili diversi.
            </p>
            <p class="expcfg-nota-privacy">
                <span aria-hidden="true">&#128274;</span>
                Le fotografie restano sul tuo dispositivo: l&rsquo;anteprima si costruisce qui nel browser
                e le immagini non vengono caricate da nessuna parte.
            </p>
        </header>

        <!-- ── Passo 1: tipo di attività ───────────────────────────── -->
        <fieldset class="expcfg-passo" id="expcfg-p1">
            <legend><span class="expcfg-num">1</span> Che attività hai?</legend>
            <div class="expcfg-scelte" role="radiogroup" aria-label="Tipo di attività">
                <button type="button" class="expcfg-scelta" data-tipo="hotel" role="radio" aria-checked="false">
                    <span class="expcfg-icona" aria-hidden="true"><i class="fas fa-hotel"></i></span>
                    <span class="expcfg-scelta-tit">Hotel o B&amp;B</span>
                    <span class="expcfg-scelta-sub">Camere, servizi, prenotazione</span>
                </button>
                <button type="button" class="expcfg-scelta" data-tipo="tour" role="radio" aria-checked="false">
                    <span class="expcfg-icona" aria-hidden="true"><i class="fas fa-map-marked-alt"></i></span>
                    <span class="expcfg-scelta-tit">Tour ed esperienze</span>
                    <span class="expcfg-scelta-sub">Escursioni, durata, partenze</span>
                </button>
                <button type="button" class="expcfg-scelta" data-tipo="altro" role="radio" aria-checked="false">
                    <span class="expcfg-icona" aria-hidden="true"><i class="fas fa-suitcase"></i></span>
                    <span class="expcfg-scelta-tit">Altra attività</span>
                    <span class="expcfg-scelta-sub">Ristorante, noleggio, servizi</span>
                </button>
            </div>
        </fieldset>

        <!-- ── Passo 2: nome e città ───────────────────────────────── -->
        <fieldset class="expcfg-passo" id="expcfg-p2">
            <legend><span class="expcfg-num">2</span> Come si chiama?</legend>
            <div class="expcfg-campi">
                <p class="expcfg-campo">
                    <label for="expcfg-nome">Nome della struttura</label>
                    <input type="text" id="expcfg-nome" maxlength="60" autocomplete="organization"
                           placeholder="Villa Serena">
                </p>
                <p class="expcfg-campo">
                    <label for="expcfg-citta">Città o località</label>
                    <input type="text" id="expcfg-citta" maxlength="60" autocomplete="address-level2"
                           placeholder="Sorrento">
                </p>
            </div>
        </fieldset>

        <!-- ── Passo 3: fotografie ─────────────────────────────────── -->
        <fieldset class="expcfg-passo" id="expcfg-p3">
            <legend><span class="expcfg-num">3</span> Carica da tre a sei fotografie</legend>
            <p class="expcfg-aiuto">
                La prima diventa l&rsquo;immagine grande in cima. Vanno bene anche scatti dal telefono:
                meglio orizzontali, luminosi, senza persone riconoscibili.
            </p>

            <label class="expcfg-dropzone" id="expcfg-dropzone" for="expcfg-file">
                <input type="file" id="expcfg-file" accept="image/jpeg,image/png,image/webp" multiple
                       class="expcfg-file-input">
                <span class="expcfg-dz-icona" aria-hidden="true"><i class="fas fa-eye"></i></span>
                <span class="expcfg-dz-tit">Trascina qui le fotografie</span>
                <span class="expcfg-dz-sub">oppure premi per sceglierle &mdash; JPG, PNG o WEBP, fino a 10&nbsp;MB l&rsquo;una</span>
            </label>

            <p class="expcfg-errore" id="expcfg-errore" role="alert" hidden></p>
            <ul class="expcfg-miniature" id="expcfg-miniature"></ul>
        </fieldset>

        <!-- ── Anteprima ───────────────────────────────────────────── -->
        <div class="expcfg-vuoto" id="expcfg-vuoto">
            <p>Completa i tre passi qui sopra e l&rsquo;anteprima comparirà qui.</p>
        </div>

        <section class="expcfg-risultato" id="expcfg-risultato" hidden aria-live="polite">
            <div class="expcfg-barra">
                <h2 class="expcfg-barra-tit">La tua anteprima</h2>
                <div class="expcfg-stili" role="radiogroup" aria-label="Stile dell&rsquo;anteprima">
                    <button type="button" class="expcfg-stile" data-stile="classico" role="radio" aria-checked="true">Classico</button>
                    <button type="button" class="expcfg-stile" data-stile="editoriale" role="radio" aria-checked="false">Editoriale</button>
                    <button type="button" class="expcfg-stile" data-stile="scuro" role="radio" aria-checked="false">Scuro</button>
                </div>
            </div>

            <div class="expcfg-finestra">
                <div class="expcfg-barratitolo" aria-hidden="true">
                    <span class="expcfg-pallino"></span><span class="expcfg-pallino"></span><span class="expcfg-pallino"></span>
                    <span class="expcfg-url" id="expcfg-url">www.latuastruttura.it</span>
                </div>
                <div class="expcfg-tela" id="expcfg-tela" data-stile="classico"></div>
            </div>

            <p class="expcfg-disclaimer">
                È un&rsquo;anteprima costruita al volo con le tue fotografie, non un sito finito:
                serve a farti vedere l&rsquo;impianto. Il sito vero ha prenotazione, mappa, lingue e il resto.
            </p>
        </section>

        <!-- ── Richiesta ───────────────────────────────────────────── -->
        <section class="expcfg-chiusura" id="expcfg-chiusura" hidden>
            <h2>Ti piace? Partiamo da qui</h2>
            <p class="expcfg-chiusura-sub">
                Ti mandiamo questa impostazione sviluppata per davvero, con le tue fotografie
                e i tuoi testi. Nessun impegno.
            </p>

            <form class="expcfg-form" id="expcfg-form" novalidate>
                <div class="expcfg-form-griglia">
                    <p class="expcfg-campo">
                        <label for="expcfg-f-nome">Nome <span aria-hidden="true">*</span></label>
                        <input type="text" id="expcfg-f-nome" name="nome" required autocomplete="given-name">
                    </p>
                    <p class="expcfg-campo">
                        <label for="expcfg-f-cognome">Cognome <span aria-hidden="true">*</span></label>
                        <input type="text" id="expcfg-f-cognome" name="cognome" required autocomplete="family-name">
                    </p>
                </div>
                <p class="expcfg-campo">
                    <label for="expcfg-f-email">Email <span aria-hidden="true">*</span></label>
                    <input type="email" id="expcfg-f-email" name="email" required autocomplete="email">
                </p>

                <!-- Trappola anti-bot: invisibile a chi legge, compilata dagli automatismi -->
                <p class="expcfg-trappola" aria-hidden="true">
                    <label for="expcfg-website">Non compilare questo campo</label>
                    <input type="text" id="expcfg-website" name="website" tabindex="-1" autocomplete="off">
                </p>

                <p class="expcfg-consenso">
                    <input type="checkbox" id="expcfg-privacy" required>
                    <label for="expcfg-privacy">
                        Acconsento al trattamento dei miei dati secondo la
                        <a href="<?php echo esc_url( get_privacy_policy_url() ?: home_url( '/privacy-policy/' ) ); ?>"
                           target="_blank" rel="noopener">Privacy Policy</a>.
                    </label>
                </p>

                <button type="submit" class="expcfg-invia" id="expcfg-invia">
                    Voglio questo sito <i class="fas fa-paper-plane" aria-hidden="true"></i>
                </button>
                <p class="expcfg-esito" id="expcfg-esito" role="status" aria-live="polite" hidden></p>
            </form>
        </section>

    </div>
</section>

</main>

<?php get_footer(); ?>
