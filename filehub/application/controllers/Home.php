<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Home (HomeController)
 * Home class to display the main site
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Home extends BaseController {

	public function __construct()
    {
        parent::__construct();
		$this->load->model('settings_model');
		$this->load->model('transactions_model');
		$this->load->model('email_model');
		$this->load->model('twilio_model');
    }

	public function index()
	{
		$this->load->model('plans_model');
		$companyInfo = $this->settings_model->getsettingsInfo();
		$data['companyInfo'] = $companyInfo;
		$this->global['pageTitle'] = $companyInfo['name'];
		$data["plans"] = $this->plans_model->getPlans(1);

        $this->loadViews('/siteContent/home', $this->global, $data, NULL);
	}

	public function error_404()
	{
		$data['pageTitle'] = 'Error 404';
		$this->load->model('settings_model');
		$this->load->view('404', $data);
	}

	public function terms()
	{
		$this->global['pageTitle'] = 'Terms';
		$data['companyInfo'] = $this->settings_model->getsettingsInfo();
		$this->loadViews('/siteContent/terms', $this->global, $data, NULL);
	}

	public function privacy()
	{
		$this->global['pageTitle'] = 'Privacy';
		$data['companyInfo'] = $this->settings_model->getsettingsInfo();
		$this->loadViews('/siteContent/privacy', $this->global, $data, NULL);
	}
	public function contact_us()
	{
		$this->load->helper(array('form', 'url'));

		//Validation
		$this->load->library('form_validation'); 
		  
        $this->form_validation->set_rules('name','First Name','trim|required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
        $this->form_validation->set_rules('subject','subject','required');
		$this->form_validation->set_rules('comment','comment','required');

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
                $response['msg'] = html_escape('Please correct the errors displayed and try again.');

                echo json_encode($response); 
			}
        }
		else
		{
			$name = $this->input->post('name', TRUE);
			$email = $this->input->post('email', true);
			$subject = $this->input->post('subject', TRUE);
			$message = $this->input->post('comment', TRUE);

			$companyInfo = $this->settings_model->getsettingsInfo();

			$mailSubject = 'New Enquiry About ' . $companyInfo['name'];
			$mailContent = 'Full Name: '.$name.'<br>'.'Email Address: '.$email.'<br>'.'Subject: '.$subject.'<br>'.'Message: '.$message; 	

			$toEmail = $companyInfo['email'];
			$fromEmail = $email;

			$name = 'Support';

			$header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

			$send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent,NULL);

			if($send == true) {
				$this->session->set_flashdata('success', 'Deposit has been added succesfully');
				$array = array(
					'success' => true,
					'msg' => html_escape("Your message has been sent succesfully"),
				);
	
				echo json_encode($array);
			} else {
				$this->session->set_flashdata('success', 'Deposit has been added succesfully. However, email sending has failed.');
				$array = array(
					'success' => true,
					'msg' => html_escape("We are having an issue in sending out the email. If persistent, please try out our emails at ".$companyInfo['email']),
				);
	
				echo json_encode($array);
			}
		}
		if (!$this->input->is_ajax_request()) {
		redirect('/#contact');
		}
	}
	
	function earningsEmails(){
        //Get earnings where emails have not been sent
        $type = 0; //Type 0 are unsent emails 1 are sent email
        $pendingEmails = $this->transactions_model->getEarningsEmails($type);
        foreach($pendingEmails as $client){
            //Send Mail
            $conditionUserMail = array('tbl_email_templates.type'=>'Earnings Email');
            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);
            $companyInfo = $this->settings_model->getsettingsInfo();
        
            if($resultEmail->num_rows() > 0)
            {
                $rowUserMailContent = $resultEmail->row();
                $splVars = array(
                    "!clientName" => $client->firstName,
                    "!amount" => to_currency($client->amount),
                    "!ref" => $client->txnCode,
                    "!companyName" => $companyInfo['name'],
                    "!address" => $companyInfo['address'],
                    "!siteurl" => base_url()
                );

                $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
                $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

                $toEmail = $client->email;
                $fromEmail = $companyInfo['SMTPUser'];

                $name = 'Support';

                $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

                $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent, NULL);

				$array = array(
					'email_sent' => '1',
				);
				
				$resultEarnings =$this->transactions_model->editEarning($client->txnCode, $array);
            }
		}
		$array = array(
			'success' => true,
			'msg' => html_escape("Cronjob succesful"),
		);

		echo json_encode($array);
	}
}