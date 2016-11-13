<?php
    error_reporting(0);
    $url=$_SERVER['PHP_SELF'];
    header("Refresh: 120;url=$url");
    include '../includes/config.php';
    ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
    
    //put this at the top of the page
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;
    
    function save_image($image,$radar_id) {
         $file = basename($image);
         $selectSQL = "SELECT image_id FROM radar_images where image_name='".$file."'";
         $result = mysql_query($selectSQL);
         $count=mysql_num_rows($result);
         if($count==0)  {
            $time=substr($file,-8,-4);
            $date=substr($file,-16,-8);
           
            $date= substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2);
              
            /* image save into folder */     
            $png_image = ImageCreateFromPNG($image);
            $dest = imagecreatetruecolor(512, 512);
            $black = imagecolorallocate($dest, 0, 0, 0);
            
            /* trans */
            $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
            imagefill($dest, 0, 0, $transparent);
            imagealphablending($dest, true);
            /* trans */
            
            imagecolortransparent($dest, $black);
            imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
            imagepng($dest, '../radar_images/'.$file);
            imagedestroy($png_image);
            /* image save into folder */
           
           chmod('../radar_images/'.$file, 0777);
           $min=substr($time,0,2);
           $sec=substr($time,2,2);
           $time=$min.":".$sec.":"."00";
           $date_time=$date.' '.$time;
           
           $created_date=gmdate("Y-m-d H:i:s");
           $query="INSERT INTO radar_images(radar_id,image_name,radar_date,radar_time,radar_date_time,insert_date) "
                               . "VALUES ('".$radar_id."','".$file."','".$date."','".$time."','".$date_time."','".$created_date."')";
           $result =  mysql_query($query); 
           $record_id= mysql_insert_id();
         }
    }
    
    /* Crawler Script */
    $selectSQL = "SELECT radar_id,radar_code FROM radar_codes";
    $result = mysql_query($selectSQL);
    while($radar_code=mysql_fetch_array($result)) {
            $remoteData = file_get_contents("http://www.bom.gov.au/products/".$radar_code['radar_code'].".loop.shtml");
            $startAnchor = strpos($remoteData, 'theImageNames[0]');
            if(strpos($remoteData, "nImages = 6;"))
            $stopAnchor = strpos($remoteData, 'nImages = 6;');
            else
            $stopAnchor = strpos($remoteData, 'nImages = 4;');
            
            $urlsRaw = substr($remoteData, $startAnchor, ($stopAnchor-$startAnchor));
            //$urlsRaw = str_replace("/radar/","http://www.bom.gov.au/radar/", $urlsRaw);
            preg_match_all("(\"(.*)\")", $urlsRaw, $rawImages);
            for ($a = 0; $a < sizeof($rawImages[1]); $a++)
            {
             if (strpos($rawImages[1][$a], ".png"))
                {
                    save_image($rawImages[1][$a],$radar_code['radar_id']);
                    $b++;
                }
            }
    }
    //echo $b;
    /* Crawler Script */
    
    /* delete image older than 1 hour */
    $selectSQL = "SELECT radar_id,radar_code FROM radar_codes";
    $result = mysql_query($selectSQL);
    while($radar_code=mysql_fetch_array($result)) {
             $selectSQL = "SELECT image_name,image_id FROM radar_images where radar_id = '".$radar_code['radar_id']."'";
              $radar_result = mysql_query($selectSQL);
              $total = mysql_num_rows($radar_result);
              if($total>12) {
                 /*$del_query="DELETE FROM radar_images WHERE radar_id='".$radar_code['radar_id']."'
AND image_id IN (SELECT image_name
     FROM radar_images WHERE radar_id='".$radar_code['radar_id']."'
     ORDER BY radar_date_time DESC LIMIT 1)";
                mysql_query($del_query);*/
                $del_total=$total-12; 
                $selectSQL = "SELECT image_id,image_name FROM radar_images WHERE radar_id='".$radar_code['radar_id']."' ORDER BY radar_date_time ASC LIMIT ".$del_total;
                $del_result = mysql_query($selectSQL);
                while($del_image=mysql_fetch_array($del_result)) {
                    if (file_exists('../radar_images/'.$del_image['image_name'])) {
                        unlink('../radar_images/'.$del_image['image_name']);
                    } 
                    $delquery = "DELETE FROM radar_images where image_id = '".$del_image['image_id']."'";
                    mysql_query($delquery);
                }
               
                
              }
            
    }
   
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    $totaltime = ($endtime - $starttime);
    echo "This page was created in ".$totaltime." seconds";
?>