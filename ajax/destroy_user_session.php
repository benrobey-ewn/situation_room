<?php 
include '../includes/config.php';

if(isset($_POST['action']) && $_POST['action']== "delete_user_session") {
	$user_id = $_POST['user_id'];
	
	 $where = "WHERE `user_id`='$user_id' ";
	 
	 $select = mysql_query("SELECT * FROM `user_sessions` $where");
    $selectUserSessionData = mysql_fetch_assoc($select);
    $ref_no = $selectUserSessionData['reference_no'];
    
    $delete_user_session = "DELETE FROM `user_sessions` $where ";
    $delete_user_session_res = mysql_query($delete_user_session);
    
    if($delete_user_session_res > 0)
    {
      $current_datetime = date("Y-m-d H:i:s");
      $login_history_query = "UPDATE login_history SET `logout_date`='$current_datetime', `updated_date` = '$current_datetime' WHERE `client_id`='$user_id' AND reference_no = '$ref_no' ";
      $update_login_history = mysql_query($login_history_query);
      $response['status']='true';
      $response['message']='All other active sessions have been closed.';
    } 
    else 
    {
     $response['status']='false';
     $response['message']='Unable to close all other active sessions, Please try again.';
   }
   echo json_encode($response);
   exit;
}

 ?>