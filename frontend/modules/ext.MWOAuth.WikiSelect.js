/**
 * OAuth JavaScript
 * @author Chris Steipp 2013
 */
( function( mw, $ ) {
	$( document ).ready( function () {
		if(window.mw){
			$( '#mw-input-wpwiki-other' ).autocomplete({
				source: mw.config.get( 'wgOAuthWikiList' )
			});
		}
	} );
})( mediaWiki, jQuery );
