<?php include '../includes/config.php';
$response=array();
switch ($_POST['layer_type']) {
  case '1':
    $layer_type='Au Railway Tracks';
    # code...
    break;

  case '2':
   $layer_type='Au Telephone Exchanges';
    # code...
    break;


  case '3':
   $layer_type='Broadcast Transmitter';
    # code...
    break;


  case '4':
    $layer_type='AU Oil and Gas Pipelines';
    # code...
    break;


  case '5':
    $layer_type='Au Electricity Transmission Substations';
    # code...
    break;

  case '6':
   $layer_type='Au Major Airport Terminals';
    # code...
    break;

  case '7':
    $layer_type='Au Petrol Stations';
    # code...
    break;

  case '8':
   $layer_type='Au Ports';
    # code...
    break;
  
   case '9':
    $layer_type='AU Operating Mines';
    # code...
    break;

 case '10':
    $layer_type='AU Major Power Stations';
    # code...
    break;
  
  case '11':
    $layer_type='Ericsson Sites';
    # code...
    break;

  default:
    $layer_type='Au Major Airport Terminals';
    # code...
    break;
}
  
  $selectSQL = "SELECT placemarker_name,placemarker_icon,placemarker_description,latitude,longitude from layer_datas where layer_type='".$layer_type."' ORDER BY placemarker_name"; 
 //echo $selectSQL; die;
 $result = mysql_query($selectSQL);
 while($data=  mysql_fetch_array($result)) {
           $data['placemarker_description']=htmlspecialchars_decode(str_replace("n"," ",stripslashes($data['placemarker_description'])),ENT_QUOTES);
           $response[]=$data;
 }
 $output=array('status'=>'true','data'=>$response);
 echo json_encode($output);
?>