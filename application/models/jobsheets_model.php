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

class Jobsheets_model extends CI_Model
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
	Function: getClients()
	Fetch the list of clients which are active(enabled) from the database.
	Author: Faris M A
	*/
 public function getClients()
	{
	            $query = $this->db->query("select id,clientname from clients where enabled='y' order by clientname asc");

		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $key => $row)
		   {
			  $result[$row->id] =  $row->clientname;
		   }
		   return $result;
		}
	}
    
    /*
	Function: getAllClients()
	Fetch the list of clients from the database. This is for search page and search filter in list jobs page
	Author: Faris M A
	*/
 public function getAllClients()
	{
	            $query = $this->db->query("select id,clientname from clients order by clientname asc");

		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $key => $row)
		   {
			  $result[$row->id] =  $row->clientname;
		   }
		   return $result;
		}
	}
   	/*
	Function: getJobsperClient()
	Fetch the list of jobcodes per client. This will fetch only jobcodes that are enabled. disabled jobcodes wont be fetched.
	Author: Faris M A
	*/
    public function getJobsperClient($clientid)  //  Setting default client id as EKSC's id for showing the job ids default in the form 
	{
	          if( $clientid != NULL) {  
		$query = $this->db->query("select id,job_no,jobname from jobs where client_id='".$clientid."' and enabled='y' and approved='y'");
		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $key => $row)
		   {
			  $result[$row->id] =  $row->job_no."(". $row->jobname.")";
		   }
		   return $result;
		}
		}
		else
		{
		return false;
		}
	} 
    /*
	Function: getAllJobsperClient()
	Fetch the list of jobcodes per client. This will fetch all job codes irrespective of the status of enabled field. This is for search page where it need all jobcodes.
	Author: Faris M A
	*/
        public function  getAllJobsperClient($clientid)
        {
          if( $clientid != NULL) {  
		$query = $this->db->query("select id,job_no,jobname from jobs where client_id='".$clientid."' order by id DESC");
		if ($query->num_rows() > 0)
		{
		//    foreach ($query->result() as $row)
		//    {
		// 	  $result[$row->id] =  $row->job_no."(". $row->jobname.")";
		//    }
		  // print_r($result);
		   return $query->result_array();
		}
		}
		else
		{
		return false;
		}
        
        }
	 /*
	Function: getAllJobsperClientLimit1000()
	Fetch the list of jobcodes per client. This will fetch all job codes irrespective of the status of enabled field. This is for search page where it need all jobcodes.
	This will limit the results by just 1000 jos inorder to limit the load
	Author: Faris M A
	*/
	public function  getAllJobsperClientLimit1000($clientid)
	{
	  if( $clientid != NULL) {  
	$query = $this->db->query("select id,job_no,jobname from jobs where client_id='".$clientid."' order by id DESC limit 1000");
	if ($query->num_rows() > 0)
	{
	//    foreach ($query->result() as $row)
	//    {
	// 	  $result[$row->id] =  $row->job_no."(". $row->jobname.")";
	//    }
	  // print_r($result);
	   return $query->result_array();
	}
	}
	else
	{
	return false;
	}
	
	}
	/*
	Function: getCountofJobsheetsforAdmin()
	Fetch the no. of jobsheets.
	Author: Faris M A
	*/
public function getCountofJobsheetsforAdmin()
{
$query = $this->db->query("select id from jobsheet_items");
$rows = $query->num_rows();
return $rows;
}
	/*
	Function: getCountofJobsheets()
	Fetch the no. of jobsheets per user.
	Author: Faris M A
	*/
public function getCountofJobsheets($userid)
{
$query = $this->db->query("select id from jobsheet_items where username= ".$userid."");
$rows = $query->num_rows();
return $rows;
}

/*
	Function: getJobsheets()
	Fetch all the jobsheets per user for a particular date with limit.
	Author: Faris M A
	*/
  public  function getJobsheets($userid,$limit ,$start,$date)
          {
		$query = $this->db->query("select t1.id,t1.date,t1.hoursspent,t1.description,t2.job_no,t3.clientname from jobsheet_items as t1,jobs as t2,clients as t3 where username='".$userid." and t1.date='".$date."' and t1.company = t3.id and t2.id = t1.jobno order by t1.id DESC LIMIT ".$start.",".$limit."");
		if ($query->num_rows() > 0)
		{
		   return $query->result_array();
		}
	 
           }
	/*
	Function: getJobsheetsforAdmin()
	Fetch all the jobsheets for a particular date with limit.
	Author: Faris M A
	*/
 public  function getJobsheetsforAdmin($limit ,$start,$date)
          {
		$query = $this->db->query("select t1.id,t1.date,t1.hoursspent,t1.description,t2.job_no,t3.clientname from jobsheet_items as t1,jobs as t2,clients as t3 where t1.date='".$date."' and t1.company = t3.id and t2.id = t1.jobno order by t1.id DESC LIMIT ".$start.",".$limit."");
	            if ($query->num_rows() > 0)
		{
		   return $query->result_array();
		}
           }

	/*
	Function: getJobsheetsbyDate()
	Fetch all the jobsheets per user for a particular date without limit.
	Author: Faris M A
	*/
	
public  function getJobsheetsbyDate($userid,$date)
          {
		$query = $this->db->query("select t1.id,t1.date,t1.hoursspent,t1.description,t2.job_no,t3.clientname from jobsheet_items as t1,jobs as t2,clients as t3 where username='".$userid."' and t1.date='".$date."' and t1.company = t3.id and t2.id = t1.jobno order by t1.id");
		if ($query->num_rows() > 0)
		{
		   return $query->result_array();
		}
	 
           }		   
	/*
	Function: getJobsheetsbyDateforAdmin()
	Fetch all the jobsheets for a particular date without limit.
	Author: Faris M A
	*/
		   
public  function getJobsheetsbyDateforAdmin($date)
          {
		$query = $this->db->query("select t1.id,t1.date,t1.hoursspent,t1.description,t2.job_no,t3.clientname from jobsheet_items as t1,jobs as t2,clients as t3 where t1.date='".$date."' and t1.company = t3.id and t2.id = t1.jobno order by t1.id");
	            if ($query->num_rows() > 0)
		{
		   return $query->result_array();
		}
           }
	/*
	Function: addJobsheet()
	Insert new jobsheet to the table.
	Author: Faris M A
	*/	   
  public function addJobsheet($username,$jobno, $client,$date,$timespent,$description)
                {
				//$query = "insert into jobsheet_items (username, jobno, company, date, hoursspent, description, username) values ('".$username."','".$jobno."','". $client."','".$date."','".$timespent."','".$description."')";
				$data = Array(
				"username" => $username,
				"jobno" => $jobno, 
				"company" => $client,
				"date" => $date,
				"hoursspent" => $timespent,
				"description" => $description,
				"username" =>  $username
				);
				$this->db->insert('jobsheet_items',$data);    
		                        return ($this->db->affected_rows() != 1) ? false : true;
				
	    }
	/*
	Function: setDaysandDate()
	Set the date and days for current week and previous week. That will be passed as a parameter from the controller. Default value is current
	Author: Faris M A
	*/
public function setDaysandDate($week="current")
{
                       $today = date('w');
		
                      if($week == "current") {
		   if($today == 0) //if today is sunday
		   {
		     $datesunday = date('Y-m-d',strtotime("today"));
                             $daysunday =  date('l',strtotime("today"));
						
		     $datemonday = date('Y-m-d',strtotime("today +1 day"));
                             $daymonday =  date('l',strtotime("today +1 day"));				
		
		     $datetuesday = date('Y-m-d',strtotime("today +2 days"));
                             $daytuesday =  date('l',strtotime("today +2 days"));				

		     $datewednesday = date('Y-m-d',strtotime("today +3 days"));
                             $daywednesday =  date('l',strtotime("today +3 days"));		
		
		     $datethursday = date('Y-m-d',strtotime("today +4 days"));
                             $daythursday =  date('l',strtotime("today +4 days"));	

		    $datefriday = date('Y-m-d',strtotime("today +5 days"));
                            $dayfriday =  date('l',strtotime("today +5 days"));	
						
		    $datesaturday = date('Y-m-d',strtotime("today +6 days"));
                            $daysaturday =  date('l',strtotime("today +6 days"));	
		   }
		   else{
		        $datesunday = date('Y-m-d',strtotime("last Sunday"));
                               $daysunday =  date('l',strtotime("last Sunday"));
						
		      $datemonday = date('Y-m-d',strtotime("last Sunday +1 day"));
                             $daymonday =  date('l',strtotime("last Sunday +1 day"));				
		
		      $datetuesday = date('Y-m-d',strtotime("last Sunday +2 days"));
                             $daytuesday =  date('l',strtotime("last Sunday +2 days"));				

		      $datewednesday = date('Y-m-d',strtotime("last Sunday +3 days"));
                             $daywednesday =  date('l',strtotime("last Sunday +3 days"));		
		
		     $datethursday = date('Y-m-d',strtotime("last Sunday +4 days"));
                             $daythursday =  date('l',strtotime("last Sunday +4 days"));	

		     $datefriday = date('Y-m-d',strtotime("last Sunday +5 days"));
                             $dayfriday =  date('l',strtotime("last Sunday +5 days"));	
						
		    $datesaturday = date('Y-m-d',strtotime("last Sunday +6 days"));
                           $daysaturday =  date('l',strtotime("last Sunday +6 days"));		
		   }
             	 
		}						
		else if($week == "prev")
		{
		
		if($today == 0)  //if today is sunday
		{
		     $datesunday = date('Y-m-d',strtotime("last Sunday"));
                               $daysunday =  date('l',strtotime("last Sunday"));
						
		      $datemonday = date('Y-m-d',strtotime("last Sunday +1 day"));
                             $daymonday =  date('l',strtotime("last Sunday +1 day"));				
		
		      $datetuesday = date('Y-m-d',strtotime("last Sunday +2 days"));
                             $daytuesday =  date('l',strtotime("last Sunday +2 days"));				

		      $datewednesday = date('Y-m-d',strtotime("last Sunday +3 days"));
                             $daywednesday =  date('l',strtotime("last Sunday +3 days"));		
		
		     $datethursday = date('Y-m-d',strtotime("last Sunday +4 days"));
                             $daythursday =  date('l',strtotime("last Sunday +4 days"));	

		     $datefriday = date('Y-m-d',strtotime("last Sunday +5 days"));
                             $dayfriday =  date('l',strtotime("last Sunday +5 days"));	
						
		    $datesaturday = date('Y-m-d',strtotime("last Sunday +6 days"));
                           $daysaturday =  date('l',strtotime("last Sunday +6 days"));	
		}
		else {
	            $datesunday = date('Y-m-d',strtotime("last Sunday -7 days"));
                        $daysunday =  date('l',strtotime("last Sunday -7 days"));
						
		$datemonday = date('Y-m-d',strtotime("last Sunday -6 days"));
                        $daymonday =  date('l',strtotime("last Sunday -6 days"));				
		
		$datetuesday = date('Y-m-d',strtotime("last Sunday -5 days"));
                        $daytuesday =  date('l',strtotime("last Sunday -5 days"));				

		$datewednesday = date('Y-m-d',strtotime("last Sunday -4 days"));
                        $daywednesday =  date('l',strtotime("last Sunday -4 days"));		
		
		$datethursday = date('Y-m-d',strtotime("last Sunday -3 days"));
                        $daythursday =  date('l',strtotime("last Sunday -3 days"));	

		$datefriday = date('Y-m-d',strtotime("last Sunday -2 days"));
                        $dayfriday =  date('l',strtotime("last Sunday -2 days"));	
						
		$datesaturday = date('Y-m-d',strtotime("last Sunday -1 day"));
                        $daysaturday =  date('l',strtotime("last Sunday -1 day"));	
		}
		}

		$date["datesunday"] = $datesunday;
		$date["daysunday"] =  $daysunday;
		$date["datemonday"] =  $datemonday;
		$date["daymonday"] = $daymonday;
		$date["datetuesday"] =  $datetuesday;
		$date["daytuesday"] =  $daytuesday;
		$date["datewednesday"] =  $datewednesday;
		$date["daywednesday"] =  $daywednesday;
		$date["datethursday"] =  $datethursday;
		$date["daythursday"] =  $daythursday;
		$date["datefriday"] =  $datefriday;
		$date["dayfriday"] =  $dayfriday;
		$date["datesaturday"] =  $datesaturday;
		$date["daysaturday"] =  $daysaturday;
		return  $date;

}	
/*
	Function: setQueryconditions()
	Set DB Query with the conditions appended to it
	Author: Faris M A

	*/	
public function getQueryResults($queryconditions)
{
	   $i=1;
	   $querystring = "";
	   $queryadditional = "";
	   $querybegining = "select t1.id,t1.date,t1.hoursspent,t1.description,t2.job_no,t3.clientname,t4.username from jobsheet_items as t1,jobs as t2,clients as t3,users as t4";
	  foreach($queryconditions as $key=>$val){
	          if($i==1)/* if first condition then it should start with WHERE Clause */
		   {
		   $queryfirst = " WHERE";
		   }
		   else 
		   {
		   $queryfirst = " AND";
		   }
		   /* Query condition concatenating  */
		   if($key == "client")
			   {
				$querystring.= $queryfirst." t1.company='".$val."'";
			   }
		 if($key == "jobcode")
			   {
				$querystring.= $queryfirst." t1.jobno='".$val."'";
			   }
		   if($key == "startdate")
			   {
				$querystring.= $queryfirst." t1.date>='".$val."'";
			   }
		   
		   if($key == "enddate")
			   {
				 $querystring.= $queryfirst." t1.date<='".$val."'";
			   }
		    if($key == "user")
			   {
				 $querystring.= $queryfirst." t1.username='".$val."'";
			   } 
		   
		$i++;
	}
	/* Query general conditions */
	$querystring.=  " AND t1.company = t3.id AND t2.id = t1.jobno AND t4.id=t1.username";
	$finalquery = $querybegining.$querystring;
	
	$query = $this->db->query($finalquery);
	 if ($query->num_rows() > 0)
		{
		   return $query->result_array();
		}
//return $querybegining.$querystring;
}	


/*
	Function: getAllJobsheets()
	Get all jobsheets from the databse if search is applied with not parameters passed
	Author: Faris M A

	*/	

public function getAllJobsheets()
{
 $query = "select t1.id,t1.date,t1.hoursspent,t1.description,t2.job_no,t3.clientname,t4.username from jobsheet_items as t1,jobs as t2,clients as t3,users as t4 where t1.company = t3.id AND t2.id = t1.jobno AND t4.id=t1.username";
$execute = $this->db->query($query);
if ($execute->num_rows() > 0)
		{
		   return $execute->result_array();
		}
}

	/*
	Function: deleteJobsheetItembyID()
	Delete a jobsheet item with respect to ID
	Author: Faris M A

	*/	
public function deleteJobsheetItembyID($id) {
                                             $query =  $this->db->query("delete from jobsheet_items where id='".$id."'");
	                                 return ($this->db->affected_rows() != 1) ? false : true;
		}
	/*
	Function: getUsers()
	Function to load all the users from the database to show in the dropdown list in the search form
	Author: Faris M A

	*/		
	public function getUsers(){
		$query = $this->db->query("select id,username from users");

		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $key => $row)
		   {
			  $result[$row->id] =  $row->username;
		   }
		   return $result;
		}
	
	}
   
}