<?php include 'includes/config.php';
if(!empty($_SESSION['user_id'])) {
   // destroy the session
    session_destroy(); 
}
    header('Location:login.php');
    die;
?>
