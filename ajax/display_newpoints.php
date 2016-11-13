<?php
include '../includes/config.php';
function oneLiner ($str)
{
   // $str = nl2br($str);
    $str = str_replace(array("\n","\r"), '', $str);
    return $str;
}  

    $icon='';
    $post_polygon_number = trim(mysql_real_escape_string($_POST['polygon_number']));
    $selected_layer_value_current = trim(mysql_real_escape_string($_POST['selected_layer_value_current']));
    // for railway track work 
    $selected_layer_value_current = str_replace("1,","",$selected_layer_value_current);
    $post_minLat = trim(mysql_real_escape_string($_POST['minLat']));
    $post_maxLat = trim(mysql_real_escape_string($_POST['maxLat']));
    $post_minLong = trim(mysql_real_escape_string($_POST['minLong']));
    $post_maxLong = trim(mysql_real_escape_string($_POST['maxLong']));
    $post_polygonprimary_key = trim(mysql_real_escape_string($_POST['polygonPrimaryKey']));

    /*Get subject of polygon*/
    $sqlp = "SELECT Subject FROM Alerts where `id`='".$post_polygonprimary_key."'";
    $rsp = mysql_query($sqlp);
    $fetchp = mysql_fetch_object($rsp);
  
    /*Get subject of polygon*/

    $main_response = array();

    $sql1 = "select DISTINCT `latitude`,`longitude`,`placemarker_name`,`placemarker_description`,`placemarker_icon`,`layer_type` from `layer_datas` where `layer_id` IN (".$selected_layer_value_current.") and (`latitude`>'".$post_minLat."' and `latitude`< '".$post_maxLat."') and (`longitude` > '".$post_minLong."' and `longitude`< '".$post_maxLong."') group by `layer_type`,`placemarker_name` order by placemarker_name ASC";
  
    $rs1=mysql_query($sql1);
    $num1 = mysql_num_rows($rs1);
    if($num1>0) {
         if(isset($layer_names[$selected_layer_value_current])) {
          $layer_details= $layer_names[$selected_layer_value_current];
          $layer_selected=$layer_details[0];
          $icon=$layer_details[1];
    }
    while($data=  mysql_fetch_object($rs1))
    {
        $place_name = stripslashes($data->placemarker_name);
        $response['polygon_number']=$post_polygon_number;
        $response['latitude']=$data->latitude;
        $response['longitude']=$data->longitude;
        $response['placemarker_name']=$data->placemarker_name;
		$response['placemarker_icon']=$data->placemarker_icon;
        $response['layer_type']=$data->layer_type;
        
        $main_response[] = $response;
    }
    //$output=array('polygon_subject'=>$fetchp->Subject,'query'=>$sql1,'polygon_number'=>$post_polygon_number,'status'=>'true','data'=>$main_response,'icon_name'=>$icon);
	$output=array('polygon_subject'=>$fetchp->Subject,'polygon_number'=>$post_polygon_number,'status'=>'true','data'=>$main_response,'icon_name'=>$icon);
	} else {
      //  $output=array('polygon_subject'=>$fetchp->Subject,'query'=>$sql1,'polygon_number'=>$post_polygon_number,'status'=>'true','data'=>$main_response,'icon_name'=>$icon);
		$output=array('polygon_subject'=>$fetchp->Subject,'polygon_number'=>$post_polygon_number,'status'=>'true','data'=>$main_response,'icon_name'=>$icon);
	}
    
    echo json_encode($output);
?>