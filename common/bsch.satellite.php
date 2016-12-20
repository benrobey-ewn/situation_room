<?php
// Written by Ben Quinn for EWN - November 2012.  benwquinn@gmail.com 0422 188 164
error_reporting(0);
$region='';
if (isset($_GET['tz'])) { $tz = $_GET['tz']; }
if (isset($_GET['imageMode'])) { $imageMode = $_GET['imageMode']; }
if (isset($_GET['region'])) { $region = $_GET['region']; }


// Defaults
if (@!$imageMode) { $imageMode = 'infrared'; }
if (@!$region) { $region = 'aus'; }


$source=array();
$source['infrared']='http://realtime2.bsch.com.au/ir_sat.html?region='.$region.'&loop=yes&images=5&allday=&start=&stop=#nav';
$source['visual']='http://realtime2.bsch.com.au/vis_sat2.html?region='.$region.'&loop=yes&images=5&allday=&start=&stop=#nav';
$source['water_vapour']='http://realtime2.bsch.com.au/wv_sat.html?region='.$region.'&loop=yes&images=5&allday=&start=&stop=#nav';

//echo $source[$imageMode]; die;
$remoteData = file_get_contents($source[$imageMode]);
//print_r($remoteData); die;

   $startAnchor = strpos($remoteData, 'tINm[4]');
   $stopAnchor = strpos($remoteData, 'nImages = 5;');

   $urlsRaw = substr($remoteData, $startAnchor, ($stopAnchor-$startAnchor));
 
   $urlsRaw = str_replace("/tmp/","http://realtime2.bsch.com.au/tmp/", $urlsRaw);
  
  
   preg_match_all("(\"(.*)\")", $urlsRaw, $rawImages);
   
   //print_r($rawImages[1]); die;
   
    //print_r($urlsRaw); die;

   $b = 0;
   
   for ($a = 0; $a < sizeof($rawImages[1]); $a++)
     {
     if (strpos($rawImages[1][$a], ".jpg"))
        {
       // $rawImages[1][$a] = str_replace($replacementHook, $imageModeReplacement, $rawImages[1][$a]);
        $satelliteImages[$b]['remoteFilename'] = $rawImages[1][$a];
        $satelliteImages[$b]['localTimeString'] = "Test";

       /* $utcTime[$a] = str_replace(".jpg", "", substr($rawImages[1][$a], -16));
        $satelliteImages[$b]['localTimeString'] = date("g:ia", gmmktime(substr($utcTime[$a],8,2),substr($utcTime[$a],10,2),0,substr($utcTime[$a],4,2),substr($utcTime[$a],6,2),substr($utcTime[$a],0,4)));
        $satelliteImages[$b]['localTimeUnix'] = (gmmktime(substr($utcTime[$a],8,2),substr($utcTime[$a],10,2),0,substr($utcTime[$a],4,2),substr($utcTime[$a],6,2),substr($utcTime[$a],0,4)));*/
        $b++;
        }
     }   
//echo '<pre>';
//print_r($satelliteImages);
$images = array_slice($satelliteImages, 0);
//print_r($images);



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
  var currentImage = 4;
  var timeOutValue;
  
  var bomImages = Array();
  var localTime = Array();
  
   $(document).ready(function(){
                          $('.option1').attr("checked",false);
                            $('.option2').attr("checked",true);
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
						   document.getElementById("satelliteImage").src = bomImages[0];
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
                .load(function() { //console.log("image loaded correctly"); //document.getElementById("satelliteTime").innerHTML = localTime[currentImage]; 
				})
                .error(function() { //console.log("error loading image");
                       })
                .attr("src", $('#satelliteImage').attr("src"));
		   
		
	    currentImage--;
      if (currentImage == -1) { currentImage = 4;}
      }
    timeOutValue = setTimeout(loopImages, 800);
    }
    
    
  </script>  
  </head>
  <body onLoad="loopImages();">
  
  <div id="container">
    <img src="" alt="" name="satelliteImage" usemap="#satelliteImageMap" id="satelliteImage" border="0" style="height:900px;">
	<?php if(((isset($_REQUEST['imageMode']) && $_REQUEST['imageMode']=='visual')) || (isset($_REQUEST['region']) && $_REQUEST['region']=='aus')) { ?>
  <map name="satelliteImageMap">
    <area shape="POLY" coords="376,522,365,529,351,534,337,537,325,536,315,539,301,546,287,552,279,557,274,568,269,574,263,575,247,575,240,575,233,571,221,571,205,574,199,574,190,582,188,587,178,588,174,592,167,597,161,600,151,600,139,600,129,596,123,594,117,587,108,583,107,581,105,570,108,565,112,562,116,558,116,548,119,539,118,528,115,514,110,502,106,491,104,478,101,468,100,460,95,447,88,438,86,427,79,414,79,407,76,388,81,389,85,404,90,407,91,395,88,391,85,383,78,371,76,362,77,354,83,342,82,332,81,323,84,313,91,308,90,319,93,324,96,319,98,311,120,300,124,294,133,288,140,283,149,284,157,286,165,282,170,280,179,278,184,272,192,272,197,273,206,269,211,267,221,263,226,258,229,253,235,245,244,236,246,230,245,224,246,214,250,209,259,199,262,202,264,208,268,220,271,218,274,211,274,207,272,205,270,199,271,194,280,199,288,198,287,191,287,186,295,177,300,173,303,169,302,165,312,161,317,163,321,153,325,153,333,152,335,149,345,149,352,158,357,165,360,167,360,176,362,175,364,168,373,169,377,170,375,474,376,522" title="Western Australia" alt="Western Australia" href="bsch.satellite.php?region=wa&loop=&images=&allday=&start=&stop=">
    <area shape="poly" coords="375,397,375,524,398,520,412,520,418,519,430,523,441,529,445,529,455,533,459,533,465,533,470,539,477,543,474,552,480,557,485,558,489,566,494,572,498,582,499,591,505,597,512,595,511,587,516,579,522,573,532,569,536,561,543,553,546,544,545,540,549,554,547,559,548,566,542,571,539,580,540,588,536,596,532,597,526,601,528,606,539,602,543,600,546,589,549,582,554,585,558,594,557,602,553,612,551,614,542,614,533,612,523,617,527,625,540,624,549,619,556,613,565,612,572,616,582,629,586,640,582,646,584,653,592,665,599,671,607,673,608,671,606,669,605,576,607,576,608,397,608,397,375,397" title="South Australia" alt="South Australia" href="bsch.satellite.php?region=sa&loop=&images=&allday=&start=&stop=">
    <area shape="poly" coords="686,739,685,739,678,740,678,746,683,765,691,778,689,784,693,796,701,806,705,811,710,812,718,814,724,810,729,807,733,799,739,802,740,804,740,796,738,789,742,777,745,773,747,766,746,747,743,739,733,742,724,746,716,747,709,750,703,746,691,738,688,738,685,740,686,739" title="Tasmania" alt="Tasmania" href="bsch.satellite.php?region=tas&loop=&images=&allday=&start=&stop=">
    <area shape="poly" coords="606,577,606,673,613,678,617,680,623,676,631,680,639,684,650,689,655,691,665,685,674,679,687,683,693,684,701,688,703,692,710,697,713,689,721,685,726,681,733,673,745,667,756,666,765,665,773,663,775,657,771,654,746,643,742,632,741,625,738,622,730,623,722,625,711,623,703,622,694,619,686,619,684,623,680,627,672,617,664,609,655,605,651,601,647,593,642,591,637,594,633,589,632,582,626,577,619,580,608,578,606,577" title="Victoria" alt="Victoria" href="bsch.satellite.php?region=vic&loop=&images=&allday=&start=&stop=">
      <area shape="poly" coords="606,462,606,576,616,581,622,578,627,579,633,586,636,591,638,594,641,589,646,594,651,596,651,600,653,604,659,610,664,614,673,621,677,624,682,623,689,620,693,620,700,623,707,624,714,623,718,625,725,626,731,624,739,625,743,631,746,638,748,643,757,648,763,652,773,656,778,659,778,650,779,643,781,631,782,620,786,614,788,606,793,596,794,592,798,586,799,577,804,569,807,561,812,555,815,550,821,546,829,538,830,531,836,518,838,509,838,500,841,490,843,478,845,470,848,461,850,454,848,444,835,447,828,448,818,450,818,459,811,462,807,465,804,459,796,454,790,455,784,454,774,452,768,455,763,459,758,462,759,462,606,462" title="New South Wales" alt="New South Wales" href="bsch.satellite.php?region=nsw&loop=&images=&allday=&start=&stop=">
      
       <area shape="poly" coords="376,397,550,397,549,202,542,194,535,192,529,190,526,188,522,186,512,179,506,174,500,167,505,156,509,151,510,144,510,136,518,133,519,137,521,130,527,119,523,114,516,116,507,115,501,111,494,115,485,112,481,112,471,109,459,106,454,103,452,98,447,103,445,113,435,116,426,116,420,116,413,115,410,122,405,125,400,129,400,135,397,138,392,140,388,150,384,156,385,162,389,165,387,173,386,175,383,169,378,171,375,169,376,397" title="Northern Territory" alt="Northern Territory" href="bsch.satellite.php?region=nt&loop=&images=&allday=&start=&stop=">
       <area shape="poly" coords="549,398,549,397,607,397,608,462,761,463,769,454,779,454,784,453,788,455,794,455,801,458,806,465,807,464,812,461,817,462,819,458,818,452,826,448,831,448,840,448,845,446,848,445,843,433,840,426,840,417,839,398,837,389,832,380,827,374,817,363,814,356,804,354,802,345,795,342,795,324,780,318,780,325,771,321,771,312,770,301,764,292,754,285,758,278,752,272,744,270,735,266,730,257,721,256,713,251,709,236,704,230,705,216,697,204,693,196,690,181,690,169,681,162,676,156,672,156,666,159,661,155,656,141,655,124,648,117,646,110,642,107,641,96,637,87,627,97,624,112,620,125,619,137,617,163,617,180,613,193,605,216,597,225,587,225,573,217,566,208,555,205,549,202,549,397,549,398" title="Queensland" alt="Queensland" href="bsch.satellite.php?region=qld&loop=&images=&allday=&start=&stop=">
  </map>
  <?php } ?>
  </div>
  






  <div class="satellite clearfix">
  <div class="row">
        <div class="left">
          <label for="select"></label>
          <select name="map_type" id="map_type">
        <option value="BOM_AU_IR">BOM </option>
        <option value="BSCH_GOES_IR/VIS" selected="selected">BSCH </option>
          </select>
        </div>
        <div class="right"><!--Satellite image Local Time <span id="satelliteTime"></span>--></div>
    </div>
<div class="row">
     <!-- <div id="satelliteTimeText">Satellite Image Local Time</div>
      <div id="satelliteTime"></div>-->
     <?php if(((isset($_REQUEST['imageMode']) && $_REQUEST['imageMode']=='visual')) || (isset($_REQUEST['region']) && $_REQUEST['region']!=='')) { ?>
      <table>
      <tr>
      <td align="left" style="padding:5px 0px 5px 0px;">
        <font class="heading">States</font>&nbsp;&nbsp; <a onClick="" href="bsch.satellite.php?imageMode=<?php echo $imageMode ?>&region=aus&loop=&images=&allday=&start=&stop=">AUS </a> | <a onClick="" href="bsch.satellite.php?imageMode=<?php echo $imageMode ?>&region=nt&loop=&images=&allday=&start=&stop=">NT</a> | <a onClick="" href="bsch.satellite.php?imageMode=<?php echo $imageMode ?>&region=qld&loop=&images=&allday=&start=&stop=">QLD</a> | <a onClick="" href="bsch.satellite.php?imageMode=<?php echo $imageMode ?>&region=nsw&loop=&images=&allday=&start=&stop=">NSW</a> | <a onClick="" href="bsch.satellite.php?imageMode=<?php echo $imageMode ?>&region=vic&loop=&images=&allday=&start=&stop=">VIC</a> | <a onClick="" href="bsch.satellite.php?imageMode=<?php echo $imageMode ?>&region=tas&loop=&images=&allday=&start=&stop=">TAS</a> | <a onClick="" href="bsch.satellite.php?imageMode=<?php echo $imageMode ?>&region=sa&loop=&images=&allday=&start=&stop=">SA</a> | <a onClick="" href="bsch.satellite.php?imageMode=<?php echo $imageMode ?>&region=wa&loop=&images=&allday=&start=&stop=">WA</a></td>
      </tr>
      </table>
      <?php } ?>
    </div>

    <div class="row">
      <div class="left">Image Options :<a href="<?php echo $_SERVER['PHP_SELF']."?imageMode=infrared"; ?>">Infrared</a><span>l<span><a href="<?php echo $_SERVER['PHP_SELF']."?imageMode=visual"; ?>">Visible</a><span>l<span><a href="<?php echo $_SERVER['PHP_SELF']."?imageMode=water_vapour"; ?>"> Water Vapour</a>  </div>
        <div class="right">
          <ul>
              <li>
                  <input type="radio" name="image_type" class="image_type option1" value="static"/>Static
                </li>
                <li>
                 <input type="radio" class="image_type option2" name="image_type" checked="checked" value="Animation" checked/> Animation 
                </li>
            </ul>
        </div>
    </div>
</div>




























  </body>
</html>