<?php include '../includes/config.php';
 $response=array();
 $alert_id=trim(mysql_real_escape_string($_POST['alert_id']));
 //$selectSQL = "Delete from Alerts where id='".$alert_id
 /* delete polygon location */
 $selectSQL = "UPDATE polygon_location SET is_deleted=1 where alert_id='1'";
 $result = mysql_query($selectSQL);
 /* Delete Alert */
 $selectSQL = "UPDATE Alerts SET is_deleted=1 where id='".$alert_id."'";  
 $result = mysql_query($selectSQL);
 
 $output=array('status'=>'true','data'=>'');
 echo json_encode($output);
?>