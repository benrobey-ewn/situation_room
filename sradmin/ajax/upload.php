<?php 
 require_once '../includes/sit_room.php';
error_reporting(0);
if(isset($_FILES['image_upload']) && $_FILES['image_upload']['error']=="0")
{
    $allowed_type = array("gif","jpeg","png","bmp","jpg");
    $extension  = end(explode(".",$_FILES['image_upload']['name']));
    $filename = time()."-".rand(999,9999).".".$extension;
    $path  = "../temp/".$filename;
    $return_path  = "temp/".$filename;

    if(in_array($extension, $allowed_type))
    {
        if(move_uploaded_file($_FILES['image_upload']['tmp_name'], $path))
        {
            echo $return_path;
        }
    }
}

?>