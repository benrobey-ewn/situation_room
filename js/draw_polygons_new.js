var docid=0;
var newParserObj;
var drawPolygonArray = [];

/* New Polygon Creation */
var polyMarkers = [];
var polygonPath = [];
var drawPolygon = 0;
var polyPath = new google.maps.MVCArray;
var poly;
/* New Polygon Creation */

/* New Polygon Creation */
function addPoint(event){
	// console.log("drawing polygon = "+drawPolygon);
	if(drawPolygon==1)  {
	 	if(event.latLng !== 'undefined'){
			polyPath.insertAt(polyPath.length, event.latLng);
		}

		var draw_polgon_markers = new google.maps.Marker({
			position: event.latLng,
			map: map,
			draggable: true
		});


		polyMarkers.push(draw_polgon_markers);
		draw_polgon_markers.setTitle("#" + polyPath.length);

		google.maps.event.addListener(draw_polgon_markers, 'click', function() {
			draw_polgon_markers.setMap(null);
			for (var i = 0, I = polyMarkers.length; i < I && polyMarkers[i] != draw_polgon_markers; ++i);
				polyMarkers.splice(i, 1);
			polyPath.removeAt(i);
		}
		);

		google.maps.event.addListener(draw_polgon_markers, 'dragend', function(){
			for (var i = 0, I = polyMarkers.length; i < I && polyMarkers[i] != draw_polgon_markers; ++i);
				polyPath.setAt(i, draw_polgon_markers.getPosition());    
		}
		);
	}
}
/* New Polygon Creation */

function removeMarkersPolygons(map)
{
		for (var i = 0; i < polyMarkers.length; i++)
		{
			polyMarkers[i].setMap(map);
    	    polyPath.removeAt(0);

		}

}


