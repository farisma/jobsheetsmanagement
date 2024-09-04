<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<table class="listclients">
<tr><th colspan="4"><h2>Client Management</h2></th></tr>
<tr><th colspan="4" align="center"><a href="<?php echo site_url('add-client') ?>">Add new client</a></th></tr>
<tr><th>Sl. No.</th><th>Client</th><th>Active</th><th>Consolidated billing required(Retainers)</th><th>Action</th></tr>

<?php 
$i = 1;
foreach($clients as $row)
{ $jobsheetid = $row["id"];
?><tr><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php  echo ($row["enabled"]=='y')?'Yes':'No';?></td><td><?php  echo ($row["consolidated_billing_for_retainer"]=='y')?'Yes':'No';?></td><td><a href="<?php echo site_url('edit-client/'. $jobsheetid); ?>">Edit</a>/<a href="<?php echo site_url('delete-client/'. $jobsheetid); ?>">Disable</a></td></tr>
<?php 
$i++;}
?>

</table>
</div>