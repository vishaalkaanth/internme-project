<!DOCTYPE html>
<html>
<?php
$session = session();
$login = $session->get('isLoggedIn');
?>

<head>

    <?php if (isset($login) && $login != '') { ?>
        <meta charset="utf-8">
        <meta name="description" content="Apply to paid and unpaid internships all over India. Apply and register for free now  at Internme.">
        <meta name="keywords" content="Internship, Paid internship, Interns, Summer internship, IT internships, Start-up Internships, Internships near me, Mentor connect, Mentors, Career guidance, Top internships, Best internships">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Internme</title>
    <?php } else { ?>
        <meta charset="utf-8">
        <meta name="description" content="Apply to paid and unpaid internships all over India. Apply and register for free now  at Internme.">
        <meta name="keywords" content="Internship, Paid internship, Interns, Summer internship, IT internships, Start-up Internships, Internships near me, Mentor connect, Mentors, Career guidance, Top internships, Best internships">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Sign In | Internme accounts</title>
    <?php } ?>
    <?php
    //  print_r($_SESSION);

    require_once(APPPATH . "Views/Common/head_seo.php");
    ?>
</head>


<style>
    .selectWidth.durWidth .select2-container {
        width: 100% !important;
    }
</style>

<body class="stickyFoot <?php if (!isset($login) && $login == '') {
                            echo 'resTop';
                        } ?>" id="wrapper">

    <?php

    use App\Models\Candidate_model;



    if (isset($login) && $login != '') {
        require_once(APPPATH . "Views/Common/header.php");
        require_once(APPPATH . "Views/Common/error_page.php");
    } else {
    ?>

        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <div class="container d-flex flex-wrap flex-sm-nowrap justify-content-center">
                        <a class="navbar-brand py-0 mb-sm-0 mb-4" href="<?= base_url(); ?>/"><img src="<?= base_url(); ?>/public/assets/img/logo_blue.svg" alt="Logo" class="img-fluid" width="200"></a>
                        <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                        <div class="navbar-collapse" id="navbarSupportedContent">
                            <ul class="menu navbar-nav me-auto mb-lg-0 justify-content-sm-end justify-content-center align-items-center w-100 d-flex flex-wrap gap-3 gap-md-0" id="mainNav">
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
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#contact">Contact Us</a>
                            </li> -->
                                <li class="dropdown me-md-3">
                                    <!--<div class="input-group searchField headSearchAln headResSearch me-md-3 me-1">

                                        <input type="search" class="form-control rounded" placeholder="Search Internship" onkeypress="enterpress_search(event, this)" id="search_value" value="<?php
                                                                                                                                                                                                //show when search with keyword
                                                                                                                                                                                                $session = session();
                                                                                                                                                                                                $searched_keyword = $session->get('searched_keyword');
                                                                                                                                                                                                if (isset($searched_keyword)) {

                                                                                                                                                                                                    echo $searched_keyword;
                                                                                                                                                                                                }
                                                                                                                                                                                                ?>">
                                        <button class="btn btn-prim px-3" type="button" id="button-addon2" onclick="search_keyword(4)"><i class="fa fa-search" aria-hidden="true"></i></button>
                                       
                                        <span id="search_alert" style="color: red;position: absolute;font-size: 10px;top: 36px;"></span>
                                    </div>-->
                                </li>
                                <li class="me-2 me-md-3">
                                    <a class="text-blue  py-2" href="<?php echo base_url('pricing-plan'); ?>">
                                        <img src="<?= base_url(); ?>/public/assets/img/menu_price1.svg" width="18" class="hoverWhite mb-1 me-1" alt="">
                                        Pricing Plan
                                    </a>
                                </li>
                                <li class="dropdown me-md-3 me-1">
                                    <a href="#" class="btn-outlined-blue dropdown-toggle px-md-3 px-2" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Login</a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/main_login/1">Candidate</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/main_login/2">Employer</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/facultylogin">Faculty</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="<?= base_url(); ?>/register/candidate" class="btn-prim dropdown-toggle py-2 px-md-3 px-2" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Register</a>
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
    <?php
    }
    // echo $_SERVER['HTTP_REFERER'];

    $Candidate_model = new Candidate_model();
    $footer_category = $session->get('candidate_footer_category');
    $footer_city = $session->get('candidate_footer_city');
    $filter_category = $session->get('candidate_filter_category');
    $filter_city = $session->get('candidate_filter_city');
    $filter_emp = $session->get('candidate_filter_emp');
    $filter_start_date = $session->get('candidate_filter_start_date');
    $filter_internship_duration_one = $session->get('candidate_filter_internship_duration_one');
    $filter_internship_duration_two = $session->get('candidate_filter_internship_duration_two');
    $filter_parttime = $session->get('candidate_filter_parttime');
    // $filter_fulltime = $session->get('candidate_filter_fulltime');
    $filter_work_from_home = $session->get('candidate_filter_work_from_home');
    $filter_job_offer = $session->get('candidate_filter_job_offer');
    $filter_stipend = $session->get('candidate_filter_stipend');
    $can_userid = $session->get('userid');
    $search_internship_showing_limit = $session->get('search_internship_showing_limit');

    if (isset($filter_category)) {
        if (is_array($filter_category)) {
            $filter_category = $filter_category;
        } else {
            $filter_category = array();
        }
    } else {
        $filter_category = array();
    }

    if (isset($footer_category)) {
        if (is_array($footer_category)) {
            $footer_category = $footer_category;
        } else {
            $footer_category = array();
        }
    } else {
        $footer_category = array();
    }

    if (isset($footer_city)) {
        if (is_array($footer_city)) {
            $footer_city = $footer_city;
        } else {
            $footer_city = array();
        }
    } else {
        $footer_city = array();
    }




    if (isset($filter_city)) {
        if (is_array($filter_city)) {
            $filter_city = $filter_city;
        } else {
            $filter_city = array();
        }
    } else {
        $filter_city = array();
    }
    if (isset($filter_emp)) {
        if (is_array($filter_emp)) {
            $filter_emp = $filter_emp;
        } else {
            $filter_emp = array();
        }
    } else {
        $filter_emp = array();
    }
    //print_r($filter_city);//exit();
    $ses_data1 = [
        'profile_complete_status',
        'company_logo',
        'company_name',
        'intership_profile',
        'intership_number'
    ];
    $session->remove($ses_data1);
    // print_r($_SESSION); 
    ?>
    <!----- Form ------>
    <div class="d-md-none d-block mt-3">
        &nbsp;
    </div>
    <section class="container my-4 <?php if (!isset($login) && $login == '') {
                                        echo 'pt-5 pt-sm-0';
                                    } ?>">
        <!-- <div id="session_alert_bookmark"></div> -->
        <div class="d-flex justify-content-between flex-lg-row flex-column mb-4">
            <h2 class="page_title mb-lg-0 mb-4 col-lg-3">Search Internship</h2>









            <?php
            $session = session();

            $usertype  =  $session->get('usertype');
            if (isset($login) && $login != '') {
                if ($usertype == 1) {

            ?>
                    <div class="d-flex flex-grow-1 align-self-lg-start">
                        <div class="input-group searchField headSearchAln searchNewUi headResSearch shadow rounded w-100">

                            <input type="search" class="form-control rounded border-0 bg-transparent" placeholder="Search Internship" id="search_value" value="<?php
                                                                                                                                                                //show when search with keyword
                                                                                                                                                                $session = session();
                                                                                                                                                                $searched_keyword = $session->get('searched_keyword');
                                                                                                                                                                if (isset($searched_keyword)) {
                                                                                                                                                                    echo $searched_keyword;
                                                                                                                                                                }
                                                                                                                                                                ?>" onkeypress="enterpress_search(event, this)">

                            <button class="btn btn-success p-0 d-flex justify-content-center align-items-center" type="button" id="button-addon2" onclick="search_keyword(4)"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <!-- <span class="input-group-text">
        <img src="<?= base_url(); ?>/public/assets/img/search.svg" alt="Search" width="14">
    </span> -->
                            <span id="search_alert" style="color: red;position: absolute;font-size: 10px;top: 36px;"></span>
                        </div>
                    </div>

                <?php }
            } else { ?>












                <div class="d-flex flex-grow-1 align-self-lg-start">
                    <div class="input-group searchField headSearchAln searchNewUi headResSearch shadow rounded w-100">

                        <input type="search" class="form-control rounded border-0 bg-transparent" placeholder="Search by Keywords" onkeypress="enterpress_search(event, this)" id="search_value" value="<?php
                                                                                                                                                                                                        //show when search with keyword
                                                                                                                                                                                                        $session = session();
                                                                                                                                                                                                        $searched_keyword = $session->get('searched_keyword');
                                                                                                                                                                                                        if (isset($searched_keyword)) {

                                                                                                                                                                                                            echo $searched_keyword;
                                                                                                                                                                                                        }
                                                                                                                                                                                                        ?>">


                        <button class="btn btn-success p-0 d-flex justify-content-center align-items-center" type="button" id="button-addon2" onclick="search_keyword(4)"><i class="fa fa-search fs-5" aria-hidden="true"></i></button>
                        <!-- <span class="input-group-text">
                                        <img src="<?= base_url(); ?>/public/assets/img/search.svg" alt="Search" width="14">
                                    </span> -->
                        <span id="search_alert" style="color: red;position: absolute;font-size: 10px;top: 36px;"></span>
                    </div>

                </div>



            <?php } ?>



















        </div>

        <div class="d-flex flex-wrap row">
            <div class="col-12 col-lg-3 mb-4 mb-lg-0 filters_for_mobile">

                <!-- <div class="card py-3 px-4 mb-4">
                    <div class="profShort d-flex flex-column align-items-center text-center">
                       
                        <div class="profPic mb-3 d-flex justify-content-center align-items-center">
                            <span class="text-white fw-bold fs-4"><?php if (!empty($session->get('name'))) {
                                                                        echo $firstStringCharacter = substr($session->get('name'), 0, 1);
                                                                    } ?></span>
                        </div>
                        <h3 class="text-gray1 fs-5 fw-bold mb-0"><?php if (!empty($session->get('name'))) {
                                                                        echo $session->get('name');
                                                                    } ?></h3>
                        <div class="w-100 text-start my-3">
                            <span class="text-blue">Profile completion 75%</span>
                            <div class="progress w-100">
                                <div class="progress-bar bg-blue w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <a href="<?= base_url(); ?>/personal-details" class="btn btn-gray">View Profile</a>
                    </div>
                </div> -->

                <div class="card stickOnScroll py-lg-3" id="content-5">
                    <h4 class="filterTtl accordion-button text-blue border-bottom px-3 pb-3 mb-0 fs-5 fw-bold des-md-none" data-bs-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Filters</h4>
                    <h4 class="filterTtl text-blue border-bottom res-md-none px-3 pb-3 fs-5 fw-bold">Filters</h4>
                    <div class="filter collapse pt-3 ps-3" id="collapseExample">
                        <div class="mb-3 selectWidth selectResW">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                            <label class="form-label fw-medium">Profile</label>
                            <select class="js-states multiSelect form-control filledBox border-0 f-14" multiple aria-label="Default select example" data-live-search="true" id="filter_category" name="filter_category[]" aria-placeholder="Profile">
                                <option value="" disabled>Select Category</option>
                                <?php if (!empty($category_list)) {
                                    foreach ($category_list as $category) {
                                        if ($category->id != 0) { ?>
                                            <option value="<?php echo $category->id; ?>" <?php if (in_array($category->id, $filter_category)) {
                                                                                                echo "selected";
                                                                                            } ?>><?php echo $category->profile; ?></option>
                                <?php }
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="mb-3 selectWidth selectResW">
                            <label class="form-label fw-medium">Company</label>
                            <select class="js-states multiSelect form-control filledBox border-0 f-14" multiple aria-label="Select Company" data-live-search="true" id="filter_company" name="filter_company[]">
                                <option value="" disabled>Select Company</option>
                                <?php


                                if (!empty($emp_list)) {
                                    foreach ($emp_list as $emp) { ?>
                                        <option value="<?php echo $emp['userid']; ?>" <?php if (in_array($emp['userid'], $filter_emp)) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $emp['profile_company_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Minimum Monthly Stipend</label>
                            <input type="hidden" id="filter_stipend_value" name="filter_stipend_value" value="<?php if (!empty($filter_stipend) && isset($filter_stipend)) {
                                                                                                                    echo $filter_stipend;
                                                                                                                } ?>">
                            <!-- <input type="range" step="2000" oninput="func_stipend(this.value)" class="w-100 border-0 bg-blue" min="0" max="10000" id="filter_stipend" name="filter_stipend[]" value="<?php if (!empty($filter_stipend) && isset($filter_stipend)) {
                                                                                                                                                                                                                echo $filter_stipend;
                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                echo '0';
                                                                                                                                                                                                            } ?>"> -->
                            <div id="range" class="mt-2 mb-1"></div>
                            <ul class="d-flex justify-content-between list-unstyled ps-0">
                                <li class="px-1">0</li>
                                <li class="px-1">5k</li>
                                <li class="px-1">10k</li>
                                <li class="px-1">15k</li>
                                <li class="px-1">20k</li>
                                <li class="px-1">25k+</li>
                            </ul>
                        </div>
                        <div class="mb-3 selectWidth selectResW">
                            <label class="form-label fw-medium">Location</label>
                            <select class="js-states multiSelect form-control filledBox border-0 f-14" multiple aria-label="Select Location" data-live-search="true" id="filter_city" name="filter_city[]">
                                <option value="" disabled>Select Location</option>
                                <?php


                                if (!empty($city_list)) {
                                    foreach ($city_list as $city) { ?>
                                        <option value="<?php echo $city['g_location_id']; ?>" <?php if (in_array($city['g_location_id'], $filter_city)) {
                                                                                                    echo "selected";
                                                                                                } ?>><?php echo $city['g_location_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>

                        <label class="form-label fw-medium">Internship Type</label>
                        <ul class="list-unstyled p-0 m-0 mb-3">
                            <li class="mb-1">
                                <input type="hidden" name="filter_work_from_home_value" id="filter_work_from_home_value" value="<?php if (!empty($filter_work_from_home)) {
                                                                                                                                    echo $filter_work_from_home;
                                                                                                                                } ?>">
                                <input class="form-check-input" style="margin-top: 6px;cursor: pointer;" type="radio" id="filter_work_from_home" name="filter_work_from_home" onclick="func_work_from_home(1)" value="<?php if (!empty($filter_work_from_home)) {
                                                                                                                                                                                                                            echo $filter_work_from_home;
                                                                                                                                                                                                                        } ?>" <?php if (!empty($filter_work_from_home && $filter_work_from_home == '1')) {
                                                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                                                } ?>>
                                <label for="filter_work_from_home" style="cursor: pointer;" class="fw-normal ms-1">Regular (In-office/On-field)</label>
                            </li>
                            <li>
                                <input class="form-check-input" style="margin-top: 6px;cursor: pointer;" type="radio" id="filter_work_from_home1" name="filter_work_from_home" onclick="func_work_from_home(2)" value="<?php if (!empty($filter_work_from_home)) {
                                                                                                                                                                                                                            echo $filter_work_from_home;
                                                                                                                                                                                                                        } ?>" <?php if (!empty($filter_work_from_home && $filter_work_from_home == '2')) {
                                                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                                                } ?>>
                                <label for="filter_work_from_home1" style="cursor: pointer;" class="fw-normal ms-1">Work From Home</label>
                            </li>
                        </ul>
                        <label class="form-label fw-medium">Timing</label>
                        <ul class=" list-unstyled p-0 m-0 mb-3">
                            <li class="mb-1">
                                <input type="hidden" name="filter_parttime_value" id="filter_parttime_value" value="<?php if (!empty($filter_parttime)) {
                                                                                                                        echo $filter_parttime;
                                                                                                                    } ?>">
                                <input class="form-check-input" style="margin-top: 6px;cursor: pointer;" type="radio" name="filter_parttime" id="filter_parttime" onclick="func_parttime(1);" value="<?php if (!empty($filter_parttime)) {
                                                                                                                                                                                                            echo $filter_parttime;
                                                                                                                                                                                                        } ?>" <?php if (!empty($filter_parttime) && $filter_parttime == '1') {
                                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                                } ?>>
                                <label for="filter_parttime" style="cursor: pointer;" class="fw-normal ms-1">Part Time</label>
                            </li>
                            <li>
                                <input class="form-check-input" style="margin-top: 6px;cursor: pointer;" type="radio" id="filter_fulltime" name="filter_parttime" onclick="func_parttime(2)" value="<?php if (!empty($filter_parttime)) {
                                                                                                                                                                                                        echo $filter_parttime;
                                                                                                                                                                                                    } ?>" <?php if (!empty($filter_parttime)  && $filter_parttime == '2') {
                                                                                                                                                                                                                echo 'checked';
                                                                                                                                                                                                            } ?>>
                                <label for="filter_fulltime" style="cursor: pointer;" class="fw-normal ms-1">Full Time</label>
                            </li>
                        </ul>


                        <div class="mb-3">
                            <label class="form-label fw-medium">Starting From (Or After)</label>
                            <input type="date" min="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d", strtotime('+1 year')); ?>" class="form-control filledBox border-0 f-14" placeholder="" id="filter_start_date" name="filter_start_date[]" value="<?php if (!empty($filter_start_date)) {
                                                                                                                                                                                                                                                                    echo $filter_start_date;
                                                                                                                                                                                                                                                                } ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Internship Duration</label>
                            <div class="d-flex selectWidth durWidth">
                                <select name="filter_internship_duration_one" id="filter_internship_duration_one" class="js-states multiSelect form-control filledBox border-0 f-14">
                                    <option value="">Duration</option>
                                    <option value="15" <?php if (isset($filter_internship_duration_one)) {
                                                            if ($filter_internship_duration_one == 15) {
                                                                echo "selected";
                                                            }
                                                        } ?>>Below 15 days</option>
                                    <option value="30" <?php if (isset($filter_internship_duration_one)) {
                                                            if ($filter_internship_duration_one == 30) {
                                                                echo "selected";
                                                            }
                                                        } ?>>Below 1 Month</option>
                                    <option value="60" <?php if (isset($filter_internship_duration_one)) {
                                                            if ($filter_internship_duration_one == 60) {
                                                                echo "selected";
                                                            }
                                                        } ?>>Below 2 Month</option>
                                    <option value="90" <?php if (isset($filter_internship_duration_one)) {
                                                            if ($filter_internship_duration_one == 90) {
                                                                echo "selected";
                                                            }
                                                        } ?>>Below 3 Month</option>
                                    <option value="180" <?php if (isset($filter_internship_duration_one)) {
                                                            if ($filter_internship_duration_one == 180) {
                                                                echo "selected";
                                                            }
                                                        } ?>>Below 6 Month</option>
                                    <option value="365" <?php if (isset($filter_internship_duration_one)) {
                                                            if ($filter_internship_duration_one == 365) {
                                                                echo "selected";
                                                            }
                                                        } ?>>Below 1 Year</option>
                                    <!-- <option value="2" <?php if (isset($filter_internship_duration_one)) {
                                                                if ($filter_internship_duration_one == 2) {
                                                                    echo "selected";
                                                                }
                                                            } ?>>Month</option>
                                    <option value="1" <?php if (isset($filter_internship_duration_one)) {
                                                            if ($filter_internship_duration_one == 1) {
                                                                echo "selected";
                                                            }
                                                        } ?>>Week</option> -->

                                </select>
                                <!-- <select name="filter_internship_duration_two" id="filter_internship_duration_two" class="form-control border-0 py-2 f-14 filledBox count_hide_show">
                                <option value="">Count</option>    
                                <?php for ($i = 1; $i <= 6; $i++) { ?>
                                        <option value="<?php echo $i; ?>" <?php if (isset($filter_internship_duration_two)) {
                                                                                if ($i == $filter_internship_duration_two) {
                                                                                    echo "selected";
                                                                                }
                                                                            } ?>><?php echo $i; ?></option>
                                    <?php } ?>
                                </select> -->

                            </div>
                        </div>
                        <ul class="ks-cboxtags preTxt p-0 m-0 mb-3">
                            <li class="mobile_position_relative">
                                <input class="form-check-input" type="checkbox" id="filter_job_offer" name="filter_job_offer" onclick="func_job_offer()" value="<?php if (!empty($filter_job_offer)) {
                                                                                                                                                                    echo $filter_job_offer;
                                                                                                                                                                } ?>" <?php if (!empty($filter_job_offer)) {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?>>
                                <label for="filter_job_offer" class="fw-medium">Internship With Pre Placement Opportunity</label>
                            </li>
                            <!-- <li>
                                <input type="checkbox" id="checkboxfive" value="Order Two">
                                <label for="checkboxfive" class="fw-medium">Fast response</label>
                            </li>
                            <li>
                                <input type="checkbox" id="checkboxsix" value="Order Two">
                                <label for="checkboxsix" class="fw-medium">Early applicant</label>
                            </li> -->
                        </ul>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-lg-0 mb-3">
                            <a href="<?php if (isset($login) && $login != '') {
                                            echo base_url('unset_candidate_filters');
                                        } else {
                                            echo base_url('Unset-Internship-Filters');
                                        } ?>" class="text-blue fw-medium">Clear All</a>
                            <a onclick="candidate_search()" class="btn btn-prim fw-medium px-4">Apply</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9 cInternList">





                <?php if (!empty($footer_category) && isset($footer_category)) { ?>
                    <h2 class="page_title mb-4 fs-5 text-center">
                        <?php if (!empty($category_list)) {
                            foreach ($category_list as $category) {
                                if ($category->id != 0) {
                                    if (in_array($category->id, $footer_category)) {
                                        echo $category->profile . " Internships ";
                                    }
                                }
                            }
                        }  ?>
                    </h2>
                <?php } ?>



                <?php if (!empty($footer_city) && isset($footer_city)) { ?>

                    <h2 class="page_title mb-4 fs-5 text-center"> Internships in
                        <?php
                        if (!empty($city_list_all)) {
                            foreach ($city_list_all as $city_all) {
                                if (in_array($city_all['dist_id'], $footer_city)) {
                                    echo mb_convert_case($city_all['dist_name'], MB_CASE_TITLE, "UTF-8");
                                }
                            }
                        }
                        ?>
                    </h2>
                <?php } ?>



                <?php
                //show when search with keyword
                $session = session();
                $searched_keyword = $session->get('searched_keyword');
                if (isset($searched_keyword) && $searched_keyword != '') {

                ?>
                    <span class="mb-2 d-inline-block">Showing results for <span style="padding: .2em;background-color: #fcf8e3;"><?php echo $searched_keyword; ?></span> <a href="<?php if (isset($login) && $login != '') {
                                                                                                                                                                                        echo base_url('Clear-Search-Filter');
                                                                                                                                                                                    } else {
                                                                                                                                                                                        echo base_url('Clear-Web-Search-Filter');
                                                                                                                                                                                    } ?>" class="f-14 text-blue me-3 badge badge-ongoing">Clear</a></span>
                <?php
                }
                ?>
                <?php if (!empty($internship_list)) { ?>
                    <div class="showFor d-flex flex-wrap justify-content-between align-items-center mb-4 py-1 px-2">
                        <p class="mb-0 text-muted">Showing <?php if (isset($page_start_id)) {
                                                                if ($page_start_id == 0) {
                                                                    echo '1';
                                                                } else {
                                                                    echo $page_start_id + 1;
                                                                }
                                                            } ?> to <?php if (isset($page_start_id)) {
                                                                        if ($page_start_id == 0) {
                                                                            if (!empty($internship_list)) {
                                                                                echo count($internship_list);
                                                                            } else {
                                                                                echo
                                                                                $page_default_limit;
                                                                            }
                                                                        } else {
                                                                            if (!empty($internship_list)) {


                                                                                echo count($internship_list) + $page_start_id;
                                                                            } else {
                                                                                echo $page_default_limit;
                                                                            }
                                                                        }
                                                                    } ?> of <?php

                                                                            //print_r($internship_list);
                                                                            if (!empty($internship_list)) {
                                                                                echo count($internship_list_count);
                                                                            } else {
                                                                                echo count($internship_list_count);
                                                                            }



                                                                            ?> Internships</p>
                        <label class="text-muted">Show
                            <select name="showing_count_result" id="showing_count_result" onchange="fun_showing_count_result(this.value)" class="selectpicker form-control bg-white border-0 f-14 w-auto mx-1">
                                <option value="10">10</option>
                                <option value="25" <?php if (isset($search_internship_showing_limit)) {
                                                        if ($search_internship_showing_limit == '25') {
                                                            echo 'selected';
                                                        }
                                                    } ?>>25</option>
                                <option value="50" <?php if (isset($search_internship_showing_limit)) {
                                                        if ($search_internship_showing_limit == '50') {
                                                            echo 'selected';
                                                        }
                                                    } ?>>50</option>
                                <option value="100" <?php if (isset($search_internship_showing_limit)) {
                                                        if ($search_internship_showing_limit == '100') {
                                                            echo 'selected';
                                                        }
                                                    } ?>>100</option>
                            </select> Internships</label>
                    </div>
                <?php } ?>
                <div class="card p-sm-4 p-3 mb-4">

                    <?php
                    //echo "<pre>";
                    //print_r($internship_list);

                    if (!empty($internship_list)) {
                        $i = 1;
                        foreach ($internship_list as $internship) {


                            $new_date = date("Y-m-d", strtotime($internship->created_at));
                            $now = time(); // or your date as well
                            $your_date = strtotime($new_date);
                            $datediff = $now - $your_date;
                            $post_days = floor($datediff / (60 * 60 * 24));

                            $where_emp = array('status' => '1', 'userid' => $internship->company_id);
                            $emp_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);

                    ?>

                            <div class="d-flex flex-sm-row flex-row-reverse bdrHide border-bottom pb-4 mb-4">
                                <div class="comLogo res-sm-none  d-flex justify-content-center align-items-center rounded p-1 me-sm-3 ms-sm-0 ms-2 ">
                                    <?php if (isset($emp_details->profile_company_logo) && !empty($emp_details->profile_company_logo)) { ?>
                                        <?php $check = file_exists(FCPATH . "public/assets/docs/uploads/emp_profile/" . $emp_details->profile_company_logo);
                                        ?>
                                        <?php if ($check) { ?>
                                            <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $emp_details->profile_company_logo; ?>" alt="logo" class="img-fluid noStretch" width="40" style="border-radius: 50%;">
                                        <?php } else { ?>
                                            <span class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                                <span><?php if (!empty($emp_details->profile_company_name)) {
                                                            echo $firstStringCharacter = substr($emp_details->profile_company_name, 0, 1);
                                                        } ?></span>
                                            </span>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <span class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                            <span><?php if (!empty($emp_details->profile_company_name)) {
                                                        echo $firstStringCharacter = substr($emp_details->profile_company_name, 0, 1);
                                                    } ?></span>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="w-100 position-relative">
                                    <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                                        <div class="d-flex flex-column">
                                            <div>
                                                <a href="<?php if (isset($login) && $login != '') {
                                                                echo base_url('/internship-details/' . $internship->internship_id);
                                                            } else {
                                                                echo base_url('/view-internship-details/' . $internship->internship_id);
                                                            }  ?>" class="">
                                                    <h3 class="fw-semibold text-blue me-sm-0 pe-sm-0 me-5 pe-2 fs-5 profile_text" id=""><?php if (isset($internship->profile) && $internship->profile != '0') {
                                                                                                                                            echo $profile = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                                                                                                        } else {
                                                                                                                                            echo $profile = $internship->other_profile;
                                                                                                                                        } ?></h3>
                                                </a>
                                                <div class="comLogo des-sm-none logoResPos  d-flex justify-content-center align-items-center rounded p-1 me-sm-3 ms-sm-0 ms-2">
                                                    <?php if (isset($emp_details->profile_company_logo) && !empty($emp_details->profile_company_logo)) { ?>

                                                        <?php if ($check) { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $emp_details->profile_company_logo; ?>" alt="logo" class="img-fluid noStretch" width="40" style="border-radius: 50%;">
                                                        <?php } else { ?>
                                                            <span class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                                                <span><?php if (!empty($emp_details->profile_company_name)) {
                                                                            echo $firstStringCharacter = substr($emp_details->profile_company_name, 0, 1);
                                                                        } ?></span>
                                                            </span>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <span class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                                            <span><?php if (!empty($emp_details->profile_company_name)) {
                                                                        echo $firstStringCharacter = substr($emp_details->profile_company_name, 0, 1);
                                                                    } ?></span>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php
                                            $where_city = array('status' => '1', 'internship_id' => $internship->internship_id);
                                            $int_city = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);
                                            ?>
                                            <h6 class="text-blue fw-medium mb-0">
                                                <ul class="d-flex flex-wrap list-unstyled mb-0 flex-column flex-sm-row">
                                                    <?php if (isset($emp_details->profile_company_name) && !empty($emp_details->profile_company_name)) { ?>
                                                        <a href="<?= base_url(); ?>/employer-details/<?php echo $internship->company_id; ?>">
                                                            <li class="text-dark comResWidth fw-normal me-0 pe-2 me-sm-3 pe-sm-0 mb-2"><img src="<?= base_url(); ?>/public/assets/img/icon_company1.svg" alt="Location" class="me-2 mb-1 " width="14"><span class="company_text"><?php if (isset($emp_details->profile_company_name)) {
                                                                                                                                                                                                                                                                                        echo $emp_details->profile_company_name;
                                                                                                                                                                                                                                                                                    } ?></span></li>
                                                        </a>
                                                    <?php }
                                                    if ($internship->internship_type == 2) { ?>
                                                        <!-- <li class="me-2 mb-2"><img src="<?= base_url(); ?>/public/assets/img/icon_location_blue.svg" alt="Location" class="me-2"></li> -->
                                                        <li class="mb-2"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                                        <?php } else {

                                                        if (!empty($int_city)) {
                                                            if ($int_city[0]->g_location_name == '') {
                                                                if ($internship->internship_type == 1) { ?>

                                                                <?php }
                                                            }
                                                            foreach ($int_city as $city) {
                                                                if ($city->g_location_name != '') {  ?>
                                                                    <li class="mb-2"><span class="badge bg-gray fw-normal text-dark f-13 "><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i><span class="location_text"><?php echo $city->g_location_name;                     //echo $Candidate_model->get_master_name('master_city', $city->work_location, 'city');                                                                                                                                                                                                                 
                                                                                                                                                                                                                                                ?></span></span></li>
                                                                <?php
                                                                    // $pre_location_name = array($city->g_location_name); 
                                                                }
                                                                // else {
                                                                //  if ($internship->pre_placement_offer == 1) { 
                                                                ?>
                                                                <!-- <li class="me-2 mb-2"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li> -->
                                                    <?php }
                                                        }
                                                    }
                                                    // } }
                                                    // print_r($pre_location_name);
                                                    ?>
                                                </ul>
                                            </h6>
                                        </div>
                                        <div class="d-flex justify-content-start align-items-center align-self-start">
                                            <div class="d-flex justify-content-md-center justify-content-between align-items-start">
                                                <p class="text-muted mb-0 me-2">Posted <?php if ($post_days == 0) {
                                                                                            echo 'Today';
                                                                                        } elseif ($post_days == '1') {
                                                                                            echo 'Yesterday';
                                                                                        } else {
                                                                                            echo $post_days . ' Days Ago';
                                                                                        } ?>
                                                    <!-- <i class="fa fa-bookmark-o ms-2" aria-hidden="true" title="bookmark"></i> -->
                                                </p>
                                                <?php if (isset($login) && $login != '') {


                                                    $where_book = array('bookmark_status' => '1', 'status' => '1', 'internship_id' => $internship->internship_id, 'can_user_id' => $can_userid, 'emp_user_id' => $emp_details->userid);
                                                    $bookmark_details = $Candidate_model->fetch_table_row('can_bookmark_details', $where_book);
                                                    // print_r($bookmark_details);
                                                    if (isset($bookmark_details)) { ?>
                                                        <input type="hidden" name="bookmark_type_<?php echo $internship->internship_id; ?>" id="bookmark_type_<?php echo $internship->internship_id; ?>" value="2" />
                                                    <?php  } else { ?>
                                                        <input type="hidden" name="bookmark_type_<?php echo $internship->internship_id; ?>" id="bookmark_type_<?php echo $internship->internship_id; ?>" value="1" />
                                                    <?php
                                                    }
                                                    ?>
                                                    <a onclick="func_can_bookmark('<?php echo $internship->internship_id; ?>','<?php echo $emp_details->userid; ?>','<?php echo $profile; ?>')" class="bookmarkIco px-2 py-1 fs-4"><span class="bookmark_icon_<?php echo $internship->internship_id; ?>"><i class="<?php if (isset($bookmark_details)) { ?>fa fa-bookmark<?php } else { ?>fa fa-bookmark-o<?php } ?>" <?php if (isset($bookmark_details)) { ?> style="color:#19A540" <?php } ?>></i></a>
                                                <?php } ?>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center share_parent <?php if (!isset($login) && $login == '') {
                                                                                                                            echo 'resShare';
                                                                                                                        } ?>">

                                                <span id="add_hide_class_new<?php echo $i; ?>" class="except">
                                                    <a class="share-btn-overall" id="add_hide_class<?php echo $i; ?>" onclick="social_meadia(<?php echo $i; ?>)">
                                                        <img src="<?= base_url(); ?>/public/assets/img/share_ico.svg" alt="Share" width="16">
                                                    </a>
                                                </span>

                                                <div class="social_hide_area_1 " id="socialmedia_hide_show_<?php echo $i; ?>">
                                                    <?php
                                                    if (isset($int_city) && !empty($int_city)) {
                                                        if ($int_city[0]->g_location_name == '') {
                                                            if ($internship->internship_type != 1) {
                                                                $loction_names[] = 'Work From Home'; ?>

                                                        <?php }
                                                        } ?>

                                                        <?php foreach ($int_city as $city) {
                                                            if ($city->g_location_name != '') { ?>
                                                                <?php

                                                                $loction_names[] = $city->g_location_name; ?>
                                                        <?php }
                                                        } ?>

                                                    <?php
                                                    } else {
                                                        $loction_names[] = 'Work From Home';
                                                    }

                                                    if (isset($internship->profile) && $internship->profile != '0') {
                                                        // $int_name = "intern";

                                                        $int_name = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                    } else {
                                                        $int_name = $internship->other_profile;
                                                    }
                                                    // print_r($loction_names);
                                                    if (!empty($loction_names)) {
                                                        $loction_name = implode(',', $loction_names);
                                                    } else {
                                                        $loction_name = '';
                                                    }

                                                    ?>



                                                    <?php

                                                    if ($internship->internship_type != 1) {
                                                        $new_location_name = 'Work From Home';
                                                    } else {
                                                        $new_location_name = array();
                                                        foreach ($int_city as $city) {
                                                            $new_location_name[] = $city->g_location_name;
                                                        }
                                                        if (!empty($new_location_name)) {
                                                            $new_location_name = implode(',', $new_location_name);
                                                        } else {
                                                            $new_location_name = '';
                                                        }
                                                    }
                                                    $share_data['intership_name'] = $int_name;
                                                    $share_data['intership_loc'] = $loction_name;


                                                    echo view('Common/head', $share_data);
                                                    ?>
                                                    <a target="_blank" class="fbtn share facebook mb-2" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url('view-internship-details/' . $internship->internship_id); ?>&text=Internship:<?php echo $int_name; ?>%0aLocation: <?php echo $new_location_name; ?>%0a%0a">
                                                        <img src="<?= base_url(); ?>/public/assets/img/facebook_ico.svg" alt="Facebook" width="40">
                                                    </a>


                                                    <a target="_blank" class="fbtn share twitter mb-2" href="https://twitter.com/intent/tweet?url=<?= urlencode(base_url('view-internship-details/' . $internship->internship_id)); ?>&text=Internship:<?php echo $int_name; ?>%0a Location: <?php echo $new_location_name; ?>%0a%0a" data-size="small" data-text="">
                                                        <img src="<?= base_url(); ?>/public/assets/img/twitter_ico.svg" alt="Twitter" width="40">
                                                    </a>

                                                    <a target="_blank" class="fbtn share pinterest mb-2" href="https://api.whatsapp.com/send?text=<?= urlencode(base_url('view-internship-details/' . $internship->internship_id)) . '%0a%0a Internship: ' . $int_name . '%0a Locations: ' . $new_location_name; ?>">
                                                        <img src="<?= base_url(); ?>/public/assets/img/whatsapp_ico.svg" alt="Whatsapp" width="40">
                                                    </a>

                                                    <a target="_blank" class="fbtn share linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo base_url('view-internship-details/' . $internship->internship_id); ?>">
                                                        <img src="<?= base_url(); ?>/public/assets/img/linkedin_ico.svg" alt="Linkedin" width="40">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <ul class="d-flex flex-wrap ps-0 list-unstyled flex-column flex-sm-row">
                                        <li class="me-sm-5 me-4 mb-md-3 mb-2 mb-sm-0">
                                            <div class="d-flex">
                                                <img src="<?= base_url(); ?>/public/assets/img/ico_start.svg" alt="Start Date" class="img-fluid me-2" width="13">
                                                <p class="text-blue fw-medium mb-0 f-14">Start Date</p>
                                            </div>
                                            <span class="fw-normal fs-6 pt-1"><?php if (isset($internship->internship_startdate)) {
                                                                                    echo date("d-m-Y", strtotime($internship->internship_startdate));
                                                                                } ?></span>
                                        </li>
                                        <li class="me-sm-5 me-4 mb-md-3 mb-2 mb-sm-0">
                                            <div class="d-flex">
                                                <img src="<?= base_url(); ?>/public/assets/img/ico_duration.svg" alt="Duration" class="img-fluid me-2" width="14">
                                                <p class="text-blue fw-medium mb-0 f-14">Duration</p>
                                            </div>
                                            <span class="fw-normal fs-6 pt-1"><?php if (isset($internship->internship_duration)) {
                                                                                    echo $internship->internship_duration;
                                                                                } ?> <?php if (isset($internship->internship_duration_type)) {
                                                                                            if ($internship->internship_duration_type == 1) {
                                                                                                if ($internship->internship_duration == 1) {
                                                                                                    echo "Week";
                                                                                                } else {
                                                                                                    echo "Weeks";
                                                                                                }
                                                                                            } elseif ($internship->internship_duration_type == 2) {
                                                                                                if ($internship->internship_duration == 1) {
                                                                                                    echo "Month";
                                                                                                } else {
                                                                                                    echo "Months";
                                                                                                }
                                                                                            }
                                                                                        } ?></span>
                                        </li>
                                        <li class="me-sm-5 me-4 mb-md-3 mb-2 mb-sm-0">
                                            <div class="d-flex">
                                                <img src="<?= base_url(); ?>/public/assets/img/ico_stipend.svg" alt="Stipend" class="img-fluid me-2" width="14">
                                                <p class="text-blue fw-medium mb-0 f-14">Stipend / Month</p>
                                            </div>
                                            <span class="fw-normal fs-6 pt-1"><?php if ($internship->stipend != '1') {
                                                                                    if (isset($internship->amount_from) && $internship->amount_from != '0') {
                                                                                        echo '₹ ' . $internship->amount_from;
                                                                                    } ?> <?php if (isset($internship->amount_to) && $internship->amount_to != '0') {
                                                                                                echo '- ' . $internship->amount_to;
                                                                                            } ?><?php } else {
                                                                                                echo "Unpaid";
                                                                                            } ?></span>
                                        </li>
                                        <li class="">
                                            <div class="d-flex">
                                                <img src="<?= base_url(); ?>/public/assets/img/last_date.svg" alt="Last date" class="img-fluid me-2" width="14">
                                                <p class="text-blue fw-medium mb-0 f-14">Last Date To Apply</p>
                                            </div>
                                            <span class="fw-normal fs-6 pt-1"><?php if (isset($internship->internship_candidate_lastdate)) {
                                                                                    echo date("d-m-Y", strtotime($internship->internship_candidate_lastdate));
                                                                                } ?></span>
                                        </li>

                                    </ul>

                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-md-center">
                                        <div>
                                            <div class="d-flex  flex-wrap">
                                                <?php if (!empty($internship->partime)) { ?><span class="badge btn-gray f-13 fw-normal rounded-25 me-2 mb-2 mb-sm-0 px-3"><?php if ($internship->partime == 1) {
                                                                                                                                                                                echo "Part Time";
                                                                                                                                                                            } else {
                                                                                                                                                                                echo "Full Time";
                                                                                                                                                                            } ?></span><?php } ?>
                                                <?php if (!empty($internship->internship_type)) { ?><span class="badge btn-gray f-13 fw-normal rounded-25 me-2 mb-2 mb-sm-0  px-3"><?php if ($internship->internship_type == 1) {
                                                                                                                                                                                        echo "Regular (In-office)";
                                                                                                                                                                                    } else {
                                                                                                                                                                                        //echo "Work From Home";
                                                                                                                                                                                    } ?></span><?php } ?>
                                                <span class="badge btn-gray f-13 fw-normal rounded-25 me-2 mb-2 mb-sm-0  px-3"><?php if ($internship->prefer_gender == 1) {
                                                                                                                                    echo "Preferably Male";
                                                                                                                                } else if ($internship->prefer_gender == 2) {
                                                                                                                                    echo "Preferably Female";
                                                                                                                                } ?></span>

                                            </div>

                                            <?php if (!empty($internship->internship_type)) {
                                                if ($internship->pre_placement_offer == 1) { ?><span class="badge badge-highlt fw-medium rounded-25 f-13 mt-3 me-sm-2 mb-2 mb-sm-0 ps-0 pe-3 py-0"><span class="iconRound me-2"><img src="<?= base_url(); ?>/public/assets/img/icon_offer.svg" alt="Last date" class="img-fluid" width="14"></span> Pre Placement Opportunity</span><?php }
                                                                                                                                                                                                                                                                                                                                                                            } ?>
                                        </div>
                                        <?php
                                        $where_apply = array('status' => '1', 'internship_id' => $internship->internship_id, 'candidate_id' => $can_userid);
                                        $apply_internship_details = $Candidate_model->fetch_table_row('can_applied_internship', $where_apply);
                                        if (isset($apply_internship_details)) {
                                            // print_r($apply_internship_details); 
                                            $current_date = date("d-m-Y");
                                            $applied_date = date("d-m-Y", strtotime($apply_internship_details->created_at));

                                            $new_date = date("Y-m-d", strtotime($apply_internship_details->created_at));
                                            $now = time(); // or your date as well
                                            $your_date = strtotime($new_date);
                                            $datediff = $now - $your_date;
                                            $post_days = floor($datediff / (60 * 60 * 24));
                                        ?>
                                            <div class="d-flex flex-wrap justify-content-end align-self-end mt-2 mt-md-0">
                                                <?php
                                                $current_date = date("Y-m-d");
                                                $duration_type = '';
                                                $duration_count_1 = $internship->internship_duration;
                                                if (isset($internship->internship_duration_type)) {
                                                    if ($internship->internship_duration_type == 1) {
                                                        // echo "Week";
                                                        if ($internship->internship_duration == 1) {
                                                            $duration_type = "Week";
                                                        }
                                                    } elseif ($internship->internship_duration_type == 2) {
                                                        // echo "Months";
                                                        if ($internship->internship_duration == 1) {
                                                            $duration_type = "Month";
                                                        }
                                                    }
                                                }

                                                // $end_date=date("Y-m-d",strtotime("+2 month",strtotime(date("Y-m-01",strtotime("now") ) )));
                                                $expire_date = date("Y-m-d", strtotime("+" . $duration_count_1 . " " . $duration_type . " ", strtotime(date($internship->internship_startdate))));

                                                if ($apply_internship_details->hiring_status == 1) {
                                                    if ($internship->internship_startdate <= $current_date) { ?>
                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                            <?php
                                                            echo "<span class='badge badge-completed fw-normal'>Ongoing</span>";
                                                            ?></span><?php
                                                                    } else { ?>
                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the employers offer">
                                                            <?php
                                                                        echo "<span class='badge badge-completed fw-normal'>Offer accepted</span>";
                                                            ?></span><?php
                                                                    }
                                                                } elseif ($apply_internship_details->hiring_status == 2) { ?>
                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have rejected the employers offer">
                                                        <?php
                                                                    echo "<span class='badge badge-red fw-normal'>Offer declined</span>";
                                                        ?></span><?php
                                                                } elseif ($apply_internship_details->complete_status == 1) {
                                                                    if ($apply_internship_details->complete_type != 1) { ?>
                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not completed the internship">
                                                            <?php
                                                                        echo "<span class='badge badge-red fw-normal'>Dropped</span>";
                                                            ?></span><?php
                                                                    } else { ?>
                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have completed the internship">
                                                            <?php
                                                                        echo "<span class='badge badge-red fw-normal'>Completed</span>";
                                                            ?></span><?php
                                                                    }
                                                                } else {
                                                                    if ($apply_internship_details->application_status == 0) { ?>

                                                        <span class="badge bg-white p-2 text-green f-13 fw-medium ms-2"><i class="fa fa-check me-1" aria-hidden="true"></i> <?php if ($apply_internship_details->application_type == 0) {
                                                                                                                                                                                echo "Applied";
                                                                                                                                                                            } else {
                                                                                                                                                                                echo "Offered";
                                                                                                                                                                            } ?> <?php if ($post_days == 0) {
                                                                                                                                                                                        echo 'Today';
                                                                                                                                                                                    } elseif ($post_days == '1') {
                                                                                                                                                                                        echo 'Yesterday';
                                                                                                                                                                                    } else {
                                                                                                                                                                                        echo 'On ' . $applied_date;
                                                                                                                                                                                    } ?></span><?php
                                                                                                                                                                                            } elseif ($apply_internship_details->application_status == 1) { ?>

                                                        <span class="badge bg-white p-2 text-green f-13 fw-medium ms-2"><i class="fa fa-check me-1" aria-hidden="true"></i> <?php if ($apply_internship_details->application_type == 0) {
                                                                                                                                                                                                    echo "Applied";
                                                                                                                                                                                                } else {
                                                                                                                                                                                                    echo "Offered";
                                                                                                                                                                                                } ?> <?php if ($post_days == 0) {
                                                                                                                                                                                                            echo 'Today';
                                                                                                                                                                                                        } elseif ($post_days == '1') {
                                                                                                                                                                                                            echo 'Yesterday';
                                                                                                                                                                                                        } else {
                                                                                                                                                                                                            echo 'On ' . $applied_date;
                                                                                                                                                                                                        } ?></span><?php
                                                                                                                                                                                                                } elseif ($apply_internship_details->application_status == 2) {
                                                                                                                                                                                                                    if ($current_date >= $expire_date) { ?>
                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not taken any action on the offer sent by the employer until internship start date">
                                                                <?php
                                                                                                                                                                                                                        echo "<span class='badge badge-completed fw-normal'>Offer expired</span>";
                                                                ?></span><?php
                                                                                                                                                                                                                    } else { ?>
                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You are hired by the employer">
                                                                <?php
                                                                                                                                                                                                                        echo "<span class='badge badge-completed fw-normal mb-1 me-2'>Hired</span>";
                                                                ?></span><?php
                                                                                                                                                                                                                    }
                                                                                                                                                                                                                } elseif ($apply_internship_details->application_status == 3) { ?>
                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is rejected by the employer">
                                                            <?php
                                                                                                                                                                                                                    echo "<span class='badge badge-red fw-normal'>Not Qualified</span>";
                                                            ?></span><?php
                                                                                                                                                                                                                }
                                                                                                                                                                                                            }

                                                                        ?>


                                            </div>
                                        <?php } else {


                                            // if (!empty($profile_personal->profile_email) && !empty($education_details)) { 
                                        ?>
                                            <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship->internship_id; ?>" class="btn btn-prim align-self-end mt-2 mt-md-0 px-4"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2" width="17"> Apply</a> -->
                                            <?php //} else {
                                            ?>

                                            <?php
                                            $current_date = date("Y-m-d");

                                            if ($internship->active_status == '1' && $internship->internship_startdate >= $current_date && $internship->internship_candidate_lastdate >= $current_date) { ?>

                                                <a href="<?php if (isset($login) && $login != '') {
                                                                echo base_url('/internship-details/' . $internship->internship_id);
                                                            } else {
                                                                echo base_url('/view-internship-details/' . $internship->internship_id);
                                                            }  ?>" class="btn btn-prim align-self-end mt-2 mt-md-0 px-3">
                                                    <?php if ($internship->premium_status == 1) { ?>
                                                        <span class=" me-1">
                                                            <img src="<?= base_url(); ?>/public/assets/img/intern_plus.svg" alt="Share" style="padding-bottom: 2px;" class="img-fluid" width="20">
                                                        </span>
                                                    <?php } ?>
                                                    <!-- <img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2" width="17">  -->
                                                    View Details <i class="fa fa-angle-double-right ms-2 text-white" aria-hidden="true"></i>
                                                </a>
                                            <?php  } else { ?>
                                                <span class="btn btn-danger align-self-end mt-2 mt-md-0 px-3" style="pointer-events: none;">Intenship Closed</span>
                                            <?php } ?>




                                            <?php     // if (isset($internship->profile) && $internship->profile != '0') {
                                            //     $profile = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                            // } else {
                                            //     $profile =  $internship->other_profile;
                                            // }
                                            // if (isset($emp_details->profile_company_logo) && !empty($emp_details->profile_company_logo)) {
                                            //     $profile_company_logo = $emp_details->profile_company_logo;
                                            // } else {
                                            //     $profile_company_logo = '';
                                            // }
                                            // if (isset($emp_details->profile_company_name) && !empty($emp_details->profile_company_name)) {
                                            //     $profile_company_name = $emp_details->profile_company_name;
                                            // } else {
                                            //     $profile_company_name = '';
                                            // }
                                            // if (empty($profile_personal->profile_email)) {
                                            // 
                                            ?>

                                            <!-- <button onclick="func_next_profile('1','<?php //echo $profile_company_logo;
                                                                                            ?>','<?php //echo $profile_company_name;
                                                                                                    ?>','<?php //echo $profile;                    
                                                                                                            ?>','<?php //echo $internship->internship_id;                                                                                                                                                                                 
                                                                                                                    ?>')" class="btn btn-prim px-4 align-self-end mt-2 mt-md-0"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2" width="17"> Apply</button>
                                                    <?php //} elseif (empty($education_details)) {
                                                    // 
                                                    ?>
                                                         <button onclick="func_next_profile('2','<?php //echo $profile_company_logo;
                                                                                                    ?>','<?php //echo $profile_company_name;
                                                                                                            ?>','<?php //echo $profile;                                                                                                                                                                                    
                                                                                                                    ?>','<?php //echo $internship->internship_id;                                                                                                                                                                                                            
                                                                                                                            ?>')" class="btn btn-prim px-4 align-self-end mt-2 mt-md-0"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2" width="17"> Apply</button> -->

                                        <?php //}
                                            // }
                                        } ?>
                                    </div>

                                </div>
                            </div>

                        <?php
                            $i++;
                        } ?>
                        <?= $pager_links ?>
                    <?php } else { ?>
                        <div class="d-flex justify-content-center align-items-center flex-column py-4 w-100">
                            <h5 class="mb-5">No Matches Found</h5>

                            <img src="<?= base_url(); ?>/public/assets/img/filter_no_result.svg" alt="No Result" width="300" class="img-fluid" style="opacity:0.5;">
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php require_once(APPPATH . "Views/Common/footer_website.php"); ?>
    <div class="m_height_50"></div>
    <?php
    require_once(APPPATH . "Views/Common/script.php"); ?>

    <script>
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////   social media 
        $(document).mouseup(function(e) {
            var container = $(".social_hide_area_1");
            // If the target of the click isn't the container

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                const divclass = document.querySelector('.social_hide_area_1');

                if (divclass.classList.contains('active')) {
                    $(".social_hide_area_1").removeClass('active');
                }
            }


        });
    </script>

    <script>
        //fix position when set filter
        $(document).ready(function() {
            $(".select2-search__field").attr('maxlength', '30');
            $(".social_hide_area_1").hide();
            // $('body').click(function() {
            //     if ($(".social_hide_area_1").css('display', 'flex')) {
            //         $(".social_hide_area_1").css('display', 'none');
            //     }
            //     else {
            //     }
            // });
        });

        function social_meadia(val) {
            $(".social_hide_area_1").hide();
            var base_url = "<?php echo base_url() ?>";
            var i;
            for (i = 1; i <= 10; i++) {
                var share_btn = ' <a class="share-btn-overall" id="add_hide_class' + i + '" onclick="social_meadia(' + i + ')"><img src="' + base_url + '/public/assets/img/share_ico.svg" alt="Share" width="16"></a>';

                $("#add_hide_class_new" + i).html(share_btn);
            }

            $("#socialmedia_hide_show_" + val).toggle();
            var base_url = "<?php echo base_url() ?>";
            var share_btn = ' <a class="share-btn-overall" id="add_hide_class' + val + '" onclick="social_meadia_hide(' + val + ')"><img src="' + base_url + '/public/assets/img/share_ico.svg" alt="Share" width="16"></a>';

            $("#add_hide_class_new" + val).html(share_btn);
            event.stopPropagation();
        }

        function social_meadia_hide(val) {
            $(".social_hide_area_1").hide();


            // $("#socialmedia_hide_show_" + val).toggle();
            var base_url = "<?php echo base_url() ?>";
            var i;
            for (i = 1; i <= 10; i++) {
                var share_btn = ' <a class="share-btn-overall" id="add_hide_class' + i + '" onclick="social_meadia(' + i + ')"><img src="' + base_url + '/public/assets/img/share_ico.svg" alt="Share" width="16"></a>';

                $("#add_hide_class_new" + i).html(share_btn);
            }

        }



        var body = document.getElementById('wrapper');
        var except = document.getElementsByClassName('except');

        if (body.addEventListener)
            body.addEventListener("click", bodyClick, false);
        else
            body.attachEvent("onclick", bodyClick);

        function bodyClick(event) {
            if (event.target != except)
                $(".social_hide_area_1").hide();
            var base_url = "<?php echo base_url() ?>";
            var i;
            for (i = 1; i <= 10; i++) {
                var share_btn = ' <a class="share-btn-overall" id="add_hide_class' + i + '" onclick="social_meadia(' + i + ')"><img src="' + base_url + '/public/assets/img/share_ico.svg" alt="Share" width="16"></a>';

                $("#add_hide_class_new" + i).html(share_btn);
            }
        }


        $(document).ready(function() {
            $('#filter_internship_duration_two').val('');
            <?php if (!empty($filter_stipend) && isset($filter_stipend)) { ?>
                var result_filter = '<?php echo $filter_stipend; ?>';

                var filter_position = 0;
                if (result_filter == 0) {
                    filter_position = 0;
                }
                if (result_filter == 5000) {
                    filter_position = 3503;
                }
                if (result_filter == 10000) {
                    filter_position = 7761;
                }
                if (result_filter == 15000) {
                    filter_position = 12456;
                }
                if (result_filter == 20000) {
                    filter_position = 17151;
                }
                if (result_filter == 25000) {
                    filter_position = 23044;
                }
                $("#range").slider("value", filter_position);
            <?php } ?>

        });

        function func_parttime(val) {
            $('#filter_parttime_value').val(val);
        }
        <?php if (isset($filter_internship_duration_two) && !empty($filter_internship_duration_two)) { ?>
            $('.count_hide_show').show();
        <?php } else { ?>
            $('.count_hide_show').hide();
        <?php } ?>
        // function func_fulltime()
        // {
        //     if ($('#filter_fulltime').is(":checked"))
        //     {
        //         $('#filter_fulltime').val(1);
        //     } else 
        //     {
        //         $('#filter_fulltime').val('');
        //     }
        // }
        function func_work_from_home(val) {
            $('#filter_work_from_home_value').val(val);
        }

        function func_job_offer() {
            if ($('#filter_job_offer').is(":checked")) {
                $('#filter_job_offer').val(1);
            } else {
                $('#filter_job_offer').val('');
            }
        }

        function func_count_hide_show(val) {
            if (val) {
                $('.count_hide_show').show();
            } else {
                $('.count_hide_show').hide();
                $('#filter_internship_duration_two').val('');


            }
        }

        function func_stipend(newvalue) {
            $('#filter_stipend_value').val(newvalue);
        }

        function candidate_search() {
            var csrftokenname = "<?php echo csrf_token() ?>";
            var csrftokenhash = $(".csrf").val();
            var category = $('#filter_category').val();
            var city = $('#filter_city').val();
            var company = $('#filter_company').val();
            var start_date = $('#filter_start_date').val();
            var internship_duration_one = $('#filter_internship_duration_one').val();
            var internship_duration_two = $('#filter_internship_duration_two').val();
            var parttime = $('#filter_parttime_value').val();
            // var fulltime = $('#filter_fulltime').val();
            var work_from_home = $('#filter_work_from_home_value').val();
            var job_offer = $('#filter_job_offer').val();
            var stipend = $('#filter_stipend_value').val();
            $.ajax({
                url: "<?php if (isset($login) && $login != '') {
                            echo base_url('set_candidate_filters');
                        } else {
                            echo base_url('Internship-Filters');
                        } ?>",
                // method: "POST",
                dataType: 'JSON',
                type: 'POST',
                data: {
                    category: category,
                    city: city,
                    company: company,
                    start_date: start_date,
                    internship_duration_one: internship_duration_one,
                    internship_duration_two: internship_duration_two,
                    parttime: parttime,
                    work_from_home: work_from_home,
                    job_offer: job_offer,
                    stipend: stipend,
                    [csrftokenname]: csrftokenhash
                },

                success: function(response) {
                    <?php if (isset($login) && $login != '') { ?>window.location = "<?php echo base_url('search-internship'); ?>";
                <?php } else { ?> window.location = "<?php echo base_url('web-search-internship'); ?>";
                <?php  } ?>

                },
                error: function(e) {
                    swal("", "Refresh This Page", "warning");
                    return false;

                }
            });
        }

        //swal alert
        function func_next_profile(val, company_logo, company_name, intership_profile, intership_number) {
            swal({
                title: "Alert",
                text: "98% of Internships are offered to candidates with completed profile",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    // if (val == 1) {
                    window.location.href = '<?= base_url(); ?>/can_apply_intern_session/' + val + '/' + company_logo + '/' + company_name + '/' + intership_profile + '/' + intership_number;
                    // }
                    // if (val == 2) {
                    //     window.location.href = '<?= base_url(); ?>/education-details';
                    // }

                } else {
                    location.reload();
                }
            })
            // swal("Please Complete Your Profile", "You clicked the button!", "success");
        }

        //stipend range
        (function() {
            $("#range").slider({
                max: 25000,
                value: 0,
                slide: function(e, ui) {

                    var result_val = 0;
                    if (ui.value > 0 && ui.value < 1000) {
                        result_val = 0;
                    }
                    if (ui.value > 1000 && ui.value < 5000) {
                        result_val = 5000;
                    }
                    if (ui.value > 5000 && ui.value < 10000) {
                        result_val = 10000;
                    }
                    if (ui.value > 10000 && ui.value < 15000) {
                        result_val = 15000;
                    }
                    if (ui.value > 15000 && ui.value < 20000) {
                        result_val = 20000;
                    }
                    if (ui.value > 20000 && ui.value < 25000) {
                        result_val = 25000;
                    }
                    $("#filter_stipend_value").val(result_val);
                    var new_position = 0;
                    if (result_val == 0) {
                        new_position = 0;
                    }
                    if (result_val == 5000) {
                        new_position = 3503;
                    }
                    if (result_val == 10000) {
                        new_position = 7761;
                    }
                    if (result_val == 15000) {
                        new_position = 12456;
                    }
                    if (result_val == 20000) {
                        new_position = 17151;
                    }
                    if (result_val == 25000) {
                        new_position = 23044;
                    }

                    $("#range").slider({
                        value: new_position,
                    }).call(this);
                }
            });

        }).call(this);

        //$( "#filter_stipend_value" ).val(  $('#range').slider('value') );

        $('#filter_category').select2({
            placeholder: "Select Profile",
            matcher: function(params, data) {
                return matchStart(params, data);
            }
        });

        $('#filter_city').select2({
            placeholder: "Select Location",
            matcher: function(params, data) {
                return matchStart(params, data);
            }
        });
        $('#filter_company').select2({
            placeholder: "Select Company",
            matcher: function(params, data) {
                return matchStart(params, data);
            }
        });

        function func_can_bookmark(internship_id, emp_user_id, profile) {

            var val = $("#bookmark_type_" + internship_id).val();
            // alert(val);
            var csrf_val = $(".csrf").val();
            var csrf = "&csrf_test_name=" + csrf_val;
            $.ajax({
                url: "<?php echo base_url('can_intership_bookmark'); ?>",
                method: "POST",
                data: "type=" + val + "&internship_id=" + internship_id + "&emp_user_id=" + emp_user_id + "&profile=" + profile + "&redirect=3" + csrf,
                success: function(response) {
                    // alert(response);
                    var splitted_data = response.split('^');
                    $(".csrf").val(splitted_data[0].trim());
                    if (splitted_data[1] == '1') {
                        // location.reload();
                        var val = $("#bookmark_type_" + internship_id).val('2');

                        $(".bookmark_icon_" + internship_id).html('<i class="fa fa-bookmark" style="color:#19A540"></i>');
                        $("#session_alert").css("display", "block");
                        $("#session_alert").html("<div class='alert alert-success flash-alert overflow-anywhere alertOn'><i class='fa fa-check me-2' aria-hidden='true'></i>Bookmark Added</div>");
                        setTimeout(function() {
                            $("#session_alert").css("display", "none");
                        }, 2000);


                    }
                    if (splitted_data[1] == '2') {
                        var val = $("#bookmark_type_" + internship_id).val('1');
                        $(".bookmark_icon_" + internship_id).html('<i class="fa fa-bookmark-o" style=""></i>');
                        $("#session_alert").css("display", "block");
                        $("#session_alert").html("<div class='alert alert-danger flash-alert overflow-anywhere alertOn'><i class='fa fa-check me-2' aria-hidden='true'></i>Bookmark Removed</div>");
                        setTimeout(function() {
                            $("#session_alert").css("display", "none");
                        }, 2000);

                    }
                },

            });
            // window.location.href = '<?= base_url(); ?>/can_intership_bookmark/' + val + '/' + intership_id + '/' + emp_user_id + '/' + profile + '/3';

        }

        function fun_showing_count_result(value) {
            window.location.href = "<?php echo base_url('emp_search_internship_showing'); ?>/" + value;
        }
    </script>
    <script>
        timeout_var = null;

        function typeWriter(selector_target, text_list, placeholder = false, i = 0, text_list_i = 0, delay_ms = 200) {
            if (!i) {
                if (placeholder) {
                    document.querySelector(selector_target).placeholder = "Search by Keywords ";
                } else {
                    document.querySelector(selector_target).innerHTML = "Search by Keywords ";
                }
            }
            txt = text_list[text_list_i];
            if (i < txt.length) {
                if (placeholder) {
                    document.querySelector(selector_target).placeholder += txt.charAt(i);
                } else {
                    document.querySelector(selector_target).innerHTML += txt.charAt(i);
                }
                i++;
                setTimeout(typeWriter, delay_ms, selector_target, text_list, placeholder, i, text_list_i);
            } else {
                text_list_i++;
                if (typeof text_list[text_list_i] === "undefined") {
                    setTimeout(typeWriter, (delay_ms * 5), selector_target, text_list, placeholder);
                } else {
                    i = 0;
                    setTimeout(typeWriter, (delay_ms * 3), selector_target, text_list, placeholder, i, text_list_i);
                }
            }
        }

        text_list = [
            "UI/UX Designer",
            "Web Developer",
            "Hyderabad"
        ];

        return_value = typeWriter("#search_value", text_list, true);


        $(function() {
            $("#content-5 .filter").mCustomScrollbar({
                theme: "dark-thin"
            });
        });
    </script>
</body>

</html>