/**
 * SDK LiveAvatar finto, usato solo da smoke.mjs.
 *
 * Espone la stessa superficie pubblica di @heygen/liveavatar-web-sdk
 * (LiveAvatarSession, voiceChat, eventi di sessione e di agente) ma senza
 * rete e senza WebRTC: serve a verificare la UI della pagina senza aprire
 * — e pagare — una sessione vera.
 */
(function () {
    function Emitter() { this._handlers = {}; }
    Emitter.prototype.on = function (event, fn) {
        (this._handlers[event] = this._handlers[event] || []).push(fn);
        return this;
    };
    Emitter.prototype.emit = function (event) {
        var args = [].slice.call(arguments, 1);
        (this._handlers[event] || []).forEach(function (fn) { fn.apply(null, args); });
    };

    var SessionEvent = {
        SESSION_STATE_CHANGED: "session.state_changed",
        SESSION_STREAM_READY: "session.stream_ready",
        SESSION_CONNECTION_QUALITY_CHANGED: "session.connection_quality_changed",
        SESSION_DISCONNECTED: "session.disconnected"
    };
    var SessionState = {
        INACTIVE: "INACTIVE", CONNECTING: "CONNECTING", CONNECTED: "CONNECTED",
        DISCONNECTING: "DISCONNECTING", DISCONNECTED: "DISCONNECTED"
    };
    var AgentEventsEnum = {
        USER_TRANSCRIPTION: "user.transcription",
        USER_TRANSCRIPTION_CHUNK: "user.transcription.chunk",
        AVATAR_TRANSCRIPTION: "avatar.transcription",
        AVATAR_TRANSCRIPTION_CHUNK: "avatar.transcription.chunk",
        AVATAR_SPEAK_STARTED: "avatar.speak_started",
        AVATAR_SPEAK_ENDED: "avatar.speak_ended",
        USER_SPEAK_STARTED: "user.speak_started",
        USER_SPEAK_ENDED: "user.speak_ended",
        SESSION_STOPPED: "session.stopped"
    };
    var VoiceChatEvent = { MUTED: "MUTED", UNMUTED: "UNMUTED", STATE_CHANGED: "STATE_CHANGED" };
    var SessionInteractivityMode = { CONVERSATIONAL: "CONVERSATIONAL", PUSH_TO_TALK: "PUSH_TO_TALK" };

    function VoiceChat() { Emitter.call(this); this._muted = true; }
    VoiceChat.prototype = Object.create(Emitter.prototype);
    Object.defineProperty(VoiceChat.prototype, "isMuted", { get: function () { return this._muted; } });
    VoiceChat.prototype.mute = function () {
        this._muted = true; this.emit(VoiceChatEvent.MUTED); return Promise.resolve();
    };
    VoiceChat.prototype.unmute = function () {
        this._muted = false; this.emit(VoiceChatEvent.UNMUTED); return Promise.resolve();
    };

    function LiveAvatarSession(token, config) {
        Emitter.call(this);
        this.voiceChat = new VoiceChat();
        this._config = config;
        window.__stub = { attached: false, interrupted: false, lastMessage: null, keepAlives: 0 };
    }
    LiveAvatarSession.prototype = Object.create(Emitter.prototype);

    LiveAvatarSession.prototype.start = function () {
        var self = this;
        return new Promise(function (resolve) {
            setTimeout(function () {
                self.emit(SessionEvent.SESSION_STATE_CHANGED, SessionState.CONNECTED);
                self.emit(SessionEvent.SESSION_STREAM_READY);
                self.emit(SessionEvent.SESSION_CONNECTION_QUALITY_CHANGED, "EXCELLENT");
                if (self._config && self._config.voiceChat) self.voiceChat.emit(VoiceChatEvent.MUTED);
                resolve();
            }, 60);
        });
    };
    LiveAvatarSession.prototype.attach = function () { window.__stub.attached = true; };
    LiveAvatarSession.prototype.keepAlive = function () {
        window.__stub.keepAlives += 1; return Promise.resolve();
    };
    LiveAvatarSession.prototype.interrupt = function () { window.__stub.interrupted = true; };
    LiveAvatarSession.prototype.message = function (text) {
        var self = this;
        window.__stub.lastMessage = text;
        // Riproduce l'ordine reale: chunk progressivi, poi la trascrizione finale.
        setTimeout(function () {
            self.emit(AgentEventsEnum.AVATAR_SPEAK_STARTED, {});
            self.emit(AgentEventsEnum.AVATAR_TRANSCRIPTION_CHUNK, { text: "Certo," });
            self.emit(AgentEventsEnum.AVATAR_TRANSCRIPTION_CHUNK, { text: "Certo, le nostre camere" });
            self.emit(AgentEventsEnum.AVATAR_TRANSCRIPTION, { text: "Certo, le nostre camere sono tutte vista mare." });
            self.emit(AgentEventsEnum.AVATAR_SPEAK_ENDED, {});
        }, 30);
        return "evt-" + Date.now();
    };
    LiveAvatarSession.prototype.stop = function () {
        var self = this;
        return new Promise(function (resolve) {
            setTimeout(function () {
                self.emit(SessionEvent.SESSION_DISCONNECTED, "CLIENT_INITIATED");
                resolve();
            }, 20);
        });
    };

    window.LiveAvatarSDK = {
        LiveAvatarSession: LiveAvatarSession,
        SessionEvent: SessionEvent,
        SessionState: SessionState,
        AgentEventsEnum: AgentEventsEnum,
        VoiceChatEvent: VoiceChatEvent,
        SessionInteractivityMode: SessionInteractivityMode
    };
})();
