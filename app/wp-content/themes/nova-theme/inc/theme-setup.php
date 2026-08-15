<?php
/**
 * Theme setup and asset loading.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function nova_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 180,
		'width'       => 420,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus( array(
		'primary' => __( 'Primary menu', 'nova-theme' ),
		'footer'  => __( 'Footer menu', 'nova-theme' ),
	) );
}
add_action( 'after_setup_theme', 'nova_theme_setup' );

function nova_theme_enqueue_assets() {
	wp_enqueue_style(
		'nova-fonts',
		'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'nova-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'nova-fonts' ),
		(string) filemtime( get_theme_file_path( '/assets/css/main.css' ) )
	);

	$header_footer_stylesheet = get_theme_file_path( '/assets/css/header-footer-v2.css' );
	wp_enqueue_style(
		'nova-header-footer-v2',
		get_theme_file_uri( '/assets/css/header-footer-v2.css' ),
		array( 'nova-main' ),
		file_exists( $header_footer_stylesheet ) ? (string) filemtime( $header_footer_stylesheet ) : NOVA_THEME_VERSION
	);

	$catalog_stylesheet = get_theme_file_path( '/assets/css/catalog-v2.css' );
	if ( file_exists( $catalog_stylesheet ) ) {
		wp_enqueue_style(
			'nova-catalog-v2',
			get_theme_file_uri( '/assets/css/catalog-v2.css' ),
			array( 'nova-header-footer-v2' ),
			(string) filemtime( $catalog_stylesheet )
		);
	}

	wp_enqueue_script(
		'nova-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery' ),
		NOVA_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'nova_theme_enqueue_assets' );

function nova_body_classes( $classes ) {
	$classes[] = 'nova-site';
	return $classes;
}
add_filter( 'body_class', 'nova_body_classes' );
