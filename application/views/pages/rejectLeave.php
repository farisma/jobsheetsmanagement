<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
 
<script type="text/javascript">
function validate()
{
	var remarks;
	remarks =  $('textarea').val();
	
			if(remarks == "") {
			document.getElementById("remarks_validate").innerHTML = "Please fill in the reason for rejection"; 
			}          
		else {
			document.getElementById("form1").submit(); 
		}
}
</script>
<?php if(isset($message)) {
	if($message == "true") { ?> 
	    <div class="alert"> <?php echo "Leave request has been rejected. A message is sent to the employee.";?></div> 
	<?php }
	else { ?> 
	    <div class="alert"> <?php echo "Leave hasn't been rejected.";?></div>
	<?php 		
		 }
	?>
	<?php }?>
<?php $this->load->view("pages/adminmenu")?>
<?php 

?>
<div class="hero-unit">
<?php 
if(isset($leaverequestsbyid)) {
	foreach($leaverequestsbyid as $rows){
		
		$requestid = $rows['id'];	
		$employeeid = $rows['employee_id'];	
		$username = $rows['username'];
	}

?>
<?php echo form_open('vacation/updateLeaveRejection', array('class' => 'form-add-leaves','id' => 'form1')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Leave Management</h2></th></tr>
	    <tr>
		   <td>Name:</td>
           <td><?php echo $username;?><input type="hidden" id="userid" name="userid" value="<?php echo $employeeid;?>"><input type="hidden" id="vacationrequestid" name="vacationrequestid" value="<?php echo $requestid;?>"><?php ?></td>
		</tr>
         <tr>
		   <td>Remarks:</td>
		   <td>
		   <textarea rows="4" cols="4" name="remarks" id="remarks">		   
		   </textarea><span id="remarks_validate"></span>
		   </td>
		 </tr>		
         <tr>
        <td><input class="btn" type="button" value="Reject" onclick="Javascript: return validate()"></td>
         
        </tr>
	 </table>
	</form>
<?php }?>
</div>
