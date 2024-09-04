<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
    <script type="text/javascript">

   
   function validate()
{
var project_type;

project_type =  document.getElementById("project_type").value;
  
        if(project_type == "") {
          document.getElementById("project_type_validate").innerHTML = "Please fill in the project type field"; 
        }            
      else {
         document.getElementById("form1").submit(); 
      }
}
</script>
<?php 
 foreach($projecttype as $row){
$id = $row["id"];
$project_type  = $row["project_type"];

}?>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<?php echo form_open('edit-project-type/'.$id, array('class' => 'form-add-job','id' => 'form1')) ?>

	 <table>
	    <tr><th colspan="2"><h2>Job Management</h2></th></tr>
	   		
		<tr><input type="hidden" name="project_type_id" value="<?php echo $id;?>">
		   <td>Project type:</td><td><input type="text" name="project_type" id="project_type" value="<?php if(isset($project_type)) echo $project_type;?>"><span id="project_type_validate" style="color:red;"></td>
		</tr>        
		<tr>
		<td colspan="2"><input class="btn" type="button" value="Update" onclick="Javascript: return validate()"></td>
		</tr>
	 </table>
	</form>
</div>