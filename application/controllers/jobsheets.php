<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jobsheets extends App_Controller
{
            public $username;
	public $searchres=Array();	
	public function __construct()
	{
		parent::__construct();
		 if (!$this->ion_auth->logged_in())
		{
			redirect('login');
		}
		$this->load->library('pagination');
		  $this->load->model("Jobsheets_model");
		  $this->load->model("Clients_model");
		  $user = $this->ion_auth->user()->row();
		 $this->username = $user->id;
		/*else
		{
		        if (!$this->ion_auth->is_admin())
		         {
			$this->session->set_flashdata('notadmin', 'You must be an admin to view this page');
			redirect('home');
		           }
		}*/
	}
     /*
	Index function.
    Load all the jobsheet items with respect to date and day of current week.	
	Author: Faris M A    	
  */
	public function index()
	{	  
	      /*  Get clients and jobcodes default for the select dropdown list in the new rows.  */
		$clients = $this->Jobsheets_model->getClients();
		$data['clients'] =  $clients;
		/*$jobsperclient =  $this->Jobsheets_model->getJobsperClient();
		$data['jobsperclient'] =  $jobsperclient;  */
		
		/* Set the days and dates of current week */
		$dates = $this->Jobsheets_model->setDaysandDate();
		/* Assign the days and dates of current week to variables to be passed as parameter for database fetch and the to be displayed in the views */
		$data["datesunday"] = $dates["datesunday"];
		$data["daysunday"] =  $dates["daysunday"];
		$data["datemonday"] =  $dates["datemonday"];
		$data["daymonday"] = $dates["daymonday"];
		$data["datetuesday"] =  $dates["datetuesday"];
		$data["daytuesday"] =  $dates["daytuesday"];
		$data["datewednesday"] =  $dates["datewednesday"];
		$data["daywednesday"] =  $dates["daywednesday"];
		$data["datethursday"] =  $dates["datethursday"];
		$data["daythursday"] =  $dates["daythursday"];
		$data["datefriday"] =  $dates["datefriday"];
		$data["dayfriday"] =  $dates["dayfriday"];
		$data["datesaturday"] =  $dates["datesaturday"];
		$data["daysaturday"] =  $dates["daysaturday"];
		
						
                        /* 
		Code for pagination		
		if (!$this->ion_auth->is_admin())
			   {
				$rows  =  $this->Jobsheets_model->getCountofJobsheets($this->username);
			   }
		   else
			   {
				$rows  =  $this->Jobsheets_model->getCountofJobsheetsforAdmin();		   
			   }		
		$config['base_url'] = site_url('jobsheets');
		$config['total_rows'] = $rows;
		$config['per_page'] =5; 
		$num_links = $config['total_rows']/$config['per_page'];
		$config['num_links'] = round($num_links);
		$limit = $config['per_page'];
		$this->pagination->initialize($config); 
		$page = ($this->uri->segment(2))? $this->uri->segment(2) : 0;
		echo $this->pagination->create_links();
        Code for pagination ends here 
		*/
		/* Get jobsheets from the datebase for particular user and date. If admin fetch all irrespective of user on date basis. */
	       if (!$this->ion_auth->is_admin())
		   {
		             $data['jobsheets_sunday']           =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datesunday"] );
			 $data['jobsheets_monday']           =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datemonday"] );
			 $data['jobsheets_tuesday']        =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datetuesday"] );
			 $data['jobsheets_wednesday']  =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datewednesday"] );
			 $data['jobsheets_thursday']     =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datethursday"] );
			 $data['jobsheets_friday']           =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datefriday"] );
			 $data['jobsheets_saturday']     =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datesaturday"] );
		   }
		   else
		   {
			$data['jobsheets_sunday']            =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datesunday"]);
		            $data['jobsheets_monday']            =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datemonday"]);
			$data['jobsheets_tuesday']         =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datetuesday"]);
			$data['jobsheets_wednesday']   =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datewednesday"]);
			$data['jobsheets_thursday']      =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datethursday"]);
			$data['jobsheets_friday']            =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datefriday"] );
			$data['jobsheets_saturday']      =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datesaturday"]);
						  
		   }
		
		$data["currentweek"] = 1;
		$this->render_page('pages/listjobsheetitems',$data);
	}
	     /*
	Index function.
    Load all the jobsheet items with respect to date and day of previous week.	
	Author: Faris M A    	
  */
	public function getPreviousweekJobsheets() {
	$clients = $this->Jobsheets_model->getClients();
		$data['clients'] =  $clients;
		/*$jobsperclient =  $this->Jobsheets_model->getJobsperClient();
		$data['jobsperclient'] =  $jobsperclient; */
		
		$week = "prev";
		$dates = $this->Jobsheets_model->setDaysandDate($week);

		$data["datesunday"] = $dates["datesunday"];
		$data["daysunday"] =  $dates["daysunday"];
		$data["datemonday"] =  $dates["datemonday"];
		$data["daymonday"] = $dates["daymonday"];
		$data["datetuesday"] =  $dates["datetuesday"];
		$data["daytuesday"] =  $dates["daytuesday"];
		$data["datewednesday"] =  $dates["datewednesday"];
		$data["daywednesday"] =  $dates["daywednesday"];
		$data["datethursday"] =  $dates["datethursday"];
		$data["daythursday"] =  $dates["daythursday"];
		$data["datefriday"] =  $dates["datefriday"];
		$data["dayfriday"] =  $dates["dayfriday"];
		$data["datesaturday"] =  $dates["datesaturday"];
		$data["daysaturday"] =  $dates["daysaturday"];
		
						
            
		
	       if (!$this->ion_auth->is_admin())
		   {
		             $data['jobsheets_sunday']           =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datesunday"] );
			 $data['jobsheets_monday']           =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datemonday"] );
			 $data['jobsheets_tuesday']        =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datetuesday"] );
			 $data['jobsheets_wednesday']  =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datewednesday"] );
			 $data['jobsheets_thursday']     =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datethursday"] );
			 $data['jobsheets_friday']           =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datefriday"] );
			 $data['jobsheets_saturday']     =    $this->Jobsheets_model->getJobsheetsbyDate($this->username,$data["datesaturday"] );
		   }
		   else
		   {
			$data['jobsheets_sunday']            =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datesunday"]);
		            $data['jobsheets_monday']            =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datemonday"]);
			$data['jobsheets_tuesday']         =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datetuesday"]);
			$data['jobsheets_wednesday']   =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datewednesday"]);
			$data['jobsheets_thursday']      =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datethursday"]);
			$data['jobsheets_friday']            =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datefriday"] );
			$data['jobsheets_saturday']      =     $this->Jobsheets_model->getJobsheetsbyDateforAdmin($data["datesaturday"]);
						  
		   }
		
		$data["previousweek"] = 1;
		$this->render_page('pages/listjobsheetitems',$data);
		}
	
/*
	Jobcodes function.
    Load all the jobcodes which are enabled for a given client. Called from Ajax request.	
	Author: Faris M A    	
  */
	
	public function populateJobCodes()
	{
	                              $clientrowtable =  $this->uri->segment(2);
			     $clientAttribName = $clientrowtable;	
                                    			  
	                                 $client_id =   $this->input->post($clientAttribName);
			             $jobsperclient =  $this->Jobsheets_model->getJobsperClient($client_id);
                                            $data['jobsperclient'] =  $jobsperclient;
			       echo json_encode($jobsperclient);						
	}
    /*
	Jobcodes function.
    Load all the jobcodes for a given client irrespective of the status of enabled field. Called from Ajax request.	
	Author: Faris M A    	
  */
    public function populateAllJobCodes()
    {
     $clientrowtable =  $this->uri->segment(2);
			     $clientAttribName = $clientrowtable;	
                                    			  
	                                $client_id =   $this->input->post($clientAttribName);
			           //  $jobsperclient =  $this->Jobsheets_model->getAllJobsperClient($client_id);
					$jobsperclient =  $this->Jobsheets_model->getAllJobsperClientLimit1000($client_id);
                                            $data['jobsperclient'] =  $jobsperclient;
			       echo json_encode($jobsperclient);	
    
    }
	/*
	Function for new jobsheet addition form (Individual page for admin. Not the one which shows the list of all jobsheets on date basis).
    
	Author: Faris M A    	
  */
	public function addJobsheetItems()
	{
	                        /* Load default clients and jobcodes */
	                        $clients = $this->Jobsheets_model->getClients();
                                    $data['clients'] =  $clients;
		            /*$jobsperclient =  $this->Jobsheets_model->getJobsperClient();			
			 $data['jobsperclient'] =  $jobsperclient;*/
			 $users  = $this->Jobsheets_model->getUsers();
			$data['users'] =  $users;
			 
			 
		          $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
                                 /* Set up various input fields in the form,  */
			$data['jobno'] = array('name' => 'jobno',
				'id' => 'jobno',
				'type' => 'text',
				'value' => $this->form_validation->set_value('jobno'),
				'class' => 'input-block-level',
				'placeholder' => 'Job code',
				 'style'       => 'width:100px'
				
			);
			$data['client'] = array('name' => 'client',
				'id' => 'client',
				'type' => 'text',
				'class' => 'input-block-level',
				'placeholder' => 'Company',
				 'style'       => 'width:100px'
			);
			$data['date'] = array('name' => 'date',
				'id' => 'date',
				'type' => 'text',
				'value' => $this->form_validation->set_value('date'),
				'class' => 'input-block-level date',
				'placeholder' => 'Date',
				'size'=>12
			);
			$data['timespent'] = array('name' => 'timespent',
				'id' => 'timespent',
				'type' => 'text',
				'value' => $this->form_validation->set_value('timespent'),
				'class' => 'input-block-level',
				'placeholder' => 'Hours',
				'size'=>4
			);
			$data['description'] = array('name' => 'description',
				'id' => 'description1',
				'type' => 'text',
				'value' => $this->form_validation->set_value('description'),
				'class' => 'input-block-level',
				'placeholder' => 'Job description',
				'size'=>25
			);	
                                    $data['userid'] = array('name' => 'userid',
				'id' => 'userid',
				'type' => 'text',
				'value' => $this->form_validation->set_value('userid'),
				'class' => 'input-block-level',				
				 'style'       => 'width:100px'
				
			);			
			 $this->render_page('pages/addjobsheet',$data);
			
	}  
		
	/*
	Function for submitting jobsheet addition form (Individual page for admin. Not the one which shows the list of all jobsheets on date basis).
    
	Author: Faris M A    	
  */
	public function ajaxSubmitJobsheetItems()
	{
	/* Get total no. of rows of data to be added for iteration */
	// $rowCount =   $this->input->post("rowcount");
	                          $this->form_validation->set_rules('client', 'Company field', 'required');
			 $this->form_validation->set_rules('jobno', 'Job no. field', 'required');
	                         $this->form_validation->set_rules('date', 'Date field', 'required');
			 $this->form_validation->set_rules('timespent', 'Hours spent field', 'required|numeric');
			 $this->form_validation->set_rules('description', 'Job description field', 'required');
			 
	
	 /* Validation and insertion of data */
	  if ($this->form_validation->run() == true) {
			       $jobno                   =    $this->input->post('jobno');
		                     $client                =    $this->input->post('client');
			         $date                      =    $this->input->post('date');
		                     $timespent       =    $this->input->post('timespent');
			         $description =    $this->input->post('description');
			        $userid                 =    $this->input->post('userid');
			        
			        	 
			       $addJobsheet =  $this->Jobsheets_model->addJobsheet( $userid,$jobno,$client,$date ,$timespent,$description);
				
			      if($addJobsheet)	 {   $message = Array("message"=>"Jobsheet has been added successfully!!!");} else {  $message = Array("message"=>"Jobsheet has not been added.");}
			       
			 }
			 else {
			 
				 $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				  $message = Array("message"=>validation_errors());
				
				  }
			echo json_encode($message);
	
	}
	
	
	
      /*
	Function for submitting jobsheet addition form on day and date basis.
    
	Author: Faris M A    	
  */
	public function ajaxSubmitJobsheetItemsbyDate()
	{
	/* Get the date day from Ajax request, which will be used for references later in the code. (forming the names of the selectors) */
	$day = $this->uri->segment(2);
	/* Get the no. of rows of data to be added with respect to particular date. */
	  $rowCount  =  $this->input->post($day."rowcount"); 
	   for($i=1; $i<=$rowCount;$i++){
	                       
			 $this->form_validation->set_rules($day.'timespent'.$i, 'Hours spent field', 'required|numeric');
			 $this->form_validation->set_rules($day.'description'.$i, 'Job description field', 'required');
			 $this->form_validation->set_rules($day.'client'.$i, 'Client field', 'required');
			 $this->form_validation->set_rules($day.'jobno'.$i, 'Job no. field', 'required');
	 }
	 /* Validation and insertion of data */
	 if ($this->form_validation->run() == true)
			 {
			 
			      for($i=1; $i<=$rowCount;$i++){
			        $jobno                   =    $this->input->post($day.'jobno'.$i);
		                     $client_id                =    $this->input->post($day.'client'.$i);
			         $date                      =    $this->input->post($day.'_date');
		                     $timespent       =    $this->input->post($day.'timespent'.$i);
			         $description =    $this->input->post($day.'description'.$i);
			        $addJobsheet =  $this->Jobsheets_model->addJobsheet( $this->username,$jobno,$client_id,$date ,$timespent,$description);
				   }
			/* If insertion is successfull, load all the jobsheets of the newly added date to send to Ajaxrequest. 
			   Because in the view, on submitting new jobsheets, js will clear all the rows of that particular date and append new json response(with all rows of data) to the table.
			       
			*/
			      if($addJobsheet)	 { 
				  if (!$this->ion_auth->is_admin())
									   { 
									   $result = $this->Jobsheets_model->getJobsheetsbyDate($this->username,$date);}
									   else{
									   $result = $this->Jobsheets_model->getJobsheetsbyDateforAdmin($date); 
									   }					
							$message = $result;				  
				  } else 
				  {
				  $message = Array("error"=>"Database error. Jobsheet has not been added.");
				  }
			       
			 }
			 else {
			 
				 $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				  $message = Array("error"=>validation_errors());
				
				  }
	  echo json_encode($message);
	}
	      /*
	function for searching jobsheets on different criteria.
    
	Author: Faris M A    	
  */
	public function searchJobsheets()
	{
                //                     if (!$this->ion_auth->is_admin())
		        //  {
                //                         $this->session->set_flashdata('notadmin', 'You must be an admin to view this page');
                //                         redirect('home');
                //                 }
                //                 else
                //                 {
                                
                                
	                        /* Load default clients */
			$clients = $this->Jobsheets_model->getAllClients(); //get all clients irrespective of its enabled status
                                    $data['clients'] =  $clients;			  
			
		           /*$jobsperclient =  $this->Jobsheets_model->getJobsperClient();	
			$data['jobsperclient'] =  $jobsperclient; */
			
			$users  = $this->Jobsheets_model->getUsers();
			$data['users'] =  $users;
			
			$data['isadmin']  =  $this->ion_auth->is_admin();
			 $data['datestart'] = array('name' => 'datestart',
				'id' => 'datestart',
				'type' => 'text',
				'value' => $this->form_validation->set_value('datestart'),
				'class' => 'input-block-level date',
				'placeholder' => 'From',
				'size'=>12
			);
			$data['dateend'] = array('name' => 'dateend',
				'id' => 'dateend',
				'type' => 'text',
				'value' => $this->form_validation->set_value('dateend'),
				'class' => 'input-block-level date',
				'placeholder' => 'To',
				'size'=>12
			);
			
			$queryconditioncount = 0; 
			$queryconditions  =  "";
			$nosearch = 0; /* Flag to check whether the controller is loading for first time. ie if there is no search parameters assigned, then do not run the db query (to avoid the errors) */
			 $client   =    $this->input->post('client');
			 $jobcode   =    $this->input->post('jobno');
			 $startdate   =    $this->input->post('datestart');
			 $enddate   =    $this->input->post('dateend');
			 $user_id   =    $this->input->post('userid');
			$queryitems  = Array();
			 if( $client != 0)
				 {
					$newarray1=  Array("client"=>$client);
					$queryitems = array_merge($queryitems,$newarray1);
					$nosearch = 1;
				 }
			  if( $jobcode != 0)
				  {
					$newarray2=  Array("jobcode"=>$jobcode);
					$queryitems = array_merge($queryitems,$newarray2);
					$nosearch = 1;
				  }
			    if( $startdate != NULL)
				{
					$newarray3=  Array("startdate"=>$startdate);
					$queryitems = array_merge($queryitems,$newarray3);
					$nosearch = 1;
				}
		                if( $enddate != NULL){
					$newarray4=  Array("enddate"=>$enddate);
					$queryitems = array_merge($queryitems,$newarray4);
					$nosearch = 1;
				}
			 if( $user_id != 0){
					$newarray5=  Array("user"=>$user_id);
					$queryitems = array_merge($queryitems,$newarray5);
					$nosearch = 1;
				}
			
			$data["queryitems"] = $queryitems;	//this array will be passed to save the query search parameters in a hidden variable and will be used using post method in the exportexcel controller 
			if($nosearch  ==  1)// Initialized on top. DB query will be run only if there is some parameter is selected
			{  
				$getResults =   $this->Jobsheets_model->getQueryResults($queryitems); 
				$data["searchresults"] =  $getResults;
			}
                                if($this->input->post("submitsearch") && $nosearch == 0)  //if no search parameters are selected and the submit search button is clicked, then it should load all the jobsheets
                                    {
                                              
                                               $getResults =   $this->Jobsheets_model->getAllJobsheets(); 
                                               $data["searchresults"] =  $getResults;
                                    }
			 
			
			 $this->render_page('pages/searchjobsheets',$data);
                            //}
	}
	
	public function exportExcelsheet()
	{

			/* Load default clients */
			$clients = $this->Jobsheets_model->getAllClients();
                                    $data['clients'] =  $clients;
			
		           /*$jobsperclient =  $this->Jobsheets_model->getJobsperClient();	
			$data['jobsperclient'] =  $jobsperclient; */
			
			$users  = $this->Jobsheets_model->getUsers();
			$data['users'] =  $users;
			
			 $data['datestart'] = array('name' => 'datestart',
				'id' => 'datestart',
				'type' => 'text',
				'value' => $this->form_validation->set_value('datestart'),
				'class' => 'input-block-level date',
				'placeholder' => 'From',
				'size'=>12
			);
			$data['dateend'] = array('name' => 'dateend',
				'id' => 'dateend',
				'type' => 'text',
				'value' => $this->form_validation->set_value('dateend'),
				'class' => 'input-block-level date',
				'placeholder' => 'To',
				'size'=>12
			);
			
			//Fetching the search parameters passed from the hiddenvariables in the view.
			$queryconditioncount = 0; 
			$queryconditions  =  "";
			$nosearch = 0; /* Flag to check whether the controller is loading for first time. ie if there is no search parameters assigned, then do not run the db query (to avoid the errors) */
			$client   =    $this->input->post('client');
			 $jobcode   =    $this->input->post('jobcode');
			 $startdate   =    $this->input->post('startdate');
			 $enddate   =    $this->input->post('enddate');
			 $user_id   =    $this->input->post('userid');
			$queryitems  = Array();
			 if( $client != 0)
				 {
					$newarray1=  Array("client"=>$client);
					$queryitems = array_merge($queryitems,$newarray1);
					$nosearch = 1;
				 }
			  if( $jobcode != NULL)
				  {
					$newarray2=  Array("jobcode"=>$jobcode);
					$queryitems = array_merge($queryitems,$newarray2);
					$nosearch = 1;
				  }
			    if( $startdate != NULL)
				{
					$newarray3=  Array("startdate"=>$startdate);
					$queryitems = array_merge($queryitems,$newarray3);
					$nosearch = 1;
				}
		                if( $enddate != NULL){
					$newarray4=  Array("enddate"=>$enddate);
					$queryitems = array_merge($queryitems,$newarray4);
					$nosearch = 1;
				}
			 if( $user_id != 0){
					$newarray5=  Array("user"=>$user_id);
					$queryitems = array_merge($queryitems,$newarray5);
					$nosearch = 1;
				}
			if($nosearch  ==  1)// Initialized on top
			{  
			    
				$getResults =   $this->Jobsheets_model->getQueryResults($queryitems); 
				
			}	
                                    else
                                    {
                                              
                                      $getResults =   $this->Jobsheets_model->getAllJobsheets(); 
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
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1,1,"Team Member");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Day");		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Date");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4,1,"Job No.");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5,1,"Client");				
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,1,"Description");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,1,"Hours spent");
			
		
				
		// set the data
		 $columnnum = 0;
		 $rownum = 2;
		
		$i = 1;
		foreach($getResults as $key=>$value){
		$day = date('l', strtotime($value['date']));
                    $date_dmy  =  date('d/M/Y',strtotime($value['date']));
		 $this->excel->getActiveSheet()->setCellValueByColumnAndRow(0,$rownum,$i);   
		 $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1,$rownum,$value['username']);	
		 $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2,$rownum,$day);
		 $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3,$rownum,$date_dmy);	
		 $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4,$rownum,$value['job_no']);
		 $this->excel->getActiveSheet()->setCellValueByColumnAndRow(5,$rownum,$value['clientname']);				 
		 $this->excel->getActiveSheet()->setCellValueByColumnAndRow(6,$rownum,$value['description']);
		 $this->excel->getActiveSheet()->setCellValueByColumnAndRow(7,$rownum,$value['hoursspent']);	
		 	
		 
                            	 
						 $columnnum++;$rownum++;$i++;
		}
		
		$filename='jobsheet.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
					
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');	
		
	}
	
	      /*
	function for deleting jobsheet from the table.
    
	Author: Faris M A    	
  */
	public function deleteJobsheetItembyID()
	{	
		$id = $this->uri->segment(2);
		$delete = $this->Jobsheets_model->deleteJobsheetItembyID($id);
		if($delete)
		{
		$message  = Array("error"=>"0");
		}
		else
		{ 
		$message  = Array("error"=>"1");
		}
		echo json_encode($message); 
	}
	
	
 /*
	function for fetching all jobs from the table.
    
	Author: Faris M A    	
  */
  public function listjobsforuser()
  {	
	$clients = $this->Clients_model->getAllClients(); //for the dropdown list for the search filter
	$data['clients'] =  $clients;
	$this->body_class[] = 'listjobs';
	$this->page_title = 'List existing jobs';
	$this->current_section = 'List of jobs';
	$jobs  =  $this->Clients_model->listJobs();
	$user = $this->ion_auth->user()->row_array();
	$isadmin = $this->ion_auth->is_admin();
	$data['user'] = $user;
	$data["jobs"] = $jobs;
	$data["isadmin"] = $isadmin;
	$this->render_page('pages/listjobs',$data);
  }
  

 /*
	function for fetching all jobs based on the search.
    
	Author: Faris M A    	
  */

  public function searchjobsforuser() 
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
			$isadmin = $this->ion_auth->is_admin();
			$data['user'] = $user;
			$data['queryitems'] =  $queryitems;
			$data["isadmin"] = $isadmin;
			//print_r($queryitems);
			$this->render_page('pages/listjobs',$data);
		}
	
}