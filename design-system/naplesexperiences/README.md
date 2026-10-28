# Design system — naplesexperiences.com

Token, componenti e livello correttivo per il tema WordPress `experiences-theme`
(repo `naplesexperiences-netizen/experiences-website`). Preparati qui perché il
repo del tema non era nella sessione: vanno copiati in `wp-content/themes/experiences-theme/design-system/`.

| File | Cosa fa |
|---|---|
| `tokens.css` | Unica fonte di verità: colori (scala teal + ruoli), tipo, spaziatura, raggi, ombre, motion, z-index |
| `components.css` | `exp-btn` (6 varianti, 3 taglie, stati disabled/loading/focus), `exp-icon-btn`, `exp-chip`, `exp-link`, `exp-card`, `exp-badge` |
| `compat.css` | Corregge i contrasti sulle utility e classi già presenti nei template, senza toccarli. È debito: si svuota man mano che si migra |
| `tailwind.preset.js` | Collega Tailwind ai token (`bg-accent/20` continua a funzionare) |
| `theme.json` | Sostituisce palette, gradienti, ombre e font-size di default di Gutenberg con quelli del brand |
| `enqueue.php` | Carica i tre CSS nell'ordine giusto (handle verificati sulla v2.19.0) |
| `preview.html` | Scheda dei componenti e tabella di migrazione delle classi |
| `check-contrast.js` | Misura il contrasto WCAG AA di ogni testo su una pagina reale, prima e dopo |

## La regola del teal

| Token | Hex | Uso |
|---|---|---|
| `teal-400` | #14a3a3 | Solo su sfondo scuro o decorativo. Su bianco fa 3.09:1 |
| `teal-600` | #0d7c7c | Fondo dei pulsanti con testo bianco (5.01:1) |
| `teal-700` | #0a6464 | Testo e link su chiaro (6.19:1 anche su mist), hover, superfici teal |
| `teal-300` | #4adede | Testo teal su night/ink |
| `teal-200` | #99ecec | Testo teal su superfici teal-700 |

Le superfici teal (`bg-secondary` usato come sezione, gradienti `to-secondary`,
`.hero-gradient`, `.pricing-card.featured`, header del chatbot) scendono da
#0d7c7c a #0a6464: il bianco attenuato fino a /82 resta leggibile.

## Risultato misurato sulla home live

```
node design-system/naplesexperiences/check-contrast.js            # com'è
node design-system/naplesexperiences/check-contrast.js --inject   # con tokens + components + compat
```

| | Desktop 1280px | Mobile 390px |
|---|---|---|
| Testi sotto AA, oggi | 79 su 342 | 77 su 326 |
| Con il design system | **0** | **0** |

Nessuno scroll orizzontale introdotto. Il checker salta testi su immagini e testo
sfumato (`background-clip: text`); per i gradienti usa il punto peggiore.

## Installazione nel tema

1. Copiare la cartella in `experiences-theme/design-system/` (senza `check-contrast.js` e `preview.html`, che restano strumenti).
2. In `functions.php`: `require get_template_directory() . '/design-system/enqueue.php';`
3. `theme.json` nella root del tema (se ne esiste già uno, unire `settings`).
4. `tailwind.config.js`: `presets: [require('./design-system/tailwind.preset.js')]`, poi ricompilare `tailwind.min.css`.
5. In `style.css`, sostituire i 69 hex scritti a mano con le variabili (`#14A3A3` → `var(--exp-teal-400)`, ecc.) e le 16 `transition` con `var(--exp-dur-*)`/`var(--exp-ease)`.
6. Lanciare `check-contrast.js` sulla pagina di staging senza `--inject`: deve dare 0.

## Migrazione

Template per template, sostituire le combinazioni di utility con i componenti
(tabella completa in `preview.html`), poi togliere da `compat.css` le regole che
non servono più. Ordine consigliato: pulsanti CTA → badge di sezione → chip del
chatbot e `verticale-btn` → card → controlli del carosello.

Il focus visibile (`compat.css` §7) e il minimo di 12px per il testo (§8) restano
anche dopo la migrazione: spostarli in `components.css` quando `compat.css` si svuota.
