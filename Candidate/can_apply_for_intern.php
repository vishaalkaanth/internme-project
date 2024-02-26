<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="stickyFoot">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>

    <?php

    use App\Models\Candidate_model;

    $session = session();
    $Candidate_model = new Candidate_model();
    $userid    =    $session->get('userid');
    $complete_popup    =    $session->get('complete_popup');
    $updated_status    =    $session->get('updated_status');

    if ($updated_status == 1) {
        $ses_data = [
            'profile_complete_status',
            'company_logo',
            'company_name',
            'intership_profile',
            'intership_number',
            'edit_profile',
            'next_but_status'
        ];
        $session->remove($ses_data);
    }
    //   print_r($_SESSION);


    ?>
    <div class="offcanvas offcanvas-end <?php if (isset($complete_popup)) {
                                            echo 'show';
                                        } ?>" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="false">
        <div class="offcanvas-body text-center d-flex justify-content-center align-items-center flex-column">
            <img src="<?= base_url(); ?>/public/assets/img/std_otp.gif" alt="" width="100" class="mb-4">
            <h2 class="fw-bold text-blue fs-4 mb-4" id="offcanvasRightLabel">Your application has been submitted</h5>
                <!-- <p class="text-gray mb-4">You can track its status on your dashboard</p> -->
                <a href="<?= base_url(); ?>/my-applications" class="btn btn-prim">Done</a>
        </div>
    </div>
    <?php if (isset($complete_popup)) {
        echo "<div class='offcanvas-backdrop fade show'></div>";
    } ?>
    <!----- Form ------>
    <section class="container my-4">
        <div class="d-flex flex-wrap row flex-column-reverse flex-lg-row">
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
                        <h6 class="text-muted fw-normal f-14 overflow-anywhere"><?php if (!empty($education_details[0]->education_college_name) && $education_details[0]->education_college_name != 0) {
                                                                    echo ucfirst($Candidate_model->get_master_name('master_college', $education_details[0]->education_college_name, 'college_name'));
                                                                } else {
                                                                    echo ucfirst($education_details[0]->education_college_name_other);
                                                                } ?></h6>
                       
                    </div>

                    <div class="border-top p-3">
                        <div class="d-flex justify-content-between flex-wrap">
                            <h3 class="text-dark fs-5 fw-medium mb-4">Personal Details</h3>
                            <?php
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
                                'intership_number' => $internship_details->internship_id,
                                'edit_profile' => 1
                            ];
                            if (!isset($apply_internship_details)) {
                                //    $session->set($ses_data);
                            ?>
                                <a href="<?= base_url(); ?>/can_profile_edit/1/<?php echo $profile_company_logo ?>/<?php echo $profile_company_name ?>/<?php echo $profile ?>/<?php echo $internship_details->internship_id ?>" class="text-blue edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <?php } ?>
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
                            <li class="d-flex mb-3">
                                <img src="<?= base_url(); ?>/public/assets/img/gender.svg" alt="gender" class="align-self-start me-3">
                                <div>
                                    <p class="text-gray1 mb-0">Gender</p>
                                    <p class="text-dark mb-0"><?php if (!empty($profile_personal->profile_gender)) {
                                                                    echo $Candidate_model->get_master_name('master_gender', $profile_personal->profile_gender, 'gender_type');
                                                                } ?></p>
                                </div>
                            </li>
                            <li class="d-flex mb-3">
                                <img src="<?= base_url(); ?>/public/assets/img/address.svg" alt="location" class="align-self-start me-3">
                                <div>
                                    <p class="text-gray1 mb-0">Preferred Location</p>
                                    <p class="text-dark mb-0"><?php if (!empty($profile_personal->g_location_name)) {
                                                                    echo $profile_personal->g_location_name;
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
            <div class="d-flex justify-content-end mb-2">
                    <a href="#" class="text-blue backBtn" onclick="previous()"><i class="fa fa-long-arrow-left me-1" aria-hidden="true"></i> Back</a>
                </div>
                <div class="card p-4 mb-3">
                    <div class="d-flex justify-content-between flex-md-row flex-column">
                        <div class="d-flex align-items-center">
                            <div class="comLogo d-flex justify-content-center align-items-center rounded p-1 me-3">
                            <!-- <span class="cCompLogo d-flex justify-content-center align-items-center p-1 rounded"> -->
                            <?php if (isset($emp_profile_details->profile_company_logo) && !empty($emp_profile_details->profile_company_logo)) { ?>
                                <?php 
                                        $check = file_exists(FCPATH."public/assets/docs/uploads/emp_profile/".$emp_profile_details->profile_company_logo);
                                             ?>
                                             <?php if($check){ ?>
                               
                                <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $emp_profile_details->profile_company_logo; ?>" alt="logo" class="img-fluid noStretch" width="60" style="border-radius: 50%;">
                                <?php }else{ ?>
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
                        <!-- </span> -->
                            </div>
                            <div class="d-flex flex-column">
                                <h3 class="fw-semibold fs-5"><?php if (!isset($apply_internship_details)) { ?>Applying to <?php } else { ?>Applied to <?php }
                                                                                                                                                if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                                                                                                                                    echo $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                                                                                                                                } else {
                                                                                                                                                    echo $internship_details->other_profile;
                                                                                                                                                } ?> internship at <?php if (isset($emp_profile_details->profile_company_name)) {
                                                                                                                                                                                                                                                                                                                                                                                                                        echo $emp_profile_details->profile_company_name;
                                                                                                                                                                                                                                                                                                                                                                                                                    } ?> </h3>
                                <?php if (!isset($apply_internship_details)) { ?>
                                    <!-- <p class="text-muted mb-0">This is the resume that the employer will see, make sure it is up to date.
                                        You can add or edit details below.</p> -->
                                <?php } ?>
                            </div>
                            
                        </div>
                       
                    </div>
                    <?php if (!isset($apply_internship_details)) { ?>
                        <form id="can_apply_internship_sub" action="<?= base_url(); ?>/can_apply_internship"  method="post" accept-charset="utf-8" class="mt-2" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="internship_id" name="internship_id" value="<?php if (isset($internship_details->internship_id)) {
                                                                                                    echo $internship_details->internship_id;
                                                                                                } ?>">
                            <input type="button" class="btn btn-prim align-self-end btnResize float-end canvasBtn" id="can_proceed_apply_submit" value="Proceed to apply" onclick="proceed_to_submit();" />
                            <!-- <a href="<?= base_url(); ?>/can-proceeds-apply/<?php echo $internship_details->internship_id; ?>" class="btn btn-prim align-self-end btnResize ms-3">Proceed to apply</a> -->
                        </form>
                    <?php } else {
                        $current_date = date("d-m-Y");
                        $applied_date = date("d-m-Y", strtotime($apply_internship_details->created_at));
                    ?>
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                    <?php if (isset($apply_internship_details)) { ?>
                             <div class="align-self-md-center align-self-end appStatus mb-2 mb-md-0">
                                <?php

                             if ($apply_internship_details->hiring_status==1) 
                               {
                                echo "<span class='badge badge-completed fw-semibold py-2'>Offer accepted</span>";
                               }else
                               {
                               if ($apply_internship_details->hiring_status==2) 
                                 {
                                    echo "<span class='badge badge-red fw-semibold py-2'>Offer declined</span>";
                                   }else
                                   {
                                    ?>
                               
                                <?php if ($apply_internship_details->application_status == '0') { ?>
                                    <span class="badge appProgress fw-semibold py-2">Status : Inprogress</span>
                                <?php } elseif ($apply_internship_details->application_status == '2') { ?>
                                    <span class="badge appHired fw-semibold py-2">Status : Hired</span>
                                <?php } elseif ($apply_internship_details->application_status == '3') { ?>
                                    <span class="badge appReject fw-semibold py-2">Status : Not Qualified</span>
                                <?php  }  elseif ($apply_internship_details->application_status == '4') {
                                    if($internship_list->complete_type != 1){
                                     ?>
                                    <span class="badge appReject fw-semibold py-2">Status : Dropped</span>
                                    <?php } else { ?>
                                    <span class="badge appReject fw-semibold py-2">Status : Completed</span>
                           <?php  } } }?>
                                </div>
                          <?php  } } ?>
                       
                    </div>
                    <?php 
                }
               ?>
                    
                </div>
                <?php if(!isset($apply_internship_details)){?>
                                <p class="text-muted mb-2">This is the resume that the employer will see, make sure it is up to date.
                                    You can add or edit details below.</p>
                                <?php }?>
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
                                        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                                ?>
                                        <div class="mb-3 pb-2 border-bottom bdrHide">
                                            <h6 class="fw-medium"><?php if (isset($experience->project_organization)) {
                                                                        echo $experience->project_organization;
                                                                    } ?> <span class="badge bg-gray fw-normal text-dark ms-3">Job</span></h6>
                                            <h6 class="mb-1 f-14 fw-normal text-muted"><?php if($experience->project_title!=0){
                                                    $where = array('id' => $experience->project_title);
                                                    $profile_id = $Candidate_model->fetch_table_row('master_profile',$where);
                                                   echo $project_title=$profile_id->profile;
                                              }else{
                                                 echo $project_title=$experience->profile_other;
                                              }
                                                    ?></h6>
                                            <ul class="d-flex flex-wrap ps-0 mb-2 list-unstyled">
                                                <!-- <li class="me-5 text-muted list-unstyled"><?php if (isset($experience->project_location)) {
                                                                                                    echo $Candidate_model->get_master_name('master_city', $experience->project_location, 'city');
                                                                                                } ?></li> -->
                                                <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if ($years != 0) {
                                                                                                                        if($years==1){echo $years . " year ";}else{ echo $years . " years ";}
                                                                                                                    }
                                                                                                                    if ($months != 0) {
                                                                                                                        if($months==1){echo $months . " Month ";}else{ echo $months . " Months ";}
                                                                                                                    } if($months==0 && $years==0 && $days!=0){ if($days==1){echo $days . " day ";}else{ echo $days . " days ";}}?></li>
                                                <li class="text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2"><?php if ($experience->project_currently_ongoing != 1) {
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
                                        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                                ?>
                                        <div class="mb-3 pb-2 border-bottom bdrHide">
                                            <h6 class="fw-medium"><?php if (isset($experience->project_organization)) {
                                                                        echo $experience->project_organization;
                                                                    } ?> <span class="badge bg-gray fw-normal text-dark ms-3">Internship</span></h6>
                                            <h6 class="mb-1 f-14 fw-normal text-muted"><?php if($experience->project_title!=0){
                                                    $where = array('id' => $experience->project_title);
                                                    $profile_id = $Candidate_model->fetch_table_row('master_profile',$where);
                                                   echo $project_title=$profile_id->profile;
                                              }else{
                                                 echo $project_title=$experience->profile_other;
                                              }
                                                    ?></h6>
                                            <ul class="d-flex flex-wrap ps-0 mb-1 list-unstyled">
                                                <!--  <li class="me-5 text-muted list-unstyled"><?php if (isset($experience->project_location)) {
                                                                                                    echo $Candidate_model->get_master_name('master_city', $experience->project_location, 'city');
                                                                                                } ?></li> -->
                                                <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if ($years != 0) {
                                                                                                if($years==1){echo $years . " year ";}else{ echo $years . " years ";}
                                                                                            }
                                                                                            if ($months != 0) {
                                                                                                if($months==1){echo $months . " Month ";}else{ echo $months . " Months ";}
                                                                                            } if($months==0 && $years==0 && $days!=0){ if($days==1){echo $days . " day ";}else{ echo $days . " days ";}} ?></li>
                                                <li class="text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2"><?php if ($experience->project_currently_ongoing != 1) {
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
                                                                                        } ?><?php if (isset($experience->project_organization_online)) {
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
                                <a href="<?= base_url(); ?>/can_profile_edit/2/<?php echo $profile_company_logo ?>/<?php echo $profile_company_name ?>/<?php echo $profile ?>/<?php echo $internship_details->internship_id ?>" class="text-blue editAb"><i class="fa fa-pencil" aria-hidden="true"></i></a>
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
                                                                                                                                                                                                                                                                                                            } ?> )</h6>
                                        <h6 class="text-muted fw-normal f-14 mb-1"><?php if (!empty($education->education_college_name) && $education->education_college_name != 0) {
                                                                                        echo $Candidate_model->get_master_name('master_college', $education->education_college_name, 'college_name');
                                                                                    } else {
                                                                                        echo $education_details[0]->education_college_name_other;
                                                                                    } ?></h6>
                                        <ul class="d-flex flex-wrap ps-0 mb-1 list-unstyled">
                                            <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if (isset($education->education_start_year)) {
                                                                                           $years_edu=$education->education_end_year - $education->education_start_year;
                                                                                           if($years_edu=='1'){ echo  $years_edu. " year "; }else{ echo  $years_edu. " years";}
                                                                                        } ?></li>
                                            <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2"><?php if (isset($education->education_start_year)) {
                                                                        echo $education->education_start_year;
                                                                    } ?> - <?php if (isset($education->education_end_year)) {
                                                                                                                                                                        echo $education->education_end_year;
                                                                                                                                                                    } ?></li>
                                        <?php if (isset($education->education_performance_scale_optional) && (!empty($education->education_performance_scale_optional))) { ?> <li class="text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_mark_gray.svg" class="me-2" width="14"><?php if(isset($education->education_performance_optional) && !empty($education->education_performance_optional)){ if($education->education_performance_scale_optional==1) {echo "Percentage : ".$education->education_performance_optional;} elseif($education->education_performance_scale_optional==2) {echo "CGPA : ".$education->education_performance_optional;}}?> <?php if(isset($education->education_performance_scale_optional) && !empty($education->education_performance_scale_optional) && ($education->education_performance_optional) && !empty($education->education_performance_optional)){ if($education->education_performance_scale_optional==1){echo "%";}else{echo "(Scale of 10)";}}else{echo "";}?></li><?php }?>
                                    </ul>
                                    </div>

                                <?php } ?>
                            </div>
                            <?php if (!isset($apply_internship_details)) {
                                //$session->set($ses_data); 
                            ?>
                                <a href="<?= base_url(); ?>/can_profile_edit/3/<?php echo $profile_company_logo ?>/<?php echo $profile_company_name ?>/<?php echo $profile ?>/<?php echo $internship_details->internship_id ?>" class="text-blue editAb"><i class="fa fa-pencil" aria-hidden="true"></i></a>
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
                                <a href="<?= base_url(); ?>/can_profile_edit/4/<?php echo $profile_company_logo ?>/<?php echo $profile_company_name ?>/<?php echo $profile ?>/<?php echo $internship_details->internship_id ?>" class="text-blue editAb"><i class="fa fa-pencil" aria-hidden="true"></i></a>
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
                                    <div class="col-12 col-md-6 pb-4">
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
                                <a href="<?= base_url(); ?>/can_profile_edit/5/<?php echo $profile_company_logo ?>/<?php echo $profile_company_name ?>/<?php echo $profile ?>/<?php echo $internship_details->internship_id ?>" class="text-blue editAb"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <?php } ?>
                        </div>
                    <?php } ?>

                </div>
                <BR>
                <?php if (empty($experience_details)) {?>
                    <a href="<?= base_url(); ?>/can_profile_edit/2/<?php echo $profile_company_logo ?>/<?php echo $profile_company_name ?>/<?php echo $profile ?>/<?php echo $internship_details->internship_id ?>" class="btn btn-outlined-blue px-3 mb-1 me-1"><i class="fa fa-plus me-2" aria-hidden="true"></i>Add Experience</a>
                <?php }?>
                <?php if (empty($skill_details)) {?>
                    <a href="<?= base_url(); ?>/can_profile_edit/4/<?php echo $profile_company_logo ?>/<?php echo $profile_company_name ?>/<?php echo $profile ?>/<?php echo $internship_details->internship_id ?>" class="btn btn-outlined-blue px-3 mb-1 me-1"><i class="fa fa-plus me-2" aria-hidden="true"></i>Add Skill</a>
                <?php }?>
                <?php if (empty($work_sample->blog_link) && empty($work_sample->github_profile) && empty($work_sample->play_store_developer) && empty($work_sample->behance_portfolio_link) && empty($work_sample->other_work_sample_link)) {?>
                    <a href="<?= base_url(); ?>/can_profile_edit/5/<?php echo $profile_company_logo ?>/<?php echo $profile_company_name ?>/<?php echo $profile ?>/<?php echo $internship_details->internship_id ?>" class="btn btn-outlined-blue px-3 mb-1"><i class="fa fa-plus me-2" aria-hidden="true"></i>Add work samples</a>
                <?php }?>
            </div>
        </div>
    </section>
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
                                            <label for="" class="form-label">Enter Reason</label>
                                            <textarea maxlength="500" id="reject_reson" name="reject_reson" class="form-control filledBox border-0 py-2 f-14" placeholder="Reason for Cancel Offer (max 500 char)" style="height: 100px;"></textarea>
                                             <font style="color:#dd4b39;"><div id="reject_reson_alert"></div></font> 
                                             
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
    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>

    <?php
    $session->remove('complete_popup');
    require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        // function func_edit_personal(val){

        //     // alert(val);
        // //    var profile_complete_status= $("#profile_complete_status").val();
        // //    var company_logo= $("#company_logo").val();
        // //    var company_name= $("#company_name").val();
        // //    var intership_profile= $("#intership_profile").val();
        // //    var intership_number= $("#intership_number").val();
        // }
    </script>
 <script>
     function previous() {
            window.history.go(-1);
        }
        //accept internship hiring
        function confirm_accept(id)
        {
            //check already confirmed

            var csrftokenname = "csrf_test_name=";
            var csrftokenhash = $(".csrf").val();  
        
          $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('Check-Already-Confirmed'); ?>",
                  dataType:'JSON',
                  data:"internship_id="+id+ "&" + csrftokenname + csrftokenhash,
                  success: function(resp){
                     
                    $(".csrf").val(resp.csrf); 

                    if (resp.result==0) 
                    {
                         swal({
                      title: "Are you sure?",
                      text: "You want to accept this offer!",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonClass: "btn-warning",
                      confirmButtonText: "ok",
                      closeOnConfirm: false
                    },
                    function(){
                        window.location.href="<?php echo base_url('/Accept-Hiring'); ?>/1/"+id;
                      
                    });
                    }else 
                    {
                          swal({
                            text: "Cancel your accepted internship, to accept new internship offer",
                            type: "info",
                            showCancelButton: false,
                            confirmButtonClass: "btn-warning",
                            confirmButtonText: "ok",
                            closeOnConfirm: false
                                });
                    }
                   
                    
                    },
                  error: function(e){ 
                 
                  alert('Error: ' + e.responseText);
                  return false;  

                  }
                  });

          
        
      }
       //reject internship hiring
        function confirm_reject(id)
        {        
        swal({
              title: "Are you sure?",
              text: "You want to reject this offer!",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-danger",
              confirmButtonText: "ok",
              closeOnConfirm: true
            },
            function(){
                $("#reject_popup").modal('show');
                $("#reject_id").val(id);
               // window.location.href="<?php echo base_url('/Reject-Hiring'); ?>/"+id;
              
            });
      }
        $(document).ready(function() {
            $('#example').DataTable();
        });
       $(document).ready(function()
       {  
            $("#reject_submit").click(function()
            {
                var reject_reson = $("#reject_reson").val();
                if (reject_reson=='')
                 {
                    $("#reject_reson_alert").html('Cancel Reason Is Mandatory');
                    //$("#reject_reson_alert").addClass('alertMsg'); 
                    return false;
                 }else 
                 {
                    //special characters
                    var format = /[!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?]+/;
                    //check match with input value 
                    if(format.test(reject_reson))
                    { 
                      $("#reject_reson_alert").html("special characters not allowed");
                      //$("#reject_reson_alert").addClass('alertMsg'); 
                      return false;   
                    } 
                    else 
                    {
                        $("#reject_reson_alert").html('');
                    return true;
                    }
                    
                 }
            });
       });
        $(document).ready(function()
        {  
            $("#reject_reson").keyup(function()
            {

                var reject_reson = $("#reject_reson").val();
                if (reject_reson=='')
                 {
                    $("#reject_reson_alert").html('Cancel Reason Is Mandatory');
                    return false;
                 }else 
                 {
                    //special characters
                    var format = /[!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?]+/;
                    //check match with input value 
                    if(format.test(reject_reson))
                    { 
                      $("#reject_reson_alert").html("special characters not allowed");
                      //$("#reject_reson_alert").addClass('alertMsg'); 
                      return false;   
                    } 
                    else 
                    {
                        $("#reject_reson_alert").html('');
                    return true;
                    }
                 }
            });
       });
        
       function proceed_to_submit()
        {        
        swal({
              title: "Dear Student",
              text: "If you come across any corporate entity that is requesting payment / fee for internships or training, please know that this is against our principles. We are committed to providing you with legitimate and valuable opportunities.\n\n"+"Your concerns matter to us. If you encounter such instances, we urge you to report them. Kindly send us an email at crm@internme.app. Make sure to include the name of the company and provide details about the situation.",
              type: "warning",
            //   showCancelButton: true,
            //   confirmButtonClass: "btn-danger",
              confirmButtonText: "Continue",
              closeOnConfirm: true
            },
            function(isConfirm){
           
           if (isConfirm){
               document.getElementById("can_apply_internship_sub").submit();

}
       });
      }

    </script>
</body>

</html>