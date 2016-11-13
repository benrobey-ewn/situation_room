function nrcKml() {
	if ($("#hardcoded-kml").attr("checked")) {
		var url = 'http://ci.ewn.com.au:55555/exo/NrcTeleKml.aspx';
		var layer = new google.maps.KmlLayer(url, {
			preserveViewport: true,
			suppressInfoWindows: false 
		});

		layer.setMap(map);
		hardcodedKML = layer;
	}
	else {
		hardcodedKML.setMap(null);
	}
}