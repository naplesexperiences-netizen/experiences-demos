<?php
/**
 * Recensioni degli ospiti (plugin FMA Strutture). Si usa solo quando ci sono recensioni pubblicate.
 *
 * Argomenti: 'recensioni' (da fma_recensioni), 'titolo', 'casa' (true = mostra la casa recensita),
 * 'stile' ('sezione' in home, 'blocco' nella pagina della struttura).
 */

defined( 'ABSPATH' ) || exit;

$recensioni = $args['recensioni'] ?? array();
if ( ! $recensioni ) {
	return;
}
$blocco = 'blocco' === ( $args['stile'] ?? 'sezione' );
$casa   = $args['casa'] ?? true;
$id     = $blocco ? 'h-recensioni' : 'reviews-title';
?>
<section class="<?php echo $blocco ? 'block reviews reviews--block' : 'reviews'; ?>" aria-labelledby="<?php echo esc_attr( $id ); ?>">
	<div class="reviews__head">
		<h2 id="<?php echo esc_attr( $id ); ?>" class="<?php echo $blocco ? 'block__title' : 'section-title'; ?>"><?php echo esc_html( $args['titolo'] ?? __( 'Dicono di noi', 'accoglienza-fma' ) ); ?></h2>
		<p class="reviews__note"><?php esc_html_e( 'Recensioni di ospiti reali, con il link alla fonte originale.', 'accoglienza-fma' ); ?></p>
	</div>
	<ul class="reviews__list">
		<?php foreach ( $recensioni as $r ) : ?>
			<li class="review">
				<blockquote class="review__text"><p><?php echo esc_html( wp_trim_words( $r['testo'], 60, '…' ) ); ?></p></blockquote>
				<p class="review__by">
					<strong><?php echo esc_html( $r['nome'] ); ?></strong>
					<?php if ( $casa && $r['casa'] ) : ?>
						<span> · <a href="<?php echo esc_url( $r['casa_url'] ); ?>"><?php echo esc_html( $r['casa'] ); ?></a></span>
					<?php endif; ?>
				</p>
				<p class="review__src">
					<?php
					$parti = array_filter(
						array(
							$r['voto'] && $r['fonte'] ? sprintf( /* translators: 1: voto, 2: sito della recensione */ __( '%1$s su %2$s', 'accoglienza-fma' ), $r['voto'], $r['fonte'] ) : ( $r['fonte'] ?: $r['voto'] ),
							$r['periodo'],
						)
					);
					echo esc_html( implode( ' · ', $parti ) );
					?>
					<?php if ( $r['link'] ) : ?>
						<?php echo $parti ? ' · ' : ''; ?><a href="<?php echo esc_url( $r['link'] ); ?>" rel="noopener nofollow" target="_blank"><?php esc_html_e( 'Leggi l’originale', 'accoglienza-fma' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(si apre in una nuova scheda)', 'accoglienza-fma' ); ?></span></a>
					<?php endif; ?>
				</p>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
