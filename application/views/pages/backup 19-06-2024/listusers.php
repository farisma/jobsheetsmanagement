<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<table class="listusers">
<tr><th colspan="4"><h2>User Management</h2></th></tr>
<tr><th colspan="4" align="center"><a href="<?php echo site_url('add-user') ?>">Add new user</a></th></tr>
<tr><th>Sl. No.</th><th>Username</th><th>First name</th><th>Last name</th><th>Email</th><th>Active</th><th>Action</th></tr>

<?php 
$i = 1;
//print_r($users);
foreach($users as $row=>$val)
{ 

$userid =  $val["user_id"];
?><tr><td colspan="1"><?php echo $i;?></td>
<td colspan="1"><?php echo $val["username"];?></td>
<td colspan="1"><?php echo $val["first_name"];?></td>
<td colspan="1"><?php echo $val["last_name"];?></td>
<td colspan="1"><?php echo $val["email"];?></td>
<td colspan="1" style="color:red;"><?php echo $val["resigned"]=='y'?"Not Active":'';?></td>


<td><a href="<?php echo site_url('edit-user/'. $userid); ?>">Edit</a>/<a href="<?php echo site_url('delete-user/'. $userid); ?>">Delete</a></td></tr>
<?php 
$i++;}
?>

</table>
</div>