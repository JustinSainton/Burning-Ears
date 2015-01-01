(function( $, window , Backbone, _ ) {

	var document = window.document;

	var burningEars = function() {

		var SELF = this;

		SELF.loadModal = function( e ) {
			e.preventDefault();
		};

		$( document ).ready( function() {
			$( '#misc-publishing-actions' ).on( 'click', '#notify-tweeters', SELF.loadModal );
		});

	};

	window.burningEars = new burningEars();

})( jQuery, window, Backbone, _ );