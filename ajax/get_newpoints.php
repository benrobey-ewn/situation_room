<?php
include '../includes/config.php';
function oneLiner ($str)
{
   // $str = nl2br($str);
    $str = str_replace(array("\n","\r"), '', $str);
    return $str;
}  

  $select_layer_value_current = trim(mysql_real_escape_string($_POST['selected_layer_value_current']));
  // for railway track work 
  $select_layer_value_current = str_replace("1,","",$select_layer_value_current);
  $post_minLat  = trim(mysql_real_escape_string($_POST['minLat']));
  $post_maxLat = trim(mysql_real_escape_string($_POST['maxLat']));
  $post_minLong = trim(mysql_real_escape_string($_POST['minLong']));
  $post_maxLong = trim(mysql_real_escape_string($_POST['maxLong']));
  $post_polygon_number  = trim(mysql_real_escape_string($_POST['polygon_number']));

  $check_id_array = array('13','14','15','18','20','21','22','23','24','31','33','54','56','57','58','59','60','61','62','63','65','66','67','68','69','70','71','72','73','74','75','76');
     
	$main_response = array();
 
         $sql1 = "select DISTINCT `latitude`,`longitude`,`placemarker_name`,`placemarker_description`,`placemarker_icon`,`layer_type`,`layer_id` from `layer_datas` where `layer_id` IN (".$select_layer_value_current.") and (`latitude`>'".$post_minLat."' and `latitude`< '".$post_maxLat."') and (`longitude` > '".$post_minLong."' and `longitude`< '".$post_maxLong."') group by `layer_type`,`placemarker_name` order by placemarker_name ASC";
         
        $rs1=mysql_query($sql1);
        //$num1 = mysql_num_rows($rs1);
        while($data=  mysql_fetch_object($rs1))
        {
          $description = html_entity_decode(stripcslashes($data->placemarker_description));
          $place_name = stripslashes($data->placemarker_name);
          $description = preg_replace('/<([^<>]+)>/e', '"<" . str_replace("\\\\\'", \'"\', "$1") . ">"', $description);
          
          if($data->layer_id=='13' || $data->layer_id=='14' || $data->layer_id=='15' || $data->layer_id=='18' || $data->layer_id=='20' || $data->layer_id=='21' || $data->layer_id=='22' || $data->layer_id=='23' || $data->layer_id=='24' || $data->layer_id=='31' || $data->layer_id=='33' || $data->layer_id=='54' || $data->layer_id=='56' || $data->layer_id=='57' || $data->layer_id=='58' || $data->layer_id=='59' || $data->layer_id=='60' || $data->layer_id=='61' || $data->layer_id=='62' || $data->layer_id=='63' || $data->layer_id=='65' || $data->layer_id=='66' || $data->layer_id=='67' || $data->layer_id=='68' || $data->layer_id=='69' || $data->layer_id=='70' || $data->layer_id=='71' || $data->layer_id=='72' || $data->layer_id=='73' || $data->layer_id=='74' || $data->layer_id=='75' || $data->layer_id=='76') {
           } else  {
                   if(!empty($place_name))
                   $description = $place_name."</br>".oneLiner($description);
                   else
                   $description = oneLiner($description);
           }
          //$description = $place_name."</br>".oneLiner($description);
      
          $response['placemarker_description'] = $description;
          $response['latitude']=$data->latitude;
          $response['longitude']=$data->longitude;
          $response['placemarker_name']=$data->placemarker_name;
          $response['placemarker_icon']=$data->placemarker_icon;
          $response['layer_type']=$data->layer_type;
      
          $main_response[] = $response;
        }
    //$output=array('query'=>$sql1,'polygon_number'=>$post_polygon_number,'status'=>'true','data'=>$main_response);
    $output=array('polygon_number'=>$post_polygon_number,'status'=>'true','data'=>$main_response);
    echo json_encode($output);
?>