<!DOCTYPE html>
<html>

<?php
$session = session();
//print_r($_SESSION);
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body style="overflow-y:hidden">

    <?php require_once(APPPATH . "Views/Common/header.php");

    use App\Models\Candidate_model;

    $Candidate_model = new Candidate_model();
    ?>

    <?php if (!empty($emp_id)) { ?>
        <section class="message-area">
            <div class="px-2 px-md-4">
                <div class="row">
                    <div class="col-12 p-0 px-md-2">
                        <div class="chat-area">
                            <!-- chatlist -->
                            <div class="chatlist">
                                <div class="modal-dialog-scrollable">
                                    <div class="modal-content p-md-1 p-3">
                                        <h3 class="fs-5 fw-semibold text-dark mb-3">Filter</h3>
                                        <select onchange="emp_search_chat(this.value)" class="form-control folder_id js-states selectSearch" id="candidate_name" name="candidate_name">
                                            <option value="">Search By Employer</option>
                                            <?php if (!empty($chat_employee)) {

                                                foreach ($chat_employee as $emp) {
                                                    $userid = $emp->sender_id;

                                                    $where = array('userid' => $userid);
                                                    $can_name = $Candidate_model->emp_names('userlogin', $where);
                                                    $username = $can_name->username;
                                            ?>
                                                    <option value="<?php echo $userid; ?>" <?php if (!empty($emp_id) && $emp_id == $userid) {
                                                                                                echo "selected";
                                                                                            } ?>><?php echo $username; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="Open-tab" data-bs-toggle="tab" data-bs-target="#Open" type="button" role="tab" aria-controls="Open" aria-selected="true">All Messages</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="Closed-tab" data-bs-toggle="tab" data-bs-target="#Closed" type="button" role="tab" aria-controls="Closed" aria-selected="false">Unread</button>
                                            </li>
                                        </ul>
                                        <div class="modal-body px-0 pt-2">

                                            <!-- chat-list -->
                                            <div class="chat-lists p-2 bg-white rounded-3">
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="Open" role="tabpanel" aria-labelledby="Open-tab">
                                                        <!-- chat-list -->
                                                        <div class="chat-list">
                                                            <?php if (!empty($emp_id)) {

                                                                $userid = $emp_id;
                                                                $userid1 = $emp_id;
                                                                $where = array('status' => '1', 'userid' => $userid);
                                                                $emp_name = $Candidate_model->emp_names('userlogin', $where);
                                                                $username = $emp_name->username;
                                                                $username1 = $emp_name->username;
                                                                if ($emp_name->usertype == '2') {
                                                                    $sub_admin_company_id = $userid;
                                                                } else {
                                                                    $where_sub_admin = array('userid' => $userid);
                                                                    $sub_admin_profile = $Candidate_model->fetch_table_row('emp_manage_admins', $where_sub_admin);
                                                                    $sub_admin_company_id = $sub_admin_profile->emp_user_id;
                                                                }
                                                                $where_com = array('userid' => $sub_admin_company_id);
                                                                $Company_data = $Candidate_model->fetch_table_row('profile_completion_form', $where_com);

                                                                $emp_company_name    = $Company_data->profile_company_name;
                                                                $emp_company_name1    = $Company_data->profile_company_name;

                                                                // print_r($sub_admin_company_id);exit;

                                                            ?>
                                                                <?php if (!empty($username)) {
                                                                    $firstStringCharacter = strtoupper(substr($username, 0, 1));
                                                                }
                                                                if (!empty($username1)) {
                                                                    $firstStringCharacter1 = strtoupper(substr($username1, 0, 1));
                                                                }
                                                                // $logoname1 = $firstStringCharacter;
                                                                ?>
                                                                <a id="<?= $userid ?>" title="<?= $emp_name->username; ?>" companyname="<?= $emp_company_name; ?>" logoname="<?= $firstStringCharacter; ?>" class="selectuser d-flex align-items-start position-relative px-2 py-3">
                                                                    <div class="flex-shrink-0 bg-white rounded-50 border-blue text-blue fw-bold fs-6 ms-0 position-relative">
                                                                        <span><?php echo $firstStringCharacter; ?></span>
                                                                        <!-- <span class="active"></span> -->
                                                                    </div>
                                                                    <div class="read flex-grow-1 ms-3">
                                                                        <div class="d-flex justify-content-between">
                                                                            <h3 class="text-dark fw-medium"><?= $emp_company_name; ?></h3>
                                                                            <span class="badge fw-normal  d-flex justify-content-center align-items-center align-self-start ms-2 count1" id="msg_status<?= $userid ?>">

                                                                            </span>
                                                                            <!-- <span class="chatOn text-muted f-11">10:30 pm</span> -->
                                                                        </div>
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <p class="f-13 mb-0"><?= $emp_name->username; ?></p>

                                                                        </div>
                                                                    </div>
                                                                </a>

                                                            <?php } ?>

                                                            <?php if (!empty($chat_employee)) {
                                                                foreach ($chat_employee as $employee) {
                                                                    $userid = $employee->sender_id;
                                                                    $where = array('status' => '1', 'userid' => $userid);
                                                                    $emp_name = $Candidate_model->emp_names('userlogin', $where);
                                                                    $username = $emp_name->username;
                                                                    if ($emp_name->usertype == '2') {
                                                                        $sub_admin_company_id = $userid;
                                                                    } else {
                                                                        $where_sub_admin = array('userid' => $userid);
                                                                        $sub_admin_profile = $Candidate_model->fetch_table_row('emp_manage_admins', $where_sub_admin);
                                                                        $sub_admin_company_id = $sub_admin_profile->emp_user_id;
                                                                    }
                                                                    $where_com = array('userid' => $sub_admin_company_id);
                                                                    $Company_data = $Candidate_model->fetch_table_row('profile_completion_form', $where_com);

                                                                    $emp_company_name    = $Company_data->profile_company_name;

                                                                    // print_r($sub_admin_company_id);exit;

                                                            ?>
                                                                    <?php if (!empty($username)) {
                                                                        $firstStringCharacter = strtoupper(substr($username, 0, 1));
                                                                    }
                                                                    // $logoname1 = $firstStringCharacter;
                                                                    if ($emp_id != $employee->sender_id) {
                                                                    ?>
                                                                        <a id="<?= $userid ?>" title="<?= $emp_name->username; ?>" companyname="<?= $emp_company_name; ?>" logoname="<?= $firstStringCharacter; ?>" class="selectuser d-flex align-items-start position-relative px-2 py-3">
                                                                            <div class="flex-shrink-0 bg-gray rounded-50 border-blue text-blue fw-bold fs-6 ms-0 position-relative">
                                                                                <span><?php echo $firstStringCharacter; ?></span>
                                                                                <!-- <span class="active"></span> -->
                                                                            </div>
                                                                            <div class="read flex-grow-1 ms-3">
                                                                                <div class="d-flex justify-content-between">
                                                                                    <h3 class="text-dark fw-medium"><?= $emp_company_name; ?></h3>
                                                                                    <span class="badge fw-normal  d-flex justify-content-center align-items-center align-self-start ms-2 count1" id="msg_status<?= $userid ?>">

                                                                                    </span>
                                                                                    <!-- <span class="chatOn text-muted f-11">10:30 pm</span> -->
                                                                                </div>
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <p class="f-13 mb-0"><?= $emp_name->username; ?></p>

                                                                                </div>
                                                                            </div>
                                                                        </a>

                                                            <?php }
                                                                }
                                                            } ?>


                                                        </div>
                                                        <!-- chat-list -->
                                                    </div>
                                                    <div class="tab-pane fade" id="Closed" role="tabpanel" aria-labelledby="Open-tab">

                                                    </div>
                                                </div>

                                            </div>
                                            <!-- chat-list -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- chatlist -->

                            <!-- chatbox -->
                            <div class="chatbox ps-md-4 ps-0" id="chatSection">
                                <input type="hidden" class="csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <div class="modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="msg-head">
                                            <div class="row">
                                                <div class="col-lg-8 col-md-7 col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 bg-gray rounded-50 text-blue fw-bold fs-6 ms-0">
                                                            <span id="logoname"></span>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h3 class="mb-0" id="companyname_txt"></h3>
                                                            <p class="mb-0" id="username_txt"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-5 col-6 d-flex align-items-center justify-content-end">
                                                    <ul class="moreoption mb-0">
                                                        <li class="navbar nav-item dropdown">
                                                            <a class="empInfo" href="#"><button class="btn p-0 corp_info_btn px-2 text-white pt-1"><img src="<?= base_url(); ?>/public/assets/img/corp_info_ico.svg" alt="Location" class="me-2 mb-1 " width="35">Info</button></a>
                                                            <a onclick="block_user('1');" class="ms-md-4 ms-0" href="#" id="block"><img src="<?= base_url(); ?>/public/assets/img/block_chat.svg" alt="Block" data-bs-toggle="tooltip" data-bs-placement="left" title="block this employer" class="me-2 mb-1 " width="22"></a>
                                                            <a onclick="block_user('2');" class="ms-md-4 ms-0" href="#" id="unblock"><img src="<?= base_url(); ?>/public/assets/img/unblock_chat.svg" alt="Block" data-bs-toggle="tooltip" data-bs-placement="left" title="Unblock this employer" class="me-2 mb-1 " width="22"></a>
                                                            <!-- <a class="nav-link dropdown-toggle px-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <img src="<?= base_url(); ?>/public/assets/img/h_menu.svg" alt="" width="20">
                                                            </a> -->
                                                            <!-- <ul class="dropdown-menu profileDrop border-0 animate slideIn"> -->
                                                            <!-- <li><a class="dropdown-item canInfo" href="#">Candidate Info</a></li> -->
                                                            <!-- <li><a class="dropdown-item empInfo" href="#">Corporate Info</a></li> -->
                                                            <!-- <li><a class="dropdown-item" href="#">Report</a></li> -->
                                                            <!-- <li><a class="dropdown-item" href="#">Block Chat</a></li> -->
                                                            <!-- </ul> -->
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="modal-body" id="content">

                                            <div id="dumppy"></div>
                                        </div>
                                        <div class="send-box position-relative px-3 py-2 text-center" id="block_box" style="display: none;">
                                            <p id="block_alert" class="mb-0"></p>
                                        </div>

                                        <div class="send-box position-relative p-3" id="chat_box">
                                            <div class="position-absolute attachBtn">
                                                <span class="label d-flex justify-content-center mx-1">
                                                    <img class="img-fluid" src="<?= base_url(); ?>/public/assets/img/attach_new.svg" alt="Attach" width="11">
                                                </span><input type="file" name="upload" id="upload" class="upload-box upload" placeholder="Upload File" aria-label="Upload File">
                                            </div>
                                            <div class="position-absolute bg-white p-0 attchement_rule">
                                                <ul class="list-disc mb-0 px-2">
                                                    <p class="mb-2">Please Upload .doc, .docx, .pdf, .png, .jpg, .jpeg, .tif, .tiff, .xls, .xlsx, .txt, .csv, .pptx, .ppt, .m4a, .mp3, .wav file formats with less than 5 MB only</p>
                                                </ul>
                                            </div>
                                            <!-- <i class="fa fa-info-circle showInfo ms-2" aria-hidden="true"></i> -->
                                            <span class="uploadFile"></span>
                                            <form method="post" id="chat_form" name="chat_form" class="d-flex align-items-center justify-content-between ps-4 ms-2">
                                                <input type="hidden" id="Sender_Name" value="<?= $_SESSION['usertype']; ?>">
                                                <input type="hidden" id="userId_txt" value="<?php $userid ?>">
                                                <input type="hidden" class="csrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                                <input maxlength="2000" type="text" class="form-control f-14 border-0 message" aria-label="message…" placeholder="Write message…" id="message" name="message">
                                                <button type="submit"><img src="<?= base_url(); ?>/public/assets/img/chat_send.svg" width="20"></button>
                                            </form>
                                        </div>
                                        <!-- <div class="assignedInfo bg-white p-3">
                                            <p class="mb-2">Human Resource is a sub-admin of your organization, you can add multiple HRs through the MANAGE ADMINS tab.</p>
                                            <h5 class="text-green mt-3 fw-semibold">Roles and Responsibilities</h5>
                                            <ul class="list-disc mb-0 ps-3">
                                                <li class="mb-2">HR can post an internship, chat with candidates, shortlist candidates, add/assign supervisors, verify log books and generate internship certificates for candidates.</li>
                                                <li class="mb-2">By default, HR is responsible for the internship they post.</li>
                                                <li>Only you're allowed to assign/reassign internships to other HRs.</li>
                                            </ul>
                                        </div> -->
                                        <div class="corporateInfo p-4 pt-3">
                                            <div class="d-flex justify-content-between align-items-center pt-0 pb-4">
                                                <h3 class="mb-0 fs-5">Corporate Info</h3>
                                                <a href="#" class="infoClose text-muted"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </div>
                                            <div id="profile"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- chatbox -->
                    </div>
                </div>
            </div>
            </div>
        </section>
    <?php } else { ?>
        <section class="container filterable d-flex justify-content-center align-items-center my-4">
            <div class="card justify-content-center align-items-center ill w-100 p-5 mb-5">
                <img src="<?= base_url(); ?>/public/assets/img/84745-message.gif" alt="No Message " width="400" class="img-fluid">
                <h3 class="text-blue my-2 fw-semibold fs-4 text-center">No Messages yet,</h3>
                <p>We will let you know when employer gets in touch</p>
                <!-- <p class="desWidth-50 text-center lh-base">Your Account Has Been Already Activated, Kindly Login To Your Internme Account</p> -->
                <!-- <a href="<?= base_url(); ?>/" class="btn btn-prim px-3">Back to home</a> -->
            </div>
        </section>
    <?php } ?>
    <!-- Modal - Accept Interview -->
    <div class="modal fade" id="accept" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered mw-630">
            <form action="" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                <div class="modal-content p-1">

                    <div class="modal-header border-bottom-0 mb-3">
                        <!-- <h5 class="modal-title text-blue" id="exampleModalLabel"><img src="<?= base_url(); ?>/public/assets/img/chat_schedule.svg" alt="" class="mb-1 me-2" width="19">Request For Reschedule Interview</h5> -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="receiver_id1" id="receiver_id1" class="form-control">
                    <input type="hidden" name="id1" id="id1" class="form-control">
                    <div class="modal-body py-0">
                        <div class="form-outline position-relative floatLabel pb-2">
                            <div class="input-group mb-4">
                                <span class="input-group-text align-items-start pt-3 bg-white border-right-0 pe-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/int_desc.svg" alt="Description" width="16">
                                </span>

                                <textarea maxlength="500" id="accept_description" name="accept_description" rows="4" class="form-control form-control-lg border-left-0 py-3 f-14" placeholder="Write description">Thanks for scheduling the interview. I will be available at the specified time.</textarea>

                            </div>


                            <font style="color:#dd4b39;">
                                <div id="accept_description_alert"></div>
                            </font>
                            <span id='remainingC1'></span>
                            <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg">Description <span style="color:#dd4b39;">*</span></label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-outlined-blue px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="accept_interview" class="btn btn-prim px-3">Submit</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <!-- Modal - Reject Interview -->
    <div class="modal fade" id="reject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered mw-630">
            <form action="" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                <div class="modal-content p-1">

                    <div class="modal-header border-bottom-0 mb-3">
                        <!-- <h5 class="modal-title text-blue" id="exampleModalLabel"><img src="<?= base_url(); ?>/public/assets/img/chat_schedule.svg" alt="" class="mb-1 me-2" width="19">Request For Reschedule Interview</h5> -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="receiver_id2" id="receiver_id2" class="form-control">
                    <input type="hidden" name="id2" id="id2" class="form-control">
                    <div class="modal-body py-0">
                        <div class="form-outline position-relative floatLabel pb-2">
                            <div class="input-group mb-4">
                                <span class="input-group-text align-items-start pt-3 bg-white border-right-0 pe-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/int_desc.svg" alt="Description" width="16">
                                </span>

                                <textarea maxlength="500" id="decline_description" name="decline_description" rows="4" class="form-control form-control-lg border-left-0 py-3 f-14" placeholder="Write description">Thank you for considering me for this opportunity. I sincerely appreciate you taking the time to interview me. However, I would like to withdraw my application.</textarea>

                            </div>


                            <font style="color:#dd4b39;">
                                <div id="decline_description_alert"></div>
                            </font>
                            <span id='remainingC2'></span>
                            <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg">Description <span style="color:#dd4b39;">*</span></label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-outlined-blue px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="decline_interview" class="btn btn-prim px-3">Submit</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <!-- Modal - ReSchedule Interview -->
    <div class="modal fade" id="reschedule" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered mw-630">
            <form action="" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                <div class="modal-content p-1">

                    <div class="modal-header border-bottom-0 mb-3">
                        <h5 class="modal-title text-blue" id="exampleModalLabel"><img src="<?= base_url(); ?>/public/assets/img/chat_schedule.svg" alt="" class="mb-1 me-2" width="19">Request For Reschedule Interview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="id3" id="id3" class="form-control">
                    <input type="hidden" name="receiver_id3" id="receiver_id3">
                    <div class="modal-body py-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-outline position-relative floatLabel">
                                    <div class="input-group mb-4">
                                        <span class="input-group-text bg-white border-right-0 pe-0 pt-3">
                                            <img src="<?= base_url(); ?>/public/assets/img/date.svg" alt="date" width="16">
                                        </span>
                                        <input min="<?php echo date("Y-m-d"); ?>" type="date" id="interview_date" name="interview_date" class="form-control form-control-lg border-left-0 pt-3 py-2 f-14" />
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="interview_date_alert"></div>
                                    </font>
                                    <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg">Date <span style="color:#dd4b39;">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline position-relative floatLabel">
                                    <div class="input-group mb-4">
                                        <span class="input-group-text bg-white border-right-0 pe-0 pt-3">
                                            <img src="<?= base_url(); ?>/public/assets/img/time.svg" alt="time" width="16">
                                        </span>
                                        <input type="time" id="interview_time" name="interview_time" class="form-control form-control-lg border-left-0 pt-3 py-2 f-14" />
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="interview_time_alert"></div>
                                    </font>
                                    <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg">Time <span style="color:#dd4b39;">*</span></label>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="form-outline position-relative floatLabel pb-2">
                            <div class="input-group mb-4">
                                <span class="input-group-text align-items-start pt-3 bg-white border-right-0 pe-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/int_desc.svg" alt="Description" width="16">
                                </span>

                                <textarea maxlength="500" id="interview_description" name="interview_description" rows="4" class="form-control form-control-lg border-left-0 py-3 f-14" placeholder="Write description">Thanks for scheduling the interview. I am unavailable on the specified date & time. Could you please reschedule this interview ?</textarea>

                            </div>
                            <font style="color:#dd4b39;">
                                <div id="interview_description_alert"></div>
                            </font>
                            <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg">Reason For Reschedule <span style="color:#dd4b39;">*</span></label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-outlined-blue px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="reschedule_interview" class="btn btn-prim px-3">Submit</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <!-- Modal - submit assignment -->
    <div class="modal fade" id="assignment_submit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-630">
            <form action="" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                <div class="modal-content p-1">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title text-blue" id="exampleModalLabel"><img src="<?= base_url(); ?>/public/assets/img/chat_assign1.svg" alt="" class="mb-1 me-1"> Submit Assignment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="id4" id="id4" class="form-control">
                    <input type="hidden" name="receiver_id4" id="receiver_id4">
                    <div class="modal-body">

                        <div class="form-outline position-relative floatLabel pb-2">
                            <div class="input-group mb-4">
                                <span class="input-group-text bg-white border-right-0 pt-3">
                                    <img src="<?= base_url(); ?>/public/assets/img/int_title.svg" alt="title" width="14">
                                </span>
                                <input disabled type="text" id="assignment_title" name="assignment_title" class="form-control border-left-0 pt-3 ps-0 py-2 f-14" placeholder="Enter Assignment Title">
                            </div>
                            <font style="color:#dd4b39;">
                                <div id="assignment_title_alert"></div>
                            </font>
                            <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg">Assignment Title </label>
                        </div>
                        <div class="form-outline position-relative floatLabel pb-2">
                            <div class="input-group mb-4">
                                <span class="input-group-text align-items-start pt-3 bg-white border-right-0 pe-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/assign_desc.svg" alt="Description" width="16">
                                </span>
                                <textarea maxlength="500" id="assignment_description" name="assignment_description" rows="6" class="form-control form-control-lg border-left-0 py-3 f-14" value=""></textarea>

                            </div>


                            <font style="color:#dd4b39;">
                                <div id="assignment_description_alert"></div>
                            </font>
                            <span id='remainingC'></span>

                            <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg">Add Notes </label>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-outline position-relative floatLabel pb-2">
                                    <input type="file" id="files" name="files" class="form-control form-control-lg py-2 f-14 upload-box chooseFile" style="display: none;" />
                                    <span class="stickHere"></span>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text bg-white border-right-0 pt-3">
                                            <img src="<?= base_url(); ?>/public/assets/img/assign_attachment.svg" alt="attachment" width="14">
                                        </span>
                                        <label for="files" class="form-control border-left-0 pt-3 py-2 f-14 ps-0" id="chooseFile">Choose File</label>
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="files_alert" class="min_h55"></div>
                                    </font>
                                    <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg">Attachment <span style="color:#dd4b39;">*</span></label>
                                </div>
                            </div>

                        </div>
                        <div class="form-outline position-relative floatLabel pb-2">
                            <div class="input-group mb-4">
                                <span class="input-group-text bg-white border-right-0 pt-3">
                                    <img src="<?= base_url(); ?>/public/assets/img/link.svg" alt="title" width="15">
                                </span>
                                <input type="text" name="assignment_link" id="assignment_link" class="form-control border-left-0 pt-3 py-2 ps-0 f-14" placeholder="Reference Link">
                            </div>
                            <font style="color:#dd4b39;">
                                <div id="assignment_link_alert"></div>
                            </font>
                            <label class="form-label position-absolute text-blue bg-white px-2" for="formControlLg"> Reference Link</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outlined-blue px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="submit_assignment" class="btn btn-prim px-3">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        jQuery(document).ready(function() {

            $(".chat-list a").click(function() {
                $(".chatbox").addClass('showbox');
                return false;
            });

            $(".chat-icon").click(function() {
                $(".chatbox").removeClass('showbox');
            });


            $('.canInfo').click(function() {
                $(".chatbox").addClass('showCan');
                $(".candidateInfo").show();
            });
            $('.empInfo').click(function() {
                $(".chatbox").addClass('showCan');
                $(".corporateInfo").show();
            });

            $('.infoClose').click(function() {
                $(".chatbox").removeClass('showCan');
                $(".candidateInfo").hide();
                $(".corporateInfo").hide();
            });

        });



        $("#files").change(function() {
            filename = this.files[0].name;
            console.log(filename);
        });
    </script>
    <script>
        $(function() {

            $('.message').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // sendTxtMessage($(this).val());
                }
            });

            $('.selectuser').click(function() {
                $(".selectuser").removeClass('selectChat');
                ScrollDown();
                ChatSection(1);
                var receiver_id = $(this).attr('id');
                var receiver_name = $(this).attr('title');
                var companyname = $(this).attr('companyname');
                var logoname = $(this).attr('logoname');
                $('#userId_txt').val(receiver_id);
                $('#username_txt').html(receiver_name);
                $('#companyname_txt').html(companyname);
                $('#logoname').html(logoname);
                $('#receiver_id1').val(receiver_id);
                $('#receiver_id2').val(receiver_id);
                $('#receiver_id3').val(receiver_id);
                $('#receiver_id4').val(receiver_id);

                GetChatHistory(receiver_id);
                Getcorporate_profile(receiver_id);
                var check_block_status1 = check_block_status(receiver_id);

                if (check_block_status1 != '4') {
                    document.getElementById("chat_box").style.display = "none";
                    if (check_block_status1 == '2') {
                        document.getElementById("block").style.display = "block";
                        document.getElementById("unblock").style.display = "none";
                        document.getElementById("block_box").style.display = "block";
                        $("#block_alert").html("You cannot send further messages as you have been blocked by the employer");
                    }
                    if (check_block_status1 == '3') {
                        document.getElementById("block").style.display = "none";
                        document.getElementById("unblock").style.display = "block";
                        document.getElementById("block_box").style.display = "block";
                        $("#block_alert").html('You have blocked this employer. Would you like to <a onclick="block_user(2);" class="text-blue"><b>unblock</b></a> to send message');
                    }


                } else {
                    document.getElementById("block_box").style.display = "none";
                    document.getElementById("block").style.display = "block";
                    document.getElementById("chat_box").style.display = "block";
                    document.getElementById("unblock").style.display = "none";
                }
                $("#msg_status" + receiver_id).removeClass('unreadCount');
                $('#msg_status' + receiver_id).html('');
                $("#" + receiver_id).addClass('selectChat');
            });
        });
        $(document).ready(function() {
            ScrollDown();
            ChatSection(1);
            <?php if (!empty($emp_id)) { ?>
                var receiver_id = '<?php echo $userid1; ?>';
                var receiver_name = '<?php echo $username1; ?>';
                var companyname = '<?php echo $emp_company_name1; ?>';
                var logoname = '<?php echo $firstStringCharacter1; ?>';
                $('#userId_txt').val(receiver_id);
                $('#username_txt').html(receiver_name);
                $('#companyname_txt').html(companyname);
                $('#logoname').html(logoname);
                $('#receiver_id1').val(receiver_id);
                $('#receiver_id2').val(receiver_id);
                $('#receiver_id3').val(receiver_id);
                $('#receiver_id4').val(receiver_id);
                GetChatHistory(receiver_id);
                Getcorporate_profile(receiver_id);
                var check_block_status1 = check_block_status(receiver_id);

                if (check_block_status1 != '4') {
                    document.getElementById("chat_box").style.display = "none";
                    if (check_block_status1 == '2') {
                        document.getElementById("block").style.display = "block";
                        document.getElementById("unblock").style.display = "none";
                        document.getElementById("block_box").style.display = "block";
                        $("#block_alert").html("You cannot send further messages as you have been blocked by the employer");
                    }
                    if (check_block_status1 == '3') {
                        document.getElementById("block").style.display = "none";
                        document.getElementById("unblock").style.display = "block";
                        document.getElementById("block_box").style.display = "block";
                        $("#block_alert").html('You have blocked this employer. Would you like to <a onclick="block_user(2);"  class="text-blue"><b>unblock</b></a> to send message');
                    }


                } else {
                    document.getElementById("block_box").style.display = "none";
                    document.getElementById("block").style.display = "block";
                    document.getElementById("chat_box").style.display = "block";
                    document.getElementById("unblock").style.display = "none";
                }
                $("#msg_status" + receiver_id).removeClass('unreadCount');
                $('#msg_status' + receiver_id).html('');
                $("#" + receiver_id).addClass('selectChat');
            <?php } ?>
        });

        function unread(receiver_id, receiver_name, logoname, companyname) {
            $(".selectuser").removeClass('selectChat');
            ScrollDown();
            ChatSection(1);
            $('#userId_txt').val(receiver_id);
            $('#username_txt').html(receiver_name);
            $('#companyname_txt').html(companyname);
            $('#logoname').html(logoname);
            $('#receiver_id1').val(receiver_id);
            $('#receiver_id2').val(receiver_id);
            $('#receiver_id3').val(receiver_id);
            $('#receiver_id4').val(receiver_id);

            GetChatHistory(receiver_id);
            Getcorporate_profile(receiver_id);
            $("#msg_status" + receiver_id).removeClass('unreadCount');
            $('#msg_status' + receiver_id).html('');
            $("#" + receiver_id).addClass('selectChat');


        }

        function ChatSection(status) {
            if (status == 0) {
                $('#chatSection :input').attr('disabled', true);
            } else {
                $('#chatSection :input').removeAttr('disabled');
            }
        }
        ChatSection(0);

        function validate_fileuploadall_style(fileName, alertarea, fieldname) {

            var contentid = document.getElementById(fileName);
            var forext = contentid.value;
            var ext = forext.substring(forext.lastIndexOf('.') + 1);
            if (contentid.value == "" || contentid.value == null) {
                contentid.focus();
                $("#" + alertarea).html("<i class='fa fa-info-circle' aria-hidden='true'></i> " + fieldname + " is Mandatory");
                $("#" + alertarea).addClass('alertMsg');
                return 0;
            } else {
                $("#" + alertarea).html(""); // ext == ".doc" || ext == ".pdf" || ext == ".rtf" || ext == ".tex" || ext == ".txt" || ext == "wpd" || ext == ".xls"
                if (ext == "doc" || ext == "DOC" || ext == "docx" || ext == "DOCX" || ext == "pdf" || ext == "PDF" || ext == "png" || ext == "PNG" || ext == "jpg" || ext == "JPG" || ext == "jpeg" || ext == "JPEG" || ext == "tif" || ext == "TIF" || ext == "tiff" || ext == "TIFF" || ext == "xls" || ext == "XLS" || ext == "xlsx" || ext == "XLSX" || ext == "txt" || ext == "TXT" || ext == "csv" || ext == "CSV" || ext == "pptx" || ext == "PPTX" || ext == "ppt" || ext == "PPT" || ext == "m4a" || ext == "M4A" || ext == "mp3" || ext == "MP3" || ext == "wav" || ext == "WAV") {
                    var size = contentid.files[0].size;
                    // alert(size);
                    if (size < 5000000) {
                        return true; // valid file extension
                    } else {
                        $("#" + alertarea).html("<i class='fa fa-info-circle' aria-hidden='true'></i> Please upload FIle less than 5 MB");
                        $("#" + alertarea).addClass('alertMsg');
                        return false;
                    }

                } else {
                    $("#" + alertarea).html("<i class='fa fa-info-circle' aria-hidden='true'></i> Please Upload .doc, .docx, .pdf, .png, .jpg, .jpeg, .tif, .tiff, .xls, .xlsx, .txt, .csv, .pptx, .ppt, .m4a, .mp3, .wav file formats only");
                    $("#" + alertarea).addClass('alertMsg');
                    return false;
                }
            }
        }

        function validate_fileuploadall_style1(fileName, alertarea, fieldname) {

            var contentid = document.getElementById(fileName);
            var forext = contentid.value;
            var ext = forext.substring(forext.lastIndexOf('.') + 1);
            if (contentid.value == "" || contentid.value == null) {
                contentid.focus();
                // $("#" + alertarea).html("<i class='fa fa-info-circle' aria-hidden='true'></i> " + fieldname + " is Mandatory");
                // $("#" + alertarea).addClass('alertMsg');
                // return 0;
            } else {
                $("#" + alertarea).html(""); // ext == ".doc" || ext == ".pdf" || ext == ".rtf" || ext == ".tex" || ext == ".txt" || ext == "wpd" || ext == ".xls"
                if (ext == "doc" || ext == "DOC" || ext == "docx" || ext == "DOCX" || ext == "pdf" || ext == "PDF" || ext == "png" || ext == "PNG" || ext == "jpg" || ext == "JPG" || ext == "jpeg" || ext == "JPEG" || ext == "tif" || ext == "TIF" || ext == "tiff" || ext == "TIFF" || ext == "xls" || ext == "XLS" || ext == "xlsx" || ext == "XLSX" || ext == "txt" || ext == "TXT" || ext == "csv" || ext == "CSV" || ext == "pptx" || ext == "PPTX" || ext == "ppt" || ext == "PPT" || ext == "m4a" || ext == "M4A" || ext == "mp3" || ext == "MP3" || ext == "wav" || ext == "WAV") {
                    var size = contentid.files[0].size;
                    // alert(size);
                    if (size < 5000000) {
                        return true; // valid file extension
                    } else {
                        swal("", "Please upload FIle less than 5 MB", "info");
                        // $("#" + alertarea).html("<i class='fa fa-info-circle' aria-hidden='true'></i> Please upload FIle less than 5 MB");
                        // $("#" + alertarea).addClass('alertMsg');
                        return false;
                    }

                } else {
                    swal("", "Please Upload .doc, .docx, .pdf, .png, .jpg, .jpeg, .tif, .tiff, .xls, .xlsx, .txt, .csv, .pptx, .ppt, .m4a, .mp3, .wav file formats only", "info");
                    // $("#" + alertarea).html("<i class='fa fa-info-circle' aria-hidden='true'></i> Please Upload .doc, .docx, .pdf, .png, .jpg, .jpeg, .tif, .tiff, .xls, .xlsx, .txt, .csv, .pptx, .ppt, .m4a, .mp3, .wav file formats only");
                    // $("#" + alertarea).addClass('alertMsg');
                    return false;
                }
            }
        }

        // FUNCTION FOR SENDING TEXT MESSAGES
        $('#chat_form').on('submit', function(event) {
            event.preventDefault();
            var form_data = $(this).serialize();
            if (form_data != '') {
                var message = $("#message").val();
                var upload = $("#upload").val();
                var file_data = document.getElementById('upload').files[0];
                // alert(file_data)
                if (message != '' || upload != '') {
                    var files1 = validate_fileuploadall_style1('upload', 'files_alert', 'Attachment');
                    if (files1 == 0) {
                        $('#upload').val('');
                        return false;
                    } else {
                        var receiver_id = $('#userId_txt').val();
                        var csrftokenname = "csrf_test_name=";
                        var csrf_val = $(".csrf").val();
                        var csrf = "&" + csrftokenname + csrf_val;
                        var form_data = new FormData();
                        form_data.append("files", file_data);
                        form_data.append("csrf_test_name", csrf_val);
                        form_data.append("receiver_id", receiver_id);
                        form_data.append("message", message);
                        $.ajax({
                            url: '<?php echo base_url() ?>/can-send-message',
                            type: 'POST',
                            data: form_data,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                //   alert(response);
                                var splitted_data = response.split('^');
                                $(".csrf").val(splitted_data[0])
                                if (splitted_data[1] == 1) {
                                    GetChatHistory(receiver_id);
                                }
                            }
                        });
                        ScrollDown();
                        $('.upload').val('');
                        $('.message').val('');
                        $('.uploadFile').html('');
                        $('.message').focus();
                    }
                } else {
                    $('#message').focus();
                }
            } else {
                $('#message').focus();
            }
        });
        // FUNCTION FOR SENDING TEXT MESSAGES


        function msg_status() {

            $.ajax({
                url: '<?= base_url() ?>/can-new-message-cheack',
                type: 'post',
                dataType: 'JSON',
                // data: csrf,
                success: function(response) {

                    $.each(response.data_msg, function(key, value) {

                        $("#msg_status" + value.sender_id).addClass('unreadCount');
                        $('#msg_status' + value.sender_id).html(value.msg_count);
                    });

                },
                error: function(jqXHR, status, err) {}
            });
        }
        // FUNCTION FOR CHECKING MESSAGE STATUS

        // FUNCTION FOR GETTING MESSAGES AND ATTACHMENTS
        function GetChatHistory(receiver_id1) {
            $.ajax({
                url: '<?= base_url() ?>/can-get-chat-history?receiver_id=' + receiver_id1,

                success: function(data) {
                    if (data) {
                        $('#dumppy').html(data);
                    } else {
                        $('#dumppy').html('');
                    }
                    // ScrollDown()
                },
                error: function(jqXHR, status, err) {}
            });
        }
        // FUNCTION FOR GETTING MESSAGES AND ATTACHMENTS

        setInterval(function() {
            var receiver_id = $('#userId_txt').val();
            if (receiver_id != '') {
                GetChatHistory(receiver_id);
            }
        }, 1500);

        setInterval(function() {
            //   setTimeout(() => {
            msg_status();
            get_unread_chat_user();
            //   });
        }, 4000);

        function get_unread_chat_user() {
            // alert(receiver_id1);
            $.ajax({
                url: '<?= base_url() ?>/get-unread-chat-user-can',

                success: function(data) {
                    // alert(data);
                    if (data) {
                        $('#Closed').html(data);
                    } else {
                        $('#Closed').html('');
                    }
                    // ScrollDown()
                },
                error: function(jqXHR, status, err) {}
            });
        }

        function ScrollDown() {
            var elmnt = document.getElementById("content");
            var h = elmnt.scrollHeight;
            $('#content').animate({
                scrollTop: h * 100
            }, 1000);
        }
        window.onload = ScrollDown();

        function Getcorporate_profile(receiver_id1) {
            // alert(receiver_id1);
            $.ajax({
                url: '<?= base_url() ?>/get-corporate-profile?receiver_id=' + receiver_id1,

                success: function(data) {
                    if (data) {
                        $('#profile').html(data);
                    } else {
                        $('#profile').html('');
                    }
                    // ScrollDown()
                },
                error: function(jqXHR, status, err) {}
            });
        }


        $('#accept_interview').click(function() {

            var accept_description = validatetext_style('accept_description', 'accept_description_alert', 'Description');


            if (accept_description == 0) {
                return false;
            } else {

                var receiver_id = $('#receiver_id1').val();
                var accept_description = $('#accept_description').val();
                var id = $('#id1').val();
                var csrf_val = $(".csrf").val();
                var csrf = "&csrf_test_name=" + csrf_val;
                var form_data = new FormData();



                form_data.append('receiver_id', receiver_id);
                form_data.append('accept_description', accept_description);
                form_data.append('id', id);
                form_data.append("csrf_test_name", csrf_val);

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url() ?>/accept-interview',
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // alert(response);
                        var splitted_data = response.split('^');
                        $(".csrf").val(splitted_data[0].trim());

                        GetChatHistory(receiver_id);
                    },
                    error: function(jqXHR, status, err) {
                        alert('Local error callback');
                    }
                });
                ScrollDown();
                $('#accept').modal('hide');
            }

        });
        $('#decline_interview').click(function() {

            var decline_description = validatetext_style('decline_description', 'decline_description_alert', 'Description');


            if (decline_description == 0) {
                return false;
            } else {

                var receiver_id = $('#receiver_id2').val();
                var decline_description = $('#decline_description').val();
                var id = $('#id2').val();
                var csrf_val = $(".csrf").val();
                var csrf = "&csrf_test_name=" + csrf_val;
                var form_data = new FormData();



                form_data.append('receiver_id', receiver_id);
                form_data.append('decline_description', decline_description);
                form_data.append('id', id);
                form_data.append("csrf_test_name", csrf_val);

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url() ?>/decline-interview',
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // alert(response);
                        var splitted_data = response.split('^');
                        $(".csrf").val(splitted_data[0].trim());

                        GetChatHistory(receiver_id);
                    },
                    error: function(jqXHR, status, err) {
                        alert('Local error callback');
                    }
                });
                ScrollDown();
                $('#reject').modal('hide');
            }

        });
        $('#reschedule_interview').click(function() {

            var interview_date = validatetext_style('interview_date', 'interview_date_alert', 'Date');
            var interview_time = validatetext_style('interview_time', 'interview_time_alert', 'Time');
            var interview_description = validatetext_style('interview_description', 'interview_description_alert', 'Description');
            if (interview_description == 0 || interview_date == 0 || interview_time == 0) {
                return false;
            } else {

                var receiver_id = $('#receiver_id2').val();
                var interview_date = $('#interview_date').val();
                var interview_time = $('#interview_time').val();
                var interview_description = $('#interview_description').val();
                var id = $('#id2').val();
                var csrf_val = $(".csrf").val();
                var csrf = "&csrf_test_name=" + csrf_val;
                var form_data = new FormData();



                form_data.append('receiver_id', receiver_id);
                form_data.append('interview_date', interview_date);
                form_data.append('interview_time', interview_time);
                form_data.append('interview_description', interview_description);
                form_data.append('id', id);
                form_data.append("csrf_test_name", csrf_val);

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url() ?>/reschedule-interview',
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // alert(response);
                        var splitted_data = response.split('^');
                        $(".csrf").val(splitted_data[0].trim());

                        GetChatHistory(receiver_id);
                    },
                    error: function(jqXHR, status, err) {
                        alert('Local error callback');
                    }
                });
                ScrollDown();
                $('#reschedule').modal('hide');
                $('#interview_date').val('');
                $('#interview_time').val('');
            }

        });

        function accept(val) {
            $('#id1').val(val);
            $('#id2').val(val);
            $('#id3').val(val);
        }

        function accept1(val, val1) {
            $('#id4').val(val);
            $('#assignment_title').val(val1);

        }
        $('#accept_description').keyup(function() {

            if (this.value.length > 500) {
                return false;
            }
            $("#remainingC1").html("Remaining Characters : " + (500 - this.value.length));
        });
        $('#decline_description').keyup(function() {

            if (this.value.length > 500) {
                return false;
            }
            $("#remainingC2").html("Remaining Characters : " + (500 - this.value.length));
        });
    </script>
    <script>
        $("#upload").on('change', function(e) {
            var labelVal = $(".title").text();
            var oldfileName = $(this).val();
            fileName = e.target.value.split('\\').pop();

            if (oldfileName == fileName) {
                return false;
            }
            var extension = fileName.split('.').pop();



            if (fileName) {
                if (fileName.length > 30) {
                    $(".uploadFile").text(fileName.slice(0, 25) + '...' + extension);
                } else {
                    $(".uploadFile").text(fileName);
                }
            } else {
                $(".filelabel .title").text(labelVal);
            }
        });

        function validate_url_style1(value, alertarea, fieldname) {
            var gstpattern = /(https?:\/\/(?:www\.&(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,})/gi;
            var alertarea = alertarea;
            var contentid = $("#" + value);
            if (contentid.val() == "" || contentid.val() == null) {
                // contentid.focus();
                // $("#" + alertarea).html("<i class='fa fa-info-circle' aria-hidden='true'></i> "+fieldname+" is Mandatory");
                // $("#"+alertarea).addClass('alertMsg');
                // return 0;
            } else if (!gstpattern.test(contentid.val())) {
                $("#" + alertarea).html("<i class='fa fa-info-circle' aria-hidden='true'></i> Enter a valid URL");
                $("#" + alertarea).addClass('alertMsg');
                return false;
            } else {
                $("#" + alertarea).html("");
                return true;
            }
        }
        // FUNCTION FOR SENDING Assignment
        $('#submit_assignment').click(function() {
            var assignment_link = validate_url_style1('assignment_link', 'assignment_link_alert', 'Link');
            var files = validate_fileuploadall_style('files', 'files_alert', 'Attachment');
            if (files == 0 || assignment_link == 0) {
                return false;
            } else {
                var file_data = document.getElementById('files').files[0];
                var receiver_id = $('#receiver_id4').val();
                var id = $('#id4').val();
                var assignment_title = $('#assignment_title').val();
                var assignment_description = $('#assignment_description').val();
                var assignment_link = $('#assignment_link').val();
                var csrf_val = $(".csrf").val();
                var csrf = "&csrf_test_name=" + csrf_val;
                var form_data = new FormData();

                form_data.append('files', file_data);
                form_data.append('assignment_title', assignment_title);
                form_data.append('assignment_description', assignment_description);
                form_data.append('receiver_id', receiver_id);
                form_data.append('id', id);
                form_data.append('assignment_link', assignment_link);
                form_data.append("csrf_test_name", csrf_val);

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url() ?>/submit-assignment',
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // alert(response);
                        var splitted_data = response.split('^');
                        $(".csrf").val(splitted_data[0].trim());

                        GetChatHistory(receiver_id);
                    },
                    error: function(jqXHR, status, err) {
                        alert('Local error callback');
                    }
                });
                ScrollDown();
                $('#assignment_submit').modal('hide');
                $('#files').val('');
                $('#assignment_title').val('');
                $('#assignment_description').val('');


            }
        });

        // FUNCTION FOR SENDING ATTACHMENTS


        function emp_search_chat(value) {


            // alert();
            if (value != '') {
                window.location.href = "<?php echo base_url('candidate-chat'); ?>/" + value;
            } else {

            }

        }
    </script>
    <script>
        $("#upload").on('change', function(e) {
            var labelVal = $(".title").text();
            var oldfileName = $(this).val();
            fileName = e.target.value.split('\\').pop();

            if (oldfileName == fileName) {
                return false;
            }
            var extension = fileName.split('.').pop();



            if (fileName) {
                if (fileName.length > 30) {
                    $(".uploadFile").text(fileName.slice(0, 25) + '...' + extension);
                } else {
                    $(".uploadFile").text(fileName);
                }
            } else {
                $(".filelabel .title").text(labelVal);
            }
        });


        $(".chooseFile").on('change', function(e) {
            var labelVal = $(".title").text();
            var oldfileName = $(this).val();
            fileName = e.target.value.split('\\').pop();

            if (oldfileName == fileName) {
                return false;
            }
            var extension = fileName.split('.').pop();



            if (fileName) {
                if (fileName.length > 20) {
                    $(".stickHere").text(fileName.slice(0, 15) + '...' + extension);
                } else {
                    $(".stickHere").text(fileName);
                }
            } else {
                $(".stickHere").text(labelVal);
            }
        });


        function block_user(val) {
            var receiver_id = $('#userId_txt').val();

            var csrf_val = $(".csrf").val();
            var csrf = "&csrf_test_name=" + csrf_val;
            // alert(csrf);
            $.ajax({
                url: "<?php echo base_url('update-block-user-can'); ?>",
                method: "POST",
                data: "&receiver_id=" + receiver_id + "&val=" + val + csrf,
                success: function(response) {
                    // alert(response);
                    var splitted_data = response.split('^');
                    $(".csrf").val(splitted_data[0])
                    if (splitted_data[1] == '1') {
                        var check_block_status1 = check_block_status(receiver_id);
                        //  alert(check_block_status1);
                        if (check_block_status1 != '4') {
                            document.getElementById("chat_box").style.display = "none";
                            if (check_block_status1 == '2') {
                                document.getElementById("block").style.display = "block";
                                document.getElementById("unblock").style.display = "none";
                                document.getElementById("block_box").style.display = "block";
                                $("#block_alert").html("You cannot send further messages as you have been blocked by the employer");
                            }
                            if (check_block_status1 == '3') {
                                document.getElementById("block").style.display = "none";
                                document.getElementById("unblock").style.display = "block";
                                document.getElementById("block_box").style.display = "block";
                                $("#block_alert").html('You have blocked this employer. Would you like to <a onclick="block_user(2);"  class="text-blue"><b>unblock</b></a> to send message');
                            }


                        } else {
                            document.getElementById("block_box").style.display = "none";
                            document.getElementById("block").style.display = "block";
                            document.getElementById("chat_box").style.display = "block";
                            document.getElementById("unblock").style.display = "none";
                        }
                    }
                },

            });
        }

        function check_block_status(receiver_id) {
            // alert();
            var csrf_val = $(".csrf").val();
            var csrf = "&csrf_test_name=" + csrf_val;
            // alert(csrf);
            var flag = '';
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('check_block_status1'); ?>",
                async: false,
                data: "&receiver_id=" + encodeURIComponent(receiver_id) + csrf,

                success: function(resp) {
                    var splitted_data = resp.split("^");
                    $(".csrf").val(splitted_data[0]);
                    if (splitted_data[1] != '') {
                        flag = splitted_data[1];
                    } else {
                        // flag = true;
                    }


                },

            })
            return flag;
        }
    </script>
</body>

</html>