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

function validate()
{
var jobname,projecttype;
jobname = document.getElementById("jobname").value;
projecttype = document.getElementById("project_type").value;
  if(jobname == "")
    {
       document.getElementById("jobname_validate").innerHTML = "Please fill in the job name field";
    }
	else if(projecttype == "")
	{
        document.getElementById("projecttype_validate").innerHTML = "Please select any project type";
	}
    else
    {
    document.getElementById("form1").submit(); 
    }
}
</script>
<?php  foreach($jobDetails as $key=>$values){
$id = $values["id"];
$job_no =  $values["job_no"];
//$mubadalajobno = substr($job_no,0,4);
$first_jobno = substr($job_no,0,4);
if($first_jobno == "EKSC") { 
$ek = 'y';
//To find consolidated jobno in case of jobs under 750
$jobnoWithoutIndex = substr($job_no,0,10);
$consolidateJobNo = $jobnoWithoutIndex."01";
} 
else if($first_jobno == 2336 || $first_jobno == 2353 || $first_jobno == 2401){
	// job no thats meant to be retainer with first job has to be consolidated job no. on local its 2218 for Mubadala. server 2336
 	$retainerconsolidated = "y";
	$jobnoWithoutIndex = substr($job_no,0,8);
    $consolidateJobNo = $jobnoWithoutIndex."01";
}
$retainerjob  = $values["retainer_c_job"];
if($retainerjob == "y")
    $retainer = "y";
$clientname  = $values["clientname"];
$clientid  = $values["clientid"];
$jobname = $values["jobname"];
$description = $values["description"];
$projecttype=$values["projecttype"];
$projecttypes = explode("/",$projecttype);


$date = $values["date"];
$approved =  $values["approved"];
$invoiced =  $values["invoiced"];
$ekbillable =  $values["ekbillable"];
$jobclosed =  $values["jobclosed"];
$retainerscope = $values["retainerscope"];
$consolidated_check = $values["consolidated_check"];
$consolidatedB_c_job = $values["consolidatedB_c_job"];
$monthly_consol_jobno = $values["monthly_consol_jobno"];
}?>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<?php echo form_open('edit-job/'.$id, array('class' => 'form-add-job','id'=>'form1')) ?>

	 <table>
	    <tr><th colspan="2"><h2>Client Management</h2></th><input type="hidden" name="client_hidden" id="client_hidden" value="<?php echo $clientname;?>" ><input type="hidden" name="clientid_hidden" id="clientid_hidden" value="<?php echo $clientid;?>" > </tr>
	    <tr>
		   <td>Client:</td><td> <?php echo $clientname;?></td>		  		 
		</tr>
		<tr>
		<td>Date:</td><td><!--<input type="text" name="date" value="<?php //if(isset($date)) echo $date;?>">--><?php if(isset($date)) echo $date;?></td>
		</tr>
		 <tr>
		   <td>Job no.:</td><td><input type="hidden" name="jobno" value="<?php if(isset($job_no)) echo $job_no;?>"><?php if(isset($job_no)) echo $job_no;?></td>
		</tr>
		 <tr>
		   <td>Job name.:</td><td><input type="text" name="jobname" id="jobname" value="<?php if(isset($jobname)) echo $jobname;?>"><span id="jobname_validate" style="color:red;"></span></td>
		</tr>
		<tr>
		   <td>Description:</td><td><input type="text" name="description" id="description" value="<?php if(isset($description)) echo $description;?>"></td>
		</tr>
		<tr>
		<td>Project type</td><td>
			<select name="project_type[]" id="project_type" multiple="multiple">
							        
							<?php $i=0;
							//print_r($project_types);
								foreach($project_types as $key => $row):
							?>
							<option value="<?php echo $row['id'];?>" <?php if(in_array($row['id'], $projecttypes)) echo "selected";?>> <?php echo $row['project_type'];?></option>	   
							<?php 
							endforeach;  ?>		
						</select>
						<span id="projecttype_validate" style="color:red;"></span>
			</td>	
		</tr>
        <?php  if( (isset($ek) && $ek == "y") || (isset($retainer) && $retainer == "y") ) { // only for ek retainer jobs and other retainer jobs?>
         <tr>
        <td colspan="2"><input type="checkbox" name="retainerscope" id="retainerscope" value="y" <?php  if($retainerscope  == "y") echo "checked";?> >&nbsp;&nbsp;Scope of Work</td>
        </tr>
        <tr>
        <td colspan="2"><input type="checkbox" name="ekbillable" id="ekbillable" value="y" <?php  if($ekbillable  == "y") echo "checked";?> >&nbsp;&nbsp;Billable</td>
        </tr>
		<!-- if jobno needs to be added in under750, only for ek retainer jobs-->
		<?php if(isset($ek) && $ek == "y") { if($monthly_consol_jobno != "y")  { // not needed if its a monthly consolidated job no_ that is the one that ends with 01?> <tr>
        <td colspan="2"><input type="checkbox" name="under750" id="under750" value="y" <?php  if($consolidated_check  == "y") echo "checked";?> >&nbsp;&nbsp;Under 2750 <input type="hidden" name="consolidateJobNo" id="consolidateJobNo" value="<?php echo $consolidateJobNo;?>"></td>
        </tr>
        <?php } }
		if(isset($retainerconsolidated) && $retainerconsolidated  == 'y' ) { if($monthly_consol_jobno != "y") {
		?>
				<tr>
				<!-- name under750 is used here also, because the in the controller its read using this variable and same database field is used for both under750 and consolidated job numbers -->
        <td colspan="2"><input type="checkbox" name="under750" id="under750" value="y" <?php  if($consolidated_check  == "y") echo "checked";?> >&nbsp;&nbsp;Add to consolidated job no <input type="hidden" name="consolidateJobNo" id="consolidateJobNo" value="<?php echo $consolidateJobNo;?>"></td>
        </tr>
		<?php } } } ?>  
		 
	
			
		<input type="hidden" name="checkEKretainer" id="checkEKretainer" value="<?php if(isset($ek)) {echo $ek;}?>">
		<input type="hidden" name="checkOtherretainer" id="checkOtherretainer" value="<?php if(isset($retainer)) echo $retainer;?>">
		<input type="hidden" name="checkconsolidatedBCjob" id="checkconsolidatedBCjob" value="<?php if(isset($consolidatedB_c_job)) echo $consolidatedB_c_job;?>">
        <tr>
		   <td colspan="2"><input type="checkbox" name="approval" id="approval" value="y" <?php  if($approved  == "y") echo "checked";?> >&nbsp;&nbsp;Approved</td><input type="hidden" name="approved_previous" id="approved_previous" value="<?php echo $approved;?>">
		</tr>
         <tr>
		   <td colspan="2"><input type="checkbox" name="invoiced" id="invoiced" value="y" <?php  if($invoiced  == "y") echo "checked";?> >&nbsp;&nbsp;Invoiced</td>
		</tr>
		<tr>
		   <td colspan="2"><input type="checkbox" name="jobclosed" id="jobclosed" value="y" <?php  if($jobclosed  == "y") echo "checked";?> >&nbsp;&nbsp;Closed</td>
		</tr>
		<tr>
		<td colspan="2"><input class="btn" type="button" value="Update" onClick="JavaScript: return validate();" ></td>
		</tr>
	 </table>
	</form>
</div>