<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';

$route['login'] = 'user/login';
$route['logout'] = 'user/logout';
$route['forgot-password'] = 'user/forgot_password';
$route['my-account'] = 'user/account';
$route['savecalendar/(:any)/(:any)/(:any)/(:any)'] = "user/savetoCalendar";



$route['adminpage'] = 'clients/adminPage';
$route['list-clients'] = 'clients/listClients';
$route['edit-client/(:any)'] = 'clients/editClient';

$route['add-client'] = 'clients/index';
$route['submit-client'] = 'clients/add';
$route['delete-client/(:any)'] = 'clients/disableClient';

$route['submit-job'] = 'clients/addjob';
$route['submit-retainer-job']  =  'clients/addretainerjob';
$route['submit-ekretainer-job'] = 'clients/addekretainerjob';
$route['submit-consolidatedbillingC-job'] = 'clients/addconsolidatedBCJob';
$route['list-jobs'] = 'clients/listjobs';
$route['list-jobs/(:any)'] = 'clients/listjobs';

$route['clients/exportJobNos'] = 'clients/exportJobNos';
$route['checkIfRetainerJob/(:any)']  =   "clients/checkIfRetainerJob/";
$route['disable-job/(:any)'] = "clients/disablejobs";
$route['delete-job/(:any)'] = "clients/deletejobs";
$route['register'] = "user/register";
$route['add-jobsheet'] ="jobsheets/addJobsheetItems";
$route['submit-jobsheet'] ="jobsheets/addJobsheetItems";
$route['jobsheets/(:any)'] ="jobsheets/index";
$route['previousWeekJobsheets'] ="jobsheets/getPreviousweekJobsheets";

$route['edit-job/(:any)'] = "clients/editjob";
$route['update-job/'] = "clients/editjob";
$route['search-jobs'] = "clients/searchjobs";
$route['search-jobs-user'] = "jobsheets/searchjobsforuser";
$route['ajaxToGetJobcodes/(:any)']   = "jobsheets/populateJobCodes/";
$route['ajaxToGetnonRetainerJobcodes/(:any)']   = "clients/getJobSeqNo/";
$route['ajaxToGetRetainerJobcodes']   = "clients/getRetainerJobSeqNo/";
$route['ajaxToGetEKRetainerJobcodes']   = "clients/getEKRetainerJobSeqNo/";
$route['ajaxToGetConsolidatedBillingJobcodes']   = "clients/getConsolidatedBillingJobcodes/";

$route['ajaxToGetAllJobcodes/(:any)']  =  "jobsheets/populateAllJobCodes/";
$route['submitJobSheets']   = "jobsheets/ajaxSubmitJobsheetItems";
$route['submitJobSheetsbyDate/(:any)'] =  "jobsheets/ajaxSubmitJobsheetItemsbyDate";

$route['delete-jobsheet/(:any)'] =  "jobsheets/deleteJobsheetItembyID";

$route['jobsheets/search']  =   "jobsheets/searchJobsheets";
$route['alljobs']  =   "jobsheets/listjobsforuser";
$route['jobsheets/exportExcelsheet']  =   "jobsheets/exportExcelsheet";
$route['add-user']  = "user/register";
$route['list-users']  =   "user/listUsers";
$route['edit-user/(:any)']  =   "user/updateUser";
$route['delete-user/(:any)']  =   "user/deleteUser";

$route['vacation/track']  =   "vacation/trackvacation";
$route['vacation/getAll']  =   "vacation/getAll";
$route['vacation/addemployeeleaves'] =   "vacation/addemployeeleaves";

$route['user/leaverequestfromadmin'] = "user/leaverequestfromadmin";
$route['user/leaverequest'] = "user/leaverequest";
$route['user/addemployeeleaves'] = "user/addemployeeleaves";
$route['vacation/leavemanagement/(:any)'] = "vacation/leavemanagement";
$route['vacation/approveleave/(:any)/(:any)'] = "vacation/approveleave";
$route['vacation/rejectleave/(:any)/(:any)'] = "vacation/rejectleave";
$route['vacation/addandApproveEmployeeleaves'] = "vacation/addandApproveEmployeeleaves";
$route['vacation/listrejectedleaves'] = "vacation/getrejectedleaves";

$route['vacation/holidays'] = "vacation/listholidays";
$route['vacation/addholidays'] = "vacation/addholidays";
$route['delete-holiday/(:any)/(:any)'] = "vacation/deleteHolidays";


$route['vacation/searchleaves'] = "vacation/leaveSearch";





$route['add-project-type'] = "clients/add_project_type";
$route['submit-project-type'] = "clients/submit_project_type";
$route['list_project_types'] = "clients/list_project_types";
$route['edit-project-type/(:any)'] = "clients/edit_project_type";
$route['delete-project-type/(:any)'] = "clients/deleteProjectType";


$route['view_reports'] = "clients/viewreportspage";
$route['findreport'] = "clients/viewreports";
$route['exportmonthlyreport'] = "clients/exportmonthlyreport";
$route['add-division'] = "clients/add_division";
$route['list_divisions'] = "clients/list_divisions";
$route['edit-division/(:any)'] = "clients/edit_division";
$route['delete-division/(:any)'] = "clients/delete_division";

// $route['findstatusreport'] = "clients/viewstatusreports";
$route['findstatusreport'] = "clients/getAjaxStatusReports";
$route['exportmonthlyreportalljobs'] = "clients/exportmonthlyreportalljobs";
$route['ajaxToGetDivisionsByClientID/(:any)'] = "clients/find_divisions_by_client";

$route['get-job-for-edit/(:any)'] = "clients/getJobForEdit";



/* End of file routes.php */
/* Location: ./application/config/routes.php */