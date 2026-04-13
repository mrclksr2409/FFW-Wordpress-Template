<?php
/**
 * FFW Theme — Blog/Beitragsübersicht (is_home() && !is_front_page()).
 * WordPress nutzt diese Datei wenn eine statische Startseite und eine
 * separate Beitragsseite in Einstellungen › Lesen konfiguriert ist.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) :
?>
<main id="primary" class="site-main">

	<header class="page-header">
		<div class="container">
			<?php
			$posts_page_id = (int) get_option( 'page_for_posts' );
			if ( $posts_page_id ) :
				echo '<h1 class="page-title">' . esc_html( get_the_title( $posts_page_id ) ) . '</h1>';
			else :
				echo '<h1 class="page-title">' . esc_html__( 'Aktuelles', 'ffw-theme' ) . '</h1>';
			endif;
			?>
			<p class="page-description"><?php esc_html_e( 'Neuigkeiten und Berichte der Feuerwehr', 'ffw-theme' ); ?></p>
		</div>
	</header>

	<div class="container">
		<?php if ( have_posts() ) : ?>

			<div class="posts-grid posts-grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
				?>
			</div>

			<?php the_posts_pagination(); ?>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>

</main>
<?php
endif;
get_footer();
