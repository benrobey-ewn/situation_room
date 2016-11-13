	var satmap;
	var satmap_next;
	var satinit = 0;
	var satimage;
	var sat_files;
	var satloopnumber = 0;
	var sat_type;
	var sat_looping = 0;
	var sat_interval_handle;
	var sat_update_handle;
	var satloopnumber_next = -1;
	var satlooptdatetime = "";
	var sat_slider_left = 100;

	$( document ).ready(function() {
		$("#sat_hours").empty();
		$("#sat_hours").append('<option value="1">1 hour</option>');
		$("#sat_hours").append('<option value="2">2 hours</option>');
		$("#sat_hours").append('<option value="3">3 hours</option>');
		
		$("#sat-enable" ).change(function() {
			if ($("#sat-enable").prop("checked")===true) {
				show_satellite($("#sat_type_selector").val());

				sat_update_handle = setInterval(function() {
					reload_sat_image();
				}, 120000);
				settings.satellite.status = true;
			} else {
				settings.satellite.status = false;
				if (stripos($("#play_sat").attr('class'), 'play', 0) === false) {
					play_sat_loop("off");
				}
				
				show_satellite($("#sat_type_selector").val());
				clearInterval(sat_update_handle);
				$("#sat_date").text("Image Date:");
			}
			settingsUpdate();
		});
		
		$("#sat_module_opacity").change(function(event) {
			if ($("#sat-enable").prop("checked")===true) {
				reload_sat_image();
			}
		});
		
		$("#sat_hours").change(function() {
			if ($("#sat-enable").prop("checked")===true) {
				reload_sat_image();
			}
		});

		$("#sat-loop-onoff").change(function() {
			if ($("#sat-enable").prop("checked")===true && $("#sat-loop-onoff").prop("checked")===true) {
				if (stripos($("#play_sat").attr('class'), 'play', 0) === false) {
					play_sat_loop("off");

				}
				sat_slider_left = 100;
				satloopnumber = 0;
				sat_looping = 0;
				satloopnumber_next =  -1;			
				reload_sat_image();
			}
		});
		
		$("#sat_type_selector").change(function() {
			if ($("#sat-enable").prop("checked")===true) {
				satloopnumber = 0;
				sat_looping = 0;
				satloopnumber_next =  -1;
				reload_sat_image();
			}
		});
		
		$("#play_sat").click(function() {
			play_sat_loop("on");
		});
		
		$("#sat_speed_control").click(function() {
			if (stripos($("#play_sat").attr('class'), 'play', 0) === false) {
				play_sat_loop("off");
				play_sat_loop("on");
			}
		});

		$("#prev_sat").click(function() {
			if (stripos($("#play_sat").attr('class'), 'play', 0) === false) {
				play_sat_loop("off");
			}
			change_sat_time('prev');
		});

		$("#next_sat").click(function() {
			if (stripos($("#play_sat").attr('class'), 'play', 0) === false) {
				play_sat_loop("off");
			}			
			change_sat_time('next');
		});
	});
	
	function reload_sat_image() {
		map.overlayMapTypes.clear();
		satinit = 0;
		show_satellite($("#sat_type_selector").val());
	}

	function play_sat_loop(mode) {
		if ($("#sat-enable").prop("checked")===true || mode == "off") {
			if (stripos($("#play_sat").attr('class'), 'pause', 0) === false) {
				if ($("#sat-loop-onoff").prop("checked")===true) {
					$("#sat-loop-onoff").attr('checked', false);
				}
				
				$('#play_sat').removeClass("play").addClass('pause');
				sat_interval_handle = setInterval(function() {
					do_sat_loop('next');
				}, parseFloat($("#sat_speed_control").val()));
			} else {
				$('#play_sat').removeClass("pause").addClass('play');
				clearInterval(sat_interval_handle);
			}
		}
	}

	function do_sat_loop(when) {
		change_sat_time(when);
	}
	
	function change_sat_time(when) {
		if (when == "next") {
			satloopnumber = satloopnumber - 1;
			if (satloopnumber < 0) satloopnumber = sat_files.length-1;
			sat_slider_left = 100-(satloopnumber/(sat_files.length-1) * 100);
			
		} else {
			satloopnumber = satloopnumber + 1;
			if (satloopnumber >= sat_files.length) satloopnumber = 0;
			sat_slider_left = satloopnumber/(sat_files.length-1) * 100;
		}
		
		satloopnumber_next = satloopnumber;
		  
		if (when == 'next') {
			if (satloopnumber_next > 0) {
				satloopnumber_next = satloopnumber_next - 1;
			} else {
				satloopnumber_next = sat_files.length - 1;
			}
		} else {
			if (satloopnumber_next < 1 ) {
				if (satloopnumber_next == 0) { 
					satloopnumber_next = sat_files.length - 1;
				} else {
					satloopnumber_next = satloopnumber_next - 1;
				}
			  
			} else {
			  satloopnumber_next = satloopnumber_next - 1;
			}
		}
		
		showsatimage(when);
	}
	
	function show_satellite(overlaytype) {
		sat_type = overlaytype;
		if (satinit == 0) {
			$.ajax({
				url: "http://54.153.195.116/storage/scripts/satellite/show_sat_files.php?type=" + sat_type + "&t=" + $("#sat_hours").val(),
				dataType: 'jsonp',
				async: false,
				jsonpCallback: 'showsatimage("next")',
				jsonp: 'callback',
			});
		} else {
			map.overlayMapTypes.clear();
			satinit = 0;
			satloopnumber = 0;
			sat_looping = 0;
			satloopnumber_next = -1;
		}
	}

	function parse_satdate(thedate) {
		// 201510040620
		
		var retdate = thedate.substr(6, 2) + "/" + thedate.substr(4, 2) + "/" + thedate.substr(0, 4) + " " + thedate.substr(8, 2) + ":" + thedate.substr(10, 2) + "z";
		return retdate
	}
	
	function showsatimage(when) {
		if (satloopnumber_next == -1) {
			satloopnumber_next = sat_files.length -1;
		}
		
		satmap = new google.maps.ImageMapType({
			getTileUrl: function(coord, zoom) {
				var normalizedCoord = getNormalizedCoord(coord, zoom);
				if (!normalizedCoord) {
					return null;
				}
				var bound = Math.pow(2, zoom);
				return '//54.153.195.116/storage/sat_data_output/' + sat_files[satloopnumber] + '/' +
				'/' + zoom + '/' + normalizedCoord.x + '/' +
				(bound - normalizedCoord.y - 1) + '.png';
			},
			tileSize: new google.maps.Size(256, 256),
			maxZoom: 8,
			minZoom: 1,
			radius: 1738000,
			name: 'sat',
			opacity: 0.00
		});
		
		satmap_next = new google.maps.ImageMapType({
			getTileUrl: function(coord, zoom) {
				var normalizedCoord = getNormalizedCoord(coord, zoom);
				if (!normalizedCoord) {
					return null;
				}
				var bound = Math.pow(2, zoom);
				return '//54.153.195.116/storage/sat_data_output/' + sat_files[satloopnumber_next] + '/' +
				'/' + zoom + '/' + normalizedCoord.x + '/' +
				(bound - normalizedCoord.y - 1) + '.png';
			},
			tileSize: new google.maps.Size(256, 256),
			maxZoom: 8,
			minZoom: 1,
			radius: 1738000,
			name: 'sat',
			opacity: 0.00
		});
		
		var tmpsatlooptdatetime = sat_files[satloopnumber].split(".");
		satlooptdatetime = tmpsatlooptdatetime[tmpsatlooptdatetime.length-2];
		
		$("#sat_date").text("Image Date: " + parse_satdate(satlooptdatetime));
		$("#sat-slider").css("left", sat_slider_left + "%");
		
		if (satinit == 0) {
			map.overlayMapTypes.insertAt(0, satmap);
			map.overlayMapTypes.insertAt(1, satmap_next);
			map.overlayMapTypes.b[0].setOpacity(parseFloat($("#sat_module_opacity").val()));
			satinit = 1;
		} else {
			if (when == "prev") {
				map.overlayMapTypes.clear();
				map.overlayMapTypes.insertAt(0, satmap);
				map.overlayMapTypes.b[0].setOpacity(parseFloat($("#sat_module_opacity").val()));
				map.overlayMapTypes.insertAt(1, satmap_next);
				sat_looping = 0;
			} else {
				if (sat_looping == 0) {
					map.overlayMapTypes.b[0].setOpacity(0.00);
					map.overlayMapTypes.b[1].setOpacity(parseFloat($("#sat_module_opacity").val()));
					map.overlayMapTypes.removeAt(0);
					map.overlayMapTypes.insertAt(0, satmap_next);
					sat_looping = 1;
				} else {
					map.overlayMapTypes.b[1].setOpacity(0.00);
					map.overlayMapTypes.b[0].setOpacity(parseFloat($("#sat_module_opacity").val()));
					map.overlayMapTypes.removeAt(1);
					map.overlayMapTypes.insertAt(1, satmap_next);
					sat_looping = 0;
				}
			}
		}
	}
	
	// Normalizes the coords that tiles repeat across the x axis (horizontally)
	// like the standard Google map tiles.
	function getNormalizedCoord(coord, zoom) {
	  var y = coord.y;
	  var x = coord.x;

	  // tile range in one direction range is dependent on zoom level
	  // 0 = 1 tile, 1 = 2 tiles, 2 = 4 tiles, 3 = 8 tiles, etc
	  var tileRange = 1 << zoom;

	  // don't repeat across y-axis (vertically)
	  if (y < 0 || y >= tileRange) {
		return null;
	  }

	  // repeat across x-axis
	  if (x < 0 || x >= tileRange) {
		x = (x % tileRange + tileRange) % tileRange;
	  }

	  return {x: x, y: y};
	}