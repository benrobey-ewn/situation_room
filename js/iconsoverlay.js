IconsOverlay.prototype = new google.maps.OverlayView();

function IconsOverlay(map) {
    this._map = map;
    this._icons = [];
    this._container = document.createElement("div");
    this._container.id = "iconslayer";
    this.setMap(map);
    this.addIcon = function (location, properties) {
        properties.location = location;
        this._icons.push(properties);
    };
}


IconsOverlay.prototype.createCircleIcon = function (properties) {
    var pos = this.getProjection().fromLatLngToDivPixel(properties.location);
    var circleIcon = document.createElement('canvas');
    circleIcon.id = 'circleicon_' + properties.id;
    circleIcon.width = circleIcon.height = properties.scale * 2 + properties.strokeWeight;
    circleIcon.style.width = circleIcon.width + 'px';
    circleIcon.style.height = circleIcon.height + 'px';
    circleIcon.style.left = (pos.x - properties.scale) + 'px';
    circleIcon.style.top = (pos.y - properties.scale) + 'px';
    circleIcon.style.position = "absolute";

    var centerX = circleIcon.width / 2;
    var centerY = circleIcon.height / 2;
    var ctx = circleIcon.getContext('2d');
    ctx.fillStyle = properties.fillColor;
    ctx.strokeStyle = properties.strokeColor;
    ctx.lineWidth = properties.strokeWeight;
    ctx.beginPath();
    ctx.arc(centerX, centerY, properties.scale, 0, Math.PI * 2, true);
    ctx.stroke();
    ctx.fill();
    return circleIcon;
};


IconsOverlay.prototype.createPathIcon = function (properties) {
    var pos = this.getProjection().fromLatLngToDivPixel(properties.location);

    var pathIcon = document.createElement('canvas');
    pathIcon.id = 'pathicon_' + properties.id;
    pathIcon.style.left = pos.x + 'px';
    pathIcon.style.top = pos.y + 'px';
    pathIcon.style.position = "absolute";
    var ctx = pathIcon.getContext('2d');
    ctx.width *= properties.scale;
    ctx.height *= properties.scale;
    ctx.scale(properties.scale, properties.scale);

    ctx.fillStyle = properties.fillColor;
    ctx.strokeStyle = properties.strokeColor;
    ctx.lineWidth = properties.strokeWeight;

    var p = new Path2D(properties.path);
    ctx.stroke(p);
    ctx.fill(p);

    return pathIcon;
};

IconsOverlay.prototype.createOpenArrowIcon = function (properties) {
    var pos = this.getProjection().fromLatLngToDivPixel(properties.location);

    var arrowIcon = document.createElement('canvas');
    arrowIcon.id = 'arrowicon_' + properties.id;
    arrowIcon.width = arrowIcon.height = properties.scale * 5;
    arrowIcon.style.left = pos.x + 'px';
    arrowIcon.style.top = pos.y + 'px';
    arrowIcon.style.position = "absolute";
    var ctx = arrowIcon.getContext('2d');
    ctx.fillStyle = properties.fillColor;
    ctx.strokeStyle = properties.strokeColor;
    ctx.lineWidth = properties.strokeWeight;

    var side = properties.scale * 5;
    var h = side * (Math.sqrt(3) / 2);

    ctx.save();
    ctx.translate(arrowIcon.width / 2, arrowIcon.height / 2);

    ctx.beginPath(); 
    ctx.moveTo(0, h / 2);
    ctx.lineTo(-side / 2, -h / 2);
    ctx.lineTo(side / 2, -h / 2);
    ctx.lineTo(0, h / 2);
    ctx.stroke();
    ctx.fill();
    ctx.closePath();
    ctx.save();

    return arrowIcon;
}


IconsOverlay.prototype.onAdd = function () {
    var panes = this.getPanes();
    panes.overlayLayer.appendChild(this._container);
};



IconsOverlay.prototype.draw = function () {
    var container = this._container;
    var self = this;
    this._icons.forEach(function (iconProperties, idx) {
        switch (iconProperties.path) {
            case google.maps.SymbolPath.CIRCLE:
                var icon = self.createCircleIcon(iconProperties);
                container.appendChild(icon);
                break;
            case google.maps.SymbolPath.BACKWARD_OPEN_ARROW:
                var icon = self.createOpenArrowIcon(iconProperties);
                container.appendChild(icon);
                break;
            default:
                var icon = self.createPathIcon(iconProperties);
                container.appendChild(icon);
                break;
        }

    });
};

IconsOverlay.prototype.onRemove = function () {
    this._container.parentNode.removeChild(this._container);
    this._container = null;
};