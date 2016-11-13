$(document).ready(function()
{
  /*Forecast model*/
  generateChartAjax();
  $('.forecast_storm_pause').hide();
  $('#forecast_model').val('');
  $('#forecasts_types_select').val('');
  $('#forecast_module_datetime').val('');
  $('#forecast_module_opacity').val('1');
  $('#forstorm_speed_control').val('4000');
  $("#forstorm_speed_control").change(function()
  {
      timeInterval=this.value;
      clearInterval(intervaltime);
      intervaltime=setInterval(function() { 
    //console.log('call');
                    shownextimage(false);
                console.log(timeInterval);
                console.log('image change');
    //show_all_overlay();
  
      },timeInterval);

    });
  /*Forecast model*/
  
  $('#map_options_selector').val('ROADMAP');
  $('#showdistricts').attr("checked",false);
  $('#showriverbasins').attr("checked",false);
  
  /* Radar Setting */
  $('#radar_display_images').attr("checked",true);
  $('#optRadar').attr("checked",false);
  $('#loop_speed_select').val("1000");
  if(map_observation==2)
  $('#select_map_range').val('256');
  else
  $('#select_map_range').val('512');
  $('.radar_play').show();
  $('.radar_pause').hide();
  $('#radar_opacity').val('1');
  /* Radar Setting */
  
  
  //$('#wx_radar_sites').attr("checked",true);
  
  /* Forecast severe warning */
  $(".forecastsevere").attr("checked",false);
  $("#forecast_severe_weather_option").val('1');
  /* Forecast severe warning */
  
  /* Date custom Convert */
  Date.fromISO= function(s){
            var day, tz,
            rx=/^(\d{4}\-\d\d\-\d\d([tT ][\d:\.]*)?)([zZ]|([+\-])(\d\d):(\d\d))?$/,
            p= rx.exec(s) || [];
            if(p[1]){
                day= p[1].split(/\D/);
                for(var i= 0, L= day.length; i<L; i++){
                    day[i]= parseInt(day[i], 10) || 0;
                };
                day[1]-= 1;
                day= new Date(Date.UTC.apply(Date, day));
                if(!day.getDate()) return NaN;
                if(p[5]){
                    tz= (parseInt(p[5], 10)*60);
                    if(p[6]) tz+= parseInt(p[6], 10);
                    if(p[4]== '+') tz*= -1;
                    if(tz) day.setUTCMinutes(day.getUTCMinutes()+ tz);
                }
                return day;
            }
            return NaN;
        }
  /* Date custom Convert */
  
  $(".weather").attr("checked",true);
  var $radios = $('input:radio[name=warning_type]');
  $radios.filter('[value=2]').prop('checked', true);
  $('#warning_days').val('48');

  var screen_width = $(window).width();
  var resize_map_width = parseInt(screen_width) - parseInt(385);
  
  

  /*if($('#checkbox-1-all').attr("checked"))
  {
     $('.warning_checkboxes').attr("checked",true);
  }*/
  
  $('input[name="forecast_warning_option[]"]').each(function() {
                        $(this).attr("checked",false);
  });

  $(".wx_radar_sites").change(function()
  {
      if($(this).attr("checked"))
        {
	   setting[7].status='on';
	} else {
	   setting[7].status='off';
	}
	update_setting();
      toggleRadarMarkers();
  });
  
     /* show control panel */
    $('#map-canvas').css({"width":resize_map_width+'px'});
    $('#navmenu').hide();
    $('#Popcontainer').show();
    var height=$(window.top).height();
    $('#Popcontainer').height(height-20);
    $('#Popcontainer').css({'overflow-y':"scroll"});
    //triggerMap(map);
    
     $('#layers').val('');
    
    $('.navigation').height(height-20);

    $("#time_display").hover(function(){
    $(".timezone").html(timeZone());
    $(".timezone").show();
    $("#time_display").css({"width":"auto"});
    },function(){
     $(".timezone").hide();
    });
    
    $(".rainfall").attr("checked",false);
    $(".rainfall_types").val('1hr');
    
    $('#additional_layers').val('');

  /* Observation Section */
    
    $(".observation_checkbox").change(function()
    {
        if($(this).attr("checked"))
        {
	            setting[1].status='on';
		    //clearInterval(observation_timeout);
                    $('.forecast_checkbox').attr("checked",false);
                    $(".forecast_checkbox").trigger("change");
                    setting[2].status='off';
                    if($(".observation_option1").attr("checked"))
                    {
                        load_observation_Capitals_only();
                    } else if($(".observation_option2").attr("checked")) {
                        load_observation_allCities();
                    } else {
                        $('.observation_option1').attr("checked",true);
                        load_observation_Capitals_only();
                    }
               
            
        } else {
	    setting[1].status='off';
	    $('.observation_option1').attr("checked" , false);
            $('.observation_option2').attr("checked" , false);
            deleteMarkersFromMap();
            clearInterval(observation_timeout);
	    observation_zoom=0;
        }
	 update_setting();
    });
    
    $(".observation_option1").change(function()
    {
        if($(this).attr("checked"))
        {
	    setting[1].observation_type=1;
	    $('.observation_option2').attr("checked",false);
            $(".observation_option2").trigger("change");
            if($(".observation_checkbox").attr("checked")) {
                /* deleteMarkersFromMap();
                 clearInterval(observation_timeout);
                  load_observation_content(1);
                 observation_timeout=setInterval(function() {
                     deleteMarkersFromMap();
                    load_observation_content(1);
                },radartimeinterval);*/
                
                load_observation_Capitals_only();
            }
        }
        else if(!$(this).attr("checked"))
        {
          deleteMarkersFromMap();
            clearInterval(observation_timeout);
      observation_zoom=0;

        }

         else if(!$(".observation_option2").attr("checked")) {
            //deleteMarkersFromMap();
            $('.observation_checkbox').attr("checked",false);
	    setting[1].observation_type=1;
	   
	    
        }
	 update_setting();
    });

    $(".observation_option2").change(function()
    {
        if($(this).attr("checked"))
        {
	    setting[1].observation_type=2;
	    $('.observation_option1').attr("checked",false);
            $(".observation_option1").trigger("change");
            if($(".observation_checkbox").attr("checked")) {
                 /*deleteMarkersFromMap();
                 clearInterval(observation_timeout);
                 load_observation_content(2);
                 observation_timeout=setInterval(function() {
                     deleteMarkersFromMap();
                    load_observation_content(2);
                 },radartimeinterval);*/
                  load_observation_allCities();
            }
        }
        else if(!$(this).attr("checked"))
        {
          deleteMarkersFromMap();
            clearInterval(observation_timeout);
      observation_zoom=0;

        }
         else if(!$(".observation_option1").attr("checked")) {
            //deleteMarkersFromMap();
            $('.observation_checkbox').attr("checked",false);
	    setting[1].observation_type=1;
	    
        }
	update_setting();
    });
    
    /* Observation Section */
   
   /* Rafar section */
    
    $("#radar_display_images").change(function()
    {
       
	  if($(this).attr("checked"))
	  {
	       
	      setting[0].status='on';
	      update_setting(); 
	       
	      if($(".weather").attr("checked"))
		$('#time_display').css("left","225px");
	      else
		$('#time_display').css("left","30px");
		
	      $('#time_display').show();
	      $('#optRadar').attr("checked",false);
	      clearInterval(radarinterval);
	      loadRadar(true);
	      radarinterval=setInterval(function() {
		//removeAllRadar();
		loadRadar(false);
	      },radartimeinterval);  
	  } else  {
	     setting[0].status='off';
	      update_setting();
	     $('#optRadar').attr("checked",false);
	     $('.radar_play').show();
	     $('.radar_pause').hide();
	     $('#time_display').hide();
	     removeAllRadar();
	     clearInterval(radarinterval);
	     RadarTimeStop();
	  }
       
    });
    
    $('#optRadar').change(function()
    {
        if($("#radar_display_images").attr("checked")) {
	  if($(this).attr("checked"))
	  {
	      setting[0].loop='on';
	      update_setting();
	      $('.radar_pause').show();
	      $('.radar_play').hide();
	     loopstart();
	     
	  } else  {
	    setting[0].loop='off';
	    update_setting();
	     stopLoop();
	     $('.radar_pause').hide();
	      $('.radar_play').show();
	  }
	}
    });
    
    $('#pause').click(function()
    {
          if($("#radar_display_images").attr("checked")) {
	  setting[0].loop='off';
	  update_setting();
	  $('#optRadar').attr("checked",false);
	  $('.radar_play').show();
	  $('.radar_pause').hide();
	  stopLoop();
       }
    });
    
    $('#play').click(function()
    {
       if($("#radar_display_images").attr("checked")) {
	  setting[0].loop='on';
	  update_setting();
	  $('#optRadar').attr("checked",true);
	  $('.radar_pause').show();
	  $('.radar_play').hide();
	  loopstart();
       }
    });
    
     $('#radar_opacity').on('change',function()
    {
      setting[0].opacity=this.value;
      update_setting();
      radar_opacity=this.value;
      if($("#radar_display_images").attr("checked")) {
        ChangeRadarOpacity();
      }
    });
    
   $("#radar_opacity").bind("keyup", function(){
     setting[0].opacity=this.value;
      update_setting();
     radar_opacity=this.value;
      if($("#radar_display_images").attr("checked")) {
        ChangeRadarOpacity();
      }
    });
    
    /* Radar section */
    
     /* weather section */
    $(".weather").change(function()
    {
        
        if($(this).attr("checked"))
        {
	    setting[3].status='on';
	    remove_warning();
           $('input[name="warning_option[]"]').each(function() {
              //console.log(this.value);
              $(this).attr("checked",true);
              
            });
            
            weather_options.length=0;
            //alert(weather_options.length);
            if(weather_options.length==0) {
               $('.warning_all').attr('checked',true);
               weather_options.push('all');
	       setting[3].warning_types='all';
            }
            
            /*$('input[name="warning_type"]').each(function() {
               if(this.value==1) 
               $(this).attr("checked",true);
               else
               $(this).attr("checked",false);
            });*/
            
            var $radios = $('input:radio[name=warning_type]');
            $radios.filter('[value=1]').prop('checked', true);
            
	    setting[3].warning_type_option='current';
            load_warning_content(1,weather_options,'');
            
            var homeControlDiv = document.createElement('div');
	    var homeControl = new WarningLegend(homeControlDiv, map); 
	    homeControlDiv.index = 1;
	    map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(homeControlDiv);
            
            $('#time_display').css("left","225px");
	     $('#forecast_codes_div').css("left","225px");
            $("#warning_report_generation").show();
           //alert(JSON.stringify(vals));
          
        } else {
	    setting[3].status='off';
            remove_warning();
	    remove_warning_legend();
	    $('#time_display').css("left","30px");
	    $('#forecast_codes_div').css("left","30px");
	    $("#warning_report_generation").hide();
        }
	update_setting();
    });
    
    /* warning option change event */
    var $checkes = $('input:checkbox[name="warning_option[]"]').change(function () {
      if($(".weather").attr("checked")) {
        remove_warning();
	if(this.value=='all' && $(this).attr("checked")) {
	   setting[3].warning_types='all';
	   weather_options.push('all');
	    $('input[name="warning_option[]"]').each(function() {
	      $(this).attr('checked',true);
	    });
	} else {
	  if(this.value=='all') {
	     $('input[name="warning_option[]"]').each(function() {
	      $(this).attr('checked',false);
	    });
	     weather_options.length=0;
	  } else {
	    var $radios = $('input:checkbox[name="warning_option[]"]');
	    $radios.filter('[value=all]').prop('checked', false);
	    weather_options = $checkes.filter(':checked').map(function () {
	      return this.value;
	    }).get();
	  }
	} 
         
	 if(weather_options.length==0) {
              //$('.weather').attr('checked',false);
	      setting[3].warning_types='';
          } 
          else
          {
         
         if($(".weather").attr("checked")) {
            $('input[name="warning_type"]:checked').each(function() {
              //console.log(this.value);
              var warning_type=this.value;
              //alert(warning_type);
              if(warning_type=='1') { 
                load_warning_content(1,weather_options,'');
		setting[3].warning_type_option='current';
              } else if(warning_type=='2') {
		var days=$('#warning_days').val();
		setting[3].warning_type_option='expired';
		setting[3].expired_warning_last=days;
                load_warning_content(2,weather_options,days);
              }
	      setting[3].warning_types=weather_options;
	      
            });
           
        }
}
         //alert(JSON.stringify(vals));
	}
	update_setting();
     });
    
    $(".warning_type").change(function()
    {
        if($(".weather").attr("checked")) {
            remove_warning();
            //alert(this.value);
            weather_options = $checkes.filter(':checked').map(function () {
             return this.value;
            }).get();
            
            if(weather_options.length==0) {
               $('.warning_all').attr('checked',true);
               weather_options.push('all');
	       setting[3].warning_types='all';
            }
            var warning_type=(this).value;
	    if(warning_type=='1') {
	        setting[3].warning_type_option='current';
		load_warning_content(1,weather_options,'');
            } else if(warning_type=='2') {
	       var days=$('#warning_days').val();
	       setting[3].warning_type_option='expired';
		setting[3].expired_warning_last=days;
               load_warning_content(2,weather_options,days);
            }
            
        }   
       update_setting();
    });
    
    $('#warning_days').on('change',function()
    {
      if($(".weather").attr("checked")) {
       var $radios = $('input:radio[name=warning_type]');
       //$radios.filter('[value=1]').prop('checked', true);
       if($radios.filter('[value=2]').prop('checked')) {
        remove_warning();
	setting[3].expired_warning_last=this.value;
        load_warning_content(2,weather_options,this.value);
       }
      }
      update_setting();
    });
    
    
    
    /* weather section */
    
    /* forecast thunderstorm */
    
    $(".forecastsevere").change(function()
    {
        if($(this).attr("checked"))
        {
	  setting[4].status='on';
	   remove_forecastevere_warning();
	   
	    $('input[name="forecast_warning_option[]"]').each(function() {
              $(this).attr("checked",true);
            });
	    
	    
	     var $radios = $('input:checkbox[name="forecast_warning_option[]"]');
	    forecast_weather_options = $radios.filter(':checked').map(function () {
	      return this.value;
	    }).get();
            
	    //forecast_weather_options.length=0;
            if(forecast_weather_options.length==0) {
               //forecast_weather_options.push('all');
	       setting[4].warning_types='';
            } else {
		$options=$('#forecast_severe_weather_option').val();
		load_severeForecast_content(forecast_weather_options,$options);
		setting[4].warning_types=forecast_weather_options;
	    }
	    
	    $('#forecast_codes_div').show();
		if($(".weather").attr("checked")) {
		  $('#time_display').css("left","225px");
		  $('#forecast_codes_div').css("left","225px");
		} else {
		  $('#time_display').css("left","30px");
		  $('#forecast_codes_div').css("left","30px");
		}
		
	    clearInterval(forecast_interval);
		  forecast_interval=setInterval(function() {
		  update_forecast_severe();
		  },forecast_intervalTime);  
	   
	   
	} else {
	    setting[4].status='off';
	    $('#forecast_codes_div').hide();
	    remove_forecastevere_warning();
	    /* $('input[name="forecast_warning_option[]"]').each(function() {
              $(this).attr("checked",false);
            });*/
        }
	update_setting();
    });
    
    
     /* warning option change event */
    var $forecast_checkes = $('input:checkbox[name="forecast_warning_option[]"]').change(function () {
          if($(".forecastsevere").attr("checked")) {
            remove_forecastevere_warning();
	
	    var $radios = $('input:checkbox[name="forecast_warning_option[]"]');
	    forecast_weather_options = $radios.filter(':checked').map(function () {
	      return this.value;
	    }).get();
	  
	   //forecast_weather_options.length=0;
            if(forecast_weather_options.length==0) {
               //forecast_weather_options.push('all');
	       setting[4].warning_types='';
            } else {
		$options=$('#forecast_severe_weather_option').val();
		load_severeForecast_content(forecast_weather_options,$options);
		setting[4].warning_types=forecast_weather_options;
	    }
         
	}
	update_setting();
       
     });
    
    $("#forecast_severe_weather_option").change(function()
    {
        if($(".forecastsevere").attr("checked")) {
            remove_forecastevere_warning();
	    setting[4].forecast_type=this.value;
            //alert(this.value);
            forecast_weather_options = $forecast_checkes.filter(':checked').map(function () {
             return this.value;
            }).get();
	    //console.log(weather_options);
            if(forecast_weather_options.length==0) {
               //forecast_weather_options.push('all');
            } else {
		
		load_severeForecast_content(forecast_weather_options,this.value);
	    }
            
        }
	update_setting();
    });
    
    
    /* forecast thunderstorm */
    $(".forecast_checkbox").change(function()
    {
        if($(this).attr("checked"))
        {
	    //setting[2].status='on';
	    //setting[1].status='off';
	    $('.observation_checkbox').attr("checked",false);
            $(".observation_checkbox").trigger("change");
        } else {
	   //setting[2].status='off';
	}
	 //update_setting();
    });

   /* forcecast section */
   
    $('#forecast_module_opacity').on('change',function()
    {
      setting[6].opacity=this.value;
      update_setting();
      opacity=this.value;
      change_opacity();
    });
   
   $("#forecast_module_opacity").bind("keyup", function(){
     setting[6].opacity=this.value;
      update_setting();
      opacity=this.value;
      change_opacity();
    });
   
   /* Rainfall Gauges */
   
   $(".rainfall").change(function()
    {
        if($(this).attr("checked"))
        {
	    show_rainfall();
	    clearInterval(rainfall_timeout);
                 rainfall_timeout=setInterval(function() {
                     update_rainfall();
	    },radartimeinterval);
	    $('#rainfall_gauges_codes_div').show();
	} else {
	    remove_all_rainfall();
	    clearInterval(rainfall_timeout);
	     $('#rainfall_gauges_codes_div').hide();
	    setting[8].status='off';
        }
	update_setting();
    });
   
    $('#rainfall_types').on('change',function()
    {
       if($(".rainfall").attr("checked"))
        {
	   show_rainfall();
	   
	}

    });
   
   /* Rainfall Gauges */
   
    

    var CurrentDate = new Date().dateFormat('M  d , Y h a');
    $('#datetimepicker').val(CurrentDate);
    //$('#layers').val(1);

    //alert(CurrentDate);
    $('#datetimepicker').datetimepicker({
        formatTime:'h a',
        formatDate:'d.m.Y',
        //defaultDate:'17.10.2014', // it's my birthday
        //defaultTime:'12 am',
        //defaultDate:CurrentDate,
        format:'M  d , Y h a',
        closeOnDateSelect:true
    });

    /* control panel click */
    $('#menu').live('click', function(e) {  
       //alert(1);
        //var height = screen.width;
	$('#map-canvas').css({"width":resize_map_width+'px'});
	
        $('#navmenu').hide();
        $('#Popcontainer').show();
        var height=$(window.top).height();
        $('#Popcontainer').height(height-20);
        //$('#Popcontainer').css({'overflow-y: scroll;'});
        $('#Popcontainer').css({'overflow-y':"scroll"});
	triggerMap(map);
	//setTimeout(function() { google.maps.event.trigger(map, 'resize');},2000);
	
    });
    
    /* control panel hide click */
    $('#closemenu').live('click', function(e) {  
        //alert(1);
	$('#map-canvas').css({"width":"100%"});
        $('#navmenu').show();
        $('#Popcontainer').hide();
	triggerMap(map);
    });
    
    /* function for trigger map rsize */
    function triggerMap(m) {
      x = m.getZoom();
      c = m.getCenter();
      google.maps.event.trigger(m, 'resize');
      m.setZoom(x);
      m.setCenter(c);
    };
    
    /* other additional layer */
    $('#additional_layers').on('change',function()
    {
      remove_post_code();
      var sel_layer = this.value;
      if(sel_layer!=='') {
        show_post_code();
      }
    });
    
 //  layers change event
    $('#layers').on('change',function()
    {

      selected_layer_value_current = this.value;
      if(selected_layer_value_current=='')
      {
        //$('#warning_report_generation').hide();
      }
      else
      {
        //$('#warning_report_generation').show();
      }

      $('#loader').show();
      clearMapLayers();
      var layer_value=this.value;
      if(layer_value!=='' && layer_value!==4) {
	  setting[5].layers=layer_value;
	  load_kml_data(layer_value);
	   if(layer_value=='11') {
	    $('#layer_button').show();
	    $('#show_sites').show();
	  }
	  if(layer_value==1) {
	    RT_NSW.setMap(map);
	    RT_NT.setMap(map);
	    RT_QLD.setMap(map);
	    RT_SA.setMap(map);
	    RT_TAS.setMap(map);
	    RT_VIS.setMap(map);
	    RT_WA.setMap(map);
	  }
      } else {
	$('#loader').hide();
      }
      update_setting();
      
      // Remove all set layers //
      //clearMapLayers();
      
      // set layers according to selection
      /*if(layer_value=='1') {
	  update_warning();
          RT_NSW.setMap(map);
          RT_NT.setMap(map);
          RT_QLD.setMap(map);
          RT_SA.setMap(map);
          RT_TAS.setMap(map);
          RT_VIS.setMap(map);
          RT_WA.setMap(map);
      } else if(layer_value=='2') {
        NTE_AREA.setMap(map);
        NTE_POINT.setMap(map);
      } else if(layer_value=='3') {
	TS_BTD.setMap(map);
	AM_BTD.setMap(map);
	DR_BTD.setMap(map);
	DT_BTD.setMap(map); 
	FM_BTD.setMap(map);
      } else if(layer_value=='4') {
        
      } else if(layer_value=='5') {
        NETSDLayer1.setMap(map);
	kml_hide_loader(NETSDLayer1);
      } else if(layer_value=='6') {
        NMATLayer1.setMap(map);
	kml_hide_loader(NMATLayer1);
      } else if(layer_value=='7') {
        NPSLayer1.setMap(map);
        NPSLayer2.setMap(map);
        NPSLayer3.setMap(map);
        NPSLayer4.setMap(map);
        NPSLayer5.setMap(map);
      }
      else if(layer_value=='8') {
	PortsLayer1.setMap(map);
	kml_hide_loader(PortsLayer1);
      } else if(layer_value=='9') {
	NOMNOMLayer1.setMap(map);
	kml_hide_loader(NOMNOMLayer1);
      } else if(layer_value=='10') {
	NMPSLayer1.setMap(map);
	kml_hide_loader(NMPSLayer1);
      } else if(layer_value=='11') {
	$('#layer_button').show();
	$('#show_sites').show();
	load_kml_data(1);
	//Ericsson.setMap(map);
	//kml_hide_loader(Ericsson);
      } */
      //setTimeout(function() {$('#loader').hide();  },10000);
    });


});



    function clearMapLayers()
    {
	  /*RT_NSW.setMap(null);
	  RT_NT.setMap(null);
	  RT_QLD.setMap(null);
	  RT_SA.setMap(null);
	  RT_TAS.setMap(null);
	  RT_VIS.setMap(null);
	  RT_WA.setMap(null);
	  
	  //
	  NTE_AREA.setMap(null);
	  NTE_POINT.setMap(null);
    
	  TS_BTD.setMap(null);
	  AM_BTD.setMap(null);
	  DR_BTD.setMap(null);
	  DT_BTD.setMap(null); 
	  FM_BTD.setMap(null);
	  
	  NETSDLayer1.setMap(null);
	  
	  NMATLayer1.setMap(null);
	  
	  NPSLayer1.setMap(null);
	  NPSLayer2.setMap(null);
	  NPSLayer3.setMap(null);
	  NPSLayer4.setMap(null);
	  NPSLayer5.setMap(null);
	  
	  PortsLayer1.setMap(null);
    
	  NOMNOMLayer1.setMap(null);
	  
	  NMPSLayer1.setMap(null);
	  Ericsson.setMap(null);*/
	    setting[5].layers='';
	    RT_NSW.setMap(null);
	    RT_NT.setMap(null);
	    RT_QLD.setMap(null);
	    RT_SA.setMap(null);
	    RT_TAS.setMap(null);
	    RT_VIS.setMap(null);
	    RT_WA.setMap(null);
	  
	  $('.navigation').hide();
	  $('#show_sites').hide();
          $('#hide_sites').hide();
	  $('#layer_button').hide();
	  map.setCenter(myCenter);
	  
	  if(kml_markes.length>0) {
	    for(i=0;i<kml_markes.length;i++) {
	      kml_markes[i].setMap(null);
	    }
	  }
    }


    function kml_hide_loader(layer) {
       /* map bound change event */
	google.maps.event.addListener(layer, "metadata_changed", function() {
		//console.debug("metadata_changed");
		 $('#loader').hide();
	});
    }
    
     if(typeof String.prototype.trim !== 'function') {
	String.prototype.trim = function() {
	  return this.replace(/^\s+|\s+$/g, ''); 
	}
    }
    
    if (!window.console) {var console = {};}
    if (!console.log) {console.log = function() {};}
