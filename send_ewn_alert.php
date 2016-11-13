<?php 
include '../includes/config.php';
set_time_limit(0);

if(isset($_POST['btn_submit'])){


	$postcodes=$_POST['postcodes'];
	$ewn_response=array();

	$Severity=1;

	$BoundaryTypeKey='PC';	
	$extractPostcodes = explode(",",$postcodes);
	foreach ($extractPostcodes as $postcode) {
		$DeliveryLocations[]=array( 'DeliveryHtmlEmail' => true,
											      'DeliverySms' => true,
											      'DeliveryWebsites' => false,
											      'Severity'=> 1,
													'BoundaryTypeKey' => $BoundaryTypeKey,
													'BoundaryCode' =>"AU-".$postcode);

		$postcodesR[]=array('postcode'=>$postcode,'class'=>$class);	
	}
	$alertSubject='Alert Request';
	$alertDescription='Alert Request Sent';

	$ewnPolygon='';
	$AlertGroupKey='2071';
	$alertData = array(
						"AlertKey"=> 465349,
						"AlertGroupKey" => $AlertGroupKey,
						"Subject" =>$alertSubject,
						"TextForWeb" =>$alertDescription,
						"TextForSMS" =>$alertDescription,
						"AlertType" => "GIS",
						"TopicKey" =>1,
						"DeliveryLocations" =>$DeliveryLocations
					); 

	
	$ch = curl_init();
	$url='https://apici.ewn.com.au:55556/v1/rest/json/alert/465349';

	$headers = array('Accept: application/json',
				    'Content-Type: application/json',
				    'APIKey: ZDKAXXFFBERZ2JR1M3ECHLZZIKG2UWX2BICWYJWN29NGQ7TTHQGCCM4GLKH0I6T5',
				    'Host: apici.ewn.com.au');
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_PUT, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($alertData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,500); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	

	$response = curl_exec($ch);
	echo curl_error($ch);
	echo "<pre>";
	print_r($response);die;



}

//$postcodes = explode(",",$_REQUEST['postcodes']);


?>
<form method="post">
<input type="text" name="postcodes">
<input type="submit" name="btn_submit" value="Send">
	

</form>