<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'situatio_product');
define('DB_PASSWORD', 'sr*123');
define('DB_DATABASE', 'situatio_prod');

$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
mysql_select_db(DB_DATABASE,$con);

define('HOST','http://situationroom.ewn.com.au');

define('ROOT',$_SERVER['DOCUMENT_ROOT']);

define('ROOT_PATH', HOST.'/');

define('KML_DATA_PATH', ROOT_PATH.'kml_data/kml');

// Railway Track

define('RT_NSW', KML_DATA_PATH.'/RT/NSW.kmz');//RTLayer1

define('RT_NT', KML_DATA_PATH.'/RT/NT.kmz');//RTLayer2

define('RT_QLD', KML_DATA_PATH.'/RT/QLD.kmz');//RTLayer3

define('RT_SA', KML_DATA_PATH.'/RT/SA.kmz');//RTLayer4

define('RT_TAS', KML_DATA_PATH.'/RT/TAS.kmz');//RTLayer5

define('RT_VIS', KML_DATA_PATH.'/RT/VIC.kmz');//RTLayer6

define('RT_WA', KML_DATA_PATH.'/RT/WA.kmz');//RTLayer7


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
define('GWA', KML_DATA_PATH.'/GWA/gwa.kml');//KMLLayer38

// GWA Chainage
define('GWA_CHAINAGE', KML_DATA_PATH.'/GWA/Chainage_1km.json');//KMLLayer39 

// Unplanned
define('OUTAGE_UNPLANNED', 'http://clients3.ewn.com.au/common/obs/pwr/seq/kml/energex-unplanned.kml');//KMLLayer45

// PUBLIC OUTAGES
define('PUBLIC_OUTAGES', 'https://www.ergon.com.au/static/Ergon/Outage%20Finder/public_outages.kml');//KMLLayer46

// PUBLIC OUTAGES FUTURE
define('PUBLIC_OUTAGES_FUTURE', 'https://www.ergon.com.au/static/Ergon/Outage%20Finder/public_outages_future.kml');//KMLLayer47

// BCC
define('BCC', KML_DATA_PATH.'/BCC/BCC.kml');//KMLLayer52

// ARTC
define('ARTC', KML_DATA_PATH.'/ARTC/track.kml');//KMLLayer53

// ARTC Chainage
define('ARTC_CHAINAGE', KML_DATA_PATH.'/ARTC/chainage.json');//KMLLayer64

$selectSQL = "SELECT GROUP_CONCAT(topic_id) as other_topic FROM topics where topic_id NOT IN(23,28,29,33,34,35,31,36,13,2,14,37,38,39,40)";
$result = mysql_query($selectSQL);
$data=  mysql_fetch_array($result);
define('OTHER_IDS', $data['other_topic']);

/* cities config  */
$capital_cities=array();
$capital_cities['sydney']=array('-32.31470062890541', '149.67802639062504',5);
$capital_cities['melbourne']=array('-37.999999999999986', '145',9);
$capital_cities['darwin']=array('-12.009768338524529', '131.830688',9);
$capital_cities['perth']=array('-31.55998348192999', '116.84000000000005', 9);
$capital_cities['brisbane']=array('-27.300963', '153.023751', 9);

$capital_cities['canberra']=array('-35.282', '149.128684',9);
$capital_cities['adelaide']=array('-34.9286212', '138.5999594', 9);
$capital_cities['hobart']=array('-42.881903', '147.323814', 9);
$capital_cities['bechtel']=array('-21.749', '119.85', 6);
/* cities config  */

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

 
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 30*60*60); // 30 hours
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(30*60*60);
session_start();
?>