<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Transactions (TransactionsController)
 * Transactions Class
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Transactions extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('plans_model');
        $this->load->model('login_model');
        $this->load->model('transactions_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('payments_model');
        $this->load->model('twilio_model');
        $this->isLoggedIn();   
    }

    /**
     * This function is used to load the earnings list
     */
    function earnings()
    {
        if($this->role == ROLE_CLIENT)
        {
            $searchText = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $this->global['searchText'] = $this->input->post('searchText', TRUE);
            $role = '3';

            $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
            
            $this->load->library('pagination');
            
            $count = $this->transactions_model->earningsListingCount($searchText, $this->vendorId, date('Y-m-d H:i:s'));
			$returns = $this->paginationCompress ( "earnings/", $count, 10 );
            
            $data['transactions'] = $this->transactions_model->earnings($searchText, $returns["page"], $returns["segment"], $this->vendorId, date('Y-m-d H:i:s'));
            $data['interestEarnings'] = $this->transactions_model->getEarningsTotalByType($this->vendorId, 'interest');
            $data['referralEarnings'] = $this->transactions_model->getEarningsTotalByType($this->vendorId, 'referral');
            $data['principalEarnings'] = $this->transactions_model->getEarningsTotalByType($this->vendorId, 'principal');

            $this->global['pageTitle'] = 'Earnings';   
            $this->global['displayBreadcrumbs'] = false;          
            $this->loadViews("transactions/table", $this->global, $data, NULL);
            
        }
        else
        {     
            $module_id = 'payouts';
            $module_action = 'view';
            if($this->isAdmin($module_id, $module_action) == FALSE)
            {
                $this->loadThis();
            } else
            {
                $searchText = $this->input->post('searchText', TRUE);
                $data['searchText'] = $searchText;
                $this->global['searchText'] = $this->input->post('searchText', TRUE);
                $role = '3';
                
                $this->load->library('pagination');
                
                $count = $this->transactions_model->allEarningsListingCount($searchText, date('Y-m-d H:i:s'));
                $returns = $this->paginationCompress ( "earnings/", $count, 10 );
                
                $data['transactions'] = $this->transactions_model->allEarnings($searchText, $returns["page"], $returns["segment"], date('Y-m-d H:i:s'));
                $data['interestEarnings'] = $this->transactions_model->getEarningsTotalByType(Null, 'interest');
                $data['referralEarnings'] = $this->transactions_model->getEarningsTotalByType(Null, 'referral');
                $data['principalEarnings'] = $this->transactions_model->getEarningsTotalByType(Null, 'principal');

                $this->global['pageTitle'] = 'Earnings'; 
                $this->global['displayBreadcrumbs'] = false;            
                $this->loadViews("transactions/table", $this->global, $data, NULL);
            }
        }
    }

    /**
     * This function is used to load the deposits list
     */
    function deposits()
    {
        if($this->role == ROLE_CLIENT)
        {
            $searchText = $this->input->post('searchText', TRUE);
            $this->global['searchText'] = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $role = '3';
            
            $this->load->library('pagination');
            
            $count = $this->transactions_model->depositsListingCount($searchText, $this->vendorId);
            $returns = $this->paginationCompress ( "deposits/".$searchText, $count, 10 );
            
            $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
            $data['transactions'] = $this->transactions_model->deposits($searchText, $returns["page"], $returns["segment"], $this->vendorId);
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits($this->vendorId);
            $data['inActiveDeposits'] = $this->transactions_model->getInActiveDeposits($this->vendorId);
            $data["plans"] = $this->plans_model->getPlans(); 
            
            $this->global['displayBreadcrumbs'] = false;
            $this->global['pageTitle'] = 'Deposits';
            
            $this->loadViews("transactions/table", $this->global, $data, NULL);
        }
        else
        {     
            $module_id = 'deposits';
            $module_action = 'view';
            if($this->isAdmin($module_id, $module_action) == FALSE)
            {
                $this->loadThis();
            } else
            {
                $searchText = $this->input->post('searchText', TRUE);
                $this->global['searchText'] = $this->input->post('searchText', TRUE);
                $data['searchText'] = $searchText;
                $role = '3';
                
                $this->load->library('pagination');
                
                $count = $this->transactions_model->allDepositsListingCount($searchText);
                $returns = $this->paginationCompress ( "deposits/", $count, 10 );
                
                $data['transactions'] = $this->transactions_model->allDeposits($searchText, $returns["page"], $returns["segment"]);
                $data['activeDeposits'] = $this->transactions_model->getActiveDeposits(Null);
                $data['inActiveDeposits'] = $this->transactions_model->getInActiveDeposits(Null);
                $data["plans"] = $this->plans_model->getPlans(); 
                
                $this->global['displayBreadcrumbs'] = false; 
                $this->global['pageTitle'] = 'Deposits';
                
                $this->loadViews("transactions/table", $this->global, $data, NULL);
            }
        }
    }

    /**
     * @access: Admin Only
     * @desc: This function is used to load the add new form
     */
    function newDeposit()
    {
        $companyInfo = $this->settings_model->getsettingsInfo();
        $data["companyInfo"] = $companyInfo ;
        $this->global['pageTitle'] = 'New Deposits';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Deposits'.' <span class="breadcrumb-arrow-right"></span> '.'New';
        $data['breadcrumbs'] = "Deposits / New Deposit";
        $this->load->helper('string');
        $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);

        if ($this->role == ROLE_CLIENT) 
        {
            $data["plans"] = $this->plans_model->getPlans(1);
            $data["clients"] = $this->user_model->users(Null, Null, Null, ROLE_CLIENT);
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits($this->vendorId);
            $data['paymentMethods'] = $this->payments_model->getPaymentMethods('1'); //Status 1 are activated payment methods
            
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('plan','Plan','trim|required|max_length[128]');
            $this->form_validation->set_rules('amount','Amount','trim|required|max_length[128]');
            $this->form_validation->set_rules('payMethod','Payment Method','required');

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $plan = $this->input->post('plan', TRUE);
                $amount = $this->input->post('amount', TRUE);
                $method = $this->input->post('payMethod', TRUE);
                $code = 'NJ'.random_string('alnum',8);

                //Check if the amount is indeed within the plan
                $planData = $this->plans_model->getPlanById($plan);
                $max = $planData->maxInvestment;
                $min = $planData->minInvestment;

                if($amount >= $min && $amount <= $max)
                {
                    if($method == 'ST') 
                    {
                        $paymentData = array(
                            'DepositAmount'  => $amount,
                            'planId' => $plan
                        );
                    
                        $this->session->set_userdata($paymentData);
                        // 'item' will be erased after 300 seconds
                        $this->session->mark_as_temp(array('DepositAmount', 'planId'), 300);

                        redirect('/stripe-payment');
                    }
                    else if($method == 'PP')
                    {
                        $methodData = $this->payments_model->getInfo('tbl_addons_api', 'paypal');
                        $cc_amount = $companyInfo['currency'] == 'USD' ? $amount : $amount/$companyInfo['currency_exchange_rate'];
                        $config = [
                            "clientID"=> $methodData->public_key,
                            "currency"=>"USD", //default is USD
                            "intent"=>"sale", //default is sale
                            "mode"=>$methodData->env,
                            "invoiceNumber"=>$code,
                            "clientSecret"=> $methodData->secret_key,
                            "redirectUrl"=> base_url('paypal/success'),//controller method paypal will return here after success
                            "cancelUrl"=>base_url('paypal/canceled')//localhost/paypal-integration-ci/index.php/welcome/payment/canceled"//controller method paypal will return here after cancel
                        ];
                        $this->load->library('paypal',$config);
                        $result = $this->paypal->pay($cc_amount);
                        $deposit_array = array(
                            'userid'=>$this->vendorId,
                            'invoice'=>$code,
                            'plan'=>$plan,
                            'txn_id'=>$result["payment"]->id,
                            'local_currency'=>$amount,
                            'payment_gross'=>$cc_amount,
                            'currency_code'=>'USD',
                            'payer_email'=>'NA',
                            'payment_status'=>'0',
                            'createdDtm'=>date('Y-m-d H:i:s')

                        );
                        $this->payments_model->addPaypal($deposit_array);
                        if($result["error"] == '') { 
                            redirect($result["approval_url"]);
                        } else { 
                            $this->session->set_flashdata('error', 'There is an error depositing via Paypal');
                            redirect('/deposits/new');
                        }
                    }
                    else if($method == 'BT')
                    {
                        $paymentData = array(
                            'DepositAmount'  => $amount,
                            'planId' => $plan
                        );
                    
                        $this->session->set_userdata($paymentData);
                        // 'item' will be erased after 300 seconds
                        $this->session->mark_as_temp(array('DepositAmount', 'planId'), 300);
                        redirect('/bank-transfer');
                    }
                    else if($method == 'BTC' OR 'ETH' OR 'LTC' OR 'DASH' OR 'XRP' OR 'BCH')
                    {
                        $paymentData = array(
                            'DepositAmount'  => $amount,
                            'planId' => $plan,
                            'method' => $method
                        );
                    
                        $this->session->set_userdata($paymentData);
                        // 'item' will be erased after 300 seconds
                        $this->session->mark_as_temp(array('DepositAmount', 'planId'), 300);

                        redirect('/coin-payment');
                    }
                }
                else
                {
                    $this->session->set_flashdata('error', 'Please input the correct amount according to your plan');
                }
            } 
            $this->loadViews("transactions/new", $this->global, $data);   
        } 
        else
        {
            $data["plans"] = $this->plans_model->getPlans();
            $module_id = 'deposits';
            $module_action = 'view';
            if($this->isAdmin($module_id, $module_action) == FALSE)
            {
                $this->loadThis();
            } 
            
            $data["clients"] = $this->user_model->users(Null, Null, Null, ROLE_CLIENT);
            $data['activeDeposits'] = $this->transactions_model->getActiveDeposits($this->vendorId);
            $data['paymentMethods'] = $this->payments_model->getPaymentMethods('1'); //Status 1 are activated payment methods

            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('email','Email','trim|required|max_length[128]');
            $this->form_validation->set_rules('plan','Plan','trim|required|max_length[128]');
            $this->form_validation->set_rules('amount','Amount','trim|required|max_length[128]');

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $plan = $this->input->post('plan', TRUE);
                $email = $this->input->post('email', TRUE);
                $amount = $this->input->post('amount', TRUE);
                $method = 'manual';
                $code = 'NJ'.random_string('alnum',8);
                $date = date('Y-m-d H:i:s');
                $datetime = date('Y-m-d H:i:s', strtotime($date));

                //Check if the amount is indeed within the plan
                $planData = $this->plans_model->getPlanById($plan);
                $max = $planData->maxInvestment;
                $min = $planData->minInvestment;

                if($amount >= $min && $amount <= $max)
                {
                    //Get Plan Details
                    $maturityPeriod = $this->plans_model->getMaturity($plan)->period_hrs;
                    $payoutsInterval = $this->plans_model->getPeriod($plan)->period_hrs;

                    //Check if email exists for client
                    $emailCheck = $this->login_model->checkClientExist($email, '3'); 

                    if($emailCheck)
                    {
                        if($method == 'manual') {
                            $plan = $this->plans_model->getPlanById($this->input->post('plan', TRUE));

                            $depositInfo = array(
                                'userId'=>$emailCheck->userId, 
                                'txnCode'=>$code,
                                'amount'=>$amount, 
                                'paymentMethod'=> $method, 
                                'planId' => $this->input->post('plan', TRUE),
                                'status' => $plan->principalReturn == 1 ? '0' : '3',
                                'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")), 
                                'createdBy'=>$this->vendorId, 
                                'createdDtm'=>$datetime
                            );

                            $userId = $emailCheck->userId;
                            $dAmount = $this->input->post('amount', TRUE);
                            $profitPercent = $plan->profit/100;
                            $earningsAmount = $profitPercent*$dAmount;
                            $earningsType = 'interest';
                            $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
                            $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));
                                
                            $result = $this->transactions_model->addNewDeposit($userId, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod);
                  
                            //Send email
                            if($result > 0){
                                //Put in cash to referree's accounts
                                $this->referralEarnings($userId, $dAmount, $result);

                                //Send Mail
                                $conditionUserMail = array('tbl_email_templates.type'=>'Deposit');
                                $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                                $companyInfo = $this->settings_model->getSettingsInfo();
                            
                                if($resultEmail->num_rows() > 0)
                                {
                                    $rowUserMailContent = $resultEmail->row();
                                    $splVars = array(
                                        "!clientName" => $emailCheck->firstName,
                                        "!depositAmount" => to_currency($this->input->post('amount', TRUE)),
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
                                        $this->session->set_flashdata('success', 'Deposit has been added succesfully');
                                    } else {
                                        $this->session->set_flashdata('success', 'Deposit has been added succesfully. However, email sending has failed.');
                                    }

                                    //Send SMS
                                    $userInfo = $this->user_model->getUserInfo($userId);
                                    $phone = $userInfo->mobile;
                                    if($phone){
                                        $body = strtr($rowUserMailContent->sms_body, $splVars);

                                        $this->twilio_model->send_sms($phone, $body);
                                    }
                                }
                                
                            } else {
                                $this->session->set_flashdata('error', 'Error in depositing the funds');
                            }

                        }
                    } 
                    else 
                    {
                        $this->session->set_flashdata('error', 'This email does not exist');
                    }
                }
                else
                {
                    $this->session->set_flashdata('error', 'Please input the correct amount according to your plan');
                }
            }
            $this->loadViews("transactions/new", $this->global, $data);
        }
        
    }

    function bankTransfer() {
        if(!$_SESSION['DepositAmount'])
        {
            redirect('deposits/new');
        } else
        {
            $amount = $this->session->flashdata('amount');
            $this->global['pageTitle'] = 'Bank Transfer';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = 'Deposits'.' <span class="breadcrumb-arrow-right"></span> '.'Bank Transfer';
            $data['payment'] = ($_SESSION['DepositAmount']);
            $bankData = $this->payments_model->getInfo('tbl_payment_methods', 'Bank Transfer');
            $data['bank_name'] = $bankData->bank_name;
            $data['account_name'] = $bankData->account_name;
            $data['account_number'] = $bankData->account_number;
            $data['swift_code'] = $bankData->swift_code;
            $this->loadViews("payments/banktransfer", $this->global, $data, NULL);
        }
    }

    function editDeposit($depositID = NULL)
    {
        $module_id = 'deposits';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            if($depositID == null)
            {
                redirect('deposits');
            } 
            $depositInfo = $this->transactions_model->getDepositInfoById($depositID);
            if($depositInfo->status != 0 || ($depositInfo->maturityDtm < date('Y-m-d H:i:s')))
            {
                redirect('deposits');
            }
            
            $this->load->helper('string');
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('plan','Plan','trim|required');
            $this->form_validation->set_rules('amount','Amount','trim|required');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('payMethod','Payment Method','trim|required');
            $this->form_validation->set_rules('date','Date','trim|required');

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $plan = $this->input->post('plan', TRUE);
                $email = $this->input->post('email', TRUE);
                $amount = $this->input->post('amount', TRUE);
                $method = $this->input->post('payMethod', TRUE);
                $date = $this->input->post('date', TRUE);
                $datetime = date('Y-m-d H:i:s', strtotime($date));

                //Get Plan Details
                $maturityPeriod = $this->plans_model->getMaturity($plan)->period_hrs;
                $payoutsInterval = $this->plans_model->getPeriod($plan)->period_hrs;

                //Check if email exists for client
                $emailCheck = $this->login_model->checkClientExist($email, '3'); 

                if($emailCheck)
                {
                    $depositInfo = array(
                        'userId'=>$emailCheck->userId, 
                        'amount'=>$amount, 
                        'paymentMethod'=> $method, 
                        'planId' => $plan,
                        'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")),  
                        'createdBy'=>$this->vendorId, 
                        'createdDtm'=>$datetime
                    );

                    $plan = $this->plans_model->getPlanById($this->input->post('plan', TRUE));
                    $dAmount = $this->input->post('amount', TRUE);
                    $profitPercent = $plan->profit/100;

                    $earningsAmount = $profitPercent*$dAmount;
                    $earningsType = 'interest';
                    $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
                    $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));

                    $result = $this->transactions_model->updateDeposit($emailCheck->userId, $depositID, $depositInfo, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod);
                        
                    if ($result > 0) 
                    {
                        $this->session->set_flashdata('success', 'Deposit has been edited succesfully');
                    } else 
                    {
                        $this->session->set_flashdata('error', 'Error in making an edit to the deposit.');
                    }
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'This email does not exist');
                }
            }

            $data['depositInfo'] = $depositInfo;
            $data["plans"] = $this->plans_model->getPlans();    
            $data['email'] = $this->user_model->getUserInfoById($depositInfo->userId)->email;  
            $data['paymentMethods'] = $this->payments_model->getPaymentMethods('1'); //Status 1 are activated payment methods      
            
            $this->global['pageTitle'] = 'Edit Deposit'; 
            $this->global['displayBreadcrumbs'] = false;      
            $this->global['breadcrumbs'] = 'Deposits'.' <span class="breadcrumb-arrow-right"></span> '.'Edit';          
            $this->loadViews("transactions/edit", $this->global, $data, NULL);
        }
    }

    function reInvest()
    {
        if(!$this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        }
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->load->helper('string');
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('password','Plan','trim|required');
            $this->form_validation->set_rules('code','code','trim|required');
            $this->form_validation->set_rules('plan','Plan','trim|required');

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
                $errors = array();
                // Loop through $_POST and get the keys
                foreach ($this->input->post() as $key => $value)
                {
                    // Add the error message for this field
                    $errors[$key] = form_error($key);
                }
                $response['errors'] = array_filter($errors); // Some might be empty
                $response['success'] = false;
                $response["csrfTokenName"] = $csrfTokenName;
                $response["csrfHash"] = $csrfHash;
                $response['msg'] = html_escape('Please correct the errors and try again.');

                echo json_encode($response);
            }
            else
            {
                $userId = $this->vendorId;
                $email = $this->user_model->getUserInfo($userId)->email;
                $password = $this->input->post('password', TRUE);
                $planId = $this->input->post('plan', TRUE);

                //Check if this is the right passsword
                $result = $this->login_model->loginMe($email, $password);

                if(!empty($result))
                {
                    $depositId = $this->input->post('code', TRUE);

                    $res = $this->transactions_model->getDeposit($depositId, $userId);

                    if($res && $res->status == 0)
                    {
                        //First update the record as having been withdrawn
                        $depositInfo = array(
                            'status' => '1'
                        );

                        $result = $this->transactions_model->editDeposit($depositInfo, $res->id);

                        if ($result == true) 
                        {
                            $code = 'NJ'.random_string('alnum',8);
                            $date = date('Y-m-d H:i:s');
                            $datetime = date('Y-m-d H:i:s', strtotime($date));

                            //Get Plan Details
                            $maturityPeriod = $this->plans_model->getMaturity($res->planId)->period_hrs;
                            $payoutsInterval = $this->plans_model->getPeriod($res->planId)->period_hrs;

                            //Create another deposit
                            $depositInfo1 = array(
                                'userId'=>$userId, 
                                'txnCode'=>$code,
                                'amount'=>$res->amount, 
                                'paymentMethod'=> 'reinvestment', 
                                'planId' => $planId,
                                'maturityDtm'=> date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours")), 
                                'createdBy'=>$userId, 
                                'createdDtm'=>$datetime
                            );

                            //Earnings Info
                            $plan = $this->plans_model->getPlanById($planId);
                            
                            $dAmount = $res->amount;
                            $profitPercent = $plan->profit/100;

                            $earningsAmount = $profitPercent*$dAmount;
                            $earningsType = 'interest';
                            $startDate = date('Y-m-d H:i:s', strtotime($date."+$payoutsInterval hours"));
                            $endDate = date('Y-m-d H:i:s', strtotime($date."+$maturityPeriod hours"));

                            $result1 = $this->transactions_model->addNewDeposit($userId, $depositInfo1, $earningsAmount, $startDate, $endDate, $payoutsInterval, $maturityPeriod);

                            if($result1>0){                            
                                //Send Mail
                                $conditionUserMail = array('tbl_email_templates.type'=>'Deposit');
                                $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                                $companyInfo = $this->settings_model->getsettingsInfo();
                            
                                if($resultEmail->num_rows() > 0)
                                {
                                    $rowUserMailContent = $resultEmail->row();
                                    $splVars = array(
                                        "!clientName" => $this->firstName,
                                        "!depositAmount" => to_currency($res->amount),
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

                                    $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent, NULL);

                                    if($send == true) {
                                        $array = array(
                                            'success' => true,
                                            'msg' => html_escape("You have successfully reinvested $$res->amount into your account."),
                                        );
                            
                                        echo json_encode($array);
                                    } else {
                                        $array = array(
                                            'success' => true,
                                            'msg' => html_escape("You have successfully reinvested $$res->amount into your account. However, email sending has failed."),
                                        );
                            
                                        echo json_encode($array);
                                    }

                                    //Send SMS
                                    $userInfo = $this->user_model->getUserInfo($userId);
                                    $phone = $userInfo->mobile;
                                    if($phone){
                                        $body = strtr($rowUserMailContent->sms_body, $splVars);

                                        $this->twilio_model->send_sms($phone, $body);
                                    }
                                }
                                
                            } else {
                                $array = array(
                                    'success' => false,
                                    'msg' => html_escape('There is an error in reinvesting your funds.'),
                                );
                    
                                echo json_encode($array);
                            }
                        }
                    } else
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape('You have either reinvested or withdrawn these funds. If this is not the case please contact our customer care team.'),
                        );
            
                        echo json_encode($array);
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('Wrong password. Please try again')
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function withdrawDeposit()
    {
        if(!$this->role == ROLE_CLIENT)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->helper('string');
            $depositID = $this->input->post('code', TRUE);
            $code = 'NJ'.random_string('alnum',8);
            if($depositID == null)
            {
                redirect('deposits');
            } 

            $userID = $this->vendorId;
            //Find out if the deposit is indeed matured, has not been withdrawn and belongs to this user
            $res = $this->transactions_model->getDeposit($depositID, $userID);

            if($res && $res->status == 0)
            {
                $depositInfo = array(
                    'status'=> '1'
                );
                        
                $result = $this->transactions_model->editDeposit($depositInfo, $res->id);

                if ($result == true) 
                {
                    $withdrawalInfo = array(
                        'userId'=>$userID, 
                        'txnCode'=>$depositID,
                        'amount'=>$res->amount, 
                        'status' => '0',
                        'createdBy'=>$this->vendorId, 
                        'createdDtm'=> date('Y-m-d H:i:s')
                    );
                    $result1 = $this->transactions_model->addNewWithdrawal($withdrawalInfo);

                    if($result1>0)
                    {
                        $result3 = $this->user_model->getUserInfo($this->vendorId);

                        $earningsInfo = array(
                            'userId'    => $userID,
                            'type'      => 'Principal',
                            'depositId' => $res->id,
                            'txnCode'   => $code,
                            'amount'    =>$res->amount,
                            'email_sent'=> '1'
                        );

                        $this->transactions_model->addNewEarning($earningsInfo);

                        if($result3)
                        {
                            //Send Mail
                            $conditionUserMail = array('tbl_email_templates.type'=>'Withdrawal Request');
                            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                            $companyInfo = $this->settings_model->getsettingsInfo();
                        
                            if($resultEmail->num_rows() > 0)
                            {
                                $rowUserMailContent = $resultEmail->row();
                                $splVars = array(
                                    "!clientName" => $result3->firstName,
                                    "!withdrawalAmount" => to_currency($res->amount),
                                    "!companyName" => $companyInfo['name'],
                                    "!address" => $companyInfo['address'],
                                    "!siteurl" => base_url()
                                );

                                $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                                $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                                $toEmail = $result3->email;
                                $fromEmail = $companyInfo['SMTPUser'];

                                $name = 'Support';

                                $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                                $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent,NULL);

                                if($send == true) {
                                    $array = array(
                                        'success' => true,
                                        'msg' => html_escape('Your withdrawal request is successful, please check email.'),
                                    );
                        
                                    echo json_encode($array);

                                    $this->session->set_flashdata('success', 'Your withdrawal request is successful, please check email.');
                                } else {
                                    $array = array(
                                        'success' => true,
                                        'msg' => html_escape('Your withdrawal request is successful, however email sending has failed, try again.'),
                                    );
                        
                                    echo json_encode($array);

                                    $this->session->set_flashdata('error', 'Your withdrawal request is successful, however email sending has failed, try again.');
                                }

                                //Send SMS
                                $phone = $result3->mobile;
                                if($phone){
                                    $body = strtr($rowUserMailContent->sms_body, $splVars);

                                    $this->twilio_model->send_sms($phone, $body);
                                }
                            }
                        }
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('There is an error in processing your withdrawal. Please try again later.'),
                    );
        
                    echo json_encode($array);

                    $this->session->set_flashdata('success', 'There is an error in processing your withdrawal. Please try again later.');
                }
            } else
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape('You are not allowed to process this withdrawal. It has either been processed or reinvested. Please contact customer care if this is not the case.'),
                );
    
                echo json_encode($array);
            }
        } 
    }

    function referralEarnings($userID = NULL, $amount = NULL, $depositID = NULL)
    {
        if($userID == Null || $amount == Null || $depositID == Null)
        {
            return false;
            //print_r('Either the user Id, amount or depositid is not available');
        }
        else 
        {
            //Get the referrer ID
            $referrerID = $this->user_model->getReferrerID($userID);

            //First Let's check whether this user has been referred by anyone
            if($referrerID != null) {
                //Check the referral method & interest
                $refMethod = $this->settings_model->getSettingsInfo()['refType'];
                $refInterest = $this->settings_model->getSettingsInfo(1)['refInterest'];
                $deposit_only_payouts = $this->settings_model->getSettingsInfo(1)['disableRefPayouts'];

                if($refMethod == 'simple')
                {   
                    $number_of_deposits = $this->transactions_model->depositsListingCount('', $referrerID);

                    //Calculate the referrer's earnings
                    $earnings = $amount * ($refInterest/100);

                    //for generating the txn code
                    $this->load->helper('string');

                    //Insert earnings into the earnings table
                    $array = array(
                        'type' => 'referral',
                        'userId'=> $referrerID,
                        'depositId' => $depositID,
                        'txnCode' => 'PO'.random_string('alnum',8),
                        'amount' => $earnings,
                        'createdDtm'=> date("Y-m-d H:i:s")
                    );
                    if($deposit_only_payouts == 1 && $number_of_deposits > 0) {
                        $result = $this->transactions_model->addNewEarning($array);
                    } else if($deposit_only_payouts == 0) {
                        $result = $this->transactions_model->addNewEarning($array);
                    } else {
                        $result = 0;
                    }

                    if($result > 0)
                    {
                        return true;
                        //print_r('New simple earning added');
                    } else 
                    {
                        return false;
                        //print_r('New simple earning not added');
                    }
                } else if($refMethod == 'multiple')
                {
                    //Find the referral levels
                    $levels_array = explode(',', $refInterest);
                    $levelsCount = count($levels_array);

                    //Get an array that looks like this [{id: 1, amount: 10}, {id: 2, amount: 15}]
                    for ($i=0; $i<$levelsCount; $i++) {
                        // Here we get the first referredID whose making the deposit
                        $referrerId_[0] = $userID;
                        //We then get multiple referrerIds based on the number of levels
                        $referrerId_[$i + 1] = $this->user_model->getReferrerID($referrerId_[$i]);
                        //We then proceed to put it in an array with referrerId_[1] as the first Id
                        $earningsData[] = (object) [
                            "id" => $referrerId_[$i + 1],
                            "interest" => $levels_array[$i],
                            "amount" => $amount * $levels_array[$i]/100
                        ];
                    }

                    //for generating the txn code
                    $this->load->helper('string');

                    //We then take the earnings data and remove all null Ids in the array to get the users
                    //that we should put soe earnings for
                    foreach($earningsData as $data) {
                        if($data->id != null) {
                            $array[] = array(
                                'type' => 'referral',
                                'userId'=> $data->id,
                                'depositId' => $depositID,
                                'txnCode' => 'PO'.random_string('alnum',8),
                                'amount' => $data->amount,
                                'createdDtm'=>date("Y-m-d H:i:s")
                            );
                        }
                    };

                    //Insert the data
                    $result = $this->transactions_model->addNewEarnings($array);

                    if($result > 0)
                    {
                        return true;
                    } else 
                    {
                        return false;
                    }
                }
            } else 
            {
                return false;
            }   
        }
    } 

    function deleteDeposit($depositID = NULL)
    {
        $module_id = 'deposits';
        $module_action = 'edit';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        { 
            if($depositID == null)
            {
                redirect('deposits');
            } 

            $depositInfo = $this->transactions_model->getDepositInfoById($depositID);
            if($depositInfo->status != 0 || ($depositInfo->maturityDtm < date('Y-m-d H:i:s')))
            {
                redirect('deposits');
            }

            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            $this->form_validation->set_rules('id','ID','required');
            $this->form_validation->set_rules('password','Password','required');

            if($this->form_validation->run() == FALSE)
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape('Please enter a password'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
            else
            {
                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result = $this->login_model->loginMe($useremail, $password);
                if(!empty($result))
                {
                    $result1 = $this->transactions_model->deleteDeposit($depositID);
                    if ($result1 == true){
                        $this->session->set_flashdata('success', 'You have succesfully deleted the transaction');
                        $array = array(
                            'success' => true,
                            'msg' => html_escape('You have succesfully deleted the transaction'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    } else {
                        $this->session->set_flashdata('error', 'There is a problem in deleting your deposit. Please reload and try again');
                        $array = array(
                            'success' => false,
                            'msg' => html_escape('There is a problem in deleting your deposit. Please reload and try again.'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('Incorrect Password'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                }
            }
        }
    }

    
    /**
     * This function is used to load the deposits list
     */
    function withdrawals()
    {
        $searchText = $this->input->post('searchText', TRUE);
        $this->global['searchText'] = $this->input->post('searchText', TRUE);

        if($this->role == ROLE_CLIENT)
        {
            
            $role = '3';

            $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
            
            $this->load->library('pagination');
            
            $count = $this->transactions_model->userWithdrawalsListingCount($searchText, $this->vendorId);
			$returns = $this->paginationCompress ( "withdrawals/", $count, 10 );
            
            $data['transactions'] = $this->transactions_model->withdrawalsById($searchText, $returns["page"], $returns["segment"], $this->vendorId);
            $data['earningsInfo'] = $this->transactions_model->getEarningsTotal($this->vendorId);
            $data['withdrawalsInfo'] = $this->transactions_model->getPendingWithdrawalsTotal($this->vendorId);
            
            $this->global['pageTitle'] = 'Withdrawals';
            $this->global['displayBreadcrumbs'] = false; 
            $this->loadViews("transactions/table", $this->global, $data, NULL);
        }
        else
        {  
            $module_id = 'withdrawals';
            $module_action = 'view';
            if($this->isAdmin($module_id, $module_action) == FALSE)
            {
                $this->loadThis();
            } else 
            {
                $role = '3';
            
                $this->load->library('pagination');
                
                $count = $this->transactions_model->allWithdrawalsListingCount($searchText);
                $returns = $this->paginationCompress ( "withdrawals/", $count, 10 );
                
                $data['transactions'] = $this->transactions_model->allwithdrawals($searchText, $returns["page"], $returns["segment"], $role);
                $data['earningsInfo'] = $this->transactions_model->getEarningsTotal(Null);
                $data['withdrawalsInfo'] = $this->transactions_model->getPendingWithdrawalsTotal(Null);
                
                $this->global['pageTitle'] = 'Withdrawals';
                $this->global['displayBreadcrumbs'] = false; 
                $this->loadViews("transactions/table", $this->global, $data, NULL);
            }      
        }
    }

    function approveWithdrawal($withdrawalId = NULL)
    {
        $module_id = 'withdrawals';
        $module_action = 'approve';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            if($withdrawalId == null)
            {
                redirect('withdrawals');
            }  
            
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            $this->load->library('form_validation');
            $this->form_validation->set_rules('id','ID','required');
            $this->form_validation->set_rules('password','Password','required');

            if($this->form_validation->run() == FALSE)
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape('Please enter a password'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
            else
            {
                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result = $this->login_model->loginMe($useremail, $password);
                if(!empty($result))
                {
                    $withdrawalInfo = array('status'=> 1);
                    $result1 = $this->transactions_model->updateWithdrawal($withdrawalInfo, $withdrawalId);

                    if($result1 == true)
                    {
                        $withdrawalInfo = $this->transactions_model->getWithdrawalInfo($withdrawalId);
                        $userId = $withdrawalInfo->userId;
                        $amount = $withdrawalInfo->amount;
                        $userInfo = $this->user_model->getUserInfo($userId);
                        $email = $userInfo->email;
                        $name = $userInfo->firstName;
                        //Send Mail
                        $conditionUserMail = array('tbl_email_templates.type'=>'Withdrawal Approval');
                        $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                        $companyInfo = $this->settings_model->getsettingsInfo();
                    
                        if($resultEmail->num_rows() > 0)
                        {
                            $rowUserMailContent = $resultEmail->row();
                            $splVars = array(
                                "!clientName" => $name,
                                "!withdrawalAmount" => to_currency($amount),
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
                                $this->session->set_flashdata('success', 'Your withdrawal request is successful, please check email.');
                                $array = array(
                                    'success' => true,
                                    'msg' => html_escape('You have succesfully approved the withdrawal.'),
                                    "csrfTokenName" => $csrfTokenName,
                                    "csrfHash" => $csrfHash
                                );
                
                                echo json_encode($array);
                            } else {
                                $this->session->set_flashdata('error', 'Email sending has failed, try again.');
                                $array = array(
                                    'success' => true,
                                    'msg' => html_escape('You have succesfully approved the withdrawal, however an email could not be sent to the client.'),
                                    "csrfTokenName" => $csrfTokenName,
                                    "csrfHash" => $csrfHash
                                );
                
                                echo json_encode($array);
                            }

                            //Send SMS
                            $phone = $userInfo->mobile;
                            if($phone){
                                $body = strtr($rowUserMailContent->sms_body, $splVars);

                                $this->twilio_model->send_sms($phone, $body);
                            }
                        }
                    } else
                    {
                        redirect('withdrawals');
                    }
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('Incorrect Password'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                }
            }
        }

    }

    /**
     * @access: Admin Only
     * @desc: This function is used to load the add new form
     */
    function newWithdrawal()
    {
        //$data["plans"] = $this->plans_model->getPlans();
        $this->global['pageTitle'] = 'New Withdrawal';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Withdrawals'.' <span class="breadcrumb-arrow-right"></span> '.'New';
        $data['displayBreadcrumbs'] = true;
        $data['breadcrumbs'] = "Withdrawals / New Withdrawal";
        $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);
        $this->load->helper('string');

        if ($this->role == ROLE_CLIENT) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('amount','Amount','trim|required|max_length[128]');

            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('errors', validation_errors());
            }
            else
            {
                $earnings = $this->transactions_model->getEarningsTotal($this->vendorId);
                $withdrawals = $this->transactions_model->getWithdrawalsTotal($this->vendorId);
                $pendingWithdrawals = $this->transactions_model->getPendingWithdrawalsTotal($this->vendorId);
                $availableFunds =  $earnings-$withdrawals;
                $amount = $this->input->post('amount', TRUE);

                if($availableFunds < $amount){
                    $this->session->set_flashdata('error', 'You don\'t have enough funds to make a withdrawal');
                } else if($amount > ($availableFunds - $pendingWithdrawals)) {
                    $this->session->set_flashdata('error', 'You have pending withdrawals. You can only make a withdrawal of'.' $'.($availableFunds - $pendingWithdrawals));
                } else {
                    $userId = $this->vendorId;
                    $code = 'WT'.random_string('alnum',8);
                    $status = 0;
                    $createdBy = $this->vendorId;
                    $createdDtm = date('Y-m-d H:i:s'); 

                    $withdrawalInfo = array(
                        'userId'=>$userId, 
                        'txnCode'=>$code,
                        'amount'=>$amount, 
                        'status' => $status,
                        'createdBy'=>$createdBy, 
                        'createdDtm'=>$createdDtm
                    );
                    $result = $this->transactions_model->addNewWithdrawal($withdrawalInfo);

                    if($result>0)
                    {
                        $result3 = $this->user_model->getUserInfo($this->vendorId);

                        if($result3)
                        {
                            //Send Mail
                            $conditionUserMail = array('tbl_email_templates.type'=>'Withdrawal Request');
                            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

                            $companyInfo = $this->settings_model->getsettingsInfo();
                        
                            if($resultEmail->num_rows() > 0)
                            {
                                $rowUserMailContent = $resultEmail->row();
                                $splVars = array(
                                    "!clientName" => $result3->firstName,
                                    "!withdrawalAmount" => to_currency($this->input->post('amount', TRUE)),
                                    "!companyName" => $companyInfo['name'],
                                    "!address" => $companyInfo['address'],
                                    "!siteurl" => base_url()
                                );

                                $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                                $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                                $toEmail = $result3->email;
                                $fromEmail = $companyInfo['SMTPUser'];

                                $name = 'Support';

                                $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                                $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent,NULL);

                                if($send == true) {
                                    $this->session->set_flashdata('success', 'Your withdrawal request is successful, please check email.');
                                } else {
                                    $this->session->set_flashdata('error', 'Your withdrawal request is successful, however email sending has failed, try again.');
                                }

                                //Send SMS
                                $phone = $result3->mobile;
                                if($phone){
                                    $body = strtr($rowUserMailContent->sms_body, $splVars);

                                    $this->twilio_model->send_sms($phone, $body);
                                }
                            }
                        } else {
                            $this->session->set_flashdata('success', 'Your withdrawal request has been received');
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 'There\'s an error in processing your withdrawal request. Try again later.');
                    }
                }
            }
            $data['earningsInfo'] = $this->transactions_model->getEarningsTotal($this->vendorId);
            $data['withdrawals'] = $this->transactions_model->getWithdrawalsTotal($this->vendorId);
            $data['pendingWithdrawals'] = $this->transactions_model->getPendingWithdrawalsTotal($this->vendorId);
            $data['accountInfo'] = abs($data['earningsInfo'] - $data['withdrawals'] - $data['pendingWithdrawals']);
            $this->loadViews("transactions/new", $this->global, $data);
        } else {
            $this->loadThis();
        }
        
    }

    function getDatesFromRange($start, $end, $payoutsInterval, $format = 'Y-m-d H:i:s') {
        $array = array();
        $interval = 'PT'.$payoutsInterval.'H';
        $interval = new DateInterval($interval);
    
        $realEnd = new DateTime($end);
        //$realEnd->add($interval);
    
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    
        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }
    
        return $array;
    }
}