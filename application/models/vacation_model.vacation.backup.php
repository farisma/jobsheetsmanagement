<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
		* Name:  Clients management model
		*
		* Author:  Faris M A
		* 		   
		*
		*
		*
		* Created:  15.02.2015
		*
		* Description:  Model to add, delete and manage Clients, job numbers etc.
		* Description:  Model to add, delete and manage Clients, job numbers etc.
		*
		* Requirements: PHP5 or above
		*
	*/
	
	class Vacation_model extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->database();
			$this->load->config('ion_auth', TRUE);
			//initialize db tables data
			$this->tables  = $this->config->item('tables', 'ion_auth');
			
		}
		
		/*
			Function GetuserbyId()
			Retrieve user details with respect to user id supplied
			
		*/
		
		public function getUserbyId($id){
			
			$query = $this->db->query("select username,email,first_name,last_name from users where id='".$id."'");
			
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $key => $row)
				{
					//$result[$key] =  $row->id;
					$result['username'] =  $row->username;
					$result['email'] =  $row->email;
					$result['first_name'] =  $row->first_name;
					$result['last_name'] =  $row->last_name;
				}
				return $result;
			}
			
		}
		
		
		
		/*
			Function GetvacationtypebyId()
			Retrieve user details with respect to user id supplied
			
		*/
		
		public function getVacationTypebyId($id){
			
			$query = $this->db->query("select vacationtype from vacationtypes where id='".$id."'");
			
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $key => $row)
				{
					
					$result['vacationtype'] =  $row->vacationtype;
					
				}
				return $result;
			}
			
		}
		
		/*
			Function: getVacationTypes()
			Fetch the list of vacations types from the database.
			Author: Faris M A
		*/
		public function getVacationTypes()
		{
			$query = $this->db->query("select id,vacationtype from vacationtypes");
			
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $key => $row)
				{
					//$result[$key] =  $row->id;
					$result[$row->id] =  $row->vacationtype;
				}
				return $result;
			}
		}
		
		/*
			Function: getVacationdetailsbyId()
			Fetch the list of vacations dates of users from the database.
			Author: Faris M A
		*/
		
		
		public function getVacationdetailsbyId($id)
		
		{
			//echo $id;
			$query = $this->db->query("select t1.id,t1.employee_id,t1.startdate,t1.enddate,t1.noofdays,t2.username,t2.first_name,t2.last_name,t3.vacationtype from leaverequests as t1, users as t2, vacationtypes as t3 where t1.employee_id=t2.id and t1.vacationtype = t3.id and t1.approved='y' and t1.employee_id='".$id."' order by t2.first_name");
			
			if ($query->num_rows() > 0)
			{
				
				return $query->result_array();
			}
		}
		
		/*
			Function: getVacationdetails()
			Fetch the list of vacations dates of users from the database.
			Author: Faris M A
		*/
		
		public function getVacationdetails()
		{
			$query = $this->db->query("select t1.id as id,t1.employee_id,t1.startdate as startdate,t1.enddate as enddate,t1.noofdays as noofdays,t2.username as username,t3.vacationtype as vacationtype from leaverequests as t1, users as t2, vacationtypes as t3 where t1.employee_id=t2.id and t1.vacationtype = t3.id and t2.resigned = 'n' and t1.approved='y'");
			
			if ($query->num_rows() > 0)
			{
				/*foreach ($query->result() as $key => $row)*/
				/*$i = 0;
					foreach ($query->result() as $row)
					{
					$result[$i]["id"] =  $row->id;
					$result[$i]["startdate"] =  $row->startdate;
					$result[$i]["enddate"] =  $row->enddate;
					$result[$i]["noofdays"] =  $row->noofdays;
					$result[$i]["username"] =  $row->username;
					$result[$i]["vacationtype"] =  $row->vacationtype;
					$i++;
				}*/
				return $query->result_array();
			}
		}
		
		/*
			Function: getVacationdetailsgroupedbyUser()
			Fetch the list of vacations dates of users from the database.
			Author: Faris M A
		*/
		
		public function getVacationdetailsgroupedbyUser()
		{
			$query = $this->db->query("select t1.id as id,t1.startdate as startdate,t1.enddate as enddate,t1.noofdays as noofdays,t2.username as username,t3.vacationtype as vacationtype from leaverequests as t1, users as t2, vacationtypes as t3 where t1.employee_id=t2.id and and t1.approved='y' and t1.vacationtype = t3.id");
			
			if ($query->num_rows() > 0)
			{
				/*foreach ($query->result() as $key => $row)*/
				/*$i = 0;
					foreach ($query->result() as $row)
					{
					$result[$i]["id"] =  $row->id;
					$result[$i]["startdate"] =  $row->startdate;
					$result[$i]["enddate"] =  $row->enddate;
					$result[$i]["noofdays"] =  $row->noofdays;
					$result[$i]["username"] =  $row->username;
					$result[$i]["vacationtype"] =  $row->vacationtype;
					$i++;
				}*/
				return $query->result_array();
			}
		}
		
		/*
			Function: getVacDates()
			Fetch the list of vacations dates of each users from the database.
			Author: Faris M A
		*/
		public function getVacDates($username){
			
			$query = $this->db->query("select t1.startdate as startdate,t1.enddate as enddate,t1.noofdays,t1.employee_id,t2.vacationtype,t3.username,t3.first_name,t3.last_name from leaverequests as t1, vacationtypes as t2, users as t3 where t1.employee_id='".$username."' and t1.approved='y' and t2.id=t1.vacationtype and t3.id = t1.employee_id");
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
		}
		
		/*
			Function: getUsers()
			Fetch the list of users from the database.
			Author: Faris M A
		*/
		public function getUsers()
		{
			$query = $this->db->query("select id,username from users where resigned = 'n' and id!=1 and id!=28 and id!=29 order by username");
			
			if ($query->num_rows() > 0)
			{
				//$i = 0;
				foreach ($query->result() as $key => $row)
				{
					$result[$row->id] =  $row->username;
					//$result[$i]["username"] =  $row->username;
					// $i++;
					
				}
				return $result;
			}
			
		}
		
		
		
		
		
		/*
			Function: add_leavedatesbyemployee()
			Add employee leaves to the table
			Author: Faris M A
		*/
		
		public function add_leavedatesbyemployee($employee_name,$startdate,$enddate,$noofdays, $vacationtype,$leaverequestid)
		{
			
			$data = array(
			'employee' => $employee_name,
			'startdate' => $startdate,
			'enddate' => $enddate,				
			'vacationtype' => $vacationtype,
			'noofdays' => $noofdays
			
            );
			$this->db->insert('leaverequests',$data);    
			return ($this->db->affected_rows() != 1) ? false : true;
			
		}
		
		/*
			Function: request_leavebyemployee()
			Add employee leave request to the table
			Author: Faris M A
		*/
		
		public function request_leavebyemployee($employee_id,$startdate,$enddate,$vacationtype,$noofdays) {
			
			$data = array(
			'employee_id' => $employee_id,
			'startdate' => $startdate,
			'enddate' => $enddate,				
			'vacationtype' => $vacationtype,
			'noofdays'=>$noofdays
			
            );
			$this->db->insert('leaverequests',$data);    
			return ($this->db->affected_rows() != 1) ? false : true;
			
		}
		
		/*
			Function: getAllleaverequests()
			Get all leave requests
			Author: Faris M A
		*/
		
		public function getAllleaverequests() {
			$query = $this->db->query("select t1.id,t1.startdate,t1.enddate,t1.employee_id,t1.approved,t1.rejectremarks,t2.username,t2.first_name,t3.vacationtype from leaverequests as t1,users as t2, vacationtypes as t3 where t2.id=t1.employee_id and t3.id=t1.vacationtype and t1.approved='n' order by t2.username");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			
		}
		
		
		/*
			Function: getSickLeaveRequests()
			Get all sick leave requests
			Author: Faris M A
		*/
		
		public function getSickLeaveRequests() {
			$query = $this->db->query("select t1.id,t1.startdate,t1.enddate,t1.employee_id,t1.approved,t1.rejectremarks,t2.username,t2.first_name,t3.vacationtype from leaverequests as t1,users as t2, vacationtypes as t3 where t2.id=t1.employee_id and t3.id=t1.vacationtype and t1.approved='n' and t1.vacationtype = 1 order by t2.username");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			
		}
		
		/*
			Function: getAnnualLeaveRequests()
			Get all annual leave requests
			Author: Faris M A
		*/
		
		public function getAnnualLeaveRequests() {
			$query = $this->db->query("select t1.id,t1.startdate,t1.enddate,t1.employee_id,t1.approved,t1.rejectremarks,t2.username,t2.first_name,t3.vacationtype from leaverequests as t1,users as t2, vacationtypes as t3 where t2.id=t1.employee_id and t3.id=t1.vacationtype and t1.approved='n' and t1.vacationtype = 2 order by t2.username");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			
		}
		
		/*
			Function: getCasualLeaveRequests()
			Get all casual leave requests
			Author: Faris M A
		*/
		
		public function getCasualLeaveRequests() {
			$query = $this->db->query("select t1.id,t1.startdate,t1.enddate,t1.employee_id,t1.approved,t1.rejectremarks,t2.username,t2.first_name,t3.vacationtype from leaverequests as t1,users as t2, vacationtypes as t3 where t2.id=t1.employee_id and t3.id=t1.vacationtype and t1.approved='n' and t1.vacationtype = 3 order by t2.username");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			
		}	
		
		
		/*
			Function: getUnpaidLeaveRequests()
			Get all casual leave requests
			Author: Faris M A
		*/
		
		public function getUnpaidLeaveRequests() {
			$query = $this->db->query("select t1.id,t1.startdate,t1.enddate,t1.employee_id,t1.approved,t1.rejectremarks,t2.username,t2.first_name,t3.vacationtype from leaverequests as t1,users as t2, vacationtypes as t3 where t2.id=t1.employee_id and t3.id=t1.vacationtype and t1.approved='n' and t1.vacationtype = 4 order by t2.username");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			
		}	
		
		
		/*
			Function: getAllapprovedleaves()
			Get all approved leaves
			Author: Faris M A
		*/
		public function getAllapprovedleaves(){
			$query = $this->db->query("select t1.id,t1.startdate,t1.enddate,t1.employee_id,t1.approved,t1.rejectremarks,t1.noofdays,t1.notes,t2.username,t2.first_name,t3.vacationtype from leaverequests as t1,users as t2, vacationtypes as t3 where t2.id=t1.employee_id and t3.id= t1.vacationtype and t1.approved='y' order by t2.username");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
		}
		
		
		/*
			Function: getAllleaverequestsbyId()
			Add employee leave request to the table
			Author: Faris M A
		*/
		
		public function getAllleaverequestsbyId($id) {
			$query = $this->db->query("select t1.id,t1.startdate,t1.enddate,t1.employee_id,t1.approved,t1.noofdays,t1.vacationtype as vacationtypeid,t1.notes,t2.username,t3.vacationtype from leaverequests as t1,users as t2, vacationtypes as t3 where t2.id=t1.employee_id and t3.id= t1.vacationtype and t1.id = '".$id."'");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			
		}
		
		/*
			Function: approveLeave()
			Add employee leave request to the table
			Author: Faris M A
		*/
		
		public function approveLeave($leaverequestid,$employee_id,$startdate,$enddate,$noofdays, $vacationtype, $notes) {
			
			$data = array(
			"employee_id" => $employee_id,
			"startdate" => $startdate,
			"enddate" => $enddate,
			"vacationtype"=>$vacationtype,
			"approved" => 'y',
			"noofdays" => $noofdays,
			"notes" => $notes
			);
			
			$this->db->set($data);
			$this->db->where('id',$leaverequestid); 
			$this->db->update('leaverequests');  	
			return ($this->db->affected_rows() != 1) ? false : true;	 
		}
		
		/*
			Function: cancelleaveRequest()
			Delete leave request from the table
			Author: Faris M A
		*/
		
		public function deleteleaverequest($id) {
			$query =  $this->db->query("delete from leaverequests where id='".$id."'");	
			
			return ($this->db->affected_rows() != 1) ? false : true;
		}
		
		/*public function deleteleavefromvacation($leaverequestid,$emp_id) {
			
			
			$query = $this->db->query("select * from vacation where employee='".$emp_id."' and leaverequestid = '".$leaverequestid."'");	
			if($query->num_rows() > 0) {
			$query =  $this->db->query("delete from vacation where employee='".$emp_id."' and leaverequestid = '".$leaverequestid."'");	
			}
			return ($this->db->affected_rows() != 1) ? false : true;
		}*/
		
		/*
			Function: rejectLeaveRequest()
			Reject a leave request
			Author: Faris
		*/
		
		public function rejectLeaveRequest($id,$data) {
			$this->db->set($data);
			$this->db->where('id',$id); 
			$this->db->update('leaverequests');  	
			return ($this->db->affected_rows() != 1) ? false : true;
			
		}
		
		/*
			function:  sendemailnotification()	
			Send email notification to admin and user when a request for leave is raised and approved respectively
		*/
		
		
		public function sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject){
			
			$this->load->library('email');			
			$this->email->set_mailtype("html");
			$this->email->from($from, $fullname);
			$this->email->to($to);	
            if($cc != NULL)					
			{$this->email->cc($cc);}
			//$this->email->bcc('them@their-example.com');					
			$this->email->subject($subject);
			$this->email->message($emailText);
			
			$send =$this->email->send();
			if($send) {
				return true;
			}
			else {
				return false;
			}
		}
		
		/* List rejected leaves 
			Function: getAllRejectedleaverequests()
		*/
		public function getAllRejectedleaverequests() {
			$query = $this->db->query("select t1.id,t1.startdate,t1.enddate,t1.employee_id,t1.approved,t1.rejectremarks,t1.noofdays,t2.username,t2.first_name,t3.vacationtype from leaverequests as t1,users as t2, vacationtypes as t3 where t2.id=t1.employee_id and t3.id=t1.vacationtype and t1.approved='r' order by t2.username");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			
		}
		
		/* Function to list all holidays*/
		public function listholidays()
		{
			$query = $this->db->query("select id,name,date from holidays");
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
		}
		
		/* Function to list all holiday dates*/
		public function listholidayDates()
		{
			$query = $this->db->query("select date from holidays");
			if ($query->num_rows() > 0)
			{
				$i = 0;
				foreach ($query->result() as $row)
				{
					$dates[$i] = $row->date;
					$i++;
				}		  
				return $dates;
			}
		}
		
		
		
		/* Find no. of working days between two dates*/
		public function findnoofworkingdays($sd,$ed){
			$no = 0;
			
			$start = new DateTime($sd);
			//date difference function will not count the end date, so adding next day's (+1 day) date to include the last date
			$end = new DateTime(date('Y-m-d', strtotime($ed . ' +1 day')));
			
			$interval = DateInterval::createFromDateString('1 day');
			
			$diff =  $end->diff($start)->format("%a");
			
			$listholidays = $this->listholidayDates();
			
			$period = new DatePeriod($start, $interval, $end);
			foreach ($period as $dt)
			{
				//print_r($dt->format('Y-m-d'));
				if ($dt->format('N') == 5 || $dt->format('N') == 6)
				{
					$no++;
				}
				else {
					if(in_array($dt->format('Y-m-d'), $listholidays)) {
						
						$no++;
					}
					
				}
			}
			
			return $diff - $no; //total no of days - number of sundays and saturdays
		}
		
		
		/* Function to add holdays to database*/
		public function insertholidays($name, $date){
		$data = array(
		'name' => $name,
		'date' => $date							
		);
		$this->db->insert('holidays',$data);
		$this->addHolidayToLeaves($date); // to update the number of leavedays by excluding the official holiday added from the total working days
		return ($this->db->affected_rows() != 1) ? false : true;
		}
		
		
		/* Function to update number of days of leaves by including the given official holiday date*/
		public function addHolidayToLeaves($holidaydate) {
		$query = $this->db->query("select id,startdate,enddate from leaverequests where enddate >= '".$holidaydate."' and startdate <= '".$holidaydate."'");
		if ($query->num_rows() > 0)
		{
		foreach ($query->result() as $row)
		{
		$id           = $row->id;
		$startdate    = $row->startdate;
		$enddate      = $row->enddate;
		$no_workingdays = $this->findnoofworkingdays($startdate,$enddate);
		$data = array(
		"id"        => $id,
		"startdate" => $startdate,
		"enddate"   => $enddate,										
		"noofdays"  => $no_workingdays				
		);
		
		$this->db->set($data);
		$this->db->where('id',$id); 
		$this->db->update('leaverequests');  	
		
		}	
		}
		return ($this->db->affected_rows() != 1) ? false : true;
		}
		
		/*Delete existing holiday date*/
		public function deleteholiday($id,$date) {
		
		$query =  $this->db->query("delete from holidays where id='".$id."'");		
		if($this->db->affected_rows() != 1) 
		{
		return false;
		}
		else {
		// here this will update the no. of days with respect to the new list of official holidays(after the above deletion) 
		$this->addHolidayToLeaves($date);
		return true;
		}
		
		
		}
		}		