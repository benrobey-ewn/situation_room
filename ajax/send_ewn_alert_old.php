<?php 
include '../includes/config.php';
set_time_limit(0);
$postcodes = explode(",",$_REQUEST['postcodes']);
$ewn_response=array();

if($_REQUEST['alert_type']=='Red'){
			$class='activeR';
			$color='Red';
			$Severity=1;
	} else if ($_REQUEST['alert_type']=='#ffcc00') {
			$class='activeA';
			$color='Amber';
			$Severity=0;
	} else if ($_REQUEST['alert_type']=='Black') {
			$class='activeB';
			$color='Black';
			$Severity=2;
	} else {
			$class='activeG';
			$color='Green';
			$Severity=3;
	}
$BoundaryTypeKey='PC';						 
foreach ($postcodes as $postcode) {
	$explodePostcode=explode("-", $postcode);
	$DeliveryLocations[]=array( 'DeliveryHtmlEmail' => true,
										      'DeliverySms' => true,
										      'DeliveryWebsites' => false,
										      "Severity"=> $Severity,
												'BoundaryTypeKey' => $BoundaryTypeKey,
												'BoundaryCode' =>"AU-".$explodePostcode[1]);

	$postcodesR[]=array('postcode'=>$explodePostcode[1],'class'=>$class,'color'=>$color,'id'=>$explodePostcode[0]);	
}
$alertSubject=$_REQUEST['title_nbn_polygon'];
$ewnPolygon='';
$AlertGroupKey='2071';
$alertData = array(
					"AlertKey"=>0,
					"AlertGroupKey" => $AlertGroupKey,
					"Subject" =>$alertSubject,
					"TextForWeb" =>$_REQUEST['nbn_description_polygon'],
					"TextForSMS" =>$_REQUEST['nbn_description_polygon'],
					"AlertType" => "GIS",
					"TopicKey" =>1,
					"DeliveryLocations" =>$DeliveryLocations
				); 
$ch = curl_init();
$url='https://apici.ewn.com.au:55556/v1/rest/json/alert';
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
if ($response === false)
{
	$ewn_response['error']=curl_error($ch);
}else{ 
	$ewn_response['success']=$response;
	$ewn_responst_array=json_decode($response,true);
	if($ewn_responst_array['AlertKey']!=''){
			$alertKey=$ewn_responst_array['AlertKey'];
		}else{
			$alertKey=0;
		}
	$i=0;
	foreach ($ewn_responst_array['DeliveryLocations'] as $value) {
			$search = array('POINT (', ')');
			$replace = array('', '');
			$points=str_replace($search, $replace, $value['Centroid']);
			$points=explode(' ',$points);
			$postcodesR[$i]['lat']=$points[0];
			$postcodesR[$i]['lng']=$points[1];
		$i++;
	}	

	foreach ($postcodes as $postcode) {
	$explodePostcode=explode("-", $postcode);

		$sql = "SELECT nbn_postcode from nbn_layers_info where  nbn_postcode='".$explodePostcode[0]."'";
		$rs=mysql_query($sql);
		$num=mysql_num_rows($rs);
		if($num>0){
			$sql_cmd = "UPDATE `nbn_layers_info` SET nbn_class='".$class."'  WHERE nbn_postcode='".$explodePostcode[0]."'";
			mysql_query($sql_cmd);
		}else{
			$sql_cmd = "INSERT INTO `nbn_layers_info` SET `nbn_postcode` = '".$explodePostcode[0]."',nbn_alertkey='".$alertKey."',`nbn_class` = '".$class."'";
			mysql_query($sql_cmd);

		}
	}
	
}
$output=$postcodesR;
echo json_encode($output,true);die;
?>