<?php
  include '../includes/config.php';
 
  $username=trim(mysql_real_escape_string($_POST['username']));
  $password=trim(mysql_real_escape_string($_POST['password']));  
  
  if(!empty($username) && !empty($password)) {
     
      $sql = "SELECT user_id,username FROM users where `username`='".$username."' AND status=1";
      $rsp = mysql_query($sql);
      if(mysql_num_rows($rsp)>0) {
         $output=array('status'=>'true','msg'=>'User already exist');
      } else {
        $password=md5($password);
        $query="INSERT INTO users(title,username,password) "
                . "VALUES ('".$username."','".$username."','".$password."')";
	
        $result = mysql_query($query);
        $output=array('status'=>'true','msg'=>'User added sucessfully');
      }
  } else {
    $output=array('status'=>'false','msg'=>'All fields manadatry');
  }
  
  
  echo json_encode($output);
?>