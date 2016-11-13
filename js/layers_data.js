var dynamic_create_array = [];
var public_checked_options = [];
var client_checked_options = [];
var RailwayTracks = [];
var total_loaded_layer = [];
var current_selected_layer = [];
var unselected_layer = [];
var multi_kml_marker_array = [];
var global_layer_marker_counter = 0;
var client_layer_count = 0;
var client_loaded_count = 0;
var public_loaded_count = 0;
var global_request = [];
var newParserObj = "";
var public_layers_data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
var mixed_layer_array = $.merge(client_layers, public_layers_data);

$(document).ready(function () {
	$('#showClientDataDropDownDiv').hide();
	$('#select_public_data').change(function () {
	}).multipleSelect({
		placeholder: 'Select Public Layer Data',
		width: '100%',
		minimumCountSelected: 50,
		onCheckAll: function () {
			if (show_asset_click) {
				clear_range_markers();
			}
			unselected_layer.length = 0;
			public_layer_count = 10;
			public_loaded_count = public_checked_options.length;
			$('#public_data_layer').attr("checked", true);
			$('#showPublicDataDropDownDiv input:checked').each(function () {
				if (this.value !== 'on') {
					if (this.value == '2') {
						var text = $(this).parent().text();
						if ($("#public_show_sites_layers option[value='" + this.value + "']").length <= 0) {
							$('#public_show_sites_layers')
							 .append($("<option></option>")
							 .attr("value", this.value)
							 .text(text));
						}
					}
					if ((jQuery.inArray(this.value, current_selected_layer)) == -1) {
						if ((jQuery.inArray(this.value, total_loaded_layer)) != -1) {
							// layer already loaded
							getLoadedLayer(this.value, 'public');
						}
						else {
							total_loaded_layer.push(this.value);
							dynamic_create_array = eval("dynamic_create_array" + this.value + "=[]"); // Create dynamic array variable
							load_layer(this.value, 'public');
						}
						current_selected_layer.push(this.value);
						public_checked_options.push(this.value);
					}
				}
			});
			$('.assets_report').prop('disabled', false);
			$('.report_disabled').hide();
			settings.dataLayer.publicLayer = public_checked_options;
			settingsUpdate();
		},
		onUncheckAll: function () {
			if (show_asset_click) {
				clear_range_markers();
			}
			public_layer_count = 0;
			public_loaded_count = 0;
			// Get all checkboxes of public
			for (b = 0; b < public_checked_options.length; b++) {
				clear_markers_layer_datas(parseInt(public_checked_options[b]));
				var removeItem = parseInt(public_checked_options[b]);
				current_selected_layer = jQuery.grep(current_selected_layer, function (value) {
					return value != removeItem;
				});
				$('#navigation' + removeItem).hide();
			}
			$('#public_site_list_option').hide();
			$('#public_show_sites_layers').find('option').remove().end();
			public_checked_options.length = 0;
			settings.dataLayer.publicLayer = public_checked_options;
			settingsUpdate();
			$('#public_data_layer').attr("checked", false);
		},
		onClick: function (view) {
			$('#public_data_layer').attr("checked", true);
			if (show_asset_click) {
				clear_range_markers();
			}
			// view consists object of label , value , checked
			if (view.checked == true) {
				public_layer_count = 1;
				public_loaded_count = 0;
				if (view.value == '2' && (LOGIN_USER_NAME == 'telstra' || LOGIN_USER_NAME == 'admin')) {
					$('#public_site_list_option').show();
					if ($("#public_show_sites_layers option[value='" + view.value + "']").length <= 0) {
						$('#public_show_sites_layers')
						 .append($("<option></option>")
						 .attr("value", view.value)
						 .text(view.label));
					}
				}
				$('.assets_report').prop('disabled', false);
				$('.report_disabled').hide();
				if ((jQuery.inArray(view.value, total_loaded_layer)) != -1) {
					// layer already loaded
					getLoadedLayer(view.value, 'public');
				} else {
					total_loaded_layer.push(view.value);
					dynamic_create_array = eval("dynamic_create_array" + view.value + "=[]"); // Create dynamic array variable
					load_layer(view.value, 'public');
				}
				current_selected_layer.push(view.value);
				public_checked_options.push(view.value);
				settings.dataLayer.publicLayer = public_checked_options;
				settingsUpdate();
			}
			else {
				public_layer_count = 0;
				public_loaded_count = 0;
				if ($('#navigation' + view.value).length > 0) {
					$('#navigation' + view.value).hide();
					hide_public_sites();
				}
				var removeItem = view.value;
				if ((jQuery.inArray(view.value, unselected_layer)) == -1) {
					// layer already loaded
					unselected_layer.push(view.value);
				}
				current_selected_layer = jQuery.grep(current_selected_layer, function (value) {
					return value != removeItem;
				});
				public_checked_options = jQuery.grep(public_checked_options, function (value) {
					return value != removeItem;
				});
				clear_markers_layer_datas(removeItem);
				if (removeItem == '2' && (LOGIN_USER_NAME == 'telstra' || LOGIN_USER_NAME == 'admin')) {
					$('#public_site_list_option').hide();
					$("#public_show_sites_layers option[value='" + removeItem + "']").remove();
				}
				var common = $.grep(current_selected_layer, function (el) {
					return $.inArray(parseInt(el), mixed_layer_array) !== -1;
				});
				// this is without loop 
				if (common.length == 0) {
					$('.assets_report').prop('disabled', true);
					$('.report_disabled').show();
				}
				settings.dataLayer.publicLayer = public_checked_options;
				settingUpdate();
			}
		}
	});

	$('#select_client_data').change(function () {
		$('#ok_layer_data').show();
	}).multipleSelect({
		placeholder: 'Select Client Layer Data',
		width: '100%',
		minimumCountSelected: 50,
		onCheckAll: function () {
			//remove_filter_marker();
			if (show_asset_click) {
				clear_range_markers();
			}
			var total_client = 0;
			$('#showClientDataDropDownDiv input:checkbox').each(function () {
				if (this.value !== 'on') {
					total_client++;
				}
			});
			$('#client_data_layer').attr("checked", true);
			client_layer_count = total_client;
			client_loaded_count = client_checked_options.length;
			$('#showClientDataDropDownDiv input:checked').each(function () {
				var e = $(this);
				e = e.value;
				if(e == 'undefined' || e === undefined ){
					e = $(this).val();	
				}
				if (e !== 'on') {
					if ((jQuery.inArray(e, current_selected_layer)) == -1) {
						if (e !== '12' && e !== '19' && e !== '38' && e !== '39' && e !== '45' && e !== '46' && e !== '47' && e !== '52' && e !== '53' && e !== '94' && e !== '95' && e !== '96' && e !== '100' && e !== '101' && e !== '102' && e !== '104' ) {
							$('#site_list_option').show();
							var text = $(this).parent().text();
							if ($("#show_sites_layers option[value='" + e + "']").length <= 0) {
								$('#show_sites_layers').append($("<option></option>").attr("value", e).text(text));
							}
							// enable reporting 
							if (forecast_latlongarray.length == 0 && latlongarray.length == 0) {
								$('.assets_report').prop('disabled', true);
							}
							else {
								$('.assets_report').prop('disabled', false);
							}
							$('.report_disabled').hide();
						}
						if ((jQuery.inArray(e, total_loaded_layer)) != -1) {
							// layer already loaded
							getLoadedLayer(e, 'client');
						}
						else {
							total_loaded_layer.push(e);
							dynamic_create_array = eval("dynamic_create_array" + e + "=[]"); // Create dynamic array variable
							load_layer(e, 'client');
						}
						current_selected_layer.push(e);
						client_checked_options.push(e);
					}
				}
			});
			if(settings.datalayer.publicLayer!== 'undefined'){
				settings.datalayer.publicLayer = client_checked_options;
				settingsUpdate();
			}
		},
		onUncheckAll: function () {
			// Get all checkboxes of public
			client_layer_count = 0;
			client_loaded_count = 0
			if (show_asset_click) {
				clear_range_markers();
			}
			for (b = 0; b < client_checked_options.length; b++) {
				clear_markers_layer_datas(parseInt(client_checked_options[b]));
				var removeItem = parseInt(client_checked_options[b]);
				current_selected_layer = jQuery.grep(current_selected_layer, function (value) {
					return value != removeItem;
				});
				$('#navigation' + removeItem).hide();
			}
			client_checked_options = [];
			$('#show_sites_layers').find('option').remove().end();
			$('#site_list_option').hide();
			$('#client_data_layer').attr("checked", false);
			$('.assets_report').prop('disabled', true);
			$('.report_disabled').show();
			settings.dataLayer.privateLayer = client_checked_options;
			settingsUpdate();
		},
		onClick: function (view) {
			var e = $(this);
			e = e.value;
			if (show_asset_click) {
				clear_range_markers();
			}
			$('#client_data_layer').attr("checked", true);
			// view consists object of label , value , checked
			if (view.checked == true) {
				client_layer_count = 1;
				client_loaded_count = 0;
				if (view.value !== '12' && view.value !== '19' && view.value !== '38' && view.value !== '39' && view.value !== '45' && view.value !== '46' && view.value !== '47' && view.value !== '52' && view.value !== '53' && view.value !== '94' && view.value !== '95' && view.value !== '96' && view.value !== '100' && view.value !== '101' && view.value !== '102' && view.value !== '104' ) {
					$('#site_list_option').show();
					if ($("#show_sites_layers option[value='" + view.value + "']").length <= 0) {
						$('#show_sites_layers').append($("<option></option>").attr("value", view.value).text(view.label));
					}
					// enable reporting 
					if (forecast_latlongarray.length == 0 && latlongarray.length == 0) {
						$('.assets_report').prop('disabled', true);
					}
					else {
						$('.assets_report').prop('disabled', false);
					}
					$('.report_disabled').hide();
				}
				if ((jQuery.inArray(view.value, total_loaded_layer)) != -1) {
					// layer already loaded
					getLoadedLayer(view.value, 'client');
				}
				else {
					total_loaded_layer.push(view.value);
					dynamic_create_array = eval("dynamic_create_array" + view.value + "=[]"); // Create dynamic array variable
					load_layer(view.value, 'client');
				}
				current_selected_layer.push(view.value);
				client_checked_options.push(view.value);
				settings.dataLayer.privateLayer = client_checked_options;
				settingsUpdate();
			}
			else {
				client_layer_count = 0;
				client_loaded_count = 0;
				var removeItem = view.value;
				$("#show_sites_layers option[value='" + removeItem + "']").remove();
				if ($('#navigation' + view.value).length > 0) {
					$('#navigation' + view.value).hide();
					hide_sites();
				}
				if(view.value=='94'){
					newParserObj.hideDocument();
				}
				if(view.value=='95'){
					newParserObj.hideDocument();
				}
				if(view.value=='100'){
					CORE_NETWORK_SITES.setMap(null);
					
				}
				if(view.value=='101'){
					NFAS_SITES.setMap(null);
					
				}
				if(view.value=='102'){
					NWAS_SITES.setMap(null);
					
				}
				
				if(view.value=='104'){
					NRC_PIPELINES.setMap(null);
					
				}
				
				if(view.value=='96'){
					EDL_SITES.setMap(null);
				}
				
				if ((jQuery.inArray(view.value, unselected_layer)) == -1) {
					// layer already loaded
					unselected_layer.push(view.value);
				}
				current_selected_layer = jQuery.grep(current_selected_layer, function (value) {
					return value != removeItem;
				});
				client_checked_options = jQuery.grep(client_checked_options, function (value) {
					return value != removeItem;
				});
				clear_markers_layer_datas(removeItem);
				var common = $.grep(current_selected_layer, function (el) {
					return $.inArray(parseInt(el), mixed_layer_array) !== -1;
				});
				if (common.length == 0) {
					$('.assets_report').prop('disabled', true);
					$('.report_disabled').show();
				}
				if(public_checked_options[0]=='1' && public_checked_options.length == 1){
					$('.assets_report').prop('disabled', true);
				}
				if ($('#site_list_option option').length >= 1) {
					$('#site_list_option').find("option[value='" + view.value + "']").remove();
				}
				else {
					$('#site_list_option').hide();
				}
				settings.dataLayer.privatelayer = client_checked_options;
				settingsUpdate();
			}
		}
	});
});

function showHideRTLayerData(type){
	 	RT_VIC4.setMap(type);
	 	RT_VIC5.setMap(type);
	 	RT_QLD1.setMap(type);
	 	RT_QLD2.setMap(type);
	 	RT_QLD3.setMap(type);
	 	RT_QLD5.setMap(type);
	 	RT_NSW1.setMap(type);
	 	RT_NSW2.setMap(type);
	 	RT_QLD7.setMap(type);
	 	RT_QLD8.setMap(type);
	 	RT_WA1.setMap(type);
	 	RT_WA2.setMap(type);
	 	RT_VIC1.setMap(type);
}

function newLayerParser(){
	newParserObj = new geoXML3.parser({ 
		map: map,
		processStyles: true,
			zoom: false,
		singleInfoWindow: true,
		suppressInfoWindows:false,
	});
}

// newly coded
function measureRailwayTrackZoom(zoomLevel){
	if($("#public_data_layer").is(":checked") && $(".data_layers_drop_down").find("input[value=1][type=checkbox]").is(":checked")){
		if(RailwayTracks.length > 0){
			if(zoomLevel>=9) {
				if(markerShownOrNot!=true){
					for(var i =0; i < RailwayTracks.length; i++){
						RailwayTracks[i].setVisible(true);
					}
					markerShownOrNot = true;
				} 
			} else{
				if(markerShownOrNot!=false){
					for(var i =0; i < RailwayTracks.length; i++){
						RailwayTracks[i].setVisible(false);
					}
					markerShownOrNot = false;
				}
			} 
		}
	}
}
// END newly coded

function showPublicDataDropdown() {
	if ($('#public_data_layer').attr("checked")) {
		$('.main_public_controller').show();
		$('#showPublicDataDropDownDiv').show();
		$('.data_layers_drop_down').show();
		$('#select_public_data').hide();
	}
	else {
		hide_public_sites();
		if(infowindow!="" && typeof infowindow!=undefined){
			infowindow.close();
		}
		public_layer_count = 0;
		remove_filter_marker();
		remove_forecast_filter_marker();
		// Get all checkboxes of public
		for (b = 0; b < public_checked_options.length; b++) {
			clear_markers_layer_datas(parseInt(public_checked_options[b]));
			var removeItem = parseInt(public_checked_options[b]);
			current_selected_layer = jQuery.grep(current_selected_layer, function (value) {
				return value != removeItem;
			});
			$('#navigation' + removeItem).hide();
		}
		public_checked_options.length = 0;
		$('#public_data_layer').attr("checked", false);
		$('#showPublicDataDropDownDiv').hide();
		$('.main_public_controller').hide();
		$('#showPublicDataDropDownDiv').find('input[type=checkbox]:checked').removeAttr('checked');
		$('#showPublicDataDropDownDiv').find('span').addClass("placeholder");
		$('#showPublicDataDropDownDiv').find('span').html('Select Public Layer Data');
		var common = $.grep(current_selected_layer, function (el) {
			return $.inArray(parseInt(el), mixed_layer_array) !== -1;
		});
		if (common.length == 0) {
			$('.assets_report').prop('disabled', true);
			$('.report_disabled').show();
		}
		settings.dataLayer.publicLayer = public_checked_options;
		settingsUpdate();
		$('#public_site_list_option').hide();
		$('#public_show_sites_layers').find('option').remove().end();
	}
}

function showClientDataDropDown() {
	if ($('#client_data_layer').attr("checked")) {
		$('.main_client_controller').show();
		$('#showClientDataDropDownDiv').show();
		$('.data_layers_drop_down_client').show();
		$('#select_client_data').hide();
	}
	else {
		hide_sites();
		if (infowindow != null && typeof infowindow!=undefined){
			infowindow.close();
		}
		client_layer_count = 0;
		$('#site_list_option').hide();
		$('#show_sites_layers').find('option').remove().end();
		remove_filter_marker();
		remove_forecast_filter_marker();
		// Get all checkboxes of public
		for (b = 0; b < client_checked_options.length; b++) {
			clear_markers_layer_datas(parseInt(client_checked_options[b]));
			var removeItem = parseInt(client_checked_options[b]);
			current_selected_layer = jQuery.grep(current_selected_layer, function (value) {
				return value != removeItem;
			});
			$('#navigation' + removeItem).hide();
		}
		client_checked_options.length = 0;
		// Get all checkboxes of public
		//HUME_HIGHWAY.setMap(null);
		if(newParserObj!=""  && newParserObj.docs!="" && newParserObj.docs.length > 0){
			newParserObj.hideDocument();
		}
		$('#client_data_layer').attr("checked", false);
		//Get checked values
		$('#showClientDataDropDownDiv').hide();
		$('.data_layers_drop_down_client').hide();
		$('.main_client_controller').hide();
		$('#showClientDataDropDownDiv').find('input[type=checkbox]:checked').removeAttr('checked').trigger('change');
		$('#showClientDataDropDownDiv').find('span').addClass("placeholder");
		$('#showClientDataDropDownDiv').find('span').html('Select Client Layer Data');
		var common = $.grep(current_selected_layer, function (el) {
			return $.inArray(parseInt(el), mixed_layer_array) !== -1;
		});
		if (common.length == 0) {
			$('.assets_report').prop('disabled', true);
			$('.report_disabled').show();
		}
		if(public_checked_options[0]=='1' && public_checked_options.length == 1){
			$('.assets_report').prop('disabled', true);
		}
		$('.navigation').hide();
		settings.dataLayer.privatelayer = client_checked_options;
		settingsUpdate();
	}
}

function clear_markers_layer_datas(layer_id) {
	if (layer_id !== 4 && layer_id !== 12 && layer_id !== 38) {
		var my_data_value = eval("dynamic_create_array" + parseInt(layer_id));
		if (my_data_value.length > 0) {
			$('#loader_layer_type').show();
			for (i = 0; i <= my_data_value.length - 1; i++) {
				my_data_value[i].setVisible(false);
				if (i == my_data_value.length - 1) {
					$('#loader_layer_type').hide();
				}
			}
		}
	}
	if (layer_id == 1) {
		showHideRTLayerData(null)
		$('#loader_layer_type').hide();
	}
	if (layer_id == 35) {
		waternsw.setMap(null);
	}
	if (layer_id == 12) {
		remove_post_code();
		$('#loader_layer_type').hide();
	}
	if (layer_id == 19) {
		MDSWA.setMap(null);
	}
	if (layer_id == 38) {
		GWA.setMap(null);
	}
	if (layer_id == 39) {
		datach1.setMap(null);
		chaininfowindow.close();
	}
	if (layer_id == 45) {
		OUTAGE_UNPLANNED.setMap(null);
		clearInterval(OUTAGE_UNPLANNED_Interval);
		$('#outages_div').hide();
	}
	if (layer_id == 46) {
		PUBLIC_OUTAGES.setMap(null);
		clearInterval(PUBLIC_OUTAGES_Interval);
	}
	if (layer_id == 47) {
		PUBLIC_OUTAGES_FUTURE.setMap(null);
		clearInterval(PUBLIC_OUTAGES_FUTURE_Interval);
	}
	// BCC
	if (layer_id == 52) {
		BCC.setMap(null);
	}
	//ARTC Layer
	if (layer_id == 53) {
		ARTC.setMap(null);
	}
	// ARTC Chainage Layer
	if (layer_id == 64) {
		chaininfowindowartc.close();
	}
}

function load_layer(layer_values, layer_type) {
	var element = '';
	if (layer_type == 'public') {
		element = $('#public_data_layer');
	}
	else {
		element = $('#client_data_layer');
	}
	// Check array consists 1 or not
	if (layer_values == 1) {
		//show_loader(layer_type);
		showHideRTLayerData(map)
	}
	if(client_checked_options.length == 0 && public_checked_options.length == 1 ) {
		$('.assets_report').prop('disabled', true);
	}
	if (layer_values == 12) {
		show_post_code();
	}
	// MDSWA layer
	if (layer_values == 19) {
		if (element.is(':checked'))
			MDSWA.setMap(map);
	}
	if (layer_values == 35) {
		if (element.is(':checked'))
			waternsw.setMap(map);
	}
	if (layer_values == 38) {
		if (element.is(':checked'))
			GWA.setMap(map);
	}
	if (layer_values == 45) {
		if (element.is(':checked')) {
			OUTAGE_UNPLANNED.setMap(map);
			refresh_kml_layer(layer_values);
			$('#outages_div').show();
		}
	}
	if (layer_values == 46) {
		if (element.is(':checked')) {
			PUBLIC_OUTAGES.setMap(map);
			refresh_kml_layer(layer_values);
		}
	}
	if (layer_values == 47) {
		if (element.is(':checked')) {
			PUBLIC_OUTAGES_FUTURE.setMap(map);
			refresh_kml_layer(layer_values);
		}
	}
	//BCC Layer
	if (layer_values == 52) {
		if (element.is(':checked'))
			BCC.setMap(map);

	}
	//ARTC Layer
	if (layer_values == 53) {
		if (element.is(':checked'))
			ARTC.setMap(map);
	}
	if (layer_values == 39) {
		datach1.loadGeoJson(chainageurl);
		// Set event listener for each feature.
		datach1.addListener('mouseover', function (event) {
			var fetproper = event.feature.getProperty("ROUTE_CHAI");
			chaininfowindow.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper + 'km </b>' + '</div>');
			chaininfowindow.setPosition(event.latLng);
			chaininfowindow.setOptions({ pixelOffset: new google.maps.Size(0, -34) });
			chaininfowindow.open(map);
		});
		datach1.setMap(map);
	}
	if(layer_values=='94'){
		newLayerParser();
	   newParserObj.parse(HUME_HIGHWAY);
	}
	
	if(layer_values==95){
		newLayerParser();
		newParserObj.parse(UTILITY_LINES);
	}
	if(layer_values==100){
		CORE_NETWORK_SITES.setMap(map);
	}
	if(layer_values==101){
		NFAS_SITES.setMap(map);
	}
	if(layer_values==102){
		NWAS_SITES.setMap(map);
	}
	
	if(layer_values==104){
		NRC_PIPELINES.setMap(map);
		console.log(NRC_PIPELINES);
	}
	
	if(layer_values==96){
		EDL_SITES.setMap(map);
	}
	show_loader(layer_type);
	var ajaxobj = null;
	ajaxobj = $.ajax
	({
		type: "POST",
		url: "ajax/get_layer_data.php",
		dataType: "json",
		data: { layer_type: layer_values },
		// we are checking the code for the file exists or not
		statusCode: {
			404: function () {
				//$("#spnStatus").html("Sorry file not found!");
			}
		},

		// error function  we are checking timeout,
		// if its taking more than 5 seconds from server to response
		// send the error message to spnStatus tag
		error: function (xhr, textStatus, errorThrown) {
			if (textStatus == 'timeout') {
				$("#spnStatus").html("Error : Current call has been timeout");
			}
		},
		success: function (response) {
			var html = "";
			var html1 = "";
			var html2 = "";
			var loaded_count;
			var total;
			global_request[response.layer_id] = 'loaded';
			if (parseInt(response.layer_id) <= 10) {
				public_loaded_count = public_loaded_count + 1;
				loaded_count = public_loaded_count;
				total = public_layer_count;
			}
			else {
				client_loaded_count = client_loaded_count + 1;
				loaded_count = client_loaded_count;
				total = client_layer_count;
			}
			var layer_type = '';
			if (parseInt(response.layer_id) <= 10) {
				layer_type = 'public';
			}
			else {
				layer_type = 'client';
			}
			if (response.data.length > 0) {
				var count = response.data.length - 1;
				for (k = 0; k < response.data.length; k++) {
					var myLatlng = new google.maps.LatLng(response.data[k].latitude, response.data[k].longitude);
					// newly coded 
					var $layer_marker_options = {
						position: myLatlng,
						title: response.data[k].placemarker_name,
						icon:'images/layer_markers/'+response.data[k].placemarker_icon
					};
					getZoom = map.getZoom();
					if (response.data[k].layer_id == 1) {
						if (getZoom >= 9) {
							$layer_marker_options.visible = true;
						} 
						else {
							$layer_marker_options.visible = false;
						}
					}
					var layer_marker_new = new google.maps.Marker($layer_marker_options);
					RailwayTracks.push(layer_marker_new);
					// END newly coded 
					var element = '';
					var container = '';
					var layer_type = '';
					// element
					if (parseInt(response.data[k].layer_id) <= 10) {
						element = $('#public_data_layer');
						container = $('#showPublicDataDropDownDiv');
						layer_type = 'public';
					}
					else {
						element = $('#client_data_layer');
						container = $('#showClientDataDropDownDiv');
						layer_type = 'client';
					}
					if (element.is(':checked') && container.find("input[type=checkbox]").filter('[value="' + response.data[k].layer_id + '"]').is(':checked')) {
						layer_marker_new.setMap(map);
					}
					multi_kml_marker_array.push(layer_marker_new);
					eval("dynamic_create_array" + response.data[k].layer_id).push(layer_marker_new);
					  if(infowindow == null){
					  	infowindow = new google.maps.InfoWindow();
					  }
					google.maps.event.addListener(layer_marker_new, 'click', (function (marker, k) {
						return function () {
							var contentString = response.data[k].placemarker_description;
							infowindow.setContent(contentString);
							infowindow.open(map, marker);
						}
					})(layer_marker_new, k));
					if (parseInt(response.data[k].layer_id) == 64) {
						google.maps.event.addListener(layer_marker_new, 'mouseover', (function (marker, k) {
							return function () {
								var fetproper2 = response.data[k].placemarker_name;
								chaininfowindowartc.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper2 + '</b>' + '</div>');
								var myLatlng = new google.maps.LatLng(response.data[k].latitude, response.data[k].longitude);
								chaininfowindowartc.setPosition(myLatlng);
								chaininfowindowartc.open(map, marker);
							}
						})(layer_marker_new, k));
					}
					if ((parseInt(response.data[k].layer_id) > 10 || parseInt(response.data[k].layer_id) == 2) && response.data[k].placemarker_name != "") {
						var tag = '<a style="cursor:pointer" onclick=setlocation(' + response.data[k].latitude + ',' + response.data[k].longitude + ',' + global_layer_marker_counter + ') >' + response.data[k].placemarker_name + '</a>';
						html += "<li>" + tag + "</li>";
					}
					if (k == count) {
						if (loaded_count >= total)
							hide_loader(layer_type);
					}
					global_layer_marker_counter++;
				}
				if ((parseInt(layer_values) > 10 || (parseInt(layer_values) == 2 && (LOGIN_USER_NAME == 'telstra' || LOGIN_USER_NAME == 'admin'))) && (parseInt(layer_values) !== 12 && parseInt(layer_values) !== 19 && parseInt(layer_values) !== 94 && parseInt(layer_values) !== 95 && parseInt(layer_values) !== 96 && parseInt(layer_values) !== 100 && parseInt(layer_values) !== 101 && parseInt(layer_values) !== 102 && parseInt(layer_values) !== 104 )) {
					if (element.is(':checked') && container.find("input[type=checkbox]").filter('[value="' + layer_values + '"]').is(':checked')) {
						if (parseInt(layer_values) !== 2 && parseInt(layer_values) !== 39) {
							$('#site_list_option').show();
						}
						else {
							$('#public_site_list_option').show();
						}
					}
				}
				$('#navigation_site_' + layer_values).append(html);
			}
			else {
				if (loaded_count >= total)
					hide_loader(layer_type);
			}
		}
	});
}

function getLoadedLayer(layer_id, layer_type) {
	var element = '';
	show_loader(layer_type);
	if (parseInt(layer_id) <= 10) {
		element = $('#public_data_layer');
	}
	else {
		element = $('#client_data_layer');
	}
	if (layer_id != 12 && layer_id != 19  && layer_id!=1){
		var get_again = eval("dynamic_create_array" + layer_id);
		if (get_again.length != 0) {
			for (m = 0; m <= get_again.length - 1; m++) {
				if (element.is(':checked')) {
					get_again[m].setVisible(true);
				}
				if (m == get_again.length - 1) {
					var loaded_count;
					var total;
					var layer_type = '';
					if (parseInt(layer_id) <= 10) {
						public_loaded_count++;
						loaded_count = public_loaded_count;
						total = public_layer_count;
						layer_type = 'public';
					} else {
						client_loaded_count++;
						loaded_count = client_loaded_count;
						total = client_layer_count;
						layer_type = 'client';
					}
					if (loaded_count >= total) {
						hide_loader(layer_type);
					}
				}
			}
		}
		else {
			var loaded_count;
			var total;
			var layer_type = '';
			if (parseInt(layer_id) <= 10) {
				public_loaded_count++;
				loaded_count = public_loaded_count;
				total = public_layer_count;
				layer_type = 'public';
			}
			else {
				client_loaded_count++;
				loaded_count = client_loaded_count;
				total = client_layer_count;
				layer_type = 'client';
			}
			if (loaded_count >= total) {
				hide_loader(layer_type);
			}
		}
	}
	if (layer_id == 12) {
		client_loaded_count++;
		if (element.is(':checked')) {
			show_post_code();
		}
		if (client_loaded_count >= client_layer_count) {
			hide_loader('client');
		}
	}
	// MDSWA layer
	if (layer_id == 19) {
		client_loaded_count++;
		if (element.is(':checked')) {
			MDSWA.setMap(map);
		}
		if (client_loaded_count >= client_layer_count) {
			hide_loader('client');
		}
	}
	if (layer_id == 45) {
		if (element.is(':checked')) {
			$('#outages_div').show();
			OUTAGE_UNPLANNED.setMap(map);
			refresh_kml_layer(layer_id);
		}
	}
	if (layer_id == 46) {
		if (element.is(':checked')) {
			PUBLIC_OUTAGES.setMap(map);
			refresh_kml_layer(layer_id);
		}
	}
	if (layer_id == 47) {
		if (element.is(':checked')) {
			PUBLIC_OUTAGES_FUTURE.setMap(map);
			refresh_kml_layer(layer_id);
		}
	}
	//BCC Layer
	if (layer_id == 52) {
		if (element.is(':checked'))
			BCC.setMap(map);
	}
	//ARTC Layer
	if (layer_id == 53) {
		if (element.is(':checked'))
			ARTC.setMap(map);
	}
	if (layer_id == 35) {
		if (element.is(':checked'))
			waternsw.setMap(map);
	}
	if (layer_id == 38) {
		if (element.is(':checked'))
			GWA.setMap(map);
	}
	if (layer_id == 1) {
		show_loader(layer_type);
		showHideRTLayerData(map)
		setTimeout(function() {
			hide_loader(layer_type);
		},5000);
		if (client_checked_options.length == 0 && public_checked_options.length == 1 ){
			$('.assets_report').prop('disabled', true);
		}
	}
	if (layer_id == 39) {
		//GWA_Chainage.setMap(map);
		//GWA GeoJson Layers
		datach1.loadGeoJson(chainageurl);
		// Set event listener for each feature.
		datach1.addListener('mouseover', function (event) {
			var fetproper = event.feature.getProperty("ROUTE_CHAI");
			var description = fetproper.split("-");
			chaininfowindow.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper + 'km </b>' + '</div>');
			chaininfowindow.setPosition(event.latLng);
			chaininfowindow.setOptions({ pixelOffset: new google.maps.Size(0, -34) });
			chaininfowindow.open(map);
		});
		datach1.setMap(map);
	}
	if (layer_id == 94){
		 newParserObj.showDocument();
	}
	if (layer_id == 95){
		EDL_SITES.setMap(map);
	}
	
	if (layer_id == 96){
		newParserObj.showDocument();
	}
	
	if(layer_id==100){
		CORE_NETWORK_SITES.setMap(map);
	}
	
	if(layer_id==101){
		NFAS_SITES.setMap(map);
	}
	
	if(layer_id==102){
		NWAS_SITES.setMap(map);
	}
	
	if(layer_id==104){
		NRC_PIPELINES.setMap(map);
	}
	
	hide_loader(layer_type);
}
function show_loader(layer_type) {
	$("div.map-data-layers").find(".heading-name").after('<span class="ajaxwaitobj gauge-load"><i class=" fa fa-spinner fa-pulse icon-spin fa-2x"></i></span>');
	if (layer_type == 'client') {
		showAjaxWait($('#client_data_layer'), 'client_layer_loader');
		$("#client_data_layer").attr("disabled", true);
	}
	else {
		showAjaxWait($('#public_data_layer'), 'public_layer_loader');
		$("#public_data_layer").attr("disabled", true);
	}
	$('.bottom :input').attr('disabled', true);
}

function hide_loader(layer_type) {
	$("div.map-data-layers").find('span.ajaxwaitobj.gauge-load').remove();
	if (layer_type == 'client') {
		hideAjaxWait('client_layer_loader');
		$("#client_data_layer").attr("disabled", false);
	}
	else {
		hideAjaxWait('public_layer_loader');
		$("#public_data_layer").attr("disabled", false);
	}
	if ($('.client_layer_loader').length == 0 && $('.public_layer_loader').length == 0) {
		$('.bottom :input').attr('disabled', false);
	}
}

function client_layer(client_layers) {
	// setting for dexus client 
	if (LOGIN_USER_NAME == 'dexus') {
		dynamic_create_array = eval("dynamic_create_array" + 15 + "=[]"); // Create dynamic array variable
		load_layer(15, 'client');
		if ((jQuery.inArray("15", client_layers)) == -1) {
			client_layers.push("15");
		}
	}
	var notinlayers = [];
	var total_client_layer = 0;
	for (z = 0; z <= client_layers.length - 1; z++) {
		if ($('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="' + client_layers[z] + '"]').length <= 0) {
			notinlayers.push(client_layers[z]);
		}
	}
	$('#showClientDataDropDownDiv input:checkbox').each(function () {
		if (this.value !== 'on') {
			total_client_layer++;
		}
	});
	for (z = 0; z <= notinlayers.length - 1; z++) {
		client_layers = jQuery.grep(client_layers, function (value) {
			return value != notinlayers[z];
		});

		client_checked_options = jQuery.grep(client_checked_options, function (value) {
			return value != notinlayers[z];
		});

	}
	// setting for dexus client
	if (client_layers.length > 0) {
		$('#client_data_layer').prop("checked", true);
		var new_text_values = '';
		client_layer_count = client_layers.length;
		for (z = 0; z <= client_layers.length - 1; z++) {
			layer_value = client_layers[z];
			if ($('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="' + client_layers[z] + '"]').length > 0) {
				$('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="' + client_layers[z] + '"]').prop("checked", true);
				if ((jQuery.inArray(client_layers[z], current_selected_layer)) == -1) {
					current_selected_layer.push(client_layers[z]);
					total_loaded_layer.push(client_layers[z]);
				}
				var text = $('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="' + client_layers[z] + '"]').parent().text();
				new_text_values = new_text_values + ' , ' + text;
				new_text_values = $.trim(new_text_values);
				new_text_values = new_text_values.replace(/^,|,$/g, '');
				dynamic_create_array = eval("dynamic_create_array" + client_layers[z] + "=[]"); // Create dynamic array variable
				load_layer(client_layers[z], 'client');
				// show sites
				if (client_layers[z] !== '12' && client_layers[z] !== '19' && client_layers[z] !== '38' && client_layers[z] !== '39' && client_layers[z] !== '45' && client_layers[z] !== '46' && client_layers[z] !== '47' && client_layers[z] !== '52' && client_layers[z] !== '53' && client_layers[z] !== '94' && client_layers[z] !== '95' && client_layers[z] !== '96' && client_layers[z] !== '100' && client_layers[z] !== '101' && client_layers[z] !== '102' && client_layers[z] !== '104') {
					$('.assets_report').prop('disabled', false);
					$('.report_disabled').hide();
					$('#site_list_option').show();

					if ($("#show_sites_layers option[value='" + client_layers[z] + "']").length <= 0) {
						$('#show_sites_layers').append($("<option></option>").attr("value", client_layers[z]).text(text));
					}
				}
			}
		}
		$('#showClientDataDropDownDiv').find('span').removeClass("placeholder");
		$('#showClientDataDropDownDiv').find('span').addClass("ms-choice");
		$('#showClientDataDropDownDiv').find('span').html(new_text_values);
		$('.main_client_controller').show();
		$('#showClientDataDropDownDiv').show();
		$('.data_layers_drop_down_client').show();
		$('#select_client_data').hide();
		if (client_layers.length == total_client_layer) {
			$('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="on"]').prop("checked", true);
		}
		settings.dataLayer.status = true;
		settingsUpdate();
	}
}

function public_layer(public_layers) {
	if (public_layers.length > 0) {
		$('#public_data_layer').prop("checked", true);
		var new_text_values = '';
		public_layer_count = public_layers.length;
		for (p = 0; p <= public_layers.length - 1; p++) {
			$('#showPublicDataDropDownDiv').find("input[type=checkbox]").filter('[value="' + public_layers[p] + '"]').prop("checked", true);
			if ((jQuery.inArray(public_layers[p], current_selected_layer)) == -1) {
				current_selected_layer.push(public_layers[p]);
				total_loaded_layer.push(client_layers[p]);
			}
			var text = $('#showPublicDataDropDownDiv').find("input[type=checkbox]").filter('[value="' + public_layers[p] + '"]').parent().text();
			new_text_values = new_text_values + ' , ' + text;
			new_text_values = $.trim(new_text_values);
			new_text_values = new_text_values.replace(/^,|,$/g, '');
			dynamic_create_array = eval("dynamic_create_array" + public_layers[p] + "=[]"); // Create dynamic array variable
			load_layer(public_layers[p], 'public');
						if(public_layers[p]!='1' && public_checked_options.length != 1  ){
			$('.assets_report').prop('disabled', false);
			$('.report_disabled').hide();
						}
			// show sites
			if (public_layers[p] == '2' && (LOGIN_USER_NAME == 'telstra' || LOGIN_USER_NAME == 'admin')) {
				$('#public_site_list_option').show();
				if ($("#public_show_sites_layers option[value='" + public_layers[p] + "']").length <= 0) {
					$('#public_show_sites_layers').append($("<option></option>").attr("value", public_layers[p]).text(text));
				}
			}
		}
		$('#showPublicDataDropDownDiv').find('span').removeClass("placeholder");
		$('#showPublicDataDropDownDiv').find('span').addClass("ms-choice");
		$('#showPublicDataDropDownDiv').find('span').html(new_text_values);
		$('.main_public_controller').show();
		$('#showPublicDataDropDownDiv').show();
		$('.data_layers_drop_down').show();
		$('#select_public_data').hide();
		if (public_layer_count == 10) {
			$('#showPublicDataDropDownDiv').find("input[type=checkbox]").filter('[value="on"]').prop("checked", true);
		}
		settings.dataLayer.status = true;
		settingsUpdate();
	}
}

function load_client_layer(client_checked_options) {
	client_layer_count = 1;
	$(client_checked_options).each(function (value) {
		if (client_checked_options[value] !== 'on') {
			if ((jQuery.inArray(client_checked_options[value], current_selected_layer)) != -1) {
				// layer already loaded
				getLoadedLayer(client_checked_options[value], 'client');
			}
		}
	});
}

function load_public_layer(public_checked_options) {
	public_layer_count = 1;
	$(public_checked_options).each(function (value) {
		if (public_checked_options[value] !== 'on') {
			if ((jQuery.inArray(public_checked_options[value], current_selected_layer)) != -1) {
				// layer already loaded
				getLoadedLayer(public_checked_options[value], 'public');
			}
		}
	});
}

// OUTAGE_UNPLANNED_Timeout;
var OUTAGE_UNPLANNED_Interval;
var PUBLIC_OUTAGES_Interval;
var PUBLIC_OUTAGES_FUTURE_Interval;
var OUTAGES_REFRESH_INTERVAL = 600000;

// function for refresh kml layer
function refresh_kml_layer(layer_id) {
	// OUTAGES UNPLANNED layer refresh
	if (layer_id == 45) {
		OUTAGE_UNPLANNED_Interval = setInterval(function () {
			OUTAGE_UNPLANNED_Timeout.setMap(null);
			var d = Math.floor(Math.random() * 1000000000000000000000001);
			var url = OUTAGE_UNPLANNED + '?ref=' + d;
			OUTAGE_UNPLANNED = new google.maps.KmlLayer(url, {
				preserveViewport: true,
				suppressInfoWindows: false
			});
			OUTAGE_UNPLANNED.setMap(map);
		}, OUTAGES_REFRESH_INTERVAL);
	}
	// PUBLIC OUTAGES layer refresh
	if (layer_id == 46) {
		PUBLIC_OUTAGES_Interval = setInterval(function () {
			PUBLIC_OUTAGES.setMap(null);
			var d = Math.floor(Math.random() * 1000000000000000000000001);
			var url = PUBLIC_OUTAGES_URL + '?ref=' + d;
			PUBLIC_OUTAGES = new google.maps.KmlLayer(url, {
				preserveViewport: true,
				suppressInfoWindows: false
			});
			PUBLIC_OUTAGES.setMap(map);
		}, OUTAGES_REFRESH_INTERVAL);
	}
	// PUBLIC_OUTAGES_FUTURE layer refresh
	if (layer_id == 47) {
		PUBLIC_OUTAGES_FUTURE_Interval = setInterval(function () {
			PUBLIC_OUTAGES_FUTURE.setMap(null);
			var d = Math.floor(Math.random() * 1000000000000000000000001);
			var url = PUBLIC_OUTAGES_FUTURE_URL + '?ref=' + d;
			PUBLIC_OUTAGES_FUTURE = new google.maps.KmlLayer(url, {
				preserveViewport: true,
				suppressInfoWindows: false
			});
			PUBLIC_OUTAGES_FUTURE.setMap(map);
		}, OUTAGES_REFRESH_INTERVAL);
	}
}