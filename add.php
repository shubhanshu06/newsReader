  <button class="btn btn-primary" data-toggle="modal" data-target="#popup" style="float: right;" >Add News</button>
  <div class="example-modal">
  	<div class="modal" id="popup">
  		<div class="modal-dialog">
  			<div class="modal-content">
  				<div class="modal-header">
  					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  					<h4 class="modal-title">Add News</h4>
  				</div>
  				<div class="modal-body">
  					<!--  form content-->          
  					<div class="form-group">
  						<form id="add" method="post" enctype="multipart/form-data">
  							<label>Title</label>
  							<input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" ><br>
                <label>Date</label>
                <input type="Date" class="form-control" id="date" name="date" ><br>    
                <label>Content</label>   
                <textarea row="" cols="" class="form-control" id="textarea" name="content" placeholder="Enter content"> </textarea><br>
                <label>Location</label><br>

                <div class="col-md-4">

                  <select id="forstate" name="countryid" class="form-control"><option value="">Select-country</option><?php foreach($country as $value) { ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option> <?php } ?></select></div>
                    <div class="col-md-4">
                     <select id="state" name="stateid" class="form-control">
                      <option value="">Select-State</option>
                    </select></div>
                    <div class="col-md-4">
                      <select id="city" name="cityid" class="form-control">
                        <option value="">Select-City</option>
                      </select><br></div>
                      <label>Category</label><br>
                      <div class="category">
                        <select name="parentid" id="main" class="form-control main subsub">
                          <option value="yes">Select-Category</option><br>
                          <?php foreach($category as $value) { if($value['parentid']==0 & $value['parentid']!= null ) { ?>
                           <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                         <?php }  } ?>
                       </select><br>
                     </div>
                     <label>Image</label>
                     <input type="file" class="form-control" id="files" name="files" ><div id="uploaded_images"> </div><br>
                     <input type="submit" class="form-control btn-primary" name="submit" value="Add">

                   </form>
                 </div>
                 <script>

                  $(function(){
                   $("#add").submit(function(){
          var dataString = $("#add").serialize();//to get all form data
          $.ajax({
          	type : "POST",
          	url : "<?php echo base_url('index.php/news/addNews'); ?>",
          	data : dataString,
          	success : function(data)
          	{
              console.log(data);
              var toaster = $('#title').val();
              if(data == false){
               $.toaster({ priority : 'success', title : 'Successfully Added', message : toaster});
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


                   $('#example1').dataTable({
                    "processing": true,
                    "serverSide": true,
                    "bAutoWidth": false,
                    "ajax": "<?php echo base_url("index.php/news/pagination"); ?>"
                  });

                   $("#forstate").change(function(){
                    var dataCountry = $("#forstate").val();
                    $.ajax({
                      type : "POST",
                      url :"<?php echo base_url('index.php/city/getState'); ?>",
                      data :"countryid="+dataCountry,
                      success : function(data)
                      {
                        var state = JSON.parse(data);
                        console.log(state);
                        if(state)
                        {
                          $('#state').empty();
                          $('#state').html('<option value="">Select-state</option>');
                          $(state).each(function(){
                            var option = $('<option/>');
                            option.attr('value',this.id).text(this.name);
                            $('#state').append(option);

                          });
                        }
                      }
                    });
                  });

                   $("#state").change(function(){
                    var dataCountry = $("#state").val();
                    console.log(dataCountry);
                    $.ajax({
                      type : "POST",
                      url :"<?php echo base_url('index.php/city/getCity1'); ?>",
                      data :"stateid="+dataCountry,
                      success : function(data)
                      {
                        var state = JSON.parse(data);
                        console.log(state);
                        if(state)
                        {
                          $('#city').empty();
                          $('#city').html('<option value="">Select-City</option>');
                          $(state).each(function(){
                            var option = $('<option/>');
                            option.attr('value',this.id).text(this.name);
                            $('#city').append(option);

                          });
                        }
                      }
                    });
                  });
                 });
                  $(document).ready(function(){
                    sid=1;
                    $(".modal-body").on("change", ".main",function(){  
                      var catOption = $(".main").val();
                      var id = "#main";
                      func(catOption,id);
                      $.ajax({
                        type : "POST",
                        url :"<?php echo base_url('index.php/category/getCategory'); ?>",
                        data :"parentid="+catOption,
                        success : function(data)
                        {
                          var parentid = JSON.parse(data);
                          if(parentid)
                          {
                           $('.subcategory').empty();
                           $('.subcategory').html('<option value="no">Select-Category</option><option value="0"> Add</option>');
                           $(parentid).each(function(){
                            var option = $('<option/>');
                            option.attr('value',this.id).text(this.name);
                            $('.subcategory').append(option);

                          });
                         }
                       }
                     });
                    });
                    $(".modal-body").on("change",".subcategory",function(){  
                     var id = "#" + $(this).attr("id");
                     var sel = $(id).val();
                     func(sel,id);
                   });


                    $(".category").on('change','.subs',function(){
                      var subCat = $(this).val();
                      var cid = "#" + $(this).attr('id');
                      func(subCat,cid);
                      $.ajax({
                        type : "POST",
                        url :"<?php echo base_url('index.php/category/getCategory'); ?>",
                        data :"parentid="+subCat,
                        success : function(data)
                        {
                          var cat = JSON.parse(data);
                          console.log(cat);
                          if(subCat!=0 && subCat!='no')
                          {
                            $('.subcategory').last().empty();
                            $('.subcategory').last().html('<option value="no">Select-subcategory</option><option value="0"> add</option>');
                            $(cat).each(function(){
                              var option = $('<option/>');
                              option.attr('value',this.id).text(this.name);
                              $('.subcategory').last().append(option);
                            });
                          }
                        }
                      });
                    });

                    function func(select,id){
                      if(select==0){
                        $(id).nextAll().remove();
                        $(id).after("<br><input type='text' id='changes' name='name' class='form-control' placeholder='Add-Category'>");
                      }
                      else {
                        if(select=='yes'){
                          $(id).nextAll().remove();
                        }

                        else{

                          var str ="<br><select id='subcategory" + sid + "' class='form-control subcategory subs'><br><option>Add subcategory</option><option value=''> Add </option></select>";
                          $(id).nextAll().remove();
                          $(id).after(str);
                          sid++;
                        }
                      }
                    }
                  });
                </script>
              </div>
              <div class="modal-footer">
               <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
             </div>
           </div><!-- /.modal-content -->
         </div><!-- /.modal-dialog -->
       </div><!-- /.modal -->
     </div><!-- /.example-modal -->


     <!--  -->
     <div class="row">
       <div class="col-xs-12">
        <div class="box">
         <div class="box-header">
          <h3 class="box-title">Data Table With Full Features</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
           <thead>
            <tr>
             <th>Id</th>
             <th>Title</th>
             <th>Date</th>
             <th>Content</th>
             <th>image</th>
             <th>city</th>
             <th>category</th>
             <th>Action</th>
           </tr>
         </thead>

       </table>
       <!-- <?php $this->load->view('admin/news/update'); ?>
       <?php $this->load->view('admin/news/delete'); ?>
     --></div><!-- /.box-body -->
   </div><!-- /.box -->
 </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->
<!-- page script -->










<!-- $('#files').change(function(){
                    var files = $('#files')[0].files;
                    var error = '';
                    var form_data = new FormData();
                    for(var count = 0; count<files.length; count++)
                    {
                     var name = files[count].name;
                     var extension = name.split('.').pop().toLowerCase();
                     if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
                     {
                      error += "Invalid " + count + " Image File";
                      alert(error);
                    }
                    else
                    {
                      form_data.append("files[]", files[count]);
                    }
                  }
                  if(error == '')
                  {
                   $.ajax({
    url:"<?php echo base_url('index.php/News/upload'); ?>" , //base_url() return http://localhost/tutorial/codeigniter/
    method:"POST",
    data:form_data,
    dataType: "JSON",
    contentType:false,
    cache:false,
    processData:false,
    beforeSend:function()
    {
     $('#uploaded_images').html("<label class='text-success'>Uploading...</label>");
   },
   success:function(data)
{        var upl = JSON.parse(data);
          console.log(upl);

         $('.text-success').hide();

/*     $('#uploaded_images').html(data);
*/     $('#files').val('');
   }
 });
                 }
                 else
                 {
                   alert(error);
                 }
               });
                   -->