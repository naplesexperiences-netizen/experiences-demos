# experiences-demos — istruzioni di progetto

Demo di landing page per clienti (Napoli / Salerno). Pagine statiche HTML/CSS/JS
in `demos/`, tema condiviso in `experiences-theme/`, hub generato da `scripts/`.

## Profili di lavoro

Le skill installate sono molte; caricarle tutte insieme fa partire quella sbagliata.
Quando l'utente nomina un profilo — **«profilo landing»**, **«profilo prodotto»**,
**«profilo perf»**, **«profilo scroll»** — usa solo le skill di quel profilo e
ignora le altre, anche se il trigger sembra combaciare.

| Profilo | Skill da usare | Riferimenti da aprire |
|---|---|---|
| **landing** — sito vetrina / brand | `hallmark`, `top-design`, `web-typography`, `ui-typography`, `microinteractions`, `frontend-design`, `gsap-scrolltrigger`, `gsap-performance` | `design-md/`, `style-gallery/` |
| **prodotto** — dashboard, booking, area riservata | `interface-design`, `ui-ux-pro-max`, `web-design-guidelines`, `ux-heuristics`, `design-everyday-things`, `ui-typography`, `dataviz` | `apple-hig/` |
| **perf** — audit di un sito esistente | `high-perf-browser`, `web-design-guidelines`, `vercel-optimize`*, `react-best-practices`* | — |
| **scroll** — esperienza scroll-driven | `scroll-craft`†, `gsap-scrolltrigger`, `gsap-timeline`, `gsap-performance`, `top-design` | `design-md/cinematic/` |
| **conversione** — copy e funnel | `cro-methodology`, `bencium-aeo`, `ux-heuristics` | — |
| **audit visivo** — restyle di qualcosa che esiste | `design-audit`, `refactoring-ui`, `ui-typography`, `ux-heuristics` | `style-gallery/` |

\* livello utente, non in questo repo — `./tooling/skills/install-skills.sh --vercel`
† gruppo `scroll`, non installata di default — `./tooling/skills/install-skills.sh scroll`

Senza profilo indicato: scegli tu in base al compito, **dichiara quale profilo stai
usando nella prima risposta** e attieniti a quello. Se il compito non rientra in
nessun profilo, lavora senza skill di design.

Dettaglio dei profili, sovrapposizioni note e conflitti fra skill: `tooling/skills/PROFILES.md`.

## Riferimenti visivi

Non sono skill: sono cartelle da leggere su richiesta, clonate **fuori dal repo**
in `~/.naples-references/` (override con `NAPLES_REFS`). Non committarle.

| Percorso | Cosa contiene |
|---|---|
| `apple-hig/skills/design/` | HIG Apple: animation-patterns, liquid-glass, SF Symbols, tipografia |
| `design-md/design-md/` | 30+ `DESIGN.md` per famiglia estetica (editorial, cinematic, glass, brutalist, terminal…) |
| `style-gallery/demos-v02/` | 11 demo HTML + screenshot, una per stile visivo |

Non ci sono? `./tooling/skills/sync-references.sh`.

`design-md/` descrive estetiche di brand reali (Linear, Stripe, Ferrari, Arc):
serve da direzione artistica, **non** da clonare. `style-gallery/` è senza licenza:
consultazione locale, non copiare codice nei deliverable dei clienti.

## Skill

`.claude/skills/` è vendored e committato: solo skill con licenza aperta, ognuna con
il suo `PROVENANCE.md` (sorgente, commit, licenza). Non modificarle a mano — il
prossimo `install-skills.sh` sovrascrive. Per personalizzarne una, forkala con altro nome.

Aggiungere/togliere skill si fa in `tooling/skills/skills.manifest`, non copiando
cartelle a mano.
