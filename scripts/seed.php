<?php
/**
 * Idempotent demo data seeder for Nova Store.
 *
 * Run through WP-CLI only:
 * wp eval-file /project/scripts/seed.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'WC_Product_Simple' ) ) {
	throw new RuntimeException( 'WooCommerce chưa được kích hoạt.' );
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

/**
 * Get or create a top-level product category.
 */
function nova_seed_category( $name, $slug ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );

	if ( $term instanceof WP_Term ) {
		return (int) $term->term_id;
	}

	$result = wp_insert_term(
		$name,
		'product_cat',
		array( 'slug' => $slug )
	);

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}

	return (int) $result['term_id'];
}

/**
 * Import a theme editorial image to the Media Library once.
 */
function nova_seed_attachment( $filename, $title ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_nova_seed_asset',
			'meta_value'     => $filename,
		)
	);

	if ( $existing ) {
		return (int) $existing[0];
	}

	$source = get_theme_file_path( '/assets/images/editorial/' . $filename );

	if ( ! file_exists( $source ) ) {
		throw new RuntimeException( sprintf( 'Không tìm thấy ảnh seed: %s', $filename ) );
	}

	// Windows bind mounts do not support Unix chmod; suppress that harmless upload warning.
	$upload = @wp_upload_bits( 'nova-' . $filename, null, file_get_contents( $source ) );

	if ( ! empty( $upload['error'] ) ) {
		throw new RuntimeException( $upload['error'] );
	}

	$filetype      = wp_check_filetype( $upload['file'] );
	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => $title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( is_wp_error( $attachment_id ) ) {
		throw new RuntimeException( $attachment_id->get_error_message() );
	}

	$image_size = wp_getimagesize( $upload['file'] );
	if ( $image_size ) {
		wp_update_attachment_metadata(
			$attachment_id,
			array(
				'width'  => (int) $image_size[0],
				'height' => (int) $image_size[1],
				'file'   => _wp_relative_upload_path( $upload['file'] ),
				'sizes'  => array(),
			)
		);
	}
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );
	update_post_meta( $attachment_id, '_nova_seed_asset', $filename );

	return (int) $attachment_id;
}

/**
 * Build a local product attribute such as color or size.
 */
function nova_seed_product_attribute( $name, $options, $position ) {
	$attribute = new WC_Product_Attribute();
	$attribute->set_id( 0 );
	$attribute->set_name( $name );
	$attribute->set_options( $options );
	$attribute->set_position( $position );
	$attribute->set_visible( true );
	$attribute->set_variation( false );

	return $attribute;
}

$category_ids = array(
	'nam'       => nova_seed_category( 'Nam', 'nam' ),
	'nu'        => nova_seed_category( 'Nữ', 'nu' ),
	'phu-kien'  => nova_seed_category( 'Phụ kiện', 'phu-kien' ),
);

$products = array(
	array(
		'sku'         => 'NOVA-DEMO-001',
		'name'        => 'Áo hoodie basic',
		'slug'        => 'ao-hoodie-basic',
		'price'       => '499000',
		'category'    => 'nam',
		'image'       => 'product-hoodie.webp',
		'colors'      => array( 'Be', 'Kem', 'Đen' ),
		'sizes'       => array( 'S', 'M', 'L', 'XL' ),
		'description' => 'Áo hoodie phom suông tối giản, chất nỉ mềm và dễ phối trong nhiều hoàn cảnh.',
	),
	array(
		'sku'         => 'NOVA-DEMO-002',
		'name'        => 'Áo thun oversized',
		'slug'        => 'ao-thun-oversized',
		'price'       => '299000',
		'category'    => 'nu',
		'image'       => 'product-tee.webp',
		'colors'      => array( 'Trắng', 'Kem', 'Đen' ),
		'sizes'       => array( 'S', 'M', 'L' ),
		'description' => 'Áo thun cotton phom rộng thoải mái, bề mặt vải mịn và giữ dáng tốt.',
	),
	array(
		'sku'         => 'NOVA-DEMO-003',
		'name'        => 'Sơ mi linen',
		'slug'        => 'so-mi-linen',
		'price'       => '549000',
		'category'    => 'nam',
		'image'       => 'product-shirt.webp',
		'colors'      => array( 'Be', 'Kem', 'Nâu' ),
		'sizes'       => array( 'M', 'L', 'XL' ),
		'description' => 'Sơ mi linen thoáng nhẹ với đường cắt gọn, phù hợp đi làm và dạo phố.',
	),
	array(
		'sku'         => 'NOVA-DEMO-004',
		'name'        => 'Quần tây ống suông',
		'slug'        => 'quan-tay-ong-suong',
		'price'       => '599000',
		'category'    => 'nam',
		'image'       => 'product-trousers.webp',
		'colors'      => array( 'Đen', 'Xám' ),
		'sizes'       => array( 'S', 'M', 'L', 'XL' ),
		'description' => 'Quần tây cạp vừa, ống suông hiện đại và chất vải đứng phom nhưng thoải mái.',
	),
	array(
		'sku'         => 'NOVA-DEMO-005',
		'name'        => 'Khoác jacket',
		'slug'        => 'khoac-jacket',
		'price'       => '799000',
		'category'    => 'nu',
		'image'       => 'product-jacket.webp',
		'colors'      => array( 'Kem', 'Nâu', 'Đen' ),
		'sizes'       => array( 'S', 'M', 'L' ),
		'description' => 'Jacket nhẹ với thiết kế tinh gọn, dễ tạo lớp cho trang phục hằng ngày.',
	),
	array(
		'sku'         => 'NOVA-DEMO-006',
		'name'        => 'Nón basic',
		'slug'        => 'non-basic',
		'price'       => '199000',
		'category'    => 'phu-kien',
		'image'       => 'product-cap.webp',
		'colors'      => array( 'Đen', 'Be' ),
		'sizes'       => array( 'Freesize' ),
		'description' => 'Nón lưỡi trai trơn, phom gọn và có khóa điều chỉnh phía sau.',
	),
);

$product_ids = array();

foreach ( $products as $item ) {
	$product_id = wc_get_product_id_by_sku( $item['sku'] );

	if ( $product_id && 'nova-store-demo' !== get_post_meta( $product_id, '_nova_seed_source', true ) ) {
		WP_CLI::warning( sprintf( 'Bỏ qua SKU %s vì không thuộc bộ seed Nova.', $item['sku'] ) );
		continue;
	}

	$product = $product_id ? wc_get_product( $product_id ) : new WC_Product_Simple();

	if ( ! $product instanceof WC_Product_Simple ) {
		WP_CLI::warning( sprintf( 'Bỏ qua SKU %s vì không phải simple product.', $item['sku'] ) );
		continue;
	}

	$product->set_name( $item['name'] );
	$product->set_slug( $item['slug'] );
	$product->set_sku( $item['sku'] );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_regular_price( $item['price'] );
	$product->set_price( $item['price'] );
	$product->set_category_ids( array( $category_ids[ $item['category'] ] ) );
	$product->set_image_id( nova_seed_attachment( $item['image'], $item['name'] ) );
	$product->set_short_description( $item['description'] );
	$product->set_description( $item['description'] . "\n\nThiết kế thuộc bộ Nova Essentials dành cho website demo hackathon." );
	$product->set_featured( true );
	$product->set_manage_stock( false );
	$product->set_stock_status( 'instock' );
	$product->set_reviews_allowed( true );
	$product->set_attributes(
		array(
			nova_seed_product_attribute( 'Màu sắc', $item['colors'], 0 ),
			nova_seed_product_attribute( 'Kích thước', $item['sizes'], 1 ),
		)
	);

	$product_id = $product->save();
	update_post_meta( $product_id, '_nova_seed_source', 'nova-store-demo' );
	$product_ids[ $item['sku'] ] = $product_id;
}

$reviews = array(
	array( 'sku' => 'NOVA-DEMO-001', 'key' => 'review-001', 'author' => 'Minh K.', 'email' => 'minh@example.com', 'rating' => 5, 'content' => 'Chất vải mềm, form đẹp và đóng gói rất chỉn chu. Mình sẽ ủng hộ Nova Store dài dài.' ),
	array( 'sku' => 'NOVA-DEMO-001', 'key' => 'review-002', 'author' => 'Hoàng Nam', 'email' => 'nam@example.com', 'rating' => 4, 'content' => 'Áo mặc ấm và màu be đẹp, phần tay hơi dài một chút nhưng tổng thể rất ổn.' ),
	array( 'sku' => 'NOVA-DEMO-002', 'key' => 'review-003', 'author' => 'Thu Trang', 'email' => 'trang@example.com', 'rating' => 5, 'content' => 'Form rộng vừa phải, vải mát và dễ phối đồ. Giao hàng cũng nhanh hơn dự kiến.' ),
	array( 'sku' => 'NOVA-DEMO-002', 'key' => 'review-004', 'author' => 'Linh Chi', 'email' => 'linh@example.com', 'rating' => 3, 'content' => 'Áo đẹp nhưng màu thực tế hơi ngà hơn trong ảnh. Chất vải vẫn khá tốt so với giá.' ),
	array( 'sku' => 'NOVA-DEMO-003', 'key' => 'review-005', 'author' => 'Đức Anh', 'email' => 'anh@example.com', 'rating' => 5, 'content' => 'Sơ mi nhẹ và thoáng, mặc đi làm rất hợp. Đường may gọn và kích thước đúng mô tả.' ),
	array( 'sku' => 'NOVA-DEMO-003', 'key' => 'review-006', 'author' => 'Quốc Bảo', 'email' => 'bao@example.com', 'rating' => 4, 'content' => 'Chất linen đẹp, giao hàng nhanh. Áo dễ nhăn nên cần ủi trước khi mặc.' ),
	array( 'sku' => 'NOVA-DEMO-004', 'key' => 'review-007', 'author' => 'Tuấn Phạm', 'email' => 'tuan@example.com', 'rating' => 4, 'content' => 'Quần đứng form và mặc thoải mái. Phần eo đúng size nhưng ống dài với người thấp.' ),
	array( 'sku' => 'NOVA-DEMO-004', 'key' => 'review-008', 'author' => 'Khánh Vy', 'email' => 'vy@example.com', 'rating' => 2, 'content' => 'Kiểu dáng đẹp nhưng sản phẩm mình nhận có một đường chỉ thừa và giao hơi chậm.' ),
	array( 'sku' => 'NOVA-DEMO-005', 'key' => 'review-009', 'author' => 'Mai Hương', 'email' => 'huong@example.com', 'rating' => 5, 'content' => 'Jacket nhẹ, màu kem rất thanh lịch và phối với váy hay quần đều đẹp.' ),
	array( 'sku' => 'NOVA-DEMO-005', 'key' => 'review-010', 'author' => 'Ngọc Hà', 'email' => 'ha@example.com', 'rating' => 4, 'content' => 'Thiết kế tối giản đúng gu, khóa kéo mượt. Mình mong shop có thêm nhiều màu hơn.' ),
	array( 'sku' => 'NOVA-DEMO-006', 'key' => 'review-011', 'author' => 'Gia Huy', 'email' => 'huy@example.com', 'rating' => 5, 'content' => 'Nón lên form gọn, màu đen dễ dùng và khóa điều chỉnh chắc chắn.' ),
	array( 'sku' => 'NOVA-DEMO-006', 'key' => 'review-012', 'author' => 'Thanh Lam', 'email' => 'lam@example.com', 'rating' => 3, 'content' => 'Mẫu nón đẹp nhưng hộp đóng gói bị móp nhẹ. Sản phẩm bên trong không bị ảnh hưởng.' ),
);

$created_reviews = 0;

foreach ( $reviews as $index => $review ) {
	if ( empty( $product_ids[ $review['sku'] ] ) ) {
		continue;
	}

	$review_key = 'nova-' . $review['key'];
	$existing   = get_comments(
		array(
			'post_id'    => $product_ids[ $review['sku'] ],
			'status'     => 'all',
			'type'       => 'review',
			'number'     => 1,
			'fields'     => 'ids',
			'meta_key'   => '_nova_seed_key',
			'meta_value' => $review_key,
		)
	);

	if ( $existing ) {
		continue;
	}

	$comment_id = wp_insert_comment(
		array(
			'comment_post_ID'      => $product_ids[ $review['sku'] ],
			'comment_author'       => $review['author'],
			'comment_author_email' => $review['email'],
			'comment_content'      => $review['content'],
			'comment_type'         => 'review',
			'comment_approved'     => 1,
			'comment_date'         => wp_date( 'Y-m-d H:i:s', time() - ( $index * DAY_IN_SECONDS ) ),
		)
	);

	if ( ! $comment_id ) {
		throw new RuntimeException( sprintf( 'Không thể tạo review %s.', $review_key ) );
	}

	add_comment_meta( $comment_id, 'rating', (int) $review['rating'], true );
	add_comment_meta( $comment_id, 'verified', 1, true );
	add_comment_meta( $comment_id, '_nova_seed_key', $review_key, true );
	wp_update_comment_count_now( $product_ids[ $review['sku'] ] );
	++$created_reviews;
}

foreach ( $product_ids as $product_id ) {
	wc_delete_product_transients( $product_id );
}

WP_CLI::success(
	sprintf(
		'Nova Store có %d category, %d sản phẩm demo và %d review mới được tạo trong lần chạy này.',
		count( $category_ids ),
		count( $product_ids ),
		$created_reviews
	)
);
