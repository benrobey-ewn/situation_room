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
	    $polygon_unqiue_name=$value['polygon_subject'].'>'.$value['created_date'];
        if (!in_array($polygon_unqiue_name, $polygonmainarray))
        {
            $polygonmainarray[] = $polygon_unqiue_name;
            $newarray= array();
            //$newarray['polygon_layer_name'] = $value['layer_type'];
            $newarray['layers'][$value['layer_type']]['placemarker_name'] = array($value['placemarker_name']);
            //$newarray['layers'][$value['layer_type']]['polygon_latitude'] = array($value['latitude']);
            //$newarray['layers'][$value['layer_type']]['polygon_longitude'] = array($value['longitude']);

            $newarray['polygon_subject'] = $value['polygon_subject'];
            $newarray['created_date'] = $value['created_date'];
          //  $newarray['polygon_latitude'] = $value['latitude'];
           // $newarray['polygon_longitude'] = $value['longitude'];
            $polygonresponsearray[$polygon_unqiue_name] = $newarray;

        }

        else
        {
            $polygon_object = $polygonresponsearray[$polygon_unqiue_name];
            if(isset($polygon_object['layers'][$value['layer_type']])) {
                $polygon_object['layers'][$value['layer_type']]['placemarker_name'][] = $value['placemarker_name'];
                //$polygon_object['layers'][$value['layer_type']]['polygon_latitude'][] = $value['latitude'];
                //$polygon_object['layers'][$value['layer_type']]['polygon_longitude'][] = $value['longitude'];
            } else {
                $polygon_object['layers'][$value['layer_type']]['placemarker_name'] = array($value['placemarker_name']);
                 // $polygon_object['layers'][$value['layer_type']]['polygon_latitude'] = array($value['latitude']);
                //$polygon_object['layers'][$value['layer_type']]['polygon_longitude'] = array($value['longitude']);
            }
            $polygonresponsearray[$polygon_unqiue_name] = $polygon_object;
        }
        
         //echo $value['placemarker_name'].'>>'.$value['layer_type'];
}

$csv='';
            //echo "<pre>";

//print_r($polygonresponsearray);
//die;
foreach ($polygonresponsearray as $polygon => $polygonv)
{
     $polygonv['polygon_subject']=str_replace(',','',$polygonv['polygon_subject']);
    $csv .= "Warning,{$polygonv['polygon_subject']},{$polygonv['created_date']} \n";//Column headers
    //echo $polygonv['polygon_layer_name'];
    //print_r($polygonv['layers']);
    foreach ($polygonv['layers'] as $key => $val) {
         $key=str_replace(',','',$key);
         $csv .= "Layer,{$key} \n";//Column headers
         //print_r($key);
         //print_r($val);
        foreach ($val as $record)
        {
            //print_r($record);
            foreach($record as $placemarker) {
                //print_r($placemarker);
                $placemarker=str_replace(",","",$placemarker);
                $csv.= $placemarker."\n"; //Append data to csv
            }
            
        }
        
        $csv.= "\n"; //Append data to csv
    }
    
    $csv.= "\n"; //Append data to csv
}

//print_r($csv);

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