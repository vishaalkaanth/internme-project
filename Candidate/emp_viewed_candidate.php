<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>
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
<body>

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>

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
             
                <div class="card p-4">
                    <div class="d-flex flex-wrap justify-content-between mb-3 pb-2 border-bottom">
                        <h4 class="text-green fw-medium fs-4 mb-0">Corporates who viewed your profile</h4>
                    </div>
                    <ul class="ps-0 list-unstyled row row-cols-1 row-cols-md-2 g-2 g-md-3 viewedEmp">
                    <?php foreach ($view_profile_emp as $emp_lo) {  
                         $check = file_exists(FCPATH."public/assets/docs/uploads/emp_profile/".$emp_lo->profile_company_logo);?>
                        <li class="col">
                            <div class="d-flex total_enroll border rounded px-2 py-1 gap-2 align-items-center h-100">
                            <?php if($check){ ?>
                                    <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $emp_lo->profile_company_logo; ?>" alt="">
                                    <?php }else{ ?>
                                 
                                        <?php } ?>
                                        
                               <a href="<?= base_url(); ?>/employer-details/<?php echo $emp_lo->user_id; ?>" target="_blank"> <p class="text-dark mb-0"><?php echo $emp_lo->profile_company_name; ?></p></a>
                            
                            </div>
                        </li>
                    
                        <?php } ?>
                    </ul>
                    <?php if(count($view_profile_emp)>12){?>
                    <button id="next" type="button" class="btn-outlined-blue px-2 px-sm-3 align-self-center">See More</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        $(document).ready(function() {

            var list = $(".viewedEmp li");
            var numToShow = 12;
            var button = $("#next");
            var numInList = list.length;
            list.hide();
            if (numInList > numToShow) {
                button.show();
            }
            list.slice(0, numToShow).show();

            button.click(function() {
                var showing = list.filter(':visible').length;
                list.slice(showing - 1, showing + numToShow).fadeIn();
                var nowShowing = list.filter(':visible').length;
                if (nowShowing >= numInList) {
                    button.hide();
                    //button.text().replace("See More", "See Less");
                }
            });

        });
    </script>

</body>

</html>