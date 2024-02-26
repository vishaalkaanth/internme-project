<!DOCTYPE html>
<html>
<?php
require_once(APPPATH . "Views/Common/head.php");
$session = session();
// print_r($_SESSION);
// print_r($applied_internship_list);
use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();

use App\Models\Employer_model;

$Employer_model = new Employer_model();
$login = $session->get('isLoggedIn');
//$this->load->view('common/head'); 
$userid    =    $session->get('userid');
$usertype    =    $session->get('usertype');

?>



<body class="web <?php if (!isset($login) && $login == '') { ?>resTop<?php } ?>">

    <?php
    if (isset($login) && $login != '') {
        require_once(APPPATH . "Views/Common/header.php");
        require_once(APPPATH . "Views/Common/error_page.php");
    } else {
    ?>
        <header>
            <nav class="navbar scrolled sticky-top navbar-expand-lg bg-light py-3">
                <?php require_once(APPPATH . "Views/Common/header_website.php"); ?>
                <!-- <div class="container-fluid">
                    <div class="container d-flex flex-wrap flex-sm-nowrap justify-content-center">
                        <a class="navbar-brand py-0 mb-sm-0 mb-4" href="index.php"><img src="<?= base_url(); ?>/public/assets/img/logo_blue.svg" alt="Logo" class="img-fluid" width="200"></a>
                       
                        <div class="navbar-collapse" id="navbarSupportedContent">
                            <ul class="menu navbar-nav me-auto mb-lg-0 justify-content-sm-end justify-content-center align-items-start w-100" id="mainNav">
                               

                                <li class="me-2 me-md-3">

                                    <a class="btn-outlined-blue px-2 px-sm-3 py-2" href="<?php echo base_url('web-search-internship'); ?>">
                                        <i class="fa fa-search me-2" aria-hidden="true"></i>
                                        Search Internship
                                    </a>
                                </li>
                                <li class="dropdown me-2 me-md-3">
                                    <a href="#" class="btn-outlined-blue dropdown-toggle px-2 px-sm-3" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Login</a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/main_login/1">Candidate</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/main_login/2">Employer</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/facultylogin">Faculty</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="<?= base_url(); ?>/register/candidate" class="btn-prim dropdown-toggle py-2 px-2 px-sm-3" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Register</a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/register/candidate">Candidate</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/register/employer">Employer</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> -->
            </nav>
            <!-- <div class="container bannerSec d-flex mt-5">
            <div class="col-md-7 bannerLt">
                <h1 class="text-white mb-4">We are The Brilliants In Terms of <span class="text-yellow">Connecting Internships!</span></h1>
                <p class="text-white">Hyperlocal platform connecting College students and MSME's with meaningful Internships.</p>
                <a href="#" class="btn-primary fw-medium mt-3 d-inline-block">Take Internship</a>
            </div>
            <div class="col-md-5">
                <img src="<?= base_url(); ?>/public/assets/img/bannerImg.png" alt="banner" class="img-fluid">
            </div>
        </div> -->
        </header>
    <?php } ?>
    <div class="d-sm-none d-block mt-4">
        &nbsp;
    </div>
    <section class="my-5 pt-4">
        <div class="pricingBanner text-center py-5">
            <div class="container">
                <h3 class="text-white mb-4">Sign-up to be on the preferred list of employers</h3>
                <ul class="list-unstyled ps-0 listYellow text-white d-flex flex-wrap flex-sm-row flex-column gap-4 pt-4 justify-content-center">
                    <li class="text-start">
                        <p class="mb-0 fs-5">Unlimited Free Internships</p>
                        <!-- <p class="fs-6">No Credit Card Required</p> -->
                    </li>
                    <li class="text-start">
                        <p class="mb-0 fs-5">Guaranteed Paid Internships</p>
                        <!-- <p class="fs-6">No Lock-In And Price Hikes</p> -->
                    </li>
                    <li class="text-start">
                        <p class="mb-0 fs-5">City Wise, Sector Wise Internships</p>
                        <!-- <p class="fs-6">50% Faster Implementation</p> -->
                    </li>
                    <li class="text-start">
                        <p class="mb-0 fs-5">100% Verified Corporates</p>
                        <!-- <p class="fs-6">Better Adoption, Easier Training</p> -->
                    </li>
                </ul>
            </div>
        </div>
        <div class="pricing-plans py-5 text-center">
            <?php
            $current_plan = $current_plan_premium = $current_plan_plus = $current_plan_super_premium = '';
            if (isset($login) && $login != '' && $usertype == '1') {
                $where = array('status' => '1', 'userid' => $userid);
                $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date,can_profile_complete_status'), $where);
                if ($payment_status_details->payment_package_type == 2 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) {
                    $current_plan_premium = 'current_plan';
                } elseif ($payment_status_details->payment_package_type == 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) {
                    $current_plan_plus = 'current_plan';
                } elseif ($payment_status_details->payment_package_type == 3 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) {
                    $current_plan_super_premium = 'current_plan';
                } else {
                    $current_plan = 'current_plan';
                }
            }
            ?>


            <div class="container">
                <h5 class="fw-medium text-blue fs-3 mb-5 text-center">Internme <span class="text-olive">Subscription plan</span></h5>
                <div class="d-flex flex-wrap row row-cols-lg-4 row-cols-md-2 row-cols-1 g-4">
                    <div class="col">
                        <div class="card pricing-card basic p-4 h-100">
                            <div class="heading px-3 py-2 rounded mb-4">
                                <h4 class="mb-0 text-white fs-4 fw-medium">Basic</h4>
                            </div>
                            <h5 class="price fs-3 text-blue mb-3">
                                FREE
                            </h5>
                            <h6 class="mb-3 planShort">Great for 1st year students</h6>


                            <?php if (isset($login) && $login != '') {
                                if ($usertype == 1) {
                                    // $where = array('status' => '1', 'userid' => $userid);
                                    // $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date,can_profile_complete_status'), $where);


                                    if ($payment_status_details->can_profile_complete_status == 1) {
                                        if ($payment_status_details->payment_package_type == 0 || $payment_status_details->payment_package_type == '' || $payment_status_details->payment_package_type == null) { ?>
                                            <a class="btn-prim px-5 fs-6 align-self-center mb-4" style="pointer-events: none;">Current Plan</a>
                                        <?php } else {
                                        ?>

                                        <?php }
                                    } else { ?>

                                        <a href="#" class="btn-prim px-5 fs-6 align-self-center mb-4" style="pointer-events: none;">Current Plan</a>
                                <?php  }
                                }
                            } else { ?>
                                <a hre="#" class="btn-prim px-5 fs-6 align-self-center mb-4" style="pointer-events: none;">Current Plan</a>
                            <?php } ?>



                            <ul class="price-features ps-0 list-unstyled text-start ">
                                <li>Unpaid internships - unlimited applications</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card pricing-card premium p-4 h-100">
                            <div class="heading px-3 py-2 rounded mb-4">
                                <h4 class="mb-0 text-white fs-4 fw-medium">Pro</h4>
                            </div>
                            <h5 class="price fs-3 text-blue mb-3">
                                <sup><img src="<?php echo base_url(); ?>/public/assets/img/rupee.svg" alt="Rupee" class="me-1" width="7"></sup>799
                                <sub class="fs-5">/yr</sub>
                            </h5>
                            <h6 class="mb-3 planShort">Great for pre-final year / 3rd year students</h6>

                            <?php if (isset($login) && $login != '') {
                                if ($usertype == 1) {
                                    // $where = array('status' => '1', 'userid' => $userid);
                                    // $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date,can_profile_complete_status'), $where);
                                    if ($payment_status_details->can_profile_complete_status == 1) {
                                        if ($payment_status_details->payment_package_type == 2 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                        <?php  } elseif ($payment_status_details->payment_package_type == 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                            <a class="btn-outlined-blue px-5 fs-6 align-self-center mb-4" style="pointer-events: none;">Current Plan</a>
                                        <?php } else {
                                        ?>
                                            <a href="<?php echo base_url('/phonepe-candidate-payment/1'); ?>" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                                        <?php }
                                    } else { ?>

                                        <a href="<?php echo base_url('/personal-details'); ?>" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                                <?php  }
                                }
                            } else { ?>
                                <!-- <a href="<?php echo base_url('pricing_plan_login'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a> -->
                                <a onclick="pricing_plan_login()" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                            <?php } ?>




                            <ul class="price-features ps-0 list-unstyled text-start">
                                <li>Unpaid internships - unlimited applications</li>
                                <li>Paid internships - 3 applications / year</li>
                                <li>Certificate in UGC format - Yes</li>
                                <li>Assessments & profile builder- 3/ year</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card pricing-card premium-plus p-4 h-100">
                            <div class="heading px-3 py-2 rounded mb-4">
                                <h4 class="mb-0 text-white fs-4 fw-medium">Premium</h4>
                            </div>
                            <div class="ribbon ribbon-top-left"><span>Popular</span></div>
                            <h5 class="price fs-3 text-blue mb-3">
                                <sup><img src="<?php echo base_url(); ?>/public/assets/img/rupee.svg" alt="Rupee" class="me-1" width="7"></sup>899
                                <sub class="fs-5">/yr</sub>
                            </h5>
                            <h6 class="mb-3 planShort">Great for final year students</h6>
                            <?php if (isset($login) && $login != '') {
                                if ($usertype == 1) {
                                    // $where = array('status' => '1', 'userid' => $userid);
                                    // $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date,can_profile_complete_status'), $where);
                                    if ($payment_status_details->can_profile_complete_status == 1) {
                                        if ($payment_status_details->payment_package_type == 2 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                            <a class="btn-outlined-blue px-5 fs-6 align-self-center mb-4" style="pointer-events: none;">Current Plan</a>
                                        <?php  } elseif ($payment_status_details->payment_package_type == 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                            <a href="<?php echo base_url('/phonepe-candidate-payment/2'); ?>" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                                        <?php } else {
                                        ?>
                                            <a href="<?php echo base_url('/phonepe-candidate-payment/2'); ?>" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                                        <?php }
                                    } else { ?>
                                        <a href="<?php echo base_url('/personal-details'); ?>" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                                <?php  }
                                }
                            } else { ?>
                                <!-- <a href="<?php echo base_url('pricing_plan_login'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a> -->
                                <a onclick="pricing_plan_login()" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                            <?php } ?>


                            <ul class="price-features ps-0 list-unstyled text-start ">
                                <li>Unpaid internships - unlimited applications</li>
                                <li>Paid / Premium internships - unlimited applications</li>
                                <li>Certificate in UGC format - Yes</li>
                                <li>Assessments & profile builder - unlimited</li>
                                <li>International professional certification courses- 1 / year</li>
                                <li>74% higher chance of getting paid internships</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card pricing-card super-premium p-4 h-100">
                            <div class="heading px-3 py-2 rounded mb-4">
                                <h4 class="mb-0 text-white fs-4 fw-medium">Super Premium</h4>
                            </div>
                            <h5 class="price fs-3 text-blue mb-3">
                                <sup><img src="<?php echo base_url(); ?>/public/assets/img/rupee.svg" alt="Rupee" class="me-1" width="7"></sup>2999
                                <sub class="fs-5">/yr</sub>
                            </h5>
                            <h6 class="mb-3 planShort">Customised for students who are serious about Career</h6>

                            <?php if (isset($login) && $login != '') {
                                if ($usertype == 1) {
                                    // $where = array('status' => '1', 'userid' => $userid);
                                    // $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date,can_profile_complete_status'), $where);
                                    if ($payment_status_details->can_profile_complete_status == 1) {
                                        if ($payment_status_details->payment_package_type == 3 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                            <a class="btn-outlined-blue px-5 fs-6 align-self-center mb-4" style="pointer-events: none;">Current Plan</a>
                                        <?php  } elseif ($payment_status_details->payment_package_type == 3 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                            <a href="<?php echo base_url('/phonepe-candidate-payment/3'); ?>" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                                        <?php } else {
                                        ?>
                                            <a href="<?php echo base_url('/phonepe-candidate-payment/3'); ?>" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                                        <?php }
                                    } else { ?>
                                        <a href="<?php echo base_url('/personal-details'); ?>" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                                <?php  }
                                }
                            } else { ?>
                                <!-- <a href="<?php echo base_url('pricing_plan_login'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a> -->
                                <a onclick="pricing_plan_login()" class="btn-outlined-blue px-5 fs-6 align-self-center mb-4">Buy Now</a>
                            <?php } ?>

                            <ul class="price-features ps-0 list-unstyled text-start">
                                <li>All features of Premium +</li>
                                <li>100% guarneteed paid internship</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-clr py-5">
            <div class="container">
                <h5 class="fw-medium text-olive fs-4 mb-3 text-center">Paid & Meaningful Internships </h5>
                <h5 class="fw-bold text-blue fs-2 mb-5 text-center">For every stream of Engineering, Arts & Science, across India</h5>
                <div class="d-flex flex-wrap justify-conent-center row row-cols-md-3 row-cols-1 g-lg-5 g-3">
                    <div class="col">
                        <div class="card h-100">
                            <div class="h-50px border-bottom pt-3 ps-3"></div>
                            <img src="<?php echo base_url(); ?>/public/assets/img/price_ico.svg" alt="" class="position-relative pricing-icon" width="30px">
                            <h5 class="text-uppercase text-blue px-4 mb-3">Guaranteed Paid Internships</h5>
                            <p class="px-4 mb-4">Become a premium+ member and get opportunities to work with Marquee Corporates</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="h-50px border-bottom"></div>
                            <img src="<?php echo base_url(); ?>/public/assets/img/get_hired.svg" alt="" class="position-relative pricing-icon" width="30px" style="top:-32px;">
                            <h5 class="text-uppercase text-blue px-4 mb-3">Get Hired</h5>
                            <p class="px-4 mb-4">93% of hiring happens through Internships - Get your passport to your dream job</p>
                        </div>
                    </div>
                    <div class="col ">
                        <div class="card h-100">
                            <div class="h-50px border-bottom"></div>
                            <img src="<?php echo base_url(); ?>/public/assets/img/higher_ico.svg" alt="" class="position-relative pricing-icon" width="33px">
                            <h5 class="text-uppercase text-blue px-4 mb-3">100% Verified</h5>
                            <p class="px-4 mb-4">100% verified corporates & verified certifications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pricingCourses pt-5">
            <div class="container">
                <h5 class="fw-medium text-blue fs-3 mb-4">Our Courses</h5>
                <div class="d-flex align-items-center list-unstyled courses owl-carousel">



                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_word.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 26</p>
                        <h6 class="f-13 mb-3">Word 2019 (MO-100)</h6>
                        <?php
                        if (!empty($login)) {
                            // print_r($profile_personal->profile_email);
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>




                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_excel.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 18</p>
                        <h6 class="f-13 mb-3">MO-200 Excel 2019 Associate</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_python.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 20</p>
                        <h6 class="f-13 mb-3">Python</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_photoshop.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 18</p>
                        <h6 class="f-13 mb-3">Photoshop 2020-2023</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_aftereffects.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 34</p>
                        <h6 class="f-13 mb-3">After Effects 2020-2023</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_word.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 22</p>
                        <h6 class="f-13 mb-3">Word 365 Apps (MO-110)</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_excel.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 21.5</p>
                        <h6 class="f-13 mb-3">Excel 365 Apps (MO-210)</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_cisco_cyber.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 23</p>
                        <h6 class="f-13 mb-3">Cisco Certified Support Technician: Cybersecurity</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_word.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 22</p>
                        <h6 class="f-13 mb-3">Word 2016 (77-725) (Lesson Layout)</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_excel.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 34.5</p>
                        <h6 class="f-13 mb-3">Excel 2016 (77-727) (Lesson Layout)</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>


                    <div class="card p-2 border pb-4 h-100" style="filter: none;">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_intuit.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 14</p>
                        <h6 class="f-13 mb-3">Certified Bookkeeping Professional</h6>
                        <?php
                        if (!empty($login)) {
                            if (!empty($profile_personal->profile_email)) {
                                if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php   } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                                <?php } ?>
                            <?php   } else { ?>
                                <a onclick="email_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>

                            <?php } ?>
                        <?php } else { ?>
                            <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now</a>
                        <?php } ?>
                    </div>




                    <!--<div class="card p-2 pb-4 h-100">
                    <img src="<?php echo base_url(); ?>/public/assets/img/ico_word.svg" class="img-fluid" alt="">
                    <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2 d-inline-block" style="width:12px;">Course Hours: 26</p>
                    <h6 class="f-13 mb-3">Word 2019 (MO-100)</h6>
                    <a onclick="login_alert()" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Enroll Now4</a>
                </div>-->
                </div>



                <!-- <div class=" pt-4 mt-5 position-relative ">
                    <div class=" marquee card border-top rounded-0 px-5 py-md-5 pt-4 pb-4 position-static"  style="filter: none;">
                        <h5 class="fw-medium text-blue mb-3 text-center position-absolute margin-auto headOver"><span class="bg-white px-3">Trusted By <span class="text-olive">Over 1K</span> Hiring Partners</span></h5>
                        <ul class="marquee-content">
                            <li class="">
                                <img src="<?= base_url(); ?>/public/assets/img/logo12.png" alt="L&T" width="150" title="L&T">
                            </li>
                            <li class="">
                                <img src="<?= base_url(); ?>/public/assets/img/logo2.png" alt="NSE academy" title="NSE academy" width="150">
                            </li>
                            <li class="">
                                <img src="<?= base_url(); ?>/public/assets/img/logo3.png" alt="SMC" title="SMC" width="150">
                            </li>
                            <li class="">
                                <img src="<?= base_url(); ?>/public/assets/img/logo5.png" alt="giz" title="giz" width="150">
                            </li>
                            <li class="">
                                <img src="<?= base_url(); ?>/public/assets/img/logo8.png" alt="Chitti ai" title="Chitti ai" width="150">
                            </li>
                            <li class="">
                                <img src="<?= base_url(); ?>/public/assets/img/indusind.png" alt="Indusind Bank" title="Indusind Bank" width="150">
                            </li>
                            <li class="">
                                <img src="<?= base_url(); ?>/public/assets/img/logo11.png" alt="Polaris" title="Polaris" width="100">
                            </li>

                            <li class="">
                                <img src="<?= base_url(); ?>/public/assets/img/logo13.png" alt="Valante" title="Valante" width="150">
                            </li>
                        </ul>
                    </div>
                </div> -->
                <?php $Employer_model = new Employer_model();
                $where = array('profile_completion_form.status' => '1', 'profile_completion_form.completed_status' => '1', 'profile_completion_form.featured_status' => '1', 'userlogin.active_status' => '1');
                $order_by = array('ordercolumn' => 'id', 'ordertype' => 'DESC');
                $emp_detail = $Employer_model->fetch_table_data_for_home_logo('profile_completion_form', $where, $order_by);

                ?>

                <div class=" pt-4 mt-5 position-relative ">
                    <div class=" marquee card border-top rounded-0 px-0 py-md-5 pt-4 pb-4 position-static" style="filter: none;">
                        <h5 class="fw-medium text-blue mb-3 text-center position-absolute margin-auto headOver"><span class="bg-white px-3">Trusted By <span class="text-olive">Over 1K</span> Hiring Partners</span></h5>
                        <div class="d-flex align-items-center list-unstyled logo-slider owl-carousel">
                            <!-- <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo12.png" alt="" width="150" title="L&T" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo2.png" alt="" width="150" title="NSE" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo3.png" alt="" width="150" title="SMC" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo5.png" alt="" width="150" title="Giz" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo8.png" alt="" width="150" title="Chitti" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/indusind.png" alt="" width="150" title="Induslnd" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo11.png" alt="" width="120" title="Polaris" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo13.png" alt="" width="150" title="volante" style="width: 150px !important;">
                            </div> -->
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo12.png" alt="" width="150" title="L&T" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo2.png" alt="" width="150" title="NSE" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo3.png" alt="" width="150" title="SMC" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo5.png" alt="" width="150" title="Giz" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/logo8.png" alt="" width="150" title="Chitti" style="width: 150px !important;">
                            </div>
                            <div class="logo-align">
                                <img src="<?= base_url(); ?>/public/assets/img/indusind.png" alt="" width="150" title="Induslnd" style="width: 150px !important;">
                            </div>

                            <?php if ($emp_detail) {
                                foreach ($emp_detail as $emp_profile) { ?>
                                    <div class="logo-align">
                                        <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $emp_profile->profile_company_logo; ?>" alt="<?php echo $emp_profile->profile_company_name; ?>" width="150" title="<?php echo $emp_profile->profile_company_name; ?>" style="width: 150px !important;">
                                    </div>
                            <?php }
                            } ?>
                        </div>
                    </div>
                </div>


            </div>




        </div>
        </div>
    </section>

    <?php require_once(APPPATH . "Views/Common/footer_website.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>


    <!--<script src="<?= base_url(); ?>/public/assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/js/bootstrap.bundle.min.js"></script>-->
    <script src="<?= base_url(); ?>/public/assets/js/owl.carousel.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/js/custom.js"></script>
    <script>
        $(document).ready(function() {

            $(".courses").owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                center: false,
                navText: [
                    '<a class="text-dark visionslide_a"><img src="public/assets/img/coursenav-prev.svg" width="40"></a>',
                    '<a class="ms-3 text-dark visionslide_a"><img src="public/assets/img/coursenav-next.svg" width="40"></a>'
                ],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    1000: {
                        items: 5
                    }
                }
            });

        });
    </script>
    <script>
        $(document).ready(function() {

            $(".logo-slider").owlCarousel({
                loop: true,
                margin: 10,
                // nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 2000,
                //slideTransition: 'linear',
                // autoplaySpeed: 6000,
                // smartSpeed: 6000,
                autoplayHoverPause: true,
                center: false,
                // navText: [
                //     '<a class="text-dark visionslide_a"><img src="public/assets/img/coursenav-prev.svg" width="40"></a>',
                //     '<a class="ms-3 text-dark visionslide_a"><img src="public/assets/img/coursenav-next.svg" width="40"></a>'
                // ],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    1000: {
                        items: 5
                    },
                    1600: {
                        items: 6
                    }
                }
            });

        });
    </script>
    <script>
        function pricing_plan_login() {
            swal({
                title: "You are not logged in",
                text: "Please login to access this page.",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.location.href = "<?php echo base_url('pricing_plan_login'); ?>";
                } else {
                    location.reload();
                }
            });
        }
    </script>
    <script>
        const root = document.documentElement;
        const marqueeElementsDisplayed = getComputedStyle(root).getPropertyValue("--marquee-elements-displayed");
        const marqueeContent = document.querySelector("ul.marquee-content");

        root.style.setProperty("--marquee-elements", marqueeContent.children.length);

        for (let i = 0; i < marqueeElementsDisplayed; i++) {
            marqueeContent.appendChild(marqueeContent.children[i].cloneNode(true));
        }
    </script>
    <script>
        function pricing_plan_subscription(val) {
            if (val == 2) {
                var title_val = "Upgrade your plan";
                var text_val = "Access to more internships requires a plan upgradation.";

            } else {
                var title_val = "Subscribe now";
                var text_val = "Access to paid features requires a subscription.";

            }

            swal({
                title: title_val,
                text: text_val,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.location.href = "<?php echo base_url('pricing-plan'); ?>";
                } else {
                    location.reload();
                }
            });
        }

        function email_alert() {

            var title_val = "Update your Email id!";
            var text_val = "Please make sure to update your email address, as it's essential for enrolling in a course.";

            swal({
                title: title_val,
                text: text_val,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.location.href = "<?php echo base_url('personal-details'); ?>";
                } else {
                    location.reload();
                }
            });
        }

        function login_alert() {

            var title_val = "Alert!";
            var text_val = "Please Login, Before Enroll For Course.";

            swal({
                title: title_val,
                text: text_val,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.location.href = "<?php echo base_url('login-gmetrix'); ?>";
                } else {
                    location.reload();
                }
            });
        }
    </script>
</body>

</html>