<?php 
$page_title = "View Clients";
$menu = "clients";
include 'includes/header.php';

include 'includes/menu.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Clients
  </h1>
  <ol class="breadcrumb">
   <!--  <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li> -->
   <li class="active"><a href="clients.php"><i class="fa fa-users"></i> Clients</a></li>

</ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">


      <div class="box">
        <div class="box-header">
          <div class="pull-right">
             <a href="add_client.php"  title="Add Client" class="btn btn-default btn-sm"><i class="fa fa-plus"></i></a>
             <a href="javascript:;" title="refresh" class="btn btn-default btn-sm refresh_table"><i class="fa fa-refresh"></i></a>
         </div>
     </div><!-- /.box-header -->
     <div class="box-body">
        <div class="message"></div>
        <div class="mailbox-controls pull-left">

          <!-- /.pull-right -->
      </div>
      <div class="clearfix"></div>
      <div class="row">

          <div class="col-sm-2 pull-right"><div class="dataTables_length" >
            <label>
              Clients  : <select name="fiter_by_status" id="fiter_by_status" aria-controls="DataTables_Table_0" class="form-control input-sm">
              <option value="3" selected>All</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
              <option value="2">Expired</option>
          </select>
          <span class="dt_loading blind">
           <span class="fa fa-circle-o-notch fa-spin fa-1x text-primary"></span>
       </span> 
   </label>
</div></div>
</div>
<div class="table-responsive">
  <table class="table table-bordered table-striped data_table_svr " data-source="data/client.php">
    <thead>
      <tr>
         <!--  <th>id</th> -->
         <th class="col-md-1">Logo</th>
         <th>Contact Person </th>
         <th>Company</th>
         <th>Account Status</th>
         <th>Payment Status</th>
         <th>Expiry Date</th>
         <th class="col-md-3">Action</th>
     </tr>
 </thead>
 <tbody></tbody>
 <tfoot>
    <tr>
       <!--  <th>id</th> -->
       <th class="col-md-1">Logo</th>
       <th>Contact Person </th>
       <th>Company</th>
       <th>Account Status</th>
       <th>Payment Status</th>
       <th>Expiry Date</th>
       <th class="col-md-3">Action</th>
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
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><b><span class="client_name"> Client </span></b> Details</h4>
    </div>
    <div class="modal-body">
        <div class="text-center modal_loading blind"><span class="fa fa-circle-o-notch fa-spin fa-2x text-primary"></span> <br>Loading</div>
        <div class="dynamic_content">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="active_session" tabindex="-1" role="dialog" aria-labelledby="active_session_model" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content ">
      <form action="ajax/client.php" method="POST" id="delete_active_session">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="active_session_model">Active Sessions for <b><span class="active_client_name">  </span></b></h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="session_message_div"></div>
              <div class="table-responsive">
                    <table class="table table-bordered table-striped data_table_svr " data-source="data/client_sessions.php?client_id=1&type=1" ><!-- 
                      <thead>
                        <tr>
                          <th class="col-md-1"><input type="checkbox" class="master_check" title="Check all options" ></th>
                          <th>Session ID </th>
                          <th>Last Updated</th>
                          <th class="col-md-2">Action</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th class="col-md-1"><input type="checkbox" class="master_check" title="Check all options" ></th>
                          <th>Session ID </th>
                          <th>Last Updated</th>
                          <th class="col-md-2">Action</th>
                        </tr>
                      </tfoot>
                  -->

                  <thead>
                      <tr>
                        <th class="col-md-1"><input type="checkbox" class="master_check" title="Check all options" ></th>
                        <th class="col-md-2">Client name </th>
                        <th class="col-md-2">Session ID </th>
                        <th class="col-md-2">IP address</th>
                        <th class="col-md-2">Operating System</th>
                        <th class="col-md-3">Browser</th>
                        <th class="col-md-4">Last Updated</th>
                        <th class="col-md-2">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <tr>
                    <th class="col-md-1"><input type="checkbox" class="master_check" title="Check all options" ></th>
                    <th class="col-md-3">Client name </th>
                    <th class="col-md-3">Session ID </th>
                    <th class="col-md-3">IP address</th>
                    <th class="col-md-3">Operating System</th>
                    <th class="col-md-3">Browser</th>
                    <th class="col-md-3">Last Updated</th>
                    <th class="col-md-2">Action</th>
                </tr>
            </tfoot>

        </table>
    </div>
</div>
</div>
</div>
<div class="modal-footer">
    <input type="hidden" name="client_id" value="" id="active_session_client_id">
    <input type="hidden" name="delete" value="delete_multiple_sessions">
    <button type="submit" class="btn btn-primary   ladda-button confirm_delete" disabled  data-style="slide-down"><span class="ladda-label" name="delete_client_session" value="delete_client_session">Delete </span><span class="ladda-spinner"></span></button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
</form>
</div>
</div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    $(document).ready(function() {
      $(document).on("click","table > tbody > tr > td ",function(event){
        console.log($(this));
        if(event.target.nodeName == 'A') return;

        $(this).siblings("td").find('a.fetch_profile').trigger("click");
    });

      $(document).on("click",".active_session_manager",function(){
        load_link = $(this).data('href');
        client_id = load_link.split("=");
        tables[1].fnReloadAjax(load_link);
        $("#active_session_client_id").val(client_id[1]);
        $(".active_client_name").html($(this).parents("td").siblings('td:nth-child(3)').html());
    });

      $(document).on("click",".fetch_profile",function(event){
         $('.dynamic_content').html("");
         $(".client_name").html("Client");
         $(".modal_loading").removeClass('blind');
         obj  = $(this);
         $.ajax({
           url: obj.data('href'),
           type: 'GET',
           data: "",
           success : function(data){
             $('.dynamic_content').html(data);
             $(".client_name").html(obj.parents("td").siblings('td:nth-child(3)').html());
             $(".modal_loading").addClass('blind');
         }
     });

     });

      $(document).on("click",".delete_current_session",function(event){
        event.preventDefault();
        if(confirm("Are you sure you want to destroy selected session?"))
        {
          $.ajax({
            url: $(this).attr('href'),
            type: 'GET',
            dataType: 'JSON',
            data: "",
            success: function(data){
              console.log(data);
              if(data['status']=="true"){
                get_message("success",data['data']);
            }
            else {
                get_message("error",data['data']);
            }

            tables[1].api().ajax.reload("",true);
        }
    });

      }
  });


      $("#delete_active_session").on("submit",function(event){
         event.preventDefault();
         obj = $(this);
         if(confirm("Are you sure you want to destroy selected session?")){
           loaders.start();
           ajax_submit("delete_active_session",function(response){
              console.log(response);
              if(response["status"]=="true"){
                 get_message("success",response['data']); 
             }  else  {
                 get_message("error",response['data']);
             }
             loaders.stop();
             tables[1].api().ajax.reload("",true);
             $(".master_check").iCheck('uncheck');
             $(".confirm_delete").attr('disabled', 'true');
         });
       }
   });


      $(".refresh_table").on('click',function(event) {
        $(this).find("i").addClass('fa-spin');
        tables[0].api().ajax.reload("",true);
        $(document).ajaxStop(function(){
          $(this).find("i").removeClass('fa-spin');
      });  
    });


      $("#fiter_by_status").on('change',function(event) {
        $(document).ajaxStart(function() {
          $(".dt_loading").removeClass('blind');
      }).ajaxStop(function() {
       $(".dt_loading").addClass('blind');
   });
      tables[0].fnReloadAjax("data/client.php?status="+$(this).val());
  });

      $(document).on('click', ".delete_client", function(event) {
        event.preventDefault();
        obj = $(this);
        if(confirm("Are you sure, you want to delete this client permanently?"))
        {
          $.get(obj.attr('href'), function(data) {
            console.log(data);
            if(data=="true"){
                    //msg = get_message("success","Client has been deleted");
                    get_message("success","Client has been deleted");
                    tables[0].api().ajax.reload();
                    
                }
                else {
                    //msg = get_message("error","Please Try Again ");
                    get_message("error","Please Try Again ");
                }
                    //$(".message").html(msg);
                    /*setTimeout(function(){
                      $(".message").find(".alert").hide("normal",function(){
                        $(this).remove();
                      });
                  },1300);*/
              });
      }
  });

      jQuery(document).on("click",".change_status",function(event){
        event.preventDefault();
        obj = $(this);
        $.get($(this).attr('href'), function(data) {
                 //msg = get_message("success","Account Status Change");
                 get_message("success","Account Status Change");
              //  $(".message").html(msg);
              if(obj.text()=="Active")
              {
                obj.text("Inactive");
                obj.attr("href","ajax/client.php?account_status=change&status=1&id="+obj.data('id'));
                obj.addClass("text-red").removeClass("text-green");
            }
            else
            {
                obj.text("Active");
                obj.attr("href","ajax/client.php?account_status=change&status=0&id="+obj.data('id'));
                obj.addClass("text-green").removeClass("text-red");
            }
               /* setTimeout(function(){
                      $(".message").find(".alert").hide("normal",function(){
                        $(this).remove();
                      });
                  },1300);*/
              });
    })



  });
</script>
