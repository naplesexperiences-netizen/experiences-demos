<?php
/**
 * Plugin Name:  Experiences Chatbot
 * Plugin URI:   https://www.naplesexperiences.com
 * Description:  Chatbot testuale configurabile per Experiences Srl, con risposte AI opzionali.
 * Version:      2.1.0
 * Author:       Experiences Srl
 * License:      Proprietary
 * Text Domain:  exp-chatbot
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'EXP_CHATBOT_VERSION', '2.1.0' );

/* =========================================================
   MODELLI
   ========================================================= */
// I modelli attuali. Il default di prima, claude-sonnet-4-5, non esiste
// piu: una chiamata con quel nome tornava errore e il chatbot cadeva
// silenziosamente sui template.
function exp_chatbot_modelli_claude() {
    return [
        'claude-opus-5-5'  => 'Claude Opus 5.5 — il piu capace ($4 / $20 per milione di token)',
        'claude-sonnet-5-5'=> 'Claude Sonnet 5.5 — equilibrato ($2 / $10)',
        'claude-haiku-5-5' => 'Claude Haiku 5.5 — il piu economico ($0,10 / $0,50)',
    ];
}

/* =========================================================
   FORNITORI
   ========================================================= */
// Groq, DeepSeek, Mistral, OpenRouter e Gemini espongono tutti lo stesso
// protocollo di OpenAI (/chat/completions): cambia solo l'indirizzo. Un
// solo percorso di codice li copre, e aggiungerne un altro domani vuol
// dire aggiungere una riga a questa tabella.
//
// I modelli indicati sono un punto di partenza, non una garanzia: i
// listini e i nomi cambiano spesso. Se un modello non esiste piu la
// chiamata fallisce e il chatbot ricade sui template — il bottone
// "Testa connessione API" mostra l'errore vero.
function exp_chatbot_fornitori() {
    return [
        'none' => [
            'label' => 'Nessuno — solo template',
            'tipo'  => 'none',
        ],
        'claude' => [
            'label'   => 'Anthropic Claude',
            'tipo'    => 'anthropic',
            'modello' => 'claude-haiku-5-5',
            'chiavi'  => 'https://console.anthropic.com',
            'listino' => 'https://www.anthropic.com/pricing#api',
            'nota'    => 'Haiku 5.5 costa $0,10 per milione di token in ingresso e $0,50 in uscita: per un chatbot di sito e gia tra le opzioni piu economiche in assoluto.',
        ],
        'gemini' => [
            'label'   => 'Google Gemini',
            'tipo'    => 'openai',
            'url'     => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            // Alias e non una versione fissa: gemini-2.0-flash, messo qui
            // nella 2.1.0, era gia stato ritirato da Google e ogni
            // chiamata tornava 404. L'alias segue il modello corrente.
            'modello' => 'gemini-flash-lite-latest',
            'chiavi'  => 'https://aistudio.google.com/apikey',
            'listino' => 'https://ai.google.dev/pricing',
            'nota'    => 'Ha un piano gratuito con un tetto giornaliero di richieste: per un chatbot a basso traffico puo bastare quello. I nomi dei modelli Gemini cambiano spesso — gli alias che finiscono in "-latest" seguono il modello corrente e non vanno aggiornati a mano.',
        ],
        'groq' => [
            'label'   => 'Groq',
            'tipo'    => 'openai',
            'url'     => 'https://api.groq.com/openai/v1/chat/completions',
            'modello' => 'llama-3.3-70b-versatile',
            'chiavi'  => 'https://console.groq.com/keys',
            'listino' => 'https://groq.com/pricing/',
            'nota'    => 'Modelli aperti (Llama e simili) eseguiti su hardware dedicato: risposte molto rapide e costo basso.',
        ],
        'deepseek' => [
            'label'   => 'DeepSeek',
            'tipo'    => 'openai',
            'url'     => 'https://api.deepseek.com/chat/completions',
            'modello' => 'deepseek-chat',
            'chiavi'  => 'https://platform.deepseek.com/api_keys',
            'listino' => 'https://api-docs.deepseek.com/quick_start/pricing',
            'nota'    => 'Tra i piu economici in circolazione. I server sono in Cina: valutalo prima di farci passare dati dei clienti.',
        ],
        'mistral' => [
            'label'   => 'Mistral',
            'tipo'    => 'openai',
            'url'     => 'https://api.mistral.ai/v1/chat/completions',
            'modello' => 'mistral-small-latest',
            'chiavi'  => 'https://console.mistral.ai/api-keys',
            'listino' => 'https://mistral.ai/pricing',
            'nota'    => 'Azienda francese, server nell\'Unione europea: la scelta piu semplice da giustificare lato GDPR.',
        ],
        'openrouter' => [
            'label'   => 'OpenRouter',
            'tipo'    => 'openai',
            'url'     => 'https://openrouter.ai/api/v1/chat/completions',
            'modello' => 'meta-llama/llama-3.3-70b-instruct',
            'chiavi'  => 'https://openrouter.ai/keys',
            'listino' => 'https://openrouter.ai/models',
            'nota'    => 'Un solo account per centinaia di modelli di fornitori diversi, alcuni gratuiti. Comodo per provare senza aprire conti ovunque.',
        ],
        'openai' => [
            'label'   => 'OpenAI',
            'tipo'    => 'openai',
            'url'     => 'https://api.openai.com/v1/chat/completions',
            'modello' => 'gpt-4o-mini',
            'chiavi'  => 'https://platform.openai.com/api-keys',
            'listino' => 'https://openai.com/api/pricing/',
            'nota'    => '',
        ],
    ];
}

function exp_chatbot_fornitore( $chiave ) {
    $f = exp_chatbot_fornitori();
    return $f[ $chiave ] ?? $f['none'];
}

// Solo i modelli recenti accettano output_config.effort e i fallback
// lato server; mandarli a un modello piu vecchio restituisce 400.
function exp_chatbot_supporta_effort( $model ) {
    return in_array( $model, [ 'claude-opus-5-5', 'claude-opus-5', 'claude-sonnet-5-5', 'claude-haiku-5-5', 'claude-fable-5-1' ], true );
}

function exp_chatbot_supporta_fallback( $model ) {
    return in_array( $model, [ 'claude-opus-5-5', 'claude-opus-5', 'claude-sonnet-5-5', 'claude-fable-5-1' ], true );
}

/* =========================================================
   CHIAVE API
   ========================================================= */
// Una costante in wp-config.php ha la precedenza sul database: la chiave
// non finisce in un backup del database ne in un export, e non e
// leggibile da chi entra in bacheca.
function exp_chatbot_api_key( $s ) {
    if ( defined( 'EXP_CHATBOT_API_KEY' ) && EXP_CHATBOT_API_KEY ) {
        return EXP_CHATBOT_API_KEY;
    }
    return $s['llm_api_key'] ?? '';
}

/* =========================================================
   DEFAULTS
   ========================================================= */
function exp_chatbot_defaults() {
    return [
        'llm_provider'    => 'none',
        'llm_api_key'     => '',
        // Haiku 5.5 e il piu economico della famiglia Claude ($0,10 e
        // $0,50 per milione di token): per rispondere a domande su
        // servizi e prezzi basta, e il salto di spesa verso i modelli
        // grandi non si ripaga su questo tipo di conversazione.
        'llm_model'       => 'claude-haiku-5-5',
        // Con 'llm_first' l'AI risponde e i template restano come rete di
        // sicurezza. Con 'template_first' vince il primo template la cui
        // parola chiave compare nel messaggio — che e come si comportava
        // la 1.3.0: "quanto costa il piano base?" contiene "quanto costa",
        // quindi tornava il listino intero e l'AI non veniva mai chiamata.
        'llm_order'       => 'llm_first',
        'llm_max_turns'   => 6,
        'rate_limit'      => 30,
        'llm_system'      => 'Sei l\'assistente virtuale di Experiences Srl, esperto di digitalizzazione turismo. Rispondi sempre in italiano in modo professionale, persuasivo e conciso (max 3 frasi). 

Il tuo ruolo: identificare il tipo di business del visitatore (Hotel/B&B, Agenzia Viaggi, Tour Operator) e proporre la soluzione giusta.

Servizi: 
1) Channel Manager (sincronizza Booking/Airbnb/Expedia, elimina overbooking, +45% prenotazioni dirette)
2) Siti web moderni (mobile-first, booking integrato, +60% conversione)
3) SEO/SEM (top Google in 90gg, traffico +200%)
4) Annunci OTA ottimizzati (+35% conversione)
5) Chatbot AI 24/7 (multilingua, +40% prenotazioni notturne)

Piani: Base 1.400€/anno (10% comm.), Advanced 1.000€ (8%), Pro 500€ (5%), Enterprise 0€ (3% + AI inclusa). Più cresci, meno paghi.

Sempre: chiedi qual è il loro business, identifica il pain point principale (overbooking? bassa visibilità? gestione manuale?), proponi la soluzione adatta. Chiudi con call-to-action: "Vuoi una consulenza gratuita? WhatsApp +39 392 691 7657 o form sul sito."',
        'bot_name'        => 'Assistente Experiences',
        'bot_subtitle'    => 'Online — ti aiuto a trovare la soluzione perfetta',
        'whatsapp_nr'     => '+39 392 691 7657',
        'show_demo_badge' => '1',
        'templates'       => exp_chatbot_default_templates(),
        'quick_questions' => "Sono un Hotel/B&B\nSono un'Agenzia Viaggi\nSono un Tour Operator\nQuanto costa?\nVoglio una consulenza gratuita",
    ];
}

function exp_chatbot_default_templates() {
    return [
        [
            'id'       => 'saluto',
            'label'    => 'Saluto iniziale',
            'triggers' => '',
            'message'  => "{SALUTO}! Sono l'assistente AI di Experiences Srl. 👋\n\nAiuto hotel, B&B, agenzie viaggi e tour operator a digitalizzarsi e aumentare prenotazioni del 45%+ usando Channel Manager, siti moderni e AI.\n\nDimmi: che tipo di business hai? Hotel, agenzia o tour operator? Così ti suggerisco la soluzione giusta per te.",
        ],
        [
            'id'       => 'chatbot_info',
            'label'    => 'Come funziona un chatbot',
            'triggers' => 'chatbot,ai,assistente,virtuale,bot,intelligenza artificiale',
            'message'  => "Il nostro Chatbot AI lavora per te 24/7. 🤖\n\n✅ Risponde ai clienti in 12 lingue in 2 secondi\n✅ Prenotazioni dirette anche di notte\n✅ Conosce i tuoi servizi, prezzi, disponibilità\n✅ Integra con Channel Manager (sync prenotazioni)\n\nRisultato medio: +40% prenotazioni notturne, 0 clienti persi per mancata risposta.",
        ],
        [
            'id'       => 'servizi',
            'label'    => 'Servizi e piani tariffari',
            'triggers' => 'servizi,piani,quanto costa il piano,enterprise,pro,advanced,base,prezzi,abbonamento,costo,quanto costa',
            'message'  => "💼 I nostri 5 servizi (risolvono i tuoi problemi reali):\n\n1️⃣ Channel Manager → -30h/sett, +45% prenotazioni dirette\n2️⃣ Sito Web Moderno → +60% conversione mobile\n3️⃣ SEO/SEM → +200% traffico in 90gg\n4️⃣ Annunci OTA → +35% conversione\n5️⃣ Chatbot AI → +40% prenotazioni notturne\n\n💰 Piani (paghi solo quando cresci):\n• Base 1.400€/anno + 10%\n• Advanced 1.000€ + 8%\n• Pro 500€ + 5%\n• Enterprise GRATIS + 3% (AI inclusa)\n\nPiù vendi, meno paghi fisso. Vuoi una consulenza gratuita?",
        ],
        [
            'id'       => 'contatti',
            'label'    => 'Contatti',
            'triggers' => 'contatto,contattare,whatsapp,telefono,scrivere,form,email,consulenza',
            'message'  => "📞 Parliamo del tuo business!\n\n✅ WhatsApp: {WHATSAPP} (risposta in 24h)\n✅ Form contatti: sezione Contatti del sito\n✅ Email: segreteria@naplesexperiences.com\n\n🎁 Consulenza gratuita: analizziamo il tuo sito e ti diciamo dove perdi prenotazioni. No vincoli, no spam.",
        ],
        [
            'id'       => 'hotel',
            'label'    => 'Soluzione Hotel/B&B',
            'triggers' => 'hotel,bb,b&b,bed and breakfast,struttura,resort,albergo,camera,booking,airbnb,overbooking',
            'message'  => "🏨 Per Hotel e B&B il problema #1 è l'overbooking + gestione manuale di 5 piattaforme.\n\nLa nostra soluzione:\n✅ Channel Manager: sync real-time Booking/Airbnb/Expedia\n✅ Zero overbooking garantito\n✅ +45% prenotazioni dirette (no commissioni OTA)\n✅ -30h/sett risparmiate\n\nCase study: Hotel 4 stelle Ischia ha aggiunto €50.000/anno in 6 mesi. Vuoi parlare con loro?",
        ],
        [
            'id'       => 'agenzia',
            'label'    => 'Soluzione Agenzia Viaggi',
            'triggers' => 'agenzia,viaggi,tour,guida,esperienze,visita,getyourguide,viator,toursbylocals',
            'message'  => "🧳 Per Agenzie Viaggi il problema #1 è essere invisibili online mentre GetYourGuide/Viator catturano i tuoi clienti.\n\nLa nostra soluzione:\n✅ Sito moderno SEO-optimized in 3 lingue\n✅ Integrazione con piattaforme globali\n✅ Pagamenti automatici online\n✅ +300% prenotazioni (case study reale)\n\nAgenzia tour Napoli è passata da 5 a 20 prenotazioni/mese in 90gg. Vuoi sapere come?",
        ],
        [
            'id'       => 'tour_operator',
            'label'    => 'Soluzione Tour Operator',
            'triggers' => 'tour operator,operatore turistico,grande agenzia,inventory,multi tour,scalare,enterprise',
            'message'  => "📊 Per Tour Operator il problema #1 è gestire centinaia di tour su 10+ piattaforme = caos.\n\nLa nostra soluzione enterprise:\n✅ 1 dashboard per tutti i tour e canali\n✅ Pricing dinamico automatico\n✅ Analytics avanzate\n✅ +40% revenue, -60% costi admin\n\nCase study: Tour Operator Campania ha aggiunto €300k/anno mantenendo lo stesso team. Vuoi una demo personalizzata?",
        ],
    ];
}

/* =========================================================
   ACTIVATION
   ========================================================= */
register_activation_hook( __FILE__, function() {
    if ( ! get_option( 'exp_chatbot_settings' ) ) {
        update_option( 'exp_chatbot_settings', exp_chatbot_defaults() );
    }
});

/* =========================================================
   ADMIN MENU
   ========================================================= */
add_action( 'admin_menu', function() {
    add_menu_page( 'Experiences Chatbot', 'Chatbot AI', 'manage_options', 'exp-chatbot', 'exp_chatbot_page_main', 'dashicons-format-chat', 30 );
    add_submenu_page( 'exp-chatbot', 'Impostazioni',     'Impostazioni',     'manage_options', 'exp-chatbot',           'exp_chatbot_page_main' );
    add_submenu_page( 'exp-chatbot', 'Template Risposte','Template Risposte','manage_options', 'exp-chatbot-templates', 'exp_chatbot_page_templates' );
    add_submenu_page( 'exp-chatbot', 'API & LLM',        'API & LLM',        'manage_options', 'exp-chatbot-llm',       'exp_chatbot_page_llm' );
});

/* =========================================================
   SAVE HANDLER
   ========================================================= */
add_action( 'admin_init', function() {
    if ( ! isset( $_POST['exp_chatbot_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['exp_chatbot_nonce'] ) ), 'exp_chatbot_save' ) ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;

    $s = get_option( 'exp_chatbot_settings', exp_chatbot_defaults() );

    if ( isset( $_POST['bot_name'] ) ) {
        $s['bot_name']        = sanitize_text_field( $_POST['bot_name'] );
        $s['bot_subtitle']    = sanitize_text_field( $_POST['bot_subtitle'] );
        $s['whatsapp_nr']     = sanitize_text_field( $_POST['whatsapp_nr'] );
        $s['show_demo_badge'] = isset( $_POST['show_demo_badge'] ) ? '1' : '0';
        $s['quick_questions'] = sanitize_textarea_field( $_POST['quick_questions'] );
    }

    if ( isset( $_POST['llm_provider'] ) ) {
        $s['llm_provider'] = sanitize_text_field( $_POST['llm_provider'] );
        $s['llm_model']    = sanitize_text_field( $_POST['llm_model'] );
        $s['llm_system']   = sanitize_textarea_field( $_POST['llm_system'] );
        $s['llm_order']    = ( ( $_POST['llm_order'] ?? '' ) === 'template_first' ) ? 'template_first' : 'llm_first';
        $s['llm_max_turns']= max( 0, min( 20, (int) ( $_POST['llm_max_turns'] ?? 6 ) ) );
        $s['rate_limit']   = max( 0, min( 500, (int) ( $_POST['rate_limit'] ?? 30 ) ) );
        // Aggiorna la chiave solo se l'utente ne ha inserita una nuova
        $new_key = trim( sanitize_text_field( $_POST['llm_api_key'] ?? '' ) );
        if ( $new_key !== '' ) {
            $s['llm_api_key'] = $new_key;
        }
    }

    if ( isset( $_POST['tpl_id'] ) && is_array( $_POST['tpl_id'] ) ) {
        $templates = [];
        foreach ( $_POST['tpl_id'] as $i => $tid ) {
            if ( empty( trim( $tid ) ) ) continue;
            $templates[] = [
                'id'       => sanitize_key( $tid ),
                'label'    => sanitize_text_field( $_POST['tpl_label'][$i] ?? '' ),
                'triggers' => sanitize_text_field( $_POST['tpl_triggers'][$i] ?? '' ),
                'message'  => sanitize_textarea_field( $_POST['tpl_message'][$i] ?? '' ),
            ];
        }
        $s['templates'] = $templates;
    }

    update_option( 'exp_chatbot_settings', $s );
    add_settings_error( 'exp_chatbot', 'saved', 'Impostazioni salvate!', 'updated' );
});

/* =========================================================
   ADMIN PAGES
   ========================================================= */
function exp_chatbot_page_main() {
    $s = get_option( 'exp_chatbot_settings', exp_chatbot_defaults() );
    settings_errors( 'exp_chatbot' );
    ?>
    <div class="wrap">
    <h1><span class="dashicons dashicons-format-chat" style="font-size:28px;margin-right:8px;color:#0D7C7C;"></span>Experiences Chatbot — Impostazioni Generali</h1>
    <p style="color:#666;">Shortcode: <code>[exp_chatbot]</code></p>
    <form method="post">
    <?php wp_nonce_field('exp_chatbot_save','exp_chatbot_nonce'); ?>
    <table class="form-table" style="max-width:700px;">
        <tr><th><label for="bot_name">Nome Chatbot</label></th>
            <td><input type="text" id="bot_name" name="bot_name" value="<?php echo esc_attr($s['bot_name']); ?>" class="regular-text"></td></tr>
        <tr><th><label for="bot_subtitle">Sottotitolo</label></th>
            <td><input type="text" id="bot_subtitle" name="bot_subtitle" value="<?php echo esc_attr($s['bot_subtitle']); ?>" class="regular-text"></td></tr>
        <tr><th><label for="whatsapp_nr">WhatsApp</label></th>
            <td><input type="text" id="whatsapp_nr" name="whatsapp_nr" value="<?php echo esc_attr($s['whatsapp_nr']); ?>" class="regular-text">
            <p class="description">Segnaposto: <code>{WHATSAPP}</code></p></td></tr>
        <tr><th>Badge DEMO</th>
            <td><label><input type="checkbox" name="show_demo_badge" value="1" <?php checked($s['show_demo_badge'],'1'); ?>> Mostra badge "DEMO"</label></td></tr>
        <tr><th><label for="quick_questions">Domande rapide</label></th>
            <td><textarea id="quick_questions" name="quick_questions" rows="5" class="large-text"><?php echo esc_textarea($s['quick_questions']); ?></textarea>
            <p class="description">Una per riga — appaiono come bottoni cliccabili.</p></td></tr>
    </table>
    <?php submit_button('Salva Impostazioni'); ?>
    </form></div>
    <?php
}

function exp_chatbot_page_templates() {
    $s = get_option( 'exp_chatbot_settings', exp_chatbot_defaults() );
    $templates = $s['templates'] ?? exp_chatbot_default_templates();
    settings_errors( 'exp_chatbot' );
    ?>
    <div class="wrap">
    <h1>Template Risposte</h1>
    <div style="background:#fff3cd;border-left:4px solid #ffc107;padding:12px 16px;margin-bottom:20px;">
        <strong>Segnaposti:</strong> <code>{SALUTO}</code> <code>{WHATSAPP}</code> <code>{BOT_NAME}</code><br>
        <strong>Triggers:</strong> parole chiave separate da virgola. Lascia vuoto per messaggi di apertura automatici.
    </div>
    <form method="post">
    <?php wp_nonce_field('exp_chatbot_save','exp_chatbot_nonce'); ?>
    <div id="exp-tpl-list">
    <?php foreach ($templates as $i => $tpl): ?>
    <div class="exp-tpl-card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <strong><?php echo esc_html($tpl['label'] ?: 'Template '.($i+1)); ?></strong>
            <button type="button" onclick="this.closest('.exp-tpl-card').remove()" style="background:#dc3545;color:#fff;border:none;border-radius:4px;padding:4px 10px;cursor:pointer;">Elimina</button>
        </div>
        <input type="hidden" name="tpl_id[]" value="<?php echo esc_attr($tpl['id']); ?>">
        <table style="width:100%;">
            <tr><td style="width:130px;padding:4px 0;font-weight:500;">Etichetta</td>
                <td><input type="text" name="tpl_label[]" value="<?php echo esc_attr($tpl['label']); ?>" class="regular-text"></td></tr>
            <tr><td style="padding:4px 0;font-weight:500;">Triggers</td>
                <td><input type="text" name="tpl_triggers[]" value="<?php echo esc_attr($tpl['triggers']); ?>" class="large-text" placeholder="es: chatbot,come funziona"></td></tr>
            <tr><td style="padding:4px 0;vertical-align:top;font-weight:500;">Messaggio</td>
                <td><textarea name="tpl_message[]" rows="5" class="large-text"><?php echo esc_textarea($tpl['message']); ?></textarea></td></tr>
        </table>
    </div>
    <?php endforeach; ?>
    </div>
    <button type="button" id="exp-add-tpl" style="background:#0D7C7C;color:#fff;border:none;border-radius:6px;padding:10px 20px;cursor:pointer;margin-bottom:20px;">+ Aggiungi template</button>
    <?php submit_button('Salva Template'); ?>
    </form></div>
    <script>
    document.getElementById('exp-add-tpl').addEventListener('click',function(){
        var uid='tpl_'+Date.now();
        var h='<div class="exp-tpl-card" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;margin-bottom:16px;">'
            +'<div style="display:flex;justify-content:space-between;margin-bottom:12px;"><strong>Nuovo Template</strong>'
            +'<button type="button" onclick="this.closest(\'.exp-tpl-card\').remove()" style="background:#dc3545;color:#fff;border:none;border-radius:4px;padding:4px 10px;cursor:pointer;">Elimina</button></div>'
            +'<input type="hidden" name="tpl_id[]" value="'+uid+'">'
            +'<table style="width:100%;">'
            +'<tr><td style="width:130px;padding:4px 0;font-weight:500;">Etichetta</td><td><input type="text" name="tpl_label[]" value="" class="regular-text"></td></tr>'
            +'<tr><td style="padding:4px 0;font-weight:500;">Triggers</td><td><input type="text" name="tpl_triggers[]" value="" class="large-text"></td></tr>'
            +'<tr><td style="padding:4px 0;vertical-align:top;font-weight:500;">Messaggio</td><td><textarea name="tpl_message[]" rows="5" class="large-text"></textarea></td></tr>'
            +'</table></div>';
        document.getElementById('exp-tpl-list').insertAdjacentHTML('beforeend',h);
    });
    </script>
    <?php
}

function exp_chatbot_page_llm() {
    $s = get_option( 'exp_chatbot_settings', exp_chatbot_defaults() );
    settings_errors( 'exp_chatbot' );
    ?>
    <div class="wrap">
    <h1>API & LLM — Intelligenza Artificiale</h1>
    <div style="background:#e8f8e8;border-left:4px solid #28a745;padding:12px 16px;margin-bottom:16px;">
        <strong>Architettura sicura:</strong> La chiave API resta nel database WordPress (server). Non viene mai inviata al browser.
    </div>
    <form method="post">
    <?php wp_nonce_field('exp_chatbot_save','exp_chatbot_nonce'); ?>
    <table class="form-table" style="max-width:700px;">
        <tr><th><label for="llm_provider">Fornitore AI</label></th>
            <td><select id="llm_provider" name="llm_provider" onchange="expCambiaFornitore(this.value)">
                <?php foreach ( exp_chatbot_fornitori() as $id => $f ): ?>
                    <option value="<?php echo esc_attr($id); ?>" <?php selected($s['llm_provider'],$id); ?>><?php echo esc_html($f['label']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php foreach ( exp_chatbot_fornitori() as $id => $f ):
                if ( 'none' === $id || empty($f['nota']) ) continue; ?>
                <p class="description exp-nota" data-forn="<?php echo esc_attr($id); ?>"
                   style="<?php echo $s['llm_provider']===$id ? '' : 'display:none'; ?>">
                    <?php echo esc_html($f['nota']); ?>
                </p>
            <?php endforeach; ?>
            <p class="description" style="margin-top:8px;">
                I fornitori dopo Claude parlano tutti lo stesso protocollo: cambiare richiede solo
                una chiave nuova e il nome di un modello. Prova e cambia quando vuoi — le tue
                impostazioni e i template restano.
            </p>
            </td></tr>
        <tr id="row_apikey" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th><label for="llm_api_key">API Key</label></th>
            <td><input type="password" id="llm_api_key" name="llm_api_key" value=""
                       placeholder="<?php echo !empty($s['llm_api_key']) ? '●●●●●●●● (salvata — lascia vuoto per mantenerla)' : 'Inserisci API key'; ?>"
                       class="large-text" autocomplete="new-password">
                <p class="description">
                    <?php foreach ( exp_chatbot_fornitori() as $id => $f ):
                        if ( 'none' === $id ) continue; ?>
                        <span class="exp-chiavi" data-forn="<?php echo esc_attr($id); ?>"
                              style="<?php echo $s['llm_provider']===$id ? '' : 'display:none'; ?>">
                            Chiave per <strong><?php echo esc_html($f['label']); ?></strong>:
                            <a href="<?php echo esc_url($f['chiavi']); ?>" target="_blank" rel="noopener"><?php echo esc_html( wp_parse_url($f['chiavi'], PHP_URL_HOST) ); ?></a>
                            <?php if ( ! empty($f['listino']) ): ?>
                                — <a href="<?php echo esc_url($f['listino']); ?>" target="_blank" rel="noopener">prezzi e modelli</a>
                            <?php endif; ?>
                        </span>
                    <?php endforeach; ?>
                    <?php if ( defined('EXP_CHATBOT_API_KEY') && EXP_CHATBOT_API_KEY ): ?>
                    <br><span style="color:#28a745;font-weight:600;">La chiave arriva da wp-config.php e ha la precedenza su questo campo.</span>
                    <?php elseif(!empty($s['llm_api_key'])): ?>
                    <br><span style="color:#28a745;font-weight:600;">Chiave attualmente salvata nel database.</span>
                    <br>Piu sicuro: toglila da qui e mettila in <code>wp-config.php</code> come
                    <code>define( 'EXP_CHATBOT_API_KEY', 'sk-ant-...' );</code> — cosi non finisce nei backup del database.
                    <?php endif; ?>
                </p></td></tr>
        <tr id="row_model" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th><label for="llm_model">Modello</label></th>
            <td><input type="text" id="llm_model" name="llm_model" value="<?php echo esc_attr($s['llm_model']); ?>" class="regular-text">
                <p class="description">
                    <span class="exp-modelli" data-forn="claude" style="<?php echo $s['llm_provider']==='claude' ? '' : 'display:none'; ?>">
                        <?php foreach ( exp_chatbot_modelli_claude() as $id => $desc ): ?>
                            <code><?php echo esc_html($id); ?></code> — <?php echo esc_html( substr($desc, strpos($desc,'—')+4) ); ?><br>
                        <?php endforeach; ?>
                    </span>
                    <em>Il nome del modello va copiato dal listino del fornitore (link qui sopra):
                    cambiano spesso, e se ne scrivi uno che non esiste piu il chatbot smette di
                    usare l&#39;AI e torna ai template senza dirtelo. Il bottone qui sotto mostra l&#39;errore vero.</em>
                </p></td></tr>
        <tr id="row_order" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th>Chi risponde</th>
            <td>
                <label><input type="radio" name="llm_order" value="llm_first" <?php checked($s['llm_order']??'llm_first','llm_first'); ?>>
                    <strong>Prima l'AI</strong> — i template diventano la rete di sicurezza se l'AI non risponde</label><br>
                <label><input type="radio" name="llm_order" value="template_first" <?php checked($s['llm_order']??'llm_first','template_first'); ?>>
                    <strong>Prima i template</strong> — l'AI interviene solo se nessuna parola chiave corrisponde</label>
                <p class="description">
                    Con "prima i template" una domanda come <em>"quanto costa il piano base?"</em> contiene
                    "quanto costa" e riceve il listino completo invece di una risposta: l'AI non viene mai chiamata.
                    I template restano comunque utili — vengono passati all'AI come base di conoscenza.
                </p></td></tr>
        <tr id="row_turns" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th><label for="llm_max_turns">Memoria conversazione</label></th>
            <td><input type="number" id="llm_max_turns" name="llm_max_turns" min="0" max="20" value="<?php echo (int) ($s['llm_max_turns']??6); ?>" class="small-text"> messaggi
                <p class="description">Quanti messaggi precedenti l'AI rilegge. A 0 ogni domanda riparte da zero e il chatbot non ricorda cosa gli hai appena detto.</p></td></tr>
        <tr id="row_rate" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th><label for="rate_limit">Limite per visitatore</label></th>
            <td><input type="number" id="rate_limit" name="rate_limit" min="0" max="500" value="<?php echo (int) ($s['rate_limit']??30); ?>" class="small-text"> messaggi ogni 10 minuti
                <p class="description"><strong>Protegge la tua carta.</strong> Il chatbot e aperto a chiunque passi dal sito: senza un tetto, qualcuno puo mandare migliaia di messaggi e spendere i tuoi crediti API. 0 disattiva il limite.</p></td></tr>
        <tr id="row_system" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th><label for="llm_system">System Prompt</label></th>
            <td><textarea id="llm_system" name="llm_system" rows="8" class="large-text"><?php echo esc_textarea($s['llm_system']); ?></textarea>
                <p class="description">I messaggi dei template vengono aggiunti automaticamente qui sotto come base di conoscenza, cosi l'AI risponde con i tuoi prezzi e i tuoi case study invece di inventarli.</p></td></tr>
    </table>
    <?php if ( $s['llm_provider'] !== 'none' && exp_chatbot_api_key( $s ) ): ?>
    <div style="margin:10px 0 20px;">
        <button type="button" id="exp-test-llm" style="background:#0D7C7C;color:#fff;border:none;border-radius:6px;padding:10px 20px;cursor:pointer;">Testa connessione API</button>
        <span id="exp-test-result" style="margin-left:12px;font-weight:500;"></span>
    </div>
    <script>
    document.getElementById('exp-test-llm').addEventListener('click',function(){
        var btn=this,res=document.getElementById('exp-test-result');
        btn.disabled=true;btn.textContent='Test in corso...';res.textContent='';
        fetch(ajaxurl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:'action=exp_chatbot_test_llm&nonce=<?php echo wp_create_nonce("exp_chatbot_test"); ?>'})
        .then(r=>r.json()).then(d=>{
            res.textContent=d.success?'✅ '+d.data.message:'❌ '+(d.data.message||'Errore');
            res.style.color=d.success?'#0D7C7C':'#dc3545';
        }).catch(()=>{res.textContent='❌ Errore di rete';res.style.color='#dc3545';})
        .finally(()=>{btn.disabled=false;btn.textContent='Testa connessione API';});
    });
    </script>
    <?php endif; ?>
    <?php submit_button('Salva Impostazioni API'); ?>
    </form></div>
    <script>
    var EXP_MODELLI_DEFAULT = <?php
        $def = [];
        foreach ( exp_chatbot_fornitori() as $id => $f ) {
            if ( ! empty( $f['modello'] ) ) $def[ $id ] = $f['modello'];
        }
        echo wp_json_encode( $def );
    ?>;
    function expCambiaFornitore(v){
        var mostra = v !== 'none';
        ['row_apikey','row_model','row_order','row_turns','row_rate','row_system'].forEach(function(id){
            var e = document.getElementById(id); if (e) e.style.display = mostra ? '' : 'none';
        });
        // Mostra solo le note, i link chiave e l'elenco modelli del fornitore scelto
        ['exp-nota','exp-chiavi','exp-modelli'].forEach(function(cls){
            document.querySelectorAll('.' + cls).forEach(function(el){
                el.style.display = (el.dataset.forn === v) ? '' : 'none';
            });
        });
        // Propone il modello predefinito del fornitore, senza cancellare
        // quello che l'utente ha scritto a mano se gia appartiene a lui.
        var campo = document.getElementById('llm_model');
        if (campo && EXP_MODELLI_DEFAULT[v]) {
            var valori = Object.keys(EXP_MODELLI_DEFAULT).map(function(k){ return EXP_MODELLI_DEFAULT[k]; });
            if (!campo.value || valori.indexOf(campo.value) !== -1) campo.value = EXP_MODELLI_DEFAULT[v];
        }
    }
    </script>
    <?php
}

/* =========================================================
   AJAX — TEST LLM (admin)
   ========================================================= */
add_action( 'wp_ajax_exp_chatbot_test_llm', function() {
    check_ajax_referer( 'exp_chatbot_test', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error(['message'=>'Non autorizzato']);
    $s = get_option( 'exp_chatbot_settings', exp_chatbot_defaults() );
    $r = exp_chatbot_call_llm( 'Rispondi solo: OK, sono operativo!', $s );
    if ( $r['ok'] ) wp_send_json_success(['message'=>'Connessione riuscita! Risposta: '.substr($r['text'],0,100)]);
    else            wp_send_json_error(['message'=>$r['error']]);
});

/* =========================================================
   AJAX — CHAT (frontend)
   ========================================================= */
add_action( 'wp_ajax_exp_chatbot_message',        'exp_chatbot_ajax_message' );
add_action( 'wp_ajax_nopriv_exp_chatbot_message', 'exp_chatbot_ajax_message' );
function exp_chatbot_ajax_message() {
    // Verifica non fatale: il nonce e dentro l'HTML, e l'HTML e servito
    // dalla cache con s-maxage lunghissimo mentre i nonce scadono in 24
    // ore. Quando la pagina in cache invecchia il token e morto. Invece
    // di morire con -1 si risponde con un codice che il JS riconosce,
    // cosi puo prendere un token fresco e ritentare una volta sola.
    if ( ! check_ajax_referer( 'exp_chatbot_front', 'nonce', false ) ) {
        wp_send_json_error( [ 'code' => 'nonce', 'reply' => 'Sessione scaduta.' ], 403 );
    }

    $msg = sanitize_text_field( wp_unslash( $_POST['message'] ?? '' ) );
    if ( ! $msg ) wp_send_json_error(['reply'=>'Messaggio vuoto.']);

    $s         = get_option( 'exp_chatbot_settings', exp_chatbot_defaults() );
    $templates = $s['templates'] ?? exp_chatbot_default_templates();
    $key       = exp_chatbot_api_key( $s );
    $has_llm   = ( ($s['llm_provider'] ?? 'none') !== 'none' ) && ! empty( $key );
    $order     = $s['llm_order'] ?? 'llm_first';

    // Cronologia inviata dal browser: il server non tiene sessioni, e
    // tenerle romperebbe la cache. Il tetto e lato server, cosi una
    // pagina manomessa non puo gonfiare la richiesta all'API.
    $storia = [];
    if ( ! empty( $_POST['history'] ) ) {
        $raw = json_decode( sanitize_textarea_field( wp_unslash( $_POST['history'] ) ), true );
        if ( is_array( $raw ) ) {
            $max = max( 0, (int) ( $s['llm_max_turns'] ?? 6 ) );
            foreach ( array_slice( $raw, -$max ) as $riga ) {
                $ruolo = ( ( $riga['role'] ?? '' ) === 'assistant' ) ? 'assistant' : 'user';
                $testo = sanitize_textarea_field( (string) ( $riga['text'] ?? '' ) );
                if ( '' !== trim( $testo ) ) {
                    $storia[] = [ 'role' => $ruolo, 'content' => mb_substr( $testo, 0, 2000 ) ];
                }
            }
        }
    }

    $trova_template = function() use ( $templates, $msg, $s ) {
        $low = strtolower( $msg );
        foreach ( $templates as $tpl ) {
            if ( empty( trim( $tpl['triggers'] ) ) ) continue;
            foreach ( array_map( 'trim', explode( ',', strtolower( $tpl['triggers'] ) ) ) as $k ) {
                if ( $k && strpos( $low, $k ) !== false ) {
                    return exp_chatbot_placeholders( $tpl['message'], $s );
                }
            }
        }
        return null;
    };

    if ( $has_llm && 'llm_first' === $order ) {
        if ( ! exp_chatbot_entro_il_limite( $s ) ) {
            wp_send_json_success([ 'reply' => "Hai scritto parecchi messaggi di fila — riprova fra qualche minuto.\n\nSe hai fretta: WhatsApp " . ( $s['whatsapp_nr'] ?? '' ), 'source' => 'rate_limit' ]);
        }
        $r = exp_chatbot_call_llm( $msg, $s, $storia, $templates );
        if ( $r['ok'] ) wp_send_json_success([ 'reply' => $r['text'], 'source' => 'llm' ]);
        // L'AI non ha risposto (chiave scaduta, rete, rifiuto): i
        // template evitano che il visitatore resti senza niente.
        $t = $trova_template();
        if ( null !== $t ) wp_send_json_success([ 'reply' => $t, 'source' => 'template' ]);
    } else {
        $t = $trova_template();
        if ( null !== $t ) wp_send_json_success([ 'reply' => $t, 'source' => 'template' ]);

        if ( $has_llm ) {
            if ( ! exp_chatbot_entro_il_limite( $s ) ) {
                wp_send_json_success([ 'reply' => "Hai scritto parecchi messaggi di fila — riprova fra qualche minuto.", 'source' => 'rate_limit' ]);
            }
            $r = exp_chatbot_call_llm( $msg, $s, $storia, $templates );
            if ( $r['ok'] ) wp_send_json_success([ 'reply' => $r['text'], 'source' => 'llm' ]);
        }
    }

    $def = "Grazie per la domanda!\n\nPer info specifiche contatta Experiences Srl:\n- Form: sezione Contatti\n- WhatsApp: ".($s['whatsapp_nr']??'+39 392 691 7657')."\n\nRispondiamo entro poche ore!";
    wp_send_json_success(['reply'=>$def,'source'=>'default']);
}

/* =========================================================
   NONCE FRESCO — endpoint mai messo in cache
   ========================================================= */
add_action( 'rest_api_init', function() {
    register_rest_route( 'exp-chatbot/v1', '/nonce', [
        'methods'             => 'GET',
        'permission_callback' => '__return_true',
        'callback'            => function() {
            $r = new WP_REST_Response( [ 'chatbot' => wp_create_nonce( 'exp_chatbot_front' ) ] );
            $r->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
            return $r;
        },
    ]);
});

// Il tema Experiences espone lo stesso servizio per il form contatti:
// se c'e, il token del chatbot viaggia insieme al suo.
add_filter( 'experiences_rest_nonces', function( $nonces ) {
    $nonces['chatbot'] = wp_create_nonce( 'exp_chatbot_front' );
    return $nonces;
});

/* =========================================================
   LIMITE PER VISITATORE
   ========================================================= */
// Il chatbot e aperto a chiunque e ogni messaggio costa. Senza un tetto
// bastano un paio di righe di script per bruciare i crediti API.
function exp_chatbot_entro_il_limite( $s ) {
    $max = (int) ( $s['rate_limit'] ?? 30 );
    if ( $max <= 0 ) return true;

    $ip    = $_SERVER['REMOTE_ADDR'] ?? '';
    $chiave = 'exp_cb_rl_' . md5( $ip . '|' . wp_salt() );
    $n     = (int) get_transient( $chiave );
    if ( $n >= $max ) return false;

    set_transient( $chiave, $n + 1, 10 * MINUTE_IN_SECONDS );
    return true;
}

/* =========================================================
   LLM CALL — server-side, chiave mai esposta al browser
   ========================================================= */
function exp_chatbot_call_llm( $msg, $s, $storia = [], $templates = [] ) {
    $provider = $s['llm_provider'] ?? 'none';
    $key      = exp_chatbot_api_key( $s );
    $model    = $s['llm_model']    ?? 'claude-opus-5-5';
    $system   = $s['llm_system']   ?? '';
    if ( empty($key) ) return ['ok'=>false,'error'=>'API key mancante'];

    // I template curati in bacheca diventano la base di conoscenza: cosi
    // l'AI cita i prezzi e i case study veri invece di inventarli.
    $system = exp_chatbot_system_con_conoscenza( $system, $templates, $s );

    // La cronologia va prima del messaggio nuovo e deve cominciare con
    // un turno utente, altrimenti l'API la rifiuta.
    $messaggi = $storia;
    while ( ! empty( $messaggi ) && 'assistant' === $messaggi[0]['role'] ) {
        array_shift( $messaggi );
    }
    $messaggi[] = [ 'role' => 'user', 'content' => $msg ];

    if ( $provider === 'claude' ) {
        // max_tokens generoso: sui modelli attuali il ragionamento e
        // sempre attivo e consuma da questo tetto. Con 500, come nella
        // 1.3.0, la risposta veniva troncata a meta.
        $body = [ 'model' => $model, 'max_tokens' => 4000, 'messages' => $messaggi ];
        if ( $system ) $body['system'] = $system;

        // Un chatbot di sito deve rispondere corto e subito: effort
        // basso riduce il ragionamento e quindi anche il conto.
        if ( exp_chatbot_supporta_effort( $model ) ) {
            $body['output_config'] = [ 'effort' => 'low' ];
        }

        $headers = [
            'x-api-key'         => $key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ];

        // Se un classificatore di sicurezza declina la richiesta, il
        // server la rigira su un altro modello invece di lasciare il
        // visitatore senza risposta.
        if ( exp_chatbot_supporta_fallback( $model ) ) {
            $body['fallbacks']        = 'default';
            $headers['anthropic-beta'] = 'server-side-fallback-2026-07-01';
        }

        $res = wp_remote_post( 'https://api.anthropic.com/v1/messages', [
            'timeout' => 30,
            'headers' => $headers,
            'body'    => wp_json_encode( $body ),
        ]);
        if ( is_wp_error($res) ) return ['ok'=>false,'error'=>$res->get_error_message()];

        $code = wp_remote_retrieve_response_code($res);
        $data = json_decode(wp_remote_retrieve_body($res),true);

        if ( ( $data['stop_reason'] ?? '' ) === 'refusal' ) {
            return [ 'ok' => false, 'error' => 'Richiesta declinata dal modello' ];
        }

        // Il contenuto non e piu un solo blocco di testo: sui modelli
        // attuali content[0] e un blocco "thinking" e leggere
        // content[0]['text'] non trova niente. Nella 1.3.0 questo da
        // solo bastava a far cadere ogni risposta sui template.
        $testo = '';
        foreach ( (array) ( $data['content'] ?? [] ) as $blocco ) {
            if ( ( $blocco['type'] ?? '' ) === 'text' && isset( $blocco['text'] ) ) {
                $testo .= $blocco['text'];
            }
        }
        if ( '' !== trim( $testo ) ) return [ 'ok' => true, 'text' => trim( $testo ) ];

        return ['ok'=>false,'error'=>($data['error']['message']??'Errore HTTP '.$code)];
    }

    // Tutti gli altri fornitori parlano il protocollo di OpenAI: cambia
    // solo l'indirizzo, preso dalla tabella dei fornitori.
    $f = exp_chatbot_fornitore( $provider );
    if ( ( $f['tipo'] ?? '' ) === 'openai' && ! empty( $f['url'] ) ) {
        $msgs = [];
        if ($system) $msgs[] = ['role'=>'system','content'=>$system];
        foreach ( $messaggi as $m ) $msgs[] = $m;

        $headers = [
            'Authorization' => 'Bearer ' . $key,
            'Content-Type'  => 'application/json',
        ];
        // OpenRouter attribuisce il traffico a questi due header e li
        // mostra nella sua dashboard; gli altri fornitori li ignorano.
        if ( 'openrouter' === $provider ) {
            $headers['HTTP-Referer'] = home_url( '/' );
            $headers['X-Title']      = get_bloginfo( 'name' );
        }

        $res = wp_remote_post( $f['url'], [
            'timeout' => 30,
            'headers' => $headers,
            'body'    => wp_json_encode([ 'model' => $model, 'max_tokens' => 1000, 'messages' => $msgs ]),
        ]);
        if ( is_wp_error($res) ) return ['ok'=>false,'error'=>$res->get_error_message()];

        $code = wp_remote_retrieve_response_code( $res );
        $data = json_decode( wp_remote_retrieve_body($res), true );
        if ( isset($data['choices'][0]['message']['content']) ) {
            $testo = trim( (string) $data['choices'][0]['message']['content'] );
            if ( '' !== $testo ) return [ 'ok' => true, 'text' => $testo ];
        }
        // Gemini restituisce l'errore dentro un array; gli altri no.
        $err = $data['error']['message'] ?? ( $data[0]['error']['message'] ?? null );
        return [ 'ok' => false, 'error' => $err ?: ( 'Risposta non valida (HTTP ' . $code . ')' ) ];
    }

    return ['ok'=>false,'error'=>'Fornitore non configurato'];
}

/* =========================================================
   BASE DI CONOSCENZA DAI TEMPLATE
   ========================================================= */
function exp_chatbot_system_con_conoscenza( $system, $templates, $s ) {
    if ( empty( $templates ) ) return $system;

    $pezzi = [];
    foreach ( $templates as $tpl ) {
        $testo = trim( (string) ( $tpl['message'] ?? '' ) );
        if ( '' === $testo ) continue;
        $titolo  = trim( (string) ( $tpl['label'] ?? '' ) );
        $pezzi[] = ( $titolo ? "## {$titolo}\n" : '' ) . exp_chatbot_placeholders( $testo, $s );
    }
    if ( empty( $pezzi ) ) return $system;

    return trim( $system )
        . "\n\n---\nBASE DI CONOSCENZA — informazioni verificate su Experiences Srl.\n"
        . "Usa questi dati per prezzi, numeri e case study: sono quelli veri. Non inventare\n"
        . "cifre diverse. Riformula con le tue parole, non incollare il testo.\n\n"
        . implode( "\n\n", $pezzi );
}

/* =========================================================
   PLACEHOLDER
   ========================================================= */
function exp_chatbot_placeholders( $text, $s ) {
    $h = (int) current_time('G');
    $saluto = $h>=6&&$h<14 ? 'Buongiorno' : ($h>=14&&$h<21 ? 'Buonasera' : 'Buonanotte');
    return str_replace(['{SALUTO}','{WHATSAPP}','{BOT_NAME}'],[$saluto,$s['whatsapp_nr']??'',$s['bot_name']??'Chatbot'],$text);
}

/* =========================================================
   SHORTCODE [exp_chatbot]
   ========================================================= */
add_shortcode( 'exp_chatbot', function( $atts ) {
    $s         = get_option( 'exp_chatbot_settings', exp_chatbot_defaults() );
    $templates = $s['templates'] ?? exp_chatbot_default_templates();
    $bot_name  = esc_html( $s['bot_name']     ?? 'Chatbot Experiences' );
    $bot_sub   = esc_html( $s['bot_subtitle']  ?? 'Online — risponde in pochi secondi' );
    $show_demo = ! empty( $s['show_demo_badge'] );
    $has_llm   = ! empty($s['llm_api_key']) && ($s['llm_provider']??'none') !== 'none';

    // {SALUTO} resta da risolvere nel browser. Risolverlo qui lo
    // congelava dentro l'HTML in cache: una pagina salvata di notte
    // diceva "Buonanotte" a chi la apriva alle dieci del mattino. Nel
    // browser si usa anche l'ora del visitatore, non quella del server.
    $auto_msgs = [];
    foreach ($templates as $tpl) {
        if ( empty(trim($tpl['triggers'])) && !empty(trim($tpl['message'])) ) {
            $auto_msgs[] = str_replace(
                ['{WHATSAPP}','{BOT_NAME}'],
                [$s['whatsapp_nr']??'', $s['bot_name']??'Chatbot'],
                $tpl['message']
            );
        }
    }

    $quick_qs = array_values( array_filter( array_map('trim', explode("\n", $s['quick_questions']??'')) ) );
    $nonce    = wp_create_nonce('exp_chatbot_front');
    $ajax_url = admin_url('admin-ajax.php');
    $nonce_url = esc_url_raw( rest_url('exp-chatbot/v1/nonce') );

    // ID univoco per supportare piu istanze nella stessa pagina
    static $instance = 0;
    $instance++;
    $uid = 'expcb' . $instance;

    ob_start();
    ?>
<style>
#<?php echo $uid; ?>{background:#fff;border-radius:1.25rem;box-shadow:0 20px 50px rgba(11,61,97,.12);border:1px solid rgba(20,163,163,.15);overflow:hidden;display:flex;flex-direction:column;min-height:460px;}
#<?php echo $uid; ?> .cb-head{background:linear-gradient(135deg,#0B3D61,#0D7C7C);padding:.9rem 1.1rem;display:flex;align-items:center;gap:.7rem;}
#<?php echo $uid; ?> .cb-icon{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;}
#<?php echo $uid; ?> .cb-icon i{color:#14A3A3;font-size:1rem;}
#<?php echo $uid; ?> .cb-name{color:#fff;font-weight:700;font-size:.9rem;}
#<?php echo $uid; ?> .cb-sub{color:rgba(255,255,255,.6);font-size:.68rem;display:flex;align-items:center;gap:.35rem;}
#<?php echo $uid; ?> .cb-dot{width:6px;height:6px;background:#22c55e;border-radius:50%;}
#<?php echo $uid; ?> .cb-demo{background:rgba(20,163,163,.25);padding:.25rem .7rem;border-radius:999px;color:#14A3A3;font-size:.65rem;font-weight:700;margin-left:auto;}
#<?php echo $uid; ?> .cb-ai-badge{background:rgba(20,163,163,.25);color:#14A3A3;font-size:.6rem;font-weight:700;padding:.15rem .5rem;border-radius:999px;margin-left:.4rem;}
#<?php echo $uid; ?> .cb-msgs{flex:1;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:.75rem;background:#f8fafb;}
#<?php echo $uid; ?> .cb-msgs::-webkit-scrollbar{width:4px;}
#<?php echo $uid; ?> .cb-msgs::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px;}
#<?php echo $uid; ?> .cb-row-bot,#<?php echo $uid; ?> .cb-row-usr{display:flex;gap:.5rem;align-items:flex-start;}
#<?php echo $uid; ?> .cb-row-usr{flex-direction:row-reverse;}
#<?php echo $uid; ?> .cb-av{width:28px;height:28px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.7rem;}
#<?php echo $uid; ?> .cb-av.bot{background:linear-gradient(135deg,#0D7C7C,#14A3A3);color:#fff;}
#<?php echo $uid; ?> .cb-av.usr{background:#e2e8f0;color:#64748b;}
#<?php echo $uid; ?> .cb-bub{max-width:82%;padding:.6rem .85rem;font-size:.82rem;line-height:1.6;}
#<?php echo $uid; ?> .cb-bub.bot{background:#fff;color:#1e293b;border-radius:.25rem 1rem 1rem 1rem;box-shadow:0 1px 4px rgba(0,0,0,.07);}
#<?php echo $uid; ?> .cb-bub.usr{background:#0D7C7C;color:#fff;border-radius:1rem .25rem 1rem 1rem;}
#<?php echo $uid; ?> .cb-time{font-size:.6rem;color:#94a3b8;margin-top:.15rem;}
#<?php echo $uid; ?> .cb-typing span{display:inline-block;width:6px;height:6px;background:#14A3A3;border-radius:50%;margin:0 2px;animation:cbDot 1.2s ease-in-out infinite;}
#<?php echo $uid; ?> .cb-typing span:nth-child(2){animation-delay:.2s;}
#<?php echo $uid; ?> .cb-typing span:nth-child(3){animation-delay:.4s;}
@keyframes cbDot{0%,60%,100%{transform:translateY(0)}30%{transform:translateY(-5px)}}
#<?php echo $uid; ?> .cb-quick{padding:.55rem 1rem;border-top:1px solid #e8f4f4;background:#fff;}
#<?php echo $uid; ?> .cb-qlabel{font-size:.63rem;color:#94a3b8;font-weight:600;margin:0 0 .4rem;text-transform:uppercase;letter-spacing:.05em;}
#<?php echo $uid; ?> .cb-qbtns{display:flex;flex-wrap:wrap;gap:.35rem;}
#<?php echo $uid; ?> .cb-qbtn{background:#E8F4F4;border:1px solid rgba(13,124,124,.2);color:#0D7C7C;font-size:.7rem;font-weight:600;padding:.3rem .75rem;border-radius:999px;cursor:pointer;transition:all .18s;white-space:nowrap;font-family:inherit;}
#<?php echo $uid; ?> .cb-qbtn:hover{background:#0D7C7C;color:#fff;border-color:#0D7C7C;}
#<?php echo $uid; ?> .cb-inputrow{padding:.75rem 1rem;border-top:1px solid #e8f4f4;background:#fff;display:flex;gap:.45rem;align-items:center;}
#<?php echo $uid; ?> .cb-input{flex:1;padding:.55rem .9rem;border:1.5px solid #e2e8f0;border-radius:.75rem;font-size:.82rem;outline:none;font-family:inherit;transition:border-color .2s;box-sizing:border-box;}
#<?php echo $uid; ?> .cb-input:focus{border-color:#0D7C7C;}
#<?php echo $uid; ?> .cb-send{width:38px;height:38px;min-width:38px;background:#0D7C7C;border:none;border-radius:.7rem;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;}
#<?php echo $uid; ?> .cb-send:hover{background:#14A3A3;}
</style>

<div id="<?php echo esc_attr($uid); ?>">

    <div class="cb-head">
        <div class="cb-icon"><i class="fas fa-robot"></i></div>
        <div style="flex:1;min-width:0;">
            <div class="cb-name">
                <?php echo $bot_name; ?>
                <?php if ($has_llm): ?><span class="cb-ai-badge">AI</span><?php endif; ?>
            </div>
            <div class="cb-sub"><span class="cb-dot"></span><?php echo $bot_sub; ?></div>
        </div>
        <?php if ($show_demo): ?><div class="cb-demo">DEMO</div><?php endif; ?>
    </div>

    <div class="cb-msgs" id="<?php echo esc_attr($uid); ?>-msgs"></div>

    <?php if (!empty($quick_qs)): ?>
    <div class="cb-quick">
        <p class="cb-qlabel">Domande rapide</p>
        <div class="cb-qbtns" id="<?php echo esc_attr($uid); ?>-qbtns">
        <?php foreach ($quick_qs as $q): ?>
            <button class="cb-qbtn" type="button"
                    data-widget="<?php echo esc_attr($uid); ?>"
                    data-msg="<?php echo esc_attr($q); ?>">
                <?php echo esc_html($q); ?>
            </button>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="cb-inputrow">
        <input class="cb-input"
               id="<?php echo esc_attr($uid); ?>-input"
               type="text"
               placeholder="Scrivi un messaggio..."
               data-widget="<?php echo esc_attr($uid); ?>">
        <button class="cb-send" type="button"
                id="<?php echo esc_attr($uid); ?>-send"
                data-widget="<?php echo esc_attr($uid); ?>"
                aria-label="Invia">
            <i class="fas fa-paper-plane" style="font-size:.8rem;pointer-events:none;"></i>
        </button>
    </div>

</div><!-- /#<?php echo $uid; ?> -->

<script>
(function(){
    /* ── Config ── */
    var UID   = '<?php echo esc_js($uid); ?>';
    var AJAX  = '<?php echo esc_js($ajax_url); ?>';
    var NONCE = '<?php echo esc_js($nonce); ?>';
    var NONCE_URL = '<?php echo esc_js($nonce_url); ?>';
    var AUTO  = <?php echo wp_json_encode( array_values($auto_msgs) ); ?>;
    var MAX_TURNS = <?php echo (int) ( $s['llm_max_turns'] ?? 6 ); ?>;

    /* Saluto calcolato sull'ora di chi legge. Prima arrivava gia
       risolto dal server e restava congelato nella pagina in cache. */
    function saluto() {
        var h = new Date().getHours();
        return h >= 6 && h < 14 ? 'Buongiorno' : (h >= 14 && h < 21 ? 'Buonasera' : 'Buonanotte');
    }
    function risolvi(t) { return String(t).replace(/\{SALUTO\}/g, saluto()); }

    /* Cronologia tenuta nel browser: il server non ha sessioni, e
       tenerle renderebbe la pagina non cacheabile. */
    var storia = [];
    function ricorda(role, text) {
        storia.push({ role: role, text: text });
        if (storia.length > MAX_TURNS) storia = storia.slice(-MAX_TURNS);
    }

    /* ── Elementi DOM ── */
    var wrap  = document.getElementById(UID);
    var box   = document.getElementById(UID + '-msgs');
    var inp   = document.getElementById(UID + '-input');
    var btn   = document.getElementById(UID + '-send');
    var qbtns = document.getElementById(UID + '-qbtns');

    if (!wrap || !box || !inp || !btn) return; // safety

    /* ── Helpers ── */
    function hhmm() {
        return new Date().toLocaleTimeString('it-IT',{hour:'2-digit',minute:'2-digit'});
    }
    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;')
                        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function nl2br(s) { return escHtml(s).replace(/\n/g,'<br>'); }

    function addMsg(txt, role) {
        var row = document.createElement('div');
        row.className = 'cb-row-' + role;

        var av = document.createElement('div');
        av.className = 'cb-av ' + role;
        av.innerHTML = role === 'bot'
            ? '<i class="fas fa-robot"></i>'
            : '<i class="fas fa-user"></i>';

        var wr  = document.createElement('div');
        wr.style.maxWidth = '82%';

        var bub = document.createElement('div');
        bub.className = 'cb-bub ' + role;
        bub.innerHTML = nl2br(txt);   /* FIX: innerHTML + nl2br — niente \n letterali */

        var ts = document.createElement('div');
        ts.className  = 'cb-time';
        ts.style.textAlign = (role === 'usr') ? 'right' : 'left';
        ts.textContent = hhmm();

        wr.appendChild(bub);
        wr.appendChild(ts);
        row.appendChild(av);
        row.appendChild(wr);
        box.appendChild(row);
        box.scrollTop = box.scrollHeight;
    }

    function showTyping() {
        var el = document.createElement('div');
        el.className = 'cb-row-bot';
        el.id = UID + '-typing';
        el.innerHTML = '<div class="cb-av bot"><i class="fas fa-robot"></i></div>'
            + '<div class="cb-bub bot cb-typing"><span></span><span></span><span></span></div>';
        box.appendChild(el);
        box.scrollTop = box.scrollHeight;
    }
    function hideTyping() {
        var t = document.getElementById(UID + '-typing');
        if (t) t.remove();
    }

    /* ── Funzione invio ── */
    function invia(txt, nonce) {
        var fd = new FormData();
        fd.append('action',  'exp_chatbot_message');
        fd.append('nonce',   nonce);
        fd.append('message', txt);
        if (storia.length) fd.append('history', JSON.stringify(storia));
        return fetch(AJAX, { method:'POST', body:fd, credentials:'same-origin' })
            .then(function(r) {
                return r.json().catch(function(){ return null; })
                    .then(function(body){ return { status: r.status, body: body }; });
            });
    }

    /* Il nonce e dentro l'HTML, e l'HTML sta in cache molto piu a lungo
       di quanto un nonce viva. Al primo rifiuto se ne prende uno fresco
       e si ritenta: il visitatore non vede niente. */
    function nonceFresco() {
        if (!NONCE_URL) return Promise.resolve(null);
        return fetch(NONCE_URL, { credentials:'same-origin', cache:'no-store' })
            .then(function(r){ return r.ok ? r.json() : null; })
            .then(function(d){ if (d && d.chatbot) { NONCE = d.chatbot; return d.chatbot; } return null; })
            .catch(function(){ return null; });
    }

    function scaduto(res) {
        return res.status === 403 || res.body === -1 || res.body === 0 || res.body === null ||
               (res.body && res.body.data && res.body.data.code === 'nonce');
    }

    function sendMsg(txt) {
        txt = (txt || '').trim();
        if (!txt) return;
        inp.value = '';
        addMsg(txt, 'usr');
        ricorda('user', txt);
        showTyping();

        invia(txt, NONCE)
            .then(function(res) {
                if (!scaduto(res)) return res;
                return nonceFresco().then(function(n){ return n ? invia(txt, n) : res; });
            })
            .then(function(res) {
                hideTyping();
                var d = res.body;
                if (d && d.success && d.data && d.data.reply) {
                    addMsg(d.data.reply, 'bot');
                    ricorda('assistant', d.data.reply);
                } else {
                    addMsg('Non riesco a rispondere in questo momento. Scrivici su WhatsApp e ti rispondiamo noi.', 'bot');
                }
            })
            .catch(function() {
                hideTyping();
                addMsg('Errore di connessione. Riprova.', 'bot');
            });
    }

    /* ── Event listeners (delegazione — nessun onclick inline) ── */

    // Tasto Invio nel campo testo
    inp.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); sendMsg(inp.value); }
    });

    // Bottone invia
    btn.addEventListener('click', function() { sendMsg(inp.value); });

    // Pulsanti domande rapide — delegazione sull'elemento padre
    if (qbtns) {
        qbtns.addEventListener('click', function(e) {
            var b = e.target.closest('.cb-qbtn');
            if (b && b.dataset.widget === UID) {
                sendMsg(b.dataset.msg);
            }
        });
    }

    /* ── Messaggi automatici di apertura ── */
    var ai = 0;
    function showAuto() {
        if (ai >= AUTO.length) return;
        showTyping();
        var msg   = risolvi(AUTO[ai]);
        var delay = Math.min(700 + msg.length * 16, 2400);
        setTimeout(function() {
            hideTyping();
            addMsg(msg, 'bot');
            ricorda('assistant', msg);
            ai++;
            if (ai < AUTO.length) setTimeout(showAuto, 800);
        }, delay);
    }
    setTimeout(showAuto, 500);

})();
</script>
    <?php
    return ob_get_clean();
});
