<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="">

    <?php require_once(APPPATH . "Views/Common/header.php"); 
    $session = session();
    $userid    =    $session->get('userid');
    $profile_complete_status=$session->get('profile_complete_status');
    $company_logo=$session->get('company_logo');
    $company_name=$session->get('company_name');
    $intership_profile=$session->get('intership_profile');
    $edit_profile=$session->get('edit_profile');
    $profile_page_view=$session->get('profile_page_view');
    // print_r($_SESSION);
    ?>

    <!----- Form ------>
    <section class="empProfile">
        <div class="d-flex flex-wrap">
            <?php require_once(APPPATH . "Views/Common/profile_side.php"); ?>
            <div class="col-12 col-lg-9 profileRt d-flex flex-column justify-content-center p-lg-5 py-5 px-4">
                 <!----- start Session Alert ------>
                 <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>
                <!----- End Session Alert ------>
           
                <div class="col-12 col-lg-10 align-self-center">
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
                <form action="<?= base_url(); ?>/update_can_work_sample" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data" >                 
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />    
                <input type="hidden" id="userid" name="userid"  value="<?php if(isset($userid)){echo $userid;}?>">
                <h2 class="fs-title text-blue  fw-medium text-center mb-5">Highlight Your Works</h2>
                    <div class="card p-4">
                    <div class="d-flex flex-wrap row">
                        <div class="col-md-6 form-group">
                            <label for="" class="form-label">Blog Link</label>
                            <div class="input-group mb-4">
                                <span class="input-group-text fillBg border-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/icon_link1.svg" alt="" width="14">
                                </span>
                                <input type="url" id="add_blog_link" name="add_blog_link" value="<?php if(isset($work_sample->blog_link)){echo $work_sample->blog_link;}?>" class="form-control filledBox border-0 py-2 f-14" placeholder="http://myblog.com">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="" class="form-label">GitHub Profile</label>
                            <div class="input-group mb-4">
                                <span class="input-group-text fillBg border-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/icon_link1.svg" alt="" width="14">
                                </span>
                                <input type="url" id="add_github" name="add_github" value="<?php if(isset($work_sample->github_profile)){echo $work_sample->github_profile;}?>" class="form-control filledBox border-0 py-2 f-14" placeholder="http://github.com/my_profile">
                            </div>
                        </div>
                        <div class="col-md-6 form-group selectField">
                            <label for="" class="form-label">Play Store Developer A/c (Public Link)</label>
                            <div class="input-group mb-4">
                                <span class="input-group-text fillBg border-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/icon_link1.svg" alt="" width="14">
                                </span>
                                <input type="url" id="add_play_store" name="add_play_store" value="<?php if(isset($work_sample->play_store_developer)){echo $work_sample->play_store_developer;}?>" class="form-control filledBox border-0 py-2 f-14" placeholder="http://play.google.com/store/apps">
                            </div>  
                        </div>
                        <div class="col-md-6 form-group selectField">
                            <label for="" class="form-label">Behance Portfolio Link</label>
                            <div class="input-group mb-4">
                                <span class="input-group-text fillBg border-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/icon_link1.svg" alt="" width="14">
                                </span>
                                <input type="url" id="add_behance_portfolio" name="add_behance_portfolio" value="<?php if(isset($work_sample->behance_portfolio_link)){echo $work_sample->behance_portfolio_link;}?>" class="form-control filledBox border-0 py-2 f-14" placeholder="http://behance.net/my_profile">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="" class="form-label">Kaggle Link </label>
                            <div class="input-group mb-4">
                                <span class="input-group-text fillBg border-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/icon_link1.svg" alt="" width="14">
                                </span>
                                <input type="url" id="add_kaggle_link" name="add_kaggle_link" value="<?php if(isset($work_sample->other_work_sample_link)){echo $work_sample->other_work_sample_link;}?>" class="form-control filledBox border-0 py-2 f-14" placeholder="http://www.kaggle.com">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="" class="form-label">Other Work Sample Link</label>
                            <div class="input-group mb-4">
                                <span class="input-group-text fillBg border-0">
                                    <img src="<?= base_url(); ?>/public/assets/img/icon_link1.svg" alt="" width="14">
                                </span>
                                <input type="url" id="add_other_work_sample" name="add_other_work_sample" value="<?php if(isset($work_sample->other_work_sample_link)){echo $work_sample->other_work_sample_link;}?>" class="form-control filledBox border-0 py-2 f-14" placeholder="http://myworksample.com">
                            </div>
                        </div>
                        <div class="col-md-12 form-group mb-4">
                            <label for="" class="form-label">Additional Details</label>
                            <textarea maxlength="500" style="height: 100px;" name="add_additional_details" id="add_additional_details" class="form-control filledBox border-0 py-2 f-14" placeholder="Add your accomplishments such as rewards, recognitions, test scores, certifications, etc. here. You may also add information such as seminars/workshops you have attended or any interests/hobbies you have pursued."><?php if(isset($work_sample->additional_details)){echo $work_sample->additional_details;}?></textarea>
                            <span id='remainingC'></span>
                        </div>
                    </div>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap mt-4">
                        <a href="<?= base_url(); ?>/skill-details" class="btn btn-prev me-2">Previous</a>
                        <?php
                                     if(isset($edit_profile) && ($edit_profile==1)){
                                        $ses_data = [
                                            'updated_status'=> 1
                                        ];
                                        $session->set($ses_data);
                                        $intership_id=$session->get('intership_number');
                                        ?>
                                         <div class=""><input type="submit" class="btn btn-prim float-end" id="can_work_sample_submit" value="Save & Continue" /></div>
                                        <!-- <a href="<?= base_url(); ?>/skill-details" class="btn btn-prim">Save & Continue</a> -->
                                        <?php  } elseif(isset($profile_page_view) && ($profile_page_view==1)){ ?>
                                            <div class=""><input type="submit" class="btn btn-prim float-end" id="can_work_sample_submit" value="Save" /></div>
                                    <?php  } else { ?>
                                            <div class=""><input type="submit" class="btn btn-prim float-end" id="can_work_sample_submit" value="Save" /></div>
                                    <?php } ?>
                        
                    </div>
            </form>
                </div>
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
<script>
              $('#add_additional_details').keyup(function() {

if (this.value.length > 500) {
    return false;
}
$("#remainingC").html("Remaining Characters : " + (500 - this.value.length));

});
</script>
</body>

</html>