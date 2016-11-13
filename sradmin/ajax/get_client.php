<?php 
require_once '../includes/sit_room.php';
if(isset($_GET['client_id']) && $_GET['client_id']!="")
{
    $client = $db->get_one_client($_GET['client_id']);
    $payment_status = "N/A";
    if(($client['account_status']==1 || $client['account_status']==0) && $client['payment_status'] == 'trial') {
        $expiry_date = strtotime($client['days_allowed']);
        $current_date = strtotime("now");
        if($current_date > $expiry_date) {
            $payment_status = 'Trial (Expired)';
        }
        else {
            $payment_status = 'Trial';
        }  
    } 
    elseif ($client['account_status']==2 && $client['payment_status'] == 'trial')   {
        $payment_status = 'Trial';
    }
    else{
        $payment_status = ucfirst($client['payment_status']);
    }
}
?>
<div class="row">
  <div class="col-md-3">
    <div class=" image preview_image text-center">
        <?php if($client['logo']!=""){ ?>
        <img src="<?php  echo (file_exists("../".$client['logo']))? $client['logo'] : "assets/default.png";  ?>" class="img-circle" alt="Client Picture" width="100" height="100"  />
        <?php } else { ?>
        <img src="assets/default.png" class="img-circle" alt="Client Picture" width="100" height="100"  />
        <?php } ?>
    </div>
</div>
<div class="col-md-9">
    <div class="col-md-5">First Name</div>
    <div class="col-md-7"><?php echo ($client['first_name']!="")? $client['first_name'] : "N/A"; ?></div>
    <hr class="soften">
    <div class="col-md-5">Last Name</div>
    <div class="col-md-7"><?php echo ($client['last_name']!="")? $client['last_name'] : "N/A"; ?></div>
    <hr class="soften">
    <div class="col-md-5">Email</div>
    <div class="col-md-7"><?php echo ($client['email']!="")? $client['email'] : "N/A"; ?></div>
    <hr class="soften">
    <div class="col-md-5">Username</div>
    <div class="col-md-7"><?php echo ($client['username']!="")? $client['username'] : "N/A"; ?></div>
    <hr class="soften">
    <div class="col-md-5">Company</div>
    <div class="col-md-7"><?php echo ($client['company_name']!="")? $client['company_name'] : "N/A"; ?></div>
    <hr class="soften">
    <div class="col-md-5">Phone</div>
    <div class="col-md-7"><?php echo ($client['phone']!="")? $client['phone'] : "N/A"; ?></div>
    <hr class="soften">
    <?php
    $status = '';
    if($client['account_status']=="1")
    {
     $status = '<span class="text-primary">Active</span>';
 }
 elseif($client['account_status']=="2")
 {
     $status = '<span class="text-danger">Expired</span>';
 }
 else
 {
    $status = '<span class="text-danger">Inactive</span>';
}
?>
<div class="col-md-5">Account Status</div>
<div class="col-md-7"><?php echo $status; ?></div>
<hr class="soften">
<div class="col-md-5">Payment Status</div>
<div class="col-md-7"><?php echo $payment_status; ?></div>


    <?php //if($client['payment_status']=="trial") {
       
        $date_ofThe_Year =  date('Y-01-01'); 
        $defaultDate = date('d/m/Y',strtotime($date_ofThe_Year));
        ?>
        <hr class="soften">
        <div class="col-md-5">Expiry Date </div>
        <div class="col-md-7"><?php if($client['days_allowed'] =="0000-00-00") {  echo $defaultDate; } else{ echo date('d/m/Y',strtotime($client['days_allowed']));}  ?></div>
        <?php //} ?>

        <hr class="soften">
        <div class="col-md-5">Concurrent Login</div>
        <div class="col-md-7"><?php if($client['concurrent_login']!=""){ if($client['concurrent_login']=="-1") { echo "Unlimited"; } else { echo $client['concurrent_login']; } } else { echo "N/A"; } ?></div>

    </div>
</div>