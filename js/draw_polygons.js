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


$(document).ready(function ()
{
	domReadyPolygons();
	/* Redraw Functionality */

	$(".submit-polygon-btn").on("click",function()
	{
		//drawingtoolsDeac();
		$("#popup_polygon_promt").modal("show");
	})


	$("#redraw").on("click",function()
	{
		removeMarkersPolygons(null);
		polyMarkers = [];
		$("#popup_polygon_promt").modal("hide");

		polyPath.clear();
	});
	/* Redraw Functionality */

	/* Continue Functionality */
	$("#continue-polygon").on('click',function()
	{
		$("#popup_polygon_promt").modal("hide");
		$("#popup_polygon_completeform").modal("show");
	});
	/* Continue Functionality */

	/* Cancel Functionality */
	$("#cancel-polygon").on("click",function()
	{
		removeMarkersPolygons(null);
		polyMarkers = [];
		$("#popup_polygon_promt").modal("hide");
		polyPath.clear();

		//drawingtoolsDeac();
	});
	/* Cancel Functionality */


	/* Cancel Functionality */
	$("#cancel_polygon_details").on("click",function()
	{
		$('#save_polygon_form')[0].reset();
		$("#save_polygon_details").attr("disabled",true);
		$("#popup_polygon_completeform").modal("hide");
		//drawingtoolsDeac();
	});
	/* Cancel Functionality */


	$("#title-polygon").on("keyup",function()
	{
		if($(this).val().length !=0 || $("#description-polygon").val().length !=0)
		{
			$("#save_polygon_details").attr("disabled",false);
		}
		else
		{
			$("#save_polygon_details").attr("disabled",true);
		}
	});


	$("#description-polygon").on("keyup",function()
	{
		if($("#title-polygon").val().length !=0 || $(this).val().length !=0)
		{
			$("#save_polygon_details").attr("disabled",false);
		}
		else 
		{
			$("#save_polygon_details").attr("disabled",true);
		}
	});

	/* Save Polygon Form */
	$("#save_polygon_form").on("submit",function(event)
	{
		var polygonLatLongArray = [];
		event.preventDefault();
		for(var k=0;k<polyMarkers.length;k++)
		{
			polygonLatLongArray.push
			({
				latitude:polyMarkers[k].getPosition().lat(),
				longitude:polyMarkers[k].getPosition().lng()
			});
		}

		if($("#title").val()!="")
		{
			$("#save_poly").attr('disabled',true);
			var obj = $(this);
			var formdata = obj.serialize()+"&"+$.param({ 'polygonArr': polygonLatLongArray });
			$.ajax({
				url: 'draw_polygon/action.php',
				type: 'POST',
				dataType : 'JSON',
				data: formdata,
				success:function(res){
					//currentPolygonObj.setMap(null);

					var filename = res.filename;
					var path = res.path;
					var layerName = res.layer_name;
					var successStatus = res.status_output;
					var totalRows = $('.pre_drawn_polygons').children('div').length;
					var newTotalRows = parseInt(totalRows)+1;
					if(successStatus=='success')
					{
						removeMarkersPolygons(null);
						polyPath.clear();
						var newHTML = '<div class="form-group" id="newpolys'+newTotalRows+'">\
						<input layer-name="'+layerName+'" type="checkbox" data-docid="'+docid+'" checked class="load_saved_kmls hitlayer'+newTotalRows+'" name="'+path+'" id="'+path+'" value="'+path+'"> <label class="text-primary" for="'+path+'">'+filename+'</label>\
						<a onclick="javascript:remove_draw_polygon(&quot;'+newTotalRows+'&quot;,&quot;'+filename+'&quot;,2)" href="javascript:void(0);" class="btn btn-default btn-xs pull-right"><i class="glyphicon glyphicon-trash"></i></a >\
						<a href="'+path+'" target="_blank" title="download KML" download class="btn btn-default btn-xs pull-right"><i class="glyphicon glyphicon-download-alt"></i></a >\
						</div>';
						$(".pre_drawn_polygons").append(newHTML);
						drawPolygonArray.push(layerName);
						settings.drawPolygonSetting.polygons = drawPolygonArray;
						settingsUpdate();
						newParserObj.parse(path);
						docid++;
						$("#popup_polygon_completeform").modal("hide");
						resetForm(obj);
						console.log(polyPath);
						polyMarkers = [];
						//$('.submit-polygon-btn').hide();

					}
					else
					{
						alert(res.message);
						$("#save_polygon_details").attr('disabled',false);
					}
					
				},
				error: function(res){
					alert("There was some error please try again");
					resetForm(obj);
				
					
				}
			});
		} else {
			alert("Title is compulsary");
			$("#save_poly").attr('disabled',true);
		}
	});



	var $click_count_new = 0;
	$(document).on("change",".load_saved_kmls",function(eve)
	{ 
		if($click_count_new == 0 ){
			$click_count_new++;
		}

		var kmlName = $(this).val();
		$(this).attr('disabled',true);
		if($(this).is(":checked"))
		{
			if(!$(this).attr("data-docid"))
			{

				/* Append data to array */
				drawPolygonArray.push($(this).attr('layer-name'));

				/* Append data to array */

				newParserObj.parse(kmlName);
				$(this).attr("data-docid",docid);
				docid++;
			}
			else
			{

				var tempdocid = $(this).attr("data-docid");
				newParserObj.showDocument(newParserObj.docs[tempdocid]);
			}

			settings.drawPolygonSetting.status = '';
			settings.drawPolygonSetting.polygons = drawPolygonArray;
			settingsUpdate();

		}
		else
		{

			//console.log($(this).attr('layer-name'));
			//console.log(settings);
			//settings.drawPolygonSetting.polygons.remove($(this).attr('layer-name'));
			var removeItemdeselect = $(this).attr('layer-name');   

			public_checked_options_new = $.grep(settings.drawPolygonSetting.polygons, function(value) {
                  return value != removeItemdeselect;
                });


            settings.drawPolygonSetting.polygons = public_checked_options_new;

			settingsUpdate();

			/* Append data to array */
			//drawPolygonArray.pop($(this).attr('layer-name'));
			/* Append data to array */

			var tempdocid = $(this).data("docid");
			newParserObj.hideDocument(newParserObj.docs[tempdocid]);

		}
		$(this).attr('disabled',false);
		

		
	});




});




function resetForm(obj)
{
	$(obj)[0].reset();
	$("#save_polygon_details").attr("disabled",true);
}


function domReadyPolygons()
{
	$(".draw-polygon-class").change(function ()
	{
		var e = $(this);
		if (e.attr("checked"))
		{
			//drawingToolsInit();
			drawPolygon = 1;
			$('.submit-polygon-btn').show();
			//$(".tools").show();
			//$("#draw_polygons").hide();
		}
		else
		{
			//drawingtoolsDeac();
			drawPolygon = 0;
			$('.submit-polygon-btn').hide();
		}
	});
}


function drawingToolsInit()
{

	drawingManager = new google.maps.drawing.DrawingManager
	({
		drawingMode: google.maps.drawing.OverlayType.POLYGON,
		drawingControl: true,
		drawingControlOptions:
		{
			position: google.maps.ControlPosition.TOP_RIGHT,
			drawingModes:
			[
				google.maps.drawing.OverlayType.POLYGON,
			]
		},
		polygonOptions :
		{
			clickable: true,
			editable: false,
		}
	});

	drawingManager.setMap(map);

	google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon)
	{
		polygonArray = [];
		currentPolygonObj = polygon;
		for (var i = 0; i < polygon.getPath().getLength(); i++)
		{
			polygonArray.push(polygon.getPath().getAt(i).toUrlValue(6));
		}

		if(polygon.getPath().getLength()>1)
		{
			$("#popup_polygon_promt").modal("show");

		}
	});
}

function drawingtoolsDeac()
{
	drawingManager.setDrawingMode(null);
	drawingManager.setMap(null);
}

window.onload=page_load_complete;

/* Execute Code after page load complete */
function page_load_complete()
{
	newLayerParser();

	/* On page load display polygons */

	for (var i = 0; i < settings.drawPolygonSetting.polygons.length; i++)
	{

		var layer_id = settings.drawPolygonSetting.polygons[i];
		var kmlNameNew = $('input[layer-name="'+layer_id+'"]').attr("name");
		//console.log($('input[layer-name="'+layer_id+'"]'));
		$('input[layer-name="'+layer_id+'"]').prop('checked', true);

		//var tempdocid = $('input[layer-name="'+layer_id+'"]').attr("data-docid");
		newParserObj.parse(kmlNameNew);
		drawPolygonArray.push(kmlNameNew);

		$('input[layer-name="'+layer_id+'"]').attr("data-docid",docid);
		docid++;


		//newParserObj.showDocument(newParserObj.docs[tempdocid]);

		//console.log(tempdocid);

		//$('.load_saved_kmls').attr('layer-name',layer_id).prop('checked', true);

		//$('#'+layer_id).prop('checked', true);
	}


	/* On page load display polygons */

} 

function remove_draw_polygon(row_id,polygon_id,poly_type)
{
	var check = confirm('Do you want to remove polygon?');
	if(check==true)
	{
		if(poly_type==1)
		{
			$('#polyrow'+row_id).hide();
		}
		else
		{
			$('#newpolys'+row_id).hide();
		}


		if($(".hitlayer"+row_id).prop('checked') == true)
		{
			var tempdocid = $(".hitlayer"+row_id).attr("data-docid");
			newParserObj.hideDocument(newParserObj.docs[tempdocid]);	
		}
		
		$.ajax({
			url: 'draw_polygon/delete_file.php',
			type: 'POST',
			dataType : 'JSON',
          	data: {'file_name' : polygon_id },
          	success:function(res)
			{},
			error: function(res)
			{}
		});
		
	}
}