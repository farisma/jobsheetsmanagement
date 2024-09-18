<script type="text/javascript">
function validate()
{
    var divisionname;
    divisionname = document.getElementById("divisionname").value;
    client = document.getElementById("client").value;

    if(divisionname == "")
      {        
          document.getElementById("division_validate").innerHTML = "Please fill in the division field";                
      }
      else if(client == "")
      {        
          document.getElementById("client_validate").innerHTML = "Please select the client";                
      }
      else {
         document.getElementById("form1").submit(); 
      }
}
</script>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<a href="<?php echo site_url('list_divisions') ?>">View all divisions</a>
<div class="hero-unit">
<?php echo form_open('add-division', array('class' => 'form-add-division','id' => 'form1')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Client Divisions</h2></th></tr>
	    <tr>
		   <td>Select Client:</td>
           <td>
           <select name="client" id="client" onChange="">
           <?php $i=0;
				foreach($clients as $key => $row):
				?>
					<option value="<?php echo $key;?>"> <?php echo $row;?></option>	   
				<?php endforeach;  ?>					
            </select>
            <span id="client_validate" style="color:red;"></span>
           </td>
		</tr>
        <tr>
            <td>Name</td>
            <td><input name="divisionname" class="divisionname" id="divisionname" /><span id="division_validate" style="color:red;"></span></td>
        </tr>
        <tr>
            <td>Color</td>
            <td><input name="divisioncolor" class="divisioncolor" id="divisioncolor" /><span id="divisioncolor_validate" style="color:red;"></span></td>
        </tr>
    
        <tr>
            <td>Text Color</td>
            <td><input name="divisiontextcolor" class="divisiontextcolor" id="divisiontextcolor" /><span id="divisiontextcolor_validate" style="color:red;"></span></td>
        </tr>        
         <tr>
            <td><input class="btn" type="button" value="Add" onclick="Javascript: return validate()"></td>         
        </tr>
	 </table>
	</form>
</div>