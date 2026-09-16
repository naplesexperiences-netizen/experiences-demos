<?php
/**
 * Footer template — Experiences Srl
 * @package experiences-srl
 */

get_template_part( 'template-parts/site-footer' );
get_template_part( 'template-parts/booking-modal' );
get_template_part( 'template-parts/cookie-banner' );

// main.js + aos.js enqueued con defer in functions.php; wp_footer() li
// stampa qui insieme alla variabile localizzata experiencesAjax.
wp_footer();
?>
</body>
</html>
