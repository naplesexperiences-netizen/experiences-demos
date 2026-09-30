<?php
/**
 * Mappa + elenco delle strutture con filtri per regione.
 *
 * Argomenti: 'strutture' (array di fma_struttura_dati), 'titolo', 'testo', 'titolo_tag' (h1|h2).
 */

defined( 'ABSPATH' ) || exit;

$strutture = $args['strutture'] ?? array();
$tag       = ( $args['titolo_tag'] ?? 'h2' ) === 'h1' ? 'h1' : 'h2';
$regioni   = array();
foreach ( $strutture as $s ) {
	if ( $s['regione'] ) {
		$regioni[ $s['regione'] ] = ( $regioni[ $s['regione'] ] ?? 0 ) + 1;
	}
}
uksort( $regioni, fn( $a, $b ) => array( $regioni[ $b ], $a ) <=> array( $regioni[ $a ], $b ) );
$n = count( $strutture );

$mappa = array();
foreach ( $strutture as $s ) {
	if ( null === $s['lat'] || null === $s['lng'] ) {
		continue;
	}
	$mappa[] = array(
		'slug'    => get_post_field( 'post_name', $s['id'] ),
		'nome'    => $s['nome'],
		'luogo'   => fma_luogo( $s ),
		'regione' => $s['regione'],
		'lat'     => $s['lat'],
		'lng'     => $s['lng'],
		'url'     => $s['url'],
		'img'     => $s['foto'] ? (string) wp_get_attachment_image_url( $s['foto'], 'fma-card' ) : '',
	);
}
?>
<section class="places" id="strutture" aria-labelledby="places-title">
	<div class="places__head">
		<<?php echo $tag; // phpcs:ignore ?> id="places-title" class="section-title"><?php echo esc_html( $args['titolo'] ?? 'Le strutture' ); ?></<?php echo $tag; // phpcs:ignore ?>>
		<?php if ( ! empty( $args['testo'] ) ) : ?>
			<p class="places__lede"><?php echo esc_html( $args['testo'] ); ?></p>
		<?php endif; ?>
	</div>
	<div class="places__tools">
		<?php if ( count( $regioni ) > 1 ) : ?>
			<div class="chips" role="group" aria-label="Filtra per regione">
				<button type="button" class="chip is-active" data-filter="" aria-pressed="true">Tutte <span><?php echo (int) $n; ?></span></button>
				<?php foreach ( $regioni as $nome => $quante ) : ?>
					<button type="button" class="chip" data-filter="<?php echo esc_attr( $nome ); ?>" aria-pressed="false"><?php echo esc_html( $nome ); ?> <span><?php echo (int) $quante; ?></span></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<p class="places__status" aria-live="polite" data-places-status><?php echo esc_html( 1 === $n ? '1 casa' : "$n case" ); ?></p>
	</div>
	<div class="search-echo" hidden data-search-echo>
		<p><span data-search-echo-text></span></p>
		<button type="button" class="link-btn" data-search-reset>Azzera la ricerca</button>
	</div>
	<div class="places__grid">
		<ol class="places__list" data-places-list>
			<?php foreach ( $strutture as $i => $s ) : ?>
				<?php $servizi = implode( ' · ', array_slice( $s['servizi'], 0, 3 ) ); ?>
				<li class="place" data-place="<?php echo esc_attr( get_post_field( 'post_name', $s['id'] ) ); ?>" data-regione="<?php echo esc_attr( $s['regione'] ); ?>">
					<a class="place__link" href="<?php echo esc_url( $s['url'] ); ?>" data-place-link>
						<span class="place__num" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
						<span class="place__media"><?php echo fma_immagine( $s['foto'], 'fma-card', array( 'class' => 'place__img', 'alt' => '' ) ); // phpcs:ignore ?></span>
						<span class="place__body">
							<span class="place__name"><?php echo esc_html( $s['nome'] ); ?></span>
							<span class="place__where"><?php echo esc_html( trim( fma_luogo( $s ) . ( $s['regione'] ? ' · ' . $s['regione'] : '' ), ' ·' ) ); ?></span>
							<span class="place__meta"><?php echo esc_html( trim( $s['tipo'] . ( $servizi ? ' · ' . $servizi : '' ), ' ·' ) ); ?></span>
						</span>
						<span class="place__go" aria-hidden="true"><?php fma_e_icona( 'arrow' ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ol>
		<div class="places__map">
			<div class="map" id="mappa-strutture" data-map role="region" aria-label="Mappa delle case"></div>
			<p class="map__fallback">La mappa si carica con la connessione. Tutte le case sono comunque nell’elenco.</p>
		</div>
	</div>
	<p class="places__empty" hidden data-places-empty>Nessuna casa corrisponde a questa ricerca. <button type="button" class="link-btn" data-search-reset>Mostra tutte le case</button></p>
	<script type="application/json" id="fma-map-data"><?php echo wp_json_encode( $mappa, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP ); ?></script>
</section>
