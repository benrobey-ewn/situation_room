var doLightning = false;
var scale = 1.1;
var LightningValue = 1800;

$( document ).ready(function() {
	setInterval(function () {
		if(doLightning===true) {
			//console.log("getjson");
			$.ajax({
				type: 'GET',
				url: "http://sitdata.ewn.com.au/gpats/getjson.php?t=25",
				async: true,
				contentType: "application/json",
				dataType: 'jsonp'
			}).done(function(data) {
				gpats_callback(data);
				
			});
		}
	}, 10000);

	setInterval(function() {
		if(doLightning===true) {
			removeOld();
			map.data.setStyle(function(feature) {
					var seconds = new Date().getTime() / 1000;
					var timeElapsed = seconds - feature.getProperty('timestamp');
					var fraction = timeElapsed/(LightningValue/255);
					var color = 'rgb(' + Math.round((255 - fraction)) + ', 0, ' + Math.round(0 + fraction) + ')';
					if(timeElapsed<15) { var stkWeight = 2; var stkColor = "#000"; } else { var stkWeight = 0.5; var stkColor = "#fff"; }
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
				});
		}
	}, 30000);
	
	$("#checkbox-1-4").change(function(event) {
		if($('#checkbox-1-4').prop('checked')===true) {
			doLightning = true;
			initializeGpats();
			settingsUpdate();
		} else {
			doLightning = false;
			deInitializeGpats();
			settingsUpdate();
		}
	});
});
		
var lightningId = [];
var lightningData = new Object();
//if(doLightning===true) { google.maps.event.addDomListener(window, 'load', initialize); }

function gpats_callback(data) {
	for(i=0;i<data.features.length;i++) {
		for(x=0;x<lightningId.length;x++) {
			if(data.features[i].properties.id==lightningId[x]) {
				var exists = true;
			}
		}
		if(exists===true) {
			//console.log(data.features[i].properties.id, "exists");
			exists = false;
		} else {
			console.log(data.features[i].properties.id, "is new");
			map.data.addGeoJson(data.features[i]);
			//lightningData[i]
			lightningId.push(data.features[i].properties.id);
		}
	}
}

function removeOld() {
	var curDate = new Date().getTime();
	map.data.forEach(function(feature) { 
		var date = new Date(feature.getProperty('timestamp')*1000);
		var minElapsed = Math.round((curDate - date)/1000/60);
		if(date < (curDate-(LightningValue*1000))) {
			console.log(feature.getProperty('id'), "is old");
			map.data.remove(feature);
		}
	});
}

function deInitializeGpats() {
	map.data.forEach(function(feature) {
		map.data.remove(feature);
		lightningId = [];
	});
}

function changeLightningLength(value) {
	LightningValue = value;
	if(doLightning === true) {
		deInitializeGpats();
		initializeGpats();
	}
}
		
function initializeGpats() {
	
	$.ajax({
		type: 'GET',
		url: "http://sitdata.ewn.com.au/gpats/getjson.php?t=" + LightningValue,
		async: true,
		contentType: "application/json",
		dataType: 'jsonp'
	}).done(function(data) {
		gpats_callback(data);
	});
	
	map.data.setStyle(styleFeature);
	
	map.data.addListener('mouseover', function(event) {
		var date = new Date((event.feature.getProperty('timestamp')*1000));
		var curDate = new Date().getTime();
		var minElapsed = Math.round((curDate - date)/1000/60);
		if(event.feature.getProperty('mag')>0 && event.feature.getProperty('strokeType') != 3) { var polarity = "+ "; var magnitude = event.feature.getProperty('mag') } else { var polarity = "- "; var magnitude = event.feature.getProperty('mag')}
		if(event.feature.getProperty('strokeType')==0) { var strokeType = "Ground"; } else { var strokeType = "Cloud"; var polarity = ""; var magnitude = "--"; }
		document.getElementById('info-box').innerHTML = 'Time: ' + minElapsed + ' minutes ago<br>Latitude: ' + event.feature.getProperty('lat') + '<br>Longitude: ' + event.feature.getProperty('long') + '<br>Stroke Type: ' + polarity + strokeType + " Stroke<br>" + 'Magnitude: ' + magnitude + 'kA\n ';
		$("#info-box").css("display", "block");
		//console.log(map.getZoom());
	});
	
	google.maps.event.addListener(map, 'zoom_changed', function() {
    	zoom = map.getZoom();
		if(zoom<8) { scale = 1.1; }
		if(zoom==8) { scale = 1.2; }
		if(zoom==9) { scale = 1.3; }
		if(zoom==10) { scale = 1.4; }
		if(zoom>10) { scale = 1.5; }
		console.log(zoom);
	
  	});
	
	map.data.addListener('mouseout', function(event) {
		$("#info-box").css("display", "none");
	});
}

function styleFeature(feature) {
	var seconds = new Date().getTime() / 1000;
	var timeElapsed = seconds - feature.getProperty('timestamp');
	var fraction = timeElapsed/(LightningValue/255);
	var color = 'rgb(' + Math.round((255 - fraction)) + ', 0, ' + Math.round(0 + fraction) + ')';
	if(timeElapsed<15) { var stkWeight = 2; var stkColor = "#000"; } else { var stkWeight = 0.5; var stkColor = "#fff"; }
	console.log(stkWeight);
	//console.log(color);
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