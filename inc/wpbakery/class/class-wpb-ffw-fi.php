<?php
/**
 * WPBakery child stub for [ffw_fi].
 *
 * Required so WPBakery treats this as a proper child element of
 * ffw_fahrzeug_info. Rendering stays with the original shortcode
 * callback (inc/shortcodes.php :: ffw_fi_shortcode).
 *
 * @package FFW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Ffw_Fi' ) ) {
	class WPBakeryShortCode_Ffw_Fi extends WPBakeryShortCode {}
}
