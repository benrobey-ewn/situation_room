<?php 
include 'includes/config.php';
error_reporting(E_ALL);
if(!empty($_SESSION['user_id'])) {
    $delete_current_session = mysql_query("DELETE FROM `user_sessions` WHERE `user_id`='".$_SESSION['user_id']."' AND `session_id`='".$_SESSION['session_id']."'");
    
    session_destroy();
}
header('Location:login.php');
?>
