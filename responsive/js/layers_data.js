var dynamic_create_array = [];
var public_checked_options = [];
var client_checked_options = [];

var total_loaded_layer=[];
var current_selected_layer=[];
var unselected_layer=[];

var multi_kml_marker_array = [];
var global_layer_marker_counter=0;

var client_layer_count=0;

var client_loaded_count=0;

var public_loaded_count=0;

var public_loaded_count=0;

var global_request=[];

var public_layers_data = [1,2,3,4,5,6,7,8,9,10]; 
var mixed_layer_array = $.merge(client_layers,public_layers_data); 

$(document).ready(function()
{
    $('#showClientDataDropDownDiv').hide();
    $('#select_public_data').change(function()
    {
        //console.log($(this).val());
    }).multipleSelect({
        placeholder: 'Select Public Layer Data',
        width: '100%',
        minimumCountSelected: 50,
        onCheckAll: function() {
                //remove_filter_marker();
				if(show_asset_click)
				clear_range_markers();
               
			   unselected_layer.length=0;
			   
			   public_layer_count=10;
			   
			   // var minuscount=public_checked_options.length;
				//public_layer_count=10;
				//public_layer_count=public_layer_count-parseInt(minuscount);
			    //console.log('total public>>>'+public_layer_count);
			   // public_loaded_count=0;
				
				public_loaded_count=public_checked_options.length;
			   //console.log('before'+current_selected_layer);
            $('#public_data_layer').attr("checked",true);
            $('#showPublicDataDropDownDiv input:checked').each(function()
            {
				if(this.value!=='on') {
					
					
					 if(this.value=='2') {
							 var text = $(this).parent().text();
							 if($("#public_show_sites_layers option[value='"+this.value+"']").length > 0) {
							 } else {
								$('#public_show_sites_layers')
								 .append($("<option></option>")
								 .attr("value",this.value)
								 .text(text)); 
							 }
					 }
				     
					
					if ((jQuery.inArray(this.value,current_selected_layer))==-1)
					{
						if ((jQuery.inArray(this.value,total_loaded_layer))!=-1)
						{
							// layer already loaded
							getLoadedLayer(this.value,'public');
							//current_selected_layer.push(this.value);
						} else {
							total_loaded_layer.push(this.value);
							
							//var get_again = eval("dynamic_create_array" + this.value);
							
							dynamic_create_array = eval("dynamic_create_array" + this.value + "=[]"); /* Create dynamic array variable*/
							//load_multi_kml_data_new(view.value);
							load_layer(this.value,'public');
						}
						current_selected_layer.push(this.value);
						public_checked_options.push(this.value);
					}
				}
                

            });
			 $('.assets_report').prop('disabled', false);
	   		 $('.report_disabled').hide();
			 
			 setting[10].public_layer_option=public_checked_options;
			 update_setting();
             //console.log(current_selected_layer);
            /* Remove on value */
            //setting[10].selected_public_options = public_checked_options;
        },
        onOpen: function () {
        },
        onUncheckAll:function()
        {
			 //remove_filter_marker();
			 if(show_asset_click)
			 clear_range_markers();
			 
			  public_layer_count=0;
			  public_loaded_count=0;
            //console.log('onUncheckAll Public'+public_checked_options+'length'+public_checked_options.length);
            /* Get all checkboxes of public */
			 for(b=0;b<public_checked_options.length;b++)
            {
				//console.log(parseInt(public_checked_options[b]));
				clear_markers_layer_datas(parseInt(public_checked_options[b]));
				var removeItem=parseInt(public_checked_options[b]);
				current_selected_layer = jQuery.grep(current_selected_layer, function(value) {
				  return value != removeItem;
				});
				
			}
			
			 $('#public_site_list_option').hide();
				  
				   $('#public_show_sites_layers')
				  .find('option')
				  .remove()
				  .end();
		   
           public_checked_options.length=0;
		   
		   setting[10].public_layer_option=public_checked_options;
		   update_setting();
		   
		   //console.log('Uncheckall>>>>>>'+total_layer_deselector);
			 //setting[10].status = 'on';
            //setting[10].public_all = 'no';
            //setting[10].selected_public_options = '';
            $('#public_data_layer').attr("checked",false);
            //update_setting();
        },
        onClose:function ()
        {
           
        },
        onClick:function(view)
        { 
           // alert("click");

            //setting[10].status = 'on';
            $('#public_data_layer').attr("checked",true);
             //remove_filter_marker();
			 if(show_asset_click)
			 clear_range_markers();
           // view consists object of label , value , checked
           if(view.checked==true)
           {
			        
					 public_layer_count=1;
			         public_loaded_count=0;
					  if(view.value=='2' && (LOGIN_USER_NAME=='telstra' || LOGIN_USER_NAME=='admin')) {
							 $('#public_site_list_option').show();
							 if($("#public_show_sites_layers option[value='"+view.value+"']").length > 0) {
							 } else {
								$('#public_show_sites_layers')
								 .append($("<option></option>")
								 .attr("value",view.value)
								 .text(view.label)); 
							 }
					   }
			  
                     $('.assets_report').prop('disabled', false);
	   			     $('.report_disabled').hide();
                    if ((jQuery.inArray(view.value,total_loaded_layer))!=-1)
                    {
						console.log('exist'+view.value)
                        // layer already loaded
						getLoadedLayer(view.value,'public');
                    } else {
						total_loaded_layer.push(view.value);
						dynamic_create_array = eval("dynamic_create_array" + view.value + "=[]"); /* Create dynamic array variable*/
						//load_multi_kml_data_new(view.value);
						load_layer(view.value,'public');
					}
					
					current_selected_layer.push(view.value);
					public_checked_options.push(view.value);
					
					setting[10].public_layer_option=public_checked_options;
					update_setting();
					
			}
           else
           {
               
			   public_layer_count=0;
			   public_loaded_count=0;
             // delete total_loaded_layer[view.value];
			  //delete current_selected_layer[view.value];
			  
			  var removeItem = view.value;
			  
			   if ((jQuery.inArray(view.value,unselected_layer))==-1)
                    {
                        // layer already loaded
						unselected_layer.push(view.value);
                    } 
			 /* total_loaded_layer = jQuery.grep(total_loaded_layer, function(value) {
				  return value != removeItem;
				}); */
				
				 current_selected_layer = jQuery.grep(current_selected_layer, function(value) {
				  return value != removeItem;
				});
				
				 public_checked_options = jQuery.grep(public_checked_options, function(value) {
				  return value != removeItem;
				});
				
				clear_markers_layer_datas(removeItem);
				
    			if(removeItem=='2' && (LOGIN_USER_NAME=='telstra' || LOGIN_USER_NAME=='admin')) {
    			    $('#public_site_list_option').hide();
    				$("#public_show_sites_layers option[value='"+removeItem+"']").remove();
    			}
				
             // console.log('current_selected_layer'+public_layers_data);
              console.log('current_selected_layer'+current_selected_layer);
			  //console.log("public layer option "+public_checked_options); 

               var common = $.grep(current_selected_layer, function(el) {
                        return $.inArray(parseInt(el), mixed_layer_array ) !== -1;
                    });
                // this is widout loop 
            if(common.length==0)
            { 
                $('.assets_report').prop('disabled', true);
                $('.report_disabled').show();
            }


			  /*if(current_selected_layer.length==0) {
					 $('.assets_report').prop('disabled', true);
	   		 		 $('.report_disabled').show();
				}*/
				
				setting[10].public_layer_option=public_checked_options;
				update_setting();
           }
           //setting[10].selected_public_options = public_checked_options;
           //update_setting();
        }
    });


    $('#select_client_data').change(function()
    {
       // console.log($(this).val());
       $('#ok_layer_data').show();
    }).multipleSelect({
        placeholder: 'Select Client Layer Data',
        width: '100%',
        minimumCountSelected: 50,
		onCheckAll: function()
        {
			 //remove_filter_marker();
			 if(show_asset_click)
			 clear_range_markers();
			 
			 var total_client=0;
			 
			  $('#showClientDataDropDownDiv input:checkbox').each(function() {
				   if(this.value!=='on') {
			        total_client++;
				   }
               });
			   
			 //console.log('total client'+total_client);
			 
			$('#client_data_layer').attr("checked",true);
			 //var minuscount=client_checked_options.length;
			 //client_layer_count=client_layers.length-minuscount;
			 client_layer_count=total_client;
			 //console.log('total client'+client_layer_count);
			 //client_layer_count=31;
			 //client_loaded_count=0;
			 client_loaded_count=client_checked_options.length;
			 
			 console.log('loaded count'+client_loaded_count);
			 
			 
            $('#showClientDataDropDownDiv input:checked').each(function()
            {
				var layer_vlaue=this.value;
                if(this.value!=='on') {
					if ((jQuery.inArray(this.value,current_selected_layer))==-1)
					{
						 if(this.value!=='12' && this.value!=='38' && this.value!=='39' && this.value!=='45' && this.value!=='46' && this.value!=='47' && this.value!=='52' && this.value!=='53') {
							 
							  $('#site_list_option').show();
							 
							 var text = $(this).parent().text();
							 if($("#show_sites_layers option[value='"+this.value+"']").length > 0) {
							 } else {
								$('#show_sites_layers')
								 .append($("<option></option>")
								 .attr("value",this.value)
								 .text(text)); 
							 }
							 
							 // enable reporting 
							  $('.assets_report').prop('disabled', false);
	   		 				  $('.report_disabled').hide();
							 
							 
							 
					     	}
						if ((jQuery.inArray(this.value,total_loaded_layer))!=-1)
						{
							console.log(this.value+'alredy loaded');
							// layer already loaded
							getLoadedLayer(this.value,'client');
							//current_selected_layer.push(this.value);
						} else {
							console.log(this.value+'new loaded');
							total_loaded_layer.push(this.value);
							//current_selected_layer.push(this.value);
							
							dynamic_create_array = eval("dynamic_create_array" + this.value + "=[]"); /* Create dynamic array variable*/
							//load_multi_kml_data_new(view.value);
							load_layer(this.value,'client');
						}
						current_selected_layer.push(layer_vlaue);
						client_checked_options.push(layer_vlaue);
						
						
					}
				}
            });
			
			 
			 setting[10].client_layer_option=client_checked_options;
			 update_setting();
        },
        onUncheckAll:function()
        {
            //console.log('onUncheckAll Public');
            /* Get all checkboxes of public */
			 //remove_filter_marker();
			 client_layer_count=0;
			 client_loaded_count=0
			
			 if(show_asset_click)
			 clear_range_markers();
			
			for(b=0;b<client_checked_options.length;b++)
            {
				clear_markers_layer_datas(parseInt(client_checked_options[b]));
				var removeItem=parseInt(client_checked_options[b]);
				current_selected_layer = jQuery.grep(current_selected_layer, function(value) {
				  return value != removeItem;
				});
			}
            client_checked_options=[];
			
			$('#show_sites_layers')
			.find('option')
			.remove()
			.end();
			
			$('#site_list_option').hide();

            //console.log('Uncheckall>>>>>>'+total_layer_deselector);
		     $('#client_data_layer').attr("checked",false);
			 $('.assets_report').prop('disabled', true);
	   		 $('.report_disabled').show();
			 
			 setting[10].client_layer_option=client_checked_options;
			update_setting();
           
        
        },
        onOpen: function () {
        },
        onClose:function ()
        {
        },
        onClick:function(view)
        {
             //  console.log("click on checkbox" + view.value);
            //console.log("testing one two three - "+view);
             //remove_filter_marker();
			 if(show_asset_click)
			    clear_range_markers();
            $('#client_data_layer').attr("checked",true);
            // view consists object of label , value , checked
			if(view.checked==true)
            {
			    
				     client_layer_count=1;
					 client_loaded_count=0;
                     if(view.value!=='12' && view.value!=='38' && view.value!=='39' && view.value!=='45' && view.value!=='46' && view.value!=='47' && view.value!=='52' && view.value!=='53') {
						   $('#site_list_option').show();
						  if($("#show_sites_layers option[value='"+this.value+"']").length > 0) {
							 } else {
								 $('#show_sites_layers')
							 .append($("<option></option>")
							 .attr("value",view.value)
							 .text(view.label)); 
					         }
						   // enable reporting 
							  $('.assets_report').prop('disabled', false);
	   		 				  $('.report_disabled').hide();
							 }
                    if ((jQuery.inArray(view.value,total_loaded_layer))!=-1)
                    {
                        // layer already loaded
						getLoadedLayer(view.value,'client');
                    } else {
						total_loaded_layer.push(view.value);
						dynamic_create_array = eval("dynamic_create_array" + view.value + "=[]"); /* Create dynamic array variable*/
						load_layer(view.value,'client');
					}
					
					current_selected_layer.push(view.value);
					client_checked_options.push(view.value);
					
					setting[10].client_layer_option=client_checked_options;
					update_setting();
					//console.log(total_loaded_layer+'>>>'+current_selected_layer+'...'+client_checked_options);
					//console.log(dynamic_create_array);
			}
           else
           {
              
			   client_layer_count=0;
			   client_loaded_count=0;
              var removeItem = view.value;
			  
			  $("#show_sites_layers option[value='"+removeItem+"']").remove();
			  
			   if ((jQuery.inArray(view.value,unselected_layer))==-1)
                    {
                        // layer already loaded
						unselected_layer.push(view.value);
                    } 
			 
			 	 current_selected_layer = jQuery.grep(current_selected_layer, function(value) {
				  return value != removeItem;
				});
				
				 client_checked_options = jQuery.grep(client_checked_options, function(value) {
				  return value != removeItem;
				});
				
				clear_markers_layer_datas(removeItem);
				// this function uses loop 
                 /*  var  disabled = 1;
                   if(current_selected_layer.length >= 1) 
                   {
                       for(checkc=0; checkc < current_selected_layer.length; checkc++)
                        {
                            check_array  = jQuery.inArray(parseInt(current_selected_layer[checkc]),client_layers);
                           if(check_array===-1)
                           {
                               disabled=0;
                           }
                           else
                           {
                               disabled = 1;
                           }    
                        }
                  }
                   else
                   {
                     disabled = 0;
                   }

                   if(disabled == 0)
                   {
                      $('.assets_report').prop('disabled', true);
                      $('.report_disabled').show();
                   }*/

                   // this is widout loop 
              //  console.log('current_selected_layer'+current_selected_layer);
                    var common = $.grep(current_selected_layer, function(el) {
                        return $.inArray(parseInt(el), mixed_layer_array ) !== -1;
                    });

                    if(common.length==0)
                    { 
                        $('.assets_report').prop('disabled', true);
                        $('.report_disabled').show();
                    }


                    if($('#site_list_option option').length >= 1)
                    {
					 $('#site_list_option').find("option[value='"+view.value+"']").remove();
                    }
                    else
                    {
                        $('#site_list_option').hide();    
                    }
	
				
				setting[10].client_layer_option=client_checked_options;
				update_setting();
           }

                     
           
        }
        
    });
});


		function showPublicDataDropdown()
		{
			if($('#public_data_layer').attr("checked"))
			{
				$('.main_public_controller').show();
				$('#showPublicDataDropDownDiv').show();
				$('.data_layers_drop_down').show();
				$('#select_public_data').hide();
				// $('#select_client_data').hide();
			}
			else
			{
				hide_public_sites();
				infowindow.close();
				public_layer_count=0;
				//clear_range_markers();
				 remove_filter_marker();
				/* Get all checkboxes of public */
				  for(b=0;b<public_checked_options.length;b++)
					{
						//console.log(parseInt(public_checked_options[b]));
						clear_markers_layer_datas(parseInt(public_checked_options[b]));
						var removeItem=parseInt(public_checked_options[b]);
						current_selected_layer = jQuery.grep(current_selected_layer, function(value) {
						  return value != removeItem;
						});
					}
				   
				   public_checked_options.length=0;
		
				$('#public_data_layer').attr("checked",false);
				
				$('#showPublicDataDropDownDiv').hide();
				$('#public_data_layer').hide();
				$('.main_public_controller').hide();
                $('#showPublicDataDropDownDiv').find('input[type=checkbox]:checked').removeAttr('checked');
                $('#showPublicDataDropDownDiv').find('span').addClass("placeholder");
				$('#showPublicDataDropDownDiv').find('span').html('Select Public Layer Data');
				
				 var common = $.grep(current_selected_layer, function(el) {
                        return $.inArray(parseInt(el), mixed_layer_array ) !== -1;
                    });
                    // this is widout loop 
                    if(common.length==0)
                    { 
                        $('.assets_report').prop('disabled', true);
                        $('.report_disabled').show();
                    }
				 
				 setting[10].public_layer_option=public_checked_options;
				 update_setting();
				 
				  $('#public_site_list_option').hide();
				  
				   $('#public_show_sites_layers')
				  .find('option')
				  .remove()
				  .end()
				  
			  ;
				 
				
		
			}
		}



	  function showClientDataDropDown()
	  {
		  if($('#client_data_layer').attr("checked"))
		  {
		  	  $('.main_client_controller').show();
			  $('#showClientDataDropDownDiv').show();
			  $('.data_layers_drop_down_client').show();
			  $('#select_client_data').hide();
		  }
		  else
		  {
		  	  hide_sites();
		  	  infowindow.close();
			  client_layer_count=0;
			  $('#site_list_option').hide();
	  
			  $('#show_sites_layers')
				  .find('option')
				  .remove()
				  .end()
				  
			  ;
			  
			  remove_filter_marker();
			  
			  //clear_range_markers();
			  
			   
	  			// console.log('layer_data_display_array'+client_checked_options);
			  /* Get all checkboxes of public */
			   for(b=0;b<client_checked_options.length;b++)
				  {
					  clear_markers_layer_datas(parseInt(client_checked_options[b]));
					  var removeItem=parseInt(client_checked_options[b]);
					  current_selected_layer = jQuery.grep(current_selected_layer, function(value) {
						return value != removeItem;
					  });
				  }
				  client_checked_options.length=0;
			  /* Get all checkboxes of public */
	  
			 $('#client_data_layer').attr("checked",false);
			 // update_setting();
	  		/*Get checked values*/
			  $('#showClientDataDropDownDiv').hide();
			  $('.data_layers_drop_down_client').hide();
			  $('.main_client_controller').hide();
            //  $('#showClientDataDropDownDiv').find('input[type=checkbox]:checked').removeAttr('checked');
			  $('#showClientDataDropDownDiv').find('input[type=checkbox]:checked').removeAttr('checked').trigger('change');
			  $('#showClientDataDropDownDiv').find('span').addClass("placeholder");
			  $('#showClientDataDropDownDiv').find('span').html('Select Client Layer Data');
			  
			 
			  
			    var common = $.grep(current_selected_layer, function(el) {
                        return $.inArray(parseInt(el), mixed_layer_array ) !== -1;
                });
                // this is widout loop 
                if(common.length==0)
                { 
                    $('.assets_report').prop('disabled', true);
                    $('.report_disabled').show();
                }
			  
			  $('.navigation').hide();
			  
			  setting[10].client_layer_option=client_checked_options;
			  update_setting();
			  
			  
		  }
	  }

	function clear_markers_layer_datas(layer_id)
	{
	   if(layer_id!==4 && layer_id!==12 && layer_id!==38) {
		   //console.log('remove'+layer_id);
		  var my_data_value = eval("dynamic_create_array"+parseInt(layer_id));
		  if(my_data_value.length>0) {
			  $('#loader_layer_type').show();
			  for(i=0;i<=my_data_value.length-1;i++)
			  {
				  //my_data_value[i].setMap(null);
				  my_data_value[i].setVisible(false);
				  if(i==my_data_value.length-1)
				  {
					  $('#loader_layer_type').hide();
				  }
			  }
		  }
		  
		   }
	  
		
						
		  if(layer_id==1) {
			   RT_NSW.setMap(null);
			  RT_NT.setMap(null);
			  RT_QLD.setMap(null);
			  RT_SA.setMap(null);
			  RT_TAS.setMap(null);
			  RT_VIS.setMap(null);
			  RT_WA.setMap(null);
			   $('#loader_layer_type').hide();
		  }
		  
		   if(layer_id==35) {
				waternsw.setMap(null);
			  }
		  
		   if(layer_id==12)
		  {
			  remove_post_code();
			  $('#loader_layer_type').hide();
		  }
		  
		  if(layer_id==38) {
			      GWA.setMap(null);
			  }
			  
			  if(layer_id==39) {
			      datach1.setMap(null);
				  chaininfowindow.close();
			  }
			  
			   if(layer_id==45) {
			      OUTAGE_UNPLANNED.setMap(null);
				  clearInterval(OUTAGE_UNPLANNED_Interval);
				  $('#outages_div').hide();
			  }
			  
			  if(layer_id==46) {
			      PUBLIC_OUTAGES.setMap(null);
				   clearInterval(PUBLIC_OUTAGES_Interval);
			  }
			  
			  if(layer_id==47) {
			      PUBLIC_OUTAGES_FUTURE.setMap(null);
				   clearInterval(PUBLIC_OUTAGES_FUTURE_Interval);
			  }
			  
			  // BCC
			   if(layer_id==52) {
			      BCC.setMap(null);
			  }
			  
			   //ARTC Layer
			  if(layer_id==53) {
				  ARTC.setMap(null);
			  }
			  
			  // ARTC Chainage Layer
			  if(layer_id==64) {
					 //datach2.setMap(null);
					 chaininfowindowartc.close();
			  }
			  
			  
			  
			  
	   }

	  function load_layer(layer_values,layer_type)
	  {
		 //console.log(layer_values);
		   var element='';
		   if(layer_type=='public') {
			   element=$('#public_data_layer');
		   } else {
			   element=$('#client_data_layer');
		   }
			 /* Check array consists 1 or not*/
			  if(layer_values == 1)
			  {
				  RT_NSW.setMap(map);
				  RT_NT.setMap(map);
				  RT_QLD.setMap(map);
				  RT_SA.setMap(map);
				  RT_TAS.setMap(map);
				  RT_VIS.setMap(map);
				  RT_WA.setMap(map);
			  }
			  if(layer_values == 12)
			  {
				 show_post_code();
			  }
		  
		   	  if(layer_values==35) {
			     if(element.is(':checked'))
			 	  waternsw.setMap(map);
			  }
			  
		 	 if(layer_values==38) {
			      if(element.is(':checked'))
			 	  GWA.setMap(map);
			  }
			  
			  if(layer_values==45) {
			      if(element.is(':checked')) {
			 	  	OUTAGE_UNPLANNED.setMap(map);
				  	refresh_kml_layer(layer_values);
					$('#outages_div').show();
				  }
			  }
			  
			  if(layer_values==46) {
			      if(element.is(':checked')) {
			 	  	PUBLIC_OUTAGES.setMap(map);
				  	refresh_kml_layer(layer_values);
				  }
			  }
			  
			  if(layer_values==47) {
			      if(element.is(':checked')) {
			 	  	PUBLIC_OUTAGES_FUTURE.setMap(map);
				  	refresh_kml_layer(layer_values);
				  }
			  }
			  
			  //BCC Layer
			  if(layer_values==52) {
			      if(element.is(':checked'))
					 BCC.setMap(map);
				  
			  }
			  
			   //ARTC Layer
			  if(layer_values==53) {
				  if(element.is(':checked'))
				  ARTC.setMap(map);
			  }
			  
			if(layer_values==39) {
							//GWA_Chainage.setMap(map);
							//GWA GeoJson Layers
								 datach1.loadGeoJson(chainageurl);
								  // Set event listener for each feature.
								  datach1.addListener('mouseover', function(event) {
									 var fetproper = event.feature.getProperty("ROUTE_CHAI");
									 chaininfowindow.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper + 'km </b>' + '</div>');
									 chaininfowindow.setPosition(event.latLng);
									 chaininfowindow.setOptions({pixelOffset: new google.maps.Size(0,-34)});
									 chaininfowindow.open(map);
								  });
						 
								 datach1.setMap(map);
						  }
						  
			/*if(layer_values==64) {
							//GWA_Chainage.setMap(map);
							//GWA GeoJson Layers
								 datach2.loadGeoJson(artc_chainageurl);
								  // Set event listener for each feature.
								datach2.addListener('mouseover', function(event) {
								   var fetproper2 = event.feature.getProperty("KmPost");
								    var description = fetproper2.split("-"); 
									description[0] = description[0].replace(' ',' km ');
									fetproper2=description[0]+description[1];
								   chaininfowindowartc.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper2 + '</b>' + '</div>');
								   chaininfowindowartc.setPosition(event.latLng);
								   chaininfowindowartc.setOptions({pixelOffset: new google.maps.Size(0,-34)});
								   chaininfowindowartc.open(map);
								   });
						 
								 datach2.setMap(map);
						  }*/
		  /* Check array consists 1 or not*/
	  
		  // var selected_layer_values=[];
		   //selected_layer_values.push(layer_values);
		  //$('#loader_layer_type').show();
		  show_loader(layer_type);
		  
		  var ajaxobj=null;
		  
		  ajaxobj=$.ajax
		  ({
		   type: "POST",
		   url: "ajax/get_layer_data.php",
		   dataType: "json",
		   data: { layer_type:layer_values},
		    // we are checking the code for the file exists or not
			statusCode:{
				404:function() {
					//$("#spnStatus").html("Sorry file not found!");
				}
			},
         
        // error function  we are checking timeout,
        // if its taking more than 5 seconds from server to response
        // send the error message to spnStatus tag
         
			error:function(xhr,textStatus,errorThrown) {   
				//$("#spnStatus").html("Error : "+xhr.error+"_"+textStatus+"_"+errorThrown);
				if(textStatus=='timeout') {
					$("#spnStatus").html("Error : Current call has been timeout");
				}
			},
		   success: function(response)
		   {
				var html = "";
				var html1 = "";
				var html2 = "";
				
				//console.log(global_request[response.layer_id]);
				
				var loaded_count;
				var total;
				
				global_request[response.layer_id]='loaded';
				
				if(parseInt(response.layer_id)<=10) {
					public_loaded_count=public_loaded_count+1;
					loaded_count=public_loaded_count;
					total=public_layer_count;
				} else {
				   client_loaded_count=client_loaded_count+1;
				   loaded_count=client_loaded_count;
				   total=client_layer_count;
				}
				
				
				
				//client_layer_count--;
				
				 // console.log('layer count'+client_layer_count+'>>>>loaded count'+client_loaded_count+'>>>>>'+response.layer_id);
				
				 var layer_type=''
					  
					  /* element */
					   if(parseInt(response.layer_id)<=10) {
							layer_type='public';
							 //console.log(response.data[k].layer_id+'public');
						 } else {
							   layer_type='client';
							 //console.log(response.data[k].layer_id+'client');
							 
						 }
				
				
				
				if(response.data.length>0) {
				var count=response.data.length-1;
				for (k=0;k<response.data.length;k++)
				{
					
					
				 
				   var myLatlng = new google.maps.LatLng(response.data[k].latitude,response.data[k].longitude);
				   var layer_marker_new = new google.maps.Marker({
						  position: myLatlng,
						  title: response.data[k].placemarker_name,
						  icon:'images/layer_markers/'+response.data[k].placemarker_icon
					  });
					  
					  //console.log('images/layer_markers/'+response.data[k].placemarker_icon);
					  
					  var element='';
					  var container='';
					  var layer_type=''
					  
					  /* element */
					   if(parseInt(response.data[k].layer_id)<=10) {
							 element=$('#public_data_layer');
							 container=$('#showPublicDataDropDownDiv');
							 layer_type='public';
							 //console.log(response.data[k].layer_id+'public');
						 } else {
							 element=$('#client_data_layer');
							  container=$('#showClientDataDropDownDiv');
							   layer_type='client';
							 //console.log(response.data[k].layer_id+'client');
							 
						 }
						
						//console.log(container.find("input[type=checkbox]").filter('[value="'+response.data[k].layer_id+'"]').is(':checked'));
						//console.log($("#client_data_layer").is(':checked'));
					  //console.log(element.is(':checked'));
					 if(element.is(':checked') && container.find("input[type=checkbox]").filter('[value="'+response.data[k].layer_id+'"]').is(':checked')) {
						layer_marker_new.setMap(map);
					 }
					multi_kml_marker_array.push(layer_marker_new);
				   
					eval("dynamic_create_array" + response.data[k].layer_id).push(layer_marker_new);
					
				google.maps.event.addListener(layer_marker_new, 'click', (function(marker, k) {
						  return function() {
									 var contentString = response.data[k].placemarker_description;
									infowindow.setContent(contentString);
									infowindow.open(map, marker);
						  }
				})(layer_marker_new, k));
				
				if(parseInt(response.data[k].layer_id)==64) {
					  google.maps.event.addListener(layer_marker_new, 'mouseover', (function(marker, k) {
								return function() {
										  var fetproper2 = response.data[k].placemarker_name;
										  //var description = fetproper2.split("-"); 
										  //description[0] = description[0].replace(' ',' km ');
										  //fetproper2=description[0]+description[1];
										 chaininfowindowartc.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper2 + '</b>' + '</div>');
										  var myLatlng = new google.maps.LatLng(response.data[k].latitude,response.data[k].longitude);
										 chaininfowindowartc.setPosition(myLatlng);
										 //chaininfowindowartc.setOptions({pixelOffset: new google.maps.Size(0,-34)});
										 chaininfowindowartc.open(map,marker);
								}
					  })(layer_marker_new, k));
				}
				
				 if(parseInt(response.data[k].layer_id)>10 || parseInt(response.data[k].layer_id)==2) {
				 var tag='<a style="cursor:pointer" onclick=setlocation('+response.data[k].latitude+','+response.data[k].longitude+','+global_layer_marker_counter+') >'+response.data[k].placemarker_name+'</a>';
					  html += "<li>" + tag + "</li>";
				 }
				
				   if(k==count) {
					 //$('#loader_layer_type').hide();
					  
					 if(loaded_count>=total)
					   hide_loader(layer_type);
					   
				   }
					global_layer_marker_counter++;
				}
	  
				     //$('#show_sites').show();
					   if((parseInt(layer_values)>10 && parseInt(layer_values)!==46 && parseInt(layer_values)!==47) || (parseInt(layer_values)==2 && (LOGIN_USER_NAME=='telstra' || LOGIN_USER_NAME=='admin'))) {
						   if(element.is(':checked') && container.find("input[type=checkbox]").filter('[value="'+layer_values+'"]').is(':checked')) {
								if(parseInt(layer_values)!==2 && parseInt(layer_values)!==39) 
						    	$('#site_list_option').show();
								else 
								$('#public_site_list_option').show();
							}
					   }
					  // console.log(html);
					  $('#navigation_site_'+layer_values).append(html);
				  } else {
					 //$('#loader').hide();
					  if(loaded_count>=total)
					   hide_loader(layer_type);
					  //hide_loader();
				}
				 
		   }
		   
		   
	   });
	   
	   //global_request[layer_values]=ajaxobj;
	  
	  }

		function getLoadedLayer(layer_id,layer_type)
		{
			    
				 
				 var element='';
				 
				 show_loader(layer_type);
				 
				 
				 if(parseInt(layer_id)<=10) {
							 element=$('#public_data_layer');
							 
							 //console.log(response.data[k].layer_id+'public');
						 } else {
							 element=$('#client_data_layer');
							 
							 //console.log(response.data[k].layer_id+'client');
							 
						 }
				 
				 
				if(layer_id!=12)
						{
							var get_again = eval("dynamic_create_array" + layer_id);
							//$('#loader_layer_type').show();
							 		if(get_again.length!=0)
									{
										for(m=0;m<=get_again.length-1;m++)
										{
											 if(element.is(':checked')) {
											  get_again[m].setVisible(true);
											 // get_again[m].setMap(map);
											}
				
											if(m==get_again.length-1)
											{
												//$('#loader_layer_type').hide();
												// hideAjaxWait('layer_loader');
												 //hide_loader();
												 var loaded_count;
												 var total;
												 var layer_type='';
												 if(parseInt(layer_id)<=10) {
														public_loaded_count++;
														loaded_count=public_loaded_count;
														total=public_layer_count;
														layer_type='public';
													} else {
													   client_loaded_count++;
													   loaded_count=client_loaded_count;
													   total=client_layer_count;
													   layer_type='client';
													}
												 
												 
												 //client_loaded_count++;
												 console.log('layer count'+client_layer_count+'loaded count'+client_loaded_count);
												  if(loaded_count>=total) {
					   									hide_loader(layer_type);
												  }
											}
										}
									}
									else
									{
										//$('#loader_layer_type').hide();
										 // hide_loader();
										// client_loaded_count++;
										 var loaded_count;
												 var total;
												 var layer_type='';
										 			if(parseInt(layer_id)<=10) {
														public_loaded_count++;
														loaded_count=public_loaded_count;
														total=public_layer_count;
														layer_type='public';
													} else {
													   client_loaded_count++;
													   loaded_count=client_loaded_count;
													   total=client_layer_count;
													   layer_type='client';
													}
										 //console.log('layer count'+client_layer_count+'loaded count'+client_loaded_count);
										  if(loaded_count>=total) {
					   						  hide_loader(layer_type);
										  }
									}
							 
						}
						if(layer_id==12)
						{
							 client_loaded_count++;
							 if(element.is(':checked'))
							 show_post_code();
							 
							 if(client_loaded_count>=client_layer_count) {
					   						  hide_loader('client');
							 }
							 
						}
						
						 if(layer_id==45) {
							  if(element.is(':checked')) {
								$('#outages_div').show();
								OUTAGE_UNPLANNED.setMap(map);
								refresh_kml_layer(layer_id);
								
							  }
						  }
						  
						  if(layer_id==46) {
							  if(element.is(':checked')) {
								PUBLIC_OUTAGES.setMap(map);
								refresh_kml_layer(layer_id);
							  }
						  }
						  
						  if(layer_id==47) {
							  if(element.is(':checked')) {
								PUBLIC_OUTAGES_FUTURE.setMap(map);
								refresh_kml_layer(layer_id);
							  }
						  }
						
						 //BCC Layer
						  if(layer_id==52) {
							  if(element.is(':checked'))
							  BCC.setMap(map);
						  }
						  
						  //ARTC Layer
						  if(layer_id==53) {
							  if(element.is(':checked'))
							  ARTC.setMap(map);
						  }
						
						 if(layer_id==35) {
							     if(element.is(':checked'))
								waternsw.setMap(map);
			  			 }
						 
						  if(layer_id==38) {
							  if(element.is(':checked'))
							  GWA.setMap(map);
						  }
						  
						  if(layer_id==1) {
							RT_NSW.setMap(map);
							RT_NT.setMap(map);
							RT_QLD.setMap(map);
							RT_SA.setMap(map);
							RT_TAS.setMap(map);
							RT_VIS.setMap(map);
							RT_WA.setMap(map);
						  }
						  
						  if(layer_id==39) {
							//GWA_Chainage.setMap(map);
							//GWA GeoJson Layers
								 datach1.loadGeoJson(chainageurl);
								  // Set event listener for each feature.
								  datach1.addListener('mouseover', function(event) {
									 var fetproper = event.feature.getProperty("ROUTE_CHAI");
									 var description = fetproper.split("-"); 
									 console.log(description);
									 chaininfowindow.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper + 'km </b>' + '</div>');
									 chaininfowindow.setPosition(event.latLng);
									 chaininfowindow.setOptions({pixelOffset: new google.maps.Size(0,-34)});
									 chaininfowindow.open(map);
								  });
						 
								 datach1.setMap(map);
						  }
						  
						  // ARTC Chainage Layer
						  /*if(layer_id==64) {
							//GWA GeoJson Layers
								 datach2.loadGeoJson(artc_chainageurl);
								  // Set event listener for each feature.
								datach2.addListener('mouseover', function(event) {
								   var fetproper2 = event.feature.getProperty("KmPost");
								    var description = fetproper2.split("-"); 
									description[0] = description[0].replace(' ',' km ');
									fetproper2=description[0]+description[1];
								   chaininfowindowartc.setContent('<div style="width:115px; background-color:orange; border:2px solid red; padding: 5px 5px 5px 5px; ">' + 'Chainage: <b>' + fetproper2 + '</b>' + '</div>');
								   chaininfowindowartc.setPosition(event.latLng);
								   chaininfowindowartc.setOptions({pixelOffset: new google.maps.Size(0,-34)});
								   chaininfowindowartc.open(map);
								   });
						 
								 datach2.setMap(map);
						  }*/
						 
						
				 
		}
		
		function show_loader(layer_type) {
			console.log(layer_type);
			if(layer_type=='client')
			showAjaxWait($('#client_data_layer'),'client_layer_loader');
			else
			showAjaxWait($('#public_data_layer'),'public_layer_loader');
			
			 $('.bottom :input').attr('disabled', true);
		}
		
		function hide_loader(layer_type) {
			console.log(layer_type);
			if(layer_type=='client')
			hideAjaxWait('client_layer_loader');
			else
			hideAjaxWait('public_layer_loader');
			
			if($('.client_layer_loader').length==0 && $('.public_layer_loader').length==0) {
			 $('.bottom :input').attr('disabled', false);
			}
			
		}
		
		function client_layer(client_layers) {
			/* Client layer setting */
			
			/* setting for dexus client */
			if(LOGIN_USER_NAME=='dexus') {
				 dynamic_create_array = eval("dynamic_create_array" + 15 + "=[]"); /* Create dynamic array variable*/
				 load_layer(15,'client');
				 if ((jQuery.inArray("15",client_layers))==-1){
					 client_layers.push("15");
				 }
			}
			
			var notinlayers=[];
			
			var total_client_layer=0;
			
			for(z=0;z<=client_layers.length-1;z++)
							{
								if($('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="'+client_layers[z]+'"]').length>0) {
								} else {
									notinlayers.push(client_layers[z]);
								}
			}
			
			 $('#showClientDataDropDownDiv input:checkbox').each(function() {
				   if(this.value!=='on') {
			        total_client_layer++;
				   }
               });
			
			
			//console.log(notinlayers);
			
						for(z=0;z<=notinlayers.length-1;z++)
							{
									client_layers = jQuery.grep(client_layers, function(value) {
									  return value != notinlayers[z];
									});
									
									client_checked_options = jQuery.grep(client_checked_options, function(value) {
									  return value != notinlayers[z];
									});
									
							}
			
			
			//console.log(client_layers);
			
			/* setting for dexus client */
			 if(client_layers.length>0) {
				     $('#client_data_layer').prop("checked",true);
				    var new_text_values='';
					client_layer_count=client_layers.length;
					for(z=0;z<=client_layers.length-1;z++)
							{
								
								layer_value=client_layers[z];
								
								//console.log($('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="'+client_layers[z]+'"]').length+client_layers[z]);
								
								if($('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="'+client_layers[z]+'"]').length>0) {
								
								$('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="'+client_layers[z]+'"]').prop("checked", true);
								if ((jQuery.inArray(client_layers[z],current_selected_layer))==-1){
									current_selected_layer.push(client_layers[z]);
									total_loaded_layer.push(client_layers[z]);
								}
								
								  var text = $('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="'+client_layers[z]+'"]').parent().text();
								  new_text_values = new_text_values+' , '+text;
								 
								  new_text_values = $.trim(new_text_values);
                            	  new_text_values = new_text_values.replace(/^,|,$/g,'');
								 
								   dynamic_create_array = eval("dynamic_create_array" + client_layers[z] + "=[]"); /* Create dynamic array variable*/
								   
								   load_layer(client_layers[z],'client');
								 
								 
								/* show sites */
								if(client_layers[z]!=='12' &&  client_layers[z]!=='38' && client_layers[z]!=='39' && client_layers[z]!=='45' && client_layers[z]!=='46' && client_layers[z]!=='47' && client_layers[z]!=='52' && client_layers[z]!=='53') {
									
									 $('.assets_report').prop('disabled', false);
	   		 						 $('.report_disabled').hide();
								     
									 $('#site_list_option').show();
							 	   
								   if($("#show_sites_layers option[value='"+client_layers[z]+"']").length > 0) {
								   } else {
									  $('#show_sites_layers')
									   .append($("<option></option>")
									   .attr("value",client_layers[z])
									   .text(text)); 
								   }
								  }
								  //load_layer(client_layers[z],'client');
								}
							 }
								
								$('#showClientDataDropDownDiv').find('span').removeClass("placeholder");
                            	$('#showClientDataDropDownDiv').find('span').addClass("ms-choice");
                            	$('#showClientDataDropDownDiv').find('span').html(new_text_values);
								/* show sites */
							
					 $('.main_client_controller').show();
					// $('#client_data_layer').prop("checked",true);
					 $('#showClientDataDropDownDiv').show();
					 $('.data_layers_drop_down_client').show();
					 $('#select_client_data').hide();
					 
					 console.log('client'+client_layers.length+'  total'+total_client_layer);
					 
					 if(client_layers.length==total_client_layer) {
							 $('#showClientDataDropDownDiv').find("input[type=checkbox]").filter('[value="on"]').prop("checked", true);
						}
				}
				
				
		}
		
		function public_layer(public_layers) {
			 /* public layer setting */
				if(public_layers.length>0) {
					$('#public_data_layer').prop("checked",true);
					 var new_text_values='';
					 public_layer_count=public_layers.length;
					 for(p=0;p<=public_layers.length-1;p++)
							{
								$('#showPublicDataDropDownDiv').find("input[type=checkbox]").filter('[value="'+public_layers[p]+'"]').prop("checked", true);
								if ((jQuery.inArray(public_layers[p],current_selected_layer))==-1){
									current_selected_layer.push(public_layers[p]);
									total_loaded_layer.push(client_layers[p]);
								}
								
								 var text = $('#showPublicDataDropDownDiv').find("input[type=checkbox]").filter('[value="'+public_layers[p]+'"]').parent().text();
								  new_text_values = new_text_values+' , '+text;
								 
								  new_text_values = $.trim(new_text_values);
                            	  new_text_values = new_text_values.replace(/^,|,$/g,'');
								  
								   dynamic_create_array = eval("dynamic_create_array" + public_layers[p] + "=[]"); /* Create dynamic array variable*/
								   load_layer(public_layers[p],'public');
								   
								   $('.assets_report').prop('disabled', false);
	   		 					   $('.report_disabled').hide();
								   
								   /* show sites */
								if(public_layers[p]=='2' && (LOGIN_USER_NAME=='telstra' || LOGIN_USER_NAME=='admin')) {
								    $('#public_site_list_option').show();
									 if($("#public_show_sites_layers option[value='"+public_layers[p]+"']").length > 0) {
									 } else {
										$('#public_show_sites_layers')
										 .append($("<option></option>")
										 .attr("value",public_layers[p])
										 .text(text)); 
									 }
								  }
								   
							}
							
						
							
						 $('#showPublicDataDropDownDiv').find('span').removeClass("placeholder");
                    				$('#showPublicDataDropDownDiv').find('span').addClass("ms-choice");
                    				$('#showPublicDataDropDownDiv').find('span').html(new_text_values);
									
						$('.main_public_controller').show();
                   		//$('#public_data_layer').prop("checked",true);
                    	$('#showPublicDataDropDownDiv').show();
                     	$('.data_layers_drop_down').show();
                     	$('#select_public_data').hide();
						
						if(public_layer_count==10) {
							 $('#showPublicDataDropDownDiv').find("input[type=checkbox]").filter('[value="on"]').prop("checked", true);
						}
				}
				
		}
		
		
		function load_client_layer(client_checked_options) {
			 //console.log(client_checked_options);
			  client_layer_count=1;
			 $(client_checked_options).each(function(value)
            {
				//console.log(client_checked_options[value]);
				if(client_checked_options[value]!=='on') {
					 if ((jQuery.inArray(client_checked_options[value],current_selected_layer))!=-1)
						{
							// layer already loaded
							getLoadedLayer(client_checked_options[value],'client');
							//current_selected_layer.push(this.value);
						}
				}
            });
			
		}
		
		function load_public_layer(public_checked_options) {
			//console.log(public_checked_options);
			  //console.log(current_selected_layer);
			 public_layer_count=1;
			 $(public_checked_options).each(function(value)
            {
				//console.log(public_checked_options[value]);
				if(public_checked_options[value]!=='on') {
					
					
					    //console.log(public_checked_options[value]);
						if ((jQuery.inArray(public_checked_options[value],current_selected_layer))!=-1)
						{
							// layer already loaded
							//console.log(public_checked_options[value]);
							getLoadedLayer(public_checked_options[value],'public');
							//current_selected_layer.push(this.value);
						} 
						
				}
            });
		}
		
		// OUTAGE_UNPLANNED_Timeout;
		var OUTAGE_UNPLANNED_Interval;
		var PUBLIC_OUTAGES_Interval;
		var PUBLIC_OUTAGES_FUTURE_Interval;
		var OUTAGES_REFRESH_INTERVAL=600000;
		
		// function for refresh kml layer
		function refresh_kml_layer(layer_id) {
			
			// OUTAGES UNPLANNED layer refresh
			if(layer_id==45) {
			     OUTAGE_UNPLANNED_Interval =setInterval(function() {
					 OUTAGE_UNPLANNED_Timeout.setMap(null);
					 //console.log('reload'+layer_id);
				    var d = Math.floor(Math.random()*1000000000000000000000001);
					  var url=OUTAGE_UNPLANNED+'?ref='+d;
					  
					  OUTAGE_UNPLANNED = new google.maps.KmlLayer(url, {
						  preserveViewport: true,
						  suppressInfoWindows: false 
					  });
					  OUTAGE_UNPLANNED.setMap(map);
				   
				}, OUTAGES_REFRESH_INTERVAL);
			}
			
			// PUBLIC OUTAGES layer refresh
			if(layer_id==46) {
				 PUBLIC_OUTAGES_Interval = setInterval(function() {
					 PUBLIC_OUTAGES.setMap(null);
					
				    var d = Math.floor(Math.random()*1000000000000000000000001);
					  var url=PUBLIC_OUTAGES_URL+'?ref='+d;
					  //console.log('reload'+layer_id+' '+url);
					  PUBLIC_OUTAGES = new google.maps.KmlLayer(url, {
						  preserveViewport: true,
						  suppressInfoWindows: false 
					  });
					  PUBLIC_OUTAGES.setMap(map);
				   
				}, OUTAGES_REFRESH_INTERVAL);
			}
			
			// PUBLIC_OUTAGES_FUTURE layer refresh
			if(layer_id==47) {
				PUBLIC_OUTAGES_FUTURE_Interval=setInterval(function() {
				   PUBLIC_OUTAGES_FUTURE.setMap(null);
					var d = Math.floor(Math.random()*1000000000000000000000001);
					  var url=PUBLIC_OUTAGES_FUTURE_URL+'?ref='+d;
					  //console.log('reload'+url);
					  PUBLIC_OUTAGES_FUTURE = new google.maps.KmlLayer(url, {
						  preserveViewport: true,
						  suppressInfoWindows: false 
					  });
					  PUBLIC_OUTAGES_FUTURE.setMap(map);
				},OUTAGES_REFRESH_INTERVAL);  
			}
			
		}