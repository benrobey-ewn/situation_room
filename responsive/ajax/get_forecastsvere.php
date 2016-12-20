<?php
 include '../includes/config.php';
 $response=array();
 $topic_type=$_POST['topic_type']; 
 $topicIDs=  implode(',', $topic_type);
 $record_type=$_POST['record_type'];
 // set the default timezone to use. Available since PHP 5.1

 /*get server timezone*/
$server_system_timezone = date_default_timezone_get(); 
date_default_timezone_set($server_system_timezone);  
 /*get server timezone*/

$current_time=date("H:i:s"); /*Time return in server time zone Australia/Melbourne current  */
 if($current_time>='09:00:00')
 {
        $newdate = date('Y-m-d');
        /*$newdateTime = date('Y-m-d 09:00:00');
        $previousdate=date('Y-m-d',strtotime('- 1 days', strtotime(date('Y-m-d'))));
        $previousdateconversion=strtotime("$previousdate 23:59:59");
        date_default_timezone_set('UTC');
        $convert_datetime_utc=date("Y-m-d H:i:s", $previousdateconversion);
        // $uprecords = "Update `Alerts` set `is_deleted`=1 where `is_deleted`=0 and (`sent`<='".$convert_datetime_utc."') and topic_id IN (37,38,39,40)";
      // die;
      //  mysql_query($uprecords);
        date_default_timezone_set($server_system_timezone);  */
  } else {
        $newdate=date('Y-m-d',strtotime('- 1 days', strtotime(date('Y-m-d'))));
  }

 
 $todaydate=strtotime("$newdate 00:00:00");
 $todaydateEnd=strtotime("$newdate 23:59:59");

 $tomorrow_set_date = date('Y-m-d', strtotime('+ 1 days', strtotime($newdate)));
 $tomorrow_date=strtotime("$tomorrow_set_date 00:00:00");
 $tomorrow_dateEnd=strtotime("$tomorrow_set_date 23:59:59");
 $tomorrow_dateEnd_time=strtotime("$tomorrow_set_date 09:00:00");


 $day_after_tomorrow_date = date('Y-m-d', strtotime('+ 2 days', strtotime($newdate)));
 $day_after_tomorrow_date=strtotime("$day_after_tomorrow_date 09:00:00");
 $day_after_tomorrow_date_time=strtotime("$day_after_tomorrow_date 09:00:00");

 
 /* echo $todaydate;
  echo "</br>";*/
  
 $system_timezone = date_default_timezone_get();
 date_default_timezone_set('UTC');
 $todaydate=date("Y-m-d H:i:s", $todaydate);
 $todaydateEnd=date("Y-m-d H:i:s", $todaydateEnd);
 $tomorrow_date=date("Y-m-d H:i:s", $tomorrow_date);
 $tomorrow_dateEnd=date("Y-m-d H:i:s", $tomorrow_dateEnd);
$tomorrow_dateEnd_time=date("Y-m-d H:i:s", $tomorrow_dateEnd_time);
$day_after_tomorrow_date_time=date("Y-m-d H:i:s", $day_after_tomorrow_date_time);

 $day_after_tomorrow_date=date("Y-m-d H:i:s", $day_after_tomorrow_date);
 date_default_timezone_set($system_timezone);
 
/* if($record_type=='1') {
        $selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,AffectedBoundaries,CreatedDate FROM Alerts where topic_id in (".$topicIDs.") AND  Sent >='".$todaydate."' AND Sent <='".$todaydateEnd."' AND Expires < '".$tomorrow_dateEnd_time."' AND is_deleted=0 ORDER BY `Sent` DESC";
    } else {
        
        $selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,AffectedBoundaries,CreatedDate FROM Alerts where topic_id in (".$topicIDs.") AND Sent >='".$todaydate."' AND Sent <='".$todaydateEnd."'  AND Expires >= '".$tomorrow_dateEnd_time."'  AND is_deleted=0 ORDER BY `Sent` DESC";
    }*/

     if($record_type=='1') {
        $selectSQL = "SELECT id,topic_id,AlertFullURL,CreatedDate FROM Alerts where topic_id in (".$topicIDs.") AND  Sent >='".$todaydate."' AND Sent <='".$todaydateEnd."' AND Expires < '".$tomorrow_dateEnd_time."' AND is_deleted=0 ORDER BY `Sent` DESC";
    } else {
        
        $selectSQL = "SELECT id,topic_id,AlertFullURL,CreatedDate FROM Alerts where topic_id in (".$topicIDs.") AND Sent >='".$todaydate."' AND Sent <='".$todaydateEnd."'  AND Expires >= '".$tomorrow_dateEnd_time."'  AND is_deleted=0 ORDER BY `Sent` DESC";
    }
    
    
            $result = mysql_query($selectSQL);
            while($data=  mysql_fetch_array($result)) {
                if ($data['topic_id']=='38') {
                        $data['color']='#FF00FF';
		} else if ($data['topic_id']=='37') {
			$data['color']='#00FFFF';
		} else if ($data['topic_id']=='39') {
			$data['color']='#7B68EE';
		} else if ($data['topic_id']=='40') {
                        $data['color']='#32CD32';
		} 
                
            $polygon=array();
            $selectpolygon="SELECT lat_long_pair FROM polygon_location where alert_id ='".$data['id']."'";
            $polgonresult = mysql_query($selectpolygon);
            if(mysql_num_rows($polgonresult) >0 ) {
                while($polygondata=  mysql_fetch_assoc($polgonresult)) {
                    $polygon[]=json_decode($polygondata['lat_long_pair']);
                }
            }   
        $data['corodinates']=$polygon;
       
       
      $response[]=$data;
        
      //$response[]=$data;
     //echo '<pre>'; print_r($response); die;
 }
 //$output=array('query'=>$selectSQL,'status'=>'true','data'=>$response);
 $output=array('status'=>'true','data'=>$response);
 echo json_encode($output);
?>