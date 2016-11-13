var forecast_marker;

// JIRA SR-77 ADMIN CHANGES
function load_forecast_markers() {
	hide_forecast_markers();
	$('#loader_observation').show();
	$.ajax({
		type: "POST",
		url: "ajax/get_forecast.php",
		dataType: "json",
		data: { LOGIN_USER_NAME: LOGIN_USER_NAME },
		success: function (response) {
			if (settings.forecasts.status == true && settings.observations.status == false) {
				var forecast_length = response.marker_data.length - 1;
				if (response.marker_data.length > 0) {
					for (i = 0; i < response.marker_data.length; i++) {
						var state_title = response.marker_data[i].state_name;
						var myLatlng = new google.maps.LatLng(response.marker_data[i].latitude, response.marker_data[i].longitude);
						//console.log(response.marker_data[i].icon);
						var img = "images/forecast/transparent/" + response.marker_data[i].icon_name;
						var forecast_marker = new google.maps.Marker
						({
							position: myLatlng,
							title: response.marker_data[i].state_name,
							icon: img,
							zIndex: 100
						});
						google.maps.event.addListener(forecast_marker, 'click', (function (marker, i) {
							return function () {
								var finalHTML = '<div align="left" style="overflow:hidden;width:640px;">';
								finalHTML = finalHTML + '<div style="padding:5px 2px;"> Forecast For ' + response.marker_data[i].state_name;
								finalHTML = finalHTML + '</div>';
								finalHTML = finalHTML + '<table class=forecasts_table cellpadding=10 width=100%><thead><tr><th colspan=2>';
								finalHTML = finalHTML + 'Date</th>';
								finalHTML = finalHTML + '<th>';
								finalHTML = finalHTML + 'Min</th>';
								finalHTML = finalHTML + '<th>';
								finalHTML = finalHTML + 'Max</th>';
								finalHTML = finalHTML + '<th class=nowrap>';
								finalHTML = finalHTML + 'Chance of Rain</th>';
								finalHTML = finalHTML + '<th class=nowrap>';
								finalHTML = finalHTML + 'Possible rainfall</th>';
								finalHTML = finalHTML + '<th>';
								finalHTML = finalHTML + 'Fire Danger</th>';
								finalHTML = finalHTML + '</tr></thead>';
								for (j = 0; j < response.marker_info[response.marker_data[i].state_name].length; j++) {
									finalHTML = finalHTML + '<tr>';
									finalHTML = finalHTML + '<td class=nowrap>';
									finalHTML = finalHTML + response.marker_info[response.marker_data[i].state_name][j].forecast_date;
									finalHTML = finalHTML + '</td>';
									finalHTML = finalHTML + '<td style=border-left:0>';
									finalHTML = finalHTML + '<img onerror="" src=' + response.marker_info[response.marker_data[i].state_name][j].forecast_image + '>';
									finalHTML = finalHTML + '</td>';
									finalHTML = finalHTML + '<td class=min nowrap>';
									finalHTML = finalHTML + response.marker_info[response.marker_data[i].state_name][j].min;
									finalHTML = finalHTML + '</td>';
									finalHTML = finalHTML + '<td class=min nowrap>';
									finalHTML = finalHTML + response.marker_info[response.marker_data[i].state_name][j].max;
									finalHTML = finalHTML + '</td>';
									finalHTML = finalHTML + '<td class=min nowrap>';
									finalHTML = finalHTML + response.marker_info[response.marker_data[i].state_name][j].chance_of_rain;
									finalHTML = finalHTML + '</td>';
									finalHTML = finalHTML + '<td class=min nowrap>';
									finalHTML = finalHTML + response.marker_info[response.marker_data[i].state_name][j].possible_rainfall;
									finalHTML = finalHTML + '</td>';
									finalHTML = finalHTML + '<td align=left>';
									finalHTML = finalHTML + response.marker_info[response.marker_data[i].state_name][j].fire_danger;
									finalHTML = finalHTML + '</td>';
									finalHTML = finalHTML + '</tr>';
								}
								finalHTML = finalHTML + '</table>';
								infowindow.setContent(finalHTML);
								infowindow.open(map, marker);
							}
						})(forecast_marker, i));
						forecastmapMarkersArray.push(forecast_marker);
						forecast_marker.setMap(map);
					}
				}
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			loadForecastMarkersError(jqXHR, textStatus, errorThrown)
		}
	});
}

function loadForecastMarkersError(jqXHR, textStatus, errorThrown) {
	console.error(textStatus + "  " + (errorThrown ? errorThrown : ''));
	showMessage(textStatus + "  " + (errorThrown ? errorThrown : ''));
}

function hide_forecast_markers() {
	if (forecastmapMarkersArray) {
		for (var i = 0; i < forecastmapMarkersArray.length; i++) {
			forecastmapMarkersArray[i].setMap(null);
		}
		forecastmapMarkersArray.length = 0;
	}
}