#!/usr/bin/env bash
# Installa un WordPress usa-e-getta (SQLite) in tests/wp, attiva il plugin e crea le strutture di prova.
# Poi:  php -S 127.0.0.1:8899 -t tests/wp/wordpress tests/router.php &  python3 tests/test_richieste.py
set -euo pipefail
cd "$(dirname "$0")"; mkdir -p wp; cd wp
[ -f wp.zip ] || curl -sSL https://wordpress.org/latest.zip -o wp.zip
[ -f sq.zip ] || curl -sSL https://downloads.wordpress.org/plugin/sqlite-database-integration.zip -o sq.zip
[ -f wp-cli.phar ] || curl -sSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o wp-cli.phar
rm -rf wordpress; unzip -q wp.zip; unzip -q sq.zip -d wordpress/wp-content/plugins/
cd wordpress; WP="php ../wp-cli.phar --allow-root"
P=wp-content/plugins/sqlite-database-integration
sed -e "s#{SQLITE_IMPLEMENTATION_FOLDER_PATH}#$PWD/$P#" -e "s#{SQLITE_PLUGIN}#sqlite-database-integration/load.php#" $P/db.copy > wp-content/db.php
$WP config create --dbname=wp --dbuser=x --dbpass=x --skip-check --force --quiet --extra-php <<<"define( 'DB_DIR', __DIR__ . '/wp-content/database/' ); define( 'WP_DEBUG', true ); define( 'WP_DEBUG_LOG', true ); define( 'WP_DEBUG_DISPLAY', false );"
$WP core install --url=http://127.0.0.1:8899 --title="Accoglienza delle Salesiane" --admin_user=admin --admin_password=admin --admin_email=admin@example.test --skip-email --quiet
$WP option update siteurl http://127.0.0.1:8899 --quiet; $WP option update home http://127.0.0.1:8899 --quiet
ln -sfn "$(cd ../../.. && pwd)" wp-content/plugins/fma-richieste
mkdir -p wp-content/mu-plugins; cp ../../fma-test-harness.php wp-content/mu-plugins/
ln -sfn "$(cd ../../../../../../assets && pwd)" wp-content/fma-assets
$WP plugin activate fma-richieste --quiet; $WP rewrite structure '/%postname%/' --quiet
$WP eval '
$a = wp_insert_post(["post_type"=>"struttura","post_status"=>"publish","post_title"=>"Villa Tiberiade","post_name"=>"villa-tiberiade"]); update_post_meta($a,"fma_email","tiberiade@case.test"); update_post_meta($a,"fma_camere",[["nome"=>"Camera singola"],["nome"=>"Camera doppia"],["nome"=>"Camera tripla"]]);
$b = wp_insert_post(["post_type"=>"struttura","post_status"=>"publish","post_title"=>"Villa Tabor","post_name"=>"villa-tabor"]); update_post_meta($b,"fma_email","tabor@case.test");
$ag = wp_insert_post(["post_type"=>"agent","post_status"=>"publish","post_title"=>"FMA Napoli"]); update_post_meta($ag,"REAL_HOMES_agent_email","napoli@case.test");
$c = wp_insert_post(["post_type"=>"property","post_status"=>"publish","post_title"=>"Un luogo di pace a Napoli","post_name"=>"fma-napoli"]); add_post_meta($c,"REAL_HOMES_agents",(string)$ag); update_post_meta($c,"rvr_accommodation",[["room_type"=>"Single Room"],["room_type"=>"Double Room"]]);
wp_insert_post(["post_type"=>"struttura","post_status"=>"publish","post_title"=>"Casa senza email","post_name"=>"senza-email"]);
$e = wp_insert_post(["post_type"=>"struttura","post_status"=>"draft","post_title"=>"Bozza"]); update_post_meta($e,"fma_email","bozza@case.test");
wp_insert_post(["post_type"=>"post","post_status"=>"publish","post_title"=>"Articolo"]);'
$WP rewrite flush --quiet
echo "Pronto. Avvia il server e lancia i test."
