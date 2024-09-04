<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/stickyheader/jquery.freezeheader.js"></script>
   <script>
  $(document).ready(function(){
  $("input[name='date']").datepicker({dateFormat: "yy-mm-dd"});
   $("#listjobs").freezeHeader();

  });
function disableJobById(value)
  {
	  var jobnoId = value;
	   
	   var msg;
	   jQuery.ajax({
		type: "GET",
		url: "<?php echo base_url(); ?>"+"index.php/disable-job/"+jobnoId,
		data: {"data":jobnoId},
		dataType: "json",
		success: function(res) {
		if (res)
		{
		        if(res == 1)
				{ 
				   msg = "Job No. has been disabled successfully";
				   $("table.listjobs tr#jobno"+jobnoId).remove();
				}
				else if(res == 0)
				msg = "Disable Unsuccessfull";
		}
		else 
		{
		        msg = "Error occurred. Disable unsuccessfull";
		}
				
		       $("div.alert").remove();
			   $("div.statusmessage").append("<div class='alert'>"+msg+"</div>");
		}
		});
  }
  
  function deleteJobById(value)
  {
   var jobnoId = value;
   jQuery.ajax({
		type: "GET",
		url: "<?php echo base_url(); ?>"+"index.php/delete-job/"+jobnoId,
		data: {"data":jobnoId},
		dataType: "json",
		success: function(res) {
		if (res)
		{
		        if(res == 1)
				{ 
				   msg = "Job No. has been deleted successfully";
				   $("table.listjobs tr#jobno"+jobnoId).remove();
				}
				else if(res == 0)
				msg = "Deletion Unsuccessfull";
		}
		else 
		{
		        msg = "Error occurred. Deletion unsuccessfull";
		}
				
		       $("div.alert").remove();
			   $("div.statusmessage").append("<div class='alert'>"+msg+"</div>");
		}
		});
  }
  
function checkClient(){
      var clientId = $("#clientlist").val();
      //console.log(clientId);
         jQuery.ajax({
		type: "GET",
		url: "<?php echo base_url(); ?>"+"index.php/checkIfRetainerJob/"+clientId,
		data: {"data":clientId},
		dataType: "json",
		success: function(res) {
		if (res)
		{
		     			
			if(res == "true")
            {
                if(!$("#ekbillable").length > 0) {
                $("#clientlist").parent("td").next("td").append("<input type='checkbox' name='ekbillable' id='ekbillable' value='y'>Billable");
                }
            }   
            else if(res == "false")            
            {
                
                $("#ekbillable").parent("td").empty();
            }        
		}
		else 
		{
		        msg = "Error occurred. Deletion unsuccessfull";
                console.log(msg);
		}				
		       
		}
		});
  } 
  </script>
  <!-- isadmin is only set when its sent from jobsheets controller.
       if its not that means its called from clients controller,
       which only admin can call. So $admin variable is set to true if the isadmin variable is not set and if its set its whatever the value that is set from jobsheets controller. 
	-->
  <?php if(isset($isadmin)) { $admin = $isadmin;} else $admin = true; ?>
  <?php  $user = $user['username']; ?>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php if($admin) { $this->load->view("pages/adminmenu");}?>
<div class="hero-unit">
<div class="statusmessage"></div>
<div><?php  if(isset($noresults)) echo $noresults;?></div>
<table class="listjobs">
<tr><th colspan="5"><h2>Job No. Management</h2></th></tr>
<tr><th colspan="5" align="center"><?php if($admin) {?><a href="<?php echo site_url('submit-job') ?>">Add new job</a><?php } ?></th></tr> 
<tr>
<td colspan="5">
<?php //if($admin) { echo form_open('search-jobs', array('class' => 'form-add-job')); } else { echo form_open('search-jobs-user', array('class' => 'form-add-job'));}?>
<?php if($admin) { echo form_open('search-jobs', array('class' => 'form-add-job')); }
else {
	echo form_open('search-jobs-user', array('class' => 'form-add-job'));
}
?>
  <table>
  <tr><td colspan="1">Filter:</td><td colspan="1">
<?php if(isset($clients)) {
echo  "<select name='client' id='clientlist' onChange='javascript: checkClient()'> <option value='0'>Choose Client</option>";
foreach($clients as $key => $rows) {
echo "<option value=".$key.">".$rows."</option>";
}
 echo "</select>";}?>
</td><td colspan="1" class="invoiced"><input type="checkbox" name="invoiced" id="invoiced" value="y">&nbsp;Invoiced&nbsp;</td><td colspan="1" id="billable"><!--<input type="checkbox" name="ekbillable" value="y" >Billable--></td><td colspan="1"><input type="text" placeholder="Date" name="date"><input style="vertical-align:top;" type="submit" class="btn" name="search" Value="Search"></td>
</tr>
  </table>
</form>
</td>
</tr>
</table>
<table class="listjobs" id="listjobs">
 <thead><tr style="background:#dddddd;"><th width="50">&nbsp;</th><th width="50" class="smallfont">Retainer</th><th width="50" class="smallfont">Billable</th><th width="50" class="smallfont">Approved</th><th width="50" class="smallfont">Invoiced</th><th width="50" class="smallfont">Consolidated billing</th><th width="70">Date</th><th width="70">Type</th><th width="80">Job No.</th><th width="150">Job Name</th><th width="250">Description</th><th width="80">Client</th><?php if($admin) {?><th width="100">Action</th><?php }?></tr>
</thead>
 <?php

$i = 1;
$icon_true = '<img src="'.base_url().'assets/img/true.png" width="16" height="16" align="center">';
$icon_false = '<img src="'.base_url().'assets/img/false.png" width="12" height="12" align="center">';
if(isset($jobs)) {foreach($jobs as $key => $rows)
{
    if($jobs[$key]["date"] != NULL) {$dateadded= date('d/M/Y',strtotime($jobs[$key]["date"])); } else {$dateadded = $jobs[$key]["date"];}
    if($jobs[$key]["invoiced"] == "n") {$invoiced = "No";$invoiced_icon = $icon_false;} else  {$invoiced = "Yes";$invoiced_icon = $icon_true;}
    if($jobs[$key]["ekbillable"] == "n") {$billable = "No";$billable_icon = $icon_false;} else  {$billable = "Yes";$billable_icon = $icon_true;}
    if($jobs[$key]["approved"] == "n") {$approved = "No";$approved_icon= $icon_false;} else {$approved = "Yes";$approved_icon= $icon_true;}/*$flag = "<div style='width:10px; height:5px;background:red;'></div>";} else {$flag = "<div style='width:10px; height:5px;background:green;'></div>";*/
  if($jobs[$key]["retainer_c_job"] == "y" || $jobs[$key]["eksc_retainer"] == "y")	 {
      $retainer = "Yes";
      $retainer_icon= $icon_true;
  }
  else {
      $retainer = "No";
      $retainer_icon= $icon_false;
  }
     if($jobs[$key]["consolidated_check"] == "y")	 {
      $under750 = "Yes";
      $under750_icon= $icon_true;
  }
  else {
      $under750 = "No";
      $under750_icon= $icon_false;
  }
  $consolidate_jobno = $jobs[$key]["consolidated_jobno"];
    ?>
     <tbody>
<tr id="jobno<?php echo $jobs[$key]["id"]; ?>">
<td><?php echo $i;?></td>
<td><?php echo $retainer_icon;?></td>
<td><?php echo $billable_icon;?></td>
<td><?php echo $approved_icon;?></td>
<td><?php  echo $invoiced_icon; ?></td>
<td><?php  echo $under750_icon; ?>
<?php //echo $consolidate_jobno;?></td>
<td><?php  echo $dateadded; ?></td>
<td><?php echo $jobs[$key]["project_type"];?></td>
<td><?php  echo $jobs[$key]["jobno"]; ?></td>
<td><?php  echo $jobs[$key]["jobname"]; ?></td>
<td><?php  echo $jobs[$key]["description"]; ?></td>
<td><?php echo $jobs[$key]["clientname"];?></td>
<?php if($admin) {?><td><a href="<?php echo site_url('edit-job/'.$jobs[$key]["id"]) ?>">Edit</a>&nbsp;&nbsp;<a onClick="disableJobById(<?php echo $jobs[$key]["id"];?>)" href="<?php echo "#"; ?>">Disable</a>&nbsp;&nbsp;<?php  if($user == "admin"  || $user == "brandon") {?><a onClick="deleteJobById(<?php echo $jobs[$key]["id"];?>)" href="<?php echo "#"; ?>">Delete</a><?php  } ?></td><?php } ?>
</tr>
</tbody>
<?php 
  $i++;
}
}
?>
</table>
<?php if(isset($pagin_links)) echo $pagin_links;?>
<?php 
if(isset($searchresults) && $searchresults == "yes") {
 echo form_open('clients/exportJobNos', array('class' => 'searchresultslistform','id'=>'searchresultslistform')); 
if(isset($queryitems)) {
foreach($queryitems as $key=>$value) {
	             if($key == "client")
			   {
				$client = $value;
			   }
		 if($key == "date")
			   {
				$date = $value;
			   }
		   if($key == "ekbillable")
			   {
				$ekbillable = $value;
			   }
		    if($key == "invoiced")
			   {
				$invoiced = $value;
			   }
	
		}
?>
<input type="hidden" name="hiddenclient" value="<?php  if(isset($client)) echo $client; else echo 0;?>">
	   <input type="hidden" name="hiddendate" value="<?php  if(isset($date)) echo  $date;?>">
	   <input type="hidden" name="hiddenekbillable" value="<?php  if(isset($ekbillable))  echo $ekbillable;?>">
	   <input type="hidden" name="hiddeninvoiced" value="<?php  if(isset($invoiced))  echo $invoiced;?>">
       <input type="submit" name="exportexcel" class="btn" value="Export as Excel">
<?php 
}
}
?>
</form>
</div>