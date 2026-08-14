<?php
/**
 * Product content in loops.
 *
 * @package NovaTheme
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

nova_product_card( $product->get_id() );

