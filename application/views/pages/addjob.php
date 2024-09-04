<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("input[name='date']").datepicker({dateFormat: "yy-mm-dd"});
});
</script>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<?php echo form_open('submit-job', array('class' => 'form-add-job')) ?>
	 <table>
	    <tr><th colspan="2"><h2>Client Management</h2></th></tr>
	    <tr>
		   <td>Client:</td><td>    
		   <select name="client"> 
		   <?php $i=0;
  		       foreach($clients as $key => $row):
			?>
			<option value="<?php echo $key;?>"> <?php echo $row;?></option>	   
			<?php 
		        endforeach;  ?>		 
		 </select> 
		 </td>
		</tr>
		<tr>
		<td>Date:</td><td><input type="text" name="date" ></td>
		</tr>
		 <tr>
		   <td>Job no.:</td><td><input type="text" name="jobno" ></td>
		</tr>
		<tr>
		   <td>Description:</td><td><input type="text" name="description" ></td>
		</tr>
		<tr>
		<td colspan="2"><input class="btn" type="submit" value="Add" ></td>
		</tr>
	 </table>
	</form>
</div>