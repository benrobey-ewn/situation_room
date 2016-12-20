<?php include 'includes/config.php';?>
<link href="css/bootstrap3.min.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/bootstrap3-responsive.min.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/lib/fonts.googleapis.css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
        <link href="css/font-awesome.min.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/lib/4.4.0-css-font-awesome.min.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/ui-lightness/ui-lightness.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/base-admin-3.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/base-admin-3-responsive.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/pages/dashboard.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/custom.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/extra.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/layer.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/radar.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/observation.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/aus_post_code.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/forecast.css?v=<?php echo SR_VER ?>" rel="stylesheet">
        <link href="css/lib/style.css?v=<?php echo SR_VER ?>" rel="stylesheet" />     
        <script type="text/javascript" src="js/jquery-1.7.2.js"></script>
        <script src="js/libs/bootstrap.min.js?v=<?php echo SR_VER ?>"></script>



      <script src="http://maps.google.com/maps/api/js?sensor=false&.js"></script>
      <script src="js/models.js?v=<?php echo SR_VER ?>"></script>
      <script>


var x = new google.maps.LatLng(-27.978636, 130.396606);
var map;
var currentPolyObj = [];
var polyArr=[];
var points;
var pointsMore;
var polygon;

   // console.log(data);
 function initialize() {

             
   
    var mapProp = {
        center: x,
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    //var ids=[];
    
    map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
      
    loadPolygon(1,map);
    for (var i = 2; i < 10; i++) {
      setTimeout(loadPolygon(i,map), 5000); 
    }
    //loadPolygon(2,map);
    //loadPolygon(3,map);
    //loadPolygon(4,map);
    //loadPolygon(5,map);
    
}  

var nbnArr=[];
var currentPolyObj = [];
function loadPolygon(page,map){
                      $.ajax({
                          url: "ajax/get_nbn_layer_data1.php?page="+page,
                          dataType: "json",
                          //async: false,
                        }).done(function(response) {
                          //console.log(response);
                          for (var i = 0; i < response.length; i++) {
                            var nbn_name=response[i].properties.name;
                            var nbn_type=response[i].properties.btype;
                            var nbn_main_id=response[i].properties.nbn_main_id;
                            var nbn_color=response[i].properties.fillColor;
                            //console.log(nbn_type+'-'+nbn_name+'-'+nbn_main_id);

                            if(nbn_color=='Black'){
                                 $("#black_zones").append('<li>'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
                                 $("#black_loading").hide();
                              }else if(nbn_color=='Red'){
                                 $("#red_zones").append('<li>'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
                                 $("#red_loading").hide();
                              }else if(nbn_color=='#ffcc00'){
                                
                                 $("#amber_zones").append('<li>'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
                                 

                              }else{
                                $("#green_zones").append('<li>'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
                                $("#green_loading").hide();
                                $("#black_loading").hide();
                                $("#red_loading").hide();
                                $("#amber_loading").hide();
                              }

                          };
                          
                          data = {"type":"FeatureCollection",
                          "features":response};
                        
                        
                          map.data.addGeoJson(data);
                

                

                          map.data.setStyle(function(feature) {
                                  //currentPolyObj = feature;
                                  var color = feature.getProperty('fillColor');
                                  if (feature.getProperty('isColorful')) {
                                    color = feature.getProperty('color');
                                  }
                                  return ({
                                    fillColor: color,
                                    strokeColor: color,
                                    strokeWeight: 2
                                  });
                                
                                });
                          if(page==9){
                              $("#loading").hide();

                          }
                          map.data.addListener('click', function(event) {
                               
                               

                              ids = event.feature.getProperty('nbn_main_id');
                              //alert(ids);
                              event.feature.setProperty('isColorful', !event.feature.getProperty('isColorful'));
                               if(currentPolyObj[ids]== undefined){
                                 currentPolyObj[ids] = [];
                                 currentPolyObj[ids].push(event);
                               } else {
                                  currentPolyObj.remove(ids);
                               }

                                if(nbnArr.indexOf(event.feature.getProperty('nbn_main_id')) == -1 && event.feature.getProperty('isColorful')==true){
                                      nbnArr.push(event.feature.getProperty('nbn_main_id'));
                                  }else{
                                     
                                     nbnArr.remove(event.feature.getProperty('nbn_main_id'));
                                  }
                                  
                                  if(nbnArr.length>0){
                                      $("#over_map").show();
                                  }else{
                                      $("#over_map").hide();
                                  }
                                  
                            });
                          
                          
                      });
            
                            
                
            }


google.maps.event.addDomListener(window, 'load', initialize);
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
 
function sendAlert(currentPolyObj,color,formData){
        
        //console.log(currentPolyObj.length);
        for(var key in currentPolyObj){
          if(key!== "remove"){
          //console.log("key = " + key);
          //console.log(currentPolyObj[key]);
          //console.log(currentPolyObj[key][0]);
          var nbn_main_id=currentPolyObj[key][0].feature.getProperty('nbn_main_id');
          console.log(nbn_main_id);
          currentPolyObj[key][0].feature.setProperty('color',color);
            $.ajax({
                          url: "ajax/generate_alert.php?id="+nbn_main_id,
                          dataType: "json",
                          //async: false,
                          data:formData
                        }).done(function(response) {

                        //console.log(response);
                  });      

          }
        }


}

//Confirmation Alert of Popup
function confirm_alert()
{
    var status= confirm("Are you sure wish to send this alert!");
    if (status== true){
       var alert_type=$("#alert_type").val();
      var formData=$("#save_nbn_alert_form").serialize();
      console.log(formData);
      sendAlert(currentPolyObj,alert_type,formData);
      $('#popup_polygon_completeform').modal('toggle');
    }else{
        return false;
    }
}
//Confirmation Alert of Popup




</script>
<div id="loading">
  <img src="images/loading.gif" style="display:block;">
  </div>
<div id="googleMap"></div>
<div class="navigation" id="navigation-nbn" style="display:block;">
        <h3 style="text-align: center; font-size: 13px; padding-top: 10px;font-weight: bold; line-height: 10px" id="site_layer_title-nbn" class="site_layer_title">NBN ZONES</h3>
        <form class="filterform" action="#"><input type="text" class="filterinput clearable" size="15" id="input_nbn"></form>
         <ul class="list-group">
            <li class=""><span class="label label-black">Black Zones</span>
                  <ul id="black_zones">
                     <li id="black_loading">
                        <img src="images/loading.gif" style="display:block;">
                      </li>
                  </ul>

            </li>
            <li class=""><span class="label label-danger">Red Zones</span>
                   <ul id="red_zones">
                        <li id="red_loading">
                        <img src="images/loading.gif" style="display:block;">
                      </li>
                  </ul>
            </li>
            <li class=""><span class="label label-warning">Amber Zones</span>
                  <ul id="amber_zones">
                      <li id="amber_loading">
                        <img src="images/loading.gif" style="display:block;">
                      </li>
                  </ul>

            </li>
            <li class=""><span class="label label-success">Green Zones</span>
                  <ul id="green_zones">
                      <li id="green_loading">
                        <img src="images/loading.gif" style="display:block;">
                      </li>
                  </ul>

            </li>
          </ul>
       
        <ul id="navigation_site_nbn" style="padding: 5px;">
              
        </ul>
        
</div>
<div class="modal fade popup_polygon_completeform" id="popup_polygon_completeform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Alert Status</h4>

            </div>
            <form  method="POST" id="save_nbn_alert_form" class="save_polygon_form" onsubmit="return validateForm()">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="title" style="color:#000;">Subject *</label>
                        <input type="text" class="form-control" id="title_polygon" id="title_polygon" placeholder="Bush Fire Alert" name="title_nbn_polygon">
                    </div>
                    <div class="form-group">
                        <label for="title" style="color:#000;">Alert Status *</label>
                        <select name="alert_type" id="alert_type" class="form-control">
                            <option value="Select an alert">Select an alert</option>
                            <option value="Green">Green</option>
                            <option value="#ffcc00">Amber</option>
                            <option value="Red">Red</option>
                            <option value="Black">Black</option>
                        </select>
                    </div>
                    <div class="form-group">    
                        <label for="description" style="color:#000;">Alert Mesasge</label>
                        <textarea id="nbn_description_polygon" name="nbn_description_polygon"  class="description form-control" cols="30" rows="10"></textarea>
                    </div>  
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="save_polygon" value="save_polygon">
                        <button type="button" name="save_poly" value="save_poly" id="save_polygon_details" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="confirm_alert()" name="save_poly" value="save_poly" id="save_nbn_alert_details" class="btn btn-primary" disabled >Send</button>

                </div>
            </form>
        </div>
    </div>
</div>

   <div id="google_map">

   </div>

   <div id="over_map" style="display:none;">
         <button type="button" name="save_poly" value="save_poly" id="open_alert_form" class="btn btn-primary">Send Alert</button>
   </div>


<style type="text/css">html, body, #googleMap {
    width: 100%;
    height: 100%;
}

   #over_map { position: absolute;
  top: 10px;
  left: 25%;
  z-index: 5;
  
  padding: 5px;
  
  text-align: center;
  font-family: 'Roboto','sans-serif';
  line-height: 30px;
  padding-left: 10px;}
#loading { position: absolute;
  top: 225px;
  left: 50%;
  z-index: 5;
  
  padding: 5px;
  
  text-align: center;
  font-family: 'Roboto','sans-serif';
  line-height: 30px;
  padding-left: 10px;}

 #navigation-nbn{ display: block;
right: 38px;
position: absolute;
z-index: 7;
width: 200px;
border: 1px solid #999;
margin: 0 auto;
height: 400px;
top: 0;
background: none repeat scroll 0 0 rgba(255, 255, 255, 0.6);
overflow-y: scroll;
overflow-x: hidden;
height: 400px;
margin-top: 5px;
}
.label-black {
    background-color: #000000;
    font-size: 13px;
}
.label-danger{
  font-size: 13px !important;
}
.label-warning{
  font-size: 13px !important;
}
.label-success{
  font-size: 13px !important;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
   $( "#open_alert_form" ).click(function() {
        $("#popup_polygon_completeform").modal("show");
});
   $("#title_polygon").on("keyup",function()
  {
    //alert($("#nbn_description_polygon").val().length);
    if($(this).val().length !=0 || $("#nbn_description_polygon").val().length !=0)
    {
      $("#save_nbn_alert_details").attr("disabled",false);
    }
    else
    {
      $("#save_nbn_alert_details").attr("disabled",true);
    }
  });


  $("#nbn_description_polygon").on("keyup",function()
  {
    if($("#title_polygon").val().length !=0 || $(this).val().length !=0)
    {
      $("#save_nbn_alert_details").attr("disabled",false);
    }
    else 
    {
      $("#save_nbn_alert_details").attr("disabled",true);
    }
  });
  /* $("#save_nbn_alert_details").click(function(){
      var alert_type=$("#alert_type").val();
      var formData=$("#save_nbn_alert_form").serialize();
      console.log(formData);
      sendAlert(currentPolyObj,alert_type,formData);
      $('#popup_polygon_completeform').modal('toggle');
   })*/

   
    
})

//Auto Search On Nbn Zones
$(function(){

    $('#input_nbn').keyup(function(){
        
        var searchText = $(this).val();
        
        $('ul > li').each(function(){
            
            var currentLiText = $(this).text(),
                showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            
            $(this).toggle(showCurrentLi);
            $("#black_loading").hide();
            $("#red_loading").hide();
            $("#amber_loading").hide();
            $("#green_loading").hide();
            
        });     
    });

});
//Auto Search On Nbn Zones
</script>