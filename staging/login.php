<?php include 'includes/config.php';
if(!empty($_SESSION['user_id'])) {
  header('Location:index.php');
  die;
}
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Situation Room | Login</title>
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
      <h1>Login to Situation Room</h1>
      <div class="errorMsg">
       
      </div>
      <form action="" id="myform" onsubmit="return false;" method="post" >
        <p><input type="text" name="username" value="" class='form-control required' placeholder="Please enter username"></p>
        <p><input type="password" name="password" value="" class='form-control required' placeholder="Please enter password" ></p>
        <!--<p class="remember_me">
          <label>
            <input type="checkbox" name="remember_me" id="remember_me">
            Remember me on this computer
          </label>
        </p>-->
        <p class="submit">
          <input type="hidden" name="redirect" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
          <input type="submit" name="commit" value="Login">
        </p>
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
           url: 'ajax/check_user_login.php',
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
              var filename = "index.php";
              <?php  if(isset($_GET["redirect"]) ){
                if($_GET["redirect"]=="cities.php"){ ?>
                  filename = "cities.php";
                  <?php }
                  if($_GET["redirect"]=="admin"){ ?>
                   filename = "admin/";
                   <?php }
                 } ?>
                 window.location=filename;
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
