  <?php include '../includes/config.php';
  function oneLiner ($str)
  {
   $str = str_replace(array("\n","\r"), '', $str);
   return $str;
 }
 $post_layer_type = trim(mysql_real_escape_string($_POST['layer_type']));

 $response=array();
 $layer_type='';
 $selectSQL = "SELECT DISTINCT placemarker_name,placemarker_icon,placemarker_description,latitude,longitude from layer_datas where layer_id='".$post_layer_type."' ORDER BY placemarker_name";

 $result = mysql_query($selectSQL);
 $num1 = mysql_num_rows($result);
 if($num1>0) {
  if(isset($layer_names[$post_layer_type])) {
    $layer_details= $layer_names[$post_layer_type];
    $layer_type=$layer_details[0];
  }
  while($data=  mysql_fetch_array($result)) {
   $description = html_entity_decode(stripcslashes($data['placemarker_description']));
   $place_name = stripslashes($data['placemarker_name']);
   $description = preg_replace('/<([^<>]+)>/e', '"<" . str_replace("\\\\\'", \'"\', "$1") . ">"', $description);

   $matching_array = array(13,14,15,18,20,21,22,23,24,31,33,54,56,57,58,59,60,61,62,63,65,66,67,68,69,70,71,72,73,74,75,76,82);

   if(in_array($post_layer_type, $matching_array)){
   } else  {
     if(!empty($place_name)){
       if($place_name == $description){
         $description = $place_name;
       } else {
        $description = $place_name."</br>".oneLiner($description);
      }
    }  else {
      $description = oneLiner($description);

    }
  }


  $data['placemarker_description']=$description;
  $data['layer_id']=$post_layer_type;
  $response[]=$data;
}
$output=array('status'=>'true','data'=>$response,'layer_name'=>$layer_type,'layer_id'=>$post_layer_type);
} else {
  $output=array('status'=>'true','data'=>$response,'layer_name'=>$layer_type,'layer_id'=>$post_layer_type);
}
echo json_encode($output);
?>