<?php
include '../includes/config.php'; 
include('../includes/simple_html_dom.php');
ini_set('max_execution_time', 50000000); //300 seconds = 5 minutes
$theData = array();

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

/* Get all states */

$sqlst = "select * from `forecast_layers`";
$rsst = mysql_query($sqlst);
$numst = mysql_num_rows($rsst);
if($numst!=0)
{
	while($fetchst = mysql_fetch_object($rsst))
	{
		$state_name = $fetchst->state_name;

		if($state_name=='Sydney')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/places/nsw/sydney/forecast');
			$page_title = "Sydney";
		}
		else if($state_name=='Melbourne')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/places/vic/melbourne/forecast');
			$page_title = "Melbourne";
		}
		else if($state_name=='Hobart')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/places/tas/hobart/forecast');
			$page_title = "Hobart";
		}
		else if($state_name=='Adelaide')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/places/sa/adelaide/forecast');
			$page_title = "Adelaide";
		}
		else if($state_name=='Perth')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/places/wa/perth/forecast');
			$page_title = "Perth";
		}
		else if($state_name=='Canberra')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/places/act/canberra/forecast');
			$page_title = "Canberra";
		}
		else if($state_name=='Darwin')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/nt/forecasts/darwin.shtml');
			$page_title = "Darwin";
		}
		else if($state_name=='Brisbane')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/places/qld/brisbane/forecast');
			$page_title = "Brisbane";
		}
		else if($state_name=='Alice Springs')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/nt/forecasts/alice-springs.shtml');
			$page_title = "Alice Springs";
		}
		else if($state_name=='Cairns')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/qld/forecasts/cairns.shtml');
			$page_title = "Cairns";
		}
		else if($state_name=='Learmonth')
		{
			$page_data = file_get_contents('http://www.bom.gov.au/wa/forecasts/exmouth.shtml');
			$page_title = "Learmonth";
		}

		$html = str_get_html($page_data);   
		// get the table. Maybe there's just one, in which case just 'table' will do
		$table = $html->find('<div class="day">');
		// initialize empty array to store the data array from each row
		$myday=0;
		$forecast_day = 1;


	    /* Delete old information */
	   // $deldata = "Delete from `forecast_layer_info` where `forecast_title`='".$page_title."'";
	   // mysql_query($deldata);
	    /* Delete old information */

		foreach($html->find('div.day') as $oneday)
		{
			/* Title info */
			$forecast_title = $page_title; 
			/* Title Info */

			if($myday==0)
			{
				$image_site_root = "http://www.bom.gov.au";
				$image_name = $oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src; // /images/symbols/large/partly-cloudy.png
				$image_name_new = substr($image_name, strrpos($image_name, '/') + 1); // partly-cloudy.png
			    $primary_image_icon =  $image_site_root.$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src;
			
			    /*Update primary image into database*/
			    /* Check image exists or not */
			    if (file_exists('../images/forecast/transparent/'.$image_name_new)) 
				{
				}
				else
				{
					define('DIRECTORY', '../images/forecast/transparent');
					//define('DIRECTORY2', '/home/aeerisco/public_html/situation_room/images/forecast/transparent');
					$content = file_get_contents($primary_image_icon);
					file_put_contents(DIRECTORY.'/'.$image_name_new, $content);
					//file_put_contents(DIRECTORY2.'/'.$image_name_new, $content);
				}

			    $sqlupdate = "Update `forecast_layers` set icon='".$primary_image_icon."' , `icon_name`='".$image_name_new."' where state_name = '".$state_name."'";
			    $rsupdate = mysql_query($sqlupdate);
			    /*Update primary image into database*/
			}


			/*Date info*/
			if($myday!=0)
			{
		    	$forecast_date = $h1=$oneday->find('h2', 0)->plaintext;
		    }
		    else
		    {
	    		$forecast_date = $h1=date("l",time())." ".date("d F",time());
		    }
		    /*Date info*/


		    /* Image info */
		    $image_site_root = "http://www.bom.gov.au";
			$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src;
			$full_image = explode("large/",$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src);
			$only_image_name = $full_image[1];
	
			//echo '/public_html/situation_room/images/forecast/transparent/'.$only_image_name;
			if (file_exists('../images/forecast/transparent/'.$only_image_name)) 
			{
				$forecast_image = 'images/forecast/transparent/'.$only_image_name;
			}
			else
			{
				$forecast_image = $image_site_root.$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src;
			}
		    /* Image Info */


		    /* Min and Max */
		    if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.min', 0)->plaintext))
		    {
			    $min=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.min', 0)->plaintext;
			    $max=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.max', 0)->plaintext;
			}
			else if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 1)->find('em.min',0)->plaintext))
		    {
			    $min=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 1)->find('em.min',0)->plaintext;
			    $max=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 2)->find('em.max',0)->plaintext;
			}
			else if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.max', 0)->plaintext))
		    {
			    $min="-"; /// not getting any info
			    $max=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.max', 0)->plaintext;
			}
			else
			{
				$min="-";
				$max="-";
			}
		    /* Min and Max */



		    /* Rain and Possible rainfall */
		    if($myday!=0)
			{
				if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.pop', 0)->plaintext))
			    {
				    $rain=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.pop', 0)->plaintext;
				    $possible=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.amt', 0)->plaintext;
				}
				else if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 3)->find('em.pop', 0)->plaintext))
			    {
				    $rain=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.rain', 0)->find('em.pop', 0)->plaintext;
				    $possible=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 4)->plaintext;
				}
				else
				{
					$rain="-";
					$possible="-";
				}
			}
			else
			{
				if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.rain', 0)->plaintext))
			    {
				    $rain=$oneday->find('div.forecast', 0)->find('dl', 0)->find('em.pop', 0)->plaintext;
				    $possible="-"; //// No data found for this
				}
				else
				{
					$rain="-";
					$possible="-";
				}
			}
		    /* Rain and Possible rainfall */



		    /* Firedanger info */
		    $fire_danger_word = $oneday->find('dl.alert', 0);
			if(!empty($fire_danger_word))
	   		{
	   			$danger_count=0;
	   			foreach($oneday->find('dl.alert', 0)->find('dd') as $dangers)
	   			{
	   				if($danger_count==0)
	   				{
	   					$forecast_danger = $dangers->plaintext."</br>";
	   				}
	   				else
	   				{
	   					$forecast_danger.= "</br>".$dangers->plaintext;
	   				}
	   				$danger_count++;
	   			}

		   	}
		   	else
		   	{
		   		$forecast_danger="-";
		   	}	
		    /* Firedanger info */


		    /* Insert new rows*/


		    $selectdayentry = "Select `forecast_info_id` from `forecast_layer_info` where `forecast_title`='".$forecast_title."' and `forecast_day`='".$forecast_day."'";
		    $rsdayentry = mysql_query($selectdayentry);
		    $numdayentry = mysql_num_rows($rsdayentry);
		    if($numdayentry==0)
		    {
		    	$sqlinsertion = "Insert Into `forecast_layer_info` (`forecast_title`,`forecast_date`,`forecast_image`,`min`,`max`,`chance_of_rain`,`possible_rainfall`,`fire_danger`,`created_date`,`forecast_day`)
		   		Values ('".$forecast_title."','".$forecast_date."','".$forecast_image."','".$min."','".$max."','".$rain."','".$possible."','".$forecast_danger."','".date('Y-m-d H:i:S')."','".$forecast_day."')";
		    	mysql_query($sqlinsertion);
		    }
		    else
		    {
		    	$sqlupdation = "Update `forecast_layer_info` set `forecast_title`='".$forecast_title."' , `forecast_date`='".$forecast_date."' , `forecast_image`='".$forecast_image."' , `min`='".$min."',`max`='".$max."',`chance_of_rain`='".$rain."',`possible_rainfall`='".$possible."',`fire_danger`='".$forecast_danger."' where `forecast_title`='".$forecast_title."' and `forecast_day`='".$forecast_day."'";
		    	mysql_query($sqlupdation);
		    }
		    /* Insert new rows*/
			$myday++;
			$forecast_day++;
		}
	}
}

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
file_put_contents("forecast_cron.txt",$totaltime,FILE_APPEND);

?>
