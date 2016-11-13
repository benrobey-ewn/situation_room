<?php
include '../includes/config.php';
function oneLiner ($str)
{
   // $str = nl2br($str);
    $str = str_replace(array("\n","\r","<br>"), '', $str);
    return $str;
}  


	/*print_r($_REQUEST);
	die;*/
	$main_response = array();
  //$sql1 = "select DISTINCT `latitude`,`longitude`,`placemarker_name`,`placemarker_description`,`placemarker_icon`,`layer_type` from `layer_datas` where `layer_type`!='Ericsson Sites' and (`latitude`>'".$_POST['minLat']."' and `latitude`<'".$_POST['maxLat']."') and (`longitude`>'".$_POST['minLong']."' and `longitude`<'".$_POST['maxLong']."') group by `layer_type`,`placemarker_name` order by placemarker_name ASC";
  $sql1 = "CALL prcLayers('".$_POST['minLat']."','".$_POST['maxLat']."','".$_POST['minLong']."','".$_POST['maxLong']."')";
  $rs1=mysql_query($sql1);
  $num1 = mysql_num_rows($rs1);
  while($data=  mysql_fetch_object($rs1))
  {
    $description = html_entity_decode(stripcslashes($data->placemarker_description));
    $place_name = stripslashes($data->placemarker_name);
    $description = preg_replace('/<([^<>]+)>/e', '"<" . str_replace("\\\\\'", \'"\', "$1") . ">"', $description);
    $description = $place_name."</br>".oneLiner($description);

    $response['placemarker_description'] = $description;
    $response['latitude']=$data->latitude;
    $response['longitude']=$data->longitude;
    $response['placemarker_name']=$data->placemarker_name;
    $response['placemarker_icon']=$data->placemarker_icon;
    $response['layer_type']=$data->layer_type;


    $main_response[] = $response;
 }



 $output=array('query'=>$sql1,'polygon_number'=>$_POST['polygon_number'],'status'=>'true','data'=>$main_response);
 echo json_encode($output);
?>