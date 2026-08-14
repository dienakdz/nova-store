<?php
/**
 * WooCommerce wrapper template.
 *
 * @package ChamTheme
 */

get_header();
?>
<main id="primary" class="site-main cham-container woo-content">
	<?php woocommerce_content(); ?>
</main>
<?php
get_footer();

