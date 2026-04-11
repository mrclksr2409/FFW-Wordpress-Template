<?php
/**
 * FFW Theme — Single Einsatz (Incident Report) template.
 * WordPress template hierarchy: single-einsatz.php
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
?>
<main id="primary" class="site-main einsatz-single">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>

		<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ffw-theme' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Start', 'ffw-theme' ); ?></a>
			<span aria-hidden="true">&rsaquo;</span>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'einsatz' ) ); ?>"><?php esc_html_e( 'Einsätze', 'ffw-theme' ); ?></a>
			<span aria-hidden="true">&rsaquo;</span>
			<span aria-current="page"><?php the_title(); ?></span>
		</nav>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'einsatz-report' ); ?>>

			<header class="einsatz-report__header">
				<span class="badge"><?php esc_html_e( 'Einsatzbericht', 'ffw-theme' ); ?></span>
				<h1 class="einsatz-report__title"><?php the_title(); ?></h1>
				<?php get_template_part( 'template-parts/einsatz/einsatz-meta' ); ?>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="einsatz-report__image">
					<?php the_post_thumbnail( 'ffw-hero' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( get_the_content() ) : ?>
				<div class="einsatz-report__content entry-content">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<footer class="einsatz-report__footer">
				<?php
				the_post_navigation(
					array(
						'prev_text'          => '<span class="nav-subtitle">' . esc_html__( 'Vorheriger Einsatz', 'ffw-theme' ) . '</span> <span class="nav-title">%title</span>',
						'next_text'          => '<span class="nav-subtitle">' . esc_html__( 'Nächster Einsatz', 'ffw-theme' ) . '</span> <span class="nav-title">%title</span>',
						'in_same_term'       => false,
						'taxonomy'           => 'einsatzart',
					)
				);
				?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'einsatz' ) ); ?>" class="btn btn--ghost">
					&larr; <?php esc_html_e( 'Alle Einsätze', 'ffw-theme' ); ?>
				</a>
			</footer>
		</article>

		<?php endwhile; ?>
	</div>
</main>


<?php
endif;
get_footer();
