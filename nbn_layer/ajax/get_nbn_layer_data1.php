<?php include '../includes/config.php';

$page = $_REQUEST['page'];

$cur_page = $page;
 
$per_page = 300; 

if($page == 1){
  $start = 1;
} else {
  $start = ($page-1) * $per_page;
}

/*$query_pag_num = "SELECT COUNT(*) AS count FROM nbn_layers";
$result_pag_num = mysql_query($query_pag_num);
$rowa = mysql_fetch_array($result_pag_num);
$count = $rowa['count'];
$no_of_paginations = ceil($count / $per_page);*/


$selectSQL = "SELECT * FROM nbn_layers ORDER BY nbn_id ASC LIMIT $start, $per_page";
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
                                                          "color"=>'#00FFFF',
                                                          "nbn_main_id"=>$nbn_main_id),
                                        "geometry"=>array("type"=>$nbn_geotype,
                                                          "coordinates"=>json_decode($nbn_coordinates,true)));
                    }
             } 
             //echo $page;die; 
           
               $output=$features;
            
echo json_encode($output,true);die;
?>