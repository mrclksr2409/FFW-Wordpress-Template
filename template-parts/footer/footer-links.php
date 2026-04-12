<?php
/**
 * FFW Theme — Footer Quick Links Column
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="footer-widget footer-widget--links">
	<h3 class="footer-widget-title"><?php esc_html_e( 'Schnelllinks', 'ffw-theme' ); ?></h3>

	<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
		<?php dynamic_sidebar( 'footer-2' ); ?>
	<?php else : ?>
		<?php
		// Default fallback navigation from the "footer-1" menu location
		wp_nav_menu(
			array(
				'theme_location' => 'footer-1',
				'menu_class'     => 'footer-menu-list',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		?>
	<?php endif; ?>
</div>
