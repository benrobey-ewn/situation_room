<?php 
include '../includes/config.php'; 
// generate kml
extract($_POST);
//print_r($polygonArr);die;
if(isset($save_polygon) && $save_polygon=="save_polygon")
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
				
				if(isset($title) && $title!="")
				{
					$title = trim($title);
					$kmlContent .= '
					<name>'.$title.'</name>';
				}
				if(isset($description) && $description!="")
				{
					$description = trim($description);
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
								$i=1;
								//echo count($newPolygonArray);
								foreach($newPolygonArray as $latlong)
								{
									$latLongPair = explode(",",$latlong);
									$kmlContent .= $latLongPair[1].','.$latLongPair[0]."\n";
									if($i==count($newPolygonArray)){
										$postCodeBoundries .=$latLongPair[1].' '.$latLongPair[0];
									}else{
										$postCodeBoundries .=$latLongPair[1].' '.$latLongPair[0].',';
									}
									$i++;
								}
								$kmlContent.='
							</coordinates>
						</LinearRing>
					</outerBoundaryIs>
				</Polygon>
			</Placemark>
		</Document>
	</kml>';
	
	$filename = str_replace('/','-',$title).".kml";


	$current_user_id = $_SESSION['user_id'];
	if (!file_exists("../user_kmls/".$current_user_id.'/'.$filename))
	{
		if(isset($_SESSION['user_id']) && $_SESSION['user_id']!='') 
		{
			if (!file_exists("../user_kmls/".$current_user_id))
			{
	    		mkdir("../user_kmls/".$current_user_id, 0777, true);
			}
			$path = "../user_kmls/".$current_user_id.'/'.$filename;
			$call_path = "user_kmls/".$current_user_id.'/'.$filename;
			file_put_contents($path, trim($kmlContent));
			echo json_encode(array("status_output"=>'success',"filename"=>$filename,"path"=>$call_path,"layer_name"=>str_replace(' ','_',$call_path)));
		}	
	}
	else
	{
		echo json_encode(array("status_output"=>'error',"message"=>'Polygon name already exists.'));
	}
	exit;
}

?>