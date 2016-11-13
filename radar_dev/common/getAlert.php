<?php  session_start();
$stateID=1;
$SevereWeather=array(23,33,34,35);
$AlertData=array();
$AlertData=$_SESSION['AlertData'];

if(isset($_REQUEST['state_id']))  {
	$stateID=$_REQUEST['state_id'];
}

$response='';
$i=0;

foreach ($AlertData as $element) {
	if (strpos($element['Subject'],$stateID) !== false || $stateID=='All') {
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
                                $colorcode='#800080';
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
		$show_id='show'.$element['id'];
                $hide_id='hide'.$element['id'];
		$element['Sent']=date('Y M d H:i',strtotime($element['Sent']));
                $corodinates=json_encode($element['polygon']);
		//$response.= '<li class="'.$class.'"><div class="date">'.$element['Sent'].' </div><div class="title"><a href="javascript:void(0)" onclick=newPopup("'.$element['AlertFullURL'].'","800","660")>'.$element['Subject'].'</a></div><button id="'.$show_id.'" onclick=show_map('.$element['id'].','.$corodinates.',"'.$colorcode.'","'.$element['AlertFullURL'].'")>Show on Map</button><button id="'.$hide_id.'" style="display:none;" onclick=remove_warning('.$element['id'].')>Hide on Map</button></li>'; 
	   $response.= '<li class="'.$class.'"><div class="date">'.$element['Sent'].' </div><div class="title"><a href="javascript:void(0)" onclick=newPopup("'.$element['AlertFullURL'].'","700","550")>'.$element['Subject'].'</a></div><div class="buttonmap"><a id="'.$show_id.'" href="javascript:void(0);" onclick=show_map('.$element['id'].','.$corodinates.',"'.$colorcode.'","'.$element['AlertFullURL'].'") title="Show on Map">Show on Map<span><img src="../images/map.png" width="15" height="14" alt="map"></span></a><a style="display: none;" id="'.$hide_id.'" href="javascript:void(0);" onclick=remove_warning('.$element['id'].') title="Hide on Map">Hide on Map<span><img src="../images/map.png" width="15" height="14" alt="map"></span></a></div></li>'; 


    }
}
if(!empty($response)) {
    
} else {
    $response.='<li>NO WARNINGS - Please check back later.</li>';
}
$output=array('status'=>'true','data'=>$response);
echo json_encode($output);
?>