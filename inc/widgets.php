<?php
/**
 * FFW Theme — Widget Area Registration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ffw_register_widget_areas() {
	$areas = array(
		array(
			'name'          => __( 'Seitenleiste', 'ffw-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Haupt-Seitenleiste für Seiten und Beiträge', 'ffw-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
		array(
			'name'          => __( 'Footer Spalte 1 — Kontakt', 'ffw-theme' ),
			'id'            => 'footer-1',
			'description'   => __( 'Linke Footer-Spalte (Kontakt / Adresse)', 'ffw-theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		),
		array(
			'name'          => __( 'Footer Spalte 2 — Schnelllinks', 'ffw-theme' ),
			'id'            => 'footer-2',
			'description'   => __( 'Mittlere Footer-Spalte (Navigation / Links)', 'ffw-theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		),
		array(
			'name'          => __( 'Footer Spalte 3 — Social', 'ffw-theme' ),
			'id'            => 'footer-3',
			'description'   => __( 'Rechte Footer-Spalte (Social Media / Info)', 'ffw-theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		),
	);

	foreach ( $areas as $area ) {
		register_sidebar( $area );
	}
}
add_action( 'widgets_init', 'ffw_register_widget_areas' );
