<script src="<?php echo base_url(); ?>assets/js/dateformat/js-date-format.js"></script>
<script type="text/javascript">
/*
Javacript function to fetch jobcodes with respect to particular client.
Author: Faris M A
Called on onchange event of clients drop down list

*/
function getJobcodes(value,name) {
         var clientid = value;
         var cnt = 0;
		  jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetJobcodes/"+name,
		data: $('#listjobsheetsform').serialize(),
		dataType: "json",
		success: function(res) {
		if (res)
		{
		         var jobCodeSelector = $("select[name='"+name+"']").parent().closest('td').next('td').find('select');  /* find the select field from next td which contains the dropdown list for job codes*/
                  jobCodeSelector.empty();
				 console.log(res.length);
				  jQuery.each(res, function( key, value ) {
				  jobCodeSelector.prepend($("<option></option>").attr("value",key).text(value)); /* Append values from the controller to the select dropdown list*/
				  cnt++;
				});
		jobCodeSelector.prepend("<option value='' selected='selected'>Choose Job No.</option>");
		//this is to decrease the height of a select dropdown if there are lot of job numbers for a client
		
		}
		else 
		{
		          var jobCodeSelector = $("select[name='"+name+"']").parent().closest('td').next('td').find('select');  
                  jobCodeSelector.empty();
		}
		}
		});
 }
 /*
Javacript function to add new jobsheet item to databse with respect to date and day.
Author: Faris M A
Called on clicking submit button

*/
 function submitJobsheet(day)
  {
  jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "index.php/submitJobSheetsbyDate/"+day,
		data: $('#listjobsheetsform').serialize(),
		dataType: "json",
		success: function(res) {
		if (res)
		{		
		var i=1;
		var totalhrs=0;
		var error = 0;/* Flag to indicate error */
		       jQuery.each(res, function( key, value ) {
				 if(key == "error") { 
                 error = 1; 
                 
                 $("#alert").html(value).addClass("alert");
                 
                 }
				 
				 else {
                   /*var d = value.date;
                   var myDate = new Date();
                 var d2 = myDate.format("M jS, Y");
                 alert(d2) */
                 var dateadded = new Date(value.date);
                   var dateadded_formatted = dateadded.format("DD/MMM/YYYY");
				   $('table tr.'+day).remove();/* Every tr for particular day is given class name as day name. Remove all row with that class in order to add all the data again with the data from json response.*/
				   $('table tr.totalhrs').remove(); /* To remove previously added total hrs row. otherwise the old total hours will stay there as the page is not reloading becoz ajax is used for adding rows. */
                   /* As each time class is removed in the loop(above code), in the below code class is given a temporary name which will be exchanged later after the loop */
				   var newrows = '<tr class="'+day+'temp"><td colspan="1">'+i+'</td><td colspan="1">'+value.clientname+'</td><td colspan="1">'+value.job_no+'</td><td colspan="1">'+dateadded_formatted+'</td><td colspan="1">'+value.hoursspent+'</td><td colspan="1">'+value.description+'</td><td> <a onclick="deletejobsheet('+value.id+')">Delete</a></td></tr>';
				   $('input[name="'+day+'"]').parents('tr').before(newrows);/* Add new rows of data before the row that contains the button to add and submit*/
			       i = Number(i) + Number(1);
				   totalhrs = Number(totalhrs) + Number(value.hoursspent); /* Calculating total hours of job done in a day */
				   }
				});
				/* If no error and row successfully added */
				if(error == 0) {
				var totalhrsrow = '<tr class="totalhrs"><td colspan="1">Total hours</td><td colspan="6" id="'+day+'_totalhrs">'+totalhrs+'hrs</td></tr>';
				$('input[name="'+day+'"]').parents('tr').before(totalhrsrow);
				/* Once new rows are added, the row count is reset in order to continue from 0 next time new row added in the list. */
				$('input[name="'+day+'rowcount"]').val(0);
				 $("input#submitjobsheet"+day).css("display","none");  /* Submit button will be hidden if new row is added.*/
				 $("input#deletenewrow"+day).css("display","none");  /* Delete row button will be hidden if new row is added*/
				}
				
				/* change the temporary class to the original class name */
				$("table tr."+day+"temp").toggleClass(day,true);
				$("table tr."+day+"temp").toggleClass(day+"temp",false);
				
		}
		else 
		{
		      alert("Error.");
		}
		}
		}); 
  }
  
</script>
<div id="alert"></div>
<?php echo form_open('', array('class' => 'listjobsheets','id'=>'listjobsheetsform')) ?>
<table class="listjobsheets">

<tr><th colspan="7" class="currentpage"><a href="<?php echo site_url("previousWeekJobsheets"); ?>" <?php  if(isset($previousweek)) {if($previousweek == 1) {$class ="class='selected'"; echo $class;}  }?>>Previous week</a>&nbsp;&nbsp;&nbsp;<span style="font-weight:bold; color:#08c;">|</span>&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url("jobsheets"); ?>" <?php  if(isset($currentweek)) {if($currentweek == 1) {$class ="class='selected'"; echo $class;} } ?>>Current week</a></th></tr>
<tr><th colspan="7"><h2>Timesheets<h2></th></tr>

<tr>
<th colspan="7"><h4>Timesheets for week <?php $year = date('Y'); $weekno = date('W',strtotime($datesunday));  echo " ".$weekno.", ".$year; ?><h4></th>
</tr>
<tr>
<th>Sl no.</th><th colspan="1">Company</th><th colspan="1">Job No.</th><th colspan="1">Date</th><th colspan="1">Hours</th><th colspan="1">Job description</th><th colspan="1">Action</th>
</tr>
<?php 
/*  Change format to display in views */
$datesunday_formatted= date('d/M/Y',strtotime($datesunday));
$datemonday_formatted = date('d/M/Y',strtotime($datemonday));
$datetuesday_formatted = date('d/M/Y',strtotime($datetuesday));
$datewednesday_formatted = date('d/M/Y',strtotime($datewednesday));
$datethursday_formatted = date('d/M/Y',strtotime($datethursday));
$datefriday_formatted = date('d/M/Y',strtotime($datefriday));
$datesaturday_formatted = date('d/M/Y',strtotime($datesaturday));?>
<!-- Date to be fetched in the controller function to use for Database operations -->
<input type="hidden" name="sunday_date" value="<?php  echo $datesunday;?>">
<input type="hidden" name="monday_date" value="<?php  echo $datemonday;?>">
<input type="hidden" name="tuesday_date" value="<?php  echo $datetuesday;?>">
<input type="hidden" name="wednesday_date" value="<?php  echo $datewednesday;?>">
<input type="hidden" name="thursday_date" value="<?php  echo $datethursday;?>">
<input type="hidden" name="friday_date" value="<?php  echo $datefriday;?>">
<input type="hidden" name="saturday_date" value="<?php  echo $datesaturday;?>">




<?php
echo  '<tr><td colspan="7" class="date">'.$datemonday_formatted ." ".$daymonday.'</td></tr>';
if($jobsheets_monday) {$i = 1; $totalhrs_monday = 0;foreach($jobsheets_monday as $row)
{ $jobsheetid = $row["id"]; $totalhrs_monday += $row["hoursspent"];
?><tr class="monday"><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["job_no"];?></td><td colspan="1"><?php $dateadded2 =  $row["date"];  echo date('d/M/Y',strtotime($dateadded2));?></td><td colspan="1"><?php echo $row["hoursspent"];?></td><td colspan="1"><?php echo $row["description"];?></td><td colspan="1">&nbsp;&nbsp;<a href="#" onclick="deletejobsheet(<?php echo $jobsheetid;?>)">Delete</a></td></tr>
<?php 
$i++;} }?>
<tr class="monday"> <td>Total Hours:</td><td colspan="7" id="monday_totalhrs"><?php   if(isset($totalhrs_monday)) echo $totalhrs_monday."hrs"; else echo "0";?></td></tr>
<tr><td colspan="7" class="buttons">
<input name="monday" type="button" id="addnewrowmonday" class="addnewrow btn" value="Add Job"><input type="button" id="deletenewrowmonday" class="deletenewrowmonday btn" value="Delete row"><input type="hidden" name="mondayrowcount" value="0">
<input type="button" name="submitjobsheetmonday" id="submitjobsheetmonday" class="btn" value="Submit Job">
</td></tr>
<?php
echo  '<tr><td colspan="7" class="date">'.$datetuesday_formatted ." ".$daytuesday.'</td></tr>';

if($jobsheets_tuesday) {$i=1;$totalhrs_tuesday = 0; foreach($jobsheets_tuesday as $row)
{ $jobsheetid = $row["id"];$totalhrs_tuesday += $row["hoursspent"];
?><tr class="tuesday"><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["job_no"];?></td><td colspan="1"><?php $dateadded3 =  $row["date"];  echo date('d/M/Y',strtotime($dateadded3));?></td><td colspan="1"><?php echo $row["hoursspent"];?></td><td colspan="1"><?php echo $row["description"];?></td><td colspan="1">&nbsp;&nbsp;<a href="#" onclick="deletejobsheet(<?php echo $jobsheetid;?>)">Delete</a></td></tr>
<?php 
$i++;} }?>
<tr class="tuesday"> <td>Total Hours:</td><td colspan="7" id="tuesday_totalhrs"><?php if(isset($totalhrs_tuesday)) echo $totalhrs_tuesday."hrs"; else echo "0";?></td></tr>
<tr><td colspan="7" class="buttons">
<input name="tuesday" type="button" id="addnewrowtuesday" class="addnewrow btn" value="Add Job"><input type="button" id="deletenewrowtuesday" class="deletenewrowtuesday btn" value="Delete row"><input type="hidden" name="tuesdayrowcount" value="0">
<input type="button" name="submitjobsheettuesday" id="submitjobsheettuesday" class="btn" value="Submit Job">
</td></tr>
<?php 
echo  '<tr><td colspan="7" class="date">'.$datewednesday_formatted ." ".$daywednesday.'</td></tr>';
if($jobsheets_wednesday) {$i=1; $totalhrs_wednesday = 0;foreach($jobsheets_wednesday as $row)
{ $jobsheetid = $row["id"]; $totalhrs_wednesday += $row["hoursspent"];
?><tr class="wednesday"><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["job_no"];?></td><td colspan="1"><?php $dateadded4 =  $row["date"];  echo date('d/M/Y',strtotime($dateadded4));?></td><td colspan="1"><?php echo $row["hoursspent"];?></td><td colspan="1"><?php echo $row["description"];?></td><td colspan="1">&nbsp;&nbsp;<a href="#" onclick="deletejobsheet(<?php echo $jobsheetid;?>)">Delete</a></td></tr>
<?php 
$i++;} }?>
<tr class="wednesday"><td>Total Hours:</td> <td colspan="7" id="wednesday_totalhrs"><?php if(isset($totalhrs_wednesday)) echo $totalhrs_wednesday."hrs"; else echo "0";?></td></tr>
<tr><td colspan="7" class="buttons">
<input name="wednesday" type="button" id="addnewrowwednesday" class="addnewrow btn" value="Add Job"><input type="button" id="deletenewrowwednesday" class="deletenewrowwednesday btn" value="Delete row"><input type="hidden" name="wednesdayrowcount" value="0">
<input type="button" name="submitjobsheetwednesday" id="submitjobsheetwednesday" class="btn" value="Submit Job">
</td></tr>
<?php 
echo  '<tr><td colspan="7" class="date">'.$datethursday_formatted ." ".$daythursday.'</td></tr>';
if($jobsheets_thursday) {$i=1;$totalhrs_thursday = 0;foreach($jobsheets_thursday as $row)
{ $jobsheetid = $row["id"]; $totalhrs_thursday += $row["hoursspent"];
?><tr class="thursday"><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["job_no"];?></td><td colspan="1"><?php $dateadded5 =  $row["date"];  echo date('d/M/Y',strtotime($dateadded5));?></td><td colspan="1"><?php echo $row["hoursspent"];?></td><td colspan="1"><?php echo $row["description"];?></td><td colspan="1">&nbsp;&nbsp;<a href="#" onclick="deletejobsheet(<?php echo $jobsheetid;?>)">Delete</a></td></tr>
<?php 
$i++;}
}?>
<tr class="thursday"> <td>Total Hours:</td><td colspan="7" id="thursday_totalhrs"><?php  if(isset($totalhrs_thursday))  echo $totalhrs_thursday."hrs"; else  echo "0";?></td></tr>
<tr><td colspan="7" class="buttons">
<input name="thursday" type="button" id="addnewrowthursday" class="addnewrow btn" value="Add Job"><input type="button" id="deletenewrowthursday" class="deletenewrowthursday btn" value="Delete row"><input type="hidden" name="thursdayrowcount" value="0">
<input type="button" name="submitjobsheetthursday" id="submitjobsheetthursday" class="btn" value="Submit Job">
</td></tr>
<?php 
echo  '<tr><td colspan="7" class="date">'.$datefriday_formatted ." ".$dayfriday.'</td></tr>';
if($jobsheets_friday) {$i=1;$totalhrs_friday = 0; foreach($jobsheets_friday as $row)
{ $jobsheetid = $row["id"];$totalhrs_friday += $row["hoursspent"];
?><tr class="friday"><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["job_no"];?></td><td colspan="1"><?php $dateadded6 =  $row["date"];  echo date('d/M/Y',strtotime($dateadded6));?></td><td colspan="1"><?php echo $row["hoursspent"];?></td><td colspan="1"><?php echo $row["description"];?></td><td colspan="1">&nbsp;&nbsp;<a href="#" onclick="deletejobsheet(<?php echo $jobsheetid;?>)">Delete</a></td></tr>
<?php 
$i++;} }?>
<tr class="friday"> <td>Total Hours:</td><td colspan="7" id="friday_totalhrs"><?php  if(isset($totalhrs_friday)) echo $totalhrs_friday."hrs"; else echo "0";?></td></tr>
<tr><td colspan="7" class="buttons">
<input name="friday" type="button" id="addnewrowfriday" class="addnewrow btn" value="Add Job"><input type="button" id="deletenewrowfriday" class="deletenewrowfriday btn" value="Delete row"><input type="hidden" name="fridayrowcount" value="0">
<input type="button" name="submitjobsheetfriday" id="submitjobsheetfriday" class="btn" value="Submit Job">
</td></tr>
<?php 
echo  '<tr><td colspan="7" class="date">'.$datesaturday_formatted ." ".$daysaturday.'</td></tr>';
if($jobsheets_saturday) { $i=1; $totalhrs_saturday = 0; foreach($jobsheets_saturday as $row)
{ $jobsheetid = $row["id"];$totalhrs_saturday += $row["hoursspent"];
?><tr class="saturday"><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["job_no"];?></td><td colspan="1"><?php $dateadded7 =  $row["date"];  echo date('d/M/Y',strtotime($dateadded7));?></td><td colspan="1"><?php echo $row["hoursspent"];?></td><td colspan="1"><?php echo $row["description"];?></td><td colspan="1">&nbsp;&nbsp;<a href="#" onclick="deletejobsheet(<?php echo $jobsheetid;?>)">Delete</a></td></tr>
<?php 
$i++;} }
?>
<tr class="saturday"> <td>Total Hours:</td><td colspan="7" id="saturday_totalhrs"><?php if(isset($totalhrs_saturday))echo $totalhrs_saturday."hrs"; else echo "0";?></td></tr>
<tr><td colspan="7" class="buttons">
<input name="saturday" type="button" id="addnewrowsaturday"  class="addnewrow btn" value="Add Job"><input type="button" id="deletenewrowsaturday" class="deletenewrowsaturday btn" value="Delete row"><input type="hidden" name="saturdayrowcount" value="0">
<input type="button" name="submitjobsheetsaturday" id="submitjobsheetsaturday" class="btn" value="Submit Job">
</td></tr>
<?php echo  '<tr><td colspan="7" class="date">'.$datesunday_formatted ." ".$daysunday.'</td></tr>';
if($jobsheets_sunday) {$i = 1;$totalhrs_sunday = 0;foreach($jobsheets_sunday as $row)
{ $jobsheetid = $row["id"];  $totalhrs_sunday += $row["hoursspent"];
?>

<tr class="sunday"><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["job_no"];?></td><td colspan="1"><?php $dateadded1 =  $row["date"];  echo date('d/M/Y',strtotime($dateadded1));?></td><td colspan="1"><?php echo $row["hoursspent"];?></td><td colspan="1"><?php echo $row["description"];?></td><td colspan="1">&nbsp;&nbsp;<a href="#" onclick="deletejobsheet(<?php echo $jobsheetid;?>)">Delete</a></td></tr>
<?php 
$i++;} }
?>
<tr class="sunday"> <td>Total Hours:</td><td colspan="6" id="sunday_totalhrs"><?php if(isset($totalhrs_sunday)) echo $totalhrs_sunday."hrs"; else echo "0";?></td></tr>

<tr><td colspan="7" class="buttons">
<input name="sunday" type="button" id="addnewrowsunday" class="addnewrow btn" value="Add Job"><input type="button" id="deletenewrowsunday" class="deletenewrowsunday btn" value="Delete row"><input type="hidden" name="sundayrowcount" value="0">
<input type="button" name="submitjobsheetsunday" id="submitjobsheetsunday" class="btn" value="Submit Job">
</td></tr>
</table>
<script type="text/javascript">
 /*
Javacript function to add new row to the jobsheet list.
Author: Faris M A
Called on clicking Add new row button.

*/
function addrow(day){
  var datarowcount = $('input[name="'+day+'rowcount"]').val();/* Get the rowcount with respect to the day/date*/
  var newdatarowcount = Number(datarowcount) + Number(1);/* Increment for new addition of rows*/
  <?php  
  /*Function getJobcodes() to be called on change of client list. HTML name attribute is also passed in order to find the next td select in the getjobcodes()*/
 $js = 'onchange="getJobcodes(this.value,this.name);"';

 /* In script next line elements will not b taken. So trimming all unnecessary spaces.*/
 
 $clientsdropdown =   form_dropdown("client2", $clients,1,$js); $trimmedclientsarray = trim(preg_replace('/\s+/', ' ', $clientsdropdown));  
$jobcodedropdown =   form_dropdown("jobno2"); $trimmedjobcodessarray = trim(preg_replace('/\s+/', ' ', $jobcodedropdown)); 
//echo $jobcodedropdown;


 $date2  = Array(
 'name' => "date2",
 'size'=> 12,
 'placeholder' => 'Date',
 'class' => 'input-block-level date'
 );
  $timespent2  = Array(
 'name' => "timespent2",
 'size'=> 4,
 'placeholder' => 'Hours',
 'class' => 'input-block-level'
 );
 $description2 = Array(
  'name' => "description2",
 'size'=> 25,
 'placeholder' => 'Job description',
 'class' => 'input-block-level',
 );
 ?>
// console.log(<?php echo $trimmedjobcodessarray;?>);
 /* Add new row just before the parent tr of the tr which contains the add new row button */
 $('input[name="'+day+'rowcount"]').parents('tr').before('<tr class="'+day+' newrow"><td style="vertical-align:top" colspan="1"></td><td style="vertical-align:top" ><?php echo $trimmedclientsarray;   ?></td><td style="vertical-align:top" colspan="1"><?php echo $trimmedjobcodessarray;?></td><td style="vertical-align:top" colspan="1"> <?php echo form_input($timespent2);  ?></td><td colspan="2" style="vertical-align:top"> <?php echo form_input($description2);   ?></td><td></td>'); 
 /* A temporary class name(like client2,jobno2 etc) is assigned using the above php code to the newly added input fields in the row. New name is assigned with rowcount iteration */
 $('input[name="'+day+'rowcount"]').parents('tr').prev('tr').find('td select[name="client2"]').attr('name',day+'client'+newdatarowcount);
 $('input[name="'+day+'rowcount"]').parents('tr').prev('tr').find('td select[name="jobno2"]').attr('name',day+'jobno'+newdatarowcount);
 $('input[name="'+day+'rowcount"]').parents('tr').prev('tr').find('td input[name="timespent2"]').attr('name',day+'timespent'+newdatarowcount);
 $('input[name="'+day+'rowcount"]').parents('tr').prev('tr').find('td input[name="description2"]').attr('name',day+'description'+newdatarowcount);
 $('input[name="'+day+'rowcount"]').val(newdatarowcount); /* Increment the row count */
 $("input#submitjobsheet"+day).css("display","inline-block");  /* Submit button will be visible only when add new row button is clicked. Its hidden bby default.*/
 $("input#deletenewrow"+day).css("display","inline-block");  /* Delete row button will be visible only when add new row button for that day is clicked. Its hidden by default.*/
/* Assigning first value of select dropdown to be choose item  */

				
 $("select[name='"+day+"client"+newdatarowcount+"']").prepend("<option value='' selected='selected'>Choose Client</option>");
$("select[name='"+day+"jobno"+newdatarowcount+"']").prepend("<option value='' selected='selected'>Choose Job no.</option>");
/*$("select[name='"+day+"jobno"+newdatarowcount+"']").on('focus',function(){
	this.size=10;
});
$("select[name='"+day+"jobno"+newdatarowcount+"']").on('blur',function(){
	this.size=1;
});
$("select[name='"+day+"jobno"+newdatarowcount+"']").on('change',function(){
	this.size=1;
});*/
 }

 /*
Javacript function to delete newly added row to insert new jobsheet item.
Author: Faris M A
Called on clicking Delete row button.

*/
function deleterow(day) {
 var rowCount = $('input[name="'+day+'rowcount"]').val();/* Getting the current row count of particular day in order to decrement the counter*/
 if(rowCount != 0 )
 { var newRowCount = rowCount - 1; }
 else if(rowCount == 0)
 {
     var newRowCount = 0;
 }
 $('input[name="'+day+'rowcount"]').parents('tr').prev('tr.newrow').remove();
 $('input[name="'+day+'rowcount"]').val(newRowCount); /* Set new row count */
}
$(document).ready(function(){
/* Add new row button will pass the day name which will be used as a reference name for many operations*/
  $("#addnewrowsunday").click(function(){
      addrow("sunday");
  });
  $("#addnewrowmonday").click(function(){
  addrow("monday");
  });
  $("#addnewrowtuesday").click(function(){
  addrow("tuesday");
  });
  $("#addnewrowwednesday").click(function(){
  addrow("wednesday");
  });
  $("#addnewrowthursday").click(function(){
  addrow("thursday");
  });
  $("#addnewrowfriday").click(function(){
  addrow("friday");
  });
  
  $("#addnewrowsaturday").click(function(){
  addrow("saturday");
  });
  
  /* Delete row button will pass the day name to remove rows added to submit new jobsheet */
  $("#deletenewrowsunday").click(function(){
      deleterow("sunday");
  });
  $("#deletenewrowmonday").click(function(){
  deleterow("monday");
  });
  $("#deletenewrowtuesday").click(function(){
  deleterow("tuesday");
  });
  $("#deletenewrowwednesday").click(function(){
  deleterow("wednesday");
  });
  $("#deletenewrowthursday").click(function(){
  deleterow("thursday");
  });
  $("#deletenewrowfriday").click(function(){
  deleterow("friday");
  });
  
  $("#deletenewrowsaturday").click(function(){
  deleterow("saturday");
  });
  
  /* submit jobsheet button will pass the day name which will be used as a reference name for many operations in controller and the Ajax function*/
   $("#submitjobsheetsaturday").click(function(){
  submitJobsheet("saturday");
  });
  $("#submitjobsheetsunday").click(function(){
  submitJobsheet("sunday");
  });
  $("#submitjobsheetmonday").click(function(){
  submitJobsheet("monday");
  });
  $("#submitjobsheettuesday").click(function(){
  submitJobsheet("tuesday");
  });
  $("#submitjobsheetwednesday").click(function(){
  submitJobsheet("wednesday");
  });
  $("#submitjobsheetthursday").click(function(){
  submitJobsheet("thursday");
  });
  $("#submitjobsheetfriday").click(function(){
  submitJobsheet("friday");
  });
  
  
});

/*
Javacript function to delete a jobsheet item to databse with respect to id.
Author: Faris M A
Called on clicking delete button

*/
 function deletejobsheet(id){
 var message;
jQuery.ajax({
		type: "GET",
		url: "<?php echo base_url(); ?>" + "index.php/delete-jobsheet/"+id,
		data: {"id":id},        
		dataType: "json",
		success: function(res) {
		if (res)
		{
		        jQuery.each(res, function( key, value ) {
				 
				 if(value == "0") /* if successfully deleted. */
				 { 
				 
				 //$("div.alert").html("Successfully deleted the jobsheet item.");
                 message = "Successfully deleted the jobsheet item.";
                 $("#alert").html(message).addClass("alert");
                
				 }
				 else if(value == "1")
				 {
				  message = "Database error occured. Delete unsuccessfull.";
                  $("#alert").html(message).addClass("alert");
				 }
                 
				 location.reload();
				});		       	
		}
		else 
		{
		         var message = "Delete unsuccessfull.";
                  $("#alert").html(message).addClass("alert");
		         
		}
		},
        cache : false
		});
        
 }
</script>



