<html>
<head>
	<!--<title>Assets Reports</title>-->
	<script type="text/javascript"  src="https://maps.googleapis.com/maps/api/js?libraries=geometry"></script>
<script src="../js/jquery-1.7.2.js"></script>
<style type="text/css">
  
.TableContainer
  {
    clear:both;
    width:100%;
    border:1px solid #368ee0
  }
  .TableContainer .title
  {
    clear:both;
    background:#368ee0;
    color:#FFF;
    font-size:16px;
    padding:10px 10px;
    font-weight:bold;
  }
  .TableContainer .TableBody
  {
    padding:10px 10px;
    margin-top:10px;
    padding-bottom:1px
  }
  .TableContainer .TableBody ul
  {
    margin:15px 5px;
    list-style:none;
    border:1px solid #dddddd;
    font-size:14px;
    color:#111111;
    background:#e7f1fb;
    padding: 0px !important;
  }
  .TableContainer .TableBody span
  {
    font-size:17px;
    color:#368ee0;
    margin-left:8px;
  }
  .TableContainer .TableBody li
  {
    padding:7px 10px;
    line-height:28px;
    border-bottom:1px solid #ddd;
  }
  .TableContainer .TableBody li:last-child
  {
    border-bottom:0;
  }
  .icon
  {
    display: inline-block;
    font-size: inherit;
    margin-right:5px;
  }
  #loader {
			height: 100%;
			opacity: 0.6;
			background:url(../images/loading2.gif) no-repeat scroll 50% 50% rgba(255, 255, 255, 0.8);
                        left: 0;
			position: absolute;
			top: 0;
			width: 100%;
			z-index: 1000;
		}
</style>
<script type="text/javascript">
    var polygon_complete_lat_longs = [];

$(document).ready(function()
{
  /*Forecast model*/
  display_range_output();


});

   function exportDataToCSV()
   {
       $('#loader').show();
      var export_url="../ajax/export_data_output.php";
      var mydata = 'layer_data='+polygon_complete_lat_longs;
      //console.log(mydata);
      $.ajax
      ({
        type: "POST",
        url: export_url,
        data: JSON.stringify(polygon_complete_lat_longs),
        dataType: "json",
        success: function(response) 
        { 
          //var my_data = JSON.stringify(response);
          //var url = "../ajax/export_data_csv.php?data="+my_data;
          //window.location.href=url;
           $('#loader').hide();
          window.location.href='../csv/csvfile.csv';
        }
      });

   }


function display_range_output()
{
  //console.log(opener.latlongarray);
  var myhtml1='';

  var display_polygon_marker_array = [];
  /*Blanl current html*/
  $('#display_markers_point_div').html('');
  /**/
    $('#loader').show();
    var complete_lat_longs = [];
    var myLatlngnew;
    var current_image_name_path="../images/layer_markers/";
    position_url="../ajax/display_newpoints.php";
   // console.log(opener.latlongarray);
    for(l=0 ; l<opener.latlongarray.length;l++)
    {
       // console.log(latlongarray[l].polygonPrimaryKey);
        var mydata = 'selected_layer_value_current='+opener.getSelectBoxValue()+'&polygonPrimaryKey='+opener.latlongarray[l].polygonPrimaryKey+'&polygon_number='+l+'&minLat='+opener.latlongarray[l].maxLat+'&minLong='+opener.latlongarray[l].minLong+'&maxLat='+opener.latlongarray[l].minLat+'&maxLong='+opener.latlongarray[l].maxLong;
        $.ajax
        ({
            type: "POST",
            url: position_url,
            dataType: "json",
            data: mydata,
            async: false,
            success: function(response) 
            { 
              var getSelectedValue = opener.getSelectBoxText();
              /*var getLayerSelectedValue = parseInt(opener.getSelectBoxValue());
              if(getLayerSelectedValue==1)
              {
                current_image_name="airoplane.png";
              }
              else if(getLayerSelectedValue==2)
              {
                current_image_name="airoplane.png";
               
              }
              else if(getLayerSelectedValue==3)
              {
                current_image_name="airoplane.png";
             
              }
              else if(getLayerSelectedValue==4)
              {
                current_image_name="airoplane.png";
               
              }
              else if(getLayerSelectedValue==5)
              {
                current_image_name="airoplane.png";
              
              }
              else if(getLayerSelectedValue==6)
              {
                current_image_name="airoplane.png";
              
              }
              else if(getLayerSelectedValue==7)
              {
                current_image_name="airoplane.png";

                
              }
              else if(getLayerSelectedValue==8)
              {
                current_image_name="airoplane.png";

                
              }
              else if(getLayerSelectedValue==9)
              {
                current_image_name="airoplane.png";

                
              } else {
                current_image_name="airoplane.png";
              }*/
           //   alert(getLayerSelectedValue);
                
                if(response.data.length>0) 
                {
                    myhtml1 = '<div id="TableContainer'+l+'" class="TableContainer"></div>';
                    $('#display_markers_point_div').append(myhtml1);
					var polygon_layers=[];
                                        var flag=false;
                    for (i=0;i<response.data.length;i++)
                    {
                        var myLatlng = new google.maps.LatLng(response.data[i].latitude,response.data[i].longitude);
                        var present_polygon = opener.polygonMarkerArray[response.polygon_number];
                        if(google.maps.geometry.poly.containsLocation(myLatlng, present_polygon) == true)
                        {
                            index = opener.indexOfPolygons.call(display_polygon_marker_array, response.polygon_number);
                             var ele_id=response.polygon_number+response.data[i].layer_type.split(' ').join('_');
                             //console.log(ele_id+response.data[i].placemarker_name);
                            if(index<0)
                            {
                               polygon_layers=[];
                              //if(response.data[i].layer_type=='') {
                                //current_image_name="airoplane.png";
                                //getSelectedValue=response.data[i].layer_type;
                              //}
                                  var myhtml3 = '<div class="title">'+response.polygon_subject+'</div><div id="TableBody'+l+'" class="TableBody"></div>';
                                                         
                                 $('#TableContainer'+l).append(myhtml3);
                            }
                            //console.log(response.polygon_number);
							index1 = opener.indexOfPolygons.call(polygon_layers, response.data[i].layer_type);
							//console.log(polygon_type);
							if(index1<0)
							{
                                                            
                                                            current_image_name=get_image(response.data[i].layer_type);
                                                                
                                                            var  myhtml4 = '<span><div class="icon"><img src='+current_image_name_path+current_image_name+'  width="20" height="20" align="texttop" /></div>'+response.data[i].layer_type+'</span><ul id="'+ele_id+'">';
                                                                 
                                                                myhtml4 = myhtml4+'<li>'+response.data[i].placemarker_name+'</li>';
								polygon_layers.push(response.data[i].layer_type);
                                                                myhtml4 = myhtml4+'</ul>';
                                                                 $('#TableBody'+l).append(myhtml4);
							} else {
                                                                var myhtml8 = '<li>'+response.data[i].placemarker_name+'</li>';
								$('#'+ele_id).append(myhtml8);
                                                        }
							
                            
							display_polygon_marker_array.push(response.polygon_number);

                            var newElements = {};
                            newElements['polygon_subject'] = response.polygon_subject;
                            newElements['polygon_number'] = response.polygon_number;
                            newElements['placemarker_name'] = response.data[i].placemarker_name;
                            newElements['placemarker_icon'] = response.data[i].placemarker_icon;
                            newElements['layer_type'] = response.data[i].layer_type;
                            polygon_complete_lat_longs.push(newElements);

                            /* */
                        }                       
                    }
                     //console.log(polygon_complete_lat_longs);
                            //myhtml1 = myhtml1+'</div></div>';
                }
                            //$('#display_markers_point_div').append(myhtml1);
            }
        });

        if(l==opener.latlongarray.length-1)
        {
         //console.log('1231');
         $('#loader').hide();
         $('#page_options').show();
         $('#display_markers_point_div').show();
        }


    }
}

function get_image(layer_name) {
    var current_image_name='';
             
             switch (layer_name) {
                case 'Au Railway Tracks':
                    current_image_name="railway.png";
                    break;
                case 'Au Telephone Exchanges':
                    current_image_name="cellsite.png";
                    break;
                case 'Broadcast Transmitter':
                    current_image_name="mobilephonetower.png";
                    break;
                case 'Au Electricity Transmission Substations':
                    current_image_name="powerlinepeople.png";
                    break;
                case 'AU Major Airport Terminals':
                    current_image_name="airoplane.png";
                    break;
                case 'Au Petrol Stations':
                    current_image_name="petrol.png";
                    break;
                case 'Au Ports':
                    current_image_name="boatcrane.png";
                    break;
                case 'Au Operating Mines':
                    current_image_name="mine.png";
                    break;
                case 'Au Major power Stations':
                    current_image_name="power.png";
                    break;
                case 'Ericsson Sites':
                    current_image_name="green_marker.png";
                    break;
                default:
                    current_image_name = "airoplane.png";
            } 
             
        return  current_image_name;    
    
}


</script>
</head>
<body>
    <div id="loader" style="display:none;"></div> 
    <div id="page_options" style="margin-bottom:10px;display:none;">
          <div style="float:left;">
            <input type=button value=Print onClick="opener.print_page();">
          </div>
          <div id="export" style="float:left;">
            <input type=button value="Save" onClick="exportDataToCSV();">
           </div>
          <div style=clear:left;></div></div>
         <div id="display_markers_point_div" style="display: none;"></div>
</body>