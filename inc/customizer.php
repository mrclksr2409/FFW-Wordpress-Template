<?php
/**
 * FFW Theme — WordPress Customizer Options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
			'title'    => __( 'Startseite — Statistiken', 'ffw-theme' ),
			'panel'    => 'ffw_theme_panel',
			'priority' => 20,
		)
	);

	$stats = array(
		'ffw_stat_members'    => array( 'label' => __( 'Mitglieder', 'ffw-theme' ),    'default' => '60+' ),
		'ffw_stat_vehicles'   => array( 'label' => __( 'Fahrzeuge', 'ffw-theme' ),     'default' => '5' ),
		'ffw_stat_operations' => array( 'label' => __( 'Einsätze/Jahr', 'ffw-theme' ), 'default' => '100+' ),
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
