<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Clients extends App_Controller
	{
	    public $b_emailid = "brandon@theandongroup.com"; 
		public $account_manager_emailid = "";
		public $accountant_emailid = "haider@theandongroup.com";
		public $teamemail = "andonteam@theandongroup.com";

		public $username;

		//public $jobs_url = 'http://localhost/jobsheets/index.php/list-jobs'; // for pagination config
		public $jobs_url = 'https://theandongroup.com/jobsheetmanagement/index.php/list-jobs';
		// public $b_emailid = "faris@theandongroup.com"; 
		// public $account_manager_emailid = "faris@theandongroup.com";
		// public $accountant_emailid = "faris@theandongroup.com";
		// public $teamemail = "faris@theandongroup.com";
		
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
			}
			$this->load->model("Clients_model");
			$this->load->model("Jobsheets_model");
			$this->load->model("Vacation_model");
			$user = $this->ion_auth->user()->row();
			$this->username = $user->id;
			//echo "user".$this->username;
		}
		
		public function index()
		{
			
			$this->render_page('pages/addclient');
		}
		public function adminPage()
		{
			
			$this->render_page('pages/admin');
		}
		
		public function add_project_type()
		{
		
		    $this->render_page('pages/addprojecttype');
		
		}
		public function list_project_types() {
			
			
			$data['listofprojecttypes'] = $this->Clients_model->listProjectTypes();
			$this->render_page('pages/listprojecttypes',$data);
			
			}
			
		public function deleteProjectType()
		{
			
			$projectTypeIdToBeDeleted =  $this->uri->segment(2);
			$deleteJob = $this->Clients_model->deleteProjectType($projectTypeIdToBeDeleted);
			$projecttypes  =  $this->Clients_model->listProjectTypes();
			if($deleteJob) {$data["message"] = "Project type has been deleted succesfully.";} else {$data["message"] = "Deletion Unsuccesfull";}
			$data["listofprojecttypes"] = $projecttypes;
			$this->render_page('pages/listprojecttypes',$data);
			
		}
		
		
		public function submit_project_type()
		{
		
		   $project_type = $this->input->post('project_type');
		   $insert = $this->Clients_model->add_project_type($project_type);
		   if($insert)
				{
					$data['message'] = "Project type has been added succesfully.";
				}
				else 
				{
					$data['message'] = "Project type hasn't been added succesfully."; 
				}
		     $this->list_project_types();
		}
		public function edit_project_type() {
		
		    $projecttypeTobeEdited =  $this->uri->segment(2);     
            $projecttype  =  $this->Clients_model->getProjectTypebyID($projecttypeTobeEdited);
			$data['projecttype'] = $projecttype;
			//$this->body_class[] = 'addclient';
			$this->page_title = 'Edit project type';
			$this->current_section = 'Add project type';
            $this->form_validation->set_rules('project_type', 'Project type field', 'required');
			if ($this->form_validation->run() == true) {
                $project_type = $this->input->post('project_type');
				 $project = Array(
                "id" => $projecttypeTobeEdited,
                "project_type"=> $project_type
                );
                $updateProjectType  =  $this->Clients_model->updateProjectType($project);
				if($updateProjectType)
				{
					$data['message'] = "Project type has been updated successfully.";
				}
				else 
				{
					$data['message'] = "Project type has not been updated successfully."; 
				}
			}
		    $this->render_page('pages/edit_projecttype',$data);
		}
		
		
		
		public function add()
		{
			
			$this->body_class[] = 'addclient';
			
			$this->page_title = 'Add new Client';
			$this->current_section = 'Add Clients';
			
			
			$this->form_validation->set_rules('clientname', 'Client field', 'required');
			$this->form_validation->set_rules('clientcode', 'Client Code field', 'required');
			$this->form_validation->set_message('required', 'This field can\'t be NULL.');
			if ($this->form_validation->run() == true)
			{
				$client_name = $this->input->post('clientname');
				$client_code = strtoupper($this->input->post('clientcode'));
				$insert = $this->Clients_model->add_clients($client_name,$client_code);
				if($insert)
				{
					$data['message'] = "Client has been added succesfully.";
				}
				else 
				{
					$data['message'] = "Client has not been added succesfully."; 
				}
			}
			else
			{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
			
			$this->render_page('pages/addclient',$data);
		}
		
		public function addjob()
		{
			
			$this->body_class[] = 'addclient';
			$this->page_title = 'Add new job';
			$this->current_section = 'Add Job';
			
			$this->form_validation->set_rules('client', 'Client name', 'required');
			//$this->form_validation->set_rules('date', 'Text', 'required');
			$this->form_validation->set_rules('jobno', 'Job no.', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required');
			$this->form_validation->set_rules('jobname', 'Job name', 'required');
            //$this->form_validation->set_message('required', 'This field can\'t be NULL');
            if ($this->form_validation->run() == true)
			{
				//google captcha verification
			 $captchaToken = $this->input->post('token');
			 $verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
			 if($verifyToken) {
				$client_id = $this->input->post('client');
				$clientnamefromid = $this->Clients_model->getClientNamebyID($client_id);
				
				$clientname = $clientnamefromid[0]['clientname'];
				//echo $clientname;
				$date = $this->input->post('date');
				$jobno = $this->input->post('jobno');
				$invoiced = $this->input->post('invoiced');
				$retainingContract = $this->input->post('retainingContract');
				if($retainingContract == "")  $retainingContract = "n"; 
				$consolidatedBillingCustomer = $this->input->post('consolidatedBillingCustomer');
				if($consolidatedBillingCustomer == "")  $consolidatedBillingCustomer = "n";
				$clientApproval = $this->input->post('approval');
				if($clientApproval == "")  $clientApproval = "n";
				
				if($invoiced == "")  $invoiced = "n";
				$description = $this->input->post('description');
				$project_type_nr = $this->input->post('project_type_nr'); //project type for non retainer
				
				$selectedOption = "";
				foreach ($project_type_nr as $option => $value) {
				$selectedOption.= $value.'/'; // I am separating Values with a comma (,) so that I can extract data using explode()
	            }
				
				$project_type_nr = substr($selectedOption,0,-1); // to remove the last /
				//echo $project_type_nr;
				$division = $this->input->post('company_division');
				if($division == '') $division = NULL;
				$quoted_amount = $this->input->post('quoted_amount');
				if($quoted_amount == null) $quoted_amount = 0;
				$jobname = $this->input->post('jobname');
				$jobseq =  substr($jobno,0,4);
				//echo "division".$division;
				$jobs = Array(
				"clientid"  =>  $client_id,
				"date" => $date,
				"jobno"  =>  $jobno,
				"description" => $description,
				"jobname" => $jobname,
				"retainingContract" => $retainingContract,
				"consolidatedbillingcustomer" => $consolidatedBillingCustomer,
				"approved" => $clientApproval,
				"jobseqno"=>$jobseq,
				"invoiced" => $invoiced,
				"division" => $division,
				"quote"=> $quoted_amount,
				"projecttype" => $project_type_nr,
				"jobraisedby" => $this->username
				);
				$insert = $this->Clients_model->add_job($jobs);
				if($insert)
				{
					$data['message'] = "Job has been added successfully.";
					
					// send email to brandon, caroline and haider if a job has been raised
					//if($client_id == 35) {
					
					$subject = "Notification on new job(".$jobno.") raised on Jobsheet Management System";
					$user = $this->ion_auth->user()->row_array();
					$fromemail = $user['email'];
					$full_name = $user['first_name']." ".$user['last_name'];
					//if a job is raised and approved
					if($clientApproval == "y") {
						$emailText = "
						Dear Admin,<br>
						This is to notify you that a new job has been raised and approved on the Jobsheet Management System. Below are the details.<br><br>
						Client: ".$clientname." <br>
						Job No: ".$jobno."<br>
						Job Name: ".$jobname."<br>";
						$cc = $this->accountant_emailid;// "caroline@theandongroup.com,haider@theandongroup.com";
						$bcc = $this->account_manager_emailid;
					}
					else {
						$emailText = "
						Dear Admin,<br>
						This is to notify you that a new job has been raised on the Jobsheet Management System. Below are the details.<br><br>
						Client: ".$clientname."<br>
						Job No: ".$jobno."<br>
						Job Name: ".$jobname."<br>";
						$cc = $this->accountant_emailid;//"caroline@theandongroup.com";haider
						$bcc = $this->account_manager_emailid;
					}
					
					$from = $fromemail;
					$to = $this->b_emailid;//"brandon@theandongroup.com";
					
					//$bcc = "";
					$fullname = $full_name;
					$this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
					
				}
				else 
				{
					$data['message'] = "Job has not been added successfully."; 
				}
			}
			else {
				$data['message'] = "Job has not been added successfully (Google Recaptcha authentication error)."; 
			}
			}
			else
			{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
			$clients = $this->Clients_model->getClients();
			$data["clients"] = $clients;
			$retainingClients = $this->Clients_model->getRetainingClients();
			$data["retainingClients"] = $retainingClients;
			$consolidatedBClients = $this->Clients_model->getConsolidatedBClients();
			$data["consolidatedBClients"] = $consolidatedBClients;
			$projecttypes =  $this->Clients_model->listProjectTypes();
			$data["project_types"] = $projecttypes;
			$data["retainer_consolidated_billing_enabled"] = $this->Clients_model->getAllRetainerClientsThatNeedsConsolidatedBilling();
			$this->render_page('pages/addNonRetainingJob',$data);
			
		}
		
		
		public function addretainerjob() 
		{
            $this->body_class[] = 'addclient';
			$this->page_title = 'Add new job';
			$this->current_section = 'Add Job';
			
			$this->form_validation->set_rules('clientR', 'Client field', 'required');
			$this->form_validation->set_rules('dateR', 'Date', 'required');
			$this->form_validation->set_rules('jobnoR', 'Job no.', 'required');
			$this->form_validation->set_rules('descriptionR', 'Description', 'required');
			$this->form_validation->set_rules('jobnameR', 'Job name', 'required');
            //$this->form_validation->set_message('required', 'This field can\'t be NULL');
			if ($this->form_validation->run() == true)
			{
				//google captcha verification
			 $captchaToken = $this->input->post('token');
			 $verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
			 if($verifyToken) {
				$client_id = $this->input->post('clientR');
				$clientnamefromid = $this->Clients_model->getClientNamebyID($client_id);		
				$clientname = $clientnamefromid[0]['clientname'];
				
				$date = $this->input->post('dateR');
				$jobno = $this->input->post('jobnoR');
				
				$description = $this->input->post('descriptionR');
				$jobnameR = $this->input->post('jobnameR');
				$project_type_rc = $this->input->post('project_type_rc'); //project type for  retainer
				$selectedOption = "";
				foreach ($project_type_rc as $option => $value) {
				$selectedOption.= $value.'/'; // I am separating Values with a comma (,) so that I can extract data using explode()
	            }
				$project_type_rc = substr($selectedOption,0,-1); // to strip the last /
				$division = $this->input->post('company_division');
				if($division == '') $division = NULL;
				$quoted_amount = $this->input->post('quoted_amount');
				if($quoted_amount == null) $quoted_amount = 0;
				$jobseq =  substr($jobno,0,4);
				$month_jobno =  substr($jobno,5,2); // month no to be saved in the database to find next jobseqno.
				$year_jobno =  substr($date,0,4); // year no to be saved in the database to find next jobseqno.
				$jobindexformonth =  substr($jobno,8,2); // job index to be saved in the database to find next jobseqno. This +1 will be the next jobindex to be added in the final jobseqno
				
				$retainerscope = $this->input->post('retainerscope');
				$billable = $this->input->post('billable');
				$approval = $this->input->post('approval');
				$invoiced = $this->input->post('invoiced');
				$monthly_consol_jobno_for_retainers = $this->input->post('monthly_consol_jobno_for_retainers'); //indicate if its firstjobno of month. value n indicates its not a first job of the month
				$addto_consolidated = $this->input->post('addto_consolidated');
				
				if($addto_consolidated == "y") {
				    //if a job is added to the consolidated job no, pick up the consolidated job (first 8 letters plus 01)
					$jobnoWithoutIndex = substr($jobno,0,8);
					$consolidatedJobNo=$jobnoWithoutIndex."01";
				}
				else {
					$addto_consolidated = "n";
					$consolidatedJobNo = "";
				}
				if($retainerscope == 'y') //which means it doesnot need to be billed. It falls under retainer contract
				{
					$approval = 'y';
					$invoiced = 'y';
					$billable  = 'n';
					$retainerscope = 'y';
				}
				else if($retainerscope != 'y'){ //which means it needs to be billed. It doesn't fall under retainer contract
					if($billable  != 'y') {  $billable = 'y';}  else if($billable  == 'y'){$billable = 'y';}
					if($approval  != 'y') {  $approval = 'n';}  else if($approval  == 'y'){$approval = 'y';}
					if($invoiced  != 'y') {  $invoiced = 'n';}   else if($invoiced  == 'y') {$invoiced = 'y';}
					
					$retainerscope  = 'n';
				}
				
				$jobs = Array(
				"client_id"  =>  $client_id,
				"date" => $date,
				"jobno"  =>  $jobno,
				"description" => $description,
				"jobname" => $jobnameR,
				"projecttype"=> $project_type_rc,
				"retainingContract" => "n",// retainer contract is to indicate a job that is quotation for retaining contract. here it should be n as it is a job that is under retainer client and not a retainer contract
				"jobseqno"=>$jobseq,
				"retainer_c_job" => "y",
				"retaining_c_monthno" => $month_jobno,
				"retaining_c_yearno" => $year_jobno,
				"retaining_c_jobnoformonth" => $jobindexformonth,
				"retainerscope" => $retainerscope,
				"ekbillable" => $billable, //ekbillable is column in the db table that is used for retaining jobs for both ek and non ek jobs.
				"approval" => $approval,
				"invoiced" => $invoiced,
				"division" => $division,
				"quote"=> $quoted_amount,
				"consolidated_check" => $addto_consolidated,
				"consolidated_jobno" => $consolidatedJobNo,
				"monthly_consol_jobno" => $monthly_consol_jobno_for_retainers,
				"jobraisedby" => $this->username
				);
				$insert = $this->Clients_model->add_retainer_job($jobs);
				if($insert)
				{
					$data['message'] = "Job has been added successfully.";
					// send email to brandon, caroline and haider if a job has been raised
					//if($client_id == 35) {
					
					$subject = "Notification on new job(".$jobno.") raised on Jobsheet Management System";
					$user = $this->ion_auth->user()->row_array();
					$fromemail = $user['email'];
					$full_name = $user['first_name']." ".$user['last_name'];
					//if a job is raised and approved
					if($approval == "y") {
						$emailText = "
						Dear Admin,<br>
						This is to notify you that a new job has been raised and approved on the Jobsheet Management System. Below are the details.<br><br>
						Client: ".$clientname." <br>
						Job No: ".$jobno."<br>
						Job Name: ".$jobnameR."<br>";
						$cc = $this->accountant_emailid;// "caroline@theandongroup.com,haider@theandongroup.com";
						$bcc = $this->account_manager_emailid;
					}
					else {
						$emailText = "
						Dear Admin,<br>
						This is to notify you that a new job has been raised on the Jobsheet Management System. Below are the details.<br><br>
						Client: ".$clientname."<br>
						Job No: ".$jobno."<br>
						Job Name: ".$jobnameR."<br>";
						$cc = $this->accountant_emailid;//"caroline@theandongroup.com";haider
						$bcc = $this->account_manager_emailid;
					}
					
					$from = $fromemail;
					$to = $this->b_emailid;//"brandon@theandongroup.com";
					
					//$bcc = "";
					$fullname = $full_name;
					$this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
					                      
				}
				else 
				{
					$data['message'] = "Job has not been added successfully."; 
				}
			} 
			else {
				$data['message'] = "Job has not been added successfully.(Google Recaptcha authentication error)"; 
			}
			}
			else
			{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
            $clients = $this->Clients_model->getClients();
			$data["clients"] = $clients;
            $retainingClients = $this->Clients_model->getRetainingClients();
			$data["retainingClients"] = $retainingClients;
			$consolidatedBClients = $this->Clients_model->getConsolidatedBClients();
			$data["consolidatedBClients"] = $consolidatedBClients;
			$projecttypes =  $this->Clients_model->listProjectTypes();
			$data["project_types"] = $projecttypes;
            $this->render_page('pages/addNonRetainingJob',$data);
		}
		
  /* 
 Function to add consolidated billing customer job 
  */
		public function addconsolidatedBCJob() {
			$this->body_class[] = 'addclient';
			$this->page_title = 'Add new job';
			$this->current_section = 'Add Job';

			$this->form_validation->set_rules('clientConsolidatedB', 'Client field', 'required');
			$this->form_validation->set_rules('dateConsolidatedBillingC', 'Date', 'required');
			$this->form_validation->set_rules('jobnoConsolidatedBillingC', 'Job no.', 'required');
			$this->form_validation->set_rules('descriptionConsolidatedBillingC', 'Description', 'required');
			$this->form_validation->set_rules('jobnameConsolidatedBillingC', 'Job name', 'required');

			if ($this->form_validation->run() == true)
			{
				//google captcha verification
			 $captchaToken = $this->input->post('token');
			 $verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
			 if($verifyToken ) {
				$client_id = $this->input->post('clientConsolidatedB');
				$clientnamefromid = $this->Clients_model->getClientNamebyID($client_id);		
				$clientname = $clientnamefromid[0]['clientname'];

				$date = $this->input->post('dateConsolidatedBillingC');
				$jobno = $this->input->post('jobnoConsolidatedBillingC');

				$description = $this->input->post('descriptionConsolidatedBillingC');
				$jobnameR = $this->input->post('jobnameConsolidatedBillingC');
				$project_type_ccb = $this->input->post('project_type_ccb'); //project type for  retainer
				$selectedOption = "";
				foreach ($project_type_ccb as $option => $value) {
				$selectedOption.= $value.'/'; // I am separating Values with a comma (,) so that I can extract data using explode()
				}
				$project_type_ccb = substr($selectedOption,0,-1); // to strip the last /
				$division = $this->input->post('company_division');
				if($division == '') $division = NULL;
				$quoted_amount = $this->input->post('quoted_amount');
				if($quoted_amount == null) $quoted_amount = 0;
				$jobseq =  substr($jobno,0,4);
				$month_jobno =  substr($jobno,5,2); // month no to be saved in the database to find next jobseqno.
				$year_jobno =  substr($date,0,4); // year no to be saved in the database to find next jobseqno.
				$jobindexformonth =  substr($jobno,8,2); // job index to be saved in the database to find next jobseqno. This +1 will be the next jobindex to be added in the final jobseqno
				$billable = $this->input->post('ccb_billable');
				$approval = $this->input->post('ccb_approval');
				$invoiced = $this->input->post('ccb_invoiced');
				($billable != 'y') ? $billable = 'n':$billable = 'y';
				($approval != 'y') ? $approval = 'n':$approval = 'y';
				($invoiced != 'y') ? $invoiced = 'n':$invoiced = 'y';
				if($approval == 'y') $billable = 'y';
				$jobs = Array(
					"client_id"  =>  $client_id,
					"date" => $date,
					"jobno"  =>  $jobno,
					"description" => $description,
					"jobname" => $jobnameR,
					"projecttype"=> $project_type_ccb,
					"consolidatedbillingcustomer" => "n",// consolidatedbillingcustomer is to indicate a job that is quotation for consolidated biling customer. here it should be n as it is a job that is opted for consolidated billing customer and not specific job under it
					"jobseqno"=>$jobseq,
					"consolidatedB_c_job" => "y",
					"consolidatedB_c_monthno" => $month_jobno,
					"consolidatedB_c_yearno" => $year_jobno,
					"consolidatedB_c_jobnoformonth" => $jobindexformonth,
					"consolidated_check" =>'y',//all jobs should be marked as consolidated by default
					"ekbillable" => $billable, //ekbillable is column in the db table that is used for retaining jobs for both ek and non ek jobs.
					"approval" => $approval,
					"invoiced" => $invoiced,								
					"division" => $division,
					"quote" => $quoted_amount,
					"jobraisedby" => $this->username
					);
				 $insert = $this->Clients_model->add_consolidatedBC_job($jobs);
			 		if($insert)
						{
							$data['message'] = "Job has been added successfully.";
							// send email to brandon, caroline and haider if a job has been raised
							//if($client_id == 35) {

							$subject = "Notification on new job(".$jobno.") raised on Jobsheet Management System";
							$user = $this->ion_auth->user()->row_array();
							$fromemail = $user['email'];
							$full_name = $user['first_name']." ".$user['last_name'];
							//if a job is raised and approved
								if($approval == "y") {
									$emailText = "
									Dear Admin,<br>
									This is to notify you that a new job has been raised and approved on the Jobsheet Management System. Below are the details.<br><br>
									Client: ".$clientname." <br>
									Job No: ".$jobno."<br>
									Job Name: ".$jobnameR."<br>";
									$cc = $this->accountant_emailid;// "caroline@theandongroup.com,haider@theandongroup.com";
									$bcc = $this->account_manager_emailid;
								}
								else {
								$emailText = "
								Dear Admin,<br>
								This is to notify you that a new job has been raised on the Jobsheet Management System. Below are the details.<br><br>
								Client: ".$clientname."<br>
								Job No: ".$jobno."<br>
								Job Name: ".$jobnameR."<br>";
								$cc = $this->accountant_emailid;//"caroline@theandongroup.com";haider
								$bcc = $this->account_manager_emailid;
								}

								$from = $fromemail;
								$to = $this->b_emailid;//"brandon@theandongroup.com";

								//$bcc = "";
								$fullname = $full_name;
								$this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);

						}
						else 
						{
								$data['message'] = "Job has not been added successfully."; 
						}
			 }
			 	else {
				    $data['message'] = "Job has not been added successfully.(Google Recaptcha authentication error)"; 
				}
		   }
		   else
			{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
			$clients = $this->Clients_model->getClients();
			$data["clients"] = $clients;
            $retainingClients = $this->Clients_model->getRetainingClients();
			$data["retainingClients"] = $retainingClients;
			$consolidatedBClients = $this->Clients_model->getConsolidatedBClients();
			$data["consolidatedBClients"] = $consolidatedBClients;
			$projecttypes =  $this->Clients_model->listProjectTypes();
			$data["project_types"] = $projecttypes;
            $this->render_page('pages/addNonRetainingJob',$data);

		}

		/* Function to add ek retainer job */
		public function addekretainerjob() 
        {
            $this->body_class[] = 'addclient';
			$this->page_title = 'Add new job';
			$this->current_section = 'Add Job';
            $this->form_validation->set_rules('clientEK', 'Client field', 'required');
			$this->form_validation->set_rules('dateEK', 'Date', 'required');
			$this->form_validation->set_rules('jobnoEK', 'Job no.', 'required');
			$this->form_validation->set_rules('descriptionEK', 'Description', 'required');
			$this->form_validation->set_rules('jobnameEK', 'Job name', 'required');
			if ($this->form_validation->run() == true)
			{
			 $captchaToken = $this->input->post('token');
			 $verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
			 if($verifyToken) {
				$client_id = $this->input->post('clientEK');
				$date = $this->input->post('dateEK');
				$jobno = $this->input->post('jobnoEK');  
				$under750 = $this->input->post('under750');
				$monthly_consol_jobno = $this->input->post('monthly_consol_jobno');
				// if under 750 is checked, then current month's consolidated job no. should be saved as consolidated job no.
				if($under750 == "y") {
					$jobnoWithoutIndex = substr($jobno,0,8);
					$consolidatedJobNo=$jobnoWithoutIndex."01";
				}
				else {
					$under750 = "n";
					$consolidatedJobNo = "";
				}
				$description = $this->input->post('descriptionEK');
				$jobnameEK = $this->input->post('jobnameEK');
				$project_type_ekr = $this->input->post('project_type_ekr'); //project type for eksc retainer
				
				$selectedOption = "";
				foreach ($project_type_ekr as $option => $value) {
					$selectedOption.= $value.'/'; // I am separating Values with a comma (,) so that I can extract data using explode()
	            }
				$project_type_ekr = substr($selectedOption,0,-1); // to strip the last /
				
				$jobseq =  substr($jobno,5,4);//eg: 0025
				$month_jobno =  substr($date,5,2); // month no to be saved in the database to find next jobseqno. It is taken from the date and not from the the jobseqno.
				$year_jobno =  substr($date,0,4); // year no to be saved in the database to find next jobseqno. It is taken from the date and not from the the jobseqno.
				$jobindexformonth =  substr($jobno,10,2); // job index to be saved in the database to find next jobseqno. This +1 will be the next jobindex to be added in the final jobseqno
				$ekbillable = $this->input->post('ekbillable');
				$ekapproval = $this->input->post('ekapproval');
				$ekinvoiced = $this->input->post('ekinvoiced');
				$retainerscope = $this->input->post('ekscope');//to check if the job falls under retaining contract or outside the scope of work
				
				
				// approval and invoiced and billable values have significance only if scope of work is not checked. if checked, the value for approved is y by default and for invoiced and approved it is n. 
				//  approved is set as "y" because of the greenflag in the jobno list is shown based on that
				//otherwise it will take the value what the user has entered. 
				//echo "scope".$retainerscope;
				if($retainerscope == 'y') //which means it doesnot need to be billed. It falls under retainer contract
				{
					$ekapproval = 'y';
					$ekinvoiced = 'y';
					$ekbillable  = 'n';
					$retainerscope = 'y';
				}
				else if($retainerscope != 'y'){ //which means it needs to be billed. It doesn't fall under retainer contract
					if($ekbillable  != 'y') {  $ekbillable = 'y';}  else if($ekbillable  == 'y'){$ekbillable = 'y';}
					if($ekapproval  != 'y') {  $ekapproval = 'n';}  else if($ekapproval  == 'y'){$ekapproval = 'y';}
					if($ekinvoiced  != 'y') {  $ekinvoiced = 'n';}   else if($ekinvoiced  == 'y') {$ekinvoiced = 'y';}
					
					$retainerscope  = 'n';
				}
				
				
				
				$jobs = Array(
				"client_id"  =>  $client_id,
				"date" => $date,
				"jobno"  =>  $jobno,
				"description" => $description,
				"jobname" => $jobnameEK,
				"projecttype" => $project_type_ekr,
				"retainingContract" => "n",// retainer contract is to indicate a job that is quotation for retaining contract. here it should be n as it is a job that is under retainer client and not a retainer contract
				"jobseqno"=>$jobseq,
				"eksc_retainer" => "y",
				"ekmonthno" => $month_jobno,
				"ekyearno" => $year_jobno,
				"ekjobnoformonth" => $jobindexformonth,
				"approved" => $ekapproval,
				"invoiced" => $ekinvoiced ,
				"ekbillable" => $ekbillable,
				"retainerscope" => $retainerscope,
				"consolidated_check" => $under750,
				"consolidated_jobno" => $consolidatedJobNo,
				"monthly_consol_jobno" => $monthly_consol_jobno,
				"jobraisedby" => $this->username
				);
				
				
				$insert = $this->Clients_model->add_ek_retainer_job($jobs);
				if($insert)
				{
					$data['message'] = "Job has been added successfully.";
					//Send an email to brandon, caroline and haider on new jobs
					$subject = "Notification on new job(".$jobno.") raised on Jobsheet Management System";
					$user = $this->ion_auth->user()->row_array();
					$fromemail = $user['email'];
					$full_name = $user['first_name']." ".$user['last_name'];
					//if a job is raised and approved
					if($ekapproval == "y") {
						$emailText = "
						Dear Admin,<br>
						This is to notify you that a new job has been raised and approved on the Jobsheet Management System. Below are the details.<br><br>
						Client: Emirates SkyCargo<br>
						Job No: ".$jobno."<br>
						Job Name: ".$jobnameEK."<br>";
						
						$cc = $this->account_manager_emailid;
						$bcc = $this->accountant_emailid;

					}
					else {
						$emailText = "
						Dear Admin,<br>
						This is to notify you that a new job has been raised on the Jobsheet Management System. Below are the details.<br><br>
						Client: Emirates SkyCargo<br>
						Job No: ".$jobno."<br>
						Job Name: ".$jobnameEK."<br>";
						$cc = $this->account_manager_emailid;
						$bcc = "";
						//$cc = "";
					}
					
					$from = $fromemail;
					$to = $this->b_emailid;										
					$fullname = $full_name;//"Andon Admin";
					$this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
				}
				else 
				{
					$data['message'] = "Job has not been added successfully."; 
				}
			 }
			 else {
				 $data['message'] = "Job has not been added successfully. (Google Recaptcha authentication error)"; 
			 }
			}
			else
			{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
            $clients = $this->Clients_model->getClients();
			$data["clients"] = $clients;
			
            $retainingClients = $this->Clients_model->getRetainingClients();
			$data["retainingClients"] = $retainingClients;
			$projecttypes =  $this->Clients_model->listProjectTypes();
			$data["project_types"] = $projecttypes;
            $this->render_page('pages/addNonRetainingJob',$data);
			
		}
		
		/* Function to get job code with next seq no. */
        public function getJobSeqNo()
        {
			$clientId  =  $this->uri->segment(2);
			
			$jobSeqNo = $this->Clients_model->getJobSeqNo($clientId);
			$data['jobSeqNo'] =  $jobSeqNo;
			echo json_encode($jobSeqNo);	
		}
		
		
		/* Function to get job code for retainer clients with next seq no. */
        public function getRetainerJobSeqNo()
        {
			$dateR = $this->input->post('dateR');
			$clientR = $this->input->post('clientR');
			$retainerJobSeqNo = $this->Clients_model->getRetainerJobSeqNo($dateR,$clientR);
			$data['retainerJobSeqNo'] =  $retainerJobSeqNo;
			echo json_encode($retainerJobSeqNo);	
		}

		/* Function to get job code for consolidated billing customers with next seq no. */
		public function getConsolidatedBillingJobcodes() {
			$dateR = $this->input->post('dateConsolidatedBillingC');
			$clientR = $this->input->post('clientConsolidatedB');
			$consolidateCustomerJobSeqNo = $this->Clients_model->getConsolidatedBillingCJobSeqNo($dateR,$clientR);
			$data['consolidateCustomerJobSeqNo'] =  $consolidateCustomerJobSeqNo;
			echo json_encode($consolidateCustomerJobSeqNo);
		}
        
        public function getEKRetainerJobSeqNo() {
			$dateEK = $this->input->post('dateEK');
			$EKretainerJobSeqNo = $this->Clients_model->getEKRetainerJobSeqNo($dateEK);
		//print_r($EKretainerJobSeqNo);
			$data['EKretainerJobSeqNo'] =  $EKretainerJobSeqNo;
			echo json_encode($EKretainerJobSeqNo);
		}
		public function listjobs()
		{
			//echo $this->uri->segment(1).','.$this->uri->segment(2).','.$this->uri->segment(3);
			//$pagin_offset = $this->uri->segment(2)== NULL?0:$this->uri->segment(2);
			//echo $pagin_limit;
			//$pagin_limit = 1000;
			$clients = $this->Clients_model->getAllClients(); //for the dropdown list for the search filter
			$data['clients'] =  $clients;
			$this->body_class[] = 'listjobs';
			$this->page_title = 'List existing jobs';
			$this->current_section = 'List of jobs';
			
			// $this->load->library('pagination');
			// $config['base_url'] = $this->jobs_url;
			// $config['total_rows'] = $this->Clients_model->countOfEnabledJobs();
			// $config['per_page'] = $pagin_limit;
			// $config['uri_segment'] = 2;
			// $this->pagination->initialize($config);
			// $data['pagin_links'] = $this->pagination->create_links();


			// $jobs  =  $this->Clients_model->listJobsWithPaginLimit($pagin_offset,$pagin_limit);
			$jobs  =  $this->Clients_model->listJobs();
			//echo "jobs2".count($jobs2).",";
			$user = $this->ion_auth->user()->row_array();
			$data['user'] = $user;
			$data["jobs"] = $jobs;
			
           // echo $this->Clients_model->countOfEnabledJobs();
			

			
			$this->render_page('pages/listjobs',$data);
		}
		/* Function to check if the client is retainer client like ek,dfm etc */
        public function checkIfRetainerJob()
        {
			$clientId =  $this->uri->segment(2);
			$return = "false";
			$retainerclients = $this->Clients_model->getRetainerClients();
			foreach($retainerclients as $key=>$value) {
				$k = $value['client_id'];
				if($k==$clientId) // if the client id is included in the list of retainer clients
				{                 
					$return = "true";
					break;
				}
				
			}
			$data['retainerJob'] =  $return;
			echo json_encode($return);	
		}
		public function listClients()
		{	
			
			$this->body_class[] = 'listclients';
			$this->page_title = 'List existing clients';
			$this->current_section = 'List of clients';
			$clients  =  $this->Clients_model->listAllClients();
			$data["clients"] = $clients;
			$this->render_page('pages/listclients',$data);
		}
		
		
		
		public function searchjobs() 
		{
			$clients  =  $this->Clients_model->getClients();
			$data["clients"] = $clients;
            
			$client = $this->input->post('client');
			$date = $this->input->post('date');
			$invoiced = $this->input->post('invoiced');
			$ekbillable  =   $this->input->post('ekbillable');
			$queryconditioncount = 0; 
			$queryconditions  =  "";
			$nosearch = 0;/* Flag to check whether the controller is loading for first time. ie if there is no search parameters assigned, then do not run the db query (to avoid the errors) */
			$queryitems  = Array();
			if( $client != 0)
			{
				$newarray1=  Array("client"=>$client);
				$queryitems = array_merge($queryitems,$newarray1);
				$nosearch = 1;
			}
			if( $date != NULL)
			{
				$newarray1=  Array("date"=>$date);
				$queryitems = array_merge($queryitems,$newarray1);
				$nosearch = 1;
			}
			if( $ekbillable == 'y'){
				$newarray1=  Array("ekbillable"=>$ekbillable);
				$queryitems = array_merge($queryitems,$newarray1);
				$nosearch = 1;
				
			}
			if( $invoiced == 'y'){
				$newarray1=  Array("invoiced"=>$invoiced);
				$queryitems = array_merge($queryitems,$newarray1);
				$nosearch = 1;
				
			}
			
			
			if($nosearch  ==  1)// only if some search parameter is entered
			{  
				$getResults =   $this->Clients_model->getJobsSearched($queryitems); 
				
				if($getResults) {$data["jobs"] =  $getResults;  $data['searchresults'] = "yes";}
				else {$data["noresults"] = "No results found. Try again";}
			}
			else   //if there is no search  filters chosen client will be 0 and clicked on submit, it will load the list job page
			{
				$getResults =   $this->Clients_model->listJobs();
				$data["jobs"] =  $getResults;  
				$data['searchresults'] = "yes";
				//redirect('list-jobs');
			}
			$user = $this->ion_auth->user()->row_array();
			$data['user'] = $user;
			$data['queryitems'] =  $queryitems;
			//print_r($queryitems);
			$this->render_page('pages/listjobs',$data);
		}
		
		
		
		
		/* Function to export job nos based on the search results*/
		public function exportJobNos()
		{
            $clients  =  $this->Clients_model->getClients();
			$data["clients"] = $clients;
            
			$client = $this->input->post('hiddenclient');
			$date = $this->input->post('hiddendate');
			$ekbillable  =   $this->input->post('hiddenekbillable');
			$invoiced =  $this->input->post('hiddeninvoiced');
			$queryconditioncount = 0; 
			$queryconditions  =  "";
			$nosearch = 0;/* Flag to check whether the controller is loading for first time. ie if there is no search parameters assigned, then do not run the db query (to avoid the errors) */
			$queryitems  = Array();
			if( $client != 0)
			{
				$newarray1=  Array("client"=>$client);
				$queryitems = array_merge($queryitems,$newarray1);
				$nosearch = 1;
			}
			if( $date != NULL)
			{
				$newarray1=  Array("date"=>$date);
				$queryitems = array_merge($queryitems,$newarray1);
				$nosearch = 1;
			}
			if( $ekbillable == 'y'){
				$newarray1=  Array("ekbillable"=>$ekbillable);
				$queryitems = array_merge($queryitems,$newarray1);
				$nosearch = 1;
				
			}
			if( $invoiced == 'y'){
				$newarray1=  Array("invoiced"=>$invoiced);
				$queryitems = array_merge($queryitems,$newarray1);
				$nosearch = 1;
				
			}
			
			if($nosearch  ==  1)// Initialized on top. DB query will be run only if there is some parameter selected
			{  
				$getResults =   $this->Clients_model->getJobsSearched($queryitems); 
				
			}
			else   //if there is no search filters chosen and clicked on submit, it will load the list job page
			{
				$getResults =   $this->Clients_model->listJobs(); 
			}
			// Preparing the excelsheet with data from the database
			
			//load our new PHPExcel library
			$this->load->library('excel');
			//activate worksheet number 1
			$this->excel->setActiveSheetIndex(0);
			//name the worksheet
			$this->excel->getActiveSheet()->setTitle('Timesheets ');
			//set cell A1 content with some text
			
			
			//set aligment to center for that merged cell (A1 to D1)
			$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)->setFitToWidth(1)->setFitToHeight(0);
			$this->excel->getActiveSheet()->getHeaderFooter()->setOddHeader('&C&B&16' . 
			$this->excel->getProperties()->getTitle())
			->setOddFooter('&CPage &P of &N');
			$col = 'A';
			$rownum = 1;
			$colnum = 1;
			
			$k=1; // rownumber for the first row
			for($col = 'A'; $col !== 'H'; $col++) {
				$this->excel->getActiveSheet()->getColumnDimension($col)    
				->setAutoSize(true); //set the column width auto
				$this->excel->getActiveSheet()->getStyle($col . $k)->getFont()->setBold(true); //set font style bold forthe first row (title)
			}
			$i=1;
			$rownum = 2; //row starts feom second coz the first reserved for the titles
			
			//setting the headers
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Sl. No.");	
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1,1,"Client");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Job No");	
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Job Name");			
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4,1,"Description");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5,1,"Type");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,1,"Date");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,1,"Invoiced");				
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8,1,"Approved");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9,1,"Billable");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10,1,"Consolidate Billing");
			
			// set the data
			$columnnum = 0;
			$rownum = 2;
			
			$i = 1;
			foreach($getResults as $key=>$value){
				
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,$rownum,$i);   
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1,$rownum,$value['clientname']);	
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2,$rownum,$value['jobno']);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3,$rownum,$value['jobname']);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4,$rownum,$value['description']);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5,$rownum,$value['project_type']);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,$rownum,$value['date']);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,$rownum,$value['invoiced']);				 
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(8,$rownum,$value['approved']);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(9,$rownum,$value['ekbillable']);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(10,$rownum,$value['consolidated_check']);
				$columnnum++;$rownum++;$i++;
			}
			
			$filename='jobnos.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
		}
		
		
		public function disableClient()
		{
			
			$clientIdToBeDeleted =  $this->uri->segment(2);
			$deleteJob = $this->Clients_model->disableClient($clientIdToBeDeleted);
			$clients  =  $this->Clients_model->listAllClients();
			//echo "clientstobedeleted".$clientIdToBeDeleted;
			if($deleteJob) {$data["message"] = "Client has been disabled succesfully.";} else {$data["message"] = "Could not successfully disable the cient. The client already is disabled or it doesnt exist.";}
			$data["clients"] = $clients;
			$this->render_page('pages/listclients',$data);
			
		}
		
		public function disableJobs()
		{
			
			$jobIdToBeDeleted =  $this->uri->segment(2);
			$deleteJob = $this->Clients_model->disableJob($jobIdToBeDeleted);
			
			if($deleteJob) { $message = 1;} else {$message = 0;}
			echo json_encode($message);
			
		}
		
		public function deleteJobs()
		{
			
			$jobIdToBeDeleted =  $this->uri->segment(2);
			$deleteJob = $this->Clients_model->deleteJob($jobIdToBeDeleted);
			
			if($deleteJob) { $message = 1;} else {$message = 0;}
			echo json_encode($message);
			
		}
		public function editjob()
		{
			
			//$toemail = "brandon@theandongroup.com";
			//$ccemailid = "haider@theandongroup.com,caroline@theandongroup.com";
			$jobIdToBeEdited =  $this->uri->segment(2); // fetching the job to be editeed from the url
			$clients = $this->Clients_model->getClients(); //for the dropdown list
			
			$data['clients'] =  $clients;
			
			
			
			// $this->form_validation->set_rules('client', 'Client name', 'required');
			//$this->form_validation->set_rules('date', 'Date', 'required');
				$this->form_validation->set_rules('jobname', 'Job name', 'required');
			//$this->form_validation->set_rules('description', 'Description', 'required');
			
			
			if ($this->form_validation->run() == true)
			{
				
				$approved  = $this->input->post('approval');
				//to check if the job is approved before, if its already approved then no need to send email notification
				$approved_currentstatus  = $this->input->post('approved_previous');
				$jobno_hidden  = $this->input->post('jobno');
				$client_hidden = $this->input->post('client_hidden');
				$clientid_hidden = $this->input->post('clientid_hidden');
				$jobname = $this->input->post('jobname');
				$description = $this->input->post('description');
				$project_type = $this->input->post('project_type');
				$selectedOption = "";
				foreach ($project_type as $option => $value) {
				$selectedOption.= $value.'/'; // I am separating Values with a comma (/) so that I can extract data using explode()
	            }
				$project_type = substr($selectedOption,0,-1);// to strip the last /;
				
				$invoiced  = $this->input->post('invoiced');
				$ekbillable = $this->input->post('ekbillable');
				$jobclosed = $this->input->post('jobclosed');
				if($jobclosed == "y") $dateofclosure = date('Y-m-d'); else $dateofclosure = null;
				$quote = $this->input->post('quoted_amount');
				if($quote == null) $quote = 0;
				$division = $this->input->post('company_division');
				if($division == '') $division = NULL;
				$retainerscope = $this->input->post('retainerscope');
				

				$checkEKretainer = $this->input->post('checkEKretainer');	
				$checkOtherretainer = $this->input->post('checkOtherretainer');		
				
				$consolidated_check = $this->input->post('under750');
				if($consolidated_check == "y")
				{
					$consolidated_jobno = $this->input->post('consolidateJobNo');
				}
				else {
					$consolidated_check = "n";
					$consolidated_jobno = "";
				}

				$checkconsolidatedBCjob = $this->input->post('checkconsolidatedBCjob');
				if($checkconsolidatedBCjob == "y") $consolidated_check = "y"; // if its a job that comes under consolidated billing customer, it should be always y for consolidated check field


				if($approved  == "") $approved  = "n";
				if($invoiced  == "") $invoiced  = "n";
				if($ekbillable == "")  $ekbillable = "n";
				if($checkEKretainer == 'y' || $checkOtherretainer == 'y')	// only if the job is a retaining one (either EK or other)
				{
					if($retainerscope == "" || $retainerscope == "n")  { $retainerscope = "n"; $ekbillable = "y";} else if($retainerscope == "y") {$retainerscope = "y"; $approved  = "y";$invoiced="y"; }
				}	
				else	{ /* for non retaining jobs, if its approved, it should be billable*/
					if($approved  == "y")		{  
						$ekbillable = "y";
					}		
					else { /* this is added if an approved job is disapproved later. for non retaining jobs there is no value for billable from the form*/
						$ekbillable = "n";							
					}					  
				}
				if($retainerscope == "") $retainerscope = "n";
				$jobs = Array(
				"id" => $jobIdToBeEdited,
				"approved"  => $approved ,		
				"description" => $description,
				"jobname" => $jobname,
				"invoiced" =>  $invoiced,
				"jobclosed"=>$jobclosed,
				"dateofclosure"=> $dateofclosure,
				"ekbillable" =>  $ekbillable,
				"retainerscope" =>$retainerscope,
				"consolidated_check" =>$consolidated_check,
				"consolidated_jobno" =>$consolidated_jobno,
				"projecttype" => $project_type,
				"quote" => $quote,
				"division" => $division
				);
				
				$update  = $this->Clients_model->update_job($jobs);
				if($update) 
				{
					$data['message'] = "Job has been updated successfully.";	
					if($approved == "y" && $approved_currentstatus != "y" ) //if an unapproved job is approved
					{
						
						$subject = "Notification on approval of job(".$jobno_hidden.")";
						$user = $this->ion_auth->user()->row_array();
						$fromemail = $user['email'];
						
						$full_name = $user['first_name']." ".$user['last_name'];
						$emailText = "
						Dear Admin,<br>
						This is to notify you that a job has been approved on the Jobsheet Management System. Below are the details.<br><br>
						Client: ".$client_hidden."<br>
						Job No: ".$jobno_hidden."<br>
						Job Name: ".$jobname."<br>";
						
						
						
						$from = $fromemail;
						$to = $this->b_emailid;
						$cc = $this->accountant_emailid;
						$bcc = "";
						$fullname = $full_name;
						//$this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
					}
					else if($jobclosed == "y") {
						$toemail = $this->b_emailid;
						$ccemail = $this->accountant_emailid;
						$subject = "Notification on closure of job(".$jobno_hidden.")";
						$user = $this->ion_auth->user()->row_array();
						$fromemail = $user['email'];
						$full_name = $user['first_name']." ".$user['last_name'];
						$emailText = "
						Team,<br>
						This is to notify you that a job has been closed on the Jobsheet Management System. Below are the details. Please update your timesheets for the same.<br><br>
						Client: ".$client_hidden."<br>
						Job No: ".$jobno_hidden."<br>
						Job Name: ".$jobname."<br>";
						$from = $fromemail;
						$to = $toemail;
						$cc = $ccemail;
						$bcc = "";
						$fullname = $full_name;
						$this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
					}
				}
	            else 
				{
					$data['message'] = "Update unsuccessful.";
				}
			}
			else{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
			
			if($jobIdToBeEdited != NULL) {
				$jobDetails = $this->Clients_model->selectJobById($jobIdToBeEdited);
				$data["jobDetails"]  =  $jobDetails;
			}
			$projecttypes =  $this->Clients_model->listProjectTypes();
			$data["project_types"] = $projecttypes;
			//echo $jobIdToBeEdited;
			$cl_id = $this->Clients_model->getClientIDbyJobid($jobIdToBeEdited);
			//print_r( $cl_id );
			if($cl_id != null) $divisions =  $this->Clients_model->getDivisionbyClientID($cl_id);
			$data["clientdivisions"] = $divisions;
			$this->render_page('pages/editjob',$data);
			
		}
		
		public function editClient()
		{    
			$cientTobeEdited =  $this->uri->segment(2);     
            $clients  =  $this->Clients_model->getClientNamebyID($cientTobeEdited);
			$this->body_class[] = 'addclient';
			$this->page_title = 'Add new Client';
			$this->current_section = 'Add Client';
            $this->form_validation->set_rules('clientcode', 'Client field', 'required');
			if ($this->form_validation->run() == true) {
                $clientcode = $this->input->post('clientcode');
				$enabled = $this->input->post('enabled');
				$consolidated_billing_retainer =  $this->input->post('consolidated_billing_for_retainer');
				if($enabled == "" || $enabled == "") $enabled = 'n';
				if($consolidated_billing_retainer == "" || $consolidated_billing_retainer == "") $consolidated_billing_retainer = 'n';
                $client = Array(
                "id" => $cientTobeEdited,
                "client_code"=> $clientcode,
				"enabled" => $enabled,
				"consolidated_billing_for_retainer" => $consolidated_billing_retainer
                );
                $updateClient  =  $this->Clients_model->updateClient($client);
				if($updateClient)
				{
					$data['message'] = "Client has been updated successfully.";
				}
				else 
				{
					$data['message'] = "Client has not been updated successfully."; 
				}
			}
			else
			{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
			$clients  =  $this->Clients_model->getClientNamebyID($cientTobeEdited);
            $data['clientDetails'] =  $clients;
			$this->render_page('pages/editclient',$data);
			
		}

		public function viewreports(){
			
			//$this->render_page('pages/view_reports');
			$client = $this->input->post('clientname');
			$reportdate = $this->input->post('reportdate');
						
			
			if($client != null && $reportdate !=null)			
			  {
				$datesplit = explode(' ', $reportdate);
				$month = $datesplit[0];
			    $year = $datesplit[1];
				$jobs = Array(
					"clientid" => $client,
					"month" => $month,
					"year" => $year
				);
				
				$jobsfetched  =  $this->Clients_model->listJobsReport($jobs); 
			    $data["jobs"] = $jobsfetched;
				$data['isadmin']  =  $this->ion_auth->is_admin();
				$data['jobsearchparams'] = $jobs;
				$this->render_page('pages/view_reports',$data);
			  }
			  else {
				$this->render_page('pages/view_reports');
			  }
			
		}

		public function getmonthname($monthno) {
			$months = [
				1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
				5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
				9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
			];
		
			return $months[$monthno];
		}

		public function exportmonthlyreport() {
			
			$client = $this->input->post('hiddenclientname');
			$month = $this->input->post('hiddenmonth');
			$year = $this->input->post('hiddenyear');
			//echo $client." ".$month." ".$year;
			$monthname = $this->getmonthname($month);
			$clientdetail = Array(
				"clientid" => $client,
				"month" => $month,
				"year" => $year
			);
			$divisionsperclient  =  $this->Clients_model->getDivisionsPerMonthPerClient($clientdetail); 
			//echo "month".$month;
			if($client != null && $month != null && $year != null) {
				$jobs = Array(
					"clientid" => $client,
					"month" => $month,
					"year" => $year
				);
			$jobsfetched  =  $this->Clients_model->listJobsReport($jobs); 
				//load our new PHPExcel library
			$this->load->library('excel');
			//activate worksheet number 1
			$this->excel->setActiveSheetIndex(0);
			//name the worksheet
			$this->excel->getActiveSheet()->setTitle('Timesheets ');
			//set cell A1 content with some text
			
			
			//set aligment to center for that merged cell (A1 to D1)
			$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)->setFitToWidth(1)->setFitToHeight(0);
			$this->excel->getActiveSheet()->getHeaderFooter()->setOddHeader('&C&B&16' . 
			$this->excel->getProperties()->getTitle())
			->setOddFooter('&CPage &P of &N');
			$col = 'A';
			$rownum = 1;
			$colnum = 1;
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,1,$monthname.", ".$year." Recharge Report"); 
			$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
			
			// $this->excel->getActiveSheet()
            // ->getStyleByColumnAndRow(0, 1)
            // ->getAlignment()
            // ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
			$k=2; // rownumber for the second row
			for($col = 'A'; $col !== 'I'; $col++) {
				$this->excel->getActiveSheet()->getColumnDimension($col)    
				->setAutoSize(true); //set the column width auto
				$this->excel->getActiveSheet()->getStyle($col . $k)->getFont()->setBold(true); //set font style bold forthe first row (title)
			}
			
			$i=1;
			$rownum = 2; //row starts feom second coz the first reserved for the titles
			
			//setting the headers
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,2,"Sl. No.");	
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1,2,"Division");	
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2,2,"Job");			
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3,2,"Job Name");			
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4,2,"Description");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5,2,"Date");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,2,"Amount");
			// $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,2,"Status");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,2,"Total");

			// set the data
			$columnnum = 0;
			$rownum = 3;
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,$rownum,"Retainer Jobs"); 
			$this->excel->getActiveSheet()->getStyle('A'.$rownum)->getFont()->setBold(true);
			$rownum = $rownum + 1;
			$i = 1;
			$totalvalueofmonth = 0;
			// for retainer jobs
			if(isset($divisionsperclient)) {
				if(!empty($divisionsperclient)) {
				foreach($divisionsperclient as $key=>$value) {
					    $currentdiv = $value['division'];
						$currentdivcolor = $this->Clients_model->getColorForDivision($currentdiv);
						
					    $jobsperdivision = $this->Clients_model->getJobsPerDivision($clientdetail,$currentdiv,true); //third paramaeter is for retainer
						//print_r($jobsperdivision);
						if(!empty($jobsperdivision)) {
						$countofjobs = count($jobsperdivision);
						$count = 1;
						$totalvalueperdiv = 0;
						foreach($jobsperdivision as $key1=>$value1) { 
							// if($value1['jobclosed'] == 'y' ) $status = "Closed"; else $status = '';
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,$rownum,$i);   
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1,$rownum,$value1['division']);
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2,$rownum,$value1['jobno']);					
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3,$rownum,$value1['jobname']);
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4,$rownum,$value1['description']);
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5,$rownum,date('d/M/Y',strtotime($value1['date'])));
								$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,$rownum,$value1['quote']);
								// $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,$rownum,$status);
								$totalvalueperdiv = $totalvalueperdiv + $value1['quote'];
								$totalvalueofmonth = $totalvalueofmonth + $value1['quote'];
								if($countofjobs == $count) $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,$rownum,$totalvalueperdiv);
								for($col = 'A'; $col !== 'I'; $col++) {
									$this->excel->getActiveSheet()->getStyle($col.$rownum)->applyFromArray(
										array(
											'fill' => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'color' => array('rgb' => $currentdivcolor['color']) 
											),
											'font' => array(
												'color' => array('rgb' => $currentdivcolor['textcolor']),
											)
										)
									);
								}

								$columnnum++;$rownum++;$i++;
								$count++;
						}
						
					}														
					
				}
			}
			} 
					$rownum = $rownum + 2;
					$this->excel->getActiveSheet()->getStyle('A' . $rownum)->getFont()->setBold(true);
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,$rownum,"Non retainer Billing"); 
					$rownum = $rownum + 1;
					$j = 1;

			// for non retainer jobs
  
			if(isset($divisionsperclient)) {  
				$k = 1;
				if(!empty($divisionsperclient)) {
					foreach($divisionsperclient as $key=>$value) {
							$currentdiv = $value['division'];
							$currentdivcolor = $this->Clients_model->getColorForDivision($currentdiv);
							$jobsperdivisionnonretainer = $this->Clients_model->getJobsPerDivision($clientdetail,$currentdiv,false); //third paramaeter is for retainer
							//print_r($jobsperdivision);
							if(!empty($jobsperdivisionnonretainer)) {
								$countofjobs_nr = count($jobsperdivisionnonretainer);
								$count_nr = 1;
								$totalvalueperdiv_nr = 0;
							foreach($jobsperdivisionnonretainer as $key1=>$value1) { 
								// if($value1['jobclosed'] == 'y' ) $status = "Closed"; else $status = '';
									$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,$rownum,$k);   
									$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1,$rownum,$value1['division']);
									$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2,$rownum,$value1['jobno']);					
									$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3,$rownum,$value1['jobname']);
									$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4,$rownum,$value1['description']);
									$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5,$rownum,date('d/M/Y',strtotime($value1['date'])));
									$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,$rownum,$value1['quote']);
									// $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,$rownum,$status);
									$totalvalueperdiv_nr = $totalvalueperdiv_nr + $value1['quote'];
									$totalvalueofmonth = $totalvalueofmonth + $value1['quote'];
								    if($countofjobs_nr == $count_nr) $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,$rownum,$totalvalueperdiv_nr);
									for($col = 'A'; $col !== 'I'; $col++) {
										$this->excel->getActiveSheet()->getStyle($col.$rownum)->applyFromArray(
											array(
												'fill' => array(
													'type' => PHPExcel_Style_Fill::FILL_SOLID,
													'color' => array('rgb' => $currentdivcolor['color']) 
												),
												'font' => array(
												     'color' => array('rgb' => $currentdivcolor['textcolor']),
												)
											)
										);
									}
									$columnnum++;$rownum++;$k++;
									$count_nr++;
							}
							
						}
					}
				}

			}
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,$rownum,"Total amount");
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,$rownum,$totalvalueofmonth);
			$this->excel->getActiveSheet()->getStyle('G' . $rownum)->getFont()->setBold(true);
			$this->excel->getActiveSheet()->getStyle('H' . $rownum)->getFont()->setBold(true);	 
            $rownum = $rownum+4;
			$divisionlegend = $this->Clients_model->getAllDivisionsandColors();
			//print_r($divisionlegend);
			$legendcount = 0;
			for($i=0;$i<5;$i++) {
				for($col = 1; $col <= 8; $col+=2) {					
						if(isset($divisionlegend[$legendcount]['division']))	
						$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,$rownum,$divisionlegend[$legendcount]['division']);
						
						if($col == 1) $newcol = "A";
						else if($col == 3) $newcol = "C";
						else if($col == 5) $newcol = "E";
						else if($col == 7) $newcol = "G";
						
						if(isset($divisionlegend[$legendcount]['division']))	{						
							$this->excel->getActiveSheet()->getStyle($newcol.$rownum)->applyFromArray(
											array(
												'fill' => array(
													'type' => PHPExcel_Style_Fill::FILL_SOLID,
													'color' => array('rgb' => $divisionlegend[$legendcount]['color']) 
												),
												
											)
										);
						}
						$legendcount++;
				}
				
				$rownum++;
			}
			
			$filename='jobspermonth.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
			//force user to download the Excel file without writing it to server's HD
			$objWriter->save('php://output');
			}
			//$this->render_page('pages/view_reports');
		}
		
		public function add_division(){

			$clients = $this->Clients_model->getClients();
			$data["clients"] = $clients;
			$this->form_validation->set_rules('divisionname', 'Division field', 'required');
			if ($this->form_validation->run() == true) {
                $clientcode = $this->input->post('client');
				$divisionname = $this->input->post('divisionname');
				$divisioncolor = $this->input->post('divisioncolor');
				if($divisioncolor == '') $divisioncolor = NULL;
				$divisiontextcolor = $this->input->post('divisiontextcolor');
				if($divisiontextcolor == '') $divisiontextcolor = NULL;
				$divisions = Array(
					"division" => $divisionname,
					"client" => $clientcode,
					"color" => $divisioncolor,
					"textcolor" => $divisiontextcolor
				);
				$insert = $this->Clients_model->add_division($divisions);
				if($insert)
				{
					$data['message'] = "Division has been added succesfully.";
				}
				else 
				{
					$data['message'] = "Division hasn't been added succesfully."; 
				}
		    }

			$this->render_page('pages/add_division',$data);	
		}

		public function list_divisions() {
			$divisions = $this->Clients_model->listDivisions();
			$data["divisions"] = $divisions;
			$this->render_page('pages/list_divisions',$data);	
		}

		public function edit_division(){
			$divisionId =  $this->uri->segment(2);     
            
			$clients = $this->Clients_model->getClients();
			$data["clients"] = $clients;
			//$this->body_class[] = 'addclient';
			$this->page_title = 'Edit division';
			$this->current_section = 'Add division';
            $this->form_validation->set_rules('divisionname', 'Division field', 'required');
			if ($this->form_validation->run() == true) {
                $divisionname = $this->input->post('divisionname');
				$client = $this->input->post('client');
				$color = $this->input->post('divisioncolor');
				$textcolor = $this->input->post('divisiontextcolor');
				 $divisionentry = Array(
                "id" => $divisionId,
				"client" => $client,
                "division"=> $divisionname,
				"color"=> $color,
				"textcolor"=> $textcolor,
                );
                $updateDivision  =  $this->Clients_model->updateDivision($divisionentry);
				if($updateDivision)
				{
					$data['message'] = "Division has been updated successfully.";
				}
				else 
				{
					$data['message'] = "Division has not been updated successfully."; 
				}
			}
			$divisiondetails  =  $this->Clients_model->getDivisionbyID($divisionId);
			$data['division'] = $divisiondetails;
		    $this->render_page('pages/edit_division',$data);
		}
		public function delete_division(){
			$divisionId =  $this->uri->segment(2);     
            $delete = $this->Clients_model->deleteDivision($divisionId);
			$divisions = $this->Clients_model->listDivisions();
			$data["divisions"] = $divisions;
			if($delete) {$data["message"] = "Division has been deleted succesfully.";} else {$data["message"] = "Deletion Unsuccesfull";}
			
			$this->render_page('pages/list_divisions',$data);
		}


		public function find_divisions_by_client() {
			$clientid =  $this->uri->segment(2); 
			$divisions = $this->Clients_model->getDivisionbyClientID($clientid);
			echo json_encode($divisions);	
		}
	}	