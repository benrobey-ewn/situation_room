<?php include '../includes/config.php';
$sqlst = "SELECT nbn_alerts.nbn_alert_title,nbn_alerts.nbn_alert_desc,nbn_alerts.nbn_alert_id,nbn_layers_info.nbn_color FROM nbn_alerts JOIN nbn_layers_info ON nbn_alerts.nbn_alert_id=nbn_layers_info.alert_id where nbn_layers_info.nbn_postcode=".$_REQUEST['postcode'];
$rsst = mysql_query($sqlst);
$numst = mysql_num_rows($rsst);
if($numst>0){
		$results=mysql_fetch_row($rsst);
}
$output=array('alertdetails'=>$results);
echo json_encode($output);die;

?>