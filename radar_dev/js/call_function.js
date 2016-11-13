/*gives index */
var indexOfPolygons = function(needle) {
    if(typeof Array.prototype.indexOfPolygons === 'function') {
        indexOfPolygons = Array.prototype.indexOfPolygons;
    } else {
        indexOfPolygons = function(needle) {
            var i = -1, index = -1;

            for(i = 0; i < this.length; i++) {
                if(this[i] === needle) {
                    index = i;
                    break;
                }
            }

            return index;
        };
    }

    return indexOfPolygons.call(this, needle);
};

function getSelectBoxText()
{
  return $("#layers option:selected").text();
}


function getSelectBoxValue()
{
  return $("#layers").val();
}

function display_range_output_old()
{
  /*Blanl current html*/
  $('#display_markers_point_div').html('');
  /**/
    $('#loader').show();
    var complete_lat_longs = [];
    var myLatlngnew;
    position_url="ajax/display_newpoints.php";
    console.log(latlongarray);
    for(l=0 ; l<latlongarray.length;l++)
    {
       // console.log(latlongarray[l].polygonPrimaryKey);
        var mydata = 'selected_layer_value_current='+opener.getSelectBoxValue()+'&polygonPrimaryKey='+latlongarray[l].polygonPrimaryKey+'&polygon_number='+l+'&minLat='+latlongarray[l].maxLat+'&minLong='+latlongarray[l].minLong+'&maxLat='+latlongarray[l].minLat+'&maxLong='+latlongarray[l].maxLong;
        $.ajax
        ({
            type: "POST",
            url: position_url,
            dataType: "json",
            data: mydata,
            async: false,
            success: function(response) 
            { 
                if(response.data.length>0) 
                {
                    for (i=0;i<response.data.length;i++)
                    {
                        var myLatlng = new google.maps.LatLng(response.data[i].latitude,response.data[i].longitude);
                        
                        if(google.maps.geometry.poly.containsLocation(myLatlng, polygonMarkerArray[response.polygon_number]) == true)
                        {
                            console.log(response.polygon_number);
                            var newElements = {};
                            newElements['polygon_subject'] = response.polygon_subject;
                            newElements['polygon_number'] = response.polygon_number;
                            newElements['latitude'] = response.data[i].latitude;
                            newElements['longitude'] = response.data[i].longitude;
                            newElements['placemarker_name'] = response.data[i].placemarker_name;
                            newElements['placemarker_icon'] = response.data[i].placemarker_icon;
                            newElements['layer_type'] = response.data[i].layer_type;
                            complete_lat_longs.push(newElements);
                        }                       
                    }
                }
                //console.log(response.polygon_number);
                //console.log(latlongarray.length);
                if(response.polygon_number==latlongarray.length-1)
                { 
                  var display_polygon_marker_array = [];
                  var popupWindowName = 'EWNPopup' + Math.floor((Math.random()*500)+1);
                 // console.log(complete_lat_longs);

                    var telephone= '';
                    var myhtml1= '<div><div style="float:left;"><input type=button value=Print onclick="opener.print_page();"></div><div style=clear:left;></div></div>';
                    for(output=0;output<complete_lat_longs.length;output++)
                    { 
                       index = indexOfPolygons.call(display_polygon_marker_array, complete_lat_longs[output].polygon_number);
                      if(index<0)
                      {
                          myhtml1=myhtml1+"<div style=clear:left;></div>";
                          myhtml1=myhtml1+"<div style=margin-top:15px;>"+complete_lat_longs[output].polygon_subject+"</div>";
                          display_polygon_marker_array.push(complete_lat_longs[output].polygon_number);
                      }
                      myhtml1=myhtml1+"<div style='border:1px solid #000; width:200px;height:50px;float:left;'>"+complete_lat_longs[output].placemarker_name+"</div>";
                      telephone++;
                    }
                    $('#display_markers_point_div').append(myhtml1);

                    var complete_html = $('#display_markers_point_div').html();
                    var myWindow = window.open("", popupWindowName, "width=1000, height=1000,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes'");
                    myWindow.document.write(complete_html);
                }
            
            }
        });

        if(l==latlongarray.length-1)
        {
         $('#loader').hide();
        }


    }
}



   function exportTableToCSV($table, filename) {

        var $rows = $table.find('tr:has(td)'),

            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character

            // actual delimiter characters for CSV format
            colDelim = '","',
            rowDelim = '"\r\n"',

            // Grab text from table into CSV formatted string
            csv = '"' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');

                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();

                    return text.replace('"', '""'); // escape double quotes

                }).get().join(tmpColDelim);

            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"',

            // Data URI
            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

        $(this)
            .attr({
            'download': filename,
                'href': csvData,
                'target': '_blank'
        });
    }




function print_page() {
    window.print();
}


function polygon_show_range()
{
  console.log(latlongarray.length);



  $('#loader').show();

  if(latlongarray.length==0)
  {
      $('#loader').hide();
  }
  //console.log(latlongarray.length);
  position_url="ajax/get_newpoints.php";

  //console.log(latlongarray.length);

  //var mydata = 'layers='+$('#layers').val()+'&raw_data='+$('#raw_data').val();

  for(l=0 ; l<latlongarray.length;l++)
  {
    var mydata = 'polygon_number='+l+'&minLat='+latlongarray[l].maxLat+'&minLong='+latlongarray[l].minLong+'&maxLat='+latlongarray[l].minLat+'&maxLong='+latlongarray[l].maxLong;
  $.ajax
  ({
    type: "POST",
    url: position_url,
    dataType: "json",
    data: mydata,
    async: false,
    success: function(response) 
    { 

      if(response.data.length>0) 
      {
        for (i=0;i<response.data.length;i++)
        {
          var myLatlng = new google.maps.LatLng(response.data[i].latitude,response.data[i].longitude);

          filter_marker = new google.maps.Marker
          ({
              position: myLatlng,
              draggable: false,
              icon : 'images/layer_markers/'+response.data[i].placemarker_icon
            });

           google.maps.event.addListener(filter_marker,'click',(function(marker, i)
          {
          return function() {
          var mydesc = response.data[i].placemarker_description;
         //   infowindow.close();
           // load_content(map,this,mydesc);
            infowindow.setContent(mydesc);
            infowindow.open(map, marker);
          }
          })(filter_marker, i));
                    
                    // info window
        //  console.log(myLatlng);
          //console.log(polygonMarkerArray[0]);
          //console.log('new');

        if(google.maps.geometry.poly.containsLocation(myLatlng, polygonMarkerArray[response.polygon_number]) == true)
        {
         // console.log('Got');
          filter_marker.setMap(map);
          filtermarkers.push(filter_marker);
        }
      }
     } 
    }
  });
    if(l==latlongarray.length-1)
    {
        $('#loader').hide();
    }
    
  } 
}


function resetToDefaults() {
          center = new google.maps.LatLng(defaultMapCenterLat,defaultMapCenterLon);
          map.setCenter(center);
          map.setZoom(defaultMapZoom);
          //updateViewportCookie(defaultRadar);
          document.getElementById("radarImage").src = 'http://clients.ewn.com.au/common/radar.php?radar=' + defaultRadar;

          if (currentRadarStatus == 'off')
          {
            if (pageLoadedWithoutRadarOn)
            {
              show128km();
              $.cookie(cookiePrefix + "RS", 'on', { expires: 365});
              currentRadarStatus = 'on';
          }
          else
          {
              radarsOn();
              $.cookie(cookiePrefix + "RS", 'on', { expires: 365});
              currentRadarStatus = 'on';
          }
      }

      if (showMarkers === false) { toggleRadarMarkers(); }

      if (observationsStatus == 'off')
      {
        displaySelectedObs(map.getZoom());
        for (var a in marker)
        {
          marker[a].setMap(map);
      }
      $.cookie(cookiePrefix + "OS", 'on', { expires: 365});
      observationsStatus = 'on';
  }    

  return false;
}

function displaySelectedObs(zoomLevel)
{
  for (a = 0; a < marker.length; a++)
  {
    if (zoomVisibility[a]) {
      if (zoomVisibility[a].indexOf('-' + zoomLevel + '-') >= 0)
      {
        marker[a].setVisible(true);
    }
    else
    {
        marker[a].setVisible(false);
    }
}
}           
}

function toggleObservations()
{
  if (observationsStatus == 'on')
  {
    for (var a in marker)
    {
      marker[a].setMap(null);
  }
  $.cookie(cookiePrefix + "OS", 'off', { expires: 365});
  observationsStatus = 'off';
}
else
{
    displaySelectedObs(map.getZoom());
    for (var a in marker)
    {
      marker[a].setMap(map);
  }
  $.cookie(cookiePrefix + "OS", 'on', { expires: 365});
  observationsStatus = 'on';
}    
}




function show128km() {
    if (currentRange == 128) return;
    if (currentRadarStatus == 'on') {  if (currentRange == 256 && currentRadarStatus == 'on') rio2.remove(); }
    $("#range1").css("display", "none");
    $("#range2").css("display", "inline");
    currentRange = 128;
    rio1.loadOverlays();
    showMarkers ? rio1.showRadarMarkers() : rio1.hideRadarMarkers()
    $.cookie(cookiePrefix + "RS", 'on', { expires: 365});
    $.cookie(cookiePrefix + "RR", '128', { expires: 365});
    $("#overview_header").html("128km Radar Stations");
    return false;
}

function show256km() {
    if (currentRange == 256) return;
    if (currentRadarStatus == 'on') { if (currentRange == 128) rio1.remove(); }
    $("#range1").css("display", "inline");
    $("#range2").css("display", "none");
    currentRange = 256;
    rio2.loadOverlays();
    showMarkers ? rio2.showRadarMarkers() : rio2.hideRadarMarkers()
    $.cookie(cookiePrefix + "RS", 'on', { expires: 365});
    $.cookie(cookiePrefix + "RR", '256', { expires: 365});
    $("#overview_header").html("256km Radar Stations");
    return false;
}


function zp(s) {
  s = "" + s;
  if (s.length == 1) return "0" + s;
  return s;
}

function ft(d) {
  return [d.getUTCFullYear(), zp(d.getUTCMonth()+1), zp(d.getUTCDate())].join("-") +  " " + zp(d.getUTCHours()) +  ":" + zp(d.getUTCMinutes()) + "UTC";
}
function log(m) {
  return;
  m = (new Date()) + " : " + m;
  if (typeof(console) == "1111") {
    var l = document.getElementById("log");
    l.innerHTML = l.innerHTML + m + "<br>";
} else {
}
}

function radarsOff() {
  var rio = (currentRange == 128) ? rio1 : rio2;
  $.cookie(cookiePrefix + "RS", 'off', { expires: 365});
  rio.updateAll(false);
  return false;
}

function load_content(map,check, item)
{
    $('#loader').show();

    $.ajax({
        type: "POST",
        url: "common/forecast_info.php",
        data: { forecast: item },
        success: function(data)
        {
            infowindow.setContent(data);
            infowindow.open(map,check);
            $('#loader').hide();
        }
    });
}

function imageExists(image_url){

    var http = new XMLHttpRequest();

    http.open('HEAD', image_url, false);
    http.send();

    return http.status != 404;

}
   
   var indexOf = function(needle) {
    if(typeof Array.prototype.indexOf === 'function') {
        indexOf = Array.prototype.indexOf;
    } else {
        indexOf = function(needle) {
            var i = -1, index = -1;

            for(i = 0; i < this.length; i++) {
                if(this[i] === needle) {
                    index = i;
                    break;
                }
            }

            return index;
        };
    }

    return indexOf.call(this, needle);
};

    var my_polygon_markers = [];

    // function for show polygon on map
    function draw_map(coordinates,color_code,url,id,source_type)
    {
      //console.log(coordinates);
        //$('#loader').show();
        map.controls[google.maps.ControlPosition.TOP_LEFT].clear();
        
        // Create a DIV to hold the control and call HomeControl()
        var homeControlDiv = document.createElement('div');
        var homeControl = new HomeControl(homeControlDiv, map);
        homeControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(homeControlDiv);
        
        index = indexOf.call(my_polygon_markers, id);
        //console.log(polygon_type);
           if(index<0)
	    {
				
	 if(coordinates.length>0) {
            for(i=0;i<coordinates.length;i++)
            {
                    var triangleCoords =[];
                    //console.log('harsh1'+coordinates[i].length);
                    
                    for(j=0;j<coordinates[i].length;j++)
                    {
                        //console.log('Harsh'+coordinates[i][j]);
                        //var coordinates_array = $.parseJSON(coordinates[i][j]);
                        var latlog=coordinates[i][j].split(',');
                        //console.log(latlog);
                       // console.log(minLat+'>>>>>'+minLong+'>>>>>>'+maxLat+'>>>>>>>'+maxLong);
                        // finding minimum of latitude
                         if (latlog[0] < minLat) {
                          minLat = latlog[0];
                          //console.log(minLat);
                         }

                      // finding minimum of logitudes
                         if (latlog[1] < minLong) {
                          minLong = latlog[1];
                         // console.log(minLong);
                         }

                         // finding maximum of latitude
                         if (latlog[0] > maxLat) {
                          maxLat = latlog[0];
                          //console.log(maxLat);
                         }

                         // finding maximum of longitude
                         if (latlog[1] > maxLong) {
                          maxLong = latlog[1];
                         // console.log(maxLong);
                         }


                        var point=new google.maps.LatLng(latlog[0], latlog[1]);
                        triangleCoords.push( point );
                    }
                    var polygon_marker1 = new google.maps.Polygon({
                        paths: triangleCoords,
                        strokeColor: color_code,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color_code,
                        fillOpacity: 0.35
                    });
                    polygonMarkerArray.push(polygon_marker1);
                    polygon_marker1.setMap(map);

                       // Marker 1
                    var coordinate1 = new google.maps.LatLng(minLat, minLong);
                     marker1 = new google.maps.Marker({
                        position:coordinate1,
                    });
                    //marker1.setMap(map);
                    
                   /* google.maps.event.addListener(marker1, 'click', function() {
                        infowindow.open(map, marker1);
                    });*/

                        // Marker 2
                    var coordinate2 = new google.maps.LatLng(minLat, maxLong);
                     marker2 = new google.maps.Marker({
                        position:coordinate2,
                    });
                    //marker2.setMap(map);

                        // Marker 3
                    var coordinate3 = new google.maps.LatLng(maxLat, maxLong);
                     marker3 = new google.maps.Marker({
                        position:coordinate3,
                    });
                    //marker3.setMap(map);

                        // Marker 4
                    var coordinate4 = new google.maps.LatLng(maxLat, minLong);
                     marker4 = new google.maps.Marker({
                        position:coordinate4,
                    });
                   //marker4.setMap(map);

                  //console.log(minLat+'>>>>>'+minLong+'>>>>>>'+maxLat+'>>>>>>>'+maxLong);

                    var newElement = {};
                    newElement['minLat'] = minLat;
                    newElement['minLong'] = minLong;
                    newElement['maxLat'] = maxLat;
                    newElement['maxLong'] = maxLong;
                            newElement['polygonPrimaryKey'] = id;
                    latlongarray.push(newElement);

                    console.log(latlongarray);
                    
                    google.maps.event.addListener(polygon_marker1, 'click', function (event)
                    {
                        newPopup(url,'587','600');
                    });
                    
                     
			
		                warningobjectMarkersArray[id]=polygon_marker1;
                    warningMarkersArray.push(polygon_marker1);
                    my_polygon_markers.push(id);
                    
                    
            }
                
            }
        } else {
          var polygon_marker1=warningobjectMarkersArray[id];
        }
        
        if(source_type=='external')
           zoomToObject(polygon_marker1);
    }
    
    
    function zoomToObject(obj){
          var bounds = new google.maps.LatLngBounds();
          var points = obj.getPath().getArray();
          for (var n = 0; n < points.length ; n++){
                    bounds.extend(points[n]);
          }
          map.fitBounds(bounds);
          }
    
    
    // function for show polygon on map
    function forecast_draw_map(coordinates,color_code,url,id)
    {
        
       if(coordinates.length>0) {
            for(i=0;i<coordinates.length;i++)
            {
                    var triangleCoords =[];
                    //console.log('harsh1'+coordinates[i].length);
                    
                    for(j=0;j<coordinates[i].length;j++)
                    {
                        //console.log('Harsh'+coordinates[i][j]);
                        //var coordinates_array = $.parseJSON(coordinates[i][j]);
                        var latlog=coordinates[i][j].split(',');
                        var point=new google.maps.LatLng(latlog[0], latlog[1]);
                        triangleCoords.push( point );
                    }
                    var polygon_marker1 = new google.maps.Polygon({
                        paths: triangleCoords,
                        strokeColor: color_code,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color_code,
                        fillOpacity: 0.35
                    });
    
                    polygon_marker1.setMap(map);
                    
                    google.maps.event.addListener(polygon_marker1, 'click', function (event)
                    {
                        newPopup(url,'587','600');
                    });
                    
                    forecastwarningobjectMarkersArray.push(polygon_marker1);
                    
            }
                
            }
        
    }
    
    function newPopup(url,width,height)
    {
        popupWindowName = 'EWNPopup' + Math.floor((Math.random()*500)+1);
        popupWindow = window.open(
        url,popupWindowName,'height=' + height + ',width=' + width +            ',left=100,top=10,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
    }

    function change_map_type(mapType)
    {
      setting[5].map_option=mapType;
      if(mapType=='SATELLITE')
      {
        map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
      }
      else if(mapType=='TERRAIN')
      {
        map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
      }
      else
      {
        map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
      }
      update_setting();
    }
    
    function refresh() {
        // refresh radar content */
        if($("#radar_display_images").attr("checked")) {
                  loadRadar(true);
	}
        
        // refresh observation data */
        
        if($(".observation_checkbox").attr("checked")) {
		
		var zoomLevel = map.getZoom();
		//console.log('zoom level'+zoomLevel);
		if($(".observation_option1").attr("checked")) {
		     load_observation_Capitals_only();
		} else {
		     load_observation_allCities();
		}
         }
        
        /* load radar images */
        loadWeatherRadarimages();
        /* load radar images */
        
        /* Update Warning */
        update_warning();
        /* forecast */
        
        /* update forecast severe */
        update_forecast_severe();
        
        /* Rainfall */
        update_rainfall();
        
    }
    
    
    function loadWeatherRadarimages() {
          
          $.each(places,function(c,category)
             {
         
          $('#radar_forecasts_information').data('goo').set('map_canvas',(this.checked)?map:null);
                 
                 var cat=$("#radar_forecasts_information").change(function()
                 {
                     //a checkbox fo the category
                     $(this).data('goo').set('map_canvas',(this.checked)?map:null);
                     
                    if($(this).attr("checked")) {
                        setting[2].status='on';
                        setting[1].status='off';
                    } else {
                       setting[2].status='off';
                    }
                    update_setting();
                 })
                 //create a data-property with a google.maps.MVCObject
                 //this MVC-object will do all the show/hide for the category 
                 .data('goo',new google.maps.MVCObject)
                // .prop('checked',!!category.checked)
         
                 //this will initialize the map-property of the MVCObject
                 .trigger('change')
         
                 //label for the checkbox
                 //.appendTo($('<div/>').css({whiteSpace:'nowrap',textAlign:'left'}).appendTo(ctrl))
                 .after(category.label);
         
                 //loop over the items(markers)
                 $.each(category.items,function(m,item)
                 {
                     var mystate = item[0];
                     $.ajax
                     ({
                         type: "POST",
                         url: "ajax/forecast_getimage.php",
                         data: { mystate: mystate },
                         success: function(def_image)
                         {
                             var image_string = def_image;
                             var pieces = image_string.split("/");
                             var short_image_name = pieces[pieces.length-1];
         
                             var check_image_exists = imageExists("images/forecast/transparent/"+short_image_name);
                             if(check_image_exists==true)
                             {
                                 var load_image = "images/forecast/transparent/"+short_image_name;
                             }
                             else
                             {
                                 var load_image = def_image;
                             }
                             //console.log(load_image);
                             //console.log(check_image_exists);
         
                             var marker=new google.maps.Marker
                             ({
                                 position:new google.maps.LatLng(item[1],item[2]),
                                 title:item[0],
                                 icon:load_image,
                                 zIndex: 100 
                             });
         
                             //bind the map-property of the marker to the map-property
                             //of the MVCObject that has been stored as checkbox-data 
                             marker.bindTo('map',cat.data('goo'),'map_canvas');
                             google.maps.event.addListener(marker,'click',function(){
                             //infowindow.setContent(item[0]);
                             //infowindow.open(map,this);
                            // map.setCenter(marker.getPosition());
                             infowindow.close();
                             load_content(map,this,item[0]);
                             });
                         }
                     });
                 });
          });
    }
    
    
    function loadForecastImages() {
          
          $.each(places,function(c,category)
             {
         
                 //  var cat=$('<input>',{type:'checkbox',id:'radar_forecasts_information'}).change(function(){
                 var cat=$("#radar_forecasts_information").change(function()
                 {
                     //alert('123');
                     //console.log(cat);
                     //a checkbox fo the category
                     $(this).data('goo').set('map_canvas',(this.checked)?map:null);
                 })
                 //create a data-property with a google.maps.MVCObject
                 //this MVC-object will do all the show/hide for the category 
                 .data('goo',new google.maps.MVCObject)
                 .prop('checked',!!category.checked)
         
                 //this will initialize the map-property of the MVCObject
                 .trigger('change')
         
                 //label for the checkbox
                 //.appendTo($('<div/>').css({whiteSpace:'nowrap',textAlign:'left'}).appendTo(ctrl))
                 .after(category.label);
         
                 //loop over the items(markers)
                 $.each(category.items,function(m,item)
                 {
                     var mystate = item[0];
                     $.ajax
                     ({
                         type: "POST",
                         url: "ajax/forecast_getimage.php",
                         data: { mystate: mystate },
                         success: function(def_image)
                         {
                             var image_string = def_image;
                             var pieces = image_string.split("/");
                             var short_image_name = pieces[pieces.length-1];
         
                             var check_image_exists = imageExists("images/forecast/transparent/"+short_image_name);
                             if(check_image_exists==true)
                             {
                                 var load_image = "images/forecast/transparent/"+short_image_name;
                             }
                             else
                             {
                                 var load_image = def_image;
                             }
                             //console.log(load_image);
                             //console.log(check_image_exists);
         
                             var marker=new google.maps.Marker
                             ({
                                 position:new google.maps.LatLng(item[1],item[2]),
                                 title:item[0],
                                 icon:load_image,
                                 zIndex: 100 
                             });
         
                             //bind the map-property of the marker to the map-property
                             //of the MVCObject that has been stored as checkbox-data 
                             marker.bindTo('map',cat.data('goo'),'map_canvas');
                             google.maps.event.addListener(marker,'click',function(){
                             //infowindow.setContent(item[0]);
                             //infowindow.open(map,this);
                            // map.setCenter(marker.getPosition());
                             infowindow.close();
                             load_content(map,this,item[0]);
                             });
                         }
                     });
                 });
          });
    }
    
     var markes=[];
    var layer_marker;
     var kml_markes=[];
    function load_ericsson_data () {
                    $.ajax
                     ({
                         type: "POST",
                         url: "ajax/get_ericsson_data.php",
                         dataType: "json",
                         data: { },
                         success: function(response)
                         {
                              html = "";
                              for (k=0;k<response.data.length;k++) {
                                 var myLatlng = new google.maps.LatLng(response.data[k].latitude,response.data[k].longitude);

                                  layer_marker = new google.maps.Marker({
                                        position: myLatlng,
                                        map: map,
                                        title: 'Hello World!',
                                        icon:  'images/magic.gif'
                                    });
                                  markes.push(layer_marker);
                                  
                                  
                                  
                              google.maps.event.addListener(layer_marker, 'click', (function(marker, k) {
                                        return function() {
                                                  var contentString = response.data[k].placemarker_name;
                                                  infowindow.setContent(contentString);
                                                  infowindow.open(map, marker);
                                        }
                              })(layer_marker, k));

                                  var tag='<a style="cursor:pointer" onclick=setlocation('+response.data[k].latitude+','+response.data[k].longitude+','+k+') >'+response.data[k].placemarker_name+'</a>';
                                  html += "<li>" + tag + "</li>";
                              }
                               $("#navigation").append(html);
                               
                                

                         }
                     });
    }
    
     function setlocation(lat,lng,index) {
             map.setCenter(new google.maps.LatLng(lat, lng));
            google.maps.event.trigger(kml_markes[index], 'click');
     }
     
     function show_sites() {
          $('.navigation').show();
          $('#show_sites').hide();
          $('#hide_sites').show();
     }
     
     function hide_sites() {
          $('.navigation').hide();
          $('#hide_sites').hide();
          $('#show_sites').show();
     }
     
     function load_kml_data (layer_type) {
                    $.ajax
                     ({
                         type: "POST",
                         url: "ajax/get_layer_data.php",
                         dataType: "json",
                         data: { layer_type:layer_type},
                         success: function(response)
                         {
                              html = "";
                              if(response.data.length>0) {
                              var count=response.data.length-1;
                              for (k=0;k<response.data.length;k++) {
                                 var myLatlng = new google.maps.LatLng(response.data[k].latitude,response.data[k].longitude);
                                  
                                  layer_marker = new google.maps.Marker({
                                        position: myLatlng,
                                        map: map,
                                        title: response.data[k].placemarker_name,
                                        //icon:  'images/magic.gif'
                                        icon:'images/layer_markers/'+response.data[k].placemarker_icon
                                    });
                                  kml_markes.push(layer_marker);
                                  
                                  
                                  
                              google.maps.event.addListener(layer_marker, 'click', (function(marker, k) {
                                        return function() {
                                                   var contentString = response.data[k].placemarker_description;
                                                  infowindow.setContent(contentString);
                                                  infowindow.open(map, marker);
                                        }
                              })(layer_marker, k));

                                  var tag='<a style="cursor:pointer" onclick=setlocation('+response.data[k].latitude+','+response.data[k].longitude+','+k+') >'+response.data[k].placemarker_name+'</a>';
                                  html += "<li>" + tag + "</li>";
                                  if(k==count) {
                                        $('#loader').hide();
                                  }
                              }
                              if(layer_type==11)
                              $("#navigation").append(html);
                              
                              } else {
                                   $('#loader').hide();
                              }
                               
                               
                                

                         }
                     });
     }
    
    

    
    
    
    
