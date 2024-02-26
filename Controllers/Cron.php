<?php

namespace App\Controllers;

use App\Models\Employer_model;

use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Supervisor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$this->Employer_model = new Employer_model();


class Cron extends BaseController
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Kolkata');
     
    }

    public function send_interview_email()
    {
        $session = session();
        $Employer_model = new Employer_model();
        //sent email  
        $current_datetime = $Employer_model->current_datetime();
        $date = date('Y-m-d');
        $time = date('H:i:00');
        $endTime = strtotime("+30 minutes", strtotime($time));
        $endTime1 = date('H:i:00', $endTime);
        $usertype = $session->get('usertype');

        $where = array('type' => '3', 'date(chat.interview_date)' => $date, 'chat.interview_time' => $endTime1);
        $data['interview_candidate'] = $Employer_model->fetch_table_data_for_all('chat', $where);
        $interview_candidate = $data['interview_candidate'];

         

        
        // print_r($interview_candidate);
        //  exit;

        if (!empty($interview_candidate)) {
            foreach ($interview_candidate as $key) {

                $where = array('userid' => $key->receiver_id);
                $candidate_name = $Employer_model->fetch_table_row('can_personal_details', $where);
                // $where = array('userid' => $key->sender_id);
                // $company_name = $Employer_model->fetch_table_row('profile_completion_form', $where);
                $userid    =    $key->sender_id;
                            if ($usertype == 3 || $usertype == 4) {
                                $where_sub = array('userid' => $userid, 'status' => '1');
                                $sub_admin_data = $Employer_model->fetch_table_row('emp_manage_admins', $where_sub);
                                $where_com = array('userid' => $sub_admin_data->emp_user_id, 'status' => '1');
                                $Company_data = $Employer_model->fetch_table_row('profile_completion_form', $where_com);

                                $emp_company_name    = $Company_data->profile_company_name;
                                // $industry_name='';
                            } else {
                                $where_com = array('userid' => $userid, 'status' => '1');
                                $Company_data = $Employer_model->fetch_table_row('profile_completion_form', $where_com);
                                // $emp_company_name    =    $session->get('emp_company_name');
                                $emp_company_name    = $Company_data->profile_company_name;
                            }
                // $internship_details = $Employer_model->fetch_table_row('employer_post_internship', $where);
                // if (isset($internship_details->profile) && $internship_details->profile != '0') {
                //     $profile = $Employer_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                // } else {
                //     $profile =  $internship_details->other_profile;
                // }
                if ($key->interview_duration == '15') {
                    $endTime = strtotime("+15 minutes", strtotime($key->interview_time));
                    $end_time1 = date('h:i a', $endTime);
                } elseif ($key->interview_duration == '30') {
                    $endTime = strtotime("+30 minutes", strtotime($key->interview_time));
                    $end_time1 = date('h:i a', $endTime);
                } elseif ($key->interview_duration == '60') {
                    $endTime = strtotime("+60 minutes", strtotime($key->interview_time));
                    $end_time1 = date('h:i a', $endTime);
                }
                $start_time = strtotime("+0 minutes", strtotime($key->interview_time));
                $int_start_time = date('h:i a', $start_time);
                $interview_slot = $int_start_time . ' - ' . $end_time1;

                 if($key->interview_mode==1){
                    $interview_mode_type= 'Video Call';
                    $video_content='Join by video link :';
                    $link=$key->link;
                 }elseif($key->interview_mode==2){
                    $interview_mode_type= 'Phone';
                    $video_content='';
                    $link='';
                 }else{
                    $interview_mode_type= 'In-office';
                    $video_content='';
                    $link='';
                 }
                 
                 $interview_date=date("d-m-Y", strtotime($key->interview_date));
                 $current_year=date('Y');
                 
                // $msg_data['msg_data'] = array('name' => $candidate_name->profile_full_name, 'company_name' => $emp_company_name, 'interview_date' => $key->interview_date, 'interview_time' => $interview_slot, 'title' => $key->title, 'link' => $key->link,'interview_mode'=>$interview_mode_type); //dynamic contents for template
                // $message = view('email_template/interview_email', $msg_data);

                $message = '{ "company_name" : "'.$emp_company_name.'", "name" : "'.$candidate_name->profile_full_name.'", "interview_date" : "'.$interview_date.'" , "interview_time" : "'.$interview_slot.'" , "title" : "'.$key->title.'" , "link" : "'.$link.'" , "interview_mode" : "'.$interview_mode_type.'" , "video_content" : "'.$video_content.'","year" : '.$current_year.'}';

                $subject = 'Internme - Internship';
                $to_email = $candidate_name->profile_email;
                $from_content = 'Internme - Internship';
                $template_key = '2d6f.456f260c51ab9602.k1.c955c630-a799-11ed-bfa0-525400fcd3f1.1863087ba13';
                if(!empty($candidate_name->profile_email)){
                $return_status=$this->email_send($message, $subject, $to_email, $from_content, $template_key);

                //    $return_status= $this->email_send($message, $subject, $to_email, $from_content);
                $emp_str_length = strlen($emp_company_name);
            

                if ($emp_str_length > 30) {
                    $emp_company_name = mb_strimwidth($emp_company_name, 0, 28, "..");
                }
            
            
                // $message = rawurlencode('Your Interview with '.$emp_company_name.' is scheduled today at '.$interview_slot.', All the best! -Team Internme');
                $message = rawurlencode('Your Interview with '.$emp_company_name.' is scheduled today at '.$interview_slot.', All the best! - Internme Team.');

                // $message = rawurlencode('Congratulation '.$can_details->profile_full_name.', You`ve been hired by '.$emp_company_name.' for '.$profile.' internship - Team InternMe.');
                $this->sms_send($candidate_name->profile_phone_number, $message);

                if($return_status){
                        echo "Email send successfully";
                }else{
                        //echo "email";
                }
            }
        }
        }
    }

  //common function for send email
//   function email_send($message, $subject, $to_email, $from_content)
//   {
//       $email = \Config\Services::email();
//       $email->setTo($to_email);
//       $email->setFrom('internme.app@gmail.com', 'NoReply', $from_content);
//       $email->setSubject($subject);
//       $email->setMessage($message);
//       if ($email->send()) {
//           return true;
//       } else {
//           return false;
//       }
//   }

function email_send($message, $subject, $to_email, $from_content, $template_key){
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
     "template_key": "'.$template_key.'",
    "bounce_address":"donotreply@notification.internme.app",
    "from": { "address": "noreply@internme.app"},
    "to": [{"email_address": {"address": "'.$to_email.'","name": "'.$from_content.'"}}],
    "subject":"'.$subject.'",
    "merge_info": '.$message.' }',
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
}
