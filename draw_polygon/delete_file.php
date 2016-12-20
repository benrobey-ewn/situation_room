<?php
if(isset($_POST['file_name']) && $_POST['file_name']!='')
{
	$path = "../".$_POST['file_name'];
	unlink($path);
}
?>