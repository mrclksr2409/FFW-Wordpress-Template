<?php
/**
 * FFW Theme — Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode: Beitragsliste
 *
 * Verwendung:
 *   [ffw_posts]
 *   [ffw_posts limit="3"]
 *   [ffw_posts limit="6" category="news"]
 *   [ffw_posts limit="9" category="news,presse"]
 *   [ffw_posts limit="6" more_text="Alle Beiträge"]
 *   [ffw_posts limit="6" more_text="Mehr lesen" more_url="/neuigkeiten/"]
 *
 * Parameter:
 *   limit     — Anzahl der Beiträge (Standard: 6)
 *   category  — Kategorie-Slug oder kommaseparierte Liste von Slugs (Standard: alle)
 *   more_text — Text des „Alle Beiträge"-Buttons (leer = kein Button)
 *   more_url  — URL des Buttons (Standard: konfigurierte Beitragsseite)
 */
function ffw_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit'     => 6,
			'category'  => '',
			'more_text' => '',
			'more_url'  => '',
		),
		$atts,
		'ffw_posts'
	);

	$query_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => max( 1, (int) $atts['limit'] ),
	);

	if ( ! empty( $atts['category'] ) ) {
		// Kommaseparierte Slugs → category__in via Term-IDs
		$slugs = array_map( 'trim', explode( ',', sanitize_text_field( $atts['category'] ) ) );
		$term_ids = array();
		foreach ( $slugs as $slug ) {
			$term = get_term_by( 'slug', $slug, 'category' );
			if ( $term && ! is_wp_error( $term ) ) {
				$term_ids[] = $term->term_id;
			}
		}
		if ( ! empty( $term_ids ) ) {
			$query_args['category__in'] = $term_ids;
		}
	}

	$posts_query = new WP_Query( $query_args );

	if ( ! $posts_query->have_posts() ) {
		return '';
	}

	// Resolve the "more" button URL
	$more_text = sanitize_text_field( $atts['more_text'] );
	$more_url  = '';
	if ( $more_text ) {
		if ( ! empty( $atts['more_url'] ) ) {
			$more_url = esc_url( $atts['more_url'] );
		} else {
			$posts_page_id = (int) get_option( 'page_for_posts' );
			if ( $posts_page_id ) {
				$more_url = esc_url( get_permalink( $posts_page_id ) );
			}
		}
	}

	ob_start();
	echo '<div class="posts-grid posts-grid--3">';
	while ( $posts_query->have_posts() ) :
		$posts_query->the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	endwhile;
	wp_reset_postdata();
	echo '</div>';

	if ( $more_text && $more_url ) {
		printf(
			'<div class="section-cta"><a href="%s" class="btn btn--outline">%s</a></div>',
			$more_url,
			esc_html( $more_text )
		);
	}

	return ob_get_clean();
}
add_shortcode( 'ffw_posts', 'ffw_posts_shortcode' );
