<?php
/**
 * Default template.
 *
 * @package NovaTheme
 */

get_header();
?>
<main id="primary" class="site-main nova-container content-area">
	<?php if ( have_posts() ) : ?>
		<div class="post-list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'post-card' ); ?>>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<div class="entry-summary"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No content found.', 'nova-theme' ); ?></p>
	<?php endif; ?>
</main>
<?php
get_footer();

