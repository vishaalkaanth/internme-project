<!DOCTYPE html>
<html>

<?php
$session = session();
$userid = $session->get('userid');
// print_r($_SESSION);
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");

use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
$can_work_report_showing_limit = $session->get('can_work_report_showing_limit');
$where_can_pro = array('status' => '1', 'userid' => $userid);
$can_profile_details = $Candidate_model->fetch_table_row('can_personal_details', $where_can_pro);

?>

<body class="stickyFoot">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>
    <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>

    <section class="canLog pb-md-5 py-4">
        <div class="container">
            <div class="d-flex justify-content-end mb-3">
                <a class="text-blue backBtn" onclick="previous()"><i class="fa fa-long-arrow-left me-1" aria-hidden="true"></i> Back</a>
            </div>
            <div class="d-flex justify-content-between flex-wrap mb-2">
                <div class="mb-0">
                    <h3 class="titleUi text-blue fw-semibold position-relative mb-2"><img src="<?= base_url(); ?>/public/assets/img/icon_intern.svg" alt="Internship" class="me-2 mb-1" width="22"><span class="fw-medium">Internship</span><?php if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                                                                                                                                                                                                                                echo $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                echo $internship_details->other_profile;
                                                                                                                                                                                                                                            } ?></h3>
                    <?php
                    $where_empl = array('status' => '1', 'userid' => $internship_details->company_id);
                    $view_profile_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_empl);
                    if (isset($view_profile_details->profile_company_name)) { ?>
                        <h5 class=""><?php echo $view_profile_details->profile_company_name; ?></h5>
                    <?php  }
                    ?>
                </div>


                <?php if ($internship_details->internship_type == '1') {
                    $order_by_loc = array('ordercolumn' => 'g_location_name', 'ordertype' => 'asc');
                    $where_loc = array('status' => '1', 'internship_id' => $internship_details->internship_id);
                    $can_work_location = $Candidate_model->fetch_table_data_for_all('emp_worklocation_multiple', $where_loc, $order_by_loc);
                    $where = array('candidate_id' => $userid, 'internship_id' => $internship_details->internship_id);
                    $can_location = $Candidate_model->fetch_table_row('can_applied_internship', $where);
                    // print_r($can_location);
                    if (count($can_work_location) > '1') {
                        if (isset($can_location->work_location) && !empty($can_location->work_location)) {
                            $location_status = '2';
                        } else {
                            $location_status = '3';
                        }

                ?>
                        <!-- <div class="col-2 form-group">
                        <label class="form-label" > Work Location <span style="color:red;">*</span></label>
                        <select class="selectpicker filterby form-control align-self-end" name="internship_duration" id="internship_duration" onchange="my_internship_location(this.value)">
                            <option value="" >Select Location</option>
                            <?php
                            if (isset($can_work_location) && !empty($can_work_location)) {
                                foreach ($can_work_location as $location) { ?>
                            <option value="<?php echo $location->g_location_id; ?>" <?php if (isset($can_location->work_location) && !empty($can_location->work_location)) {
                                                                                        if ($can_location->work_location == $location->g_location_id) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                    } ?>><?php echo $location->g_location_name; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div> -->
                        <?php if (isset($can_location->work_location_name) && !empty($can_location->work_location_name)) { ?>
                            <div class="text-end">
                                <div class="d-flex">
                                    <img src="<?= base_url(); ?>/public/assets/img/location.svg" alt="Location" class="img-fluid me-2" width="12">
                                    <p class="text-dark fw-medium mb-0  lh-base f-14">Work Location</p>
                                </div>
                                <p class="text-blue fw-medium mb-0 lh-base fs-5"><?php echo $can_location->work_location_name; ?></p>
                            </div>
                        <?php }
                    } else {
                        $location_status = '1';
                        ?>
                        <div class="text-end">
                            <div class="d-flex">
                                <img src="<?= base_url(); ?>/public/assets/img/location.svg" alt="Location" class="img-fluid me-2" width="12">
                                <p class="text-dark fw-medium mb-0  lh-base f-14">Work Location</p>
                            </div>
                            <p class="text-blue fw-medium mb-0 lh-base fs-5"><?php echo $can_work_location[0]->g_location_name; ?></p>


                        </div>
                    <?php }
                } else {
                    $location_status = '1';
                    ?>
                    <div class="text-end">
                        <div class="d-flex">
                            <img src="<?= base_url(); ?>/public/assets/img/location.svg" alt="Location" class="img-fluid me-2" width="12">
                            <p class="text-dark fw-medium mb-0  lh-base f-14">Work Location</p>
                        </div>
                        <p class="text-blue fw-medium mb-0 lh-base fs-5">Work From Home</p>
                    </div>
                <?php } ?>
            </div>
            <input type="hidden" id="internship_complete_status" name="internship_complete_status" value="<?php echo  $internship_applied_list->complete_status; ?>" />
            <input type="hidden" id="candidate_userid" name="candidate_userid" value="<?php echo $userid; ?>" />


            <div class="card cardBg mb-2 p-4">
                <div class="canLogFeat d-flex flex-wrap align-items-center">
                    <div class="col-12 col-md-4 col-lg-2 mb-3 mb-lg-0">
                        <div class="logProfile d-flex">
                            <input type="hidden" name="inter_start_date" id="inter_start_date" value="" />
                            <img src="<?= base_url(); ?>/public/assets/img/log_from.svg" alt="From Date" class="me-2">
                            <div>
                                <h2 class="text-dark fs-5 fw-semibold mb-2"><?php if (isset($internship_details->internship_startdate)) {
                                                                                echo date("d-m-Y", strtotime($internship_details->internship_startdate));
                                                                            } ?></h2>
                                <h6 class="text-dark mb-0">From</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2 mb-3 mb-lg-0">
                        <div class="logProfile d-flex">
                            <img src="<?= base_url(); ?>/public/assets/img/log_duration.svg" alt="Duration" class="me-2">
                            <div>
                                <h2 class="text-dark fs-5 fw-semibold mb-2"><?php if (isset($internship_details->internship_duration)) {
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
                                                                                    } ?></h2>
                                <h6 class="text-dark mb-0">Duration</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-8 d-flex flex-wrap">
                        <div class="col-12 col-md-4 col-lg-4 mb-3 mb-lg-0">
                            <div class="logProfile d-flex">
                                <img src="<?= base_url(); ?>/public/assets/img/log_hr.svg" alt="HR" class="me-2">
                                <div>
                                    <h2 class="text-dark fs-5 fw-semibold mb-2"><?php if (isset($internship_details->assigned_to)) {
                                                                                    $where_hr = array('userid' => $internship_details->assigned_to);
                                                                                    $user_details_hr = $Candidate_model->fetch_table_row('userlogin', $where_hr);
                                                                                    echo $user_details_hr->name;
                                                                                } ?></h2>
                                    <h6 class="text-dark mb-0">HR</h6>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($internship_applied_list->emp_supervisor)) {
                            $where_sup = array('userid' => $internship_applied_list->emp_supervisor);
                            $user_details_sup = $Candidate_model->fetch_table_row('userlogin', $where_sup); ?>
                            <div class="col-12 col-md-4 col-lg-4 mb-3 mb-lg-0">
                                <div class="logProfile d-flex">
                                    <img src="<?= base_url(); ?>/public/assets/img/log_supervisor.svg" alt="Supervisor" class="me-2">
                                    <div>
                                        <h2 class="text-dark fs-5 fw-semibold mb-2"><?php echo $user_details_sup->name; ?></h2>
                                        <h6 class="text-dark mb-0">Supervisor</h6>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- <div class="col-12 col-md-4 col-lg-4 mb-3 mb-lg-0">
                        <div class="logProfile d-flex">
                            <img src="<?= base_url(); ?>/public/assets/img/log_clg.svg" alt="College Supervisor" class="me-2">
                            <div>
                                <h2 class="text-dark fs-5 fw-semibold mb-2">Mohammed Siraj</h2>
                                <h6 class="text-dark mb-0">College Supervisor</h6>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>

            <div class="row d-flex flex-column-reverse flex-lg-row flex-wrap pt-3">
                <div class="col-12 col-lg-8 workReport">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <h4 class="fs-4 fw-semibold mb-0"><img src="<?= base_url(); ?>/public/assets/img/log_workreport.svg" alt="Work Report" class="me-2" width="18"> Work Report</h4>
                        <div>
                            <?php
                            if (!empty($log_sheet_details)) { ?>
                                <a <?php if ($can_profile_details->payment_package_type > 0) { ?> href="<?= base_url(); ?>/log-work-report/<?php echo $internship_details->internship_id; ?> " <?php } else { ?> onclick="candidate_paid_request()" <?php } ?> class="text-blue me-3"><img src="<?= base_url(); ?>/public/assets/img/download_l.svg" alt="Download" class="me-2 mb-1" width="13">Download</a>
                            <?php } ?>

                        </div>
                    </div>
                    <?php
                    if (!empty($log_sheet_details)) { ?>
                        <div class="showFor d-flex flex-wrap justify-content-between align-items-center mb-2 py-1 px-2">
                            <p class="mb-0 text-muted">Showing <?php if (isset($page_start_id)) {
                                                                    if ($page_start_id == 0) {
                                                                        echo '1';
                                                                    } else {
                                                                        echo $page_start_id + 1;
                                                                    }
                                                                } ?> to <?php if (isset($page_start_id)) {
                                                                            if ($page_start_id == 0) {
                                                                                if (!empty($log_sheet_details)) {
                                                                                    echo count($log_sheet_details);
                                                                                } else {
                                                                                    echo $page_default_limit;
                                                                                }
                                                                            } else {
                                                                                if (!empty($log_sheet_details)) {
                                                                                    echo count($log_sheet_details) + $page_start_id;
                                                                                } else {
                                                                                    echo $page_default_limit;
                                                                                }
                                                                            }
                                                                        } ?> of <?php echo count($log_sheet_details_style); ?> Work Reports</p>
                            <label class="text-muted">Show
                                <select name="showing_count_result" id="showing_count_result" onchange="fun_showing_count_result(this.value)" class="selectpicker form-control bg-white border-0 f-14 w-auto mx-1">
                                    <option value="10">10</option>
                                    <option value="25" <?php if (isset($can_work_report_showing_limit)) {
                                                            if ($can_work_report_showing_limit == '25') {
                                                                echo 'selected';
                                                            }
                                                        } ?>>25</option>
                                    <option value="50" <?php if (isset($can_work_report_showing_limit)) {
                                                            if ($can_work_report_showing_limit == '50') {
                                                                echo 'selected';
                                                            }
                                                        } ?>>50</option>
                                    <option value="100" <?php if (isset($can_work_report_showing_limit)) {
                                                            if ($can_work_report_showing_limit == '100') {
                                                                echo 'selected';
                                                            }
                                                        } ?>>100</option>
                                </select> Work Reports</label>
                        </div>
                    <?php } ?>
                    <div class="card p-3 logScroll mb-3">

                        <!-- if no logs -->
                        <!-- <div class="noLogs text-center py-5">
                            <img src="<?= base_url(); ?>/public/assets/img/noLogs_illu.svg" alt="No Logs" width="400">
                        </div> -->
                        <!-- if no logs -->
                        <?php
                        if (!empty($log_sheet_details_style)) {
                            foreach ($log_sheet_details_style as $sheet_details) {
                                $date = date_create($sheet_details->log_date);
                                // echo date_format($date,"M d");
                                $sheet_year = date("Y", strtotime($sheet_details->log_date));
                                $sheet_month = date("m", strtotime($sheet_details->log_date));
                                $sheet_day = date("d", strtotime($sheet_details->log_date));
                                $int_sheet_date = $sheet_year . $sheet_month . (($sheet_year) + ($sheet_month) + ($sheet_day)); ?>
                                <input type="hidden" name="style_internship_status" id="st<?php echo  $int_sheet_date; ?>" class="style_status<?php echo  $int_sheet_date; ?> status_common" value="<?php echo  $sheet_details->approved_status; ?>" />
                                <input type="hidden" name="style_internship_date" class="style_date<?php echo  $int_sheet_date; ?> date_common" value="<?php echo  $int_sheet_date; ?>" />

                        <?php
                            }
                        }

                        ?>

                        <?php

                        if (!empty($log_sheet_details)) {
                            foreach ($log_sheet_details as $sheet_details) {
                                $date = date_create($sheet_details->log_date);
                                // // echo date_format($date,"M d");
                                // $sheet_year = date("Y", strtotime($sheet_details->log_date));
                                // $sheet_month = date("m", strtotime($sheet_details->log_date));
                                // $sheet_day = date("d", strtotime($sheet_details->log_date));
                                // $int_sheet_date = $sheet_year . $sheet_month . (($sheet_year) + ($sheet_month) + ($sheet_day));
                        ?>
                                <!-- <input type="hidden" name="style_internship_status" id="st<?php echo  $int_sheet_date; ?>" class="style_status<?php echo  $int_sheet_date; ?> status_common" value="<?php echo  $sheet_details->approved_status; ?>" />
                            <input type="hidden" name="style_internship_date" class="style_date<?php echo  $int_sheet_date; ?> date_common" value="<?php echo  $int_sheet_date; ?>" /> -->
                                <div class="logEntry d-flex flex-wrap border-bottom bdrHide pb-4 mb-4">
                                    <div class="col-12 col-md-2 logDate">
                                        <h5 class="text-blue text-md-center lh-base mb-2 mb-md-0"><?php echo date_format($date, "M d"); ?> <br><?php echo date_format($date, "Y"); ?></h5>
                                    </div>
                                    <div class="col-12 col-md-10 logCnt">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="logHour text-blue">
                                                <img src="<?= base_url(); ?>/public/assets/img/log_hour.svg" alt="Hour" class="me-2 mb-1" width="10">
                                                <?php
                                                if ($sheet_details->worked_hours == '1') {
                                                    echo $sheet_details->worked_hours . ' Hour';
                                                } else {
                                                    echo $sheet_details->worked_hours . ' Hours';
                                                }
                                                ?>
                                            </div>
                                            <?php if ($sheet_details->approved_status == '1') { ?>
                                                <div>
                                                    <?php if (isset($sheet_details->log_edit_user)) {
                                                        if ($sheet_details->log_edit_user != '') { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/info.svg" alt="" class="me-2" width="5" data-bs-toggle="tooltip" data-bs-placement="top" title="Edited by Employer">
                                                    <?php }
                                                    } ?>
                                                    <span class="badge badge-completed fw-normal me-0">Approved</span>


                                                </div>
                                            <?php } else { ?>
                                                <div>
                                                    <a href="" onclick="func_can_logsheet('<?php echo $sheet_details->id; ?>','<?php echo $sheet_details->log_date; ?>','<?php echo $sheet_details->worked_hours; ?>','<?php echo base64_encode($sheet_details->description); ?>')" type="button" class="text-blue edit me-2" data-bs-toggle="modal" data-bs-target="#editlog"><img src="<?= base_url(); ?>/public/assets/img/edit.svg" alt="Edit" class="" width="13"></a>
                                                    <?php if (isset($sheet_details->log_edit_user)) {
                                                        if ($sheet_details->log_edit_user != '') { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/info.svg" alt="" class="me-2" width="5" data-bs-toggle="tooltip" data-bs-placement="top" title="Edited by Employer">
                                                    <?php }
                                                    } ?>
                                                    <span class="badge badge-pending fw-normal me-0">Pending</span>


                                                </div>
                                            <?php } ?>
                                        </div>
                                        <p class="mb-0"><?php echo nl2br($sheet_details->description); ?></p>
                                    </div>
                                </div>

                            <?php }
                        } else { ?>

                            <div class="noLogs text-center py-5">
                                <img src="<?= base_url(); ?>/public/assets/img/noLogs_illu.svg" alt="No Logs" width="400">
                            </div>
                        <?php } ?>
                    </div>
                    <?php echo $pager_links; ?>
                </div>
                <div class="col-12 col-lg-4 addLog">
                    <h4 class="fs-4 fw-semibold mb-3"><img src="<?= base_url(); ?>/public/assets/img/select_cal.svg" alt="Calendar" class="me-2" width="18"> Select Date To Add Log</h4>
                    <ul class="list-unstyled d-flex flex-wrap ps-0 dateColorHint">
                        <li class="curDate me-4">Current Date</li>
                        <li class="appDate me-4">Approved</li>
                        <li class="penDate">Pending</li>
                    </ul>
                    <div id="container" class="calendar-container p-3 mb-4"></div>



                    <!-- <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?= base_url(); ?>/public/assets/img/dash_banner1.png" class="d-block w-100" alt="banner1">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url(); ?>/public/assets/img/dash_banner2.png" class="d-block w-100" alt="banner2">
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>



    <div class="modal fade" id="addlog" tabindex="-1" aria-labelledby="addlogLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">


            <div class="modal-content">
                <form action="<?= base_url(); ?>/add_log_sheet_details" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                    <input type="hidden" name="internship_id" id="internship_id" value="<?php echo $internship_details->internship_id ?>" />
                    <input type="hidden" name="company_id" id="company_id" value="<?php echo $internship_details->company_id ?>" />
                    <div class="modal-header justify-content-center border-bottom-0 pt-4">
                        <h5 class="modal-title text-green fw-semibold" id="addlogLabel">Log Sheet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-0 px-4">
                        <div class="d-flex flex-wrap row">
                            <?php if ($internship_details->internship_type == '1') {
                                $order_by_loc = array('ordercolumn' => 'g_location_name', 'ordertype' => 'asc');
                                $where_loc = array('status' => '1', 'internship_id' => $internship_details->internship_id);
                                $can_work_location = $Candidate_model->fetch_table_data_for_all('emp_worklocation_multiple', $where_loc, $order_by_loc);
                                $where = array('candidate_id' => $userid, 'internship_id' => $internship_details->internship_id);
                                $can_location = $Candidate_model->fetch_table_row('can_applied_internship', $where);

                                if (count($can_work_location) > '1') {
                                    if (isset($can_location->work_location) && !empty($can_location->work_location)) {
                                        $location_status = '2';
                                    } else {
                                        $location_status = '3';
                                    }

                            ?>
                                    <div class="col-md-12 form-group mb-4">
                                        <label class="form-label"> Work Location <span style="color:red;">*</span></label>
                                        <select class="selectpicker filledBox filterby form-control align-self-end" name="add_internship_location" id="add_internship_location" onchange="my_internship_location(this.value)">
                                            <option value="">Select Location</option>
                                            <?php
                                            if (isset($can_work_location) && !empty($can_work_location)) {
                                                foreach ($can_work_location as $location) { ?>
                                                    <option value="<?php echo $location->g_location_id; ?>" <?php if (isset($can_location->work_location) && !empty($can_location->work_location)) {
                                                                                                                if ($can_location->work_location == $location->g_location_id) {
                                                                                                                    echo 'selected';
                                                                                                                }
                                                                                                            } ?>><?php echo $location->g_location_name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>


                                    </div>
                                <?php } else {
                                    $location_status = '1';
                                ?>
                                    <div class="d-flex mb-4">
                                        <img src="<?= base_url(); ?>/public/assets/img/location.svg" alt="Location" class="img-fluid me-2" width="12">
                                        <p class="text-dark fw-medium mb-0  lh-base f-14">Work Location :</p>
                                        <p class="text-blue fw-medium mb-0 lh-base f-14 ms-3"><?php echo $can_work_location[0]->g_location_name; ?></p>


                                    </div>
                                <?php }
                            } else {
                                $location_status = '1';
                                ?>
                                <div class="d-flex mb-4">
                                    <img src="<?= base_url(); ?>/public/assets/img/location.svg" alt="Location" class="img-fluid me-2" width="12">
                                    <p class="text-dark fw-medium mb-0  lh-base f-14">Work Location :</p>
                                    <p class="text-blue fw-medium mb-0 lh-base f-14 ms-3">Work From Home</p>


                                </div>
                            <?php } ?>
                            <font style="color:#dd4b39;">
                                <div id="add_internship_duration_alert"></div>
                            </font>


                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Date <span style="color:red;">*</span></label>
                                <input type="date" id="add_log_date" name="add_log_date" class="form-control filledBox border-0 py-2 f-14 mb-4" placeholder="Select Date" min="<?php echo $internship_details->internship_startdate; ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                                <font style="color:#dd4b39;">
                                    <div id="add_log_date_alert"></div>
                                </font>
                            </div>
                            <div class="col-md-6 form-group selectField htOverflow-150">
                                <label for="" class="form-label">Work Hours <span style="color:red;">*</span></label>
                                <select id="add_worked_hours" name="add_worked_hours" class="selectpicker form-control filledBox border-0 ps-0 f-14 mb-4">
                                    <option value="">Select Work Hours</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                </select>
                                <font style="color:#dd4b39;">
                                    <div id="add_worked_hours_alert"></div>
                                </font>
                            </div>
                            <div class="col-md-12 form-group mb-4">
                                <label for="" class="form-label">Description <span style="color:red;">*</span></label>
                                <textarea maxlength="500" style="height: 170px;" id="add_log_description" name="add_log_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Your Task Details"></textarea>
                                <span id="remainingC"></span>

                            </div>
                            <font style="color:#dd4b39;">
                                <div id="add_log_description_alert"></div>
                            </font>

                        </div>
                    </div>
                    <div class="modal-footer border-top-0 justify-content-center pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-prim" id="add_log_submit" value="Submit">
                    </div>
                </form>
            </div>

        </div>

    </div>

    <div class="modal fade" id="editlog" tabindex="-1" aria-labelledby="editlogLabel" aria-hidden="true" style="">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url(); ?>/edit_log_sheet_details" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                    <input type="hidden" name="edit_internship_id" id="edit_internship_id" value="<?php echo $internship_details->internship_id ?>" />
                    <input type="hidden" name="edit_id" id="edit_id" value="" />
                    <div class="modal-header justify-content-center border-bottom-0 pt-4">
                        <h5 class="modal-title text-green fw-semibold" id="editlogLabel">Log Sheet</h5>
                    </div>
                    <div class="modal-body pb-0 px-4">
                        <div class="d-flex flex-wrap row">
                            <?php if ($internship_details->internship_type == '1') {
                                $order_by_loc = array('ordercolumn' => 'g_location_name', 'ordertype' => 'asc');
                                $where_loc = array('status' => '1', 'internship_id' => $internship_details->internship_id);
                                $can_work_location = $Candidate_model->fetch_table_data_for_all('emp_worklocation_multiple', $where_loc, $order_by_loc);
                                $where = array('candidate_id' => $userid, 'internship_id' => $internship_details->internship_id);
                                $can_location = $Candidate_model->fetch_table_row('can_applied_internship', $where);

                                if (count($can_work_location) > '1') {
                                    if (isset($can_location->work_location) && !empty($can_location->work_location)) {
                                        $location_status = '2';
                                    } else {
                                        $location_status = '3';
                                    }

                            ?>
                                    <div class="col-md-12 form-group mb-4">
                                        <label class="form-label"> Work Location <span style="color:red;">*</span></label>
                                        <select class="selectpicker filledBox filterby form-control align-self-end" name="edit_internship_location" id="edit_internship_location" onchange="my_internship_location(this.value)">
                                            <option value="">Select Location</option>
                                            <?php
                                            if (isset($can_work_location) && !empty($can_work_location)) {
                                                foreach ($can_work_location as $location) { ?>
                                                    <option value="<?php echo $location->g_location_id; ?>" <?php if (isset($can_location->work_location) && !empty($can_location->work_location)) {
                                                                                                                if ($can_location->work_location == $location->g_location_id) {
                                                                                                                    echo 'selected';
                                                                                                                }
                                                                                                            } ?>><?php echo $location->g_location_name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>


                                    </div>
                                <?php } else {
                                    $location_status = '1';
                                ?>
                                    <div class="d-flex mb-4">
                                        <img src="<?= base_url(); ?>/public/assets/img/location.svg" alt="Location" class="img-fluid me-2" width="12">
                                        <p class="text-dark fw-medium mb-0  lh-base f-14">Work Location :</p>
                                        <p class="text-blue fw-medium mb-0 lh-base f-14 ms-3"><?php echo $can_work_location[0]->g_location_name; ?></p>


                                    </div>
                                <?php }
                            } else {
                                $location_status = '1';
                                ?>
                                <div class="d-flex mb-4">
                                    <img src="<?= base_url(); ?>/public/assets/img/location.svg" alt="Location" class="img-fluid me-2" width="12">
                                    <p class="text-dark fw-medium mb-0  lh-base f-14">Work Location : </p>
                                    <p class="text-blue fw-medium mb-0 lh-base f-14 ms-3">Work From Home</p>
                                </div>



                            <?php } ?>
                            <font style="color:#dd4b39;">
                                <div id="edit_internship_duration_alert"></div>
                            </font>
                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Date <span style="color:red;">*</span></label>
                                <input type="date" id="edit_log_date" name="edit_log_date" class="form-control filledBox border-0 py-2 f-14 mb-4" placeholder="Select Date" min="<?php echo $internship_details->internship_startdate; ?>" max="<?php echo date('Y-m-d'); ?>">
                                <font style="color:#dd4b39;">
                                    <div id="edit_log_date_alert"></div>
                                </font>
                            </div>
                            <div class="col-md-6 form-group selectField  htOverflow-150">
                                <label for="" class="form-label">Work Hours <span style="color:red;">*</span></label>
                                <select id="edit_worked_hours" name="edit_worked_hours" class="filledBox form-control  f-14 border-0 mb-4">
                                    <option value="">Select Work Hours</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                </select>
                                <font style="color:#dd4b39;">
                                    <div id="edit_worked_hours_alert"></div>
                                </font>
                            </div>
                            <div class="col-md-12 form-group mb-4">
                                <label for="" class="form-label">Description <span style="color:red;">*</span></label>
                                <textarea maxlength="500" style="height: 170px;" id="edit_log_description" name="edit_log_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Your Task Details"></textarea>
                                <span id="remainingC_1"></span>

                            </div>
                            <font style="color:#dd4b39;">
                                <div id="edit_log_description_alert"></div>
                            </font>

                        </div>
                    </div>
                    <div class="modal-footer border-top-0 justify-content-center pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-prim" id="edit_log_submit" value="Submit">
                    </div>
                </form>
            </div>

        </div>
    </div>
    <input type="hidden" id="location_status" name="location_status" value="<?php echo $location_status; ?>" />


    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        function previous() {
            window.history.go(-1);
        }
        $(document).ready(function() {
            <?php if (isset($internship_details->internship_startdate)) { ?>
                var internship_start_date = '<?php echo date("Y-m-d", strtotime($internship_details->internship_startdate)); ?>';
                var year = '<?php echo date("Y", strtotime($internship_details->internship_startdate)); ?>';
                var month = '<?php echo date("m", strtotime($internship_details->internship_startdate)); ?>';
                var day = '<?php echo date("d", strtotime($internship_details->internship_startdate)); ?>';
            <?php } ?>
            var int_start_date = year + month + (parseFloat(year) + parseFloat(month) + parseFloat(day));
            $('#inter_start_date').val(int_start_date);

            //    alert(int_start_date);

        });



        var current_month_year = '<?php echo date("F Y"); ?>';
        // alert(current_month_year);
        function func_can_logsheet(edit_id, log_date, worked_hours, description) {
            // alert(log_date);
            $('#edit_id').val(edit_id);
            $('#edit_log_date').val(log_date);
            $('#edit_worked_hours').val(worked_hours);
            $('#edit_log_description').val(atob(description));
            // $('#edit_education_start_year').val(start_year);atob()
        }

        // function func_close() {
        //     $("#addlog").hide();
        //     $("#addlog").removeClass("show");
        // }
        var $calendar;
        $(document).ready(function() {
            let container = $("#container").simpleCalendar({
                fixedStartDay: 0, // begin weeks by sunday
                disableEmptyDetails: true,
                events: [
                    // generate new event for yesterday at noon
                    {
                        startDate: new Date(new Date().setHours(new Date().getHours() - new Date().getHours() - 12, 0)).toISOString(),
                        endDate: new Date(new Date().setHours(new Date().getHours() - new Date().getHours() - 11)).getTime(),
                        summary: 'Restaurant'
                    },
                    // generate new event for the last two days
                    {
                        startDate: new Date(new Date().setHours(new Date().getHours() - 48)).toISOString(),
                        endDate: new Date(new Date().setHours(new Date().getHours() - 24)).getTime(),
                        summary: 'Visit of the Louvre'
                    }
                ],

            });
            $calendar = container.data('plugin_simpleCalendar')
        });

        $('#add_log_description').keyup(function() {

            if (this.value.length > 500) {
                return false;
            }
            $("#remainingC").html("Remaining Characters : " + (500 - this.value.length));

        });
        $('#edit_log_description').keyup(function() {

            if (this.value.length > 500) {
                return false;
            }
            $("#remainingC_1").html("Remaining Characters : " + (500 - this.value.length));

        });

        $(document).ready(function() {
            $("#add_log_description").keyup(function() {
                return validatetext_style('add_log_description', 'add_log_description_alert', 'Description');
            });
            $("#add_worked_hours").change(function() {
                return validatetext_style('add_worked_hours', 'add_worked_hours_alert', 'Work Hours');
            });
            $("#add_log_date").change(function() {
                return validatetext_style('add_log_date', 'add_log_date_alert', 'Log Date');
            });
            $("#add_internship_location").change(function() {
                return validatetext_style('add_internship_location', 'add_internship_duration_alert', 'Work Location');
            });
        });

        $(document).ready(function() {
            $("#add_log_submit").click(function() {

                var add_log_description = validatetext_style('add_log_description', 'add_log_description_alert', 'Description');
                var add_worked_hours = validatetext_style('add_worked_hours', 'add_worked_hours_alert', 'Work Hours');
                var add_log_date = validatetext_style('add_log_date', 'add_log_date_alert', 'Log Date');
                var location_status = $('#location_status').val();

                if (location_status == 3) {
                    var internship_duration = validatetext_style('add_internship_location', 'add_internship_duration_alert', 'Work Location');
                } else {
                    var internship_duration = 1;
                }

                if (internship_duration == 0 || add_log_date == 0 || add_worked_hours == 0 || add_log_description == 0) {
                    return false;
                }
            });
        });

        $(document).ready(function() {
            $("#edit_log_description").keyup(function() {
                return validatetext_style('edit_log_description', 'edit_log_description_alert', 'Description');
            });
            $("#edit_worked_hours").change(function() {
                return validatetext_style('edit_worked_hours', 'edit_worked_hours_alert', 'Work Hours');
            });
            $("#edit_log_date").change(function() {
                return validatetext_style('edit_log_date', 'edit_log_date_alert', 'Log Date');
            });
            $("#edit_internship_location").change(function() {
                return validatetext_style('edit_internship_location', 'edit_internship_duration_alert', 'Work Location');
            });
        });

        $(document).ready(function() {
            $("#edit_log_submit").click(function() {

                var edit_log_description = validatetext_style('edit_log_description', 'edit_log_description_alert', 'Description');
                var edit_worked_hours = validatetext_style('edit_worked_hours', 'edit_worked_hours_alert', 'Work Hours');
                var edit_log_date = validatetext_style('edit_log_date', 'edit_log_date_alert', 'Log Date');
                var location_status = $('#location_status').val();
                if (location_status == 3) {
                    var edit_internship_duration = validatetext_style('edit_internship_location', 'edit_internship_duration_alert', 'Work Location');
                } else {
                    var edit_internship_duration = 1;
                }

                if (edit_internship_duration == 0 || edit_log_date == 0 || edit_worked_hours == 0 || edit_log_description == 0) {
                    return false;
                }
            });
        });

        function fun_get_click_date(date) {
            <?php if (!empty($log_sheet_details)) {
                foreach ($log_sheet_details as $sheet_details) {
                    $date = date_create($sheet_details->log_date); ?>
                    var sheet_year = '<?php echo date("Y", strtotime($sheet_details->log_date)); ?>';
                    var sheet_month = '<?php echo date("m", strtotime($sheet_details->log_date)); ?>';
                    var sheet_day = '<?php echo date("d", strtotime($sheet_details->log_date)); ?>';

                    int_sheet_date = sheet_year + sheet_month + (parseFloat(sheet_year) + parseFloat(sheet_month) + parseFloat(sheet_day));
                    // alert(int_sheet_date);
                    <?php if ($sheet_details->approved_status == 1) { ?>
                        // $('.'+int_sheet_date).css("background-color", "#54C16F");
                        // $('.'+int_sheet_date).prop("disabled", true);
                    <?php } elseif ($sheet_details->approved_status == 0) { ?>
                        // $('.'+int_sheet_date).css("background-color", "#F7A032");
                        // $('.'+int_sheet_date).prop("disabled", true);
                    <?php } else { ?>
                        // $('#'+int_sheet_date).css("background-color", "#CBD1D2"); 
                    <?php } ?>
            <?php  }
            }
            ?>
            var click_date = moment(date).format('YYYY-MM-DD');
            $('#add_log_date').val(click_date);
            $("#add_log_date").attr('readonly', 'readonly');
            <?php if (isset($internship_details->internship_startdate)) { ?>
                var internship_start_date = '<?php echo date("Y-m-d", strtotime($internship_details->internship_startdate)); ?>';
                var year = '<?php echo date("Y", strtotime($internship_details->internship_startdate)); ?>';
                var month = '<?php echo date("m", strtotime($internship_details->internship_startdate)); ?>';
                var day = '<?php echo date("d", strtotime($internship_details->internship_startdate)); ?>';
            <?php } ?>
            //    int_start_date=year+month+(parseFloat(year)+parseFloat(month)+parseFloat(day))-1;

            //    for (var i = int_start_date; i >2022112000 ; i--) {
            //          $('#'+i).prop("disabled", true);
            //         }

        }

        function fun_showing_count_result(value) {
            var internship_id = $('#internship_id').val();
            window.location.href = "<?php echo base_url('can_work_report_showing'); ?>/" + value + "/" + internship_id;
        }

        function my_internship_location(value) {
            var internship_id = $('#internship_id').val();
            var candidate_userid = $('#candidate_userid').val();

            // alert(value);
            var csrftokenname = "csrf_test_name=";
            var csrftokenhash = $(".csrf").val();
            if (value != '') {
                var location_status = $('#location_status').val(1);
                // $.ajax({
                //     type: "POST",
                //     url: "<?php echo base_url('update_candidate_work_location'); ?>",
                //     data: "&location_id=" + value+"&internship_id=" + internship_id+"&candidate_userid=" + candidate_userid+ "&" + csrftokenname + csrftokenhash,
                //     success: function(resp) {
                //             location.reload();
                //     },

                // });
            } else {
                var location_status = $('#location_status').val(3);
            }
        }

        function candidate_paid_request() {
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