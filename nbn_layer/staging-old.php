<?php include 'includes/config.php';?>
   
<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
<script src="js/libs/bootstrap.min.js?v=<?php echo SR_VER ?>"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false&.js"></script>
<script>


var x = new google.maps.LatLng(-27.978636, 130.396606);
var map;
var currentPolyObj = [];
   // console.log(data);
function initialize() {

     $("#loading").show();        

    var mapProp = {
        center: x,
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    //var ids=[];
    map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
     
     $.ajax({
              url: "ajax/get_nbn_layer_data.php",
              dataType: "json",
            }).done(function(response) {
               
              console.log(response);
              $("#loading").hide();
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

                     /* if(ids.indexOf(event.feature.getProperty('nbn_main_id')) == -1 && event.feature.getProperty('isColorful')==true){
                            ids.push(event.feature.getProperty('nbn_main_id'));
                        }else{
                            //alert(event.feature.getProperty('nbn_main_id'))
                           ids.remove(event.feature.getProperty('nbn_main_id'));
                        }*/
                        if(ids.length>0){
                            //$("#over_map").show();
                        }else{
                            //$("#over_map").hide();
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

<div id="googleMap">


</div>
  <div id="loading">
  <img src="images/loading.gif" style="display:block;">
  </div>



   

   


<style type="text/css">html, body, #googleMap {
    width: 100%;
    height: 100%;
}
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
