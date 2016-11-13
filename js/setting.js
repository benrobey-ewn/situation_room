// apply settings to menu AFTER map loads
mapsLoadedQueue.push(function () { settingsReadFromCookie(); });
// "[]" are for old array values that will need to be changed.
var settings = {
	//[0]
	radar: {
		status: true,
		loop: false,
		type: radarType,
		speed: 1000,
		sites: false,
		opacity: 100,
		// [7]
		weatherRadarSites: false
	},

	lightning: {
		status: false,
		minutes: '',
		loop: false,
		speed: 1000
	},

	//[1]
	observations: {
		status: true,
		type: 1
	},

	//[8]
	rainfallGauges: {
		status: false,
		type: '1hr',
		options: ''
	},

	//[9]
	riverGauges: {
		status: false,
		options: ''
	},

	//[2]
	forecasts: {
		status: false
	},

	//[3]
	warnings: {
		status: true,
		typeOption: 'expired',
		warningLast: 48,
		types: 'all'
	},

	//[4]
	severeWeather: {
		status: false,
		fCastType: 1,
		warningType: 'all'
	},

	satellite: {
		status: false,
		type: '',
		opacity: 1,
		imageDate: 1,
		loop: false,
		loopSpeed: 1
	},

	//[5]
	mapOptions: {
		status: false,
		option: 'ROADMAP',
		weatherRegion: false,
		catchments: false,
		fireRegion: false
	},

	//[10]
	dataLayer: {
		status: false,
		publicLayer: '',
		privateLayer: ''
	},

	//[6]
	modelSetting: {
		status: true,
		opacity: 1,
		type: '',
		dateTime: '',
		layers: '',
		loop: false,
		speed: 4000
	}
};
try {
	// set cookie path default
	if (location.pathname.indexOf('/') > -1) {
		var p = location.pathname.substring(0, location.pathname.lastIndexOf('/') + 1);

		if (p.length > 1 && p.length == p.lastIndexOf('/') + 1) {
			$.cookie.defaults.path = p;
		}		
	}
}
catch (e)
{
	console.error(e);
}
function settingsReadFromCookie() {
	try {
		var settingsCookie = $.parseJSON($.cookie("SitRoomSettings"));
		//Load cookie
		if (settingsCookie != null) {
			settings = $.extend({}, settings, settingsCookie);
		}
			//Set cookie to default if no prior cookies exist
		else {
			$.cookie("SitRoomSettings", null);
			$.cookie("SitRoomSettings", JSON.stringify(settings), { expires: 365 });
		}
		settings = $.parseJSON($.cookie("SitRoomSettings"));
	}
	catch (e) {
		console.error(e);
	}
	settingsApply();
}

// This funciton upgrades the old settings cookie to the new cookie as to maintain the original settings for a users situaiton room.
function settingsCheckOldCookie() {
	var oldSettingsCookie = $.parseJSON($.cookie("situation_room_settings"));
	// If old settings cookie 'situation_room_settings' exists, upgrade it to SitRoomSettings cookie.
	if ($.cookie("SitRoomSettings") == null && oldSettingsCookie != null && oldSettingsCookie.length > 0) {
		//Radar
		settings.radar.status = settingsStringToBoolean(oldSettingsCookie[0].status);
		settings.radar.loop = settingsStringToBoolean(oldSettingsCookie[0].loop);
		settings.radar.type = oldSettingsCookie[0].radar_type;
		settings.radar.speed = oldSettingsCookie[0].radar_speed;
		settings.radar.sites = settingsStringToBoolean(oldSettingsCookie[0].radar_sites);
		settings.radar.opacity = oldSettingsCookie[0].opacity;
		//Weather Radar Sites
		settings.radar.weatherRadarSites = settingsStringToBoolean(oldSettingsCookie[7].status);

		//Observations
		settings.observations.status = settingsStringToBoolean(oldSettingsCookie[1].status);
		settings.observations.type = oldSettingsCookie[1].observation_type;

		//Forecasts
		settings.forecasts.status = settingsStringToBoolean(oldSettingsCookie[2].status);

		//Warnings
		settings.warnings.status = settingsStringToBoolean(oldSettingsCookie[3].status);
		settings.warnings.typeOption = oldSettingsCookie[3].warning_type_option;
		settings.warnings.warningLast = oldSettingsCookie[3].expired_warning_last;
		settings.warnings.types = oldSettingsCookie[3].warning_types;

		//Severe Weather
		settings.severeWeather.status = settingsStringToBoolean(oldSettingsCookie[4].status);
		settings.severeWeather.fCastType = oldSettingsCookie[4].forecast_type;
		settings.severeWeather.warningType = oldSettingsCookie[4].warning_types;

		//Map Options
		settings.mapOptions.option = oldSettingsCookie[5].map_option;
		settings.mapOptions.weatherRegion = settingsStringToBoolean(oldSettingsCookie[5].weather_region);
		settings.mapOptions.catchments = settingsStringToBoolean(oldSettingsCookie[5].river_catchments);
		settings.mapOptions.fireRegion = settingsStringToBoolean(oldSettingsCookie[5].fire_region);

		//Forecast Model
		settings.modelSetting.status = settingsStringToBoolean(oldSettingsCookie[6].status);
		settings.modelSetting.opacity = oldSettingsCookie[6].opacity;
		settings.modelSetting.type = oldSettingsCookie[6].forecast_type;
		settings.modelSetting.dateTime = oldSettingsCookie[6].forecast_date_time;
		settings.modelSetting.layers = oldSettingsCookie[6].layers;
		settings.modelSetting.loop = settingsStringToBoolean(oldSettingsCookie[6].loop);
		settings.modelSetting.speed = oldSettingsCookie[6].speed;

		//Rainfall Gauges
		settings.rainfallGauges.status = settingsStringToBoolean(oldSettingsCookie[8].status);
		settings.rainfallGauges.type = oldSettingsCookie[8].type;
		settings.rainfallGauges.options = oldSettingsCookie[8].options;

		//River Gauges
		settings.riverGauges.status = settingsStringToBoolean(oldSettingsCookie[9].status);
		settings.riverGauges.options = oldSettingsCookie[9].options;

		//Data Layer
		settings.dataLayer.publicLayer = oldSettingsCookie[10].public_layer_option;
		settings.dataLayer.privateLayer = oldSettingsCookie[10].private_layer_option;

		//Set new values in settings
		settingsUpdate();

	}

	//Wipe old cookie https://github.com/carhartl/jquery-cookie under 'Delete cookie:'
	$.removeCookie("situation_room_settings");
}

function settingsStringToBoolean(settingValue) {
	settingValueBoolean = false;
	if (settingValue == 'on') {
		settingValueBoolean = true;
	}
	return settingValueBoolean;
}

function settingsUpdate() {
	// Possible answer for passing hash of keys + values or a single key+value (like jquery .css({'k',v}) )
	/*
	if (arguments !== null && typeof arguments !== 'undefined' && arguments.length > 0) {
		if (typeof arguments[0] == 'Object') {
			settings = $.extend(settings, arguments[0]);
		}
		else if (arguments.length >= 2)
		{
			// set thing to value
			if (setting.hasOwnProperty(arguments[0])) {
				setting[arguments[0]] = arguments[1];      
			}
		}

	}
	*/
	$.cookie("SitRoomSettings", JSON.stringify(settings), { expires: 365 });
	settings = $.parseJSON($.cookie("SitRoomSettings"));
}

//Apply the settings values to their related controls
//These methods might be re defining default values, need to check.
//Ordered by how they were originally listed (will eventually be changed to a logical flow).
function settingsApply() {
	settingsSetSituationRoomRadar();
	settingsSetSituationsRoomLightning();
	settingsSetSituationRoomObservations();
	settingsSetSituationRoomRainfallGauges();
	settingsSetSituationRoomForecasts();
	settingsSetSituationRoomWarnings();
	settingsSetSituationRoomSevereWeather();
	settingsSetSituationRoomSatellite();
	settingsSetSituationRoomMapOptions();
	settingsSetSituationRoomRiverGauges();
	settingsSetSituationRoomLayers();
	settingsSetSituationRoomForecastModel();
	settingsUpdate();
	logEWNCopyright();
}

function settingsSetSituationRoomRadar() {
	initEWNMapsRadarsMarkerLabelPrototype();
	rio2 = new RadarImageOverlay(map, radars2, minimumSeconds, updateInterval, 75);
	if (settings.radar !== null && typeof settings.radar !== 'undefined' && settings.radar.status != false) {
		$('#optRadar').attr("checked", (settings.radar.loop == true));
		$('#radar_display_images').attr("checked", (settings.radar.status == true));
		if (settings.radar.type !== '') {
			radarType = settings.radar.type;
			if (radarType == '512') {
				radarType = 256;
			}
			if (map_observation == 2) {
				$('#select_map_range').val('256');
			}
			else {
				$('#select_map_range').val(radarType);
			}
		}
		if (settings.radar.opacity !== '') {
			radar_opacity = settings.radar.opacity;
			$("#radar_opacity").val(radar_opacity);
			$('#slider-radar').slider('value', parseInt(radar_opacity));
		}
		if (settings.radar.speed !== '') {
			timeInterval = parseInt(settings.radar.speed);
			$('#loop_speed_select').val(timeInterval);
		}
		if (settings.radar.loop == true) {
			EWNMapsRadarPlayStart();
		}
		else {
			$('#optRadar').attr("checked", false);
		}
		EWNMapsRadarShow();
		//Show radar bar
		$('.radar-info').show();
		$('.radar-bar-image').show();

		//Radar weatherSites
		if (settings.radar.weatherRadarSites == true) {
			$('#wx_radar_sites').attr("checked", true);
			rio2.showRadarMarkers();
		}
		else {
			$('#wx_radar_sites').attr("checked", false);
			rio2.hideRadarMarkers();
		}
	}
}

function settingsSetSituationsRoomLightning() {
	if (settings.lightning !== null && typeof settings.lightning !== 'undefined' && settings.lightning.status != false) {
		$('#checkbox-1-4').attr("checked", true);
		//Load rest.
	}
}

function settingsSetSituationRoomObservations() {
	if (settings.observations !== null && typeof settings.observations !== 'undefined' && settings.observations.status != false) {
		$('.observation_checkbox').attr("checked", true);
		if (settings.observations.type == 1) {
			$('.observation_option1').attr("checked", true);
			$('.observation_option2').attr("checked", false);
			load_observation_Capitals_only();
		}
		else if (settings.observations.type == 2) {
			$('.observation_option1').attr("checked", false);
			$('.observation_option2').attr("checked", true);
			load_observation_allCities();
		}
	}
}

function settingsSetSituationRoomRainfallGauges() {
	if (settings.rainfallGauges !== null && typeof settings.rainfallGauges !== 'undefined' && settings.rainfallGauges.status != false) {
		var warning_option = [];
		$(".rainfall").attr("checked", true);
		warning_option = settings.rainfallGauges.options;
		var $radios = $('input:checkbox[name="rainfall_option[]"]');
		$(warning_option).each(function (value) {
			var warning_value = warning_option[value];
			$radios.filter('[value="' + warning_value + '"]').prop('checked', true);
		});
		$("#rainfall_types").val(settings.rainfallGauges.type);
		update_rainfall();
		legendMainShow($('#rainfall_gauges_codes_div'));
	}
	else if (settings.rainfallGauges.options != null && typeof settings.rainfallGauges.options !== 'undefined') {
		warning_option = settings.rainfallGauges.options;
		var $radios = $('input:checkbox[name="rainfall_option[]"]');
		$(warning_option).each(function (value) {
			var warning_value = warning_option[value];
			$radios.filter('[value="' + warning_value + '"]').prop('checked', true);
		});
		$("#rainfall_types").val(settings.rainfallGauges.type);
	}
}

function settingsSetSituationRoomRiverGauges() {
	if (settings.riverGauges !== null && typeof settings.riverGauges !== 'undefined' && settings.riverGauges.status != false) {
		var river_guage_option = [];
		$(".river-guage").attr("checked", true);
		river_guage_option = settings.riverGauges.options;
		var $river_guage_radios = $('input:checkbox[name="river_guage_options[]"]');
		$(river_guage_option).each(function (value) {
			var river_guage_value = river_guage_option[value];
			$river_guage_radios.filter('[value="' + river_guage_value + '"]').prop('checked', true);
		});
		show_river_guages();
		clearInterval(river_guage_timeout);
		river_guage_timeout = setInterval(function () {
			update_river_guages();
		}, radartimeinterval);
		legendMainShow($('#river_gauges_codes_div'));
	}
}

function settingsSetSituationRoomForecasts() {
	if (settings.forecasts !== null && settings.forecasts !== 'undefined' && settings.forecasts.status != false) {
		$(".forecast_checkbox").attr("checked", true);
		$(".forecast_checkbox").trigger("change");
	}
}

function settingsSetSituationRoomWarnings() {
	if (settings.warnings !== null && typeof settings.warnings !== 'undefined' && settings.warnings.status != false) {
		$(".weather").attr("checked", true);
		legendMainShow($('#warning_codes_div'));
		var warning_option = [];
		var warning_type = 1;
		var expired_days = parseInt(settings.warnings.warningLast);
		if (isNaN(expired_days) == true) {
			expired_days = 48;
		}
		if (settings.warnings.typeOption == 'current') {
			expired_days = '';
			$('#current_warnings').prop('checked', true);
			$('#cyclone_option').show();
			$('.cyclone').attr("checked", true);
			toggle_cyclone();
		}
		else {
			warning_type = 2;
			$('#warning_days').val(expired_days);
			$('#warning_last').prop('checked', true);
			$('#cyclone_option').hide();
		}
		if (settings.warnings.types == 'all') {
			warning_option.push('all');
			$('input[name="warning_option[]"]').each(function () {
				$(this).attr("checked", true);
			});
			set_warning_setting(warning_type, warning_option, expired_days);
		}
		else {
			warning_option = settings.warnings.types;
			var $radios = $('input:checkbox[name="warning_option[]"]');
			$(warning_option).each(function (value) {
				var warning_value = warning_option[value];
				$radios.filter('[value="' + warning_value + '"]').prop('checked', true);
			});
			set_warning_setting(warning_type, warning_option, expired_days);
		}
		$("#warningreportoption1").show();
		$('#warningreportoption2').show();
	}
}

function settingsSetSituationRoomSevereWeather() {
	if (settings.severeWeather !== null && typeof settings.severeWeather !== 'undefined' && settings.severeWeather.status != false) {
		$(".forecastsevere").attr("checked", true);
		$('#forecast_severe_weather_option').val(settings.severeWeather.fCastType);
		var warning_option = [];
		if (settings.severeWeather.warningType != '') {
			var warning_type = settings.severeWeather.fCastType;
			var $radios = $('input:checkbox[name="forecast_warning_option[]"]');
			warning_option = settings.severeWeather.warningType;
			$(warning_option).each(function (value) {
				var warning_value = warning_option[value];
				$radios.filter('[value="' + warning_value + '"]').prop('checked', true);
			});
		}
		if (warning_option.length > 0) {
			load_severeForecast_content(warning_option, warning_type);
		}
		forecast_interval = setInterval(function () {
			update_forecast_severe();
		}, forecast_intervalTime);
		legendMainShow($('#forecast_codes_div'));
		$("#forecastwarningreportoption1").show();
		$('#forecastwarningreportoption2').show();
	}
}

function settingsSetSituationRoomSatellite() {
	if (settings.satellite !== null && typeof settings.satellite !== 'undefined' && settings.satellite.status != false) {
		$('#sat-enable').attr("checked", true);
		$("#sat-enable").trigger("change");
	}
}

function settingsSetSituationRoomMapOptions() {
	if (settings.mapOptions !== null && typeof settings.mapOptions !== 'undefined' && settings.mapOptions.status != false) {
		$("#map-options").attr("checked", true);
		if (settings.mapOptions.option == 'SATELLITE') {
			map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
			map.setOptions({ styles: "" });
		}
		else if (settings.mapOptions.option == 'TERRAIN') {
			map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
			map.setOptions({ styles: "" });
		}
		else if (settings.mapOptions.option == 'HYBRID') {
			map.setMapTypeId(google.maps.MapTypeId.HYBRID);
			map.setOptions({ styles: "" });
		}
		else if (settings.mapOptions.option == 'light_monochrome') {
			map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
			var mapMonochrome = {
				styles: [{ "featureType": "administrative.locality", "elementType": "all", "stylers": [{ "hue": "#2c2e33" }, { "saturation": 7 }, { "lightness": 19 }, { "visibility": "on" }] }, { "featureType": "landscape", "elementType": "all", "stylers": [{ "hue": "#ffffff" }, { "saturation": -100 }, { "lightness": 100 }, { "visibility": "simplified" }] }, { "featureType": "poi", "elementType": "all", "stylers": [{ "hue": "#ffffff" }, { "saturation": -100 }, { "lightness": 100 }, { "visibility": "off" }] }, { "featureType": "road", "elementType": "geometry", "stylers": [{ "hue": "#bbc0c4" }, { "saturation": -93 }, { "lightness": 31 }, { "visibility": "simplified" }] }, { "featureType": "road", "elementType": "labels", "stylers": [{ "hue": "#bbc0c4" }, { "saturation": -93 }, { "lightness": 31 }, { "visibility": "on" }] }, { "featureType": "road.arterial", "elementType": "labels", "stylers": [{ "hue": "#bbc0c4" }, { "saturation": -93 }, { "lightness": -2 }, { "visibility": "simplified" }] }, { "featureType": "road.local", "elementType": "geometry", "stylers": [{ "hue": "#e9ebed" }, { "saturation": -90 }, { "lightness": -8 }, { "visibility": "simplified" }] }, { "featureType": "transit", "elementType": "all", "stylers": [{ "hue": "#e9ebed" }, { "saturation": 10 }, { "lightness": 69 }, { "visibility": "on" }] }, { "featureType": "water", "elementType": "all", "stylers": [{ "hue": "#e9ebed" }, { "saturation": -78 }, { "lightness": 67 }, { "visibility": "simplified" }] }]
			};
			map.setOptions(mapMonochrome);
			$("#map_options_selector").find("option[value='light_monochrome']").attr("selected", true);
			settings.mapOptions.option = "light_monochrome";
		}
		else if (settings.mapOptions.option == 'ROADMAP') {
			map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
			map.setOptions({ styles: "" });
		}
		if (settings.mapOptions.weatherRegion == true) {
			$('#weatherregions').attr("checked", true);
			$('#weatherregions').trigger("change");
		}
		if (settings.mapOptions.catchments == true) {
			$('#riverbasins').attr("checked", true);
			$('#riverbasins').trigger("change");
		}
		if (settings.mapOptions.fireRegion == true) {
			// $('#checkbox-1-40').attr("checked", true);
			$('#firedistrict').attr("checked", true);
			$('#firedistrict').trigger("change");
		}
		// apply map type settings 
		if (typeof settings.mapOptions.option != "undefined") {
			$("#map_options_selector option[value='" + settings.mapOptions.option + "']").attr("selected", true);
		}
	}
}

function settingsSetSituationRoomLayers() {
	if (settings.dataLayer !== null && typeof settings.dataLayer !== 'undefined' && settings.dataLayer.status != false) {
		$("#checkbox-data-layers").attr("checked", true);
		if (typeof settings.dataLayer.publicLayer !== 'undefined') {
			var public_layers = [];
			if (settings.dataLayer.publicLayer !== '') {
				public_layers = settings.dataLayer.publicLayer;
				public_checked_options = public_layers;
				public_layer(public_layers);
			}
		}
		if (typeof settings.dataLayer.privateLayer !== 'undefined') {
			var client_layers = [];
			if (settings.dataLayer.privateLayer !== '') {
				client_layers = settings.dataLayer.privateLayer;
				client_checked_options = client_layers;
				client_layer(client_layers);
			}
		}
	}
	else if (LOGIN_USER_NAME == 'dexus') {
		var client_layers = [];
		client_layer(client_layers);
	}
}

function settingsSetSituationRoomForecastModel() {
	if (settings.modelSetting !== null && typeof settings.modelSetting !== 'undefined' && settings.modelSetting.status != false) {
		$('#checkbox-coming-soon').attr("checked", true);
		$('#checkbox-coming-soon').trigger("change");
	}
}

function logEWNCopyright() {
	//Lower Case
	console.log("    ___ __      __ ____  \n   / _ \\\\ \\ /\\ / /|  _ \\ \n  |  __/ \\ V  V / | | | |\n   \\___|  \\_/\\_/  |_| |_|\n   early warning network \n        © ewn 2015.");
}