$( document ).ready(function() {
  $("#hardcoded-kml").click(function() {
  	nrcKml();
  });

  var nrcKmlTimer;
});

function nrcKml() {
	if ($("#hardcoded-kml").attr("checked")) {
		//var url = 'http://situationroom.ewn.com.au/dev/kml/gps-position.kml';
		var url = "http://api3.ewn.com.au/exo/NrcTeleKml.aspx";
		
		if (nrcLayer !== '') {
			nrcLayer.hideDocument();
		}

		nrcLayer = new geoXML3.parser({ 
			map: map,
			processStyles: true,
			zoom: false,
			singleInfoWindow: true,
			suppressInfoWindows:false,
		});
 
		nrcLayer.parse(url);
		nrcKmlTimer = setTimeout(function() { nrcKml(); }, 300000);
		console.log('D: ' + new Date().toLocaleString() + ' T: ' + nrcKmlTimer);
	}
	else {
		nrcLayer.hideDocument();
		clearTimeout(nrcKmlTimer);
	}
}