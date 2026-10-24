# Traduzioni del tema

`functions.php` dichiara il dominio `experiences-srl` e lo carica da questa
cartella con `load_theme_textdomain()`.

## Convenzione dei nomi

Per un **tema** WordPress cerca `languages/{locale}.mo` — quindi `en_US.mo`,
`de_DE.mo`, `fr_FR.mo`. **Non** `experiences-srl-en_US.mo`: quella è la
convenzione dei *plugin* (`load_plugin_textdomain`) e qui non funzionerebbe.
Fino alla v2.17.0 questo README diceva il contrario ed era sbagliato.

## Cosa c'è

| File | Cosa contiene |
|---|---|
| `experiences-srl.pot` | Il modello, con tutte le stringhe e il punto del codice da cui arrivano. Non si traduce: si copia. |
| `en_US.po` | La traduzione inglese, leggibile e modificabile. |
| `en_US.mo` | La stessa, compilata. È il file che WordPress legge davvero. |

Dalla v2.17.0 **tutte le stringhe visibili del sito** sono traducibili, non solo
quelle della bacheca: homepage, intestazione, piede di pagina, articoli,
configuratore, banner cookie e modale di prenotazione.

## Rigenerare il modello

Dopo aver toccato un template:

```bash
python3 scripts/make-pot.py
```

Legge `__()`, `_e()`, `esc_html__()`, `esc_attr__()`, `esc_html_e()` e
`esc_attr_e()` con dominio `experiences-srl` e riscrive il `.pot`.

## Aggiungere o correggere una traduzione

Con [Poedit](https://poedit.net/) (gratuito): si apre il `.po`, si traduce, si
salva — Poedit scrive il `.mo` da solo.

Senza Poedit:

```bash
python3 scripts/make-pot.py        # aggiorna il modello dal codice
python3 scripts/sync-po.py         # allinea en_US.po e compila en_US.mo
```

`sync-po.py` rimette le voci del `.po` nell'ordine del `.pot` con i riferimenti
aggiornati, aggiunge le stringhe nuove con `msgstr ""`, toglie quelle che nel
tema non esistono più e **non tocca le traduzioni già fatte**: il `.po` è la
sorgente, non un file generato. Se resta anche un solo `msgstr` vuoto elenca
quali, esce con stato 1 e lascia stare il `.mo` — così non si pubblica mai un
`.mo` con dei buchi dentro. Riempiti i buchi nel `.po`, si rilancia.

Per un'altra lingua: `python3 scripts/sync-po.py de_DE`.

Il `.mo` lo scrive lo script da sé, senza `msgfmt`, che non è detto sia
installato.

## Stringhe con markup dentro

Alcune frasi contengono tag in linea, per esempio:

```
Aumenta le tue <span class="text-gradient">prenotazioni</span>, …
```

Il markup va **ricopiato identico** nella traduzione: cambiano solo le parole.
Spezzare la frase in pezzi separati darebbe una traduzione sgrammaticata, ed è
il motivo per cui queste frasi non sono state divise.

Le icone decorative (`<i class="fas fa-check"></i>`) invece restano **fuori**
dalle stringhe: così un cambio di icona non invalida la traduzione.

## Due lingue insieme (Polylang)

Il tema da solo mostra **una** lingua, quella del locale di WordPress. Per
avere italiano e inglese contemporaneamente serve Polylang, che:

- assegna il locale per lingua, quindi `__()` pesca dal `.mo` giusto;
- gestisce gli URL (`/` italiano, `/en/` inglese) e i tag `hreflang`;
- collega ogni articolo e pagina alla sua traduzione.

Il selettore IT|EN nell'intestazione lo stampa `experiences_lingue()`
(`functions.php`), che legge `pll_the_languages()`. Senza Polylang la funzione
torna un array vuoto e il selettore non compare: il sito resta identico a prima.
