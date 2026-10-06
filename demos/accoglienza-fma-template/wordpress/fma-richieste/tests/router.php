<?php
$root = __DIR__ . '/wp/wordpress';
$path = urldecode( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
$file = realpath( $root . $path );
if ( $file && is_file( $file ) && ! str_ends_with( $file, '.php' ) ) return false;
if ( $file && is_file( $file ) ) { chdir( dirname( $file ) ); $_SERVER['SCRIPT_NAME'] = $path; require $file; return; }
if ( $file && is_dir( $file ) && is_file( $file . '/index.php' ) ) { chdir( $file ); require $file . '/index.php'; return; }
$_SERVER['SCRIPT_NAME'] = '/index.php'; chdir( $root ); require $root . '/index.php';
