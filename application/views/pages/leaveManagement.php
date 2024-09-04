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
	if($_GET['success'] == "leaverequested" && $_GET['mailsent'] == "true") {
	echo "<div class='alert'>Leave request has been raised. An email has been sent to Admin</div>";
	}
	else if($_GET['success'] == "leaverequested" && $_GET['mailsent'] == "false") {
		echo "<div class='alert'>Leave request has raised. But there was an error in sending email to the admin. Please check with the Admin to approve your request</div>";
	}

if($_GET['success'] == "leaveconfirmed" && $_GET['mailsenttouser'] == "true") {
	echo "<div class='alert'>Leave has been approved. An email has been sent to the employee.</div>";
}
else if($_GET['success'] == "leaveconfirmed" && $_GET['mailsenttouser'] == "false") {
		echo "<div class='alert'>Leave has been confirmed. But there was an error in sending email to the employee.</div>";
	}
 

if($_GET['success'] == "leaverejected" && $_GET['rejectionsenttouser'] == "true") {
	echo "<div class='alert'>Leave has been rejected. An email has been sent to the employee.</div>";
}
else if($_GET['success'] == "leaverejected" && $_GET['rejectionsenttouser'] == "false") {
		echo "<div class='alert'>Leave has been rejected. But there was an error in sending email to the employee.</div>";
	}
 }
?>
<h3>Leave Management</h3>

<?php include('leavemanage_menu.php')?>
<ul class="leavetypes-2">
	<li><a href="<?php echo site_url('vacation/leavemanagement/annualleaverequests') ?>">Annual</a></li>
	<li><a href="<?php echo site_url('vacation/leavemanagement/sickleaverequests') ?>">Sick</a></li>
<li><a href="<?php echo site_url('vacation/leavemanagement/casualleaverequests') ?>">Casual</a></li>
<li><a href="<?php echo site_url('vacation/leavemanagement/unpaidleaverequests') ?>">Unpaid</a></li>
	</ul>
<?php if(isset($reqtype)) {
	if(isset($listofrequests)) {
	?>
	
<table class="leaverequests">
<tr>
<th></th><th width="150">Name</th><th width="150">Start date</th><th width="150">End Date</th><th width="150">Vacation type</th><th width="100">Status</th><th width="60">Action</th><th width="60"></th><th width="60"></th><th width="60"></th>
</tr>

<?php 


	foreach($listofrequests as $rows) {
	  	?>
	<tr><td></td><td><?php echo $rows['first_name'];?></td><td><?php echo date("d-m-Y", strtotime($rows['startdate']));?></td><td><?php echo date("d-m-Y", strtotime($rows['enddate']));?></td><td><?php echo $rows['vacationtype'];?></td><td><?php if($rows['approved'] == 'n') { echo "Not Approved"; } else if($rows['approved'] == 'r') {echo "Rejected";} else echo "Approved";?></td><td><?php if($rows['approved'] == 'n') {?><a href="<?php echo site_url('vacation/approveleave/'.$rows['id'].'/'.$rows['employee_id']) ?>">Approve</a><?php } else if($rows['approved'] == 'r') {?> <a href="<?php echo site_url('vacation/approveleave/'.$rows['id'].'/'.$rows['employee_id']) ?>">Approve</a><?php } else {?>  <a href="#">Approved</a><?php } ?></td><td><?php if($rows['approved'] == 'n') {?><a href="<?php echo site_url('vacation/rejectleave/'.$rows['id'].'/'.$rows['employee_id']) ?>">&nbsp;&nbsp;Reject<?php }?></td><td><a href="<?php echo site_url('vacation/deleteleave/'.$rows['id'].'/'.$rows['employee_id']) ?>">Delete</td><td><?php if(isset($rows['rejectremarks']) && $rows['approved'] == 'r') echo $rows['rejectremarks'];?></td></tr>
		<?php
	}	

?>

	
</table>

<?php
}
else {
echo "No requests found";
}	
}
	?>
</div>
