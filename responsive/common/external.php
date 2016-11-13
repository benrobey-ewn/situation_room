<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div align="left" style="overflow:hidden;">
<?php
include('simple_html_dom.php');
$theData = array();

  

$page_data = file_get_contents('http://www.bom.gov.au/places/nsw/sydney/forecast');


//echo $filtered_data;

$html = str_get_html($page_data);   

// get the table. Maybe there's just one, in which case just 'table' will do
$table = $html->find('<div class="day">');

// initialize empty array to store the data array from each row

$i=0;
// loop over rows
foreach($html->find('div.day') as $oneday)
{
	/*foreach($oneday->find('h2') as $heading)
	{
		echo $heading;
		echo "</br>";
	}

	foreach($oneday->find('div.forecast') as $forecast)
	{
		echo $forecast;
		echo "</br>";
	}*/
    echo $h1=$oneday->find('h2', 0)->plaintext;
    echo "</br>";
    echo $min=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.min', 0)->plaintext;
    echo "</br>";
    echo $max=$oneday->find('div.forecast', 0)->find('dl', 0)->find('dd.max', 0)->plaintext;
    echo "</br>";
}


echo '<pre>';
//print_r($theData);
?>
</div>
<br><br><br><br><br><br>
</body>
</html>
