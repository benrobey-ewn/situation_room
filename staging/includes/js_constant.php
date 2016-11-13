/* Constant.js */
var map;
var bermudaTriangle;
var weatherLayer;
var width = '900px;';
var width = '400px;';

var map_observation = 1;
var radarType;
var myCenter=new google.maps.LatLng(-25.274398,133.775136);
var zoom = 5;

var mapMarkersArray = [];  //global variable used to manage map markers
var warningMarkersArray = [];  //global variable used to manage warning map markers
var forecastmapMarkersArray = []; // global array for forcast images
var observation_timeout;
var SITE_URL = '<?php echo HOST; ?>';

var rainfall_timeout;
var river_guage_timeout;

var show_asset_click=false;

if (!window.console) {var console = {};}
if (!console.log) {console.log = function() {};}

var warningobjectMarkersArray = [];
var weather_options = [];

var forecastwarningobjectMarkersArray = [];
var forecast_weather_options = [];

var $forecast_checkes = $('input:checkbox[name="forecast_warning_option[]"]');

	<?php if(!empty($_GET['p']) || $_SESSION['username']=='linfox' || $_SESSION['username']=='linfoxnsw' || $_SESSION['username']=='linfoxqld' || $_SESSION['username']=='linfoxvic' || $_SESSION['username']=='linfoxnt' || $_SESSION['username']=='linfoxsa' || $_SESSION['username']=='linfoxtas' || $_SESSION['username']=='bechtel') { 
		if($_SESSION['username']=='linfox') {
		$city='perth';
	} else if($_SESSION['username']=='bechtel') {
		$city = 'bechtel';
	} else  {
		$city = $_GET['p'];
	}

	if($_SESSION['username']=='linfoxnsw'){
		$city='sydney';
	} else if($_SESSION['username']=='linfoxqld') {
		$city = "brisbane";
	} else if($_SESSION['username']=='linfoxvic') {
		$city = "melbourne";
	} else if($_SESSION['username']=='linfoxnt') {
		$city = "melbourne";
	} else if($_SESSION['username']=='linfoxsa') {
		$city = "darwin";
	} else if($_SESSION['username']=='linfoxtas') {
		$city = "hobart";
	}
	if (!empty($capital_cities[$city])) { ?>
		map_observation = 2;
		myCenter = new google.maps.LatLng(<?php echo $capital_cities[$city][0] ?>, <?php echo $capital_cities[$city][1] ?>);
		zoom = <?php echo $capital_cities[$city][2] ?>;
	<?php }
	}  ?>

var LOGIN_USER_NAME = '<?php echo $_SERVER['PHP_AUTH_USER']?$_SERVER['PHP_AUTH_USER']:''; ?>';
var client_layers = <?php echo '[' . implode(', ', $client_layers) . ']' ?>;