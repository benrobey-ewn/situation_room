<?php require_once 'sit_room.php';  
if(!isset($_SESSION['admin_id']) && $_SESSION['admin_id']=="")
{
  header("Location:login.php");
} 
$admin = $db->get_one_client($_SESSION['admin_id']);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo sit_room::$main_title; ?> | <?php echo $page_title ?></title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link rel="icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
  <link rel="shortcut icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
  <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/chosen_v1.4.2/chosen.min.css">
  <!-- Bootstrap 3.3.4 -->
  <link href="<?php echo sit_room::$base_url; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Font Awesome Icons -->
  
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Ionicons -->
  <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="<?php echo sit_room::$base_url; ?>/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo sit_room::$base_url; ?>/assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

  <link href="<?php echo sit_room::$base_url; ?>/assets/dist/css/custom.css" rel="stylesheet" type="text/css" />

  <!-- iCheck -->
  <link href="<?php echo sit_room::$base_url; ?>/assets/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
  
  <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/ladda/css/ladda.css">
  <!-- form validation  -->
  <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/Parsley/dist/parsley.css">
  
  <!-- upload images -->
  <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/uploadifive/uploadifive.css">

  <link href="<?php echo sit_room::$base_url; ?>/assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo sit_room::$base_url; ?>/assets/dist/css/modify.css" rel="stylesheet" type="text/css" />
  <!-- <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/gritters/css/jquery.gritter.css" type="text/css"> -->
  <link rel="stylesheet" href="<?php echo sit_room::$base_url; ?>/assets/plugins/noty-2.3.5/animate.css" type="text/css">
  

</head>
<body class="skin-blue sidebar-mini">
  <div class="wrapper">
    
    <header class="main-header">
      <!-- Logo -->
      <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">SR</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><?php echo sit_room::$main_title; ?></b></span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
              <!-- <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    inner menu: contains the actual data
                    <ul class="menu">
                      <li>start message
                        <a href="#">
                          <div class="pull-left">
                            <img src="assets/dist/default.png" class="img-circle" alt="User Image"/>
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>end message
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="assets/dist/img/user3-128x128.jpg" class="img-circle" alt="user image"/>
                          </div>
                          <h4>
                            AdminLTE Design Team
                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="assets/dist/img/user4-128x128.jpg" class="img-circle" alt="user image"/>
                          </div>
                          <h4>
                            Developers
                            <small><i class="fa fa-clock-o"></i> Today</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="assets/dist/img/user3-128x128.jpg" class="img-circle" alt="user image"/>
                          </div>
                          <h4>
                            Sales Department
                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="assets/dist/img/user4-128x128.jpg" class="img-circle" alt="user image"/>
                          </div>
                          <h4>
                            Reviewers
                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li> -->
              <!-- Notifications: style can be found in dropdown.less -->
              <!-- <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">10</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 10 notifications</li>
                  <li>
                    inner menu: contains the actual data
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-red"></i> 5 new members joined
                        </a>
                      </li>
              
                      <li>
                        <a href="#">
                          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-user text-red"></i> You changed your username
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li> -->
              <!-- Tasks: style can be found in dropdown.less -->
              <!-- <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <span class="label label-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                    inner menu: contains the actual data
                    <ul class="menu">
                      <li>Task item
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li>end task item
                      <li>Task item
                        <a href="#">
                          <h3>
                            Create a nice theme
                            <small class="pull-right">40%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">40% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li>end task item
                      <li>Task item
                        <a href="#">
                          <h3>
                            Some task I need to do
                            <small class="pull-right">60%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">60% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li>end task item
                      <li>Task item
                        <a href="#">
                          <h3>
                            Make beautiful transitions
                            <small class="pull-right">80%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">80% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li>end task item
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li> -->
              <!-- User Account: style can be found in dropdown.less -->
              <li class="user user-menu">
              <a href="../" target="_blank" title="Open Situation Room in a new tab">Open Situation Room</a>
              </li>
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php if($admin['logo']!=""){ ?>
                  <img src="<?php echo (file_exists($admin['logo']))? $admin['logo'] : "assets/default.png"; ?>" class="user-image" alt="Admin Picture" width="204" height="136"  />
                  <?php } else { ?>
                  <img src="assets/default.png" class="user-image" alt="Admin Picture" width="204" height="136"  />
                  <?php } ?>
                  <span class="hidden-xs"><?php echo ($_SESSION['admin_name']!="")? $_SESSION['admin_name']:""; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  
                  <li class="user-header">
                    <?php if($admin['logo']!=""){ ?>
                    <img src="<?php echo (file_exists($admin['logo']))? $admin['logo'] : "assets/default.png"; ?>" class="img-circle" alt="Admin Picture" width="204" height="136"  />
                    <?php } else { ?>
                    <img src="assets/default.png" class="img-circle" alt="Admin Picture" width="204" height="136"  />
                    <?php } ?>
                    <p>
                      <?php echo ($_SESSION['admin_name']!="")? $_SESSION['admin_name']:""; ?> <!-- - Web Developer -->
                    </p>
                  </li>
                  
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="admin.php" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="signout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              
              
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->