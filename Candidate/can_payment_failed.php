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

    <section class="canDashboard py-md-5 py-4 d-flex align-items-center justify-content-center" style="">
        <div class="container">
            <div class="col-12 col-md-9 col-lg-6 mx-auto d-flex bg-subscribe bg-white flex-column justify-content-center paymentDetail px-4">
                <!-- <span class="subs_title align-self-center px-4">Internme Subscription</span> -->
                <h3 class="text-danger my-4">Payment Failed!</h3>
                <img src="<?= base_url(); ?>/public/assets/img/failure_pay.gif" class="mx-auto d-block mb-4" alt="payment successfull" width="150">
                <dl class="text-start">
                    <dt>Order ID</dt>
                    <dd><span class="d-sm-inline-block d-none">:</span> <?php echo $orderID; ?></dd>
                    <dt>Error Message</dt>
                    <dd><span class="d-sm-inline-block d-none">:</span><?php echo $raz_error;
                                                                        ?></dd>

                </dl>
                <!-- <ul class="text-dark mx-auto d-block ps-0 col-12">
                    <li class="col-12 d-flex flex-wrap">
                        <div class="col-12 col-md-6">Order ID : </div>
                        <div class="col-12 col-md-6"><?php echo $orderID; ?></div>
                    </li>
                    <li class="col-12 d-flex flex-wrap">
                        <div class="col-12 col-md-6">Error Message : </div>
                        <div class="col-12 col-md-6"><?php //echo $raz_error;
                                                        ?></div>
                    </li>
                </ul> -->
                <div class="subs_hor_line_parent">
                    <hr class="subs_hor_line">
                </div>
                <div class="d-flex justify-content-center align-items-center px-5 pb-4 mt-3">
                    <button class="btn btn-danger py-2 px-4 fs-5 fw-bold subs_btn">Ok</button>
                </div>
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
</body>

</html>