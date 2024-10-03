<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
<!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
<script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
 <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
 <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render=6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("input[name='date']").datepicker({dateFormat: "yy-mm-dd"});
		$("input[name='dateR']").datepicker({dateFormat: "yy-mm-dd"});
		$("input[name='dateEK']").datepicker({dateFormat: "yy-mm-dd"});
		$("input[name='dateConsolidatedBillingC']").datepicker({dateFormat: "yy-mm-dd"});
		$("td.addto_consolidated").hide();
		//$('#tabs').tab();
	});
</script>
<script type="text/javascript">
	function getJobSeqNo(value){
		var clientId = value;
		var divisions_option;
		divisions_option = $('#company_division');
	
		jQuery.ajax({
					type: "GET",
					url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetDivisionsByClientID/"+clientId,
					data: {},
					dataType: "json",
					success: function(res) {
						if (res)
						{
							//console.log(res)
							//alert(res)
							divisions_option.addClass('show')
							$(".division_field").addClass('show')
							$.each(res, function(index, value) {
								divisions_option.append($('<option></option>').val(value.id).text(value.division));
							});
						}
						else 
						{
							divisions_option.removeClass('show')
							$(".division_field").removeClass('show')
							divisions_option.val('')
							//$("#show_jobno").text("Error in fetching the Job no."); 
						}
					}
		});

		 
		jQuery.ajax({
			type: "GET",
			url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetnonRetainerJobcodes/"+clientId,
			data: {},
			dataType: "json",
			success: function(res) {
				if (res)
				{
					$("#jobno").val(res); 
					$("#show_jobno").text(res); 
					
				}
				else 
				{
					$("#show_jobno").text("Error in fetching the Job no."); 
				}
			}
		});    
	}
    
	function getRetainerJobSeqNo(value)
	{
		var clientId;
		var divisions_option;
		//this function is called from datepicker aswell. In that case value  will be null. then will selct the value from dropdown
		if(value == null) clientId = $("#clientR").val(); else clientId = value;
		divisions_option = $('#retainer_company_division');
	
		   jQuery.ajax({
					type: "GET",
					url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetDivisionsByClientID/"+clientId,
					data: {},
					dataType: "json",
					success: function(res) {
						if (res)
						{
							//console.log(res)
							divisions_option.addClass('show')
							$(".retainer_division_field").addClass('show')
							$.each(res, function(index, value) {
								divisions_option.append($('<option></option>').val(value.id).text(value.division));
							});
						}
						else 
						{
							$(".retainer_division_field").removeClass('show')
							divisions_option.removeClass('show')
							divisions_option.val('')
							//$("#show_jobno").text("Error in fetching the Job no."); 
						}
					}
				});
		
		
		

	$("td.addto_consolidated").hide();
	var monthNames = ["Jan", "Feb", "March", "April", "May", "June","July", "Aug", "Sep", "Oct", "Nov", "Dec"];
	var retainerClients = ["18","75"]; //Client ids apart from EKSC that are part of monthly retainers and need consolidated job no, here its mubadala
		var clientR = $("#clientR").val();		
		var dateR   = $("#dateR").val();
		/*console.log(retainerClients.indexOf(clientR));
		if(retainerClients.indexOf(clientR) == -1)
		{
	
		$("td.addto_consolidated").hide(); //this is required only if retainer client that needs consolidated jobno is present
		}*/
	
		if(clientR != "" && dateR!= "") {
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetRetainerJobcodes/",
				data: $('#form-add-retainer-job').serialize(), 
				dataType: "json",
				success: function(res) {
					if (res)
					{
						$("#jobnoR").val(res[1]); 
						$("#show_jobnoR").text(res[1]); 
						//console.log(res);
						if(res[0] == true) /*If this is the first job no of the month for a retainer that needs consolidated jobno */
							{
								var p_int = parseInt(res[2]) - parseInt(1);			  
								var monthN = monthNames[p_int];
								if(res[4] == true) {
									$("#descriptionConsolidatedretainer-show").text("Consolidated job no. for " + monthN+ ", " + res[3]+" ");
									$("#descriptionR").val("Consolidated job no. for " + monthN+ ", " + res[3]+" "); 
									$("#descriptionR").hide();
									$("#monthly_consol_jobno_for_retainers").val("y");
									$(".addto_consolidated").hide();
								}
								else if(res[4] == false) { // if the client is consolidated billing retainer and its not first job of the month
									$("#descriptionConsolidatedretainer-show").text("");
								    $("#descriptionR").val(""); 
								    $("#descriptionR").show();
									$("#monthly_consol_jobno_for_retainers").val("n");
									$(".addto_consolidated").show();
								}
							}
							else if(res[0] == false) {
								$("#descriptionR").show();	
								$("#descriptionR").val("");
								$("#jobnameR").val("");
								$("#descriptionConsolidatedretainer-show").text("");
								$("#monthly_consol_jobno_for_retainers").val("n");
								//console.log(retainerClients.indexOf(clientR));
								// if(retainerClients.indexOf(clientR) != -1) /* This is required only for the jobs that are not the first jobs of a month as well as its a part of a retainer client that needs consolidated billing*/
								// 	{    
								//        $(".addto_consolidated").show();
								// 	}
							}
					}
					else 
					{
						$("#show_jobnoR").text("Error in fetching the Job no."); 
					}
				}
			});
		}        
	}
	

	function getConsolidatedBillingCJobSeqNo(value){
		//$("td.addto_consolidated").hide();
		var divisions_option;
		var clientId;
		//this function is called from datepicker aswell. In that case value  will be null. then will selct the value from dropdown
		if(value == null) clientId = $("#clientConsolidatedB").val(); else clientId = value;
		divisions_option = $('#consol_company_division');
	
		   jQuery.ajax({
					type: "GET",
					url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetDivisionsByClientID/"+clientId,
					data: {},
					dataType: "json",
					success: function(res) {
						if (res)
						{
							//console.log(res)
							//divisions_option = $('#consol_company_division');
							divisions_option.addClass('show')
							$(".consolidated_division_field").addClass('show')
							$.each(res, function(index, value) {
								divisions_option.append($('<option></option>').val(value.id).text(value.division));
							});
						}
						else 
						{
							divisions_option.removeClass('show')
							$(".consolidated_division_field").removeClass('show')
							divisions_option.val('')
							//$("#show_jobno").text("Error in fetching the Job no."); 
						}
					}
				});
		
	var monthNames = ["Jan", "Feb", "March", "April", "May", "June","July", "Aug", "Sep", "Oct", "Nov", "Dec"];
	//var retainerClients = ["18","75"]; //Client ids apart from EKSC that are part of monthly retainers and need consolidated job no, here its mubadala
		var clientConsolidatedB = $("#clientConsolidatedB").val();		
		var dateConsolidatedBillingC   = $("#dateConsolidatedBillingC").val();
		/*console.log(retainerClients.indexOf(clientR));
		if(retainerClients.indexOf(clientR) == -1)
		{
	
		$("td.addto_consolidated").hide(); //this is required only if retainer client that needs consolidated jobno is present
		}*/

		if(clientConsolidatedB != "" && dateConsolidatedBillingC!= "") {
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetConsolidatedBillingJobcodes/",
				data: $('#form-add-consolidated-billingC-job').serialize(), 
				dataType: "json",
				success: function(res) {
					if (res)
					{
						$("#jobnoConsolidatedBillingC").val(res[1]); 
						$("#show_jobnoConsolidatedJob").text(res[1]); 
						console.log("results",res);
						var p_int = parseInt(res[2]) - parseInt(1);			  
						var monthN = monthNames[p_int];
						if(res[0] == true)
						  {
							$("#descriptionConsolidatedbillingC-show").text("Consolidated job no. for " + monthN+ ", " + res[3]+" ");
							$("#descriptionConsolidatedBillingC").val("Consolidated job no. for " + monthN+ ", " + res[3]+" "); 
							$("#descriptionConsolidatedBillingC").hide();
						  }
						else {
							$("#descriptionConsolidatedbillingC-show").text("");
							$("#descriptionConsolidatedBillingC").val(""); 
							$("#descriptionConsolidatedBillingC").show();
						}


					}
					else 
					{
						$("#show_jobnoConsolidatedJob").text("Error in fetching the Job no."); 
					}
				}
			});
		} 
	}


	function getEKRetainerJobSeqNo()
	{
		var monthNames = ["Jan", "Feb", "March", "April", "May", "June","July", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var divisions_option;
		var clientId;
		clientId = $("#clientEK").val(); 
		divisions_option = $('#ekretainer_company_division');

		jQuery.ajax({
					type: "GET",
					url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetDivisionsByClientID/"+clientId,
					data: {},
					dataType: "json",
					success: function(res) {
						if (res)
						{
							//console.log(res)
							//divisions_option = $('#consol_company_division');
							divisions_option.addClass('show')
							$(".ekretainer_division_field").addClass('show')
							$.each(res, function(index, value) {
								divisions_option.append($('<option></option>').val(value.id).text(value.division));
							});
						}
						else 
						{
							divisions_option.removeClass('show')
							$(".ekretainer_division_field").removeClass('show')
							divisions_option.val('')
							//$("#show_jobno").text("Error in fetching the Job no."); 
						}
					}
				});
		
		jQuery.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/ajaxToGetEKRetainerJobcodes/",
			data: $('#form-add-ekretainer-job').serialize(), 
			dataType: "json",
			success: function(res) {
				if (res)
				{
					console.log(res)
					$("#jobnoEK").val(res[1]); 
					$("#show_jobnoEK").text(res[1]); 
					if(res[0] == true) /*If this is the first job no of the month */
					{
						var p_int = parseInt(res[2]) - parseInt(1);			  
						var monthN = monthNames[p_int];
						$("#descriptionEK-show").text("Consolidated job no. for " + monthN+ ", " + res[3]+" ");
						$("#descriptionEK").val("Consolidated job no. for " + monthN+ ", " + res[3]+" ");  
						$("#descriptionEK").hide();
						$("#monthly_consol_jobno").val("y");
						$(".under750_cont").hide();
					}
					else if(res[0] == false) {
						$("#descriptionEK").show();
						$("#descriptionEK").val("");
						$("#descriptionEK-show").text("");
						$("#monthly_consol_jobno").val("n");
						$(".under750_cont").show();
					}
					
				}
				else 
				{
					$("#show_jobnoEK").text("Error in fetching the Job no."); 
				}
			}
		});
	}
	
	function validate1()
	{
		var client, desc, jobno, jobname, project_type,quoted_amount;
		client = document.getElementById("client").value;
		desc = document.getElementById("description").value;
		jobname = document.getElementById("jobname").value;
		jobno =  document.getElementById("jobno").value;
		quoted_amount = parseInt(document.getElementById("quoted_amount").value);
		project_type = document.getElementById("project_type_nr").value;
		if(desc == "" || client == "" || jobname == "" || project_type == "") 
		{
			
			if(client == "")
			{
				document.getElementById("client_validate1").innerHTML = "Please select the client";
			}
			if(desc == "")
			{
				document.getElementById("desc_validate1").innerHTML = "Please fill in the description field";
			}
			if(jobname == "")
			{
				document.getElementById("jobname_validate1").innerHTML = "Please fill in the job name field";
			}
			if(project_type == "")
			{
		        document.getElementById("project_type_nr_validate1").innerHTML = "Please fill in the project type field";
			}
			
			return false;
		}
		else if((quoted_amount != "" || quoted_amount !=0) &&  quoted_amount > 100000)
			{
				
		        document.getElementById("quoted_amount_validate1").innerHTML = "Amount exceeds the maximum limit. You can only enter up 100000";
				return false;
			}
		else { 
			 grecaptcha.ready(function() {
          grecaptcha.execute('6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
              console.log("Token",token);
              if(token != "")
              {
                document.querySelector(".token1").value = token;
               document.getElementById("form1").submit(); 
              }
              

          });
        });
			
		}
		
	}
	
	function validate2()
	{
		var client, desc, date, jobnameR,project_type,quoted_amount;
		client = document.getElementById("clientR").value;
		desc = document.getElementById("descriptionR").value;
		jobnameR =  document.getElementById("jobnameR").value;
		date =  document.getElementById("dateR").value;
		project_type = document.getElementById("project_type_rc").value;
		quoted_amount = parseInt(document.getElementById("retainer_quoted_amount").value);

		if(desc == "" || client == "" || date == "" || jobnameR == "" || project_type == "") 
		{
			
			if(client == "")
			{
				document.getElementById("client_validate2").innerHTML = "Please select the client";
			}
			if(desc == "")
			{
				document.getElementById("desc_validate2").innerHTML = "Please fill in the description field";
			}
			if(date == "")
			{
				document.getElementById("date_validate2").innerHTML = "Please fill in the date field";
			}
			if(jobnameR == "")
			{
				document.getElementById("jobnameR_validate2").innerHTML = "Please fill in the job name field";
			}
			if(project_type == "")
			{
		        document.getElementById("project_type_rc_validate1").innerHTML = "Please fill in the project type field";
			}
			return false;
		}
		else if((quoted_amount != "" || quoted_amount !=0) &&  quoted_amount > 100000)
			{
				
		        document.getElementById("quoted_amount_validate2").innerHTML = "Amount exceeds the maximum limit. You can only enter up 100000";
				return false;
			}
		else { 
			 grecaptcha.ready(function() {
          grecaptcha.execute('6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
              console.log("Token",token);
              if(token != "")
              {
                document.querySelector(".token2").value = token;
               document.getElementById("form-add-retainer-job").submit(); 
              }
              

          });
        });
			
		}
	}
	
	function validate3()
	{
		var desc,date,jobnameEK,project_type;
		desc = document.getElementById("descriptionEK").value;
		jobnameEK = document.getElementById("jobnameEK").value;
		date = document.getElementById("dateEK").value;
		project_type = document.getElementById("project_type_ekr").value;
		quoted_amount = parseInt(document.getElementById("ekretainer_quoted_amount").value);
		if(desc == "" || date == "" || jobnameEK == "" || project_type == "") 
		{
			if(jobnameEK == "")
			{
				document.getElementById("jobnameEK_validate3").innerHTML = "Please fill in the job name field";
			}
			if(desc == "")
			{
				document.getElementById("desc_validate3").innerHTML = "Please fill in the description field";
			}
			if(date == "")
			{
				document.getElementById("date_validate3").innerHTML = "Please fill in the date field";
			}
			if(project_type == "")
			{
		        document.getElementById("project_type_ekr_validate1").innerHTML = "Please fill in the project type field";
			}
			return false;
		}
		else if((quoted_amount != "" || quoted_amount !=0) &&  quoted_amount > 100000)
			{
				
		        document.getElementById("quoted_amount_validate3").innerHTML = "Amount exceeds the maximum limit. You can only enter up 100000";
				return false;
			}
		else { 
			 grecaptcha.ready(function() {
          grecaptcha.execute('6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
              console.log("Token",token);
              if(token != "")
              {
                document.querySelector(".token3").value = token;
                document.getElementById("form-add-ekretainer-job").submit(); 
              }
              

          });
        });
			
		}
	}


	function validate4() {
		var client, desc, date, jobnameR,project_type,quoted_amount;
		client = document.getElementById("clientConsolidatedB").value;
		desc = document.getElementById("descriptionConsolidatedBillingC").value;
		jobnameR =  document.getElementById("jobnameConsolidatedBillingC").value;
		date =  document.getElementById("dateConsolidatedBillingC").value;
		project_type = document.getElementById("project_type_ccb").value;
		quoted_amount = parseInt(document.getElementById("consol_quoted_amount").value);

		if(desc == "" || client == "" || date == "" || jobnameR == "" || project_type == "") 
		{

			if(client == "")
			{
				document.getElementById("client_validate4").innerHTML = "Please select the client";
			}
			if(desc == "")
			{
				document.getElementById("desc_consolidated_bc").innerHTML = "Please fill in the description field";
			}
			if(date == "")
			{
				document.getElementById("date_validate4").innerHTML = "Please fill in the date field";
			}
			if(jobnameR == "")
			{
				document.getElementById("jobnameconsolidatedB_validate4").innerHTML = "Please fill in the job name field";
			}
			if(project_type == "")
			{
		        document.getElementById("project_type_ccb_validate1").innerHTML = "Please fill in the project type field";
			}
			return false;
		}
		else if((quoted_amount != "" || quoted_amount !=0) &&  quoted_amount > 100000)
			{
				
		        document.getElementById("quoted_amount_validate4").innerHTML = "Amount exceeds the maximum limit. You can only enter up 100000";
				return false;
			}
		else { 
			 grecaptcha.ready(function() {
          grecaptcha.execute('6LeQ6A4aAAAAAKe29UyIDwcqvmwikoKhAaE-80GT', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
              console.log("Token",token);
              if(token != "")
              {
                document.querySelector(".token4").value = token;
               document.getElementById("form-add-consolidated-billingC-job").submit(); 
              }


          });
        });

		}
	}
	
	
</script>
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>
<div class="hero-unit">
	<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#nonRetaingC" data-toggle="tab">Job numbers</a></li>
        <li><a href="#retainingC" data-toggle="tab">Retaining Customers</a></li>
		<li><a href="#consolidatedJobsC" data-toggle="tab">Consolidated Billing Customers</a></li>
        <li><a href="#ekscjobs" data-toggle="tab">EKSC</a></li>
		
		
	</ul>
    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="nonRetaingC">
            <h2>Job Numbers</h2>
            <?php echo form_open('submit-job', array('class' => 'form-add-job','id'=>'form1', 'autocomplete'=>'off')); ?>
			<table>	   
				<tr>
					<td>Client:</td><td>    
						<select name="client" id="client" onChange="getJobSeqNo(this.value)">
							<option value="select"> Select Client</option>           
							<?php $i=0;
								foreach($clients as $key => $row):
							?>
							<option value="<?php echo $key;?>"> <?php echo $row;?></option>	   
							<?php 
							endforeach;  ?>		 
						</select> <span id="client_validate1" style="color:red;"></span>
					</td>
				</tr>
				<tr>
					<td>Job no.:</td><td><span id="show_jobno"></span><input type="hidden" name="jobno" id="jobno"  ></td>
				</tr>
				<tr>
					<td>Date:</td><td><input type="text" name="date" ></td>
				</tr>
				<tr>
					<td>Job name:</td><td><input type="text" name="jobname" id="jobname" ><span id="jobname_validate1" style="color:red;"></span></td>
				</tr>
				<tr>
					<td>Description:</td><td><input type="text" name="description" id="description" ><span id="desc_validate1" style="color:red;"></span></td>
				</tr>
				<tr>
				<td>Project type:</td><td>
					<select name="project_type_nr[]" id="project_type_nr" multiple="multiple">
							        
							<?php $i=0;
							//print_r($project_types);
								foreach($project_types as $key => $row):
							?>
							<option value="<?php echo $row['id'];?>"> <?php echo $row['project_type'];?></option>	   
							<?php 
							endforeach;  ?>		
							
						</select><span id="project_type_nr_validate1" style="color:red;"></span>
					
					</td>
				</tr>
				<tr>
				   <td colspan="1"><span class="quote_field">Quote:</span></td><td colspan="1">
				   <input type="number" id="quoted_amount" name="quoted_amount" min="1" max="100000" step="0.1" class="quote_field">		
				   <span id="quoted_amount_validate1" style="color:red;"></span>		
					</td>
				</tr>
				<tr>
				   <td colspan="1" ><span class="division_field"> Division:</span></td><td colspan="1" >
					   <select name="company_division" id="company_division" class="division_field">							        							
							<!-- <option value="0"> Human Capital </option>	   
							<option value="1"> Corporate Services </option>	 
							<option value="2"> Group Comms  </option>	 
							<option value="3"> BCM </option>	 
							<option value="4"> D&TS </option>	 
							<option value="5"> Ethics & Compliance  </option>	 
							<option value="6"> Taxation </option>	 
							<option value="7"> Legal </option>	 
							<option value="8"> Construction Management </option>	 														
							<option value="9"> Finance </option>
							<option value="10"> Portfolio Strategy </option>
							<option value="11"> Treasury Investor </option>
							<option value="12"> Government Affairs </option>
							<option value="13"> Emiratization</option>
							<option value="14"> ERM </option>
							<option value="15"> Responsible Investing </option>
							<option value="16"> Group Strategy </option>
							<option value="17"> UAE Investments </option>
							<option value="18"> Internal Audit </option>
							<option value="19"> Other </option> -->
						 </select>					
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="retainingContract" id="retainingContract" value="y">&nbsp;&nbsp;Retaining Customer</td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="consolidatedBillingCustomer" id="consolidatedBillingCustomer" value="y">&nbsp;&nbsp;Consolidated Billing Customer</td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="approval" id="approval" value="y" >&nbsp;&nbsp;Approved</td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="invoiced" id="invoiced" value="y" >&nbsp;&nbsp;Invoiced</td>
				</tr>
				<tr>
					<td colspan="2"><input type="hidden" id="token" class="token1" name="token"><input class="btn" type="button" value="Add" onClick="JavaScript: return validate1();"></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="tab-pane" id="retainingC">
		<h2>Retaining Customers</h2>
		<?php echo form_open('submit-retainer-job', array('class' => 'form-add-retainer-job','id' => 'form-add-retainer-job','autocomplete'=>'off')); ?>
		<table>	   
			<tr>
				<td>Client:</td><td>    
					<select name="clientR" id="clientR" onchange="getRetainerJobSeqNo(this.value)">
						<option value="select"> Select Client</option>           
						<?php 
							$i=0;
							foreach($retainingClients as $key => $row):
						?>
						<option value="<?php echo $key;?>"> <?php echo $row;?></option>	   
						<?php 
						endforeach;  ?>		 
					</select> <span id="client_validate2" style="color:red;"></span>
				</td>
			</tr>
			<tr>
				<td>Date:</td><td><input type="text" name="dateR" id="dateR" onchange="getRetainerJobSeqNo()"><span id="date_validate2" style="color:red;"></span></td>
			</tr>
			<tr>
				<td>Job no.:</td><td><span id="show_jobnoR"></span><input type="hidden" name="jobnoR" id="jobnoR"  ></td>
			</tr>
			<tr>
				<td>Job name:</td><td><input type="text" name="jobnameR" id="jobnameR" ><span id="jobnameR_validate2" style="color:red;"></span></td>
			</tr>	
			<tr>
				<td>Description:</td><td><span id="descriptionConsolidatedretainer-show"></span><input type="text" name="descriptionR" id="descriptionR" ><span id="desc_validate2" style="color:red;"></span></td>
			</tr>
			<tr>
				<td>Project type:</td><td>
					<select name="project_type_rc[]" id="project_type_rc" multiple="multiple">
							        
							<?php $i=0;
							//print_r($project_types);
								foreach($project_types as $key => $row):
							?>
							<option value="<?php echo $row['id'];?>"> <?php echo $row['project_type'];?></option>	   
							<?php 
							endforeach;  ?>		
							
						</select><span id="project_type_rc_validate1" style="color:red;"></span>
					
					</td>
				</tr>
				<tr>
				   <td colspan="1"><span class="retainer_quote_field">Quote:</span></td><td colspan="1">
				   <input type="number" id="retainer_quoted_amount" name="quoted_amount" min="1" max="100000" step="0.1" class="retainer_quote_field">		
				   <span id="quoted_amount_validate2" style="color:red;"></span>		
					</td>
				</tr>
				<tr>
				   <td colspan="1" ><span class="retainer_division_field"> Division:</span></td><td colspan="1" >
					   <select name="company_division" id="retainer_company_division" class="retainer_division_field">							        							
							<!-- <option value="0"> Human Capital </option>	   
							<option value="1"> Corporate Services </option>	 
							<option value="2"> Group Comms  </option>	 
							<option value="3"> BCM </option>	 
							<option value="4"> D&TS </option>	 
							<option value="5"> Ethics & Compliance  </option>	 
							<option value="6"> Taxation </option>	 
							<option value="7"> Legal </option>	 
							<option value="8"> Construction Management </option>	 														
							<option value="9"> Finance </option>
							<option value="10"> Portfolio Strategy </option>
							<option value="11"> Treasury Investor </option>
							<option value="12"> Government Affairs </option>
							<option value="13"> Emiratization</option>
							<option value="14"> ERM </option>
							<option value="15"> Responsible Investing </option>
							<option value="16"> Group Strategy </option>
							<option value="17"> UAE Investments </option>
							<option value="18"> Internal Audit </option>
							<option value="19"> Other </option> -->
						 </select>					
					</td>
				</tr>
			<tr>
				<td colspan="2"><input type="checkbox" name="retainerscope" id="retainerscope" value="y">&nbsp;&nbsp;Scope of Work</td> <!-- just to confirm if the job falls under retaining contract -->
			</tr>
			<tr>
				<td colspan="2"><input type="checkbox" name="billable" id="billable" value="y">&nbsp;&nbsp;Billable</td> 
			</tr>
			<tr>
				<td colspan="2"><input type="checkbox" name="approval" id="approval" value="y" >&nbsp;&nbsp;Approved</td>
			</tr>
			<tr>
				<td colspan="2"><input type="checkbox" name="invoiced" id="invoiced" value="y" >&nbsp;&nbsp;Invoiced</td>
			</tr>
			<tr><input type="hidden" name="monthly_consol_jobno_for_retainers" id="monthly_consol_jobno_for_retainers"> 
						<td colspan="2" class="addto_consolidated">
						<input type="checkbox" name="addto_consolidated" id="addto_consolidated" value="y" >&nbsp;&nbsp;Add to consolidated job no.</td>
					</tr>
			<tr>
				<td colspan="2"><input type="hidden" id="token" class="token2" name="token"><input class="btn" type="button" value="Add" onClick="JavaScript: return validate2();"></td>
			</tr>
		</table>
	</form>
</div>
<div class="tab-pane" id="consolidatedJobsC">
		<h2>Consolidated billing Customers</h2>
		<?php echo form_open('submit-consolidatedbillingC-job', array('class' => 'form-add-consolidated-billingC-job','id' => 'form-add-consolidated-billingC-job','autocomplete'=>'off')); ?>
		<table>	   
			<tr>
				<td>Client:</td><td>    
					<select name="clientConsolidatedB" id="clientConsolidatedB" onchange="getConsolidatedBillingCJobSeqNo(this.value)">
						<option value="select"> Select Client</option>           
						<?php 
							$i=0;
							foreach($consolidatedBClients as $key => $row):
						?>
						<option value="<?php echo $key;?>"> <?php echo $row;?></option>	   
						<?php 
						endforeach;  ?>		 
					</select> <span id="client_validate4" style="color:red;"></span>
				</td>
			</tr>
			<tr>
				<td>Date:</td><td><input type="text" name="dateConsolidatedBillingC" id="dateConsolidatedBillingC" onchange="getConsolidatedBillingCJobSeqNo()"><span id="date_validate4" style="color:red;"></span></td>
			</tr>
			<tr>
				<td>Job no.:</td><td><span id="show_jobnoConsolidatedJob"></span><input type="hidden" name="jobnoConsolidatedBillingC" id="jobnoConsolidatedBillingC"  ></td>
			</tr>
			<tr>
				<td>Job name:</td><td><input type="text" name="jobnameConsolidatedBillingC" id="jobnameConsolidatedBillingC" ><span id="jobnameconsolidatedB_validate4" style="color:red;"></span></td>
			</tr>	
			<tr>
				<td>Description:</td><td><span id="descriptionConsolidatedbillingC-show"></span><input type="text" name="descriptionConsolidatedBillingC" id="descriptionConsolidatedBillingC" ><span id="desc_consolidated_bc" style="color:red;"></span></td>
			</tr>
			<tr>
				<td>Project type:</td><td>
					<select name="project_type_ccb[]" id="project_type_ccb" multiple="multiple">

							<?php $i=0;
							//print_r($project_types);
								foreach($project_types as $key => $row):
							?>
							<option value="<?php echo $row['id'];?>"> <?php echo $row['project_type'];?></option>	   
							<?php 
							endforeach;  ?>		

						</select><span id="project_type_ccb_validate1" style="color:red;"></span>

					</td>
				</tr>
				<tr>
				   <td colspan="1"><span class="consolidated_quote_field">Quote:</span></td><td colspan="1">
				   <input type="number" id="consol_quoted_amount" name="quoted_amount" min="1" max="100000" step="0.1" class="consolidated_quote_field">
				   <span id="quoted_amount_validate4" style="color:red;"></span>				
					</td>
				</tr>
				<tr>
				   <td colspan="1" ><span class="consolidated_division_field"> Division:</span></td><td colspan="1" >
					   <select name="company_division" id="consol_company_division" class="consolidated_division_field">							        							
							<!-- <option value="0"> Human Capital </option>	   
							<option value="1"> Corporate Services </option>	 
							<option value="2"> Group Comms  </option>	 
							<option value="3"> BCM </option>	 
							<option value="4"> D&TS </option>	 
							<option value="5"> Ethics & Compliance  </option>	 
							<option value="6"> Taxation </option>	 
							<option value="7"> Legal </option>	 
							<option value="8"> Construction Management </option>	 														
							<option value="9"> Finance </option>
							<option value="10"> Portfolio Strategy </option>
							<option value="11"> Treasury Investor </option>
							<option value="12"> Government Affairs </option>
							<option value="13"> Emiratization</option>
							<option value="14"> ERM </option>
							<option value="15"> Responsible Investing </option>
							<option value="16"> Group Strategy </option>
							<option value="17"> UAE Investments </option>
							<option value="18"> Internal Audit </option>
							<option value="19"> Other </option> -->
						 </select>					
					</td>
				</tr>
			<tr>
				<td colspan="2"><input type="checkbox" name="ccb_billable" id="ccb_billable" value="y">&nbsp;&nbsp;Billable</td> 
			</tr>
			<tr>
				<td colspan="2"><input type="checkbox" name="ccb_approval" id="ccb_approval" value="y" >&nbsp;&nbsp;Approved</td>
			</tr>
			<tr>
				<td colspan="2"><input type="checkbox" name="ccb_invoiced" id="ccb_invoiced" value="y" >&nbsp;&nbsp;Invoiced</td>
			</tr>
			<!-- <tr><input type="hidden" name="monthly_consol_jobno_for_retainers" id="monthly_consol_jobno_for_retainers"> 
						<td colspan="2" class="addto_consolidated">
						<input type="checkbox" name="addto_consolidated" id="addto_consolidated" value="y" >&nbsp;&nbsp;Add to consolidated job no.</td>
					</tr> -->
			<tr>
				<td colspan="2"><input type="hidden" id="token" class="token4" name="token"><input class="btn" type="button" value="Add" onClick="JavaScript: return validate4();"></td>
			</tr>
		</table>
	</form>
</div>
<div class="tab-pane" id="ekscjobs">
	<h2>EKSC Jobs</h2>
	<?php echo form_open('submit-ekretainer-job', array('class' => 'form-add-ekretainer-job','id' => 'form-add-ekretainer-job','autocomplete'=>'off')); ?>
	<table>	   
	    <tr>
			<td>Client:</td><td>    
				<select name="clientEK" id="clientEK" >
					<option value="35"> Emirates Skycargo</option>           		 
				</select> <span id=""></span>
			</td>
		</tr>
        <tr>
			<td>Date:</td><td><input type="text" name="dateEK" id="dateEK" onchange="getEKRetainerJobSeqNo()"><span id="date_validate3" style="color:red;"></td>
			</tr>
			<tr>
				<td>Job no.:</td><td><span id="show_jobnoEK"></span><input type="hidden" name="jobnoEK" id="jobnoEK"  ></td>
			</tr>	
			<tr>
				<td>Job name:</td><td><input type="text" name="jobnameEK" id="jobnameEK" ><span id="jobnameEK_validate3" style="color:red;"></td>
				</tr>		
				<tr>
					<td>Description:</td><td><span id="descriptionEK-show"></span><input type="text" name="descriptionEK" id="descriptionEK" ><span id="desc_validate3" style="color:red;"></td>
					</tr>
						<tr>
				<td>Project type:</td><td>
					<select name="project_type_ekr[]" id="project_type_ekr" multiple="multiple">
							        
							<?php $i=0;
							//print_r($project_types);
								foreach($project_types as $key => $row):
							?>
							<option value="<?php echo $row['id'];?>"> <?php echo $row['project_type'];?></option>	   
							<?php 
							endforeach;  ?>		
							
						</select><span id="project_type_ekr_validate1" style="color:red;"></span>
					
					</td>
				</tr>
				<tr>
				   <td colspan="1"><span class="ekretainer_quote_field">Quote:</span></td><td colspan="1">
				   <input type="number" id="ekretainer_quoted_amount" name="quoted_amount" min="1" max="100000" step="0.1" class="ekretainer_quote_field">
				   <span id="quoted_amount_validate3" style="color:red;"></span>				
					</td>
				</tr>
				<tr>
				   <td colspan="1" ><span class="ekretainer_division_field"> Division:</span></td><td colspan="1" >
					   <select name="company_division" id="ekretainer_company_division" class="ekretainer_division_field">	
					</td>
				</tr>
					<tr>
						<td colspan="2"><input type="checkbox" name="ekscope" id="ekscope" value="y">&nbsp;&nbsp;Scope of Work</td> <!-- just to confirm if the job falls under retaining contract -->
					</tr>
					<tr>
						<td colspan="2"><input type="checkbox" name="ekbillable" id="ekbillable" value="y">&nbsp;&nbsp;Billable</td>
					</tr>
					<tr>
						<td colspan="2"><input type="checkbox" name="ekapproval" id="ekapproval" value="y" >&nbsp;&nbsp;Approved</td>
					</tr>
					<tr>
						<td colspan="2"><input type="checkbox" name="ekinvoiced" id="ekinvoiced" value="y" >&nbsp;&nbsp;Invoiced</td>
					</tr>
					<tr><input type="hidden" name="monthly_consol_jobno" id="monthly_consol_jobno">
						<td colspan="2" class="under750_cont">
						<input type="checkbox" name="under750" id="under750" value="y" >&nbsp;&nbsp;Under 2750</td>
					</tr>
					<tr>
						<td colspan="2"><input type="hidden" id="token" class="token3" name="token"><input class="btn" type="button" value="Add" onClick="JavaScript: return validate3();"></td>
					</tr>
				</table>
			</form>
		</div>
        
	</div>
</div>


</div>