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
   // console.log(data);
function initialize() {

             
   
    var mapProp = {
        center: x,
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    //var ids=[];
    var nbnArr=[];
    map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
     $.ajax({
              url: "ajax/get_nbn_layer_data.php",
              dataType: "json",
            }).done(function(response) {
               
              console.log(response);
              map.data.addGeoJson(response);
             
                map.data.setStyle(function(feature) {
                        //currentPolyObj = feature;
                        var color = feature.getProperty('fillColor');
                        if (feature.getProperty('isColorful')) {
                          color = feature.getProperty('color');
                        }
                        return /** @type {google.maps.Data.StyleOptions} */({
                          fillColor: color,
                          strokeColor: color,
                          strokeWeight: 2
                        });
                      
                      });
                 $("#loading").hide();
                map.data.addListener('click', function(event) {
                     //alert( event.feature.getProperty('nbn_main_id'));
                     console.log(event);

                    ids = event.feature.getProperty('nbn_main_id');
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
                            //alert(event.feature.getProperty('nbn_main_id'))
                           nbnArr.remove(event.feature.getProperty('nbn_main_id'));
                        }
                        //alert(currentPolyObj[ids].length);
                        if(nbnArr.length>0){
                            $("#over_map").show();
                        }else{
                            $("#over_map").hide();
                        }
                        //$("#alertid").val(ids);
                        //console.log(ids);
                        //console.log(currentPolyObj);

                  });
                 //alert(ids);
               /* map.data.setStyle(function (feature) {
                    var color = feature.getProperty('fillColor');
                    return {
                        fillColor: color,
                        strokeWeight: 1
                    };
                });*/ 
                
            });   
    
    
}
google.maps.event.addDomListener(window, 'load', initialize);

 
function sendAlert(currentPolyObj,color){
        
        //console.log(currentPolyObj.length);
        for(var key in currentPolyObj){
          if(key!== "remove"){
          //console.log("key = " + key);
          //console.log(currentPolyObj[key]);
          //console.log(currentPolyObj[key][0]);
          console.log(currentPolyObj[key][0].feature.getProperty('nbn_main_id'));
             currentPolyObj[key][0].feature.setProperty('color',color);
          }
        }


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

</script>
<div id="loading">
  <img src="images/loading.gif" style="display:block;">
  </div>
<div id="googleMap"></div>

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
                        <input type="text" class="form-control" id="title_polygon" id="title_polygon" placeholder="Bush Fire Alert">
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
                        <textarea id="nbn_description_polygon"  class="description form-control" cols="30" rows="10"></textarea>
                    </div>  
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="save_polygon" value="save_polygon">
                        <button type="button" name="save_poly" value="save_poly" id="save_polygon_details" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button type="button" name="save_poly" value="save_poly" id="save_nbn_alert_details" class="btn btn-primary" disabled >Save changes</button>

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
   $("#save_nbn_alert_details").click(function(){
      var alert_type=$("#alert_type").val();
      sendAlert(currentPolyObj,alert_type);
       $('#popup_polygon_completeform').modal('toggle');
   })

   /*$("#alert_type").change(function(){
      var alert_type=$("#alert_type").val();
      alert(alert_type);
      sendAlert(currentPolyObj,this.value);
    })*/
    
})
</script>