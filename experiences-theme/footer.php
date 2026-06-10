<?php
/**
 * Footer template — Experiences Srl
 * @package experiences-srl
 */

// Footer del sito + WhatsApp float su tutte le pagine
get_template_part( 'template-parts/site-footer' );

// main.js (handle: experiences-main) e aos.js sono enqueued con defer
// in functions.php; wp_footer() li stampa qui insieme alla variabile
// localizzata experiencesAjax necessaria al contact form.
wp_footer();
?>
</body>
</html>
