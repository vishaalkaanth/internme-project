<?php

namespace App\Controllers;

class Razorpay extends BaseController
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Kolkata');
    }
    public function candidate_payment()
    {
        $data = [];
        $data['title'] = 'Checkout payment | Tutsmake.com';
        $data['callback_url'] = base_url() . '/razorpay-callback';
        $data['surl'] = base_url() . '/razorpay-success';
        $data['furl'] = base_url() . '/razorpay-failed';
        $data['currency_code'] = 'INR';
        return view('razorpay', $data);
        // $this->load->view("razorpay", $data);
    }

    // initialized cURL Request
    private function curl_handler($payment_id, $amount)
    {
        $url = 'https://api.razorpay.com/v1/payments/' . $payment_id . '/capture';
        $key_id = "rzp_live_U5JHzi0BdrUlj9";
        $key_secret = "QyXs5C16bbcrQeYbA07QZgVF";
        $fields_string = "amount=$amount";
        //cURL Request
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        return $ch;
    }

    // callback method
    public function callback()
    {
        $session = session();
        if (!empty($this->request->getVar('razorpay_payment_id')) && !empty($this->request->getVar('merchant_order_id')))
        {
            $razorpay_payment_id = $this->request->getVar('razorpay_payment_id');
            $merchant_order_id = $this->request->getVar('merchant_order_id');
            $session = session();
            $session->set('razorpay_payment_id',$razorpay_payment_id);	
            $session->set('merchant_order_id',$merchant_order_id);	

            $currency_code = 'INR';
            $amount = $this->request->getVar('merchant_total'); 
            $success = false;
            $error = '';
            try
            {
                $ch = $this->curl_handler($razorpay_payment_id, $amount);
                //execute post
                $result = curl_exec($ch);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($result === false)
                {
                    $success = false;
                    $error = 'Curl error: ' . curl_error($ch);
                }
                else
                {
                    $response_array = json_decode($result, true);
                    //Check success response
                    if ($http_status === 200 and isset($response_array['error']) === false)
                    {
                        $success = true;
                    }
                    else
                    {
                        $success = false;
                        if (!empty($response_array['error']['code']))
                        {
                            $error = $response_array['error']['code'] . ':' . $response_array['error']['description'];
                        }
                        else
                        {
                            $error = 'RAZORPAY_ERROR:Invalid Response <br/>' . $result;
                        }
                    }
                }
                //close curl connection
                curl_close($ch);
            }
            catch(Exception $e)
            {
                $success = false;
                $error = 'Request to Razorpay Failed';
            }
            if ($success === true)
            {
                if (!empty($session->get('ci_subscription_keys')))
                {
                    $session->unset('ci_subscription_keys');
                }
                // if (!$order_info['order_status_id'])
                // {
                    return redirect()->to($this->request->getVar('merchant_surl_id'));
                    // return redirect($this->request->getVar('merchant_surl_id'));
                // }
                // else
                // {
                //     return redirect($this->request->getVar('merchant_surl_id'));
                // }
            }
            else
            {
                return redirect()->to($this->request->getVar('merchant_furl_id'));
            }
        }
        else
        {
            echo 'An error occured. Contact site administrator, please!';
        }
    }
    public function success()
    {
        $session = session();
        $data['title'] = 'Razorpay Success | In22labs';
        echo "<h4>Your transaction is successful</h4>";
        echo "<br/>";
        echo "Transaction ID: " . $session->get('razorpay_payment_id');
        echo "<br/>";
        echo "Order ID: " . $session->get('merchant_order_id');
    }
    public function failed()
    {
        $session = session();
        $data['title'] = 'Razorpay Failed | In22labs';
        echo "<h4>Your transaction got Failed</h4>";
        echo "<br/>";
        echo "Transaction ID: " . $session->get('razorpay_payment_id');
        echo "<br/>";
        echo "Order ID: " .$session->get('merchant_order_id');
    }
}

