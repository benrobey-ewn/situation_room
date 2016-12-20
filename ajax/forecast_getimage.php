<?php
  
  $post_mystate = trim(mysql_real_escape_string($_POST['mystate']));

if($post_mystate=='Sydney')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/nsw/sydney/forecast');
}
else if($post_mystate=='Melbourne')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/vic/melbourne/forecast');
}
else if($post_mystate=='Hobart')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/tas/hobart/forecast');
}
else if($post_mystate=='Adelaide')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/sa/adelaide/forecast');
}
else if($post_mystate=='Perth')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/wa/perth/forecast');
}
else if($post_mystate=='Canberra')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/act/canberra/forecast');
}
else if($post_mystate=='Darwin')
{
	$page_data = file_get_contents('http://www.bom.gov.au/nt/forecasts/darwin.shtml');
}
else if($post_mystate=='Brisbane')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/qld/brisbane/forecast');
}
else if($post_mystate=='Alice')
{
	$page_data = file_get_contents('http://www.bom.gov.au/nt/forecasts/alice-springs.shtml');
}
else if($post_mystate=='Cairns')
{
	$page_data = file_get_contents('http://www.bom.gov.au/qld/forecasts/cairns.shtml');
}
else if($post_mystate=='Learmonth')
{
	$page_data = file_get_contents('http://www.bom.gov.au/wa/forecasts/exmouth.shtml');
}

?>
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
foreach($html->find('div.day') as $oneday)
{
	if($myday==0)
	{
		$image_site_root = "http://www.bom.gov.au";
	    echo $image_site_root.$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.image', 0)->find('img', 0)->src;
	}
	$myday++;
}

?>
