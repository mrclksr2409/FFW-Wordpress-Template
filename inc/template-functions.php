<?php
/**
 * FFW Theme — Template Helper Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output post date with machine-readable time element.
 */
function ffw_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);

	printf(
		'<span class="posted-on"><a href="%1$s" rel="bookmark">%2$s</a></span>',
		esc_url( get_permalink() ),
		$time_string
	);
}

/**
 * Output post author.
 */
function ffw_posted_by() {
	printf(
		'<span class="byline"> %s <span class="author vcard"><a class="url fn n" href="%s">%s</a></span></span>',
		esc_html__( 'von', 'ffw-theme' ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
}

/**
 * Output post categories.
 */
function ffw_entry_categories() {
	$categories = get_the_category_list( esc_html__( ', ', 'ffw-theme' ) );
	if ( $categories ) {
		printf(
			'<span class="cat-links">%s</span>',
			$categories
		);
	}
}

/**
 * Output post tags and edit link in post footer.
 */
function ffw_entry_footer() {
	// Tags
	$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'ffw-theme' ) );
	if ( $tags_list ) {
		printf(
			'<span class="tags-links">%1$s %2$s</span>',
			esc_html__( 'Schlagwörter:', 'ffw-theme' ),
			$tags_list
		);
	}

	// Edit link
	edit_post_link(
		sprintf(
			wp_kses(
				__( 'Beitrag bearbeiten <span class="screen-reader-text">„%s"</span>', 'ffw-theme' ),
				array( 'span' => array( 'class' => array() ) )
			),
			wp_kses_post( get_the_title() )
		),
		'<span class="edit-link">',
		'</span>'
	);
}

/**
 * Fallback menu callback when no menu is assigned.
 */
function ffw_fallback_menu() {
	echo '<ul class="primary-nav">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Startseite', 'ffw-theme' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Modify WordPress excerpt length.
 *
 * @param int $length Default excerpt length.
 * @return int
 */
function ffw_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'ffw_excerpt_length', 999 );

/**
 * Modify WordPress excerpt "more" string.
 *
 * @param string $more Default more string.
 * @return string
 */
function ffw_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'ffw_excerpt_more' );

/**
 * Add body class based on context.
 *
 * @param array $classes Array of body class names.
 * @return array
 */
function ffw_body_classes( $classes ) {
	if ( is_singular() && ! is_home() ) {
		$classes[] = 'singular';
	}

	if ( is_singular( 'einsatz' ) ) {
		$classes[] = 'is-einsatz';
	}

	if ( function_exists( 'is_tribe_event' ) && is_tribe_event() ) {
		$classes[] = 'is-tribe-event';
	}

	return $classes;
}
add_filter( 'body_class', 'ffw_body_classes' );

/**
 * Wrap oEmbed output in a responsive container.
 *
 * @param string $html Default oEmbed HTML.
 * @return string
 */
function ffw_wrap_oembed( $html ) {
	return '<div class="embed-responsive">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', 'ffw_wrap_oembed', 10 );

/**
 * Modify the posts navigation markup template to use theme classes.
 *
 * @param string $template Default navigation markup template.
 * @return string
 */
function ffw_navigation_markup_template( $template ) {
	return '<nav class="navigation %1$s" role="navigation" aria-label="%2$s">%3$s</nav>';
}
add_filter( 'navigation_markup_template', 'ffw_navigation_markup_template' );

/**
 * Remove the Einsatzverwaltung plugin's auto-appended report details block
 * on single einsatz pages, since the theme provides its own styled meta box.
 *
 * Priority 20 ensures this runs after the plugin has registered its filters.
 * The check uses the class namespace so it works regardless of exact method name.
 */
add_action( 'template_redirect', function() {
	if ( ! is_singular( 'einsatz' ) ) {
		return;
	}
	global $wp_filter;
	if ( ! isset( $wp_filter['the_content'] ) ) {
		return;
	}
	foreach ( $wp_filter['the_content']->callbacks as $priority => $callbacks ) {
		foreach ( $callbacks as $callback ) {
			$fn = $callback['function'];
			if ( ! is_array( $fn ) || ! is_object( $fn[0] ) ) {
				continue;
			}
			$class = get_class( $fn[0] );
			if ( false !== stripos( $class, 'einsatzverwaltung' )
				|| method_exists( $fn[0], 'filterSingleEinsatz' ) ) {
				remove_filter( 'the_content', $fn, $priority );
			}
		}
	}
}, 20 );
