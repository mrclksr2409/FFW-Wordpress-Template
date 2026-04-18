<?php
/**
 * WPBakery mapping for [ffw_child_pages] — Unterseiten-Karten.
 *
 * Source callback: inc/shortcodes.php :: ffw_child_pages_shortcode()
 *
 * @package FFW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

vc_map(
	array(
		'name'            => __( 'FFW: Unterseiten-Karten', 'ffw-theme' ),
		'base'            => 'ffw_child_pages',
		'category'        => FFW_VC_CATEGORY,
		'icon'            => 'icon-ffw-child-pages',
		'description'     => __( 'Listet direkte Unterseiten der aktuellen Seite als Karten.', 'ffw-theme' ),
		'content_element' => true,
		'show_settings_on_create' => true,
		'params'          => array(
			array(
				'type'        => 'textfield',
				'param_name'  => 'parent',
				'heading'     => __( 'Eltern-Seiten-ID', 'ffw-theme' ),
				'description' => __( '0 oder leer = aktuelle Seite verwenden.', 'ffw-theme' ),
				'value'       => '0',
				'admin_label' => true,
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'exclude',
				'heading'     => __( 'Ausgeschlossene IDs', 'ffw-theme' ),
				'description' => __( 'Kommaseparierte Seiten-IDs, die nicht angezeigt werden sollen.', 'ffw-theme' ),
				'value'       => '',
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'more_text',
				'heading'     => __( 'Button-Text', 'ffw-theme' ),
				'description' => __( 'Leer = kein Button unter dem Grid.', 'ffw-theme' ),
				'value'       => '',
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'more_url',
				'heading'     => __( 'Button-URL', 'ffw-theme' ),
				'description' => __( 'Optional. Leer = Permalink der Elternseite.', 'ffw-theme' ),
				'value'       => '',
				'dependency'  => array(
					'element'   => 'more_text',
					'not_empty' => true,
				),
			),
		),
	)
);
