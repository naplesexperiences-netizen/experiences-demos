/**
 * Experiences Srl — HeyGen Streaming Avatar Engine
 * Gestisce la sessione WebRTC con HeyGen Streaming API v2
 */

// ── State ─────────────────────────────────────────────────────
let avatarSession   = null;
let peerConnection  = null;
let sessionToken    = null;
let isMuted         = false;
let isSessionActive = false;
let chatHistory     = [];

// ── DOM refs ──────────────────────────────────────────────────
const getEl = id => document.getElementById(id);

// ── UI helpers ────────────────────────────────────────────────
function avatarSetStatus(text, color = 'gray') {
    const dot  = getEl('status-dot');
    const span = getEl('status-text');
    if (!dot || !span) return;
    const colors = { gray:'bg-gray-400', green:'bg-green-400', amber:'bg-amber-400', red:'bg-red-400' };
    dot.className = `w-2 h-2 rounded-full ${colors[color] || colors.gray}`;
    span.textContent = text;
}

function avatarAddMessage(text, role = 'assistant') {
    const win = getEl('avatar-chat-window');
    if (!win) return;
    const isUser = role === 'user';
    const div = document.createElement('div');
    div.className = `flex gap-3 mb-4 ${isUser ? 'flex-row-reverse' : ''}`;
    div.innerHTML = `
        ${!isUser ? `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-secondary to-accent flex items-center justify-center flex-shrink-0">
            <i class="fas fa-robot text-white text-xs"></i></div>` : ''}
        <div class="${isUser
            ? 'bg-secondary text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-xs ml-auto'
            : 'bg-white rounded-2xl rounded-tl-none px-4 py-3 shadow-sm max-w-xs'}">
            <p class="text-sm ${isUser ? 'text-white' : 'text-gray-700'}">${text}</p>
            <span class="text-xs ${isUser ? 'text-white/70' : 'text-gray-400'} mt-1 block">${new Date().toLocaleTimeString('it-IT',{hour:'2-digit',minute:'2-digit'})}</span>
        </div>
        ${isUser ? `<div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user text-gray-500 text-xs"></i></div>` : ''}
    `;
    win.appendChild(div);
    win.scrollTop = win.scrollHeight;
    chatHistory.push({ role, content: text });
}

function avatarShowTyping() {
    const win = getEl('avatar-chat-window');
    if (!win) return;
    const div = document.createElement('div');
    div.id = 'typing-indicator';
    div.className = 'flex gap-3 mb-4';
    div.innerHTML = `
        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-secondary to-accent flex items-center justify-center flex-shrink-0">
            <i class="fas fa-robot text-white text-xs"></i></div>
        <div class="bg-white rounded-2xl rounded-tl-none px-4 py-3 shadow-sm">
            <div class="flex gap-1 items-center h-4">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0.15s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0.3s"></div>
            </div>
        </div>`;
    win.appendChild(div);
    win.scrollTop = win.scrollHeight;
}

function avatarRemoveTyping() {
    const t = getEl('typing-indicator');
    if (t) t.remove();
}

// ── AI Text reply (Claude API fallback when no HeyGen session) ──
async function getAIReply(userMessage) {
    // Build context-aware messages
    const messages = [
        {
            role: 'user',
            content: AVATAR_CONFIG.systemPrompt + '\n\nRispondi in massimo 3 frasi, in italiano.'
        },
        { role: 'assistant', content: 'Certo, sono pronto ad aiutarti!' },
        ...chatHistory.slice(-6).map(m => ({ role: m.role, content: m.content })),
        { role: 'user', content: userMessage }
    ];

    try {
        const res = await fetch('https://api.anthropic.com/v1/messages', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                model: 'claude-sonnet-4-20250514',
                max_tokens: 300,
                messages: messages.slice(-8) // keep last 8 turns
            })
        });
        if (!res.ok) throw new Error('API error');
        const data = await res.json();
        return data.content[0].text;
    } catch {
        return 'Mi dispiace, al momento non riesco a rispondere. Contattaci su WhatsApp per assistenza immediata! 😊';
    }
}

// ── HeyGen: get streaming token ────────────────────────────────
async function heygenGetToken() {
    if (!AVATAR_CONFIG.apiKey || AVATAR_CONFIG.apiKey.includes('INSERISCI')) {
        return null;
    }
    try {
        const res = await fetch(`${AVATAR_CONFIG.apiBase}/v1/streaming.create_token`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Api-Key': AVATAR_CONFIG.apiKey,
            }
        });
        const data = await res.json();
        return data.data?.token || null;
    } catch { return null; }
}

// ── HeyGen: create session ─────────────────────────────────────
async function heygenCreateSession(token) {
    const res = await fetch(`${AVATAR_CONFIG.apiBase}/v1/streaming.new`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({
            quality:   AVATAR_CONFIG.quality,
            avatar_id: AVATAR_CONFIG.avatarId,
            voice: {
                voice_id: AVATAR_CONFIG.voiceId,
                rate: 1.0,
            },
            version: 'v2',
        })
    });
    return await res.json();
}

// ── HeyGen: setup WebRTC ───────────────────────────────────────
async function heygenSetupWebRTC(sessionData, token) {
    const pc = new RTCPeerConnection();
    peerConnection = pc;

    pc.ontrack = (event) => {
        const video = getEl('avatar-video');
        if (event.track.kind === 'video' && video) {
            video.srcObject = event.streams[0];
            video.style.opacity = '1';
            getEl('avatar-idle').style.opacity = '0';
            setTimeout(() => getEl('avatar-idle').style.display = 'none', 700);
        }
    };

    pc.onicecandidate = async ({ candidate }) => {
        if (candidate) {
            await fetch(`${AVATAR_CONFIG.apiBase}/v1/streaming.ice`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({ session_id: sessionData.session_id, candidate })
            });
        }
    };

    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);

    const sdpRes = await fetch(`${AVATAR_CONFIG.apiBase}/v1/streaming.start`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({
            session_id: sessionData.session_id,
            sdp: offer,
        })
    });
    const sdpData = await sdpRes.json();
    await pc.setRemoteDescription(new RTCSessionDescription(sdpData.data?.sdp));
    return pc;
}

// ── HeyGen: make avatar speak ──────────────────────────────────
async function heygenSpeak(text) {
    if (!sessionToken || !avatarSession) return;
    getEl('avatar-speaking-ui').classList.remove('hidden');
    await fetch(`${AVATAR_CONFIG.apiBase}/v1/streaming.task`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${sessionToken}`,
        },
        body: JSON.stringify({
            session_id: avatarSession.session_id,
            text,
            task_type: 'talk',
        })
    });
    setTimeout(() => getEl('avatar-speaking-ui').classList.add('hidden'), text.length * 60 + 1500);
}

// ── PUBLIC: Start session ──────────────────────────────────────
window.avatarStartSession = async function () {
    const btn = getEl('btn-start-session');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Connessione...</span>';
    avatarSetStatus('Connessione in corso...', 'amber');

    // Try HeyGen first
    const token = await heygenGetToken();

    if (token) {
        sessionToken = token;
        try {
            const sessionData = await heygenCreateSession(token);
            avatarSession = sessionData.data;
            await heygenSetupWebRTC(avatarSession, token);
            isSessionActive = true;
            avatarSetStatus('Sessione attiva', 'green');
            getEl('live-dot').className = 'w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse';
            avatarAddMessage('Ciao! Sono il tuo assistente virtuale di Experiences Srl. Come posso aiutarti oggi? Puoi parlarmi direttamente oppure scrivere qui sotto.');
            await heygenSpeak('Ciao! Sono il tuo assistente virtuale di Experiences Srl. Come posso aiutarti oggi?');
        } catch (e) {
            console.error('HeyGen error:', e);
            avatarSetStatus('Usa la chat testuale', 'gray');
            avatarAddMessage('La sessione video non è disponibile al momento. Puoi comunque scrivermi qui! Come posso aiutarti?');
        }
    } else {
        // Text-only AI mode
        avatarSetStatus('Modalità chat attiva', 'green');
        avatarAddMessage('Ciao! Sono in modalità testuale. Per attivare l\'avatar video, configura la API Key di HeyGen nel file avatar-config.js. Come posso aiutarti?');
        isSessionActive = true;
    }

    btn.style.display = 'none';
    getEl('btn-end-session').style.display = '';
    getEl('btn-end-session').disabled = false;
    getEl('btn-mute').style.display = '';
    getEl('btn-mute').disabled = false;
    getEl('avatar-api-notice').style.display = 'none';
};

// ── PUBLIC: End session ────────────────────────────────────────
window.avatarEndSession = async function () {
    if (avatarSession && sessionToken) {
        try {
            await fetch(`${AVATAR_CONFIG.apiBase}/v1/streaming.stop`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${sessionToken}` },
                body: JSON.stringify({ session_id: avatarSession.session_id })
            });
        } catch {}
    }
    if (peerConnection) peerConnection.close();
    const video = getEl('avatar-video');
    if (video) { video.srcObject = null; video.style.opacity = '0'; }
    const idle = getEl('avatar-idle');
    if (idle) { idle.style.display = ''; idle.style.opacity = '1'; }
    avatarSession = sessionToken = peerConnection = null;
    isSessionActive = isMuted = false;
    avatarSetStatus('Sessione terminata', 'gray');
    getEl('live-dot').className = 'w-1.5 h-1.5 bg-red-400 rounded-full';
    getEl('btn-start-session').style.display = '';
    getEl('btn-start-session').disabled = false;
    getEl('btn-start-session').innerHTML = '<i class="fas fa-play"></i><span>Avvia Sessione</span>';
    getEl('btn-end-session').style.display = 'none';
    getEl('btn-mute').style.display = 'none';
    getEl('avatar-api-notice').style.display = '';
};

// ── PUBLIC: Toggle mute ────────────────────────────────────────
window.avatarToggleMute = function () {
    isMuted = !isMuted;
    if (peerConnection) {
        peerConnection.getReceivers().forEach(r => { if (r.track.kind === 'audio') r.track.enabled = !isMuted; });
    }
    getEl('mute-icon').className = isMuted ? 'fas fa-microphone-slash' : 'fas fa-microphone';
    getEl('btn-mute').className = isMuted
        ? 'px-4 py-3 border-2 border-red-300 text-red-400 rounded-xl transition-all'
        : 'px-4 py-3 border-2 border-gray-200 text-gray-600 rounded-xl transition-all';
};

// ── PUBLIC: Send message ───────────────────────────────────────
window.avatarSendMessage = async function (preset) {
    const input = getEl('avatar-text-input');
    const text  = preset || (input ? input.value.trim() : '');
    if (!text) return;
    if (input) input.value = '';

    avatarAddMessage(text, 'user');
    avatarShowTyping();

    // Get AI reply
    const reply = await getAIReply(text);
    avatarRemoveTyping();
    avatarAddMessage(reply, 'assistant');

    // Make avatar speak if session active
    if (isSessionActive && avatarSession && sessionToken) {
        await heygenSpeak(reply);
    }
};

// ── CSS: wave animation ────────────────────────────────────────
const waveStyle = document.createElement('style');
waveStyle.textContent = `
    @keyframes wave {
        0%, 100% { transform: scaleY(0.4); }
        50% { transform: scaleY(1); }
    }
    #avatar-idle { transition: opacity 0.7s ease; }
`;
document.head.appendChild(waveStyle);
