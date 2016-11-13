<?php 
require_once '../includes/sit_room.php';
if(isset($_GET['client_id']) && $_GET['client_id']!="")
{
     /*$db->where ("client_id",$_GET['client_id']);
     $client_layer_data = $db->get("client_layers");*/
     
     $ids  = array();

     /*$db->where ("id",$_GET['client_id']);
     $client_data = $db->get("clients");*/

     $client_data = mysql_fetch_assoc(mysql_query("SELECT * FROM  clients WHERE id = '".$_GET['client_id']."'"));

     $data = mysql_query("SELECT * FROM client_layers WHERE `client_id`='".$_GET['client_id']."'");
     while ($clients_layers = mysql_fetch_assoc($data)) {
         $ids[] = $clients_layers['layer_id'];

     }
     /*$layer = $db->rawQuery('SELECT DISTINCT `layer_id`,`layer_type` FROM `layer_datas`');*/
     $data = mysql_query("SELECT DISTINCT `layer_id`,`layer_type` FROM `layer_datas` WHERE layer_id > 10");
     

     
     $select_box = '<div><label for="dynamic_select">Choose Client Layers</label> &nbsp; &nbsp; &nbsp; <select class="dynamic_select" multiple="multiple" name="layer_ids[]" data-placeholder="Choose Client Layers" id="dynamic_select" style="width:350px;">';
     while($layer = mysql_fetch_assoc($data))
     { 
         $selected="";
         if(in_array($layer['layer_id'], $ids)){
             $selected = 'selected="selected"';
         }
         $select_box .= '<option value="'.$layer['layer_id'].'" '.$selected.'>'.$layer['layer_type'].'</option>';

     }
     $select_box .= '</div></select>';

    
      
      echo json_encode(
          array(
                  "client_name"=>$client_data['first_name'],
                  "client_id"=>$_GET['client_id'],
                  "content"=>$select_box
              )
          );
      exit;
}
?>

