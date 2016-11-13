<?php include 'includes/config.php'; 
if (empty($_SESSION['user_id'])) {
	header('Location:login.php');
	die;
}
session_force_destroy();
?>
<html>
	<head>
		<title><?php if(!empty($_GET['p'])) { echo ucfirst($_GET['p']) . " | "; }?>Situation Room</title>
		<meta http-equiv="Cache-control" content="no-cache">
		<meta http-equiv="Expires" content="-1">
		<link rel="icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
		<link rel="shortcut icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<link href="css/bootstrap3.min.css" rel="stylesheet">
		<link href="css/bootstrap3-responsive.min.css" rel="stylesheet">
		<link href="css/lib/fonts.googleapis.css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
		<link href="css/lib/4.4.0-css-font-awesome.min.css" rel="stylesheet">
		<link href="css/ui-lightness/ui-lightness.css" rel="stylesheet">
		<link href="css/base-admin-3.css" rel="stylesheet">
		<link href="css/base-admin-3-responsive.css" rel="stylesheet">
		<link href="css/pages/dashboard.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">
		<link href="css/extra.css" rel="stylesheet">
		<link href="css/layer.css" rel="stylesheet">
		<link href="css/radar.css" rel="stylesheet">
		<link href="css/observation.css" rel="stylesheet">
		<link href="css/aus_post_code.css" rel="stylesheet">
		<link href="css/forecast.css" rel="stylesheet">
		<link href="css/lib/style.css" rel="stylesheet" />

		<!-- JavScript Disabled check -->
		<noscript>
			JavaScript is not enabled on your browser and is required for this page to function properly. <br />
			<a href="http://www.enable-javascript.com/" target="_blank"> Click here for instructions on how to enable JavaScript.</a>
			<!--<meta http-equiv="refresh" content="0;url=http://www.enable-javascript.com/">-->
			<!--<meta http-equiv="refresh" content="0;url=http://www.enable-javascript.com/">-->
			<style type="text/css">
				#loader, #loader_observation, #map-canvas, #time_display, #showcontact, #navmenu, #nav-container, #Popcontainer{ display:none;}
			</style>
		</noscript>
	</head>
	<body>

		<?php $selectSQL = "SELECT DISTINCT layer_type, layer_id FROM layer_datas where layer_id > 10 ORDER BY layer_data_id ASC ";
			$result = mysql_query($selectSQL); 
			while ($row = mysql_fetch_array($result)) {
				if (isset($layer_names[$row['layer_id']])) {
					$layer_details = $layer_names[$row['layer_id']];
					$layer_type = $layer_details[0];
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
		<div class="container-fluid fullheight screen-container">
			<div id="width-max" class="row fullheight screen-row">
				<div id="map-container" class="col-md-9 fullheight map-container">
					<div id="legend-container" class="container legend-container">
						<div id="legend-container-content" class="legend-container-content">
							<!-- Rainfall Gauges legend -->
							<?php include('includes/rainfall_gauges_legend.php'); ?>

							<!-- River Gauges legend -->
							<?php include('includes/river_gauges_legend.php'); ?>

							<!-- Warning Legend -->
							<?php include('includes/warning_legend.php'); ?>

							<!-- Forecast Legend -->
							<?php include('includes/forecast_savere_legend.php'); ?>
							<p class="legend-container-text centered-text">Drop to original position</p>
						</div>
					</div>
					<div id="map-canvas" class="fullheight"> </div>
					<div id="info-box" style="background-color: white; border: 1px solid grey; bottom: 300px; height: auto; padding: 10px; position: absolute; left: 7px; display: none;"></div>
					<div class="radar-info">
						<div class="radar-bar">
							<img class="radar-bar-image" src="images/radarbar.png"/>
						</div>
					</div>
				</div><!-- /map-container -->
				<div>
					<div class="expand-controls">
						<!-- <div class="expand-div collapse-navigation"> -->
						<div>
							<button type="button" id="nav-small" class="btn btn-success">Expand Navigation</button>
						</div>
					</div>
				</div>
				<div class="control-panel">
					<?php include 'includes/new_control_panel.php'; ?>
				</div>
			</div><!-- /screen-row -->
		</div><!-- /screen-containter -->

		<!-- Google Map Js -->
		<?php $whitelist = array('127.0.0.1','::1');
		if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) { ?>
			<script src="https://maps.googleapis.com/maps/api/js?client=gme-earlywarningnetwork&sensor=false&v=3.exp"></script>
		<?php } else { ?>
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false&v=3.exp"></script>
		<?php } ?>
		<!-- /Google Map Js -->

		<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
		<script src="js/libs/bootstrap.min.js"></script>

		<!--[if lte IE 9]>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.3/jquery.xdomainrequest.min.js"></script>
		<![endif]-->

		<!-- Js constant -->
		<script><?php include'includes/js_constant.php'; ?></script>
		<!-- /Js constant -->
		
		<!-- NODE/SOCKET.IO  Start-->
		<!--<script src="<?php //echo HOST ?>:3210/socket.io/socket.io.js"></script>
		<script src="js/node_jscript.js"></script>-->
		<!-- NODE/SOCKET.IO End-->

		<!-- Custom js files -->
		<script src="js/jquery.arrays.js"></script>
		<script src="js/jquery.radar.js"></script>
		<script src="js/masterclusterer.js"></script>
		<script src="js/radar.js"></script>
		<script src="js/models.js"></script>
		<script src="js/satellite.js"></script>
		<script src="js/jquery.domready.js"></script>
		<script src="js/observation.js"></script>
		<script src="js/forecast.js"></script>
		<script src="js/ground.js"></script>
		<script src="js/call_function.js"></script>
		<script src="js/forecast.js"></script>
		<script src="js/warning.js"></script>
		<script src="js/forcastsevere.js"></script>
		<script src="js/lightning.js"></script> <!--lightning script-->
		<script src="js/jquery.datetimepicker.js"></script>
		<script src="js/jquery-ui.js"></script>
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

		<!--  Multi Layer Js -->
		<script src="js/layers_data.js"></script>
		<script src="js/jquery.multiple.select.js"></script>

		<!--  cyclone Js -->
		<script type="text/javascript" src="js/geoxml3.js"></script>
		<script type="text/javascript" src="js/cyclone.js"></script>

		<!--  Libs -->
		<script src="js/Application.js"></script>

		<!-- include -->
		<script><?php include'includes/layer_constant.php'; ?></script>
		<script><?php include'js/customjs.php'; ?></script>

		<script src="js/custom.js"></script>

		<script src="js/topojsonLayers.js"></script>
		<!-- Analytics -->
		<?php include_once("includes/analyticstracking.php") ?>
	</body>
</html>