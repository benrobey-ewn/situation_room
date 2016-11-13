<?php

    error_reporting(0);
    $url=$_SERVER['PHP_SELF'];
    //header("Refresh: 120;url=$url");

    include '../includes/config.php';
    ini_set('max_execution_time', 3600); //300 seconds = 5 minutes

    
    //put this at the top of the page

    $mtime = microtime();

    $mtime = explode(" ",$mtime);

    $mtime = $mtime[1] + $mtime[0];

    $starttime = $mtime;

    

    function save_image($image,$radar_id,$radar_code) {

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
            
            if($radar_code =='IDR491')
		imagecopyresampled($dest, $png_image, 0, 17, 0, 17, 512, 100, 512, 100);
            else if($radar_code=='IDR041') {
                imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //$white = imagecolorallocate($dest, 221, 160, 221);
                //imagefilledrectangle($dest, 0, 0, 510, 335, $white);
                 imagefilledrectangle($dest, 0, 0, 505, 335, $white);
                //$white = imagecolorallocate($dest, 160, 32, 240);
                //imagefilledrectangle($dest, 0, 335, 305, 512, $white);
                 imagefilledrectangle($dest, 0, 335, 308, 512, $white);
            } else if($radar_code=='IDR281') {
                imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //$white = imagecolorallocate($dest, 221, 160, 221);
                //imagefilledrectangle($dest, 0, 0, 490, 300, $white);
                imagefilledrectangle($dest, 0, 0, 498, 290, $white);
                //$white = imagecolorallocate($dest, 160, 32, 240);
                //imagefilledrectangle($dest, 0, 300, 260, 512, $white);
                imagefilledrectangle($dest, 0, 300, 257, 512, $white);
                //$white = imagecolorallocate($dest, 160, 32, 240);
                //imagefilledrectangle($dest, 490, 372, 512, 512, $white);
            } /*else if($radar_code=='IDR701') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 300, 86, $white);
            } */
            else if($radar_code=='IDR161') {
                $white = imagecolorallocate($dest, 0, 0, 0);
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 0, 10, 412, 512, $white);
                //$white = imagecolorallocate($dest, 160, 32, 240);
                imagefilledrectangle($dest, 190, 0, 512, 372, $white);
                //$white = imagecolorallocate($dest, 160, 32, 240);
                imagefilledrectangle($dest, 490, 372, 512, 512, $white);
            } else if($radar_code=='IDR481') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 0, 82, 250, 512, $white);
                //$white = imagecolorallocate($dest, 160, 32, 240);
                imagefilledrectangle($dest, 0, 0, 130, 82, $white);
            } else if($radar_code=='IDR331') {
                imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 123, 104, 238);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 150, 0, 512, 455, $white);
                
                // $white = imagecolorallocate($dest, 153, 50, 204);
                //$white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 150, 110, $white);
                
                 //$white = imagecolorallocate($dest, 147, 112, 219);
                //$white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 312, 455, 512, 512, $white);
            /* } else if($radar_code=='IDR421') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //imagefilledrectangle($dest, 0, 50, 282, 510, $white);
                imagefilledrectangle($dest, 0, 68, 298, 510, $white);
            } else if($radar_code=='IDR771') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 156, 464, 515, $white);
            } */
            } else if($radar_code=='IDR071') {
                imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //$white = imagecolorallocate($dest, 221, 160, 221);
                //imagefilledrectangle($dest, 0, 0, 510, 335, $white);
                 imagefilledrectangle($dest, 260, 0, 512, 282, $white);
            } else if($radar_code=='IDR421') {
               imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
               $white = imagecolorallocate($dest, 0, 0, 0);
                //$white = imagecolorallocate($dest, 123, 104, 238);
                //$white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 68, 298, 510, $white);
                //$white = imagecolorallocate($dest, 49, 79, 79);
                imagefilledrectangle($dest, 42, 0, 512, 300, $white);
            } else if($radar_code=='IDR771') {
                imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //$white = imagecolorallocate($dest, 135, 206, 250);
                //$white = imagecolorallocate($dest, 0, 0, 0);
                //imagefilledrectangle($dest, 0, 230, 248, 512, $white);
            } else if($radar_code=='IDR181') {
                imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
               // $white = imagecolorallocate($dest, 0, 100, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 45, 451, $white);
            } else if($radar_code=='IDR751') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 0, 100, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 134, 161, $white);
                imagefilledrectangle($dest, 134, 0, 520, 58, $white);
            } else if($radar_code=='IDR411') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 0, 100, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 80, 308, $white);
                imagefilledrectangle($dest, 0, 265, 480, 512, $white);
            } else if($radar_code=='IDR221') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 0, 100, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 8, 480, $white);
                imagefilledrectangle($dest, 0, 0, 48, 37, $white);
                //imagefilledrectangle($dest, 47, 0, 550, 230, $white);
                imagefilledrectangle($dest, 170, 254, 512, 512, $white);
                /* bottom */
                //imagefilledrectangle($dest, 0, 492, 512, 512, $white);
            } else if($radar_code=='IDR081') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 255, 0, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //imagefilledrectangle($dest, 0, 0, 334, 245, $white);
            } else if($radar_code=='IDR141') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 255, 0, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 190, 180, 520, 520, $white);
            } else if($radar_code=='IDR551') {
                 //imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$red = imagecolorallocate($dest, 255, 0, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 330, 442, 550, $white);
                //$pink = imagecolorallocate($dest, 255, 105, 180);
                imagefilledrectangle($dest, 0, 135, 215, 325, $white);
            } else if($radar_code=='IDR051') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 0, 100, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //imagefilledrectangle($dest, 80, 417, 512, 512, $white);
                //imagefilledrectangle($dest, 84, 418, 512, 512, $white);
                imagefilledrectangle($dest, 84, 421, 512, 512, $white);
            } else if($radar_code=='IDR151') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 0, 100, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 235, 360, 512, $white);
            } else if($radar_code=='IDR671') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                /* **/
                //$white = imagecolorallocate($dest, 124, 252, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //imagefilledrectangle($dest, 260, 0, 512, 512, $white);
                 imagefilledrectangle($dest, 260, 0, 512, 173, $white);
                 
                 imagefilledrectangle($dest, 260, 210, 512, 512, $white);
                
                // $white = imagecolorallocate($dest, 173, 255, 47);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //imagefilledrectangle($dest, 0, 0, 115, 185, $white);
                imagefilledrectangle($dest, 0, 0, 115, 185, $white);
                
                // $white = imagecolorallocate($dest, 255, 255, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //imagefilledrectangle($dest, 105, 0, 265, 214, $white);
                imagefilledrectangle($dest, 105, 0, 265, 173, $white);
                
                /* */
                //$white = imagecolorallocate($dest, 85, 107, 47);
                $white = imagecolorallocate($dest, 0, 0, 0);
                //imagefilledrectangle($dest, 0, 485, 512, 530, $white);
                imagefilledrectangle($dest, 0, 485, 512, 530, $white);
            } else if($radar_code=='IDR271') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                
                /* **/
                //$white = imagecolorallocate($dest, 124, 252, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 160, 358, 512, 512, $white);
                
                 /* **/
                //$white = imagecolorallocate($dest, 124, 252, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 490, 220, 512, 512, $white);
                
            } else if($radar_code=='IDR171') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                
                /* **/
                //$white = imagecolorallocate($dest, 124, 252, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 150, 220, 512, $white);
                
                 /* **/
                //$white = imagecolorallocate($dest, 240, 230, 140);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 312, 0, 512, 370, $white);
                
            } else if($radar_code=='IDR441') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                
                /* **/
                //$white = imagecolorallocate($dest, 124, 252, 0);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 195, 110, $white);
                
                 /* **/
                //$white = imagecolorallocate($dest, 255, 255, 0);
                //$white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 410, 335, 512, 512, $white);
                
            } else if($radar_code=='IDR251') {
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                
                /* **/
                //$white = imagecolorallocate($dest, 178, 34, 34);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 214, 42, $white);
                
                 /* **/
                //$white = imagecolorallocate($dest, 244, 164, 96);
                //$white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 60, 224, 512, $white);
                
                //$white = imagecolorallocate($dest, 255, 20, 147);
                //$white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 120, 408, 512, 512, $white);
                
                //$white = imagecolorallocate($dest, 176, 48, 96);
                //$white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 298, 0, 512, 332, $white);
                
            } else
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

                    save_image($rawImages[1][$a],$radar_code['radar_id'],$radar_code['radar_code']);

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

              if($total>15) {

                 /*$del_query="DELETE FROM radar_images WHERE radar_id='".$radar_code['radar_id']."'

AND image_id IN (SELECT image_name

     FROM radar_images WHERE radar_id='".$radar_code['radar_id']."'

     ORDER BY radar_date_time DESC LIMIT 1)";

                mysql_query($del_query);*/

                $del_total=$total-15; 

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

    //echo "This page was created in ".$totaltime." seconds";

?>