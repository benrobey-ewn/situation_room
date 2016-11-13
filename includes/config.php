<?php

// server should keep session data for AT LEAST 1 hour
error_reporting(E_ALL);

session_start();
ob_start();
ini_set('session.gc_maxlifetime', 30*60*60); // 30 hours
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(30*60*60);

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_DATABASE', 'situatio_product');
define('SR_VER',date("Ymd")."-1");
$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
mysql_select_db(DB_DATABASE,$con);
mysql_set_charset("utf8");

define('HOST','http://situationroom.ewn.com.au');

define('ROOT',$_SERVER['DOCUMENT_ROOT']);

define('ROOT_PATH', HOST.'/');

define('KML_DATA_PATH', ROOT_PATH.'kml_data/kml');

// Railway Track
 
define('RT_NSW1', KML_DATA_PATH.'/RT/NSW-1.kmz');//RTLayer
define('RT_NSW2', KML_DATA_PATH.'/RT/NSW-2.kmz');//RTLayer
define('RT_NSW3', KML_DATA_PATH.'/RT/NSW-3.kmz');//RTLayer
define('RT_QLD1', KML_DATA_PATH.'/RT/QLD-1.kmz');//RTLayer
define('RT_QLD2', KML_DATA_PATH.'/RT/QLD-2.kmz');//RTLayer
define('RT_QLD3', KML_DATA_PATH.'/RT/QLD-3.kmz');//RTLayer
define('RT_QLD4', KML_DATA_PATH.'/RT/QLD-4.kmz');//RTLayer
define('RT_QLD5', KML_DATA_PATH.'/RT/QLD-5.kmz');//RTLayer
define('RT_QLD6', KML_DATA_PATH.'/RT/QLD-6.kmz');//RTLayer
define('RT_QLD7', KML_DATA_PATH.'/RT/QLD-7.kmz');//RTLayer
define('RT_QLD8', KML_DATA_PATH.'/RT/QLD-8.kmz');//RTLayer
define('RT_WA1', KML_DATA_PATH.'/RT/WA-1.kmz');//RTLayer
define('RT_WA2', KML_DATA_PATH.'/RT/WA-2.kmz');//RTLayer
define('RT_VIC1', KML_DATA_PATH.'/RT/VIC-1.kmz');//RTLayer
define('RT_VIC2', KML_DATA_PATH.'/RT/VIC-2.kmz');//RTLayer
define('RT_VIC3', KML_DATA_PATH.'/RT/VIC-3.kmz');//RTLayer
define('RT_VIC4', KML_DATA_PATH.'/RT/VIC-4.kmz');//RTLayer
define('RT_VIC5', KML_DATA_PATH.'/RT/VIC-5.kmz');//RTLayer


//AU Petrol Stations
define('NPSLayer1', KML_DATA_PATH.'/NPS/National_Petrol_Stations_1.kmz');
define('NPSLayer2', KML_DATA_PATH.'/NPS/National_Petrol_Stations_2.kmz');
define('NPSLayer3', KML_DATA_PATH.'/NPS/National_Petrol_Stations_3.kmz');
define('NPSLayer4', KML_DATA_PATH.'/NPS/National_Petrol_Stations_4.kmz');
define('NPSLayer5', KML_DATA_PATH.'/NPS/National_Petrol_Stations_5.kmz');

// National Telephone Exchange
define('NTE_AREA', KML_DATA_PATH.'/NTE/NationalTelephoneExchangeAreas.kmz');
define('NTE_POINT', KML_DATA_PATH.'/NTE/NationalTelephoneExchangePts.kmz');

// Broadcost Transmitter 
define('TS_BTD', KML_DATA_PATH.'/BTD/tmpkmz.kmz');
define('AM_BTD', KML_DATA_PATH.'/BTD/amkmz.kmz');
define('DR_BTD', KML_DATA_PATH.'/BTD/dabkmz.kmz');
define('DT_BTD', KML_DATA_PATH.'/BTD/dtvkmz.kmz');
define('FM_BTD', KML_DATA_PATH.'/BTD/fmkmz.kmz');

//AU Electricity Transmission Substations
define('NETSDLayer1', KML_DATA_PATH.'/NETSD/NationalTransmissionSubstations.kml');

//AU Major Airport Terminals
define('NMATLayer1', KML_DATA_PATH.'/NMAT/NationalMajorAirportTerminals.kml');

//AU Ports
define('PortsLayer1', KML_DATA_PATH.'/Ports/ports.kml');//KMLLayer8

//AU Operating Mines 
define('NOMNOMLayer1', KML_DATA_PATH.'/NOM/operating_mines.kml');//KMLLayer9

//AU Major Power Stations 
define('NMPSLayer1', KML_DATA_PATH.'/NMPS/NationalMajorPowerStations.kml');//KMLLayer10

// Ericsson layer
define('Ericsson', KML_DATA_PATH.'/Ericsson/Ericsson.kmz');//KMLLayer11

// MDSWA layer
define('MDSWA', KML_DATA_PATH.'/MDSWA/MDB_WRPA_SurfaceWater.kmz');//KMLLayer19

// Water NSW layer
define('WATERNSW', KML_DATA_PATH.'/WN/WATERNSW.kml');//KMLLayer35

// GWA layer
//define('GWA', KML_DATA_PATH.'/GWA/gwa.kml');//KMLLayer38
define('GWA', 'http://clients3.ewn.com.au/rainfallcheckv3/clients/gwa/layers/trackalertarea.kml');//KMLLayer38

// GWA Chainage
define('GWA_CHAINAGE', KML_DATA_PATH.'/GWA/Chainage_1km.json');//KMLLayer39

// Unplanned
define('OUTAGE_UNPLANNED', 'http://clients3.ewn.com.au/common/obs/pwr/seq/kml/energex-unplanned.kml');//KMLLayer45

// PUBLIC OUTAGES
define('PUBLIC_OUTAGES', 'https://www.ergon.com.au/static/Ergon/Outage%20Finder/public_outages.kml');//KMLLayer46

// PUBLIC OUTAGES FUTURE
define('PUBLIC_OUTAGES_FUTURE', 'https://www.ergon.com.au/static/Ergon/Outage%20Finder/public_outages_future.kml');//KMLLayer47

// BCC
define('BCC', KML_DATA_PATH.'/BCC/BCC.kmz');//KMLLayer52

// ARTC
define('ARTC', KML_DATA_PATH.'/ARTC/track.kml');//KMLLayer53

// ARTC Chainage
define('ARTC_CHAINAGE', KML_DATA_PATH.'/ARTC/chainage.json');//KMLLayer64

// Linfox situation room 
define('HUME_HIGHWAY', KML_DATA_PATH.'/Hume_highway.kml');//KMLLayer 94

define('UTILITY_LINES', KML_DATA_PATH.'/utility_lines.kml');//KMLLayer 95

define('EDL_SITES', KML_DATA_PATH.'/edl_sites.kmz');//KMLLayer 96

define('CORE_NETWORK_SITES', KML_DATA_PATH.'/Core_Network_Sites.kml');//KMLLayer 100

define('NFAS_SITES', KML_DATA_PATH.'/NFAS_Sites.kml');//KMLLayer 101

define('NWAS_SITES', KML_DATA_PATH.'/NWAS_Sites.kml');//KMLLayer 102

define('NRC_PIPELINES', KML_DATA_PATH.'/NRC/NRC-last.kml');//KMLLayer 104


$selectSQL = "SELECT GROUP_CONCAT(topic_id) as other_topic FROM topics where topic_id NOT IN(23,28,29,33,34,35,31,36,13,2,14,37,38,39,40)";
$result = mysql_query($selectSQL);
$data=  mysql_fetch_array($result);
define('OTHER_IDS', $data['other_topic']);

/* cities config  */
$capital_cities=array();
$capital_citiesSQL =  mysql_query("SELECT * FROM `city_state`  WHERE `is_state` = '0'");
while($capital_citiesRows = mysql_fetch_assoc($capital_citiesSQL)){
	$capital_cities[$capital_citiesRows['name']] = array($capital_citiesRows['lat'],$capital_citiesRows['lng'],$capital_citiesRows['zoom']);
}
/* cities config  */

/* States config  */
$capital_states=array();
$capital_statesSQL =  mysql_query("SELECT * FROM `city_state`  WHERE `is_state` = '1'");
while($capital_citiesRows = mysql_fetch_assoc($capital_statesSQL)){
	$capital_states[$capital_citiesRows['name']] = array($capital_citiesRows['lat'],$capital_citiesRows['lng'],$capital_citiesRows['zoom']);
}
/* States config  */



/* layers Config */
$layer_names=array();
$client_layers=array();

$selectSQL = "SELECT DISTINCT layer_type,layer_id,placemarker_icon FROM layer_datas ORDER BY layer_data_id ASC ";
$result = mysql_query($selectSQL); 
while($row=mysql_fetch_array($result)) {
	if($row['layer_type']=='NBNWAS') {
		$row['layer_type']=='NBN NWAS';
	} else if($row['layer_type']=='NBNFAS') {
		$row['layer_type']=='NBN NFAS';
	}
	$layer_names[$row['layer_id']]=array($row['layer_type'],$row['placemarker_icon']);
	if($row['layer_id']>10) {
		$client_layers[]=$row['layer_id'];
	}
}



// custom code as per Linfox SR
$externalRadarLink = "common/radar.php?radar=713";

switch ($_SESSION['username']) {
	case 'linfoxnsw':
	$externalRadarLink = "common/radar.php?radar=713";
	break;
	
	case 'linfoxqld':
	$externalRadarLink = "common/radar.php?radar=663";
	break;
	
	case 'linfoxvic':
	$externalRadarLink = "common/radar.php?radar=023";
	break;
	
	case 'linfoxnt':
	$externalRadarLink = "common/radar.php?radar=633";
	break;
	
	case 'linfoxsa':
	$externalRadarLink = "common/radar.php?radar=643";
	break;
	
	case 'linfoxtas':
	$externalRadarLink = "common/radar.php?radar=763";
	break;
	
	case 'linfox':
	$externalRadarLink = "common/radar.php?radar=703";
	break;
	
	default:
	$externalRadarLink = "common/radar.php?radar=713";
	break;
}

function system_info()
{

    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];

    $os_platform    =   "Unknown OS Platform";
    
    $browser        =   "Unknown Browser";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/macintosh|mac os x|Mac OS X/i' =>  'Mac OS X',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile',
                            '/Windows Phone/i'      =>   'Windows Phone' 
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    } 


    $browser_array  =   array(
                            '/mobile/i'     =>  'Handheld Browser',
                            '/msie|MSIE|iemobile/i'       =>  'Internet Explorer',
                            '/trident/i'    =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }

    return array(
         "user_agent"     =>    $user_agent,
         "os"     =>    $os_platform,
         "browser"     =>    $browser,
        );
}
// get system information
// user session update function
function update_user_session()
{
    $where = "`user_id`='". $_SESSION['user_id']."' AND `session_id`='".$_SESSION['session_id']."'";
    if($_SESSION['session_id'] !=  session_id())
    {
        $delete_active_session = mysql_query("DELETE FROM `user_sessions` WHERE $where");
        if($delete_active_session > 0)
        {
            $_SESSION['session_id'] =  session_id();
            $get_system = system_info();
            $insert_active_session = mysql_query("INSERT INTO `user_sessions` SET 
                                                           `user_id` = '".$_SESSION['user_id']."',
                                                           `session_id` = '".$_SESSION['session_id']."',
                                                           `user_agent` = '".$get_system['user_agent']."',
                                                           `browser` = '".$get_system['browser']."',
                                                           `os` = '".$get_system['os']."',
                                                           `ip_address` = '".$_SERVER['REMOTE_ADDR']."',
                                                           `created_at` = '".$current_datetime."',
                                                           `updated_at` = '".$current_datetime."'");
        }
    }
    else
    {
        $select_active_session = mysql_query("SELECT * FROM `user_sessions` WHERE $where");
        $update_active_session = mysql_query("UPDATE `user_sessions` SET `updated_at` = '".date("Y-m-d H:i:s")."' WHERE $where");
    }
    return true;
}
// user session update function
// force terminate session 
function session_force_destroy()
{
     $query = "SELECT * FROM `clients` WHERE `id`= '".$_SESSION['user_id']."'";
     $clients = mysql_query($query);
     $clients_data = mysql_fetch_assoc($clients);
    if($clients_data['is_admin']!=1 /* && $_SESSION['multiple_allowed']==false*/ )
    {
        $active_session = mysql_query("SELECT `id` as `total_active_session` FROM `user_sessions` WHERE  user_id = '".$_SESSION['user_id']."' AND `session_id` = '".$_SESSION['session_id']."' ");

         if(!mysql_num_rows($active_session))
         {
            unset($_SESSION['user_id']);
            unset($_SESSION['session_id']);
            header("Location:login.php?max=1");
         }
    }

}

function generateRandomStr($length=5){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>