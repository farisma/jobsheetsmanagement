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
					
					<option value="<?php echo $rows;?>" <?php if($userselected == $rows) echo "selected";?>><?php echo $values;?></option>
				<?php } ?>
			</select><input class="btn" type="submit" value="View" ></td>
			<td><a href="<?php echo site_url('vacation/calendarview') ?>">Go to calendar view</a></td>
		</tr>
	</table>
</form>

<div class="vac-col-1">
    
	<?php 
	$currentYear = date("Y");
		echo "<table>";
		/*Creating an array that contains all the vacation dates per person, so that it can be used later for traversing*/
		//print_r($noofannualleavesforperson);
		// echo "<pre>";
		// print_r($vacdates);
		// echo "</pre>";
		foreach($vacdates as $rows => $values) {
			
			$person = $rows;
			// echo "<pre>";
			// print_r($person);
			// print_r($values);
			// echo "</pre>";
			//echo $noofannualleavesforperson[$person]."<br>";
			//echo gettype($values);
			$count = is_null($values)?0:count($values);
			// $count = count($values);
		    //	echo "count".$count;
			$no_of_days = 0;
			$no_of_sickdays = 0;
			$no_of_unpaiddays = 0;
			$no_of_casualdays = 0;
	
			if($count>0){
				for($i=0;$i<$count;$i++) {
					$startdateperperson = $vacdates[$rows][$i]['startdate'];
					$enddateperperson = $vacdates[$rows][$i]['enddate'];
					$checkvacationtype = $vacdates[$rows][$i]['vacationtype'];
					$period = new DatePeriod(new DateTime($startdateperperson), new DateInterval('P1D'), new DateTime($enddateperperson. "+ 1 Day"));
					/* To find the number of annual days, sick days and all other leaves*/
					/* Its added to an array for further use in calendar view inorder to highlight date*/
					if($checkvacationtype == "Annual")
					{
						$no_of_days+=  $vacdates[$rows][$i]['noofdays'];
						
						foreach ($period as $dateperperson) {
							
							$datesperperson[$person][] = $dateperperson->format("Y-m-d");
				
						}
					}
					if($checkvacationtype == "Sick")
					{
						$no_of_sickdays+=  $vacdates[$rows][$i]['noofdays'];
						foreach ($period as $dateperperson) {
							
							$sickdatesperperson[$person][] = $dateperperson->format("Y-m-d");
						}
					}
					if($checkvacationtype == "Casual")
					{
						$no_of_casualdays+=  $vacdates[$rows][$i]['noofdays'];
						foreach ($period as $dateperperson) {
							
							$casualdatesperperson[$person][] = $dateperperson->format("Y-m-d");
						}
					}
					if($checkvacationtype == "Unpaid")
					{
						$no_of_unpaiddays+=  $vacdates[$rows][$i]['noofdays'];
						foreach ($period as $dateperperson) {
							
							$unpaiddatesperperson[$person][] = $dateperperson->format("Y-m-d");
						}
					}
					//echo $no_of_annual_days_currentyear."<br>";
				}
				
			}
			
			$no_of_day_person[$person] =  $noofannualleavesforperson[$person];//No of annual days for person for curernt year;	
			//echo "NO:".$no_of_days;
			/*$no_of_sickdays_day_person[$person] =  $no_of_sickdays;		
			$no_of_casual_day_person[$person] =  $no_of_casualdays;	
			$no_of_unpaid_day_person[$person] =  $no_of_unpaiddays;	*/
		}
		
		$currentmonth = date("m");
		
		
		for($k=1;$k <= 12;$k++) {
			// echo $k;
			$date_cur = date('Y-'.$k.'-01');
			
			$monthsinayear_dates[$k] = $date_cur;
			$monthsinayear[$k]=strtotime($date_cur);
			$noofdays_currentmonth[$k] = cal_days_in_month(CAL_GREGORIAN, $k, date("Y"));
			
			
		}
		
		
		
		/*creating date of a month and highlight the dates that are included in vacation dates*/
		$vacdetails = "";
		$flag = 0;
		//print_r($vacdates);
		foreach($vacdates as $rows => $values) {
			// print_r($datesperperson);
			$person = $rows;
			$firstname = is_null($values)?'':$values[0]['first_name'];
			$annualleavecount = 0;
			$unpaiddatescount = 0;
			$sickdatescount = 0;
			$casualdatescount = 0;
			if(isset($datesperperson[$person])) /*Annual leaves*/
			
			$annualleavecount = count($datesperperson[$person]);
			
			if(isset($unpaiddatesperperson[$person]))
			$unpaiddatescount = count($unpaiddatesperperson[$person]);
			
			if(isset($casualdatesperperson[$person]))
			$casualdatescount = count($casualdatesperperson[$person]);
			
			if(isset($sickdatesperperson[$person]))
			$sickdatescount = count($sickdatesperperson[$person]);
			if($firstname!='') {
			$vacdetails.= "<tr>";
			$vacdetails.= "<td>".$firstname."</td><td>".$no_of_day_person[$person]."</td>";
			
			for($j=1;$j<=12;$j++) // months in current year
			{
				$vacdetails.= "<td>";
				
				for($i=1;$i<=$noofdays_currentmonth[$j];$i++) { // days in each month
			        $m = sprintf("%02d",$j);
					$day = sprintf("%02d",$i);
					$y = date('Y');
					$dateformated = $y.'-'.$m.'-'.$day;
					
					$addclass ="red";
					if(isset($annualleavecount) && $annualleavecount > 0) {
						if(in_array($dateformated, $datesperperson[$person])){ // if the date is included in vacation dates
							
							
							$addclass = "highlighted";
						}
					}
					if(isset($sickdatescount) && $sickdatescount > 0){
						if(in_array($dateformated, $sickdatesperperson[$person])){ // if the date is included in sick dates
							
							
							$addclass = "sick";
						}
					}
					if(isset($unpaiddatescount) && $unpaiddatescount >0)
					{
						if(in_array($dateformated, $unpaiddatesperperson[$person])){ // if the date is included in unpaid dates
							
							
							$addclass = "unpaid";
						}
					}
					if(isset($casualdatescount) && $casualdatescount > 0)
					{
						if(in_array($dateformated, $casualdatesperperson[$person])){ // if the date is included in casual dates
							
							
							$addclass = "casual";
						}
					}
					if(in_array($dateformated, $listholidays)){ // if the date is included in official holiday dates
						
						
						$addclass = "officialholiday";
					}
					
					$vacdetails.= "<div title='".$dateformated."' class='datecolumns ".$addclass."'></div>";
					
				}
				$vacdetails.= "</td>";
			}
			$vacdetails.= "</tr>";
		}
			$flag++;
		}
		
		
		
		$vacdetails.= "</table>";
		
	?>
	<?php ?>
	<?php if(isset($vacationdetailsbyid)) { ?>
		<?php foreach($vacationdetailsbyid as $rows => $values) {
		    $vacdet[$rows]['employee_id']  = $values['employee_id'];
		    $vacdet[$rows]['username']  = $values['username'];
		    $vacdet[$rows]['startdate'] = $values['startdate'];
			$vacdet[$rows]['enddate']   = $values['enddate'];
			$vacdet[$rows]['noofdays']  = $values['noofdays'];
			$vacdet[$rows]['vacationtype'] = $values['vacationtype'];
			
			
			$period = new DatePeriod(new DateTime($vacdet[$rows]['startdate']), new DateInterval('P1D'), new DateTime($vacdet[$rows]['enddate']. "+ 1 Day"));
			foreach ($period as $date) {
				$dates[] = $date->format("Y-m-d");
			}
		?>
		
		<?php } 
		
		} 	
		if((isset($dates) && count($dates) == 0) || !(isset($dates))) {
		
		$dates = "";
		}
		
		?>
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
		<div class="leavetypes holiday_official">
		
		</div>
		<div class="leavetype">
		Official Holidays
		</div>
		</div>
		<div style="clear:both;"></div>
		<table class="vacationdetails">
		<tr>
		
		<tr>
		<th style="text-align:left;">
		User
		</th>
		<th style="text-align:left;">
		Days (Annual)
		</th>
		<?php foreach($monthsinayear as $rows=>$values) {
		?>
		<th style="text-align:left;width:190px;">
		<?php  echo date('M,Y',$values);?>
		</th>
		<?php 
		}?>
		
		
		</tr>
		<?php if(isset($vacdetails)) echo $vacdetails;?>
		</table>
		
		</div>
		<!--<div class="vac-col-2">
	    <div id="calendar-container">
		<div id="calendar-header">
		<span id="calendar-month-year"></span>
		</div>
		
        </div>
		</div>-->
		</div>
		</div>
		
		<script type="text/javascript">
		window.onload = function(){
		
		getNextMonths();
		
		}
		
		function getNextMonths() {	
		/* vacation dates for selected user*/
		var datesofvac = <?php echo json_encode($dates); ?>;
		console.log(datesofvac);
		var d = new Date();
		var month_name = ['January','February','March','April','May','June','July','August','September','October','November','December'];
		var month = d.getMonth();   //0-11
		var year = d.getFullYear(); //2014
		var first_date = month_name[month] + " " + 1 + " " + year;
		var tmp = new Date(first_date).toDateString();
		//Mon Sep 01 2014 ...
		var first_day = tmp.substring(0, 3);    //Mon
		var day_name = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
		var day_no = day_name.indexOf(first_day);   //1
		
		var days = new Date(year, month+1, 0).getDate();    //30
		
		var calendar = get_calendar(day_no, days,month+1,year,datesofvac);
		var head_div = document.createElement('div');
		head_div.setAttribute("id","monthtitle");
		var cal_container = document.createElement('div');
		cal_container.setAttribute("id","calendar-dates");
		document.getElementById("calendar-container").appendChild(cal_container);
		
		document.getElementById("calendar-dates").appendChild(head_div);
		document.getElementById("monthtitle").innerHTML =month_name[month]+" "+year;;
		document.getElementById("calendar-dates").appendChild(calendar);
		var month_next,year_next, firstdate_nextmonth, tmp_nextmonth,firstday_nextmonth,dayno_nextmonth,days_next_month,calendar_next;
		var dates = [first_date];
		for(i=1;i<=2;i++){
		
		if(month == 11)
		{ 
		month_next = 0;
		year_next = parseInt(year) + parseInt(i);
	    }
	    else {
		month_next = parseInt(month) + parseInt(i);
		year_next = year;
		}
		var header = month_name[month_next]+" "+year_next;
		//next_month_dayno = new;
		firstdate_nextmonth =  month_name[month_next] + " " + 1 + " " + year_next;
		tmp_nextmonth = new Date(firstdate_nextmonth).toDateString();
		firstday_nextmonth =  tmp_nextmonth.substring(0, 3);    //Mon
		dayno_nextmonth = day_name.indexOf(firstday_nextmonth);
		days_next_month = new Date(year_next, month_next+1, 0).getDate();
		
		calendar_next = get_calendar(dayno_nextmonth, days_next_month,month_next+1,year_next,datesofvac);
		
		
	    var head_div = document.createElement('div');
	    head_div.setAttribute("id","monthtitle"+i);
	    var cal_containernext = document.createElement('div');
	    cal_containernext.setAttribute("id","calendar-dates"+i);
		
		document.getElementById("calendar-container").appendChild(cal_containernext);
		document.getElementById("calendar-dates"+i).appendChild(head_div);
		document.getElementById("monthtitle"+i).innerHTML =header;
		document.getElementById("calendar-dates"+i).appendChild(calendar_next);
		}
	    
		
		
		}
		
		function get_calendar(day_no, days, thismonth, thisyear,dates){
		
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
		
		if(dates.indexOf(finaldate) != -1)
		{
		
		td.setAttribute("class","highlight");
		}
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
		
		if(dates.indexOf(finaldate) != -1)
		{
		
		td.setAttribute("class","highlight");
		}
		tr.appendChild(td);
        }
        table.appendChild(tr);
		}
		return table;
		}
		</script>		