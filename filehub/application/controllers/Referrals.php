<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Referrals (ReferralsController)
 * Referrals Class
 * @author : Axis96
 * @version : 1.0
 * @since : 07 December 2019
 */
class Referrals extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('transactions_model');
        $this->load->model('settings_model');
        $this->load->model('email_model');
        $this->load->model('twilio_model');
        $this->isLoggedIn();   
    }
    /**
     * This function used to send an invite link to new users
     */
    public function invite()
    {
        $csrfTokenName = $this->security->get_csrf_token_name();
        $csrfHash = $this->security->get_csrf_hash();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');

        if($this->form_validation->run() == FALSE)
        {
            $array = array(
                'success' => false,
                'msg' => html_escape('Please enter the email address of the person you want to refer us to'),
                "csrfTokenName" => $csrfTokenName,
                "csrfHash" => $csrfHash
            );

            echo json_encode($array);
        }
        else
        { 
            $data = $this->user_model->getUserInfo($this->vendorId);
            $name = $data->firstName;
            $refcode = $data->refCode;
            $joinLink = base_url()."signup/".$data->refCode;

            //Send Mail
            $conditionUserMail = array('tbl_email_templates.type'=>'Referral Invitation');
            $resultEmail = $this->email_model->getEmailSettings($conditionUserMail);

            $companyInfo = $this->settings_model->getsettingsInfo();
        
            if($resultEmail->num_rows() > 0)
            {
                $rowUserMailContent = $resultEmail->row();
                $splVars = array(
                    "!referrerName" => $name,
                    "!referralLink" => $joinLink,
                    "!companyName" => $companyInfo['name'],
                    "!address" => $companyInfo['address'],
                    "!siteurl" => base_url()
                );

            $mailSubject = strtr($rowUserMailContent->mail_subject, $splVars);
            $mailContent = strtr($rowUserMailContent->mail_body, $splVars); 	

            $toEmail = $this->security->xss_clean($this->input->post('email'));
            $fromEmail = $companyInfo['SMTPUser'];

            $name = 'Support';

            $header = "From: ". $name . " <" . $fromEmail . ">\r\n"; //optional headerfields

            $send = $this->email_model->sendHtmlMail($toEmail,$fromEmail,$mailSubject,$mailContent,null);

            if($send == true) {
                $array = array(
                    'success' => true,
                    'msg' => html_escape('Your invitation has been sent succesfully'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            } else {
                $array = array(
                    'success' => false,
                    'msg' => html_escape('There is an error in sending your invitation. Please try again later.'),
                    "csrfTokenName" => $csrfTokenName,
                    "csrfHash" => $csrfHash
                );

                echo json_encode($array);
            }
            }           
        }
    }

}