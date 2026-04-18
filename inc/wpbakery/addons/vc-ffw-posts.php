<?php
/**
 * WPBakery mapping for [ffw_posts] — Beitragsliste.
 *
 * Source callback: inc/shortcodes.php :: ffw_posts_shortcode()
 *
 * @package FFW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

vc_map(
	array(
		'name'            => __( 'FFW: Beitragsliste', 'ffw-theme' ),
		'base'            => 'ffw_posts',
		'category'        => FFW_VC_CATEGORY,
		'icon'            => 'icon-ffw-posts',
		'description'     => __( 'Aktuelle Beiträge als 3-spaltiges Grid.', 'ffw-theme' ),
		'content_element' => true,
		'show_settings_on_create' => true,
		'params'          => array(
			array(
				'type'        => 'textfield',
				'param_name'  => 'limit',
				'heading'     => __( 'Anzahl Beiträge', 'ffw-theme' ),
				'description' => __( 'Wie viele Beiträge sollen angezeigt werden?', 'ffw-theme' ),
				'value'       => '6',
				'admin_label' => true,
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'category',
				'heading'     => __( 'Kategorie-Slug(s)', 'ffw-theme' ),
				'description' => __( 'Leer = alle Kategorien. Mehrere Slugs mit Komma trennen (z. B. "news,presse").', 'ffw-theme' ),
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
				'description' => __( 'Optional. Leer = konfigurierte Beitragsseite.', 'ffw-theme' ),
				'value'       => '',
				'dependency'  => array(
					'element'   => 'more_text',
					'not_empty' => true,
				),
			),
		),
	)
);
