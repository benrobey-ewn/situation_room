<?php include 'includes/config.php'; 
if(empty($_SESSION['user_id'])) {
	header('Location:login.php');
	die;
}
session_force_destroy();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> <?php if(!empty($_GET['p'])) { echo ucfirst($_GET['p']); }?> Situation Room</title>
	<meta http-equiv="Cache-control" content="no-cache">
	<link rel="icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
    <link rel="shortcut icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
	<link href='css/style.css' rel='stylesheet' type='text/css'>
	<link href='css/main_style.css' rel='stylesheet' type='text/css'>
	<link href='css/aus_post_code.css' rel='stylesheet' type='text/css'>
	<link href='css/jquery.datetimepicker.css' rel='stylesheet' type='text/css'>
	<link href='css/layer.css' rel='stylesheet' type='text/css'>
	<link href='css/multiple-select.css' rel='stylesheet' type='text/css'>
	<link href='css/observation.css' rel='stylesheet' type='text/css'>
	<link href='css/radar.css' rel='stylesheet' type='text/css'>

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

	<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
	 	
	 	<!-- Google Map Js -->
	    <?php  $whitelist = array('127.0.0.1','::1');
	    if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){ ?>
	        <script src="https://maps.googleapis.com/maps/api/js?client=gme-earlywarningnetwork&sensor=false&v=3.exp"></script>
	    <?php } else {  ?>
	       <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false&v=3.exp"></script>
	    <?php } ?>
	    <!-- Google Map Js -->
	
	<!-- Js constant -->
	<script>
		<?php include'includes/js_constant.php'; ?>
	</script>
	<!-- Js constant -->

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
	<script src="js/jquery-ui.js" type="text/javascript"></script>
	<script src="js/jquery.ui.touch-punch.min.js"></script>
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

	<!--  Multi Layer Js -->
	<script src="js/layers_data.js"></script>
	<script src="js/jquery.multiple.select.js"></script>

	<!--  cyclone Js -->
	<script type="text/javascript" src="js/geoxml3.js"></script>
	<script type="text/javascript" src="js/cyclone.js"></script>
	<script type="text/javascript" src="js/jquery.accordion.js"></script>
	<script>
		<?php include'includes/layer_constant.php'; ?>
	</script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link href="css/jquerysctipttop.css" rel="stylesheet" type="text/css">
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
		   <!-- <div class="sidelist_header"> -->
		   <div class="search_close" onclick="hide_sites();" id="close_site_<?php echo $row['layer_id'] ?>">X</div>
			<h3 style="text-align: center; font-size: 13px; padding-top: 10px;font-weight: bold; line-height: 10px;" id="site_layer_title<?php echo $row['layer_id'] ?>" class="site_layer_title"><?php echo $layer_type; ?></h3>
			<ul id="navigation_site_<?php echo $row['layer_id'] ?>" style="height: 100%; margin-top: 5px; overflow-y: scroll; padding: 5px;"></ul>
		</div>

		<?php } ?>

		<div class="navigation" id="navigation2" style="display:none;">
		   <div class="search_close" onclick="hide_public_sites();">X</div>
			<h3 style="text-align: center; font-size: 13px; padding-top: 10px;font-weight: bold; line-height: 10px" id="site_layer_title2" class="site_layer_title">AU Telephone Exchanges</h3>
			<ul id="navigation_site_2" style="padding: 5px;"></ul>
		</div>

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
		<div class="main_menu">
			<div class="responsive_menu"><a id="nav-toggle" href="#" class="nav_button">menu<!--img src="images/responsive_menu.png"><img src="images/responsive_menu_close.png"--></a></div>
			<div style="border: medium none; display: none;" class="hide" id="navmenu"><a id="menu" href="#"><img src="images/menu.png"></a></div>
			<div id="full_page_menu" class="menu">
				<!-- Menu Panel -->
				<?php include 'includes/control_panel.php'; ?>
				<!-- Menu Panel -->
			</div>
		</div>
		<!-- contact ewn start-->
		<?php include 'includes/contact_ewn.php'; ?>
		<!-- contact ewn end-->
		
		<!--legend button-->
		<div class="desktop_view_legend show_legend"><a class="desktop_view" href="#!">legend</a><a class="mobile_view" href="#!">legend</a></div>
		<div class="legend_container">
			<div class="example1">

				<!-- Forecast severe legend -->
				<?php include('includes/forecast_savere_legend.php'); ?>

				<!-- Rainfall Gauges legend -->
				<?php include('includes/rainfall_gauges_legend.php'); ?>

				<!-- River Gauges legend -->
				<?php include('includes/river_gauges_legend.php'); ?>

				<!-- outage Legend -->
				<?php include('includes/outage_legend.php'); ?>

				<!-- outage Legend -->
				<?php include('includes/warning_legend.php'); ?>
			</div>
		</div>
		<script type="text/javascript">
		
		$(function() {
			
			$('.example1').accordion({ multiOpen: true });

			$('.example2').accordion();

			$('.example3').accordion();
			$('.example4').accordion();
			
			//$( ".example1" ).draggable().resizable({ handles: 'n, s' });
			//$( ".example1" ).draggable();
			
			$( ".legend_container" ).draggable({
				appendTo: 'body',
                scroll: false,
                containment: "body"
            });

			$("#contact_ewn_container").draggable({
				appendTo: 'body',
                scroll: false,
                containment: "body"
            });
			//$( ".contact_ewn" ).draggable({containment: "parent"});
			
			$('.nav_button').click(function(){
				$('#menu').toggleClass('menu_show');
			});
			
			$('.desktop_view_legend').click(function(){
				$('.legend_container').toggle();
				if($('.desktop_view_legend').hasClass('hide_legend')) {
					$('.desktop_view_legend').removeClass('hide_legend');
					$('.desktop_view_legend').addClass('show_legend');
					
				} else {
				//$('.desktop_view_legend').toggleClass('show_legend');
				$('.desktop_view_legend').removeClass('show_legend');
				$('.desktop_view_legend').addClass('hide_legend');
			}
		});

			/*$('.panel-heading').click(function(){
				
				//console.log($(this));
				if($(this).hasClass('active')) {
					$(this).removeClass('active');
				} else {
					$('.panel-heading').removeClass('active');
					$(this).addClass('active');
				}
				//$(this).toggleClass('active');
				//$(this).toggleClass('active');
				})
				$('.panel-body ').hide('active');
				$('.panel-body ').removeClass('acc-open');*/
					$('.panel-heading').click(function(){
						if($(this).hasClass('active')) {
							$(this).removeClass('active');
							$(this).removeClass('unique_active');
							/*if($('.unique_active').length>0) {
							} else {
								$('.legend_container').removeClass('change_class_onclick');
							}*/
						} else {
							//$('.panel-heading').removeClass('active');
							$(this).addClass('active');
							$(this).addClass('unique_active');
							/*if($('.unique_active').length>0) {
								$('.legend_container').addClass('change_class_onclick');
							} */
						}
					
					});
			});
</script>
<script>
	document.querySelector('#nav-toggle').addEventListener('click', function () {
		this.classList.toggle('active');
	});

   /*$(document).ready(function() {
   	   $(window).on('resize', function(){
   	   	var obj = $(this);
   	   	if(obj.width() > 1024){
   	   		if($('#nav-toggle').hasClass("active")){
   	   			$('#nav-toggle').removeClass('active');
   	   		}
   	   	} else {
   	   		$('#nav-toggle').removeClass('active');
   	   	}
   	   });
   });*/
</script>

<!-- dummy image -->
<img src="images/ajax-loader-16.gif" width="16" height="16" style="display:none;" />
</body>
</html>
