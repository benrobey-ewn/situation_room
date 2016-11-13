/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    var observation_marker;
    var cluster_markers = [];

      var styles = [[{
        url: 'images/observation/yellow_clutter.png',
        height: 55,
        width: 56
   
      }]];

      var markerClusterer = null;
      var map = null;



            function clearClusters() {
        markerClusterer.clearMarkers();
      }





    function load_observation_content(type) {
        $('#loader_observation').show();
        $.ajax({
            type: "POST",
            url: "ajax/get_observation.php",
            dataType: "json",
            data: { record_type:type },
            success: function(response)
            {
                //console.log(response.data.length);
                var observation_length=response.data.length-1;
                if(response.data.length>0) {
                   
                for (i=0;i<response.data.length;i++)
                {
                    
                    //console.log(response.data[i]);
                    var myLatlng = new google.maps.LatLng(response.data[i].lat,response.data[i].lng);
                     var className='cityshow';
                     var zoomLevel = map.getZoom();
    	                if(zoomLevel>=8)
                      {

                         //className='cityhide';
                           /* if(response.data[i].group_capital!=0)
                            {
        		                  className='cityhide';
                            }*/

                            /* if(response.data[i].is_capital==1)
                            {
                                className='cityhide';
                            }*/

                              //className='cityhide';
    	                } else {
    		             className='cityshow';
    	                }
                    
                    var temp=parseInt(response.data[i].temp);
                    var marker_icon='observation.png';
                    var label_class='dark-blue';
                    var new_marker_icon = 'blue.png';
                    if(temp<0 && temp==0) {
                      marker_icon='dark_blue.png';
                      label_class='dark_blue_label';
                      new_marker_icon = 'blue.png';
                    } else if(temp>=1 && temp<=10) {
                         marker_icon='light_blue.png';
                         label_class='light_blue_label';
                         new_marker_icon = 'circle.png';
                    } else if(temp>=11 && temp<=20) {
                         marker_icon='light_orange.png';
                         label_class='yellow_label';
                         new_marker_icon = 'yellow.png';
                    } else if(temp>=21 && temp<=30) {
                         marker_icon='orange.png';
                          label_class='orange_label';
                          new_marker_icon = 'orange.png';
                    } else if(temp>=31 && temp<=40) {
                         marker_icon='red.png';
                         label_class='red_label';
                         new_marker_icon = 'light_red.png';
                    } else if(temp>=41) {
                         marker_icon='dark_red.png';
                         label_class='dark_red_label';
                         new_marker_icon = 'red.png';
                    }
                    
                    var marker_icon='white.png';
		    
		               if(response.data[i].temp.trim()=='-')
                    response.data[i].temp='&nbsp;&nbsp;&nbsp;'+response.data[i].temp;
                    
                    if(response.data[i].city_name=='Alice Springs Airport') {
                        response.data[i].city_name='Alice Springs';
                    }
		    
		    if(response.data[i].city_name=='Melbourne (Olympic Park)') {
                        response.data[i].city_name='Melbourne';
                    }
                    
                    //console.log('marker icon'+marker_icon+' '+temp+' '+label_class);
                                            //icon: 'images/observation/'+marker_icon,

                    observation_marker = new MarkerWithLabel({
                        position: myLatlng,
                        draggable: false,
                        map: map,
                        labelContent: '<div class=mylabels>'+'<div align=left style="font-size:14px;">'+response.data[i].temp+'</div><div align=left class="city_label '+className+'" style=width:auto;>'+response.data[i].city_name+'</div></div>',
                        labelAnchor: new google.maps.Point(-10, 20), // -10,20 ---- -22,0
                        labelClass: "border_labels", // the CSS class for the label
                        labelStyle: {opacity: 1},
                        icon: 'images/observation/'+new_marker_icon,
                        title:response.data[i].city_name+' observation'
                      });



                    
                    // info window
                    google.maps.event.addListener(observation_marker, 'click', (function(marker, i) {
                        return function() {
                             var contentString = '<div id="markerinfo" style="width:320px;"><div style="padding:5px 2px;">'+response.data[i].city_name+'</div>'+
                                            '<table cellpadding="0" cellspacing="0" class="forecasts_table">'+
                                            '<tr>'+
                                            '<td style="border-radius:6px 0 0 0;" class="head">Date/Time '+response.data[i].timezone+'</td>'+
                                            '<td style="border-radius:0 6px 0 0">'+response.data[i].date_time_cst+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                            '<td class="head">Temp&deg;C</td>'+
                                            '<td>'+response.data[i].temp+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                            '<td class="head">Dew Point&deg;C</td>'+
                                            '<td>'+response.data[i].dew_ponit+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                            '<td class="head">Rel Hum %</td>'+
                                            '<td>'+response.data[i].rel_hum+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                            '<td class="head">Wind Direction</td>'+
                                            '<td>'+response.data[i].wind_dir+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                            '<td class="head">Wind Speed (Km/h)</td>'+
                                            '<td>'+response.data[i].wind_speed_km_h+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                            '<td class="head">Rain since 9 am (mm)</td>'+
                                            '<td>'+response.data[i].rain_since_9am_mm+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                            '<td class="head">Low Tmp&deg;C time</td>'+
                                            '<td>'+response.data[i].low_Tmp+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                            '<td class="head">High Tmp&deg;C time</td>'+
                                            '<td>'+response.data[i].high_tmp+'</td>'+
                                            '</tr>'+
                                            '<tbody>'+
                                            '</tbody>'+
                                        '</table></div>';
                            infowindow.setContent(contentString);
                            infowindow.open(map, marker);
                    }
                    })(observation_marker, i));
                    
                    mapMarkersArray.push(observation_marker);
                    cluster_markers.push(observation_marker);

                    observation_marker.setMap(map);
                    if(i==observation_length) {
                         //if(type=='2') {
                          //hide_show_city_lable();
                         //}
                         $('#loader_observation').hide();
                    }

                }



                    var zoom = 21;
                    var size = -1;
                    var style = 0;
                    zoom = zoom === -1 ? null : zoom;
                    size = size === -1 ? null : size;
                    style = style === -1 ? null: style;

                    markerClusterer = new MarkerClusterer(map, cluster_markers, {
                      maxZoom: zoom,
                      gridSize: size,
                      styles: styles[style]
                    }); 
                } else {
                	$('#loader_observation').hide();
                    //$('#loader').hide();
                }
               // $('#loader').hide();
            }
        });
    }


    function deleteMarkersFromMap() {
      if (mapMarkersArray) {
        for (var i=0; i < mapMarkersArray.length; i++) {
            mapMarkersArray[i].setMap(null);
            cluster_markers[i].setMap(null);
            clearClusters();
        }
            mapMarkersArray.length = 0;
            cluster_markers.length = 0;
      }
    }
    
    
    /* function for load all cities observations data */
    function load_observation_allCities() {
        var zoomLevel = map.getZoom();
        if(zoomLevel<8) {
                if(observation_zoom!==3) {
                    deleteMarkersFromMap();
                     clearInterval(observation_timeout);
                     if($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked"))
                     {
                        load_observation_content(3);
                      }
                    observation_timeout=setInterval(function() {
                    console.log('reload map');
                    deleteMarkersFromMap();
                    if($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked"))
                     {
                    load_observation_content(3);
                }
                    },radartimeinterval);
                    observation_zoom=3;
                } else {
                    hide_show_city_lable(); 
                }
	} else {
                if(observation_zoom!==2) {
                    deleteMarkersFromMap();
                     clearInterval(observation_timeout);
                     if($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked"))
                     {
                    load_observation_content(2);
                }
                    observation_timeout=setInterval(function() {
                        console.log('reload map');
                        deleteMarkersFromMap();
                        if($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked"))
                     {
                        load_observation_content(2);
                    }
                    },radartimeinterval);
                    observation_zoom=2;
                } else {
                    hide_show_city_lable(); 
                }
	}
        
    }
    
    /* function for load capital observations data only */
    function load_observation_Capitals_only() {
        var zoomLevel = map.getZoom();
        if(zoomLevel<8) {
                if(observation_zoom!==4) {
                    deleteMarkersFromMap();
                     clearInterval(observation_timeout);
                     if($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked"))
                     {
                    load_observation_content(4);
                }
                    observation_timeout=setInterval(function() {
                    console.log('reload map');
                    deleteMarkersFromMap();
                    load_observation_content(4);
                    },radartimeinterval);
                    observation_zoom=4;
                } else {
                    hide_show_city_lable(); 
                }
	} else {
                if(observation_zoom!==1) {
                    deleteMarkersFromMap();
                     clearInterval(observation_timeout);
                     if($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked"))
                     {
                    load_observation_content(1);
                }
                    observation_timeout=setInterval(function() {
                        console.log('reload map');
                        deleteMarkersFromMap();
                        if($('.observation_option1').attr("checked") || $('.observation_option2').attr("checked"))
                     {
                        load_observation_content(1);
                    }
                    },radartimeinterval);
                    observation_zoom=1;
                } else {
                    hide_show_city_lable(); 
                }
	}
       
    }
    
    function hide_show_city_lable() {
        var zoomLevel = map.getZoom();
	     //alert(zoomLevel);
	     console.log('zoom level'+zoomLevel);
	     if(zoomLevel>=8) {
                 //$('.city_label').removeClass('cityshow');
		 //$('.city_label').addClass('cityhide');
                 $('.city_label').css({'width':"auto"});
             } else {
		 $('.city_label').removeClass('cityhide');
		 //$('.city_label').addClass('cityshow');
                 $('.city_label').css({'width':"auto"});
	     }
    }



function hide_show_city_lable_typeid(type_id)
    { 
      //console.log('i am calling..............');
      var zoomLevel = map.getZoom();

      if(zoomLevel<=4)
      {
       /* if (markerClusterer) {
          markerClusterer.clearMarkers();
        }*/
      }

      console.log(type_id);
      if(type_id=='hybrid' || type_id =='roadmap' || type_id=='terrain')
      {

       if(zoomLevel>=8)
       {
         //$('.city_label').addClass('cityhide');
         //$('.city_label').css({'width':"auto"});
      }
    }
   
     else if(zoomLevel>=8) {
           $('.city_label').removeClass('cityhide');
          $('.city_label').css({'width':"auto"});
       }

   }
