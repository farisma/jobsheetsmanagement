<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
  <script>
  $(document).ready(function(){
      $("input[name='holidaydate']").datepicker({dateFormat: "yy-mm-dd"});
	 
	  
	  });

  </script>
<script type="text/javascript">
function validate()
{
var hd,holidayname;

hd =  document.getElementById("holidaydate").value;
holidayname =  document.getElementById("holidayname").value;

    if(hd == "" || holidayname == "")
      {
               
		if(holidayname == "") {
          document.getElementById("hdname_validate").innerHTML = "Please fill in the holiday name "; 
        }
		else if(hd == "") {
          document.getElementById("hd_validate").innerHTML = "Please fill in the holiday date field "; 
        }
		
      }
      else {
         document.getElementById("form1").submit(); 
      }
}
</script>
<?php if(isset($message)) {
	if($message == "false") { ?> <div class="alert"> <?php echo "Holiday hasn't been added. Please try again."; ?></div> 
	<?php }
	}
	?>
<?php $this->load->view("pages/adminmenu");?>
<?php 

?>
<div class="hero-unit">

<?php echo form_open('vacation/insertholidays', array('class' => 'form-add-holidays','id' => 'form1')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Add Holidays</h2></th></tr>
	    <tr>
		   <td>Name:</td>
           <td><input type="text" id="holidayname" name="holidayname" /><span id="hdname_validate" style="color:red;"></span>
		  </td>
		</tr>
        <tr>
        <td>Date:</td>
         <td>
          <input type="text" name="holidaydate" id="holidaydate"  style="text-transform:uppercase;" value=""><span id="hd_validate" style="color:red;"></span>
         </td>
        </tr>
		 
		 
		
         <tr>
        <td><input class="btn" type="button" value="Add" onclick="Javascript: return validate()"></td>
         
        </tr>
	 </table>
	</form>
<?php ?>
</div>
