<?php 
  require_once '../includes/sit_room.php';
  $response = array();

  if(isset($_POST['admin_edit']) && $_POST['admin_edit']!="")
  {
      $picture = "";
      if($_POST['get_image']!="")
      {
          $new_path = "uploads/".basename($_POST['get_image']); 
          $current_file ="../".$_POST['get_image'];
          if(file_exists($current_file)){
              if(copy($current_file, "../".$new_path)) {
                  unlink($current_file);
                  $picture = ', `logo`="'.$new_path.'"';
              }
          }
      }

        $data["first_name"] = trim(mysql_real_escape_string($_POST['fist_name']));
        $data["last_name"] = trim(mysql_real_escape_string($_POST['last_name']));
        $data["username"] = trim(mysql_real_escape_string($_POST['username']));
        $data["email"] = trim(mysql_real_escape_string($_POST['email']));
        $data["phone"] = trim(mysql_real_escape_string($_POST['phone_no']));

        $update_admin = mysql_query("UPDATE `clients` SET `first_name`='".$data['first_name']."',
                                                        `last_name`='".$data['last_name']."',
                                                        `email`='".$data['email']."',
                                                        `phone`='".$data['phone']."'
                                                        $picture
                                                        WHERE `id` = '".$_SESSION['admin_id']."'");
        if($update_admin  > 0)
        {
            $_SESSION['admin_name']=$data['first_name']." ".$data['last_name'];
            $response['status']='true';
            $response['data']='User Updated Successfully';
        }
        else
        {
            $response['status']='false';
            $response['data']='Please Try Again';
        }

        echo json_encode($response);
  } 

  if(isset($_POST['change_password']) && $_POST['change_password']=="change_password") {
      $checkPass = "SELECT * FROM `clients` WHERE `password`='".md5($_POST['old_pass'])."' AND `id` = '".$_SESSION['admin_id']."'";
      $checkPassRes = mysql_query($checkPass);
      if(mysql_num_rows($checkPassRes) <= 0){
          $response['status']='false';
           $response['data']='Old password is incorrect';
      } else {
          $update_admin = mysql_query("UPDATE `clients` SET `password`='".md5($_POST['password'])."' WHERE `id` = '".$_SESSION['admin_id']."'");
         if($update_admin  > 0)
         {
            $response['status']='true';
            $response['data']='Password Updated Successfully';
         }
         else
         {
            $response['status']='false';
            $response['data']='Please Try Again';
         }
      }
       echo json_encode($response);
  }

  ?>