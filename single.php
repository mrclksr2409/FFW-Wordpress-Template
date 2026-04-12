<?php
/**
 * FFW Theme — Single post template.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :

	while ( have_posts() ) :
		the_post();

		// Meta for page-header
		$content     = get_the_content();
		$word_count  = str_word_count( wp_strip_all_tags( $content ) );
		$read_time   = max( 1, (int) ceil( $word_count / 200 ) );
		$categories  = get_the_category();
		$primary_cat = ! empty( $categories ) ? $categories[0] : null;
?>
<main id="primary" class="site-main">

	<header class="page-header">
		<div class="container">

			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ffw-theme' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Startseite', 'ffw-theme' ); ?></a>
				<?php if ( $primary_cat ) : ?>
					<span aria-hidden="true">›</span>
					<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>"><?php echo esc_html( $primary_cat->name ); ?></a>
				<?php endif; ?>
				<span aria-hidden="true">›</span>
				<span aria-current="page"><?php the_title(); ?></span>
			</nav>

			<?php if ( $primary_cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="badge post-cat-badge">
					<?php echo esc_html( $primary_cat->name ); ?>
				</a>
			<?php endif; ?>

			<h1 class="page-title"><?php the_title(); ?></h1>

			<p class="page-description">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<span class="page-header__sep">·</span>
				<?php esc_html_e( 'von', 'ffw-theme' ); ?>
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="post-author-link">
					<?php echo esc_html( get_the_author() ); ?>
				</a>
				<span class="page-header__sep">·</span>
				<?php
				printf(
					/* translators: %d: reading time in minutes */
					esc_html( _n( '%d Min. Lesezeit', '%d Min. Lesezeit', $read_time, 'ffw-theme' ) ),
					(int) $read_time
				);
				?>
			</p>

		</div>
	</header>

	<div class="container">
		<?php
		get_template_part( 'template-parts/content', 'single' );
		?>
	</div>

</main>
<?php
	endwhile;

endif;
get_footer();
