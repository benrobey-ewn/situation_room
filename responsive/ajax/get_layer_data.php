<?php include '../includes/config.php';

function oneLiner ($str)
{
   // $str = nl2br($str);
    $str = str_replace(array("\n","\r"), '', $str);
    return $str;
}  


$response=array();
$layer_type='';

    $selectSQL = "SELECT DISTINCT placemarker_name,placemarker_icon,placemarker_description,latitude,longitude from layer_datas where layer_id='".$_POST['layer_type']."' ORDER BY placemarker_name";
 
    $result = mysql_query($selectSQL);
	$num1 = mysql_num_rows($result);
	if($num1>0) {
		if(isset($layer_names[$_POST['layer_type']])) {
		  $layer_details= $layer_names[$_POST['layer_type']];
		  $layer_type=$layer_details[0];
	   }
    //print_r($result); die;
 
    while($data=  mysql_fetch_array($result)) {
       $description = html_entity_decode(stripcslashes($data['placemarker_description']));
       $place_name = stripslashes($data['placemarker_name']);
       $description = preg_replace('/<([^<>]+)>/e', '"<" . str_replace("\\\\\'", \'"\', "$1") . ">"', $description);
     if($_POST['layer_type']=='13' || $_POST['layer_type']=='14' || $_POST['layer_type']=='15' || $_POST['layer_type']=='18' || $_POST['layer_type']=='20' || $_POST['layer_type']=='21' || $_POST['layer_type']=='22' || $_POST['layer_type']=='23' || $_POST['layer_type']=='24' || $_POST['layer_type']=='31' || $_POST['layer_type']=='33' || $_POST['layer_type']=='54' || $_POST['layer_type']=='56' || $_POST['layer_type']=='57' || $_POST['layer_type']=='58' || $_POST['layer_type']=='59' || $_POST['layer_type']=='60' || $_POST['layer_type']=='61' || $_POST['layer_type']=='62' || $_POST['layer_type']=='63' || $_POST['layer_type']=='65' || $_POST['layer_type']=='66' || $_POST['layer_type']=='67' || $_POST['layer_type']=='68' || $_POST['layer_type']=='69' || $_POST['layer_type']=='70' || $_POST['layer_type']=='71' || $_POST['layer_type']=='72' || $_POST['layer_type']=='73' || $_POST['layer_type']=='74' || $_POST['layer_type']=='75' || $_POST['layer_type']=='76') {
       } else  {
               if(!empty($place_name))
               $description = $place_name."</br>".oneLiner($description);
               else
               $description = oneLiner($description);
       }
   
   
              $data['placemarker_description']=$description;
			  $data['layer_id']=$_POST['layer_type'];
              $response[]=$data;
    }
    $output=array('status'=>'true','data'=>$response,'layer_name'=>$layer_type,'layer_id'=>$_POST['layer_type']);
    } else {
		$output=array('status'=>'true','data'=>$response,'layer_name'=>$layer_type,'layer_id'=>$_POST['layer_type']);
	}
	echo json_encode($output);
?>