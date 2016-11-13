<?php include '../includes/config.php';
 $response=array();
 $SevereWeather=array(23,33,34,35);
 $topic_type=$_POST['topic_type']; 
 $topicIDs=  implode(',', $topic_type);
 $record_type=$_POST['record_type'];
 $warning_days=$_POST['warning_days'];
 // set the default timezone to use. Available since PHP 5.1
 $system_timezone = date_default_timezone_get();
 date_default_timezone_set('UTC');
 $newdate = date('Y-m-d H:i:s');
 
date_default_timezone_set($system_timezone);

$topicIDs=  explode(',', $topicIDs);
$AlltopicIDs= $topicIDs;
$topicIDs = array_diff($topicIDs, array('BWA', 'BW'));
$topicIDs=  implode(',', $topicIDs);
 
 
 if($record_type=='1') {
     if(in_array('all', $topic_type)) {
        $selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,AffectedBoundaries FROM Alerts where Expires >='".$newdate."' AND topic_id NOT IN (37,38,39,40) ORDER BY `Sent` DESC";   
     } else {
    $selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,AffectedBoundaries FROM Alerts where topic_id in (".$topicIDs.") AND  Expires >='".$newdate."' ORDER BY `Sent` DESC"; 
     }
    } else {
        $days_ago = date('Y-m-d H:i:s', strtotime('- '.$warning_days.' hours', strtotime($newdate)));
        if(in_array('all', $topic_type)) {
            $selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,AffectedBoundaries FROM Alerts where Expires >='".$days_ago."'  AND topic_id NOT IN (37,38,39,40)  order by `Sent` DESC"; 
        } else {
            $selectSQL = "SELECT id,AlertFullURL,topic_id,Subject,AffectedBoundaries FROM Alerts where topic_id in (".$topicIDs.") AND Expires >='".$days_ago."'   order by `Sent` DESC"; 
        }
 }
 
 //echo $selectSQL; die;
 $result = mysql_query($selectSQL);
 while($data=  mysql_fetch_array($result)) {
                if(in_array($data['topic_id'],$SevereWeather)) {
                    $data['color']='#FFC0CB';
                } else if ($data['topic_id']=='28') {
                        $data['color']='#FF0000';
		} else if ($data['topic_id']=='13') {
			$data['color']='#ff6700';
		} else if ($data['topic_id']=='14') {
			$data['color']='#0000FF';
		} else if ($data['topic_id']=='36') {
                    if (strpos($data['Subject'],'Bushfire Watch & Act') !== false) {
                            $data['color']='#C2AFE6';
                    } else {
                            $data['color']='#A52A2A';
                    }
                } else if ($data['topic_id']=='29') {
                        $data['color']='#008000';
		} else if ($data['topic_id']=='31') {
                        $data['color']='#79C2FF';
		} else {
			$data['color']='#FFFF00';
		}
                
            //$data['color']='#FF0000';
            $polygon=array();
            $selectpolygon="SELECT * FROM polygon_location where alert_id ='".$data['id']."'";
            $polgonresult = mysql_query($selectpolygon);
            if(mysql_num_rows($polgonresult) >0 ) {
                while($polygondata=  mysql_fetch_assoc($polgonresult)) {
                    $polygon[]=json_decode($polygondata['lat_long_pair']);
                }
            }   
        $data['corodinates']=$polygon;
       
        if(in_array('BWA',$AlltopicIDs) && !in_array('all',$AlltopicIDs) && count($AlltopicIDs)==2) {
              if(strpos($data['Subject'],'Bushfire Watch & Act') !== false)
               $response[]=$data;
        } else if(in_array('BW',$AlltopicIDs) && !in_array('all',$AlltopicIDs) && count($AlltopicIDs)==2) {
             if(strpos($data['Subject'],'Bushfire Watch & Act') == false)
             $response[]=$data;
        } else {
            $response[]=$data;
        }
      //$response[]=$data;
     //echo '<pre>'; print_r($response); die;
 }
 $output=array('query'=>$selectSQL,'status'=>'true','data'=>$response);
 echo json_encode($output);
?>