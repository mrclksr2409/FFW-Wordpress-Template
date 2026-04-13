<?php
/**
 * FFW Theme Customizer — Kontakt & Adresse
 *
 * Kontaktdaten für Footer und Kontaktseiten.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registriert die Kontakt-Section.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function ffw_customizer_contact( $wp_customize ) {

	$wp_customize->add_section(
		'ffw_contact_section',
		array(
			'title'    => __( 'Kontakt & Adresse', 'ffw-theme' ),
			'panel'    => 'ffw_theme_panel',
			'priority' => 20,
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
}
