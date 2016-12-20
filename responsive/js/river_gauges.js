    var river_guage_layers=[];
    var river_guage_options=['nofloodorclass','minor','moderate','major'];
    var river_guage_detector;
    
    function show_river_guages()
    {
    	 $('#loader').show();

		remove_all_river_guages('1');
	 	var timeoutdiff=0;
	 	{
	 		var newcount = 1;
	    	$(river_guage_options).each(function( value )
	    	{
            	var d = Math.floor(Math.random()*1000000000000000000000001);
				var url='http://clients3.ewn.com.au/common/obs/rainfall/rivers/kml/'+river_guage_options[value]+'.kml?ref='+d;
				console.log('url'+url);
				var layer = new google.maps.KmlLayer(url, {
				    preserveViewport: true,
				    suppressInfoWindows: false 
				});
			
				if(river_guage_options[value]!='')
				{
				    timeoutID = window.setTimeout(function() {
				    	/* Remove loader */
				    	console.log(newcount);
				    		if(river_guage_options.length==newcount)
						{
						
							$('#loader').hide();
						}
				/* Remove loader */
					layer.setMap(map);
									newcount++;

				    }, timeoutdiff);
				}

				timeoutdiff=timeoutdiff+2000;

				river_guage_layers[river_guage_options[value]]=layer;

				
			
				
	    	});
	    
	    
	    
		    $('input[name="river_guage_options[]"]').each(function()
		    {
	                $(this).attr("checked",true);
		    });

		    var rivers_options=[];
		    rivers_options = $('input:checkbox[name="river_guage_options[]"]').filter(':checked').map(function () {
	             return this.value;
	            }).get();
		    
		    setting[9].status='on';
		    setting[9].options=rivers_options;
		    update_setting();
	    
	   		//$('input:checkbox[name="river_guage_options[]"]').filter('[value="0"]').prop('checked', false);
	  	}
    }

    function remove_all_river_guages(type)
    {
	    for(i=0;i<river_guage_options.length;i++) 
	    {
			if(typeof river_guage_layers[river_guage_options[i]]!=='undefined')
			{
			    river_guage_layers[river_guage_options[i]].setMap(null);
			}
	    }

	    if(type==1)
	    {
		    $('input[name="river_guage_options[]"]').each(function() {
		                    $(this).attr("checked",false);
			    });
		}
		river_guage_layers.length=0;
    }


    
    function remove_rainfall(id)
    {
		if(typeof river_guage_layers[id]!='undefined') 
		{
		    river_guage_layers[id].setMap(null);
		}
    }
    
    function toggleRiverGuageKML(checked, id)
   	{
		if($(".river-guage").attr("checked"))
		{
			if (checked)
			{
				river_guage_detector = 'ON';
	     		//var rainfall_type=$('#rainfall_types').val();
	     		var d = Math.floor(Math.random()*1000000000000000000000001);
				var url='http://clients3.ewn.com.au/common/obs/rainfall/rivers/kml/'+id+'.kml?ref='+d;
				console.log('url'+url);
	    		var layer = new google.maps.KmlLayer(url,
	    		{
					preserveViewport: true,
					suppressInfoWindows: false 
	    		});
	    		layer.setMap(map);
	    		river_guage_layers[id]=layer;
			}
			else
			{
				river_guage_detector = 'OFF';
	    		river_guage_layers[id].setMap(null);
	    		delete river_guage_layers[id];
			}
	    		var rivers_options=[];
	    		rivers_options = $('input:checkbox[name="river_guage_options[]"]').filter(':checked').map(function ()
	    		{
             		return this.value;
            	}).get();
	    		setting[9].options=rivers_options;
	    		update_setting();
	 	}
   	}
    
    function update_river_guages()
    {
		if($(".river-guage").attr("checked"))
		{
			console.log('update_river_guages');
	    	//var river_guage_layers=[];
	    	remove_all_river_guages('2');
	     	var timeoutdiff=0;
	    	 var river_guage_options=[];
	     river_guage_options = $('input:checkbox[name="river_guage_options[]"]').filter(':checked').map(function () {
		 return this.value;
	      }).get();
	      
			$(river_guage_options).each(function( value )
			{
            	var d = Math.floor(Math.random()*1000000000000000000000001);
				var url='http://clients3.ewn.com.au/common/obs/rainfall/rivers/kml/'+river_guage_options[value]+'.kml?ref='+d;
				console.log('url'+url);
				var layer = new google.maps.KmlLayer(url, {
				    preserveViewport: true,
				    suppressInfoWindows: false 
				});
				
				if(river_guage_options[value]!='')
				{
					console.log('asaasasasasafdfdfdf');
				    timeoutID = window.setTimeout(function() {
					layer.setMap(map);
				    }, timeoutdiff);
				}

				timeoutdiff=timeoutdiff+2000;

				river_guage_layers[river_guage_options[value]]=layer;

	    	});
	   		$('#river_gauges_codes_div').show();
		}
    }
    
    