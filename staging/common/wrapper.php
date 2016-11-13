<?php

if (isset($_GET['page'])) { $page = $_GET['page']; }
if (isset($_GET['mode'])) { $mode = $_GET['mode']; }
if (isset($_GET['radar'])) { $radar = $_GET['radar']; }
if (isset($_GET['tz'])) { $timeZone = $_GET['tz']; }
if (isset($_GET['chartsLocation'])) { $chartsLocation = $_GET['chartsLocation']; }
if (isset($_GET['forecastLocation'])) { $forecastLocation = $_GET['forecastLocation']; }
if (isset($_GET['forecastState'])) { $forecastState = $_GET['forecastState']; }
if (isset($_GET['obsLocation'])) { $obsLocation = $_GET['obsLocation']; }

if ($page == 'ewn-warnings')
  {
  $pageTitle = 'EWN Alerts'; 
  $pageURL = 'http://clients.ewn.com.au/common/recent_alerts.php';
  $pageHeader = 'The Early Warning Network: Recent Alerts';
  $headerSize = '100%';
  $headerHTML = "<div class=\"header\">{$pageHeader}</div>";
  }
elseif ($page == 'charts')
  {
  $pageTitle = 'EWN Charts';
  $pageURL = "http://101.0.91.82/clients/ewn/stormcast.html?ops=gfs:::{$chartsLocation}:ts";
  $pageHeader = 'The Early Warning Network: Forecast Charts';
  $headerSize = '100%';
  $headerHTML = "<div class=\"header\">{$pageHeader}</div>";
  }
elseif ($page == 'charts-wyoming')
  {
  $pageTitle = 'EWN Charts';
  $pageURL = "http://101.0.91.82/clients/ewn-wyoming/stormcast.html?ops=gfs:::{$chartsLocation}:ts";
  $pageHeader = 'The Early Warning Network: Forecast Charts';
  $headerSize = '100%';
  $headerHTML = "<div class=\"header\">{$pageHeader}</div>";
  }
elseif ($page == 'forecast')
  {
  $pageTitle = 'BoM Forecast';
  $pageURL = "http://clients.ewn.com.au/common/bom/forecast.php?location={$forecastLocation}&forecastState={$forecastState}&mode={$mode}";
  
  if ($mode == 'local') { $headerLocation = $forecastLocation; }
  if ($mode == 'state') { $headerLocation = $forecastState; }
  
  $pageHeader = 'The Early Warning Network: BoM Forecast for '.ucwords(str_replace("-", " ", $headerLocation));  
  $headerSize = '100%';
  $headerHTML = "<div style=\"display: inline-block; width:60%; text-align: left;\"><div class=\"header\">{$pageHeader}</div></div><div style=\"display: inline-block; width:40%; text-align: right;\"><div class=\"headerLinks\"><a href=\"http://clients.ewn.com.au/common/wrapper.html?page=forecast&mode=local&forecastLocation={$forecastLocation}&forecastState={$forecastState}\">Local</a> | <a href=\"http://clients.ewn.com.au/common/wrapper.html?page=forecast&mode=state&forecastLocation={$forecastLocation}&forecastState={$forecastState}\">State</a></div></div>";
  }
elseif ($page == 'radar')
  {
  $pageTitle = 'BoM Radar';
  $pageURL = "http://clients.ewn.com.au/common/radar.php?radar={$radar}&tz={$timeZone}";
  $pageHeader = 'The Early Warning Network: BoM Radar';
  $headerSize = '100%';
  $headerHTML = "<div class=\"header\">{$pageHeader}</div>";
  }
elseif ($page == 'satellite')
  {
  $pageTitle = 'BoM Satellite Images';
  $pageURL = "http://clients.ewn.com.au/common/bom.satellite.php?tz={$timeZone}";
  $pageHeader = 'The Early Warning Network: BoM Satellite Images';
  $headerSize = '100%';
  $headerHTML = "<div class=\"header\">{$pageHeader}</div>";
  }
elseif ($page == 'obs')
  {
  $pageTitle = 'BoM Observations';
  $pageURL = "http://clients.ewn.com.au/common/bom/observations.php?location={$obsLocation}";
  $pageHeader = 'The Early Warning Network: BoM Observations for '.ucwords(str_replace("-", " ", $obsLocation));
  $headerSize = '100%';  
  $headerHTML = "<div class=\"header\">{$pageHeader}</div>";
  }
  
?>
<html>
  <head>
  <style>
  body { margin: 0px; overflow: hidden; font-family:"Helvetica", "Arial", sans-serif; }
  a, a:visited, a:active { color: rgb(0, 103, 184); }
  a:hover { color: red; }
  
  .header { background-color: #F18308; color: white; font-weight: bold; font-size: <?php echo $headerSize; ?>; padding-top:10px; padding-bottom:10px; padding-left:5px; }
  .headerLinks { background-color: #F18308; color: white; font-weight: bold; font-size: <?php echo $headerSize; ?>; padding: 10px 10px 10px 5px; }
  </style>
  <title><?php echo $pageTitle; ?></title>  
  </head>
  <body>
  <?php echo $headerHTML; ?>
  <iframe src="<?php echo $pageURL; ?>" frameborder="0" width="100%" height="100%">
  <br><br><br><br><br><br><br><br>
  </body>
</html>
