<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = "home";
$route['404_override'] = 'home/error_404';
$route['translate_uri_dashes'] = FALSE;

/*********** WEBSITE ROUTES ************************/
$route['faqs'] = "home/faqs";
$route['terms'] = "home/terms";
$route['privacy'] = "home/privacy";
$route['contactus'] = "home/contact_us";

/************* AUTH ROUTES *************/
$route['signup'] = 'auth/signup';
$route['signup/(:any)'] = 'auth/signup/$1';
$route['login'] = 'auth/loginMe';
$route['forgotPassword'] = "auth/forgotPassword";
$route['resetPasswordUser'] = "auth/resetPasswordUser";
$route['resetPassword'] = "auth/resetPasswordConfirmUser";
$route['resetPassword/(:any)'] = "auth/resetPasswordConfirmUser/$1";
$route['resetPassword/(:any)/(:any)'] = "auth/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "auth/createPasswordUser";

/*********** SETTINGS ROUTES *******************/
$route['settings'] = 'settings/settings';
$route['settings/companyInfo'] = 'settings/companyInfoUpdate';
$route['settings/emailInfo'] = 'settings/emailInfoUpdate';
$route['settings/email_templates'] = 'settings/email_templates';
$route['settings/edit_email'] = 'settings/editEmailTemplate';
$route['emailTemplate'] = "Settings/email_template"; 
$route['paymentAPIInfo'] = "settings/addons_info";
$route['paymentmethodInfo'] = "settings/paymentmethodInfo";
$route['settings/referral'] = "Settings/referralEdit"; 
$route['settings/addonAPIUpdate'] = "settings/addons_update";
$route['settings/paymentMethodUpdate'] = "settings/paymentMethodEdit";
$route['settings/seo'] = "Settings/SEO_Update"; 

/*********** USER DEFINED ROUTES *******************/
$route['dashboard'] = 'user';
$route['invite'] = 'referrals/invite';
$route['logout'] = 'user/logout';

$route['addNew'] = "user/addNew";
$route['addNewUser'] = "user/addNewUser";
$route['editOld'] = "user/editOld";
$route['editOld/(:num)'] = "user/editOld/$1";
$route['editUser'] = "user/editUser";
$route['deleteUser'] = "user/deleteUser";
$route['profile'] = "user/profile";
$route['profile/(:any)'] = "user/profile/$1";
$route['profileUpdate'] = "user/profileUpdate";
$route['profileUpdate/(:any)'] = "user/profileUpdate/$1";
$route['paymentInfo'] = "user/paymentAccountUpdate";

$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['changePassword/(:any)'] = "user/changePassword/$1";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";
$route['login-history'] = "user/loginHistoy";
$route['login-history/(:num)'] = "user/loginHistoy/$1";
$route['login-history/(:num)/(:num)'] = "user/loginHistoy/$1/$2";
$route['referrals'] = 'referrals/referrals';


/*********** TEAM ROUTES *******************/
$route['team'] = 'user/team';
$route['team/(:num)'] = "user/team/$1";
$route['team/newManager'] = "user/addNewManager";
$route['team/editManager/(:num)'] = "user/editManager/$1";

/*********** CLIENTS ROUTES *******************/
$route['clients'] = 'user/clients';
$route['clients/newClient'] = "user/addNewClient";
$route['clients/viewClient/(:num)'] = "user/viewClient/$1";
$route['clients/(:num)'] = "user/clients/$1";
$route['clients/editClient/(:num)'] = "user/editClient/$1";

/*********** PLANS ROUTES *******************/
$route['plans'] = 'plans/inPlans';
$route['plans/(:num)'] = "plans/inPlans/$1";
$route['plans/new'] = "plans/addNewPlan";
$route['plans/edit/(:num)'] = "plans/editPlan/$1";
$route['plans/delete/(:num)'] = "plans/deletePlan/$1";

/*********** DEPOSITS ROUTES *******************/
$route['deposits'] = 'transactions/deposits';
$route['deposits/(:num)'] = "transactions/deposits/$1";
$route['deposits/new'] = "transactions/newDeposit";
$route['deposits/payment'] = "transactions/paymentPage";
$route['bitcoinPayment'] = "transactions/bitcoinDeposit";
$route['deposits/editTrans/(:num)'] = "transactions/editDeposit/$1";
$route['deposits/deleteTrans/(:num)'] = "transactions/deleteDeposit/$1";

/*********** WITHDRAWAL ROUTES *******************/
$route['withdrawals'] = 'transactions/withdrawals';
$route['withdrawals/(:num)'] = 'transactions/withdrawals/$1';
$route['withdrawals/new'] = "transactions/newWithdrawal";
$route["withdrawDeposit"] = "transactions/withdrawDeposit";
$route["reinvest"] = "transactions/reInvest";
$route['approveWithdrawal/(:num)'] = "transactions/approveWithdrawal/$1";

/*********** PAYMENTS ROUTES *******************/
$route['earnings'] = 'transactions/earnings';
$route['earnings/(:num)'] = 'transactions/earnings/$1';
$route['stripe-payment'] = "Stripe";
$route['paypal-payment'] = "Paypal/index";
$route['coin-payment'] = "Coinpayments";
$route['bank-transfer'] = "transactions/bankTransfer";
$route['paypal/callback'] = "Paypal/callback";
$route['stripePost']['post'] = "Stripe/stripePost";
$route['stripepaymentsuccess'] = "Stripe/success";
$route['ipncp/(:any)'] = "Coinpayments/IPN_Response/$1";
$route['checkpayment/(:any)'] = "Coinpayments/checkCoinPayments/$1";

$route['paypal/success'] = "Paypal/success";
$route['paypal/canceled'] = "Paypal/canceled";

/********** CRON JOBS ROUTES *********************/
$route['emailcronjob'] = 'home/earningsEmails';
/* End of file routes.php */
/* Location: ./application/config/routes.php */
