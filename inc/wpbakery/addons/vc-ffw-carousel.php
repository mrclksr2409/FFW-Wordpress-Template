<?php
/**
 * WPBakery mapping for [ffw_carousel] — Bilder-Karussell (Swiper).
 *
 * Source callback: inc/shortcodes.php :: ffw_carousel_shortcode()
 *
 * @package FFW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ffw_image_sizes = array();
foreach ( get_intermediate_image_sizes() as $size ) {
	$ffw_image_sizes[ $size ] = $size;
}
$ffw_image_sizes = array_merge( array( 'ffw-card' => 'ffw-card' ), $ffw_image_sizes );

vc_map(
	array(
		'name'            => __( 'FFW: Bilder-Karussell', 'ffw-theme' ),
		'base'            => 'ffw_carousel',
		'category'        => FFW_VC_CATEGORY,
		'icon'            => 'icon-ffw-carousel',
		'description'     => __( 'Swiper-basiertes Bilder-Karussell aus der Mediathek.', 'ffw-theme' ),
		'content_element' => true,
		'show_settings_on_create' => true,
		'params'          => array(
			array(
				'type'        => 'attach_images',
				'param_name'  => 'ids',
				'heading'     => __( 'Bilder', 'ffw-theme' ),
				'description' => __( 'Bilder aus der Mediathek auswählen.', 'ffw-theme' ),
				'value'       => '',
				'admin_label' => true,
			),
			array(
				'type'        => 'dropdown',
				'param_name'  => 'image_size',
				'heading'     => __( 'Bildgröße', 'ffw-theme' ),
				'description' => __( 'Registrierte WordPress-Bildgröße.', 'ffw-theme' ),
				'value'       => $ffw_image_sizes,
				'std'         => 'ffw-card',
			),
			array(
				'type'        => 'dropdown',
				'param_name'  => 'slides_per_view',
				'heading'     => __( 'Slides pro Ansicht (Desktop)', 'ffw-theme' ),
				'value'       => array(
					'1'    => '1',
					'2'    => '2',
					'3'    => '3',
					'4'    => '4',
					'5'    => '5',
					'6'    => '6',
					'auto' => __( 'Automatisch', 'ffw-theme' ),
				),
				'std'         => '1',
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'speed',
				'heading'     => __( 'Übergangsgeschwindigkeit (ms)', 'ffw-theme' ),
				'description' => __( 'Dauer eines Slide-Übergangs in Millisekunden.', 'ffw-theme' ),
				'value'       => '600',
				'group'       => __( 'Verhalten', 'ffw-theme' ),
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'autoplay',
				'heading'     => __( 'Autoplay (Sekunden)', 'ffw-theme' ),
				'description' => __( '0 = Autoplay deaktiviert.', 'ffw-theme' ),
				'value'       => '0',
				'group'       => __( 'Verhalten', 'ffw-theme' ),
			),
			array(
				'type'        => 'checkbox',
				'param_name'  => 'loop',
				'heading'     => __( 'Endlosschleife', 'ffw-theme' ),
				'value'       => array( __( 'Aktivieren', 'ffw-theme' ) => 'true' ),
				'group'       => __( 'Verhalten', 'ffw-theme' ),
			),
			array(
				'type'        => 'checkbox',
				'param_name'  => 'partial_view',
				'heading'     => __( 'Nachbar-Slides anschneiden', 'ffw-theme' ),
				'value'       => array( __( 'Aktivieren', 'ffw-theme' ) => 'true' ),
				'group'       => __( 'Layout', 'ffw-theme' ),
			),
			array(
				'type'        => 'checkbox',
				'param_name'  => 'hide_pagination',
				'heading'     => __( 'Pagination-Dots ausblenden', 'ffw-theme' ),
				'value'       => array( __( 'Ausblenden', 'ffw-theme' ) => 'true' ),
				'group'       => __( 'Layout', 'ffw-theme' ),
			),
			array(
				'type'        => 'checkbox',
				'param_name'  => 'hide_nav',
				'heading'     => __( 'Prev/Next-Buttons ausblenden', 'ffw-theme' ),
				'value'       => array( __( 'Ausblenden', 'ffw-theme' ) => 'true' ),
				'group'       => __( 'Layout', 'ffw-theme' ),
			),
		),
	)
);
