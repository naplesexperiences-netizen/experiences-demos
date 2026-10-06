#!/usr/bin/env bash
#
# sync-references.sh — materiale di riferimento VISIVO, non skill.
#
# Questi tre repo servono da consultare, non da far scattare. Installarli come
# skill costerebbe ~16.000 token di description a ogni sessione (claude-code-apple-skills
# da solo sono 183 skill su SwiftUI / App Store Connect / TestFlight, mai invocate
# per un sito web). Clonati come cartelle normali costano 0 finche non si aprono.
#
# Vengono clonati FUORI dal repo: non sono nostri, non vanno committati, e uno dei
# tre non ha licenza. Default: ~/.naples-references (override con NAPLES_REFS).
#
#   ./tooling/skills/sync-references.sh          # clona / aggiorna
#   ./tooling/skills/sync-references.sh --where  # stampa solo i percorsi
#
set -euo pipefail

REFS="${NAPLES_REFS:-$HOME/.naples-references}"

# nome_locale | repo | sottocartella da tenere (sparse checkout) | nota
ENTRIES=(
  "apple-hig|https://github.com/rshankras/claude-code-apple-skills|skills/design|MIT — HIG, animation-patterns, liquid-glass, SF Symbols, typography"
  "design-md|https://github.com/rohitg00/awesome-claude-design|design-md|MIT — 30+ famiglie estetiche (editorial, cinematic, glass, brutalist...)"
  "style-gallery|https://github.com/claudekit/frontend-design-pro-demo|demos-v02|SENZA LICENZA — solo consultazione locale, non ridistribuire"
)

if [ "${1:-}" = "--where" ]; then
  echo "$REFS"
  for e in "${ENTRIES[@]}"; do IFS='|' read -r n _ sub _ <<<"$e"; echo "  $REFS/$n/$sub"; done
  exit 0
fi

mkdir -p "$REFS"
echo "Riferimenti in: $REFS"
echo

for e in "${ENTRIES[@]}"; do
  IFS='|' read -r name repo sub note <<<"$e"
  dir="$REFS/$name"

  if [ -d "$dir/.git" ]; then
    echo "· aggiorno $name"
    git -C "$dir" fetch --depth 1 origin HEAD -q
    git -C "$dir" reset --hard FETCH_HEAD -q
  else
    echo "· clono    $name"
    # blob:none + sparse: scarica solo la sottocartella che serve, non tutto il repo.
    git clone --depth 1 --filter=blob:none --sparse -q "$repo" "$dir"
    git -C "$dir" sparse-checkout set "$sub" -q 2>/dev/null || git -C "$dir" sparse-checkout set "$sub"
  fi

  if [ -d "$dir/$sub" ]; then
    printf '  ok  %-14s %6s  %s\n' "$name" "$(du -sh --exclude=.git "$dir/$sub" 2>/dev/null | cut -f1)" "$note"
  else
    echo "  ATTENZIONE: $name — '$sub' non trovato (layout upstream cambiato?)" >&2
  fi
done

echo
echo "Nessuno di questi va committato. In CLAUDE.md sono indicati come sola consultazione."
