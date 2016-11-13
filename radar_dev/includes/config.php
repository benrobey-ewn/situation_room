<?php

/*define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'situatio_product');
define('DB_PASSWORD', 'sr*123');*/

/////// Added by Programmer 3 

// old constants work 
/*define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'situatio_dev');
define('DB_PASSWORD', 'sr*123');
define('DB_DATABASE', 'situatio_radar_dev');

$radar_dev_con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
mysql_select_db(DB_DATABASE,$radar_dev_con);*/

define('RADAR_DB_SERVER', 'localhost');
define('RADAR_DB_USERNAME', 'situatio_dev');
define('RADAR_DB_PASSWORD', 'sr*123');
define('RADAR_DB_DATABASE', 'situatio_radar_dev');

$radar_dev_con = mysql_connect(RADAR_DB_SERVER,RADAR_DB_USERNAME,RADAR_DB_PASSWORD);
mysql_select_db(RADAR_DB_DATABASE,$radar_dev_con);

/////////////////
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
/* cities config  */
error_reporting(0);

?>