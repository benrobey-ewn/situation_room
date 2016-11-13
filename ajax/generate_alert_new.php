<?php include '../includes/config.php';


	if($_REQUEST['alert_type']=='Red'){
			$class='activeR';
	} else if ($_REQUEST['alert_type']=='#ffcc00') {
			$class='activeA';
	} else if ($_REQUEST['alert_type']=='Black') {
			$class='activeB';
	} else {
			$class='activeG';
	}
	$nbn_layer_ids = explode(",",$_REQUEST['id']);
foreach($nbn_layer_ids as $id){
	$sql = "SELECT nbn_postcode from nbn_layers_info where  nbn_postcode='$id'";
	$rs=mysql_query($sql);
	$num=mysql_num_rows($rs);
	if($num>0){
		$sql_cmd = "UPDATE `nbn_layers_info` SET nbn_class='".$class."' WHERE nbn_postcode='$id'";
		mysql_query($sql_cmd);
	}else{
		$sql_cmd = "INSERT INTO `nbn_layers_info` SET `nbn_postcode` = '$id',`nbn_class` = '$class'";
		mysql_query($sql_cmd);

	}
	
}


echo $class;
die;

?>