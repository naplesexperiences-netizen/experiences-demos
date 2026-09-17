#!/usr/bin/env bash
#
# install-skills.sh — installazione SELETTIVA delle skill di design/performance web.
#
# Perche selettiva: la description di ogni skill installata entra nel contesto a
# OGNI sessione, che tu la usi o no. Installare i repo interi costerebbe ~35.000
# token fissi per sessione (di cui ~15.800 di skill iOS/App Store). Questo script
# installa solo le skill web, ~4.000 token.
#
# Le skill open (MIT / Apache-2.0) vengono copiate in .claude/skills/ e committate,
# seguendo la convenzione gia in uso per le skill GSAP di questo repo.
# Le skill Vercel NON sono copiabili (repo senza LICENSE): --vercel le installa a
# livello utente, fuori dal repo.
#
#   ./tooling/skills/install-skills.sh              # gruppo core (14 skill)
#   ./tooling/skills/install-skills.sh scroll       # core + scroll-craft
#   ./tooling/skills/install-skills.sh --vercel     # le 4 skill Vercel, a livello utente
#   ./tooling/skills/install-skills.sh --list       # mostra cosa verrebbe installato
#   ./tooling/skills/install-skills.sh --check      # verifica l'installato, non scrive nulla
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
MANIFEST="$ROOT/tooling/skills/skills.manifest"
DEST="$ROOT/.claude/skills"
CACHE="${TMPDIR:-/tmp}/naples-skills-cache"

WANTED="core"
MODE="install"

for a in "$@"; do
  case "$a" in
    --vercel) MODE="vercel" ;;
    --list)   MODE="list" ;;
    --check)  MODE="check" ;;
    -h|--help) sed -n '2,25p' "$0"; exit 0 ;;
    scroll)   WANTED="core scroll" ;;
    *) echo "argomento sconosciuto: $a (usa --help)" >&2; exit 2 ;;
  esac
done

# owner__repo: 'anthropics/skills' e 'wondelai/skills' hanno lo stesso basename,
# un slug col solo nome del repo li farebbe collidere nella stessa cartella di cache.
repo_slug() { echo "${1%.git}" | awk -F/ '{print $(NF-1) "__" $NF}'; }

in_groups() { for g in $WANTED; do [ "$g" = "$1" ] && return 0; done; return 1; }

# Righe del manifest, campi ripuliti dagli spazi.
rows() {
  grep -vE '^\s*(#|$)' "$MANIFEST" | while IFS='|' read -r name repo path lic grp; do
    printf '%s|%s|%s|%s|%s\n' \
      "$(echo "$name" | xargs)" "$(echo "$repo" | xargs)" \
      "$(echo "$path" | xargs)" "$(echo "$lic"  | xargs)" "$(echo "$grp" | xargs)"
  done
}

# ---------------------------------------------------------------- --vercel ----
# vercel-labs/agent-skills non ha LICENSE: niente copia nel repo. Livello utente.
if [ "$MODE" = "vercel" ]; then
  echo "Skill Vercel (livello utente, NON committate — il repo upstream non ha licenza):"
  echo
  for s in react-best-practices vercel-optimize react-view-transitions composition-patterns; do
    echo "  npx skills add https://github.com/vercel-labs/agent-skills --skill $s"
  done
  echo
  read -rp "Eseguo ora questi 4 comandi? [y/N] " ok
  [[ "${ok:-n}" =~ ^[Yy]$ ]] || { echo "Saltato. Copiali a mano quando vuoi."; exit 0; }
  for s in react-best-practices vercel-optimize react-view-transitions composition-patterns; do
    echo "--- $s"
    npx skills add https://github.com/vercel-labs/agent-skills --skill "$s" || \
      echo "  FALLITO: installa a mano, oppure clona il repo e copia skills/$s in ~/.claude/skills/"
  done
  exit 0
fi

# ----------------------------------------------------------------- --list ----
if [ "$MODE" = "list" ]; then
  printf '%-24s %-12s %-10s %s\n' SKILL GRUPPO LICENZA REPO
  rows | while IFS='|' read -r name repo path lic grp; do
    printf '%-24s %-12s %-10s %s\n' "$name" "$grp" "$lic" "${repo##*/}"
  done
  exit 0
fi

# ---------------------------------------------------------------- --check ----
if [ "$MODE" = "check" ]; then
  fail=0
  rows | while IFS='|' read -r name repo path lic grp; do
    in_groups "$grp" || continue
    if [ -f "$DEST/$name/SKILL.md" ]; then
      fm=$(awk '/^---$/{c++;next} c==1' "$DEST/$name/SKILL.md" | grep -m1 '^name:' | sed 's/^name:[[:space:]]*//' | tr -d '"'"'"' ')
      if [ "$fm" = "$name" ]; then echo "  ok        $name"
      else echo "  NOME!=DIR $name (frontmatter: '$fm') — Claude Code potrebbe non caricarla"; fi
    else
      echo "  MANCANTE  $name"; fail=1
    fi
  done
  exit 0
fi

# --------------------------------------------------------------- install ----
mkdir -p "$DEST" "$CACHE"

echo "Installo in: $DEST"
echo "Gruppi:      $WANTED"
echo

# Un clone per repo, riusato da tutte le sue skill.
rows | awk -F'|' '{print $2}' | sort -u | while read -r repo; do
  slug="$(repo_slug "$repo")"
  if [ -d "$CACHE/$slug/.git" ]; then
    echo "· aggiorno $slug"
    git -C "$CACHE/$slug" fetch --depth 1 origin HEAD -q && git -C "$CACHE/$slug" reset --hard FETCH_HEAD -q
  else
    echo "· clono    $slug"
    git clone --depth 1 -q "$repo" "$CACHE/$slug"
  fi
done

echo
installed=0
rows | while IFS='|' read -r name repo path lic grp; do
  in_groups "$grp" || continue
  slug="$(repo_slug "$repo")"
  src="$CACHE/$slug/$path"

  if [ ! -f "$src/SKILL.md" ]; then
    echo "  SALTATA  $name — $path non trovato in $slug (layout upstream cambiato?)" >&2
    continue
  fi

  sha="$(git -C "$CACHE/$slug" rev-parse --short HEAD)"
  rm -rf "${DEST:?}/$name"
  mkdir -p "$DEST/$name"
  cp -R "$src/." "$DEST/$name/"
  rm -rf "$DEST/$name/.git"

  # MIT e Apache-2.0 richiedono che il testo della licenza accompagni la copia.
  # Alcune skill (anthropics) hanno gia il proprio LICENSE.txt dentro la cartella;
  # per le altre la licenza sta solo nella root del repo upstream: la portiamo qui.
  if ! ls "$DEST/$name"/LICENSE* >/dev/null 2>&1; then
    root_lic="$(ls "$CACHE/$slug"/LICENSE* 2>/dev/null | head -1 || true)"
    if [ -n "$root_lic" ]; then
      cp "$root_lic" "$DEST/$name/LICENSE.upstream"
    else
      echo "  ATTENZIONE: $name — nessun LICENSE trovato upstream, non ridistribuire" >&2
    fi
  fi

  # Provenienza: da dove viene, con che licenza, a che commit. Serve per
  # riaggiornare la copia e per sapere cosa si puo ridistribuire.
  cat > "$DEST/$name/PROVENANCE.md" <<PROV
# Provenienza — $name

Copia vendored, non modificarla a mano: le modifiche andrebbero perse al prossimo
\`install-skills.sh\`. Per personalizzarla, forka la skill con un altro nome.

| | |
|---|---|
| Sorgente | $repo |
| Percorso | \`$path\` |
| Commit   | \`$sha\` |
| Licenza  | $lic |
| Copiata  | $(date -u +%Y-%m-%d) |

Per aggiornare: \`./tooling/skills/install-skills.sh$([ "$WANTED" = "core" ] || echo " scroll")\`
PROV

  size=$(du -sk "$DEST/$name" | cut -f1)
  printf '  ok  %-24s %5s KB  %-10s @%s\n' "$name" "$size" "$lic" "$sha"
done

echo
echo "Fatto. Verifica con:  ./tooling/skills/install-skills.sh --check"
echo "Skill Vercel (separate, livello utente):  ./tooling/skills/install-skills.sh --vercel"
