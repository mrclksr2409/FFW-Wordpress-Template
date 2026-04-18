<?php
/**
 * WPBakery mapping for [ffw_fi] — Zusatzfeld innerhalb von [ffw_fahrzeug_info].
 *
 * Child element: darf ausschließlich in ffw_fahrzeug_info eingefügt werden.
 * Source callback: inc/shortcodes.php :: ffw_fi_shortcode()
 *
 * @package FFW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

vc_map(
	array(
		'name'            => __( 'FFW: Zusatzfeld', 'ffw-theme' ),
		'base'            => 'ffw_fi',
		'category'        => FFW_VC_CATEGORY,
		'icon'            => 'icon-ffw-fi',
		'description'     => __( 'Zusätzliche Label-/Wert-Zeile innerhalb einer Fahrzeug-Info-Box.', 'ffw-theme' ),
		'as_child'        => array( 'only' => 'ffw_fahrzeug_info' ),
		'content_element' => true,
		'show_settings_on_create' => true,
		'params'          => array(
			array(
				'type'        => 'textfield',
				'param_name'  => 'label',
				'heading'     => __( 'Feldname', 'ffw-theme' ),
				'description' => __( 'Z. B. "Funkkennung" oder "Tankinhalt".', 'ffw-theme' ),
				'value'       => '',
				'admin_label' => true,
			),
			array(
				'type'        => 'textarea',
				'param_name'  => 'content',
				'heading'     => __( 'Wert', 'ffw-theme' ),
				'description' => __( 'Der Wert, der neben dem Feldnamen angezeigt wird.', 'ffw-theme' ),
				'value'       => '',
			),
		),
	)
);
