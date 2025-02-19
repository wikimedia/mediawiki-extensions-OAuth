/**
 * OAuth JavaScript
 *
 * @author Aaron Schulz 2013
 */
( function () {
	'use strict';

	const mwoauth = {
		init: function () {
			const $form = $( '#mw-mwoauth-authorize-form' );

			OO.ui.getWindowManager().openWindow( 'message', {
				message: $form,
				size: 'medium',
				actions: []
			} ).opened.then( () => {
				// Appending the <form> to a <label> makes the whole form a click target
				// for 'Allow', so move it out of the <label>
				OO.ui.getWindowManager().getCurrentWindow().text.$element.append( $form );
			} );

			$form.on( 'submit', () => {
				$( '#mw-mwoauth-accept' ).prop( 'disabled', true );
			} );
		}
	};

	// Perform some onload events:
	$( mwoauth.init );

}() );
