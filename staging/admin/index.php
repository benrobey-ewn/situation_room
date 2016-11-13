<?php 
require_once "../includes/config.php";
require_once 'function.php';
if(isset($_SESSION['username'])){
	if($_SESSION['username']!="admin"){
		header("Location:../login.php");
	}
} else {
	header("Location:../login.php?redirect=admin");
}
$topicData = getTopicsDropDown();
 
 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Situation Room | Alerts | Admin </title>
 	<link rel="icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
   <link rel="shortcut icon" type="image/ico" href="http://www.ewn.com.au/favicon.ico" />
	<link href="../css/bootstrap3.min.css" rel="stylesheet">
	<link href="../css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="dt/dataTables.bootstrap.min.css">
 </head>
 <body>
 
 <div class="container">
 	<div class="row">
 	   <div class="pull-left">
 	   	<h4 class="lead"><img src="../images/ewn-Logo.jpg" alt="EWN Alerts Manager" width="150"> EWN Alerts Manager</h4>
 	   </div>
 	   <div class="pull-right">
 	   	<h4 class="lead"><a href="../" target="_blank" style="line-height:1.8" >Open Situation Room </a></h4>
 	   </div>
 	   <div class="clearfix"></div>
 	   <hr style="margin-bottom:10px !important; margin-top:0px !important; " />
 		<div class="col-md-12">
 			<table id="ewn_alerts" class="table  table-bordered table-hover" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Topic</th>
                <th>Subject</th>
                <th>Created Date</th>
                <th>Expire Date</th>
                <th>Actions</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>id</th>
                <th>Topic</th>
                <th>Subject</th>
                <th class="col-md-2">Created Date</th>
                <th class="col-md-2">Expire Date</th>
                 <th class="col-md-1">Action</th>
            </tr>
        </tfoot>
        <tbody></tbody>
    </table>
 		</div>
 	</div>
 </div>	
 </body>
    <script type="text/javascript" src="../js/jquery-1.7.2.js"></script>
     <script src="../js/libs/bootstrap.min.js"></script>
     <script src="dt/jquery.dataTables.min.js"></script>
     <script src="dt/dataTables.bootstrap.min.js"></script>
 </html>
 <!-- modal for  updation -->
 <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
       <form method="POST" action="core.php" id="update_alert_form" class="update_alert_form" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Alerts</h4>
      </div>
      <div class="modal-body">
			  <div class="form-group">
			    <label for="exampleInputEmail1">Topic </label>
			    <select name="topic_id" id="topic_id" class="form-control">
			    	<?php echo $topicData; ?>
			    </select>	
			  </div>
			  <div class="form-group">
			    <label for="exampleInputPassword1">Subject</label>
			    <input type="text" name="subject" id="subject" class="form-control" id="exampleInputPassword1" placeholder="Subject">
			  </div>
      </div>
      <input type="hidden" name="alert_id" id="alert_id" value="">
      <input type="hidden" name="save_changes" value="save_changes">
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="save_changes"  name="save_changes" value="save_changes">Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
	</form>
  </div>
</div>
 <?php // page based script ?>
<script>
    var datatable;
    var alertErrorMessage = "Something Went wrong, please try again";
    var alertSuccessMessage = "";
	$(document).ready(function() {
		datatable = $('#ewn_alerts').DataTable({
              "bPaginate": true,
              "bLengthChange": true,
              "bFilter": true,
              "bSort": true,
              "bInfo": true,
              "bAutoWidth": false,
              "bProcessing": true,
              "bServerSide": true,
              "sAjaxSource": "data/alert.php",
               "oLanguage": {
                     "sLoadingRecords": '<span class="text-center"><span class="icon-refresh icon-spin icon-1x text-primary"></span> Loading</span>',
                     "sProcessing": '<span class="icon-refresh icon-spin icon-1x text-primary"></span> Loading',
                  },
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 5 ] }],
                "bSorting" : [[0,'DESC']], 
                 
            });
    
    //  to delete the warning 
    
    $(document).on("click",".confirm_delete_alert",function(){
    	if(confirm("Confirm delete this alert")){
    		   alert_id = $(this).data("id");
           $.ajax({
              url: 'core.php?delete_alert='+alert_id,
              dataType: 'JSON',
           }).done(function(res) {
              if(res.message!=""){
                alert(res.message);
              }
              if(res.status=="true"){
                 datatable.draw();
              }
           }).fail(function(res) {
             alert(alertErrorMessage);
           })
            
           
    	}
    });
    
    //  to active/inactive the warning 
    
    $(document).on("click",".inactive_warning",function(){
          alert_status = $(this).data("status");
          if(alert_status==0){
            msg = "show";
          } else{
            msg = "hide";
          }
      
      if(confirm("Confirm "+msg+" this alert")){
           alert_id = $(this).data("id");
           $.ajax({
              url: 'core.php?active_inactive_warning='+alert_id+"&status="+alert_status,
              dataType: 'JSON',
           }).done(function(res) {
              if(res.message!=""){
                alert(res.message);
              }
              if(res.status=="true"){
                 datatable.draw();
              }
           }).fail(function(res) {
             alert(alertErrorMessage);
           })
            
           
      }
    });
    
    
    
    $(document).on("click",'.edit_warning',function(event){
    	  event.preventDefault();
    	  alert_id = $(this).data("id");
    	  $.ajax({
    	  	url: 'core.php?get_alert='+alert_id,
    	  	type: 'GET',
    	  	dataType: 'JSON'
    	  }).done(function(res) {
    	  	$("#alert_id").val(alert_id);
    	  	$("#topic_id").val(res['topic_id']);
			$("#subject").val(res['subject']);
			$("#myModal").modal("show");
    	  	
    	  }).fail(function(res) {
    	  	alert(alertErrorMessage);
    	  })
    })
    
    $(document).on("submit","form#update_alert_form",function(event){
    	$("#save_changes").attr("disabled",true).html("Loading....");
    	event.preventDefault();
    	var obj = $(this);
    	$.ajax({
    		url: obj.attr('action'),
    		type: 'POST',
    		dataType: 'JSON',
    		data: obj.serialize(),
    	}).done(function(res) {
   			if(res.message!=""){
          alert(res.message);
        }
   			$("#save_changes").attr("disabled",false).html("Update")
    			$("#myModal").modal("hide");
    			if(res.status=="true"){
    				 datatable.draw(false);
    			}
    	}).fail(function() {
    		 alert(alertErrorMessage);
    		 $("#myModal").modal("hide");
    		 $("#save_changes").attr("disabled",false).html("Update")
    	});
    })
	});
</script>


<style>

</style>