<?php require_once 'includes/sit_room.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo sit_room::$main_title; ?> | Admin Login</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link rel="icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
        <link rel="shortcut icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
    <link href="<?php echo sit_room::$base_url; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo sit_room::$base_url; ?>/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo sit_room::$base_url; ?>/assets/dist/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?php echo sit_room::$base_url; ?>/assets/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
   
    <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/ladda/css/ladda.css">
    <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/Parsley/dist/parsley.css">
    <link href="<?php echo sit_room::$base_url; ?>/assets/dist/css/modify.css" rel="stylesheet" type="text/css" />

    <!-- <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/gritters/css/jquery.gritter.css" type="text/css"> -->
     <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/noty-2.3.5/animate.css" type="text/css">

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>

  
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
         <b><?php //echo sit_room::$main_title; ?>  Admin Login </b>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="ajax/login.php" id="login_submit" method="post" data-parsley-validate>
          <div class="message"></div>
          <div class="form-group has-feedback">
            <input name="username" value="<?php echo (isset($_COOKIE['username']) && $_COOKIE['username']!="")? $_COOKIE['username'] : ""; ?>" type="text" class="form-control" placeholder="Please enter username"  required/>
            <span class="glyphicon glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" value="<?php echo (isset($_COOKIE['password']) && $_COOKIE['password']!="")? $_COOKIE['password'] : ""; ?>" class="form-control" placeholder="Please enter password"   required/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember_me" value="1" <?php echo (isset($_COOKIE['remember_me']) && $_COOKIE['remember_me']==1)? "checked" : ""; ?>> &nbsp; Remember Me
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="hidden" name="check_login" value="check_login">
              <button type="submit" class="btn btn-primary btn-block btn-flat ladda-button"  data-style="slide-down"><span class="ladda-label">Sign in</span><span class="ladda-spinner"></span></button>
              
             
            </div><!-- /.col -->
          </div>
        </form>

         
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/iCheck/icheck.min.js" type="text/javascript"></script>

    <!-- <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/jquery_validate/jquery.validate.min.js"></script> -->
    <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/Parsley/dist/parsley.min.js"></script>

    <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/ladda/js/spin.js"></script> 
    <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/ladda/js/ladda.js"></script> 
    
     <!-- <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/gritters/js/jquery.gritter.min.js" type="text/javascript"></script> -->
     <script src="<?php echo sit_room::$base_url; ?>/assets/plugins/noty-2.3.5/packaged/jquery.noty.packaged.js" type="text/javascript"></script>

    <script src="<?php echo sit_room::$base_url; ?>/assets/common.js"></script> 

    <script>
       $(document).ready(function() {
          $("#login_submit").on('submit', function(event) {
              obj = $(this);
                event.preventDefault();
                loaders.start();
                ajax_submit("login_submit",function(response){
                    if(response["status"]=="true")  {
                         location.href='clients.php';
                      }  else  {
                        get_message("error",response['data']);
                      }
                      loaders.stop(); 
                });
              });
       });
    </script>
  </body>
</html>