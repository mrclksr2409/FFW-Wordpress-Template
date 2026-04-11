<?php
/**
 * FFW Theme — Elementor Compatibility
 *
 * Registers Elementor Theme Locations so Elementor Pro's Theme Builder
 * can replace header, footer, single, and archive with custom templates.
 * The theme remains fully functional without Elementor installed.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Elementor Theme Locations.
 * Called by Elementor Pro's theme manager after it loads.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager
 */
function ffw_register_elementor_locations( $manager ) {
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'ffw_register_elementor_locations' );

/**
 * Declare Elementor theme support.
 * This enables Elementor's built-in page templates:
 * - Elementor Canvas (completely blank)
 * - Elementor Full Width (no sidebar, uses theme header/footer)
 */
function ffw_elementor_theme_support() {
	add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'ffw_elementor_theme_support' );
