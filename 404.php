<?php
/**
 * FFW Theme — 404 error page.
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container">
		<section class="error-404 not-found">
			<div class="error-404__content">
				<div class="error-404__number">404</div>
				<h1 class="error-404__title"><?php esc_html_e( 'Seite nicht gefunden', 'ffw-theme' ); ?></h1>
				<p class="error-404__message">
					<?php esc_html_e( 'Die gesuchte Seite existiert nicht oder wurde verschoben. Nutze die Suche oder gehe zur Startseite zurück.', 'ffw-theme' ); ?>
				</p>
				<div class="error-404__actions">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
						<?php esc_html_e( 'Zur Startseite', 'ffw-theme' ); ?>
					</a>
				</div>
				<div class="error-404__search">
					<?php get_search_form(); ?>
				</div>
			</div>
		</section>
	</div>
</main>

<?php
get_footer();
