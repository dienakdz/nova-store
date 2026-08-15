<?php
/**
 * Site footer.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nova_shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$nova_cart_url    = nova_cart_link();
$nova_account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$nova_blog_page   = absint( get_option( 'page_for_posts' ) );
$nova_blog_url    = $nova_blog_page ? get_permalink( $nova_blog_page ) : home_url( '/' );
$nova_socials     = array_filter(
	apply_filters(
		'nova_footer_social_links',
		array(
			'Facebook'  => get_theme_mod( 'nova_facebook_url', '' ),
			'Instagram' => get_theme_mod( 'nova_instagram_url', '' ),
			'TikTok'    => get_theme_mod( 'nova_tiktok_url', '' ),
			'YouTube'   => get_theme_mod( 'nova_youtube_url', '' ),
			'Zalo'      => nova_get_zalo_url(),
		)
	)
);
?>
<footer class="site-footer nova-footer-v2">
	<div class="nova-container footer-grid">
		<div class="footer-brand">
			<a class="footer-brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%s - Trang chủ', 'nova-theme' ), get_bloginfo( 'name' ) ) ); ?>">
				<?php nova_site_logo( 'footer-logo' ); ?>
			</a>
			<p><?php esc_html_e( 'Thời trang tối giản cho phong cách hiện đại. Chất lượng tạo nên khác biệt.', 'nova-theme' ); ?></p>
			<?php if ( $nova_socials ) : ?>
				<nav class="footer-socials" aria-label="<?php esc_attr_e( 'Mạng xã hội', 'nova-theme' ); ?>">
					<?php foreach ( $nova_socials as $nova_social_name => $nova_social_url ) : ?>
						<a href="<?php echo esc_url( $nova_social_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $nova_social_name ); ?>">
							<span aria-hidden="true"><?php echo esc_html( substr( $nova_social_name, 0, 2 ) ); ?></span>
						</a>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		</div>

		<div class="footer-column">
			<h2><?php esc_html_e( 'Thông tin', 'nova-theme' ); ?></h2>
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-menu',
						'fallback_cb'    => false,
					)
				);
				?>
			<?php else : ?>
				<ul class="footer-menu">
					<li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>"><?php esc_html_e( 'Về chúng tôi', 'nova-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $nova_shop_url ); ?>"><?php esc_html_e( 'Cửa hàng', 'nova-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $nova_blog_url ); ?>"><?php esc_html_e( 'Tin tức', 'nova-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( nova_get_hotline_href() ); ?>"><?php esc_html_e( 'Liên hệ', 'nova-theme' ); ?></a></li>
				</ul>
			<?php endif; ?>
		</div>

		<div class="footer-column">
			<h2><?php esc_html_e( 'Hỗ trợ', 'nova-theme' ); ?></h2>
			<ul class="footer-menu">
				<li><a href="<?php echo esc_url( $nova_account_url ); ?>"><?php esc_html_e( 'Tài khoản của tôi', 'nova-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( $nova_cart_url ); ?>"><?php esc_html_e( 'Giỏ hàng', 'nova-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( nova_get_hotline_href() ); ?>"><?php esc_html_e( 'Tư vấn mua hàng', 'nova-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( nova_get_zalo_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Hỗ trợ qua Zalo', 'nova-theme' ); ?></a></li>
			</ul>
		</div>

		<div class="footer-newsletter">
			<h2><?php esc_html_e( 'Đăng ký nhận tin', 'nova-theme' ); ?></h2>
			<p><?php esc_html_e( 'Nhận ưu đãi và cập nhật bộ sưu tập mới nhất.', 'nova-theme' ); ?></p>
			<form class="newsletter-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
				<label class="screen-reader-text" for="nova-newsletter-email"><?php esc_html_e( 'Email của bạn', 'nova-theme' ); ?></label>
				<input id="nova-newsletter-email" name="nova_newsletter_email" type="email" placeholder="<?php esc_attr_e( 'Nhập email của bạn', 'nova-theme' ); ?>" required autocomplete="email">
				<button type="submit" aria-label="<?php esc_attr_e( 'Đăng ký nhận tin', 'nova-theme' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h13M14 7l5 5-5 5"/></svg>
				</button>
			</form>
			<p class="footer-address"><?php echo esc_html( nova_get_address() ); ?></p>
		</div>
	</div>

	<div class="footer-bottom">
		<div class="nova-container footer-bottom-inner">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'nova-theme' ); ?></p>
			<ul class="footer-payments" aria-label="<?php esc_attr_e( 'Phương thức thanh toán', 'nova-theme' ); ?>">
				<li>VISA</li>
				<li>Mastercard</li>
				<li>COD</li>
				<li><?php esc_html_e( 'Chuyển khoản', 'nova-theme' ); ?></li>
			</ul>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
