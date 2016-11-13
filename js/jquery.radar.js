/*function RadarImageOverlay(map, radars, minimumSeconds, updateInterval, opacity) {
  this.map = map;
  this.setMap(map);
      this.radars = radars; // All available radars.
      this.minimumSeconds = minimumSeconds;
      this.updateInterval = updateInterval;
      this.percentOpacity = opacity;
      this.radarOverlays = []; // Currently shown.
      this.stopped = false;
      this.updateTimer = null;
      this.markers = null;
  }*/

function initEWNMapsRadarsMarkerLabelPrototype() {

	RadarImageOverlay.prototype = new google.maps.OverlayView();

	RadarImageOverlay.prototype.remove = function () {
		//      if (this.div) {
		//        this.setMap(null);
		//        this.div.parentNode.removeChild(this.div);
		//       this.div = null;
		//     }
		this.stopped = true;
		for (var i = 0; i < this.radars.length; i++) {
			if (this.radarOverlays[i] != null) {
				this.radarOverlays[i].hide();
			}
		}
		this.hideRadarMarkers();
	}

	RadarImageOverlay.prototype.draw = function (firstTime) {
		for (var i = 0; i < this.radars.length; i++) {
			if (this.radarOverlays[i] != null) {
				this.radarOverlays[i].draw();
			}
		}
	}


	RadarImageOverlay.prototype.getRadarImage = function (i, noShow) {
		if (this.radarOverlays[i]) {
			if (!noShow) {
				this.radarOverlays[i].show();
				this.radarOverlays[i].draw();
			}
			return this.radarOverlays[i];
		}

		var ri = new RadarImage(this, this.radars[i], i, this.opacity);
		this.radarOverlays[i] = ri;
		return ri;
	}

	RadarImageOverlay.prototype.removeRadarImage = function (i) {
		this.radarOverlays[i].hide();
		//this.radarOverlays[i] = null;
	}


	RadarImageOverlay.prototype.loadOverlays = function () {
		this.stopped = false;
		var list_html = "";
		var state_id = -1;
		for (var i = 0; i < this.radars.length; i++) {
			var ri = this.getRadarImage(i);
			if (state_id != ri.state_id) {
				if (state_id != -1) list_html += "</ul></div>";
				state_id = ri.state_id;
				list_html += "<div class='radar_state'><div class='radar_list_header'><input id='state_" + state_id + "'  type='checkbox' checked='true'/>" + states[state_id] + "</div>\n<ul class='radar_list'>\n";
			}
			list_html += "<li><input id='radar_" + i + "'  type='checkbox' checked='true'/>" + ri.code + " : " + ri.name + "<br><span id='radar_ts_" + i + "'>N/A</span></li>\n";
		}
		list_html += "</ul></div>\n";
		var list = document.getElementById("radar_list");
		if (list) {
			list.innerHTML = list_html;
			This = this;
			for (var i = 0; i < this.radars.length; i++) {
				$("#radar_" + i).bind("click", function (i) { return function () { This.radarChange(i) } }(i));
			}
		}

		for (var state_id = 1; state_id <= 8; state_id++) {
			$("#state_" + state_id).bind("click", function (i) { return function () { This.stateChange(i) } }(state_id));
		}
		this.updateOverlays();
	}



	RadarImageOverlay.prototype.stateChange = function (state_id) {
		var cb = document.getElementById("state_" + state_id);
		for (var i = 0; i < this.radars.length; i++) {
			var ri = this.getRadarImage(i, true);
			if (ri.state_id == state_id) {
				var cbr = document.getElementById("radar_" + i);
				if (cb.checked) {
					cbr.checked = true;
					ri.show();
				} else {
					cbr.checked = false;
					this.removeRadarImage(i)
				}
			}
		}
		if (cb.checked) this.resetUpdateTimer();
		this.draw();
	}


	RadarImageOverlay.prototype.updateAll = function (show) {
		//console.log(this.radars);
		// console.log(this.radars.length);

		for (var i = 0; i < this.radars.length; i++) {
			var ri = this.getRadarImage(i);
			// console.log(ri);
			//var cbr = document.getElementById("radar_"+i);
			//cbr.checked = show;
			if (show) {
				ri.show();
			} else {
				this.removeRadarImage(i)
			}
		}
		for (var state_id = 1; state_id <= 8; state_id++) {
			var cb = document.getElementById("state_" + state_id);
			if (cb) cb.checked = show;
		}
		if (show) this.resetUpdateTimer();
	}


	RadarImageOverlay.prototype.radarChange = function (i) {
		var cb = document.getElementById("radar_" + i);
		if (cb.checked) {
			this.getRadarImage(i)
			this.draw();
			this.resetUpdateTimer();
		} else {
			this.removeRadarImage(i)
		}
	}


	// Update all visible radars
	RadarImageOverlay.prototype.updateOverlays = function () {
		if (this.stopped) return;
		this.updateTimer = null;
		log("Updating overlays!");
		for (var i = 0; i < this.radars.length; i++) {
			if (this.radarOverlays[i]) {
				if (this.radarOverlays[i].displayed) log("Updating " + this.radarOverlays[i].code);
				this.radarOverlays[i].toUpdate = this.radarOverlays[i].displayed;
			}
		}
		this.draw();
		This = this;
		this.updateTimer = setTimeout(function () { This.updateOverlays() }, updateInterval);
	}

	RadarImageOverlay.prototype.resetUpdateTimer = function () {
		if (this.stopped || this.updateTimer == null) return;
		log("Reset Update Timer")
		clearTimeout(this.updateTimer);
		This = this;
		this.updateTimer = setTimeout(function () { This.updateOverlays() }, quickUpdateInterval);
	}


	RadarImageOverlay.prototype.showRadarMarkers = function () {
		this.createRadarMarkers();
		for (var i = 0; i < this.markers.length; i++) {
			this.markers[i].setMap(map);
		}
	}
	RadarImageOverlay.prototype.hideRadarMarkers = function () {
		if (this.markers == null) return; // Never created
		for (var i = 0; i < this.markers.length; i++) {
			this.markers[i].setMap(null);
		}
	}

	RadarImage.prototype.createElements = function () {
		if (this.div) return;
		var panes = this.rio.getPanes();
		if (!panes) return; // Map not ready yet.
		var div = document.createElement("div");
		div.style.border = borderStyle;
		div.style.position = "absolute";
		div.style.overflow = "hidden"; //visible";
		div.setAttribute('id', "img_container_" + this.index);
		var img = this.img = document.createElement("img");
		img.src = "http://clients.ewn.com.au/common/images/blank.png";
		div.appendChild(img);
		this.div = div;
		//   this.setOpacity(this.rio.percentOpacity);
		this.show();

	}

	RadarImage.prototype.hide = function () {
		this.div.parentNode.removeChild(this.div);
		this.displayed = false;
	}
	RadarImage.prototype.show = function () {
		// console.log(this.div);
		this.rio.getPanes().overlayLayer.appendChild(this.div);
		this.displayed = true;
	}
	RadarImage.prototype.updateImage = function () {
		if (!this.toUpdate || this.loading) return;

		// Load a new image perhaps...
		var loadOffset = 0;
		newURL = this.calculateURL(loadOffset);

		if (this.url == newURL) return;
		this.loadOffset = loadOffset;
		log("Trying " + this.code + " with offset " + loadOffset);
		this.newImg = null;
		this.loadImage();
		this.toUpdate = false;
	}
	RadarImage.prototype.loadImage = function () {
		var This = this;
		this.url = this.calculateURL(this.loadOffset);
		if (this.img.src.split("?")[0] == this.url) return;

		if (!this.newImg || this.newImg.src.split("?")[0] != this.url) {
			this.loading = true;
			this.newImg = document.createElement("img");
			this.newImg.onerror = function () { log("FAILED : " + This.code + ":" + This.loadOffset + " : " + This.url); This.loadSuccess = false; }; // Flag error for the image loading
			this.loadSuccess = true; // Assume OK for now
			this.newImg.src = this.url + "?" + (new Date().getTime());
			log("Trying : " + this.newImg.src);
		}

		if (this.newImg.complete || !this.loadSuccess) {
			if (this.loadSuccess && this.newImg.width > 0) {
				log("Got " + this.code + " with offset " + this.loadOffset + " : " + this.url);
				this.img.src = this.newImg.src;
				this.timestamp = this.loadingTimestamp;
				//document.getElementById("radar_ts_"+this.index).innerHTML = this.loadOffset + ": " + ft(this.timestamp);
				var e = document.getElementById("radar_ts_" + this.index)
				if (e) e.innerHTML = ft(this.timestamp);
			} else {
				if (this.loadOffset < maxLoadOffset) {
					log("Failed, at " + this.loadOffset + " offset for " + this.code + " : " + this.url + " : loadSuccess=" + this.loadSuccess + ", width = " + this.newImg.width + ", complete=" + this.newImg.complete);
					this.loadOffset += 1;
					this.loadImage(); // Go back a step.
				} else {
					log("Giving up on " + this.code + " with offset " + this.loadOffset + " : " + this.url);
				}
			}
			this.loading = false;
		} else {
			//log("Trying again  " + this.code + " with offset " + this.loadOffset  + " : " + this.url);
			setTimeout(function () { This.loadImage() }, 200);
		}
	}

	RadarImage.prototype.draw = function () {
		this.createElements(); // Ensure we have elements to show
		if (!this.div) return;
		this.updateImage();

		var imgWidth = 512;
		var imgHeight = 512;
		var topOffset = 16;
		var bottomOffset = 16;

		var pointSW = new google.maps.LatLng(this.lat1, this.lon1);
		var pointNE = new google.maps.LatLng(this.lat2, this.lon2);
		var bounds = new google.maps.LatLngBounds(pointSW, pointNE);

		// Calculate the DIV coordinates of two opposite corners of our bounds to get the size and position of our rectangle
		var c1 = this.rio.get('projection').fromLatLngToDivPixel(bounds.getSouthWest());
		var c2 = this.rio.get('projection').fromLatLngToDivPixel(bounds.getNorthEast());
		if (!c1 || !c2) return; // Still not ready, only in IE over slower connections it seems
		var boxHeight = Math.abs(c2.y - c1.y);
		var boxWidth = Math.abs(c2.x - c1.x);
		var scale = parseFloat(boxHeight) / parseFloat(imgHeight);
		var boxOffsetTop = parseInt(Math.round(topOffset * scale));
		var boxOffsetBottom = parseInt(Math.round(bottomOffset * scale));

		this.div.style.width = boxWidth + "px";
		this.div.style.left = Math.min(c2.x, c1.x) + "px";
		this.div.style.height = (boxHeight - boxOffsetTop - boxOffsetBottom - 1) + "px";
		this.div.style.top = (Math.min(c2.y, c1.y) + boxOffsetTop + 1) + "px";

		this.img.style.marginTop = (-boxOffsetTop - 1) + "px";
		this.img.style.width = boxWidth + "px";
		this.img.style.height = boxHeight + "px";
	}

	RadarImage.prototype.setOpacity = function (opacity) {
		if (opacity < 0) opacity = 0;
		if (opacity > 100) opacity = 100;
		var c = opacity / 100;
		if (typeof (this.div.style.filter) == 'string') this.div.style.filter = 'alpha(opacity:' + opacity + ')';
		if (typeof (this.div.style.KHTMLOpacity) == 'string') this.div.style.KHTMLOpacity = c;
		if (typeof (this.div.style.MozOpacity) == 'string') this.div.style.MozOpacity = c;
		if (typeof (this.div.style.opacity) == 'string') this.div.style.opacity = c;
	}

	RadarImage.prototype.calculateURL = function (offset) {
		if (!offset) offset = 0;
		var dt = new Date();
		dt.setTime(dt.getTime() - this.rio.minimumSeconds * 1000 - offset * this.interval * 60 * 1000); // Add in known minimum delay from timestamp to availability
		dt.setTime(dt.getTime() - (dt.getUTCMinutes() % this.interval) * 60 * 1000);
		this.loadingTimestamp = dt;
		ts = [dt.getUTCFullYear(), zp(dt.getUTCMonth() + 1), zp(dt.getUTCDate()), zp(dt.getUTCHours()), zp(dt.getUTCMinutes())].join("");
		return "http://wac.72dd.edgecastcdn.net/8072DD/radimg/radar/" + this.code + ".T." + ts + ".png"; // 200909061030
		//this.url = "http://www.bom.gov.au/radar/" + this.code + ".T.png?" + (new Date()).getTime();
	}

	RadarImageOverlay.prototype.remove = function () {
		//      if (this.div) {
		//        this.setMap(null);
		//        this.div.parentNode.removeChild(this.div);
		//       this.div = null;
		//     }
		this.stopped = true;
		for (var i = 0; i < this.radars.length; i++) {
			if (this.radarOverlays[i] != null) {
				this.radarOverlays[i].hide();
			}
		}
		this.hideRadarMarkers();
	}

	RadarImageOverlay.prototype.draw = function (firstTime) {
		//console.log(this.radars.length);
		for (var i = 0; i < this.radars.length; i++) {
			if (this.radarOverlays[i] != null) {
				this.radarOverlays[i].draw();
			}
		}
	}

	RadarImageOverlay.prototype.getRadarImage = function (i, noShow) {
		if (this.radarOverlays[i]) {
			if (!noShow) {
				this.radarOverlays[i].show();
				this.radarOverlays[i].draw();
			}
			return this.radarOverlays[i];
		}

		var ri = new RadarImage(this, this.radars[i], i, this.opacity);
		this.radarOverlays[i] = ri;
		return ri;
	}

	RadarImageOverlay.prototype.removeRadarImage = function (i) {
		this.radarOverlays[i].hide();
		//this.radarOverlays[i] = null;
	}

	RadarImageOverlay.prototype.loadOverlays = function () {
		this.stopped = false;
		var list_html = "";
		var state_id = -1;
		for (var i = 0; i < this.radars.length; i++) {
			var ri = this.getRadarImage(i);
			if (state_id != ri.state_id) {
				if (state_id != -1) list_html += "</ul></div>";
				state_id = ri.state_id;
				list_html += "<div class='radar_state'><div class='radar_list_header'><input id='state_" + state_id + "'  type='checkbox' checked='true'/>" + states[state_id] + "</div>\n<ul class='radar_list'>\n";
			}
			list_html += "<li><input id='radar_" + i + "'  type='checkbox' checked='true'/>" + ri.code + " : " + ri.name + "<br><span id='radar_ts_" + i + "'>N/A</span></li>\n";
		}
		list_html += "</ul></div>\n";
		var list = document.getElementById("radar_list");
		if (list) {
			list.innerHTML = list_html;
			This = this;
			for (var i = 0; i < this.radars.length; i++) {
				$("#radar_" + i).bind("click", function (i) { return function () { This.radarChange(i) } }(i));
			}
		}

		for (var state_id = 1; state_id <= 8; state_id++) {
			$("#state_" + state_id).bind("click", function (i) { return function () { This.stateChange(i) } }(state_id));
		}
		this.updateOverlays();
	}

	RadarImageOverlay.prototype.stateChange = function (state_id) {
		var cb = document.getElementById("state_" + state_id);
		for (var i = 0; i < this.radars.length; i++) {
			var ri = this.getRadarImage(i, true);
			if (ri.state_id == state_id) {
				var cbr = document.getElementById("radar_" + i);
				if (cb.checked) {
					cbr.checked = true;
					ri.show();
				} else {
					cbr.checked = false;
					this.removeRadarImage(i)
				}
			}
		}
		if (cb.checked) this.resetUpdateTimer();
		this.draw();
	}

	RadarImageOverlay.prototype.updateAll = function (show) {
		//console.log(this.radars);
		// console.log(this.radars.length);

		for (var i = 0; i < this.radars.length; i++) {
			var ri = this.getRadarImage(i);
			//console.log(ri);
			//var cbr = document.getElementById("radar_"+i);
			//cbr.checked = show;
			if (show) {
				ri.show();
			} else {
				this.removeRadarImage(i)
			}
		}
		for (var state_id = 1; state_id <= 8; state_id++) {
			var cb = document.getElementById("state_" + state_id);
			if (cb) cb.checked = show;
		}
		if (show) this.resetUpdateTimer();
	}


	RadarImageOverlay.prototype.radarChange = function (i) {
		var cb = document.getElementById("radar_" + i);
		if (cb.checked) {
			this.getRadarImage(i)
			this.draw();
			this.resetUpdateTimer();
		} else {
			this.removeRadarImage(i)
		}
	}

	RadarImageOverlay.prototype.show = function () {
		if (this.div_) {
			this.div_.style.visibility = "visible";
		}
	}
	RadarImageOverlay.prototype.onAdd = function () {/*
  var div = document.createElement('DIV');
  div.style.border = "0px";
  div.style.position = "absolute";
  var img = document.createElement("img");
  //img.src = this.image_;
   img.src = '';
  img.style.width = "100%";
  img.style.height = "100%";
  div.appendChild(img);
  this.div_ = div;
  var panes = this.getPanes();
  panes.overlayImage.appendChild(div);
*/}
	// Update all visible radars
	RadarImageOverlay.prototype.updateOverlays = function () {
		if (this.stopped) return;
		this.updateTimer = null;
		log("Updating overlays!");
		for (var i = 0; i < this.radars.length; i++) {
			if (this.radarOverlays[i]) {
				if (this.radarOverlays[i].displayed) log("Updating " + this.radarOverlays[i].code);
				this.radarOverlays[i].toUpdate = this.radarOverlays[i].displayed;
			}
		}
		this.draw();
		This = this;
		this.updateTimer = setTimeout(function () { This.updateOverlays() }, updateInterval);
	}

	RadarImageOverlay.prototype.resetUpdateTimer = function () {
		if (this.stopped || this.updateTimer == null) return;
		log("Reset Update Timer")
		clearTimeout(this.updateTimer);
		This = this;
		this.updateTimer = setTimeout(function () { This.updateOverlays() }, quickUpdateInterval);
	}


	RadarImageOverlay.prototype.showRadarMarkers = function () {
		this.createRadarMarkers();
		for (var i = 0; i < this.markers.length; i++) {
			this.markers[i].setMap(map);
		}
	}
	RadarImageOverlay.prototype.hideRadarMarkers = function () {
		if (this.markers == null) return; // Never created
		for (var i = 0; i < this.markers.length; i++) {
			this.markers[i].setMap(null);
		}
	}


	RadarImageOverlay.prototype.createRadarMarkers = function () {
		if (this.markers != null) return; // Already created
		this.markers = [];
		var imagePath = "http://clients.ewn.com.au/common/images/wifi_small.png";
		var f = function (code) {
			return function () {
				var url = "http://www.bom.gov.au/products/" + code + ".loop.shtml";
				radarCode = code.replace("IDR", "");
				// document.getElementById("radarImage").src = 'http://clients.ewn.com.au/common/radar.php?radar=' + radarCode;
				//updateViewportCookie(radarCode);
				radarNumber = radarCode;
				newPopup('common/radar.php?radar=' + radarNumber, '512', '600');

				//document.location = url;
				//var win = window.open(url,'_blank'); //,'width=300,height=200,menubar=yes,status=yes,location=yes,toolbar=yes,scrollbars=yes');
				//if (window.focus) {win.focus()}

			};
		};

		for (var i = 0; i < this.radars.length; i++) {
			var d = this.radars[i]; // ['IDR553','Wagga Wagga',146.063377,-36.321769,148.876623,-34.018231,10,2],
			var title = d[1] + " radar: click for loop";
			var pos = new google.maps.LatLng(d[3] + (d[5] - d[3]) / 2, d[2] + (d[4] - d[2]) / 2);
			var marker = new google.maps.Marker({ position: pos, map: map, title: title, icon: imagePath });
			google.maps.event.addListener(marker, 'click', f(d[0]));
			this.markers[this.markers.length] = marker;
		};
	}





}


function radarStatus() {


	// console.log(currentRadarStatus);
	//console.log(pageLoadedWithoutRadarOn);
	if (currentRadarStatus == 'on') {
		radarsOff();
		$.cookie(cookiePrefix + "RS", 'off', { expires: 365 });
		currentRadarStatus = 'off';
	}
	else {
		if (pageLoadedWithoutRadarOn) {
			show128km();
			$.cookie(cookiePrefix + "RS", 'on', { expires: 365 });
			currentRadarStatus = 'on';
		}
		else {
			radarsOn();
			$.cookie(cookiePrefix + "RS", 'on', { expires: 365 });
			currentRadarStatus = 'on';
		}
	}


}



function toggleRadarMarkers() {
	// This line simply toggles between true and false - very clever!
	/*showMarkers = !showMarkers;
	{ 
	  if (showMarkers)
	  {
		rio2.hideRadarMarkers();
		//$.cookie(cookiePrefix + "RM", 'on', { expires: 365});
	   }
	else
	{
		rio2.showRadarMarkers();
		//$.cookie(cookiePrefix + "RM", 'off', { expires: 365});
	}
	
	}*/

	if ($(".wx_radar_sites").attr("checked")) {
		rio2.showRadarMarkers();
	} else {
		rio2.hideRadarMarkers();
	}
}



function radarsOn() {
	var rio = (currentRange == 128) ? rio1 : rio2;
	// $.cookie(cookiePrefix + "RS", 'on', { expires: 365});
	rio.updateAll(true);
	return false;
}

function RadarImage(rio, d, i, opacity) {
	this.rio = rio;
	this.code = d[0];
	this.name = d[1];
	this.lon1 = d[2];
	this.lat1 = d[3];
	this.lon2 = d[4];
	this.lat2 = d[5];
	this.interval = d[6];
	this.state_id = d[7];
	this.index = i;
	this.opacity = opacity;
	this.url = null;
	this.div = null;
	this.img = null;
	this.toUpdate = true;
	this.timestamp = null;
	this.loadingTimestamp = null;
	this.displayed = true;
}


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
// http://code.google.com/apis/maps/documentation/overlays.html#Custom_Overlays
function RadarImageOverlay(map, radars, minimumSeconds, updateInterval, opacity) {
	//console.log(this);
	this.map = map;
	this.setMap(map);
	this.radars = radars; // All available radars.
	this.minimumSeconds = minimumSeconds;
	this.updateInterval = updateInterval;
	this.percentOpacity = opacity;
	this.radarOverlays = []; // Currently shown.
	this.stopped = false;
	this.updateTimer = null;
	this.markers = null;
}
