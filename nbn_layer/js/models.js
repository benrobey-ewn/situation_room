	var chart = 'cloud-low';
	var gstimeprev;
	var gstimecur;
	var gstimenext;
	var gfs = '';
	var modelmapprev;
	var modelmap;
	var modelmapnext;
	var modelson = '0';
	var modellistenerhandle;
	var init = 1;
	var loop_select_image = 0;
	var mapmodel;
	var models_looping = 0;
	var models_interval_handle;
	var modelsneLat;
	var modelsneLon;
	var modelsswLat;
	var modelsswLon;
	var gribdate = "";
	var models_slider_left = 0;

	var modeloverlayOpts = {
	  opacity:0.6
	}

	var modeloverlayOptsNext = {
	  opacity:0.0
	}	

	$( document ).ready(function() {
		$("#forecast_model").empty();
		$("#forecast_model").append('<option value="gfs">GFS</option>');
		$("#forecast_model").append('<option value="access-r">ACCESS-R</option>');
		$("#forecast_model").append('<option value="access-g">ACCESS-G</option>');

		$("#forstorm_speed_control").empty();
		$("#forstorm_speed_control").append('<option value="3000">Slow</option>');
		$("#forstorm_speed_control").append('<option value="1000">Normal</option>');
		$("#forstorm_speed_control").append('<option value="800">Fast</option>');
		
		$("#forecasts_types_select").empty();
		$("#forecasts_types_select").append('<option value="cloud-low">Cloud Cover - Low</option>');
		$("#forecasts_types_select").append('<option value="cloud-mid">Cloud Cover - Mid level</option>');
		$("#forecasts_types_select").append('<option value="cloud-high">Cloud Cover - High level</option>');
		$("#forecasts_types_select").append('<option value="cloud-total">Cloud cover - All Levels (Total)</option>');
		$("#forecasts_types_select").append('<option value="inversion">Low level inversions strength</option>');
		$("#forecasts_types_select").append('<option value="mslp">Rainfall (3 hours) & Surface Pressure</option>');
		$("#forecasts_types_select").append('<option value="precip">Rainfall Accumulation</option>');
		$("#forecasts_types_select").append('<option value="ts">Storm probability</option>');		
		$("#forecasts_types_select").append('<option value="li">Storm instability</option>');
		$("#forecasts_types_select").append('<option value="cape">Storm Energy</option>');
		$("#forecasts_types_select").append('<option value="gustssfc">Surface Wind Gusts</option>');		
		$("#forecasts_types_select").append('<option value="rhscreen">Surface Relative Humidity</option>');
		$("#forecasts_types_select").append('<option value="dp">Surface Dew Point</option>');
		$("#forecasts_types_select").append('<option value="tscreen">Temperature Surface</option>');
		$("#forecasts_types_select").append('<option value="t850">Temperature 850mb</option>');
		$("#forecasts_types_select").append('<option value="t700">Temperature 700mb</option>');
		$("#forecasts_types_select").append('<option value="t500">Temperature 500mb</option>');
		$("#forecasts_types_select").append('<option value="t300">Temperature 300mb</option>');
		$("#forecasts_types_select").append('<option value="s10m">Wind Speed/Direction Surface</option>');
		$("#forecasts_types_select").append('<option value="s850">Wind Speed/Direction 850mb</option>');
		$("#forecasts_types_select").append('<option value="s700">Wind Speed/Direction 700mb</option>');
		$("#forecasts_types_select").append('<option value="s500">Wind Speed/Direction 500mb</option>');
		$("#forecasts_types_select").append('<option value="s300">Wind Speed/Direction 300mb</option>');

		$("#checkbox-coming-soon" ).change(function() {
			if ($("#checkbox-coming-soon").prop("checked")===true) {
				togglemodels($("#forecast_model").val());
				settings.modelSetting.status = true;
			} else {
				if (models_looping == 1) {
					play_model_forecast();
				}
				
				modelson = '0';
				google.maps.event.removeListener(modellistenerhandle);
				deinitializemodels();
				settings.modelSetting.status = false;
			}
			settingsUpdate();
		});

		$("#forecast_model").change(function(event) {
			var models_cur_text = $("#forecasts_types_select").val();
			
			$("#forecasts_types_select").empty();
			
			if ($("#forecast_model").val() != "access-g") {
				$("#forecasts_types_select").append('<option value="cloud-low">Cloud Cover - Low</option>');
				$("#forecasts_types_select").append('<option value="cloud-mid">Cloud Cover - Mid level</option>');
				$("#forecasts_types_select").append('<option value="cloud-high">Cloud Cover - High level</option>');
				$("#forecasts_types_select").append('<option value="cloud-total">Cloud cover - All Levels (Total)</option>');
			}
			
			$("#forecasts_types_select").append('<option value="inversion">Low level inversions strength</option>');				
			$("#forecasts_types_select").append('<option value="mslp">Rainfall (3 hours) & Surface Pressure</option>');
			$("#forecasts_types_select").append('<option value="precip">Rainfall Accumulation</option>');

			if ($("#forecast_model").val() == "gfs") {
				$("#forecasts_types_select").append('<option value="ts">Storm probability</option>');
				$("#forecasts_types_select").append('<option value="li">Storm instability</option>');
				$("#forecasts_types_select").append('<option value="cape">Storm Energy</option>');
			}
			
			$("#forecasts_types_select").append('<option value="gustssfc">Surface Wind Gusts</option>');		
			$("#forecasts_types_select").append('<option value="rhscreen">Surface Relative Humidity</option>');
			$("#forecasts_types_select").append('<option value="dp">Surface Dew Point</option>');
			$("#forecasts_types_select").append('<option value="tscreen">Temperature Surface</option>');
			$("#forecasts_types_select").append('<option value="t850">Temperature 850mb</option>');
			$("#forecasts_types_select").append('<option value="t700">Temperature 700mb</option>');
			$("#forecasts_types_select").append('<option value="t500">Temperature 500mb</option>');
			$("#forecasts_types_select").append('<option value="t300">Temperature 300mb</option>');
			$("#forecasts_types_select").append('<option value="s10m">Wind Speed/Direction Surface</option>');
			$("#forecasts_types_select").append('<option value="s850">Wind Speed/Direction 850mb</option>');
			$("#forecasts_types_select").append('<option value="s700">Wind Speed/Direction 700mb</option>');
			$("#forecasts_types_select").append('<option value="s500">Wind Speed/Direction 500mb</option>');
			$("#forecasts_types_select").append('<option value="s300">Wind Speed/Direction 300mb</option>');

			for(i=0;i<$("#forecasts_types_select").get(0).length;i++) {
				if (models_cur_text == $("#forecasts_types_select").get(0).options[i].value) {
					$("#forecasts_types_select").get(0).selectedIndex = i;
				}
			}

			chart = $("#forecasts_types_select").val();
			togglemodels($("#forecast_model").val());
		});

		$("#forecast_module_opacity").change(function(event) {
			changemodelopacity($("#forecast_module_opacity").val());
		});

		$("#forecasts_types_select").change(function(event) {
			if ($("#checkbox-coming-soon").prop("checked")===true) {
				changemodelschart($("#forecasts_types_select").val());
			} else {
				chart = $("#forecasts_types_select").val();
			}
		});

		$("#forecast_module_datetime").change(function(event) {
			change_model_forecast_time('cur');
		});

		$("#forstorm_speed_control").change(function() {
			change_forecast_loop_speed();
		});

		$("#play_storm").click(function() {
			if ($("#checkbox-coming-soon").prop("checked")===true) {
				play_model_forecast();
			}
		});

		$("#prev_storm").click(function() {
			change_model_forecast_time('prev');
		});

		$("#next_storm").click(function() {
			change_model_forecast_time('next');
		});
	});

	function changemodelschart(newmodel) {
	  chart = newmodel;
	  generateModelChart("regenerate");
	}

	function generateModelChart(when) {
		var modelsne = map.getBounds().getNorthEast();
		var modelssw = map.getBounds().getSouthWest();

		var modelsneLat_val = modelsne.lat();
		var modelsneLon_val = modelsne.lng();
		var modelsswLat_val = modelssw.lat();
		var modelsswLon_val = modelssw.lng();

		if (modelsswLat_val < modelsswLat) { modelsswLat_val = modelsswLat; }
		if (modelsswLon_val < modelsswLon) { modelsswLon_val = modelsswLon; }
		if (modelsneLat_val > modelsneLat) { modelsneLat_val = modelsneLat; }
		if (modelsneLon_val > modelsneLon || modelsneLon_val < 0) { modelsneLon_val = modelsneLon; }

		var modelsswBound = new google.maps.LatLng(modelsswLat_val, modelsswLon_val);
		var modelsneBound = new google.maps.LatLng(modelsneLat_val, modelsneLon_val);

		var modelsbounds = new google.maps.LatLngBounds(modelsswBound, modelsneBound);
		 
		var modelurlprev = 'http://54.153.195.116/storage/scripts/make_grads_script.php?model=' + mapmodel + '&var=' + chart + '&region=&png=1&' + 'swLat=' + modelsswLat_val + '&swLon=' + modelsswLon_val + '&neLat=' + modelsneLat_val + '&neLon=' + modelsneLon_val + '&t=' + gstimeprev;
		var modelurl = 'http://54.153.195.116/storage/scripts/make_grads_script.php?model=' + mapmodel + '&var=' + chart + '&region=&png=1&' + 'swLat=' + modelsswLat_val + '&swLon=' + modelsswLon_val + '&neLat=' + modelsneLat_val + '&neLon=' + modelsneLon_val + '&t=' + gstimecur;
		var modelurlnext = 'http://54.153.195.116/storage/scripts/make_grads_script.php?model=' + mapmodel + '&var=' + chart + '&region=&png=1&' + 'swLat=' + modelsswLat_val + '&swLon=' + modelsswLon_val + '&neLat=' + modelsneLat_val + '&neLon=' + modelsneLon_val + '&t=' + gstimenext;

		$("#models-slider").css("left", models_slider_left + "%");
		
		if (when == "regenerate" || when == "prev" || when == "cur") {
			modelmap.setMap(null);
			modelmapnext.setMap(null);
			modelmap = new google.maps.GroundOverlay(modelurl,modelsbounds, modeloverlayOpts);
			modelmapnext = new google.maps.GroundOverlay(modelurlnext,modelsbounds, modeloverlayOptsNext);
			modelmap.setMap(map);
			modelmapnext.setMap(map);
			loop_select_image = 0;
			init = 0;
		} else {  
			if (init == 0) {
				// Map not loaded yet
				
				if (loop_select_image == 0) {
					modelmapnext.setOpacity(modeloverlayOpts.opacity);
					modelmap.setMap(null);
					modelmap = new google.maps.GroundOverlay(modelurlnext,modelsbounds, modeloverlayOptsNext);
					modelmap.setMap(map);
					loop_select_image = 1;
				} else {
					modelmap.setOpacity(modeloverlayOpts.opacity);
					modelmapnext.setMap(null);
					modelmapnext = new google.maps.GroundOverlay(modelurlnext,modelsbounds, modeloverlayOptsNext);
					modelmapnext.setMap(map);
					loop_select_image = 0;
				}
			} else {
				init = 0;
				modelmap = new google.maps.GroundOverlay(modelurl,modelsbounds, modeloverlayOpts);
				modelmapnext = new google.maps.GroundOverlay(modelurlnext,modelsbounds, modeloverlayOptsNext);
				modelmap.setMap(map);
				modelmapnext.setMap(map);
			}
		}

		console.log(modelurl);
	}

	function deinitializemodels() {
		modelmap.setMap(null);
		modelmapnext.setMap(null);
		init = 1;
		loop_select_image = 0;

		$("#forecast_module_datetime").empty();
		$("#forecast_module_datetime").append('<option value="">Select Date/Time</option>');
	}

	function changemodelopacity(opacity) {
		modeloverlayOpts.opacity = parseFloat(opacity);
		generateModelChart("next");
	}

	function play_model_forecast() {
		if (stripos($("#play_storm").attr('class'), 'pause', 0) === false) {
			$('#play_storm').removeClass("play").addClass('pause');
			models_looping = 1;
		} else {
			$('#play_storm').removeClass("pause").addClass('play');
			models_looping = 0;
		}

		if (models_looping == 1) {
			models_interval_handle = setInterval(do_forecast_loop, parseInt($('#forstorm_speed_control').val()));
			change_model_forecast_time('next');
		} else {
			clearInterval(models_interval_handle);
		}
	}

function change_forecast_loop_speed() {
	if (models_looping == 1) {
		clearInterval(models_interval_handle);
		models_interval_handle = setInterval(do_forecast_loop, parseInt($('#forstorm_speed_control').val()));
		change_model_forecast_time('next');
	}
}

	function do_forecast_loop() {
		change_model_forecast_time('next');
	}

	function stripos(f_haystack, f_needle, f_offset) {
		var haystack = (f_haystack + '')
		.toLowerCase();
		var needle = (f_needle + '')
		.toLowerCase();
		var index = 0;

		if ((index = haystack.indexOf(needle, f_offset)) !== -1) {
			return index;
		}
			return false;
		}

function change_model_forecast_time(when) {
	var models_cur_index = parseInt($("#forecast_module_datetime").get(0).selectedIndex);

	if (when == 'prev') {
		if (models_cur_index > 1) {
			models_cur_index = models_cur_index - 1;
		} else {
			models_cur_index = $("#forecast_module_datetime").get(0).length - 1;
		}
		models_slider_left = models_cur_index/($("#forecast_module_datetime").get(0).length-1) * 100;
	} else {
		if (models_cur_index < $("#forecast_module_datetime").get(0).length - 1) {
			models_cur_index = models_cur_index + 1;
		} else {
			models_cur_index = 1;
		}
		models_slider_left = models_cur_index/($("#forecast_module_datetime").get(0).length-1) * 100;
	}
	
	if (when == 'cur') {
		models_cur_index = parseInt($("#forecast_module_datetime").get(0).selectedIndex);
	} else {
		$("#forecast_module_datetime").get(0).selectedIndex = models_cur_index;
	}

	var models_prev_index = parseInt($("#forecast_module_datetime").get(0).selectedIndex);

	if (when == 'prev') {
		if (models_prev_index > 1) {
			models_prev_index = models_prev_index - 1;
		} else {
			models_prev_index = $("#forecast_module_datetime").get(0).length - 1;
		}
	} else {
		if (models_prev_index < $("#forecast_module_datetime").get(0).length) {
			if (models_prev_index == 1) { 
				models_prev_index = $("#forecast_module_datetime").get(0).length - 1;
			} else {
				models_prev_index = models_prev_index - 1;
			}
		} else {
			models_prev_index = 1;
		}
	}

	var models_next_index = parseInt($("#forecast_module_datetime").get(0).selectedIndex);

	if (when == 'prev') {
		if (models_next_index > 0) {
			if (models_next_index == $("#forecast_module_datetime").get(0).length - 1) {
				models_next_index = 1;
			} else {
				models_next_index = models_next_index + 1;
			}
		} else {
			models_next_index = $("#forecast_module_datetime").get(0).length - 2;
		}
	} else {
		if (models_next_index < $("#forecast_module_datetime").get(0).length - 1) {
			models_next_index = models_next_index + 1;
		} else {
			models_next_index = 1;
		}
	}

	setforecasttime(models_prev_index, models_cur_index, models_next_index, when);
}

	function setforecasttime(index, index1, index2, when) {
		gstimeprev = parseInt(index);
		gstimecur = parseInt(index1);
		gstimenext = parseInt(index2);
		generateModelChart(when);
	}

	function updatemodelforecasttimes() {
		$.ajax({
			url: "//54.153.195.116/storage/scripts/create_run_times.php?model=" + mapmodel,
			dataType: 'jsonp',
			async: false,
			jsonpCallback: 'addforecasttimes',
			jsonp: 'callback',
		});
	}

	function addforecasttimes(data) {
		for(i=1;i<data.length;i++) {
			if (i == parseInt(data[0])) {
				var itemval= '<option value="' + i + '" selected="">' + data[i] + '</option>';
				gstimecur = i;
				gstimenext = i+1;
				models_slider_left = (i/data.length)*100;
			} else {
				var itemval= '<option value="' + i + '">' + data[i] + '</option>';
			}

			$("#forecast_module_datetime").append(itemval);
		}
		$("#forecast_model_name").text("Forecast Model: " + format_grib_date(gribdate));
		generateModelChart("next");
	}

	function format_grib_date(thedates) {
				// 201510040620
		
		var retdate = thedates.substr(6, 2) + "/" + thedates.substr(4, 2) + "/" + thedates.substr(0, 4) + " " + thedates.substr(8, 2) + "z";
		return retdate
	}
	
	function togglemodels(themodel) {
		if (themodel == 'gfs') { mapmodel = 'gfs'; }
		if (themodel == 'access-r') { mapmodel = 'access-r'; }
		if (themodel == 'access-g') { mapmodel = 'access-g'; }
	  
		if (modelson == '0') {
			modelson = '1';
			updatemodelforecasttimes();

			modellistenerhandle = google.maps.event.addListener(map, 'idle', function() {
				if (models_looping == 1) {
					play_model_forecast();
				}
				//modelmap.setMap(null);
				//modelmapnext.setMap(null);
				init = 1;
				loop_select_image = 0;
				generateModelChart("next");
			});
			
		} else {
			if (models_looping == 1) {
				play_model_forecast();
			}
			
			if (themodel == "") {

			} else {
				deinitializemodels();
				updatemodelforecasttimes();
			}
		}
	}