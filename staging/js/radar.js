
$(document).ready(function () {
    ewn2_maps_pageLoad();
});
function ewn2_maps_pageLoad() {
	//useHttp();
	window.setTimeout("if (typeof initialize == 'function') { initialize() };", 100);
}
// (c) Copyright 2006-2015 Early Warning Network Pty Ltd. All Rights Reserved. 
// ewn.com.au

var observation_zoom = 0;

//useHttp();
var useAjax = true;
var d = false;
var map = null;
var polyShape = null;
var polyShapeStatic;
var polygonMode = true;
var polygonDepth = "20";
var polyPoints = [];
var mapTool = "polygon";
var marker;
var markers = [];
var disableMarkerRefresh = false;
var geocoder = null;
var autocomplete = null;
var input = null;
var blnSilent = false;

var fillColor = "#DB7605"; // orange fill
var lineColor = "#ff0000"; // red line
var opacity = .5;
var radar_opacity = 100;
var lineWeight = 2;
var radartimeinterval = 300000;
var staticfillColor = "#0066FF"; // orange fill
var staticlineColor = "#ff0000"; // red line
var staticopacity = .2;
var staticlineWeight = 2;

var homePoint = "-28.005277, 153.401684";
var ewnhome = new String("-28.005277, 153.401684");
var ewnhomePoint = [-28.005277, 153.401684];

var iconBaseUrl = "/icons/";
var viewPointLat = null;
var viewPointLong = null;
var radarSetupDone = false;
var staticRadars = {};
var staticRadarImagesM = false;
var staticRadarImagesTimer = null;
var staticRadarImagesTimerDelay = 100;
var staticRadarImagesTimerRepeatDelay = 2000;
var staticRadarImagesDownloading = 0;
var staticRadarControl = null;
var staticRadarControlTimer = null;
var staticRadarControlVisible = null;
var staticRadarPaused = false;
var staticRadarTimes = [];
var staticRadarTimesX = -1;
var staticRadarTimeText = null;
var staticRadarInitialLoad = true;
var selectedRadarType = 'Auto';
var timerViewPortChanged = null;
var staticPoints = [];
var staticPolys = [];
var staticText = [];
var staticPolyShapes = [];
var staticPolyPoints = [];
var staticOverlays = [];
var staticGas = [];
var mapElems = [];
var maps = [];
var mapsLoaded = 0;
var mapsLoading = false;
var markerClusterer = null;
var lastAddress = '';
var infoBox = null;
var anchor = null;
var timerMembersViewPortChanged = null;
var timerAddPolygonFromKmlId = null;
var timerResizeMapsDelay = null;
var mapOpt = null;
var kmlStyles = {};
var mapStyles = {};
var gp;
var weatherLayer;
var cloudLayer;
var mapLabelTimer = null;
var mapLabel = null;
var mapLabelOver = false;
var bodyElem = null;
var footerElem = null;
var windowElem = null;
var mapAlertMarkers = [];
var mapCycloneMarkers = [];
var mapAlertMarkersById = {};
var mapAlertAreas = [];
var mapCycloneAreas = [];
var mapAlertAreasLoaded = false;
var point = null;
var mapAlertLabelsById = {};
var newAlertsTimer = null;
var scrollTimeout, enableScrollRefresh = false;
var RECENTTIME = 64800; // 10800
var mapsLoadedFired = false;
var mapsLoadedQueue = [];

var rio2;
var infowindow = new google.maps.InfoWindow();

mapsLoadedQueue.push = function () {
	if (mapsLoadedFired) {
		if (arguments && arguments.length > 0) {
			var mX = arguments.length;
			for (var x = 0; x < mX; x++) {
				(arguments[x])();
			}
		}
	}
	else {
		return Array.prototype.push.apply(this, arguments);
	}
}
kmlStyles["red"] = { strokeColor: "#FF0000",
	strokeOpacity: 0.8,
	strokeWeight: 2,
	fillColor: "#FF0000",
	fillOpacity: 0.35
};
kmlStyles["green"] = { strokeColor: "#00FF00",
	strokeOpacity: 0.8,
	strokeWeight: 2,
	fillColor: "#00FF00",
	fillOpacity: 0.35
};
kmlStyles["yellow"] = { strokeColor: "#FFFF00",
	strokeOpacity: 0.8,
	strokeWeight: 2,
	fillColor: "#FFFF00",
	fillOpacity: 0.35
};
kmlStyles["orange"] = { strokeColor: "#FFCC00",
	strokeOpacity: 0.8,
	strokeWeight: 2,
	fillColor: "#FFCC00",
	fillOpacity: 0.35
};
kmlStyles["blue"] = { strokeColor: "#0000FF",
	strokeOpacity: 0.8,
	strokeWeight: 2,
	fillColor: "#0000FF",
	fillOpacity: 0.35
};
mapStyles["grey"] = [{ featureType: 'water', elementType: 'all', stylers: [{ hue: '#e9ebed' }, { saturation: -78 }, { lightness: 67 }, { visibility: 'simplified'}] }, { featureType: 'landscape', elementType: 'all', stylers: [{ hue: '#ffffff' }, { saturation: -100 }, { lightness: 100 }, { visibility: 'simplified'}] }, { featureType: 'road', elementType: 'geometry', stylers: [{ hue: '#bbc0c4' }, { saturation: -93 }, { lightness: 31 }, { visibility: 'simplified'}] }, { featureType: 'poi', elementType: 'all', stylers: [{ hue: '#ffffff' }, { saturation: -100 }, { lightness: 100 }, { visibility: 'off'}] }, { featureType: 'road.local', elementType: 'geometry', stylers: [{ hue: '#e9ebed' }, { saturation: -90 }, { lightness: -8 }, { visibility: 'simplified'}] }, { featureType: 'transit', elementType: 'all', stylers: [{ hue: '#e9ebed' }, { saturation: 10 }, { lightness: 69 }, { visibility: 'on'}] }, { featureType: 'administrative.locality', elementType: 'all', stylers: [{ hue: '#2c2e33' }, { saturation: 7 }, { lightness: 19 }, { visibility: 'on'}] }, { featureType: 'road', elementType: 'labels', stylers: [{ hue: '#bbc0c4' }, { saturation: -93 }, { lightness: 31 }, { visibility: 'on'}] }, { featureType: 'road.arterial', elementType: 'labels', stylers: [{ hue: '#bbc0c4' }, { saturation: -93 }, { lightness: -2 }, { visibility: 'simplified'}]}];
mapStyles["subtle"] = [{ featureType: "administrative", elementType: "all", stylers: [{ visibility: "on" }, { saturation: -100 }, { lightness: 20}] }, { featureType: "road", elementType: "all", stylers: [{ visibility: "on" }, { saturation: -100 }, { lightness: 40}] }, { featureType: "water", elementType: "all", stylers: [{ visibility: "on" }, { saturation: -10 }, { lightness: 30}] }, { featureType: "landscape.man_made", elementType: "all", stylers: [{ visibility: "simplified" }, { saturation: -60 }, { lightness: 10}] }, { featureType: "landscape.natural", elementType: "all", stylers: [{ visibility: "simplified" }, { saturation: -60 }, { lightness: 60}] }, { featureType: "poi", elementType: "all", stylers: [{ visibility: "off" }, { saturation: -100 }, { lightness: 60}] }, { featureType: "transit", elementType: "all", stylers: [{ visibility: "off" }, { saturation: -100 }, { lightness: 60}]}];
mapStyles["default"] = [{ featureType: "poi", elementType: "all", stylers: [{ visibility: "off" }, { saturation: -100 }, { lightness: 60}]}];
var icons = null;
var things = {};
if (!window.console) console = {};
console.log = console.log || function () { };
console.warn = console.warn || function () { };
console.error = console.error || function () { };
console.info = console.info || function () { };


function initialize() {

	
	// check for maps first
	mapElems = $("#map-canvas").add(".mapcanvas");
	if (mapElems.length > 0) {

		if (typeof google == "undefined") {
			if (!mapsLoading) {
				mapsLoading = true;
				console.log("Loading Google Maps...");
				var gmUrl = "https://maps.googleapis.com/maps/api/js?sensor=false";
				var gmLibs = []; //libraries = visualization,drawing,weather,places
				if ($('#optWeather').length > 0 || $('#optCloud').length > 0) {
					gmLibs.push("weather");
				}
				if ($('#setLocationAutoComplete').add('.setLocationAutoComplete').length > 0) {
					gmLibs.push("places");
				}
				if (mapElems.attr("data-editor")) {
					gmLibs.push("drawing");
				}
				if (mapElems.attr("data-clusters") || mapElems.attr("data-marker-poly-id")) {
					gmLibs.push("visualization");
				}
				if (gmLibs.length > 0) {
					gmUrl += "&libraries=" + gmLibs.join(',');
				}
				gmUrl += '&callback=initialize';
				console.log("Loading Google Maps from " + gmUrl);
				var gmscript = document.createElement('script');
				gmscript.type = 'text/javascript';
				gmscript.src = gmUrl;
				document.body.appendChild(gmscript);
			}
			return;
		}
		if (settings.radar.status == true) {
			initEWNMapsRadarPrototype();
		}

		homePoint = MyHomePoint();

		if (markerClusterer) {
			markerClusterer.clearMarkers();
		}
		var maxX = mapElems.length;
		for (var x = 0; x < maxX; x++) {
			$(mapElems).data('mapIndex', x);
			setupMap(mapElems[x]);
		}
		// Add static points if defined
		if (staticPoints.length > 0) {
			var loc = new String(window.parent.document.location);
			if (loc.indexOf("alertgis") == -1) {
				drawStaticPoints();
			}
		}

		// Show Polygons is defined
		if (staticPolys.length > 0) {
			// full map
			//ViewPortAustralia();
			drawStaticPolygons();
		}
		// Show PolyImages
		if (staticGas.length > 0) {
			drawStaticPolyImages();
		}
		// auto load
		window.setTimeout("if (typeof autoLoadPolygon == 'function') { autoLoadPolygon() }", 600);
		if ($(".ddlListGroups").length > 0)
			window.setTimeout("if (typeof getMembersSetup == 'function') { getMembersSetup() }", 300);

		//was: window.setTimeout("if (typeof mapLoaded == 'function') { mapLoaded() }", 500);
		// now: counter is setup in setupMap, so setup a backup with longer delay incase 'idle' never fires
		window.setTimeout("if (typeof fireMapLoadedEvent == 'function') { fireMapLoadedEvent(true); }", 3000);

		if (settings.radar.status == true) {
			try {
				//addRadars();
			}
			catch (ex) {
				console.error(ex);
			}
		}
		$(window).resize(function () { resizeMapsDelayed(); });

	   // new test work for - railway tracks
		markerShownOrNot = false; 
	 	google.maps.event.addListener(map, 'zoom_changed', function() {
	 		var zoomLevel = map.getZoom();
	 		measureRailwayTrackZoom(zoomLevel);
	 	});
	} //end: if maps exist
}
function wHasFocus() {
	try {
		return document.hasFocus();
	} catch (e) {
		console.log("unable to determine if document hasFocus. " + e);
	}
	return false;
}
function setupMap(newmapElem) {
	if (typeof google == "undefined"){
		initialize();
		return;
	}
	if (geocoder == null) {
		geocoder = new google.maps.Geocoder();
	}


	var mapinit = getMapInitCookie();
	var ccenter = new google.maps.LatLng(mapinit.c.lat, mapinit.c.lng);



	if (mapOpt == null) {
	    var maptypeID = google.maps.MapTypeId.ROADMAP;
	    mapOpt = {
	        center: ccenter,
	        zoom: mapinit.z,
	        mapTypeControl: true,
	        mapTypeControlOptions: {
	            position: google.maps.ControlPosition.TOP_LEFT
	        },
	        panControl: true,
	        panControlOptions: {
	            position: google.maps.ControlPosition.LEFT_TOP
	        },
	        streetViewControl: false,
	        streetViewControlOptions: {
	            position: google.maps.ControlPosition.LEFT_TOP
	        },
	        zoomControl: true,
	        zoomControlOptions: {
	            style: google.maps.ZoomControlStyle.LARGE,
	            position: google.maps.ControlPosition.LEFT_TOP
	        },
	        scaleControl: true,
	        //mapTypeId: google.maps.MapTypeId.HYBRID,
	        mapTypeId: maptypeID,
	    };
	}

	var newmap = new google.maps.Map(newmapElem, mapOpt);

	maps.push(newmap);
	// Main map =  first map

	if (!map) {
		map = newmap;
	//	newmapElem.mapClickListener = google.maps.event.addListener(map, "click", mapClick);
	}
	newmapElem.map = newmap;

	google.maps.event.addListenerOnce(newmap, 'idle', function () {
		fireMapLoadedEvent();
	});

	initMap(newmap);

	// add autocomplete functions
	//setupMapAutoComplete(newmap);

	return newmap;
}
function fireMapLoadedEvent(force) {
	if (force == true) {
		if (mapsLoadedFired == false) {
			mapsLoadedFired = true;
			if (typeof mapLoaded == 'function') {
				mapLoaded();
			}
			while (mapsLoadedQueue.length > 0) {
				(mapsLoadedQueue.shift())();
			}
		}
	} else {
		// increase map loaded counter
		mapsLoaded = mapsLoaded + 1;
		// fire event if all maps loaded
		if (mapsLoaded == maps.length
			&& mapsLoadedFired == false) {
			mapsLoadedFired = true;
			if (typeof mapLoaded == 'function') {
				mapLoaded();
			}
			while (mapsLoadedQueue.length > 0) {
				(mapsLoadedQueue.shift())();
			}
		}
	}
}
function initMapCenter(newmapElem, mapOpt) {
	mapOpt.center = MyHomePoint();

	return mapOpt;
}
function initMap(newmap) {
	var newmapElemJq = $(newmap.getDiv());

	var dMarkerLat = newmapElemJq.data("markerLat");
	var dMarkerLon = newmapElemJq.data("markerLon");
	var dMarkerPoly = newmapElemJq.data("markerPoly");
	var dMarkerPolyId = newmapElemJq.data("markerPolyId");
	var dKmlPolyId = newmapElemJq.data("kmlPolyId");
	var dMarkerEdit = newmapElemJq.data("markerEdit");
	var dMarkerType = newmapElemJq.data("markerType");
	var dMapStyle = newmapElemJq.data("mapStyle");
	var dMapIndex = parseInt(newmapElemJq.data("mapIndex"));
	var dUpdatell = newmapElemJq.data("updatell");
	var dAllowRadius = parseBool(newmapElemJq.data("allowRadius"));
	var dMarkerMove = parseBool(newmapElemJq.data("allowMarkerMove"));
	var dMarkerRadius = parseInt(newmapElemJq.data("markerRadius"));
	var dShowTraffic = parseBool(newmapElemJq.data("traffic"));
	var hasAddressFields = $("#setLocationSuburb").add(newmapElemJq.find(".setLocationSuburb")).length > 0;
	if (dMarkerLat != null && dMarkerLon != null && (hasAddressFields == false || dMapIndex > 0 || isNaN(dMapIndex)))
		addStaticPoint(dMarkerLat, dMarkerLon, "", dMarkerType, "", 1, newmap, undefined, dMarkerRadius);
	else if (dMarkerPolyId != null)
		addPolygonFromId(dMarkerPolyId, dMarkerType, newmap);
	else if (dKmlPolyId != null)
		addPolygonFromKmlId(dKmlPolyId, dMarkerType, newmap);
	//else if (dMarkerType == "photo")
	//	initPhotos(newmap);
	var setMapStyle;
	if (dMapStyle != null) {
		setMapStyle = mapStyles[dMapStyle];
	}
	if (!setMapStyle) {
		setMapStyle = mapStyles['default'];
	}
	if (!!setMapStyle)
		newmap.setOptions({ styles: setMapStyle });

	if (dAllowRadius)
		setupRadiusControls(newmap);

	if (dUpdatell) {
		google.maps.event.addListener(map, 'bounds_changed', function () {
			if (map.updateLLTimer)
				window.clearTimeout(map.updateLLTimer);
			map.updateLLTimer = window.setTimeout("if (typeof updateLL == 'function') { updateLL(); }", 3000);
		});
	}
	if (dShowTraffic) {
		newmap.trafficLayer = new google.maps.TrafficLayer();
		newmap.trafficLayer.setMap(newmap);	
	}
	// Jump to address if its defined
	if (hasAddressFields && dMapIndex == 0) {
		showAddress();
	}
	var dClusters = newmapElemJq.data("clusters")
	newmap.useClusters = (dClusters == "True" || dClusters == "true" || dClusters == true);

	// add fullscreen control
	//mapAddFullScreenControl(newmap);


	// resizeable maps
	if (newmapElemJq.hasClass('mapResizeable') && typeof jQuery.ui != 'undefined') {
		// wrap the map with a new div with the resize background at the bottom
		var wrapper = $(document.createElement("div")).addClass('mapResizeableArea');
		newmapElemJq.wrap(wrapper);
		$('.mapResizeableArea').resizable({
			maxHeight: null,
			maxWidth: null,
			minHeight: newmapElemJq.height(),
			minWidth: 10,
			helper: "ui-resizable-helper",
			alsoResize: '#map_canvas',
			stop: function (event, ui) {
				resizeMapsDelayed();
				trackEvent('alerts', 'map', 'resize', ui.size.height);
			}
		});
	}
}
function updateLL() {
	if (map != null) {
		var mapElem = $(map.getDiv());
		var dUpdatell = mapElem.data("updatell");
		if (dUpdatell) {
			var mapcenter = map.getCenter();
			var mapzoom = map.getZoom();
			try {
				if (mapcenter) {
					var cval = new String().concat(mapcenter.lat().toFixed(6), ",", mapcenter.lng().toFixed(6));
					if (!isNaN(cval) && !!!cval && cval.indexOf("NaN") < 0) {
						$.cookie("ll", cval, { expires: 2592000, path: '/' });
					}
				}
				if (mapzoom)
					$.cookie("llz", mapzoom, { expires: 2592000, path: '/' });
			} catch (e) {
				console.log("updateLL not possible");
			}
		}
	}
}
function clearStaticPoints() {
	staticPoints = [];
}

function snapBounds(event) {
	if (map != null) {
		var mapbounds = map.getBounds();
		if (mapbounds != null && mapbounds != undefined) {
			var viewportLatMin = mapbounds.getNorthEast().lat();
			var viewportLatMax = mapbounds.getSouthWest().lat();
			var viewportLonMax = mapbounds.getNorthEast().lng();
			var viewportLonMin = mapbounds.getSouthWest().lng();
			// if we have wrapped the world, pan so that the dateline is on the edge
			var c = map.getCenter();
			//console.log('center lat:' + c.lat() + ' lng:' + c.lng() + ' vpLngMin:' + viewportLonMin + ' vpLngMax:' + viewportLonMax);
			if (viewportLonMax < 0 && viewportLonMin > 0) {
				// right dateline edge
				if (c.lng() > 0) {
					map.panTo(new google.maps.LatLng(c.lat(), c.lng() - (180 + viewportLonMax)));
				}
				else {
					// left dateline edge
					map.panTo(new google.maps.LatLng(c.lat(), c.lng() + (180 - viewportLonMin)));
				}
			}

			// store maps current center and zoom (reused in initmap)
			/*
			c = map.getCenter();
			var z = map.getZoom();
			var llVal = c.lat() + "," + c.lng();

			setCookie('ll', llVal, 2592000);
			setCookie('llz', z, 2592000);			
			*/

			updateLL();

			$('.setViewportCenter').val(llVal);
			$('.setViewportZoom').val(z);
		}
	}
}
// Show all of Australia on the map
function ViewPortAustralia(newmap) {
	if (newmap != null) {
		newmap.setCenter(new google.maps.LatLng(-27.978636, 130.396606));
		newmap.setZoom(4);
	} else {
		map.setCenter(new google.maps.LatLng(-27.978636, 130.396606));
		map.setZoom(4);
	}
}
// Show all of Australia on the map
function SetViewPort(lon, lat) {
	viewPointLat = lat;
	viewPointLong = lon;
}

var mapinitcsave = null;
function saveMapInitCookie() 
{
	if (mapinitcsave) { clearTimeout(mapinitcsave); }
	mapinitcsave = setTimeout( saveMapInitCookie_() , 500);
}
function saveMapInitCookie_()
{

	var mapinit = getMapInitCookie();

	var c = map.getCenter();
	if (c) {
	    mapinit.c = gmaplatlng2hash(c);
	    mapinit.z = map.getZoom();
	    mapinit.sw = gmaplatlng2hash(map.getBounds().getSouthWest());
	    mapinit.ne = gmaplatlng2hash(map.getBounds().getNorthEast());

	    if (mapinit.c && mapinit.z && mapinit.sw && mapinit.ne) {
	        var str = JSON.stringify(mapinit);
	        if (str.indexOf("NaN") < 0) {
	            $.cookie("mapinit", str, { expires: 365 });
	        }
	    }
	}
}
function getMapInitCookie()
{

	var mapinitdefault = { z: 5, c: { lat: -28.4106257631404, lng: 133.73119068749995 }, sw: { lat: -44.707623291039795, lng: 112.39574146874997 }, ne: { lat: -9.177909380446085, lng: 155.06663990624997 } }
	var mapinit = $.cookie("mapinit") || mapinitdefault;
	if (typeof mapinit == "string") {
		try {
			mapinit = JSON.parse(mapinit);
		} catch (e)
		{
			console.error('mapinit cookie is invalid - using defaults');
			mapinit = mapinitdefault;
		}
	}

	// sanity check mapinit obj
	if (!mapinit.c) { mapinit.c = mapinitdefault.c; }
	if (!mapinit.z) { mapinit.z = mapinitdefault.z; }
	if (!mapinit.sw) { mapinit.sw = mapinitdefault.sw; }
	if (!mapinit.ne) { mapinit.ne = mapinitdefault.ne; }
	if (!mapinit.c.lat) { mapinit.c.lat = mapinitdefault.c.lat; }
	if (!mapinit.c.lng) { mapinit.c.lng = mapinitdefault.c.lng; }
	if (!mapinit.sw.lat) { mapinit.sw.lat = mapinitdefault.sw.lat; }
	if (!mapinit.sw.lng) { mapinit.sw.lng = mapinitdefault.sw.lng; }
	if (!mapinit.ne.lat) { mapinit.ne.lat = mapinitdefault.ne.lat; }
	if (!mapinit.ne.lng) { mapinit.ne.lng = mapinitdefault.ne.lng; }

	var clat = mapinit.c.lat || mapinitdefault.c.lat;
	var clng = mapinit.c.lng || mapinitdefault.c.lng;
	var zoom = mapinit.z || mapinitdefault.z;


	return mapinit;

}
function gmaplatlng2hash(glatlng) {
	var h = {};
	h.lat = glatlng.lat();
	h.lng = glatlng.lng();
	return h;
}

function MyHomePoint() {
	
	var mapinit = getMapInitCookie();
	var ccenter = new google.maps.LatLng(mapinit.c.lat, mapinit.c.lng);
	return ccenter;
}


function showMap() {
	if ($("#map_canvas").add(".mapcanvas").css("visibility") != "visible") {
		$("#map_canvas").add(".mapcanvas").css("visibility", "visible");
		resizeMapsDelayed();
	}
}
function EWNMapsOverlay(fn, bounds, map, layerOrder) {

	// Now initialize all properties.
	this.bounds_ = bounds;
	this.fn_ = fn;
	this.map_ = map;
	this.layerOrder_ = layerOrder;
	// We define a property to hold the image's
	// div. We'll actually create this div
	// upon receipt of the add() method so we'll
	// leave it null for now.
	this.div_ = null;

	// Explicitly call setMap on this overlay
	this.setMap(map);

	// make sure the map fits the bounds
	map.fitBounds(bounds);
}

function drawStaticPolyImages() {

	EWNMapsOverlay.prototype = new google.maps.OverlayView();

	EWNMapsOverlay.prototype.onAdd = function () {

		// Note: an overlay's receipt of add() indicates that
		// the map's panes are now available for attaching
		// the overlay to the map via the DOM.

		// Create the DIV and set some basic attributes.
		var div = document.createElement('div');
		div.style.border = 'none';
		div.style.borderWidth = '0px';
		div.style.position = 'absolute';

		if (this.layerOrder_ != undefined) {
			div.style.zIndex = 100 + parseInt(this.layerOrder_);

			/*debug
			div.style.border = 'solid';
			div.style.borderWidth = '2px';
			div.style.borderColor = '#' + (parseInt(div.style.zIndex) * 62578).toString(16);
			div.style.backgroundColor = div.style.borderColor;
			*/
		}

		// Create an IMG element and attach it to the DIV.
		var img = document.createElement('img');
	    //img.src = this.currentImage().url;
		img.className = "radarImg";
		img.src = sameHostUrl(this.fn_);
		img.style.width = '100%';
		img.style.height = '100%';
		/*debug
		img.style.border = 'solid';
		img.style.borderWidth = '1px';
		img.style.borderColor = 'red';
		*/
		img.style.opacity = '' + (radar_opacity / 100);
		img.style.filter = "progid:DXImageTransform.Microsoft.Alpha(Opacity=" + radar_opacity + ")"; // first!
		img.style.filter = "alpha(opacity=" + radar_opacity + ")"; /* For IE8 and earlier */
		this.img_ = img;
		div.appendChild(img);

		// Set the overlay's div_ property to this DIV
		this.div_ = div;

		// We add an overlay to a map via one of the map's panes.
		// We'll add this overlay to the overlayImage pane.
		var panes = this.getPanes();
		// was - but placed overlay above marker images
		//panes.overlayImage.appendChild(this.div_);
		// now - should place overlay behind markers
		panes.mapPane.appendChild(this.div_);

	}
	EWNMapsOverlay.prototype.draw = function () {

		// Size and position the overlay. We use a southwest and northeast
		// position of the overlay to peg it to the correct position and size.
		// We need to retrieve the projection from this overlay to do this.
		var overlayProjection = this.getProjection();

		// Retrieve the southwest and northeast coordinates of this overlay
		// in latlngs and convert them to pixels coordinates.
		// We'll use these coordinates to resize the DIV.
		var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
		var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

		// Resize the image's DIV to fit the indicated dimensions.
		var div = this.div_;
		div.style.left = sw.x + 'px';
		div.style.top = ne.y + 'px';
		div.style.width = (ne.x - sw.x) + 'px';
		div.style.height = (sw.y - ne.y) + 'px';

		if (this.layerOrder_ != undefined) {
			div.style.zIndex = 100 + parseInt(this.layerOrder_);
		}
		else
			div.style.zIndex = 100;
	}


	for (var x = 0; x < staticGas.length; x++) {
		var thisGa = staticGas[x];

		var neBound = new google.maps.LatLng(thisGa.ne.lat, thisGa.ne.lng);
		var swBound = new google.maps.LatLng(thisGa.sw.lat, thisGa.sw.lng);
		var bounds = new google.maps.LatLngBounds(swBound, neBound);
		var r = new EWNMapsOverlay(thisGa.fn, bounds, map, x);
		staticOverlays[x] = r;

		// add click passthrough so polygons can be drawn over polygons
		google.maps.event.addListener(staticOverlays[x], "click", function (latlng) {
			if (latlng != null && latlng != undefined) {
				mapClick(latlng);
			}
		});

	}
}

function addGa(fileName,Alng,Alat,Blng,Blat)
{
	var ga = { fn: fileName, sw: { lng: Alng, lat: Alat }, ne: { lng: Blng, lat: Blat }};
	staticGas.push(ga);
}
function valOrEmpty(val) {
	if (val == null || val == undefined)
		return '';
	else
		return val;
}
function valOrDefault(val,def) {
	if (val == null || val == undefined || val == "")
		return def;
	else
		return val;
}
function valQuotedOrEmpty(val) {
	if (val == null || val == undefined)
		return '';
	else
		return "'" + val + "'";
}
function valQuotedOrNull(val) {
	if (val == null || val == undefined)
		return null;
	else
		return "'" + val + "'";
}
function valOrNull(val) {
	if (val == null || val == undefined || val == "")
		return null;
	else
		return val;
}
function fixJson(data) {
	//fix ms json dates
	data = data.replace(/\\\\\/Date\((-?\d+)\)\\\\\//g,
		function (o, m1) {
			var ldate = new Date(parseInt(m1));
			var utcDate = new Date(Date.UTC(ldate.getFullYear(), ldate.getMonth(), ldate.getDate(), ldate.getHours(), ldate.getMinutes(), ldate.getSeconds()));

			var localString = $.datepicker.formatDate('D dd M y', utcDate) + ' ' + utcDate.toLocaleTimeString();
			if (localString.indexOf('NaN') >= 0)
				return utcDate.toLocaleString();
			return localString;
		})
	return data;
}
function resizeMaps() {
	//console.log('resizeMaps called');
	for (var x = 0; x < maps.length; x++) {
		if (!maps[x].recenter) {
			//console.log('resizeMaps saving map center');
			maps[x].recenter = maps[x].getCenter();
		}
		google.maps.event.addListenerOnce(maps[x], 'bounds_changed', function (event) {
			//console.log('resizeMaps ListenerOnce bounds_changed');
			if (!!this.recenter) {
				//console.log('resizeMaps ListenerOnce bounds_changed setting map center');
				this.setCenter(this.recenter);
			}
			if (!!this.marker) {
				//console.log('resizeMaps ListenerOnce bounds_changed fit marker');
				fitToRadius(this.marker);
			}
			this.recenter == null;
		});
		google.maps.event.trigger(maps[x], 'resize');
		//console.log('resizeMaps setting map center');
		maps[x].setCenter(maps[x].recenter);
	}
}
function resizeMapsDelayed() {
	//console.log('resizeMapsDelayed called');
	for (var x = 0; x < maps.length; x++) {
		if (!maps[x].recenter) {
			//console.log('saving map center');
			maps[x].recenter = maps[x].getCenter();
		}
	}
	if (timerResizeMapsDelay) {
		window.clearTimeout(timerResizeMapsDelay);
	} 
	timerResizeMapsDelay = window.setTimeout("resizeMaps();", 500);
}

function sameHostUrl(path) {
	var host = new String(window.location.host);
	if (path.substring(0, 1) != "/")
		path = "/" + path;
	return window.location.protocol + "//" + host + path
}

function radarHostUrl(path) {

	if (path.substring(0, 1) != "/")
		path = "/" + path;
	/*var host = new String(window.location.host);
	if (host.indexOf('local') < 0 && host.indexOf('test') < 0 && host.indexOf('ci.') < 0)*/
	var	host = 'sitdata.ewn.com.au';
	return window.location.protocol + "//" + host + path
}
function sendAjax(callMethod, data, onSuccess, onFailure, srcElem) {
	/*
	var url = new String(window.location.host);
	url = window.location.protocol + "//" + url + "/exo/webextensions.asmx";
	url += "/" + callMethod;
	*/
	var url = sameHostUrl("/exo/webextensions.asmx/" + callMethod);
	if (data == null || data.length == 0)
		data = '{}';
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		contentType: "application/json; charset=utf-8",
		dataType: "json",
		processData: false,
		context: (srcElem) ? srcElem.first() : null,
		success: function (jqXHR, textStatus) {
			if (onSuccess != null)
				onSuccess(jqXHR, textStatus, srcElem);
		},
		error: function (jqXHR, textStatus, errorThrown, thrownError) {
			if (onFailure != null)
				onFailure(jqXHR, textStatus, errorThrown, srcElem);
		}
	});
}


function getRadarImagesForViewPort(viewportLatMin, viewportLatMax, viewportLonMin, viewportLonMax, viewportZoom, radarType) {
//	if (useAjax) {
	var data = "minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom + "&radarType=" + radarType;
	//console.log(data);
	showMessage('Loading Radars...');
	//sendAjax('GetRadarImagesForViewPort2', data, getRadarImagesForViewPortSuccess, getRadarImagesForViewPortError, $(map.getDiv()));
		
	var srcElem = $(map.getDiv());
	var url = "http://sitdata.ewn.com.au/radar/getRadar.php";
	if (data == null || data.length == 0)
		data = '{}';
	$.ajax({
		type: "GET",
		url: url,
		data: data,
		contentType: "application/json; charset=utf-8",
		dataType: "json",
		processData: true,
		context: (srcElem) ? srcElem.first() : null,
		success: function (jqXHR, textStatus) {
				//console.log(jqXHR);
				getRadarImagesForViewPortSuccess(jqXHR, textStatus, srcElem);
		},
		error: function (jqXHR, textStatus, errorThrown, thrownError) {

				getRadarImagesForViewPortError(jqXHR, textStatus, errorThrown, srcElem);
		}
	});

		
		
		return true;
//	}
	return false;
}

function getRadarImagesForViewPortSuccess(jqXHR, textStatus, srcElem) {
//	var result = $.parseJSON(fixJson(jqXHR));
		var result = jqXHR;
	polyPoints = [];
	var data = result.Data;
	if (data == null || data.length == 0 || result.IsSuccess == false)
		showMessage("Unable to load radars or there are no radar images available for this view");
	else {
	    if (d)
	    {
	        console.debug(data);
	    }
		if (typeof data == "String") {
		 data = $.parseJSON(data);
		 }
		//clearRadarImages();

		var prevRadar = "";
		var r;
		var ri;
		var rit = {};

		$.each(data, function () {
			var curRadar = this.RadarCode;
			if (prevRadar != curRadar) {
				// add only new radars
				if (!staticRadars[this.RadarCode]) {
					var neBound = new google.maps.LatLng(this.OverlayTLlat, this.OverlayBRlon);
					var swBound = new google.maps.LatLng(this.OverlayBRlat, this.OverlayTLlon);
					var bounds = new google.maps.LatLngBounds(swBound, neBound);
					r = new EWNMapsRadar(this.RadarCode, bounds, map, this.layerOrder);
					staticRadars[this.RadarCode] = r;
				}
				else {
					r = staticRadars[this.RadarCode];
					r.clearImages();
				}
				//console.log("added radar " + this.RadarCode + " " + this.RadarType + " - " + this.RadarName);
			}

			var ris = {};
			$.each(this.RadarImages, function () {
			    // add image to radar
			    var riurl = radarHostUrl(this.Folder + this.Fn);
			    ri = new EWNMapsRadarImage(this.Fn, this.ImageDate, this.Fn, riurl);
			    ris[this.ImageDate] = ri;

			    // kept track of unique times
			    rit[this.ImageDate] = this.ImageDate;
			});

			r.images_ = sortRadarImages(ris);

		    // start loading first image immediately
			if (r.images_.length > 0)
			    r.loadImage();



			prevRadar = curRadar;
		});

		// convert radar times into sorted array
		//console.log('new radar times');
		//console.log(rit);
		//console.log('old radar times');
		//console.log(staticRadarTimes);
		staticRadarTimes = radarTimesPadded(hashMerge(staticRadarTimes, rit));
		if (staticRadarTimes != null && staticRadarTimes.length > 0)
			staticRadarTimesCurrent = staticRadarTimes[0];
		//console.log('combined radar times');
		//console.log(staticRadarTimes);
		drawRadarImages();
		showMessage('');
	}

}
function sortRadarImages(ris)
{
    var orig = hashKeys(ris).sort();
    var rissorted = [];
    for (var o in orig) {
        rissorted.push(ris[orig[o]]);
    }
    return rissorted;
}
function hashMerge(h1, h2) {
	var out = {};
	if (h1 != null && h1 != undefined) {
		if (h1 instanceof Array) {
			var maxX = h1.length;
			for (var x = 0; x < maxX; x++) {
				out[h1[x]] = h1[x];
			}
		}
		else if (h1 instanceof Object) {
			for (var key in h1) {
				out[key] = h1[key];
			}
		}
	}
	if (h2 != null && h2 != undefined) {
		if (h2 instanceof Array) {
			var maxY = h2.length;
			for (var y = 0; y < maxY; y++) {
				out[h2[y]] = h2[y];
			}
		}
		else if (h2 instanceof Object) {
			for (var key in h2) {
				out[key] = h2[key];
			}
		}
	}
	return out;
}
function radarTimesPadded(rit) {
	var orig = hashKeys(rit).sort();
	var pad = [];
	var lasto = null;
	// loop through original times, adding missing minutes
	for (var o in orig) {
		var od = Date.fromISO(orig[o]);
		if (lasto != null) {
			var diff = Math.floor((od.getTime() - lasto.getTime())/1000/60);
			for (var p = 1; p < diff; p++) {
				pad.push(radarTimeAddMinutes(lasto,p));
			}
		}
		lasto = od;
		pad.push(orig[o]);
	}
	return pad.sort();
}
function radarTimeAddMinutes(date, minutes)
{
	var d = new Date(date.getTime() + minutes * 60000);
	//2013-02-12 06:00:00Z
	var ds = d.getUTCFullYear() + '-' + padTimePart(d.getUTCMonth() + 1) + '-' + padTimePart(d.getUTCDate()) + ' ' +
				padTimePart(d.getUTCHours()) + ':' + padTimePart(d.getUTCMinutes()) + ':' + padTimePart(d.getUTCSeconds()) + 'Z';
	return ds;
}
function padTimePart(num) {
	return new String(num).charPadLeft('0', 2);
}
String.prototype.repeat = function (num) {
	if (num < 1) return '';
	if (num == 1) return this;
	return new Array(num + 1).join(this);
}
String.prototype.charPadLeft = function (padChar, maxLength) {
	if (this == null || this.length >= maxLength)
		return this;
	var padded = new String(padChar.repeat(maxLength - this.length) + this);
	return padded;
}
function getRadarImagesForViewPortError(jqXHR, textStatus, errorThrown, srcElem) {
	console.error(textStatus + "  " + (errorThrown ? errorThrown : ''));
	showMessage(textStatus + "  " + (errorThrown ? errorThrown : ''));
	if (staticRadarTimeText != undefined && staticRadarTimeText != null)
		staticRadarTimeText.text('-');
}
function clearRadarImages() {
	// clear timer
	clearRadarImagesTimer();
	for (var key in staticRadars) {
		staticRadars[key].setMap(null);
	}
	staticRadarImages = [];
	staticRadars = {};
	staticRadarTimes = [];
	staticRadarTimesX = -1;
}
function setRadarImagesTimer(t) {
	if (!staticRadarPaused) {
		clearRadarImagesTimer();
		if (!t)
			t = staticRadarImagesTimerDelay;
		staticRadarImagesTimer = setTimeout("if (typeof EWNMapsRadarNext == 'function') { EWNMapsRadarNext() };", t);
	}
}
function clearRadarImagesTimer() {
	if (staticRadarImagesTimer != null)
		window.clearTimeout(staticRadarImagesTimer);
	staticRadarImagesTimer = null;
}
function drawRadarImages() {
	// show radar images on map
	if (staticRadarInitialLoad) {
		// start timer (10 secs) to allow browser to settle first, if maps add the radars faster the timer start sooner.
		setRadarImagesTimer(10000);
		staticRadarInitialLoad = false;
	}
	else
		setRadarImagesTimer(staticRadarImagesTimerDelay);
}
function EWNMapsRadarNext() {
	if (staticRadarPaused)
		return;
	//EWNMapsRadarPlayStart();
	// if still waiting on last frame to download, just reset timer
	if (staticRadarImagesDownloading > 0) {
		setRadarImagesTimer(staticRadarImagesTimerDelay);
		return;		
	}
	// advance timeframe to next
	staticRadarTimesX = staticRadarTimesX + 1;
	if (staticRadarTimesX >= staticRadarTimes.length) {
	    staticRadarTimesX = -1;
	    // are we looping ?
	    if (settings.radar.loop) {
	        // pause before restarting loop
	        setRadarImagesTimer(staticRadarImagesTimerRepeatDelay);
	    }
	} else {
		var currentRadarTime = staticRadarTimes[staticRadarTimesX];
		if (staticRadarTimeText == null || staticRadarTimeText.length == 0)
			staticRadarTimeText = $('#mapRadarTimeTextDiv .mapRadarControlText');			
		staticRadarTimeText.text(timeToLocal(currentRadarTime));
		// highlight starting time
		if (staticRadarTimesX == 0)
			staticRadarTimeText.addClass('highlight');
		else
			staticRadarTimeText.removeClass('highlight');
	    //$('#mapRadarTimeDiv').show();
		//console.log(currentRadarTime);
		for (var key in staticRadars) {
			if (staticRadars[key].isInMapBounds()) {
				staticRadars[key].showNextImage(currentRadarTime);
			}
		}
		

		// start the timer for the next fram
		setRadarImagesTimer(staticRadarImagesTimerDelay);
	}
}
function EWNMapsRadarNextFrame() {
	/* move the radar images one frame forward.
	   As not every time interval has a frame, we need to move forward up to 15 times until
	   we hit the next time interval with a different image.
	*/
	EWNMapsRadarPlayStop();
	var changedFrames = false;
	for (var f = 0; f < 15; f++) {
		// advance timeframe to next
		staticRadarTimesX = staticRadarTimesX + 1;
		if (staticRadarTimesX >= staticRadarTimes.length) {
			staticRadarTimesX = -1;
			// pause before restarting loop
			//setRadarImagesTimer(staticRadarImagesTimerRepeatDelay);
		} else {
			var currentRadarTime = staticRadarTimes[staticRadarTimesX];
			if (staticRadarTimeText == null || staticRadarTimeText.length == 0)
				staticRadarTimeText = $('#mapRadarTimeTextDiv .mapRadarControlText');
			staticRadarTimeText.text(timeToLocal(currentRadarTime));
			// highlight starting time
			if (staticRadarTimesX == 0)
				staticRadarTimeText.addClass('highlight');
			else
				staticRadarTimeText.removeClass('highlight');
			//$('#mapRadarTimeDiv').show();
			//console.log('currentRadarTime = ' + currentRadarTime);
			for (var key in staticRadars) {
				if (staticRadars[key].isInMapBounds()) {
					var changed = staticRadars[key].showNextImage(currentRadarTime)
					changedFrames = changedFrames || changed;
				}
			}
			if (changedFrames) {
				f = 100;
			}

			// start the timer for the next fram
			//setRadarImagesTimer(staticRadarImagesTimerDelay);
		}
	}
}
function EWNMapsRadarPrevFrame() {
	/* move the radar images one frame forward.
	As not every time interval has a frame, we need to move forward up to 15 times until
	we hit the next time interval with a different image.
	*/
	EWNMapsRadarPlayStop();
	var changedFrames = false;
	for (var f = 0; f < 15; f++) {
		// step timeframe to previous
		staticRadarTimesX = staticRadarTimesX - 1;
		if (staticRadarTimesX < 0) {
			staticRadarTimesX = staticRadarTimes.length-1;
			// pause before restarting loop
			//setRadarImagesTimer(staticRadarImagesTimerRepeatDelay);
		} else {
			var currentRadarTime = staticRadarTimes[staticRadarTimesX];
			if (staticRadarTimeText == null || staticRadarTimeText.length == 0)
				staticRadarTimeText = $('#mapRadarTimeTextDiv .mapRadarControlText');
			staticRadarTimeText.text(timeToLocal(currentRadarTime));
			// highlight starting time
			if (staticRadarTimesX == 0)
				staticRadarTimeText.addClass('highlight');
			else
				staticRadarTimeText.removeClass('highlight');
			//$('#mapRadarTimeDiv').show();
			//console.log('currentRadarTime = ' + currentRadarTime);
			for (var key in staticRadars) {
				if (staticRadars[key].isInMapBounds()) {
					var changed = staticRadars[key].showPrevImage(currentRadarTime)
					changedFrames = changedFrames || changed;
				}
			}
			if (changedFrames) {
				f = 100;
			}

			// start the timer for the next fram
			//setRadarImagesTimer(staticRadarImagesTimerDelay);
		}
	}
}
function EWNMapsRadarPlayStart() {
	staticRadarPaused = false;
	setRadarImagesTimer(staticRadarImagesTimerDelay);
	//staticRadarControl.attr('title', 'Pause radar animations');
	//if (!stringContains(staticRadarControl.css('background-image'), 'pause')) {
	//	staticRadarControl.css('background-image', 'url("/icons/control_pause.png")');
	//}
	$('.radar_pause').show();
	$('.radar_play').hide();
	// ensure radars are showing
	for (var key in staticRadars) {
		staticRadars[key].show();
	}
}
function EWNMapsRadarPlayStop() {
	clearRadarImagesTimer();
	//staticRadarControl.attr('title', 'Play radar animations');
	//staticRadarControl.css('background-image', 'url("/icons/control_play.png")');
	staticRadarPaused = true;
	$('.radar_pause').hide();
	$('.radar_play').show();
}
function EWNMapsRadarPlayToggle() {
	//if (staticRadarControl == null)
	//	staticRadarControl = $('#mapRadarPlayControlDivInner');
	if (staticRadarImagesTimer != null) {
		EWNMapsRadarPlayStop();
	}
	else {
		// play
		EWNMapsRadarPlayStart();
	}
}

function EWNMapsRadarShow() {
	addRadars();
	staticRadarPaused = false;
	setRadarImagesTimer(staticRadarImagesTimerDelay);
	//staticRadarControlVisible.attr('title', 'Hide radar animations');
	//staticRadarControlVisible.find('.mapRadarControlText').text('Hide');
	//staticRadarControlVisible.css('background-image', 'url("/icons/control_pause.png")');
//	$('#optRadar').prop('checked', true);
	// ensure radars are showing
	var maxX = staticRadars.length;
	for (var key in staticRadars) {
		staticRadars[key].show();
	}
	ChangeRadarOpacity();
}
function EWNMapsRadarHide() {
	clearRadarImagesTimer();
	//staticRadarControlVisible.attr('title', 'Show radar animations');
	//staticRadarControlVisible.find('.mapRadarControlText').text('Show');
	//staticRadarControl.css('background-image', 'url("/icons/control_play.png")');
	staticRadarPaused = true;
	// ensure radars are hidden
	for (var key in staticRadars) {
		staticRadars[key].hide();
	}
}
function EWNMapsRadarHideToggle() {
/*
	if (staticRadarControlVisible == null)
		staticRadarControlVisible = $('#mapRadarVisibleControlDivInner');
	if (staticRadarControlVisible.find('.mapRadarControlText').text() == 'Hide') {
		EWNMapsRadarHide();
	}
	else {
		// play
		EWNMapsRadarShow();
	}
	*/
}
function ChangeRadarOpacity() {
	EWNMapsRadarOpacityUpdate();
}
function EWNMapsRadarOpacityUpdate() {
	if (radar_opacity < 10)
		radar_opacity = radar_opacity * 100;
	if (radar_opacity < 10)
		radar_opacity = 10;

    $('.radarImg').css({
        opacity : '' + (radar_opacity / 100),
        filter : "progid:DXImageTransform.Microsoft.Alpha(Opacity=" + radar_opacity + ")", // first
    }).css({
        filter: "alpha(opacity=" + radar_opacity + ")" /* For IE8 and earlier */
    });
	settings.radar.opacity = radar_opacity;
	settingsUpdate();
}
function change_speed_maintain_loop(speed)
{
    var ispeed = parseInt(speed);
    if (!isNaN(ispeed)) {
        staticRadarImagesTimerDelay = ispeed;
    }
}
function EWNMapsRadarImage(key, date, fn, url) {
	this.key = key;
	this.date = date;
	this.fn = fn;
	this.url = url;


}

function EWNMapsRadar(radarCode, bounds, map, layerOrder) {

	// Now initialize all properties.
	this.bounds_ = bounds;
	this.images_ = [];
	this.jimages_ = [];
	this.map_ = map;
	this.imageX_ = 0;
	this.radarCode_ = radarCode;
	this.layerOrder_ = layerOrder;
	// We define a property to hold the image's
	// div. We'll actually create this div
	// upon receipt of the add() method so we'll
	// leave it null for now.
	this.div_ = null;


	// Explicitly call setMap on this overlay
	this.setMap(map);
}

//EWNMapsRadar.prototype = new google.maps.OverlayView();
function initEWNMapsRadarPrototype() {
	EWNMapsRadar.prototype = new google.maps.OverlayView();

	EWNMapsRadar.prototype.showNextImage = function (matchTime) {
		try {
			if (this.img_ == undefined && this.images_ > 0) {
				// maps aren't fully loaded yet
				staticRadarTimesX = -1;
				setRadarImagesTimer(1000);
			}
			if (this.images_.length == 0)
				return;
			// radar time sync
			//console.log('matchTime = ' + matchTime);
			//console.log('this.radarCode_ = ' + this.radarCode_);
			//console.log('this.imageX_ = ' + this.imageX_);
			//console.log('this.nextImage().date = ' + this.nextImage().date);
			//console.log('this.nextImage().date == matchTime = ' + new String(this.nextImage().date == matchTime));
			if (this.nextImage().date == matchTime) {
				this.img_.src = this.nextImage().url
				this.loadNextImage();
				return true;
			}
		} catch (e) {
			console.error(e);
		}
		return false;
	}
	EWNMapsRadar.prototype.loadNextImage = function () {
		// increase counter
		this.imageX_ = this.imageX_ + 1;
		if (this.imageX_ >= this.images_.length)
			this.imageX_ = 0;
		// load next image without displaying it
		this.loadImage();
	}
	EWNMapsRadar.prototype.showPrevImage = function (matchTime) {
		try {
			if (this.img_ == undefined && this.images_ > 0) {
				// maps aren't fully loaded yet
				staticRadarTimesX = -1;
				setRadarImagesTimer(1000);
			}
			if (this.images_.length == 0)
				return;
			// radar time sync
			if (this.prevImage().date == matchTime) {
				this.img_.src = this.prevImage().url
				this.loadPrevImage();
				return true;
			}
		} catch (e) {
			console.error(e);
		}
		return false;
	}
	EWNMapsRadar.prototype.loadPrevImage = function () {
		// increase counter
		this.imageX_ = this.imageX_ - 1;
		if (this.imageX_ < 0)
			this.imageX_ = this.images_.length - 1;
		// load next image without displaying it
		this.loadImage();
	}
	EWNMapsRadar.prototype.loadImage = function () {
		try {
			var img;
			if (this.jimages_.length <= this.imageX_) {
				img = new Image();
				img.parent = this;
				// remove image from radar images if it fails to load
				img.onerror = function () {
					this.parent.images_.splice(this.imageX_, 1);
					this.parent.jimages_.splice(this.imageX_, 1);
					// hide the radar if all images fail
					if (this.parent.images_.length == 0) {
						this.parent.setMap(null);
					} else {
						this.parent.loadImage();
					}
					staticRadarImagesDownloading--;
				};
				img.onload = function () {
					staticRadarImagesDownloading--;
				};
				staticRadarImagesDownloading++;
				img.src = this.currentImage().url;
				this.jimages_.push(img);
				if (d)
					console.log('Showing Image for Radar ' + this.radarCode_ + ' ' + img.src);
			}
		} catch (e) {
			console.error("x");
		}
	}


	EWNMapsRadar.prototype.currentImage = function () {
		return this.images_[this.imageX_];
	}
	EWNMapsRadar.prototype.prevImage = function () {
		var p = this.imageX_ - 1;
		if (p < 0) {
			p = this.images_.length - 1;
		}
		return this.images_[p];
	}
	EWNMapsRadar.prototype.nextImage = function () {
		var n = this.imageX_ + 1;
		if (n >= this.images_.length) {
			n = 0;
		}
		return this.images_[n];
	}
	EWNMapsRadar.prototype.onAdd = function () {

		// Note: an overlay's receipt of add() indicates that
		// the map's panes are now available for attaching
		// the overlay to the map via the DOM.

		// Create the DIV and set some basic attributes.
		var div = document.createElement('div');
		div.style.border = 'none';
		div.style.borderWidth = '0px';
		div.style.position = 'absolute';

		if (this.layerOrder_ != undefined) {
			div.style.zIndex = 100 + parseInt(this.layerOrder_);

			/*debug
			div.style.border = 'solid';
			div.style.borderWidth = '2px';
			div.style.borderColor = '#' + (parseInt(div.style.zIndex) * 62578).toString(16);
			div.style.backgroundColor = div.style.borderColor;
			*/
		}

		// Create an IMG element and attach it to the DIV.
		var img = document.createElement('img');
		img.className = "radarImg";
		//img.src = this.currentImage().url;
		img.src = sameHostUrl("/images/magic.gif");
		img.style.width = '100%';
		img.style.height = '100%';
		/*debug
		img.style.border = 'solid';
		img.style.borderWidth = '1px';
		img.style.borderColor = 'red';
		*/
		img.style.opacity = '0.9';
		img.style.filter = "progid:DXImageTransform.Microsoft.Alpha(Opacity=90)"; // first!
		img.style.filter = "alpha(opacity=90)"; /* For IE8 and earlier */
		this.img_ = img;
		div.appendChild(img);

		// Set the overlay's div_ property to this DIV
		this.div_ = div;

		// We add an overlay to a map via one of the map's panes.
		// We'll add this overlay to the overlayImage pane.
		var panes = this.getPanes();
		// was - but placed overlay above marker images
		//panes.overlayImage.appendChild(this.div_);
		// now - should place overlay behind markers
		panes.mapPane.appendChild(this.div_);

		setRadarImagesTimer(1000);
	}
	EWNMapsRadar.prototype.draw = function () {

		// Size and position the overlay. We use a southwest and northeast
		// position of the overlay to peg it to the correct position and size.
		// We need to retrieve the projection from this overlay to do this.
		var overlayProjection = this.getProjection();

		// Retrieve the southwest and northeast coordinates of this overlay
		// in latlngs and convert them to pixels coordinates.
		// We'll use these coordinates to resize the DIV.
		var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
		var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

		// Resize the image's DIV to fit the indicated dimensions.
		var div = this.div_;
		div.style.left = sw.x + 'px';
		div.style.top = ne.y + 'px';
		div.style.width = (ne.x - sw.x) + 'px';
		div.style.height = (sw.y - ne.y) + 'px';

		if (this.layerOrder_ != undefined) {
			div.style.zIndex = 100 + parseInt(this.layerOrder_);
		}
		else
			div.style.zIndex = 100;
	}

	EWNMapsRadar.prototype.onRemove = function () {
		this.div_.parentNode.removeChild(this.div_);
	}

	// Note that the visibility property must be a string enclosed in quotes
	EWNMapsRadar.prototype.hide = function () {
		if (this.div_) {
			this.div_.style.visibility = 'hidden';
		}
	}

	EWNMapsRadar.prototype.show = function () {
		if (this.div_) {
			// check if is in bounds
			if (this.isInMapBounds()) {
				this.div_.style.visibility = 'visible';
			}
			else {
				this.div_.style.visibility = 'hidden';
			}
		}
	}
	EWNMapsRadar.prototype.isInMapBounds = function () {
		var mapbounds = this.map_.getBounds();
		if (mapbounds != null && mapbounds != undefined) {
			return mapbounds.intersects(this.bounds_);
			//return true;
		}
	}
	EWNMapsRadar.prototype.toggle = function () {
		if (this.div_) {
			if (this.div_.style.visibility == 'hidden') {
				this.show();
			} else {
				this.hide();
			}
		}
	}

	EWNMapsRadar.prototype.toggleDOM = function () {
		if (this.getMap()) {
			this.setMap(null);
		} else {
			this.setMap(this.map_);
		}
	}
	EWNMapsRadar.prototype.clearImages = function () {
		this.images_ = [];
		this.jimages_ = [];
		this.imageX_ = 0;
	}
}
function addRadars(m) {
	if (!radarSetupDone) {
		if (m) {
			staticRadarImagesM = true;
			staticRadarImagesTimerDelay = staticRadarImagesTimerDelay * 2;
		}
		getRadarsSetup();
		addRadarControls();
		getRadarsViewPortChanged();
		//window.setTimeout("if (typeof EWNMapsRadarOutOfDate == 'function') { EWNMapsRadarOutOfDate() }", 900000); //15min
	}
	radarSetupDone = true;
}
function EWNMapsRadarOutOfDate() {
	EWNMapsRadarPlayStop();

	if (!staticRadarImagesM
		&& wHasFocus()) {
		window.refresh();
	}

	// Radar : Auto, 512km, 256km, 128km
	var mapRadarOutOfDateDiv = $('#mapRadarOutOfDateDiv');
	if (mapRadarOutOfDateDiv.length == 0) {
		mapRadarOutOfDateDiv = $(document.createElement("div")).attr('id', 'mapRadarOutOfDateDiv');
	}
	mapRadarOutOfDateDiv.html('');

	mapRadarOutOfDateDiv.css({
		'margin': '5px 5px 5px 0',
		'-webkit-box-shadow': 'rgba(0, 0, 0, 0.4) 0px 2px 4px',
		'box-shadow': 'rgba(0, 0, 0, 0.4) 0px 2px 4px',
		'background-position': 'initial initial',
		'background-repeat': 'initial initial',
		'width': '50%'
	});


	var mapRadarOutOfDateControlDiv = createMapControl('Radars may be outdated and have been paused, please click here to refresh', "", function () {
		if (staticRadarImagesM) {
			window.refresh();
			/*
			staticRadarPaused = false;
			getRadarsViewPortChanged();
			var mapRadarOutOfDateDiv = $('#mapRadarOutOfDateDiv');
			map.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(0);
			mapRadarOutOfDateDiv.remove();
			*/
		}
		else {
			window.refresh();
		}
	}, false, null, 'mapRadarOutOfDateControlDiv', 134, 34);
	mapRadarOutOfDateDiv.append(mapRadarOutOfDateControlDiv);
	mapRadarOutOfDateControlDiv.css({ width: 'auto', height: 'auto' });
	mapRadarOutOfDateDiv.find('.mapRadarControlText').css({ fontSize: '14px', width: 'auto', height: 'auto', padding: '10px' }).addClass('highlight');
	if (staticRadarImagesM)
		mapRadarOutOfDateDiv.css({'margin-bottom':'30px'});
	// add controlholder to map
	if (mapRadarOutOfDateDiv[0].index != 1) {
		mapRadarOutOfDateDiv[0].index = 1;
		map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(mapRadarOutOfDateDiv[0]);
	}

}
function getRadarsSetup() {
    google.maps.event.addListener(map, 'bounds_changed', function () {
        saveMapInitCookie();
		getRadarsViewPortChanged();
	});
}
function getRadars(radarType) {
	if (!staticRadarPaused) {
		if (radarType)
			selectedRadarType = radarType;
		if (map != null) {
			var mapbounds = map.getBounds();
			if (mapbounds != null && mapbounds != undefined) {
				var viewportLatMin = mapbounds.getNorthEast().lat();
				var viewportLatMax = mapbounds.getSouthWest().lat();
				var viewportLonMin = mapbounds.getNorthEast().lng();
				var viewportLonMax = mapbounds.getSouthWest().lng();
				var viewportZoom = map.getZoom();
				getRadarImagesForViewPort(viewportLatMin, viewportLatMax, viewportLonMin, viewportLonMax, viewportZoom, selectedRadarType);
			}
		}
	}
}
function getRadarsViewPortChanged() {
	if (timerViewPortChanged != null)
		window.clearTimeout(timerViewPortChanged);
	if (!disableMarkerRefresh && !staticRadarPaused) {
		showMessage('Loading radars...');

		if (staticRadarTimeText == null || staticRadarTimeText.length == 0)
			staticRadarTimeText = $('#mapRadarTimeTextDiv .mapRadarControlText');
		staticRadarTimeText.text('Loading radars...');
		timerViewPortChanged = window.setTimeout("if (typeof getRadars == 'function') { getRadars() }", 800);
	}
}
function addRadarControls() {
	// hide pan control, move zoom control
	// move the pan control to LEFT_TOP so it doesn't interferred with other controls
	/*
	map.setOptions({ 
		panControl: false,
		panControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
		},
		zoomControl: true,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.LARGE,
			position: google.maps.ControlPosition.LEFT_CENTER
		}
	});
*/

	var radarControlCss = {
		'margin': '5px 5px 5px 0',
		'-webkit-box-shadow': 'rgba(0, 0, 0, 0.4) 0px 2px 4px',
		'box-shadow': 'rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px',
		'background-position': 'initial initial',
		'background-repeat': 'initial initial',
		'border-bottom-right-radius': '2px',
		'border-top-right-radius': '2px',
		'-webkit-background-clip': 'padding-box',
		'border-width': '1px 1px 1px 0px',
		'border-top-style': 'solid',
		'border-right-style': 'solid',
		'border-bottom-style': 'solid',
		'border-top-color': 'rgba(0, 0, 0, 0.14902)',
		'border-right-color': 'rgba(0, 0, 0, 0.14902)',
		'border-bottom-color': 'rgba(0, 0, 0, 0.14902)',
	};
/*

	// Radar : Auto, 512km, 256km, 128km
	var mapRadarControlDiv = $('#mapRadarControlDiv');
	if (mapRadarControlDiv.length == 0)
	{
		mapRadarControlDiv = $(document.createElement("div")).attr('id','mapRadarControlDiv');
		mapRadarControlDiv.addClass('mapRadarControls');
	}
	mapRadarControlDiv.html('');

	// Set CSS styles for the DIV containing the control
	// Setting padding to 5 px will offset the control
	// from the edge of the map
	mapRadarControlDiv.css(radarControlCss);
	mapRadarControlDiv.css({
		'width' : '62px',
		height: '23px',
		overflow: 'hidden',
		display: 'none'
	});

	google.maps.event.addDomListener(mapRadarControlDiv[0], 'mouseover', function () {
		showMapRadarDropdown();
		if (staticRadarControlTimer != null) {
			window.clearTimeout(staticRadarControlTimer);
			staticRadarControlTimer = null;
		}
	});
	google.maps.event.addDomListener(mapRadarControlDiv[0], 'mouseout', function () {
		staticRadarControlTimer = window.setTimeout("if (typeof hideMapRadarDropdown == 'function') { hideMapRadarDropdown() }", 1000);
	});

	mapRadarControlDiv.append(createMapControl('Radars', "", function () {
		showMapRadarDropdown();
	}, false, null, null, 60, 21));
	mapRadarControlDiv.append(createMapControl('Auto', "Automatically choose radar's that are in view", function () {
		setMapRadarControl('Auto');
	}, true, '/icons/radar32w.gif', null, 60, 62));
	mapRadarControlDiv.append(createMapControl('512km', "Show only 512km Radars", function () {
		setMapRadarControl('512km');
	}, true, '/icons/radar32w.gif', null, 60, 62));
	mapRadarControlDiv.append(createMapControl('256km', "Show only 256km Radars", function () {
		setMapRadarControl('256km');
	}, true, '/icons/radar24w.gif', null, 60, 62));
	mapRadarControlDiv.append(createMapControl('128km', "Show only 128km Radars", function () {
		setMapRadarControl('128km');
	}, true, '/icons/radar16w.gif'));
	//(text, title, clickFunction, hideTopBorder, bgImg, id, widthPx, heightPx)


	// add controlholder to map
	if (mapRadarControlDiv[0].index != 1)
	{
		mapRadarControlDiv[0].index = 1;
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(mapRadarControlDiv[0]);
	}

	// default is for sr menu to be shown, so hide the radar controls.
//	mapRadarControlDiv.hide();
		*/


	// prev frame radar control
	var mapRadarPrevFrameControlDiv = $('#mapRadarPrevFrameControlDiv');
	if (mapRadarPrevFrameControlDiv.length == 0) {
		mapRadarPrevFrameControlDiv = $(document.createElement("div")).attr('id', 'mapRadarPrevFrameControlDiv');
		mapRadarPrevFrameControlDiv.addClass('mapRadarControls');
	}
	mapRadarPrevFrameControlDiv.html('');
	mapRadarPrevFrameControlDiv.css(radarControlCss);
	mapRadarPrevFrameControlDiv.css({
		'margin': '5px 0 5px 5px',
		'width': '20px',
		height: '23px',
		overflow: 'hidden',
		display: 'none'
	});


	var staticRadarNextControl = createMapControl(' ', "Previous Frame", function () {
		EWNMapsRadarPrevFrame();
	}, false, '/icons/control_rewind.png', 'mapRadarPrevFrameControlDivInner', 19, 21);
	staticRadarNextControl.css('border-right', 'none');
	mapRadarPrevFrameControlDiv.append(staticRadarNextControl);

	if (mapRadarPrevFrameControlDiv[0].index != 1) {
		mapRadarPrevFrameControlDiv[0].index = 2;
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(mapRadarPrevFrameControlDiv[0]);
	}



	/****/



	// play/pause radar control
	var mapRadarPlayControlDiv = $('#mapRadarPlayControlDiv');
	if (mapRadarPlayControlDiv.length == 0) {
		mapRadarPlayControlDiv = $(document.createElement("div")).attr('id', 'mapRadarPlayControlDiv');
		mapRadarPlayControlDiv.addClass('mapRadarControls');
	}
	mapRadarPlayControlDiv.html('');
	mapRadarPlayControlDiv.css(radarControlCss);
	mapRadarPlayControlDiv.css({
		'margin': '5px 0 5px 0',
		'width' : '20px',
		height: '23px',
		overflow: 'hidden',
		display: 'none'
	});


	staticRadarControl = createMapControl(' ', "Pause Radar animation", function () {
		EWNMapsRadarPlayToggle();
	}, false, '/icons/control_pause.png', 'mapRadarPlayControlDivInner', 19, 21);
	staticRadarControl.css('border-right', 'none');
	mapRadarPlayControlDiv.append(staticRadarControl);

	if (mapRadarPlayControlDiv[0].index != 1) {
		mapRadarPlayControlDiv[0].index = 2;
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(mapRadarPlayControlDiv[0]);
	}


	// next frame radar control
	var mapRadarNextFrameControlDiv = $('#mapRadarNextFrameControlDiv');
	if (mapRadarNextFrameControlDiv.length == 0) {
		mapRadarNextFrameControlDiv = $(document.createElement("div")).attr('id', 'mapRadarNextFrameControlDiv');
		mapRadarNextFrameControlDiv.addClass('mapRadarControls');
	}
	mapRadarNextFrameControlDiv.html('');
	mapRadarNextFrameControlDiv.css(radarControlCss);
	mapRadarNextFrameControlDiv.css({
		'margin': '5px 0 5px 0',
		'width': '20px',
		height: '23px',
		overflow: 'hidden',
		display: 'none'
	});


	var staticRadarNextControl = createMapControl(' ', "Next Frame", function () {
		EWNMapsRadarNextFrame();
	}, false, '/icons/control_fastforward.png', 'mapRadarNextFrameControlDivInner', 19, 21);
	staticRadarNextControl.css('border-right', 'none');
	mapRadarNextFrameControlDiv.append(staticRadarNextControl);

	if (mapRadarNextFrameControlDiv[0].index != 1) {
		mapRadarNextFrameControlDiv[0].index = 2;
		map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(mapRadarNextFrameControlDiv[0]);
	}
	// create a timer to hide the controls after maps adds them
	//window.setTimeout("radarHideRadarControlsOnMap();", 1000);

	// mobile only - maybe change to if $('#optRadar').length == 0
	/*
	if (staticRadarImagesM) {


		// play/pause radar control
		var mapRadarVisibleControlDiv = $('#mapRadarVisibleControlDiv');
		if (mapRadarVisibleControlDiv.length == 0) {
			mapRadarVisibleControlDiv = $(document.createElement("div")).attr('id', 'mapRadarVisibleControlDiv');
		}
		mapRadarVisibleControlDiv.html('');
		mapRadarVisibleControlDiv.css(radarControlCss);
		mapRadarVisibleControlDiv.css({
			'margin': '5px 0 5px 5px',
			'width': '46px',
			height: '23px',
			overflow: 'hidden'
		});
		// remove the margin from the previous control
		//mapRadarPlayControlDiv.css({ 'margin-left': '0' });

		staticRadarControlVisible = createMapControl('Hide', "Hide Radar animation", function () {
			EWNMapsRadarHideToggle();
		}, false, null, 'mapRadarVisibleControlDivInner', 44, 21);
		//staticRadarControlVisible.css('border-right', 'none');
		mapRadarVisibleControlDiv.append(staticRadarControlVisible);

		if (mapRadarVisibleControlDiv[0].index != 1) {
			mapRadarVisibleControlDiv[0].index = 3;
			map.controls[google.maps.ControlPosition.TOP_RIGHT].push(mapRadarVisibleControlDiv[0]);
		}



		// refresh control
		var mapRadarRefreshControlDiv = $('#mapRadarRefreshControlDiv');
		if (mapRadarRefreshControlDiv.length == 0) {
			mapRadarRefreshControlDiv = $(document.createElement("div")).attr('id', 'mapRadarRefreshControlDiv');
		}
		mapRadarRefreshControlDiv.html('');
		mapRadarRefreshControlDiv.css(radarControlCss);
		mapRadarRefreshControlDiv.css({
			'margin': '5px 0 5px 5px',
			'width': '60px',
			height: '23px',
			overflow: 'hidden'
		});
		// remove the margin from the previous control
		//mapRadarPlayControlDiv.css({ 'margin-left': '0' });

		mapRadarRefreshControl = createMapControl('Refresh', "Refresh Radar Images", function () {
			window.refresh();
		}, false, null, 'mapRadarRefreshControlDivInner', 58, 21);
		mapRadarRefreshControlDiv.append(mapRadarRefreshControl);

		if (mapRadarRefreshControlDiv[0].index != 1) {
			mapRadarRefreshControlDiv[0].index = 1;
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(mapRadarRefreshControlDiv[0]);
		}
	}
	*/

	var mapRadarTimeDiv = $('#mapRadarTimeDiv');
	if (mapRadarTimeDiv.length == 0) {
		mapRadarTimeDiv = $(document.createElement("div")).attr('id', 'mapRadarTimeDiv');
	}
	mapRadarTimeDiv.html('');
	mapRadarTimeDiv.css(radarControlCss);

	mapRadarTimeDiv.css({
		'width': '163px',
		height: '21px',
		overflow: 'hidden'
		//,display: 'none'
	})
	var mapRadarTimeTextDiv = createMapControl('-', "Radar Image Time", null, false, null, 'mapRadarTimeTextDiv', 163, 21);
	mapRadarTimeTextDiv.css({ overflow: 'hidden' });
	mapRadarTimeDiv.append(mapRadarTimeTextDiv);

	var mapRadarTimeZoneDiv = createMapControl('-', "Timezone", null, false, null, 'mapRadarTimeZoneDiv', 183, 21);
	mapRadarTimeDiv.append(mapRadarTimeZoneDiv);
	mapRadarTimeZoneDiv.css({ borderLeft: 'none' });
	mapRadarTimeZoneDiv.find('.mapRadarControlText').text(timeZone()).css({ fontSize: '11px' });

	// add controlholder to map
	if (mapRadarTimeDiv[0].index != 1) {
		mapRadarTimeDiv[0].index = 1;
		if (staticRadarImagesM)
			map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(mapRadarTimeDiv[0]);
		else
			map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(mapRadarTimeDiv[0]);
	}
	//staticRadarTimeText.hide();

	google.maps.event.addDomListener(mapRadarTimeDiv[0], 'mouseover', function () {
		showMapRadarSubcontrols('#mapRadarTimeDiv', '23px', '350px');
		if (staticRadarControlTimer != null) {
			window.clearTimeout(staticRadarControlTimer);
			staticRadarControlTimer = null;
		}
	});
	google.maps.event.addDomListener(mapRadarTimeDiv[0], 'mouseout', function () {
		staticRadarControlTimer = window.setTimeout("if (typeof hideMapRadarSubcontrols == 'function') { hideMapRadarSubcontrols('#mapRadarTimeDiv', '23px', '165px') }", 1000);
	});


}
function showMapRadarDropdown() {
	$('#mapRadarControlDiv').css({ overflow: 'visible', height: 'auto' });
}
function hideMapRadarDropdown() {
	$('#mapRadarControlDiv').css({ overflow: 'hidden', height: '20px' });
}
function showMapRadarSubcontrols(selector, height, width) {
	$(selector).css({ overflow: 'visible', height: height, width: width });
}
function hideMapRadarSubcontrols(selector, height, width) {
	$(selector).css({ overflow: 'hidden', height: height, width: width });
}
function hideMapRadar() {
	EWNMapsRadarPlayStop();
	for (var key in staticRadars) {
		staticRadars[key].hide();
	}
}
function showMapRadar() {
	EWNMapsRadarPlayStart(); // includes show
}
function setMapRadarControl(text) 
{
	// set active to bold
	$('.mapRadarControlText').css('font-weight','normal');
	$('.mapRadarControlText' + text).css('font-weight', 'bold');
	var radarType = text;
	if (radarType.substr(radarType.length - 2, 2) == 'km')
		radarType = radarType.substring(0, radarType.length - 2)
	staticRadarPaused = false;
	clearRadarImages();
	getRadars(radarType);
	hideMapRadarDropdown();
}
function createMapControl(text, title, clickFunction, hideTopBorder, bgImg, id, widthPx, heightPx) {

	if (!widthPx)
		widthPx = 60;
	if (!heightPx)
		heightPx = 62;
	if (widthPx == parseInt(widthPx))
		widthPx = widthPx + 'px';
	if (heightPx == parseInt(heightPx))
		heightPx = heightPx + 'px';
	// Set CSS for the control border

	var controlUI = $(document.createElement('div'));
	if (id) {
		controlUI.attr('id', id);
		controlUI.addClass(id);
	}
	controlUI.css({
		backgroundColor : 'white',
		borderStyle : 'solid',
		borderWidth : '0',
		borderColor : '#999',
		cursor : 'pointer',
		textAlign : 'center',
		display: 'inline-block',
		width: widthPx,
		height: heightPx,
		'vertical-align': 'middle'
	});
	//if (browserIsIE())
	//	controlUI.css({display: 'inline'});
	controlUI.attr('title', title);
	if (hideTopBorder)
		controlUI.css({ borderTop: 'none' });
	if (bgImg) {
		controlUI.css({ 
			background: 'white url("' + bgImg + '") center center no-repeat',
			height: heightPx
		});
	}


	// Set CSS for the control interior
	var controlText = $(document.createElement('div'));
	controlText.addClass('mapRadarControlText mapRadarControlText' + text);
	controlText.css({
		fontFamily : 'Arial,sans-serif',
		fontSize : '13px',
		color : '#000',
		paddingLeft : '6px',
		paddingRight : '6px',
		paddingTop : '1px',
		paddingBottom: '1px'
	});

	if (text.substr(0, selectedRadarType.length) == selectedRadarType)
		controlText.css({ fontWeight: 'bold' });
	controlText.text(text);

	controlUI.append(controlText);

	// Setup the click event listeners
	if (clickFunction != null)
		google.maps.event.addDomListener(controlUI[0], 'click', clickFunction);

	return controlUI;
}
function mapStaticToInteractive(elem) {
	elem = $(elem);
	elem.css({ 'background-image': 'url(/images/ajax-loader-150-loading.gif)' });
	elem.addClass('mapcanvas');
	elem.removeClass('clickable');
	elem.removeClass('gmStaticClickable');

	// create new div with same properties as original image
	var newElem = $(document.createElement('div'));
	$.each(elem[0].attributes, function (idx, attr) {
		newElem.attr(attr.nodeName, attr.value);
	});
	// set the width + height to the displayed size (responsive)
	if (elem.hasClass('gmStaticClickableAutoExpand')) {
		newElem.css('width', '100%');
	} else {
		newElem.css('width', elem.width() + 'px');
	}

	if (elem.height() < 400) {
		newElem.css('height', '400px');
	} else {
		newElem.css('height', elem.height() + 'px');
	}
	elem.replaceWith(newElem);

	if (mapsLoadedFired) {
		var newmap = setupMap(newElem[0]);

		if (typeof google != "undefined") {
			google.maps.event.addListenerOnce(newmap, 'idle', function () {

				// Add static points if defined
				if (staticPoints.length > 0) {
					var loc = new String(window.parent.document.location);
					if (loc.indexOf("alertgis") == -1) {
						drawStaticPoints();
					}
				}

				// Show Polygons is defined
				if (staticPolys.length > 0) {
					drawStaticPolygons();
				}
				// Show PolyImages
				if (staticGas.length > 0) {
					drawStaticPolyImages();
				}
			});
		}

	}

	if (typeof google == "undefined") {
		if (!mapsLoading) 
		{
			initialize();
		}
	}
}
function hashKeys(hash) {
	var array_keys = new Array();
	for (var key in hash) {
		array_keys.push(key);
	}
	return array_keys;
}

function isMobile() {
	if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1)
		return true;
	else
		return false;
}


function parseBool(val) {
	if (!val)
		return false;
	if (val == true || val == "true" || val == "True" || val == "1")
		return true;
	return false;
}

function adjustTimes() {
	var times = $('time.timeago');
	if (times.length > 0) {
		times.timeago();
	}
	var tzone = timeZone();

	var tlocal = $('time.timeutc2local');
	tlocal.each(function (index, elem) {
		elem = $(elem);
		//var newDate = new Date(elem.attr('datetime'));
		//elem.text($.datepicker.formatDate('dd M y ', newDate) + ' ' + newDate.toLocaleTimeString());
		elem.text(timeToLocal(elem.attr('datetime')));
		if (!elem.attr('title'))
			elem.attr('title', tzone);
	});
	var tzoneelem = $('.timezonestr');
	if (tzoneelem.length > 0) {
		if (tzone != null) {
			tzoneelem.text('Times shown in ' + tzone);
		}
	}
	var blogdates = $('.blog-date');
	blogdates.each(function (index, elem) {
		timeToBlogDate(elem, tzone); 
	});
}
function timeToBlogDate(e, tzone) {
		e = $(e);
		if (e.hasClass('gvIntroPicDiv'))
			return;
		var newDate = new Date(e.attr('datetime'));
		if (isNaN(newDate))
			newDate = Date.fromISO(e.attr('datetime'));
		if (isNaN(newDate))
			return;
			
		var diff = (new Date()).getTime() - newDate.getTime();
		if (diff > 23 * 60 * 60 * 1000)
		{
			// show date
			newDate = '<span class="blog-date-month">' + $.datepicker.formatDate('D', newDate) + '</span>'
					+ '<span class="blog-date-day">' + $.datepicker.formatDate('dd', newDate) + '</span>'
					+ '<span class="blog-date-year">' + $.datepicker.formatDate('M', newDate) + '</span>'
			e.html(newDate);
		}
		else
		{
			// show times
			newDate = '<span class="blog-date-month">' + newDate.toTimeString().substr(0,5) + '</span>'
					+ '<span class="blog-date-day-ago blog-date-day" title="' + e.attr('datetime') + '"></span>'
			e.html(newDate);
			e.find('.blog-date-day').timeago();
		}
		if (!e.attr('title'))
			e.attr('title', tzone);
}
function timeToLocal(datetimeString) {
	var newDate = new Date(datetimeString);
	if (isNaN(newDate))
		newDate = Date.fromISO(datetimeString);
	if (isNaN(newDate))
		return datetimeString + ' (UTC)';
	var localString = $.datepicker.formatDate('D dd M y', newDate) + ' ' + newDate.toLocaleTimeString();
	if (localString.indexOf('NaN') >= 0)
		return datetimeString + ' (UTC)';
	return localString;
}
function timeZone() {
	try {
		var d = new Date();
		var n = d.toTimeString();
		if (n.length > 8) {
			var i = n.indexOf(' ');
			var tz = n.substr(i + 1);
			var tzre = /\((.*)\)/;
			var tzrem = tzre.exec(tz);
			if (tzrem != null
				&& tzrem.length > 1) {
				return tzrem[1];
			}
			else
				return tz;
		}
	} catch (e) {
		console.log('unable to get locale timezone');
	}
	return '';
}

function showMessage(txt, deftxt) {
	if ((txt == null || txt.length == 0) && deftxt != undefined)
		txt = deftxt;

	if ($('#dialog').length > 0) {
		var msgBox = $('#dialog').find('.messagebox');
		if (msgBox.length > 0) {
			msgBox.text(txt);
		}
	} else {
		var msgBox = $('.messagebox');
		if (msgBox.length > 0) {
			msgBox.first().text(txt);
		}
	}
	if ((txt == null || txt.length == 0) && deftxt != undefined)
		return;
	//console.log(txt);
}

function stringContains(str, find) {
	if (str == null)
		return false;
	if (typeof str != "string")
		return false;
	return str.indexOf(find) >= 0;
}


function radarHideRadarControlsOnMap()
{
	$('.mapRadarControls').hide();
}

function radarShowRadarControlsOnMap()
{
	$('.mapRadarControls').show();
}