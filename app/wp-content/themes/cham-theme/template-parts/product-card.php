<?php
/**
 * Product card.
 *
 * @package ChamTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product = isset( $args['product'] ) ? $args['product'] : wc_get_product( get_the_ID() );

if ( ! $product ) {
	return;
}

$product_id = $product->get_id();
$categories = wc_get_product_category_list( $product_id, ', ' );
?>
<article class="cham-product-card">
	<a class="product-thumb" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
		<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
		<?php if ( $product->is_on_sale() ) : ?>
			<span class="sale-badge"><?php esc_html_e( 'Giảm giá', 'cham-theme' ); ?></span>
		<?php endif; ?>
	</a>
	<div class="product-card-body">
		<?php if ( $categories ) : ?>
			<div class="product-category"><?php echo wp_kses_post( $categories ); ?></div>
		<?php endif; ?>
		<h3><a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
		<div class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
		<div class="product-card-actions">
			<?php
			if ( $product->is_purchasable() && $product->is_in_stock() && ! $product->is_type( 'variable' ) ) {
				$buy_now_url = add_query_arg( 'cham_buy_now', '1', $product->add_to_cart_url() );
				echo apply_filters(
					'woocommerce_loop_add_to_cart_link',
					sprintf(
						'<a href="%s" data-quantity="1" class="%s" %s><span class="cham-cart-button-icon" aria-hidden="true"></span><span>%s</span></a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( implode( ' ', array_filter( array( 'cham-btn', 'cham-btn-outline', 'add_to_cart_button', 'ajax_add_to_cart' ) ) ) ),
						wc_implode_html_attributes( array(
							'data-product_id'  => $product_id,
							'data-product_sku' => $product->get_sku(),
							'aria-label'       => $product->add_to_cart_description(),
							'rel'              => 'nofollow',
						) ),
						esc_html__( 'Giỏ hàng', 'cham-theme' )
					),
					$product
				);
				echo '<a class="cham-btn cham-btn-primary" href="' . esc_url( $buy_now_url ) . '" rel="nofollow">' . esc_html__( 'Mua ngay', 'cham-theme' ) . '</a>';
			} else {
				echo '<a class="cham-btn cham-btn-outline" href="' . esc_url( get_permalink( $product_id ) ) . '"><span class="cham-cart-button-icon" aria-hidden="true"></span><span>' . esc_html__( 'Giỏ hàng', 'cham-theme' ) . '</span></a>';
				echo '<a class="cham-btn cham-btn-primary" href="' . esc_url( get_permalink( $product_id ) ) . '">' . esc_html__( 'Mua ngay', 'cham-theme' ) . '</a>';
			}
			?>
		</div>
	</div>
</article>
