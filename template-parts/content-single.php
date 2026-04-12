<?php
/**
 * FFW Theme — Template part for displaying single posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Estimated reading time (approx. 200 words/min)
$content    = get_the_content();
$word_count = str_word_count( wp_strip_all_tags( $content ) );
$read_time  = max( 1, (int) ceil( $word_count / 200 ) );

// Primary category for breadcrumb & badge
$categories = get_the_category();
$primary_cat = ! empty( $categories ) ? $categories[0] : null;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>

		<!-- ── Hero with featured image ────────────────────────────────────── -->
		<div class="post-hero">
			<?php the_post_thumbnail( 'ffw-hero', array( 'class' => 'post-hero__img', 'alt' => '' ) ); ?>

			<div class="post-hero__overlay">
				<div class="container">

					<nav class="post-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ffw-theme' ); ?>">
						<ol class="post-breadcrumb__list">
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Startseite', 'ffw-theme' ); ?></a></li>
							<?php if ( $primary_cat ) : ?>
								<li><a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>"><?php echo esc_html( $primary_cat->name ); ?></a></li>
							<?php endif; ?>
							<li aria-current="page"><?php the_title(); ?></li>
						</ol>
					</nav>

					<?php if ( $primary_cat ) : ?>
						<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="post-cat-badge">
							<?php echo esc_html( $primary_cat->name ); ?>
						</a>
					<?php endif; ?>

					<h1 class="post-hero__title"><?php the_title(); ?></h1>

					<div class="post-hero__meta">
						<span class="post-hero__meta-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
							<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						</span>
						<span class="post-hero__meta-sep" aria-hidden="true">·</span>
						<span class="post-hero__meta-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="post-hero__author-link">
								<?php echo esc_html( get_the_author() ); ?>
							</a>
						</span>
						<span class="post-hero__meta-sep" aria-hidden="true">·</span>
						<span class="post-hero__meta-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
							<?php
							printf(
								/* translators: %d: reading time in minutes */
								esc_html( _n( '%d Min. Lesezeit', '%d Min. Lesezeit', $read_time, 'ffw-theme' ) ),
								(int) $read_time
							);
							?>
						</span>
					</div>

				</div>
			</div>
		</div>

	<?php else : ?>

		<!-- ── Header without featured image ─────────────────────────────── -->
		<div class="post-header-plain">
			<div class="container">

				<nav class="post-breadcrumb post-breadcrumb--dark" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ffw-theme' ); ?>">
					<ol class="post-breadcrumb__list">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Startseite', 'ffw-theme' ); ?></a></li>
						<?php if ( $primary_cat ) : ?>
							<li><a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>"><?php echo esc_html( $primary_cat->name ); ?></a></li>
						<?php endif; ?>
						<li aria-current="page"><?php the_title(); ?></li>
					</ol>
				</nav>

				<?php if ( $primary_cat ) : ?>
					<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="post-cat-badge post-cat-badge--dark">
						<?php echo esc_html( $primary_cat->name ); ?>
					</a>
				<?php endif; ?>

				<h1 class="post-header-plain__title"><?php the_title(); ?></h1>

				<div class="post-header-plain__meta">
					<span class="post-meta-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					</span>
					<span class="post-meta-sep" aria-hidden="true">·</span>
					<span class="post-meta-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="post-author-link">
							<?php echo esc_html( get_the_author() ); ?>
						</a>
					</span>
					<span class="post-meta-sep" aria-hidden="true">·</span>
					<span class="post-meta-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
						<?php
						printf(
							esc_html( _n( '%d Min. Lesezeit', '%d Min. Lesezeit', $read_time, 'ffw-theme' ) ),
							(int) $read_time
						);
						?>
					</span>
				</div>

			</div>
		</div>

	<?php endif; ?>

	<!-- ── Article body ─────────────────────────────────────────────────── -->
	<div class="post-body">
		<div class="container post-container">

			<div class="entry-content">
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

			<?php
			$tags_list = get_the_tag_list( '', '' );
			if ( $tags_list || current_user_can( 'edit_posts' ) ) :
			?>
			<footer class="post-footer">
				<?php if ( $tags_list ) : ?>
					<div class="post-tags">
						<span class="post-tags__label"><?php esc_html_e( 'Schlagwörter:', 'ffw-theme' ); ?></span>
						<div class="post-tags__list"><?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
					</div>
				<?php endif; ?>

				<?php
				edit_post_link(
					esc_html__( 'Beitrag bearbeiten', 'ffw-theme' ),
					'<span class="post-edit-link"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> ',
					'</span>'
				);
				?>
			</footer>
			<?php endif; ?>

		</div>
	</div>

</article>
