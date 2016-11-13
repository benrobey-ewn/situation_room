$(document).ready(function() {

	capital_cities = {};
	<?php
	foreach ($capital_cities as $city => $value) { ?>
		internal = new Array();
		<?php foreach ($value as $singles){ ?>
			internal.push('<?php echo $singles?>');
		<?php } ?>
		capital_cities['<?php echo $city; ?>'] = internal;
	<?php }
	?>

	capital_states = {};
	<?php
	foreach ($capital_states as $state => $value) { ?>
		internal = new Array();
		<?php foreach ($value as $singles){ ?>
			internal.push('<?php echo $singles?>');
		<?php } ?>
		capital_states['<?php echo $state; ?>'] = internal;
	<?php }
	?>

	$(".city_state_toggler").on("change",function() {
		showselect = $(this).val();
		hideselect = $(this).data("value");
		$("."+showselect).show();
		$("."+hideselect).hide();
		$("."+hideselect).val("none").trigger('change');
	})

	$("#capital_cities").on("change",function() {
		city_name = $(this).val();
		if (city_name!="none") {
			myCenter=new google.maps.LatLng(capital_cities[city_name][0],capital_cities[city_name][1]);
				zoom=parseInt(capital_cities[city_name][2]);
		} 
		else {
			myCenter=new google.maps.LatLng(-24.994167,134.866944);
			zoom=parseInt(5);
		}
		map.setCenter(myCenter);
		map.setZoom(zoom);
	});

	$("#capital_states").on("change",function(){
		state_name = $(this).val();
		if (state_name!="none") {
			myCenter = new google.maps.LatLng(capital_states[state_name][0],capital_states[state_name][1]);
			zoom = parseInt(capital_states[state_name][2]);
		} 
		else {
			myCenter = new google.maps.LatLng(-24.994167,134.866944);
			zoom = parseInt(5);
		}
		map.setCenter(myCenter);
		map.setZoom(zoom);
	});

	google.maps.event.addDomListener(window, 'resize', function() {
		infowindow.open(map);
	});
});