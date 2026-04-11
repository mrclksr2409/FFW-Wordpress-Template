<?php
/**
 * FFW Theme — Template part for displaying post excerpts (compact card).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-excerpt-item' ); ?>>
	<div class="post-excerpt-inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-excerpt-thumb">
				<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
					<?php the_post_thumbnail( 'ffw-thumb' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="post-excerpt-body">
			<p class="post-excerpt-date text-muted text-small">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
			</p>
			<h3 class="post-excerpt-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
		</div>
	</div>
</article>
