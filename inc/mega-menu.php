<?php
/**
 * FFW Theme — Fahrzeuge Mega Menu
 *
 * Provides a custom nav walker that renders any menu item with the CSS class
 * "mega-menu" (set in wp-admin → Menüs → erweiterte Menüoptionen) as a
 * full-width image-card panel instead of a plain dropdown.
 *
 * Usage:
 *   1. Open the WordPress menu editor and enable "CSS-Klassen" in the
 *      "Erweiterte Menüoptionen" panel.
 *   2. Add the class  mega-menu  to the top-level "Fahrzeuge" item.
 *   3. Add the sub-pages as direct children of that item.
 *   4. Make sure each sub-page has a Beitragsbild (featured image) set.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Walker for the primary navigation.
 *
 * Behaviour:
 *  - Top-level items that carry the  mega-menu  CSS class get an extra
 *    has-mega-menu  class and a chevron indicator.
 *  - Their first-level children are rendered as image cards inside a
 *    .mega-menu-panel > .mega-menu-grid  wrapper instead of a plain <ul>.
 *  - Everything else falls through to the standard Walker_Nav_Menu logic.
 */
class FFW_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * The top-level menu item currently being processed.
	 *
	 * @var object|null
	 */
	private $current_top_item = null;

	// -----------------------------------------------------------------------
	// Walker overrides
	// -----------------------------------------------------------------------

	/**
	 * Start a sub-level.  For mega-menu parents we open the panel wrapper
	 * instead of a plain <ul>.
	 *
	 * @param string   $output  Passed by reference.
	 * @param int      $depth   Depth of the menu. 0 = top level.
	 * @param stdClass $args    Walker args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth && $this->is_mega_menu() ) {
			$output .= "\n<div class=\"mega-menu-panel\" role=\"region\" aria-label=\""
				. esc_attr( $this->current_top_item->title )
				. "\">\n<div class=\"mega-menu-grid\">\n";
			return;
		}

		parent::start_lvl( $output, $depth, $args );
	}

	/**
	 * End a sub-level.  Closes the panel wrapper for mega-menu parents.
	 *
	 * @param string   $output  Passed by reference.
	 * @param int      $depth   Depth of the menu.
	 * @param stdClass $args    Walker args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth && $this->is_mega_menu() ) {
			$output .= "</div>\n</div>\n";
			return;
		}

		parent::end_lvl( $output, $depth, $args );
	}

	/**
	 * Render a single menu item element.
	 *
	 * @param string   $output  Passed by reference.
	 * @param object   $item    Data object for this menu item.
	 * @param int      $depth   Depth of the item.
	 * @param stdClass $args    Walker args.
	 * @param int      $id      Current item/element ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Track the current top-level item so child items can check it.
		if ( 0 === $depth ) {
			$this->current_top_item = $item;

			// Inject helper class so CSS can target this item reliably.
			if ( $this->is_mega_menu() ) {
				$item->classes[] = 'has-mega-menu';
			}
		}

		// Children of a mega-menu top item → render as image card.
		if ( 1 === $depth && $this->is_mega_menu() ) {
			$output .= $this->render_vehicle_card( $item );
			return;
		}

		// Default rendering for everything else.
		parent::start_el( $output, $item, $depth, $args, $id );

		// Append chevron SVG to top-level mega-menu link (inject before </a>).
		if ( 0 === $depth && $this->is_mega_menu() ) {
			// The parent already closed the tag — re-open by replacing the last </a>
			// occurrence with the chevron + </a>.
			$chevron = ' <svg class="mega-menu-chevron" xmlns="http://www.w3.org/2000/svg"'
				. ' width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"'
				. ' focusable="false">'
				. '<path d="M1 3l4 4 4-4" stroke="currentColor" stroke-width="1.5"'
				. ' stroke-linecap="round" stroke-linejoin="round" fill="none"/>'
				. '</svg>';

			// Insert chevron right before the closing </a> of the top-level link.
			$pos = strrpos( $output, '</a>' );
			if ( false !== $pos ) {
				$output = substr_replace( $output, $chevron . '</a>', $pos, 4 );
			}
		}
	}

	/**
	 * Close the list element.  Cards are self-contained divs — no extra tag.
	 *
	 * @param string   $output  Passed by reference.
	 * @param object   $item    Data object for this menu item.
	 * @param int      $depth   Depth of the item.
	 * @param stdClass $args    Walker args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 1 === $depth && $this->is_mega_menu() ) {
			// Card div was already closed in render_vehicle_card().
			return;
		}

		parent::end_el( $output, $item, $depth, $args );
	}

	// -----------------------------------------------------------------------
	// Helpers
	// -----------------------------------------------------------------------

	/**
	 * Returns true when the current top-level item has the mega-menu class.
	 *
	 * @return bool
	 */
	private function is_mega_menu(): bool {
		return (
			null !== $this->current_top_item &&
			in_array( 'mega-menu', (array) $this->current_top_item->classes, true )
		);
	}

	/**
	 * Build the HTML for a single vehicle card.
	 *
	 * @param object $item  Menu item data object.
	 * @return string
	 */
	private function render_vehicle_card( object $item ): string {
		$page_id    = absint( $item->object_id );
		$url        = esc_url( $item->url );
		$title      = esc_html( apply_filters( 'the_title', $item->title, $page_id ) );
		$is_current = in_array( 'current-menu-item', (array) $item->classes, true );

		$card_classes = 'mega-menu-card';
		if ( $is_current ) {
			$card_classes .= ' mega-menu-card--active';
		}

		$html  = '<div class="' . $card_classes . '">';
		$html .= '<a href="' . $url . '" class="mega-menu-card__link">';

		// ---- Featured image -------------------------------------------------
		if ( has_post_thumbnail( $page_id ) ) {
			$html .= get_the_post_thumbnail(
				$page_id,
				'ffw-vehicle',
				array(
					'class'   => 'mega-menu-card__image',
					'loading' => 'eager',
					'alt'     => $title,
				)
			);
		} else {
			// Placeholder with a simple vehicle silhouette icon.
			$html .= '<div class="mega-menu-card__image mega-menu-card__image--placeholder" aria-hidden="true">';
			$html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 48" fill="none"'
				. ' stroke="currentColor" stroke-width="2" stroke-linecap="round"'
				. ' stroke-linejoin="round" class="mega-menu-card__placeholder-icon">'
				. '<rect x="3" y="14" width="74" height="22" rx="3"/>'
				. '<path d="M3 24h74M18 14V8h44v6"/>'
				. '<path d="M8 36v4M72 36v4"/>'
				. '<circle cx="18" cy="38" r="5"/>'
				. '<circle cx="62" cy="38" r="5"/>'
				. '</svg>';
			$html .= '</div>';
		}

		// ---- Title bar ------------------------------------------------------
		$html .= '<span class="mega-menu-card__title">' . $title . '</span>';

		$html .= '</a>';
		$html .= '</div>';

		return $html;
	}
}
