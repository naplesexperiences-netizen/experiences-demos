# Project skills

Skill Claude Code a livello di progetto: valgono solo in questo repo, non altrove.
Tutto quello che lavora su `demos/` (landing page statiche HTML/CSS/JS) le vede.

**Non modificare a mano queste cartelle.** Sono copie vendored: il prossimo
`./tooling/skills/install-skills.sh` le sovrascrive. Ogni skill ha il suo
`PROVENANCE.md` con sorgente, commit e licenza. Per aggiungerne o toglierne una si
edita `tooling/skills/skills.manifest`, non si copiano cartelle.

Quale skill usare in quale situazione: `CLAUDE.md` (tabella dei profili) e
`tooling/skills/PROFILES.md` (collisioni di trigger).

## GSAP (animazione)

`gsap-core`, `gsap-timeline`, `gsap-scrolltrigger`, `gsap-plugins`, `gsap-utils`,
`gsap-performance`, dal repo ufficiale GreenSock.

- Sorgente: https://github.com/greensock/gsap-skills
- Licenza: MIT, © GreenSock

Coprono entrance animation, scroll reveal, parallasse, sezioni pinnate, SVG draw/morph
e performance sulle pagine statiche in `demos/`. `gsap-react` e `gsap-frameworks` sono
stati saltati: qui non si usa nessun framework JS.

⚠️ Non mescolare con `scroll-craft`: ha un motore di scroll proprio e i due si
contendono lo stesso evento. Vedi `tooling/skills/PROFILES.md`.

## Design e UX (vendored, licenza aperta)

| Skill | Sorgente | Licenza |
|---|---|---|
| `frontend-design`, `theme-factory` | anthropics/skills | Apache-2.0 |
| `high-perf-browser`, `web-typography`, `refactoring-ui`, `top-design`, `microinteractions`, `ux-heuristics`, `design-everyday-things`, `cro-methodology` | wondelai/skills | MIT |
| `ui-typography`, `design-audit`, `bencium-aeo` | bencium/bencium-marketplace | MIT |
| `interface-design` | Dammyjay93/interface-design | MIT |
| `hallmark` | — | vedi `PROVENANCE.md` |

Esito dell'audit di sicurezza e licenze dei repo di provenienza: `tooling/skills/AUDIT.md`.

## Non vendored, di proposito

**Skill Vercel** (`react-best-practices`, `vercel-optimize`, `react-view-transitions`,
`composition-patterns`): `vercel-labs/agent-skills` **non ha alcun LICENSE**, quindi
copiarne il testo qui non sarebbe una redistribuzione permessa. Si installano a livello
utente, fuori dal repo:

```
./tooling/skills/install-skills.sh --vercel
```

**`scroll-craft`**: licenza MIT, quindi copiabile, ma pesante (213 KB di references) e
con dipendenze esterne (ffmpeg completo, `playwright-core`, Chrome vero). Fuori dal set
di default; si installa quando serve:

```
./tooling/skills/install-skills.sh scroll
```

## Correzione rispetto alla versione precedente di questo file

La nota che diceva di non poter vendorizzare `frontend-design` per licenza proprietaria
riguardava `anthropics/claude-code`. La stessa skill esiste in `anthropics/skills`, che
è **Apache-2.0**: è quella copiata qui, legittimamente.
