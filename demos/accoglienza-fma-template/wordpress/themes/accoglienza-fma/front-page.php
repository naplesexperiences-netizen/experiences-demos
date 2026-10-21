<?php
/**
 * Home: slider delle strutture + ricerca, partner, chi siamo, mappa ed elenco, ultimi articoli.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$attivo    = fma_plugin_attivo();
$strutture = $attivo ? fma_strutture() : array();
$slider    = array_values( array_filter( $strutture, fn( $s ) => $s['in_slider'] && $s['foto'] ) );
$conteggi  = fma_conteggi();
$regioni   = $attivo ? fma_regioni() : array();
?>
<main id="contenuto">

<section class="hero" aria-labelledby="hero-title">
	<div class="hero__text">
		<h1 id="hero-title" class="hero__title" data-hero-in><?php echo esc_html( fma_testo( 'hero_titolo' ) ); ?></h1>
		<p class="hero__lede" data-hero-in><?php echo esc_html( fma_testo( 'hero_testo' ) ); ?></p>
	</div>

	<?php if ( $slider ) : ?>
		<div class="hero__slider" data-slider aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Le nostre case', 'accoglienza-fma' ); ?>">
			<div class="slider__track">
				<?php foreach ( $slider as $i => $s ) : ?>
					<?php
					$titolo = $s['titolo_hero'] ?: $s['nome'];
					$sotto  = $s['titolo_hero'] ? $s['nome'] : $s['tipo'];
					$attr   = array( 'class' => 'slide__img', 'alt' => trim( $s['nome'] . ( $s['localita'] ? ', ' . $s['localita'] : '' ) ), 'sizes' => '(max-width: 60rem) 100vw, 58vw' );
					if ( 0 === $i ) {
						$attr['loading']       = 'eager';
						$attr['fetchpriority'] = 'high';
					}
					$img = fma_immagine( $s['foto'], 'fma-hero', $attr );
					if ( $i > 0 ) {
						$img = fma_immagine_rimandata( $img ); // le altre slide si caricano quando stanno per comparire
					}
					?>
					<figure class="slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-slide role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: numero della foto, 2: totale, 3: nome della casa */ __( '%1$d di %2$d: %3$s', 'accoglienza-fma' ), $i + 1, count( $slider ), $s['nome'] ) ); ?>"<?php echo 0 === $i ? '' : ' aria-hidden="true"'; ?>>
						<?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput -- HTML di wp_get_attachment_image. ?>
						<figcaption class="slide__cap">
							<?php if ( fma_luogo( $s ) ) : ?>
								<span class="slide__where"><?php fma_e_icona( 'pin', 'icon icon--sm' ); ?><?php echo esc_html( fma_luogo( $s ) ); ?></span>
							<?php endif; ?>
							<span class="slide__title"><?php echo esc_html( $titolo ); ?></span>
							<?php if ( $sotto ) : ?>
								<span class="slide__sub"><?php echo esc_html( $sotto ); ?></span>
							<?php endif; ?>
							<a class="slide__link" href="<?php echo esc_url( $s['url'] ); ?>"<?php echo 0 === $i ? '' : ' tabindex="-1"'; ?>><?php esc_html_e( 'Scopri la casa', 'accoglienza-fma' ); ?> <?php fma_e_icona( 'arrow', 'icon icon--sm' ); ?></a>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
			<div class="slider__controls">
				<button class="slider__btn" type="button" data-slider-prev aria-label="<?php esc_attr_e( 'Casa precedente', 'accoglienza-fma' ); ?>"><?php fma_e_icona( 'chev-l' ); ?></button>
				<p class="slider__count" aria-live="polite"><span data-slider-now>01</span><span aria-hidden="true"> / </span><span class="visually-hidden"> <?php esc_html_e( 'di', 'accoglienza-fma' ); ?> </span><span><?php echo esc_html( sprintf( '%02d', count( $slider ) ) ); ?></span></p>
				<button class="slider__btn" type="button" data-slider-next aria-label="<?php esc_attr_e( 'Casa successiva', 'accoglienza-fma' ); ?>"><?php fma_e_icona( 'chev-r' ); ?></button>
				<button class="slider__btn slider__btn--toggle" type="button" data-slider-toggle aria-label="<?php esc_attr_e( 'Metti in pausa lo scorrimento', 'accoglienza-fma' ); ?>" aria-pressed="false"><?php fma_e_icona( 'pause', 'icon icon-pause' ); ?><?php fma_e_icona( 'play', 'icon icon-play' ); ?></button>
				<span class="slider__progress" aria-hidden="true"><span data-slider-bar></span></span>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $strutture ) : ?>
		<form class="search" action="#strutture" data-search role="search" aria-label="<?php esc_attr_e( 'Cerca una casa', 'accoglienza-fma' ); ?>" data-hero-in>
			<div class="field field--where">
				<label for="s-dove"><?php esc_html_e( 'Dove', 'accoglienza-fma' ); ?></label>
				<select id="s-dove" name="dove">
					<option value=""><?php esc_html_e( 'Tutte le case', 'accoglienza-fma' ); ?></option>
					<?php if ( count( $regioni ) > 1 ) : ?>
						<optgroup label="<?php esc_attr_e( 'Per regione', 'accoglienza-fma' ); ?>">
							<?php foreach ( $regioni as $r ) : ?>
								<option value="<?php echo esc_attr( 'regione:' . $r->name ); ?>"><?php echo esc_html( sprintf( /* translators: 1: regione, 2: numero di case */ __( '%1$s — tutte le case (%2$d)', 'accoglienza-fma' ), $r->name, $r->count ) ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endif; ?>
					<?php foreach ( $regioni as $r ) : ?>
						<optgroup label="<?php echo esc_attr( $r->name ); ?>">
							<?php foreach ( $strutture as $s ) : ?>
								<?php if ( $s['regione'] === $r->name ) : ?>
									<option value="<?php echo esc_attr( get_post_field( 'post_name', $s['id'] ) ); ?>"><?php echo esc_html( trim( $s['localita'] . ' · ' . $s['nome'], ' ·' ) ); ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="field">
				<label for="s-arrivo"><?php esc_html_e( 'Arrivo', 'accoglienza-fma' ); ?></label>
				<input id="s-arrivo" name="arrivo" type="date" data-date-in>
			</div>
			<div class="field">
				<label for="s-partenza"><?php esc_html_e( 'Partenza', 'accoglienza-fma' ); ?></label>
				<input id="s-partenza" name="partenza" type="date" data-date-out>
			</div>
			<div class="field field--guests">
				<label for="s-ospiti"><?php esc_html_e( 'Ospiti', 'accoglienza-fma' ); ?></label>
				<div class="stepper" data-stepper>
					<button type="button" data-step="-1" aria-label="<?php esc_attr_e( 'Un ospite in meno', 'accoglienza-fma' ); ?>"><?php fma_e_icona( 'minus', 'icon icon--sm' ); ?></button>
					<input id="s-ospiti" name="ospiti" type="number" min="1" max="60" value="2" inputmode="numeric">
					<button type="button" data-step="1" aria-label="<?php esc_attr_e( 'Un ospite in più', 'accoglienza-fma' ); ?>"><?php fma_e_icona( 'plus', 'icon icon--sm' ); ?></button>
				</div>
			</div>
			<button class="btn btn--primary search__go" type="submit"><?php esc_html_e( 'Cerca', 'accoglienza-fma' ); ?></button>
			<p class="search__error" role="alert" hidden data-search-error></p>
		</form>
	<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>
		<p class="empty" data-hero-in><?php echo $attivo ? 'Aggiungi le strutture da <strong>Strutture → Aggiungi struttura</strong>: compariranno nello slider, nella ricerca e sulla mappa.' : 'Attiva il plugin <strong>FMA Strutture</strong> per mostrare slider, ricerca e mappa.'; // phpcs:ignore ?></p>
	<?php endif; ?>
</section>

<?php
$partner = $attivo ? get_posts( array( 'post_type' => 'partner', 'post_status' => 'publish', 'numberposts' => 30, 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ) ) ) : array();
$partner = array_filter( $partner, 'has_post_thumbnail' );
if ( $partner ) :
	ob_start();
	foreach ( $partner as $p ) {
		$url  = (string) get_post_meta( $p->ID, 'fma_url', true );
		$nome = get_the_title( $p );
		// L'alt è il nome del partner anche quando il logo è un link: è il testo del link per Google e per i lettori di schermo.
		$logo = get_the_post_thumbnail( $p, 'medium', array( 'class' => 'partner__logo', 'alt' => $nome, 'loading' => 'lazy' ) );
		if ( $url ) {
			echo '<li><a href="' . esc_url( $url ) . '" rel="noopener" target="_blank">' . $logo . '<span class="visually-hidden"> ' . esc_html__( '(si apre in una nuova scheda)', 'accoglienza-fma' ) . '</span></a></li>'; // phpcs:ignore
		} else {
			echo '<li><span>' . $logo . '</span></li>'; // phpcs:ignore
		}
	}
	$loghi = ob_get_clean();
	?>
	<section class="partners" aria-labelledby="partner-title">
		<h2 id="partner-title" class="partners__title"><?php echo esc_html( fma_testo( 'partner_titolo' ) ); ?></h2>
		<div class="marquee" data-marquee>
			<ul class="marquee__track"><?php echo $loghi; // phpcs:ignore ?></ul>
			<ul class="marquee__track" aria-hidden="true"><?php echo str_replace( '<a ', '<a tabindex="-1" ', $loghi ); // phpcs:ignore ?></ul>
		</div>
	</section>
<?php endif; ?>

<section class="about" id="chi-siamo" aria-labelledby="about-title">
	<?php $foto_chi = (int) get_theme_mod( 'fma_chi_immagine', 0 ); ?>
	<?php if ( $foto_chi ) : ?>
		<figure class="about__photo" data-unveil>
			<?php echo fma_immagine( $foto_chi, 'large', array( 'alt' => fma_testo( 'chi_didascalia' ), 'sizes' => '(max-width: 60rem) 100vw, 36vw' ) ); // phpcs:ignore ?>
			<?php if ( fma_testo( 'chi_didascalia' ) ) : ?>
				<figcaption><?php echo esc_html( fma_testo( 'chi_didascalia' ) ); ?></figcaption>
			<?php endif; ?>
		</figure>
	<?php endif; ?>
	<div class="about__text">
		<h2 id="about-title" class="section-title"><?php echo esc_html( fma_testo( 'chi_titolo' ) ); ?></h2>
		<p class="lede"><?php echo esc_html( fma_testo( 'chi_lede' ) ); ?></p>
		<?php if ( fma_testo( 'chi_testo' ) ) : ?>
			<p><?php echo esc_html( fma_testo( 'chi_testo' ) ); ?></p>
		<?php endif; ?>
		<?php if ( fma_testo( 'chi_citazione' ) ) : ?>
			<blockquote class="about__quote">
				<p><?php echo esc_html( fma_testo( 'chi_citazione' ) ); ?></p>
				<?php if ( fma_testo( 'chi_firma' ) ) : ?>
					<footer><?php echo esc_html( fma_testo( 'chi_firma' ) ); ?></footer>
				<?php endif; ?>
			</blockquote>
		<?php endif; ?>
		<dl class="stats">
			<?php if ( fma_testo( 'chi_anno' ) ) : ?>
				<div><dt><?php echo esc_html( fma_testo( 'chi_anno_etichetta' ) ); ?></dt><dd data-count="<?php echo esc_attr( fma_testo( 'chi_anno' ) ); ?>"><?php echo esc_html( fma_testo( 'chi_anno' ) ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $conteggi['case'] ) : ?>
				<div><dt><?php esc_html_e( 'Case per ferie in rete', 'accoglienza-fma' ); ?></dt><dd data-count="<?php echo (int) $conteggi['case']; ?>"><?php echo (int) $conteggi['case']; ?></dd></div>
			<?php endif; ?>
			<?php if ( $conteggi['regioni'] ) : ?>
				<div><dt><?php echo esc_html( 1 === $conteggi['regioni'] ? __( 'Regione', 'accoglienza-fma' ) : __( 'Regioni in cui siamo presenti', 'accoglienza-fma' ) ); ?></dt><dd data-count="<?php echo (int) $conteggi['regioni']; ?>"><?php echo (int) $conteggi['regioni']; ?></dd></div>
			<?php endif; ?>
		</dl>
	</div>
</section>

<?php if ( $strutture ) : ?>
	<?php
	$etichette = array(
		1 => array( 'pin', $conteggi['case'] ? fma_case_regioni( $conteggi['case'], $conteggi['regioni'], true ) : __( 'Tutte le case', 'accoglienza-fma' ) ),
		2 => array( 'check', __( 'Date · persone · camera', 'accoglienza-fma' ) ),
		3 => array( 'mail', __( 'Risposta via email dalla casa', 'accoglienza-fma' ) ),
	);
	?>
	<section class="steps" aria-labelledby="steps-title">
		<h2 id="steps-title" class="section-title"><?php echo esc_html( fma_testo( 'passi_titolo' ) ); ?></h2>
		<ol class="steps__list" data-unveil-group>
			<?php for ( $n = 1; $n <= 3; $n++ ) : ?>
				<?php $foto = fma_passo_immagine( $n ); ?>
				<li class="step<?php echo $foto ? '' : ' step--testo'; ?>">
					<figure class="step__media">
						<?php echo fma_immagine( $foto, 'fma-card', array( 'class' => 'step__img', 'alt' => '', 'sizes' => '(max-width: 60rem) 100vw, 30vw' ) ); // phpcs:ignore ?>
						<span class="step__num" aria-hidden="true"><?php echo (int) $n; ?></span>
						<span class="step__chip"><?php fma_e_icona( $etichette[ $n ][0], 'icon icon--sm' ); ?><?php echo esc_html( $etichette[ $n ][1] ); ?></span>
					</figure>
					<h3 class="step__title"><span class="visually-hidden"><?php /* translators: %d: numero del passo */ echo esc_html( sprintf( __( 'Passo %d:', 'accoglienza-fma' ), $n ) . ' ' ); ?></span><?php echo esc_html( fma_testo( "passo{$n}_titolo" ) ); ?></h3>
					<p class="step__text"><?php echo esc_html( fma_testo( "passo{$n}_testo" ) ); ?></p>
				</li>
			<?php endfor; ?>
		</ol>
	</section>
<?php endif; ?>

<?php
if ( $strutture ) {
	get_template_part(
		'template-parts/strutture-mappa',
		null,
		array(
			'strutture' => $strutture,
			'titolo'    => fma_testo( 'strutture_titolo' ),
			'testo'     => fma_testo( 'strutture_testo' ),
		)
	);
}

$recensioni = function_exists( 'fma_recensioni' ) ? fma_recensioni( 0, 3 ) : array();
if ( $recensioni ) {
	get_template_part( 'template-parts/recensioni', null, array( 'recensioni' => $recensioni, 'titolo' => fma_testo( 'recensioni_titolo' ) ) );
}

$articoli = get_posts( array( 'numberposts' => 3, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
if ( $articoli ) :
	$primo  = array_shift( $articoli );
	$blog   = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : '';
	$cat    = get_the_category( $primo->ID );
	?>
	<section class="journal" aria-labelledby="journal-title">
		<div class="journal__head">
			<h2 id="journal-title" class="section-title"><?php echo esc_html( fma_testo( 'blog_titolo' ) ); ?></h2>
			<?php if ( $blog ) : ?>
				<a class="text-link" href="<?php echo esc_url( $blog ); ?>"><?php esc_html_e( 'Tutti gli articoli', 'accoglienza-fma' ); ?> <?php fma_e_icona( 'arrow', 'icon icon--sm' ); ?></a>
			<?php endif; ?>
		</div>
		<div class="journal__grid">
			<article class="post post--lead">
				<a href="<?php echo esc_url( get_permalink( $primo ) ); ?>">
					<?php if ( has_post_thumbnail( $primo ) ) : ?>
						<span class="post__media" data-unveil><?php echo get_the_post_thumbnail( $primo, 'large', array( 'alt' => '', 'sizes' => '(max-width: 60rem) 100vw, 50vw' ) ); ?></span>
					<?php endif; ?>
					<span class="post__meta"><?php if ( $cat ) : ?><span class="post__cat"><?php echo esc_html( $cat[0]->name ); ?></span> · <?php endif; ?><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $primo ) ); ?>"><?php echo esc_html( fma_data( $primo ) ); ?></time> · <?php echo esc_html( fma_minuti_testo( fma_minuti_lettura( $primo ) ) ); ?></span>
					<span class="post__title"><?php echo esc_html( get_the_title( $primo ) ); ?></span>
					<span class="post__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $primo ) ) ); ?></span>
				</a>
			</article>
			<?php if ( $articoli ) : ?>
				<ul class="journal__list">
					<?php foreach ( $articoli as $a ) : ?>
						<li class="post post--row">
							<a href="<?php echo esc_url( get_permalink( $a ) ); ?>">
								<span class="post__thumb"><?php echo get_the_post_thumbnail( $a, 'fma-quadrato', array( 'alt' => '', 'sizes' => '7.5rem' ) ); ?></span>
								<span class="post__body">
									<span class="post__meta"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $a ) ); ?>"><?php echo esc_html( fma_data( $a ) ); ?></time> · <?php echo esc_html( fma_minuti_testo( fma_minuti_lettura( $a ) ) ); ?></span>
									<span class="post__title"><?php echo esc_html( get_the_title( $a ) ); ?></span>
								</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>

<?php if ( $strutture ) : ?>
	<div class="bottom-bar" data-bottom-bar="home" hidden>
		<p><strong><?php /* translators: %d: numero di case */ echo esc_html( sprintf( _n( '%d casa per ferie', '%d case per ferie', count( $strutture ), 'accoglienza-fma' ), count( $strutture ) ) ); ?></strong><span><?php esc_html_e( 'Ti risponde direttamente la casa', 'accoglienza-fma' ); ?></span></p>
		<a class="btn btn--primary" href="#strutture"><?php esc_html_e( 'Scegli la casa', 'accoglienza-fma' ); ?></a>
	</div>
<?php endif; ?>
</main>
<?php
get_footer();
