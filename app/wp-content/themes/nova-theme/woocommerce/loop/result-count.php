<?php
/**
 * Product loop result count.
 *
 * @package NovaTheme
 * @version 10.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<p class="woocommerce-result-count" role="status" aria-relevant="all" <?php echo ( empty( $orderedby ) || 1 === intval( $total ) ) ? '' : 'data-is-sorted-by="true"'; ?>>
	<?php
	if ( 1 === intval( $total ) ) {
		esc_html_e( 'Hiển thị 1 sản phẩm', 'nova-theme' );
	} elseif ( $total <= $per_page || -1 === $per_page ) {
		$orderedby_placeholder = empty( $orderedby ) ? '%2$s' : '<span class="screen-reader-text">%2$s</span>';
		printf(
			/* translators: 1: total products 2: sorted by. */
			esc_html__( 'Hiển thị tất cả %1$d sản phẩm', 'nova-theme' ) . $orderedby_placeholder,
			esc_html( $total ),
			esc_html( $orderedby )
		);
	} else {
		$first                 = ( $per_page * $current ) - $per_page + 1;
		$last                  = min( $total, $per_page * $current );
		$orderedby_placeholder = empty( $orderedby ) ? '%4$s' : '<span class="screen-reader-text">%4$s</span>';
		printf(
			/* translators: 1: first product 2: last product 3: total products 4: sorted by. */
			esc_html__( 'Hiển thị %1$d-%2$d trong %3$d sản phẩm', 'nova-theme' ) . $orderedby_placeholder,
			esc_html( $first ),
			esc_html( $last ),
			esc_html( $total ),
			esc_html( $orderedby )
		);
	}
	?>
</p>
