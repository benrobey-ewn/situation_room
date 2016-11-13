<?php 
include 'inc/config.php';
// generate kml
extract($_POST);

if(isset($save_polygon) && $save_polygon=="save_polygon"){
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
				
				if(isset($title) && $title!=""){
					$title = trim($title);
					$kmlContent .= '
					<name>'.$title.'</name>';
				}
				if(isset($description) && $description!=""){
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
								foreach($polygonArr as $latlong){
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
	$path = 'generated_kmls/'.$filename;
	file_put_contents('../'.$path, trim($kmlContent));
	echo json_encode(array("filename"=>$filename,"path"=>$path));
	exit;
}

?>