<!DOCTYPE html>
<html>
<head>
	<title>Drawing tools</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta charset="utf-8">
	<style>
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
		}
		#map {
			height: 100%;
			width: 70%;
		}
		
		.vertical-alignment-helper {
			display:table;
			height: 100%;
			width: 100%;
			pointer-events:none; /* This makes sure that we can still click outside of the modal to close it */
		}
		.vertical-align-center {
			/* To center vertically */
			display: table-cell;
			vertical-align: middle;
			pointer-events:none;
		}
		.modal-content {
			/* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
			width:inherit;
			height:inherit;
			/* To center horizontally */
			margin: 0 auto;
			pointer-events: all;
		}
		.modal-sm{
			width: 380px;
		}
		html, body {
			background: #191919 !important;
		}
	</style>
	
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
	
	
	<div id="map" class="pull-left"></div>
	<div class="controls ">
		<div class="complete_tools_kit" >
		
				<button name="draw" value="polygon" id="draw_polygons" class="btn btn-primary"  >Draw Polygons</button>
				<span class="tools" style="display:none;">
					<button name="stop" value="polygon" id="stop_draw_polygons" class="btn btn-danger">Close Polygon Draw</button>
				</span>
		
		</div>
		<div class="row ">
			<div class="pre_drawn_polygons col-md-3">
				<?php 
				$glob = glob("generated_kmls/*.kml");
				if(!empty($glob)){
					echo '<hr>';
					foreach($glob as $elements){
						echo '<div class="form-group">	
						<input type="checkbox" class="load_saved_kmls" name="'.$elements.'" id="'.$elements.'" value="'.$elements.'"> <label for="'.$elements.'" class="text-primary">'.basename($elements).'</label> 
						<a href="'.$elements.'" target="_blank" title="download KML"  download class="btn btn-default btn-xs pull-right"><i class="glyphicon glyphicon-download-alt"></i></a >
						</div>'; 
					}
				}
				?>
			</div>
		</div>
	</div>
	
	<div style="clear:both;"></div>
	
	<div class="modal fade popup_polygon_completeform" id="popup_polygon_completeform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Complete your polygon by given a title or description</h4>
				</div>
				<form action="#" method="POST" id="save_polygon_form" class="save_polygon_form" >
					<div class="modal-body">
						<div class="form-group">
							<label for="title">Title *</label>
							<input type="text" class="form-control" name="title" id="title">
						</div>
						<div class="form-group">	
							<label for="description">Description</label>
							<textarea name="description" id="description" class="description form-control" cols="30" rows="10"></textarea>
						</div>	
					</div>
					<div class="modal-footer">
						<input type="hidden" name="save_polygon" value="save_polygon">
						<button type="submit" name="save_poly" value="save_poly" id="save_poly" class="btn btn-primary" disabled>Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="modal popup_polygon_promt" tabindex="-1" role="dialog" id="popup_polygon_promt" data-keyboard="false" data-backdrop="static">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog  modal-sm vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Choose option for the drawn polygon</h4>
					</div>
					<div class="modal-body">
						<div class="text-center">
							<button class=" btn btn-primary redraw" id="redraw" name="action" value="redraw">Redraw</button>
							<button class=" btn btn-success continue" id="continue" name="action" value="continue">Continue</button>
							<button class=" btn btn-danger cancel" id="cancel" name="action" value="cancel">Close Tools</button>
						</div>
					</div>
				</div><!-- /.modal-content -->
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&signed_in=true&libraries=drawing" ></script>
	<script src="assets/js/jquery-1.7.2.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/geoxml3.js"></script>
	<script>
		var polygonArray = [];
		var currentPolygonObj;
		var map;
		var drawingManager;
		var infowindow;
		var allTempPolygonsArr = {};
		var kmlLayers = {};
		var newParserObj;
		var docid=0;
		function newLayerParser(){
			newParserObj = new geoXML3.parser({ 
				map: map,
				processStyles: true,
				zoom: false,
				singleInfoWindow: true,
				suppressInfoWindows:false,
			});
		}
		function initMap() {
			map = new google.maps.Map(document.getElementById('map'), {
				center: new google.maps.LatLng(-24.994167,134.866944),
				zoom: 5
			});
			newLayerParser();
		}
		
		
		
		google.maps.event.addDomListener(window, 'load', initMap);
		
		function dawingToolsInit(){
			drawingManager = new google.maps.drawing.DrawingManager({
				drawingMode: google.maps.drawing.OverlayType.POLYGON,
				drawingControl: true,
				drawingControlOptions: {
					position: google.maps.ControlPosition.TOP_RIGHT,
					drawingModes: [
			     //   google.maps.drawing.OverlayType.MARKER,
			    //    google.maps.drawing.OverlayType.CIRCLE,
			    google.maps.drawing.OverlayType.POLYGON,
			       // google.maps.drawing.OverlayType.POLYLINE,
			     //   google.maps.drawing.OverlayType.RECTANGLE
			     ]
			  },
			   /* markerOptions: {
			    	icon: 'images/beachflag.png'
			    },*/
			  /*  circleOptions: {
			      fillColor: '#ffff00',
			      fillOpacity: 1,
			      strokeWeight: 5,
			      clickable: false,
			      editable: true,
			      zIndex: 1
			   }*/
			   polygonOptions : {
			   	clickable: true,
			   	editable: false,
			   }
			});
			drawingManager.setMap(map);
			
			google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
				polygonArray = [];
				currentPolygonObj = polygon;
				for (var i = 0; i < polygon.getPath().getLength(); i++) {
					polygonArray.push(polygon.getPath().getAt(i).toUrlValue(6));
				}
				
				$("#popup_polygon_promt").modal("show");
				
				
			});
		}
		
		function drawingtoolsDeac(){
			drawingManager.setDrawingMode(null);
			drawingManager.setMap(null);
			$(".tools").hide();
			$("#draw_polygons").show();
		}
		
		$(document).ready(function() {
			$("#draw_polygons").on("click",function(){
				dawingToolsInit();
				$(".tools").show();
				$("#draw_polygons").hide();
			});
			
			$("#stop_draw_polygons").on("click",function(){
				drawingtoolsDeac();
			})
			
			$("#redraw").on("click",function(){
				currentPolygonObj.setMap(null);
				$("#popup_polygon_promt").modal("hide");
			});
			
			$("#cancel").on("click",function(){
				currentPolygonObj.setMap(null);
				$("#popup_polygon_promt").modal("hide");
				drawingtoolsDeac();
			});
			
			$("#continue").on('click',function(){
				$("#popup_polygon_promt").modal("hide");
				$("#popup_polygon_completeform").modal("show");
			});
			
			$("#save_polygon_form").on("submit",function(event){
				event.preventDefault();
				if($("#title").val()!=""){
					var obj = $(this);
					var formdata = obj.serialize()+"&"+$.param({ 'polygonArr': polygonArray });
					$.ajax({
						url: 'core/action.php',
						type: 'POST',
						dataType : 'JSON',
						data: formdata,
						success:function(res){
							currentPolygonObj.setMap(null);
							var filename = res.filename;
							var path = res.path;
							var newHTML = '<div class="form-group">\
							<input type="checkbox" data-docid="'+docid+'" checked class="load_saved_kmls" name="'+path+'" id="'+path+'" value="'+path+'"> <label class="text-primary" for="'+path+'">'+filename+'</label>\
							<a href="'+path+'" target="_blank" title="download KML" download class="btn btn-default btn-xs pull-right"><i class="glyphicon glyphicon-download-alt"></i></a >\
						</div>';
						$(".pre_drawn_polygons").append(newHTML);
						newParserObj.parse(path);
						docid++;
						$("#popup_polygon_completeform").modal("hide");
						resetForm(obj);
					},
					error: function(res){
						alert("There was some error please try again");
						resetForm(obj);
						
					}
				});
				} else {
					alert("Title is compulsary");
				}
			});

$(document).on("change",".load_saved_kmls",function(eve){
	var kmlName = $(this).val();
	
	if($(this).is(":checked")){
		if(!$(this).attr("data-docid")){
			newParserObj.parse(kmlName);
			$(this).attr("data-docid",docid);
			docid++;
		} else {
			var tempdocid = $(this).attr("data-docid");
			newParserObj.showDocument(newParserObj.docs[tempdocid]);
		}
	} else{
		var tempdocid = $(this).data("docid");
		newParserObj.hideDocument(newParserObj.docs[tempdocid]);				
	}
});

$("#title").on("keyup",function(){
	if($(this).val().length !=0 || $("#description").val().length !=0){
		$("#save_poly").attr("disabled",false);
	} else {
		$("#save_poly").attr("disabled",true);
	}
});

$("#description").on("keyup",function(){
	if($("#title").val().length !=0 || $(this).val().length !=0){
		$("#save_poly").attr("disabled",false);
	} else {
		$("#save_poly").attr("disabled",true);
	}
});
});

function resetForm(obj){
	$(obj)[0].reset();
	$("#save_poly").attr("disabled",true);
}
</script>
</body>
</html>