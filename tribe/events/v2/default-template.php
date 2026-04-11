<?php
/**
 * FFW Theme — The Events Calendar default template override.
 * Wraps all calendar views in the theme's header/footer for consistent styling.
 *
 * @package FFW Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main tribe-events-wrapper">

	<header class="page-header">
		<div class="container">
			<h1 class="page-title"><?php esc_html_e( 'Termine & Veranstaltungen', 'ffw-theme' ); ?></h1>
			<p class="page-description"><?php esc_html_e( 'Übungen, Veranstaltungen und Termine der Feuerwehr', 'ffw-theme' ); ?></p>
		</div>
	</header>

	<div class="container">
		<?php tribe_events_before_html(); ?>
		<?php tribe_get_view(); ?>
		<?php tribe_events_after_html(); ?>
	</div>

</main>
<?php
get_footer();
