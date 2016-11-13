#!/usr/bin/php -q
<?php

        $filename = "test.txt";
        $hostname = "localhost";
        $username = "situatio_gpats";
        $password = "1JdE8724";
        $dbname = "situatio_situationroom_dev";

        $link = mysql_connect($hostname,$username,$password)
                or die ("Cound not connect");

        mysql_select_db($dbname);
        //date_default_timezone_set("Australia/Brisbane");

        if(file_exists($filename)) {
                $data = file_get_contents($filename);

                $f = @fopen($filename, "r+");
                if ($f !== false) {
                        ftruncate($f, 0);
                        fclose($f);
                }
                //if($data!="") { unlink($filename); }

                $dataArray = explode("\n",$data);
                for($i=0;$i<count($dataArray);$i++) {
                        if($dataArray[$i]!="") {
                                $tempData = explode(",",$dataArray[$i]);

                                $type = $tempData[0];
                                $timeHr = floor($tempData[1]/60);
                                $timeMin = fmod($tempData[1], 60);
                                $timeSec = round($tempData[2]/1000,0);
                                $tempTime = $tempData[3]."-".$tempData[4]."-".$tempData[5]." ".$timeHr.":".$timeMin.":".$timeSec;
                                $dateTime = strtotime($tempTime)+36000;

                                $lat = $tempData[6]/10000000;
                                $long = $tempData[7]/10000000;

                                $mag = hexdecs($tempData[8]);

                                if($lat < -5 && $lat > -45 && $long > 105 && $long < 160) {
                                        echo "$type,$dateTime,$lat,$long,$mag $timeHr $timeMin $timeSec ".$tempData[3]."/".$tempData[4]."/".$tempData[5];
                                        echo "\n";

                                        $query = "INSERT INTO `gpats_data` (`id`,`type`,`timestamp`,`lat`,`long`,`mag`) VALUES (NULL, $type, $dateTime, $lat, $long, $mag);";
                                        $result = mysql_query($query);
                                } else {
                                        echo "$type,$dateTime,$lat,$long,$mag $timeHr $timeMin $timeSec ".$tempData[3]."/".$tempData[4]."/".$tempData[5]." Outside Lat/Long - Ignored";
                                        echo "\n";
                                }
                        }
                }

                //echo mysql_affected_rows();
        }

        function hexdecs($hex) {
                $hex = preg_replace('/[^0-9A-Fa-f]/', '', $hex);

                $dec = hexdec($hex);
                $max = pow(2, 4 * (strlen($hex) + (strlen($hex) & 2)));

                $_dec = $max - $dec;

                return $dec > $_dec ? -$_dec : $dec;
        }
?>
