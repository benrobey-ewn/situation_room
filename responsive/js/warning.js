/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    var observation_marker;
    var bermudaTriangle;
    var warning_interval;
    var warning_intervalTime=300000;
    function load_warning_content(type,topics,warning_days) {
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: "ajax/get_warning.php",
            dataType: "json",
            data: { record_type:type,topic_type: topics,warning_days:warning_days},
            success: function(response)
            {
                //console.log(response.data.length);
		latlongarray.length = 0;
                for (k=0;k<response.data.length;k++) {
                    //console.log('length'+k+response.data[k].corodinates.length);
                    var url=response.data[k].AlertFullURL;
                    //console.log('ponis'+coordinates_json.length);
                    //console.log(coordinates_json[0].length)
                    if(response.data[k].corodinates.length!==0) {
                      var coordinates_json= response.data[k].corodinates;
		      //response.data[k].CreatedDate=response.data[k].CreatedDate+'Z';
		      //response.data[k].CreatedDate='2015-02-02 03:35:00Z';
		      //response.data[k].CreatedDate=timeToLocal(response.data[k].CreatedDate);
                      draw_map(coordinates_json,response.data[k].color,response.data[k].AlertFullURL,parseInt(response.data[k].id),'warning',response.data[k].CreatedDate);
                     
                    }
                    
                }
		if(show_asset_click) {
		   polygon_show_range()
	        }
		$('#loader').hide();
            }
        });
    }

    function clear_range_markers()
    {
       if(filtermarkers)
      {
          //console.log(filtermarkers);
          for (var f=0; f < filtermarkers.length; f++) {
         // console.log('i am calling');
            filtermarkers[f].setMap(null);
        }
      }
      
      show_asset_click=false;
      if(show_forecast_asset_click) {
      } else {
			redraw_layer();
      }
      
      
    }
    
    function remove_filter_marker() {
	//console.log(filtermarkers.length);
	 if(filtermarkers.length>0)
      {
          //console.log(filtermarkers);
          for (var f=0; f < filtermarkers.length; f++) {
	    //console.log(filtermarkers[f]);
         // console.log('i am calling');
            filtermarkers[f].setMap(null);
        }
      }
      show_asset_click=false;
	
    }


    function remove_warning() {
     // console.log(filtermarkers);
      if(filtermarkers)
      {
          //console.log(filtermarkers);
          for (var f=0; f < filtermarkers.length; f++) {
         // console.log('i am calling');
            filtermarkers[f].setMap(null);
        }
      }


      if (warningMarkersArray) {
        for (var i=0; i < warningMarkersArray.length; i++) {
         // console.log('i am calling');
            warningMarkersArray[i].setMap(null);
            
            polygonMarkerArray[i].setMap(null);
        }
	
	
    }
      
      warningMarkersArray.length = 0;
            polygonMarkerArray.length=0;
            my_polygon_markers.length = 0;
            latlongarray.length = 0;
/*            marker1.setMap(null);
             marker2.setMap(null)
            marker3.setMap(null);
            marker4.setMap(null);*/
            //console.log(latlongarray);
            minLat = 1000;
            minLong = 1000;
            maxLat = -1000;
            maxLong = -1000;
      
      map.controls[google.maps.ControlPosition.TOP_LEFT].clear();
      //$('#time_display').css("left","30px");
      //$('#forecast_codes_div').css("left","30px");
      //map.controls[google.maps.ControlPosition.LEFT_BOTTOM].clear();
      
    }
    
    function redraw_layer() {
	 //console.log(kml_markes.length);
	 hide_layer_markers();
	 /*if (kml_markes) {
	    for (z=0; z < multi_kml_marker_array.length; z++) {
	     // console.log('i am calling');
		multi_kml_marker_array[z].setMap(map);
	    }
	 }*/
	 
	  var layer_value=$('#layers').val();
	  	 /*if(layer_value==1) {
                    RT_NSW.setMap(map);
                    RT_NT.setMap(map);
                    RT_QLD.setMap(map);
                    RT_SA.setMap(map);
                    RT_TAS.setMap(map);
                    RT_VIS.setMap(map);
                    RT_WA.setMap(map);
          }
		  
		   if(layer_value==35) {
	    			waternsw.setMap(map);
	  			}  */
			
		  console.log('redraw');
		  console.log(client_checked_options);	
		  load_client_layer(client_checked_options);
		  load_public_layer(public_checked_options);
	
    }
    
    function hide_layer_markers() {
	    RT_NSW.setMap(null);
	    RT_NT.setMap(null);
	    RT_QLD.setMap(null);
	    RT_SA.setMap(null);
	    RT_TAS.setMap(null);
	    RT_VIS.setMap(null);
	    RT_WA.setMap(null);
	    
	    MDSWA.setMap(null);
	    waternsw.setMap(null);
		
		//
		datach1.setMap(null);
		datach2.setMap(null);
		
		if(multi_kml_marker_array.length>0) {
			for(i=0;i<multi_kml_marker_array.length;i++) {
			  //multi_kml_marker_array[i].setMap(null);
			  multi_kml_marker_array[i].setVisible(false);
			}
		  }
		 
		}
		
		
		
		
    
    // Add a Home control that returns the user to London
    function HomeControl(controlDiv, map) {
      controlDiv.style.padding = '5px';
      
      var controlUI = document.createElement('div');
      controlUI.className = "hide_alerts";
      //controlUI.innerHTML = "<img src='../images/menu.png'>";
      /*controlUI.style.backgroundColor = '#2165C4';
      controlUI.style.border='1px solid';
      controlUI.style.cursor = 'pointer';
      controlUI.style.textAlign = 'center';
      controlUI.title = 'Hide Alerts';*/
      controlDiv.appendChild(controlUI);
      
      var controlText = document.createElement('div');
      //controlText.style.fontFamily='Arial,sans-serif';
      //controlText.style.fontSize='12px';
      //controlText.style.paddingLeft = '4px';
      //controlText.style.paddingRight = '4px';
      //controlText.style.color='#FFFFFF';
      controlText.innerHTML = '<b>Hide Alerts<b>';
      controlUI.appendChild(controlText);
    
      // Setup click-event listener: simply set the map to London
      google.maps.event.addDomListener(controlUI, 'click', function() {
        $(".weather").attr("checked",false);
        remove_warning();
        remove_warning_legend();
        $('#time_display').css("left","30px");
        $('#forecast_codes_div').css("left","30px");
      });
    }
    
    /* function for warning legend */
    function WarningLegend(controlDiv, map) {
		$('#warning_legend').show();
		/*controlDiv.style.padding = '5px';
  
		var controlUI = document.createElement('div');
		//controlUI.style.backgroundColor = 'yellow';
		//controlUI.style.border='1px solid';
		//controlUI.style.cursor = 'pointer';
		controlUI.style.textAlign = 'center';
		//controlUI.title = 'Set map to London';
		controlDiv.appendChild(controlUI);
  
		var controlText = document.createElement('div');
		controlText.style.fontFamily='Arial,sans-serif';
		controlText.style.fontSize='12px';
		controlText.style.paddingLeft = '2px';
		controlText.style.paddingRight = '4px';
		
               controlText.innerHTML = controlText.innerHTML + '<div style="clear: both;width: 204px;border: 1px solid #999;margin: 0 auto; height:254px;background-color:#FFF">'
                +'<ul style="list-style:none; margin:0; padding:0;">'
                +'<li style="float:left; width:100%;">'
                  +'<div style="clear: both;padding: 10px 10px;margin-bottom: 5px;">'
                     +'<h1 style="font-size: 14px;text-align: center;margin:0;padding:0;">Warning Codes</h1></div>'
                  +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                      +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                         +'<img src="images/box/red.png" width="18" height="18">'
                      +'</div>'
                      +'<h3 style="width: 100%;font-size: 13px;font-weight: normal;color: #000;margin:0;padding:0; text-align:left;">Severe Thunderstorm</h3>'
                  +'</div>'
                  +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                      +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                         +'<img src="images/box/pink.png" width="18" height="18">'
                      +'</div>'
                      +'<h3 style="width: 100%;font-size: 13px;font-weight: normal;color: #000;margin:0;padding:0; text-align:left;">Severe Weather</h3>'
                  +'</div>'
                  +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                      +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                         +'<img src="images/box/blue.png" width="18" height="18">'
                      +'</div>'
                      +'<h3 style="width: 100%;font-size: 13px;font-weight: normal;color: #000;margin:0;padding:0; text-align:left;">Flood Watch</h3>'
                  +'</div>'
                  +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                    +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                     +'<img src="images/box/orange.png" width="18" height="18">'
                    +'</div>'
                    +'<h3 style="margin:0;padding:0;width: 100%;font-size: 13px;font-weight: normal;color: #000;text-align:left;">Fire Weather Warning</h3>'
                  +'</div>'
                  +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                    +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                     +'<img src="images/box/brown.png" width="18" height="18">'
                    +'</div>'
                    +'<h3 style="margin:0;padding:0;width: 100%;font-size: 13px;font-weight: normal;color: #000;text-align:left;">Bushfire Advices</h3>'
                  +'</div>'
                  +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                    +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                     +'<img src="images/box/purple.png" width="18" height="18">'
                    +'</div>'
                    +'<h3 style="margin:0;padding:0;width: 100%;font-size: 13px;font-weight: normal;color: #000;text-align:left;">Bushfire W&A and EW</h3>'
                  +'</div>'
                  +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                    +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                        +'<img src="images/box/green.png" width="18" height="18">'
                    +'</div>'
                    +'<h3 style="margin:0;padding:0;width: 100%;font-size: 13px;font-weight: normal;color: #000;text-align:left;">Tropical Cyclones</h3>'
                   +'</div>'
                   +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                    +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                      +'<img src="images/box/light_blue.png" width="18" height="18">'
                    +'</div>'
                    +'<h3 style="margin:0;padding:0;width: 100%;font-size: 13px;font-weight: normal;color: #000;text-align:left;">Tsunami</h3>'
                   +'</div>'
                   +'<div style="margin-bottom: 1px;clear: both;padding: 2px 10px;">'
                    +'<div style="width: 40px;margin: 0;padding: 0;text-align: center;float: left;">'
                     +'<img src="images/box/yellow.png" width="18" height="18">'
                    +'</div>'
                    +'<h3 style="margin:0;padding:0;width: 100%;font-size: 13px;font-weight: normal;color: #000;text-align:left;">Others</h3>'
                  +'</div>'
                '</li>'
                +'</ul>'
                +'</div>';
               //console.log(controlText.innerHTML);
                //controlText.innerHTML =controlText.innerHTML+'</body>';
		controlUI.appendChild(controlText);
*/
		// Setup click-event listener: simply set the map to London
		
	}
        
         /* remove single warning */
       function remove_warning_single(id) {
         if(typeof warningobjectMarkersArray[id] == 'undefined') {
         } else {
           warningobjectMarkersArray[id].setMap(null);
           var index = my_polygon_markers.indexOf(id);
           if (index > -1) {
              my_polygon_markers.splice(index, 1);
           }
         }
       }
       
       /* remove warning legend */
       function remove_warning_legend() {
         //map.controls[google.maps.ControlPosition.LEFT_BOTTOM].clear();
		 $('#warning_legend').hide();
       }
       
       /* load warning */
       function load_initial_warning() {
           $('input[name="warning_option[]"]').each(function() {
              $(this).attr("checked",true);
            });
          weather_options.push('all');
           $('#time_display').css("left","225px");
          var homeControlDiv = document.createElement('div');
	    var homeControl = new WarningLegend(homeControlDiv, map); 
	    homeControlDiv.index = 1;
	    map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(homeControlDiv);
            load_warning_content(2,weather_options,48);
            
            warning_interval=setInterval(function() {
                //console.log('warning');
		update_warning();
	      },warning_intervalTime);  
       }
       
       /* function for update warning */
        function update_warning() {
	    		if($(".weather").attr("checked")) {
					   var $checkes = $('input:checkbox[name="warning_option[]"]');
					   weather_options = $checkes.filter(':checked').map(function () {
						return this.value;
					   }).get();
					   //console.log(weather_options);
					   if(weather_options.length==0) {
						  return false;
					   } else {
						   remove_warning();
						   $('input[name="warning_type"]:checked').each(function() {
							 var warning_type=this.value;
							 if(warning_type=='1') { 
							   load_warning_content(1,weather_options,'');
							 } else if(warning_type=='2') {
							   var days=$('#warning_days').val();
							   load_warning_content(2,weather_options,days);
							 }
						   });
						   
					   
					   }   
				 }
       }
       
       function set_warning_setting(warning_type,warning_options,days) {
			  var homeControlDiv = document.createElement('div');
			  var homeControl = new WarningLegend(homeControlDiv, map); 
			   homeControlDiv.index = 1;
			   $('#time_display').css("left","225px");
			   map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(homeControlDiv);
			   
			   /* check warning option */
				if(warning_options.length==0) {
						  
					   } else {
					load_warning_content(warning_type,warning_options,days);
				   }
					
				warning_interval=setInterval(function() {
				   update_warning();
				},warning_intervalTime);  
       }


