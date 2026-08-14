<?php
/**
 * Customizer settings.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function nova_theme_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'nova_contact', array(
		'title'    => __( 'Nova Store - Liên hệ', 'nova-theme' ),
		'priority' => 30,
	) );

	$wp_customize->add_section( 'nova_homepage', array(
		'title'    => __( 'Nova Store - Trang chủ', 'nova-theme' ),
		'priority' => 31,
	) );

	$fields = array(
		'nova_hotline' => array(
			'label'   => __( 'Hotline', 'nova-theme' ),
			'default' => '0909 000 999',
		),
		'nova_zalo_url' => array(
			'label'   => __( 'Zalo URL', 'nova-theme' ),
			'default' => 'https://zalo.me/0909000999',
		),
		'nova_address' => array(
			'label'   => __( 'Address', 'nova-theme' ),
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
			'section' => 'nova_contact',
			'type'    => 'text',
		) );
	}

	for ( $banner_index = 1; $banner_index <= 5; $banner_index++ ) {
		$image_setting = 1 === $banner_index ? 'nova_banner_image' : 'nova_banner_image_' . $banner_index;
		$link_setting  = 1 === $banner_index ? 'nova_banner_link' : 'nova_banner_link_' . $banner_index;

		$wp_customize->add_setting( $image_setting, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$image_setting,
				array(
					'label'       => sprintf( __( 'Banner %d image', 'nova-theme' ), $banner_index ),
					'description' => 1 === $banner_index ? __( 'Add one image for a static banner, or add more images to turn this area into a looping banner.', 'nova-theme' ) : '',
					'section'     => 'nova_homepage',
				)
			)
		);

		$wp_customize->add_setting( $link_setting, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( $link_setting, array(
			'label'       => sprintf( __( 'Banner %d link', 'nova-theme' ), $banner_index ),
			'description' => 1 === $banner_index ? __( 'Optional URL opened when customers click the homepage banner.', 'nova-theme' ) : '',
			'section'     => 'nova_homepage',
			'type'        => 'url',
		) );
	}

	$wp_customize->add_setting( 'nova_home_category_limit', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'nova_home_category_limit', array(
		'label'       => __( 'Home category limit', 'nova-theme' ),
		'description' => __( 'Maximum number of product categories shown on the homepage.', 'nova-theme' ),
		'section'     => 'nova_homepage',
		'type'        => 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12,
		),
	) );

	$wp_customize->add_setting( 'nova_home_products_per_category', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'nova_home_products_per_category', array(
		'label'       => __( 'Products per home category', 'nova-theme' ),
		'description' => __( 'Maximum number of products shown under each homepage category.', 'nova-theme' ),
		'section'     => 'nova_homepage',
		'type'        => 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12,
		),
	) );
}
add_action( 'customize_register', 'nova_theme_customize_register' );
