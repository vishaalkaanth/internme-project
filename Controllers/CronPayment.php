<?php

namespace App\Controllers;

use App\Models\Common_model;

require_once(APPPATH . "Libraries/razorpay/razorpay-php/Razorpay.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Errors;
use Razorpay\Api\Request;
use GuzzleHttp\Client;

class CronPayment extends BaseController
{
    protected $session;
    function __construct()
    {
        //$this->session = \Config\Services::session();
        //$this->session->start();
        date_default_timezone_set('Asia/Kolkata');
    }
    public function razorpay_webhook_update()
    {
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        echo "<h3> Result Found From date " . date("d-m-Y", strtotime($start_date)) . " To " . date("d-m-Y", strtotime($end_date)) . "</h3>";

        $session = session();
        $Common_model = new Common_model();
        $usertype = $session->get('usertype');
        $not_updated_webhook_details = $Common_model->fetch_data_for_razorpay_webhook_transactions('razorpay_payment_transactions', $start_date, $end_date);
        //echo "<pre>";
        //print_r($not_updated_webhook_details);
        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        if (!empty($not_updated_webhook_details)) {
            foreach ($not_updated_webhook_details as $orders_id_value) {

                $order_id =  $orders_id_value->order_id;
                $userid =  $orders_id_value->userid;
                $p_status = $orders_id_value->payment_status;
                $pay_status = ($p_status == "paid") ? "captured" : $p_status;
                if ($pay_status == "captured") {
                    $invoice_no = $this->get_invoice_no();
                }
                $where_history = array('userid' => $userid, 'order_id' => $order_id);
                $data_history = [
                    'payment_id' => $orders_id_value->payment_id,
                    'payment_date' => $orders_id_value->payment_date,
                    'payment_amount' => $orders_id_value->payment_amount,
                    'payment_status' => $pay_status,
                    'payment_method' => $orders_id_value->payment_method,
                    'card_id' => $orders_id_value->card_id,
                    'bank' => $orders_id_value->bank,
                    'wallet' => $orders_id_value->wallet,
                    'vpa' => $orders_id_value->vpa,
                    'captured' => $orders_id_value->captured,
                    'error_code' => $orders_id_value->error_code,
                    'error_description' => $orders_id_value->error_description,
                    'error_source' => $orders_id_value->error_source,
                    'error_reason' => $orders_id_value->error_reason,
                    'invoice_no' => $invoice_no,
                    'create_mode' => "3"
                ];
                //'created_at' => $current_datetime
                $Common_model->update_commen('can_payment_details_history', $where_history, $data_history);

                if ($pay_status == "captured") {
                    $p_dates = $orders_id_value->payment_date;
                    $payment_ex_date = date("Y-m-d", strtotime($p_dates));
                    $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
                    $payment_amount = $orders_id_value->payment_amount;
                    if ($payment_amount == '799') {
                        $payment_package_type = 1;
                    }
                    if ($payment_amount == '899') {
                        $payment_package_type = 2;
                    }
                    if ($payment_amount == '2999') {
                        $payment_package_type = 3;
                    }
                    $where = array('userid' => $userid);
                    $data_ins = [
                        'payment_status' => "1",
                        'payment_amount' => $orders_id_value->payment_amount,
                        'payment_date' => $orders_id_value->payment_date,
                        'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                        'payment_id' => $orders_id_value->payment_id,
                        'payment_package_type' => $payment_package_type
                    ];
                    $Common_model->update_commen('can_personal_details', $where, $data_ins);

                    $data_inv = [
                        'userid' => $userid,
                        'invoice_no' => $invoice_no,
                        'merchant_order_id' => $orders_id_value->merchant_order_id,
                        'merchant_txn_id' => $orders_id_value->merchant_txn_id,
                        'order_id' => $order_id,
                        'payment_id' => $orders_id_value->payment_id,
                        'payment_date' => $orders_id_value->payment_date,
                        'payment_amount' => $orders_id_value->payment_amount,
                        'payment_status' => $pay_status,
                        'payment_method' => $orders_id_value->payment_method,
                        'payment_wallet' => $orders_id_value->wallet,
                        'created_at' => $current_datetime
                    ];
                    $Common_model->insert_commen('invoice_details', $data_inv);
                    echo " User ID: " . $userid . " --- " . " Order ID: " . $order_id . " --- Status: " . $pay_status . " Updated...<br/>";
                }
                $wherer = array('order_id' => $order_id);
                $data_insr = ['raz_status' => "1"];
                $Common_model->update_commen('razorpay_payment_transactions', $wherer, $data_insr);
            }
        } else {
            echo "No records found...";
        }
    }
    public function razorpay_status_update()
    {
        // past 7 days 
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        echo "<h3> Result Found From date " . date("d-m-Y", strtotime($start_date)) . " To " . date("d-m-Y", strtotime($end_date)) . "</h3>";

        $session = session();
        $Common_model = new Common_model();
        $usertype = $session->get('usertype');
        $not_updated_transaction_details = $Common_model->fetch_data_for_transaciton_status('can_payment_details_history', $start_date, $end_date);
        echo "<pre>";
        //print_r($not_updated_transaction_details);
        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        if (!empty($not_updated_transaction_details)) {
            foreach ($not_updated_transaction_details as $orders_id_value) {
                $order_id =  $orders_id_value->order_id;
                $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live
                // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
                $all_orders = $api->order->fetch($order_id)->payments();
                $all_orders_arr = $all_orders['items'];
                $Common_model = new Common_model();
                $i = 1;
                foreach ($all_orders_arr as $orders_detail) {
                    $userid = $orders_detail['notes']->user_id;
                    $payment_id = $orders_detail['id'];
                    $p_dates = $orders_detail['created_at'];
                    $payment_date = date("d-m-Y h:i:sa", $p_dates);
                    $payment_amount = $orders_detail['amount'] / 100;
                    if ($orders_detail['status'] == "captured") {
                        $invoice_no = $this->get_invoice_no();
                    } else {
                        $invoice_no = NULL;
                    }
                    if ($i == 1) {
                        $where_history = array('userid' => $userid, 'order_id' => $orders_detail['order_id']);
                        $data_history = [
                            'payment_id' => $payment_id,
                            'payment_date' => $payment_date,
                            'payment_amount' => $payment_amount,
                            'payment_status' => $orders_detail['status'],
                            'payment_method' => $orders_detail['method'],
                            'card_id' => $orders_detail['card_id'],
                            'bank' => $orders_detail['bank'],
                            'wallet' => $orders_detail['wallet'],
                            'vpa' => $orders_detail['vpa'],
                            'captured' => $orders_detail['captured'],
                            'error_code' => $orders_detail['error_code'],
                            'error_description' => $orders_detail['error_description'],
                            'error_source' => $orders_detail['error_source'],
                            'error_reason' => $orders_detail['error_reason'],
                            'invoice_no' => $invoice_no,
                            'create_mode' => "2"
                        ];
                        //'created_at' => $current_datetime
                        $Common_model->update_commen('can_payment_details_history', $where_history, $data_history);

                        if ($orders_detail['status'] == "captured") {

                            $payment_ex_date = date("Y-m-d", $p_dates);
                            $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));

                            if ($payment_amount == '799') {
                                $payment_package_type = 1;
                            }
                            if ($payment_amount == '899') {
                                $payment_package_type = 2;
                            }
                            if ($payment_amount == '2999') {
                                $payment_package_type = 3;
                            }
                            $where = array('userid' => $userid);
                            $data_ins = [
                                'payment_status' => "1",
                                'payment_amount' => $payment_amount,
                                'payment_date' => $payment_date,
                                'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                                'payment_id' => $payment_id,
                                'payment_package_type' => $payment_package_type
                            ];
                            $Common_model->update_commen('can_personal_details', $where, $data_ins);

                            $data_inv = [
                                'userid' => $userid,
                                'invoice_no' => $invoice_no,
                                'merchant_order_id' => $orders_detail['notes']->merchant_order_id,
                                'merchant_txn_id' => $orders_detail['notes']->merchant_trans_id,
                                'order_id' => $orders_detail['order_id'],
                                'payment_id' => $payment_id,
                                'payment_date' => $payment_date,
                                'payment_amount' => $payment_amount,
                                'payment_status' => $orders_detail['status'],
                                'payment_method' => $orders_detail['method'],
                                'payment_wallet' => $orders_detail['wallet'],
                                'created_at' => $current_datetime
                            ];
                            $Common_model->insert_commen('invoice_details', $data_inv);
                        }
                        if ($orders_detail['status'] == "captured") {
                            $pay_status = '<h3 style="color:green;">  Payment Captured </h3>';
                        } elseif ($orders_detail['status'] == "failed") {
                            $pay_status = '<h3 style="color:Red;" class="panel-title" ><b> Payment Failed  </b></h3>';
                        } elseif ($orders_detail['status'] == "created") {
                            $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Created  </b></h3>';
                        } elseif ($orders_detail['status'] == "refunded") {
                            $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Refunded  </b></h3>';
                        }
                        echo $i . ") User ID: " . $userid . " --- " . " Order ID: " . $orders_detail['order_id'] . " --- Status: " . $pay_status . "<br/>";
                    } else {
                        $data_history_ins = [
                            'userid' => $userid,
                            'merchant_order_id' => $orders_detail['notes']->merchant_order_id,
                            'merchant_txn_id' => $orders_detail['notes']->merchant_trans_id,
                            'order_id' => $orders_detail['order_id'],
                            'payment_id' => $payment_id,
                            'payment_date' => $payment_date,
                            'payment_amount' => $payment_amount,
                            'payment_status' => $orders_detail['status'],
                            'payment_method' => $orders_detail['method'],
                            'card_id' => $orders_detail['card_id'],
                            'bank' => $orders_detail['bank'],
                            'wallet' => $orders_detail['wallet'],
                            'vpa' => $orders_detail['vpa'],
                            'captured' => $orders_detail['captured'],
                            'error_code' => $orders_detail['error_code'],
                            'error_description' => $orders_detail['error_description'],
                            'error_source' => $orders_detail['error_source'],
                            'error_reason' => $orders_detail['error_reason'],
                            'invoice_no' => $invoice_no,
                            'create_mode' => "2",
                            'created_at' => $current_datetime
                        ];
                        $Common_model->insert_commen('can_payment_details_history', $data_history_ins);

                        if ($orders_detail['status'] == "captured") {
                            $payment_ex_date = date("Y-m-d", $p_dates);
                            $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
                            if ($payment_amount == '799') {
                                $payment_package_type = 1;
                            }
                            if ($payment_amount == '899') {
                                $payment_package_type = 2;
                            }
                            if ($payment_amount == '2999') {
                                $payment_package_type = 3;
                            }

                            $where = array('userid' => $userid);
                            $data_ins = [
                                'payment_status' => "1",
                                'payment_amount' => $payment_amount,
                                'payment_date' => $payment_date,
                                'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                                'payment_id' => $payment_id,
                                'payment_package_type' => $payment_package_type
                            ];
                            $Common_model->update_commen('can_personal_details', $where, $data_ins);

                            $data_inv = [
                                'userid' => $userid,
                                'invoice_no' => $invoice_no,
                                'merchant_order_id' => $orders_detail['notes']->merchant_order_id,
                                'merchant_txn_id' => $orders_detail['notes']->merchant_trans_id,
                                'order_id' => $orders_detail['order_id'],
                                'payment_id' => $payment_id,
                                'payment_date' => $payment_date,
                                'payment_amount' => $payment_amount,
                                'payment_status' => $orders_detail['status'],
                                'payment_method' => $orders_detail['method'],
                                'payment_wallet' => $orders_detail['wallet'],
                                'created_at' => $current_datetime
                            ];
                            $Common_model->insert_commen('invoice_details', $data_inv);
                        }
                        if ($orders_detail['status'] == "captured") {
                            $pay_status = '<h3 style="color:green;">  Payment Captured </h3>';
                        } elseif ($orders_detail['status'] == "failed") {
                            $pay_status = '<h3 style="color:Red;" class="panel-title" ><b> Payment Failed  </b></h3>';
                        } elseif ($orders_detail['status'] == "created") {
                            $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Created  </b></h3>';
                        } elseif ($orders_detail['status'] == "refunded") {
                            $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Refunded  </b></h3>';
                        }
                        echo $i . ") User ID: " . $userid . " --- " . " Order ID: " . $orders_detail['order_id'] . " --- Status: " . $pay_status . "<br/>";
                    }
                    $i++;
                }
            }
        } else {
            echo "No records found...";
        }
        exit();
        return redirect()->to('razorpay-status');
    }
    public function failed_status_update()
    {
        // past 7 days 
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        echo "<h3> Result Found From date " . date("d-m-Y", strtotime($start_date)) . " To " . date("d-m-Y", strtotime($end_date)) . "</h3>";

        $session = session();
        $Common_model = new Common_model();
        $usertype = $session->get('usertype');
        $not_updated_transaction_details = $Common_model->fetch_data_for_failed_transaciton_status('can_payment_details_history', $start_date, $end_date);
        echo "<pre>";
        //print_r($not_updated_transaction_details);
        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        if (!empty($not_updated_transaction_details)) {
            foreach ($not_updated_transaction_details as $orders_id_value) {
                $order_id =  $orders_id_value->order_id;
                $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live 
                // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
                $all_orders = $api->order->fetch($order_id)->payments();
                $all_orders_arr = array_reverse($all_orders['items']);
                $Common_model = new Common_model();
                $i = 1;
                foreach ($all_orders_arr as $orders_detail) {
                    $userid = $orders_detail['notes']->user_id;
                    $payment_id = $orders_detail['id'];
                    $p_dates = $orders_detail['created_at'];
                    $payment_date = date("d-m-Y h:i:sa", $p_dates);
                    $payment_amount = $orders_detail['amount'] / 100;
                    if ($orders_detail['status'] == "captured") {
                        $invoice_no = $this->get_invoice_no();
                    } else {
                        $invoice_no = NULL;
                    }
                    if ($i == 1) {
                        $where_history = array('userid' => $userid, 'order_id' => $orders_detail['order_id']);
                        $data_history = [
                            'payment_id' => $payment_id,
                            'payment_date' => $payment_date,
                            'payment_amount' => $payment_amount,
                            'payment_status' => $orders_detail['status'],
                            'payment_method' => $orders_detail['method'],
                            'card_id' => $orders_detail['card_id'],
                            'bank' => $orders_detail['bank'],
                            'wallet' => $orders_detail['wallet'],
                            'vpa' => $orders_detail['vpa'],
                            'captured' => $orders_detail['captured'],
                            'error_code' => $orders_detail['error_code'],
                            'error_description' => $orders_detail['error_description'],
                            'error_source' => $orders_detail['error_source'],
                            'error_reason' => $orders_detail['error_reason'],
                            'invoice_no' => $invoice_no,
                            'create_mode' => "2"
                        ];
                        //'created_at' => $current_datetime
                        $Common_model->update_commen('can_payment_details_history', $where_history, $data_history);

                        if ($orders_detail['status'] == "captured") {
                            $payment_ex_date = date("Y-m-d", $p_dates);
                            $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
                           
                            if ($payment_amount == '799') {
                                $payment_package_type = 1;
                            }
                            if ($payment_amount == '899') {
                                $payment_package_type = 2;
                            }
                            if ($payment_amount == '2999') {
                                $payment_package_type = 3;
                            }
                            $where = array('userid' => $userid);
                            $data_ins = [
                                'payment_status' => "1",
                                'payment_amount' => $payment_amount,
                                'payment_date' => $payment_date,
                                'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                                'payment_id' => $payment_id,
                                'payment_package_type' => $payment_package_type
                            ];
                            $Common_model->update_commen('can_personal_details', $where, $data_ins);

                            $data_inv = [
                                'userid' => $userid,
                                'invoice_no' => $invoice_no,
                                'merchant_order_id' => $orders_detail['notes']->merchant_order_id,
                                'merchant_txn_id' => $orders_detail['notes']->merchant_trans_id,
                                'order_id' => $orders_detail['order_id'],
                                'payment_id' => $payment_id,
                                'payment_date' => $payment_date,
                                'payment_amount' => $payment_amount,
                                'payment_status' => $orders_detail['status'],
                                'payment_method' => $orders_detail['method'],
                                'payment_wallet' => $orders_detail['wallet'],
                                'created_at' => $current_datetime
                            ];
                            $Common_model->insert_commen('invoice_details', $data_inv);
                        }
                        if ($orders_detail['status'] == "captured") {
                            $pay_status = '<h3 style="color:green;">  Payment Captured </h3>';
                        } elseif ($orders_detail['status'] == "failed") {
                            $pay_status = '<h3 style="color:Red;" class="panel-title" ><b> Payment Failed  </b></h3>';
                        } elseif ($orders_detail['status'] == "created") {
                            $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Created  </b></h3>';
                        }
                        echo $i . ") User ID: " . $userid . " --- " . " Order ID: " . $orders_detail['order_id'] . " --- Status: " . $pay_status . "<br/>";
                    } else {
                        $data_history_ins = [
                            'userid' => $userid,
                            'merchant_order_id' => $orders_detail['notes']->merchant_order_id,
                            'merchant_txn_id' => $orders_detail['notes']->merchant_trans_id,
                            'order_id' => $orders_detail['order_id'],
                            'payment_id' => $payment_id,
                            'payment_date' => $payment_date,
                            'payment_amount' => $payment_amount,
                            'payment_status' => $orders_detail['status'],
                            'payment_method' => $orders_detail['method'],
                            'card_id' => $orders_detail['card_id'],
                            'bank' => $orders_detail['bank'],
                            'wallet' => $orders_detail['wallet'],
                            'vpa' => $orders_detail['vpa'],
                            'captured' => $orders_detail['captured'],
                            'error_code' => $orders_detail['error_code'],
                            'error_description' => $orders_detail['error_description'],
                            'error_source' => $orders_detail['error_source'],
                            'error_reason' => $orders_detail['error_reason'],
                            'invoice_no' => $invoice_no,
                            'create_mode' => "2",
                            'created_at' => $current_datetime
                        ];
                        $Common_model->insert_commen('can_payment_details_history', $data_history_ins);

                        if ($orders_detail['status'] == "captured") {
                            $payment_ex_date = date("Y-m-d", $p_dates);
                            $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
                           
                            if ($payment_amount == '799') {
                                $payment_package_type = 1;
                            }
                            if ($payment_amount == '899') {
                                $payment_package_type = 2;
                            }
                            if ($payment_amount == '2999') {
                                $payment_package_type = 3;
                            }
                            $where = array('userid' => $userid);
                            $data_ins = [
                                'payment_status' => "1",
                                'payment_amount' => $payment_amount,
                                'payment_date' => $payment_date,
                                'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                                'payment_id' => $payment_id,
                                'payment_package_type' => $payment_package_type
                            ];
                            $Common_model->update_commen('can_personal_details', $where, $data_ins);

                            $data_inv = [
                                'userid' => $userid,
                                'invoice_no' => $invoice_no,
                                'merchant_order_id' => $orders_detail['notes']->merchant_order_id,
                                'merchant_txn_id' => $orders_detail['notes']->merchant_trans_id,
                                'order_id' => $orders_detail['order_id'],
                                'payment_id' => $payment_id,
                                'payment_date' => $payment_date,
                                'payment_amount' => $payment_amount,
                                'payment_status' => $orders_detail['status'],
                                'payment_method' => $orders_detail['method'],
                                'payment_wallet' => $orders_detail['wallet'],
                                'created_at' => $current_datetime
                            ];
                            $Common_model->insert_commen('invoice_details', $data_inv);
                        }
                        if ($orders_detail['status'] == "captured") {
                            $pay_status = '<h3 style="color:green;">  Payment Captured </h3>';
                        } elseif ($orders_detail['status'] == "failed") {
                            $pay_status = '<h3 style="color:Red;" class="panel-title" ><b> Payment Failed  </b></h3>';
                        } elseif ($orders_detail['status'] == "created") {
                            $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Created  </b></h3>';
                        }
                        echo $i . ") User ID: " . $userid . " --- " . " Order ID: " . $orders_detail['order_id'] . " --- Status: " . $pay_status . "<br/>";
                    }
                    $i++;
                }
            }
        } else {
            echo "No records found...";
        }
        exit();
        return redirect()->to('razorpay-status');
    }
    public function refund_status_update()
    {
        // past 7 days 
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        echo "<h3> Result Found From date " . date("d-m-Y", strtotime($start_date)) . " To " . date("d-m-Y", strtotime($end_date)) . "</h3>";

        $session = session();
        $Common_model = new Common_model();
        $usertype = $session->get('usertype');
        $auto_updated_refund_details = $Common_model->fetch_data_for_refund_transaciton_status('can_payment_details_history', $start_date, $end_date);
        //echo "<pre>";
        //print_r($auto_updated_refund_details);
        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        if (!empty($auto_updated_refund_details)) {
            foreach ($auto_updated_refund_details as $payment_id_value) {
                $paymentId =  $payment_id_value->payment_id;
                $order_id =  $payment_id_value->order_id;
                $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live
                // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
                $Common_model = new Common_model();
                $i = 1;
                $paymentid_arr = array('payment_id' => $paymentId);
                $all = $api->payment->refund($paymentid_arr);
                $all_orders = $api->order->fetch($order_id)->payments();
                foreach ($all_orders['items'] as $orders_detail) {
                    $userid = $orders_detail['notes']->user_id;
                    if ($orders_detail['status'] == "refunded") {
                        $allrefund = $api->payment->refunds($orders_detail['id']);
                        $where_history = array('payment_id' => $orders_detail['id'], 'order_id' => $orders_detail['order_id']);
                        $data_history = [
                            'payment_status' => $orders_detail['status'],
                            'refund_id' => $allrefund['items'][0]['id'],
                            'refund_amount' => $orders_detail['amount_refunded'] / 100,
                            'refund_date' => $current_datetime,
                            'refund_ref_no' => $orders_detail['refund_status'],
                            'refund_initiate' => '2',
                            'create_mode' => "2"
                        ];
                        $Common_model->update_commen('can_payment_details_history', $where_history, $data_history);
                    }

                    if ($orders_detail['status'] == "captured") {
                        $pay_status = '<h3 style="color:green;">  Payment Captured </h3>';
                    } elseif ($orders_detail['status'] == "failed") {
                        $pay_status = '<h3 style="color:Red;" class="panel-title" ><b> Payment Failed  </b></h3>';
                    } elseif ($orders_detail['status'] == "created") {
                        $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Created  </b></h3>';
                    } elseif ($orders_detail['status'] == "refunded") {
                        $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Refunded  </b></h3>';
                    }
                    echo $i . ") User ID: " . $userid . " --- " . " Order ID: " . $order_id . " --- Status: " . $pay_status . "<br/>";
                    $i++;
                }
            }
        } else {
            echo "No records found...";
        }
        exit();
        return redirect()->to('razorpay-status');
    }

    function get_invoice_no()
    {
        $Common_model = new Common_model();
        $getInvoiceid = $Common_model->get_invoice_id();
        $month = date("m");
        $year = date("y");
        if (empty($getInvoiceid)) {
            $fin_no = sprintf("%07d", 1);
            $invoice_no = "INTERN" . $month . $year . $fin_no;
            return $invoice_no;
        } else {
            $fin_no = sprintf("%07d", $getInvoiceid[0]->id + 1);
            $invoice_no = "INTERN" . $month . $year . $fin_no;
            return $invoice_no;
        }
    }


    public function phonepe_webhook_update()
    {
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        echo "<h3> Result Found From date " . date("d-m-Y", strtotime($start_date)) . " To " . date("d-m-Y", strtotime($end_date)) . "</h3>";

        $session = session();
        $Common_model = new Common_model();
        $usertype = $session->get('usertype');
        $not_updated_webhook_details = $Common_model->fetch_data_for_razorpay_webhook_transactions('s2s_payment_details_history_phonepe', $start_date, $end_date);
        //echo "<pre>";
        // print_r($not_updated_webhook_details);exit;
        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        if (!empty($not_updated_webhook_details)) {
            foreach ($not_updated_webhook_details as $orders_id_value) {

                $order_id =  $orders_id_value->order_id;
                $responce =  $orders_id_value->json;
                $where_can = array('order_id' => $order_id);
                $profile_personal = $Common_model->fetch_table_row('can_payment_details_history_phonepe', $where_can);
                // print_r($profile_personal);exit;
                $responseData = json_decode($responce, true);
                // $userid =  $orders_id_value->userid;
                $p_status = $orders_id_value->payment_status;
                // $pay_status = ($p_status=="paid")?"captured":$p_status;
                $where_can1 = array('order_id' => $order_id);
                $inv_data = $Common_model->fetch_table_data('invoice_details', $where_can1);
        //   print_r($inv_data);exit;
                    // if ($p_status == "PAYMENT_SUCCESS" && isset($inv_data)) {
                    //     $invoice_no = $this->get_invoice_no();
                    // }else if($p_status == "PAYMENT_SUCCESS" && !empty($inv_data)){
                    //     $invoice_no =$inv_data[0]->invoice_no;
                    // }else{
                    //     $invoice_no ='';
                    // }
             
                    if ($p_status === "PAYMENT_SUCCESS") {
                        $invoice_no = !empty($inv_data) ? $inv_data[0]->invoice_no : $this->get_invoice_no();
                    } else {
                        $invoice_no = '';
                    }
                    
                $where_history = array('order_id' => $order_id);
                // print_r($responseData);exit;
                $data_history = [
                    'payment_id' => $orders_id_value->payment_id,
                    'payment_date' => $orders_id_value->payment_date,
                    'payment_amount' => $orders_id_value->payment_amount,
                    'payment_status' => $p_status,
                    'payment_method' => $orders_id_value->payment_method,
                    'utr' => (!empty($responseData['data']['paymentInstrument']['utr'])) ? $responseData['data']['paymentInstrument']['utr'] : "",
                    'cardType' => (!empty($responseData['data']['paymentInstrument']['cardType'])) ? $responseData['data']['paymentInstrument']['cardType'] : "",
                    'pgTransactionId' => (!empty($responseData['data']['paymentInstrument']['pgTransactionId'])) ? $responseData['data']['paymentInstrument']['pgTransactionId'] : "",
                    'bankTransactionId' => (!empty($responseData['data']['paymentInstrument']['bankTransactionId'])) ? $responseData['data']['paymentInstrument']['bankTransactionId'] : "",
                    'pgAuthorizationCode' => (!empty($responseData['data']['paymentInstrument']['pgAuthorizationCode'])) ? $responseData['data']['paymentInstrument']['pgAuthorizationCode'] : "",
                    'arn' => (!empty($responseData['data']['paymentInstrument']['arn'])) ? $responseData['data']['paymentInstrument']['arn'] : "",
                    'bankId' => (!empty($responseData['data']['paymentInstrument']['bankId'])) ? $responseData['data']['paymentInstrument']['bankId'] : "",
                    'pgServiceTransactionId' => (!empty($responseData['data']['paymentInstrument']['pgServiceTransactionId'])) ? $responseData['data']['paymentInstrument']['pgServiceTransactionId'] : "",
                    'message' => $responseData['message'],
                    'state' => $responseData['data']['state'],
                    'responseCode' => $responseData['data']['responseCode'],
                    'invoice_no' => $invoice_no,
                    'create_mode' => "3"
                ];
                //'created_at' => $current_datetime
                $Common_model->update_commen('can_payment_details_history_phonepe', $where_history, $data_history);

                if ($p_status == "PAYMENT_SUCCESS") {
                    $p_dates = $orders_id_value->payment_date;
                    $payment_ex_date = date("Y-m-d", strtotime($p_dates));
                    $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
                    $payment_amount = $orders_id_value->payment_amount;
                   
                    if ($payment_amount == '799') {
                        $payment_package_type = 1;
                    }
                    if ($payment_amount == '899') {
                        $payment_package_type = 2;
                    }
                    if ($payment_amount == '2999') {
                        $payment_package_type = 3;
                    }

// print_r($payment_amount);exit;
                    $where = array('userid' => $profile_personal->userid);
                    $data_ins = [
                        'payment_status' => "1",
                        'payment_amount' => $orders_id_value->payment_amount,
                        'payment_date' => $orders_id_value->payment_date,
                        'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                        'payment_id' => $orders_id_value->payment_id,
                        'payment_package_type' => $payment_package_type
                    ];
                    $Common_model->update_commen('can_personal_details', $where, $data_ins);
                   

                    if(empty($inv_data)){
                    $data_inv = [
                        'userid' => $profile_personal->userid,
                        'invoice_no' => $invoice_no,
                        'merchant_order_id' => $orders_id_value->order_id,
                        'merchant_txn_id' => $orders_id_value->payment_id,
                        'order_id' => $order_id,
                        'payment_id' => $orders_id_value->payment_id,
                        'payment_date' => $orders_id_value->payment_date,
                        'payment_amount' => $orders_id_value->payment_amount,
                        'payment_status' => $p_status,
                        'payment_method' => $orders_id_value->payment_method,
                        // 'payment_wallet' => $orders_id_value->wallet,
                        'created_at' => $current_datetime
                    ];
                    $Common_model->insert_commen('invoice_details', $data_inv);
                }else{
                    $data_inv_update = [
                    
                        'merchant_txn_id' => $orders_id_value->payment_id,
                        'payment_id' => $orders_id_value->payment_id,
                        'payment_date' => $orders_id_value->payment_date,
                        'payment_amount' => $orders_id_value->payment_amount,
                        'payment_status' => $p_status,
                        'payment_method' => $orders_id_value->payment_method,
                       
                    ];
                    $where_can1 = array('order_id' => $order_id);
                    $Common_model->update_commen('invoice_details', $where_can1, $data_inv_update);
                }
                    echo " User ID: " . $profile_personal->userid . " --- " . " Order ID: " . $order_id . " --- Status: " . $p_status . " Updated...<br/>";
                }
                $wherer = array('order_id' => $order_id);
                $data_insr = ['raz_status' => "1"];
                $Common_model->update_commen('s2s_payment_details_history_phonepe', $wherer, $data_insr);
            }
        } else {
            echo "No records found...";
        }
    }

    public function phonepe_status_update()
    {
        // past 7 days 
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        echo "<h3> Result Found From date " . date("d-m-Y", strtotime($start_date)) . " To " . date("d-m-Y", strtotime($end_date)) . "</h3>";

        $session = session();
        $Common_model = new Common_model();
        $usertype = $session->get('usertype');
        $not_updated_transaction_details = $Common_model->fetch_data_for_transaciton_status_phonepe('can_payment_details_history_phonepe', $start_date, $end_date);
        echo "<pre>";
        // print_r($not_updated_transaction_details);exit;
        $client = new Client();
        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        if (!empty($not_updated_transaction_details)) {
            foreach ($not_updated_transaction_details as $orders_id_value) {
                $order_id =  $orders_id_value->order_id;
                $userid =  $orders_id_value->userid;

                //   $merchantId = 'PGTESTPAYUAT93'; //testing
                $merchantId = 'UNWINDONLINE'; //live
                // $merchantId = 'UNWINDONLINEUAT'; //staging



                //    $saltKey = '875126e4-5a13-4dae-ad60-5b8c8b629035'; // Replace with your actual salt key testing
                $saltKey = '1d8fd59d-5d46-4d01-8303-f274e1b7799d'; // Replace with your actual salt key live
                //    $saltKey = 'c6cae9ff-9bfe-407f-9efb-31904cea5afa'; // Replace with your actual salt key staging
                $saltIndex = '1';

                // Construct the string to be hashed
                $stringToHash = "/pg/v1/status/" . $merchantId . "/" . $order_id . $saltKey;

                // Calculate the SHA-256 hash
                $sha256Hash = hash('sha256', $stringToHash);

                // Combine the hash with "###" and salt index
                $finalString = $sha256Hash . '###' . $saltIndex;
                $response = $client->request('GET', 'https://api.phonepe.com/apis/hermes/pg/v1/status/' . $merchantId . '/' . $order_id, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-VERIFY' => $finalString,
                        'X-MERCHANT-ID' => $merchantId,
                        'Accept' => 'application/json',
                    ],
                    // 'verify' => false,
                ]);
                $responseBody = $response->getBody()->getContents();

                $responseData = json_decode($responseBody, true);

                // print_r($responseData);exit;

                $Common_model = new Common_model();
                $i = 1;

                $payment_id = $responseData['data']['transactionId'];
                $p_dates = strtotime($current_datetime);
                $payment_date = date("d-m-Y h:i:sa", $p_dates);
                $payment_amount = $responseData['data']['amount'] / 100;
                $payment_ex_date = date("Y-m-d", $p_dates);
                $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
                $paid_amount = '';

                if ($responseData['code'] == "PAYMENT_SUCCESS") {
                    $invoice_no = $this->get_invoice_no();
                } else {
                    $invoice_no = NULL;
                }


                $where_history = array('userid' => $userid, 'order_id' => $order_id);
                $data_history = [
                    'payment_id' => $payment_id,
                    'payment_date' => $payment_date,
                    'payment_amount' => $payment_amount,
                    'payment_status' => $responseData['code'],
                    'payment_method' => $responseData['data']['paymentInstrument']['type'],
                    'utr' => (!empty($responseData['data']['paymentInstrument']['utr'])) ? $responseData['data']['paymentInstrument']['utr'] : "",
                    'cardType' => (!empty($responseData['data']['paymentInstrument']['cardType'])) ? $responseData['data']['paymentInstrument']['cardType'] : "",
                    'pgTransactionId' => (!empty($responseData['data']['paymentInstrument']['pgTransactionId'])) ? $responseData['data']['paymentInstrument']['pgTransactionId'] : "",
                    'bankTransactionId' => (!empty($responseData['data']['paymentInstrument']['bankTransactionId'])) ? $responseData['data']['paymentInstrument']['bankTransactionId'] : "",
                    'pgAuthorizationCode' => (!empty($responseData['data']['paymentInstrument']['pgAuthorizationCode'])) ? $responseData['data']['paymentInstrument']['pgAuthorizationCode'] : "",
                    'arn' => (!empty($responseData['data']['paymentInstrument']['arn'])) ? $responseData['data']['paymentInstrument']['arn'] : "",
                    'bankId' => (!empty($responseData['data']['paymentInstrument']['bankId'])) ? $responseData['data']['paymentInstrument']['bankId'] : "",
                    'pgServiceTransactionId' => (!empty($responseData['data']['paymentInstrument']['pgServiceTransactionId'])) ? $responseData['data']['paymentInstrument']['pgServiceTransactionId'] : "",
                    'message' => $responseData['message'],
                    'state' => $responseData['data']['state'],
                    'responseCode' => $responseData['data']['responseCode'],
                    'create_mode' => 2,
                    'invoice_no' => $invoice_no,
                ];
                //'created_at' => $current_datetime
                $Common_model->update_commen('can_payment_details_history_phonepe', $where_history, $data_history);

                if ($responseData['code'] == "PAYMENT_SUCCESS") {

                    $payment_ex_date = date("Y-m-d", $p_dates);
                    $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));

                    if ($payment_amount == '799') {
                        $payment_package_type = 1;
                    }
                    if ($payment_amount == '899') {
                        $payment_package_type = 2;
                    }
                    if ($payment_amount == '2999') {
                        $payment_package_type = 3;
                    }
                    $where = array('userid' => $userid);
                    $data_ins = [
                        'payment_status' => "1",
                        'payment_amount' => $payment_amount,
                        'payment_date' => $payment_date,
                        'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                        'payment_id' => $payment_id,
                        'payment_package_type' => $payment_package_type
                    ];
                    $Common_model->update_commen('can_personal_details', $where, $data_ins);

                    $data_inv = [
                        'userid' => $userid,
                        'invoice_no' => $invoice_no,
                        'merchant_order_id' => $responseData['data']['merchantTransactionId'],
                        'merchant_txn_id' => $payment_id,
                        'order_id' => $responseData['data']['merchantTransactionId'],
                        'payment_id' => $payment_id,
                        'payment_date' => $payment_date,
                        'payment_amount' => $payment_amount,
                        'payment_status' => $responseData['code'],
                        'payment_method' =>  $responseData['data']['paymentInstrument']['type'],
                        // 'payment_wallet' => $order->wallet,
                        'created_at' => $current_datetime
                    ];
                    $Common_model->insert_commen('invoice_details', $data_inv);
                }
                if ($responseData['code'] == "PAYMENT_SUCCESS") {
                    $pay_status = '<h3 style="color:green;">  Payment Captured </h3>';
                } elseif ($responseData['code'] == "PAYMENT_ERROR") {
                    $pay_status = '<h3 style="color:Red;" class="panel-title" ><b> Payment Failed  </b></h3>';
                } elseif ($responseData['code'] == "created") {
                    $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Created  </b></h3>';
                } elseif ($responseData['code'] == "PAYMENT_PENDING") {
                    $pay_status = '<h3 style="color:black;" class="panel-title" ><b> Payment Refunded  </b></h3>';
                }
                echo $i . ") User ID: " . $userid . " --- " . " Order ID: " . $order_id . " --- Status: " . $pay_status . "<br/>";
            }
        } else {
            echo "No records found...";
        }
        exit();
        return redirect()->to('razorpay-status');
    }

    public function phonepe_refund_status_update()
    {
        $client = new Client();
        // past 7 days 
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        echo "<h3> Result Found From date " . date("d-m-Y", strtotime($start_date)) . " To " . date("d-m-Y", strtotime($end_date)) . "</h3>";

        $session = session();
        $Common_model = new Common_model();
        $usertype = $session->get('usertype');
        $auto_updated_refund_details = $Common_model->fetch_data_for_refund_transaciton_status_phonepe('can_payment_details_history_phonepe', $start_date, $end_date);
        //echo "<pre>";
        // print_r($auto_updated_refund_details);exit;
        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        $i = 1;
        if (!empty($auto_updated_refund_details)) {
            foreach ($auto_updated_refund_details as $payment_id_value) {
                $paymentId =  $payment_id_value->refund_payment_id;
                $order_id =  $payment_id_value->refund_order_id;
                $userid =  $payment_id_value->userid;
                
                $merchantId = 'UNWINDONLINE'; //live
                // $merchantId = 'UNWINDONLINEUAT'; //staging



                //    $saltKey = '875126e4-5a13-4dae-ad60-5b8c8b629035'; // Replace with your actual salt key testing
                   $saltKey = '1d8fd59d-5d46-4d01-8303-f274e1b7799d'; // Replace with your actual salt key live
                // $saltKey = 'c6cae9ff-9bfe-407f-9efb-31904cea5afa'; // Replace with your actual salt key staging
                $saltIndex = '1';

                // Construct the string to be hashed
                $stringToHash = "/pg/v1/status/" . $merchantId . "/" . $order_id . $saltKey;

                // Calculate the SHA-256 hash
                $sha256Hash = hash('sha256', $stringToHash);

                // Combine the hash with "###" and salt index
                $finalString = $sha256Hash . '###' . $saltIndex;
                $response = $client->request('GET', 'https://api.phonepe.com/apis/hermes/pg/v1/status/' . $merchantId . '/' . $order_id, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-VERIFY' => $finalString,
                        'X-MERCHANT-ID'=> $merchantId,
                        'Accept' => 'application/json',
                    ],
                    // 'verify' => false,
                ]);
                $responseBody = $response->getBody()->getContents();

                $responseData = json_decode($responseBody, true);
                // print_r($responseData);
                // exit;



                if ($responseData['code'] == "PAYMENT_SUCCESS") {
                    $where_history = array('refund_order_id' => $order_id);
                    $data_history = [
                        'refund_status' => $responseData['code'],
                        'refund_message' => $responseData['message'],
                        'refund_state' => $responseData['data']['state'],
                        'refund_responseCode' => $responseData['data']['responseCode'],
                    ];
                    $his_result = $Common_model->update_commen('can_payment_details_history_phonepe', $where_history, $data_history);
                }
                echo $i . ") User ID: " . $userid . " --- " . " Order ID: " . $order_id . " --- Status: " . $responseData['code'] . "<br/>";
            }
        } else {
            echo "No records found...";
        }
        exit();
        return redirect()->to('razorpay-status');
    }
}
