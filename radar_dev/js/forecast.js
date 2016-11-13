 var places={
    restaurant:{
        icon: 'images/weather.png' ,
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

    var forecast_markers;
    function load_forecast_markers(type)
    {
        $('#loader').show();
        var counter = '0';
        $.each(places,function(c,category)
        {
            $.each(category.items,function(m,item)
            {
              var mystate = item[0];
                $.ajax
                ({
                    type: "POST",
                    url: "ajax/forecast_getimage.php",
                    data: { mystate: mystate },
                    success: function(def_image)
                    {
                         $('#loader').hide();

                        forecast_markers=new google.maps.Marker
                        ({
                            position:new google.maps.LatLng(item[1],item[2]),
                            title:item[0],
                            icon:def_image,
                            zIndex: 100 
                        });


                        // info window
                       google.maps.event.addListener(forecast_markers,'click',function()
                       {
                            infowindow.close();
                            load_content(map,this,item[0]);
                        });

                    forecastmapMarkersArray.push(forecast_markers);
                    forecast_markers.setMap(map);
                    }
                });
                counter = ++counter;
            });
        });
    }


    function hide_forecast_markers()
    {
      if (forecastmapMarkersArray) {
        for (var i=0; i < forecastmapMarkersArray.length; i++) {
            forecastmapMarkersArray[i].setMap(null);
        }
            forecastmapMarkersArray.length = 0;
      }
    
    }