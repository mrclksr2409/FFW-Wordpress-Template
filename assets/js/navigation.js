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
		// Regular dropdown: child <ul>
		// Mega menu: child .mega-menu-panel
		var subMenu   = item.querySelector( 'ul' );
		var megaPanel = item.querySelector( '.mega-menu-panel' );
		var panel     = megaPanel || subMenu;

		if ( !panel ) return;

		var link = item.querySelector( 'a' );
		if ( link ) {
			link.setAttribute( 'aria-haspopup', 'true' );
			link.setAttribute( 'aria-expanded', 'false' );
		}

		function openPanel() {
			if ( megaPanel ) {
				megaPanel.style.display = 'block';
			} else {
				subMenu.style.display = 'block';
			}
			if ( link ) link.setAttribute( 'aria-expanded', 'true' );
		}

		function closePanel() {
			if ( megaPanel ) {
				megaPanel.style.display = '';
			} else {
				subMenu.style.display = '';
			}
			if ( link ) link.setAttribute( 'aria-expanded', 'false' );
		}

		item.addEventListener( 'mouseenter', openPanel );
		item.addEventListener( 'mouseleave', closePanel );

		// Keyboard focus management: close when focus leaves the item entirely
		var allLinks = item.querySelectorAll( 'a' );
		var lastLink = allLinks[ allLinks.length - 1 ];

		if ( lastLink ) {
			lastLink.addEventListener( 'blur', function ( e ) {
				if ( !item.contains( e.relatedTarget ) ) {
					closePanel();
				}
			} );
		}
	} );
})();
