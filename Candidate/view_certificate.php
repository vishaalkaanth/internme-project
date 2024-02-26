<!DOCTYPE html>
<html>

<?php
use App\Models\Candidate_model;
$Candidate_model = new Candidate_model();
$session = session();
$login = $session->get('isLoggedIn');
// print_r($_SESSION);
// $userid          = $session->get('userid');
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="stickyFoot" style="overflow-x: auto;">

<?php

if (isset($login) && $login != '') {
    require_once(APPPATH . "Views/Common/header.php");
} else {
?>
    <header>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div class="container d-flex flex-wrap flex-sm-nowrap justify-content-center">
                    <a class="navbar-brand py-0 mb-sm-0 mb-4" href="<?= base_url(); ?>/"><img src="<?= base_url(); ?>/public/assets/img/logo_blue.svg" alt="Logo" class="img-fluid" width="200"></a>
                    <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                    <div class="navbar-collapse" id="navbarSupportedContent">
                        <ul class="menu navbar-nav me-auto mb-lg-0 justify-content-sm-end justify-content-center align-items-center w-100" id="mainNav">
                            <!-- <li class="nav-item">
                            <a class="nav-link text-white active" href="#home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#service">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#about">Customers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#contact">Testi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#contact">Why Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#contact">Contact Us</a>
                        </li> -->
                            <li class="dropdown me-md-3">
                                <div class="input-group searchField headResSearch  headSearchAln me-3">

                                    <input type="search" class="form-control rounded" placeholder="Search Internship" id="search_value" onkeypress="enterpress_search(event, this)" value="<?php
                                                                                                                                                                                //show when search with keyword
                                                                                                                                                                                $session = session();
                                                                                                                                                                                $searched_keyword = $session->get('searched_keyword');
                                                                                                                                                                                if (isset($searched_keyword)) {

                                                                                                                                                                                    echo $searched_keyword;
                                                                                                                                                                                }
                                                                                                                                                                                ?>">
                                    <button class="btn btn-prim px-3" type="button" id="button-addon2" onclick="search_keyword(4)"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <!-- <span class="input-group-text">
                                        <img src="<?= base_url(); ?>/public/assets/img/search.svg" alt="Search" width="14">
                                    </span> -->
                                    <span id="search_alert" style="color: red;position: absolute;font-size: 10px;top: 36px;"></span>
                                </div>
                            </li>
                            <li class="dropdown me-sm-3 me-1">
                                <a href="#" class="btn-outlined-blue dropdown-toggle px-md-3 px-2" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Login</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="<?= base_url(); ?>/main_login/1">Candidate</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url(); ?>/main_login/2">Employer</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url(); ?>/facultylogin">Faculty</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="<?= base_url(); ?>/register/candidate" class="btn-prim dropdown-toggle py-2 px-md-3 px-2" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Register</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="<?= base_url(); ?>/register/candidate">Candidate</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url(); ?>/register/employer">Employer</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
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

    <?php 
    
   
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
        
            <!-- <a href="#" onclick="printDiv()" class="btn btn-prim float-end mb-4 px-3 download_certificate"><img src="<?= base_url(); ?>/public/assets/img/download_l.svg" alt="Download" class="me-2" width="13">Download</a> -->
   
            
            <div id="print_area" class="d-flex flex-column flex-wrap justify-content-end w-100">
            
                <div id="print_area" class="certificateBg align-self-center pe-3">
                
                    <div class="certificate float-end text-end py-4">
                        <div class="d-flex justify-content-end align-items-center mb-5">
                        <div class="companyLogo d-flex justify-content-center align-items-center mx-4 px-1">
                            <?php if(!empty($apply_internship_details->certificate_issued_logo)) { ?>
                                <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php if(!empty($apply_internship_details->certificate_issued_logo)) { echo $apply_internship_details->certificate_issued_logo; } ?>" alt="Company logo">
                            <?php }?>
                        </div>
                            <div>
                                <h3 class="text-uppercase text-blue-cer fw-normal">Certificate of Internship</h3>
                                <p class="text-uppercase text-blue-cer mb-0">THIS CERTIFICATE IS PROUDLY PRESENTED TO</p>
                            </div>
                        </div>
                        <h4 class="canCerName text-uppercase text-blue-cer fw-bold mb-4 pb-3 d-inline-block"><?php if(!empty($profile_personal->profile_full_name)) { echo ucfirst($profile_personal->profile_full_name); }  ?></h4>
                        <p>a student of <span class="text-blue-cer"><?php if(!empty($education_college_name)) { echo $education_college_name; } ?></span> pursuing <?php if(!empty($academic_courses)) { echo $academic_courses; } ?> <?php if(!empty($specialization)) { echo $specialization; } ?> has successfully completed 
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
                                                                                                echo $Candidate_model->get_master_name('master_profile', $internship_details[0]->profile,'profile');
                                                                                            } else {
                                                                                                echo $internship_details[0]->other_profile;
                                                                                            } ?></span> at <?php if(!empty($company_details->profile_company_name)) { echo $company_details->profile_company_name; }  ?>. </p>
                        <div class="d-flex justify-content-end align-items-center mt-5">
                            <div class="date text-center">
                                <p class="text-gray d-flex justify-content-center align-items-end border-bottom-dark pb-2 mb-2" style="height: 26px;"><?php if(!empty($apply_internship_details->certificate_issue_date)) { echo date("d-m-Y", strtotime($apply_internship_details->certificate_issue_date)); } ?></p>
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
                                <?php if(!empty($apply_internship_details->certificate_issued_sign)) { ?>
                                            <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php if(!empty($apply_internship_details->certificate_issued_sign)) { echo $apply_internship_details->certificate_issued_sign; }?>" alt="Signature">
                                <?php }?>
                                </p>
                                <p class="text-uppercase mb-0 label-certificate">Authorized Signatory</p>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <p class="mb-0 f-13"><b> <?php if(!empty($apply_internship_details->certificate_issued_id)) { ?> Certificate ID : <?php  echo $apply_internship_details->certificate_issued_id; ?><?php }?> </b></p>
                            <p class="mb-0 f-11 text-gray1">Verify this certificate through <a class="text-blue" href="https://internme.app/verify">internme.app/verify</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
 
</body>

</html>