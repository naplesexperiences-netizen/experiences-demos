<?php
/**
 * Design system Experiences — da includere in functions.php del tema:
 *   require get_template_directory() . '/design-system/enqueue.php';
 *
 * Ordine: tokens → (tailwind, style.css già registrati dal tema) → components → compat.
 * 'experiences-tailwind' e 'experiences-style' sono gli handle che il tema usa
 * oggi (v2.19.0). Se cambiano, WordPress salta in silenzio gli stili che ne
 * dipendono: va aggiornato anche qui.
 */
add_action( 'wp_enqueue_scripts', function () {
	$dir = get_template_directory_uri() . '/design-system';
	$ver = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'experiences-tokens', "$dir/tokens.css", array(), $ver );
	wp_enqueue_style( 'experiences-components', "$dir/components.css", array( 'experiences-tokens', 'experiences-tailwind' ), $ver );
	wp_enqueue_style( 'experiences-compat', "$dir/compat.css", array( 'experiences-components', 'experiences-style' ), $ver );
}, 20 );
