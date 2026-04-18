<?php
/**
 * WPBakery mapping for [ffw_fahrzeug_info] — Fahrzeug-Info-Container.
 *
 * Parent element: nimmt [ffw_fi]-Kinder auf.
 * Source callback: inc/shortcodes.php :: ffw_fahrzeug_info_shortcode()
 *
 * @package FFW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

vc_map(
	array(
		'name'                    => __( 'FFW: Fahrzeug-Info', 'ffw-theme' ),
		'base'                    => 'ffw_fahrzeug_info',
		'category'                => FFW_VC_CATEGORY,
		'icon'                    => 'icon-ffw-fahrzeug',
		'description'             => __( 'Info-Box mit Fahrzeugdaten + optionale Zusatzfelder.', 'ffw-theme' ),
		'as_parent'               => array( 'only' => 'ffw_fi' ),
		'is_container'            => true,
		'content_element'         => true,
		'show_settings_on_create' => true,
		'js_view'                 => 'VcColumnView',
		'params'                  => array(
			array(
				'type'        => 'textfield',
				'param_name'  => 'title',
				'heading'     => __( 'Überschrift', 'ffw-theme' ),
				'description' => __( 'Optional. Wird über der Info-Liste angezeigt.', 'ffw-theme' ),
				'value'       => '',
				'admin_label' => true,
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'rufname',
				'heading'    => __( 'Rufname', 'ffw-theme' ),
				'value'      => '',
				'group'      => __( 'Fahrzeugdaten', 'ffw-theme' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'baujahr',
				'heading'    => __( 'Baujahr', 'ffw-theme' ),
				'value'      => '',
				'group'      => __( 'Fahrzeugdaten', 'ffw-theme' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'indienststellung',
				'heading'    => __( 'Indienststellung', 'ffw-theme' ),
				'value'      => '',
				'group'      => __( 'Fahrzeugdaten', 'ffw-theme' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'fahrgestell',
				'heading'    => __( 'Fahrgestell', 'ffw-theme' ),
				'value'      => '',
				'group'      => __( 'Fahrzeugdaten', 'ffw-theme' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'motorleistung',
				'heading'    => __( 'Motorleistung', 'ffw-theme' ),
				'value'      => '',
				'group'      => __( 'Fahrzeugdaten', 'ffw-theme' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'gesamtgewicht',
				'heading'    => __( 'Zulässiges Gesamtgewicht', 'ffw-theme' ),
				'value'      => '',
				'group'      => __( 'Fahrzeugdaten', 'ffw-theme' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'aufbau',
				'heading'    => __( 'Aufbau', 'ffw-theme' ),
				'value'      => '',
				'group'      => __( 'Fahrzeugdaten', 'ffw-theme' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'besatzung',
				'heading'    => __( 'Besatzung', 'ffw-theme' ),
				'description' => __( 'Z. B. "1/8".', 'ffw-theme' ),
				'value'      => '',
				'group'      => __( 'Fahrzeugdaten', 'ffw-theme' ),
			),
		),
	)
);
