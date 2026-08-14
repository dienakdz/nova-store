<?php
/**
 * Single product image and video gallery.
 *
 * @package ChamTheme
 * @version 10.5.0
 */

use Automattic\WooCommerce\Enums\ProductType;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$video_url         = function_exists( 'cham_get_product_video_url' ) ? cham_get_product_video_url( $product ) : '';
$video_thumb       = $post_thumbnail_id ? wp_get_attachment_image_url( $post_thumbnail_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src( 'woocommerce_thumbnail' );
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id || $video_url ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);

if ( $video_url ) {
	$wrapper_classes[] = 'cham-product-gallery-has-video';
}
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<div class="woocommerce-product-gallery__wrapper">
		<?php if ( $video_url ) : ?>
			<div
				class="woocommerce-product-gallery__image cham-product-video-slide"
				data-thumb="<?php echo esc_url( $video_thumb ); ?>"
				data-thumb-alt="<?php echo esc_attr( get_the_title( $product->get_id() ) ); ?>"
			>
				<video controls preload="metadata" playsinline src="<?php echo esc_url( $video_url ); ?>"></video>
			</div>
		<?php endif; ?>

		<?php
		if ( $post_thumbnail_id ) {
			$html = wc_get_gallery_image_html( $post_thumbnail_id, ! $video_url );
		} else {
			$wrapper_classname = $product->is_type( ProductType::VARIABLE ) && ! empty( $product->get_visible_children() ) && '' !== $product->get_price() ?
				'woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder' :
				'woocommerce-product-gallery__image--placeholder';
			$html              = sprintf( '<div class="%s">', esc_attr( $wrapper_classname ) );
			$html             .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html             .= '</div>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

		do_action( 'woocommerce_product_thumbnails' );
		?>
	</div>
</div>
