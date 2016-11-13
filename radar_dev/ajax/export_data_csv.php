<?php
header('Access-Control-Allow-Origin: *');
$json_data=file_get_contents('php://input');

$jsondata = $_REQUEST['data'];
echo $json = json_decode($jsondata, true);
die;



echo $all_data = json_decode($_POST['data']);
print_r(json_decode($_REQUEST['data']));
die;
$polygonmainarray = array();
$polygonresponsearray = array();
$csv='';
foreach ($all_data as $polygon => $polygonv)
{
    
    $csv .= "Warning,{$polygonv['polygon_subject']} \n";//Column headers
    $csv .= "Layer,{$polygonv['polygon_layer_name']} \n";//Column headers
    $data_array = $polygonv['placemarker_name'];
    foreach ($data_array as $record)
    {
        $csv.= $record."\n"; //Append data to csv
    }
    $csv.= "\n"; //Append data to csv
}
$csv_handler = fopen('csvfile.csv','w');
fwrite ($csv_handler,$csv);
$filename = "csvfile.csv";
$filenamenew = "csvfile.csv";
header("Content-type:application/csv");
header("Content-Length: ".filesize($filename));
header("Content-Disposition:attachment;filename=\"".$filenamenew.'"');
readfile($filename);
unlink($filename);
fclose ($csv_handler);
//echo 'Data saved to csvfile.csv';
?>