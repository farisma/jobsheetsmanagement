<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<div class="hero-unit">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
   <script>
  $(document).ready(function(){
      $("input[name='datestart']").datepicker({dateFormat: "yy-mm-dd"});
	  $("input[name='dateend']").datepicker({dateFormat: "yy-mm-dd"});
	  //$("select[name='client']").append("<option value='0'>No selection</option>");
	  
	  $("select[name='userid']").append("<option value='0' selected='selected'>No user</option>");
	  $("select[name='client']").prepend("<option value='0' selected='selected'>Choose Client</option>");
	  $("select[name='jobno']").prepend("<option value='0' selected='selected'>Choose Job no.</option>");
	  
  });
  </script>
<script type="text/javascript">
function getJobcodes(value,name) {
         var clientid = value;
		  jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetAllJobcodes/"+name,
		data: $('#searchjobsheetform').serialize(),
		dataType: "json",
		success: function(res) {
		if (res)
		{
			
		         var jobCodeSelector = $("select[name='"+name+"']").parent().closest('td').next('td').find('select');  
                  jobCodeSelector.empty();
				  jQuery.each(res, function( index, item ) {
					
				  jobCodeSelector.append($("<option></option>").attr("value",item.id).text(item.job_no+' ('+item.jobname+')'));
				});
		jobCodeSelector.prepend("<option value='' selected='selected'>Choose Job no.</option>");
		}
		else 
		{

		          var jobCodeSelector = $("select[name='"+name+"']").parent().closest('td').next('td').find('select');  
                  jobCodeSelector.empty();
		}
		},
		error: function(jqXHR, status, errorThrown) {
			console.log("HTTP Status Code: " + jqXHR.status);
			console.log("Error: " + errorThrown);
		}
		});
 }
</script>
<?php echo form_open('jobsheets/search', array('class' => 'searchjobsheet','id'=>'searchjobsheetform')) ?>
	 <table class="searchjobsheet" id="searchjobsheet">
	    <tr>
		<th colspan="6" align="center"><h2>Job Management</h2></th>
		</tr>
	
         <tr>
		  <td>
		    Choose client<?php   $js = 'onchange="getJobcodes(this.value,this.name);"';  
			               echo form_dropdown('client', $clients,0,$js); ?>
			<?php echo form_error('client'); ?>   
			</td>
		   <td>
			Jobcode<?php echo  form_dropdown('jobno'); ?>
			<?php echo form_error('jobno'); ?>   
			</td>
		  </tr> 
		  <tr>
		   <td> 
		   From<?php echo form_input($datestart); ?>
			<?php echo form_error('datestart'); ?>  
		   </td>
		   <td>
		   To <?php echo form_input($dateend); ?>
			<?php echo form_error('dateend'); ?>  
		    </td>
			</tr>
			<tr>			
		   <td>
		   User  <?php echo form_dropdown('userid',$users,0); ?>
			<?php echo form_error('userid'); ?> 
		   </td>
		   </tr>
		   
		   
		</tr>
		 
		  
  
		<tr>
		<td colspan="2"><input type="submit" id="submitsearch" name="submitsearch" value="Search" class="btn" ></td>
		</tr>
	 </table>
	</form>
	
	<?php   if(isset($searchresults)) {?>
	<?php  echo form_open('jobsheets/exportExcelsheet', array('class' => 'searchresultslistform','id'=>'searchresultslistform')); ?>
	<?php 
	if(isset($queryitems))
	{
	   foreach($queryitems as $key=>$value) {
	             if($key == "client")
			   {
				$client = $value;
			   }
		 if($key == "jobcode")
			   {
				$jobcode = $value;
			   }
		   if($key == "startdate")
			   {
				$startdate = $value;
			   }
		   
		   if($key == "enddate")
			   {
				 $enddate = $value;
			   }
		    if($key == "user")
			   {
				 $user = $value;
			   }
		}
	   ?>
	   <input type="hidden" name="client" value="<?php  if(isset($client)) echo $client; else echo 0;?>">
	   <input type="hidden" name="jobcode" value="<?php  if(isset($jobcode)) echo  $jobcode;?>">
	   <input type="hidden" name="startdate" value="<?php  if(isset($startdate))  echo $startdate;?>">
	   <input type="hidden" name="enddate" value="<?php  if(isset($enddate)) echo  $enddate;?>">
	   <input type="hidden" name="userid" value="<?php if(isset($user))  echo  $user; else echo 0;?>">
	      <?php 
	
	}
	?>
	<table class="searchresultslist">
	 <tr><th colspan="6">Search results...</th></tr>
	    <tr>
<th>Sl no.</th><th colspan="1">Company</th><th colspan="1">Job code</th><th colspan="1">Date</th><th colspan="1">Hours</th><th colspan="1">Job description</th><th>User</th>
</tr>
<?php $i=1;
foreach($searchresults as $row) {
?>
<tr><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["job_no"];?></td><td colspan="1"><?php  $dateadded = $row["date"]; echo date('d/M/Y',strtotime($dateadded));?></td><td colspan="1"><?php echo $row["hoursspent"];?></td><td colspan="1"><?php echo $row["description"];?></td><td><?php echo $row["username"];?></td></tr>
<?php  $i++;}?>
<?php if(isset($isadmin) && $isadmin === true) {?><tr> <td colspan="7"><input type="submit" name="exportexcel" class="btn" value="Export as Excel"></td></tr><?php } ?>
	</table>

	<?php }
	else {
	
	echo "Cannot find results for your search. Please try again...";
	
	}
	
	?>
</div>
