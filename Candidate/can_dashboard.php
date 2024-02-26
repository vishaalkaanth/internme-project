<!DOCTYPE html>
<html>

<?php
$session = session();
// print_r($_SESSION);
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
$can_userid = $session->get('userid');
$session = session();
$login = $session->get('isLoggedIn');

use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
$current_date = date("Y-m-d");
if(isset($internship_offers_log[0])){
$where = array('status' => '1', 'internship_id' => $internship_offers_log[0]->internship_id);
$internship_detail = $Candidate_model->fetch_table_data_for_all('employer_post_internship', $where);

}
?>

<body class="stickyFoot">
    <style>
        @media (max-width: 767px) {
            .social_hide_area_1 {
                right: 0px;
            }
        }
    </style>

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>
    <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>
    <section class="canDashboard py-4">
        <div class="container py-3">
            <input type="hidden" class="csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="d-flex flex-wrap">
                <div class="canDashBanner d-flex align-items-center mb-4 py-md-0 py-3 <?php if (!empty($internship_offers_log[0]->hiring_status)) { if($internship_detail[0]->internship_startdate <= $current_date){ ?> col-md-10  <?php }else{echo "col-md-12";}} else { ?>col-md-12 <?php } ?>">
                    <div class="col-md-9 px-lg-5 px-4">
                        <h2 class="text-white lh-base fs-2">Welcome back, <span class="text-yellow"><?php echo $session->get('name'); ?></span></h2>
                        <p class="text-white mb-0 fs-5">We have got some actionable ways for you to land on your dream internship and develop a career!</p>
                    </div>
                    <div class="col-md-3 d-md-block d-none dashBanRt">
                        <img src="<?= base_url(); ?>/public/assets/img/can_dash_banner.svg" alt="" class="img-fluid" width="170">
                    </div>
                </div>
                <?php if (!empty($internship_offers_log[0]->hiring_status)) {
                   //print_r($internship_detail[0]->internship_startdate);exit;
                    if($internship_detail[0]->internship_startdate <= $current_date){ ?>
                    <div class="col-md-2 d-flex align-items-center justify-content-end mb-4 py-md-0 py-3">
                        <div class="dashTile d-flex tile4">
                            <img src="<?= base_url(); ?>/public/assets/img/calendar.svg" alt="" class="" width="30">
                            <div>
                                <h6 class="text-dark">Access Your</h6>
                                <h2 class="text-blue fs-4 fw-bold mb-0">Logsheet</h2>
                            </div>
                            <?php if (!empty($internship_offers_log[0]->hiring_status)) { ?>
                                <a href="<?= base_url(); ?>/candidate-logsheet/<?= $internship_offers_log[0]->internship_id; ?>" class="btn btn-green align-self-end py-1 ms-2">Here</a>
                            <?php } else { ?>
                                <a onclick="func_log_sheet_here()" class="btn btn-green align-self-end py-1 ms-2">Here</a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } }?>
            </div>
            <div class="card cardBdrLt mb-4 p-4">
                <div class="canCounts row d-flex flex-wrap align-items-center">
                    <!-- <div class="col">
                        <h4 class="mb-0 text-blue"><span class="text-muted">My </span><br>Dashboard</h4>
                    </div> -->
                    <div class="col ms-md-4 ms-0">
                        <a href="<?= base_url(); ?>/my-applications">
                            <div class="dashTile d-flex tile1">
                                <img src="<?= base_url(); ?>/public/assets/img/intern_applied.svg" alt="" class="">
                                <div>
                                    <h6 class="text-dark">Internship Applied</h6>
                                    <h2 class="text-blue fs-4 fw-bold mb-0"><?php if (!empty($applied_internship_list)) {
                                                                                echo count($applied_internship_list);
                                                                            } else {
                                                                                echo '0';
                                                                            } ?></h2>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?= base_url(); ?>/offers-received">
                            <div class="dashTile d-flex tile2">
                                <img src="<?= base_url(); ?>/public/assets/img/intern_offer.svg" alt="" class="">
                                <div>
                                    <h6 class="text-dark">Offers Received</h6>
                                    <h2 class="text-blue fs-4 fw-bold mb-0"><?php echo $applied_internship_list_hired_count; ?></h2>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?= base_url(); ?>/my-internships">
                            <div class="dashTile d-flex tile2">
                                <img src="<?= base_url(); ?>/public/assets/img/intern_offer.svg" alt="" class="">
                                <div>
                                    <h6 class="text-dark">Offers Accepted</h6>
                                    <h2 class="text-blue fs-4 fw-bold mb-0"><?php echo $my_count_applied_internship_list; ?></h2>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?= base_url(); ?>/direct-corporate-offers">
                            <div class="dashTile d-flex tile2">
                                <img src="<?= base_url(); ?>/public/assets/img/dash_dir_corp_offer.svg" width="50" alt="" class="">
                                <div>
                                    <h6 class="text-dark">Direct Corporate Offers</h6>
                                    <h2 class="text-blue fs-4 fw-bold mb-0"><?php echo $application_count_applied_internship_list; ?></h2>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?= base_url(); ?>/bookmark">
                            <div class="dashTile d-flex tile3">
                                <img src="<?= base_url(); ?>/public/assets/img/bookmarked.svg" alt="" class="">
                                <div>
                                    <h6 class="text-dark">Bookmarked</h6>
                                    <h2 class="text-blue fs-4 fw-bold mb-0"><?php if (!empty($can_bookmark_list)) {
                                                                                echo count($can_bookmark_list);
                                                                            } else {
                                                                                echo '0';
                                                                            } ?></h2>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- <div class="col">
                        <div class="dashTile d-flex tile4">
                            <img src="<?= base_url(); ?>/public/assets/img/calendar.svg" alt="" class="" width="30">
                            <div>
                                <h6 class="text-dark">Access Your</h6>
                                <h2 class="text-blue fs-4 fw-bold mb-0">Logsheet</h2>
                            </div>
                            <?php if (!empty($internship_offers_log[0]->hiring_status)) { ?>
                                <a href="<?= base_url(); ?>/candidate-logsheet/<?= $internship_offers_log[0]->internship_id; ?>" class="btn btn-green align-self-end py-1 ms-2">Here</a>
                            <?php } else { ?>
                                <a onclick="func_log_sheet_here()" class="btn btn-green align-self-end py-1 ms-2">Here</a>
                            <?php } ?>
                        </div>
                    </div> -->

                </div>
            </div>
            <?php if (!empty($internship_list)) { ?>
                <div class="d-flex flex-wrap justify-content-between align-items-center my-4 pt-3">
                    <h4 class="text-green mb-0">Popular Internships</h4>
                    <!-- <a href="#" class="text-muted">View All</a> -->
                </div>
            <?php } ?>
            <div class="row d-flex flex-wrap gap-3 px-3 px-md-2">
                <?php
                if (!empty($internship_list)) {
                    $i = 1;
                    $list_count = count($internship_list);
                    foreach ($internship_list as $internship) {
                        $where_emp = array('status' => '1', 'userid' => $internship->company_id);
                        $emp_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                ?>
                        <div class="w-30 mb-3 card <?php if($internship->premium_status==1) { ?> card_premium_ico <?php } ?>" style="z-index: <?php echo $list_count; ?>;">
                            <div class=" p-3 pb-0">
                                <div class="d-flex flex-sm-row flex-row-reverse">
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
                                        <div class="d-flex flex-column">
                                            <div>
                                                <a href="<?php echo base_url('/internship-details/' . $internship->internship_id); ?>" class="">
                                                    <h3 class="fw-semibold text-blue me-sm-0 pe-sm-0 me-5 pe-2 fs-5 profile_text" id=""><?php if (isset($internship->profile) && $internship->profile != '0') {
                                                                                                                                            echo $profile = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                                                                                                        } else {
                                                                                                                                            echo $profile = $internship->other_profile;
                                                                                                                                        } ?> </h3>
                                                </a>
                                                <div class="comLogo des-sm-none logoResPos  d-flex justify-content-center align-items-center rounded p-1 me-sm-3 ms-sm-0 ms-2">
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
                                            </div>
                                            <?php
                                            $where_city = array('status' => '1', 'internship_id' => $internship->internship_id);
                                            $int_city = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);
                                            ?>
                                            <h6 class="text-blue fw-medium mb-0">

                                                <?php if (isset($emp_details->profile_company_name) && !empty($emp_details->profile_company_name)) { ?>
                                                    <a href="<?= base_url(); ?>/employer-details/<?php echo $internship->company_id; ?>" class="text-dark comResWidth fw-normal d-inline-block mb-1"><img src="<?= base_url(); ?>/public/assets/img/icon_company1.svg" alt="Location" class="me-2 mb-1 " width="14"><span class="company_text f-13"><?php echo $emp_details->profile_company_name; ?></span></a>
                                                <?php } ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <ul class="d-flex flex-wrap list-unstyled my-2 ">
                                    <?php
                                    if (!empty($int_city)) {
                                        if ($int_city[0]->g_location_name == '') {
                                            if ($internship->internship_type != 1) { ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                            <?php }
                                        }
                                        foreach ($int_city as $city) {
                                            if ($city->g_location_name != '') {  ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13 "><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i><span class="location_text"><?php echo $city->g_location_name; ?></span></span></li>
                                        <?php  }
                                        }
                                    } else { ?>
                                        <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                    <?php    } ?>
                                </ul>
                                <ul class="d-flex flex-wrap ps-0 list-unstyled mb-0">
                                    <li class="me-4 mb-md-3 mb-2">
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
                                    <li class="mb-md-3 mb-2">
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
                                </ul>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="startFrom text-dark f-14 ps-2 pe-3 py-1">
                                        <img src="<?= base_url(); ?>/public/assets/img/ico_start.svg" alt="Start Date" class="img-fluid me-2" width="13">Starting from : <?php if (isset($internship->internship_startdate)) {
                                                                                                                                                                                echo date("d M Y", strtotime($internship->internship_startdate));
                                                                                                                                                                            } ?>
                                    </div>
                                    <?php
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
                                    <div class="d-flex justify-content-start align-items-start">
                                        <a onclick="func_can_bookmark('<?php echo $internship->internship_id; ?>','<?php echo $emp_details->userid; ?>','<?php echo $profile; ?>')" class="bookmarkIco px-2 py-1 fs-4"><span class="bookmark_icon_<?php echo $internship->internship_id; ?>"><i class="<?php if (isset($bookmark_details)) { ?>fa fa-bookmark<?php } else { ?>fa fa-bookmark-o<?php } ?>" <?php if (isset($bookmark_details)) { ?> style="color:#19A540" <?php } ?>></i></a>
                                        <div class="d-flex justify-content-center align-items-center share_parent resShare">
                                            <a class="share-btn-overall" id="add_hide_class" onclick="social_meadia(<?php echo $i; ?>)">
                                                <img src="<?= base_url(); ?>/public/assets/img/share_ico.svg" alt="Share" width="16">
                                            </a>
                                            <div class="social_hide_area_1 " id="socialmedia_hide_show_<?php echo $i; ?>">
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

                                                if (isset($internship->profile) && $internship->profile != '0') {
                                                    // $int_name = "intern";

                                                    $int_name = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                } else {
                                                    $int_name = $internship->other_profile;
                                                }
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
                            </div>
                        </div>

                <?php $list_count--;
                        $i++;
                    }
                } ?>
            </div>
        </div>
    </section>



    <!-------- New Section design for Internships Based on .... Starts here ----->
    <?php  $i = 10;
                    
                    $keyword_result=$Candidate_model->filter_search_keyword();
                    $list_count = count($keyword_result);
                    $all_internship_id=array();
                    $sum=1;  
                    if(isset($keyword_result)&&!empty($keyword_result)){
                        foreach ($keyword_result as $keyword_result_value){
                            $internship_empty = $Candidate_model->fetch_table_data_filter_search_keyword($keyword_result_value['search_key']);

                        }
                        //  print_r($internship_empty);
                        ?>
                         <?php  if(!empty($internship_empty)){ ?>
        <section class="bg_candi_orange py-3">
            <div class="container">
               
                <div class="d-flex flex-wrap justify-content-between align-items-center my-4 pt-3">
                    <h4 class="text-dark mb-0">Internships Based on <span class="candi_dash_search">Your Interest</span></h4>
                </div>

                <div class="row d-flex flex-wrap gap-3 px-3 px-md-2">
                    <div class="card col-12 col-md-6 mb-3 w-30 search_card p-0 align-items-center justify-content-center">
                        <img src="<?= base_url(); ?>/public/assets/img/search_based.svg" alt="search_based" class="img-fluid">
                    </div>
                    <?php

                   
                    foreach ($keyword_result as $keyword_result_value){
                    $internship = $Candidate_model->fetch_table_data_filter_search_keyword($keyword_result_value['search_key']);
                    //foreach ($internship_list_search_key as $internship) {
                        if(!empty($internship)){
                            
                                
                            
                                if(!in_array($internship->internship_id,$all_internship_id)){ 
                                    $all_internship_id[]=$internship->internship_id;
                            if($sum<6){
                            // $find_internship_id=(in_array($internship->internship_id,$all_internship_id));
//   print_r($all_internship_id);
//  echo $internship->internship_id;
                            // if(!empty($find_internship_id)){
                        $where_emp = array('status' => '1', 'userid' => $internship->company_id);
                        $emp_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                    ?>
                        <div class="card col-12 col-md-6 mb-3 w-30 candi_dash_search_card <?php if($internship->premium_status==1) { ?> card_premium_ico <?php } ?>" style="z-index: <?php echo $list_count; ?>;">
                            <div class="p-3 pb-0">
                                <div class="d-flex flex-sm-row flex-row-reverse">
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
                                        <div class="d-flex flex-column">
                                            <div>
                                                <a href="<?php echo base_url('/internship-details/' . $internship->internship_id); ?>" class="">
                                                    <h3 class="fw-semibold text-blue me-sm-0 pe-sm-0 me-5 pe-2 fs-5 profile_text" id=""><?php if (isset($internship->profile) && $internship->profile != '0') {
                                                                                                                                            echo $profile = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                                                                                                        } else {
                                                                                                                                            echo $profile = $internship->other_profile;
                                                                                                                                        } ?> </h3>
                                                </a>
                                                <div class="comLogo des-sm-none logoResPos  d-flex justify-content-center align-items-center rounded p-1 me-sm-3 ms-sm-0 ms-2">
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
                                            </div>
                                            <?php
                                            $where_city = array('status' => '1', 'internship_id' => $internship->internship_id);
                                            $int_city = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);
                                            ?>
                                            <h6 class="text-blue fw-medium mb-0">

                                                <?php if (isset($emp_details->profile_company_name) && !empty($emp_details->profile_company_name)) { ?>
                                                    <a href="<?= base_url(); ?>/employer-details/<?php echo $internship->company_id; ?>" class="text-dark comResWidth fw-normal d-inline-block mb-1"><img src="<?= base_url(); ?>/public/assets/img/icon_company1.svg" alt="Location" class="me-2 mb-1 " width="14"><span class="company_text f-13"><?php echo $emp_details->profile_company_name; ?></span></a>
                                                <?php } ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <ul class="d-flex flex-wrap list-unstyled my-2 ">
                                    <?php
                                    if (!empty($int_city)) {
                                        if ($int_city[0]->g_location_name == '') {
                                            if ($internship->internship_type != 1) { ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                            <?php }
                                        }
                                        foreach ($int_city as $city) {
                                            if ($city->g_location_name != '') {  ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13 "><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i><span class="location_text"><?php echo $city->g_location_name; ?></span></span></li>
                                        <?php  }
                                        }
                                    } else { ?>
                                        <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                    <?php    } ?>
                                </ul>
                                <ul class="d-flex flex-wrap ps-0 list-unstyled mb-0">
                                    <li class="me-4 mb-md-3 mb-2">
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
                                    <li class="mb-md-3 mb-2">
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
                                </ul>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="startFrom text-dark f-14 ps-2 pe-3 py-1">
                                        <img src="<?= base_url(); ?>/public/assets/img/ico_start.svg" alt="Start Date" class="img-fluid me-2" width="13">Starting from : <?php if (isset($internship->internship_startdate)) {
                                                                                                                                                                                echo date("d M Y", strtotime($internship->internship_startdate));
                                                                                                                                                                            } ?>
                                    </div>
                                    <?php
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
                                    <div class="d-flex justify-content-start align-items-start">
                                        <a onclick="func_can_bookmark('<?php echo $internship->internship_id; ?>','<?php echo $emp_details->userid; ?>','<?php echo $profile; ?>')" class="bookmarkIco px-2 py-1 fs-4"><span class="bookmark_icon_<?php echo $internship->internship_id; ?>"><i class="<?php if (isset($bookmark_details)) { ?>fa fa-bookmark<?php } else { ?>fa fa-bookmark-o<?php } ?>" <?php if (isset($bookmark_details)) { ?> style="color:#19A540" <?php } ?>></i></a>
                                        <div class="d-flex justify-content-center align-items-center share_parent resShare">
                                            <a class="share-btn-overall" id="add_hide_class" onclick="social_meadia(<?php echo $i; ?>)">
                                                <img src="<?= base_url(); ?>/public/assets/img/share_ico.svg" alt="Share" width="16">
                                            </a>
                                            <div class="social_hide_area_1 " id="socialmedia_hide_show_<?php echo $i; ?>">
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

                                                if (isset($internship->profile) && $internship->profile != '0') {
                                                    // $int_name = "intern";

                                                    $int_name = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                } else {
                                                    $int_name = $internship->other_profile;
                                                }
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
                            </div>
                        </div>

                    <?php
                         } 
                        $sum++;
                                }
                                            
                    }
                    $list_count--;
                        $i++;
                    }
                   // print_r($all_internship_id);exit;
                    ?>
                    


                </div>
            </div>
        </section>
        <?php } ?>
    <?php  } ?>

    <!-- ***************************************Current Location***************************************************** -->
    <?php if (!empty($internship_list_current_location) && isset($internship_list_current_location)) { ?>
        <section class="py-3">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between align-items-center my-4 pt-3">
                    <h4 class="text-dark mb-0">Internships Based on <span class="candi_dash_locaion_current">Current Location</span></h4>
                </div>

                <div class="row d-flex flex-wrap gap-3 px-3 px-md-2">
                    <div class="card col-12 col-md-6 mb-3 w-30 location_card_current p-0 align-items-center justify-content-center">
                        <img src="<?= base_url(); ?>/public/assets/img/location_based_current.svg" alt="location_based" class="img-fluid">
                    </div>
                    <?php

                    $i = 30;
                    $list_count = count($internship_list_current_location);
                    foreach (array_slice($internship_list_current_location, 0, 2) as $internship) {
                        $where_emp = array('status' => '1', 'userid' => $internship->company_id);
                        $emp_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                    ?>

                        <div class="card col-12 col-md-6 mb-3 w-30 candi_dash_location_card_current" style="z-index: <?php echo $list_count; ?>">
                            <div class="p-3 pb-0">
                                <div class="d-flex flex-sm-row flex-row-reverse">
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
                                        <div class="d-flex flex-column">
                                            <div>
                                                <a href="<?php echo base_url('/internship-details/' . $internship->internship_id); ?>" class="">
                                                    <h3 class="fw-semibold text-blue me-sm-0 pe-sm-0 me-5 pe-2 fs-5 profile_text" id=""><?php if (isset($internship->profile) && $internship->profile != '0') {
                                                                                                                                            echo $profile = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                                                                                                        } else {
                                                                                                                                            echo $profile = $internship->other_profile;
                                                                                                                                        } ?> </h3>
                                                </a>
                                                <div class="comLogo des-sm-none logoResPos  d-flex justify-content-center align-items-center rounded p-1 me-sm-3 ms-sm-0 ms-2">
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
                                            </div>
                                            <?php
                                            $where_city = array('status' => '1', 'internship_id' => $internship->internship_id);
                                            $int_city = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);
                                            ?>
                                            <h6 class="text-blue fw-medium mb-0">

                                                <?php if (isset($emp_details->profile_company_name) && !empty($emp_details->profile_company_name)) { ?>
                                                    <a href="<?= base_url(); ?>/employer-details/<?php echo $internship->company_id; ?>" class="text-dark comResWidth fw-normal d-inline-block mb-1"><img src="<?= base_url(); ?>/public/assets/img/icon_company1.svg" alt="Location" class="me-2 mb-1 " width="14"><span class="company_text f-13"><?php echo $emp_details->profile_company_name; ?></span></a>
                                                <?php } ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <ul class="d-flex flex-wrap list-unstyled my-2 ">
                                    <?php
                                    if (!empty($int_city)) {
                                        if ($int_city[0]->g_location_name == '') {
                                            if ($internship->internship_type != 1) { ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                            <?php }
                                        }
                                        foreach ($int_city as $city) {
                                            if ($city->g_location_name != '') {  ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13 "><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i><span class="location_text"><?php echo $city->g_location_name; ?></span></span></li>
                                        <?php  }
                                        }
                                    } else { ?>
                                        <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                    <?php    } ?>
                                </ul>
                                <ul class="d-flex flex-wrap ps-0 list-unstyled mb-0">
                                    <li class="me-4 mb-md-3 mb-2">
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
                                    <li class="mb-md-3 mb-2">
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
                                </ul>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="startFrom text-dark f-14 ps-2 pe-3 py-1">
                                        <img src="<?= base_url(); ?>/public/assets/img/ico_start.svg" alt="Start Date" class="img-fluid me-2" width="13">Starting from : <?php if (isset($internship->internship_startdate)) {
                                                                                                                                                                                echo date("d M Y", strtotime($internship->internship_startdate));
                                                                                                                                                                            } ?>
                                    </div>
                                    <?php
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
                                    <div class="d-flex justify-content-start align-items-start">
                                        <a onclick="func_can_bookmark('<?php echo $internship->internship_id; ?>','<?php echo $emp_details->userid; ?>','<?php echo $profile; ?>')" class="bookmarkIco px-2 py-1 fs-4"><span class="bookmark_icon_<?php echo $internship->internship_id; ?>"><i class="<?php if (isset($bookmark_details)) { ?>fa fa-bookmark<?php } else { ?>fa fa-bookmark-o<?php } ?>" <?php if (isset($bookmark_details)) { ?> style="color:#19A540" <?php } ?>></i></a>
                                        <div class="d-flex justify-content-center align-items-center share_parent resShare">
                                            <a class="share-btn-overall" id="add_hide_class" onclick="social_meadia(<?php echo $i; ?>)">
                                                <img src="<?= base_url(); ?>/public/assets/img/share_ico.svg" alt="Share" width="16">
                                            </a>
                                            <div class="social_hide_area_1 " id="socialmedia_hide_show_<?php echo $i; ?>">
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

                                                if (isset($internship->profile) && $internship->profile != '0') {
                                                    // $int_name = "intern";

                                                    $int_name = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                } else {
                                                    $int_name = $internship->other_profile;
                                                }
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
                            </div>
                        </div>

                    <?php $list_count--;
                        $i++;
                    }
                    ?>

                    <?php if (!empty($internship_list_current_location)) {
                        $i = 40;
                        $list_count = count($internship_list_current_location);
                        if ($list_count > 2) { ?>

                            <?php

                            foreach (array_slice($internship_list_current_location, 2, 5) as $internship) {
                                $where_emp = array('status' => '1', 'userid' => $internship->company_id);
                                $emp_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                            ?>



                                <div class="card col-12 col-md-6 mb-3 w-30 candi_dash_location_card" style="z-index: <?php echo $list_count; ?>">
                                    <div class="p-3 pb-0">
                                        <div class="d-flex flex-sm-row flex-row-reverse">
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
                                                <div class="d-flex flex-column">
                                                    <div>
                                                        <a href="<?php echo base_url('/internship-details/' . $internship->internship_id); ?>" class="">
                                                            <h3 class="fw-semibold text-blue me-sm-0 pe-sm-0 me-5 pe-2 fs-5 profile_text" id=""><?php if (isset($internship->profile) && $internship->profile != '0') {
                                                                                                                                                    echo $profile = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                                                                                                                } else {
                                                                                                                                                    echo $profile = $internship->other_profile;
                                                                                                                                                } ?> </h3>
                                                        </a>
                                                        <div class="comLogo des-sm-none logoResPos  d-flex justify-content-center align-items-center rounded p-1 me-sm-3 ms-sm-0 ms-2">
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
                                                    </div>
                                                    <?php
                                                    $where_city = array('status' => '1', 'internship_id' => $internship->internship_id);
                                                    $int_city = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);
                                                    ?>
                                                    <h6 class="text-blue fw-medium mb-0">

                                                        <?php if (isset($emp_details->profile_company_name) && !empty($emp_details->profile_company_name)) { ?>
                                                            <a href="<?= base_url(); ?>/employer-details/<?php echo $internship->company_id; ?>" class="text-dark comResWidth fw-normal d-inline-block mb-1"><img src="<?= base_url(); ?>/public/assets/img/icon_company1.svg" alt="Location" class="me-2 mb-1 " width="14"><span class="company_text f-13"><?php echo $emp_details->profile_company_name; ?></span></a>
                                                        <?php } ?>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="d-flex flex-wrap list-unstyled my-2 ">
                                            <?php
                                            if (!empty($int_city)) {
                                                if ($int_city[0]->g_location_name == '') {
                                                    if ($internship->internship_type != 1) { ?>
                                                        <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                                    <?php }
                                                }
                                                foreach ($int_city as $city) {
                                                    if ($city->g_location_name != '') {  ?>
                                                        <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13 "><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i><span class="location_text"><?php echo $city->g_location_name; ?></span></span></li>
                                                <?php  }
                                                }
                                            } else { ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                            <?php    } ?>
                                        </ul>
                                        <ul class="d-flex flex-wrap ps-0 list-unstyled mb-0">
                                            <li class="me-4 mb-md-3 mb-2">
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
                                            <li class="mb-md-3 mb-2">
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
                                        </ul>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="startFrom text-dark f-14 ps-2 pe-3 py-1">
                                                <img src="<?= base_url(); ?>/public/assets/img/ico_start.svg" alt="Start Date" class="img-fluid me-2" width="13">Starting from : <?php if (isset($internship->internship_startdate)) {
                                                                                                                                                                                        echo date("d M Y", strtotime($internship->internship_startdate));
                                                                                                                                                                                    } ?>
                                            </div>
                                            <?php
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
                                            <div class="d-flex justify-content-start align-items-start">
                                                <a onclick="func_can_bookmark('<?php echo $internship->internship_id; ?>','<?php echo $emp_details->userid; ?>','<?php echo $profile; ?>')" class="bookmarkIco px-2 py-1 fs-4"><span class="bookmark_icon_<?php echo $internship->internship_id; ?>"><i class="<?php if (isset($bookmark_details)) { ?>fa fa-bookmark<?php } else { ?>fa fa-bookmark-o<?php } ?>" <?php if (isset($bookmark_details)) { ?> style="color:#19A540" <?php } ?>></i></a>
                                                <div class="d-flex justify-content-center align-items-center share_parent resShare">
                                                    <a class="share-btn-overall" id="add_hide_class" onclick="social_meadia(<?php echo $i; ?>)">
                                                        <img src="<?= base_url(); ?>/public/assets/img/share_ico.svg" alt="Share" width="16">
                                                    </a>
                                                    <div class="social_hide_area_1 " id="socialmedia_hide_show_<?php echo $i; ?>">
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

                                                        if (isset($internship->profile) && $internship->profile != '0') {
                                                            // $int_name = "intern";

                                                            $int_name = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                        } else {
                                                            $int_name = $internship->other_profile;
                                                        }
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
                                    </div>
                                </div>
                            <?php $list_count--;
                                $i++;
                            }
                            ?>
                    <?php }
                    } ?>


                </div>
        </section>

    <?php } ?>


    <!-------- New Section design for Internships Based on .... Ends here ----->
<!--  Preffered Location  -->
<?php if (!empty($internship_list_preferred_location)) { ?>
        <section class="py-3">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between align-items-center my-4 pt-3">
                    <h4 class="text-dark mb-0">Internships Based on <span class="candi_dash_locaion">Preferred Location</span></h4>
                </div>

                <div class="row d-flex flex-wrap gap-3 px-3 px-md-2">
                    <div class="card col-12 col-md-6 mb-3 w-30 location_card p-0 align-items-center justify-content-center">
                        <img src="<?= base_url(); ?>/public/assets/img/location_based.svg" alt="location_based" class="img-fluid">
                    </div>
                    <?php

                    $i = 50;
                    $list_count = count($internship_list_preferred_location);
                    foreach (array_slice($internship_list_preferred_location, 0, 2) as $internship) {
                        $where_emp = array('status' => '1', 'userid' => $internship->company_id);
                        $emp_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                    ?>

                        <div class="card col-12 col-md-6 mb-3 w-30 candi_dash_location_card <?php if($internship->premium_status==1) { ?> card_premium_ico <?php } ?>" style="z-index: <?php echo $list_count; ?>">
                            <div class="p-3 pb-0">
                                <div class="d-flex flex-sm-row flex-row-reverse">
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
                                        <div class="d-flex flex-column">
                                            <div>
                                                <a href="<?php echo base_url('/internship-details/' . $internship->internship_id); ?>" class="">
                                                    <h3 class="fw-semibold text-blue me-sm-0 pe-sm-0 me-5 pe-2 fs-5 profile_text" id=""><?php if (isset($internship->profile) && $internship->profile != '0') {
                                                                                                                                            echo $profile = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                                                                                                        } else {
                                                                                                                                            echo $profile = $internship->other_profile;
                                                                                                                                        } ?> </h3>
                                                </a>
                                                <div class="comLogo des-sm-none logoResPos  d-flex justify-content-center align-items-center rounded p-1 me-sm-3 ms-sm-0 ms-2">
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
                                            </div>
                                            <?php
                                            $where_city = array('status' => '1', 'internship_id' => $internship->internship_id);
                                            $int_city = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);
                                            ?>
                                            <h6 class="text-blue fw-medium mb-0">

                                                <?php if (isset($emp_details->profile_company_name) && !empty($emp_details->profile_company_name)) { ?>
                                                    <a href="<?= base_url(); ?>/employer-details/<?php echo $internship->company_id; ?>" class="text-dark comResWidth fw-normal d-inline-block mb-1"><img src="<?= base_url(); ?>/public/assets/img/icon_company1.svg" alt="Location" class="me-2 mb-1 " width="14"><span class="company_text f-13"><?php echo $emp_details->profile_company_name; ?></span></a>
                                                <?php } ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <ul class="d-flex flex-wrap list-unstyled my-2 ">
                                    <?php
                                    if (!empty($int_city)) {
                                        if ($int_city[0]->g_location_name == '') {
                                            if ($internship->internship_type != 1) { ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                            <?php }
                                        }
                                        foreach ($int_city as $city) {
                                            if ($city->g_location_name != '') {  ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13 "><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i><span class="location_text"><?php echo $city->g_location_name; ?></span></span></li>
                                        <?php  }
                                        }
                                    } else { ?>
                                        <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                    <?php    } ?>
                                </ul>
                                <ul class="d-flex flex-wrap ps-0 list-unstyled mb-0">
                                    <li class="me-4 mb-md-3 mb-2">
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
                                    <li class="mb-md-3 mb-2">
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
                                </ul>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="startFrom text-dark f-14 ps-2 pe-3 py-1">
                                        <img src="<?= base_url(); ?>/public/assets/img/ico_start.svg" alt="Start Date" class="img-fluid me-2" width="13">Starting from : <?php if (isset($internship->internship_startdate)) {
                                                                                                                                                                                echo date("d M Y", strtotime($internship->internship_startdate));
                                                                                                                                                                            } ?>
                                    </div>
                                    <?php
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
                                    <div class="d-flex justify-content-start align-items-start">
                                        <a onclick="func_can_bookmark('<?php echo $internship->internship_id; ?>','<?php echo $emp_details->userid; ?>','<?php echo $profile; ?>')" class="bookmarkIco px-2 py-1 fs-4"><span class="bookmark_icon_<?php echo $internship->internship_id; ?>"><i class="<?php if (isset($bookmark_details)) { ?>fa fa-bookmark<?php } else { ?>fa fa-bookmark-o<?php } ?>" <?php if (isset($bookmark_details)) { ?> style="color:#19A540" <?php } ?>></i></a>
                                        <div class="d-flex justify-content-center align-items-center share_parent resShare">
                                            <a class="share-btn-overall" id="add_hide_class" onclick="social_meadia(<?php echo $i; ?>)">
                                                <img src="<?= base_url(); ?>/public/assets/img/share_ico.svg" alt="Share" width="16">
                                            </a>
                                            <div class="social_hide_area_1 " id="socialmedia_hide_show_<?php echo $i; ?>">
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

                                                if (isset($internship->profile) && $internship->profile != '0') {
                                                    // $int_name = "intern";

                                                    $int_name = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                } else {
                                                    $int_name = $internship->other_profile;
                                                }
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
                            </div>
                        </div>

                    <?php $list_count--;
                        $i++;
                    }
                    ?>

                    <?php if (!empty($internship_list_preferred_location)) {
                        $i = 60;
                        $list_count = count($internship_list_preferred_location);
                        if ($list_count > 2) { ?>

                            <?php

                            foreach (array_slice($internship_list_preferred_location, 2, 5) as $internship) {
                                $where_emp = array('status' => '1', 'userid' => $internship->company_id);
                                $emp_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                            ?>



                                <div class="card col-12 col-md-6 mb-3 w-30 candi_dash_location_card" style="z-index: <?php echo $list_count; ?>">
                                    <div class="p-3 pb-0">
                                        <div class="d-flex flex-sm-row flex-row-reverse">
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
                                                <div class="d-flex flex-column">
                                                    <div>
                                                        <a href="<?php echo base_url('/internship-details/' . $internship->internship_id); ?>" class="">
                                                            <h3 class="fw-semibold text-blue me-sm-0 pe-sm-0 me-5 pe-2 fs-5 profile_text" id=""><?php if (isset($internship->profile) && $internship->profile != '0') {
                                                                                                                                                    echo $profile = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                                                                                                                } else {
                                                                                                                                                    echo $profile = $internship->other_profile;
                                                                                                                                                } ?> </h3>
                                                        </a>
                                                        <div class="comLogo des-sm-none logoResPos  d-flex justify-content-center align-items-center rounded p-1 me-sm-3 ms-sm-0 ms-2">
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
                                                    </div>
                                                    <?php
                                                    $where_city = array('status' => '1', 'internship_id' => $internship->internship_id);
                                                    $int_city = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);
                                                    ?>
                                                    <h6 class="text-blue fw-medium mb-0">

                                                        <?php if (isset($emp_details->profile_company_name) && !empty($emp_details->profile_company_name)) { ?>
                                                            <a href="<?= base_url(); ?>/employer-details/<?php echo $internship->company_id; ?>" class="text-dark comResWidth fw-normal d-inline-block mb-1"><img src="<?= base_url(); ?>/public/assets/img/icon_company1.svg" alt="Location" class="me-2 mb-1 " width="14"><span class="company_text f-13"><?php echo $emp_details->profile_company_name; ?></span></a>
                                                        <?php } ?>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="d-flex flex-wrap list-unstyled my-2 ">
                                            <?php
                                            if (!empty($int_city)) {
                                                if ($int_city[0]->g_location_name == '') {
                                                    if ($internship->internship_type != 1) { ?>
                                                        <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                                    <?php }
                                                }
                                                foreach ($int_city as $city) {
                                                    if ($city->g_location_name != '') {  ?>
                                                        <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13 "><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i><span class="location_text"><?php echo $city->g_location_name; ?></span></span></li>
                                                <?php  }
                                                }
                                            } else { ?>
                                                <li class="mb-1"><span class="badge bg-gray fw-normal text-dark f-13"><i class="fa fa-map-marker text-gray1 f-13 me-2" aria-hidden="true"></i>Work From Home</span></li>
                                            <?php    } ?>
                                        </ul>
                                        <ul class="d-flex flex-wrap ps-0 list-unstyled mb-0">
                                            <li class="me-4 mb-md-3 mb-2">
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
                                            <li class="mb-md-3 mb-2">
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
                                        </ul>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="startFrom text-dark f-14 ps-2 pe-3 py-1">
                                                <img src="<?= base_url(); ?>/public/assets/img/ico_start.svg" alt="Start Date" class="img-fluid me-2" width="13">Starting from : <?php if (isset($internship->internship_startdate)) {
                                                                                                                                                                                        echo date("d M Y", strtotime($internship->internship_startdate));
                                                                                                                                                                                    } ?>
                                            </div>
                                            <?php
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
                                            <div class="d-flex justify-content-start align-items-start">
                                                <a onclick="func_can_bookmark('<?php echo $internship->internship_id; ?>','<?php echo $emp_details->userid; ?>','<?php echo $profile; ?>')" class="bookmarkIco px-2 py-1 fs-4"><span class="bookmark_icon_<?php echo $internship->internship_id; ?>"><i class="<?php if (isset($bookmark_details)) { ?>fa fa-bookmark<?php } else { ?>fa fa-bookmark-o<?php } ?>" <?php if (isset($bookmark_details)) { ?> style="color:#19A540" <?php } ?>></i></a>
                                                <div class="d-flex justify-content-center align-items-center share_parent resShare">
                                                    <a class="share-btn-overall" id="add_hide_class" onclick="social_meadia(<?php echo $i; ?>)">
                                                        <img src="<?= base_url(); ?>/public/assets/img/share_ico.svg" alt="Share" width="16">
                                                    </a>
                                                    <div class="social_hide_area_1 " id="socialmedia_hide_show_<?php echo $i; ?>">
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

                                                        if (isset($internship->profile) && $internship->profile != '0') {
                                                            // $int_name = "intern";

                                                            $int_name = $Candidate_model->get_master_name('master_profile', $internship->profile, 'profile');
                                                        } else {
                                                            $int_name = $internship->other_profile;
                                                        }
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
                                    </div>
                                </div>
                            <?php $list_count--;
                                $i++;
                            }
                            ?>
                    <?php }
                    } ?>


                </div>
        </section>

    <?php } ?>
    <!-- end -->
    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>

    <script>
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
        $(document).ready(function() {
            $(".social_hide_area_1").hide();
        });

        $(document).ready(function() {
            $("#gauge3").gauge(75, {
                type: "halfcircle"
            });

        });

        function social_meadia(val) {
            $("#socialmedia_hide_show_" + val).toggle();

        }

        function func_log_sheet_here() {

            swal({
                title: "Alert",
                text: "No Active Internship",
                type: "info",
                showCancelButton: false,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Ok",
                // cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    location.reload();

                } else {
                    location.reload();
                }
            })
            // swal("Please Complete Your Profile", "You clicked the button!", "success");
        }

        function func_can_bookmark(internship_id, emp_user_id, profile) {

            var val = $("#bookmark_type_" + internship_id).val();
            // alert(val);
            var csrf_val = $(".csrf").val();
            var csrf = "&csrf_test_name=" + csrf_val;
            $.ajax({
                url: "<?php echo base_url('can_intership_bookmark'); ?>",
                method: "POST",
                data: "type=" + val + "&internship_id=" + internship_id + "&emp_user_id=" + emp_user_id + "&profile=" + profile + "&redirect=2" + csrf,
                success: function(response) {
                    // alert(response);
                    var splitted_data = response.split('^');
                    $(".csrf").val(splitted_data[0].trim());
                    if (splitted_data[1] == '1') {
                        location.reload();
                        //    var val = $("#bookmark_type_"+internship_id).val('2');

                        //    $(".bookmark_icon_"+internship_id).html('<i class="fa fa-bookmark" style="color:#19A540"></i>');
                        //    $("#session_alert").css("display","block");
                        //    $("#session_alert").html("<div class='alert alert-success flash-alert overflow-anywhere alertOn'><i class='fa fa-check me-2' aria-hidden='true'></i>Bookmark Added</div>");
                        //        setTimeout(function() {
                        //            $("#session_alert").css("display","none");
                        //        }, 2000);


                    }
                    if (splitted_data[1] == '2') {
                        location.reload();
                        //    var val = $("#bookmark_type_"+internship_id).val('1');
                        //    $(".bookmark_icon_"+internship_id).html('<i class="fa fa-bookmark-o" style=""></i>');
                        //    $("#session_alert").css("display","block");
                        //    $("#session_alert").html("<div class='alert alert-danger flash-alert overflow-anywhere alertOn'><i class='fa fa-check me-2' aria-hidden='true'></i>Bookmark Removed</div>");
                        //        setTimeout(function() {
                        //            $("#session_alert").css("display","none");
                        //        }, 2000);

                    }
                },

            });
            // window.location.href = '<?= base_url(); ?>/can_intership_bookmark/' + val + '/' + intership_id + '/' + emp_user_id + '/' + profile + '/3';

        }
    </script>
</body>

</html>