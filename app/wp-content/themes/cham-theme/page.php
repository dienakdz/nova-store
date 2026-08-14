<?php
/**
 * Page template.
 *
 * @package ChamTheme
 */

get_header();
?>
<main id="primary" class="site-main cham-container content-area">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'page-content' ); ?>>
			<header class="entry-header">
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();

