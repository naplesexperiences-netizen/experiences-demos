#!/usr/bin/env bash
# Sito WordPress locale usa-e-getta (SQLite) con tema + plugin FMA e i contenuti della demo importati.
#   ./dev/setup.sh            installa in dev/wp (ignorata da git)
#   php -S 127.0.0.1:8890 -t dev/wp/wordpress dev/router.php    poi apri http://127.0.0.1:8890  (admin / admin)
#   SENZA_CONTENUTI=1 ./dev/setup.sh   sito vuoto, per provare Strumenti → Importa contenuti FMA
# Le email non partono: il mu-plugin dev/fma-dev-mail.php le salva in wp-content/mail-log.json.
set -euo pipefail
DEV="$(cd "$(dirname "$0")" && pwd)"; WPROOT="$(cd "$DEV/.." && pwd)"; DEMO="$(cd "$WPROOT/.." && pwd)"
PORTA="${PORTA:-8890}"
mkdir -p "$DEV/wp"; cd "$DEV/wp"
CACHE="$WPROOT/plugins/fma-richieste/tests/wp"
for f in wp.zip sq.zip wp-cli.phar; do [ -f "$f" ] || { [ -f "$CACHE/$f" ] && cp "$CACHE/$f" .; } || true; done
[ -f wp.zip ] || curl -sSL https://wordpress.org/latest.zip -o wp.zip
[ -f sq.zip ] || curl -sSL https://downloads.wordpress.org/plugin/sqlite-database-integration.zip -o sq.zip
[ -f wp-cli.phar ] || curl -sSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o wp-cli.phar
rm -rf wordpress; unzip -q wp.zip; unzip -q sq.zip -d wordpress/wp-content/plugins/
cd wordpress; WP="php ../wp-cli.phar --allow-root"
P=wp-content/plugins/sqlite-database-integration
sed -e "s#{SQLITE_IMPLEMENTATION_FOLDER_PATH}#$PWD/$P#" -e "s#{SQLITE_PLUGIN}#sqlite-database-integration/load.php#" $P/db.copy > wp-content/db.php
$WP config create --dbname=wp --dbuser=x --dbpass=x --skip-check --force --quiet --extra-php <<<"define( 'DB_DIR', __DIR__ . '/wp-content/database/' ); define( 'WP_DEBUG', true ); define( 'WP_DEBUG_LOG', true ); define( 'WP_DEBUG_DISPLAY', false );"
$WP core install --url=http://127.0.0.1:$PORTA --title="Accoglienza delle Salesiane" --admin_user=admin --admin_password=admin --admin_email=admin@example.test --skip-email --quiet
$WP option update siteurl http://127.0.0.1:$PORTA --quiet; $WP option update home http://127.0.0.1:$PORTA --quiet
$WP language core install it_IT --activate --quiet 2>/dev/null || $WP option update WPLANG it_IT --quiet
$WP option update timezone_string Europe/Rome --quiet
ln -sfn "$WPROOT/themes/accoglienza-fma" wp-content/themes/accoglienza-fma
ln -sfn "$WPROOT/plugins/fma-strutture" wp-content/plugins/fma-strutture
ln -sfn "$WPROOT/plugins/fma-richieste" wp-content/plugins/fma-richieste
mkdir -p wp-content/mu-plugins; cp "$DEV/fma-dev-mail.php" wp-content/mu-plugins/
$WP theme activate accoglienza-fma --quiet
$WP plugin activate fma-strutture fma-richieste --quiet
if [ -z "${SENZA_CONTENUTI:-}" ]; then
  $WP fma importa "$DEMO/_src/data.json" --immagini="$DEMO/img" --configura-sito
fi
$WP rewrite flush --quiet
echo "Pronto: php -S 127.0.0.1:$PORTA -t $DEV/wp/wordpress $DEV/router.php"
