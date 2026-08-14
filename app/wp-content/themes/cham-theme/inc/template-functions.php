<?php
/**
 * Template helpers.
 *
 * @package ChamTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cham_get_hotline() {
	return get_theme_mod( 'cham_hotline', '0909 000 999' );
}

function cham_get_hotline_href() {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', cham_get_hotline() );
}

function cham_get_zalo_url() {
	return get_theme_mod( 'cham_zalo_url', 'https://zalo.me/0909000999' );
}

function cham_get_address() {
	return get_theme_mod( 'cham_address', 'TP. Ho Chi Minh, Viet Nam' );
}

function cham_get_banner_image_url() {
	return get_theme_mod( 'cham_banner_image', '' );
}

function cham_get_banner_link_url() {
	return get_theme_mod( 'cham_banner_link', '' );
}

function cham_get_home_banners() {
	$banners = array();

	for ( $banner_index = 1; $banner_index <= 5; $banner_index++ ) {
		$image_setting = 1 === $banner_index ? 'cham_banner_image' : 'cham_banner_image_' . $banner_index;
		$link_setting  = 1 === $banner_index ? 'cham_banner_link' : 'cham_banner_link_' . $banner_index;
		$image         = get_theme_mod( $image_setting, '' );

		if ( ! $image ) {
			continue;
		}

		$banners[] = array(
			'image' => $image,
			'link'  => get_theme_mod( $link_setting, '' ),
		);
	}

	return $banners;
}

function cham_get_home_category_limit() {
	return max( 1, min( 12, absint( get_theme_mod( 'cham_home_category_limit', 4 ) ) ) );
}

function cham_get_home_products_per_category() {
	return max( 1, min( 12, absint( get_theme_mod( 'cham_home_products_per_category', 4 ) ) ) );
}

function cham_site_logo( $class = '' ) {
	$classes = trim( 'cham-site-logo ' . $class );

	if ( has_custom_logo() ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo           = wp_get_attachment_image(
			$custom_logo_id,
			'full',
			false,
			array(
				'class' => $classes,
				'alt'   => get_bloginfo( 'name' ),
			)
		);

		if ( $logo ) {
			echo wp_kses_post( $logo );
			return;
		}
	}

	echo '<img class="' . esc_attr( $classes ) . '" src="' . esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
}

function cham_get_menu_icon_svg( $label ) {
	$key = strtolower( remove_accents( wp_strip_all_tags( $label ) ) );
	$key = preg_replace( '/\s+/', ' ', trim( $key ) );

	$icons = array(
		'trang chu' => '<svg viewBox="0 0 24 24"><path d="M3 11.5 12 4l9 7.5v8a1 1 0 0 1-1 1h-5.4v-5.7H9.4v5.7H4a1 1 0 0 1-1-1v-8z"/></svg>',
		'cua hang' => '<svg viewBox="0 0 24 24"><path d="M5 4h14l1.5 6.2a3.3 3.3 0 0 1-5.2 3.1 3.3 3.3 0 0 1-5.3 0 3.3 3.3 0 0 1-5.5-2.5L5 4zm1 11.2c1 .3 2.1.1 3-.5.9.6 2.1.8 3 .4 1 .4 2.1.3 3-.4.8.6 1.9.8 3 .5V20H6v-4.8z"/></svg>',
		'ca phe'   => '<svg viewBox="0 0 24 24"><path d="M5 7h11v5.5A5.5 5.5 0 0 1 10.5 18 5.5 5.5 0 0 1 5 12.5V7zm12 1h1.4a2.6 2.6 0 0 1 0 5.2H17V8zM4 20h14v2H4v-2zM8.5 2.4c1.1.8 1.1 2 0 2.8-.6-.6-.6-1.8 0-2.8zm4 0c1.1.8 1.1 2 0 2.8-.6-.6-.6-1.8 0-2.8z"/></svg>',
		'may pha'  => '<svg viewBox="0 0 24 24"><path d="M5 3h12a2 2 0 0 1 2 2v12H5V3zm3 3v4h8V6H8zm0 7h4v2H8v-2zm-3 6h14v2H5v-2z"/></svg>',
		'may xay'  => '<svg viewBox="0 0 24 24"><path d="M8 2h8l-1 6h-6L8 2zm1 8h6l2 10H7l2-10zm2 3v4h2v-4h-2z"/></svg>',
		'phu kien' => '<svg viewBox="0 0 24 24"><path d="M4 8.5 12 4l8 4.5v7L12 20l-8-4.5v-7zm8 1.2 4.3-2.4L12 4.9 7.7 7.3 12 9.7zm-6 1.1v3.6l5 2.8v-3.6l-5-2.8zm12 0-5 2.8v3.6l5-2.8v-3.6z"/></svg>',
	);

	if ( empty( $icons[ $key ] ) ) {
		return '<svg viewBox="0 0 24 24"><path d="M12 3 3.5 7.5V17l8.5 4 8.5-4V7.5L12 3zm0 2.3 5.4 2.8-5.4 2.7-5.4-2.7L12 5.3zM5.5 10.1l5.5 2.8v5.4l-5.5-2.6v-5.6zm13 5.6-5.5 2.6v-5.4l5.5-2.8v5.6z"/></svg>';
	}

	return $icons[ $key ];
}

function cham_menu_item_label( $label ) {
	return '<span class="cham-menu-icon" aria-hidden="true">' . cham_get_menu_icon_svg( $label ) . '</span><span class="cham-menu-text">' . esc_html( $label ) . '</span>';
}

function cham_primary_menu_item_title( $title, $item, $args, $depth ) {
	if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location || 0 !== (int) $depth ) {
		return $title;
	}

	return '<span class="cham-menu-icon" aria-hidden="true">' . cham_get_menu_icon_svg( $item->title ) . '</span><span class="cham-menu-text">' . $title . '</span>';
}
add_filter( 'nav_menu_item_title', 'cham_primary_menu_item_title', 10, 4 );

function cham_primary_menu_fallback() {
	$items = array(
		array( __( 'Trang chủ', 'cham-theme' ), home_url( '/' ) ),
		array( __( 'Cửa hàng', 'cham-theme' ), function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ),
		array( __( 'Cà phê', 'cham-theme' ), cham_get_product_category_link_by_name( 'Sản phẩm cà phê' ) ),
		array( __( 'Máy pha', 'cham-theme' ), cham_get_product_category_link_by_name( 'Sản phẩm máy pha cà phê' ) ),
	);

	echo '<ul class="cham-menu">';
	foreach ( $items as $item ) {
		echo '<li><a href="' . esc_url( $item[1] ) . '">' . cham_menu_item_label( $item[0] ) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</ul>';
}

function cham_get_product_category_link_by_name( $name ) {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return home_url( '/' );
	}

	$term = get_term_by( 'name', $name, 'product_cat' );
	if ( ! $term || is_wp_error( $term ) ) {
		return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	}

	$link = get_term_link( $term );
	if ( is_wp_error( $link ) ) {
		return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	}

	return $link;
}

function cham_product_card( $product_id = null ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	$product = wc_get_product( $product_id ? $product_id : get_the_ID() );
	if ( ! $product ) {
		return;
	}

	$GLOBALS['product'] = $product;
	get_template_part( 'template-parts/product-card', null, array( 'product' => $product ) );
}

function cham_home_product_categories( $limit = 4 ) {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
		'number'     => $limit,
	) );

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	return $terms;
}

function cham_product_category_query( $term_id, $posts_per_page = 4 ) {
	return new WP_Query( array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => $posts_per_page,
		'ignore_sticky_posts' => true,
		'orderby'             => 'menu_order title',
		'order'               => 'ASC',
		'tax_query'           => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => array( 'exclude-from-catalog' ),
				'operator' => 'NOT IN',
			),
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => array( absint( $term_id ) ),
			),
		),
	) );
}

function cham_feature_items() {
	return array(
		array( 'icon' => 'bean', 'title' => __( 'Cà phê chọn lọc', 'cham-theme' ), 'text' => __( 'Tập trung vào hương vị ổn định, dễ pha và phù hợp gu thưởng thức hằng ngày.', 'cham-theme' ) ),
		array( 'icon' => 'truck', 'title' => __( 'Giao hàng rõ ràng', 'cham-theme' ), 'text' => __( 'Quy trình đặt hàng đơn giản, hỗ trợ COD và chuyển khoản ngân hàng.', 'cham-theme' ) ),
		array( 'icon' => 'phone', 'title' => __( 'Tư vấn nhanh', 'cham-theme' ), 'text' => __( 'Hotline và Zalo luôn hiện ở các điểm mua hàng quan trọng.', 'cham-theme' ) ),
	);
}
