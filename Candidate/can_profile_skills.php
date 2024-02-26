<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="">

    <?php require_once(APPPATH . "Views/Common/header.php");
     use App\Models\Candidate_model;
    $Candidate_model = new Candidate_model(); 
    $session = session();
    $userid    =    $session->get('userid');
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
            <?php 
                $skill_ids=array();
             if (!empty($skill_details)) {
                foreach ($skill_details as $skill) {
                    $skill_ids[]= $skill->skills;
                }
                //  print_r($skill_ids);
            }?>
            <div class="col-12 col-lg-9 profileRt d-flex flex-column justify-content-center p-lg-5 py-5 px-4">
            <div class="col-12 col-lg-10 align-self-center">
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
                                <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $company_logo;?>" alt="logo" class="img-fluid noStretch" style="border-radius:50%;" width="40">
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
            </div>
                <h2 class="fs-title text-blue fw-medium text-center mb-5">Skills</h2>
                <form action="<?= base_url(); ?>/add_can_skills" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data" >
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" id="userid" name="userid"  value="<?php if(isset($userid)){echo $userid;}?>">
                <div class="d-flex justify-content-center mb-4 row">
                <div class="col-12 col-lg-10 d-flex flex-wrap row">
                    <div class="col-12 col-md-6 col-lg-4 selectWidth">
                        <div class="mb-4">
                    <label for="" class="form-label">Add Skills</label>
                            <select id="add_skill" name="add_skill" class="js-states selectSearch form-control filledBox border-0 f-14" aria-label="Default select example" data-live-search="true">
                                <option value="">Select Skills</option>
                                <?php if (!empty($master_skills)) {
                                    foreach ($master_skills as $skills) { ?>
                                        <option value="<?php echo $skills->id; ?>" <?php if(in_array($skills->id, $skill_ids)){ echo "disabled"; }?>><?php echo $skills->skill_name; ?></option>
                                <?php }
                                } ?>
                            </select>
                       
                    </div>
                    <font style="color:#dd4b39;"><div id="add_skill_alert"></div></font>
                </div>
                    <div class="col-12 col-md-6 col-lg-4">
                    <div class="mb-4">
                    <label for="" class="form-label">Level</label>
                            <select id="add_skill_level" name="add_skill_level" class="selectpicker form-control filledBox f-14 border-0">
                                <option value="">Select level</option>
                                <option value="1">Beginner</option>
                                <option value="2">Intermediate</option>
                                <option value="3">Advanced</option>
                            </select>
                           
                    </div>
                   
                    <font style="color:#dd4b39;"><div id="add_skill_level_alert"></div></font>
                    </div>
                    <div class="col-12 col-lg-3 align-self-center mt-4 mt-lg-0">
                         <input type="submit" class="btn btn-prim" id="can_profile_skill_submit" value="Add" />
                    </div>
                </div>
                            </div>
                </form>
               
                <div class="d-flex justify-content-center">
                    <div class="col-12 col-lg-10 d-flex row align-self-start">
                    <?php if (!empty($skill_details)) {
                        foreach ($skill_details as $skill) { ?>
                        <div class="col-md-6 mb-4">
                            <div class="card p-3">
                                <div class="d-flex flex-column flex-lg-row flex-wrap justify-content-between align-items-lg-center align-items-start mb-2">
                                    <h3 class="text-dark fs-6 fw-medium mb-0"><?php echo $Candidate_model->get_master_name('master_skills', $skill->skills, 'skill_name'); ?></h3>
                                    <div>
                                    <!-- <a href="<?= base_url(); ?>/delete_common/<?php echo $skill->id;?>/can_skills_details/skill-details" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a> -->
                                    <a onclick="func_delete_skill('<?php echo $skill->id; ?>','can_skills_details','skill-details')" class="text-blue delete"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Delete</a>    
                                </div>
                                </div>
                                <ul class="ps-0 list-unstyled mb-0 d-flex">
                                    <li><i class="fa fa-star me-1 text-yellow" aria-hidden="true"></i></li>
                                    <li><i class="<?php if($skill->skill_level!=1){echo "fa fa-star";}else{ echo "fa fa-star-o";}?> me-1 text-yellow" aria-hidden="true"></i></li>
                                    <li><i class="<?php if($skill->skill_level==3){echo "fa fa-star";}else{ echo "fa fa-star-o";}?> me-1 text-yellow" aria-hidden="true"></i></li>
                                </ul>
                                <!-- <h6 class="mb-0 text-muted"><?php if($skill->skill_level==1){echo "Beginner";}elseif($skill->skill_level==2){echo "Intermediate";}elseif($skill->skill_level==3){echo "Advanced";}?></h6> -->
                            </div>
                        </div>
                        <?php }}?>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url(); ?>/experience-details" class="btn btn-prev me-2">Previous</a>
                            <div>
                                <?php
                                $next_but_status=$session->get('next_but_status');
                                
                                if(empty($skill_details) && (!isset($profile_page_view) && ($profile_page_view!=1)) && (!isset($edit_profile) && ($edit_profile!=1) )){
                                    ?>
                                <a href="<?= base_url(); ?>/work-sample-details" class="btn btn-outlined-blue me-2">Skip</a>
                                <?php } else{
                                     if(isset($edit_profile) && ($edit_profile==1)){
                                        $ses_data = [
                                            'updated_status'=> 1
                                        ];
                                        $session->set($ses_data);
                                        $intership_id=$session->get('intership_number');
                                        if(isset($next_but_status) && $next_but_status=='1'){
                                            if(empty($skill_details)){
                                            ?>
                                            <a href="<?= base_url(); ?>/work-sample-details" class="btn btn-outlined-blue me-2">Skip</a>
                                            <?php } else {?>
                                            <a href="<?= base_url(); ?>/work-sample-details" class="btn btn-prim">Next</a>
                                                <?php } } else{?>
                                        <a href="<?= base_url(); ?>/can-apply-for-internship/<?= $intership_id; ?>" class="btn btn-prim">Save & Continue</a>
                                        <?php } } elseif(isset($profile_page_view) && ($profile_page_view==1)){ ?>
                                        <a href="<?= base_url(); ?>/profile-details" class="btn btn-prim">Save</a>
                                    <?php  } else { 
                                        if(empty($skill_details)){ ?>
                                    <a href="<?= base_url(); ?>/work-sample-details" class="btn btn-outlined-blue me-2">Skip</a>
                                            <?php } else { ?>
                                            <a href="<?= base_url(); ?>/work-sample-details" class="btn btn-prim">Next</a>
                                    <?php } } } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>

    <script>

function func_delete_skill(id,tablename,refresh_page){
            swal({
                    title: "Are you sure ?",
                    text: "You want to remove this skill",
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

        $(document).ready(function() {
            $("#add_skill").change(function() {
                return validatetext_style('add_skill','add_skill_alert','Skill');
            });
            $("#add_skill_level").change(function() {
                return validatetext_style('add_skill_level','add_skill_level_alert','Level');
            });
        });
    $(document).ready(function(){
        $("#can_profile_skill_submit").click(function(){
 
        var add_skill_level    = validatetext_style('add_skill_level','add_skill_level_alert','Level');
        var add_skill    = validatetext_style('add_skill','add_skill_alert','Skill');
    
     if(add_skill==0 ||add_skill_level==0)
     {
         return false;
     }
 });
 });

    $('#add_skill').select2({
         matcher: function(params, data) {
         return matchStart(params, data);
        }
        });

        </script>
</body>

</html>