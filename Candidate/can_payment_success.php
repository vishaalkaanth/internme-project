<!DOCTYPE html>
<html>

<?php
$session = session();
//print_r($_SESSION);
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="bg_subscription">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>



    <div style="height:10px"></div>


    <section class="canDashboard canPayment py-md-5 py-4 d-flex align-items-center justify-content-center" style="">
        <div class="container">
            <div class="col-12 col-md-9 col-lg-6 paymentDetail col-sm-12 col-md-6 mx-auto d-flex bg-subscribe bg-white flex-column justify-content-center px-4">
                <!-- <span class="subs_title align-self-center px-4">Internme Subscription</span> -->
                <h3 class="text-green my-4">Payment Successfull!</h3>
                <img src="<?= base_url(); ?>/public/assets/img/successful_pay.gif" class="mx-auto d-block mb-4" alt="payment successfull" width="150">
                <dl class="text-start">
                    <dt>Payment ID</dt>
                    <dd><span class="d-sm-inline-block d-none">:</span> <?php echo $paymentID; ?></dd>
                    <dt>Order ID</dt>
                    <dd><span class="d-sm-inline-block d-none">:</span> <?php echo $orderID; ?></dd>
                    <dt>Payment Date & Time</dt>
                    <dd><span class="d-sm-inline-block d-none">:</span> <span style="text-decoration: inherit; white-space: nowrap;"><?php echo $paymentDate; ?></dd>
                    <dt>Payment Amount</dt>
                    <dd><span class="d-sm-inline-block d-none">:</span> <?php echo "Rs. " . $paymentAmount; ?></dd>
                </dl>
                <!-- <ul class="text-dark mx-auto d-block ps-0 col-12 mb-5">
                    <li class="col-12 d-flex flex-wrap">
                        <div class="col-12 col-md-6">Payment ID :</div>
                        <div class="col-12 col-md-6"><?php echo $paymentID; ?></div>
                    </li>
                    <li class="col-12 d-flex flex-wrap">
                        <div class="col-12 col-md-6">Order ID :</div>
                        <div class="col-12 col-md-6"><?php echo $orderID; ?></div>
                    </li>
                    <li class="col-12 d-flex flex-wrap">
                        <div class="col-12 col-md-6">Payment Date & Time :</div>
                        <div class="col-12 col-md-6"><?php echo $paymentDate; ?></div>
                    </li>
                    <li class="col-12 d-flex flex-wrap">
                        <div class="col-12 col-md-6">Payment Amount:</div>
                        <div class="col-12 col-md-6 text-success fw-bold"><?php echo "Rs. " . $paymentAmount; ?></div>
                    </li>
                </ul> -->
                <div class="subs_hor_line_parent">
                    <hr class="subs_hor_line">
                </div>
                <div class="d-flex justify-content-center align-items-center px-5 pb-4 mt-1">
                    <!--<button class="btn btn-green py-2 px-4 fs-5 fw-bold subs_btn">Ok</button>-->
                    <a href="<?= base_url(); ?>/my-transactions" class="btn btn-green py-2 px-4 fs-5 fw-bold subs_btn">Ok</a>
                </div>

            </div>
        </div>
    </section>

    <div style="height:10px"></div>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function() {
            history.pushState(null, null, location.href);
        });
    </script>
</body>

</html>