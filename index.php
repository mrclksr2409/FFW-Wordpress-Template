<?php
/**
 * FFW Theme — index.php
 * Required WordPress fallback template.
 */

get_header();
?>
<main id="primary" class="site-main">

	<?php if ( have_posts() ) : ?>

		<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="page-header">
			<div class="container">
				<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
			</div>
		</header>
		<?php endif; ?>

		<div class="container">
			<div class="posts-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
				?>
			</div>
			<?php the_posts_navigation(); ?>
		</div>

	<?php else : ?>
		<div class="container">
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		</div>
	<?php endif; ?>

</main>
<?php
get_footer();
