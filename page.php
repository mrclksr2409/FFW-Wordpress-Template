<?php
/**
 * FFW Theme — Standard page template.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
?>
<main id="primary" class="site-main">
	<div class="container">
		<div class="content-area">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
					<?php if ( ! is_front_page() ) : ?>
						<header class="entry-header page-header">
							<?php the_title( '<h1 class="entry-title page-title">', '</h1>' ); ?>
						</header>
					<?php endif; ?>

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

			<?php endwhile; ?>
		</div>
	</div>
</main>
<?php
endif;
get_footer();
