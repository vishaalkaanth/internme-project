<!DOCTYPE html>
<html>

<?php
$session = session();
//print_r($_SESSION);
//$this->load->view('common/head'); 
$session = session();
$login = $session->get('isLoggedIn');
require_once(APPPATH . "Views/Common/head.php");
?>

<body class=" <?php if ($login) { ?>resTop<?php } ?>">

    <?php if ($login) { ?>
        <?php require_once(APPPATH . "Views/Common/header.php"); ?>
    <?php } else { ?>
        <header>
            <nav class="navbar scrolled sticky-top navbar-expand-lg bg-light py-3">
                <div class="container-fluid">
                    <div class="container d-flex flex-wrap flex-sm-nowrap justify-content-center">
                        <a class="navbar-brand py-0 mb-sm-0 mb-4" href="index.php"><img src="<?= base_url(); ?>/public/assets/img/logo_blue.svg" alt="Logo" class="img-fluid" width="200"></a>
                        <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                        <div class="navbar-collapse" id="navbarSupportedContent">
                            <ul class="menu navbar-nav me-auto mb-lg-0 justify-content-sm-end justify-content-center align-items-start w-100" id="mainNav">
                                <!-- <li class="nav-item">
                                <a class="nav-link text-white active" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#service">Features</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#about">Customers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#contact">Testi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#contact">Why Us</a>
                            </li>-->

                                <li class="me-2 me-md-3">
                                    <a class="btn-outlined-none px-2 px-sm-3 py-2" href="<?php echo base_url('web-search-internship'); ?>">
                                        <!-- <img src="<?= base_url(); ?>/public/assets/img/icon_job1.svg" width="15" class="hoverWhite mb-1 me-1" alt="">  -->
                                        Search Internships
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
                </div>
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

    <img src="<?php echo base_url(); ?>/public/assets/img/banner_gmetrix.png" alt="banner" class="img-fluid d-none d-md-block">
    <img src="<?php echo base_url(); ?>/public/assets/img/banner_gmetrix_mobile.png" alt="banner" class="img-fluid d-md-none">
    <div class="container my-4">
        <!--<h5 class="fw-semibold text-blue fs-4 mb-3">Welcome to GMetrix</h5>-->
        <p class="">Welcome to Internme plus. Internme is happy to offer premium courses and certifications from Pearsons, Cambridge English, Adobe, Autodesk, Microsoft, NSE amongst others, giving a remarkable edge for our subscribers.</p>
        <p>Along with internship, these courses and certifications add a life long value addition, in securing more meaningful jobs and highlight your resume to prospective employers.</p>
        <?php if (empty($gmatrix_data)) { ?>

            <h5 class="fw-semibold text-blue fs-4 mb-3 mt-4">Select a Course</h5>

            <div class="d-flex flex-wrap justify-content-start row">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">

                
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_word.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 26</p>
                        <h6 class="f-13 mb-2">Word 2019 (MO-100)</h6>
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


                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_excel.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 18</p>
                        <h6 class="f-13 mb-2">MO-200 Excel 2019 Associate</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_python.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 20</p>
                        <h6 class="f-13 mb-2">Python</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_photoshop.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 18</p>
                        <h6 class="f-13 mb-2">Photoshop 2020-2023</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_aftereffects.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 34</p>
                        <h6 class="f-13 mb-2">After Effects 2020-2023</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_word.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 22</p>
                        <h6 class="f-13 mb-2">Word 365 Apps (MO-110)</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_excel.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 21.5</p>
                        <h6 class="f-13 mb-2">Excel 365 Apps (MO-210)</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_cisco_cyber.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 23</p>
                        <h6 class="f-13 mb-2">Cisco Certified Support Technician: Cybersecurity</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_word.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 22</p>
                        <h6 class="f-13 mb-2">Word 2016 (77-725) (Lesson Layout)</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_excel.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 34.5</p>
                        <h6 class="f-13 mb-2">Excel 2016 (77-727) (Lesson Layout)</h6>
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
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                    <div class="card p-2 h-100">
                        <img src="<?php echo base_url(); ?>/public/assets/img/ico_intuit.svg" class="img-fluid" alt="">
                        <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/hour_duration.svg" alt="banner" class="img-fluid me-2" width="12">Course Hours: 14</p>
                        <h6 class="f-13 mb-2">Certified Bookkeeping Professional</h6>
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
                </div>
            </div>
        <?php } else { ?>
            <h5 class="fw-semibold text-blue fs-4 mb-3 mt-4">Enrolled Courses</h5>
            <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                <div class="card p-2 h-100">
                    <?php 
// print_r($gmatrix_data[0]->CourseName);
                // echo $gmatrix_data[0]->CourseName;exit;    
                    if (trim($gmatrix_data[0]->CourseName) == 'Word 2019 (MO-100)') {
                        $image_name = 'ico_word.svg';
                    } elseif (trim($gmatrix_data[0]->CourseName) == 'MO-200 Excel 2019 Associate') {
                        $image_name = 'ico_excel.svg';
                    } elseif (trim($gmatrix_data[0]->CourseName) == 'Python') {
                        $image_name = 'ico_python.svg';
                    } elseif (trim($gmatrix_data[0]->CourseName) == 'Photoshop 2020-2023') {
                        $image_name = 'ico_photoshop.svg';
                    } elseif (trim($gmatrix_data[0]->CourseName) == 'After Effects 2020-2023') {
                        $image_name = 'ico_aftereffects.svg';
                    } elseif (trim($gmatrix_data[0]->CourseName) == 'Word 365 Apps (MO-110)') {
                        $image_name = 'ico_word.svg';
                    } elseif (trim($gmatrix_data[0]->CourseName) == 'Excel 365 Apps (MO-210)') {
                        $image_name = 'ico_excel.svg';
                    } elseif (trim($gmatrix_data[0]->CourseName) == 'Cisco Certified Support Technician: Cybersecurity') {
                        $image_name = 'ico_cisco_cyber.svg';
                    } elseif (trim($gmatrix_data[0]->CourseName) == 'Word 2016 (77-725) (Lesson Layout)') {
                        $image_name = 'ico_word.svg';
                    }elseif (trim($gmatrix_data[0]->CourseName) == 'Excel 2016 (77-727) (Lesson Layout)') {
                        $image_name = 'ico_excel.svg';
                    }elseif (trim($gmatrix_data[0]->CourseName) == 'Certified Bookkeeping Professional') {
                        $image_name = 'ico_intuit.svg';
                    } ?>

                    <img src="<?php echo base_url(); ?>/public/assets/img/<?php echo $image_name; ?>" class="img-fluid" alt="">
                    <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/calendar.svg" alt="banner" class="img-fluid me-2" width="12">Start Date: <?php echo $newDate = date("d-M-Y", strtotime($gmatrix_data[0]->StartDate)); ?></p>
                    <h6 class="f-13 mb-2"><?php echo $gmatrix_data[0]->CourseName; ?></h6>
                    <?php
                    if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                        <a href="<?= base_url(); ?>/gmetrix-data" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Continue Learning</a>
                    <?php } else { ?>
                        <a onclick="pricing_plan_subscription(1)" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">Continue Learning</a>
                    <?php } ?>
                </div>
            </div>

        <?php } ?>
    </div>

    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
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