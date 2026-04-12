<?php
/**
 * FFW Theme — Template part for displaying single posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-hero-image">
			<?php the_post_thumbnail( 'ffw-hero' ); ?>
		</div>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php ffw_posted_on(); ?>
			<?php ffw_posted_by(); ?>
			<?php ffw_entry_categories(); ?>
		</div>
	</header>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					__( 'Weiterlesen<span class="screen-reader-text"> "%s"</span>', 'ffw-theme' ),
					array(
						'span' => array( 'class' => array() ),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Seiten:', 'ffw-theme' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>

	<footer class="entry-footer">
		<?php ffw_entry_footer(); ?>
	</footer>
</article>
