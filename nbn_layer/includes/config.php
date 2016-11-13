<?php
error_reporting(0);
/*define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'situation_room');*/

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'situatio_dev');
define('DB_PASSWORD', 'sr*123');
define('DB_DATABASE', 'situatio_situationroom_dev');

$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
mysql_select_db(DB_DATABASE,$con);



?>