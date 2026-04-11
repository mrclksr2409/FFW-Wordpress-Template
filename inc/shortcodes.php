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
 *
 * Parameter:
 *   limit    — Anzahl der Beiträge (Standard: 6)
 *   category — Kategorie-Slug oder kommaseparierte Liste von Slugs (Standard: alle)
 */
function ffw_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit'    => 6,
			'category' => '',
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

	ob_start();
	echo '<div class="posts-grid posts-grid--3">';
	while ( $posts_query->have_posts() ) :
		$posts_query->the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	endwhile;
	wp_reset_postdata();
	echo '</div>';

	return ob_get_clean();
}
add_shortcode( 'ffw_posts', 'ffw_posts_shortcode' );
