  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
   <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script> -->
   <script src="//code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
  <script>
  $(document).ready(function(){         
    $("#searchstatusreport").hide()
    $("#searchclosedjobsreport").hide()
     // function to populate dropdown with last 12 months 
	   dateselector =  $("#reportdate");
     statusreportdate = $("#statusreportdate");
     const monthNames = ["January", "February", "March", "April", "May", "June", 
                                "July", "August", "September", "October", "November", "December"];            
            // Get current date
            const currentDate = new Date();            
            // Populate dropdown with previous 12 months
            for (let i = 0; i <= 11; i++) {
                // Calculate month index (0 = January, 11 = December)
                const monthIndex = (currentDate.getMonth() - i + 12) % 12;
                // Calculate the year
                const year = currentDate.getFullYear() - (i >= currentDate.getMonth() ? 1 : 0);
                // Get the month name and year
                const monthName = monthNames[monthIndex];
                // Add option to dropdown
                dateselector.append(`<option value="${monthIndex + 1} ${year}">${monthName} ${year}</option>`);
                statusreportdate.append(`<option value="${monthIndex + 1} ${year}">${monthName} ${year}</option>`);
            }
	  });

    function printJobs(jobs,retainer) {
      console.log(jobs.length)
      var retainertable = $("#retainerjobslist"),
          nonretainertable = $("#nonretainerjobslist"),          
          nonretainertitle = $("#nonretainertitle"),
          retainertitle = $("#retainertitle"),
          status;
          if(retainer) {
            title = retainertitle;
            table = retainertable
            titleText = "Retainer Jobs"
          }
          else {
            title = nonretainertitle;
            table = nonretainertable
            titleText = 'Non Retainer Jobs';
          }
                title.empty();
                title.text(titleText)
                table.empty()
                table.append('<tr><th>Sl no.</th><th colspan="1">Job</th><th colspan="1">Division</th><th colspan="1">Job Name</th><th colspan="1">Description</th><th colspan="1">Date</th><th colspan="1">Amount</th><th>Status</th></tr>');
                var i = 1; 
                $.each(jobs, function(index, value) {
                   console.log(value)
                  $.each(value, function(index, value2) {
                    //console.log(value2)
                    if(value2.jobclosed == 'n' && value2.invoiced == 'n')
                       status = "WIP";
                    else if(value2.jobclosed == 'y' && value2.invoiced == 'n')
                       status = "Closed";
                    else if (value2.jobclosed == 'y' && value2.invoiced == 'y')
                       status = "Invoiced";
                    table.append(
                      '<tr><td>'+i+'</td><td>'+value2.jobno+'</td><td>'+value2.division+'</td><td>'+value2.jobname+'</td><td>'+value2.description+'</td><td>'+value2.date+'</td><td>'+value2.quote+'</td><td>'+status+'</td></tr>'
                    )
                    i++;
                  });
                });
    }

    function printClosedJobs(jobs,retainer){
      var retainertable = $("#closedjobsretainerjobslist"),
          nonretainertable = $("#closedjobsnonretainerjobslist"),          
          nonretainertitle = $("#closednonretainertitle"),
          retainertitle = $("#closedretainertitle"),
          status;
          console.log("JOBS",jobs)
      if(jobs.length > 0) {      
       
      
          if(retainer) {
            title = retainertitle;
            table = retainertable
            titleText = "Retainer Jobs"
          }
          else {
            title = nonretainertitle;
            table = nonretainertable
            titleText = 'Non Retainer Jobs';
          }
                title.empty();
                title.text(titleText)
                table.empty()
                table.append('<tr><th>Sl no.</th><th colspan="1">Job</th><th colspan="1">Division</th><th colspan="1">Job Name</th><th colspan="1">Description</th><th colspan="1">Date</th><th colspan="1">Amount</th><th>Status</th></tr>');
                var i = 1;
                $.each(jobs, function(index, value) { 
                  table.append(
                      '<tr><td>'+i+'</td><td>'+value.jobno+'</td><td>'+value.division+'</td><td>'+value.jobname+'</td><td>'+value.description+'</td><td>'+value.date+'</td><td>'+value.quote+'</td><td>'+"Closed"+'</td></tr>'
                    )
                    i++;
                });
        }
       
    }

    function fetchJobs(jobs,retainer){
               if(Array.isArray(jobs)) {
                    if(jobs.length > 0) {          
                      printJobs(jobs, retainer);
                    }
               }
               //if it has multiple divisions, the return value will be an object with multiple arrays in it
               else if(!Array.isArray(jobs) && typeof(jobs) == "object") {
                var newArr=[];
                for (let key in jobs) {
                    if (jobs.hasOwnProperty(key)) {
                        // Loop through each array if needed
                        if(jobs[key] != null) {
                          newArr.push(jobs[key])                                                    
                         }
                    }
                }
                printJobs(newArr, retainer);
               }
    }
    function getJobs() {
      clearScreen();
      var searchbtn = $("#searchstatusreport");
      var clientid = $("#statusreportform_cientid").val(),
          statusreport_date = $("#statusreportdate").val();
      var statusreport_closed = $('#statusreportclosed').prop('checked')? $("#statusreportclosed").val():false;
      var statusreport_invoiced = $('#statusreportinvoiced').prop('checked')? $("#statusreportinvoiced").val():false;
      

      var retainerjobs,nonretainerjobs;
      //this is for passing parameters to export function to generate excel report by setting up selected parameters in hidden variables
      $("#statusreport_cientid").val(clientid)
      $("#statusreport_closed").val(statusreport_closed)
      $("#statusreport_invoiced").val(statusreport_invoiced)
      $("#statusreport_date").val(statusreport_date)
      jQuery.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>" + "index.php/findstatusreport/",
					data: $('#form-find-status-report').serialize(), 
				  dataType: "json",
					success: function(res) {
						if (res)
						{              
              console.log("al",res)            
              nonretainerjobs = res.nonretainerjobs;
              retainerjobs = res.retainerjobs
              if(retainerjobs != null) fetchJobs(retainerjobs,true);
              if(nonretainerjobs != null) fetchJobs(nonretainerjobs,false);
              searchbtn.show()
             
						}
						else 
						{
              searchbtn.hide()
							
						}
					},
          error: function(jqXHR, textStatus, errorThrown) {
            searchbtn.hide()
            console.log("Error:", textStatus, errorThrown);
            // You can also handle different status codes here
            if (jqXHR.status === 404) {
                console.log("404 Not Found");
            } else if (jqXHR.status === 500) {
                console.log("500 Internal Server Error");
            }
           }
		});
    }
    
    function getClosedJobs(){
     
       clearScreen();
      var searchbtn = $("#searchclosedjobsreport")
      var clientid = $("#closedjobsreport_clientid").val(),
      statusreport_date = $("#reportdate").val();
      var datesplit = statusreport_date.split(" ")
      var retainerjobs,nonretainerjobs;

      $("#closedjobsreportclientid").val(clientid)
      $("#closedjobsreportmonth").val(datesplit[0])
      $("#closedjobsreportyear").val(datesplit[1])
      jQuery.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>" + "index.php/findreport/",
					data: $('#form-find-report').serialize(), 
				  dataType: "json",
					success: function(res) {
						if (res)
						{              
              console.log("al",res)            
              nonretainerjobs = res.nonretainer;
              retainerjobs = res.retainerconsol
              console.log("al1 - retainer",res.nonretainer) 
              console.log("al2 - nonretainer",res.retainerconsol) 
              if(retainerjobs != null || retainerjobs != undefined) printClosedJobs(retainerjobs,true);
              if(nonretainerjobs != null || nonretainerjobs != undefined) printClosedJobs(nonretainerjobs,false);
             
              searchbtn.show()
             
						}
						else 
						{
              searchbtn.hide()
						
						}
					},
          error: function(jqXHR, textStatus, errorThrown) {
            searchbtn.hide()
            console.log("Error:", textStatus, errorThrown);
            // You can also handle different status codes here
            if (jqXHR.status === 404) {
                console.log("404 Not Found");
            } else if (jqXHR.status === 500) {
                console.log("500 Internal Server Error");
            }
           }
		});

         
    }
    function clearScreen() {
      $(".searchresultslist").empty();
      $(".exportbtn").hide();
      $(".reporttitle").empty();
    }
  </script>


  <?php $this->load->view("pages/adminmenu");?>
  <div class="hero-unit">
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
          <li class="active"><a href="#rechargereport" data-toggle="tab">Recharge Report</a></li>
          <li><a href="#statusreport" data-toggle="tab">Status Report</a></li>		
    </ul>
  <div id="my-tab-content" class="tab-content">
  <div class="tab-pane active" id="rechargereport">
  <?php echo form_open('findreport', array('class' => 'form-find-report','id' => 'form-find-report')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="2"><h2>Reports</h2></th></tr>
	    <tr>
		     <td>Client:</td>
         <td><select name="clientname" id="closedjobsreport_clientid">
         <?php 
								foreach($clients as $key => $row):
							?>
							<option value="<?php echo $key;?>"> <?php echo $row;?></option>	   
							<?php 
							endforeach;  ?>		
                <!-- <option value="18">Mubadala</option> -->
            </select>
		     </td>
		  </tr>
      <tr>
         <td>Date:</td>
         <td>
          <select id="reportdate" name="reportdate"></select>
         </td>
      </tr>
      <tr>
         <td><input class="btn" type="button" value="Get report" onClick="getClosedJobs();"></td>
      </tr>
	 </table>
	</form>
  </div>
  <div class="tab-pane" id="statusreport">
  <?php echo form_open('findstatusreport', array('class' => 'form-find-report','id' => 'form-find-status-report')) ?>
	 <table class="form-addclient">
	    <tr><th colspan="3"><h2>Reports</h2></th></tr>
	    <tr>
		   <td colspan="1">Client:</td>
       <td colspan="2">
        <select name="clientname" id="statusreportform_cientid">
        <?php
								foreach($clients as $key => $row):
							?>
							<option value="<?php echo $key;?>"> <?php echo $row;?></option>	   
							<?php 
							endforeach;  ?>		
            <!-- <option value="18">Mubadala</option> -->
        </select>
		  </td>
		  </tr>   
      <tr>
        <td colspan="1"><input type="checkbox" value='y' name="statusreportclosed" id="statusreportclosed"/> Closed </td>
        <td colspan="1"><input type="checkbox" value='y' name="statusreportinvoiced" id="statusreportinvoiced"/> Invoiced </td>
        <td colspan="1" style="padding-left:12px;"><select id="statusreportdate" name="statusreportdate"></select></td>
      </tr>    
      <tr>
        <td><input class="btn" type="button" value="Get report" onClick="getJobs();"></td>         
      </tr>
	 </table>
	</form>
  </div>
  </div>
</div>



	<?php  echo form_open('exportmonthlyreport', array('class' => 'searchresultslistform','id'=>'searchresultslistform')); ?>
   <input type="hidden" name="hiddenclientname" id="closedjobsreportclientid" value="<?php  if(isset($clientid)) echo $clientid; else echo 0;?>">
   <input type="hidden" name="hiddenmonth" id="closedjobsreportmonth" value="<?php  if(isset($month)) echo  $month;?>">
   <input type="hidden" name="hiddenyear" id="closedjobsreportyear" value="<?php  if(isset($year)) echo  $year;?>">

   <div id="closedjobsretainerresults">
           <h2 id="closedretainertitle" class="reporttitle"></h2>
           <table class="searchresultslist" id="closedjobsretainerjobslist"></table>
       </div>
       <div id="closedjobsnonretainerresults">
            <h2 id="closednonretainertitle" class="reporttitle"></h2>
            <table class="searchresultslist" id="closedjobsnonretainerjobslist"></table>
       </div>
   <div><input type="submit" id="searchclosedjobsreport" name="exportexcel" class="btn exportbtn" value="Export as Excel"></div>
  </form>


  <?php 
  
      //these variables are for populating hidden variables that is used in exporting report function
  // $clientid = $alljobsearchparams["clientid"];     
  echo form_open('exportmonthlyreportalljobs', array('class' => 'searchresultslistform','id'=>'searchresultslistform')); ?>
      <input type="hidden" name="hiddenclientname" id="statusreport_cientid" value="<?php  //if(isset($clientid)) echo $clientid; else echo 0;?>">
      <input type="hidden" name="hiddenclosed" id="statusreport_closed" value="<?php  //if(isset($clientid)) echo $clientid; else echo 0;?>">
      <input type="hidden" name="hiddeninvoiced" id="statusreport_invoiced" value="<?php  //if(isset($clientid)) echo $clientid; else echo 0;?>">
      <input type="hidden" name="hiddendate" id="statusreport_date" value="<?php  //if(isset($clientid)) echo $clientid; else echo 0;?>">

       <div id="retainerresults">
           <h2 id="retainertitle" class="reporttitle"></h2>
           <table class="searchresultslist" id="retainerjobslist"></table>
       </div>
       <div id="nonretainerresults">
            <h2 id="nonretainertitle" class="reporttitle"></h2>
            <table class="searchresultslist" id="nonretainerjobslist"></table>
       </div>
      
       
<div><input type="submit"  id="searchstatusreport" name="exportexcel" class="btn exportbtn" value="Export as Excel"></div>
</form>
  
