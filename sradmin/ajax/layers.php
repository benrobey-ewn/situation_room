<?php
   require_once '../includes/sit_room.php';
   
   $response = array();
   if(isset($_POST['layer_name_update']) && $_POST['layer_name_update']=="layer_name_update"){
     
       $layer_name = mysql_real_escape_string($_POST['layer_name']);
       $layer_id = mysql_real_escape_string($_POST['layer_id']);
       $placemarker_icon = mysql_real_escape_string($_POST['placemarker_icon']);
       $popup_placemarker_icon = mysql_real_escape_string($_POST['popup_placemarker_icon']);

        $update_more = "";
        if($placemarker_icon!=""){
           $new_path = "../../images/layer_markers/".basename($placemarker_icon);
           $current_file ="../".$placemarker_icon;
           if(file_exists($current_file)){
              if(copy($current_file, $new_path)) {
                  unlink($current_file);
                  $update_more = ', `placemarker_icon`="'.basename($placemarker_icon).'"';
              } 
           } 
        }
        if($popup_placemarker_icon!=""){
            $popup_new_path = "../../images/popup_layer_markers/".basename($placemarker_icon);
            $popup_current_file ="../".$popup_placemarker_icon;
            copy($popup_current_file, $popup_new_path);
        }


       $update_layers = mysql_query("UPDATE layers SET layer_name = '".$layer_name."' $update_more  WHERE id='".$layer_id."'");
       $update_layers_data = mysql_query("UPDATE layer_datas SET layer_type = '".$layer_name."' $update_more WHERE layer_id='".$layer_id."' ");
       if($update_layers > 0 && $update_layers_data > 0) {
           $response['status']='true';
           $response['data']="Layer Name  Updated Successfully ".$update_more;
       } else {
           $response['status']='false';
           $response['data']='Please Try Again';
       } 
       echo json_encode($response);
     exit;
   }

   if(isset($_POST['layer_data_update']) && $_POST['layer_data_update']=="layer_data_update"){
     
       $placemarker_name = mysql_real_escape_string($_POST['placemarker_name']);
       $placemarker_description = mysql_real_escape_string($_POST['placemarker_description']);
       $latitude = mysql_real_escape_string($_POST['latitude']);
       $longitude = mysql_real_escape_string($_POST['longitude']);
       $layer_data_id = mysql_real_escape_string($_POST['layer_data_id']);
       $layer_id = mysql_real_escape_string($_POST['layer_id']);

       $query= "UPDATE `layer_datas` SET  `placemarker_name` = '".$placemarker_name."', `placemarker_description` = '".$placemarker_description."', `latitude` = '".$latitude."',
                                          `longitude` = '".$longitude."' WHERE  `layer_data_id` = '".$layer_data_id."' AND  `layer_id` = '".$layer_id."'";
       $update_layer_data = mysql_query($query) or die(mysql_error());

       if($update_layer_data > 0) {
           $response['status']='true';
           $response['data']='Layer Details Updated Successfully';
       } else {
           $response['status']='false';
           $response['data']='Please Try Again';
       } 
       echo json_encode($response);
       exit;
   }

   if(isset($_GET['delete_layer']) && $_GET['delete_layer']=="1"){

      $delete_layer_data  = mysql_query("DELETE FROM `layer_datas` WHERE `layer_data_id`='".$_GET['layer_data_id']."'");
      if($delete_layer_data  > 0){
           $response['status']='true';
           $response['data']='Layer Data Deleted';
      } else {
           $response['status']='false';
           $response['data']='Please Try Again';
      } 
       echo json_encode($response);
       exit;
   }  
 
?>
