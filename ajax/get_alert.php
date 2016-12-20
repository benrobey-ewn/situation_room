<?php 
include '../includes/config.php';
include '../includes/functions.php';
$response=array();
$sqlst = "SELECT * FROM nbn_alerts  where nbn_alert_title  like '%".$_GET['term']."%'";
$rsst = mysql_query($sqlst);
$numst = mysql_num_rows($rsst);
if($numst>0){
	while ( $row=mysql_fetch_object($rsst)) {
		$alertcount= getAlertCount($row->nbn_alert_id);
		if($alertcount>0){
			$postcode[]=$row->nbn_alert_title; 
		}
	}
}
echo json_encode($postcode);die;

?>