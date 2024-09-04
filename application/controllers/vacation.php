<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Vacation extends App_Controller
	{
		public $b_emailid = "brandon@theandongroup.com"; 
		public $hr_emailid = ""; //lissy@theandongroup.com
		public $teamid = "andonteam@theandongroup.com";

		// public $b_emailid = "faris@theandongroup.com"; 
		// public $hr_emailid = ""; //lissy@theandongroup.com
		// public $teamid = "faris@theandongroup.com";
		
		public function __construct()
		{
			parent::__construct();
			if (!$this->ion_auth->logged_in())
			{
				redirect('login');
			}
			else
			{
		        if (!$this->ion_auth->is_admin())
				{
					$this->session->set_flashdata('notadmin', 'You must be an admin to view this page');
					redirect('home');
				}
				else {
					
					
					$user = $this->ion_auth->user()->row();
					$email = $user->email;
					if($user->id != 1 && $user->id != 4 && $user->id != 23){ /* Brandon Haider or Super admin*/
						$this->session->set_flashdata('notadmin', 'You are not allowed to view this page');
						redirect('home');
					}
				}
			}
			$this->load->model("Clients_model");
			$this->load->model("Vacation_model");
			$this->load->library('form_validation');
		}
		
		public function index()
		{
			
			$this->render_page('pages/vacationtracker');
			
		}
		public function adminPage()
		{	          
			$this->render_page('pages/admin');
		}
		public function getUsers() {
			
			$getUsers = $this->Vacation_model->getUsers();		
			return $getUsers; 
			
		}
		
		public function trackvacation()
		{
			
			$getvacationtypes = $this->Vacation_model->getVacationTypes();
			
			
			$this->body_class[] = 'vacationtracker';
			
			$this->page_title = 'Vacation Tracker';
			$this->current_section = 'Track vacaion';							
			$data['vacationtypes'] = $getvacationtypes;
			//get all users
			$users = $this->getUsers();
			//print_r($users);
			$data['users'] = $users;
			
			//for the list users with graph of their vacation dates across each month
			$getAll = $this->Vacation_model->getVacationdetails();
			$data['allvacation'] = $getAll; 
			$i=1;
			$currentYear = Date('Y');
			foreach($users as $r=>$s) {
				//print_r($r);
				//print_r($s);
				$username = $s;
				$getvacdates[$username] = $this->Vacation_model->getVacDates($r);
				
				$noofannualleaves = $this->Vacation_model->getCountOfAnnualLeaveDaysofcurrentYear($r,$currentYear);
				
				$noofannualdaysforthisyear[$username] = $noofannualleaves;
				//echo $r;
			}
			$data['noofannualleavesforperson'] = $noofannualdaysforthisyear;
			
			$data['vacdates'] = $getvacdates;
			foreach($getAll as $rows => $values)  {
				$un = $values['username'];
				$startdate = $values['startdate'];
				$enddate = $values['enddate'];
				$calculatedays = new DatePeriod(new DateTime($startdate), new DateInterval('P1D'), new DateTime($enddate. "+ 1 Day"));
				foreach ($calculatedays as $date) {
					$list_of_dates[$un][] = $date->format("Y-m-d");
				}			
			}		
			//print_r($list_of_dates);
			$data['list_of_dates'] = $list_of_dates;
			$user = $this->ion_auth->user()->row();
			
			$getvacationsbyid = $this->Vacation_model->getVacationdetailsbyId($user->id);
			$data['userselected'] =  $user->id;
			$data['vacationdetailsbyid'] = $getvacationsbyid; 
			$data['listholidays'] = $this->Vacation_model->listholidayDates();
			$this->render_page('pages/vacationtracker',$data);
		}
		
		/*
			Function calendarview
			Function to view vacation in a calendar format
		*/
		public function calendarview() {
			
			$users = $this->getUsers();
			//print_r($users);
			$data['users'] = $users;
			$data['userselected'] =  2; //default id is faris
			$getvacationsbyid = $this->Vacation_model->getVacationdetailsbyId(2);
			$data['vacationdetailsbyid'] = $getvacationsbyid; 
			$this->render_page('pages/calendarview',$data);
			
		}
		public function trackvacationbyuser()
		{
			$getAll = $this->Vacation_model->getVacationdetails();
			$data['allvacation'] = $getAll; 
			$users = $this->getUsers();
			$data['users'] = $users;
			foreach($users as $r=>$s) {
				
				$username = $s;
				$getvacdates[$username] = $this->Vacation_model->getVacDates($r);
				
			}
			
			
			$data['vacdates'] = $getvacdates;
			$user = $this->input->post('user');
			$getvacationsbyid = $this->Vacation_model->getVacationdetailsbyId($user);
			$data['vacationdetailsbyid'] = $getvacationsbyid; 
			
			
			$getvacationsbyid = $this->Vacation_model->getVacationdetailsbyId($user);
			$data['userselected'] =  $user;
			$data['vacationdetailsbyid'] = $getvacationsbyid; 
			$data['listholidays'] = $this->Vacation_model->listholidayDates();
			$this->render_page('pages/calendarview',$data);
		}
		
		
		
		public function addandApproveEmployeeleaves()
		{
			
			
			$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
			$data['users'] = $this->getUsers();
			
			$this->body_class[] = 'addleave';
			$this->page_title = 'Add employee leaves';
			$this->current_section = 'Add Leaves';
			$employee_id = $this->input->post('userid');
			$leaverequestid = $this->input->post('vacationrequestid');
			$startdate = $this->input->post('startdate');
			$enddate = $this->input->post('enddate');
			$noofdays = $this->input->post('noofdays'); 
		$vacationtype = $this->input->post('vacation_type'); 
		$notes = $this->input->post('notes'); 	
		
		//$insert = $this->Vacation_model->add_leavedatesbyemployee($employee_id,$startdate,$enddate,$noofdays, $vacationtype,$leaverequestid);
		
		$update = $this->Vacation_model->approveLeave($leaverequestid,$employee_id,$startdate,$enddate,$noofdays, $vacationtype, $notes);
		if($update)
		{
		$getvacationtype = $this->Vacation_model->getVacationTypebyId($vacationtype);
		$vactype = $getvacationtype['vacationtype'];
		$userdetails = $this->Vacation_model->getUserbyId($employee_id);
		
		$email = $userdetails['email'];
		$fn = $userdetails['first_name'];
		$ln = $userdetails['last_name'];
		
		$subject_cal = $fn." ".$ln." holidays";
		$desc_cal = $fn." ".$ln." holiday dates";
		$baseurl = base_url();
		$savetocalendarlink = $baseurl."index.php/savecalendar/".$startdate."/".$enddate."/".$subject_cal."/".$desc_cal;
		
		
		$emailText = "
		Dear ".$fn." ".$ln.",<br>
		Congratulations!!! Your request for leave from ".$startdate." to ".$enddate." has been approved.<br><br>";
		$emailToAllText = "Team,<br>"
		.$fn." ".$ln. " is on leave from ".$startdate." to ".$enddate.". <a href='".$savetocalendarlink."'>Click here</a> save it on your calendar.";
		
		$emailToAllId = $this->teamid;
		
		
		
		$from = $this->b_emailid; //"brandon@theandongroup.com";
		$to = $email;
		$cc = $this->hr_emailid; //"lissy@theandongroup.com";//lissy@theandongroup.com
		$bcc = "";
		$fullname = "Brandon Grieve";
		$subject = 'Leave approval notification';
		$send =$this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
		$sendtoall = $this->Vacation_model->sendemailnotification($from,$emailToAllId,$emailToAllText,$fullname,$cc,$bcc,$subject);
		if($send){
		$data['mailsent'] ="true";
		$mailsenttouser = "true";
		}
		else {
		$data['mailsent'] ="false";
		$mailsenttouser = "false";
		}
		
		$data['message'] = "true";
		redirect('vacation/leavemanagement?success=leaveconfirmed&mailsenttouser='.$mailsenttouser.'');
		}
		else 
		{
		$data['message'] = "false"; 
		$data['leaverequestsbyid'] = $this->Vacation_model->getAllleaverequestsbyId($leaverequestid);
		
		}		
		
		$this->render_page('pages/confirmleaves',$data);
		
		}
		
		
		/* Edit approved leaves */
		public function confirmleaveedit(){
		$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
		$data['users'] = $this->getUsers();
		
	    $this->body_class[] = 'addleave';
		$this->page_title = 'Add employee leaves';
		$this->current_section = 'Add Leaves';
		$employee_id = $this->input->post('userid');
		$leaverequestid = $this->input->post('vacationrequestid');
        $startdate = $this->input->post('startdate');
		$enddate = $this->input->post('enddate');
		$noofdays = $this->input->post('noofdays'); 
		$vacationtype = $this->input->post('vacation_type'); 
		$notes =  $this->input->post('notes'); 
		$update = $this->Vacation_model->approveLeave($leaverequestid,$employee_id,$startdate,$enddate,$noofdays, $vacationtype,$notes);
		if($update)
		{
		$data['message'] = "true";
		redirect('vacation/listapprovedleaves?success=leaveedited');
		}
		else {
		$data['message'] = "false"; 
		$data['leaverequestsbyid'] = $this->Vacation_model->getAllleaverequestsbyId($leaverequestid);
		}
		$this->render_page('pages/editLeaves',$data);
		}
		
		
		
		/*
		Rrequest leaves for users by admin
		*/
		public function addleavesfromadmin(){
		
		
		$employee_name = $this->input->post('username');
        $startdate = $this->input->post('startdate');
		$enddate = $this->input->post('enddate');
		$noofdays = $this->input->post('noofdays');
		$nofworkingdays = $this->Vacation_model->findnoofworkingdays($startdate,$enddate);
		//echo $nofworkingdays;
		$noofdays = $nofworkingdays;
		$vacationtype = $this->input->post('vacation_type'); 
		
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('startdate', 'startdate', 'required');
		$this->form_validation->set_rules('enddate', 'enddate', 'required');
		$this->form_validation->set_rules('vacation_type', 'vacation_type', 'required');

		

		if ($this->form_validation->run() == FALSE) {
		
		
		$this->render_page('pages/addleaverequests_admin',$data);
		}

		//google captcha verification
		$captchaToken = $this->input->post('token');
		$verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
	  
		if($verifyToken) {
		$insert = $this->Vacation_model->request_leavebyemployee($employee_name,$startdate,$enddate,$vacationtype,$noofdays);
		if($insert)
		{
		$getvacationtype = $this->Vacation_model->getVacationTypebyId($vacationtype);
		$vactype = $getvacationtype['vacationtype'];
		$userdetails = $this->Vacation_model->getUserbyId($employee_name);
		
		$email = $userdetails['email'];
		$fn = $userdetails['first_name'];
		$ln = $userdetails['last_name'];
		
		$emailText = "
		Dear Admin,<br>
		A leave request has been raised by ".$fn." ".$ln.". Please see the details below.<br><br>
		
		Start date: ".$startdate."<br>
		End date: ".$enddate."<br>
		Vacation type: ".$vactype."<br><br>
		Go to the <a href='https://theandongroup.com/jobsheetmanagement/index.php/vacation/leavemanagement'>Leave requests</a> page to see all the leave requests.
		";
		$from = $email;
		$to = $this->b_emailid; //"brandon@theandongroup.com";//brandon@theandongroup.com
		$cc = $this->hr_emailid; //"amiee@theandongroup.com";//amiee@theandongroup.com
		//echo "---".$to;
		$bcc = "";
		$fullname = $fn." ".$ln;
		$subject = 'Request for leave';
		
		$send = $this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
		if($send){
		$data['mailsent'] ="true";
		$mailsent = "true";
		}
		else {
		$data['mailsent'] ="false";
		$mailsent = "false";
		}
		$data['message'] = "true";
		redirect('vacation/leavemanagement?success=leaverequested&mailsent='.$mailsent.'');
		}
		else 
		{
		$data['message'] = "false"; 
		
		}	
		}
		else {
			$data['message'] = "false";
		}
		
        
		$users = $this->Vacation_model->getUsers();
		$data['users'] = $users;
		$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
		
		$this->render_page('pages/addleaverequests_admin',$data);
		
		}
		
		
		
		
		/*
		View vacation calendar view and list leave requests
		*/
		public function leavemanagement(){
		$categ = $this->uri->segment(3);
		$data['reqtype'] = $categ;
		if($categ != NULL) {
		if($categ == "requests"){           
		$data['listofrequests'] = $this->Vacation_model->getAllleaverequests();
		}
		else if($categ == "annualleaverequests"){
		$data['listofrequests'] = $this->Vacation_model->getAnnualLeaveRequests();	
		}
		else if($categ == "sickleaverequests") {
		$data['listofrequests'] = $this->Vacation_model->getSickLeaveRequests();	
		}
		else if($categ == "casualleaverequests") {
		$data['listofrequests'] = $this->Vacation_model->getCasualLeaveRequests();
		}
		else if($categ == "unpaidleaverequests") {
		$data['listofrequests'] = $this->Vacation_model->getUnpaidLeaveRequests();
		}
		}
		else {
		$data['reqtype'] = "requests";
		$data['listofrequests'] = $this->Vacation_model->getAllleaverequests();
		}
		$data['rejectedleaverequests'] = $this->Vacation_model->getAllRejectedleaverequests();
		$this->render_page('pages/leaveManagement',$data);
		}
		/*
		function sickLeaveRequests
		view sick leave requests
		*/
		public function sickLeaveRequests(){
		$categ = $this->uri->segment(4);
		$data['reqtype'] = $categ;
		
		}
		
		/* function: listapprovedleaves */
		
		public function listapprovedleaves(){
		
		$data['listofapprovedleaves'] = $this->Vacation_model->getAllapprovedleaves();
		
		$this->render_page('pages/approvedleaves',$data);
		}
		
		/*
		functoin: getrejectedleaves()
		*/
		
		public function getrejectedleaves()
		{
		$data['rejectedleaverequests'] = $this->Vacation_model->getAllRejectedleaverequests();
		$this->render_page('pages/rejectedleaves',$data);	
		}
		/**/
		/*
		Approve leave
		*/
		
		public function approveleave()
		{
		
		$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
		$data['users'] = $this->getUsers();
		$id  =  $this->uri->segment(3);
		$emp_id  =  $this->uri->segment(4);
		$data['id'] = $id;
		
		$data['leaverequestsbyid'] = $this->Vacation_model->getAllleaverequestsbyId($id);
		$this->render_page('pages/confirmleaves',$data);
		}
		
		
		/*
		Edit approved leaves
		*/
		public function editleave()
		{
		
		$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
		$data['users'] = $this->getUsers();
		$id  =  $this->uri->segment(3);
		$emp_id  =  $this->uri->segment(4);
		$data['id'] = $id;
		
		$data['leaverequestsbyid'] = $this->Vacation_model->getAllleaverequestsbyId($id);
		$this->render_page('pages/editLeaves',$data);
		}
		
		
		
		/*
		Cancel leave
		*/
		
		public function deleteleave()
		{
		
		
		$id  =  $this->uri->segment(3);
		$emp_id  =  $this->uri->segment(4);
		
		$delete = $this->Vacation_model->deleteleaverequest($id);
		//	$delete2 = $this->Vacation_model->deleteleavefromvacation($id,$emp_id);
		if($delete)
		{
		$data['message'] = "Leave request has been deleted.";
		}
		else 
		{
		$data['message'] = "Leave request hasn't been deleted."; 
		}	
		$data['listofrequests'] = $this->Vacation_model->getAllleaverequests();
		$this->render_page('pages/leaveManagement',$data);
		}
		public function rejectleave()
		{		
	    $data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
		$data['users'] = $this->getUsers();
		$id  =  $this->uri->segment(3);
		$emp_id  =  $this->uri->segment(4);		
		$data['id'] = $id;		
		$data['leaverequestsbyid'] = $this->Vacation_model->getAllleaverequestsbyId($id);
		$this->render_page('pages/rejectLeave',$data);
		}
		
		public function updateLeaveRejection()
		{
		$vacation_request_id = $this->input->post('vacationrequestid');
		$remarks = $this->input->post('remarks');
		$userid = $this->input->post('userid');
		$update = Array(
		"rejectremarks" => $remarks,
		"approved"=> 'r'
		);
		$update = $this->Vacation_model->rejectLeaveRequest($vacation_request_id,$update);
		
		if($update) 
		{
		$userdetails = $this->Vacation_model->getUserbyId($userid);
		
		$email = $userdetails['email'];
		$fn = $userdetails['first_name'];
		$ln = $userdetails['last_name'];
		
		$emailText = "
		Dear ".$fn." ".$ln.",<br>
		Unfortunately your request for leave has been rejected by the HR.<br><br>
		Reason for rejection: ".$remarks."<br><br>
		Please check with the HR for further details.
		";
		
		$from = $this->b_emailid; //"brandon@theandongroup.com";
		$to = $email;
		$cc = $this->hr_emailid; //"amiee@theandongroup.com";//amiee@theandongroup.com
		$bcc = "";
		$fullname = "Brandon Grieve";
		$subject = 'Request for leave notification';
		
		$send = $this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
		
		
		if($send){
		$data['mailsent'] ="true";
		$rejectionsenttouser = "true";
		}
		else {
		$data['mailsent'] ="false";
		$rejectionsenttouser = "false";
		}
		
		$data['message'] = "true";
		redirect('vacation/leavemanagement?success=leaverejected&rejectionsenttouser='.$rejectionsenttouser.'');
		
		}
		else {
		$data["message"] = "false";
		}
		$this->render_page('pages/rejectLeave',$data);
		
		}
		
		
		public function addholidays(){
		$this->render_page('pages/addholidays');
		
		}
		
		public function insertholidays()
		{
		$name = $this->input->post('holidayname');	
	    $date = $this->input->post('holidaydate');
	    $insert = $this->Vacation_model->insertholidays($name,$date);
		
		
		if($insert)
		{
		
		$hols = $this->Vacation_model->listholidays();
		$data['holidays'] = $hols;
		$data["message"] = "true";
		redirect('vacation/listholidays');
		
		}
		else {
		$data["message"] = "false";
		//$this->render_page('pages/addholidays',$data);
		redirect('vacation/listholidays');
		}
		
		}
		
		public function listholidays(){
		$hols = $this->Vacation_model->listholidays();
		$data['holidays'] = $hols;
		$this->render_page('pages/listholidays',$data);
		}
		
		public function deleteHolidays()
		{
		$id     =  $this->uri->segment(2);
		$date   =  $this->uri->segment(3);
		
		$delete = $this->Vacation_model->deleteholiday($id,$date);
		if($delete) {
		$this->Vacation_model->addHolidayToLeaves($date); //to update the number of leaves on leaverequests table. This will find the leave requests that include this deleted date and update the table with respect to the new list of holidays after deletion of this date.
		$data["message"] = "Holiday deleted succesfully.";
		}
		else {
		$data["message"] = "Deletion Unsuccessfull.";
		}
		$hols = $this->Vacation_model->listholidays();
		$data['holidays'] = $hols;
		$this->render_page('pages/listholidays',$data);
		
		}
		
		/*Function: leaveSearch()
		  Function to retrieve all types of leave with respect to user for a particular year*/
		  
		  public function leaveSearch(){
		    $users = $this->getUsers();
		    $data['users'] =  $users;
			if($this->input->post("userid")) {
			$user = $this->input->post('userid');
		    $year = $this->input->post('year');
			$data['sickleavescnt'] = $this->Vacation_model->getCountOfLeavesBasedonVacationType($user,$year,1);
			$data['annualleavescnt'] = $this->Vacation_model->getCountOfLeavesBasedonVacationType($user,$year,2);
			$data['casualeavescnt'] = $this->Vacation_model->getCountOfLeavesBasedonVacationType($user,$year,3);
			$data['unpaidleavescnt'] = $this->Vacation_model->getCountOfLeavesBasedonVacationType($user,$year,4);
		
			$data['userid'] =  $user;
			$data['sickleaves'] = $this->Vacation_model->getAllApprovedLeavesBasedonVacationType($user,$year,1);
			$data['annualleaves'] = $this->Vacation_model->getAllApprovedLeavesBasedonVacationType($user,$year,2);
			$data['casualeaves'] = $this->Vacation_model->getAllApprovedLeavesBasedonVacationType($user,$year,3);
			$data['unpaidleaves'] = $this->Vacation_model->getAllApprovedLeavesBasedonVacationType($user,$year,4);
			}
			$this->render_page('pages/searchleavesforusers',$data);
		  }
		  
		 
		}		