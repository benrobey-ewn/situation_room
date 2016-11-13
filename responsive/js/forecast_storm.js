var allmonth = new Array();
allmonth[1] = "Jan";
allmonth[2] = "Feb";
allmonth[3] = "Mar";
allmonth[4] = "Apr";
allmonth[5] = "May";
allmonth[6] = "Jun";
allmonth[7] = "Jul";
allmonth[8] = "Aug";
allmonth[9] = "Sep";
allmonth[10] = "Oct";
allmonth[11] = "Nov";
allmonth[12] = "Dec";

var Stormoverlay=null;
var init = true;
var opacity=1;
var forecastoverlay=[];
var total_time_lists;
var allforecastoverlay=[];
var time_for_loop = 5000;
var current_image_counter=0;
var intervaltime;
//var mydatetimearray = array();

StormcastOverlay.prototype = new google.maps.OverlayView();


 function generateChartAjax()
 {
    var storm_url = 'ajax/get_forecast_storm.php';
        $.ajax({
            type: "POST",
            url: storm_url,
            dataType: "json",
            success: function(response)
            {
                var mydate = new Date();
                var thisyear = mydate.getFullYear(); 
                var thismonth = mydate.getMonth(); 
                var all_days = response.hours.length;
                console.log(all_days);
                total_time_lists = all_days;
                //console.log(response.days);
                //console.log(all_days);


                for (i=0;i<all_days;i++) 
                {
                    var singleday = response.hours[i];
                    //console.log(singleday);
                    var endtime = singleday.slice(-2);
                    var myyear = singleday.substring(0, 4);
                    var mymonth = singleday.substring(4, 6);
                    var starttime = singleday.substring(8, 10);
                    var dateinfo = singleday.substring(6, 8);
                    var my_time_mode;


                    if(starttime>12)
                    {
                        starttime = parseInt(starttime)-12;
                        my_time_mode = 'pm';
                    }
                    else
                    {
                        my_time_mode = 'am';
                    }



                    var monthname = allmonth[mymonth]; 
                    var my_final_string = monthname+' '+dateinfo+', '+myyear+' '+starttime+':'+endtime+' '+my_time_mode;
                    var selectoption = '<option value='+i+'>'+my_final_string+'</option>';
                    $( "#forecast_module_datetime" ).append(selectoption );

                   

                }
            }
        });
    
 }


 //function  generateChart()
function StormcastOverlay(bounds, image, map) {
        this.bounds_ = bounds;
        this.image_ = image;
        this.map_ = map;
        this.div_ = null;
        this.images_ = [];
        this.image_index = 0;
        this.setMap(map);
      }

      StormcastOverlay.prototype.onAdd = function() {
      
        var div = document.createElement('div');
        div.style.borderStyle = 'none';
        div.style.borderWidth = '0px';
        div.style.position = 'absolute';
      
        // Create the img element and attach it to the div.
        var img = document.createElement('img');
        img.src = this.image_;
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.position = 'absolute';
        img.style.opacity = opacity;
        img.style.filter  = 'alpha(opacity=90)'; // IE fallback
        div.appendChild(img);
       
     	img.style.display = 'none';
     	img.onerror = function(){
            console.log('img error');
            this.src='images/magic.gif';
            img.style.display = '';
        }
    	img.onload = function(){
        console.log('img success');
            img.style.display = '';
        img.style.display = '';
        }
        
        div.setAttribute('class',"forecast_images") ;
        this.div_ = div;
       
       // Add the element to the "overlayLayer" pane.
        var panes = this.getPanes();
        panes.overlayLayer.appendChild(div);

      };

      StormcastOverlay.prototype.draw = function() {
        if (this.div_) {
        var overlayProjection = this.getProjection();
        var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
        var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

        var div = this.div_;
        div.style.left = sw.x + 'px';
        div.style.top = ne.y + 'px';
        div.style.width = (ne.x - sw.x) + 'px';
        div.style.height = (sw.y - ne.y) + 'px';
        }
      };
	  
      StormcastOverlay.prototype.NextImage = function() {
         x=this.div_;
         y=x.childNodes[0];
         var img=y;
         img.src = this.image_;
      };

      StormcastOverlay.prototype.onRemove = function() {
        this.div_.parentNode.removeChild(this.div_);
        this.div_ = null;
      };
      
      StormcastOverlay.prototype.changeOpacity = function() {
         x=this.div_;
         y=x.childNodes[0];
         var img=y;
         img.style.opacity = opacity;
      };

      StormcastOverlay.prototype.hide = function() {
      if (this.div_) {
        // The visibility property must be a string enclosed in quotes.
        this.div_.style.visibility = 'hidden';
      }
    };

      StormcastOverlay.prototype.show = function() {
          console.log(this.div_);
        if (this.div_) {
          this.div_.style.visibility = 'visible';
        }
      };




      
   function generateChart(isfire)
      {
        hideimages();
        var chart  = $('#forecasts_types_select').val();
        var chartTime = $('#forecast_module_datetime').val();
        var forecast_model = $('#forecast_model').val();
        getallimagesstorm(chart,total_time_lists,forecast_model);
        

        //console.log(chart+'>>>>'+chartTime+'>>>>'+forecast_model);

    }
    
    
    function getallimagesstorm(map_type,times,forecast_model)
    {
        $('#loader').show();
        if(forecast_model!='')
        {
            if(map_type!='')
            {
                var new_times = times-1;
                for(myimage=0;myimage<times;myimage++)
                {
                    var ne = map.getBounds().getNorthEast();
                    var sw = map.getBounds().getSouthWest();
                    var neLat = ne.lat()+5;
                    var neLon = ne.lng()+5;
                    var swLat = sw.lat()-5;
                    var swLon = sw.lng()-5;


                    if (swLat < -55) { swLat = -55; }
                    if (swLon < 100) { swLon = 100; }
                    if (neLat > 0) { neLat = 0; }
                    if (neLon > 170 || neLon < 0) { neLon = 170; }
                    
					  
                    var url = 'http://cloud2.stormcast.com.au/stormcast/map.interface.php?swLat=' + swLat + '&swLon=' + swLon + '&neLat=' + neLat + '&neLon=' + neLon + '&chartTime=' + myimage + '&chart=' + map_type;
					
					               
                    //console.log(url);
                    var swBound = new google.maps.LatLng(swLat, swLon);
                    var neBound = new google.maps.LatLng(neLat, neLon);
                    var bounds = new google.maps.LatLngBounds(swBound, neBound);
                     
		     var load_url=url;		
		     if(myimage==0){
                        if(current_image_counter>0) {
                           $('#forecast_module_datetime').val(current_image_counter);
                           load_url = 'http://cloud2.stormcast.com.au/stormcast/map.interface.php?swLat=' + swLat + '&swLon=' + swLon + '&neLat=' + neLat + '&neLon=' + neLon + '&chartTime=' + current_image_counter + '&chart=' + map_type;
                        } else {
                           load_url=url;
                        }
                        Stormoverlay = new StormcastOverlay(bounds, url, map);
                        Stormoverlay.images_.push(url);
                        allforecastoverlay.push(Stormoverlay);
                        allforecastoverlay[0].image_index=current_image_counter;
                        //$('#forecast_module_datetime').val(current_image_counter);
                     } else {
			Stormoverlay=allforecastoverlay[0];
			Stormoverlay.images_.push(url);
		      }
                      
                      
					
                    if(myimage==new_times)
                    {
                       // console.log(myimage);
                       // console.log(new_times);
                         $('#loader').hide();
                    }
                }
            }
            else
            {
                $('#loader').hide();
                $('.forecast_images').hide();  
                $('#forecasts_types_select').val('');
                $('#forecast_module_datetime').val('');
                clearInterval(intervaltime);
                $('.forecast_storm_play').show();
                $('.forecast_storm_pause').hide();
            }
        }
        else
        {
            $('#loader').hide();
            $('.forecast_images').hide();  

            $('#forecasts_types_select').val('');
            $('#forecast_module_datetime').val('');
            clearInterval(intervaltime);
            $('.forecast_storm_play').show();
            $('.forecast_storm_pause').hide();
        }
    }
    
    /* show current image */
    function showcurrentimage(time)
    {
        current_image_counter=parseInt(time);
        allforecastoverlay[0].image_index=current_image_counter;
        allforecastoverlay[0].image_=allforecastoverlay[0].images_[current_image_counter];
        allforecastoverlay[0].NextImage();
        $('#forecast_module_datetime').val(current_image_counter);
            
    }
    
    /* show Next image */	
    function shownextimage(is_paused)
    {
         if(allforecastoverlay[0].images_.length>1) { 
            var count=allforecastoverlay[0].images_.length-1;
            if(allforecastoverlay[0].image_index == count) {
                  current_image_counter=0;
               } else {
                   current_image_counter=allforecastoverlay[0].image_index+1;
               }
            
               allforecastoverlay[0].image_index=current_image_counter;
               allforecastoverlay[0].image_=allforecastoverlay[0].images_[current_image_counter];
               allforecastoverlay[0].NextImage();
               $('#forecast_module_datetime').val(current_image_counter);
               
               if(is_paused) {
                     loopforecaststop();
               }
         }
   }
    /* show previous images */
    function showpreviousimage(is_paused)
    {
         if(allforecastoverlay[0].images_.length>1) { 
            if(allforecastoverlay[0].image_index==0){
               current_image_counter=allforecastoverlay[0].images_.length-1;
            } else {
              current_image_counter=allforecastoverlay[0].image_index-1;
            }
             allforecastoverlay[0].image_index=current_image_counter;
             allforecastoverlay[0].image_=allforecastoverlay[0].images_[current_image_counter];
             allforecastoverlay[0].NextImage();
             $('#forecast_module_datetime').val(current_image_counter);
              if(is_paused) {
                 loopforecaststop();
              }
         }
    }

    /* loop start */
    function loopforecaststart(is_paused)
    {
             clearInterval(intervaltime);
            $('.forecast_storm_play').hide();
            $('.forecast_storm_pause').show();

            intervaltime = setInterval(function(){
                shownextimage(false);
                console.log(time_for_loop);
                console.log('image change');
            }, time_for_loop);
       
    }

    /* loop stop */
    function loopforecaststop()
    {
       
           clearInterval(intervaltime);
            $('.forecast_storm_play').show();
            $('.forecast_storm_pause').hide();
            
    }
    
    /* hide all images */
    function hideimages(img_index)
    {
        if(typeof allforecastoverlay[0] == 'undefined') {
	 } else {
            //allforecastoverlay[0].hide();
            allforecastoverlay[0].onRemove();
            allforecastoverlay.length=0;
            Stormoverlay=null;
         }
         allforecastoverlay.length=0;
        
    }
    /* Remove all images and forecast object */
    function remove_all_images()
    {
	 if(typeof allforecastoverlay[0] == 'undefined') {
	 } else {
	    allforecastoverlay[0].onRemove();
            allforecastoverlay[0].images_.length=0;
            Stormoverlay=null;
	 }
         allforecastoverlay.length=0;
         
    }


    function change_opacity()
    {
         if(typeof allforecastoverlay[0] == 'undefined') {
	 } else {
         allforecastoverlay[0].changeOpacity();
         }
    }


