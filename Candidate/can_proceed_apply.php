<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="stickyFoot">

    <?php require_once(APPPATH . "Views/Common/header.php"); 
     use App\Models\Candidate_model;
     $session = session();
     $Candidate_model = new Candidate_model();
     $userid    =    $session->get('userid');
     $complete_popup=$session->get('complete_popup');
    //  print_r($internship_details);
    // print_r($_SESSION);
    ?>

    <!----- Form ------>
    <section class="container my-4">
        <div class="card p-4">
        <form action="<?= base_url(); ?>/can_apply_internship" method="post" accept-charset="utf-8" class="px-5" enctype="multipart/form-data" >
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />    
        <h3 class="text-blue fw-semibold fs-4 mb-4"> <?php if(isset($internship_details->profile)) {echo $Candidate_model->get_master_name('master_profile',$internship_details->profile,'profile');}?> internship at <?php if(isset($emp_profile_details->profile_company_name)){echo $emp_profile_details->profile_company_name;}?> </h3>
            <h5 class="text-green fw-medium fs-5">Cover letter</h5>
            <div class="form-group mb-4">
                <label for="" class="form-label">Why should you be hired for this role?</label>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" id="userid" name="userid"  value="<?php if(isset($userid)){echo $userid;}?>">
                <input type="hidden" id="internship_id" name="internship_id"  value="<?php if(isset($internship_details->internship_id)){echo $internship_details->internship_id;}?>">
                <textarea name="add_hired_role_reason" id="add_hired_role_reason" class="form-control textarea-resize f-14 py-2" placeholder="Employers see the answer to this question even before they view your resume.Answer this question carefully and relevent information like your skills/experience and why you find the role exciting"></textarea>
                <font style="color:#dd4b39;"><div id="add_hired_role_reason_alert"></div></font>
            </div>
            <h5 class="text-green fw-medium fs-5">Availabilty</h5>
            <div class="form-group mb-4">
                <label for="" class="form-label">Are you available for <?php echo $internship_details->internship_duration; if($internship_details->internship_duration_type==1){ echo " week";}elseif($internship_details->internship_duration_type==2){ echo " months";}?> , starting immediately, for a full-time internship? If not, what is the time period you are available for and the earliest date you can start this internship on?</label>
                <textarea name="add_availablity" id="add_availablity" class="form-control textarea-resize f-14 py-2" placeholder="Eg: I am available full time in chennai for the next 6 months,but will have exams for 15 days in September"></textarea>
                <font style="color:#dd4b39;"><div id="add_availablity_alert"></div></font>            
            </div>
            <div class="d-flex justify-content-end">
                <input type="submit" class="btn btn-prim canvasBtn" id="can_proceed_apply_submit" value="Submit" />
                <!-- <button type="button" class="btn btn-prim canvasBtn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" data-backdrop="static" data-keyboard="false">Submit</button> -->
            </div>
        </form>
        </div>
    </section>

    <div class="offcanvas offcanvas-end <?php if(isset($complete_popup)){echo 'show';}?>" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="false">
        <div class="offcanvas-body text-center d-flex justify-content-center align-items-center flex-column">
            <img src="<?= base_url(); ?>/public/assets/img/std_otp.gif" alt="" width="100" class="mb-4">
            <h2 class="fw-bold text-blue fs-4 mb-4" id="offcanvasRightLabel">Your application has been submitted</h5>
                <!-- <p class="text-gray mb-4">You can track its status on your dashboard</p> -->
                <a href="<?= base_url(); ?>/my-applications" class="btn btn-prim">Done</a>
        </div>
    </div>

    <?php
     $session->remove('complete_popup');
    require_once(APPPATH . "Views/Common/script.php"); ?>
<script>
     $(document).ready(function() {
            $("#add_availablity").keyup(function() {
                return validatetext_style('add_availablity','add_availablity_alert','Availabilty'); 
            });
            $("#add_hired_role_reason").keyup(function() {
                return validatetext_style('add_hired_role_reason','add_hired_role_reason_alert','Cover letter');
            });
        });
    $(document).ready(function(){  
        $("#can_proceed_apply_submit").click(function(){
        var add_availablity    = validatetext_style('add_availablity','add_availablity_alert','Availabilty'); 
        var add_hired_role_reason    = validatetext_style('add_hired_role_reason','add_hired_role_reason_alert','Cover letter');
            if(add_hired_role_reason==0 ||add_availablity==0)
            {
                return false;
            }
        });
    });
 </script>
</body>

</html>