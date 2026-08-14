<?php
/**
 * Single product price.
 *
 * @package NovaTheme
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price nova-single-product-price' ) ); ?>">
	<?php echo wp_kses_post( $product ? $product->get_price_html() : '' ); ?>
</p>
