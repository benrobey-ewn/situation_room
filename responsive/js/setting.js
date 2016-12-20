    /* setting .js  */

    var setting=[];

    /*var people = [

       { 'name' : 'Abel', 'age' : 1 },

       { 'name' : 'Bella', 'age' : 2 },

       { 'name' : 'Chad', 'age' : 3 },

    ];

    $.cookie("people", JSON.stringify(people));

    // later on...

    var people = $.parseJSON($.cookie("people"));

    people.push(

        { 'name' : 'Daniel', 'age' : 4 }

    );

    $.cookie("people", JSON.stringify(people)); */



    function save_setting() {

        /* Radar setting 0*/

        var radar_setting={};

        radar_setting.status='on';

        radar_setting.loop='off';

        radar_setting.radar_type=radarType;

        radar_setting.radar_speed=1000;

        radar_setting.radar_sites='on';

        radar_setting.opacity=1;

        setting.push(radar_setting);

        //setting['radar_setting']=radar_setting;

        /* Radar setting */

        

        /* observation setting 1*/

        var observation_setting={};

        observation_setting.status='on';

        observation_setting.observation_type=1;

        setting.push(observation_setting);

        //setting['observation_setting']=observation_setting;

        /* observation setting */

        

        /* forecast setting 2*/

        var forecast_setting={};

        forecast_setting.status='off';

        setting.push(forecast_setting);

        //setting['forecast_setting']=forecast_setting;

        /* forecast setting */

        

       /* warning setting 3*/

        var warning_setting={};

        warning_setting.status='on';

        //warning_setting.warning_type_option='expired';
		warning_setting.warning_type_option='current';

        //warning_setting.expired_warning_last=48;
		warning_setting.expired_warning_last='';

        warning_setting.warning_types='all';

        setting.push(warning_setting);

        //setting['warning_setting']=warning_setting;

        /* warning setting */

        

        /* Forecast Severe Weatherg setting 4*/

        var forecast_warning_setting={};

        forecast_warning_setting.status='off';

        forecast_warning_setting.forecast_type=1;

        forecast_warning_setting.warning_types='all';

        setting.push(forecast_warning_setting);

        //setting['forecast_warning_setting']=forecast_warning_setting;

        /* Forecast Severe Weather setting */

        

        /* Map option setting 5*/

        var map_option_setting={};

        map_option_setting.map_option='ROADMAP';

        map_option_setting.weather_region='off';

        map_option_setting.river_catchments='off';

        map_option_setting.fire_region='off';

		//setting['map_option_setting']=map_option_setting;

        setting.push(map_option_setting);

        /* Map option setting */

        

        /* Forecast Model setting 6*/

        var forecast_model_setting={};

        forecast_model_setting.status='on';

        forecast_model_setting.opacity=1;

        forecast_model_setting.forecast_type='';

        forecast_model_setting.forecast_date_time='';

        forecast_model_setting.layers='';

        forecast_model_setting.loop='off';

        forecast_model_setting.speed=4000;

        //setting['forecast_model_setting']=forecast_model_setting;

        setting.push(forecast_model_setting);

        /* Map option setting */

        

        /* weather Radar sites 7*/

        var weather_radar_setting={};

        weather_radar_setting.status='on';

        setting.push(weather_radar_setting);

        

        /*  Rainfall Gauges 8*/

        var rainfall_gauges_setting={};

        rainfall_gauges_setting.status='off';

        rainfall_gauges_setting.type='1hr';

        rainfall_gauges_setting.options='';

        setting.push(rainfall_gauges_setting);

		

		/* River Gauges 9 */

		var river_guages_setting={};

        river_guages_setting.status='off';

        river_guages_setting.options='';

        setting.push(river_guages_setting);

		

		/* Data Layer setting 10*/

		var data_layer_setting={};

		data_layer_setting.public_layer_option='';

		data_layer_setting.client_layer_option='';

		setting.push(data_layer_setting);

        

        

        //console.log(setting);

        $.cookie("situation_room_settings", null);

        $.cookie("situation_room_settings", JSON.stringify(setting),{ expires: 365 });

        setting = $.parseJSON($.cookie("situation_room_settings"));

        

        setsituationRoom();

    }

    

    function set_setting() {

        setting = $.parseJSON($.cookie("situation_room_settings"));

        if(setting == null) {

            setting=[];

            save_setting();

        } else {

        //console.log(setting);

            setsituationRoom();

        }

    }



    function check_setting_exist() {

        //delete_setting();

        //$.cookie("situation_room_settings", null);

        var check_setting = $.cookie("situation_room_settings");

        if(check_setting!== null && typeof check_setting!=='undefined') {

            set_setting();

            return true;

        } else {

            save_setting();

            return false;

        }

    }

    

    function setsituationRoom() {

        /* LOAD RADAR */

        if(setting!== null && typeof setting[0]!=='undefined') {

               if(setting[0].status=='on') {

                        $('#radar_display_images').attr("checked",true);

                        $('#optRadar').attr("checked",false);

                        

                        if(setting[0].radar_type!=='') {

                          radarType=setting[0].radar_type;
						  
						   if(radarType=='512')
							  radarType=256;
						  
						  if(map_observation==2)

                            $('#select_map_range').val('256');

						   else {
							 
							  $('#select_map_range').val(radarType);
							}

                        }

                          

                        if(setting[0].radar_speed!=='') {

                          timeInterval=parseInt(setting[0].radar_speed);

                          $('#loop_speed_select').val(timeInterval);

                        }

                          

                        if(setting[0].opacity!=='') {

                          radar_opacity=setting[0].opacity;

                          $('#radar_opacity').val(radar_opacity);

                        }

                        

                        setTimeout( function() {

                            radarinterval=setInterval(function() { 

                            loadRadar(true);

                            },radartimeinterval);

                        },2000);

                        

                        if(setting[0].loop=='on') {

                         $('.radar_pause').show();

                         $('.radar_play').hide();

                         $('#optRadar').attr("checked",true);

                         loopstart();

                        }

                } else {

                    $('#time_display').hide();

                    $('#radar_display_images').attr("checked",false);

                    $('#optRadar').attr("checked",false);

                }

		

	} else {

            setTimeout( function() {

                radarinterval=setInterval(function() { 

                    loadRadar(true);

		},radartimeinterval);

            },2000);

            

        }

        

        /* LOAD RADAR */

        

        /* Weather Radar sites */

        

        if(setting!== null && typeof setting[7]!=='undefined') {

                if(setting[7].status=='on') {

                        $('#wx_radar_sites').attr("checked",true);

                        initEWNMapsRadarsMarkerLabelPrototype();

                        rio2 = new RadarImageOverlay(map, radars2, minimumSeconds, updateInterval, 75);

                        //loadForecastImages();

                        rio2.showRadarMarkers();

                } else {

                        $('#wx_radar_sites').attr("checked",false);

                        initEWNMapsRadarsMarkerLabelPrototype();

                        rio2 = new RadarImageOverlay(map, radars2, minimumSeconds, updateInterval, 75);

                        //loadForecastImages();

                        rio2.hideRadarMarkers();

                }

	} else {

            $('#wx_radar_sites').attr("checked",true);

            initEWNMapsRadarsMarkerLabelPrototype();

            rio2 = new RadarImageOverlay(map, radars2, minimumSeconds, updateInterval, 75);

            //loadForecastImages();

            rio2.showRadarMarkers();

            

        }

        

        /* Weather radar sites */

        

        

        

        /* Load Observation */

        if(setting!== null && typeof setting[1]!=='undefined') {

            if(setting[1].status=='on') {

                $('#loader_observation').show();

                $('.observation_checkbox').attr("checked",true);

                if(setting[1].observation_type==1) {

                    $('.observation_option1').attr("checked",true);

                    $('.observation_option2').attr("checked",false);

                    load_observation_Capitals_only();

                } else if(setting[1].observation_type==2) {

                    $('.observation_option1').attr("checked",false);

                    $('.observation_option2').attr("checked",true);

                    load_observation_allCities();

                }

            } else {

                $('.observation_checkbox').attr("checked",false);

                $('#loader_observation').hide();

            }

        } else {

            /*observation checkbox default setting */

            $('.observation_checkbox').attr("checked",true);

            if(map_observation==1) {

              $('.observation_option1').attr("checked",true);

              $('.observation_option2').attr("checked",false);

            } else {

              $('.observation_option1').attr("checked",false);

              $('.observation_option2').attr("checked",true);

            }

            setTimeout( function() {

	    if(map_observation==1)

		load_observation_Capitals_only();

	    else

		load_observation_allCities();

            },2000);

        }

        /* Load Observation */

        

        /* load forecast */

        if(setting!== null && setting[2]!=='undefined') {

            if(setting[2].status=='on') {

                $(".forecast_checkbox").attr("checked",true);

                $(".forecast_checkbox").trigger("change");

            } else {

                $(".forecast_checkbox").attr("checked",false);

            }

        } 

        

        

        /* load forecast */

        

        /* Load Warning */

        if(setting!== null && typeof setting[3]!=='undefined') {

            if(setting[3].status=='on') {

				$(".weather").attr("checked",true);

                var warning_option=[];

                var warning_type=1;

                var expired_days=48;

                if(setting[3].warning_type_option=='current') {

                    warning_type=1;

                    expired_days='';

                    $('input:radio[name=warning_type]').filter('[value=1]').prop('checked', true);
					
					$('#cyclone_option').show();
					
					 $('.cyclone').attr("checked",true);
					
					toggle_cyclone();

                } else {

                    warning_type=2;

                    expired_days=parseInt(setting[3].expired_warning_last);

                    $('#warning_days').val(expired_days);

                    $('input:radio[name=warning_type]').filter('[value=2]').prop('checked', true);
					
					$('#cyclone_option').hide();

                }

                

                if(setting[3].warning_types=='all') {

                    warning_option.push('all');

                    $('input[name="warning_option[]"]').each(function() {

                        $(this).attr("checked",true);

                    });

                    set_warning_setting(warning_type,warning_option,expired_days);

                } else {

                    warning_option=setting[3].warning_types;

                    //console.log(warning_option);

                     var $radios = $('input:checkbox[name="warning_option[]"]');

                     $(warning_option).each(function( value ) {

                            var warning_value=warning_option[value];

                            $radios.filter('[value="'+warning_value+'"]').prop('checked', true);

                    });

                    set_warning_setting(warning_type,warning_option,expired_days);

                    /*$("#warning_report_generation").show();

                    $('#warning_markers_generation').show();

                    $('#download_as_csv_generation').show();

                     $('#clear_markres_array').show();*/

		     



                }

		$("#warningreportoption1").show();

		$('#warningreportoption2').show();

            } else {

                $(".weather").attr("checked",false);
				
				/*$("#warning_report_generation").hide();

                $('#warning_markers_generation').hide();

                $('#download_as_csv_generation').hide();

                $('#clear_markres_array').hide();*/

		$("#warningreportoption1").hide();

		$('#warningreportoption2').hide();



            }

        } else {

           load_initial_warning();

            /*$("#warning_report_generation").show();

            $('#warning_markers_generation').show();

            $('#download_as_csv_generation').show();

            $('#clear_markres_array').show();*/

	    $("#warningreportoption1").show();

	    $('#warningreportoption2').show();

		

		}

        

        /* Load Warning */

        

        /* Load forecast Warning */

        if(setting!== null && typeof setting[4]!=='undefined') {

            if(setting[4].status=='on') {

                $(".forecastsevere").attr("checked",true);

                $('#forecast_severe_weather_option').val(setting[4].forecast_type);

                var warning_option=[];

                if(setting[4].warning_types=='') {

                } else {

                    var warning_type=setting[4].forecast_type;

                    var $radios = $('input:checkbox[name="forecast_warning_option[]"]');

                    warning_option=setting[4].warning_types;

                    $(warning_option).each(function( value ) {

                            //console.log(warning_option[value]);

                            var warning_value=warning_option[value];

                            $radios.filter('[value="'+warning_value+'"]').prop('checked', true);

                    });

                }

                if(warning_option.length>0)

                load_severeForecast_content(warning_option,warning_type);

                forecast_interval=setInterval(function() {

		  update_forecast_severe();

		  },forecast_intervalTime);

                

                $('#forecast_codes_div').show();

		if($(".weather").attr("checked")) {

		  $('#time_display').css("left","225px");

		  $('#forecast_codes_div').css("left","225px");

		} else {

		  $('#time_display').css("left","30px");

		  $('#forecast_codes_div').css("left","30px");

		}

		$("#forecastwarningreportoption1").show();

		$('#forecastwarningreportoption2').show();

            } else {

                $(".forecastsevere").attr("checked",false);

		$("#forecastwarningreportoption1").hide();

		$('#forecastwarningreportoption2').hide();

            }

        } else {

           //load_initial_warning();     

        }

        

        /* Load forecast Warning */

        

        /* Load map Setting*/

        if(setting!== null && typeof setting[5]!=='undefined') {

            

          if(setting[5].map_option=='SATELLITE')

	    map.setMapTypeId(google.maps.MapTypeId.SATELLITE);

	  else if(setting[5].map_option=='TERRAIN')

            map.setMapTypeId(google.maps.MapTypeId.TERRAIN);

	   else if(setting[5].map_option=='light_monochrome')
             change_map_type("light_monochrome")

          

          if(setting[5].weather_region=='on') {

            $('#showdistricts').attr("checked",true);

             toggleShowDistricts();

          }

          

          if(setting[5].river_catchments=='on') {

            $('#showriverbasins').attr("checked",true);

             toggleShowRiverBasins();

          }

          

          if(setting[5].fire_region=='on') {

            $('#checkbox-1-40').attr("checked",true);

             //toggleShowRiverBasins();

          }

           if(typeof setting[5].map_option!="undefined")
          {
              $("#map_options_selector option:selected").attr("selected",false);
              $("#map_options_selector option[value='"+setting[5].map_option+"']").attr("selected",true);
          }

	 	} 

        /* load Map setting */

        

        /* rainfall gagges */

        if(setting!== null && typeof setting[8]!=='undefined') {

          var warning_option=[];

          if(setting[8].status=='on') {

                $(".rainfall").attr("checked",true);

                warning_option=setting[8].options;

                var $radios = $('input:checkbox[name="rainfall_option[]"]');

                     $(warning_option).each(function( value ) {

                            var warning_value=warning_option[value];

                            $radios.filter('[value="'+warning_value+'"]').prop('checked', true);

                    });

                $("#rainfall_types").val(setting[8].type);

                update_rainfall();

                /*rainfall_timeout=setInterval(function() {

                     update_rainfall();

                },radartimeinterval);*/

            }

          

        } else {

                var rainfall_gauges_setting={};

		rainfall_gauges_setting.status='off';

		rainfall_gauges_setting.type='1hr';

		rainfall_gauges_setting.options='';

		setting.push(rainfall_gauges_setting);

                update_setting();

        }

        /* rainfall gagges */





        /* river guages */

        if(setting!== null && typeof setting[9]!=='undefined' && ($('#river_guage_li').is(':visible')))

        {

        

                    var river_guage_option=[];

          if(setting[9].status=='on')

          {

                $(".river-guage").attr("checked",true);

                river_guage_option=setting[9].options;

                //console.log(river_guage_option);

                var $river_guage_radios = $('input:checkbox[name="river_guage_options[]"]');

                $(river_guage_option).each(function( value )

                {

                    var river_guage_value=river_guage_option[value];

                    console.log('river_guage_value>>>>'+river_guage_value);

                    $river_guage_radios.filter('[value="'+river_guage_value+'"]').prop('checked', true);

                });

                update_river_guages();

            }

        }

        else

        {

            var river_guages_setting={};

            river_guages_setting.status='off';

            river_guages_setting.options='';

            setting.push(river_guages_setting);

            update_setting();

        }

        /* river guages */

		

		/* layer setting */

		 if(setting!== null && typeof setting[10]!=='undefined') {

          		var public_layers=[];

				var client_layers=[];

				

				if(setting[10].public_layer_option!=='') 

				public_layers=setting[10].public_layer_option;

				

				if(setting[10].client_layer_option!=='') 

				client_layers=setting[10].client_layer_option;

				public_checked_options=public_layers;

				client_checked_options=client_layers;

				/* Client layer setting */

				client_layer(client_layers);

				/*  public layer setting */

				public_layer(public_layers);

				

				

		 } else {

                var data_layer_setting={};

				data_layer_setting.public_layer_option='';

				data_layer_setting.client_layer_option='';

				setting.push(data_layer_setting);

                update_setting();
				
				if(LOGIN_USER_NAME=='dexus') {
				
				  var client_layers=[];
				
				  client_layer(client_layers);
				
				}

         }

		

		/* layer seeting */
		
		set_legend_container();

        

        

    }

    

    function update_setting() {

        $.cookie("situation_room_settings", null);

        $.cookie("situation_room_settings", JSON.stringify(setting),{ expires: 365 });
		
		set_legend_container();

    }



    function delete_setting() {

        $.cookie("situation_room_settings", null);

        setting.length=0;

    }
	
	/* function for set legend container height dynamic */
	function set_legend_container () {
			 var count=0;
			 
			$('.panel').each(function(){
				//console.log($(this));
				//console.log($(this).css('display'));
				if ($(this).css('display') == 'none') {
					
                } else {
					count++;
				}
			});
			//console.log('total div'+count);
			var height=count*40;
			$('.legend_container').css({"height": height});
			if(count==0) {
				$('.desktop_view_legend').hide();
				$('.desktop_view_legend').removeClass('hide_legend');
				$('.desktop_view_legend').addClass('show_legend');
			} else {
				if($('.desktop_view_legend').hasClass("show_legend")) {
				//if($('.desktop_view_legend').css('display') !== 'none') {
					$('.desktop_view_legend').show();
					$('.desktop_view_legend').removeClass('hide_legend');
					$('.desktop_view_legend').addClass('show_legend');
					$('.legend_container').show();
				}
			}
			
	}

	

	

    

    