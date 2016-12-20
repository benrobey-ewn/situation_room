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
  <title>SituationRoom | Login</title>
  <link rel="icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
  <link rel="shortcut icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
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
   .ui-dialog-titlebar-close {
    visibility: hidden;
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

  <div id="dialog_error" style="display:none;" title="Reached Maximum Login Limit">
    <p><span class="ui-icon ui-icon-alert dialog_error_message" style="float:left; margin:12px 12px 20px 0;"></span></p>
  </div>
</section>

<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src='js/jquery.validate.min.js'></script>
<script type="text/javascript">
  var received_user_id;
  $(function() {
    // dialog confirm 
    $( "#dialog_error" ).dialog({
      autoOpen: false,
      closeOnEscape: false,
      resizable: false,
      draggable: false,
      height: "auto",
      width: 400,
      modal: true,
     // hide: 'fade',
     //  show: 'highlight',
     buttons: {
      "Yes": function() {
        $("#loader").show();
        $.ajax({
          url: 'ajax/destroy_user_session.php',
          dataType: "json",
          type: 'POST',
          data: {
            user_id : received_user_id,
            action  : "delete_user_session" 
          },
          success : function(response){
            $('.errorMsg').html(response.message);
            $("#loader").hide();
            $( "#dialog_error" ).dialog( "close" );
          },
          error : function(response){
           $('.errorMsg').html("Request timed out. There appears to be a network connection issue. Check your connection to the internet or try again later");
           $("#loader").hide();
           $( "#dialog_error" ).dialog( "close" );
         }
       })
      },
      "No": function() {
       $(".errorMsg").html('Please contact "<a href=\'mailto:support@ewn.com.au\'>support@ewn.com.au</a>" if you require additional logins.');
       $( "#dialog_error" ).dialog( "close" );
     }
   }
 });
    
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
            received_user_id = data.user_id;
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
                if(data.error_code == 200){
                  $('.errorMsg').html("");
                  $(".dialog_error_message").parent("p").html(data.msg);
                  $('#dialog_error').dialog('open');
                } else {
                  $('.errorMsg').html(data.msg);
                  $("#loader").hide();
                }
              }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
              $("#loader").hide();
              $('.errorMsg').html("Request timed out. There appears to be a network connection issue. Check your connection to the internet or try again later");
            }
            
          });
        
        
      }
      
    });
  });
</script>

</body>
</html>
