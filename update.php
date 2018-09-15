<div class="example-modal">
  <div class="modal" id="popupdate">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Update Category</h4>
        </div>
        <div class="modal-body">
          <!--  form content-->         
          <div class="form-group">
            <form id="update" method="post">
              <label>Category</label>
             <select name="parentid" id="category" class="form-control main parent">

              <option value="no">Select-Category</option>
              <?php foreach($category as $value) {  ?>
               <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
             <?php } ?>
           </select><br> 
           <label>Sub-Category</label>
           <input type="text" id="sub" class="form-control" name="name"><br>
           <input type="hidden" name="id" id="cateid">   

           <input type="submit" class="form-control btn-primary" name="submit" value="Update">
         </form>
         <script>
          $(function(){
          $(document).on('click', '.update_button', function(){
             var cateid = $(this).val();
             $.ajax({
              type : "POST",
              url : "<?php echo base_url('index.php/category/getCategory'); ?>",
              data : "id="+cateid,

              success : function(data)
              {
                var category = $.parseJSON(data);
                console.log(data);
                $("#sub").attr('value',category.name);
                $("#cateid").attr('value',category.id);

                $('.parent option[value='+category.parentid+']').attr('selected','selected');
/*                console.log($('#category option[value='+category.parentid+']'));
*/                
              },
              error : function(XMLHttpRequest,textStatus,errorThrown)
              {
                alert("unable to process data:" + errorThrown);
              }


            });
           });
         });
          $("#update").submit(function(){
            var cateName = $("#update").serialize();
                  //to get all form data

                  $.ajax({
                    type : "POST",
                    url : "<?php echo base_url('index.php/category/updateCategory'); ?>",
                    data : cateName,

                    success : function(data)
                    {   
                      var toaster = $('#sub').val();
                      if(data == false)
                      {
                        $.toaster({ priority : 'success', title : 'Successfully Updated', message : toaster});
                        $(".modal").hide();
                      }
                      else{
                       alert(data);
                     }
                   },
                   error: function(XMLHttpRequest, textStatus, errorThrown) { 
                     /*alert("Error: " + errorThrown);*/ 
                     alert("unable to process data:"+errorThrown);
                   }
                 }); 
               return false; // to stop the actual form post method
             });

           </script>