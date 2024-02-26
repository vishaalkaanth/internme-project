<?php

namespace App\Controllers;

use App\Models\LoginModel;

class Login extends BaseController
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Kolkata');
    }

    // Get User's Device OS, OS Version & Browser
    function userdevicedata()
    {
        $agents = $this->request->getUserAgent();
        if ($agents->isBrowser()) {
            $agent = $agents->getBrowser() . ' ' . $agents->getVersion();
        } elseif ($agents->isRobot()) {
            $agent = $agents->getRobot();
        } elseif ($agents->isMobile()) {
            $agent = $agents->getMobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        // Browser
        if (isset($agent)) {
            $data['browser']    =    $agent;
        } else {
            $data['browser']    =    NULL;
        }

        // Device OS
        //$data['deviceos']    =    $agents->platform(); // Platform info (Windows, Linux, Mac, etc.)
        $platformarray        =    explode(' ', $agents->getPlatform());
        if (isset($platformarray) && count($platformarray) > 0) {
            $data['deviceos']    =    $platformarray[0];
        } else {
            $data['deviceos']    =    NULL;
        }

        // OS Version
        if ($agents->getPlatform()) {
            $data['osversion']    =    $agents->getPlatform();
        } else {
            $data['osversion']    =    NULL;
        }

        return $data;
    }

    // Get User's Device Type
    function detectDevice()
    {
        $userAgent        =    $_SERVER["HTTP_USER_AGENT"];
        $devicesTypes    = array(
            "computer"    =>    array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
            "tablet"    =>    array("tablet", "android", "ipad", "tablet.*firefox"),
            "mobile"    =>    array("mobile ", "android.*mobile", "iphone", "ipod", "opera mobi", "opera mini"),
            "bot"        =>    array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis")
        );

        if (isset($userAgent) && $userAgent != '') {
            if (isset($devicesTypes) && count($devicesTypes) > 0) {
                foreach ($devicesTypes as $deviceType => $devices) {
                    foreach ($devices as $device) {
                        if (preg_match("/" . $device . "/i", $userAgent)) {
                            $deviceName = ucfirst($deviceType);
                        } else {
                            $deviceName = NULL;
                        }
                    }
                }
            } else {
                $deviceName = NULL;
            }
        } else {
            $deviceName    =    NULL;
        }
        return $deviceName;
    }

    // Get User's IP Address - Function to get the client IP address
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    // Get User's IP Address - Function to get the client ip address
    function get_client_ip_env()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    // Get User's IP Address - Function to get the client ip address
    function get_client_ip_server()
    {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if ($_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if ($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    // Get User's IP Adress
    function getipaddress()
    {
        $ipaddress1    =    '';
        $ipaddress2    =    '';
        $ipaddress3    =    '';

        $ipaddress1    =    $this->get_client_ip();
        $ipaddress2    =    $this->get_client_ip_env();
        $ipaddress3    =    $this->get_client_ip();
        if ($ipaddress1 != '') {
            $ipaddress    =    $ipaddress1;
        } else if ($ipaddress2 != '') {
            $ipaddress    =    $ipaddress2;
        } else if ($ipaddress3 != '') {
            $ipaddress    =    $ipaddress3;
        } else {
            $ipaddress    =    '';
        }
        return $ipaddress;
    }

    public function main_login($user_type)
    {
        $session = session();
        if ($user_type == '1') {
            $session->set('usertype', '1');
            $session->set('login_usertype', 'candidate');
        } elseif ($user_type == '2') {
            $session->set('usertype', '2');
            $session->set('login_usertype', 'employer');
        } elseif ($user_type == '3') {
            $session->set('usertype', '3');
            $session->set('login_usertype', 'hr');
        } elseif ($user_type == '4') {
            $session->set('usertype', '4');
            $session->set('login_usertype', 'supervisor');
        }
        return redirect()->to('/login');
    }
    public function userlogin()
    {
        helper(['form']);
        $session = session();
        $logged_in = $session->get('isLoggedIn');
        if ($logged_in) {
            $user_type    =    $session->get('usertype');
            $userid    =    $session->get('userid');

            $userModel = new LoginModel();
            if ($user_type == '2') {
                return redirect()->to('/employer-dashboard');
            } elseif ($user_type == '1') {

                $where = array('userid' =>  $userid, 'can_profile_complete_status' => '1');
                // $get_data_can = $userModel->fetch_table_data('can_personal_details', $where);
                $get_data_can = $userModel->fetch_table_data_userlogin('can_personal_details', $where);
                if (!empty($get_data_can)) {
                    return redirect()->to('/search-internship');
                } else {
                    return redirect()->to('/personal-details');
                }
            } elseif ($user_type == '3') {
                return redirect()->to('organization-details');
                // return redirect()->to('/internship-list-hr');
            } elseif ($user_type == '4') {
                return redirect()->to('organization-details');
                // return redirect()->to('/internship-list-supervisor');
            }
        } else {
            return view('Auth/login');
        }
    }

    public function admin_login()
    {
        helper(['form']);
        $session = session();
        $logged_in = $session->get('isLoggedIn');
        if ($logged_in) {
            $user_type    =    $session->get('usertype');
            if ($user_type == '5') {
                return redirect()->to('/admin-dashboard');
            }
        } else {
            return view('Auth/admin_login');
        }
    }

    public function forgot_password($url_usertype)
    {
        $session = session();
        $ses_data = [
            'login_usertype' => $url_usertype
        ];
        $session->set($ses_data);
        return view('Auth/emp_forgot_pass');
    }

    public function forgot_password_otp()
    {
        return view('Auth/emp_forgot_otp');
    }
    public function reset_password()
    {
        return view('Auth/emp_reset_password');
    }

    public function loginAuth()
    {

        $session = session();
        $userModel = new LoginModel();
        $logsessionid        =    NULL;
        $userdevicedata        =    $this->userdevicedata();
        $logsessionid        =    $session->get('__ci_last_regenerate');


        if ($this->request->getMethod() == 'post') {

            $user_type_gen = $this->request->getVar('user_type');
            $mobile = $this->request->getVar('user_mobile');
            $email = $this->request->getVar('user_email');
            $password = $this->request->getVar('user_password');

            if ($user_type_gen == 1) {
                $user_type = $this->request->getVar('user_type');
            } else {
                $ses_data_user = [
                    'login_usertype' => 'employer'
                ];
                $session->set($ses_data_user);
                $user_type = $this->request->getVar('user_type');
                $session->setFlashdata('temp_email', $email);
                $usertypeIn = array('4', '2', '3');
                $where = array('email' => $email);
                $get_user_type = $userModel->exisCheck_userlogin('userlogin', $where, $usertypeIn);
                if (!empty($get_user_type)) {
                    if ($get_user_type->usertype == 2) {
                        if ($get_user_type->status == 1 && $get_user_type->active_status == 1) {
                            $user_type = $get_user_type->usertype;
                        } elseif ($get_user_type->status == 0 && $get_user_type->active_status == 1) {
                            $session->setFlashdata('error_msg', 'Your account activation is pending, awaiting for approval.');
                            return redirect()->to('/login');
                        } elseif ($get_user_type->status == 1 && $get_user_type->active_status == 0) {
                            $session->setFlashdata('error_msg', 'Your account has been deactivated, for further queries contact us at crm@internme.app.');
                            return redirect()->to('/login');
                        } elseif ($get_user_type->status == 2 && $get_user_type->active_status == 1) {
                            $session->setFlashdata('error_msg', 'Your account has been rejected, for further queries contact us at crm@internme.app.');
                            return redirect()->to('/login');
                        } else {
                            $session->setFlashdata('error_msg', 'Email ID does not exists.');
                            return redirect()->to('/login');
                        }
                    } elseif ($get_user_type->usertype == 3 || $get_user_type->usertype == 4) {
                        $where = array('userid' => $get_user_type->company_id, 'status' => '1');
                        $available_data = $userModel->fetch_table_row_loginAuth('userlogin', $where);
                        if ($get_user_type->status == 1 && $available_data->active_status == 1 && $available_data->status == 1) {
                            $user_type = $get_user_type->usertype;
                        } elseif ($get_user_type->status == 0) {
                            $session->setFlashdata('error_msg', 'Your account has been deactivated, contact your admin at' . $available_data->email . '');
                            return redirect()->to('/login');
                        } elseif ($get_user_type->status == 1 && $available_data->active_status == 0 && $available_data->status == 1) {
                            $session->setFlashdata('error_msg', "Your organization's account has been deactivated, contact your admin at " . $available_data->email);
                            return redirect()->to('/login');
                        } else {
                        }
                    }
                } else {
                    $session->setFlashdata('error_msg', 'Email ID does not exists.');
                    return redirect()->to('/login');
                }



                // if (!empty($get_user_type)) {
                //     if ($get_user_type->usertype == '2' || $get_user_type->usertype == '3' || $get_user_type->usertype == '4') {
                //         $user_type = $get_user_type->usertype;
                //     }
                // } else {
                //     $where = array('email' => $email, 'status' => '0');
                //     $get_user_type = $userModel->exisCheck_userlogin('userlogin', $where);
                //     $ses_data_user = [
                //         'login_usertype' => 'employer'
                //     ];
                //     $session->set($ses_data_user);
                //     $user_type = $this->request->getVar('user_type');
                //     $session->setFlashdata('temp_email', $email);
                //     if (!empty($get_user_type)) {
                //         if ($get_user_type->usertype == '2') {  
                //             $session->setFlashdata('error_msg', 'Your account is pending activation and awaiting approval.');
                //         } else {
                //             $where = array('userid' => $get_user_type->company_id, 'status' => '1');
                //             $available_data = $userModel->fetch_table_row_loginAuth('userlogin', $where);
                //             $session->setFlashdata('error_msg', 'Your account has been de-activated , please contact ' . $available_data->email . '');
                //         }
                //     } else {
                //         $session->setFlashdata('error_msg', 'Email ID does not exists.');
                //     }

                //     return redirect()->to('/login');
                // }
            }


            if (isset($email) && $user_type == '2') {
                $input = $this->validate([
                    'user_email' => 'required|valid_email',
                    'user_password' => 'required'
                ]);
                $ses_data = [
                    'login_usertype' => 'employer'
                ];
            }
            if (isset($email) && $user_type == '3') {
                $input = $this->validate([
                    'user_email' => 'required|valid_email',
                    'user_password' => 'required'
                ]);
                $ses_data = [
                    'login_usertype' => 'hr'
                ];
            }
            if (isset($email) && $user_type == '4') {
                $input = $this->validate([
                    'user_email' => 'required|valid_email',
                    'user_password' => 'required'
                ]);
                $ses_data = [
                    'login_usertype' => 'supervisor'
                ];
            }
            if (isset($mobile) && $user_type == '1') {
                $input = $this->validate([
                    'user_mobile' => 'required|numeric|max_length[10]',
                    'user_password' => 'required'
                ]);
                $ses_data = [
                    'login_usertype' => 'candidate'
                ];
            }
            $session->set($ses_data);
            if (!$input) {
                echo view('login', [
                    'validation' => $this->validator
                ]);
            } else {
                $data = $userModel->userlogin_loginAuth($email, $mobile, $user_type);
                if ($data) {
                    $salt = $data->salt_code;
                    $pass = $data->password;
                    $enc_password = hash("sha256", $password . $salt);
                    if (empty($data->candidate_firstname)) {
                        $user_name = $data->name;
                    } else {
                        $user_name = $data->candidate_firstname;
                    }
                    //$pass = $data->ref;
                    if ($enc_password == $pass) {
                        $current_datetime = $userModel->current_datetime();
                        $data_log = array('logged_in' => $current_datetime);
                        $where = array('userid' => $data->userid);
                        $logout = $userModel->update_commen("userlogin", $where, $data_log);

                        $postdata = array(
                            'userid'   => $data->userid,
                            'ipaddress'  => $this->getipaddress(),
                            'devicetype' => $this->detectDevice(),
                            'deviceid'   => '',
                            'deviceos'   => $userdevicedata['deviceos'],
                            'osversion'  => $userdevicedata['osversion'],
                            'browser'    => $userdevicedata['browser'],
                            'logintime'  => date('Y-m-d H:i:s'),
                            'status'  => 1
                        );
                        $result = $userModel->insert_commen('userlogin_track', $postdata);
                        // $where = array('userid'=> $data->userid,'status'=> '1');
                        // $available_data = $userModel->fetch_table_row('can_personal_details',$where);
                        // $where = array('userid'=> $data->userid,'status'=> '1');
                        // $available_data1 = $userModel->fetch_table_data('can_education_details',$where);
                        // if(isset($available_data1)){
                        //     $candidate_education_status=1;
                        // }else{
                        //     $candidate_education_status=0;
                        // }
                        $ses_data = [
                            'id' => $data->id,
                            'userid' => $data->userid,
                            'usertype' => $data->usertype,
                            'name' => $user_name,
                            'emp_company_name' => $data->industry_name,
                            'email' => $data->email,
                            'mobile' => $data->mobile,
                            // 'candidate_email' => $available_data->profile_email,
                            // 'candidate_education_status' => $candidate_education_status,
                            'isLoggedIn' => TRUE,
                        ];

                        $session->set($ses_data);
                        if ($data->usertype == '2' && $user_type == '2') {
                            $where = array('user_id' =>  $data->userid, 'status' => '1');
                            $get_data_internship = $userModel->exisCheck_common('employer_post_internship', $where);
                            // print_r($get_data_internship);exit;
                            if (!empty($get_data_internship)) {
                                // return redirect()->to('/internship-list');
                                return redirect()->to('/employer-dashboard');
                            } else {
                                // return redirect()->to('/post-internship');
                                return redirect()->to('/employer-dashboard');
                            }
                        } elseif ($data->usertype == '1' && $user_type == '1') {

                            //redirect when candidate login for appaly
                            $internship_numer    =    $this->request->getVar('internship_numer');
                            $gmetrix          = $session->get('gmetrix');
                            if (isset($internship_numer) && $internship_numer != '') {
                                return redirect()->to('/view-internship-details/' . $internship_numer);
                            } elseif(isset($gmetrix) && $gmetrix != ''){
                                return redirect()->to('gmetrix-view');
                            }else {
                                $where = array('userid' =>  $data->userid, 'can_profile_complete_status' => '1');
                                $get_data_can = $userModel->exisCheck_common('can_personal_details', $where);
                                if (!empty($get_data_can)) {
                                    $pricing_plan_redirect = $session->get('pricing_plan_redirect');
                                    if (!empty($pricing_plan_redirect)) {
                                        return redirect()->to('/pricing-plan');
                                    }
                                    return redirect()->to('/search-internship');
                                } else {
                                    return redirect()->to('/personal-details');
                                }
                            }
                        } elseif ($data->usertype == '3' && $user_type == '3') {


                            return redirect()->to('/employer-dashboard');
                        } elseif ($data->usertype == '4' && $user_type == '4') {


                            return redirect()->to('/employer-dashboard');
                        }
                    } else {
                        $session->setFlashdata('error_msg', 'Password is incorrect.');
                        $session->setFlashdata('temp_phone', $mobile);
                        $session->setFlashdata('temp_email', $email);
                        return redirect()->to('/login');
                    }
                } else {
                    if ($user_type == '2' || $user_type == '3' || $user_type == '4') {
                        $session->setFlashdata('error_msg', 'Email ID does not exists.');
                        $session->setFlashdata('temp_email', $email);
                    } else {
                        $session->setFlashdata('error_msg', 'Mobile Number does not exists.');
                        $session->setFlashdata('temp_phone', $mobile);
                    }
                    return redirect()->to('/login');
                }
            }
        }
    }

    public function emp_register($url_usertype)
    {
        $session = session();
        $ses_data = [
            'login_usertype' => $url_usertype
        ];
        $session->set($ses_data);

        return view('Auth/emp_register');
    }
    public function emp_register_otp()
    {
        return view('Auth/emp_register_otp');
    }
    public function emp_set_password()
    {
        return view('Auth/emp_set_password');
    }

    public function user_retrieve_password()
    {
        $otp = mt_rand(100000, 999999);
        $session = session();
        // print_r($_SESSION);
        $user_type = $this->request->getVar('user_type');
        // echo $user_type;exit;
        $mobile = $this->request->getVar('user_mobile');
        $email = $this->request->getVar('user_email');
        $userModel = new LoginModel();
        if (isset($email) && $user_type == '2') {
            // $where = array('email' => $email,'usertype' => $user_type, 'status' => '1');
            // $where = '( date(userlogin.logged_in) < "'.$prev_date.'" OR userlogin`.`logged_in IS NULL )';
            $where = '(email="' . $email . '" AND (usertype = "2" OR usertype = "3" OR usertype = "4" ) AND status="1")';
            $where_check = array('email' => $email, 'status' => '1');
            $exisCheck_userlogin = $userModel->exisCheck_userlogin('userlogin', $where_check);

            if (!isset($exisCheck_userlogin) && empty($exisCheck_userlogin)) {
                $session->setFlashdata('error_status', '1');
                $session->setFlashdata('error_msg', 'Invalid Email Address');
                $user_type_val = 'employer';
                return redirect()->to('/forgot-password/' . $user_type_val);
            }
            $user_type = $exisCheck_userlogin->usertype;
        }
        // if (isset($email) && $user_type == '3') {
        //     $where = array('email' => $email,'usertype' => $user_type, 'status' => '1');
        // }
        if (isset($mobile) && $user_type == '1') {
            $where = array('mobile' => $mobile, 'usertype' => $user_type, 'status' => '1');
        }
        if (isset($email) && $user_type == '6') {
            $where = array('email' => $email, 'usertype' => $user_type, 'status' => '1');
        }

        //redirect when attempt failed


        $exisCheck_userlogin = $userModel->exisCheck_userlogin('userlogin', $where);
        // print_r($exisCheck_userlogin);exit;
        if ($exisCheck_userlogin) {
            $available_data = $userModel->table_row_common_userlogin('userlogin', $where);
            $otp_count = ($available_data->otp_count + 1);

            $data = ['otp' => $otp, 'otp_count' => $otp_count];
            $update_otp = $userModel->update_commen('userlogin', $where, $data);
            if ($update_otp) {
                $ses_data = [
                    'id' => $available_data->id,
                    'userid' => $available_data->userid,
                    'name' => $available_data->candidate_firstname,
                    'email' => $available_data->email,
                    'mobile' => $available_data->mobile,
                    'otp' => $otp,
                ];
                $session->set($ses_data);
                if ($user_type == '2' || $user_type == '3' || $user_type == '4') {
                    $ses_data1 = ['user_type_otp' => 2];
                }
                if ($user_type == '1') {
                    $ses_data1 = ['user_type_otp' => 1];
                }
                if ($user_type == '6') {
                    $ses_data1 = ['user_type_otp' => 6];
                }
                $session->set($ses_data1);
            }

            //check otp count
            $otp_count = $userModel->otp_count_check($this->request->getVar('user_mobile'), $this->request->getVar('user_email'), $user_type);

            //allow only 5 attempt
            if ($otp_count < 5) {
                $otp_count_new  = $otp_count + 1;

                $data = array(
                    'email_id'     => $this->request->getVar('user_email'),
                    'phone_number' => $this->request->getVar('user_mobile'),
                    'otp_count'    => $otp_count_new,
                    'otp_number'   => $otp,
                    'user_type'    => $user_type,
                );

                $userModel->otp_count_save($data);
            }
            if ($otp_count >= 5) {

                $session->setFlashdata('error_status', '1');
                $session->setFlashdata('error_msg', 'OTP Limit Exceeded, Please Try After 24 Hours');
                if ($user_type == '1') {
                    return redirect()->to('forgot-password/candidate');
                } elseif ($user_type == '2' || $user_type == '3' || $user_type == '4') {
                    return redirect()->to('forgot-password/employer');
                } elseif ($user_type == '6') {
                    return redirect()->to('forgot-password/faculty');
                }
            } else {
                if ($user_type == '1') {

                    //otp sms  
                    $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');
                    $this->sms_send($this->request->getVar('user_mobile'), $message);
                } else {
                    // gmail sent email otp
                    // $msg_data['msg_data']=array('otp'=>$otp,'name'=>$available_data->name);//dynamic contents for template
                    // $message     = view('email_template/forgot_password',$msg_data);
                    $current_year = date('Y');
                    $message = '{ "otp" : "' . $otp . '", "name" : "' . $available_data->name . '","year" : "' . $current_year . '" }'; //dynamic contents for template
                    $subject      = 'Internme - OTP Verification';
                    $to_email     =  $this->request->getVar('user_email');
                    $from_content = 'Internme - OTP Verification';
                    $template_key = '2d6f.456f260c51ab9602.k1.c45e4ff0-a779-11ed-bfa0-525400fcd3f1.1862fb5e26f';
                    $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                    // $this->email_send($message, $subject, $to_email, $from_content);
                }
            }
            return redirect()->to('/forgot-password-otp');
        } else {
            if ($user_type == '2') {
                $session->setFlashdata('error_status', '1');
                $session->setFlashdata('error_msg', 'Invalid Email Address');
                $user_type_val = 'employer';
            }
            if ($user_type == '6') {
                $session->setFlashdata('error_status', '1');
                $session->setFlashdata('error_msg', 'Invalid Email Address');
                $user_type_val = 'faculty';
            }
            if ($user_type == '1') {
                $session->setFlashdata('error_msg', 'Invalid Mobile Number');
                $user_type_val = 'candidate';
            }
            return redirect()->to('/forgot-password/' . $user_type_val);
        }
    }

    public function validate_user_otp($url_type)
    {
        // $url_type=(1=registration,2=forgot password);
        //echo $url_type;exit();
        $session = session();
        //    $user_type = $this->request->getVar('user_type');
        //    $mobile = $this->request->getVar('user_mobile');
        //    $email = $this->request->getVar('user_email');
        //    $otp = $this->request->getVar('user_otp');
        //   // $session_otp    =    $session->get('otp');
        //    $userModel = new LoginModel();
        //    if(isset($email) && $user_type=='2'){
        //        $where = array('email'=> $email,'otp'=> $otp,'status'=> '1');
        //    }
        //    if(isset($mobile) && $user_type=='1'){
        //        $where = array('mobile'=> $mobile,'otp'=> $otp,'status'=> '1');
        //    }
        // $exisCheck_userlogin = $userModel->exisCheck_userlogin('userlogin',$where);
        // if($exisCheck_userlogin || $session_otp==$otp)
        // {
        if ($url_type == 'register') {
            return redirect()->to('/set-password');
        } else {

            if ($url_type == 'employer') {
                $user_type = $this->request->getVar('user_type');
                $mobile    = $this->request->getVar('user_mobile');
                $email     = $this->request->getVar('user_email');
                $otp       = $this->request->getVar('user_otp');
                if (isset($email) && $user_type == '2') {
                    $where = array('email_id' => $email, 'otp_number' => $otp, 'user_type' => '2');
                } elseif (isset($mobile) && $user_type == '1') {
                    $where = array('phone_number' => $mobile, 'otp_number' => $otp, 'user_type' => '1');
                } elseif (isset($mobile) && $user_type == '6') {
                    $where = array('email_id' => $email, 'otp_number' => $otp, 'user_type' => '6');
                }
                $userModel = new LoginModel();
                $exisCheck_common = $userModel->exisCheck_common('user_otp', $where);
                if ($exisCheck_common) {
                    return redirect()->to('/reset-password');
                } else {
                    $session->setFlashdata('error_msg_otp', 'Enter Valid OTP');
                    return redirect()->to('/forgot-password-otp');
                }
            }
        }
        // }else{
        //      $session->setFlashdata('error_msg_otp', 'Enter Valid OTP');
        //      if($url_type=='register'){
        //          return redirect()->to('/register-otp');  
        //      }else{
        //          return redirect()->to('/forgot-password-otp');   
        //      }

        //  }

    }
    public function validate_user_otp_hr()
    {
        // $url_type=(1=registration,2=forgot password);
        //echo $url_type;exit();
        $session = session();

        return redirect()->to('/hr-set-password');
    }
    public function change_user_password($url_type)
    {
        //$url_type=(1=registration,2=forgot password)
        $session = session();
        $userModel = new LoginModel();
        $user_create_password = $this->request->getVar('user_create_password');
        $user_confirm_password = $this->request->getVar('user_confirm_password');
        $user_type = $this->request->getVar('user_type');
        $mobile = $this->request->getVar('user_mobile');
        $email = $this->request->getVar('user_email');
        $user_id = $this->request->getVar('user_id');
        $session_otp    =    $session->get('otp');
        $enc_password = hash("sha256", $user_create_password);
        $current_datetime = $userModel->current_datetime();
        $salt = $userModel->RandomString();
        $status = 1;
        // $enc_password = hash ( "sha256", $user_create_password );
        $enc_password = hash("sha256", $user_create_password . $salt);

        if ($user_confirm_password != '' && $user_create_password != '' && $user_create_password == $user_confirm_password) {

            if (isset($email) && $user_type == '2') {
                $where = array('email' => $email, 'id' => $user_id, 'status' => '1');
                //rest otp count
                //

                //$userModel->otp_count_reset($email,2);
            }
            if (isset($email) && $user_type == '6') {
                $where = array('email' => $email, 'id' => $user_id, 'status' => '1');
                if ($user_type == '6') {
                    $ses_data = [

                        'show_usertype' => '6',
                    ];
                    $session->set($ses_data);
                }
                //rest otp count
                //

                //$userModel->otp_count_reset($email,2);
            }
            if (isset($mobile) && $user_type == '1') {
                $where = array('mobile' => $mobile, 'id' => $user_id, 'status' => '1');

                //rest otp count
                //$userModel->otp_count_reset($mobile,1);
            }
            //$data = [ 'ref' => $user_create_password,'password' => $enc_password];
            $data = ['ref' => $user_create_password, 'salt_code' => $salt, 'password' => $enc_password];
            $update_password = '';
            if ($url_type == 'register') {
                $rand = rand(11, 99);
                $userid = $user_type . date('ymdhis') . $rand;
                if ((isset($mobile) && !empty($mobile) && isset($userid)) || (isset($email) && !empty($email) && isset($userid))) {
                    if ((isset($email) && !empty($email))) {
                        $domain = explode('@', $email);
                        $com_domain_name = $domain[1];
                    } else {
                        $com_domain_name = '';
                    }
                    if ($session->get('usertype') == '2') {
                        $company_id = $userid;
                        $status = 1; /// need to change status 0 for need corporate approval
                    } else {
                        $company_id = '';
                    }
                    $data1 = [
                        'userid' => $userid,
                        'company_id' => $company_id,
                        'usertype' => $session->get('usertype'),
                        'name' => $this->c_trim($session->get('candidate_firstname')) . $this->c_trim($session->get('candidate_lastname')),
                        'username' => $this->c_trim($session->get('username')),
                        'candidate_firstname' => $this->c_trim($session->get('candidate_firstname')),
                        'candidate_lastname' => $this->c_trim($session->get('candidate_lastname')),
                        'industry_name' => $this->c_trim($session->get('industry_name')),
                        'email' => $session->get('email'),
                        'mobile' => $session->get('mobile'),
                        'email_domain' => $com_domain_name,
                        'mobile_whatsapp' => '',
                        'otp' => $session_otp,
                        'ref' => $user_create_password,
                        'password' => $enc_password,
                        'created_at' => $current_datetime,
                        'salt_code' => $salt,
                        'status' => $status,
                    ];

                    $update_password = $userModel->insert_commen('userlogin', $data1);
                }
                if (isset($email) && !empty($email) && isset($userid) && $user_type == '2') {
                    $employe_data = [
                        'userid' => $userid,
                        'profile_company_name' => $this->c_trim($session->get('industry_name')),
                        'profile_name' => $this->c_trim($session->get('candidate_firstname')),
                        'profile_official_email' => $session->get('email'),
                        'official_email_verify_status' => 1,
                        'created_at' => $current_datetime
                    ];
                    if ($update_password) {
                        $employe_profile = $userModel->insert_commen('profile_completion_form', $employe_data);
                    }
                    $ses_data_1 = [
                        'id',
                        'userid',
                        'name',
                        'email',
                        'mobile',
                        'company_logo',
                        'company_name',
                        'intership_profile',
                        'intership_number',
                        'edit_profile',
                        'profile_page_view',
                        'profile_complete_status',
                        'next_but_status',
                        'emp_company_name',
                        'username',
                        'candidate_firstname',
                        'candidate_lastname',
                        'industry_name',
                        'mobile_whatsapp',
                        'otp',
                        'created_at',
                    ];

                    $session->remove($ses_data_1);
                }
                if (isset($mobile) && !empty($mobile) && isset($userid) &&  $user_type == '1') {
                    $candidate_data = [
                        'userid' => $userid,
                        'profile_full_name' => $this->c_trim($session->get('candidate_firstname')) . ' ' . $this->c_trim($session->get('candidate_lastname')),
                        'profile_first_name' => $this->c_trim($session->get('candidate_firstname')),
                        'profile_last_name' => $this->c_trim($session->get('candidate_lastname')),
                        'profile_phone_number' => $session->get('mobile'),
                        'mobile_verify_status' => 1,
                        'created_at' => $current_datetime
                    ];
                    if ($update_password) {
                        $candidate_profile = $userModel->insert_commen('can_personal_details', $candidate_data);
                    }
                    $ses_data_1 = [
                        'id',
                        'userid',
                        'name',
                        'email',
                        'mobile',
                        'company_logo',
                        'company_name',
                        'intership_profile',
                        'intership_number',
                        'edit_profile',
                        'profile_page_view',
                        'profile_complete_status',
                        'next_but_status',
                        'emp_company_name',
                        'username',
                        'candidate_firstname',
                        'candidate_lastname',
                        'industry_name',
                        'mobile_whatsapp',
                        'otp',
                        'created_at',
                    ];

                    $session->remove($ses_data_1);
                    // print_r($_SESSION);  exit();
                }

                if ($update_password) {
                    $where = array('id' => $update_password);
                    $available_data = $userModel->table_row_common_userlogin('userlogin', $where);

                    //echo $insert; exit();
                    $ses_data = [
                        'id' => $available_data->id,
                        'userid' => $available_data->userid,
                        'name' => $available_data->candidate_firstname,
                        'email' => $available_data->email,
                        'mobile' => $available_data->mobile,
                        'otp' => $available_data->otp,
                        'register_or_update' => 1,
                        'password_update_status' => 1,
                    ];
                    $session->set($ses_data);

                    if ($user_type == '1') {

                        //otp sms                          
                        //$message = rawurlencode('Congratulations '.$session->get('candidate_firstname').$session->get('candidate_lastname').'! You have been successfully registered with Internme.'); 
                        //$this->sms_send($this->request->getVar('user_mobile'),$message);

                    } else {
                        // sent email otp
                        // $msg_data['msg_data']=array('user_type'=>'employer','name'=>$available_data->candidate_firstname);//dynamic contents for template
                        // $message     = view('email_template/employer_registration_success',$msg_data); 
                        // $this->email_send($message, $subject, $to_mail, $from_sub);


                        $current_year = date('Y');
                        $message = '{"name" : "' . $available_data->candidate_firstname . '", "user_type" : "employer" ,"year" : "' . $current_year . '"  }'; //dynamic contents for template
                        // $subject  = 'Successful Registration on Internme - Verification and Approval Pending';
                        $subject  = 'Welcome to Internme - Your Account has been  confirmed!';
                        $to_email  = $session->get('email');
                        // $from_content = 'Successful Registration on Internme - Verification and Approval Pending';
                        $from_content = 'Welcome to Internme - Your Account has been  confirmed!';
                        // $template_key = '2d6f.456f260c51ab9602.k1.0ac65fc0-14ab-11ee-9272-5254004d4100.188fb5077bc';
                        $template_key = '2d6f.456f260c51ab9602.k1.a2f3c2d0-a780-11ed-bfa0-525400fcd3f1.1862fe2e77d';
                        $this->email_send($message, $subject, $to_email, $from_content, $template_key);

                        if(base_url()=='https://internme.app'){
                        ///internel mail start
                        $current_year = date('Y');
                        $message = '{"emp_name" : "' . $available_data->candidate_firstname . '","org_name" : "' . $available_data->industry_name . '","email_id" : "' . $available_data->email . '","year" : "' . $current_year . '"  }'; //dynamic contents for template

                        $subject  = 'Internme - New corporate -  ' . $available_data->industry_name . ' | ' . $available_data->email . '';
                        $to_email  = 'mk@in22labs.com';

                        $from_content = 'Internme - New corporate -  ' . $available_data->industry_name . ' | ' . $available_data->email . '';

                        $template_key = '2d6f.456f260c51ab9602.k1.cc2b2da0-4005-11ee-8f95-52540064429e.18a1771457a';

                        $this->email_send($message, $subject, $to_email, $from_content, $template_key);

                        $current_year = date('Y');
                        $message = '{"emp_name" : "' . $available_data->candidate_firstname . '","org_name" : "' . $available_data->industry_name . '","email_id" : "' . $available_data->email . '","year" : "' . $current_year . '"  }'; //dynamic contents for template

                        $subject  = 'Internme - New corporate -  ' . $available_data->industry_name . ' | ' . $available_data->email . '';
                        $to_email  = 'thanuj@launchpadllc.in';

                        $from_content = 'Internme - New corporate -  ' . $available_data->industry_name . ' | ' . $available_data->email . '';

                        $template_key = '2d6f.456f260c51ab9602.k1.cc2b2da0-4005-11ee-8f95-52540064429e.18a1771457a';

                        $this->email_send($message, $subject, $to_email, $from_content, $template_key);

                        ///internel mail end

                        }

                    }










                    // if($user_type=='2'){
                    //     $ses_data1 = ['user_type_otp' => 2];
                    // }
                    // if($user_type=='1'){
                    //     $ses_data1 = ['user_type_otp' => 1];
                    // }
                    // return redirect()->to('/set-password');
                    return redirect()->to('/succesfull-message');
                }
            } else {
                // if ((isset($mobile) && !empty($mobile) && isset($userid)) || (isset($email) && !empty($email) && isset($userid))) {
                $update_password = $userModel->update_commen('userlogin', $where, $data);
                // }
            }


            if ($update_password) {
                $ses_data = [
                    'register_or_update' => 2,
                    'password_update_status' => 1
                ];
                $session->set($ses_data);
                $ses_data_1 = [
                    'id',
                    'userid',
                    'name',
                    'email',
                    'mobile',
                    'company_logo',
                    'company_name',
                    'intership_profile',
                    'intership_number',
                    'edit_profile',
                    'profile_page_view',
                    'profile_complete_status',
                    'next_but_status',
                    'emp_company_name',
                    'username',
                    'candidate_firstname',
                    'candidate_lastname',
                    'industry_name',
                    'mobile_whatsapp',
                    'otp',
                    'created_at',
                ];

                $session->remove($ses_data_1);
                if ($url_type == 'register') {
                    return redirect()->to('/succesfull-message');
                    // return redirect()->to('/set-password');
                } else {
                    return redirect()->to('/succesfull-message');
                    // return redirect()->to('/reset-password');
                }
            } else {
                $session->setFlashdata('error_msg_password', 'Password Not Updated');
                if ($url_type == 'register') {
                    return redirect()->to('/set-password');
                } else {
                    return redirect()->to('/reset-password');
                }
            }
        } else {
            $session->setFlashdata('error_msg_password', 'Password Not Matching');
            if ($url_type == 'register') {
                return redirect()->to('/set-password');
            } else {
                return redirect()->to('/reset-password');
            }
        }
    }



    public function change_user_password_hr()
    {
        //$url_type=(1=registration,2=forgot password)
        $session = session();
        $userModel = new LoginModel();
        $user_create_password = $this->request->getVar('user_create_password');
        $user_confirm_password = $this->request->getVar('user_confirm_password');
        $user_type = $this->request->getVar('user_type');

        $mobile = $this->request->getVar('user_mobile');
        $user_id = $this->request->getVar('user_id');
        $enc_password = hash("sha256", $user_create_password);
        $current_datetime = $userModel->current_datetime();
        $salt = $userModel->RandomString();
        // $enc_password = hash ( "sha256", $user_create_password );
        $enc_password = hash("sha256", $user_create_password . $salt);

        if ($user_confirm_password != '' && $user_create_password != '' && $user_create_password == $user_confirm_password) {


            $where = array('userid' => $user_id);
            $data1 = [

                'ref' => $user_create_password,
                'password' => $enc_password,
                'mobile' => $session->get('mobile'),
                'salt_code' => $salt,
            ];

            $update_password1 = $userModel->update_commen('userlogin', $where, $data1);

            $data22 = [

                'emp_mobile' => $session->get('mobile'),

            ];

            $update_mobile = $userModel->update_commen('emp_manage_admins', $where, $data22);


            if ($update_password1) {
                $ses_data = [

                    'password_update_status' => 1,
                    'register_or_update' => 1,
                ];
                $session->set($ses_data);
                if ($user_type == '3') {
                    $user_name = 'Human Resource';
                }
                if ($user_type == '4') {
                    $user_name = 'Supervisor';
                }
                $where1 = array('userid' => $user_id);
                $user_data = $userModel->fetch_emp_manage_admins('emp_manage_admins', $where1);


                // $msg_data['msg_data']=array('user_type'=>$user_name,'name'=>$user_data->emp_name);//dynamic contents for template
                // $message     = view('email_template/employer_registration_success',$msg_data); 
                $current_year = date('Y');
                $message = '{"name" : "' . $user_data->emp_name . '", "user_type" : "' . $user_name . '" ,"year" : "' . $current_year . '"  }'; //dynamic contents for template
                $subject  = 'Welcome to Internme - Your Account has been  Confirmed!';
                $to_email  = $user_data->emp_official_email;
                $from_content = 'Welcome to Internme - Your Account has been  Confirmed!';
                $template_key = '2d6f.456f260c51ab9602.k1.a2f3c2d0-a780-11ed-bfa0-525400fcd3f1.1862fe2e77d';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);


                // return redirect()->to('/hr-set-password');succesfull_message
                return redirect()->to('/succesfull-message');
            }
        } else {
            $session->setFlashdata('error_msg_password', 'Password Not Matching');

            return redirect()->to('/hr-set-password');
        }
    }

    public function add_registration()
    {
        $session = session();
        // print_r($session);exit();
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $user_type = $this->request->getVar('user_type');
        $mobile = $this->request->getVar('user_mobile');
        $email = $this->request->getVar('user_email');
        $otp = mt_rand(100000, 999999);
        $input = '';
        if (isset($email) && $user_type == '2') {
            $where = array('email' => $email, 'usertype' => $user_type, 'status' => '1');
            $input = $this->validate([
                'user_email' => 'required|valid_email',
                'user_company_name' => 'required',
                'user_first_name' => 'required'
            ]);
            // $blocked_domains = $userModel->restricted_email_domains();
            // $list_of_blocked_domains=array();
            // foreach($blocked_domains as $domain) {
            //     $list_of_blocked_domains[] = $domain->domain_name;
            // }
            // $domain = substr(strrchr(strtolower($email),'@'), 1);

            // if (in_array($domain, $list_of_blocked_domains) ) {
            //     $session->setFlashdata('error_msg_register', 'Enter your Business Mail ID');
            //         return redirect()->to('/register/employer');
            // }

        }
        if (isset($mobile) && $user_type == '1') {
            $where = array('mobile' => $mobile, 'usertype' => $user_type, 'status' => '1');
            $input = $this->validate([
                // 'user_mobile_whatsapp' => 'required',
                'user_mobile' => 'required|numeric|max_length[10]',
                'user_first_name' => 'required',
                // 'user_last_name' => 'required'
            ]);
        }
        if (!$input) {
            echo view('login', [
                'validation' => $this->validator
            ]);
        } else {

            $exisCheck_userlogin = $userModel->exisCheck_userlogin('userlogin', $where);
            if (!$exisCheck_userlogin) {
                $rand = rand(11, 99);
                $userid = $user_type . date('ymdhis') . $rand;
                $ses_data = [
                    'userid' => $userid,
                    'usertype' => $this->request->getVar('user_type'),
                    'name' => $this->request->getVar('user_first_name'),
                    'username' => $this->request->getVar('user_first_name'),
                    'candidate_firstname' => $this->request->getVar('user_first_name'),
                    // 'candidate_lastname' => $this->request->getVar('user_last_name'),
                    'industry_name' => $this->request->getVar('user_company_name'),
                    'email' => $this->request->getVar('user_email'),
                    'mobile' => $this->request->getVar('user_mobile'),
                    'mobile_whatsapp' => '',
                    'otp' => $otp,
                    'created_at' => $current_datetime
                ];
                //save otp to table

                //check otp count
                $otp_count = $userModel->otp_count_check($this->request->getVar('user_mobile'), $this->request->getVar('user_email'), $user_type);
                //allow only 5 attempt
                if ($otp_count < 5) {
                    $otp_count_new  = $otp_count + 1;

                    $data = array(
                        'email_id'     => $this->request->getVar('user_email'),
                        'phone_number' => $this->request->getVar('user_mobile'),
                        'otp_count'    => $otp_count_new,
                        'otp_number'   => $otp,
                        'user_type'    => $user_type,
                    );

                    $userModel->otp_count_save($data);
                }
                //print_r($otp_count);exit();








                // $insert = $userModel->insert_commen('userlogin',$data);
                // if(isset($email) && $user_type=='2'){
                // $employe_data = [
                //     'userid' => $userid,
                //     'profile_company_name' => $this->request->getVar('user_company_name'),
                //     'profile_name' => $this->request->getVar('user_first_name'),
                //     'profile_official_email' => $this->request->getVar('user_email'),
                //     'created_at' => $current_datetime
                // ];
                // $employe_profile = $userModel->insert_commen('profile_completion_form',$employe_data);
                // }
                // if(isset($mobile) && $user_type=='1'){
                // $candidate_data = [
                //     'userid' => $userid,
                //     'profile_full_name' => $this->request->getVar('user_first_name').$this->request->getVar('user_last_name'),
                //     'profile_phone_number' => $this->request->getVar('user_mobile'),
                //     'created_at' => $current_datetime
                // ];
                // $candidate_profile = $userModel->insert_commen('can_personal_details',$candidate_data);
                // }

                // if($insert){
                //     $where = array('id'=> $insert,'status'=> '1');
                //     $available_data = $userModel->fetch_table_data('userlogin',$where);
                //     //echo $insert; exit();
                // $ses_data = [
                //     'id' => $available_data->id,
                //     'userid' => $available_data->userid,
                //     'name' => $available_data->candidate_firstname,
                //     'email' => $available_data->email,
                //     'mobile' => $available_data->mobile,
                //     'otp' => $available_data->otp,
                // ];

                //redirect when attempt failed
                if ($otp_count >= 5) {
                    session()->setFlashdata('error_msg_register', 'OTP Limit Exceeded, Please Try After 24 Hours', 1);
                    return redirect()->to('register/candidate');
                } else {
                    if ($user_type == '1') {

                        //otp sms                          
                        $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');
                        $this->sms_send($this->request->getVar('user_mobile'), $message);
                    } else {
                        //sent email otp
                        $current_year = date('Y');
                        // $msg_data['msg_data']=array('otp'=>$otp,'name'=>$this->request->getVar('user_first_name'),'email_status'=>1);//dynamic contents for template
                        // $message     = view('email_template/verification_of_email',$msg_data);

                        $message = '{ "otp" : "' . $otp . '", "name" : "' . $this->request->getVar('user_first_name') . '", "title" : "Verification of Email" ,"year" : "' . $current_year . '"}'; //dynamic contents for template
                        $subject  = 'Internme - OTP Verification';
                        $to_email  = $this->request->getVar('user_email');
                        $from_content = 'Internme - OTP Verification';
                        $template_key = '2d6f.456f260c51ab9602.k1.6b140830-a784-11ed-bfa0-525400fcd3f1.1862ffbb033';
                        $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                        // $this->email_send($message, $subject, $to_mail, $from_sub);
                    }

                    //print_r($response);exit();
                    $session->set($ses_data);
                    if ($user_type == '2') {
                        $ses_data1 = ['user_type_otp' => 2];
                    }
                    if ($user_type == '1') {
                        $ses_data1 = ['user_type_otp' => 1];
                    }
                    $session->set($ses_data1);
                    return redirect()->to('/register-otp');
                }

                // }

            } else {
                $session->setFlashdata('error_msg_register', 'User already Exists');
                if ($user_type == '2') {
                    return redirect()->to('/register/employer');
                }
                if ($user_type == '1') {
                    return redirect()->to('/register/candidate');
                }
            }
        }
    }

    function userlogout()
    {
        $session = session();
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $userdevicedata        =    $this->userdevicedata();

        if ($session->get('userid')) {
            $userlogout    =    $session->get('userid');
            //    date_default_timezone_set('Asia/Calcutta');
            //    $this->input->set_cookie('_gvml_0A1',time()-3600);

            $data = array('logged_out' => $current_datetime);
            $where = array('userid' => $userlogout);
            $ses_data = ['emp_usertype' => $session->get('usertype')];
            $session->set($ses_data);
            $logout = $userModel->update_commen("userlogin", $where, $data);
            if ($logout) {
                $ses_data = [
                    'id',
                    'userid',
                    'usertype',
                    'name',
                    'email',
                    'mobile',
                    'login_usertype',
                    'company_logo',
                    'company_name',
                    'intership_profile',
                    'intership_number',
                    'edit_profile',
                    'profile_page_view',
                    'profile_complete_status',
                    'next_but_status',
                    'emp_company_name',
                    'func_session_post_internship_folder',
                    'func_session_post_internship_folder_id',
                    'isLoggedIn',
                    'pricing_plan_redirect',
                    'gmetrix',
                ];
                $session->remove($ses_data);

                $postdata = array(
                    'userid'   => $userlogout,
                    'ipaddress'  => $this->getipaddress(),
                    'devicetype' => $this->detectDevice(),
                    'deviceid'   => '',
                    'deviceos'   => $userdevicedata['deviceos'],
                    'osversion'  => $userdevicedata['osversion'],
                    'browser'    => $userdevicedata['browser'],
                    'status'  => 1
                );

                $where_track = array(
                    'userid' => $userlogout,
                    'ipaddress'  => $this->getipaddress(),
                    'devicetype' => $this->detectDevice(),
                    'deviceos'   => $userdevicedata['deviceos'],
                    'osversion'  => $userdevicedata['osversion'],
                    'browser'    => $userdevicedata['browser'],
                    'status'  => 1
                );
                $data_track = array(
                    'logouttime' => $current_datetime,
                    'status' => 0,
                );

                $logout_track = $userModel->update_commen("userlogin_track", $where_track, $data_track);

                if ($session->get('emp_usertype') == 2 || $session->get('emp_usertype') == 3 || $session->get('emp_usertype') == 4) {
                    return redirect()->to('/main_login/' . $session->get('emp_usertype'));
                } elseif ($session->get('emp_usertype') == 5) {
                    return redirect()->to('/adminlogin');
                } elseif ($session->get('emp_usertype') == 6) {
                    return redirect()->to('/facultylogin');
                } else {
                    return redirect()->to('/login');
                }
            }
        } else {
            return redirect()->to('/login');
        }
    }


    public function get_bussinuss_email_check()
    {
        $session = session();
        $email = $this->request->getVar('email');
        //  echo $email;
        $domain1 = explode('@', $email);
        $userModel = new LoginModel();
        $blocked_domains = $userModel->restricted_email_domains();
        $list_of_blocked_domains = array();
        if (!empty($blocked_domains)) {
            foreach ($blocked_domains as $domain) {
                $list_of_blocked_domains[] = $domain->domain_name;
            }
        }
        $domain = substr(strrchr(strtolower($email), '@'), 1);

        if (in_array($domain, $list_of_blocked_domains)) {

            echo '1' . '^' . csrf_hash();
        } else {
            $where = array('email_domain' => $domain1[1], 'usertype' => '2');
            $user_domain = $userModel->table_row_common_userlogin('userlogin', $where);

            // print_r($user_domain2);
            if (!empty($user_domain)) {
                $where2 = array('company_id' => $user_domain->company_id, 'usertype' => '2');
                $user_domain2 = $userModel->table_row_common_userlogin('userlogin', $where2);
                echo '0' . '^'  . csrf_hash() . '^' . $user_domain2->name . '^' . $user_domain2->email;
            } else {
                echo '0' . '^'  . csrf_hash() . '^' . '0' . '^' . '0';
            }
        }
    }

    //resend otp
    function resend_otp($number)
    {
        $userModel = new LoginModel();
        //generate otp
        $otp = mt_rand(100000, 999999);
        //check number exist
        if ($number != 0 || $number != '') {
            if (is_numeric($number)) {

                //check otp count
                $otp_count = $userModel->otp_count_check_number($number);
                //allow only 5 attempt
                if ($otp_count < 5) {
                    $otp_count_new  = $otp_count + 1;
                    //save otp to table
                    $data = array(
                        'phone_number' => $number,
                        'otp_count'    => $otp_count_new,
                        'otp_number'   => $otp,
                        'user_type'    => 1
                    );

                    $userModel->otp_count_save($data);


                    //otp sms 
                    $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');
                    $this->sms_send($number, $message);

                    session()->setFlashdata('error_status', '2');
                    session()->setFlashdata('error_msg_register', 'OTP Send successfully');
                    return redirect()->to('register-otp');
                } else {
                    session()->setFlashdata('error_msg_register', 'OTP Limit Exceeded, Please Try After 24 Hours', 1);
                    return redirect()->to('register/candidate');
                }
            } else {
                //check otp count
                $otp_count = $userModel->otp_count_check_email($number);
                //allow only 5 attempt
                if ($otp_count < 5) {
                    $otp_count_new  = $otp_count + 1;
                    //save otp to table
                    $data = array(
                        'email_id'     => $number,
                        'otp_count'    => $otp_count_new,
                        'otp_number'   => $otp,
                        'user_type'    => 2
                    );

                    $userModel->otp_count_save($data);

                    //sent email otp
                    if (isset($_SESSION['name']) && $_SESSION['name'] != '') {
                        $name = $_SESSION['name'];
                    } else {
                        $name = 'Sir';
                    }
                    $current_year = date('Y');
                    // $msg_data['msg_data']=array('otp'=>$otp,'name'=>$name);//dynamic contents for template
                    // $message     = view('email_template/verification_of_email',$msg_data);
                    $message = '{ "otp" : "' . $otp . '", "name" : "' . $name . '", "title" : "OTP Verification" ,"year" : "' . $current_year . '"}'; //dynamic contents for template
                    $subject  = 'Internme - OTP Verification';
                    $to_email  = $number;
                    $from_content = 'Internme - OTP Verification';
                    $template_key = '2d6f.456f260c51ab9602.k1.31740a80-a789-11ed-bfa0-525400fcd3f1.186301afb28';
                    $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                    // $this->email_send($message, $subject, $to_mail, $from_sub);




                    // $message = 'Sir,<br>&nbsp;&nbsp;&nbsp;&nbsp; Your OTP for Email Id verification is: '.$otp.' - InternMe Team.';

                    // $subject = 'Internme';

                    // $email = \Config\Services::email(); 
                    // $email->setTo($number);
                    // $email->setFrom('internme.app@gmail.com', 'NoReply', 'Internme - OTP Verification');                            
                    // $email->setSubject($subject);
                    // $email->setMessage($message);
                    // $email->send();

                    session()->setFlashdata('error_status', '2');
                    session()->setFlashdata('error_msg_register', 'OTP Send successfully');
                    return redirect()->to('register-otp');
                } else {
                    session()->setFlashdata('error_msg_register', 'OTP Limit Exceeded, Please Try After 24 Hours', 1);
                    return redirect()->to('register/candidate');
                }
            }
        } else {
            session()->setFlashdata('error_status', '1');
            session()->setFlashdata('error_msg', 'Phone Number not valid');
            return redirect()->to('register-otp');
        }
    }
    //hr and supervisor resend otp
    function hr_supervisor_resend_otp($number)
    {
        $userModel = new LoginModel();
        //generate otp
        $otp = mt_rand(100000, 999999);
        $session = session();
        $login_usertype = $session->get('user_type_otp');
        //check number exist
        if ($number != 0 || $number != '') {
            if (is_numeric($number)) {

                //check otp count
                $otp_count = $userModel->otp_count_check_number_hr($number, $login_usertype);
                //allow only 5 attempt
                //   echo  $otp_count ;exit;
                if ($otp_count < 5) {
                    $otp_count_new  = $otp_count + 1;
                    //save otp to table
                    $data = array(
                        'phone_number' => $number,
                        'otp_count'    => $otp_count_new,
                        'otp_number'   => $otp,
                        'user_type'    => $login_usertype,
                    );

                    $userModel->otp_count_save($data);


                    //otp sms 
                    $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');
                    $sendotp = $this->sms_send($number, $message);

                    session()->setFlashdata('error_status', '2');
                    session()->setFlashdata('error_msg_register', 'OTP Send successfully');
                    return redirect()->to('hr-register-otp');
                } else {
                    session()->setFlashdata('error_msg_register', 'OTP Limit Exceeded, Please Try After 24 Hours', 1);
                    return redirect()->to('register/candidate');
                }
            }
        } else {
            session()->setFlashdata('error_status', '1');
            session()->setFlashdata('error_msg', 'Phone Number not valid');
            return redirect()->to('hr-register-otp');
        }
    }
    //check user otp 
    function check_user_otp()
    {
        $userModel = new LoginModel();
        extract($_REQUEST);
        //check with user otp
        $otp_count = $userModel->check_with_user_otp($user_otp, $phone_number, $usertype);
        $all_data  = array('otp_status' => $otp_count, 'csrf' => csrf_hash());
        // print_r($all_data);
        echo json_encode($all_data);
    }

    //resend otp
    function forgot_send_otp()
    {
        extract($_REQUEST);
        $userModel = new LoginModel();
        //generate otp
        $otp = mt_rand(100000, 999999);
        //check number exist

        if ($user_mobile != '' && is_numeric($user_mobile)) {

            //check otp count
            $otp_count = $userModel->otp_count_check_number($user_mobile);
            //allow only 5 attempt
            if ($otp_count < 5) {
                $otp_count_new  = $otp_count + 1;
                //save otp to table
                $data = array(
                    'phone_number' => $user_mobile,
                    'otp_count'    => $otp_count_new,
                    'otp_number'   => $otp,
                    'user_type'    => 1
                );

                $userModel->otp_count_save($data);

                //otp sms 
                $message = rawurlencode('Dear User, Use the following OTP to reset your password: ' . $otp . ' - InternMe Team.');
                $this->sms_send($user_mobile, $message);

                $msg =  'OTP Send successfully';
                $status = 1;
            } else {
                $msg = 'OTP Limit Exceeded, Please Try After 24 Hours';
                $status = 2;
            }
        } else {
            $where = array('email' => $user_email, 'status' => '1');
            $available_data = $userModel->exisCheck_userlogin('userlogin', $where);
            //check otp count
            $otp_count = $userModel->otp_count_check_hr_supervisor($user_email, $available_data->usertype);
            //allow only 5 attempt
            if ($otp_count < 5) {
                $otp_count_new  = $otp_count + 1;
                //save otp to table
                $data = array(
                    'email_id'     => $user_email,
                    'otp_count'    => $otp_count_new,
                    'otp_number'   => $otp,
                    'user_type'    => $available_data->usertype
                );

                $userModel->otp_count_save($data);
                $current_year = date('Y');
                // $msg_data['msg_data']=array('otp'=>$otp,'name'=>$available_data->name);//dynamic contents for template
                // $message     = view('email_template/forgot_password',$msg_data);
                $message = '{ "otp" : "' . $otp . '", "name" : "' . $available_data->name . '","year" : "' . $current_year . '" }'; //dynamic contents for template
                $subject      = 'Internme - OTP Verification';
                $to_email     =  $user_email;
                $from_content = 'Internme - OTP Verification';
                $template_key = '2d6f.456f260c51ab9602.k1.c45e4ff0-a779-11ed-bfa0-525400fcd3f1.1862fb5e26f';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                // $this->email_send($message, $subject, $to_mail, $from_sub);
                $msg = 'OTP Send successfully';
                $status = 1;
            } else {
                $msg = 'OTP Limit Exceeded, Please Try After 24 Hours';
                $status = 2;
            }
        }

        echo $status . '^' . $msg . '^' . csrf_hash();
    }


    public function hr_register($hr_usertype, $hr_id)
    {
        $userModel = new LoginModel();
        $session = session();
        $ses_data = [
            'login_usertype' => $hr_usertype
        ];
        $where = array('userid' => $hr_id);
        $data['user_data'] = $userModel->fetch_emp_manage_admins('emp_manage_admins', $where);
        $user_data_login = $userModel->exisCheck_userlogin('userlogin', $where);
        // print_r($data['user_data']);exit;
        if (empty($user_data_login->password)) {
            return view('Auth/hr_register', $data);
        } else {
            return view('design/link_expired');
        }
    }
    public function hr_register_otp()
    {

        return view('Auth/hr_register_otp');
    }
    public function hr_set_password()
    {
        return view('Auth/hr_set_password');
    }
    public function add_registration_hr()
    {
        $session = session();
        // print_r($session);exit();
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $user_type = $this->request->getVar('user_type');
        $email = $this->request->getVar('user_email');
        $mobile = $this->request->getVar('user_mobile');
        $user_id = $this->request->getVar('user_id');

        $otp = mt_rand(100000, 999999);
        $input = '';

        $where = array('mobile' => $mobile, 'status' => '1');
        $input = $this->validate([
            'user_email' => 'required|valid_email',
            // 'user_company_name' => 'required',
            'user_first_name' => 'required'
        ]);

        if (!$input) {
            echo view('login', [
                'validation' => $this->validator
            ]);
        } else {

            $exisCheck_userlogin = $userModel->exisCheck1('userlogin', $where);
            if (!$exisCheck_userlogin) {

                //check otp count
                $otp_count = $userModel->otp_count_check($this->request->getVar('user_mobile'), '', $user_type);
                //allow only 5 attempt
                if ($otp_count < 5) {
                    $otp_count_new  = $otp_count + 1;

                    $data = array(
                        'email_id'     => '',
                        'phone_number' => $this->request->getVar('user_mobile'),
                        'otp_count'    => $otp_count_new,
                        'otp_number'   => $otp,
                        'user_type'    => $user_type,
                    );

                    $userModel->otp_count_save($data);
                }

                //redirect when attempt failed
                if ($otp_count >= 5) {
                    session()->setFlashdata('error_msg_register', 'OTP Limit Exceeded, Please Try After 24 Hours', 1);
                    return redirect()->to('hr-register-otp');
                } else {

                    $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');
                    $this->sms_send($this->request->getVar('user_mobile'), $message);



                    if ($user_type == '3') {
                        $ses_data1 = ['user_type_otp' => 3, 'mobile' => $mobile, 'id' => $user_id];
                    }
                    if ($user_type == '4') {
                        $ses_data1 = ['user_type_otp' => 4, 'mobile' => $mobile, 'id' => $user_id];
                    }
                    $session->set($ses_data1);
                    return redirect()->to('/hr-register-otp');
                }

                // }

            } else {
                $session->setFlashdata('error_msg_register', 'User already Exists');
                if ($user_type == '3') {
                    return redirect()->to('/hr-register/' . $user_type . '/' . $user_id);
                }
                if ($user_type == '4') {
                    return redirect()->to('/hr-register/' . $user_type . '/' . $user_id);
                }
            }
        }
    }

    //common function for send email - Gmail
    // function email_send($message, $subject, $to_email, $from_content)
    // {
    //     $email = \Config\Services::email();
    //     $email->setTo($to_email);
    //     $email->setFrom('internme.app@gmail.com', 'NoReply', $from_content);
    //     $email->setSubject($subject);
    //     $email->setMessage($message);
    //     if ($email->send()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
    // common function for send email - Zoho
    function email_send($message, $subject, $to_email, $from_content, $template_key)
    {
        // echo $message;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
         "template_key": "' . $template_key . '",
        "bounce_address":"donotreply@notification.internme.app",
        "from": { "address": "noreply@internme.app"},
        "to": [{"email_address": {"address": "' . $to_email . '","name": "' . $from_content . '"}}],
        "subject":"' . $subject . '",
        "merge_info": ' . $message . ' }',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR61/80GiCqgslDWkLrs+mV0GUlzxEU0sjgem6SD6H6/F98c9wkPOU1KiGvcZE2FvEDoaobl6zk8I12EO2YgqzFEFDiiF9mqRe1U4J3x17qnvhDzPV25dlBONKYkAwwhtnmJlEcsk+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // echo "cURL Error #:" . $err;
            return false;
        } else {
            // echo $response;
            return true;
        }
    }
    //common function for send sms
    function sms_send($number, $message)
    {
        $apiKey = urlencode('EM5b4IXHyls-hDU0Ja8rBGILMJ7RKiJEtcXakV8kke');
        $sender = urlencode('INTNME');

        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $number, "sender" => $sender, "message" => $message);

        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return true;
    }


    function c_trim($var)
    {
        $var = ltrim($var);
        $var = rtrim($var);
        return $var;
    }
    function succesfull_message()
    {
        $session = session();
        $ses_data_1 = [
            'id',
            'userid',
            'name',
            'email',
            'mobile',
            'company_logo',
            'company_name',
            'intership_profile',
            'intership_number',
            'edit_profile',
            'profile_page_view',
            'profile_complete_status',
            'next_but_status',
            'emp_company_name',
            'username',
            'candidate_firstname',
            'candidate_lastname',
            'industry_name',
            'mobile_whatsapp',
            'otp',
            'created_at',
            'password_update_status',
            'isLoggedIn',
        ];

        $session->remove($ses_data_1);
        return view('Auth/succesfull_message');
    }



    public function admin_loginAuth()
    {

        $session = session();
        $userModel = new LoginModel();
        if ($this->request->getMethod() == 'post') {

            $user_type = $this->request->getVar('user_type');

            $email = $this->request->getVar('user_email');
            $password = $this->request->getVar('user_password');



            if (isset($email) && $user_type == '5') {
                $input = $this->validate([
                    'user_email' => 'required|valid_email',
                    'user_password' => 'required'
                ]);
                $ses_data = [
                    'login_usertype' => 'employer'
                ];
            }

            $session->set($ses_data);
            if (!$input) {
                echo view('login', [
                    'validation' => $this->validator
                ]);
            } else {
                $data = $userModel->userlogin_loginAuth($email, '', $user_type);
                if ($data) {
                    $salt = $data->salt_code;
                    $pass = $data->password;
                    $enc_password = hash("sha256", $password . $salt);
                    if (empty($data->candidate_firstname)) {
                        $user_name = $data->name;
                    } else {
                        $user_name = $data->candidate_firstname;
                    }
                    //$pass = $data->ref;
                    if ($enc_password == $pass) {
                        $current_datetime = $userModel->current_datetime();
                        $data_log = array('logged_in' => $current_datetime);
                        $where = array('userid' => $data->userid);
                        $logout = $userModel->update_commen("userlogin", $where, $data_log);
                        $ses_data = [
                            'id' => $data->id,
                            'userid' => $data->userid,
                            'usertype' => $data->usertype,
                            'name' => $user_name,
                            'emp_company_name' => $data->industry_name,
                            'email' => $data->email,
                            'mobile' => $data->mobile,
                            // 'candidate_email' => $available_data->profile_email,
                            // 'candidate_education_status' => $candidate_education_status,
                            'isLoggedIn' => TRUE,
                        ];

                        $session->set($ses_data);
                        if ($data->usertype == '5' && $user_type == '5') {
                            return redirect()->to('/admin-dashboard');
                        }
                    } else {
                        $session->setFlashdata('error_msg', 'Password is incorrect.');
                        return redirect()->to('/adminlogin');
                    }
                } else {

                    $session->setFlashdata('error_msg', 'Email ID does not exists.');

                    return redirect()->to('/adminlogin');
                }
            }
        }
    }


    // Faculty Login

    public function faculty_login()
    {
        helper(['form']);
        $session = session();
        $logged_in = $session->get('isLoggedIn');
        if ($logged_in) {
            $user_type    =    $session->get('usertype');
            $userid    =    $session->get('userid');

            $userModel = new LoginModel();
            if ($user_type == '6') {
                return redirect()->to('/faculty-dashboard');
            }
        } else {
            return view('Auth/faculty_login');
        }
    }

    public function faculty_loginAuth()
    {

        $session = session();
        $userModel = new LoginModel();
        $logsessionid        =    NULL;
        $userdevicedata        =    $this->userdevicedata();
        $logsessionid        =    $session->get('__ci_last_regenerate');


        if ($this->request->getMethod() == 'post') {

            $user_type = $this->request->getVar('user_type');
            $email = $this->request->getVar('user_email');
            $password = $this->request->getVar('user_password');

            if (isset($email) && $user_type == '6') {
                $input = $this->validate([
                    'user_email' => 'required|valid_email',
                    'user_password' => 'required'
                ]);
                $ses_data = [
                    'login_usertype' => 'employer'
                ];
            }

            $session->set($ses_data);
            if (!$input) {
                echo view('login', [
                    'validation' => $this->validator
                ]);
            } else {
                $data = $userModel->userlogin($email, '', $user_type);
                if ($data) {
                    $salt = $data->salt_code;
                    $pass = $data->password;
                    $enc_password = hash("sha256", $password . $salt);
                    $user_name = $data->name;
                    //$pass = $data->ref;
                    if ($enc_password == $pass) {
                        $current_datetime = $userModel->current_datetime();
                        $data_log = array('logged_in' => $current_datetime);
                        $where = array('userid' => $data->userid);
                        $logout = $userModel->update_commen("userlogin", $where, $data_log);

                        $postdata = array(
                            'userid'   => $data->userid,
                            'ipaddress'  => $this->getipaddress(),
                            'devicetype' => $this->detectDevice(),
                            'deviceid'   => '',
                            'deviceos'   => $userdevicedata['deviceos'],
                            'osversion'  => $userdevicedata['osversion'],
                            'browser'    => $userdevicedata['browser'],
                            'logintime'  => date('Y-m-d H:i:s'),
                            'status'  => 1
                        );
                        $result = $userModel->insert_commen('userlogin_track', $postdata);

                        $ses_data = [
                            'id' => $data->id,
                            'userid' => $data->userid,
                            'usertype' => $data->usertype,
                            'name' => $user_name,
                            'emp_company_name' => $data->industry_name,
                            'email' => $data->email,
                            'mobile' => $data->mobile,
                            // 'candidate_email' => $available_data->profile_email,
                            // 'candidate_education_status' => $candidate_education_status,
                            'isLoggedIn' => TRUE,
                        ];

                        $session->set($ses_data);
                        if ($data->usertype == '6' && $user_type == '6') {
                            return redirect()->to('/faculty-dashboard');
                        }
                    } else {
                        $session->setFlashdata('error_msg', 'Password is incorrect.');
                        $session->setFlashdata('temp_email', $email);
                        return redirect()->to('/facultylogin');
                    }
                } else {

                    $session->setFlashdata('error_msg', 'Email ID does not exists.');
                    $session->setFlashdata('temp_email', $email);

                    return redirect()->to('/facultylogin');
                }
            }
        }
    }

    public function faculty_register($faculty_id)
    {
        $userModel = new LoginModel();
        // $session = session();
        // $ses_data = [
        //     'login_usertype' => $hr_usertype
        // ];
        $where = array('userid' => $faculty_id);
        $data['user_data'] = $userModel->fetch_table_data('faculty_reg_data', $where);
        $user_data_login = $userModel->fetch_table_data('userlogin', $where);
        // print_r($data['user_data']);exit;
        if (empty($user_data_login->password)) {
            return view('Auth/faculty_register', $data);
        } else {
            return view('design/link_expired');
        }
    }
    public function faculty_register_otp()
    {

        return view('Auth/faculty_register_otp');
    }
    public function faculty_set_password()
    {
        return view('Auth/faculty_set_password');
    }

    public function add_registration_faculty()
    {
        $session = session();
        // print_r($session);exit();
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $user_type = $this->request->getVar('user_type');
        $email = $this->request->getVar('user_email');
        $mobile = $this->request->getVar('user_mobile');
        $user_id = $this->request->getVar('user_id');

        $otp = mt_rand(100000, 999999);
        $input = '';

        $where = array('mobile' => $mobile, 'usertype' => $user_type, 'status' => '1');
        $input = $this->validate([
            'user_email' => 'required|valid_email',
            // 'user_company_name' => 'required',
            'user_first_name' => 'required'
        ]);

        if (!$input) {
            echo view('login', [
                'validation' => $this->validator
            ]);
        } else {

            $exisCheck = $userModel->exisCheck('userlogin', $where);
            if (!$exisCheck) {

                //check otp count
                $otp_count = $userModel->otp_count_check($this->request->getVar('user_mobile'), '', $user_type);
                //allow only 5 attempt
                if ($otp_count < 5) {
                    $otp_count_new  = $otp_count + 1;

                    $data = array(
                        'email_id'     => '',
                        'phone_number' => $this->request->getVar('user_mobile'),
                        'otp_count'    => $otp_count_new,
                        'otp_number'   => $otp,
                        'user_type'    => $user_type,
                    );

                    $userModel->otp_count_save($data);
                }

                //redirect when attempt failed
                if ($otp_count >= 5) {
                    session()->setFlashdata('error_msg_register', 'OTP Limit Exceeded, Please Try After 24 Hours', 1);
                    return redirect()->to('faculty-register-otp');
                } else {

                    $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');
                    $this->sms_send($this->request->getVar('user_mobile'), $message);

                    $ses_data1 = ['user_type_otp' => 6, 'mobile' => $mobile, 'id' => $user_id];

                    $session->set($ses_data1);
                    return redirect()->to('/faculty-register-otp');
                }

                // }

            } else {
                $session->setFlashdata('error_msg_register', 'User already Exists');

                return redirect()->to('/faculty-register/' . $user_id);
            }
        }
    }

    public function validate_user_otp_faculty()
    {
        // $url_type=(1=registration,2=forgot password);
        //echo $url_type;exit();
        $session = session();

        return redirect()->to('/faculty-set-password');
    }

    public function change_user_password_faculty()
    {
        //$url_type=(1=registration,2=forgot password)
        $session = session();
        $userModel = new LoginModel();
        $user_create_password = $this->request->getVar('user_create_password');
        $user_confirm_password = $this->request->getVar('user_confirm_password');
        $user_type = $this->request->getVar('user_type');

        $mobile = $this->request->getVar('user_mobile');
        $user_id = $this->request->getVar('user_id');
        $enc_password = hash("sha256", $user_create_password);
        $current_datetime = $userModel->current_datetime();
        $salt = $userModel->RandomString();
        // $enc_password = hash ( "sha256", $user_create_password );
        $enc_password = hash("sha256", $user_create_password . $salt);

        if ($user_confirm_password != '' && $user_create_password != '' && $user_create_password == $user_confirm_password) {


            $where = array('userid' => $user_id);
            $data1 = [

                'ref' => $user_create_password,
                'password' => $enc_password,
                'mobile' => $mobile,
                'salt_code' => $salt,
            ];

            $update_password1 = $userModel->update_commen('userlogin', $where, $data1);

            $data22 = [

                'faculty_mobile' => $mobile,

            ];

            $update_mobile = $userModel->update_commen('faculty_reg_data', $where, $data22);


            if ($update_password1) {
                $ses_data = [

                    'password_update_status' => 1,
                    'register_or_update' => 1,
                    'show_usertype' => 6,
                ];
                $session->set($ses_data);

                //     $user_name = 'Faculty';

                // $where1 = array('userid' => $user_id);
                // $user_data = $userModel->fetch_table_data('faculty_reg_data', $where1);


                // // $msg_data['msg_data']=array('user_type'=>$user_name,'name'=>$user_data->emp_name);//dynamic contents for template
                // // $message     = view('email_template/employer_registration_success',$msg_data); 
                //     $current_year=date('Y');
                //     $message = '{"name" : "'.$user_data->faculty_name .'", "user_type" : "'.$user_name.'" ,"year" : "'.$current_year.'"  }'; //dynamic contents for template
                //     $subject  = 'Internme - Registration';
                //     $to_email  = $user_data->faculty_email ;
                //     $from_content = 'Internme - Registration';
                //     $template_key = '2d6f.456f260c51ab9602.k1.a2f3c2d0-a780-11ed-bfa0-525400fcd3f1.1862fe2e77d';
                //     $this->email_send($message, $subject, $to_email, $from_content, $template_key);


                // // return redirect()->to('/hr-set-password');succesfull_message
                return redirect()->to('/succesfull-message');
            }
        } else {
            $session->setFlashdata('error_msg_password', 'Password Not Matching');

            return redirect()->to('/faculty-set-password');
        }
    }

    //hr and supervisor resend otp
    function faculty_supervisor_resend_otp($number)
    {
        $userModel = new LoginModel();
        //generate otp
        $otp = mt_rand(100000, 999999);
        $session = session();
        $login_usertype = $session->get('user_type_otp');
        //check number exist
        if ($number != 0 || $number != '') {
            if (is_numeric($number)) {

                //check otp count
                $otp_count = $userModel->otp_count_check_number_faculty($number, $login_usertype);
                //allow only 5 attempt
                //   echo  $otp_count ;exit;
                if ($otp_count < 5) {
                    $otp_count_new  = $otp_count + 1;
                    //save otp to table
                    $data = array(
                        'phone_number' => $number,
                        'otp_count'    => $otp_count_new,
                        'otp_number'   => $otp,
                        'user_type'    => $login_usertype,
                    );

                    $userModel->otp_count_save($data);


                    //otp sms 
                    $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');
                    $sendotp = $this->sms_send($number, $message);

                    session()->setFlashdata('error_status', '2');
                    session()->setFlashdata('error_msg_register', 'OTP Send successfully');
                    return redirect()->to('faculty-register-otp');
                } else {
                    session()->setFlashdata('error_msg_register', 'OTP Limit Exceeded, Please Try After 24 Hours', 1);
                    return redirect()->to('faculty-register-otp');
                }
            }
        } else {
            session()->setFlashdata('error_status', '1');
            session()->setFlashdata('error_msg', 'Phone Number not valid');
            return redirect()->to('faculty-register-otp');
        }
    }
}
