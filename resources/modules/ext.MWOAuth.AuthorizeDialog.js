/**
 * OAuth JavaScript
 *
 * @author Aaron Schulz 2013
 */
( function () {
	'use strict';

	var mwoauth = {
		init: function () {
			var $form = $( '#mw-mwoauth-authorize-dialog' );

			OO.ui.getWindowManager().openWindow( 'message', {
				message: $form,
				size: 'medium',
				actions: []
			} ).then( function () {
				// Appending the <form> to a <label> makes the whole form a click target
				// for 'Allow', so move it out of the <label>
				OO.ui.getWindowManager().getCurrentWindow().text.$element.append( $form );
			} );

			$form.find( '.mw-htmlform-submit-buttons' ).addClass( 'mw-ui-flush-right' );
			$form.on( 'submit', function () {
				$( '#mw-mwoauth-accept' ).prop( 'disabled', true );
			} );
		}
	};

	// Perform some onload events:
	$( mwoauth.init );

}() );
