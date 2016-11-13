<?php include("../includes/config.php");
$alertTableName = "Alerts";
if(isset($_GET)){
	// to get alert details
	if($_GET['get_alert']!=""){
		$alert_id = trim($_GET['get_alert']);
	   $sqlString = "SELECT topic_id,subject FROM `$alertTableName` WHERE id='$alert_id'";
	   $sqlRes = mysql_query($sqlString);
	   if(mysql_num_rows($sqlRes) > 0){
	   	 $row = mysql_fetch_assoc($sqlRes);
	   	 $row['alert_id'] = $alert_id;
	   	 echo json_encode($row); 
	   	 die;
	   }
	}
	// to delete alerts
	if($_GET['delete_alert']!=""){
		$alert_id = trim($_GET['delete_alert']);
	   $sqlString1 = "DELETE FROM `$alertTableName` WHERE id='$alert_id'";
	   $sqlRes1 = mysql_query($sqlString1);
	   if($sqlRes1 > 0){
	   	$sqlString2 = "DELETE FROM polygon_location WHERE `alert_id`='$alert_id'";
	   	$sqlRes2 = mysql_query($sqlString2);
	   	if($sqlRes2 > 0){
	   		$output = array("status"=>"true","message"=>"Alert Successfully Deleted","query"=>$sqlString2,"---",$sqlString1);
	   	} else {
	   		$output = array("status"=>"false","message"=>"Alert Partially Deleted");
	   	}
	   } else {
	   	$output = array("status"=>"false","message"=>"Failed to Delete Alert");
	   }
	 echo json_encode($output);
	 exit;	
	}
	
	// to active / inactive alerts
	if($_GET['active_inactive_warning']!=""){
		$alert_id = trim($_GET['active_inactive_warning']);
		$alert_status = trim($_GET['status']);
		if($alert_status==0){
			$alert_message = "Alert Successfully Shown";
		} else {
			$alert_message = "Alert Hidden";
		}
	   $sqlString1 = "UPDATE `$alertTableName` SET is_deleted='$alert_status' WHERE id='$alert_id'";
	   $sqlRes1 = mysql_query($sqlString1);
	   if($sqlRes1 > 0){
	   	$sqlString2 = "UPDATE polygon_location SET is_deleted='$alert_status' WHERE `alert_id`='$alert_id'";
	   	$sqlRes2 = mysql_query($sqlString2);
	   	if($sqlRes2 > 0){
	   		$output = array("status"=>"true","message"=>$alert_message);
	   	} else {
	   		$output = array("status"=>"false","message"=>"Alert Partially Updated");
	   	}
	   } else {
	   	$output = array("status"=>"false","message"=>"Failed to Update Alert");
	   }
	 echo json_encode($output);
	 exit;	
	}
}

if(isset($_POST) && $_POST['save_changes']=="save_changes"){
	$topic_id = mysql_real_escape_string($_POST['topic_id']);
	$subject = mysql_real_escape_string($_POST['subject']);
	$alert_id = mysql_real_escape_string($_POST['alert_id']);
	$update_alert = mysql_query("UPDATE `$alertTableName` SET `topic_id` = '$topic_id', `subject`='$subject' WHERE `id`='$alert_id'");
	if($update_alert > 0){
		echo json_encode(array("status"=>"true","message"=>"Successfully Updated"));
	} else {
		echo json_encode(array("status"=>"false","message"=>"Failed to Update"));
	}
	exit;
}
 
?>