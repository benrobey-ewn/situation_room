<?php include '../includes/config.php';
$response=array();
$sqlst = "select * from nbn_layers_info where nbn_postcode='".$_REQUEST['id']."'";
$rsst = mysql_query($sqlst);
$numst = mysql_num_rows($rsst);
if($numst>0){
		$row=mysql_fetch_row($rsst);
		echo $row[2];die;
	
}

//$output=array('classes'=>$response);
//echo json_encode($output);die;

?>