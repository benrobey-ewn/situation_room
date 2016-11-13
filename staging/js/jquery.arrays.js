// This example creates a custom overlay called USGSOverlay, containing
// a U.S. Geological Survey (USGS) image of the relevant area on the map.

// Set the custom overlay object's prototype to a new instance
// of OverlayView. In effect, this will subclass the overlay class.
// Note that we set the prototype to an instance, rather than the
// parent class itself, because we do not wish to modify the parent class.
/* used in hide show */
var marker = new Array();
var radarNumber;
var status = 'off';      
var map, rio1, rio2;
var minimumSeconds = 180; // 3 minutes from radartime stamp to earliest availability.
var updateInterval = 1*60*1000; // 1 Minute
var quickUpdateInterval = 3*1000; // 3 seconds
var maxLoadOffset = 6;
var currentRange = -1;
var defaultRange = 128;
var borderStyle = "0px solid white";
var fdOverlay = null;
var showForecastDistricts=true;
var fireOverlay = null;
var showFireDistricts=false;
var showMarkers;
var currentRadarStatus = 'on';
var observationsStatus = '-';
var onColour = "#FFFFFF";
var offColour = "red";
var blinkOnOff = 1;
var blinkSpeed= 1000;
var pageLoadedWithoutRadarOn;
var cookiePrefix = 'AA_';
var defaultRadar = 713;



var radars1 = new Array(
['IDR553','Wagga Wagga',146.063377,-36.321769,148.876623,-34.018231,10,2],
['IDR533','Moree',148.528886,-30.651769,151.171114,-28.348231,10,2],
['IDR403','Canberra',148.094797,-36.811769,150.925203,-34.508231,10,2],
['IDR043','Newcastle',150.660141,-33.881769,153.393859,-31.578231,10,2],
['IDR693','Namoi',148.918200,-32.177269,151.601800,-29.873731,10,2],
['IDR283','Grafton',151.647315,-30.771769,154.292685,-28.468231,10,2],
['IDR713','Sydney',149.827889,-34.852769,152.592111,-32.549231,6,2],
['IDR033','Sydney (Appin)',149.478770,-35.411769,152.261230,-33.108231,10,2],

['IDR243','Bowen',146.857296,-21.031769,149.302704,-18.728231,10,7],
['IDR233','Gladstone',150.002708,-25.011769,152.517292,-22.708231,10,7],
['IDR083','Gympie',151.301013,-27.121769,153.858987,-24.818231,10,7],
['IDR193','Cairns',144.478770,-17.971769,146.881230,-15.668231,10,7],
['IDR563','Longreach',143.036833,-24.581769,145.543167,-22.278231,10,7],
['IDR223','Mackay',147.987362,-22.271769,150.452638,-19.968231,10,7],
['IDR363','Mornington Island',137.969716,-17.821769,140.370284,-15.518231,10,7],
['IDR733','Townsville',145.330796,-20.571769,147.769204,-18.268231,10,7],
['IDR673','Warrego',146.065839,-27.591769,148.634161,-25.288231,10,7],
['IDR183','Weipa',140.741463,-13.821769,143.098537,-11.518231,10,7],
['IDR503','Marburg',151.242392,-28.761769,153.837608,-26.458231,10,7],
['IDR723','Emerald',146.984693,-24.701569,149.493307,-22.398031,10,7],
['IDR663','Brisbane',151.941110,-28.869769,154.538890,-26.566231,6,7],
['IDR753','Mount Isa',138.325695,-21.865769,140.784305,-19.562231,6,7],

['IDR303','Mildura',140.689266,-35.381769,143.470734,-33.078231,10,1],
['IDR683','Bairnsdale',146.103015,-39.041769,149.016985,-36.738231,10,1],
['IDR493','Yarrawonga',144.608179,-37.181769,147.451821,-34.878231,10,1],
['IDR023','Melbourne',143.293806,-39.001769,146.206194,-36.698231,6,1],

['IDR523','West Takone',144.051245,-42.332769,147.106755,-40.029231,10,4],
['IDR763','Hobart (Mt Koonya)',146.265485,-44.241769,149.414515,-41.938231,6,4],

['IDR333','Ceduna',132.342206,-33.281769,135.057794,-30.978231,10,5],
['IDR143','Mt.Gambier',139.315776,-38.901769,142.224224,-36.598231,10,5],
['IDR273','Woomera',135.456299,-32.311769,138.143701,-30.008231,10,5],
['IDR463','Adelaide',137.090598,-36.481769,139.909402,-34.178231,10,5],
['IDR643','Buckland Park',137.071814,-35.768769,139.866186,-33.465231,6,5],

['IDR313','Albany',116.397162,-36.101769,119.202838,-33.798231,10,3],
['IDR173','Broome',121.021329,-19.101769,123.438671,-16.798231,10,3],
['IDR053','Carnarvon',112.402528,-26.031769,114.937472,-23.728231,10,3],
['IDR153','Dampier',115.461214,-21.801769,117.918786,-19.498231,10,3],
['IDR323','Esperance',120.505807,-34.981769,123.274193,-32.678231,10,3],
['IDR063','Geraldton',113.387857,-29.951769,116.012143,-27.648231,10,3],
['IDR443','Giles',127.030982,-26.181769,129.569018,-23.878231,10,3],
['IDR393','Halls Creek',126.449398,-19.381769,128.870602,-17.078231,10,3],
['IDR483','Kalgoorlie-Boulder',120.111498,-31.941769,122.788502,-29.638231,10,3],
['IDR293','Learmonth',112.758981,-23.251769,115.241019,-20.948231,10,3],
['IDR703','Perth (Serpentine)',114.508311,-33.541769,117.231689,-31.238231,10,7],
['IDR163','Port Hedland',117.403458,-21.521769,119.856542,-19.218231,10,3],
['IDR073','Wyndham',126.927051,-16.601769,129.312949,-14.298231,10,3],

['IDR253','Alice Springs',132.643096,-24.971769,135.156904,-22.668231,10,8],
['IDR093','Gove',135.643236,-13.431769,137.996764,-11.128231,10,8],
['IDR423','Katherine/Tindal',131.262277,-15.661769,133.637723,-13.358231,10,8],
['IDR653','Tennant Creek',132.959134,-20.791769,135.400866,-18.488231,10,8],
['IDR633','Darwin/Berrimah',129.752425,-13.611769,132.107575,-11.308231,6,8],
['IDR773','Warruwi',132.205977,-12.801169,134.554023,-10.497631,6,8]
);




var radars2 = new Array(
['IDR552','Wagga Wagga',144.656753,-37.473539,150.283247,-32.866461,10,2],
['IDR532','Moree',147.207771,-31.803539,152.492229,-27.196461,10,2],
['IDR402','Canberra',146.679594,-37.963539,152.340406,-33.356461,10,2],
['IDR042','Newcastle',149.293283,-35.033539,154.760717,-30.426461,10,2],
['IDR692','Namoi',147.576400,-33.329039,152.943600,-28.721961,10,2],
['IDR282','Grafton',150.324631,-31.923539,155.615369,-27.316461,10,2],
['IDR712','Sydney',148.445778,-36.004539,153.974222,-31.397461,6,2],
['IDR032','Sydney (Appin)',148.087541,-36.563539,153.652459,-31.956461,10,2],

['IDR242','Bowen',145.634592,-22.183539,150.525408,-17.576461,10,7],
['IDR232','Gladstone',148.745416,-26.163539,153.774584,-21.556461,10,7],
['IDR082','Gympie',150.022026,-28.273539,155.137974,-23.666461,10,7],
['IDR192','Cairns',143.277540,-19.123539,148.082460,-14.516461,10,7],
['IDR562','Longreach',141.783665,-25.733539,146.796335,-21.126461,10,7],
['IDR222','Mackay',146.754723,-23.423539,151.685277,-18.816461,10,7],
['IDR362','Mornington Island',136.769432,-18.973539,141.570568,-14.366461,10,7],
['IDR732','Townsville',144.111592,-21.723539,148.988408,-17.116461,10,7],
['IDR672','Warrego',144.781677,-28.743539,149.918323,-24.136461,10,7],
['IDR182','Weipa',139.562925,-14.973539,144.277075,-10.366461,10,7],
['IDR502','Marburg',149.944785,-29.913539,155.135215,-25.306461,10,7],
['IDR722','Emerald',145.730387,-25.853339,150.747613,-21.246261,10,7],
['IDR662','Brisbane',150.642219,-30.021539,155.837781,-25.414461,6,7],
['IDR752','Mount Isa',137.096391,-23.017539,142.013609,-18.410461,6,7],

['IDR302','Mildura',139.298532,-36.533539,144.861468,-31.926461,10,1],
['IDR682','Bairnsdale',144.646031,-40.193539,150.473969,-35.586461,10,1],
['IDR492','Yarrawonga',143.186358,-38.333539,148.873642,-33.726461,10,1],
['IDR022','Melbourne',141.837613,-40.153539,147.662387,-35.546461,6,1],

['IDR522','West Takone',142.523489,-43.484539,148.634511,-38.877461,10,4],
['IDR762','Hobart (Mt Koonya)',144.690969,-45.393539,150.989031,-40.786461,6,4],

['IDR332','Ceduna',130.984411,-34.433539,136.415589,-29.826461,10,5],
['IDR142','Mt.Gambier',137.861553,-40.053539,143.678447,-35.446461,10,5],
['IDR272','Woomera',134.112598,-33.463539,139.487402,-28.856461,10,5],
['IDR462','Adelaide',135.681196,-37.633539,141.318804,-33.026461,10,5],
['IDR642','Buckland Park',135.674627,-36.920539,141.263373,-32.313461,6,5],

['IDR312','Albany',114.994324,-37.253539,120.605676,-32.646461,10,3],
['IDR172','Broome',119.812659,-20.253539,124.647341,-15.646461,10,3],
['IDR052','Carnarvon',111.135055,-27.183539,116.204945,-22.576461,10,3],
['IDR152','Dampier',114.232427,-22.953539,119.147573,-18.346461,10,3],
['IDR322','Esperance',119.121614,-36.133539,124.658386,-31.526461,10,3],
['IDR062','Geraldton',112.075714,-31.103539,117.324286,-26.496461,10,3],
['IDR442','Giles',125.761965,-27.333539,130.838035,-22.726461,10,3],
['IDR392','Halls Creek',125.238797,-20.533539,130.081203,-15.926461,10,3],
['IDR482','Kalgoorlie-Boulder',118.772995,-33.093539,124.127005,-28.486461,10,3],
['IDR292','Learmonth',111.517962,-24.403539,116.482038,-19.796461,10,3],
['IDR702','Perth (Serpentine)',113.146622,-34.693539,118.593378,-30.086461,10,7],
['IDR162','Port Hedland',116.176916,-22.673539,121.083084,-18.066461,10,3],
['IDR072','Wyndham',125.734103,-17.753539,130.505897,-13.146461,10,3],

['IDR252','Alice Springs',131.386192,-26.123539,136.413808,-21.516461,10,8],
['IDR092','Gove',134.466472,-14.583539,139.173528,-9.976461,10,8],
['IDR422','Katherine/Tindal',130.074554,-16.813539,134.825446,-12.206461,10,8],
['IDR652','Tennant Creek',131.738269,-21.943539,136.621731,-17.336461,10,8],
['IDR632','Darwin/Berrimah',128.574850,-14.763539,133.285150,-10.156461,6,8],
['IDR772','Warruwi',131.031955,-13.952939,135.728045,-9.345861,6,8] 
);

var states = ["", 
        "Victoria",
        "New South Wales",
        "Western Australia",
        "Tasmania",
        "South Australia",
        "Australian Capital Territory",
        "Queensland",
        "Northern Territory"
        ];

function toggleRadarMarkers() {
  // This line simply toggles between true and false - very clever!
  showMarkers = !showMarkers;
  if (currentRange == 128)
  {
    if (showMarkers)
    {
      rio1.showRadarMarkers();
      $.cookie(cookiePrefix + "RM", 'on', { expires: 365});
  }
  else
  {
      rio1.hideRadarMarkers();
      $.cookie(cookiePrefix + "RM", 'off', { expires: 365});
  }
}
else
{ 
    if (showMarkers)
    {
      rio2.showRadarMarkers();
      $.cookie(cookiePrefix + "RM", 'on', { expires: 365});
  }
  else
  {
      rio2.hideRadarMarkers();
      $.cookie(cookiePrefix + "RM", 'off', { expires: 365});
  }
}
}


    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    // http://code.google.com/apis/maps/documentation/overlays.html#Custom_Overlays
    function RadarImageOverlay(map, radars, minimumSeconds, updateInterval, opacity) {
      console.log(this);
      this.map = map;
      this.setMap(map);
      this.radars = radars; // All available radars.
      this.minimumSeconds = minimumSeconds;
      this.updateInterval = updateInterval;
      this.percentOpacity = opacity;
      this.radarOverlays = []; // Currently shown.
      this.stopped = false;
      this.updateTimer = null;
      this.markers = null;
  }