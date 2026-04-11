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

<style>
.error-404 {
	min-height: 60vh;
	display: flex;
	align-items: center;
	justify-content: center;
	text-align: center;
	padding: var(--ffw-spacing-xl) 0;
}

.error-404__number {
	font-family: var(--ffw-font-heading);
	font-size: clamp(6rem, 20vw, 12rem);
	font-weight: 700;
	color: var(--ffw-color-primary);
	line-height: 1;
	opacity: 0.3;
	margin-bottom: var(--ffw-spacing-md);
}

.error-404__title {
	font-size: 2rem;
	margin-bottom: var(--ffw-spacing-md);
}

.error-404__message {
	color: var(--ffw-text-muted);
	max-width: 500px;
	margin: 0 auto var(--ffw-spacing-lg);
}

.error-404__search {
	margin-top: var(--ffw-spacing-lg);
	max-width: 400px;
	margin-left: auto;
	margin-right: auto;
}
</style>
<?php
get_footer();
