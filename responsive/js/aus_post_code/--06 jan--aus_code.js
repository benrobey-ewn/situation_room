                useAjax = false;
	var drawnOnce = false;
	var active, basins, basinPaths, googleMapProjection, overlayProjection, curZ, layer, svg, post_overlay, path, ignoreClick = false;
        var map;
	var width = 960, height = 650;
                function loadBoundaries(z) {
		if (z == curZ)
			return;
		curZ = z;

		var bTypeKey = 'PC';
		
		useAjax = false;
		var fn = "./aus_code_json/b-" + bTypeKey + "-geo-topo-" + z + ".json";
		console.log(fn);
		d3.json(fn, function (error, topology) {

                        if (post_overlay) {
				post_overlay.setMap(null);
			}
			post_overlay = new google.maps.OverlayView();

			// Add the container when the overlay is added to the map.
			post_overlay.onAdd = function () {
				console.log("overlay onAdd");
				//if (!layer)
				layer = d3.select(this.getPanes().overlayMouseTarget).append("div").attr("class", "SvgOverlay basins");

				if (svg)
					$('svg').remove();
				svg = layer.append("svg")
				  .attr("width", width)
				  .attr("height", height);
				
				basins = svg.append("g").attr("class", "basins");

				post_overlay.draw = function () {


					console.log("overlay draw");
					var markerOverlay = this;
					overlayProjection = this.getProjection();
					// Turn the overlay projection into a d3 projection
					googleMapProjection = function (coordinates) {
						var googleCoordinates = new google.maps.LatLng(coordinates[1], coordinates[0]);
						var pixelCoordinates = overlayProjection.fromLatLngToDivPixel(googleCoordinates);
						return [pixelCoordinates.x + 4000, pixelCoordinates.y + 4000];
					}

					path = d3.geo.path().projection(googleMapProjection);

					basinPaths = basins.selectAll("path")
							.data(topojson.feature(topology, topology.objects.boundary).features)
							.attr("d", path)
							.enter().append("path")
							.attr("d", path)
							.on("click", basinClick)
							.append("svg:title")
							.text(function (d) {
								return '#' + d.id + ' ' + d.properties.btype; 
							}); 

				};
			};
			post_overlay.onRemove = function () {
				console.log("overlay onRemove");
			};

			// Bind our overlay to the map
			post_overlay.setMap(map);

		});
	}
	function basinClick(d) {
		/*var u = '/admin/sadmin/boundary.aspx?bk=' + d.id;
		window.open(u,'boundary');*/
		  $('#zipcode').html('Post code '+d.properties.btype);
                  $('#custom_dialog').show();
	}
	function loadBoundariesByKey() {

		getBoundary(null, "VR", null, null, map);
		
	}
        
        function show_post_code() {
                width = $(map.getDiv()).width();
		height = $(map.getDiv()).height();

			
                       loadBoundaries(parseInt(zoom));

                      google.maps.event.addListener(map, 'zoom_changed', function () {
                                 var sel_layer = $('#additional_layers').val();
                                if(sel_layer==1) {
                                                var mz = map.getZoom();
                                                var z = 3;
                
                                                if (mz > 5)
                                                        z = 5;
                                                if (mz > 6)
                                                        z = 6;
                                                if (mz > 7)
                                                        z = 7;
                                                if (mz > 9)
                                                        z = 9;
                                                if (mz > 10)
                                                        z = 10;
                                                if (mz > 11)
                                                        z = 0;
                                                if (mz > 12)
                                                        z = -1;
                
                                                if (!useAjax) {
                                                        loadBoundaries(z);
                                                }
                                }
			});    
        }
        
        function remove_post_code() {
                //console.log(post_overlay);
                //post_overlay.setMap(null);
                if(typeof svg!=='undefined') {
                    svg.selectAll("path").remove();
                    
                }
                
                if(post_overlay) {
                    post_overlay.setMap(null);
                    post_overlay=false;
                }
               
                
        }        
        