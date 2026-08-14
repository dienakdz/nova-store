<?php
/**
 * Site header.
 *
 * @package ChamTheme
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
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'cham-theme' ); ?></a>

<header class="site-header">
	<div class="top-bar">
		<div class="cham-container top-bar-inner">
			<a class="top-bar-hotline" href="<?php echo esc_url( cham_get_hotline_href() ); ?>">
				<span class="action-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.5 3 3.6 5.1 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.3 1.3.4 2.7.6 4.1.6.7 0 1.2.5 1.2 1.2v3.5c0 .7-.5 1.2-1.2 1.2C10.3 22 2 13.7 2 3.3 2 2.5 2.5 2 3.3 2h3.5C7.5 2 8 2.5 8 3.3c0 1.4.2 2.8.6 4.1.1.4 0 .9-.3 1.2l-2.1 2.2z"/></svg>
				</span>
				<span><?php esc_html_e( 'Gọi hotline:', 'cham-theme' ); ?> <?php echo esc_html( cham_get_hotline() ); ?></span>
			</a>
			<a class="top-bar-cart" href="<?php echo esc_url( cham_cart_link() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'cham-theme' ); ?>">
				<span class="action-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24"><path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM7.2 14h7.4c.8 0 1.5-.4 1.9-1.1L21 4H5.2L4.6 2H1v2h2.1l3.6 11.1c.2.6.8.9 1.4.9H19v-2H7.2z"/></svg>
				</span>
				<span><?php esc_html_e( 'Giỏ hàng', 'cham-theme' ); ?></span>
				<span class="cham-cart-count"><?php echo esc_html( cham_cart_count() ); ?></span>
			</a>
		</div>
	</div>

	<div class="cham-container header-main">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php cham_site_logo( 'brand-logo' ); ?>
		</a>

		<button class="menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false">
			<span></span><span></span><span></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'cham-theme' ); ?></span>
		</button>

		<nav class="main-nav" id="primary-menu" aria-label="<?php esc_attr_e( 'Primary menu', 'cham-theme' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'cham-menu',
					'fallback_cb'    => false,
				) );
			} else {
				cham_primary_menu_fallback();
			}
			?>
		</nav>
	</div>
</header>
