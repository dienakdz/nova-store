<?php
/**
 * Theme setup and asset loading.
 *
 * @package ChamTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cham_theme_setup() {
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
		'primary' => __( 'Primary menu', 'cham-theme' ),
		'footer'  => __( 'Footer menu', 'cham-theme' ),
	) );
}
add_action( 'after_setup_theme', 'cham_theme_setup' );

function cham_theme_enqueue_assets() {
	wp_enqueue_style(
		'cham-fonts',
		'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'cham-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'cham-fonts' ),
		CHAM_THEME_VERSION
	);

	wp_enqueue_script(
		'cham-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery' ),
		CHAM_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'cham_theme_enqueue_assets' );

function cham_body_classes( $classes ) {
	$classes[] = 'cham-site';
	return $classes;
}
add_filter( 'body_class', 'cham_body_classes' );
