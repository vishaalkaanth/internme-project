<!DOCTYPE html>
<html>

<style>
.loader { 
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #0ba38c;
  border-bottom: 16px solid #1576b4;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  z-index: 9999;
    position: fixed;
    top: 50%;
    left: 50%;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.loader_background
{
        background: #00000073;
    width: 100%;
    height: 100%;
    position: fixed;
    z-index: 999;
}

</style>
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
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<!-- <div class="loader_background" style="display: none;z-index: 99999;" id="loader_background"></div> -->
<div class="loader" style="display: none;z-index: 99999;" id="loader"></div>
<body class="">

    <?php require_once(APPPATH . "Views/Common/header.php"); 
     $session = session();
    //  print_r($_SESSION);
     $profile_complete_status=$session->get('profile_complete_status');
     $company_logo=$session->get('company_logo');
     $company_name=$session->get('company_name');
     $intership_profile=$session->get('intership_profile');
     $edit_profile=$session->get('edit_profile');
     $profile_page_view=$session->get('profile_page_view');

    ?>

    <!----- Form ------>
    
    <section class="empProfile">
        <div class="d-flex flex-wrap">
            <?php require_once(APPPATH . "Views/Common/profile_side.php");?>
            
            
            <div class="col-12 col-lg-9 profileRt d-flex justify-content-center p-lg-5 py-5 px-4">
                
                <div class="col-12 col-lg-10 align-self-start">
                    <!----- start Session Alert ------>
                    <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>
                <!----- End Session Alert ------>
                <?php if(isset($profile_complete_status) && ($profile_complete_status==1)){?>
                    <div class="card p-2 mb-4">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                <div class="comLogo d-flex justify-content-center align-items-center rounded p-1 me-3">
                            <?php if(!empty($company_logo)){ ?>
                                <?php 
                                        $check = file_exists(FCPATH."public/assets/docs/uploads/emp_profile/".$company_logo);
                                             ?>
                                             <?php if($check){ ?> 
                                <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $company_logo;?>" alt="logo" class="img-fluid noStretch" style="border-radius: 50%;" width="40">
                                <?php }else{ ?>
                                            <a class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                            <span><?php if (!empty($company_name)) {
                                                        echo $firstStringCharacter = substr($company_name, 0, 1);
                                                    } ?></span>
                                        </a>
                                    <?php } ?>
                                <?php }else { ?> 
                                    <a class="nav-link bg-primary rounded-50 text-white fw-bold fs-6" href="#" style="margin-left: 0px;">
                                        <span><?php if(!empty($company_name)){ echo $firstStringCharacter = substr($company_name, 0, 1);}?></span>
                                    </a>
                                    <?php } ?>
                            </div>
                                    <h3 class="fw-medium mb-0 fs-6">You Are Almost There - <?php if (isset($intership_profile)) {
                                                                                    echo $intership_profile;
                                                                                } ?> Internship At <?php if (isset($company_name)) {
                                                                                                                                                                        echo $company_name;
                                                                                                                                                                    } ?> </h3>
                                </div>

                            </div>

                        </div>
                <?php }?>
                
                <form action="<?= base_url(); ?>/update_can_personal_details" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data" >
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />    
                <input type="hidden" id="userid" name="userid"  value="<?php if(isset($profile_personal->userid)){echo $profile_personal->userid;}?>">
                <h2 class="fs-title text-blue fw-medium text-center mb-2">Personal Details</h2>  
                 <?php use App\Models\LoginModel;
                  $userModel = new LoginModel();
                  $where = array('userid' =>  $profile_personal->userid, 'can_profile_complete_status' => '1');
                $get_data_can = $userModel->fetch_table_data('can_personal_details', $where);
                if(empty($get_data_can) && ($profile_complete_status!=1)){ ?>
                <small class="text-dark badge-sticky-info text-start f-13 my-0 ">It takes exactly 93 seconds to fill your details and that's the time between you and your dream internship</small>
                <?php } ?>
                <div class="card p-4 mt-4">
                        
                <small class="text-dark badge-sticky text-start f-13 my-3">The name given here will be printed in your certificates, and cannot be changed in issued certificates</small>
                    <div class="d-flex flex-wrap row">
                            <!-- <div class="col-md-6 form-group">
                                <label for="" class="form-label">Full Name</label> <span style="color:red;">*</span>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_user.svg" alt="Name" width="14">
                                    </span>
                                    <input type="text" maxlength="50" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Name" id="add_profile_full_name" name="add_profile_full_name" value="<?php if(isset($profile_personal->profile_full_name)){echo $profile_personal->profile_full_name;}?>" onkeyup="profile_full_name()">
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_full_name_alert"></div></font>
                            </div> -->
                            

                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">First Name</label> <span style="color:red;">*</span>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_user.svg" alt="Name" width="14">
                                    </span>
                                    <input type="text" maxlength="50" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter First Name" id="add_profile_first_name" name="add_profile_first_name" value="<?php if(isset($user_profile_personal->candidate_firstname)){echo $user_profile_personal->candidate_firstname;}?>" onkeyup="profile_first_name()">
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_first_name_alert"></div></font>
                                
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Last Name</label> <span style="color:red;">*</span>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_user.svg" alt="Name" width="14">
                                    </span>
                                    <input type="text" maxlength="50" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Last Name" id="add_profile_last_name" name="add_profile_last_name" value="<?php if(isset($user_profile_personal->candidate_lastname)){echo $user_profile_personal->candidate_lastname;}?>" onkeyup="profile_last_name()">
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_last_name_alert"></div></font>
                            </div>
                            <!-- <small class="mb-3">The same will be printed in your certificates, and cannot be changed in future</small> -->
                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Gender</label> <span style="color:red;">*</span>
                                <input type="hidden" name="add_profile_gender_value" class="form-control" id="add_profile_gender_value" value="<?php if(isset($profile_personal->profile_gender)){echo $profile_personal->profile_gender;}?>">
                                <div class="d-flex flex-wrap mb-4">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" onclick="fucn_profile_gender(this.value)" id="add_profile_gender1" name="add_profile_gender" value="1" <?php if(isset($profile_personal->profile_gender) && $profile_personal->profile_gender=="1"){echo "checked";}?>>
                                        <label class="form-check-label f-14" for="add_profile_gender1">Male</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" onclick="fucn_profile_gender(this.value)" id="add_profile_gender2" name="add_profile_gender" value="2" <?php if(isset($profile_personal->profile_gender) && $profile_personal->profile_gender=="2"){echo "checked";}?>>
                                        <label class="form-check-label f-14" for="add_profile_gender2">Female</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" onclick="fucn_profile_gender(this.value)" id="add_profile_gender3" name="add_profile_gender" value="3" <?php if(isset($profile_personal->profile_gender) && $profile_personal->profile_gender=="3"){echo "checked";}?>>
                                        <label class="form-check-label f-14" for="add_profile_gender3">Transgender</label>
                                    </div>
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_gender_alert"></div></font>
                            </div>
                           
                            <div class="col-md-6 form-group">
                                 <div class="d-flex justify-content-between">
  
                                <label for="" class="form-label">Mobile Number <span style="color:red;">*</span></label>
                                <div id="verification_status_lab1"><span class="text-green"  ><i class="fa fa-check " aria-hidden="true"></i> verified</span></div> 
                                <input type="hidden" id="verification_status1" value="<?php if (isset($profile_personal->mobile_verify_status)) { if($profile_personal->mobile_verify_status == 1 ){ echo '1';} else{ echo '0';}  }  ?>">
                                        </div>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_phone.svg" alt="Phone" width="10">
                                    </span>
                                    <input type="text" maxlength="10" class="form-control filledBox border-0 py-2 f-14" placeholder="+91" id="add_profile_phone_number" name="add_profile_phone_number" value="<?php if(isset($profile_personal->profile_phone_number)){echo $profile_personal->profile_phone_number;}?>" onkeyup="profile_phone_number()">
                                    <button class="btn btn-green" type="button" id="send_otp1">Send OTP</button>
                                                    <button class="btn btn-green" type="button" disabled id="send_otp_loading1">
                                                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                      Sending...
                                                    </button>
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_phone_number_alert"></div></font>
                            </div>
                            
                            <!-- <div class="col-md-6 form-group">
                                <label for="" class="form-label">Date of Birth</label>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_date.svg" alt="DOB" width="14">
                                    </span>
                                    <input type="date" class="form-control filledBox border-0 py-2 f-14" placeholder="dd/mm/yyyy" max ="<?php echo date('Y-m-d');?>" id="add_profile_dob" name="add_profile_dob" value="<?php if(isset($profile_personal->profile_dob)){echo $profile_personal->profile_dob;}?>">
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_dob_alert"></div></font>
                            </div> -->
                           
                            <!-- <div class="col-md-6 form-group">
                                <label for="" class="form-label">Linkedin</label>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_in.svg" alt="linkedin" width="14">
                                    </span>
                                    <input type="text" class="form-control filledBox border-0 py-2 f-14" placeholder="Username" id="add_profile_linked_in" name="add_profile_linked_in" value="<?php if(isset($profile_personal->profile_linked_in)){echo $profile_personal->profile_linked_in;}?>">
                                </div>
                            </div> -->
                             <div class="col-md-6 form-group mb-2">
                             <div class="input-group mb-4">
                             <label for="" class="form-label">Current Location <span style="color:red;">*</span></label>
                                        <select style="width:100% !important"  id="location_full_name" name="location_full_name" class="js-states selectSearch form-control filledBox border-0 f-14">
                                            <option value="">Select Location</option>
                                            <?php if (!empty($master_location)) {
                                                foreach ($master_location as $st) { ?>
                                                 <option value="<?php echo $st->dist_id; ?>,<?php echo $st->dist_name; ?>,<?php echo $st->state_name; ?>" <?php if(isset($profile_personal->g_location_name) && $profile_personal->g_location_name==$st->dist_name){echo "selected";}?>><?php echo $st->dist_name; ?>, <?php echo $st->state_name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        </div>
                                        <font style="color:#dd4b39;">
                                    <div id="location_can_alert"></div>
                                </font>
                             </div>




                             

                            
                            
                            <div class="col-md-6 form-group">
                                <div class="d-flex justify-content-between">
                                <label for="" class="form-label">Email ID <span style="color:red;">*</span></label> 
                                <?php //if ($profile_personal->otp_status == '1') { ?>
                                            <div id="verification_status_lab"><span class="text-green" ><i class="fa fa-check " aria-hidden="true"></i> verified</span></div>
                                            <?php //} ?>
                                            <!-- <span class="text-green" id="verified_otp" style="display: none;"><i class="fa fa-check " aria-hidden="true"></i> verified</span> -->
                                            <input type="hidden" id="verification_status" value="<?php if (isset($profile_personal->email_verify_status) && !empty($profile_personal->profile_email)) { if($profile_personal->email_verify_status == 1 ){ echo '1';} else{ echo '0';}  }else{ echo '0';}  ?>">
                                        </div>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_mail.svg" alt="Email ID" width="14">
                                    </span>
                                    <input type="email" autocomplete="off" maxlength="50" class="form-control filledBox border-0 py-2 f-14 email" placeholder="Email ID"  id="add_profile_email" name="add_profile_email" value="<?php if(isset($profile_personal->profile_email)){echo $profile_personal->profile_email;}?>" onkeyup="profile_email()">
                                    <ul class="autosuffix ps-0"></ul>
                                    <?php // if(isset($profile_personal->otp_status) && $profile_personal->otp_status == '0'){ ?>
                                            <button class="btn btn-green" type="button"  id="send_otp">Send OTP</button>
                                            <button class="btn btn-green" type="button" id="send_otp_load" disabled>
                                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                              Sending...
                                            </button>
                                            <?php //} ?>
                                            <!-- <button class="btn btn-green" type="button"  id="verified_otp" style="display:none"> Verified</button> -->
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_email_alert"></div></font>
                            </div>
                            
                            <div class="col-md-6 form-group mb-2">
                             <div class="input-group mb-4">   
                                <label for="" class="form-label">Preferred Location(Max : 3 ) </label>
                                  <select  style="width:100% !important;"  id="work_location" name="work_location" class="js-states selectSearch form-control filledBox border-0 f-14" onchange="location_limit();new_location(this.value);">
                                            <option value="">Select Location</option>
                                            <?php if (!empty($master_location)) {
                                                foreach ($master_location as $st) { ?>
                                                 <option value="<?php echo $st->dist_name; ?>,<?php echo $st->dist_id; ?>" ><?php echo $st->dist_name; ?>, <?php echo $st->state_name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                </div>

                               


                                    <input type="hidden" id="total_location" value="<?php if (!empty($location)) {
                                                                                                echo count($location);
                                                                                            } else {
                                                                                                echo '0';
                                                                                            } ?>">
                                    
                                    <div class="chosenSkills mb-4">
                                    <ul class="ks-cboxtags list-unstyled p-0 d-flex flex-wrap" id="location_append_area">

                                    <?php $prefer_loc_array = ''; $ik=1;
                                            if (!empty($location)) {
                                                foreach ($location as $location_value) {
                                                    if($ik=='1')
                                                    {
                                                        $prefer_loc_array =  $prefer_loc_array.$location_value->g_location_id;
                                                    }else
                                                    {
                                                        $prefer_loc_array =  $prefer_loc_array.','.$location_value->g_location_id;
                                                    }
                                                    

                                            ?>


                                                    <li class="me-3 moveListLi"  onclick="remove_locationnew('<?php echo $location_value->g_location_id; ?>');" id="location_list_<?php echo $location_value->g_location_id; ?>"><label for="checkboxOne" class="text-gray"><?php echo $location_value->g_location_name; ?></label><input type="hidden" value="<?php echo $location_value->g_location_name; ?>" name="location_name[]"><input type="hidden" name="work_location[]" value="<?php echo $location_value->g_location_name; ?>"></li>
                                            <?php
                                               $ik++; }
                                            }
                                            ?>
                                    </ul>
                                    </div>
                                    <font style="color:#dd4b39;">
                                    <div id="work_location_alert"></div>
                                    </font>
                               
                                <!-- <label for="" class="form-label">Preferred Location <span style="color:red;">*</span></label>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_place2.svg" alt="GST number" width="13">
                                    </span>
                                    <input type="text" id="location_can" maxlength="15" name="location_full_name" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter location"    value="<?php if(isset($profile_personal->g_location_name)){ echo $profile_personal->g_location_name; } ?>" onblur="location_changes()" onkeyup="location_validation()">
                                    <input type="hidden" name="location_id" id="location_id" value="<?php if(isset($profile_personal->g_location_name)){ echo $profile_personal->g_location_id; } ?>"> 
                                    <input type="hidden" name="location_name" id="location_name" value="<?php if(isset($profile_personal->g_location_id)){ echo $profile_personal->g_location_name; } ?>">
                                    <input type="hidden" name="location_district" id="location_district" value="">
                                                <input type="hidden" name="location_state" id="location_state" value="">
                                   
                                </div>
                                <font style="color:#dd4b39;">
                                    <div id="location_can_alert"></div>
                                </font> -->
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="" class="form-label">Linkedin Profile URL</label>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_in.svg" alt="linkedin" width="14">
                                    </span>
                                    <input onkeyup="profile_linked_in()" type="text" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Linkedin URL" id="add_profile_linked_in" name="add_profile_linked_in" value="<?php if(isset($profile_personal->profile_linked_in)){echo $profile_personal->profile_linked_in;}?>" autocomplete="off">
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_linked_in_alert"></div></font>
                            </div>

 

                              
                            <!-- <div class="col-md-6 form-group">
                                <label for="" class="form-label">Photo</label>
                                <div class="input-group mb-4">
                                    <span class="input-group-text fillBg border-0">
                                        <img src="<?= base_url(); ?>/public/assets/img/icon_camera.svg" alt="logo" width="14">
                                    </span>
                                    <input type="file" id="add_profile_photo" name="add_profile_photo" class="form-control filledBox border-0 py-2 f-14" placeholder="Upload here">
                                    <input type="hidden" name="profile_photo_view" class="form-control" id="profile_photo_view" value="<?php if(isset($profile_personal->profile_photo)){echo $profile_personal->profile_photo;}?>">
                                </div>
                                <font style="color:#dd4b39;"><div id="add_profile_photo_alert"></div></font>
                                <?php if(isset($profile_personal->profile_photo)){if($profile_personal->profile_photo!=''){ ?>
                                <a href="<?= base_url(); ?>/public/assets/docs/uploads/can_profile_photo/<?php echo $profile_personal->profile_photo; ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                                <?php }}?>
                            </div> -->
                        </div>
                        </div>
                        <?php //if(isset($edit_profile) && ($edit_profile==1)){?>
                        <!-- <div class="d-flex justify-content-end mt-4"><input type="submit" class="btn btn-prim float-end" id="can_personal_submit" value="Save & Continue" /></div> -->
                        <?php //}else{?>
                            <?php if(isset($edit_profile) && ($edit_profile==1)){
                                    $ses_data = [
                                        'updated_status'=> 1
                                    ];
                                    $session->set($ses_data);
                                    $intership_id=$session->get('intership_number');
                                    $next_but_status=$session->get('next_but_status'); 
                                    if(isset($profile_personal->profile_email) && $profile_personal->profile_email!='') {
                                        // print_r($profile_personal); 
                                        if(isset($next_but_status) && $next_but_status=='1'){ ?>
                                            <div class="d-flex justify-content-end mt-4"><input type="submit" class="btn btn-prim float-end" id="can_personal_submit" value="Next" /></div>
                                      <?php  }else{
                                    ?>
                                    <div class="d-flex justify-content-end mt-4"><input type="submit" class="btn btn-prim float-end" id="can_personal_submit" value="Save & Continue" /></div>
                                   
                                    <?php } } else {
                                        ?>
                                        <div class="d-flex justify-content-end mt-4"><input type="submit" class="btn btn-prim float-end" id="can_personal_submit" value="Next" /></div>
                                    <?php } ?>
                                    <!-- <a href="<?= base_url(); ?>/can-apply-for-internship/<?= $intership_id; ?>" class="btn btn-prim float-end">Save & Continue</a> -->
                                    <?php  } elseif(isset($profile_page_view) && ($profile_page_view==1)){ ?>
                                        <div class="d-flex justify-content-end mt-4"><input type="submit" class="btn btn-prim float-end" id="can_personal_submit" value="Save" /></div>
                                       <?php } else{ ?>
                                        <div class="d-flex justify-content-end mt-4"><input type="submit" class="btn btn-prim float-end" id="can_personal_submit" value="Next" /></div>
                            <?php } 
                        // } ?>
                            
                           
                </form>
                </div>
                
            </div>
           
        </div>
    </section>
    <div class='offcanvas-backdrop fade show' id="side_bar_active" style="display: none;"></div>
    <div class="offcanvas offcanvas-end" id="offcanvas_id" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="false">
        <div class="offcanvas-header">
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close" onclick="close_cancel_click()"></button>
        </div>
        <div class="offcanvas-body text-center d-flex justify-content-center align-items-center flex-column">
            <h3 class="fw-bold text-blue mb-3">Enter OTP <span style="color:#dd4b39;">*</span></h3>
            <h6 class="fw-normal text-muted" id="otp_alert">We've sent an OTP to your registered mobile number</h6>
            <div class="form-group w-100">
                    <input type="text" id="enter_otp" name="enter_otp" class="form-control f-14 py-2 mt-3 mb-4" placeholder="Enter OTP" maxlength="6"  >
                     <font style="color:#dd4b39;"><div id="enter_otp_alert" style="margin-top: -15px;"></div></font>

                                <div class="btnGroup" style="margin-top: 31px">
                                    <span class="timer">
                                  <span id="counter" style="    margin-right: 40px;"></span>
                                  <a class="text-blue me-3" style="display: none;" id="show_resend" onclick="resend_otp()">Resend</a> 
                                </span>
                                <span class="Btn" id="verifiBtn">
                                  <button type="button" class="btn btn-prim" value="Verify OTP" id="otp_verify">Verify OTP</button>
                                </span>
                                
                              </div><br>
                    
                     <button type="button" class="btn btn-gray" data-bs-dismiss="offcanvas" id="close_btn" style="display: none;" onclick="cancel_click()">Close</button> 
            </div>
        </div>
    </div>


    <div class='offcanvas-backdrop fade show' id="side_bar_active1" style="display: none;"></div>
                        <div class="offcanvas offcanvas-end" id="offcanvas_id1" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="false">
                            <div class="offcanvas-header">
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"  onclick="close_cancel_click1()"></button>
                            </div>
                            <div class="offcanvas-body text-center d-flex justify-content-center align-items-center flex-column">
                                <h3 class="fw-bold text-blue mb-3">Enter OTP <span style="color:#dd4b39;">*</span></h3>
                                <h6 class="fw-normal text-muted" id="otp_alert1">We've sent an OTP to your registered mobile number</h6>
                                <div class="form-group w-100">
                                    <input type="text" id="enter_otp1" name="enter_otp1" class="form-control filledBox border-0 py-2 f-14 mb-4" maxlength="6" placeholder="Enter OTP" value="">
                                    <font style="color:#dd4b39;">
                                        <div id="enter_otp_alert1"></div>
                                    </font>

                                     <div class="btnGroup">
                                    <span class="timer">
                                  <span id="counter1" style="    margin-right: 40px;"></span>
                                  <a type="button" class="text-blue me-3" style="display: none;" id="show_resend1" onclick="resend_otp1()">Resend</a> 
                                </span>
                                <span class="Btn" id="verifiBtn">
                                  <button type="button" class="btn btn-prim" value="Verify OTP" id="otp_verify1">Verify OTP</button>
                                </span>
                                
                              </div>
                    
                         <button type="button" class="btn btn-gray" data-bs-dismiss="offcanvas" id="close_btn" style="display: none;" onclick="cancel_click1()">Close</button> 

                                    <!-- <button type="button" class="btn btn-prim me-2" value="Verify OTP" id="otp_verify1">Verify OTP</button>
                                    <button type="button" class="btn btn-prev" data-bs-dismiss="offcanvas">Close</button> -->
                                </div>
                            </div>
                        </div>
    
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script> 
$(document).ready(function(){  
   
    <?php if($profile_personal->email_verify_status == 0 && empty($profile_personal->profile_email)){  ?>
		
        $('#send_otp').show();
		$('#send_otp_load').hide();
        $('#verification_status_lab').hide();
        
        
        <?php } else{ ?>
           
            $('#send_otp').hide();
		$('#send_otp_load').hide();
        $('#verification_status_lab').show();
        
            <?php } ?>
<?php if (isset($profile_personal->mobile_verify_status)) { if($profile_personal->mobile_verify_status == 0 ){  ?>
           $('#send_otp1').show();
           $('#send_otp_loading1').hide();
           $('#verification_status_lab1').hide();
           <?php } else{ ?>
           $('#send_otp1').hide();
           $('#send_otp_loading1').hide();
           $('#verification_status_lab1').show();
               <?php } } ?>
});
         //validate location
        // function location_validation()
        // {
        //     var location_can = $('#location_can').val(); 

        //     if (location_can=='')
        //      {
        //         $("#location_can_alert").text("Location Is Mandatory");
        //          $("#location_can_alert").addClass('alertMsg');    
        //      }else 
        //      {

        //      //special characters
        //         var format = /[!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?]+/;//!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?
        //         //check match with input value 
        //         if(format.test(location_can)){ 

        //           $("#location_can_alert").text("Special Characters Not Allowed");
        //          $("#location_can_alert").addClass('alertMsg');                     
        //         } else {                   
        //                 $('#location_can_alert').text('');                         
                   
        //         } 
        //     }           
        // }
    function fucn_profile_gender(value){
         $('#add_profile_gender_value').val(value);
         $("#add_profile_gender_alert").html('');
    }
    function profile_first_name() {
        return validatextname_style('add_profile_first_name','add_profile_first_name_alert','First Name'); 
            }

            function profile_last_name() {
        return validatextname_style('add_profile_last_name','add_profile_last_name_alert','Last Name'); 
            }
            function profile_linked_in() {
                $("#add_profile_linked_in_alert").addClass('alertMsg');
                return validlinked_in('add_profile_linked_in','add_profile_linked_in_alert','Linked In'); 
            }
    function profile_email() {
            
            var csrftokenname = "csrf_test_name=";
            var csrftokenhash = $(".csrf").val();
            var userid = $('#userid').val();
            var add_profile_email = $('#add_profile_email').val();
           
            $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('get_can_mobile_email_edit'); ?>",
                  data:"userid="+encodeURIComponent(userid) +"&" + csrftokenname + csrftokenhash,
                  success: function(resp){

                    // alert(resp);
                    var splitted_data = resp.split("^");
			        $(".csrf").val(splitted_data[2]); 
                    var add_profile_email_old = splitted_data[1];
                    if(add_profile_email!=''){
                       
                    if(add_profile_email!=add_profile_email_old){
                        $('#send_otp').show();
                        $('#verification_status').val(0);
                        // $('#can_personal_submit').attr('disabled','disabled');
                        $('#verification_status_lab').hide();
                        return validemailid_style('add_profile_email','add_profile_email_alert','Email ID');
                    }else{
                        $('#send_otp').hide();
                        $('#verification_status').val(1);
                        $('#verification_status_lab').show();
                        // $('#can_personal_submit').removeAttr('disabled');
                        return validemailid_style('add_profile_email','add_profile_email_alert','Email ID');
                    }
                }else{
                    $('#send_otp').show();
                    $('#verification_status_lab').hide();
                }
                    },
                  error: function(e){ 
                 
                  //alert('Error: ' + e.responseText);
                  return false;  

                  }
                  });
            
            }
    function profile_phone_number() {
        var csrftokenname = "csrf_test_name=";
            var csrftokenhash = $(".csrf").val();
            var userid = $('#userid').val();
            var profile_mobile = $('#add_profile_phone_number').val();
        //    alert(profile_mobile);
            $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('get_can_mobile_email_edit'); ?>",
                  data:"userid="+encodeURIComponent(userid) +"&" + csrftokenname + csrftokenhash,
                  success: function(resp){

                    //  alert(resp);
                    var splitted_data = resp.split("^");
			        $(".csrf").val(splitted_data[2]); 
                    var profile_mobile_old = splitted_data[0];
                    if(profile_mobile!=profile_mobile_old){
                        $('#send_otp1').show();
                        $('#verification_status1').val(0);
                        // $('#org_submit').attr('disabled','disabled');
                        $('#verification_status_lab1').hide();
                        return validmobile_style('add_profile_phone_number','add_profile_phone_number_alert','Mobile Number');
                    }else{
                        $('#send_otp1').hide();
                        $('#verification_status1').val(1);
                        $('#verification_status_lab1').show();
                        // $('#org_submit').removeAttr('disabled');
                        return validmobile_style('add_profile_phone_number','add_profile_phone_number_alert','Mobile Number');
                    }
                    },
                  error: function(e){ 
                 
                //   alert('Error: ' + e.responseText);
                  return false;  

                  }
                  });
        
            }
    $(document).ready(function() {
            $("#add_profile_gender_value").change(function() {
                return validatetext_style('add_profile_gender_value','add_profile_gender_alert','Gender');
            });
         $("#location_can").keyup(function() {

             // var location_can =  $("#location_can").val();
                 
             //    if (location_can=='')
             //     {

             //        $("#location_id").val('');
             //     }

                //return validatetext_style('location_can', 'location_can_alert', 'Location');


            });
            // $("#add_profile_dob").change(function() {
            //     return validatetext_style('add_profile_dob','add_profile_dob_alert','Date of Birth');
            // });
        });
    $(document).ready(function(){  
  
        $("#can_personal_submit").click(function(){
       
        // var add_profile_dob    = validatetext_style('add_profile_dob','add_profile_dob_alert','Date of Birth');
        var add_profile_email    = validemailid_style('add_profile_email','add_profile_email_alert','Email ID');
        var add_profile_phone_number    = validmobile_style('add_profile_phone_number','add_profile_phone_number_alert','Mobile Number');
        var add_profile_gender    = validatetext_style('add_profile_gender_value','add_profile_gender_alert','Gender');
        var add_profile_last_name    = validatextname_style('add_profile_last_name','add_profile_last_name_alert','Last Name');
        var add_profile_first_name    = validatextname_style('add_profile_first_name','add_profile_first_name_alert','First Name');

        var add_profile_linked_in    =  validlinked_in('add_profile_linked_in','add_profile_linked_in_alert','Linked In');

        var location_can    = validatetext_style('location_full_name','location_can_alert','Location');
        // var location_id    = validatetext_style('location_id','location_can_alert','Location');
        
        var verification_status= $("#verification_status").val();
        var add_profile_email_val= $("#add_profile_email").val();
        // alert(add_profile_email_val);
        if(add_profile_email_val!=''){
        if (verification_status==0)
         { 
             $("#add_profile_email_alert").html("Please Verify Email ID");
             $("#add_profile_email_alert").addClass('alertMsg'); 
            var verification_status=0;
         }else{
            var verification_status=1;
         }
        }else{
            // alert(1);
           var verification_status=1;
        }

         var verification_status1= $("#verification_status1").val();
        if (verification_status1==0)
         { 
             $("#add_profile_phone_number_alert").html("Please Verify Mobile Number");
             $("#add_profile_phone_number_alert").addClass('alertMsg'); 
         }

        //  var location_id= $("#location_id").val();
        // if (location_id=='')
        //  { 
        //      $("#location_can_alert").html("Location Is Mandatory");
        //      $("#location_can_alert").addClass('alertMsg'); 
        //  }
// alert(verification_status);

                
            // if(verification_status == 0||verification_status1 == 0 || add_profile_email==0 ||add_profile_first_name==0 ||add_profile_last_name==0 ||add_profile_phone_number==0 ||add_profile_gender==0 || location_can==0 || location_id==0)
            if(verification_status == 0||verification_status1 == 0 || add_profile_email==0 ||add_profile_first_name==0 ||add_profile_last_name==0 ||add_profile_phone_number==0 ||add_profile_gender==0 || add_profile_linked_in==0 || location_can==0)
            {
                return false;
            } 
        });
        });

        $(document).ready(function() {
      $("#send_otp").click(function() {
         // $('#loader').show();
         // $('#loader_background').show();
        // $('#offcanvasRight').show;
        var csrftokenname = "csrf_test_name=";
		var csrftokenhash = $(".csrf").val();
        $("#enter_otp").val('');
        var userid = $('#userid').val();
        var email = $('#add_profile_email').val();
        var number = $('#add_profile_phone_number').val();
        var add_profile_email    = validemailid_style('add_profile_email','add_profile_email_alert','Email ID');
        // var add_profile_phone_number    = validmobile_style('add_profile_phone_number','add_profile_phone_number_alert','Mobile Number');
        // var add_profile_gender    = validatetext_style('add_profile_gender_value','add_profile_gender_alert','Gender');
        // var profile_full_name    = validatextname_style('add_profile_full_name','add_profile_full_name_alert','Full Name');

         if (add_profile_email == 0 ) {

          return false;
     }
     $('#send_otp_load').show();
     $('#send_otp').hide();
         countdown();
          $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('can_mobile_send_otp'); ?>",
                  data:"&userid="+encodeURIComponent(userid)+ "&email=" + encodeURIComponent(email)+ "&user_type=1" + "&number=" + encodeURIComponent(number)+ "&" + csrftokenname + csrftokenhash,
                  success: function(resp){

                    $('#send_otp_load').hide();
                    // alert(resp);
                    // return false;
                    var splitted_data = resp.split("^");
			        $(".csrf").val(splitted_data[1]); 
                    if(splitted_data[0]!='0'){
                        if (splitted_data[3]!=2) 
                        {
                        $('#offcanvas_id').offcanvas('show');
                       $('#side_bar_active').show();
                        }
                        if (splitted_data[3]==1) 
                        {
                            $("#enter_otp_alert").html(splitted_data[2]);
                            $("#enter_otp_alert").addClass('alertMsg');
                            $("#otp_alert").html('');
                            $('#close_btn').show();
                             $('.btnGroup').hide();


                        }else if (splitted_data[3]==2) 
                        {
                            $("#add_profile_email_alert").html('Email ID Is Already Registered. Please Enter A Different Email ID.');
                            $("#add_profile_email_alert").addClass('alertMsg');
                            $("#add_profile_email").val('');
                            //$('#send_otp').prop('disabled', true);
                            // $('#add_profile_email').prop('readonly', false);
                            $('#send_otp').show();
                            $('#verification_status_lab').hide();

                        }else 
                        {
                            $("#otp_alert").html(splitted_data[2]);
                            // $("#otp_alert").addClass('alertMsg');
                        }
                       // $('#add_profile_email').prop('readonly', true);
                        //$("#enter_otp").val(splitted_data[0]);
                        // $('#offcanvasRight').show;//now its working
                        //alert(splitted_data[2]);
                    }else{
                        if(splitted_data[3]=='5'){
                            $('#send_otp_load').hide();
                            swal({
                title: '',
                text: splitted_data[2],
                showCancelButton: false,
                confirmButtonClass: "btn-warning",
                confirmButtonText: "Close",
                closeOnConfirm: false
            });
                            // alert(splitted_data[2]);
                        }else{
                        location.reload();
                        }
                    }
                    //$('#can_personal_submit').removeAttr('disabled');
                    // $('#verification_status_lab').show();
                    // $('#loader').hide();
                    // $('#loader_background').hide();
                    },
                //   error: function(e){ 
                 
                //   alert('Error: ' + e.responseText);
                //   return false;  

                //   }
                  });

      });
    });

         $(document).ready(function() {
      $("#enter_otp").keyup(function() {

         var user_otp = $('#enter_otp').val();

         if (isNaN(user_otp))
           { 
            $("#enter_otp_alert").html('Enter a valid number');
            $("#enter_otp_alert").addClass('alertMsg');
          }else 
          {
            $("#enter_otp_alert").html('');
          }
          });
    });

    $(document).ready(function() {
      $("#enter_otp1").keyup(function() {

         var user_otp = $('#enter_otp1').val();

         if (isNaN(user_otp))
           { 
            $("#enter_otp_alert1").html('Enter a valid number');
            $("#enter_otp_alert1").addClass('alertMsg');
          }else 
          {
            $("#enter_otp_alert1").html('');
          }
          });
    });

    $(document).ready(function() {
      $("#otp_verify").click(function() {

        var user_otp1 = validatetext_style('enter_otp', 'enter_otp_alert','OTP');

      if (user_otp1 == 0) {
          $("#enter_otp_alert").html("<i class='fa fa-info-circle' aria-hidden='true'></i> Please enter the OTP.");
          $("#enter_otp_alert").addClass('alertMsg');
          return false;
        }
        var csrftokenname = "csrf_test_name=";
		var csrftokenhash = $(".csrf").val();
 
          var user_otp = $('#enter_otp').val();
          var userid = $('#userid').val();
          var email = $('#add_profile_email').val();
          $.ajax({
            type: "POST",
                  url: "<?php echo base_url('can_mobile_otp_verify'); ?>",
                  data:"&user_otp="+encodeURIComponent(user_otp)+"&email="+encodeURIComponent(email)+"&userid="+encodeURIComponent(userid)+ "&" + csrftokenname + csrftokenhash,
                  success: function(resp){
                    var splitted_data = resp.split("^");
			        $(".csrf").val(splitted_data[1]); 
                    if(splitted_data[0]=='1'){
                        $('#offcanvas_id').offcanvas('hide');
                        $('#side_bar_active').hide();
                        $("#send_otp").hide();
                        $('#verified_otp').show();
                        // $('#add_profile_email').prop('readonly', true);
                        $('#verification_status').val(1);
                        $('#verification_status_lab').show();
                        // $("#otp_success").val('1');
                        // document.getElementById("profile_mobile").readOnly = true;
                    }else{
                        $("#enter_otp_alert").html("<i class='fa fa-info-circle' aria-hidden='true'></i> OTP is Invalid");
                        $("#enter_otp_alert").addClass('alertMsg');
                    }
                    },
                  error: function(e){ 
                  // swal("","Refresh This Page","warning"); 
                  alert('Error: ' + e.responseText);
                  return false;  

                  }
                  });

      });
    });


    
    </script>
<!-- --------------------------------------------------------location API start here---------------------------------------------------- -->
<script type="text/javascript">

//set lag log here
const center = { lat: 39, lng: -112 };
//Create a bounding box with sides ~10km away from the center point
const defaultBounds = {
  north: center.lat + 0.001,
  south: center.lat - 0.001,
  east: center.lng + 0.001,
  west: center.lng - 0.001,
};
//get input value
const input = document.getElementById("location_can");
const options = {
                   bounds: defaultBounds,
                  componentRestrictions: { country: "in" },//result wise country
                  fields: ["address_components", "geometry",  "place_id", "name"],  //output
                  types: ["locality"],  //showing result
                  
                };
const autocomplete = new google.maps.places.Autocomplete(input, options);
//if place choosed
autocomplete.addListener('place_changed', function() 
                                                   {
                                                        const place = autocomplete.getPlace();
                                                        $.each(place.address_components, function (key, value)
     { 
        if (value.types[0] == "route")
        { 
            itemRoute = value.long_name;
            //  alert('Route : '+itemRoute);
        }
         if (value.types[0] == "locality"){ 
        itemLocality = value.long_name;
        // alert('Locality : '+itemLocality);
        }

         if (value.types[0] == "administrative_area_level_3"){ 
        itemLocality = value.long_name;
        $('#location_district').val(itemLocality); //get district name
                                                      
        // alert('District : '+itemLocality);
        }
         if (value.types[0] == "administrative_area_level_1"){ 
        itemLocality = value.long_name;
        $('#location_state').val(itemLocality); //get location name
        // alert('State : '+itemLocality);
        }


        if (value.types[0] == "country"){  
            itemCountry = value.long_name;
            // alert('Country : '+itemCountry);
        }

        if (value.types[0] == "postal_code_prefix"){  
            itemPc = value.long_name;
            // alert('postal code : '+itemPc);
        }

        if (value.types[0] == "street_number"){   
            itemSnumber = value.long_name;
            // alert('street number : '+itemSnumber);
        }

         
   
   });
                                                        $('#location_id').val(place.place_id);//get place id
                                                        $('#location_name').val(place.name); //get location name
                                                    });

 //location id remove when location change
  function location_changes()
  {
    var location_can  = $('#location_can').val();
    var location_name = $('#location_name').val();
   //  alert(location_can);
    if (!location_can.includes(location_name))
     {
        $('#location_id').val('');
        //$('#location_can_alert').html('Plese Choose a Location');
     }else 
     {
        //$('#location_can_alert').html('');
     }
  }
 
 
</script>
<!-- --------------------------------------------------------location API end here---------------------------------------------------- -->

 <script type="text/javascript">
    var countdown_settimeout;
       function countdown() {
        var seconds = 30;
        function tick() {
          var counter = document.getElementById("counter");
          seconds--;
          counter.innerHTML =
            "0:" + (seconds < 10 ? "0" : "") + String(seconds);
          if (seconds > 0) {
            countdown_settimeout=setTimeout(tick, 1000);
          } else {
            $("#show_resend").show();
            document.getElementById("counter").innerHTML = "";
          }
        }
        tick();
      }

      var countdown1_settimeout;

      function countdown1() {
        var seconds = 30;
        function tick() {
          var counter = document.getElementById("counter1");
          seconds--;
          counter.innerHTML =
            "0:" + (seconds < 10 ? "0" : "") + String(seconds);
          if (seconds > 0) {
            countdown1_settimeout=setTimeout(tick, 1000);
          } else {
            $("#show_resend1").show();
            document.getElementById("counter1").innerHTML = "";
          }
        }
        tick();
      }
      // countdown();

  //resend otp 
  
  function resend_otp()
  {
         // $('#loader').show();
         // $('#loader_background').show();

        var csrftokenname = "csrf_test_name=";
        var csrftokenhash = $(".csrf").val();
        var userid = $('#userid').val();
        var email = $('#add_profile_email').val();
        var number = $('#add_profile_phone_number').val();
        
        $('#show_resend').hide();
        countdown();
          $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('can_mobile_send_otp'); ?>",
                  data:"&userid="+encodeURIComponent(userid)+ "&email=" + encodeURIComponent(email)+ "&number=" + encodeURIComponent(number)+ "&" + csrftokenname + csrftokenhash,
                  success: function(resp){
                    // alert(resp);
                    var splitted_data = resp.split("^");
                    $(".csrf").val(splitted_data[1]); 
                    if(splitted_data[0]!='0'){
                        $('#offcanvas_id').offcanvas('show');
                        
                        if (splitted_data[3]==1) 
                        {
                            $("#enter_otp_alert").html(splitted_data[2]);
                            $("#otp_alert").html('');
                            $('#close_btn').show();
                             $('.btnGroup').hide();
                             

                        }else 
                        {
                            $("#otp_alert").html(splitted_data[2]);
                        }
                        //$("#enter_otp").val(splitted_data[0]);
                        // $('#offcanvasRight').show;//now its working
                        //alert(splitted_data[2]);
                    }else{
                        location.reload();
                    }
                     // $('#loader').hide();
                     // $('#loader_background').hide();
                    },
                  error: function(e){ 
                 
                  alert('Error: ' + e.responseText);
                  return false;  

                  }
                  });


  }  


  
   // $('.row-offcanvas').toggleClass('active')
  
 function cancel_click()
 {
    $('#side_bar_active').hide();
    $('#send_otp').show();

 }

 function cancel_click1()
 {
    $('#side_bar_active1').hide();
    $('#send_otp1').show();

 }
 function close_cancel_click()
 {
    $('#side_bar_active').hide();
    $('#send_otp').show();
    $('#verification_status_lab').hide();
    clearTimeout(countdown_settimeout);

 }

 function close_cancel_click1()
 {
    $('#side_bar_active1').hide();
    $('#send_otp1').show();
    $('#verification_status_lab1').hide();
    clearTimeout(countdown1_settimeout);
}
 

 $(document).ready(function() {
            $("#send_otp1").click(function() {
                var csrf_val = $(".csrf").val();
                var csrf = "&csrf_test_name=" + csrf_val;
                // $('#offcanvasRight').show;
                $("#enter_otp1").val('');
                var user_id = $('#userid').val();
                var mobile = $('#add_profile_phone_number').val();
              
                var profile_mobile = validmobile_style('add_profile_phone_number', 'add_profile_phone_number_alert', 'Mobile Number');



                if ( profile_mobile == 0) {

                    return false;
                }
                 countdown1();
                $('#send_otp_loading1').show();
                $('#send_otp1').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('can_profile_mobile_otp'); ?>",
                    data: "&user_id=" + encodeURIComponent(user_id)+ "&mobile=" + encodeURIComponent(mobile) + csrf,
                    success: function(resp) {
                        // alert(resp);
                        $('#send_otp_loading1').hide();
                        var splitted_data = resp.split('^');
                       
                        $(".csrf").val(splitted_data[1].trim())
                        if (splitted_data[0] == '1') {
                            $('#offcanvas_id1').offcanvas('show');
                            $('#side_bar_active1').show();
                            $("#enter_otp_alert1").html("");
                            //$("#enter_otp").val(splitted_data[1]);
                        } else if (splitted_data[0] == '2'){
                            $("#add_profile_phone_number_alert").html("<i class='fa fa-info-circle' aria-hidden='true'></i> Mobile Number Is Already Registered. Please Enter A Different Mobile Number.");
                            $("#add_profile_phone_number_alert").addClass('alertMsg');
                            // $('#profile_mobile').prop('readonly', false);
                            $('#add_profile_phone_number').val('');
                            $('#send_otp1').show();
                            return false;
                            // location.reload();
                        } 

                        if (splitted_data[3]==1) 
                        {
                            $("#enter_otp_alert1").html(splitted_data[2]);
                            $("#enter_otp_alert1").addClass('alertMsg');
                            $("#otp_alert1").html('');
                            $('#close_btn1').show();
                             $('.btnGroup').hide();


                        }else 
                        {
                            $("#otp_alert1").html(splitted_data[2]);
                            // $("#otp_alert1").addClass('alertMsg');
                        }
                         
                         //$('#verified_otp').show();

                        // if(resp!='0'){
                        //     $('.offcanvas').offcanvas('show');
                        //     $("#enter_otp").val(resp);
                        //     // $('#offcanvasRight').show;//now its working

                        // }else{
                        //     location.reload();
                        // }
                        // $('#org_submit').removeAttr('disabled');
                        $('#verification_status_lab1').show();
                    },
                    error: function(e) {

                        alert('Error: ' + e.responseText);
                        return false;

                    }
                });

            });
        });

        $(document).ready(function() {
            $("#otp_verify1").click(function() {
                var csrf_val = $(".csrf").val();
                var csrf = "&csrf_test_name=" + csrf_val;
                var user_otp1 = validatetext_style('enter_otp1', 'enter_otp_alert1');

                if (user_otp1 == 0) {
                    $("#enter_otp_alert1").html("<i class='fa fa-info-circle' aria-hidden='true'></i> Please enter the OTP.");
                    // $("#enter_otp_alert1").addClass('alertMsg');
                    return false;
                }


                user_otp = $('#enter_otp1').val();
                user_id = $('#userid').val();
                mobile = $('#add_profile_phone_number').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('mobile_otp_verify_edit'); ?>",
                    data: "&user_otp=" + encodeURIComponent(user_otp) + "&user_id=" + encodeURIComponent(user_id) + "&mobile=" + encodeURIComponent(mobile) + csrf,
                    success: function(resp) {
                        var splitted_data = resp.split('^');
                        $(".csrf").val(splitted_data[0].trim())
                        if (splitted_data[1] == '1') {
                            $('#offcanvas_id1').offcanvas('hide');
                            $('#side_bar_active1').hide();
                            $("#send_otp1").hide();
                            $("#verified_otp1").show();
                            // $('#profile_mobile').prop('readonly', true);
                            $("#otp_success1").val('1');
                            $('#verification_status1').val(1);
                            // document.getElementById("profile_mobile").readOnly = true;
                        } else {
                            $("#enter_otp_alert1").html("<i class='fa fa-info-circle' aria-hidden='true'></i> OTP Is Invalid");
                            // $("#enter_otp_alert1").addClass('alertMsg');
                            return false;
                        }
                        // if(resp=='1'){
                        //     $('.offcanvas').offcanvas('hide');
                        //     $("#send_otp").hide();
                        //     $("#verified_otp").show();
                        //     $("#otp_success").val('1');
                        //     document.getElementById("profile_mobile").readOnly = true;
                        // }else{
                        //     $("#enter_otp_alert").html("OTP Invalid");
                        // }
                    },
                    error: function(e) {
                        // swal("","Refresh This Page","warning"); 
                        alert('Error: ' + e.responseText);
                        return false;

                    }
                });

            });


        });

        function resend_otp1()
  {
         // $('#loader').show();
         // $('#loader_background').show();

        var csrftokenname = "csrf_test_name=";
        var csrftokenhash = $(".csrf").val();
        var user_id = $('#userid').val();
        var email = $('#add_profile_email').val();
        var mobile = $('#add_profile_phone_number').val();
        // alert(number);
        $('#show_resend1').hide();
        countdown1();
          $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('can_profile_mobile_otp'); ?>",
                  data:"&user_id="+encodeURIComponent(user_id)+ "&email=" + encodeURIComponent(email)+ "&mobile=" + encodeURIComponent(mobile)+ "&" + csrftokenname + csrftokenhash,
                  success: function(resp){
                    // alert(resp);
                    var splitted_data = resp.split("^");
                    $(".csrf").val(splitted_data[1]); 
                    if(splitted_data[0]!='0'){
                        $('#offcanvas_id1').offcanvas('show');
                        
                        if (splitted_data[3]==1) 
                        {
                            $("#enter_otp_alert1").html(splitted_data[2]);
                            $("#otp_alert1").html('');
                            $('#close_btn1').show();
                             $('.btnGroup').hide();
                             

                        }else 
                        {
                            $("#otp_alert1").html(splitted_data[2]);
                        }
                        //$("#enter_otp").val(splitted_data[0]);
                        // $('#offcanvasRight').show;//now its working
                        //alert(splitted_data[2]);
                    }else{
                        location.reload();
                    }
                     // $('#loader').hide();
                     // $('#loader_background').hide();
                    },
                  error: function(e){ 
                 
                  alert('Error: ' + e.responseText);
                  return false;  

                  }
                  });


  }
    </script>
    <script>
        function location_limit() {
           
            var total_location = $('#total_location').val();
            var work_location = $('#work_location').val();
            
            //special characters
            var format = /[!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?]+/; //!@#$%^&*£+¬_+\=\[\]{};':"\\|<>\/?
            //check match with input value 
            if (format.test(work_location)) {
                $("#work_location_alert").html("Special Characters Not Allowed");
                $("#work_location_alert").addClass('alertMsg');
                return false;
            } else {
                if (total_location >= 3) {
                    //alert(total_location);
                    $('#work_location').val('');
                    $('#work_location_alert').html('Maximum 3 Locations Only');
                    //$("#work_location_alert").addClass('alertMsg');
                    return false;
                } else {
                    $('#work_location_alert').html('');
                    return true;
                }
            }
        }

        var preferred_loc_arr = '<?php echo $prefer_loc_array; ?>';
        if(preferred_loc_arr)
        {
            var choosed_locationsnew = preferred_loc_arr.split(',');
        }else 
        {
            var choosed_locationsnew = [];
        }
        
       
        function new_location(val) {
            //  alert(preferred_loc_arr);
            var strArray = val.split(",");
            var dist_id=strArray[1];
            var dist_name=strArray[0];
            var total_location = $('#total_location').val();

                if (total_location < 3) {
                    if(val){
                        if (choosed_locationsnew.indexOf(dist_id) == -1) {
                            // alert(choosed_locationsnew);
                        choosed_locationsnew.push(dist_id);
                        $("#location_append_area").append('<li class="me-3 moveListLi" onclick="remove_locationnew(' + "'" + dist_id + "'" + ');" id="location_list_' + dist_id + '"><label for="checkboxOne" class="text-gray">' + dist_name + '</label><input type="hidden" name="work_location[]"   value="' + dist_name + '"><input type="hidden" name="location_name[]"   value="' +dist_name + '"></li>');
                        var new_count = parseInt(total_location) + 1;
                        $('#total_location').val(new_count);
                        }else{
                            $('#work_location').val('');
                $('#work_location_alert').html('Location Already Added');
                $("#work_location_alert").addClass('alertMsg');
                        }
                    }
                   
                }
                $('#work_location').val('');
        }

        function remove_locationnew(id) {
            $("#location_list_" + id).remove();
            $("#location_district_" + id).remove();
            $("#location_state_" + id).remove();
            var total_location = $('#total_location').val();
            var new_count = parseInt(total_location) - 1;
            $('#total_location').val(new_count);
            $('#work_location_alert').html('');


            var index = choosed_locationsnew.indexOf(id);
            if (index > -1) {
                choosed_locationsnew.splice(index, 1);
            }
        }
        </script>
        <script>
            var email = document.querySelector('.email'),
    auto = document.querySelector('.autosuffix'),

    popularEmails = ['gmail.com', 'outlook.com', 'yahoo.com', 'yahoo.co.in', 'reddif.com', 'hotmail.com'],

    itemSelected = 0,
    
    itemList = [];

window.addEventListener('keyup', function(){
    
  if(window.event.keyCode === 40) { // Down
    if(itemSelected === (itemList.length - 1)) {
      itemSelected = itemList.length - 1;
    }
    else {
      itemSelected += 1;
    }
  }

  if(window.event.keyCode === 38) { // Up
    if(itemSelected === 0) {
      return;
    }
    else {
      itemSelected -= 1;
    }
  }
  
  if(window.event.keyCode === 13) { // Enter
    email.value = itemList[itemSelected].textContent;
    auto.innerHTML = '';
  }
  
  for(var i = 0; i < itemList.length; i++) { // For loop through all items and add selected class if needed
    if(itemList[i].classList.contains('selected')) {
      itemList[i].classList.remove('selected');
    }
    if(itemSelected === i) {
      itemList[i].classList.add('selected');
    }
  }
  
  console.log(itemSelected, itemList);
});


email.addEventListener('keyup', function() {
  auto.innerHTML = '';
  
  if(email.value.match('@')) { // If the input has a @ in it
    var afterAt = email.value.substring(email.value.indexOf('@') + 1, email.value.length);
    var popularEmailsSub = [];
    
    for(var l = 0; l < popularEmails.length; l++) {
      popularEmailsSub.push(popularEmails[l].substring(0, afterAt.length))
    }
    
    if(afterAt == '') {
      for(var i = 0; i < popularEmails.length; i++) {
        auto.innerHTML += '<li>' + email.value + popularEmails[i] + '</li>';
      }
      itemList = document.querySelectorAll('.autosuffix li');
      itemList[0].classList.add('selected');
    }
    
    else if(!(afterAt == '')) {
      var matchedEmails = [];
      
      for(var k = 0; k < popularEmails.length; k++) {
        if(popularEmailsSub[k].match(afterAt)) {
          matchedEmails.push(popularEmails[k]);                   
        }
      }
      
      for(var i = 0; i < matchedEmails.length; i++) {
        auto.innerHTML += '<li>' + email.value.substring(0, email.value.indexOf('@')) + '@' + matchedEmails[i] + '</li>';
      }
    }
    
    var itemsList = document.querySelectorAll('.autosuffix li');
    
    for(var j = 0; j < itemsList.length; j++) {
      itemsList[j].addEventListener('click', function() {
        email.value = this.textContent;
        auto.innerHTML = '';
      });
    }
  }
});

            </script>
</body>

</html>