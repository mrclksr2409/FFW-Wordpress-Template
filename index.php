<?php
/**
 * FFW Theme — index.php
 * Required WordPress fallback template.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) :
?>
<main id="primary" class="site-main">

	<?php if ( is_home() && ! is_front_page() ) : ?>
	<header class="page-header">
		<div class="container">
			<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
		</div>
	</header>
	<?php endif; ?>

	<div class="container">
		<?php if ( have_posts() ) : ?>

			<div class="posts-grid posts-grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
				?>
			</div>

			<?php the_posts_pagination(); ?>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>

</main>
<?php
endif;
get_footer();
