<?php
set_time_limit(0);
function getSuburbName($postcode){
	$sql = "SELECT nbn_suburbsname from nbn_suburbs where  nbn_postcode_id='".$postcode."'";
	$rs=mysql_query($sql);
	$num=mysql_num_rows($rs);
	if($num>0){
		$row=mysql_fetch_row($rs);
		return json_decode($row[0]);die;
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
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,500); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 500);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		$postcodes=json_decode($response);
		if (!empty($postcodes)) {
			$suburbs=implode(",",$postcodes);
			$sql_cmd = "INSERT INTO `nbn_suburbs` SET `nbn_postcode_id` = '".$postcode."',nbn_suburbsname='".$response."'";
			mysql_query($sql_cmd);
		}
	    return json_decode($response);die;
	}
	
}
function getAlertbyPostcode($postcode){
	$sql = "SELECT nbn_color,alert_id from nbn_layers_info where  nbn_postcode='".$postcode."'";
	$rs=mysql_query($sql);
	$num=mysql_num_rows($rs);
	if($num>0){
		$result=mysql_fetch_row($rs);
	}else{
		$result='null';
	}
	return $result;
}
function getAlertCount($alert_id){
	$sql = "SELECT nbn_id from nbn_layers_info where alert_id=".$alert_id;
	$rs=mysql_query($sql);
	$num=mysql_num_rows($rs);
	if($num>0){
		return 1;
	}else{
		return 0;
	}
}
function curlcall($url,$request){
	$ch = curl_init();
	$headers = array('Accept: application/json',
			    'Content-Type: application/json',
			    'APIKey: ZDKAXXFFBERZ2JR1M3ECHLZZIKG2UWX2BICWYJWN29NGQ7TTHQGCCM4GLKH0I6T5',
			    'Host: apici.ewn.com.au');
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($request));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	if ($response === false)
	{
	$ewn_response['error']=curl_error($ch);
	}else{

	}
}
function log_input($apicall,$data){
 $put_path ="../logs/ewn_api/".date("d-m-Y")."/"; 
 $filename = $apicall.".log";
 if (!file_exists($put_path)) {
  mkdir($put_path);
 }
 file_put_contents($put_path.$filename,json_encode($data)."\n\n",FILE_APPEND);
}


?>