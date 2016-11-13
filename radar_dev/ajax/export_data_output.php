<?php
header('Access-Control-Allow-Origin: *');
$json_data=file_get_contents('php://input');
$all_data = json_decode($json_data, true);
//echo "<pre>";
$polygonmainarray = array();
$polygonresponsearray = array();
//echo '<pre>';
foreach ($all_data as $key => $value)
{
        if (!in_array($value['polygon_subject'], $polygonmainarray))
        {
            $polygonmainarray[] = $value['polygon_subject'];
            $newarray= array();
            //$newarray['polygon_layer_name'] = $value['layer_type'];
            $newarray['layers'][$value['layer_type']]['placemarker_name'] = array($value['placemarker_name']);
            $newarray['polygon_subject'] = $value['polygon_subject'];
            $polygonresponsearray[$value['polygon_subject']] = $newarray;

        }

        else
        {
            $polygon_object = $polygonresponsearray[$value['polygon_subject']];
            if(isset($polygon_object['layers'][$value['layer_type']])) {
                $polygon_object['layers'][$value['layer_type']]['placemarker_name'][] = $value['placemarker_name'];
            } else {
                $polygon_object['layers'][$value['layer_type']]['placemarker_name'] = array($value['placemarker_name']);
            }
            $polygonresponsearray[$value['polygon_subject']] = $polygon_object;
        }
        
         //echo $value['placemarker_name'].'>>'.$value['layer_type'];
}

$csv='';
//print_r($polygonresponsearray);
//die;
foreach ($polygonresponsearray as $polygon => $polygonv)
{
    $csv .= "Warning,{$polygonv['polygon_subject']} \n";//Column headers
    //echo $polygonv['polygon_layer_name'];
    //print_r($polygonv['layers']);
    foreach ($polygonv['layers'] as $key => $val) {
         $csv .= "Layer,{$key} \n";//Column headers
         //print_r($key);
         //print_r($val);
        foreach ($val as $record)
        {
            //print_r($record);
            foreach($record as $placemarker) {
                //print_r($placemarker);
                $csv.= $placemarker."\n"; //Append data to csv
            }
            
        }
        
        $csv.= "\n"; //Append data to csv
    }
    
    $csv.= "\n"; //Append data to csv
}
//die;
/*foreach ($polygonresponsearray as $polygon => $polygonv)
{
    
    $csv .= "Warning,{$polygonv['polygon_subject']} \n";//Column headers
    foreach ($polygonv['layer_type'] as $key => $val) {
        $csv .= "Layer,{$polygonv['polygon_layer_name']} \n";//Column headers
        $data_array = $val['placemarker_name'];
        foreach ($data_array as $record)
        {
            $csv.= $record."\n"; //Append data to csv
        }
    }
    $csv.= "\n"; //Append data to csv
}*/
unlink('../csv/csvfile.csv');
$csv_handler = fopen('../csv/csvfile.csv','w');
fwrite ($csv_handler,$csv);
fclose ($csv_handler);
echo json_encode($polygonresponsearray);
?>