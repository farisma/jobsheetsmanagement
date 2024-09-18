<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
  <script>
  $(document).ready(function(){
      $("input[name='startdate']").datepicker({dateFormat: "yy-mm-dd"});
	  $("input[name='enddate']").datepicker({dateFormat: "yy-mm-dd"});
	  
	  });

  </script>
<script type="text/javascript">
function validate()
{
var emp,sd,ed,nod;

sd =  document.getElementById("startdate").value;
ed =  document.getElementById("enddate").value;
nod = document.getElementById("noofdays").value;
    if(emp == "" || sd == "" || ed == "" || nod == "")
      {
        if(emp == "") {
          document.getElementById("employeename_validate").innerHTML = "Please fill in the employee name field"; 
        }
        else if(sd == "") {
          document.getElementById("sd_validate").innerHTML = "Please fill in the start date field"; 
        }
		else if(ed == "") {
          document.getElementById("ed_validate").innerHTML = "Please fill in the end date field"; 
        }
		else if(nod == "") {
          document.getElementById("nod_validate").innerHTML = "Please fill in the no. of days field"; 
        }
      }
      else {
         document.getElementById("form1").submit(); 
      }
}
</script>
<?php if(isset($message)) {
	if($message == "true") { ?> <div class="alert"> <?php if($mailsent == "true") {echo "Leave has been approved. An email has been sent to the employee."; } else { echo "Leave request has been approved. But there was an error in sending email to the employee.";}?></div> 
	<?php }
	else { ?> <div class="alert"> <?php echo "Leave hasn't been approved.";?></div>
	<?php 
	
	
	}
	?><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<?php 

?>
<div class="hero-unit">
<?php 
if(isset($leaverequestsbyid)) {
foreach($leaverequestsbyid as $rows){
	
	$requestid = $rows['id'];
	$startdate = $rows['startdate'];
	$enddate = $rows['enddate'];
	$employeeid = $rows['employee_id'];
	$vacationtypeid = $rows['vacationtypeid'];
	$username = $rows['username'];
	$noofdays = $rows['noofdays'];
	$notes = $rows['notes'];
}

?>
<?php echo form_open('vacation/addandApproveEmployeeleaves', array('class' => 'form-add-leaves','id' => 'form1')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Leave Management</h2></th></tr>
	    <tr>
		   <td>Name:</td>
           <td><?php echo $username;?><input type="hidden" id="userid" name="userid" value="<?php echo $employeeid;?>"><input type="hidden" id="vacationrequestid" name="vacationrequestid" value="<?php echo $requestid;?>"><?php ?></td>
		</tr>
        <tr>
        <td>Start date:</td>
         <td>
          <input type="text" name="startdate" id="startdate"  style="text-transform:uppercase;" value="<?php echo $startdate;?>"><span id="sd_validate" style="color:red;"></span>
         </td>
        </tr>
		   <tr>
        <td>End date:</td>
         <td>
          <input type="text" name="enddate" id="enddate" style="text-transform:uppercase;" value="<?php echo $enddate;?>"><span id="ed_validate" style="color:red;"></span>
         </td>
        </tr>
		 <tr>
        <td>No. of days:</td>
         <td>
          <input type="text" name="noofdays" id="noofdays" style="text-transform:uppercase;" value="<?php echo $noofdays;?>"><span id="nod_validate" style="color:red;"></span>
         </td>
        </tr>
		<tr>
		<td>Vacation Type</td>
		<td>
		<select name="vacation_type" id="vacation_type">
		<?php 
		if(isset($vacationtypes))  {
		foreach($vacationtypes as $rows => $values) {?>
		<?php ?>
		<option value="<?php echo $rows;?>" <?php if($rows == $vacationtypeid) { echo "selected";}?>><?php echo $values;?></option>
		<?php } }?>
		</select>
		</td>
		</tr>
		<tr>
		<td>
		Notes:
		</td>
		<td>
		<textarea name="notes" id="notes" rows="4" cols="4">
		<?php echo $notes;?>
		</textarea>
		</td>
		</tr>
         <tr>
        <td><input class="btn" type="button" value="Approve" onclick="Javascript: return validate()"></td> 
         
        </tr>
	 </table>
	</form>
<?php }?>
</div>
