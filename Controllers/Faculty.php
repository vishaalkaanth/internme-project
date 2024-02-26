<?php

namespace App\Controllers;

use App\Models\Faculty_model;
use App\Models\Candidate_model;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Supervisor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$this->Faculty_model = new Faculty_model();


class Faculty extends BaseController
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Kolkata');
     
    }

    public function faculty_dashboard()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        
        $where = array('faculty_id' => $userid);
        $data['total_candidate'] = $Faculty_model->data_count_fetch('can_applied_internship', $where);
        $where1 = array('faculty_id' => $userid,'complete_status' => '1','complete_type!=' =>'1' );
        $data['dropped_candidate'] = $Faculty_model->data_count_fetch('can_applied_internship', $where1);
        $where2 = array('faculty_id' => $userid,'complete_status' => '1','complete_type' =>'1' );
        $data['completed_candidate'] = $Faculty_model->data_count_fetch('can_applied_internship', $where2);
        $current_date =date("Y-m-d");
        $where3 = array('can_applied_internship.hiring_status' => '1','can_applied_internship.complete_status!=' => '1','can_applied_internship.complete_type' =>'0', 'can_applied_internship.faculty_id' => $userid, 'employer_post_internship.internship_startdate <=' => $current_date);

        $data['ongoing_candidate'] = $Faculty_model->fetch_table_data_ongoing('can_applied_internship', $where3);
    //    print_r($data['ongoing_candidate']);exit;
    $where4 = array('faculty_id' => $userid);
    $data['total_internship'] = $Faculty_model->data_count_fetch_groupby('can_applied_internship', $where4);
    $where5 = array('faculty_id' => $userid);
        $data['visited_completed'] = $Faculty_model->data_count_fetch_groupby('faculty_visited_data_final', $where5); 
        // $where6 = array('faculty_id' => $userid,'visited_status' => '0');
        // $current_date =date("Y-m-d");
        // $where8 = array('can_applied_internship.complete_status' => '1','can_applied_internship.complete_type!=' =>'1', 'can_applied_internship.faculty_id' => $userid);
 $where8 = '(can_applied_internship.faculty_id="'. $userid.'" AND employer_post_internship.active_status="1" AND ((can_applied_internship.complete_type="0" or can_applied_internship.complete_type="1") AND  employer_post_internship.internship_startdate <= "'. $current_date.'"))';
 $data['visited_pending'] = count($Faculty_model->fetch_table_data_visit_pending('can_applied_internship', $where8));
        // $where9 = array('can_applied_internship.hiring_status' => '1', 'can_applied_internship.faculty_id' => $userid ,'employer_post_internship.internship_startdate >', $current_date);
        // // $where8 = '(can_applied_internship.hiring_status="1" AND can_applied_internship.faculty_id="'. $userid.'" AND ((can_applied_internship.complete_status="1" AND can_applied_internship.complete_type!="1") OR ( employer_post_internship.internship_startdate > "'. $current_date.'")))';
        // $visited_pending2= count($Faculty_model->fetch_table_data_visit_pending1('can_applied_internship', $where9));
        // $visited_pending3=array_merge($visited_pending1->internship_id,$visited_pending2->internship_id);
        // $visited_pending4=array_unique($visited_pending3);

        // $data['visited_pending'] = $data['total_internship']-$data['visited_completed'];  

        $where7 = array('can_applied_internship.faculty_id' => $userid);

        $data['ongoing_internship'] = $Faculty_model->fetch_table_data_ongoing_internship('can_applied_internship', $where7);
    return view('faculty/faculty_dashboard',$data);
    }
    public function faculty_profile()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where1 = array('status' => '1');
        $col_data = 'id,college_name';
        $data['master_college'] = $Faculty_model->fetch_table_data_col('master_college', $where1,$col_data);
            $where = array('userid' => $userid);
            $data['faculty_profile'] = $Faculty_model->fetch_data_for_faculty_profile('faculty_reg_data', $where);
        return view('faculty/faculty_profile',$data);
    }

    public function applied_candidate_list($internship_id)
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $current_date =date("Y-m-d");
        $where3 = array('can_applied_internship.faculty_id' => $userid, 'can_applied_internship.internship_id' => $internship_id);

        $data['ongoing_candidate'] = $Faculty_model->fetch_table_data_ongoing_internship_candidate('can_applied_internship', $where3);
        return view('faculty/applied_candidate_list',$data);
    }

    public function schedule_visit() //Function For accept interview
    {
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $internship_id = $this->request->getVar('internship_id');
        $schedule_visit_date = $this->request->getVar('schedule_visit_date');
        $current_datetime = $Faculty_model->current_datetime();
        $schedule_visit_time_from = $this->request->getVar('schedule_visit_time_from');
        $schedule_visit_time_to = $this->request->getVar('schedule_visit_time_to');
      
        $data = [
            'faculty_id' => $userid,
            'internship_id' => $internship_id,
            'schedule_visit_date' => $schedule_visit_date,
            'visit_time_from' => $schedule_visit_time_from,
            'visit_time_to' => $schedule_visit_time_to,
            'created_at' => $current_datetime,
        ];
        $result = $Faculty_model->insert_commen('faculty_visited_data', $data);
        $visit_date = date("d-M-Y", strtotime($schedule_visit_date));
        $current_year=date('Y');
        $where_com = array('internship_id ' => $internship_id);
        $internship_data = $Faculty_model->fetch_table_row('employer_post_internship', $where_com);

             $where = array('userid' => $userid);
            $faculty_profile = $Faculty_model->fetch_data_for_faculty_profile('faculty_reg_data', $where);
       
            $where_cor = array('userid ' => $internship_data->user_id);
            $user_data_cor = $Faculty_model->fetch_table_row('profile_completion_form', $where_cor);
            $where_cor = array('userid ' => $internship_data->assigned_to);
            $user_data_log = $Faculty_model->fetch_table_row('userlogin', $where_cor);
            if($result) {
            
        $message = '{ "teacher_name" : "'.$faculty_profile[0]->faculty_name.'", "visit_date" : "'.$visit_date.'", "company_name" : "'.$user_data_cor->profile_company_name.'", "poc_location" : "'.$user_data_cor->location_name.'", "poc_name" : "'.$user_data_log->name.'", "poc_number" : "'.$user_data_log->mobile.'", "poc_mail" : "'.$user_data_log->email.'", "year" : "'.$current_year.'"}'; //dynamic contents for template
        $subject      = 'Your visit to '.$user_data_cor->profile_company_name.' has been scheduled successfully';
        $to_email     =  $faculty_profile[0]->faculty_email;
        $from_content = 'Your visit to '.$user_data_cor->profile_company_name.' has been scheduled successfully';
        $template_key = '2d6f.456f260c51ab9602.k1.092dc760-b2a0-11ed-9749-525400d6cd4f.18678c79ed6';
        // echo $to_email;exit;
        $this->email_send($message, $subject, $to_email, $from_content, $template_key);

        if (isset($internship_data->profile) && $internship_data->profile != '0') {
            $profile = $Faculty_model->get_master_name_profile('master_profile', $internship_data->profile, 'profile');
        } else {
            $profile =  $internship_data->other_profile;
        }
        $newTime1 = date("h:i a", strtotime($schedule_visit_time_from));
        $newTime2 = date("h:i a", strtotime($schedule_visit_time_to));
        $time=$newTime1.'-'.$newTime2;
        $message = '{ "internship_profile" : "'.$profile.'","faculty_name" : "'.$faculty_profile[0]->faculty_name.'", "name" : "'.$user_data_log->name.'", "visit_date" : "'.$visit_date.'", "eta_arrival" : "'.$time.'", "number" : "'.$faculty_profile[0]->faculty_mobile.'", "mail" : "'.$faculty_profile[0]->faculty_email.'", "college_name" : "'.$faculty_profile[0]->faculty_college_other.'", "year" : "'.$current_year.'"}'; //dynamic contents for template
        $subject      = 'Faculty visit for '.$profile.' internship is scheduled.';
        $to_email     =  $user_data_log->email;
        $from_content = 'Faculty visit for '.$profile.' internship is scheduled.';
        $template_key = '2d6f.456f260c51ab9602.k1.6e7a1310-b2a7-11ed-9749-525400d6cd4f.18678f816c1';
        // echo $to_email;exit;
        $this->email_send($message, $subject, $to_email, $from_content, $template_key);
    }
        $session->setFlashdata('error_msg', 'Visit Scheduled Successfully');
        $session->setFlashdata('error_status', '2');
        return redirect()->to('faculty-dashboard');
    }

    public function faculty_candidate_list($internship_id)
    {
        helper(['form']);
        $session = session();
        // $ses_data = [
        //     'session_name_img' => ''];
        // $session->set($ses_data);
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $current_date =date("Y-m-d");
        $data['internship_id'] =$internship_id;
        $where3 = array('can_applied_internship.faculty_id' => $userid, 'can_applied_internship.internship_id' => $internship_id);

        $data['ongoing_candidate'] = $Faculty_model->fetch_table_data_ongoing_internship_candidate('can_applied_internship', $where3);
        return view('faculty/faculty_candidate_list',$data);
    }


    public function update_schedule_visit()
    {
        $candidate_id1 = $this->request->getVar('candidate_id');
        // print_r($candidate_id1);exit;
        if(count($candidate_id1)>1){
        $candidate_id = implode(",", array_filter($candidate_id1));
        }else{
            $candidate_id = $candidate_id1;
        }
        // 
        $candidatearr = $candidate_id;
        $internship_id = $this->request->getVar('internship_id');
        $visit_date = $this->request->getVar('visit_date');
        $o_q1 = $this->request->getVar('org1_rating_value');
        $o_q2 = $this->request->getVar('org2_rating_value');
        $o_q3 = $this->request->getVar('org3_rating_value');
        $o_q4 = $this->request->getVar('org4_rating_value');
        $org_description = $this->request->getVar('org_description');
        // $files = $this->request->getFile('files');

        $session = session();
        $Faculty_model = new Faculty_model();
        $current_datetime = $Faculty_model->current_datetime();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        // $files = explode(",", $files);
        

            // if (!empty($candidate_id1)) {
            //     foreach ($candidate_id1 as $key) {
            
            //         $data = ['faculty_visited_date' => $visit_date];
            //         $where = array('candidate_id' => $key, 'faculty_id' => $userid,'internship_id' => $internship_id);
            //         $update_application_status = $Faculty_model->update_commen('can_applied_internship', $where, $data);
            //     }
            // }

$session_name_img    =    $session->get('session_name_img');
 $where1 = array('session_name' => $session_name_img);
        $col_data = 'id,img_name,session_name';
        $img_data = $Faculty_model->fetch_table_data_col('faculty_img_upload', $where1,$col_data);
        /* Store the path of source file */
        if(!empty($img_data[0]->img_name)){
            $filePath = 'public/assets/docs/uploads/temp_faculty_visit_images/'.$img_data[0]->img_name;
            $destinationFilePath = 'public/assets/docs/uploads/faculty_visit_images/'.$img_data[0]->img_name;
            rename($filePath, $destinationFilePath); 
            $img1=$img_data[0]->img_name;
        }else{
            $img1='';
        }
        if(!empty($img_data[1]->img_name)){
            $filePath1 = 'public/assets/docs/uploads/temp_faculty_visit_images/'.$img_data[1]->img_name;
            $destinationFilePath1 = 'public/assets/docs/uploads/faculty_visit_images/'.$img_data[1]->img_name;
            rename($filePath1, $destinationFilePath1); 
            $img2=$img_data[1]->img_name;
        }else{
            $img2='';
        }
        if(!empty($img_data[2]->img_name)){
            $filePath2 = 'public/assets/docs/uploads/temp_faculty_visit_images/'.$img_data[2]->img_name;
            $destinationFilePath2 = 'public/assets/docs/uploads/faculty_visit_images/'.$img_data[2]->img_name;
            rename($filePath2, $destinationFilePath2);
            $img3=$img_data[2]->img_name;
        }else{
            $img3='';
        }
        if(!empty($img_data[3]->img_name)){
            $filePath3 = 'public/assets/docs/uploads/temp_faculty_visit_images/'.$img_data[3]->img_name;
            $destinationFilePath3 = 'public/assets/docs/uploads/faculty_visit_images/'.$img_data[3]->img_name;
         rename($filePath3, $destinationFilePath3); 
         $img4=$img_data[3]->img_name;
        }else{
            $img4='';
        }

 

            $data = [
                'faculty_id' => $userid,
                'internship_id' => $internship_id,
                'candidate_id' => $candidatearr,
                'visited_date' => $visit_date,
                'image1' => $img1,
                'image2' => $img2,
                'image3' => $img3,
                'image4' => $img4,
                'org_description' => $this->request->getVar('org_description'),
                'o_q1' => $this->request->getVar('org1_rating_value'),
                'o_q2' => $this->request->getVar('org2_rating_value'),
                'o_q3' => $this->request->getVar('org3_rating_value'),
                'o_q4' => $this->request->getVar('org4_rating_value'),
                'college_description' => $this->request->getVar('college_description'),
                'c_q1' => $this->request->getVar('hr1_rating_value'),
                'c_q2' => $this->request->getVar('hr2_rating_value'),
                'c_q3' => $this->request->getVar('hr3_rating_value'),
                'c_q4' => $this->request->getVar('hr4_rating_value'),
                'stu_description' => $this->request->getVar('stu_description'),
                's_q1' => $this->request->getVar('stu1_rating_value'),
                's_q2' => $this->request->getVar('stu2_rating_value'),
                's_q3' => $this->request->getVar('stu3_rating_value'),
                's_q4' => $this->request->getVar('stu4_rating_value'),
                'created_at' => $current_datetime,
            ];
            $result = $Faculty_model->insert_commen('faculty_visited_data_final', $data);
            if ($result) {
                $wheredel = array('session_name' => $session_name_img);
                $result = $Faculty_model->delete_commen('faculty_img_upload', $wheredel);
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Visited Report Updated successfully');
              
                return redirect()->to('faculty-dashboard');
            } else {
                return redirect()->to('faculty-dashboard');
            }
            
    }
    public function get_can_mobile_email_edit_fac()
    {
        $session = session();
        $usertype    =    $session->get('usertype');
        // $model = new Employer_model();
        $userid = $this->request->getVar('userid');
       
        $Faculty_model = new Faculty_model();
        $where = array('userid' => $userid, 'status' => '1');
       
            $profile = $Faculty_model->fetch_table_row('faculty_reg_data', $where);
            echo $profile->faculty_mobile . '^' . $profile->faculty_email . '^' . csrf_hash();
      
    }

    public function fac_profile_mobile_otp_send()
    {
        $otp = mt_rand(100000, 999999);
        // $model = new Employer_model();

        $user_id = $this->request->getVar('user_id');
        $mobile = $this->request->getVar('mobile');
        $session        = session();
        $usertype    =    $session->get('usertype');
        $Faculty_model = new Faculty_model();

        //check duplicate 
      
            $duplicate_data = $Faculty_model->duplicate_number($mobile, $user_id);
   
        // print_r($duplicate_data);

        if (empty($duplicate_data)) {
            //check otp count
            $otp_count = $Faculty_model->otp_count_check($mobile, $usertype);

            //allow only 5 attempt
            if ($otp_count < 5) {
                $otp_count_new  = $otp_count + 1;

                $data = array(
                    'phone_number' => $mobile,
                    'otp_count'    => $otp_count_new,
                    'otp_number'   => $otp,
                    'user_type'    => $usertype,
                );

                $update_otp = $Faculty_model->otp_count_save($data);
                //otp sms 
                $apiKey = urlencode('EM5b4IXHyls-hDU0Ja8rBGILMJ7RKiJEtcXakV8kke');
                // Message details
                $numbers = $mobile;
                $sender = urlencode('INTNME');
                $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');

                // Prepare data for POST request
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

                // Send the POST request with cURL
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);


                $msg = 'We have sent an OTP to your registered Mobile Number';
                $otp_status = 0;
            } else {
                $msg = 'OTP Limit Exceeded, Please Try After 24 Hours';
                $otp_status = 1;
            }
            // print_r($duplicate_data);
            if ($update_otp) {
                echo '1' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
            } else {
                echo '0' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
            }
        } else {
            $msg = 'Duplicate Entry';
            $otp_status = 2;
            echo '2' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
        }
    }

    public function mobile_otp_verify_fac()
    {
        // $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $user_otp = $this->request->getVar('user_otp');
        $mobile = $this->request->getVar('mobile');
        $session = session();
        $usertype    =    $session->get('usertype');
        $Faculty_model = new Faculty_model();

            $where = array('otp_number' => $user_otp, 'user_type' => $usertype, 'phone_number' => $mobile);
            $col_data = 'id';
        $available_data = $Faculty_model->fetch_table_data_col('user_otp', $where,$col_data);
        if ($available_data) {
            $otp_count = 0;
        
                $data = ['faculty_mobile' => $mobile, 'mobile_otp_status' => 1];
                $data_user = ['mobile' => $mobile];
                $where1 = array('userid' => $user_id);
                $where_user = array('userid' => $user_id);
                $update_otp = $Faculty_model->update_commen('faculty_reg_data', $where1, $data);
                $update_user = $Faculty_model->update_commen('userlogin', $where_user, $data_user);
            
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }

    public function fac_profile_email_otp()
    {
        $otp = mt_rand(100000, 999999);
        // $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $email = $this->request->getVar('email');
        $session        = session();
        $usertype    =    $session->get('usertype');
        $Faculty_model = new Faculty_model();

        //check duplicate 

       
            $duplicate_data = $Faculty_model->duplicate_email($email, $user_id);

        // print_r($duplicate_data);exit();

        if (empty($duplicate_data)) {
            //check otp count
            $otp_count = $Faculty_model->otp_count_check_email($email, $usertype);

            //allow only 5 attempt
            if ($otp_count < 5) {

                $otp_count_new  = $otp_count + 1;

                $data1 = array(
                    'email_id'     => $email,
                    'otp_count'    => $otp_count_new,
                    'otp_number'   => $otp,
                    'user_type'    => $usertype,
                );

                $update_otp = $Faculty_model->otp_count_save_email($data1, $usertype);

                $to_email = $email;
   
                $where = array('userid' => $user_id);
                $col_data = 'faculty_name';
                    $available_data = $Faculty_model->fetch_table_data_col('faculty_reg_data', $where,$col_data);
                   
                    $employer_name = $available_data[0]->faculty_name;
                    //  print_r($update_otp);
      
                $current_year=date('Y');
                // $msg_data['msg_data'] = array('otp' => $otp, 'name' => $employer_name, 'email_status' => 2); //dynamic contents for template
                // $message     = view('email_template/verification_of_email', $msg_data);
                $message = '{ "otp" : "'.$otp.'", "name" : "'.$employer_name.'", "title" : "OTP Verification" ,"year" : '.$current_year.'}'; //dynamic contents for template
                $subject  = 'Internme - OTP Verification';
                $to_email  = $email;
                $from_content = 'Internme - OTP Verification';
                $template_key = '2d6f.456f260c51ab9602.k1.31740a80-a789-11ed-bfa0-525400fcd3f1.186301afb28';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                // $this->email_send($message, $subject, $to_mail, $from_sub);


                $msg = 'We have sent an OTP to your registered Email ID';
                $otp_status = 0;


                if ($update_otp) {


                    echo '1' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
                } else {


                    echo '0' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
                }
            } else {
                $msg = 'OTP Limit Exceeded, Please Try After 24 Hours';
                $otp_status = 1;
                echo '1' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
            }
        } else {
            $msg = 'Email ID Duplicate';
            $otp_status = 2;
            echo '2' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
        }
    }
    public function fac_email_otp_verify_edit()
    {
        // $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $user_otp = $this->request->getVar('user_otp');
        $email = $this->request->getVar('email');
        $session = session();
        $usertype    =    $session->get('usertype');
        $Faculty_model = new Faculty_model();
     
        $where = array('otp_number' => $user_otp, 'user_type' => $usertype, 'email_id' => $email);
        $col_data = 'id';
        $available_data = $Faculty_model->fetch_table_data_col('user_otp', $where,$col_data);
        if ($available_data) {
            $otp_count = 0;
          
                $data = ['faculty_email' => $email, 'email_otp_status' => 1];
              
                $data_user = ['email' => $email];
                $where1 = array('userid' => $user_id);
                $update_profile = $Faculty_model->update_commen('faculty_reg_data', $where1, $data);
                $update_user = $Faculty_model->update_commen('userlogin', $where1, $data_user);

            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }

    public function update_faculty_details()
    {

        $session = session();

        $Faculty_model = new Faculty_model();
        $validation =  \Config\Services::validation();
        $session = session();
        $usertype    =    $session->get('usertype');
        $isValidated = $this->validate([
            'faculty_name' => ['label'  => 'Full name', 'rules'  => 'required',],
            'profile_mail' => ['label'  => 'Mail id', 'rules'  => 'required|valid_email',],
            'profile_mobile' => ['label'  => 'Mobile number', 'rules'  => 'required|max_length[10]',],
            'college_name'      => ['label'  => ' College Name', 'rules'  => 'required',], //validate location
        ]);


        //if not validated 
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('faculty-profile');
        } else {
            $user_id = $this->request->getVar('user_id');
            $college_name = $this->request->getVar('college_name');
            $where1 = array('college_name' => $college_name);
            $col_data = 'id,college_name';
            $college_id = $Faculty_model->fetch_table_row_col('master_college', $where1,$col_data);
            // print_r($college_id->id);exit;
            if (!empty($college_id)) {
                $edu_college = $college_id->id;
              
            } else {
                $edu_college = 0;
               
            }

            $where = array('userid' => $user_id);
      
                $data = [
                    'faculty_name' => $this->c_trim($this->request->getVar('faculty_name')),
                    'faculty_email' => $this->c_trim($this->request->getVar('profile_mail')),
                    'faculty_mobile' => $this->c_trim($this->request->getVar('profile_mobile')),
                    'faculty_college' => $edu_college,
                    'faculty_college_other' => $college_name,
                 
                ];
                // print_r($data);exit;
                $update_org_data1 = $Faculty_model->update_commen('faculty_reg_data', $where, $data);
                        $data1 = [
                    'name' => $this->c_trim($this->request->getVar('faculty_name')),
                    'username' => $this->c_trim($this->request->getVar('faculty_name')),
                    'email' => $this->c_trim($this->request->getVar('profile_mail')),
                    'mobile' => $this->c_trim($this->request->getVar('profile_mobile')),
                        ];
                $update_org_data = $Faculty_model->update_commen('userlogin', $where, $data1);
      
            if ($update_org_data1) {
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Faculty details Updated successfully');
              
                return redirect()->to('faculty-profile');
            } else {
                return redirect()->to('faculty-profile');
            }
        }
    }

    function c_trim($var)
    {
        $var = ltrim($var);
        $var = rtrim($var);
        return $var;
    }

    public function fac_images()
    {
        //print_r($_FILES['file']['name']);
        $model = new Faculty_model();
       
        $file_string1 = $this->request->getVar('photoimage');
        // print_r($file_string1);exit;
        //photo camera
    // $file_string1 = $this->input->post('camphoto');

    $image1 = explode(";base64,", $file_string1);

    $image_type1 = explode("image/", $image1[0]);

    $image_type_png1 = $image_type1[1];

    $image_base641 = base64_decode($image1[1]);

    $output_dir11   = "public/assets/docs/uploads/temp_faculty_visit_images/";

    $sig_name1 ='A'.mt_rand(10000000, 99999999).mt_rand(10000000, 99999999).'.'.$image_type_png1;

    file_put_contents($output_dir11.$sig_name1, $image_base641);

        // $newName = $image_base641->getRandomName();
        // $files->move('public/assets/docs/uploads/temp_faculty_visit_images/', 'A'.$newName);

        $session = session();
        $Faculty_model = new Faculty_model();
        $session_name_img    =    $session->get('session_name_img');

        if(!empty($session_name_img)){
            $session_name    =    $session->get('session_name_img');
          
        }else{
            $session_name = mt_rand(10000000, 99999999);
            $ses_data = [
                'session_name_img' => $session_name];
            $session->set($ses_data);
        }
      
        $data = ['img_name' => $sig_name1,'session_name' => $session_name];
        //print_r($data);
        $update_logo = $Faculty_model->insert_commen('faculty_img_upload', $data);

        $where1 = array('session_name' => $session->get('session_name_img'));
        $col_data = 'id,img_name,session_name';
        $img_data = $Faculty_model->fetch_table_data_col('faculty_img_upload', $where1,$col_data);
        if($img_data!="") { $count_imgdata=count($img_data); }
        else { $count_imgdata='0'; }
        $getdates = '';

        if (!empty($img_data)) {
            foreach ($img_data as $as) {
                
 $urlt = base_url().'/public/assets/docs/uploads/temp_faculty_visit_images/'.$as->img_name;
 $img_name = '"'.$as->img_name.'"';
 $img_id = '"'.$as->id.'"';
 $getdates = $getdates . "<div class='pip'><img class='imageThumb' src='$urlt'><span class='remove' onclick='delete_img($img_name,$img_id)'>x</span></div>";
                //  $getdates = $getdates . '<span class="pip"><img class="imageThumb" src="'.base_url().'"/public/assets/docs/uploads/temp_faculty_visit_images/"'.$as->img_name.'"><span class="remove" onclick="delete_img("'.$as->img_name.'","'. $as->id .'");">x</span></span>';
              
            }
        }
        echo csrf_hash() . '^' . $getdates. '^' . $count_imgdata;
    }
    public function fac_images1()
    {
        //print_r($_FILES['file']['name']);
        $model = new Faculty_model();
       
        $files = $this->request->getFile('img_files');
        $newName = $files->getRandomName();
        $files->move('public/assets/docs/uploads/temp_faculty_visit_images/', 'A'.$newName);

        $session = session();
        $Faculty_model = new Faculty_model();
        $session_name_img    =    $session->get('session_name_img');

        if(!empty($session_name_img)){
            $session_name    =    $session->get('session_name_img');
          
        }else{
            $session_name = mt_rand(10000000, 99999999);
            $ses_data = [
                'session_name_img' => $session_name];
            $session->set($ses_data);
        }
      
        $data = ['img_name' => 'A'.$newName,'session_name' => $session_name];
        //print_r($data);
        $update_logo = $Faculty_model->insert_commen('faculty_img_upload', $data);

        $where1 = array('session_name' => $session->get('session_name_img'));
        $col_data = 'id,img_name,session_name';
        $img_data = $Faculty_model->fetch_table_data_col('faculty_img_upload', $where1,$col_data);
        if($img_data!="") { $count_imgdata=count($img_data); }
        else { $count_imgdata='0'; }
        $getdates = '';

        if (!empty($img_data)) {
            foreach ($img_data as $as) {
                
 $urlt = base_url().'/public/assets/docs/uploads/temp_faculty_visit_images/'.$as->img_name;
 $img_name = '"'.$as->img_name.'"';
 $img_id = '"'.$as->id.'"';
 $getdates = $getdates . "<div class='pip'><img class='imageThumb' src='$urlt'><span class='remove' onclick='delete_img($img_name,$img_id)'>x</span></div>";
                //  $getdates = $getdates . '<span class="pip"><img class="imageThumb" src="'.base_url().'"/public/assets/docs/uploads/temp_faculty_visit_images/"'.$as->img_name.'"><span class="remove" onclick="delete_img("'.$as->img_name.'","'. $as->id .'");">x</span></span>';
              
            }
        }
        echo csrf_hash() . '^' . $getdates. '^' . $count_imgdata;
    }

    public function delete_img()
    {
        //print_r($_FILES['file']['name']);
        $Faculty_model = new Faculty_model();
        $id = $this->request->getVar('id');
        $img_name = $this->request->getVar('img_name');
          unlink('public/assets/docs/uploads/temp_faculty_visit_images/' . $img_name);
          $wheredel = array('id' => $id);
          $result = $Faculty_model->delete_commen('faculty_img_upload', $wheredel);

        $session = session();
        $Faculty_model = new Faculty_model();
        $session_name_img    =    $session->get('session_name_img');


        $where1 = array('session_name' => $session->get('session_name_img'));
        $col_data = 'id,img_name,session_name';
        $img_data = $Faculty_model->fetch_table_data_col('faculty_img_upload', $where1,$col_data);
        if($img_data!="") { $count_imgdata=count($img_data); }
        else { $count_imgdata='0'; }
        $getdates = '';

        if (!empty($img_data)) {
            foreach ($img_data as $as) {
               
 $urlt = base_url().'/public/assets/docs/uploads/temp_faculty_visit_images/'.$as->img_name;
 $img_name = '"'.$as->img_name.'"';
 $img_id = '"'.$as->id.'"';
 $getdates = $getdates . "<div class='pip'><img class='imageThumb' src='$urlt'><span class='remove' onclick='delete_img($img_name,$img_id)'>x</span></div>";
                // $getdates = $getdates . '<span class="pip"><img class="imageThumb" src="'.base_url().'"/public/assets/docs/uploads/temp_faculty_visit_images/"'.$as->img_name.'"><span class="remove" onclick="delete_img("'.$as->img_name.'","'. $as->id .'");">x</span></span>';
              
            }
        }
        echo csrf_hash().'^'.$getdates.'^' .$count_imgdata;
    }
    public function candidate_certificate($internship_id,$candidate_id)
    {
        $session         = session();
        $Faculty_model = new Faculty_model();
        $userid          = $session->get('userid');
        $data['candidate_id']         = $candidate_id;

        $where = array('status' => '1', 'userid' => $candidate_id);
        $data['profile_personal'] = $Faculty_model->fetch_table_row('can_personal_details', $where);
        $where = array('status' => '1', 'internship_id' => $internship_id);
        $data['internship_details'] = $Faculty_model->fetch_table_data_for_all('employer_post_internship', $where);

        $where = array('status' => '1', 'company_id' => $data['internship_details'][0]->company_id);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['certificate_details'] = $Faculty_model->fetch_table_data_for_all('emp_certificate_details', $where, $order_by);

        $where_apply = array('status' => '1', 'internship_id' => $internship_id, 'candidate_id' => $candidate_id);
        $data['apply_internship_details'] = $Faculty_model->fetch_table_row('can_applied_internship', $where_apply);

        $where3 = array('status' => '1', 'userid' => $data['internship_details'][0]->company_id);
        $data['company_details'] = $Faculty_model->fetch_table_row('profile_completion_form', $where3);



        return view('faculty/can_certificate', $data);
    }


    public function work_report($internship_id,$candidate_id)
    {
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'internship_id' => $internship_id);
        $data['internship_details'] = $Faculty_model->fetch_table_row('employer_post_internship', $where);

        $where_can = array('status' => '1', 'userid' => $candidate_id);
        $data['candidate_details'] = $Faculty_model->fetch_table_row('can_personal_details', $where_can);

        $where_edu = array('status' => '1', 'userid' => $candidate_id);
        $data['candidate_educational_details'] = $Faculty_model->fetch_table_row('can_education_details', $where_edu);

        $where_cou = array('status' => '1', 'candidate_id' => $candidate_id, 'internship_id' => $internship_id);
        $data['internship_applied_list'] = $Faculty_model->fetch_table_row('can_applied_internship', $where_cou);

        $order_by = array('ordercolumn' => 'log_date', 'ordertype' => 'asc');
        $where_log = array('status' => '1', 'user_id' => $candidate_id, 'internship_id' => $internship_id, 'company_id' => $data['internship_details']->company_id);
        $data['log_sheet_details'] = $Faculty_model->fetch_table_data_for_log('can_log_sheet', $where_log, $order_by);

        $where_empl = array('status' => '1', 'userid' => $data['internship_details']->company_id);
        $data['view_profile_details'] = $Faculty_model->fetch_table_row('profile_completion_form', $where_empl);

        // $data['candidate_id'] = $userid;
        $dompdf = new \Dompdf\Dompdf(array('enable_remote' => true));

        $dompdf->loadHtml(view('faculty/work_report', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        //$dompdf = new Dompdf(array('enable_remote' => true));
        $dompdf->stream("Internship Report.pdf");

        // return view('Candidate/work_report',$data);
    }

    public function date_duplicate_check(){
        // $model = new Employer_model();
        $date = $this->request->getVar('date');
        $internship_id = $this->request->getVar('internship_id');
        $session = session();
        $usertype    =    $session->get('usertype');
        $userid    =    $session->get('userid');
        $Faculty_model = new Faculty_model();
     
        $where = array('visited_date' => $date, 'internship_id' => $internship_id, 'faculty_id' => $userid);
        $col_data = 'id';
        $available_data = $Faculty_model->fetch_table_data_col('faculty_visited_data_final', $where,$col_data);
        if ($available_data) {
         echo csrf_hash() . '^' .'1';
        } else {
            echo csrf_hash() . '^' .'0';
        }
    }

    public function view_visit_report($internship_id)
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where7 = array('faculty_visited_data_final.faculty_id' => $userid,'faculty_visited_data_final.internship_id' => $internship_id);

        $data['faculty_visited_data'] = $Faculty_model->fetch_table_data_visited_internship('faculty_visited_data_final', $where7);
    return view('faculty/view_visit_report',$data);
    }
    public function view_visit_report_download($internship_id)
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where7 = array('faculty_visited_data_final.faculty_id' => $userid,'faculty_visited_data_final.internship_id' => $internship_id);

        $data['faculty_visited_data'] = $Faculty_model->fetch_table_data_visited_internship('faculty_visited_data_final', $where7);


        // $data['candidate_id'] = $userid;
        $dompdf = new \Dompdf\Dompdf(array('enable_remote' => true));

        $dompdf->loadHtml(view('faculty/view_visit_report_download', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        //$dompdf = new Dompdf(array('enable_remote' => true));
        $dompdf->stream("Internship Visit Report.pdf");

        // return view('faculty/view_visit_report_download',$data);
    }

    public function faculty_visit_log()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
   
        $where7 = array('faculty_visited_data_final.faculty_id' => $userid);

        $data['faculty_visited_log'] = $Faculty_model->fetch_table_data_visited_log('faculty_visited_data_final', $where7);

        return view('faculty/faculty_visit_log',$data);
    }

    public function faculty_candidate_list_all()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where = array('faculty_id' => $userid);
        $data['title'] = 'Total Students List';
        $data['total_candidate'] = $Faculty_model->fetch_table_data_ongoing_internship_candidate('can_applied_internship', $where);
     return view('faculty/dashboard_candidate_list',$data);
    }
    public function faculty_candidate_list_ongoing()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $current_date =date("Y-m-d");
        $where3 = array('can_applied_internship.hiring_status' => '1','can_applied_internship.complete_status!=' => '1','can_applied_internship.complete_type' =>'0', 'can_applied_internship.faculty_id' => $userid, 'employer_post_internship.internship_startdate <=' => $current_date);

        $data['total_candidate'] = $Faculty_model->fetch_table_data_ongoing_internship_candidate('can_applied_internship', $where3);
        $data['title'] = 'Students List - Ongoing Internship';
        return view('faculty/dashboard_candidate_list',$data);
    }
    public function faculty_candidate_list_completed()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $current_date =date("Y-m-d");
        $where2 = array('faculty_id' => $userid,'complete_status' => '1','complete_type' =>'1' );

        $data['total_candidate'] = $Faculty_model->fetch_table_data_ongoing_internship_candidate('can_applied_internship', $where2);
        $data['title'] = 'Students List - Completed Internship';
        return view('faculty/dashboard_candidate_list',$data);
    }
    public function faculty_candidate_list_dropped()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $current_date =date("Y-m-d");
        $where1 = array('faculty_id' => $userid,'complete_status' => '1','complete_type!=' =>'1' );
        $data['total_candidate'] = $Faculty_model->fetch_table_data_ongoing_internship_candidate('can_applied_internship', $where1);
        $data['title'] = 'Students List - Dropped Internship';
        return view('faculty/dashboard_candidate_list',$data);
    }
    public function faculty_internship_list_all()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where7 = array('can_applied_internship.faculty_id' => $userid);

        $data['total_internship'] = $Faculty_model->fetch_table_data_ongoing_internship('can_applied_internship', $where7);
        $data['title'] = 'Total Internship List';
        return view('faculty/dashboard_internship_list',$data);
    }
    public function faculty_internship_list_visit_completed()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where7 = array('faculty_visited_data_final.faculty_id' => $userid);
         $data['total_internship'] = $Faculty_model->fetch_table_data_ongoing_internship_visited('faculty_visited_data_final', $where7);
         $data['title'] = 'Visits Completed';
         $data['v_date'] = '1';
         return view('faculty/dashboard_internship_list',$data);
    }

    public function faculty_internship_list_visit_pending()
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $current_date =date("Y-m-d");
        $where8 = '(can_applied_internship.faculty_id="'. $userid.'" AND employer_post_internship.active_status="1" AND ((can_applied_internship.complete_type="0" or can_applied_internship.complete_type="1") AND  employer_post_internship.internship_startdate <= "'. $current_date.'"))';
        $data['total_internship'] = $Faculty_model->fetch_table_data_visit_pending1('can_applied_internship', $where8);
        $data['title'] = 'Visits Pending';
        return view('faculty/dashboard_internship_list',$data);
    }


    public function update_visit_status()
    {
        $candidate_id1 = $this->request->getVar('candidate_id');
        // print_r($candidate_id1);exit;
        if(count($candidate_id1)>1){
        $candidate_id = implode(",", array_filter($candidate_id1));
        }else{
            $candidate_id = $candidate_id1;
        }
        // 
        $candidatearr = $candidate_id;
        $internship_id = $this->request->getVar('internship_id');
        $id = $this->request->getVar('id');
        $visit_date = $this->request->getVar('visit_date');

        $session = session();
        $Faculty_model = new Faculty_model();
        $current_datetime = $Faculty_model->current_datetime();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        // $files = explode(",", $files);
        

            // if (!empty($candidate_id1)) {
            //     foreach ($candidate_id1 as $key) {
            
            //         $data = ['faculty_visited_date' => $visit_date];
            //         $where = array('candidate_id' => $key, 'faculty_id' => $userid,'internship_id' => $internship_id);
            //         $update_application_status = $Faculty_model->update_commen('can_applied_internship', $where, $data);
            //     }
            // }

            $data = [
                'candidate_id' => $candidatearr,
                'org_description' => $this->request->getVar('org_description'),
                'o_q1' => $this->request->getVar('org1_rating_value'),
                'o_q2' => $this->request->getVar('org2_rating_value'),
                'o_q3' => $this->request->getVar('org3_rating_value'),
                'o_q4' => $this->request->getVar('org4_rating_value'),
                'college_description' => $this->request->getVar('college_description'),
                'c_q1' => $this->request->getVar('hr1_rating_value'),
                'c_q2' => $this->request->getVar('hr2_rating_value'),
                'c_q3' => $this->request->getVar('hr3_rating_value'),
                'c_q4' => $this->request->getVar('hr4_rating_value'),
                'stu_description' => $this->request->getVar('stu_description'),
                's_q1' => $this->request->getVar('stu1_rating_value'),
                's_q2' => $this->request->getVar('stu2_rating_value'),
                's_q3' => $this->request->getVar('stu3_rating_value'),
                's_q4' => $this->request->getVar('stu4_rating_value'),
               
            ];
            $where = array('id' => $id);
            $result = $Faculty_model->update_commen('faculty_visited_data_final', $where, $data);
            // $result = $Faculty_model->insert_commen('faculty_visited_data_final', $data);
            if ($result) {
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Visited Report Updated successfully');
              
                return redirect()->to('edit-visit-report/'.$id);
            } else {
                return redirect()->to('edit-visit-report/'.$id);
            }
            
    }

    public function edit_visit_report($id)
    {
        helper(['form']);
        $session = session();
        $Faculty_model = new Faculty_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where7 = array('faculty_visited_data_final.id' => $id);

        $data['faculty_visited_data'] = $Faculty_model->fetch_table_data_visited_internship('faculty_visited_data_final', $where7);
        // print_r($data['faculty_visited_data']);
    return view('faculty/edit_visit_report',$data);
    }
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
}
