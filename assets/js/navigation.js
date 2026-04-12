/**
 * FFW Theme — Navigation JS
 * Mobile hamburger menu + sticky header
 */
(function () {
	'use strict';

	var header  = document.getElementById( 'masthead' );
	var toggle  = document.querySelector( '.menu-toggle' );
	var navMenu = document.querySelector( '.primary-menu-container' );
	var ACTIVE   = 'is-open';
	var SCROLLED = 'is-scrolled';

	// -------------------------------------------------------------------------
	// Hamburger toggle
	// -------------------------------------------------------------------------
	if ( toggle && navMenu ) {
		toggle.addEventListener( 'click', function () {
			var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', String( !expanded ) );
			toggle.classList.toggle( ACTIVE );
			navMenu.classList.toggle( ACTIVE );
			document.body.classList.toggle( 'menu-open' );
		} );

		// Close on outside click
		document.addEventListener( 'click', function ( e ) {
			if ( header && !header.contains( e.target ) ) {
				toggle.setAttribute( 'aria-expanded', 'false' );
				toggle.classList.remove( ACTIVE );
				navMenu.classList.remove( ACTIVE );
				document.body.classList.remove( 'menu-open' );
			}
		} );

		// Close on Escape key
		document.addEventListener( 'keyup', function ( e ) {
			if ( e.key === 'Escape' && navMenu.classList.contains( ACTIVE ) ) {
				toggle.setAttribute( 'aria-expanded', 'false' );
				toggle.classList.remove( ACTIVE );
				navMenu.classList.remove( ACTIVE );
				document.body.classList.remove( 'menu-open' );
				toggle.focus();
			}
		} );
	}

	// -------------------------------------------------------------------------
	// Sticky header: add class on scroll
	// -------------------------------------------------------------------------
	if ( header ) {
		var ticking = false;

		window.addEventListener(
			'scroll',
			function () {
				if ( !ticking ) {
					window.requestAnimationFrame( function () {
						header.classList.toggle( SCROLLED, window.scrollY > 50 );
						ticking = false;
					} );
					ticking = true;
				}
			},
			{ passive: true }
		);
	}

	// -------------------------------------------------------------------------
	// Sub-menu & Mega-Menu keyboard / hover accessibility
	// -------------------------------------------------------------------------
	var menuItems = document.querySelectorAll( '.primary-nav > li' );

	menuItems.forEach( function ( item ) {
		// Regular dropdown uses a child <ul>; mega menu uses .mega-menu-panel.
		var subMenu   = item.querySelector( 'ul' );
		var megaPanel = item.querySelector( '.mega-menu-panel' );

		if ( !megaPanel && !subMenu ) return;

		var link         = item.querySelector( 'a' );
		var closeTimeout = null;

		if ( link ) {
			link.setAttribute( 'aria-haspopup', 'true' );
			link.setAttribute( 'aria-expanded', 'false' );
		}

		// Show panel immediately.
		function doOpen() {
			if ( megaPanel ) {
				megaPanel.style.display = 'block';
			} else {
				subMenu.style.display = 'block';
			}
			if ( link ) link.setAttribute( 'aria-expanded', 'true' );
		}

		// Hide panel immediately (used by keyboard blur).
		function doClose() {
			if ( megaPanel ) {
				megaPanel.style.display = '';
			} else {
				subMenu.style.display = '';
			}
			if ( link ) link.setAttribute( 'aria-expanded', 'false' );
		}

		// Cancel any pending close and open the panel right away.
		function openPanel() {
			clearTimeout( closeTimeout );
			doOpen();
		}

		// For mega menus: delay closing so the cursor can travel across the
		// visual gap between the <li> boundary and the panel.  The panel's own
		// mouseenter will cancel the timeout if the cursor reaches it in time.
		function closePanel() {
			clearTimeout( closeTimeout );
			if ( megaPanel ) {
				closeTimeout = setTimeout( doClose, 120 );
			} else {
				doClose();
			}
		}

		// Trigger item (the <li>) — covers the link and any inline content.
		item.addEventListener( 'mouseenter', openPanel );
		item.addEventListener( 'mouseleave', closePanel );

		// The mega-menu panel itself is absolutely positioned and therefore
		// outside the <li>'s layout box.  We must listen on the panel too so
		// entering it cancels the close timeout.
		if ( megaPanel ) {
			megaPanel.addEventListener( 'mouseenter', openPanel );
			megaPanel.addEventListener( 'mouseleave', closePanel );
		}

		// Keyboard: close immediately when focus leaves the entire item tree.
		var allLinks = item.querySelectorAll( 'a' );
		var lastLink = allLinks[ allLinks.length - 1 ];

		if ( lastLink ) {
			lastLink.addEventListener( 'blur', function ( e ) {
				if ( !item.contains( e.relatedTarget ) ) {
					clearTimeout( closeTimeout );
					doClose();
				}
			} );
		}
	} );
})();
