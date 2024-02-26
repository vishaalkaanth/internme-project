<!DOCTYPE html>
<html>

<?php
$session = session();
//print_r($_SESSION);
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
use App\Models\Candidate_model;
$Candidate_model = new Candidate_model();
$userid    =    $session->get('userid');
?>

<body>

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>

    <img src="<?php echo base_url(); ?>/public/assets/img/assessment_banner.png" alt="banner" class="img-fluid d-none d-md-block">
    <img src="<?php echo base_url(); ?>/public/assets/img/banner_gmetrix_mobile.png" alt="banner" class="img-fluid d-md-none">
    <div class="container my-4">
        <h5 class="fw-semibold text-center text-blue fs-4 mb-3">Take Assessment</h5>
        <p class="text-center">Research has shown a strong correlation between people with high cognitive ability and job performance.</p>
       
        <div class="d-flex flex-wrap justify-content-center ">
        <?php $i=1;
        foreach ($assessment_list as $ass) {  ?>
  <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-4 px-2">
                <div class="card h-100">
                    <h6 class="mb-0 f-15 p-3 pb-0 fw-bolder lh-base" style="min-height: 60px;"><?php echo $ass->assessment_name; ?></h6>
                    <div class="d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between px-3">
                            <p class=" mt-2 me-2  f-13 fw-medium mb-2"><img src="<?php echo base_url(); ?>/public/assets/img/duration_assessment.svg" class="img-fluid me-2" alt="">Duration (mins)</p>
                            <p class=" mt-2 f-13 fw-medium text-end mb-2"><?php echo $ass->assessment_duration; ?></p>
                        </div>

                        <hr class="hrr" style="width:90%;text-align:center;margin-left:11px">

                        <div class="d-flex justify-content-between px-3">
                            <p class=" mt-2 me-2  f-13 fw-medium mb-2 "><img src="<?php echo base_url(); ?>/public/assets/img/pass.svg" class="img-fluid me-2" alt="">Pass Percentage</p>
                            <p class=" mt-2 f-13 fw-medium text-end mb-2"><?php echo $ass->assessment_pass_percentage; ?></p>
                        </div>

                        <hr class="hrr" style="width:90%;margin-left:11px">

                        <div class="d-flex  justify-content-between px-3">
                            <p class=" mt-2 me-2 mb-3 f-13 fw-medium"><img src="<?php echo base_url(); ?>/public/assets/img/question_no.svg" class="img-fluid me-2" alt="">No.of.Questions</p>
                            <p class=" mt-2 mb-3 f-13 fw-medium text-end"><?php echo $ass->assessment_question_counts; ?></p>
                        </div>

                        <div class="d-flex justify-content-center gap-2 mt-auto px-2 mb-3">
                            
                <?php 
                
                $where = array('result_status!=' => '', 'userid' => $userid, 'assessment_id' => $ass->assessment_id);
                $assessment_score = $Candidate_model->fetch_table_data('candidate_open_assessment', $where);
                if(!empty($assessment_score)){
                ?>
                <a href="<?= base_url(); ?>/assessment-data/<?php echo $ass->assessment_id; ?>" class="btn-sec btn-vilot-active text-white px-3 " type="button">Retake Assessment</a>
                            <a href="<?= base_url(); ?>/assessment-details/<?php echo $ass->assessment_id; ?>" class="btn-green btn-vilot-active text-white px-3 " type="button">View Score</a>
<?php }else{ ?>
    <a href="<?= base_url(); ?>/assessment-data/<?php echo $ass->assessment_id; ?>" class="btn-sec btn-vilot-active text-white px-3 " type="button">Take Assessment</a>
   
    <?php } ?>
                        </div>
                    </div>

                </div>
            </div>
            <!-- <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 mb-4 px-md-1 px-2">
                <div class="card h-100">
                    <h6 class="mb-2 f-15 px-2 mt-4 mb-1 fw-bolder"><?php echo $ass->assessment_name; ?></h6>
                    <div class="">
                        <div class="d-flex">
                            <p class=" mt-2 ms-2 me-2  f-13 fw-medium"><img src="<?php echo base_url(); ?>/public/assets/img/duration_assessment.svg" class="img-fluid me-2" alt="">Duration (mins)</p>
                            <p class=" mt-2 f-13 fw-medium text-end"><?php echo $ass->assessment_duration; ?></p>
                        </div>

                        <hr class="hrr" style="width:90%;text-align:center;margin-left:11px">

                        <div class="d-flex">
                            <p class=" mt-2 px-2 f-13 fw-medium "><img src="<?php echo base_url(); ?>/public/assets/img/pass.svg" class="img-fluid me-2" alt="">Pass Percentage</p>
                            <p class=" mt-2 px-2 f-13 fw-medium text-end"><?php echo $ass->assessment_pass_percentage; ?></p>
                        </div>

                        <hr class="hrr" style="width:90%;margin-left:11px">

                        <div class="d-flex">
                            <p class=" mt-2 px-2 mb-3 f-13 fw-medium"><img src="<?php echo base_url(); ?>/public/assets/img/question_no.svg" class="img-fluid me-2" alt="">No.of.Questions</p>
                            <p class=" mt-2 px-2 mb-2 f-13 fw-medium text-end"><?php echo $ass->assessment_question_counts; ?></p>
                        </div>

                        <div class="d-grid gap-2 mt-2">
                            <a href="<?= base_url(); ?>/assessment-data/<?php echo $ass->assessment_id; ?>" class="btn-sec btn-vilot-active " type="button">Take Assessment</a>
                        </div>
                    </div>

                </div>
            </div> -->
            <?php } ?>

       
        </div>

        <!-- <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">

                    <li class="page-item active">
                        <a class="page-link" href="#">
                            1 </a>
                    </li>
                    <li class="page-item ">
                        <a class="page-link" href="#">
                            2 </a>
                    </li>
                    <li class="page-item ">
                        <a class="page-link" href="#">
                            3 </a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">Next</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Last">
                            <span aria-hidden="true">Last</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div> -->



    </div>

    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
</body>

</html>