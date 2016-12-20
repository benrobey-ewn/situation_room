<?php  include '../includes/config.php';
       include('../includes/simple_html_dom.php');
       ini_set('max_execution_time', 300); //300 seconds = 5 minutes
//put this at the top of the page
   $append_content=date('m-d-Y h:i:s')."\n";
   file_put_contents("observation_cron.txt",$append_content,FILE_APPEND);
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $starttime = $mtime;

error_reporting(E_ALL);

/**
*Get Lattitude Longitude
**/
function getLatLong($address){
	 $address = urlencode($address); // Wrigley Field
	 $url ='http://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false';
	 $geocode=file_get_contents($url);
	 $results = json_decode($geocode, true);
	 if($results['status']=='OK'){
		$arr['lat'] = $results['results'][0]['geometry']['location']['lat'];
		$arr['long'] = $results['results'][0]['geometry']['location']['lng'];
	}
	else{
		$arr['lat'] ='';
		$arr['long']='';
		}
	return $arr;
}

function getLatlng($url){
    // Find all links
                     
        $lathookOne='<table class="stationdetails" summary="">';
        $lathookTwo='</table>';
        $page_data = file_get_contents($url);
   
        $start_grab = strpos($page_data, $lathookOne);
        $stop_grab = strpos($page_data, $lathookTwo);
   
        $filtered_data = substr($page_data, $start_grab, ($stop_grab-$start_grab));
        //print_r($filtered_data);
   
        $html = str_get_html($filtered_data);
        $arr=array();
        $arr['lat'] ='';
        $arr['long']='';
        
        // loop over rows
        foreach($html->find('table') as $onetable){
                foreach($onetable->find('tr') as $row) {
                    foreach($row->find('td') as $cell) {
                            if (strpos((string)$cell->innertext,'Lat') !== false){
                                    $latlongArray=explode(':', $cell->innertext);
                                    $arr['lat'] =  strip_tags($latlongArray[1]);
                                    //print_r($latlongArray);
                                    //echo  $cell->innertext=preg_replace('#<[^>]+>#', ' ', $cell->innertext);
                            } elseif(strpos((string)$cell->innertext,'Lon') !== false) {
                                        $latlongArray=explode(':', $cell->innertext);
                                        $arr['long'] =strip_tags($latlongArray[1]);
                            }

                    }

                }
        } //print_r($arr); die;
                       return $arr;
                     
}

function search($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, search($subarray, $key, $value));
        }
    }

    return $results;
}

   
   $all_cities_ids=array();
   $fetch_cities=array();
   $all_states_cities=array();
   
   $query = "SELECT state_id,state_name FROM states";
   $stateresult = mysql_query($query);
   
   if(mysql_num_rows($stateresult)>0) {
        while($row=mysql_fetch_assoc($stateresult)) {
            $all_cities=array();
            $state_id = $row['state_id'];
            $query = "SELECT city_id,city_name,lat,lng,is_capital FROM cities where state_id=".$state_id;
            $result = mysql_query($query);
                if(mysql_num_rows($result)>0) {
                    
                    while($row=mysql_fetch_assoc($result)) {
                       $all_cities[]= $row;
                       $all_cities_ids[]=$row['city_id'];
                    }
                
        }
        $all_states_cities[$state_id]= $all_cities;
    }
}
//echo '<pre>';
//print_r($all_cities_ids);die;
$theData = array();
//$locations=array('NSW','QLD','VIC','TAS','SA','WA','NT');
$capitals=array('Canberra','Sydney','Melbourne','Brisbane','Perth','Adelaide','Hobart','DarwinDarwin Airport','Darwin Harbour');
$query = "SELECT state_id,state_name,state_full_name FROM states";
$stateresult = mysql_query($query);
if(mysql_num_rows($stateresult)>0) {
    while($row=mysql_fetch_assoc($stateresult)) {
      
   $location = $row['state_name'];
   $state_full_name = $row['state_full_name'];
   $state_id = $row['state_id'];
   
   if ($location == 'NSW')
    {
    $dataURL = 'http://www.bom.gov.au/nsw/observations/nswall.shtml';
    $hookOne = '<h2 id="NR">NORTHERN RIVERS</h2>';
    $hookTwo = '<!-- End: Content -->';
    $capital_fetch=array('http://www.bom.gov.au/nsw/observations/sydney.shtml','http://www.bom.gov.au/act/observations/canberra.shtml');
    $capitals=array('Canberra');
    $capitalhookOne='<h2 id="SYDNEY">SYDNEY AREA</h2>';
    $capitalhookTwo='<!-- End: Content -->';
    
    }
  else if ($location == 'QLD')
    {
    $dataURL = 'http://www.bom.gov.au/qld/observations/qldall.shtml';
    $hookOne = '<h2 id="PENINSULA</h2>';
    $hookTwo = '<h2 id="MOB">MOBILE</h2>'; 
    $capital_fetch=array('http://www.bom.gov.au/qld/observations/brisbane.shtml');
    $capitals=array();
    $capitalhookOne='<h2 id="BRISBANE">BRISBANE AREA</h2>';
    $capitalhookTwo='<!-- End: Content -->';
    }
  else if ($location == 'VIC')
    {
    $dataURL = 'http://www.bom.gov.au/vic/observations/vicall.shtml';
    $hookOne = '<h2 id="MAL">MALLEE</h2>';
    $hookTwo = '<!-- End: Content -->';
    $capital_fetch=array('http://www.bom.gov.au/vic/observations/melbourne.shtml');
    $capitals=array();
    $capitalhookOne='<h2 id="MELBOURNE">MELBOURNE AREA</h2>';
    $capitalhookTwo='<!-- End: Content -->';
    }
else if ($location == 'TAS')
    {
    $dataURL = 'http://www.bom.gov.au/tas/observations/tasall.shtml';
    $hookOne = '<h2 id="KI">KING ISLAND</h2>';
    $hookTwo = '<!-- End: Content -->';
    $capital_fetch=array('http://www.bom.gov.au/tas/observations/hobart.shtml');
    $capitals=array();
    $capitalhookOne='<h2 id="HOBART">HOBART AREA</h2>';
    $capitalhookTwo='<!-- End: Content -->';
    }
else if ($location == 'SA')
    {
    $dataURL = 'http://www.bom.gov.au/sa/observations/saall.shtml';
    $hookOne = '<h2 id="AM">ADELAIDE AND MOUNT LOFTY RANGES</h2>';
    $hookTwo = '<h2 id="MOB">MOBILE</h2>'; 
    $capital_fetch=array('http://www.bom.gov.au/sa/observations/adelaide.shtml');
    $capitals=array();
    $capitalhookOne='<h2 id="ADELAIDE">ADELAIDE AREA</h2>';
    $capitalhookTwo='<!-- End: Content -->';
    }
else if ($location == 'WA')
    {
    $dataURL = 'http://www.bom.gov.au/wa/observations/waall.shtml';
    $hookOne = '<h2 id="KIM">KIMBERLEY</h2>';
    $hookTwo = '<!-- End: Content -->'; 
    $capital_fetch=array('http://www.bom.gov.au/wa/observations/perth.shtml');
    $capitals=array();
    $capitalhookOne='<h2 id="PERTH">PERTH AREA</h2>';
    $capitalhookTwo='<!-- End: Content -->';
    }
else if ($location == 'NT')
    {
    $dataURL = 'http://www.bom.gov.au/nt/observations/ntall.shtml';
    $hookOne = '<h2 id="DD">DARWIN-DALY</h2>';
    $hookTwo = '<!-- End: Content -->'; 
    $capital_fetch=array('http://www.bom.gov.au/nt/observations/darwin.shtml');
    $capitals=array();
    $capitalhookOne='<h2 id="DARWIN">DARWIN AREA</h2>';
    $capitalhookTwo='<!-- End: Content -->';
    }
else
  {
  die("No location specified"); 
  }
  
  
  /* fetch all capital data */
    foreach($capital_fetch as $capital_data) {
    $page_data = file_get_contents($capital_data);
    $start_grab = strpos($page_data, $capitalhookOne);
    $stop_grab = strpos($page_data, $capitalhookTwo);
    $filtered_data = substr($page_data, $start_grab, ($stop_grab-$start_grab));
    $html = str_get_html($filtered_data);  
    foreach($html->find('table') as $onetable){
    foreach($onetable->find('tr.rowleftcolumn') as $row) {
      // print_r($row); die;
       foreach($row->find('th') as $cell) {
                     $city = strip_tags(preg_replace("/(^)?(<br\s*\/?>\s*)+$/", " ", $cell->innertext));
                     $capitals[]=$city;
       }
       
    }
        }
    }
    //print_r($capitals);
    //die;
   /* fetch all capital data */
  

$page_data = file_get_contents($dataURL);

$start_grab = strpos($page_data, $hookOne);
$stop_grab = strpos($page_data, $hookTwo);

$filtered_data = substr($page_data, $start_grab, ($stop_grab-$start_grab));

$filtered_data = str_replace('<a href="#">Return to top of page.</a>', "", $filtered_data);
$filtered_data = str_replace('<a href="/products/', '<a href="http://clients.ewn.com.au/common/bom/observationsHistory.php?page=products/', $filtered_data);

//echo $filtered_data;

$html = str_get_html($filtered_data);   

// get the table. Maybe there's just one, in which case just 'table' will do
$table = $html->find('<table>');

// initialize empty array to store the data array from each row

$i=0;
// loop over rows
foreach($html->find('table') as $onetable){
    foreach($onetable->find('tr.rowleftcolumn') as $row) {
        //if($i=='0'|| $i=='1') {
        //} else {
    
                $rowData = array();
                foreach($row->find('th') as $cell) {
                     $city = strip_tags(preg_replace("/(^)?(<br\s*\/?>\s*)+$/", " ", $cell->innertext));
                     $is_capital=false;
                      
                     if (in_array($city, $capitals)) {
                       $rowData['capital']='yes';
                       $is_capital=true;
                     } else {
                       $rowData['capital']='no';
                       $is_capital=false;
                     }
                     
                     //echo '<pre>';
                     $findcity=search($all_states_cities[$state_id], 'city_name', $city);
                     //print_r($findcity);
                     
                     if(!empty($findcity)) {
                        $rowData['city_id']=$findcity[0]['city_id'];
                     } else {
                         $city_address=$city.', '.$state_full_name;
                         $latlng=getLatLong($city_address);
                         if(!empty($latlng['lat']) || !empty($latlng['lat'])) {
                         } else {
                            foreach($cell->find('a') as $element){
                            $latlng=getLatlng($element->href);
                            //print_r($latlng);  die;  
                            }
                         }
                         $query="INSERT INTO cities(state_id,city_name,is_capital,lat,lng) "
                            . "VALUES ('".$state_id."','".$city."','".$is_capital."','".$latlng['lat']."','".$latlng['long']."')";
                            $result = mysql_query($query); 
                            $rowData['city_id']=  mysql_insert_id();
                     }
                     $fetch_cities[]=$rowData['city_id'];
                    
                }
                foreach($row->find('td') as $cell) {
                    //$cell->innertext=preg_replace('#<[^>]+>#', ' ', $cell->innertext);
                    $rowData[] = str_replace("<br />", " ", $cell->innertext);
                    /*$rowData[] = preg_replace("/(^)?(<br\s*\/?>\s*)+$/", " ", $cell->innertext);*/
                }
            $theData[] = $rowData;
        //}
    //$i++;
  }
}

foreach ($theData as $data) {
        $observation_query = "SELECT observation_id FROM observation where city_id=".$data['city_id'];
        $observation_result = mysql_query($observation_query);
        if(mysql_num_rows($observation_result)>0) {
            $observation_data=  mysql_fetch_array($observation_result);
            echo 'updated observation for observation id: ' . $observation_data['observation_id'];
            echo "\n\n";
                 $updatequery="UPDATE observation SET date_time_cst='".$data[0]."',temp='".$data[1]."',app_tmp='".$data[2]."',dew_ponit='".$data[3]."',rel_hum='".$data[4]."',delta_t='".$data[5]."',wind_dir='".$data[6]."',wind_speed_km_h='".$data[7]."',wind_guest_km_h='".$data[8]."',wind_speed_kts='".$data[9]."',wind_guest_kts='".$data[10]."',press_hpa='".$data[11]."',rain_since_9am_mm='".$data[12]."',low_Tmp='".$data[13]."',high_tmp='".$data[14]."',higest_wind_guest_dir='".$data[15]."',higest_wind_guest_km_h_time='".$data[16]."',higest_wind_guest_kts_time='".$data[17]."' where observation_id='".$observation_data['observation_id']."'";                      $result = mysql_query($updatequery); 
        } else {
                        $insertquery="INSERT INTO observation(city_id,date_time_cst,temp,app_tmp,dew_ponit,rel_hum,delta_t,wind_dir,wind_speed_km_h,wind_guest_km_h,wind_speed_kts,wind_guest_kts,press_hpa,rain_since_9am_mm,low_Tmp,high_tmp,higest_wind_guest_dir,higest_wind_guest_km_h_time,higest_wind_guest_kts_time) "
                . "VALUES ('".$data['city_id']."','".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."','".$data[10]."','".$data[11]."','".$data[12]."','".$data[13]."','".$data[14]."','".$data[15]."','".$data[16]."','".$data[17]."')"; 
                        $result = mysql_query($insertquery); 
                        $observation_id=mysql_insert_id();
                echo 'inserted observation';
        }
}

 }
}

// remove observation data of removed cities
if(!empty($all_cities_ids)) {
    $remove_cities = array_diff($all_cities_ids,$fetch_cities);
    if(!empty($remove_cities)) {
        foreach ($remove_cities as $remove_city) {
            //echo $remove_city;
            $query="DELETE FROM observation where city_id=".$remove_city;
            $result = mysql_query($query);
            echo 'deleted observation';
        }
    }
    
}
// remove observation data of removed cities
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   //echo "This page was created in ".$totaltime." seconds"; 
?>

