<?php
/**
 * Nova Store front page.
 *
 * @package NovaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_stylesheet = get_theme_file_path( '/assets/css/home-v2.css' );
wp_enqueue_style(
	'nova-home-v2',
	get_theme_file_uri( '/assets/css/home-v2.css' ),
	array( 'nova-main' ),
	file_exists( $home_stylesheet ) ? (string) filemtime( $home_stylesheet ) : NOVA_THEME_VERSION
);

get_header();

$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$assets_url = trailingslashit( get_template_directory_uri() ) . 'assets/images/editorial/';
$banners    = function_exists( 'nova_get_home_banners' ) ? nova_get_home_banners() : array();
$hero_image = ! empty( $banners[0]['image'] ) ? $banners[0]['image'] : $assets_url . 'hero.webp';
$gallery_images = array(
	'category-men.webp',
	'product-shirt.webp',
	'category-women.webp',
	'product-jacket.webp',
	'category-accessories.webp',
	'product-cap.webp',
);
$demo_products = array(
	array( 'name' => __( 'Áo hoodie basic', 'nova-theme' ), 'price' => 499000, 'image' => 'product-hoodie.webp', 'colors' => array( '#d8c5a8', '#f2eee7', '#171717' ) ),
	array( 'name' => __( 'Áo thun oversized', 'nova-theme' ), 'price' => 299000, 'image' => 'product-tee.webp', 'colors' => array( '#f7f5ef', '#b7aea2', '#171717' ) ),
	array( 'name' => __( 'Sơ mi linen', 'nova-theme' ), 'price' => 549000, 'image' => 'product-shirt.webp', 'colors' => array( '#d6c2a3', '#f1ece2', '#786653' ) ),
	array( 'name' => __( 'Quần tây ống suông', 'nova-theme' ), 'price' => 599000, 'image' => 'product-trousers.webp', 'colors' => array( '#171717', '#706b63' ) ),
	array( 'name' => __( 'Khoác jacket', 'nova-theme' ), 'price' => 799000, 'image' => 'product-jacket.webp', 'colors' => array( '#e1d4bf', '#8e7b66', '#171717' ) ),
	array( 'name' => __( 'Nón basic', 'nova-theme' ), 'price' => 199000, 'image' => 'product-cap.webp', 'colors' => array( '#171717', '#c6b8a5' ) ),
);

/* Prefer the matching WooCommerce category, then fall back to the first categories available. */
$available_categories = function_exists( 'nova_home_product_categories' ) ? nova_home_product_categories( 3 ) : array();
$category_definitions = array(
	array(
		'title' => __( 'Nam', 'nova-theme' ),
		'slugs' => array( 'nam', 'men', 'quan-ao-nam' ),
		'image' => 'category-men.webp',
	),
	array(
		'title' => __( 'Nữ', 'nova-theme' ),
		'slugs' => array( 'nu', 'women', 'quan-ao-nu' ),
		'image' => 'category-women.webp',
	),
	array(
		'title' => __( 'Phụ kiện', 'nova-theme' ),
		'slugs' => array( 'phu-kien', 'accessories' ),
		'image' => 'category-accessories.webp',
	),
);

foreach ( $category_definitions as $category_index => &$category_definition ) {
	$category_term = false;

	if ( taxonomy_exists( 'product_cat' ) ) {
		foreach ( $category_definition['slugs'] as $category_slug ) {
			$category_term = get_term_by( 'slug', $category_slug, 'product_cat' );
			if ( $category_term && ! is_wp_error( $category_term ) ) {
				break;
			}
		}
	}

	if ( ( ! $category_term || is_wp_error( $category_term ) ) && isset( $available_categories[ $category_index ] ) ) {
		$category_term = $available_categories[ $category_index ];
	}

	$category_definition['url'] = $shop_url;
	if ( $category_term && ! is_wp_error( $category_term ) ) {
		$category_link = get_term_link( $category_term );
		if ( ! is_wp_error( $category_link ) ) {
			$category_definition['url'] = $category_link;
		}
	}
}
unset( $category_definition );

/* Show featured products first, then fill the row with the latest products. */
$featured_product_ids = array();
if ( function_exists( 'wc_get_products' ) ) {
	$featured_product_ids = wc_get_products(
		array(
			'status'   => 'publish',
			'featured' => true,
			'limit'    => 6,
			'return'   => 'ids',
		)
	);

	if ( count( $featured_product_ids ) < 6 ) {
		$latest_product_ids = wc_get_products(
			array(
				'status'  => 'publish',
				'exclude' => $featured_product_ids,
				'limit'   => 6 - count( $featured_product_ids ),
				'orderby' => 'date',
				'order'   => 'DESC',
				'return'  => 'ids',
			)
		);
		$featured_product_ids = array_merge( $featured_product_ids, $latest_product_ids );
	}
}

/* Real approved WooCommerce reviews; demo quotes only appear while the store has none. */
$customer_reviews = get_comments(
	array(
		'post_type' => 'product',
		'status'    => 'approve',
		'number'    => 3,
		'orderby'   => 'comment_date_gmt',
		'order'     => 'DESC',
	)
);

$demo_reviews = array(
	array(
		'author'   => 'Minh K.',
		'location' => __( 'Hà Nội', 'nova-theme' ),
		'content'  => __( 'Chất vải rất tốt, form đẹp, đóng gói chỉn chu. Sẽ ủng hộ Nova Store dài dài!', 'nova-theme' ),
	),
	array(
		'author'   => 'Thu Trang',
		'location' => __( 'TP. Hồ Chí Minh', 'nova-theme' ),
		'content'  => __( 'Mình rất thích phong cách tối giản của Nova Store, tính tế và dễ phối đồ.', 'nova-theme' ),
	),
	array(
		'author'   => 'Đức Anh',
		'location' => __( 'Đà Nẵng', 'nova-theme' ),
		'content'  => __( 'Giao hàng nhanh, sản phẩm đúng như mô tả. Rất hài lòng!', 'nova-theme' ),
	),
);
?>

<main id="primary" class="site-main nova-home-v2">
	<section class="home-v2-hero" aria-labelledby="nova-hero-title">
		<div class="nova-container home-v2-hero-grid">
			<div class="home-v2-hero-copy">
				<p class="home-v2-kicker"><?php esc_html_e( 'NEW SEASON · 2026', 'nova-theme' ); ?></p>
				<h1 id="nova-hero-title"><?php esc_html_e( 'Sống tối giản, chọn chất riêng.', 'nova-theme' ); ?></h1>
				<p><?php esc_html_e( 'Thời trang tinh tế cho phong cách hiện đại. Tối giản trong thiết kế, khác biệt trong chất liệu.', 'nova-theme' ); ?></p>
				<a class="home-v2-button" href="<?php echo esc_url( $shop_url ); ?>">
					<?php esc_html_e( 'Khám phá bộ sưu tập', 'nova-theme' ); ?>
					<span aria-hidden="true">→</span>
				</a>
			</div>
			<div class="home-v2-hero-media">
				<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php esc_attr_e( 'Bộ sưu tập thời trang tối giản của Nova Store', 'nova-theme' ); ?>">
			</div>
		</div>
	</section>

	<section class="home-v2-categories" aria-labelledby="nova-categories-title">
		<div class="nova-container">
			<h2 id="nova-categories-title" class="screen-reader-text"><?php esc_html_e( 'Danh mục nổi bật', 'nova-theme' ); ?></h2>
			<div class="home-v2-category-grid">
				<?php foreach ( $category_definitions as $category_definition ) : ?>
					<a class="home-v2-category-card" href="<?php echo esc_url( $category_definition['url'] ); ?>">
						<img src="<?php echo esc_url( $assets_url . $category_definition['image'] ); ?>" alt="">
						<span class="home-v2-category-overlay" aria-hidden="true"></span>
						<span class="home-v2-category-content">
							<strong><?php echo esc_html( $category_definition['title'] ); ?></strong>
							<span><?php esc_html_e( 'Xem ngay', 'nova-theme' ); ?> →</span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="home-v2-section home-v2-featured" aria-labelledby="nova-featured-title">
		<div class="nova-container">
			<div class="home-v2-section-heading">
				<div>
					<p class="home-v2-kicker"><?php esc_html_e( 'NOVA SELECTION', 'nova-theme' ); ?></p>
					<h2 id="nova-featured-title"><?php esc_html_e( 'Sản phẩm nổi bật', 'nova-theme' ); ?></h2>
				</div>
				<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Xem tất cả', 'nova-theme' ); ?> →</a>
			</div>

			<?php if ( $featured_product_ids ) : ?>
				<div class="nova-product-grid home-v2-product-grid">
					<?php foreach ( $featured_product_ids as $featured_product_id ) : ?>
						<?php nova_product_card( $featured_product_id ); ?>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="nova-product-grid home-v2-product-grid home-v2-demo-products">
					<?php foreach ( $demo_products as $demo_product ) : ?>
						<article class="nova-product-card">
							<a class="product-thumb" href="<?php echo esc_url( $shop_url ); ?>" aria-label="<?php echo esc_attr( $demo_product['name'] ); ?>">
								<img src="<?php echo esc_url( $assets_url . $demo_product['image'] ); ?>" alt="<?php echo esc_attr( $demo_product['name'] ); ?>" loading="lazy">
							</a>
							<div class="product-card-body">
								<div class="product-category"><?php esc_html_e( 'Nova Essentials', 'nova-theme' ); ?></div>
								<h3><a href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( $demo_product['name'] ); ?></a></h3>
								<div class="product-price"><?php echo wp_kses_post( function_exists( 'wc_price' ) ? wc_price( $demo_product['price'] ) : number_format_i18n( $demo_product['price'] ) . 'đ' ); ?></div>
								<div class="nova-product-swatches" aria-label="<?php esc_attr_e( 'Màu sắc', 'nova-theme' ); ?>">
									<?php foreach ( $demo_product['colors'] as $color_index => $demo_color ) : ?>
										<span class="nova-product-swatch<?php echo 0 === $color_index ? ' is-active' : ''; ?>" style="--nova-swatch-color: <?php echo esc_attr( $demo_color ); ?>"></span>
									<?php endforeach; ?>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="home-v2-collection">
		<div class="nova-container">
			<div class="home-v2-collection-card">
				<img src="<?php echo esc_url( $assets_url . 'collection.webp' ); ?>" alt="<?php esc_attr_e( 'Bộ sưu tập thời trang mới của Nova Store', 'nova-theme' ); ?>">
				<div class="home-v2-collection-copy">
					<p class="home-v2-kicker"><?php esc_html_e( 'NEW COLLECTION', 'nova-theme' ); ?></p>
					<h2><?php esc_html_e( 'Essential Edit', 'nova-theme' ); ?></h2>
					<p><?php esc_html_e( 'Thanh lịch. Tinh tế. Dành cho bạn.', 'nova-theme' ); ?></p>
					<a class="home-v2-button" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Khám phá ngay', 'nova-theme' ); ?> <span aria-hidden="true">→</span></a>
				</div>
			</div>
		</div>
	</section>

	<section class="home-v2-benefits" aria-label="<?php esc_attr_e( 'Dịch vụ của Nova Store', 'nova-theme' ); ?>">
		<div class="nova-container home-v2-benefit-grid">
			<div class="home-v2-benefit">
				<svg viewBox="0 0 32 32" aria-hidden="true"><path d="M2 8h18v14H2zM20 13h5l5 6v3H20zM7 27a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm17 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>
				<div><strong><?php esc_html_e( 'Giao hàng toàn quốc', 'nova-theme' ); ?></strong><span><?php esc_html_e( 'Miễn phí cho đơn từ 500.000đ', 'nova-theme' ); ?></span></div>
			</div>
			<div class="home-v2-benefit">
				<svg viewBox="0 0 32 32" aria-hidden="true"><path d="M27 9v8h-8M5 23v-8h8M26 17a11 11 0 0 1-20 6M6 15A11 11 0 0 1 26 9"/></svg>
				<div><strong><?php esc_html_e( 'Đổi trả dễ dàng', 'nova-theme' ); ?></strong><span><?php esc_html_e( 'Đổi trả trong 7 ngày', 'nova-theme' ); ?></span></div>
			</div>
			<div class="home-v2-benefit">
				<svg viewBox="0 0 32 32" aria-hidden="true"><rect x="3" y="7" width="26" height="18" rx="2"/><path d="M3 13h26M20 20h5"/></svg>
				<div><strong><?php esc_html_e( 'Thanh toán an toàn', 'nova-theme' ); ?></strong><span><?php esc_html_e( 'Đa dạng phương thức', 'nova-theme' ); ?></span></div>
			</div>
			<div class="home-v2-benefit">
				<svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 27a11 11 0 1 0 0-22 11 11 0 0 0 0 22Z"/><path d="M11 18v-4a5 5 0 0 1 10 0v4M8 17h3v5H8zM21 17h3v5h-3z"/></svg>
				<div><strong><?php esc_html_e( 'Tư vấn 24/7', 'nova-theme' ); ?></strong><span><?php esc_html_e( 'Hỗ trợ nhanh chóng', 'nova-theme' ); ?></span></div>
			</div>
		</div>
	</section>

	<section class="home-v2-section home-v2-testimonials" aria-labelledby="nova-testimonials-title">
		<div class="nova-container">
			<div class="home-v2-section-heading home-v2-section-heading-centered">
				<div>
					<p class="home-v2-kicker"><?php esc_html_e( 'REAL REVIEWS', 'nova-theme' ); ?></p>
					<h2 id="nova-testimonials-title"><?php esc_html_e( 'Khách hàng nói gì về chúng tôi?', 'nova-theme' ); ?></h2>
				</div>
			</div>
			<div class="home-v2-review-grid">
				<?php if ( $customer_reviews ) : ?>
					<?php foreach ( $customer_reviews as $customer_review ) : ?>
						<?php
						$review_rating = max( 0, min( 5, (int) get_comment_meta( $customer_review->comment_ID, 'rating', true ) ) );
						$product_url   = get_permalink( $customer_review->comment_post_ID );
						?>
						<article class="home-v2-review">
							<div class="home-v2-review-mark" aria-hidden="true">“</div>
							<?php if ( $review_rating ) : ?>
								<div class="home-v2-stars" aria-label="<?php echo esc_attr( sprintf( __( '%d trên 5 sao', 'nova-theme' ), $review_rating ) ); ?>"><?php echo esc_html( str_repeat( '★', $review_rating ) . str_repeat( '☆', 5 - $review_rating ) ); ?></div>
							<?php endif; ?>
							<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $customer_review->comment_content ), 28, '…' ) ); ?></p>
							<div class="home-v2-reviewer">
								<?php echo wp_kses_post( get_avatar( $customer_review, 44, '', $customer_review->comment_author, array( 'class' => 'home-v2-avatar' ) ) ); ?>
								<div>
									<strong><?php echo esc_html( $customer_review->comment_author ); ?></strong>
									<?php if ( $product_url ) : ?>
										<a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( get_the_title( $customer_review->comment_post_ID ) ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				<?php else : ?>
					<?php foreach ( $demo_reviews as $demo_review ) : ?>
						<article class="home-v2-review">
							<div class="home-v2-review-mark" aria-hidden="true">“</div>
							<div class="home-v2-stars" aria-label="<?php esc_attr_e( '5 trên 5 sao', 'nova-theme' ); ?>">★★★★★</div>
							<p><?php echo esc_html( $demo_review['content'] ); ?></p>
							<div class="home-v2-reviewer">
								<span class="home-v2-avatar home-v2-avatar-fallback" aria-hidden="true"><?php echo esc_html( mb_substr( $demo_review['author'], 0, 1 ) ); ?></span>
								<div><strong><?php echo esc_html( $demo_review['author'] ); ?></strong><span><?php echo esc_html( $demo_review['location'] ); ?></span></div>
							</div>
						</article>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="home-v2-gallery" aria-labelledby="nova-gallery-title">
		<div class="nova-container">
			<div class="home-v2-gallery-heading">
				<h2 id="nova-gallery-title">@NOVASTORE.OFFICIAL</h2>
				<p><?php esc_html_e( 'Theo dõi cảm hứng phối đồ mỗi ngày', 'nova-theme' ); ?></p>
			</div>
			<div class="home-v2-gallery-grid">
				<?php foreach ( $gallery_images as $gallery_index => $gallery_image ) : ?>
					<a href="<?php echo esc_url( $shop_url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Khám phá phong cách Nova %d', 'nova-theme' ), $gallery_index + 1 ) ); ?>">
						<img src="<?php echo esc_url( $assets_url . $gallery_image ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Gợi ý phối đồ Nova Store %d', 'nova-theme' ), $gallery_index + 1 ) ); ?>" loading="lazy">
						<span aria-hidden="true">↗</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
