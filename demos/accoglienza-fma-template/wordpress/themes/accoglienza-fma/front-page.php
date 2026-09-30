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
		<div class="hero__slider" data-slider aria-roledescription="carousel" aria-label="Le nostre case">
			<div class="slider__track">
				<?php foreach ( $slider as $i => $s ) : ?>
					<?php
					$titolo = $s['titolo_hero'] ?: $s['nome'];
					$sotto  = $s['titolo_hero'] ? $s['nome'] : $s['tipo'];
					$attr   = array( 'class' => 'slide__img', 'alt' => trim( $s['nome'] . ( $s['localita'] ? ', ' . $s['localita'] : '' ) ), 'sizes' => '(max-width: 960px) 100vw, 58vw' );
					if ( 0 === $i ) {
						$attr['loading']       = 'eager';
						$attr['fetchpriority'] = 'high';
					}
					?>
					<figure class="slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-slide role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( '%d di %d: %s', $i + 1, count( $slider ), $s['nome'] ) ); ?>"<?php echo 0 === $i ? '' : ' aria-hidden="true"'; ?>>
						<?php echo fma_immagine( $s['foto'], 'fma-hero', $attr ); // phpcs:ignore ?>
						<figcaption class="slide__cap">
							<?php if ( fma_luogo( $s ) ) : ?>
								<span class="slide__where"><?php fma_e_icona( 'pin', 'icon icon--sm' ); ?><?php echo esc_html( fma_luogo( $s ) ); ?></span>
							<?php endif; ?>
							<span class="slide__title"><?php echo esc_html( $titolo ); ?></span>
							<?php if ( $sotto ) : ?>
								<span class="slide__sub"><?php echo esc_html( $sotto ); ?></span>
							<?php endif; ?>
							<a class="slide__link" href="<?php echo esc_url( $s['url'] ); ?>"<?php echo 0 === $i ? '' : ' tabindex="-1"'; ?>>Scopri la casa <?php fma_e_icona( 'arrow', 'icon icon--sm' ); ?></a>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
			<div class="slider__controls">
				<button class="slider__btn" type="button" data-slider-prev aria-label="Casa precedente"><?php fma_e_icona( 'chev-l' ); ?></button>
				<p class="slider__count" aria-live="polite"><span data-slider-now>01</span><span aria-hidden="true"> / </span><span class="visually-hidden"> di </span><span><?php echo esc_html( sprintf( '%02d', count( $slider ) ) ); ?></span></p>
				<button class="slider__btn" type="button" data-slider-next aria-label="Casa successiva"><?php fma_e_icona( 'chev-r' ); ?></button>
				<button class="slider__btn slider__btn--toggle" type="button" data-slider-toggle aria-label="Metti in pausa lo scorrimento" aria-pressed="false"><?php fma_e_icona( 'pause', 'icon icon-pause' ); ?><?php fma_e_icona( 'play', 'icon icon-play' ); ?></button>
				<span class="slider__progress" aria-hidden="true"><span data-slider-bar></span></span>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $strutture ) : ?>
		<form class="search" action="#strutture" data-search role="search" aria-label="Cerca una casa" data-hero-in>
			<div class="field field--where">
				<label for="s-dove">Dove</label>
				<select id="s-dove" name="dove">
					<option value="">Tutte le case</option>
					<?php if ( count( $regioni ) > 1 ) : ?>
						<optgroup label="Per regione">
							<?php foreach ( $regioni as $r ) : ?>
								<option value="<?php echo esc_attr( 'regione:' . $r->name ); ?>"><?php echo esc_html( sprintf( '%s — tutte le case (%d)', $r->name, $r->count ) ); ?></option>
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
				<label for="s-arrivo">Arrivo</label>
				<input id="s-arrivo" name="arrivo" type="date" data-date-in>
			</div>
			<div class="field">
				<label for="s-partenza">Partenza</label>
				<input id="s-partenza" name="partenza" type="date" data-date-out>
			</div>
			<div class="field field--guests">
				<label for="s-ospiti">Ospiti</label>
				<div class="stepper" data-stepper>
					<button type="button" data-step="-1" aria-label="Un ospite in meno"><?php fma_e_icona( 'minus', 'icon icon--sm' ); ?></button>
					<input id="s-ospiti" name="ospiti" type="number" min="1" max="60" value="2" inputmode="numeric">
					<button type="button" data-step="1" aria-label="Un ospite in più"><?php fma_e_icona( 'plus', 'icon icon--sm' ); ?></button>
				</div>
			</div>
			<button class="btn btn--primary search__go" type="submit">Cerca</button>
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
		$logo = get_the_post_thumbnail( $p, 'medium', array( 'class' => 'partner__logo', 'alt' => $url ? '' : $nome, 'loading' => 'lazy' ) );
		if ( $url ) {
			echo '<li><a href="' . esc_url( $url ) . '" rel="noopener" target="_blank" aria-label="' . esc_attr( $nome . ' (si apre in una nuova scheda)' ) . '">' . $logo . '</a></li>'; // phpcs:ignore
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
			<?php echo fma_immagine( $foto_chi, 'large', array( 'alt' => fma_testo( 'chi_didascalia' ) ) ); // phpcs:ignore ?>
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
				<div><dt>Case per ferie in rete</dt><dd data-count="<?php echo (int) $conteggi['case']; ?>"><?php echo (int) $conteggi['case']; ?></dd></div>
			<?php endif; ?>
			<?php if ( $conteggi['regioni'] ) : ?>
				<div><dt><?php echo 1 === $conteggi['regioni'] ? 'Regione' : 'Regioni in cui siamo presenti'; ?></dt><dd data-count="<?php echo (int) $conteggi['regioni']; ?>"><?php echo (int) $conteggi['regioni']; ?></dd></div>
			<?php endif; ?>
		</dl>
	</div>
</section>

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
				<a class="text-link" href="<?php echo esc_url( $blog ); ?>">Tutti gli articoli <?php fma_e_icona( 'arrow', 'icon icon--sm' ); ?></a>
			<?php endif; ?>
		</div>
		<div class="journal__grid">
			<article class="post post--lead">
				<a href="<?php echo esc_url( get_permalink( $primo ) ); ?>">
					<?php if ( has_post_thumbnail( $primo ) ) : ?>
						<span class="post__media" data-unveil><?php echo get_the_post_thumbnail( $primo, 'large', array( 'alt' => '' ) ); ?></span>
					<?php endif; ?>
					<span class="post__meta"><?php if ( $cat ) : ?><span class="post__cat"><?php echo esc_html( $cat[0]->name ); ?></span> · <?php endif; ?><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $primo ) ); ?>"><?php echo esc_html( fma_data( $primo ) ); ?></time> · <?php echo (int) fma_minuti_lettura( $primo ); ?> min</span>
					<span class="post__title"><?php echo esc_html( get_the_title( $primo ) ); ?></span>
					<span class="post__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $primo ) ) ); ?></span>
				</a>
			</article>
			<?php if ( $articoli ) : ?>
				<ul class="journal__list">
					<?php foreach ( $articoli as $a ) : ?>
						<li class="post post--row">
							<a href="<?php echo esc_url( get_permalink( $a ) ); ?>">
								<span class="post__thumb"><?php echo get_the_post_thumbnail( $a, 'fma-quadrato', array( 'alt' => '' ) ); ?></span>
								<span class="post__body">
									<span class="post__meta"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $a ) ); ?>"><?php echo esc_html( fma_data( $a ) ); ?></time> · <?php echo (int) fma_minuti_lettura( $a ); ?> min</span>
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

</main>
<?php
get_footer();
