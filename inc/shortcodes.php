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
			$more_url = $atts['more_url'];
		} else {
			$posts_page_id = (int) get_option( 'page_for_posts' );
			if ( $posts_page_id ) {
				$more_url = get_permalink( $posts_page_id );
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
			esc_url( $more_url ),
			esc_html( $more_text )
		);
	}

	return ob_get_clean();
}
add_shortcode( 'ffw_posts', 'ffw_posts_shortcode' );

/**
 * Shortcode: Child-Pages-Karten
 *
 * Listet alle direkten Unterseiten der aktuellen (oder angegebenen) Seite
 * als Karten auf – identisches Layout wie die Fahrzeug-Boxen im Mega-Menü.
 *
 * Verwendung:
 *   [ffw_child_pages]
 *   [ffw_child_pages parent="42"]
 *   [ffw_child_pages exclude="42,77"]
 *   [ffw_child_pages more_text="Alle Fahrzeuge" more_url="/fahrzeuge/"]
 *
 * Parameter:
 *   parent    — ID der Elternseite (0 = aktuelle Seite)
 *   exclude   — Kommaseparierte IDs, die nicht angezeigt werden sollen
 *   more_text — Text des optionalen Buttons unter dem Grid (leer = kein Button)
 *   more_url  — URL des Buttons
 */
function ffw_child_pages_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'parent'    => 0,
			'exclude'   => '',
			'more_text' => '',
			'more_url'  => '',
		),
		$atts,
		'ffw_child_pages'
	);

	$parent_id = (int) $atts['parent'];
	if ( ! $parent_id ) {
		$parent_id = (int) get_the_ID();
	}

	if ( ! $parent_id ) {
		return '';
	}

	$exclude_ids = array();
	if ( ! empty( $atts['exclude'] ) ) {
		$exclude_ids = array_filter(
			array_map( 'absint', explode( ',', (string) $atts['exclude'] ) )
		);
	}

	$query_args = array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'post_parent'    => $parent_id,
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	);

	if ( ! empty( $exclude_ids ) ) {
		$query_args['post__not_in'] = $exclude_ids;
	}

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		return '';
	}

	$more_text = sanitize_text_field( $atts['more_text'] );
	$more_url  = '';
	if ( $more_text ) {
		if ( ! empty( $atts['more_url'] ) ) {
			$more_url = $atts['more_url'];
		} else {
			// Fallback: parent page permalink — consistent with ffw_posts_shortcode.
			$more_url = get_permalink( $parent_id );
		}
	}

	ob_start();
	echo '<div class="child-pages-grid">';

	while ( $query->have_posts() ) :
		$query->the_post();

		$page_id      = get_the_ID();
		$url          = get_permalink();
		$title        = get_the_title();
		$is_current   = is_page( $page_id );
		$card_classes = 'child-page-card' . ( $is_current ? ' child-page-card--active' : '' );

		echo '<div class="' . esc_attr( $card_classes ) . '">';
		echo '<a href="' . esc_url( $url ) . '" class="child-page-card__link">';

		if ( has_post_thumbnail( $page_id ) ) {
			echo get_the_post_thumbnail(
				$page_id,
				'ffw-vehicle',
				array(
					'class'   => 'child-page-card__image',
					'loading' => 'lazy',
					'alt'     => $title,
				)
			);
		} else {
			echo '<div class="child-page-card__image child-page-card__image--placeholder" aria-hidden="true">';
			echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 48" fill="none"'
				. ' stroke="currentColor" stroke-width="2" stroke-linecap="round"'
				. ' stroke-linejoin="round" class="child-page-card__placeholder-icon">'
				. '<rect x="3" y="14" width="74" height="22" rx="3"/>'
				. '<path d="M3 24h74M18 14V8h44v6"/>'
				. '<path d="M8 36v4M72 36v4"/>'
				. '<circle cx="18" cy="38" r="5"/>'
				. '<circle cx="62" cy="38" r="5"/>'
				. '</svg>';
			echo '</div>';
		}

		echo '<span class="child-page-card__title">' . esc_html( $title ) . '</span>';
		echo '</a>';
		echo '</div>';

	endwhile;
	wp_reset_postdata();

	echo '</div>';

	if ( $more_text && $more_url ) {
		printf(
			'<div class="section-cta"><a href="%s" class="btn btn--outline">%s</a></div>',
			esc_url( $more_url ),
			esc_html( $more_text )
		);
	}

	return ob_get_clean();
}
add_shortcode( 'ffw_child_pages', 'ffw_child_pages_shortcode' );

/**
 * Interner Sammler für Zusatzfelder innerhalb von [ffw_fahrzeug_info].
 *
 * Wird von [ffw_fi] befüllt und von der äußeren Shortcode-Funktion ausgelesen.
 * Die Funktion ist ein statischer Container, damit wir nicht auf globale
 * Variablen zurückgreifen müssen.
 *
 * @param string $action 'reset' | 'add' | 'get'
 * @param array  $item   Bei 'add': [ 'label' => string, 'value' => string ]
 * @return array
 */
function ffw_fahrzeug_info_extras( $action = 'get', $item = array() ) {
	static $extras = array();

	if ( 'reset' === $action ) {
		$extras = array();
		return $extras;
	}

	if ( 'add' === $action && ! empty( $item ) ) {
		$extras[] = $item;
		return $extras;
	}

	return $extras;
}

/**
 * Shortcode: Fahrzeuginformationen
 *
 * Zeigt eine Info-Box im Stil der Einsatz-Meta-Box mit Fahrzeugdaten.
 * Alle Attribute sind optional — leere Felder werden nicht gerendert.
 * Zusätzliche, frei benennbare Felder lassen sich über verschachtelte
 * [ffw_fi]-Tags im Inhalt ergänzen.
 *
 * Verwendung:
 *   [ffw_fahrzeug_info
 *       title="Löschgruppenfahrzeug LF 10"
 *       rufname="Florian 1-42-1"
 *       baujahr="2020"
 *       indienststellung="01.03.2020"
 *       fahrgestell="MAN TGM 13.290"
 *       motorleistung="213 kW / 290 PS"
 *       gesamtgewicht="14.500 kg"
 *       aufbau="Schlingmann"
 *       besatzung="1/8"]
 *       [ffw_fi label="Funkkennung"]Florian 1-42-1[/ffw_fi]
 *       [ffw_fi label="Tankinhalt"]1.000 l Wasser / 120 l Schaum[/ffw_fi]
 *   [/ffw_fahrzeug_info]
 *
 * Attribute:
 *   title            — optionale Überschrift über der Liste
 *   rufname          — Funk-/Rufname des Fahrzeugs
 *   baujahr          — Baujahr
 *   indienststellung — Datum der Indienststellung
 *   fahrgestell      — Fahrgestellhersteller/-typ
 *   motorleistung    — Motorleistung (kW / PS)
 *   gesamtgewicht    — Zulässiges Gesamtgewicht
 *   aufbau           — Aufbauhersteller
 *   besatzung        — Besatzungsstärke (z. B. 1/8)
 */
function ffw_fahrzeug_info_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'title'            => '',
			'rufname'          => '',
			'baujahr'          => '',
			'indienststellung' => '',
			'fahrgestell'      => '',
			'motorleistung'    => '',
			'gesamtgewicht'    => '',
			'aufbau'           => '',
			'besatzung'        => '',
		),
		$atts,
		'ffw_fahrzeug_info'
	);

	// Sammler für [ffw_fi]-Extras zurücksetzen und Inhalt parsen
	ffw_fahrzeug_info_extras( 'reset' );
	if ( ! empty( $content ) ) {
		do_shortcode( $content );
	}
	$extras = ffw_fahrzeug_info_extras( 'get' );

	// SVG-Icons (14×14, currentColor) — konsistent zu .einsatz-meta__icon
	$icons = array(
		'rufname'          => '<svg class="fahrzeug-info__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>',
		'baujahr'          => '<svg class="fahrzeug-info__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
		'indienststellung' => '<svg class="fahrzeug-info__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>',
		'fahrgestell'      => '<svg class="fahrzeug-info__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
		'motorleistung'    => '<svg class="fahrzeug-info__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
		'gesamtgewicht'    => '<svg class="fahrzeug-info__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 6.5 17.5 17.5"/><path d="M3 12l9-9 9 9"/><path d="M12 3v18"/><path d="M3 21h18"/></svg>',
		'aufbau'           => '<svg class="fahrzeug-info__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
		'besatzung'        => '<svg class="fahrzeug-info__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
	);

	$labels = array(
		'rufname'          => __( 'Rufname', 'ffw-theme' ),
		'baujahr'          => __( 'Baujahr', 'ffw-theme' ),
		'indienststellung' => __( 'Indienststellung', 'ffw-theme' ),
		'fahrgestell'      => __( 'Fahrgestell', 'ffw-theme' ),
		'motorleistung'    => __( 'Motorleistung', 'ffw-theme' ),
		'gesamtgewicht'    => __( 'Zulässiges Gesamtgewicht', 'ffw-theme' ),
		'aufbau'           => __( 'Aufbau', 'ffw-theme' ),
		'besatzung'        => __( 'Besatzung', 'ffw-theme' ),
	);

	ob_start();
	echo '<div class="fahrzeug-info-wrapper">';

	if ( '' !== trim( (string) $atts['title'] ) ) {
		echo '<h3 class="fahrzeug-info__title">' . esc_html( $atts['title'] ) . '</h3>';
	}

	echo '<dl class="fahrzeug-info">';

	foreach ( $labels as $key => $label ) {
		$value = trim( (string) $atts[ $key ] );
		if ( '' === $value ) {
			continue;
		}
		echo '<div class="fahrzeug-info__item">';
		echo '<dt>' . $icons[ $key ] . esc_html( $label ) . '</dt>';
		echo '<dd>' . esc_html( $value ) . '</dd>';
		echo '</div>';
	}

	foreach ( $extras as $extra ) {
		$label = trim( (string) ( $extra['label'] ?? '' ) );
		$value = trim( (string) ( $extra['value'] ?? '' ) );
		if ( '' === $value ) {
			continue;
		}
		echo '<div class="fahrzeug-info__item fahrzeug-info__item--extra">';
		echo '<dt>' . esc_html( $label ) . '</dt>';
		echo '<dd>' . esc_html( $value ) . '</dd>';
		echo '</div>';
	}

	echo '</dl>';
	echo '</div>';

	// Sammler aufräumen, damit mehrere Instanzen auf derselben Seite funktionieren
	ffw_fahrzeug_info_extras( 'reset' );

	return ob_get_clean();
}
add_shortcode( 'ffw_fahrzeug_info', 'ffw_fahrzeug_info_shortcode' );

/**
 * Shortcode: Einzelnes Zusatzfeld innerhalb von [ffw_fahrzeug_info].
 *
 * Schreibt das Label/Wert-Paar in den Sammler und gibt selbst nichts aus —
 * das Rendering übernimmt der äußere Shortcode.
 *
 * Verwendung:
 *   [ffw_fi label="Funkkennung"]Florian 1-42-1[/ffw_fi]
 */
function ffw_fi_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'label' => '',
		),
		$atts,
		'ffw_fi'
	);

	ffw_fahrzeug_info_extras(
		'add',
		array(
			'label' => sanitize_text_field( $atts['label'] ),
			'value' => wp_strip_all_tags( (string) $content ),
		)
	);

	return '';
}
add_shortcode( 'ffw_fi', 'ffw_fi_shortcode' );

/**
 * Shortcode: Bilder-Karussell
 *
 * Zeigt Bilder aus der Mediathek als Slider. Nutzt die Swiper-Library
 * (lokal gebündelt) und lädt JS/CSS nur dann, wenn der Shortcode auf der
 * Seite tatsächlich gerendert wird.
 *
 * Verwendung:
 *   [ffw_carousel ids="12,34,56,78"]
 *   [ffw_carousel ids="12,34,56,78" slides_per_view="3" autoplay="5" loop="true"]
 *   [ffw_carousel ids="12,34,56" partial_view="true" hide_pagination="true"]
 *
 * Attribute:
 *   ids              — kommaseparierte Liste von Attachment-IDs (Pflicht)
 *   image_size       — registrierte WP-Bildgröße (Standard: ffw-card)
 *   slides_per_view  — sichtbare Slides auf Desktop: 1–6 oder "auto" (Standard: 1)
 *   speed            — Übergangsgeschwindigkeit in ms (Standard: 600)
 *   autoplay         — Auto-Advance in Sekunden, 0 = aus (Standard: 0)
 *   hide_pagination  — Pagination-Dots ausblenden (Standard: false)
 *   hide_nav         — Prev/Next-Buttons ausblenden (Standard: false)
 *   partial_view     — Nachbar-Slides angeschnitten zeigen (Standard: false)
 *   loop             — Endlosschleife (Standard: false)
 */
function ffw_carousel_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'ids'             => '',
			'image_size'      => 'ffw-card',
			'slides_per_view' => '1',
			'speed'           => 600,
			'autoplay'        => 0,
			'hide_pagination' => 'false',
			'hide_nav'        => 'false',
			'partial_view'    => 'false',
			'loop'            => 'false',
		),
		$atts,
		'ffw_carousel'
	);

	// Attachment-IDs parsen und validieren.
	$ids = array_values( array_filter( array_map( 'absint', explode( ',', (string) $atts['ids'] ) ) ) );
	if ( empty( $ids ) ) {
		return '';
	}

	// Nur Einträge behalten, die wirklich Attachments sind.
	$ids = array_values( array_filter( $ids, static function ( $id ) {
		return 'attachment' === get_post_type( $id );
	} ) );
	if ( empty( $ids ) ) {
		return '';
	}

	$image_size      = sanitize_key( $atts['image_size'] );
	$slides_raw      = strtolower( sanitize_text_field( (string) $atts['slides_per_view'] ) );
	$slides_per_view = ( 'auto' === $slides_raw ) ? 'auto' : max( 1, min( 6, (int) $slides_raw ) );
	$speed           = max( 100, (int) $atts['speed'] );
	$autoplay        = max( 0, (int) $atts['autoplay'] );
	$hide_pagination = filter_var( $atts['hide_pagination'], FILTER_VALIDATE_BOOLEAN );
	$hide_nav        = filter_var( $atts['hide_nav'], FILTER_VALIDATE_BOOLEAN );
	$partial_view    = filter_var( $atts['partial_view'], FILTER_VALIDATE_BOOLEAN );
	$loop            = filter_var( $atts['loop'], FILTER_VALIDATE_BOOLEAN );

	// Loop nur sinnvoll, wenn genügend Slides vorhanden sind.
	$numeric_spv = is_numeric( $slides_per_view ) ? (int) $slides_per_view : 1;
	if ( $loop && count( $ids ) <= $numeric_spv ) {
		$loop = false;
	}

	// Assets für diese Instanz laden (register-then-enqueue-on-demand).
	wp_enqueue_style( 'ffw-swiper' );
	wp_enqueue_script( 'ffw-swiper' );
	wp_enqueue_script( 'ffw-carousel' );

	$instance_id = wp_unique_id( 'ffw-carousel-' );

	$config = array(
		'speed'          => $speed,
		'loop'           => $loop,
		'slidesPerView'  => $slides_per_view,
		'partialView'    => $partial_view,
		'autoplay'       => $autoplay,
		'hidePagination' => $hide_pagination,
		'hideNav'        => $hide_nav,
	);

	$classes = array( 'ffw-carousel' );
	if ( $partial_view ) {
		$classes[] = 'ffw-carousel--partial';
	}
	if ( $hide_pagination ) {
		$classes[] = 'ffw-carousel--no-pagination';
	}
	if ( $hide_nav ) {
		$classes[] = 'ffw-carousel--no-nav';
	}

	ob_start();
	?>
	<div
		id="<?php echo esc_attr( $instance_id ); ?>"
		class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		data-ffw-carousel="<?php echo esc_attr( wp_json_encode( $config ) ); ?>"
	>
		<div class="swiper ffw-carousel__viewport">
			<div class="swiper-wrapper">
				<?php foreach ( $ids as $id ) : ?>
					<div class="swiper-slide ffw-carousel__slide">
						<?php
						echo wp_get_attachment_image(
							$id,
							$image_size,
							false,
							array(
								'class'   => 'ffw-carousel__image',
								'loading' => 'lazy',
							)
						);
						?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( ! $hide_pagination ) : ?>
				<div class="ffw-carousel__pagination swiper-pagination" aria-hidden="true"></div>
			<?php endif; ?>
		</div>
		<?php if ( ! $hide_nav ) : ?>
			<button
				type="button"
				class="ffw-carousel__button ffw-carousel__button--prev"
				aria-label="<?php esc_attr_e( 'Vorheriges Bild', 'ffw-theme' ); ?>"
			>
				<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
			</button>
			<button
				type="button"
				class="ffw-carousel__button ffw-carousel__button--next"
				aria-label="<?php esc_attr_e( 'Nächstes Bild', 'ffw-theme' ); ?>"
			>
				<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
			</button>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ffw_carousel', 'ffw_carousel_shortcode' );
