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

## Modulo di richiesta

Nella demo l'invio è simulato (nessun messaggio parte). In WordPress: invio via `admin-post.php` con nonce, mail a `contatti.email` della struttura e copia all'ospite.
