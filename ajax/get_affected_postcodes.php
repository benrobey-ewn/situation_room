<?php
include '../includes/config.php';
include '../includes/functions.php';

error_reporting(0);

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


$client = new GuzzleHttp\Client();
$res = $client->post('https://api.ewn.com.au/v1/rest/json/assetsinpolygon', [
    'headers' => ['Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'APIKey' => '5JYU2GAQOTLX8ZLZPGGEN6KXRIZWNKZUQC6IU5CFUPIAB48AND1IIB89YU5PGH0U',
        'Host' => 'api.ewn.com.au'],
    'body' => json_encode($alertData)
]);


$response = $res->getBody();
$httpCode = $res->getStatusCode();
if (!in_array($httpCode, [200, 201])) {
    $response =  'error:' . curl_error($ch);
    $log_api_data = array(
        "request"=> $alertData,
        "httpcode"=> $httpCode,
        "response" => $response,
        "status" => 'false'
    );

    log_input('assetsinpolygon',$log_api_data);
    echo json_encode(array("postcodes"=>"error"));die;
}
else {
    $response =  json_encode(array("postcodes"=>json_decode($response)));
    $log_api_data = array(
        "request"  =>  $alertData,
        "httpcode" =>  $httpCode,
        "response" =>  $response['postcodes'],
        "status"   =>  'false'
    );

    log_input('assetsinpolygon',$log_api_data);
    echo $response;
}



?>