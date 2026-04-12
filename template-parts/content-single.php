<?php
/**
 * FFW Theme — Template part for displaying single posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-report' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-report__image">
			<?php the_post_thumbnail( 'ffw-hero' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( get_the_content() ) : ?>
		<div class="post-report__content entry-content">
			<?php
			the_content(
				sprintf(
					wp_kses(
						__( 'Weiterlesen<span class="screen-reader-text"> "%s"</span>', 'ffw-theme' ),
						array( 'span' => array( 'class' => array() ) )
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
	<?php endif; ?>

	<footer class="post-report__footer">

		<?php
		$tags_list = get_the_tag_list( '', '' );
		if ( $tags_list ) :
		?>
		<div class="post-tags">
			<span class="post-tags__label"><?php esc_html_e( 'Schlagwörter:', 'ffw-theme' ); ?></span>
			<div class="post-tags__list"><?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		</div>
		<?php endif; ?>

		<?php
		edit_post_link(
			esc_html__( 'Beitrag bearbeiten', 'ffw-theme' ),
			'<span class="post-edit-link">',
			'</span>'
		);
		?>

	</footer>

</article>
