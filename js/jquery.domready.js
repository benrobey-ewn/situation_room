var d = false;
var sessionPingTime = 120000;
if (typeof String.prototype.trim !== 'function') {
	String.prototype.trim = function () {
		return this.replace(/^\s+|\s+$/g, '');
	}
}

if (!window.console) console = {};
console.log = console.log || function () { };
console.warn = console.warn || function () { };
console.error = console.error || function () { };
console.info = console.info || function () { };

var dragging = false;

$(document).ready(function () {
	domReadyLoadMain();
});

function domReadyLoadMain() {
	domReadyDateCustomConvert();
	domReadyWindowResize();
	domReadyRadar();
	domReadyLightning();
	domReadyObservations();
	domReadyRainfallGauges();
	domReadyRiverGauges();
	domReadyForecasts();
	domReadyWarnings();
	domReadyForecastSevereWeather();
	// Needs to be left in 
	domReadySatellite();
	domReadyMapOptions();
	domReadyMapDataLayers();
	domReadyNbnDataLayers();
	//Needs to be left in.
	domReadyForecastModel();
	//domReadyDateTimePicker();
	//domReadyMapTriggerResize();
	domReadyContact();
	domReadyUpdateUserSession();
	domReadyLegends();
	domReadySliders();
	domReadySetSituationRoom();
	domReadyMenuCollapse();

	$('#nbn_description_polygon').on('keyup', function() {

		if($(this).val().length > 5000) {
			$('.nbn_description_polygon_alert').html('Warning: Total message length can not exceed 5000 characters.');
			$('#save_nbn_alert_details').prop('disabled', 'disabled');
		}
		else if($(this).val().length > 160) {
			$('.nbn_description_polygon_alert').html('SMS content limit of 160 characters has been reached. Text input after this point will be visible by email only.');
			$('#save_nbn_alert_details').prop('disabled', false);
		}
		else {
			$('.nbn_description_polygon_alert').html('');
			$('#save_nbn_alert_details').prop('disabled', false);
		}
	});
}

//Date for radar
function domReadyDateCustomConvert() {
	Date.fromISO = function (s) {
		var day, tz,
		rx = /^(\d{4}\-\d\d\-\d\d([tT ][\d:\.]*)?)([zZ]|([+\-])(\d\d):(\d\d))?$/,
		p = rx.exec(s) || [];
		if (p[1]) {
			day = p[1].split(/\D/);
			for (var i = 0, L = day.length; i < L; i++) {
				day[i] = parseInt(day[i], 10) || 0;
			};
			day[1] -= 1;
			day = new Date(Date.UTC.apply(Date, day));
			if (!day.getDate()) return NaN;
			if (p[5]) {
				tz = (parseInt(p[5], 10) * 60);
				if (p[6]) tz += parseInt(p[6], 10);
				if (p[4] == '+') tz *= -1;
				if (tz) day.setUTCMinutes(day.getUTCMinutes() + tz);
			}
			return day;
		}
		return NaN;
	}
}

function domReadyWindowResize() {
	var screen_width = $(window).width();
	var resize_map_width = parseInt(screen_width) - parseInt(385);
	screen_resize_elements($(window).width());

	$(window).resize(function () {
		screen_width = $(window).width();
		if ($('#Popcontainer').css('display') == 'none') {
			resize_map_width = parseInt(screen_width);
		}
		else {
			var height = $(window.top).height();
			$('#Popcontainer').height(height - 20);
			$('#Popcontainer').css({ 'overflow-y': "scroll" });
			resize_map_width = parseInt(screen_width) - parseInt(385);
		}
		$('#map-canvas').css({ "width": resize_map_width + 'px' });
		screen_resize_elements($(window).width());
		google.maps.event.trigger(map, "resize");
		mapContainerLoadSizeWidth = $('#map-container').width();
		mapContainerLoadSizeHeight = $('#map-container').height();
	});
}

function domReadyRadar() {
	$("#radar_display_images").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			EWNMapsRadarShow();
			settings.radar.status = true;
			//Show radar bar
			$('.radar-info').show();
			$('.radar-bar-image').show();
			$('.wx_radar_sites').trigger("change");
		}
		else {
			EWNMapsRadarHide();
			settings.radar.status = false;
			$('.radar-info').hide();
			if ($('.wx_radar_sites').is(":checked")) {
				rio2.hideRadarMarkers();
			}
		}
		settingsUpdate();
	});

	$('#optRadar').change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			//If radar isn't checked, turn it on!
			if (!$("#radar_display_images").is(':checked')) {
				$("#radar_display_images").attr("checked", true);
				settings.radar.status = true;
				$("#radar_display_images").trigger("change");
			}
			settings.radar.loop = true;
			EWNMapsRadarPlayStart();
			
		}
		else {
			settings.radar.loop = false;
			EWNMapsRadarPlayStop();
		}
		settingsUpdate();
	});

	$('#pause').click(function () {
		EWNMapsRadarPlayStop();
	});

	$('#play').click(function () {
		EWNMapsRadarPlayStart();
	});

	$('#radar_opacity').on('change', function () {
		var e = $(this);
		RadarOpacityChange(e);
	});

	$("#radar_opacity").bind("keyup", function () {
		var e = $(this);
		RadarOpacityChange(e);
	});

	function RadarOpacityChange(e) {
		settings.radar.opacity = e.value;
		radar_opacity = e.value;
		settingsUpdate();
		EWNMapsRadarOpacityUpdate();
	}

	$(".wx_radar_sites").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			if (!$('#radar_display_images').is(":checked")) {
				$('#radar_display_images').attr("checked", true);
				$('#radar_display_images').trigger("change");
			}
			settings.radar.weatherRadarSites = true;
			rio2.showRadarMarkers();
		}
		else {
			settings.radar.weatherRadarSites = false;
			rio2.hideRadarMarkers();
		}
		settingsUpdate();
	});
}

function domReadyLightning() {
	$('#checkbox-1-4').change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.lightning.status = true;
		}
		else {
			settings.lightning.status = false;
		}
		settingsUpdate();
	});
}

function domReadyObservations() {
	$(".observation_checkbox").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			if ($(".observation_option3").is(':checked')) {
				settings.observations.status = true;
				observationChange(3);
			}
			else if ($('.observation_option2').is(':checked')) {
				settings.observations.status = true;
				observationChange(2);
			}
			//Default tick
			else {
				$(".observation_option1").attr("checked", true);
				settings.observations.status = true;
				observationChange(1);
			}
		}
		else {
			observationOff();
		}
	});

	$(".observation_option1").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			observationChange(1);
		}
		else {
			observationOff();
		}
	});

	$(".observation_option2").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			observationChange(2);
		}
		else {
			observationOff();
		}
	});

	$(".observation_option3").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			observationChange(3);
		}
		else {
			observationOff();
		}
	});

	function observationChange(observationOption) {
		if (!$(".observation_checkbox").is(":checked")) {
			settings.observations.status = true;
			$(".observation_checkbox").attr("checked", true);
		}
		settings.observations.type = observationOption;
		observationForecastChange();
		for (var i = 1; i <= 3; i++) {
			if (observationOption != i && $('.observation_option'+ i).is(":checked")) {
				$('.observation_option' + i).attr("checked", false);
			}
		}
		//Calls to observation.js
		var zoomLevel = map.getZoom();
		deleteMarkersFromMap();
		clearInterval(observation_timeout);
		settingsUpdate();
		load_observation_content(observationOption);
		observation_timeout = setInterval(function () {
			deleteMarkersFromMap();
			load_observation_content(observationOption);
		}, radartimeinterval);
	}

	function observationForecastChange() {
		if ($('#radar_forecasts_information').is(':checked')) {
			$('#radar_forecasts_information').attr("checked", false);
			$("#radar_forecasts_information").trigger("change");
			settings.forecasts.status = false;
		}
	}

	function observationOff() {
		settings.observations.status = false;
		$('.observation_checkbox').attr("checked", false);
		$('.observation_option1').attr("checked", false);
		$('.observation_option2').attr("checked", false);
		$('.observation_option3').attr("checked", false);
		deleteMarkersFromMap();
		clearInterval(observation_timeout);
		settingsUpdate();
	}
}

function domReadyRainfallGauges() {
	$(".rainfall").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.rainfallGauges.status = true;
			var rainfallCheckedBoxes = [];
			rainfallCheckedBoxes = $('.rainfall_checkboxes:checked').map(function () {
				return this.value;
			}).get()
			if (rainfallCheckedBoxes.length == 0) {
				$('.rainfall_checkboxes').each(function () {
					$(this).attr("checked", true);
				});
			}
			var rainfallCheckedBoxes = [];
			rainfallCheckedBoxes = $('.rainfall_checkboxes:checked').map(function () {
				return this.value;
			}).get()
			settings.rainfallGauges.options = rainfallCheckedBoxes;
			rainfallChangeAll();
			legendMainShow($('#rainfall_gauges_codes_div'));
			clearInterval(rainfall_timeout);
			rainfall_timeout = setInterval(function () {
				rainfallChangeAll();
			}, radartimeinterval);
		}
		else {
			settings.rainfallGauges.status = false;
			remove_all_rainfall();
			legendMainHide($('#rainfall_gauges_codes_div'));
			clearInterval(rainfall_timeout);
		}
		settingsUpdate();
	});

	$('#rainfall_types').on('change', function () {
		settings.rainfallGauges.type = $("#rainfall_types").val();
		remove_all_rainfall();
		$(".rainfall").attr("checked", true);
		$(".rainfall").trigger("change");
	});

	$('.rainfall_checkboxes').change(function () {
		var e = $(this);
		var rainfallCheckedBoxes = [];
		rainfallCheckedBoxes = $('.rainfall_checkboxes:checked').map(function () {
			return this.value;
		}).get();
		settings.rainfallGauges.options = rainfallCheckedBoxes;
		if (!rainfallCheckedBoxes.length <= 0 || $(".rainfall").is(":checked")) {
			if (rainfallCheckedBoxes.length >= 1 && !$(".rainfall").is(':checked') && e.attr("checked")) {
				$(".rainfall").attr("checked", true);
				settings.rainfallGauges.status = true;
				$(".rainfall").trigger("change");
			}
			else if (rainfallCheckedBoxes.length <= 0) {
				$(".rainfall").attr("checked", false);
				settings.rainfallGauges.status = false;
				rainfallChangeSingleCheckBox(e);
			}
			else {
				rainfallChangeSingleCheckBox(e);
			}
		}
		settingsUpdate();
	});
}

function domReadyRiverGauges() {
	$(".river-guage").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			var riverCheckedBoxes = [];
			riverCheckedBoxes = $('.river_guage_checkboxes:checked').map(function () {
				return this.value;
			}).get();
			if (riverCheckedBoxes.length == 0) {
				$('.river_guage_checkboxes').each(function () {
					$(this).attr("checked", true);
				});
			}
			var riverCheckedBoxes = [];
			riverCheckedBoxes = $('.river_guage_checkboxes:checked').map(function () {
				return this.value;
			}).get();
			settings.riverGauges.options = riverCheckedBoxes;
			settings.riverGauges.status = true;
			show_river_guages(riverCheckedBoxes);
			clearInterval(river_guage_timeout);
			river_guage_timeout = setInterval(function () {
				update_river_guages();
			}, radartimeinterval);
			legendMainShow($('#river_gauges_codes_div'));
		}
		else {
			settings.riverGauges.status = false;
			remove_all_river_guages();
			clearInterval(river_guage_timeout);
			legendMainHide($('#river_gauges_codes_div'));
		}
		settingsUpdate();
	});

	//Gauge is spelt wrong.
	$('.river_guage_checkboxes').change(function () {
		var e = $(this);
		var riverCheckedBoxes = [];
		riverCheckedBoxes = $('.river_guage_checkboxes":checked').map(function () {
			return this.value;
		}).get();
		settings.riverGauges.options = riverCheckedBoxes;
		if (!riverCheckedBoxes.length <= 0 || $(".river-guage").is(":checked")) {
			if (riverCheckedBoxes.length >= 1 && !$(".river-guage").is(':checked') && e.attr("checked")) {
				$(".river-guage").attr("checked", true);
				settings.riverGauges.status = true;
				$(".river-guage").trigger("change");
			}
			else if (riverCheckedBoxes.length <= 0) {
				$(".river-guage").attr("checked", false);
				settings.riverGauges.status = false;
				remove_all_river_guages();
			}
			else {
				settings.riverGauges.status = true;
				toggleRiverGuageKML(e);
			}
		}
		else {
			remove_all_river_guages();
		}
		settingsUpdate();
	});
}

function domReadyForecasts() {
	$("#radar_forecasts_information").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.forecasts.status = true;
			if ($(".observation_checkbox").is(':checked')) {
				$(".observation_checkbox").attr("checked", false);
				$(".observation_checkbox").trigger("change");
				settings.observations.status = false;
			}
			load_forecast_markers();
		}
		else {
			settings.forecasts.status = false;
			$('#radar_forecasts_information').attr("checked", false);
			hide_forecast_markers();
		}
		settingsUpdate();
	});
}

function domReadyWarnings() {
	$(".weather").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.warnings.status = true;
			var days = $('#warning_days').val();
			settings.warnings.warningLast = days;
			remove_warning();
			$('input[name="warning_option[]"]').each(function () {
				$(this).attr("checked", true);
			});
			weather_options.length = 0;
			if (weather_options.length == 0) {
				$('.warning_all').attr('checked', true);
				weather_options.push('all');
				settings.warnings.type = 'all';
			}
			var $radios = $('input:radio[name=warning_type]');
			$radios.filter('[value=2]').prop('checked', true);
			settings.warnings.typeOption = 'expired';
			load_warning_content(2, weather_options, days);
			$("#warningreportoption1").show();
			$('#warningreportoption2').show();
			var layer_value = $('#layers').val();
			if (layer_value == '' && layer_value == 4) {
				$('.assets_report').prop('disabled', true);
				$('.report_disabled').show();
			}
			legendMainShow($('#warning_codes_div'));
		}
		else {
			settings.warnings.status = false;
			remove_warning();
			$("#warningreportoption1").hide();
			$('#warningreportoption2').hide();
			var layer_value = $('#layers').val();
			if (layer_value !== '' && layer_value !== 4 && layer_value !== 12) {
				settings.mapOptions.option = layer_value;
				clear_range_markers();
				if (layer_value == 1) {
					showHideRTLayerData(map);
					RT_NSW.setMap(map);
					RT_NT.setMap(map);
					RT_QLD.setMap(map);
					RT_SA.setMap(map);
					RT_TAS.setMap(map);
					RT_VIS.setMap(map);
					RT_WA.setMap(map);
				}
			}
			$('#cyclone_option').hide();
			clearInterval(cyclone_interval);
			hide_cyclone();
			legendMainHide($('#warning_codes_div'));
		}
		settingsUpdate();
	});

	var $checkes = $('input:checkbox[name="warning_option[]"]').change(function () {
		var e = $(this);
		if ($(".weather").is(':checked')) {
			remove_warning();
			if (this.value == 'all' && e.attr("checked")) {
				settings.warnings.type = 'all';
				weather_options.push('all');
				$('input[name="warning_option[]"]').each(function () {
					$(this).attr('checked', true);
				});
			}
			else {
				if (this.value == 'all') {
					$('input[name="warning_option[]"]').each(function () {
						$(this).attr('checked', false);
					});
					weather_options.length = 0;
				}
				else {
					var $radios = $('input:checkbox[name="warning_option[]"]');
					$radios.filter('[value=all]').prop('checked', false);
					weather_options = $checkes.filter(':checked').map(function () {
						return this.value;
					}).get();
				}
			}
			if (weather_options.length == 0) {
				settings.warnings.type = '';
			}
			else {
				if ($(".weather").is(':checked')) {
					$('input[name="warning_type"]:checked').each(function () {
						var warning_type = this.value;
						if (warning_type == '1') {
							settings.warnings.typeOption = "current";
							load_warning_content(1, weather_options, '');
						}
						else if (warning_type == '2') {
							var days = $('#warning_days').val();
							settings.warnings.typeOption = "expired";
							settings.warnings.warningLast = days;
							load_warning_content(2, weather_options, days);
						}
						settings.warnings.typeOption = weather_options;
					});
				}
			}
		}
		settingsUpdate();
		setTimeout(function () {
			if ($('input[name="warning_option[]"].warning_checkboxes:checked').length == $('input[name="warning_option[]"].warning_checkboxes').length) {
				$(".warning_all").attr("checked", true);
			}
		}, 20);
	});

	$(".warning_type").change(function () {
		var e = $(this);
		if ($(".weather").is(':checked')) {
			var warning_type = (this).value;
			remove_warning();
			weather_options = $checkes.filter(':checked').map(function () {
				return this.value;
			}).get();
			if (weather_options.length == 0) {
				$('.warning_all').attr('checked', true);
				weather_options.push('all');
				settings.warnings.typeOption = 'all';
				$('input[name="warning_option[]"]').each(function () {
					$(this).attr('checked', true);
				});
			}
			//Current warnings == 1
			if (warning_type == '1') {
				$('.cyclone').attr("checked", true);
				$('#cyclone_option').show();
				toggle_cyclone();
				settings.warnings.typeOption = 'current';
				load_warning_content(1, weather_options, '');
			}
				//Warnings Last == 2
				//#warning_days == dropd down box warnings
			else if (warning_type == '2') {
				$('#cyclone_option').hide();
				clearInterval(cyclone_interval);
				hide_cyclone();

				var days = $('#warning_days').val();
				settings.warnings.typeOption = 'expired';
				settings.warnings.typeOption = days;
				load_warning_content(2, weather_options, days);
			}

		}
		settingsUpdate();
	});

	$('#warning_days').on('change', function () {
		var e = $(this);
		if ($(".weather").is(':checked')) {
			var $radios = $('input:radio[name=warning_type]');
			weather_options = $('input:checkbox[name="warning_option[]"]').filter(':checked').map(function () {
				return this.value;
			}).get();
			if (weather_options.length != 0) {
				remove_warning();
				$("#warning_last").attr("checked", true);
				settings.warnings.warningLast = this.value;
				load_warning_content(2, weather_options, this.value);
			}
		}
		settingsUpdate();
	});
}

function domReadyForecastSevereWeather() {
	$(".forecastsevere").change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.severeWeather.status = true;
			remove_forecastevere_warning();
			$('input[name="forecast_warning_option[]"]').each(function () {
				$(this).attr("checked", true);
			});
			var $radios = $('input:checkbox[name="forecast_warning_option[]"]');
			forecast_weather_options = $radios.filter(':checked').map(function () {
				return this.value;
			}).get();
			if (forecast_weather_options.length == 0) {
				settings.severeWeather.fCastType = '';
			}
			else {
				$options = $('#forecast_severe_weather_option').val();
				load_severeForecast_content(forecast_weather_options, $options);
				settings.severeWeather.warningType = forecast_weather_options;
			}
			legendMainShow($('#forecast_codes_div'));
			clearInterval(forecast_interval);
			forecast_interval = setInterval(function () {
				update_forecast_severe();
			}, forecast_intervalTime);
			$("#forecastwarningreportoption1").show();
			$('#forecastwarningreportoption2').show();
		}
		else {
			settings.severeWeather.status = false;
			legendMainHide($('#forecast_codes_div'));
			remove_forecastevere_warning();
			$("#forecastwarningreportoption1").hide();
			$('#forecastwarningreportoption2').hide();
		}
		settingsUpdate();
	});

	var forecastChecks = $('input:checkbox[name="forecast_warning_option[]"]').change(function () {
		var e = $(this);
		if ($(".forecastsevere").is(':checked')) {
			remove_forecastevere_warning();
			var $radios = $('input:checkbox[name="forecast_warning_option[]"]');
			forecast_weather_options = $radios.filter(':checked').map(function () {
				return this.value;
			}).get();
			if (forecast_weather_options.length == 0) {
				settings.severeWeather.warningType = '';
			}
			else {
				$options = $('#forecast_severe_weather_option').val();
				load_severeForecast_content(forecast_weather_options, $options);
				settings.severeWeather.warningType = forecast_weather_options;
			}
		}
		settingsUpdate();
	});

	$("#forecast_severe_weather_option").change(function () {
		var e = $(this);
		if ($(".forecastsevere").is(':checked')) {
			remove_forecastevere_warning();
			settings.severeWeather.fCastType = this.value;
			forecast_weather_options = forecastChecks.filter(':checked').map(function () {
				return this.value;
			}).get();
			if (forecast_weather_options.length != 0) {
				load_severeForecast_content(forecast_weather_options, this.value);
			}
		}
		settingsUpdate();
	});

	$('#forecast_module_opacity').on('change', function () {
		var e = $(this);
		e = e.val();
		changeOpacity(e);

	});

	$("#forecast_module_opacity").bind("keyup", function () {
		var e = $(this);
		e = e.val();
		changeOpacity(e);
	});

	function changeOpacity(e) {
		settings.modelSettings.opacity = e;
		opacity = e;
		change_opacity();
		settingsUpdate();
	}
}

function domReadySatellite() {
	//Move dom ready stuff here.
/*
	$('#sat-enable').change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.satellite.status = true;
		}
		else {
			settings.satellite.status = false;
		}
		settingsUpdate();
	});
	*/
}

function domReadyMapOptions() {
	// START Imported from ground.js
	var districts = null;
	var riverbasins = null;
	var firedistrict = null;

	var t1 = createTask(1);
	var t2 = createTask(2);
	var t3 = createTask(3);
	var t4 = createTask(4);
	var t5 = createTask(5);
	var t6 = createTask(6);

	function executeMapping() {
		var tasks = Array.prototype.concat.apply([], arguments);
		var task = tasks.shift();
		task(function () {
			if (tasks.length > 0)
				executeMapping.apply(this, tasks);
		});
	}

	function createTask(num) {
		console.log("num = " + num); 
		return function (callback) {
			setTimeout(function () {
				if (num == 1) {
					districts.setMap(map);
				}
				else if (num == 2) {
					$('#loader').hide();
				}
				else if (num == 3) {
					riverbasins.setMap(map);
				}
				else if (num == 4) {
					$('#loader').hide();
				}
				else if (num == 5) {
					firedistrict.setMap(map);
				}
				else if (num == 6) {
					$('#loader').hide();
				}
				if (typeof callback == 'function') {
					callback();
				}
			}, 1000);
		}
	}
	// END Imported from ground.js

	$('#map-options').change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.mapOptions.status = true;
			if ($('#weatherregions').is(":checked")) {
				$('#weatherregions').trigger("change");
			}
			if ($('#riverbasins').is(":checked")) {
				$('#riverbasins').trigger("change");
			}
			
			if ($('#firedistrict').is(":checked")) {
				$('#firedistrict').trigger("change");
			}
			
			//Nothing ticked turn something on...
			if (!$('#weatherregions').is(":checked") && !$('#riverbasins').is(":checked") && $('#map_options_selector').val() == 'ROADMAP') {
				$('#weatherregions').attr("checked", true);
				$('#weatherregions').trigger("change");
			}
		}
		else {
			settings.mapOptions.status = false;
			if ($('#weatherregions').is(":checked")) {
				districts.setMap(null);
			}
			if ($('#riverbasins').is(":checked")) {
				riverbasins.setMap(null);
			}
			if ($('#firedistrict').is(":checked")) {
				firedistrict.setMap(null);
			}
			if ($('#map_options_selector').val() != 'ROADMAP') {
				$('#map_options_selector').val('ROADMAP');
				$('#map_options_selector').trigger("change");
			}
		}
		settingsUpdate();
	});

	$('#weatherregions').change(function () {
		if ($('#weatherregions').is(':checked')) {
			if (!$('#map-options').is(':checked')) {
				$('#map-options').attr("checked", true);
				settings.mapOptions.status = true;
			}
			if (districts == null) {
				districts = new google.maps.GroundOverlay("images/forecast/forecast_districts.png", new google.maps.LatLngBounds(new google.maps.LatLng(-60.00, 110.00), new google.maps.LatLng(0.00, 170.00)));
				google.maps.event.addListener(districts, 'click', function (mouseevent) {
					google.maps.event.trigger(map, 'click', mouseevent);
				});
			}
			settings.mapOptions.weatherRegion = true;
			executeMapping(t1, t2);
		}
		else {
			settings.mapOptions.weatherRegion = false;
			districts.setMap(null);
			if (!$('#riverbasins').is(':checked') && $('#map_options_selector').val() == 'ROADMAP') {
				$('#map-options').attr("checked", false);
				settings.mapOptions.status = false;
			}
		}
		settingsUpdate();
	});

	$('#riverbasins').change(function () {
		if ($('#riverbasins').is(':checked')) {
			if (!$('#map-options').is(':checked')) {
				$('#map-options').attr("checked", true);
				settings.mapOptions.status = true;
			}
			if (riverbasins == null) {
				riverbasins = new google.maps.GroundOverlay("images/forecast/river_basins.png", new google.maps.LatLngBounds(new google.maps.LatLng(-43.80, 112.80), new google.maps.LatLng(-10.10, 158.05)));
				google.maps.event.addListener(riverbasins, 'click', function (mouseevent) {
					google.maps.event.trigger(map, 'click', mouseevent);
				});
			}
			settings.mapOptions.catchments = true;
			executeMapping(t3, t4);
		}
		else {
			settings.mapOptions.catchments = false;
			riverbasins.setMap(null);
			if (!$('#weatherregions').is(':checked') && $('#map_options_selector').val() == 'ROADMAP') {
				$('#map-options').attr("checked", false);
				settings.mapOptions.status = false;
			}
		}
		settingsUpdate();
	});
	// firedistrict
	$('#firedistrict').change(function () {
		if ($('#firedistrict').is(':checked')) {
			if (!$('#map-options').is(':checked')) {
				$('#map-options').attr("checked", true);
				settings.mapOptions.status = true;
			}
			if (firedistrict == null) {
				firedistrict = new google.maps.GroundOverlay("images/forecast/fire_districts.png", new google.maps.LatLngBounds(new google.maps.LatLng(-43.80,112.70), new google.maps.LatLng(-10.10, 158.05)));
				google.maps.event.addListener(firedistrict, 'click', function (mouseevent) {
					google.maps.event.trigger(map, 'click', mouseevent);
				});
			}
			settings.mapOptions.fireRegion = true;
			executeMapping(t5, t6);
		}
		else {
			settings.mapOptions.fireRegion = false;
			firedistrict.setMap(null);
			if (!$('#weatherregions').is(':checked') && $('#map_options_selector').val() == 'ROADMAP') {
				$('#map-options').attr("checked", false);
				settings.mapOptions.status = false;
			}
		}
		settingsUpdate();
	});

	$('#map_options_selector').change(function () {
		//get value from ddl
		e = $(this).val();
		settings.mapOptions.option = e;
		if (e == 'SATELLITE') {
			map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
			map.setOptions({ styles: "" });
		}
		else if (e == 'HYBRID') {
			map.setMapTypeId(google.maps.MapTypeId.HYBRID);
			map.setOptions({ styles: "" });
		}
		else if (e == 'TERRAIN') {
			map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
			map.setOptions({ styles: "" });
		}
		else if (e == 'light_monochrome') {
			map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
			var mapMonochrome = {
				styles: [{ "featureType": "administrative.locality", "elementType": "all", "stylers": [{ "hue": "#2c2e33" }, { "saturation": 7 }, { "lightness": 19 }, { "visibility": "on" }] }, { "featureType": "landscape", "elementType": "all", "stylers": [{ "hue": "#ffffff" }, { "saturation": -100 }, { "lightness": 100 }, { "visibility": "simplified" }] }, { "featureType": "poi", "elementType": "all", "stylers": [{ "hue": "#ffffff" }, { "saturation": -100 }, { "lightness": 100 }, { "visibility": "off" }] }, { "featureType": "road", "elementType": "geometry", "stylers": [{ "hue": "#bbc0c4" }, { "saturation": -93 }, { "lightness": 31 }, { "visibility": "simplified" }] }, { "featureType": "road", "elementType": "labels", "stylers": [{ "hue": "#bbc0c4" }, { "saturation": -93 }, { "lightness": 31 }, { "visibility": "on" }] }, { "featureType": "road.arterial", "elementType": "labels", "stylers": [{ "hue": "#bbc0c4" }, { "saturation": -93 }, { "lightness": -2 }, { "visibility": "simplified" }] }, { "featureType": "road.local", "elementType": "geometry", "stylers": [{ "hue": "#e9ebed" }, { "saturation": -90 }, { "lightness": -8 }, { "visibility": "simplified" }] }, { "featureType": "transit", "elementType": "all", "stylers": [{ "hue": "#e9ebed" }, { "saturation": 10 }, { "lightness": 69 }, { "visibility": "on" }] }, { "featureType": "water", "elementType": "all", "stylers": [{ "hue": "#e9ebed" }, { "saturation": -78 }, { "lightness": 67 }, { "visibility": "simplified" }] }]
			};
			map.setOptions(mapMonochrome);
			$("#map_options_selector").find("option[value='light_monochrome']").attr("selected", true);
			settings.mapOptions.option = "light_monochrome";
		}
		else {
			map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
			map.setOptions({ styles: "" });
			if (!$('#weatherregions').is(':checked') && !$('#riverbasins').is(':checked') && $('#map-options').is(':checked')) {
				$('#map-options').attr("checked", false);
				settings.mapOptions.status = false;
			}
		}
		if (!$('#map-options').is(':checked') && $('#map_options_selector').val() != 'ROADMAP') {
			$('#map-options').attr("checked", true);
			settings.mapOptions.status = true;
		}
		settingsUpdate();
	});

	//For the site list popout in map data layer (search functionality).
	$.expr[':'].Contains = function (a, i, m) {
		return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
	};
	$(document).on('input', '.clearable', function () {
		$(this)[tog(this.value)]('x');
	}).on('mousemove', '.x', function (e) {
		$(this)[tog(this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left)]('onX');
	}).on('click', '.onX', function () {
		$(this).removeClass('x onX').val('').change();
	});

	//Helper function for the site list popout in map data layer (search functionality).
	function tog(v) {
		return v ? 'addClass' : 'removeClass';
	}
}

//For the site list popout in map data layers.
function listFilter(header, list, layer_id) {
	if ($('#input' + layer_id).length == 0) {
		var form = $("<form>").attr({ "class": "filterform", "action": "#" }),
		input = $("<input>").attr({ "class": "filterinput clearable", "type": "text", "size": "15", 'id': 'input' + layer_id });
		$(form).append(input).insertAfter(header);

		$(input).change(function () {
			var filter = $(this).val();
			var count = filter.replace(/ /g, '').length;
			if (filter) {
				$(list).find("a:not(:Contains(" + filter + "))").parent().hide();
				$(list).find("a:Contains(" + filter + ")").parent().show();
			}
			else {
				$(list).find("li").show();
			}
			return false;
		}).keyup(function () {
			$(this).change();
		});
	}
}

function domReadyMapDataLayers() {
	$('#checkbox-data-layers').change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.dataLayer.status = true;
		}
		else {
			settings.dataLayer.status = false;
		}
		settingsUpdate();
		settingsSetSituationRoomLayers();
	});

	$('#client_data_layer').change(function () {
		var e = $(this);
		if (e.attr("checked") && !$('#checkbox-data-layers').is(":checked")) {
			$('#checkbox-data-layers').attr("checked", true);
			$('#checkbox-data-layers').trigger("change");
		}
		else if (!e.attr("checked") && !$('#public_data_layer').is(":checked")) {
			$('#checkbox-data-layers').attr("checked", false);
			$('#checkbox-data-layers').trigger("change");
		}
		showClientDataDropDown();
	});

	$('#public_data_layer').change(function () {
		var e = $(this);
		if (e.attr("checked") && !$('#checkbox-data-layers').is(":checked")) {
			$('#checkbox-data-layers').attr("checked", true);
			$('#checkbox-data-layers').trigger("change");
		}
		else if (!e.attr("checked") && !$('#client_data_layer').is(":checked")) {
			$('#checkbox-data-layers').attr("checked", false);
			$('#checkbox-data-layers').trigger("change");
		}
		showPublicDataDropdown();
	});

	$('#additional_layers').on('change', function () {
		var e = $(this);
		remove_post_code();
		if (e.val() !== '') {
			show_post_code();
		}
	});

	$('#layers').on('change', function () {
		var e = $(this);
		e = e.val();
		selected_layer_value_current = e;
		$("#navigation").html('');
		$("div.navigation").scrollTop(0);
		clearMapLayers();
		remove_filter_marker();
		remove_post_code();
		remove_forecast_filter_marker();
		if (e !== '' && e !== 4) {
			settings.mapOptions.option = e;
			if (e != '12' && e != '19' && e != '38' && e != '39') {
				$('.assets_report').prop('disabled', false);
				$('.report_disabled').hide();
				load_kml_data(e);
			}
			if (e == 1) {
				RT_NSW.setMap(map);
				RT_NT.setMap(map);
				RT_QLD.setMap(map);
				RT_SA.setMap(map);
				RT_TAS.setMap(map);
				RT_VIS.setMap(map);
				RT_WA.setMap(map);
			}
			if (e == 35) {
				waternsw.setMap(map);
			}
			if (e == 39) {
				datach1.loadGeoJson(chainageurl);
				//Set event listener for each feature.
				datach1.addListener('mouseover', function (event) {
					var fetproper = event.feature.getProperty("ROUTE_CHAI");
					chaininfowindow.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper + 'km </b>' + '</div>');
					chaininfowindow.setPosition(event.latLng);
					chaininfowindow.setOptions({ pixelOffset: new google.maps.Size(0, -34) });
					chaininfowindow.open(map);
				});
				datach1.setMap(map);
			}
			if (e == 12) {
				show_post_code();
				settings.mapOptions.option = e;
			}
			if (e == 19) {
				$('#loader').show();
				MDSWA.setMap(map);
				kml_hide_loader(MDSWA);
				settings.mapOptions.options = e;
				$('.assets_report').prop('disabled', true);
				$('.report_disabled').show();
			}
		}
		else {
			$('#loader_layer_type').hide();
			$('.assets_report').prop('disabled', true);
			$('.report_disabled').show();
		}
		settingsUpdate();
	});
}
function domReadyNbnDataLayers() {
	$('#checkbox-nbn-data-layers').change(function () {
		var e = $(this);
		if (e.attr("checked")) {
			settings.nbndataLayer.status = true;
			show_post_codeP();
		}
		else {
			settings.nbndataLayer.status = false;
			remove_post_codeP();
		}
		settingsUpdate();

	});
}

function domReadyForecastModel() {
	//Move dom ready stuff here.
}
//NOT SURE IF NEEDED, UNCOMMENT IF IT IS.
/*
function domReadyDateTimePicker() {

	var CurrentDate = new Date().dateFormat('M  d , Y h a');
	$('#datetimepicker').val(CurrentDate);
	$('#datetimepicker').datetimepicker({
		formatTime: 'h a',
		formatDate: 'd.m.Y',
		format: 'M  d , Y h a',
		closeOnDateSelect: true
	});
	
}

function domReadyMapTriggerResize() {
	function triggerMap(m) {
		x = m.getZoom();
		c = m.getCenter();
		google.maps.event.trigger(m, 'resize');
		m.setZoom(x);
		m.setCenter(c);
	};
}
*/

function domReadyContact() {
	$('#showcontact').live('click', function (e) {
		$('#showcontantdetails').show();
	});

	$('#closecontact').live('click', function (e) {
		$('#showcontantdetails').hide();
	});

	$('#closedialog').click(function () {
		$('#custom_dialog').hide();
	});
	//$('.assets_report').prop('disabled', true);
	//$('.report_disabled').show();
}

function domReadyUpdateUserSession() {
	user_session_update = setInterval(function () {
		$.ajax({
			url: 'ajax/check_session.php',
			type: 'POST',
			success: function (data) {
				if (data == 0) {
					window.location.href = 'login.php?max=1';
				}
			}
		})
	}, sessionPingTime);
}

function domReadyLegends() {
	//Creates or gets the legend cookie.
	var legendsCookie = legendsGetCookie();
	//Loads the legends locations.
	legendsPopulate(legendsCookie);
	if ($('.legend-container-content > div').children().size() <= 0) {
		$('.legend-container-content').hide();
	}
	//Draggable legends.
	$('.legend-main').draggable({
		start: function (event, ui) {
			dragging = true;
			if ($('.legend-container-content > div').children().size() <= 0 || $('.legend-container-content > div').children(':visible').size() <= 0) {
				$('.legend-container-content').fadeIn();
				$('.legend-container-text').addClass('legend-container-text-display');
			}
			dragLegendBreakAway(event, ui);
			ui.helper.css('cursor', 'all-scroll');
			ui.helper.css("bottom", 'auto');
		},
		drag: function (event, ui) {
			dragging = true;
			if (ui.helper.hasClass('first-drag')) {
				ui.position.top = event.clientY;
				ui.position.left = event.clientX;
			}
			//top
			if (ui.position.top < 30) {
				ui.position.top = 30;
			}
			else {
				//bottom
				if (ui.position.top > $('#map-container').height() - ui.helper.height() - 40) {
					ui.position.top = $('#map-container').height() - ui.helper.height() - 40
				}
				else if (ui.helper.hasClass('first-drag')) {
					ui.position.top = event.clientY;
				}
			}
			//left
			if (ui.position.left < 20) {
				ui.position.left = 20;
			}
			else {
				//right
				if (ui.position.left > $('#map-container').width() - ui.helper.width() - 20) {
					ui.position.left = $('#map-container').width() - ui.helper.width() - 20
				}
				else if (ui.helper.hasClass('first-drag')) {
					ui.position.left = event.clientX;
				}
			}
		},
		stop: function (event, ui) {
			legendSetLocations(event, ui.helper);
			ui.helper.css('cursor', 'pointer');
			ui.helper.removeClass('first-drag');
			setTimeout(function () { dragging = false }, 500);
			if ($('.legend-container-content > div').children().size() <= 0 || $('.legend-container-content > div').children(':visible').size() == 0) {
				setTimeout(function () {
					$('.legend-container-text').removeClass('legend-container-text-display');
					$('.legend-container-content').fadeOut();
				}, 500);
			}
		},
		scroll: false
	});

	$('.legend-container-content').droppable({
		accept: '.legend-main',
		hoverClass: "no-legends-collapsed-hover",
		drop: function (event, ui) {
			dragLegendDropZone(ui.helper);
			legendSetLocations(event, ui.helper);
		}
	});

	if ($('.legend-container-content > div').children().size() <= 0) {
		$('.legend-container-content').addClass('no-legends-collapsed');
	}
	// Put 
	mapsLoadedQueue.push(function () { domReadyLegendsIntoMap(); });
}
function domReadyLegendsIntoMap()
{
	//Push Legends into google maps
	//var legendsDiv = $('#legend-container');
	//legendsDiv = legendsDiv.detach();
	//map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legendsDiv[0]);
	var e = $('#legend-container');
	e.css({ position: '', top: '', bottom: '', left: '', right: '' });
	map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(document.getElementById('legend-container'));
}

function domReadySliders() {
	var radarSliderDdl = $("#radar_opacity");
	var radarSlider = $("<div id='slider-radar' class='hidden-phone hidden-tablet'></div>").insertAfter(radarSliderDdl).slider({
		min: 10,
		max: 100,
		step: 10,
		range: "min",
		slide: function (event, ui) {
			var v = parseInt(ui.value);
		
			if (!isNaN(v)) {
				radar_opacity = v;
				if (radar_opacity < 10)
					radar_opacity = radar_opacity * 100;
				if (radar_opacity < 10)
					radar_opacity = 10;
		
				radarSliderDdl.val(radar_opacity);
				ChangeRadarOpacity();
			}
			else {
				console.error("Can not set radar slider value, Invalid number.");
			}
		},
		stop: function (event, ui) {
			var v = parseInt(ui.value);
			if (!isNaN(v)) {
				settings.radar.opacity = v;
				settingsUpdate();
				radar_opacity = v;
				ChangeRadarOpacity();
			}
		}
	});
	radarSlider.slider("value", settings.radar.opacity);
	radarSliderDdl.val(settings.radar.opacity);
	$("#radar_opacity").change(function () {
		var e = $(this);
		
		var v = parseInt(e.val());
		
		if (!isNaN(v)) {
			radar_opacity = v;
			if (radar_opacity < 10)
				radar_opacity = radar_opacity * 100;
			if (radar_opacity < 10)
				radar_opacity = 10;
		
			radarSlider.slider("value", e);
		}
		else {
			console.error("Can not set radar slider value, Invalid number.");
		}
	});
	/* NEED TO CHECK IF STILL NEEDED
	var fCastModuleDdl = $("#forecast_module_opacity");
	var fCastModuleSlider = $("<div id='slider-f-cast-module'></div>").insertAfter(fCastModuleDdl).slider({
		min: 10,
		max: 100,
		step: 10,
		range: "min",
		value: parseFloat($("#forecast_module_opacity").val()),
		slide: function (event, ui) {
			fCastModuleDdl[0].selectedIndex = parseInt((ui.value / 10) - 1);
		},
		stop: function (event, ui) {
			// Please code me.
		}
	});
	$("#forecast_module_opacity").change(function () {
		var e = $(this);
		e = parseInt((e.val() * 100));
		if (!isNaN(e) && e > 0 && e <= 100) {
			fCastModuleSlider.slider("value", e);
		}
		else {
			log.error("Can not set forecast module slider value, Invalid number.");
		}
	});
	*/
}

function domReadySetSituationRoom() {
	settingsCheckOldCookie();
}

// On load checks to see what parts of nav should be expanded.
function domReadyMenuCollapse() {
	$('.header-collapse').each(function () {
		var e = $(this);
		if (e.children('.header-checkbox').attr('checked') == true) {
			e.siblings('.content-collapse').show();
			e.find('span').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-up');
		}
		else if (e.children('.header-checkbox').attr('checked') == false) {
			e.removeClass('border-bottom-0');
		}
	});
}

function legendsGetCookie() {
	/*
	c = is collapsed
	ra = rainfall legend
	ri = river legend
	wa = warning legend
	fo = forecast legend
	nb = NBN zone selection
	zl = Zone List
	top = top value of div in page
	left = left value of div in page
	*/
	var legendDefault = { ra: { c: true, top: '', bottom: '', left: '', right: '' }, ri: { c: true, top: '', bottom: '', left: '', right: '' }, wa: { c: true, top: '', bottom: '', left: '', right: '' }, fo: { c: true, top: '', bottom: '', left: '', right: '' },nb: { c: true, top: '', bottom: '', left: '', right: '' },zl: { c: true, top: '', bottom: '', left: '', right: '' }}
	var legendLocations = $.cookie("legendLocations") || legendDefault;
	if (typeof legendLocations == "string") {
		try {
			legendLocations = JSON.parse(legendLocations);
		}
		catch (ex) {
			console.error('legendLocation cookie is invalid, using default locations...');
			legendLoctions = legendDefaults;
		}
	}
	return legendLocations;
}

//save the cookie each time a user stops moving the legend
function legendSetLocations(event, e) {
	e = $(e);
	var legendLocations = legendsGetCookie();
	var legendObjectID = { ra: 'rainfall_gauges_codes_div', ri: 'river_gauges_codes_div', wa: 'warning_codes_div', fo: 'forecast_codes_div',nb: 'nbn_zones_div',zl:'navigation-nbn'}
	var eAttribute = e.attr('id');
	$.each(legendObjectID, function (legendKey, legendValue) {
		if (eAttribute == legendValue) {
			legendLocations[legendKey] = legendSetLegend(legendLocations[legendKey], e);
			$.cookie("legendLocations", JSON.stringify(legendLocations), { expires: 365 });
		}
	});
}

function legendSetLegend(currentLegend, e) {
	gMapsLegendPosition(e);
	//Check if collapsed.
	if (e.parent().hasClass('map-container')) {
		currentLegend.c = false;
		if (e.position().top > 0) {
			currentLegend.top = e.position().top;
		}
		else {
			currentLegend.bottom = e.position().top - $('#map-container').height() - e.position().top - e.outerHeight();
		}
		if (e.position().left > 0) {
		currentLegend.left = e.position().left;
		}
		else {
			currentLegend.right = e.position().left - $('#map-container').width() - e.position().left - e.outerWidth();
		}
	}
	else {
		currentLegend.c = true;
		currentLegend.top = '';
		currentLegend.bottom = '';
		currentLegend.left = '';
		currentLegend.right = '';
	}
	return currentLegend;
}

function legendsPopulate(legendsCookie) {
	var legendObjectID = { ra: '#rainfall_gauges_codes_div', ri: '#river_gauges_codes_div', wa: '#warning_codes_div', fo: '#forecast_codes_div',nb: '#nbn_zones_div',zl:'#navigation-nbn'}
	//Iterates through cookie recursively.
	$.each(legendsCookie, legendsCookieIterate);
	function legendsCookieIterate(key, value) {
		if (value !== null && typeof value === "object") {
			if (value.c != true) {
				//compare cookieID to legendID so we can change the correct legend.
				//Legend will automatically load into the collapsed box, no need to set it in an else.
				$.each(legendObjectID, function (legendKey, legendValue) {
					if (legendKey == key) {
						if (value.top > 0 && value.top < $('#map-container').height() && value.left > 0 && value.left < $('#map-container').width()) {
							$(legendValue).appendTo('#map-container');
							if (value.top > 0) {
								$(legendValue).css('top', value.top);
								$(legendValue).css('bottom', '');
							}
							else {
								$(legendValue).css('bottom', value.bottom);
								$(legendValue).css('top', '');
							}
							if (value.left > 0) {
								$(legendValue).css('left', value.left);
								$(legendValue).css('right', '');
							}
							else {
								$(legendValue).css('right', value.right);
								$(legendValue).css('left', '');
							}
							$(legendValue).addClass('single-draggable-legend');
						}
					}
				});
			}
			$.each(value, legendsCookieIterate);
		}
	}
}

function screen_resize_elements(screen_size) {
	if (screen_size >= "1100") {
		if ($('#nav-container').is(':hidden')) {
			$("#showcontact").css('right', '12%');
			$("#showcontantdetails").css('right', '12%');
		}
		else {
			$(".navigation").css('right', '25.4%');
			$("#showcontact").css('right', '25.4%');
			$("#showcontantdetails").css('right', '25.4%');
		}
	}
	else {
		$(".navigation").css('right', '2%');
		if (screen_size >= "1100") {
			defined_width = "13";
		}
		else if (screen_size <= "1100" && screen_size >= "700") {
			defined_width = "16";
		}
		else if (screen_size <= "700" && screen_size >= "500") {
			defined_width = "20";
		}
		else if (screen_size <= "500") {
			defined_width = "25";
		}
		$("#showcontact").css('right', defined_width + '%');
		$("#showcontantdetails").css('right', defined_width + '%');
	}
}

function clearMapLayers() {
	settings.mapOptions.option = '';
	RT_NSW.setMap(null);
	RT_NT.setMap(null);
	RT_QLD.setMap(null);
	RT_SA.setMap(null);
	RT_TAS.setMap(null);
	RT_VIS.setMap(null);
	RT_WA.setMap(null);
	MDSWA.setMap(null);
	waternsw.setMap(null);
	GWA.setMap(null);
	datach1.setMap(null);
	$('.navigation').hide();
	$('#show_sites').hide();
	$('#hide_sites').hide();
	$('#layer_button').hide();
	if (kml_markes.length > 0) {
		for (var i = 0; i < kml_markes.length; i++) {
			kml_markes[i].setMap(null);
		}
	}
	kml_markes.length = 0;
}

function kml_hide_loader(layer) {
	// map bound change event
	google.maps.event.addListener(layer, "metadata_changed", function () {
		$('#loader').hide();
	});
}
