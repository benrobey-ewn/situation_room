       /* Constant.js */
    <!--/ kml layers constants / -->
    var RT_NSW ;
    var RT_NT;
    var RT_QLD;
    var RT_SA;
    var RT_TAS;
    var RT_VIS;
    var RT_WA;
    var NTE_AREA;
    var NTE_POINT;
    var TS_BTD;
     var AM_BTD;
     var selected_layer_value_current;

     var marker1;
var marker2;
var marker3;
var marker4;
        
    var DT_BTD ;
    var DR_BTD;
     var FM_BTD ;
    var NETSDLayer1;
    var NMATLayer1;
    var NPSLayer1;
        
    var NPSLayer2;
    var NPSLayer3;
    var NPSLayer4;
    var NPSLayer5;
    var PortsLayer1;
    var NOMNOMLayer1;
     var NMPSLayer1;
     var Ericsson ;

     var  minLat = 1000;
var  minLong = 1000;

var  maxLat = -1000;
var  maxLong = -1000;

var latlongarray = [];
var polygonMarkerArray = [];
var filtermarkers = [];

     <!--     / kml layers constants / -->

     var mapMarkersArray = [];  //global variable used to manage map markers
     var warningMarkersArray = [];  //global variable used to manage warning map markers
     var forecastmapMarkersArray = []; // global array for forcast images
           
           var observation_timeout;
           
           var SITE_URL='<?php echo HOST; ?>';
    

      
     
       // Railway Track
       RT_NSW = new google.maps.KmlLayer('http://aeeris.com/dev/NSW.kmz',{
                      map: map,
                      preserveViewport: true
                  });
     RT_NT = new google.maps.KmlLayer('http://aeeris.com/dev/NT.kmz',{
                      map: map,
                      preserveViewport: true
                  });
     RT_QLD = new google.maps.KmlLayer('http://aeeris.com/dev/QLD.kmz',{
                      map: map,
                      preserveViewport: true
                  });
     RT_SA = new google.maps.KmlLayer('http://aeeris.com/dev/SA.kmz',{
                      map: map,
                      preserveViewport: true
                  });
     RT_TAS = new google.maps.KmlLayer('http://aeeris.com/dev/TAS.kmz',{
                      map: map,
                      preserveViewport: true
                  });
     RT_VIS = new google.maps.KmlLayer('http://aeeris.com/dev/VIC.kmz',{
                      
                      map: map,
                      preserveViewport: true
                  });
     RT_WA = new google.maps.KmlLayer('http://aeeris.com/dev/WA.kmz',{
                      map: map,
                      preserveViewport: true
                  });
    
    
     // Telphone Exchange
     NTE_AREA = new google.maps.KmlLayer('<?php echo NTE_AREA; ?>',{
                      map: map,
                      preserveViewport: true
                  });
     NTE_POINT = new google.maps.KmlLayer('<?php echo NTE_POINT; ?>',{
                      map: map,
                      preserveViewport: true
                  });
    
    // Broadcost Transmitter 
     TS_BTD = new google.maps.KmlLayer('<?php echo TS_BTD; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
     AM_BTD = new google.maps.KmlLayer('<?php echo AM_BTD; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
     DR_BTD = new google.maps.KmlLayer('<?php echo DR_BTD; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
     DT_BTD = new google.maps.KmlLayer('<?php echo DT_BTD; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
     FM_BTD = new google.maps.KmlLayer('<?php echo FM_BTD; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
    
    //AU Electricity Transmission Substations
     NETSDLayer1 = new google.maps.KmlLayer('<?php echo NETSDLayer1; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
    
    //AU Major Airport Terminals
     NMATLayer1 = new google.maps.KmlLayer('<?php echo NMATLayer1; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
    
    //AU Petrol Stations
     NPSLayer1=new google.maps.KmlLayer('<?php echo NPSLayer1; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
     NPSLayer2=new google.maps.KmlLayer('<?php echo NPSLayer2; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
     NPSLayer3=new google.maps.KmlLayer('<?php echo NPSLayer3; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
     NPSLayer4=new google.maps.KmlLayer('<?php echo NPSLayer4; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
     NPSLayer5=new google.maps.KmlLayer('<?php echo NPSLayer5; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
    
    //AU Ports
     PortsLayer1 = new google.maps.KmlLayer('<?php echo PortsLayer1; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
    
    //AU Operating Mines 
     NOMNOMLayer1 = new google.maps.KmlLayer('<?php echo NOMNOMLayer1; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
    
    //AU Major Power Stations 
     NMPSLayer1 = new google.maps.KmlLayer('<?php echo NMPSLayer1; ?>',{
                      
                      map: map,
                      preserveViewport: true
                  });
    
     // Ericsson layer
    Ericsson = new google.maps.KmlLayer('<?php echo Ericsson; ?>',{
                      map: map,
                      preserveViewport: true
                  });
                  
       <?php if(!empty($_GET['p'])) { 
           $city=$_GET['p'];
           if(!empty($capital_cities[$city])) { ?>
             myCenter=new google.maps.LatLng(<?php echo $capital_cities[$city][0] ?>, <?php echo $capital_cities[$city][1] ?>);
               zoom=<?php echo $capital_cities[$city][2] ?>;
               radarType = 256;
                $("#select_map_range").val('256');
           <?php }
          
       } else { ?>
       radarType = 512;
        $("#select_map_range").val('512');
       <?php } ?>
    
