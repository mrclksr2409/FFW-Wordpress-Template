<?php
/**
 * FFW Theme — Single post template.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :

	while ( have_posts() ) :
		the_post();
?>
<main id="primary" class="site-main single-post-main">

	<?php get_template_part( 'template-parts/content', 'single' ); ?>

	<div class="container post-container">

		<?php
		the_post_navigation(
			array(
				'prev_text' => '<span class="post-nav__label">' . esc_html__( 'Vorheriger Beitrag', 'ffw-theme' ) . '</span><span class="post-nav__title">%title</span>',
				'next_text' => '<span class="post-nav__label">' . esc_html__( 'Nächster Beitrag', 'ffw-theme' ) . '</span><span class="post-nav__title">%title</span>',
			)
		);
		?>

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<div class="post-comments-section">
				<?php comments_template(); ?>
			</div>
		<?php endif; ?>

	</div>

</main>
<?php
	endwhile;

endif;
get_footer();
