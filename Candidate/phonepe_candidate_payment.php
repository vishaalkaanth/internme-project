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

    
    
    <section class="canDashboard py-lg-5 py-4 d-flex align-items-start justify-content-center" style="height:90vh">
    <form name="razorpayform" id="razorpayform" action="<?php echo $callback_url; ?>" method="POST">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
       
        <input type="hidden" name="merchant_order_id" id="merchant_order_id" value="<?php echo $order_id; ?>"/>

        <input type="hidden" name="merchant_amount" id="merchant_amount" value="<?php echo $paid_amount; ?>"/>
 
        <div class="container d-flex flex-wrap flex-column-reverse flex-lg-row canPayment">
            <div class="col-12 col-lg-6 mt-5 mt-lg-0">
                <h1 class="fw-bold text-blue mb-lg-4 mb-0 pe-0 pe-md-4">Get 100% Internships with valuable Companies!</h1>
                <div class="border-bottom-green d-inline-block"></div>
                <h5 class="fw-bold text-blue mt-3 pt-0 pt-md-3">Hyperlocal platform for college students & corporates</h5>
                <p class="mt-3">Choose from a wide range of opportunities from AI, ML, IOT, core engineering, banking and other opportunities, with internships from leading corporates and 100% verified startups.</p>
                <h5 class="fw-bold text-blue mt-md-3 mt-0">Crazy dreams take crazy effort</h5>
                <ul class="payment_ul_list">
                    <li>100% verified corporates - Building Trust all the way</li>
                    <li>Pre-assessed students - easier for corporates to choose from</li>
                    <li>AI powered internship suggestion engine</li>
                    <li>100% corporate certification (not just our certificate)</li>
                </ul>
    
                <div class="mt-5">
                    <div><img src="<?= base_url(); ?>/public/assets/img/phonepe.svg" alt="Phonepe logo" title="Phonepe" class="img-fluid" width="130"></div>
                    <p style="color:#00000060"><small>Supercharge your business with the all - powerful Payment Gateway</small></p>
                </div>
            </div>
            <div class="col-12 col-lg-5 col-md-12 offset-lg-1">
                <div class="card p-0" style="border-radius: 0;">
                    <div class="p-4 paymentDetail">
                        <h4 class="fw-bold text-blue mb-4 pe-0 pe-md-4">Payment Details</h4>
                        <div class="border-bottom-green mb-5"></div>
                        <dl>
                            <dt>Order ID</dt>
                            <dd><span class="d-sm-inline-block d-none">:</span> <?php echo $order_id; ?></dd>
                            <dt>Date and Time</dt>
                            <dd><span class="d-sm-inline-block d-none">:</span> <?php echo date("d-m-Y h:i:s\ta",strtotime($current_date)); ?></dd>
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
                                        <?php echo date("d-m-Y h:i:s\ta",strtotime($current_date)); ?>
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
                                        <span style="text-decoration: inherit; white-space: nowrap;"><span>Rs. </span><?php echo $paid_amount; ?>.00</span></b>
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>

                    <div class="col-12 d-flex">
                        <div class="col-sm-6 flex-grow-1">
                            <img src="<?= base_url(); ?>/public/assets/img/payee_type.svg" alt="Payee Type" title="Payee Type" class="p-2 mx-auto d-block" width="200">
                        </div>
                        <div class="col-sm-6 flex-grow-1 d-flex align-items-center justify-content-center text-white" style="background: #19A540;cursor: pointer;" >
                            <!-- <span class="fs-4 fw-bold">Pay Now</span> -->
                            <button type="submit" class="btn btn-green py-2 px-4 fs-5 fw-bold subs_btn" >Pay Now</button>
                            
                        </div>
                    </div>
                    </form> 
                </div>
            </div>
        </div>
       
    </section>

    <div style="height:10px"></div>


<?php require_once(APPPATH . "Views/Common/script.php"); ?>
    

    
</body>

</html>