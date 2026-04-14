<?php
/**
 * FFW Theme — Scripts & Styles Enqueueing
 *
 * Alle Theme-Styles sind in einer einzigen style.css konsolidiert.
 * tribe/events/tribe-events.css wird automatisch vom TEC-Plugin geladen.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme assets.
 */
function ffw_enqueue_assets() {
	// Google Fonts: Inter + Oswald
	wp_enqueue_style(
		'ffw-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Oswald:wght@400;600;700&display=swap',
		array(),
		null
	);

	// Konsolidiertes Theme-Stylesheet (style.css)
	wp_enqueue_style(
		'ffw-style',
		get_stylesheet_uri(),
		array( 'ffw-google-fonts' ),
		FFW_THEME_VERSION
	);

	// Mega Menu Styles
	wp_enqueue_style(
		'ffw-mega-menu',
		FFW_THEME_URI . '/assets/css/mega-menu.css',
		array( 'ffw-style' ),
		FFW_THEME_VERSION
	);

	// Shortcode Styles
	wp_enqueue_style(
		'ffw-shortcodes',
		FFW_THEME_URI . '/assets/css/shortcodes.css',
		array( 'ffw-style' ),
		FFW_THEME_VERSION
	);

	// Navigation JS (mobile hamburger + sticky header)
	wp_enqueue_script(
		'ffw-navigation-js',
		FFW_THEME_URI . '/assets/js/navigation.js',
		array(),
		FFW_THEME_VERSION,
		true
	);

	// Main JS
	wp_enqueue_script(
		'ffw-main',
		FFW_THEME_URI . '/assets/js/main.js',
		array( 'ffw-navigation-js' ),
		FFW_THEME_VERSION,
		true
	);

	// Kommentar-Reply-Script
	if ( is_singular() && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ffw_enqueue_assets' );

/**
 * Add preconnect for Google Fonts performance.
 */
function ffw_preconnect_google_fonts( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = array(
			'href'        => 'https://fonts.googleapis.com',
			'crossorigin' => false,
		);
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => true,
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'ffw_preconnect_google_fonts', 10, 2 );
