<?php
  require_once '../includes/sit_room.php';
   if(isset($_POST['check_login']) && $_POST['check_login']=="check_login") {
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
      $results = array();
      /*$db->where ('username', $_POST['username']);
      $db->where ('password', md5($_POST['password']));
      $results = $db->get ('admin');*/
       $select_admin = mysql_query("SELECT * FROM clients WHERE `username` ='".$_POST['username']."' AND `password` ='".md5($_POST['password'])."' AND `is_admin`=1 LIMIT 1");
      $results[0] = mysql_fetch_assoc($select_admin);
      
      if(!empty($results[0])){
        if($results[0]['account_status']=="1"){

          $_SESSION['admin_name']=$results[0]['first_name']." ".$results[0]['last_name'];
          $_SESSION['admin_id'] = $results[0]['id'];
          
          // for client login variables 
          if($_SESSION['user_id']==""){
            $_SESSION['user_id'] =  $results[0]['id'];
            $_SESSION['username'] = "admin";
            $_SESSION['session_id']=  session_id();
          }
          // for client login variables 
          
          if(isset($_POST["remember_me"]) && $_POST["remember_me"]==1){
            setcookie("username", $_POST['username'] , time()+60*60*24*7,"/");
            setcookie("password", $_POST['password'] , time()+60*60*24*7,"/");
            setcookie("remember_me", 1 , time()+60*60*24*7,"/");

          }else{
            if(isset($_COOKIE['username']) && $_COOKIE['username']!="" && isset($_COOKIE['password']) && $_COOKIE['password']!=""){
               setcookie("username", ""   , 1,"/");
               setcookie("password",  "" , 1,"/");
               setcookie("remember_me", 1 , 1,"/");
             }
          }
          $response = array("status"=>"true","data"=>"Login Successfull");
        }else{
          $response = array("status"=>"false","data"=>"Your Account Is Not Active");
        }
      }
      else{
        $response = array("status"=>"false","data"=>"Check Your Login Credentials");
      }
    }

    echo json_encode($response);
    exit;
  }
 
?>
