<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
    <title>EWN Situation Room</title>
	<meta http-equiv="Cache-control" content="no-cache">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Expires" content="-1">
        <link href="css/style.css" rel="stylesheet" type="text/css">
    <style>
body {
    /*width: 600px;*/
    margin:0px auto;
    font-family: 'trebuchet MS', 'Lucida sans', Arial;
    font-size: 14px;
    color: #444;
}

table {
    *border-collapse: collapse; /* IE7 and lower */
    border-spacing: 0;
    width: 100%;    
}

.bordered
{
    border: solid #ccc 1px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: 0 1px 1px #ccc; 
    -moz-box-shadow: 0 1px 1px #ccc; 
    box-shadow: 0 1px 1px #ccc;
	font-size:15px;
}
.bordered a
{
	text-decoration:none;
	display:block;
	color:#0d79c1;
}
.bordered tr:hover {
    background: #f0f7ff;
    -o-transition: all 0.1s ease-in-out;
    -webkit-transition: all 0.1s ease-in-out;
    -moz-transition: all 0.1s ease-in-out;
    -ms-transition: all 0.1s ease-in-out;
    transition: all 0.1s ease-in-out;     
}    
    
.bordered td, .bordered th {
    border-left: 1px solid #ccc;
    border-top: 1px solid #ccc;
    padding: 10px;
    text-align: left;    
}

.bordered th {
    background-color: #f0f7ff;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));
    background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:    -moz-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:     -ms-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:      -o-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:         linear-gradient(top, #ebf3fc, #dce9f9);
    -webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset; 
    -moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;  
    box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;        
    border-top: none;
    text-shadow: 0 1px 0 rgba(255,255,255,.5); 
}

.bordered td:first-child, .bordered th:first-child {
    border-left: none;
}

.bordered th:first-child {
    -moz-border-radius: 6px 0 0 0;
    -webkit-border-radius: 6px 0 0 0;
    border-radius: 6px 0 0 0;
}

.bordered th:last-child {
    -moz-border-radius: 0 6px 0 0;
    -webkit-border-radius: 0 6px 0 0;
    border-radius: 0 6px 0 0;
}

.bordered th:only-child{
    -moz-border-radius: 6px 6px 0 0;
    -webkit-border-radius: 6px 6px 0 0;
    border-radius: 6px 6px 0 0;
}

.bordered tr:last-child td:first-child {
    -moz-border-radius: 0 0 0 6px;
    -webkit-border-radius: 0 0 0 6px;
    border-radius: 0 0 0 6px;
}

.bordered tr:last-child td:last-child {
    -moz-border-radius: 0 0 6px 0;
    -webkit-border-radius: 0 0 6px 0;
    border-radius: 0 0 6px 0;
}
h1
{
	font-size: 16px;
	margin: 6px 0;
	padding: 0;
}

.main {
    width:80%;
    margin: 0 auto;
    padding: 15px;
  
}
.header {
    border-radius:10px;
}
@media (max-width:720px), @media (max-width:640px)
{
	h1
	{
		font-size:13px;
	}
	.bordered
	{
		font-size:12px;
	}
}

/*----------------------*/

/*.zebra td, .zebra th {
    padding: 10px;
    border-bottom: 1px solid #f2f2f2;    
}

.zebra tbody tr:nth-child(even) {
    background: #f5f5f5;
    -webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset; 
    -moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;  
    box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;        
}

.zebra th {
    text-align: left;
    text-shadow: 0 1px 0 rgba(255,255,255,.5); 
    border-bottom: 1px solid #ccc;
    background-color: #eee;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#f5f5f5), to(#eee));
    background-image: -webkit-linear-gradient(top, #f5f5f5, #eee);
    background-image:    -moz-linear-gradient(top, #f5f5f5, #eee);
    background-image:     -ms-linear-gradient(top, #f5f5f5, #eee);
    background-image:      -o-linear-gradient(top, #f5f5f5, #eee); 
    background-image:         linear-gradient(top, #f5f5f5, #eee);
}

.zebra th:first-child {
    -moz-border-radius: 6px 0 0 0;
    -webkit-border-radius: 6px 0 0 0;
    border-radius: 6px 0 0 0;  
}

.zebra th:last-child {
    -moz-border-radius: 0 6px 0 0;
    -webkit-border-radius: 0 6px 0 0;
    border-radius: 0 6px 0 0;
}

.zebra th:only-child{
    -moz-border-radius: 6px 6px 0 0;
    -webkit-border-radius: 6px 6px 0 0;
    border-radius: 6px 6px 0 0;
}

.zebra tfoot td {
    border-bottom: 0;
    border-top: 1px solid #fff;
    background-color: #f1f1f1;  
}

.zebra tfoot td:first-child {
    -moz-border-radius: 0 0 0 6px;
    -webkit-border-radius: 0 0 0 6px;
    border-radius: 0 0 0 6px;
}

.zebra tfoot td:last-child {
    -moz-border-radius: 0 0 6px 0;
    -webkit-border-radius: 0 0 6px 0;
    border-radius: 0 0 6px 0;
}

.zebra tfoot td:only-child{
    -moz-border-radius: 0 0 6px 6px;
    -webkit-border-radius: 0 0 6px 6px
    border-radius: 0 0 6px 6px
}*/
	</style>
    </head>
    <body>
        
        <div class="main">
        <div style=" color: #000000;width: 100%; margin: 0 auto; margin-bottom: 5px;" class="header">
			
			<div style="float:left;width:100%;text-align:center;">
				<div class="pageTitle">
					<h1>EWN Situation Room</h1>
				</div>
			</div>
			<div style="clear:left;">
			</div>
	</div>
	
        
        <table style="width: 100%"  class="bordered">
            <thead>
                <tr>
                    <th>City</th>
                    <th>URL</th>
                    <!--<th>Username</th>
                    <th>Password</th>-->
                </tr>
            </thead>
            <tbody>
                 <tr>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=adelaide" target="_blank">Adelaide</a></td>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=adelaide" target="_blank"><?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev_dev/index.php?p=adelaide</a></td>
                    <!--<td><a href="index.php?p=adelaide" target="_blank">admin</a></td>
                    <td><a href="index.php?p=adelaide" target="_blank">aeeris@123</a></td>-->
                </tr>
                 
                 <tr>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=brisbane" target="_blank">Brisbane</a></td>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=brisbane" target="_blank"><?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=brisbane</a></td>
                    <!--<td><a href="index.php?p=brisbane" target="_blank">admin</a></td>
                    <td><a href="index.php?p=brisbane" target="_blank">aeeris@123</a></td>-->
                </tr>
                 
                 <tr>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=canberra" target="_blank">Canberra</a></td>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=canberra" target="_blank"><?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=canberra</a></td>
                    <!--<td><a href="index.php?p=canberra" target="_blank">admin</a></td>
                    <td><a href="index.php?p=canberra" target="_blank">aeeris@123</a></td>-->
                </tr>
                 
                 <tr>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=darwin" target="_blank">Darwin</a></td>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=darwin" target="_blank"><?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=darwin</a></td>
                    <!--<td><a href="index.php?p=darwin" target="_blank">admin</a></td>
                    <td><a href="index.php?p=darwin" target="_blank">aeeris@123</a></td>-->
                </tr>
                 
                 <tr>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=hobart" target="_blank">Hobart</a></td>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=hobart" target="_blank"><?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=hobart</a></td>
                   <!-- <td><a href="index.php?p=hobart" target="_blank">admin</a></td>
                    <td><a href="index.php?p=hobart" target="_blank">aeeris@123</a></td>-->
                </tr>
                 
                 
                <tr>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=melbourne" target="_blank">Melbourne</a></td>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=melbourne" target="_blank"><?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=melbourne</a></td>
                   <!-- <td><a href="index.php?p=melbourne" target="_blank">admin</a></td>
                    <td><a href="index.php?p=melbourne" target="_blank">aeeris@123</a></td>-->

                </tr>
                 
                 <tr>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=perth" target="_blank">Perth</a></td>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=perth" target="_blank"><?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=perth</a></td>
                   <!-- <td><a href="index.php?p=perth" target="_blank">admin</a></td>
                    <td><a href="index.php?p=perth" target="_blank">aeeris@123</a></td>-->
                </tr>
                 
                 <tr>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=sydney" target="_blank">Sydney</a></td>
                    <td><a href="<?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=sydney" target="_blank"><?php echo "http://".$_SERVER[HTTP_HOST] ?>/situation_room_dev/index.php?p=sydney</a></td>
                   <!-- <td><a href="index.php?p=sydney" target="_blank">admin</a></td>
                    <td><a href="index.php?p=sydney" target="_blank">aeeris@123</a></td>-->
                </tr>
                 
                 
            </tbody>
        </table>
       </div>
</body>
</html>