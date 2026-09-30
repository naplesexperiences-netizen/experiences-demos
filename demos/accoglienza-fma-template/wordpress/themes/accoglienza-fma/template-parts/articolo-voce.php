<?php
/**
 * Voce dell'elenco del blog. Argomenti: 'primo' (bool) per la voce in evidenza.
 */

defined( 'ABSPATH' ) || exit;

$primo = ! empty( $args['primo'] );
$cat   = get_the_category();
$casa  = (int) get_post_meta( get_the_ID(), 'fma_struttura_vicina', true );
$casa  = $casa && 'publish' === get_post_status( $casa ) ? $casa : 0;
?>
<li class="entry<?php echo $primo ? ' entry--lead' : ''; ?>">
	<a href="<?php the_permalink(); ?>">
		<span class="entry__media"<?php echo $primo ? ' data-unveil' : ''; ?>>
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail(
					$primo ? 'large' : 'fma-card',
					$primo
						? array( 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 64em) 55vw, 100vw' )
						: array( 'alt' => '', 'sizes' => '(min-width: 64em) 33vw, 100vw' )
				);
			}
			?>
		</span>
		<span class="entry__body">
			<span class="post__meta"><?php if ( $cat ) : ?><span class="post__cat"><?php echo esc_html( $cat[0]->name ); ?></span> · <?php endif; ?><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( fma_data() ); ?></time> · <?php echo (int) fma_minuti_lettura(); ?> min</span>
			<span class="entry__title"><?php the_title(); ?></span>
			<span class="entry__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></span>
			<?php if ( $casa ) : ?>
				<span class="entry__near"><?php fma_e_icona( 'pin', 'icon icon--sm' ); ?>Vicino a <?php echo esc_html( get_the_title( $casa ) ); ?></span>
			<?php endif; ?>
		</span>
	</a>
</li>
