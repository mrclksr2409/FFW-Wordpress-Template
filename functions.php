<?php
/**
 * FFW Theme — functions.php
 * Bootstrap file: loads all /inc/ modules.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FFW_THEME_VERSION', '1.0.0' );
define( 'FFW_THEME_DIR', get_template_directory() );
define( 'FFW_THEME_URI', get_template_directory_uri() );

require FFW_THEME_DIR . '/inc/theme-setup.php';
require FFW_THEME_DIR . '/inc/enqueue.php';
require FFW_THEME_DIR . '/inc/widgets.php';
require FFW_THEME_DIR . '/inc/elementor.php';
require FFW_THEME_DIR . '/inc/customizer.php';
require FFW_THEME_DIR . '/inc/template-functions.php';
require FFW_THEME_DIR . '/inc/update-checker.php';
