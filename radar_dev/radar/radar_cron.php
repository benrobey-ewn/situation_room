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
		   //$png_image =imagecreatefromstring(file_get_contents($image));

            $dest = imagecreatetruecolor(512, 512);

            $black = imagecolorallocate($dest, 0, 0, 0);
            

            /* transparent */
            $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
            imagefill($dest, 0, 0, $transparent);
            imagealphablending($dest, true);
            /* transparent */
            
            imagecolortransparent($dest, $black);
            /* radar cropping start*/
			switch ($radar_code) {
			  
			  case 'IDR041':
				imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
				$white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 30, 0, 512, 120, $white);
				//imagefilledrectangle($dest, 0, 470, 212, 512, $white);
				break;
				
			  case 'IDR051':
				imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				imagefilledrectangle($dest, 84, 445, 512, 512, $white);
				# code...
				break;
				
			  case 'IDR071':
			    imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				imagefilledrectangle($dest, 280, 0, 512, 282, $white);
				imagefilledrectangle($dest, 285, 465, 512, 512, $white);
				break;
			
			
			  case 'IDR081':
			    imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
				# code...
				break;
			
			
			  case 'IDR141':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
				# code...
				break;
				
			  case 'IDR151':
				  imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 240, 365, 512, $white);
				# code...
				break;
			
			
			  case 'IDR161':
				 $white = imagecolorallocate($dest, 0, 0, 0);
				 //$white = imagecolorallocate($dest, 221, 160, 221);
                 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                 imagefilledrectangle($dest, 0, 10, 400, 512, $white);
                 imagefilledrectangle($dest, 190, 0, 512, 372, $white);
                 imagefilledrectangle($dest, 490, 372, 512, 512, $white);
				break;
				
			  case 'IDR171':
				   imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				imagefilledrectangle($dest, 0, 152, 210, 512, $white);
                imagefilledrectangle($dest, 320, 0, 512, 370, $white);
				# code...
				break;
				
			    //
			  case 'IDR181':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 0, 0, 45, 451, $white);
				# code...
				break;
				
			 case 'IDR221':
				  imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 8, 480, $white);
                imagefilledrectangle($dest, 0, 0, 48, 37, $white);
                imagefilledrectangle($dest, 170, 254, 512, 512, $white);
				# code...
				break;
				
			  case 'IDR241': // IDR241
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
				// $white = imagecolorallocate($dest, 221, 160, 221);
                $white = imagecolorallocate($dest, 0, 0, 0);
				imagefilledrectangle($dest, 0, 0, 103, 70, $white);
                imagefilledrectangle($dest, 0, 105, 55, 512, $white);
                imagefilledrectangle($dest, 230, 350, 512, 512, $white);
                //imagefilledrectangle($dest, 105, 0, 512, 260, $white);
				# code...
				break;
			
			  case 'IDR251':
			     imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                 $white = imagecolorallocate($dest, 0, 0, 0);
				 //imagefilledrectangle($dest, 0, 0, 214, 42, $white);
				 //$white = imagecolorallocate($dest, 221, 160, 221);
                 imagefilledrectangle($dest, 0, 62, 224, 512, $white);
                 imagefilledrectangle($dest, 120, 408, 512, 512, $white);
                 imagefilledrectangle($dest, 298, 0, 512, 332, $white);
				# code...
				break;
				
				case 'IDR271':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 190, 370, 512, 512, $white);
                imagefilledrectangle($dest, 420, 252, 512, 512, $white);
			    # code...
				break;
				
			   case 'IDR281':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 0, 0, 492, 290, $white);
                imagefilledrectangle($dest, 0, 300, 490, 512, $white);
			    # code...
				break;
				
				//
				case 'IDR331':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 150, 0, 512, 420, $white);
                imagefilledrectangle($dest, 0, 0, 150, 110, $white);
                imagefilledrectangle($dest, 312, 465, 512, 512, $white);
			    # code...
				break;
				
				case 'IDR361':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
				/*imagefilledrectangle($dest, 0, 0, 150, 390, $white);
                imagefilledrectangle($dest, 150, 0, 215, 260, $white);
                imagefilledrectangle($dest, 0, 0, 512, 260, $white);
                imagefilledrectangle($dest, 480, 288, 512, 512, $white);*/
				imagefilledrectangle($dest, 0, 0, 150, 390, $white);
                imagefilledrectangle($dest, 150, 0, 215, 230, $white);
                imagefilledrectangle($dest, 0, 0, 512, 260, $white);
                imagefilledrectangle($dest, 480, 288, 512, 512, $white);
			    # code...
				break;
				
			   case 'IDR411':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                //$white = imagecolorallocate($dest, 0, 0, 0);
				$white = imagecolorallocate($dest, 0, 0, 0);
				imagefilledrectangle($dest, 0, 0, 80, 308, $white);
				imagefilledrectangle($dest, 0, 242, 415, 512, $white);
                //imagefilledrectangle($dest, 0, 245, 418, 512, $white);
				# code...
				break;
				
				//
			  case 'IDR421':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
                //imagefilledrectangle($dest, 0, 75, 298, 512, $white);
                imagefilledrectangle($dest, 70, 0, 512, 310, $white);
				imagefilledrectangle($dest, 0, 65, 298, 310, $white);
				imagefilledrectangle($dest, 0, 350, 298, 512, $white);
				imagefilledrectangle($dest, 0, 310, 50, 350, $white);
				# code...
				break;
			
			    case 'IDR441':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 0, 195, 110, $white);
                imagefilledrectangle($dest, 410, 335, 512, 512, $white);
				# code...
				break;
				
				case 'IDR481':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 0, 82, 245, 512, $white);
                imagefilledrectangle($dest, 0, 0, 130, 82, $white);
				# code...
				break;
			
			  case 'IDR491':
			    imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
				imagefilledrectangle($dest, 68, 292, 512, 512, $white);
                imagefilledrectangle($dest, 0, 110, 285, 512, $white);
				imagefilledrectangle($dest, 305, 0, 512, 332, $white);
				# code...
				break;
			  
			  case 'IDR521':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
				imagefilledrectangle($dest, 0, 0, 280, 320, $white);
				# code...
				break;
				
			  case 'IDR551':
				   imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
                imagefilledrectangle($dest, 0, 330, 442, 512, $white);
                imagefilledrectangle($dest, 0, 135, 215, 325, $white);
			    # code...
				break;
				
			  case 'IDR671':
				 imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
               imagefilledrectangle($dest, 275, 0, 512, 512, $white);
                imagefilledrectangle($dest, 0, 0, 115, 185, $white);
                imagefilledrectangle($dest, 105, 0, 267, 152, $white);
			    # code...
				break;
				
			  case 'IDR751':
				  imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                 $white = imagecolorallocate($dest, 0, 0, 0);
				//$white = imagecolorallocate($dest, 221, 160, 221);
                imagefilledrectangle($dest, 0, 0, 134, 161, $white);
                imagefilledrectangle($dest, 134, 0, 520, 58, $white);
			    # code...
				break;
			  
			  case 'IDR771':
				  imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
                $white = imagecolorallocate($dest, 0, 0, 0);
				# code...
				break;
				
			 default:
				// imagecopyresampled($dest, $png_image, 0, 0, 0, 17, 512, 512, 512, 512);
			     imagecopyresampled($dest, $png_image, 0, 17, 0, 17, 512, 512, 512, 512);
				 $white = imagecolorallocate($dest, 0, 0, 0);
				 imagefilledrectangle($dest, 0, 498, 512, 512, $white);
				# code...
				break;
			}
			
			 /* radar cropping end*/

            
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

     //$selectSQL = "SELECT radar_id,radar_code FROM radar_codes where radar_code IN ('IDR331','IDR271','IDR141')";
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