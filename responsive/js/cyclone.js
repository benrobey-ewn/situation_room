    var gp;
	 var cyclone_interval;
	var cyclone_intervalTime=3600000;
	//var cyclone_intervalTime=20000;
	
	function show_cyclone() {
		showAjaxWait($('.cyclone'),'cyclone_loader');
		gp = new geoXML3.parser({ map: map,
						processStyles: true,
						//createMarker: addMyMarker,
						//createOverlay: addMyOverlay,
						polylineOptions: { 
						icons: [{
								icon: {
									path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
									fillColor: '#000000',
									strokeColor: '#000000',
									scale: 2
								},
								offset: '50px',
								repeat: '100px'
							}]
						},
						afterParse: kmlParseComplete,
						failedParse: kmlParseFailed,
						zoom: false
		});
		var d = Math.floor(Math.random()*1000000000000000000000001);
		var url='http://static.ewn.com.au/cyclone/cyclone.kml?ref='+d;
		gp.parse(url);
		
		
	}
	
	function hide_cyclone() {
		//console.log(gp.docs[0]);
		//console.log(gp.docs[0].gpolygons);
		if(typeof gp!=='undefined') {
			if(typeof gp.docs[0].markers !=='undefined') {
				for (var i=0; i < gp.docs[0].markers.length; i++) {
						gp.docs[0].markers[i].setMap(null);
					
				}
			}
			if(typeof gp.docs[0].gpolygons !=='undefined') {
				for (var i=0; i < gp.docs[0].gpolygons.length; i++) {
						gp.docs[0].gpolygons[i].setMap(null);
				}
			}
			if(typeof gp.docs[0].gpolylines !=='undefined') {
				for (var i=0; i < gp.docs[0].gpolylines.length; i++) {
						gp.docs[0].gpolylines[i].setMap(null);
				}
			}
			if(typeof gp.docs[0].groundoverlays !=='undefined') {
				for (var i=0; i < gp.docs[0].groundoverlays.length; i++) {
						gp.docs[0].groundoverlays[i].setMap(null);
				}
			}
		}
		
		
	}
	
	function kmlParseComplete() {
		 hideAjaxWait('cyclone_loader');
	}
	
	function kmlParseFailed() {
		console.log('failed');
	}
	
	
	function toggle_cyclone() {
		 if($('.cyclone').attr("checked") && $(".weather").attr("checked")){
			show_cyclone();
			 cyclone_interval=setInterval(function() {
				  console.log('cyclone');
				  hide_cyclone();
				  setTimeout(function() { 
				  	//toggle_cyclone();
				  	if($('.cyclone').attr("checked") && $(".weather").attr("checked")){
				  		show_cyclone();
				  	}
				  },500);
	         },cyclone_intervalTime);  
	     } else {
			 hide_cyclone();
			 clearInterval(cyclone_interval);
		 }
	}