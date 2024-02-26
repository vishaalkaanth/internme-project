<!DOCTYPE html>
<html class="h-100">

<?php
$session = session();
//print_r($_SESSION);
//$this->load->view('common/head'); 
$session = session();
$login = $session->get('isLoggedIn');
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="h-100 d-flex flex-column <?php if ($login) { ?>resTop<?php } ?>">


        <?php require_once(APPPATH . "Views/Common/header.php"); ?>


     <img src="<?php echo base_url(); ?>/public/assets/img/my_course_banner.png" alt="banner" class="img-fluid d-none d-md-block">
    <img src="<?php echo base_url(); ?>/public/assets/img/my_course_banner_mobile.png" alt="banner" class="img-fluid d-md-none"> 
    <div class="container my-4 flex-grow-1">
        <!--<h5 class="fw-semibold text-blue fs-4 mb-3">Welcome to GMetrix</h5>-->
        
        <?php if (!empty($course_details)) { ?>

            <h5 class="fw-semibold text-blue fs-4 mb-3 mt-4">Enrolled Courses</h5>
            <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-4 ">
                <div class="card p-2 h-100">
                    <?php 
// print_r($gmatrix_data[0]->CourseName);
                // echo $gmatrix_data[0]->CourseName;exit;    
                    if (trim($course_details->CourseName) == 'Word 2019 (MO-100)') {
                        $image_name = 'ico_word.svg';
                    } elseif (trim($course_details->CourseName) == 'MO-200 Excel 2019 Associate') {
                        $image_name = 'ico_excel.svg';
                    } elseif (trim($course_details->CourseName) == 'Python') {
                        $image_name = 'ico_python.svg';
                    } elseif (trim($course_details->CourseName) == 'Photoshop 2020-2023') {
                        $image_name = 'ico_photoshop.svg';
                    } elseif (trim($course_details->CourseName) == 'After Effects 2020-2023') {
                        $image_name = 'ico_aftereffects.svg';
                    } elseif (trim($course_details->CourseName) == 'Word 365 Apps (MO-110)') {
                        $image_name = 'ico_word.svg';
                    } elseif (trim($course_details->CourseName) == 'Excel 365 Apps (MO-210)') {
                        $image_name = 'ico_excel.svg';
                    } elseif (trim($course_details->CourseName) == 'Cisco Certified Support Technician: Cybersecurity') {
                        $image_name = 'ico_cisco_cyber.svg';
                    } elseif (trim($course_details->CourseName) == 'Word 2016 (77-725) (Lesson Layout)') {
                        $image_name = 'ico_word.svg';
                    }elseif (trim($course_details->CourseName) == 'Excel 2016 (77-727) (Lesson Layout)') {
                        $image_name = 'ico_excel.svg';
                    }elseif (trim($course_details->CourseName) == 'Certified Bookkeeping Professional') {
                        $image_name = 'ico_intuit.svg';
                    } ?>

                    <img src="<?php echo base_url(); ?>/public/assets/img/<?php echo $image_name; ?>" class="img-fluid" alt="">
                    <p class=" mt-2 mb-1 f-13"><img src="<?php echo base_url(); ?>/public/assets/img/calendar.svg" alt="banner" class="img-fluid me-2" width="12">Start Date: <?php echo $newDate = date("d-M-Y", strtotime($course_details->StartDate)); ?></p>
                    <h6 class="f-13 mb-2"><?php echo $course_details->CourseName; ?></h6>
             
                        <a href="<?= base_url(); ?>/gmetrix-view" target="_blank" class="btn-outlined-blue px-2 py-1 mx-auto mt-auto fw-bold">View Courses</a>
              
                </div>
            </div>

        <?php } ?>
    </div>

    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        function pricing_plan_subscription(val) {
            if (val == 2) {
                var title_val = "Upgrade your plan";
                var text_val = "Access to more internships requires a plan upgradation.";

            } else {
                var title_val = "Subscribe now";
                var text_val = "Access to paid features requires a subscription.";

            }

            swal({
                title: title_val,
                text: text_val,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.location.href = "<?php echo base_url('pricing-plan'); ?>";
                } else {
                    location.reload();
                }
            });
        }

        function email_alert() {

            var title_val = "Update your Email id!";
            var text_val = "Please make sure to update your email address, as it's essential for enrolling in a course.";

            swal({
                title: title_val,
                text: text_val,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.location.href = "<?php echo base_url('personal-details'); ?>";
                } else {
                    location.reload();
                }
            });
        }

        function login_alert() {

            var title_val = "Alert!";
            var text_val = "Please Login, Before Enroll For Course.";

            swal({
                title: title_val,
                text: text_val,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.location.href = "<?php echo base_url('login-gmetrix'); ?>";
                } else {
                    location.reload();
                }
            });
        }
    </script>
</body>

</html>