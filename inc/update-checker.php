<?php
/**
 * FFW Theme — Automatische Updates via GitHub Releases
 *
 * Nutzt das Plugin Update Checker (yahnis-elsts/plugin-update-checker),
 * um Theme-Updates direkt aus GitHub-Releases zu beziehen.
 * WordPress-Admins sehen verfügbare Updates unter Design → Themes.
 *
 * Ein neues Update wird automatisch erkannt, sobald ein neues Release
 * auf GitHub erstellt wird. Die Version im Release-Tag (z. B. "v1.2.3")
 * muss größer sein als die aktuelle Version in style.css.
 *
 * Pre-Releases und Drafts werden automatisch übersprungen.
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
 * Ohne setBranch() wird automatisch der neueste stabile GitHub-Release
 * als Updatequelle genutzt. Die Versionsnummer wird aus dem Release-Tag
 * gelesen (z. B. "v1.2.3" → "1.2.3") und mit der Version in style.css
 * verglichen.
 */
$ffw_update_checker = PucFactory::buildUpdateChecker(
	'https://github.com/mrclksr2409/FFW-Wordpress-Template/',
	FFW_THEME_DIR . '/style.css',
	'ffw-theme'
);
