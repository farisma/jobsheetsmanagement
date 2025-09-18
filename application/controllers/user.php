<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	class User extends App_Controller
	{
		public $b_emailid = "brandon@theandongroup.com"; 
		public $hr_emailid = "";  //lissy@theandongroup.com

		// public $b_emailid = "faris@theandongroup.com"; 
		// public $hr_emailid = "";  
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("Vacation_model");
		
		}
		
		public function index()
		{
			redirect('login');
		}
		
		public function login()
		{
			$this->body_class[] = 'login';
			
			$this->page_title = 'Please sign in';
			
			$this->current_section = 'login';
			
			// validate form input
			$this->form_validation->set_rules('identity', 'Email', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			
			
			if ($this->form_validation->run() == true)
			{ 
				//google captcha verification
			 $captchaToken = $this->input->post('token');
			 $verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
			 echo "tokenver".$verifyToken;
				if($verifyToken) {

				// check to see if the user is logging in
				// check for "remember me"
				$remember = (bool) $this->input->post('remember');
				
				if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
				{ 
					$this->session->set_flashdata('app_success', $this->ion_auth->messages());
					redirect('home');
				}
				else
				{ 
					$this->session->set_flashdata('app_error', $this->ion_auth->errors());
					redirect('home');
				}
				}
				else {
					$this->session->set_flashdata('app_error', $this->ion_auth->errors());
					redirect('home');
				}
				
			}
			else
			{  
				// the user is not logging in so display the login page
				// set the flash data error message if there is one
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				
				$data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
				'class' => 'input-block-level',
				'placeholder' => 'Your email'
				);
				$data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'class' => 'input-block-level',
				'placeholder' => 'Your password'
				);
				
				$this->render_page('user/login', $data);
			}
		}
		
		public function logout()
		{
			// log the user out
			$logout = $this->ion_auth->logout();
			
			// redirect them back to the login page
			redirect('login');
		}
		
		public function forgot_password()
		{
			if ($this->form_validation->run('user_forgot_password'))
			{
				$forgotten = $this->ion_auth->forgotten_password($this->input->post('email', TRUE));
				
				if ($forgotten)
				{ 
					// if there were no errors
					$this->session->set_flashdata('app_success', $this->ion_auth->messages());
					redirect('login');
				}
				else
				{
					$this->session->set_flashdata('app_error', $this->ion_auth->errors());
					redirect('login');
				}
			}
			
			$this->body_class[] = 'forgot_password';
			
			$this->page_title = 'Forgot password';
			
			$this->current_section = 'forgot_password';
			
			$this->render_page('user/forgot_password');
		}
		
		public function account()
		{
			$this->body_class[] = 'my_account';
			
			$this->page_title = 'My Account';
			
			$this->current_section = 'my_account';
			
			$user = $this->ion_auth->user()->row_array();
			
			$this->render_page('user/account', array('user' => $user));
		}
		
		public function register()
		{
			$this->form_validation->set_rules('username', 'Text', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('firstname', 'Text', 'required');
			$this->form_validation->set_rules('lastname', 'Text', 'required');
			
			$username        =     $this->input->post('username');
			$password        =     $this->input->post('password');
			$email                 =     $this->input->post('email');
			$firstname     =     $this->input->post('firstname');
			$lastname        =     $this->input->post('lastname');
			$usergroup     =     $this->input->post('group');
			$additional_data  = Array(
			'first_name' => $firstname,
			'last_name' => $lastname
			);
			
			$group = Array($usergroup);
			
			
			if ($this->form_validation->run() == true)
			{ 
					//google captcha verification
			 $captchaToken = $this->input->post('token');
			 $verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
				if($verifyToken) {
				if ($this->ion_auth->register($username ,$password,$email,$additional_data,$group))
				{ 
					$this->session->set_flashdata('app_success', $this->ion_auth->messages());
					redirect('home');
				}
				else
				{
					$this->session->set_flashdata('app_error', $this->ion_auth->errors());
					redirect('user/register');
				}
			 }
			 else {
				 $this->session->set_flashdata('app_error', $this->ion_auth->errors());
					redirect('user/register');
			 }
			}
			
			else 
			{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				
				$data['username'] = array('name' => 'username',
				'id' => 'username',
				'type' => 'text',
				'value' => $this->form_validation->set_value('username'),
				'class' => 'input-block-level',
				'placeholder' => 'Username'
				);
				$data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'class' => 'input-block-level',
				'placeholder' => 'Your password'
				);
				$data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'email',
				'value' => $this->form_validation->set_value('email'),
				'class' => 'input-block-level',
				'placeholder' => 'Your Email'
				);
				$data['firstname'] = array('name' => 'firstname',
				'id' => 'firstname',
				'type' => 'text',
				'value' => $this->form_validation->set_value('firstname'),
				'class' => 'input-block-level',
				'placeholder' => 'First Name'
				);
				$data['lastname'] = array('name' => 'lastname',
				'id' => 'lastname',
				'type' => 'text',
				'value' => $this->form_validation->set_value('lastname'),
				'class' => 'input-block-level',
				'placeholder' => 'Last Name'
				);
				$data['group'] = array('name' => 'group',
				'id' => 'group',
				'type' => 'text',
				'value' => $this->form_validation->set_value('group'),
				'class' => 'input-block-level',
				'placeholder' => 'User type'
				);
			}
			
			$this->render_page('user/register',$data);
		}
		
		public function listUsers()
		{
			$users = $this->ion_auth->users()->result_array();
			
			$data['users']  = $users;
			$this->render_page('pages/listusers',$data);
			
		}
        public function deleteUser()
		{
			$id = $this->uri->segment(2);
			$delete = $this->ion_auth->delete_user($id);
			$data['message'] = $this->ion_auth->messages();
			
			$users = $this->ion_auth->users()->result_array();
			
			$data['users']  = $users;
			$this->render_page('pages/listusers',$data);
			
		}
		
		public function updateUser()
		{
			$id = $this->uri->segment(2);
			$user = $this->ion_auth->user($id)->result_array();  //to fetch user to be updated
			
			$username        =     $this->input->post('username');
			$password        =     $this->input->post('password');
			$email                 =     $this->input->post('email');
			$firstname     =     $this->input->post('firstname');
			$lastname        =     $this->input->post('lastname');
			$dataarray = array(
			'first_name' => $firstname,
			'last_name' => $lastname,
			'password' =>  $password ,
			'email'=> $email,
			'username'=>$username
			);
			
			$this->form_validation->set_rules('username', 'Text', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('firstname', 'Text', 'required');
			$this->form_validation->set_rules('lastname', 'Text', 'required');
			if ($this->form_validation->run() == true)
			{ 
						//google captcha verification
			 $captchaToken = $this->input->post('token');
			 $verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
				if($verifyToken) {
				if ($this->ion_auth->update($id ,$dataarray))
				{ 
					$this->session->set_flashdata('app_success', $this->ion_auth->messages());
					redirect('user/listUsers');
				}
				else
				{
					$this->session->set_flashdata('app_error', $this->ion_auth->errors());
					redirect('user/updateUser');
				}
				}
				else {
					$this->session->set_flashdata('app_error', $this->ion_auth->errors());
					redirect('user/updateUser');
				}
			}
			else {                
				$data['username'] = array('name' => 'username',
				'id' => 'username',
				'type' => 'text',
				'value' => $user[0]['username'],
				'class' => 'input-block-level',
				'placeholder' => 'Username'
				);
				$data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'class' => 'input-block-level',
				'placeholder' => 'Your new password'
				);
				$data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'email',
				'value' =>  $user[0]['email'],
				'class' => 'input-block-level',
				'placeholder' => 'Your Email'
				);
				$data['firstname'] = array('name' => 'firstname',
				'id' => 'firstname',
				'type' => 'text',
				'value' => $user[0]['first_name'],
				'class' => 'input-block-level',
				'placeholder' => 'First Name'
				);
				$data['lastname'] = array('name' => 'lastname',
				'id' => 'lastname',
				'type' => 'text',
				'value' => $user[0]['last_name'],
				'class' => 'input-block-level',
				'placeholder' => 'Last Name'
				);
				
				
				$data['id']  =    $user[0]['id'];
				$this->render_page('user/update_user',$data);
			}
		}
		
		
		public function leaverequest(){
			$user = $this->ion_auth->user()->row_array();
			$data['user'] = $user;
			$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
			$this->render_page('pages/addleaverequests',$data);
		}
		public function leaverequestfromadmin(){
			$users = $this->Vacation_model->getUsers();
			$data['users'] = $users;
			$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
			$this->render_page('pages/addleaverequests_admin',$data);
		}
		
		
		public function addemployeeleaves(){
			//$googlecaptchaToken
			$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
			
			//echo $this->input->post('token');
			$this->body_class[] = 'addleave';
			$this->page_title = 'Add employee leaves';
			$this->current_section = 'Add Leaves';
			
			$employee_id = $this->input->post('userid');
			$startdate = $this->input->post('startdate');
			$enddate = $this->input->post('enddate');
			$nofworkingdays = $this->Vacation_model->findnoofworkingdays($startdate,$enddate);
		//	echo $nofworkingdays;
			$vacationtype = $this->input->post('vacation_type'); 
			$noofdays = $this->input->post('noofdays'); 
			$noofdays = $nofworkingdays;

			//google captcha verification
			$captchaToken = $this->input->post('token');
			$verifyToken =  $this->Vacation_model->verifyToken($captchaToken);
			//echo "fdf".$verifyToken;
			if($verifyToken) {
					$insert = $this->Vacation_model->request_leavebyemployee($employee_id,$startdate,$enddate,$vacationtype,$noofdays);
					if($insert)
						{
						$getvacationtype = $this->Vacation_model->getVacationTypebyId($vacationtype);
						$vactype = $getvacationtype['vacationtype'];
						$userdetails = $this->Vacation_model->getUserbyId($employee_id);
						
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
						$to = $this->b_emailid; 
						$cc =  $this->hr_emailid; 
						$bcc = "";
						$fullname = $fn." ".$ln;
						$subject = 'Request for leave';
						$send = $this->Vacation_model->sendemailnotification($from,$to,$emailText,$fullname,$cc,$bcc,$subject);
						if($send){
							$data['mailsent'] ="true";
						}
						else {
							$data['mailsent'] ="false";
						}
						
						$data['message'] = "true";
						}
					else 
						{
						$data['message'] = "false"; 
						}	
			}
			else {
			$data['message'] = "false";
				}
				
			
		$user = $this->ion_auth->user()->row_array();
		$data['user'] = $user;
		$data['vacationtypes'] = $this->Vacation_model->getVacationtypes();
		$this->render_page('pages/addleaverequests',$data);
		
		
		}
		
		 /* Function: dateToCal
			  convert date timestamp to ics format date
		  */
		public function dateToCal($timestamp) {
			return date('Ymd\THis', $timestamp);
		}

		
		 /* Function: savetocalendar
			  function to save leave dates to calendar when somebody goes on leave
			  */
		  
		  public function savetoCalendar(){
  
	 $startTime = $this->uri->segment(2);
			   $endTime   = $this->uri->segment(3);
			   $subject   = urldecode($this->uri->segment(4));
			   $desc      = urldecode($this->uri->segment(5));			  


  //$d1 = "2018-08-19 09:00:00";
 // $d2 = "2018-08-25 24:00:00";
  $d1 = $startTime." 09:00:00";
  $d2 = $endTime." 23:00:00";
  $d1_ts = strtotime($d1);
  $d2_ts = strtotime($d2);
  
  $date1_ics = $this->dateToCal($d1_ts);
  $date2_ics = $this->dateToCal($d2_ts);
  $ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "example.com
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART:".$date1_ics."
DTEND:".$date2_ics."
SUMMARY:".$subject."
DESCRIPTION:".$desc."
END:VEVENT
END:VCALENDAR";

  //set correct content-type-header
  header('Content-type: text/calendar; charset=utf-8');
  header('Content-Disposition: inline; filename=calendar.ics');
  echo $ical;
  exit;
			  //echo 
		  }

		  
	public function searchLeavesforUser(){
		   // $users = $this->getUsers();
		    //$data['users'] =  $users;
			
			$currentuser = $this->ion_auth->user()->row_array();
			//print_r($currentuser);
			$user = $currentuser['id'];
			$data['username']=$currentuser['username'];
			if($this->input->post('year')) {
			//$user = $this->input->post('userid');
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
			
			$this->render_page('pages/searchleavesforCurrentUser',$data);
	}

		}		