/**
 * Experiences Srl — HeyGen Streaming Avatar Configuration
 * Documentazione: https://docs.heygen.com/docs/streaming-api
 */

const AVATAR_CONFIG = {
    // ── HeyGen API Key ─────────────────────────────────────────
    apiKey: 'sk_V2_hgu_kBCj46zjR4v_IGvAabTyeZWHJn13wLN7r8nLbcLAL26f',

    // ── Avatar ID ──────────────────────────────────────────────
    // Scegli un avatar dalla libreria HeyGen → https://app.heygen.com/avatars
    // Avatar pubblici gratuiti disponibili:
    avatarId: 'Wayne_20240711',

    // ── Voce italiana ──────────────────────────────────────────
    voiceId: 'it-IT-DiegoNeural',

    // ── Qualità video ──────────────────────────────────────────
    quality: 'high',

    // ── Lingua ─────────────────────────────────────────────────
    language: 'it',

    // ── Personalità dell'assistente ────────────────────────────
    systemPrompt: `Sei l'assistente virtuale di Experiences Srl, un'azienda italiana
che offre soluzioni digitali complete per agenzie di viaggi e strutture alberghiere.

I tuoi servizi includono:
- Sviluppo e gestione siti web responsive con booking integrato
- SEO/SEM Marketing e campagne Google Ads
- Gestione Channel Manager (sincronizzazione OTA: Booking, Expedia, Agoda)
- Creazione e ottimizzazione annunci OTA
- Assistenti virtuali AI e chatbot 24/7

Piani tariffari:
- Base: €1.400/anno + 10% commissione (fino a 1.000 clienti)
- Advanced: €1.000/anno + 8% commissione (fino a 5.000 clienti)
- Pro: €500/anno + 5% commissione (fino a 10.000 clienti)
- Enterprise: €0/anno + 3% commissione (oltre 10.000 clienti, include AI)

Rispondi sempre in italiano, in modo professionale ma cordiale.
Sii conciso: massimo 2-3 frasi per risposta.
Per prenotare una consulenza gratuita, invita l'utente a compilare il form
nella sezione Contatti o a scrivere su WhatsApp al +39 392 691 7657.`,

    // ── HeyGen API base URL ────────────────────────────────────
    apiBase: 'https://api.heygen.com',
};
