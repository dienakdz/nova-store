<?php
/**
 * Site header.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nova_shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$nova_account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$nova_orders_url = function_exists( 'wc_get_endpoint_url' )
	? wc_get_endpoint_url( 'orders', '', $nova_account_url )
	: $nova_account_url;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Bỏ qua và đến nội dung', 'nova-theme' ); ?></a>

<header class="site-header nova-header-v2">
	<div class="nova-utility-bar">
		<div class="nova-container nova-utility-inner">
			<p class="nova-shipping-note">
				<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6.5h11v10H3zM14 10h3.8l3.2 3.3v3.2h-7zM7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm10 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
				<?php esc_html_e( 'Miễn phí giao hàng cho đơn từ 500.000đ', 'nova-theme' ); ?>
			</p>
			<nav class="nova-utility-links" aria-label="<?php esc_attr_e( 'Liên kết hỗ trợ', 'nova-theme' ); ?>">
				<a href="<?php echo esc_url( $nova_shop_url ); ?>"><?php esc_html_e( 'Cửa hàng', 'nova-theme' ); ?></a>
				<a href="<?php echo esc_url( $nova_orders_url ); ?>"><?php esc_html_e( 'Theo dõi đơn', 'nova-theme' ); ?></a>
				<a href="<?php echo esc_url( nova_get_hotline_href() ); ?>"><?php esc_html_e( 'Hỗ trợ', 'nova-theme' ); ?></a>
			</nav>
		</div>
	</div>

	<div class="nova-container header-main">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%s - Trang chủ', 'nova-theme' ), get_bloginfo( 'name' ) ) ); ?>">
			<?php nova_site_logo( 'brand-logo' ); ?>
		</a>

		<nav class="main-nav" id="primary-menu" aria-label="<?php esc_attr_e( 'Điều hướng chính', 'nova-theme' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nova-menu',
						'fallback_cb'    => false,
					)
				);
			} else {
				nova_primary_menu_fallback();
			}
			?>
		</nav>

		<div class="header-actions">
			<form class="header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="nova-product-search"><?php esc_html_e( 'Tìm sản phẩm', 'nova-theme' ); ?></label>
				<input id="nova-product-search" type="search" name="s" placeholder="<?php esc_attr_e( 'Tìm sản phẩm', 'nova-theme' ); ?>" autocomplete="off">
				<input type="hidden" name="post_type" value="product">
				<button type="submit" aria-label="<?php esc_attr_e( 'Tìm kiếm', 'nova-theme' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.2 4.2"/></svg>
				</button>
			</form>

			<a class="header-account nova-header-icon" href="<?php echo esc_url( $nova_account_url ); ?>" aria-label="<?php esc_attr_e( 'Tài khoản của tôi', 'nova-theme' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5.5 20v-1.7A5.5 5.5 0 0 1 11 12.8h2a5.5 5.5 0 0 1 5.5 5.5V20Z"/></svg>
			</a>

			<a class="header-cart nova-header-icon" href="<?php echo esc_url( nova_cart_link() ); ?>" aria-label="<?php esc_attr_e( 'Giỏ hàng', 'nova-theme' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5.5 8h13l1 12h-15l1-12Z"/><path d="M9 9V6.5a3 3 0 0 1 6 0V9"/></svg>
				<span class="nova-cart-count"><?php echo esc_html( nova_cart_count() ); ?></span>
			</a>
		</div>

		<button class="menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
			<span></span><span></span><span></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Mở menu', 'nova-theme' ); ?></span>
		</button>
	</div>
</header>
