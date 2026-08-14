<?php
/**
 * Front page template.
 *
 * @package ChamTheme
 */

get_header();

$shop_url              = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$home_banners          = cham_get_home_banners();
$home_categories       = cham_home_product_categories( cham_get_home_category_limit() );
$products_per_category = cham_get_home_products_per_category();
$feature_items         = cham_feature_items();
?>
<main id="primary" class="site-main">
	<section class="hero">
		<div class="cham-container">
			<?php if ( count( $home_banners ) > 1 ) : ?>
				<div class="home-banner home-banner-slider home-banner-has-image" data-banner-slider>
					<div class="home-banner-track">
						<?php foreach ( $home_banners as $index => $banner ) : ?>
							<?php if ( $banner['link'] ) : ?>
								<a class="home-banner-slide" href="<?php echo esc_url( $banner['link'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Banner khuyến mãi %d', 'cham-theme' ), $index + 1 ) ); ?>">
									<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Banner khuyến mãi %d', 'cham-theme' ), $index + 1 ) ); ?>">
								</a>
							<?php else : ?>
								<div class="home-banner-slide" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Banner khuyến mãi %d', 'cham-theme' ), $index + 1 ) ); ?>">
									<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Banner khuyến mãi %d', 'cham-theme' ), $index + 1 ) ); ?>">
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<button class="home-banner-control home-banner-prev" type="button" aria-label="<?php esc_attr_e( 'Banner trước', 'cham-theme' ); ?>"></button>
					<button class="home-banner-control home-banner-next" type="button" aria-label="<?php esc_attr_e( 'Banner tiếp theo', 'cham-theme' ); ?>"></button>
					<div class="home-banner-dots" aria-label="<?php esc_attr_e( 'Chọn banner', 'cham-theme' ); ?>">
						<?php foreach ( $home_banners as $index => $banner ) : ?>
							<button type="button" class="<?php echo 0 === $index ? 'is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Xem banner %d', 'cham-theme' ), $index + 1 ) ); ?>"></button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php elseif ( count( $home_banners ) === 1 ) : ?>
				<?php $banner = $home_banners[0]; ?>
				<?php if ( $banner['link'] ) : ?>
					<a class="home-banner home-banner-has-image" href="<?php echo esc_url( $banner['link'] ); ?>" aria-label="<?php esc_attr_e( 'Banner khuyến mãi', 'cham-theme' ); ?>">
						<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php esc_attr_e( 'Banner khuyến mãi', 'cham-theme' ); ?>">
					</a>
				<?php else : ?>
					<div class="home-banner home-banner-has-image" role="img" aria-label="<?php esc_attr_e( 'Banner khuyến mãi', 'cham-theme' ); ?>">
						<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php esc_attr_e( 'Banner khuyến mãi', 'cham-theme' ); ?>">
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="home-banner" role="img" aria-label="<?php esc_attr_e( 'Banner khuyến mãi', 'cham-theme' ); ?>"></div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $home_categories ) : ?>
		<?php foreach ( $home_categories as $index => $category ) : ?>
			<?php $category_query = cham_product_category_query( $category->term_id, $products_per_category ); ?>
			<?php if ( ! $category_query->have_posts() ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<?php
			$category_url = get_term_link( $category );
			if ( is_wp_error( $category_url ) ) {
				$category_url = $shop_url;
			}
			?>
			<section class="home-section product-category-section<?php echo 0 !== $index % 2 ? ' product-category-section-alt' : ''; ?>">
				<div class="cham-container">
					<div class="section-heading section-heading-row">
						<h2><?php echo esc_html( $category->name ); ?></h2>
						<a class="text-link" href="<?php echo esc_url( $category_url ); ?>"><?php esc_html_e( 'Xem tất cả', 'cham-theme' ); ?></a>
					</div>
					<div class="cham-product-grid">
						<?php
						while ( $category_query->have_posts() ) :
							$category_query->the_post();
							cham_product_card();
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
		<?php endforeach; ?>
	<?php else : ?>
		<section class="home-section product-category-section">
			<div class="cham-container">
				<div class="empty-products">
					<p><?php esc_html_e( 'Thêm danh mục và sản phẩm WooCommerce để khu vực này tự hiển thị.', 'cham-theme' ); ?></p>
					<a class="cham-btn cham-btn-primary" href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' ) ); ?>"><?php esc_html_e( 'Thêm danh mục', 'cham-theme' ); ?></a>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="home-section promise-section">
		<div class="cham-container promise-grid">
			<?php foreach ( $feature_items as $item ) : ?>
				<div class="promise-item">
					<div class="promise-icon"><?php echo esc_html( strtoupper( substr( $item['title'], 0, 1 ) ) ); ?></div>
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="home-section about-section">
		<div class="cham-container about-grid">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Về Chạm Coffee', 'cham-theme' ); ?></p>
				<h2><?php esc_html_e( 'Một cửa hàng cà phê sạch sẽ, thân thiện và dễ mua.', 'cham-theme' ); ?></h2>
			</div>
			<p><?php esc_html_e( 'Chạm Coffee tập trung vào trải nghiệm mua cà phê trực tuyến rõ ràng: danh mục dễ hiểu, biến thể đóng gói và dạng xay đặt ngay trên trang sản phẩm, cùng các kênh tư vấn nhanh khi khách cần chọn đúng gu.', 'cham-theme' ); ?></p>
		</div>
	</section>
</main>
<?php
get_footer();
