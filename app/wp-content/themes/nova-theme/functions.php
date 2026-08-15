<?php
/**
 * Nova Store theme functions.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOVA_THEME_VERSION', '1.1.0' );

$nova_theme_includes = array(
	'inc/theme-setup.php',
	'inc/customizer.php',
	'inc/template-functions.php',
	'inc/woocommerce.php',
);

foreach ( $nova_theme_includes as $nova_theme_file ) {
	require_once get_template_directory() . '/' . $nova_theme_file;
}
