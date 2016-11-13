<?php 
include '../includes/config.php';
include '../includes/functions.php';
$postcodes = explode(",",$_REQUEST['postcodes']);
$ewn_response=array();

if($_REQUEST['alert_type']=='Green' && $_REQUEST['alert_id']!=''){
	$class='activeG';
	$color='Green';
	$Severity=3;
	$BoundaryTypeKey='PC';
	$alert_id=$_REQUEST['alert_id'];						 
	foreach($postcodes as $postcode) {
				
				$DeliveryLocations[]=array( 'DeliveryHtmlEmail' => true,
										      'DeliverySms' => true,
										      'DeliveryWebsites' => false,
										       "Severity"=> $Severity,
												'BoundaryTypeKey' => $BoundaryTypeKey,
												'BoundaryCode' =>"AU-".$postcode);
				$postcodesR[]=array('postcode'=>$postcode,'class'=>$class,'color'=>$color,'current_thread'=>'null','current_alert_id'=>'null');
	}
	$alertSubject=$_REQUEST['title_nbn_polygon'];
	$ewnPolygon='';
	$AlertGroupKey='2159';
	$alertData = array(
						"AlertKey"=>0,
						"AlertGroupKey" => $AlertGroupKey,
						"Subject" =>$alertSubject,
						"TextForWeb" =>$_REQUEST['nbn_description_polygon'],
						"TextForSMS" =>$_REQUEST['nbn_description_polygon'],
						"AlertType" => "GIS",
						"DeliveryLocations" =>$DeliveryLocations
					);
	$ch = curl_init();
	$url='https://api.ewn.com.au/v1/rest/json/alert';
	$headers = array('Accept: application/json',
			    'Content-Type: application/json',
			    'APIKey: 5JYU2GAQOTLX8ZLZPGGEN6KXRIZWNKZUQC6IU5CFUPIAB48AND1IIB89YU5PGH0U',
			    'Host: api.ewn.com.au');
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($alertData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
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
		log_input('alert',$log_api_data);
		echo json_encode(array("postdata"=>"error"));die;  
    } else {
    	$ewn_responst_array=json_decode($response,true);
    	$sql_cmd = 'DELETE FROM nbn_layers_info WHERE alert_id='.$alert_id;
		mysql_query($sql_cmd);
		$del_alert = 'DELETE FROM nbn_alerts WHERE nbn_alert_id='.$_REQUEST['alert_id'];
		mysql_query($del_alert);
		$output=array('postdata'=>$postcodesR,'alert_id'=>$alert_id,'alert_title'=>$alertSubject,'alert_color'=>$color,'alert_description'=>$_REQUEST['nbn_description_polygon'],'status'=>'multipleupdate');
		echo json_encode($output,true);die;
    }	
}elseif($_REQUEST['alert_id']!=''){

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
	
	$DeliveryLocations[]=array( 'DeliveryHtmlEmail' => true,
							      'DeliverySms' => true,
							      'DeliveryWebsites' => false,
							      "Severity"=> $Severity,
									'BoundaryTypeKey' => $BoundaryTypeKey,
									'BoundaryCode' =>"AU-".$postcode);
	$checkAlert=getAlertbyPostcode($postcode);
	if($checkAlert!='null'){
		$postcodesR[]=array('postcode'=>$postcode,'class'=>$class,'color'=>$color,'suburb'=>getSuburbName($postcode),'current_thread'=>$checkAlert[0],'current_alert_id'=>$checkAlert[1]);
	}else{
		$postcodesR[]=array('postcode'=>$postcode,'class'=>$class,'color'=>$color,'suburb'=>getSuburbName($postcode),'current_thread'=>'null','current_alert_id'=>'null');
	}
}
	$alertSubject=$_REQUEST['title_nbn_polygon'];
	$ewnPolygon='';
	$AlertGroupKey='2159';
	$alertData = array(
						"AlertKey"=>0,
						"AlertGroupKey" => $AlertGroupKey,
						"Subject" =>$alertSubject,
						"TextForWeb" =>$_REQUEST['nbn_description_polygon'],
						"TextForSMS" =>$_REQUEST['nbn_description_polygon'],
						"AlertType" => "GIS",
						"DeliveryLocations" =>$DeliveryLocations
					);


	$ch = curl_init();
	$url='https://api.ewn.com.au/v1/rest/json/alert';
	$headers = array('Accept: application/json',
			    'Content-Type: application/json',
			    'APIKey: 5JYU2GAQOTLX8ZLZPGGEN6KXRIZWNKZUQC6IU5CFUPIAB48AND1IIB89YU5PGH0U',
			    'Host: api.ewn.com.au');
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($alertData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
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

	log_input('alert',$log_api_data);
	echo json_encode(array("postdata"=>"error"));die;  
    } else {
    	$ewn_responst_array=json_decode($response,true);

		$sql = "SELECT nbn_alert_id from nbn_alerts where nbn_alert_title='".trim($alertSubject)."'";
		$rs=mysql_query($sql);
		$num=mysql_num_rows($rs);
		if($num>0){
			$row=mysql_fetch_row($rs);
			$alert_id=$row[0];
			if($alert_id!=$_REQUEST['alert_id']){
			$del_alert = 'DELETE FROM nbn_alerts WHERE nbn_alert_id='.$_REQUEST['alert_id'];
			mysql_query($del_alert);
			}
		}else{
			$sql_cmd = "INSERT INTO `nbn_alerts` SET `nbn_alert_title` = '".$alertSubject."',nbn_alert_desc='".$_REQUEST['nbn_description_polygon']."',`nbn_alert_type` = '".$color."'";
			mysql_query($sql_cmd);
			$alert_id=mysql_insert_id();   
			
			$del_alert = 'DELETE FROM nbn_alerts WHERE nbn_alert_id='.$_REQUEST['alert_id'];
			mysql_query($del_alert);

		}
		foreach ($postcodes as $postcode) {
				$alertKey=$ewn_responst_array['AlertKey'];
				$sql = "SELECT nbn_postcode from nbn_layers_info where  nbn_postcode='".$postcode."'";
				$rs=mysql_query($sql);
				$num=mysql_num_rows($rs);
				if($num>0){
					$sql_cmd = "UPDATE `nbn_layers_info` SET nbn_class='".$class."' , nbn_alertkey='".$alertKey."',`nbn_color` = '".$color."',`alert_id` = '".$alert_id."' WHERE nbn_postcode='".$postcode."'";
					mysql_query($sql_cmd);
				}else{
					$sql_cmd = "INSERT INTO `nbn_layers_info` SET `nbn_postcode` = '".$postcode."',nbn_alertkey='".$alertKey."',`nbn_class` = '".$class."',`nbn_color` = '".$color."',`alert_id` = '".$alert_id."'";
					mysql_query($sql_cmd);
				}
		}
		$log_api_data = array(
						 "request"=> $alertData,
						 "error" =>  curl_error($ch),
						 "httpcode"=>$httpcode,
						 "response" => $response,
						 "status" => 'false'
						);

		log_input('alert',$log_api_data);
		$output=array('postdata'=>$postcodesR,'alert_id'=>$alert_id,'alert_title'=>$alertSubject,'alert_color'=>$color,'status'=>'','alert_description'=>$_REQUEST['nbn_description_polygon'],'status'=>'update','prev_alert_id'=>$_REQUEST['alert_id']);
		echo json_encode($output,true);die;
	}

}else{
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
	
	$DeliveryLocations[]=array( 'DeliveryHtmlEmail' => true,
							      'DeliverySms' => true,
							      'DeliveryWebsites' => false,
							      "Severity"=> $Severity,
									'BoundaryTypeKey' => $BoundaryTypeKey,
									'BoundaryCode' =>"AU-".$postcode);
	$checkAlert=getAlertbyPostcode($postcode);
	if($checkAlert!='null'){
		$postcodesR[]=array('postcode'=>$postcode,'class'=>$class,'color'=>$color,'suburb'=>getSuburbName($postcode),'current_thread'=>$checkAlert[0],'current_alert_id'=>$checkAlert[1]);
	}else{
		$postcodesR[]=array('postcode'=>$postcode,'class'=>$class,'color'=>$color,'suburb'=>getSuburbName($postcode),'current_thread'=>'null','current_alert_id'=>'null');
	}
}
$alertSubject=$_REQUEST['title_nbn_polygon'];
$ewnPolygon='';
$AlertGroupKey='2159';
$alertData = array(
					"AlertKey"=>0,
					"AlertGroupKey" => $AlertGroupKey,
					"Subject" =>$alertSubject,
					"TextForWeb" =>$_REQUEST['nbn_description_polygon'],
					"TextForSMS" =>$_REQUEST['nbn_description_polygon'],
					"AlertType" => "GIS",
					"DeliveryLocations" =>$DeliveryLocations
				);
	$ch = curl_init();
	$url='https://api.ewn.com.au/v1/rest/json/alert';
	$headers = array('Accept: application/json',
			    'Content-Type: application/json',
			    'APIKey: 5JYU2GAQOTLX8ZLZPGGEN6KXRIZWNKZUQC6IU5CFUPIAB48AND1IIB89YU5PGH0U',
			    'Host: api.ewn.com.au');
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($alertData));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
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

	log_input('alert',$log_api_data);
	echo json_encode(array("postdata"=>"error"));die;  
    }else{
    	$ewn_responst_array=json_decode($response,true);
    	$sql = "SELECT nbn_alert_id from nbn_alerts where nbn_alert_title='".trim($alertSubject)."'";
		$rs=mysql_query($sql);
		$num=mysql_num_rows($rs);
		if($num>0){
			$row=mysql_fetch_row($rs);
			$alert_id=$row[0];
		}else{
			$sql_cmd = "INSERT INTO `nbn_alerts` SET `nbn_alert_title` = '".$alertSubject."',nbn_alert_desc='".$_REQUEST['nbn_description_polygon']."',`nbn_alert_type` = '".$color."'";
			mysql_query($sql_cmd);
			$alert_id=mysql_insert_id();

		}
    	foreach ($postcodes as $postcode) {
			$alertKey=$ewn_responst_array['AlertKey'];
			if($color!='Green'){
					$sql = "SELECT nbn_postcode from nbn_layers_info where  nbn_postcode='".$postcode."'";
					$rs=mysql_query($sql);
					$num=mysql_num_rows($rs);
					if($num>0){
						$sql_cmd = "UPDATE `nbn_layers_info` SET nbn_class='".$class."' , nbn_alertkey='".$alertKey."',`nbn_color` = '".$color."',`alert_id` = '".$alert_id."' WHERE nbn_postcode='".$postcode."'";
						mysql_query($sql_cmd);
					}else{
						$sql_cmd = "INSERT INTO `nbn_layers_info` SET `nbn_postcode` = '".$postcode."',nbn_alertkey='".$alertKey."',`nbn_class` = '".$class."',`nbn_color` = '".$color."',`alert_id` = '".$alert_id."'";
						mysql_query($sql_cmd);

					}
			}else{
				$sql_cmd = 'DELETE FROM nbn_layers_info WHERE nbn_postcode='.$postcode;
				mysql_query($sql_cmd);
			}
		
		}
    	$log_api_data = array(
						 "request"=> $alertData,
						 "error" =>  curl_error($ch),
						 "httpcode"=>$httpcode,
						 "response" => $response,
						 "status" => 'false' 
						);

		log_input('alert',$log_api_data);
		$output=array('postdata'=>$postcodesR,'alert_id'=>$alert_id,'alert_title'=>$alertSubject,'alert_color'=>$color,'status'=>'','alert_description'=>$_REQUEST['nbn_description_polygon']);
		echo json_encode($output,true);die;
	}
	
	
}

?>