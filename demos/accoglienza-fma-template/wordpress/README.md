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
4. Contenuti, con uno di questi due modi:
   - **A mano**: in **Strutture → Aggiungi nuova** compila i box *Scheda della struttura*, *Contatti e richieste di soggiorno*, *Camere*, *Galleria foto*, *Nei dintorni e “Da sapere”* e *Home page*. Assegna regione e servizi e imposta l'immagine in evidenza.
   - **Con l'importatore** (serve WP-CLI, disponibile su quasi tutti gli hosting con SSH): scompatta `fma-contenuti.zip` sul server e lancia
     ```bash
     wp fma importa fma-contenuti/data.json --immagini=fma-contenuti/img --configura-sito
     ```
     Importa 8 strutture, 6 partner e 5 articoli con tutte le foto. `--configura-sito` crea le pagine Home e Blog, il menu e il logo, e rimuove i contenuti di esempio di WordPress. Si può rilanciare: aggiorna, non duplica.
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
| Box «Dormi vicino» negli articoli | Box *Casa collegata* nella modifica dell'articolo. |
| Menu | **Aspetto → Menu**, posizioni «Menu principale» e «Menu a piè di pagina». |

## Scelte tecniche

- **Niente risorse esterne**: font (Fraunces, Geist), GSAP e Leaflet sono nel tema, quindi niente Google Fonts né CDN (GDPR). Le tessere della mappa arrivano da OpenStreetMap.
- **Script con `defer`** e nessuna dipendenza da jQuery nel sito pubblico. Il tema regge l'ottimizzazione degli script di WP-Optimize, che oggi rompe hero e galleria del sito attuale.
- **Leaflet solo dove c'è una mappa**: home, archivio, regioni, singola struttura.
- **Nessun nonce nel modulo di richiesta**, perché le pagine sono in cache. L'antispam usa honeypot, tempo minimo e limite per IP (dettagli in `../README.md`).
- **Compatibile con il sito di oggi**: `fma-richieste` trova l'email anche nelle proprietà RealHomes (`property` + agente), quindi si può attivare prima della migrazione.

## Sito locale di prova

```bash
./dev/setup.sh                                               # WordPress + SQLite in dev/wp, contenuti importati
php -S 127.0.0.1:8890 -t dev/wp/wordpress dev/router.php     # http://127.0.0.1:8890 — admin / admin
```

Nel sito locale nessuna email parte davvero: il mu-plugin `dev/fma-dev-mail.php` le salva in `dev/wp/wordpress/wp-content/mail-log.json`.

Test del modulo di richiesta: `plugins/fma-richieste/tests/` (26 controlli, istruzioni in `../README.md`).
