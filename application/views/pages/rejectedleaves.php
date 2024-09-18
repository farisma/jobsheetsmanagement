<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>

<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu");?>
<div class="hero-unit">
<?php if(isset($_GET['success']))
{
	if($_GET['success'] == "leaveedited") {
	echo "<div class='alert'>Leave has been edited.</div>";
	}
	else  {
		echo "<div class='alert'>Change couldn't be made. Please check with the IT support.</div>";
	}
 }
?>
<h3>Leave Management</h3>
<?php include("leavemanage_menu.php");?>
<?php if(isset($rejectedleaverequests)) {?>
<table class="leaverequests">
<tr>
	<th></th>
	<th width="150">Name</th>
	<th width="150">Start date</th>
	<th width="150">End Date</th>
	<th width="50">Days</th>
	<th width="150">Vacation type</th>
	<th width="150">Notes</th>
	<th width="100">Action</th>
</tr>

<?php 
	foreach($rejectedleaverequests as $rows) {
	  	?>
	<tr>
		<td></td>
	    <td><?php echo $rows['first_name'];?></td>
		<td><?php echo date("d-m-Y", strtotime($rows['startdate']));?></td>
		<td><?php echo date("d-m-Y", strtotime($rows['enddate']));?></td>
		<td><?php echo $rows['noofdays'];?></td>
		<td><?php echo $rows['vacationtype'];?></td>
		<td><?php if(isset($rows['rejectremarks'])) echo $rows['rejectremarks'];?></td>
		<td><a href="<?php echo site_url('vacation/editleave/'.$rows['id'].'/'.$rows['employee_id']) ?>">&nbsp;&nbsp;Edit</a>&nbsp;&nbsp;<a href="<?php echo site_url('vacation/deleteleave/'.$rows['id'].'/'.$rows['employee_id']) ?>">Delete</a>&nbsp;</td>
	</tr>
		<?php
	}	

?>

</table>
<?php }
else {
	echo "No rejected leaves found";
}
?>
</div>
