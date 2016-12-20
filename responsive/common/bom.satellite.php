<meta name="viewport" content="width=device-width, initial-scale=1">
<?php

// Written by Ben Quinn for EWN - November 2012.  benwquinn@gmail.com 0422 188 164
error_reporting(0);

if (isset($_GET['tz'])) { $tz = $_GET['tz']; }
if (isset($_GET['imageMode'])) { $imageMode = $_GET['imageMode']; }
if (isset($_GET['region'])) { $region = $_GET['region']; }

// Defaults
if (@!$imageMode) { $imageMode = 'infrared'; }
if (@!$region) { $region = '1'; }

$timezone['wa'] = 'Australia/Perth';
$timezone['nt'] = 'Australia/Darwin';
$timezone['qld'] = 'Australia/Brisbane';
$timezone['nsw'] = 'Australia/Sydney';
$timezone['act'] = 'Australia/Canberra';
$timezone['vic'] = 'Australia/Canberra';
$timezone['sa'] = 'Australia/Adelaide';
$timezone['tas'] = 'Australia/Hobart';

date_default_timezone_set($timezone[$tz]); 

$imageModes['1']['truecolour'] = 'IDE00135';
$imageModes['1']['falsecolour'] = 'IDE00134';
$imageModes['1']['infrared'] = 'IDE00105';
$imageModes['1']['visible'] = 'IDE00106';
$imageModes['1']['colourenhanced'] = 'IDE00133';
$imageModes['1']['replacementHook'] = 'IDE00135';

$imageModes['12']['truecolour'] = 'IDE00125';
$imageModes['12']['falsecolour'] = 'IDE00124';
$imageModes['12']['infrared'] = 'IDE00125';
$imageModes['12']['visible'] = 'IDE00126';
$imageModes['12']['colourenhanced'] = 'IDE00123';
$imageModes['12']['replacementHook'] = 'IDE00125';

$imageModes['14']['truecolour'] = 'IDE00145';
$imageModes['14']['falsecolour'] = 'IDE00144';
$imageModes['14']['infrared'] = 'IDE00145';
$imageModes['14']['visible'] = 'IDE00146';
$imageModes['14']['colourenhanced'] = 'IDE00143';
$imageModes['14']['replacementHook'] = 'IDE00145';

$imageModeReplacement = $imageModes[$region][$imageMode];
$replacementHook = $imageModes[$region]['replacementHook'];

$remoteData = file_get_contents("http://www.bom.gov.au/australia/satellite/?domain={$region}");

   $startAnchor = strpos($remoteData, 'Animator.imgListsat');
   $stopAnchor = strpos($remoteData, 'function initPage');

   $urlsRaw = substr($remoteData, $startAnchor, ($stopAnchor-$startAnchor));

   $urlsRaw = str_replace("/satellite/","http://www.bom.gov.au/satellite/", $urlsRaw);
   //print_r($urlsRaw); die;
   preg_match_all("(\"(.*)\")", $urlsRaw, $rawImages);

   $b = 0;
   
   for ($a = 0; $a < sizeof($rawImages[1]); $a++)
     {
     if (strpos($rawImages[1][$a], ".jpg"))
        {
        $rawImages[1][$a] = str_replace($replacementHook, $imageModeReplacement, $rawImages[1][$a]);
        $satelliteImages[$b]['remoteFilename'] = "http://www.bom.gov.au{$rawImages[1][$a]}";
        $satelliteImages[$b]['localTimeString'] = "Test";

        $utcTime[$a] = str_replace(".jpg", "", substr($rawImages[1][$a], -16));
        $satelliteImages[$b]['localTimeString'] = date("g:ia", gmmktime(substr($utcTime[$a],8,2),substr($utcTime[$a],10,2),0,substr($utcTime[$a],4,2),substr($utcTime[$a],6,2),substr($utcTime[$a],0,4)));
        $satelliteImages[$b]['localTimeUnix'] = (gmmktime(substr($utcTime[$a],8,2),substr($utcTime[$a],10,2),0,substr($utcTime[$a],4,2),substr($utcTime[$a],6,2),substr($utcTime[$a],0,4)));
        $b++;
        }
     }   

$images = array_slice($satelliteImages, -4);

$tab = '';

?>
<html>
  <head>
  <title>Satellite</title>
  <style type="text/css">
   body
{
  font-family:Arial, Verdana, Geneva, sans-serif;
  -webkit-font-smoothing: antialiased;
  font-weight:normal;
}
body, html
{
  height:100%;
  background: #deedf4;
}
body, *
{
  margin:0;
  padding:0;
}
ul li
{
  list-style:none;
}

.Popcontainer
{

  width:365px;
  padding:6px;
  border:3px solid #7b797c;
  position:relative;
  float:right;
}
.logo
{
  /*background:url(../images/logo.png);*/
  width:165px;
  height:15px;
  margin:10px auto;
}
.hide
{
  position:absolute;
  border:1px solid #a71f23;
  border-radius:20px;
  top:0px;
  right:6px;
}
.hide a
{
  font-weight:bold;
  text-decoration:none;
  color:#000;
  padding:3px 14px;
  display:block;
  font-size:14px;
}
.Popcontainer h1
{
  text-transform:uppercase;
  text-align:center;
  font-size:30px;
  color:#292929;
  margin:10px 0;
  margin-top:15px;
  clear:both;
}
.Room
{
  border:1px solid #b5b5b5;
  height:auto;
}
.Room li
{
  border-bottom:1px solid #b5b5b5;
  padding:5px;
}
.Room li:last-child
{
  border-bottom:0;
}
.Room li > ul > li
{
  font-size:11px;
  border:0;
  height: 16px;
  line-height: 16px;
}
.Room li > ul > li > span > h2, .Room li > ul > li > span > h3
{
  font-weight: bold;
  font-size: 12px;
  float:left;
  margin-right:3px;
  margin-left:10px;
  line-height:10px;
}
.Room li > ul > li > span > h3
{
  font-weight:normal;
}
.Room .nomargin
{
  margin-left:0;
}
.Room select.Selectmargin
{
  margin-top:-2px;
}
.Room li > ul > li > span > select
{
  margin-left:8px;
}
.Room li ul li span 
{
  float:left;
}
.Controls
{
  float:left;
  margin-left:7px;
  margin-top:3px;
}
.Room li li .Controls li
{
  float:left;
  padding:0;
  border:0;
}
.Controls li.play, .Controls li.pause, .Controls li.reverse, .Controls li.forward
{
  background:url(../images/audio-control.png);
  background-repeat:no-repeat;
  width:17px;
  height:14px;
  background-position-x:1px;
  background-position-y:0px;
}
.Controls li.pause
{
  background:url("../images/audio-control.png") no-repeat scroll -14px 0px rgba(0, 0, 0, 0);
}
.Controls li.reverse
{
  background:url("../images/audio-control.png") no-repeat scroll -32px 0px rgba(0, 0, 0, 0);
}
.Controls li.forward
{
  background:url("../images/audio-control.png") no-repeat scroll -53px 0px rgba(0, 0, 0, 0);
}
.Controls li.play a, .Controls li.pause a, .Controls li.reverse a, .Controls li.forward a
{
  display:block;
  text-indent:-9999px;
}
label {
    display: inline;
}
 
.regular-checkbox {
    display: none;
}
 
.regular-checkbox + label {
    background-color: #fafafa;
    border: 1px solid #cacece;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
    padding:5px;
    border-radius: 2px;
    display: inline-block;
    position: relative;
  margin:0px 5px 0 2px;
  float:left;
}
.regular-checkbox + label:active, .regular-checkbox:checked + label:active {
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
}
 
.regular-checkbox:checked + label {
    background-color: #e9ecee;
    border: 1px solid #adb8c0;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1);
    color: #99a1a7;
}
 
.regular-checkbox:checked + label:after {
    content: '\2714';
    font-size: 13px;
    position: absolute;
    top:-4px;
    left:1px;
    color: #951419;
}
.external
{
  clear:both;
  margin:10px 0;
  margin-top:30px;
  text-align:center;
}
.external ul
{
  margin-top:20px;
}
.external li
{
  float:left;
  width:23%;
  text-align:center;
}
.external li a
{
  margin:0 5px;
  border:2px solid #222;
  border-radius:14px;
  display:block;
  color:#000;
  text-decoration:none;
  text-transform:uppercase;
  padding:5px 0;
  font-size:13px;
  font-weight:600;
}
span.width31
{
  width:31% !important;
}
span.width33
{
  width:34% !important;
}
span.width35
{
  width:35% !important;
}
span.width33 label, span.width31 label, span.width35 label
{
  float:right;
}
span h3.centertext
{
  text-align: center;
  width: 65% !important;
}



.alertbody
{
  font-family: 'Conv_MyriadPro-Regular' !important;
  color:#262626 !important;
}
.alert
{
  /*width:800px;*/
  height:auto;
  margin:0 auto;
  border:3px solid #F18308;
}
.alert a
{
  text-decoration:none;
  color:#262626;
  display:block;
}
.alert .header
{
  clear:both;
  height:40px;
  background:#F18308;
}
.select_box select
{
  height:30px;
  border-radius:3px;
  margin-left:5px;
  margin-top:4px;
  width:60px;
}
.alert .pageTitle h1
{
  font-size:28px;
  text-align:center;
  line-height:37px;
}
.alert ul
{
  list-style:none;
  padding:0 15px;
}
.alert li
{
  list-style:none;
  margin-bottom:10px;
}
.alert li.red, .alert li.green, .alert li.yellow, .alert li.blue, .alert li.grey, .alert li.purple, .alert li.orange,.alert li.pink
{
  border:1px solid #FF0000;
  border-left:10px solid #FF0000;
  min-height:70px;
  position:relative;
  padding-left:5px;
}
.alert li.grey
{
  border:1px solid #515151;
  border-left:10px solid #515151; 
}
.alert li.blue
{
  border:1px solid #0000FF;
  border-left:10px solid #0000FF; 
}
.alert li.green
{
  border:1px solid #00ff00;
  border-left:10px solid #00ff00; 
}
.alert li.yellow
{
  border:1px solid #FFFF00;
  border-left:10px solid #FFFF00; 
}
.alert li.purple
{
  border:1px solid #bb6d93;
  border-left:10px solid #bb6d93; 
}
.alert li.orange
{
  border:1px solid #ff6700;
  border-left:10px solid #ff6700; 
}
.alert li.pink
{
  border:1px solid #f0147f;
  border-left:10px solid #f0147f; 
}
.alert li.red .date, .alert li.green .date, .alert li.yellow .date, .alert li.blue .date, .alert li.grey .date,.alert li.purple .date, .alert li.orange .date,.alert li.pink .date
{
  font-size:14px;
  color:#454545;
  clear:both;
  padding:5px;
  margin-top:10px;
}
.alert li.red .title, .alert li.green .title, .alert li.yellow .title, .alert li.blue .title, .alert li.grey .title,.alert li.purple .title,.alert li.orange .title,.alert li.pink .title
{
  font-size:17px;
  clear:both;
  padding:5px;
  padding-top:0px;
}
.alert li.red button, .alert li.green button, .alert li.yellow button, .alert li.blue button, .alert li.grey button,.alert li.purple button,.alert li.orange button,.alert li.pink button
{
  border:1px solid rgba(255, 0, 0, .3);
  position:absolute;
  right:5px;
  top:5px;
  padding:5px;
  background:#fff;
  background:url(../images/map.png);
  background:url("../images/map.png") no-repeat scroll 94px 7px rgba(0, 0, 0, 0);
  padding-right:29px;
  cursor:pointer;
  outline:none;
}
.alert li.green button
{
  border:1px solid rgba(0, 255, 0, .3);
}
.alert li.yellow button
{
  border:1px solid rgba(255, 255, 0, .3);
}
.alert li.blue button
{
  border:1px solid rgba(0, 0, 255, .3);
}
.alert li.grey button
{
  border:1px solid rgba(51, 51, 51, .3);
}
.alert li.purple button
{
  border:1px solid rgba(255, 255, 0, .3);
}
.alert li.orange button
{
  border:1px solid rgba(0, 0, 255, .3);
}
.alert li.pink button
{
  border:1px solid rgba(51, 51, 51, .3);
}

/* index css */

 html {
        height: 100%
      }
      body {
        height: 100%;
        margin: 0;
        padding: 0
      }
     
.row
{
  clear:both;
  margin-bottom:40px;
}
.satellite
{
  width:92%;
  height:auto;
  margin:0 auto;
  background-color:#deedf4;
  padding:0 4%;
  padding-top:20px;
  padding-bottom:10px;

}
.left, .right
{
  float:left;
  width:50%;
}
.left
{
  font-size:16px;
  font-weight:bold;
  color:#346178;
}
.left select
{
  background-color:#cddfe7;
  border:1px solid #346178;
  color:#5e5e5e;
  padding:5px 4px;
}
.left a
{
  font-size:16px;
  padding-left:10px;
  padding-right:10px;
  font-weight:normal;
  color:#5e5e5e;
  text-decoration:none;
}
.left span
{
  color:#5e5e5e;
  font-weight:normal;
}
.right
{
  float:right;
  text-align:right;
  color:#5e5e5e;
  font-size:16px;
}
.right span
{
  margin-left:10px;
  color:red;
  font-weight:bold;
}
.right ul
{
  float:right;
  margin-left:10%;
}
.right ul li
{
  float:left;
  line-height:20px;
}
.right ul li:first-child
{
  margin-right:40px;
}
.right ul li input
{
  margin-right:10px;
}
.clearfix:before,.clearfix:after
{
  display:table;content:" "
}
clearfix:after
{
  clear:both;
}


@media (max-width: 640px)
{
  .left, .right
  {
    width:100%;
    text-align:left;
    margin-bottom:10px;
  }
  .right ul
  {
    float:none;
    margin-left:inherit;
  }
}
  </style>
   <script src="../js/jquery-1.7.2.js"></script>
  <script language="Javascript">

  var loopMode = 'play';
  var currentImage = 0;
  var timeOutValue;
  
  var bomImages = Array();
  var localTime = Array();
  
   $(document).ready(function(){
			  $('#map_type').on('change',function() {
					  //alert( this.value );
					  var map_type=this.value;
					  if(map_type=='BOM_AU_IR') 
					  location.href='bom.satellite.php';
					  else 
					   location.href='bsch.satellite.php';
					
				 });
				  
				 $('.image_type').change(function() {
				   //alert(this.value);
						if(this.value=='static')    {
							//document.getElementById("satelliteImage").src = '../images/aus_vis_latest.jpg';
							clearTimeout(timeOutValue);
						} else if(this.value=='Animation') {
							//alert(1);
							loopImages();
						}
				   });
			  
		  });
  
  <?php
  
  $sizeofImages = sizeof($images);
  
  if ($sizeofImages > 0)
    {
    for ($a = 0; $a < sizeof($images); $a++)
      {
      if ($a != 0) { $tab = "  "; }
      echo "{$tab}bomImages[$a] = '{$images[$a]['remoteFilename']}';\r\n";
      echo "{$tab}localTime[$a] = '{$images[$a]['localTimeString']}';\r\n";
      }
    
    echo "\r\n  var lastImage = '".sizeof($images)."';\r\n";
    }
  else
    {
    echo "{$tab}bomImages[0] = 'http://clients.ewn.com.au/common/images/satellite.images.unavailable.png';\r\n";
    echo "{$tab}localTime[0] = 'Offline';\r\n";
    }
    
  ?>
  
  function preloadsatelliteImages()
    {
    var satelliteImages = [];

    for (i = 0; i < bomImages.length; i++)
      {
      satelliteImages[i] = new Image()
      satelliteImages[i].src = bomImages[i];
      }
    }

  function loopImages()
    {
    if (loopMode == 'play')
      {
	    //alert(IsImageOk(bomImages[currentImage]));
           document.getElementById("satelliteImage").src = bomImages[currentImage];
		  $('#satelliteImage')
                .load(function() { console.log("image loaded correctly"); document.getElementById("satelliteTime").innerHTML = localTime[currentImage]; })
                .error(function() { console.log("error loading image"); })
                .attr("src", $('#satelliteImage').attr("src"));
		   
		
	    currentImage++;
      if (currentImage == lastImage) { currentImage = 0; }
      }
    timeOutValue = setTimeout(loopImages, 800);
    }
    
    
  </script>  
  </head>
  <body onLoad="loopImages();">
  
  <div id="container">
    <img id="satelliteImage" src="">
  </div>
  
  <!-- <div id="satelliteTextContainer">
    <div style="float: left; margin-left:10px;"> -->
      <!--<div id="regionLinks">Region: <a href="<?php //echo $_SERVER['PHP_SELF']."?region=1&imageMode={$imageMode}&tz={$tz}"; ?>">Australia</a> - <a href="<?php //echo $_SERVER['PHP_SELF']."?region=12&imageMode={$imageMode}&tz={$tz}"; ?>">West</a> - <a href="<?php //echo $_SERVER['PHP_SELF']."?region=14&imageMode={$imageMode}&tz={$tz}"; ?>">East</a></div>-->
	   <!--<<select name="map_type" id="map_type">
        <option value="BOM_AU_IR" selected>BOM </option>
        <option value="BSCH_GOES_IR/VIS">BSCH </option>
     </select>
    </div>    
    <div style="float: right;">
      <div id="satelliteTimeText">Satellite Image Local Time</div>
      <div id="satelliteTime"></div>
    </div>
    <div style="clear: both;"></div>
  </div>  
 div id="satelliteLinksContainer">
    Image Options <a href="<?php //echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=truecolour&tz={$tz}"; ?>">True Colour</a> | <a href="<?php //echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=falsecolour&tz={$tz}"; ?>">False Colour</a> | <a href="<?php //echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=infrared&tz={$tz}"; ?>">Infrared</a> | <a href="<?php //echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=visible&tz={$tz}"; ?>">Visible</a> | <a href="<?php //echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=colourenhanced&tz={$tz}"; ?>">Color Enhanced</a>
  </div>  
 
  <div id="satelliteTextContainer">
    <div style="float: left; margin-left:5px;">
     Image Options <a href="<?php //echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=infrared&tz={$tz}"; ?>">Infrared</a> | <a href="<?php echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=visible&tz={$tz}"; ?>">Visible</a> 
    </div>    
    <div style="float: right; margin-right:10px;">
      <input type="radio" name="image_type" class="image_type" value="static" checked/>Static  <input type="radio" class="image_type" name="image_type" checked="checked" value="Animation" checked/> Animation 
    </div>
    <div style="clear: both;"></div>
  </div> -->






  <div class="satellite clearfix">
  <div class="row">
        <div class="left">
          <label for="select"></label>
          <select name="map_type" id="map_type">
            <option value="BOM_AU_IR" selected>BOM </option>
        <option value="BSCH_GOES_IR/VIS">BSCH </option>
          </select>
        </div>
        <div class="right">Satellite image Local Time <span id="satelliteTime"></span></div>
    </div>
    <div class="row clearfix">
      <div class="left">Image Options :<a href="<?php echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=infrared&tz={$tz}"; ?>">Infrared</a><span>l<span><a href="<?php echo $_SERVER['PHP_SELF']."?region={$region}&imageMode=visible&tz={$tz}"; ?>">Visible</a> </div>
        <div class="right">
          <ul>
              <li>
                  <input type="radio" name="image_type" class="image_type" value="static" checked/>Static
                </li>
                <li>
                  <input type="radio" class="image_type" name="image_type" checked="checked" value="Animation" checked/> Animation
                </li>
            </ul>
        </div>
    </div>
</div>









  </body>
</html>