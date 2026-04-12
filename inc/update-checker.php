<?php
/**
 * FFW Theme — Automatische Updates via GitHub
 *
 * Nutzt das Plugin Update Checker (yahnis-elsts/plugin-update-checker),
 * um Theme-Updates direkt aus dem GitHub-Repository zu beziehen.
 * WordPress-Admins sehen verfügbare Updates unter Design → Themes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ffw_puc_autoload = FFW_THEME_DIR . '/inc/lib/autoload.php';
if ( ! file_exists( $ffw_puc_autoload ) ) {
	return; // Graceful degradation: Library nicht vorhanden → kein Update-Check
}

require_once $ffw_puc_autoload;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$ffw_update_checker = PucFactory::buildUpdateChecker(
	'https://github.com/mrclksr2409/ffw-wordpress-template/',
	FFW_THEME_DIR . '/style.css',
	'ffw-theme'
);

// Updates auf Basis von GitHub-Releases beziehen (stabiles Release-Tag wird genutzt)
