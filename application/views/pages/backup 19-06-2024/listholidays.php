



<?php $this->load->view("pages/adminmenu")?>
<?php 

?>
<div class="hero-unit">

<?php
	if(isset($message)) {
		?><div class="alert"> <?php echo $message;?></div>
		
		<?php 
	}
if(isset($holidays)) {

	?>
	<table>
	  <tr>
	    <th align="left">Sl. No.</th>
		<th align="left" width="180">Holiday</th>
		<th align="left">Date</th>
		<th align="left">Action</th>
	  </tr>
	  <?php 
	  $i=1;
	  foreach($holidays as $hols)
	  {
	     ?><tr> <td><?php echo $i;?></td><td><?php echo $hols['name'];?></td><td><?php echo date("d-m-Y", strtotime($hols['date']));?></td><td><a href="<?php echo site_url('delete-holiday/'.$hols['id']."/".$hols['date'].""); ?>">Delete</a></td></tr>
		 <?php
		 $i++;
	  }
	  ?>
	</table>
	
	<?php
} else
{
	echo "No holidays found";
	
}
 ?>
 <ul><li><a href="<?php echo site_url('vacation/addholidays') ?>">Add holiday</a></li></ul>
</div>
