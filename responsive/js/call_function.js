var polygon_complete_lat_longs = [];

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
  //return $("#layers").val();
  //console.log(current_selected_layer);
  //console.log('length'+current_selected_layer.length);
  if(current_selected_layer.length>1) {
  	 var layer_id=current_selected_layer.join();
  } else {
	  var layer_id=current_selected_layer[0];
  }
  return layer_id;
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
   
  //clear_range_markers();
  remove_filter_marker();
  //clearMapLayers();
  hide_layer_markers();
  show_asset_click=true;
  
  
  $('#loader').show();

  if(latlongarray.length==0)
  {
      $('#loader').hide();
  }
  //console.log(latlongarray.length);

  var response_count=0;
  position_url="ajax/get_newpoints.php";

  console.log('polygon length'+latlongarray.length);
  
  filtermarkers.length=0;
  //var mydata = 'layers='+$('#layers').val()+'&raw_data='+$('#raw_data').val();

  for(l=0 ; l<latlongarray.length;l++)
  {
    var mydata = 'selected_layer_value_current='+getSelectBoxValue()+'&polygon_number='+l+'&minLat='+latlongarray[l].minLat+'&minLong='+latlongarray[l].minLong+'&maxLat='+latlongarray[l].maxLat+'&maxLong='+latlongarray[l].maxLong;
  $.ajax
  ({
    type: "POST",
    url: position_url,
    dataType: "json",
    data: mydata,
    async: true,
    success: function(response) 
    { 

      response_count++;

      if(response.data.length>0) 
      {
        //filtermarkers.length=0;
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

     if(response_count==latlongarray.length) {
      console.log(response_count);
       $('#loader').hide(); 
     }

    }
  });
    if(l==latlongarray.length-1)
    {
        console.log('end loop');
        //$('#loader').hide();
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
    function draw_map(coordinates,color_code,url,id,source_type,created_date)
    {
        var  minLat = 1000;
		var  minLong = 1000;
		var  maxLat = -1000;
		var  maxLong = -1000;
      //created_date=created_date+'Z';
      created_date=timeToLocal(created_date);
      //console.log(created_date);
        //$('#loader').show();
        //console.log(created_date);
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
                       // finding minimum of latitude
                         if (parseFloat(latlog[0]) < parseFloat(minLat)) {
                          minLat = latlog[0];
                          //console.log(minLat);
                         }

                      // finding minimum of logitudes
                         if (parseFloat(latlog[1]) < parseFloat(minLong)) {
                          minLong = latlog[1];
                         // console.log(minLong);
                         }

                         // finding maximum of latitude
                         if (parseFloat(latlog[0]) > parseFloat(maxLat)) {
                          maxLat = latlog[0];
                          //console.log(maxLat);
                         }

                         // finding maximum of longitude
                         if (parseFloat(latlog[1]) > parseFloat(maxLong)) {
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
                            newElement['created_date'] = created_date;
                    latlongarray.push(newElement);

                    //console.log(latlongarray);
                    
                    google.maps.event.addListener(polygon_marker1, 'click', function (event)
                    {
                        google.maps.event.trigger(map, 'click', event);
                        newPopup(url,'587','600');
                    });
                    
                    polygon_marker1.setMap(map);
                    
                    
                    
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
    function forecast_draw_map(coordinates,color_code,url,id,created_date)
    {
        
        var  forecast_minLat = 1000;
    var  forecast_minLong = 1000;
    var  forecast_maxLat = -1000;
    var  forecast_maxLong = -1000;
    created_date=timeToLocal(created_date);
    
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
                        
                        if (parseFloat(latlog[0]) < parseFloat(forecast_minLat)) {
                          forecast_minLat = latlog[0];
                          //console.log(minLat);
                         }

                      // finding minimum of logitudes
                         if (parseFloat(latlog[1]) < parseFloat(forecast_minLong)) {
                          forecast_minLong = latlog[1];
                         // console.log(minLong);
                         }

                         // finding maximum of latitude
                         if (parseFloat(latlog[0]) > parseFloat(forecast_maxLat)) {
                          forecast_maxLat = latlog[0];
                          //console.log(maxLat);
                         }

                         // finding maximum of longitude
                         if (parseFloat(latlog[1]) > parseFloat(forecast_maxLong)) {
                          forecast_maxLong = latlog[1];
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
                    
                    polygon_marker1.set('zIndex',-1000);
    
                    polygon_marker1.setMap(map);
                    
                    var newElement = {};
                    newElement['minLat'] = forecast_minLat;
                    newElement['minLong'] = forecast_minLong;
                    newElement['maxLat'] = forecast_maxLat;
                    newElement['maxLong'] = forecast_maxLong;
                    newElement['polygonPrimaryKey'] = id;
                    newElement['created_date'] = created_date;
                    forecast_latlongarray.push(newElement);
                    
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
        map.setOptions({styles: ""});
      }
      else if(mapType=='TERRAIN')
      {
        map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
        map.setOptions({styles: ""});
      }
      else if(mapType=='light_monochrome')
      {
        map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
        var mapOptions = {
           styles: [{"featureType":"administrative.locality","elementType":"all","stylers":[{"hue":"#2c2e33"},{"saturation":7},{"lightness":19},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":-2},{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#e9ebed"},{"saturation":-90},{"lightness":-8},{"visibility":"simplified"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":10},{"lightness":69},{"visibility":"on"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":-78},{"lightness":67},{"visibility":"simplified"}]}]
        };
        map.setOptions(mapOptions);
        $("#map_options_selector").find("option[value='light_monochrome']").attr("selected",true);
        setting[5].map_option="light_monochrome";
      }
      else
      {
        map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        map.setOptions({styles: ""});
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
        if($("#radar_forecasts_information").attr("checked"))
         {
          load_forecast_markers();
         }
        /* load radar images */
        
        /* Update Warning */
        update_warning();
        /* forecast */
        
        /* update forecast severe */
        update_forecast_severe();
        
        /* Rainfall */
        update_rainfall();

        /* cyclone */
        if($('.cyclone').attr("checked") && $(".weather").attr("checked")){
          hide_cyclone();
          clearInterval(cyclone_interval);
          toggle_cyclone();
        }
        
    }
    
    
    function loadWeatherRadarimages() {
          
          $.each(places,function(c,category)
             {
         
          $('#radar_forecasts_information').data('goo').set('map_canvas',(this.checked)?map:null);
                 //  var cat=$('<input>',{type:'checkbox',id:'radar_forecasts_information'}).change(function(){
                 var cat=$("#radar_forecasts_information").change(function()
                 {
                     //alert('123');
                     //console.log(cat);
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
                     if(((LOGIN_USER_NAME=='admin' || LOGIN_USER_NAME=='bechtel') && (mystate=='Learmonth')) || (mystate!='Learmonth'))
                     {
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
                    }
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
                      if(((LOGIN_USER_NAME=='admin' || LOGIN_USER_NAME=='bechtel') && (mystate=='Learmonth')) || (mystate!='Learmonth'))
                     {
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
                  }
                 });
          });
    }
    
     var markes=[];
    var layer_marker;
     var kml_markes=[];
    
    
     /*function setlocation(lat,lng,index) {
             map.setCenter(new google.maps.LatLng(lat, lng));
            google.maps.event.trigger(kml_markes[index], 'click');
            map.setZoom(10);
     }*/
	 
	 function setlocation(lat,lng,index) {
             map.setCenter(new google.maps.LatLng(lat, lng));
            google.maps.event.trigger(multi_kml_marker_array[index], 'click');
            map.setZoom(10);
     }
     
    /* function show_sites() {
          $('.navigation').show();
          $('#show_sites').hide();
          $('#hide_sites').show();
          if(show_asset_click) {
           clear_range_markers();
          }
     }
     
     function hide_sites() {
          $('.navigation').hide();
          $('#hide_sites').hide();
          $('#show_sites').show();
     }*/
	 function show_sites(layer_type)
     {
		  /* hide publlic sites */
		  $('#public_show_sites').show();
      $('#public_hide_sites').hide();
      
      // mobile devices 
      var screen_width = $(window).width();
      if(parseInt(screen_width) < 414) {
        $('.navigation').css({"right": "0px"});
        $('#full_page_menu').toggle();
        if($('#full_page_menu').css('display') == 'none') {
              $('#map-canvas').width($(window).width());
        } else {
             $('#map-canvas').css({"width":resize_map_width+'px'});
        }

        triggerMap_1(map);
        $('#nav-toggle').removeClass('active');
      }
      // mobile devices 
		  
		  $('.site_list_option').show();
		  $('#show_sites').hide();
      $('#hide_sites').show();
		 //$('.site_list_button').show();
		 var layer_id=$('#show_sites_layers').val();
		 listFilter($('#site_layer_title'+layer_id), $('#navigation'+layer_id),layer_id);
		 
		 $('.navigation').hide();
		 $('#navigation'+layer_id).show();
		 
  }

   /* function for trigger map rsize */
    function triggerMap_1(m) {
      console.log(m);
      if(typeof m !=='undefined' && m!==null) {
        x = m.getZoom();
        c = m.getCenter();
        google.maps.event.trigger(m, 'resize');
        m.setZoom(x);
        m.setCenter(c);
      }
    }

  function hide_sites()
  {
    //$('.site_list_option').hide();
		$('#hide_sites').hide();
    $('#show_sites').show();
		 var layer_id=$('#show_sites_layers').val();
		 $('.navigation').hide();
		 $('#navigation'+layer_id).hide();
		//$('.site_list_button').hide();
  }
	 
	 function show_public_sites() {
		 /* on show sites */
		 $('#hide_sites').hide();
     $('#show_sites').show();

      // mobile devices 
      var screen_width = $(window).width();
      if(parseInt(screen_width) < 414) {
        $('.navigation').css({"right": "0px"});
        $('#full_page_menu').toggle();
        if($('#full_page_menu').css('display') == 'none') {
              $('#map-canvas').width($(window).width());
        } else {
             $('#map-canvas').css({"width":resize_map_width+'px'});
        }

        triggerMap_1(map);
        $('#nav-toggle').removeClass('active');
      }
      // mobile devices 
		
		  $('.site_list_option').show();
		  $('#public_show_sites').hide();
      $('#public_hide_sites').show();
		 //$('.site_list_button').show();
		 var layer_id=$('#public_show_sites_layers').val();
		 listFilter($('#site_layer_title'+layer_id), $('#navigation'+layer_id),layer_id);
		 
		 $('.navigation').hide();
		 $('#navigation'+layer_id).show();
	 }
	 
	  function hide_public_sites()
     {
        //$('.site_list_option').hide();
		$('#public_show_sites').show();
          $('#public_hide_sites').hide();
		 var layer_id=$('#public_show_sites_layers').val();
		 $('.navigation').hide();
		 $('#navigation'+layer_id).hide();
		//$('.site_list_button').hide();
     }
     
     function load_kml_data (layer_type) {
      $('#loader_layer_type').show();
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
                                                  // var contentString = response.data[k].placemarker_description;
                                                   var contentString = '<div style="width:280px;overflow-y: visible;height:auto;">'
                                                                         +'<div style="padding:5px 2px;">'+response.data[k].placemarker_description+'</div>'
                                                  +'</div>';
                                                  infowindow.setContent(contentString);
                                                  infowindow.open(map, marker);
                                        }
                              })(layer_marker, k));
                                  
                                  if(response.data[k].placemarker_name=='') {
                                    response.data[k].placemarker_name='-'
                                  }
                                  var tag='<a style="cursor:pointer" onclick=setlocation('+response.data[k].latitude+','+response.data[k].longitude+','+k+') >'+response.data[k].placemarker_name+'</a>';
                                  html += "<li>" + tag + "</li>";
                                  if(k==count) {
                                       // $('#loader').hide();
                                   $('#loader_layer_type').hide();
                                  }
                              }
                              /* condition for show sites */
							   if (((jQuery.inArray(parseInt(layer_type),client_layers))!=-1) || (layer_type==2 && (LOGIN_USER_NAME=='telstra' || LOGIN_USER_NAME=='admin'))) {
                             /* if((layer_type==11 || layer_type==13 || layer_type==14 || layer_type==15 || layer_type==16 || layer_type==17 || layer_type==18 || layer_type==20 || layer_type==21 || layer_type==22 || layer_type==23 || layer_type==24 || layer_type==25 || layer_type==26 || layer_type==27) || (layer_type==2 && (LOGIN_USER_NAME=='telstra' || LOGIN_USER_NAME=='admin'))) {*/
                                    $("#navigation").html('');
                                    $("#site_layer_title").html(response.layer_name);
                                    $("#navigation").append(html);
                                    /* show sites option */    
                                    $('#layer_button').show();
                                    $('#show_sites').show();
	  
                              }
                              
                              } else {
                                   //$('#loader').hide();
                                   $('#loader_layer_type').hide();
                              }
                               
                               
                                

                         }
                     });
     }
    
    

function download_polygon_show_range_csv()
{
  //console.log(opener.latlongarray);
  var myhtml1='';

  var display_polygon_marker_array = [];
    polygon_complete_lat_longs = [];

  /*Blanl current html*/
  $('#display_markers_point_div').html('');
  /**/
    $('#loader').show();
    var complete_lat_longs = [];
    var myLatlngnew;
    var current_image_name_path="images/layer_markers/";
    position_url="ajax/display_newpoints.php";
    for(l=0 ; l<latlongarray.length;l++)
    {
       // console.log(latlongarray[l].polygonPrimaryKey);
        var mydata = 'selected_layer_value_current='+getSelectBoxValue()+'&polygonPrimaryKey='+latlongarray[l].polygonPrimaryKey+'&polygon_number='+l+'&minLat='+latlongarray[l].minLat+'&minLong='+latlongarray[l].minLong+'&maxLat='+latlongarray[l].maxLat+'&maxLong='+latlongarray[l].maxLong;
        
        $.ajax
        ({
            type: "POST",
            url: position_url,
            dataType: "json",
            data: mydata,
            async: false,
            success: function(response) 
            { 
              var getSelectedValue = getSelectBoxText();
             
                
                if(response.data.length>0) 
                {
                    var polygon_layers=[];
                    var flag=false;
                    for (i=0;i<response.data.length;i++)
                    {
                        var myLatlng = new google.maps.LatLng(response.data[i].latitude,response.data[i].longitude);
                        var present_polygon = polygonMarkerArray[response.polygon_number];
                        if(google.maps.geometry.poly.containsLocation(myLatlng, present_polygon) == true)
                        {
                            index = indexOfPolygons.call(display_polygon_marker_array, response.polygon_number);
                             var ele_id=response.polygon_number+response.data[i].layer_type.split(' ').join('_');
                             //console.log(ele_id+response.data[i].placemarker_name);
                            var newElements = {};
                            newElements['polygon_subject'] = response.polygon_subject;
                            newElements['polygon_number'] = response.polygon_number;
                            newElements['placemarker_name'] = response.data[i].placemarker_name;
                            newElements['placemarker_icon'] = response.data[i].placemarker_icon;
                            newElements['latitude'] = response.data[i].latitude;
                            newElements['longitude'] = response.data[i].longitude;
                            newElements['layer_type'] = response.data[i].layer_type;
                            polygon_complete_lat_longs.push(newElements);

                            /* */
                        }                       
                    }
                }
            }
        });

        if(l==latlongarray.length-1)
        {
         //console.log('1231');
         $('#loader').hide();
         exportDataToCSVDirect();
        }


    }
      
}




   function exportDataToCSVDirect()
   {

    //console.log(polygon_complete_lat_longs);
   
       $('#loader').show();
      var export_url="ajax/export_data_output.php";
      var mydata = 'layer_data='+polygon_complete_lat_longs;
      //console.log(mydata);
      $.ajax
      ({
        type: "POST",
        url: export_url,
        data: JSON.stringify(polygon_complete_lat_longs),
        dataType: "json",
        success: function(response) 
        { 
          //var my_data = JSON.stringify(response);
          //var url = "../ajax/export_data_csv.php?data="+my_data;
          //window.location.href=url;
           $('#loader').hide();
          //document.location='csv/csvfile.csv';
          //window.location='csv/csvfile.csv?0';
          downloadFile('csv/csvfile.csv');
        }
      });

   }
   
   /*function showAjaxWait(event) {

    if ($('.ajaxwaitobj').length == 0) {
        var e;
        if (event.target != undefined) {
        e = $(event.target);
        } else {
        e = $(event);
        }
        if (e.prop('tagName') == "DIV") {
        e.append('<span class="ajaxwaitobj"><img src="images/ajax-loader-16.gif" width="16" height="16" /></span>');
        }
        else {
        $('<span class="ajaxwaitobj"><img src="images/ajax-loader-16.gif" width="16" height="16" /></span>').insertAfter(e);
        }
        }
    }
    
    function hideAjaxWait(count) {
    var w = $('.ajaxwaitobj');
    if ((w == null || w.length == 0) && count == undefined) 
    {
    // maybe browser is busy and hasn't yet drawn the waitobject
    window.setTimeout("if (typeof hideAjaxWait == 'function') { hideAjaxWait(1);  }", 500);
    }
    else 
    {
    $('.ajaxwaitobj').detach();
    }
    }*/
   
   function showAjaxWait(event,element) {
   if ($('.'+element).length == 0) {
        var e;
        if (event.target != undefined) {
        e = $(event.target);
        } else {
        e = $(event);
        }
        if (e.prop('tagName') == "DIV") {
        e.append('<span class="ajaxwaitobj '+element+'"><img src="images/ajax-loader-16.gif" width="16" height="16" /></span>');
        }
        else {
        $('<span class="ajaxwaitobj '+element+'"><img src="images/ajax-loader-16.gif" width="16" height="16" /></span>').insertAfter(e);
        }
        }
    }
    
    function hideAjaxWait(element) {
    //var w = $('.ajaxwaitobj');
    var w = $('.'+element);
    if ((w == null || w.length == 0)) 
    {
    // maybe browser is busy and hasn't yet drawn the waitobject
       //window.setTimeout("if (typeof hideAjaxWait == 'function') { hideAjaxWait(1);  }", 500);
    }
    else 
    {
    //$('.ajaxwaitobj').detach();
       w.detach();
    }
    }
   
   
    
    
    
    
    
