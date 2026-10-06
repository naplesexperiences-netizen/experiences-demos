<?php
/**
 * Fix Portfolio Duplicates — Experiences Srl
 *
 * HOW TO USE:
 * 1. Upload this file to your WordPress root (same folder as wp-config.php)
 * 2. Visit: https://your-domain.com/fix-duplicates.php  (must be logged in as admin)
 * 3. DELETE this file immediately after running it
 */

require_once __DIR__ . '/wp-load.php';

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied. Log in as admin first.' );
}

echo '<style>body{font-family:sans-serif;max-width:700px;margin:40px auto;padding:20px;}
.del{color:#c00;} .ok{color:#0D7C7C;font-weight:bold;}</style>';
echo '<h1>Fix Portfolio Duplicates</h1>';

$all = get_posts([
    'post_type'      => 'portfolio_site',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);

$seen    = [];
$deleted = 0;

foreach ( $all as $post ) {
    $title = $post->post_title;
    if ( isset( $seen[$title] ) ) {
        // Duplicate — keep first, delete this one
        wp_delete_post( $post->ID, true );
        echo "<p class='del'>🗑 Deleted duplicate: <strong>$title</strong> (ID {$post->ID})</p>";
        $deleted++;
    } else {
        $seen[$title] = $post->ID;
        echo "<p class='ok'>✅ Kept: <strong>$title</strong> (ID {$post->ID})</p>";
    }
}

echo "<hr><h2>Done! Deleted $deleted duplicates. Kept " . count($seen) . " unique posts.</h2>";
echo "<p style='color:#c00;font-weight:bold;'>⚠️ DELETE this file from your server now!</p>";
echo "<p><a href='" . admin_url('edit.php?post_type=portfolio_site') . "'>→ View Portfolio</a></p>";
