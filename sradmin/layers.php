<?php 
 $page_title = "View Layers";
 $menu = "layers";
 include 'includes/header.php';

 include 'includes/menu.php';
 ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Layers
          </h1>
          <ol class="breadcrumb">
             <li class="active"><a href="layers.php"><i class="fa fa-users"></i> Layers</a></li>
          </ol>
        </section>

        <!-- Main content -->
     <section class="content">
          <div class="row">
            <div class="col-xs-12">
               

              <div class="box">
                <div class="box-header">
                  <div class="pull-right">
                    
                    <a href="javascript:;" title="refresh" class="btn btn-default btn-sm refresh_table"><i class="fa fa-refresh"></i></a>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="message"></div>
                  <div class="mailbox-controls pull-left">
                    
                    <!-- /.pull-right -->
                  </div>
                  <div class="clearfix"></div>
                    
                   <div class="table-responsive">
                    <table class="table table-bordered table-striped data_table_svr " data-source="data/layer.php">
                      <thead>
                        <tr>
                          <th class="col-md-2">Layer Type </th>
                          <th class="col-md-3">Layer Name</th>
                          <th class="col-md-1">Data Count</th>
                          <th class="col-md-1">Status</th>
                          <th class="col-md-1">Action</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                           <th class="col-md-2">Layer Type </th>
                          <th class="col-md-3">Layer Name</th>
                          <th class="col-md-1">Data Count</th>
                          <th class="col-md-1">Status</th>
                          <th class="col-md-1">Action</th>
                        </tr>
                      </tfoot>
                    </table>
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
    $(".refresh_table").on('click',function(event) {
            $(this).find("i").addClass('fa-spin');
              tables[0].api().ajax.reload("",true);
              $(document).ajaxStop(function(){
                $(this).find("i").removeClass('fa-spin');
              });  
          });
  });
</script>