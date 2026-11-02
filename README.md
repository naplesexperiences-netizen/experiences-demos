# experiences-demos

Raccolta di demo, landing page e prototipi realizzati da **experiences SRL**.

🔗 **Hub live**: https://naplesexperiences-netizen.github.io/experiences-demos/

## Convenzione

Ogni demo vive in una cartella sotto `demos/`:

```
demos/
└── <slug-demo>/
    ├── index.html          # entry point
    ├── images/             # asset locali (opzionale)
    └── ...
```

Il file `index.html` deve avere un `<title>` significativo e (consigliato) un
`<meta name="description">`: lo script `generate-hub.py` li usa per popolare
la card del demo nell'hub di root.

### Tag e categorie

Aggiungi metadati al `<head>` del demo per farlo apparire nei filtri dell'hub:

```html
<meta name="demo:tags" content="hotel,sorrento,luxury,beachfront">
<meta name="demo:category" content="hotel">
```

I tag (lista separata da virgole) compaiono come chip filtrabili nell'hub
e nelle card. La `category` è la classificazione primaria. Sono entrambi
opzionali ma fortemente consigliati per tenere navigabile la collezione.

## Workflow

```
┌──────────────────┐    push    ┌──────────────┐  GitHub Actions  ┌──────────────────┐
│ branch feature   │ ─────────▶│   main       │ ───────────────▶ │ GitHub Pages     │
│ (claude/...)     │   merge    │ (default)    │  (auto deploy)   │ naplesexperien…  │
└──────────────────┘            └──────────────┘                  └──────────────────┘
```

1. **Sviluppo**: lavora sempre su un branch feature (`claude/<topic>` per
   sessioni Claude, o `feat/<demo>` per altri lavori).
2. **Merge su `main`**: GitHub Pages pubblica `main` così com'è (deploy dal
   branch, «pages build and deployment»), in meno di un minuto.
3. **Live URL**: il demo è disponibile a
   `https://naplesexperiences-netizen.github.io/experiences-demos/demos/<slug>/`.

## Aggiungere un nuovo demo

```bash
# 1. nuovo branch
git checkout -b feat/<nome-demo>

# 2. nuova cartella con il sito
mkdir -p demos/<nome-demo>
# ... scrivi index.html con <title> e <meta description>

# 3. commit + push + merge su main (non toccare index.html di root)
git add demos/<nome-demo>
git commit -m "demo: <nome-demo>"
git push -u origin feat/<nome-demo>
# poi merge su main → deploy automatico
```

## Hub auto-generato

> **Non lanciare `scripts/generate-hub.py` e non committare il suo output.**
> Dal 26 ottobre 2026 l'`index.html` di root rimanda a `vetrina/`; lo script
> lo sovrascriverebbe con l'elenco pubblico di tutte le demo e i nomi dei
> clienti. Resta nel repo solo come base per un hub futuro (vedi l'avvertenza
> in testa allo script). La CI lo esegue solo per verificare che non dia
> errori, senza pubblicare nulla.

## Configurazione GitHub Pages

- **Settings → Pages → Source**: `Deploy from a branch` → `main` / root
- Nessun workflow di deploy: il vecchio `pages.yml` (che rigenerava l'hub) è
  stato tolto il 2 novembre 2026. Non rimettere la fonte su `GitHub Actions`.

## CI (PR e push)

Il workflow `.github/workflows/ci.yml` gira su ogni PR verso `main` e su
ogni push su `main`. Tre job in parallelo:

| Job | Tool | Cosa controlla |
|---|---|---|
| `html-lint` | [HTMLHint](https://htmlhint.com/) (config in `.htmlhintrc`) | DOCTYPE, tag pair, attributi, id duplicati |
| `link-check` | [lychee](https://github.com/lycheeverse/lychee) (offline) | Link interni rotti tra demo e asset locali |
| `hub-script` | `scripts/generate-hub.py` | Lo script gira senza errori |

> Il link check è in modalità `--offline`: verifica solo path locali per
> evitare falsi positivi da siti esterni che bloccano i bot. Se vuoi
> validare anche gli URL esterni, rimuovi `--offline` dal workflow.
