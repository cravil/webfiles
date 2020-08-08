<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('login_model');
        $this->load->model('transactions_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('payments_model');
        $this->load->model('twilio_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        //$this->global['pageTitle'] = 'Dashboard';
        $earnings = $this->transactions_model->getEarningsTotal($this->vendorId);
        $withdrawals = $this->transactions_model->getWithdrawalsTotal($this->vendorId);
        $availableFunds =  abs($earnings-$withdrawals);

        $data["payouts"] = $this->transactions_model->getAllPayoutsTotal(date('Y-m-d'),date('Y-m-d'));
        
        $data["allClients"] = $this->user_model->getAllUsers('', 3, '');
        $data["clientsthisweek"] = $this->user_model->getAllUsers(date('Y-m-d', strtotime("-7 days")), 3,  date('Y-m-d'));

        $data["pendingWithdrawals"] = $this->transactions_model->getAllWithdrawalsTotal(date('Y-m-d', strtotime('1900-01-01')),date('Y-m-d'), 0);

        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data['accountInfo'] = $availableFunds;
        if($this->role == ROLE_ADMIN || $this->role == ROLE_MANAGER){
            $searchText = $this->input->post('searchText' ,TRUE);
            $count = $this->transactions_model->allEarningsListingCount($searchText, date('Y-m-d H:i:s'));
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits(null);
            $data['inActiveDeposits'] = $this->transactions_model->getInActiveDeposits(null);
            $returns = $this->paginationCompress ( "earnings/", $count, 5 );
            $data['earningTransactions'] = $this->transactions_model->allEarnings($searchText, $returns["page"], $returns["segment"], date('Y-m-d H:i:s'));
        } else {
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits($this->vendorId);
        }
        
        $data['earningsInfo'] = $earnings;
        $data['withdrawals'] = $withdrawals;
        $data['referrals'] = $this->transactions_model->referrals($this->vendorId)->num_rows();
        $data["companyInfo"] = $this->settings_model->getSettingsInfo();

        //Earnings Data
        $searchText = $this->input->post('searchText' ,TRUE);
        $count = $this->transactions_model->earningsListingCount($searchText, $this->vendorId, date('Y-m-d H:i:s'));
		$returns = $this->paginationCompress ( "earnings/", $count, 5 );
        $data['transactions'] = $this->transactions_model->earnings($searchText, $returns["page"], $returns["segment"], $this->vendorId, date('Y-m-d H:i:s'));

        $this->global['pageTitle'] = 'Dashboard';
        $this->global['breadcrumbs'] = 'Home'.' <span class="breadcrumb-arrow-right"></span> '.'Dashboard';
        $this->global['displayBreadcrumbs'] = true;
        $this->loadViews("dashboard", $this->global, $data , NULL);
    }
    
    /**
     * This function is used to load the team list
     */
    function team()
    {
        $module_id = 'teams';
        $module_action = 'view';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = $this->input->post('searchText' ,TRUE);
            $data['searchText'] = $searchText;
            $role = '2';
            
            $this->load->library('pagination');
            
            $count = $this->user_model->userListingCount($searchText, $role);
            $returns = $this->paginationCompress ( "team/", $count, 10 );
            
            $data['userRecords'] = $this->user_model->users($searchText, $returns["page"], $returns["segment"], $role);
            $data["allUsers"] = $this->user_model->getAllUsers('', $role, '');
            $data['lastLogin'] = $this->user_model->lastLogin();
            
            $this->global['pageTitle'] = 'Team';
            $this->global['displayBreadcrumbs'] = false;
            $this->loadViews("users/table", $this->global, $data, NULL);
        }
    }

    /**
     * @access: Admin Only
     * @desc: This function is used to load the add new form
     */
    function addNewManager()
    {
        $module_id = 'teams';
        $module_action = 'add';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            $this->global['pageTitle'] = 'Add New Manager';
            $this->global['displayBreadcrumbs'] = true;
            $this->global['breadcrumbs'] = 'Team'.' <span class="breadcrumb-arrow-right"></span> '.'Add';

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('lname','Last Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('phone','Phone','required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $firstName = ucwords(strtolower($this->input->post('fname', TRUE)));
                $lastName = ucwords(strtolower($this->input->post('lname', TRUE)));
                $email = strtolower($this->input->post('email', TRUE));
                $roleId = '2';
                $mobile = $this->input->post('phone', TRUE);

                $this->load->model('login_model');
                $result1 = $this->login_model->checkEmailExist($email);
                if($result1>0)
                {
                    $this->session->set_flashdata('error', 'Email is in use');
                } 
                else
                {
                    $this->load->helper('string');
                    $activationurl = random_string('alnum',15);
                    $reset_link = base_url() . "resetPassword/" . $activationurl;

                    $userInfo = array(
                        'email'=>$email, 
                        'roleId'=>$roleId, 
                        'firstName'=> $firstName, 
                        'lastName' => $lastName,
                        'mobile'=>$mobile,
                        'createdBy'=>$this->vendorId, 
                        'createdDtm'=>date('Y-m-d H:i:s')
                    );
    
                    $permissions = $this->input->post("permissions") != false ? $this->input->post("permissions") : array();
                    
                    $this->load->model('user_model');
                    $result = $this->user_model->addNewManager($userInfo, $permissions);
                    
                    if($result)
                    {
                        $inputdata['email'] = $email;
                        $inputdata['activation_id'] = $activationurl;
                        $inputdata['createdDtm'] = date('Y-m-d H:i:s');
                        $inputdata['agent'] = getBrowserAgent();
                        $inputdata['client_ip'] = $this->input->ip_address();
                        
                        $this->login_model->resetPasswordUser($inputdata); 
                        
                        //Send Mail
                        $conditionUserMail = array('tbl_email_templates.type'=>'Add Client');
                        $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                        $companyInfo = $this->settings_model->getsettingsInfo();
                    
                        if($resultEmail->num_rows() > 0)
                        {
                            $rowUserMailContent = $resultEmail->row();
                            $splVars = array(
                                "!clientName" => $firstName,
                                "!passwordResetLink" => $reset_link,
                                "!companyName" => $companyInfo['name'],
                                "!address" => $companyInfo['address'],
                                "!siteurl" => base_url()
                            );

                            $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                            $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                            $toEmail = $email;
                            $fromEmail = $companyInfo['SMTPUser'];

                            $name = 'Support';

                            $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                            $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent,NULL);

                            if($send == true) {
                                $this->session->set_flashdata('success', "Account has been setup and an email has been sent to the user succesfully.");
                            } else {
                                $this->session->set_flashdata('error', "Account has been setup successfully. However, email sending has failed.");
                            }

                            //Send SMS
                            $phone = $mobile;
                            if($phone){
                                $body = strtr($rowUserMailContent->sms_body, $splVars);

                                $this->twilio_model->send_sms($phone, $body);
                            }
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 'Employee creation failed');
                    }
                }
                
                redirect('team/newManager');
            }
            
            $this->loadViews("users/add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used load manager edit information
     * @param number $userId : Optional : This is user id
     */
    function editManager($userId = NULL)
    {
        $module_id = 'teams';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('team');
            }

            $this->load->library('form_validation');
            
            $userId = $userId;
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('lname','Last Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $firstName = ucwords(strtolower($this->input->post('fname', TRUE)));
                $lastName = ucwords(strtolower($this->input->post('lname', TRUE)));
                $email = strtolower($this->input->post('email', TRUE));
                $deactivate = $this->input->post('deactivate', TRUE) == NULL ? '0' : '1';
                $roleId = '2';
                
                $userInfo = array();
                
                $userInfo = array(
                    'email'=>$email, 
                    'roleId'=>$roleId, 
                    'firstName'=>$firstName, 
                    'lastName'=>$lastName,
                    'isActive'=>$deactivate,
                    'updatedBy'=>$this->vendorId, 
                    'updatedDtm'=>date('Y-m-d H:i:s')
                );

                $permissions = $this->input->post("permissions") != false ? $this->input->post("permissions") : array();
                
                $result = $this->user_model->editManager($userInfo, $permissions, $userId);
                
                if($result)
                {
                    $this->session->set_flashdata('success', 'Manager updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Manager update failed');
                }
                
                redirect('team/editManager/'.$userId);
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->global['pageTitle'] = 'Edit Manager';
            $this->global['displayBreadcrumbs'] = true;
            $this->global['breadcrumbs'] = 'Team'.' <span class="breadcrumb-arrow-right"></span> '.'Edit';
            
            $this->loadViews("users/edit", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the client list
     */
    function clients()
    {
        $module_id = 'clients';
        $module_action = 'view';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = html_purify($this->input->post('searchText'));
            $this->global['searchText'] = $searchText;
            $role = '3';
            
            $this->load->library('pagination');
            
            $count = $this->user_model->userListingCount($searchText, $role);

			$returns = $this->paginationCompress ( "clients/", $count, 10 );
            
            $data['userRecords'] = $this->user_model->users($searchText, $returns["page"], $returns["segment"], $role);

            $data["allUsers"] = $this->user_model->getAllUsers('', $role, '');

            $data["clientsThisWeek"] = $this->user_model->getAllUsers(date('Y-m-d', strtotime("-7 days")), $role,  date('Y-m-d'));
            
            $this->global['pageTitle'] = 'Clients';
            $this->global['displayBreadcrumbs'] = false;
            
            $this->loadViews("users/table", $this->global, $data, NULL);
        }
    }

    /**
     * @access: Admin Only
     * @desc: This function is used to load the add new form
     */
    function addNewClient()
    {
        $module_id = 'clients';
        $module_action = 'add';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            $this->global['pageTitle'] = 'Add New Client';
            $this->global['displayBreadcrumbs'] = true;
            $this->global['breadcrumbs'] = 'Clients'.' <span class="breadcrumb-arrow-right"></span> '.'New';

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('lname','Last Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            //$this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            //$this->form_validation->set_rules('principal','Principal Amount','required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $this->load->helper('string');
                $firstName = ucwords(strtolower($this->input->post('fname', TRUE)));
                $lastName = ucwords(strtolower($this->input->post('lname', TRUE)));
                $email = strtolower($this->input->post('email', TRUE));
                $isActive = '0';
                $roleId = '3';
                $code = random_string('alnum',8);

                $this->load->model('login_model');
                $result1 = $this->login_model->checkEmailExist($email);
                if($result1>0)
                {
                    $this->session->set_flashdata('error', 'Email is in use');
                } 
                else
                {

                    $userInfo = array(
                        'email'=>$email, 
                        'roleId'=>$roleId, 
                        'firstName'=> $firstName, 
                        'lastName' => $lastName, 
                        'isActive'=>$isActive, 
                        'refCode' => $code,
                        'createdBy'=>$this->vendorId, 
                        'createdDtm'=>date('Y-m-d H:i:s')
                    );
                    
                    $this->load->model('user_model');
                    $result = $this->user_model->addNewUser($userInfo);
                    
                    if($result>0)
                    {
                        $inputdata['email'] = $email;
                        $inputdata['activation_id'] = random_string('alnum',15);
                        $inputdata['createdDtm'] = date('Y-m-d H:i:s');
                        $inputdata['agent'] = getBrowserAgent();
                        $inputdata['client_ip'] = $this->input->ip_address();
                        
                        $this->login_model->resetPasswordUser($inputdata); 
                        $reset_link = base_url() . "resetPassword/" . $inputdata['activation_id'];

                        //Send Mail
                        $conditionUserMail = array('tbl_email_templates.type'=>'Add Client');
                        $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                        $companyInfo = $this->settings_model->getsettingsInfo();
                    
                        if($resultEmail->num_rows() > 0)
                        {
                            $rowUserMailContent = $resultEmail->row();
                            $splVars = array(
                                "!clientName" => $firstName,
                                "!passwordResetLink" => $reset_link,
                                "!companyName" => $companyInfo['name'],
                                "!address" => $companyInfo['address'],
                                "!siteurl" => base_url()
                            );

                            $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                            $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                            $toEmail = $email;
                            $fromEmail = $companyInfo['SMTPUser'];

                            $name = 'Support';

                            $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                            $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent,NULL);

                            if($send == true) {
                                $this->session->set_flashdata('success', "Account has been setup and an email has been sent to the user succesfully.");
                            } else {
                                $this->session->set_flashdata('error', "Account has been setup successfully. However, email sending has failed.");
                            }
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 'Client creation failed');
                    }

                }

                redirect('clients/newClient');
            }
            
            $this->loadViews("users/add", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used load manager edit information
     * @param number $userId : Optional : This is user id
     */
    function editClient($userId = NULL)
    {
        $module_id = 'clients';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('clients');
            }

            $this->load->library('form_validation');
            
            $userId = $userId;
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('lname','Last Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $firstName = ucwords(strtolower($this->input->post('fname', TRUE)));
                $lastName = ucwords(strtolower($this->input->post('lname', TRUE)));
                $email = strtolower($this->input->post('email', TRUE));
                $deactivate = $this->input->post('deactivate', TRUE) == NULL ? '0' : '1';
                $roleId = '3';
                
                $userInfo = array(
                    'email'=>$email, 
                    'roleId'=>$roleId,
                    'firstName'=>ucwords($firstName), 
                    'lastName'=>ucwords($lastName), 
                    'isActive'=>$deactivate,
                    'updatedBy'=>$this->vendorId, 
                    'updatedDtm'=>date('Y-m-d H:i:s')
                );
              
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Client updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Client update failed');
                }
                
                redirect('clients/editClient/'.$userId);
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->global['pageTitle'] = 'Edit Client';
            $this->global['displayBreadcrumbs'] = true;
            $this->global['breadcrumbs'] = 'Client'.' <span class="breadcrumb-arrow-right"></span> '.'Edit';
            
            $this->loadViews("users/edit", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used load manager edit information
     * @param number $userId : Optional : This is user id
     */
    function viewClient($userId = NULL)
    {
        $module_id = 'clients';
        $module_action = 'view';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('clients');
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            $data['accountInfo'] = $this->transactions_model->getActiveDeposits($userId);

            //Earnings and account Info
            $earnings = $this->transactions_model->getEarningsTotal($userId);
            $withdrawals = $this->transactions_model->getWithdrawalsTotal($userId);
            $availableFunds =  abs($earnings-$withdrawals);

            $data['accountInfo'] = $availableFunds;
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits($userId);
            $data['earningsInfo'] = $earnings;
            $data['withdrawals'] = $withdrawals;
            //Pagination for transactions table
            $searchText = $this->input->post('searchText' ,TRUE);
            $data['searchText'] = $searchText;
            $count = $this->user_model->userListingCount($searchText);

			$returns = $this->paginationCompress ( "users/", $count, 10 );
            $data['deposits'] = $this->transactions_model->deposits($searchText, $returns["page"], $returns["segment"], $userId);
            $data['withdrawals'] = $this->transactions_model->withdrawalsById($searchText, $returns["page"], $returns["segment"], $userId);
            $data['earnings'] = $this->transactions_model->earnings($searchText, $returns["page"], $returns["segment"], $userId, date('Y-m-d H:i:s'));
            
            $this->global['pageTitle'] = 'View User';
            $this->global['displayBreadcrumbs'] = false;
            
            $this->loadViews("users/view", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId", TRUE);
        $email = $this->input->post("email", TRUE);

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = '404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    /**
     * This function used to show login history
     * @param number $userId : This is user id
     */
    function loginHistoy($userId = NULL)
    {
        $module_id = 'loginHistory';
        $module_action = 'view';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $userId = ($userId == NULL ? 0 : $userId);

            $searchText = $this->input->post('searchText', TRUE);
            $fromDate = $this->input->post('fromDate', TRUE);
            $toDate = $this->input->post('toDate', TRUE);

            $data["userInfo"] = $this->user_model->getUserInfoById($userId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);
            $returns = $this->paginationCompress ( "login-history/".$userId."/", $count, 10, 3);
            
            $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'User Login History';
            $this->global['displayBreadcrumbs'] = false;
            
            $this->loadViews("users/loginHistory", $this->global, $data, NULL);
        }        
    }

    /**
     * This function is used to show users profile
     */
    function profile($active = "details")
    {
        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data["active"] = $active;
        $withdrawableDeposits = $this->transactions_model->getWithdrawableDeposits($this->vendorId);
        $interestEarnings = $this->transactions_model->getEarningsTotalByType($this->vendorId, 'interest');
        $referralEarnings = $this->transactions_model->getEarningsTotalByType($this->vendorId, 'referral');
        $withdrawals = $this->transactions_model->getWithdrawalsTotal($this->vendorId);
        $data['accountInfo'] = $withdrawableDeposits + $interestEarnings + $referralEarnings - $withdrawals;
        $data['withdrawalMethods'] = $this->payments_model->getwithdrawalMethods();
        
        $this->global['pageTitle'] = $active == "details" ? 'My profile' : 'Change Password';
        $this->global['breadcrumbs'] = 'Settings'.' <span class="breadcrumb-arrow-right"></span> '.'My Profile';
        $this->global['displayBreadcrumbs'] = true;
        $this->loadViews("users/profile", $this->global, $data, NULL);
        //$this->load->view('profile', $data);
    }

    /**
     * This function is used to update the user details
     * @param text $active : This is flag to set the active tab
     */
    function profileUpdate($active = "details")
    {
        $this->load->library('form_validation');
            
        $this->form_validation->set_rules('fname','First Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('lname','Last Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('password','Password','required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]|callback_emailExists');        
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $fname = ucwords(strtolower($this->input->post('fname', TRUE)));
            $lname = ucwords(strtolower($this->input->post('lname', TRUE)));
            $password = $this->input->post('password', TRUE);
            $mobile = $this->input->post('fullMobile', TRUE);
            $email = strtolower($this->input->post('email', TRUE));
            $userInfo = array('firstName'=>$fname, 'lastName'=>$lname, 'email'=>$email, 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));

            $currentEmail = $this->user_model->getUserInfoById($this->vendorId)->email;

            $result1 = $this->login_model->loginMe($currentEmail, $password);
            if(!empty($result1))
            {
                $result = $this->user_model->editUser($userInfo, $this->vendorId);
            
                if($result == true)
                {
                    $sessionArray = array(
                        'firstName'=>$fname,
                        'lastName'=>$lname,
                    );
                    $this->session->set_userdata($sessionArray);
                    $this->session->set_flashdata('success', 'Profile updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Profile update failed');
                }

            } else {
                $this->session->set_flashdata('error', 'Incorrect Password');
            }

            redirect('profile/'.$active);
        }
    }

    function logo_update()
    {
        if(isset($_FILES["profile-pic"]["name"])){
            if ($this->security->xss_clean($this->input->post('profile-pic'), TRUE) === TRUE)
            {
            $config["upload_path"] = './uploads';
            $config['allowed_types'] = 'jpg|png';
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('profile-pic')){
               $data = ($this->upload->data());
               $ppic = $data["file_name"];
            }else{
               $errors = $this->upload->display_errors();
               $nameLogoWhite = $this->settings_model->getSettingsInfo()['whiteLogo'];
            }; 
            }
         }

         $userInfo = array(
             'ppic'=>$ppic, 
             'updatedBy'=>$this->vendorId, 
             'updatedDtm'=>date('Y-m-d H:i:s')
        );

        $result = $this->user_model->editUser($userInfo, $this->vendorId);

        if($result == true)
        {
            $sessionArray = array(
                'ppic'=>$ppic,
            );
            
            $this->session->set_userdata($sessionArray);

            $array = array(
                'success' => true,
                'msg' => html_escape('Succesfully updated your profile picture'),
            );

            echo json_encode($array);
        }
        else
        {
            $array = array(
                'success' => false,
                'msg' => html_escape('There is an erro in updating your profile picture'),
            );

            echo json_encode($array);
        }
    }

    /**
     * This function is used to update the user details
     * @param text $active : This is flag to set the active tab
     */
    function paymentAccountUpdate($active = "details")
    {
        $this->load->library('form_validation');
        if($this->input->post('paymentType', TRUE) == 'Paypal'){
            $this->form_validation->set_rules('paymentType','Paypal','trim|required');
            $this->form_validation->set_rules('paymentAccount','Payment Account','trim|valid_email|required|max_length[128]');
        }    
        else
        {
            $this->form_validation->set_rules('paymentType','Payment Type','trim|required');
            $this->form_validation->set_rules('paymentAccount','Payment Account','trim|required|max_length[128]');
        }
        $this->form_validation->set_rules('password','Password','required');     
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $paymentType = $this->input->post('paymentType', TRUE);
            $paymentAccount = $this->input->post('paymentAccount', TRUE);
            $password = $this->input->post('password', TRUE);
            $userInfo = array(
                'pmtType'=>$paymentType,
                'pmtaccount'=>$paymentAccount, 
                'updatedBy'=>$this->vendorId, 
                'updatedDtm'=>date('Y-m-d H:i:s')
            );

            $userData = $this->user_model->getUserInfoById($this->vendorId);

            $result1 = $this->login_model->loginMe($userData->email, $password);
            if(!empty($result1))
            {
                $result = $this->user_model->editUser($userInfo, $this->vendorId);
            
                if($result == true)
                {
                    $conditionUserMail = array('tbl_email_templates.type'=>'Account Updated');
                    $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                    $companyInfo = $this->settings_model->getsettingsInfo();
                
                    if($resultEmail->num_rows() > 0)
                    {
                        $rowUserMailContent = $resultEmail->row();
                        $splVars = array(
                            "!clientName" => $userData->firstName,
                            "!companyName" => $companyInfo['name'],
                            "!address" => $companyInfo['address'],
                            "!siteurl" => base_url()
                        );

                        $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                        $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                        $toEmail = $userData->email;
                        $fromEmail = $companyInfo['SMTPUser'];

                        $name = 'Support';

                        $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                        $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent,NULL);

                        if($send == true) {
                            $success = "Reset password link sent successfully, please check email.";
                        } else {
                            $success = "Email sending has failed, try again.";
                        }
                        //Send SMS
                        $phone = $userData->mobile;
                        if($phone){
                            $body = strtr($rowUserMailContent->sms_body, $splVars);
                            $this->twilio_model->send_sms($phone, $body);
                        }
                    }
                    $this->session->set_flashdata('success', 'Your withdrawal account has been updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Profile update failed');
                }

            } else {
                $this->session->set_flashdata('error', 'Incorrect Password');
            }

            redirect('profile/'.$active);
        }
    }

    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    function changePassword($active = "changepass")
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword', TRUE);
            $newPassword = $this->input->post('newPassword', TRUE);
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect('profile/'.$active);
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) { $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }
                
                redirect('profile/'.$active);
            }
        }
    }

    /**
     * This function is used to check whether email already exist or not
     * @param {string} $email : This is users email
     */
    function emailExists($email)
    {
        $userId = $this->vendorId;
        $return = false;

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ $return = true; }
        else {
            $this->form_validation->set_message('emailExists', 'The {field} already taken');
            $return = false;
        }

        return $return;
    }
}

?>