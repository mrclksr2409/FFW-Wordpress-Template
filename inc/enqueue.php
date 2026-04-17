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
 * Return ".min" in production, empty string when SCRIPT_DEBUG is on.
 */
function ffw_asset_suffix() {
	return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
}

/**
 * Enqueue theme assets.
 */
function ffw_enqueue_assets() {
	$min = ffw_asset_suffix();

	// Google Fonts: Inter + Oswald (Weights auf tatsächlich benötigte reduziert).
	wp_enqueue_style(
		'ffw-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Oswald:wght@600;700&display=swap',
		array(),
		null
	);

	// Konsolidiertes Theme-Stylesheet (style.css / style.min.css).
	$style_rel = 'style' . $min . '.css';
	$style_path = FFW_THEME_DIR . '/' . $style_rel;
	$style_uri  = file_exists( $style_path ) ? FFW_THEME_URI . '/' . $style_rel : get_stylesheet_uri();
	wp_enqueue_style(
		'ffw-style',
		$style_uri,
		array( 'ffw-google-fonts' ),
		FFW_THEME_VERSION
	);

	// Mega Menu Styles.
	wp_enqueue_style(
		'ffw-mega-menu',
		FFW_THEME_URI . '/assets/css/mega-menu' . $min . '.css',
		array( 'ffw-style' ),
		FFW_THEME_VERSION
	);

	// Shortcode Styles.
	wp_enqueue_style(
		'ffw-shortcodes',
		FFW_THEME_URI . '/assets/css/shortcodes' . $min . '.css',
		array( 'ffw-style' ),
		FFW_THEME_VERSION
	);

	// Navigation JS (mobile hamburger + sticky header).
	wp_enqueue_script(
		'ffw-navigation-js',
		FFW_THEME_URI . '/assets/js/navigation' . $min . '.js',
		array(),
		FFW_THEME_VERSION,
		true
	);

	// Main JS.
	wp_enqueue_script(
		'ffw-main',
		FFW_THEME_URI . '/assets/js/main' . $min . '.js',
		array( 'ffw-navigation-js' ),
		FFW_THEME_VERSION,
		true
	);

	// Kommentar-Reply-Script.
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

/**
 * Preload the front-page hero image so the LCP element starts downloading
 * before the CSS that references it has parsed.
 */
function ffw_preload_hero_image() {
	if ( ! is_front_page() ) {
		return;
	}
	$img = get_theme_mod( 'ffw_hero_image', '' );
	if ( ! $img ) {
		return;
	}
	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n",
		esc_url( $img )
	);
}
add_action( 'wp_head', 'ffw_preload_hero_image', 2 );

/**
 * Remove WordPress core emoji scripts and styles. Modern browsers render
 * emoji natively; the detection script is pure overhead.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'emoji_svg_url', '__return_false' );
add_filter( 'tiny_mce_plugins', function ( $plugins ) {
	return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
} );

/**
 * Remove oEmbed discovery links and the wp-embed host script on the frontend
 * (no visitor-facing feature depends on them in this theme).
 */
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
function ffw_dequeue_wp_embed() {
	wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'ffw_dequeue_wp_embed' );

/**
 * Drop Gutenberg block-library CSS on templates that never render block content.
 * Front page, einsatz archive and single einsatz pages use custom templates —
 * the ~22 KB of block CSS is dead weight there and the single biggest contributor
 * to "unused CSS" warnings in PageSpeed.
 */
function ffw_dequeue_block_css() {
	if ( is_front_page()
		|| is_post_type_archive( 'einsatz' )
		|| is_singular( 'einsatz' ) ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'classic-theme-styles' );
		wp_dequeue_style( 'global-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'ffw_dequeue_block_css', 100 );
