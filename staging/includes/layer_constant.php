/* Constant.js */
<!--kml layers constants-->
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
var MDSWA ;
var waternsw ;
var GWA ;
var BCC;

var  minLat = 1000;
var  minLong = 1000;
var  maxLat = -1000;
var  maxLong = -1000;

var latlongarray = [];
var polygonMarkerArray = [];
var filtermarkers = [];
<!--/kml layers constants-->

var mapMarkersArray = [];  //global variable used to manage map markers
var warningMarkersArray = [];  //global variable used to manage warning map markers
var forecastmapMarkersArray = []; // global array for forcast images

var observation_timeout;

var SITE_URL='<?php echo HOST; ?>';

// Railway Track

RT_NSW1 = new google.maps.KmlLayer('<?php echo RT_NSW1; ?>',{
map: map,
preserveViewport: true
});
RT_NSW2 = new google.maps.KmlLayer('<?php echo RT_NSW2; ?>',{
map: map,
preserveViewport: true
});

RT_NSW3 = new google.maps.KmlLayer('<?php echo RT_NSW3; ?>',{
map: map,
preserveViewport: true
});


RT_QLD1 = new google.maps.KmlLayer('<?php echo RT_QLD1; ?>',{
map: map,
preserveViewport: true
});
RT_QLD2 = new google.maps.KmlLayer('<?php echo RT_QLD2; ?>',{
map: map,
preserveViewport: true
});

RT_QLD3 = new google.maps.KmlLayer('<?php echo RT_QLD3; ?>',{
map: map,
preserveViewport: true
});
RT_QLD4 = new google.maps.KmlLayer('<?php echo RT_QLD4; ?>',{
map: map,
preserveViewport: true
});

RT_QLD5 = new google.maps.KmlLayer('<?php echo RT_QLD5; ?>',{
map: map,
preserveViewport: true
});
RT_QLD6 = new google.maps.KmlLayer('<?php echo RT_QLD6; ?>',{
map: map,
preserveViewport: true
});

RT_QLD7 = new google.maps.KmlLayer('<?php echo RT_QLD7; ?>',{
map: map,
preserveViewport: true
});
RT_QLD8 = new google.maps.KmlLayer('<?php echo RT_QLD8; ?>',{
map: map,
preserveViewport: true
});

RT_WA1 = new google.maps.KmlLayer('<?php echo RT_WA1; ?>',{
map: map,
preserveViewport: true
});
RT_WA2 = new google.maps.KmlLayer('<?php echo RT_WA2; ?>',{
map: map,
preserveViewport: true
});


RT_VIC1 = new google.maps.KmlLayer('<?php echo RT_VIC1; ?>',{
map: map,
preserveViewport: true
});

RT_VIC2 = new google.maps.KmlLayer('<?php echo RT_VIC2; ?>',{
map: map,
preserveViewport: true
});
RT_VIC3 = new google.maps.KmlLayer('<?php echo RT_VIC3; ?>',{
map: map,
preserveViewport: true
});
RT_VIC4 = new google.maps.KmlLayer('<?php echo RT_VIC4; ?>',{
map: map,
preserveViewport: true
});
RT_VIC5 = new google.maps.KmlLayer('<?php echo RT_VIC5; ?>',{
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

// MDSWA layer
MDSWA = new google.maps.KmlLayer('<?php echo MDSWA; ?>',{
map: map,
preserveViewport: true
});

//waternsw layer
waternsw = new google.maps.KmlLayer('<?php echo WATERNSW; ?>',{
map: map,
preserveViewport: true
});

//GWA layer
GWA = new google.maps.KmlLayer('<?php echo GWA;?>',{
map: map,
preserveViewport: true
});

var OUTAGE_UNPLANNED = new google.maps.KmlLayer('<?php echo OUTAGE_UNPLANNED;?>',{
map: map,
preserveViewport: true
});

var PUBLIC_OUTAGES = new google.maps.KmlLayer('<?php echo PUBLIC_OUTAGES;?>',{
map: map,
preserveViewport: true
});

var PUBLIC_OUTAGES_FUTURE = new google.maps.KmlLayer('<?php echo PUBLIC_OUTAGES_FUTURE;?>',{
map: map,
preserveViewport: true
});

// BCC
BCC = new google.maps.KmlLayer('<?php echo BCC;?>',{
map: map,
preserveViewport: true
});

// ARTC
var ARTC = new google.maps.KmlLayer('<?php echo ARTC;?>',{
map: map,
preserveViewport: true
});

// HUME HIghway
/*var HUME_HIGHWAY = new google.maps.KmlLayer('<?php echo HUME_HIGHWAY;?>',{
map: map,
preserveViewport: true
});
*/

HUME_HIGHWAY = '<?php echo HUME_HIGHWAY;?>';

<?php if (!empty($_GET['p'])) { 
	$city = $_GET['p'];
	if (!empty($capital_cities[$city])) { ?>
	myCenter = new google.maps.LatLng(<?php echo $capital_cities[$city][0] ?>, <?php echo $capital_cities[$city][1] ?>);
	zoom = <?php echo $capital_cities[$city][2] ?>;
	<?php }
} ?>

// outage layer constant
var OUTAGE_UNPLANNED_URL = '<?php echo OUTAGE_UNPLANNED;?>';
var PUBLIC_OUTAGES_URL = '<?php echo PUBLIC_OUTAGES;?>';
var PUBLIC_OUTAGES_FUTURE_URL = '<?php echo PUBLIC_OUTAGES_FUTURE;?>';

var datach1 = new google.maps.Data();
var chainageurl = '<?php echo GWA_CHAINAGE;?>';
var chaininfowindow = new google.maps.InfoWindow({ content:'<div style="width:200px; height:100px">Some text</div>'});

var datach2 = new google.maps.Data();
var artc_chainageurl = '<?php echo ARTC_CHAINAGE;?>';
var chaininfowindowartc = new google.maps.InfoWindow({ content:'<div style="width:200px; height:100px">Some text</div>'});