<?php include '../includes/config.php';
$response=array();
$sqlst = "select * from nbn_layers_info";
$rsst = mysql_query($sqlst);
$numst = mysql_num_rows($rsst);
if($numst>0){
	
	while ( $row=mysql_fetch_object($rsst)) {
		$response[$row->nbn_postcode] = $row->nbn_class;
	}
	
}

$output=array('classes'=>$response);
echo json_encode($output);die;

?>