<?php
/**
 * Site header.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'nova-theme' ); ?></a>

<header class="site-header">
	<div class="announcement-bar">
		<div class="nova-container announcement-inner">
			<a class="announcement-hotline" href="<?php echo esc_url( nova_get_hotline_href() ); ?>">
				<?php esc_html_e( 'Hotline', 'nova-theme' ); ?> <?php echo esc_html( nova_get_hotline() ); ?>
			</a>
			<p><strong><?php esc_html_e( 'NEW SEASON', 'nova-theme' ); ?></strong> <?php esc_html_e( 'Miễn phí vận chuyển cho đơn hàng từ 499K', 'nova-theme' ); ?></p>
			<span><?php esc_html_e( 'Đổi trả trong 7 ngày', 'nova-theme' ); ?></span>
		</div>
	</div>

	<div class="nova-container header-main">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php nova_site_logo( 'brand-logo' ); ?>
		</a>

		<nav class="main-nav" id="primary-menu" aria-label="<?php esc_attr_e( 'Primary menu', 'nova-theme' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nova-menu',
					'fallback_cb'    => false,
				) );
			} else {
				nova_primary_menu_fallback();
			}
			?>
		</nav>

		<div class="header-actions">
			<form class="header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="nova-product-search"><?php esc_html_e( 'Tìm sản phẩm', 'nova-theme' ); ?></label>
				<input id="nova-product-search" type="search" name="s" placeholder="<?php esc_attr_e( 'Tìm kiếm', 'nova-theme' ); ?>">
				<input type="hidden" name="post_type" value="product">
				<button type="submit" aria-label="<?php esc_attr_e( 'Tìm kiếm', 'nova-theme' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m21 20-5.2-5.2a7.5 7.5 0 1 0-1 1L20 21l1-1ZM4.5 10a5.5 5.5 0 1 1 11 0 5.5 5.5 0 0 1-11 0Z"/></svg>
				</button>
			</form>
			<a class="header-cart" href="<?php echo esc_url( nova_cart_link() ); ?>" aria-label="<?php esc_attr_e( 'Giỏ hàng', 'nova-theme' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 18a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM2 3h2.5l2.1 10.5A3 3 0 0 0 9.5 16H18a3 3 0 0 0 2.8-2l1.8-5.5H6.2L5.7 6H2V3Z"/></svg>
				<span class="nova-cart-count"><?php echo esc_html( nova_cart_count() ); ?></span>
			</a>
		</div>

		<button class="menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
			<span></span><span></span><span></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'nova-theme' ); ?></span>
		</button>
	</div>
</header>
