/**
 * FFW Theme — Customizer Live Preview
 *
 * Aktualisiert CSS Custom Properties sofort im Customizer-Preview-Iframe,
 * ohne dass die Seite neu geladen werden muss (transport: 'postMessage').
 *
 * Die Farbzuordnung (Setting-ID → CSS-Variable) wird vom PHP-Backend
 * via wp_localize_script als window.ffwColorMap übergeben.
 */
( function () {
	'use strict';

	if ( typeof wp === 'undefined' || typeof wp.customize === 'undefined' ) {
		return;
	}

	var colorMap = window.ffwColorMap || {};

	/**
	 * Berechnet rgba() für die Alpha-Variable der Primärfarbe.
	 *
	 * @param {string} hex  Hex-Farbe, z. B. "#E30613"
	 * @param {number} alpha
	 * @returns {string}
	 */
	function hexToRgba( hex, alpha ) {
		hex = hex.replace( /^#/, '' );
		if ( hex.length === 3 ) {
			hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
		}
		var r = parseInt( hex.substring( 0, 2 ), 16 );
		var g = parseInt( hex.substring( 2, 4 ), 16 );
		var b = parseInt( hex.substring( 4, 6 ), 16 );
		return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
	}

	// Jede Farbe einzeln überwachen
	Object.keys( colorMap ).forEach( function ( settingId ) {
		var cssVar = colorMap[ settingId ];

		wp.customize( settingId, function ( value ) {
			value.bind( function ( newColor ) {
				if ( ! newColor ) return;

				// CSS Custom Property sofort setzen
				document.documentElement.style.setProperty( cssVar, newColor );

				// Abgeleitete Alpha-Farbe bei Primärfarbe automatisch mitaktualisieren
				if ( settingId === 'ffw_color_primary' ) {
					document.documentElement.style.setProperty(
						'--ffw-color-primary-alpha',
						hexToRgba( newColor, 0.12 )
					);
				}
			} );
		} );
	} );

} )();
