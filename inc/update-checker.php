<?php
/**
 * FFW Theme — Automatische Updates via GitHub (Branch main)
 *
 * Nutzt das Plugin Update Checker (yahnis-elsts/plugin-update-checker),
 * um Theme-Updates direkt vom GitHub-Branch "main" zu beziehen.
 * WordPress-Admins sehen verfügbare Updates unter Design → Themes.
 *
 * Ein neues Update wird automatisch erkannt, sobald auf dem Branch "main"
 * eine höhere Version in style.css steht. GitHub-Releases und Tags werden
 * dabei nicht berücksichtigt.
 *
 * @author Marcel Kaiser
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ffw_puc_autoload = FFW_THEME_DIR . '/inc/lib/autoload.php';
if ( ! file_exists( $ffw_puc_autoload ) ) {
	// Graceful degradation: library missing → skip auto-updates, but warn in debug mode
	// so operators notice a broken deploy rather than silently never receiving updates.
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		_doing_it_wrong(
			'ffw_update_checker',
			esc_html__( 'Plugin Update Checker library not found at /inc/lib/autoload.php — theme auto-updates are disabled.', 'ffw-theme' ),
			esc_html( FFW_THEME_VERSION )
		);
	}
	return;
}

require_once $ffw_puc_autoload;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Konfiguration des GitHub Update Checkers.
 *
 * Updatequelle ist ausschließlich der aktuelle Stand des Branches "main".
 * Die Versionsnummer wird aus style.css auf main gelesen und mit der
 * installierten Version verglichen.
 *
 * Entwickelt wird auf "beta"; erst nach dem Merge nach "main" werden
 * Updates an installierte Seiten ausgeliefert.
 */
$ffw_update_checker = PucFactory::buildUpdateChecker(
	'https://github.com/mrclksr2409/FFW-Wordpress-Template/',
	FFW_THEME_DIR . '/style.css',
	'ffw-theme'
);

$ffw_update_checker->setBranch( 'main' );

// Releases und Tags ignorieren, nur der Branch-Stand zählt.
add_filter(
	$ffw_update_checker->getUniqueName( 'vcs_update_detection_strategies' ),
	static function ( $strategies ) {
		unset( $strategies['latest_release'], $strategies['latest_tag'] );
		return $strategies;
	}
);
