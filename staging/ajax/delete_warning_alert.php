<?php include '../includes/config.php';
 $response=array();
 $alert_id=trim(mysql_real_escape_string($_POST['alert_id']));
 
 
  $system_timezone = date_default_timezone_get();
  date_default_timezone_set('UTC');
  $todaydate = date('Y-m-d H:i:s');
  date_default_timezone_set($system_timezone);
 
   /* delete polygon location */
   //$selectSQL = "UPDATE polygon_location SET is_deleted=1 where alert_id='".$alert_id."'";
   //$result = mysql_query($selectSQL);
   
   /* Delete Alert */
   $selectSQL = "UPDATE Alerts SET Expires='".$todaydate."' where id='".$alert_id."'";  
   $result = mysql_query($selectSQL);
   
   $output=array('status'=>'true','data'=>'','sql'=>$selectSQL);
   echo json_encode($output);
?>