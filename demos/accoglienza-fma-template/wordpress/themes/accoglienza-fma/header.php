<?php
/**
 * Testata (masthead editoriale).
 */

defined( 'ABSPATH' ) || exit;

$conteggi = fma_conteggi();
$cta_url  = is_front_page() ? '#strutture' : ( get_post_type_archive_link( 'struttura' ) ?: home_url( '/#strutture' ) );
$logo_id  = (int) get_theme_mod( 'custom_logo' );
$menu     = array(
	'theme_location' => 'primario',
	'container'      => false,
	'depth'          => 1,
	'fallback_cb'    => 'fma_menu_riserva',
);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#contenuto"><?php esc_html_e( 'Vai al contenuto', 'accoglienza-fma' ); ?></a>
<header class="mast" data-mast>
	<div class="mast__issue">
		<p><?php echo esc_html( fma_testo( 'testata' ) ); ?><?php if ( $conteggi['case'] ) : ?> · <?php echo esc_html( fma_case_regioni( $conteggi['case'], $conteggi['regioni'] ) ); ?><?php endif; ?></p>
	</div>
	<div class="mast__bar">
		<a class="mast__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' — ' . __( 'home', 'accoglienza-fma' ) ); ?>">
			<?php if ( $logo_id ) : ?>
				<?php echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => '', 'loading' => 'eager', 'decoding' => 'async' ) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/accoglienza-fma.png' ); ?>" alt="" width="500" height="121">
			<?php endif; ?>
		</a>
		<nav class="mast__nav" aria-label="<?php esc_attr_e( 'Principale', 'accoglienza-fma' ); ?>">
			<?php wp_nav_menu( $menu ); ?>
		</nav>
		<div class="mast__actions">
			<?php fma_lingue( 'mast__lang' ); ?>
			<a class="btn btn--primary mast__cta" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( fma_testo( 'cta' ) ); ?></a>
		</div>
		<button class="mast__menu" type="button" aria-expanded="false" aria-controls="menu-mobile" data-menu-toggle>
			<?php fma_e_icona( 'menu' ); ?><span><?php esc_html_e( 'Menu', 'accoglienza-fma' ); ?></span>
		</button>
	</div>
	<div class="mast__sheet" id="menu-mobile" hidden>
		<?php wp_nav_menu( array_merge( $menu, array( 'menu_id' => 'menu-mobile-voci', 'fma_mobile' => true ) ) ); ?>
		<a class="btn btn--primary" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( fma_testo( 'cta' ) ); ?></a>
		<?php fma_lingue( 'sheet__lang' ); ?>
	</div>
</header>
