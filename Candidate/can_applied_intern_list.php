<!DOCTYPE html>
<html>
<style>
    .email {
        width: 260px;
        margin-left: 20%;
        display: block;
        font-size: 16px;
        padding: 10px 0;
        border: none;
        border-bottom: solid 1px #383838;
        color: #383838;
        background-repeat: no-repeat;
        background-size: 260px 100%;
        background-position: -260px 0;
        transition: background-position 0.2s cubic-bezier(0.64, 0.09, 0.08, 1);
    }

    .email:focus,
    .email:valid {
        background-position: 0 0;
        outline: none;
    }

    .autosuffix {
        margin-left: 20%;
        margin-top: 36px;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.16), 0 2px 8px 0 rgba(0, 0, 0, 0.12);
        display: inline-block;
        border-radius: 3px;
        position: absolute;
        background: #fff;
        width: 100%;
        z-index: 1;
    }

    .autosuffix li {
        padding: 10px;
        padding-right: 20px;
        cursor: pointer;
        color: #383838;
        list-style: none;
    }

    .autosuffix li:first-of-type {
        border-radius: 3px 3px 0 0;
    }

    .autosuffix li:last-of-type {
        border-radius: 0 0 3px 3px;
    }

    .autosuffix li:hover {
        background: #ebebeb;
    }
</style>

<?php
$session = session();
$userid    =    $session->get('userid');
$active_sort_my_application    =    $session->get('active_sort_my_application');
$application_offers_received    =    $session->get('application_offers_received');
// print_r($_SESSION);
// print_r($applied_internship_list);
use App\Models\Candidate_model;

$session = session();
$login = $session->get('isLoggedIn');
$Candidate_model = new Candidate_model();
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
$where_can_pro = array('status' => '1', 'userid' => $userid);
$can_profile_details = $Candidate_model->fetch_table_row('can_personal_details', $where_can_pro);

?>

<body class="stickyFoot">

    <?php require_once(APPPATH . "Views/Common/header.php");
    require_once(APPPATH . "Views/Common/error_page.php");

    ?>
    <!----- Form ------>
    <section class="container filterable my-4">
        <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
            <?php if ($application_offers_received == '1') { ?>
                <h2 class="page_title mb-sm-0 mb-3">Offers Received</h2>
            <?php  } else { ?>
                <h2 class="page_title mb-sm-0 mb-3">My Applications</h2>
                <!-- <a href="#" class="text-blue backBtn me-3" onclick="previous()"><i class="fa fa-long-arrow-left me-1" aria-hidden="true"></i> Back</a> -->
            <?php } ?>
        </div>


        <div class="card p-4">
            <?php if ($application_offers_received != '1') { ?>
                <div class="d-flex justify-content-between flex-wrap">
                    <ul class="nav nav-tabs hrInternList canAppList mb-4" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="<?= base_url(); ?>/my-applications" class="nav-link active" id="myapp-tab"><img src='<?php echo base_url("/public/assets/img/my_application.svg"); ?>' width='15' class='me-2 mb-1'>My Applications
                                <span class="badge bg-primary ms-2 p-1 fw-normal"><?php if (isset($applied_internship_list) && !empty($applied_internship_list)) {
                                                                                        echo count($applied_internship_list);
                                                                                    } else {
                                                                                        echo '0';
                                                                                    } ?></span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="<?= base_url(); ?>/direct-corporate-offers" class="nav-link" id="intoff-tab">
                                <img src='<?php echo base_url("/public/assets/img/internship_offers.svg"); ?>' width='18' class='me-2 mb-1'>Direct Corporate Offers
                                <?php if ($personal_details->can_offer_status == 1) { ?>
                                    <span class="newDot"></span>
                                <?php } ?>
                                <span class="badge bg-gray1 ms-2 p-1 fw-normal"><?php if (isset($offer_count_applied_internship_list) && !empty($offer_count_applied_internship_list)) {
                                                                                    echo count($offer_count_applied_internship_list);
                                                                                } else {
                                                                                    echo '0';
                                                                                } ?></span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="<?= base_url(); ?>/my-internships" class="nav-link" id="myint-tab"><img src='<?php echo base_url("/public/assets/img/icon_intern1.svg"); ?>' width='17' class='me-2 mb-1'>My Internships
                                <span class="badge bg-gray1 ms-2 p-1 fw-normal"><?php if (isset($my_count_applied_internship_list) && !empty($my_count_applied_internship_list)) {
                                                                                    echo count($my_count_applied_internship_list);
                                                                                } else {
                                                                                    echo '0';
                                                                                } ?></span>
                            </a>
                        </li>
                    </ul>
                    <div class="col-md-4 col-lg-2 col-12 form-group mt-3">
                        <!-- <label class="form-label"> Filter By : </label> -->
                        <select name="internship_duration" id="internship_duration" onchange="my_application_list(this.value)" class="js-states selectSearch filterby form-control align-self-end">
                            <option value="1" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '1') {
                                                        echo 'selected';
                                                    }
                                                } ?>>All</option>
                            <option value="2" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '2') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Offer accepted</option>
                            <option value="3" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '3') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Offer declined</option>
                            <option value="4" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '4') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Dropped</option>
                            <option value="5" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '5') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Completed</option>
                            <option value="6" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '6') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Under review</option>
                            <option value="7" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '7') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Hired</option>
                            <option value="8" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '8') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Not Qualified</option>
                            <option value="9" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '9') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Ongoing</option>
                            <option value="10" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '10') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Offer expired</option>
                            <option value="11" <?php if (isset($active_sort_my_application)) {
                                                    if ($active_sort_my_application == '11') {
                                                        echo 'selected';
                                                    }
                                                } ?>>Under Consideration</option>

                        </select>
                    </div>
                </div>
            <?php } ?>
            <div class="pgContent mt-2">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="myapp" role="tabpanel" aria-labelledby="myapp-tab">
                        <div class="table-responsive">
                            <table class="table" id="example">
                                <thead>
                                    <tr class="filters">
                                        <th scope="col">S.No</th>
                                        <th scope="col">Company</th>
                                        <th scope="col">Profile</th>
                                        <th scope="col">Applied On</th>
                                        <th scope="col" class="text-center">Stipend</th>
                                        <!-- <th scope="col" class="text-center">Number Of Applicants</th> -->
                                        <th scope="col" class="text-center">Status</th>
                                        <!--       <th scope="col" class="text-center">Review</th> -->
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($applied_internship_list) && !empty($applied_internship_list)) {
                                        $i = 1;
                                        foreach ($applied_internship_list as $internship_list) {

                                            $where = array('status' => '1', 'internship_id' => $internship_list->internship_id);
                                            $internship_details = $Candidate_model->fetch_table_row('employer_post_internship', $where);
                                            $where_emp = array('status' => '1', 'userid' => $internship_details->company_id);
                                            $employer_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                                            $where_count = array('status' => '1', 'internship_id' => $internship_details->internship_id);
                                            $applicant_count = $Candidate_model->data_count_fetch('can_applied_internship', $where_count);
                                            $userid          = $session->get('userid');
                                            $where4 = array('status' => '1', 'userid' => $userid);
                                            $order_by = array('ordercolumn' => 'education_end_year', 'ordertype' => 'desc');
                                            $education_details = $Candidate_model->fetch_table_data_last_college('can_education_details', $where4);
                                            if ($education_details[0]->education_college_name != 0) {
                                                $where1 = array('id' => $education_details[0]->education_college_name);
                                                $education_college_name = $Candidate_model->get_master_commen_for_all('master_college', $where1, 'college_name');
                                            } else {
                                                $education_college_name = $education_details[0]->education_college_name_other;
                                            }

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
                                            //$expire_date = date("Y-m-d", strtotime("+" . $duration_count_1 . " " . $duration_type . " ", strtotime(date($internship_details->internship_startdate))));
                                            $expire_date = $internship_details->internship_startdate;

                                            //  exit;
                                            $current_date = date("Y-m-d");
                                            if ($active_sort_my_application == 10) {
                                                if ($current_date >= $expire_date) {
                                                    // echo $expire_date;

                                    ?>
                                                    <tr>
                                                        <td scope="row"><?php echo $i; ?></td>
                                                        <td class="overflow-anywhere"><a href="<?= base_url(); ?>/employer-details/<?php echo $internship_details->company_id; ?>" class="text-blue1"><?php if (isset($employer_details->profile_company_name)) {
                                                                                                                                                                                                            echo $employer_details->profile_company_name;
                                                                                                                                                                                                        } ?></a></td>
                                                        <td><a href="<?php echo base_url('internship-details'); ?>/<?php echo $internship_list->internship_id; ?>" class="text-blue1"><?php if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                                                                                                                                                                            echo $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                                                                                                                                                                        } else {
                                                                                                                                                                                            echo $internship_details->other_profile;
                                                                                                                                                                                        } ?></a></td>
                                                        <td><?php echo $newDate = date("d-M-Y", strtotime($internship_list->created_at)); ?></td>
                                                        <!-- <td class="text-center"><?php if (isset($applicant_count)) {
                                                                                            echo $applicant_count;
                                                                                        } ?></td> -->
                                                        <td class="text-center"><?php if ($internship_details->stipend != '1') {
                                                                                    if (isset($internship_details->amount_from) && $internship_details->amount_from != '0') {
                                                                                        echo '₹ ' . $internship_details->amount_from;
                                                                                    } ?> <?php if (isset($internship_details->amount_to) && $internship_details->amount_to != '0') {
                                                                                        echo '- ' . $internship_details->amount_to;
                                                                                    }
                                                                                } else {
                                                                                    echo "-";
                                                                                } ?></td>
                                                        <td class="text-center">
                                                            <?php
                                                            // print_r($internship_list);
                                                            // echo $internship_details->internship_startdate;


                                                            if (!empty($internship_list)) {

                                                                //if hired 
                                                                if ($internship_list->hiring_status == 1) {
                                                                    if ($internship_details->internship_startdate <= $current_date) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                                            <?php
                                                                            echo "<span class='badge status-ongoing fw-normal'>Ongoing</span>";
                                                                            ?></span><?php
                                                                                } else { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the employers offer">
                                                                            <?php
                                                                                    echo "<span class='badge badge-completed fw-normal'>Offer accepted</span>";
                                                                            ?></span><?php
                                                                                }
                                                                            } elseif ($internship_list->hiring_status == 2) { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have rejected the employers offer">
                                                                        <?php
                                                                                echo "<span class='badge badge-red fw-normal'>Offer declined</span>";
                                                                        ?></span><?php
                                                                            } elseif ($internship_list->hiring_status == 4) {
                                                                                if ($internship_details->internship_startdate <= $current_date) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                                            <?php
                                                                                    echo "<span class='badge badge-red fw-normal'>Offer declined</span>";
                                                                            ?></span><?php
                                                                                } else { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="When you opt for under consideration, you will have time till internship start date to accept the offer. If you fail to accept the offer before that, then status is set as 'offer declined'">
                                                                            <?php
                                                                                    echo "<span class='badge status-consider fw-normal'>Under Consideration</span>";
                                                                            ?></span><?php
                                                                                }
                                                                            } elseif ($internship_list->complete_status == 1) {
                                                                                if ($internship_list->complete_type != 1) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not completed the internship">
                                                                            <?php
                                                                                    echo "<span class='badge status-dropped fw-normal'>Dropped</span>";
                                                                            ?></span><?php
                                                                                } else { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have completed the internship">
                                                                            <?php
                                                                                    echo "<span class='badge badge-completed fw-normal'>Completed</span>";
                                                                            ?></span><?php
                                                                                }
                                                                            } else {
                                                                                if ($internship_list->application_status == 0) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is submitted to the employer successfully">
                                                                            <?php
                                                                                    echo "<span class='badge status-review  fw-normal me-2'>Under review</span>";
                                                                            ?></span><?php
                                                                                } elseif ($internship_list->application_status == 1) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is submitted to the employer successfully">
                                                                            <?php
                                                                                    echo "<span class='badge status-review  fw-normal me-2'>Under review</span>";
                                                                            ?></span><?php
                                                                                } elseif ($internship_list->application_status == 2) {
                                                                                    if ($current_date >= $expire_date) { ?>
                                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not taken any action on the offer sent by the employer until internship start date">
                                                                                <?php
                                                                                        echo "<span class='badge status-expired fw-normal'>Offer expired</span>";
                                                                                ?></span><?php
                                                                                    } else { ?>
                                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You are hired by the employer">
                                                                                <?php
                                                                                        echo "<span class='badge badge-completed fw-normal mb-1 me-2'>Hired</span>";
                                                                                ?></span><?php
                                                                                    }
                                                                                } elseif ($internship_list->application_status == 3) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is rejected by the employer">
                                                                            <?php
                                                                                    echo "<span class='badge status-notqualified fw-normal'>Not Qualified</span>";
                                                                            ?></span><?php
                                                                                }
                                                                            }
                                                                        } else {
                                                                                    ?>
                                                                <span class="badge badge-gray fw-normal me-2"><img src='<?php echo base_url("/public/assets/img/icon_company1.svg"); ?>' width='12' class='me-1'>
                                                                    Not Applied</span>
                                                            <?php
                                                                        }


                                                                        //    if (!empty($internship_list)) 
                                                                        //          {

                                                                        //    }

                                                            ?>
                                                            <!-- <span class="badge badge-ongoing fw-normal"><?php if ($internship_list->application_status == 0) {
                                                                                                                    echo "Under Review";
                                                                                                                } elseif ($internship_list->application_status == 2) {
                                                                                                                    echo "Hired";
                                                                                                                } elseif ($internship_list->application_status == 3) {
                                                                                                                    echo "Not Qualified";
                                                                                                                } ?></span> -->
                                                            <!-- <i class="fa fa-question-circle text-muted ms-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="That means it is taken by the employer"></i> -->
                                                        </td>
                                                        <!--  <td class="text-center">
                                             <a href="<?php //echo base_url('can-apply-for-internship'); 
                                                        ?>/<?php //echo $internship_list->internship_id; 
                                                                ?>"><img src="<?= base_url(); ?>/public/assets/img/icon_review.svg" alt="View" width="15"></a>
                                                 </td> -->
                                                        <?php

                                                        $where = array('status' => '1', 'internship_id' => $internship_list->internship_id, 'user_id' => $userid);
                                                        $can_log_details = $Candidate_model->fetch_table_data('can_log_sheet', $where);

                                                        $new_date = date("Y-m-d", strtotime($internship_details->internship_startdate));
                                                        $now = time(); // or your date as well
                                                        $your_date = strtotime($new_date);
                                                        $datediff = $now - $your_date;
                                                        $days_diff = floor($datediff / (60 * 60 * 24));
                                                        // print_r($can_log_details);

                                                        if ($days_diff >= 0) {
                                                            if (($internship_list->application_status == 2) && ($internship_list->hiring_status == 1 || $internship_list->hiring_status == 3)) {
                                                        ?>
                                                                <td class="text-center">
                                                                    <?php if ($internship_list->complete_status == 1) {
                                                                        if ($internship_list->complete_type != 1) {
                                                                            // echo "<span class='badge badge-red fw-normal'>Dropped</span>";
                                                                        } else { ?>
                                                                            <?php if (isset($can_log_details) && !empty($can_log_details)) { ?>
                                                                                <?php if (isset($internship_list->emp_ratings) && !empty($internship_list->emp_ratings)) {

                                                                                ?>
                                                                                    <a onclick="emp_rating_download('<?php echo $internship_list->internship_id; ?>');" data-bs-toggle="tooltip" title="Download Work Report" class="">

                                                                                        <img src="<?= base_url(); ?>/public/assets/img/d_report_o.svg" alt="report" width="18">
                                                                                    </a>
                                                                                <?php } else {
                                                                                ?>
                                                                                    <a <?php if ($can_profile_details->payment_package_type > 0) { ?> href="<?= base_url(); ?>/log-work-report/<?php echo $internship_list->internship_id; ?> " <?php } else { ?> onclick="candidate_paid_request()" <?php } ?> data-bs-toggle="tooltip" title="Download Work Report" class="">

                                                                                        <img src="<?= base_url(); ?>/public/assets/img/d_report_o.svg" alt="report" width="18">
                                                                                    </a>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                            <?php if ($internship_list->certificate_issue_status == 1) { ?>
                                                                                <span class="divider text-gray1 mx-1"></span>
                                                                                <a href="<?= base_url(); ?>/candidate-certificate/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View Certificate" class="">

                                                                                    <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="18">
                                                                                </a>
                                                                            <?php } ?>
                                                                        <?php }
                                                                    } else {
                                                                        ?>
                                                                        <!-- <a href="#" onclick="complete_internship('<?php echo $internship_list->internship_id; ?>')">
                                                                    <button class="btn btn-danger f-13 py-1 px-2">Complete Internship</button>
                                                                </a> -->
                                                                        <a href="#" onclick="complete_internship('<?php echo $internship_list->internship_id; ?>','<?php echo base64_encode($internship_list->complete_reason); ?>','<?php echo $internship_list->complete_type; ?>','<?php echo $internship_list->can_ratings; ?>')" data-bs-toggle="modal" data-bs-target="#complete_intern">
                                                                            <span data-bs-toggle="tooltip" title="Complete Internship"><img src="<?= base_url(); ?>/public/assets/img/completeIntern_o.svg" alt="Complete Internship" width="23"></span>
                                                                        </a>
                                                                        <span class="divider text-gray1 mx-1"></span>
                                                                        <a href="<?= base_url(); ?>/candidate-logsheet/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View / Add Log" class="">

                                                                            <img src="<?= base_url(); ?>/public/assets/img/report_o.svg" alt="report" width="18">
                                                                        </a>
                                                                        <?php if ($internship_list->certificate_issue_status == 1) { ?>
                                                                            <span class="divider text-gray1 mx-1"></span>
                                                                            <a href="<?= base_url(); ?>/candidate-certificate/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View Certificate" class="">

                                                                                <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="18">
                                                                            </a>
                                                                        <?php } ?>
                                                                        <!--<span class="divider text-gray1 mx-1"></span>
                                                                 <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')" class="" data-bs-toggle="tooltip" title="View Certificate">
                                                                <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="17" >
                                                            </a> -->
                                                                    <?php } ?>
                                                                    <?php if ($internship_list->hiring_status == 1) {
                                                                        if ($internship_details->internship_startdate <= $current_date) {
                                                                            if (!empty($internship_list->faculty_id)) {
                                                                                $where = array('userid ' => $internship_list->faculty_id);
                                                                                $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                                    ?>
                                                                                <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                                    <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                                </a>
                                                                    <?php }
                                                                        }
                                                                    } ?>

                                                                </td>

                                                            <?php } else { ?>
                                                                <td class="text-center">
                                                                    <?php
                                                                    if ($current_date < $expire_date) {
                                                                        if ($internship_list->application_status == 2) {
                                                                            if ($internship_list->hiring_status == 0) {
                                                                    ?>
                                                                                <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                                <a href="#" onclick="confirm_under_cons('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-orange f-12 py-1 px-2">Under Consideration</button></a>

                                                                            <?php
                                                                            }
                                                                            if ($internship_list->hiring_status == 4) { ?>
                                                                                <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                            <?php }

                                                                            if ($internship_list->hiring_status == 1) {
                                                                            ?>
                                                                                <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-danger f-12 py-1 px-2" style="white-space: nowrap;">Cancel Offer</button></a>
                                                                    <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <?php if ($internship_list->hiring_status == 1) {
                                                                        if ($internship_details->internship_startdate <= $current_date) {
                                                                            if (!empty($internship_list->faculty_id)) {
                                                                                $where = array('userid ' => $internship_list->faculty_id);
                                                                                $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                                    ?>
                                                                                <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                                    <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                                </a>
                                                                    <?php }
                                                                        }
                                                                    } ?>

                                                                </td>

                                                            <?php }
                                                        } else { ?>
                                                            <td class="text-center">
                                                                <?php
                                                                if ($current_date < $expire_date) {
                                                                    if ($internship_list->application_status == 2) {
                                                                        if ($internship_list->hiring_status == 0) {
                                                                ?>
                                                                            <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                            <a href="#" onclick="confirm_under_cons('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-orange f-12 py-1 px-2">









                                                                                </button></a>
                                                                        <?php
                                                                        }
                                                                        if ($internship_list->hiring_status == 4) { ?>
                                                                            <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                        <?php }

                                                                        if ($internship_list->hiring_status == 1) {
                                                                        ?>
                                                                            <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-danger f-12 py-1 px-2" style="white-space: nowrap;">Cancel Offer</button></a>
                                                                <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <?php if ($internship_list->hiring_status == 1) {
                                                                    if ($internship_details->internship_startdate <= $current_date) {
                                                                        if (!empty($internship_list->faculty_id)) {
                                                                            $where = array('userid ' => $internship_list->faculty_id);
                                                                            $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                                ?>
                                                                            <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                                <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                            </a>
                                                                <?php }
                                                                    }
                                                                } ?>
                                                            </td>

                                                        <?php } ?>


                                                    </tr>
                                                <?php $i++;
                                                }
                                            } elseif ($active_sort_my_application == 7) {
                                                // echo $expire_date."ttrr";
                                                if ($current_date < $expire_date) {

                                                ?>
                                                    <tr>
                                                        <td scope="row"><?php echo $i; ?></td>
                                                        <td class="overflow-anywhere"><a href="<?= base_url(); ?>/employer-details/<?php echo $internship_details->company_id; ?>" class="text-blue1"><?php if (isset($employer_details->profile_company_name)) {
                                                                                                                                                                                                            echo $employer_details->profile_company_name;
                                                                                                                                                                                                        } ?></a></td>
                                                        <td><a href="<?php echo base_url('internship-details'); ?>/<?php echo $internship_list->internship_id; ?>" class="text-blue1"><?php if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                                                                                                                                                                            echo $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                                                                                                                                                                        } else {
                                                                                                                                                                                            echo $internship_details->other_profile;
                                                                                                                                                                                        } ?></a></td>
                                                        <td><?php echo $newDate = date("d-M-Y", strtotime($internship_list->created_at)); ?></td>
                                                        <!-- <td class="text-center"><?php if (isset($applicant_count)) {
                                                                                            echo $applicant_count;
                                                                                        } ?></td> -->
                                                        <td class="text-center"><?php if ($internship_details->stipend != '1') {
                                                                                    if (isset($internship_details->amount_from) && $internship_details->amount_from != '0') {
                                                                                        echo '₹ ' . $internship_details->amount_from;
                                                                                    } ?> <?php if (isset($internship_details->amount_to) && $internship_details->amount_to != '0') {
                                                                                        echo '- ' . $internship_details->amount_to;
                                                                                    }
                                                                                } else {
                                                                                    echo "-";
                                                                                } ?></td>
                                                        <td class="text-center">
                                                            <?php
                                                            // print_r($internship_list);
                                                            // echo $internship_details->internship_startdate;


                                                            if (!empty($internship_list)) {

                                                                //if hired 
                                                                if ($internship_list->hiring_status == 1) {
                                                                    if ($internship_details->internship_startdate <= $current_date) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                                            <?php
                                                                            echo "<span class='badge status-ongoing fw-normal'>Ongoing</span>";
                                                                            ?></span><?php
                                                                                } else { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the employers offer">
                                                                            <?php
                                                                                    echo "<span class='badge badge-completed fw-normal'>Offer accepted</span>";
                                                                            ?></span><?php
                                                                                }
                                                                            } elseif ($internship_list->hiring_status == 2) { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have rejected the employers offer">
                                                                        <?php
                                                                                echo "<span class='badge badge-red fw-normal'>Offer declined</span>";
                                                                        ?></span><?php
                                                                            } elseif ($internship_list->hiring_status == 4) {
                                                                                if ($internship_details->internship_startdate <= $current_date) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                                            <?php
                                                                                    echo "<span class='badge badge-red fw-normal'>Offer declined</span>";
                                                                            ?></span><?php
                                                                                } else { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="When you opt for 
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                , you will have time until internship start date to accept/cancel offer. If you fail to take action before that then status is set as 'offer declined'">
                                                                            <?php
                                                                                    echo "<span class='badge status-consider fw-normal'>Under Consideration</span>";
                                                                            ?></span><?php
                                                                                }
                                                                            } elseif ($internship_list->complete_status == 1) {
                                                                                if ($internship_list->complete_type != 1) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not completed the internship">
                                                                            <?php
                                                                                    echo "<span class='badge status-dropped fw-normal'>Dropped</span>";
                                                                            ?></span><?php
                                                                                } else { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have completed the internship">
                                                                            <?php
                                                                                    echo "<span class='badge badge-completed fw-normal'>Completed</span>";
                                                                            ?></span><?php
                                                                                }
                                                                            } else {
                                                                                if ($internship_list->application_status == 0) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is submitted to the employer successfully">
                                                                            <?php
                                                                                    echo "<span class='badge status-review fw-normal me-2'>Under review</span>";
                                                                            ?></span><?php
                                                                                } elseif ($internship_list->application_status == 1) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is submitted to the employer successfully">
                                                                            <?php
                                                                                    echo "<span class='badge status-review fw-normal me-2'>Under review</span>";
                                                                            ?></span><?php
                                                                                } elseif ($internship_list->application_status == 2) {
                                                                                    if ($current_date >= $expire_date) { ?>
                                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not taken any action on the offer sent by the employer until internship start date">
                                                                                <?php
                                                                                        echo "<span class='badge status-expired fw-normal'>Offer expired</span>";
                                                                                ?></span><?php
                                                                                    } else { ?>
                                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You are hired by the employer">
                                                                                <?php
                                                                                        echo "<span class='badge badge-completed fw-normal mb-1 me-2'>Hired</span>";
                                                                                ?></span><?php
                                                                                    }
                                                                                } elseif ($internship_list->application_status == 3) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is rejected by the employer">
                                                                            <?php
                                                                                    echo "<span class='badge status-notqualified fw-normal'>Not Qualified</span>";
                                                                            ?></span><?php
                                                                                }
                                                                            }
                                                                        } else {
                                                                                    ?>
                                                                <span class="badge badge-gray fw-normal me-2"><img src='<?php echo base_url("/public/assets/img/icon_company1.svg"); ?>' width='12' class='me-1'>
                                                                    Not Applied</span>
                                                            <?php
                                                                        }


                                                                        //    if (!empty($internship_list)) 
                                                                        //          {

                                                                        //    }

                                                            ?>
                                                            <!-- <span class="badge badge-ongoing fw-normal"><?php if ($internship_list->application_status == 0) {
                                                                                                                    echo "Under Review";
                                                                                                                } elseif ($internship_list->application_status == 2) {
                                                                                                                    echo "Hired";
                                                                                                                } elseif ($internship_list->application_status == 3) {
                                                                                                                    echo "Not Qualified";
                                                                                                                } ?></span> -->
                                                            <!-- <i class="fa fa-question-circle text-muted ms-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="That means it is taken by the employer"></i> -->
                                                        </td>
                                                        <!--  <td class="text-center">
                                    <a href="<?php //echo base_url('can-apply-for-internship'); 
                                                ?>/<?php //echo $internship_list->internship_id; 
                                                        ?>"><img src="<?= base_url(); ?>/public/assets/img/icon_review.svg" alt="View" width="15"></a>
                                </td> -->
                                                        <?php

                                                        $where = array('status' => '1', 'internship_id' => $internship_list->internship_id, 'user_id' => $userid);
                                                        $can_log_details = $Candidate_model->fetch_table_data('can_log_sheet', $where);

                                                        $new_date = date("Y-m-d", strtotime($internship_details->internship_startdate));
                                                        $now = time(); // or your date as well
                                                        $your_date = strtotime($new_date);
                                                        $datediff = $now - $your_date;
                                                        $days_diff = floor($datediff / (60 * 60 * 24));
                                                        // print_r($can_log_details);

                                                        if ($days_diff >= 0) {
                                                            if (($internship_list->application_status == 2) && ($internship_list->hiring_status == 1 || $internship_list->hiring_status == 3)) {
                                                        ?>
                                                                <td class="text-center">
                                                                    <?php if ($internship_list->complete_status == 1) {
                                                                        if ($internship_list->complete_type != 1) {
                                                                            // echo "<span class='badge badge-red fw-normal'>Dropped</span>";
                                                                        } else { ?>
                                                                            <?php if (isset($can_log_details) && !empty($can_log_details)) { ?>
                                                                                <?php if (isset($internship_list->emp_ratings) && !empty($internship_list->emp_ratings)) { ?>
                                                                                    <a onclick="emp_rating_download('<?php echo $internship_list->internship_id; ?>');" data-bs-toggle="tooltip" title="Download Work Report" class="">

                                                                                        <img src="<?= base_url(); ?>/public/assets/img/d_report_o.svg" alt="report" width="18">
                                                                                    </a>
                                                                                <?php } else { ?>
                                                                                    <a <?php if ($can_profile_details->payment_package_type > 0) { ?> href="<?= base_url(); ?>/log-work-report/<?php echo $internship_list->internship_id; ?> " <?php } else { ?> onclick="candidate_paid_request()" <?php } ?> data-bs-toggle="tooltip" title="Download Work Report" class="">

                                                                                        <img src="<?= base_url(); ?>/public/assets/img/d_report_o.svg" alt="report" width="18">
                                                                                    </a>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                            <?php if ($internship_list->certificate_issue_status == 1) { ?>
                                                                                <span class="divider text-gray1 mx-1"></span>
                                                                                <a href="<?= base_url(); ?>/candidate-certificate/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View Certificate" class="">

                                                                                    <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="18">
                                                                                </a>
                                                                            <?php } ?>
                                                                        <?php }
                                                                    } else {
                                                                        ?>
                                                                        <!-- <a href="#" onclick="complete_internship('<?php echo $internship_list->internship_id; ?>')">
                                                                    <button class="btn btn-danger f-13 py-1 px-2">Complete Internship</button>
                                                                </a> -->
                                                                        <a href="#" onclick="complete_internship('<?php echo $internship_list->internship_id; ?>','<?php echo base64_encode($internship_list->complete_reason); ?>','<?php echo $internship_list->complete_type; ?>','<?php echo $internship_list->can_ratings; ?>')" data-bs-toggle="modal" data-bs-target="#complete_intern">
                                                                            <span data-bs-toggle="tooltip" title="Complete Internship"><img src="<?= base_url(); ?>/public/assets/img/completeIntern_o.svg" alt="Complete Internship" width="23"></span>
                                                                        </a>
                                                                        <span class="divider text-gray1 mx-1"></span>
                                                                        <a href="<?= base_url(); ?>/candidate-logsheet/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View / Add Log" class="">

                                                                            <img src="<?= base_url(); ?>/public/assets/img/report_o.svg" alt="report" width="18">
                                                                        </a>
                                                                        <?php if ($internship_list->certificate_issue_status == 1) { ?>
                                                                            <span class="divider text-gray1 mx-1"></span>
                                                                            <a href="<?= base_url(); ?>/candidate-certificate/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View Certificate" class="">

                                                                                <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="18">
                                                                            </a>
                                                                        <?php } ?>
                                                                        <!--<span class="divider text-gray1 mx-1"></span>
                                                                 <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')" class="" data-bs-toggle="tooltip" title="View Certificate">
                                                                <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="17" >
                                                            </a> -->
                                                                    <?php } ?>
                                                                    <?php if ($internship_list->hiring_status == 1) {
                                                                        if ($internship_details->internship_startdate <= $current_date) {
                                                                            if (!empty($internship_list->faculty_id)) {
                                                                                $where = array('userid ' => $internship_list->faculty_id);
                                                                                $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                                    ?>
                                                                                <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                                    <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                                </a>
                                                                    <?php }
                                                                        }
                                                                    } ?>
                                                                </td>

                                                            <?php } else { ?>
                                                                <td class="text-center">
                                                                    <?php
                                                                    if ($current_date < $expire_date) {
                                                                        if ($internship_list->application_status == 2) {
                                                                            if ($internship_list->hiring_status == 0) {
                                                                    ?>
                                                                                <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                                <a href="#" onclick="confirm_under_cons('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-12 py-1 px-2">Under Consideration</button></a>
                                                                            <?php
                                                                            }
                                                                            if ($internship_list->hiring_status == 4) { ?>
                                                                                <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                            <?php }

                                                                            if ($internship_list->hiring_status == 1) {
                                                                            ?>
                                                                                <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-danger f-12 py-1 px-2" style="white-space: nowrap;">Cancel Offer</button></a>
                                                                    <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <?php if ($internship_list->hiring_status == 1) {
                                                                        if ($internship_details->internship_startdate <= $current_date) {
                                                                            if (!empty($internship_list->faculty_id)) {
                                                                                $where = array('userid ' => $internship_list->faculty_id);
                                                                                $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                                    ?>
                                                                                <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                                    <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                                </a>
                                                                    <?php }
                                                                        }
                                                                    } ?>
                                                                </td>

                                                            <?php }
                                                        } else { ?>
                                                            <td class="text-center">
                                                                <?php
                                                                if ($current_date < $expire_date) {
                                                                    if ($internship_list->application_status == 2) {
                                                                        if ($internship_list->hiring_status == 0) {
                                                                ?>
                                                                            <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                            <a href="#" onclick="confirm_under_cons('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-12 py-1 px-2">Under Consideration</button></a>
                                                                        <?php
                                                                        }
                                                                        if ($internship_list->hiring_status == 4) { ?>
                                                                            <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                        <?php }

                                                                        if ($internship_list->hiring_status == 1) {
                                                                        ?>
                                                                            <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-danger f-12 py-1 px-2" style="white-space: nowrap;">Cancel Offer</button></a>
                                                                <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <?php if ($internship_list->hiring_status == 1) {
                                                                    if ($internship_details->internship_startdate <= $current_date) {
                                                                        if (!empty($internship_list->faculty_id)) {
                                                                            $where = array('userid ' => $internship_list->faculty_id);
                                                                            $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                                ?>
                                                                            <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                                <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                            </a>
                                                                <?php }
                                                                    }
                                                                } ?>
                                                            </td>
                                                        <?php } ?>


                                                    </tr>
                                                <?php $i++;
                                                }
                                            } else {
                                                // echo $expire_date."else";
                                                ?>
                                                <tr>
                                                    <td scope="row"><?php echo $i; ?></td>
                                                    <td class="overflow-anywhere"><a href="<?= base_url(); ?>/employer-details/<?php echo $internship_details->company_id; ?>" class="text-blue1"><?php if (isset($employer_details->profile_company_name)) {
                                                                                                                                                                                                        echo $employer_details->profile_company_name;
                                                                                                                                                                                                    } ?></a></td>
                                                    <td><a href="<?php echo base_url('internship-details'); ?>/<?php echo $internship_list->internship_id; ?>" class="text-blue1"><?php if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                                                                                                                                                                        echo $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                                                                                                                                                                    } else {
                                                                                                                                                                                        echo $internship_details->other_profile;
                                                                                                                                                                                    } ?></a></td>
                                                    <td><?php echo $newDate = date("d-M-Y", strtotime($internship_list->created_at)); ?></td>
                                                    <!-- <td class="text-center"><?php if (isset($applicant_count)) {
                                                                                        echo $applicant_count;
                                                                                    } ?></td> -->
                                                    <td class="text-center"><?php if ($internship_details->stipend != '1') {
                                                                                if (isset($internship_details->amount_from) && $internship_details->amount_from != '0') {
                                                                                    echo '₹ ' . $internship_details->amount_from;
                                                                                } ?> <?php if (isset($internship_details->amount_to) && $internship_details->amount_to != '0') {
                                                                                            echo '- ' . $internship_details->amount_to;
                                                                                        }
                                                                                    } else {
                                                                                        echo "-";
                                                                                    } ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        // print_r($internship_list);
                                                        // echo $internship_details->internship_startdate;


                                                        if (!empty($internship_list)) {

                                                            //if hired 
                                                            if ($internship_list->hiring_status == 1) {
                                                                if ($internship_details->internship_startdate <= $current_date) { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                                        <?php
                                                                        echo "<span class='badge status-ongoing fw-normal'>Ongoing</span>";
                                                                        ?></span><?php
                                                                                } else { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the employers offer">
                                                                        <?php
                                                                                    echo "<span class='badge badge-completed fw-normal'>Offer accepted</span>";
                                                                        ?></span><?php
                                                                                }
                                                                            } elseif ($internship_list->hiring_status == 2) { ?>
                                                                <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have rejected the employers offer">
                                                                    <?php
                                                                                echo "<span class='badge badge-red fw-normal'>Offer declined</span>";
                                                                    ?></span><?php
                                                                            } elseif ($internship_list->hiring_status == 4) {
                                                                                if ($internship_details->internship_startdate <= $current_date) { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have accepted the offer sent by the employer and the internship started already">
                                                                        <?php
                                                                                    echo "<span class='badge badge-red fw-normal'>Offer declined</span>";
                                                                        ?></span><?php
                                                                                        } else { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="When you opt for under consideration, you will have time till internship start date to accept the offer. If you fail to accept the offer before that, then status is set as 'offer declined'">
                                                                        <?php
                                                                                            echo "<span class='badge status-consider fw-normal'>Under Consideration</span>";
                                                                        ?></span><?php
                                                                                        }
                                                                                    } elseif ($internship_list->complete_status == 1) {
                                                                                        if ($internship_list->complete_type != 1) { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not completed the internship">
                                                                        <?php
                                                                                            echo "<span class='badge status-dropped fw-normal'>Dropped</span>";
                                                                        ?></span><?php
                                                                                        } else { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have completed the internship">
                                                                        <?php
                                                                                            echo "<span class='badge badge-completed fw-normal'>Completed</span>";
                                                                        ?></span><?php
                                                                                        }
                                                                                    } else {
                                                                                        if ($internship_list->application_status == 0) { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is submitted to the employer successfully">
                                                                        <?php
                                                                                            echo "<span class='badge status-review fw-normal me-2'>Under review</span>";
                                                                        ?></span><?php
                                                                                        } elseif ($internship_list->application_status == 1) { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is submitted to the employer successfully">
                                                                        <?php
                                                                                            echo "<span class='badge status-review fw-normal me-2'>Under review</span>";
                                                                        ?></span><?php
                                                                                        } elseif ($internship_list->application_status == 2) {
                                                                                            if ($current_date >= $expire_date) { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You have not taken any action on the offer sent by the employer until internship start date">
                                                                            <?php
                                                                                                echo "<span class='badge status-expired fw-normal'>Offer expired</span>";
                                                                            ?></span><?php
                                                                                            } else { ?>
                                                                        <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="You are hired by the employer">
                                                                            <?php
                                                                                                echo "<span class='badge badge-completed fw-normal mb-1 me-2'>Hired</span>";
                                                                            ?></span><?php
                                                                                            }
                                                                                        } elseif ($internship_list->application_status == 3) { ?>
                                                                    <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Your application is rejected by the employer">
                                                                        <?php
                                                                                            echo "<span class='badge status-notqualified fw-normal'>Not Qualified</span>";
                                                                        ?></span><?php
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    ?>
                                                            <span class="badge badge-gray fw-normal me-2"><img src='<?php echo base_url("/public/assets/img/icon_company1.svg"); ?>' width='12' class='me-1'>
                                                                Not Applied</span>
                                                        <?php
                                                                                }


                                                                                //    if (!empty($internship_list)) 
                                                                                //          {

                                                                                //    }

                                                        ?>
                                                        <!-- <span class="badge badge-ongoing fw-normal"><?php if ($internship_list->application_status == 0) {
                                                                                                                echo "Under Review";
                                                                                                            } elseif ($internship_list->application_status == 2) {
                                                                                                                echo "Hired";
                                                                                                            } elseif ($internship_list->application_status == 3) {
                                                                                                                echo "Not Qualified";
                                                                                                            } ?></span> -->
                                                        <!-- <i class="fa fa-question-circle text-muted ms-2" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="That means it is taken by the employer"></i> -->
                                                    </td>
                                                    <!--  <td class="text-center">
                                    <a href="<?php //echo base_url('can-apply-for-internship'); 
                                                ?>/<?php //echo $internship_list->internship_id; 
                                                    ?>"><img src="<?= base_url(); ?>/public/assets/img/icon_review.svg" alt="View" width="15"></a>
                                </td> -->
                                                    <?php

                                                    $where = array('status' => '1', 'internship_id' => $internship_list->internship_id, 'user_id' => $userid);
                                                    $can_log_details = $Candidate_model->fetch_table_data('can_log_sheet', $where);

                                                    $new_date = date("Y-m-d", strtotime($internship_details->internship_startdate));
                                                    $now = time(); // or your date as well
                                                    $your_date = strtotime($new_date);
                                                    $datediff = $now - $your_date;
                                                    $days_diff = floor($datediff / (60 * 60 * 24));
                                                    // print_r($can_log_details);

                                                    if ($days_diff >= 0) {
                                                        if (($internship_list->application_status == 2) && ($internship_list->hiring_status == 1 || $internship_list->hiring_status == 3)) {
                                                    ?>
                                                            <td class="text-center">
                                                                <?php if ($internship_list->complete_status == 1) {
                                                                    if ($internship_list->complete_type != 1) {
                                                                        // echo "<span class='badge badge-red fw-normal'>Dropped</span>";
                                                                    } else { ?>
                                                                        <?php if (isset($can_log_details) && !empty($can_log_details)) { ?>
                                                                            <?php if (isset($internship_list->emp_ratings) && !empty($internship_list->emp_ratings)) { ?>
                                                                                <a onclick="emp_rating_download('<?php echo $internship_list->internship_id; ?>');" data-bs-toggle="tooltip" title="Download Work Report" class="">

                                                                                    <img src="<?= base_url(); ?>/public/assets/img/d_report_o.svg" alt="report" width="18">
                                                                                </a>
                                                                            <?php } else { ?>
                                                                                <a <?php if ($can_profile_details->payment_package_type > 0) { ?> href="<?= base_url(); ?>/log-work-report/<?php echo $internship_list->internship_id; ?> " <?php } else { ?> onclick="candidate_paid_request()" <?php } ?> data-bs-toggle="tooltip" title="Download Work Report" class="">

                                                                                    <img src="<?= base_url(); ?>/public/assets/img/d_report_o.svg" alt="report" width="18">
                                                                                </a>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        <?php if ($internship_list->certificate_issue_status == 1) { ?>
                                                                            <span class="divider text-gray1 mx-1"></span>
                                                                            <a href="<?= base_url(); ?>/candidate-certificate/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View Certificate" class="">

                                                                                <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="18">
                                                                            </a>
                                                                        <?php } ?>
                                                                    <?php }
                                                                } else {
                                                                    ?>
                                                                    <!-- <a href="#" onclick="complete_internship('<?php echo $internship_list->internship_id; ?>')">
                                                                    <button class="btn btn-danger f-13 py-1 px-2">Complete Internship</button>
                                                                </a> -->
                                                                    <a href="#" onclick="complete_internship('<?php echo $internship_list->internship_id; ?>','<?php echo base64_encode($internship_list->complete_reason); ?>','<?php echo $internship_list->complete_type; ?>','<?php echo $internship_list->can_ratings; ?>')" data-bs-toggle="modal" data-bs-target="#complete_intern">
                                                                        <span data-bs-toggle="tooltip" title="Complete Internship"><img src="<?= base_url(); ?>/public/assets/img/completeIntern_o.svg" alt="Complete Internship" width="23"></span>
                                                                    </a>
                                                                    <span class="divider text-gray1 mx-1"></span>
                                                                    <a href="<?= base_url(); ?>/candidate-logsheet/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View / Add Log" class="">

                                                                        <img src="<?= base_url(); ?>/public/assets/img/report_o.svg" alt="report" width="18">
                                                                    </a>
                                                                    <?php if ($internship_list->certificate_issue_status == 1) { ?>
                                                                        <span class="divider text-gray1 mx-1"></span>
                                                                        <a href="<?= base_url(); ?>/candidate-certificate/<?php echo $internship_list->internship_id; ?>" data-bs-toggle="tooltip" title="View Certificate" class="">

                                                                            <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="18">
                                                                        </a>
                                                                    <?php } ?>
                                                                    <!--<span class="divider text-gray1 mx-1"></span>
                                                                 <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')" class="" data-bs-toggle="tooltip" title="View Certificate">
                                                                <img src="<?= base_url(); ?>/public/assets/img/menu_certificate.svg" alt="certificate" width="17" >
                                                            </a> -->
                                                                <?php } ?>
                                                                <?php if ($internship_list->hiring_status == 1) {
                                                                    if ($internship_details->internship_startdate <= $current_date) {
                                                                        if (!empty($internship_list->faculty_id)) {
                                                                            $where = array('userid ' => $internship_list->faculty_id);
                                                                            $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                                ?>
                                                                            <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                                <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                            </a>
                                                                <?php }
                                                                    }
                                                                } ?>
                                                            </td>

                                                        <?php } else { ?>
                                                            <td class="text-center">
                                                                <?php
                                                                if ($current_date < $expire_date) {
                                                                    if ($internship_list->application_status == 2) {
                                                                        if ($internship_list->hiring_status == 0) {
                                                                ?>
                                                                            <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                            <a href="#" onclick="confirm_under_cons('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-orange f-12 py-1 px-2 my-1">Under Consideration</button></a>
                                                                        <?php
                                                                        }
                                                                        if ($internship_list->hiring_status == 4) { ?>
                                                                            <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                        <?php }

                                                                        if ($internship_list->hiring_status == 1) {
                                                                        ?>
                                                                            <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-danger f-12 py-1 px-2" style="white-space: nowrap;">Cancel Offer</button></a>
                                                                <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <?php if ($internship_list->hiring_status == 1) {
                                                                    if ($internship_details->internship_startdate <= $current_date) {
                                                                        if (!empty($internship_list->faculty_id)) {
                                                                            $where = array('userid ' => $internship_list->faculty_id);
                                                                            $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                                ?>
                                                                            <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                                <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                            </a>
                                                                <?php }
                                                                    }
                                                                } ?>
                                                            </td>

                                                        <?php }
                                                    } else { ?>
                                                        <td class="text-center">
                                                            <?php
                                                            if ($current_date < $expire_date) {
                                                                if ($internship_list->application_status == 2) {
                                                                    if ($internship_list->hiring_status == 0) {
                                                            ?>
                                                                        <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                        <a href="#" onclick="confirm_under_cons('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-orange f-12 py-1 px-2">Under Consideration</button></a>

                                                                    <?php
                                                                    }
                                                                    if ($internship_list->hiring_status == 4) { ?>
                                                                        <a href="#" onclick="confirm_accept('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-green f-13 py-1 px-2" data-bs-toggle="modal">Accept Offer</button></a>
                                                                    <?php }

                                                                    if ($internship_list->hiring_status == 1) {
                                                                    ?>
                                                                        <a href="#" onclick="confirm_reject('<?php echo $internship_list->internship_id; ?>')"><button class="btn btn-danger f-12 py-1 px-2" style="white-space: nowrap;">Cancel Offer</button></a>
                                                            <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <?php if ($internship_list->hiring_status == 1) {
                                                                if ($internship_details->internship_startdate <= $current_date) {
                                                                    if (!empty($internship_list->faculty_id)) {
                                                                        $where = array('userid ' => $internship_list->faculty_id);
                                                                        $faculty_detials = $Candidate_model->fetch_table_row('faculty_reg_data', $where);
                                                            ?>
                                                                        <span class="divider text-gray1 mx-1"></span><a data-bs-toggle="tooltip" title="" class="" data-bs-original-title="View / Edit Faculty Details" onclick="confirm_faculty_update('<?php echo $internship_list->id; ?>','<?php echo $education_details[0]->education_college_name; ?>','<?php echo $education_college_name; ?>','<?php echo $internship_list->internship_id; ?>','<?php echo $internship_list->college_reg_number; ?>','<?php echo $faculty_detials->faculty_name; ?>','<?php echo $faculty_detials->faculty_email; ?>')">

                                                                            <img src="<?= base_url(); ?>/public/assets/img//view_faculty_ico.svg" alt="certificate" width="18">
                                                                        </a>
                                                            <?php }
                                                                }
                                                            } ?>
                                                        </td>

                                                    <?php } ?>


                                                </tr>
                                    <?php $i++;
                                            }
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="tab-pane fade" id="intoff" role="tabpanel" aria-labelledby="intoff-tab">...</div>
                    <div class="tab-pane fade" id="myint" role="tabpanel" aria-labelledby="myint-tab">...</div> -->
                </div>

            </div>
        </div>
    </section>
    <!-- Modal complete intern -->
    <!-- <div class="modal fade" id="complete_intern" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">
                <form method="post" action="<?php echo base_url('/Reject-Hiring'); ?>">
                    <input type="hidden" name="reject_id" id="reject_id">
                    <input type="hidden" class="csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <div class="modal-header justify-content-center border-bottom-0 pt-4">
                        <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Share Your Feedback</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-0 px-4">
                        <label for="" class="form-label w-100">Rate Us</label>
                        <div class="rate float-start mb-3">
                            <input type="radio" id="star1" name="rate" value="1" />
                            <label for="star1" title="text">1 star</label>
                            <input type="radio" id="star2" name="rate" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star3" name="rate" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star4" name="rate" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star5" name="rate" value="5" />
                            <label for="star5" title="text">5 stars</label>
                        </div>

                        <div class="form-group mb-4">
                            <label for="" class="form-label w-100">Feedback</label>
                            <textarea maxlength="500" class="form-control filledBox border-0 py-2 f-14" placeholder="Tell us about your experience taking this internship. Was it a good match for you?" style="height: 100px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 justify-content-end pt-0 pb-4 px-4">
                        <input type="submit" class="btn btn-prim float-end px-3" id="reject_submit" value="Complete" />
                    </div>
                </form>
            </div>


        </div>
    </div> -->
    <!-- Modal cancel -->
    <div class="modal fade" id="reject_popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">
                <form method="post" action="<?php echo base_url('/Reject-Hiring'); ?>">
                    <input type="hidden" name="reject_id" id="reject_id">
                    <input type="hidden" name="redirect_url" id="redirect_url" value="1">
                    <input type="hidden" class="csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <div class="modal-header justify-content-center border-bottom-0 pt-4">
                        <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Cancelation Reason</h5>
                    </div>
                    <div class="modal-body pb-0 px-4">
                        <div class="col-12 form-group selectField mb-4">
                            <label for="" class="form-label">Enter Reason <span style="color:#dd4b39;">*</span></label>
                            <textarea maxlength="500" id="reject_reson" name="reject_reson" class="form-control filledBox border-0 py-2 f-14" placeholder="Reason for Cancel Offer (max 500 char)" style="height: 100px;"></textarea>
                            <span id='remainingC'></span>
                            <font style="color:#dd4b39;">
                                <div id="reject_reson_alert"></div>
                            </font>

                        </div>
                    </div>
                    <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-prim float-end" id="reject_submit" value="Submit" />
                    </div>
                </form>
            </div>


        </div>
    </div>

    <div class="modal fade" id="under_cons_popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">
                <form method="post" action="<?php echo base_url('/add_under_consideration'); ?>">
                    <input type="hidden" name="under_cons_id" id="under_cons_id">
                    <input type="hidden" name="redirect_url" id="redirect_url" value="1">
                    <input type="hidden" class="csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <div class="modal-header justify-content-center border-bottom-0 pt-4">
                        <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Under Consideration Reason</h5>
                    </div>
                    <div class="modal-body pb-0 px-4">
                        <div class="col-12 form-group selectField mb-4">
                            <label for="" class="form-label">Enter Reason <span style="color:#dd4b39;">*</span></label>
                            <textarea maxlength="500" id="under_cons_reson" name="under_cons_reson" class="form-control filledBox border-0 py-2 f-14" placeholder="Reason for Under Consideration (max 500 char)" style="height: 100px;"></textarea>
                            <span id='remainingC2'></span>
                            <font style="color:#dd4b39;">
                                <div id="under_cons_reson_alert"></div>
                            </font>

                        </div>
                    </div>
                    <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-prim float-end" id="under_cons_submit" value="Submit" />
                    </div>
                </form>
            </div>


        </div>
    </div>

    <!-- Modal cancel -->
    <div class="modal fade" id="complete_intern" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">
                <form method="post" action="<?php echo base_url('/complete-internship'); ?>">
                    <input type="hidden" name="complete_id" id="complete_id">
                    <input type="hidden" name="complete_type" id="complete_type" value="">
                    <input type="hidden" name="page_redirect" id="page_redirect" value="1">
                    <input type="hidden" class="csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <div class="modal-header justify-content-center border-bottom-0 pt-4">
                        <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Share Your Feedback</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body pb-0 px-4">
                        <div class="form-group">
                            <label for="" class="form-label">Complete Reason</label> <span style="color:red;">*</span>
                            <select id="add_reason_type" name="add_reason_type" onchange="fun_reason_type(this.value)" class="selectpicker filledBox form-control f-14 border-0 mb-4">
                                <option value="">Select Reason</option>
                                <?php if (!empty($complete_reason)) {
                                    foreach ($complete_reason as $reason) { ?>
                                        <option value="<?php echo $reason->id; ?>"><?php echo $reason->reason_type; ?></option>
                                <?php }
                                } ?>
                            </select>
                            <font style="color:#dd4b39;">
                                <div id="add_reason_type_alert"></div>
                            </font>
                        </div>
                        <div class="form-group mb-4">
                            <label for="" class="form-label w-100">Ratings <span style="color:red;">*</span></label>
                            <input type="hidden" name="rating_value" id="rating_value">
                            <div class="rate float-start mb-3">
                                <input type="radio" id="star5" name="add_rate" onclick="fun_rating_value(5)" value="5" />
                                <label for="star5" title="text">1 star</label>
                                <input type="radio" id="star4" name="add_rate" onclick="fun_rating_value(4)" value="4" />
                                <label for="star4" title="text">2 stars</label>
                                <input type="radio" id="star3" name="add_rate" onclick="fun_rating_value(3)" value="3" />
                                <label for="star3" title="text">3 stars</label>
                                <input type="radio" id="star2" name="add_rate" onclick="fun_rating_value(2)" value="2" />
                                <label for="star2" title="text">4 stars</label>
                                <input type="radio" id="star1" name="add_rate" onclick="fun_rating_value(1)" value="1" />
                                <label for="star1" title="text">5 stars</label>
                            </div>

                        </div><BR>
                        <div class="form-group mb-4">
                            <font style="color:#dd4b39;">
                                <div id="rating_value_alert" class="w-100"></div>
                            </font>
                        </div>

                        <div class="form-group mb-4">
                            <label for="" class="form-label w-100">Feedback</label>
                            <textarea maxlength="500" id="complete_reason" name="complete_reason" class="form-control filledBox border-0 py-2 f-14" placeholder="Tell us about your experience taking this internship. Was it a good match for you?" style="height: 100px;"></textarea>
                            <span id='remainingC1'></span>
                            <font style="color:#dd4b39;">
                                <div id="complete_reason_alert" class="w-100"></div>
                            </font>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 justify-content-end pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-prim float-end" id="complete_submit" value="Complete" />
                    </div>
                </form>
            </div>


        </div>
    </div>


    <div class="modal fade" id="facultyconfirm" tabindex="-1" aria-labelledby="facultyconfirmLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= base_url(); ?>/Accept-Hiring" method="post" accept-charset="utf-8" class="" id="add_emp_manage_admin" enctype="multipart/form-data">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                <div class="modal-content">
                    <div class="modal-header justify-content-center border-bottom-0 pt-4">
                        <h5 class="modal-title text-green fw-semibold text-center" id="exampleModalLabel">Do you want to inform your college<br>about this <span class="text-blue">Internship</span></h5>
                    </div>
                    <div class="modal-body pb-0 px-4">
                        <div class="d-flex flex-wrap row">
                            <div class="col-md-12 form-group selectField">
                                <div class="d-flex justify-content-center mb-4">
                                    <div class="form-check me-4">
                                        <input type="hidden" name="faculty" id="faculty" value="">
                                        <input type="hidden" name="college_id" id="college_id" value="">
                                        <input type="hidden" name="college_name" id="college_name" value="">
                                        <input type="hidden" name="id" id="id" value="">
                                        <input type="hidden" name="internship_id11" id="internship_id11" value="">
                                        <input type="hidden" name="ca_type" id="ca_type" value="">
                                        <input type="hidden" name="fa_type" id="fa_type" value="0">
                                        <input type="hidden" name="faculty_email1" id="faculty_email1" value="">
                                        <label class="form-check-label f-14" for="faculty">Yes</label>
                                        <input class="form-check-input" type="radio" id="faculty_yes" name="add_employee_type" onclick="func_employee_type(1)">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label f-14" for="faculty_no">No its fine</label>
                                        <input class="form-check-input" type="radio" id="faculty_no" name="add_employee_type" onclick="func_employee_type(2)">
                                    </div>
                                </div>
                                <font style="color:#dd4b39;">
                                    <div id="faculty_alert"></div>
                                </font>
                            </div>

                            <div id="faculty_data" style="display:none">
                                <div class="col-md-12 form-group selectField">
                                    <label for="" class="form-label">College Name </label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_company.svg" alt="Name" width="14">
                                        </span>
                                        <input type="text" maxlength="50" name="faculty_college" id="faculty_college" class="form-control filledBox border-0 py-2 ps-0 f-14 non_edit" readonly placeholder="Jerusalem College of Engg">

                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="faculty_college_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-12 form-group selectField">
                                    <label for="" class="form-label">Your Registration Number <span style="color:red;">*</span></label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_id.svg" alt="ID" width="16">
                                        </span>
                                        <input type="text" maxlength="15" name="can_reg_number" id="can_reg_number" class="form-control filledBox border-0 py-2 ps-0 f-14" placeholder="Enter Registration No">
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="can_reg_number_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-12 form-group selectField">
                                    <label for="" class="form-label">Faculty Name <span style="color:red;">*</span></label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_user.svg" alt="Name" width="12">
                                        </span>
                                        <input type="text" maxlength="50" name="faculty_name" id="faculty_name" class="form-control filledBox border-0 py-2 ps-0 f-14" placeholder="Enter Full Name">

                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="faculty_name_alert"></div>
                                    </font>
                                    <span id="note_show" style="display:none;">
                                        <div class="pb-0" style="color:#f15e06;"><small><b>Note :</b> Faculty name cannot be edited until the faculty's email ID has been edited</small></div>
                                    </span>

                                </div>
                                <!-- <small class="text-dark badge-sticky text-start f-13 my-3" id="name_note" >Faculty name cannot be edited until the faculty's email ID has been edited</small> -->
                                <div class="col-md-12 form-group selectField mt-3">
                                    <label for="" class="form-label">Faculty Email ID <span style="color:red;">*</span></label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_mail.svg" alt="Mail" width="14">
                                        </span>
                                        <input type="email" autocomplete="off" maxlength="50" name="faculty_email" id="faculty_email" class="form-control filledBox border-0 py-2 ps-0 f-14 email" placeholder="Enter Email ID" onkeyup="profile_email()">
                                        <ul class="autosuffix ps-0"></ul>
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="faculty_email_alert"></div>
                                    </font>
                                </div>


                            </div>
                        </div>
                        <div class="modal-footer border-top-0 justify-content-center pt-0 pb-4 px-4">
                            <input type="submit" class="btn btn-prim" id="faculty_submit" value="Submit" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="emp_rating_confirm" tabindex="-1" aria-labelledby="facultyconfirmLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= base_url(); ?>/log-work-report" method="post" accept-charset="utf-8" class="" id="add_emp_manage_admin" enctype="multipart/form-data">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                <div class="modal-content">
                    <div class="modal-header justify-content-center border-bottom-0 pt-4">
                        <h5 class="modal-title text-green fw-semibold text-center" id="exampleModalLabel">Do you want to Download Work Report<br>With <span class="text-blue">Employer Rating And Feedback</span></h5>
                    </div>
                    <div class="modal-body pb-0 px-4">
                        <div class="d-flex flex-wrap row">
                            <div class="col-md-12 form-group selectField">
                                <input type="hidden" name="internship_id_rating" id="internship_id_rating" value="">
                                <input type="hidden" name="rating_status" id="rating_status" value="">
                                <div class="d-flex justify-content-center mb-4">
                                    <div class="form-check me-4">

                                        <label class="form-check-label f-14" for="rating_yes">Yes</label>
                                        <input class="form-check-input" type="radio" id="rating_yes" name="rating_show" onclick="func_employee_rating(1)">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label f-14" for="rating_no">No its fine</label>
                                        <input class="form-check-input" type="radio" id="rating_no" name="rating_show" onclick="func_employee_rating(2)">
                                    </div>
                                </div>
                                <font style="color:#dd4b39;">
                                    <div id="rating_status_alert"></div>
                                </font>
                            </div>


                        </div>
                        <div class="modal-footer border-top-0 justify-content-center pt-0 pb-4 px-4">
                            <input type="submit" class="btn btn-prim" id="rating_submit" value="Download" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        function fun_reason_type(val) {
            $("#complete_type").val(val);
        }

        function fun_rating_value(val) {
            $('#rating_value').val(val);
        }
        $('#reject_reson').keyup(function() {
            if (this.value.length > 500) {
                return false;
            }
            $("#remainingC").html("Remaining Characters : " + (500 - this.value.length));
        });

        $('#complete_reason').keyup(function() {
            if (this.value.length > 500) {
                return false;
            }
            $("#remainingC1").html("Remaining Characters : " + (500 - this.value.length));
        });
        $('#under_cons_reson').keyup(function() {
            if (this.value.length > 500) {
                return false;
            }
            $("#remainingC2").html("Remaining Characters : " + (500 - this.value.length));
        });
        //accept internship hiring
        // function confirm_accept(id) {
        //     //check already confirmed

        //     var csrftokenname = "csrf_test_name=";
        //     var csrftokenhash = $(".csrf").val();

        //     $.ajax({
        //         type: "POST",
        //         url: "<?php echo base_url('Check-Already-Confirmed'); ?>",
        //         dataType: 'JSON',
        //         data: "internship_id=" + id + "&" + csrftokenname + csrftokenhash,
        //         success: function(resp) {

        //             $(".csrf").val(resp.csrf);

        //             if (resp.result == 0) {
        //                 swal({
        //                         title: "Are you sure?",
        //                         text: "You want to accept this offer!",
        //                         type: "warning",
        //                         showCancelButton: true,
        //                         confirmButtonClass: "btn-warning",
        //                         confirmButtonText: "ok",
        //                         closeOnConfirm: false
        //                     },
        //                     function() {
        //                         window.location.href = "<?php echo base_url('/Accept-Hiring'); ?>/1/" + id;

        //                     });
        //             } else {
        //                 swal({
        //                     title: "Alert",
        //                     text: "Cancel your accepted internship, to accept new internship offer",
        //                     type: "info",
        //                     showCancelButton: false,
        //                     confirmButtonClass: "btn-warning",
        //                     confirmButtonText: "ok",
        //                     closeOnConfirm: false
        //                 });
        //             }


        //         },
        //         error: function(e) {

        //             alert('Error: ' + e.responseText);
        //             return false;

        //         }
        //     });



        // }

        function confirm_accept(rowid, college_id, college_name, id) {
            //check already confirmed

            var csrftokenname = "csrf_test_name=";
            var csrftokenhash = $(".csrf").val();

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Check-Already-Confirmed'); ?>",
                dataType: 'JSON',
                data: "internship_id=" + id + "&" + csrftokenname + csrftokenhash,
                success: function(resp) {

                    $(".csrf").val(resp.csrf);

                    if (resp.result == 0) {
                        $('#facultyconfirm').modal('show');
                        // $('#facultyconfirm').hide();
                        $('#id').val(rowid);
                        $('#college_id').val(college_id);
                        $('#college_name').val(college_name);
                        $('#faculty_college').val(college_name);
                        $('#ca_type').val('1');
                        $('#fa_type').val('0');
                        $('#internship_id11').val(id);
                        // swal({
                        //         title: "Are you sure?",
                        //         text: "You want to accept this offer!",
                        //         type: "warning",
                        //         showCancelButton: true,
                        //         confirmButtonClass: "btn-warning",
                        //         confirmButtonText: "ok",
                        //         closeOnConfirm: false
                        //     },
                        //     function() {
                        //         window.location.href="<?php echo base_url('/Accept-Hiring'); ?>/2/"+id;

                        //     });
                    } else {
                        swal({

                            title: "Alert",
                            text: "Cancel your accepted intership, to accept new intership offer",
                            type: "info",
                            showCancelButton: false,
                            confirmButtonClass: "btn-warning",
                            confirmButtonText: "ok",
                            closeOnConfirm: false
                        });
                    }


                },
                error: function(e) {

                    alert('Error: ' + e.responseText);
                    return false;

                }
            });



        }
        //reject internship hiring
        function confirm_reject(id) {
            swal({
                    title: "Are you sure?",
                    text: "You want to reject this offer!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "ok",
                    closeOnConfirm: true
                },
                function() {
                    $("#reject_popup").modal('show');
                    $("#reject_id").val(id);
                    // window.location.href="<?php echo base_url('/Reject-Hiring'); ?>/"+id;

                });
        }

        function confirm_under_cons(id) {
            swal({
                    title: "Are you sure?",
                    text: "You want to under consideration this offer!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "ok",
                    closeOnConfirm: true
                },
                function() {
                    $("#under_cons_popup").modal('show');
                    $("#under_cons_id").val(id);
                });
        }

        function complete_internship(id, complete_reason, reason_type, rating_value) {
            // $("#complete_popup").modal('show');
            // alert(reason_type);
            $("#complete_id").val(id);
            $("#add_reason_type").val(reason_type);
            $("#rating_value").val(rating_value);
            $("#complete_reason").val(atob(complete_reason));

            $('#star' + rating_value).attr('checked', 'checked');

        }
        $(document).ready(function() {
            $('#example').DataTable();
        });
        $(document).ready(function() {
            $("#reject_submit").click(function() {
                var reject_reson = $("#reject_reson").val();
                if (reject_reson == '') {
                    $("#reject_reson_alert").html('Cancel Reason Is Mandatory');
                    //$("#reject_reson_alert").addClass('alertMsg'); 
                    return false;
                } else {
                    //special characters
                    var format = /[!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?]+/;
                    //check match with input value 
                    if (format.test(reject_reson)) {
                        $("#reject_reson_alert").html("special characters not allowed");
                        //$("#reject_reson_alert").addClass('alertMsg'); 
                        return false;
                    } else {
                        $("#reject_reson_alert").html('');
                        return true;
                    }

                }
            });
        });
        $(document).ready(function() {
            $("#reject_reson").keyup(function() {

                var reject_reson = $("#reject_reson").val();
                if (reject_reson == '') {
                    $("#reject_reson_alert").html('Cancel Reason Is Mandatory');
                    return false;
                } else {
                    //special characters
                    var format = /[!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?]+/;
                    //check match with input value 
                    if (format.test(reject_reson)) {
                        $("#reject_reson_alert").html("special characters not allowed");
                        //$("#reject_reson_alert").addClass('alertMsg'); 
                        return false;
                    } else {
                        $("#reject_reson_alert").html('');
                        return true;
                    }
                }
            });
        });

        $(document).ready(function() {
            $("#under_cons_reson").keyup(function() {

                var under_cons_reson = $("#under_cons_reson").val();
                if (under_cons_reson == '') {
                    $("#under_cons_reson_alert").html('Reason Is Mandatory');
                    return false;
                } else {
                    //special characters
                    var format = /[!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?]+/;
                    //check match with input value 
                    if (format.test(under_cons_reson)) {
                        $("#under_cons_reson_alert").html("special characters not allowed");
                        //$("#under_cons_reson_alert").addClass('alertMsg'); 
                        return false;
                    } else {
                        $("#under_cons_reson_alert").html('');
                        return true;
                    }
                }
            });
        });

        $(document).ready(function() {
            $("#under_cons_submit").click(function() {
                var under_cons_reson = $("#under_cons_reson").val();
                if (under_cons_reson == '') {
                    $("#under_cons_reson_alert").html('Reason Is Mandatory');
                    //$("#under_cons_reson_alert").addClass('alertMsg'); 
                    return false;
                } else {
                    //special characters
                    var format = /[!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?]+/;
                    //check match with input value 
                    if (format.test(under_cons_reson)) {
                        $("#under_cons_reson_alert").html("special characters not allowed");
                        //$("#under_cons_reson_alert").addClass('alertMsg'); 
                        return false;
                    } else {
                        $("#under_cons_reson_alert").html('');
                        return true;
                    }

                }
            });
        });

        $(document).ready(function() {
            // $("#add_reason_type").change(function() {
            //     validatetext_style('add_reason_type', 'add_reason_type_alert', 'Ratings');
            // });
            $("#complete_reason").keyup(function() {
                validate_description_special_not_required('complete_reason', 'complete_reason_alert');
            });
        });

        $(document).ready(function() {
            $("#complete_submit").click(function() {

                var complete_reason = validate_description_special_not_required('complete_reason', 'complete_reason_alert');
                var add_reason_type = validatetext_style('complete_type', 'add_reason_type_alert', 'Complete Reason');
                var rating_value = validatetext_style('rating_value', 'rating_value_alert', 'Ratings');

                if (complete_reason == 0 || rating_value == 0 || add_reason_type == 0) {
                    return false;
                }
            });
        });

        function my_application_list(val) {
            // alert(val);
            // $('#internship_duration').val();
            window.location.href = "<?= base_url(); ?>/my-applications/" + val;
        }
        $('[data-bs-toggle="tooltip"]').tooltip({
            trigger: 'hover'

        })
        $(document).mousedown(function(e) {
            $('.tooltip_hide').click(function() {
                $('[data-bs-toggle="tooltip"]').tooltip('hide');
            });
        });

        function previous() {
            window.history.go(-1);
        }


        function func_employee_type(val) {
            if (val == 1) {
                document.getElementById("faculty_data").style.display = "block";
            } else {
                document.getElementById("faculty_data").style.display = "none";
            }
            $('#faculty').val(val);
            $('#faculty_alert').html('');
        }

        function func_employee_rating(val) {
            $('#rating_status').val(val);

        }
        $(document).ready(function() {
            $("#faculty").change(function() {
                return validatetext('faculty', 'faculty_alert');
            });
            $("#faculty_name").keyup(function() {
                return validatextname_style('faculty_name', 'faculty_name_alert', 'Faculty Name');
            });
            $("#faculty_email").keyup(function() {
                return validemailid_style('faculty_email', 'faculty_email_alert', 'Faculty Email ID');
            });

            $("#can_reg_number").keyup(function() {
                return validatetext_all_spcl_char_style_decimal('can_reg_number', 'can_reg_number_alert', 'Your Registration Number');
            });


        });

        $(document).ready(function() {
            $("#faculty_submit").click(function() {
                var faculty = $("#faculty").val();
                if (faculty == 1) {
                    var can_reg_number = validatetext_all_spcl_char_style_decimal('can_reg_number', 'can_reg_number_alert', 'Your Registration Number');

                    var faculty_email = validemailid_style('faculty_email', 'faculty_email_alert', 'Faculty Email ID');
                    var faculty_name = validatextname_style('faculty_name', 'faculty_name_alert', 'Faculty Name');
                } else {
                    var can_reg_number = 1;

                    var faculty_email = 1;
                    var faculty_name = 1;
                }

                var faculty = validatetext('faculty', 'faculty_alert');

                if (can_reg_number == 0 || faculty_email == 0 || faculty_name == 0 || faculty == 0) {
                    return false;
                }
            });
        });



        function confirm_faculty_update(rowid, college_id, college_name, id, college_reg_number, faculty_name, faculty_email) {
            $('#faculty').val('1');

            $('#facultyconfirm').modal('show');
            // $('#facultyconfirm').hide();
            document.getElementById("faculty_data").style.display = "block";
            document.getElementById("faculty_yes").checked = true;
            document.getElementById("faculty_no").disabled = true;
            document.getElementById("faculty_name").disabled = true;
            document.getElementById("note_show").style.display = "block";
            $('#id').val(rowid);
            $('#college_id').val(college_id);
            $('#college_name').val(college_name);
            $('#faculty_college').val(college_name);
            $('#can_reg_number').val(college_reg_number);
            $('#faculty_name').val(faculty_name);
            $('#faculty_email').val(faculty_email);
            $('#faculty_email1').val(faculty_email);
            $('#ca_type').val('1');
            $('#fa_type').val('1');
            $('#internship_id11').val(id);


        }

        function emp_rating_download(id) {

            $('#emp_rating_confirm').modal('show');
            $('#internship_id_rating').val(id);


        }

        $(document).ready(function() {
            $("#rating_submit").click(function() {

                var rating_status = validatetext('rating_status', 'rating_status_alert');

                if (rating_status == 0) {
                    return false;
                } else {
                    $('#emp_rating_confirm').modal('hide');
                }
            });
        });
    </script>
    <script>
        var email = document.querySelector('.email'),
            auto = document.querySelector('.autosuffix'),

            popularEmails = ['gmail.com', 'outlook.com', 'yahoo.com', 'yahoo.co.in', 'reddif.com', 'hotmail.com'],

            itemSelected = 0,

            itemList = [];

        window.addEventListener('keyup', function() {

            if (window.event.keyCode === 40) { // Down
                if (itemSelected === (itemList.length - 1)) {
                    itemSelected = itemList.length - 1;
                } else {
                    itemSelected += 1;
                }
            }

            if (window.event.keyCode === 38) { // Up
                if (itemSelected === 0) {
                    return;
                } else {
                    itemSelected -= 1;
                }
            }

            if (window.event.keyCode === 13) { // Enter
                email.value = itemList[itemSelected].textContent;
                auto.innerHTML = '';
            }

            for (var i = 0; i < itemList.length; i++) { // For loop through all items and add selected class if needed
                if (itemList[i].classList.contains('selected')) {
                    itemList[i].classList.remove('selected');
                }
                if (itemSelected === i) {
                    itemList[i].classList.add('selected');
                }
            }

            console.log(itemSelected, itemList);
        });


        email.addEventListener('keyup', function() {
            auto.innerHTML = '';

            if (email.value.match('@')) { // If the input has a @ in it
                var afterAt = email.value.substring(email.value.indexOf('@') + 1, email.value.length);
                var popularEmailsSub = [];

                for (var l = 0; l < popularEmails.length; l++) {
                    popularEmailsSub.push(popularEmails[l].substring(0, afterAt.length))
                }

                if (afterAt == '') {
                    for (var i = 0; i < popularEmails.length; i++) {
                        auto.innerHTML += '<li>' + email.value + popularEmails[i] + '</li>';
                    }
                    itemList = document.querySelectorAll('.autosuffix li');
                    itemList[0].classList.add('selected');
                } else if (!(afterAt == '')) {
                    var matchedEmails = [];

                    for (var k = 0; k < popularEmails.length; k++) {
                        if (popularEmailsSub[k].match(afterAt)) {
                            matchedEmails.push(popularEmails[k]);
                        }
                    }

                    for (var i = 0; i < matchedEmails.length; i++) {
                        auto.innerHTML += '<li>' + email.value.substring(0, email.value.indexOf('@')) + '@' + matchedEmails[i] + '</li>';
                    }
                }

                var itemsList = document.querySelectorAll('.autosuffix li');

                for (var j = 0; j < itemsList.length; j++) {
                    itemsList[j].addEventListener('click', function() {
                        email.value = this.textContent;
                        auto.innerHTML = '';
                    });
                }
            }
        });

        function profile_email() {


            var faculty_email1 = $('#faculty_email1').val();
            var faculty_email = $('#faculty_email').val();
            // alert(faculty_email1);
            // alert(faculty_email);
            if (faculty_email1 != faculty_email) {
                // alert('not');
                document.getElementById("faculty_name").disabled = false;
                // $('#faculty_name').val('');
            } else {
                document.getElementById("faculty_name").disabled = true;
            }

        }

        function candidate_paid_request(){
            swal({
                            title: "Alert",
                            text: "Access to this feature is only available with our Intern+ or Intern premium package subscription.",
                            type: "info",
                            showCancelButton: false,
                            confirmButtonClass: "btn-warning",
                            confirmButtonText: "ok",
                            closeOnConfirm: false
                        });
        }
    </script>
</body>

</html>