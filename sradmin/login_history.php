<?php 
$page_title = "Client Login History";
$menu = "clients";
include 'includes/header.php';

include 'includes/menu.php';
$client_id = "";
$where = "";
$uniqueC = "";
$table = "login_history";
if(!empty($_GET['client_id'])){
  $client_id =  $_GET['client_id'];
  if ($client_id=="all") {
    $where = " GROUP BY client_id";
    $uniqueC = "DISTINCT(client_id)";
  } else {
    $where = "WHERE client_id = '".$client_id."'"; 
    
  }
}
  $page = $db->pagination($table,$where,"login_history.php?client_id=".$client_id,$_GET['page'],20,$uniqueC);
  $sql = "SELECT `$table`.*,first_name,username,company_name FROM `$table` INNER JOIN `clients` ON `clients`.`id` = client_id  $where ORDER BY login_date DESC LIMIT {$page['start']}, {$page['limit']}";
  $result = mysql_query($sql);
  $countRes = 0;
  if(mysql_num_rows($result) > 0){
    $countRes = mysql_num_rows($result);
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
                      <option value="all" <?php echo ($client_id=="all") ? 'selected' : ""; ?>  >Get all clients at least one login history</option>
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
                <?php if(empty($client_id) || $client_id=="all"){ ?>
                 <th class="col-md-2">Client Name</th>
                 <?php } ?>
                 <th class="col-md-2">Session Start Time</th>
                 <th class="col-md-2">Session End Time</th>
                 <th class="col-md-3">IP address</th>
                 <th class="col-md-3">Operating System</th>
                 <th class="col-md-3">Browser</th>
               </tr>
             </thead>
             <tbody>
              <?php if($countRes > 0){
                while ($row = mysql_fetch_assoc($result)) {
                  $logouttime = "-";
                  if($row['logout_date']!="" && $row['logout_date']!="0000-00-00 00:00:00"){
                    $logouttime = date('d/m/Y H:i:s',strtotime($row['logout_date']));
                  }
                  echo '<tr>';
                  if(empty($client_id) || $client_id=="all"){
                //  echo '<td>'.$clientList[$row["client_id"]].'</td>';
                  echo '<td>'.$row["first_name"].'</td>';
                  
                  }
                  //date('d/m/Y H:i:s',strtotime($row['login_date'])); 
                  echo '<td>'.date('d/m/Y H:i:s',strtotime($row['login_date'])).'</td>
                  <td>'. $logouttime .'</td>
                  <td>'.$row['ip_address'].'</td>
                  <td>'.$row['os'].'</td>
                  <td>'.$row['browser'].'</td>
                </tr>'; 
              }
            } else {
              echo '<tr> <td colspan="6">No Login History Available</td></tr>'; 
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
