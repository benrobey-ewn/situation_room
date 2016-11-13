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

  switch ($_POST['selected_layer_value_current']) {
    case '1':
      $layer_selected = 'Au Railway Tracks';
      break;

    case '2':
      $layer_selected = 'Au Telephone Exchanges';
      break;
   
    case '3':
      $layer_selected = 'Broadcast Transmitter';
      break;
   
    case '5':
      $layer_selected = 'Au Electricity Transmission Substations';
      break;
   
    case '6':
      $layer_selected = 'Au Major Airport Terminals';
      break;

    case '7':
      $layer_selected = 'Au Petrol Stations';
      break;
   
    case '8':
      $layer_selected = 'Au Ports';
      break;
  
    case '9':
      $layer_selected = 'Au Operating Mines';
      break;
  
    case '10':
      $layer_selected = 'Au Major power Stations';
      break;

    
    default:
      $layer_selected = '';
      break;
  }







  /*Get subject of polygon*/
  $sqlp = "SELECT Subject FROM Alerts where `id`='".$_POST['polygonPrimaryKey']."'";
  $rsp = mysql_query($sqlp);
  $fetchp = mysql_fetch_object($rsp);

  /*Get subject of polygon*/


  $main_response = array();
  
  if($layer_selected!=='') {
  $sql1 = "select `placemarker_name`,`layer_type`,latitude,longitude from `layer_datas` where `layer_type`!='Ericsson Sites' and `layer_type`='".$layer_selected."' and (`latitude`>'".$_POST['minLat']."' and `latitude`<'".$_POST['maxLat']."') and (`longitude`>'".$_POST['minLong']."' and `longitude`<'".$_POST['maxLong']."') group by `layer_type`,`placemarker_name` order by placemarker_name ASC";
  } else {
    $sql1 = "select `placemarker_name`,`layer_type`,latitude,longitude from `layer_datas` where `layer_type`!='Ericsson Sites' and (`latitude`>'".$_POST['minLat']."' and `latitude`<'".$_POST['maxLat']."') and (`longitude`>'".$_POST['minLong']."' and `longitude`<'".$_POST['maxLong']."') group by `layer_type`,`placemarker_name` order by placemarker_name ASC";
  }
  $rs1=mysql_query($sql1);
  $num1 = mysql_num_rows($rs1);
  while($data=  mysql_fetch_object($rs1))
  {
    //$description = html_entity_decode(stripcslashes($data->placemarker_description));
    $place_name = stripslashes($data->placemarker_name);
    //$description = preg_replace('/<([^<>]+)>/e', '"<" . str_replace("\\\\\'", \'"\', "$1") . ">"', $description);
    //$description = $place_name.oneLiner($description);

    //$response['placemarker_description'] = $description;
    $response['polygon_number']=$_POST['polygon_number'];
    $response['latitude']=$data->latitude;
    $response['longitude']=$data->longitude;
    $response['placemarker_name']=$data->placemarker_name;
   // $response['placemarker_icon']=$data->placemarker_icon;
    $response['layer_type']=$data->layer_type;


    $main_response[] = $response;
 }



 $output=array('polygon_subject'=>$fetchp->Subject,'query'=>$sql1,'polygon_number'=>$_POST['polygon_number'],'status'=>'true','data'=>$main_response);
 echo json_encode($output);
?>