(function($){

	/** Fullscreen/responsive video **/
	$(function() {
		var video = $('#full-video');
		video.removeAttr('height').removeAttr('width');
		$(window).on( 'load resize', function() {
			var newHeight = $(window).width() * video.attr('data-aspect');
			if ( newHeight < $(window).height() ) {
				video.width( $(window).height() / video.attr('data-aspect') ).height( $(window).height() );
			} else {
				video.width($(window).width()).height(newHeight);
			}
		});
	});

}(jQuery));

/** YouTube iframe API stuff **/
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var loop;

function onYouTubeIframeAPIReady() {
	loop = new YT.Player('full-video', {
		events: {
			'onReady': onPlayerReady
		}
	});
}

function onPlayerReady() {
	loop.playVideo();
	jQuery( '#full-video' ).delay(1000).fadeTo( 'slow', 1 );
	loop.mute();
}