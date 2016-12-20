<?php include 'includes/config.php';
if(!empty($_SESSION['user_id'])) {
  header('Location:index.php');
  die;
}
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SituationRoom | Login</title>
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
       <?php if(isset($_GET['max']) && $_GET['max']!="") { echo "Your session has expired. Please login again"; } ?>
     </div>
     <form action="" id="myform" onsubmit="return false;" method="post" >
      <p><input type="text" name="username" value="" class='form-control required' placeholder="Please enter username"></p>
      <p><input type="password" name="password" value="" class='form-control required' placeholder="Please enter password" ></p>
      <p class="submit"><input type="submit" name="commit" value="Login"></p>
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
            //console.log(data.status);
            //console.log(data.query);
            if(data.status=='true') {
              $('.errorMsg').html('');
              window.location='<?php if(isset($_GET["redirect"]) && $_GET["redirect"]=="cities.php") { echo "cities.php"; } else { echo "index.php"; } ?>';
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
