<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Settings (SettingsController)
 * Settings Class
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Settings extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->model('user_model');
        $this->load->model('login_model');
        $this->load->model('email_model');
        $this->load->model('payments_model');
        $this->load->model('addons_model');
        $this->load->model('twilio_model');
        $this->isLoggedIn();   
    }

    function settings()
    {
        $module_id = 'settings';
        $module_action = 'general_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
        $this->global['pageTitle'] = 'Settings';
        $this->global['displayBreadcrumbs'] = true; 
        $this->global['breadcrumbs'] = 'Settings'.' <span class="breadcrumb-arrow-right"></span> '.'General';
        $data["companyInfo"] = $this->settings_model->getSettingsInfo();
        $data['periods'] = $this->payments_model->getAllPeriods();
        $this->loadViews("settings/settings", $this->global, $data, NULL);
        }
    }

    function email_templates(){
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {        
            $this->global['pageTitle'] = 'Email Templates';
            $this->global['displayBreadcrumbs'] = true; 
            $this->global['breadcrumbs'] = 'Settings'.' <span class="breadcrumb-arrow-right"></span> '.'Emails';
            
            $searchText = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count = $this->email_model->emailListingCount($searchText);
			$returns = $this->paginationCompress ( "settings/email_templates/", $count, 11 );
            
            $data['emailTemplates'] = $this->email_model->emails($searchText, $returns["page"], $returns["segment"]);

            //Let us load the first email
            $data['emailID'] = $this->email_model->firstEmailRow()->id;
            $data['emailSubject'] = $this->email_model->firstEmailRow()->mail_subject;
            $data['emailBody'] = html_purify($this->email_model->firstEmailRow()->mail_body);
            
            $this->loadViews("settings/emailTemplates", $this->global, $data, NULL);
        }

    }

    function editEmailTemplate() {
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email_subject','Email Subject','trim|required');
            $this->form_validation->set_rules('email_body','Email Body','trim|required');

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
                $subject = $this->input->post('email_subject', TRUE);
                $body    = html_purify($this->input->post('email_body'));
                $emailId = $this->input->post('email_id', TRUE);

                $emailInfo = array(
                    'mail_subject'=>$subject, 
                    'mail_body'=>$body, 
                    'modifiedBy'=>$this->vendorId, 
                    'modifiedDtm'=>date('Y-m-d H:i:s')
                );

                $result = $this->email_model->updateEmailSettings($emailInfo, $emailId);

                if($result == true)
                {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape('Successfully edited the email template'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('Failed to edit the email template'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function companyInfoUpdate()
    {
        $this->global['pageTitle'] = 'Settings';
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
        $module_id = 'settings';
        $module_action = 'general_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();

        $data["companyInfo"] = $this->settings_model->getsettingsInfo();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('companyName','Company Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('email','Company Email','required');
        $this->form_validation->set_rules('currency','Currency','required');
        $this->form_validation->set_rules('position','Currency Position','required');
        if($this->input->post('currency', TRUE) !== 'USD'){
            $this->form_validation->set_rules('excurrency','Exchange Rate','required');
        }
        $this->form_validation->set_rules('password','Password','required');
        
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
            $companyName = $this->input->post('companyName', TRUE);
            $phone1 = $this->input->post('phone1', TRUE);
            $phone2 = $this->input->post('phone2', TRUE);
            $email = $this->input->post('email', TRUE);
            $address = $this->input->post('address', TRUE);
            $currency = $this->input->post('currency', TRUE);
            $cu_position = $this->input->post('position', TRUE);
            $currency_ex = $this->input->post('excurrency', TRUE);
            $url = $this->input->post('url', TRUE);
            $password = $this->input->post('password', TRUE);
            $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

            $result1 = $this->login_model->loginMe($useremail, $password);
            if(!empty($result1))
            {
                //Upload the logos First
                if(isset($_FILES["white-logo"]["name"])){
                    if ($this->security->xss_clean($this->input->post('white-logo'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('white-logo')){
                            $data = ($this->upload->data());
                            $nameLogoWhite = $data["file_name"];
                            $white_logourl = '<img class="logo-showcase-white" src="'.base_url().'uploads/'.$data["file_name"].'">';
                        }else{
                            $errors = $this->upload->display_errors();
                            $nameLogoWhite = $this->settings_model->getSettingsInfo()['whiteLogo'];
                            $white_logourl = '';
                        }; 
                    }
                } 

                if(isset($_FILES["dark-logo"]["name"])){
                    if ($this->security->xss_clean($this->input->post('white-logo'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('dark-logo')){
                            $data = ($this->upload->data());
                            $nameLogoDark = $data["file_name"];
                            $dark_logourl = '<img class="logo-showcase-dark" src="'.base_url().'uploads/'.$data["file_name"].'">';
                        }else{
                            $errors = $this->upload->display_errors();
                            $nameLogoDark = $this->settings_model->getSettingsInfo()['darkLogo'];
                            $dark_logourl = '';
                        }; 
                    }
                }

                if(isset($_FILES["favicon-logo"]["name"])){
                    if ($this->security->xss_clean($this->input->post('favicon-logo'), TRUE) === TRUE)
                    {
                        $config["upload_path"] = './uploads';
                        $config['allowed_types'] = 'jpg|png';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('favicon-logo')){
                            $data = ($this->upload->data());
                            $nameFavicon = $data["file_name"];
                            $favicon_url = '<img class="logo-favicon" src="'.base_url().'uploads/'.$data["file_name"].'">';
                        }else{
                            $errors = $this->upload->display_errors();
                            $nameFavicon = $this->settings_model->getSettingsInfo()['favicon'];
                            $favicon_url = '';
                        }; 
                    }
                }

                 $companyInfo = array(
                    array(
                        'type' => 'name',
                        'value' => $companyName
                    ),
                    array(
                        'type' => 'phone1',
                        'value' => $phone1
                    ),
                    array(
                        'type' => 'phone2',
                        'value' => $phone2
                    ),
                    array(
                        'type' => 'email',
                        'value' => $email
                    ),
                    array(
                        'type' => 'address',
                        'value' => $address
                    ),
                    array(
                        'type' => 'url',
                        'value' => $url
                    ),
                    array(
                        'type' => 'whiteLogo',
                        'value' => $nameLogoWhite
                    ),
                    array(
                        'type' => 'darkLogo',
                        'value' => $nameLogoDark
                    ),
                    array(
                        'type' => 'favicon',
                        'value' => $nameFavicon
                    ),
                    array(
                        'type' => 'currency',
                        'value' => $currency
                    ),
                    array(
                        'type' => 'currency_position',
                        'value' => $cu_position
                    ),
                    array(
                        'type' => 'currency_exchange_rate',
                        'value' => $currency_ex
                    )
                );
                
               $this->db->update_batch('tbl_settings', $companyInfo, 'type');

                 $array = array(
                    'success' => true,
                    'msg' => html_escape('Succesfully updated your account info'),
                    'whiteLogo' => $white_logourl,
                    'darkLogo' => $dark_logourl,
                    'favicon' => $favicon_url,
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);

            } else {
                $array = array(
                    'success' => false,
                    'msg' => html_escape('Incorrect password. Please try again'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }         
        }
        }
    }

    function SEO_Update()
    {
        $module_id = 'settings';
        $module_action = 'general_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('title','Page Title','required');
        
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
            $response['msg'] = html_escape('Please correct the errors displayed and try again.');

            echo json_encode($response); 
        }
        else
        {
            $title = $this->input->post('title', TRUE);
            $description = $this->input->post('description', TRUE);
            $keywords = $this->input->post('keywords', TRUE);
            //$chat = htmlentities($this->input->post('chatwidget'));
            $password = $this->input->post('password', TRUE);
            $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

            $result1 = $this->login_model->loginMe($useremail, $password);
            if(!empty($result1))
            {
                $companyInfo = array(
                    array(
                        'type' => 'title',
                        'value' => $title
                    ),
                    array(
                        'type' => 'description',
                        'value' => $description
                    ),
                    array(
                        'type' => 'keywords',
                        'value' => $keywords
                    )/*,
                    array(
                        'type' => 'chatWidget',
                        'value' => $chat
                    )*/
                );
                
                $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');
            
                if($result == true)
                {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape('Your info has been updated succesfully'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('There was nothing to update. Please check and try again.'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }

            } else {
                $array = array(
                    'success' => false,
                    'msg' => html_escape('Incorrect password. Please try again'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }         
        }
        }
    }

    function paymentMethods()
    {
        $module_id = 'settings';
        $module_action = 'payment_methods';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $searchText = $this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $count = $this->payments_model->listingCount('tbl_payment_methods', $searchText);
			$returns = $this->paginationCompress ( "settings/paymentMethods/", $count, 10 );
            $data['paymentMethods'] = $this->payments_model->getAll('tbl_payment_methods',$searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Payment Methods';
            $this->global['displayBreadcrumbs'] = false; 
            $this->loadViews("settings/payments", $this->global, $data, NULL);
        }
    }

    function addons()
    {
        $module_id = 'settings';
        $module_action = 'API_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText =$this->input->post('searchText', TRUE);
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $count = $this->payments_model->listingCount('tbl_addons_api', $searchText);
			$returns = $this->paginationCompress ( "settings/addons/", $count, 10 );
            $data['paymentMethods'] = $this->payments_model->getAll('tbl_addons_api', $searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Add-ons Settings';
            $this->global['displayBreadcrumbs'] = false; 
            $this->loadViews("settings/addons", $this->global, $data, NULL);
        }
    }

    function paymentmethodInfo()
    {
        $module_id = 'settings';
        $module_action = 'payment_methods';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $method = $this->input->post('method', TRUE);
            $paymentMethod = $this->payments_model->getInfo('tbl_payment_methods', $method);
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            if($method != 'Bank Transfer'){
                $array = array(
                    'id' => $paymentMethod->id,
                    'name' => $paymentMethod->name,
                    'logo' => $paymentMethod->logo,
                    'ref' => $paymentMethod->ref,
                    'API' => $paymentMethod->API,
                    'status' => $paymentMethod->status,
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );
            }else{
                $array = array(
                    'id' => $paymentMethod->id,
                    'name' => $paymentMethod->name,
                    'logo' => $paymentMethod->logo,
                    'ref' => $paymentMethod->ref,
                    'bname' => $paymentMethod->bank_name,
                    'acname' => $paymentMethod->account_name,
                    'acnumber' => $paymentMethod->account_number,
                    'swcode' => $paymentMethod->swift_code,
                    'status' => $paymentMethod->status,
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );
            }

            echo json_encode($array);
        }
    }

    function addons_info()
    {
        $module_id = 'settings';
        $module_action = 'API_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $method = $this->input->post('method', TRUE);
            $paymentMethod = $this->payments_model->getInfo('tbl_addons_api', $method);

            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            
            $array = array(
                'id' => $paymentMethod->id,
                'name' => $paymentMethod->name,
                'logo' => $paymentMethod->logo,
                'publicKey' => $paymentMethod->public_key,
                'secretKey' => $paymentMethod->secret_key,
                'merchantID' => $paymentMethod->merchantID,
                'IPNKey' => $paymentMethod->IPN_secret,
                'IPNURL' => $paymentMethod->base_url,
                'status' => $paymentMethod->status,
                'env' => $paymentMethod->env,
                "csrfTokenName" => $csrfTokenName,
                "csrfHash" => $csrfHash
            );

            echo json_encode($array);
        }
    }

    function paymentMethodEdit()
    {
        $module_id = 'settings';
        $module_action = 'payment_methods';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $method = $this->input->post('method', TRUE);
            $id = $this->payments_model->getInfo('tbl_payment_methods', $method)->id;
            $this->load->library('form_validation');
            if($method != 'Bank Transfer'){
                $this->form_validation->set_rules('status','Status','required');
            }else{
                $this->form_validation->set_rules('status','Status','required');
                $this->form_validation->set_rules('bname','Bank Name','required');
                $this->form_validation->set_rules('acname','Account Name','required');
                $this->form_validation->set_rules('acnumber','Account Number','required');
                $this->form_validation->set_rules('swcode','Swift Code','required');
            }
            
            if($this->form_validation->run() == FALSE)
            {
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
                //find out if the API is active
                $APIstatus = $this->payments_model->getAPIStatus($method);
                if($APIstatus->status == 0 && $method != 'Bank Transfer')
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape("Please Activate the $APIstatus->name API"),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $status = $this->input->post('status', TRUE);

                    if($method == 'Bank Transfer'){
                        $bank_name = $this->input->post('bname', TRUE);
                        $bank_account = $this->input->post('acname', TRUE);
                        $ac_number = $this->input->post('acnumber', TRUE);
                        $swift = $this->input->post('swcode', TRUE);
                        $paymentInfo = array(
                            'status'=>$status,
                            'bank_name'=>$bank_name,
                            'account_name'=>$bank_account,
                            'account_number'=>$ac_number,
                            'swift_code'=>$swift
                        );
                    }   else
                    {
                        $paymentInfo = array(
                            'status'=>$status
                        );
                    }                 

                    $result = $this->payments_model->editInfo('tbl_payment_methods', $paymentInfo, $id);

                    if($result == true)
                    {
                        $array = array(
                            'success' => true,
                            '_id'=> $id,
                            'status'=>$status,
                            'msg' => html_escape($method.' Method has been updated.'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                    else
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape('There is a problem in updating your information'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                }
            }
        }
    }

    function addons_update()
    {
        $module_id = 'settings';
        $module_action = 'API_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $method = $this->input->post('method', TRUE);
            $id = $this->payments_model->getInfo('tbl_addons_api', $method)->id;
            $this->load->library('form_validation');
            if($method != 'Payeer'){
            $this->form_validation->set_rules('pKey','Public Key','required');
            }
            $this->form_validation->set_rules('sKey','Secret Key','required');
            $this->form_validation->set_rules('status','Status','required');
            if($method == 'CoinPayments'){
                $this->form_validation->set_rules('merchantID','Merchant ID','required');
                $this->form_validation->set_rules('IPNKey','IPN Key','required');
                $this->form_validation->set_rules('IPNURL','IPN Url','required');
            } else if($method == 'Paypal'){
                $this->form_validation->set_rules('env','Mode','required');
            }
            
            if($this->form_validation->run() == FALSE)
            {
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
                $pKey = $this->input->post('pKey', TRUE);
                $sKey = $this->input->post('sKey', TRUE);
                $merchant1 = $this->input->post('merchantID1', TRUE);
                $merchant = $this->input->post('merchantID', TRUE);
                $IPNKey = $this->input->post('IPNKey', TRUE);
                $IPNURL = $this->input->post('IPNURL', TRUE);
                $env = $this->input->post('mode', TRUE);
                $status = $this->input->post('status', TRUE);

                if($method == 'CoinPayments'){
                    $paymentInfo = array(
                        'public_key'=>$pKey, 
                        'secret_key'=>$sKey,
                        'merchantID'=>$merchant, 
                        'IPN_secret'=>$IPNKey,
                        'base_url'=>$IPNURL,
                        'status'=>$status
                    );
                } else if($method == 'Stripe') {
                    $paymentInfo = array(
                        'public_key'=>$pKey, 
                        'secret_key'=>$sKey, 
                        'status'=>$status
                    );
                } else if($method == 'Payeer') {
                    $paymentInfo = array(
                        'secret_key'=>$sKey,
                        'merchantID'=>$merchant1, 
                        'status'=>$status
                    );
                } else if($method == 'PayPal') {
                    $paymentInfo = array(
                        'public_key'=>$pKey,
                        'secret_key'=>$sKey, 
                        'env'=>$env,
                        'status'=>$status
                    );
                }else if($method == 'Twilio') {
                    $paymentInfo = array(
                        'secret_key'=>$sKey,
                        'public_key'=>$pKey, 
                        'status'=>$status
                    );
                }
                
                $result = $this->payments_model->editInfo('tbl_addons_api', $paymentInfo, $id);

                if($result == true)
                {
                    $array = array(
                        'success' => true,
                        'msg' => html_escape($method.' Method has been updated.'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('There is a problem in updating your information'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
            }
        }
    }

    function testEmail(){
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email','Email address','required');
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
        } else
        {
            $email = $this->input->post('email', TRUE);

            //Send Mail
            $conditionUserMail = array('tbl_email_templates.type'=>'Test Email');
            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

            $companyInfo = $this->settings_model->getsettingsInfo();
        
            if($resultEmail->num_rows() > 0)
            {
                $rowUserMailContent = $resultEmail->row();
                $splVars = array(
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
                    $array = array(
                        'success' => true,
                        'msg' => html_escape('Email sent succesfully'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                } else {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('Email sending has failed'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );
    
                    echo json_encode($array);
                }
            }
        }
    }

    function testSMS(){
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('phone','SMS Phone Number','required');
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
        } else
        {
            $this->load->model('twilio_model');
            $phone = $this->input->post('phone', TRUE);
            $body = 'Test SMS';

            $result = $this->twilio_model->send_sms($phone, $body);

            if($result)
            {
                $array = array(
                    'success' => true,
                    'msg' => html_escape('SMS sent succesfully'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            } else
            {
                $array = array(
                    'success' => false,
                    'msg' => html_escape('SMS sending has failed'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
        }
    }

    function SMSInfoUpdate()
    {
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->global['pageTitle'] = 'Settings';
            $data["companyInfo"] = $this->settings_model->getsettingsInfo();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('sms_phone','SMS Phone Number','required');
            $this->form_validation->set_rules('smsactive','SMS Active','required');
            $this->form_validation->set_rules('password','Password','required');

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
            } else {
                //Test 1: First check if Twilio API is active, if not break and tell the user to setup the API
                $API_status = $this->addons_model->get_addon_info('Twilio');

                if($API_status->status == 1){
                    $SMSPhone  = $this->input->post('sms_phone', TRUE);
                    $SMSActive = $this->input->post('smsactive', TRUE);
                    $password  = $this->input->post('password', TRUE);

                    $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;
                    $result = $this->login_model->loginMe($useremail, $password);

                    if(!empty($result))
                    {
                        $companyInfo = array(
                            array(
                                'type' => 'sms_active',
                                'value' => $SMSActive
                            ),
                            array(
                                'type' => 'sms_phone',
                                'value' => $SMSPhone
                            )
                        );
                        
                        $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');
                    
                        if($result == true)
                        {
                            $array = array(
                                'success' => true,
                                'msg' => html_escape('Your SMS Settings have been updated succesfully'),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );

                            echo json_encode($array);
                        }
                        else
                        {
                            $array = array(
                                'success' => false,
                                'msg' => html_escape('There was nothing to update. Please check and try again.'),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );

                            echo json_encode($array);
                        }

                    } else {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape('Incorrect Password'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                } else {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('Please Activate the Twilio API'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
            }
        }
    }
    

    function emailInfoUpdate()
    {
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();
            $this->global['pageTitle'] = 'Settings';
            $data["companyInfo"] = $this->settings_model->getsettingsInfo();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('SMTPHost','SMTP Host','required');
            $this->form_validation->set_rules('SMTPPort','SMTP Port','required');
            $this->form_validation->set_rules('SMTPEmail','SMTP User','required');
            $this->form_validation->set_rules('SMTPPass','SMTP Password','required');
            $this->form_validation->set_rules('SMTPProtocol','SMTP Protocol','required');
            $this->form_validation->set_rules('emailactive','SMS Active','required');
        
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
                $SMTPHost = $this->input->post('SMTPHost', TRUE);
                $SMTPPort = $this->input->post('SMTPPort', TRUE);
                $SMTPProtocol = $this->input->post('SMTPProtocol', TRUE);
                $SMTPUser = $this->input->post('SMTPEmail', TRUE);
                $SMTPPassword = $this->input->post('SMTPPass', TRUE);
                $emailActive = $this->input->post('emailactive', TRUE);

                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result1 = $this->login_model->loginMe($useremail, $password);
                if(!empty($result1))
                {
                    $companyInfo = array(
                        array(
                            'type' => 'SMTPHost',
                            'value' => $SMTPHost
                        ),
                        array(
                            'type' => 'SMTPPort',
                            'value' => $SMTPPort
                        ),
                        array(
                            'type' => 'SMTPProtocol',
                            'value' => $SMTPProtocol
                        ),
                        array(
                            'type' => 'SMTPUser',
                            'value' => $SMTPUser
                        ),
                        array(
                            'type' => 'SMTPPass',
                            'value' => $SMTPPassword
                        ),
                        array(
                            'type' => 'email_active',
                            'value' => $emailActive
                        )
                    );
                    
                    $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');
                
                    if($result == true)
                    {
                        $array = array(
                            'success' => true,
                            'msg' => html_escape('Your Email information has been updated succesfully'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                    else
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape('There was nothing to update. Please check and try again.'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }

                } else {
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

    function email_template(){
        $module_id = 'settings';
        $module_action = 'email_templates';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            if(empty($this->input->post('id', TRUE)))
            {
                return null;
            }
            else
            {
                $emailInfo = $this->email_model->getEmailInfoById($this->input->post('id', TRUE));
                $emailSubject = $emailInfo->mail_subject;
                $emailBody = $emailInfo->mail_body;
                $emailID = $emailInfo->id;

                $array = array(
                    'id' => $emailID,
                    'subject' => $emailSubject,
                    'body' => html_purify($emailBody),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
        }
    }

    function referralEdit()
    {
        $module_id = 'settings';
        $module_action = 'general_settings';
        if($this->isAdmin($module_id, $module_action) == FALSE)
        {
            $this->loadThis();
        } 
        else
        {
            $csrfTokenName = $this->security->get_csrf_token_name();
            $csrfHash = $this->security->get_csrf_hash();

            if($this->input->post('refType', TRUE) == 'simple')
            {
                $refType = 'simple';
                $int = $this->input->post('simpleInt', TRUE);
                $refpayouts = $this->input->post('refpayouts', TRUE);

                $password = $this->input->post('password', TRUE);
                $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                $result1 = $this->login_model->loginMe($useremail, $password);
                if(!empty($result1))
                {
                    $companyInfo = array(
                        array(
                            'type' => 'refType',
                            'value' => $refType
                        ),
                        array(
                            'type' => 'refInterest',
                            'value' => $int
                        ),
                        array(
                            'type' => 'disableRefPayouts',
                            'value' => $refpayouts
                        )
                    );
                    
                    $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');

                    if($result == true)
                    {
                        $array = array(
                            'success' => true,
                            'msg' => html_escape('Successfully changed the earnings settings'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                    else
                    {
                        $array = array(
                            'success' => false,
                            'msg' => html_escape('Failed to edit the earnings settings'),
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
                        'msg' => html_escape('Wrong password. Please try again'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }

            }
            else if($this->input->post('refType', TRUE) == 'multiple') 
            {
                $wkdpayouts = $this->input->post('wkdpayouts', TRUE);
                $refpayouts = $this->input->post('refpayouts', TRUE);
                $refType = 'multiple';
                $field_values_array = $_POST['multipleInt'];
                if(count($field_values_array) <= 1) 
                {
                    $array = array(
                        'success' => false,
                        'msg' => html_escape('Please input more than 1 level of interest.'),
                        "csrfTokenName" => $csrfTokenName,
                        "csrfHash" => $csrfHash
                    );

                    echo json_encode($array);
                }
                else
                {
                    $int = implode(', ', $field_values_array);
                    $password = $this->input->post('password', TRUE);
                    $useremail = $this->user_model->getUserInfoById($this->vendorId)->email;

                    $result1 = $this->login_model->loginMe($useremail, $password);
                    if(!empty($result1))
                    {

                        $companyInfo = array(
                            array(
                                'type' => 'refType',
                                'value' => $refType
                            ),
                            array(
                                'type' => 'refInterest',
                                'value' => $int
                            ),
                            array(
                                'type' => 'disableRefPayouts',
                                'value' => $refpayouts
                            )
                        );
                        
                        $result = $this->db->update_batch('tbl_settings', $companyInfo, 'type');

                        if($result == true)
                        {
                            $array = array(
                                'success' => true,
                                'msg' => html_escape('Successfully changed Earnings Settings'),
                                "csrfTokenName" => $csrfTokenName,
                                "csrfHash" => $csrfHash
                            );

                            echo json_encode($array);
                        }
                        else
                        {
                            $array = array(
                                'success' => false,
                                'msg' => html_escape('Failed to edit the earnings settings'),
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
                            'msg' => html_escape('Wrong password. Please try again'),
                            "csrfTokenName" => $csrfTokenName,
                            "csrfHash" => $csrfHash
                        );

                        echo json_encode($array);
                    }
                }
            }
        }
    }
}