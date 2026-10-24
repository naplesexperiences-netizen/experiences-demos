# Accoglienza FMA — tema e plugin WordPress

La demo statica (`../index.html`) diventa un sito WordPress: stesso aspetto, stesse animazioni, stessi CSS e JS.

| Cartella | Cosa fa |
|---|---|
| `themes/accoglienza-fma/` | Il tema: home, pagina struttura, archivio strutture, blog, articolo, pagine, 404. Tema classico PHP, senza page builder. |
| `plugins/fma-strutture/` | Strutture (tipo di contenuto `struttura`), regioni, servizi, partner, campi e importatore dei contenuti. |
| `plugins/fma-richieste/` | Modulo di richiesta di soggiorno: ogni richiesta arriva all'email della struttura. |

Tema e plugin sono separati apposta: strutture e richieste restano nel sito anche se un domani si cambia tema.

## Installazione

1. Crea i pacchetti con `./build-zip.sh`. Finiscono in `dist/`:
   `accoglienza-fma.zip`, `fma-strutture.zip`, `fma-richieste.zip` e `fma-contenuti.zip` (dati e foto della demo per l'importatore).
2. In WordPress:
   - **Plugin → Aggiungi nuovo → Carica plugin**: carica e attiva `fma-strutture.zip` e `fma-richieste.zip`.
   - **Aspetto → Temi → Aggiungi nuovo → Carica tema**: carica e attiva `accoglienza-fma.zip`.
3. **Impostazioni → Permalink**: scegli «Nome articolo» e salva. Servono gli indirizzi `/strutture/<nome>/`.
4. Contenuti, in uno di questi tre modi. Tutti importano 8 strutture, 6 partner e 5 articoli con le foto. Si possono rilanciare: aggiornano quello che c'è già e non creano doppioni.
   Un file con i soli articoli (`"strutture": []`) aggiunge o aggiorna gli articoli e li collega alle case già presenti, senza toccare le schede delle strutture.
   Le immagini si riconoscono dal percorso nello zip (es. `blog/<slug>.webp`): se il file è cambiato viene caricata la nuova versione e quella vecchia resta in **Media**; se è uguale non si carica niente.
   - **Dal browser** (consigliato): **Strumenti → Importa contenuti FMA**, scegli `fma-contenuti.zip` e premi *Importa*. Una barra mostra l'avanzamento, una struttura alla volta, in circa un minuto. Se la pagina si chiude o un passo fallisce, riaprendola riprende da dove era rimasta.
     - Se lo zip supera il limite di caricamento dell'hosting (indicato nella pagina), caricalo via FTP in `wp-content/uploads/fma-contenuti.zip` e scegli «File già sul server». A importazione finita il file viene cancellato.
     - «Configura anche il sito» crea le pagine Home e Blog, il menu, i permalink e il logo, e rimuove i contenuti di esempio di WordPress. È già spuntato su un sito nuovo; lascialo spento se il sito è già avviato.
   - **Con WP-CLI** (via SSH): scompatta `fma-contenuti.zip` nella cartella di WordPress e lancia
     ```bash
     wp fma importa fma-contenuti/data.json --immagini=fma-contenuti/img --configura-sito
     ```
   - **A mano**: in **Strutture → Aggiungi nuova** compila i box *Scheda della struttura*, *Contatti e richieste di soggiorno*, *Camere*, *Galleria foto*, *Nei dintorni e “Da sapere”* e *Home page*. Assegna regione e servizi e imposta l'immagine in evidenza.
5. **Aspetto → Personalizza → Home page**: testi di hero, chi siamo, sezione strutture e blog, più la foto del «Chi siamo».
6. **Email**: installa un plugin SMTP autenticato (per esempio WP Mail SMTP), altrimenti le richieste rischiano di finire in spam.

Ogni struttura deve avere l'**Email per le richieste di soggiorno** (box *Contatti e richieste di soggiorno*). Se manca, l'elenco Strutture scrive «Manca: le richieste non partono» e la schermata di modifica mostra un avviso.

## Dove si modifica cosa

| Nel sito | In amministrazione |
|---|---|
| Slider della home | Strutture con «Mostra nello slider della home» (box *Home page*), in ordine di *Attributi → Ordine*. Il titolo in sovraimpressione è il campo «Titolo nello slider della home». |
| Ricerca, mappa ed elenco | Tutte le strutture pubblicate con latitudine e longitudine. I filtri sono le regioni. |
| Loghi che scorrono | **Partner**: titolo, logo (immagine in evidenza) e link. |
| Chi siamo, testata, piè di pagina | **Personalizza → Home page**. Logo: **Personalizza → Identità del sito**. |
| Pagina della struttura | Riassunto = introduzione; il testo dell'editor diventa una sezione per ogni titolo H2. Camere, galleria, dintorni, regole, contatti e prezzo sono nei box sotto l'editor. |
| Prezzo e tassa di soggiorno (scheda contatti della casa) | Box *Scheda della struttura*: «Prezzo minimo a notte», «Il prezzo è» (a persona o a camera) e «Tassa di soggiorno» (testo libero, con importo ed esenzioni). Compaiono solo se compilati. |
| Box «Dormi vicino» negli articoli | Box *Casa collegata* nella modifica dell'articolo. |
| «Come si prenota» (3 passi) in home | **Personalizza → Home page**, campi «Passo 1/2/3» (titolo, testo e foto; senza foto scelta si usa una foto delle case). |
| Recensioni («Dicono di noi» in home, «Cosa dicono gli ospiti» nella pagina della casa) | **Strutture → Tutte le recensioni**: nome dell'ospite, testo, casa, fonte, voto, periodo e link all'originale. Solo recensioni reali. Senza recensioni pubblicate le sezioni non compaiono. |
| Barra in basso su telefono (home) | Compare scorrendo oltre l'hero e porta all'elenco delle case. Non ci sono numeri di telefono centrali: ogni ospite scrive direttamente alla casa. |
| Menu | **Aspetto → Menu**, posizioni «Menu principale» e «Menu a piè di pagina». |
| Anteprima dei link condivisi (WhatsApp, Facebook, LinkedIn) | Strutture e articoli usano l'immagine in evidenza; home e altre pagine la foto di **Personalizza → Condivisione sui social** (se vuota, la prima casa dello slider). I tag si disattivano da soli con Yoast, Rank Math, AIOSEO, SEOPress o The SEO Framework. |
| Informativa privacy (link nel modulo e nel piè di pagina) | **Impostazioni → Privacy**. Se manca, la bacheca mostra un avviso con il pulsante «Crea l’informativa»: crea la pagina in bozza con un testo già scritto per questo sito; vanno completati i punti tra parentesi quadre «[da completare…]» prima di pubblicarla. Finché manca, il modulo non mostra link vuoti. |
| Titolo e descrizione su Google (case) | Box *Su Google e nelle condivisioni*: «Titolo per Google» (nome, tipo di casa e località) e «Descrizione per Google» (120–155 caratteri, frasi intere). Le otto case FMA li hanno già compilati; se vuoti, il titolo si compone da solo e la descrizione è l’inizio del riassunto, tagliata a fine frase. |
| Titolo e descrizione su Google (home) | **Personalizza → Google e condivisione**. Il titolo è quello che si cerca («Case per ferie religiose in Italia»), non lo slogan. Archivio e regioni hanno titoli automatici («Case per ferie in Campania»). |
| Testo alternativo delle foto | **Media → Libreria**, campo «Testo alternativo» di ogni foto. Le foto delle otto case ne hanno già uno che descrive cosa mostrano. |
| Eventi di Google Analytics | Nessuna impostazione nel sito: nella pagina di una casa il tema invia `richiesta_iniziata` (inizio compilazione) e `generate_lead` (richiesta partita davvero), e aggiunge il parametro `casa` a tutti gli eventi della pagina (nella configurazione del tag di Site Kit, filtro `googlesitekit_gtag_opt`). I clic su email, telefono e WhatsApp li registra Site Kit (`contact_link_click`, parametro `link_type`). Partono solo se il tag di Google è presente, cioè con Site Kit attivo e il consenso dato. In Analytics: dimensioni personalizzate `casa` e `tipo_soggiorno`, evento chiave `generate_lead`. |
| Icona nella scheda del browser | **Personalizza → Identità del sito → Icona del sito** (PNG quadrato di almeno 512 px). |

## Lingue (Polylang)

Il sito è in italiano; inglese, francese, spagnolo, portoghese e cinese si aggiungono con **Polylang** (versione gratuita).

1. **Lingue → Lingue**: aggiungi English (en_GB), Français (fr_FR), Español (es_ES), Português (pt_PT) e 中文 (zh_CN). L'italiano resta la lingua predefinita.
2. **Lingue → Impostazioni → Modifiche URL**: spunta «L’URL della pagina iniziale contiene il codice della lingua…», così la home inglese è `/en/` e non `/en/home-en/`.
3. **Strumenti → Traduzioni FMA**: scegli le lingue e premi *Importa le traduzioni*. Crea, collegate a quelle italiane, le 8 case (testi, camere, regole, dintorni, titolo e descrizione per Google), i 21 articoli, le pagine Home e Blog, le categorie, le regioni, i servizi e il motto del sito. Foto, coordinate, contatti, email per le richieste e prezzi si copiano dalla versione italiana. Con WP-CLI: `wp fma traduzioni [--lingua=en]`.

| Cosa | Dove |
|---|---|
| Testi delle traduzioni importate | `fma-strutture/traduzioni/<lingua>.json`. L'importazione si può rilanciare: aggiorna le traduzioni con questi file, quindi **le correzioni fatte a mano in amministrazione vengono sovrascritte**. Dopo la prima importazione conviene correggere direttamente in WordPress e non rilanciarla. |
| Testi del tema e del modulo (pulsanti, etichette, messaggi, email all'ospite) | `accoglienza-fma/languages/<locale>.po` e `fma-richieste/languages/fma-richieste-<locale>.po` (con `.mo` e `.l10n.php`). I testi predefiniti della home in **Personalizza** sono già tradotti; se li cambi, la versione nelle altre lingue si scrive in **Lingue → Traduzioni stringhe**, gruppo «Accoglienza FMA». |
| Selettore della lingua | In testata accanto al pulsante «Richiedi un soggiorno» e nel menu su telefono. Compare solo con Polylang attivo. |
| Email alla casa | Restano in italiano, con la riga «Lingua dell’ospite: …». La copia all'ospite è nella sua lingua. |
| Informativa privacy e cookie | Non tradotte: finché manca la traduzione, link e consenso del modulo rimandano alla pagina italiana. |
| Nuova casa o nuovo articolo | Si scrive in italiano; la traduzione si crea dal «+» di Polylang nella colonna della lingua. |

## Scelte tecniche

- **Niente risorse esterne**: font (Fraunces, Geist), GSAP e Leaflet sono nel tema, quindi niente Google Fonts né CDN (GDPR). Le tessere della mappa arrivano da OpenStreetMap.
- **Script con `defer`** e nessuna dipendenza da jQuery nel sito pubblico. Il tema regge l'ottimizzazione degli script di WP-Optimize, che oggi rompe hero e galleria del sito attuale.
- **Home leggera**: lo slider carica subito solo la prima foto e prepara la successiva; le altre arrivano quando stanno per comparire. Le foto hanno più misure (16:10 da 800, 1200 e 1920 px; miniature da 400 px) e ogni immagine dichiara la sua larghezza a schermo, così il browser scarica la misura giusta. Su un sito già popolato, dopo l'aggiornamento del tema rigenera le miniature (`wp media regenerate --only-missing` oppure il plugin *Regenerate Thumbnails*).
- **Aggiornamenti senza cache vecchia**: CSS e JS del tema hanno nell'indirizzo la data di modifica del file, quindi dopo un aggiornamento browser e cache prendono subito i file nuovi. Con WP-Optimize o un'altra cache di pagina, svuotala dopo aver aggiornato il tema.
- **Cache svuotata dopo gli aggiornamenti**: quando cambia uno dei file di tema o plugin FMA (nuovo zip caricato), alla prima pagina non in cache — per esempio la bacheca — `fma-strutture` svuota la cache di WP-Optimize (e di WP Rocket, W3 Total Cache, WP Super Cache, LiteSpeed se presenti).
- **robots.txt pulito**: un solo gruppo per User-agent anche quando un plugin aggiunge le sue regole; `/sitemap_index.xml` porta a `/wp-sitemap.xml`.
- **Aggiornamenti dei contenuti senza sovrascrivere**: descrizioni per Google, testi alternativi e i testi riscritti di Caorle e Mazzarello (prima uguali ai siti delle case) arrivano con l’aggiornamento del plugin, una volta sola e solo dove il campo è vuoto o ancora quello importato.
- **Nome di accesso non visibile**: niente pagine autore (portano alla home), niente mappa del sito degli utenti, niente autore nei dati di incorporamento e nei feed, elenco utenti delle API solo per chi è collegato.
- **Leaflet solo dove c'è una mappa**: home, archivio, regioni, singola struttura.
- **Nessun nonce nel modulo di richiesta**, perché le pagine sono in cache. L'antispam usa honeypot, tempo minimo e limite per IP (dettagli in `../README.md`).
- **Importazione dal browser senza rischi**: la pagina è riservata agli amministratori e ogni passo è protetto da nonce. Dallo zip si estraggono solo `data.json` e le immagini, in una cartella temporanea che a fine lavoro viene cancellata: niente PHP in uploads, e i percorsi con `../` vengono scartati sia nello zip sia nel file dati.
- **Compatibile con il sito di oggi**: `fma-richieste` trova l'email anche nelle proprietà RealHomes (`property` + agente), quindi si può attivare prima della migrazione.

## Sito locale di prova

```bash
./dev/setup.sh                                               # WordPress + SQLite in dev/wp, contenuti importati
SENZA_CONTENUTI=1 ./dev/setup.sh                             # oppure vuoto, per provare la pagina di importazione
php -S 127.0.0.1:8890 -t dev/wp/wordpress dev/router.php     # http://127.0.0.1:8890 — admin / admin
```

Nel sito locale nessuna email parte davvero: il mu-plugin `dev/fma-dev-mail.php` le salva in `dev/wp/wordpress/wp-content/mail-log.json`.

Test del modulo di richiesta: `plugins/fma-richieste/tests/` (26 controlli, istruzioni in `../README.md`).
