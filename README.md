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

## Workflow

```
┌──────────────────┐    push    ┌──────────────┐  GitHub Actions  ┌──────────────────┐
│ branch feature   │ ─────────▶│   main       │ ───────────────▶ │ GitHub Pages     │
│ (claude/...)     │   merge    │ (default)    │  (auto deploy)   │ naplesexperien…  │
└──────────────────┘            └──────────────┘                  └──────────────────┘
```

1. **Sviluppo**: lavora sempre su un branch feature (`claude/<topic>` per
   sessioni Claude, o `feat/<demo>` per altri lavori).
2. **Merge su `main`**: il workflow `.github/workflows/pages.yml` rigenera
   l'`index.html` di root e fa il deploy su GitHub Pages.
3. **Live URL**: il demo è disponibile a
   `https://naplesexperiences-netizen.github.io/experiences-demos/demos/<slug>/`.

## Aggiungere un nuovo demo

```bash
# 1. nuovo branch
git checkout -b feat/<nome-demo>

# 2. nuova cartella con il sito
mkdir -p demos/<nome-demo>
# ... scrivi index.html con <title> e <meta description>

# 3. (opzionale) rigenera l'hub localmente per verificare
python3 scripts/generate-hub.py

# 4. commit + push + merge su main
git add demos/<nome-demo> index.html
git commit -m "demo: <nome-demo>"
git push -u origin feat/<nome-demo>
# poi merge su main → deploy automatico
```

## Hub auto-generato

`scripts/generate-hub.py` scansiona `demos/`, estrae `<title>` e
`<meta description>` di ciascun `index.html`, e scrive un `index.html`
nella root del repo. Lo script viene eseguito anche dal workflow di deploy,
quindi non è necessario committare la versione locale (ma puoi farlo per
avere il preview aggiornato anche sui branch).

## Configurazione GitHub Pages

- **Settings → Pages → Source**: `GitHub Actions`
- **Workflow**: `.github/workflows/pages.yml` (push su `main` + dispatch manuale)
