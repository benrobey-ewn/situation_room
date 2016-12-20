var doLightning = false;
var scale = 1.1;
var LightningValue = 1800;
var lightningId = [];
var lightningData = new Object();

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
			console.log("http://sitdata.ewn.com.au/lightning/getLightningSantos.php?t=3&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom);
			$.ajax({
				type: 'GET',
				url: "http://sitdata.ewn.com.au/lightning/getLightningSantos.php?t=3&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom,
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
						//console.log(data.features[i].properties.id, "exists");
						exists = false;
					} else {
						console.log(data.features[i].properties.id, "is new");
						map.data.addGeoJson(data.features[i]);
						//lightningData[i]
						lightningId.push(data.features[i].properties.id);
					}
				}
			});
			removeOld();
			map.data.setStyle(styleFeature);
			/*map.data.setStyle(function(feature) {
				var seconds = new Date().getTime() / 1000;
				var timeElapsed = seconds - feature.getProperty('timestamp');
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
			});*/
		}
	}, 1000);

	/*setInterval(function() {
		if(doLightning===true) {
			removeOld();
			map.data.setStyle(function(feature) {
					var seconds = new Date().getTime() / 1000;
					var timeElapsed = seconds - feature.getProperty('timestamp');
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
				});
		}
	}, 10000);*/
	
	$("#checkbox-1-4").change(function(event) {
		if($('#checkbox-1-4').prop('checked')===true) {
			doLightning = true;
			initializeLightning();
			//settingsUpdate();
		} else {
			doLightning = false;
			deInitializeLightning();
			//settingsUpdate();
		}
	});
});

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
	console.log("http://sitdata.ewn.com.au/lightning/getLightningSantos.php?t=" + LightningValue + "&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom);
	$.ajax({
		type: 'GET',
		url: "http://sitdata.ewn.com.au/lightning/getLightningSantos.php?t=" + LightningValue + "&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom,
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
				//console.log(data.features[i].properties.id, "exists");
				exists = false;
			} else {
				console.log(data.features[i].properties.id, "is new");
				map.data.addGeoJson(data.features[i]);
				//lightningData[i]
				lightningId.push(data.features[i].properties.id);
			}
		}
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
	feature.setProperty('age',parseInt(feature.getProperty('age'))+1);
	var timeElapsed = parseInt(feature.getProperty('age'));
	console.log(feature.getProperty('timestamp'),timeElapsed);
	var fraction = timeElapsed/(LightningValue/255);
	var color = 'rgb(' + Math.round((255 - fraction)) + ', 0, ' + Math.round(0 + fraction) + ')';
	if(timeElapsed<10) { var stkWeight = 2; var stkColor = "#000"; } else { var stkWeight = 0.5; var stkColor = "#fff"; }
	//console.log(timeElapsed);
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


/*var doLightning = false;

var scale = 1.1;
var LightningValue = 1800;
var LightningUpdateFrequency = 1000;

$( document ).ready(function() {	
	$("#checkbox-1-4").change(function(event) {
		if($('#checkbox-1-4').prop('checked')===true) {
			initializeLightning();
			doLightning = true;
		} else {
			deInitializeLightning();
			doLightning = false;
		}
	});
	
	setInterval(function() {
		updateLightning();
	}, LightningUpdateFrequency);
});

function initializeLightning() {
	var mapbounds = map.getBounds();
	var viewportLatMin = mapbounds.getNorthEast().lat();
	var viewportLatMax = mapbounds.getSouthWest().lat();
	var viewportLonMin = mapbounds.getNorthEast().lng();
	var viewportLonMax = mapbounds.getSouthWest().lng();
	if(viewportLatMin > 0) { viewportLatMin = 0; }
	if(viewportLonMin < 0) { viewportLonMin = 180; }
	var viewportZoom = map.getZoom();
	console.log("http://sitdata.ewn.com.au/lightning/getjson.php?t=" + LightningValue + "&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom);
	map.data.loadGeoJson("http://sitdata.ewn.com.au/lightning/getjson.php?t=" + LightningValue + "&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom);
	var seconds = new Date().getTime() / 1000;
	map.data.setStyle({visible: true});
	map.data.setStyle(function(feature) {
		var timeElapsed = seconds - feature.getProperty('timestamp');
		var fraction = timeElapsed/(LightningValue/255);
		var color = 'rgb(' + Math.round((255 - fraction)) + ', 0, ' + Math.round(0 + fraction) + ')';
		if(timeElapsed<15) { var stkWeight = 2; var stkColor = "#000"; } else { var stkWeight = 0.5; var stkColor = "#fff"; }
		//console.log(stkWeight);
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
	});
	
	/*$.ajax({
		type: 'GET',
		url: "http://sitdata.ewn.com.au/lightning/getjson.php?t=" + LightningValue + "&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom,
		async: true,
		contentType: "application/json",
		dataType: 'json'
	}).done(function(data) {
		lightningData = data;
		var overlay = new IconsOverlay(map);
		var seconds = new Date().getTime() / 1000;
		lightningData.features.forEach(function (f) {

			var position = new google.maps.LatLng(f.geometry.coordinates[1], f.geometry.coordinates[0]);
			var timeElapsed = seconds - f.properties.timestamp;
			var fraction = timeElapsed/(LightningValue/255);
			var color = 'rgb(' + Math.round((255 - fraction)) + ', 0, ' + Math.round(0 + fraction) + ')';
			if(timeElapsed<15) { var stkWeight = 2; var stkColor = "#000"; } else { var stkWeight = 0.5; var stkColor = "#fff"; }
			
			if (f.properties.strokeType == 3) {
				//add circle icon
				var circleIconProperties = {
					id: f.properties.id,
					path: google.maps.SymbolPath.CIRCLE,
					strokeWeight: stkWeight,
					strokeColor: stkColor,
					fillColor: color,
					fillOpacity: 0.5,
					scale: 2 * scale
				}
				overlay.addIcon(position, circleIconProperties);
				//console.log(id);
			}
			else if (f.properties.strokeType == 0 && f.properties.mag > 0) {
				//add path icon
				var pathIconProperties = {
					id: f.properties.id,
					path: 'm5.219986,29.697235l23.795303,0l0,-25.117241l24.409424,0l0,25.117241l23.795288,0l0,25.765518l-23.795288,0l0,25.117264l-24.409424,0l0,-25.117264l-23.795303,0l0,-25.765518z',
					strokeWeight: stkWeight,
					strokeColor: stkColor,
					fillColor: color,
					fillOpacity: 0.5,
					scale: 0.12 * scale
				}
				overlay.addIcon(position,pathIconProperties);
			} else {
				var arrowIconProperties = {
					id: f.properties.id,
					path: google.maps.SymbolPath.BACKWARD_OPEN_ARROW,
					strokeColor: stkWeight,
					fillColor: color,
					fillOpacity: 0.5,
					scale: 1.5 * scale
				}
				overlay.addIcon(position, arrowIconProperties);       
			}     
		});
	});
	
	google.maps.event.addListener(map, 'zoom_changed', function() {
    	zoom = map.getZoom();
		if(zoom<8) { scale = 1.1; }
		if(zoom==8) { scale = 1.2; }
		if(zoom==9) { scale = 1.3; }
		if(zoom==10) { scale = 1.4; }
		if(zoom==11) { scale = 1.5; }
		if(zoom==12) { scale = 1.6; }
		if(zoom==13) { scale = 1.7; }
		if(zoom==14) { scale = 1.8; }
		if(zoom>14) { scale = 1.9; }
		console.log(zoom);
	
  	});
}

function deInitializeLightning() {
	map.data.setStyle({visible: false});
}

/*function lightning_callback(data) {
	for(i=0;i<data.features.length;i++) {
		for(x=0;x<lightningId.length;x++) {
			if(data.features[i].properties.id==lightningId[x]) {
				var exists = true;
			}
		}
		if(exists===true) {
			exists = false;
		} else {
			console.log(data.features[i].properties.id, "is new");
			map.data.addGeoJson(data.features[i]);
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



/*function changeLightningLength(value) {
	LightningValue = value;
	if(doLightning === true) {
		deInitializeLightning();
		initializeLightning();
	}
}*/
/*
function changeLightningFreq(value) {
	LightningUpdateFrequency = value;
}

function updateLightning() {
	if(doLightning===true) {
		removeOld();
		var mapbounds = map.getBounds();
		var viewportLatMin = mapbounds.getNorthEast().lat();
		var viewportLatMax = mapbounds.getSouthWest().lat();
		var viewportLonMin = mapbounds.getNorthEast().lng();
		var viewportLonMax = mapbounds.getSouthWest().lng();
		if(viewportLatMin > 0) { viewportLatMin = 0; }
		if(viewportLonMin < 0) { viewportLonMin = 180; }
		var viewportZoom = map.getZoom();
		console.log("http://sitdata.ewn.com.au/lightning/getjson.php?t=5&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom);
		map.data.loadGeoJson("http://sitdata.ewn.com.au/lightning/getjson.php?t=5&minLat=" + viewportLatMin + "&maxLat=" + viewportLatMax + "&minLon=" + viewportLonMin + "&maxLon=" + viewportLonMax + "&zoom=" + viewportZoom);
		var seconds = new Date().getTime() / 1000;
		map.data.setStyle({visible: true});
		map.data.setStyle(function(feature) {
			var timeElapsed = seconds - feature.getProperty('timestamp');
			var fraction = timeElapsed/(LightningValue/255);
			var color = 'rgb(' + Math.round((255 - fraction)) + ', 0, ' + Math.round(0 + fraction) + ')';
			if(timeElapsed<15) { var stkWeight = 2; var stkColor = "#000"; } else { var stkWeight = 0.5; var stkColor = "#fff"; }
			//console.log(stkWeight);
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
		});
	}
}*/