<?php  if(isset($_REQUEST['email']))  {
	                   $email=$_REQUEST['email']; 
                       }
		        $share="http://fxbytes.com/new/ewn2.0/common/alert.php";
                        $message = '<html><body>';
			//$message .= '<img src="http://fxbytes.com/new/floral/images/logo.png" alt="Floral" />';
			$message .= '<table rules="all" cellpadding="10">';
			$message .= "<tr style='background: #eee;'><td><strong>Share Link:</strong> </td><td>" . $share . "</td></tr>";
			$message .= "</table>";
			$message .= "</body></html>";
			
			//   CHANGE THE BELOW VARIABLES TO YOUR NEEDS
                        //$to = 'gupta.harsh@fxbytes.com';
			$from='admin@ewn.com.au';
			$to=$email;
			$subject = 'Ewn alert ';
			$headers = "From: " .  $from . "\r\n";
			//$headers .= "Reply-To: ". strip_tags($email) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if (mail($to, $subject, $message, $headers)) {
             
	      $output=array('status'=>'true','data'=>'Your message has been sent.');
            } else {
               $output=array('status'=>'true','data'=>'There was a problem sending the email.');
            }
  
  
 
 
 
 echo json_encode($output);
?>
  
