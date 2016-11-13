<?php 
$page_title = "View Clients Active Session";
$menu = "clients";
include 'includes/header.php';
include 'includes/menu.php';
$select_clients = $db->clientDropDown();
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Clients Active Session
		</h1>
		<ol class="breadcrumb">
			<!--  <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li> -->
			<li class="active"><a href="clients.php"><i class="fa fa-users"></i> Clients</a></li>
			
		</ol>
	</section>
	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				
				<div class="box">
					<form action="ajax/client.php" method="POST" id="delete_active_session">
						<div class="box-header">
						<div class="col-md-4 col-sm-10 col-xs-12">
						  <div class="pull-left">
						  	<label for="client_id" class="form-label">Select Client :</label>
						  </div>
							<div class="pull-left" style="margin-left:10px;">
								<select name="client_id" id="client_id" class="form-control">
									<option value="" disbled >Please Select a client</option>
									<?php echo $select_clients; ?>
								</select>
							</div>
						</div>
						<br>
						<br>
								
								<div class="clearfix"></div>
							<div class="pull-left">
								<button type="submit" class="btn btn-primary   ladda-button confirm_delete" disabled  data-style="slide-down"><span class="ladda-label" name="delete_client_session" value="delete_client_session">Delete </span><span class="ladda-spinner"></span></button>
							</div>
							<div class="pull-right">
								
								<a href="javascript:;" title="refresh" class="btn btn-default btn-sm refresh_table"><i class="fa fa-refresh"></i></a>
							</div>
							
								
						</div><!-- /.box-header -->
						
						<div class="box-body">
							<div class="mailbox-controls pull-left">
								
								<!-- /.pull-right -->
							</div>
							<div class="clearfix"></div>
							
							<div class="table-responsive">
								<table class="table table-bordered table-striped data_table_svr " data-source="data/client_sessions.php?type=1" >
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
						</div><!-- /.box-body -->
						<input type="hidden" name="delete" value="delete_multiple_sessions">
					</form>
				</div><!-- /.box -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</section>
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
						tables[0].api().ajax.reload("",true);
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
					tables[0].api().ajax.reload("",true);
					$(".master_check").iCheck('uncheck');
					$(".confirm_delete").attr('disabled', 'true');
				});
			}
		});
		
		$("#client_id").on("change",function(){
			var obj = $(this).val();
			if(obj!=""){
				tables[0].fnReloadAjax("data/client_sessions.php?type=1&client_id="+obj);
			} else {
				tables[0].fnReloadAjax("data/client_sessions.php?type=1");
			}
		})

	});
</script>