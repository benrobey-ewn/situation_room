<?php 
include '../includes/config.php'; 
// generate kml
extract($_POST);
$postCodeBoundries='';
if(isset($save_polygon) && $save_polygon=="save_nbn_polygon")
{

	$kmlContent = '<?xml version="1.0" encoding="UTF-8"?>
	<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
		<Document>
			<name>Testing polygon earth.kml</name>
			<Style id="s_ylw-pushpin">
				<IconStyle>
					<scale>1.1</scale>
					<Icon>
						<href>assets/img/ylw-pushpin.png</href>
					</Icon>
					<hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
				</IconStyle>
				<LineStyle>
					<color>ff000000</color>
					<width>2</width>
				</LineStyle>
				<PolyStyle>
					<color>78000000</color>
				</PolyStyle>
			</Style>
			<StyleMap id="m_ylw-pushpin">
				<Pair>
					<key>normal</key>
					<styleUrl>#s_ylw-pushpin</styleUrl>
				</Pair>
				<Pair>
					<key>highlight</key>
					<styleUrl>#s_ylw-pushpin_hl</styleUrl>
				</Pair>
			</StyleMap>
			<Style id="s_ylw-pushpin_hl">
				<IconStyle>
					<scale>1.3</scale>
					<Icon>
						<href>assets/img/ylw-pushpin.png</href>
					</Icon>
					<hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
				</IconStyle>
				<LineStyle>
					<color>ff000000</color>
					<width>2</width>
				</LineStyle>
				<PolyStyle>
					<color>78000000</color>
				</PolyStyle>
			</Style>
			<Placemark>';
				
				if(isset($title_nbn_polygon) && $title_nbn_polygon!="")
				{
					$title = trim($title_nbn_polygon);
					$kmlContent .= '
					<name>'.$title.'</name>';
				}
				if(isset($nbn_description_polygon) && $nbn_description_polygon!="")
				{
					$description = trim($nbn_description_polygon);
					$kmlContent .= '
					<description>'.$description.'</description>';
				}
				
				$kmlContent .= '
				<styleUrl>#m_ylw-pushpin</styleUrl>
				<Polygon>
					<tessellate>1</tessellate>
					<outerBoundaryIs>
						<LinearRing>
							<coordinates>
								';
								$firstIndex = array($polygonArr[0]);
								$newPolygonArray = array_merge($polygonArr,$firstIndex);
								foreach($newPolygonArray as $latlong)
								{
									$latLongPair = explode(",",$latlong);
									$kmlContent .= $latLongPair[1].','.$latLongPair[0]."\n";
								}
								$kmlContent.='
							</coordinates>
						</LinearRing>
					</outerBoundaryIs>
				</Polygon>
			</Placemark>
		</Document>
	</kml>';
	
	$filename = $title.".kml";
	if(isset($_SESSION['user_id']) && $_SESSION['user_id']!='') 
	{
		$current_user_id = $_SESSION['user_id'];
		if (!file_exists("../user_kmls/".$current_user_id))
		{
    		mkdir("../user_kmls/".$current_user_id, 0777, true);
		}
		$path = "../user_kmls/".$current_user_id.'/'.$filename;
		$call_path = "user_kmls/".$current_user_id.'/'.$filename;
		file_put_contents($path, trim($kmlContent));

		//Ewn API Alert
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
			$extractPostcodes = explode(",",$postcodes);						 
			foreach ($extractPostcodes as $postcode) {
				$explodePostcode=explode("-", $postcode);
				$DeliveryLocations[]=array( 'DeliveryHtmlEmail' => true,
													      'DeliverySms' => true,
													      'DeliveryWebsites' => false,
													      "Severity"=> $Severity,
															'BoundaryTypeKey' => $BoundaryTypeKey,
															'BoundaryCode' =>"AU-".$explodePostcode[1]);

				$outPostcodes[]=array('postcode'=>$explodePostcode[1],'class'=>$class,'color'=>$color,'id'=>$explodePostcode[0]);	
			}
				
			$alertSubject=$title;
			$ewnPolygon='';
			$AlertGroupKey='2071';
			$alertData = array(
								"AlertKey"=>0,
								"AlertGroupKey" => $AlertGroupKey,
								"Subject" =>$alertSubject,
								"TextForWeb" =>$description,
								"TextForSMS" =>$description,
								"AlertType" => "GIS",
								"TopicKey" =>1,
								"DeliveryLocations" =>$DeliveryLocations
							); 
			
			//echo json_encode($alertData);die;
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
				if($ewn_responst_array['AlertKey']!=''){
					$alertKey=$ewn_responst_array['AlertKey'];
				}else{
					$alertKey=0;
				}
				$ewn_response['success']=$response;
				$ewn_responst_array=json_decode($response,true);
				$i=0;
				foreach ($ewn_responst_array['DeliveryLocations'] as $value) {
						$search = array('POINT (', ')');
						$replace = array('', '');
						$points=str_replace($search, $replace, $value['Centroid']);
						$points=explode(' ',$points);
						$outPostcodes[$i]['lat']=$points[0];
						$outPostcodes[$i]['lng']=$points[1];
					$i++;
				}	
				foreach ($extractPostcodes as $postcode) {
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
		//Ewn API Alert
		echo json_encode(array("filename"=>$filename,"path"=>$call_path,"layer_name"=>str_replace(' ','_',$call_path),'postcode'=>$outPostcodes));
	}	
	exit;
}

?>