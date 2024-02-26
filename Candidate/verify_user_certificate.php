<!DOCTYPE html>
<html>

<?php

use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
$session = session();
// print_r($_SESSION);
// $userid          = $session->get('userid');
//$this->load->view('common/head'); 
$session = session();
$login = $session->get('isLoggedIn');
?>

<head>
    <meta charset="utf-8">
    <meta name="description" content="Intern me app is the leading internship portal in India, offering internships from leading MNC's and paid internships across India. All internships posted are from valid corporates and students are assessed under different parameters.">
    <meta name="keywords" content="Internship, Internme, Paid internship, Internship jobs, Chennai internship, Bengaluru internship, Startup internship, Coimbatore internship, Hyderabad internship, AI Internship, ML Internship, MBA internship, Marketing Internship, Sales Internship, HR Internship, Software Internship">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>verification | Internme accounts</title>
    <?php
    require_once(APPPATH . "Views/Common/head_seo.php");
    ?>
</head>

<body class="stickyFoot" style="overflow-x: auto;">

    <?php

    if (isset($login) && $login != '') {
        require_once(APPPATH . "Views/Common/header.php");
    } else {
    ?>
        <header>

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <?php require_once(APPPATH . "Views/Common/header_website.php"); ?>
            </nav>
            <!-- <div class="container bannerSec d-flex mt-5">
        <div class="col-md-7 bannerLt">
            <h1 class="text-white mb-4">We are The Brilliants In Terms of <span class="text-yellow">Connecting Internships!</span></h1>
            <p class="text-white">Hyperlocal platform connecting College students and MSME's with meaningful Internships.</p>
            <a href="#" class="btn-primary fw-medium mt-3 d-inline-block">Take Internship</a>
        </div>
        <div class="col-md-5">
            <img src="<?= base_url(); ?>/public/assets/img/bannerImg.png" alt="banner" class="img-fluid">
        </div>
    </div> -->
        </header>
    <?php
    }

    // use App\Models\Candidate_model; 

    ?>
    <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>

    <section class="py-4">
        <div class="container">
            <div class="card certifyCard align-items-center py-5 p-4">
                <div class="d-flex flex-wrap justify-content-center row w-100 h-100 align-items-center">
                    <div class="col-md-6 col-lg-5 form-group text-center pe-md-5 mb-4 mb-md-0">
                        <img src="<?= base_url(); ?>/public/assets/img/verify_illu.svg" alt="Verify Certificate" class="img-fluid" width="400">
                    </div>
                    <div class="col-md-6 col-lg-4 form-group">
                        <form method="post" action="<?php echo base_url('/verify-certificate'); ?>">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                            <label for="" class="form-label w-100 text-start">Certificate ID <span style="color:red;">*</span></label>
                            <input type="text" autocomplete="off" class="form-control border-0 filledBox f-14 mb-4" autofocus placeholder="Enter Certificate ID" id="add_certificate_id" name="add_certificate_id" maxlength="10">
                            <font style="color:#dd4b39;">
                                <div id="add_certificate_id_alert"></div>
                            </font>
                            <input type="submit" class="btn btn-prim px-3" id="reject_submit" value="Submit" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/footer_website.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>

    <script>
        function printDiv() {
            var printContents = document.getElementById('print_area').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
            //  var a = document.body.appendChild(
            //     document.createElement("a")
            // );
            // a.download = "export.pdf";
            // a.href = "data:text/html," + document.getElementById("print_area").innerHTML;
            //  a.click();
        }

        $(document).ready(function() {
            $("#add_certificate_id").keyup(function() {
                return validatenumberwithzero_style('add_certificate_id', 'add_certificate_id_alert', 'Certificate ID');
            });
        });
        $(document).ready(function() {
            $("#reject_submit").click(function() {
                var add_certificate_id = validatenumberwithzero_style('add_certificate_id', 'add_certificate_id_alert', 'Certificate ID');

                if (add_certificate_id == 0) {
                    return false;
                }
            });
        });
    </script>
</body>

</html>