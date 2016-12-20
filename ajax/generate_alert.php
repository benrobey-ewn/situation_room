<?php include '../includes/config.php';
$sqlString1 = "UPDATE `nbn_layers` SET nbn_fillcolor='".$_REQUEST['alert_type']."' WHERE nbn_main_id=".$_REQUEST['id'];
$sqlRes1 = mysql_query($sqlString1);



?>