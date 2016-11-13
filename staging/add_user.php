<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   <title>Situation Room | Add User</title>
  <link rel="stylesheet" href="css/login.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
   <style>
		#loader {
			height: 100%;
			opacity: 0.6;
			background:url(images/loading2.gif) no-repeat scroll 50% 50% rgba(255, 255, 255, 0.8);
                        left: 0;
			position: absolute;
			top: 0;
			width: 100%;
			z-index: 1000;
		}
   </style>
</head>
<body>
    <div id="loader" style="display:none;"></div>
  <section class="container">
    <div class="login">
      <h1>Add User</h1>
      <div class="errorMsg">
         
      </div>
      <div class="successMsg">
         
      </div>
      <form action="" id="myform" onsubmit="return false;" method="post" >
        <p><input type="text" name="username" id="username" class='form-control required' placeholder="Please enter username"></p>
        <p><input type="password" name="password" id="password" value="" class='form-control required' placeholder="Please enter password" ></p>
        <!--<p class="remember_me">
          <label>
            <input type="checkbox" name="remember_me" id="remember_me">
            Remember me on this computer
          </label>
        </p>-->
        <p class="submit"><input type="submit" name="commit" value="Submit"></p>
      </form>
    </div>

   
  </section>
  
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script src='js/jquery.validate.min.js'></script>
<script type="text/javascript">
    $(function() {
        $( "#myform" ).validate({
            
            submitHandler: function (form) {
				  $("#loader").show();
				   var formData = new FormData(form);
                                                    $.ajax({
							url: 'ajax/add_user.php',
                                                        dataType: "json",
							type: 'POST',
							data: formData,
							contentType: false,
							processData: false,
							success: function (data) {
                                                            $("#loader").hide();
                                                            console.log(data.status);
                                                            if(data.status=='true') {
                                                                $('.errorMsg').html('');
                                                                //window.location='index.php';
                                                                $('.successMsg').html(data.msg);
                                                                $('#username').val('');
                                                                $('#password').val('');
                                                            } else {
                                                                $('.errorMsg').html(data.msg);
                                                            }
							},
							error: function (XMLHttpRequest, textStatus, errorThrown) {
								$("#loader").hide();
								$('.errorMsg').html('Something went wrong.Try again after sometimes.');
								
							}
					
						});
				  
				   
              }
            
            });
    });
</script>

</body>
</html>
