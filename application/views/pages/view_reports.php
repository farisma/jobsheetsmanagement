<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
  <script>
  $(document).ready(function(){
    //   $("input[name='holidaydate']").datepicker({dateFormat: "yy-mm-dd"});
	 dateselector =  $("#reportdate");
     const monthNames = ["January", "February", "March", "April", "May", "June", 
                                "July", "August", "September", "October", "November", "December"];
            
            // Get current date
            const currentDate = new Date();
            
            // Populate dropdown with previous 12 months
            for (let i = 0; i <= 11; i++) {
                // Calculate month index (0 = January, 11 = December)
                const monthIndex = (currentDate.getMonth() - i + 12) % 12;
                // Calculate the year
                const year = currentDate.getFullYear() - (i >= currentDate.getMonth() ? 1 : 0);
                // Get the month name and year
                const monthName = monthNames[monthIndex];
                // Add option to dropdown
                dateselector.append(`<option value="${monthIndex + 1} ${year}">${monthName} ${year}</option>`);
            }
	  });

  </script>
<script type="text/javascript">
function validate()
{
// var hd,holidayname;

// hd =  document.getElementById("holidaydate").value;
// holidayname =  document.getElementById("holidayname").value;

//     if(hd == "" || holidayname == "")
//       {
               
// 		if(holidayname == "") {
//           document.getElementById("hdname_validate").innerHTML = "Please fill in the holiday name "; 
//         }
// 		else if(hd == "") {
//           document.getElementById("hd_validate").innerHTML = "Please fill in the holiday date field "; 
//         }
		
//       }
//       else {
//          document.getElementById("form1").submit(); 
//       }
}
</script>

<?php $this->load->view("pages/adminmenu");?>
<?php 

?>
<div class="hero-unit">

<?php echo form_open('findreport', array('class' => 'form-find-report','id' => 'form1')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Reports</h2></th></tr>
	    <tr>
		   <td>Client:</td>
           <td><select name="clientname">
                <option value="18">Mubadala</option>
            </select>
		  </td>
		</tr>
        <tr>
        <td>Date:</td>
         <td>
          <select id="reportdate" name="reportdate"></select>
         </td>
        </tr>
		 
		 
		
         <tr>
        <td><input class="btn" type="submit" value="Get report" onclick="Javascript: return validate()"></td>
         
        </tr>
	 </table>
	</form>
<?php // if(isset($jobs['nonretainer'])) echo '<pre>'; print_r($jobs['nonretainer']); echo '</pre>';?>
<?php // if(isset($jobs['retainer'])) echo '<pre>'; print_r($jobs['retainer']); echo '</pre>';?>
</div>
<?php   if(isset($jobs)) {
    if(isset($jobsearchparams)) {
      $clientid = $jobsearchparams["clientid"];
      $month = $jobsearchparams["month"];
      $year = $jobsearchparams["year"];
    }
  ?>

   
	<?php  echo form_open('exportmonthlyreport', array('class' => 'searchresultslistform','id'=>'searchresultslistform')); ?>
  <input type="hidden" name="hiddenclientname" value="<?php  if(isset($clientid)) echo $clientid; else echo 0;?>">
   <input type="hidden" name="hiddenmonth" value="<?php  if(isset($month)) echo  $month;?>">
   <input type="hidden" name="hiddenyear" value="<?php  if(isset($year)) echo  $year;?>">
	<?php 
	if(isset($jobs['retainerconsol']))
	{
    $retainer = $jobs['retainerconsol'];
    ?>
    <h2>Retainer Jobs</h2>
      <table class="searchresultslist">
      
            <tr>
      <th>Sl no.</th><th colspan="1">Job</th><th colspan="1">Division</th><th colspan="1">Job Name</th><th colspan="1">Description</th><th colspan="1">Date</th><th colspan="1">Amount</th><th>Status</th>
      </tr>
      <?php $i=1;
      foreach($retainer as $row) {
      ?>
      
      <tr>
      <td colspan="1"><?php echo $i;?></td>
      <td colspan="1"><?php echo $row["jobno"];?></td>
      <td colspan="1"><?php echo $row["division"];?></td>
      <td colspan="1"><?php echo $row["jobname"];?></td>
      
      <td colspan="1"><?php echo $row["description"];?></td>
      <td colspan="1"><?php $dateadded = $row["date"]; echo date('d/M/Y',strtotime($dateadded));?></td>
      <td colspan="1"><?php echo $row["quote"];?></td>
      <td><?php echo ($row["jobclosed"] == "y")?"Closed":'';?></td>
    </tr>
      <?php  $i++;}?>
<!-- <?php //if(isset($isadmin) && $isadmin === true) {?><tr> <td colspan="7"><input type="submit" name="exportexcel" class="btn" value="Export as Excel"></td></tr><?php //} ?> -->
	</table>

	<?php }
if(isset($jobs['nonretainer']))
{
  $nonretainer = $jobs['nonretainer'];
  ?>
  <h2>Non Retainer Jobs</h2>
    <table class="searchresultslist">
    
    <tr>
    <th>Sl no.</th><th colspan="1">Job</th><th colspan="1">Division</th><th colspan="1">Job Name</th><th colspan="1">Description</th><th colspan="1">Date</th><th colspan="1">Amount</th><th>Status</th>
    </tr>
    <?php $i=1;
    foreach($nonretainer as $row) {
    ?>
      <tr>
      <td colspan="1"><?php echo $i;?></td>
      <td colspan="1"><?php echo $row["jobno"];?></td>
      <td colspan="1"><?php echo $row["division"];?></td>
      <td colspan="1"><?php echo $row["jobname"];?></td>
      
      <td colspan="1"><?php echo $row["description"];?></td>
      <td colspan="1"><?php $dateadded = $row["date"]; echo date('d/M/Y',strtotime($dateadded));?></td>
      <td colspan="1"><?php echo $row["quote"];?></td>
      <td><?php echo ($row["jobclosed"] == "y")?"Closed":'';?></td>
    </tr>
      <?php  $i++;}?>
<!-- <?php //if(isset($isadmin) && $isadmin === true) {?><tr> <td colspan="7"><input type="submit" name="exportexcel" class="btn" value="Export as Excel"></td></tr><?php //} ?> -->
</table>

<?php }
	?>
  <?php if(isset($isadmin) && $isadmin === true) {?><div><input type="submit" name="exportexcel" class="btn" value="Export as Excel"></div><?php } ?>
  </form>
  <?php
}
else {
	
	echo "Cannot find results for your search. Please try again...";
	
	}
	?>
