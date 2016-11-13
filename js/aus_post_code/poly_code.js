	var useAjaxP = false;
	var drawnOnceP = false;
    var zip_markerP=new google.maps.Marker({});
	var activeP, basinsP, basinPathsP, googleMapProjectionP, overlayProjectionP, curZP, layerP, svgP, post_overlayP, pathP, ignoreClickP = false;
    // var map;
    var width = 960, height = 650;
    var currentPolyObj=[];
    var curr_user_access=$("#nbn_user_type").val();
	var lastOpenedInfoWindow;
	var bbox=[];
	var postCoordinates=[];
	var nbndocid=0;
	// var nbnParserObject;
	var drawPolygonArray = [];
	var newParserObj;
	var polygonPath = [];
	var docid=0;
	var currentPolygonObj = "";
	var nbndrawingManager ="";
	
	function show_nbn_loader() {
	$("div.nbn-data-layers").find(".heading-name").after('<span class="ajaxwaitobj gauge-load"><i class=" fa fa-spinner fa-pulse icon-spin fa-2x"></i></span>');
	}

	function hide_nbn_loader() {
		$("div.nbn-data-layers").find('span.ajaxwaitobj.gauge-load').remove();	
	}

    function loadBoundariesP(z) {
			
		show_nbn_loader();
		if (z == curZP)
			return;
		curZP = z;

		var bTypeKey = 'PC';

		useAjaxP = false;
		var fn = "/js/boundaries/b-" + bTypeKey + "-geo-topo-" + z + ".json";
		console.log(fn);
		d3.json(fn, function (error, topology) {
		
	        if (post_overlayP) {
				post_overlayP.setMap(null);
			}
			post_overlayP = new google.maps.OverlayView();

			// Add the container when the overlay is added to the map.
			post_overlayP.onAdd = function () {
				//console.log("overlay onAdd");
				//if (!layer)
				layerP = d3.select(this.getPanes().overlayMouseTarget).append("div").attr("class", "SvgOverlayP basins");
				//console.log(svgP);
				if (svgP)
					$('svg').remove();
				svgP = layerP.append("svg").attr("width", width).attr("height", height);
				basinsP = svgP.append("g").attr("class", "basins");
				
				post_overlayP.draw = function () {
                   
				    
	                    //console.log("overlay draw");
						var markerOverlay = this;
						overlayProjectionP = this.getProjection();
                                        
                        // Turn the overlay projection into a d3 projection
						googleMapProjectionP = function (coordinates) {
							//console.log(coordinates);
							var googleCoordinates = new google.maps.LatLng(coordinates[1], coordinates[0]);
							var pixelCoordinates = overlayProjectionP.fromLatLngToDivPixel(googleCoordinates);
							return [pixelCoordinates.x + 4000, pixelCoordinates.y + 4000];
						}
					//var geometries=(topology.objects.boundary.geometries);	
					//console.log(geometries);	
					pathP = d3.geo.path().projection(googleMapProjectionP);
					var checkClass=getClassFromDb();
					//console.log(ca);
					basinPathsP = basinsP.selectAll("path")
								.data(topojson.feature(topology, topology.objects.boundary).features)
								.attr("d", pathP)
								.enter().append("path")
								.attr("d", pathP)
                                .attr("class", function(d){
                                	var centroidArr=d.geometry.coordinates;
                                	if(checkClass.classes.hasOwnProperty(d.id)){
                                			if(checkClass.classes[d.id]=='activeR'){
                                				$("#Red_zones").append('<li id="'+d.id+'"><a style="cursor:pointer" onclick="setPolygon('+d.properties.name+','+d.geometry.coordinates+');">'+d.properties.name+'</a></li>');
		                                 			
                                			}
                                			if(checkClass.classes[d.id]=='activeB'){
                                				$("#Black_zones").append('<li id="'+d.id+'"><a style="cursor:pointer" onclick="setPolygon('+d.properties.name+','+d.geometry.coordinates+');">'+d.properties.name+'</a></li>');
                                			}
                                			if(checkClass.classes[d.id]=='activeA'){
                                				$("#Amber_zones").append('<li id="'+d.id+'"><a style="cursor:pointer" onclick="setPolygon('+d.properties.name+','+d.geometry.coordinates+');">'+d.properties.name+'</a></li>');
		                                 			
                                			}
											return checkClass.classes[d.id];
										}else{
                                				$("#Green_zones").append('<li id="'+d.id+'"><a style="cursor:pointer" onclick="setPolygon('+d.properties.name+','+d.geometry.coordinates+');">'+d.properties.name+'</a></li>');
											return 'activeG';
										}
                                }
                                )
                                .attr("id", function(d){return "nbn_"+d.id;})
								.on("click", clickP)
								.append("svg:title")
								.text(function (d) {
                             		return d.properties.name; 
								});                              
		       

			};
		};
                        
        post_overlayP.onRemove = function () {
			console.log("overlay onRemove");
		};

		// Bind our overlay to the map
		post_overlayP.setMap(map);


		var centerControlDiv = document.createElement('div');
  		var centerControl = new CenterControl(centerControlDiv, map);
  		centerControlDiv.index = 1;
  		map.controls[google.maps.ControlPosition.TOP_LEFT].push(centerControlDiv);
		$( ".nbn-data-layers" ).ajaxComplete(function(){
			hide_nbn_loader();
		});
			$('#loader').hide();
			$("#nbn_list_option").show();
			$(".pre_drawn_nbn_polygons").show();
			$("#loading_nbn_layers").hide();
			enableAllCheckboxes();

		});     
	}
	//Load Drawing mode
	function domReadyNBNPolygons(){
	// 	nbn_parser_initalise();

			$("#drawing_mode").change(function ()
			{
				var e = $(this);
				var temp_status_flag = false;
				if (e.attr("checked"))	{
					nbndrawingToolsInit();
					disableSavedPolygons();
					temp_status_flag = true;
					
					
				} else {
					nbndrawingtoolsDeac();
					enableSavedPolygons();
					temp_status_flag = false;
				}
				
				if(settings.drawPolygonSetting.status!==undefined){
					settings.drawPolygonSetting.status = temp_status_flag;
				} else{

					settings.drawPolygonSetting = {
						polygons: '',
						status : false
					}
				}

				settingsUpdate();
			});
	}
	//Load Drawing Mode

	//Drawing mode enabled
	function nbndrawingToolsInit()
	{

		nbndrawingManager = new google.maps.drawing.DrawingManager
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
		nbndrawingManager.setMap(map);
		customControlDiv = document.createElement('div');
	    customControl = new CustomControl(customControlDiv, map);

	    customControlDiv.index = 1;
	   	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(customControlDiv); 	
	  
	   

		google.maps.event.addListener(nbndrawingManager, 'polygoncomplete', function(polygon)
		{
			nbnpolygonArray = [];
			currentPolygonObj = polygon;
			for (var i = 0; i < polygon.getPath().getLength(); i++)
			{
				nbnpolygonArray.push(polygon.getPath().getAt(i).toUrlValue(6));
			
			}
			if(polygon.getPath().getLength()>1)
			{
				getAffectedPostcodes(nbnpolygonArray);
				$("#send_alert_type").val('draw');
			}
			if (nbndrawingManager.getDrawingMode() != null) {
		     	nbndrawingManager.setDrawingMode(null);
		    }
		});
		google.maps.event.addListener(nbndrawingManager, "drawingmode_changed", function() {
		    if (nbndrawingManager.getDrawingMode() != null) {
		      	if(currentPolygonObj!==undefined && currentPolygonObj!=""){
	        			currentPolygonObj.setMap(null);
	        			removeSelectedZones();

	       		}
		    }
		});
	}
	//Drawing Mode enabled

	
	//Drawing Mode disabled
	function nbndrawingtoolsDeac()
	{
		if(nbndrawingManager!==undefined && nbndrawingManager!=""){
			nbndrawingManager.setDrawingMode(null);
			nbndrawingManager.setMap(null);
		}
		map.controls[google.maps.ControlPosition.TOP_RIGHT].clear(); 
		 if(currentPolygonObj!==undefined && currentPolygonObj!=""){
	        	currentPolygonObj.setMap(null);
	        }
	     removeSelectedZones();
	}
	//Drawing Mode disabled

	//Clear Button
	function CustomControl(controlDiv, map) {

	    // Set CSS for the control border
	    var controlUI = document.createElement('div');
	    controlUI.className = "clear_button";
	    controlUI.style.backgroundColor = 'rgb(255, 255, 255)';
	    controlUI.style.borderStyle = 'solid';
	    controlUI.style.color = 'rgb(86, 86, 86)';
	    controlUI.style.borderWidth = '1px';
	    controlUI.style.borderColor = '#ccc';
	    controlUI.style.height = '26px';
	    controlUI.style.marginTop = '4px';
	    controlUI.style.marginLeft = '933px';
	    controlUI.style.paddingTop = '1px';
	    controlUI.style.cursor = 'pointer';
	    controlUI.style.textAlign = 'center';
	    controlUI.title = 'Remove Polygon';
	    controlDiv.appendChild(controlUI);

	    // Set CSS for the control interior
	    var controlText = document.createElement('div');
	    controlText.style.fontFamily = 'Arial,sans-serif';
	    controlText.style.fontSize = '10px';
	    controlText.style.paddingLeft = '4px';
	    controlText.style.paddingRight = '4px';
	    controlText.style.marginTop = '0px';
	    controlText.innerHTML = '<b>Clear</b>';
	    controlUI.appendChild(controlText);

	    // Setup the click event listeners
	    google.maps.event.addDomListener(controlUI, 'click', function () {
	        if(currentPolygonObj!==undefined && currentPolygonObj!=""){
	        	currentPolygonObj.setMap(null);
	        }
			removedSavedPolygons();
		  
	    });
	}
	//Clear Button

	//Reset Map Button
	function CenterControl(controlDiv, map) {

	  // Set CSS for the control border.
	  var controlUI = document.createElement('div');
	  controlUI.style.backgroundColor = 'rgb(255, 255, 255)';
      controlUI.style.borderStyle = 'solid';
      controlUI.style.color = 'rgb(86, 86, 86)';
      controlUI.style.borderWidth = '1px';
      controlUI.style.borderColor = '#ccc';
      controlUI.style.height = '36px';
      controlUI.style.marginTop = '10px';
      controlUI.style.paddingTop = '6px';
      controlUI.style.cursor = 'pointer';
      controlUI.style.textAlign = 'center';
	  controlDiv.appendChild(controlUI);

	  // Set CSS for the control interior.
	  var controlText = document.createElement('div');
	  controlText.style.fontFamily = 'Arial,sans-serif';
	  controlText.style.fontSize = '10px';
	  controlText.style.paddingLeft = '4px';
	  controlText.style.paddingRight = '4px';
	  controlText.style.marginTop = '0px';
	  controlText.innerHTML = '<b>Reset Map</b>';
	  controlUI.appendChild(controlText);

	  // Setup the click event listeners: simply set the map to Chicago.
	  controlUI.addEventListener('click', function() {
	  	map.setCenter(new google.maps.LatLng(-27.978636, 130.396606));
	    map.setZoom(5);
	  });

	}
	//Reset Map Button
	//Click Even on Polygon
	var color_arr = {};	
	var setPostcodes =[];	
	function clickP(d) {
		if($("#drawing_mode").prop('checked') != true){
		var temp_id = d.id;
		var postid = d.properties.name;
		var setPostcode=temp_id+'-'+postid;
		var $path = $("path#nbn_"+temp_id);
		if(curr_user_access=='full_access'){
		var classP = $path.attr('class'); 

		if(color_arr[temp_id] === undefined){
			color_arr[temp_id] = classP;
				$path.attr("class","");
			  	$path.attr("class","active");
		} else {
			  	$path.attr("class","");
			  	$path.attr("class",color_arr[temp_id]);
			delete color_arr[temp_id];
		}
		  
		
             //console.log(currentPolyObj);

               if(setPostcodes.indexOf(setPostcode) == -1){
                    setPostcodes.push(setPostcode);
                 } else {
                    setPostcodes.remove(setPostcode);
             }
			  if(setPostcodes.length>0){
	                        $("#open_alert_form").show();
							$("#drawing_mode").attr("disabled", true);
							$(".pre_drawn_nbn_polygons").hide();

	                 }else{
	                        $("#open_alert_form").hide();
	                        $("#drawing_mode").attr("disabled", false);
	                        $(".pre_drawn_nbn_polygons").show();
	           }
	            $("#nbn_postcodes").val(setPostcodes);
	            $("#send_alert_type").val('click');
		  }
		
	
	  	}else{
	  		//alert("Please Disable Drawing Tools First");


	  }
	}
	//Click Even on Polygon
		
	//Zooming Polygon
	function setPolygon(postcode,lat,lng){
	
	
		map.setCenter(new google.maps.LatLng(lng,lat));
	 	marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lng,lat),
                    map: map,
                    icon: 'images/magic.gif',
                  
                });
	            var zip_marker_contentString = '<div id="markerinfo" style="width:100px;">Post code - '+postcode+'</div>';
		        var zip_marker_infowindow = new google.maps.InfoWindow({
		        	content: zip_marker_contentString
		        });
         // marker.setMap(null);
                 google.maps.event.addListener(marker, 'click', function() {
        			 closeLastOpenedInfoWindo();
        			zip_marker_infowindow.open(map,marker);
        			map.setZoom(9);
        			lastOpenedInfoWindow = zip_marker_infowindow;
        });
        google.maps.event.trigger(marker, 'click');
	}
	//Zooming Polygon

	//InfoWindow
	function closeLastOpenedInfoWindo() {
		if (lastOpenedInfoWindow) {
		    lastOpenedInfoWindow.close();
		}
	 }
	 //Infowindow

	 //Removde key from array function  
	Array.prototype.remove = function() {
	    var what, a = arguments, L = a.length, ax;
	    while (L && this.length) {
	        what = a[--L];
	        while ((ax = this.indexOf(what)) !== -1) {
	            this.splice(ax, 1);
	        }
	    }
	    return this;
	};
	//Removde key from array function 


	//Get current threat level of polygon    
	function getClassFromDb(){
		$.ajax({
		    async: false, 
		    dataType: "json",
		    url: "ajax/get_class.php",
		    success : function (response)
		              {
		                  ajaxResponse = response;
		              },
		    // other properties
		});

		return ajaxResponse;
	    
	}
	//Get current threat level of polygon


	//Get Affected Postcodes on Drawn polygon 
	var seleced_nbnarr=[];
	var setDrawnPostcodes=[];
	function getAffectedPostcodes(nbnpolygonArray){
		var formdata = $.param({ 'polygonArr': nbnpolygonArray});
        $.ajax({
		    async: false, 
		    dataType: "json",
		    type: 'POST',
		    data:formdata,
		    url: "ajax/get_affected_postcodes.php",
		    success : function (res)
		              {
						var postcodes=res.postcodes;
						for (var i = 0; i < postcodes.length; i++) {
						    $("path#nbn_"+postcodes[i]).attr("class","active");
						    var temp_id=postcodes[i];
						    var selpostcode=$('ul.list-group').find('li#'+temp_id).text();
						    var setPostcode=temp_id+'-'+selpostcode;

						   if(seleced_nbnarr[temp_id]== undefined){
		                       seleced_nbnarr[temp_id] = 'activeG';
		                     }

		                     if(setDrawnPostcodes.indexOf(setPostcode) == -1){
				                    setDrawnPostcodes.push(setPostcode);
				                 } else {
				                    setDrawnPostcodes.remove(setPostcode);
				             } 

						}
						console.log(setDrawnPostcodes);
						if(setDrawnPostcodes.length>0){
		                        $("#open_alert_form").show();
		                 }else{
		                        $("#open_alert_form").hide();
		           		}
		           		 $("#nbn_postcodes").val(setDrawnPostcodes);	
		              },
		    // other properties
		});

		//return ajaxResponse;
	           

	}
	//Get Affected Postcodes on Drawn polygon


	//Get Affected Postcodes on Load polygon
	var nbncolor_arr = [];	
	var setLoadPostcodes=[];
	function getAffectedPostcodesOnLoad(nbnpolygonArray,kmlName,docid){
		//console.log(docid);

		var formdata =$.param({ 'polygonArr': nbnpolygonArray});
        $.ajax({
		    async: false, 
		    dataType: "json",
		    type: 'POST',
		    data:formdata,
		    url: "ajax/get_affected_postcodes_load.php",
		    success : function (res)
		              {

						//nbncolor_arr['filename']=kmlName;
						var postcodes=res.postcodes;
						for (var i = 0; i < postcodes.length; i++) {
						   	
						   	var temp_id = postcodes[i];
						   	var selpostcode=$('ul.list-group').find('li#'+temp_id).text();
						    var setPostcode=temp_id+'-'+selpostcode;


						     if(setDrawnPostcodes.indexOf(setPostcode) == -1){
				                    setDrawnPostcodes.push(setPostcode);
				                 } else {
				                    setDrawnPostcodes.remove(setPostcode);
				             } 

							var $path = $("path#nbn_"+temp_id);
							var classP = $path.attr('class');
						   
							if(nbncolor_arr[temp_id]== undefined){
		                       nbncolor_arr[temp_id] = classP+'-'+kmlName+'-'+selpostcode;
		                     } 

						   	
						     $("path#nbn_"+postcodes[i]).attr("class","");
						   	 $("path#nbn_"+postcodes[i]).attr("class","active");
							   if(setDrawnPostcodes.length>0){
			                        $("#open_alert_form").show();
			                 }else{
			                        $("#open_alert_form").hide();
			           		}
						}
						console.log(nbncolor_arr);
						if(setDrawnPostcodes.length>0){
		                        $("#open_alert_form").show();
		                 }else{
		                        $("#open_alert_form").hide();
		           		}
		           		 $("#nbn_postcodes").val(setDrawnPostcodes);	
		           		 $("#send_alert_type").val('click');
		              },
		    // other properties
		});

		//return ajaxResponse;
	           

	}
	//Get Affected Postcodes on Load polygon

	var removeLi;
	function confirm_alert()
	{
	    var status= confirm("Are you sure wish to send this alert?");
	    if (status== true){
			var send_alert_type=$("#send_alert_type").val();
			if(send_alert_type=='click'){
						$("#cancel_polygon").attr('disabled',true);
						$("#save_nbn_alert_details").html('<i class="fa fa-spinner fa-pulse icon-spin "></i> Loading...').attr('disabled',true);
						 var formData=$("#save_nbn_alert_form").serialize();
				      	 setTimeout(function(){ 
				      	 $.ajax({
							    async: false, 
							    dataType: "json",
							    url: "ajax/send_ewn_alert.php",
							    data: formData,
							    success : function (response) {
				                  for (var i = 0; i < response.length; i++) {

				                  	var selid=response[i].id;
				                  	var selpostcode=response[i].postcode;
				                  	var selclass=response[i].class;
				                  	var selcolor=response[i].color;
				                  	var setPostcode=selid+'_'+selpostcode;
				                  	var lat=response[i].lat;
							        var lng=response[i].lng;
				                  	var currclass = $("path#nbn_"+selid).attr('class');
				                  	//console.log('Current Class='+currclass);
				                  	if ($('ul#Black_zones li:contains('+selpostcode+')').length ) {
				                  		$('ul#Black_zones').find('li#'+selid).remove();
    								
									}
									if ( $('ul#Red_zones li:contains('+selpostcode+')').length ) {
    									$('ul#Red_zones').find('li#'+selid).remove();
									}

									if ( $('ul#Amber_zones li:contains('+selpostcode+')').length ) {
    									$('ul#Amber_zones').find('li#'+selid).remove();
									}
									if ( $('ul#Green_zones li:contains('+selpostcode+')').length ) {
    									$('ul#Green_zones').find('li#'+selid).remove();
									}
									$('#'+selcolor+'_zones').append('<li id="'+selid+'"><a style="cursor:pointer" onclick="setPolygon('+selpostcode+','+lat+','+lng+');">'+selpostcode+'</a></li>');
						            
						           
						            $("path#nbn_"+selid).attr("class","");
				       				$("path#nbn_"+selid).attr("class",selclass);
				       				
				       				console.log(currclass);
				       				setPostcodes.remove(setPostcode);
				       				delete color_arr[selid];
					       			}
					       			setPostcodes.length=0;
									$("#nbn_postcodes").val('');
						       		$("#open_alert_form").hide();
									alert("Alert has been sent successfully."); 
					       			$("#save_nbn_alert_details").html('Send');
					       		   	$('#save_nbn_alert_form')[0].reset();
					       		   	$("#drawing_mode").attr("disabled", false);
					       		   	$('#popup_nbn_polygon').modal('toggle');
					       		   	$("#cancel_polygon").attr('disabled',false);
					       		   	console.log(setPostcodes);
					       		},
							    error: function(response){
							    	 $("#save_nbn_alert_details").html('Send').attr('disabled',false);
							    	 $("#cancel_polygon").attr('disabled',false);
							    	 alert("Alert send un-successful, Please try again!"); 

							    }
							    
							});
				      	},100);
				       	//  setTimeout(function(){ alert("Alert has been sent successfully"); }, 2000);
					}else{	    
				        var alert_type=$("#alert_type").val();
				        $("#save_nbn_alert_details").html('<i class=" fa fa-spinner fa-pulse icon-spin "></i> Loading...').attr('disabled',true);
						console.log("draw="+nbnpolygonArray);
						var obj = $(this);
						var formdata = $("#save_nbn_alert_form").serialize()+"&"+$.param({ 'polygonArr': nbnpolygonArray });
						var totalRows = $('.pre_drawn_nbn_polygons').children('div').length;
						var newTotalRows = parseInt(totalRows)+1;
						//console.log(totalRows);
						 setTimeout(function(){ 
					      $.ajax({
							    async: false,
							    type: 'POST', 
							    dataType: "json",
							    data:formdata,
							    url: "ajax/send_alert.php",
							    success:function(res)
									{
										currentPolygonObj.setMap(null);
										var filename = res.filename;
										var path = res.path;
										var layerName = res.layer_name;
										var newHTML = '<div class="form-group" id="newpolys'+newTotalRows+'">\
										<span class="draw_kml_loading draw_kml_load hide" ><i class=" fa fa-spinner fa-pulse icon-spin "></i></span>\
										<input layer-name="'+layerName+'" type="checkbox" data-docid="'+nbndocid+'" checked class="load_saved_kmls hitlayer'+newTotalRows+'" name="'+path+'" id="'+path+'" value="'+path+'"> <label class="text-primary" for="'+path+'">'+filename+'</label>\
										<a onclick="javascript:remove_draw_polygon(&quot;'+newTotalRows+'&quot;,&quot;'+filename+'&quot;,2)" href="javascript:void(0);" class="btn btn-default btn-xs pull-right"><i class="glyphicon glyphicon-trash"></i></a >\
										<a href="'+path+'" target="_blank" title="download KML" download class="btn btn-default btn-xs pull-right"><i class="glyphicon glyphicon-download-alt"></i></a >\
										</div>';
										$(".pre_drawn_nbn_polygons").append(newHTML);
										drawPolygonArray.push(layerName);
										settings.drawPolygonSetting.polygons = drawPolygonArray;
										settingsUpdate();
										newParserObj.parse(path);
										nbndocid++;
										
											var postcodes=res.postcode;
											for (var i = 0; i < res.postcode.length; i++) {

												var selid=postcodes[i].id;
							                  	var selpostcode=postcodes[i].postcode;
							                  	var selclass=postcodes[i].class;
							                  	var selcolor=postcodes[i].color;
							                  	var setPostcode=selid+'_'+selpostcode;
							                  	var lat=postcodes[i].lat;
							                  	var lng=postcodes[i].lng;
							                  	

							                  	if ($('ul#Black_zones li:contains('+selpostcode+')').length ) {
							                  		$('ul#Black_zones').find('li#'+selid).remove();
			    								
												}
												if ( $('ul#Red_zones li:contains('+selpostcode+')').length ) {
			    									$('ul#Red_zones').find('li#'+selid).remove();
												}

												if ( $('ul#Amber_zones li:contains('+selpostcode+')').length ) {
			    									$('ul#Amber_zones').find('li#'+selid).remove();
												}
												if ( $('ul#Green_zones li:contains('+selpostcode+')').length ) {
			    									$('ul#Green_zones').find('li#'+selid).remove();
												}
												$('#'+selcolor+'_zones').append('<li id="'+selid+'"><a style="cursor:pointer" onclick="setPolygon('+selpostcode+','+lat+','+lng+');">'+selpostcode+'</a></li>');
									           
									            $("path#nbn_"+selid).attr("class","");
							       				$("path#nbn_"+selid).attr("class",selclass);

											  
											    setDrawnPostcodes.remove(setPostcode);
											    var index = setDrawnPostcodes.indexOf(setPostcode);
												setDrawnPostcodes.splice(index, 1);
											      
				       							//delete color_arr[response[i].postcode];
											}
										  setTimeout(function(){ alert("Alert has been sent successfully."); }, 2000);
									       $('#save_nbn_alert_form')[0].reset();
									       $('#popup_nbn_polygon').modal('hide');
									       $("#save_nbn_alert_details").html('Send');
									       $("#open_alert_form").hide();
									       $("#nbn_postcodes").val('');
										
									},
									error: function(res)
									{
										alert("There was some error please try again");
										 $('#save_nbn_alert_form')[0].reset();
										  $("#save_nbn_alert_details").html('Send')
										//resetForm(obj);
									}
							    
								});
					      },50);
			      }
	    }else{
	        return false;
	    }
	}

	function show_nbn_layers()
	{
	        $('#navigation-nbn').show();
	        greenZoneSort();
	        blackZoneSort();
	        redZoneSort();
	        amberZoneSort();
	        $('#show_nbn_layers').hide();
	        $('#hide_nbn_layers').show();

	}
	function hide_nbn_layers(){
       $('#navigation-nbn').hide();
        $('#show_nbn_layers').show();
        $('#hide_nbn_layers').hide();
	}
	function loadBoundariesByKeyP() {
		getBoundary(null, "VR", null, null, map);
	}
	function greenZoneSort(){
			  var items = $('#Green_zones li').get();
		      items.sort(function(a,b){
		        var keyA = $(a).text();
		        var keyB = $(b).text();

		        if (keyA < keyB) return 1;
		        if (keyA > keyB) return -1;
		        return 0;
		      });
		      var ul = $('#Green_zones');
		      $.each(items, function(i, li){
		        ul.append(li);
		      });

	}
	function redZoneSort(){
				var items = $('#Red_zones li').get();
			      items.sort(function(a,b){
			        var keyA = $(a).text();
			        var keyB = $(b).text();

			        if (keyA < keyB) return 1;
			        if (keyA > keyB) return -1;
			        return 0;
			      });
			      var ul = $('#Red_zones');
			      $.each(items, function(i, li){
			        ul.append(li);
			      });
		
	}
	function amberZoneSort(){
				var items = $('#Amber_zones li').get();
			      items.sort(function(a,b){
			        var keyA = $(a).text();
			        var keyB = $(b).text();

			        if (keyA < keyB) return 1;
			        if (keyA > keyB) return -1;
			        return 0;
			      });
			      var ul = $('#Amber_zones');
			      $.each(items, function(i, li){
			        ul.append(li);
			      });
		
	}
	function blackZoneSort(){
				var items = $('#Black_zones li').get();
			      items.sort(function(a,b){
			        var keyA = $(a).text();
			        var keyB = $(b).text();

			        if (keyA < keyB) return 1;
			        if (keyA > keyB) return -1;
			        return 0;
			      });
			      var ul = $('#Black_zones');
			      $.each(items, function(i, li){
			        ul.append(li);
			      });
		
	}
        
    function placeMarkerP(location,infocontent) {
    	//console.log(infocontent);
    	zip_markerP.setMap(null);
	    zip_markerP = new google.maps.Marker({
			position: location, 
			map: map,
            icon: 'images/magic.gif'
	    });
		
	    zip_markerP.setMap(map);
            
        var zip_marker_contentString = '<div id="markerinfo" style="width:100px;">Post code - '+infocontent+'</div>';
        var zip_marker_infowindow = new google.maps.InfoWindow({
        	content: zip_marker_contentString
        });
                
        google.maps.event.clearListeners(zip_markerP, 'click');
                
        google.maps.event.addListener(zip_markerP, 'click', function() {
        	zip_marker_infowindow.open(map,zip_markerP);

        });

        //new google.maps.event.trigger( marker, 'click' );
        google.maps.event.trigger(zip_markerP, 'click');
	}
        
   function show_post_codeP() {
		$("#loading_nbn_layers").show();
		loadBoundariesP(9);
		//$("#loading_nbn_layers").hide();
	}
        
	function remove_post_codeP() {
			
			//marker.setMap(null);
			
			google.maps.event.clearListeners(map, 'click');
			
			if(typeof svgP!=='undefined') {
				svgP.selectAll("path").remove();
			}
			
			if(post_overlayP) {
				post_overlayP.setMap(null);
				post_overlayP=false;
			}
			
			curZP=0;
			$("#nbn_list_option").hide();
			$('#navigation-nbn').hide();
	        $('#show_nbn_layers').show();
	        $('#hide_nbn_layers').hide();
	        $("#Green_zones").html('');
	        $("#Red_zones").html('');
	        $("#Amber_zones").html('');
	        $("#Black_zones").html('');
	        $("#inputnbn").val('');
	        $("#nbn_postcodes").val('');
	       	$(".pre_drawn_nbn_polygons").hide();
	        closeLastOpenedInfoWindo();
	        map.controls[google.maps.ControlPosition.TOP_LEFT].clear();
	        disableAllCheckboxes();
	        $('.pre_drawn_nbn_polygons input:checked').each(function() {
		    	var tempdocid = $(this).attr('data-docid');
		        var kmlName = $(this).attr('id');
				newParserObj.hideDocument(newParserObj.docs[tempdocid]);
				$('.hitlayer'+(parseInt(tempdocid)+1)).attr("checked", false).trigger("change");
			});
			settings.drawPolygonSetting.polygons='';
			settingsUpdate();
			setPostcodes.length=0;
			setDrawnPostcodes.length=0;
			nbncolor_arr.length=0;
			color_arr={};
	}  

	
	window.onload=page_load_complete;

   $(document).ready(function(){
   

	   $( "#open_alert_form" ).click(function() {
	        $("#popup_nbn_polygon").modal("show");
		});
	     $('#title_nbn_polygon').keyup(function(){
            //console.log($("#alert_type").val().length);
        if($(this).val().length !=0 && $("#alert_type").val().length!=0)
            $('#save_nbn_alert_details').attr('disabled', false);            
        else
            $('#save_nbn_alert_details').attr('disabled',true);
	    })

	        $('#alert_type').on('change',function(){
	        if($(this).val().length !=0 && $("#title_nbn_polygon").val().length!=0)
	            $('#save_nbn_alert_details').attr('disabled', false);            
	        else
	            $('#save_nbn_alert_details').attr('disabled',true);
	    })
		listFilter($('#site_layer_title-nbn'), $('#navigation-nbn'), 'nbn');
		domReadyNBNPolygons();
 	var $click_count_new = 0;
	$(document).on("change",".load_saved_kmls",function(eve)
	{ 
		if($click_count_new == 0 ){
			$click_count_new++;
		}
		var $obj =  $(this);
		var kmlName = $obj.val();
		//console.log(kmlName);
		$obj.attr('disabled',true);
		if($obj.is(":checked"))	{
			if(!$obj.attr("data-docid")){

				/* Append data to array */
				drawPolygonArray.push($obj.attr('layer-name'));

				$obj.parent(".my_polygons").find(".draw_kml_loading").removeClass("hide");
				$obj.addClass("hide");
				/* Append data to array */

					newParserObj.parse(kmlName);
					get_kml_coordinates(kmlName,function(){
						$obj.parent(".my_polygons").find(".draw_kml_loading").addClass("hide");
						$obj.removeClass("hide");
					},docid);
				
					$obj.attr("data-docid",docid);
					docid++;
			}else{
				var tempdocid = $(this).attr("data-docid");
				newParserObj.showDocument(newParserObj.docs[tempdocid]);
				$obj.parent(".my_polygons").find(".draw_kml_loading").removeClass("hide");
				$obj.addClass("hide");
				get_kml_coordinates(kmlName,function(){
						$obj.parent(".my_polygons").find(".draw_kml_loading").addClass("hide");
						$obj.removeClass("hide");
					},tempdocid);
			}
			settings.drawPolygonSetting.status = '';
			settings.drawPolygonSetting.polygons = drawPolygonArray;
			settingsUpdate();
		  }else{
		  	$("#nbn_postcodes").val('');
		  	var setPostcode;
		  	var index;
			var postcodes=nbncolor_arr;
				for (var key in postcodes) {
					if(key!='remove'){
						var classKey = postcodes[key].split("-");
						var setPostcode=key+'-'+classKey[2];
						if(kmlName==classKey[1]){
							$("path#nbn_"+key).attr("class","");
							$("path#nbn_"+key).attr("class",classKey[0]);
							setPostcode=key+'-'+classKey[2];
					 		index = setDrawnPostcodes.indexOf(setPostcode);
					 		if (index > -1) {
							    setDrawnPostcodes.splice(index, 1);
							}
						}
					}
				}
			
			$("#nbn_postcodes").val(setDrawnPostcodes);
			if(setDrawnPostcodes.length>0){
		            $("#open_alert_form").show();
		        }else{
		            $("#open_alert_form").hide();
		                   
		     }	
			var removeItemdeselect = $(this).attr('layer-name');
			public_checked_options_new = $.grep(settings.drawPolygonSetting.polygons, function(value) {
                  return value != removeItemdeselect;
            });
            settings.drawPolygonSetting.polygons = public_checked_options_new;
			settingsUpdate();
			var tempdocid = $(this).data("docid");
			console.log(tempdocid);
			newParserObj.hideDocument(newParserObj.docs[tempdocid]);
		}
		$(this).attr('disabled',false);
	});
}); 


   var callback;
   function page_load_complete(){
	newLayerParser();

	/* On page load display polygons */
	//console.log(settings.drawPolygonSetting.polygons);

	if(settings.drawPolygonSetting.polygons!==undefined){
		for (var i = 0; i < settings.drawPolygonSetting.polygons.length; i++)
		{
			var layer_id = settings.drawPolygonSetting.polygons[i];
			var kmlNameNew = $('input[layer-name="'+layer_id+'"]').attr("name");
			$('input[layer-name="'+layer_id+'"]').prop('checked', true);
			//console.log(layer_id);
			newParserObj.parse(kmlNameNew);
			get_kml_coordinates(kmlNameNew,callback,docid);
			$('input[layer-name="'+layer_id+'"]').attr("data-docid",docid);
			docid++;
		}

    }else{
    	settings.drawPolygonSetting.polygons='';
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
          	success:function(res){},
			error: function(res){}
		});
		
	}
}


function get_kml_coordinates(kmlNameNew,callback,docid){
	$.ajax({
		  url:kmlNameNew
		  ,dataType:"xml"
		  ,success:function(xml){
		     var c =$(xml).find('coordinates').text();
		     //console.log(c.trim());
		     var coordinates=c.trim();
		     getAffectedPostcodesOnLoad(coordinates,kmlNameNew,docid);
		   	 callback();
		   }

		});
}
function removedSavedPolygons(){
		$('.pre_drawn_nbn_polygons input:checked').each(function() {
		    var tempdocid = $(this).attr('data-docid');
		     var kmlName = $(this).attr('id');
			newParserObj.hideDocument(newParserObj.docs[tempdocid]);
			var postcodes=nbncolor_arr;
				for (var key in postcodes) {
					if(key!='remove'){
						var classKey = postcodes[key].split("-");
						if(kmlName==classKey[1]){
							$("path#nbn_"+key).attr("class","");
							$("path#nbn_"+key).attr("class",classKey[0]);
						}
					}
				}
			$('.hitlayer'+(parseInt(tempdocid)+1)).attr("checked", false).trigger("change");	
		});
		for (var key in seleced_nbnarr) {
					if(key!='remove'){
						$("path#nbn_"+key).attr("class","");
						$("path#nbn_"+key).attr("class",seleced_nbnarr[key]);
					}
		}
		$("#nbn_postcodes").val('');
		$("#open_alert_form").hide();
}
function disableSavedPolygons(){
		$('.pre_drawn_nbn_polygons input:checked').each(function() {
		    var tempdocid = $(this).attr('data-docid');
		     var kmlName = $(this).attr('id');
			newParserObj.hideDocument(newParserObj.docs[tempdocid]);
			var postcodes=nbncolor_arr;
				for (var key in postcodes) {
					if(key!='remove'){
						var classKey = postcodes[key].split("-");
						if(kmlName==classKey[1]){
							$("path#nbn_"+key).attr("class","");
							$("path#nbn_"+key).attr("class",classKey[0]);
						}
					}
				}
			$('.hitlayer'+(parseInt(tempdocid)+1)).attr("checked", false).trigger("change");	
		});
		for (var key in seleced_nbnarr) {
					if(key!='remove'){
						$("path#nbn_"+key).attr("class","");
						$("path#nbn_"+key).attr("class",seleced_nbnarr[key]);
					}
				}
		$("#nbn_postcodes").val('');
		$("#open_alert_form").hide();
		$(".pre_drawn_nbn_polygons input:checkbox").attr("disabled", "disabled");
}
function enableSavedPolygons(){
		$(".pre_drawn_nbn_polygons input:checkbox").attr("disabled", false);
}
function disableAllCheckboxes(){
	$("#drawing_mode").attr("checked", false).trigger("change");
	$("#drawing_mode").attr("disabled", true);
}
function enableAllCheckboxes(){
	$("#drawing_mode").attr("disabled", false);
	$(".load_saved_kmls").attr("disabled", false);
}

function removeSelectedZones(){
			for (var key in seleced_nbnarr) {
					if(key!='remove'){
						$("path#nbn_"+key).attr("class","");
						$("path#nbn_"+key).attr("class",seleced_nbnarr[key]);
					}
				}
			setDrawnPostcodes.length=0;
			$("#open_alert_form").hide();
			$("#nbn_postcodes").val('');	
}
disableAllCheckboxes();
