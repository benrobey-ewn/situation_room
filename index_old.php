<?php include 'includes/config.php'; 
if(empty($_SESSION['user_id'])) {
    header('Location:login.php');
    die;
}
session_force_destroy();
?>
<!DOCTYPE html>
<html>
    <head>
        <title> <?php if(!empty($_GET['p'])) { echo ucfirst($_GET['p']); }?> Situation Room</title>
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Expires" content="-1">
    <link rel="icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
        <link rel="shortcut icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
	<link rel="stylesheet" type="text/css" href="css/observation.css"/>
    <link rel="stylesheet" type="text/css" href="css/forecast.css"/>
    <link rel="stylesheet" type="text/css" href="css/radar.css"/>
    <link rel="stylesheet" type="text/css" href="css/layer.css"/>
    <link rel="stylesheet" type="text/css" href="css/aus_post_code.css"/> 
    <link rel="stylesheet"  type="text/css" href="css/multiple-select.css" /> 
    
    <!-- Javscript Disabled check -->   
   <noscript>
     Javascript is not enabled on browser and require this to be enabled to function properly.
     Here are the <a href="http://www.enable-javascript.com/" target="_blank">
     instructions how to enable JavaScript in your web browser</a>.
      <!--<meta http-equiv="refresh" content="0;url=http://www.enable-javascript.com/">-->
      <style type="text/css">
	    #loader, #loader_observation,#map-canvas,#time_display,#showcontact,#navmenu { display:none;}
      </style>
   </noscript>  
        
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false&v=3.exp&libraries=weather"></script>
	<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
        <!--[if lte IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.3/jquery.xdomainrequest.min.js"></script>
    <![endif]-->
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
    <script type="text/javascript" src="js/forecast.js"></script>
	<script type="text/javascript" src="js/forecast_storm.js"></script>
	<script type="text/javascript" src="js/ground.js"></script>
	<script type="text/javascript" src="js/call_function.js"></script>
    <script type="text/javascript" src="js/forecast.js"></script>
	<script type="text/javascript" src="js/warning.js"></script>
    <script type="text/javascript" src="js/forcastsevere.js"></script>
	<script type="text/javascript" src="js/gpats.js"></script>
	<script src="js/jquery.datetimepicker.js"></script>
    <script src="js/jquery-ui.js" type="text/javascript"></script>
	<script src="js/jquery.datetimepicker.js"></script>
	<script src="js/markerwithlabel.js"></script>
    <script src="js/jquery.cookie.js"></script>
	<script src="js/setting.js"></script>
    <script src="js/rainfall_gauges.js"></script>
    <script src="js/river_gauges.js"></script>
    <!-- Aus Post code js -->
    <script src="js/aus_post_code/d3.v3.min.js"></script>
    <script src="js/aus_post_code/topojson.js" was="http://d3js.org/topojson.v1.min.js"></script>
    <script src="js/aus_post_code/aus_code.js"></script>
    <script src="js/file_download.js"></script>
    
    <script src="js/layers_data.js"></script>
    <script src="js/jquery.multiple.select.js"></script>
    
    <script type="text/javascript" src="js/geoxml3.js"></script>
    <script type="text/javascript" src="js/cyclone.js"></script>

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
        <!-- Alert dialog -->
        <div id="custom_dialog" style="display: none;"><div id="closedialog"><a title="Close">x</a></div><div id="zipcode">dfhgfjghjgj</div></div>
        <!-- ercisson Layer -->
         <?php $selectSQL = "SELECT DISTINCT layer_type,layer_id FROM layer_datas where layer_id > 10 ORDER BY layer_data_id ASC ";
			  $result = mysql_query($selectSQL); 
			  while($row=mysql_fetch_array($result)) {
				   if(isset($layer_names[$row['layer_id']])) {
					  $layer_details= $layer_names[$row['layer_id']];
					  $layer_type=$layer_details[0];
   					}
	     ?>
         <div class="navigation" id="navigation<?php echo $row['layer_id'] ?>" style="display:none;">
            <h3 style="text-align: center; font-size: 13px; padding-top: 10px;font-weight: bold; line-height: 10px" id="site_layer_title<?php echo $row['layer_id'] ?>" class="site_layer_title"><?php echo $layer_type; ?></h3>
            <ul id="navigation_site_<?php echo $row['layer_id'] ?>" style="padding: 5px;"></ul>
       </div>
         
         <?php } ?>
         
         <div class="navigation" id="navigation2" style="display:none;">
            <h3 style="text-align: center; font-size: 13px; padding-top: 10px;font-weight: bold; line-height: 10px" id="site_layer_title2" class="site_layer_title">AU Telephone Exchanges</h3>
            <ul id="navigation_site_2" style="padding: 5px;"></ul>
       </div>
        
      <!-- <div class="navigation">
            <h3 style="text-align: center; font-size: 13px; padding-top: 10px;font-weight: bold; line-height: 10px" id="site_layer_title">Ericsson Sites</h3>
            <ul id="navigation" style="padding: 5px;"></</ul>
       </div>-->
        <!-- Forecast severe legend -->
        <?php include('includes/forecast_savere_legend.php'); ?>
        
        <!-- Rainfall Gauges legend -->
        <?php include('includes/rainfall_gauges_legend.php'); ?>

        <!-- River Gauges legend -->
        <?php include('includes/river_gauges_legend.php'); ?>
        
        <!-- outage Legend -->
         <?php include('includes/outage_legend.php'); ?>

        
        <!-- Dialog Box -->
        <div id="time_display" class="time_display"><div class="maptimeText" style="float: left;">Loading Radars..</div> <div class="timezone" style="float: left; display: none;">Loading Radars..</div></div>
         
        <!-- Radar Confirm Dialog Box -->
        <div id="radar_confirm_display" class="radar_confirm_display" style="display: none; left: 500px;"><div class="containertext" style="">Try changing the radar to &nbsp;&nbsp;<a class="hide_alerts" onclick="javascript:setMapRadarControl('256');"><b>256 km </b></a>&nbsp; or &nbsp;<a class="hide_alerts" onclick="javascript:setMapRadarControl('128');"><b>128 km </b></a> &nbsp;&nbsp;<a class="hide_alerts" onclick="javascript:hideConfirmbox();"><b>Cancel </b></a></div></div>
	
        <!-- Loader Div -->
        <div id="loader" style="display:none;"></div>
        <div id="loader_observation"></div>
        <div id="loader_layer_type" style="display:none;"></div>
    
        <!-- Map Div -->
        <div id="map-canvas" style="width:100%;"></div>
        
        <!-- Nav Button -->
        <div id="navmenu" class="hide" style="border:none"><a href="#" id="menu"><img src="images/menu.png" ></a></div>
	
		<!-- Lightning Info -->
		<div id="info-box" style="background-color: white; border: 1px solid grey; bottom: 300px; height: auto; padding: 10px; position: absolute; left: 7px; display: none;"></div>

        <!-- Menu Panel -->
        <?php include 'includes/control_panel.php'; ?>
        <!-- Menu Panel -->
    
    <!-- dummy image -->
   <img src="images/ajax-loader-16.gif" width="16" height="16" style="display:none;" />
    
</body>
</html>