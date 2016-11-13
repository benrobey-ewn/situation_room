// On load checks to see what parts of nav should be expanded.
$(function () {
	$('.header-collapse').each(function () {
		var e = $(this);
		if (e.children('.header-checkbox').attr('checked') == true) {
			e.siblings('.content-collapse').show();
			e.find('span').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-up');
		}
		else if (e.children('.header-checkbox').attr('checked') == false) {
			e.removeClass('border-bottom-0');
		}
	});
});

// Called by click event of checkbox
$('.header-checkbox').click(function () {
	var e = $(this);
	if (!e.is(':checked')) {
		e.parent().siblings('.content-collapse').find('.regular-checkbox').each(
			e.parent().siblings('.content-collapse').find('.regular-checkbox').prop('checked', false)
		);
	}
});

// Called by on click event of header-collapse.
$('.header-collapse').click(function (event) {
	var e = $(this);
	if ((event.target.nodeName != "LABEL" && event.target.nodeName != "INPUT")) {
		if (e.find('span').hasClass('glyphicon-chevron-down')) {  // Collapse.
			e.siblings('.content-collapse').slideUp("fast");
			e.find('span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
			e.addClass('border-bottom-20').delay(200).queue(function () {
				e.removeClass('border-bottom-0');
				e.dequeue();
			});
		}
		else if (e.find('span').hasClass('glyphicon-chevron-up')) {   // Expand.
			e.siblings('.content-collapse').slideDown("fast");
			e.find('span').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
			e.removeClass('border-bottom-20').addClass('border-bottom-0');
			$('html, #Popcontainer').animate({
				scrollTop: $(e).offset().top
			},
				'slow');
		}
	}
});

// Expand  or collapse text.
$('.info-text').click(function () {
	var e = $(this);
	if (e.hasClass('info-text-sml')) {
		e.removeClass('info-text-sml').addClass('info-text-lrg');
	}
	else {
		e.removeClass('info-text-lrg').addClass('info-text-sml');
	}
});

// Called by on click event of .collapse-navigation-btn.
$('.collapse-navigation').click(function () {
	$('#nav-container').hide();
	$('#nav-small').slideToggle("fast");
	$('#nav-container').removeClass('col-md-3');
	$('#map-container').removeClass('col-md-9').addClass('col-md-12');
	$('#map-container').addClass('map-container-width-max');
	
	radarShowRadarControlsOnMap();
	
	google.maps.event.trigger(map, "resize");
	// resize elements
	screen_resize_elements($(window).width());
	mapContainerLoadSizeWidth = $('#map-container').width();
	mapContainerLoadSizeHeight = $('#map-container').height();
});

// Called by on click event of #nav-small.
$('#nav-small').click(function () {
	$('#nav-small').hide();
	$('#nav-container').slideToggle("fast");
	$('#nav-container').addClass('col-md-3');
	$('#map-container').removeClass('col-md-12').addClass('col-md-9');
	$('#map-container').removeClass('map-container-width-max');
	
	radarHideRadarControlsOnMap();
	
	
	google.maps.event.trigger(map, "resize");
	// resize elements
	screen_resize_elements($(window).width());
	mapContainerLoadSizeWidth = $('#map-container').width();
	mapContainerLoadSizeHeight = $('#map-container').height();
});

$('.legend-content').click(function (event) {
	if (!dragging) {
		var e = $(this);
		if (!e.find('.legend-content-body').is(':visible')) {
			legendMainShow(e.parent());
		}
		else {
			e.find('.legend-content-body').slideUp(400);
			e.find('.rotate').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
		}
	}
});

function legendMainShow(e) {
	var e = $(e);
		if (e.parent().hasClass('legend-container-content')) {
			var container = $('.legend-container');
			var legends = container.find(".legend-main .legend-content-body");
			legends.each(function () {
				if ($(this).parents().hasClass('legend-container-content')) {
					$(this).slideUp(400);
					$(this).parent().find('.rotate').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
				}
			});
		}
	e.find('.legend-content-body').slideDown(400);
	e.find('.rotate').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
	e.show();
	window.setTimeout(function () { legendMoveIntoMap(e); }, 500);

	if ($('.legend-container-content > div').children().size() > 0) {
		if ($('.legend-container-content').hasClass('no-legends-collapsed')) {
			$('.legend-container-content').removeClass('no-legends-collapsed');
			$('.legend-container-content').fadeIn();
		}
	}
}
function legendMoveIntoMap(e) {
	e = $(e);
	if (e.position().top + e.height() + 40 > $('#map-container').height()) {
		e.css("top", $('#map-container').height() - e.height() - 40);
	}
}

function legendMainHide(e) {
	var e = $(e);
	if (!$('.legend-container-content > div').children(':visible').size() > 0) {
		$('.legend-container-content').removeClass('no-legends-collapsed');
		$('.legend-container-content').fadeOut();
	}
	e.hide();
	e.find('.legend-content-body').slideUp(400);
	e.find('.rotate').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
}

function dragLegendBreakAway(event, ui) {
	var e = $(ui.helper);
	if (e.parent().hasClass('legend-container-content')) {
		e.appendTo('#map-container');
		if (e.hasClass('dropped-legend')) {
			e.removeClass('dropped-legend');
		}
		e.addClass('single-draggable-legend');
		e.addClass('first-drag');
	}
	if ($('.legend-container-content > div').children().size() <= 0 || $('.legend-container-content > div').children(':visible').size() == 0) {
		$('.legend-container-content').addClass('no-legends-collapsed');
		$('.legend-container-text').addClass('legend-container-text-display');
	}
}

function dragLegendDropZone(e) {
	var e = $(e);
	if (e.parent().hasClass('map-container')) {
		e.appendTo('#legend-container-content');
		e.addClass('dropped-legend').removeClass('single-draggable-legend');
		e.removeAttr('style');
		legendMainShow(e);
	}
	if ($('.legend-container-content > div').children().size() > 0 || !$('.legend-container-content > div').children(':visible').size() == 0) {
		$('.legend-container-content').removeClass('no-legends-collapsed');
		$('.legend-container-text').removeClass('legend-container-text-display');
	}
}

//Global for resizing map to have legends hold their place :3
var mapContainerLoadSizeWidth = $('#map-container').width();
var mapContainerLoadSizeHeight = $('#map-container').height();

function gMapsLegendPosition(e) {
	var e = $(e);

	//default bottom left.
	var top = true;
	var left = true;
	var mapContainerSizeHeight = $('#map-container').height();
	var mapContainerSizeWidth = $('#map-container').width();

	//top
	if (e.position().top >= mapContainerSizeHeight / 2) {
		top = false;
	}
	//left
	if (e.position().left >= mapContainerSizeWidth / 2) {
		left = false;
	}

	var cssSettings = {};
	if (top) {
		if (e.position().top + mapContainerSizeHeight - mapContainerLoadSizeHeight < 30) {
			cssSettings.top = 30;
		}
		else {
			cssSettings.top = (e.position().top + mapContainerSizeHeight - mapContainerLoadSizeHeight);
		}
		cssSettings.bottom = '';
	}
	else {
		if (mapContainerSizeHeight - e.position().top - e.outerHeight() - mapContainerSizeHeight + mapContainerLoadSizeHeight < 30) {
			cssSettings.bottom = 30;
		}
		else {
			cssSettings.bottom = (mapContainerSizeHeight - e.position().top - e.outerHeight() - mapContainerSizeHeight + mapContainerLoadSizeHeight);
		}
		cssSettings.top = '';
	}
	if (left) {
		if (e.position().left + mapContainerSizeWidth - mapContainerLoadSizeWidth < 20) {
			cssSettings.left = 20;
		}
		else {
			cssSettings.left = (e.position().left + mapContainerSizeWidth - mapContainerLoadSizeWidth);
		}
		cssSettings.right = '';
	}
	else {
		if (mapContainerSizeWidth - e.position().left - e.outerWidth() - mapContainerSizeWidth + mapContainerLoadSizeWidth < 20) {
			cssSettings.right = 20;
		}
		else {
			cssSettings.right = (mapContainerSizeWidth - e.position().left - e.outerWidth() - mapContainerSizeWidth + mapContainerLoadSizeWidth);
		}
		cssSettings.left = '';
	}
	e.css(cssSettings);
	mapContainerLoadSizeWidth = $('#map-container').width();
	mapContainerLoadSizeHeight = $('#map-container').height();
}

$('.legend-main').mouseenter(function () {
	var e = $(this);
	e.find('.anchor-class').addClass('drag-icon');
}).mouseleave(function () {
	var e = $(this);
	e.find('.anchor-class').removeClass('drag-icon');
});

$('.radar-info').click(function () {
	if ($('.radar-bar-image').is(':visible')) {
		// Possible solutaion for making it look nice (not finished) $('.radar-info').animate({ width:"=10px"}, 5000, $('.radar-bar-image').hide());
		$('.radar-bar-image').hide();
	}
	else {
		$('.radar-bar-image').show();
		// Possible solutaion for making it look nice (not finished)$('.radar-info').animate({ width: "=365px"}, 5000);
	}
});