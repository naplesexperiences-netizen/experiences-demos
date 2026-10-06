# Accoglienza FMA — demo di template WordPress

Demo statica del nuovo template per accoglienzafma.com (case per ferie delle Figlie di Maria Ausiliatrice).
Contenuti reali presi dall'API REST del sito attuale il 29/09/2026: 8 strutture, 8 referenti, 4 partner, 5 articoli.

Rigenerare le pagine dopo aver modificato dati o template:

```bash
python3 demos/accoglienza-fma-template/_src/build.py
```

## Pagina demo → template WordPress

| Demo | Template WP | Note |
|---|---|---|
| `index.html` | `front-page.php` | hero slider (strutture con "in slider"), ricerca, partner, chi siamo, mappa + elenco, ultimi 3 articoli |
| `strutture/<slug>/` | `single-struttura.php` | CPT `struttura` gestito dal plugin |
| `blog/` | `home.php` | pagina articoli |
| `blog/<slug>/` | `single.php` | con box "Dormi vicino" → struttura collegata |

## Campi della struttura (CPT del plugin)

I campi sono le chiavi di `_src/data.json` → `strutture[]`. Quelli marcati *opz.* possono restare vuoti: il template ha uno stato vuoto.

- `nome`, `titolo_hero` *opz.* (titolo in sovraimpressione nello slider; se vuoto si usa il nome), `tipo`
- `localita`, `provincia`, `regione`, `indirizzo`, `lat`, `lng`
- `intro` *opz.*, `sezioni[]` *opz.* (titolo + testo)
- `servizi[]` (tassonomia), `camere[]` *opz.* (nome, dettaglio, letti, ospiti, quante, foto), `camere_totali` *opz.*
- `prezzo_da` *opz.*, `tassa` *opz.*, `orari` *opz.*, `dintorni[]` *opz.*, `regole[]` *opz.*
- `gallery[]`, foto hero, `booking_url` *opz.*
- `contatti`: logo, email, telefono, WhatsApp, sito (oggi sono il CPT "agente" di RealHomes)

## Modulo di richiesta → email della struttura

Nella demo statica l'invio è simulato: nessun messaggio parte.

Nel sito WordPress lo gestisce il plugin `wordpress/fma-richieste/`. Nel template della struttura basta `<?php fma_richieste_form( get_the_ID() ); ?>`, che usa lo stesso markup della demo, con stessi CSS e JS.

- **Destinatario**: sempre ricavato lato server dalla struttura. Prima il meta `fma_email` della struttura, poi l'email del referente RealHomes collegato (`REAL_HOMES_agents` → `REAL_HOMES_agent_email`), cioè i dati del sito di oggi. Il modulo non trasporta indirizzi, quindi non può scrivere a destinatari arbitrari.
- **Email**: alla struttura con `Reply-To` dell'ospite (la casa risponde direttamente) e copia all'ospite con `Reply-To` della casa. Se l'invio fallisce, il visitatore vede l'email della casa a cui scrivere.
- **Antispam senza nonce**: honeypot, tempo minimo di compilazione e 5 richieste ogni 15 minuti per IP. Un nonce scaduto nelle pagine in cache (WP-Optimize) farebbe perdere richieste.
- **Funziona anche senza JavaScript**: POST classico e ritorno alla pagina con `?richiesta=inviata`.
- **Filtri**: `fma_richieste_destinatario`, `fma_richieste_intestazioni`, `fma_richieste_camere`, `fma_richieste_tipi_struttura`; azione `fma_richieste_inviata`.

Test su un WordPress vero, usa-e-getta, con SQLite:

```bash
cd demos/accoglienza-fma-template/wordpress/fma-richieste
bash tests/setup.sh
php -S 127.0.0.1:8899 tests/router.php &
python3 tests/test_richieste.py      # 26 controlli
```

Le email vengono intercettate (`wp-content/mail-log.json`) e non partono. In produzione serve un SMTP autenticato (per esempio WP Mail SMTP), altrimenti `wp_mail` rischia di finire in spam.
