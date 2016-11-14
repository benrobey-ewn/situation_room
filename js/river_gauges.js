var river_guage_layers = [];
var river_guage_options = ['nofloodorclass', 'minor', 'moderate', 'major'];
// var river_guage_options=['minor'];
var river_guage_detector;
var timestamp_arr = [];
var hashMarker = [];
var riverGaugeCompleteData = ['nofloodorclass', 'minor', 'moderate', 'major'];
var myParser = "";
var globalTimeStamp = "";
var Geoxml3 = "";
var riverGaugeNewInfoWindow = "";
var clickedMarkerArr = [];
var rawMarkers = [];
var IconRequestURL = "http://clients3.ewn.com.au/common/images/kml/rainfall/";

var CURRENT_ID = false;
var CURRNT_GAUGE = '';

var hardcodedKML = "";

// for live server 
var kmlRequestURL = "http://clients3.ewn.com.au/common/obs/rainfall/rivers/kml/";
var jsonRequestURL = "http://clients3.ewn.com.au/common/obs/rainfall/rivers/json/";
var RefeshLink = "http://clients3.ewn.com.au/common/obs/rainfall/rivers/json/dyn/havechanged.php?ts=";
// for dev server 
/*var kmlRequestURL='js/rkmls/';
var jsonRequestURL='js/json/';
var RefeshLink = "js/rkmls/havechanged.php?ts=";*/

function getRandomMathValue() {
	return Math.floor(Math.random() * 1000000000000000000000001);
}

function getMyParser() {
	myParser = new geoXML3.parser({
		map: map,
		processStyles: true,
		createMarker: addMyMarker,
		afterParse: river_gauge_success,
		failedParse: river_gauge_failed,
		zoom: false,
		singleInfoWindow: true,
		suppressInfoWindows: true,
	});
}

function show_river_guages(riverCheckedBoxes) {
	if (settings.riverGauges.status != false) {
		toggleKmlSingle = false;
		river_guage_show();
		var random = getRandomMathValue();
		CURRENT_ID = true;
		getMyParser();
		$.each(riverCheckedBoxes, function (value) {
			myParser.parse(kmlRequestURL + riverCheckedBoxes[value] + "-rev01.kml?ref=" + random);
		});
	}
}

function addMyMarker(myMarker) {
	if (settings.riverGauges.status != false && CURRENT_ID) {
		var match_marker = myParser.createMarker(myMarker);
		var markerID = myMarker.id;
		if (hashMarker[markerID] === undefined) {
			hashMarker[markerID] = match_marker;
			rawMarkers[markerID] = myMarker;
		}
	}
	else {
		riverGaugeRemoveSingle(CURRNT_GAUGE);
	}
}

function river_gauge_success(markerDoc) {
	if (settings.riverGauges.status != false && CURRENT_ID) {
		river_guage_show();
		Geoxml3 = markerDoc[0];
		lastMarker = Geoxml3.placemarks.length;
		if (lastMarker > 0) {
			var temp_name = Geoxml3.baseUrl.replace(kmlRequestURL, "");
			temp_name = temp_name.replace("-rev01.kml", "");
			if (Geoxml3.placemarks[lastMarker - 1].node.nextElementSibling.id !== undefined) {
				timestamp_arr[temp_name] = Geoxml3.placemarks[lastMarker - 1].node.nextElementSibling.id;
			}
			if (riverGaugeCompleteData[temp_name] === undefined) {
				riverGaugeCompleteData[temp_name] = [];
			}
			var tempid = "";
			for (var i = 0; i <= (Geoxml3.placemarks.length) - 1; i++) {
				tempid = Geoxml3.placemarks[i].id;
				riverGaugeCompleteData[temp_name].push(tempid);
				bindPlacemarkClickEvent(hashMarker[tempid], tempid);
			}
			river_guage_hide();
		}
		else {
			if (toggleKmlSingle) {
				river_guage_hide();
			}
			toggleKmlSingle = false;
		}
		globalTimeStamp = Math.min.apply(null, Object.keys(timestamp_arr).map(function (e) {
			return timestamp_arr[e];
		}));
	}
	else {
		riverGaugeRemoveSingle(CURRNT_GAUGE);
	}
}

function river_guage_hide() {
	//hideAjaxWait('river_loader');
	//$("input.river_guage_checkboxes").removeAttr("disabled");
	//$("input#checkbox-river-guage").attr("disabled", false).show();
}

function river_guage_show() {
//	showAjaxWait($('.river-guage'), 'river_loader');
//	$("input.river_guage_checkboxes").attr("disabled", true);
//	$("input#checkbox-river-guage").attr("disabled", true).hide();
}

function bindPlacemarkClickEvent(placemark, requestID) {
	google.maps.event.addListener(placemark, "click", function () {
		if (clickedMarkerArr[requestID] === undefined) {
			$.ajax({
				url: jsonRequestURL + requestID + ".json",
				type: 'GET',
				dataType: 'JSON',
				success: function (data) {
					if (settings.riverGauges.status != false) {
						if (riverGaugeNewInfoWindow != "") {
							riverGaugeNewInfoWindow.close();
						}
						riverGaugeNewInfoWindow = new google.maps.InfoWindow({
							content: data.description
						});
						//START updating the marker object.
						var imageMarkerAndIcon = getMarkerIcon(data.icon);
						if (hashMarker[requestID] !== undefined) {
							hashMarker[requestID].setMap(null);
							delete hashMarker[requestID];
							rawMarkers[requestID].name = data.name;
							rawMarkers[requestID].styleUrl = data.icon;
							rawMarkers[requestID].style.href = imageMarkerAndIcon;
							rawMarkers[requestID].style.icon.url = imageMarkerAndIcon;
							var tempMarkerParsed = myParser.createMarker(rawMarkers[requestID]);
							hashMarker[requestID] = tempMarkerParsed;
							bindPlacemarkClickEvent(hashMarker[requestID], requestID);
						}
						//END updating the marker object.
						riverGaugeNewInfoWindow.open(map, placemark);
						clickedMarkerArr[requestID] = riverGaugeNewInfoWindow;
					}
				},
				fail: function (jqXHR, textStatus, errorThrown, thrownError) {
					riverGaugeAjaxError(jqXHR, textStatus, errorThrown, thrownError);
				}
			})
		} else {
			for (var index in clickedMarkerArr) {
				clickedMarkerArr[index].close();
			}
			clickedMarkerArr[requestID].open(map, placemark);
		}
	});
}

function remove_all_river_guages() {
	for (var index in hashMarker) {
		if (typeof hashMarker[index].setMap === "function") {
			hashMarker[index].setMap(null);
			delete hashMarker[index];
		}
	}
	for (var index2 in clickedMarkerArr) {
		delete clickedMarkerArr[index2];
	}
	if (riverGaugeNewInfoWindow != "") {
		riverGaugeNewInfoWindow.close();
	}
	CURRENT_ID = false;
}

function river_gauge_failed() {
	river_guage_hide();
	console.error("River Gauge Error: Parse failed.");
}

function toggleRiverGuageKML(id) {
	if ($(".river-guage").attr("checked")) {
		if (id.is(":checked")) {
			river_guage_show();
			river_guage_detector = 'ON';
			var random = getRandomMathValue();
			CURRENT_ID = id.is(":checked");
			CURRENT_GAUGE = id.val();
			getMyParser();
			toggleKmlSingle = true;
			myParser.parse(kmlRequestURL + id.val() + "-rev01.kml?ref=" + random);
		}
		else {
			riverGaugeRemoveSingle(id.val());
		}
	}
}

function riverGaugeRemoveSingle(id) {
	if (riverGaugeCompleteData[id] !== undefined) {
		if (riverGaugeCompleteData[id].length > 0) {
			for (var i = 0; i < riverGaugeCompleteData[id].length; i++) {
				var index = riverGaugeCompleteData[id][i];
				if (hashMarker[index] !== undefined) {
					hashMarker[index].setMap(null);
					delete hashMarker[index];
				}
			}
			delete timestamp_arr[id];
			delete riverGaugeCompleteData[id];
		}
	}
	CURRENT_ID = false;
}

function update_river_guages() {
	if ($(".river-guage").attr("checked")) {
		river_guage_show();
		if (globalTimeStamp != '') {
			$.ajax({
				url: RefeshLink + globalTimeStamp,
				type: 'GET',
				dataType: 'JSON',
				success: function (response) {
					if (response.ts != null && response.ts != 0 && response.ts != "" && settings.riverGauges.status != false && response.values.length > 0) {
						globalTimeStamp = response.ts;
						console.log("global time stamp " + globalTimeStamp);
						for (var j = 0; j < response.values.length; j++) {
							if (settings.riverGauges.status != false) {
								var hashMarkerIndex = response.values[j].id;
								var imageMarkerAndIcon = getMarkerIcon(response.values[j].style);
								if (clickedMarkerArr[hashMarkerIndex] !== undefined) {
									delete clickedMarkerArr[hashMarkerIndex];
								}
								if (riverGaugeNewInfoWindow != "") {
									riverGaugeNewInfoWindow.close();
								}
								if (hashMarker[hashMarkerIndex] !== undefined) {
									hashMarker[hashMarkerIndex].setMap(null);
									delete hashMarker[hashMarkerIndex];
									rawMarkers[hashMarkerIndex].name = response.values[j].reading.toString();
									rawMarkers[hashMarkerIndex].styleUrl = response.values[j].style;
									rawMarkers[hashMarkerIndex].style.href = imageMarkerAndIcon;
									rawMarkers[hashMarkerIndex].style.icon.url = imageMarkerAndIcon;
									var tempMarkerParsed = myParser.createMarker(rawMarkers[hashMarkerIndex]);
									hashMarker[hashMarkerIndex] = tempMarkerParsed;
									bindPlacemarkClickEvent(hashMarker[hashMarkerIndex], hashMarkerIndex);
								}
							}
						}
					}
					river_guage_hide();
				},
				fail: function (jqXHR, textStatus, errorThrown, thrownError) {
					river_guage_hide();
					riverGaugeAjaxError(jqXHR, textStatus, errorThrown, thrownError);
				}
			});
		}
		$('#river_gauges_codes_div').show();
	}
}

function riverGaugeAjaxError(jqXHR, textStatus, errorThrown, thrownError) {
	console.error(textStatus + "  " + (errorThrown ? errorThrown : ''));
	showMessage(textStatus + "  " + (errorThrown ? errorThrown : ''));
}

function getMarkerIcon(styleName) {
	var tempIconName = "";
	switch (styleName) {
		case "#blacktri":
			tempIconName = "blacktriangle.png";
			break;
		case "#greytri":
			tempIconName = "greytriangle.png";
			break;
		case "#aquatri":
			tempIconName = "aquatriangle.png";
			break;
		case "#greentri":
			tempIconName = "greentriangle.png";
			break;
		case "#orangetri":
			tempIconName = "orangetriangle.png";
			break;
		case "#redtri":
			tempIconName = "redtriangle.png";
			break;
	}
	return IconRequestURL + tempIconName;
}
