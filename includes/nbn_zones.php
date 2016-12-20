<!-- NBN Zone selection floating window -->
<div id="nbn_zones_div" class="row legend-main noleftright">
	<div class="col-xs-12 col-sm-12 col-md-12 nbn-legend-content">
		<div class="row legend-content-header">
			<div class="col-xs-1 col-sm-1 col-md-1 noleftrightpad anchor-class">
				<i class="fa fa-arrows legend-content-header-icons"></i>
			</div>
			<div class="col-xs-9 col-sm-9 col-md-9 legend-content-header-value text-center noleftrightpad">
				<h1 style="padding-top:4px !important;">Zone selected</h1>
			</div>
			<div class="col-xs-1 col-sm-1 col-md-1 noleftrightpad pointer-icon">
				<span class="rotate glyphicon glyphicon-chevron-down legend-content-header-icons" aria-hidden="false" id="nbn_selected_zones"></span>
			</div>
		</div>
		<div class="row legend-content-body" id="nbn-content">
			<div class="legend-content-body-value">
					<div class="accordion_container col-md-12" id="selected_zones">
					</div>
			</div>
			<button class="col-md-5 btn" id="open_alert_form">Change Zone</button>
			<button class="col-md-5 btn" id="cancel_alert">Cancel</button>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<!-- NBN zone selection floating window -->

<!-- NBN zone list floating window-->
<div id="navigation-nbn" class="row legend-main noleftright">
	<div class="col-xs-12 col-sm-12 col-md-12 nbn-legend-content">
		<div class="row legend-content-header">
			<div class="col-xs-1 col-sm-1 col-md-1 noleftrightpad anchor-class">
				<i class="fa fa-arrows legend-content-header-icons"></i>
			</div>
			<div class="col-xs-9 col-sm-9 col-md-9 legend-content-header-value text-center noleftrightpad">
				<h1 style="text-align: center;padding-top: 10px; line-height: 10px" id="site_layer_title-nbn" class="site_layer_title">NBN Zones</h1>
			</div>
			<div class="col-xs-1 col-sm-1 col-md-1 noleftrightpad pointer-icon">
				<span class="rotate glyphicon glyphicon-chevron-down legend-content-header-icons" aria-hidden="false" id="nbn_zones"></span>
			</div>
		</div>
		<div class="row legend-content-body" id="nbn-content">
			<div class="legend-content-body-value">
					<ul class="list-group" id="nbn_zones_list">
						<?php 
						$sqlst = "SELECT * FROM nbn_alerts  group by nbn_alert_id";
						$rsst = mysql_query($sqlst);
						$numst = mysql_num_rows($rsst);
						if($numst>0){
							while ( $row=mysql_fetch_object($rsst)) {
								$alertcount= getAlertCount($row->nbn_alert_id);

								 		if($alertcount>0){
								 ?>			

								 <li class="alert_thread" id="<?php echo $row->nbn_alert_id ?>"><span class="mainplusminus" onclick="toggleModeSub(<?php echo $row->nbn_alert_id;?>);" id="mainplusminus_<?php echo $row->nbn_alert_id;?>">+</span><a href="javascript:void(0);" class="alert_subject_link" onclick="selAlerts(<?php echo $row->nbn_alert_id;?>);" id="<?php echo $row->nbn_alert_id;?>"><?php echo $row->nbn_alert_title; ?></a>
								 	<input type="hidden" id="nbn-subject-<?php echo $row->nbn_alert_id;?>" name="subject" value="<?php echo $row->nbn_alert_title;?>">
								 		<input type="hidden" id="nbn-description-<?php echo $row->nbn_alert_id;?>" name="description" value="<?php echo $row->nbn_alert_desc;?>">
								 </li>
										<div id="nbn_alerts_<?php echo $row->nbn_alert_id ; ?>" style="display:none;" class="alert_thread_body">
											<?php 
												$sqlsr = "SELECT * FROM nbn_layers_info WHERE alert_id='".$row->nbn_alert_id."'";
												$rssr = mysql_query($sqlsr);
												$numsr = mysql_num_rows($rssr); 
											if($numsr>0){
												$zone_black='';
												$zone_red='';
												$zone_amber='';
												while ( $rowalert=mysql_fetch_object($rssr)) { 

														$zone_color=$rowalert->nbn_color;
														$nbn_suburbsnames=getSuburbName($rowalert->nbn_postcode);
														if($zone_color=='Black'){
															$zone_black .='<li id="'.$rowalert->nbn_postcode.'" postcode="'.$rowalert->nbn_postcode.'">
														<div class="accordion_head_post">
															'.$rowalert->nbn_postcode.'
															<span class="plusminus">+</span>
														</div>
														<div class="accordion_body_post" id="content" style="display: none;">';
															foreach ($nbn_suburbsnames as $nbn_suburbsname) {
															$zone_black .='<p>'.$nbn_suburbsname.'</p>';
														}
														$zone_black .='</div>
														</li>';
														}
														if($zone_color=='Red'){
															$zone_red .='<li id="'.$rowalert->nbn_postcode.'" postcode="'.$rowalert->nbn_postcode.'">
														<div class="accordion_head_post">
															'.$rowalert->nbn_postcode.'
															<span class="plusminus">+</span>
														</div>
														<div class="accordion_body_post" id="content" style="display: none;">';
														foreach ($nbn_suburbsnames as $nbn_suburbsname) {
															$zone_red .='<p>'.$nbn_suburbsname.'</p>';
														}
														$zone_red .='</div>
														</li>';

														}
														if($zone_color=='Amber'){
															$zone_amber .='<li id="'.$rowalert->nbn_postcode.'" postcode="'.$rowalert->nbn_postcode.'">
														<div class="accordion_head_post">
															'.$rowalert->nbn_postcode.'
															<span class="plusminus">+</span>
														</div>
														<div class="accordion_body_post" id="content" style="display: none;">';
															foreach ($nbn_suburbsnames as $nbn_suburbsname) {
															$zone_amber .='<p>'.$nbn_suburbsname.'</p>';
														}
														$zone_amber .='</div>
														</li>';
														}

													?>
												<?php } ?>

												<?php if($zone_black!=''){?>
											
												<span class="label label-black" id="label_black_<?php echo $row->nbn_alert_id;?>" style="text-align:left; font-size:13px !important;">Black</span>
												<ul id="Black_zones_<?php echo $row->nbn_alert_id ; ?>">
														<?php echo $zone_black;?>
												</ul>
											
											<?php }?>
											<?php if($zone_red!=''){?>
											
												<span class="label label-danger" id="label_danger_<?php echo $row->nbn_alert_id;?>" style="text-align:left;">Red</span>
												<ul id="Red_zones_<?php echo $row->nbn_alert_id ; ?>">
													<?php echo $zone_red;?>
												</ul>
											
											<?php }?>
											<?php if($zone_amber!=''){?>
												<span class="label label-warning" id="label_warning_<?php echo $row->nbn_alert_id;?>" style="text-align:left;">Amber</span>
												<ul id="Amber_zones_<?php echo $row->nbn_alert_id ; ?>">
													<?php echo $zone_amber;?>
												</ul>
											<?php }?>

											<?php }	

											?>
											

											
										</div>	

												
							<?php 
								}
							}
						}


					?>
		          </ul>	
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<!-- NBN zone list floating window -->
<!-- NBN zone list floating window -->

<!-- NBN zone alert floating window -->
<div id="nbn_zones_alert" class="row legend-main noleftright">
	<div class="col-xs-12 col-sm-12 col-md-12 nbn-legend-content">
		<div class="row legend-content-header">
			<div class="col-xs-1 col-sm-1 col-md-1 noleftrightpad anchor-class">
				<i class="fa fa-arrows legend-content-header-icons"></i>
			</div>
			<div class="col-xs-9 col-sm-9 col-md-9 legend-content-header-value text-center noleftrightpad">
				<h1>Change Zone Status</h1>
			</div>
			
		</div>
		<div class="row legend-content-body" id="nbn-alert-content">
			<div class="legend-content-body-value">
					 <form  method="POST" id="save_nbn_alert_form" class="save_polygon_form">
	                <div class="modal-body">
	                    <div id="ewn_loading" style="display:none;"><img src="images/loading.gif" ></div>
	                    <div class="form-group">
	                        <label for="title" style="color:#000;">Subject *</label>
	                        <select id="combobox" name="combobox">
								    <option value="">Select one...</option>
								   <?php 
								   	$sqlst = "SELECT * FROM nbn_alerts  group by nbn_alert_id";
									$rsst = mysql_query($sqlst);
									$numst = mysql_num_rows($rsst);
									if($numst>0){
										while ( $row=mysql_fetch_object($rsst)) {
											$alertcount= getAlertCount($row->nbn_alert_id);

											 		if($alertcount>0){
											 ?>			

										 <option value="<?php echo $row->nbn_alert_title; ?>"><?php echo $row->nbn_alert_title; ?></option>
								   
								   <?php 

								   		}
									}
								}?>
							</select>
	                        <div class="autosearch"></div>
	                        <input type="hidden" class="form-control"  id="nbn_postcodes" name="postcodes">
	                    </div>
	                    <div class="form-group">
	                        <label for="title" style="color:#000;">Zone Status *</label>
	                        <select name="alert_type" id="alert_type" class="form-control">
	                            <option value="">Select an alert</option>
	                            <option value="Green">Green</option>
	                            <option value="#ffcc00">Amber</option>
	                            <option value="Red">Red</option>
	                            <option value="Black">Black</option>
	                        </select>
	                    </div>
	                    <div class="form-group">
							<div class="nbn_description_polygon_alert"></div>
	                        <label for="description" style="color:#000;">Alert Message</label>
	                        <textarea id="nbn_description_polygon" name="nbn_description_polygon"  class="form-control" cols="8" rows="2" style="max-width:360px !important;"></textarea>
	                    </div>  
	                </div>
	                <div class="modal-footer">
	                <input type="hidden" id="send_alert_type">
	                    <input type="hidden" name="save_polygon" value="save_nbn_polygon">
	                    <input type="hidden" name="alert_id" id="alert_id">
	                    <button type="button" name="save_poly" value="save_poly" id="cancel_polygon_details" class="btn btn-primary" data-dismiss="modal">Cancel</button>
	                    <button type="button" onclick="confirm_alert()" name="save_poly" value="save_poly" id="save_nbn_alert_details" style="margin-top:0px !important;" class="btn btn-primary" disabled >Send</button>

	                </div>
	            </form>
			</div>
			
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<!-- NBN zone alert floating window -->


