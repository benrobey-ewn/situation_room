<?php include '../includes/config.php';
 $response=array();
 
 $selectSQL = "SELECT placemarker_name,latitude,longitude from layer_datas where layer_type='Ericsson Sites' ORDER BY placemarker_name";   
 //echo $selectSQL; die;
 $result = mysql_query($selectSQL);
 while($data=  mysql_fetch_array($result)) {
               
           $response[]=$data;
 }
 $output=array('status'=>'true','data'=>$response);
 echo json_encode($output);
?>