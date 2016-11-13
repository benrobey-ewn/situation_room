<?php 
include '../includes/config.php';
include '../includes/functions.php';
extract($_POST);
$postCodeBoundries='';
$firstIndex = array($polygonArr[0]);
$newPolygonArray = array_merge($polygonArr,$firstIndex);
$i=1;
//echo count($newPolygonArray);
foreach($newPolygonArray as $latlong)
{
	$latLongPair = explode(",",$latlong);
	if($i==count($newPolygonArray)){
		$postCodeBoundries .=$latLongPair[1].' '.$latLongPair[0];
	}else{
		$postCodeBoundries .=$latLongPair[1].' '.$latLongPair[0].',';
	}
	$i++;
}
$alertData = array(
					"BoundaryTypeKey"=>"PC",
					"Polygon" =>$postCodeBoundries,
					
				); 
	
$ch = curl_init();
$url='https://apici.ewn.com.au:55556/v1/rest/json/assetsinpolygon';

$headers = array('Accept: application/json',
			    'Content-Type: application/json',
			    'APIKey: ZDKAXXFFBERZ2JR1M3ECHLZZIKG2UWX2BICWYJWN29NGQ7TTHQGCCM4GLKH0I6T5',
			    'Host: apici.ewn.com.au');
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($alertData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,1);
curl_setopt($ch, CURLOPT_TIMEOUT, 40);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (curl_error($ch)) {
    	$response =  'error:' . curl_error($ch);
    	$log_api_data = array(
		 "request"=> $alertData,
		 "error" =>  curl_error($ch),
		 "httpcode"=>$httpcode,
		 "response" => $response,
		 "status" => 'false'
		);

	log_input('assetsinpolygon',$log_api_data);
	echo json_encode(array("postcodes"=>"error"));die;  
    } else {
    	$response =  json_encode(array("postcodes"=>json_decode($response)));
    	$log_api_data = array(
						 "request"=> $alertData,
						 "error" =>  curl_error($ch),
						 "httpcode"=>$httpcode,
						 "response" => $response,
						 "status" => 'false'
						);

		log_input('assetsinpolygon',$log_api_data);
	echo $response;die;
	}



?>