<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
/**
 * Class : Auth (AuthController)
 * Auth class to control the registration amd authentication of users and start their session.
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Auth extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('twilio_model');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }
    
    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->loadViews('/auth/login', $this->global);
        }
        else
        {
            redirect('/dashboard');
        }
    }

    public function signup()
    {
        //Referral Code 
        $refcode = $this->uri->segment(2);

        //Page Data
        $this->global['pageTitle']  = 'Signup Page';
        $data['companyName']        = $this->settings_model->getsettingsInfo()['name'];
        $data["code"]               = $refcode;

        //Helpers
        $this->load->helper('url');
        $this->load->helper('security');

        //Validation
        $this->load->library('form_validation');   
        $this->form_validation->set_rules('firstname','First Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('lastname','Last Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password','Password','required');
        $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]');
        $this->form_validation->set_rules('accept_terms','Terms and Conditions','required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('errors', validation_errors());

            //Ajax Request
            if ($this->input->is_ajax_request()) {
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }

                $response['errors'] = array_filter($errors); // Some might be empty
                $response['success'] = false;
                if (!isset($_POST['accept_terms'])){
                    $response['terms'] = html_escape('Please read and accept our terms and conditions.');
                }
                    $response['msg'] = html_escape('Please correct the errors displayed and try again.');

                echo json_encode($response); 
            }
        }
        else
        {
            $fname = ucwords(strtolower($this->input->post('firstname', TRUE)));
            $lname = ucwords(strtolower($this->input->post('lastname', TRUE)));
            $email = strtolower($this->input->post('email', TRUE));
            $password = $this->input->post('password', TRUE);
            $ref = $this->input->post('ref', TRUE);
            $roleId = '3';
            $dateCreated = date('Y-m-d H:i:s');
            $result = $this->login_model->checkEmailExist($email); 

            if($result>0){
                $this->session->set_flashdata('error', 'Email is in use');
                if ($this->input->is_ajax_request()) {
                    $array = array(
                        'errors' => array(
                            'email' => html_escape('This email is in use')
                        ),
                        'success' => false
                    );
    
                    echo json_encode($array);
                }

            } else {
                
                $this->load->helper('string');
                $code = random_string('alnum',8);
                $userInfo = array(
                    'email'=>$email, 
                    'password'=>getHashedPassword($password), 
                    'roleId'=>$roleId, 
                    'firstName'=> $fname,
                    'lastName'=> $lname, 
                    'refCode' => $code,
                    'createdDtm'=> $dateCreated
                );
                $this->load->model('user_model');
                $result1 = $this->user_model->addNewUser($userInfo);
                if($result1>0)
                {
                    //Send Mail
				    $conditionUserMail = array('tbl_email_templates.type'=>'registration');
				    $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                    $firstname = $this->input->post('firstname', TRUE);
                    $companyInfo = $this->settings_model->getsettingsInfo();
                    $companyname = $companyInfo['name'];  
				
				    if($resultEmail->num_rows() > 0)
				    {
					    $rowUserMailContent = $resultEmail->row();
					    $splVars = array(
                            "!site_name"   => $this->config->item('site_title'),
                            "!clientName"  => $firstname,
                            "!clientEmail" => $email,
                            "!companyName" => $companyname,
                            "!address"     => $companyInfo['address'],
                            "!siteurl"     => base_url()
                        );

					$mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
					$mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

					$toEmail = $this->input->post('email',TRUE);
					$fromEmail = $companyInfo['SMTPUser'];

					$name = 'Support';

					$header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

					$this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent,NULL);

				    //print_r($mailContent);
				    }

                    $result2 = $this->login_model->loginMe($email, $password);
                    if($ref) 
                    {
                        $isrefcode = $this->user_model->getReferralId($ref);
                        if($isrefcode)
                        {
                            $referrer = $isrefcode->userId;
                            $referred = $result2->userId;
                            $interest = '12%';
                            $created = date('Y-m-d H:i:s');
                            $referralInfo = array(
                                'referrerId' => $referrer,
                                'referredId' => $referred,
                                'interest' => '12',
                                'createdDtm' => $created
                            );
                            $this->user_model->addReferral($referralInfo);
                        }
                    } 
                    $lastLogin = $this->login_model->lastLoginInfo($result2->userId);
                    $sessionArray = array('userId'=>$result2->userId,                    
                                        'role'=>$result2->roleId,
                                        'roleText'=>$result2->role,
                                        'firstName'=>$result2->firstName,
                                        'lastName'=>$result2->lastName,
                                        'isLoggedIn' => TRUE
                                );

                    $this->session->set_userdata($sessionArray);
                    unset($sessionArray['userId'], $sessionArray['isLoggedIn']);
                    $loginInfo = array("userId"=>$result2->userId, "sessionData" => json_encode($sessionArray), "machineIp"=>$_SERVER['REMOTE_ADDR'], "userAgent"=>getBrowserAgent(), "agentString"=>$this->agent->agent_string(), "platform"=>$this->agent->platform());
                    $this->login_model->lastLogin($loginInfo);
                    if (!$this->input->is_ajax_request()) {
                    redirect('/dashboard');
                    } else
                    {
                        $array = array(
                            'success' => true,
                            'msg' => html_escape('Signup succesful'),
                            'url' => base_url().'dashboard'
                        );
        
                        echo json_encode($array);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Signup failed');
                    if ($this->input->is_ajax_request()) {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('Signup failed. Please try again')
                    );
    
                    echo json_encode($array);
                    }
                }
            }            
        }
        if (!$this->input->is_ajax_request()) {
        $this->loadViews('/auth/register', $this->global, $data);
        }
    }
    
    
    /**
     * This function used to logged in the client
     */
    public function loginMe()
    {
        $this->global['pageTitle'] = 'Login';
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
            $this->session->set_flashdata('errors', validation_errors());
        }
        else
        {
            $email = strtolower($this->input->post('email', TRUE));
            $password = $this->input->post('password', TRUE);
            
            $result = $this->login_model->loginMe($email, $password);
            
            if(!empty($result))
            {
                //Check is the user is active
                if($result->isActive == 1){
                    $this->session->set_flashdata('error', 'Your account has been deactivated. Please contact support.');
                    redirect('/login');
                } else
                {
                    $lastLogin = $this->login_model->lastLoginInfo($result->userId);
                    $sessionArray = array('userId'=>$result->userId,                    
                                            'role'=>$result->roleId,
                                            'roleText'=>$result->role,
                                            'firstName'=>$result->firstName,
                                            'lastName'=>$result->lastName,
                                            'ppic'=>$result->ppic,
                                            'lastLogin'=> $lastLogin->createdDtm,
                                            'isLoggedIn' => TRUE
                                    );

                    $this->session->set_userdata($sessionArray);
                    unset($sessionArray['userId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin']);
                    $loginInfo = array("userId"=>$result->userId, "sessionData" => json_encode($sessionArray), "machineIp"=>$_SERVER['REMOTE_ADDR'], "userAgent"=>getBrowserAgent(), "agentString"=>$this->agent->agent_string(), "platform"=>$this->agent->platform());
                    $this->login_model->lastLogin($loginInfo);
                    redirect('/dashboard');
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'Client email or password mismatch');
                
                $this->index();
            }
        }
    }

    /**
     * This function used to load forgot password view
     */
    public function forgotPassword()
    {
        $this->global['pageTitle'] = 'Forgot Password';
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->loadViews('/auth/forgotPassword', $this->global, NULL, NULL);
        }
        else
        {
            redirect('/dashboard');
        }
    }
    
    /**
     * This function used to generate reset password request link
     */
    function resetPasswordUser()
    {
        $status = '';
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('login_email','Email','trim|required|valid_email');
                
        if($this->form_validation->run() == FALSE)
        {
            $this->forgotPassword();
        }
        else 
        {
            $email = strtolower($this->input->post('login_email', TRUE));
            
            if($this->login_model->checkEmailExist($email))
            {
               
                $this->load->helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum',15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = getBrowserAgent();
                $data['client_ip'] = $this->input->ip_address();
                
                $save = $this->login_model->resetPasswordUser($data);                
                
                if($save)
                {
                    $reset_link = base_url() . "resetPassword/" . $data['activation_id'];
                    $userInfo = $this->login_model->getCustomerInfoByEmail($email);

                    if(!empty($userInfo)){
                        $data1["name"] = $userInfo->firstName.'&nbsp'.$userInfo->lastName;
                        $data1["email"] = $userInfo->email;
                        $data1["message"] = "Reset Your Password";
                    }

                    //Send Mail and SMS
				    $conditionUserMail = array('tbl_email_templates.type'=>'Forgot Password');
				    $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                    $companyInfo = $this->settings_model->getsettingsInfo();
				
				    if($resultEmail->num_rows() > 0)
				    {
					    $rowUserMailContent = $resultEmail->row();
					    $splVars = array(
                            "!clientName"  => $userInfo->firstName,
                            "!companyName" => $companyInfo['name'],
                            "!address"     => $companyInfo['address'],
                            "!siteurl"     => base_url(),
                            "!resetLink"   => $reset_link
                        );

					$mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
					$mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

					$toEmail   = $userInfo->email;
					$fromEmail = $companyInfo['SMTPUser'];

					$name = 'Support';

					$header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                    $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent);

				    //$sendStatus = resetPasswordEmail($data1);
                    if($send == true) {
                        setFlashData('success', "Reset password link sent successfully, please check your email.");
                    } else {
                        setFlashData('error', "Email sending has failed, try again.");
                    }

                    //Send SMS
                    $phone = $userInfo->mobile;
                    if($phone){
                        $body = strtr($rowUserMailContent->sms_body, $splVars);

                        $result = $this->twilio_model->send_sms($phone, $body);
                    }

                    }
                }
                else
                {
                    setFlashData('error', "It seems an error while sending your details, try again.");
                }
            }
            else
            {
                setFlashData('error', "This email is not registered with us.");
            }
            if ($this->input->post('redirect', TRUE) == 1) {
                redirect('profile');
            } else {
                redirect('forgotPassword');
            }
            
        }
    }

    /**
     * This function used to reset the password 
     * @param string $activation_id : This is unique id
     * @param string $email : This is user email
     */
    function resetPasswordConfirmUser($activation_id)
    {        
        $this->global['pageTitle'] = 'Reset Password';
        // Check activation id in database
        $activationInfo = $this->login_model->checkActivationDetails($activation_id);

        if ($activationInfo->num_rows() > 0) {
            $email = $activationInfo->row()->email;
            $userInfo = $this->login_model->getCustomerInfoByEmail($email);
            $data['email'] = $email;
            $data['name'] = $userInfo->firstName;
            $data['activation_code'] = $activation_id;
            $this->loadViews('/auth/newPassword', $this->global, $data);

            $this->load->library('form_validation');
        
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            
            if($this->form_validation->run() == FALSE)
            {
                //$this->resetPasswordConfirmUser($activation_id, urlencode($email));
                $this->session->set_flashdata('error', validation_errors());
            }
            else
            {
                $password = $this->input->post('password', TRUE);
                $cpassword = $this->input->post('cpassword', TRUE);
                
                // Check activation id in database
                $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
                
                if($is_correct == 1)
                {                
                    $this->login_model->createPasswordUser($email, $password);
                    setFlashData('success', 'Password reset successfully');
                    redirect("/login");
                }
                else
                {

                    setFlashData('error', 'Password reset failed');
                    redirect('/resetPassword'.$activation_id);
                }            
            }
        }
        else
        {
            redirect('/login');
        }
    }
    
    /**
     * This function used to create new password for user
     */
    function createPasswordUser()
    {
        $status = '';
        $message = '';
        $email = strtolower($this->input->post("email", TRUE));
        $activation_id = $this->input->post("activation_code", TRUE);
        $encoded_email = urlencode($email);
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('password','Password','required|max_length[20]');
        $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            //$this->resetPasswordConfirmUser($activation_id, urlencode($email));
            setFlashData('error', validation_errors());
            redirect('/resetPassword'.'/'.$activation_id);
        }
        else
        {
            $password = $this->input->post('password', TRUE);
            $cpassword = $this->input->post('cpassword', TRUE);
            
            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
            
            if($is_correct == 1)
            {                
                $this->login_model->createPasswordUser($email, $password);
                setFlashData('success', 'Password reset successfully');
                redirect("/login");
            }
            else
            {

                setFlashData('error', 'Password reset failed');
                redirect('/resetPassword'.$activation_id);
            }            
        }
    }
}

?>