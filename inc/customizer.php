<?php
/**
 * FFW Theme — WordPress Customizer Options
 *
 * Orchestrator: lädt die modularen Customizer-Dateien und registriert
 * das Haupt-Panel mit allen Sections in logischer Reihenfolge.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Modulare Customizer-Dateien laden.
require FFW_THEME_DIR . '/inc/customizer/colors.php';
require FFW_THEME_DIR . '/inc/customizer/homepage.php';
require FFW_THEME_DIR . '/inc/customizer/contact.php';
require FFW_THEME_DIR . '/inc/customizer/social.php';

/**
 * Registriert alle Customizer-Panels, Sections, Settings und Controls.
 *
 * Reihenfolge orientiert sich an der Nutzungshäufigkeit für den Admin:
 * 1. Startseite (Hero + Statistiken) — wird am häufigsten bearbeitet
 * 2. Kontakt & Adresse             — selten geändert, aber wichtig
 * 3. Social Media                   — sehr selten geändert
 * 4. Farben                         — einmalig beim Setup
 *
 * @param WP_Customize_Manager $wp_customize
 */
function ffw_customize_register( $wp_customize ) {

	// Panel
	$wp_customize->add_panel(
		'ffw_theme_panel',
		array(
			'title'       => __( 'FFW Theme Optionen', 'ffw-theme' ),
			'description' => __( 'Einstellungen für das Feuerwehr-Theme', 'ffw-theme' ),
			'priority'    => 130,
		)
	);

	// Sections in logischer Reihenfolge registrieren
	ffw_customizer_homepage( $wp_customize );  // Prio 10 + 15
	ffw_customizer_contact( $wp_customize );   // Prio 20
	ffw_customizer_social( $wp_customize );    // Prio 25
	ffw_customizer_colors( $wp_customize );    // Prio 30
}
add_action( 'customize_register', 'ffw_customize_register' );
