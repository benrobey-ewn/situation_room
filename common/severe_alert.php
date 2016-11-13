<?php
include '../includes/config.php';
$SevereWeather=array(37,38,39,40);
$topicIDs=  implode(',', $SevereWeather);

function ConvertGMTToLocalTimezone($gmttime)
{
    $system_timezone = date_default_timezone_get();

    date_default_timezone_set("GMT");
    $gmt = date("Y-m-d h:i:s A");

    $local_timezone = $system_timezone;
    date_default_timezone_set($local_timezone);
    $local = date("Y-m-d h:i:s A");

    date_default_timezone_set($system_timezone);
    $diff = (strtotime($local) - strtotime($gmt));

    $date = new DateTime($gmttime);
    $date->modify("+$diff seconds");
    $timestamp = $date->format("Y-m-d H:i:s");
    return $timestamp;
}

/*get server timezone*/
$server_system_timezone = date_default_timezone_get(); 
date_default_timezone_set($server_system_timezone);  
 /*get server timezone*/

$current_time=date("H:i:s"); /*Time return in server time zone Australia/Melbourne current  */
 if($current_time>='09:00:00')
 {
        $newdate = date('Y-m-d');
  } else {
        $newdate=date('Y-m-d',strtotime('- 1 days', strtotime(date('Y-m-d'))));
  }

 
 $todaydate=strtotime("$newdate 00:00:00");
 $todaydateEnd=strtotime("$newdate 23:59:59");

 $tomorrow_set_date = date('Y-m-d', strtotime('+ 1 days', strtotime($newdate)));
 $tomorrow_date=strtotime("$tomorrow_set_date 00:00:00");
 $tomorrow_dateEnd=strtotime("$tomorrow_set_date 23:59:59");
 $tomorrow_dateEnd_time=strtotime("$tomorrow_set_date 09:00:00");


 $day_after_tomorrow_date = date('Y-m-d', strtotime('+ 2 days', strtotime($newdate)));
 $day_after_tomorrow_date=strtotime("$day_after_tomorrow_date 09:00:00");
 $day_after_tomorrow_date_time=strtotime("$day_after_tomorrow_date 09:00:00");

 $system_timezone = date_default_timezone_get();
 date_default_timezone_set('UTC');
 $todaydate=date("Y-m-d H:i:s", $todaydate);
 $todaydateEnd=date("Y-m-d H:i:s", $todaydateEnd);
 $tomorrow_date=date("Y-m-d H:i:s", $tomorrow_date);
 $tomorrow_dateEnd=date("Y-m-d H:i:s", $tomorrow_dateEnd);
$tomorrow_dateEnd_time=date("Y-m-d H:i:s", $tomorrow_dateEnd_time);
$day_after_tomorrow_date_time=date("Y-m-d H:i:s", $day_after_tomorrow_date_time);

 $day_after_tomorrow_date=date("Y-m-d H:i:s", $day_after_tomorrow_date);
 date_default_timezone_set($system_timezone);


$Alert_array=array();
$selectSQL = "SELECT id,topic_id,AlertFullURL,Subject,Sent FROM Alerts where topic_id in (".$topicIDs.") AND  Sent >='".$todaydate."' AND Sent <='".$todaydateEnd."' AND (Expires < '".$tomorrow_dateEnd_time."' OR Expires >= '".$tomorrow_dateEnd_time."') AND is_deleted=0 ORDER BY `Sent` DESC";
$result = mysql_query($selectSQL);
//echo mysql_num_rows($result);
while($data=  mysql_fetch_assoc($result))
{
	
	$alert=array();
	$alert=$data;
        $Alert_array[]=$alert;
}

//echo '<pre>';
//print_r($Alert_array); die;
?>
<html>
<head>
	<title>Ewn Severe Weather Forecasts Alerts</title>
	<style>
	body
	{
		font-family:Arial, Verdana, Geneva, sans-serif;
		-webkit-font-smoothing: antialiased;
		font-weight:normal;
	}
	body, html
	{
		height:100%;
	}
	body, *
	{
		margin:0;
		padding:0;
	}
	ul li
	{
		list-style:none;
	}
	.alert
	{
		/*width:800px;*/
		height:auto;
		margin:0 auto;
		border:3px solid #F18308;
	}
	.alert a
	{
		text-decoration:none;
		color:#262626;
		display:block;
	}
	.alert .header
	{
		clear:both;
		height:40px;
		background:#F18308;
	}
	.select_box select
	{
		height:30px;
		border-radius:3px;
		margin-left:5px;
		margin-top:4px;
		width:60px;
	}
			.alert .pageTitle h1
	{
		font-size:28px;
		text-align:center;
		line-height:37px;
	}
	.alert ul
	{
		list-style:none;
		padding:0 15px;
	}
	.alert li
	{
		list-style:none;
		margin-bottom:10px;
	}
	.alert li.red, .alert li.green, .alert li.yellow, .alert li.blue, .alert li.grey, .alert li.purple, .alert li.orange,.alert li.pink, .alert li.light_blue,.alert li.brown
	{
		border:1px solid #FF0000;
		border-left:10px solid #FF0000;
		min-height:70px;
		position:relative;
		padding-left:5px;
	}
	.alert li.grey
	{
		border:1px solid #515151;
		border-left:10px solid #515151;	
	}
	.alert li.blue
	{
		border:1px solid #0000FF;
		border-left:10px solid #0000FF;	
	}
        .alert li.light_blue
	{
		border:1px solid #00FFFF;
		border-left:10px solid #00FFFF;	
	}
	.alert li.green
	{
		border:1px solid #32CD32;
		border-left:10px solid #32CD32;	
	}
	.alert li.yellow
	{
		border:1px solid #FFFF00;
		border-left:10px solid #FFFF00;	
	}
	.alert li.purple
	{
		border:1px solid #7B68EE;
		border-left:10px solid #7B68EE;	
	}
	.alert li.orange
	{
		border:1px solid #ff6700;
		border-left:10px solid #ff6700;	
	}
	.alert li.pink
	{
		border:1px solid #FF00FF;
		border-left:10px solid #FF00FF;	
	}
        .alert li.brown
	{
		border:1px solid #A52A2A;
		border-left:10px solid #A52A2A;	
	}
        
        
        
	.alert li.red .date, .alert li.green .date, .alert li.yellow .date, .alert li.blue .date, .alert li.grey .date,.alert li.purple .date, .alert li.orange .date,.alert li.pink .date,.alert li.light_blue .date,.alert li.brown .date
	{
		font-size:14px;
		color:#454545;
		clear:both;
		padding:5px;
		margin-top:10px;
	}
	.alert li.red .title, .alert li.green .title, .alert li.yellow .title, .alert li.blue .title, .alert li.grey .title,.alert li.purple .title,.alert li.orange .title,.alert li.pink .title,.alert li.light_blue .title,.alert li.brown .title
	{
		font-size:17px;
		clear:both;
		padding:5px;
		padding-top:0px;
	}
	.alert li .buttonmap span
	{
		float:right;
		margin-top:6px;
		margin-top:3px\9;
		padding-left:5px;
		*+margin-top:-19px;
	}
	@media screen and (-ms-high-contrast: active), (-ms-high-contrast: none)
	{ 
		.alert li .buttonmap span
		{
			margin-top:6px;
		}
	}
	.alert li.red .buttonmap, .alert li.green .buttonmap, .alert li.yellow .buttonmap, .alert li.blue .buttonmap, .alert li.grey .buttonmap,.alert li.purple .buttonmap,.alert li.orange .buttonmap,.alert li.pink .buttonmap,.alert li.light_blue .buttonmap,.alert li.brown .buttonmap
	{
		border:1px solid rgba(255, 0, 0, .3);
		position:absolute;
		right:5px;
		right:25px\9;
		right:5px\9\0;
		top:5px;
		background:#fff;
		cursor:pointer;
		outline:none;
		font-size:13px;
		height:24px;
		line-height:24px;
		padding:0 5px;
		*+width:110px;
		border:1px solid #ffbfbf\9;
	}
	.alert li.green .buttonmap
	{
		border:1px solid rgba(50, 205, 50, 1);
		border:1px solid #b2ffb2\9;
	}
	.alert li.yellow .buttonmap
	{
		border:1px solid rgba(255, 255, 0, .3);
		border:1px solid #ffffb2\9;
	}
	.alert li.blue .buttonmap
	{
		border:1px solid rgba(0, 0, 255, .3);
		border:1px solid #b2b2ff\9;
	}
	.alert li.grey .buttonmap
	{
		border:1px solid rgba(51, 51, 51, .3);
		border:1px solid #c1c1c1\9;
	}
	.alert li.purple .buttonmap
	{
		border:1px solid rgba(123, 104, 238, 1);
		border:1px solid #d9b2d9\9;
	}
	.alert li.orange .buttonmap
	{
		border:1px solid rgba(255, 132, 0, .3);
		border:1px solid #ffdab2\9;
	}
	.alert li.pink .buttonmap
	{
		border:1px solid rgba(255, 0, 255, 1);
		border:1px solid #ffe6ea\9;
	}
        .alert li.light_blue .buttonmap
	{
		border:1px solid rgba(0, 255, 255, 1);
		border:1px solid #c2d8ee\9;
	}
        .alert li.brown .buttonmap
	{
		border:1px solid rgba(165, 42, 42, .3);
		border:1px solid #e4bfbf\9;
	}
	.alert .buttonmap img
	{
		border:0;
	}
    </style>
	<link href="../css/fonts.css" rel="stylesheet" type="text/css">
	<script src="../js/jquery-1.7.2.js"></script>
	<script type="text/javascript">
		function delete_warning(id)
		 {
		 	var r = confirm("Are you sure want to delete this warning?");
                        if (r == true) {
                            //x = "You pressed OK!";
                            $('#loader').show();
                            $.ajax({
			    	type: "POST",
			    	dataType: "json",
			    	timeout: 60000,
			    	url:'../ajax/delete_warning.php',
			    	data: { alert_id:id },
			    	error: function(x, t, m) {
			    		if(t==="timeout") {
			    			alert('connection error');
			    		} else {
			    			alert(t);
			    		}
			    		$('#loader').hide();
			    	},
			    	success: function(result) { 
			    		
			    		$('#loader').hide();  
			    		if(result.status=='fail' || result.status==false){
				   	  return;
					}
                                       $('#show'+id).remove();
					
				}
                            });
                        } else {
                            //x = "You pressed Cancel!";
                        }
                 }
        </script>
	<style>
		#loader {
			height: 100%;
			opacity: 0.6;
			background:url(../images/loading2.gif) no-repeat scroll 50% 50% rgba(255, 255, 255, 0.8);
                        left: 0;
			position: absolute;
			top: 0;
			width: 100%;
			z-index: 1000;
		}
	</style>

</head>
<body class="alertbody">
	<div id="loader" style="display:none;"></div> 
	<div class="alert">
		<div class="header" style="margin-bottom:10px;">
			<div style="float:left;">
			</div>
			<div style="float:left;width:100%;text-align:center;color:#fff;">
				<div class="pageTitle">
					<h1>The Early Warning Network : Recent Alerts</h1>
				</div>
			</div>
			<div style="clear:left;">
			</div>
		</div>
		<!--/header-->
                <ul id="content">
		    <?php if(count($Alert_array)>0) {
                      foreach ($Alert_array as $element) {
                        if ($element['topic_id']=='38') {
                                $class='pink';
			       $colorcode='#FFC0CB';
                        } else if ($element['topic_id']=='37') {
                                $class='light_blue';
				$colorcode='#79C2FF';
                        } else if ($element['topic_id']=='39') {
                                $class='purple';
				$colorcode='#C2AFE6';
                        } else if ($element['topic_id']=='40') {
                                $class='green';
				$colorcode='#008000';
                        } 

                            $newtimedate = ConvertGMTToLocalTimezone($element['Sent'],$system_timezone);
                            $element['Sent']=date('Y M d H:i',strtotime($newtimedate));  
                            $show_id='show'.$element['id'];
                        ?> 
				<li class="<?php echo $class; ?>" id="<?php echo $show_id; ?>">
					<div class="date"><?php echo $element['Sent'];?> </div>
				    <div class="title">
                                        <a href='javascript:void(0)' onclick='newPopup("<?php echo $element['AlertFullURL'];?>","700","550")'><?php echo $element['Subject'];?></a></div>
					<div class="buttonmap">
                                        <a href="javascript:void(0);" onclick='delete_warning(<?php echo $element['id']; ?>)' title="Delete">
		                	Delete
                                        </a>
                                    </div>
                                </li>

				<?php } } else {?>
                                <li>NO WARNINGS - Please check back later</</li>
                                <?php } ?>
			</ul>
		</div>
	</body>
	</html>
