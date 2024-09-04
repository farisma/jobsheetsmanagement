<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
    $("input[name='date']").datepicker({dateFormat: "yy-mm-dd"});
    });
   
function validate()
{
var ccode;

ccode =  document.getElementById("clientcode").value;
  
        if(ccode == "") {
          document.getElementById("ccode_validate").innerHTML = "Please fill in the Client code field"; 
        }            
      else {
         document.getElementById("form1").submit(); 
      }
}
</script>
<?php 
 foreach($clientDetails as $row){
$id = $row["id"];
$clientname  = $row["clientname"];
$client_code = $row["client_code"];
$enabled = $row["enabled"];
$consolidated_billing_required = $row["consolidated_billing_for_retainer"];

}?>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
<?php echo form_open('edit-client/'.$id, array('class' => 'form-add-job','id' => 'form1')) ?>

	 <table>
	    <tr><th colspan="2"><h2>Client Management</h2></th></tr>
	    <tr>
		   <td>Client:</td><td> <?php  echo $clientname  ;?></td>		  		 
		</tr>		
		<tr><input type="hidden" name="client_id" value="<?php echo $id;?>">
		   <td>Client Code:</td><td><input type="text" name="clientcode" id="clientcode" value="<?php if(isset($client_code)) echo $client_code;?>"><span id="ccode_validate" style="color:red;"></td>
		</tr>  
		<tr>
			<td>Active:</td><td><input type="checkbox" name="enabled" id="enabled" value="y" <?php echo ($enabled=='y')?'checked':'';?>>&nbsp;&nbsp;</td>
		</tr>   
		<tr>
			<td>Consolidated billing required:</td><td><input type="checkbox" name="consolidated_billing_for_retainer" id="consolidated_billing_for_retainer" value="y" <?php echo ($consolidated_billing_required=='y')?'checked':'';?>>&nbsp;&nbsp;(Only if its a retainer client)</td>
		</tr>    
		<tr>
		<td colspan="2"><input class="btn" type="button" value="Update" onclick="Javascript: return validate()"></td>
		</tr>
	 </table>
	</form>
</div>