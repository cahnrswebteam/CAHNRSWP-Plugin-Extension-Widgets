jQuery(document).ready(function($){

	var resized = function() {

		( $(window).width() > 693 ) ? desktop() : mobile();

	}

	// Desktop functionality.
	var desktop = function() {

		$( '#program-list' ).find( '.program-details' ).remove();

		if ( $( '#program-preview' ).length == 0 ) {
  		$( '.program-preview' ).append( '<article id="program-preview"></article>' );
		}

		// Program preview on hover.
		$( '#program-list a' ).hover( function() {

			var program = $(this);

			$( '#program-preview' ).html(
				'<header class="article-title"><h4><a title="Go to the ' + program.html() + ' website" href="' + program.attr( 'href' ) + '">' + program.html() + ' <span class="dashicons dashicons-external"></span></a></h4></header>' +
				'<div class="article-summary"><p>' + program.data( 'desc' ) + '</p>' +
				'<img src="' + program.data( 'img' ) + '" /></div>'
			);

		});

		// Scroll preview pane.
		$(function() {

			var $preview = $( '#program-preview' ),
					offset   = $preview.offset(),
					topPadding = ( $(window).width() > 989 ) ? $( '.cahnrs-header-group ').outerHeight() : $( '.cahnrs-header-group ').outerHeight() + $( '.spine-header ').outerHeight();

			$(window).scroll( function() {
				( $(window).scrollTop() > offset.top ) ? $preview.css( 'margin-top', $(window).scrollTop() - offset.top + topPadding ) : $preview.css( 'margin-top', 0 );
			});

		});

	}

	// Mobile functionality.
	var mobile = function() {

		$( '#program-preview' ).remove();

		$( '#program-list' ).on( 'click', 'a', function(event) {

			event.preventDefault();

			var program = $(this)
					details = $( '#program-list' ).find( '.program-details' );

			details.remove();

			program.parent('li').append( 
				'<span class="program-details">' +
				'<strong><a title="Go to the ' + program.html() + ' website" href="' + program.attr( 'href' ) + '">Visit site <span class="dashicons dashicons-external"></span></a></strong><br />' +
				program.data( 'desc' ) + '<br />' +
				'<img src="' + program.data( 'img' ) + '" /></span>'
			);

		});

	}

	resized();
	$(window).resize(resized);

});