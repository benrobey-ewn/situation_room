$( document ).ready(function() {
	var riverBasinsKMLUrl = 'http://situationroom.ewn.com.au/kml_data/kml/BOM/river_basins.kml';
	var riverBasinsKMLOptions = {
	  suppressInfoWindows: true,
	  preserveViewport: true
	};
	var riverBasinsKMLLayer = new google.maps.KmlLayer(riverBasinsKMLUrl, riverBasinsKMLOptions);
	
	var weatherRegionsKMLUrl = 'http://situationroom.ewn.com.au/kml_data/kml/BOM/weather_regions.kml';
	var weatherRegionsKMLOptions = {
	  suppressInfoWindows: true,
	  preserveViewport: true
	};
	var weatherRegionsKMLLayer = new google.maps.KmlLayer(weatherRegionsKMLUrl, weatherRegionsKMLOptions);
	
	var fireRegionsKMLUrl = 'http://situationroom.ewn.com.au/kml_data/kml/BOM/fire_regions.kml';
	console.log(fireRegionsKMLUrl);
	var fireRegionsKMLOptions = {
	  suppressInfoWindows: true,
	  preserveViewport: true
	};
	var fireRegionsKMLLayer = new google.maps.KmlLayer(fireRegionsKMLUrl, fireRegionsKMLOptions);
	
	$("#fireregions").change(function(event) {
		if($('#fireregions').prop('checked')===true) {
			console.log('fireregions checked');
			fireRegionsKMLLayer.setMap(map)
		} else {
			console.log('fireregions unchecked');
			fireRegionsKMLLayer.setMap(null)
		}
	});
	
	$("#weatherregions").change(function(event) {
		if($('#weatherregions').prop('checked')===true) {
			console.log('weatherregions checked');
			weatherRegionsKMLLayer.setMap(map)
		} else {
			console.log('weatherregions unchecked');
			weatherRegionsKMLLayer.setMap(null)
		}
	});
	
	$("#riverbasins").change(function(event) {
		if($('#riverbasins').prop('checked')===true) {
			console.log('riverbasins checked');
			riverBasinsKMLLayer.setMap(map)
		} else {
			console.log('riverbasins unchecked');
			riverBasinsKMLLayer.setMap(null)
		}
	});
});