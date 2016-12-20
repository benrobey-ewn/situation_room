<?php 
 $page_title = "View Layer Data";
 $menu = "layers";
 include 'includes/header.php';

 include 'includes/menu.php';
 $layer = $db->get_one_layer($_GET['layer_id']);
 ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Layer Data for - <span class="layer_name_span"><?php echo $layer['layer_name']; ?></span>
          </h1>
      <ol class="breadcrumb">
         <li class="active"><a href="layer_details.php"><i class="fa fa-users"></i> Layer Data</a></li>
       </ol>
        </section>
        <section class="content" style="min-height:0px">
          <div class="row">
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">
                
                <!-- form start -->
                <form role="form" method="POST" id="update_layer_name" action="ajax/layers.php" enctype='multipart/form-data' data-parsley-validate>
                  <div class="box-body">
                    <div class="form-group">
                        <label for="layer_name">Layer Name</label>
                        <input type="text" class="form-control" id="layer_name" name="layer_name" value="<?php echo $layer['layer_name']; ?>"  required placeholder="Enter Layer Name">
                    </div>
                    <?php if(file_exists("../images/layer_markers/".$layer['placemarker_icon'])){ ?>
                        <div class="form-group">    
                            <label for="get_image"> layer Marker:  </label>
                            <div class=" image preview_image">
                                <img src="<?php echo "../images/layer_markers/".$layer['placemarker_icon']; ?>" class="img-thumbnail" alt="Layer Marker" width="30" height="30"  />
                            </div>
                            <input type="file" name="pic" id="file_upload" value=""  class="uploadifive-button">
                            <input type="hidden" id="" name="placemarker_icon" value="<?php echo $layer['placemarker_icon']; ?>"  class="get_image">
                            <span class="text-danger"></span>
                            <span class="muted">Keep small recommended size 11 X 11 </span>
                        </div>
                        <div class="form-group">    
                            <label for="get_image"> Popup layer Marker:  </label>
                            <div class=" image preview_image">
                                <img src="<?php echo "../images/popup_layer_markers/".$layer['placemarker_icon']; ?>" class="img-thumbnail" alt="Layer Marker" width="50" height="50"  />
                            </div>
                            <input type="file" name="pic" id="file_upload" value=""  class="uploadifive-button">
                            <input type="hidden" id="" name="popup_placemarker_icon" value=""  class="get_image">
                            <span class="text-danger"></span>
                            <span class="muted">For report and external window - Keep small recommended size 35 X 35 </span>
                        </div>
                    <?php } ?>

                    <div class="box-footer">
                      <input type="hidden" name="layer_id" value="<?php echo $_GET['layer_id'] ?>">
                      <input type="hidden" name="layer_name_update" value="layer_name_update">
                      <button type="submit" class="btn btn-primary   ladda-button"  data-style="slide-down"><span class="ladda-label" name="layer_name_update" value="layer_name_update">Update </span><span class="ladda-spinner"></span></button>
                    </div>
                </form>
              </div><!-- /.box -->
 
            </div>
          </div>   <!-- /.row -->
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
                    <table class="table table-bordered table-striped data_table_svr " data-source="data/layer_datas.php?layer_id=<?php echo $_GET['layer_id'] ?>">
                      <thead>
                        <tr>
                          <th class="col-md-1">#</th>
                          <th class="col-md-3">Placemarker Name</th>
                          <th class="col-md-1">Latitute</th>
                          <th class="col-md-1">Longitude</th>
                          <th class="col-md-1">Action</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                           <th class="col-md-1"># </th>
                          <th class="col-md-3">Placemarker Name</th>
                          <th class="col-md-1">Latitute</th>
                          <th class="col-md-1">Longitude</th>
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

 
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form action="ajax/layers.php" method="POST" id="update_layer_datas" role="form">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel"><b><span class="client_name"> Layer  </span></b> Edit</h4>
            </div>
            <div class="modal-body">
                <div class="text-center modal_loading blind"><span class="fa fa-circle-o-notch fa-spin fa-2x text-primary"></span> <br>Loading</div>
                <div class="dynamic_content">
                </div>
            </div>
            <div class="modal-footer">
             <input type="hidden" name="layer_data_update" value="layer_data_update">
             <input type="hidden" name="layer_id" value="<?php echo $_GET['layer_id'] ?>">
             <button type="submit" class="btn btn-primary   ladda-button"   data-style="slide-down"><span class="ladda-label" name="update_layer_data_values" value="update_layer_data_values">Update </span><span class="ladda-spinner"></span></button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </form>
      </div>
    </div>


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

     // get layer datas form 
     $(document).on("click",".get_layer_form",function(event){
       event.preventDefault();
       $(".modal_loading").show();
       $(".dynamic_content").html("");
       $.ajax({
         url: $(this).data('href'),
         type: 'GET',
         data: "",
         success : function(response_html){
           $(".modal_loading").hide();
           $(".dynamic_content").html(response_html);
         }
       })
     });

     // get placemarker preview content 
        $(document).on("keyup","#placemarker_description",function(event){
            $(".placemarker_description").html($(this).val())
        });
    // for layer details update 

      $("#update_layer_datas").on('submit', function(event) {
         obj = $(this);
         event.preventDefault();
         loaders.start();
          ajax_submit("update_layer_datas",function(response){
              if(response["status"]=="true"){ 
                  get_message("success",response['data']); 
                  tables[0].api().ajax.reload("",false);  
                  setTimeout(function(){
                    $("#myModal").modal("hide"); 
                  },1200);
              }  else  { 
                  get_message("error",response['data']);
              }
            loaders.stop();
          });
    });
    


     // for layer name update 
    
     $("#update_layer_name").on('submit', function(event) {
       obj = $(this);
       event.preventDefault();
          loaders.start();
          ajax_submit("update_layer_name",function(response){
           
              if(response["status"]=="true"){ 
                  get_message("success",response['data']); 
                  $(".layer_name_span").html($("#layer_name").val())
              }  else  { 
                  get_message("error",response['data']);
              }
            loaders.stop();
          });
    });

     // for layer data delete 

     $(document).on("click",".delete_layer_data",function(event){
         event.preventDefault();
         if(confirm("Are you sure delete This Layer Data")){
             $.ajax({
               url: $(this).attr('href'),
               type: 'GET',
               dataType : 'JSON',
               data : ''
             })
             .done(function(response) {
                if(response["status"]=="true"){ 
                   get_message("success",response['data']); 
                   tables[0].api().ajax.reload("",false);  
                }  else  { 
                    get_message("error",response['data']);
                }
             }) 
             
         }          
     });


  });
</script>