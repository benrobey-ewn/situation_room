<?php 
$page_title = "Client Login History";
$menu = "clients";
include 'includes/header.php';

include 'includes/menu.php';
$client_id = "";
if(!empty($_GET['client_id'])){
  $client_id =  $_GET['client_id'];
  $table = "login_history";
  $where = "WHERE client_id = '".$client_id."'"; 
  $page = $db->pagination($table,$where,"login_history.php?client_id=".$client_id,$_GET['page'],20);
  $sql = "SELECT * FROM `$table` $where LIMIT {$page['start']}, {$page['limit']}";
  $result = mysql_query($sql);
  $countRes = 0;
  if(mysql_num_rows($result) > 0){
    $countRes = mysql_num_rows($result);
}
}
$select_clients = $db->clientDropDown($client_id);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Clients
  </h1>
  <ol class="breadcrumb">
      <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li> -->
      <li><a href="clients.php"> <i class="fa fa-users"></i> Clients</a></li>
      <li class="active">Login History</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <!-- /.box-header -->
          <div class="box-body">
            <div class="message"></div>
            <div class="mailbox-controls pull-left">
              <!-- /.pull-right -->
          </div>
          <div class="clearfix"></div>
          <div class="row">
              <div class="col-md-12 ">
                 <div class="col-sm-4"></div>
                 <div class="col-sm-4">
                   <form action="#" method="GET" id="user_history_form">
                      <div class="form-group" >
                        <label>Select Client  : </label>
                        <select name="client_id" id="client_id" class="form-control">
                          <option value="" disbled >Please Select a client</option>
                          <?php echo $select_clients; ?>
                      </select>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <hr>
  <div class="table-responsive">
    <table class="table table-bordered table-striped " >
      <thead>
        <tr>
           <th class="col-md-3">Date</th>
           <th class="col-md-3">Time</th>
       </tr>
   </thead>
   <tbody>
      <?php if($countRes > 0){
        while ($row = mysql_fetch_assoc($result)) {
          $dateTime= explode(" ",$row['login_date']);
          echo '<tr>
          <td>
            '.'-->>>'.$dateTime[0].'
        </td>
        <td>
            '.'-->>>'.$dateTime[1].'
        </td>
    </tr>'; 
}
} else {
  echo '<tr> <td>No Login History Available</td></tr>'; 
} ?>
</tbody>
</table>
<?php if($page['pagination']!="") { ?>
<div class="pull-right">
    <?php echo $page['pagination']; ?>
</div>
<?php } ?>
</div>
</div><!-- /.box-body -->
</div><!-- /.box -->
</div><!-- /.col -->
</div><!-- /.row -->
</section>
</div><!-- /.content-wrapper -->





<?php include 'includes/footer.php'; ?>

<script>
  $(document).ready(function() {
    $("#client_id").on("change",function(){
      $("#user_history_form").submit();
  })
});
</script>
