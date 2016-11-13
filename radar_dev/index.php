<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title> <?php if(!empty($_GET['p'])) { echo ucfirst($_GET['p']); }?> Situation Room</title>
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Expires" content="-1">

	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
	<link rel="stylesheet" type="text/css" href="css/observation.css"/>
        <link rel="stylesheet" type="text/css" href="css/radar.css"/>
        <link rel="stylesheet" type="text/css" href="css/layer.css"/>
        <link rel="stylesheet" type="text/css" href="css/aus_post_code.css"/>        
       <!-- Google Map Js -->
        <?php  $whitelist = array('127.0.0.1','::1');
        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){ ?>
            <script src="https://maps.googleapis.com/maps/api/js?client=gme-earlywarningnetwork&sensor=false&v=3.exp"></script>
        <?php } else {  ?>
           <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false&v=3.exp"></script>
        <?php } ?>
        <!-- Google Map Js -->
	<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
       <!-- Js constant -->
        <script>
	   <?php include'includes/js_constant.php'; ?>
	</script>
        <!-- Js constant -->
	<!-- Custom js files -->
	<script type="text/javascript" src="js/jquery.arrays.js"></script>
	<script type="text/javascript" src="js/jquery.radar.js"></script>
        <script type="text/javascript" src="js/masterclusterer.js"></script>
	<script type="text/javascript" src="js/radar.js"></script>
        <script type="text/javascript" src="js/jquery.domready.js"></script>
	<script type="text/javascript" src="js/observation.js"></script>
	<script type="text/javascript" src="js/forecast_storm.js"></script>
	<script type="text/javascript" src="js/ground.js"></script>
	<script type="text/javascript" src="js/call_function.js"></script>
        <script type="text/javascript" src="js/forecast.js"></script>
	<script type="text/javascript" src="js/warning.js"></script>
        <script type="text/javascript" src="js/forcastsevere.js"></script>
	<script src="js/jquery.datetimepicker.js"></script>
        <script src="js/jquery-ui.js" type="text/javascript"></script>
	<script src="js/jquery.datetimepicker.js"></script>
	<script src="js/markerwithlabel.js"></script>
        <script src="js/jquery.cookie.js"></script>
	<script src="js/setting.js"></script>
        <script src="js/rainfall_gauges.js"></script>
        <!-- Aus Post code js -->
        <script src="js/aus_post_code/d3.v3.min.js"></script>
        <script src="js/aus_post_code/topojson.js" was="http://d3js.org/topojson.v1.min.js"></script>
        <script src="js/aus_post_code/aus_code.js"></script>
        <script>
	   <?php include'includes/layer_constant.php'; ?>
	</script>
        <!--[if IE 8]>
    
        <style>
            .room
            {
                background-color:#CC3300;
            }
        </style>
    <![endif]-->
    </head>
    <body>
        <!-- ercisson Layer -->
       <div class="navigation">
            <h3 style="text-align: center; font-size: 13px; padding-top: 10px;font-weight: bold; line-height: 10px">Ericsson Sites</h3>
            <ul id="navigation" style="padding: 5px;"></</ul>
       </div>
        <!-- Forecast severe legend -->
        <?php include('includes/forecast_savere_legend.php'); ?>
        
        <!-- Rainfall Gauges legend -->
        <?php include('includes/rainfall_gauges_legend.php'); ?>
        
        <!-- Dialog Box -->
        <div id="time_display" class="time_display"><div class="maptimeText" style="float: left;">Loading Radars..</div> <div class="timezone" style="float: left; display: none;">Loading Radars..</div></div>
         <!-- Radar Confirm Dialog Box -->
        <div id="radar_confirm_display" class="radar_confirm_display" style="display: none; left: 500px;"><div class="containertext" style="">Try changing the radar to &nbsp;&nbsp;<a class="hide_alerts" onclick="javascript:setMapRadarControl('256');"><b>256 km </b></a>&nbsp; or &nbsp;<a class="hide_alerts" onclick="javascript:setMapRadarControl('128');"><b>128 km </b></a> &nbsp;&nbsp;<a class="hide_alerts" onclick="javascript:hideConfirmbox();"><b>Cancel </b></a></div></div>
	<!-- Loader Div -->
    <div id="loader" style="display:none;"></div>
    <div id="loader_observation"></div>
	<!-- Map Div -->
	<div id="map-canvas" style="width:100%;"></div>
	<!-- Nav Button -->
	<div id="navmenu" class="hide" style="border:none"><a href="#" id="menu"><img src="images/menu.png" ></a></div>
	

	<!-- Menu Panel -->
	<?php include 'includes/control_panel.php'; ?>
	<!-- Menu Panel -->
    
</body>
</html>