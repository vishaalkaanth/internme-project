<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>
<style>
    .swal-wide {
        width: 850px !important;
    }
</style>

<body class="stickyFoot">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>

    <?php

    use App\Models\Candidate_model;

    $session = session();
    $Candidate_model = new Candidate_model();
    $userid    =    $session->get('userid');
    $ses_data = [
        'profile_page_view' => '1'
    ];
    $session->set($ses_data);
    //   print_r($_SESSION);


    ?>
    <!-- <div class="offcanvas offcanvas-end <?php if (isset($complete_popup)) {
                                                    echo 'show';
                                                } ?>" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="false">
        <div class="offcanvas-body text-center d-flex justify-content-center align-items-center flex-column">
            <img src="<?= base_url(); ?>/public/assets/img/std_otp.gif" alt="" width="100" class="mb-4">
            <h2 class="fw-bold text-blue fs-4 mb-4" id="offcanvasRightLabel">Your application has been submitted</h5>
                <p class="text-gray mb-4">You can track its status on your dashboard</p>
                <a href="<?= base_url(); ?>/my-applications" class="btn btn-prim">Done</a>
        </div>
    </div> -->
    <!----- Form ------>
    <section class="container my-4">
        <div class="d-flex flex-wrap row">
            <div class="col-12 col-lg-4">
                <div class="card mb-4">
                    <div class="profShort d-flex flex-column align-items-center text-center p-3">
                        <div class="profPic mb-3 d-flex justify-content-center align-items-center">
                            <!-- <span class="text-white fw-bold fs-4">A</span> -->
                            <?php if (!empty($profile_personal->profile_photo)) { ?>
                                <img src="<?= base_url(); ?>/public/assets/docs/uploads/can_profile_photo/<?php echo $profile_personal->profile_photo; ?>" alt="Profile" class="img-fluid object-fit-cover">
                            <?php } else {
                                echo '<span class="text-white fw-bold fs-2">' . $firstStringCharacter = substr($session->get('name'), 0, 1) . '</span>';
                            } ?>

                        </div>
                        <h3 class="text-dark fs-5 fw-semibold mb-2"><?php if (!empty($profile_personal->profile_full_name)) {
                                                                        echo ucfirst($profile_personal->profile_full_name);
                                                                    } ?></h3>
                        <h6 class="text-muted overflow-anywhere"><?php if (!empty($education_details[0]->education_college_name) && $education_details[0]->education_college_name != 0) {
                                                                        echo ucfirst($Candidate_model->get_master_name('master_college', $education_details[0]->education_college_name, 'college_name'));
                                                                    } else {
                                                                        echo ucfirst($education_details[0]->education_college_name_other);
                                                                    } ?></h6>
                    </div>
                    <div class="border-top p-3">
                        <div class="d-flex justify-content-between flex-wrap">
                            <h3 class="text-dark fs-5 fw-medium mb-4">Personal Details</h3>
                            <a href="<?= base_url(); ?>/personal-details" class="text-blue edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                        </div>
                        <ul class="ps-0 list-unstyled">
                            <?php if (!empty($profile_personal->profile_email)) { ?>
                                <li class="d-flex mb-3">
                                    <img src="<?= base_url(); ?>/public/assets/img/email.svg" alt="Email ID" class="align-self-start me-3">
                                    <div>
                                        <p class="text-gray1 mb-0">Email ID</p>
                                        <p class="text-dark mb-0"><?php echo $profile_personal->profile_email ?></p>
                                    </div>
                                </li>
                            <?php } ?>
                            <li class="d-flex mb-3">
                                <img src="<?= base_url(); ?>/public/assets/img/phone1.svg" alt="Phone" class="align-self-start me-3">
                                <div>
                                    <p class="text-gray1 mb-0">Phone</p>
                                    <p class="text-dark mb-0"><?php if (!empty($profile_personal->profile_phone_number)) {
                                                                    echo $profile_personal->profile_phone_number;
                                                                } ?></p>
                                </div>
                            </li>
                            <?php if (!empty($profile_personal->profile_gender)) { ?>
                                <li class="d-flex mb-3">
                                    <img src="<?= base_url(); ?>/public/assets/img/gender.svg" alt="gender" class="align-self-start me-3">
                                    <div>
                                        <p class="text-gray1 mb-0">Gender</p>
                                        <p class="text-dark mb-0"><?php
                                                                    echo $Candidate_model->get_master_name('master_gender', $profile_personal->profile_gender, 'gender_type');
                                                                    ?></p>
                                    </div>
                                </li>
                            <?php } ?>
                            <li class="d-flex mb-3">
                                <img src="<?= base_url(); ?>/public/assets/img/address.svg" alt="location" class="align-self-start me-3">
                                <div>
                                    <p class="text-gray1 mb-0">Current Location</p>
                                    <p class="text-dark mb-0"><?php if (!empty($profile_personal->g_location_name)) {
                                                                    echo ucfirst($profile_personal->g_location_name);
                                                                } ?></p>
                                </div>
                            </li>
                            <!-- <li class="d-flex mb-3">
                                <img src="<?= base_url(); ?>/public/assets/img/address.svg" alt="Address" class="align-self-start me-3">
                                <div>
                                    <p class="text-gray1 mb-0">Address</p>
                                    <p class="text-dark mb-0"><?php if (!empty($address_details->communication_address_line1)) {
                                                                    echo $address_details->communication_address_line1 . ",";
                                                                } ?>
                                    <?php if (!empty($address_details->communication_address_line2)) {
                                        echo $address_details->communication_address_line2 . ",";
                                    } ?>
                                    <?php if (!empty($address_details->communication_state)) {
                                        echo $Candidate_model->get_master_name_dist('master_district', $address_details->communication_district, 'dist_name') . ",";
                                    } ?>
                                    <?php if (!empty($address_details->communication_district)) {
                                        echo $Candidate_model->get_master_name('master_state', $address_details->communication_state, 'name') . ",";
                                    } ?>
                                    <?php if (!empty($address_details->communication_pincode)) {
                                        echo $address_details->communication_pincode . ".";
                                    } ?></p>
                                </div>
                            </li> -->
                            <!-- <?php if (!empty($profile_personal->profile_linked_in)) { ?>
                            <li class="d-flex mb-3">
                                <img src="<?= base_url(); ?>/public/assets/img/linkedin.svg" alt="Linkedin" class="align-self-start me-3">
                                <div>
                                    <p class="text-gray1 mb-0">Linkedin</p>
                                    <p class="text-dark mb-0"><?php echo $profile_personal->profile_linked_in ?></p>
                                </div>
                            </li>
                            <?php } ?>  -->
                        </ul>
                    </div>
                    <?php if ((isset($work_sample->play_store_developer)) || (isset($work_sample->github_profile)) || (isset($work_sample->behance_portfolio_link))) { ?>
                        <!-- <div class="border-top p-3">
                        <ul class="d-flex flex-wrap justify-content-center list-unstyled ps-0 mb-0">
                        <?php if (isset($work_sample->play_store_developer)) { ?>
                            <li>
                                <a href="<?php echo $work_sample->play_store_developer; ?>" target="_blank"><img src="<?= base_url(); ?>/public/assets/img/icon_playstore.svg" alt="playstore" class="me-2" width="45"></a>
                            </li>
                            <?php }
                        if (isset($work_sample->github_profile)) { ?>
                            <li>
                                <a href="<?php echo $work_sample->github_profile; ?>" target="_blank"><img src="<?= base_url(); ?>/public/assets/img/icon_git.svg" alt="git hub" class="me-2" width="45"></a>
                            </li>
                            <?php }
                        if (isset($work_sample->behance_portfolio_link)) { ?>
                            <li>
                                <a href="<?php echo $work_sample->behance_portfolio_link; ?>" target="_blank"><img src="<?= base_url(); ?>/public/assets/img/icon_behance.svg" alt="Behance" class="" width="45"></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div> -->
                    <?php } ?>
                </div>
            </div>
            <div class="col-12 col-lg-8 cInternList">
                <div class="card p-4 mb-4 align-items-center">
                    <div class="w-100 text-center position-relative accessDb">
                        <h3 class="mb-0">Profile Details</h3>
                        <a href="<?= base_url(); ?>/html-pdf/<?php echo $userid; ?>" class="empActBtn hireO noBdr lh-base downloadResume" data-bs-toggle="tooltip" title="Download Resume"><img src="<?= base_url(); ?>/public/assets/img/down_pdf1.svg" alt="download" width="18" class="me-1"> Download</a>
                    </div>
                </div>

                <div class="card profSingleSpc p-4">
                    <!-- <div class="border-bottom mb-4">
                        <div class="d-flex flex-wrap justify-content-between mb-4">
                            <h4 class="text-green fw-medium fs-4 mb-0">Personal Details</h4>
                            <?php if (!isset($apply_internship_details)) { ?>
                            <a href="#" class="text-blue"><i class="fa fa-download me-2" aria-hidden="true"></i></a>
                            <?php } ?>
                        </div>
                        <ul class="ps-0 list-unstyled d-flex flex-wrap">
                            <?php if (!empty($profile_personal->profile_gender)) { ?>
                            <li class="col-md-3">
                                <p class="text-gray1 mb-0">Gender</p>
                                <p class="text-dark mb-0"><?php if (!empty($profile_personal->profile_gender)) {
                                                                echo $Candidate_model->get_master_name('master_gender', $profile_personal->profile_gender, 'gender_type');
                                                            } ?></p>
                            </li>
                            <?php } ?>
                            <li class="col-md-3">
                                <p class="text-gray1 mb-0">Location</p>
                                <p class="text-dark mb-0"><?php if (!empty($address_details->communication_state)) {
                                                                echo $Candidate_model->get_master_name_dist('master_district', $address_details->communication_district, 'dist_name');
                                                            } ?></p>
                            </li>
                            <li class="col-md-3">
                                <p class="text-gray1 mb-0">Date of Birth</p>
                                <p class="text-dark mb-0"><?php if (!empty($profile_personal->profile_dob)) {
                                                                echo date("d-M Y", strtotime($profile_personal->profile_dob));
                                                            } ?></p>
                            </li>
                        </ul>
                    </div> -->
                    <?php if (!empty($experience_details)) { ?>
                        <div class="d-flex flex-wrap position-relative filledData mb-4 pb-0">
                            <div class="col-12 col-md-3">
                                <h4 class="text-green fw-medium fs-4 mb-4">Experience</h4>
                            </div>


                            <div class="col-12 col-md-9 pe-md-4">
                                <?php foreach ($experience_details as $experience) {
                                    if ($experience->experience_type == 1) {
                                        $project_end_year = $experience->project_end_year;
                                        if (empty($experience->project_end_year)) {
                                            $project_end_year = date('Y-m');
                                        }
                                        $diff = abs(strtotime($experience->project_start_year) - strtotime($project_end_year));
                                        $years = floor($diff / (365 * 60 * 60 * 24));
                                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                                ?>
                                        <div class="mb-3 pb-2 border-bottom bdrHide">
                                            <h6 class="fw-medium"><?php if (isset($experience->project_organization)) {
                                                                        echo $experience->project_organization;
                                                                    } ?> <span class="badge bg-gray fw-normal text-dark ms-3">Job</span></h6>
                                            <h6 class="mb-1 f-14 fw-normal text-muted"><?php if ($experience->project_title != 0) {
                                                                                            $where = array('id' => $experience->project_title);
                                                                                            $profile_id = $Candidate_model->fetch_table_row('master_profile', $where);
                                                                                            echo $project_title = $profile_id->profile;
                                                                                        } else {
                                                                                            echo $project_title = $experience->profile_other;
                                                                                        }
                                                                                        ?></h6>
                                            <ul class="d-flex flex-wrap ps-0 mb-2 list-unstyled">
                                                <!--  <li class="me-5 text-muted list-unstyled"><?php if (isset($experience->project_location)) {
                                                                                                    echo $Candidate_model->get_master_name('master_city', $experience->project_location, 'city');
                                                                                                } ?></li> -->
                                                <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if ($years != 0) {
                                                                                                                                                                                echo $years . " year ";
                                                                                                                                                                            }
                                                                                                                                                                            if ($months != 0) {
                                                                                                                                                                                echo $months . " Months";
                                                                                                                                                                            }
                                                                                                                                                                            if ($months == 0 && $years == 0 && $days != 0) {
                                                                                                                                                                                echo $days . " days";
                                                                                                                                                                            } ?></li>
                                                <li class="text-muted "><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2"><?php if ($experience->project_currently_ongoing != 1) {
                                                                                                                                                            echo date("M Y", strtotime($experience->project_start_year)) . ' - ' . date("M Y", strtotime($experience->project_end_year));
                                                                                                                                                        } else {
                                                                                                                                                            echo date("M Y", strtotime($experience->project_start_year)) . ' - Present';
                                                                                                                                                        } ?></li>
                                            </ul>
                                            <!-- <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text evers when<span id="dots">...</span><span id="more">erisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. <a href="" class="text-blue"  onclick="myFunction()" id="myBtn">Read more</a></p> -->
                                            <p class="mb-2"><span class="more text-muted">
                                                    <?php if (isset($experience->project_description)) {
                                                        echo $experience->project_description;
                                                    } ?>
                                                </span></p>
                                        </div>
                                <?php }
                                } ?>
                                <?php foreach ($experience_details as $experience) {
                                    if ($experience->experience_type == 2) {
                                        $project_end_year = $experience->project_end_year;
                                        if (empty($experience->project_end_year)) {
                                            $project_end_year = date('Y-m');
                                        }
                                        $diff = abs(strtotime($experience->project_start_year) - strtotime($project_end_year));
                                        $years = floor($diff / (365 * 60 * 60 * 24));
                                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                                ?>
                                        <div class="mb-3 pb-2 border-bottom bdrHide">
                                            <h6 class="fw-medium"><?php if (isset($experience->project_organization)) {
                                                                        echo $experience->project_organization;
                                                                    } ?> <span class="badge bg-gray fw-normal text-dark ms-3">Internship</span></h6>
                                            <h6 class="mb-1 f-14 fw-normal text-muted"><?php if ($experience->project_title != 0) {
                                                                                            $where = array('id' => $experience->project_title);
                                                                                            $profile_id = $Candidate_model->fetch_table_row('master_profile', $where);
                                                                                            echo $project_title = $profile_id->profile;
                                                                                        } else {
                                                                                            echo $project_title = $experience->profile_other;
                                                                                        }
                                                                                        ?></h6>
                                            <ul class="d-flex flex-wrap ps-0 mb-2 list-unstyled">
                                                <!--   <li class="me-5 text-muted list-unstyled"><?php if (isset($experience->project_location)) {
                                                                                                        echo $Candidate_model->get_master_name('master_city', $experience->project_location, 'city');
                                                                                                    } ?></li> -->
                                                <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if ($years != 0) {
                                                                                                                                                                                echo $years . " year ";
                                                                                                                                                                            }
                                                                                                                                                                            if ($months != 0) {
                                                                                                                                                                                echo $months . " Months";
                                                                                                                                                                            }
                                                                                                                                                                            if ($months == 0 && $years == 0 && $days != 0) {
                                                                                                                                                                                echo $days . " days";
                                                                                                                                                                            } ?></li>
                                                <li class="text-muted "><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2"><?php if ($experience->project_currently_ongoing != 1) {
                                                                                                                                                            echo date("M Y", strtotime($experience->project_start_year)) . ' - ' . date("M Y", strtotime($experience->project_end_year));
                                                                                                                                                        } else {
                                                                                                                                                            echo date("M Y", strtotime($experience->project_start_year)) . ' - Present';
                                                                                                                                                        } ?></li>
                                            </ul>
                                            <!-- <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text evers when<span id="dots">...</span><span id="more">erisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. <a href="" class="text-blue"  onclick="myFunction()" id="myBtn">Read more</a></p> -->
                                            <p class="mb-2"><span class="more text-muted">
                                                    <?php if (isset($experience->project_description)) {
                                                        echo $experience->project_description;
                                                    } ?>
                                                </span></p>
                                        </div>
                                <?php }
                                } ?>
                                <?php foreach ($experience_details as $experience) {
                                    if ($experience->experience_type == 3) {
                                        $project_end_year = $experience->project_end_year;
                                        if (empty($experience->project_end_year)) {
                                            $project_end_year = date('Y-m');
                                        }
                                        $diff = abs(strtotime($experience->project_start_year) - strtotime($project_end_year));
                                        $years = floor($diff / (365 * 60 * 60 * 24));
                                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                ?>
                                        <div class="mb-3 pb-2 border-bottom bdrHide">
                                            <h6 class="fw-medium"><?php if (isset($experience->project_organization)) {
                                                                        echo $experience->project_organization;
                                                                    } ?> <span class="badge bg-gray fw-normal text-dark ms-3">Training</span></h6>
                                            <h6 class="mb-1 f-14 fw-normal text-muted"><?php if (isset($experience->project_title)) {
                                                                                            echo $experience->project_title;
                                                                                        } ?>
                                                <?php if (isset($experience->project_organization_online)) {
                                                    if ($experience->project_organization_online == '1') {
                                                        echo ' (Online) ';
                                                    } else {
                                                        echo ' (Offline) ';
                                                    }
                                                } ?></h6>
                                            <ul class="d-flex flex-wrap ps-0 mb-1 list-unstyled">
                                                <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if (isset($experience->project_duration)) {
                                                                                                                                                                                echo $experience->project_duration_type;
                                                                                                                                                                                if ($experience->project_duration == 1) {
                                                                                                                                                                                    echo " Days";
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo " Hours";
                                                                                                                                                                                }
                                                                                                                                                                            } ?></li>
                                                <!-- <li class="me-5 text-muted list-unstyled"><?php if ($years != 0) {
                                                                                                    echo $years . " year ";
                                                                                                }
                                                                                                if ($months != 0) {
                                                                                                    echo $months . " Months";
                                                                                                } ?></li>
                                <li class="text-muted"><?php if ($experience->project_currently_ongoing != 1) {
                                                            echo date("M Y", strtotime($experience->project_start_year)) . ' - ' . date("M Y", strtotime($experience->project_end_year));
                                                        } else {
                                                            echo date("M Y", strtotime($experience->project_start_year)) . ' - Present';
                                                        } ?></li> -->
                                            </ul>
                                            <!-- <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text evers when<span id="dots">...</span><span id="more">erisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. <a href="" class="text-blue"  onclick="myFunction()" id="myBtn">Read more</a></p> -->
                                            <p class="mb-2"><span class="more text-muted">
                                                    <?php if (isset($experience->project_description)) {
                                                        echo $experience->project_description;
                                                    } ?>
                                                </span></p>
                                        </div>
                                <?php }
                                } ?>
                                <?php foreach ($experience_details as $experience) {
                                    if ($experience->experience_type == 4) {
                                        $project_end_year = $experience->project_end_year;
                                        if (empty($experience->project_end_year)) {
                                            $project_end_year = date('Y-m');
                                        }
                                        $diff = abs(strtotime($experience->project_start_year) - strtotime($project_end_year));
                                        $years = floor($diff / (365 * 60 * 60 * 24));
                                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)); ?>
                                        <div class="mb-3 pb-2 border-bottom bdrHide">
                                            <h6 class="fw-medium"><?php if (isset($experience->project_title)) {
                                                                        echo $experience->project_title;
                                                                    } ?> <?php if (isset($experience->project_organization)) {
                                                                                if ($experience->project_organization == 1) {
                                                                                    echo " (Academic) ";
                                                                                } else {
                                                                                    echo " (Industry) ";
                                                                                }
                                                                            } ?><span class="badge bg-gray fw-normal text-dark ms-3">Projects</span></h6>
                                            <a href="<?php echo $experience->project_link; ?>" target="_blank" class="text-muted">
                                                <h6 class="mb-1 f-14 fw-normal text-muted"><?php if (isset($experience->project_link)) {
                                                                                                echo $experience->project_link;
                                                                                            } ?></h6>
                                            </a>
                                            <!-- <ul class="d-flex flex-wrap ps-0 mb-1">
                                <li class="me-5 text-muted list-unstyled"><?php if ($years != 0) {
                                                                                echo $years . " year ";
                                                                            }
                                                                            if ($months != 0) {
                                                                                echo $months . " Months";
                                                                            } ?></li>
                                <li class="text-muted"><?php if ($experience->project_currently_ongoing != 1) {
                                                            echo date("M Y", strtotime($experience->project_start_year)) . ' - ' . date("M Y", strtotime($experience->project_end_year));
                                                        } else {
                                                            echo date("M Y", strtotime($experience->project_start_year)) . ' - Present';
                                                        } ?></li>
                            </ul> -->
                                            <!-- <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text evers when<span id="dots">...</span><span id="more">erisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. <a href="" class="text-blue"  onclick="myFunction()" id="myBtn">Read more</a></p> -->
                                            <p class="mb-2"><span class="more text-muted">
                                                    <?php if (isset($experience->project_description)) {
                                                        echo $experience->project_description;
                                                    } ?>
                                                </span></p>
                                        </div>
                                <?php }
                                } ?>
                            </div>

                            <?php if (!isset($apply_internship_details)) {
                                //$session->set($ses_data); 
                            ?>
                                <a href="<?= base_url(); ?>/experience-details" class="text-blue editAb"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($education_details)) { ?>
                        <div class="d-flex flex-wrap position-relative filledData mb-4 pb-0">
                            <div class="col-12 col-md-3">
                                <h4 class="text-green fw-medium fs-4 mb-4">Education</h4>
                            </div>
                            <div class="col-12 col-md-9 pe-md-4">
                                <?php
                                foreach ($education_details as $education) {
                                    // $project_end_year=$education->education_end_year;
                                    // if(empty($education->education_end_year)){
                                    //     $project_end_year=date('Y-m');
                                    // }
                                    // $diff = abs(strtotime($education->education_start_year)-strtotime($project_end_year));
                                    // $years = floor($diff / (365*60*60*24));
                                    // $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                ?><?php
                                    if (!empty($education->education_course)) {
                                        $where_can = array('status' => '1', 'id' => $education->education_course);
                                        $academic_courses_details = $Candidate_model->fetch_table_row('master_academic_courses', $where_can);
                                        if ($academic_courses_details->course_duration != 0) {
                                            if (date('m') > 5) {
                                                $education_start_year_fin = $education->education_start_year + 1;
                                            } else {
                                                $education_start_year_fin = $education->education_start_year;
                                            }
                                            $student_study_year = $education_start_year_fin + $academic_courses_details->course_duration;
                                            // echo $student_study_year;


                                            if (date('m') > 5) {
                                                $current_year = date('Y') + 1;
                                            } else {
                                                $current_year = date('Y');
                                            }
                                            // echo $current_year;
                                            if ($student_study_year >= $current_year) {
                                                $study_year = $current_year - $education->education_start_year;
                                                if ($study_year == 1) {
                                                    $final_study_year = "1st Year";
                                                } elseif ($study_year == 2) {
                                                    $final_study_year = "2nd Year";
                                                } elseif ($study_year == 3) {
                                                    $final_study_year = "3rd Year";
                                                } elseif ($study_year == 4) {
                                                    $final_study_year = "4th Year";
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                <div class="mb-3 pb-2 border-bottom bdrHide">
                                    <h6 class="fw-medium"><?php if (isset($education->education_course) && $education->education_course != 0) {
                                                                echo $Candidate_model->get_master_name('master_academic_courses', $education->education_course, 'name');
                                                            } else {
                                                                echo $education->education_course_other;
                                                            } ?> ( <?php if (isset($education->education_course) && $education->education_specialization != 0) {
                                                                        echo $Candidate_model->get_master_name('master_academic_branch', $education->education_specialization, 'name');
                                                                    } else {
                                                                        echo $education->education_specialization_other;
                                                                    } ?> ) <?php if (isset($final_study_year)) {
                                                                                    echo ' - ' . $final_study_year;
                                                                                } ?></h6>
                                    <h6 class="text-muted fw-normal f-14 mb-1"><?php if (!empty($education->education_college_name) && $education->education_college_name != 0) {
                                                                                    echo $Candidate_model->get_master_name('master_college', $education->education_college_name, 'college_name');
                                                                                } else {
                                                                                    echo $education->education_college_name_other;
                                                                                } ?></h6>
                                    <ul class="d-flex flex-wrap ps-0 mb-1 list-unstyled">
                                        <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if (isset($education->education_start_year)) {
                                                                                                                                                                        echo $education->education_end_year - $education->education_start_year . " years ";
                                                                                                                                                                    } ?></li>
                                        <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2"><?php if (isset($education->education_start_year)) {
                                                                                                                                                        echo $education->education_start_year;
                                                                                                                                                    } ?> - <?php if (isset($education->education_end_year)) {
                                                                                                                                                                echo $education->education_end_year;
                                                                                                                                                            } ?></li>
                                        <?php if (isset($education->education_performance_scale_optional) && (!empty($education->education_performance_scale_optional))) { ?> <li class="text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_mark_gray.svg" class="me-2" width="14"><?php if (isset($education->education_performance_optional) && !empty($education->education_performance_optional)) {
                                                                                                                                                                                                                                                                                                    if ($education->education_performance_scale_optional == 1) {
                                                                                                                                                                                                                                                                                                        echo "Percentage : " . $education->education_performance_optional;
                                                                                                                                                                                                                                                                                                    } elseif ($education->education_performance_scale_optional == 2) {
                                                                                                                                                                                                                                                                                                        echo "CGPA : " . $education->education_performance_optional;
                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                } ?> <?php if (isset($education->education_performance_scale_optional) && !empty($education->education_performance_scale_optional) && ($education->education_performance_optional) && !empty($education->education_performance_optional)) {
                                                                                                                                                                                                                                                                                                            if ($education->education_performance_scale_optional == 1) {
                                                                                                                                                                                                                                                                                                                echo "%";
                                                                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                                                                echo "(Scale of 10)";
                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                                                            echo "";
                                                                                                                                                                                                                                                                                                        } ?></li><?php } ?>
                                    </ul>
                                </div>

                            <?php } ?>
                            </div>
                            <?php if (!isset($apply_internship_details)) {
                                //$session->set($ses_data); 
                            ?>
                                <a href="<?= base_url(); ?>/education-details" class="text-blue editAb"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($skill_details)) { ?>
                        <div class="d-flex flex-wrap position-relative filledData mb-4 pb-0">
                            <div class="col-12 col-md-3">
                                <h4 class="text-green fw-medium fs-4 mb-4">Skills</h4>
                            </div>
                            <div class="col-12 col-md-9 pe-md-4">
                                <ul class="d-flex flex-wrap ps-0 list-unstyled mb-0">
                                    <?php
                                    foreach ($skill_details as $skill) { ?>
                                        <li class="d-flex me-3 mb-2">
                                            <?php echo $Candidate_model->get_master_name('master_skills', $skill->skills, 'skill_name'); ?>
                                            <ul class="ps-0 list-unstyled mb-0 ms-2 d-flex">
                                                <li><i class="fa fa-star me-1 text-yellow" aria-hidden="true"></i></li>
                                                <li><i class="<?php if ($skill->skill_level != 1) {
                                                                    echo "fa fa-star";
                                                                } else {
                                                                    echo "fa fa-star-o";
                                                                } ?> me-1 text-yellow" aria-hidden="true"></i></li>
                                                <li><i class="<?php if ($skill->skill_level == 3) {
                                                                    echo "fa fa-star";
                                                                } else {
                                                                    echo "fa fa-star-o";
                                                                } ?> me-1 text-yellow" aria-hidden="true"></i></li>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                </ul>
                            </div>
                            <?php if (!isset($apply_internship_details)) {
                                //$session->set($ses_data); 
                            ?>
                                <a href="<?= base_url(); ?>/skill-details" class="text-blue editAb"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($work_sample->blog_link) || !empty($work_sample->github_profile) || !empty($work_sample->play_store_developer) || !empty($work_sample->behance_portfolio_link) || !empty($work_sample->other_work_sample_link)) { ?>
                        <div class="d-flex flex-wrap position-relative filledData pb-0">
                            <div class="col-12 col-md-3">
                                <h4 class="text-green fw-medium fs-4">Portfolios / <br class="d-md-block d-none">work samples</h4>
                            </div>
                            <div class="col-12 col-md-9 pe-md-4 d-flex flex-wrap">
                                <?php if (isset($work_sample->blog_link) && !empty($work_sample->blog_link)) { ?>
                                    <div class="col-12 col-md-6 pb-4 pe-2">
                                        <h6 class="fw-medium">Blog link</h6>
                                        <a href="<?php echo $work_sample->blog_link; ?>" target="_blank" class="text-blue"><?php echo $work_sample->blog_link; ?></a>
                                    </div>
                                <?php }
                                if (isset($work_sample->github_profile) && !empty($work_sample->github_profile)) { ?>
                                    <div class="col-12 col-md-6 pb-4">
                                        <h6 class="fw-medium">GitHub profile</h6>
                                        <a href="<?php echo $work_sample->github_profile; ?>" target="_blank" class="text-blue"><?php echo $work_sample->github_profile; ?></a>
                                    </div>
                                <?php }
                                if (isset($work_sample->play_store_developer) && !empty($work_sample->play_store_developer)) { ?>
                                    <div class="col-12 col-md-6 pb-4 pe-2">
                                        <h6 class="fw-medium">Play store developer A/c (public link)</h6>
                                        <a href="<?php echo $work_sample->play_store_developer; ?>" target="_blank" class="text-blue"><?php echo $work_sample->play_store_developer; ?></a>
                                    </div>
                                <?php }
                                if (isset($work_sample->behance_portfolio_link) && !empty($work_sample->behance_portfolio_link)) { ?>
                                    <div class="col-12 col-md-6 pb-4">
                                        <h6 class="fw-medium">Behance portfolio link</h6>
                                        <a href="<?php echo $work_sample->behance_portfolio_link; ?>" target="_blank" class="text-blue"><?php echo $work_sample->behance_portfolio_link; ?></a>
                                    </div>
                                <?php }
                                if (isset($work_sample->kaggle_link) && !empty($work_sample->kaggle_link)) { ?>
                                    <div class="col-12 col-md-6 pb-4 pe-2">
                                        <h6 class="fw-medium">Kaggle Link</h6>
                                        <a href="<?php echo $work_sample->kaggle_link; ?>" target="_blank" class="text-blue"><?php echo $work_sample->kaggle_link; ?></a>
                                    </div>
                                <?php }
                                if (isset($work_sample->other_work_sample_link) && !empty($work_sample->other_work_sample_link)) { ?>
                                    <div class="col-12 col-md-6 pb-4">
                                        <h6 class="fw-medium">Other work sample link</h6>
                                        <a href="<?php echo $work_sample->other_work_sample_link; ?>" target="_blank" class="text-blue"><?php echo $work_sample->other_work_sample_link; ?></a>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if (!isset($apply_internship_details)) {
                                //$session->set($ses_data); 
                            ?>
                                <a href="<?= base_url(); ?>/work-sample-details" class="text-blue editAb"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($view_profile_emp)) { ?>
                        <div class="d-flex flex-wrap position-relative">
                            <div class="col-12 col-md-3">
                                <h4 class="text-green fw-medium fs-4">Corporates Who <br class="d-md-block d-none">Viewed Your Profile</h4>
                            </div>
                            <div class="col-12 col-md-9 d-flex flex-wrap">
                                <!-- <h6 class="fw-medium">Unlock all your profile viewers with Premium</h6> -->
                                <div class="d-flex justify-content-sm-between flex-sm-row flex-column w-100">
                                    <div class="total_enroll d-flex flex-wrap align-items-center justify-content-start">
                                    <img src="<?php base_url(); ?>public/assets/img/blur1.png" alt="">
                                                <img src="<?php base_url(); ?>public/assets/img/blur2.png" alt="">
                                                <img src="<?php base_url(); ?>public/assets/img/blur3.png" alt="">
                                        <!-- <?php if ($profile_personal->payment_package_type == 2 || $profile_personal->payment_package_type == 3 || $profile_personal->payment_package_type == 1) {
                                            if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                                <?php foreach ($view_profile_emp as $emp_lo) {
                                                    $check = file_exists(FCPATH . "public/assets/docs/uploads/emp_profile/" . $emp_lo->profile_company_logo);
                                                ?>

                                                    <?php if ($check) { ?>
                                                        <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $emp_lo->profile_company_logo; ?>" alt="">
                                                    <?php } else { ?>

                                                    <?php } ?>

                                                <?php } ?>

                                            <?php } else { ?>

                                                <img src="<?php base_url(); ?>public/assets/img/blur1.png" alt="">
                                                <img src="<?php base_url(); ?>public/assets/img/blur2.png" alt="">
                                                <img src="<?php base_url(); ?>public/assets/img/blur3.png" alt="">
                                            <?php } ?>
                                        <?php } else { ?>
                                            <img src="<?php base_url(); ?>public/assets/img/blur1.png" alt="">
                                            <img src="<?php base_url(); ?>public/assets/img/blur2.png" alt="">
                                            <img src="<?php base_url(); ?>public/assets/img/blur3.png" alt="">
                                        <?php   } ?> -->
                                        <?php if ($profile_personal->payment_package_type == 2 || $profile_personal->payment_package_type == 3 || $profile_personal->payment_package_type == 1) {
                                            if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>
                                                <p class="ms-3 mb-0"><a href="<?= base_url(); ?>/profile-viewed-employers" class="text-blue"><?php echo count($view_profile_emp); ?> - Profile Views</a></p>

                                            <?php } else { ?>
                                                <p class="ms-3 mb-0"><a onclick="pricing_plan_subscription(3)" class="text-blue"><?php echo count($view_profile_emp); ?> - Profile Views</a></p>
                                            <?php   }
                                        } else { ?>
                                            <p class="ms-3 mb-0"><a onclick="pricing_plan_subscription(1)" class="text-blue"><?php echo count($view_profile_emp); ?> - Profile Views</a></p>
                                        <?php  } ?>
                                    </div>
                                    <?php if ($profile_personal->payment_package_type == 2 || $profile_personal->payment_package_type == 3 || $profile_personal->payment_package_type == 1) {
                                        if (!empty($profile_personal->payment_expiry_date) && $profile_personal->payment_expiry_date > date('Y-m-d')) { ?>


                                        <?php } else { ?>
                                            <a href="<?= base_url(); ?>/pricing-plan" class="btn btn-prim px-3 align-self-sm-center align-self-end"><img src="<?php base_url(); ?>public/assets/img/premium.svg" alt="" class="me-2 mb-1" width="18">Try Premium</a>
                                        <?php   }
                                    } else { ?>
                                        <a href="<?= base_url(); ?>/pricing-plan"  class="btn btn-prim px-3 align-self-sm-center align-self-end"><img src="<?php base_url(); ?>public/assets/img/premium.svg" alt="" class="me-2 mb-1" width="18">Try Premium</a>
                                    <?php  } ?>




                                </div>
                            </div>

                        </div>
                    <?php } ?>
                </div><BR>
                <?php if (empty($experience_details)) { ?>
                    <a href="<?= base_url(); ?>/experience-details" class="btn btn-outlined-blue px-3 mb-1"><i class="fa fa-plus me-2" aria-hidden="true"></i>Add Experience</a>
                <?php } ?>
                <?php if (empty($skill_details)) { ?>
                    <a href="<?= base_url(); ?>/skill-details" class="btn btn-outlined-blue px-3 mb-1"><i class="fa fa-plus me-2" aria-hidden="true"></i>Add Skill</a>
                <?php } ?>
                <?php if (empty($work_sample->blog_link) && empty($work_sample->github_profile) && empty($work_sample->play_store_developer) && empty($work_sample->behance_portfolio_link) && empty($work_sample->other_work_sample_link)) { ?>
                    <a href="<?= base_url(); ?>/work-sample-details" class="btn btn-outlined-blue px-3 mb-1"><i class="fa fa-plus me-2" aria-hidden="true"></i>Add work samples</a>
                <?php } ?>
            </div>
        </div>

    </section>
    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
    require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        function pricing_plan_subscription(val) {

            if (val == 3) {
                var title_val = "Upgrade your plan";
                var text_val = "Upgrade your plan to view the corporates who viewed your profile";

            } else {
                var title_val = "Subscribe now";
                var text_val = "Subscribe internme to view the corporates who viewed your profile";

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
                    imageElement.addEventListener('click', function() {
                        // Replace 'yourLink' with the actual hyperlink
                        window.open(img_url, '_blank');
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo base_url('pricing-plan'); ?>";
                } else {
                    location.reload();
                }
            });

        }
    </script>
</body>

</html>