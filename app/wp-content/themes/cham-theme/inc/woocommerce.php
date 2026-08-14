<?php
/**
 * WooCommerce integration.
 *
 * @package ChamTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cham_woocommerce_script_texts() {
	wp_add_inline_script(
		'wc-add-to-cart',
		"if ( window.wc_add_to_cart_params ) { window.wc_add_to_cart_params.i18n_view_cart = 'Xem giỏ hàng'; }",
		'after'
	);
}
add_action( 'wp_enqueue_scripts', 'cham_woocommerce_script_texts', 20 );

function cham_cart_count() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return 0;
	}

	return WC()->cart->get_cart_contents_count();
}

function cham_cart_link() {
	if ( function_exists( 'wc_get_cart_url' ) ) {
		return wc_get_cart_url();
	}

	return home_url( '/' );
}

function cham_cart_fragment( $fragments ) {
	$fragments['span.cham-cart-count'] = '<span class="cham-cart-count">' . esc_html( cham_cart_count() ) . '</span>';
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'cham_cart_fragment' );

function cham_admin_product_video_assets( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'product' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'cham-admin-product-video',
		get_template_directory_uri() . '/assets/js/admin-product-video.js',
		array( 'jquery' ),
		CHAM_THEME_VERSION,
		true
	);

	wp_localize_script(
		'cham-admin-product-video',
		'chamProductVideo',
		array(
			'frameTitle'   => __( 'Select product video', 'cham-theme' ),
			'buttonText'   => __( 'Use this video', 'cham-theme' ),
			'videoPreview' => __( 'Selected video preview', 'cham-theme' ),
		)
	);

	wp_add_inline_style(
		'woocommerce_admin_styles',
		'.cham-product-video-actions .button{margin-right:6px}.cham-product-video-status{display:inline-block;margin-left:6px}.cham-product-video-preview{display:block;max-width:320px;width:100%;height:auto;background:#111;border-radius:4px}.cham-product-video-preview[hidden]{display:none}'
	);
}
add_action( 'admin_enqueue_scripts', 'cham_admin_product_video_assets' );

function cham_product_video_field() {
	$video_url = get_post_meta( get_the_ID(), '_cham_product_video_url', true );
	$status    = $video_url ? __( 'Video selected', 'cham-theme' ) : __( 'No video selected', 'cham-theme' );

	echo '<p class="form-field cham-product-video-actions">';
	echo '<label for="_cham_product_video_url">' . esc_html__( 'Product video', 'cham-theme' ) . '</label>';
	echo '<input type="hidden" id="_cham_product_video_url" name="_cham_product_video_url" value="' . esc_attr( $video_url ) . '">';
	echo '<button type="button" class="button cham-select-product-video">' . esc_html__( 'Choose video', 'cham-theme' ) . '</button> ';
	echo '<button type="button" class="button cham-remove-product-video">' . esc_html__( 'Remove video', 'cham-theme' ) . '</button>';
	echo '<span class="description cham-product-video-status" data-empty="' . esc_attr__( 'No video selected', 'cham-theme' ) . '" data-selected="' . esc_attr__( 'Video selected', 'cham-theme' ) . '">' . esc_html( $status ) . '</span>';
	echo wc_help_tip( __( 'Choose an optional MP4/WebM video from Media Library. It will appear before product images in the single product gallery.', 'cham-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</p>';
	echo '<p class="form-field cham-product-video-preview-row">';
	echo '<span class="cham-product-video-spacer"></span>';
	echo '<video class="cham-product-video-preview" controls preload="metadata" src="' . esc_url( $video_url ) . '"' . ( $video_url ? '' : ' hidden' ) . '></video>';
	echo '</p>';
}
add_action( 'woocommerce_product_options_general_product_data', 'cham_product_video_field' );

function cham_save_product_video_field( $product ) {
	$video_url = isset( $_POST['_cham_product_video_url'] ) ? esc_url_raw( wp_unslash( $_POST['_cham_product_video_url'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$product->update_meta_data( '_cham_product_video_url', $video_url );
}
add_action( 'woocommerce_admin_process_product_object', 'cham_save_product_video_field' );

function cham_get_product_video_url( $product = null ) {
	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( $product ? $product : get_the_ID() );
	}

	if ( ! $product ) {
		return '';
	}

	return esc_url( $product->get_meta( '_cham_product_video_url', true ) );
}

function cham_wc_category_in_loop() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$terms = wc_get_product_category_list( $product->get_id(), ', ' );
	if ( $terms ) {
		echo '<div class="cham-loop-category">' . wp_kses_post( $terms ) . '</div>';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'cham_wc_category_in_loop', 8 );

function cham_wc_loop_wrapper_start() {
	echo '<div class="cham-shop-toolbar">';
}
add_action( 'woocommerce_before_shop_loop', 'cham_wc_loop_wrapper_start', 15 );

function cham_wc_loop_wrapper_end() {
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'cham_wc_loop_wrapper_end', 35 );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

function cham_product_trust_list() {
	echo '<div class="cham-product-trust-list" aria-label="' . esc_attr__( 'Cam kết sản phẩm', 'cham-theme' ) . '">';
	echo '<ul>';
	echo '<li><span class="cham-product-trust-list__icon" aria-hidden="true"></span><span>' . esc_html__( 'Tiêu chuẩn sản xuất Quốc tế ISO 22000:2018', 'cham-theme' ) . '</span></li>';
	echo '<li><span class="cham-product-trust-list__icon" aria-hidden="true"></span><span>' . esc_html__( 'Cam kết giá sỉ tốt nhất từ nhà sản xuất', 'cham-theme' ) . '</span></li>';
	echo '<li><span class="cham-product-trust-list__icon" aria-hidden="true"></span><span>' . esc_html__( 'Cà phê nguyên hạt thơm ngon chuẩn vị', 'cham-theme' ) . '</span></li>';
	echo '<li><span class="cham-product-trust-list__icon" aria-hidden="true"></span><span>' . esc_html__( 'Nguyên liệu nguồn gốc xuất xứ rõ ràng', 'cham-theme' ) . '</span></li>';
	echo '</ul>';
	echo '</div>';
}

function cham_product_media_column() {
	echo '<div class="cham-product-media-column">';
	woocommerce_show_product_images();
	cham_product_trust_list();
	echo '</div>';
}
add_action( 'woocommerce_before_single_product_summary', 'cham_product_media_column', 20 );

function cham_format_compact_number( $number, $append_plus = false ) {
	$number = max( 0, (int) $number );

	if ( $number >= 1000000 ) {
		$value     = $number / 1000000;
		$precision = $value >= 10 ? 0 : 1;
		$output    = number_format( $value, $precision, ',', '.' ) . 'tr';
	} elseif ( $number >= 1000 ) {
		$value     = $number / 1000;
		$precision = $value >= 10 ? 0 : 1;
		$output    = number_format( $value, $precision, ',', '.' ) . 'k';
	} else {
		$output = number_format_i18n( $number );
	}

	return $append_plus && $number >= 1000 ? $output . '+' : $output;
}

function cham_product_social_proof() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$average      = (float) $product->get_average_rating();
	$review_count = (int) $product->get_review_count();
	$sales_count  = (int) $product->get_total_sales();
	$rating_width = $average > 0 ? min( 100, max( 0, ( $average / 5 ) * 100 ) ) : 0;
	$rating_text  = number_format( $average, 1, '.', '' );
	$reviews_id   = wc_reviews_enabled() ? '#reviews' : '';

	echo '<div class="cham-product-social-proof" aria-label="' . esc_attr__( 'Thông tin đánh giá và lượt bán', 'cham-theme' ) . '">';
	echo '<div class="cham-product-social-proof__item cham-product-social-proof__rating">';
	echo '<span class="cham-product-social-proof__value">' . esc_html( $rating_text ) . '</span>';
	echo '<span class="cham-rating-stars" style="--cham-rating-percent:' . esc_attr( $rating_width ) . '%" aria-hidden="true"></span>';
	echo '</div>';

	if ( $reviews_id ) {
		echo '<a class="cham-product-social-proof__item" href="' . esc_url( $reviews_id ) . '">';
	} else {
		echo '<span class="cham-product-social-proof__item">';
	}

	echo '<span class="cham-product-social-proof__value">' . esc_html( cham_format_compact_number( $review_count ) ) . '</span>';
	echo '<span>' . esc_html__( 'Đánh giá', 'cham-theme' ) . '</span>';

	if ( $reviews_id ) {
		echo '</a>';
	} else {
		echo '</span>';
	}

	echo '<div class="cham-product-social-proof__item">';
	echo '<span class="cham-product-social-proof__value">' . esc_html( cham_format_compact_number( $sales_count, true ) ) . '</span>';
	echo '<span>' . esc_html__( 'Đã bán', 'cham-theme' ) . '</span>';
	echo '</div>';
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'cham_product_social_proof', 6 );

function cham_product_cart_actions() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	echo '<input type="hidden" name="cham_buy_now" value="">';
	echo '<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="cham-buy-now-button button alt" onclick="this.form.cham_buy_now.value=\'1\';">' . esc_html__( 'Mua ngay', 'cham-theme' ) . '</button>';
}
add_action( 'woocommerce_after_add_to_cart_button', 'cham_product_cart_actions' );

function cham_buy_now_redirect( $url ) {
	if ( ! empty( $_REQUEST['cham_buy_now'] ) && function_exists( 'wc_get_checkout_url' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return wc_get_checkout_url();
	}

	return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'cham_buy_now_redirect' );

add_filter( 'woocommerce_enable_order_notes_field', '__return_true' );
add_filter( 'pre_option_woocommerce_enable_order_comments', '__return_true' );

function cham_checkout_order_notes_field( $fields ) {
	if ( isset( $fields['order']['order_comments'] ) ) {
		return $fields;
	}

	$fields['order']['order_comments'] = array(
		'type'        => 'textarea',
		'class'       => array( 'form-row-wide' ),
		'label'       => __( 'Ghi chú đơn hàng', 'cham-theme' ),
		'placeholder' => __( 'Ghi chú về thời gian nhận hàng hoặc yêu cầu khác.', 'cham-theme' ),
		'required'    => false,
		'priority'    => 10,
	);

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'cham_checkout_order_notes_field' );

add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 1000 );
add_filter( 'pre_option_woocommerce_enable_order_comments', '__return_false', 1000 );
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );

function cham_checkout_fields( $fields ) {
	$coolbird_options = get_option( 'coolviad_woo_district', array() );
	$address_schema   = is_array( $coolbird_options ) && ! empty( $coolbird_options['address_schema'] ) ? $coolbird_options['address_schema'] : 'new';

	unset(
		$fields['billing']['billing_first_name'],
		$fields['billing']['billing_company'],
		$fields['billing']['billing_country'],
		$fields['billing']['billing_email'],
		$fields['billing']['billing_postcode'],
		$fields['shipping'],
		$fields['order']['order_comments']
	);

	if ( 'new' === $address_schema ) {
		unset( $fields['billing']['billing_address_2'] );
	}

	if ( isset( $fields['billing']['billing_last_name'] ) ) {
		$fields['billing']['billing_last_name']['label']       = __( 'Họ và tên', 'cham-theme' );
		$fields['billing']['billing_last_name']['placeholder'] = __( 'Nhập họ và tên', 'cham-theme' );
		$fields['billing']['billing_last_name']['class']       = array( 'form-row-wide' );
		$fields['billing']['billing_last_name']['required']    = true;
		$fields['billing']['billing_last_name']['priority']    = 10;
	}

	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['label']       = __( 'Số điện thoại', 'cham-theme' );
		$fields['billing']['billing_phone']['placeholder'] = __( 'Nhập số điện thoại', 'cham-theme' );
		$fields['billing']['billing_phone']['class']       = array( 'form-row-wide' );
		$fields['billing']['billing_phone']['required']    = true;
		$fields['billing']['billing_phone']['priority']    = 20;
	}

	if ( isset( $fields['billing']['billing_state'] ) ) {
		$fields['billing']['billing_state']['label']    = __( 'Tỉnh/Thành phố', 'cham-theme' );
		$fields['billing']['billing_state']['class']    = 'new' === $address_schema ? array( 'form-row-wide', 'address-field', 'update_totals_on_change' ) : array( 'form-row-first', 'address-field', 'update_totals_on_change' );
		$fields['billing']['billing_state']['required'] = true;
		$fields['billing']['billing_state']['priority'] = 30;
	}

	if ( isset( $fields['billing']['billing_city'] ) ) {
		$fields['billing']['billing_city']['label']    = 'new' === $address_schema ? __( 'Phường/Xã', 'cham-theme' ) : __( 'Quận/Huyện', 'cham-theme' );
		$fields['billing']['billing_city']['class']    = 'new' === $address_schema ? array( 'form-row-wide', 'address-field' ) : array( 'form-row-last', 'address-field' );
		$fields['billing']['billing_city']['required'] = true;
		$fields['billing']['billing_city']['priority'] = 40;
	}

	if ( 'old' === $address_schema && isset( $fields['billing']['billing_address_2'] ) ) {
		$fields['billing']['billing_address_2']['label']    = __( 'Phường/Xã', 'cham-theme' );
		$fields['billing']['billing_address_2']['class']    = array( 'form-row-wide', 'address-field' );
		$fields['billing']['billing_address_2']['required'] = true;
		$fields['billing']['billing_address_2']['priority'] = 50;
	}

	if ( isset( $fields['billing']['billing_address_1'] ) ) {
		$fields['billing']['billing_address_1']['label']       = __( 'Địa chỉ cụ thể', 'cham-theme' );
		$fields['billing']['billing_address_1']['placeholder'] = __( 'Số nhà, tên đường', 'cham-theme' );
		$fields['billing']['billing_address_1']['class']       = array( 'form-row-wide', 'address-field' );
		$fields['billing']['billing_address_1']['required']    = true;
		$fields['billing']['billing_address_1']['priority']    = 60;
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'cham_checkout_fields', 1000001 );

function cham_copy_billing_to_shipping_address( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return;
	}

	$order->set_shipping_first_name( $order->get_billing_first_name() );
	$order->set_shipping_last_name( $order->get_billing_last_name() );
	$order->set_shipping_company( '' );
	$order->set_shipping_country( $order->get_billing_country() ? $order->get_billing_country() : 'VN' );
	$order->set_shipping_address_1( $order->get_billing_address_1() );
	$order->set_shipping_address_2( $order->get_billing_address_2() );
	$order->set_shipping_city( $order->get_billing_city() );
	$order->set_shipping_state( $order->get_billing_state() );
	$order->set_shipping_postcode( $order->get_billing_postcode() );

	if ( method_exists( $order, 'set_shipping_phone' ) ) {
		$order->set_shipping_phone( $order->get_billing_phone() );
	} else {
		$order->update_meta_data( '_shipping_phone', $order->get_billing_phone() );
	}
}
add_action( 'woocommerce_checkout_create_order', 'cham_copy_billing_to_shipping_address', 20 );

function cham_woocommerce_order_received_title() {
	return __( 'Đặt hàng thành công', 'cham-theme' );
}
add_filter( 'woocommerce_endpoint_order-received_title', 'cham_woocommerce_order_received_title' );

function cham_woocommerce_order_received_text( $text, $order ) {
	if ( $order ) {
		return __( 'Cảm ơn bạn. Đơn hàng của bạn đã được tiếp nhận.', 'cham-theme' );
	}

	return $text;
}
add_filter( 'woocommerce_thankyou_order_received_text', 'cham_woocommerce_order_received_text', 10, 2 );

function cham_woocommerce_shipped_via_text( $html, $order ) {
	$method = $order ? $order->get_shipping_method() : '';

	if ( ! $method ) {
		return '';
	}

	return '&nbsp;<small class="shipped_via">- ' . esc_html( $method ) . '</small>';
}
add_filter( 'woocommerce_order_shipping_to_display_shipped_via', 'cham_woocommerce_shipped_via_text', 10, 2 );

function cham_woocommerce_frontend_texts( $translation, $text, $domain ) {
	if ( 'woocommerce' !== $domain || ( is_admin() && ! wp_doing_ajax() ) ) {
		return $translation;
	}

	$texts = array(
		'Order received'                           => 'Đặt hàng thành công',
		'Thank you. Your order has been received.' => 'Cảm ơn bạn. Đơn hàng của bạn đã được tiếp nhận.',
		'Order number:'                           => 'Mã đơn hàng:',
		'Date:'                                   => 'Ngày đặt:',
		'Total:'                                  => 'Tổng cộng:',
		'Payment method:'                         => 'Phương thức thanh toán:',
		'Order details'                           => 'Chi tiết đơn hàng',
		'Product'                                 => 'Sản phẩm',
		'Subtotal:'                               => 'Tạm tính:',
		'Shipping:'                               => 'Phí giao hàng:',
		'Billing address'                         => 'Địa chỉ thanh toán',
		'Shipping address'                        => 'Địa chỉ giao hàng',
		'Email address'                           => 'Email',
		'Phone'                                   => 'Số điện thoại',
	);

	return isset( $texts[ $text ] ) ? $texts[ $text ] : $translation;
}
add_filter( 'gettext', 'cham_woocommerce_frontend_texts', 10, 3 );

function cham_checkout_frontend_texts( $translation, $text, $domain ) {
	if ( 'woocommerce' !== $domain || ( is_admin() && ! wp_doing_ajax() ) ) {
		return $translation;
	}

	$texts = array(
		'Billing details' => 'Thông tin giao hàng',
		'Your order'      => 'Tóm tắt đơn hàng',
		'Place order'     => 'Đặt hàng',
	);

	return isset( $texts[ $text ] ) ? $texts[ $text ] : $translation;
}
add_filter( 'gettext', 'cham_checkout_frontend_texts', 20, 3 );

function cham_woocommerce_sale_flash() {
	return '<span class="onsale">' . esc_html__( 'Giảm giá', 'cham-theme' ) . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'cham_woocommerce_sale_flash' );

function cham_variable_price_lowest_only( $price, $product ) {
	if ( ! is_product() ) {
		return $price;
	}

	if ( ! $product instanceof WC_Product_Variable ) {
		return $price;
	}

	$prices = $product->get_variation_prices( true );

	if ( empty( $prices['price'] ) ) {
		return $price;
	}

	$min_variation_id = array_search( min( $prices['price'] ), $prices['price'], true );
	$min_price        = $prices['price'][ $min_variation_id ];
	$regular_prices   = ! empty( $prices['regular_price'] ) ? $prices['regular_price'] : array();
	$regular_price    = isset( $regular_prices[ $min_variation_id ] ) ? $regular_prices[ $min_variation_id ] : $min_price;

	if ( $regular_price > $min_price ) {
		return wc_format_sale_price( wc_price( $regular_price ), wc_price( $min_price ) );
	}

	return wc_price( $min_price );
}
add_filter( 'woocommerce_variable_price_html', 'cham_variable_price_lowest_only', 10, 2 );

function cham_woocommerce_add_to_cart_text() {
	return __( 'Thêm vào giỏ hàng', 'cham-theme' );
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'cham_woocommerce_add_to_cart_text' );
add_filter( 'woocommerce_product_add_to_cart_text', 'cham_woocommerce_add_to_cart_text' );

function cham_woocommerce_variation_dropdown_args( $args ) {
	$args['show_option_none'] = __( 'Chọn tùy chọn', 'cham-theme' );
	return $args;
}
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'cham_woocommerce_variation_dropdown_args' );

function cham_woocommerce_product_tabs( $tabs ) {
	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['title'] = __( 'Mô tả', 'cham-theme' );
	}

	if ( isset( $tabs['additional_information'] ) ) {
		$tabs['additional_information']['title'] = __( 'Thông tin thêm', 'cham-theme' );
	}

	if ( isset( $tabs['reviews'] ) ) {
		$count = get_comments_number();
		$tabs['reviews']['title'] = sprintf(
			/* translators: %d: review count. */
			__( 'Đánh giá (%d)', 'cham-theme' ),
			$count
		);
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'cham_woocommerce_product_tabs' );

function cham_woocommerce_description_heading() {
	return __( 'Mô tả sản phẩm', 'cham-theme' );
}
add_filter( 'woocommerce_product_description_heading', 'cham_woocommerce_description_heading' );

function cham_woocommerce_additional_info_heading() {
	return __( 'Thông tin thêm', 'cham-theme' );
}
add_filter( 'woocommerce_product_additional_information_heading', 'cham_woocommerce_additional_info_heading' );

function cham_related_products_heading() {
	return __( 'Sản phẩm liên quan', 'cham-theme' );
}
add_filter( 'woocommerce_product_related_products_heading', 'cham_related_products_heading' );
