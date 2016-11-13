var dynamic_create_array = [];
var public_checked_options = [];
var client_checked_options = [];
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
            setting[10].status = 'on';
            setting[10].public_all = 'yes';
            $('#public_data_layer').attr("checked",true);
            $('#showPublicDataDropDownDiv input:checked').each(function()
            {
                if (((jQuery.inArray(this.value,total_layer_selector))==-1) && ((jQuery.inArray(this.value,layer_data_display_array))==-1))
                {
                    total_layer_selector.push(this.value);
                }

                if ((jQuery.inArray(this.value,total_layer_deselector))!=-1)
                {
                    if ((jQuery.inArray(this.value,loaded_layer_selector))==-1)
                    {
                        loaded_layer_selector.push(this.value);
                    }
                }
            });
            //console.log(selected);
            /* Remove on value */
            var removeItemdeselect = 'on';   
            total_layer_selector = $.grep(total_layer_selector, function(value) {
              return value != removeItemdeselect;
            });
            /* Remove on value */
            update_setting();
        },
        onOpen: function () {
        },
        onUncheckAll:function()
        {
            //console.log('onUncheckAll Public');
            /* Get all checkboxes of public */
            for(b=1;b<=layer_data_display_array.length;b++)
            {
                if ((jQuery.inArray(b,total_layer_deselector))==-1)
                {
                   //console.log('124578');
                   total_layer_deselector.push(b); /* Add into deselector */
                }
            }
            /* Get all checkboxes of public */

           for(p=1;p<=10;p++)
           {
                var removeItemdeselect = p;   
                total_layer_selector = $.grep(total_layer_selector, function(value) {
                  return value != removeItemdeselect;
                });
           }


            //console.log('Uncheckall>>>>>>'+total_layer_deselector);

            setting[10].status = 'on';
            setting[10].public_all = 'no';
            $('#public_data_layer').attr("checked",false);
            update_setting();
        },
        onClose:function ()
        {
            clear_multi_markers_layer_datas(clear_callback);
            getLayerDataSection();
            getLoadedLayers();
        },
        onClick:function(view)
        {
            setting[10].status = 'on';
            $('#public_data_layer').attr("checked",true);
            
           // view consists object of label , value , checked
           if(view.checked==true)
           {
                $('#showPublicDataDropDownDiv input:checked').each(function()
                {
                    if (((jQuery.inArray(this.value,public_checked_options))==-1))
                    {
                        public_checked_options.push(this.value);/*Remove this tomorrow if error*/
                    }
                });
            if (((jQuery.inArray(view.value,total_layer_selector))==-1) && ((jQuery.inArray(view.value,layer_data_display_array))==-1))
            {
                total_layer_selector.push(view.value);
            }
            if ((jQuery.inArray(view.value,total_layer_deselector))==-1)
            {
                if ((jQuery.inArray(view.value,loaded_layer_selector))==-1)
                {
                    loaded_layer_selector.push(view.value);
                }
            }
           }
           else
           {
            if (((jQuery.inArray(this.value,public_checked_options))!=-1))
            {
                var removeItemdeselect = this.value;   
                public_checked_options = $.grep(public_checked_options, function(value) {
                  return value != removeItemdeselect;
                });
            }
            setting[10].public_all = 'no';
            var removeItem = view.value;   
            total_layer_selector = $.grep(total_layer_selector, function(value) {
              return value != removeItem;
            });



            if ((jQuery.inArray(view.value,total_layer_deselector))==-1)
            {
                total_layer_deselector.push(view.value);
            }

            //console.log('total_layer_selector'+total_layer_selector);
           // console.log('total_layer_deselector'+total_layer_deselector);
            
           }
           setting[10].selected_public_options = public_checked_options;
           update_setting();
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
            setting[11].status = 'on';
            setting[11].public_all = 'yes';
             $('#client_data_layer').attr("checked",true);
            $('#showClientDataDropDownDiv input:checked').each(function()
            {
                if (((jQuery.inArray(this.value,total_layer_selector))==-1) && ((jQuery.inArray(this.value,layer_data_display_array))==-1))
                {
                    total_layer_selector.push(this.value);
                }

                if ((jQuery.inArray(this.value,total_layer_deselector))!=-1)
                {
                    if ((jQuery.inArray(this.value,loaded_layer_selector))==-1)
                    {
                        loaded_layer_selector.push(this.value);
                    }
                }
            });
            //console.log(selected);
            /* Remove on value */
            var removeItemdeselect = 'on';   
            total_layer_selector = $.grep(total_layer_selector, function(value) {
              return value != removeItemdeselect;
            });
            /* Remove on value */
            update_setting();
           // console.log(view);
        },
        onUncheckAll:function()
        {
            //console.log('onUncheckAll Public');
            /* Get all checkboxes of public */
            for(b=1;b<=layer_data_display_array.length;b++)
            {
                if ((jQuery.inArray(b,total_layer_deselector))==-1)
                {
                   //console.log('124578');
                   total_layer_deselector.push(b); /* Add into deselector */
                }
            }
            /* Get all checkboxes of public */

           for(p=11;p<=14;p++)
           {
                var removeItemdeselect = p;   
                total_layer_selector = $.grep(total_layer_selector, function(value) {
                  return value != removeItemdeselect;
                });
           }


            //console.log('Uncheckall>>>>>>'+total_layer_deselector);

            setting[11].status = 'on';
            setting[11].public_all = 'no';
           $('#client_data_layer').attr("checked",false);
            update_setting();
        
            
        },
        onOpen: function () {
        },
        onClose:function ()
        {
            clear_multi_client_markers_layer_datas(clear_client_callback);
            getLayerDataSection();
            getLoadedLayers();
        },
        onClick:function(view)
        {

            setting[11].status = 'on';
            $('#client_data_layer').attr("checked",true);
            
           // view consists object of label , value , checked
           if(view.checked==true)
           {
                $('#showClientDataDropDownDiv input:checked').each(function()
                {
                    if (((jQuery.inArray(this.value,client_checked_options))==-1))
                    {
                        client_checked_options.push(this.value);/*Remove this tomorrow if error*/
                    }
                });
            if (((jQuery.inArray(view.value,total_layer_selector))==-1) && ((jQuery.inArray(view.value,layer_data_display_array))==-1))
            {
                total_layer_selector.push(view.value);
            }
            if ((jQuery.inArray(view.value,total_layer_deselector))==-1)
            {
                if ((jQuery.inArray(view.value,loaded_layer_selector))==-1)
                {
                    loaded_layer_selector.push(view.value);
                }
            }
           }
           else
           {
            if (((jQuery.inArray(this.value,client_checked_options))!=-1))
            {
                var removeItemdeselect = this.value;   
                client_checked_options = $.grep(client_checked_options, function(value) {
                  return value != removeItemdeselect;
                });
            }
            setting[11].public_all = 'no';
            var removeItem = view.value;   
            total_layer_selector = $.grep(total_layer_selector, function(value) {
              return value != removeItem;
            });



            if ((jQuery.inArray(view.value,total_layer_deselector))==-1)
            {
                total_layer_deselector.push(view.value);
            }

            //console.log('total_layer_selector'+total_layer_selector);
           // console.log('total_layer_deselector'+total_layer_deselector);
            
           }
           setting[11].selected_client_options = client_checked_options;
           update_setting();
        
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
        //console.log('onUncheckAll Public');
        /* Get all checkboxes of public */
        for(b=1;b<=layer_data_display_array.length;b++)
        {
            if ((jQuery.inArray(b,total_layer_deselector))==-1)
            {
               //console.log('124578');
               total_layer_deselector.push(b); /* Add into deselector */
            }
        }
        /* Get all checkboxes of public */

       for(p=1;p<=10;p++)
       {
            var removeItemdeselect = p;   
            total_layer_selector = $.grep(total_layer_selector, function(value) {
              return value != removeItemdeselect;
            });
       }


        //console.log('Uncheckall>>>>>>'+total_layer_deselector);

        setting[10].status = 'off';
        setting[10].public_all = 'no';
        $('#public_data_layer').attr("checked",false);
        update_setting();

        

        /*Get checked values*/


        $('#showPublicDataDropDownDiv').hide();
        $('#public_data_layer').hide();
        $('.main_public_controller').hide();
        $('#showPublicDataDropDownDiv').find('input[type=checkbox]:checked').removeAttr('checked');
        $('#showPublicDataDropDownDiv').find('span').addClass("placeholder");
        $('#showPublicDataDropDownDiv').find('span').html('Select Public Layer Data');

        clear_multi_markers_layer_datas(clear_callback);
        getLayerDataSection();
        getLoadedLayers();
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
        //console.log('onUncheckAll Public');
        /* Get all checkboxes of public */
        for(b=1;b<=layer_data_display_array.length;b++)
        {
            if ((jQuery.inArray(b,total_layer_deselector))==-1)
            {
               //console.log('124578');
               total_layer_deselector.push(b); /* Add into deselector */
            }
        }
        /* Get all checkboxes of public */

       for(p=11;p<=14;p++)
       {
            var removeItemdeselect = p;   
            total_layer_selector = $.grep(total_layer_selector, function(value) {
              return value != removeItemdeselect;
            });
       }


        //console.log('Uncheckall>>>>>>'+total_layer_deselector);

        setting[11].status = 'off';
        setting[11].public_all = 'no';
       $('#client_data_layer').attr("checked",false);
        update_setting();

        

        /*Get checked values*/
        $('#showClientDataDropDownDiv').hide();
        $('.data_layers_drop_down_client').hide();
        $('.main_client_controller').hide();
        $('#showClientDataDropDownDiv').find('input[type=checkbox]:checked').removeAttr('checked');
        $('#showClientDataDropDownDiv').find('span').addClass("placeholder");
        $('#showClientDataDropDownDiv').find('span').html('Select Client Layer Data');
        
        clear_multi_client_markers_layer_datas(clear_client_callback);
        getLayerDataSection();
        getLoadedLayers();
        $('.client_site_lists').hide();
    }
}


function getLoadedLayers()
{
    console.log('loaded_layer_selector'+loaded_layer_selector);
    //console.log(loaded_layer_selector);
    if(loaded_layer_selector.length!=0)
    {
        for(j=0;j<=loaded_layer_selector.length-1;j++)
        {
            if (((jQuery.inArray(loaded_layer_selector[j],loaded_layer_selector))!=-1))
            {
                if(loaded_layer_selector[j]!=1 && loaded_layer_selector[j]!=12)
                {
                    var get_again = eval("dynamic_create_array" + loaded_layer_selector[j]);
                    $('#loader_layer_type').show();
                    for(m=0;m<=get_again.length-1;m++)
                    {
                        get_again[m].setVisible(true);

                        if(m==get_again.length-1)
                        {
                            $('#loader_layer_type').hide();
                        }
                    }
                }
                if(loaded_layer_selector[j]==12)
                {
                    show_post_code();
                }
                if(loaded_layer_selector[j]==1)
                {
                    var get_again = eval("dynamic_create_array" + layer_data_display_array[j]);
                    $('#loader_layer_type').show();
                    for(m=0;m<=get_again.length-1;m++)
                    {
                        get_again[m].setVisible(true);
                         if(m==get_again.length-1)
                        {
                            $('#loader_layer_type').hide();
                        }
                    }
                
                    RT_NSW.setMap(map);
                    RT_NT.setMap(map);
                    RT_QLD.setMap(map);
                    RT_SA.setMap(map);
                    RT_TAS.setMap(map);
                    RT_VIS.setMap(map);
                    RT_WA.setMap(map);
                }
            }
        }
    }
}

function getLayerDataSection()
{
   //console.log('function>>>>getLayerDataSection');
    //console.log('total_layer_selector'+total_layer_selector);
   // console.log('layer_data_display_array'+layer_data_display_array);
    //console.log('total_layer_deselector'+total_layer_deselector);



    if(total_layer_selector.length!=0)
    {
        for(layer=0;layer<=total_layer_selector.length-1;layer++)
        {
            //console.log('>>>>>'+jQuery.inArray( total_layer_selector[layer], layer_data_display_array));
            if ((jQuery.inArray(total_layer_selector[layer],layer_data_display_array))==-1)
            {
                all_selected_layer_value_current.push(total_layer_selector[layer]);
                 //console.log('function>>>>load_multi_kml_data');
                layer_data_display_array.push(total_layer_selector[layer]);
                //var $dynamic_create_layer.layer_type = new array();
                dynamic_create_array = eval("dynamic_create_array" + total_layer_selector[layer] + "=[]"); /* Create dynamic array variable*/
                //console.log(dynamic_create_array);
               
                if(layer==total_layer_selector.length-1)
                {
                    load_multi_kml_data_new(all_selected_layer_value_current);
                }
            }
        }
    }
    else
    {
        clearMapLayers();
    }
}


function load_multi_kml_data_new(layer_values)
{
    /* Check array consists 1 or not*/
    if(jQuery.inArray('1', layer_values) != -1)
    {
        RT_NSW.setMap(map);
        RT_NT.setMap(map);
        RT_QLD.setMap(map);
        RT_SA.setMap(map);
        RT_TAS.setMap(map);
        RT_VIS.setMap(map);
        RT_WA.setMap(map);
    }
    if((jQuery.inArray('12', layer_values)) != -1)
    {
       show_post_code();
    }
    /* Check array consists 1 or not*/


    //console.log(layer_values);
    $('#loader_layer_type').show();
    $.ajax
    ({
     type: "POST",
     url: "ajax/get_layer_data.php",
     dataType: "json",
     data: { layer_values:layer_values},
     success: function(response)
     {
          var html = "";
          var html1 = "";
          var html2 = "";
          if(response.data.length>0) {
          var count=response.data.length-1;
          for (k=0;k<response.data.length;k++)
          {
             var myLatlng = new google.maps.LatLng(response.data[k].latitude,response.data[k].longitude);
              
             var layer_marker_new = new google.maps.Marker({
                    position: myLatlng,
                   // map: map,
                    title: response.data[k].placemarker_name,
                    //icon:  'images/magic.gif'
                    icon:'images/layer_markers/'+response.data[k].placemarker_icon
                });


        google.maps.event.addListener(layer_marker_new, 'visible_changed', function() {
          //console.log('visible_changed triggered');
        });

               layer_marker_new.setMap(map);
              multi_kml_marker_array.push(layer_marker_new);
             // console.log($("#select_public_data option:contains('"+response.data[k].layer_type +"')").val());
              eval("dynamic_create_array" + response.data[k].layer_id).push(layer_marker_new);
              
          google.maps.event.addListener(layer_marker_new, 'click', (function(marker, k) {
                    return function() {
                               var contentString = response.data[k].placemarker_description;
                              infowindow.setContent(contentString);
                              infowindow.open(map, marker);
                    }
          })(layer_marker_new, k));

            /* Grap values for 11 13 14 */
            if(response.data[k].layer_id==11)
            {
                var tag='<a style="cursor:pointer" onclick=setlocation('+response.data[k].latitude+','+response.data[k].longitude+','+k+') >'+response.data[k].placemarker_name+'</a>';
                html += "<li>" + tag + "</li>";
            }

            if(response.data[k].layer_id==13)
            {
                var tag1='<a style="cursor:pointer" onclick=setlocation('+response.data[k].latitude+','+response.data[k].longitude+','+k+') >'+response.data[k].placemarker_name+'</a>';
                html1 += "<li>" + tag1 + "</li>";
            }

            if(response.data[k].layer_id==14)
            {
                var tag2='<a style="cursor:pointer" onclick=setlocation('+response.data[k].latitude+','+response.data[k].longitude+','+k+') >'+response.data[k].placemarker_name+'</a>';
                html2 += "<li>" + tag2 + "</li>";
            }

            /* Grap values for 11 13 14 */

           
              
              if(k==count) {
                   // $('#loader').hide();
               $('#loader_layer_type').hide();
              }
          }


          jQuery.each(total_layer_selector, function(index, item)
          {
            $('.client_site_lists').show();
                if(item==11)
                {
                    $("#navigation").append(html);
                    $('#show_ericsson_sites').show();
                }

                if(item==13)
                {
                    $("#navigation_nbnnwas").append(html1);
                    $('#show_nbnnwas_sites').show();
                }

                if(item==14)
                {
                    $("#navigation_nbnnfas").append(html2);
                    $('#show_nbnnfas_sites').show();
                }
          });


           
         
          } else {
               //$('#loader').hide();
               $('#loader_layer_type').hide();
          }
           
     }
 });
}


function clear_multi_client_markers_layer_datas(clear_client_callback)
{
    //console.log('clear>>>>>'+total_layer_deselector);
    //console.log(total_layer_deselector.length);
    console.log('clearing Public'+total_layer_deselector);
    if(total_layer_deselector.length>0)
    {
        for(l=0;l<=total_layer_deselector.length-1;l++)
        {
            if(total_layer_deselector[l] > 10)
            {
                if(total_layer_deselector[l]!=12)
                {
                    //console.log('i am removing'+total_layer_deselector[l]);
                    //console.log(total_layer_deselector[l]);
                    var my_data_value = eval("dynamic_create_array"+parseInt(total_layer_deselector[l]));
                    //$('#loader_layer_type').show();
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
               
                if(total_layer_deselector[l]==12)
                {
                    //console.log('i am removing'+total_layer_deselector[l]);
                    remove_post_code();
                }    
            }
            //remove_array_values(total_layer_deselector[l]);
        }
    }
    clear_client_callback(total_layer_deselector);
}




function clear_multi_markers_layer_datas(callback)
{
    //console.log('clear>>>>>'+total_layer_deselector);
    //console.log(total_layer_deselector.length);
    console.log('clearing Public'+total_layer_deselector);
    if(total_layer_deselector.length>0)
    {
        for(l=0;l<=total_layer_deselector.length-1;l++)
        {
            if(total_layer_deselector[l] <= 10)
            {
                if(total_layer_deselector[l]!=12 && total_layer_deselector[l]!=1)
                {
                    //console.log('i am removing'+total_layer_deselector[l]);
                    //console.log(total_layer_deselector[l]);
                    var my_data_value = eval("dynamic_create_array"+parseInt(total_layer_deselector[l]));
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
                if(total_layer_deselector[l]==1)
                {
                    //console.log('i am removing'+total_layer_deselector[l]);
                    var my_data_value = eval("dynamic_create_array"+parseInt(total_layer_deselector[l]));
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
                
                    RT_NSW.setMap(null);
                    RT_NT.setMap(null);
                    RT_QLD.setMap(null);
                    RT_SA.setMap(null);
                    RT_TAS.setMap(null);
                    RT_VIS.setMap(null);
                    RT_WA.setMap(null);
                }
            }
            //remove_array_values(total_layer_deselector[l]);
        }
    }
    callback(total_layer_deselector);
}



function clear_callback(total_layer_deselector_var) {
    // I'm the callback
    //console.log('i am callback');
    var previous_data_len = total_layer_deselector_var.length;
    var previous_data = total_layer_deselector_var.length-1;
    if(previous_data_len!=0)
    {
        for(l=0;l<=previous_data;l++)
        {
            //console.log('for loop');
             var removeItemdeselect = total_layer_deselector_var[l];
             //console.log(removeItemdeselect); 
             //console.log(total_layer_deselector);
            if(removeItemdeselect<=10)
            {
                if ((jQuery.inArray(removeItemdeselect,total_layer_deselector)) != -1)
                {
                   
                    //console.log(removeItemdeselect);  
                    total_layer_deselector = $.grep(total_layer_deselector, function(value) {
                      return value != removeItemdeselect;
                    });

                    loaded_layer_selector = $.grep(loaded_layer_selector, function(value) {
                      return value != removeItemdeselect;
                    });
                }
            }
        }
    }
}

function clear_client_callback(total_layer_deselector_var) {
    // I'm the callback
    //console.log('i am callback');
    var previous_data_len = total_layer_deselector_var.length;
    var previous_data = total_layer_deselector_var.length-1;
    if(previous_data_len!=0)
    {
        for(l=0;l<=previous_data;l++)
        {
            //console.log('for loop');
             var removeItemdeselect = total_layer_deselector_var[l];
             //console.log(removeItemdeselect); 
             //console.log(total_layer_deselector);
             if(removeItemdeselect>10)
             {
                if ((jQuery.inArray(removeItemdeselect,total_layer_deselector)) != -1)
                {
                   
                    //console.log(removeItemdeselect);  
                    total_layer_deselector = $.grep(total_layer_deselector, function(value) {
                      return value != removeItemdeselect;
                    });

                    loaded_layer_selector = $.grep(loaded_layer_selector, function(value) {
                      return value != removeItemdeselect;
                    });
                }
            }
        }
    }
}

