var observation_marker;
var cluster_markers = [];
var styles = [[{
	url: 'images/observation/cluster-background.png',
	height: 34,
	width: 35,
	lineheight: 35,
}]];
var markerClusterer = null;
var map = null;

function clearClusters() {
	markerClusterer.clearMarkers();
}

function load_observation_content(type) {
	var loadObservationContentData = { record_type: type };
	if (loadObservationContentData == null) {
		loadObservationContentData = '{}';
	}
	$.ajax({
		type: "POST",
		url: "ajax/get_observation.php",
		dataType: "json",
		data: loadObservationContentData,
		success: function (response) {
			if (settings.observations.status == true && settings.forecasts.status == false && settings.observations.type == type) {
				var observation_length = response.data.length - 1;
				if (response.data.length > 0) {
					for (i = 0; i < response.data.length; i++) {
						if (settings.observations.status != false && settings.observations.type == type) {
							var myLatlng = new google.maps.LatLng(response.data[i].lat, response.data[i].lng);
							var className = 'cityshow';
							var zoomLevel = map.getZoom();
							if (zoomLevel < 8) {
								className = 'cityshow';
							}
							var temp = parseInt(response.data[i].temp);
							var markerIcon = 'none.png';
							//Even more efficient way to do it...
							/*
								markerIcon = temp + '.png';
								//Just need icons from the range -x to y (-x being lowest temp you want to go and, y being highest temp you want to go).
								//Only problem is you have to load --x + y amount of images...
								//Also need to round incase of double values.
						
							*/
							if (temp <= -20) {
								markerIcon = 'min-20.png';
							}
							else if (temp <= -10) {
								markerIcon = 'min-10.png';
							}
							else if (temp <= 0) {
								markerIcon = '0.png';
							}
							else if (temp <= 10) {
								markerIcon = '10.png';
							}
							else if (temp <= 20) {
								markerIcon = '20.png';
							}
							else if (temp <= 30) {
								markerIcon = '30.png';
							}
							else if (temp > 30) {
								markerIcon = 'pls-40.png'
							}
							if (settings.observations.status != false && settings.observations.type == type) {
								observation_marker = new MarkerWithLabel({
									position: myLatlng,
									draggable: false,
									map: map,
									labelContent: '<div class=mylabels>' + '<div align=left style="font-size:14px;">' + response.data[i].temp + '</div><div align=left class="city_label ' + className + '" style=width:auto;></div></div>',
									labelAnchor: new google.maps.Point(-10, 20), // -10,20 ---- -22,0
									labelClass: "border_labels", // the CSS class for the label
									labelStyle: { opacity: 1 },
									icon: 'images/observation/' + markerIcon,
									title: response.data[i].city_name + ' observation'
								});
								// info window
								google.maps.event.addListener(observation_marker, 'click', (function (marker, i) {
									return function () {
										if (settings.observations.status != false && settings.observations.type == type) {
											var contentString = '<div id="markerinfo" style="width:320px;"><div style="padding:5px 2px;">' + response.data[i].city_name + '</div>' +
														   '<table cellpadding="0" cellspacing="0" class="forecasts_table">' +
														   '<tr>' +
														   '<td style="border-radius:6px 0 0 0;" class="head">Date/Time ' + response.data[i].timezone + '</td>' +
														   '<td style="border-radius:0 6px 0 0">' + response.data[i].date_time_cst + '</td>' +
														   '</tr>' +
														   '<tr>' +
														   '<td class="head">Temp&deg;C</td>' +
														   '<td>' + response.data[i].temp + '</td>' +
														   '</tr>' +
														   '<tr>' +
														   '<td class="head">Dew Point&deg;C</td>' +
														   '<td>' + response.data[i].dew_ponit + '</td>' +
														   '</tr>' +
														   '<tr>' +
														   '<td class="head">Rel Hum %</td>' +
														   '<td>' + response.data[i].rel_hum + '</td>' +
														   '</tr>' +
														   '<tr>' +
														   '<td class="head">Wind Direction</td>' +
														   '<td>' + response.data[i].wind_dir + '</td>' +
														   '</tr>' +
														   '<tr>' +
														   '<td class="head">Wind Speed (Km/h)</td>' +
														   '<td>' + response.data[i].wind_speed_km_h + '</td>' +
														   '</tr>' +
														   '<tr>' +
														   '<td class="head">Rain since 9 am (mm)</td>' +
														   '<td>' + response.data[i].rain_since_9am_mm + '</td>' +
														   '</tr>' +
														   '<tr>' +
														   '<td class="head">Low Tmp&deg;C time</td>' +
														   '<td>' + response.data[i].low_Tmp + '</td>' +
														   '</tr>' +
														   '<tr>' +
														   '<td class="head">High Tmp&deg;C time</td>' +
														   '<td>' + response.data[i].high_tmp + '</td>' +
														   '</tr>' +
														   '<tbody>' +
														   '</tbody>' +
													   '</table></div>';
											infowindow.setContent(contentString);
											infowindow.open(map, marker);
										}
									}
								})(observation_marker, i));
								if (settings.observations.status != false && settings.observations.type == type) {
									mapMarkersArray.push(observation_marker);
									cluster_markers.push(observation_marker);
									observation_marker.setMap(map);
								}
							}
						}
					}
					var zoom = 21;
					var size = -1;
					var style = 0;
					zoom = zoom === -1 ? null : zoom;
					size = size === -1 ? null : size;
					style = style === -1 ? null : style;
					markerClusterer = new MarkerClusterer(map, cluster_markers, {
						maxZoom: zoom,
						gridSize: size,
						styles: styles[style]
					});
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			loadObservationContentError(jqXHR, textStatus, errorThrown);
		}
	});
}

function loadObservationContentError(jqXHR, textStatus, errorThrown) {
	console.error(textStatus + "  " + (errorThrown ? errorThrown : ''));
	showMessage(textStatus + "  " + (errorThrown ? errorThrown : ''));
}

function deleteMarkersFromMap() {
	if (mapMarkersArray) {
		for (var i = 0; i < mapMarkersArray.length; i++) {
			mapMarkersArray[i].setMap(null);
			cluster_markers[i].setMap(null);
			clearClusters();
		}
		mapMarkersArray.length = 0;
		cluster_markers.length = 0;
	}
}

//Never called
/*
function changeObservation(observationOption) {

}


function changeCityLabel() {
	var zoomLevel = map.getZoom();
	if (zoomLevel >= 8) {
		$('.city_label').css({ 'width': "auto" });
	}
	else {
		$('.city_label').removeClass('cityhide');
		$('.city_label').css({ 'width': "auto" });
	}
}


function hide_show_city_label_typeid(type_id) {
	var zoomLevel = map.getZoom();
	if (zoomLevel >= 8) {
		$('.city_label').removeClass('cityhide');
		$('.city_label').css({ 'width': "auto" });
	}
}


//functions for refresh button - manual refresh
//same as capital and cities
//load all cities observations data
function refresh_observation_allCities() {
	var zoomLevel = map.getZoom();
	if (zoomLevel < 8) {
		deleteMarkersFromMap();
		clearInterval(observation_timeout);
		if ($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked")) {
			load_observation_content(3);
		}
		observation_timeout = setInterval(function () {
			deleteMarkersFromMap();
			if ($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked")) {
				load_observation_content(3);
			}
		}, radartimeinterval);
	}
	else {
		deleteMarkersFromMap();
		clearInterval(observation_timeout);
		if ($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked")) {
			load_observation_content(2);
		}
		observation_timeout = setInterval(function () {
			deleteMarkersFromMap();
			if ($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked")) {
				load_observation_content(2);
			}
		}, radartimeinterval);
	}
}

//load capital observations data only
function refresh_observation_Capitals_only() {
	var zoomLevel = map.getZoom();
	if (zoomLevel < 8) {
		deleteMarkersFromMap();
		clearInterval(observation_timeout);
		if ($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked")) {
			load_observation_content(4);
		}
		observation_timeout = setInterval(function () {
			deleteMarkersFromMap();
			load_observation_content(4);
		}, radartimeinterval);
	}
	else {
		deleteMarkersFromMap();
		clearInterval(observation_timeout);
		if ($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked")) {
			load_observation_content(1);
		}
		observation_timeout = setInterval(function () {
			deleteMarkersFromMap();
			if ($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked")) {
				load_observation_content(1);
			}
		}, radartimeinterval);
	}
}
*/