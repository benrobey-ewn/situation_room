var rainfall_layers = [];
var rainfall_option = ['0', '9.9', '24.9', '50.0', '99.9', '199.9', '200.0'];
var rainfall_loaded_layer = 0;
var rainfall_count = 0;

function show_rainfall() {
/*
	if ($(".rainfall").attr("checked")) {
		showAjaxWait($('.rainfall'), 'rainfall_loader');
		$("input.rainfall_checkboxes").attr("disabled", true);
		$("input#checkbox-rainfall").attr("disabled", true).hide();
		remove_all_rainfall();
		var timeoutdiff = 0;
		var rainfall_options = [];
		rainfall_options = $('input:checkbox[name="rainfall_option[]"]').filter(':checked').map(function () {
			return this.value;
		}).get();
		var rainfall_type = $('#rainfall_types').val();
		if (rainfall_type !== '') {
			rainfall_count = rainfall.option.length;
			$(rainfall_option).each(function (value) {
				var d = Math.floor(Math.random() * 1000000000000000000000001);
				var url = 'http://clients3.ewn.com.au/common/obs/rainfall/' + rainfall_type + '/kml/' + rainfall_option[value] + '.kml?ref=' + d;
				var layer = new google.maps.KmlLayer(url, {
					preserveViewport: true,
					suppressInfoWindows: false,
					pane: "floatPane",
				});
				if (value > 0) {
					timeoutID = window.setTimeout(function () {
						layer.setMap(map);
						rainfall_hide_loader(layer, 'multi');
					}, timeoutdiff);
					//layer.setMap(map);
				}
				timeoutdiff = timeoutdiff + 2000;
				//rainfall_layers.push(layer);
				rainfall_layers[rainfall_option[value]] = layer;
			});
			$('input[name="rainfall_option[]"]').each(function () {
				$(this).attr("checked", true);
			});
			$('input:checkbox[name="rainfall_option[]"]').filter('[value="0"]').prop('checked', false);
			*/

	update_rainfall();

	//	}
	//}
}

function rainfall_hide_loader(layer, type) {
	// map bound change event
	google.maps.event.addListener(layer, "metadata_changed", function () {
		if (type == 'single') {
			hideAjaxWait('rainfall_loader');
			$("input.rainfall_checkboxes").removeAttr("disabled");
			$("input#checkbox-rainfall").attr("disabled", false).show();
		} else {
			rainfall_loaded_layer++;
			if (parseInt(rainfall_loaded_layer) == parseInt(rainfall_count)) {
				$("input.rainfall_checkboxes").removeAttr("disabled");
				$("input#checkbox-rainfall").attr("disabled", false).show();
				hideAjaxWait('rainfall_loader');
			}
		}
	});
}

function remove_all_rainfall() {
	for (var i = 0; i < rainfall_option.length; i++) {
		//console.log(rainfall_layers[rainfall_option[i]]);
		if (typeof rainfall_layers[rainfall_option[i]] !== 'undefined') {
			rainfall_layers[rainfall_option[i]].setMap(null);
		}
	}
	rainfall_layers.length = 0;
}

function remove_rainfall(id) {
	if (typeof rainfall_layers[id] != 'undefined') {
		rainfall_layers[id].setMap(null);
	}
}


function toggleKML(checked, id) {
	if ($(".rainfall").attr("checked")) {
		if (checked) {
			showAjaxWait($('.rainfall', 'rainfall_loader'));
			$("input.rainfall_checkboxes").attr("disabled", true);
			$("input#checkbox-rainfall").attr("disabled", true).hide();
			var rainfall_type = $('#rainfall_types').val();
			var d = Math.floor(Math.random() * 1000000000000000000000001);
			var url = 'http://clients3.ewn.com.au/common/obs/rainfall/' + rainfall_type + '/kml/' + id + '.kml?ref=' + d;
			var layer = new google.maps.KmlLayer(url, {
				preserveViewport: true,
				suppressInfoWindows: false
			});
			layer.setMap(map);
			rainfall_hide_loader(layer, 'single');
			rainfall_layers[id] = layer;
		} else {
			rainfall_layers[id].setMap(null);
			delete rainfall_layers[id];
		}
		var weather_options = [];
		weather_options = $('input:checkbox[name="rainfall_option[]"]').filter(':checked').map(function () {
			return this.value;
		}).get();
		settings.rainfallGauges.options = weather_options;
		settingsUpdate();
		$("input.rainfall_checkboxes").attr("disabled", false);
		$("input#checkbox-rainfall").attr("disabled", false).show();
	}
}

function update_rainfall() {
	if ($(".rainfall").attr("checked")) {
		showAjaxWait($('.rainfall'), 'rainfall_loader');
		$("input.rainfall_checkboxes").attr("disabled", true);
		$("input#checkbox-rainfall").attr("disabled", true).hide();
		remove_all_rainfall();
		var timeoutdiff = 0;
		var rainfall_option = [];
		rainfall_option = $('input:checkbox[name="rainfall_option[]"]').filter(':checked').map(function () {
			return this.value;
		}).get();
		var rainfall_type = $('#rainfall_types').val();
		if (rainfall_type !== '') {
			rainfall_count = rainfall_option.length;
			$(rainfall_option).each(function (value) {
				var d = Math.floor(Math.random() * 1000000000000000000000001);
				var url = 'http://clients3.ewn.com.au/common/obs/rainfall/' + rainfall_type + '/kml/' + rainfall_option[value] + '.kml?ref=' + d;
				var layer = new google.maps.KmlLayer(url, {
					preserveViewport: true,
					suppressInfoWindows: false,
					pane: "floatPane",
				});
				//layer.setMap(map);
				//rainfall_layers.push(layer);
				timeoutID = window.setTimeout(function () {
					layer.setMap(map);
					rainfall_hide_loader(layer, 'multi');
				}, timeoutdiff);
				timeoutdiff = timeoutdiff + 2000;
				rainfall_layers[rainfall_option[value]] = layer;
			});
		}
		$("input.rainfall_checkboxes").attr("disabled", false);
		$("input#checkbox-rainfall").attr("disabled", false).show();
		settings.rainfallGauges.status = true;
		settings.rainfallGauges.type = rainfall_type;
		settings.rainfallGauges.options = rainfall_option;
		settingsUpdate();
		hideAjaxWait('rainfall_loader');
	}
}