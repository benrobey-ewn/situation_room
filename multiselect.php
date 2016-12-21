<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="http://d3js.org/d3.v2.js?2.9.1"></script>
<script src="js/aus_post_code/topojson.js" was="http://d3js.org/topojson.v1.min.js"></script>
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&libraries=drawing&sensor=false&v=3.exp"></script>
<style type="text/css">
  html, body {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

.hide {
  display: none;
}

#map, #canvas1 {
  position: absolute;
  margin: 0;
  padding: 0;
  top: 34px;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 10;
}

#canvas1 {
  z-index: 0;
}
#canvas1.front {
  z-index: 100;
}
#canvas1 svg {
  width: 100%;
    height: 100%
}

.stations {
  position: absolute;
}
.stations, .stations svg.aaa {
  position: absolute;
}
.stations border {
  position: absolute;
  stroke: black;
  stroke-width: 2px;
}
.stations svg.aaa {
  width: 60px;
  height: 20px;
  padding-right: 100px;
  font: 10px sans-serif;
}
.stations circle {
  fill: brown;
  stroke: black;
  stroke-width: 1.5px;
}

.menu{
    margin:0;
    padding:3px;
    background: grey;    
    list-style-type:none;
    overflow:hidden;
}
.menu li{
    float:left;
    background: white;
    margin-right:5px;
}
.menu li a{
    display:block;
    cursor: pointer;
    padding: 5px;
}
.menu li:hover, .menu .highlight {
    background: gold;
}

path {
        stroke: #000;
        stroke-width: 1;
        fill: none;
    }

.selectioncomplete{
    fill: crimson;
    fill-opacity:0.2;
}

circle {
  fill: #000;
  fill-opacity: .5;
  cursor: move;
}


rect {
  fill: none;
  pointer-events: all;
}

.SvgOverlayP svg {
    position: absolute;
    top: -4000px;
    left: -4000px;
    width: 8000px;
    height: 8000px;        
}
        
.SvgOverlayP path {
    stroke: green;
    stroke-width: 2px;
    fill: green;
    fill-opacity: .3;
}
.SvgOverlayP bl {
    stroke: blue;
    stroke-width: 2px;
    fill: blue;
    fill-opacity: .3;
}
        
.SvgOverlayP path.boundaryprox0 {
    stroke: #C03F00;
    stroke-width: 2px;
    fill: #C03F00;
    fill-opacity: .3;
}
.SvgOverlayP path.boundaryprox1 {
    stroke: #DB7605;
    stroke-width: 2px;
    fill: #DB7605;
    fill-opacity: .3;
}
.SvgOverlayP path.boundaryprox2 {
    stroke: Yellow;
    stroke-width: 2px;
    fill: Yellow;
    fill-opacity: .3;
}
.SvgOverlayP path:hover {
    stroke: #00ffff;
    stroke-width: 2px;
    fill: #00ffff;
    fill-opacity: .3;
}

svg 
{
  border: 1px solid purple;
  stroke: purple;
  stroke-width: 1px;
}

.clickable {
 cursor: pointer;
}


#custom_dialog {
position:absolute;
left:50%;
top:50%;
z-index:10;
width: 150px;
border: 1px solid #999;
margin: 0 auto; 
background-color: rgb(255, 255, 255);
padding:15px;

}

#closedialog {
position:absolute;
right:0px;
width:8%;
top:2px;
/*padding:5px;*/
}

#zipcode {
 width:80%;
 padding:5px;
}

#closedialog {
  cursor:pointer;
}

.SvgOverlayP  .feature {
  fill: green !important;
  opacity: .5 !important;
  cursor: pointer !important;
  stroke:green !important;
}


 .SvgOverlayP .active {
  fill: #00ffff !important;
  stroke: #00ffff !important;
}
.SvgOverlayP .activeR {
  fill: red !important;
  stroke: red !important;
  fill-opacity: .3;
}
.SvgOverlayP .activeB {
  fill: black !important;
  stroke: black !important;
  fill-opacity: .3;
}
.SvgOverlayP .activeA {
  fill: #ffcc00 !important;
  stroke: #ffcc00 !important;
  fill-opacity: .3;
}
.SvgOverlayP .activeG {
  fill: green !important;
  stroke: green !important;
  fill-opacity: .3;
}
  
</style>
<ul class="menu">    
    <li><a class="edit">Edit Area</a></li>
    <li><a class="clear">Clear Area</a></li>
    <li><a class="draw">Draw Area</a></li>  
</ul>


<div id="map"></div>
<div id="canvas1"><svg></svg></div>

<script type="text/javascript">
    $( document ).ready(function() {
var map, overlay, data;
var poly = null;
var useAjaxP = false;
var drawnOnceP = false;
var zip_markerP=new google.maps.Marker({});
var activeP, basinsP, basinPathsP, googleMapProjectionP, overlayProjectionP, curZP, layerP, svgP, post_overlayP, pathP, ignoreClickP = false;
var map;
var width = 960, height = 650;
var currentPolyObj=[];
var currentPostCode=[];
var curr_user_access=$("#nbn_user_type").val();
var marker;
var ca;
var lastOpenedInfoWindow;
var coordinates=[];
var menuHandler = {
    invoke: function(){
        $('.clear').click(function(e) {
            e.preventDefault();
            console.log("clear path");
            
            shapeSelector.clear();
        });
        
        $('.edit').click(function(e) {
            e.preventDefault();
            
            if($(this).data("editing")){
                $(this).data("editing", false);
                $(this).removeClass('highlight')
                poly.setEditable(false);
            }else{
                shapeSelector.edit();
            }
            
        }); 
 
        $('.draw').click(function(e) {
            e.preventDefault();
            if ($(this).hasClass('highlight')) {
                d3.select("#canvas1").classed({'front': false})
                $(this).removeClass('highlight');
                poly.setMap(map);
            } else {
                shapeSelector.clear();
                $(this).addClass('highlight');
                console.log("start drawing");

                
                shapeSelector.invoke();
            }
            
        });
    }
};

menuHandler.invoke();

//////////////////////////////


var shapeSelector = {
        invoke: function(){
            var that = this;
            
            // An array to hold the coordinates
            // of the line drawn on each svg.
            var  coords = []
            this.line = d3.svg.line();
            
            this.drag = this.getDragBehaviours()

            this.svg = d3.select("#canvas1").classed({'front': true})
                .select('#canvas1 svg')
                   .call(that.drag);
        },
        getDragBehaviours: function(){
            var that = this;
                  
            // Set the behavior for each part
            // of the drag.
            drag = d3.behavior.drag()
                .on("dragstart", function() {
                    // Empty the coords array.
                    coords = [];
                    svg = d3.select(this);
                    
                    // If a selection line already exists,
                    // remove it.
                    svg.select("#canvas1 .selection").remove();
                    
                    // Add a new selection line.
                    svg.append("path").attr({"class": "selection"});

                })
                .on("drag", function() {
                    // Store the mouse's current position

                    //console.log('Chords='+d3.mouse(this));
                    //console.log('Console---'+coordinates);
                    coords.push(d3.mouse(this));


                    
                    svg = d3.select(this);
                    
                    // Change the path of the selection line
                    // to represent the area where the mouse
                    // has been dragged.

                    svg.select("#canvas1 .selection").attr({
                        d: that.line(coords)
                    });
                    
                })
                .on("dragend", function() {
                    //console.log(d3.select("#map svg path"));
                    svg = d3.select(this);
                    // If the user clicks without having
                    // drawn a path, remove any paths
                    // that were drawn previously.
                    if (coords.length === 0) {
                        d3.selectAll("#canvas1 svg path").remove();
                        return;
                    }
                   // $("path#nbn_0872").attr("class","active");
                    // Draw a path between the first point
                    // and the last point, to close the path.
                    var currentPath = d3.selectAll("#canvas1 svg path").attr("d");
                    var finalPath = currentPath + " " + that.line([coords[0], coords[coords.length-1]]);
                    d3.selectAll("#canvas1 svg path").attr("d", finalPath)
                    
                    
                    svg.select("#canvas1 .selection").attr("class", "selection selectioncomplete");
                    var simpleCoords = that.simplifyShape($("#canvas1 .selection"));
                    var postcodeA=d3.selectAll("#map path").attr("cord");
                    //console.log("simpleCoords="+simpleCoords);
                    that.addShapeToBaseMap(simpleCoords);
                });
            
            return drag;
        },

        addShapeToBaseMap: function(divCoords) {
            var geoCoords = []
            for (var i = 0; i < divCoords.length; i++)
                geoCoords.push(overlay.getProjection().fromContainerPixelToLatLng(new google.maps.Point(Number(divCoords[i][0]), Number(divCoords[i][1]))));

            poly = new google.maps.Polygon({
                paths: geoCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });
            google.maps.event.addListener(poly, 'click', function (event) { shapeSelector.edit(); });
            poly.setMap(map);
            //this.setMarkerStates();
            svg.select("#canvas1 .selection").remove()
            d3.select("#canvas1").classed({'front': false});
            $('.draw').removeClass('highlight');
        },

        edit: function() {
            $(".edit").data("editing", true);
            $(".edit").addClass('highlight')
            poly.setEditable(true);
        },

        clear: function() {
            if (poly)
                poly.setMap(null);
            poly = null;
            d3.select("#canvas1 .selection").remove()
            d3.selectAll(".marker").classed({'hide': false});
            d3.select("#canvas1").classed({'front': false});
            $('.menu li').removeClass('highlight');
        },

        simplifyShape: function(svg){
           
            
            var points = svg.attr("d");
            var epsilon = 10;
            var path = path_from_svg(points);
            var simp = path_simplify(path, epsilon);
            
            var points1 = svg_to_path(simp);

            //console.log("Points="+points1);
                        
           $(".selectioncomplete").attr("d", points);

           return simp;
                        
                        
            function path_simplify_r(path, first, last, eps) {
                if (first >= last - 1) return [path[first]];
            
                var px = path[first][0];
                var py = path[first][1];
            
                var dx = path[last][0] - px;
                var dy = path[last][1] - py;
            
                var nn = Math.sqrt(dx*dx + dy*dy);
                var nx = -dy / nn;
                var ny = dx / nn;
            
                var ii = first;
                var max = -1;
            
                for (var i = first + 1; i < last; i++) {
                    var p = path[i];
            
                    var qx = p[0] - px;
                    var qy = p[1] - py;
            
                    var d = Math.abs(qx * nx + qy * ny);
                    if (d > max) {
                        max = d;
                        ii = i;
                    }
                }
            
                if (max < eps) return [path[first]];
            
                var p1 = path_simplify_r(path, first, ii, eps);
                var p2 = path_simplify_r(path, ii, last, eps);
            
                return p1.concat(p2);        
            }
            
            function path_simplify(path, eps) {
                var p = path_simplify_r(path, 0, path.length - 1, eps);
                return p.concat([path[path.length - 1]]);
            }
            
            function path_draw(path, cx, color) {
                cx.strokeStyle = color;
                cx.beginPath();
                cx.moveTo(2*path[0][0], 2*path[0][1]);
            
                for (var i = 1; i < path.length; i++) {
                    var p = path[i];
                    cx.lineTo(2*p[0], 2*p[1]);
                }
                cx.stroke();
            }
            
            function path_repr(path) {
                var repr = "";
                for (var i = 0; i < path.length; i++) {
                    if (i) repr += ", ";
                    repr += "(" + path[i][0] + ", " + path[i][1] + ")";
                }
                return repr;
            }
            
            function path_from_svg(svg) {
                var pts = svg.split(/[ML]/);
                var path = [];
            
                console.log(pts.length);
                for (var i = 1; i < pts.length; i++) {
                    path.push(pts[i].split(","));
                }
                
                return path;
            }
            function svg_to_path(path) {
                return "M" + path.join("L");
            }            
        }
    }    








    var googleMaps = {
    init: function() {
        // create map
        map = new google.maps.Map(d3.select("#map").node(), {
            zoom: 5,
            center: new google.maps.LatLng(-27.978636, 130.396606),
            mapTypeId: google.maps.MapTypeId.ROAD
        });
        
        
        
        addMarkerOverlay();

        function addMarkerOverlay() {
            // Load the station data. When the data comes back, create an overlay.
            overlay = new google.maps.OverlayView();

           var bTypeKey = 'PC';
           var z=9;
          useAjaxP = false;
          var fn = "js/boundaries/b-" + bTypeKey + "-geo-topo-" + z + ".json"; 
            // Add the container when the overlay is added to the map.
          //console.log(fn);  
                    d3.json(fn, function (topology) {
                     
                            overlay.onAdd = function() {

                            //console.log("overlay onAdd");
                            //if (!layer)
                            layerP = d3.select(this.getPanes().overlayMouseTarget).append("div").attr("class", "SvgOverlayP basins");
                            //console.log(svgP);
                            if (svgP)
                            $('svgP').remove();
                            svgP = layerP.append("svg").attr("width", width).attr("height", height);
                            basinsP = svgP.append("g").attr("class", "basins");
                            
                            overlay.draw = function () {
                                       
                                
                                          //console.log("overlay draw");
                                var markerOverlay = this;
                                overlayProjectionP = this.getProjection();
                                                            
                                            // Turn the overlay projection into a d3 projection
                                googleMapProjectionP = function (coordinates) {
                                  //console.log(coordinates);
                                  var googleCoordinates = new google.maps.LatLng(coordinates[1], coordinates[0]);
                                  var pixelCoordinates = overlayProjectionP.fromLatLngToDivPixel(googleCoordinates);
                                  //console.log(pixelCoordinates.x+''+pixelCoordinates.y);
                                  return [pixelCoordinates.x + 4000, pixelCoordinates.y + 4000];
                                }
                                
                              pathP = d3.geo.path().projection(googleMapProjectionP);
                              ca=getClassFromDb();
                              //console.log(pathP);
                              //console.log(ca);
                              basinPathsP = basinsP.selectAll("#map path")
                                    .data(topojson.feature(topology, topology.objects.boundary).features)
                                    .attr("d", pathP)
                                    .enter().append("path")
                                    .attr("d", pathP)
                                    .attr("class", function(d){
                                                      var cArr=d.geometry.coordinates;
                                                     //coordinates.push(cArr[0]);
                                                      if(ca.classes.hasOwnProperty(d.properties.name)){
                                                          //console.log(ca.classes[d.properties.name]);
                                                          if(ca.classes[d.properties.name]=='activeR'){
                                                            $("#Red_zones").append('<li id="'+d.properties.name+'"><a style="cursor:pointer" onclick="setPolygon('+d.properties.name+','+cArr+');">'+d.properties.name+'</a></li>');
                                                              
                                                          }
                                                          if(ca.classes[d.properties.name]=='activeB'){
                                                            $("#Black_zones").append('<li id="'+d.properties.name+'"><a style="cursor:pointer" onclick="setPolygon('+d.properties.name+','+cArr+');">'+d.properties.name+'</a></li>');
                                                          }
                                                          if(ca.classes[d.properties.name]=='activeA'){
                                                            $("#Amber_zones").append('<li id="'+d.properties.name+'"><a style="cursor:pointer" onclick="setPolygon('+d.properties.name+','+cArr+');">'+d.properties.name+'</a></li>');
                                                              
                                                          }
                                          return ca.classes[d.properties.name];
                                        }else{
                                                            $("#Green_zones").append('<li id="member_'+d.properties.name+'"><a style="cursor:pointer" onclick="setPolygon('+d.properties.name+','+cArr+');">'+d.properties.name+'</a></li>');
                                          return 'activeG';
                                        }
                                                      
                                                      }
                                                    )
                                                    .attr("id", function(d){

                                                      return "nbn_"+d.properties.name;
                                                    })
                                                     .attr("cord", function(d){
                                                      return "nbn_"+d.properties.name;
                                                    })
                                                   
                                                    .on("click", clickP)  
                                                    //.attr("transform", function(d) { return "translate(" + pathP.centroid(d) + ")"; })
                                    .append("svg:title")
                                    .text(function (d) {
                                                    return d.properties.name; 
                                    }); 

                               

                          };
                        
                        };
                  
                      // Bind our overlay to the map…
                      overlay.setMap(map);
                    });
        }
    }
}


googleMaps.init();

});
var color_arr = {};   
var currentPolyObj =[];
    function clickP(d) {
      //console.log(ca);
      //console.log("Coming="+coords);
      var cords=d.geometry.coordinates;
      var cord=cords[0];

      //var middle = cord[Math.round((cord.length - 1) / 2)];

      //console.log(middle[0]+'-'+middle[1]);

      for (var i = 0; i < cord.length; i++) {

        //console.log(cord[i][0]+'***'+cord[i][1]);

      }
      //console.log(d.geometry.coordinates);
      var coordinates=d.geometry.coordinates;
      var temp_id = d.properties.name;
      var $path = $("path#nbn_"+temp_id);
      
      var classP = $path.attr('class'); 

      if(color_arr[temp_id] === undefined){
        color_arr[temp_id] = classP;
          $path.attr("class","");
          if(classP=='activeG'){
              $path.attr("class","active");
        }else{
          $path.attr("class","activeG");
        }
      } else {
            $path.attr("class","");
            $path.attr("class",color_arr[temp_id]);
        delete color_arr[temp_id];
      }
      //console.log(color_arr);
      
        $("#open_alert_form").show();
        if(currentPolyObj.indexOf(temp_id) == -1 && currentPolyObj!=undefined){
                        currentPolyObj.push(temp_id);

                     } else {
                        currentPolyObj.remove(temp_id);
                 }
                 $("#nbn_postcodes").val(currentPolyObj);
                 //console.log(currentPolyObj);
      
        
        
           
    }
function closeLastOpenedInfoWindo() {
  if (lastOpenedInfoWindow) {
      lastOpenedInfoWindow.close();
  }
 }  
 var center_point_lat;
  var center_point_lon;
function setPolygon(postcode,lat,lng){
    
                   center_point_lat=lng;
                   center_point_lon=lat;
              
                map.setCenter(new google.maps.LatLng(center_point_lat,center_point_lon));
        marker = new google.maps.Marker({
                        position: new google.maps.LatLng(center_point_lat,center_point_lon),
                        map: map,
                        icon: 'images/magic.gif',
                      
                    });
          //console.log(marker);
                  var zip_marker_contentString = '<div id="markerinfo" style="width:100px;">Post code - '+postcode+'</div>';
                var zip_marker_infowindow = new google.maps.InfoWindow({
                  content: zip_marker_contentString
                });
                     google.maps.event.addListener(marker, 'click', function() {
                   closeLastOpenedInfoWindo();
                   zoom(map);
                  zip_marker_infowindow.open(map,marker);
                  map.setZoom(6);
                  lastOpenedInfoWindow = zip_marker_infowindow;
            });
            google.maps.event.trigger(marker, 'click');
}

    
function sendAlert(currentObj,color,formData){
  //console.log(ca);
  console.log(currentObj);
  console.log(currentObj.length);
    color_arr = {};
    var lngth=currentObj.length-1;
      for (var i = 0; i < currentObj.length; i++) {
             var $poly_id = currentPolyObj[i]; 

             if(ca.classes.hasOwnProperty($poly_id)){
                if(ca.classes[$poly_id]=='activeR'){
                  $('#Red_zones #'+$poly_id).remove();
                }
                if(ca.classes[$poly_id]=='activeB'){
                  $('#Black_zones #'+$poly_id).remove();
                }
                if(ca.classes[$poly_id]=='activeA'){
                  $('#Amber_zones #'+$poly_id).remove();

                }
                //console.log(ca.classes[$poly_id]);
             }else{

                $('#Green_zones #'+$poly_id).remove();
             }    
               $("path#nbn_"+$poly_id).attr("class","");
               if(color=='Red'){
                  $("path#nbn_"+$poly_id).attr("class","activeR");
                  $("#Red_zones").append('<li id="'+$poly_id+'"><a style="cursor:pointer" onclick="setPolygon('+$poly_id+','+$poly_id+');">'+$poly_id+'</a></li>');
                   //color_arr[$poly_id] = 'activeR';
               }
               if(color=='Black'){
                  $("path#nbn_"+$poly_id).attr("class","activeB");
                  $("#Black_zones").append('<li id="'+$poly_id+'"><a style="cursor:pointer" onclick="setPolygon('+$poly_id+','+$poly_id+');">'+$poly_id+'</a></li>');
                   //color_arr[$poly_id] = 'activeB';
              }
               if(color=='#ffcc00'){
                  $("path#nbn_"+$poly_id).attr("class","activeA");
                  $("#Amber_zones").append('<li id="'+$poly_id+'"><a style="cursor:pointer" onclick="setPolygon('+$poly_id+','+$poly_id+');">'+$poly_id+'</a></li>');
                  // color_arr[$poly_id] = 'activeA';

               }
               if(color=='green'){
                  $("path#nbn_"+$poly_id).attr("class","activeG");
                  $("#Green_zones").append('<li id="'+$poly_id+'"><a style="cursor:pointer" onclick="setPolygon('+$poly_id+','+$poly_id+');">'+$poly_id+'</a></li>');
                   //color_arr[$poly_id] = 'activeG';
                   
               }

               
               //delete color_arr;
              // console.log("After Sending"+color_arr);
              
                  //console.log("Numbers"+(currentObj.length-1);
          //console/log
            currentPolyObj.remove($poly_id);
      } 
      
}

function getClassFromDb(){
      
      
                        $.ajax({
                async: false, 
                dataType: "json",

                url: "ajax/get_class.php",
                success : function (response)
                          {
                              ajaxResponse = response;
                          },
                
            });

            return ajaxResponse;
           

}
function sendAlertEwn(alertData){
      //console.log(alertData);
      $("#ajax_loader").show();
      $.ajax({
                async: false, 
                dataType: "json",
                url: "ajax/send_ewn_alert.php",
                data: alertData,
                success : function (response)
                          {
                              for (var i = 0; i < response.length; i++) {
                          console.log(response[i].postcode);  
                          $("path#nbn_"+response[i].postcode).attr("class",response[i].class);
                          currentPolyObj.remove(response[i].postcode);
                          delete color_arr[response[i].postcode];
                        }

                      $("#nbn_postcodes").val('');
                      $("#ajax_loader").hide();
                    }
                
            });
}
function confirm_alert()
  {
      var status= confirm("Are you sure wish to send this alert!");
      if (status== true){

         var alert_type=$("#alert_type").val();
         var formData=$("#save_nbn_alert_form").serialize();
         sendAlertEwn(formData);
         setTimeout(function(){ alert("Alert has been sent successfully"); }, 2000);
         $('#save_nbn_alert_form')[0].reset();
          $('.nbn_description_polygon_alert').html('');
         $('#popup_nbn_polygon').modal('toggle');
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


    
function remove_post_codeP() {
    zip_markerP.setMap(null);
    google.maps.event.clearListeners(map, 'click');
    //console.log(post_overlay);
    //post_overlay.setMap(null);
    
    if(typeof svgP!=='undefined') {
      svgP.selectAll("path").remove();
    }
    
    if(post_overlayP) {
      post_overlayP.setMap(null);
      post_overlayP=false;
    }
    
    curZP=0;
}  
  
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
    
   
 

});     




</script>