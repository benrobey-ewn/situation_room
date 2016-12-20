<?php 

function getAllTopics(){
	$arr = "";
	 $select = "SELECT topic_id,alert_type FROM  `topics`";
	 $selectRes = mysql_query($select);
	 if($selectRes > 0){
	 	 while($row = mysql_fetch_assoc($selectRes)){
	 	 	 $arr[$row['topic_id']] = ucfirst($row['alert_type']);
	 	 }
	 }
	 return $arr;
}


function getTopicsDropDown(){
	 $html = "";
	 $allTopics = getAllTopics();
	 foreach ($allTopics as $topic_id => $alert_type ) {
	 	   if($topic_id==1 || $topic_id==37){
	 	   	if($topic_id==1){
	 	   		$optgrouplabel = "Warnings";
	 	   	} else {
	 	   		$optgrouplabel = "Forecast Severe Weather";
	 	   	}
	 	     $html.='<optgroup label="'.$optgrouplabel.'">';
	 	   }
	 	 	 $html.='<option value="'.$topic_id.'">'.$alert_type.'</option>';
   	 	 if($topic_id==36 || $topic_id==40){
	   	   $html.='</optgroup>';	
	 	   }
	 }
	 return $html;
}




 ?>
 