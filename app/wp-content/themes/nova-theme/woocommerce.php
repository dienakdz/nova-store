<?php
/**
 * WooCommerce wrapper template.
 *
 * @package NovaTheme
 */

wp_enqueue_style(
	'nova-catalog-v2',
	get_template_directory_uri() . '/assets/css/catalog-v2.css',
	array( 'nova-main' ),
	filemtime( get_template_directory() . '/assets/css/catalog-v2.css' )
);

get_header();

$is_catalog = is_shop() || is_product_taxonomy() || is_post_type_archive( 'product' );
?>
<main id="primary" class="site-main nova-container woo-content<?php echo $is_catalog ? ' nova-catalog-page' : ''; ?>">
	<?php if ( $is_catalog ) : ?>
		<?php
		$catalog_title       = woocommerce_page_title( false );
		$catalog_description = is_product_taxonomy() ? term_description() : __( 'Những thiết kế tối giản, dễ phối và phù hợp với nhịp sống hằng ngày.', 'nova-theme' );
		$product_categories  = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'parent'     => 0,
				'number'     => 8,
				'orderby'    => 'menu_order',
				'order'      => 'ASC',
			)
		);
		$current_term = get_queried_object();
		?>
		<header class="nova-catalog-hero">
			<p class="nova-catalog-eyebrow"><?php esc_html_e( 'Nova Store / Sản phẩm', 'nova-theme' ); ?></p>
			<h1><?php echo esc_html( $catalog_title ); ?></h1>
			<?php if ( $catalog_description ) : ?>
				<div class="nova-catalog-description"><?php echo wp_kses_post( $catalog_description ); ?></div>
			<?php endif; ?>
		</header>

		<?php if ( ! is_wp_error( $product_categories ) && $product_categories ) : ?>
			<nav class="nova-catalog-categories" aria-label="<?php esc_attr_e( 'Danh mục sản phẩm', 'nova-theme' ); ?>">
				<a class="<?php echo is_shop() ? 'is-active' : ''; ?>" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php esc_html_e( 'Tất cả', 'nova-theme' ); ?>
				</a>
				<?php foreach ( $product_categories as $product_category ) : ?>
					<?php $category_link = get_term_link( $product_category ); ?>
					<?php if ( ! is_wp_error( $category_link ) ) : ?>
						<a class="<?php echo isset( $current_term->term_id ) && (int) $current_term->term_id === (int) $product_category->term_id ? 'is-active' : ''; ?>" href="<?php echo esc_url( $category_link ); ?>">
							<?php echo esc_html( $product_category->name ); ?>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
	<?php endif; ?>

	<?php woocommerce_content(); ?>
</main>
<?php
get_footer();

