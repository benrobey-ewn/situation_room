<?php 
 $page_title = "Assign Layers";
 $menu = "clients";
 include 'includes/header.php';
  if(isset($_GET['client_id']) && $_GET['client_id']!="")
 {
    $client = $db->get_one_client($_GET['client_id']);
 }
 include 'includes/menu.php';
     $layers = $db->get_client_layer($_GET['client_id']);
 ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           <?php echo $page_title . " for " .  $client['first_name'] . " " .$client['last_name'];  ?>
          </h1>
          <ol class="breadcrumb">
            <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li> -->
            <li><a href="clients.php"> <i class="fa fa-users"></i>  Clients</a></li>
            <li class="active"><a href="#"><?php echo $page_title  ?></a></li>
          </ol>
        </section>

     <!-- Main content -->
     <section class="content">
         <div class="row">
            <div class="col-xs-12">
               
              <div class="message"></div>
              <div class="box">
                <form role="form" method="POST" id="assign_layer_form" action="ajax/client.php" >
                <div class="box-header">
                  <div class="pull-right">
                    <button type="submit" class="btn btn-primary   ladda-button"  data-style="slide-down"><span class="ladda-label" name="client_add" value="client_add"> Save Assigned Layers</span><span class="ladda-spinner"></span></button>
                   
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="mailbox-controls pull-left">
                    
                    <!-- /.pull-right -->
                  </div>
                  <div class="clearfix"></div>
                   <div class="table-responsive">
                    <table class="table table-bordered table-striped data_table_svr " data-source="data/assigned_layers.php?client_id=<?php echo $_GET['client_id'] ?>">
                      <thead>
                        <tr>
                          <th class="col-md-1">#</th>
                          <th>Layer Name</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th class="col-md-1">#</th>
                           <th>Layer Name</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div><!-- /.box-body -->
                <input type="hidden" name="layer_ids" id="layer_id_values" value="<?php echo (!empty($layers) && $layers!=0) ?  implode(",",$layers) : "" ; ?>" >
                <input type="hidden" name="client_id"  value="<?php echo $_GET['client_id'] ?>" >
                <input type="hidden" name="assign_layer" value="assign_layer"> 
                </form>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div>

     </section>

    </div>

<?php include 'includes/footer.php'; ?>

<script>
  $(document).ready(function() {

      $("#assign_layer_form").on('submit', function(event) {
              obj = $(this);
              event.preventDefault();
              loaders.start();
              ajax_submit("assign_layer_form",function(response){
                  if(response["status"]=="true"){
                      /*msg = get_message("success",response['data']); 
                      $(".message").html(msg);*/
                      get_message("success",response['data'])
                  }  else  {
                      /*msg = get_message("error",response['data']);
                        $(".message").html(msg); */
                        get_message("error",response['data'])
                  }
                      tables[0].api().ajax.reload();
                      /*setTimeout(function(){  
                         $(".message").find(".alert").hide("normal",function(){
                          $(this).remove();
                        });
                    },1200);*/
              loaders.stop();
              });
      });
    $(document).on("ifChanged",".layer_ids",function(){
        layers = new Array();
        var index = "";
        if($("#layer_id_values").val()!="")
        {
         assigned_values = $("#layer_id_values").val();
         assigned_values = assigned_values.split(",");
        }
        else{
          assigned_values = new Array();
        }  
        if($(this).is(':checked'))
        {
          assigned_values.push($(this).val())
        }
        else
        {
           index = assigned_values.indexOf($(this).val());
           if (index > -1) {
                assigned_values.splice(index, 1);
            }
        }
        new_assigned_values = assigned_values.join(",");
        console.log(new_assigned_values);
        $("#layer_id_values").val(new_assigned_values);
    })
  });
</script>
