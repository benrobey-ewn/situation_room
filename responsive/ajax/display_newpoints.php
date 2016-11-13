<?php
include '../includes/config.php';
function oneLiner ($str)
{
   // $str = nl2br($str);
    $str = str_replace(array("\n","\r"), '', $str);
    return $str;
}  

   $icon='';
   
    
    /*Get subject of polygon*/
    $sqlp = "SELECT Subject FROM Alerts where `id`='".$_POST['polygonPrimaryKey']."'";
    $rsp = mysql_query($sqlp);
    $fetchp = mysql_fetch_object($rsp);
  
    /*Get subject of polygon*/

    $main_response = array();

    $sql1 = "select DISTINCT `latitude`,`longitude`,`placemarker_name`,`placemarker_description`,`placemarker_icon`,`layer_type` from `layer_datas` where `layer_id` IN (".$_POST['selected_layer_value_current'].") and (`latitude`>'".$_POST['minLat']."' and `latitude`< '".$_POST['maxLat']."') and (`longitude` > '".$_POST['minLong']."' and `longitude`< '".$_POST['maxLong']."') group by `layer_type`,`placemarker_name` order by placemarker_name ASC";
  
    $rs1=mysql_query($sql1);
    $num1 = mysql_num_rows($rs1);
	if($num1>0) {
		 if(isset($layer_names[$_POST['selected_layer_value_current']])) {
          $layer_details= $layer_names[$_POST['selected_layer_value_current']];
          $layer_selected=$layer_details[0];
          $icon=$layer_details[1];
    }
    while($data=  mysql_fetch_object($rs1))
    {
        $place_name = stripslashes($data->placemarker_name);
        $response['polygon_number']=$_POST['polygon_number'];
        $response['latitude']=$data->latitude;
        $response['longitude']=$data->longitude;
        $response['placemarker_name']=$data->placemarker_name;
		$response['placemarker_icon']=$data->placemarker_icon;
        $response['layer_type']=$data->layer_type;
        
        $main_response[] = $response;
    }
	$output=array('polygon_subject'=>$fetchp->Subject,'query'=>$sql1,'polygon_number'=>$_POST['polygon_number'],'status'=>'true','data'=>$main_response,'icon_name'=>$icon);
	} else {
		$output=array('polygon_subject'=>$fetchp->Subject,'query'=>$sql1,'polygon_number'=>$_POST['polygon_number'],'status'=>'true','data'=>$main_response,'icon_name'=>$icon);
	}
    
    echo json_encode($output);
?>