<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="../js/jquery-1.7.2.js"></script>

<?php
error_reporting(0);
$tab = '';

if (isset($_GET['radar']))
{
  $radar = $_GET['radar'];
}

if (!isset($radar))
{
  die("No radar specified");
}

$radarTimezone['71'] = 'Australia/Sydney';
$radarTimezone['03'] = 'Australia/Sydney';
$radarTimezone['40'] = 'Australia/Sydney';
$radarTimezone['28'] = 'Australia/Sydney';
$radarTimezone['30'] = 'Australia/Sydney';
$radarTimezone['53'] = 'Australia/Sydney';
$radarTimezone['69'] = 'Australia/Sydney';
$radarTimezone['04'] = 'Australia/Sydney';
$radarTimezone['55'] = 'Australia/Sydney';
$radarTimezone['49'] = 'Australia/Sydney';
$radarTimezone['62'] = 'Australia/Sydney';
$radarTimezone['02'] = 'Australia/Melbourne';
$radarTimezone['68'] = 'Australia/Melbourne';
$radarTimezone['30'] = 'Australia/Melbourne';
$radarTimezone['49'] = 'Australia/Melbourne';
$radarTimezone['14'] = 'Australia/Melbourne';
$radarTimezone['66'] = 'Australia/Brisbane';
$radarTimezone['50'] = 'Australia/Brisbane';
$radarTimezone['24'] = 'Australia/Brisbane';
$radarTimezone['19'] = 'Australia/Brisbane';
$radarTimezone['72'] = 'Australia/Brisbane';
$radarTimezone['23'] = 'Australia/Brisbane';
$radarTimezone['08'] = 'Australia/Brisbane';
$radarTimezone['56'] = 'Australia/Brisbane';
$radarTimezone['22'] = 'Australia/Brisbane';
$radarTimezone['36'] = 'Australia/Brisbane';
$radarTimezone['73'] = 'Australia/Brisbane';
$radarTimezone['67'] = 'Australia/Brisbane';
$radarTimezone['18'] = 'Australia/Brisbane';
$radarTimezone['41'] = 'Australia/Brisbane';
$radarTimezone['75'] = 'Australia/Brisbane';
$radarTimezone['70'] = 'Australia/Perth';
$radarTimezone['31'] = 'Australia/Perth';
$radarTimezone['17'] = 'Australia/Perth';
$radarTimezone['05'] = 'Australia/Perth';
$radarTimezone['15'] = 'Australia/Perth';
$radarTimezone['32'] = 'Australia/Perth';
$radarTimezone['45'] = 'Australia/Perth';
$radarTimezone['06'] = 'Australia/Perth';
$radarTimezone['44'] = 'Australia/Perth';
$radarTimezone['39'] = 'Australia/Perth';
$radarTimezone['48'] = 'Australia/Perth';
$radarTimezone['29'] = 'Australia/Perth';
$radarTimezone['16'] = 'Australia/Perth';
$radarTimezone['07'] = 'Australia/Perth';
$radarTimezone['64'] = 'Australia/Adelaide';
$radarTimezone['46'] = 'Australia/Adelaide';
$radarTimezone['33'] = 'Australia/Adelaide';
$radarTimezone['45'] = 'Australia/Adelaide';
$radarTimezone['44'] = 'Australia/Adelaide';
$radarTimezone['30'] = 'Australia/Adelaide';
$radarTimezone['14'] = 'Australia/Adelaide';
$radarTimezone['27'] = 'Australia/Adelaide';
$radarTimezone['52'] = 'Australia/Hobart';
$radarTimezone['37'] = 'Australia/Hobart';
$radarTimezone['63'] = 'Australia/Darwin';
$radarTimezone['25'] = 'Australia/Darwin';
$radarTimezone['44'] = 'Australia/Darwin';
$radarTimezone['09'] = 'Australia/Darwin';
$radarTimezone['42'] = 'Australia/Darwin';
$radarTimezone['65'] = 'Australia/Darwin';
$radarTimezone['07'] = 'Australia/Darwin';
$radarTimezone['77'] = 'Australia/Darwin';

if ($radarTimezone[substr($radar,0,2)]) {
  date_default_timezone_set($radarTimezone[substr($radar,0,2)]);
  }
else {
  date_default_timezone_set('Australia/Brisbane'); 
} 

$images = getRadarImages($radar);

function getRadarImages($radar)
   {
   global $basedir;
   global $crop_data;
   global $plusUTCHours;

   $rawImages = Array();
   $images = Array();
   $imagesPointer = 0;
   $rand = rand(1, 1000);
  
   $remoteData = file_get_contents("http://www.bom.gov.au/products/IDR".$radar.".loop.shtml?cache=$rand", NULL, NULL, 0, 25000);
   /*theImageNames = new Array();
theImageNames[0] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161136.png";
theImageNames[1] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161142.png";
theImageNames[2] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161148.png";
theImageNames[3] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161154.png";
theImageNames[4] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161200.png";
theImageNames[5] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161206.png";
nImages = 6;
Km = 128;*/

   $startAnchor = strpos($remoteData, 'theImageNames = new Array();');
   $stopAnchor = strpos($remoteData, 'Km = ');

   $urlsRaw = substr($remoteData, $startAnchor, ($stopAnchor-$startAnchor));
    /*theImageNames = new Array(); theImageNames[0] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161142.png"; theImageNames[1] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161148.png"; theImageNames[2] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161154.png"; theImageNames[3] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161200.png"; theImageNames[4] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161206.png"; theImageNames[5] = "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161212.png"; nImages = 6; */
    //die;

   if (!strstr($urlsRaw, 'edge'))
   {
     $urlsRaw = str_replace("/radar/","http://www.bom.gov.au/radar/", $urlsRaw);
   }
   
   preg_match_all("(\"(.*)\")", $urlsRaw, $rawImages);

 /*Array
(
    [0] => Array
        (
            [0] => "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161148.png"
            [1] => "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161154.png"
            [2] => "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161200.png"
            [3] => "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161206.png"
            [4] => "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161212.png"
            [5] => "http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161218.png"
        )

    [1] => Array
        (
            [0] => http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161148.png
            [1] => http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161154.png
            [2] => http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161200.png
            [3] => http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161206.png
            [4] => http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161212.png
            [5] => http://wac.72DD.edgecastcdn.net/8072DD/radimg/radar/IDR713.T.201410161218.png
        )

)*/
   
   for ($a = 0; $a < sizeof($rawImages[1]); $a++)
     {
     if (strpos($rawImages[1][$a], "IDR".$radar.".T"))
        {
          $images[$imagesPointer]['remoteFilename'] = $rawImages[1][$a];
          $images[$imagesPointer]['localFilename'] = substr($rawImages[1][$a], -16);   ////201410161148.png
          $utcTime[$a] = str_replace(".png", "", substr($rawImages[1][$a], -16));
          //substr($utcTime[$a],8,2); //// 9 and 10 word
          //echo substr($utcTime[$a],8,2),substr($utcTime[$a],10,2),0,substr($utcTime[$a],4,2),substr($utcTime[$a],6,2),substr($utcTime[$a],0,4);
          // 1200010162014 die;

          $images[$imagesPointer]['localTimeString'] = date("g:ia", gmmktime(substr($utcTime[$a],8,2),substr($utcTime[$a],10,2),0,substr($utcTime[$a],4,2),substr($utcTime[$a],6,2),substr($utcTime[$a],0,4)));
          $images[$imagesPointer]['localTimeUnix'] = (gmmktime(substr($utcTime[$a],8,2),substr($utcTime[$a],10,2),0,substr($utcTime[$a],4,2),substr($utcTime[$a],6,2),substr($utcTime[$a],0,4)));
          $imagesPointer++;   
        }
     }   

   return $images;

}


?>
<html>
  <head>
  <title>Radar Loop</title>
  <style type="text/css">
  
  *
    {
    font-family: Arial, Helvetica, Sans-serif;
    }
  
  body, html 
    {
    margin: 0px;
    background-color: white;
    }
    
  .clearFloat
    {
    clear: both;
    }
    
  #container
    {
    width: 512px;
    height: 512px;
    position: relative;
    display: block;
    z-index: 10;
    }
    
  .overlay
    {
    position: absolute;
    z-index: 5;
    }
    
  #radarImage
    {
    position: absolute;
    z-index: 10;
    border-bottom: 1px solid grey;
    }
  
  #radarLinksContainer
    {
    float: left;
    padding: 8px 0px 0px 5px;
    text-align: left;
    width: 48%;
    }
    
  #radarTextContainer
    {
    float: right;
    padding: 8px 0px 0px 0px;
    text-align: right;
    width: 48%;
    }
    
  #radarText
    {
    display: inline;
    text-align: right;
    width: auto;
    }
    
  #radarTime
    {
    position: relative;
    display: inline;
    min-width: 65px;
    width: 65px;
    text-align: right;
    color: black;
    font-weight: bold;
    color: red;
    font-size: 100%;
    padding-right: 5px;
    }
  
  </style>
  <script type="text/javascript" >


    var radarLink1 = document.getElementById("radarLink1");
    var radarLink2 = document.getElementById("radarLink2");
    var radarLink3 = document.getElementById("radarLink3");

        $(document).ready(function()
        {
          $( "#radarLink1" ).click(function() {
           radarScaleLinks('radarLink1');
         });


          $( "#radarLink2" ).click(function() {
           radarScaleLinks('radarLink2');
         });
      


          $( "#radarLink3" ).click(function() {
           radarScaleLinks('radarLink3');
         });
      
      });

          function radarScaleLinks(radarLink1)
      {
        var id = radarLink1;
        var radarScale = id.replace("radarLink", "");
        var radarID = '<?php echo $_GET['radar'] ?>'.substr(0,2);
        var radarFull = radarID + radarScale;
        window.location.href='radar.php?radar='+radarFull; 
        return false;
      }

  var loopMode = 'play';
  var currentImage = 0;
  var timeOutValue;
  
  var bomImages = Array();
  var localTime = Array();
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
    echo "{$tab}bomImages[0] = 'http://clients.ewn.com.au/common/images/radar.offline.png';\r\n";
    echo "{$tab}localTime[0] = 'Offline';\r\n";
    echo "var lastImage = 0;\r\n";
    }
    
  ?>
  
  function preloadRadarImages()
    {
    var radarImages = [];

    for (i = 0; i < bomImages.length; i++)
      {
      radarImages[i] = new Image()
      radarImages[i].src = bomImages[i];
      }
    }

  function loopImages()
    {
    if (loopMode == 'play')
      {
      document.getElementById("radarImage").src = bomImages[currentImage];
      document.getElementById("radarTime").innerHTML = localTime[currentImage];
      currentImage++;
      if (currentImage == lastImage) { currentImage = 0; }
      }
    timeOutValue = setTimeout(loopImages, 650);
    }
    
  function initiateLoop()
    {
    if (lastImage == 0) {
      document.getElementById("radarImage").src = bomImages[0];
    }
    else {
      loopImages();    
    }
  }
    
  </script>  
  </head>
  <body onLoad="initiateLoop();">
  
  <div id="container">
    <img class="overlay" src="http://www.bom.gov.au/products/radar_transparencies/IDR<?php echo $radar; ?>.background.png">
    <img class="overlay" src="http://www.bom.gov.au/products/radar_transparencies/IDR<?php echo $radar; ?>.topography.png">
    <img class="overlay" src="http://www.bom.gov.au/products/radar_transparencies/IDR<?php echo $radar; ?>.range.png">
    <img class="overlay" src="http://www.bom.gov.au/products/radar_transparencies/IDR<?php echo $radar; ?>.locations.png">
    <img id="radarImage" src="">
  </div>
  
  <div id="radarLinksContainer">
    <a id="radarLink3" href="javascript:void(0);">128km</a> | <a id="radarLink2" href="javascript:void(0);">256km</a> | <a id="radarLink1" href="javascript:void(0);">512km</a>
  </div>  
  <div id="radarTextContainer">
    <div id="radarText">Radar Image Local Time</div>
    <div id="radarTime"></div>
  </div>  
  <div class="clearFloat"></div>
  
  <script>
   /* 
    var radarLink1 = document.getElementById("radarLink1");
    var radarLink2 = document.getElementById("radarLink2");
    var radarLink3 = document.getElementById("radarLink3");
    
    if (radarLink1.addEventListener) { radarLink1.addEventListener("click", window.parent.radarScaleLinks, false); }
    else { radarLink1.attachEvent("click", window.parent.radarScaleLinks); }
    
    if (radarLink2.addEventListener) { radarLink2.addEventListener("click", window.parent.radarScaleLinks, false); }
    else { radarLink2.attachEvent("click", window.parent.radarScaleLinks); }
    
    if (radarLink3.addEventListener) { radarLink3.addEventListener("click", window.parent.radarScaleLinks, false); }
    else { radarLink3.attachEvent("click", window.parent.radarScaleLinks); }*/
    
  </script>
  </body>
</html>