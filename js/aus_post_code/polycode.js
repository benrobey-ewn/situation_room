	var useAjaxP = false;
	var drawnOnceP = false;
    var zip_markerP=new google.maps.Marker({});
	var activeP, basinPathsP, googleMapProjectionP, overlayProjectionP, curZP, pathP, ignoreClickP = false;
    // var map;
    var width = 960, height = 650;
    var currentPolyObj=[];
    var curr_user_access=$("#nbn_user_type").val();
	var lastOpenedInfoWindow;
	var bbox=[];
	var postCoordinates=[];
	var topoObject=[];
	var nbndocid=0;
	// var nbnParserObject;
	var drawPolygonArray = [];
	var newParserObj;
	var polygonPath = [];
	var docid=0;
	var currentPolygonObj = "";
	var nbndrawingManager ="";
	var postdids;
	var listArray;
	var seleced_nbnarr=[];
	var setDrawnPostcodes=[];
	var color_arr = {};	
	var setPostcodes =[];
	var ajaxResponse; 
	var nbncolor_arr = [];	
	var setLoadPostcodes=[];
	var html;
	var suburbs;
	var checkClass;
	var appendhtml;
	var listpostcodes;
	var label_class;
	var allD3bounds = [];
	var layerP = false;
	var svgP = false;


	function show_nbn_loader() {
	$("div.nbn-data-layers").find(".heading-name").after('<span class="ajaxwaitobj gauge-load"><i class=" fa fa-spinner fa-pulse icon-spin fa-2x"></i></span>');
	}

	function hide_nbn_loader() {
		$("div.nbn-data-layers").find('span.ajaxwaitobj.gauge-load').remove();	
	}

	function loadLayer(path) {
		d3.json(path, function (error, topology) {

			/*if (post_overlayP) {
			 post_overlayP.setMap(null);
			 }*/
			var post_overlayP = new google.maps.OverlayView();

			// Add the container when the overlay is added to the map.

			post_overlayP.onAdd = function () {
				//console.log("overlay onAdd");
				if(layerP == false) {
					layerP = d3.select(this.getPanes().overlayMouseTarget).append("div").attr("class", "SvgOverlayP basins");
				}
				//console.log(svgP);
				if(svgP == false) {
					svgP = layerP.append("svg").attr("width", width).attr("height", height);
				}
				var basinsP = svgP.append("g").attr("class", "basins");
				var geometries=topology.objects.boundary.geometries;
				//console.log(geometries.length);
				for (var i = 0; i < geometries.length; i++) {
					var pathid=geometries[i].id;
					if(topoObject[pathid] === undefined){
						topoObject[pathid] = geometries[i].properties;
					}
				}
				post_overlayP.draw = function () {
					var markerOverlay = this;
					var overlayProjectionP = this.getProjection();
					// Turn the overlay projection into a d3 projection
					var googleMapProjectionP = function (coordinates) {
						//console.log(coordinates);
						var googleCoordinates = new google.maps.LatLng(coordinates[1], coordinates[0]);
						var pixelCoordinates = overlayProjectionP.fromLatLngToDivPixel(googleCoordinates);
						return [pixelCoordinates.x + 4000, pixelCoordinates.y + 4000];
					}
					var pathP = d3.geo.path().projection(googleMapProjectionP);

					var basinPathsP = basinsP.selectAll("path")
						.data(topojson.feature(topology, topology.objects.boundary).features)
						.attr("d", pathP)
						.enter().append("path")
						.attr("d", pathP)
						.attr("class", function(d){
								if(checkClass.classes.hasOwnProperty(d.properties.name)){
									return checkClass.classes[d.properties.name];
								}else{
									return 'activeG';
								}
							}

						)
						.attr("id", function(d){return "nbn_"+d.properties.name;})
						.attr("title", function(d) {
							var qhtml;
							qhtml ='<div class="accordion_container"><a class="accordion_head" onclick="toggleModeOnOff(\'' + d.properties.name + '\');">'+d.properties.name+'<span class="hoverplusminus">+</span></a><div class="accordion_body" id="suburb_'+d.properties.name+'" style="display: none;overflow-y:scroll !important;max-height:400px;"><div>';
							return qhtml;
						})
						.on('click', clickP)
						.append("svg:title");

				};

			};


			post_overlayP.onRemove = function () {
				console.log("overlay onRemove");
			};
			// Bind our overlay to the map
			// Bind our overlay to the map
			post_overlayP.setMap(map);


		});
	}

    function loadBoundariesP(z) {
		show_nbn_loader();
		if (z == curZP)
			return;
		curZP = z;
		var bTypeKey = 'PC';

		useAjaxP = false;

		/* @todo ALL THE BAD THINGS ARE HERE */

		//var path = "/js/boundaries/b-" + bTypeKey + "-geo-topo-" + z + ".json";

		//i don't know how this shit is supposed to work so I'm going to load everything
		var base = "/js/boundaries/";

		var paths = [
			'b-PC-2-geo-topo-9.json',
			'b-PC-3-geo-topo-9.json',
			'b-PC-4-geo-topo-9.json',
			'b-PC-5-geo-topo-9.json',
			'b-PC-6-geo-topo-9.json',
			'b-PC-7-geo-topo-9.json'
		];

		paths.forEach(function(path) {
			loadLayer(base+path);
		});

		window.setTimeout(function() {
			$('svg path').qtip({
				overwrite: true,
				content: {
					text: function(d) {
						return ($(this).attr("title"));//returning the tooltip
					}
				},
				position: {
					/*viewport: $(window),*/
					my: 'top right',  // Position my top left...
					at: 'top center', // at the bottom right of...
				},
				hide: {
					fixed: true,
					delay: 300
				},
				style: 'qtip-light'
			});
		}, 2000);


		var centerControlDiv = document.createElement('div');
		var centerControl = new CenterControl(centerControlDiv, map);
		centerControlDiv.index = 1;
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(centerControlDiv);

		hide_nbn_loader();
		$('#loader').hide();
		$("#nbn_list_option").show();
		$("#loading_nbn_layers").hide();
		$("#drawing_mode").attr("disabled", false);
		enableSubjectTextBox();
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
					temp_status_flag = true;
				} else {
					nbndrawingtoolsDeac();
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
			removeSelectedZones();
			//removeSetValue();
		  
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
	
	//Click Event on Polygon
	function clickP(d) {
		console.log('sdfsdf')
		if($("#drawing_mode").prop('checked') != true){
			$("#alert_id").val('');
			var temp_id = d.id;
			var postid = d.properties.name;
			var setPostcode=postid;
			var $path = $("path#nbn_"+postid);
			if(curr_user_access=='full_access'){
				var classP = $path.attr('class');

			if(color_arr[postid] === undefined){
				color_arr[postid] = classP;
					$path.attr("class","");
				  	$path.attr("class","active");
			} else {
				  	$path.attr("class","");
				  	$path.attr("class",color_arr[postid]);
					delete color_arr[postid];
			}
           if(setPostcodes.indexOf(setPostcode) == -1){
           		html ='<div class="accordion_head_post_sel" id="head_'+postid+'">'+postid+'<span id="plusminus_'+postid+'" class="plusminus" onclick="tabToggleModeSub(\'' + postid + '\')">+</span></div><div class="accordion_body_post" id="content_'+postid+'" style="display: none;">';
       		 	html +='</div>';
				$("#selected_zones").append(html);
                setPostcodes.push(setPostcode);
             } else {
             	 $( "#head_"+postid).remove();
             	 $( "#content_"+postid).remove();
                setPostcodes.remove(setPostcode);
            }
		    if(setPostcodes.length>0){
		    	if(setPostcodes.length==1){
	    			var getpostcode = setPostcodes[0];
	    			console.log(getpostcode);
	    			if(color_arr[getpostcode]!='activeG'){
	    				var getDetails=getAlertDetails(getpostcode);
	    				var getalertdetails=getDetails.alertdetails;
	    				console.log(getalertdetails);
	    				$("#title_nbn_polygon").val(getalertdetails[0]);
						$("#nbn_description_polygon").val(getalertdetails[1]);
	    			}else{
	    				$("#title_nbn_polygon").val('');
						$("#nbn_description_polygon").val('');
	    			}
	    		}else{
	    				$("#title_nbn_polygon").val('');
						$("#nbn_description_polygon").val('');
	    		}
		  		nbnMainShow($('#nbn_zones_div'));
		  		nbnMainHide('#nbn_zones_alert');
				$("#drawing_mode").attr("disabled", true);
                }else{
                nbnMainHide('#nbn_zones_div');
                nbnMainHide('#nbn_zones_alert');
                $("#drawing_mode").attr("disabled", false);
            }
		        $("#nbn_postcodes").val(setPostcodes);
		        $("#send_alert_type").val('click');
			  }
	  	}else{
	  		//alert("Please Disable Drawing Tools First");
	  }
	}
	//Click Even on Polygon
	var subhtml;
	function setSuburbName(postcode){
		$("#content_"+postcode).html('');
		suburbs=getSuburbsName(postcode);
				subhtml='';
       		 	for (var i = 0; i < suburbs.length; i++) {
       		 		if(suburbs[i]!=undefined){
       		 			subhtml +='<p>'+suburbs[i]+'</p>';
       		 		}
       	}
       	$("#content_"+postcode).append(subhtml);
	}

	 //Removde value from array function  
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
	//Removde value from array function 


	//Get current threat level of polygon
	function getClassFromDb(){
		$.ajax({
		    async: false, 
		    dataType: "json",
		    url: "ajax/get_class.php",
		    success : function (response)
		              {
		                  ajaxResponse = response;
		                  for(var key in response.classes) {
								var currclass = response.classes[key];
								$("path#nbn_"+key).attr("class",currclass);
							}
		              },
		});

		return ajaxResponse;
	    
	}
	//Get current threat level of polygon

	//Get Alert Details
	function getAlertDetails(postcode){
		$.ajax({
		    async: false, 
		    dataType: "json",
		    data:{postcode:postcode},
		    url: "ajax/get_alert_by_id.php",
		    success : function (response)
		              {
		                  ajaxResponse = response;
		              },
		});

		return ajaxResponse;
	    
	}
	//Get Alert Details

	//Get Suburb Name
	function getSuburbsName(postcode){
		$.ajax({
		    async: false, 
		    dataType: "json",
		    url: "ajax/get_suburbname.php",
		    data : { postcode : postcode},
		    success : function (response)
		              {
		                  ajaxResponse = response;
		              },
		});
		return ajaxResponse;
	}
	//Get Suburb Name

	//Get Affected Postcodes on Drawn polygon
	var sortPostcode= [];
	function getAffectedPostcodes(nbnpolygonArray){
		var formdata = $.param({ 'polygonArr': nbnpolygonArray});
		$("#title_nbn_polygon").val('');
		$("#nbn_description_polygon").val('');
		$("#alert_id").val('');
        $.ajax({
		    async: false, 
		    dataType: "json",
		    type: 'POST',
		    data:formdata,
		    url: "ajax/get_affected_postcodes.php",
		    success : function (res)
		              {
		              	sortPostcode.length=0;
						var postcodes=res.postcodes;
						if(postcodes!='error'){
							console
								for (var j = 0; j < postcodes.length; j++) {
									sortPostcode.push(topoObject[postcodes[j]].name);
								} 
								sortPostcode.sort(function(a, b){return a-b});
								console.log(sortPostcode);
								for(var k = 0; k < sortPostcode.length; k++){

									    var postid=sortPostcode[k];
			   						    var setPostcode=postid;
									    var $path = $("path#nbn_"+postid); 
									    var classP = $path.attr('class'); 
									  if(color_arr[postid] === undefined){
											color_arr[postid] = classP;
												$path.attr("class","");
											  	$path.attr("class","active");
										} else {
											  	$path.attr("class","");
											  	$path.attr("class",color_arr[postid]);
												delete color_arr[postid];
										}
							             if(setDrawnPostcodes.indexOf(setPostcode) == -1){
								           		html ='<div class="accordion_head_post_sel" id="head_'+postid+'">'+postid+'<span id="plusminus_'+postid+'" class="plusminus" onclick="tabToggleModeSub(\'' + postid + '\')">+</span></div><div class="accordion_body_post" id="content_'+postid+'" style="display: none;">';
								       		 	html +='</div>';
												$("#selected_zones").append(html);
								                setDrawnPostcodes.push(setPostcode);
								             } else {
								             	 $( "#head_"+postid).remove();
								             	 $( "#content_"+postid).remove();
								                setDrawnPostcodes.remove(setPostcode);
								            }
										 $("path#nbn_"+postid).attr("class","active");
							}
							if(setDrawnPostcodes.length>0){
							        $("#legend-container-content").show();
							        nbnMainShow($('#nbn_zones_div'));
							    	
			                 }else{
								 	nbnMainHide($('#nbn_zones_div'));
			           		}
			           		 $("#nbn_postcodes").val(setDrawnPostcodes);	

						}else{
							alert("An unknown error occurred please try again");
							if(currentPolygonObj!==undefined && currentPolygonObj!=""){
								       currentPolygonObj.setMap(null);
							}
						}
						
		              },
		    // other properties
		});
		//return ajaxResponse;
	}
	//Get Affected Postcodes on Drawn polygon
	
	function confirm_alert()
	{
	    var status= confirm("Are you sure wish to send this alert?");
	    if (status== true){
						$("#save_nbn_alert_details").html('<i class="fa fa-spinner fa-pulse icon-spin "></i> Loading...').attr('disabled',true);
						 var formData=$("#save_nbn_alert_form").serialize();
				      	 setTimeout(function(){ 
				      	 $.ajax({
							    async: true, 
							    dataType: "json",
							    url: "ajax/send_ewn_alert.php",
							    type: 'POST',
							    data: formData,
							    success : function (response) {
							    	var postdata=response.postdata;
							    	var alert_id=response.alert_id;
				                  	var alert_title=response.alert_title;
				                  	var alert_color=response.alert_color;
				                  	var alert_desc=response.alert_description;
				                  	var status=response.status;

				                  	if(status=='multipleupdate'){
				                  		for (var i = 0; i < postdata.length; i++) {
							                  	var selpostcode=postdata[i].postcode;
							                  	var selclass=postdata[i].class;
							                  	var selcolor=postdata[i].color;
							                  	$("path#nbn_"+selpostcode).attr("class","");
												$("path#nbn_"+selpostcode).attr("class",selclass);
							       				setPostcodes.remove(selpostcode);
							       				delete color_arr[selpostcode];
							       			}
							       			$("#combobox option[value='"+alert_title+"']").remove();
						                	$( "#"+alert_id ).remove();
						                	$( "#nbn_alerts_"+alert_id ).remove();
						                	$(".alert_subject_link").each(function(){
							         			$(this).attr("disabled",false).removeClass("disabled").attr("style","color:#3d9cd3");
							     			});

				                  	}else if(status=='update'){
				                  			var prev_alert_id=response.prev_alert_id;

				                  			$( "#"+prev_alert_id ).remove();
						                	$( "#nbn_alerts_"+prev_alert_id ).remove();

					                  		if(alert_color=='Red'){
										    	label_class='danger';
											 }
									    	if(alert_color=='Black'){
									    		label_class='black';
									    	}
									    	if(alert_color=='Amber'){
									    		label_class='warning';
									    	}
											  appendhtml='';
											  listpostcodes='';
						                  for (var i = 0; i < postdata.length; i++) {
							                  	var selpostcode=postdata[i].postcode;
							                  	var selclass=postdata[i].class;
							                  	var selcolor=postdata[i].color;
							                  	if (postdata[i].current_thread!='null' && postdata[i].current_alert_id!='null') {
							                  		var current_thread=postdata[i].current_thread;
							                  		var current_alert_id=postdata[i].current_alert_id;
						                  			$('#nbn_alerts_'+current_alert_id).find('li#'+selpostcode).remove();
													
												}
							                  	var suburbs=postdata[i].suburb;
							                  	var suburbhtml='';
							                  	for (var j = 0; j < suburbs.length; j++) {
							           		 		if(suburbs[j]!=undefined){
							           		 			suburbhtml +='<p>'+suburbs[j]+'</p>';
							           		 		}
							           		 	}
										   
										    listpostcodes +='<li id="'+selpostcode+'" postcode="'+selpostcode+'"><div class="accordion_head_post">'+selpostcode+'<span class="plusminus">+</span></div><div style="display: none;" id="content" class="accordion_body_post">'+suburbhtml+'</div></li>';
							                  	$("path#nbn_"+selpostcode).attr("class","");
												$("path#nbn_"+selpostcode).attr("class",selclass);
							       				setPostcodes.remove(selpostcode);
							       				delete color_arr[selpostcode];
							       			}
							       			if(alert_color!='Green'){
								       			if ($( "#"+alert_id ).length>0) {
												    if($("#"+alert_color+"_zones_"+alert_id).length>0){
												   		$("#"+alert_color+"_zones_"+alert_id).append(listpostcodes);
												    }else{
												    	appendhtml='<span class="label label-'+label_class+'" id="label_'+label_class+'_'+alert_id+'" style="text-align:left;">'+alert_color+'</span><ul id="'+alert_color+'_zones_'+alert_id+'">'+listpostcodes+'</ul>';
												    	$("#nbn_alerts_"+alert_id).append(appendhtml);
												    }
												}else{
														appendhtml='<li id="'+alert_id+'" class="alert_thread"><span class="mainplusminus" onclick="toggleModeSub('+alert_id+');" id="mainplusminus_'+alert_id+'">+</span><a href="javascript:void(0);" class="alert_subject_link" onclick="selAlerts('+alert_id+');" id="'+alert_id+'">'+alert_title+'</a></li><input type="hidden" id="nbn-subject-'+alert_id+'" name="subject" value="'+alert_title+'"><input type="hidden" id="nbn-description-'+alert_id+'" name="description" value="'+alert_desc+'"><div class="alert_thread_body" style="display: none;" id="nbn_alerts_'+alert_id+'"><span class="label label-'+label_class+'" style="text-align:left;" id="label_'+label_class+'_'+alert_id+'">'+alert_color+'</span><ul id="'+alert_color+'_zones_'+alert_id+'">'+listpostcodes+'</ul></div>';
												    	$("#nbn_zones_list").append(appendhtml);
												    	$("#combobox").append('<option value="'+alert_title+'">'+alert_title+'</option>');
												}
							       			}
											if(!$('#Red_zones_'+alert_id+' li').length && alert_color!='Red'){
												$('#label_danger_'+alert_id).remove();
											}
											if(!$('#Black_zones_'+alert_id+' li').length && alert_color!='Black'){
												$('#label_black_'+alert_id).remove();
											}
											if(!$('#Amber_zones_'+alert_id+' li').length && alert_color!='Amber'){
												$('#label_warning_'+alert_id).remove();
											}
											if(currentPolygonObj!==undefined && currentPolygonObj!=""){
								        		currentPolygonObj.setMap(null);
								       		}
						                
				                  	}else if(postdata=='error'){
				                  			alert("An unknown error occurred please try again");
				                  			for (var key in color_arr) {
												if(key!='remove'){
													$("path#nbn_"+key).attr("class","");
													$("path#nbn_"+key).attr("class",color_arr[key]);
												}
											}

				                  	}else{
					                  		if(alert_color=='Red'){
										    	label_class='danger';
											 }
									    	if(alert_color=='Black'){
									    		label_class='black';
									    	}
									    	if(alert_color=='Amber'){
									    		label_class='warning';
									    	}
											  appendhtml='';
											  listpostcodes='';
						                  for (var i = 0; i < postdata.length; i++) {
							                  	var selpostcode=postdata[i].postcode;
							                  	var selclass=postdata[i].class;
							                  	var selcolor=postdata[i].color;
							                  	if (postdata[i].current_thread!='null' && postdata[i].current_alert_id!='null') {
							                  		var current_thread=postdata[i].current_thread;
							                  		var current_alert_id=postdata[i].current_alert_id;
						                  			$('#nbn_alerts_'+current_alert_id).find('li#'+selpostcode).remove();
						                  			if ($('ul#Black_zones_'+current_alert_id+' li').size() < 1 && $('ul#Red_zones_'+current_alert_id+' li').size() < 1  && $('ul#Amber_zones_'+current_alert_id+' li').size() < 1 ) {
														//$('ul#'+current_thread+'_zones_'+current_alert_id).remove();
														//$('#label_'+returnAlertColor(current_thread)+'_'+current_alert_id).remove();
														$( "#"+current_alert_id ).remove();
						                				$( "#nbn_alerts_"+current_alert_id ).remove();
													}
												}
							                  	var suburbs=postdata[i].suburb;
							                  	var suburbhtml='';
							                  	for (var j = 0; j < suburbs.length; j++) {
							           		 		if(suburbs[j]!=undefined){
							           		 			suburbhtml +='<p>'+suburbs[j]+'</p>';
							           		 		}
							           		 	}
										    	listpostcodes +='<li id="'+selpostcode+'" postcode="'+selpostcode+'"><div class="accordion_head_post">'+selpostcode+'<span class="plusminus">+</span></div><div style="display: none;" id="content" class="accordion_body_post">'+suburbhtml+'</div></li>';
							                  	$("path#nbn_"+selpostcode).attr("class","");
												$("path#nbn_"+selpostcode).attr("class",selclass);
							       				setPostcodes.remove(selpostcode);
							       				delete color_arr[selpostcode];
							       			}
							       			if(alert_color!='Green'){
								       			if ($( "#"+alert_id ).length>0) {
												    if($("#"+alert_color+"_zones_"+alert_id).length>0){
												   		$("#"+alert_color+"_zones_"+alert_id).append(listpostcodes);
												    }else{
												    	appendhtml='<span class="label label-'+label_class+'" id="label_'+label_class+'_'+alert_id+'" style="text-align:left;">'+alert_color+'</span><ul id="'+alert_color+'_zones_'+alert_id+'">'+listpostcodes+'</ul>';
												    	$("#nbn_alerts_"+alert_id).append(appendhtml);
												    }
												}else{
														appendhtml='<li id="'+alert_id+'" class="alert_thread"><span class="mainplusminus" onclick="toggleModeSub('+alert_id+');" id="mainplusminus_'+alert_id+'">+</span><a href="javascript:void(0);" class="alert_subject_link" onclick="selAlerts('+alert_id+');" id="'+alert_id+'">'+alert_title+'</a></li><input type="hidden" id="nbn-subject-'+alert_id+'" name="subject" value="'+alert_title+'"><input type="hidden" id="nbn-description-'+alert_id+'" name="description" value="'+alert_desc+'"><div class="alert_thread_body" style="display: none;" id="nbn_alerts_'+alert_id+'"><span class="label label-'+label_class+'" style="text-align:left;" id="label_'+label_class+'_'+alert_id+'">'+alert_color+'</span><ul id="'+alert_color+'_zones_'+alert_id+'">'+listpostcodes+'</ul></div>';
												    	$("#nbn_zones_list").append(appendhtml);
												    	$("#combobox").append('<option value="'+alert_title+'">'+alert_title+'</option>');
												}
							       			}
											if(!$('#Red_zones_'+alert_id+' li').length && alert_color!='Red'){
												$('#label_danger_'+alert_id).remove();
											}
											if(!$('#Black_zones_'+alert_id+' li').length && alert_color!='Black'){
												$('#label_black_'+alert_id).remove();
											}
											if(!$('#Amber_zones_'+alert_id+' li').length && alert_color!='Amber'){
												$('#label_warning_'+alert_id).remove();
											}
											if(currentPolygonObj!==undefined && currentPolygonObj!=""){
								        		currentPolygonObj.setMap(null);
								       		}
				                  	}
				                  	$("#alert_id").val('');
					       			setPostcodes.length=0;
					       			setDrawnPostcodes.length=0;
					       			$("#selected_zones").html('');
									$("#nbn_postcodes").val('');
									alert("Alert has been sent successfully."); 
					       			$("#save_nbn_alert_details").html('Send');
					       		   	$('#save_nbn_alert_form')[0].reset();
					       		   	$("#drawing_mode").attr("disabled", false);
					       		   	nbnMainHide($("#nbn_zones_alert"));
					       		   	nbnMainHide('#nbn_zones_div');
					       		   	enableSubjectTextBox();
							    },
							    error: function(response){
							    	 $("#save_nbn_alert_details").html('Send').attr('disabled',false);
							    	 alert("Alert send un-successful, Please try again!"); 

							    }
							    
							});
				      	},50);
	    }else{
	        return false;
	    }
	}

	function show_nbn_layers()
	{
        if (!$("#legend-container-content").is(':visible')) {
        	$("#legend-container-content").show();
        	nbnMainShow($('#navigation-nbn'));
    	}else{
    		nbnMainShow($('#navigation-nbn'));
    	}
        $('#show_nbn_layers').hide();
        $('#hide_nbn_layers').show();

	}
	function hide_nbn_layers(){
        $('#navigation-nbn').hide();
        $('#show_nbn_layers').show();
        $('#hide_nbn_layers').hide();
        hideAllTabs();
	}
	function loadBoundariesByKeyP() {
		getBoundary(null, "VR", null, null, map);
	}
   function show_post_codeP() {
   		checkClass=getClassFromDb();
		$("#loading_nbn_layers").show();
		loadBoundariesP(9);
	}
	function remove_post_codeP() {
		
		if(typeof svgP!=='undefined') {
				svgP.selectAll("path").remove();
			}
			
			if(post_overlayP) {
				post_overlayP.setMap(null);
				post_overlayP=false;
			}
			
		curZP=0;
		if(currentPolygonObj!==undefined && currentPolygonObj!=""){
			currentPolygonObj.setMap(null);
		}
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
        nbnMainHide('#nbn_zones_div');
        nbnMainHide('#nbn_zones_alert');
        nbnMainHide('#navigation-nbn');
        $("#selected_zones").html('');
        map.controls[google.maps.ControlPosition.TOP_LEFT].clear();
		removeAllSetValues();
		disableAllCheckboxes();
		setPostcodes.length=0;
		setDrawnPostcodes.length=0;
	}  
	var hoverhtml;
	function toggleModeOnOff(postcode){
		console.log(postcode);
		hoverhtml='';
		if ($('.accordion_body').is(':visible')) {
            $(".accordion_body").slideUp(300);
            $(".hoverplusminus").text('+');
            $(".accordion_body").html("");
        }else{
        	$(".accordion_body").html("");
        	suburbs=getSuburbsName(postcode);
   		 	for (var i = 0; i < suburbs.length; i++) {
   		 		if(suburbs[i]!=undefined){
   		 			hoverhtml +='<p>'+suburbs[i]+'</p>';
   		 		}
   		 	}
   		 	$("#suburb_"+postcode).html(hoverhtml);
        	$('.accordion_body').is(':visible');
        	$(".accordion_body").slideDown(300);
            $(".hoverplusminus").text('-');
        }

	} 
	function toggleModeSub(alertid){
			if ($('#nbn_alerts_'+alertid).is(':visible')) {
			    $('#nbn_alerts_'+alertid).slideUp(300);
			    $("#mainplusminus_"+alertid).text('+');
			} else {
			    $('#nbn_alerts_'+alertid).slideDown(300);
			    $("#mainplusminus_"+alertid).text('-');
		}
	}
	function tabToggleModeSub(id){

			if ($('#content_'+id).is(':visible')) {
			    $('#content_'+id).slideUp(600);
			    $("#plusminus_"+id).text('+');
			} else {
				$("#content_"+id).html('');
				suburbs=getSuburbsName(id);
						subhtml='';
		       		 	for (var i = 0; i < suburbs.length; i++) {
		       		 		if(suburbs[i]!=undefined){
		       		 			subhtml +='<p>'+suburbs[i]+'</p>';
		       		 		}
       			}
       			$("#content_"+id).append(subhtml);
			    $('#content_'+id).slideDown(300);
			    $("#plusminus_"+id).text('-');
		}

	}
	$(document).ready(function(){
		$('.legend-main').draggable({
			    handle: 'h1'
		})
   		$(".accordion_head_post").live('click',function (event) {
   			event.preventDefault();
	        /*if ($('.accordion_body_post').is(':visible')) {
	            $(".accordion_body_post").slideUp(300);
	            $(".plusminus").text('+');
	        }*/
	        if ($(this).next(".accordion_body_post").is(':visible')) {
	            $(this).next(".accordion_body_post").slideUp(1000);
	            $(this).children(".plusminus").text('+');
	        } else {
	            $(this).next(".accordion_body_post").slideDown(300);
	            $(this).children(".plusminus").text('-');
	        }
	    });
		/*$(".alert_thread").live('click',function () {
			
		});*/

	   $( "#open_alert_form" ).live('click',function() {
	       	nbnMainShow($("#nbn_zones_alert"));
	        nbnMainHide($("#nbn_zones_div"));
		});
	  
   /*$("#title_nbn_polygon" ).autocomplete({
			     source: "ajax/get_alert.php",
			      open: function() {
				        $(this).autocomplete("widget")
				               .appendTo(".autosearch")
				               .css("position", "static");
				    },
		         select: function(event, ui) {
			        event.preventDefault();
			        $("#title_nbn_polygon").val(ui.item.label);
			}
	    })*/
	   $("#cancel_alert").click(function(){
	   			for (var key in color_arr) {
					if(key!='remove'){
						$("path#nbn_"+key).attr("class","");
						$("path#nbn_"+key).attr("class",color_arr[key]);
						delete color_arr[key];
					}
				}
			if(currentPolygonObj!==undefined && currentPolygonObj!=""){
	        	currentPolygonObj.setMap(null);
	        }	
			removeSetValue();
			$("#drawing_mode").attr("disabled", false);
	   		enableSubjectTextBox();
	   });
	   $("#cancel_polygon_details").click(function(){
	   		nbnMainHide('#nbn_zones_alert');
	   			for (var key in color_arr) {
					if(key!='remove'){
						$("path#nbn_"+key).attr("class","");
						$("path#nbn_"+key).attr("class",color_arr[key]);
						delete color_arr[key];
					}
				}
				for (var key in seleced_nbnarr) {
					if(key!='remove'){
						$("path#nbn_"+key).attr("class","");
						$("path#nbn_"+key).attr("class",seleced_nbnarr[key]);
						delete seleced_nbnarr[key];
					}
				}
			if(currentPolygonObj!==undefined && currentPolygonObj!=""){
	        	currentPolygonObj.setMap(null);
	        }	
			removeSetValue();
			$("#drawing_mode").attr("disabled", false);
			enableSubjectTextBox();	
	   		
	   });
	   $("#nbn_zones").click(function(){
	   		if (!$("#navigation-nbn").find('.legend-content-body').is(':visible')) {
					$("#navigation-nbn").find('.legend-content-body').slideDown(400);
					$("#navigation-nbn").find('.rotate').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
					$("#navigation-nbn").find("#inputnbn").show();
					$("#navigation-nbn .alert_thread_body").slideUp(300);
					$("#navigation-nbn .mainplusminus").text('+');
					$("#navigation-nbn .accordion_body_post").slideUp(300);
	            	$("#navigation-nbn .plusminus").text('+');
				}
				else {
					$("#navigation-nbn").find('.legend-content-body').slideUp(400);
					$("#navigation-nbn").find('.rotate').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
					$("#navigation-nbn").find("#inputnbn").hide();
				}

	   });
	    $("#nbn_selected_zones").click(function(){
	   		if (!$("#nbn_zones_div").find('.legend-content-body').is(':visible')) {
					$("#nbn_zones_div").find('.legend-content-body').slideDown(400);
					$("#nbn_zones_div").find('.rotate').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
				}
				else {
					$("#nbn_zones_div").find('.legend-content-body').slideUp(400);
					$("#nbn_zones_div").find('.rotate').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
				}

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
		nbnlistFilter($('#site_layer_title-nbn'), $('#navigation-nbn'), 'nbn');
		domReadyNBNPolygons();


		//Auto Search
		 $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            classes: {
              "ui-tooltip": "ui-state-highlight"
            }
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Subjects" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .on( "mousedown", function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .on( "click", function() {
            input.trigger( "focus" );
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );

          });
           $(".ui-autocomplete-input").attr("name", 'title_nbn_polygon');
           $(".ui-autocomplete-input").attr("id", 'title_nbn_polygon');
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
 
    $( "#combobox" ).combobox(); 
		//Auto Search
}); 
function removeAllSetValues(){
	setPostcodes.length=0;
	nbncolor_arr.length=0;
	color_arr={};
}
function removeSelectedZones(){
			for (var key in color_arr) {
					if(key!='remove'){
						$("path#nbn_"+key).attr("class","");
						$("path#nbn_"+key).attr("class",color_arr[key]);
					}
				}
			setDrawnPostcodes.length=0;
			color_arr={};
			$("#nbn_postcodes").val('');
			$("#selected_zones").html('');
			html='';	
			nbnMainHide("#nbn_zones_div");
			nbnMainHide("#nbn_zones_alert");
}
function nbnMainShow(e) {
	var e = $(e);
	var eAttribute = e.attr('id');
	if (!$("#legend-container-content").is(':visible')) {
		$("#legend-container-content").show();
	}
	//$(e).css({"bottom": "auto", "display": "block","left": "610px","top": "48px"}); 
	if (e.parent().hasClass('legend-container-content')) {
		var container = $('.legend-container');
		var legends = container.find(".legend-main .legend-content-body");
	}
	e.find('.legend-content-body').slideDown(400);
	e.find('.rotate').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
	e.show();
	window.setTimeout(function () { legendMoveIntoMap(e); }, 500);

	if ($('.legend-container-content > div').children().size() > 0) {
		if ($('.legend-container-content').hasClass('no-legends-collapsed')) {
			$('.legend-container-content').removeClass('no-legends-collapsed');
			$('.legend-container-content').fadeIn();
		}
	}
	 
}
function nbnMainHide(e) {
	var e = $(e);
	if (!$('.legend-container-content > div').children(':visible').size() > 0) {
		$('.legend-container-content').removeClass('no-legends-collapsed');
		$('.legend-container-content').fadeOut();
	}
	e.hide();
	e.find('.legend-content-body').slideUp(400);
	e.find('.rotate').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
}
function nbnlistFilter(header, list, layer_id) {
	if ($('#input' + layer_id).length == 0) {
		var form = $("<form>").attr({ "class": "filterform", "action": "#" }),
		input = $("<input>").attr({ "class": "filterinput clearable", "type": "text", "size": "15", 'id': 'input' + layer_id });
		$(form).append(input).insertAfter(header);

		$(input).change(function () {
			hideAllTabs();
			var filter = $(this).val();
			var count = filter.replace(/ /g, '').length;
			if (filter) {
				$(list).find("a:not(:Contains(" + filter + "))").parent().hide();
				$(list).find("a:Contains(" + filter + ")").parent().show();
			}
			else {
				$(list).find("li").show();
			}
			return false;
		}).keyup(function () {
			$(this).change();
		});
	}
}

var selsubjct=[];
function selAlerts(id){
	$( "#nbn_alerts_"+id+" li" ).each(function( index ) {
		var postid = $(this).attr('postcode');
		
		$("#title_nbn_polygon").val('');
		$("#nbn_description_polygon").val('');
		var nbn_subject=$("#nbn-subject-"+id).val();
		var nbn_description=$("#nbn-description-"+id).val();
		var setPostcode=postid;
		var $path = $("path#nbn_"+postid);
		var classP = $path.attr('class'); 
		if(color_arr[postid] === undefined){
			color_arr[postid] = classP;
				$path.attr("class","");
			  	$path.attr("class","active");
			  	$(".alert_subject_link").each(function(){
			  		if($(this).attr("id") != id){
         				$(this).attr("disabled",true).addClass("disabled").attr("style","cursor: not-allowed; color:#666;opacity:0.5;pointer-events: none;");
			  		}
     			});
		} else {
			  	$path.attr("class","");
			  	$(".alert_subject_link").each(function(){
         			$(this).attr("disabled",false).removeClass("disabled").attr("style","color:#666");
     			});
			  	$path.attr("class",color_arr[postid]);
				delete color_arr[postid];
		}
	  	if(setPostcodes.indexOf(setPostcode) == -1){
       			html ='<div class="accordion_head_post_sel" id="head_'+postid+'">'+postid+'<span id="plusminus_'+postid+'" class="plusminus" onclick="tabToggleModeSub(\'' + postid + '\')">+</span></div><div class="accordion_body_post" id="content_'+postid+'" style="display: none;">';
       		 	html +='</div>';
				$("#selected_zones").append(html);
            	setPostcodes.push(setPostcode);
            	$("#title_nbn_polygon").val(nbn_subject);
				$("#nbn_description_polygon").val(nbn_description);
				$("#alert_id").val(id);
         } else {
         	 	$( "#head_"+postid).remove();
         	 	$( "#content_"+postid).remove();
            	setPostcodes.remove(setPostcode);
            	$("#title_nbn_polygon").val('');
				$("#nbn_description_polygon").val('');
				$("#alert_id").val('');
        }
	    if(setPostcodes.length>0){
	    		if(curr_user_access!='full_access'){
	    			$("#open_alert_form").attr("disabled", true);
	    		}	
	  			nbnMainShow($('#nbn_zones_div'));
				$("#drawing_mode").attr("disabled", true);
            }else{
            	nbnMainHide('#nbn_zones_div');
            	$("#drawing_mode").attr("disabled", false);
		}
        $("#nbn_postcodes").val(setPostcodes);
	 });
}
function hideAllTabs(){
	$("#navigation-nbn .alert_thread_body").hide();
	$("#navigation-nbn .mainplusminus").text('+');
	$("#navigation-nbn .accordion_body_post").hide();
	$("#navigation-nbn .plusminus").text('+');
}
function disableAllCheckboxes(){
	$("#drawing_mode").attr("checked", false).trigger("change");
	$("#drawing_mode").attr("disabled", true);
}
function removeSetValue(){
	$("#nbn_postcodes").val('');
	$("#selected_zones").html('');
	setPostcodes.length=0;
	setDrawnPostcodes.length=0;
	html='';
	for (var key in color_arr) {
					if(key!='remove'){
						$("path#nbn_"+key).attr("class","");
						$("path#nbn_"+key).attr("class",color_arr[key]);
					}
		}
	nbnMainHide($('#nbn_zones_div'));
	nbnMainHide($('#nbn_zones_alert'));
	$("#nbn_postcodes").val('');
}
function enableSubjectTextBox(){
	$(".alert_subject_link").each(function(){
         			$(this).attr("disabled",false).removeClass("disabled").attr("style","color:#666");
    });
}
function returnAlertColor(alert_color){
	if(alert_color=='Red'){
    	label_class='danger';
	 }
	if(alert_color=='Black'){
		label_class='black';
	}
	if(alert_color=='Amber'){
		label_class='warning';
	}
	return label_class;

}

