 <script>
    $(function(){
   $(document).on('click', '.delete_button', function(){
    var cateid = $(this).val();
     bootbox.confirm({
    message: "do you want to delete?",
    buttons: {
        confirm: {
            label: 'Yes',
            className: 'btn-success'
        },
        cancel: {
            label: 'No',
            className: 'btn-danger'
        }
    },
    callback: function (result) {
        if(result == true){
     
           $.ajax({
            type : "POST",
            url : "<?php echo base_url('index.php/category/deleteCategory'); ?>",
            data : "id="+cateid,
            
            success : function(data)
            {

               $.toaster({ priority : 'success', title : 'Successfully Deleted', message : "Add again"});        
            
            },
            error : function(XMLHttpRequest,textStatus,errorThrown)
            {
              alert("Can't delete this state:" + errorThrown);
            }

           });
         }
       }
          });
   });
});
        </script>