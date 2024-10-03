<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/stickyheader/jquery.freezeheader.js"></script>
   <script>
  $(document).ready(function(){
  $("input[name='date']").datepicker({dateFormat: "yy-mm-dd"});
   $("#listjobs").freezeHeader();
//    $(".jobedit").hide()
var divisionsdropdown, projecttypesdropdown;
jQuery.ajax({
	type: "GET",
		url: "<?php echo base_url(); ?>"+"index.php/get-divisions-and-project-types/",
		data: {},
		dataType: "json",
		success: function(res) {
			console.log("div",res)
			divisionsdropdown = document.createElement('select');
			
			projecttypesdropdown = document.createElement('select');
			projecttypesdropdown.setAttribute('multiple', 'multiple');

			// Loop through the array and create an option element for each division
			res.divisions.forEach(item => {
				let option = document.createElement('option');
				option.value = item.id;  // Set option value to the division id
				option.textContent = item.division;  // Set the displayed text to the division name
				divisionsdropdown.appendChild(option);  // Append each option to the dropdown
			});
			res.projecttypes.forEach(item => {
				let option = document.createElement('option');
				option.value = item.id;  // Set option value to the division id
				option.textContent = item.project_type;  // Set the displayed text to the division name
				projecttypesdropdown.appendChild(option);  // Append each option to the dropdown
			});
			divisionsdropdown.classList.add('divisiondrop');
			projecttypesdropdown.classList.add('projecttypesdrop');
		}

})

   $(".editOnClick").click(function(e){
	e.preventDefault();
	var jobnoId = $(this).data("id")
	if($('tr.jobedit'+jobnoId).length == 0 ) {
	var parent = $(this).closest('tr');
	console.log(parent)
	console.log(jobnoId)
	jQuery.ajax({
		type: "GET",
		url: "<?php echo base_url(); ?>"+"index.php/get-job-for-edit/"+jobnoId,
		data: {},
		dataType: "json",
		success: function(res) {
			if (res)
				{
					console.log(res)
					var retainerchecked,ekscretainerchecked,ekbillablechecked, approvedchecked, invoicedchecked, consolchecked, projecttypesplit,closedchecked,ptdropdown,retainerinput;
					if(res[0].retainer_c_job == "y" || res[0].eksc_retainer == "y")  {
						retainerchecked = "checked";
						// retainerinput = `<input type="checkbox" name="retainerjob" id="retainerjob" value="y" ${retainerchecked} />`;
					}
					if(res[0].ekbillable == "y") ekbillablechecked = "checked";
					if(res[0].approved == "y") approvedchecked = "checked";
					if(res[0].jobclosed == "y") closedchecked = "checked";
					if(res[0].invoiced == "y") invoicedchecked = "checked";
					if(res[0].consolidated_check == "y") consolchecked = "checked";
					
					parent.after(`<tr class="jobedit${jobnoId}">
					<td colspan="1"></td>
					<td colspan="1"><input type="checkbox" name="retainerjob" id="retainerjob" value="y" ${retainerchecked} disabled /></td>
					<td colspan="1"><input type="checkbox" name="billablejob" id="billablejob" value="y" ${ekbillablechecked} /></td>
					<td colspan="1"><input type="checkbox" name="approvedjob" id="approvedjob" value="y" ${approvedchecked} /></td>
					<td colspan="1"><input type="checkbox" name="closedjob" id="closedjob" value="y" ${closedchecked} /></td>
					<td colspan="1"><input type="checkbox" name="invoicedjob" id="invoicedjob" value="y" ${invoicedchecked} /></td>
					<td colspan="1"><input type="checkbox" name="consoljob" id="consoljob" value="y" ${consolchecked} /></td>
					<td colspan="1">${res[0].date}</td>
					<td colspan="1" class="ptdropdowntd${jobnoId}"></td>
					<td colspan="1">${res[0].job_no}</td>
					<td colspan="1"><input type="text" name="jobname" id="jobname" value="${res[0].jobname}" style="width:90%;"/></td>
					<td colspan="1"><input type="text" name="jobdesc" id="jobdesc" value="${res[0].description}" style="width:95%;"/></td>
					<td colspan="1"><input type="number" name="quote" id="quote" min="1" max="100000" step="0.1" value="${res[0].quoted_amount}" style="width:90%;"/></td>
					<td colspan="1" class="divdropdowntd${jobnoId}"></td>
					<td class="inlineEditBtn"><input type="button" name="update" class="btn updateBtn" value="Update" data-id="${jobnoId}" /> &nbsp;<input type="button" name="cancelrow" class="btn cancelBtn" value="Cancel" data-id="${jobnoId}" /></td>
					
					<td class="updatestatus"></td>
					</tr>`)
					$(".ptdropdowntd"+jobnoId).append(projecttypesdropdown)
					$(".divdropdowntd"+jobnoId).append(divisionsdropdown)

					$(".ptdropdowntd"+jobnoId+" select").css("width",'90%')
					$(".divdropdowntd"+jobnoId+" select").css("width",'90%')

					$(".divdropdowntd"+jobnoId+" .divisiondrop").val(res[0].division)

					projecttypesplit = res[0].projecttype.split('/');					
					ptdropdown = $(".ptdropdowntd"+jobnoId+" .projecttypesdrop");
					ptdropdown.find('option').prop('selected', false);

					projecttypesplit.forEach(function(value) {
						ptdropdown.find('option').each(function() {
							let option = $(this);  // Convert the option to a jQuery element
							if (option.val() === value) {
								option.prop('selected', true);  // Set the option as selected
							}
						});
					});
										//$(".ptdropdowntd"+jobnoId+" .projecttypesdrop").val(res[0].division)
				}
			else 
				{
					//msg = "Error occurred. Disable unsuccessfull";
				}				
		}
		});
	}
  })
  
  $(document).on('click', '.cancelBtn', function() {
	var jobid = $(this).data('id');
	$('tr.jobedit'+jobid).remove();
  });
  $(document).on('click', '.updateBtn', function() {
	var jobid = $(this).data('id'),
	  //  retainer = $('tr.jobedit'+jobid).find('input#retainerjob').prop("checked")?$('tr.jobedit'+jobid).find('input#retainerjob').val():false,
		billable = $('tr.jobedit'+jobid).find('input#billablejob').prop("checked")?$('tr.jobedit'+jobid).find('input#billablejob').val():"n",
		approved = $('tr.jobedit'+jobid).find('input#approvedjob').prop("checked")?$('tr.jobedit'+jobid).find('input#approvedjob').val():"n",
		closed = $('tr.jobedit'+jobid).find('input#closedjob').prop("checked")?$('tr.jobedit'+jobid).find('input#closedjob').val():"n",
		invoiced = $('tr.jobedit'+jobid).find('input#invoicedjob').prop("checked")?$('tr.jobedit'+jobid).find('input#invoicedjob').val():"n",
		consol = $('tr.jobedit'+jobid).find('input#consoljob').prop("checked")?$('tr.jobedit'+jobid).find('input#consoljob').val():"n",
		jobname = $('tr.jobedit'+jobid).find('input#jobname').val(),
		jobdesc = $('tr.jobedit'+jobid).find('input#jobdesc').val(),
		quote = $('tr.jobedit'+jobid).find('input#quote').val(),
		projecttype = $(".ptdropdowntd"+jobid+" .projecttypesdrop").val(),
		division = $(".divdropdowntd"+jobid+" .divisiondrop").val();
		console.log("drop",projecttype)
	var data = {
		"id":jobid,
		//"retainer":retainer,
		"billable":billable,
		"approved":approved,
		"closed":closed,
		"invoiced":invoiced,
		"consol":consol,
		"jobname":jobname,
		"jobdesc":jobdesc,
		"quote":quote,
		"projecttype":projecttype,
		"division":division
	}
	var statusmsg = $('tr.jobedit'+jobid+ ' td.updatestatus');
	jQuery.ajax({
		type: "GET",
		url: "<?php echo base_url(); ?>"+"index.php/edit-job-inline/",
		data: data,			
		dataType: "json",
		success: function(res) {
			if (res)
				{
					//console.log("update",res)
					
					//console.log("stat",'tr.jobedit'+jobid+ ' td.updatestatus')
					if(statusmsg.length > 0 ) statusmsg.empty()
					statusmsg.append('<p class="success">Updated successfully!</p>')								
				}
				else {
					if(statusmsg.length > 0 ) statusmsg.empty()
					statusmsg.append('<p class="fail">Update failed!</p>')	
				}
		},
		error: function(jqXHR, textStatus, errorThrown) {
           // searchbtn.hide()
            console.log("Error:", textStatus, errorThrown);
            // You can also handle different status codes here
            if (jqXHR.status === 404) {
                console.log("404 Not Found");
            } else if (jqXHR.status === 500) {
                console.log("500 Internal Server Error");
            }
           }
	})
	console.log(data)
  });
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
	<thead>
		<tr style="background:#dddddd;">
			<th width="10">&nbsp;</th>
			<th width="30" class="smallfont">Retainer</th>
			<th width="30" class="smallfont">Billable</th>
			<th width="35" class="smallfont">Approved</th>
			<th width="40" class="smallfont">Closed</th>
			<th width="40" class="smallfont">Invoiced</th>
			<th width="30" class="smallfont">Consol. billing</th>
			<th width="50">Date</th>
			<th width="95">Type</th>
			<th width="60">Job No.</th>
			<th width="70">Job Name</th>
			<th width="180">Description</th>
			<th width="70">Amount</th>
			<th width="100">Division</th>
			<th width="80">Client</th>
			<?php if($admin) {?><th width="120">Action</th><?php }?>
		</tr>
	</thead>
 <?php

$i = 1;
$icon_true = '<img src="'.base_url().'assets/img/true.png" width="16" height="16" align="center">';
$icon_false = '<img src="'.base_url().'assets/img/false.png" width="12" height="12" align="center">';
if(isset($jobs)) {foreach($jobs as $key => $rows)
{
    if($jobs[$key]["date"] != NULL) {$dateadded= date('d/M/Y',strtotime($jobs[$key]["date"])); } else {$dateadded = $jobs[$key]["date"];}
    if($jobs[$key]["invoiced"] == "n") {$invoiced = "No";$invoiced_icon = $icon_false;} else  {$invoiced = "Yes";$invoiced_icon = $icon_true;}
	if($jobs[$key]["closed"] == "n") {$closed = "No";$closed_icon = $icon_false;} else  {$closed = "Yes";$closed_icon = $icon_true;}
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
			<td><?php  echo $closed_icon; ?></td>
			<td><?php  echo $invoiced_icon; ?></td>
			<td><?php  echo $under750_icon; ?>
			<?php //echo $consolidate_jobno;?></td>
			<td><?php  echo $dateadded; ?></td>
			<td><?php echo $jobs[$key]["project_type"];?></td>
			<td><?php  echo $jobs[$key]["jobno"]; ?></td>
			<td><?php  echo $jobs[$key]["jobname"]; ?></td>
			<td><?php  echo $jobs[$key]["description"]; ?></td>
			<td><?php  echo $jobs[$key]["quote"]; ?></td>
			<td><?php  echo $jobs[$key]["division"]; ?></td>
			<td><?php echo $jobs[$key]["clientname"];?></td>
			<?php if($admin) {?><td><a data-id="<?php echo $jobs[$key]["id"] ?>" href="#" class="editOnClick">Edit inline</a>&nbsp;&nbsp;<a href="<?php echo site_url('edit-job/'.$jobs[$key]["id"]) ?>">Edit</a>&nbsp;&nbsp;<a onClick="disableJobById(<?php echo $jobs[$key]["id"];?>)" href="<?php echo "#"; ?>">Disable</a>&nbsp;&nbsp;<?php  if($user == "admin"  || $user == "brandon") {?><a onClick="deleteJobById(<?php echo $jobs[$key]["id"];?>)" href="<?php echo "#"; ?>">Delete</a><?php  } ?></td><?php } ?>
	    </tr>
		<!-- <tr class="jobedit" id="jobedit<?php echo $jobs[$key]["id"]; ?>">
  			<td></td>
			<td><input type="checkbox" name="retainerjob" id="retainerjob" value="y" <?php  if($jobs[$key]["retainer_c_job"]  == "y") echo "checked";?>></td>
			<td><input type="checkbox" name="billablejob" id="billablejob" value="y" <?php  if($jobs[$key]["ekbillable"]  == "y") echo "checked";?>></td>
			<td><input type="checkbox" name="approvedjob" id="approvedjob" value="y" <?php  if($jobs[$key]["approved"]  == "y") echo "checked";?>></td>
			<td><input type="checkbox" name="invoicedjob" id="invoicedjob" value="y" <?php  if($jobs[$key]["invoiced"]  == "y") echo "checked";?>></td>
			<td><input type="checkbox" name="consoljob" id="consoljob" value="y" <?php  if($jobs[$key]["consolidated_check"] == "y") echo "checked";?>></td>
			<td></td>
			<td>
			<select style="width:95% !important;" name="project_type[]" id="project_type" multiple="multiple">
			<?php 
			$projecttypes = explode("/",$jobs[$key]["project_type_ids"]);
			print_r($projecttypes);
			foreach($project_types as $key => $row):?>
				<option value="<?php echo $row['id'];?>" <?php if(in_array($row['id'], $projecttypes)) echo "selected";?>> <?php echo $row['project_type'];?></option>	   
			<?php endforeach;  ?>	
			</select>			
			</td>
			<td>
			<?php echo $jobs[$key]["jobno"]; ?>
			</td>
			<td><input style="width:95% !important;" type="text" name="jobname" id="jobname" value="<?php echo $jobs[$key]["jobname"]; ?>"/></td>
			<td><input style="width:95% !important;" type="text" name="jobdesc" id="jobdesc" value="<?php echo $jobs[$key]["description"]; ?>"/></td>
			<td><input style="width:95% !important;" type="number" name="quote" id="quote" min="1" max="100000" step="0.1" value="<?php echo $jobs[$key]["quote"]; ?>"/></td>
		</tr> -->
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