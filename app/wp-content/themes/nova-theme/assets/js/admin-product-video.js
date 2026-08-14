( function( $ ) {
	'use strict';

	var frame;

	function setVideo( url ) {
		var $input = $( '#_nova_product_video_url' );
		var $preview = $( '.nova-product-video-preview' );
		var $status = $( '.nova-product-video-status' );

		$input.val( url ).trigger( 'change' );
		$preview.attr( 'src', url );
		$preview.prop( 'hidden', ! url );
		$status.text( url ? $status.data( 'selected' ) : $status.data( 'empty' ) );
	}

	$( document ).on( 'click', '.nova-select-product-video', function( event ) {
		event.preventDefault();

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media( {
			title: novaProductVideo.frameTitle,
			button: {
				text: novaProductVideo.buttonText
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

	$( document ).on( 'click', '.nova-remove-product-video', function( event ) {
		event.preventDefault();
		setVideo( '' );
	} );
}( jQuery ) );
