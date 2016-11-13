<?php  header("Access-Control-Allow-Origin: *");
     include '../includes/config.php';
    //$data_string=file_get_contents('php://input');
    //$parametes=json_decode($data_string);
    //print_r(var_dump($data_string));
    //print_r($data_string);
    //print_r($_POST);
    $viewportLatMin=$_POST['viewportLatMin'];
    $viewportLatMax=$_POST['viewportLatMax'];
    
    $viewportLonMin=$_POST['viewportLonMin'];
    $viewportLonMax=$_POST['viewportLonMax'];
    
    $viewportZoom=$_POST['viewportZoom'];
    $radarType=$_POST['radarType'];
    /*if($radarType=='256' || $radarType=='512')
        $radarType='256';
    else
        $radarType='128';
    */
    $radarType=$radarType;
    $response=array();
    $selectSQL = "SELECT RC.radar_key, RC.radar_code,RC.radar_name, RC.min_lat, RC.min_long, RC.max_lat, RC.max_long, RC.radar_type, RC.radar_type, RI.image_name, RI.radar_date_time, RI.insert_date from radar_codes RC INNER JOIN radar_images RI on RC.radar_id = RI.radar_id WHERE RC.radar_type=".$radarType." AND  RC.min_long <".$viewportLonMin." AND max_long > ".$viewportLonMax." AND min_lat <".$viewportLatMin." AND max_lat >".$viewportLatMax." ORDER BY RC.radar_code,RI.radar_date_time ASC";   
    $result = mysql_query($selectSQL);
    while($data=  mysql_fetch_array($result)) {
        $radar_obj = new stdClass();
        $radar_obj->RadarKey=$data['radar_key'];
        $radar_obj->RadarName = $data['radar_name'];
        $radar_obj->RadarCode = $data['radar_code'];
        $radar_obj->RadarType=$data['radar_type'];
        //$radar_obj->centerLat = '';
        //$radar_obj->centerLon = '';
        $radar_obj->overlayTLlat = $data['max_lat'];
        $radar_obj->overlayTLlon = $data['min_long'];
        $radar_obj->overlayBRlat = $data['min_lat'];
        $radar_obj->overlayBRlon = $data['max_long'];
        $radar_obj->RadarImageKey = '';
        $radar_obj->Added = $data['insert_date'].'Z';
        $radar_obj->fn = $data['image_name'];
        $radar_obj->url = 'radar_images/'.$data['image_name'];
        $radar_obj->imageDate = $data['radar_date_time'].'Z';
        
        $response[]=$radar_obj;
    }
    //echo '<pre>';
    //print_r($response);
    //die;
    $output=array('status'=>'true','data'=>$response,'count'=>count($response));
    echo json_encode($output);
?>