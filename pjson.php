


<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Polygon Arrays</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>

      // This example creates a simple polygon representing the Bermuda Triangle.
      // When the user clicks on the polygon an info window opens, showing
      // information about the polygon's coordinates.

      var map;
      var infoWindow;

      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
          center: {lat: -27.978636, lng: 130.396606},
          mapTypeId: google.maps.MapTypeId.TERRAIN
        });

        // Define the LatLng coordinates for the polygon.
        var triangleCoords = [
            {lat: -23.893873631954193, lng: 115.9661865234375},

            {lat: -23.893873631954193, lng: 117.147216796875},

            {lat: -23.783334016799927, lng: 117.75146484375},

            {lat: -23.541830658912659, lng: 118.795166015625},

            {lat:-23.400748252868652, lng: 119.9267578125},

            {lat:-23.667669296264648, lng: 120.399169921875},

             {lat:-23.853688299655914, lng: 121.124267578125},
             {lat:-23.400748252868652,lng:121.300048828125},

			{lat:-21.000419616699219,lng:122.991943359375},

			{lat:-20.620445668697357,lng:123.0029296875},

			{lat:-20.435248851776123,lng:116.488037109375},

			{lat:-20.527875244617462,lng:114.642333984375},

			{lat:-22.327719688415527,lng:113.203125},

			{lat:-22.601840198040009,lng:113.02734375},

			{lat:-22.966485798358917,lng:113.807373046875},

			{lat:-23.065075695514679,lng:114.29351806640625},

			{lat:-23.486423432826996,lng:114.7137451171875},

			{lat:-23.642511248588562,lng:115.543212890625},

			{lat:-23.853688299655914,lng:115.71075439453125},

			{lat:-23.893873631954193,lng:115.9661865234375},


        ];


        // Construct the polygon.
        var bermudaTriangle = new google.maps.Polygon({
          paths: triangleCoords,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: '#FF0000',
          fillOpacity: 0.35
        });
        bermudaTriangle.setMap(map);

        // Add a listener for the click event.
        bermudaTriangle.addListener('click', showArrays);

        infoWindow = new google.maps.InfoWindow;
      }

      /** @this {google.maps.Polygon} */
      function showArrays(event) {
        // Since this polygon has only one path, we can call getPath() to return the
        // MVCArray of LatLngs.
        var vertices = this.getPath();

        var contentString = '<b>Bermuda Triangle polygon</b><br>' +
            'Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
            '<br>';

        // Iterate over the vertices.
        for (var i =0; i < vertices.getLength(); i++) {
          var xy = vertices.getAt(i);
          contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
              xy.lng();
        }

        // Replace the info window's content and position.
        infoWindow.setContent(contentString);
        infoWindow.setPosition(event.latLng);

        infoWindow.open(map);
      }
    </script>
    <script src="http://maps.google.com/maps/api/js?sensor=false&.js"></script>
  </body>
</html