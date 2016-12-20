<?php 
include 'includes/config.php';
error_reporting(E_ALL);
if(!empty($_SESSION['user_id'])) {
	$delete_current_session = mysql_query("DELETE FROM `user_sessions` WHERE `user_id`='".$_SESSION['user_id']."' AND `session_id`='".$_SESSION['session_id']."'");
	$current_datetime = date("Y-m-d H:i:s"); 
	$update_client_session = mysql_query("UPDATE `login_history` SET `logout_date` = '".$current_datetime."', `updated_date` = '".$current_datetime."' WHERE `client_id`='".$_SESSION['user_id']."' AND `reference_no` = '".$_SESSION['login_no']."' ORDER BY id DESC LIMIT 1");
	
	unset($_SESSION['user_id']);
	unset($_SESSION['session_id']);
	unset($_SESSION['login_no']);
}
header('Location:login.php');
?>
