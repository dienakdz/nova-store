<?php
/**
 * Chạm Coffee theme functions.
 *
 * @package ChamTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CHAM_THEME_VERSION', '1.0.1' );

$cham_theme_includes = array(
	'inc/theme-setup.php',
	'inc/customizer.php',
	'inc/template-functions.php',
	'inc/woocommerce.php',
);

foreach ( $cham_theme_includes as $cham_theme_file ) {
	require_once get_template_directory() . '/' . $cham_theme_file;
}
