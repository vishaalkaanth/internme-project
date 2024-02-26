<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet"> -->
<style>
    .filledData {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9e9e9;
    }

    .filledData:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
</style>
<style>


/*the container must be positioned relative:*/
.autocomplete {
  position: relative;
  display: inline-block;
  width: 100% !important;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 40px;
  left: 0;
  right: 0;
  max-height: 200px;
  overflow-y: auto;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}

/*when hovering an item:*/
.autocomplete-items div:hover {
  background-color: #e9e9e9; 
}

/*when navigating through the items using the arrow keys:*/
.autocomplete-active {
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
</style>

<body class="">

    <?php require_once(APPPATH . "Views/Common/header.php");

    use App\Models\Candidate_model;

    $session = session();
    $userid    =    $session->get('userid');
    $Candidate_model = new Candidate_model();
    $profile_complete_status = $session->get('profile_complete_status');
    $company_logo = $session->get('company_logo');
    $company_name = $session->get('company_name');
    $intership_profile = $session->get('intership_profile');
    $edit_profile = $session->get('edit_profile');
    $profile_page_view=$session->get('profile_page_view');
    ?>

    <!----- Form ------>
    <section class="empProfile">
        <div class="d-flex flex-wrap">
            <?php require_once(APPPATH . "Views/Common/profile_side.php"); ?>
            <div class="col-12 col-lg-9 profileRt d-flex flex-column justify-content-center p-lg-5 py-5 px-4">
                <div class="col-12 col-lg-10 align-self-center">
                    <!----- start Session Alert ------>
                    <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>
                    <!----- End Session Alert ------>
                    <?php if (isset($profile_complete_status) && ($profile_complete_status == 1)) { ?>
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
                    <?php } ?>
                </div>
                <h2 class="fs-title text-blue fw-medium text-center mb-4">Experience</h2>
                <div class="d-flex justify-content-center mb-4">
                    <div class="col-md-10 d-flex justify-content-md-center justify-content-start flex-wrap">
                        <button type="button" class="btn btn-outlined-blue resBtn me-3 mb-2" data-bs-toggle="modal" data-bs-target="#jobModal"><img src="<?= base_url(); ?>/public/assets/img/icon_job_user.svg" alt="" class="me-1" width="15"> Job</button>
                        <button type="button" class="btn btn-outlined-blue resBtn me-3 mb-2" data-bs-toggle="modal" data-bs-target="#internModal" onauxclick="internModal()" ><img src="<?= base_url(); ?>/public/assets/img/icon_internship.svg" alt="" class="me-1" width="17"> Internship</button>
                        <button type="button" class="btn btn-outlined-blue resBtn me-3 mb-2" data-bs-toggle="modal" data-bs-target="#trainingModal"><img src="<?= base_url(); ?>/public/assets/img/icon_course.svg" alt="" class="me-1" width="15"> Training</button>
                        <button type="button" class="btn btn-outlined-blue resBtn me-3 mb-2" data-bs-toggle="modal" data-bs-target="#academicModal"><img src="<?= base_url(); ?>/public/assets/img/icon_project.svg" alt="" class="me-1" width="16"> Projects</button>
                    </div>
                </div>

                <!-- Modal - job -->
                <div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="jobModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">

                        <form action="<?= base_url(); ?>/add_can_experience" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="experience_type" name="experience_type" value="1">

                            <div class="modal-content">
                                <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                    <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Job Details</h5>
                                </div>
                                <div class="modal-body pb-0 px-4">
                                    <div class="d-flex flex-wrap row">
                                        <div class="col-12 col-lg-6 form-group selectField">
                                            <label for="" class="form-label">Profile</label> <span style="color:red;">*</span>
                                            <!-- <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_job1.svg" alt="profile" width="14">
                                                </span>
                                                <input maxlength="50" type="text" id="add_experience_profile" name="add_experience_profile" class="form-control filledBox border-0 py-2 f-14" placeholder="e.g. Graphic designer" onkeyup="experience_profile()">

                                            </div> -->
                                            <!-- <font style="color:#dd4b39;">
                                                <div id="add_experience_profile_alert"></div>
                                            </font> -->
                                            <!-- <div class="autoSearch completeIt d-block z-index-9">
                                                
                                <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Profile" id="add_experience_profile" name="add_experience_profile"  maxlength="50" onkeyup="experience_profile()">
                                <div class="icon"></div>
                                <div class="autoComplete" id="profile_data"></div>
                                <font style="color:#dd4b39;"><div id="add_experience_profile_alert"></div></font>
                            </div> -->
                            <div class="autocomplete" style="width:300px;">
                            <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Profile" id="add_experience_profile" name="add_experience_profile"  maxlength="50" onkeyup="experience_profile()">
                                        <font style="color:#dd4b39;"><div id="add_experience_profile_alert"></div></font>
                                    </div>
                                        </div>
                                        <div class="col-12 col-lg-6 form-group selectField">
                                            <label for="" class="form-label">Organization</label> <span style="color:red;">*</span>
                                            <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_organization.svg" alt="profile" width="14">
                                                </span>
                                                <input maxlength="160" type="text" id="add_experience_organization" name="add_experience_organization" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Organization Name" onkeyup="experience_organization()">

                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_organization_alert"></div>
                                            </font>
                                        </div>
                                       <!--  <div class="col-md-6 form-group selectField mb-4">
                                            <label for="" class="form-label">Location</label> <span style="color:red;">*</span>
                                            <select id="add_experience_location" name="add_experience_location" class="selectpicker form-control filledBox border-0 f-14 mb-4">
                                                <option value="">Select location</option>
                                                <?php if (!empty($city_list)) {
                                                    foreach ($city_list as $city) { ?>
                                                        <option value="<?php echo $city->id; ?>"><?php echo $city->city; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_location_alert"></div>
                                            </font>
                                        </div> -->
                                        
                                           
                                                <div class="col-12 col-lg-6 form-group selectField pe-lg-2">
                                                    <label for="" class="form-label">From</label> <span style="color:red;">*</span>
                                                    <input type="date" id="add_experience_start_year" name="add_experience_start_year" onchange="func_start_year(this.value)" min="<?php echo date("Y-m-d", strtotime('-42 year')); ?>" max="<?php echo date('Y-m-d'); ?>" value="" class=" form-control filledBox border-0 py-2 f-14 mb-4 ">
                                                    <!-- <select onchange="func_start_year(this.value)" id="add_experience_start_year" name="add_experience_start_year" class="form-control f-14 border-left-0">
                                                <option value="">Select start year</option>
                                                    <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
                                                        $year = $x;
                                                        echo "<option value='" . $year . "'>$year</option>";
                                                    }
                                                    ?>
                                            </select> -->
                                                    <font style="color:#dd4b39;">
                                                        <div id="add_experience_start_year_alert"></div>
                                                    </font>
                                                </div>
                                                <div class="col-12 col-lg-6 form-group selectField ps-lg-2 experience_working_hide">
                                                    <label for="" class="form-label">Till</label> <span style="color:red;">*</span>
                                                    <input type="date" id="add_experience_end_year" name="add_experience_end_year" min="<?php echo date("Y-m-d", strtotime('-42 year')); ?>" max="<?php echo date('Y-m-d'); ?>" value="" class="form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <!-- <select id="add_experience_end_year" name="add_experience_end_year"  class="form-control f-14 border-left-0">
                                                <option value="">Select end year</option>
                                                    <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
                                                        $year = $x;
                                                        echo "<option value='" . $year . "'>$year</option>";
                                                    }
                                                    ?>
                                                </select> -->
                                                    <font style="color:#dd4b39;">
                                                        <div id="add_experience_end_year_alert"></div>
                                                    </font>
                                                </div>
                                            <!-- <div class="col-md-6 form-group selectField">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="add_experience_currently_working" name="add_experience_currently_working">
                                                <label class="form-check-label f-14" for="add_experience_currently_working">Currently Working</label>
                                            </div>
                                        </div> -->
                                        <div class="col-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Description </label>
                                            <textarea maxlength="500" id="add_experience_description" name="add_experience_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Short description of work done (max 500 char)" style="height: 100px;" onkeyup="experience_description()"></textarea>
                                            <span id='remainingC'></span>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_description_alert"></div>
                                            </font>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-prim float-end" id="add_can_job_submit" value="Submit" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
                <!-- Modal - intern -->
                <div class="modal fade" id="internModal" tabindex="-1" aria-labelledby="internModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <form action="<?= base_url(); ?>/add_can_experience" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid2" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="experience_type2" name="experience_type" value="2">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                    <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Internship Details</h5>
                                </div>
                                <div class="modal-body pb-0 px-4">
                                    <div class="d-flex flex-wrap row">
                                        <div class="col-12 col-lg-6 form-group selectField">
                                            <label for="" class="form-label">Profile</label> <span style="color:red;">*</span>
                                            <!-- <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_job1.svg" alt="profile" width="14">
                                                </span>
                                                <input maxlength="50" type="text" id="add_experience_profile2" name="add_experience_profile" class="form-control filledBox border-0 py-2 f-14" placeholder="e.g. Graphic designer" onkeyup="experience_profile2()">

                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_profile2_alert"></div>
                                            </font> -->
                                            <!-- <div class="autoSearch completeIt d-block z-index-9">
                                                
                                                <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Profile" id="add_experience_profile2" name="add_experience_profile"  maxlength="50" onkeyup="experience_profile2()">
                                                <div class="icon"></div>
                                                <div class="autoComplete" id="profile_data"></div>
                                                <font style="color:#dd4b39;"><div id="add_experience_profile2_alert"></div></font>
                                            </div> -->
                                            <div class="autocomplete" style="width:300px;">
                                            <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Profile" id="add_experience_profile2" name="add_experience_profile"  maxlength="50" onkeyup="experience_profile2()">
                            <font style="color:#dd4b39;"><div id="add_experience_profile2_alert"></div></font>
                                    </div>
                                        </div>
                                        <div class="col-12 col-lg-6 form-group selectField">
                                            <label for="" class="form-label">Organization</label> <span style="color:red;">*</span>
                                            <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_organization.svg" alt="profile" width="14">
                                                </span>
                                                <input maxlength="160" type="text" id="add_experience_organization2" name="add_experience_organization" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Organization Name" onkeyup="experience_organization2()">

                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_organization2_alert"></div>
                                            </font>
                                        </div>
                                        <!-- <div class="col-md-6 form-group selectField mb-4">
                                            <label for="" class="form-label">Location</label> <span style="color:red;">*</span>
                                            <select id="add_experience_location2" name="add_experience_location" class="selectpicker form-control filledBox f-14 border-0 mb-4">
                                                <option value="">Select Location</option>
                                                <?php if (!empty($city_list)) {
                                                    foreach ($city_list as $city) { ?>
                                                        <option value="<?php echo $city->id; ?>"><?php echo $city->city; ?></option>
                                                <?php }
                                                } ?>
                                            </select> 
                                             <input type="text" id="internship_location" maxlength="15" value="" name="work_location" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter location">
                                            <input type="hidden" name="location_id" id="location_id" value="">
                                            <input type="hidden" name="location_name" id="location_name" value="">

                                                <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_location.svg" alt="GST number" width="14">
                                                </span>
                                                <input type="text" id="internship_location" maxlength="15" name="location_full_name" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter location"  >
                                                <input type="hidden" name="location_id" id="location_id"  > 
                                                <input type="hidden" name="location_name" id="location_name"  >  
                                                <button class="btn btn-green" type="button" id="gst_details">Verify</button>  
                                              </div>  



                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_location2_alert"></div>
                                            </font>
                                        </div> -->
                                       
                                             
                                                <div class="col-12 col-lg-6 form-group selectField pe-lg-2">
                                                    <label for="" class="form-label">From</label> <span style="color:red;">*</span>
                                                    <input type="date" id="add_experience_start_year2" name="add_experience_start_year" onchange="func_start_year2(this.value)" min="<?php echo date("Y-m-d", strtotime('-42 year')); ?>" max="<?php echo date('Y-m-d'); ?>" value="" class=" form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <!-- <select onchange="func_start_year2(this.value)" id="add_experience_start_year2" name="add_experience_start_year" class="form-control f-14 border-left-0">
                                                    <option value="">Select start year</option>
                                                    <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
                                                        $year = $x;
                                                        echo "<option value='" . $year . "'>$year</option>";
                                                    }
                                                    ?>
                                                </select> -->
                                                    <font style="color:#dd4b39;">
                                                        <div id="add_experience_start_year2_alert"></div>
                                                    </font>
                                                </div>
                                                <div class="col-12 col-lg-6 form-group selectField ps-lg-2 experience_working_hide2">
                                                    <label for="" class="form-label">Till</label> <span style="color:red;">*</span>
                                                    <input type="date" id="add_experience_end_year2" name="add_experience_end_year" min="<?php echo date("Y-m-d", strtotime('-42 year')); ?>" max="<?php echo date('Y-m-d'); ?>" value="" class="form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <!-- <select id="add_experience_end_year2" name="add_experience_end_year"  class="form-control f-14 border-left-0">
                                                    <option value="">Select end year</option>
                                                    <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
                                                        $year = $x;
                                                        echo "<option value='" . $year . "'>$year</option>";
                                                    }
                                                    ?>
                                                </select> -->
                                                    <font style="color:#dd4b39;">
                                                        <div id="add_experience_end_year2_alert"></div>
                                                    </font>
                                                </div>
                                            <!-- <div class="col-md-6 form-group selectField"> 
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="add_experience_currently_working2" name="add_experience_currently_working">
                                                <label class="form-check-label f-14" for="add_experience_currently_working2">Currently Working</label>
                                            </div>
                                            </div> -->
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Description </label>
                                            <textarea maxlength="500" id="add_experience_description2" name="add_experience_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Short description of work done (max 500 char)" style="height: 100px;" onkeyup="experience_description2()"></textarea>
                                            <span id='remainingC1'></span>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_description2_alert"></div>
                                            </font>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-prim float-end" id="add_can_internship_submit" value="Submit" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
                <!-- Modal - training -->
                <div class="modal fade" id="trainingModal" tabindex="-1" aria-labelledby="trainingModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <form action="<?= base_url(); ?>/add_can_experience" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid3" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="experience_type3" name="experience_type" value="3">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                    <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Training Details</h5>
                                </div>
                                <div class="modal-body pb-0 px-4">
                                    <div class="d-flex flex-wrap row">
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Training program</label> <span style="color:red;">*</span>
                                            <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_course.svg" alt="profile" width="14">
                                                </span>
                                                <input maxlength="50" type="text" id="add_experience_profile3" name="add_experience_profile" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Training program" onkeyup="experience_profile3()">

                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_profile3_alert"></div>
                                            </font>
                                        </div>
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Organization</label> <span style="color:red;">*</span>
                                            <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_organization.svg" alt="profile" width="14">
                                                </span>
                                                <input maxlength="160" type="text" id="add_experience_organization3" name="add_experience_organization" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Organization Name" onkeyup="experience_organization3()">

                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_organization3_alert"></div>
                                            </font>

                                        </div>
                                        <div class="col-md-12 form-group selectField">
                                            <label class="form-label f-14" for="">Training Mode <span style="color:red;">*</span></label>
                                            <div class="d-flex mb-4">
                                            <div class="form-check me-4">
                                            <input type="hidden" name="add_experience_organization_online3_value" id="add_experience_organization_online3_value" value="">
                                             <label class="form-check-label f-14" for="add_experience_organization_online3">Online</label>
                                             <input class="form-check-input" type="radio" id="add_experience_organization_online3" name="add_experience_organization_online" onclick="func_organization_online(1)">
                                            </div>
                                            <div class="form-check">
                                             <label class="form-check-label f-14" for="add_experience_organization_online31">Offline</label>
                                             <input class="form-check-input" type="radio" id="add_experience_organization_online31" name="add_experience_organization_online" onclick="func_organization_online(2)">
                                            </div>
                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_organization_online3_value_alert"></div>
                                            </font>
                                        </div>
                                        
                                             
                                            <div class="col-md-6 form-group selectField pe-md-2">
                                                <label for="" class="form-label">Training Duration</label> <span style="color:red;">*</span>
                                                <select id="add_training_duration" name="add_training_duration"  class="filledBox form-control f-14 border-0 mb-4" >
                                                <option value="">Select Type</option>
                                                <option value="1">Days</option>
                                                <option value="2">Hours</option>
                                                </select>
                                            <font style="color:#dd4b39;">
                                                <div id="add_training_duration_alert"></div>
                                            </font>
                                                </div>
                                                <div class="col-md-6 form-group selectField ps-md-2 ">
                                                <label for="" class="form-label">Days/Hours</label> <span style="color:red;">*</span>
                                                    <input type="text" maxlength="3" id="add_training_duration_type" name="add_training_duration_type" placeholder="Enter Number Of Days/Hours" class=" form-control filledBox border-0 py-2 f-14 mb-4" >
                                                    <font style="color:#dd4b39;">
                                                        <div id="add_training_duration_type_alert"></div>
                                                    </font>
                                                <!-- <label for="" class="form-label">Till</label> <span style="color:red;">*</span>
                                                    <input type="month" id="add_experience_end_year3" name="add_experience_end_year" min="" max="<?php echo date('Y-m-d'); ?>" value="" class=" form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <font style="color:#dd4b39;">
                                                        <div id="add_experience_end_year3_alert"></div>
                                                    </font> -->
                                                </div>
                                           <!-- <div class="col-md-6 form-group selectField">  
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="add_experience_currently_working3" name="add_experience_currently_working">
                                                <label class="form-check-label f-14" for="add_experience_currently_working3">Currently ongoing</label>
                                            </div>
                                        </div> -->
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Description </label>
                                            <textarea maxlength="500" id="add_experience_description3" name="add_experience_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Short description of work done (max 500 char)" style="height: 100px;" onkeyup="experience_description3()"></textarea>
                                            <span id='remainingC2'></span>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_description3_alert"></div>
                                            </font>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-prim float-end" id="add_can_training_submit" value="Submit" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
                <!-- Modal - academic -->
                <div class="modal fade" id="academicModal" tabindex="-1" aria-labelledby="academicModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <form action="<?= base_url(); ?>/add_can_experience" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid4" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="experience_type4" name="experience_type" value="4">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                    <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Project Details</h5> 
                                </div>
                                <div class="modal-body pb-0 px-4">
                                    <div class="d-flex flex-wrap row">
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Title</label> <span style="color:red;">*</span>
                                            <input maxlength="50" type="text" id="add_experience_profile4" name="add_experience_profile" class="form-control filledBox border-0 py-2 f-14 mb-4" placeholder="Project title" onkeyup="experience_profile4()">
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_profile4_alert"></div>
                                            </font>
                                        </div>
                                        <div class="col-md-6 form-group selectField">
                                        <label for="" class="form-label">Type</label> <span style="color:red;">*</span>
                                        <select id="add_experience_organization4" name="add_experience_organization"  class="filledBox form-control f-14 border-0 mb-4">
                                                <option value="">Select Type</option>
                                                <option value="1">Academic</option>
                                                <option value="2">Industry</option>
                                            </select>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_organization4_alert"></div>
                                            </font>
                                            <!-- <div class="d-flex flex-wrap">
                                                <div class="col-md-6 form-group selectField pe-2">
                                                    <label for="" class="form-label">From</label> <span style="color:red;">*</span>
                                                    <input type="month" id="add_experience_start_year4" name="add_experience_start_year" onchange="func_start_year4(this.value)" min="" max="<?php echo date('Y-m-d'); ?>" value="" class="form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <font style="color:#dd4b39;">
                                                        <div id="add_experience_start_year4_alert"></div>
                                                    </font>
                                                </div>
                                                <div class="col-md-6 form-group selectField ps-2 experience_working_hide4">
                                                    <label for="" class="form-label">Till</label> <span style="color:red;">*</span>
                                                    <input type="month" id="add_experience_end_year4" name="add_experience_end_year" min="" max="<?php echo date('Y-m-d'); ?>" value="" class="form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <font style="color:#dd4b39;">
                                                        <div id="add_experience_end_year4_alert"></div>
                                                    </font>
                                                </div>
                                            </div> -->
                                            <!-- <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="add_experience_currently_working4" name="add_experience_currently_working">
                                            <label class="form-check-label f-14" for="add_experience_currently_working4">Currently ongoing</label>
                                        </div> -->
                                        </div>
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Description </label>
                                            <textarea maxlength="500" id="add_experience_description4" name="add_experience_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Short description of work done (max 500 char)" style="height: 100px;" onkeyup="experience_description4()"></textarea>
                                            <span id='remainingC3'></span>
                                            <font style="color:#dd4b39;">
                                                <div id="add_experience_description4_alert"></div>
                                            </font>
                                        </div>
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Project link </label>
                                            <div class="input-group">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_link1.svg" alt="profile" width="14">
                                                </span>
                                                <input type="url" id="add_experience_project_link4" name="add_experience_project_link" class="form-control filledBox border-0 py-2 f-14" placeholder="http://my_project.com">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-prim float-end" id="add_can_academic_submit" value="Submit" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->



                <div class="d-flex justify-content-center">
                    <div class="col-12 col-lg-10 align-self-start">
                        <?php if (empty($experience_details)) { ?>
                            <div class="card p-4 mb-4">
                                <div class="text-center">
                                    <p class="mb-5">Promote Yourself- Add Your Previous Internship/ Project/ Training Details If Any!</p>
                                    <img src="<?= base_url(); ?>/public/assets/img/add_illu.svg" alt="" class="img-fluid" width="250">
                                </div>
                            </div>
                        <?php } else { ?>
                            <?php
                            $where_job = array('status' => '1', 'userid' => $userid, 'experience_type' => '1');
                            $job_count = $Candidate_model->data_count_fetch('can_experience_details', $where_job);
                            // echo ($job_count);
                            if ($job_count != '0') { ?>
                                <div class="card p-4 mb-4">
                                    <h3 class="text-green fs-5 fw-semibold mb-3">Job</h3>
                                    <?php foreach ($experience_details as $experience) {
                                        if ($experience->experience_type == 1) {
                                            if ($experience->project_currently_ongoing == '1') { ?>
                                                <input type="hidden" class="present_status_job" name="present_status_job[]" value="1">
                                            <?php }
                                            $project_end_year = $experience->project_end_year;
                                            if (empty($experience->project_end_year)) {
                                                $project_end_year = date('Y-m');
                                            }
                                            $diff = abs(strtotime($experience->project_start_year) - strtotime($project_end_year));
                                            $years = floor($diff / (365 * 60 * 60 * 24));
                                            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                                            ?>
                                            <div class="filledData">
                                                <div class="d-flex flex-wrap flex-sm-row flex-column justify-content-between align-items-sm-center mb-1">
                                                    <h6 class="mb-2 mb-sm-1"><?php if (isset($experience->project_organization)) {
                                                                            echo $experience->project_organization;
                                                                        } ?></h6>
                                                    <div>

                                                   
                                                 <?php if($experience->project_title!=0){
                                                    $where = array('id' => $experience->project_title);
                                                    $profile_id = $Candidate_model->fetch_table_row('master_profile',$where);
                                                    $project_title=$profile_id->profile;
                                              }else{
                                                  $project_title=$experience->profile_other;
                                              }
                                                    ?>
                                                        <a href="" onclick="func_edit_experience('<?php echo $experience->id; ?>','<?php echo $experience->experience_type; ?>','<?php echo $userid; ?>','<?php echo $project_title; ?>','<?php echo $experience->project_organization; ?>','<?php echo $experience->project_organization_online; ?>','<?php echo $experience->project_start_year; ?>','<?php echo $experience->project_end_year; ?>','<?php echo $experience->project_currently_ongoing; ?>','<?php echo base64_encode($experience->project_description); ?>','<?php echo $experience->project_link; ?>')" type="button" class="text-blue edit me-4" data-bs-toggle="modal" data-bs-target="#jobModal_edit"><i class="fa fa-pencil me-2" aria-hidden="true"></i>Edit</a>
                                                        <!-- <a href="<?= base_url(); ?>/delete_common/<?php echo $experience->id; ?>/can_experience_details/experience-details" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a> -->
                                                        <a onclick="func_delete_experience('<?php echo $experience->id; ?>','can_experience_details','experience-details')" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a>
                                                        
                                                    </div>
                                                </div>

                                                <h6 class="text-muted fw-normal mb-0"><?php if($experience->project_title!=0){
                                                    $where = array('id' => $experience->project_title);
                                                    $profile_id = $Candidate_model->fetch_table_row('master_profile',$where);
                                                   echo $project_title=$profile_id->profile;
                                              }else{
                                                 echo $project_title=$experience->profile_other;
                                              }
                                                    ?></h6>
                                                <!-- <h6>Product Trainer</h6> -->
                                                <ul class="d-flex flex-wrap ps-0 mb-2 list-unstyled">
                                                   <!--  <li class="me-5 text-muted list-unstyled"><?php //if (isset($experience->project_location)) {
                                                                                                   // echo $Candidate_model->get_master_name('master_city', $experience->project_location, 'city');
                                                                                                //} ?></li> -->
                                                    <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if ($years != 0) {
                                                                                                           if($years==1){echo $years . " year ";}else{ echo $years . " years ";}
                                                                                                        }
                                                                                                        if ($months != 0) {
                                                                                                            if($months==1){echo $months . " Month ";}else{ echo $months . " Months ";}
                                                                                                        } if($months==0 && $years==0 && $days!=0){ if($days==1){echo $days . " day ";}else{ echo $days . " days ";}}?> </li>
                                                    <li class="text-muted "><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2" width="12"><?php if ($experience->project_currently_ongoing != 1) {
                                                                                echo date("M Y", strtotime($experience->project_start_year)) . ' - ' . date("M Y", strtotime($experience->project_end_year));
                                                                            } else {
                                                                                echo date("M Y", strtotime($experience->project_start_year)) . ' - Present';
                                                                            } ?></li>
                                                </ul>
                                                <h6 class="fw-normal text-muted f-14"><?php if (isset($experience->project_description)) {
                                                        echo $experience->project_description;
                                                    } ?></h6>
                                            </div>

                                    <?php }
                                    } ?>
                                </div>
                            <?php
                            }
                            $where_inter = array('status' => '1', 'userid' => $userid, 'experience_type' => '2');
                            $internship_count = $Candidate_model->data_count_fetch('can_experience_details', $where_inter);
                            // echo ($internship_count);
                            if ($internship_count != '0') { ?>
                                <div class="card p-4 mb-4">
                                    <h3 class="text-green fs-5 fw-semibold mb-3">Internship</h3>
                                    <?php foreach ($experience_details as $experience) {
                                        if ($experience->experience_type == 2) {
                                            if ($experience->project_currently_ongoing == '1') { ?>
                                                <input type="hidden" class="present_status_internship" name="present_status_internship[]" value="1">
                                            <?php }
                                            $project_end_year = $experience->project_end_year;
                                            if (empty($experience->project_end_year)) {
                                                $project_end_year = date('Y-m');
                                            }
                                            $diff = abs(strtotime($experience->project_start_year) - strtotime($project_end_year));
                                            $years = floor($diff / (365 * 60 * 60 * 24));
                                            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                                            ?>

                                            <div class="filledData">
                                                <div class="d-flex flex-wrap flex-sm-row flex-column justify-content-between align-items-sm-center mb-1">
                                                    <h6 class="mb-2 mb-sm-1"><?php if (isset($experience->project_organization)) {
                                                            echo $experience->project_organization;
                                                        } ?></h6>
                                                    <div>
                                                    <?php if($experience->project_title!=0){
                                                    $where = array('id' => $experience->project_title);
                                                    $profile_id = $Candidate_model->fetch_table_row('master_profile',$where);
                                                    $project_title=$profile_id->profile;
                                              }else{
                                                  $project_title=$experience->profile_other;
                                              }
                                                    ?>
                                                        <a href="" onclick="func_edit_experience2('<?php echo $experience->id; ?>','<?php echo $experience->experience_type; ?>','<?php echo $userid; ?>','<?php echo $project_title; ?>','<?php echo $experience->project_organization; ?>','<?php echo $experience->project_organization_online; ?>','<?php echo $experience->project_start_year; ?>','<?php echo $experience->project_end_year; ?>','<?php echo $experience->project_currently_ongoing; ?>','<?php echo base64_encode($experience->project_description); ?>','<?php echo $experience->project_link; ?>')" type="button" class="text-blue edit me-4" data-bs-toggle="modal" data-bs-target="#internModal_edit"><i class="fa fa-pencil me-2" aria-hidden="true"></i>Edit</a>
                                                        <!-- <a href="<?= base_url(); ?>/delete_common/<?php echo $experience->id; ?>/can_experience_details/experience-details" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a> -->
                                                        <a onclick="func_delete_experience('<?php echo $experience->id; ?>','can_experience_details','experience-details')" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a>
                                                    </div>
                                                </div>

                                                <h6 class="fw-normal text-muted mb-0"> <?php if($experience->project_title!=0){
                                                    $where = array('id' => $experience->project_title);
                                                    $profile_id = $Candidate_model->fetch_table_row('master_profile',$where);
                                                   echo $project_title=$profile_id->profile;
                                              }else{
                                                 echo $project_title=$experience->profile_other;
                                              }
                                                    ?></h6>
                                                <!-- <h6>Product Trainer</h6> -->
                                                <ul class="d-flex flex-wrap ps-0 mb-2 list-unstyled">
                                                    <!-- <li class="me-5 text-muted list-unstyled"><?php if (isset($experience->project_location)) {
                                                                                                    echo $Candidate_model->get_master_name('master_city', $experience->project_location, 'city');;
                                                                                                } ?></li> -->
                                                    <li class="me-4 text-muted"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if ($years != 0) {
                                                                                                            // echo $years . " year ";
                                                                                                            if($years==1){echo $years . " year ";}else{ echo $years . " years ";}
                                                                                                        }
                                                                                                        if ($months != 0) {
                                                                                                            // echo $months . " Months";
                                                                                                            if($months==1){echo $months . " Month ";}else{ echo $months . " Months ";}
                                                                                                        } if($months==0 && $years==0 && $days!=0){ if($days==1){echo $days . " day ";}else{ echo $days . " days ";} } ?> </li>
                                                    <li class="text-muted "><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2" width="12"><?php if ($experience->project_currently_ongoing != 1) {
                                                                                echo date("M Y", strtotime($experience->project_start_year)) . ' - ' . date("M Y", strtotime($experience->project_end_year));
                                                                            } else {
                                                                                echo date("M Y", strtotime($experience->project_start_year)) . ' - Present';
                                                                            } ?></li>
                                                </ul>
                                                <h6 class="fw-normal text-muted f-14"><?php if (isset($experience->project_description)) {
                                                        echo $experience->project_description;
                                                    } ?></h6>
                                            </div>

                                    <?php }
                                    } ?>
                                </div>
                            <?php }
                            $where_training = array('status' => '1', 'userid' => $userid, 'experience_type' => '3');
                            $training_count = $Candidate_model->data_count_fetch('can_experience_details', $where_training);
                            //    echo ($training_count);
                            if ($training_count != '0') { ?>
                                <div class="card p-4 mb-4">
                                    <h3 class="text-green fs-5 fw-semibold mb-3">Training</h3>
                                    <?php foreach ($experience_details as $experience) {
                                        if ($experience->experience_type == 3) {
                                            if ($experience->project_currently_ongoing == '1') { ?>
                                                <input type="hidden" class="present_status_training" name="present_status_training[]" value="1">
                                            <?php }
                                            $project_end_year = $experience->project_end_year;
                                            if (empty($experience->project_end_year)) {
                                                $project_end_year = date('Y-m');
                                            }
                                            $diff = abs(strtotime($experience->project_start_year) - strtotime($project_end_year));
                                            $years = floor($diff / (365 * 60 * 60 * 24));
                                            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)); ?>

                                            <div class="filledData">
                                                <div class="d-flex flex-wrap flex-sm-row flex-column justify-content-between align-items-sm-center mb-1">
                                                    <h6 class="mb-2 mb-sm-1"><?php if (isset($experience->project_organization)) {
                                                            echo $experience->project_organization;
                                                        } ?></h6>
                                                    <div>
                                                        <a href="" onclick="func_edit_experience3('<?php echo $experience->id; ?>','<?php echo $experience->experience_type; ?>','<?php echo $userid; ?>','<?php echo $experience->project_title; ?>','<?php echo $experience->project_organization; ?>','<?php echo $experience->project_organization_online; ?>','<?php echo $experience->project_duration; ?>','<?php echo $experience->project_duration_type; ?>','<?php echo $experience->project_currently_ongoing; ?>','<?php echo base64_encode($experience->project_description); ?>','<?php echo $experience->project_link; ?>')" type="button" class="text-blue edit me-4" data-bs-toggle="modal" data-bs-target="#trainingModal_edit"><i class="fa fa-pencil me-2" aria-hidden="true"></i>Edit</a>
                                                        <!-- <a href="<?= base_url(); ?>/delete_common/<?php echo $experience->id; ?>/can_experience_details/experience-details" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a> -->
                                                        <a onclick="func_delete_experience('<?php echo $experience->id; ?>','can_experience_details','experience-details')" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a>
                                                    </div>
                                                </div>

                                                <h6 class="fw-normal text-muted mb-0"><?php if (isset($experience->project_title)) {
                                                        echo $experience->project_title;
                                                    } ?><?php if (isset($experience->project_organization_online)) {
                                                                                                                                        if ($experience->project_organization_online == '1') {
                                                                                                                                            echo ' (Online) ';
                                                                                                                                        }else{echo ' (Offline) ';}
                                                                                                                                    } ?></h6>
                                                <!-- <h6>Product Trainer</h6> -->
                                                <ul class="d-flex flex-wrap ps-0 mb-2 list-unstyled">
                                                    <li class="me-4 text-muted list-unstyled"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12"><?php if (isset($experience->project_duration)) {
                                                        echo $experience->project_duration_type;
                                                                                                    if($experience->project_duration==1) {echo " Days";}else{ echo " Hours";}
                                                                                                } ?></li>
                                                   
                                                    <!-- <li class="text-muted"><?php if ($experience->project_currently_ongoing != 1) {
                                                                                echo date("M Y", strtotime($experience->project_start_year)) . ' - ' . date("M Y", strtotime($experience->project_end_year));
                                                                            } else {
                                                                                echo date("M Y", strtotime($experience->project_start_year)) . ' - Present';
                                                                            } ?></li> -->
                                                </ul>
                                                <h6 class="fw-normal text-muted f-14"><?php if (isset($experience->project_description)) {
                                                        echo $experience->project_description;
                                                    } ?></h6>
                                            </div>

                                    <?php }
                                    } ?>
                                </div>
                            <?php }
                            $where_project = array('status' => '1', 'userid' => $userid, 'experience_type' => '4');
                            $project_count = $Candidate_model->data_count_fetch('can_experience_details', $where_project);
                            //    echo ($project_count);
                            if ($project_count != '0') { ?>
                                <div class="card p-4 mb-4">
                                    <h3 class="text-green fs-5 fw-semibold mb-3">Projects</h3>
                                    <?php foreach ($experience_details as $experience) {
                                        if ($experience->experience_type == 4) {
                                            $project_end_year = $experience->project_end_year;
                                            if (empty($experience->project_end_year)) {
                                                $project_end_year = date('Y-m');
                                            }
                                            $diff = abs(strtotime($experience->project_start_year) - strtotime($project_end_year));
                                            $years = floor($diff / (365 * 60 * 60 * 24));
                                            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)); ?>

                                            <div class="filledData">
                                                <div class="d-flex flex-wrap flex-sm-row flex-column justify-content-between align-items-sm-center mb-1">
                                                    <h6 class="mb-2 mb-sm-1"><?php if (isset($experience->project_title)) {
                                                            echo $experience->project_title;
                                                        } ?><?php if (isset($experience->project_organization)) { if($experience->project_organization==1){echo " (Academic) ";} else{ echo " (Industry) ";}} ?></h6>
                                                    <div>
                                                        <a href="" onclick="func_edit_experience4('<?php echo $experience->id; ?>','<?php echo $experience->experience_type; ?>','<?php echo $userid; ?>','<?php echo $experience->project_title; ?>','<?php echo $experience->project_organization; ?>','<?php echo $experience->project_organization_online; ?>','<?php echo $experience->project_start_year; ?>','<?php echo $experience->project_end_year; ?>','<?php echo $experience->project_currently_ongoing; ?>','<?php echo base64_encode($experience->project_description); ?>','<?php echo $experience->project_link; ?>')" type="button" class="text-blue edit me-4" data-bs-toggle="modal" data-bs-target="#academicModal_edit"><i class="fa fa-pencil me-2" aria-hidden="true"></i>Edit</a>
                                                        <!-- <a href="<?= base_url(); ?>/delete_common/<?php echo $experience->id; ?>/can_experience_details/experience-details" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a> -->
                                                        <a onclick="func_delete_experience('<?php echo $experience->id; ?>','can_experience_details','experience-details')" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a>
                                                    </div>
                                                </div>
                                                <h6 class="fw-normal text-muted mb-0"><a target="_blank" href="<?php if (isset($experience->project_link)) {
                                                        echo $experience->project_link;
                                                    } ?>"><?php if (isset($experience->project_link)) {
                                                        echo $experience->project_link;
                                                    } ?></a></h6>

                                                <!-- <h6>Product Trainer</h6> -->
                                                <!-- <ul class="d-flex flex-wrap ps-0 mb-2">
                                                    <?php if ($months != 0) { ?><li class="me-5 text-muted list-unstyled"><?php if ($years != 0) {
                                                                                                                            echo $years . " year ";
                                                                                                                        }
                                                                                                                        if ($months != 0) {
                                                                                                                            echo $months . " Months";
                                                                                                                        } ?></li><?php } ?>
                                                    <li class="text-muted"><?php if ($experience->project_currently_ongoing != 1) {
                                                                                echo date("M Y", strtotime($experience->project_start_year)) . ' - ' . date("M Y", strtotime($experience->project_end_year));
                                                                            } else {
                                                                                echo date("M Y", strtotime($experience->project_start_year)) . ' - Present';
                                                                            } ?></li>
                                                </ul> -->
                                                <h6 class="fw-normal text-muted f-14"><?php if (isset($experience->project_description)) {
                                                        echo $experience->project_description;
                                                    } ?></h6>
                                            </div>

                                    <?php }
                                    } ?>
                                </div>
                        <?php }
                        } ?>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url(); ?>/education-details" class="btn btn-prev me-2">Previous</a>
                            <div>
                                <?php
                                $next_but_status=$session->get('next_but_status'); 
                                if ((empty($experience_details)) && (!isset($profile_page_view) && ($profile_page_view!=1) ) && (!isset($edit_profile) && ($edit_profile!=1) )) { ?>
                                    <a href="<?= base_url(); ?>/skill-details" class="btn btn-outlined-blue me-2">Skip</a>
                                <?php } else{
                                if (isset($edit_profile) && ($edit_profile == 1)) {
                                    $ses_data = [
                                        'updated_status' => 1
                                    ];
                                    $session->set($ses_data);
                                    $intership_id = $session->get('intership_number');
                                    if(isset($next_but_status) && $next_but_status=='1'){
                                        if(empty($experience_details)){
                                ?>
                                <a href="<?= base_url(); ?>/skill-details" class="btn btn-outlined-blue me-2">Skip</a>
                                <?php } else { ?>
                                <a href="<?= base_url(); ?>/skill-details" class="btn btn-prim">Next</a>
                                    <?php } } else{ ?>
                                        
                                        <a href="<?= base_url(); ?>/can-apply-for-internship/<?= $intership_id; ?>" class="btn btn-prim">Save & Continue</a>
                                    <?php  }  } elseif(isset($profile_page_view) && ($profile_page_view==1)){ ?>
                                        <a href="<?= base_url(); ?>/profile-details" class="btn btn-prim">Save</a>
                                <?php  } else { 
                                        if(empty($experience_details)){ ?>
                                    <a href="<?= base_url(); ?>/education-details" class="btn btn-outlined-blue me-2">Skip</a>
                                            <?php } else { ?>
                                            <a href="<?= base_url(); ?>/skill-details" class="btn btn-prim">Next</a>
                                    <?php } } } ?>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Modal - job - edit -->
                <div class="modal fade" id="jobModal_edit" tabindex="-1" aria-labelledby="jobModalLabel_edit" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <form action="<?= base_url(); ?>/edit_can_experience" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="experience_type" name="experience_type" value="1">
                            <input type="hidden" id="editid" name="editid" value="">

                            <div class="modal-content">
                                <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                    <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Job Details</h5>
                                </div>
                                <div class="modal-body pb-0 px-4">
                                    <div class="d-flex flex-wrap row">
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Profile</label> <span style="color:red;">*</span>
                                            <!-- <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_job1.svg" alt="profile" width="14">
                                                </span>
                                                <input type="text" id="edit_experience_profile" name="edit_experience_profile" class="form-control filledBox border-0 py-2 f-14" placeholder="e.g. Graphic designer" onkeyup="experience_profile_edit()">
                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_profile_alert"></div>
                                            </font> -->
                                            <!-- <div class="autoSearch completeIt d-block z-index-9">
                                                
                                                <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Profile" id="edit_experience_profile" name="edit_experience_profile"  maxlength="50" onkeyup="experience_profile_edit()">
                                                <div class="icon"></div>
                                                <div class="autoComplete" id="profile_data"></div>
                                                <font style="color:#dd4b39;"><div id="edit_experience_profile_alert"></div></font>
                                            </div> -->
                                            <div class="autocomplete" style="width:300px;">
                                            <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Profile" id="edit_experience_profile" name="edit_experience_profile"  maxlength="50" onkeyup="experience_profile_edit()">
                                        <font style="color:#dd4b39;"><div id="edit_experience_profile_alert"></div></font>
                                    </div>
                                        </div>
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Organization</label> <span style="color:red;">*</span>
                                            <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_organization.svg" alt="profile" width="14">
                                                </span>
                                                <input type="text" maxlength="160" id="edit_experience_organization" name="edit_experience_organization" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Organization Name" onkeyup="experience_organization_edit()">
                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_organization_alert"></div>
                                            </font>
                                        </div>
                                      <!--   <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Location</label> <span style="color:red;">*</span>
                                            <select id="edit_experience_location" name="edit_experience_location" class="form-control filledBox border-0 f-14 selectpicker mb-4">
                                                <option value="">Select location</option>
                                                <?php if (!empty($city_list)) {
                                                    foreach ($city_list as $city) { ?>
                                                        <option value="<?php echo $city->id; ?>"><?php echo $city->city; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_location_alert"></div>
                                            </font>
                                        </div> -->
                                       
                                            
                                                <div class="col-md-6 form-group selectField pe-md-2">
                                                    <label for="" class="form-label">From</label> <span style="color:red;">*</span>
                                                    <input type="date" id="edit_experience_start_year" name="edit_experience_start_year" onchange="func_start_year_edit(this.value)" min="" max="<?php echo date('Y-m-d'); ?>" value="" class=" form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <!-- <select onchange="func_start_year_edit(this.value)" id="edit_experience_start_year" name="edit_experience_start_year" class="form-control f-14 border-left-0">
                                                <option value="">Select start year</option>
                                                    <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
                                                        $year = $x;
                                                        echo "<option value='" . $year . "'>$year</option>";
                                                    }
                                                    ?>
                                            </select> -->
                                                    <font style="color:#dd4b39;">
                                                        <div id="edit_experience_start_year_alert"></div>
                                                    </font>
                                                </div>
                                                <div class="col-md-6 form-group selectField ps-md-2 edit_experience_working_hide">
                                                    <label for="" class="form-label">Till</label> <span style="color:red;">*</span>
                                                    <input type="date" id="edit_experience_end_year" name="edit_experience_end_year" min="" max="<?php echo date('Y-m-d'); ?>" value="" class="form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <!-- <select id="edit_experience_end_year" name="edit_experience_end_year"  class="form-control f-14 border-left-0">
                                                <option value="">Select end year</option>
                                                    <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
                                                        $year = $x;
                                                        echo "<option value='" . $year . "'>$year</option>";
                                                    }
                                                    ?>
                                                </select> -->
                                                    <font style="color:#dd4b39;">
                                                        <div id="edit_experience_end_year_alert"></div>
                                                    </font>
                                                </div>
                                            <div class="col-md-6 form-group selectField">  
                                            <!-- <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="edit_experience_currently_working" name="edit_experience_currently_working">
                                                <label class="form-check-label f-14" for="edit_experience_currently_working">Currently Working</label>
                                            </div> -->
                                        </div>
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Description </label>
                                            <textarea maxlength="500" id="edit_experience_description" name="edit_experience_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Short description of work done (max 500 char)" style="height: 100px;" onkeyup="experience_description_edit()"></textarea>
                                            <span id='remainingC4'></span>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_description_alert"></div>
                                            </font>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-prim float-end" id="edit_can_job_submit" value="Submit" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
                <!-- Modal - intern - edit -->
                <div class="modal fade" id="internModal_edit" tabindex="-1" aria-labelledby="internModalLabel_edit" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <form action="<?= base_url(); ?>/edit_can_experience" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid2" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="experience_type2" name="experience_type" value="2">
                            <input type="hidden" id="editid2" name="editid" value="">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                    <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Internship Details</h5>
                                </div>
                                <div class="modal-body pb-0 px-4">
                                    <div class="d-flex flex-wrap row">
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Profile</label> <span style="color:red;">*</span>
                                            <!-- <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_job1.svg" alt="profile" width="14">
                                                </span>
                                                <input type="text" id="edit_experience_profile2" name="edit_experience_profile" class="form-control filledBox border-0 py-2 f-14" placeholder="e.g. Graphic designer" onkeyup="experience_profile2_edit()">
                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_profile2_alert"></div>
                                            </font> -->
                                            <!-- <div class="autoSearch completeIt d-block z-index-9">
                                                
                                                <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Profile" id="edit_experience_profile2" name="edit_experience_profile"  maxlength="50" onkeyup="experience_profile2_edit()">
                                                <div class="icon"></div>
                                                <div class="autoComplete" id="profile_data"></div>
                                                <font style="color:#dd4b39;"><div id="edit_experience_profile2_alert"></div></font>
                                            </div> -->
                                            <div class="autocomplete" style="width:300px;">
                                            <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Profile" id="edit_experience_profile2" name="edit_experience_profile"  maxlength="50" onkeyup="experience_profile2_edit()">
                                        <font style="color:#dd4b39;"><div id="edit_experience_profile2_alert"></div></font>
                                    </div>
                                        </div>
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Organization</label> <span style="color:red;">*</span>
                                            <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_organization.svg" alt="profile" width="14">
                                                </span>
                                                <input type="text" maxlength="160" id="edit_experience_organization2" name="edit_experience_organization" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Organization Name" onkeyup="experience_organization2_edit()">
                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_organization2_alert"></div>
                                            </font>
                                        </div>
                                       <!--  <div class="col-md-6 form-group selectField mb-4">
                                            <label for="" class="form-label">Location</label> <span style="color:red;">*</span>
                                            <select id="edit_experience_location2" name="edit_experience_location" class="selectpicker form-control filledBox border-0 f-14">
                                                <option value="">Select Location</option>
                                                <?php if (!empty($city_list)) {
                                                    foreach ($city_list as $city) { ?>
                                                        <option value="<?php echo $city->id; ?>"><?php echo $city->city; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_location2_alert"></div>
                                            </font>
                                        </div> -->
                                        
                                            
                                                <div class="col-md-6 form-group selectField pe-md-2">
                                                    <label for="" class="form-label">From</label> <span style="color:red;">*</span>
                                                    <input type="date" id="edit_experience_start_year2" name="edit_experience_start_year" onchange="func_start_year_edit2(this.value)" min="" max="<?php echo date('Y-m-d'); ?>" value="" class="form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <!-- <select onchange="func_start_year_edit2(this.value)" id="edit_experience_start_year2" name="edit_experience_start_year" class="form-control f-14 border-left-0">
                                                <option value="">Select start year</option>
                                                    <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
                                                        $year = $x;
                                                        echo "<option value='" . $year . "'>$year</option>";
                                                    }
                                                    ?>
                                            </select> -->
                                                    <font style="color:#dd4b39;">
                                                        <div id="edit_experience_start_year2_alert"></div>
                                                    </font>
                                                </div>
                                                <div class="col-md-6 form-group selectField ps-md-2 edit_experience_working_hide2">
                                                    <label for="" class="form-label">Till</label> <span style="color:red;">*</span>
                                                    <input type="date" id="edit_experience_end_year2" name="edit_experience_end_year" min="" max="<?php echo date('Y-m-d'); ?>" value="" class=" form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <!-- <select id="edit_experience_end_year2" name="edit_experience_end_year"  class="form-control f-14 border-left-0">
                                                <option value="">Select end year</option>
                                                    <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
                                                        $year = $x;
                                                        echo "<option value='" . $year . "'>$year</option>";
                                                    }
                                                    ?>
                                                </select> -->
                                                    <font style="color:#dd4b39;">
                                                        <div id="edit_experience_end_year2_alert"></div>
                                                    </font>
                                                </div>
                                            <div class="col-md-6 form-group selectField"> 
                                            <!-- <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="edit_experience_currently_working2" name="edit_experience_currently_working">
                                                <label class="form-check-label f-14" for="edit_experience_currently_working2">Currently Working</label>
                                            </div> -->
                                        </div>
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Description </label>
                                            <textarea maxlength="500" id="edit_experience_description2" name="edit_experience_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Short description of work done (max 500 char)" style="height: 100px;" onkeyup="experience_description2_edit()"></textarea>
                                            <span id='remainingC5'></span>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_description2_alert"></div>
                                            </font>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-prim float-end" id="edit_can_internship_submit" value="Submit" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
                <!-- Modal - training - edit -->
                <div class="modal fade" id="trainingModal_edit" tabindex="-1" aria-labelledby="trainingModalLabel_edit" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <form action="<?= base_url(); ?>/edit_can_experience" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid3" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="experience_type3" name="experience_type" value="3">
                            <input type="hidden" id="editid3" name="editid" value="">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                    <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Training Details</h5>
                                </div>
                                <div class="modal-body pb-0 px-4">
                                    <div class="d-flex flex-wrap row">
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Training program</label> <span style="color:red;">*</span>
                                            <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_course.svg" alt="profile" width="14">
                                                </span>
                                                <input type="text" maxlength="50" id="edit_experience_profile3" name="edit_experience_profile" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Training program" onkeyup="experience_profile3_edit()">
                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_profile3_alert"></div>
                                            </font>
                                        </div>
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Organization</label> <span style="color:red;">*</span>
                                            <div class="input-group mb-4">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_organization.svg" alt="profile" width="14">
                                                </span>
                                                <input type="text" maxlength="160" id="edit_experience_organization3" name="edit_experience_organization" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter Organization Name" onkeyup="experience_organization3_edit()">
                                            </div>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_organization3_alert"></div>
                                            </font>

                                        </div>
                                        <div class="col-md-12 form-group selectField">
                                            
                                        <label class="form-label f-14" for="">Training Mode</label> <span style="color:red;">*</span>
                                        <div class="d-flex mb-4">
                                            <div class="form-check me-4">
                                            <input type="hidden" name="edit_experience_organization_online3_value" id="edit_experience_organization_online3_value" value="">
                                             <label class="form-check-label f-14" for="edit_experience_organization_online3">Online</label>
                                             <input class="form-check-input" type="radio" id="edit_experience_organization_online3" name="edit_experience_organization_online" onclick="func_organization_online_edit(1)">
                                            </div>
                                            <div class="form-check">
                                             <label class="form-check-label f-14" for="edit_experience_organization_online31">Offline</label>
                                             <input class="form-check-input" type="radio" id="edit_experience_organization_online31" name="edit_experience_organization_online" onclick="func_organization_online_edit(2)">
                                            </div>
                                        </div>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_organization_online3_value_alert"></div>
                                            </font>
                                        </div>
                                        <!-- <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Location</label> <span style="color:red;">*</span>
                                            <select id="edit_experience_location3" name="edit_experience_location" class="selectpicker form-control filledBox border-0 f-14 mb-4">
                                                <option value="">Select location</option>
                                                <?php if (!empty($city_list)) {
                                                    foreach ($city_list as $city) { ?>
                                                        <option value="<?php echo $city->id; ?>"><?php echo $city->city; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_location3_alert"></div>
                                            </font>
                                        </div> -->
                                        
                                            
                                                <div class="col-md-6 form-group selectField pe-md-2">
                                                <label for="" class="form-label">Training Duration</label> <span style="color:red;">*</span>
                                                <select id="edit_training_duration" name="edit_training_duration"  class="filledBox form-control f-14 border-0 mb-4">
                                                <option value="">Select Type</option>
                                                <option value="1">Days</option>
                                                <option value="2">Hours</option>
                                                </select>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_training_duration_alert"></div>
                                            </font>
                                                </div>
                                                <div class="col-md-6 form-group selectField ps-md-2 edit_experience_working_hide3">
                                                <label for="" class="form-label">Days/Hours</label> <span style="color:red;">*</span>
                                                    <input type="text" maxlength="3" id="edit_training_duration_type" name="edit_training_duration_type" placeholder="Enter Number Of Days/Hours" class=" form-control filledBox border-0 py-2 f-14 mb-4">
                                                    <font style="color:#dd4b39;">
                                                        <div id="edit_training_duration_type_alert"></div>
                                                    </font>
                                                </div>
                                            <!-- <div class="col-md-6 form-group selectField"> 
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="edit_experience_currently_working3" name="edit_experience_currently_working">
                                                <label class="form-check-label f-14" for="edit_experience_currently_working3">Currently ongoing</label>
                                            </div>
                                        </div> -->
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Description </label>
                                            <textarea maxlength="500" id="edit_experience_description3" name="edit_experience_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Short description of work done (max 500 char)" style="height: 100px;" onkeyup="experience_description3_edit()"></textarea>
                                            <span id='remainingC6'></span>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_description3_alert"></div>
                                            </font>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-prim float-end" id="edit_can_training_submit" value="Submit" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
                <!-- Modal - academic - edit -->
                <div class="modal fade" id="academicModal_edit" tabindex="-1" aria-labelledby="academicModalLabel_edit" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <form action="<?= base_url(); ?>/edit_can_experience" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <input type="hidden" id="userid4" name="userid" value="<?php if (isset($userid)) {
                                                                                        echo $userid;
                                                                                    } ?>">
                            <input type="hidden" id="experience_type4" name="experience_type" value="4">
                            <input type="hidden" id="editid4" name="editid" value="">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                    <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Project Details</h5>
                                </div>
                                <div class="modal-body pb-0 px-4">
                                    <div class="d-flex flex-wrap row">
                                        <div class="col-md-6 form-group selectField">
                                            <label for="" class="form-label">Title</label> <span style="color:red;">*</span>
                                            <input type="text" maxlength="50" id="edit_experience_profile4" name="edit_experience_profile" class="form-control filledBox border-0 py-2 f-14 mb-4" placeholder="Project title" onkeyup="experience_profile4_edit()">
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_profile4_alert"></div>
                                            </font>
                                        </div>

                                        <div class="col-md-6 form-group selectField">
                                        <label for="" class="form-label">Type</label> <span style="color:red;">*</span>
                                        <select id="edit_experience_organization4" name="edit_experience_organization"  class="filledBox form-control f-14 border-0 mb-4">
                                                <option value="">Select Type</option>
                                                <option value="1">Academic</option>
                                                <option value="2">Industry</option>
                                            </select>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_organization4_alert"></div>
                                            </font>
                                            <!-- <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="edit_experience_currently_working4" name="edit_experience_currently_working">
                                            <label class="form-check-label f-14" for="edit_experience_currently_working4">Currently ongoing</label>
                                        </div> -->
                                        </div>
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Description </label>
                                            <textarea maxlength="500" id="edit_experience_description4" name="edit_experience_description" class="form-control filledBox border-0 py-2 f-14" placeholder="Short description of work done (max 500 char)" style="height: 100px;" onkeyup="experience_description4_edit()"></textarea>
                                            <span id='remainingC7'></span>
                                            <font style="color:#dd4b39;">
                                                <div id="edit_experience_description4_alert"></div>
                                            </font>
                                        </div>
                                        <div class="col-md-12 form-group selectField mb-4">
                                            <label for="" class="form-label">Project link </label>
                                            <div class="input-group">
                                                <span class="input-group-text fillBg border-0">
                                                    <img src="<?= base_url(); ?>/public/assets/img/icon_link1.svg" alt="profile" width="14">
                                                </span>
                                                <input type="url" id="edit_experience_project_link4" name="edit_experience_project_link" class="form-control filledBox border-0 py-2 f-14" placeholder="http://my_project.com">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                    <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-prim float-end" id="edit_can_academic_submit" value="Submit" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        function func_delete_experience(id,tablename,refresh_page){
            swal({
                    title: "Are you sure ?",
                    text: "You want to remove this experience",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonClass: "btn-primary",
                    confirmButtonText: "Proceed",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {

                    if (isConfirm) {
                        window.location.href = '<?= base_url("delete_common"); ?>/'+ id+'/'+ tablename+'/'+ refresh_page;

                    } else {
                        location.reload();
                    }
                })
        }

        $('#add_experience_description').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC").html("Remaining Characters : " + (500 - this.value.length));

});
$('#add_experience_description2').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC1").html("Remaining Characters : " + (500 - this.value.length));

});
$('#add_experience_description3').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC2").html("Remaining Characters : " + (500 - this.value.length));

});
$('#add_experience_description4').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC3").html("Remaining Characters : " + (500 - this.value.length));

});
$('#edit_experience_description').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC4").html("Remaining Characters : " + (500 - this.value.length));

});
$('#edit_experience_description2').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC5").html("Remaining Characters : " + (500 - this.value.length));

});
$('#edit_experience_description3').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC6").html("Remaining Characters : " + (500 - this.value.length));

});
$('#edit_experience_description4').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC7").html("Remaining Characters : " + (500 - this.value.length));

});


        $(document).ready(function() {
            // var present_status_job = $(".present_status_job").val();
            // if (present_status_job == '1') {
            //     $("#add_experience_currently_working").attr("disabled", true);
            // }
            // var present_status_internship = $(".present_status_internship").val();
            // if (present_status_internship == '1') {
            //     $("#add_experience_currently_working2").attr("disabled", true);
            // }
            var present_status_training = $(".present_status_training").val();
            if (present_status_training == '1') {
                $("#add_experience_currently_working3").attr("disabled", true);
            }
        });

        function func_edit_experience(editid, type, userid, profile, organization, organization_online, start_year, end_year, currently_working, description, link) {

            //  alert(atob(description));
            //  var decode_description="<?php //echo base64_decode(".'+description+'.");?>";
            $('#editid').val(editid);
            $('#edit_experience_profile').val(profile);
            if (type == 3) {
                $('#edit_experience_organization_online').val(organization_online);
                if (organization_online == 1) {
                    $("#edit_experience_organization_online").prop("checked", true);
                }
            }
            if (type != 4) {
                $('#edit_experience_organization').val(organization);
               // $('#edit_experience_location').val(location);
            }
            $('#edit_experience_start_year').val(start_year);
            $('#edit_experience_end_year').val(end_year);
            $('#edit_experience_description').val(atob(description));
            document.getElementById("edit_experience_end_year").setAttribute("min", start_year);
            // $('#edit_experience_currently_working').val(currently_working);
            // if (currently_working == 1) {
            //     $("#edit_experience_currently_working").prop("checked", true);
            //     $('.edit_experience_working_hide').hide();
            // }

            if (type == 4) {
                $('#edit_experience_project_link').val(link);
            }

        }

        function func_edit_experience2(editid, type, userid, profile, organization, organization_online, start_year, end_year, currently_working, description, link) {

            // alert(location);
            $('#editid2').val(editid);
            $('#edit_experience_profile2').val(profile);
            if (type == 3) {
                $('#edit_experience_organization_online2').val(organization_online);
                if (organization_online == 1) {
                    $("#edit_experience_organization_online2").prop("checked", true);
                }
            }
            if (type != 4) {
                $('#edit_experience_organization2').val(organization);
               // $('#edit_experience_location2').val(location);
            }
            $('#edit_experience_start_year2').val(start_year);
            $('#edit_experience_end_year2').val(end_year);
            $('#edit_experience_description2').val(atob(description));
            // $('#edit_experience_currently_working2').val(currently_working);
            // if (currently_working == 1) {
            //     $("#edit_experience_currently_working2").prop("checked", true);
            //     $('.edit_experience_working_hide2').hide();
            // }

            if (type == 4) {
                $('#edit_experience_project_link2').val(link);
            }
            document.getElementById("edit_experience_end_year2").setAttribute("min", start_year);



        }

        function func_edit_experience3(editid, type, userid, profile, organization, organization_online, project_duration, project_duration_type, currently_working, description, link) {

            // alert(location);
            $('#editid3').val(editid);
            $('#edit_experience_profile3').val(profile);
                if (organization_online == 1) {
                    $("#edit_experience_organization_online3").prop("checked", true);
                }else{
                    $("#edit_experience_organization_online31").prop("checked", true);
                }
            if (type != 4) {
                $('#edit_experience_organization3').val(organization);
                //$('#edit_experience_location3').val(location);
            }
            $('#edit_experience_organization_online3_value').val(project_duration);
            $('#edit_training_duration').val(project_duration);
            $('#edit_training_duration_type').val(project_duration_type);
            $('#edit_experience_description3').val(atob(description));
            $('#edit_experience_currently_working3').val(currently_working);
            // if (currently_working == 1) {
            //     $("#edit_experience_currently_working3").prop("checked", true);
            //     $('.edit_experience_working_hide3').hide();
            // }

            if (type == 4) {
                $('#edit_experience_project_link3').val(link);
            }



        }

        function func_edit_experience4(editid, type, userid, profile, organization, organization_online, start_year, end_year, currently_working, description, link) {

            // alert(location);
            $('#editid4').val(editid);
            $('#edit_experience_profile4').val(profile);
            $('#edit_experience_organization4').val(organization);
            // $('#edit_experience_start_year4').val(start_year);
            // $('#edit_experience_end_year4').val(end_year);
            $('#edit_experience_description4').val(atob(description));
            // $('#edit_experience_currently_working4').val(currently_working);
            // if(currently_working==1){
            //     $( "#edit_experience_currently_working4" ).prop( "checked", true );
            //     $('.edit_experience_working_hide4').hide();
            // }
            $('#edit_experience_project_link4').val(link);
        }

        // $('#add_experience_currently_working').click(function() {
        //     if ($('#add_experience_currently_working').prop('checked') == true) {
        //         $('#add_experience_currently_working').val('1');
        //         $('.experience_working_hide').hide();
        //         $('#add_experience_end_year').val('');
        //     } else {
        //         $('.experience_working_hide').show();
        //         $('#add_experience_currently_working').val('0');
        //     }
        // });


        // $('#add_experience_currently_working2').click(function() {
        //     if ($('#add_experience_currently_working2').prop('checked') == true) {

        //         $('#add_experience_currently_working2').val('1');
        //         $('.experience_working_hide2').hide();
        //         $('#add_experience_end_year2').val('');
        //     } else {
        //         $('.experience_working_hide2').show();
        //         $('#add_experience_currently_working2').val('0');
        //     }
        // });
        $('#add_experience_currently_working3').click(function() {
            if ($('#add_experience_currently_working3').prop('checked') == true) {

                $('#add_experience_currently_working3').val('1');
                $('.experience_working_hide3').hide();
                $('#add_experience_end_year3').val('');
            } else {
                $('.experience_working_hide3').show();
                $('#add_experience_currently_working3').val('0');
            }
        });
        // $('#add_experience_currently_working4').click(function() {
        // if($('#add_experience_currently_working4').prop('checked')==true){

        //     $('#add_experience_currently_working4').val('1');
        //     $('.experience_working_hide4').hide();
        //     $('#add_experience_end_year4').val('');
        // } 
        // else{
        //     $('.experience_working_hide4').show();
        //     $('#add_experience_currently_working4').val('0');
        // }
        // });

        // $('#edit_experience_currently_working').click(function() {
        //     if ($('#edit_experience_currently_working').prop('checked') == true) {

        //         $('#edit_experience_currently_working').val('1');
        //         $('.edit_experience_working_hide').hide();
        //         $('#edit_experience_end_year').val('');
        //     } else {
        //         $('.edit_experience_working_hide').show();
        //         $('#edit_experience_currently_working').val('0');
        //     }
        // });
        // $('#edit_experience_currently_working2').click(function() {
        //     if ($('#edit_experience_currently_working2').prop('checked') == true) {

        //         $('#edit_experience_currently_working2').val('1');
        //         $('.edit_experience_working_hide2').hide();
        //         $('#edit_experience_end_year2').val('');
        //     } else {
        //         $('.edit_experience_working_hide2').show();
        //         $('#edit_experience_currently_working2').val('0');
        //     }
        // });
        $('#edit_experience_currently_working3').click(function() {
            if ($('#edit_experience_currently_working3').prop('checked') == true) {

                $('#edit_experience_currently_working3').val('1');
                $('.edit_experience_working_hide3').hide();
                $('#edit_experience_end_year3').val('');
            } else {
                $('.edit_experience_working_hide3').show();
                $('#edit_experience_currently_working3').val('0');
            }
        });
        // $('#edit_experience_currently_working4').click(function() {
        // if($('#edit_experience_currently_working4').prop('checked')==true){

        //     $('#edit_experience_currently_working4').val('1');
        //     $('.edit_experience_working_hide4').hide();
        //     $('#edit_experience_end_year4').val('');
        // } 
        // else{
        //     $('.edit_experience_working_hide4').show();
        //     $('#edit_experience_currently_working4').val('0');
        // }
        // });


        // $('#add_experience_organization_online3').click(function() {
        //     if ($('#add_experience_organization_online3').prop('checked') == true) {

        //         $('#add_experience_organization_online3').val('1');
        //     } else {
        //         $('#add_experience_organization_online3').val('0');
        //     }
        // });

        $('#edit_experience_organization_online3').click(function() {
            if ($('#edit_experience_organization_online3').prop('checked') == true) {

                $('#edit_experience_organization_online3').val('1');
            } else {
                $('#edit_experience_organization_online3').val('0');
            }
        });
        //job
        function experience_profile() {
            return validatetext_spcl_char_style('add_experience_profile', 'add_experience_profile_alert', 'Profile');
        }

        function experience_organization() {
            return validatecompanyname_style('add_experience_organization', 'add_experience_organization_alert', 'Organization');
        }

        function experience_description() { 
            return validate_description_special_not_required('add_experience_description', 'add_experience_description_alert');
        }
        // Intership
        function experience_profile2() {
            return validatetext_spcl_char_style('add_experience_profile2', 'add_experience_profile2_alert', 'Profile');
        }

        function experience_organization2() {
            return validatecompanyname_style('add_experience_organization2', 'add_experience_organization2_alert', 'Organization');
        }

        function experience_description2() { 
            return validate_description_special_not_required('add_experience_description2', 'add_experience_description2_alert');
        }
        // Training
        function experience_profile3() {
            return validate_textonly_style('add_experience_profile3', 'add_experience_profile3_alert', 'Training program');
        }

        function experience_organization3() {
            return validatecompanyname_style('add_experience_organization3', 'add_experience_organization3_alert', 'Organization');
        }

        function experience_description3() { 
            return validate_description_special_not_required('add_experience_description3', 'add_experience_description3_alert');
        }
        //Personal project
        function experience_profile4() {
            return validate_textonly_style('add_experience_profile4', 'add_experience_profile4_alert', 'Title');
        }
        function experience_description4() { 
            return validate_description_special_not_required('add_experience_description4', 'add_experience_description4_alert');
        }
        //job
        function experience_profile_edit() {
            return validatetext_spcl_char_style('edit_experience_profile', 'edit_experience_profile_alert', 'Profile');
        }

        function experience_organization_edit() {
            return validatecompanyname_style('edit_experience_organization', 'edit_experience_organization_alert', 'Organization');
        }

        function experience_description_edit() { 
            return validate_description_special_not_required('edit_experience_description', 'edit_experience_description_alert');
        }
        // Intership
        function experience_profile2_edit() {
            return validatetext_spcl_char_style('edit_experience_profile2', 'edit_experience_profile2_alert', 'Profile');
        }

        function experience_organization2_edit() {
            return validatecompanyname_style('edit_experience_organization2', 'edit_experience_organization2_alert', 'Organization');
        }
        function experience_description2_edit() { 
            return validate_description_special_not_required('edit_experience_description2', 'edit_experience_description2_alert');
        }
        // Training
        function experience_profile3_edit() {
            return validate_textonly_style('edit_experience_profile3', 'edit_experience_profile3_alert', 'Training program');
        }

        function experience_organization3_edit() {
            return validatecompanyname_style('edit_experience_organization3', 'edit_experience_organization3_alert', 'Organization');
        }

        function experience_description3_edit() { 
            return validate_description_special_not_required('edit_experience_description3', 'edit_experience_description3_alert');
        }
        //Personal project
        function experience_profile4_edit() {
            return validate_textonly_style('edit_experience_profile4', 'edit_experience_profile4_alert', 'Title');
        }

        function experience_description4_edit() { 
            return validate_description_special_not_required('edit_experience_description4', 'edit_experience_description4_alert');
        }







        $(document).ready(function() {
            //  job
            // $("#add_experience_location").change(function() {
            //     return validatetext_style('add_experience_location', 'add_experience_location_alert', 'Location');
            // });
            $("#add_experience_start_year").change(function() {
                return validatetext_style('add_experience_start_year', 'add_experience_start_year_alert', 'From Date');
            });
            $("#add_experience_end_year").change(function() {
                return validatetext_style('add_experience_end_year', 'add_experience_end_year_alert', 'Till Date');
            });

            // Intership
            // $("#add_experience_location2").change(function() {
            //     return validatetext_style('add_experience_location2', 'add_experience_location2_alert', 'Location');
            // });
            $("#add_experience_start_year2").change(function() {
                return validatetext_style('add_experience_start_year2', 'add_experience_start_year2_alert', 'From Date');
            });
            $("#add_experience_end_year2").change(function() {
                return validatetext_style('add_experience_end_year2', 'add_experience_end_year2_alert', 'Till Date');
            });

            // Training
            // $("#add_experience_location3").change(function() {
            //     return validatetext_style('add_experience_location3', 'add_experience_location3_alert', 'Location');
            // });
            $("#add_experience_start_year3").change(function() {
                return validatetext_style('add_experience_start_year3', 'add_experience_start_year3_alert', 'From Date');
            });
            $("#add_experience_end_year3").change(function() {
                return validatetext_style('add_experience_end_year3', 'add_experience_end_year3_alert', 'Till Date');
            });

            //Personal project
            $("#add_experience_organization4").change(function() {
                return validatetext_style('add_experience_organization4', 'add_experience_organization4_alert', 'Type');
            });
            $("#edit_experience_organization4").change(function() {
                return validatetext_style('edit_experience_organization4', 'edit_experience_organization4_alert', 'Type');
            });
            // $("#add_experience_end_year4").change(function() {
            //     return validatetext_style('add_experience_end_year4', 'add_experience_end_year4_alert', 'Till Date');
            // });

            // job

            // $("#edit_experience_location").change(function() {
            //     return validatetext_style('edit_experience_location', 'edit_experience_location_alert', 'Location');
            // });
            $("#edit_experience_start_year").change(function() {
                return validatetext_style('edit_experience_start_year', 'edit_experience_start_year_alert', 'From Date');
            });
            $("#edit_experience_end_year").change(function() {
                return validatetext_style('edit_experience_end_year', 'edit_experience_end_year_alert', 'Till Date');
            });

            // Intership

            // $("#edit_experience_location2").change(function() {
            //     return validatetext_style('edit_experience_location2', 'edit_experience_location2_alert', 'Location');
            // });
            $("#edit_experience_start_year2").change(function() {
                return validatetext_style('edit_experience_start_year2', 'edit_experience_start_year2_alert', 'From Date');
            });
            $("#edit_experience_end_year2").change(function() {
                return validatetext_style('edit_experience_end_year2', 'edit_experience_end_year2_alert', 'Till Date');
            });

            // Training
            // $("#edit_experience_location3").change(function() {
            //     return validatetext_style('edit_experience_location3', 'edit_experience_location3_alert', 'Location');
            // });
            $("#edit_experience_start_year3").change(function() {
                return validatetext_style('edit_experience_start_year3', 'edit_experience_start_year3_alert', 'From Date');
            });
            $("#edit_experience_end_year3").change(function() {
                return validatetext_style('edit_experience_end_year3', 'edit_experience_end_year3_alert', 'Till Date');
            });

            //Personal project
            // $("#edit_experience_start_year4").change(function() {
            //     return validatetext_style('edit_experience_start_year4', 'edit_experience_start_year4_alert', 'From Date');
            // });
            // $("#edit_experience_end_year4").change(function() {
            //     return validatetext_style('edit_experience_end_year4', 'edit_experience_end_year4_alert', 'Till Date');
            // });
        });


        $(document).ready(function() {
            $("#add_can_job_submit").click(function() {
                var add_experience_description = validate_description_special_not_required('add_experience_description', 'add_experience_description_alert');
                // var add_experience_currently_working = $('#add_experience_currently_working').val();
                // if (add_experience_currently_working != 1) {
                    var add_experience_end_year = validatetext_style('add_experience_end_year', 'add_experience_end_year_alert', 'Till Date');
                // } else {
                //     var add_experience_end_year = 1;
                // }
                var add_experience_start_year = validatetext_style('add_experience_start_year', 'add_experience_start_year_alert', 'From Date');
                //var add_experience_location = validatetext_style('add_experience_location', 'add_experience_location_alert', 'Location');
                var add_experience_organization = validatecompanyname_style('add_experience_organization', 'add_experience_organization_alert', 'Organization');
                var add_experience_profile = validatetext_spcl_char_style('add_experience_profile', 'add_experience_profile_alert', 'Profile');


                if (add_experience_description==0||add_experience_profile == 0 || add_experience_organization == 0 ||  add_experience_start_year == 0 || add_experience_end_year == 0) {
                    return false;
                }
            });
        });

        $(document).ready(function() {
            $("#add_can_internship_submit").click(function() {

                // var add_experience_currently_working2 = $('#add_experience_currently_working2').val();
                // if (add_experience_currently_working2 != 1) {
                //     var add_experience_end_year2 = validatetext_style('add_experience_end_year2', 'add_experience_end_year2_alert', 'Till Date');
                // } else {
                //     var add_experience_end_year2 = 1;
                // }
                var add_experience_description2 = validate_description_special_not_required('add_experience_description2', 'add_experience_description2_alert');
                var add_experience_end_year2 = validatetext_style('add_experience_end_year2', 'add_experience_end_year2_alert', 'Till Date');
                var add_experience_start_year2 = validatetext_style('add_experience_start_year2', 'add_experience_start_year2_alert', 'From Date');
                //var add_experience_location2 = validatetext_style('add_experience_location2', 'add_experience_location2_alert', 'Location');
                var add_experience_organization2 = validatecompanyname_style('add_experience_organization2', 'add_experience_organization2_alert', 'Organization');
                var add_experience_profile2 = validatetext_spcl_char_style('add_experience_profile2', 'add_experience_profile2_alert', 'Profile');


                if (add_experience_description2 ==0 || add_experience_profile2 == 0 || add_experience_organization2 == 0 ||  add_experience_start_year2 == 0 || add_experience_end_year2 == 0) {
                    return false;
                }
            });
        });

        $(document).ready(function() {
            $("#add_can_training_submit").click(function() {
                var add_experience_description3 = validate_description_special_not_required('add_experience_description3', 'add_experience_description3_alert');
                // var add_experience_currently_working3 = $('#add_experience_currently_working3').val();
                // if (add_experience_currently_working3 != 1) {
                    var add_training_duration_type = validatenumberwithzero_style('add_training_duration_type', 'add_training_duration_type_alert', 'Days Or Hours');
                // } else {
                //     var add_experience_end_year3 = 1;
                // }

                var add_training_duration = validatetext_style('add_training_duration', 'add_training_duration_alert', 'Training Duration');
                var add_experience_organization_online3_value = validatetext_style('add_experience_organization_online3_value', 'add_experience_organization_online3_value_alert', 'Training Mode');
                var add_experience_organization3 = validatecompanyname_style('add_experience_organization3', 'add_experience_organization3_alert', 'Organization');
                var add_experience_profile3 = validate_textonly_style('add_experience_profile3', 'add_experience_profile3_alert', 'Training program');


                if (add_experience_description3 == 0 || add_experience_profile3 == 0 || add_experience_organization3 == 0 ||  add_training_duration == 0 || add_training_duration_type == 0 || add_experience_organization_online3_value==0) {
                    return false;
                }
            });
        });

        $(document).ready(function() {


            $("#add_can_academic_submit").click(function() {
                // var add_experience_end_year4 = validatetext_style('add_experience_end_year4', 'add_experience_end_year4_alert', 'Till Date');
                // var add_experience_start_year4 = validatetext_style('add_experience_start_year4', 'add_experience_start_year4_alert', 'From Date');
                var add_experience_description4 = validate_description_special_not_required('add_experience_description4', 'add_experience_description4_alert');
                var add_experience_organization4 = validatecompanyname_style('add_experience_organization4', 'add_experience_organization4_alert', 'Type');
                var add_experience_profile4 = validate_textonly_style('add_experience_profile4', 'add_experience_profile4_alert', 'Title');


                if (add_experience_description4==0||add_experience_profile4 == 0||add_experience_organization4 == 0) {
                    return false;
                }
            });
        });

        $(document).ready(function() {
            $("#edit_can_job_submit").click(function() {
                // var edit_experience_currently_working = $('#edit_experience_currently_working').val();
                // if (edit_experience_currently_working != 1) {
                    var edit_experience_description = validate_description_special_not_required('edit_experience_description', 'edit_experience_description_alert');
                    var edit_experience_end_year = validatetext_style('edit_experience_end_year', 'edit_experience_end_year_alert', 'Till Date');
                // } else {
                //     var edit_experience_end_year = 1;
                // }

                var edit_experience_start_year = validatetext_style('edit_experience_start_year', 'edit_experience_start_year_alert', 'From Date');
                //var edit_experience_location = validatetext_style('edit_experience_location', 'edit_experience_location_alert', 'Location');
                var edit_experience_organization = validatecompanyname_style('edit_experience_organization', 'edit_experience_organization_alert', 'Organization');
                var edit_experience_profile = validatetext_spcl_char_style('edit_experience_profile', 'edit_experience_profile_alert', 'Profile');


                if (edit_experience_description==0 || edit_experience_profile == 0 || edit_experience_organization == 0 ||  edit_experience_start_year == 0 || edit_experience_end_year == 0) {
                    return false;
                }
            });
        });

        $(document).ready(function() {
            $("#edit_can_internship_submit").click(function() {
                // var edit_experience_currently_working2 = $('#edit_experience_currently_working2').val();
                // if (edit_experience_currently_working2 != 1) {
                //     var edit_experience_end_year2 = validatetext_style('edit_experience_end_year2', 'edit_experience_end_year2_alert', 'Till Date');
                // } else {
                //     var edit_experience_end_year2 = 1;
                // }
                var edit_experience_description2 = validate_description_special_not_required('edit_experience_description2', 'edit_experience_description2_alert');
                var edit_experience_end_year2 = validatetext_style('edit_experience_end_year2', 'edit_experience_end_year2_alert', 'Till Date');
                var edit_experience_start_year2 = validatetext_style('edit_experience_start_year2', 'edit_experience_start_year2_alert', 'From Date');
               // var edit_experience_location2 = validatetext_style('edit_experience_location2', 'edit_experience_location2_alert', 'Location');
                var edit_experience_organization2 = validatecompanyname_style('edit_experience_organization2', 'edit_experience_organization2_alert', 'Organization');
                var edit_experience_profile2 = validatetext_spcl_char_style('edit_experience_profile2', 'edit_experience_profile2_alert', 'Profile');


                if (edit_experience_description2==0||edit_experience_profile2 == 0 || edit_experience_organization2 == 0 || edit_experience_start_year2 == 0 || edit_experience_end_year2 == 0) {
                    return false;
                }
            });
        });

        $(document).ready(function() {
            $("#edit_can_training_submit").click(function() {
                var edit_experience_description3 = validate_description_special_not_required('edit_experience_description3', 'edit_experience_description3_alert');
                // var edit_experience_currently_working3 = $('#edit_experience_currently_working3').val();
                // if (edit_experience_currently_working3 != 1) {
                    var edit_training_duration_type = validatenumberwithzero_style('edit_training_duration_type', 'edit_training_duration_type_alert', 'Days Or Hours');
                // } else {
                //     var edit_experience_end_year3 = 1;
                // }

                var edit_training_duration = validatetext_style('edit_training_duration', 'edit_training_duration_alert', 'Training Duration');
                var edit_experience_organization_online3_value = validatetext_style('edit_experience_organization_online3_value', 'edit_experience_organization_online3_value_alert', 'Training Mode');
                var edit_experience_organization3 = validatecompanyname_style('edit_experience_organization3', 'edit_experience_organization3_alert', 'Organization');
                var edit_experience_profile3 = validate_textonly_style('edit_experience_profile3', 'edit_experience_profile3_alert', 'Training program');


                if (edit_experience_description3==0||edit_experience_organization_online3_value==0||edit_experience_profile3 == 0 || edit_experience_organization3 == 0 ||  edit_training_duration == 0 || edit_training_duration_type == 0) {
                    return false;
                }
            });
        });

        $(document).ready(function() {


            $("#edit_can_academic_submit").click(function() {
                var edit_experience_description4 = validate_description_special_not_required('edit_experience_description4', 'edit_experience_description4_alert');
                // var edit_experience_end_year4 = validatetext_style('edit_experience_end_year4', 'edit_experience_end_year4_alert', 'Till Date');
                // var edit_experience_start_year4 = validatetext_style('edit_experience_start_year4', 'edit_experience_start_year4_alert', 'From Date');
                var edit_experience_organization4 = validatecompanyname_style('edit_experience_organization4', 'edit_experience_organization4_alert', 'Type');
                var edit_experience_profile4 = validate_textonly_style('edit_experience_profile4', 'edit_experience_profile4_alert', 'Title');


                if (edit_experience_description4==0||edit_experience_profile4 == 0 || edit_experience_organization4==0) {
                    return false;
                }
            });
        });

        function func_start_year(start_year) {
            document.getElementById("add_experience_end_year").setAttribute("min", start_year);
        }

        function func_start_year2(start_year) {
            document.getElementById("add_experience_end_year2").setAttribute("min", start_year);
        }

        function func_start_year3(start_year) {
            document.getElementById("add_experience_end_year3").setAttribute("min", start_year);
        }

        function func_start_year4(start_year) {
            document.getElementById("add_experience_end_year4").setAttribute("min", start_year);
        }

        function func_start_year_edit(start_year) {
            document.getElementById("edit_experience_end_year").setAttribute("min", start_year);
        }

        function func_start_year_edit2(start_year) {
            document.getElementById("edit_experience_end_year2").setAttribute("min", start_year);
        }

        function func_start_year_edit3(start_year) {
            document.getElementById("edit_experience_end_year3").setAttribute("min", start_year);
        }

        // function func_start_year_edit4(start_year) {
        //     document.getElementById("edit_experience_end_year4").setAttribute("min", start_year);
        // }

        function func_organization_online(val) {
            $('#add_experience_organization_online3_value').val(val);
        }
        function func_organization_online_edit(val) {
            $('#edit_experience_organization_online3_value').val(val);
        }
    </script>


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
 <script>
    $(".datepicker").datepicker( {
    format: "mm-yyyy",
    startView: "months", 
    minViewMode: "months",
    maxDate: '+0Y',
});
 </script> -->

 <!-- <script>
     //Searchable textbox for Profile

     function searchOn(words, find, limit) {
            if (words == undefined || !Array.isArray(words)) {
                return [];
            }

            if (find == undefined || String(find).length <= 0) {
                return [];
            }

            if (limit == undefined || isNaN(limit)) {
                limit = null;
            }

            let matches = [];
            words.forEach((word) => {
                if (limit == 0) {
                    return matches;
                }

                if (word.toLowerCase() != find.toLowerCase()) {

                    if (word.toLowerCase().substr(0, find.length) == find.toLowerCase()) {
                        matches.push(word);

                        if (limit !== null) {
                            limit--;
                        }
                    }
                }
            });

            return matches;
        }
        let countries = [
            <?php $i = 1;
            foreach ($profile_data as $profile) {
         ?> "<?php echo $profile->profile; ?>",
            <?php } ?>

        ];
     let textboxes = document.querySelectorAll('.completeIt');
        textboxes.forEach((textbox) => {
            
            let input = textbox.querySelector('input[type="text"]');
            let autoComplete = textbox.querySelector('#profile_data');

            input.addEventListener('input', () => {
                let val = input.value;

                let matches = searchOn(countries, val, 10);

                let items = autoComplete.querySelectorAll('.item');

                let remains = [];
                items.forEach((item) => {
                    let save = false;
                    matches.forEach((match) => {
                        if (item.dataset.value == match) {
                            save = true;
                            remains.push(match);
                        }
                    });

                    if (!save) {
                        item.remove();
                    }
                });

                matches.forEach((match, index) => {
                    if (!remains.includes(match)) {
                        let item = document.createElement('a');
                        item.classList.add('item');
                        item.setAttribute('href', '#');

                        item.innerHTML = match;
                        item.dataset.value = match;

                        item.addEventListener('click', (event) => {
                            event.preventDefault();

                            input.value = match;

                            autoComplete.querySelectorAll('.item').forEach((item) => {
                                item.remove();
                            });

                            input.focus();
                        });

                        setTimeout(() => {
                            autoComplete.appendChild(item);
                        }, index * 50);
                    }
                });
            });

            input.addEventListener('keyup', (event) => {
                if (event.keyCode == 40) {
                    let firstItem = autoComplete.querySelector('.item');
                    if (firstItem != undefined) {
                        firstItem.focus();
                    }
                }
            });

        });
 </script> -->
 <script>
function autocomplete(inp, arr) {
// alert(inp);
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
   
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
   
        // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) 
        if ((arr[i].toUpperCase()).includes(val.toUpperCase())){
         
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = arr[i].substr(0, val.length);
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
    

      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var countries = [<?php $i = 1;
            foreach ($profile_data as $profile) {
         ?> "<?php echo $profile->profile; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("add_experience_profile"), countries);


function autocomplete1(inp, arr) {
// alert(inp);
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) 
        if ((arr[i].toUpperCase()).includes(val.toUpperCase())){
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = arr[i].substr(0, val.length);
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var countries = [<?php $i = 1;
            foreach ($profile_data as $profile) {
         ?> "<?php echo $profile->profile; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete1(document.getElementById("add_experience_profile2"), countries);


function autocomplete2(inp, arr) {
// alert(inp);
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) 
        if ((arr[i].toUpperCase()).includes(val.toUpperCase())){
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = arr[i].substr(0, val.length);
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var countries = [<?php $i = 1;
            foreach ($profile_data as $profile) {
         ?> "<?php echo $profile->profile; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete2(document.getElementById("edit_experience_profile"), countries);



function autocomplete3(inp, arr) {
// alert(inp);
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) 
        if ((arr[i].toUpperCase()).includes(val.toUpperCase())){
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = arr[i].substr(0, val.length);
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var countries = [<?php $i = 1;
            foreach ($profile_data as $profile) {
         ?> "<?php echo $profile->profile; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete3(document.getElementById("edit_experience_profile2"), countries);

</script>
</body>

</html>