        /* Constant.js */
        var map;
        var bermudaTriangle;
        var weatherLayer;
        var width='900px;';
        var width='400px;';
        
        var map_observation=1;
        var radarType;
        var myCenter=new google.maps.LatLng(-25.274398,133.775136);
        var zoom=5;
        
        var mapMarkersArray = [];  //global variable used to manage map markers
        var warningMarkersArray = [];  //global variable used to manage warning map markers
        var forecastmapMarkersArray = []; // global array for forcast images
        var observation_timeout;
        var SITE_URL='<?php echo HOST; ?>';
        
        var rainfall_timeout;
        
        if (!window.console) {var console = {};}
        if (!console.log) {console.log = function() {};}
        
         var warningobjectMarkersArray = [];
          var weather_options = [];
          
          var forecastwarningobjectMarkersArray = [];
        var forecast_weather_options = [];
        
        var $forecast_checkes = $('input:checkbox[name="forecast_warning_option[]"]');
        
        <?php if(!empty($_GET['p'])) { 
           $city=$_GET['p'];
           if(!empty($capital_cities[$city])) { ?>
             map_observation=2;
             myCenter=new google.maps.LatLng(<?php echo $capital_cities[$city][0] ?>, <?php echo $capital_cities[$city][1] ?>);
               zoom=<?php echo $capital_cities[$city][2] ?>;
               radarType = 256;
               $("#select_map_range").val('256');
        <?php }
        } else { ?>
            radarType = 256;
            $("#select_map_range").val('512');
        <?php } ?>
