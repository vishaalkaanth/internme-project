<!DOCTYPE html>
<html>

<?php
$session = session();
//print_r($_SESSION);
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");

?>
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"> -->
<body style="background: #232328;">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>
    <?php
        $description        = "Internme Subscription";
        $txnid              =  $merchant_txn_id;     
        //$key_id             = "rzp_live_U5JHzi0BdrUlj9"; // live
        $key_id             = "rzp_test_KzdAgSM1LXXPHN";   //test
        $currency_code      = $currency_code;            
        $total              = (1* 100); // 100 = 1 indian rupees
        $amount             = 1;
        //$merchant_order_id  = "INTERNME-".date("YmdHis");
        $card_holder_name   = $profile_personal->profile_full_name;
        $email              = $profile_personal->profile_email;
        $phone              = $profile_personal->profile_phone_number;
        $name               = $profile_personal->profile_full_name;
    ?>
    <section class="canDashboard py-md-5 py-4 d-flex align-items-center justify-content-center" style="height:90vh">
    <form name="razorpay-form" id="razorpay-form" action="<?php echo $callback_url; ?>" method="POST">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" />
        <input type="hidden" name="merchant_order_id" id="merchant_order_id" value="<?php echo $merchant_order_id; ?>"/>
        <input type="hidden" name="merchant_trans_id" id="merchant_trans_id" value="<?php echo $txnid; ?>"/>
        <input type="hidden" name="merchant_product_info_id" id="merchant_product_info_id" value="<?php echo $description; ?>"/>
        <input type="hidden" name="merchant_surl_id" id="merchant_surl_id" value="<?php echo $surl; ?>"/>
        <input type="hidden" name="merchant_furl_id" id="merchant_furl_id" value="<?php echo $furl; ?>"/>
        <input type="hidden" name="card_holder_name_id" id="card_holder_name_id" value="<?php echo $card_holder_name; ?>"/>
        <input type="hidden" name="merchant_total" id="merchant_total" value="<?php echo $total; ?>"/>
        <input type="hidden" name="merchant_amount" id="merchant_amount" value="<?php echo $amount; ?>"/>
    </form>   
    <div class="container">
            <div class="col-lg-5 col-sm-12 col-md-6 mx-auto d-flex bg-subscribe flex-column justify-content-center">
                <span class="subs_title align-self-center px-4">Internme Subscription</span>
                <h3 class="text-green my-5">Key Features</h3>
                <ul class="text-white mx-auto d-block ps-0">
                    <li>Get upto 3X attention from recruiters</li>
                    <li>Rank higher in recruiter's search</li>
                    <li>Increase chance of getting shortlisted</li>
                </ul>
                <div class="subs_hor_line_parent">
                    <hr class="subs_hor_line">
                </div>
                <div class="d-flex justify-content-between align-items-center px-5 pb-4">
                    <span class="fs-3 text-white">&#8377; <?php echo $amount; ?> /yr</span>
                    <button class="btn btn-green py-2 px-4 fs-5 fw-bold subs_btn" onclick="razorpaySubmit(this);">Buy Now</button>
                </div>

            </div>
        </div>
    </section>

<?php require_once(APPPATH . "Views/Common/script.php"); ?>
    
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
        key:            "<?php echo $key_id; ?>",
        amount:         "<?php echo $total; ?>",
        name:           "<?php echo $name; ?>",
        description:    "Order # <?php echo $merchant_order_id; ?>",
        netbanking:     true,
        currency:       "<?php echo $currency_code; ?>", // INR
        prefill: {
        name:       "<?php echo $card_holder_name; ?>",
        email:      "<?php echo $email; ?>",
        contact:    "<?php echo $phone; ?>"
        },
        notes: {
            soolegal_order_id: "<?php echo $merchant_order_id; ?>",
        },
        handler: function (transaction) {
            document.getElementById('razorpay_payment_id').value = transaction.razorpay_payment_id;
            document.getElementById('razorpay-form').submit();
        },
        "modal": {
        "ondismiss": function(){
            location.reload()
        }
        }
};
var razorpay_pay_btn, instance;
function razorpaySubmit(el) {
        if(typeof Razorpay == 'undefined') {
            setTimeout(razorpaySubmit, 200);
            if(!razorpay_pay_btn && el) {
                razorpay_pay_btn    = el;
                el.disabled         = true;
                el.value            = 'Please wait...';  
            }
        } else {
            if(!instance) {
                instance = new Razorpay(options);
                if(razorpay_pay_btn) {
                razorpay_pay_btn.disabled   = false;
                razorpay_pay_btn.value      = "Pay Now";
                }
            }
            instance.open();
        }
}  
</script>
   
    
</body>

</html>