var nbnArr=[];
var currentPolyObj = [];
var arr=[];
function loadPolygon(page,map,nbn_user_type){
                      $("#loading").show();
                      $.ajax({
                          url: "ajax/get_nbn_layer_data1.php?page="+page,
                          dataType: "json",
                          //async: false,
                        }).done(function(response) {
                          //console.log(response);
                          for (var i = 0; i < response.length; i++) {
                            var nbn_name=response[i].properties.name;
                            var nbn_type=response[i].properties.btype;
                            var nbn_main_id=response[i].properties.nbn_main_id;
                            var nbn_color=response[i].properties.fillColor;
                            //console.log(nbn_type+'-'+nbn_name+'-'+nbn_main_id);

                            if(nbn_color=='Black'){
                                 $("#Black_zones").append('<li id="'+nbn_type+'_'+nbn_name+'">'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
                                 $("#black_loading").hide();
                              }else if(nbn_color=='Red'){
                                 $("#Red_zones").append('<li id="'+nbn_type+'_'+nbn_name+'">'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
                                 $("#Red_loading").hide();
                              }else if(nbn_color=='#ffcc00'){
                                
                                 $("#Amber_zones").append('<li id="'+nbn_type+'_'+nbn_name+'">'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
                                 $("#amber_loading").hide();
                              }else{
                                $("#Green_zones").append('<li id="'+nbn_type+'_'+nbn_name+'">'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
                                $("#green_loading").hide();
                                $("#black_loading").hide();
                                $("#red_loading").hide();
                                $("#amber_loading").hide();

                              }

                          };
                          
                          
                          data = {"type":"FeatureCollection",
                          "features":response};


                          arr.push(data);
                        
              // adding data to map            
              map.data.addGeoJson(data);
                
                //adding style to map
                map.data.setStyle(function(feature) {
                        //currentPolyObj = feature;
                        var color = feature.getProperty('fillColor');
                        if (feature.getProperty('isColorful')) {
                          color = feature.getProperty('color');
                        }
                        return ({
                          fillColor: color,
                          strokeColor: color,
                          strokeWeight: 2
                        });
                      
                      });
                if(page==9){
                  $("#loading_nbn").hide();
                  $("#nbn_list_option").show();
                  $("#data_uploaded").val('success');
                }
                 
                
                if(nbn_user_type!='readonly'){
                  map.data.addListener('click', function(event) {
                     
                     

                    ids = event.feature.getProperty('nbn_main_id');
                    //alert(ids);
                    event.feature.setProperty('isColorful', !event.feature.getProperty('isColorful'));
                     if(currentPolyObj[ids]== undefined){
                       currentPolyObj[ids] = [];
                       currentPolyObj[ids].push(event);
                     } else {
                        currentPolyObj.remove(ids);
                     }

                      if(nbnArr.indexOf(event.feature.getProperty('nbn_main_id')) == -1 && event.feature.getProperty('isColorful')==true){
                            nbnArr.push(event.feature.getProperty('nbn_main_id'));
                        }else{
                           
                           nbnArr.remove(event.feature.getProperty('nbn_main_id'));
                        }
                        //console.log(nbnArr);
                        if(nbnArr.length>0){
                            $("#open_alert_form").show();
                        }else{
                            $("#open_alert_form").hide();
                        }
                        
                  });

                }
                
                
                
            });
}
 //Send Alert
function sendAlert(currentPolyObj,color,formData){
        
        //console.log(currentPolyObj.length);
        for(var key in currentPolyObj){
          if(key!== "remove"){
          //console.log("key = " + key);
          //console.log(currentPolyObj[key]);
          //console.log(currentPolyObj[key][0]);
          var nbn_main_id=currentPolyObj[key][0].feature.getProperty('nbn_main_id');
          var fillColor=currentPolyObj[key][0].feature.getProperty('fillColor');

          var nbn_name=currentPolyObj[key][0].feature.getProperty('name');
          var nbn_type=currentPolyObj[key][0].feature.getProperty('btype');
          var nbn_main_id=currentPolyObj[key][0].feature.getProperty('nbn_main_id');
          var nbn_color=currentPolyObj[key][0].feature.getProperty('fillColor');
          //console.log(fillColor);
              if(nbn_color=='#ffcc00'){
                  divnbn_color='Amber';
              }else{
                divnbn_color=nbn_color;
              }
              if(color=='#ffcc00'){
                divcolor='Amber'
              }else{
                divcolor=color;
              }
              //console.log('#'+color+'_zone');
             // console.log('#'+nbn_type+'_'+nbn_name);
              $('#'+divcolor+'_zones').append('<li id="'+nbn_type+'_'+nbn_name+'">'+nbn_type+'-'+nbn_name+'-'+nbn_main_id+'</li>');
              
              console.log('#'+divnbn_color+'_zones #'+nbn_type+'_'+nbn_name);
              $('#'+divnbn_color+'_zones #'+nbn_type+'_'+nbn_name).remove();
              
          currentPolyObj[key][0].feature.setProperty('color',color);
            $.ajax({
                          url: "ajax/generate_alert.php?id="+nbn_main_id,
                          dataType: "json",
                          //async: false,
                          data:formData
                        }).done(function(response) {

                        //console.log(response);
                  });



          }
        }


}
//Send Alert
function removePolygon(){
   map.data.forEach(function(feature) {
       //filter...
        map.data.remove(feature);
});

}
function reDrawPolygon(){
    
  console.log(arr);
}
//Confirmation Alert on Send
function confirm_alert()
{
    var status= confirm("Are you sure wish to send this alert!");
    if (status== true){
       var alert_type=$("#alert_type").val();
      var formData=$("#save_nbn_alert_form").serialize();
      //console.log(formData);
      sendAlert(currentPolyObj,alert_type,formData);
      $('#save_nbn_alert_form')[0].reset();
      $('#popup_nbn_polygon').modal('toggle');
    }else{
        return false;
    }
}
//Confirmation Alert on Send

function show_nbn_layers(){
        $('#navigation-nbn').show();
        $('#show_nbn_layers').hide();
        $('#hide_nbn_layers').show();

}
function hide_nbn_layers(){
       $('#navigation-nbn').hide();
        $('#show_nbn_layers').show();
        $('#hide_nbn_layers').hide();
}

Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};
$(document).ready(function(){
   $( "#open_alert_form" ).click(function() {
    
        $("#popup_nbn_polygon").modal("show");
});
   $("#title_polygon").on("keyup",function()
  {
    //alert($("#nbn_description_polygon").val().length);
    if($(this).val().length !=0 || $("#nbn_description_polygon").val().length !=0)
    {
      $("#save_nbn_alert_details").attr("disabled",false);
    }
    else
    {
      $("#save_nbn_alert_details").attr("disabled",true);
    }
  });


  $("#nbn_description_polygon").on("keyup",function()
  {
    if($("#title_polygon").val().length !=0 || $(this).val().length !=0)
    {
      $("#save_nbn_alert_details").attr("disabled",false);
    }
    else 
    {
      $("#save_nbn_alert_details").attr("disabled",true);
    }
  });
})

//Auto Search On Nbn Zones
$(function(){

    $('#input_nbn').keyup(function(){
        
        var searchText = $(this).val();
        
        $('ul > li').each(function(){
            
            var currentLiText = $(this).text(),
                showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            
            $(this).toggle(showCurrentLi);
            $("#black_loading").hide();
            $("#red_loading").hide();
            $("#amber_loading").hide();
            $("#green_loading").hide();
            
        });     
    });

});