<?php
  
if($_POST['mystate']=='Sydney')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/nsw/sydney/forecast');
}
else if($_POST['mystate']=='Melbourne')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/vic/melbourne/forecast');
}
else if($_POST['mystate']=='Hobart')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/tas/hobart/forecast');
}
else if($_POST['mystate']=='Adelaide')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/sa/adelaide/forecast');
}
else if($_POST['mystate']=='Perth')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/wa/perth/forecast');
}
else if($_POST['mystate']=='Canberra')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/act/canberra/forecast');
}
else if($_POST['mystate']=='Darwin')
{
	$page_data = file_get_contents('http://www.bom.gov.au/nt/forecasts/darwin.shtml');
}
else if($_POST['mystate']=='Brisbane')
{
	$page_data = file_get_contents('http://www.bom.gov.au/places/qld/brisbane/forecast');
}
else if($_POST['mystate']=='Alice')
{
	$page_data = file_get_contents('http://www.bom.gov.au/nt/forecasts/alice-springs.shtml');
}
else if($_POST['mystate']=='Cairns')
{
	$page_data = file_get_contents('http://www.bom.gov.au/qld/forecasts/cairns.shtml');
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
