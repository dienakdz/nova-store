<?php
/**
 * Customizer settings.
 *
 * @package ChamTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cham_theme_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'cham_contact', array(
		'title'    => __( 'Chạm Coffee - Liên hệ', 'cham-theme' ),
		'priority' => 30,
	) );

	$wp_customize->add_section( 'cham_homepage', array(
		'title'    => __( 'Chạm Coffee - Trang chủ', 'cham-theme' ),
		'priority' => 31,
	) );

	$fields = array(
		'cham_hotline' => array(
			'label'   => __( 'Hotline', 'cham-theme' ),
			'default' => '0909 000 999',
		),
		'cham_zalo_url' => array(
			'label'   => __( 'Zalo URL', 'cham-theme' ),
			'default' => 'https://zalo.me/0909000999',
		),
		'cham_address' => array(
			'label'   => __( 'Address', 'cham-theme' ),
			'default' => 'TP. Ho Chi Minh, Viet Nam',
		),
	);

	foreach ( $fields as $setting => $field ) {
		$wp_customize->add_setting( $setting, array(
			'default'           => $field['default'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $setting, array(
			'label'   => $field['label'],
			'section' => 'cham_contact',
			'type'    => 'text',
		) );
	}

	for ( $banner_index = 1; $banner_index <= 5; $banner_index++ ) {
		$image_setting = 1 === $banner_index ? 'cham_banner_image' : 'cham_banner_image_' . $banner_index;
		$link_setting  = 1 === $banner_index ? 'cham_banner_link' : 'cham_banner_link_' . $banner_index;

		$wp_customize->add_setting( $image_setting, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$image_setting,
				array(
					'label'       => sprintf( __( 'Banner %d image', 'cham-theme' ), $banner_index ),
					'description' => 1 === $banner_index ? __( 'Add one image for a static banner, or add more images to turn this area into a looping banner.', 'cham-theme' ) : '',
					'section'     => 'cham_homepage',
				)
			)
		);

		$wp_customize->add_setting( $link_setting, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( $link_setting, array(
			'label'       => sprintf( __( 'Banner %d link', 'cham-theme' ), $banner_index ),
			'description' => 1 === $banner_index ? __( 'Optional URL opened when customers click the homepage banner.', 'cham-theme' ) : '',
			'section'     => 'cham_homepage',
			'type'        => 'url',
		) );
	}

	$wp_customize->add_setting( 'cham_home_category_limit', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'cham_home_category_limit', array(
		'label'       => __( 'Home category limit', 'cham-theme' ),
		'description' => __( 'Maximum number of product categories shown on the homepage.', 'cham-theme' ),
		'section'     => 'cham_homepage',
		'type'        => 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12,
		),
	) );

	$wp_customize->add_setting( 'cham_home_products_per_category', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'cham_home_products_per_category', array(
		'label'       => __( 'Products per home category', 'cham-theme' ),
		'description' => __( 'Maximum number of products shown under each homepage category.', 'cham-theme' ),
		'section'     => 'cham_homepage',
		'type'        => 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12,
		),
	) );
}
add_action( 'customize_register', 'cham_theme_customize_register' );
