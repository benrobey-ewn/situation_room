<?php 
require_once '../includes/sit_room.php';
$response = array();


if(isset($_POST['user_name_check']) && $_POST['user_name_check']=="user_name_check") {
  $where = " `username` LIKE '%".trim($_POST['user_name'])."%'";
  if(isset($_POST['client_id']) && $_POST['client_id']!="")
  {
    $where.= ' AND `id` != "'.$_POST['client_id'].'"';
  }

  $select = mysql_query("SELECT `id`,`username` FROM `clients` WHERE $where");
  if(mysql_num_rows($select) > 0) {
    echo 1;
  } else {
    echo 0;
  }
  exit;

}

if(isset($_POST['email_check']) && $_POST['email_check']=="email_check") {
  $where = " `email` LIKE '%".trim($_POST['email_address'])."%'";
  if(isset($_POST['client_id']) && $_POST['client_id']!="")
  {
    $where.= ' AND `id` != "'.$_POST['client_id'].'"';
  }

  $select = mysql_query("SELECT `id`,`email` FROM `clients` WHERE $where");
  if(mysql_num_rows($select) > 0) {
    echo 1;
  } else {
    echo 0;
  }
  exit;

}

if(isset($_POST['client_add']) && $_POST['client_add']=="client_add") {

  $data["first_name"] =  trim(mysql_real_escape_string($_POST['fist_name']));
  $data["last_name"] = trim(mysql_real_escape_string($_POST['last_name']));
  $data["email"] = trim(mysql_real_escape_string($_POST['email']));
  $data["username"] = trim(mysql_real_escape_string($_POST['username']));
  $data["password"] = md5(trim(mysql_real_escape_string($_POST['password'])));
  $data["company_name"] = trim(mysql_real_escape_string($_POST['company_name']));
  $data["phone"] = trim(mysql_real_escape_string($_POST['phone']));
  $data["account_status"] = trim(mysql_real_escape_string($_POST['account_status']));
  $data["concurrent_login"] = trim(mysql_real_escape_string($_POST['concurrent_login']));
  $data["is_authorised_nbn_user"] = trim(mysql_real_escape_string($_POST['is_authorised_nbn_user']));
  $data["nbn_user_type"] = trim(mysql_real_escape_string($_POST['nbn_user_type']));
  $data["logo"]  = "";
  if($_POST['get_image']!="")
  {
    $new_path = "uploads/".basename($_POST['get_image']); 
    $current_file ="../".$_POST['get_image'];
    if(file_exists($current_file)){
      if(copy($current_file, "../".$new_path)) {
        unlink($current_file);
        $data["logo"] =  $new_path;
      }
    }
  }
  $data["payment_status"] = trim(mysql_real_escape_string($_POST['payment_status']));
  //if($_POST['payment_status']=="trial"){
  //$data["days_allowed"] = trim(mysql_real_escape_string($_POST['days_allowed']));
 /* }
  else{
    $date_ofThe_Year =  date('Y-01-01');
    $defaultDate = date('Y-m-d',strtotime($date_ofThe_Year));
    $data["days_allowed"] = $defaultDate;
  }
*/
  $data["days_allowed"] = trim(mysql_real_escape_string($_POST['days_allowed']));
  $tempvar = explode('/', $data["days_allowed"]);
  $date_save = $tempvar[2].'-'.$tempvar[1].'-'.$tempvar[0];
  $data["created_at"] = date("Y-m-d h:i:s");
  
        //$id = $db->insert ('clients', $data);
  $count = count($data);
  
  $insert = "INSERT INTO `clients` SET    
  `first_name`   = '".$data["first_name"]."',
  `last_name`   = '".$data["last_name"]."',
  `email`   = '".$data["email"]."',
  `username`   = '".$data["username"]."',
  `password`   = '".$data["password"]."',
  `company_name`   = '".$data["company_name"]."',
  `phone`   = '".$data["phone"]."',
  `account_status`   = '".$data["account_status"]."',
  `logo`   = '".$data["logo"]."',
  `concurrent_login` = '".$data["concurrent_login"]."',
  `payment_status`   = '".$data["payment_status"]."',
  `is_authorised_nbn_user`   = '".$data["is_authorised_nbn_user"]."',
  `nbn_user_type`   = '".$data["nbn_user_type"]."',
  `days_allowed`   = '".$date_save."',
  `created_at`   = '".$data["created_at"]."'";
  //echo $insert;die;
  $insert_res  = mysql_query($insert);
  $id = mysql_insert_id();
  if($id > 0)
  {
            //  insert a default layer for the client - australian postal code 
    $insert_layer = mysql_query("INSERT INTO `client_layers` SET `client_id` = '".$id."', `layer_id`='12' , `created_at` = '".date("Y-m-d H:i:s")."' ");
    $response['status']='true';
    $response['data']='Client Added Successfully';
  }
  else
  {
    $response['status']='false';
    $response['data']='Please Try Again';
  }

  echo json_encode($response);
  exit;
}


if(isset($_POST['client_edit']) && $_POST['client_edit']=="client_edit") {

  $data["first_name"] =  trim(mysql_real_escape_string($_POST['fist_name']));
  $data["last_name"] = trim(mysql_real_escape_string($_POST['last_name']));
  $data["email"] = trim(mysql_real_escape_string($_POST['email']));
  $data["username"] = trim(mysql_real_escape_string($_POST['username']));
  $data["company_name"] = trim(mysql_real_escape_string($_POST['company_name']));
  $data["phone"] = trim(mysql_real_escape_string($_POST['phone']));
  $data["payment_status"] = trim(mysql_real_escape_string($_POST['payment_status']));
  $data["concurrent_login"] = trim(mysql_real_escape_string($_POST['concurrent_login']));
  
  //if($_POST['payment_status']=="trial"){
 // $post_date  = trim(mysql_real_escape_string($_POST['days_allowed']));
 // $query = ", `days_allowed`   = '".date('Y-m-d',strtotime($post_date))."' ";
 /* }else{
    $query = '';
  }*/
  
  $data["is_authorised_nbn_user"] = trim(mysql_real_escape_string($_POST['is_authorised_nbn_user']));
  $data["nbn_user_type"] = trim(mysql_real_escape_string($_POST['nbn_user_type']));

  $data["days_allowed"] = trim(mysql_real_escape_string($_POST['days_allowed']));
  $tempvar = explode('/', $data["days_allowed"]);
  $date_save = $tempvar[2].'-'.$tempvar[1].'-'.$tempvar[0];

  $data["account_status"] = trim(mysql_real_escape_string($_POST['account_status']));
  if($_POST['get_image']!="" && trim($_POST['get_image']))
  {
    $new_path = "uploads/".basename($_POST['get_image']); 
    $current_file ="../".$_POST['get_image'];
    if(file_exists($current_file)){
      if(copy($current_file, "../".$new_path)) {
        unlink($current_file);
        $data["logo"] =  $new_path;
      }
    }
    
    $logo = ", `logo` = '".$data["logo"]."' ";
  }
        //$db->where ('id', $_POST['id']);
  $updateQ = "UPDATE `clients` SET `first_name` = '".$data["first_name"]."',
  `last_name` = '".$data["last_name"]."',
  `email` = '".$data["email"]."',
  `username` = '".$data["username"]."',
  `company_name` = '".$data["company_name"]."',
  `phone` = '".$data["phone"]."',
  `concurrent_login` = '".$data["concurrent_login"]."',
  `payment_status` = '".$data["payment_status"]."',
  `account_status` = '".$data["account_status"]."',
  `is_authorised_nbn_user`   = '".$data["is_authorised_nbn_user"]."',
  `nbn_user_type`   = '".$data["nbn_user_type"]."',
  `days_allowed`   = '".$date_save."'
  $logo
  WHERE `id`='".$_POST['id']."'";
  $update_query = mysql_query($updateQ);
  //$db->update ('clients', $data)
  if($update_query > 0){
    $response['status']='true';
    $response['data']='Client Updated Successfully';
  }else{
    $response['status']='false';
    $response['data']='Please Try Again';
  }
  echo json_encode($response);
  exit;
}

if(isset($_GET['delete_client']) && $_GET['delete_client']=="delete_client")
{
        // delete the row permanetly
       /*$db->where ('id',$_GET['id']); 
       if($db->delete('clients')) */
        $delete = mysql_query("DELETE FROM `clients` WHERE `id`='".$_GET['id']."'");  
      if($delete > 0)
      {
        echo 'true';
      }
      else
      {
       echo 'false';
     }
     exit;
   }

   if(isset($_GET['account_status']) &&  $_GET['account_status']=="change")
   {
    $update = mysql_query("UPDATE `clients` SET `account_status` = '".trim(mysql_real_escape_string($_GET['status']))."' WHERE `id`='".$_GET['id']."'");
    if($update > 0)
    {
      $response['status']='true';
      $response['data']='Account Status Updated';
      
    }else{
      $response['status']='false';
      $response['data']='Please Try Again';
    }
    echo json_encode($response);
    exit;
  }

  if(isset($_POST['assign_layer']) &&  $_POST['assign_layer']=="assign_layer")
  {
    $delete = mysql_query("DELETE FROM `client_layers` WHERE `client_id` = '".$_POST['client_id']."'");
    if($delete > 0)
    {
      if(isset($_POST['layer_ids'])){
        $layer_ids = explode(",",$_POST['layer_ids']);
        foreach($layer_ids as $layer_id)
        {
          $insert = mysql_query("INSERT INTO `client_layers` SET `layer_id` = '".trim(mysql_real_escape_string($layer_id))."', `client_id`='".trim(mysql_real_escape_string($_POST['client_id']))."', `created_at` = '".date("Y-m-d H:i:s")."' ");

        }
      } 
      $response['status']='true';
      $response['data']='Client Layer Saved';
    }
    echo json_encode($response);
    exit;
  }


  if(isset($_GET['delete']) &&  $_GET['delete']=="active_session")
  {
    $where = "WHERE `user_id`='".$_GET["client_id"]."' AND `id`='".$_GET["session_id"]."'";
    $select = mysql_query("SELECT * FROM `user_sessions` $where");
    $selectUserSessionData = mysql_fetch_assoc($select);
    $ref_no = $selectUserSessionData['reference_no'];
    
    $query = "DELETE FROM `user_sessions` $where ";
    $delete = mysql_query($query);
    
    if($delete > 0)
    {
      $current_datetime = date("Y-m-d H:i:s");
      $query = "UPDATE login_history SET `logout_date`='$current_datetime', `updated_date` = '$current_datetime' WHERE `client_id`='".$_GET["client_id"]."' AND reference_no = '$ref_no' ";
      $update_login_history = mysql_query($query);
      $response['status']='true';
      $response['data']='Selected Session Deleted';
    } 
    else 
    {
     $response['status']='false';
     $response['data']='Unable to delete ';
   }
   echo json_encode($response);
   exit;
 }

 if(isset($_POST['delete']) &&  $_POST['delete']=="delete_multiple_sessions")
 {
  if(!empty($_POST['session_ids']))
  { 
    $count = 0;
    foreach($_POST['session_ids'] as $session_id){
      $where = " WHERE `id`='".$session_id."' ";
      $selectSession = mysql_query("SELECT * FROM `user_sessions` $where");
      $selectSessionRow = mysql_fetch_assoc($selectSession);
      $ref_no = $selectSessionRow['reference_no'];
      
      $query = "DELETE FROM `user_sessions` $where";
      $delete = mysql_query($query);
      if($delete > 0){
        $current_datetime = date("Y-m-d H:i:s");
        $query = "UPDATE login_history SET `logout_date`='$current_datetime', `updated_date` = '$current_datetime' WHERE reference_no = '$ref_no' ";
        $update_login_history = mysql_query($query);
        $count++; 
      }
    }
    
    if($count > 0)    {
      $response['status']='true';
      $response['data']=$count . ' Session Terminated';
    }
    else  {
     $response['status']='false';
     $response['data']='Unable to terminate sessions. Please try again';
   }  

 }
 else{
  $response['status']='false';
  $response['data']='No session selected to delete';
}

echo json_encode($response);
exit;       
}  
if(isset($_POST['change_password']) &&  $_POST['change_password']=="change_password")
{
 $update = mysql_query("UPDATE `clients` SET `password` = '".md5(trim(mysql_real_escape_string($_POST['password'])))."' WHERE `id`='".$_POST['client_id']."'");
 if($update > 0)
 {
  $response['status']='true';
  $response['data']='Client Password Changed';
  
}else{
  $response['status']='false';
  $response['data']='Please Try Again';
}
echo json_encode($response);
exit;
}

?>