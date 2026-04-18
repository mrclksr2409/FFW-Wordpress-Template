/**
 * FFW Theme — Image Carousel
 *
 * Initialisiert alle [ffw_carousel]-Instanzen auf der Seite mit Swiper.js.
 * Konfiguration kommt aus dem data-ffw-carousel Attribut am Root-Element.
 */
(function () {
	'use strict';

	if ( typeof window.Swiper !== 'function' ) {
		return;
	}

	var prefersReducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function parseConfig( root ) {
		var raw = root.getAttribute( 'data-ffw-carousel' );
		if ( !raw ) {
			return null;
		}
		try {
			return JSON.parse( raw );
		} catch ( err ) {
			return null;
		}
	}

	function buildBreakpoints( desktopSpv, partial ) {
		var offset = partial ? 0.15 : 0;
		var mobile = 1 + offset;
		var tabletSpv;

		if ( desktopSpv === 'auto' ) {
			return {
				576:  { slidesPerView: 'auto' },
				992:  { slidesPerView: 'auto' }
			};
		}

		tabletSpv = Math.min( 2, desktopSpv );

		return {
			576:  { slidesPerView: tabletSpv + offset },
			992:  { slidesPerView: desktopSpv + offset }
		};
	}

	function initCarousel( root ) {
		var config = parseConfig( root );
		if ( !config ) {
			return;
		}

		var viewport = root.querySelector( '.ffw-carousel__viewport' );
		if ( !viewport ) {
			return;
		}

		var desktopSpv = config.slidesPerView;
		if ( typeof desktopSpv !== 'number' && desktopSpv !== 'auto' ) {
			desktopSpv = parseInt( desktopSpv, 10 ) || 1;
		}

		var partial = !!config.partialView;
		var mobileSpv = ( desktopSpv === 'auto' ) ? 'auto' : 1 + ( partial ? 0.15 : 0 );

		var options = {
			speed:         prefersReducedMotion ? 0 : Math.max( 100, parseInt( config.speed, 10 ) || 600 ),
			loop:          !!config.loop,
			slidesPerView: mobileSpv,
			spaceBetween:  16,
			watchOverflow: true,
			a11y: {
				prevSlideMessage: 'Vorheriges Bild',
				nextSlideMessage: 'Nächstes Bild'
			},
			breakpoints: buildBreakpoints( desktopSpv, partial )
		};

		if ( !config.hideNav ) {
			options.navigation = {
				prevEl: root.querySelector( '.ffw-carousel__button--prev' ),
				nextEl: root.querySelector( '.ffw-carousel__button--next' )
			};
		}

		if ( !config.hidePagination ) {
			options.pagination = {
				el:        root.querySelector( '.ffw-carousel__pagination' ),
				clickable: true
			};
		}

		var autoplaySeconds = parseInt( config.autoplay, 10 ) || 0;
		if ( autoplaySeconds > 0 && !prefersReducedMotion ) {
			options.autoplay = {
				delay:                autoplaySeconds * 1000,
				disableOnInteraction: false,
				pauseOnMouseEnter:    true
			};
		}

		try {
			new window.Swiper( viewport, options );
		} catch ( err ) {
			if ( window.console && window.console.error ) {
				window.console.error( 'ffw-carousel init failed:', err );
			}
		}
	}

	function initAll() {
		document.querySelectorAll( '.ffw-carousel[data-ffw-carousel]' ).forEach( initCarousel );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}
}());
