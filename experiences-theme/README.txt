=== Experiences Srl WordPress Theme v2.1 ===

NOVITÀ v2.1:
- ✅ Sezione Blog/Risorse in homepage (ultimi 3 articoli + placeholder se vuoto)
- ✅ single.php → Layout completo articolo blog (hero, share, autore, correlati, prev/next)
- ✅ archive.php → Lista articoli (featured + grid + filtri categoria + paginazione)
- ✅ Helper: tempo di lettura, post views counter, excerpt 40 parole
- ✅ CSS .prose-blog completo (h2/h3, blockquote con design, drop cap, callout, table, pre)
- ✅ Image size custom 'blog-thumb' (1200x675, 16:9)

NOVITÀ v2.0:
- ✅ Hero con selettore verticale (Hotel/Agenzie/Tour Operator)
- ✅ Services con framework Problem→Solution→Risultato
- ✅ NUOVA Case Study Section con metriche reali
- ✅ Pricing con spiegazione modello commission-based
- ✅ Chatbot v1.3 con template segmentati per verticale


INSTALLAZIONE:
1. Carica la cartella 'experiences-theme' in /wp-content/themes/
   OPPURE carica lo ZIP da Aspetto → Temi → Aggiungi Nuovo → Carica Tema
2. Attiva il tema da Aspetto → Temi
3. Vai in Impostazioni → Lettura → seleziona "Una pagina statica" → Homepage: qualsiasi pagina
4. Il tema usa front-page.php come homepage automaticamente

PORTFOLIO DINAMICO (per aggiungere nuovi siti):
1. Nel menu WordPress appare "Portfolio Siti"
2. Aggiungi un nuovo sito: titolo, immagine in evidenza, URL (campo personalizzato 'site_url'), categoria ('site_category')
3. Nel front-page.php sostituisci la sezione portfolio statica con: [experiences_portfolio posts="6"]

FILE STRUTTURA:
/experiences-theme/
  style.css           → Identificazione tema + CSS completo
  functions.php       → Registrazione assets, CPT portfolio, form AJAX, SEO
  front-page.php      → Homepage completa (caricata automaticamente da WP)
  header.php          → <head> con wp_head()
  footer.php          → wp_footer() + </body>
  index.php           → Fallback
  404.php             → Pagina errore personalizzata
  assets/
    js/main.js        → JavaScript completo con form AJAX
    img/              → Metti qui: logo.png, og-image.jpg (1200x630px)
    css/              → CSS aggiuntivo opzionale

DOPO L'ATTIVAZIONE:
- Installa Rank Math SEO per gestione avanzata meta tag
- Installa WP Super Cache per velocità
- Collega Google Search Console
- Aggiungi screenshot tema in assets/img/screenshot.png (880x660px)

CONTATTI:
segreteria@naplesexperiences.com
WhatsApp: +39 392 691 7657
