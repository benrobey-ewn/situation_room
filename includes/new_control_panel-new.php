<?php if($_SESSION['username']=='Media' || $_SESSION['username']=='admin') {?>
<?php } ?>
<div id="showcontantdetails" style="display: none;">
	<div id="closecontact">
		<a title="Close">x</a>
	</div>
	<h3>Contact us</h3>
	<ul style="margin-top: 0px !important; padding:0px;">
		<li>
			<ul style="margin-top: 0px !important; padding:0px; ">
				<li>
					<p class="heading">Support: <a href="mailto:support@ewn.com.au">support@ewn.com.au</a></p>
				</li>
				<li>
					<p>If you have any enquiries or suggestions, please <a href="http://www.ewn.com.au/contact-us.aspx">contact us.</a></p>
				</li>
			</ul>
		</li>
	</ul>
</div>
<div class="col-md-3" id="nav-container">
	<div class="row Popcontainer" id="Popcontainer">
		<div class="col-md-12 padding-helper">
			<div class="row heading-sr">
				<div class="col-md-12 padding-helper">
					<div class="row logo-company">
						<div class="col-md-12">
							<img src="images/logo-combo.png" alt="logo" />
						</div>
					</div>
					<div class="row refresh-log">
						<div class="col-sm-4 col-md-4 col-lg-4">
							<button type="button" class="btn collapse-navigation">Hide Nav</button>
						</div>
						<div class="col-sm-4 col-md-4 col-lg-4">
							<a href="logout.php" class="btn">
								<i class="icon-signout"></i> Logout
							</a>
						</div>
						<div class="col-sm-4 col-md-4 col-lg-4">
							<div class="showcontact" id="showcontact">
								<button type="button" class="btn">Contact</button>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /header-->
			<div class="row loop-controls">
				<div class="col-md-12 header-collapse  border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="loop_controls" name="loop_controls" class="header-checkbox cmn-toggle cmn-toggle-round">
							<label for="loop_controls" class="label-heading"></label>
						</div>
					<div class="heading-name">
						<p class="selectable-heading">Global Loop Controls</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="col-md-12 content-collapse" id="radars_box">
					<div class="row ">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Global Loop Controls.
								</p>
							</div>
						</div>
					</div>
					<div class="row global-loop-controls">
						<div class="col-md-2">
							<a href="#" class="loop-play" id="play" title="Play" onclick="">
								<i class="fa fa-play"></i>
							</a>
						</div>
						<div class="col-md-2">
							<a href="#" class="loop-pause" id="pause" title="Pause" onclick="">
								<i class="fa fa-pause"></i>
							</a>
						</div>
						<div class="col-md-2">
							<a href="#" class="loop-reverse" id="reverse" title="Reverse" onclick="">
								<i class="fa fa-backward"></i>
							</a>
						</div>
						<div class="col-md-2">
							<a href="#" class="loop-forward" id="forward" title="Forward" onclick="">
								<i class="fa fa-forward"></i>
							</a>
						</div>
						<div class="col-md-2">
							<a href="#" class="loop-start" id="start" title="Start" onclick="">
								<i class="fa fa-step-backward"></i>
							</a>
						</div>
						<div class="col-md-2">
							<a href="#" class="loop-end" id="end" title="End" onclick="">
								<i class="fa fa-step-forward"></i>
							</a>
						</div>
					</div>
					<!-- TODO ADDED LATER
					<div class="row global-loop-step container-btm-mrgn">
						<div class="col-md-4">
							<label for="loop-step">Loop Step</label>
						</div>
						<div class="col-md-8">
							<select name="loop-step" id="loop-step">
								<option value="10">10%</option>
								<option value="20">20%</option>
								<option value="30">30%</option>
								<option value="40">40%</option>
								<option value="50">50%</option>
								<option value="60">60%</option>
								<option value="70">70%</option>
								<option value="80">80%</option>
								<option value="90">90%</option>
								<option value="100" selected="">100%</option>
							</select>
						</div>
					</div>
					-->
					<div class="row global-loop-speed container-btm-mrgn">
						<div class="col-md-4">
							<label for="loop-speed">Loop Speed</label>
						</div>
						<div class="col-md-8">
							<select name="loop-speed" id="loop-speed">
								<option value="0">No Loop</option>
								<option value="1">Slowest</option>
								<option value="2">Slow</option>
								<option value="3" selected="">Normal</option>
								<option value="4">Fast</option>
								<option value="5">Fastest</option>
							</select>
						</div>
					</div>
					<div class="row global-loop-length container-btm-mrgn">
						<div class="col-md-4">
							<label for="loop-length">Loop Length</label>
						</div>
						<div class="col-md-8">
							<select name="loop-length" id="loop-length">
								<option value="15">15 Minutes</option>
								<option value="30" selected="">30 Minutes</option>
								<option value="45">45 Minutes</option>
								<option value="60">60 Minutes</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<!-- /Global Loop controls-->
			<div class="row radar">
				<div class="col-md-12 header-collapse  border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="radar_display_images" name="radar_display_images" class="header-checkbox static_radars cmn-toggle cmn-toggle-round">
							<label for="radar_display_images" class="label-heading"></label>
						</div>
					<div class="heading-name">
						<p class="selectable-heading">Radar</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="col-md-12 content-collapse" id="radars_box">
					<div class="row ">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Weather radar as sourced from the Bureau of Meteorology.
									Options include:
									Show or hide radar overlay.
									Loop or static radar image controls including scale and speed.
									Click radar site marker for a separate loop of that location.
									Opacity: Controls how opaque the reflectivity will appear.
									Cell Info (coming soon).
								</p>
							</div>
						</div>
					</div>
					<div class="row radar-weather-sites container-btm-mrgn">
						<div class="col-md-6 centered-div">
							<input type="checkbox" class="regular-checkbox wx_radar_sites" id="wx_radar_sites">
							<label for="wx_radar_sites">Weather Radar Sites</label>
						</div>
						<div class="col-md-6">
							<button type="button" value="Radar" class="btn btn-success btn-sm theyellow" onclick="javascript:newPopup('<?php echo $externalRadarLink ?>','512','600');" ><i class="icon-external-link"></i> Pop Out</button>
						</div>
					</div>
					<div class="row cell-info container-btm-mrgn">

						<div class="col-md-2">
							<label for="radar_opacity">Opacity</label>
						</div>
							<div class="col-md-8">
								<select name="radar_opacity" id="radar_opacity" class="hidden-desktop">
								<option value="10">10%</option>
								<option value="20">20%</option>
								<option value="30">30%</option>
								<option value="40">40%</option>
								<option value="50">50%</option>
								<option value="60">60%</option>
								<option value="70">70%</option>
								<option value="80">80%</option>
								<option value="90">90%</option>
								<option value="100" selected="">100%</option>
							</select>
						</div>
					</div>
					<div class="row radar-loop-controls">
						<div class="col-md-3">
							<input type="checkbox" id="optRadar" name="optRadar" class="regular-checkbox loop_radars">
							<label for="optRadar">Radar Loop</label>
						</div>
						<div class="col-md-5">
							<div class="row Controls radar-controls">
								<div class="col-md-3">
									<a href="#" class="play radar_play" id="play" title="Play" onclick="">Play</a>
								</div>
								<div class="col-md-3">
									<a href="#" class="pause radar_pause" id="pause" title="Pause" onclick="">Pause</a>
								</div>
								<div class="col-md-3">
									<a href="#" class="reverse radar_reverse" id="prev" title="Previous" onclick="javascript:EWNMapsRadarPrevFrame();">Reverse</a>
								</div>
								<div class="col-md-3">
									<a href="#" class="forward radar_forward" id="next" title="Forward" onclick="javascript:EWNMapsRadarNextFrame(true);">Forward</a>
								</div>
							</div>
						</div>
						<!-- NOT INCLUDED IN THIS RELEASE
						<div class="col-md-3">
							<select name="drop" id="loop_speed_select" onchange="javascript:change_speed_maintain_loop(this.value);">
								<option value="1500">Slow</option>
								<option value="700" selected="selected">Normal</option>
								<option value="250">Fast</option>
							</select>
						</div>
						-->
					</div>
				</div>
			</div><!-- /radar-->
			<!--lightning-->
			<div class="row lightning">
				<div class="col-md-12 header-collapse  border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-1-4" class="header-checkbox static_radars cmn-toggle cmn-toggle-round">
							<label for="checkbox-1-4" class="label-heading"></label>
						</div>
					<div class="heading-name">
						<p class="selectable-heading">Lightning</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="col-md-12 content-collapse">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									The lightning overlay shows the past hour's lightning strikes, with white markers most recent to red the oldest.<br>
									A dot marker indicates a cloud to cloud (CC) lightning stroke.<br>
									A cross marker indicates a positive cloud to ground (CG) lightning stroke.<br>
									A triangle marker indicates a negative cloud to ground (CG) lightning stroke.<br>
									<!--Use the loop control to animate the display.-->
								</p>
							</div>
						</div>
					</div>
					<!--<div class="row">
						<div class="col-md-12">
							<div class="col-md-8">
								<label for="lightningshowbounds">Show Trial Area</label>
								<input type="checkbox" id="lightningshowbounds">
							</div>
						</div>
					</div>-->
					<div class="row">
						<div class="col-md-2">
							<p>Minutes: </p>
						</div>
						<div class="col-md-12">
							<div class="Controls">
								<div class="col-md-8">
									<select name="lightninglength" id="lightninglength" onchange="javascript:changeLightningLength(this.value);">
										<option value="300">5 Min</option>
										<option value="600">10 Min</option>
										<option value="900">15 Min</option>
										<option value="1200">20 Min</option>
										<option value="1500">25 Min</option>
										<option value="1800" selected="selected">30 Min</option>
										<option value="3600">60 Min</option>
										<option value="7200">120 Min</option>
										<option value="10800">180 Min</option>
										<option value="14400">240 Min</option>
										<option value="18000">300 Min</option>
										<!--<option value="43200">**12 HOURS**</option>-->
									</select>
								</div>
							</div>
						</div>
					</div>
					<!--<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>* Please note selecting one of these options will take a while to produce results due to large volume of data. Suggest turning off all other layers before selecting one of these options.</p>
							</div>
						</div>
					</div>-->
					<!--later release
					<div class="row lightning-loop-controls">
						<div class="col-md-4 loop-control-align">
							<input type="checkbox" id="checkbox-1-5" class="regular-checkbox">
							<label for="checkbox-1-5">Lightning Loop</label>
						</div>
						<div class="col-md-4">
							<div class="row Controls lightning-controls">
								<div class="col-md-3">
									<a class="play" href="index.php#" title="Play">play</a>
								</div>
								<div class="col-md-3">
									<a class="pause" href="index.php#" title="Pause">pause</a>
								</div>
								<div class="col-md-3">
									<a class="reverse" href="index.php#" title="Reverse">reverse</a>
								</div>
								<div class="col-md-3">
									<a class="forward" href="index.php#" title="Forward">forward</a>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<select name="drop" class="drop">
								<option value="100">Slow</option>
								<option value="125">Meduim</option>
								<option value="128">Fast</option>
							</select>
						</div>
					</div>
					-->
				</div>
			</div><!-- /lightning-->
			<div class="row observations">
				<div class="col-md-12 header-collapse  border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-1-6" class="header-checkbox regular-checkbox observation_checkbox cmn-toggle cmn-toggle-round">
							<label for="checkbox-1-6" class="label-heading"></label>
						</div>
					<div class="heading-name">
						<p class="selectable-heading">Observations</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="col-md-12 content-collapse" id="observations_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Displays the current temperature of cities and regional centres.
									The number of sites shown depends on the map zoom.
									Click a temperature marker to show all observations for the site, including rainfall, humidity and wind.
									Cities only and ALL checkboxes restrict the number of sites shown.
								</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<input type="checkbox" id="observations-capitals" class="regular-checkbox observation_option1">
							<label for="observations-capitals">Capitals only</label>
						</div>
						<div class="col-md-4">
							<input type="checkbox" id="observations-cities" class="regular-checkbox observation_option2">
							<label for="observations-cities">Cities only</label>
						</div>
						<div class="col-md-4">
							<input type="checkbox" id="observations-all" class="regular-checkbox observation_option3">
							<label for="observations-all">All</label>
						</div>
					</div>
				</div>
			</div><!-- /observations-->
			<div class="row rainfall-gauges">
				<div class="header-collapse col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-rainfall" class="header-checkbox rainfall regular-checkbox cmn-toggle cmn-toggle-round">
							<label for="checkbox-rainfall" class="label-heading"></label>
						</div>
					<div class="heading-name">
						<p class="selectable-heading">Rainfall Gauges</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="col-md-12 content-collapse" id="rainfall_gauge_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Displays amounts for every rainfall gauge in Australia.
									Use the drop down list and checkboxes to filter which amounts to display and the time intervals to which it applies.
								</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<select name="rainfall_types" id="rainfall_types">
								<option value="1hr" selected="selected">Last Hours</option>
								<option value="9am">Since 9 am</option>
								<option value="24to9am">24 to 9 am</option>
								<option value="6hr">6 Hours</option>
								<option value="24hr">24 Hours</option>
								<option value="72hr">72 Hours</option>
							</select>
						</div>
					</div>
					<div class="row rain-option-0-1">
						<div class="col-md-6">
							<input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall1" class="regular-checkbox rainfall_checkboxes" value="0">
							<label for="checkbox-rainfall1">0 mm</label>
						</div>
						<div class="col-md-6">
							<input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall2" class="regular-checkbox rainfall_checkboxes" value="9.9">
							<label for="checkbox-rainfall2">0.2 - 9.9 mm</label>
						</div>
					</div>
					<div class="row rain-option-2-3">
						<div class="col-md-6">
							<input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall3" class="regular-checkbox rainfall_checkboxes" value="24.9">
							<label for="checkbox-rainfall3">10.0 - 24.9 mm</label>
						</div>
						<div class="col-md-6">
							<input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall4" class="regular-checkbox rainfall_checkboxes" value="50.0">
							<label for="checkbox-rainfall4">25.0 - 49.9 mm</label>
						</div>
					</div>
					<div class="row rain-option-4-5">
						<div class="col-md-6">
							<input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall5" class="regular-checkbox rainfall_checkboxes" value="99.9">
							<label for="checkbox-rainfall5">50.0 - 99.9 mm</label>
						</div>
						<div class="col-md-6">
							<input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall6" class="regular-checkbox rainfall_checkboxes" value="199.9">
							<label for="checkbox-rainfall6">100 - 199.9 mm</label>
						</div>
					</div>
					<div class="row rain-option-6">
						<div class="col-md-12">
							<input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall7" class="regular-checkbox rainfall_checkboxes" value="200.0">
							<label for="checkbox-rainfall7">200 mm+</label>
						</div>
					</div>
				</div>
			</div><!-- /rainfall-gauges-->
			<div class="row river-gauges">
				<div class="header-collapse col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-river-guage" class="header-checkbox river-guage regular-checkbox cmn-toggle cmn-toggle-round">
						<label for="checkbox-river-guage" class="label-heading"></label>
					</div>
					<div class="heading-name">
						<p class="selectable-heading">River Gauges</p>
					</div>
					<div class="rotate-img">
						<span for="checkbox-river-guage" class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12" id="river_gauge_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Displays river heights for every gauge in Australia.
									Use the checkboxes to filter which flood levels are displayed.
								</p>
							</div>
						</div>
					</div>
					<div class="row river-no-minor">
						<div class="col-md-6">
							<input name="river_guage_options[]" type="checkbox" id="checkbox-river-guage1" class="regular-checkbox river_guage_checkboxes" value="nofloodorclass">
							<label for="checkbox-river-guage1">No Flooding</label>
						</div>
						<div class="col-md-6">
							<input name="river_guage_options[]" type="checkbox" id="checkbox-river-guage2" class="regular-checkbox river_guage_checkboxes" value="minor">
							<label for="checkbox-river-guage2">Minor Flooding</label>
						</div>
					</div>
					<div class="row river-moderate-major">
						<div class="col-md-6">
							<input name="river_guage_options[]" type="checkbox" id="checkbox-river-guage3" class="regular-checkbox river_guage_checkboxes" value="moderate">
							<label for="checkbox-river-guage3">Moderate Flooding</label>
						</div>
						<div class="col-md-6">
							<input name="river_guage_options[]" type="checkbox" id="checkbox-river-guage4" class="regular-checkbox river_guage_checkboxes" value="major">
							<label for="checkbox-river-guage4">Major Flooding</label>
						</div>
					</div>
				</div>
			</div><!-- /river-gauges-->
			<div class="row forecasts">
				<div class="header-collapse col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="radar_forecasts_information" name="radar_forecasts_information" class="header-checkbox regular-checkbox forecast_checkbox cmn-toggle cmn-toggle-round">
						<label for="radar_forecasts_information" class="label-heading"></label>
					</div>
					<div class="heading-name">
						<p class="selectable-heading">Forecasts</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12" id="forecasts_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Temperature and rainfall forecast for all capital cities.
									Click the icon for the seven day forecast.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /forecasts-->
			<div class="row warnings">
				<div class="header-collapse col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-1-14" class="header-checkbox weather regular-checkbox cmn-toggle cmn-toggle-round">
						<label for="checkbox-1-14" class="label-heading"></label>
					</div>
					<div class="heading-name">
						<p class="selectable-heading">Warnings</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12" id="warnings_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Overlays a feed of alert polygons for warnings issued by EWN.
									Click a polygon on the map for details of the alert.
									Current Warnings option shows all alerts that have not expired.
									Warnings last 48 hours shows all alerts for that time period regardless if they have expired.
									Filter the alerts types displayed by using the checkboxes.
								</p>
							</div>
						</div>
					</div>
					<div class="row warnings-current">
						<div class="col-md-6 centered-div">
							<input id="current_warnings" type="radio" checked="" class="warning_type" name="warning_type" value="1">
							<label for="current_warnings">Current</label>
						</div>
						<div class="col-md-6">
							<button type="button" value="Alerts" class="btn btn-success btn-sm thered" onclick="javascript:newPopup('common/alert.php','800','660');">
								<i class="icon-external-link"></i> Pop Out
							</button>
						</div>
					</div>
					<div class="row warnings-last-hours container-btm-mrgn">
						<div class="col-md-6 margin-top-warnings-last">
							<input id="warning_last" type="radio" class="warning_type" name="warning_type" value="2">
							<label for="warning_last">Warnings last</label>
						</div>
						<div class="col-md-6 ">
							<select name="warning_days" id="warning_days">
								<option value="6">6 hours</option>
								<option value="12">12 hours</option>
								<option value="18">18 hours</option>
								<option value="24">24 hours</option>
								<option value="30">30 hours</option>
								<option value="36">36 hours</option>
								<option value="42">42 hours</option>
								<option value="48" selected="selected">48 hours</option>
							</select>
						</div>
					</div>
					<div class="row warnings-tstorm-weather">
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-16" class="regular-checkbox warning_checkboxes" value="28" checked="checked">
								<label for="checkbox-1-16" class="label-inline">Severe Thunderstorm</label>
							</span>
						</div>
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-15" class="regular-checkbox warning_checkboxes" value="23,33,34,35" checked="checked">
								<label for="checkbox-1-15" class="label-inline">Severe Weather</label>
							</span>
						</div>
					</div>
					<div class="row warnings-flood-fire">
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-106" class="regular-checkbox warning_checkboxes" value="14" checked="checked">
								<label for="checkbox-1-106" class="label-inline">Flood Watch</label>
							</span>
						</div>
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-18" class="regular-checkbox warning_checkboxes" value="13" checked="checked">
								<label for="checkbox-1-18" class="label-inline">Fire Weather Warning</label>
							</span>
						</div>
					</div>
					<div class="row warnings-bfire-bfire-ew">
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-22" class="regular-checkbox warning_checkboxes" value="1,36,BW" checked="checked">
								<label for="checkbox-1-22" class="label-inline">Bushfire Advices</label>
							</span>
						</div>
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-105" class="regular-checkbox warning_checkboxes" value="1,36,BWA" checked="checked">
								<label for="checkbox-1-105" class="label-inline">Bushfire W&amp;A and EW</label>
							</span>
						</div>
					</div>
					<div class="row warnings-tsunami-cyclones">
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-21" class="regular-checkbox warning_checkboxes" value="31" checked="checked">
								<label for="checkbox-1-21" class="label-inline">Tsunami</label>
							</span>
						</div>
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-20" class="regular-checkbox warning_checkboxes" value="29" checked="checked">
								<label for="checkbox-1-20" class="label-inline">Tropical Cyclones</label>
							</span>
						</div>
					</div>
					<div class="row warnings-other-all">
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-23" class="regular-checkbox warning_checkboxes" value="1,3,4,5,6,7,8,9,10,11,12,15,16,17,18,19,20,21,22,24,25,26,27,30,32" checked="checked">
								<label for="checkbox-1-23" class="label-inline">Other</label>
							</span>
						</div>
						<div class="col-md-6">
							<span class="width50">
								<input name="warning_option[]" type="checkbox" id="checkbox-1-all" class="regular-checkbox warning_all" checked="checked" value="all">
								<label for="checkbox-1-all" class="label-inline">All</label>
							</span>
						</div>
					</div>
					<div class="row cyclone_tracker" id="cyclone_option">
						<div class="col-md-12">
							<input type="checkbox" id="checkbox-cyclone" class="regular-checkbox cyclone" onclick="toggle_cyclone()" checked="checked">
							<label for="checkbox-cyclone" class="label-inline">Cyclone Tracker</label>
						</div>
					</div>
					<div id="warningreportoption1" style="display: none;">
						<div class="row warning-report">
							<div class="col-md-12">
								<a class="report_disabled assets_title" title="Select a layer to view show assets/projects under threat"> </a>
								<input id="warning_markers_generation" type="button" onclick="javascript:polygon_show_range()" value="Show Assets/Projects Under Threat" class="btn assets assets_report" disabled="">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-lg-8">
								<a class="report_disabled report-disable-warning" title="Select a layer to view report"> </a>
								<a href="JavaScript:newPopup('common/report.php','800','660');">
									<input id="warning_report_generation" class="btn assets assets_report" type="button" value="Report of Assets/Projects under threat" disabled="">
								</a>
							</div>
							<div class="col-md-12 col-lg-4">
								<input type="button" id="clear_markres_array" class="btn assets" value="Clear" onclick="javascript:clear_range_markers();">
							</div>
						</div>
						<div class="row warning_download">
							<div class="col-md-12 col-lg-12">
								<a class="report_disabled" title="Select a layer to download as csv"> </a>
								<input id="download_as_csv_generation" type="button" class="btn assets assets_report" value="Download Report as CSV" onclick="javascript:download_polygon_show_range_csv();" disabled="">
							</div>
						</div>
					</div>
				</div>
			</div><!-- /warnings-->
			<div class="row forecast-severe-weather">
				<div class="header-collapse col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-1-24" class="header-checkbox forecastsevere regular-checkbox cmn-toggle cmn-toggle-round">
						<label for="checkbox-1-24" class="label-heading"></label>
					</div>
					<div class="heading-name">
						<p class="selectable-heading">Forecast Severe Weather</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12" id="forecast_severe_weather_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Displays threat areas forecast by EWN meteorologists for the next 24 and 48 hours.
									Filter the threat types displayed by using the checkboxes.
								</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 container-btm-mrgn">
							<?php
								$current_time = date("H:i:s");
								if ($current_time >= '09:00:00') {
									$current_day = date('D')." 9am + 24hrs";
									$tomorrow = date("D", time() + 86400)." 9am + 24hrs";
								} 
								else {
									$current_day = date("D", time() - 86400)." 9am + 24hrs";
									$tomorrow  = date('D')." 9am + 24hrs";
								}
							?>
							<select name="drop" class="Selectmargin" id="forecast_severe_weather_option">
								<option value="1">
									<?php echo $current_day; ?>
								</option>
								<option value="2">
									<?php echo $tomorrow; ?>
								</option>
							</select>
						</div>
					</div>
					<div class="row sweather_tstorm_rainfall">
						<div class="col-md-6">
							<input name="forecast_warning_option[]" type="checkbox" id="checkbox-1-201" class="regular-checkbox warning_checkboxes" value="38">
							<label for="checkbox-1-201" class="label-inline">Severe Thunderstorms</label>
						</div>
						<div class="col-md-6">
							<input name="forecast_warning_option[]" type="checkbox" id="checkbox-1-202" class="regular-checkbox warning_checkboxes" value="37">
							<label for="checkbox-1-202" class="label-inline">Heavy Rainfall</label>
						</div>
					</div>
					<div class="row sweather_winds_cyclone container-btm-mrgn">
						<div class="col-md-6">
							<input name="forecast_warning_option[]" type="checkbox" id="checkbox-1-203" class="regular-checkbox warning_checkboxes" value="39">
							<label for="checkbox-1-203" class="label-inline">Severe Winds</label>
						</div>
						<div class="col-md-6">
							<input name="forecast_warning_option[]" type="checkbox" id="checkbox-1-204" class="regular-checkbox warning_checkboxes" value="40">
							<label for="checkbox-1-204" class="label-inline">Tropical Cyclone</label>
						</div>
					</div>
					<div id="forecastwarningreportoption1" style="display:none;">
						<div class="row sweather_report row-centered">
							<div class="col-md-12">
								<a class="report_disabled assets_title" title="Select a layer to view report"> </a>
								<input type="button" id="forecast_warning_markers_generation" onclick="javascript:forecast_severe_show_asset()" value="Show Assets/Projects Under Threat" class="btn assets assets_report " disabled="">
							</div>
							<div class="col-md-12 col-lg-8">
								<a class="report_disabled report-disable-warning" title="Select a layer to view report"> </a>
								<a href="JavaScript:newPopup('common/forecast_report.php','800','660');">
									<input id="forecast_warning_report_generation" class="btn assets assets_report " type="button" value="Report of Assets/Projects under threat" disabled="">
								</a>
							</div>
							<div class="col-md-12 col-lg-4">
								<input type="button" id="clear_markres_array" class="btn assets" value="Clear" onclick="javascript:forecast_clear_filter_markers();">
							</div>
						</div>
						<div class="row sweather_download row-centered">
							<div class="col-md-12 col-lg-8">
								<a class="report_disabled" title="Select a layer to view report"> </a>
								<input id="download_as_csv_generation" type="button" class="btn assets assets_report " value="Download Report as CSV" onclick="javascript:forecast_download_csv_report();" disabled="">
							</div>
							<div class="col-md-12 col-lg-4">
								<input type="button" class="btn assets" value="Warning list" onclick="JavaScript:newPopup(&#39;common/severe_alert.php&#39;,&#39;512&#39;,&#39;600&#39;);">
							</div>
						</div>
					</div>
				</div>
			</div><!-- /severe weather-->
			<div class="row satellite">
				<div class="header-collapse col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="sat-enable" class="header-checkbox regular-checkbox cmn-toggle cmn-toggle-round">
							<label for="sat-enable" class="label-heading"></label>
						</div>
					<div class="heading-name">
						<p class="selectable-heading extra_option">
							Satellite
						</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>
									Displays current visual, infrared and water vapour satellite images.
									Use the controls to step or loop through previous images.
								</p>
							</div>
						</div>
					</div>
					<div class="row" style="margin-bottom: 10px;">
						<div class="col-md-3 centered-div-satellite">
							<label for="sat_type_selector">Satellite Type: </label>
						</div>
						<div class="col-md-6 centered-div-satellite">
							<select id="sat_type_selector" name="sat_type_selector" class="Selectmargin">
								<option value="sat-ir" selected="selected">Infrared Zehr</option>
								<option value="sat-vis" selected="selected">Visible</option>
								<option value="sat-wv">Water Vapour</option>
								<option value="sat_irrbw">Infrared Rainbow</option>
							</select>
							<!--<input type="checkbox" id="sat-ir" class="regular-checkbox">
							<label for="checkbox-1-36">Infra-red</label>-->
						</div>
						<div class="col-md-3">
							<button type="button" value="Satellite" class="btn btn-sm thegreen" onclick="javascript:newPopup('common/bom.satellite.php','641','600');"><i class="icon-external-link"></i> Pop Out</button>
						</div>
					</div>
					<div class="row opacity container-btm-mrgn">
						<div class="col-md-4">
							<label for="sat_module_opacity">Opacity</label>
							<select name="sat_module_opacity" class="Selectmargin" id="sat_module_opacity">
								<option value="0.1">10%</option>
								<option value="0.2">20%</option>
								<option value="0.3">30%</option>
								<option value="0.4">40%</option>
								<option value="0.5">50%</option>
								<option value="0.6">60%</option>
								<option value="0.7" selected="selected">70%</option>
								<option value="0.8">80%</option>
								<option value="0.9">90%</option>
								<option value="1">100%</option>
							</select>
						</div>
						<div class="col-md-4">
							<p id="sat_date">Image Date:</p>
						</div>
						<div class="col-md-4">
							<select name="drop" class="drop" id="sat_hours">
								<option value="128">3 Hours</option>
							</select>
						</div>
					</div>
					<div class="row satellite_loop">
						<div class="col-md-11">
							<div style="width:100%; margin:15px auto;" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
								<span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"  id="sat-slider" style="left: 0%;"></span>
							</div>
						</div>
						<div class="col-md-11">&nbsp;</div>
					</div>
					<div class="row satellite_loop">
						<div class="col-md-4 loop-control-align">
							<input type="checkbox" id="sat-loop-onoff" class="regular-checkbox">
							<label for="sat-loop-onoff">View latest image</label>
						</div>
						<div class="col-md-4">
							<div class="row Controls satellite-controls">
								<div class="col-md-3">
									<a class="play" href="index.php#" title="Play" id="play_sat">play</a>
								</div>
								<div class="col-md-3">
									<a class="reverse" href="index.php#" title="Reverse" id="prev_sat">reverse</a>
								</div>
								<div class="col-md-3">
									<a class="forward" href="index.php#" title="Forward" id="next_sat">forward</a>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<select name="sat_speed_control" id="sat_speed_control" class="sat_speed_control">
								<option value="4000">Slow</option>
								<option value="2000" selected="selected">Normal</option>
								<option value="1000">Fast</option>
								<option value="500">Very Fast</option>
							</select>
						</div>
					</div>
				</div>
			</div><!-- /satellite-->
			<div class="row map-options">
				<div class="header-collapse col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="map-options" class="header-checkbox regular-checkbox cmn-toggle cmn-toggle-round" />
						<label for="map-options" class="label-heading"></label>
					</div>
					<div class="heading-name">
						<p class="selectable-heading extra_option">Map Options</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12" id="map_option_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>Allows you to change the Google Map background or overlay spatial data for river catchments and BoM forecasts districts.</p>
							</div>
						</div>
					</div>
					<div class="row container-btm-mrgn">
						<div class="col-md-6">
							<label for="map_options_selector">Map Options</label>
						</div>
						<div class="col-md-6">
							<select id="map_options_selector" class="Selectmargin" name="drop" >
								<option value="ROADMAP" selected="selected">Google Map View</option>
								<option value="SATELLITE">Google Satellite View</option>
								<option value="HYBRID">Google Hybrid View</option>
								<option value="TERRAIN">Terrain view</option>
								<option value="light_monochrome">Light Monochrome</option>
							</select>
						</div>
					</div>
					<!--
					NOT IN CURRENT REVISION
					<div class="row mapoptions_fire">
						<div class="col-md-12">
							<input type="checkbox" id="fireregions" class="regular-checkbox">
							<label for="fireregions">Fire Regions</label>
						</div>
					</div>
					-->
					<div class="row mapoptions_regions_catchments">
						<div class="col-md-6">
							<input type="checkbox" id="weatherregions" class="regular-checkbox">
							<label for="weatherregions">Weather Regions</label>
						</div>
						<div class="col-md-6">
							<input type="checkbox" id="riverbasins" class="regular-checkbox">
							<label for="riverbasins">River Catchments</label>
						</div>
					</div>
				</div>
			</div>
			<!-- /mapoptions-->
			<div class="row map-data-layers">
				<div class="header-collapse bypass_check col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-data-layers" class="header-checkbox regular-checkbox cmn-toggle cmn-toggle-round">
						<label for="checkbox-data-layers" class="label-heading"></label>
					</div>
					<div class="heading-name">
						<p class="selectable-heading extra_option">Map Data Layers</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12" id="map_data_layers_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>Provides overview of company sites.</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="checkbox" class="regular-checkbox" id="client_data_layer">
							<label for="client_data_layer">Client Data</label>
						</div>
					</div>
					<div class="row map_data_layers_client_dd">
						<div class="col-md-12">
							<div class="main_client_controller">
								<div class="data_layers_drop_down_client" id="showClientDataDropDownDiv">
									<select class="data_layers_drop_down_client" id="select_client_data" multiple="multiple">
										<?php
											if ($_SESSION['username'] == 'admin') {
													$select = "SELECT DISTINCT `layer_id`,`layer_type` FROM `layer_datas` as `ld` WHERE `ld`.`layer_id` > '10' ORDER BY `ld`.`layer_id` ASC";
											} 
											else {
												$select = "SELECT DISTINCT `ld`.`layer_id`,`layer_type`,`client_id` FROM `layer_datas` as `ld` INNER JOIN `client_layers` as `cl` ON `ld`.`layer_id` = `cl`.`layer_id` WHERE `client_id` = '".$_SESSION['user_id']."' AND `ld`.`layer_id` > '10' ORDER BY `ld`.`layer_id` ASC";
											}
											$select_rs = mysql_query($select);
											if (mysql_num_rows($select_rs) > 0) {
												$option  = '' ;
												while ($row = mysql_fetch_assoc($select_rs)) {
													$option .= '<option value="'.$row['layer_id'].'">'.$row['layer_type'].'</option>'; 
												}
											echo $option;
											}
										?>
									</select>
								</div>
								<div id="site_list_option">
									<div id="site_list_button">
										<button id="show_sites" onclick="show_sites();" class="btn">Site List</button>
										<button id="hide_sites" onclick="hide_sites();"  class="btn">Hide List</button>
									</div>
									<div id="site_list_drop">
										<select name="show_sites_layers" id="show_sites_layers" onchange="show_sites(this.value);"></select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="checkbox" id="public_data_layer" class="regular-checkbox">
							<label for="public_data_layer">Public Data</label>
						</div>
					</div>
					<div class="row map_data_layers_public_dd">
						<div class="col-md-12">
							<div>
								<div class="main_public_controller">
									<div id="showPublicDataDropDownDiv" class="data_layers_drop_down">
										<select class="data_layers_drop_down" id="select_public_data" multiple="multiple">
											<option value="1">AU Railway Tracks</option>
											<option value="2">AU Telephone Exchanges</option>
											<option value="3">Broadcast Transmitter</option>
											<option value="4">AU Oil and Gas Pipelines</option>
											<option value="5">AU Electricity Transmission Substations</option>
											<option value="6">AU Major Airport Terminals</option>
											<option value="7">AU Petrol Stations</option>
											<option value="8">AU Ports </option>
											<option value="9">AU Operating Mines </option>
											<option value="10">AU Major Power Stations</option>
										</select>
									</div>
								</div>
							</div>
							<div id="site_list_option">
								<div id="site_list_button">
									<button id="show_sites" onclick="show_sites();">Site List</button>
									<button id="hide_sites" onclick="hide_sites();">Hide List </button>
								</div>
								<div id="site_list_drop">
									<select name="show_sites_layers" id="show_sites_layers" onchange="show_sites(this.value);"></select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /map_data_layers-->
			<div class="row forecast-model">
				<div class="header-collapse col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-coming-soon" class="header-checkbox regular-checkbox cmn-toggle cmn-toggle-round">
							<label for="checkbox-coming-soon" class="label-heading"></label>
						</div>
					<div class="heading-name">
						<p class="selectable-heading extra_option">Forecast Model</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml">
								<p>Choose from the dropdown list of models and criteria to view forecast weather model information as a layer on the map.</p>
							</div>
						</div>
					</div>
					<div class="row container-btm-mrgn">
						<div class="col-md-6">
							<label for="forecast_model" id="forecast_model_name">Forecast Model</label>
						</div>
						<div class="col-md-6">
							<select name="drop" class="Selectmargin" id="forecast_model">
								<option value="">OFF</option>
								<option value="gfs">GFS</option>
							</select>
						</div>
					</div>
					<div class="row opacity container-btm-mrgn">
						<div class="col-md-12">
							<label for="forecast_module_opacity">Opacity</label>
							<select name="forecast_module_opacity" class="Selectmargin" id="forecast_module_opacity">
								<option value="0.1">10%</option>
								<option value="0.2">20%</option>
								<option value="0.3">30%</option>
								<option value="0.4">40%</option>
								<option value="0.5">50%</option>
								<option value="0.6">60%</option>
								<option value="0.7" selected="selected">70%</option>
								<option value="0.8">80%</option>
								<option value="0.9">90%</option>
								<option value="1">100%</option>
							</select>
						</div>
					</div>
					<div class="row forecast_type container-btm-mrgn">
						<div class="col-md-6">
							<label for="forecasts_types_select">Forecast Type</label>
						</div>
						<div class="col-md-6">
							<select name="drop" class="Selectmargin" id="forecasts_types_select">
								<option value="">Select Forecast Type</option>
								<option value="li">Storm instability</option>
								<option value="cape">Storm Energy</option>
								<option value="mslp">Surface Pressure + rain</option>
								<option value="tscreen">Surface Temperature</option>
								<option value="s10m" selected="">Surface Winds</option>
								<option value="gustssfc">Surface Wind Gusts</option>
								<option value="rhscreen">Surface relative humidity</option>
								<option value="ts">Storm probability</option>
								<option value="cloud-total">Cloud cover - Total</option>
								<option value="cloud-high">Cloud Cover - High level</option>
								<option value="cloud-mid">Cloud Cover - mid level</option>
								<option value="cloud-low">Cloud Cover - low level</option>
							</select>
						</div>
					</div>
					<div class="row forecast_date_time container-btm-mrgn">
						<div class="col-md-6">
							<label for="forecast_module_datetime">Forecast Date/Time</label>
						</div>
						<div class="col-md-6">
							<select name="forecast_module_datetime" id="forecast_module_datetime">
								<option value="">Select Date/Time</option>
							</select>
						</div>
					</div>
					<div class="row models_loop">
						<div class="col-md-11">
							<div style="width:100%; margin:15px auto;" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
								<span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"  id="models-slider" style="left: 0%;"></span>
							</div>
						</div>
						<div class="col-md-11">&nbsp;</div>
					</div>					
					<div class="row model_controls container-btm-mrgn">
						<div class="col-md-4">
							<label for="forecast_storm_play">Model Loop</label>
						</div>
						<div class="col-md-4">
							<div class="row Controls">
								<div class="col-md-4">
									<a href="javascript:void(0);" class="play forecast_storm_play" id="play_storm" title="Play">play</a>
								</div>
								<div class="col-md-4">
									<a href="index.php#" class="reverse forecast_storm_reverse" id="prev_storm" title="Previous">reverse</a>
								</div>
								<div class="col-md-4">
									<a href="index.php#" class="forward forecast_storm_forward" id="next_storm" title="Forward">forward</a>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<select name="drop" id="forstorm_speed_control" class="forstorm_speed_control">
								<option value="5000">Slow</option>
								<option value="4000" selected="selected">Normal</option>
								<option value="3000">Fast</option>
							</select>
						</div>
					</div>
				</div>
			</div><!-- /forecast model-->

			<!--Hacky Kml Stuff-->
			<?php if ($_SESSION['username'] == 'admin') { ?>
				<div class="row additional-kml">
					<div class="header-collapse col-md-12 border-bottom-20">
						<div class="switch">
							<input type="checkbox" id="checkbox-additional-kml" class="header-checkbox regular-checkbox cmn-toggle cmn-toggle-round">
							<label for="checkbox-additional-kml" class="label-heading"></label>
						</div>
						<div class="heading-name">
							<p class="selectable-heading extra_option">KML Layers</p>
						</div>
						<div class="rotate-img">
							<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
						</div>
					</div>
					<div class="content-collapse col-md-12">
						<div class="row">
							<div class="info-text info-text-sml"  style="display:none;">
								<p>Kml Extras</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="checkbox" id="hardcoded-kml"> <label class="text-primary">Show Vehicle KML</label>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<!--/Hacky Kml Stuff-->

			<!--Nbn Data Layers -->
			<?php if(isset($_SESSION['is_authorised_nbn_user']) && $_SESSION['is_authorised_nbn_user']==1){?>
			<div class="row nbn-data-layers">
				<div class="header-collapse bypass_check col-md-12 border-bottom-20">
					<div class="switch">
						<input type="checkbox" id="checkbox-nbn-data-layers" class="header-checkbox regular-checkbox cmn-toggle cmn-toggle-round">
						<label for="checkbox-nbn-data-layers" class="label-heading"></label>
					</div>
					<div class="heading-name">
						<p class="selectable-heading extra_option">Nbn Data Layers</p>
					</div>
					<div class="rotate-img">
						<span class="rotate glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
					</div>
				</div>
				<div class="content-collapse col-md-12" id="nbn-data-layers_box">
					<div class="row">
						<div class="col-md-12">
							<div class="info-text info-text-sml" id="loading_nbn_layers" style="display:none;">
								<p>Loading NBN layers.</p>
							</div>
							
							<div class="form-group my_polygons">	
									<input  type="checkbox"  id="drawing_mode" value="1" data-docid="1"> <label class="text-primary">Draw Polygon</label>
									
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							
							<input type="hidden" id="nbn_user_type" value="<?php echo $_SESSION['nbn_user_type'];?>">
							<input type="hidden" id="data_uploaded" value="">
							
						</div>
					</div>
					<div class="row">
							<div id="nbn_list_option" style="display:none;">								<div id="nbn_list_button">
									<button id="show_nbn_layers" class="btn btn-primary" onclick="show_nbn_layers();">Site List</button>
									<button id="hide_nbn_layers" class="btn btn-primary" onclick="hide_nbn_layers();" style="display:none;">Hide List </button>
									 <button type="button" name="save_poly" value="save_poly" id="open_alert_form" class="btn btn-primary" style="display:none;">Send Alert</button>
							</div>
					</div>
					
					<div class="pre_drawn_nbn_polygons col-md-12" style="margin-top:5px;">
							<?php 
							$current_user_id = $_SESSION['user_id'];
							$glob = glob("user_kmls/$current_user_id/*.kml");
							if(!empty($glob))
							{
								$counter = 1;
								foreach($glob as $elements)
								{
									$newElementName = str_replace(' ','_',$elements);
									echo '<div class="form-group my_polygons" id="polyrow'.$counter.'">	
									<input layer-name="'.$newElementName.'" type="checkbox" class="load_saved_kmls hitlayer'.$counter.'" name="'.$elements.'" id="'.$elements.'" value="'.$elements.'"> <label for="'.$elements.'" class="text-primary">'.basename($elements).'</label>
									<a onclick="javascript:remove_draw_polygon(&quot;'.$counter.'&quot;,&quot;'.$elements.'&quot;,1)" href="javascript:void(0);" class="btn btn-default btn-xs pull-right"><i class="glyphicon glyphicon-trash"></i></a >
									<a href="'.$elements.'" target="_blank" title="download KML"  download class="btn btn-default btn-xs pull-right"><i class="glyphicon glyphicon-download-alt"></i></a >
									</div>'; 
									$counter++;
								}
							}
							?>
					</div> 
					
				</div>
			</div>
			<?php }?>
			<!-- /nbn_data_layers-->
		</div>
	</div><!-- /Popcontainer-->
</div>


<!-- Popup container after comletion of polygon -->
<div class="modal popup_polygon_promt" tabindex="-1" role="dialog" id="popup_polygon_promt" data-keyboard="false" data-backdrop="static">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog  modal-sm vertical-align-center">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Choose option for the drawn polygon</h4>
				</div>
				<div class="modal-body">
					<div class="text-center">
						<button class=" btn btn-primary redraw" id="redraw" name="action" value="redraw">Clear</button>
						<button class=" btn btn-success continue" id="continue-polygon" name="action" value="continue">Save</button>
						<button class=" btn btn-danger cancel" id="cancel-polygon" name="action" value="cancel">Cancel</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Popup container after comletion of polygon -->

<!-- After click on Continue Button -->
<div class="modal fade popup_polygon_completeform" id="popup_polygon_completeform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Complete your polygon by given a title or description</h4>
			</div>
			<form action="#" method="POST" id="save_polygon_form" class="save_polygon_form" >
				<div class="modal-body">
					<div class="form-group">
						<label for="title" style="color:#000;">Title *</label>
						<input type="text" class="form-control" name="title" id="title-polygon">
					</div>
					<div class="form-group">	
						<label for="description" style="color:#000;">Description</label>
						<textarea name="description" id="description-polygon" class="description form-control" cols="30" rows="10"></textarea>
					</div>	
				</div>
				<div class="modal-footer">
					<input type="hidden" name="save_polygon" value="save_polygon">
					<button type="submit" name="save_poly" value="save_poly" id="save_polygon_details" class="btn btn-primary" disabled>Save changes</button>
					<button type="button" name="cancel_poly" value="cancel_poly" id="cancel_polygon_details" class="btn btn-primary">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- After click on Continue Button -->

<!-- Popup container after comletion of polygon -->
<div class="modal popup_polygon_promt" tabindex="-1" role="dialog" id="popup_nbn_polygon_promt" data-keyboard="false" data-backdrop="static">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog  modal-sm vertical-align-center">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Choose option for the drawn polygon</h4>
				</div>
				<div class="modal-body">
					<div class="text-center">
						<button class=" btn btn-primary redraw" id="redraw-nbn" name="action" value="redraw">Clear</button>
						<button class=" btn btn-success continue" id="continue-nbn-polygon" name="action" value="continue">Save</button>
						<button class=" btn btn-danger cancel" id="cancel-nbn-polygon" name="action" value="cancel">Cancel</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Popup container after comletion of polygon -->

<!-- After click on Continue Button -->
<div class="modal fade popup_polygon_completeform" id="popup_nbn_polygon_completeform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Complete your polygon by given a title or description</h4>
			</div>
			<form action="#" method="POST" id="save_nbn_polygon_form" class="save_nbn_polygon_form" >
				<div class="modal-body">
					<div class="form-group">
						<label for="title" style="color:#000;">Title *</label>
						<input type="text" class="form-control" name="title" id="title-nbn-polygon">
					</div>
					<div class="form-group">	
						<label for="description" style="color:#000;">Description</label>
						<textarea name="description" id="description-nbn-polygon" class="description form-control" cols="30" rows="10"></textarea>
					</div>	
				</div>
				<div class="modal-footer">
					<input type="hidden" name="save_polygon" value="save_polygon">
					<button type="button" name="save_nbn_poly" value="save_nbn_poly" id="save_nbn_polygon_details" class="btn btn-primary" disabled>Save changes</button>
					<button type="button" name="cancel_nbn_poly" value="cancel_nbn_poly" id="cancel_nbn_polygon_details" class="btn btn-primary">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- After click on Continue Button -->

<!-- After click on Alert Button -->
<div class="modal fade popup_nbn_polygon" id="popup_nbn_polygon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Alert Status</h4>
            </div>
            <form  method="POST" id="save_nbn_alert_form" class="save_polygon_form">
                <div class="modal-body">
                    <div id="ewn_loading" style="display:none;"><img src="images/loading.gif" ></div>
                    <div class="form-group">
                        <label for="title" style="color:#000;">Subject *</label>
                        <input type="text" class="form-control" id="title_nbn_polygon" placeholder="Bush Fire Alert" name="title_nbn_polygon">
                        <input type="hidden" class="form-control"  id="nbn_postcodes" name="postcodes">
                    </div>
                    <div class="form-group">
                        <label for="title" style="color:#000;">Alert Status *</label>
                        <select name="alert_type" id="alert_type" class="form-control">
                            <option value="">Select an alert</option>
                            <option value="Green">Green</option>
                            <option value="#ffcc00">Amber</option>
                            <option value="Red">Red</option>
                            <option value="Black">Black</option>
                        </select>
                    </div>
                    <div class="form-group">    
                        <label for="description" style="color:#000;">Alert Message</label>
                        <textarea id="nbn_description_polygon" name="nbn_description_polygon"  class="form-control" cols="30" rows="10" style="width:100% !important;"></textarea>
                    </div>  
                </div>
                <div class="modal-footer">
                <input type="hidden" id="send_alert_type">
                    <input type="hidden" name="save_polygon" value="save_nbn_polygon">
                        <button type="button" name="save_poly" value="save_poly" id="save_polygon_details" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="confirm_alert()" name="save_poly" value="save_poly" id="save_nbn_alert_details" class="btn btn-primary" disabled >Send</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- After click on Alert Button -->
