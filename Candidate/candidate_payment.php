<!DOCTYPE html>
<html>

<?php
$session = session();
//print_r($_SESSION);
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");

?>
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"> -->

<body class="bg_subscription">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>
    <?php
    $description        = "Internme Subscription";
    $txnid              =  $merchant_txn_id;
    $key_id             = "rzp_live_U5JHzi0BdrUlj9"; // live
    //$key_id             = "rzp_test_KzdAgSM1LXXPHN";   //test
    $currency_code      = $currency_code;
    $total              = (1 * 100); // 100 = 1 indian rupees
    $amount             = $paid_amount;
    //$merchant_order_id  = "INTERNME-".date("YmdHis");
    $card_holder_name   = $profile_personal->profile_full_name;
    $email              = $profile_personal->profile_email;
    $phone              = $profile_personal->profile_phone_number;
    $name               = $profile_personal->profile_full_name;
    ?>
    <!--<section class="canDashboard py-md-5 py-4 d-flex align-items-center justify-content-center" style="height:90vh">
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
                    <button id="rzp-button1" class="btn btn-green py-2 px-4 fs-5 fw-bold subs_btn" onclick="razorpaySubmit(this);">Buy Now</button>
                </div>

            </div>
        </div>
    </section>-->



    <section class="canDashboard py-5 d-flex align-items-start justify-content-center" style="height:90vh">
        <form name="razorpayform" id="razorpayform" action="<?php echo $callback_url; ?>" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" />
            <input type="hidden" name="merchant_order_id" id="merchant_order_id" value="<?php echo $merchant_order_id; ?>" />
            <input type="hidden" name="merchant_trans_id" id="merchant_trans_id" value="<?php echo $txnid; ?>" />
            <input type="hidden" name="merchant_product_info_id" id="merchant_product_info_id" value="<?php echo $description; ?>" />
            <input type="hidden" name="merchant_surl_id" id="merchant_surl_id" value="<?php echo $surl; ?>" />
            <input type="hidden" name="merchant_furl_id" id="merchant_furl_id" value="<?php echo $furl; ?>" />
            <input type="hidden" name="card_holder_name_id" id="card_holder_name_id" value="<?php echo $card_holder_name; ?>" />
            <input type="hidden" name="merchant_total" id="merchant_total" value="<?php echo $total; ?>" />
            <input type="hidden" name="merchant_amount" id="merchant_amount" value="<?php echo $amount; ?>" />
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        </form>
        <div class="container canPayment d-flex flex-wrap flex-column-reverse flex-lg-row">
            <div class="col-12 col-lg-6 mt-5 mt-lg-0">
                <h1 class="fw-bold text-blue mb-4 pe-0">Get 100% Internships with valuable Companies!</h1>
                <div class="border-bottom-green mb-4"></div>
                <h5 class="fw-bold text-blue mt-md-5 mt-0 pt-0 pt-md-3">Hyperlocal platform for college students & corporates</h5>
                <p class="mt-3">Choose from a wide range of opportunities from AI, ML, IOT, core engineering, banking and other opportunities, with internships from leading corporates and 100% verified startups.</p>
                <h5 class="fw-bold text-blue mt-md-3 mt-0">Crazy dreams take crazy effort</h5>
                <ul class="payment_ul_list">
                    <li>100% verified corporates - Building Trust all the way</li>
                    <li>Pre-assessed students - easier for corporates to choose from</li>
                    <li>AI powered internship suggestion engine</li>
                    <li>100% corporate certification (not just our certificate)</li>
                </ul>
                <!-- <h5 class="fw-bold text-blue mt-md-3 mt-0">Share us on :</h5>
                <div class="gap-3 d-flex flex-row mt-3">
                    <span><a><img src="<?= base_url(); ?>/public/assets/img/facebook_ico.svg" alt="Facebok" title="Facebok" class="img-fluid"></a></span>
                    <span><a><img src="<?= base_url(); ?>/public/assets/img/twitter_ico.svg" alt="Twitter" title="Twitter" class="img-fluid"></a></span>
                    <span><a><img src="<?= base_url(); ?>/public/assets/img/whatsapp_ico.svg" alt="Whatsapp" title="Whatsapp" class="img-fluid"></a></span>
                    <span><a><img src="<?= base_url(); ?>/public/assets/img/linkedin_ico.svg" alt="Linkedin" title="Linkedin" class="img-fluid"></a></span>
                </div> -->
                <div class="mt-5">
                    <div><img src="<?= base_url(); ?>/public/assets/img/razorpay.svg" alt="Razorpay logo" title="Razor Pay" class="img-fluid"></div>
                    <p style="color:#00000060"><small>Supercharge your business with the all - powerful Payment Gateway</small></p>
                </div>
            </div>
            <div class="col-12 col-lg-5 col-md-12 offset-lg-1">
                <div class="card p-0" style="border-radius: 0;">
                    <div class="p-4">
                        <h4 class="fw-bold text-blue mb-4 pe-0 pe-md-4">Payment Details</h4>
                        <div class="border-bottom-green mb-5"></div>
                        <dl>
                            <dt>Order ID</dt>
                            <dd><span class="d-sm-inline-block d-none">:</span> <?php echo $order_id; ?></dd>
                            <dt>Date and Time</dt>
                            <dd><span class="d-sm-inline-block d-none">:</span> <?php echo date("d-m-Y h:i:s\ta", strtotime($current_date)); ?></dd>
                            <dt>Package Amount</dt>
                            <dd><span class="d-sm-inline-block d-none">:</span> <span style="text-decoration: inherit; white-space: nowrap;"><span>Rs. </span><?php echo $amountExcludingGST; ?></span></dd>
                            <dt>CGST</dt>
                            <dd><span class="d-sm-inline-block d-none">:</span> <span>Rs. </span><?php echo $cgstAmount; ?></span></dd>
                            <dt>SGST</dt>
                            <dd><span class="d-sm-inline-block d-none">:</span> <span>Rs. </span><?php echo $sgstAmount; ?></span></dd>
                            <dt>Payable Amount</dt>
                            <dd><span class="d-sm-inline-block d-none">:</span> <span>Rs. </span><?php echo $paid_amount; ?>.00</span></dd>
                        </dl>
                        <!-- <table width="100%" border="0" cellspacing="2" cellpadding="10" align="center">
                            <tbody>
                                
                                <tr valign="top">
                                    <td valign="top" align="left">
                                        <b>
                                            Order ID&nbsp;:
                                        </b>
                                    </td>
                                    <td valign="top" align="left">
                                        <?php echo $order_id; ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td valign="top" align="left">
                                        <b>
                                            Date and Time&nbsp;:
                                        </b>
                                    </td>
                                    <td valign="top" align="left">
                                        <?php echo date("d-m-Y h:i:s\ta", strtotime($current_date)); ?>
                                    </td>
                                </tr>                                
                               
                                <tr valign="top">
                                    <td valign="top" align="left">
                                        <b>
                                            Package Amount&nbsp;:
                                        </b>
                                    </td>
                                    <td valign="top" align="left">
                                        <span style="text-decoration: inherit; white-space: nowrap;"><span>Rs. </span><?php echo $amountExcludingGST; ?></span></b>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td valign="top" align="left">
                                        <b>
                                            CGST&nbsp;:
                                        </b>
                                    </td>
                                    <td valign="top" align="left">
                                        <span style="text-decoration: inherit; white-space: nowrap;"><span>Rs. </span><?php echo $cgstAmount; ?></span></b>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td valign="top" align="left">
                                        <b>
                                            SGST&nbsp;:
                                        </b>
                                    </td>
                                    <td valign="top" align="left">
                                        <span style="text-decoration: inherit; white-space: nowrap;"><span>Rs. </span><?php echo $sgstAmount; ?></span></b>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td valign="top" align="left">
                                        <b>
                                            Payable Amount&nbsp;:
                                        </b>
                                    </td>
                                    <td valign="top" align="left">
                                        <span style="text-decoration: inherit; white-space: nowrap;"><span>Rs. </span><?php echo $amount; ?>.00</span></b>
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>

                    <div class="col-12 d-flex">
                        <div class="col-sm-6 flex-grow-1">
                            <img src="<?= base_url(); ?>/public/assets/img/payee_type.svg" alt="Payee Type" title="Payee Type" class="p-2 mx-auto d-block" width="200">
                        </div>
                        <div class="col-sm-6 flex-grow-1 d-flex align-items-center justify-content-center text-white" style="background: #19A540;cursor: pointer;" onclick="razorpaySubmit(this);">
                            <span class="fs-4 fw-bold">Pay Now</span>
                            <!--<button id="rzp-button1" class="btn btn-green py-2 px-4 fs-5 fw-bold subs_btn" onclick="razorpaySubmit(this);">Pay Now</button>-->

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div style="height:10px"></div>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        /*var options = {
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
*/
        var options = <?php echo json_encode($razpay); ?>;


        options.handler = function(response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            //alert(JSON.stringify(response));
            document.razorpayform.submit();
        };

        // Boolean whether to show image inside a white frame. (default: true)
        options.theme.image_padding = false;

        options.modal = {
            ondismiss: function() {
                console.log("This code runs when the popup is closed");
            },
            // Boolean indicating whether pressing escape key 
            // should close the checkout form. (default: true)
            escape: true,
            // Boolean indicating whether clicking translucent blank
            // space outside checkout form should close the form. (default: false)
            backdropclose: false
        };

        var rzp = new Razorpay(options);
        rzp.on('payment.captured', function(response) {
            alert(response);

        });



        $(document).ready(function() {
            //$("#rzp-button1").click();
            // rzp.open();
            //e.preventDefault();
        });

        function razorpaySubmit(el) {
            rzp.open();
            //e.preventDefault();
        }


        /*

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
        */
    </script>


</body>

</html>