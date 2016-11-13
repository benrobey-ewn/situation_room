      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <!-- <div class="user-panel">
            <div class="pull-left image">
              <?php if($admin['profile_pic']!=""){ ?>
                                    <img src="<?php echo (file_exists($admin['profile_pic']))? $admin['profile_pic'] : "assets/default.png"; ?>" class="img-circle" alt="Admin Picture" width="204" height="136"  />
                                <?php } else { ?>
                                    <img src="assets/default.png" class="img-circle" alt="Admin Picture" width="204" height="136"  />
                                <?php } ?>
            </div>
            <div class="pull-left info">
              <h5><?php echo ($_SESSION['admin_name']!="")? $_SESSION['admin_name']:""; ?></h5>
            </div>
          </div> -->
          <!-- search form -->
          <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form> -->
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <!-- <li class="header">MAIN NAVIGATION</li> -->
            <li class="treeview">
              <!-- <a href="#">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a> -->
            </li>
             <li class="treeview <?php echo ( $menu=="clients")? "active" : "" ?> ">
              <a href="#">
                <i class="fa fa-users"></i> <span>Client's</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li <?php echo ( $page_title=="View Clients" || $page_title == "Assign Layers")? 'class="active"' : ''; ?>><a href="clients.php"><i class="fa fa-eye"></i> View Client's</a></li>
                <li <?php echo ( $page_title=="Client Login History")? 'class="active"' : ''; ?>><a href="login_history.php"><i class="fa fa-clock-o"></i> Login History</a></li>
                <li <?php echo ( $page_title=="View Clients Active Session")? 'class="active"' : ''; ?>><a href="sessions.php"><i class="fa fa-power-off"></i> Active Session</a></li>
                <li <?php echo ( $page_title=="Edit Client" || $page_title=="Add Client")? 'class="active"' : ''; ?>><a href="add_client.php"><i class="fa fa-user"></i> <?php echo ( $page_title=="Edit Client" || $page_title=="Add Client")? $page_title : "Add Client"; ?></a></li>
              </ul>
            </li>
            <!-- <li class="treeview <?php // echo ( $menu=="layers")? "active" : "" ?> ">
              <a href="layers.php">
                <i class="fa fa-database"></i> <span>Layers</span>
              </a>
            </li> -->

            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->