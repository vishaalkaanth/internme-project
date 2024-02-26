<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="stickyFoot">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>

    <!----- Form ------>
    <section class="container my-4">
        <div class="card flex-column flex-lg-row align-items-center justify-content-center py-lg-5 p-4 mb-4">
            <h6 class="text-blue fw-semibold mb-4 my-lg-0 mt-5 fs-4 ">Password Updated Successfully</h6>
            <img src="<?= base_url(); ?>/public/assets/img/password_changed.gif" alt="Password changed" class="img-fluid ms-lg-5" width="400">
        </div>
    </section>

    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>

</body>

</html>