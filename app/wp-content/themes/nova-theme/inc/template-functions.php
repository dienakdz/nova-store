<?php
/**
 * Template helpers.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function nova_get_hotline() {
	return get_theme_mod( 'nova_hotline', '0909 000 999' );
}

function nova_get_hotline_href() {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', nova_get_hotline() );
}

function nova_get_zalo_url() {
	return get_theme_mod( 'nova_zalo_url', 'https://zalo.me/0909000999' );
}

function nova_get_address() {
	return get_theme_mod( 'nova_address', 'TP. Ho Chi Minh, Viet Nam' );
}

function nova_get_banner_image_url() {
	return get_theme_mod( 'nova_banner_image', '' );
}

function nova_get_banner_link_url() {
	return get_theme_mod( 'nova_banner_link', '' );
}

function nova_get_home_banners() {
	$banners = array();

	for ( $banner_index = 1; $banner_index <= 5; $banner_index++ ) {
		$image_setting = 1 === $banner_index ? 'nova_banner_image' : 'nova_banner_image_' . $banner_index;
		$link_setting  = 1 === $banner_index ? 'nova_banner_link' : 'nova_banner_link_' . $banner_index;
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

function nova_get_home_category_limit() {
	return max( 1, min( 12, absint( get_theme_mod( 'nova_home_category_limit', 4 ) ) ) );
}

function nova_get_home_products_per_category() {
	return max( 1, min( 12, absint( get_theme_mod( 'nova_home_products_per_category', 4 ) ) ) );
}

function nova_site_logo( $class = '' ) {
	$classes = trim( 'nova-site-logo ' . $class );

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

function nova_get_menu_icon_svg( $label ) {
	$key = strtolower( remove_accents( wp_strip_all_tags( $label ) ) );
	$key = preg_replace( '/\s+/', ' ', trim( $key ) );

	$icons = array(
		'trang chu' => '<svg viewBox="0 0 24 24"><path d="M3 11.5 12 4l9 7.5v8a1 1 0 0 1-1 1h-5.4v-5.7H9.4v5.7H4a1 1 0 0 1-1-1v-8z"/></svg>',
		'cua hang' => '<svg viewBox="0 0 24 24"><path d="M5 4h14l1.5 6.2a3.3 3.3 0 0 1-5.2 3.1 3.3 3.3 0 0 1-5.3 0 3.3 3.3 0 0 1-5.5-2.5L5 4zm1 11.2c1 .3 2.1.1 3-.5.9.6 2.1.8 3 .4 1 .4 2.1.3 3-.4.8.6 1.9.8 3 .5V20H6v-4.8z"/></svg>',
		'quan ao'  => '<svg viewBox="0 0 24 24"><path d="m8 4 4 2 4-2 4 3-2.5 4-2-1.2V21h-7V9.8L6.5 11 4 7l4-3z"/></svg>',
		'phu kien' => '<svg viewBox="0 0 24 24"><path d="M7 7V6a5 5 0 0 1 10 0v1h3l1 14H3L4 7h3zm2 0h6V6a3 3 0 0 0-6 0v1z"/></svg>',
	);

	if ( empty( $icons[ $key ] ) ) {
		return '<svg viewBox="0 0 24 24"><path d="M12 3 3.5 7.5V17l8.5 4 8.5-4V7.5L12 3zm0 2.3 5.4 2.8-5.4 2.7-5.4-2.7L12 5.3zM5.5 10.1l5.5 2.8v5.4l-5.5-2.6v-5.6zm13 5.6-5.5 2.6v-5.4l5.5-2.8v5.6z"/></svg>';
	}

	return $icons[ $key ];
}

function nova_menu_item_label( $label ) {
	return '<span class="nova-menu-icon" aria-hidden="true">' . nova_get_menu_icon_svg( $label ) . '</span><span class="nova-menu-text">' . esc_html( $label ) . '</span>';
}

function nova_primary_menu_item_title( $title, $item, $args, $depth ) {
	if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location || 0 !== (int) $depth ) {
		return $title;
	}

	return '<span class="nova-menu-icon" aria-hidden="true">' . nova_get_menu_icon_svg( $item->title ) . '</span><span class="nova-menu-text">' . $title . '</span>';
}
add_filter( 'nav_menu_item_title', 'nova_primary_menu_item_title', 10, 4 );

function nova_primary_menu_fallback() {
	$items = array(
		array( __( 'Trang chủ', 'nova-theme' ), home_url( '/' ) ),
		array( __( 'Cửa hàng', 'nova-theme' ), function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ),
		array( __( 'Quần áo', 'nova-theme' ), nova_get_product_category_link_by_name( 'Quần áo' ) ),
		array( __( 'Phụ kiện', 'nova-theme' ), nova_get_product_category_link_by_name( 'Phụ kiện' ) ),
	);

	echo '<ul class="nova-menu">';
	foreach ( $items as $item ) {
		echo '<li><a href="' . esc_url( $item[1] ) . '">' . nova_menu_item_label( $item[0] ) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</ul>';
}

function nova_get_product_category_link_by_name( $name ) {
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

function nova_product_card( $product_id = null ) {
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

function nova_home_product_categories( $limit = 4 ) {
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

function nova_product_category_query( $term_id, $posts_per_page = 4 ) {
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

function nova_feature_items() {
	return array(
		array( 'icon' => 'shirt', 'title' => __( 'Sản phẩm dễ chọn', 'nova-theme' ), 'text' => __( 'Thông tin chất liệu, màu sắc và kích thước được trình bày rõ ràng trên từng sản phẩm.', 'nova-theme' ) ),
		array( 'icon' => 'truck', 'title' => __( 'Giao hàng rõ ràng', 'nova-theme' ), 'text' => __( 'Quy trình đặt hàng đơn giản, hỗ trợ COD và chuyển khoản ngân hàng.', 'nova-theme' ) ),
		array( 'icon' => 'phone', 'title' => __( 'Tư vấn nhanh', 'nova-theme' ), 'text' => __( 'Hotline và Zalo luôn hiện ở các điểm mua hàng quan trọng.', 'nova-theme' ) ),
	);
}
