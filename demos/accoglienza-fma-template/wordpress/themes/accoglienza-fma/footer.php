<?php
/**
 * Piè di pagina (con marchio).
 */

defined( 'ABSPATH' ) || exit;

$logo_id = (int) get_theme_mod( 'custom_logo' );
?>
<footer class="foot">
	<div class="foot__inner">
		<div class="foot__mast">
			<?php if ( $logo_id ) : ?>
				<?php echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/accoglienza-fma.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="500" height="121" loading="lazy">
			<?php endif; ?>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="foot__tagline"><?php echo esc_html( rtrim( get_bloginfo( 'description' ), '.' ) ); ?>.</p>
			<?php endif; ?>
		</div>
		<nav class="foot__links" aria-label="Piè di pagina">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'piede',
					'container'      => false,
					'depth'          => 1,
					'items_wrap'     => '%3$s',
					'fallback_cb'    => false,
					'walker'         => new class() extends Walker_Nav_Menu {
						public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
							$output .= '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
						}
						public function end_el( &$output, $item, $depth = 0, $args = null ) {}
					},
				)
			);
			if ( get_privacy_policy_url() ) {
				echo '<a href="' . esc_url( get_privacy_policy_url() ) . '">Privacy</a>';
			}
			?>
		</nav>
		<p class="foot__meta">© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?> · Figlie di Maria Ausiliatrice. <?php echo esc_html( fma_testo( 'piede_nota' ) ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
