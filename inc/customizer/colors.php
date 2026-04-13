<?php
/**
 * FFW Theme Customizer — Farben
 *
 * Farbdefinitionen, Customizer-Registration, dynamisches CSS-Output
 * und Live-Preview-JS für alle Theme-Farben.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Alle anpassbaren Farben mit ihren CSS-Custom-Property-Namen und Standardwerten.
 *
 * @return array
 */
function ffw_get_color_settings() {
	return array(
		'ffw_color_primary'      => array(
			'label'    => __( 'Akzentfarbe (Feuerwehr-Rot)', 'ffw-theme' ),
			'css_var'  => '--ffw-color-primary',
			'default'  => '#E30613',
		),
		'ffw_color_primary_dark' => array(
			'label'    => __( 'Akzentfarbe Hover / Dunkel', 'ffw-theme' ),
			'css_var'  => '--ffw-color-primary-dark',
			'default'  => '#b0040f',
		),
		'ffw_color_primary_light' => array(
			'label'    => __( 'Akzentfarbe Hell / Aktiv', 'ffw-theme' ),
			'css_var'  => '--ffw-color-primary-light',
			'default'  => '#ff3040',
		),
		'ffw_bg_base'            => array(
			'label'    => __( 'Seitenhintergrund', 'ffw-theme' ),
			'css_var'  => '--ffw-bg-base',
			'default'  => '#f5f7f9',
		),
		'ffw_bg_deep'            => array(
			'label'    => __( 'Header- & Footer-Hintergrund', 'ffw-theme' ),
			'css_var'  => '--ffw-bg-deep',
			'default'  => '#eef0f3',
		),
		'ffw_bg_card'            => array(
			'label'    => __( 'Karten-Hintergrund', 'ffw-theme' ),
			'css_var'  => '--ffw-bg-card',
			'default'  => '#ffffff',
		),
		'ffw_bg_raised'          => array(
			'label'    => __( 'Erhöhte Elemente / Zebrastreifen', 'ffw-theme' ),
			'css_var'  => '--ffw-bg-raised',
			'default'  => '#eaecef',
		),
		'ffw_text_primary'       => array(
			'label'    => __( 'Haupttext', 'ffw-theme' ),
			'css_var'  => '--ffw-text-primary',
			'default'  => '#1a1a1a',
		),
		'ffw_text_secondary'     => array(
			'label'    => __( 'Sekundärtext / Fließtext', 'ffw-theme' ),
			'css_var'  => '--ffw-text-secondary',
			'default'  => '#444444',
		),
		'ffw_text_muted'         => array(
			'label'    => __( 'Gedämpfter Text / Labels', 'ffw-theme' ),
			'css_var'  => '--ffw-text-muted',
			'default'  => '#777777',
		),
		'ffw_border_color'       => array(
			'label'    => __( 'Rahmen- & Trennlinien-Farbe', 'ffw-theme' ),
			'css_var'  => '--ffw-border-color',
			'default'  => '#dde1e7',
		),
	);
}

// ---------------------------------------------------------------------------
// Customizer-Registration
// ---------------------------------------------------------------------------

/**
 * Registriert die Farb-Section mit allen Color-Controls.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function ffw_customizer_colors( $wp_customize ) {

	$wp_customize->add_section(
		'ffw_colors_section',
		array(
			'title'       => __( 'Farben', 'ffw-theme' ),
			'description' => __( 'Alle Theme-Farben können hier individuell angepasst werden. Änderungen sind sofort in der Live-Vorschau sichtbar.', 'ffw-theme' ),
			'panel'       => 'ffw_theme_panel',
			'priority'    => 30,
		)
	);

	foreach ( ffw_get_color_settings() as $id => $color ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $color['default'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $color['label'],
					'section' => 'ffw_colors_section',
				)
			)
		);
	}
}

// ---------------------------------------------------------------------------
// Dynamisches CSS: CSS Custom Properties via wp_head ausgeben
// ---------------------------------------------------------------------------

/**
 * Gibt ein <style>-Block aus, der alle Customizer-Farben als
 * CSS Custom Properties in :root setzt und damit die Standardwerte
 * aus style.css überschreibt.
 */
function ffw_customizer_css_output() {
	$lines = array();

	foreach ( ffw_get_color_settings() as $id => $color ) {
		$value = get_theme_mod( $id, $color['default'] );
		if ( $value && $value !== $color['default'] ) {
			$lines[] = "\t" . esc_attr( $color['css_var'] ) . ': ' . sanitize_hex_color( $value ) . ';';
		}
	}

	// Abgeleitete Alpha-Farbe automatisch aus Primärfarbe berechnen
	$primary = sanitize_hex_color( get_theme_mod( 'ffw_color_primary', '#E30613' ) );
	if ( $primary ) {
		$rgb    = ffw_hex_to_rgb( $primary );
		$alpha  = 'rgba(' . $rgb . ', 0.12)';
		$lines[] = "\t--ffw-color-primary-alpha: {$alpha};";
	}

	if ( empty( $lines ) ) {
		return;
	}

	echo "\n<style id=\"ffw-customizer-css\">\n:root {\n";
	echo implode( "\n", $lines );
	echo "\n}\n</style>\n";
}
add_action( 'wp_head', 'ffw_customizer_css_output', 20 );

/**
 * Hilfsfunktion: Hex-Farbe zu "R, G, B"-String.
 *
 * @param string $hex Hex-Farbwert (mit oder ohne #).
 * @return string  Kommaseparierte RGB-Werte, z. B. "227, 6, 19"
 */
function ffw_hex_to_rgb( $hex ) {
	$hex = ltrim( $hex, '#' );
	if ( strlen( $hex ) === 3 ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	$r = hexdec( substr( $hex, 0, 2 ) );
	$g = hexdec( substr( $hex, 2, 2 ) );
	$b = hexdec( substr( $hex, 4, 2 ) );
	return "{$r}, {$g}, {$b}";
}

// ---------------------------------------------------------------------------
// Live-Preview JS laden (aktualisiert CSS-Vars ohne Seitenreload)
// ---------------------------------------------------------------------------

function ffw_customize_preview_js() {
	wp_enqueue_script(
		'ffw-customize-preview',
		FFW_THEME_URI . '/assets/js/customize-preview.js',
		array( 'customize-preview' ),
		FFW_THEME_VERSION,
		true
	);

	// Farbdefinitionen als JSON an das JS übergeben
	$color_map = array();
	foreach ( ffw_get_color_settings() as $id => $color ) {
		$color_map[ $id ] = $color['css_var'];
	}
	wp_localize_script( 'ffw-customize-preview', 'ffwColorMap', $color_map );
}
add_action( 'customize_preview_init', 'ffw_customize_preview_js' );
