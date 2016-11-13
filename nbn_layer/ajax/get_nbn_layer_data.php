  <?php include '../includes/config.php';
  


$selectSQL = "SELECT * FROM nbn_layers ORDER BY nbn_id ASC";
            $result = mysql_query($selectSQL);
            //$features=array(); 
            while ($row = mysql_fetch_array($result)) {
                    $nbn_main_id=$row['nbn_main_id'];
                    $nbn_btype=$row['nbn_btype'];
                    $nbn_name=$row['nbn_name'];
                    $nbn_geotype=$row['nbn_geotype'];
                    $nbn_coordinates=$row['nbn_coordinates'];
                    $nbn_fillcolor=$row['nbn_fillcolor'];
                    if(json_decode($nbn_coordinates,true)!=NULL ){
                      $features[]=array("type"=>"Feature",
                                        "id"=>$nbn_main_id,
                                        "properties"=>array("btype"=>$nbn_btype,
                                                          "name"=>$nbn_name,
                                                          "fillColor"=> $nbn_fillcolor,
                                                          "color"=>'red',
                                                          "nbn_main_id"=>$nbn_main_id),
                                        "geometry"=>array("type"=>$nbn_geotype,
                                                          "coordinates"=>json_decode($nbn_coordinates,true)));
                    }
             }  
           $output=array("type"=>"FeatureCollection",
           'features'=>$features);

echo json_encode($output,true);
die;
?>