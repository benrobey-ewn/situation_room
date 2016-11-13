<?php if($_SERVER['PHP_AUTH_USER']=='Media' || $_SERVER['PHP_AUTH_USER']=='admin') {?>
<div class="showcontact" id="showcontact">
      	<a href="#"><img src="images/contact_ewn.png" ></a>
</div>
<?php } ?>
<div id="showcontantdetails" style="display: none;">
    <div id="closecontact"><a title="Close">x</a></div>
    <h3>CONTACTS</h3>
    <ul style="margin-top: 0px !important;">
        <li>
            <ul style="margin-top: 0px !important;">
                <li><span class="heading">Kerry Plowright</span></li>
                <li>Managing Director</li>
                <li>Early Warning Network</li>
                <li>Jenners Corner</li>
                <li>120 Chinderah Bay Road,</li>
                <li>Chinderah  NSW 2487 </li>
                <li><a href="mailto:kerry@ewn.com.au" >kerry@ewn.com.au</a></li>
                <li>Phone: +61 2 66742711</li>
                <li>Mobile: 0403 147 197</li>
            </ul>
        </li>  
        <li style="border-bottom:none;">
            <ul>
                <li><span class="heading">Ken Kato</span></li>
                <li>Forecaster and Media Officer</li>
                <li>Early Warning Network</li>
                <li>Jenners Corner</li>
                <li>120 Chinderah Bay Road,</li>
                <li>Chinderah  NSW 2487 </li>
                <li><a href="mailto:ken@ewn.com.au" >ken@ewn.com.au</a></li>
                <li>Mobile: 0403 147 197</li>
            </ul>
        </li>
            
    </ul>
</div>
<style>
	select
	{
		height:22px;
	}
</style>

<?php
switch (isset($_GET['p']) && $_GET['p']) {
  case 'adelaide':
  $radar_code = '462';
  $chart='adl';
    # code...
    break;

  case 'brisbane':
  $radar_code = '662';
  $chart='bne';
    # code...
    break;


  case 'canberra':
  $radar_code = '402';
  $chart='cbr';
    # code...
    break;


  case 'darwin':
  $radar_code = '632';
  $chart='drw';
    # code...
    break;


  case 'hobart':
  $radar_code = '762';
  $chart='hob';
    # code...
    break;

  case 'melbourne':
  $radar_code = '022';
  $chart='mel';
    # code...
    break;

  case 'perth':
  $radar_code = '702';
  $chart='per';
    # code...
    break;


  case 'sydney':
  $radar_code = '713';
  $chart='syd';
    # code...
    break;
  
  default:
  $radar_code = '713';
  $chart='aus';
    # code...
    break;
}
?>


      <div id="Popcontainer" class="Popcontainer" style="display:none;">
      <div style="position:relative">
      <!--<div class="logo"></div>
      <div class="hide"><a href="#" id="closemenu">HIDE</a></div>-->
      <div class="logo"><img src="images/logo.png" width="113" height="27" alt="" /></div>
      <div class="refresh">
       <a href="#" onclick="refresh()">Refresh</a>
      </div>
      <div class="hide">
      	<a href="#" id="closemenu"><img src="images/hide.png" ></a>
      </div>
      <h1>Situation Room</h1>
      <div class="Room">
          <ul>
          <li> 
              <!--First Box-->
              <ul>
            <li> <span>
              <h2 class="nomargin">Radar</h2>
              <input type="checkbox" checked="checked" id="radar_display_images" name="radar_display_images" class="regular-checkbox static_radars">
              <label for="radar_display_images"></label>
              </span> <span>
              <h2>Loop</h2>
              <input type="checkbox" id="optRadar" name="optRadar" class="regular-checkbox loop_radars" />
              <label for="optRadar"></label>
              </span> <span>
              <h2>Cell (Storm) Info</h2>
              <input type="checkbox" id="checkbox-1-3" class="regular-checkbox">
              <label for="checkbox-1-3"></label>
              </span> <span style="margin-top:-2.5px;">
              <select id="select_map_range" name="select_map_range" name="drop" class="Selectmargin" onchange="javascript:setMapRadarControl(this.value);">
                 <option value="64">64 km</option>
                <option value="128">128 km</option>
                <option value="256" <?php if(isset($_GET['p'])) { ?> selected="selected" <?php } ?>>256 km</option>
                <option value="512" <?php if(!isset($_GET['p'])) { ?> selected="selected" <?php } ?> >512 km</option>
              </select>
              </span> </li>
            <li> <span> Radar Loop Controls </span> <span>
              <div class="Controls">
                <ul>
                    <li class="play radar_play"><a href="#" id="play" title="Play" onclick="">play</a></li>
                    <li class="pause radar_pause"><a href="#" id="pause" title="Pause">pause</a></li>
                    <li class="reverse radar_reverse" id="reverse"><a href="#" id="prev" title="Previous" onclick="javascript:show_Previous(true);">reverse</a></li>
                    <li class="forward radar_forward" id="forward"><a href="#" id="next" title="Forward" onclick="javascript:show_Next(true);">forward</a></li>
                </ul>
              </div>
              </span> <span style="margin-top:-2.5px;">
               <select name="drop" id="loop_speed_select" onchange="javascript:change_speed_maintain_loop(this.value);">
                <option value="1500">Slow</option>
                <option value="700" selected="selected">Normal</option>
                <option value="250" >Fast</option>
              </select>
              </span> </li>
              
              <li style="margin-top: 5px;">
              <span class="width60">
                <h3 class="nomargin" style="margin-right: 5px;">Show Weather Radar Sites</h3>
                <input checked="checked" type="checkbox" class="regular-checkbox wx_radar_sites" id="wx_radar_sites">
                <label for="wx_radar_sites"></label>
                </span>
              <span class="width40" style="margin-top:-5px;">
                <h3 style="margin-left: 0px; margin-top: 5px;"> Opacity </h3>
                <select name="radar_opacity" id="radar_opacity" style="float: left">
                  <option value='0'>0%</option>
                  <option value='.1'>10%</option>
                  <option value='.2'>20%</option>
                  <option value='.3'>30%</option>
                  <option value='.4'>40%</option>
                  <option value='.5'>50%</option>
                  <option value='.6'>60%</option>
                  <option value='.7'>70%</option>
                  <option value='.8'>80%</option>
                  <option value='.9'>90%</option>
                  <option value='1' selected>100%</option>
                </select>
             </span>
            </li>
            <li> <span>
              <h2 class="nomargin">Lightning</h2>
              <input type="checkbox" id="checkbox-1-4" class="regular-checkbox">
              <label for="checkbox-1-4"></label>
              </span> <span>
              <h2>Loop</h2>
              <input type="checkbox" id="checkbox-1-5" class="regular-checkbox">
              <label for="checkbox-1-5"></label>
              </span>
              <span>
              <h2>(Coming Soon)</h2>
              </span>

               </li>
            <li> <span> Lightning Loop Controls </span> <span>
              <div class="Controls">
                <ul>
                  <li class="play"><a href="#" title="Play">play</a></li>
                  <li class="pause"><a href="#" title="Pause">pause</a></li>
                  <li class="reverse"><a href="#" title="Reverse">reverse</a></li>
                  <li class="forward"><a href="#" title="Forward">forward</a></li>
                </ul>
              </div>
              </span> <span style="margin-top:-2.5px;">
              <select name="drop">
                <option value="128">Faster</option>
                <option value="125">Slow</option>
                <option value="100">Slower</option>
              </select>
              </span> </li>

             

            
          </ul>
              <!--/First Box--> 
            </li>
        <li> 
              <!--Second Box-->
              <ul>
            <li style="padding-bottom: 0px;">
            <span class="width33" style="margin-right:15px;">
              <h2 class="nomargin">Observations</h2>
              <input type="checkbox" id="checkbox-1-6" class="regular-checkbox observation_checkbox">
              <label for="checkbox-1-6"></label>
              </span>
              <span class="width38">
                  <h3 class="nomargin" style="margin-right:10px;" >Cities only</h3>
                    <input type="checkbox" id="checkbox-1-8" class="regular-checkbox observation_option1">
                    <label for="checkbox-1-8"></label>
                    </span>
                <span  class="width22">
                  <!--<h3>Capital Cities / Towns</h3>-->
		    <h3 style="margin-right:10px;">ALL</h3>
                    <input checked="checked" type="checkbox" id="checkbox-1-9" class="regular-checkbox observation_option2">
                    <label for="checkbox-1-9"></label>
                    </span>
               </li>
           
          </ul>
              <!--/Second Box--> 
          </li>
         <!-- rainfall gage -->
          <li> 
               <!--Third Box-->
               <ul>
                <li>
                    <span class="width32" style="margin-right: 10px;">
                      <h2 class="nomargin" style="margin-right: 10px; line-height: 13px;">Rainfall Gauges</h2>
                        <input  type="checkbox" id="checkbox-rainfall" class="rainfall regular-checkbox">
                      <label for="checkbox-rainfall" style="margin-top:1px;"></label>
                  </span>
                    
                  <span class="width34" style="margin-top:-4px;">
                        <select name="rainfall_types" id="rainfall_types">
                                    <option value='1hr' selected="selected">Last Hours</option>
                                    <option value='9am'>Since 9 am</option>
                                    <option value='24to9am'>24 to 9 am</option>
                                    <option value='6hr'>6 Hours</option>
                                    <option value='24hr'>24 Hours</option>
                                    <option value='72hr'>72 Hours</option>
                        </select>
                  </span>
                   
                </li>
                
                <li>
                  <span class="width50">
                    <h3 class="nomargin" style="float:left;">0 mm</h3>
                                <input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall1" class="regular-checkbox rainfall_checkboxes" value="0" onclick="toggleKML(this.checked, this.value)">
                        <label for="checkbox-rainfall1" style="float:right;"></label>
                    </span>
                  <span class="width50">
                        <h3 class="marginleft10" style="float:left;">0.2 - 9.9 mm</h3>
                        <input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall2" class="regular-checkbox rainfall_checkboxes" value="9.9" onclick="toggleKML(this.checked, this.value)">
                        <label for="checkbox-rainfall2" style="float:right"></label>
                  </span>
                    
                    
                   
                </li>
              <li>
                
                <span class="width50">
                    <h3 class="nomargin" style="float:left;">10.0 - 24.9 mm</h3>
                                <input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall3" class="regular-checkbox rainfall_checkboxes" value="24.9" onclick="toggleKML(this.checked, this.value)">
                        <label for="checkbox-rainfall3" style="float:right"></label>
                    </span>
                <span class="width50">
                <h3 class="marginleft10" style="float:left;"> 25.0 - 49.9 mm</h3>
                <input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall4" class="regular-checkbox rainfall_checkboxes" value="50.0" onclick="toggleKML(this.checked, this.value)">
                    <label for="checkbox-rainfall4" style="float:right"></label>
                </span>
                 
                   
              </li>
              <li>
                <span class="width50">
                <h3 class="nomargin" style="float:left;" >50.0 - 99.9 mm</h3>
                  <input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall5" class="regular-checkbox rainfall_checkboxes" value="99.9" onclick="toggleKML(this.checked, this.value)">
                  <label for="checkbox-rainfall5" style="float:right;"></label>
              </span>
                <span class="width50">
                <h3 class="marginleft10" style="float:left;"> 100 - 199.9 mm</h3>
                <input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall6" class="regular-checkbox rainfall_checkboxes" value="199.9" onclick="toggleKML(this.checked, this.value)">
                    <label for="checkbox-rainfall6" style="float:right"></label>
                </span>
              </li>
              <li>
                <span class="width50">
                <h3 class="nomargin" style="float:left;" >200 mm+</h3>
                  <input name="rainfall_option[]" type="checkbox" id="checkbox-rainfall7" class="regular-checkbox rainfall_checkboxes" value="200.0" onclick="toggleKML(this.checked, this.value)">
                  <label for="checkbox-rainfall7" style="float:right;"></label>
              </span>
               
              </li>
              
              
            <!--/Third Box-->
          </ul>
              <!--/Second Box--> 
          </li>
          <li> 
              <!--Second Box-->
              <ul>
            <li style="padding-bottom: 0px;">
              <span class="width33">
              <h2 class="nomargin">Forecasts</h2>
              <input type="checkbox" id="radar_forecasts_information" name="radar_forecasts_information" class="regular-checkbox forecast_checkbox">
              <label for="radar_forecasts_information"></label>
              </span> </li>
          
          </ul>
              <!--/Second Box--> 
          </li>
          <li> 
               <!--Third Box-->
               <ul>
                <li>
                    <span class="width32" style="margin-right: 10px;">
                      <h2 class="nomargin" style="margin-right: 10px; line-height: 13px;">Warnings</h2>
                        <input  type="checkbox" id="checkbox-1-14" class="weather regular-checkbox">
                      <label for="checkbox-1-14" style="margin-top:1px;"></label>
                  </span>
                   
               
                    
                        
                </li>
                 <li>
                       <span class="width34" style="margin-right: 10px; ">
                        <h2 class="nomargin" style="margin-right: 5px; line-height: 13px;">Current</h2>
                        <input type="radio" checked class="warning_type" name="warning_type" value="1"  >
                      </span>
                      <span class="width34" style="margin-top:-4px; ">
                      <h2 class="nomargin" style="margin-right: 5px; margin-top: 5px;">Warnings last</h2>
                        <input type="radio" class="warning_type" name="warning_type" value="2" >
                            <select name="warning_days" id="warning_days">
                                    <option value='6'>6 hours</option>
                                    <option value='12'>12 hours</option>
                                    <option value='18'>18 hours</option>
                                    <option value='24'>24 hours</option>
                                    <option value='30'>30 hours</option>
                                    <option value='36'>36 hours</option>
                                    <option value='42'>42 hours</option>
                                    <option value='48' selected="selected">48 hours</option>
                            </select>
                      
                  </span></li>
              <li>
                  <span class="width50">
                    <h3 class="nomargin" style="float:left;">Severe Thunderstorm</h3>
                                <input name="warning_option[]" type="checkbox" id="checkbox-1-16" class="regular-checkbox warning_checkboxes" value="28">
                        <label for="checkbox-1-16" style="float:right;"></label>
                    </span>
                  <span class="width50">
                        <h3 class="marginleft10" style="float:left;">Severe Weather</h3>
                        <input name="warning_option[]" type="checkbox" id="checkbox-1-15" class="regular-checkbox warning_checkboxes" value="23,33,34,35">
                        <label for="checkbox-1-15" style="float:right"></label>
                  </span>
                    
                    
                   
                </li>
              <li>
                
                <span class="width50">
                    <h3 class="nomargin" style="float:left;">Flood Watch</h3>
                                <input name="warning_option[]" type="checkbox" id="checkbox-1-106" class="regular-checkbox warning_checkboxes" value="14">
                        <label for="checkbox-1-106" style="float:right"></label>
                    </span>
                <span class="width50">
                <h3 class="marginleft10" style="float:left;"> Fire Weather Warning</h3>
                <input name="warning_option[]" type="checkbox" id="checkbox-1-18" class="regular-checkbox warning_checkboxes" value="13">
                    <label for="checkbox-1-18" style="float:right"></label>
                </span>
                 
                   
              </li>
              <li>
                <span class="width50">
                <h3 class="nomargin" style="float:left;" >Bushfire Warning</h3>
                  <input name="warning_option[]" type="checkbox" id="checkbox-1-22" class="regular-checkbox warning_checkboxes" value="36,BW">
                  <label for="checkbox-1-22" style="float:right;"></label>
              </span>
                
                <span  class="width50">
                 <h3 class="marginleft10" style="float:left;">Bushfire Watch & Acts</h3>
                  <input name="warning_option[]" type="checkbox" id="checkbox-1-105" class="regular-checkbox warning_checkboxes" value="36,BWA">
                  <label for="checkbox-1-105" style="float:right;"></label>
              </span>
                
              </li>
              <li>
                 <span class="width50">
                    <h3 class="nomargin" style="float:left;">Tsunami</h3>
                <input name="warning_option[]" type="checkbox" id="checkbox-1-21" class="regular-checkbox warning_checkboxes" value="31">
                <label for="checkbox-1-21" style="float:right;"></label>
                  </span>
                <span class="width50">
                        <h3 class="marginleft10" style=" float:left;">Tropical Cyclones</h3>
                <input name="warning_option[]" type="checkbox" id="checkbox-1-20" class="regular-checkbox warning_checkboxes" value="29">
                <label for="checkbox-1-20" style="float:right;"></label>
              </span>
              
              </li>
              <li>
                 <span  class="width50">
                 <h3 class="nomargin" style="float:left;">Other</h3>
                  <input name="warning_option[]" type="checkbox" id="checkbox-1-23" class="regular-checkbox warning_checkboxes" value="<?php echo OTHER_IDS; ?>">
                  <label for="checkbox-1-23" style="float:right;"></label>
              </span>
                <span class="width50">
                  <h3 class="marginleft10" style="float:left;">All</h3>
                  <input name="warning_option[]" type="checkbox" id="checkbox-1-all" class="regular-checkbox warning_all"  checked="checked"value="all">
                  <label for="checkbox-1-all" style="float:right;"></label>
              </span>
              </li>
               <li style="margin-bottom:10px;">
                 <span  class="width50">
                 <h3 class="nomargin" style="float:left;">
                 	<input type="button" onclick="javascript:polygon_show_range()" value="Show Assets/Projects Under Threat" class="assets">
				        </h3>
              </span>
               <span class="width50" id="warning_report_generation" style="display:none;">
                  <h3 class="marginleft10" style="float:right;"><a style="text-decoration:none;" href="JavaScript:newPopup('common/report.php','800','660');"><input class="assets" type="button" value="Report"></a>
</h3>
              </span>
              </li>
            <!--/Third Box-->
          </ul>
            </li>
        <li> 
              <!--Fourth Box-->
             <ul>
                <li> <span>
              <h2 class="nomargin" style="margin-right: 3px;margin-top:2px;">Forecast Severe Weather</h2>
              <input type="checkbox" id="checkbox-1-24" class="forecastsevere regular-checkbox" style="margin-top:2px;">
              <label for="checkbox-1-24"></label>
              <?php
                $current_time=date("H:i:s");
                if($current_time>='09:00:00') { 
                    $current_day = date('D')." 9am + 24hrs";
                    $tomorrow = date("D", time() + 86400)." 9am + 24hrs";
                } else {
                    $current_day= date("D", time() - 86400)." 9am + 24hrs";
                    $tomorrow  = date('D')." 9am + 24hrs";
                }
              ?>

              <select name="drop" class="Selectmargin" name="forecast_severe_weather_option" id="forecast_severe_weather_option">
                <option value="1"><?php echo $current_day; ?></option>
                <option value="2"><?php echo $tomorrow; ?></option>
              </select>
              </span>
              </li>
           
              <li>
                 <span class="width50">
                    <h3 class="nomargin" style="float:left;">Severe Thunderstorms</h3>
                <input name="forecast_warning_option[]" type="checkbox" id="checkbox-1-201" class="regular-checkbox warning_checkboxes" value="38">
                <label for="checkbox-1-201" style="float:right;"></label>
                  </span>
                <span class="width50">
                        <h3 class="marginleft10" style=" float:left;">Heavy Rainfall</h3>
                <input name="forecast_warning_option[]" type="checkbox" id="checkbox-1-202" class="regular-checkbox warning_checkboxes" value="37">
                <label for="checkbox-1-202" style="float:right;"></label>
              </span>
              
              </li>
              <li>
                 <span class="width50">
                    <h3 class="nomargin" style="float:left;">Severe Winds</h3>
                <input name="forecast_warning_option[]" type="checkbox" id="checkbox-1-203" class="regular-checkbox warning_checkboxes" value="39">
                <label for="checkbox-1-203" style="float:right;"></label>
                  </span>
                <span class="width50">
                        <h3 class="marginleft10" style=" float:left;">Tropical Cyclone</h3>
                <input name="forecast_warning_option[]" type="checkbox" id="checkbox-1-204" class="regular-checkbox warning_checkboxes" value="40">
                <label for="checkbox-1-204" style="float:right;"></label>
              </span>
              
              </li>
            <!--/Third Box-->
          </ul>
            </li>
        <li> 
              <!--Fifth Box-->
              <ul>
            <li>
              <span  class="width33">
                  <h2 class="nomargin" >Satellite</h2>
                  <input type="checkbox" id="checkbox-1-34" class="regular-checkbox">
                  <label for="checkbox-1-34"></label>
                </span>
                <span  class="width35">
                  <h2>Loop</h2>
                  <input type="checkbox" id="checkbox-1-35" class="regular-checkbox">
                  <label for="checkbox-1-35"></label>
              </span>
                <span  class="width31">
              <h2>(Coming Soon)</h2>
              </span>
          </li>
          <li>
            <span  class="width33">
              <h3 class="nomargin">Infra-red</h3>
              <input type="checkbox" id="checkbox-1-36" class="regular-checkbox">
              <label for="checkbox-1-36"></label>
            </span>
            <span  class="width35">
              <h3>Visual</h3>
              <input type="checkbox" id="checkbox-1-37" class="regular-checkbox">
              <label for="checkbox-1-37"></label>
              </span>
              <span  class="width31">
              <h3 class="nomargin">Water Vapour</h3>
              <input type="checkbox" id="checkbox-1-38" class="regular-checkbox">
              <label for="checkbox-1-38"></label>
              </span>
              </li>
            <li>
            <span> Satellite Loop Controls </span> <span>
              <div class="Controls">
                <ul>
                  <li class="play"><a href="#" title="Play">play</a></li>
                  <li class="pause"><a href="#" title="Pause">pause</a></li>
                  <li class="reverse"><a href="#" title="Reverse">reverse</a></li>
                  <li class="forward"><a href="#" title="Forward">forward</a></li>
                </ul>
              </div>
              </span> <span style="margin-top:-2.5px;">
              <select name="drop">
                <option value="128">3 Hours</option>
              </select>
              </span> </li>
          </ul>
            </li>
        <li> 
              <!--Fourth Box-->
              <ul>

        <li style="margin-bottom: 7px;"> <span >
              <h2 class="nomargin" style="padding-top:5px;">Map Option</h2>
              <select id="map_options_selector" name="drop" class="Selectmargin" onchange="javascript:change_map_type(this.value);" style="margin-top:-2px;">
                <option value="ROADMAP" selected="selected">Google Map View</option>
                <option value="SATELLITE" >Google Satellite View</option>
                <option value="TERRAIN">Terrain view</option>
              </select>
              </span> </li>
        
            <li>
                 <span class="width50">
                    <h3 class="nomargin" style="float:left;">Weather Regions</h3>
                <input type="checkbox" id="showdistricts" onclick="javascript:toggleShowDistricts();" class="regular-checkbox">
              <label for="showdistricts" style="float: right;"></label>
                  </span>
              <span  class="width50">
                <h3 class="marginleft10" style="float:left;">River Catchments&nbsp;</h3>
            <input type="checkbox" class="regular-checkbox" onclick="javascript:toggleShowRiverBasins();" id="showriverbasins" style="padding-left:5px;">
            <label for="showriverbasins" style="float: right;"></label>
              </span>
              
              
             </li>
            <li>
                <span class="width50">
                   <h3 class="nomargin" style="float:left;">Fire Regions</h3>
              <input type="checkbox" id="checkbox-1-40" class="regular-checkbox">
              <label for="checkbox-1-40" style="float: right;"></label>
                  </span>
                
              </li>
            <li style="margin-bottom:5px;">
              <span style="width:15%; float: left;">
                <h2 class="nomargin" style="line-height:20px">Layers</h2>
              </span>
              <span style="width:85%; float: left;">
              <select name="layers" id="layers" style="margin-left:0">
                <option value="" selected="selected">None</option>
                <option value="1" >AU Railway Tracks</option>
                <option value="2">AU Telephone Exchanges</option>
                <option value="3">Broadcast Transmitter</option>
                <option value="4">AU Oil and Gas Pipelines</option>
                <option value="5">AU Electricity Transmission Substations</option>
                <option value="6">AU Major Airport Terminals</option>
                <option value="7">AU Petrol Stations</option>
                <option value="8">AU Ports </option>
                <option value="9">AU Operating Mines </option>
                <option value="10">AU Major Power Stations</option>
                <?php if($_SERVER['PHP_AUTH_USER']=='ericsson' || $_SERVER['PHP_AUTH_USER']=='admin') {?>
                <option value="11">Ericsson Sites</option>
                <?php } ?>
              </select>
              </span> </li>
             <li style="display: none;" id="layer_button"><span><button id="show_sites" onclick="show_sites();" style="display:none;">Show Sites </button>
              <button id="hide_sites" onclick="hide_sites();" style="display:none;">Hide Sites </button></span></li>
           <!--/Third Box-->
          </ul>
            </li>
         <li> 
              <!--Fourth Box-->
              <ul>
             <li style="margin-bottom:5px;">
              <span style="width:40%; float: left;">
                <h2 class="nomargin" style="line-height:20px">Additional Layers</h2>
              </span>
              <span style="width:60%; float: left;">
              <select name="additional_layers" id="additional_layers" style="margin-left:0">
                <option value="" selected="selected">None</option>
                <option value="1" >Australian Postcode areas</option>
              </select>
              </span>
             </li>
            
           <!--/Third Box-->
          </ul>
            </li>
          <li> 
              <!--Fourth Box-->
              <ul>
              <li style="padding-left: 0px;">
               <span style="padding-left: 0px ! important;" class="width33">
              <h2 style="margin-left: 5px ! important;">(Coming Soon)</h2>
              </span>
              </li>
            <li> <span class="width40">
              <h2 class="nomargin" style="margin-top:4px;">Forecast model</h2>
              </span>
              <span class="width60">
              <!-- <select style="float: left; " name="drop" class="Selectmargin" id="forecast_model" onchange="javascript:generateChart(false);"> -->
              <select style="float: left; " name="drop" class="Selectmargin" id="forecast_model">
                <option value="">OFF</option>
                <!-- <option value="access_r">ACCESS R</option> -->
               <!--  <option value="GFS">GFS</option> -->
              </select>
              <h3 class="nomargin" style="margin-top:5px; float: left; margin-left: 10px;font-weight:normal;"> Opacity </h3>
              <select name="forecast_module_opacity" class="Selectmargin" id="forecast_module_opacity" style="float: left; ">
                  <option value='0'>0%</option>
                  <option value='.1'>10%</option>
                  <option value='.2'>20%</option>
                  <option value='.3'>30%</option>
                  <option value='.4'>40%</option>
                  <option value='.5'>50%</option>
                  <option value='.6'>60%</option>
                  <option value='.7'>70%</option>
                  <option value='.8'>80%</option>
                  <option value='.9'>90%</option>
                  <option value='1' selected>100%</option>
                </select>
              </span>
            </li>
            <li> <span class="width40">
              <h2 class="nomargin" style="margin-top:4px;">Forecast Type</h2>
              </span>
              <span class="width60">
              <!-- <select name="drop" class="Selectmargin" id="forecasts_types_select" onchange="javascript:generateChart(true);"> -->
              <select name="drop" class="Selectmargin" id="forecasts_types_select">
                <option value="">Select Forecast Type</option>
                <option value="li">Storm instability</option>
                <option value="cape">Storm Energy</option>
                <option value="mslp">Surface Pressure + rain</option>
                <option value="tscreen">Surface Temperature</option>
                <option value="s10m">Surface Winds</option>
                <option value="gustssfc">Surface Wind Gusts</option>
                <option value="rhscreen">Surface relative humidity</option>
                <option value="ts">Storm probability</option>
                <option value="cloud-total">Cloud cover - Total</option>
                <option value="cloud-high">Cloud Cover - High level</option>
                <option value="cloud-mid">Cloud Cover - mid level</option>
                <option value="cloud-low">Cloud Cover - low level</option>
              </select>
              </span>
            </li>
            <li> <span class="width40">
              <h2 class="nomargin" style="margin-top:4px;">Forecast Date/Time</h2> </span>
              <span class="width60">
        <!-- <input id="datetimepicker" style="width:150px;" readonly="readonly"> -->
        <select name="forecast_module_datetime" id="forecast_module_datetime"> <!-- //onchange="javascript:showcurrentimage(this.value);" -->
                  <option value=''>Select Date/Time</option>
                </select>
            
              </span>
            </li>
            <li> <span class="width40"> Model Controls (loop) </span>
              <span class="width60">
              <div class="Controls">
                <ul>
                    <li class="play forecast_storm_play"><a href="javascript:void(0);" id="play_storm" title="Play">play</a></li>
                    <li class="reverse forecast_storm_reverse" id="reverse_storm"><a href="#" id="prev_storm" title="Previous">reverse</a></li>
                    <li class="forward forecast_storm_forward" id="forward_storm"><a href="#" id="next_storm" title="Forward">forward</a></li>
                    <!-- <li class="play forecast_storm_play"><a href="javascript:void(0);" id="play_storm" title="Play" onclick="javascript:loopforecaststart(true);">play</a></li>
                    <li class="pause forecast_storm_pause"><a href="#" onclick="javascript:loopforecaststop();"> id="pause_storm" title="Pause">pause</a></li>
                    <li class="reverse forecast_storm_reverse" id="reverse_storm"><a href="#" id="prev_storm" title="Previous" onclick="javascript:showpreviousimage(true);">reverse</a></li>
                    <li class="forward forecast_storm_forward" id="forward_storm"><a href="#" id="next_storm" title="Forward" onclick="javascript:shownextimage(true);">forward</a></li> -->
                </ul>
              </div>
              <span>
              <select name="drop" style="margin-left: 10px;" id="forstorm_speed_control" class="forstorm_speed_control">
                <option value="3000">Faster</option>
                <option value="4000" selected="selected">Normal</option>
                <option value="5000">Slower</option>
              </select>
              </span>
               </span>
            </li>
            <!--/Third Box-->
          </ul>
            </li>
          
         
      </ul>
        </div>
        
    <div class="external">
          <h2>EXTERNAL WINDOW VIEWER</h2>
          <ul>
        <li class="theyellow" ><a href="JavaScript:newPopup('common/radar.php?radar=<?php echo $radar_code; ?>','512','600');">Radar</a></li>
        <li class="thegreen"  style="width:31%;"><a href="JavaScript:newPopup('common/bom.satellite.php','641','600');">Satellite</a></li>
        <li class="thered" ><a href="JavaScript:newPopup('common/alert.php','800','660');">alerts</a></li>
        <li class="theblue" ><a href="JavaScript:newPopup('common/wrapper.php?page=charts&chartsLocation=<?php echo $chart; ?>','1010','960');">charts</a></li>
      </ul>
        </div>
    <div style="height:35px;"></div>
  </div>
    </div>