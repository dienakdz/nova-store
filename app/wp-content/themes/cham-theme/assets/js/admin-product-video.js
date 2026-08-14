( function( $ ) {
	'use strict';

	var frame;

	function setVideo( url ) {
		var $input = $( '#_cham_product_video_url' );
		var $preview = $( '.cham-product-video-preview' );
		var $status = $( '.cham-product-video-status' );

		$input.val( url ).trigger( 'change' );
		$preview.attr( 'src', url );
		$preview.prop( 'hidden', ! url );
		$status.text( url ? $status.data( 'selected' ) : $status.data( 'empty' ) );
	}

	$( document ).on( 'click', '.cham-select-product-video', function( event ) {
		event.preventDefault();

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media( {
			title: chamProductVideo.frameTitle,
			button: {
				text: chamProductVideo.buttonText
			},
			library: {
				type: 'video'
			},
			multiple: false
		} );

		frame.on( 'select', function() {
			var attachment = frame.state().get( 'selection' ).first().toJSON();

			if ( attachment && attachment.url ) {
				setVideo( attachment.url );
			}
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.cham-remove-product-video', function( event ) {
		event.preventDefault();
		setVideo( '' );
	} );
}( jQuery ) );
