/**
 * FFW Theme — Main JavaScript
 * General-purpose enhancements.
 */
(function () {
	'use strict';

	// Wrap responsive embeds (YouTube, etc.)
	document.querySelectorAll( '.entry-content iframe[src*="youtube"], .entry-content iframe[src*="vimeo"]' ).forEach( function ( iframe ) {
		if ( !iframe.parentElement.classList.contains( 'embed-responsive' ) ) {
			var wrapper = document.createElement( 'div' );
			wrapper.className = 'embed-responsive';
			iframe.parentNode.insertBefore( wrapper, iframe );
			wrapper.appendChild( iframe );
		}
	} );

	// Smooth scroll to anchor links
	document.querySelectorAll( 'a[href^="#"]:not([href="#"])' ).forEach( function ( link ) {
		link.addEventListener( 'click', function ( e ) {
			var target = document.querySelector( this.getAttribute( 'href' ) );
			if ( target ) {
				e.preventDefault();
				target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				// Update URL without page jump
				history.pushState( null, null, this.getAttribute( 'href' ) );
			}
		} );
	} );

	// Add focus-visible class for keyboard users (progressively enhanced)
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Tab' ) {
			document.body.classList.add( 'using-keyboard' );
		}
	} );
	document.addEventListener( 'mousedown', function () {
		document.body.classList.remove( 'using-keyboard' );
	} );

})();
