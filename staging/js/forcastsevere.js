var forecast_interval;
var forecast_intervalTime = 300000;
var forecast_filter_marker = [];
var forecast_minLat = 0;
var forecast_minLong = 1000;
var forecast_maxLat = 0;
var forecast_maxLong = 1000;
var forecast_latlongarray = [];
var show_forecast_asset_click = false;

function load_severeForecast_content(topics, record_type) {
	var loadSevereForecastData = { record_type: record_type, topic_type: topics };
	if (loadSevereForecastData == null) {
		loadSevereForecastData = '{}';
	}
	$.ajax({
		type: "POST",
		url: "ajax/get_forecastsvere.php",
		dataType: "json",
		data: loadSevereForecastData,
		success: function (response) {
			if (settings.severeWeather.status != false) {
				forecast_latlongarray.length = 0;
				for (k = 0; k < response.data.length; k++) {
					//console.log('length' + k + response.data[k].corodinates.length);
					var url = response.data[k].AlertFullURL;
					if (response.data[k].corodinates.length !== 0) {
						var coordinates_json = response.data[k].corodinates;
						forecast_draw_map(coordinates_json, response.data[k].color, response.data[k].AlertFullURL, parseInt(response.data[k].id), response.data[k].CreatedDate);
					}
				}
				if (show_forecast_asset_click) {
					forecast_severe_show_asset()
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			loadSevereForecastError(jqXHR, textStatus, errorThrown);
		}
	});
}

function loadSevereForecastError(jqXHR, textStatus, errorThrown) {
	console.error(textStatus + "  " + (errorThrown ? errorThrown : ''));
	showMessage(textStatus + "  " + (errorThrown ? errorThrown : ''));
}

function remove_forecastevere_warning() {
	if (forecastwarningobjectMarkersArray) {
		for (var i = 0; i < forecastwarningobjectMarkersArray.length; i++) {
			forecastwarningobjectMarkersArray[i].setMap(null);
		}
		forecastwarningobjectMarkersArray.length = 0;
	}
}

// remove warning legend
function remove_Forecast_legend() {
	map.controls[google.maps.ControlPosition.TOP_LEFT].removeAt(1);
}

function update_forecast_severe() {
	if ($(".forecastsevere").attr("checked")) {
		remove_forecastevere_warning();
		var forecast_weather_options = [];
		forecast_weather_options = $('input:checkbox[name="forecast_warning_option[]"]').filter(':checked').map(function () {
			return this.value;
		}).get();
		if (forecast_weather_options.length != 0) {
			load_severeForecast_content(forecast_weather_options, $('#forecast_severe_weather_option').val());
		}

	}
}

function remove_forecast_filter_marker() {
	if (forecast_filter_marker.length > 0) {
		for (var f = 0; f < forecast_filter_marker.length; f++) {
			forecast_filter_marker[f].setMap(null);
		}
	}
	show_forecast_asset_click = false;
}

function forecast_severe_show_asset() {
	remove_forecast_filter_marker();
	hide_layer_markers();
	show_forecast_asset_click = true;
	position_url = "ajax/get_newpoints.php";
	//console.log('polygon length' + forecast_latlongarray.length);
	forecast_filter_marker.length = 0;
	for (l = 0 ; l < forecast_latlongarray.length; l++) {
		var mydata = 'selected_layer_value_current=' + getSelectBoxValue() + '&polygon_number=' + l + '&minLat=' + forecast_latlongarray[l].minLat + '&minLong=' + forecast_latlongarray[l].minLong + '&maxLat=' + forecast_latlongarray[l].maxLat + '&maxLong=' + forecast_latlongarray[l].maxLong;
		if (mydata == null) {
			mydata = '{}';
		}
		$.ajax
		({
			type: "POST",
			url: position_url,
			dataType: "json",
			data: mydata,
			async: false,
			success: function (response) {
				if (response.data.length > 0 && settings.severeWeather.status != false) {
					//console.log(response.polygon_number);
					for (i = 0; i < response.data.length; i++) {
						var myLatlng = new google.maps.LatLng(response.data[i].latitude, response.data[i].longitude);
						filter_marker = new google.maps.Marker
						({
							position: myLatlng,
							draggable: false,
							icon: 'images/layer_markers/' + response.data[i].placemarker_icon
						});
						google.maps.event.addListener(filter_marker, 'click', (function (marker, i) {
							return function () {
								var mydesc = response.data[i].placemarker_description;
								infowindow.setContent(mydesc);
								infowindow.open(map, marker);
							}
						})(filter_marker, i));
						if (google.maps.geometry.poly.containsLocation(myLatlng, forecastwarningobjectMarkersArray[response.polygon_number]) == true) {
							filter_marker.setMap(map);
							forecast_filter_marker.push(filter_marker);
						}
					}
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				loadSevereForecastError(jqXHR, textStatus, errorThrown);
			}
		});
	}
}


function forecast_clear_filter_markers() {
	if (forecast_filter_marker) {
		for (var f = 0; f < forecast_filter_marker.length; f++) {
			forecast_filter_marker[f].setMap(null);
		}
	}
	forecast_filter_marker.length = 0;
	show_forecast_asset_click = false;
	if (!show_asset_click) {
		redraw_layer();
	}

}

function forecast_download_csv_report() {
	var myhtml1 = '';
	var display_polygon_marker_array = [];
	polygon_complete_lat_longs = [];
	var complete_lat_longs = [];
	var myLatlngnew;
	var current_image_name_path = "images/layer_markers/";
	position_url = "ajax/display_newpoints.php";
	for (l = 0 ; l < forecast_latlongarray.length; l++) {
		var mydata = 'selected_layer_value_current=' + getSelectBoxValue() + '&polygonPrimaryKey=' + forecast_latlongarray[l].polygonPrimaryKey + '&polygon_number=' + l + '&minLat=' + forecast_latlongarray[l].minLat + '&minLong=' + forecast_latlongarray[l].minLong + '&maxLat=' + forecast_latlongarray[l].maxLat + '&maxLong=' + forecast_latlongarray[l].maxLong;
		if (mydata == null) {
			mydata = '{}';
		}
		$.ajax
        ({
        	type: "POST",
        	url: position_url,
        	dataType: "json",
        	data: mydata,
        	async: false,
        	success: function (response) {
        		var getSelectedValue = getSelectBoxText();
        		if (response.data.length > 0 && settings.severeWeather.status != false) {
        			var polygon_layers = [];
        			var flag = false;
        			for (i = 0; i < response.data.length; i++) {
        				var myLatlng = new google.maps.LatLng(response.data[i].latitude, response.data[i].longitude);
        				var present_polygon = forecastwarningobjectMarkersArray[response.polygon_number];
        				if (google.maps.geometry.poly.containsLocation(myLatlng, present_polygon) == true) {
        					index = indexOfPolygons.call(display_polygon_marker_array, response.polygon_number);
        					var ele_id = response.polygon_number + response.data[i].layer_type.split(' ').join('_');
        					var newElements = {};
        					newElements['polygon_subject'] = response.polygon_subject;
        					newElements['polygon_number'] = response.polygon_number;
        					newElements['placemarker_name'] = response.data[i].placemarker_name;
        					newElements['placemarker_icon'] = response.data[i].placemarker_icon;
        					newElements['latitude'] = response.data[i].latitude;
        					newElements['longitude'] = response.data[i].longitude;
        					newElements['layer_type'] = response.data[i].layer_type;
        					newElements['created_date'] = forecast_latlongarray[l].created_date;
        					polygon_complete_lat_longs.push(newElements);
        				}
        			}
        		}
        	},
        	error: function (jqXHR, textStatus, errorThrown) {
        		loadSevereForecastError(jqXHR, textStatus, errorThrown);
        	}
        });
		if (l == forecastwarningobjectMarkersArray.length - 1) {
			exportDataToCSVDirect();
		}
	}
}
