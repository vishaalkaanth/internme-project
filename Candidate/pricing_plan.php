<!DOCTYPE html>
<html>

<?php
require_once(APPPATH . "Views/Common/head.php");
$session = session();
// print_r($_SESSION);
// print_r($applied_internship_list);
use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
$login = $session->get('isLoggedIn');
//$this->load->view('common/head'); 
$userid    =    $session->get('userid');

?>

<body class="stickyFoot <?php if (!isset($login) && $login == '') {
                            echo 'resTop';
                        } ?>">

    <?php
    if (isset($login) && $login != '') {
        require_once(APPPATH . "Views/Common/header.php");
        require_once(APPPATH . "Views/Common/error_page.php");
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
                            <ul class="menu navbar-nav me-auto mb-lg-0 justify-content-sm-end justify-content-center align-items-center w-100 d-flex flex-wrap gap-3 gap-md-0" id="mainNav">
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
                                    <div class="input-group searchField headSearchAln headResSearch me-md-3 me-1">

                                        <input type="search" class="form-control rounded" placeholder="Search Internship" onkeypress="enterpress_search(event, this)" id="search_value" value="<?php
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
                                <li class="dropdown me-md-3 me-1">
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
    } ?>
    <section class="container-fluid px-0 py-10 bg-gray-100">
        <div class="container px-lg-20">
            <h3 class="mb-3 text-center fw-bolder text-secondary landingH3"></h3>
            <p class="text-gray-600 text-center mb-10 lh-base">
            </p>

            <div class="d-flex justify-content-center">
                <div class="pricingAlign">
                    <div class="row g-lg-10 g-5">


                        <div class="col-md-12">
                            <div class="d-flex h-100 align-items-center">

                                <div class="planCard w-100 d-flex flex-column flex-center rounded-3 bg-white border px-lg-15 px-5 py-10">


                                    <div class="mb-7 text-center">
                                        <h1 class="text-dark fs-2 ">
                                            Silver

                                        </h1>
                                        <span class="fs-2x fs-lg-3x fw-bolder text-secondary" data-kt-plan-price-month="39" data-kt-plan-price-annual="399">
                                            <img src="https://mean-median-mode.com/assets/media/img/icon_rupee.svg" alt="Rupee" class="svg-secondary me-2 mb-2" width="12">
                                            799.00</span> <span class="fs-7 fw-bold opacity-50 ">/
                                            <span data-kt-element="period">
                                                12 Months </span>
                                        </span>




                                        <div class=" fw-bolder fs-4 line_ position-relative">
                                        </div>
                                    </div>
                                    <?php if (isset($login) && $login != '') {
                                        $where = array('status' => '1', 'userid' => $userid);
                                        $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date'), $where);
                                        if ($payment_status_details->payment_package_type == 2 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                            <!-- <a class="btn btn-prim text-yellow fw-medium px-3 mt-4">P <img src="<?php echo base_url(); ?>/public/assets/img/arrow.svg" alt="View all" class="ms-2" width="13"></a> -->
                                        <?php  } 
                                        elseif ($payment_status_details->payment_package_type == 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>

                                      <?php }
                                        else {
                                            ?>
                                            <a href="<?php echo base_url(); ?>/candidate-payment/1" class="btn btn-prim text-yellow fw-medium px-3 mt-4">Choose Plan <img src="<?php echo base_url(); ?>/public/assets/img/arrow.svg" alt="View all" class="ms-2" width="13"></a>
                                        <?php
                                        }
                                    } else { ?>
                                        <a href="<?php echo base_url(); ?>/login" class="btn btn-prim text-yellow fw-medium px-3 mt-4">Choose Plan <img src="<?php echo base_url(); ?>/public/assets/img/arrow.svg" alt="View all" class="ms-2" width="13"></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="d-flex h-100 align-items-center">

                                <div class="planCard w-100 d-flex flex-column flex-center rounded-3 bg-white border px-lg-15 px-5 py-10">


                                    <div class="mb-7 text-center">
                                        <h1 class="text-dark fs-2 ">
                                            Gold

                                        </h1>
                                        <span class="fs-2x fs-lg-3x fw-bolder text-secondary" data-kt-plan-price-month="39" data-kt-plan-price-annual="399">
                                            <img src="https://mean-median-mode.com/assets/media/img/icon_rupee.svg" alt="Rupee" class="svg-secondary me-2 mb-2" width="12">
                                            1299.00</span> <span class="fs-7 fw-bold opacity-50 ">/
                                            <span data-kt-element="period">
                                                12 Months </span>
                                        </span>




                                        <div class=" fw-bolder fs-4 line_ position-relative">
                                        </div>
                                    </div>

                                    <?php if (isset($login) && $login != '') {
                                        $where = array('status' => '1', 'userid' => $userid);
                                        $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date'), $where);
                                        if ($payment_status_details->payment_package_type == 2 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                            <!-- <a class="btn btn-prim text-yellow fw-medium px-3 mt-4">P <img src="<?php echo base_url(); ?>/public/assets/img/arrow.svg" alt="View all" class="ms-2" width="13"></a> -->
                                        <?php  } 
                                        elseif ($payment_status_details->payment_package_type == 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>

                                      <?php }
                                        else {
                                            ?>
                                            <a href="<?php echo base_url(); ?>/candidate-payment/2" class="btn btn-prim text-yellow fw-medium px-3 mt-4">Choose Plan <img src="<?php echo base_url(); ?>/public/assets/img/arrow.svg" alt="View all" class="ms-2" width="13"></a>
                                        <?php
                                        }
                                    } else { ?>
                                        <a href="<?php echo base_url(); ?>/login" class="btn btn-prim text-yellow fw-medium px-3 mt-4">Choose Plan <img src="<?php echo base_url(); ?>/public/assets/img/arrow.svg" alt="View all" class="ms-2" width="13"></a>
                                    <?php } ?>
                                </div>
                            </div>
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