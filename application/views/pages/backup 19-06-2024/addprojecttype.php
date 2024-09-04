
<script type="text/javascript">
function validate()
{
var proj_type;
proj_type = document.getElementById("project_type").value;

    if(proj_type == "")
      {
        
          document.getElementById("project_type_validate").innerHTML = "Please fill in the project type field"; 
        
       
      }
      else {
         document.getElementById("form1").submit(); 
      }
}
</script>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<?php echo form_open('submit-project-type', array('class' => 'form-add-project-type','id' => 'form1')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Job Management</h2></th></tr>
	    <tr>
		   <td>Enter project type:</td>
           <td><input type="text" name="project_type" id="project_type"><span id="project_type_validate" style="color:red;"></span></td>
		</tr>
    
         <tr>
        <td><input class="btn" type="button" value="Add" onclick="Javascript: return validate()"></td>
         
        </tr>
	 </table>
	</form>
</div>