<?php
/**
 * FFW Theme — Search results template.
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title">
				<?php
				printf(
					esc_html__( 'Suchergebnisse für: %s', 'ffw-theme' ),
					'<span>' . get_search_query() . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="posts-grid">
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
get_footer();
