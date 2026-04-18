<?php
/**
 * WPBakery container stub for [ffw_fahrzeug_info].
 *
 * Required so that WPBakery recognises the element as a parent container
 * that accepts [ffw_fi] children. Rendering stays with the original
 * shortcode callback (inc/shortcodes.php :: ffw_fahrzeug_info_shortcode).
 *
 * @package FFW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) && ! class_exists( 'WPBakeryShortCode_Ffw_Fahrzeug_Info' ) ) {
	class WPBakeryShortCode_Ffw_Fahrzeug_Info extends WPBakeryShortCodesContainer {}
}
