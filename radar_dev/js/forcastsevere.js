/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    var forecast_interval;
    var forecast_intervalTime=300000;
    function load_severeForecast_content(topics,record_type) {
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: "ajax/get_forecastsvere.php",
            dataType: "json",
            data: { record_type:record_type,topic_type: topics},
            success: function(response)
            {
                for (k=0;k<response.data.length;k++) {
                    console.log('length'+k+response.data[k].corodinates.length);
                    var url=response.data[k].AlertFullURL;
                    if(response.data[k].corodinates.length!==0) {
                      var coordinates_json= response.data[k].corodinates;
                      forecast_draw_map(coordinates_json,response.data[k].color,response.data[k].AlertFullURL,parseInt(response.data[k].id));
                    }
                    
                }
                $('#loader').hide();
            }
        });
    }
    
    
    function remove_forecastevere_warning() {
      if (forecastwarningobjectMarkersArray) {
        for (var i=0; i < forecastwarningobjectMarkersArray.length; i++) {
            forecastwarningobjectMarkersArray[i].setMap(null);
        }
            forecastwarningobjectMarkersArray.length = 0;
        }
      //map.controls[google.maps.ControlPosition.TOP_LEFT].clear();
      //map.controls[google.maps.ControlPosition.LEFT_BOTTOM].clear();
      
    }


    
	
	 /* remove warning legend */
       function remove_Forecast_legend() {
	   map.controls[google.maps.ControlPosition.TOP_LEFT].removeAt(1);
           //map.controls[google.maps.ControlPosition.TOP_LEFT].clear();
       }
       
       
       function update_forecast_severe() {
	if($(".forecastsevere").attr("checked")) {
            remove_forecastevere_warning();
            //alert(this.value);
	    var forecast_weather_options=[];
            forecast_weather_options = $('input:checkbox[name="forecast_warning_option[]"]').filter(':checked').map(function () {
             return this.value;
            }).get();
	    //console.log(weather_options);
            if(forecast_weather_options.length==0) {
               //forecast_weather_options.push('all');
	       //alert(1);
            } else {
		//alert($('#forecast_severe_weather_option').val());
		load_severeForecast_content(forecast_weather_options,$('#forecast_severe_weather_option').val());
	    }
            
        }   
       }
       
       
      
       
       
       
       




