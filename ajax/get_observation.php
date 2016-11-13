<?php
include '../includes/config.php';
$response = array();
$record_type = trim(mysql_real_escape_string($_POST['record_type']));
if ($record_type == '1') {
	$selectSQL = "SELECT observation.*, cities.group_capital, cities.is_capital, cities.city_name, cities.lat, cities.lng, states.timezone FROM observation INNER JOIN cities ON observation.city_id = cities.city_id INNER JOIN states ON cities.state_id = states.state_id where cities.group_capital=1 AND cities.is_capital = 1";
}
else if ($record_type == '2') {
	$selectSQL = "SELECT observation.*, cities.group_capital, cities.is_capital, cities.city_name, cities.lat, cities.lng, states.timezone FROM observation INNER JOIN cities ON observation.city_id = cities.city_id INNER JOIN states ON cities.state_id = states.state_id where cities.is_capital = 1";
}
else {
	$selectSQL = "SELECT observation.*, cities.group_capital, cities.is_capital, cities.city_name, cities.lat, cities.lng, states.timezone FROM observation INNER JOIN cities ON observation.city_id = cities.city_id INNER JOIN states ON cities.state_id = states.state_id";
}

$result = mysql_query($selectSQL);
while ($data =  mysql_fetch_array($result)) {
	$response[] = $data;
}
$output = array('status' => 'true','data' => $response);
echo json_encode($output);
?>