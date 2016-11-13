<?php 
 $page_title = "Admin Profile ";
 $menu = "Admin";
 include 'includes/header.php';
 include 'includes/menu.php';
 ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> <?php echo $page_title ;  ?> </h1>
          <ol class="breadcrumb">
            <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li> -->
            <li><a href="#">Admin</a></li>
          </ol>
        </section>

        <section class="content">
            <div class="col-md-12">
              <!-- Custom Tabs -->
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1" data-toggle="tab">Overview</a></li>
                  <li><a href="#tab_2" data-toggle="tab">Edit Account</a></li>
                
                  
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <div class="contents">

                        <div class="col-md-6 text-center">
                             <div class="form-group">    
                                <div class=" image preview_image">
                                <?php if($admin['logo']!=""){ ?>
                                    <img src="<?php echo (file_exists($admin['logo']))? $admin['logo'] : "assets/default.png"; ?>" class="img-thumbnail" id="main_thumbnail" alt="Admin Picture" width="204" height="136"  />
                                <?php } else { ?>
                                    <img src="assets/default.png" class="img-thumbnail" alt="Admin Picture" width="204" height="136"  />
                                <?php } ?>
                                  </div>
                                  <div class="full_name"><h4><?php echo $admin['first_name']." " . $admin['last_name']  ?></h4></div>
                                
                        </div>
                            
                        </div>
                        <div class="col-md-6">
                             <div class="row">
                                 <div class="col-md-3  col-sm-6 ">First Name : </div>
                                 <div class="col-md-6 col-sm-6 " id="first_name_on_update"><?php echo $admin['first_name']; ?></div>
                             </div>
                             <br>
                             <div class="row">
                                 <div class="col-md-3  col-sm-3 ">Last Name : </div>
                                 <div class="col-md-6 col-sm-6 " id="last_name_on_update"><?php echo $admin['last_name'] ?></div>
                             </div>
                             <br>
                             <div class="row">
                                 <div class="col-md-3">Username : </div>
                                 <div class="col-md-6" id="username_on_update"><?php echo $admin['username'] ?></div>
                             </div>
                             <br>
                             <div class="row">
                                 <div class="col-md-3">Email Address : </div>
                                 <div class="col-md-6" id="email_on_update"><?php echo $admin['email'] ?></div>
                             </div>
                             <br>
                             <div class="row">
                                 <div class="col-md-3">Phone No : </div>
                                 <div class="col-md-6" id="phone_no_on_update"><?php echo $admin['phone'] ?></div>
                             </div>
                        </div>
                    </div>
                   </div><!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                    <form role="form" method="POST" id="admin_edit" action="ajax/admin.php" enctype='multipart/form-data' data-parsley-validate>
                        <div class="form-group">
                          <label for="fist_name">First Name</label>
                          <input type="text" class="form-control" id="fist_name" name="fist_name" value="<?php echo ($admin['first_name']!="")? $admin['first_name'] : ""; ?>"  required placeholder="Enter First Name">
                        </div>
                        <div class="form-group">
                          <label for="last_name">Last Name</label>
                          <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo ( $admin['last_name']!="")? $admin['last_name'] : ""; ?>"  placeholder="Enter Last Name">
                        </div>
                         <div class="form-group">
                          <label for="user_name">Username</label>
                          <input type="text" class="form-control" id="user_name" name="username" value="<?php echo ( $admin['username']!="")? $admin['username'] : ""; ?>" disabled required placeholder="Enter Username">
                        </div>
                        <div class="form-group">
                          <label for="email_address">Email Address</label>
                          <input type="email" class="form-control" id="email_address" name="email" value="<?php echo ( $admin['email']!="")? $admin['email'] : ""; ?>" required placeholder="Enter  Email Address">
                        </div>
                         <div class="form-group">
                          <label for="phone_no">Phone</label>
                          <input type="text" class="form-control" id="phone_no" name="phone_no" value="<?php echo ( $admin['phone']!="")? $admin['phone'] : ""; ?>" data-parsley-maxlength="12" data-parsley-type="number" placeholder="Enter Phone No">
                       </div>
                       <div class="form-group">    
                                <label for="get_image"> Picture:  </label>
                                <div class=" image preview_image">
                                <?php if($admin['logo']!=""){ ?>
                                    <img src="<?php echo (file_exists($admin['logo']))? $admin['logo'] : "assets/default.png"; ?>" class="img-thumbnail" alt="Admin Picture" width="204" height="136"  />
                                <?php } else { ?>
                                    <img src="assets/default.png" class="img-thumbnail" alt="Admin Picture" width="204" height="136"  />
                                <?php } ?>
                                  </div>
                                <input type="file" name="pic" id="file_upload" value=""  class="uploadifive-button">
                                <input type="hidden" id="get_image" name="get_image" value=""  class="get_image">
                                <span class="text-danger"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="box-footer">
                        <input type="hidden" name="admin_edit" value="admin_edit">
                            <button type="submit" class="btn btn-primary   ladda-button"  data-style="slide-down"><span class="ladda-label" name="client_add" value="client_add"> Update </span><span class="ladda-spinner"></span></button> 
                           
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-key"></i> Change Password</a>
                            
                        </div>
                    <div class="message"></div>
                   </form>
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- nav-tabs-custom -->
            </div><!-- /.col -->
          </section>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" action="ajax/admin.php" method="POST" data-parsley-validate id="change_password">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Change Password </h4>
      </div>
      <div class="modal-body">
          <div class="text-center modal_loading blind"><span class="fa fa-circle-o-notch fa-spin fa-2x text-primary"></span> <br>Loading</div>
          <div class="dynamic_content">
            <div class="row">
              <div class="col-md-12">
                 <div class="form-group">
                    <label for="old_pass">Old Password</label>
                    <input type="password" class="form-control" id="old_pass" name="old_pass" required data-parsley-minlength="6" placeholder="*******">
                  </div>
                  
                 <div class="form-group">
                    <label for="pass">New Password</label>
                    <input type="password" class="form-control" id="pass" name="pass" required data-parsley-minlength="6" placeholder="*******">
                  </div>

                  <div class="form-group">
                    <label for="re_pass"> Re-enter New Password</label>
                    <input type="password" class="form-control" id="re_pass" name="password" data-parsley-equalto="#pass" required data-parsley-minlength="6" placeholder="*******"  >
                  </div>
            </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="change_password" value="change_password">
        <button type="submit" class="btn btn-primary   ladda-button"  data-style="slide-down"><span class="ladda-label" name="client_add" value="client_add"> Update </span><span class="ladda-spinner"></span></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<!--  page based js  -->
<script>
  $(document).ready(function() {
    
    $("#admin_edit").on('submit', function(event) {
     
      obj = $(this);
      event.preventDefault();
      loaders.start();
      ajax_submit("admin_edit",function(response){
          if(response["status"]=="true"){
              get_message("success",response['data']); 
              $("#phone_no_on_update").html($("#phone_no").val());
              $("#first_name_on_update").html($("#fist_name").val());
              $("#last_name_on_update").html($("#last_name").val());
              $("#username_on_update").html($("#user_name").val());
              $("#email_on_update").html($("#email_address").val());
              $("#main_thumbnail").attr("src",$("#get_image").val());
          }  else  {
              get_message("error",response['data']);
          }
       loaders.stop();
      });
    });

    $("#change_password").on('submit', function(event) {
      obj = $(this);
      event.preventDefault();
      loaders.start();
      ajax_submit("change_password",function(response){
          if(response["status"]=="true"){
              get_message("success",response['data']); 
              $("#myModal").modal("hide");
              $("#change_password")[0].reset();
             obj.find("input.parsley-success").removeClass('parsley-success');
          }  else  {
              get_message("error",response['data']); 
             obj.find("input.parsley-success").addClass('parsley-error'); 
          }
         loaders.stop();
      });
    });

    
    
  });


</script>