   <?php
    $data_array = array (
            array ('ROCKHAMPTON - Port Alma','Queensland'),
            array ('ROCKHAMPTON - Port Alma','Queensland'),
            array ('ROCKHAMPTON - Port Alma','Queensland'),
            array(),
            array ('ROCKHAMPTON - Port Alma','Queensland'),
            );

$csv = "Layer,AU Ports \n";//Column headers
foreach ($data_array as $record){
    if(isset($record[0])) {
    $csv.= $record[0].','.$record[1]."\n"; //Append data to csv
    } else {
        $csv.= "\n"; //Append data to csv
    }
    }

$csv_handler = fopen('csvfile.csv','w');
fwrite ($csv_handler,$csv);
fclose ($csv_handler);

echo 'Data saved to csvfile.csv';
    
  ?>