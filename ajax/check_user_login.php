<?php
include '../includes/config.php';
$username = trim(mysql_real_escape_string($_POST['username']));
$password = md5(trim(mysql_real_escape_string($_POST['password'])));
error_reporting(0);
 //ini_set("display_errors",1);

$response = array();

if(!empty($_POST['username']) && !empty($_POST['password'])) {
  
  $sql = "SELECT id,username,account_status,created_at,days_allowed,concurrent_login,is_admin,payment_status FROM clients where `username`='".$username."' AND password='".$password."'";
  $rsp = mysql_query($sql);
  if(mysql_num_rows($rsp)>0) {
    $fetch = mysql_fetch_assoc($rsp);
    if($fetch['account_status']==1){

      $allow_login = 1;
            // New code to upload
      if($fetch['is_admin']!=1){
       
        $date_allowed = strtotime($fetch['days_allowed']);
        $current_date =  strtotime("now");
        $diff = $date_allowed - $current_date;
        if($diff < 0) {   
          $allow_login = 0;
          $message = "Your access has expired, Please contact <a href='mailto:support@ewn.com.au'>support@ewn.com.au</a>";
          if($fetch['payment_status'] == 'trial'){
            $message = "Your trial period is expired, Please contact <a href='mailto:support@ewn.com.au'>support@ewn.com.au</a>";
          }
          $output=array('status'=>'false','msg'=>$message,'error_code'=>100);
        }
        
        

        if($fetch['concurrent_login']!="-1"){
         $allowed_count = $fetch['concurrent_login'];
         $select_saved_session = mysql_query("SELECT COUNT(id) as `total_active_session` FROM user_sessions WHERE `user_id` = '".$fetch['id']."'");
         $data = mysql_fetch_assoc($select_saved_session);
         if($data['total_active_session'] >= $allowed_count)
         {
           $output=array('status'=>'false','msg'=>'You have reached your maximum login limit. Do you want all other active sessions to be closed?','error_code'=>200,'user_id'=> $fetch['id']);
           $allow_login = 0;
         }
             //    $_SESSION['multiple_allowed']=false;
       }     
       else{
            //   $_SESSION['multiple_allowed']=true;
       }
     }
     

     if($allow_login==1)
     {
      $_SESSION['user_id']=  $fetch['id'];
      $_SESSION['username']=  $fetch['username'];
      $_SESSION['session_id']=  session_id();
      $_SESSION['login_no'] = generateRandomStr();
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
         `reference_no` = '".$_SESSION['login_no']."',
         `created_at` = '".$current_datetime."',
         `updated_at` = '".$current_datetime."'");
        
        $login_history = mysql_query("INSERT INTO login_history SET  
         `client_id` = '".$fetch['id']."', 
         `login_date` = '".$current_datetime."', 
         `browser` = '".$get_system['browser']."',
         `os` = '".$get_system['os']."',
         `ip_address` = '".$_SERVER['REMOTE_ADDR']."',
         `reference_no` = '".$_SESSION['login_no']."',
         `created_date` = '".$current_datetime."', 
         `updated_date` = '".$current_datetime."'");
      }
      $output=array('status'=>'true','msg'=>'sucess','error_code'=>1);
    } 
  } else {
   $message = "Your account is no longer active, Please contact <a href='mailto:support@ewn.com.au'>support@ewn.com.au</a>";
   if($fetch['account_status'] == 2){
    $message = "Your access has expired, Please contact <a href='mailto:support@ewn.com.au'>support@ewn.com.au</a>";
  }
  $output=array('status'=>'false','msg'=>$message,'error_code'=>300);
}
} else {
        //$output=array('status'=>'false', 'msg'=>'Invalid username and password');
  $output=array('status'=>'false', 'msg'=>"Unable to login. Please check you have entered your password and username correctly. If you have forgotten your password or can't login, please contact <a href='mailto:support@ewn.com.au'>support@ewn.com.au</a>",'error_code'=>400);
}
} else {
  $output=array('status'=>'false','msg'=>'all fields manadatry','error_code'=>500);
}
echo json_encode($output);
?>