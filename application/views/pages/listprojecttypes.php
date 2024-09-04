<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<table class="listclients">
<tr><th colspan="4"><h2>Client Management</h2></th></tr>
<tr><th colspan="4" align="center"><a href="<?php echo site_url('add-project-type') ?>">Add new project type</a></th></tr>
<tr><th>Sl. No.</th><th>Client</th><th>Action</th></tr>
<?php 
$i = 1;
foreach($listofprojecttypes as $row)
{ $projecttypeid = $row["id"];
?><tr><td colspan="1"><?php echo $i;?></td><td colspan="1"><?php echo $row["project_type"];?></td><td><a href="<?php echo site_url('edit-project-type/'. $projecttypeid); ?>">Edit</a>/<a href="<?php echo site_url('delete-project-type/'. $projecttypeid); ?>">Delete</a></td></tr>
<?php 
$i++;}
?>

</table>
</div>