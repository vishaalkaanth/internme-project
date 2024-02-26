<!DOCTYPE html>
<html>

<?php

use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
$session = session();
// print_r($_SESSION);
$userid          = $session->get('userid');
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>
<style>
    body {
        -webkit-print-color-adjust: exact !important;
        font-size: 11px;
        color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
</style>

<body class="stickyFoot" style="overflow-x: auto;">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>

    <?php

    $where_edu = array('status' => '1', 'userid' => $userid);
    $order_by = array('ordercolumn' => 'education_end_year', 'ordertype' => 'desc');
    $education_details = $Candidate_model->fetch_table_data_for_all_limit('can_education_details', $where_edu);
    if ($education_details[0]->education_college_name != 0) {
        $where_mas = array('id' => $education_details[0]->education_college_name);
        $education_college_name = $Candidate_model->get_master_commen_for_all('master_college', $where_mas, 'college_name');
    } else {
        $education_college_name = $education_details[0]->education_college_name_other;
    }

    if (isset($education_details[0]->education_course) && $education_details[0]->education_course != 0) {
        $academic_courses = $Candidate_model->get_master_name1('master_academic_courses', $education_details[0]->education_course, 'name');
    } else {
        $academic_courses =  $education_details[0]->education_course_other;
    }

    if (isset($education_details[0]->education_specialization) && $education_details[0]->education_specialization != 0) {
        $specialization =   $Candidate_model->get_master_name1('master_academic_branch', $education_details[0]->education_specialization, 'name');
    } else {
        $specialization =   $education_details[0]->education_specialization_other;
    }
    ?>

    <section class="py-4">
        <div class="container">
            <div class="d-flex justify-content-end align-items-center mb-4 gap-2">
                <a href="#" class="text-blue backBtn" onclick="previous()"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                <a href="#" onclick="printDiv('<?php echo $userid; ?>','<?php echo $apply_internship_details->certificate_issued_id; ?>')" class="btn btn-prim float-end px-3 download_certificate"><img src="<?= base_url(); ?>/public/assets/img/download_l.svg" alt="Download" class="me-2" width="13">Download</a>
                <a href="#" onclick="captureAndDownload('<?php echo $userid; ?>','<?php echo $apply_internship_details->certificate_issued_id; ?>')" class="btn btn-prim float-end px-3 download_certificate d-flex align-items-center"><i class="fa fa-linkedin-square me-2 fs-5" aria-hidden="true"></i>Share Certificate</a>
            </div>
            <div id="print_area" class="d-flex flex-column flex-wrap justify-content-end w-100">
                <div id="print_area" class="certificateBg align-self-center pe-3">
                    <div class="certificate float-end text-end py-4">
                        <div class="d-flex justify-content-end align-items-center mb-5">
                            <div class="companyLogo d-flex justify-content-center align-items-center mx-4 px-1">
                                <?php if (!empty($apply_internship_details->certificate_issued_logo)) { ?>
                                    <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php if (!empty($apply_internship_details->certificate_issued_logo)) {
                                                                                                            echo $apply_internship_details->certificate_issued_logo;
                                                                                                        } ?>" alt="Company logo">
                                <?php } ?>
                            </div>
                            <div>
                                <h3 class="text-uppercase text-blue-cer fw-normal">Certificate of Internship</h3>
                                <p class="text-uppercase text-blue-cer mb-0">THIS CERTIFICATE IS PROUDLY PRESENTED TO</p>
                            </div>
                        </div>
                        <h4 class="canCerName text-uppercase text-blue-cer fw-bold mb-4 pb-3 d-inline-block"><?php if (!empty($profile_personal->profile_full_name)) {
                                                                                                                    echo ucfirst($profile_personal->profile_full_name);
                                                                                                                }  ?></h4>
                        <p>student of <span class="text-blue-cer"><?php if (!empty($education_college_name)) {
                                                                        echo ucfirst($education_college_name);
                                                                    } ?></span> pursuing <?php if (!empty($academic_courses)) {
                                                                                                echo $academic_courses;
                                                                                            } ?> <?php if (!empty($specialization)) {
                                                                                                                                                                                echo $specialization;
                                                                                                                                                                            } ?> has successfully completed
                            <span class="text-blue-cer"><?php echo $internship_details[0]->internship_duration; ?> <?php if ($internship_details[0]->internship_duration_type != 1) {
                                                                                                                        if ($internship_details[0]->internship_duration == 1) {
                                                                                                                            echo "Month";
                                                                                                                        } else {
                                                                                                                            echo "Months";
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        if ($internship_details[0]->internship_duration == 1) {
                                                                                                                            echo "Week";
                                                                                                                        } else {
                                                                                                                            echo "Weeks";
                                                                                                                        }
                                                                                                                    } ?></span> of internship on <span class="text-blue-cer"><?php if ($internship_details[0]->profile != 0) {
                                                                                                                                                                                    echo $Candidate_model->get_master_name('master_profile', $internship_details[0]->profile, 'profile');
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo $internship_details[0]->other_profile;
                                                                                                                                                                                } ?></span> at <?php if (!empty($company_details->profile_company_name)) {
                                                                                                                                                                            echo $company_details->profile_company_name;
                                                                                                                                                                        }  ?>.
                        </p>
                        <div class="d-flex justify-content-end align-items-center mt-5">
                            <div class="date text-center">
                                <p class="text-gray d-flex justify-content-center align-items-end border-bottom-dark pb-2 mb-2" style="height: 26px;"><?php if (!empty($apply_internship_details->certificate_issue_date)) {
                                                                                                                                                            echo date("d-m-Y", strtotime($apply_internship_details->certificate_issue_date));
                                                                                                                                                        } ?></p>
                                <p class="text-uppercase mb-0 label-certificate">Date</p>
                            </div>
                            <!-- <div class="cerDefault d-flex flex-column justify-content-center align-items-center">
                                <p class="text-gray mb-0">23/11/2022</p>
                                <span class="border-bottom-dark pt-2 mb-2 w-100"></span>
                                <p class="text-uppercase mb-0">Date</p>
                            </div> -->
                            <div class="d-flex justify-content-center align-items-center mx-4 px-1">

                            </div>
                            <!-- <div class="cerDefault d-flex flex-column justify-content-center align-items-center">
                                <p class="text-gray mb-0"><img src="<?= base_url(); ?>/public/assets/img/sign.png" alt=""></p>
                                <span class="border-bottom-dark pt-2 mb-2 w-100"></span>
                                <p class="text-uppercase mb-0">Date</p>
                            </div> -->
                            <div class="signature text-center">
                                <p class="text-gray d-flex justify-content-center border-bottom-dark pb-2 mb-2">
                                    <?php if (!empty($apply_internship_details->certificate_issued_sign)) { ?>
                                        <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php if (!empty($apply_internship_details->certificate_issued_sign)) {
                                                                                                                echo $apply_internship_details->certificate_issued_sign;
                                                                                                            } ?>" alt="Signature">
                                    <?php } ?>
                                </p>
                                <p class="text-uppercase mb-0 label-certificate">Authorized Signatory</p>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <p class="mb-0 f-13"><b> <?php if (!empty($apply_internship_details->certificate_issued_id)) { ?> Certificate ID : <?php echo $apply_internship_details->certificate_issued_id; ?><?php } ?> </b></p>
                            <p class="mb-0 f-11 text-gray1">Verify this certificate through <a class="text-blue" href="https://internme.app/verify">internme.app/verify</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

    <script>
        function previous() {
            window.history.go(-1);
        }

        // function printDiv() {
        //     var css = '@page { size: landscape; }',
        //         head = document.head || document.getElementsByTagName('head')[0],
        //         style = document.createElement('style');

        //     style.type = 'text/css';
        //     style.media = 'print';

        //     if (style.styleSheet) {
        //         style.styleSheet.cssText = css;
        //     } else {
        //         style.appendChild(document.createTextNode(css));
        //     }

        //     head.appendChild(style);

        //     // window.print();
        //     var printContents = document.getElementById('print_area').innerHTML;
        //     var originalContents = document.body.innerHTML;

        //     document.body.innerHTML = printContents;

        //     window.print();

        //     document.body.innerHTML = originalContents;
        //     //  var a = document.body.appendChild(
        //     //     document.createElement("a")
        //     // );
        //     // a.download = "export.pdf";
        //     // a.href = "data:text/html," + document.getElementById("print_area").innerHTML;
        //     //  a.click();
        // }
    </script>
    <script>
        function printDiv(user_id,certificate_id) {
            // alert(certificate_id);
            // var baseUrl = '<?php echo base_url('public/assets/docs/uploads/candidate_certificate'); ?>' // Replace this with your actual base URL
            var fileName = user_id+certificate_id+'_certificate.png';
            html2canvas(document.getElementById('print_area')).then(function(canvas) {
                var baseUrl = '/assets/images/'; // Replace this with your actual base URL
            // var fileName = 'capture.png';
                var link = document.createElement('a');
                link.href = canvas.toDataURL();
                // link.download = 'capture.png';
                link.download = fileName;
                link.click();
            });
        }


        function captureAndDownload(user_id, certificate_id) {

            <?php $y= date("Y", strtotime($apply_internship_details->certificate_issue_date));
            $m= date("m", strtotime($apply_internship_details->certificate_issue_date));
            // $d= date("d", strtotime($apply_internship_details->certificate_issue_date));
            
            ?>

            var name = "Internship Completion certificate";
                            var organizationName = "InternMe";
                            var issueYear = <?php echo $y ?>;
                            var issueMonth = <?php echo $m ?>;
                           
                            var certId = certificate_id;
                            var certUrl =  '<?php echo base_url() ?>/view-certificate/'+certificate_id;

                           
                            var urlToOpen = 'https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME' +
                                '&name=' + encodeURIComponent(name) +
                                '&organizationName=' + encodeURIComponent(organizationName) +
                                '&issueYear=' + encodeURIComponent(issueYear) +
                                '&issueMonth=' + encodeURIComponent(issueMonth) +
                                '&certId=' + encodeURIComponent(certId) +
                                '&certUrl=' + encodeURIComponent(certUrl);

                            openNewTab(urlToOpen);
        }



        function captureAndDownload1(user_id, certificate_id) {
            html2canvas(document.getElementById('print_area')).then(function(canvas) {
                var link = document.createElement('a');
                var imageData = canvas.toDataURL('image/png');

                var csrf_val = $(".csrf").val();
                var csrf = "&csrf_test_name=" + csrf_val;

                var form_data1 = new FormData();
                form_data1.append("img_files", imageData);
                form_data1.append("csrf_test_name", csrf_val);
                // alert(imageData);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('save-cartificate'); ?>",
                    data: form_data1,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        var splitted_data = resp.split('^');
                        $(".csrf").val(splitted_data[0].trim());
                        if (splitted_data[1] != '') {
                            var name = "Internship Completion certificate";
                            var organizationName = "InternMe";
                            var issueYear = "2023";
                            var issueMonth = "12";
                            var expirationYear = "2024";
                            var expirationMonth = certificate_id;
                            var certId = "123";
                            var certUrl = splitted_data[1];

                            alert(splitted_data[1]);
                            var urlToOpen = 'https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME' +
                                '&name=' + encodeURIComponent(name) +
                                '&organizationName=' + encodeURIComponent(organizationName) +
                                '&issueYear=' + encodeURIComponent(issueYear) +
                                '&issueMonth=' + encodeURIComponent(issueMonth) +
                                '&expirationYear=' + encodeURIComponent(expirationYear) +
                                '&expirationMonth=' + encodeURIComponent(expirationMonth) +
                                '&certId=' + encodeURIComponent(certId) +
                                '&certUrl=' + encodeURIComponent(certUrl);

                            openNewTab(urlToOpen);
                        }
                       
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status, error);
                        console.log(xhr.responseText); // Log the responseText for more details
                    }
                });
            });
        }

        function openNewTab(url) {
            window.open(url, '_blank');
        }
    </script>

</body>

</html>