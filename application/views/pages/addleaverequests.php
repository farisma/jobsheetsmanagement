<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT"></script>

  <script>
  $(document).ready(function(){
      $("input[name='startdate']").datepicker({dateFormat: "yy-mm-dd"});
	  $("input[name='enddate']").datepicker({dateFormat: "yy-mm-dd"});
	  
	  });

  </script>


<?php //$this->load->view("pages/adminmenu")?>

<div class="hero-unit">
<?php
$hideform = 0;
 if(isset($message)) {
	if($message == "true") { $hideform = 1;?> <div class="alert"> <?php if($mailsent == "true") {echo "Leave request has been raised. An email has been sent to HR"; } else { echo "Leave request has been raised. But there was an error in sending email to the HR. Please check with the HR to approve your request";}?></div> 
	<?php }
	else { ?> <div class="alert"> <?php echo "Leave request hasn't been sent to Admin.";?></div>
	<?php 
	
	
	}
	?><?php }?>
<?php if($hideform == 0) {?>
<?php echo form_open('user/addemployeeleaves', array('class' => 'form-add-leaves','id' => 'form1')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Leave Management - Apply for leave</h2></th></tr>
	    <tr>
		   <td>Name:</td><?php ?>
           <td><?php echo $user['username']?><input type="hidden" id="userid" name="userid" value="<?php echo $user['id']?>"></td>
		</tr>
        <tr>
        <td>Start date:</td>
         <td>
          <input type="text" name="startdate" id="startdate"  style="text-transform:uppercase;"><span id="sd_validate" style="color:red;"></span>
         </td>
        </tr>
		   <tr>
        <td>End date:</td>
         <td>
          <input type="text" name="enddate" id="enddate" style="text-transform:uppercase;"><span id="ed_validate" style="color:red;"></span>
         </td>
        </tr>
		<tr>
        <td>No. of days:</td>
         <td>
          <input type="text" name="noofdays" id="noofdays" style="text-transform:uppercase;"><span id="nod_validate" style="color:red;"></span>
         </td>
        </tr>
	
		<tr>
		<td>Vacation Type</td>
		<td>
		<select name="vacation_type" id="vacation_type">
		<?php 
		if(isset($vacationtypes))  {
		foreach($vacationtypes as $rows => $values) {?>
		<?php ?>
		<option value="<?php echo $rows;?>"><?php echo $values;?></option>
		<?php } } ?>
		</select>
		</td>
		</tr>
         <tr>
        <td>	            <input type="hidden" id="token" name="token">
<input class="btn" type="button" id="submit-btn" value="Add" ></td>
         
        </tr>
	 </table>
	</form>
<?php } ?>
</div>

 <script>
 window.addEventListener('load', function () {
document.getElementById('submit-btn').addEventListener(
  'click',
  function(e) {
  e.preventDefault();
        var emp,sd,ed,noofdays;

sd =  document.getElementById("startdate").value;
ed =  document.getElementById("enddate").value;
noofdays = document.getElementById("noofdays").value;
if(sd == "" || ed == "" || noofdays == "")
      {
      
        if(sd == "") {
          document.getElementById("sd_validate").innerHTML = "Please fill in the start date field"; 
        }
		else if(ed == "") {
          document.getElementById("ed_validate").innerHTML = "Please fill in the end date field"; 
        }
		else if(noofdays == "") {
          document.getElementById("nod_validate").innerHTML = "Please fill in the no. of days field"; 
        }
      }
      else {

        grecaptcha.ready(function() {
          grecaptcha.execute('6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
              console.log(token);
              if(token != "")
              {
                document.getElementById("token").value = token;
                document.getElementById("form1").submit(); 
              }
              

          });
        });
        
      }
        
      });

      
 });
  </script>

