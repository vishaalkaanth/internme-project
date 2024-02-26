<?php

namespace App\Controllers;

use App\Models\Sms_user_model;

use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Supervisor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$this->Sms_user_model = new Sms_user_model();


class Sms_user extends BaseController
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Kolkata');
     
    }

    public function send_sms_user()
    {
        $Sms_user_model = new Sms_user_model();
        $where = array('sms_send_status' => '0');
        $available_data = $Sms_user_model->fetch_table_data('user_sms', $where);
        
        $i=1;
        if(isset($available_data) && !empty($available_data)){
        foreach($available_data as $user_data){
            $emp_str_length = strlen($user_data->name);
            if ($emp_str_length > 30) {
                $candidate_name = mb_strimwidth($user_data->name, 0, 28, "..");
            }else{
                $candidate_name = $user_data->name;
            }
            $message = rawurlencode("Dear ".$candidate_name.", You are registered to InternMe as a special initiative from your college. Login to interme.app using your mobile number and reset password and explore paid internships and internships with leading MNC's - InternMe.app");
            $sms_send=$this->sms_send($user_data->mobile_number, $message);
            if($sms_send){
                $where_update = array('id' => $user_data->id);
                $data = ['sms_send_status' => 1];
                $update_status = $Sms_user_model->update_commen('user_sms', $where_update, $data);
                if($update_status){
                    echo $i. '.'.$user_data->mobile_number." - SMS Send Successfully<BR>";
                    // echo $message;
                }

                $i++;
            }
            }
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
