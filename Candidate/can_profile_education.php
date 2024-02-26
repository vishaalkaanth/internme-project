<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>
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
<!-- <style>
    .autoSearch .autoComplete .item1 {
  animation: showItem .3s ease forwards;
  background-color: #fff;
  box-shadow: 0 8px 8px -10px rgba(0, 0, 0, .4);
  box-sizing: border-box;
  color: #212529;
  cursor: pointer;
  display: block;
  font-size: 14px;
  opacity: 0;
  outline: none;
  padding: 10px;
  text-decoration: none;
  transform-origin: top;
  transform: translateX(10px);
}
.autoSearch .autoComplete .item1:focus {
  background-color: #24337d;
  color: #FFF;
}
.autoSearch .autoComplete .item2 {
  animation: showItem .3s ease forwards;
  background-color: #fff;
  box-shadow: 0 8px 8px -10px rgba(0, 0, 0, .4);
  box-sizing: border-box;
  color: #212529;
  cursor: pointer;
  display: block;
  font-size: 14px;
  opacity: 0;
  outline: none;
  padding: 10px;
  text-decoration: none;
  transform-origin: top;
  transform: translateX(10px);
}
.autoSearch .autoComplete .item2:focus {
  background-color: #24337d;
  color: #FFF;
}
.autoSearch .autoComplete .item3 {
  animation: showItem .3s ease forwards;
  background-color: #fff;
  box-shadow: 0 8px 8px -10px rgba(0, 0, 0, .4);
  box-sizing: border-box;
  color: #212529;
  cursor: pointer;
  display: block;
  font-size: 14px;
  opacity: 0;
  outline: none;
  padding: 10px;
  text-decoration: none;
  transform-origin: top;
  transform: translateX(10px);
}
.autoSearch .autoComplete .item3:focus {
  background-color: #24337d;
  color: #FFF;
}
</style> -->

<body class="">

    <?php require_once(APPPATH . "Views/Common/header.php"); 
    $session = session();
    $userid    =    $session->get('userid');
    use App\Models\Candidate_model;
    $Candidate_model = new Candidate_model(); 
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
            <?php require_once(APPPATH . "Views/Common/profile_side.php"); ?>

           
                <!----- start Session Alert ------>
                

            <div class="col-12 col-lg-9 profileRt d- flex-column justify-content-center p-lg-5 py-5 px-4">
            <div class="d-flex justify-content-center">
            <div class="col-12 col-lg-10 align-self-left">   
            <!----- start Session Alert ------>
            <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>
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
                                <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $company_logo;?>" alt="logo" class="img-fluid noStretch" width="40" style="border-radius: 50%;">
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
                                <h3 class="fw-medium mb-0 fs-6">You Are Almost There - <?php if (isset($intership_profile)) { echo $intership_profile;} ?> Internship At <?php if(isset($company_name)){echo $company_name;}?> </h3>
                        </div>
                       
                    </div>

                </div>
                <?php }?>
               </div>
            </div>

                <!----- End Session Alert ------>
                <h2 class="fs-title text-blue fw-medium text-center mb-4">Education Details</h2>
                <div class="d-flex justify-content-center mb-4">
                    <div class="col-12 col-lg-10">
                        <button type="button" class="btn btn-outlined-blue float-end" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-plus me-2" aria-hidden="true"></i> Add Education</button>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                    <form action="<?= base_url(); ?>/add_can_educational_details" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data" >
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />    
                    <div class="modal-content">
                            <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Education Details</h5>
                            </div>
                            <div class="modal-body pb-0 px-4">
                                <p class="text-blue">Enter Current / Recent Education Detail</p>
                                
                                <input type="hidden" id="userid" name="userid"  value="<?php if(isset($userid)){echo $userid;}?>">
                                <div class="d-flex flex-wrap row">
                                    <div class="col-12 col-lg-6  form-group selectField selectWidth">
                                        <label for="" class="form-label">College Name</label> <span style="color:red;">*</span>
                                        <div class="autocomplete" style="width:300px;">
                                        <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter College Name" id="add_education_college_name" name="add_education_college_name"  maxlength="100" >
                                        <font style="color:#dd4b39;"><div id="add_education_college_name_alert"></div></font>
                                    </div>
                                        <!-- <div class="autoSearch completeIt d-block z-index-99">
                                <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter College Name" id="add_education_college_name" name="add_education_college_name"  maxlength="100" >
                                <div class="icon"></div>
                                <div class="autoComplete" id="college_data"></div>
                                <font style="color:#dd4b39;"><div id="add_education_college_name_alert"></div></font>
                            </div> -->
                                       
                                    </div>
                                   
                                    <div class="col-12 col-lg-6 form-group selectField selectWidth">
                                        <label for="" class="form-label">Course</label> <span style="color:red;">*</span>
                                        <!-- <select id="add_education_course" name="add_education_course"  class="filledBox form-control f-14 border-0 mb-4" data-live-search="true" onchange="func_get_spec_by_courses(this.value)">
                                            <option value="">Select Course</option>
                                            <?php if (!empty($master_courses)) {
                                                foreach ($master_courses as $courses) { ?>
                                                    <option value="<?php echo $courses->id; ?>"><?php echo $courses->name; ?></option>
                                            <?php }
                                            } ?>
                                        </select> -->
                                        <div class="autocomplete" style="width:300px;">
                                        <input value="" maxlength="50" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Course" id="add_education_course" name="add_education_course" >
                                        <font style="color:#dd4b39;"><div id="add_education_course_alert"></div></font>
                                    </div>
                                        <!-- <div class="autoSearch completeIt d-block z-index-9">
                                <input value="" maxlength="50" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Course" id="add_education_course" name="add_education_course" >
                                <div class="icon"></div>
                                <div class="autoComplete" id="courses_data"></div>
                                 
                                        <font style="color:#dd4b39;"><div id="add_education_course_alert"></div></font>
                                        </div> -->
                                    </div>
                                    
                                    <div class="col-12 col-lg-6 form-group selectField selectWidth">
                                        <label for="" class="form-label">Specialization</label> <span style="color:red;">*</span>
                                        <!-- <select id="add_education_specialization" name="add_education_specialization" class="filledBox form-control f-14 border-0 mb-4">
                                            <option value="">Select Specialization</option>
                                            <?php if (!empty($master_courses)) {
                                                foreach ($master_specialization as $specialization) { ?>
                                                    <option value="<?php echo $specialization->id; ?>"><?php echo $specialization->name; ?></option>
                                            <?php }
                                            } ?>
                                        </select> -->
                                        <!-- <div class="autoSearch completeIt d-block">
                                <input value="" type="text"  maxlength="50" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Specialization" id="add_education_specialization" name="add_education_specialization" >
                                <div class="icon"></div>
                                <div class="autoComplete" id="specialization_data"></div>
                                 
                                        <font style="color:#dd4b39;"><div id="add_education_course_alert"></div></font>
                                        </div> -->
                                        <div class="autocomplete" style="width:300px;">
                                        <input value="" type="text"  maxlength="50" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Specialization" id="add_education_specialization" name="add_education_specialization" >
                                        <font style="color:#dd4b39;"><div id="add_education_specialization_alert"></div></font>
                                    </div>
                                       
                                    </div>
                                    
                                    <div class="col-12 col-lg-6 d-flex flex-wrap form-group selectField">
                                        <div class="col-12 col-lg-6 form-group selectField selectWidth pe-lg-2 pe-0">
                                            <label for="" class="form-label">Year of Joining</label> <span style="color:red;">*</span>
                                            <!-- <input type="month" id="add_education_start_year" name="add_education_start_year" onchange="func_start_year_add(this.value)" min="" max="" value="" class=" form-control f-14 border-left-0"> -->
                                            <select id="add_education_start_year" name="add_education_start_year" onchange="func_start_year_add(this.value)" class="filledBox form-control f-14 border-0 mb-4">
                                                <option value="">Select Year</option>
													<!-- <?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
														$year = $x;
                                                        echo "<option value='".$year."'>$year</option>";
													}
													?> -->
											</select>
                                            <font style="color:#dd4b39;"><div id="add_education_start_year_alert"></div></font>
                                        </div>
                                        
                                        <div class="col-12 col-lg-6 form-group selectField selectWidth ps-lg-2 ps-0">
                                            <label for="" class="form-label">Year of Passout</label> <span style="color:red;">*</span>
                                            <!-- <input type="month" id="add_education_end_year" name="add_education_end_year" min="" max="" value="" class=" form-control f-14 border-left-0"> -->
                                            <select id="add_education_end_year" name="add_education_end_year" class="filledBox form-control f-14 border-0 mb-4">
                                                <option value="">Select Year</option>
                                                    <!-- <?php
                                                    for ($x = (date('Y')+7); $x >= 1980; $x--) {
														$year = $x;
                                                        echo "<option value='".$year."'>$year</option>";
													}
													?> -->
                                            </select>
                                            <font style="color:#dd4b39;"><div id="add_education_end_year_alert"></div></font>
                                        </div>
                                        
                                    </div>
                                    <div class="col-12 col-lg-6 form-group selectField mb-4">
                                        <label for="" class="form-label">Score / CGPA </label>
                                        <select id="add_education_performance_scale" name="add_education_performance_scale" class="selectpicker form-control filledBox border-0 f-14" onchange="education_performance_scale(this.value)">
                                            <option value="">Select Score / CGPA</option>
                                            <option value="1">Percentage</option>
                                            <option value="2">CGPA (Scale of 10)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-6 form-group mb-4 education_performance_scale_show_per">
                                        <label for="" class="form-label">Percentage <span style="color:red;">*</span></label>
                                        <input type="text" maxlength="5" class="form-control filledBox border-0 py-2 f-14 mb-4" id="add_education_performance_per" name="add_education_performance_per" placeholder="Enter Percentage" >
                                         <font style="color:#dd4b39;"><div id="add_education_performance_per_alert"></div></font> 
                                    </div>
                                    <div class="col-12 col-lg-6 form-group mb-4 education_performance_scale_show_cgpa">
                                        <label for="" class="form-label">CGPA <span style="color:red;">*</span></label>
                                        <input type="text" maxlength="4" class="form-control filledBox border-0 py-2 f-14 mb-4" id="add_education_performance_cgpa" name="add_education_performance_cgpa" placeholder="Enter CGPA" >
                                         <font style="color:#dd4b39;"><div id="add_education_performance_cgpa_alert"></div></font> 
                                    </div>
                                </div>
                               
                            </div>
                            <div class="modal-footer border-top-0 justify-content-between pt-2 mb-3 pb-4 px-4">
                                <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                <input type="submit" class="btn btn-prim float-end" id="add_can_educational_submit" value="Submit" />
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- Modal -->
                <!-- Modal -->
                <div class="modal fade" id="exampleModal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                    <form action="<?= base_url(); ?>/edit_can_educationa_details" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data" >
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />    
                    <div class="modal-content">
                            <div class="modal-header justify-content-center border-bottom-0 pt-4">
                                <h5 class="modal-title text-green fw-semibold" id="exampleModalLabel">Education Details</h5>
                            </div>
                            <div class="modal-body pb-0 px-4">
                                <p class="text-blue">Enter Current / Recent Education detail</p>
                                
                                <input type="hidden" id="userid" name="userid"  value="<?php if(isset($userid)){echo $userid;}?>">
                                <input type="hidden" id="editid" name="editid"  value="">
                                <div class="d-flex flex-wrap row">
                                    <!-- <div class="col-md-6 form-group selectField mb-4">
                                        <label for="" class="form-label">College Name</label> <span style="color:red;">*</span>
                                        <select id="edit_education_college_name" name="edit_education_college_name" class="form-control filledBox border-0 f-14 mb-4">
                                            <option value="">Select college</option>
                                            <?php if (!empty($master_college)) {
                                                foreach ($master_college as $college) { ?>
                                                    <option value="<?php echo $college->id; ?>"><?php echo $college->college_name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <font style="color:#dd4b39;"><div id="edit_education_college_name_alert"></div></font>
                                    </div> -->
                                    <div class="col-12 col-lg-6  form-group selectField selectWidth">
                                        <label for="" class="form-label">College Name</label> <span style="color:red;">*</span>
                                  
                                        <!-- <div class="autoSearch completeIt d-block z-index-99">
                                <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter College Name" id="edit_education_college_name" name="edit_education_college_name" >
                                <div class="icon"></div>
                                <div class="autoComplete" id="college_data"></div>
                                <font style="color:#dd4b39;"><div id="edit_education_college_name_alert"></div></font>
                            </div> -->
                            <div class="autocomplete" style="width:300px;">
                            <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter College Name" id="edit_education_college_name" name="edit_education_college_name" >
                            <font style="color:#dd4b39;"><div id="edit_education_college_name_alert"></div></font>
                                    </div>
                                       
                                    </div>
                                   
                                    <div class="col-12 col-lg-6 form-group selectField">
                                        <label for="" class="form-label">Course</label> <span style="color:red;">*</span>
                                        <!-- <select id="edit_education_course" name="edit_education_course" class="form-control filledBox f-14 mb-4 border-0" onchange="func_get_spec_by_courses_edit(this.value)">
                                            <option value="">Select course</option>
                                            <?php if (!empty($master_courses)) {
                                                foreach ($master_courses as $courses) { ?>
                                                    <option value="<?php echo $courses->id; ?>"><?php echo $courses->name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <font style="color:#dd4b39;"><div id="edit_education_course_alert"></div></font> -->
                                        <!-- <div class="autoSearch completeIt d-block z-index-9">
                                <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Course" id="edit_education_course" name="edit_education_course" >
                                <div class="icon"></div>
                                <div class="autoComplete" id="courses_data"></div>
                                <font style="color:#dd4b39;"><div id="edit_education_course_alert"></div></font>
                            </div> -->
                            <div class="autocomplete" style="width:300px;">
                            <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Course" id="edit_education_course" name="edit_education_course" >
                                        <font style="color:#dd4b39;"><div id="edit_education_course_alert"></div></font>
                                    </div>
                                    </div>
                                    
                                    <div class="col-12 col-lg-6 form-group selectField">
                                        <label for="" class="form-label">Specialization</label> <span style="color:red;">*</span>
                                        <!-- <select id="edit_education_specialization" name="edit_education_specialization" class="form-control filledBox f-14 border-0 mb-4" >
                                            <option value="">Select specialization</option>
                                            <?php if (!empty($master_courses)) {
                                                foreach ($master_specialization as $specialization) { ?>
                                                    <option value="<?php echo $specialization->id; ?>"><?php echo $specialization->name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <font style="color:#dd4b39;"><div id="edit_education_specialization_alert"></div></font> -->
                                        <!-- <div class="autoSearch completeIt d-block">
                                <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Specialization" id="edit_education_specialization" name="edit_education_specialization" >
                                <div class="icon"></div>
                                <div class="autoComplete" id="specialization_data"></div>
                                <font style="color:#dd4b39;"><div id="edit_education_specialization_alert"></div></font>
                            </div> -->
                            <div class="autocomplete" style="width:300px;">
                            <input value="" type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Specialization" id="edit_education_specialization" name="edit_education_specialization" >
                                        <font style="color:#dd4b39;"><div id="edit_education_specialization_alert"></div></font>
                                    </div>
                                    </div>
                                    
                                    <div class="col-12 col-lg-6 d-flex flex-wrap form-group selectField">
                                        <div class="col-12 col-lg-6 form-group selectField pe-lg-2 pe-0">
                                            <label for="" class="form-label">Year of Joining</label> <span style="color:red;">*</span>
                                            <!-- <input type="month" id="edit_education_start_year" name="edit_education_start_year" onchange="func_start_year(this.value)" min="" max="" value="" class=" form-control f-14 border-left-0"> -->
                                            <select id="edit_education_start_year" name="edit_education_start_year" onchange="func_start_year(this.value)" class="form-control filledBox f-14 border-0 mb-4">
                                                <option value="">Select Year</option>
													<?php
                                                    for ($x = date('Y'); $x >= 1980; $x--) {
														$year = $x;
                                                        echo "<option value='".$year."'>$year</option>";
													}
													?>
											</select>
                                            <font style="color:#dd4b39;"><div id="edit_education_start_year_alert"></div></font>
                                        </div>
                                        
                                        <div class="col-12 col-lg-6 form-group selectField ps-lg-2 ps-0">
                                            <label for="" class="form-label">Year of Passout</label> <span style="color:red;">*</span>
                                            <!-- <input type="month" id="edit_education_end_year" name="edit_education_end_year" min="" max="" value="" class=" form-control f-14 border-left-0"> -->
                                            <select id="edit_education_end_year" name="edit_education_end_year" class="form-control filledBox f-14 border-0 mb-4">
                                                <option value="">Select Year</option>
                                                    <?php
                                                    for ($x = (date('Y')+7); $x >= 1980; $x--) {
														$year = $x;
                                                        echo "<option value='".$year."'>$year</option>";
													}
													?>
                                            </select>
                                            <font style="color:#dd4b39;"><div id="edit_education_end_year_alert"></div></font>
                                        </div>
                                        
                                    </div>
                                    <div class="col-12 col-lg-6 form-group selectField mb-4">
                                        <label for="" class="form-label">Score / CGPA </label>
                                        <select id="edit_education_performance_scale" name="edit_education_performance_scale" class="form-control filledBox border-0 f-14" onchange="education_performance_scale_edit(this.value)">
                                            <option value="">Select Score / CGPA</option>
                                            <option value="1">Percentage</option>
                                            <option value="2">CGPA (Scale of 10)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-6 form-group mb-4 edit_education_performance_scale_show_per">
                                        <label for="" class="form-label">Percentage <span style="color:red;">*</span></label>
                                        <input type="text" maxlength="5" class="form-control filledBox border-0 py-2 f-14 mb-4" id="edit_education_performance_per" name="edit_education_performance_per" placeholder="Enter Percentage" >
                                         <font style="color:#dd4b39;"><div id="edit_education_performance_per_alert"></div></font> 
                                    </div>
                                    <div class="col-12 col-lg-6 form-group mb-4 edit_education_performance_scale_show_cgpa">
                                        <label for="" class="form-label">CGPA <span style="color:red;">*</span></label>
                                        <input type="text" maxlength="4" class="form-control filledBox border-0 py-2 f-14 mb-4" id="edit_education_performance_cgpa" name="edit_education_performance_cgpa" placeholder="Enter CGPA" >
                                         <font style="color:#dd4b39;"><div id="edit_education_performance_cgpa_alert"></div></font> 
                                    </div>
                                </div>
                               
                            </div>
                            <div class="modal-footer border-top-0 justify-content-between pt-0 pb-4 px-4">
                                <button type="button" class="btn btn-outlined-blue" data-bs-dismiss="modal">Cancel</button>
                                <input type="submit" class="btn btn-prim float-end" id="edit_can_educational_submit" value="Submit" />
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- Modal -->
                
                <div class="d-flex justify-content-center">
                <div class="col-12 col-lg-10 align-self-start">
                        <div class="card p-4 mb-4">
                            <?php  if(empty($education_details)){?>
                            <div class="text-center">
                                <p class="mb-5">Please Add Education Details</p>
                                <img src="<?= base_url(); ?>/public/assets/img/add_illu.svg" alt="" class="img-fluid" width="250">
                            </div>
                            <?php } else{
                                foreach($education_details as $education){?>
                            <div class="filledData">
                                <div class="d-flex flex-wrap flex-column flex-md-row justify-content-between align-items-md-center mb-2">
                                <?php
                                    if(!empty($education->education_course)){
                                      $where_can = array('status' => '1', 'id' => $education->education_course);
                                      $academic_courses_details = $Candidate_model->fetch_table_row('master_academic_courses', $where_can);
                                      if($academic_courses_details->course_duration!=0){
                                        if(date('m') > 5){
                                          $education_start_year_fin=$education->education_start_year+1;
                                        }else{
                                          $education_start_year_fin=$education->education_start_year;
                                        }
                                        $student_study_year=$education_start_year_fin+$academic_courses_details->course_duration;
                                        // echo $student_study_year;
                                        
                                        
                                        if ( date('m') > 5 ) { $current_year=date('Y')+ 1;} else { $current_year = date('Y');}
                                        // echo $current_year;
                                        if($student_study_year >= $current_year){
                                            $study_year=$current_year-$education->education_start_year;
                                            if($study_year==1){
                                              $final_study_year= "1st Year";
                                            }elseif($study_year==2){
                                              $final_study_year= "2nd Year";
                                            }elseif($study_year==3){
                                              $final_study_year= "3rd Year";
                                            }elseif($study_year==4){
                                              $final_study_year= "4th Year";
                                            }
                                           
                                        }
                                      }
                                    }
                                    ?>
                                    <h3 class="text-dark fs-5 fw-medium mb-md-0 mb-2"><?php if(isset($education->education_course) && $education->education_course!=0){echo $Candidate_model->get_master_name('master_academic_courses', $education->education_course, 'name');}else{echo $education->education_course_other; }?> ( <?php if(isset($education->education_course) && $education->education_specialization!=0){echo $Candidate_model->get_master_name('master_academic_branch', $education->education_specialization, 'name');}else{echo $education->education_specialization_other;}?> )<?php if(isset($final_study_year)){ echo ' - '.$final_study_year;}?></h3>
                                    
                                    
                                    <div>
                                        <?php if($education->education_college_name!=0){
                                              $where = array('id' => $education->education_college_name);
                                              $college_id = $Candidate_model->fetch_table_row('master_college',$where);
                                              $college_name=$college_id->college_name;
                                        }else{
                                            $college_name=$education->education_college_name_other;
                                        }
                                        if($education->education_course!=0){
                                            $where = array('id' => $education->education_course);
                                            $course_id = $Candidate_model->fetch_table_row('master_academic_courses',$where);
                                            $course_name=$course_id->name;
                                      }else{
                                          $course_name=$education->education_course_other;
                                      }
                                      if($education->education_specialization!=0){
                                        $where = array('id' => $education->education_specialization);
                                        $specialization_id = $Candidate_model->fetch_table_row('master_academic_branch',$where);
                                        $specialization_name=$specialization_id->name;
                                  }else{
                                      $specialization_name=$education->education_specialization_other;
                                  }
                                        ?>
                                        <a href="" onclick="func_edit_educational('<?php echo $education->id;?>','<?php echo $userid;?>','<?php echo $college_name;?>','<?php echo $course_name;?>','<?php echo $specialization_name;?>','<?php echo $education->education_start_year;?>','<?php echo $education->education_end_year;?>','<?php echo $education->education_performance_scale_optional;?>','<?php echo $education->education_performance_optional;?>')" type="button" class="text-blue edit me-4" data-bs-toggle="modal" data-bs-target="#exampleModal_edit"><i class="fa fa-pencil me-2" aria-hidden="true"></i>Edit</a>
                                        <!-- <a href="<?= base_url(); ?>/delete_can_educationa_details/<?php echo $education->id;?>" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a> -->
                                        <a onclick="func_delete_education('<?php echo $education->id; ?>')" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a>
                                    </div>
                                </div> 
                                <h6 class="fw-normal text-muted f-14"><?php if(isset($education->education_college_name) && $education->education_college_name!=0){echo $Candidate_model->get_master_name('master_college', $education->education_college_name, 'college_name');}else{ echo $education->education_college_name_other; }?></h6>
                                <ul class="d-flex flex-wrap flex-column flex-md-row ps-0 mb-0 list-unstyled">
                                    <li class="text-muted me-4"><img src="<?= base_url(); ?>/public/assets/img/icon_duration_gray.svg" class="me-2" width="12">
                                    <?php if(isset($education->education_start_year)){
                                      $years_edu=$education->education_end_year - $education->education_start_year;
                                       if($years_edu=='1'){ echo  $years_edu. " year "; }else{ echo  $years_edu. " years";}}?></li>
                                    <li class="text-muted me-4"><img src="<?= base_url(); ?>/public/assets/img/icon_cal_gray.svg" class="me-2" width="12"><?php if(isset($education->education_start_year)){echo $education->education_start_year;}?> - <?php if(isset($education->education_end_year)){echo $education->education_end_year;}?></li>
                                    <li class="text-muted"><?php if(isset($education->education_performance_optional) && !empty($education->education_performance_optional)){ ?> <img src="<?= base_url(); ?>/public/assets/img/icon_mark_gray.svg" class="me-2" width="16"> <?php if($education->education_performance_scale_optional==1) {echo "Percentage : ".$education->education_performance_optional;} elseif($education->education_performance_scale_optional==2) {echo "CGPA : ".$education->education_performance_optional;}}?> <?php if(isset($education->education_performance_scale_optional) && !empty($education->education_performance_scale_optional) && ($education->education_performance_optional) && !empty($education->education_performance_optional)){ if($education->education_performance_scale_optional==1){echo "%";}else{echo "(Scale of 10)";}}else{echo "";}?></li>
                                </ul>
                            </div>
                            <?php }}?>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url(); ?>/personal-details" class="btn btn-prev me-2">Previous</a>
                            <div>
                                <!-- <a href="<?= base_url(); ?>/can_profile_personal" class="btn btn-outlined-blue me-2">Skip</a> -->
                                
                                <?php
                                $next_but_status=$session->get('next_but_status'); 
                                if(empty($education_details)){?>
                                    <?php if(isset($edit_profile) && ($edit_profile==1)){
                                        if(isset($next_but_status) && $next_but_status=='1'){
                                        ?>
                                         <button onclick="func_no_data()" class="btn btn-prim">Next</button>
                                        
                                        <?php } else{?>
                                            <button onclick="func_no_data()" class="btn btn-prim">Save & Continue</button>
                                        <?php }  } elseif(isset($profile_page_view) && ($profile_page_view==1)){ ?>
                                            <button onclick="func_no_data()" class="btn btn-prim">Save</button>
                                           <?php } else { ?>
                                    <button onclick="func_no_data()" class="btn btn-prim">Next</button>
                                    <?php }
                                 }else{?>
                                 <?php if(isset($edit_profile) && ($edit_profile==1)){
                                    $ses_data = [
                                        'updated_status'=> 1
                                    ];
                                    $session->set($ses_data);
                                    $intership_id=$session->get('intership_number');
                                    if(isset($next_but_status) && $next_but_status=='1'){ ?>
                                            <a href="<?= base_url(); ?>/experience-details" class="btn btn-prim">Next</a>
                                      <?php  } else{
                                    ?>
                                    <a href="<?= base_url(); ?>/can-apply-for-internship/<?= $intership_id; ?>" class="btn btn-prim">Save & Continue</a>
                                    <?php } } elseif(isset($profile_page_view) && ($profile_page_view==1)){ ?>
                                        <a href="<?= base_url(); ?>/profile-details" class="btn btn-prim">Save</a>
                                    <?php  } else { ?>
                                    <a href="<?= base_url(); ?>/experience-details" class="btn btn-prim">Next</a>
                            <?php } }?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    
    <script> 
    function func_no_data(){
        swal({
                  title: "Alert",
                  text: "Please Add Your Education Details.",
                  type: "info",
                  showCancelButton: true,
                  confirmButtonClass: "btn-primary",
                  confirmButtonText: "Proceed",
                  cancelButtonText: "Cancel",
                  closeOnConfirm: false,
                  closeOnCancel: false
                },function(isConfirm) {

if (isConfirm) {
    window.location.reload();
} else {
    location.reload();
}
})  
    }

    function func_delete_education(id){
            swal({
                    title: "Are you sure ?",
                    text: "You want to remove this education detail",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonClass: "btn-primary",
                    confirmButtonText: "Proceed",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {

                    if (isConfirm) {
                        window.location.href = '<?= base_url("delete_can_educationa_details"); ?>/'+ id;

                    } else {
                        location.reload();
                    }
                })
        }

    function func_edit_educational(editid,userid,college_name,course,specialization,start_year,end_year,scale,performance){
        
        //alert(modal);
        $('#editid').val(editid);
        $('#edit_education_college_name').val(college_name);
        $('#edit_education_course').val(course);
        $('#edit_education_specialization').val(specialization);
        $('#edit_education_start_year').val(start_year);
        $('#edit_education_end_year').val(end_year);
        $('#edit_education_performance_scale').val(scale);
       
        
        // if(performance!=''){
        //  $('.education_performance_scale_show1').css('display','block'); 
        //     }else{
        //         $('.education_performance_scale_show1').css('display','none');
        //     }
// alert(scale);
            if(scale==1){
                $('.edit_education_performance_scale_show_per').css('display','block'); 
                $('.edit_education_performance_scale_show_cgpa').css('display','none');
                $('#edit_education_performance_per').val(performance);
            }
            else if(scale==2){
                $('.edit_education_performance_scale_show_cgpa').css('display','block');
                $('.edit_education_performance_scale_show_per').css('display','none');
                $('#edit_education_performance_cgpa').val(performance);
            }
            else{
                $('.edit_education_performance_scale_show_per').css('display','none');
                $('.edit_education_performance_scale_show_cgpa').css('display','none');
            }
            document.getElementById("edit_education_end_year").setAttribute("min", start_year);
            for(var i=1980; i<=start_year; i++)
        {
             $("#edit_education_end_year option[value='"+i+"']").attr("disabled","disabled");
        }
        

    }
   
    $(document).ready(function() {
        // Add
            $("#add_education_college_name").keyup(function() {
                return validatetext_spcl_char_style('add_education_college_name','add_education_college_name_alert','College Name');
            });
            $("#add_education_course").change(function() {
                return validatetext_spcl_char_style('add_education_course','add_education_course_alert','Course');   
            });
            $("#add_education_specialization").change(function() {
                return validatetext_spcl_char_style('add_education_specialization','add_education_specialization_alert','Specialization');
            });
            $("#add_education_start_year").change(function() {
                return validatetext_style('add_education_start_year','add_education_start_year_alert','From');
            });
            $("#add_education_end_year").change(function() {
                return validatetext_style('add_education_end_year','add_education_end_year_alert','Till');
            });
            $("#add_education_performance_per").keyup(function() {
                var value=$('#add_education_performance_scale').val();
        if(value == 1)
        {
            return validate_percentage_not_required('add_education_performance_per','add_education_performance_per_alert','Percentage');
           
        }
    });
    $("#add_education_performance_cgpa").keyup(function() {
                var value=$('#add_education_performance_scale').val();
        if(value == 2)
         {
             return validate_gpa_not_required('add_education_performance_cgpa','add_education_performance_cgpa_alert','CGPA');
         }
    });
          
        // Edit
            $("#edit_education_college_name").keyup(function() {
                return validatetext_style('edit_education_college_name','edit_education_college_name_alert','College Name');
            });
            $("#edit_education_course").change(function() {
                return validatetext_style('edit_education_course','edit_education_course_alert','Course');   
            });
            $("#edit_education_specialization").change(function() {
                return validatetext_style('edit_education_specialization','edit_education_specialization_alert','Specialization');
            });
            $("#edit_education_start_year").change(function() {
                return validatetext_style('edit_education_start_year','edit_education_start_year_alert','From');
            });
            $("#edit_education_end_year").change(function() {
                return validatetext_style('edit_education_end_year','edit_education_end_year_alert','Till');
            });
            
            $("#edit_education_performance_per").keyup(function() {
                var value=$('#edit_education_performance_scale').val();
        if(value == 1)
        {
            return validate_percentage_not_required('edit_education_performance_per','edit_education_performance_per_alert','Percentage');
           
        }
    });
    $("#edit_education_performance_cgpa").keyup(function() {
                var value=$('#edit_education_performance_scale').val();
        if(value == 2)
         {
             return validate_gpa_not_required('edit_education_performance_cgpa','edit_education_performance_cgpa_alert','CGPA');
         }
    });
           
});


    $(document).ready(function(){  
         $("#add_can_educational_submit").click(function(){
            var add_education_performance_scale=$('#add_education_performance_scale').val();
            // alert(add_education_performance_scale);
            if(add_education_performance_scale==1){
              var add_education_performance    = validate_percentage_not_required('add_education_performance_per','add_education_performance_per_alert','Performance');
            }
            else if(add_education_performance_scale==2){
                var add_education_performance    = validate_gpa_not_required('add_education_performance_cgpa','add_education_performance_cgpa_alert','CGPA');
            }
            else{
                add_education_performance=1;
                // $("#add_education_performance_alert").html("");
            }
        var add_education_end_year    = validatetext_style('add_education_end_year','add_education_end_year_alert','Year of Passout');
        var add_education_start_year    = validatetext_style('add_education_start_year','add_education_start_year_alert','Year of Joining');
        var add_education_specialization    = validatetext_spcl_char_style('add_education_specialization','add_education_specialization_alert','Specialization');
        var add_education_course    = validatetext_spcl_char_style('add_education_course','add_education_course_alert','Course');   
        var add_education_college_name    = validatetext_spcl_char_style('add_education_college_name','add_education_college_name_alert','College Name');

    
     if(add_education_performance==0 ||add_education_end_year==0 ||add_education_start_year==0 ||add_education_specialization==0 ||add_education_course==0 ||add_education_college_name==0)
     {
         return false;
     } 
 });
 });

    $(document).ready(function(){  
            $("#edit_can_educational_submit").click(function(){
                var edit_education_performance_scale=$('#edit_education_performance_scale').val();
            // alert(edit_education_performance_scale);
            if(edit_education_performance_scale==1){
              var edit_education_performance    = validate_percentage_not_required('edit_education_performance_per','edit_education_performance_per_alert','Performance');
            }
            else if(edit_education_performance_scale==2){
                var edit_education_performance    = validate_gpa_not_required('edit_education_performance_cgpa','edit_education_performance_cgpa_alert','CGPA');
            }
            else{
                edit_education_performance=1;
                // $("#edit_education_performance_alert").html("");
            }
        var edit_education_end_year    = validatetext_style('edit_education_end_year','edit_education_end_year_alert','Year of Passout');
        var edit_education_start_year    = validatetext_style('edit_education_start_year','edit_education_start_year_alert','Year of Joining');
        var edit_education_specialization    = validatetext_spcl_char_style('edit_education_specialization','edit_education_specialization_alert','Specialization');
        var edit_education_course    = validatetext_spcl_char_style('edit_education_course','edit_education_course_alert','Course');   
        var edit_education_college_name    = validatetext_spcl_char_style('edit_education_college_name','edit_education_college_name_alert','College Name');

        
        if(edit_education_performance==0 ||edit_education_end_year==0 ||edit_education_start_year==0 ||edit_education_specialization==0 ||edit_education_course==0 ||edit_education_college_name==0)
        {
            return false;
        } 
    });
    });

    function func_start_year(start_year){

             $("#edit_education_end_year option[value]").prop("disabled", false);
      // alert(start_year);
        document.getElementById("edit_education_end_year").setAttribute("min", start_year);
        for(var i=1980; i<=start_year; i++)
        {
             $("#edit_education_end_year option[value='"+i+"']").attr("disabled","disabled");
        }
    }

    function func_start_year_add(start_year){
        // document.getElementById("add_education_end_year").setAttribute("min", start_year);
        for(var i=1980; i<=start_year; i++)
        {
             $("#add_education_end_year option[value='"+i+"']").attr("disabled","disabled");
        }
    }

    function func_get_spec_by_courses(value) {
        // alert(value);
        var csrftokenname = "csrf_test_name=";
		var csrftokenhash = $(".csrf").val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('get_spec_by_courses'); ?>",
            data: "&courses=" + encodeURIComponent(value)+ "&" + csrftokenname + csrftokenhash,
            success: function(resp) {
                //  alert(resp);
                var splitted_data = resp.split("^");
			    $(".csrf").val(splitted_data[1]);    
                document.getElementById("add_education_specialization").innerHTML = splitted_data[0];
                return true;

            },

        });
    }
    function func_get_spec_by_courses_edit(value) {
        // alert(value);
        var csrftokenname = "csrf_test_name=";
		var csrftokenhash = $(".csrf").val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('get_spec_by_courses_edit'); ?>",
            data: "&courses=" + encodeURIComponent(value)+ "&" + csrftokenname + csrftokenhash,
            success: function(resp) {
                //  alert(resp); 
                var splitted_data = resp.split("^");
			    $(".csrf").val(splitted_data[1]);   
                document.getElementById("edit_education_specialization").innerHTML = splitted_data[0];
                return true;

            },

        });
    }
    function validate_percentage_not_required(value, alertarea) // function to validate for text box
{
    var alertarea = alertarea;
    var contentid = $("#" + value);
    var field_id = $('#'+value).val();
    var x = parseFloat(field_id);
    
     if (isNaN(contentid.val())) {
        contentid.focus();
        $("#" + alertarea).html("Numeric Value Only Allowed");
        $("#"+alertarea).addClass('alertMsg');
        return false;
    }
    else if(isNaN(x) || x < 10 || x > 100)
    {
        $("#" + alertarea).html("Enter Valid Percentage"); 
        $("#"+alertarea).addClass('alertMsg'); 
        return false;  
    }
       else
    {
        $("#" + alertarea).html("");
    }


}
    


    </script>
    <script>
         //Searchable textbox fpr college Name

     

        $(document).ready(function() 
    {
    $('.education_performance_scale_show_per').css('display','none');
    $('.education_performance_scale_show_cgpa').css('display','none');
    $('.edit_education_performance_scale_show_per').css('display','none');
    $('.edit_education_performance_scale_show_cgpa').css('display','none');
    });

        function education_performance_scale(val){
            if(val==1){
                $('.education_performance_scale_show_per').css('display','block'); 
                $('.education_performance_scale_show_cgpa').css('display','none');
            }
            else if(val==2){
                $('.education_performance_scale_show_cgpa').css('display','block');
                $('.education_performance_scale_show_per').css('display','none');
            }
            else{
                $('.education_performance_scale_show_per').css('display','none');
                $('.education_performance_scale_show_cgpa').css('display','none');
            }
            
        }

        function education_performance_scale_edit(val){
            if(val==1){
                $('.edit_education_performance_scale_show_per').css('display','block'); 
                $('.edit_education_performance_scale_show_cgpa').css('display','none');
            }
            else if(val==2){
                $('.edit_education_performance_scale_show_cgpa').css('display','block');
                $('.edit_education_performance_scale_show_per').css('display','none');
            }
            else{
                $('.edit_education_performance_scale_show_per').css('display','none');
                $('.edit_education_performance_scale_show_cgpa').css('display','none');
            }
            
        }
$(document).ready(function() 
    { $('.education_performance_scale_show1').css('display','block'); });



function validate_gpa_not_required(value, alertarea) // function to validate for text box
{
    var alertarea = alertarea;
    var contentid = $("#" + value);
    var field_id = $('#'+value).val();
    var x = parseFloat(field_id);
    
     if (isNaN(contentid.val())) {
        contentid.focus();
        $("#" + alertarea).html("Numeric Value Only Allowed");
        $("#"+alertarea).addClass('alertMsg');
        return false;
    }
    else if(isNaN(x) || x < 0.1 || x > 10)
    {
        $("#" + alertarea).html("Enter Valid CGPA"); 
        $("#"+alertarea).addClass('alertMsg'); 
        return false;  
    }
       else
    {
        $("#" + alertarea).html("");
    }
}

        </script>
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
        if ((arr[i].toUpperCase()).includes(val.toUpperCase()))  {
         
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
            foreach ($master_college as $college) {
         ?> "<?php echo $college->college_name; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("add_education_college_name"), countries);


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
          b.innerHTML =arr[i].substr(0, val.length);
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
            foreach ($master_courses as $courses) {
         ?> "<?php echo $courses->name; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete1(document.getElementById("add_education_course"), countries);


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
             foreach ($master_specialization as $specialization) { ?>
         "<?php echo $specialization->name; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete2(document.getElementById("add_education_specialization"), countries);


/*edit script */

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
            foreach ($master_college as $college) {
         ?> "<?php echo $college->college_name; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete3(document.getElementById("edit_education_college_name"), countries);


function autocomplete4(inp, arr) {
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
            foreach ($master_courses as $courses) {
         ?> "<?php echo $courses->name; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete4(document.getElementById("edit_education_course"), countries);


function autocomplete5(inp, arr) {
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
             foreach ($master_specialization as $specialization) { ?>
         "<?php echo $specialization->name; ?>",
            <?php } ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete5(document.getElementById("edit_education_specialization"), countries);
</script>
<script>
        var currentYear = new Date().getFullYear();

        // Populate Start Year dropdown
        var startYearSelect = document.getElementById("add_education_start_year");
        for (var x = currentYear; x >= 1980; x--) {
        // for (var x = currentYear - 100; x <= (currentYear + 5); x++) {
            var yearOption = document.createElement("option");
            yearOption.value = x;
            yearOption.text = x;
            startYearSelect.add(yearOption);
        }
    
       
        $(document).ready(function() {
    $("#add_education_start_year").change(function() {
        // Clear existing options in End Year dropdown
        $("#add_education_end_year").empty();

        // Get the selected start year value
        var startYear = parseInt($(this).val(), 10);

        // Append an empty option to the End Year dropdown
        $("#add_education_end_year").append('<option value="">Select Year</option>');

        // Populate End Year dropdown
        for (var x = startYear + 1; x <= (startYear + 6); x++) {
            $("#add_education_end_year").append('<option value="' + x + '">' + x + '</option>');
        }
    });
});



    </script>

<script>
        var currentYear = new Date().getFullYear();

        // Populate Start Year dropdown
        var startYearSelect = document.getElementById("edit_education_start_year");
        for (var x = currentYear; x >= 1980; x--) {
        // for (var x = currentYear - 100; x <= (currentYear + 5); x++) {
            var yearOption = document.createElement("option");
            yearOption.value = x;
            yearOption.text = x;
            startYearSelect.add(yearOption);
        }
    
       
        $(document).ready(function() {
    $("#edit_education_start_year").change(function() {
        // Clear existing options in End Year dropdown
        $("#edit_education_end_year").empty();

        // Get the selected start year value
        var startYear = parseInt($(this).val(), 10);

        // Append an empty option to the End Year dropdown
        $("#edit_education_end_year").append('<option value="">Select Year</option>');

        // Populate End Year dropdown
        for (var x = startYear + 1; x <= (startYear + 6); x++) {
            $("#edit_education_end_year").append('<option value="' + x + '">' + x + '</option>');
        }
    });
});



    </script>
</body>

</html>