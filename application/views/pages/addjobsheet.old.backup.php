<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
  <script>
  $(document).ready(function(){
      $("input[name='date']").datepicker({dateFormat: "yy-mm-dd"});
	   $("select[name='client']").prepend("<option value='' selected='selected'>Choose Client</option>");
	   $("select[name='jobno']").prepend("<option value='' selected='selected'>Choose Job no.</option>");
	  });

  </script>
<script type="text/javascript">
function getJobcodes(value,name) {
         var clientid = value;
		  jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetJobcodes/"+name,
		data: $('#addjobsheetform').serialize(),
		dataType: "json",
		success: function(res) {
		if (res)
		{
		         var jobCodeSelector = $("select[name='"+name+"']").parent().closest('td').next('td').find('select');  
                  jobCodeSelector.empty();
				  jQuery.each(res, function( key, value ) {
				  jobCodeSelector.append($("<option></option>").attr("value",key).text(value));
				});
				jobCodeSelector.prepend("<option value='' selected='selected'>Choose Job no.</option>");
		
		}
		else 
		{
		          var jobCodeSelector = $("select[name='"+name+"']").parent().closest('td').next('td').find('select');  
                  jobCodeSelector.empty();
		}
		}
		});
 }
</script>

<?php echo form_open('', array('class' => 'addjobsheet','id'=>'addjobsheetform')) ?>
	 <table class="addjobsheet" id="addjobsheet">
	    <tr>
		<th colspan="6"><h2>Timesheet Management</h2></th>
		</tr>
		<tr>
		<th colspan="1">Company</th><th colspan="1">Job code</th><th colspan="1">Date</th><th colspan="1">Hours</th><th colspan="1">Job description</th><th colspan="1">User</th>
		</tr>
         <tr>
		  <td>
		    <?php   $js = 'onchange="getJobcodes(this.value,this.name);"';  
			               echo form_dropdown('client', $clients,1,$js) ?>
			<?php echo form_error('client'); ?>   
			</td>
		   <td>
			<?php echo  form_dropdown('jobno') ?>
			<?php echo form_error('jobno'); ?>   
			</td>
		   
		   <td> 
		    <?php echo form_input($date); ?>
			<?php echo form_error('date'); ?>  
		   </td>
		   <td>
		    <?php echo form_input($timespent); ?>
			<?php echo form_error('timespent'); ?>  
		    </td>		   
		   <td>
		    <?php echo form_input($description); ?>
		    <?php echo form_error('description'); ?>  		     
		   </td>
		    <td>
		    <?php echo form_dropdown('userid',$users,0); ?>
			<?php echo form_error('userid'); ?> 
		   </td>
		</tr>
		 
		  
  
		<tr>
		<td colspan="2"> <input type="button" id="submitjobsheet" value="Submit" ></td>
		</tr>
	 </table>
	</form>
</div>
<script type="text/javascript"> 

$(document).ready(function(){

  $("#submitjobsheet").click(function(){
  
       jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "index.php/submitJobSheets",
		data: $('#addjobsheetform').serialize(),
		dataType: "json",
		success: function(res) {
		if (res)
		{
		       jQuery.each(res, function( key, value ) {
				  $("div.alert").html(value);
				});
		}
		else 
		{
		      alert("Error.");
		}
		}
		});
  
  
  });
 
 
 
 });
 </script>