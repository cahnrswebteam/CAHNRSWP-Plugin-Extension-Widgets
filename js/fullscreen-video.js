var tag = document.createElement( 'script' ),
		firstScriptTag = document.getElementsByTagName( 'script' )[0],
		loop;

tag.src = 'https://www.youtube.com/iframe_api';
firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );

function onYouTubeIframeAPIReady() {
	loop = new YT.Player('full-video', {
		events: {
			'onReady': onPlayerReady
		}
	});
}

function onPlayerReady() {
	loop.playVideo();
	loop.mute();
	resize_video();
	jQuery( '#full-video' ).removeAttr( 'height' ).removeAttr( 'width' ).delay(1000).fadeTo( 'slow', 1 );
}

window.onresize = resize_video;

function resize_video() {
	var loop_container = jQuery( '#full-video' ),
			window_height = window.innerHeight,
			window_width = window.innerWidth, 
			new_height = window_width * 0.5625; /* Assuming 16x9 ratio */
	if ( new_height < window_height ) {
		loop_container.width( window_height / 0.5625 ).height( window_height );
	} else {
		loop_container.width( window_width ).height( new_height );
	}
}