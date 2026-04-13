<?php
/**
 * FFW Theme Customizer — Startseite
 *
 * Hero-Bereich und Statistiken-Section für die Startseite.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registriert die Startseite-Sections (Hero + Statistiken).
 *
 * @param WP_Customize_Manager $wp_customize
 */
function ffw_customizer_homepage( $wp_customize ) {

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
			'priority'    => 15,
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
}
