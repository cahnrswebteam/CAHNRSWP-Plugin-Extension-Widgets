var cahnrsIcons = [];
var locType = new Array();
  
locType[0] = "campus";
locType[1] = "energy";
locType[2] = "ext";
locType[3] = "rec";
locType[4] = "farms";
  
cahnrsIcons['campus'] = new google.maps.MarkerImage(
	imagePath + 'campus.png',
	new google.maps.Size(20, 22),
	null,
	new google.maps.Point(10, 22)
);

cahnrsIcons['energy'] = new google.maps.MarkerImage(
	imagePath + 'energy.png',
	new google.maps.Size(17, 17),
	null,
	new google.maps.Point(8, 8)
); 

cahnrsIcons['farms'] = new google.maps.MarkerImage(
	imagePath + 'farms.png',
	new google.maps.Size(20, 13),
	null,
	new google.maps.Point(10, 6)
); 

cahnrsIcons['rec'] = new google.maps.MarkerImage(
	imagePath + 'rec.png',
	new google.maps.Size(20, 18),
	null,
	new google.maps.Point(10, 9)
); 

cahnrsIcons['ext'] = new google.maps.MarkerImage(
	imagePath + 'ext.png',
	new google.maps.Size(15, 17),
	null,
	new google.maps.Point(7, 8)
); 	
	
//declare namespace
var cahnrsext = {};

//declare map
var map;

// CAHNRS location array    
var cahnrsloc = [];

//defines bounding box for all locations
var bounds;

// Creating a global infoWindow object that will be reused by all markers
var infoWindow = new google.maps.InfoWindow();

//toggle array layers on/off
cahnrsext.toggleArrayLayer = function(arraylayer) {
	if (arraylayer) {
		for (i in arraylayer) {					
			if (arraylayer[i].getVisible() == true) {
				arraylayer[i].setMap(null);
				arraylayer[i].visible = false;
			} else {
				arraylayer[i].setMap(map);
				arraylayer[i].visible = true;
			}						
		}
	}
}
 
 
//function to creae busstop
cahnrsext.createMark = function(i,latitude,longitude,name,type,typenum,contentString,data) {

	var markerLatLng = new google.maps.LatLng(latitude,longitude);
	
	//extent bounds four each stop and adjust map to fit to it.
	bounds.extend(markerLatLng);

	//if cahnrsloc is not defined, define it as an empty arrary first.
	if(typeof cahnrsloc[typenum] == "undefined") {
		cahnrsloc[typenum] = [];
	}
	
	//create the marker, but DO NOT map it.
	cahnrsloc[typenum][i] = new google.maps.Marker({
		position: markerLatLng,
		map: map,
		title: name,
		icon: cahnrsIcons[type],
		zindex: i
	});
	
	var latlng = new google.maps.LatLng(47.3500973611119,-120.900198462495);
		
	//contentString = contentString + '<a href="http://maps.google.com/maps?saddr=&daddr=' + markerLatLng + '"target="_blank">Directions</a>';
 
	if (typenum>0) {
  	cahnrsloc[typenum][i].visible = false;
	} else {
		cahnrsloc[typenum][i].visible = true;
	}

  var marker = cahnrsloc[typenum][i];

	google.maps.event.addListener(cahnrsloc[typenum][i], 'click', function(){
		infoWindow.setContent(contentString);
		infoWindow.open(map,cahnrsloc[typenum][i]);
	});

	map.fitBounds(bounds);

}
 
//map cahnrs locations
cahnrsext.cahnrsmap = function(type,typenum,wsheet) {
	
	var thecontent;
	var typenum;		  

	if (typenum>0) {
		jQuery('#map-locations').append('<input type="checkbox" id="toggle'+type+'" onClick="cahnrsext.toggleArrayLayer(cahnrsloc['+typenum+'])"> '+type + '<br>');
	} else {
		jQuery('#map-locations-list').append('<input type="checkbox" checked="checked" id="toggle'+type+'" onClick="cahnrsext.toggleArrayLayer(cahnrsloc['+typenum+'])"> '+type + '<br>');
	}

	bounds = new google.maps.LatLngBounds();

	jQuery.getJSON('http://spreadsheets.google.com/feeds/list/0AlQK02JZCmjvdHl4Y1ZGWVBXR1BVX2czYzdNQmF5enc/' + wsheet +'/public/values?alt=json-in-script&callback=?', function(data) {
		jQuery.each(data.feed.entry, function(i,entry) {
			thecontent = '<div id="info">' +
				'<strong><a href="' + entry.gsx$website.$t + '" target="_blank">' + entry.gsx$name.$t + '</a></strong><br />' +
				entry.gsx$address.$t/* + '<br />' +
				'<a href="mailto:' + entry.gsx$contact.$t +'">email</a><br />' +
				entry.gsx$phone.$t +*/
				'</div>';
				cahnrsext.createMark(i,entry.gsx$latitude.$t,entry.gsx$longitude.$t,entry.gsx$name.$t,entry.gsx$type.$t,typenum,thecontent,data.feed.entry);
		});
	});
}

//Function that gets run when the document loads
cahnrsext.initialize = function() {

	var map_options = {
    center: new google.maps.LatLng(47.3500973611119,-120.900198462495),
    zoom: 7,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControlOptions: { mapTypeIds: [] },
		backgroundColor: "#ffffff",
		disableDefaultUI: true,
		//draggable: false,
		keyboardShortcuts: true,
		mapMaker: false,
		noClear: true,
		mapTypeControl: true,
		overviewMapControl: true,
		panControl: true,
		rotateControl: true,
		scaleControl: true,
		zoomControl: true,
		scrollwheel: false,
  };

	map = new google.maps.Map(document.getElementById('cahnrs-map-canvas'), map_options);

	cahnrsext.cahnrsmap('Campuses',0,'oda');
	cahnrsext.cahnrsmap('WSU Extension Offices',2,'od8');
	cahnrsext.cahnrsmap('Research and Extension Centers',3,'od9');
	cahnrsext.cahnrsmap('Energy Program Offices',1,'odb');
	cahnrsext.cahnrsmap('Research Stations',4,'ocy');

}

google.maps.event.addDomListener( window, 'load', cahnrsext.initialize );


jQuery(document).ready(function($){
	$( '#map-toggle-locations' ).on( 'click', function(event) {
		$(this).parent('div').toggleClass('opened');
	});
});