<?php
/**
 * FFW Theme — Single post template.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :

	while ( have_posts() ) :
		the_post();
?>
<main id="primary" class="site-main">

	<header class="page-header">
		<div class="container">
			<h1 class="page-title"><?php the_title(); ?></h1>
			<p class="page-description">
				<?php
				echo esc_html( get_the_date() );
				if ( has_category() ) :
					echo ' &mdash; ';
					the_category( ', ' );
				endif;
				?>
			</p>
		</div>
	</header>

	<div class="container">
		<div class="content-area content-area--single">
			<?php
			get_template_part( 'template-parts/content', 'single' );

			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Vorheriger Beitrag', 'ffw-theme' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Nächster Beitrag', 'ffw-theme' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</div>
	</div>

</main>
<?php
	endwhile;

endif;
get_footer();
