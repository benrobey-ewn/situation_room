var doLightning = false;
var scale = 1.1;
var LightningValue = 1800;
var lightningId = [];
var lightningData = new Object();

var triangleCoords = [
	{lat: -24.5, lng: 151.35},
	{lat: -24.5, lng: 146.00},
	{lat: -28.04, lng: 146.0},
	{lat: -28.04, lng: 151.35}
];

// Construct the polygon.
var lightningBounds = new google.maps.Polygon({
	paths: triangleCoords,
	strokeColor: '#CCCCCC',
	strokeOpacity: 0.8,
	strokeWeight: 1,
	fillColor: '#CCCCCC',
	fillOpacity: 0.4
});

$( document ).ready(function() {
	setInterval(function () {
		if(doLightning===true) {
			var mapbounds = map.getBounds();
			var viewportLatMin = mapbounds.getNorthEast().lat();
			var viewportLatMax = mapbounds.getSouthWest().lat();
			var viewportLonMin = mapbounds.getNorthEast().lng();
			var viewportLonMax = mapbounds.getSouthWest().lng();
			if(viewportLatMin > 0) { viewportLatMin = 0; }
			if(viewportLonMin < 0) { viewportLonMin = 180; }
			var viewportZoom = map.getZoom();
			$.ajax({
				type: 'GET',
				url: "http://sitdata.ewn.com.au/lightning/getjson.php?t=3&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom,
				async: true,
				contentType: "application/json",
				dataType: 'json'
			}).done(function(data) {
				for(i=0;i<data.features.length;i++) {
					for(x=0;x<lightningId.length;x++) {
						if(data.features[i].properties.id==lightningId[x]) {
							var exists = true;
						}
					}
					if(exists===true) {
						exists = false;
					} else {
						map.data.addGeoJson(data.features[i]);
						lightningId.push(data.features[i].properties.id);
					}
				}
			});
			removeOld();
			map.data.setStyle(styleFeature);
		}
	}, 1000);
	
	$("#checkbox-1-4").change(function(event) {
		if($('#checkbox-1-4').prop('checked')===true) {
			doLightning = true;
			initializeLightning();
		} else {
			doLightning = false;
			deInitializeLightning();
		}
	});
	
	$("#lightninglength").change(function(event) {
		changeLightningLength($('#lightninglength option:selected').val());
	});
	
	$("#lightningshowbounds").change(function(event) {
		if($('#lightningshowbounds').prop('checked')===true) {			
			lightningBounds.setMap(map);
		} else {
			lightningBounds.setMap(null);
		}
	});
});

function removeOld() {
	var curDate = new Date().getTime();
	map.data.forEach(function(feature) { 
		var date = new Date(feature.getProperty('timestamp')*1000);
		var minElapsed = Math.round((curDate - date)/1000/60);
		if(date < (curDate-(LightningValue*1000))) {
			map.data.remove(feature);
		}
	});
}

function deInitializeLightning() {
	map.data.forEach(function(feature) {
		map.data.remove(feature);
		lightningId = [];
	});
}

function changeLightningLength(value) {
	LightningValue = value;
	if(doLightning === true) {
		deInitializeLightning();
		initializeLightning();
	}
}
		
function initializeLightning() {
	var mapbounds = map.getBounds();
	var viewportLatMin = mapbounds.getNorthEast().lat();
	var viewportLatMax = mapbounds.getSouthWest().lat();
	var viewportLonMin = mapbounds.getNorthEast().lng();
	var viewportLonMax = mapbounds.getSouthWest().lng();
	if(viewportLatMin > 0) { viewportLatMin = 0; }
	if(viewportLonMin < 0) { viewportLonMin = 180; }
	var viewportZoom = map.getZoom();
	$.ajax({
		type: 'GET',
		url: "http://sitdata.ewn.com.au/lightning/getjson.php?t=" + LightningValue + "&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom,
		async: true,
		timeout: 300000,
		contentType: "application/json",
		dataType: 'json'
	}).done(function(data) {
		console.log(data);
		for(i=0;i<data.features.length;i++) {
			for(x=0;x<lightningId.length;x++) {
				if(data.features[i].properties.id==lightningId[x]) {
					var exists = true;
				}
			}
			if(exists===true) {
				exists = false;
			} else {
				map.data.addGeoJson(data.features[i]);
				lightningId.push(data.features[i].properties.id);
			}
		}
	}).fail(function(error) {
		console.log(error);
	});
	
	map.data.setStyle(styleFeature);
	
	map.data.addListener('mouseover', function(event) {
		var date = new Date((event.feature.getProperty('timestamp')*1000));
		var curDate = new Date().getTime();
		var minElapsed = Math.round(event.feature.getProperty('age')/60);
		if(event.feature.getProperty('mag')>0 && event.feature.getProperty('strokeType') != 3) { var polarity = "+ "; var magnitude = event.feature.getProperty('mag') } else { var polarity = "- "; var magnitude = event.feature.getProperty('mag')}
		if(event.feature.getProperty('strokeType')==0) { var strokeType = "Ground"; } else { var strokeType = "Cloud"; var polarity = ""; var magnitude = "--"; }
		document.getElementById('info-box').innerHTML = 'Time: ' + minElapsed + ' minutes ago<br>Latitude: ' + event.feature.getProperty('lat') + '<br>Longitude: ' + event.feature.getProperty('long') + '<br>Stroke Type: ' + polarity + strokeType + " Stroke<br>" + 'Magnitude: ' + magnitude + 'kA\n ';
		$("#info-box").css("display", "block");
	});
	
	google.maps.event.addListener(map, 'zoom_changed', function() {
    	zoom = map.getZoom();
		if(zoom<8) { scale = 1.1; }
		if(zoom==8) { scale = 1.2; }
		if(zoom==9) { scale = 1.3; }
		if(zoom==10) { scale = 1.4; }
		if(zoom>10) { scale = 1.5; }	
  	});
	
	map.data.addListener('mouseout', function(event) {
		$("#info-box").css("display", "none");
	});
}

function styleFeature(feature) {
	feature.setProperty('age',parseInt(feature.getProperty('age'))+1);
	var timeElapsed = parseInt(feature.getProperty('age'));
	var fraction = timeElapsed/(LightningValue/255);
	var color = 'rgb(' + Math.round((255 - fraction)) + ', 0, ' + Math.round(0 + fraction) + ')';
	if(timeElapsed<10) { var stkWeight = 2; var stkColor = "#000"; } else { var stkWeight = 0.5; var stkColor = "#fff"; }
	if(feature.getProperty('strokeType')==0) {
		if(feature.getProperty('mag')>0) {
			return {
				icon: {
					path: 'm5.219986,29.697235l23.795303,0l0,-25.117241l24.409424,0l0,25.117241l23.795288,0l0,25.765518l-23.795288,0l0,25.117264l-24.409424,0l0,-25.117264l-23.795303,0l0,-25.765518z',
					strokeWeight: stkWeight,
					strokeColor: stkColor,
					fillColor: color,
					fillOpacity: 0.5,
					scale: 0.12 * scale
				},
			};
		} else {
			return {
				icon: {
					path: google.maps.SymbolPath.BACKWARD_OPEN_ARROW,
					strokeWeight: stkWeight,
					strokeColor: stkColor,
					fillColor: color,
					fillOpacity: 0.5,
					scale: 1.5 * scale
				},
			};
		}
	} else {
		return {
			icon: {
				path: google.maps.SymbolPath.CIRCLE,
				strokeWeight: stkWeight,
				strokeColor: stkColor,
				fillColor: color,
				fillOpacity: 0.5,
				scale: 2 * scale
			},
		};
	}
}