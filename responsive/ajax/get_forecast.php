<?php
include '../includes/config.php';
$response=array();
$info_response = array();

if($_POST['LOGIN_USER_NAME']=='admin' || $_POST['LOGIN_USER_NAME']=='bechtel')
{
	$sqlst = "select * from `forecast_layers`";
}
else
{
	$sqlst = "select * from `forecast_layers` where `state_name`!='Learmonth'";
}
$rsst = mysql_query($sqlst);
$numst = mysql_num_rows($rsst);
if($numst!=0)
{
	while($fetchst = mysql_fetch_object($rsst))
	{

		if (file_exists('../images/forecast/transparent/'.$fetchst->icon_name)) 
		{
			$fetchst->icon_name = $fetchst->icon_name;
		} 
		else
		{
			$fetchst->icon_name = 'magic.gif';
		}

		//$fetchst->icon_name = $fetchst->icon_name;


		$response[]=$fetchst;

		$sqlsub = "select * from `forecast_layer_info` where `forecast_title`='".$fetchst->state_name."'";
		$rssub=mysql_query($sqlsub);
		$numsub=mysql_num_rows($rssub);
		if($numsub!=0)
		{
			while($fetchsub = mysql_fetch_object($rssub))
			{
				$info_response[$fetchst->state_name][] = $fetchsub;
			}
		}
	}
}

$output=array('status'=>'true','marker_data'=>$response,'marker_info'=>$info_response);
//echo "<pre>";
//print_r($output);
//die;
echo json_encode($output);
?>