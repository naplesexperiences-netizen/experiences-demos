#!/usr/bin/env bash
# Crea i due zip da caricare su WordPress.
#   ./design-system/naplesexperiences/build-zip.sh [cartella-di-uscita]
#
#   experiences-design-system.zip           plugin: Plugin → Aggiungi nuovo → Carica plugin
#   experiences-design-system-tema.zip      file per integrare il design system nel tema
set -euo pipefail
src="$(cd "$(dirname "$0")" && pwd)"
out="$(mkdir -p "${1:-$src/dist}" && cd "${1:-$src/dist}" && pwd)"
tmp="$(mktemp -d)"
trap 'rm -rf "$tmp"' EXIT

# Plugin
p="$tmp/experiences-design-system"
mkdir -p "$p/assets"
cp "$src/plugin/experiences-design-system.php" "$src/plugin/readme.txt" "$p/"
cp "$src/tokens.css" "$src/components.css" "$src/compat.css" "$p/assets/"
sed 's|href="tokens.css"|href="assets/tokens.css"|; s|href="components.css"|href="assets/components.css"|' "$src/preview.html" > "$p/preview.html"
printf '<?php\n// Silence is golden.\n' > "$p/index.php"
printf '<?php\n// Silence is golden.\n' > "$p/assets/index.php"
rm -f "$out/experiences-design-system.zip"
(cd "$tmp" && zip -qrX "$out/experiences-design-system.zip" experiences-design-system)

# File per il tema
t="$tmp/design-system"
mkdir -p "$t"
cp "$src"/{tokens.css,components.css,compat.css,enqueue.php,theme.json,tailwind.preset.js,preview.html,README.md,check-contrast.js} "$t/"
rm -f "$out/experiences-design-system-tema.zip"
(cd "$tmp" && zip -qrX "$out/experiences-design-system-tema.zip" design-system)

ls -l "$out"/*.zip
