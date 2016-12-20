<?php
  include '../includes/config.php';
  $username = trim(mysql_real_escape_string($_POST['username']));
  $password = md5(trim(mysql_real_escape_string($_POST['password'])));
 
  
  $response = array();
  
  if(!empty($_POST['username']) && !empty($_POST['password'])) {
    
    $sql = "SELECT id,username,account_status,created_at,days_allowed,concurrent_login,is_admin,payment_status FROM clients where `username`='".$username."' AND password='".$password."'";
    $rsp = mysql_query($sql);
    if(mysql_num_rows($rsp)>0) {
        $fetch = mysql_fetch_assoc($rsp);
        if($fetch['account_status']==1){

            $allow_login = 1;
            if($fetch['days_allowed']!="-1" && $fetch['payment_status']!="paid"){
               $days = $fetch['days_allowed'];
               $calulated_date = strtotime($fetch['created_at']." +".$days." days");
               $current_date = strtotime("now");
               if ($calulated_date < $current_date){
                    $output=array('status'=>'false','msg'=>'Your trail period has expired, Please contact your administrator');
                    $allow_login = 0;
               }
            }

             if($fetch['concurrent_login']!="-1"){
                 $allowed_count = $fetch['concurrent_login'];
                 $select_saved_session = mysql_query("SELECT COUNT(id) as `total_active_session` FROM user_sessions WHERE `user_id` = '".$fetch['id']."'");
                 $data = mysql_fetch_assoc($select_saved_session);
                 if($data['total_active_session'] >= $allowed_count)
                 {
                   $output=array('status'=>'false','msg'=>'You have reached your Max Login Limit');
                   $allow_login = 0;
                 }
                 $_SESSION['multiple_allowed']=false;
             }     
             else{
               $_SESSION['multiple_allowed']=true;
             }

            if($allow_login==1)
            {
                $_SESSION['user_id']=  $fetch['id'];
                $_SESSION['username']=  $fetch['username'];
                $_SESSION['session_id']=  session_id();
                if($fetch['is_admin']!=1)
                {
                  $get_system = system_info();
                  $current_datetime = date("Y-m-d H:i:s"); 
                  $active_session = mysql_query("INSERT INTO `user_sessions` SET 
                                                             `user_id` = '".$fetch['id']."',
                                                             `session_id` = '".$_SESSION['session_id']."',
                                                             `user_agent` = '".$get_system['user_agent']."',
                                                             `browser` = '".$get_system['browser']."',
                                                             `os` = '".$get_system['os']."',
                                                             `ip_address` = '".$_SERVER['REMOTE_ADDR']."',
                                                             `created_at` = '".$current_datetime."',
                                                             `updated_at` = '".$current_datetime."'");
                }
                $output=array('status'=>'true','msg'=>'sucess');
            } 
        } else {
          $output=array('status'=>'false','msg'=>'Your account is not active, Please contact your administrator');
        }
    } else {
        $output=array('status'=>'false', 'msg'=>'Invalid username and password');
    }
  } else {
        $output=array('status'=>'false','msg'=>'all fields manadatry');
  }
  echo json_encode($output);
?>