var rainfall_layers = [];

function remove_all_rainfall() {
	var rainfall_option = [];
	rainfall_option = $('input:checkbox[name="rainfall_option[]"]').filter(':checked').map(function () {
		return this.value;
	}).get();
	for (var i = 0; i < rainfall_option.length; i++) {
		if (typeof rainfall_layers[rainfall_option[i]] !== 'undefined') {
			rainfall_layers[rainfall_option[i]].setMap(null);
		}
	}
	rainfall_layers.length = 0;
}

function rainfallChangeSingleCheckBox(id) {
	if (!id.is(":checked")) {
		id = id.val();
		rainfall_layers[id].setMap(null);
		delete rainfall_layers[id];
	}
	else {
		rainfallChangeSingle(id.val());
	}
}

function rainfallChangeSingle(id) {
	if (settings.rainfallGauges.status != false) {
		var rainfall_type = $('#rainfall_types').val();
		var d = Math.floor(Math.random() * 1000000000000000000000001);
		var url = 'http://clients3.ewn.com.au/common/obs/rainfall/' + rainfall_type + '/kml/' + id + '.kml?ref=' + d;
		var layer = new google.maps.KmlLayer(url, {
			preserveViewport: true,
			suppressInfoWindows: false
		});
		layer.setMap(map);
		rainfall_layers[id] = layer;
	}
	else {
		rainfall_layers[id].setMap(null);
		delete rainfall_layers[id];
	}
}

function rainfallChangeAll() {
	if ($(".rainfall").attr("checked")) {
		//pass in rainfall options
		var rainfall_option = [];
		rainfall_option = $('input:checkbox[name="rainfall_option[]"]').filter(':checked').map(function () {
			return this.value;
		}).get();
		if (settings.rainfallGauges.status != false) {
			$(rainfall_option).each(function (index) {
				if (settings.rainfallGauges.status != false) {
					rainfallChangeSingle(rainfall_option[index]);
				}
				else {
					remove_all_rainfall();
				}
			});
		}
	}
}