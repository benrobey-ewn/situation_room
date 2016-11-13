    var map;
    var infowindow = null;
    var rio2;
    var overlay=[];
    var radercodes=[];
    var interval;
    var imagemaxcount=0;
    var current_index=0;
    var is_paused=true;
    var radarType='512';
    var timeInterval=700; //// used for control speed of loop
    var viewportLatMin='-10.499923215063747';
    var viewportLatMax='-46.98865521026523';
    var viewportLonMin='175.46223087499993';
    var viewportLonMax='94.60285587499993';
    var viewportZoom='4';
    var radarinterval;
    var radartimeinterval=300000;
    var polygon_marker = new google.maps.Polygon({});
    var observation_zoom=0;
    var RadarTimes=[];
    var radartimesinterval;
    var radartimerIndex=0;
    var radarimagesTimerInterval=300;
    var radar_opacity=1;
    var staticRadarTimes = [];
    var staticloadedImages = [];
    var imageframeImages=[];
    var downloadedImages=0;
    var totalRadar=0;
    
    /* frame image counter */
    var downloadedfirstframeImages=0;
    var downloadedsecondframeImages=0;
    var downloadedthirdframeImages=0;
    var downloadedfourthframeImages=0;
    var downloadedfifthframeImages=0;
    var downloadedsixframeImages=0;
    var downloadedlastdownloadedImages=0;
    
    var totalfirstframeImages=0;
    var totalsecondframeImages=0;
    var totalthirdframeImages=0;
    var totalfourthframeImages=0;
    var totalfifthframeImages=0;
    var totalsixframeImages=0;
    var totallastdownloadedImages=0;
    
    //var img;
    var img12 = new Image();
    
    
    Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
    
    
    var places={
	    restaurant:{
	    label:'',
	    //the category may be default-checked when you want to
	    //uncomment the next line
	    //checked:true,
	    icon: 'http://maps.gstatic.com/mapfiles/markers2/marker.png' ,
	    items: [
	    ['Sydney',  -33.86 , 151.21 ],
	    ['Melbourne', -37.81, 144.97 ],
	    ['Hobart', -42.89 , 147.33 ],
	    ['Adelaide', -34.92 , 138.62],
	    ['Perth',-31.92 , 115.87],
	    ['Canberra',-35.31  , 149.20],
	    ['Darwin',-12.47  , 130.85],
	    ['Brisbane',-27.48  , 153.04],
	    ['Alice',-23.80  , 133.89],
	    ['Cairns',-16.87  , 145.75 ]
	    ]
	    }
	    };
    
    
    var parameter_data = "{ minLat: '" + viewportLatMin + "', \n\
                            maxLat: '" + viewportLatMax + "', \n\
                            minLon: '" + viewportLonMin + "', \n\
                            maxLon: '" + viewportLonMax + "', \n\
                            zoom: '" + viewportZoom + "',\n\
                            radarType: '" + radarType + "' \n\
                         }";
    
    
    //var data = { minLat: '-18.885387571045207', maxLat: '-31.34415479736761', minLon: '162.36156178124997', maxLon: '105.18871021874997', zoom: '5', radarType: Raday_Type };
    USGSOverlay.prototype = new google.maps.OverlayView();
    
    // Initialize the map and the custom overlay.
    
    
    
    function initialize() {
	var maptypeID=google.maps.MapTypeId.ROADMAP;
	var mapOptions = {
	    center: myCenter,
	    zoom: zoom,
	    mapTypeControl:true,
	    mapTypeControlOptions: {
	    position: google.maps.ControlPosition.TOP_LEFT
	    },
	    panControl: true,
	    panControlOptions: {
		position: google.maps.ControlPosition.LEFT_TOP
	    },
	    streetViewControl: true,
	    streetViewControlOptions: {
		position: google.maps.ControlPosition.LEFT_TOP
	    },
	    zoomControl: true,
	    zoomControlOptions: {
		style: google.maps.ZoomControlStyle.LARGE,
		position: google.maps.ControlPosition.LEFT_TOP
	    },
	    //mapTypeId: google.maps.MapTypeId.HYBRID,
	    mapTypeId: maptypeID,
	};
    
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	infowindow = new google.maps.InfoWindow();
    
        /* update forcast */
	/* load radar images */
         
	check_setting_exist();
	
	
	google.maps.event.addListener(map, 'tilesloaded', function() {
	    $('#loader').hide();
	});

	google.maps.event.addListener( map, 'maptypeid_changed', function() { 
		hide_show_city_lable_typeid(map.getMapTypeId());
		if(map.getMapTypeId()=='hybrid')
		{
			$('#map_options_selector').val('SATELLITE');
		}
		else
		{
			 $('#map_options_selector').val(map.getMapTypeId().toUpperCase());
		}
	});
	
	/* map bound change event */
	google.maps.event.addListener(map, 'bounds_changed', function() {
	        /*if($("#radar_display_images").attr("checked"))
		{
                  loadRadar();
		} */
		if($(".observation_checkbox").attr("checked")) {
		    
		}
                  //do whatever you want with those bounds
         });
	
	/* map idle change event */
	google.maps.event.addListener(map, 'idle', function() {
	     if($("#radar_display_images").attr("checked"))
		{
                  loadRadar(false);
		  
		}
	     /*var zoomLevel = map.getZoom();
	     //alert(zoomLevel);
	     console.log(zoomLevel);
	     if(zoomLevel<=6) {
		 $('.city_label').hide();
	     } else {
		 $('.city_label').show();
	     }*/
	     //hide_show_city_lable();
	   
	    
		//$('#loader').hide();
		//setTimeout(function() {$('#loader').hide(); },5000);
	});
	
	/* map zoom change event */
	google.maps.event.addListener(map, 'zoom_changed', function() {
           hide_show_city_lable_typeid(map.getMapTypeId());
           var zoomLevel = map.getZoom();
            generateChart(true) ;
	    //alert(zoomLevel);
	    if($(".observation_checkbox").attr("checked")) {
		var zoomLevel = map.getZoom();
		//console.log('zoom level'+zoomLevel);
		if($(".observation_option1").attr("checked")) {
		    load_observation_Capitals_only();
		} else {
		    load_observation_allCities();
		}
	    }
	    
	    /* radar check */
	    if($('#radar_display_images').attr("checked")) {
		if(map_observation==1) {
		   if(zoomLevel>8) {
		       if(radarType=='512') {
			$('#radar_confirm_display').show();
			setTimeout(function() { $('#radar_confirm_display').hide();},15000);
			//$('.containertext').html('show confirm box');
		       }
		   }
		}
	    }
	    
	    //map.setCenter(myLatLng);
	    //infowindow.setContent('Zoom: ' + zoomLevel);
	});
    }

    /** @constructor */
    function USGSOverlay(bounds, image, map, radarcode) {

      // Initialize all properties.
      this.bounds_ = bounds;
      this.image_ = image;
      this.map_ = map;
      this.images_ = [];
      this.radar_code = radarcode;
      this.image_index = 0;


      // actually create this div upon receipt of the onAdd()
      // method so we'll leave it null for now.
      this.div_ = null;

      // Explicitly call setMap on this overlay.
      this.setMap(map);
    }



    // [START region_attachment]
    /**
     * onAdd is called when the map's panes are ready and the overlay has been
     * added to the map.
     */
    USGSOverlay.prototype.onAdd = function() {
      
      var radar_class=this.radar_code;
      
      var div = document.createElement('div');
		div.style.borderStyle = 'solid';
      	div.style.borderWidth = '1px';
      //div.style.borderStyle = 'none';
      //div.style.borderWidth = '0px';
      div.style.position = 'absolute';
      //div.style.fontSize= '60px;';
      //div.style.cssText = 'border:1px solid red';
      // Create the img element and attach it to the div.
        var img = document.createElement('img');
       //img.title = 'newTitle';
       //img.alt = 'newAlt';
       var element1_class=radar_class+'image6';
       var image_url=this.image_;
       var radarCode=this.radar_code;
       var obj=this;
       img.className = element1_class+" image6";
       //img.className = "show";
       img.style.display = 'none';
      
       
       img.onerror = function(){
            this.src='images/magic.gif';
	    //img.style.display = '';
	    /*if(typeof staticloadedImages[radarCode] == 'undefined') {
		 obj.loadNextimage();
		
	    } else {
		if(staticloadedImages[radarCode]!=='') {
		       this.src=staticloadedImages[radarCode];
		} else {
			this.src='images/magic.gif';
		}
	    }*/
	    //console.log(overlay[radarCode].images_[staticRadarTimes[current_index]]);
	    /*if(typeof overlay[radarCode].images_[staticRadarTimes[current_index]] == 'undefined') {
	    } else {
		 delete overlay[radarCode].images_[staticRadarTimes[current_index]];
	    }*/
	    
	    /*console.log('total frame'+totalRadar+'lastdownloadedImages'+lastdownloadedImages);
	    
	     if(lastdownloadedImages>=totalRadar) {
		RadarTimeStart();
		img.style.display = '';
	    }*/
	    
	    
        }
       
	img.onload = function(){
	    //radar_object.previous_images_=radar_object.image_;
            //console.log('total frame'+totalRadar);
	    downloadedlastdownloadedImages++;
	    staticloadedImages[radarCode]=this.src;
	    
	    if(this.src !=='images/magic.gif')
		staticloadedImages[radarCode]=this.src;
	    
	    console.log('total frame'+totalRadar+'lastdownloadedImages'+downloadedlastdownloadedImages);
	    
	    if(downloadedlastdownloadedImages>=totalRadar && current_index===6) {
		RadarTimeStart();
		totallastdownloadedImages=totalRadar;
		$('.image6').removeClass('hide_current');
		$('.image6').addClass('show_current');
		$('.image6').css("display",""); 
		
		
		
	    }
	    //console.log('Loaded'+staticloadedImages[radarCode]);
	}
	
	img.src = this.image_;
	
	
       
      img.style.width = '100%';
      img.style.height = '100%';
      img.style.position = 'absolute';
      img.style.opacity = radar_opacity;
      img.style.filter  = 'alpha(opacity=90)'; // IE fallback
	  
	  
	   var text_div = document.createElement('div');
		//text_div.style.borderStyle = 'solid';
      	//div.style.borderWidth = '1px';
      //div.style.borderStyle = 'none';
      //div.style.borderWidth = '0px';
      text_div.style.position = 'absolute';
	  
	  text_div.innerHTML = radar_class;
      
      //div.appendChild(document.createTextNode(radar_class));
	  div.appendChild(text_div);
      div.appendChild(img);
      
       i=0;
       for(i<0;i<6;i++) {
		var element_class=radar_class+'image'+i;
		var img1 = document.createElement('img');
		var image_url=this.image_;
		var radarCode=this.radar_code;
		img1.className = element_class+" image"+i;
		//img1.style.display = 'none';
       
		img1.onerror = function(){
		     
		     //this.src='images/magic.gif';
		     if(typeof staticloadedImages[radarCode]!=='undefined') {
			if(staticloadedImages[radarCode]!=='') {
				this.src=staticloadedImages[radarCode];
			} else {
				this.src='images/magic.gif';
			}
		     }
		}
		img1.onload = function(){
		    
		     if(this.src !=='images/magic.gif')
			 staticloadedImages[radarCode]=this.src;
			 
		      	 
		}
		 
		img1.src='images/magic.gif';
       
	       img1.style.width = '100%';
	       img1.style.height = '100%';
	       img1.style.position = 'absolute';
	       img1.style.opacity = 0;
	       img1.style.filter  = 'alpha(opacity=90)'; // IE fallback
	       
	       
		div.appendChild(img1);
       }
       
      this.div_ = div;

      // Add the element to the "overlayLayer" pane.
      var panes = this.getPanes();
      panes.overlayLayer.appendChild(div);
    };
    // [END region_attachment]

    // [START region_drawing]
    USGSOverlay.prototype.draw = function() {

      var overlayProjection = this.getProjection();
      var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
      var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

      // Resize the image's div to fit the indicated dimensions.
      var div = this.div_;
      div.style.left = sw.x + 'px';
      div.style.top = ne.y + 'px';
      div.style.width = (ne.x - sw.x) + 'px';
      div.style.height = (sw.y - ne.y) + 'px';
      
      this.Addelement();
      
    };
    
    // we ever set the overlay's map property to 'null'.
    USGSOverlay.prototype.onRemove = function() {
      this.div_.parentNode.removeChild(this.div_);
      this.div_ = null;
    };
    
    USGSOverlay.prototype.NextImage = function() {
      if (this.div_) {
      x=this.div_;
      y=x.childNodes[0];
      var img=y;
      img.src = this.image_;
      }
    };
    
    USGSOverlay.prototype.loadNextimage = function() {
      var radarCode=this.radar_code;
      var count=staticRadarTimes.length-1;
      var c_current_index=current_index;
      if(c_current_index == count) {
               c_current_index=0;
       } else {
               c_current_index=c_current_index+1;
       }
      
	if(typeof overlay[radarCode].images_[staticRadarTimes[c_current_index]] == 'undefined') {
	  
	} else {
	     if (this.div_) {
         x=this.div_;
         y=x.childNodes[0];
         var img=y;
	     img.src = overlay[radarCode].images_[staticRadarTimes[c_current_index]];
	     //console.log('sucess');
		 }
       }
    };
    
    USGSOverlay.prototype.loadPreviousimage = function() {
      //console.log(staticRadarTimes);
      var radarCode=this.radar_code;
      var count=staticRadarTimes.length-1;
      var c_current_index=current_index;
      
      if(c_current_index==0){
             c_current_index=staticRadarTimes.length-1;
          } else {
            c_current_index=c_current_index-1;
          }
      x=this.div_;
      y=x.childNodes[0];
      var img=y;
      
      if(typeof overlay[radarCode].images_[staticRadarTimes[c_current_index]] == 'undefined') {
       } else {
	 img.src = overlay[radarCode].images_[staticRadarTimes[c_current_index]];
	}
    };
    
    USGSOverlay.prototype.ChangeOpactiy = function() {
      if (this.div_) {
	/*for(z=0;z<7;z++) {
		x=this.div_;
		y=x.childNodes[z];
		var img=y;
		img.style.opacity = radar_opacity;
	}*/
	$('.show_current').css("opacity",radar_opacity); 
      }
	  
	  
    };
    
    // [END region_removal]

    // Set the visibility to 'hidden' or 'visible'.
    USGSOverlay.prototype.hide = function() {
      if (this.div_) {
        // The visibility property must be a string enclosed in quotes.
        this.div_.style.visibility = 'hidden';
      }
    };

    USGSOverlay.prototype.show = function() {
      if (this.div_) {
        this.div_.style.visibility = 'visible';
      }
    };

    USGSOverlay.prototype.toggle = function() {
      if (this.div_) {
        if (this.div_.style.visibility == 'hidden') {
          this.show();
        } else {
          this.hide();
        }
      }
    };

    USGSOverlay.prototype.toggleDOM = function() {
      if (this.getMap()) {
        this.setMap(null);
      } else {
        this.setMap(this.map_);
      }
    };
    
    USGSOverlay.prototype.Addelement = function() {
      if (this.div_) {
	for(i=0;i<6;i++) {
	    var image_url;
	    if(typeof overlay[this.radar_code].images_[staticRadarTimes[i]]!=='undefined') {
		image_url=overlay[this.radar_code].images_[staticRadarTimes[i]];
		$('.'+this.radar_code+'image'+i).attr("src", overlay[this.radar_code].images_[staticRadarTimes[i]]);
	    } else {
		image_url='images/magic.gif';
		$('.'+this.radar_code+'image'+i).attr("src", 'images/magic.gif');
	    }
	}
      }
    };
    
    google.maps.event.addDomListener(window, 'load', initialize);
    
    /* function for response of radar request */
    function getRadarImagesForViewPortSuccess(jqXHR, textStatus, srcElem) {
	removeAllRadar();
	
	if($("#radar_display_images").attr("checked")) {
		$('#image_box').html('');
	imagemaxcount=0;
	//var result = $.parseJSON(jqXHR.d);
	//var data = result.Data;
	var data=jqXHR.data;
	console.log(data.length);
	if (data == null || data.length == 0) {
		$('.maptimeText').html('Unable to load radars or there are no radar images available for this view');
		$('#loader').hide();
	} else {
	        var rit = {};
		var radartimers = [];
	        $('.maptimeText').html('Loading Radars..');
		//var data = $.parseJSON(data);
		staticRadarTimes = [];
		/* generate timers array */
		    $.each(data, function () {
			   // kept track of unique times
			    rit[this.imageDate] = this.imageDate;
		    });
		    
		    radartimers = radarTimesPadded(hashMerge(staticRadarTimes, rit));
		    
		
		    if (radartimers != null && radartimers.length > 0) {
			     /*var od = Date.fromISO(radartimers[0]);
			     staticRadarTimes.push(radartimers[0]);
			     staticRadarTimes.push(radarTimeAddMinutes(od,10));
			     staticRadarTimes.push(radarTimeAddMinutes(od,20));
			     staticRadarTimes.push(radarTimeAddMinutes(od,30));
			     staticRadarTimes.push(radarTimeAddMinutes(od,40));
			     staticRadarTimes.push(radarTimeAddMinutes(od,50));
			     staticRadarTimes.push(radarTimeAddMinutes(od,60));*/
			     var timer_length= radartimers.length-1;
			     var od = Date.fromISO(radartimers[timer_length]);
			     //console.log(od);
			     //console.log(radartimers[timer_length]);
			     var od = Date.fromISO(roundMinutes(od));
			     //console.log(od);
			     staticRadarTimes.push(radarTimeSubtractMinutes(od,60));
			     staticRadarTimes.push(radarTimeSubtractMinutes(od,50));
			     staticRadarTimes.push(radarTimeSubtractMinutes(od,40));
			     staticRadarTimes.push(radarTimeSubtractMinutes(od,30));
			     staticRadarTimes.push(radarTimeSubtractMinutes(od,20));
			     staticRadarTimes.push(radarTimeSubtractMinutes(od,10));
			     //staticRadarTimes.push(radartimers[timer_length]);
			     staticRadarTimes.push(roundMinutes(od));
			     
		    }
		    
		    //console.log(staticRadarTimes);
		    
		    //console.log(staticRadarTimes);
		    /* calculate four image of every radar */
		    var radarCodeImages=[];
		    $.each(data, function () {
                           var curRadar = this.RadarCode;
			  // var img="http://static.ewn.com.au/"+"/images/radar/" + this.fn;

			  	/////// Added by Programmer 3
				// var img='radar_images/'+ this.fn;
				var img='../dev/radar_images/'+ this.fn;
				///////
			   
			   //console.log(this.imageDate);
			  var image_index=0;
			   if(this.imageDate < staticRadarTimes[0]) {
			      //console.log('first');
			      image_index=0;
			    } else if(this.imageDate >= staticRadarTimes[0] &&  this.imageDate<staticRadarTimes[1]) {
			      //console.log('second');
			      image_index=1;
			    } else if(this.imageDate >= staticRadarTimes[1] &&  this.imageDate<staticRadarTimes[2]) {
			      //console.log('third');
			      image_index=2;
			    } else if(this.imageDate>= staticRadarTimes[2] &&  this.imageDate<staticRadarTimes[3]) {
			      //console.log('fouth');
			      image_index=3;
		            } else if(this.imageDate>= staticRadarTimes[3] &&  this.imageDate<staticRadarTimes[4]) {
			      //console.log('fouth');
			      image_index=4;
		            } else if(this.imageDate>= staticRadarTimes[4] &&  this.imageDate<staticRadarTimes[5]) {
			      //console.log('fouth');
			      image_index=5;
		            } else if(this.imageDate>= staticRadarTimes[5] &&  this.imageDate<=staticRadarTimes[6]) {
			      //console.log('fouth');
			      image_index=6;
		            }
			
			  if(image_index==6) {
				//totallastdownloadedImages++;
				radarCodeImages[this.RadarCode]=img;
				 img12 = new Image();
					img12.onload = function(){
					  //downloadedlastdownloadedImages++;
					 //console.log('total'+downloadedImages);
					  //$('#image_box').append(this);
					};
		
					img12.src = img;
			  }
			  
			  if(image_index==5) {
				        totalsixframeImages++;
				radarCodeImages[this.RadarCode]=img;
				 img12 = new Image();
					img12.onload = function(){
					  downloadedsixframeImages++;
					 //console.log('total'+downloadedImages);
					  //$('#image_box').append(this);
					};
		
					img12.src = img;
			  }
			  
			  if(image_index==4) {
				         totalfifthframeImages++;
					 img12 = new Image();
					img12.onload = function(){
					  downloadedfifthframeImages++;
					 //console.log('total'+downloadedImages);
					  //$('#image_box').append(this);
					};
		
					img12.src = img;
			  }
			  
			   if(image_index==3) {
					 totalfourthframeImages++;
					 img12 = new Image();
					img12.onload = function(){
					  downloadedfourthframeImages++;
					 //console.log('total'+downloadedImages);
					  //$('#image_box').append(this);
					};
		
					img12.src = img;
			  }
			  
			  if(image_index==2) {
					totalthirdframeImages++;
					 img12 = new Image();
					img12.onload = function(){
					  downloadedthirdframeImages++;
					 //console.log('total'+downloadedImages);
					  //$('#image_box').append(this);
					};
		
					img12.src = img;
			  }
			  
			  if(image_index==1) {
				       totalsecondframeImages++;
					 img12 = new Image();
					img12.onload = function(){
					  downloadedsecondframeImages++;
					 //console.log('total'+downloadedImages);
					  //$('#image_box').append(this);
					};
		
					img12.src = img;
			  }
			  
			  if(image_index==0) {
				        totalfirstframeImages++;
					 img12 = new Image();
					img12.onload = function(){
					  downloadedfirstframeImages++;
					  //console.log('total first'+downloadedfirstframeImages);
					  //$('#image_box').append(this);
					};
		
					img12.src = img;
			  }
			  
		    });
		    /* calculate four image of every radar */
		        //console.log(staticRadarTimes);
			staticRadarTimesCurrent = staticRadarTimes[0];
		
		   //console.log(radarCodeImages);	
		
		/* */
		
		$.each(data, function () {
                        var curRadar = this.RadarCode;
			//console.log('fh'+this.fn)
                        //var img="http://static.ewn.com.au"+"/images/radar/" + this.fn;

                //////  Added by Programmer 3
				// var img='radar_images/'+ this.fn;
				var img='../dev/radar_images/'+ this.fn;
                //////
			
			   var image_index=0;
			   if(this.imageDate < staticRadarTimes[0]) {
			      //console.log('first');
			      image_index=0;
			    } else if(this.imageDate >= staticRadarTimes[0] &&  this.imageDate<staticRadarTimes[1]) {
			      //console.log('second');
			      image_index=1;
			    } else if(this.imageDate >= staticRadarTimes[1] &&  this.imageDate<staticRadarTimes[2]) {
			      //console.log('third');
			      image_index=2;
			    } else if(this.imageDate>= staticRadarTimes[2] &&  this.imageDate<staticRadarTimes[3]) {
			      //console.log('fouth');
			      image_index=3;
		            } else if(this.imageDate>= staticRadarTimes[3] &&  this.imageDate<staticRadarTimes[4]) {
			      //console.log('fouth');
			      image_index=4;
		            } else if(this.imageDate>= staticRadarTimes[4] &&  this.imageDate<staticRadarTimes[5]) {
			      //console.log('fouth');
			      image_index=5;
		            } else if(this.imageDate>= staticRadarTimes[5] &&  this.imageDate<=staticRadarTimes[6]) {
			      //console.log('fouth');
			      image_index=6;
		            }
			    
			// kept track of unique times
			//rit[this.imageDate] = this.imageDate;
			    
			    /*this.imageDate=timeToLocal(this.imageDate);
			    var index = RadarTimes.indexOf(this.imageDate);
			    if (index == -1) {
				    RadarTimes.push(this.imageDate);
			    }*/
                                //console.log(img.src);
                                // add only new radars
				if (!overlay[this.RadarCode]) {
				    //console.log(image_index);
				    var local_img=img
                                    var neBound = new google.maps.LatLng(this.overlayTLlat, this.overlayBRlon);
                                    var swBound = new google.maps.LatLng(this.overlayBRlat, this.overlayTLlon);
                                    var bounds = new google.maps.LatLngBounds(swBound, neBound);
				    /*if(image_index>0) {
					local_img='images/magic.gif';
				    }*/
				    
				    totalRadar++;
				    if(typeof radarCodeImages[this.RadarCode] == 'undefined') {
					local_img='images/magic.gif';
				    } else {
				        local_img=radarCodeImages[this.RadarCode];
				    }
				    
				    //console.log(local_img);
                                    r = new USGSOverlay(bounds, local_img, map, this.RadarCode); 
                                    overlay[this.RadarCode]=r;
				    radercodes.push(this.RadarCode);
                                }
				else {
                                    r = overlay[this.RadarCode];
                                    //r.images_.push(img);
                                }
				
				
					/*var img2 = new Image();
					img2.onload = function(){
					  downloadedImages++;
					 //console.log('total'+downloadedImages);
					  //$('#image_box').append(this);
					};
		
					img2.src = img;*/
					
					
					
				
				
				
				//$('#image_box').append(img2);
				imagemaxcount++;
				
			 
			r.images_[staticRadarTimes[image_index]]=img;
			  //var size = Object.size(r.images_);
			  /*if(size>1) {
				console.log(r.div_);
				r.AddImages(r,2);
			  }*/
			  //if(r.images_.length>imagemaxcount)
                             // imagemaxcount=r.images_.length;
			
			prevRadar = curRadar;
			
		});
		
		//console.log(staticRadarTimes);
		//console.log(overlay);
		console.log(imagemaxcount);
		radartimerIndex=6;
		current_index=6;
                //imagemaxcount=imagemaxcount-1;
                $('#loader').hide();
		//RadarTimeStart();
		
		if($("#optRadar").attr("checked")) {
			setTimeout( function() {
                                loopstart();
				},2000);
		}
		
		
        }
     }
     
    }
    
    function timeZone() {
	try {
		var d = new Date();
		var n = d.toTimeString();
		if (n.length > 8) {
			var i = n.indexOf(' ');
			var tz = n.substr(i + 1);
			var tzre = /\((.*)\)/;
			var tzrem = tzre.exec(tz);
			if (tzrem != null
				&& tzrem.length > 1) {
				return tzrem[1];
			}
			else
				return tz;
		}
	} catch (e) {
		console.log('unable to get locale timezone');
	}
	return '';
    }
    
    function getRadarImagesForViewPortFailed(jqXHR, textStatus, srcElem) {
         $('#loader').hide();

     }
     
     
     function timeToLocal(datetimeString) {
	var newDate = new Date(datetimeString);
	if (isNaN(newDate))
		newDate = Date.fromISO(datetimeString);
	if (isNaN(newDate))
		return datetimeString + ' (UTC)';
	var localString = $.datepicker.formatDate('D dd M y', newDate) + ' ' + newDate.toLocaleTimeString();
	if (localString.indexOf('NaN') >= 0)
		return datetimeString + ' (UTC)';
	return localString;
    }
    
    
    /* function for load radar */ 
    function sendAjax(callMethod, data, onSuccess, onFailure, srcElem,is_loader) {
	//var url = "http://www.ewn.com.au/exo/webextensions.asmx/" + callMethod;
	    var url="radar/getRadar.php";
	    if(is_loader)
            $('#loader').show();
		  
	    /*if (msieversion() && msieversion()<10 ) {
      		    var url = "ajax/getRadar.php";
    	    } else {
		    var url = "http://www.ewn.com.au/exo/webextensions.asmx/" + callMethod;
	    }*/
	    
	    
	    var mapbounds = map.getBounds();
		var viewportLatMin = mapbounds.getNorthEast().lat();
		var viewportLatMax = mapbounds.getSouthWest().lat();
		var viewportLonMin = mapbounds.getNorthEast().lng();
		var viewportLonMax = mapbounds.getSouthWest().lng();
		var viewportZoom = map.getZoom();
	    
				
	    $.ajax({
                type: "POST",
                url: url,
                data: {viewportLatMin:viewportLatMin, viewportLatMax:viewportLatMax, viewportLonMin: viewportLonMin, viewportLonMax:viewportLonMax, viewportZoom:viewportZoom, radarType:radarType},
                //contentType: "application/json; charset=utf-8",
                dataType: "json",
                //processData: false,
                //context: (srcElem) ? srcElem.first() : null,
                success: function (jqXHR, textStatus) {
                    //console.log("success");
                    if (onSuccess != null)
                       onSuccess(jqXHR, textStatus, srcElem);
                },
                error: function (jqXHR, textStatus, errorThrown, thrownError) {
                    //console.log("Failed");
                }
            });
			
    }
	
    function msieversion() {
	var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0)  {    // If Internet Explorer, return version number
             //alert(parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))));
	    return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)));
	} else  {                // If another browser, return 0
            //alert('otherbrowser');
	    return false;
	}
	return false;
    }

    /* function for Next image */
    function show_Next(is_paused) {
	//console.log('current'+current_index);
	if(imagemaxcount>0) {
	  var frame=0;
		var count=staticRadarTimes.length-1;
            if(current_index == count) {
               frame=0;
            } else {
                frame=current_index+1;
            }
	 
	 var totalimagesdownload;
	 var imagesdownloaded;
	 
	if(frame==0) {
		totalimagesdownload=totalfirstframeImages;
		imagesdownloaded=downloadedfirstframeImages;
	 } else if(frame==1) {
		totalimagesdownload=totalsecondframeImages;
		imagesdownloaded=downloadedsecondframeImages;
	 } else if(frame==2) {
		totalimagesdownload=totalthirdframeImages;
		imagesdownloaded=downloadedthirdframeImages;
	 } else if(frame==3) {
		totalimagesdownload=totalfourthframeImages;
		imagesdownloaded=downloadedfourthframeImages;
	 } else if(frame==4) {
		totalimagesdownload=totalfifthframeImages;
		imagesdownloaded=downloadedfifthframeImages;
	 } else if(frame==5) {
		totalimagesdownload=totalsixframeImages;
		imagesdownloaded=downloadedsixframeImages;
	 } else if(frame==6) {
		totalimagesdownload=totallastdownloadedImages;
		imagesdownloaded=downloadedlastdownloadedImages;
	 }
	console.log('frame'+frame+'total'+totalimagesdownload+'downloaded'+imagesdownloaded);
	//console.log('needed'+istotalimage+'current'+downloadedImages);
	if(parseInt(imagesdownloaded) >= parseInt(totalimagesdownload) && downloadedlastdownloadedImages>=totalRadar) {
		//console.log('Next');
	
	    var last_index=current_index;
	    var count=staticRadarTimes.length-1;
            if(current_index == count) {
               current_index=0;
            } else {
                current_index=current_index+1;
            }
	    
	    //console.log('gdfg'+current_index);
	    
	     if(typeof staticRadarTimes[current_index]!=='undefined')
		$('.maptimeText').html(timeToLocal(staticRadarTimes[current_index]));
		
	    /*for (i=0;i<radercodes.length;i++) {
         
		overlay[radercodes[i]].image_index=current_index;
		if(typeof overlay[radercodes[i]].images_[staticRadarTimes[current_index]] == 'undefined') {
		   
		} else {
                   overlay[radercodes[i]].image_=overlay[radercodes[i]].images_[staticRadarTimes[current_index]];
		}
		overlay[radercodes[i]].radar_code=radercodes[i];
		overlay[radercodes[i]].NextImage();
	    }*/
	    $('.image'+last_index).addClass('hide_current');
	    $('.image'+last_index).removeClass('show_current');
	    $('.image'+current_index).removeClass('hide_current');
	    $('.image'+current_index).addClass('show_current');
		
		$('.image'+current_index).css("opacity",radar_opacity); 
		$('.image'+last_index).css("opacity","0"); 
		
		
		//$('.image'+last_index).css("display","none"); 
	}   
	    
        if(is_paused) {
            //clearInterval(interval);
	    if(interval) {
		//clearInterval(interval);
		clearTimeout(interval);
		interval=false;
	    }
		 $('#optRadar').attr("checked",false);
	    $('.radar_pause').hide();
            $('.radar_play').show();
	} else {
		clearTimeout(interval);
		interval = setTimeout(function() { show_Next(false); }, timeInterval);
	}
	}
    }
  
    /* function for previous image */
    function show_Previous(is_paused) {
	if(imagemaxcount>0) {
	var frame=0;
	var count=staticRadarTimes.length-1;
            if(current_index == 0) {
               frame=staticRadarTimes.length-1;;
            } else {
                frame=current_index-1;
            }
	    
	    
	
	 var totalimagesdownload;
	 var imagesdownloaded;
	 
	 
	if(frame==0) {
		totalimagesdownload=totalfirstframeImages;
		imagesdownloaded=downloadedfirstframeImages;
	 } else if(frame==1) {
		totalimagesdownload=totalsecondframeImages;
		imagesdownloaded=downloadedsecondframeImages;
	 } else if(frame==2) {
		totalimagesdownload=totalthirdframeImages;
		imagesdownloaded=downloadedthirdframeImages;
	 } else if(frame==3) {
		totalimagesdownload=totalfourthframeImages;
		imagesdownloaded=downloadedfourthframeImages;
	 } else if(frame==4) {
		totalimagesdownload=totalfifthframeImages;
		imagesdownloaded=downloadedfifthframeImages;
	 } else if(frame==5) {
		totalimagesdownload=totalsixframeImages;
		imagesdownloaded=downloadedsixframeImages;
	 } else if(frame==6) {
		totalimagesdownload=totallastdownloadedImages;
		imagesdownloaded=downloadedlastdownloadedImages;
	 }
	 //console.log('frame'+frame+'total'+totalimagesdownload+'downloaded'+imagesdownloaded);
	if(parseInt(imagesdownloaded) >= parseInt(totalimagesdownload) && downloadedlastdownloadedImages>=totalRadar) {
		console.log('previous');
	
	var last_index=current_index;
	 if(current_index==0){
             current_index=staticRadarTimes.length-1;
          } else {
            current_index=current_index-1;
          }
	 
	 if(staticRadarTimes[current_index]!=='undefined')
		$('.maptimeText').html(timeToLocal(staticRadarTimes[current_index]));
         
	 /*for (i=0;i<radercodes.length;i++) {
           overlay[radercodes[i]].image_index=current_index;
           if(typeof overlay[radercodes[i]].images_[staticRadarTimes[current_index]] == 'undefined') {
                  
		   if(current_index>0) {
		    } else {
			overlay[radercodes[i]].image_='images/magic.gif';
		    }
            } else {
                  overlay[radercodes[i]].image_=overlay[radercodes[i]].images_[staticRadarTimes[current_index]];
	    }
	  overlay[radercodes[i]].radar_code=radercodes[i];
          overlay[radercodes[i]].NextImage();
         } */
	    $('.image'+last_index).addClass('hide_current');
	    $('.image'+last_index).removeClass('show_current');
	    $('.image'+current_index).removeClass('hide_current');
	    $('.image'+current_index).addClass('show_current');
		$('.image'+current_index).css("opacity",radar_opacity); 
		$('.image'+last_index).css("opacity","0"); 
		//$('.image'+current_index).css("display",""); 
		//$('.image'+last_index).css("display","none"); 
	}
       if(is_paused) {
            //clearInterval(interval);
	    if(interval) {
		//clearInterval(interval);
		clearTimeout(interval);
		interval=false;
	    }
		 $('#optRadar').attr("checked",false);
	    $('.radar_pause').hide();
            $('.radar_play').show();
	}
	}
    }
    
    
    /* indiviusal Loop all frame end*/
    
    /* change opacity of current images */
    function ChangeRadarOpacity() {
	
      for (i=0;i<radercodes.length;i++) {
             overlay[radercodes[i]].ChangeOpactiy();
	 }
       
    }

    /* function for loop end */
    function stopLoop() {
        if(interval) {
		//clearInterval(interval);
		clearTimeout(interval);
		interval=false;
	}
    }
    
    /* function for loop start */
    function loopstart() {
	if(interval) {
		//clearInterval(interval);
		clearTimeout(interval);
		interval=false;
	}
	
	interval = setTimeout(function() { show_Next(false); }, timeInterval);
        /*interval=setInterval(function() { 
            //console.log('call');
            show_Next(false);
            //show_all_overlay();
    
        },timeInterval); */   
    }
    
    /* function for set Radar control */
    function setMapRadarControl(radar_type) {
	$('#radar_confirm_display').hide();
	$('#select_map_range').val(radar_type);
	if($("#radar_display_images").attr("checked")) {
	    removeAllRadar();
	    stopLoop();
	    radarType=radar_type;
	    loadRadar(true);
	    setting[0].radar_type=radarType;
	    update_setting();
	    
	}
    }
    
    /* function for speed control */
    function change_speed_maintain_loop(speed) {
	if($("#radar_display_images").attr("checked")) {
	    timeInterval=speed;
	    setting[0].radar_speed=speed;
	    update_setting();
	 if($("#optRadar").attr("checked")) {
	    if(interval) {
		//clearInterval(interval);
		clearTimeout(interval);
		interval=false;
	    }
	    /*interval=setInterval(function() { 
		//console.log('call');
		show_Next(false);
		//show_all_overlay();
	
	    },timeInterval);*/
	    interval = setTimeout(function() { show_Next(false); }, timeInterval);
	  }
	}
    }
    
    /* function for Remove all Radar */
    function removeAllRadar() {
        for (i=0;i<radercodes.length;i++) {
           overlay[radercodes[i]].setMap(null); 
        }
         //overlay.length = 0;
         //radercodes.length = 0;
         /* reinitialize array */
         overlay=[];
         radercodes=[];
	 RadarTimes=[];
	 staticRadarTimes=[];
	 
	img12.onload = null;
	
	totalRadar=0;
	
	downloadedImages=0;
	
	downloadedlastdownloadedImages=0;
	downloadedfirstframeImages=0;
	downloadedsecondframeImages=0;
	downloadedthirdframeImages=0;
	downloadedfourthframeImages=0;
	downloadedfifthframeImages=0;
        downloadedsixframeImages=0;
	
	totallastdownloadedImages=0;
	totalfirstframeImages=0;
	totalsecondframeImages=0;
	totalthirdframeImages=0;
	totalfourthframeImages=0;
	totalfifthframeImages=0;
        totalsixframeImages=0;
	
	imagemaxcount=0;
	
	//img.removeEventListener("load", drawStitch,true);
	
	
        
    }
    
    /* Load radar Request */
    function loadRadar(is_loader) {
	$('.maptimeText').html('Loading Radars..');
	var mapbounds = map.getBounds();
	var viewportLatMin = mapbounds.getNorthEast().lat();
	var viewportLatMax = mapbounds.getSouthWest().lat();
	var viewportLonMin = mapbounds.getNorthEast().lng();
	var viewportLonMax = mapbounds.getSouthWest().lng();
	var viewportZoom = map.getZoom();
	
	if (mapbounds != null && mapbounds != undefined) {
		var parameter_data = "{ minLat: '" + viewportLatMin + "', \n\
                            maxLat: '" + viewportLatMax + "', \n\
                            minLon: '" + viewportLonMin + "', \n\
                            maxLon: '" + viewportLonMax + "', \n\
                            zoom: '" + viewportZoom + "',\n\
                            radarType: '" + radarType + "' \n\
		}";
		
		
		sendAjax('GetRadarImagesForViewPort', parameter_data, getRadarImagesForViewPortSuccess,
                     getRadarImagesForViewPortFailed , $(map.getDiv()),is_loader);
	}
	
    }
    
    /* Start Radar Timer */
    function RadarTimeStart() {
	 if(staticRadarTimes.length>0) {
	    //RadarTimeStop();
	    if(staticRadarTimes[radartimerIndex]!=='undefined')
	    $('.maptimeText').html(timeToLocal(staticRadarTimes[radartimerIndex]));
	    /*if(is_loop) {
		radartimesinterval=setInterval(function() {
		    //console.log('previous'+radartimerIndex);
		    var max_length=staticRadarTimes.length-1;
		     if(radartimerIndex==max_length)
			radartimerIndex=0;
		     else
			radartimerIndex=radartimerIndex+1;
			//console.log(radartimerIndex+RadarTimes[radartimerIndex]);
		     $('.maptimeText').html(staticRadarTimes[radartimerIndex]);
		      //console.log('next'+radartimerIndex);
			   
		},radarimagesTimerInterval);
	    } else {
		RadarTimeStop();
	    }*/	
	}
    }
    
    /* Stop Radar times */
    function RadarTimeStop() {
	if(interval) {
		clearInterval(interval);
		interval=false;
	}
	radartimerIndex=0;
	interval=false;
    }
    
    
    function radarTimesPadded(rit) {
	var orig = hashKeys(rit).sort();
	//console.log(orig);
	/*var pad = [];
	var lasto = null;
	// loop through original times, adding missing minutes
	for (var o in orig) {
	        var od = Date.fromISO(orig[o]);
		if (lasto != null) {
			var diff = Math.floor((od.getTime() - lasto.getTime())/1000/60);
			for (var p = 1; p < diff; p++) {
				pad.push(radarTimeAddMinutes(lasto,p));
			}
		}
		lasto = od;
		pad.push(orig[o]);
	}
	return pad.sort();*/
	return orig;
    }
    
    
    function radarTimeAddMinutes(date, minutes)
    {
	    var d = new Date(date.getTime() + minutes * 60000);
	    //2013-02-12 06:00:00Z
	    var ds = d.getUTCFullYear() + '-' + padTimePart(d.getUTCMonth() + 1) + '-' + padTimePart(d.getUTCDate()) + ' ' +
	    padTimePart(d.getUTCHours()) + ':' + padTimePart(d.getUTCMinutes()) + ':' + padTimePart(d.getUTCSeconds()) + 'Z';
	    return ds;
    }
    
    function roundMinutes(date) {
	var d = new Date(date.getTime());
	var minutes=d.getUTCMinutes();
	 minutes=parseInt(minutes);
	var round_minutes=Math.ceil(minutes / 10) * 10;
	var diff=round_minutes-minutes;
	if(diff>0) {
		var d = new Date(date.getTime() + diff * 60000);
	} else {
		 var d = new Date(date.getTime());
	}
	
	 var ds = d.getUTCFullYear() + '-' + padTimePart(d.getUTCMonth() + 1) + '-' + padTimePart(d.getUTCDate()) + ' ' +
	    padTimePart(d.getUTCHours()) + ':' + padTimePart(d.getUTCMinutes()) + ':' + padTimePart(d.getUTCSeconds()) + 'Z';
	//return minutes;
	//console.log(ds);
	return ds;
    }
    
    function radarTimeSubtractMinutes(date, minutes)
    {
	    var d = new Date(date.getTime() - minutes * 60000);
	    //2013-02-12 06:00:00Z
	    var ds = d.getUTCFullYear() + '-' + padTimePart(d.getUTCMonth() + 1) + '-' + padTimePart(d.getUTCDate()) + ' ' +
	    padTimePart(d.getUTCHours()) + ':' + padTimePart(d.getUTCMinutes()) + ':' + padTimePart(d.getUTCSeconds()) + 'Z';
	    return ds;
    }
    
    function padTimePart(num) {
	    return new String(num).charPadLeft('0', 2);
    }
    
    String.prototype.repeat = function (num) {
	    if (num < 1) return '';
	    if (num == 1) return this;
	    return new Array(num + 1).join(this);
    }
    String.prototype.charPadLeft = function (padChar, maxLength) {
	    if (this == null || this.length >= maxLength)
		    return this;
	    var padded = new String(padChar.repeat(maxLength - this.length) + this);
	    return padded;
    }
    
    
    function hashMerge(h1, h2) {
	    var out = {};
	    if (h1 != null && h1 != undefined) {
		    if (h1 instanceof Array) {
			    var maxX = h1.length;
			    for (var x = 0; x < maxX; x++) {
				    out[h1[x]] = h1[x];
			    }
		    }
		    else if (h1 instanceof Object) {
			    for (var key in h1) {
				    out[key] = h1[key];
			    }
		    }
	    }
	    if (h2 != null && h2 != undefined) {
		    if (h2 instanceof Array) {
			    var maxY = h2.length;
			    for (var y = 0; y < maxY; y++) {
				    out[h2[y]] = h2[y];
			    }
		    }
		    else if (h2 instanceof Object) {
			    for (var key in h2) {
				    out[key] = h2[key];
			    }
		    }
	    }
	    return out;
    }
    
    
    function hashKeys(hash) {
	    var array_keys = new Array();
	    for (var key in hash) {
		    array_keys.push(key);
	    }
	    return array_keys;
    }
    
    function hideConfirmbox () {
       $('#radar_confirm_display').hide();
   }
    
    