#!/usr/bin/env bash
# Crea in dist/ i pacchetti da caricare in WordPress (Aspetto → Temi / Plugin → Aggiungi nuovo → Carica)
# più i contenuti della demo per l'importatore:
#   accoglienza-fma.zip   fma-strutture.zip   fma-richieste.zip   fma-contenuti.zip
set -euo pipefail
cd "$(dirname "$0")"
rm -rf dist; mkdir -p dist
( cd themes  && zip -qr ../dist/accoglienza-fma.zip accoglienza-fma -x '*.DS_Store' )
( cd plugins && zip -qr ../dist/fma-strutture.zip fma-strutture -x '*.DS_Store' )
( cd plugins && zip -qr ../dist/fma-richieste.zip fma-richieste -x 'fma-richieste/tests/*' '*.DS_Store' )
TMP=$(mktemp -d); mkdir -p "$TMP/fma-contenuti"
cp ../_src/data.json "$TMP/fma-contenuti/"; cp -r ../img "$TMP/fma-contenuti/img"
( cd "$TMP" && zip -qr - fma-contenuti ) > dist/fma-contenuti.zip; rm -rf "$TMP"
for f in dist/*.zip; do printf '%-26s %s\n' "$(basename "$f")" "$(du -h "$f" | cut -f1)"; done
