<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
  <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
 <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("input[name='date']").datepicker({dateFormat: "yy-mm-dd"});
});
</script> -->
<?php if(isset($message)) {?><div class="alert"> <?php echo $message;?></div><?php }?>
<?php $this->load->view("pages/adminmenu")?>


<div class="hero-unit">

<?php echo form_open('vacation/trackvacationbyuser', array('class' => 'form-add-job')) ?>
	 <table>
	    <tr><th colspan="2"><h2></h2></th></tr>
	    
		<tr>
		
		<td colspan="2"><select name="user" id="user">
		<?php foreach($users as $rows => $values) {?>
		<?php //foreach($rows as $key=>$values) {?>
		<option value="<?php echo $rows;?>" <?php if($userselected == $rows) echo "selected";?>><?php echo $values;?></option>
		<?php } //}?>
		</select><input class="btn" type="submit" value="View" ></td>
		</tr>
	 </table>
	</form>
	<div>
	<div class="leavetypes sickcol">
	
	</div>
	<div class="leavetype">
	Sick
	</div>
	<div class="leavetypes annualcol">
	
	</div>
	<div class="leavetype">
	Annual
	</div>
	<div class="leavetypes casualcol">
	
	</div>
	<div class="leavetype">
	Casual
	</div>
	<div class="leavetypes unpaidcol">
	
	</div>
	<div class="leavetype">
	Unpaid
	</div>

	</div>
	<div style="clear:both;"></div>
	<div class="vac-col-1">
	
	    <div id="calendar-container">
            <div id="calendar-header">
                <span id="calendar-month-year"></span>
            </div>
            
        </div>
	
	 <?php
		//$d[]=strtotime("+1 Months");
		//$d2=strtotime("+2 Months");

		//$current_month  = date("Y-m-d");
		//$next_month     = date("Y-m-d", $d);
		//$next_month2    = date("Y-m-d", $d2);
		
		//$thismonth_noofdays  =  cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
		//$nextmonth_noofdays  =  cal_days_in_month(CAL_GREGORIAN, date("m",$d), date("Y",$d));
		//$nextmonth_noofdays2 =  cal_days_in_month(CAL_GREGORIAN, date("m",$d2), date("Y",$d2));
		
	
		
	 ?>
	 <?php 
	   echo "<table>";
	   /*Creating an array that contains all the vacation dates per person, so that it can be used later for traversing*/

	//echo $currentmonth;
	
		//echo "yes";
		 for($k=1;$k <= 12;$k++) {

		          $date_cur = date('Y-'.$k.'-01');
		 
		          $monthsinayear_dates[$k] = $date_cur;
				  $monthsinayear[$k] = strtotime($date_cur);
				  $noofdays_currentmonth[$k] = cal_days_in_month(CAL_GREGORIAN, $k, date("Y"));
				  
				  
	  }	
	 ?>
	<?php //print_r($vacationdetailsbyid);?>
	<?php if(isset($vacationdetailsbyid)) {
    $no_of_days =0;
	$no_of_sickdays = 0;
	$no_of_casualdays=0;
	$no_of_unpaiddays =0;
	?>
	<?php 
	//print_r($vacationdetailsbyid);
	foreach($vacationdetailsbyid as $rows => $values) {
		    $vacdet[$rows]['employee_id']  = $values['employee_id'];
		    $vacdet[$rows]['username']  = $values['username'];
		    $vacdet[$rows]['startdate'] = $values['startdate'];
			$vacdet[$rows]['enddate']   = $values['enddate'];
			$vacdet[$rows]['noofdays']  = $values['noofdays'];
			$vacdet[$rows]['vacationtype'] = $values['vacationtype'];
			//echo $vacdet[$rows]['vacationtype'];
		        $checkvacationtype = $vacdet[$rows]['vacationtype'];
				$period = new DatePeriod(new DateTime($vacdet[$rows]['startdate']), new DateInterval('P1D'), new DateTime($vacdet[$rows]['enddate']. "+ 1 Day"));
				//print_r($period);
				//print_r($checkvacationtype);
				if($checkvacationtype == "Annual")
				 {
					 
					 $no_of_days+=  $vacdet[$rows]['noofdays'];
					 foreach ($period as $dateperperson) {
						
						$datesperperson[] = $dateperperson->format("Y-m-d");
					}
				 }
				 if($checkvacationtype == "Sick")
				 {
					$no_of_sickdays+=  $vacdet[$rows]['noofdays'];
					foreach ($period as $dateperperson) {
						
						$sickdatesperperson[] = $dateperperson->format("Y-m-d");
					}
				 }
				 if($checkvacationtype == "Casual")
				 {
					$no_of_casualdays+=  $vacdet[$rows]['noofdays'];
					foreach ($period as $dateperperson) {
						
						$casualdatesperperson[] = $dateperperson->format("Y-m-d");
					}
				 }
				 if($checkvacationtype == "Unpaid")
				 {
					$no_of_unpaiddays+=  $vacdet[$rows]['noofdays'];
					foreach ($period as $dateperperson) {
						
						$unpaiddatesperperson[] = $dateperperson->format("Y-m-d");
					}
				 }
				
			
			
		?>
	
	<?php }
//echo $unpaiddatesperperson;	
//echo $casualdatesperperson;	

if((isset($unpaiddatesperperson) && count($unpaiddatesperperson) == 0) || !(isset($unpaiddatesperperson))) {

	$unpaiddatesperperson = "";
}
if((isset($sickdatesperperson) && count($sickdatesperperson) == 0) || !(isset($sickdatesperperson))) {

	$sickdatesperperson = "";
}
if((isset($casualdatesperperson) && count($casualdatesperperson) == 0) || !(isset($casualdatesperperson))) {

	$casualdatesperperson = "";
}
if((isset($datesperperson) && count($datesperperson) == 0) || !(isset($datesperperson))) {

	$datesperperson = "";
}

	/*if(isset($datesperperson) && (count($datesperperson) == 0 || (!isset($datesperperson)))) {

	$datesperperson = "";
}
	if(isset($unpaiddatesperperson) && (count($unpaiddatesperperson) == 0 || (!isset($unpaiddatesperperson)))) {
echo "unpaid";
	$unpaiddatesperperson = "";
}
if(isset($sickdatesperperson) && (count($sickdatesperperson) == 0 || (!isset($sickdatesperperson)))) {

	$sickdatesperperson = "";
}
if(isset($casualdatesperperson) && (count($casualdatesperperson) == 0 || (!isset($casualdatesperperson)))) {
echo "casual";
	$casualdatesperperson = "";
}*/

	
	} 	
	
	?>


</div>

<script type="text/javascript">
   window.onload = function(){
   
   getNextMonths();
   
}

function getNextMonths() {	
/* vacation dates for selected user*/ 
//var datesofvac = ""; 
var datesofvac = <?php echo json_encode($datesperperson); ?>;
var casualleavedates = <?php echo json_encode($casualdatesperperson); ?>;
var sickleavedates = <?php echo json_encode($sickdatesperperson); ?>;
var unpaidleavedates = <?php echo json_encode($unpaiddatesperperson); ?>;
console.log(datesofvac);
	
	    var month_name = ['January','February','March','April','May','June','July','August','September','October','November','December'];
	var date_cur,monthsinayear,noofdays_currentmonth;
	var monthsinayear_dates = new Array();
	
    date_cur = new Date();	
	year_cur = date_cur.getFullYear();	
  for(var k=0;k <= 11;k++) {		         	
                 		  
		          monthsinayear_dates[k] = month_name[k] + " " + 1 + " " + year_cur;
				  var tmp = new Date(monthsinayear_dates[k]).toDateString();
				  //console.log(tmp);
				  
				  var first_day = tmp.substring(0, 3);    //Mon
				  var day_name = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
				  var day_no = day_name.indexOf(first_day);   //1
				  
				  var days = new Date(year_cur, k+1, 0).getDate();    //30
				  console.log(days);
				  var calendar = get_calendar(day_no, days,k+1,year_cur,datesofvac,casualleavedates,sickleavedates,unpaidleavedates);
				  
				  var head_div = document.createElement('div');
	head_div.setAttribute("id","monthtitle"+k);
	head_div.setAttribute("class","monthtitle");
	
	var cal_container = document.createElement('div');
	cal_container.setAttribute("id","calendar-dates-"+k);
	cal_container.setAttribute("class","calendar-dates");
	document.getElementById("calendar-container").appendChild(cal_container);
	
	document.getElementById("calendar-dates-"+k).appendChild(head_div);
	document.getElementById("monthtitle"+k).innerHTML =month_name[k]+" "+year_cur;
	document.getElementById("calendar-dates-"+k).appendChild(calendar);
				  
				  //monthsinayear[$k] = strtotime($date_cur);
				 
				 // noofdays_currentmonth[$k] = cal_days_in_month(CAL_GREGORIAN, $k, date("Y"));
				  
				  
	  }	
	 

}

function get_calendar(day_no, days, thismonth, thisyear,datesofvac,casualleavedates,sickleavedates,unpaidleavedates){
	
	var finaldate,thisday;
	
	if(thismonth <10)
		thismonth = "0"+thismonth;
	
  
    var table = document.createElement('table');
    var tr = document.createElement('tr');
    
    //row for the day letters
    for(var c=0; c<=6; c++){
        var td = document.createElement('td');
        td.innerHTML = "SMTWTFS"[c];
        tr.appendChild(td);
    }
    table.appendChild(tr);
    
    
    tr = document.createElement('tr');
    var c;
    for(c=0; c<=6; c++){
		
        if(c == day_no){
            break;
        }
        var td = document.createElement('td');
        td.innerHTML = "";
        tr.appendChild(td);
    }
    
    var count = 1;
    for(; c<=6; c++){
		
        var td = document.createElement('td');
        td.innerHTML = count;
        
		if(count <10)
		thisday = "0"+count;
	     else
	     thisday = count;
	    finaldate = thisyear+"-"+thismonth+"-"+thisday;
		var addclass2 = "";
		if(datesofvac.indexOf(finaldate) != -1)
		{		   
		addclass2 = "highlight";
		}
		if(casualleavedates.indexOf(finaldate) != -1)
		{		      
		addclass2 = "casual";
		}
if(sickleavedates.indexOf(finaldate) != -1)
		{
		addclass2 = "sick";
		}	
if(unpaidleavedates.indexOf(finaldate) != -1)
		{		       
		addclass2 = "unpaid";
		}			
		td.setAttribute("class",addclass2);
		
		count++;
        tr.appendChild(td);
		
    }
    table.appendChild(tr);
    
    //rest of the date rows
    for(var r=3; r<=7; r++){
		
        tr = document.createElement('tr');
        for(var c=0; c<=6; c++){
			
            if(count > days){
                table.appendChild(tr);
                return table;
				break;
            }
            var td = document.createElement('td');
            td.innerHTML = count;
            
			if(count <10)
		thisday = "0"+count;
	     else
	     thisday = count;
	 count++;
	    finaldate = thisyear+"-"+thismonth+"-"+thisday;
	var addclass = "";
		if(datesofvac.indexOf(finaldate) != -1)
		{		   
		addclass = "highlight";
		}
		if(casualleavedates.indexOf(finaldate) != -1)
		{		      
		addclass = "casual";
		}
		console.log(sickleavedates);
if(sickleavedates.indexOf(finaldate) != -1)
		{
		addclass = "sick";
		}	
if(unpaidleavedates.indexOf(finaldate) != -1)
		{		       
		addclass = "unpaid";
		}			
		td.setAttribute("class",addclass);
            tr.appendChild(td);
        }
        table.appendChild(tr);
    }
    return table;
}
</script>