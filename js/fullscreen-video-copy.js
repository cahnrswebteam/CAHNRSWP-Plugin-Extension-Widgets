var tag = document.createElement( 'script' ),
		first_script_tag = document.getElementsByTagName( 'script' )[0],
		loop;

tag.src = 'http://www.youtube.com/iframe_api';
first_script_tag.parentNode.insertBefore( tag, first_script_tag );

function onYouTubeIframeAPIReady() {
	var loop_id = jQuery( '#full-video' ).data( 'video-id' );
	var player_vars = {
		'controls': 0,
		'enablejsapi': 1,
		'loop': 1,
		'modestbranding': 1,
		'playlist': loop_id,
		'rel': 0,
		'showinfo': 0
	}
	if ( document.createElement( 'video' ).canPlayType ) jQuery.extend( player_vars, {
		'html5': 1
	} );
	loop = new YT.Player( 'full-video', {
		height: '720',
		width: '1280',
		videoId: loop_id,
		playerVars: player_vars,
		events: {
			'onReady': onPlayerReady
		}
	});
}

function onPlayerReady() {
	loop.playVideo();
	loop.mute();
	jQuery(loop.f).removeAttr( 'height' ).removeAttr( 'width' ).delay(1000).fadeTo( 'slow', 1 );
	resize_video();
}

window.onresize = resize_video;

function resize_video() {
	var loop_container = jQuery( '#full-video' ),
			window_height = jQuery(window).height(),
			window_width = jQuery(window).width(),
			new_height = window_width * loop_container.data( 'aspect' );
	if ( new_height < window_height ) {
		loop_container.width( window_height / loop_container.data( 'aspect' ) ).height( window_height );
	} else {
		loop_container.width( window_width ).height( new_height );
	}
}