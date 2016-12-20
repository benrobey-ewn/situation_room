<?php
//
// A very simple PHP example that sends a HTTP POST to a remote site
//
  //print_r($_POST); die;
  //echo file_get_contents('php://input'); die;
  $viewportLatMin='-10.499923215063747';
     $viewportLatMax='-46.98865521026523';
    $viewportLonMin='175.46223087499993';
    $viewportLonMax='94.60285587499993';
    $viewportZoom='4';
	$radarType='512';
	
	

//$postparameters="minLat=$viewportLatMin&maxLat=$viewportLatMax&minLon=$viewportLonMin&maxLon=$viewportLonMax&zoom=$viewportZoom&radarType=$radarType";

$data = array("minLat" => $viewportLatMin, "maxLat" => $viewportLatMax, "minLon"=>$viewportLonMin, "maxLon"=>$viewportLonMax, "zoom"=>$viewportZoom, "radarType"=>$radarType);                                                                    
//$data_string = json_encode($data); 
$data_string=file_get_contents('php://input');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"http://www.ewn.com.au/exo/webextensions.asmx/GetRadarImagesForViewPort");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);



// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);     

$server_output = curl_exec ($ch);

curl_close ($ch);

//$json = json_decode($server_output, true); 

//print_r($json);

//die;

//print_r($server_output);
//echo "<pre>";

//header('Content-Type: application/json');
echo $server_output; die;
//echo $server_output;
//echo json_encode($server_output); die;
//print_r(json_decode($server_output));

?>