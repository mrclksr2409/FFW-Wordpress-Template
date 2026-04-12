<?php
/**
 * FFW Theme — Template part for displaying posts in archive/blog loops.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card post-card' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="card__thumbnail">
			<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
				<?php the_post_thumbnail( 'ffw-card' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="card__body">
		<p class="card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>
			<?php if ( has_category() ) : ?>
				&mdash;
				<?php the_category( ', ' ); ?>
			<?php endif; ?>
		</p>

		<h2 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<div class="card__excerpt">
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">
			<?php esc_html_e( 'Weiterlesen', 'ffw-theme' ); ?>
		</a>
	</div>
</article>
