    var rainfall_layers=[];
    var rainfall_option=['0','9.9','24.9','50.0','99.9','199.9','200.0'];
    
    function show_rainfall() {
	remove_all_rainfall();
	 var rainfall_type=$('#rainfall_types').val();
	 var timeoutdiff=0;
	 if(rainfall_type!=='') {
	    //console.log(rainfall_option);
	    $(rainfall_option).each(function( value ) {
                        var d = Math.floor(Math.random()*1000000000000000000000001);
			var url='http://clients3.ewn.com.au/common/obs/rainfall/'+rainfall_type+'/kml/'+rainfall_option[value]+'.kml?ref='+d;
			//console.log('url'+url);
			var layer = new google.maps.KmlLayer(url, {
			    preserveViewport: true,
			    suppressInfoWindows: false 
			});
			
			if(value>0) {
			    timeoutID = window.setTimeout(function() {
				layer.setMap(map);
			    }, timeoutdiff);
			    //layer.setMap(map);
			}
			timeoutdiff=timeoutdiff+2000;
			//rainfall_layers.push(layer);
			rainfall_layers[rainfall_option[value]]=layer;
			
			// delay for layer
			//if(value==0)
			//timeoutID = window.setTimeout(function() { layer.setMap(map); } , 1);
	    });
	    
	    
	    
	    $('input[name="rainfall_option[]"]').each(function() {
                    $(this).attr("checked",true);
	    });
	    
	    $('input:checkbox[name="rainfall_option[]"]').filter('[value="0"]').prop('checked', false);
	    
		    
	    
	    /*if(typeof setting[8]!=='undefined') {
		var rainfall_gauges_setting={};
		rainfall_gauges_setting.status='on';
		rainfall_gauges_setting.type=rainfall_type;
		rainfall_gauges_setting.options='all';
		setting.push(rainfall_gauges_setting);
	    } else {
		setting[8].status='on';
		setting[8].type=rainfall_type;
		setting[8].options='all';
	    }*/
	    var weather_options=[];
	    weather_options = $('input:checkbox[name="rainfall_option[]"]').filter(':checked').map(function () {
             return this.value;
            }).get();
	    
	    setting[8].status='on';
	    setting[8].type=rainfall_type;
	    setting[8].options=weather_options;
	    update_setting();
	   
	}
    }

    function remove_all_rainfall() {
	//console.log(rainfall_layers);
	//console.log(rainfall_layers.length);
	//if(rainfall_layers.length>0) {
	    for(i=0;i<rainfall_option.length;i++) {
		//console.log(rainfall_layers[rainfall_option[i]]);
		if(typeof rainfall_layers[rainfall_option[i]]!=='undefined') {
		    rainfall_layers[rainfall_option[i]].setMap(null);
		}
	    }
	//}
	//rainfall_layers=[];
	rainfall_layers.length=0;
    }
    
    function remove_rainfall(id) {
	if(typeof rainfall_layers[id]!='undefined') {
	    rainfall_layers[id].setMap(null);
	}
	
    }
    
    function toggleKML(checked, id) {
	 if($(".rainfall").attr("checked")) {
	if (checked) {
	     var rainfall_type=$('#rainfall_types').val();
	     var d = Math.floor(Math.random()*1000000000000000000000001);
	     var url='http://clients3.ewn.com.au/common/obs/rainfall/'+rainfall_type+'/kml/'+id+'.kml?ref='+d;
	    var layer = new google.maps.KmlLayer(url, {
		preserveViewport: true,
		suppressInfoWindows: false 
	    });
	    layer.setMap(map);
	    rainfall_layers[id]=layer;
	} else {
	    rainfall_layers[id].setMap(null);
	    delete rainfall_layers[id];
	}
	    var weather_options=[];
	    weather_options = $('input:checkbox[name="rainfall_option[]"]').filter(':checked').map(function () {
             return this.value;
            }).get();
	    setting[8].options=weather_options;
	    update_setting();
	 }
    }
    
    function update_rainfall() {
	if($(".rainfall").attr("checked")) {
	    //var rainfall_layers=[];
	    remove_all_rainfall();
	     var timeoutdiff=0;
	    var rainfall_option=[];
	     rainfall_option = $('input:checkbox[name="rainfall_option[]"]').filter(':checked').map(function () {
		 return this.value;
	      }).get();
	      var rainfall_type=$('#rainfall_types').val();
	      if(rainfall_type!=='') {
	      
		$(rainfall_option).each(function( value ) {
			    var d = Math.floor(Math.random()*1000000000000000000000001);
			    var url='http://clients3.ewn.com.au/common/obs/rainfall/'+rainfall_type+'/kml/'+rainfall_option[value]+'.kml?ref='+d;
			    //console.log('url'+url);
			    var layer = new google.maps.KmlLayer(url, {
				preserveViewport: true,
				suppressInfoWindows: false 
			    });
			    //layer.setMap(map);
			    //rainfall_layers.push(layer);
			    timeoutID = window.setTimeout(function() {
				layer.setMap(map);
			     }, timeoutdiff);
			    timeoutdiff=timeoutdiff+2000;
			    rainfall_layers[rainfall_option[value]]=layer;
		
		});
	    }
	    $('#rainfall_gauges_codes_div').show();
	}
    }
    
    