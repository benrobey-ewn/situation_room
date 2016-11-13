var river_guage_layers = [];
var river_guage_options = ['nofloodorclass', 'minor', 'moderate', 'major'];
var loaded_layer = 0;
var newcount = 0;
// not found 
function show_river_guages() {
	//$('#loader_river').show();
	$("input.river_guage_checkboxes").attr("disabled", true);
	$("input#checkbox-river-guage").attr("disabled", true).hide();
	remove_all_river_guages('1');
	var timeoutdiff = 0;
	showAjaxWait($('.river-guage'), 'river_loader');
	newcount = 0;
	loaded_layer = 0;
	newcount = 4;

	$(river_guage_options).each(function (value) {
		var d = Math.floor(Math.random() * 1000000000000000000000001);
		var url = 'http://clients3.ewn.com.au/common/obs/rainfall/rivers/kml/' + river_guage_options[value] + '.kml?ref=' + d;
		//	console.log('url'+url);
		var layer = new google.maps.KmlLayer(url, {
			preserveViewport: true,
			suppressInfoWindows: false
		});
		if (river_guage_options[value] != '') {
			timeoutID = window.setTimeout(function () {
				// Remove loader
				if (river_guage_options.length == newcount) {
					//$('#loader_observation').hide();
				}
				//console.log('1245');
				layer.setMap(map);
				river_hide_loader(layer, 'multi');
			}, timeoutdiff);
		}
		timeoutdiff = timeoutdiff + 2000;
		river_guage_layers[river_guage_options[value]] = layer;
		//newcount++;
	});

	$('input[name="river_guage_options[]"]').each(function () {
		$(this).attr("checked", true);
	});

	var rivers_options = [];
	rivers_options = $('input:checkbox[name="river_guage_options[]"]').filter(':checked').map(function () {
		return this.value;
	}).get();

	//console.log("Here testing ".rivers_options);
	setting[9].status = 'on';
	setting[9].options = rivers_options;
	update_setting();
	//$('input:checkbox[name="river_guage_options[]"]').filter('[value="0"]').prop('checked', false);
}

function river_hide_loader(layer, type) {
	// map bound change event
	google.maps.event.addListener(layer, "metadata_changed", function () {
		if (type == 'single') {
			hideAjaxWait('river_loader');
			$("input.river_guage_checkboxes").removeAttr("disabled");
			$("input#checkbox-river-guage").attr("disabled", false).show();
		} else {
			loaded_layer++;
			//console.log('Loaded>>>>'+loaded_layer+'total'+newcount);
			if (parseInt(loaded_layer) == parseInt(newcount)) {
				//console.log('Loaded>>>>'+loaded_layer+'total'+newcount);
				//$('#loader_river').hide();
				hideAjaxWait('river_loader');
				$("input.river_guage_checkboxes").removeAttr("disabled");
				$("input#checkbox-river-guage").attr("disabled", false).show();
			}
		}
	});
}

function remove_all_river_guages(type) {
	for (var i = 0; i < river_guage_options.length; i++) {
		if (typeof river_guage_layers[river_guage_options[i]] !== 'undefined') {
			river_guage_layers[river_guage_options[i]].setMap(null);
		}
	}
	/*
	if (type == 1) {
		$('input[name="river_guage_options[]"]').each(function() {
			$(this).attr("checked",false);
		});
	}
	*/
	river_guage_layers.length = 0;
}



function remove_rainfall(id) {
	if (typeof river_guage_layers[id] != 'undefined') {
		river_guage_layers[id].setMap(null);
	}
}

function toggleRiverGuageKML(checked, id) {
	if ($(".river-guage").attr("checked")) {
		if (checked) {
			showAjaxWait($('.river-guage'), 'river_loader');
			$("input.river_guage_checkboxes").attr("disabled", true);
			$("input#checkbox-river-guage").attr("disabled", true).hide();
			//var rainfall_type=$('#rainfall_types').val();
			var d = Math.floor(Math.random() * 1000000000000000000000001);
			var url = 'http://clients3.ewn.com.au/common/obs/rainfall/rivers/kml/' + id + '.kml?ref=' + d;
			//console.log('url'+url);
			var layer = new google.maps.KmlLayer(url,
			{
				preserveViewport: true,
				suppressInfoWindows: false
			});
			layer.setMap(map);
			river_hide_loader(layer, 'single');
			river_guage_layers[id] = layer;
		}
		else {
			river_guage_layers[id].setMap(null);
			delete river_guage_layers[id];
		}
		var rivers_options = [];
		rivers_options = $('input:checkbox[name="river_guage_options[]"]').filter(':checked').map(function () {
			return this.value;
		}).get();
		setting[9].options = rivers_options;
		update_setting();
	}
}

function update_river_guages() {
	if ($(".river-guage").attr("checked")) {
		$("input.river_guage_checkboxes").attr("disabled", true);
		$("input#checkbox-river-guage").attr("disabled", true).hide();
		showAjaxWait($('.river-guage'), 'river_loader');
		//$('#loader_river').show();
		//console.log('update_river_guages');
		//var river_guage_layers=[];
		remove_all_river_guages('2');
		var timeoutdiff = 0;
		loaded_layer = 0;
		var river_guage_options = [];
		river_guage_options = $('input:checkbox[name="river_guage_options[]"]').filter(':checked').map(function () {
			return this.value;
		}).get();
		newcount = river_guage_options.length;
		$(river_guage_options).each(function (value) {
			//console.log('here also');
			var d = Math.floor(Math.random() * 1000000000000000000000001);
			//var url='http://clients3.ewn.com.au/common/obs/rainfall/rivers/kml/'+river_guage_options[value]+'.kml?ref='+d;
			var url = 'http://clients3.ewn.com.au/common/obs/rainfall/rivers/kml/' + river_guage_options[value] + '.kml?ref=' + d;
			//	console.log('url'+url);
			var layer = new google.maps.KmlLayer(url, {
				preserveViewport: true,
				suppressInfoWindows: false
			});
			timeoutID = window.setTimeout(function () {
				layer.setMap(map);
				river_hide_loader(layer, 'multi');
			}, timeoutdiff);
			timeoutdiff = timeoutdiff + 2000;
			river_guage_layers[river_guage_options[value]] = layer;
		});
		legendMainShow($('#river_gauges_codes_div'));
	}
}