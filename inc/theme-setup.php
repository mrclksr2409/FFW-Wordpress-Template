<?php
/**
 * FFW Theme — Theme Setup
 * Registers theme supports, menus, and image sizes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ffw_theme_setup() {
	// Text domain
	load_theme_textdomain( 'ffw-theme', FFW_THEME_DIR . '/languages' );

	// Content width
	$GLOBALS['content_width'] = 1200;

	// Theme supports
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'custom-background',
		array(
			'default-color' => '1a1a1a',
		)
	);

	// Navigation menus
	register_nav_menus(
		array(
			'primary'  => __( 'Hauptmenü', 'ffw-theme' ),
			'footer-1' => __( 'Footer Schnelllinks', 'ffw-theme' ),
			'social'   => __( 'Social Media', 'ffw-theme' ),
		)
	);

	// Image sizes
	add_image_size( 'ffw-hero',    1920, 600,  true );
	add_image_size( 'ffw-card',    600,  400,  true );
	add_image_size( 'ffw-thumb',   300,  200,  true );
	add_image_size( 'ffw-vehicle', 800,  533,  true );
	add_image_size( 'ffw-square',  400,  400,  true );

	// Editor color palette (matches CSS vars)
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Feuerwehr-Rot', 'ffw-theme' ),
				'slug'  => 'primary',
				'color' => '#E30613',
			),
			array(
				'name'  => __( 'Dunkel', 'ffw-theme' ),
				'slug'  => 'dark',
				'color' => '#1a1a1a',
			),
			array(
				'name'  => __( 'Karte', 'ffw-theme' ),
				'slug'  => 'card',
				'color' => '#2d2d2d',
			),
			array(
				'name'  => __( 'Weiß', 'ffw-theme' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
		)
	);
}
add_action( 'after_setup_theme', 'ffw_theme_setup' );

/**
 * Add custom image size names to the media uploader.
 */
function ffw_custom_image_sizes( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'ffw-hero'    => __( 'FFW Hero (1920×600)', 'ffw-theme' ),
			'ffw-card'    => __( 'FFW Karte (600×400)', 'ffw-theme' ),
			'ffw-vehicle' => __( 'FFW Fahrzeug (800×533)', 'ffw-theme' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'ffw_custom_image_sizes' );
