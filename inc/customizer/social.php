<?php
/**
 * FFW Theme Customizer — Social Media Links
 *
 * Social-Media-URLs für Footer und Header.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registriert die Social-Media-Section.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function ffw_customizer_social( $wp_customize ) {

	$wp_customize->add_section(
		'ffw_social_section',
		array(
			'title'    => __( 'Social Media Links', 'ffw-theme' ),
			'panel'    => 'ffw_theme_panel',
			'priority' => 25,
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
