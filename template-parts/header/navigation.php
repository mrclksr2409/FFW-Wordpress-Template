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
		<div class="mobile-search">
			<?php get_search_form(); ?>
		</div>
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

	<button
		class="search-toggle"
		aria-controls="header-search"
		aria-expanded="false"
		aria-label="<?php esc_attr_e( 'Suche öffnen', 'ffw-theme' ); ?>"
	>
		<svg class="search-toggle__icon search-toggle__icon--open" aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<circle cx="11" cy="11" r="7"></circle>
			<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
		</svg>
		<svg class="search-toggle__icon search-toggle__icon--close" aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<line x1="18" y1="6" x2="6" y2="18"></line>
			<line x1="6" y1="6" x2="18" y2="18"></line>
		</svg>
	</button>

	<div class="header-search" id="header-search" hidden>
		<?php get_search_form(); ?>
	</div>
</nav>
