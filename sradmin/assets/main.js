var table;
$(document).ready(function() {
  
  // uplodifive start

  $(".uploadifive-button").each(function() {
   $(this).uploadifive({
     'auto'             : true,
     'multi'            : false,
     'buttonText'       : 'Choose Image',
     'fileTypeExts'     : '*.gif; *.jpg; *.png',
     'fileObjName'      : 'image_upload',
     'uploadScript'     :  "ajax/upload.php",
     'onUploadComplete' : function(file, data) {
      var obj = $(this);
      setTimeout(function(){
        $("div.uploadifive-queue-item.complete").fadeOut("linear",function(){
          $(this).remove()
        });
      },1500);
      obj.parents(".form-group").find(".get_image").val(data);
      obj.parents(".form-group").find("img").attr("src",data);
      
      if($("#remove_profile_pic").length > 0){
        if($("#remove_profile_pic").is(':hidden')){
          $("#remove_profile_pic").removeClass("blind");
        }
      }
    }
  });
 });


  $('.uploadifive-button').uploadifive({
    'auto'             : true,
    'multi'            : false,
    'buttonText'       : 'Choose Image',
    'fileTypeExts'     : '*.gif; *.jpg; *.png',
    'fileObjName'      : 'image_upload',
               // 'queueID'          : 'queue',
                // 'dnd'              :  'true',
                'uploadScript'     :  "ajax/upload.php",
                'onUploadComplete' : function(file, data) {
                  var obj = $(this);
                  setTimeout(function(){
                    $("div.uploadifive-queue-item.complete").fadeOut("linear",function(){
                      $(this).remove()
                    });
                  },1500);
                  obj.parents(".form-group").find(".get_image").val(data);
                  obj.parents(".form-group").find("img").attr("src",data);
                  
                  if($("#remove_profile_pic").length > 0){
                    if($("#remove_profile_pic").is(':hidden')){
                      $("#remove_profile_pic").removeClass("blind");
                    }
                  }
                }
              });
       // uploadify end


       //  toggle 

       $(document).on("ifChanged",".hide_show",function(){
        if($(this).is(':checked'))
        {  
          $("."+$(this).attr("name")).slideDown();
        }
        else
        { 
          
          $("."+$(this).attr("name")).slideUp();
        }
      });

       $(document).ajaxStop(function(){
        $(function () {
          $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
                  increaseArea: '20%' // optional
                });
        });
      });

       $(document).on("ifChanged",".master_check",function(event){
         event.stopPropagation()
         if($(this).is(':checked')) {
           $(".child_check").iCheck('check');
           $(".master_check").iCheck('check');
         } else {
           total_checked_child = $(".child_check:checked").length;
           total_child = $(".child_check").length;
           if(total_checked_child == total_child)
           {
             $(".child_check").iCheck('uncheck');
           }
           
           $(".master_check").iCheck('uncheck');
         }
         
         if($(".master_check:checked").length > 0 && $(".child_check:checked").length > 0)
         {
          $(".confirm_delete").attr("disabled",false);
        }
        else 
        {
         $(".confirm_delete").attr("disabled",true); 
       }
     });

       $(document).on("ifChanged",".child_check",function(event){
         total_child = $(".child_check").length;
         total_checked_child = $(".child_check:checked").length;
         if(total_checked_child < total_child)
         {
          $(".master_check").iCheck('uncheck');
        }

        if(total_checked_child==total_child)
        {
         $(".master_check").iCheck("check");
       }

       if($(".child_check:checked").length > 0)
       {
        $(".confirm_delete").attr("disabled",false);
      }
      else 
      {
       $(".confirm_delete").attr("disabled",true); 
     }

   });    

       if($(".data_table_svr").length > 0){
         i = 0;
         tables = [];
         $(".data_table_svr").each(function() {
          var lastColumn = ($(this).find('tr th').length/2)-1;
           //alert(lastColumn);
           tables[i] =  $(this).dataTable({
            "lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            "bPaginate": true,
            "bLengthChange": true,
            "stateSave" : true,
            "bFilter": true,
            "bSort": true,
            "iDisplayLength": 25,
            "bInfo": true,
            "bAutoWidth": false,
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": $(this).data("source"),
            "oLanguage": {
             "sLoadingRecords": '<div class="text-center"><span class="fa fa-circle-o-notch fa-spin fa-1x text-primary"></span> Loading</div>',
             "sProcessing": '<div class="text-center"><span class="fa fa-circle-o-notch fa-spin fa-1x text-primary"></span> Loading</div>',
           },
           "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0 , lastColumn ] }],
           
           
         });
           i++;
         });
       }

     });