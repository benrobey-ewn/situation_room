<?php
include '../includes/config.php';
$SevereWeather=array(23,33,34,35);
$region=1;
$imageMode='infrared';
$map_type='BSCH';
session_start();
unset($_SESSION['AlertData']);
if(isset($_REQUEST['region']))
{
	$region=$_REQUEST['region'];
}
if(isset($_REQUEST['imageMode']))
{ 
	$imageMode=$_REQUEST['imageMode'];
}

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

// set the default timezone to use. Available since PHP 5.1
 $system_timezone = date_default_timezone_get();
 date_default_timezone_set('UTC');
$newdate = date('Y-m-d H:i:s');

date_default_timezone_set($system_timezone);

$Alert_array=array();
//$newdate =  date('Y-m-d H:i:s', strtotime($current_time) - 60 * 60 * 11);
$days_ago = date('Y-m-d h:i:s', strtotime('-2 days', strtotime($newdate)));
$selectSQL="SELECT * FROM Alerts where Expires >='".$newdate."' AND (Sent >='".$days_ago."' AND Sent <='".$newdate."') AND topic_id NOT IN (37,38,39,40) ORDER BY `Sent` DESC";
//$selectSQLtest = "SELECT * FROM Alerts order by `Sent` DESC";
$result = mysql_query($selectSQL);
//echo mysql_num_rows($result);
while($data=  mysql_fetch_assoc($result))
{
	//print_r($data); die;
	$alert=array();
	//echo "{$key1}: {$val1}"."<br>";
	$alert=$data;

                
                
                $polygon=array();
                $selectpolygon="SELECT * FROM polygon_location where alert_id ='".$data['id']."'";
                $polgonresult = mysql_query($selectpolygon);
                while($polygondata=  mysql_fetch_assoc($polgonresult)) {
                    $polygon[]=json_decode($polygondata['lat_long_pair']);
                }
                
                $alert['polygon']=$polygon;
                

                $Alert_array[]=$alert;
}


$_SESSION['AlertData']=$Alert_array;
//echo '<pre>';
//print_r($Alert_array); die;
?>
<html>
<head>
	<title>Ewn Alerts</title>
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
		border:1px solid #79C2FF;
		border-left:10px solid #79C2FF;	
	}
	.alert li.green
	{
		border:1px solid #00ff00;
		border-left:10px solid #00ff00;	
	}
	.alert li.yellow
	{
		border:1px solid #FFFF00;
		border-left:10px solid #FFFF00;	
	}
	.alert li.purple
	{
		border:1px solid #C2AFE6;
		border-left:10px solid #C2AFE6;	
	}
	.alert li.orange
	{
		border:1px solid #ff6700;
		border-left:10px solid #ff6700;	
	}
	.alert li.pink
	{
		border:1px solid #f0147f;
		border-left:10px solid #f0147f;	
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
		border:1px solid rgba(0, 255, 0, .3);
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
		border:1px solid rgba(128, 0, 128, .3);
		border:1px solid #d9b2d9\9;
	}
	.alert li.orange .buttonmap
	{
		border:1px solid rgba(255, 132, 0, .3);
		border:1px solid #ffdab2\9;
	}
	.alert li.pink .buttonmap
	{
		border:1px solid rgba(255, 192, 203, .3);
		border:1px solid #ffe6ea\9;
	}
        .alert li.light_blue .buttonmap
	{
		border:1px solid rgba(53, 126, 199, .3);
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
		$(document).ready(function(){
			$('#state').on('change',function() {
			    //alert(this.value);
			    $('#loader').show();
			    var state_id=this.value
			    $.ajax({
			    	type: "GET",
			    	dataType: "json",
			    	timeout: 60000,
			    	url:'getAlert.php',
			    	data: { state_id:state_id },
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
				   	    //messageAlert(Login_failed);
					  //alert_wrapper_function(Login_failed,function() {});
					  return;
					}
					$("#content").html('');
					$("#content").html(result.data);
				}
			});
			});

		});


		function show_map(id,polygon_type,color_code,url)
		 {
		 	
			opener.draw_map(polygon_type,color_code,url,id,'external');
                        $('#show'+id).hide();
                        $('#hide'+id).show();
                        
                        //self.close(); 
		}
                
                function remove_warning(id) {
                       opener.remove_warning_single(id);
                       $('#hide'+id).hide();
                       $('#show'+id).show();
                       
                }
                
                 function newPopup(url,width,height)
                {
                    popupWindowName = 'EWNPopup' + Math.floor((Math.random()*500)+1);
                    popupWindow = window.open(
                    url,popupWindowName,'height=' + height + ',width=' + width +            ',left=100,top=10,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
                }


		
	</script>
	<style>
		#loader {
			height: 100%;
			/*background: url(http://google-web-toolkit.googlecode.com/svn-history/r8457/trunk/user/src/com/google/gwt/cell/client/loading.gif) no-repeat 50% 50%;*/
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

		<div class="select_box" style="margin-left:15px;margin-bottom:10px;">State
			<select name="state" id="state">
				<option value="All">All</option>
				<option value="NSW">NSW</option>
				<option value="QLD">QLD</option>
				<option value="WA">WA</option>
				<option value="SA">SA</option>
				<option value="VIC">VIC</option>
				<option value="TAS">TAS</option>
				<option value="NT">NT</option>
				<option value="ACT">ACT</option>
			</select>
		</div>

		<ul id="content">
		    <?php if(count($Alert_array)>0) {
                      foreach ($Alert_array as $element) {
                       if(in_array($element['topic_id'],$SevereWeather)) {
                               $class='pink';
			       $colorcode='#FFC0CB';
                        } else if ($element['topic_id']=='28') {
                                $class='red';
				$colorcode='#FF0000';
                        } else if ($element['topic_id']=='13') {
                                $class='orange';
				$colorcode='#FF8800';
                        } else if ($element['topic_id']=='14') {
                                $class='blue';
				$colorcode='#0000FF';
                        } else if ($element['topic_id']=='36') {
                            if (strpos($element['Subject'],'Bushfire Watch & Act') !== false) {
                                $class='purple';
                                $colorcode='#C2AFE6';
                            } else {
                                $class='brown';
                                $colorcode='#A52A2A';
                            }
                        } else if ($element['topic_id']=='29') {
                                $class='green';
				$colorcode='#008000';
                        } else if ($element['topic_id']=='31') {
                                $class='light_blue';
				$colorcode='#79C2FF';
                        } else {
                                $class='yellow';
				$colorcode='#FFFF00';
                        }

                        $newtimedate = ConvertGMTToLocalTimezone($element['Sent'],$system_timezone);
                        $element['Sent']=date('Y M d H:i',strtotime($newtimedate));  
                        $corodinates=json_encode($element['polygon']);
                        $show_id='show'.$element['id'];
                        $hide_id='hide'.$element['id'];
                        //echo $class; die;
                        ?> 
				<li class="<?php echo $class;?>">
					<div class="date"><?php echo $element['Sent'];?> </div>
				    <div class="title"><a href='javascript:void(0)' onclick='newPopup("<?php echo $element['AlertFullURL'];?>","700","550")'><?php echo $element['Subject'];?></a></div>
					<div class="buttonmap">
		            	<a id="<?php echo $show_id; ?>" href="javascript:void(0);" onclick='show_map(<?php echo $element['id']; ?>,<?php echo $corodinates; ?>,"<?php echo $colorcode; ?>","<?php echo $element['AlertFullURL'];?>")' title="Show on Map">
		                	Show on Map
		                	<span><img src="../images/map.png" width="15" height="14" alt="map"></span>
		                </a>
		                <a style="display: none;" id="<?php echo $hide_id; ?>" href="javascript:void(0);" onclick='remove_warning(<?php echo $element['id']; ?>)' title="Hide on Map">
		                	Hide on Map
		                	<span><img src="../images/map.png" width="15" height="14" alt="map"></span>
		                </a>
	         	    </div>

                                </li>

				<?php } } else {?>
                                <li>NO WARNINGS - Please check back later<!-- Currently there are no active alerts to display. Check after some time -->.</</li>
                                <?php } ?>
			</ul>
		</div>
	</body>
	</html>
