<?php 
if(empty($_SESSION))
{
    session_start();
}

if(isset($_SESSION['admin_id']) && $_SESSION['admin_id']!="")
{
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
}
header("Location:login.php");





 ?>