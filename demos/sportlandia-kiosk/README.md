# SPORTLANDIA - Kiosk Digitale Touch Screen

## 📱 Descrizione

Sito web interattivo verticale (portrait) ottimizzato per schermi touch screen 42" HD presso Sportlandia, centro fitness a Castellammare di Stabia.

### 🎯 Caratteristiche Principali

- **Layout Verticale (Portrait)** - Ottimizzato per schermi 42" touch screen
- **Galleria animata con GSAP** - Timeline interrompibili: il tap rapido su
  avanti/indietro resta sempre coerente
- **Due modalità di slide** - Le locandine ufficiali si vedono per intero
  (`contain`, mai tagliate); le foto riempiono lo schermo con testo sovrapposto
- **Popup orari** - Tocca una slide per il dettaglio degli orari
- **Play/pausa** - Lo scorrimento automatico si può fermare
- **Barra di avanzamento sincrona** - La barra *è* il timer, quindi pausa e
  ripresa sono esatte
- **Accessibile** - Focus visibile, `aria-live`, `prefers-reduced-motion`
- **Funziona offline** - GSAP vendorizzato, font non bloccante (init ~110 ms)

## 📚 Corsi Disponibili (orari ufficiali stagione 25/26)

1. **Sala Attrezzi** 🏋️ — Lun-Ven 07:00-21:00, Sab 09:00-12:00
2. **Pilates Matwork** 🧘 — Base / Intermedio / Avanzato
3. **Pilates Reformer** ⚙️ — Lun-Sab, sessioni multiple giornaliere
4. **Pilates Studio** 🎯 — Su appuntamento (Cadillac, Barile)
5. **TEC-Room** 💪 — Mar/Gio, allenamento tecnico-funzionale
6. **Les Mills** 📺 — Programmi Shapes, Strength, Circuito
7. **Fisiorehab 360** 🏥 — Fisioterapia e riabilitazione su appuntamento
8. **GREENtosi** 🌱 — Sostenibilità e impegno eco-friendly

> Orari estratti dal PDF ufficiale **SPORTLANDIA ORARI 25/26**.

## 📍 Contatti

- **Sportlandia · SSD INFITNESS ARL**
- Corso Alcide De Gasperi, 177/B — Castellammare di Stabia (NA)
- Tel. +39 081 871 8076
- Email: ssdinfitness@gmail.com
- **Apertura Centro**: Lun-Ven 07:00-21:00 · Sabato 08:00-12:30

## 🎨 Design

- **Colori Primari**: Blu cobalto (#0d47a1, #1976d2) + Ciano Infitness (#00bcd4)
  su navy profondo (#062c5c) — palette del marchio Sportlandia
- **Font**: Barlow Condensed (titoli) + Barlow (testo), coppia tipografica
  per brand sportivi; caricati in modo non bloccante
- **Icone**: SVG inline (nessuna emoji)
- **Animazioni**: Timeline GSAP 3.15. Navigazione manuale = scorrimento
  direzionale; avanzamento automatico = dissolvenza

## 🖱️ Interazioni

### Navigazione Principale
- **‹ Pulsante Sinistra**: Corso precedente
- **Contatore**: Posizione attuale / Totale corsi
- **⏸ Play/Pausa**: Ferma o riprende lo scorrimento automatico
- **› Pulsante Destra**: Corso successivo
- **Pallini**: Salto diretto a un corso

### Dettagli Corsi
- **Click sulla Card**: Apre popup con orari completi
- **Click Esterno**: Chiude il popup
- **Tasto Esc**: Chiude il popup

### Tasti Supportati
- **Freccia Sinistra/Destra**: Navigazione galleria
- **Spazio**: Play/pausa
- **Escape**: Chiudi popup

### Comportamento automatico
Lo scorrimento si ferma da solo quando la pagina non è visibile, quando la
galleria esce dallo schermo e quando il popup orari è aperto.

## 📱 Specifiche Tecniche

- **HTML5** - Semantica moderna
- **CSS3** - Custom properties per le fasce fisse (`--chrome-top/bottom`)
- **GSAP 3.15** - Vendorizzato in `vendor/` (73 KB): il totem deve funzionare
  anche senza rete
- **Responsive** - 1080×1920 (42"), tablet e mobile

## 🌐 Branding

Footer con contatti Sportlandia e link a **naplesexperiences.com**.

## 🚀 Utilizzo

1. Apri `index.html` nel browser
2. Usa i pulsanti ← e → per navigare tra i corsi
3. Clicca su ogni corso per visualizzare gli orari completi
4. Il sito è touch-friendly per schermi da 42"

## 📁 File Structure

```
sportlandia-kiosk/
├── index.html      # Pagina principale con HTML, CSS e JS inline
└── README.md       # Questo file
```

## 👥 Crediti

- **Cliente**: Sportlandia - Centro Fitness Castellammare di Stabia
- **Sviluppatore**: experiences SRL
- **Anno**: 2026

## 📧 Contatti

Per domande o modifiche contatta **experiences SRL**:
- 🌐 [Sito Web](https://naplesexperiences.com)
- 📧 [Email](mailto:info@naplesexperiences.com)

---

**© 2026 Sportlandia - Powered by naplesexperiences.com**
