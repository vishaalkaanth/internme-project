<!DOCTYPE html>
<html class="h-100">

<?php
$session = session();
//print_r($_SESSION);
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>
<style>
    .floatTtl {
        top: -10px;
        background: #fff;
        padding: 0 5px;
    }
</style>

<body class="d-flex flex-column h-100">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>
    <!-- <div class="container my-4"> -->
    <div class="container content d-flex flex-column flex-column-fluid py-5 flex-grow-1">
        <div class="col-xl-12">
            <div class="d-flex justify-content-end mb-2">
                <a href="<?= base_url(); ?>/candidate-open-assessment" class="btn-outlined-blue px-3 py-1 f-13"><i class="fa fa-long-arrow-left me-1" aria-hidden="true"></i>Back</a>
            </div>
            <div class="card card-xl-stretch-50 mb-3 mb-xl-5 pt-3">
                <div class="p-4">
                    <div class="border rounded-3 d-flex flex-wrap p-3 pt-4 position-relative">
                        <h5 class="fw-semibold text-center text-blue fs-5 position-absolute floatTtl"><?php echo $assessment_data->assessment_name; ?> Assessment Result</h5>
                        <div class="col-md-3 d-flex flex-column">
                            <label class="text-muted mb-2">Assessment Name</label>
                            <span class="f-14 text-dark"><?php echo $assessment_data->assessment_name; ?></span>
                        </div>
                        <div class="col-md-3 d-flex flex-column">
                            <label class="text-muted mb-2">Assessment Pass Percentage</label>
                            <span class="text-dark f-14"><?php echo $assessment_data->assessment_pass_percentage; ?> %</span>
                        </div>
                        <div class="col-md-3 d-flex flex-column">
                            <label class="text-muted mb-2">Duration</label>
                            <span class="f-14 text-dark"><?php echo $assessment_data->assessment_duration; ?> Minutes</span>
                        </div>
                        <div class="col-md-3 d-flex flex-column">
                            <label class="text-muted mb-2">Total no.of Question</label>
                            <span class="f-14 text-dark"><?php echo $assessment_data->assessment_question_counts; ?></span>
                        </div>
                        <div class="col-md-12 d-flex flex-column mt-4">
                            <label class="text-muted mb-2">Assessment Descriprtion</label>
                            <span class="f-14 text-dark"><?php echo $assessment_data->assessment_description; ?></span>
                        </div>
                    </div>
                    <div class="">
                        <!-- <div class="col-6">
                            <div class="row mb-4">
                                <label class="col-lg-7 text-dark fw-bolder">Assessment Name</label>
                                <div class="col-lg-5">
                                    <span class="fs-7 text-gray-800"><?php echo $assessment_data->assessment_name; ?></span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-lg-7 text-dark fw-bolder">Assessment Pass Percentage</label>
                                <div class="col-lg-5 fv-row">
                                    <span class="text-gray-800 fs-7"><?php echo $assessment_data->assessment_pass_percentage; ?> %</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="row mb-4">
                                <label class="col-lg-7 text-dark fw-bolder fw-bolder">Duration</label>
                                <div class="col-lg-5">
                                    <span class="fs-7 text-gray-800"><?php echo $assessment_data->assessment_duration; ?> Minutes</span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-lg-7 text-dark fw-bolder">Total no.of Question</label>
                                <div class="col-lg-5 fv-row">
                                    <span class=" text-gray-800 fs-7"><?php echo $assessment_data->assessment_question_counts; ?></span>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div class="col-12">
                            <div class="row mb-4">
                                <label class="col-lg-12 text-dark fw-bolder fw-bolder">Assessment Descriprtion</label>
                                <div class="col-lg-12  mt-1">
                                    <span class="fs-7 text-gray-800"><?php echo $assessment_data->assessment_description; ?></span>
                                </div>
                            </div>
                        </div> -->

                        <div class="table-responsive mt-4">
                            <table class="table  table-row-bordered gy-3 gs-7 border rounded">
                                <thead style="background-color: #24337D; color:#fff">
                                    <tr class="f-14 text-gray-800 px-7 text-center">
                                        <th class="py-2">Sno</th>
                                        <th class="py-2">Date</th>
                                        <th class="py-2">Score</th>
                                        <th class="py-2">Percentage</th>
                                        <th class="py-2">Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($assessment_score as $ass) {  ?>
                                        <tr class="text-center">
                                            <td class="f-13"><?php echo $i; ?></td>
                                            <td class="f-13"><?php echo $newDate = date("d-M-Y H:i a", strtotime($ass->created_at)); ?></td>
                                            <td class="f-13"><?php echo $ass->result_assessment_score; ?></td>
                                            <td class="f-13"><?php echo $ass->result_assessment_percentage; ?>%</td>

                                            <td class="f-13">
                                                <?php if ($ass->result_status == "Pass") { ?>
                                                    <span class="badge fw-normal f-12 lh-1 bg-success"><?php echo $ass->result_status; ?></span>
                                                <?php } else { ?>
                                                    <span class="badge fw-normal f-12 lh-1 bg-danger"><?php echo $ass->result_status; ?></span>
                                                <?php } ?>
                                            </td>

                                        </tr>
                                    <?php $i++;
                                    } ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- </div> -->


    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
</body>

</html>