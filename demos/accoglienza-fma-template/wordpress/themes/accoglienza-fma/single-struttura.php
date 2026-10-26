<?php
/**
 * Pagina di una struttura: galleria, descrizione, servizi, camere, posizione
 * e, di lato, contatti della casa con il modulo di richiesta (plugin FMA Richieste).
 */

defined( 'ABSPATH' ) || exit;

if ( ! fma_plugin_attivo() ) {
	get_template_part( 'index' );
	return;
}

get_header();

while ( have_posts() ) :
	the_post();
	$s = fma_struttura_dati( get_the_ID() );
	$c = $s['contatti'];

	$foto = array_values( array_unique( array_filter( array_merge( array( $s['foto'] ), $s['galleria'] ) ) ) );
	$n    = count( $foto );
	$lb   = array_map( fn( $id ) => (string) wp_get_attachment_image_url( $id, 'full' ), $foto );

	$luogo     = fma_luogo( $s );
	$archivio  = get_post_type_archive_link( 'struttura' );
	$contenuto = fma_sezioni_contenuto( apply_filters( 'the_content', get_the_content() ) );
	$sito_nome = rtrim( preg_replace( '#^https?://(www\.)?#i', '', $c['sito'] ), '/' );
	$ext       = fma_icona( 'external', 'icon icon--sm' );
	?>
<main id="contenuto">
	<nav class="crumbs" aria-label="<?php esc_attr_e( 'Percorso', 'accoglienza-fma' ); ?>">
		<ol><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'accoglienza-fma' ); ?></a></li><li><a href="<?php echo esc_url( $archivio ); ?>"><?php esc_html_e( 'Strutture', 'accoglienza-fma' ); ?></a></li><li aria-current="page"><?php the_title(); ?></li></ol>
	</nav>

	<header class="stay__head">
		<h1 class="stay__title" data-hero-in><?php the_title(); ?></h1>
		<?php if ( $s['titolo_hero'] ) : ?>
			<p class="stay__claim"><?php echo esc_html( $s['titolo_hero'] ); ?></p>
		<?php endif; ?>
		<p class="stay__facts" data-hero-in>
			<?php if ( $luogo || $s['regione'] ) : ?>
				<span><?php fma_e_icona( 'pin', 'icon icon--sm' ); ?><?php echo esc_html( trim( $luogo . ( $s['regione'] ? ' · ' . $s['regione'] : '' ), ' ·' ) ); ?></span>
			<?php endif; ?>
			<?php if ( $s['tipo'] ) : ?>
				<span><?php echo esc_html( $s['tipo'] ); ?></span>
			<?php endif; ?>
			<?php if ( $s['camere_totali'] ) : ?>
				<span><?php /* translators: %d: numero di camere */ echo esc_html( sprintf( _n( '%d camera', '%d camere', (int) $s['camere_totali'], 'accoglienza-fma' ), (int) $s['camere_totali'] ) ); ?></span>
			<?php endif; ?>
		</p>
	</header>

	<?php if ( $n ) : ?>
		<section class="gallery" aria-label="<?php esc_attr_e( 'Foto della struttura', 'accoglienza-fma' ); ?>">
			<div class="gallery__grid gallery__grid--<?php echo (int) min( $n, 5 ); ?>" data-unveil-group>
				<?php foreach ( array_slice( $foto, 0, 5 ) as $i => $id ) : ?>
					<?php
					// Il testo alternativo scritto nella Libreria media; se manca, nome della casa e numero.
					$alt = trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) ) ?: sprintf( /* translators: 1: nome della casa, 2: numero */ __( '%1$s, foto %2$d', 'accoglienza-fma' ), $s['nome'], $i + 1 );
					?>
					<button type="button" class="gallery__tile<?php echo 0 === $i ? ' gallery__tile--lead' : ''; ?>" data-lightbox-open="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: numero, 2: totale, 3: descrizione */ __( 'Apri la foto %1$d di %2$d: %3$s', 'accoglienza-fma' ), $i + 1, $n, $alt ) ); ?>">
						<?php
						echo fma_immagine( // phpcs:ignore WordPress.Security.EscapeOutput
							$id,
							0 === $i ? 'fma-hero' : 'fma-card',
							0 === $i
								? array( 'alt' => $alt, 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 64em) 60vw, 100vw' )
								: array( 'alt' => $alt, 'sizes' => '(min-width: 64em) 20vw, 50vw' )
						);
						?>
					</button>
				<?php endforeach; ?>
			</div>
			<button type="button" class="btn btn--ghost gallery__all" data-lightbox-open="0"><?php fma_e_icona( 'photos', 'icon icon--sm' ); ?><?php /* translators: %d: numero di foto */ echo esc_html( sprintf( __( 'Tutte le foto (%d)', 'accoglienza-fma' ), $n ) ); ?></button>
			<script type="application/json" id="fma-gallery"><?php echo wp_json_encode( $lb, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP ); ?></script>
		</section>
	<?php endif; ?>

	<div class="stay">
		<div class="stay__main">
			<?php if ( $s['intro'] ) : ?>
				<p class="stay__intro"><?php echo esc_html( $s['intro'] ); ?></p>
			<?php else : ?>
				<div class="empty"><p class="empty__title"><?php esc_html_e( 'La descrizione di questa casa è in arrivo.', 'accoglienza-fma' ); ?></p><p><?php esc_html_e( 'Nel frattempo trovi qui sotto servizi, posizione e contatti. Per qualsiasi domanda scrivi direttamente alla struttura.', 'accoglienza-fma' ); ?></p></div>
			<?php endif; ?>

			<?php if ( $s['servizi'] ) : ?>
				<section class="block" aria-labelledby="h-servizi">
					<h2 id="h-servizi" class="block__title"><?php esc_html_e( 'Servizi', 'accoglienza-fma' ); ?></h2>
					<ul class="amenities">
						<?php foreach ( $s['servizi'] as $servizio ) : ?>
							<li><?php fma_e_icona( 'check', 'icon icon--sm' ); ?><?php echo esc_html( $servizio ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<section class="block" aria-labelledby="h-camere">
				<h2 id="h-camere" class="block__title"><?php esc_html_e( 'Le camere', 'accoglienza-fma' ); ?></h2>
				<?php if ( $s['camere'] ) : ?>
					<div class="rooms">
						<?php foreach ( $s['camere'] as $r ) : ?>
							<?php $img = fma_immagine( (int) ( $r['immagine'] ?? 0 ), 'fma-card', array( 'alt' => $r['nome'] ?? '', 'sizes' => '(min-width: 64em) 22rem, 100vw' ) ); ?>
							<article class="room<?php echo $img ? ' room--media' : ''; ?>">
								<?php if ( $img ) : ?>
									<div class="room__media"><?php echo $img; // phpcs:ignore ?></div>
								<?php endif; ?>
								<div class="room__body">
									<h3 class="room__name"><?php echo esc_html( $r['nome'] ?? '' ); ?></h3>
									<?php if ( ! empty( $r['dettaglio'] ) ) : ?>
										<p class="room__desc"><?php echo esc_html( $r['dettaglio'] ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $r['letti'] ) || ! empty( $r['ospiti'] ) || ! empty( $r['quante'] ) ) : ?>
										<ul class="room__facts">
											<?php if ( ! empty( $r['letti'] ) ) : ?>
												<li><span><?php esc_html_e( 'Letti', 'accoglienza-fma' ); ?></span><?php echo esc_html( $r['letti'] ); ?></li>
											<?php endif; ?>
											<?php if ( ! empty( $r['ospiti'] ) ) : ?>
												<li><span><?php esc_html_e( 'Ospiti', 'accoglienza-fma' ); ?></span><?php /* translators: %d: numero massimo di ospiti */ echo esc_html( sprintf( __( 'fino a %d', 'accoglienza-fma' ), (int) $r['ospiti'] ) ); ?></li>
											<?php endif; ?>
											<?php if ( ! empty( $r['quante'] ) ) : ?>
												<li><span><?php esc_html_e( 'Camere', 'accoglienza-fma' ); ?></span><?php echo (int) $r['quante']; ?></li>
											<?php endif; ?>
										</ul>
									<?php endif; ?>
									<button type="button" class="text-link room__pick" data-pick-room="<?php echo esc_attr( $r['nome'] ?? '' ); ?>"><?php esc_html_e( 'Richiedi questa camera', 'accoglienza-fma' ); ?> <?php fma_e_icona( 'arrow', 'icon icon--sm' ); ?></button>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="empty">
						<p class="empty__title"><?php esc_html_e( 'Le tipologie di camera sono in aggiornamento.', 'accoglienza-fma' ); ?><?php echo $s['camere_totali'] ? esc_html( ' ' . sprintf( /* translators: %d: numero di camere */ _n( 'La casa dispone di %d camera.', 'La casa dispone di %d camere.', (int) $s['camere_totali'], 'accoglienza-fma' ), (int) $s['camere_totali'] ) ) : ''; ?></p>
						<p><?php esc_html_e( 'Indica nel modulo di richiesta di quante persone e di che sistemazione hai bisogno: ti risponde direttamente la casa.', 'accoglienza-fma' ); ?></p>
					</div>
				<?php endif; ?>
			</section>

			<?php echo $contenuto; // phpcs:ignore WordPress.Security.EscapeOutput -- contenuto dell'editor già filtrato. ?>

			<?php
			if ( function_exists( 'fma_recensioni' ) ) {
				get_template_part( 'template-parts/recensioni', null, array( 'recensioni' => fma_recensioni( get_the_ID(), 3 ), 'titolo' => __( 'Cosa dicono gli ospiti', 'accoglienza-fma' ), 'casa' => false, 'stile' => 'blocco' ) );
			}
			?>

			<?php if ( $s['dintorni'] ) : ?>
				<section class="block" aria-labelledby="h-dintorni">
					<h2 id="h-dintorni" class="block__title"><?php esc_html_e( 'Nei dintorni', 'accoglienza-fma' ); ?></h2>
					<dl class="spec">
						<?php foreach ( $s['dintorni'] as $d ) : ?>
							<div><dt><?php echo esc_html( $d['luogo'] ?? '' ); ?></dt><dd><?php echo esc_html( $d['distanza'] ?? '' ); ?></dd></div>
						<?php endforeach; ?>
					</dl>
				</section>
			<?php endif; ?>

			<?php $info = array_values( array_filter( array_merge( array( $s['orari'] ), $s['regole'] ) ) ); ?>
			<?php if ( $info ) : ?>
				<section class="block" aria-labelledby="h-sapere">
					<h2 id="h-sapere" class="block__title"><?php esc_html_e( 'Da sapere', 'accoglienza-fma' ); ?></h2>
					<ul class="rules">
						<?php foreach ( $info as $voce ) : ?>
							<li><?php echo esc_html( $voce ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if ( $s['indirizzo'] || null !== $s['lat'] ) : ?>
				<section class="block" aria-labelledby="h-dove">
					<h2 id="h-dove" class="block__title"><?php esc_html_e( 'Dove siamo', 'accoglienza-fma' ); ?></h2>
					<?php if ( $s['indirizzo'] ) : ?>
						<p class="where"><?php fma_e_icona( 'pin', 'icon icon--sm' ); ?><?php echo esc_html( $s['indirizzo'] ); ?></p>
					<?php endif; ?>
					<?php
					$gmaps = '';
					if ( null !== $s['lat'] && null !== $s['lng'] ) :
						$gmaps = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $s['lat'] . ',' . $s['lng'] );
						?>
						<div class="map map--small" data-map-single data-lat="<?php echo esc_attr( $s['lat'] ); ?>" data-lng="<?php echo esc_attr( $s['lng'] ); ?>" data-name="<?php echo esc_attr( $s['nome'] ); ?>" role="region" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: nome della casa */ __( 'Mappa: %s', 'accoglienza-fma' ), $s['nome'] ) ); ?>"></div>
					<?php elseif ( $s['indirizzo'] ) : ?>
						<?php $gmaps = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $s['indirizzo'] ); ?>
					<?php endif; ?>
					<p class="where__links">
						<?php if ( $gmaps ) : ?>
							<a class="text-link" href="<?php echo esc_url( $gmaps ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Indicazioni stradali', 'accoglienza-fma' ); ?> <?php echo $ext; // phpcs:ignore ?></a>
						<?php endif; ?>
						<?php if ( $s['booking_url'] ) : ?>
							<a class="text-link" href="<?php echo esc_url( $s['booking_url'] ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'La casa anche su Booking.com', 'accoglienza-fma' ); ?> <?php echo $ext; // phpcs:ignore ?></a>
						<?php endif; ?>
					</p>
				</section>
			<?php endif; ?>

			<div class="request-slot" data-request-slot></div>
		</div>

		<aside class="stay__aside" data-aside aria-label="<?php esc_attr_e( 'Contatti e richiesta di soggiorno', 'accoglienza-fma' ); ?>">
			<div class="contact">
				<div class="contact__head">
					<?php echo fma_immagine( $c['logo'], 'medium', array( 'class' => 'contact__logo', 'alt' => '' ) ); // phpcs:ignore ?>
					<div><p class="contact__kicker"><?php esc_html_e( 'Ti risponde', 'accoglienza-fma' ); ?></p><p class="contact__name"><?php echo esc_html( $c['nome'] ?: $s['nome'] ); ?></p></div>
				</div>
				<?php $tassa = fma_tassa_testo( $s['tassa'] ); ?>
				<?php if ( null !== $s['prezzo_da'] || $tassa ) : ?>
					<dl class="contact__prices">
						<?php if ( null !== $s['prezzo_da'] ) : ?>
							<div class="contact__price"><dt><?php esc_html_e( 'Prezzo indicativo', 'accoglienza-fma' ); ?></dt><dd><?php esc_html_e( 'da', 'accoglienza-fma' ); ?> <strong><?php echo esc_html( fma_prezzo( $s['prezzo_da'] ) ); ?>&nbsp;€</strong> <?php echo esc_html( fma_prezzo_unita( $s['prezzo_unita'] ?? '' ) ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $tassa ) : ?>
							<div class="contact__tax"><dt><?php esc_html_e( 'Tassa di soggiorno', 'accoglienza-fma' ); ?></dt><dd><?php echo esc_html( $tassa ); ?></dd></div>
						<?php endif; ?>
					</dl>
				<?php endif; ?>
				<div class="contact__rows">
					<?php if ( $c['telefono'] ) : ?>
						<a class="contact__row" href="tel:<?php echo esc_attr( $c['telefono_link'] ); ?>"><?php fma_e_icona( 'phone' ); ?><span><span class="contact__label"><?php esc_html_e( 'Telefono', 'accoglienza-fma' ); ?></span><?php echo esc_html( $c['telefono'] ); ?></span></a>
					<?php endif; ?>
					<?php if ( $c['cellulare'] ) : ?>
						<a class="contact__row" href="tel:<?php echo esc_attr( fma_link_telefono( $c['cellulare'] ) ); ?>"><?php fma_e_icona( 'phone' ); ?><span><span class="contact__label"><?php esc_html_e( 'Cellulare', 'accoglienza-fma' ); ?></span><?php echo esc_html( $c['cellulare'] ); ?></span></a>
					<?php endif; ?>
					<?php if ( $c['whatsapp'] ) : ?>
						<a class="contact__row" href="<?php echo esc_url( $c['whatsapp_link'] ); ?>" rel="noopener" target="_blank"><?php fma_e_icona( 'chat' ); ?><span><span class="contact__label">WhatsApp</span><?php echo esc_html( $c['whatsapp'] ); ?></span></a>
					<?php endif; ?>
					<?php if ( $c['email'] ) : ?>
						<a class="contact__row" href="mailto:<?php echo esc_attr( antispambot( $c['email'] ) ); ?>"><?php fma_e_icona( 'mail' ); ?><span><span class="contact__label">Email</span><?php echo esc_html( antispambot( $c['email'] ) ); ?></span></a>
					<?php endif; ?>
					<?php if ( $c['sito'] ) : ?>
						<a class="contact__row" href="<?php echo esc_url( $c['sito'] ); ?>" rel="noopener" target="_blank"><?php fma_e_icona( 'globe' ); ?><span><span class="contact__label"><?php esc_html_e( 'Sito della casa', 'accoglienza-fma' ); ?></span><?php echo esc_html( $sito_nome ); ?></span></a>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( function_exists( 'fma_richieste_form' ) ) : ?>
				<?php fma_richieste_form( get_the_ID() ); ?>
			<?php elseif ( current_user_can( 'activate_plugins' ) ) : ?>
				<div class="empty" id="richiesta"><p class="empty__title">Modulo di richiesta non attivo</p><p>Attiva il plugin <em>FMA Richieste</em> per mostrare qui il modulo di prenotazione.</p></div>
			<?php endif; ?>
		</aside>
	</div>

	<?php if ( function_exists( 'fma_richieste_form' ) ) : ?>
		<div class="bottom-bar" data-bottom-bar hidden>
			<p><strong><?php the_title(); ?></strong><span><?php echo esc_html( $luogo ); ?></span></p>
			<a class="btn btn--primary" href="#richiesta"><?php esc_html_e( 'Richiedi un soggiorno', 'accoglienza-fma' ); ?></a>
		</div>
	<?php endif; ?>

	<?php
	$altre = fma_strutture( array( 'post__not_in' => array( get_the_ID() ) ) );
	uksort( $altre, fn( $a, $b ) => array( $altre[ $a ]['regione'] !== $s['regione'], $a ) <=> array( $altre[ $b ]['regione'] !== $s['regione'], $b ) );
	$altre = array_slice( $altre, 0, 3 );
	?>
	<?php if ( $altre ) : ?>
		<section class="others" aria-labelledby="others-title">
			<h2 id="others-title" class="section-title section-title--sm"><?php esc_html_e( 'Altre case', 'accoglienza-fma' ); ?></h2>
			<ul class="others__list">
				<?php foreach ( $altre as $x ) : ?>
					<li class="mini">
						<a href="<?php echo esc_url( $x['url'] ); ?>">
							<span class="mini__media"><?php echo fma_immagine( $x['foto'], 'fma-card', array( 'alt' => '', 'sizes' => '(min-width: 64em) 30vw, 100vw' ) ); // phpcs:ignore ?></span>
							<span class="mini__name"><?php echo esc_html( $x['nome'] ); ?></span>
							<span class="mini__where"><?php echo esc_html( trim( fma_luogo( $x ) . ( $x['regione'] ? ' · ' . $x['regione'] : '' ), ' ·' ) ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
</main>
	<?php
endwhile;

get_footer();
