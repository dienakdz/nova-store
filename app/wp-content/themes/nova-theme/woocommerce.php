<?php
/**
 * WooCommerce wrapper template.
 *
 * @package NovaTheme
 */

get_header();
?>
<main id="primary" class="site-main nova-container woo-content">
	<?php woocommerce_content(); ?>
</main>
<?php
get_footer();

