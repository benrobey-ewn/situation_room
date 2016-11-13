<?php 
include '../dev/includes/config.php'; 
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
$current_datetime  = date("Y-m-d H:i:s");
$one_hour_previous = date("Y-m-d H:i:s",strtotime('-1 hour'));

 $deleted_count = 0;
$select = mysql_query("SELECT * FROM `user_sessions` WHERE `updated_at`  <= '".$one_hour_previous."'");
if(mysql_num_rows($select) > 0) {
    while($select_data = mysql_fetch_assoc($select)){
        $session_db_id = $select_data['session_id'];
        $user_id = $select_data['user_id'];
        $select_user = mysql_query("SELECT * FROM `clients`  WHERE `id`='".$user_id."'");
        if($select_user['concurrent_login']!="-1") {
            session_id($session_db_id);
            session_start();
            session_destroy();
            $delete_old_sessions  = mysql_query("DELETE FROM `user_sessions` WHERE `session_id`='".$session_db_id."'");
            $deleted_count++;
        }
    }
}


echo $deleted_count." no. of rows deleted";
 ?>