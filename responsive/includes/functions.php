<?php 

// get system information
function system_info()
{

    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];

    $os_platform    =   "Unknown OS Platform";
    
    $browser        =   "Unknown Browser";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/macintosh|mac os x|Mac OS X/i' =>  'Mac OS X',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile',
                            '/Windows Phone/i'      =>   'Windows Phone' 
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    } 


    $browser_array  =   array(
                            '/mobile/i'     =>  'Handheld Browser',
                            '/msie|MSIE|iemobile/i'       =>  'Internet Explorer',
                            '/trident/i'    =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }

    return array(
         "user_agent"     =>    $user_agent,
         "os"     =>    $os_platform,
         "browser"     =>    $browser,
        );
}
// get system information
// user session update function
function update_user_session()
{
     $where = "`user_id`='". $_SESSION['user_id']."' AND `session_id`='".$_SESSION['session_id']."'"; 

    if($_SESSION['session_id'] !=  session_id())
    {
        $delete_active_session = mysql_query("DELETE FROM `user_sessions` WHERE $where");
        if($delete_active_session > 0)
        {
            $_SESSION['session_id'] =  session_id();
            $get_system = system_info();
            $insert_active_session = mysql_query("INSERT INTO `user_sessions` SET 
                                                           `user_id` = '".$_SESSION['user_id']."',
                                                           `session_id` = '".$_SESSION['session_id']."',
                                                           `user_agent` = '".$get_system['user_agent']."',
                                                           `browser` = '".$get_system['browser']."',
                                                           `os` = '".$get_system['os']."',
                                                           `ip_address` = '".$_SERVER['REMOTE_ADDR']."',
                                                           `created_at` = '".$current_datetime."',
                                                           `updated_at` = '".$current_datetime."'");
        }
    }
    else
    {
        //$select_active_session = mysql_query("SELECT * FROM `user_sessions` WHERE $where");
        echo "updated session";
        $update_active_session = mysql_query("UPDATE `user_sessions` SET `updated_at` = '".date("Y-m-d H:i:s")."' WHERE $where");
    }
    return true;
}
// user session update function
// force terminate session 
function session_force_destroy()
{
     $query = "SELECT * FROM `clients` WHERE `id`= '".$_SESSION['user_id']."'";
     $clients = mysql_query($query);
     $clients_data = mysql_fetch_assoc($clients);
    if($clients_data['is_admin']!=1 && $_SESSION['multiple_allowed']==false)
    {
        $active_session = mysql_query("SELECT `id` as `total_active_session` FROM `user_sessions` WHERE  user_id = '".$_SESSION['user_id']."' AND `session_id` = '".$_SESSION['session_id']."' ");

         if(!mysql_num_rows($active_session))
         {
            session_destroy();
            header("Location:login.php?max=1");
         }
    }

}
// force terminate session 


 ?>