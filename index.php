<?php
/**
 * FFW Theme — index.php
 * Required WordPress fallback template.
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container">
		<?php
		if ( have_posts() ) :
			if ( is_home() && ! is_front_page() ) :
				?>
				<header class="page-header">
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			echo '<div class="posts-grid">';
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', get_post_format() );
			endwhile;
			echo '</div>';

			the_posts_navigation();
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>
	</div>
</main>
<?php
get_footer();
