<!DOCTYPE html>
<html>
<?php

use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
?>

<style>
    .stars {
        color: #ffb300;
        font-size: 0.8em !important;
    }
    .swal-wide{
    width:850px !important;
}
</style>
<?php
if (isset($int_city) && !empty($int_city)) {
    if ($int_city[0]->g_location_name == '') {
        if ($internship_details->internship_type != 1) {
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

if (isset($internship_details->profile) && $internship_details->profile != '0') {
    // $int_name = "intern";

    $int_name = $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
} else {
    $int_name = $internship_details->other_profile;
}
if (!empty($loction_names)) {
    $loction_name = implode(',', $loction_names);
} else {
    $loction_name = '';
}

?>



<?php
$share_data['intership_name'] = $int_name;
$share_data['intership_loc'] = $loction_name;

echo view('Common/head', $share_data);
//  require_once(APPPATH . "Views/Common/head.php");
$session = session();
$login = $session->get('isLoggedIn');
?>

<body id="socialhide" class="stickyFoot <?php if (!isset($login) && $login == '') {
                                            echo 'resTop';
                                        } ?>">
    <style type="text/css">
        .share-button.sharer .social.active.top {
            transform: scale(1) translateY(-10px);
        }

        .share-button.sharer .social.active {
            opacity: 1;
            transition: all 0.4s ease 0s;
            visibility: visible;
        }

        .share-button.sharer .social.top {
            margin-top: -46px;
            transform-origin: 0 0 0;
        }

        .share-button.sharer .social {
            /*margin-left: -65px;*/
            opacity: 0;
            transition: all 0.4s ease 0s;
            visibility: hidden;

        }

        .disable_social {
            display: none !important;
        }
    </style>
    <?php

    if (isset($login) && $login != '') {
        require_once(APPPATH . "Views/Common/header.php");
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
                                    <div class="input-group searchField headResSearch  headSearchAln me-3">

                                        <input type="search" class="form-control rounded" placeholder="Search Internship" id="search_value" onkeypress="enterpress_search(event, this)" value="<?php
                                                                                                                                                                                                //show when search with keyword
                                                                                                                                                                                                $session = session();
                                                                                                                                                                                                $searched_keyword = $session->get('searched_keyword');
                                                                                                                                                                                                if (isset($searched_keyword)) {

                                                                                                                                                                                                    echo $searched_keyword;
                                                                                                                                                                                                }
                                                                                                                                                                                                ?>">
                                        <button class="btn btn-prim px-3" type="button" id="button-addon2" onclick="search_keyword(4)"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        <!-- <span class="input-group-text">
                                            <img src="<?= base_url(); ?>/public/assets/img/search.svg" alt="Search" width="14">
                                        </span> -->
                                        <span id="search_alert" style="color: red;position: absolute;font-size: 10px;top: 36px;"></span>
                                    </div>
                                </li>
                                <li class="dropdown me-sm-3 me-1">
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

    // use App\Models\Candidate_model;
    ?>

    <!----- Form ------>
    <?php
    // print_r($_SESSION);
    //  print_r($internship_details);

    $new_date = date("Y-m-d", strtotime($internship_details->created_at));
    $now = time(); // or your date as well
    $your_date = strtotime($new_date);
    $datediff = $now - $your_date;
    $post_days = floor($datediff / (60 * 60 * 24));
    $can_userid = $session->get('userid');
    $usertype = $session->get('usertype');
    $ses_data1 = [
        'profile_complete_status',
        'company_logo',
        'company_name',
        'intership_profile',
        'intership_number',
        'profile_page_view'
    ];
    $session->remove($ses_data1);
    // print_r($_SESSION); 
    ?>
    <div class="d-md-none d-block mt-3">
        &nbsp;
    </div>
    <section class="container my-4 pt-sm-0">
        <div class="d-flex flex-wrap cInSingle row">
            <div class="col-12">
                <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>
                <div class="d-flex justify-content-end mb-2">
                    <?php if (isset($login) && $login != '') { ?>
                        <a href="#" class="text-blue backBtn" onclick="previous()"><i class="fa fa-long-arrow-left me-1" aria-hidden="true"></i> Back</a>
                    <?php } else { ?>
                        <a href="#" class="text-blue backBtn" onclick="previous()"><i class="fa fa-long-arrow-left me-1" aria-hidden="true"></i> Back</a>
                    <?php } ?>
                </div>
                <div class="card canInSingle p-md-4 p-3 mb-4">
                    <div class="des-sm-none mb-2">
                        <span class="cCompLogo d-flex justify-content-center align-items-center p-1 rounded">
                            <?php if (isset($emp_profile_details->profile_company_logo) && !empty($emp_profile_details->profile_company_logo)) { ?>
                                <?php
                                $check = file_exists(FCPATH . "public/assets/docs/uploads/emp_profile/" . $emp_profile_details->profile_company_logo);
                                ?>
                                <?php if ($check) { ?>

                                    <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $emp_profile_details->profile_company_logo; ?>" alt="logo" class="img-fluid noStretch" width="60" style="border-radius: 50%;">
                                <?php } else { ?>
                                    <a class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                        <span><?php if (!empty($emp_profile_details->profile_company_name)) {
                                                    echo $firstStringCharacter = substr($emp_profile_details->profile_company_name, 0, 1);
                                                } ?></span>
                                    </a>
                                <?php } ?>

                            <?php } else { ?>
                                <a class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                    <span><?php if (!empty($emp_profile_details->profile_company_name)) {
                                                echo $firstStringCharacter = substr($emp_profile_details->profile_company_name, 0, 1);
                                            } ?></span>
                                </a>
                            <?php } ?>
                        </span>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between">
                        <div class="internTtl col-12 col-md-10">





                            <h3 class="fw-semibold text-white fs-4 mb-2"><?php if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                                                                echo $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                                                            } else {
                                                                                echo $internship_details->other_profile;
                                                                            } ?></h3>
                            <h6 class="text-white fw-normal d-flex flex-wrap align-items-sm-center flex-sm-row flex-column">
                                <?php
                                $where_empl = array('status' => '1', 'userid' => $internship_details->company_id);
                                $view_profile_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_empl);
                                if (isset($view_profile_details->profile_company_name)) {
                                    echo '<a href="' . base_url() . '/employer-details/' . $internship_details->company_id . '" class="text-white">' . $view_profile_details->profile_company_name . '</a>';
                                }

                                if (isset($int_city) && !empty($int_city)) {
                                    if ($int_city[0]->g_location_name == '') {
                                        if ($internship_details->internship_type != 1) {
                                            $loction_names[] = 'Work From Home'; ?>
                                            <span class="text-muted">
                                                <ul class="d-flex flex-wrap list-unstyled mb-0 ms-sm-3 mt-2 mt-sm-0">
                                                    <li class=""><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                                </ul>
                                            </span>
                                    <?php }
                                    } ?>
                                    <span class="text-muted">
                                        <ul class="d-flex flex-wrap list-unstyled mb-0 ms-sm-3 mt-2 mt-sm-0">
                                            <?php foreach ($int_city as $city) {
                                                if ($city->g_location_name != '') { ?>
                                                    <li class=""><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i><?php //echo $Candidate_model->get_master_name('master_city', $city->work_location, 'city'); 
                                                                                                                                                                                                echo $city->g_location_name;
                                                                                                                                                                                                $loction_names[] = $city->g_location_name; ?></span></li>
                                            <?php }
                                            } ?>
                                        </ul>
                                    </span>
                                <?php } ?>
                            </h6>
                        </div>
                        <div class="text-end col-12 col-sm-2 d-flex justify-content-end res-sm-none">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                            <span class="cCompLogo d-flex justify-content-center align-items-center p-1 rounded">
                                <?php if (isset($emp_profile_details->profile_company_logo) && !empty($emp_profile_details->profile_company_logo)) { ?>
                                    <?php
                                    $check = file_exists(FCPATH . "public/assets/docs/uploads/emp_profile/" . $emp_profile_details->profile_company_logo);
                                    ?>
                                    <?php if ($check) { ?>

                                        <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $emp_profile_details->profile_company_logo; ?>" alt="logo" class="img-fluid noStretch" width="60" style="border-radius: 50%;">
                                    <?php } else { ?>
                                        <span class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                            <span><?php if (!empty($emp_profile_details->profile_company_name)) {
                                                        echo $firstStringCharacter = substr($emp_profile_details->profile_company_name, 0, 1);
                                                    } ?></span>
                                        </span>
                                    <?php } ?>

                                <?php } else { ?>
                                    <span class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                        <span><?php if (!empty($emp_profile_details->profile_company_name)) {
                                                    echo $firstStringCharacter = substr($emp_profile_details->profile_company_name, 0, 1);
                                                } ?></span>
                                    </span>
                                <?php } ?>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between">
                        <p class="text-white">Posted <?php if ($post_days == 0) {
                                                            echo 'Today';
                                                        } elseif ($post_days == '1') {
                                                            echo 'Yesterday';
                                                        } else {
                                                            echo $post_days . ' Days Ago';
                                                        } ?></p>
                        <p class="text-white fw-medium"> <?php if (isset($applicant_count) && $applicant_count != 0 && $applicant_count != 1) {
                                                                echo $applicant_count . ' Applicant';
                                                            } else {
                                                                echo '';
                                                            } ?> </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row flex-wrap justify-content-between mt-3">
                        <div class="d-flex flex-wrap flex-column flex-md-row mb-3 mb-xl-0">
                            <div class="d-flex flex-wrap flex-column flex-md-row">

                                <div class="pe-5 mb-lg-0 mb-3">
                                    <p class="text-gray2 mb-0 f-13">Stipend / Month</p>

                                    <h5 class="fs-6 text-white fw-medium mb-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/stipend_fill.svg" alt="" class="me-1 mb-1">
                                        <?php if ($internship_details->stipend != '1') {
                                            if (isset($internship_details->amount_from) && $internship_details->amount_from != '0') {
                                                echo '₹ ' . $internship_details->amount_from;
                                            } ?> <?php if (isset($internship_details->amount_to) && $internship_details->amount_to != '0') {
                                                        echo '- ' . $internship_details->amount_to;
                                                    } ?><?php } else {
                                                        echo "Unpaid";
                                                    } ?>

                                    </h5>
                                </div>
                                <div class="pe-5 mb-lg-0 mb-3">
                                    <p class="text-gray2 mb-0 f-13">Duration</p>
                                    <h5 class="fs-6 text-white fw-medium mb-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/duration_fill.svg" alt="" class="me-1 mb-1">
                                        <?php if (isset($internship_details->internship_duration)) {
                                            echo $internship_details->internship_duration;
                                        } ?> <?php if (isset($internship_details->internship_duration_type)) {
                                                    if ($internship_details->internship_duration_type == 1) {
                                                        // echo "Week";
                                                        if ($internship_details->internship_duration == 1) {
                                                            echo "Week";
                                                        } else {
                                                            echo "Weeks";
                                                        }
                                                    } elseif ($internship_details->internship_duration_type == 2) {
                                                        // echo "Months";
                                                        if ($internship_details->internship_duration == 1) {
                                                            echo "Month";
                                                        } else {
                                                            echo "Months";
                                                        }
                                                    }
                                                } ?>
                                    </h5>

                                </div>
                                <div class="pe-5 mb-lg-0 mb-3">
                                    <p class="text-gray2 mb-0 f-13">Type</p>
                                    <h5 class="fs-6 text-white fw-medium mb-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/job_fill.svg" alt="" class="me-1 mb-1">
                                        <?php if ($internship_details->internship_type == 1) {
                                            echo "Regular (In-Office)";
                                        } else {
                                            echo "Work From Home";
                                            $loction_names[] = 'Work From Home';
                                        } ?>
                                    </h5>

                                </div>
                            </div>
                            <div class="d-flex flex-wrap">
                                <div class="pe-sm-3 pe-2 align-self-end">
                                    <h5 class="fs-6 text-white fw-medium featBorder px-md-3 px-0 py-2 mb-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/type_fill.svg" alt="" class="me-1 mb-1" width="15">
                                        <?php if ($internship_details->partime == 1) {
                                            echo "Part Time";
                                        } else {
                                            echo "Full Time";
                                        } ?>
                                    </h5>
                                </div>
                                <div class="align-self-end">
                                    <h5 class="fs-6 text-white fw-medium featBorder px-3 py-2 mb-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/opening_fill.svg" alt="" class="me-1 mb-1" width="18">
                                        <?php if (isset($internship_details->number_opening)) {
                                            echo $internship_details->number_opening;
                                        } ?> Openings <?php if($internship_details->prefer_gender==1){ echo "( Preferably Male )";}else if($internship_details->prefer_gender==2){ echo "( Preferably Female )"; } ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <!-- ----------------------------social share------------------------ -->


                        <div class="featBtns align-self-start align-self-md-end  position-relative <?php if (!isset($login) && $login == '') {
                                                                                                        echo 'resShare';
                                                                                                    } ?>">

                            <?php if ($internship_details->premium_status == 1) { ?>
                                <span class="me-2">
                                    <img src="<?= base_url(); ?>/public/assets/img/intern_plus.svg" alt="Premium" style="padding-bottom: 2px;" class="img-fluid" width="35">
                                </span>
                            <?php } ?>
                            <input type="hidden" id="url_copy" value="<?php echo base_url('view-internship-details/' . $internship_details->internship_id); ?>">
                            <?php if (($session->get('usertype') == '1') || (!isset($login) && $login == '')) { ?>
                                <?php
                                $where_apply = array('status' => '1', 'internship_id' => $internship_details->internship_id, 'candidate_id' => $can_userid);
                                $apply_internship_details = $Candidate_model->fetch_table_row('can_applied_internship', $where_apply);
                                if (isset($apply_internship_details)) {
                                    if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                        $profile = $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                    } else {
                                        $profile =  $internship_details->other_profile;
                                    }
                                    $current_date = date("d-m-Y");
                                    $applied_date = date("d-m-Y", strtotime($apply_internship_details->created_at));
                                    $new_date = date("Y-m-d", strtotime($apply_internship_details->created_at));
                                    $now = time(); // or your date as well
                                    $your_date = strtotime($new_date);
                                    $datediff = $now - $your_date;
                                    $post_days = floor($datediff / (60 * 60 * 24));
                                    if (isset($login) && $login != '') {
                                        $where_book = array('bookmark_status' => '1', 'status' => '1', 'internship_id' => $internship_details->internship_id, 'can_user_id' => $can_userid, 'emp_user_id' => $emp_profile_details->userid);
                                        $bookmark_details = $Candidate_model->fetch_table_row('can_bookmark_details', $where_book);
                                        // print_r($bookmark_details);
                                        if (isset($bookmark_details)) { ?>
                                            <button onclick="func_can_bookmark('2','<?php echo $internship_details->internship_id; ?>','<?php echo $emp_profile_details->userid; ?>','<?php echo $profile; ?>')" class="btn btn-outlined-white bookmarked" data-bs-toggle="tooltip" data-bs-placement="top" title="Bookmark"><i class="fa fa-bookmark-o" aria-hidden="true"></i></button>
                                        <?php  } else {
                                        ?>
                                            <button onclick="func_can_bookmark('1','<?php echo $internship_details->internship_id; ?>','<?php echo $emp_profile_details->userid; ?>','<?php echo $profile; ?>')" class="btn btn-outlined-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Bookmark"><i class="fa fa-bookmark-o" aria-hidden="true"></i></button>
                                    <?php
                                        }
                                    }
                                    ?>



                                    <?php
                                    $current_date = date("Y-m-d");
                                    $duration_type = '';
                                    $duration_count_1 = $internship_details->internship_duration;
                                    if (isset($internship_details->internship_duration_type)) {
                                        if ($internship_details->internship_duration_type == 1) {
                                            // echo "Week";
                                            if ($internship_details->internship_duration == 1) {
                                                $duration_type = "Week";
                                            }
                                        } elseif ($internship_details->internship_duration_type == 2) {
                                            // echo "Months";
                                            if ($internship_details->internship_duration == 1) {
                                                $duration_type = "Month";
                                            }
                                        }
                                    }

                                    // $end_date=date("Y-m-d",strtotime("+2 month",strtotime(date("Y-m-01",strtotime("now") ) )));
                                    // $expire_date = date("Y-m-d", strtotime("+" . $duration_count_1 . " " . $duration_type . " ", strtotime(date($internship_details->internship_startdate))));
                                    $expire_date = $internship_details->internship_startdate;

                                    if ($apply_internship_details->hiring_status == 1) {
                                        if ($internship_details->internship_startdate <= $current_date) { ?>
                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                <?php
                                                echo "<span class='badge status-ongoing fw-normal ms-1'>Ongoing</span>";
                                                ?></span><?php
                                                        } else { ?>
                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the employers offer">
                                                <?php
                                                            echo "<span class='badge badge-completed fw-normal ms-1'>Offer accepted</span>";
                                                ?></span><?php
                                                        }
                                                    } elseif ($apply_internship_details->hiring_status == 2) { ?>
                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have rejected the employers offer">
                                            <?php
                                                        echo "<span class='badge badge-red fw-normal ms-1'>Offer declined</span>";
                                            ?></span><?php
                                                    } elseif ($apply_internship_details->hiring_status == 4) {
                                                        if ($internship_details->internship_startdate <= $current_date) { ?>
                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                <?php
                                                            echo "<span class='badge badge-red fw-normal ms-1'>Offer declined</span>";
                                                ?></span><?php
                                                        } else { ?>
                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="When you opt for under consideration, you will have time till internship start date to accept the offer. If you fail to accept the offer before that, then status is set as 'offer declined'">
                                                <?php
                                                            echo "<span class='badge status-consider fw-normal ms-1'>Under Consideration</span>";
                                                ?></span><?php
                                                        }
                                                    } elseif ($apply_internship_details->complete_status == 1) {
                                                        if ($apply_internship_details->complete_type != 1) { ?>
                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not completed the internship">
                                                <?php
                                                            echo "<span class='badge status-dropped fw-normal ms-1'>Dropped</span>";
                                                ?></span><?php
                                                        } else { ?>
                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have completed the internship">
                                                <?php
                                                            echo "<span class='badge badge-completed fw-normal ms-1'>Completed</span>";
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
                                                                                                                                                                                                            echo "<span class='badge status-expired fw-normal ms-1'>Offer expired</span>";
                                                    ?></span><?php
                                                                                                                                                                                                        } else { ?>
                                                <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You are hired by the employer">
                                                    <?php
                                                                                                                                                                                                            echo "<span class='badge badge-completed fw-normal mb-1 me-2 ms-1'>Hired</span>";
                                                    ?></span><?php
                                                                                                                                                                                                        }
                                                                                                                                                                                                    } elseif ($apply_internship_details->application_status == 3) { ?>
                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is rejected by the employer">
                                                <?php
                                                                                                                                                                                                        echo "<span class='badge status-notqualified fw-normal ms-1'>Not Qualified</span>";
                                                ?></span><?php
                                                                                                                                                                                                    }
                                                                                                                                                                                                }

                                                            ?>




                                    <?php } else {
                                    if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                        $profile = $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                    } else {
                                        $profile =  $internship_details->other_profile;
                                    }
                                    if (isset($emp_profile_details->profile_company_logo) && !empty($emp_profile_details->profile_company_logo)) {
                                        $profile_company_logo = $emp_profile_details->profile_company_logo;
                                    } else {
                                        $profile_company_logo = '';
                                    }
                                    if (isset($emp_profile_details->profile_company_name) && !empty($emp_profile_details->profile_company_name)) {
                                        $profile_company_name = $emp_profile_details->profile_company_name;
                                    } else {
                                        $profile_company_name = '';
                                    }

                                    $ses_data = [
                                        'profile_complete_status' => 1,
                                        'company_logo' => $profile_company_logo,
                                        'company_name' => $profile_company_name,
                                        'intership_profile' => $profile,
                                        'intership_number' => $internship_details->internship_id
                                    ];
                                    $session->set($ses_data);
                                    if (isset($login) && $login != '' && ($internship_details->internship_candidate_lastdate > date('Y-m-d'))) {
                                        if ($internship_details->active_status == 1) {
                                            $where_book = array('bookmark_status' => '1', 'status' => '1', 'internship_id' => $internship_details->internship_id, 'can_user_id' => $can_userid, 'emp_user_id' => $emp_profile_details->userid);
                                            $bookmark_details = $Candidate_model->fetch_table_row('can_bookmark_details', $where_book);
                                            // print_r($bookmark_details);
                                            if (isset($bookmark_details)) { ?>
                                                <button onclick="func_can_bookmark('2','<?php echo $internship_details->internship_id; ?>','<?php echo $emp_profile_details->userid; ?>','<?php echo $profile; ?>')" class="btn btn-outlined-white bookmarked" data-bs-toggle="tooltip" data-bs-placement="top" title="Bookmark"><i class="fa fa-bookmark-o" aria-hidden="true"></i></button>
                                            <?php  } else {
                                            ?>
                                                <button onclick="func_can_bookmark('1','<?php echo $internship_details->internship_id; ?>','<?php echo $emp_profile_details->userid; ?>','<?php echo $profile; ?>')" class="btn btn-outlined-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Bookmark"><i class="fa fa-bookmark-o" aria-hidden="true"></i></button>
                                        <?php
                                            }
                                        }
                                    }
                                    // if($internship_details->internship_candidate_lastdate >= date('Y-m-d')){
                                    if ($internship_details->active_status == 0 || $internship_details->internship_candidate_lastdate < date('Y-m-d')) { ?>
                                        <span class="fs-6 fw-medium timeClosed closedFlag pe-3 ps-2 py-2"><span class="me-2"><img src="<?= base_url(); ?>/public/assets/img/timer.svg" alt="" width="13" class="mb-1"></span> Internship Closed</span>
                                    <?php  } else { ?>
                                        <?php //if (!empty($profile_personal->g_location_name) && !empty($profile_personal->profile_gender) && !empty($education_details) && ($profile_personal->mobile_verify_status==1) && ($profile_personal->email_verify_status==1) && ($profile_personal->can_profile_complete_status==1)) { 
                                        if (isset($login) && $login != '' && $usertype == '1') { 
                                       if (!empty($profile_personal->g_location_name) && !empty($profile_personal->profile_gender) && !empty($education_details) && ($profile_personal->mobile_verify_status == 1) && ($profile_personal->can_profile_complete_status == 1)) {
                                            /** If check paid or unpaid internship */
                                            if ($internship_details->premium_status == '1') { ?>

                                                <!--premium plan user -->

                                                <?php if ($profile_personal->payment_package_type == 2 || $profile_personal->payment_package_type == 3) {
                                                    if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                                        <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->
                                                        <?php if($internship_details->prefer_gender!='4'  && $internship_details->prefer_gender!=NULL) {?>
                                                            <?php if($profile_personal->profile_gender==$internship_details->prefer_gender){ ?>
                                                        <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                   <?php }else{ ?>
                                                    <a onclick="gender_alert()" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                  <?php  } ?>
                                                        <?php }else{ ?>
                                                    <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                    <?php } ?>
                                                    <?php } else { ?>
                                                        <a onclick="pricing_plan_subscription(1)" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                    <?php
                                                    } ?>
                                                    <!--plus plan user -->
                                                    <?php } elseif ($profile_personal->payment_package_type == 1) {
                                                    $date = DateTime::createFromFormat('d-m-Y h:i:sa', $profile_personal->payment_date);
                                                    $outputDate = $date->format('Y-m-d');
                                                    $where_int = array('can_applied_internship.status' => '1','employer_post_internship.premium_status' => '1', 'can_applied_internship.candidate_id' => $profile_personal->userid, 'date(can_applied_internship.created_at)>=' => $outputDate, 'date(can_applied_internship.created_at)<=' => $profile_personal->payment_expiry_date);
                                                    $candidate_applied_details = $Candidate_model->fetch_table_data_premium_check('can_applied_internship', ('can_applied_internship.id'), $where_int);
                                                    //  print_r($candidate_applied_details);exit;
                                                    if(!empty($candidate_applied_details)){
                                                        $applied_count=  count($candidate_applied_details);
                                                    }else{
                                                        $applied_count=  '0';
                                                    }
                                                 
                                                    if (!empty($candidate_applied_details) && count($candidate_applied_details) > 2 && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                                        <a onclick="pricing_plan_subscription(3)" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                    <?php  } else { ?>
                                                        <?php if($internship_details->prefer_gender!='4'  && $internship_details->prefer_gender!=NULL) {?>
                                                            <?php if($profile_personal->profile_gender==$internship_details->prefer_gender){ ?>
                                                        <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->
                                                        <a onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>');remaining_alert('<?php echo $applied_count; ?>','<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 "><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                        <?php }else{ ?>
                                                    <a onclick="gender_alert()" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                  <?php  } ?>
                                                        <?php }else{ ?>
                                                            <a onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>');remaining_alert('<?php echo $applied_count; ?>','<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                    <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->
                                                    <?php } ?>
                                                   <?php  }

                                                    ?>
                                                    <!-- Free plan user -->
                                                <?php } else { ?>
                                                    <a onclick="pricing_plan_subscription(1)" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                <?php } ?>
                                                <!-- Unpaid internship All users-->
                                            <?php  } else {
                                            ?>
                                                       <?php if($internship_details->prefer_gender!='4'  && $internship_details->prefer_gender!=NULL) {?>
                                                            <?php if($profile_personal->profile_gender==$internship_details->prefer_gender){ ?>
                                                        <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                   <?php }else{ ?>
                                                    <a onclick="gender_alert()" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                  <?php  } ?>
                                                        <?php }else{ ?>
                                                    <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                    <?php } ?>
                                                <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->
                                            <?php }
                                        } else {
                                            // if (empty($profile_personal->profile_phone_number) || empty($profile_personal->profile_email) || empty($profile_personal->g_location_name) || empty($profile_personal->profile_gender) || ($profile_personal->mobile_verify_status==0) || ($profile_personal->email_verify_status==0)) { 
                                            if (empty($profile_personal->profile_phone_number) || empty($profile_personal->g_location_name) || empty($profile_personal->profile_gender) || ($profile_personal->mobile_verify_status == 0)) { ?>
                                                <button onclick="func_next_profile(1,'<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</button>
                                            <?php } elseif (empty($education_details)) {
                                            ?>
                                                <button onclick="func_next_profile(2,'<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</button>
                                            <?php } else { ?>
                                                <button onclick="func_next_profile(2,'<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</button>

                                        <?php  }
                                        } ?>

                                    <?php   }
                                }
                            }  }else {
                                if ($internship_details->active_status == 0 || $internship_details->internship_candidate_lastdate < date('Y-m-d')) { ?>
                                    <span class="fs-6 fw-medium timeClosed closedFlag pe-3 ps-2 py-2"><span class="me-2"><img src="<?= base_url(); ?>/public/assets/img/timer.svg" alt="" width="13" class="mb-1"></span> Internship Closed</span>
                                <?php } else { ?>

                            <?php   }
                            } ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card p-4 mb-4 canInSingleMinH">
                    <?php if (isset($internship_details->pre_placement_offer) && ($internship_details->pre_placement_offer == '1')) { ?>
                        <div class="alert alert-info py-2 mb-4 f-14" role="alert">
                            <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                            This Internship Comes With Pre Placement Opportunity
                        </div>
                    <?php } ?>

                    <h5 class="fw-medium text-blue btm_line mb-3"><img src="<?= base_url(); ?>/public/assets/img/icon_company1.svg" alt="" class="me-2 mb-1" width="19"> About <?php if (isset($emp_profile_details->profile_company_name)) {
                                                                                                                                                                                    echo $emp_profile_details->profile_company_name;
                                                                                                                                                                                } ?> <?php if (isset($emp_profile_details->profile_website_details) && !empty($emp_profile_details->profile_website_details)) { ?><a class="text-blue1 fw-medium d-inline-block compWebLink align-self-start" href="<?php echo $emp_profile_details->profile_website_details; ?>" target="_blank"><?php if (isset($emp_profile_details->profile_company_name)) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        //echo $emp_profile_details->profile_company_name;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    } ?><img src="<?= base_url(); ?>/public/assets/img/icon_link.svg" alt="" class="ms-2" width="15"></a><?php } ?></h5>

                    <p><?php if (isset($emp_profile_details->profile_company_description)) {
                            echo nl2br($emp_profile_details->profile_company_description);
                        } ?></p>
<?php if (!empty($internship_details->about_internship)) {
                        ?>       
                    <h5 class="fw-medium text-blue btm_line mt-4 mb-3"><img src="<?= base_url(); ?>/public/assets/img/hire.svg" alt="" class="me-2 mb-1" width="17"> About The Internship</h5>
                    <!-- <p class="fw-medium mb-2">Selected Intern's Day-To-Day Responsibilities Include:</p> -->
                    <ul class="list-unstyled ps-3">
                        <li><?php if (isset($internship_details->about_internship)) {

                                echo nl2br($internship_details->about_internship);
                            } ?></li>
                    </ul>
                    <?php }
                    ?>
                    <?php if (!empty($int_edu)) { ?>
                        <h5 class="fw-medium text-blue  btm_line mt-4 mb-4"><img src="<?= base_url(); ?>/public/assets/img/icon_pedu.svg" alt="" class="me-2 mb-1" width="20"> Preferred Education Qualification</h5>
                        <ul class="d-flex flex-wrap list-unstyled"><span class="f-9 align-self-start me-1 shortlist_left_bar">Degree - </span>
                            <?php
                            foreach ($int_edu as $edu) { ?>
                                <li class="me-3 mb-2"><span class="badge bg-gray fw-normal text-dark f-14 px-3 py-2"><?php echo $Candidate_model->get_master_name('master_academic_courses', $edu->education, 'name'); ?></span></li>
                            <?php }
                            ?>
                        </ul>
                    <?php }
                    ?>
                                  <?php if (!empty($int_spe)) {
                        ?>
                            <!-- <h5 class="fw-medium text-blue mt-4 mb-4 btm_line"><img src="<?= base_url(); ?>/public/assets/img/icon_edu.svg" alt="" class="me-2 mb-1" width="20"> Preferred Specialization</h5> -->
                            <ul class="d-flex flex-wrap list-unstyled"><span class="f-9 align-self-start me-1 shortlist_left_bar">Specialization - </span>
                                <?php foreach ($int_spe as $sp) { ?>
                                    <li class="me-3 mb-2"><span class="badge bg-gray fw-normal text-dark f-14 px-3 py-2"><?php echo $Candidate_model->get_master_name('master_academic_branch', $sp->specialization, 'name'); ?></span></li>
                                <?php } ?>
                            </ul>
                        <?php  } ?>
                    <?php if (!empty($int_skill)) { ?>
                        <h5 class="fw-medium text-blue btm_line mt-4 mb-4"><img src="<?= base_url(); ?>/public/assets/img/icon_skill1.svg" alt="" class="me-2 mb-1" width="15"> Skills Required</h5>
                        <ul class="d-flex flex-wrap list-unstyled">
                            <?php
                            foreach ($int_skill as $sk) { ?>
                                <li class="me-3 mb-2"><span class="badge bg-gray fw-normal text-dark f-14 px-3 py-2"><?php echo $Candidate_model->get_master_name('master_skills', $sk->selected_skills, 'skill_name'); ?></span></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                    <h5 class="fw-medium text-blue btm_line mt-4 mb-3"><img src="<?= base_url(); ?>/public/assets/img/hire.svg" alt="" class="me-2 mb-1" width="17"> Responsibilities</h5>
                    <!-- <p class="fw-medium mb-2">Selected Intern's Day-To-Day Responsibilities Include:</p> -->
                    <ul class="list-unstyled ps-3">
                        <li><?php if (isset($internship_details->interns_responsibilities)) {

                                echo nl2br($internship_details->interns_responsibilities);
                            } ?></li>
                    </ul>
                    <!-- <h5 class="fw-medium text-blue btm_line mt-4 mb-3"><img src="<?= base_url(); ?>/public/assets/img/icon_apply2.svg" alt="" class="me-2 mb-1" width="19"> Who Can Apply</h5> -->
                  
                    <!-- <ul class="list-decimal ps-3">
                        <li>Are available for <?php if ($internship_details->internship_type == 1) {
                                                    echo "Regular (In-Office)";
                                                } else {
                                                    echo "Work from home";
                                                } ?> internship.</li>
                        <li>Are available for duration of <?php echo $internship_details->internship_duration; ?> <?php if ($internship_details->internship_duration_type != 1) {
                                                                                                                        // echo "Month";
                                                                                                                        if ($internship_details->internship_duration == 1) {
                                                                                                                            echo "Month";
                                                                                                                        } else {
                                                                                                                            echo "Months";
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        if ($internship_details->internship_duration == 1) {
                                                                                                                            echo "Week";
                                                                                                                        } else {
                                                                                                                            echo "Weeks";
                                                                                                                        }
                                                                                                                        // echo "Week";
                                                                                                                    } ?>.</li>
                    </ul> -->
             
                    <!-- <h5 class="fw-medium text-blue mt-5 mb-3"><img src="<?= base_url(); ?>/public/assets/img/icon_opening.svg" alt="" class="me-2" width="17"> Number of openings</h5>
                    <p class="fw-medium mb-2"><?php if (isset($internship_details->number_opening)) {
                                                    echo $internship_details->number_opening;
                                                } ?></p> -->
                    <?php if (!empty($perks)) {
                    ?>
                        <h5 class="fw-medium text-blue  btm_line mt-4 mb-4"><img src="<?= base_url(); ?>/public/assets/img/perks.svg" alt="" class="me-2 mb-1" width="21"> Perks</h5>
                        <ul class="list-decimal ps-3">
                            <?php foreach ($perks as $per) { ?>
                                <li><?php echo $Candidate_model->get_master_name('master_perks', $per->perks, 'perks'); ?></li>
                            <?php } ?>
                        </ul>
                    <?php  } ?>

                    <?php
 if (isset($login) && $login != '' && $usertype == '1') { 
                    if (isset($apply_internship_details) && !empty($apply_internship_details) || ($internship_details->active_status == 0 || $internship_details->internship_candidate_lastdate < date('Y-m-d'))) { ?>
                        <!-- <span class="fs-6 fw-medium timeClosed closedFlag pe-3 ps-2 py-2"><span class="me-2"><img src="<?= base_url(); ?>/public/assets/img/timer.svg" alt="" width="13" class="mb-1"></span> Internship Closed</span> -->
                    <?php  } else {
                    ?>
                        <?php if (!empty($profile_personal->g_location_name) && !empty($profile_personal->profile_gender) && !empty($education_details) && ($profile_personal->mobile_verify_status == 1) && ($profile_personal->can_profile_complete_status == 1)) {
                            if ($internship_details->premium_status == '1') { ?>

                                <!--premium plan user -->

                                <?php if ($profile_personal->payment_package_type == 2 || $profile_personal->payment_package_type == 3) {
                                    if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                               <?php if($internship_details->prefer_gender!='4' && $internship_details->prefer_gender!=NULL) {?>
                                                            <?php if($profile_personal->profile_gender==$internship_details->prefer_gender){ ?>
                                                        <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                   <?php }else{ ?>
                                                    <a onclick="gender_alert()" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                  <?php  } ?>
                                                        <?php }else{ ?>
                                                    <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                    <?php } ?>
                                        <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->

                                    <?php } else { ?>
                                        <a onclick="pricing_plan_subscription(1)" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                    <?php
                                    } ?>
                                    <!--plus plan user -->
                                    <?php } elseif ($profile_personal->payment_package_type == 1) {
                                    $date = DateTime::createFromFormat('d-m-Y h:i:sa', $profile_personal->payment_date);
                                    $outputDate = $date->format('Y-m-d');
                                    $where_int = array('can_applied_internship.status' => '1','employer_post_internship.premium_status' => '1', 'can_applied_internship.candidate_id' => $profile_personal->userid, 'date(can_applied_internship.created_at)>=' => $outputDate, 'date(can_applied_internship.created_at)<=' => $profile_personal->payment_expiry_date);
                                    $candidate_applied_details = $Candidate_model->fetch_table_data_premium_check('can_applied_internship', ('can_applied_internship.id'), $where_int);
                                    if(!empty($candidate_applied_details)){
                                        $applied_count=  count($candidate_applied_details);
                                    }else{
                                        $applied_count=  '0';
                                    }
                                    //  print_r($candidate_applied_details);exit;
                                    if (!empty($candidate_applied_details) && count($candidate_applied_details) > 2 && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                        <a onclick="pricing_plan_subscription(3)" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                    <?php  } else { ?>
                                        <?php if($internship_details->prefer_gender!='4'  && $internship_details->prefer_gender!=NULL) {?>
                                                            <?php if($profile_personal->profile_gender==$internship_details->prefer_gender){ ?>
                                                        <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->
                                                        <a onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>');remaining_alert('<?php echo $applied_count; ?>','<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                        <?php }else{ ?>
                                                    <a onclick="gender_alert()" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                  <?php  } ?>
                                                        <?php }else{ ?>
                                                            <a onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>');remaining_alert('<?php echo $applied_count; ?>','<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                    <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->
                                                    <?php } ?>
                                        <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->
                                    <?php  }

                                    ?>
                                    <!-- Free plan user -->
                                <?php } else { ?>
                                    <a onclick="pricing_plan_subscription(1)" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                <?php } ?>
                                <!-- Unpaid internship All users-->
                            <?php  } else {
                            ?>
                                       <?php if($internship_details->prefer_gender!='4'  && $internship_details->prefer_gender!=NULL) {?>
                                                            <?php if($profile_personal->profile_gender==$internship_details->prefer_gender){ ?>
                                                        <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                   <?php }else{ ?>
                                                    <a onclick="gender_alert()" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                  <?php  } ?>
                                                        <?php }else{ ?>
                                                    <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" onclick="func_internship_session('<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a>
                                                    <?php } ?>
                                <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?php echo $internship_details->internship_id; ?>" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</a> -->
                            <?php }
                        } else {

                            if (empty($profile_personal->profile_phone_number) || empty($profile_personal->g_location_name) || empty($profile_personal->profile_gender) || ($profile_personal->mobile_verify_status == 0)) { ?>
                                <button onclick="func_next_profile(1,'<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</button>
                            <?php } elseif (empty($education_details)) {
                            ?>
                                <button onclick="func_next_profile(2,'<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</button>
                            <?php } else { ?>
                                <button onclick="func_next_profile(2,'<?php echo $internship_details->internship_id; ?>')" class="btn btn-green ms-2 align-self-end"><img src="<?= base_url(); ?>/public/assets/img/icon_job2.svg" alt="Apply" class="iconWhite me-2 mb-1" width="15"> Apply</button>

                    <?php   }
                        }
                    }  }?>


                    <div class="d-flex justify-content-start gap-0 gap-md-5 flex-wrap align-items-center">
                        <?php if (isset($login) && $login != '' && $usertype == '1') { ?>
                            <?php if (isset($apply_internship_details->can_ratings) && $apply_internship_details->can_ratings != 0 && $apply_internship_details->can_ratings != NULL) { ?>
                                <div>
                                    <h5 class="fw-medium text-blue  btm_line mt-4 mb-4"><img src="<?= base_url(); ?>/public/assets/img/candidate_rating.svg" alt="" class="me-2 mb-1" width="21"> My Rating</h5>
                                    <ul class="list-decimal  ps-0">
                                        <div class="d-flex align-items-center fs-3 text-blue">
                                            <?php echo $apply_internship_details->can_ratings ?>
                                            <span class="stars ms-2"> <?php echo "<span class='stars ms-2'>";
                                                                        for ($i = 1; $i <= 5; $i++) {
                                                                            if (round($apply_internship_details->can_ratings - .25) >= $i) {
                                                                                echo "<i class='fa fa-star'></i>"; //fas fa-star for v5
                                                                            } elseif (round($apply_internship_details->can_ratings + .25) >= $i) {
                                                                                echo "<i class='fa fa-star-half-o'></i>"; //fas fa-star-half-alt for v5
                                                                            } else {
                                                                                echo "<i class='fa fa-star-o'></i>"; //far fa-star for v5
                                                                            }
                                                                        }
                                                                        echo '</span>'; ?></span>
                                        </div>

                                    </ul>
                                </div>
                        <?php }
                        } ?>

                        <?php if (isset($rating) && $rating != 0 && $rating != NULL) { ?>
                            <div>

                                <h5 class="fw-medium text-blue  btm_line mt-4 mb-4"><img src="<?= base_url(); ?>/public/assets/img/internship_rating.svg" alt="" class="me-2 mb-1" width="21"> Overall Ratings</h5>
                                <ul class="list-decimal ps-0">
                                    <div class="d-flex align-items-center fs-3 text-blue">
                                        <?php echo $rating ?><span class="stars ms-2"> <?php echo "<span class='stars ms-2'>";
                                                                                        for ($i = 1; $i <= 5; $i++) {
                                                                                            if (round($rating - .25) >= $i) {
                                                                                                echo "<i class='fa fa-star'></i>"; //fas fa-star for v5
                                                                                            } elseif (round($rating + .25) >= $i) {
                                                                                                echo "<i class='fa fa-star-half-o'></i>"; //fas fa-star-half-alt for v5
                                                                                            } else {
                                                                                                echo "<i class='fa fa-star-o'></i>"; //far fa-star for v5
                                                                                            }
                                                                                        }
                                                                                        echo '</span>'; ?></span></div>

                                    <!-- 0<span class="stars ms-2"><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></span></div> -->

                                </ul>
                            </div>
                        <?php } ?>
                    </div>

                </div>

            </div>

            <div class="col-12 col-lg-4 relateIntern">
                <div class="card d-flex flex-wrap flex-row mb-3 p-4 justify-content-between" style="z-index: -1;">
                    <div class="pe-5 mb-sm-0 mb-3">
                        <h5 class="fs-5 text-blue fw-medium mb-0">
                            <?php if (isset($internship_details->internship_candidate_lastdate)) {
                                echo date("d-m-Y", strtotime($internship_details->internship_candidate_lastdate));
                            } ?>
                        </h5>
                        <p class="text-blue mb-0">
                            <img src="<?= base_url(); ?>/public/assets/img/last_date.svg" alt="" class="me-1 mb-1 mt-1" width="14">
                            Last Date To Apply
                        </p>
                    </div>
                    <div class="">
                        <h5 class="fs-5 text-blue fw-medium mb-0">
                            <?php if (isset($internship_details->internship_startdate)) {
                                echo date("d-m-Y", strtotime($internship_details->internship_startdate));
                            } ?>
                        </h5>
                        <p class="text-blue mb-0">
                            <img src="<?= base_url(); ?>/public/assets/img/calendar2.svg" alt="" class="me-1 mb-1 mt-1" width="14">
                            Start Date
                        </p>
                    </div>
                </div>

                <div class="card p-4">


                    <?php if (!empty($int_related_details)) { ?>
                        <h5 class="fw-semibold text-dark text-center mb-3">You May Like These Internships</h5>
                        <div class="relateScroll">
                            <?php foreach ($int_related_details as $internship) {

                                $where_emp = array('status' => '1', 'userid' => $internship->company_id);
                                $related_profile_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                            ?>

                                <div class="card pt-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center px-3">
                                        <div>
                                            <?php if ((!isset($login) && $login == '')) { ?>
                                                <a href="<?php echo base_url('view-internship-details'); ?>/<?php echo $internship->internship_id; ?>" class="">
                                                <?php } else { ?>
                                                    <a href="<?php echo base_url('internship-details'); ?>/<?php echo $internship->internship_id; ?>" class="">
                                                    <?php } ?>

                                                    <h3 class="fw-semibold text-blue fs-6"><?php if (isset($internship->profile) && $internship->profile != '0') {
                                                                                                echo $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                                                            } else {
                                                                                                echo $internship->other_profile;
                                                                                            } ?></h3>
                                                    </a>
                                                    <h6 class="text-blue fw-medium f-14 overflow-anywhere"><?php
                                                                                                            if (isset($related_profile_details->profile_company_name)) {
                                                                                                                echo '<span class="text-muted fw-normal compName">' . $related_profile_details->profile_company_name . '</span>';
                                                                                                            } ?>
                                                    </h6>
                                        </div>
                                        <div class="comLogo d-flex justify-content-center align-items-center rounded p-1 ms-2"> <?php if (isset($related_profile_details->profile_company_logo) && !empty($related_profile_details->profile_company_logo)) { ?>
                                                <?php
                                                                                                                                    $check = file_exists(FCPATH . "public/assets/docs/uploads/emp_profile/" . $related_profile_details->profile_company_logo);
                                                ?>
                                                <?php if ($check) { ?>
                                                    <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $related_profile_details->profile_company_logo; ?>" alt="logo" class="img-fluid noStretch" width="40" style="border-radius: 50%;">

                                                <?php } else { ?>
                                                    <a class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                                        <span><?php if (!empty($related_profile_details->profile_company_name)) {
                                                                                                                                            echo $firstStringCharacter = substr($related_profile_details->profile_company_name, 0, 1);
                                                                                                                                        } ?></span>
                                                    </a>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <a class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                                    <span><?php if (!empty($related_profile_details->profile_company_name)) {
                                                                                                                                        echo $firstStringCharacter = substr($related_profile_details->profile_company_name, 0, 1);
                                                                                                                                    } ?></span>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled d-flex flex-wrap px-3 mb-1"><?php

                                                                                            $where_city = array('status' => '1', 'internship_id' => $internship->internship_id);
                                                                                            $int_city1 = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);
                                                                                            if (isset($int_city1) && !empty($int_city1)) {
                                                                                                if ($int_city1[0]->g_location_name == '') {
                                                                                                    if ($internship->internship_type != 1) { ?>
                                                    <li class="mb-1"><span class="badge bg-gray fw-normal f-13 text-dark me-2"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                                <?php }
                                                                                                }
                                                                                                foreach ($int_city1 as $city) { ?>
                                                <?php
                                                                                                    if ($city->g_location_name != '') {


                                                                                                        echo '<li class="mb-1"><span class="badge bg-gray fw-normal f-13 text-dark me-2"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>' . $city->g_location_name . '</span></li>';
                                                                                                    } ?>
                                        <?php //$Candidate_model->get_master_name('master_city', $city->work_location, 'city');
                                                                                                }
                                                                                            } ?>
                                    </ul>
                                    <ul class="d-flex flex-wrap px-3 py-2 list-unstyled bg-blue-gray mb-0">
                                        <li class="me-4">
                                            <p class="mb-0"><img src="<?= base_url(); ?>/public/assets/img/ico_duration.svg" alt="Duration" class="img-fluid me-1" width="12">
                                                <span class="text-blue mb-0 f-14">Duration</span>
                                            </p>
                                            <p class="fw-normal f-13 lh-sm mb-0"><?php if (isset($internship->internship_duration)) {
                                                                                        echo $internship->internship_duration;
                                                                                    } ?> <?php if (isset($internship->internship_duration_type)) {
                                                                                                if ($internship->internship_duration_type == 1) {
                                                                                                    // echo "Week";
                                                                                                    if ($internship->internship_duration == 1) {
                                                                                                        echo "Week";
                                                                                                    } else {
                                                                                                        echo "Weeks";
                                                                                                    }
                                                                                                } elseif ($internship->internship_duration_type == 2) {
                                                                                                    // echo "Months";
                                                                                                    if ($internship->internship_duration == 1) {
                                                                                                        echo "Month";
                                                                                                    } else {
                                                                                                        echo "Months";
                                                                                                    }
                                                                                                }
                                                                                            } ?></p>
                                        </li>
                                        <li>
                                            <p class="mb-0"><img src="<?= base_url(); ?>/public/assets/img/ico_stipend.svg" alt="Duration" class="img-fluid me-1" width="12">
                                                <span class="text-blue mb-0 f-14">Stipend / Month</span>
                                            </p>
                                            <p class="fw-normal f-13 lh-sm mb-0"><?php if ($internship->stipend != '1') {
                                                                                        if (isset($internship->amount_from) && $internship->amount_from != '0') {
                                                                                            echo '₹ ' . $internship->amount_from;
                                                                                        } ?> <?php if (isset($internship->amount_to) && $internship->amount_to != '0') {
                                                                                                    echo '- ' . $internship->amount_to;
                                                                                                }
                                                                                            } else {
                                                                                                echo "Unpaid";
                                                                                            } ?></p>
                                        </li>
                                    </ul>
                                </div>

                            <?php } ?>
                        </div>
                    <?php   } ?>
                    <img src="<?= base_url(); ?>/public/assets/img/intern_feat.png" alt="Features" class="img-fluid">



                </div>


            </div>
        </div>
    </section>
    <div class="featBtns align-self-end share-button sharer <?php if (!isset($login) && $login == '') {
                                                                echo 'resShare';
                                                            } ?>">
        <a class="share-btn" id="add_hide_class">
            <img src="<?= base_url(); ?>/public/assets/img/share_ico.svg" alt="Share" width="20">
        </a>

        <div onclick="social_hide(0)" id="social_hide_area" class="social socialIcons top center networks-5 d-flex flex-column" style="position: absolute; margin-top: -10px;margin-left: -29px; display: none;">
            <!-- Facebook Share Button -->
            <a target="_blank" class="fbtn share facebook mb-2" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url('view-internship-details/' . $internship_details->internship_id); ?>&text=Internship:<?php echo $int_name; ?>%0aLocation: <?php echo $loction_name; ?>%0a%0a">
                <img src="<?= base_url(); ?>/public/assets/img/facebook_ico.svg" alt="Facebook" width="40">
            </a>
            <!-- Google Plus Share Button -->

            <!-- Twitter Share Button -->
            <a target="_blank" class="fbtn share twitter mb-2" href="https://twitter.com/intent/tweet?url=<?= urlencode(base_url('view-internship-details/' . $internship_details->internship_id)); ?>&text=Internship:<?php echo $int_name; ?>%0a Location: <?php echo $loction_name; ?>%0a%0a" data-size="small" data-text="">
                <img src="<?= base_url(); ?>/public/assets/img/twitter_ico.svg" alt="Twitter" width="40">
            </a>


            <!-- Whatsapp Share Button -->
            <a target="_blank" class="fbtn share pinterest mb-2" href="https://api.whatsapp.com/send?text=<?= urlencode(base_url('view-internship-details/' . $internship_details->internship_id)) . '%0a%0a Internship: ' . $int_name . '%0a Locations: ' . $loction_name; ?>">
                <img src="<?= base_url(); ?>/public/assets/img/whatsapp_ico.svg" alt="Whatsapp" width="40">
            </a>

            <!-- LinkedIn Share Button -->
            <a target="_blank" class="fbtn share linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo base_url('view-internship-details/' . $internship_details->internship_id); ?>">
                <img src="<?= base_url(); ?>/public/assets/img/linkedin_ico.svg" alt="Linkedin" width="40">
            </a>
            <!--  <a class="fbtn share copy" href="#" onclick="copy_this('<?php echo base_url('view-internship-details/' . $internship_details->internship_id); ?>')">
                                    <img src="<?= base_url(); ?>/public/assets/img/link_ico.svg" alt="Link" width="40">
                                </a> -->

        </div>
    </div>
    <!-- Flash alert -->
    <!-- <div class="alert alert-success flash-alert overflow-anywhere" role="alert">Bookmark Added</div> -->
    <!-- <div class="alert alert-info flash-alert overflow-anywhere" role="alert">Bookmark Added</div>
    <div class="alert alert-danger flash-alert overflow-anywhere" role="alert">Bookmark Added</div> -->


    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>

    <script>
        function previous() {
            window.history.go(-1);
        }
        // $(document).click(function() {
        //     $(".social").hide('slow');
        // });

        // $(".share-btn").click(function(e) {
        //    e.stopPropagation();
        // }); 


        //swal alert
        <?php
        if (isset($login) && $login != '') {
        ?>

            function func_next_profile(val, intership_id) {

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
                        //     window.location.href = '<?= base_url(); ?>/personal-details';
                        // }
                        // if (val == 2) {
                        //     window.location.href = '<?= base_url(); ?>/education-details';
                        // }
                        window.location.href = '<?= base_url(); ?>/can_apply_before_intern_session/' + val;

                    } else {
                        location.reload();
                        // swal("Cancelled", "You Have Cancelled", "error");
                    }
                })
                // swal("Please Complete Your Profile", "You clicked the button!", "success");
            }
        <?php
        } else {
        ?>

            function func_next_profile(val, intership_id) {

                swal({
                    title: "Alert",
                    text: "Please Login, Before Applying For Internship",
                    type: "info",
                    showCancelButton: false,
                    confirmButtonClass: "btn-primary",
                    confirmButtonText: "Proceed",
                    // cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {

                    if (isConfirm) {
                        window.location.href = '<?= base_url("Login-Web"); ?>/' + intership_id;

                    } else {
                        location.reload();
                    }
                })
                // swal("Please Complete Your Profile", "You clicked the button!", "success");
            }
        <?php } ?>

        function func_can_bookmark(val, internship_id, emp_user_id, profile) {
            // alert(emp_user_id);
            if (val == 1) {
                window.location.href = '<?= base_url(); ?>/can_intership_bookmark_single/' + val + '/' + internship_id + '/' + emp_user_id + '/' + profile + '/1';
            } else {
                window.location.href = '<?= base_url(); ?>/can_intership_bookmark_single/' + val + '/' + internship_id + '/' + emp_user_id + '/' + profile + '/1';
            }
        }


        $(document).ready(function() {
            //custom button for homepage
            $(".share-btn").click(function(e) {
                $('.networks-5').not($(this).next(".networks-5")).each(function() {
                    $(this).removeClass("active");
                });

                $(this).next(".networks-5").toggleClass("active");
            });
        });

        function copy_this(text_link) {
            //window.prompt("Copy to clipboard: Ctrl+C, Enter", text_link);
            // var copyText = document.getElementById("url_copy");            
            //  copyText.select();
            //  copyText.setSelectionRange(0, 99999);
            //  navigator.clipboard.writeText(copyText.value);     


            let url = text_link;

            navigator.clipboard.writeText(url).then(function() {
                alert('Copied!');
            }, function() {
                alert('Copy error')
            });
        }
        //social media 

        $(document).mouseup(function(e) {
            var container = $("#social_hide_area");
            // If the target of the click isn't the container

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                const divclass = document.querySelector('#social_hide_area');

                if (divclass.classList.contains('active')) {
                    $("#social_hide_area").removeClass('active');
                }
            }


        });
    </script>

    <script>
        (function($) {
            $(window).on("load", function() {

                $(".relateScroll").mCustomScrollbar({
                    setHeight: 310,
                    theme: "minimal-dark"
                });

            });
        })(jQuery);


        // function pricing_plan_subscription(val) {
            
        //     if (val == 3) {
        //         var title_val = "Upgrade your plan";
        //         var text_val = "Access to more internships requires a plan upgradation.";

        //     } else {
        //         var title_val = "Subscribe now";
        //         var text_val = "Access to paid features requires a subscription.";

        //     }

        //     swal({
        //         title: title_val,
        //         text: text_val,
        //         type: "info",
        //         showCancelButton: true,
        //         confirmButtonClass: "btn-primary",
        //         confirmButtonText: "Proceed",
        //         cancelButtonText: "Cancel",
        //         closeOnConfirm: false,
        //         closeOnCancel: false
        //     }, function(isConfirm) {

        //         if (isConfirm) {
        //             window.location.href = "<?php echo base_url('pricing-plan'); ?>";
        //         } else {
        //             location.reload();
        //         }
        //     });
        // }

        function gender_alert() {
   
                var title_val = "Alert";
                var text_val = "Your gender doesn't align with the prerequisites for this internship. Please consider exploring other internship opportunities.";


            swal({
                title: title_val,
                text: text_val,
                type: "info",
                showCancelButton: false,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "OK",
                // cancelButtonText: "Cancel",
                closeOnConfirm: true,
                // closeOnCancel: false
            }, function(isConfirm) {

            });
        }



        // function remaining_alert(count,internship_id) {

        //     if (count == 0) {
        //         var title_val = "Remaining 3";
        //         var text_val = "You have the opportunity to apply for 3 exclusive premium internships.";

        //     } else if(count == 1) {
        //         var title_val = "Remaining 2";
        //         var text_val = "You have the opportunity to apply for 2 exclusive premium internships.";

        //     }else if(count == 2) {
        //         var title_val = "Remaining 1";
        //         var text_val = "You have the opportunity to apply for 1 exclusive premium internships.";

        //     }

        //     swal({
        //         title: title_val,
        //         text: text_val,
        //         type: "info",
        //         showCancelButton: true,
        //         confirmButtonClass: "btn-primary",
        //         confirmButtonText: "Proceed",
        //         cancelButtonText: "Cancel",
        //         closeOnConfirm: true,
        //         closeOnCancel: true
        //     }, function(isConfirm) {

        //         if (isConfirm) {
        //             window.location.href = "<?php echo base_url('can-apply-for-internship'); ?>/"+ internship_id;
        //         } else {
        //             location.reload();
        //         }
        //     });
        // }

        function remaining_alert(count,internship_id) {
           
            if (count == 0) {
                var title_val = "Remaining 3";
                var text_val = "You have the opportunity to apply for 3 exclusive premium internships.";

            } else if(count == 1) {
                var title_val = "Remaining 2";
                var text_val = "You have the opportunity to apply for 2 exclusive premium internships.";

            }else if(count == 2) {
                var title_val = "Remaining 1";
                var text_val = "You have the opportunity to apply for 1 exclusive premium internships.";

            }
            <?php $randomNumber = rand(1, 3); ?>
            var randomNumber = <?php echo $randomNumber; ?>;
            // alert($randomNumber);
            var image = '<?php echo base_url("public/assets/img/upgrade_banner_" . $randomNumber . ".png"); ?>';
          
            var img_url = '<?php echo  base_url('pricing-plan') ?>';

Swal.fire({
    title: title_val,
    text: text_val,
    imageUrl: image,
    imageWidth: 800,
    imageAlt: "Custom image",
    showCancelButton: true,
    customClass: 'swal-wide',
    confirmButtonText: "Proceed to apply",
    cancelButtonText: "Cancel",
    reverseButtons: true,
    didOpen: () => {
        // Add an event listener to the image
        var imageElement = document.querySelector('.swal2-image');
        imageElement.style.cursor = 'pointer';
        imageElement.addEventListener('click', function () {
            // Replace 'yourLink' with the actual hyperlink
            window.open(img_url, '_blank');
        });
    }
}).then((result) => {
  if (result.isConfirmed) {
    // alert(internship_id);
    // sessionStorage.setItem('internshipValue', internship_id);  
    // func_internship_session(internship_id);
    window.location.href = "<?php echo base_url('can-apply-for-internship'); ?>/"+ internship_id;
  }else{
    location.reload();
  }
});

}



function pricing_plan_subscription(val) {
           
    if (val == 3) {
                var title_val = "Upgrade your plan";
                var text_val = "Access to more internships requires a plan upgradation.";

            } else {
                var title_val = "Subscribe now";
                var text_val = "Access to paid features requires a subscription.";

            }
           <?php $randomNumber = rand(1, 3); ?>
           var randomNumber = <?php echo $randomNumber; ?>;
           // alert($randomNumber);
           var image = '<?php echo base_url("public/assets/img/upgrade_banner_" . $randomNumber . ".png"); ?>';
         
           var img_url = '<?php echo  base_url('pricing-plan') ?>';

Swal.fire({
   title: title_val,
   text: text_val,
   imageUrl: image,
   imageWidth: 800,
   imageAlt: "Custom image",
   showCancelButton: true,
   customClass: 'swal-wide',
   confirmButtonText: "Proceed",
   cancelButtonText: "Cancel",
   reverseButtons: true,
   didOpen: () => {
       // Add an event listener to the image
       var imageElement = document.querySelector('.swal2-image');
       imageElement.style.cursor = 'pointer';
       imageElement.addEventListener('click', function () {
           // Replace 'yourLink' with the actual hyperlink
           window.open(img_url, '_blank');
       });
   }
}).then((result) => {
 if (result.isConfirmed) {
    window.location.href = "<?php echo base_url('pricing-plan'); ?>";
 }else{
   location.reload();
 }
});

}

function func_internship_session(val) {
    // alert(val);
    var csrf_val = $(".csrf").val();
            var csrf = "&csrf_test_name=" + csrf_val;
    // alert(val);
    $.ajax({
                        url: "<?php echo base_url('set-session-internship-id'); ?>",
                        method: "POST",
                        data: "&internship_id=" + val + csrf,
                        success: function(response) {

                            var splitted_data = response.split('^');
                            $(".csrf").val(splitted_data[0].trim())
                            
                        },
                    });
}
    </script>

</body>

</html>