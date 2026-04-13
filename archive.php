<?php
/**
 * FFW Theme — Archive template (Kategorien, Tags, Datum, Autor).
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) :
?>
<main id="primary" class="site-main">

	<header class="page-header">
		<div class="container">
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			<?php the_archive_description( '<p class="page-description">', '</p>' ); ?>
		</div>
	</header>

	<div class="container">
		<?php if ( have_posts() ) : ?>

			<p class="archive-count">
				<?php
				printf(
					/* translators: %d: number of posts */
					esc_html( _n( '%d Beitrag', '%d Beiträge', (int) $wp_query->found_posts, 'ffw-theme' ) ),
					(int) $wp_query->found_posts
				);
				?>
			</p>

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
