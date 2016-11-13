<?php include '../includes/config.php';
$response=array();
//$SevereWeather=array(23,33,34,35);
$SevereWeather=array(23,33,34);
$topic_type=$_POST['topic_type']; 
$topicIDs=  implode(',', $topic_type);
$record_type=trim(mysql_real_escape_string($_POST['record_type']));
$warning_days=trim(mysql_real_escape_string($_POST['warning_days']));
 // set the default timezone to use. Available since PHP 5.1
$system_timezone = date_default_timezone_get();
date_default_timezone_set('UTC');
$newdate = date('Y-m-d H:i:s');

date_default_timezone_set($system_timezone);

$topicIDs=  explode(',', $topicIDs);
$AlltopicIDs= $topicIDs;

//print_r($AlltopicIDs);

if(in_array('all', $AlltopicIDs))
{
    $detectByName = "Bushfire";
}
else
{
    if(in_array('BWA', $AlltopicIDs) && in_array('BW', $AlltopicIDs))
    {
        $detectByName = "Bushfire";
    }
    else
    {

        if(in_array('BWA', $AlltopicIDs))
        {
            $detectByName = "Bushfire Watch";
        }
        if(in_array('BW', $AlltopicIDs))
        {
            $detectByName = "Bushfire Advice";
        }
    }
}



$topicIDs = array_diff($topicIDs, array('BWA', 'BW'));
$topicIDs=  implode(',', $topicIDs);



/*Check bushfire selected or not*/
if(in_array('36', $AlltopicIDs))
{
    $getAlertWithNewKey = "OR (topic_id=1 AND Subject like '%$detectByName%')";
}
else
{
    $getAlertWithNewKey = '';
}

//echo $getAlertWithNewKey;
//die;

/*Check bushfire selected or not*/

 

     if($record_type=='1') {
         if(in_array('all', $topic_type)) {
            $selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,CreatedDate FROM Alerts where Expires >='".$newdate."' AND topic_id NOT IN (37,38,39,40) AND `is_deleted`=0 ORDER BY `Sent` DESC";   
         } else {
            $selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,CreatedDate FROM Alerts where topic_id in (".$topicIDs.") AND  Expires >='".$newdate."' AND `is_deleted`=0 ORDER BY `Sent` DESC"; 
         }

      } else {
            $days_ago = date('Y-m-d H:i:s', strtotime('- '.$warning_days.' hours', strtotime($newdate)));
            if(in_array('all', $topic_type)) {
                $selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,CreatedDate FROM Alerts where Expires >='".$days_ago."'  AND topic_id NOT IN (37,38,39,40)  AND `is_deleted`=0 ORDER BY `Sent` DESC"; 
            } else {
                $selectSQL = "SELECT id,AlertFullURL,topic_id,Subject,CreatedDate FROM Alerts where topic_id in (".$topicIDs.") AND Expires >='".$days_ago."'   AND `is_deleted`=0 ORDER BY `Sent` DESC"; 
            }
     }


 
 
$result = mysql_query($selectSQL);
while($data=  mysql_fetch_array($result))
{
    //$data['CreatedDate']=date('d-m-Y h:i:s a',strtotime($data['CreatedDate'])).' UTC';
    $data['CreatedDate']=date('Y-m-d H:i:s',strtotime($data['CreatedDate'])).'Z';
    if(in_array($data['topic_id'],$SevereWeather)) {
        $data['color']='#FFC0CB';
    } else if ($data['topic_id']=='28' || $data['topic_id']=='35') {
        $data['color']='#FF0000';
    } else if ($data['topic_id']=='13') {
        $data['color']='#ff6700';
     } else if ($data['topic_id']=='14') {
         $data['color']='#0000FF';
     } else if (($data['topic_id']=='36' || $data['topic_id']=='1') && (strpos($data['Subject'],'Bushfire Watch & Act') !== false) || (strpos($data['Subject'],'Bushfire Watch and Act') !== false) || (strpos($data['Subject'],'Bushfire Watch And Act') !== false) || (strpos($data['Subject'],'Bushfire Emergency Warning') !== false) || (strpos($data['Subject'],'Bushfire Advice') !== false) || (strpos($data['Subject'],'Bushfire Watch &amp; Act') !== false))
    {
        if ((strpos($data['Subject'],'Bushfire Watch & Act') !== false) || (strpos($data['Subject'],'Bushfire Watch And Act') !== false) || (strpos($data['Subject'],'Bushfire Watch and Act') !== false) || (strpos($data['Subject'],'Bushfire Watch &amp; Act') !== false) || (strpos($data['Subject'],'Bushfire Emergency Warning') !== false) && ($detectByName=='Bushfire' || $detectByName=='Bushfire Watch')) 
        {
            $data['color']='#800080';//C2AFE6
        }
        else if(strpos($data['Subject'],'Bushfire Advice') !== false && ($detectByName=="Bushfire" || $detectByName=="Bushfire Advice"))
        {
            $data['color']='#A52A2A';
        }
    }
    else if ($data['topic_id']=='29')
    {
        $data['color']='#008000';
    }
    else if ($data['topic_id']=='31')
    {
        $data['color']='#79C2FF';
    } else
    {
        $data['color']='#FFFF00';
    }
//echo $detectByName; die;
//print_r($data);
//die;

            //$data['color']='#FF0000';
                 $polygon=array();
                 $selectpolygon="SELECT `lat_long_pair` FROM polygon_location WHERE alert_id ='".$data['id']."' AND `is_deleted`=0 ";
                 $polgonresult = mysql_query($selectpolygon);
                 if(mysql_num_rows($polgonresult) >0 ) {
                    while($polygondata=  mysql_fetch_assoc($polgonresult)) {
                        $polygon[]=json_decode($polygondata['lat_long_pair']);
                    }
                }   
                $data['corodinates']=$polygon;

                //if(in_array('BWA',$AlltopicIDs) && !in_array('all',$AlltopicIDs) && count($AlltopicIDs)==2)
                if(!in_array('all',$AlltopicIDs))
                {  

                    if(in_array('BWA',$AlltopicIDs) && in_array('BW',$AlltopicIDs))
                    {  
                        if(in_array('BWA',$AlltopicIDs))
                        {
                            if ((strpos($data['Subject'],'Bushfire Watch & Act') !== false) || (strpos($data['Subject'],'Bushfire Watch and Act') !== false) || (strpos($data['Subject'],'Bushfire Emergency Warning') !== false) && ($detectByName=='Bushfire' || $detectByName=='Bushfire Watch'))
                            {
                                $response[]=$data;
                            }
                            if((strpos($data['Subject'],'Bushfire Advice')) === false && (strpos($data['Subject'],'Bushfire Watch & Act') === false) && (strpos($data['Subject'],'Bushfire Watch and Act') === false) && (strpos($data['Subject'],'Bushfire Emergency Warning') === false))
                            {
                                $response[]=$data;
                            }
                        }
                        //else if(in_array('BW',$AlltopicIDs) && !in_array('all',$AlltopicIDs) && count($AlltopicIDs)==2)
                        if(in_array('BW',$AlltopicIDs))
                        {
                            if(strpos($data['Subject'],'Bushfire Advice') !== false && ($detectByName=="Bushfire" || $detectByName=="Bushfire Advice"))
                            {    
                                $response[]=$data;
                            }
                        }
                    }
                    else if(in_array('BWA',$AlltopicIDs))
                    {  
                        if(in_array('BWA',$AlltopicIDs))
                        {
                            if ((strpos($data['Subject'],'Bushfire Watch & Act') !== false) || (strpos($data['Subject'],'Bushfire Watch and Act') !== false) || (strpos($data['Subject'],'Bushfire Emergency Warning') !== false) && ($detectByName=='Bushfire' || $detectByName=='Bushfire Watch'))
                            {
                                $response[]=$data;
                            }
                            if((strpos($data['Subject'],'Bushfire Advice')) === false && (strpos($data['Subject'],'Bushfire Watch & Act') === false) && (strpos($data['Subject'],'Bushfire Watch and Act') === false) && (strpos($data['Subject'],'Bushfire Emergency Warning') === false))
                            {
                                $response[]=$data;
                            }
                        }
                    }
                    else if(in_array('BW',$AlltopicIDs))
                    {  
                        //else if(in_array('BW',$AlltopicIDs) && !in_array('all',$AlltopicIDs) && count($AlltopicIDs)==2)
                        if(in_array('BW',$AlltopicIDs))
                        {
                            if(strpos($data['Subject'],'Bushfire Advice') !== false && ($detectByName=="Bushfire" || $detectByName=="Bushfire Advice"))
                            {    
                                $response[]=$data;
                            }
                            if((strpos($data['Subject'],'Bushfire Advice')) === false && (strpos($data['Subject'],'Bushfire Watch & Act') === false) && (strpos($data['Subject'],'Bushfire Watch and Act') === false) && (strpos($data['Subject'],'Bushfire Emergency Warning') === false))
                            {
                                $response[]=$data;
                            }
                        }
                    }
                    else
                    {
                      
                        if ((strpos($data['Subject'],'Bushfire Watch & Act') === false) && (strpos($data['Subject'],'Bushfire Watch and Act') === false) && (strpos($data['Subject'],'Bushfire Emergency Warning') === false) && (strpos($data['Subject'],'Bushfire') === false) && (strpos($data['Subject'],'Bushfire Watch') === false) && (strpos($data['Subject'],'Bushfire Advice') === false) ) {

                            $response[]=$data;
                        
                        } 
                       
                    }
                }
                else
                {
                    $response[]=$data;        
                }
      //$response[]=$data;
     
    }
   // echo '<pre>'; print_r($response); die;
  // $output=array('query'=>$selectSQL,'status'=>'true','data'=>$response);
    $output=array('status'=>'true','data'=>$response);
    echo json_encode($output);
    ?>