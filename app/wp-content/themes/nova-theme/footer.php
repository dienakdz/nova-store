<?php
/**
 * Site footer.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="site-footer">
	<div class="nova-container footer-grid">
		<div class="footer-brand">
			<?php nova_site_logo( 'footer-logo' ); ?>
			<p><?php esc_html_e( 'Nova Store mang đến trải nghiệm mua sắm thời trang hiện đại, dễ chọn và phù hợp phong cách hằng ngày.', 'nova-theme' ); ?></p>
		</div>
		<div>
			<h2><?php esc_html_e( 'Mua hàng', 'nova-theme' ); ?></h2>
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'fallback_cb'    => false,
				) );
			} else {
				echo '<ul class="footer-menu">';
				echo '<li><a href="' . esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ) . '">' . esc_html__( 'Cửa hàng', 'nova-theme' ) . '</a></li>';
				echo '<li><a href="' . esc_url( nova_cart_link() ) . '">' . esc_html__( 'Giỏ hàng', 'nova-theme' ) . '</a></li>';
				echo '</ul>';
			}
			?>
		</div>
		<div>
			<h2><?php esc_html_e( 'Liên hệ', 'nova-theme' ); ?></h2>
			<ul class="footer-menu">
				<li><a href="<?php echo esc_url( nova_get_hotline_href() ); ?>"><?php echo esc_html( nova_get_hotline() ); ?></a></li>
				<li><a href="<?php echo esc_url( nova_get_zalo_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Nhắn Zalo', 'nova-theme' ); ?></a></li>
				<li><?php echo esc_html( nova_get_address() ); ?></li>
			</ul>
		</div>
		<div>
			<h2><?php esc_html_e( 'Thanh toán', 'nova-theme' ); ?></h2>
			<p><?php esc_html_e( 'Hỗ trợ COD và chuyển khoản ngân hàng theo cấu hình WooCommerce hiện tại.', 'nova-theme' ); ?></p>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="nova-container">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
