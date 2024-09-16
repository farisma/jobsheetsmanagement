<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>

<table class="listclients">
<tr><th colspan="4"><h2>Divisions Management</h2></th></tr>
<tr><th colspan="4" align="center"><a href="<?php echo site_url('add-division') ?>">Add new division</a></th></tr>
<tr><th>Sl. No.</th><th>Client</th><th>Division</th></tr>
<?php 
$i = 1;
if(isset($divisions)) {
foreach($divisions as $row)
{ 
    $divisionid = $row["id"];
?><tr><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["clientname"];?></td><td colspan="1"><?php echo $row["division"];?></td><td><a href="<?php echo site_url('edit-division/'. $divisionid); ?>">Edit</a>/<a href="<?php echo site_url('delete-division/'. $divisionid); ?>">Delete</a></td></tr>
<?php 
$i++;
}
}
?>

</table>
</div>