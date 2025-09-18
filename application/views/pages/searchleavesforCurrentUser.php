<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<div class="hero-unit">

<?php echo form_open('user/searchleavesforuser', array('class' => 'searchleaves','id'=>'searchleavesform')) ?>
<h3>Check your leaves status</h3>

<?php //include('leavemanage_menu.php')?>
	 <table class="searchjobsheet" id="searchjobsheet">
	  
	
			<tr>			
		   
		    <td>
				<?php
              $currentYear = date("Y");?>

			  <select name="year" id="year">
				<?php
				for ($year = 2017; $year <= $currentYear; $year++) {
					echo "<option value=\"$year\">$year</option>";
				}
				?>
			  
			  </select>
			</td>
		   </tr>		  		  
		</tr>
		<tr>
		<td colspan="2"><input type="submit" id="submitsearch" name="submitsearch" value="Search" class="btn" ></td>
		</tr>
	 </table>
	</form>
	
	<?php   if(isset($userid)) {
	//$username =  $users[$userid];
	
	?>
	<table class="searchresultslist">
	 <tr><th colspan="6">Search results...</th></tr>
	    <tr>
<th>User</th><th colspan="1">Annual</th><th colspan="1">Sick</th><th colspan="1">Unpaid</th><th colspan="1">Casual</th>
</tr>

<tr><td colspan="1"><?php echo $username;?></td><td colspan="1"><?php echo $annualleavescnt;?></td><td colspan="1"><?php echo $sickleavescnt;?></td><td colspan="1"><?php echo $unpaidleavescnt;?></td><td colspan="1"><?php echo $casualeavescnt;?></td></tr>

	</table>
<?php	if(isset($annualleaves)) {?>
	<table class="leaverequests">
	<tr><th colspan="4">Annual Leaves</th></tr>
	<tr><td width="150"> Start date</td><td width="150">End date</td><td width="150">No. of days</td><td>Calendar days</td></tr>
	<?php
	foreach($annualleaves as $row)
	{
	?><tr><td><?php echo date("d-m-Y", strtotime($row['startdate'])); ?></td><td><?php echo date("d-m-Y", strtotime($row['enddate']));?></td><td><?php echo $row['noofdays'];?></td><td><?php echo $row['calendardays'];?></td></tr>
	<?php
	}?>
	</table>
	<?php
}
?>
	<?php	if(isset($sickleaves)) {?>
	<table class="leaverequests">
	<tr><th colspan="4">Sick Leaves</th></tr>
	<tr><td width="150"> Start date</td><td width="150">End date</td><td width="150">No. of days</td><td>Calendar days</td></tr>
	<?php
	foreach($sickleaves as $row)
	{
	?><tr><td><?php echo date("d-m-Y", strtotime($row['startdate'])); ?></td><td><?php echo date("d-m-Y", strtotime($row['enddate']));?></td><td><?php echo $row['noofdays'];?></td><td><?php echo $row['calendardays'];?></td></tr>
	<?php
	}?>
	</table>
	<?php
}
?>
<?php	if(isset($casualeaves)) {?>
	<table class="leaverequests">
	<tr><th colspan="4">Casual Leaves</th></tr>
	<tr><td width="150"> Start date</td><td width="150">End date</td><td width="150">No. of days</td><td>Calendar days</td></tr>
	<?php
	foreach($casualeaves as $row)
	{
	?><tr><td><?php echo date("d-m-Y", strtotime($row['startdate'])); ?></td><td><?php echo date("d-m-Y", strtotime($row['enddate']));?></td><td><?php echo $row['noofdays'];?></td><td><?php echo $row['calendardays'];?></td></tr>
	<?php
	}?>
	</table>
	<?php
}
?>
<?php	if(isset($unpaidleaves)) {?>
	<table class="leaverequests">
	<tr><th colspan="4">Unpaid Leaves</th></tr>
	<tr><td width="150"> Start date</td><td width="150">End date</td><td width="150">No. of days</td><td>Calendar days</td></tr>
	<?php
	foreach($unpaidleaves as $row)
	{
	?><tr><td><?php echo date("d-m-Y", strtotime($row['startdate'])); ?></td><td><?php echo date("d-m-Y", strtotime($row['enddate']));?></td><td><?php echo $row['noofdays'];?></td><td><?php echo $row['calendardays'];?></td></tr>
	<?php
	}?>
	</table>
	<?php
}
?>
	<?php }

	else {
	
	echo "Cannot find results for your search. Please try again...";
	
	}
	
	?>
</div>
