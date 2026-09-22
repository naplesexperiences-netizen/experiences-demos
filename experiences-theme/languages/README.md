# Traduzioni del tema

`functions.php` dichiara il dominio `experiences-srl` e lo carica da questa
cartella. Fino alla v2.6.0 la cartella non esisteva: `load_theme_textdomain()`
puntava nel vuoto e nessuna traduzione poteva essere caricata.

Le stringhe traducibili sono 32 e riguardano tutte la **bacheca di WordPress**
— etichette del Personalizzatore, nomi del custom post type Portfolio, avvisi.
I testi del sito pubblico stanno nei template e non passano da qui.

## Cosa c'è

- `experiences-srl.pot` — il modello, con tutte le stringhe e il punto del
  codice da cui arrivano. Non va tradotto: si copia.

## Tradurre in una lingua

1. Copia il `.pot` in `experiences-srl-CODICE.po` (es. `experiences-srl-en_US.po`
   per l'inglese, `experiences-srl-de_DE.po` per il tedesco).
2. Apri il `.po` con [Poedit](https://poedit.net/) — gratuito, Mac e Windows —
   e compila i campi `msgstr`.
3. Al salvataggio Poedit genera anche il `.mo` affiancato. Servono **entrambi**
   i file in questa cartella: WordPress legge il `.mo`.

Il codice lingua è quello impostato in *Impostazioni → Generali → Lingua del
sito*: se i due non coincidono la traduzione non viene caricata.

## Dopo aver modificato il codice

Se aggiungi o cambi stringhe dentro `__()` o `esc_html__()`, rigenera il
modello dalla radice del repository:

    python3 scripts/make-pot.py

Poi in Poedit usa *Catalogo → Aggiorna da POT* per riportare le novità nelle
traduzioni già fatte senza perdere il lavoro.
