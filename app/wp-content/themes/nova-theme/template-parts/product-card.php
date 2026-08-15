<?php
/**
 * Product card.
 *
 * @package NovaTheme
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
$product_url = get_permalink( $product_id );
$swatches    = array();

foreach ( $product->get_attributes() as $attribute ) {
	$attribute_name  = $attribute->get_name();
	$attribute_label = wc_attribute_label( $attribute_name );
	$searchable_name = sanitize_title( $attribute_name . '-' . $attribute_label );

	if ( false === strpos( $searchable_name, 'color' ) && false === strpos( $searchable_name, 'colour' ) && false === strpos( $searchable_name, 'mau' ) ) {
		continue;
	}

	if ( $attribute->is_taxonomy() ) {
		$terms = wc_get_product_terms( $product_id, $attribute_name, array( 'fields' => 'all' ) );
		if ( is_wp_error( $terms ) ) {
			continue;
		}

		foreach ( $terms as $term ) {
			$swatches[] = array(
				'name' => $term->name,
				'slug' => $term->slug,
			);
		}
	} else {
		foreach ( $attribute->get_options() as $option ) {
			$swatches[] = array(
				'name' => $option,
				'slug' => sanitize_title( $option ),
			);
		}
	}

	break;
}

$color_map = array(
	'black'      => '#171717',
	'den'        => '#171717',
	'white'      => '#f7f5ef',
	'trang'      => '#f7f5ef',
	'beige'      => '#cfbea4',
	'be'         => '#cfbea4',
	'cream'      => '#e8dfcf',
	'kem'        => '#e8dfcf',
	'brown'      => '#795741',
	'nau'        => '#795741',
	'grey'       => '#8d8c88',
	'gray'       => '#8d8c88',
	'xam'        => '#8d8c88',
	'red'        => '#a4473f',
	'do'         => '#a4473f',
	'navy'       => '#30445e',
	'blue'       => '#456987',
	'xanh-duong' => '#456987',
	'green'      => '#5b7058',
	'xanh-la'    => '#5b7058',
	'yellow'     => '#d1ad55',
	'vang'       => '#d1ad55',
	'pink'       => '#d6a5ac',
	'hong'       => '#d6a5ac',
	'purple'     => '#71617c',
	'tim'        => '#71617c',
);
$fallback_colors = array( '#b9ab98', '#4e5b68', '#7b675c', '#727363', '#c6b9aa' );
?>
<article class="nova-product-card">
	<div class="nova-product-card-media">
		<a class="product-thumb" href="<?php echo esc_url( $product_url ); ?>" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
			<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
			<?php if ( $product->is_on_sale() ) : ?>
				<span class="sale-badge"><?php esc_html_e( 'Sale', 'nova-theme' ); ?></span>
			<?php endif; ?>
		</a>

		<div class="product-card-actions">
			<?php
			$can_quick_add = $product->is_purchasable() && $product->is_in_stock() && ! $product->is_type( 'variable' );
			$button_label  = $can_quick_add ? __( 'Thêm nhanh', 'nova-theme' ) : __( 'Xem sản phẩm', 'nova-theme' );
			$button_url    = $can_quick_add ? $product->add_to_cart_url() : $product_url;
			$button_class  = array( 'button', 'nova-card-quick-add', 'product_type_' . $product->get_type() );

			if ( $can_quick_add && $product->supports( 'ajax_add_to_cart' ) ) {
				$button_class[] = 'add_to_cart_button';
				$button_class[] = 'ajax_add_to_cart';
			}

			echo apply_filters(
				'woocommerce_loop_add_to_cart_link',
				sprintf(
					'<a href="%1$s" data-quantity="1" class="%2$s" data-product_id="%3$d" data-product_sku="%4$s" aria-label="%5$s" rel="nofollow"><span>%6$s</span><span aria-hidden="true">&#8594;</span></a>',
					esc_url( $button_url ),
					esc_attr( implode( ' ', $button_class ) ),
					$product_id,
					esc_attr( $product->get_sku() ),
					esc_attr( $product->add_to_cart_description() ),
					esc_html( $button_label )
				),
				$product,
				array()
			);
			?>
		</div>
	</div>
	<div class="product-card-body">
		<?php if ( $categories ) : ?>
			<div class="product-category"><?php echo wp_kses_post( $categories ); ?></div>
		<?php endif; ?>
		<h3><a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
		<div class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

		<?php if ( $swatches ) : ?>
			<div class="nova-product-swatches" aria-label="<?php esc_attr_e( 'Màu sắc', 'nova-theme' ); ?>">
				<?php foreach ( array_slice( $swatches, 0, 5 ) as $index => $swatch ) : ?>
					<?php
					$swatch_slug  = sanitize_title( $swatch['slug'] );
					$swatch_color = isset( $color_map[ $swatch_slug ] ) ? $color_map[ $swatch_slug ] : $fallback_colors[ abs( crc32( $swatch_slug ) ) % count( $fallback_colors ) ];
					?>
					<span class="nova-product-swatch<?php echo 0 === $index ? ' is-active' : ''; ?>" style="--nova-swatch-color: <?php echo esc_attr( $swatch_color ); ?>" title="<?php echo esc_attr( $swatch['name'] ); ?>">
						<span class="screen-reader-text"><?php echo esc_html( $swatch['name'] ); ?></span>
					</span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</article>
