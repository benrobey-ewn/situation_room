<?php
header('Access-Control-Allow-Origin: *');
$json_data=file_get_contents('php://input');
$all_data = json_decode($json_data, true);
//echo "<pre>";
$polygonmainarray = array();
$polygonresponsearray = array();
foreach ($all_data as $key => $value)
{
        if (!in_array($value['polygon_subject'], $polygonmainarray))
        {
            $polygonmainarray[] = $value['polygon_subject'];
            $newarray= array();
            $newarray['polygon_layer_name'] = $value['layer_type'];
            $newarray['placemarker_name'] = array($value['placemarker_name']);
            $newarray['polygon_subject'] = $value['polygon_subject'];
            $polygonresponsearray[$value['polygon_subject']] = $newarray;

        }

        else
        {
            $polygon_object = $polygonresponsearray[$value['polygon_subject']];
            $polygon_object['placemarker_name'][] = $value['placemarker_name'];
            $polygonresponsearray[$value['polygon_subject']] = $polygon_object;
        }
}

$csv='';
foreach ($polygonresponsearray as $polygon => $polygonv)
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
unlink('../csv/csvfile.csv');
$csv_handler = fopen('../csv/csvfile.csv','w');
fwrite ($csv_handler,$csv);
fclose ($csv_handler);
echo json_encode($polygonresponsearray);
?>