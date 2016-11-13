<?php 
include '../includes/config.php';

if($_SESSION['username']!="admin")
{
    $select_clients = mysql_query("SELECT id FROM `user_sessions` WHERE `user_id`='". $_SESSION['user_id']."' AND `session_id`='".$_SESSION['session_id']."'");
    if(!mysql_num_rows($select_clients))
    {
        echo 0;
        exit;
    }
     $update = update_user_session();
    if($update > 0) {
        echo 1;
        exit;
        
    }
} else {
    echo 1;
    exit;
}
 ?>
