# 🚀 Deploy su GitHub

## Opzione 1: Usando Personal Access Token (Consigliato)

### Prerequisiti
- Account GitHub configurato
- Personal Access Token generato su GitHub (Settings → Developer settings → Personal access tokens)

### Passaggi

1. **Creare il repository su GitHub**
   - Vai su [GitHub](https://github.com/new)
   - Nome: `sportlandia-kiosk`
   - Descrizione: "Touch screen 42 inch interactive kiosk for Sportlandia fitness center"
   - Visibilità: Public (per portfolio)

2. **Aggiungi il remote repository**
   ```bash
   git remote add origin https://YOUR_TOKEN@github.com/experiences-srl/sportlandia-kiosk.git
   ```
   Sostituisci `YOUR_TOKEN` con il tuo Personal Access Token

3. **Fai il push**
   ```bash
   git push -u origin master
   ```

## Opzione 2: Usando SSH Key

### Prerequisiti
- SSH Key configurato su GitHub

### Passaggi

1. **Creare il repository su GitHub** (come sopra)

2. **Aggiungi il remote repository**
   ```bash
   git remote add origin git@github.com:experiences-srl/sportlandia-kiosk.git
   ```

3. **Fai il push**
   ```bash
   git push -u origin master
   ```

## Opzione 3: Usando GitHub CLI

```bash
# Login a GitHub
gh auth login

# Creare il repository
gh repo create experiences-srl/sportlandia-kiosk --public --source=. --remote=origin --push
```

---

**Dopo il push**, il sito sarà disponibile su:
https://github.com/experiences-srl/sportlandia-kiosk

Potrai anche abilitare **GitHub Pages** per visualizzare il sito live:
Settings → Pages → Source → Deploy from branch → Master → Save

Il sito sarà disponibile su:
https://experiences-srl.github.io/sportlandia-kiosk/
