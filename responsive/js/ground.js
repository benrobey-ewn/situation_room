var districts = null;
var riverbasins = null;

var t1 = createTask(1);  //////////// start of loader
var t2 = createTask(2);
var t3 = createTask(3);  //////////// start of loader
var t4 = createTask(4);

function executeMapping() {
    //alert('4');
    var tasks = Array.prototype.concat.apply([], arguments);
    var task = tasks.shift();
    task(function() {
        if(tasks.length > 0)
            executeMapping.apply(this, tasks);
    });
}


function createTask(num) {
    return function(callback) {
        setTimeout(function() {
            console.log('Calling of ::::::::'+num);
            if(num==1)
            {
                districts.setMap(map);
            }
            if(num==2)
            {
                $('#loader').hide();
            }
            if(num==3)
            {
               riverbasins.setMap(map);
            }
            if(num==4)
            {
                $('#loader').hide();
            }
            if(typeof callback == 'function') callback();
        }, 1000);
    }
} 
 
function toggleShowDistricts()
{
    $('#loader').show();
    if (districts == null)
    {
        districts = new google.maps.GroundOverlay("images/forecast/forecast_districts.png", new google.maps.LatLngBounds(new google.maps.LatLng(-60.00, 110.00), new google.maps.LatLng(0.00, 170.00)));

        google.maps.event.addListener(districts, 'click', function (mouseevent)
        {
            google.maps.event.trigger(map, 'click', mouseevent);
        });
    }

    var showDistrictsCheckbox = document.getElementById("showdistricts");

    if (showDistrictsCheckbox)
    {
        if (showDistrictsCheckbox.checked)
        {
            setting[5].weather_region='on';
            executeMapping(t1,t2);
            //districts.setMap(map);
           // setTimeout(function(){$('#loader').hide();}, 400);

        }
        else
        {
            setting[5].weather_region='off';
            districts.setMap(null);
            $('#loader').hide();
        }
    }
    update_setting();

}

function toggleShowRiverBasins()
{
    $('#loader').show();

    if (riverbasins == null) {
        riverbasins = new google.maps.GroundOverlay("images/forecast/river_basins.png", new google.maps.LatLngBounds(new google.maps.LatLng(-43.80, 112.80), new google.maps.LatLng(-10.10, 158.05)));

        google.maps.event.addListener(riverbasins, 'click', function (mouseevent) {
            google.maps.event.trigger(map, 'click', mouseevent);
        });
    }

    var showDistrictsCheckbox = document.getElementById("showriverbasins");

    if (showDistrictsCheckbox) {
        if (showDistrictsCheckbox.checked) {
            setting[5].river_catchments='on';
           // riverbasins.setMap(map);
            executeMapping(t3,t4);
        }
        else {
            setting[5].river_catchments='off';
            riverbasins.setMap(null);
            $('#loader').hide();
        }
    }
    update_setting();

}