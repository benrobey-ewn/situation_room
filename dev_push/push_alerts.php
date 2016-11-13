<?php
include 'config.php';

header('Access-Control-Allow-Origin: *');
$my_raw_data=file_get_contents('php://input');

$data_string=file_get_contents('php://input');

$filename = 'data_'.time();
file_put_contents ('records/'.$filename.'.txt' , $my_raw_data);


set_magic_quotes_runtime(false);
//header('Content-Type: application/xml');
 /*if (!isset($_POST['xml_data']) || $_POST['xml_data'] == '') {
  echo "xml_data data is missing";
  die;
}
die("outside if");*/
  //$xml_get = $_POST['xml_data'];


  $xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $my_raw_data);
  $xml = stripslashes($xml);


  $xml = simplexml_load_string($xml);

  $alertKey = "";
  $subject = "";
  $sent = "";
  $expires = "";
  $alertFullURL = "";
  $affectedBoundaries = "";

 

  $alertContent = $xml->AlertContent;
  

  //$alertKey = mysql_real_escape_string(trim($alertContent->AlertKey));
  //$alertSubject = mysql_real_escape_string(trim($alertContent->Subject));
  //$alertSent = mysql_real_escape_string(trim($alertContent->Sent));
  //$alertExpires = mysql_real_escape_string(trim($alertContent->Expires));
 // $alertAlertFullURL = mysql_real_escape_string(trim($alertContent->AlertFullURL));


  $alertkey = mysql_real_escape_string(trim($alertContent->AlertKey));
  $AlertGroupKey = mysql_real_escape_string(trim($alertContent->AlertGroupKey));
  $subject = mysql_real_escape_string(trim($alertContent->Subject));
  $TextForSMS = mysql_real_escape_string(trim($alertContent->TextForSMS));       
  $CreatedDate = mysql_real_escape_string(trim($alertContent->CreatedDate));
  $CreatedBy_UserKey = mysql_real_escape_string(trim($alertContent->CreatedBy_UserKey));
  $Expires = mysql_real_escape_string(trim($alertContent->Expires));
  $TopicKey = mysql_real_escape_string(trim($alertContent->TopicKey));
  $IsDraft = mysql_real_escape_string(trim($alertContent->IsDraft));
  $sent = mysql_real_escape_string(trim($alertContent->Sent));
  $IsBadMessage =  mysql_real_escape_string(trim($alertContent->IsBadMessage));
  $IsDenied = mysql_real_escape_string(trim($alertContent->IsDenied));
  $IsApproved = mysql_real_escape_string(trim($alertContent->IsApproved));
  $AlertURL = mysql_real_escape_string(trim($alertContent->AlertURL));
  $AlertGUID = mysql_real_escape_string(trim($alertContent->AlertGUID));
  $SendToAllInGroup = mysql_real_escape_string(trim($alertContent->SendToAllInGroup));
  $AlertType = mysql_real_escape_string(trim($alertContent->AlertType));
  $IsExpired = mysql_real_escape_string(trim($alertContent->IsExpired));
  $DoNotSendToExisting = mysql_real_escape_string(trim($alertContent->DoNotSendToExisting));
  $HasExpired = mysql_real_escape_string(trim($alertContent->HasExpired));
  $AlertFullURL = mysql_real_escape_string(trim($alertContent->AlertFullURL));
  $AlertM2URL = mysql_real_escape_string(trim($alertContent->AlertM2URL));
  $AlertMURL = mysql_real_escape_string(trim($alertContent->AlertMURL));
  $ExternalID = mysql_real_escape_string(trim($alertContent->ExternalID));
  

        
  $selecttopicssql = "SELECT topic_id FROM topics WHERE topic_key = '". $TopicKey ."'";
  $topic_result = mysql_query($selecttopicssql);
  $topic_id=1;
  if(mysql_num_rows($topic_result))
  {
      $row = mysql_fetch_assoc($topic_result);
      $topic_id=$row['topic_id'];
  }
  
  $alertAffectedBoundaries = $xml->AffectedBoundaries;
  $alertDeliveryLocations = $xml->AlertContent->DeliveryLocations;



  

  

  $boundaryType = $alertAffectedBoundaries->BoundaryType;
  $deliverylocation = $alertDeliveryLocations->AlertDeliveryLocationJoin;

  
  $boundaryTypeCount = count($boundaryType);
  $deliverylocationCount = count($deliverylocation);

  $boundires=array();
  foreach ($boundaryType as $value)
  {
    $boundry=array();
    foreach($value->attributes() as $a => $b)
    {
        $boundry[$a]=(string)$b;
    }    
    $count = count($value->Boundary);
    
    foreach($value->Boundary as $a => $b)
    {
       $corboundry = array();
       foreach($b->attributes() as $a => $b)
       {
         $corboundry[$a] = (string)$b;
       }
       $boundry['Boundary'][]=$corboundry;
    }
    
    $sent_date = new DateTime($sent);
    $sent=$sent_date->format('Y-m-d H:i:s'); # read format from date() function
    
    $expire_date = new DateTime($Expires);
    $Expires=$expire_date->format('Y-m-d H:i:s'); # read format from date() function
   
    $created_date = new DateTime($CreatedDate);
    $CreatedDate=$created_date->format('Y-m-d H:i:s'); # read format from date() function
  
    $boundires[]=$boundry;
  }



  /*Create location array*/
  $mylocations=array();
  $mynewloc = array();
  foreach ($deliverylocation as $keyl=> $loc)
  {
    $mynewloc[] = $loc;      
  }
  /*Create location array*/


 $final=json_encode($boundires);
 $finallocation = json_encode($mynewloc);

// insert database code

  if ($alertkey != "") {

    $selectSQL = "SELECT * FROM Alerts WHERE AlertKey = '". $alertkey ."'";
    $result = mysql_query($selectSQL);
    $fetch_result = mysql_fetch_object($result);

    if (!$result) {
        die(mysql_error());
    }

    $rowCount = mysql_num_rows($result);


    if ($rowCount == 0)
    {
        $insertSQL = "INSERT INTO Alerts(AlertKey,AlertGroupKey,Subject,TextForSMS,CreatedDate,CreatedBy_UserKey,Expires,IsDraft,Sent,IsBadMessage,IsDenied,IsApproved,AlertURL,AlertGUID,SendToAllInGroup,AlertType,IsExpired,DoNotSendToExisting,HasExpired,AlertFullURL,AlertM2URL,AlertMURL,ExternalID,AffectedBoundaries,topic_id,DeliveryLocations)values('".$alertkey."','".$AlertGroupKey."','".$subject."','".$TextForSMS."','".$CreatedDate."','".$CreatedBy_UserKey."','".$Expires."','".$IsDraft."','".$sent."','".$IsBadMessage."','".$IsDenied."','".$IsApproved."','".$AlertURL."','".$AlertGUID."','".$SendToAllInGroup."','".$AlertType."','".$IsExpired."','".$DoNotSendToExisting."','".$HasExpired."','".$AlertFullURL."','".$AlertM2URL."','".$AlertMURL."','".$ExternalID."','".mysql_real_escape_string($final)."','".$topic_id."','".mysql_real_escape_string($finallocation)."')";
        $result = mysql_query($insertSQL);
        $last_insert_id  =mysql_insert_id();

        foreach($mynewloc as $mykeyl=>$val1)
        {
            $mypolygonfullval = $val1->Polygon;
            $mypolychange1 = str_replace("POLYGON ((","",$mypolygonfullval); //// remove POLYGON ((
            $mypolychange2 = str_replace("))","",$mypolychange1); //// remove ))
            $mypolychange3 = explode(",",$mypolychange2); /// explode w.r.t ,
            $mylatlongpair = array();
            foreach($mypolychange3 as $key3=>$val3)
            {
              //echo trim($val3);
              $mypolychange4 = explode(" ",trim($val3)); //// explode w.r.t space imp 0 is lon, 1 is lat
              $mylatitude = $mypolychange4[1];
              $mylongitude = $mypolychange4[0];
              $mylatlongpair[] = $mylatitude.",".$mylongitude;
            }

            $mylatlongpairdata = json_encode($mylatlongpair);

            /*insert into polygon location table*/

            $insertpoly = "INSERT INTO `polygon_location` (`alert_id`,`lat_long_pair`,`created_date`) values ('".$last_insert_id."','".$mylatlongpairdata."','".date('Y-m-d H:i:s')."')";
            mysql_query($insertpoly);

            /*insert into polygon location table*/
        }

        if (!$result)
        {
        die(mysql_error());
        }
    }
    else
    {
      $updateSQL = "UPDATE Alerts set AlertGroupKey = '".$AlertGroupKey."',  Subject = '".$subject."',TextForSMS = '".$TextForSMS."',CreatedDate = '".$CreatedDate."',CreatedBy_UserKey = '".$CreatedBy_UserKey."',Expires = '".$Expires."',IsDraft = '".$IsDraft."',Sent = '".$sent."',IsBadMessage = '".$IsBadMessage."',IsDenied = '".$IsDenied."',IsApproved = '".$IsApproved."',AlertURL = '".$AlertURL."',AlertGUID = '".$AlertGUID."',SendToAllInGroup = '".$SendToAllInGroup."',AlertType = '".$AlertType."',IsExpired ='".$IsExpired."',DoNotSendToExisting = '".$DoNotSendToExisting."',HasExpired = '".$HasExpired."',AlertFullURL = '".$AlertFullURL."',AlertM2URL = '".$AlertM2URL."',AlertMURL = '".$AlertMURL."',ExternalID = '".$ExternalID."',AffectedBoundaries = '" . mysql_real_escape_string($final) . "',topic_id='".$topic_id."',DeliveryLocations='".mysql_real_escape_string($finallocation)."'  WHERE AlertKey = '" . $alertkey . "'";
      $result = mysql_query($updateSQL);


      $deletealert = "DELETE from `polygon_location` where `alert_id`='".$fetch_result->id."'";
      mysql_query($deletealert);
      
      foreach($mynewloc as $mykeyl=>$val1)
      {
          $mypolygonfullval = $val1->Polygon;
          $mypolychange1 = str_replace("POLYGON ((","",$mypolygonfullval); //// remove POLYGON ((
          $mypolychange2 = str_replace("))","",$mypolychange1); //// remove ))
          $mypolychange3 = explode(",",$mypolychange2); /// explode w.r.t ,
          $mylatlongpair = array();
          foreach($mypolychange3 as $key3=>$val3)
          {
            //echo trim($val3);
            $mypolychange4 = explode(" ",trim($val3)); //// explode w.r.t space imp 0 is lon, 1 is lat
            $mylatitude = $mypolychange4[1];
            $mylongitude = $mypolychange4[0];
            $mylatlongpair[] = $mylatitude.",".$mylongitude;
          }

          $mylatlongpairdata = json_encode($mylatlongpair);
          /*insert into polygon location table*/

          $insertpoly = "INSERT INTO `polygon_location` (`alert_id`,`lat_long_pair`,`created_date`) values ('".$fetch_result->id."','".$mylatlongpairdata."','".date('Y-m-d H:i:s')."')";
          mysql_query($insertpoly);

          /*insert into polygon location table*/
      }
    



    }

    if ($result)
    {
      echo "success";
    }
    else {
      echo "failed";
    }
  }
?>