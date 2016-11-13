<?php 
$page_title = (isset($_GET['edit']) && $_GET['edit']!="")? "Edit Client" : "Add Client";
$menu = "clients";
include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css">
<?php
if(isset($_GET['edit']) && $_GET['edit']!="")
{
  $client = $db->get_one_client($_GET['edit']);
}
include 'includes/menu.php';
?>
<style type="text/css">
  .datepicker table tr td.active.active {
    pointer-events: none !important;
    cursor: default !important;
  }
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><?php echo $page_title ;  ?></h1>
    <ol class="breadcrumb">
      <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li> -->
      <li><a href="clients.php"> <i class="fa fa-users"></i> Clients</a></li>
      <li class="active"><?php echo $page_title ;  ?></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">

          <!-- form start -->
          <form role="form" method="POST" id="client_add" action="ajax/client.php" enctype='multipart/form-data' data-parsley-validate>
            <div class="box-body">
              <h4>User Details</h4>
              <hr>
              <div class="form-group">
                <label for="fist_name">Contact First Name</label>
                <input type="text" class="form-control" id="fist_name" name="fist_name" value="<?php echo (isset($_GET['edit']) && $client['first_name']!="")? $client['first_name'] : ""; ?>"  required placeholder="Enter First Name">
              </div>
              <div class="form-group">
                <label for="last_name">Contact Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo (isset($_GET['edit']) && $client['last_name']!="")? $client['last_name'] : ""; ?>"  placeholder="Enter Last Name">
              </div>
              <div class="form-group">
                <label for="user_name">Username</label>
                <input type="text" class="form-control" id="user_name" name="username" value="<?php echo (isset($_GET['edit']) && $client['username']!="")? $client['username'] : ""; ?>" required placeholder="Enter Username">
                <span class="text-danger blind user_exists">This username already exists</span>
              </div>
              <div class="form-group">
                <label for="email_address">Email Address</label>
                <input type="email" class="form-control" id="email_address" name="email" value="<?php echo (isset($_GET['edit']) && $client['email']!="")? $client['email'] : ""; ?>"  placeholder="Enter  Email Address">
                <span class="text-danger blind email_exists">This email is already in use</span>
              </div>
              <?php if(!isset($_GET['edit'])){ ?>
                    <!-- <div class="form-group">
                      <label for="pass">Password</label>
                      <input type="password" class="form-control" id="pass" name="pass" required data-parsley-minlength="6" placeholder="*******">
                    </div> -->

                    <div class="form-group">
                      <label for="re_pass">Password</label>
                      <input type="password" class="form-control" id="re_pass" name="password" required data-parsley-minlength="6" placeholder="*******"  >
                    </div>
                    <?php } ?>

                    <div class="form-group">
                      <label for="phone">Contact Phone</label>
                      <input type="text" class="form-control" id="phone" data-parsley-maxlength="12" name="phone" value="<?php echo (isset($_GET['edit']) && $client['phone']!="")? $client['phone'] : ""; ?>" placeholder="Enter Phone No">
                    </div>

                    <div class="form-group">
                      <label for="company_name">Company Name</label>
                      <input type="text" class="form-control" id="company_name" value="<?php echo (isset($_GET['edit']) && $client['company_name']!="")? $client['company_name'] : ""; ?>" name="company_name" placeholder="Enter Company Name">
                    </div>

                    <div class="form-group">
                     <label for="company_name">Account Status</label>
                     <div class="checkbox icheck">
                      <label>
                        <input type="radio" name="account_status" value="1" <?php echo (isset($_GET['edit']) && $client['account_status']=="1")? "checked"  : ""; ?>> &nbsp; Active
                      </label>
                      <label>
                        <input type="radio" name="account_status" value="0" <?php if(isset($_GET['edit']) && $client['account_status']=="0"){ echo "checked"; } ?> <?php  if(!isset($_GET['edit']) ){ echo "checked"; } ?> > &nbsp; Inactive
                      </label><br/>
                      <label>
                        <input type="radio" name="account_status" value="2" <?php echo (isset($_GET['edit']) && $client['account_status']=="2")? "checked"  : ""; ?>> &nbsp; Expired
                      </label>
                    </div>
                  </div>


                  <div class="form-group">
                   <label for="company_name">Payment Status</label>
                   <div class="checkbox icheck">
                    <label>
                      <input type="radio" name="payment_status" value="free" <?php echo (isset($_GET['edit']) && $client['payment_status']=="free")? "checked"  : ""; ?>> &nbsp; Free
                    </label>
                    <label>
                      <input type="radio" name="payment_status" value="trial" <?php echo (isset($_GET['edit']) && $client['payment_status']=="trial")? "checked"  : ""; ?>> &nbsp; Trial
                    </label><br/>
                    <label>
                      <input type="radio" name="payment_status" value="paid" <?php if(isset($_GET['edit']) && $client['payment_status']=="paid"){ echo "checked"; } ?> <?php  if(!isset($_GET['edit']) ){ echo "checked"; } ?> > &nbsp; Paid
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label for="days_allowed">Expiry Date</label>
                  <?php
                  $date_ofThe_Year =  date('Y-01-01');
                  if(isset($_GET['edit']))
                  {
                    //echo $client['days_allowed']; 01/01/2016
                    if($client['days_allowed'] == '0000-00-00')
                    {
                      $days_allowed = date('d/m/Y',strtotime($date_ofThe_Year));
                    }
                    else
                    {
                     $days_allowed = date('d/m/Y',strtotime($client['days_allowed']));
                   }
                 }
                 ?>
                 <input type="text" class="form-control datepicker" id="days_allowed" name="days_allowed" value="<?php echo (isset($_GET['edit']) && $client['days_allowed']!="")? $days_allowed : date('d/m/Y'); ?>"  name="days_allowed" placeholder="Trial Expiry Date" readonly>
                 <!-- <span class="text-muted">Set -1 for Unlimited Access</span> -->
               </div>


               <div class="form-group">
                <label for="concurrent_login">Concurrent Login </label>
                <input type="text" data-parsley-type="number" class="form-control" id="concurrent_login" name="concurrent_login" value="<?php echo (isset($_GET['edit']) && $client['concurrent_login']!="")? $client['concurrent_login'] : ""; ?>"  name="concurrent_login" placeholder="Set Limit to Client Login" value="" >
                <span class="text-muted">Set -1 for unlimited concurrent login</span>
              </div>
              
              <!-- <div class="form-group">
                       <label for="">Authorised NBN user</label>
                      <div class="checkbox icheck">
                        <label>
                          <input type="radio" name="is_authorised_nbn_user" value="1" class="hide_show" <?php echo (isset($_GET['edit']) && $client['is_authorised_nbn_user']=="1")? "checked"  : ""; ?>> &nbsp; Yes
                        </label>
                        <label>
                          <input type="radio" name="is_authorised_nbn_user" value="0" <?php if(isset($_GET['edit']) && $client['is_authorised_nbn_user']=="0"){ echo "checked"; } ?> <?php  if(!isset($_GET['edit']) ){ echo "checked"; } ?> > &nbsp; No
                        </label>
                      </div>
                    </div>
                    
                    <div class="form-group is_authorised_nbn_user <?php echo (isset($_GET['edit']) && $client['is_authorised_nbn_user']!="1")? "blind" : ""; ?>  <?php  if(!isset($_GET['edit']) ){ echo "blind"; }  ?>">
                       <label for="company_name">Nbn User Type</label>
                      <div class="checkbox icheck">
                        <label>
                          <input type="radio" name="nbn_user_type" value="full_access" class="" <?php echo (isset($_GET['edit']) && $client['nbn_user_type']=="full_access")? "checked"  : ""; ?>> &nbsp;  Full Edit Access
                        </label>
                        <label>
                          <input type="radio" name="nbn_user_type" value="readonly" <?php if(isset($_GET['edit']) && $client['nbn_user_type']=="readonly"){ echo "checked"; } ?> <?php  if(!isset($_GET['edit']) ){ echo "checked"; } ?> > &nbsp; Read Only Access
                        </label>
                      </div>
                    </div> -->

              <div class="col-md-12">
                <div class="form-group">    
                  <label for="get_image">Company Logo:  </label>
                  <div class=" image preview_image">
                    <?php if(isset($_GET['edit']) && $client['logo']!=""){ ?>
                      <img src="<?php echo (file_exists($client['logo']))? $client['logo'] : "assets/default.png"; ?>" class="img-thumbnail" alt="Client Picture" width="204" height="136"  />
                      <?php } else { ?>
                        <img src="assets/default.png" class="img-thumbnail" alt="Client Picture" width="204" height="136"  />
                        <?php } ?>
                      </div>
                      <input type="file" name="pic" id="file_upload" value=""  class="uploadifive-button">
                      <input type="hidden"  name="get_image" value=""  class="get_image">
                      <span class="text-danger"></span>
                    </div>
                  </div>

                  <div class="box-footer">
                    <?php if(isset($_GET['edit']) && $client['id']!=""){ ?>
                      <input type="hidden" name="id" value="<?php echo $client['id'] ?>">
                      <input type="hidden" name="client_edit" value="client_edit">
                      <button type="submit" class="btn btn-primary   ladda-button"  data-style="slide-down"><span class="ladda-label" name="client_edit" value="client_edit">Update </span><span class="ladda-spinner"></span></button>
                      <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-key"></i> Change Password</a>
                      <?php } else { ?>
                        <input type="hidden" name="client_add" value="client_add">
                        <button type="submit" class="btn btn-primary   ladda-button"  data-style="slide-down"><span class="ladda-label" name="client_add" value="client_add">Add </span><span class="ladda-spinner"></span></button>
                        <?php } ?>
                      </div>
                      <div class="message"></div>
                    </form>
                  </div><!-- /.box -->

                </div>
              </div>   <!-- /.row -->
            </section><!-- /.content -->
          </div><!-- /.content-wrapper -->
          <?php if(isset($_GET['edit']) && $client['id']!=""){ ?>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form role="form" action="ajax/client.php" method="POST" data-parsley-validate id="change_password">
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
                            <label for="pass">Password</label>
                            <input type="password" class="form-control" id="pass" name="pass" required data-parsley-minlength="6" placeholder="*******">
                          </div>

                          <div class="form-group">
                            <label for="re_pass"> Re-enter Password</label>
                            <input type="password" class="form-control" id="re_pass" name="password" data-parsley-equalto="#pass" required data-parsley-minlength="6" placeholder="*******"  >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <input type="hidden" name="change_password" value="change_password">
                    <input type="hidden" name="client_id" value="<?php echo $client['id'] ?>">
                    <button type="submit" class="btn btn-primary   ladda-button"  data-style="slide-down"><span class="ladda-label" name="client_add" value="client_add"> Update </span><span class="ladda-spinner"></span></button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php } ?>
          <?php include  'includes/footer.php'; ?>
          <script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>

          <!--  page based js  -->

          <script>
            dont_submit =  user_name_exists = 0;
            $(document).ready(function() {
             
              
              
              $("#client_add").on('submit', function(event) {

                obj = $(this);
                event.preventDefault();
                if(dont_submit == 0 && user_name_exists==0)
                {
                  loaders.start();
                  ajax_submit("client_add",function(response){
                    if(response["status"]=="true"){
                      get_message("success",response['data']); 
                      setTimeout(function(){
                        location.href='clients.php';
                      },2000);
                    }  else  {
                      get_message("error",response['data']);

                    }
                    loaders.stop();
                  });
                }
              });

              $("#email_address").on("blur",function(){
               if($(this).val().indexOf("@") > -1)
               {
                 $.ajax({
                   url: $("form#client_add").attr("action"),
                   type: "POST",
                   data: {
                    "email_check" : "email_check",
                    "email_address" : $(this).val()
                    <?php if(isset($_GET['edit']) && $_GET['edit']!=""){ ?>
                      , "client_id" : <?php echo $_GET['edit']  ?>
                      <?php } ?>
                    },
                    success : function(data){
                     if(data==1) {
                       dont_submit = 1;
                       $(".email_exists").removeClass("blind");
                     }  else  {
                      dont_submit = 0;
                      $(".email_exists").addClass("blind");
                    }
                  }
                });
               }
             });

              $("#user_name").on("blur",function(){
               if($(this).val() != ""){
                $.ajax({
                 url: $("form#client_add").attr("action"),
                 type: "POST",
                 data: {
                  "user_name_check" : "user_name_check",
                  "user_name" : $(this).val()
                  <?php if(isset($_GET['edit']) && $_GET['edit']!=""){ ?>
                    , "client_id" : <?php echo $_GET['edit']  ?>
                    <?php } ?>
                  },
                  success : function(data){
                   if(data==1) {
                     user_name_exists = 1;
                     $(".user_exists").removeClass("blind");
                   }  else  {
                    user_name_exists = 0;
                    $(".user_exists").addClass("blind");
                  }
                }
              });
              }
            });

              <?php if(isset($_GET['edit']) && $client['id']!=""){ ?>
                $("#change_password").on('submit', function(event) {
                  obj = $(this);
                  event.preventDefault();
                  loaders.start();
                  ajax_submit("change_password",function(response){
                    if(response["status"]=="true"){
                      get_message("success",response['data']); 
                    }  else  {
                      get_message("error",response['data']); 
                    }
                    $("#myModal").modal("hide");
                    $("#change_password")[0].reset();
                    obj.find("input.parsley-success").removeClass('parsley-success');
                    loaders.stop();
                  });
                });
                <?php } ?>  
              });
            </script>
            
            <script>
              $(function() {
                var temp_value = $( ".datepicker").val();
                $( ".datepicker" ).datepicker({
                  singleDatePicker: true,
                  showDropdowns: true,
                  format: 'dd/mm/yyyy'
                }).bind("keydown", function (e) {
                  if (e.keyCode == 13 && $(this).prop("readonly") == "readonly") {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                  }
                })
                
               /* $( ".datepicker").on("keyup",function(event){
                  if($(this).val()!=""){
                    temp_value = $(this).val();
                  }
                  console.log(event.keyCode + " =  temp_value =  " + temp_value);
                  if(event.keyCode === 13){
                    if(temp_value!= ""){
                      $(this).val(temp_value);
                    }
                 }
               })*/
             });

           </script>

