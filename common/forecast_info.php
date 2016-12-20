<?php
$post_forecast = trim(mysql_real_escape_string($_POST['forecast']));
  
if($post_forecast=='Sydney')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/nsw/sydney/forecast');
	$page_title = "Sydney";
}
else if($post_forecast=='Melbourne')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/vic/melbourne/forecast');
	$page_title = "Melbourne";
}
else if($post_forecast=='Hobart')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/tas/hobart/forecast');
	$page_title = "Hobart";
}
else if($post_forecast=='Adelaide')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/sa/adelaide/forecast');
	$page_title = "Adelaide";
}
else if($post_forecast=='Perth')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/wa/perth/forecast');
	$page_title = "Perth";
}
else if($post_forecast=='Canberra')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/act/canberra/forecast');
	$page_title = "Canberra";
}
else if($post_forecast=='Darwin')
{
	$page_data = file_get_contents('http://www.bom.gov.au/nt/forecasts/darwin.shtml');
	$page_title = "Darwin";
}
else if($post_forecast=='Brisbane')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/qld/brisbane/forecast');
	$page_title = "Brisbane";
}
else if($post_forecast=='Alice')
{
	$page_data = file_get_contents('http://www.bom.gov.au/nt/forecasts/alice-springs.shtml');
	$page_title = "Alice Springs";
}
else if($post_forecast=='Cairns')
{
	$page_data = file_get_contents('http://www.bom.gov.au/qld/forecasts/cairns.shtml');
	$page_title = "Cairns";
}
else if($post_forecast=='Learmonth')
{
	$page_data = file_get_contents('http://www.bom.gov.au/wa/forecasts/exmouth.shtml');
	$page_title = "Learmonth";
}

?>
<style type="text/css">
table, tbody
{
	border-spacing:0;
}
.forecasts_table {
border: solid #ccc 1px;
-moz-border-radius: 6px;
-webkit-border-radius: 6px;
border-radius: 6px;
-webkit-box-shadow: 0 1px 1px #ccc;
-moz-box-shadow: 0 1px 1px #ccc;
box-shadow: 0 1px 1px #ccc;
}
.forecasts_table tr:hover {
background: #fbf8e9;
-o-transition: all 0.1s ease-in-out;
-webkit-transition: all 0.1s ease-in-out;
-moz-transition: all 0.1s ease-in-out;
-ms-transition: all 0.1s ease-in-out;
transition: all 0.1s ease-in-out;
}
.forecasts_table td, .forecasts_table th {
border-left: 1px solid #ccc;
border-top: 1px solid #ccc;
padding: 10px;
text-align: left;

}
.forecasts_table th {
background-color: #dce9f9;
background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));
background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);
background-image: -moz-linear-gradient(top, #ebf3fc, #dce9f9);
background-image: -ms-linear-gradient(top, #ebf3fc, #dce9f9);
background-image: -o-linear-gradient(top, #ebf3fc, #dce9f9);
background-image: linear-gradient(top, #ebf3fc, #dce9f9);
-webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;
-moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;
box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;
border-top: none;
text-shadow: 0 1px 0 rgba(255,255,255,.5);
font-family: "trebuchet MS","Lucida sans",Arial;
font-size:12px;
}
.forecasts_table td:first-child, .bordered th:first-child {
border-left: none;
}
.forecasts_table th:first-child {
-moz-border-radius: 6px 0 0 0;
-webkit-border-radius: 6px 0 0 0;
border-radius: 6px 0 0 0;
border-left:0;
}
.forecasts_table th:last-child {
-moz-border-radius: 0 6px 0 0;
-webkit-border-radius: 0 6px 0 0;
border-radius: 0 6px 0 0;
}
.forecasts_table th:only-child{
-moz-border-radius: 6px 6px 0 0;
-webkit-border-radius: 6px 6px 0 0;
border-radius: 6px 6px 0 0;
}
.forecasts_table tr:last-child td:first-child {
-moz-border-radius: 0 0 0 6px;
-webkit-border-radius: 0 0 0 6px;
border-radius: 0 0 0 6px;
}
.forecasts_table tr:last-child td:last-child {
-moz-border-radius: 0 0 6px 0;
-webkit-border-radius: 0 0 6px 0;
border-radius: 0 0 6px 0;
} 
.forecasts_table td, .forecasts_table th {
    border-left: 1px solid #ccc;
    border-top: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}
.forecasts_table th
{
	border-top:none;
}
.forecasts_table tr
{
	background-color: #fff;
}
.forecasts_table td
{
	padding: 2px 7px;
}
.bolder
{
	font-weight: bold;
}
.max
{
	color:#CC0000;
}
.min
{
	color:#2d66cc;
}
.rainchance
{
	color:#060;
}
.nowrap
{
	white-space:nowrap;
}
</style>
<div align="left" style="overflow:hidden;width:640px;">
<div style="padding:5px 2px;">
	Forecast for <?php echo $page_title; ?>
    <!-- <span style="float:right">X</span> -->
</div>
<?php
//error_reporting(0);
include('../includes/simple_html_dom.php');
$theData = array();



//echo $filtered_data;

$html = str_get_html($page_data);   

// get the table. Maybe there's just one, in which case just 'table' will do
$table = $html->find('<div class="day">');

// initialize empty array to store the data array from each row

$myday=0;
// loop over rows
echo "<table class=forecasts_table cellpadding=10 width=100%>";
echo "<thead>";
echo "<tr>";
	echo "<th colspan=2>";
	echo "Date";
	echo "</th>";
	echo "<th>";
	echo "Min";
	echo "</th>";
	echo "<th>";
	echo "Max";
	echo "</th>";
	echo "<th class=nowrap>";
	echo "Chance of rain";
	echo "</th>";
	echo "<th class=nowrap>";
	echo "Possible rainfall";
	echo "</th>";
	echo "<th>";
	echo "Fire Danger";
	echo "</th>";
echo "</tr>";
echo "</thead>";
foreach($html->find('div.day') as $oneday)
{
	//if($myday!=0)
	{
		//print_r($oneday);
		//die;
		echo "<tr>";
		
		echo "<td class=nowrap>";
		if($myday!=0)
		{
	    	echo $h1=$oneday->find('h2', 0)->plaintext;
	    }
	    else
	    {
    		echo $h1=date("l",time())." ".date("d F",time());
    		//echo $h1=$oneday->find('h2', 0)->plaintext;
	    }
	    echo "</td>";
		
		/*echo "<td style=border-left:0>";
			$image_site_root = "http://www.bom.gov.au";
	    echo '<img src='.$image_site_root.$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src.'>';
	    echo "</td>";*/

	    echo "<td style=border-left:0>";
			$image_site_root = "http://www.bom.gov.au";
			$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src;
			$full_image = explode("large/",$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src);
			$only_image_name = $full_image[1];
	
			//echo '/public_html/situation_room/images/forecast/transparent/'.$only_image_name;
			if (file_exists('../images/forecast/transparent/'.$only_image_name)) 
			{
				$image_path_root = 'images/forecast/transparent/'.$only_image_name;
			}
			else
			{
				$image_path_root = $image_site_root.$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src;
			}

			//echo $image_path_root;
			echo "</br>";

	    echo '<img src='.$image_path_root.'>';
	    echo "</td>";


		/*echo "<td valign=top align=left>";
		echo $mois=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.summary', 0)->plaintext;
	    echo "</td>";*/

   		if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.min', 0)->plaintext))
	    {
			echo "<td class=min nowrap>";
		    echo $min=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.min', 0)->plaintext;
		    echo "</td>";

			echo "<td class=max nowrap>";
		    echo $max=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.max', 0)->plaintext;
		    echo "</td>";
		}
		else if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 1)->find('em.min',0)->plaintext))
	    {
			echo "<td class=min nowrap>";
		    echo $min=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 1)->find('em.min',0)->plaintext;
		    echo "</td>";

			echo "<td class=max nowrap>";
		    echo $max=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 2)->find('em.max',0)->plaintext;
		    echo "</td>";
		}
		else if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.max', 0)->plaintext))
	    {
			echo "<td class=min nowrap>";
		    echo $min="-"; /// not getting any info
		    echo "</td>";

			echo "<td class=max nowrap>";
		    echo $max=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.max', 0)->plaintext;
		    echo "</td>";
		}
		else
		{
			echo "<td>";
	   		echo "-";
		    echo "</td>";

			echo "<td>";
	   		echo "-";
		    echo "</td>";
		}
		if($myday!=0)
		{
			if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.pop', 0)->plaintext))
		    {
				echo "<td>";
			    echo $rain=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.pop', 0)->plaintext;
			    echo "</td>";

				echo "<td class=rainchance>";
			    echo $possible=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.amt', 0)->plaintext;
			    echo "</td>";
			}
			else if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 3)->find('em.pop', 0)->plaintext))
		    {
				echo "<td>";
			    echo $rain=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.rain', 0)->find('em.pop', 0)->plaintext;
			    echo "</td>";

				echo "<td>";
			    echo $possible=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd', 4)->plaintext;
			    echo "</td>";
			}
			else
			{
				echo "<td>";
		   		echo "-";
			    echo "</td>";

				echo "<td>";
		   		echo "-";
			    echo "</td>";
			}
		}
		else
		{
			if(isset($oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.rain', 0)->plaintext))
		    {
				echo "<td>";
			    echo $rain=$oneday->find('div.forecast', 0)->find('dl', 0)->find('em.pop', 0)->plaintext;
			    echo "</td>";

				echo "<td class=rainchance>";
			    echo $possible="-"; //// No data found for this
			    echo "</td>";
			}
			else
			{
				echo "<td>";
			    echo "-";
			    echo "</td>";

				echo "<td class=rainchance>";
			    echo "-";
			    echo "</td>";
			}

		}



   		$fire_danger_word = $oneday->find('dl.alert', 0);
		echo "<td align=left>";
		if(!empty($fire_danger_word))
   		{
   			foreach($oneday->find('dl.alert', 0)->find('dd') as $dangers)
   			{
   				echo $dangers->plaintext;
   				echo "</br>";
   			}

	   	}
	   	else
	   	{
	   		echo "-";

	   	}		 
	   echo "</td>";
	echo "</tr>";
	}
  $myday++;
}
echo "</table>";

?>
</div>