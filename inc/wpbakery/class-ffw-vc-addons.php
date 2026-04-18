<?php
/**
 * FFW Theme — WPBakery Visual Composer Addons Loader
 *
 * Registers all theme shortcodes as native WPBakery elements.
 * Follows the pattern of the official wpbakery/dev-example repository:
 * a single loader that hooks into `vc_before_init` and pulls in one file
 * per element from /addons/.
 *
 * The rendering stays with the existing `add_shortcode()` callbacks in
 * inc/shortcodes.php — these files only describe the editor UI.
 *
 * @package FFW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'FFW_VC_CATEGORY' ) ) {
	define( 'FFW_VC_CATEGORY', __( 'FFW Theme', 'ffw-theme' ) );
}

if ( ! defined( 'FFW_VC_ADDONS_DIR' ) ) {
	define( 'FFW_VC_ADDONS_DIR', FFW_THEME_DIR . '/inc/wpbakery' );
}

if ( ! defined( 'FFW_VC_ADDONS_URI' ) ) {
	define( 'FFW_VC_ADDONS_URI', FFW_THEME_URI . '/inc/wpbakery' );
}

if ( ! class_exists( 'FFW_VC_Addons' ) ) {

	final class FFW_VC_Addons {

		private static $instance = null;

		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		private function __construct() {
			add_action( 'vc_before_init', array( $this, 'register_elements' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
			add_action( 'vc_backend_editor_enqueue_js_css', array( $this, 'enqueue_admin_assets' ) );
			add_action( 'vc_frontend_editor_enqueue_js_css', array( $this, 'enqueue_admin_assets' ) );
		}

		public function register_elements() {
			$this->load_shortcode_classes();
			$this->load_addons();
		}

		private function load_shortcode_classes() {
			$classes = array(
				'class-wpb-ffw-fahrzeug-info.php',
				'class-wpb-ffw-fi.php',
			);
			foreach ( $classes as $file ) {
				$path = FFW_VC_ADDONS_DIR . '/class/' . $file;
				if ( file_exists( $path ) ) {
					require_once $path;
				}
			}
		}

		private function load_addons() {
			$addons = array(
				'vc-ffw-posts.php',
				'vc-ffw-child-pages.php',
				'vc-ffw-fahrzeug-info.php',
				'vc-ffw-fi.php',
				'vc-ffw-carousel.php',
			);
			foreach ( $addons as $file ) {
				$path = FFW_VC_ADDONS_DIR . '/addons/' . $file;
				if ( file_exists( $path ) ) {
					require_once $path;
				}
			}
		}

		public function enqueue_admin_assets() {
			wp_enqueue_style(
				'ffw-vc-admin',
				FFW_VC_ADDONS_URI . '/assets/admin.css',
				array(),
				defined( 'FFW_THEME_VERSION' ) ? FFW_THEME_VERSION : false
			);
		}
	}

	FFW_VC_Addons::init();
}
