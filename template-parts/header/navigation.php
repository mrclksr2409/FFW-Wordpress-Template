<?php
/**
 * FFW Theme — Primary Navigation + Hamburger
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Hauptnavigation', 'ffw-theme' ); ?>">
	<button
		class="menu-toggle"
		aria-controls="primary-menu"
		aria-expanded="false"
		aria-label="<?php esc_attr_e( 'Menü öffnen', 'ffw-theme' ); ?>"
	>
		<span class="hamburger-box" aria-hidden="true">
			<span class="hamburger-inner"></span>
		</span>
		<span class="menu-toggle-label"><?php esc_html_e( 'Menü', 'ffw-theme' ); ?></span>
	</button>

	<div class="primary-menu-container" id="primary-menu">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-nav',
				'menu_class'     => 'primary-nav',
				'container'      => false,
				'fallback_cb'    => 'ffw_fallback_menu',
				'walker'         => new FFW_Mega_Menu_Walker(),
			)
		);
		?>
	</div>
</nav>
