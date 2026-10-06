# Audit dei repo di skill valutati

Analisi statica di 12 repository (settembre 2026), prima di installarne qualcosa.
Riprodurre l'audit: clonare i repo e rieseguire i controlli elencati sotto.

## 1. Sicurezza — nessun malware

~2.300 file esaminati. Controlli eseguiti ed esito:

| Controllo | Esito |
|---|---|
| `curl`/`wget` in pipe a shell | 1 occorrenza, dentro un esempio di *cattiva documentazione* in un checklist di audit — innocua |
| base64 / `atob` / `b64decode` | solo usi legittimi (fixture di test, certificati CI Apple, calcolo dimensioni buffer) |
| `eval` / `exec` / `child_process` su input esterno | nessuno |
| accesso a `~/.ssh`, `~/.aws`, keychain, `.npmrc` | nessuno |
| host di rete nel codice eseguibile | solo Vercel, GitHub, Apple, kie.ai — nessun host anonimo, IP hardcoded o webhook |
| script `preinstall` / `postinstall` | **nessuno** |
| binari ELF / Mach-O / PE | **zero** |
| unicode invisibile o bidi (injection nascosta) | **zero** |
| frasi di prompt injection | **zero** |
| `rm -rf ~`, `sudo`, scritture in `.bashrc` / `crontab` | nessuno |
| contenuto dei 6 `.zip` | solo `.md` e `.sh` già presenti in chiaro |
| hook che eseguono comandi | 1 solo (`bencium/emotion-statusline`, escluso) |

## 2. Rilievi non-malware, ma rilevanti

1. **`bencium/emotion-statusline`** — hook `Stop` che a ogni turno legge l'intero
   transcript, ne estrae comandi Bash e file toccati e li manda a un modello. Resta
   dentro l'account Anthropic, ma consuma token a ogni turno e scrive un log persistente
   con `cwd` e `session_id` in `~/.claude/cache/`. Solo macOS. **Escluso.**
2. **`scroll-craft` → kie.ai** — `kie.mjs` risale fino a 8 cartelle sopra il cwd
   cercando un `.env` con `KIE_AI_API_KEY`, e carica immagini locali su
   `kieai.redpandaai.co`. Documentato e opzionale, ma è un servizio a pagamento di terzi.
3. **`deploy-to-vercel`** — impacchetta il progetto e lo carica su
   `claude-skills-deploy.vercel.com`. Per design, ma è codice sorgente che esce. **Non installata.**
4. **`web-design-guidelines` / `writing-guidelines` (Vercel)** — non contengono regole:
   le scaricano live da `raw.githubusercontent.com` a ogni esecuzione. Dipendenza di rete
   e superficie supply-chain.
5. **`frontend-design-pro-demo`** — il `SKILL.md` dichiara `license: Complete terms in
   LICENSE.txt` ma **nel repo non esiste alcun LICENSE**: senza licenza, tutti i diritti
   riservati. Usato solo come galleria visiva locale, mai ridistribuito.
6. **`interface-design`** — ha committato `website/.vercel/project.json` con projectId e
   orgId dell'autore. Innocuo per noi.
7. **`npx skills add … --skill figma` non funziona**: in `anthropics/skills` non esiste
   nessuna skill `figma`. La skill Figma arriva dal server MCP Figma. Nello stesso repo
   c'è invece `frontend-design`, che è quella utile.

## 3. Licenze

Determina cosa si può copiare dentro questo repo e cosa no.

| Repo | Licenza | Vendorizzabile |
|---|---|---|
| `anthropics/skills` | Apache-2.0 | ✅ |
| `wondelai/skills` | MIT | ✅ |
| `bencium/bencium-marketplace` | MIT | ✅ |
| `Dammyjay93/interface-design` | MIT | ✅ |
| `nateherkai/scroll-craft` | MIT | ✅ |
| `rshankras/claude-code-apple-skills` | MIT | ✅ (tenuto come riferimento) |
| `rohitg00/awesome-claude-design` | MIT | ✅ (tenuto come riferimento) |
| `img2threejs/img2threejs` | Apache-2.0 | ✅ (non installato) |
| `Owl-Listener/designer-skills` | MIT | ✅ (escluso per qualità) |
| **`vercel-labs/agent-skills`** | **nessuna** | ❌ → livello utente |
| **`LovroPodobnik/refactoring-ui-skill`** | **nessuna** | ❌ |
| **`claudekit/frontend-design-pro-demo`** | **nessuna** | ❌ → sola consultazione |

Nota: `anthropics/skills` (Apache-2.0) è un repo diverso da `anthropics/claude-code`
(proprietario). `frontend-design` sta nel primo, quindi **è** copiabile — al contrario di
quanto diceva la versione precedente di `.claude/skills/README.md`.

## 4. Costo in contesto — misurato

La `description` di ogni skill installata entra nel contesto a ogni sessione, invocata o no.

**Se si installasse tutto (Tier A + B + riferimenti come skill):**

| Repo | #skill | ~token/sessione |
|---|---:|---:|
| claude-code-apple-skills | 183 | 15.800 |
| wondelai/skills (intero) | 65 | 13.100 |
| anthropics/skills (intero) | 20 | 2.600 |
| bencium-marketplace (intero) | 16 | 2.000 |
| vercel-labs/agent-skills | 9 | 840 |
| altri | 5 | 650 |
| **totale** | **298** | **~35.000** |

**Con l'installazione selettiva di questo repo (21 skill, incluse le 7 preesistenti):**

```
~2.700 token/sessione
```

Risparmio: **~32.000 token fissi per sessione**, senza perdere nessuna capacità —
il materiale escluso è o fuori tema (iOS, strategia d'impresa) o tenuto come
riferimento su disco, a costo zero finché non lo si apre.

Costo del **corpo** delle skill, pagato solo all'invocazione (`SKILL.md` da solo /
con tutto l'albero `references/`):

| Skill | SKILL.md | + references |
|---|---:|---:|
| `react-best-practices` (73 regole) | 1.900 | 60.400 |
| `scroll-craft` | 6.500 | 57.500 |
| `vercel-optimize` | 4.600 | 50.700 |
| `high-perf-browser` | 4.200 | 25.600 |
| `interface-design` | 7.800 | 7.800 |
