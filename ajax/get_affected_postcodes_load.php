<?php 
include '../includes/config.php';
set_time_limit(0);
extract($_POST);


$postCodeBoundries='';

$newPolygonArray = explode("\n", $polygonArr);


$i=1;
//echo count($newPolygonArray);
foreach($newPolygonArray as $latlong)
{
	$latLongPair = explode(",",$latlong);
	if($i==count($newPolygonArray)){
		$postCodeBoundries .=$latLongPair[0].' '.$latLongPair[1];
	}else{
		$postCodeBoundries .=$latLongPair[0].' '.$latLongPair[1].',';
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
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,500); 
curl_setopt($ch, CURLOPT_TIMEOUT, 500);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
echo json_encode(array("postcodes"=>json_decode($response)));die;
?>