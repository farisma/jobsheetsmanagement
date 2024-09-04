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
	
	class Clients_model extends CI_Model
	{
		
		
		private $ekContractStartDate;
		private $consolidated_clients = array(); //array to save clientids of clients apart from Emirates SkyCargo that needs consolidated job no in the start of the month
		function __construct()
		{
			parent::__construct();
			$this->load->database();
			$this->load->config('ion_auth', TRUE);
			//initialize db tables data
			$this->tables  = $this->config->item('tables', 'ion_auth');
			$this->ekContractStartDate = "2013-11-01";  //start date of the EKSC contract
			// Retatiner client ids that need first jobno of every month to be a consolidated jobno
			//$this->consolidated_clients  = [2336,2353,2401]; //mubadala jobno local 2218 	server 2336,2353 2401 is mubadala new contract
			array_push($this->consolidated_clients,2336,2353,2401); //these are retainer clients that needs consolidated job numbers, not the clients that just need a consolidated job numbers
		//	print_r($this->consolidated_clients);
		}
		/*
			Function: add_clients()
			Add client to the database.
			Author: Faris M A
		*/	
		function add_clients($client,$clientcode)
		{
			
			$data = array(
			'clientname' => $client,
			'client_code' => $clientcode,
            );
			$this->db->insert('clients',$data);    
			return ($this->db->affected_rows() != 1) ? false : true;
		}
		/*
			Function: add_job()
			Add new job with respect to the client to the database.
			Author: Faris M A
		*/	
		function add_job($jobs)
		{
            $data = array(
			"client_id" => $jobs["clientid"],
			"date" => $jobs["date"],
			"job_no" => $jobs["jobno"],
			"description" => $jobs["description"],
			"jobname" => $jobs["jobname"],
			"retainingContract" => $jobs["retainingContract"],
			"approved" => $jobs["approved"],
			"jobseqno"=>$jobs["jobseqno"],
			"invoiced"=>$jobs["invoiced"],
			"projecttype" => $jobs["projecttype"],
			"consolidatedbillingcustomer" => $jobs['consolidatedbillingcustomer'],
			"jobraisedby" => $jobs['jobraisedby']
			);
			$this->db->insert('jobs',$data);    
			return ($this->db->affected_rows() != 1) ? false : true;
		}
		
		
		/*
			Function: add_project_type()
			Add project types in order to add while creating job
			Author: Faris M A
	    */
		function add_project_type($project_type)
		{
			$data = array(
			'project_type' => $project_type
            );
			$this->db->insert('project_types',$data);    
			return ($this->db->affected_rows() != 1) ? false : true;
			
		}
		
		/*
			Function: add_retainer_job()
			Add new job that is for retainer clients.
			Author: Faris M A
		*/	
		function add_retainer_job($jobs) {
			$data = array(
			"client_id"  =>  $jobs["client_id"],
			"date" => $jobs["date"],
			"job_no"  =>  $jobs["jobno"],
			"description" => $jobs["description"],
			"jobname" => $jobs["jobname"],
			"projecttype"=> $jobs["projecttype"],
			"retainingContract" =>$jobs["retainingContract"],
			"jobseqno"=>$jobs["jobseqno"],
			"retainer_c_job" => $jobs["retainer_c_job"],
			"retaining_c_monthno" => $jobs["retaining_c_monthno"],
			"retaining_c_yearno" => $jobs["retaining_c_yearno"],
			"retaining_c_jobnoformonth" => $jobs["retaining_c_jobnoformonth"],
			"approved" => $jobs["approval"] ,
			"retainerscope" => $jobs["retainerscope"],
			"ekbillable" => $jobs["ekbillable"],               
			"invoiced" => $jobs["invoiced"],
			"consolidated_check" => $jobs['consolidated_check'],
			"consolidated_jobno" => $jobs['consolidated_jobno'],
			"monthly_consol_jobno" => $jobs['monthly_consol_jobno'],
			"jobraisedby" => $jobs['jobraisedby']
			);
			$this->db->insert('jobs',$data);    
			return ($this->db->affected_rows() != 1) ? false : true;
			
		}


		/*
			Function: add_consolidatedBC_job()
			Add new job that is for consolidated billing customer.
			Author: Faris M A
		*/	
		function add_consolidatedBC_job($jobs) {
			$data = array(
				"client_id"  =>  $jobs["client_id"],
				"date" => $jobs["date"],
				"job_no"  =>  $jobs["jobno"],
				"description" => $jobs["description"],
				"jobname" => $jobs["jobname"],
				"projecttype"=> $jobs["projecttype"],
				"retainingContract" => "n", 
				"consolidatedbillingcustomer" => "n",// consolidatedbillingcustomer is to indicate a job that is quotation for consolidated biling customer. here it should be n as it is a job that is opted for consolidated billing customer and not specific job under it
				"jobseqno"=>$jobs["jobseqno"],
				"consolidated_check"=>$jobs["consolidated_check"],				
				"consolidatedB_c_job" => "y",
				"consolidatedB_c_monthno" => $jobs["consolidatedB_c_monthno"],
				"consolidatedB_c_yearno" => $jobs["consolidatedB_c_yearno"],
				"consolidatedB_c_jobnoformonth" => $jobs["consolidatedB_c_jobnoformonth"],
				"approved" => $jobs["approval"],
				"invoiced" => $jobs["invoiced"],
				"ekbillable" => $jobs["ekbillable"],											
				"jobraisedby" => $jobs['jobraisedby']
			);
			$this->db->insert('jobs',$data);    
			return ($this->db->affected_rows() != 1) ? false : true;

		}

		
		/*
			Function: add_ek_retainer_job()
			Add new retainer job that is for EKSC.
			Author: Faris M A
		*/
		function add_ek_retainer_job($jobs) {
			$data = Array(
			"client_id"  =>  $jobs['client_id'],
			"date" => $jobs['date'],
			"job_no"  =>  $jobs['jobno'],
			"jobname"  =>  $jobs['jobname'],
			"description" => $jobs['description'],
			"projecttype" => $jobs['projecttype'],
			"retainingContract" => "n",// retainer contract is to indicate a job that is quotation for retaining contract. here it should be n as it is a job that is under retainer client and not a retainer contract
			"jobseqno"=>$jobs['jobseqno'],
			"eksc_retainer" => "y",
			"ekmonthno" => $jobs['ekmonthno'],
			"ekyearno" => $jobs['ekyearno'],
			"ekjobnoformonth" => $jobs['ekjobnoformonth'],
			"approved" =>  $jobs['approved'],
			"invoiced" => $jobs['invoiced'],
			"ekbillable" => $jobs['ekbillable'],
			"retainerscope" => $jobs['retainerscope'],
			"consolidated_check" => $jobs['consolidated_check'],
			"consolidated_jobno" => $jobs['consolidated_jobno'],
			"monthly_consol_jobno" => $jobs['monthly_consol_jobno'],
			"jobraisedby" => $jobs['jobraisedby']
			); 
			$this->db->insert('jobs',$data);    
			return ($this->db->affected_rows() != 1) ? false : true;
			
		}
		/*
			Function: getClients()
			Get all clients(enabled = 'y') from the database.
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
			Function: getRetainingClients()
			Get all clients(enabled = 'y' & retainingContract = 'y' and ekscretainer != 'y') from the database.
			Author: Faris M A
		*/	
		public function getRetainingClients()
		{
			$query = $this->db->query("select t1.id as clientid,t1.clientname as clientname,t2.id as jobid,t2.client_id as clientidinjobs from clients as t1,jobs as t2 where t1.id=t2.client_id and t2.enabled = 'y' and t2.retainingContract='y' and eksc_retainer != 'y' group by t1.clientname order by t1.clientname asc");
			
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $key => $row)
				{
					$result[$row->clientid] =  $row->clientname;
				}
				return $result;
			}
		}  
		
		
/*
			Function: getConsolidatedBClients()
			Get all clients(enabled = 'y' & consolidatedbillingcustomer = 'y') from the database.
			Author: Faris M A
		*/	
		public function getConsolidatedBClients()
		{
			$query = $this->db->query("select t1.id as clientid,t1.clientname as clientname,t2.id as jobid,t2.client_id as clientidinjobs from clients as t1,jobs as t2 where t1.id=t2.client_id and t2.enabled = 'y' and t2.consolidatedbillingcustomer='y' group by t1.clientname order by t1.clientname asc");

			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $key => $row)
				{
					$result[$row->clientid] =  $row->clientname;
				}
				return $result;
			}
		}   

		/*
			Function: getAllClients()
			Get all clients from the database.
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
			Function: getAllRetainerClientsThatNeedsConsolidatedBilling()
			Get all clients from the database.
			Author: Faris M A
		*/	
		public function getAllRetainerClientsThatNeedsConsolidatedBilling()
		{
			$query = $this->db->query("select id,clientname,consolidated_billing_for_retainer from clients where enabled='y' and consolidated_billing_for_retainer='y' order by clientname asc");

			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $key => $row)
				{
					$result[$row->id] =  $row->id;
				}
				return $result;
			}
		}


		/*
			Function: getClientNamebyID()
			Get client name with respect to id from the database.
			Author: Faris M A
		*/
		public function getClientNamebyID($id) 
		{
			$query = $this->db->query("select id,clientname,client_code,enabled,consolidated_billing_for_retainer from clients where id='".$id."' order by clientname asc");
			
			if ($query->num_rows() > 0)
			{
				/*foreach ($query->result() as $key => $row)
					{
					$result[0] =  $row->clientname;
					$result[1] =  $row->client_code;
					}
				return $result;*/
				return  $query->result_array();
			}
		}
		/*
			Function: getJobcodesbyID()
			Get job code with respect to id from the database.
			Author: Faris M A
		*/
		public function getJobcodesbyID($id)
		{
			$query = $this->db->query("select job_no from jobs where id='".$id."' where enabled='y'");
			
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $key => $row)
				{
					$result =  $row->job_no;
				}
				return $result;
			}
		}
		/*
			Function: listAllClients()
			List all clients in the database.
			Author: Faris M A
		*/
		public function listAllClients()
		{
			$query = $this->db->query("select id,clientname,client_code,enabled,consolidated_billing_for_retainer from clients order by clientname asc");
			
			if ($query->num_rows() > 0)
			{
				
				return  $query->result_array();
			}
		}	
		/*
			Function: listClients()
			List all clients that are active from the database.
			Author: Faris M A
		*/
		public function listClients()
		{
			$query = $this->db->query("select id,clientname,client_code from clients where enabled='y' order by clientname asc");
			
			if ($query->num_rows() > 0)
			{
				
				return  $query->result_array();
			}
		}	
		
		
		/*
			Function: listProjectTypes()
			List all project types in the database.
			Author: Faris M A
		*/
		public function listProjectTypes()
		{
			$query = $this->db->query("select id,project_type from project_types");
			
			if ($query->num_rows() > 0)
			{
				
				return  $query->result_array();
			}
		}	
		
		/*
		Function: getProjectTypebyID()	
		List project with respect to id	
		*/
		public function getProjectTypebyID($id)
		{
			$query = $this->db->query("select id,project_type from project_types where id='".$id."'");
			
			if ($query->num_rows() > 0)
			{
				
				return  $query->result_array();
			}
		}	
		/*
		Function: updateProjectType()	
		List project with respect to id	
		*/
		public function updateProjectType($projecttype)
		{
			$data = array(	
	    "project_type" => $projecttype["project_type"]
		);
		$id = $projecttype["id"];
		$this->db->set($data);
		$this->db->where('id',$id); 
		$this->db->update('project_types');    
		
		return ($this->db->affected_rows() != 1) ? false : true;
		}	
		
		public function deleteProjectType($projectTypeIdToBeDeleted){
		 $query =  $this->db->query("delete from project_types where id='".$projectTypeIdToBeDeleted."'");
		return ($this->db->affected_rows() != 1) ? false : true;
		
		}
		/*
			Function: listJobs()
			List all jobs in the database.
			Author: Faris M A
		*/	
		public function listJobs ()
		{
			$query = $this->db->query("select t1.id,t1.date as dateadded,t1.job_no,t2.clientname,t1.jobname,t1.projecttype,t1.description,t1.invoiced,t1.approved,t1.ekbillable,t1.retainer_c_job,t1.eksc_retainer,t1.consolidated_check,t1.consolidated_jobno,t1.projecttype from jobs as t1,clients as t2 where t2.id = t1.client_id and t1.enabled='y' order by id desc");
			
			if ($query->num_rows() > 0)
			{
				$i = 0;
				foreach ($query->result() as $row)
				{
					$result[$i]["id"] =  $row->id;
					$result[$i]["date"] =  $row->dateadded;
					$result[$i]["jobno"] =  $row->job_no;
					$result[$i]["clientname"] =  $row->clientname;
					$result[$i]["jobname"] =  $row->jobname;
					$result[$i]["description"] =  $row->description;
					$result[$i]["invoiced"] =  $row->invoiced;
					$result[$i]["approved"] =  $row->approved;
					$result[$i]["ekbillable"] =  $row->ekbillable;
					$result[$i]["retainer_c_job"] =  $row->retainer_c_job;
					$result[$i]["eksc_retainer"] =  $row->eksc_retainer;
					$result[$i]["consolidated_check"] =  $row->consolidated_check;
					$result[$i]["consolidated_jobno"] =  $row->consolidated_jobno;
					$result[$i]["project_type"] =  $row->projecttype;
					
					
					//To convert project types' id to its name in case of multiple project type in a project
					$projecttypes = explode("/",$result[$i]["project_type"]);
					$projecttypes_concat = "";
					for($k=0;$k<count($projecttypes);$k++) {
					
					$query2 = $this->db->query("select project_type from project_types where id = '".$projecttypes[$k]."'");
					if ($query2->num_rows() > 0) {
					 $res = $query2->result_array();
					 $projecttypes_concat.= $res[0]['project_type']."/";
					}
					}
					$result[$i]["project_type"] = substr($projecttypes_concat,0,-1); //to strip last /
					$i++;
				}
				
				return $result;
			}
		}


	/*
			Function: listJobsWithPaginLimit()
			List jobs in the database with limit mentioned in the pagination.
			Author: Faris M A
		*/	
		public function listJobsWithPaginLimit($offset,$limit){
			$query = $this->db->query("select t1.id,t1.date as dateadded,t1.job_no,t2.clientname,t1.jobname,t1.projecttype,t1.description,t1.invoiced,t1.approved,t1.ekbillable,t1.retainer_c_job,t1.eksc_retainer,t1.consolidated_check,t1.consolidated_jobno,t1.projecttype from jobs as t1,clients as t2 where t2.id = t1.client_id and t1.enabled='y' order by id desc limit ".$offset.",".$limit."");
			
			if ($query->num_rows() > 0)
			{
				$i = 0;
				foreach ($query->result() as $row)
				{
					$result[$i]["id"] =  $row->id;
					$result[$i]["date"] =  $row->dateadded;
					$result[$i]["jobno"] =  $row->job_no;
					$result[$i]["clientname"] =  $row->clientname;
					$result[$i]["jobname"] =  $row->jobname;
					$result[$i]["description"] =  $row->description;
					$result[$i]["invoiced"] =  $row->invoiced;
					$result[$i]["approved"] =  $row->approved;
					$result[$i]["ekbillable"] =  $row->ekbillable;
					$result[$i]["retainer_c_job"] =  $row->retainer_c_job;
					$result[$i]["eksc_retainer"] =  $row->eksc_retainer;
					$result[$i]["consolidated_check"] =  $row->consolidated_check;
					$result[$i]["consolidated_jobno"] =  $row->consolidated_jobno;
					$result[$i]["project_type"] =  $row->projecttype;
					
					
					//To convert project types' id to its name in case of multiple project type in a project
					$projecttypes = explode("/",$result[$i]["project_type"]);
					$projecttypes_concat = "";
					for($k=0;$k<count($projecttypes);$k++) {
					
					$query2 = $this->db->query("select project_type from project_types where id = '".$projecttypes[$k]."'");
					if ($query2->num_rows() > 0) {
					 $res = $query2->result_array();
					 $projecttypes_concat.= $res[0]['project_type']."/";
					}
					}
					$result[$i]["project_type"] = substr($projecttypes_concat,0,-1); //to strip last /
					$i++;
				}
				
				return $result;
			}
		}


			/*
			Function: countOfEnabledJobs()
			List jobs in the database with limit mentioned in the pagination.
			Author: Faris M A
		*/	
		public function countOfEnabledJobs(){
			$query = $this->db->query("select * from jobs as t1,clients as t2 where t2.id = t1.client_id and t1.enabled='y'");
			return $query->num_rows();
		}
		
		/*  Function to retrieve retainer clients */
		public function getRetainerClients()
		{
			$query=$this->db->query("SELECT client_id FROM jobs WHERE eksc_retainer='y' or retainer_c_job='y' group by client_id");
			if ($query->num_rows() > 0)
			{
				return  $query->result_array();
			}
		}
		
		/*
			Function: getJobsSearched()
			List all jobs that matches the search parameters.
			Author: Faris M A
		*/				
		
		
		public function getJobsSearched($queryconditions)
		{
			$i=1;
			$querystring = "";
			$queryadditional = "";
			$querybegining ="select t1.id,t1.date as dateadded,t1.job_no,t2.clientname,t1.jobname,t1.projecttype,t1.description,t1.approved,t1.invoiced,t1.ekbillable,t1.eksc_retainer,t1.retainer_c_job,t1.consolidated_check,t1.consolidated_jobno from jobs as t1,clients as t2";
			
			foreach($queryconditions as $key=>$val){
				if($i==1)/* if first condition then it should start with WHERE Clause */
				{
					$queryfirst = " WHERE";
				}
				else 
				{
					$queryfirst = " AND";
				}
				if($key == "client")
				{
					$querystring.= $queryfirst." t1.client_id='".$val."'";
				}
				if($key == "date")
				{
					$querystring.= $queryfirst." t1.date='".$val."'";
				}
				if($key == "ekbillable")
				{
					$querystring.= $queryfirst." t1.ekbillable='".$val."'";
				}
				if($key == "invoiced")
				{
			        $querystring.= $queryfirst." t1.invoiced='".$val."'";
				}
				$i++;
			}
			
			/* Query general conditions */
			$querystring.=  "AND t2.id = t1.client_id and t1.enabled='y' order by id desc";
			$finalquery = $querybegining.$querystring;
			//print_r($finalquery);
			$query = $this->db->query($finalquery);
			if ($query->num_rows() > 0)
			{
				$i = 0;
				foreach ($query->result() as $row)
				{
					$result[$i]["id"] =  $row->id;
					$result[$i]["date"] =  $row->dateadded;
					$result[$i]["jobno"] =  $row->job_no;
					$result[$i]["clientname"] =  $row->clientname;
					$result[$i]["jobname"] =  $row->jobname;
					$result[$i]["description"] =  $row->description;
					$result[$i]["approved"] =  $row->approved;
					$result[$i]["invoiced"] =  $row->invoiced;
					$result[$i]["ekbillable"] =  $row->ekbillable;
					$result[$i]["eksc_retainer"] =  $row->eksc_retainer;
					$result[$i]["retainer_c_job"] =  $row->retainer_c_job;
					$result[$i]["consolidated_check"] =  $row->consolidated_check;
					$result[$i]["consolidated_jobno"] =  $row->consolidated_jobno;
					$result[$i]["project_type"] =  $row->projecttype;
					
					//To convert project types' id to its name in case of multiple project type in a project
					$projecttypes = explode("/",$result[$i]["project_type"]);
					$projecttypes_concat = "";
					for($k=0;$k<count($projecttypes);$k++) {
					
					$query2 = $this->db->query("select project_type from project_types where id = '".$projecttypes[$k]."'");
					if ($query2->num_rows() > 0) {
					 $res = $query2->result_array();
					 $projecttypes_concat.= $res[0]['project_type']."/";
					}
					}
					$result[$i]["project_type"] = substr($projecttypes_concat,0,-1); //to strip last /
					
					$i++;
				}
				
				return $result;
			}
			else  return false;
			
		}
		
		/*
			Function: selectJobById()
			select particular job details with respect to jobno. from the database.
			Author: Faris M A
		*/	
		
		public function selectJobById ($id)
		{
			$query = $this->db->query("select t1.id,t1.date,t1.job_no,t2.id as clientid,t2.clientname,t1.jobname,t1.description,t1.projecttype,t1.approved,t1.invoiced,t1.jobclosed,t1.ekbillable,t1.retainerscope,t1.retainer_c_job,t1.consolidated_check,t1.consolidated_jobno,t1.monthly_consol_jobno,t1.consolidatedB_c_job from jobs as t1,clients as t2 where t2.id = t1.client_id and t1.id=".$id."");
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			else  {
			return false; }
		}
		
		/*
			Function: getJobSeqNo()
			select next sequential no for non retaining job
			Author: Faris M A
		*/	
		
		public function getJobSeqNo ($clientId)
		{
			
			$query = $this->db->query("SELECT MAX(jobseqno)+1 as jobseqno FROM jobs WHERE eksc_retainer != 'y'");  // take the maximum of existing job nos by checking the first codes from the existing jobcode
			$result1  =  $query->row();	
			$jobseqno  =  $result1->jobseqno;  //to find the sequential no.
			$query = $this->db->query("SELECT client_code FROM clients WHERE id = '".$clientId."'");             
			$result2 =   $query->row();
			$client_code = $result2->client_code;
			$newjobseq = sprintf("%04d", $jobseqno);
			$jobcode = $newjobseq."_".$client_code;
			return  $jobcode;
		}
		
		/*
			Function: getRetainerJobSeqNo()
			select next sequential no for retaining client's job with respect to year, month and jobno of particular month.
			Author: Faris M A
		*/
		
		public function getRetainerJobSeqNo ($dateR,$clientR) {
		    $firstjobofmonth = false;
			$year = substr($dateR,0,4);
			$month =  substr($dateR,5,2); // year and month to check the next number of job of the same month and year
			$query1 = $this->db->query("select max(jobseqno) as jobno from jobs where client_id = '$clientR' and retainingContract='y'");
			$result1  =  $query1->row();     
			$retainerprefix =    $result1->jobno;
			
			
			
			$query2 = $this->db->query("select max(retaining_c_jobnoformonth)+1 as lastjobno from jobs where client_id='$clientR' and retainer_c_job='y' and retaining_c_yearno=$year and retaining_c_monthno=$month");
			$result2  =  $query2->row();  
			if($result2->lastjobno == NULL || $result2->lastjobno == 0)
			{
				$jobseqformonth = "01";
				$firstjobofmonth = true;
			}
			else 
			{ 
				$jobseqformonth2 =   $result2->lastjobno;
				$jobseqformonth  =  sprintf("%02d",$jobseqformonth2);
			}
		$finaljobseqno = $retainerprefix."_".$month."_". $jobseqformonth;
		//check if this job no is a retainer jobno that needs to have a consolidated job no in the begining of the month
	//	print_r($this->consolidated_clients);
	     $client_consol_retainer = $this->getAllRetainerClientsThatNeedsConsolidatedBilling();
		if (in_array($clientR,$client_consol_retainer) && $firstjobofmonth == true) {
					$consolidatedjobno = array(true,$finaljobseqno,$month,$year,$firstjobofmonth); //an array is passed with the job no and indicator to tell if its a jobno that is consolidated
					$finaljobseqnotosend = $consolidatedjobno;
				}
				else if(in_array($clientR,$client_consol_retainer) && $firstjobofmonth == false){
					$consolidatedjobno = array(true,$finaljobseqno,$month,$year,$firstjobofmonth); //an array is passed with the job no and indicator to tell if its a jobno that is consolidated
					$finaljobseqnotosend = $consolidatedjobno;
					}
				else {
					$finaljobseqnotosend = array(false,$finaljobseqno,$month,$year,$firstjobofmonth);	
					}
				return $finaljobseqnotosend;
		}


		/*
			Function: getConsolidatedBillingCJobSeqNo()
			select next sequential no for consolidated billing client's job with respect to year, month and jobno of particular month.
			Author: Faris M A
		*/

		public function getConsolidatedBillingCJobSeqNo ($dateR,$clientR) {
		    $firstjobofmonth = false;
			$year = substr($dateR,0,4);
			$month =  substr($dateR,5,2); // year and month to check the next number of job of the same month and year
			$query1 = $this->db->query("select max(jobseqno) as jobno from jobs where client_id = '$clientR' and consolidatedbillingcustomer='y'");
			$result1  =  $query1->row();     
			$retainerprefix =    $result1->jobno;



			$query2 = $this->db->query("select max(consolidatedB_c_jobnoformonth)+1 as lastjobno from jobs where client_id='$clientR' and consolidatedB_c_job='y' and consolidatedB_c_yearno=$year and consolidatedB_c_monthno=$month");
			$result2  =  $query2->row();  
			if($result2->lastjobno == NULL || $result2->lastjobno == 0)
			{
				$jobseqformonth = "01";
				$firstjobofmonth = true;
			}
			else 
			{ 
				$jobseqformonth2 =   $result2->lastjobno;
				$jobseqformonth  =  sprintf("%02d",$jobseqformonth2);
			}
		$finaljobseqno = $retainerprefix."_".$month."_". $jobseqformonth;
		$finaljobseqnotosend = array($firstjobofmonth,$finaljobseqno,$month,$year);	
		return $finaljobseqnotosend;
		}
		
		/*
		Function: getEKRetainerJobSeqNo() EKSC_0025_04  -  desired output. 04 is the jobno for month, 0025 is number of month since contract.
		select next sequential no for EKSC retainer jobs with respect to date and month and jobno of particular month.(here month no's do not renew yearly)
		Author: Faris M A
		*/
		
		
		public function getEKRetainerJobSeqNo($dateEK) 
		{
		$year = substr($dateEK,0,4);
        $month =  substr($dateEK,5,2); 
		$query1 = $this->db->query("SELECT TIMESTAMPDIFF(MONTH, '".$this->ekContractStartDate."', '".$dateEK."') as datediff"); //find the difference between contract commencement and the job date
		$result1  =  $query1->row();
		$monthdiff = $result1->datediff+1;
		$EKjobseqformonth  =  sprintf("%04d",   $monthdiff);
		$query2 = $this->db->query("select MAX(ekjobnoformonth)+1 as maxjobindex from jobs WHERE ekyearno = ".$year ." and ekmonthno = ".$month."");
		//  echo "select MAX(ekjobnoformonth) as maxjobindex from jobs WHERE ekyearno = ".$year ."& ekmonthno = ".$month."";
		$result2  =  $query2->row();
		
		$ekjobindexformonth = $result2->maxjobindex;
		
		// First job of everymonth is considered as a consolidated job no to include all job nos that are billed under AED 750
		//Below check is to send an indication for first job of a month
		if($ekjobindexformonth == NULL){
		$ekjobindexformonth_final =  "01";
		$consolidated = true;
		$consolidatedjobno =  array($consolidated,"EKSC_".$EKjobseqformonth."_".$ekjobindexformonth_final,$month,$year);		
		}
		else {
		$consolidated = false;
		$ekjobindexformonth_final =  sprintf("%02d",   $ekjobindexformonth);
		$consolidatedjobno = array($consolidated,"EKSC_".$EKjobseqformonth."_".$ekjobindexformonth_final);
		//return  "EKSC_".$EKjobseqformonth."_".$ekjobindexformonth_final;  
		}
		return $consolidatedjobno;
		
		
		
		}
		/*
		Function: update_job()
		Update existing job with the new details.
		Author: Faris M A
		*/	
		
		public function update_job($job)	
		{
		
		$data = array(
		"approved" => $job["approved"],
		"jobname" => $job["jobname"],
		"description" => $job["description"],
		"invoiced"=>$job["invoiced"],
		"jobclosed"=>$job["jobclosed"],
		"ekbillable"=>$job["ekbillable"],
		"retainerscope" => $job["retainerscope"],
		"consolidated_check" =>$job["consolidated_check"],
		"consolidated_jobno" =>$job["consolidated_jobno"],
		"projecttype" => $job["projecttype"]
		
		);
		
		$id = $job["id"];
		$this->db->set($data);
		$this->db->where('id',$id); 
		$this->db->update('jobs');    
		
		return ($this->db->affected_rows() != 1) ? false : true;
		
		}
		
		/*
		Function: updateClient()
		Update existing Client with the new details (Client Code).
		Author: Faris M A
		*/	
		
		public function updateClient($client)
		{
		$data = array(	
	    "client_code" => $client["client_code"],
		"enabled" => $client["enabled"],
		"consolidated_billing_for_retainer" => $client['consolidated_billing_for_retainer']
		);
		$id = $client["id"];
		$this->db->set($data);
		$this->db->where('id',$id); 
		$this->db->update('clients');    
		
		return ($this->db->affected_rows() != 1) ? false : true;
		}
		/*
        Function: deleteJob()
        Delete particular job in the database.
        Author: Faris M A
		*/			
		public function deleteJob($id) 
		{
		$query =  $this->db->query("delete from jobs where id='".$id."'");	
		return ($this->db->affected_rows() != 1) ? false : true;
		
		}
		
		public function deleteClient($clientid)
		{
		$query =  $this->db->query("delete from clients where id='".$clientid."'");
		return ($this->db->affected_rows() != 1) ? false : true;
		}
		
		
		/*
		Function: disableJob()
		Disable particular job in the database.
		Author: Faris M A
		
		*/
		public function disableJob($id) 
		{
		$query =  $this->db->query("update jobs set enabled='n' where id='".$id."'");
		
		return ($this->db->affected_rows() != 1) ? false : true;
		
		}
		
		
		/*
		Function: disableClient()
		Disable particular client in the database.
		Author: Faris M A
		
		*/
		public function disableClient($clientid)
		{
		$query =  $this->db->query("update clients set enabled='n' where id='".$clientid."'");
		return ($this->db->affected_rows() != 1) ? false : true;
		}
		
		}		