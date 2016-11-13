<!DOCTYPE html>
<html>
    <head>
    <title>Situation Room</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <style type="text/css">
     
     #loading-image {
	/*background-color: #333;
	width: 55px;
	height: 55px;*/
	position: fixed;
	top: 106px;
	left: 100px;
	z-index: 1;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px; /* future proofing */
	-khtml-border-radius: 10px;
}


</style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false&v=3.exp&libraries=weather"></script>
    <!--<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false"></script>-->
    <script src="../js/jquery-1.7.2.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript">
     $(document).ready(function(){
	$( "#share" ).validate({
	   rules: {
		email: {
			required: true,
		}
	 },submitHandler: function (form) {
	  $('#loading-image').show();
	  var email=$('#email').val();
	  
	  
	       $.ajax({
				   type: "POST",
				   dataType: "json",
				   timeout: 60000,
				   url:'sendmail.php',
				   data: {email:email},
				   error: function(x, t, m) {
				      if(t==="timeout") {
				   	  //myAlert('connection error');
				      } else {
					  //myAlert(t);
				      }
				      $('#loading-image').hide();
				   },
				   success: function(result) {
				    $('#loading-image').hide();
				    console.log(result);
				    $('#response-ok').html('mail send sucessfully');
				    //alert(result);
					
				   }
			    });
		//
		return ;
	  }
       });
    });
    
    </script>
   
    </head>

    <body>
    <h2>Share</h2>
    <form class="form-horizontal" id="share" method="POST">
       <input type="text" id="email" class="form-control required email" placeholder="Email" name="email" value=""> <br/> <br/>
       <button type="submit" class="btn btn-primary">Submit</button>
     </form>
    <div id="response-ok"></div>
    <div id="response-error"></div>
    <div id="loading-image" style="display: none">
	<img src="../images/loading.gif" alt="Loading..." />
    </div>
               
</body>
</html>