<?php
/**
 * FFW Theme — WordPress Customizer Options
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

function ffw_customize_register( $wp_customize ) {

	// =========================================================================
	// Panel: FFW Theme Options
	// =========================================================================
	$wp_customize->add_panel(
		'ffw_theme_panel',
		array(
			'title'       => __( 'FFW Theme Optionen', 'ffw-theme' ),
			'description' => __( 'Einstellungen für das Feuerwehr-Theme', 'ffw-theme' ),
			'priority'    => 130,
		)
	);

	// =========================================================================
	// Section: Farben
	// =========================================================================
	$wp_customize->add_section(
		'ffw_colors_section',
		array(
			'title'       => __( 'Farben', 'ffw-theme' ),
			'description' => __( 'Alle Theme-Farben können hier individuell angepasst werden. Änderungen sind sofort in der Live-Vorschau sichtbar.', 'ffw-theme' ),
			'panel'       => 'ffw_theme_panel',
			'priority'    => 5,
		)
	);

	foreach ( ffw_get_color_settings() as $id => $color ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $color['default'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage', // Live-Preview ohne Seitenreload
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

	// =========================================================================
	// Section: Hero / Startseite
	// =========================================================================
	$wp_customize->add_section(
		'ffw_hero_section',
		array(
			'title'    => __( 'Startseite — Hero Bereich', 'ffw-theme' ),
			'panel'    => 'ffw_theme_panel',
			'priority' => 10,
		)
	);

	// Hero Title
	$wp_customize->add_setting(
		'ffw_hero_title',
		array(
			'default'           => get_bloginfo( 'name' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ffw_hero_title',
		array(
			'label'   => __( 'Hero Überschrift', 'ffw-theme' ),
			'section' => 'ffw_hero_section',
			'type'    => 'text',
		)
	);

	// Hero Subtitle
	$wp_customize->add_setting(
		'ffw_hero_subtitle',
		array(
			'default'           => get_bloginfo( 'description' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ffw_hero_subtitle',
		array(
			'label'   => __( 'Hero Untertitel', 'ffw-theme' ),
			'section' => 'ffw_hero_section',
			'type'    => 'text',
		)
	);

	// Hero Background Image
	$wp_customize->add_setting(
		'ffw_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'ffw_hero_image',
			array(
				'label'   => __( 'Hero Hintergrundbild', 'ffw-theme' ),
				'section' => 'ffw_hero_section',
			)
		)
	);

	// Hero CTA Text
	$wp_customize->add_setting(
		'ffw_hero_cta_text',
		array(
			'default'           => __( 'Einsätze ansehen', 'ffw-theme' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ffw_hero_cta_text',
		array(
			'label'   => __( 'CTA Button Text', 'ffw-theme' ),
			'section' => 'ffw_hero_section',
			'type'    => 'text',
		)
	);

	// Hero CTA URL
	$wp_customize->add_setting(
		'ffw_hero_cta_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'ffw_hero_cta_url',
		array(
			'label'   => __( 'CTA Button Link (URL)', 'ffw-theme' ),
			'section' => 'ffw_hero_section',
			'type'    => 'url',
		)
	);

	// =========================================================================
	// Section: Stats Bar
	// =========================================================================
	$wp_customize->add_section(
		'ffw_stats_section',
		array(
			'title'       => __( 'Startseite — Statistiken', 'ffw-theme' ),
			'description' => __( 'Fahrzeuge und Einsätze/Jahr werden automatisch aus dem Einsatzverwaltung-Plugin gezählt, sobald es aktiv ist. Diese Felder sind dann nur der Fallback-Wert (z. B. vor dem ersten Einsatz). Die Mitgliederzahl muss manuell gepflegt werden.', 'ffw-theme' ),
			'panel'       => 'ffw_theme_panel',
			'priority'    => 20,
		)
	);

	$stats = array(
		'ffw_stat_members'    => array( 'label' => __( 'Mitglieder (manuell)', 'ffw-theme' ),              'default' => '60+' ),
		'ffw_stat_vehicles'   => array( 'label' => __( 'Fahrzeuge (Fallback wenn Plugin inaktiv)', 'ffw-theme' ),  'default' => '5' ),
		'ffw_stat_operations' => array( 'label' => __( 'Einsätze/Jahr (Fallback wenn Plugin inaktiv)', 'ffw-theme' ), 'default' => '100+' ),
	);

	foreach ( $stats as $id => $stat ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $stat['default'],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $stat['label'],
				'section' => 'ffw_stats_section',
				'type'    => 'text',
			)
		);
	}

	// =========================================================================
	// Section: Contact Info
	// =========================================================================
	$wp_customize->add_section(
		'ffw_contact_section',
		array(
			'title'    => __( 'Kontakt & Adresse', 'ffw-theme' ),
			'panel'    => 'ffw_theme_panel',
			'priority' => 30,
		)
	);

	// Address
	$wp_customize->add_setting(
		'ffw_contact_address',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ffw_contact_address',
		array(
			'label'   => __( 'Adresse (Gerätehaus)', 'ffw-theme' ),
			'section' => 'ffw_contact_section',
			'type'    => 'textarea',
		)
	);

	// Phone
	$wp_customize->add_setting(
		'ffw_contact_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ffw_contact_phone',
		array(
			'label'   => __( 'Telefonnummer', 'ffw-theme' ),
			'section' => 'ffw_contact_section',
			'type'    => 'text',
		)
	);

	// Email
	$wp_customize->add_setting(
		'ffw_contact_email',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'ffw_contact_email',
		array(
			'label'   => __( 'E-Mail-Adresse', 'ffw-theme' ),
			'section' => 'ffw_contact_section',
			'type'    => 'email',
		)
	);

	// =========================================================================
	// Section: Social Media
	// =========================================================================
	$wp_customize->add_section(
		'ffw_social_section',
		array(
			'title'    => __( 'Social Media Links', 'ffw-theme' ),
			'panel'    => 'ffw_theme_panel',
			'priority' => 40,
		)
	);

	$social_networks = array(
		'ffw_social_facebook'  => 'Facebook',
		'ffw_social_instagram' => 'Instagram',
		'ffw_social_youtube'   => 'YouTube',
		'ffw_social_twitter'   => 'X / Twitter',
	);

	foreach ( $social_networks as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label . ' URL',
				'section' => 'ffw_social_section',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'ffw_customize_register' );

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
			// Nur ausgeben wenn der Wert vom Standard abweicht
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

