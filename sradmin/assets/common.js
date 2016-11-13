  $(function(){
    if($('.ladda-button').length > 0){
        loaders = Ladda.create( document.querySelector( '.ladda-button' ) );
     }
    
  });

 
    /*function get_message(msg_type,msg)
    {
      var msg_image = "assets/dist/img/success.png";
      var title = "";
      
        if(msg_type=="error") {
             title="Error"; 
             msg_image = "assets/dist/img/error64.png";
        }

        if(msg_type=="success") {
             title="Success"; 
             msg_image = "assets/dist/img/success.png";

        }  
            $.gritter.add({
                title: title,
                text: msg,
                image: msg_image,
                sticky: false,
                position: 'bottom-right',
                time: 3500,
                before_open: function(){
                    if($('.gritter-item-wrapper').length == 1)
                    {
                        return false;
                    }
                }
            });
            $('.gritter-item-wrapper').parent().addClass('bottom-right');
     }*/

     function get_message(msg_type,msg)
     {
        if(msg_type=="error") {
          message_append = '<i class="fa  fa-exclamation-circle fa-2x"></i> ';
          type="error";
        }

        if(msg_type=="success") {
          message_append = '<i class="fa  fa-check-circle fa-2x"></i> ';
          type = "success";
        } 

        var n = noty({
            text        : message_append+" "+msg,
            type        : type,
            dismissQueue: true,
            layout      : "bottomRight",
           // theme       : 'defaultTheme',
            timeout: 10,
            animation: {
                open: 'animated bounceInRight', // Animate.css class names
                close: 'animated bounceOutRight', // Animate.css class names
                easing: 'swing', // unavailable - no need
                speed: 500 // unavailable - no need
            },
            maxVisible: 1,
        });

        setTimeout(function(){
          n.close();
        },4000);

     }


      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });


       
      });
      

 function ajax_submit(form_id,callback)
 {
   var id = $("#"+form_id);
     $.ajax({
       url: id.attr('action'),
       type: "POST",
        dataType: "JSON",
       data: id.serialize(),
       success : function(data){
         console.log(data);
         callback(data);
       },
       error:function(){
         //$(".message").html("Something Went Wrong Please Try Again");
         get_message("error","Something Went Wrong Please Try Again"); 
           loaders.stop();
       }

     }) 
     
 }
