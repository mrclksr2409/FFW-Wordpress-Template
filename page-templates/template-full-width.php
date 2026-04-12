<?php
/**
 * Template Name: Vollbreite (ohne Sidebar)
 * Template Post Type: post, page
 *
 * Full-width page template without a sidebar.
 * Works natively with Elementor — the_content() outputs Elementor's rendered content.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
?>
<main id="primary" class="site-main layout-full-width">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( get_the_title() ) : ?>
				<header class="page-header">
					<div class="container">
						<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
					</div>
				</header>
			<?php endif; ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
endif;
get_footer();
