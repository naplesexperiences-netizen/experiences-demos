<?php
/**
 * Plugin Name:  Experiences Chatbot
 * Plugin URI:   https://www.naplesexperiences.com
 * Description:  Chatbot testuale configurabile per Experiences Srl.
 * Version:      1.3.0
 * Author:       Experiences Srl
 * License:      Proprietary
 * Text Domain:  exp-chatbot
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'EXP_CHATBOT_VERSION', '1.3.0' );

/* =========================================================
   DEFAULTS
   ========================================================= */
function exp_chatbot_defaults() {
    return [
        'llm_provider'    => 'none',
        'llm_api_key'     => '',
        'llm_model'       => 'claude-sonnet-4-5',
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
    if ( ! wp_verify_nonce( $_POST['exp_chatbot_nonce'], 'exp_chatbot_save' ) ) return;
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
        <tr><th><label for="llm_provider">Provider AI</label></th>
            <td><select id="llm_provider" name="llm_provider" onchange="expToggleLLM(this.value)">
                <option value="none"   <?php selected($s['llm_provider'],'none'); ?>>Nessuno — solo template</option>
                <option value="claude" <?php selected($s['llm_provider'],'claude'); ?>>Anthropic Claude</option>
                <option value="openai" <?php selected($s['llm_provider'],'openai'); ?>>OpenAI (GPT)</option>
            </select></td></tr>
        <tr id="row_apikey" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th><label for="llm_api_key">API Key</label></th>
            <td><input type="password" id="llm_api_key" name="llm_api_key" value=""
                       placeholder="<?php echo !empty($s['llm_api_key']) ? '●●●●●●●● (salvata — lascia vuoto per mantenerla)' : 'Inserisci API key'; ?>"
                       class="large-text" autocomplete="new-password">
                <p class="description">
                    Claude: <a href="https://console.anthropic.com" target="_blank">console.anthropic.com</a> —
                    OpenAI: <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com</a>
                    <?php if(!empty($s['llm_api_key'])): ?>
                    <br><span style="color:#28a745;font-weight:600;">Chiave attualmente salvata.</span>
                    <?php endif; ?>
                </p></td></tr>
        <tr id="row_model" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th><label for="llm_model">Modello</label></th>
            <td><input type="text" id="llm_model" name="llm_model" value="<?php echo esc_attr($s['llm_model']); ?>" class="regular-text">
                <p class="description">Claude: <code>claude-sonnet-4-5</code> / <code>claude-haiku-4-5</code> — OpenAI: <code>gpt-4o-mini</code></p></td></tr>
        <tr id="row_system" style="<?php echo $s['llm_provider']==='none'?'display:none':''; ?>">
            <th><label for="llm_system">System Prompt</label></th>
            <td><textarea id="llm_system" name="llm_system" rows="8" class="large-text"><?php echo esc_textarea($s['llm_system']); ?></textarea></td></tr>
    </table>
    <?php if ( $s['llm_provider'] !== 'none' && $s['llm_api_key'] ): ?>
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
    <script>function expToggleLLM(v){var show=v!=='none';['row_apikey','row_model','row_system'].forEach(function(id){document.getElementById(id).style.display=show?'':'none';});}</script>
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
    check_ajax_referer( 'exp_chatbot_front', 'nonce' );
    $msg = sanitize_text_field( $_POST['message'] ?? '' );
    if ( ! $msg ) wp_send_json_error(['reply'=>'Messaggio vuoto.']);

    $s         = get_option( 'exp_chatbot_settings', exp_chatbot_defaults() );
    $templates = $s['templates'] ?? exp_chatbot_default_templates();
    $low       = strtolower( $msg );

    foreach ( $templates as $tpl ) {
        if ( empty( trim($tpl['triggers']) ) ) continue;
        foreach ( array_map('trim', explode(',', strtolower($tpl['triggers']))) as $k ) {
            if ( $k && strpos($low, $k) !== false ) {
                wp_send_json_success(['reply'=> exp_chatbot_placeholders($tpl['message'],$s), 'source'=>'template']);
            }
        }
    }

    if ( $s['llm_provider'] !== 'none' && ! empty($s['llm_api_key']) ) {
        $r = exp_chatbot_call_llm( $msg, $s );
        if ( $r['ok'] ) wp_send_json_success(['reply'=>$r['text'],'source'=>'llm']);
    }

    $def = "Grazie per la domanda!\n\nPer info specifiche contatta Experiences Srl:\n- Form: sezione Contatti\n- WhatsApp: ".($s['whatsapp_nr']??'+39 392 691 7657')."\n\nRispondiamo entro poche ore!";
    wp_send_json_success(['reply'=>$def,'source'=>'default']);
}

/* =========================================================
   LLM CALL — server-side, chiave mai esposta al browser
   ========================================================= */
function exp_chatbot_call_llm( $msg, $s ) {
    $provider = $s['llm_provider'] ?? 'none';
    $key      = $s['llm_api_key']  ?? '';
    $model    = $s['llm_model']    ?? 'claude-sonnet-4-5';
    $system   = $s['llm_system']   ?? '';
    if ( empty($key) ) return ['ok'=>false,'error'=>'API key mancante'];

    if ( $provider === 'claude' ) {
        $body = ['model'=>$model,'max_tokens'=>500,'messages'=>[['role'=>'user','content'=>$msg]]];
        if ( $system ) $body['system'] = $system;
        $res = wp_remote_post('https://api.anthropic.com/v1/messages',[
            'timeout'=>20,
            'headers'=>['x-api-key'=>$key,'anthropic-version'=>'2023-06-01','content-type'=>'application/json'],
            'body'=>wp_json_encode($body),
        ]);
        if ( is_wp_error($res) ) return ['ok'=>false,'error'=>$res->get_error_message()];
        $code = wp_remote_retrieve_response_code($res);
        $data = json_decode(wp_remote_retrieve_body($res),true);
        if ( isset($data['content'][0]['text']) ) return ['ok'=>true,'text'=>trim($data['content'][0]['text'])];
        return ['ok'=>false,'error'=>($data['error']['message']??'Errore HTTP '.$code)];
    }

    if ( $provider === 'openai' ) {
        $msgs = [];
        if ($system) $msgs[] = ['role'=>'system','content'=>$system];
        $msgs[] = ['role'=>'user','content'=>$msg];
        $res = wp_remote_post('https://api.openai.com/v1/chat/completions',[
            'timeout'=>20,
            'headers'=>['Authorization'=>'Bearer '.$key,'Content-Type'=>'application/json'],
            'body'=>wp_json_encode(['model'=>$model,'max_tokens'=>500,'messages'=>$msgs]),
        ]);
        if ( is_wp_error($res) ) return ['ok'=>false,'error'=>$res->get_error_message()];
        $data = json_decode(wp_remote_retrieve_body($res),true);
        if ( isset($data['choices'][0]['message']['content']) ) return ['ok'=>true,'text'=>trim($data['choices'][0]['message']['content'])];
        return ['ok'=>false,'error'=>($data['error']['message']??'Risposta non valida')];
    }

    return ['ok'=>false,'error'=>'Provider non configurato'];
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

    $auto_msgs = [];
    foreach ($templates as $tpl) {
        if ( empty(trim($tpl['triggers'])) && !empty(trim($tpl['message'])) ) {
            $auto_msgs[] = exp_chatbot_placeholders($tpl['message'], $s);
        }
    }

    $quick_qs = array_values( array_filter( array_map('trim', explode("\n", $s['quick_questions']??'')) ) );
    $nonce    = wp_create_nonce('exp_chatbot_front');
    $ajax_url = admin_url('admin-ajax.php');

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
    var AUTO  = <?php echo wp_json_encode( array_values($auto_msgs) ); ?>;

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
    function sendMsg(txt) {
        txt = (txt || '').trim();
        if (!txt) return;
        inp.value = '';
        addMsg(txt, 'usr');
        showTyping();

        var fd = new FormData();
        fd.append('action',  'exp_chatbot_message');
        fd.append('nonce',   NONCE);
        fd.append('message', txt);

        fetch(AJAX, { method:'POST', body:fd })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                hideTyping();
                addMsg(d.success ? d.data.reply : 'Errore. Riprova più tardi.', 'bot');
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
        var msg   = AUTO[ai];
        var delay = Math.min(700 + msg.length * 16, 2400);
        setTimeout(function() {
            hideTyping();
            addMsg(msg, 'bot');
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
