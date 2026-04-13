<?php
/**
 * FFW Theme — Single Einsatz (Incident Report) template.
 * WordPress template hierarchy: single-einsatz.php
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
?>
<main id="primary" class="site-main einsatz-single">

	<?php while ( have_posts() ) : the_post(); ?>

		<header class="page-header">
			<div class="container">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</div>
		</header>

		<div class="container">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'einsatz-report' ); ?>>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="einsatz-report__image">
						<?php the_post_thumbnail( 'ffw-hero' ); ?>
					</div>
				<?php endif; ?>

				<?php get_template_part( 'template-parts/einsatz/einsatz-meta' ); ?>

				<?php if ( get_the_content() ) : ?>
					<div class="einsatz-report__content entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

			</article>
		</div>

	<?php endwhile; ?>

</main>
<?php
endif;
get_footer();
