var observation_marker;
var warning_interval;
//var warning_intervalTime = 300000;
 var warning_interval="";
 var getWarningXhr;
 var warning_intervalTime=30000;
 var WarningPolygon = {};

function load_warning_content(type, topics, warning_days) {

	var data = { record_type: type, topic_type: topics, warning_days: warning_days };
	if (data == null || data.record_type == null || data.topic_type == null || data.warning_days == null) {
		data = '{}';
	}
	$.ajax({
		type: "POST",
		url: "ajax/get_warning.php",
		dataType: "json",
		data: data,
		success: function(response) {
			if (settings.warnings.status == true) {
				latlongarray.length = 0;
				WarningPolygon = {};
				clearWarningInterval();
				remove_warning();
				for (var k = 0; k < response.data.length; k++) {
					var url = response.data[k].AlertFullURL;
					if (response.data[k].corodinates.length !== 0) {
						var coordinates_json = response.data[k].corodinates;
						draw_map(coordinates_json,response.data[k].color,response.data[k].AlertFullURL,parseInt(response.data[k].id),'warning',response.data[k].CreatedDate,response.data[k].Expires);
					}
				}
				if (show_asset_click) {
					polygon_show_range();
				}
				if(type==1){
					startWarningInterval();
				}
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			loadWarningContentError(jqXHR, textStatus, errorThrown);
		}
	});
}

function loadWarningContentError(jqXHR, textStatus, errorThrown) {
	console.error(textStatus + "  " + (errorThrown ? errorThrown : ''));
	showMessage(textStatus + "  " + (errorThrown ? errorThrown : ''));
}

function clear_range_markers() {
	if (filtermarkers) {
		for (var f = 0; f < filtermarkers.length; f++) {
			filtermarkers[f].setMap(null);
		}
	}
	show_asset_click = false;
	if (!show_forecast_asset_click) {
		redraw_layer();
	}
}

function remove_filter_marker() {
	if (filtermarkers.length > 0) {
		for (var f = 0; f < filtermarkers.length; f++) {
			filtermarkers[f].setMap(null);
		}
	}
	show_asset_click = false;
}

function remove_warning() {
	if (filtermarkers) {
		for (var f = 0; f < filtermarkers.length; f++) {
			filtermarkers[f].setMap(null);
		}
	}
	if (warningMarkersArray) {
		for (var i = 0; i < warningMarkersArray.length; i++) {
			warningMarkersArray[i].setMap(null);
			polygonMarkerArray[i].setMap(null);
		}
	}
	warningMarkersArray.length = 0;
	polygonMarkerArray.length = 0;
	my_polygon_markers.length = 0;
	latlongarray.length = 0;
	minLat = 1000;
	minLong = 1000;
	maxLat = -1000;
	maxLong = -1000;
	map.controls[google.maps.ControlPosition.TOP_LEFT].clear();
}

function redraw_layer() {
	hide_layer_markers();
	var layer_value=$('#layers').val();
	load_client_layer(client_checked_options);
	load_public_layer(public_checked_options);
}

function hide_layer_markers() {
	MDSWA.setMap(null);
	waternsw.setMap(null);
	datach1.setMap(null);
	datach2.setMap(null);
	if (multi_kml_marker_array.length > 0) {
		for (var i = 0; i < multi_kml_marker_array.length; i++) {
			multi_kml_marker_array[i].setVisible(false);
		}
	}
}

function HomeControl(controlDiv, map) {
	controlDiv.style.padding = '5px';
	var controlUI = document.createElement('div');
	controlUI.className = "hide_alerts";
	controlDiv.appendChild(controlUI);
	var controlText = document.createElement('div');
	controlText.innerHTML = '<b>Hide Alerts<b>';
	controlUI.appendChild(controlText);

	google.maps.event.addDomListener(controlUI, 'click', function() {
		$(".weather").attr("checked",false);
		remove_warning();
	});
}

//remove single warning
function remove_warning_single(id) {
    if (typeof warningobjectMarkersArray[id] != 'undefined') {
		warningobjectMarkersArray[id].setMap(null);
		var index = my_polygon_markers.indexOf(id);
		if (index > -1) {
			my_polygon_markers.splice(index, 1);
		}
	}
}

//load warning
function load_initial_warning() {
	$('input[name="warning_option[]"]').each(function() {
		$(this).attr("checked",true);
	});
	weather_options.push('all');
	load_warning_content(2,weather_options,48);

	warning_interval=setInterval(function() {
		update_warning();
	}
	,warning_intervalTime);
}

//function for update warning
/*function update_warning() {
	if ($(".weather").attr("checked")) {
		var $checkes = $('input:checkbox[name="warning_option[]"]');
		weather_options = $checkes.filter(':checked').map(function () {
			return this.value;
		}).get();
		if (weather_options.length == 0) {
			return false;
		}
		else {
			remove_warning();
			$('input[name="warning_type"]:checked').each(function() {
				var warning_type = this.value;
				if (warning_type =='1') { 
					load_warning_content(1,weather_options,'');
				}
				else if (warning_type == '2') {
					var days=$('#warning_days').val();
					load_warning_content(2,weather_options,days);
				}
			});
		}
	}
}*/

function getCurrentUTCDateTime(){
     var dtd = new Date(); 
     var daycheck =  (dtd.getUTCDate() >= 10) ? dtd.getUTCDate() : "0"+dtd.getUTCDate();
     var hourcheck = (dtd.getUTCHours() >= 10) ? dtd.getUTCHours(): "0"+dtd.getUTCHours();
     var mincheck = (dtd.getUTCMinutes() >= 10) ? dtd.getUTCMinutes(): "0"+dtd.getUTCMinutes();
     var seccheck = (dtd.getUTCSeconds() >= 10) ? dtd.getUTCSeconds(): "0"+dtd.getUTCSeconds();
     
     return dtd.getUTCFullYear()  +"-"+ (dtd.getUTCMonth()+1)  +"-"+ daycheck + " "  + hourcheck + ":"+ mincheck +":"+ seccheck;
   
  }
 
 function parseDate(str)
 {
  var s = str.split(" "),
  d = s[0].split("-"),
  t = s[1].replace(/:/g, "");
  return d[0] + d[1] + d[2] + t;
}      

function update_warning(){
  /*var currentLocalDate = getCurrentUTCDateTime();
  for(var expiry in WarningPolygon){
   console.log(expiry +" > "+ currentLocalDate);
    if(parseDate(currentLocalDate) >  parseDate(expiry) ){
     WarningPolygon[expiry].setMap(null);
     delete WarningPolygon[expiry];
    }
  }*/
}

function clearWarningInterval(){
   clearInterval(warning_interval);
}


function startWarningInterval(){
  warning_interval=setInterval(function() {
    update_warning();
  },warning_intervalTime);  
}

function set_warning_setting(warning_type, warning_options, days) {
	//check warning option
	if (warning_options.length != 0) {
		load_warning_content(warning_type, warning_options, days);
		 if(warning_type!=2){
     	 	startWarningInterval();
  		 } else{
  		 	clearWarningInterval();
  		 }
	}
}

function liveUpdateWarning(){
	socket.on("get_new_warning",function(data){
	 if($(".weather").attr("checked")){
	   var update_warning_type = $(".warning_type:checked").val()
	   var warningdays = '';
	   var weather_options = $('input:checkbox[name="warning_option[]"]').filter(':checked').map(function () { return this.value; }).get();
	  // console.log("weather_options " + weather_options  );
	   if(update_warning_type == 2){
	   	warningdays = $("#warning_days").val();
	   } 
	//   console.log('warning type ' + update_warning_type);
	//   console.log("warning days " + warningdays);
	   load_warning_content(update_warning_type,weather_options,warningdays);
	 } 
	})
}

// node get realtime warning updates
$(document).ready(function() {
 //	liveUpdateWarning();
});