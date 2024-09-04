
<script type="text/javascript">
function validate()
{
var client,ccode;
client = document.getElementById("clientname").value;
ccode =  document.getElementById("clientcode").value;
    if(ccode == "" || client == "")
      {
        if(ccode == "") {
          document.getElementById("ccode_validate").innerHTML = "Please fill in the Client code field"; 
        }
        if(client == "") {
          document.getElementById("clientname_validate").innerHTML = "Please fill in the client field"; 
        }
      }
      else {
         document.getElementById("form1").submit(); 
      }
}
</script>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<?php echo form_open('submit-client', array('class' => 'form-add-client','id' => 'form1')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Client Management</h2></th></tr>
	    <tr>
		   <td>Enter the new Client:</td>
           <td><input type="text" name="clientname" id="clientname"><span id="clientname_validate" style="color:red;"></span></td>
		</tr>
        <tr>
        <td>Enter the Client Code:</td>
         <td>
          <input type="text" name="clientcode" id="clientcode" size="5" style="text-transform:uppercase;"><span id="ccode_validate" style="color:red;"></span>
         </td>
        </tr>
         <tr>
        <td><input class="btn" type="button" value="Add" onclick="Javascript: return validate()"></td>
         
        </tr>
	 </table>
	</form>
</div>