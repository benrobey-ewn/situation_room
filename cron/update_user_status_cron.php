<?php
error_reporting(0);
include '../includes/config.php'; 
ini_set('max_execution_time', 50000000); //300 seconds = 5 minutes

// $sqlst = "select * from `clients` where is_deleted=0 AND account_status=1";
$sqlst = "SELECT * FROM `clients` WHERE is_deleted=0";
$rsst = mysql_query($sqlst);
$numst = mysql_num_rows($rsst);
if($numst!=0)
{
	$todaysDate =  date("m/d/Y");
	while($fetchst = mysql_fetch_array($rsst))
	{ 
		$date_allowed = $fetchst['days_allowed'];
		if(strtotime($todaysDate) > strtotime($date_allowed))
		{
          $sqlUpdate = "UPDATE `clients` SET account_status = 2 WHERE id='".$fetchst['id']."'";
          // echo '<br/>';
          mysql_query($sqlUpdate);
		}
	}
}
?>
