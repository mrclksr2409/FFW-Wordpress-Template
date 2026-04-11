<?php
/**
 * FFW Theme — Einsatz Archive template.
 * WordPress template hierarchy: archive-einsatz.php
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) :
	$current_year = get_query_var( 'year' ) ? intval( get_query_var( 'year' ) ) : intval( date( 'Y' ) );
?>
<main id="primary" class="site-main einsatz-archive">

	<header class="page-header einsatz-archive__header">
		<div class="container">
			<h1 class="page-title">
				<?php esc_html_e( 'Einsätze', 'ffw-theme' ); ?>
				<?php if ( get_query_var( 'year' ) ) : ?>
					<span class="page-title__year"><?php echo esc_html( $current_year ); ?></span>
				<?php endif; ?>
			</h1>
			<p class="page-description"><?php esc_html_e( 'Übersicht aller Feuerwehreinsätze', 'ffw-theme' ); ?></p>
		</div>
	</header>

	<div class="container">

		<?php
		// Year navigation — The Einsatzverwaltung plugin provides [einsatzjahre] shortcode
		if ( shortcode_exists( 'einsatzjahre' ) ) :
			echo '<div class="einsatz-year-nav">' . do_shortcode( '[einsatzjahre]' ) . '</div>';
		endif;
		?>

		<?php if ( have_posts() ) : ?>

			<div class="einsatz-archive__stats">
				<p class="einsatz-archive__count">
					<?php
					printf(
						/* translators: %d: number of operations */
						esc_html( _n( '%d Einsatz', '%d Einsätze', $wp_query->found_posts, 'ffw-theme' ) ),
						esc_html( $wp_query->found_posts )
					);
					if ( get_query_var( 'year' ) ) :
						echo ' ' . esc_html( sprintf( __( 'im Jahr %d', 'ffw-theme' ), $current_year ) );
					endif;
					?>
				</p>
			</div>

			<div class="einsatz-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/einsatz/einsatz-card' );
				endwhile;
				?>
			</div>

			<?php the_posts_pagination(); ?>

		<?php else : ?>
			<div class="einsatz-archive__empty">
				<p><?php esc_html_e( 'Für diesen Zeitraum wurden keine Einsätze gefunden.', 'ffw-theme' ); ?></p>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'einsatz' ) ); ?>" class="btn btn--outline">
					<?php esc_html_e( 'Alle Einsätze anzeigen', 'ffw-theme' ); ?>
				</a>
			</div>
		<?php endif; ?>

	</div>
</main>
<?php
endif;
get_footer();
