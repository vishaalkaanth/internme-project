<?php
namespace App\Controllers;

use App\Models\Common_model;

require_once(APPPATH."Libraries/razorpay/razorpay-php/Razorpay.php");
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Errors;
use Razorpay\Api\Request;

//header("HTTP/1.1 200 OK");

class PaymentWebhook extends BaseController
{
    protected $session;
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }
    
    
    public function webhook_payment_posting()
	{
	    //ini_set('allow_url_fopen', '1');
        ini_set('allow_url_fopen', 'On'); 
        $post = file_get_contents('php://input');
        //$post = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
        $data = json_decode($post, true);
        $webhookSecret='Internme@1234';
        file_put_contents('public/assets/docs/webhooks/data2.txt', $post);
         //exit();
        //  $post = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
        //$data = json_decode($post, true);
        
        //print_r($data);
        $Common_model = new Common_model();
        $data_history_ins = ['userid' => $data];
        $Common_model->insert_commen('can_payment_details_history_test', $data_history_ins);
        
       //if ((isset($data['event']) === true) && (empty($data['event']) === false) && (in_array($data['event'], $eventArray) === true)) {
    
            try {
                // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
                $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live

                $webhookSignature = hash_hmac('sha256', $post, $webhookSecret);
                $api->utility->verifyWebhookSignature($post, $webhookSignature, $webhookSecret);
                //$api->utility->verifyWebhookSignature($post, $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'], $webhookSecret);
                //file_put_contents('data.txt', $post);
                file_put_contents('public/assets/docs/webhooks/data2.txt', $post);
                        $Common_model = new Common_model();
                        $data_history_ins = ['userid' => "1"];
                        $Common_model->insert_commen('can_payment_details_history_test', $data_history_ins);
                     //Grant Commission - Don't want to execute it multiple times..
                
               header("HTTP/1.1 200 OK");  
                
            } catch (SignatureVerificationError $error) {
                $log = array('message' => $error->getMessage(), 'data' => $data);
                error_log(json_encode($log));
                echo "Unauthorized";
            }
  
}
  
   public function webhook_payment_posting_old_n()
	{
	    ini_set("allow_url_fopen", true);
	    $post = file_get_contents("php://input");
        $data = json_decode($post, true);
        // $keyId = "rzp_test_apAaBShG1heg3Y"; //Test
        $keyId = "rzp_live_U5JHzi0BdrUlj9"; //Live
        // $keySecret = "QS5IZZhFWsu1OjCkGdtBBwNj"; //Test
        $keySecret = "QyXs5C16bbcrQeYbA07QZgVF"; //Live
        $eventArray = [ORDER_PAID];
        //$conn=mysqli_connect("localhost","internme","iAvMzyTM2qjZjTxO","internmedb");
        //  if($conn)
        // {
         //   mysqli_query($conn,"insert into can_payment_details_history_test (userid) values('10')");
        //}
        //echo "asdf";
        //exit();

        if ((isset($data['event']) === true) && (empty($data['event']) === false) &&
        (in_array($data['event'], $eventArray) === true)) {
    
        if (isset($_SERVER['HTTP_X_RAZORPAY_SIGNATURE']) === true) {
            try {
                $api = new Api($keyId, $keySecret);
                $api->utility->verifyWebhookSignature($post, $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'], 'Internme@1234');
            } catch (SignatureVerificationError $error) {
                $log = array('message' => $error->getMessage(), 'data' => $data);
                error_log(json_encode($log));
                echo "Unauthorized";
            }
    
            switch ($data['event']) {
                case ORDER_PAID:
                    $status = $data['payload']['order']['entity']['status'];
                    //$email = $data['payload']['payment']['entity']['email'];
                    //$contact = $data['payload']['payment']['entity']['contact'];
                    //$order_id = $data['payload']['order']['entity']['id'];
    
                    if ($status == "paid") {
                        // insert values into database, send confirmation email, receipts etc
                        /*$pass_data = [
                            'email' => $email,
                            'contact' => $contact,
                            'order_id' => $order_id,
                            'status' => $status,
                        ];
                        postData($pass_data);
                        */
                        
                        $Common_model = new Common_model();
                        $data_history_ins = ['userid' => "1"];
                        $Common_model->insert_commen('can_payment_details_history_test', $data_history_ins);
                    }
    
            }
    
        } else echo "Unauthorizedssss";
    
    } else
    {
        echo "Unauthorizedss";   
    }
  
}
    public function webhook_payment_posting_old()
	{
	   
            $data = file_get_contents('php://input');
            $headers = getallheaders();
            $en_headers = json_encode($headers);
            $filename1 = date('y'.'m'.'d'.'H'.'i'.'s')."body.json";
            $filename2 = date('y'.'m'.'d'.'H'.'i'.'s')."header.json";
            $url = base_url()."/public/assets/docs/webhooks";
            file_put_contents("$url/$filename1",$data);
            file_put_contents("$url/$filename2",$en_headers); 
            $calculated = hash_hmac('sha256', $data, 'Internme@1234');
            $receieved = $headeres['X-Razorpay-Signature'];
            $data = json_decode($data);
            
            $Common_model = new Common_model();
            $data_history_ins = ['userid' => "1"];
            $Common_model->insert_commen('can_payment_details_history_test', $data_history_ins);
            
            if($calculated == $receieved)
            {
                    $Common_model = new Common_model();
                    $data_history_ins = [
                             'userid' => "1",
                             'merchant_order_id' => "1",
                             'merchant_txn_id' => "1",
                             'order_id' => "1",
                             'payment_id' => "1",
                             'payment_date' => "2023-17-04",
                             'payment_amount' => "1",
                             'payment_status' => "1",
                             'payment_method' => "1",
                             'card_id' => "1",
                             'bank' => "1",
                             'wallet' => "1",
                             'vpa' => "1",
                             'captured' => "1",
                             'invoice_no' => "1",
                             'create_mode' => "2",
                             'status' => "2",
                             'status' => "2",
                             'created_at' => "2023-17-04"];
                    $Common_model->insert_commen('razorpay_payment_transactions', $data_history_ins);
                    exit();
                }                                     
	}   
    
 
}
