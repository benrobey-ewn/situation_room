<?php 
set_time_limit(0);
include '../includes/config.php'; 
include '../includes/functions.php';
$postcode = $_REQUEST['postcode'];
$sql = "SELECT nbn_suburbsname from nbn_suburbs where  nbn_postcode_id='".$postcode."'";
$rs=mysql_query($sql);
$num=mysql_num_rows($rs);
if($num>0){
	$row=mysql_fetch_row($rs);
	echo (($row[0]));die;
}else{
	$ch = curl_init();
	$url='https://apici.ewn.com.au:55556/v1/rest/json/suburbsinpostcode';
	$headers = array('Accept: application/json',
				    'Content-Type: application/json',
				    'APIKey: ZDKAXXFFBERZ2JR1M3ECHLZZIKG2UWX2BICWYJWN29NGQ7TTHQGCCM4GLKH0I6T5',
				    'Host: apici.ewn.com.au');
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$postcode);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	if (curl_error($ch)) {
	    	$response_data =  'error:' . curl_error($c);
	    } else {
			$response_data=json_decode($response);
			if (!empty($response_data)) {
				$suburbs=implode(",",$response_data);
				$sql_cmd = "INSERT INTO `nbn_suburbs` SET `nbn_postcode_id` = '".$postcode."',nbn_suburbsname='".$response."'";
				mysql_query($sql_cmd);
			}
	}
	$log_api_data = array(
		 "request"=> $postcode,
		 "error" =>  curl_error($c),
		 "response" => $response_data
		);

	log_input('suburbsinpostcode',$log_api_data);
	echo $response;die;


	

}


?>