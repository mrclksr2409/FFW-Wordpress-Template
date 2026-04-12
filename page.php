<?php
/**
 * FFW Theme — Standard page template.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
?>
<main id="primary" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( ! is_front_page() ) : ?>
		<header class="page-header">
			<div class="container">
				<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
			</div>
		</header>
		<?php endif; ?>

		<div class="container">
			<div class="content-area">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
					<div class="entry-content">
						<?php
						the_content();
						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Seiten:', 'ffw-theme' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>
				</article>

				<?php if ( comments_open() || get_comments_number() ) : ?>
					<?php comments_template(); ?>
				<?php endif; ?>
			</div>
		</div>

	<?php endwhile; ?>
</main>
<?php
endif;
get_footer();
